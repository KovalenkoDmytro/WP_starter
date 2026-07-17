<?php

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class Kovalenko_Admin_Change_Audit_Event_Logger
{
    /**
     * Post meta keys that are internal WordPress/browser-lock bookkeeping and never
     * meaningful to a site owner reviewing an audit trail.
     */
    private const IGNORED_META_KEYS = [
        'edit_lock',
        'edit_last',
        '_edit_lock',
        '_edit_last',
        '_wp_old_slug',
        '_wp_old_date',
        '_wp_trash_meta_status',
        '_wp_trash_meta_time',
        '_wp_desired_post_slug',
        '_pingme',
        '_encloseme',
    ];

    /**
     * Human-friendly overrides for meta keys whose raw name would otherwise be
     * confusing to a non-technical reader of the log (featured image, common SEO plugins).
     */
    private const META_KEY_LABELS = [
        '_thumbnail_id' => 'Featured image',
        '_yoast_wpseo_title' => 'SEO title (Yoast)',
        '_yoast_wpseo_metadesc' => 'SEO description (Yoast)',
        '_yoast_wpseo_focuskw' => 'SEO focus keyword (Yoast)',
        '_yoast_wpseo_canonical' => 'SEO canonical URL (Yoast)',
        'rank_math_title' => 'SEO title (Rank Math)',
        'rank_math_description' => 'SEO description (Rank Math)',
        'rank_math_focus_keyword' => 'SEO focus keyword (Rank Math)',
        '_aioseo_title' => 'SEO title (AIOSEO)',
        '_aioseo_description' => 'SEO description (AIOSEO)',
    ];

    /**
     * Post meta changes captured during the current request, keyed by post ID and then
     * by meta key, so multiple writes to the same key within one request collapse into
     * a single before/after pair instead of one log row per write.
     *
     * @var array<int, array<string, array{before: string, after: string, action: string}>>
     */
    private array $pending_meta_changes = [];

    /**
     * Actor captured at the moment the first meta change for a post is buffered,
     * so the flushed log entry attributes the change to the user who made it.
     *
     * @var array<int, array{id: int|null, label: string}>
     */
    private array $pending_meta_actors = [];

    public function register_hooks(): void
    {
        add_action('wp_login', [$this, 'log_login'], 10, 2);
        add_action('wp_logout', [$this, 'log_logout'], 10, 1);
        add_action('post_updated', [$this, 'log_post_update'], 10, 3);
        add_action('wp_insert_post', [$this, 'log_post_creation'], 10, 3);
        add_action('wp_trash_post', [$this, 'log_post_trash']);
        add_action('before_delete_post', [$this, 'log_post_deletion']);
        add_action('activated_plugin', [$this, 'log_plugin_activation']);
        add_action('deactivated_plugin', [$this, 'log_plugin_deactivation']);
        add_action('upgrader_process_complete', [$this, 'log_plugin_deletion'], 10, 2);
        add_action('add_post_meta', [$this, 'capture_post_meta_add'], 10, 3);
        add_action('update_post_meta', [$this, 'capture_post_meta_update'], 10, 4);
        add_action('delete_post_meta', [$this, 'capture_post_meta_delete'], 10, 4);
        add_action('set_object_terms', [$this, 'log_taxonomy_change'], 10, 6);
        add_action('shutdown', [$this, 'flush_pending_meta_changes']);
    }

    public function log_login(string $user_login, \WP_User $user): void
    {
        $this->record_activity(
            sprintf("User '%s' logged in.", $user_login),
            (int) $user->ID
        );
    }

    public function log_logout(int $user_id): void
    {
        $actor = $this->get_actor_from_user_id($user_id);
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);

        $this->record_activity(
            sprintf('%s logged out.', $actor_text),
            $actor['id']
        );
    }

    public function log_post_update(int $post_id, object $post_after, object $post_before): void
    {
        if (
            $post_after->post_status === 'auto-draft'
            || wp_is_post_revision($post_id)
            || wp_is_post_autosave($post_id)
        ) {
            return;
        }

        $changes = $this->detect_post_changes($post_before, $post_after);

        if ($changes['details'] === []) {
            // No core wp_posts column changed. Any meaningful change to this save
            // (custom fields, SEO fields, featured image, taxonomies) is captured
            // separately by the meta/taxonomy hooks, so skip the noisy generic fallback.
            return;
        }

        $actor = $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);

        $this->record_activity(
            sprintf(
                '%s updated post ID %d (%s): %s.',
                $actor_text,
                $post_id,
                get_permalink($post_id) ?: home_url(sprintf('/?p=%d', $post_id)),
                $changes['summary']
            ),
            $actor['id'],
            $changes['details']
        );
    }

    public function log_post_creation(int $post_id, \WP_Post $post, bool $update): void
    {
        if (
            $update
            || $post->post_status === 'auto-draft'
            || wp_is_post_revision($post_id)
            || wp_is_post_autosave($post_id)
        ) {
            return;
        }

        $actor = $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);
        $this->record_activity(
            sprintf(
                "%s created post ID %d (%s) with title '%s'.",
                $actor_text,
                $post_id,
                get_permalink($post_id) ?: home_url(sprintf('/?p=%d', $post_id)),
                $post->post_title
            ),
            $actor['id']
        );
    }

    public function log_post_trash(int $post_id): void
    {
        $actor = $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);
        $post = get_post($post_id);

        if (! $post instanceof \WP_Post) {
            return;
        }

        $this->record_activity(
            sprintf(
                "%s moved post ID %d with title '%s' to the trash.",
                $actor_text,
                $post_id,
                $post->post_title
            ),
            $actor['id']
        );
    }

    public function log_post_deletion(int $post_id): void
    {
        $actor = $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);
        $post = get_post($post_id);

        if (! $post instanceof \WP_Post || $post->post_status === 'trash') {
            return;
        }

        $this->record_activity(
            sprintf(
                "%s permanently deleted post ID %d (%s) with title '%s'.",
                $actor_text,
                $post_id,
                get_permalink($post_id) ?: home_url(sprintf('/?p=%d', $post_id)),
                $post->post_title
            ),
            $actor['id']
        );
    }

    public function log_plugin_activation(string $plugin): void
    {
        $actor = $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);
        $this->record_activity(
            sprintf(
                "%s activated plugin '%s'.",
                $actor_text,
                plugin_basename($plugin)
            ),
            $actor['id']
        );
    }

    public function log_plugin_deactivation(string $plugin): void
    {
        $actor = $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);
        $this->record_activity(
            sprintf(
                "%s deactivated plugin '%s'.",
                $actor_text,
                plugin_basename($plugin)
            ),
            $actor['id']
        );
    }

    public function log_plugin_deletion(object $upgrader, array $options): void
    {
        unset($upgrader);

        if (($options['type'] ?? '') !== 'plugin' || ($options['action'] ?? '') !== 'delete') {
            return;
        }

        $actor = $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);
        $deleted_plugins = isset($options['plugins']) && is_array($options['plugins'])
            ? implode(', ', array_map('plugin_basename', $options['plugins']))
            : 'Unknown plugins';

        $this->record_activity(
            sprintf(
                '%s deleted plugin(s): %s.',
                $actor_text,
                $deleted_plugins
            ),
            $actor['id']
        );
    }

    /**
     * Fires before WordPress inserts a brand-new post meta row, so `$meta_value` is
     * already the "after" state and there is no previous value to compare against.
     */
    public function capture_post_meta_add(int $object_id, string $meta_key, mixed $meta_value): void
    {
        $this->capture_post_meta_change($object_id, $meta_key, '', $meta_value, 'added');
    }

    /**
     * Fires before WordPress overwrites an existing post meta row, which means
     * `get_post_meta()` still returns the pre-change value at this point.
     */
    public function capture_post_meta_update(int $meta_id, int $object_id, string $meta_key, mixed $meta_value): void
    {
        unset($meta_id);

        $before_value = get_post_meta($object_id, $meta_key, true);
        $this->capture_post_meta_change($object_id, $meta_key, $before_value, $meta_value, 'updated');
    }

    /**
     * Fires before WordPress deletes post meta row(s), so the current stored value
     * is still readable as the "before" state.
     *
     * @param array<int, int> $meta_ids
     */
    public function capture_post_meta_delete(array $meta_ids, int $object_id, string $meta_key, mixed $meta_value): void
    {
        unset($meta_ids);

        $before_value = get_post_meta($object_id, $meta_key, true);
        $this->capture_post_meta_change($object_id, $meta_key, $before_value, $meta_value, 'deleted');
    }

    /**
     * Buffers a single post meta change instead of logging it immediately. WordPress
     * routinely fires several meta hooks for one form submission (e.g. saving a post
     * with ACF/SEO fields touches a dozen meta keys), so writing one log row per hook
     * call would flood the audit trail. Buffering per post ID and flushing once on
     * `shutdown` produces a single aggregated log entry per request instead.
     */
    private function capture_post_meta_change(int $post_id, string $meta_key, mixed $before, mixed $after, string $action): void
    {
        if (! $this->should_track_meta_key($meta_key)) {
            return;
        }

        $post = get_post($post_id);
        if (! $post instanceof \WP_Post || wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
            return;
        }

        $before_value = $this->normalize_meta_value_for_log($meta_key, $before);
        $after_value = $this->normalize_meta_value_for_log($meta_key, $after);

        if ($action !== 'deleted' && $before_value === $after_value) {
            return;
        }

        if (! isset($this->pending_meta_changes[$post_id])) {
            $this->pending_meta_changes[$post_id] = [];
            $this->pending_meta_actors[$post_id] = $this->get_current_actor();
        }

        // Keep the value captured at the start of the request as "before" so several
        // writes to the same key within one request still resolve to a single diff.
        $existing_before = $this->pending_meta_changes[$post_id][$meta_key]['before'] ?? $before_value;

        $this->pending_meta_changes[$post_id][$meta_key] = [
            'before' => $existing_before,
            'after' => $after_value,
            'action' => $action,
        ];
    }

    public function flush_pending_meta_changes(): void
    {
        foreach ($this->pending_meta_changes as $post_id => $changes) {
            if ($changes === []) {
                continue;
            }

            $this->flush_post_meta_changes((int) $post_id, $changes);
        }

        $this->pending_meta_changes = [];
        $this->pending_meta_actors = [];
    }

    /**
     * @param array<string, array{before: string, after: string, action: string}> $changes
     */
    private function flush_post_meta_changes(int $post_id, array $changes): void
    {
        $post = get_post($post_id);
        if (! $post instanceof \WP_Post) {
            return;
        }

        $actor = $this->pending_meta_actors[$post_id] ?? $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);

        $details = [];
        $changed_labels = [];

        foreach ($changes as $meta_key => $change) {
            $label = $this->humanize_meta_key($meta_key);
            $changed_labels[] = $label;
            $details[] = $this->build_change_detail(
                label: $label,
                before: $change['before'],
                after: $change['after']
            );
        }

        $summary = count($changed_labels) === 1
            ? sprintf('updated post meta: %s', $changed_labels[0])
            : sprintf('post metadata updated: %s', implode(', ', $changed_labels));

        $this->record_activity(
            sprintf(
                '%s updated post ID %d (%s): %s.',
                $actor_text,
                $post_id,
                get_permalink($post_id) ?: home_url(sprintf('/?p=%d', $post_id)),
                $summary
            ),
            $actor['id'],
            $details
        );
    }

    /**
     * @param array<int, int|string> $tt_ids
     * @param array<int, int|string> $old_tt_ids
     */
    public function log_taxonomy_change(int $object_id, array $terms, array $tt_ids, string $taxonomy, bool $append, array $old_tt_ids): void
    {
        unset($terms, $append);

        $post = get_post($object_id);
        if (! $post instanceof \WP_Post || wp_is_post_revision($object_id) || wp_is_post_autosave($object_id)) {
            return;
        }

        $before_names = $this->resolve_term_names_by_tt_ids($old_tt_ids, $taxonomy);
        $after_names = $this->resolve_term_names_by_tt_ids($tt_ids, $taxonomy);

        if ($before_names === $after_names) {
            return;
        }

        $actor = $this->get_current_actor();
        $actor_text = $this->format_actor_text($actor['label'], $actor['id']);
        $taxonomy_label = $this->resolve_taxonomy_label($taxonomy);

        $before_text = $before_names !== [] ? implode(', ', $before_names) : __('(none)', 'kovalenko-admin-change-audit');
        $after_text = $after_names !== [] ? implode(', ', $after_names) : __('(none)', 'kovalenko-admin-change-audit');

        $details = [
            $this->build_change_detail(
                label: $taxonomy_label,
                before: $before_text,
                after: $after_text
            ),
        ];

        $this->record_activity(
            sprintf(
                "%s updated post ID %d (%s) %s: from '%s' to '%s'.",
                $actor_text,
                $object_id,
                get_permalink($object_id) ?: home_url(sprintf('/?p=%d', $object_id)),
                strtolower($taxonomy_label),
                $before_text,
                $after_text
            ),
            $actor['id'],
            $details
        );
    }

    /**
     * @param array<int, int|string> $tt_ids
     *
     * @return list<string>
     */
    private function resolve_term_names_by_tt_ids(array $tt_ids, string $taxonomy): array
    {
        $names = [];

        foreach ($tt_ids as $tt_id) {
            $term = get_term_by('term_taxonomy_id', (int) $tt_id, $taxonomy);

            if ($term instanceof \WP_Term) {
                $names[] = $term->name;
            }
        }

        sort($names);

        return $names;
    }

    private function resolve_taxonomy_label(string $taxonomy): string
    {
        $taxonomy_object = get_taxonomy($taxonomy);

        if ($taxonomy_object instanceof \WP_Taxonomy && $taxonomy_object->labels->singular_name !== '') {
            return $taxonomy_object->labels->singular_name;
        }

        return ucfirst($taxonomy);
    }

    private function should_track_meta_key(string $meta_key): bool
    {
        if (in_array($meta_key, self::IGNORED_META_KEYS, true)) {
            return false;
        }

        if (str_starts_with($meta_key, '_oembed_')) {
            return false;
        }

        if (str_starts_with($meta_key, '_wp_attachment_metadata')) {
            return false;
        }

        return true;
    }

    private function humanize_meta_key(string $meta_key): string
    {
        if (isset(self::META_KEY_LABELS[$meta_key])) {
            return self::META_KEY_LABELS[$meta_key];
        }

        $label = str_replace(['_', '-'], ' ', ltrim($meta_key, '_'));
        $label = trim($label);

        return $label !== '' ? ucwords($label) : $meta_key;
    }

    private function normalize_meta_value_for_log(string $meta_key, mixed $value): string
    {
        if ($meta_key === '_thumbnail_id') {
            return $this->resolve_attachment_label($value);
        }

        if (is_array($value) || is_object($value)) {
            $encoded = wp_json_encode($value);

            return is_string($encoded) ? trim($encoded) : '';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return trim((string) $value);
    }

    private function resolve_attachment_label(mixed $value): string
    {
        $attachment_id = (int) $value;

        if ($attachment_id <= 0) {
            return '';
        }

        $title = get_the_title($attachment_id);

        return $title !== '' ? sprintf('%s (#%d)', $title, $attachment_id) : sprintf('#%d', $attachment_id);
    }

    /**
     * @param list<array{label: string, before: string, after: string}>|null $details
     */
    private function record_activity(string $message, ?int $user_id = null, ?array $details = null): void
    {
        kovalenko_admin_change_audit_record_activity($message, $user_id, $details);
    }

    private function get_current_actor(): array
    {
        $current_user = wp_get_current_user();
        if ($current_user instanceof \WP_User && $current_user->exists() && $current_user->user_login !== '') {
            return [
                'id' => (int) $current_user->ID,
                'label' => $current_user->user_login,
            ];
        }

        return [
            'id' => null,
            'label' => $this->get_system_actor_label(),
        ];
    }

    private function get_actor_from_user_id(int $user_id): array
    {
        if ($user_id > 0) {
            $user = get_userdata($user_id);
            if ($user instanceof \WP_User && $user->user_login !== '') {
                return [
                    'id' => $user_id,
                    'label' => $user->user_login,
                ];
            }

            return [
                'id' => $user_id,
                'label' => sprintf('Deleted user #%d', $user_id),
            ];
        }

        return [
            'id' => null,
            'label' => $this->get_system_actor_label(),
        ];
    }

    private function get_system_actor_label(): string
    {
        return __('System', 'kovalenko-admin-change-audit');
    }

    private function format_actor_text(string $actor_label, ?int $user_id): string
    {
        if ($user_id === null) {
            return $actor_label;
        }

        return sprintf("User '%s'", $actor_label);
    }

    /**
     * @return array{
     *     summary: string,
     *     details: list<array{label: string, before: string, after: string}>
     * }
     */
    private function detect_post_changes(object $post_before, object $post_after): array
    {
        $summary_parts = [];
        $details = [];

        if ($post_before->post_title !== $post_after->post_title) {
            $summary_parts[] = sprintf(
                "title changed from '%s' to '%s'",
                $post_before->post_title,
                $post_after->post_title
            );
            $details[] = $this->build_change_detail(
                label: __('Title', 'kovalenko-admin-change-audit'),
                before: (string) $post_before->post_title,
                after: (string) $post_after->post_title
            );
        }

        if ($post_before->post_content !== $post_after->post_content) {
            $summary_parts[] = 'content updated';
            $details[] = $this->build_change_detail(
                label: __('Content', 'kovalenko-admin-change-audit'),
                before: $this->prepare_post_content_value((string) $post_before->post_content),
                after: $this->prepare_post_content_value((string) $post_after->post_content)
            );
        }

        if (($post_before->post_excerpt ?? '') !== ($post_after->post_excerpt ?? '')) {
            $summary_parts[] = 'excerpt updated';
            $details[] = $this->build_change_detail(
                label: __('Excerpt', 'kovalenko-admin-change-audit'),
                before: $this->prepare_post_content_value((string) ($post_before->post_excerpt ?? '')),
                after: $this->prepare_post_content_value((string) ($post_after->post_excerpt ?? ''))
            );
        }

        if (($post_before->post_status ?? '') !== ($post_after->post_status ?? '')) {
            $summary_parts[] = sprintf(
                "status changed from '%s' to '%s'",
                $post_before->post_status,
                $post_after->post_status
            );
            $details[] = $this->build_change_detail(
                label: __('Status', 'kovalenko-admin-change-audit'),
                before: (string) ($post_before->post_status ?? ''),
                after: (string) ($post_after->post_status ?? '')
            );
        }

        if (($post_before->post_name ?? '') !== ($post_after->post_name ?? '')) {
            $summary_parts[] = sprintf(
                "slug changed from '%s' to '%s'",
                $post_before->post_name,
                $post_after->post_name
            );
            $details[] = $this->build_change_detail(
                label: __('Slug', 'kovalenko-admin-change-audit'),
                before: (string) ($post_before->post_name ?? ''),
                after: (string) ($post_after->post_name ?? '')
            );
        }

        if (($post_before->menu_order ?? 0) !== ($post_after->menu_order ?? 0)) {
            $summary_parts[] = 'menu order updated';
            $details[] = $this->build_change_detail(
                label: __('Menu order', 'kovalenko-admin-change-audit'),
                before: (string) ($post_before->menu_order ?? 0),
                after: (string) ($post_after->menu_order ?? 0)
            );
        }

        if (($post_before->post_parent ?? 0) !== ($post_after->post_parent ?? 0)) {
            $summary_parts[] = 'parent updated';
            $details[] = $this->build_change_detail(
                label: __('Parent', 'kovalenko-admin-change-audit'),
                before: $this->resolve_post_parent_label((int) ($post_before->post_parent ?? 0)),
                after: $this->resolve_post_parent_label((int) ($post_after->post_parent ?? 0))
            );
        }

        if (($post_before->post_author ?? 0) !== ($post_after->post_author ?? 0)) {
            $summary_parts[] = 'author updated';
            $details[] = $this->build_change_detail(
                label: __('Author', 'kovalenko-admin-change-audit'),
                before: $this->resolve_author_label((int) ($post_before->post_author ?? 0)),
                after: $this->resolve_author_label((int) ($post_after->post_author ?? 0))
            );
        }

        if (($post_before->comment_status ?? '') !== ($post_after->comment_status ?? '')) {
            $summary_parts[] = 'comment settings updated';
            $details[] = $this->build_change_detail(
                label: __('Comments', 'kovalenko-admin-change-audit'),
                before: (string) ($post_before->comment_status ?? ''),
                after: (string) ($post_after->comment_status ?? '')
            );
        }

        if (($post_before->ping_status ?? '') !== ($post_after->ping_status ?? '')) {
            $summary_parts[] = 'ping settings updated';
            $details[] = $this->build_change_detail(
                label: __('Pings', 'kovalenko-admin-change-audit'),
                before: (string) ($post_before->ping_status ?? ''),
                after: (string) ($post_after->ping_status ?? '')
            );
        }

        return [
            'summary' => $summary_parts !== []
                ? implode(', ', $summary_parts)
                : 'post settings or metadata updated',
            'details' => $details,
        ];
    }

    /**
     * @return array{label: string, before: string, after: string}
     */
    private function build_change_detail(string $label, string $before, string $after): array
    {
        return [
            'label' => $label,
            'before' => trim($before),
            'after' => trim($after),
        ];
    }

    private function prepare_post_content_value(string $value): string
    {
        $normalized = trim(str_replace(["\r\n", "\r"], "\n", $value));
        $plain_text = trim(wp_strip_all_tags($normalized, false));

        if ($plain_text !== '') {
            $normalized = $plain_text;
        }

        $collapsed = preg_replace("/\n{3,}/", "\n\n", $normalized);

        return is_string($collapsed) ? trim($collapsed) : $normalized;
    }

    private function resolve_post_parent_label(int $post_id): string
    {
        if ($post_id <= 0) {
            return '';
        }

        $post = get_post($post_id);

        if (! $post instanceof \WP_Post) {
            return (string) $post_id;
        }

        return $post->post_title !== ''
            ? sprintf('%s (#%d)', $post->post_title, $post_id)
            : sprintf('#%d', $post_id);
    }

    private function resolve_author_label(int $user_id): string
    {
        if ($user_id <= 0) {
            return '';
        }

        $user = get_userdata($user_id);

        if (! $user instanceof \WP_User) {
            return (string) $user_id;
        }

        return $user->user_login;
    }
}
