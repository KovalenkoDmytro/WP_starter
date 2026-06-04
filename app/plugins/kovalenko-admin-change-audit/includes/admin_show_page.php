<?php

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

function kovalenko_admin_change_audit_normalize_filters(array $source): array
{
    $allowed_order_columns = ['id', 'user', 'activity', 'ip_address', 'created_at'];
    $allowed_order = ['ASC', 'DESC'];

    $order_by = sanitize_key((string) ($source['order_by'] ?? 'created_at'));
    if (! in_array($order_by, $allowed_order_columns, true)) {
        $order_by = 'created_at';
    }

    $order = strtoupper(sanitize_text_field((string) ($source['order'] ?? 'DESC')));
    if (! in_array($order, $allowed_order, true)) {
        $order = 'DESC';
    }

    return [
        'start_date' => sanitize_text_field((string) ($source['start_date'] ?? '')),
        'end_date' => sanitize_text_field((string) ($source['end_date'] ?? '')),
        'username' => sanitize_text_field((string) ($source['username'] ?? '')),
        'search' => sanitize_text_field((string) ($source['search'] ?? '')),
        'ip_address' => sanitize_text_field((string) ($source['ip_address'] ?? '')),
        'order_by' => $order_by,
        'order' => $order,
        'paged' => max(1, absint($source['paged'] ?? 1)),
        'per_page' => 25,
    ];
}

function kovalenko_admin_change_audit_admin_page(): void
{
    if (! kovalenko_admin_change_audit()->can_view_logs()) {
        wp_die(esc_html__('You are not allowed to view these logs.', 'kovalenko-admin-change-audit'));
    }

    $page_url = kovalenko_admin_change_audit()->get_admin_page_url();
    $show_unlock = kovalenko_admin_change_audit()->is_password_required() && ! kovalenko_admin_change_audit()->is_screen_unlocked();

    echo '<div class="wrap wp-activity-logger-shell">';
    echo '<h1>' . esc_html__('Activity Logs', 'kovalenko-admin-change-audit') . '</h1>';

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin notice flag from the current screen URL.
    $has_unlock_error = isset($_GET['kovalenko_admin_change_audit_access_error']) && sanitize_text_field(wp_unslash($_GET['kovalenko_admin_change_audit_access_error'])) === '1';
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin notice flag from the current screen URL.
    $security_saved = isset($_GET['kovalenko_admin_change_audit_security_saved']) ? sanitize_text_field(wp_unslash($_GET['kovalenko_admin_change_audit_security_saved'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin notice flag from the current screen URL.
    $security_error = isset($_GET['kovalenko_admin_change_audit_security_error']) ? sanitize_text_field(wp_unslash($_GET['kovalenko_admin_change_audit_security_error'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin notice flag from the current screen URL.
    $timezone_saved = isset($_GET['kovalenko_admin_change_audit_timezone_saved']) ? sanitize_text_field(wp_unslash($_GET['kovalenko_admin_change_audit_timezone_saved'])) : '';
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only admin notice flag from the current screen URL.
    $timezone_error = isset($_GET['kovalenko_admin_change_audit_timezone_error']) ? sanitize_text_field(wp_unslash($_GET['kovalenko_admin_change_audit_timezone_error'])) : '';

    if ($show_unlock) {
        kovalenko_admin_change_audit_render_unlock_screen($page_url, $has_unlock_error);
        echo '</div>';

        return;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only filter values are accepted from the current admin screen URL.
    $filters = kovalenko_admin_change_audit_normalize_filters(wp_unslash($_GET));
    $payload = kovalenko_admin_change_audit_get_logs_payload($filters);

    kovalenko_admin_change_audit_render_dashboard($page_url, $filters, $payload, $security_saved, $security_error, $timezone_saved, $timezone_error);
    echo '</div>';
}

function kovalenko_admin_change_audit_render_unlock_screen(string $page_url, bool $has_error): void
{
    echo '<div class="wp-activity-logger-lock-card">';
    echo '<p class="wp-activity-logger-eyebrow">' . esc_html__('Protected screen', 'kovalenko-admin-change-audit') . '</p>';
    echo '<h2>' . esc_html__('Unlock the log viewer', 'kovalenko-admin-change-audit') . '</h2>';
    echo '<p>' . esc_html__('This viewer is protected with an access password when one is configured.', 'kovalenko-admin-change-audit') . '</p>';

    if ($has_error) {
        echo '<div class="notice notice-error inline"><p>' . esc_html__('The password did not match. Try again.', 'kovalenko-admin-change-audit') . '</p></div>';
    }

    echo '<form method="post" action="' . esc_url($page_url) . '" class="wp-activity-logger-unlock-form">';
    wp_nonce_field('kovalenko_admin_change_audit_unlock');
    echo '<input type="hidden" name="kovalenko_admin_change_audit_unlock" value="1">';
    echo '<label for="wp-activity-logger-password">' . esc_html__('Access password', 'kovalenko-admin-change-audit') . '</label>';
    echo '<input id="wp-activity-logger-password" name="kovalenko_admin_change_audit_password" type="password" class="regular-text" autocomplete="current-password" required>';
    echo '<button type="submit" class="button button-primary">' . esc_html__('Unlock logs', 'kovalenko-admin-change-audit') . '</button>';
    echo '</form>';
    echo '</div>';
}

function kovalenko_admin_change_audit_render_dashboard(
    string $page_url,
    array $filters,
    array $payload,
    string $security_saved,
    string $security_error,
    string $timezone_saved,
    string $timezone_error
): void
{
    $metrics = $payload['metrics'];
    $pagination = $payload['pagination'];
    $initial_payload = wp_json_encode($payload);

    echo '<div class="wp-activity-logger-app" data-page-url="' . esc_url($page_url) . '">';

    echo '<section class="wp-activity-logger-hero">';
    echo '<div>';
    echo '<p class="wp-activity-logger-eyebrow">' . esc_html__('Owner activity monitor', 'kovalenko-admin-change-audit') . '</p>';
    echo '<h2>' . esc_html__('A cleaner audit trail for client work', 'kovalenko-admin-change-audit') . '</h2>';
    echo '<p>' . esc_html__('Track edits, plugin changes, and login activity from a dedicated Activity Logs screen.', 'kovalenko-admin-change-audit') . '</p>';
    echo '</div>';
    echo '<div class="wp-activity-logger-actions">';
    echo '<a class="button button-secondary" href="' . esc_url($page_url) . '">' . esc_html__('Reset view', 'kovalenko-admin-change-audit') . '</a>';
    echo '<button type="button" class="button button-primary" data-refresh-now>' . esc_html__('Refresh now', 'kovalenko-admin-change-audit') . '</button>';
    echo '</div>';
    echo '</section>';

    kovalenko_admin_change_audit_render_security_panel($page_url, $security_saved, $security_error);
    kovalenko_admin_change_audit_render_timezone_panel($page_url, $timezone_saved, $timezone_error);

    echo '<section class="wp-activity-logger-stats">';
    kovalenko_admin_change_audit_render_stat_card(__('Total logs', 'kovalenko-admin-change-audit'), (string) $metrics['totalLogs'], 'total-logs');
    kovalenko_admin_change_audit_render_stat_card(__('Users seen', 'kovalenko-admin-change-audit'), (string) $metrics['uniqueUsers'], 'unique-users');
    kovalenko_admin_change_audit_render_stat_card(__('IP addresses', 'kovalenko-admin-change-audit'), (string) $metrics['uniqueIps'], 'unique-ips');
    kovalenko_admin_change_audit_render_stat_card(__('Latest activity', 'kovalenko-admin-change-audit'), (string) $metrics['latestActivity'], 'latest-activity');
    echo '</section>';

    echo '<section class="wp-activity-logger-panel">';
    echo '<form method="get" action="' . esc_url($page_url) . '" class="wp-activity-logger-filters" data-filter-form>';
    echo '<div class="wp-activity-logger-field">';
    echo '<label for="wpal-start-date">' . esc_html__('From', 'kovalenko-admin-change-audit') . '</label>';
    echo '<input id="wpal-start-date" type="date" name="start_date" value="' . esc_attr($filters['start_date']) . '">';
    echo '</div>';
    echo '<div class="wp-activity-logger-field">';
    echo '<label for="wpal-end-date">' . esc_html__('To', 'kovalenko-admin-change-audit') . '</label>';
    echo '<input id="wpal-end-date" type="date" name="end_date" value="' . esc_attr($filters['end_date']) . '">';
    echo '</div>';
    echo '<div class="wp-activity-logger-field">';
    echo '<label for="wpal-username">' . esc_html__('Username', 'kovalenko-admin-change-audit') . '</label>';
    echo '<input id="wpal-username" type="text" name="username" value="' . esc_attr($filters['username']) . '" placeholder="' . esc_attr__('Filter by username', 'kovalenko-admin-change-audit') . '">';
    echo '</div>';
    echo '<div class="wp-activity-logger-field wp-activity-logger-field-wide">';
    echo '<label for="wpal-search">' . esc_html__('Search', 'kovalenko-admin-change-audit') . '</label>';
    echo '<input id="wpal-search" type="text" name="search" value="' . esc_attr($filters['search']) . '" placeholder="' . esc_attr__('Search activity text or IP address', 'kovalenko-admin-change-audit') . '">';
    echo '</div>';
    echo '<div class="wp-activity-logger-field">';
    echo '<label for="wpal-ip-address">' . esc_html__('IP address', 'kovalenko-admin-change-audit') . '</label>';
    echo '<input id="wpal-ip-address" type="text" name="ip_address" value="' . esc_attr($filters['ip_address']) . '" placeholder="' . esc_attr__('Contains...', 'kovalenko-admin-change-audit') . '">';
    echo '</div>';
    echo '<div class="wp-activity-logger-field">';
    echo '<label for="wpal-order-by">' . esc_html__('Sort by', 'kovalenko-admin-change-audit') . '</label>';
    echo '<select id="wpal-order-by" name="order_by">';
    kovalenko_admin_change_audit_render_select_option('created_at', $filters['order_by'], __('Newest activity', 'kovalenko-admin-change-audit'));
    kovalenko_admin_change_audit_render_select_option('id', $filters['order_by'], __('Log ID', 'kovalenko-admin-change-audit'));
    kovalenko_admin_change_audit_render_select_option('user', $filters['order_by'], __('Username', 'kovalenko-admin-change-audit'));
    kovalenko_admin_change_audit_render_select_option('activity', $filters['order_by'], __('Activity text', 'kovalenko-admin-change-audit'));
    kovalenko_admin_change_audit_render_select_option('ip_address', $filters['order_by'], __('IP address', 'kovalenko-admin-change-audit'));
    echo '</select>';
    echo '</div>';
    echo '<div class="wp-activity-logger-field">';
    echo '<label for="wpal-order">' . esc_html__('Direction', 'kovalenko-admin-change-audit') . '</label>';
    echo '<select id="wpal-order" name="order">';
    kovalenko_admin_change_audit_render_select_option('DESC', $filters['order'], __('Descending', 'kovalenko-admin-change-audit'));
    kovalenko_admin_change_audit_render_select_option('ASC', $filters['order'], __('Ascending', 'kovalenko-admin-change-audit'));
    echo '</select>';
    echo '</div>';
    echo '<div class="wp-activity-logger-field">';
    echo '<label for="wpal-refresh-interval">' . esc_html__('Auto refresh', 'kovalenko-admin-change-audit') . '</label>';
    echo '<select id="wpal-refresh-interval" name="refresh_interval" data-refresh-interval>';
    echo '<option value="0">' . esc_html__('Off', 'kovalenko-admin-change-audit') . '</option>';
    echo '<option value="15">' . esc_html__('Every 15s', 'kovalenko-admin-change-audit') . '</option>';
    echo '<option value="30" selected>' . esc_html__('Every 30s', 'kovalenko-admin-change-audit') . '</option>';
    echo '<option value="60">' . esc_html__('Every 60s', 'kovalenko-admin-change-audit') . '</option>';
    echo '</select>';
    echo '</div>';
    echo '<div class="wp-activity-logger-filter-actions">';
    echo '<button type="submit" class="button button-primary">' . esc_html__('Apply filters', 'kovalenko-admin-change-audit') . '</button>';
    echo '<a class="button button-secondary" href="' . esc_url($page_url) . '">' . esc_html__('Clear filters', 'kovalenko-admin-change-audit') . '</a>';
    echo '</div>';
    echo '</form>';

    echo '<div class="wp-activity-logger-meta-bar">';
    echo '<div class="wp-activity-logger-status"><span class="wp-activity-logger-status-dot"></span><span data-refresh-status>' . esc_html__('Live refresh ready', 'kovalenko-admin-change-audit') . '</span></div>';
    echo '<div class="wp-activity-logger-meta-right">';
    echo '<span data-last-updated>' . esc_html__('Waiting for the next refresh...', 'kovalenko-admin-change-audit') . '</span>';
    echo '<span class="wp-activity-logger-page-count" data-page-count>';
    echo esc_html(
        sprintf(
            /* translators: 1: current page number, 2: total page count */
            __('Page %1$d of %2$d', 'kovalenko-admin-change-audit'),
            $pagination['currentPage'],
            $pagination['totalPages']
        )
    );
    echo '</span>';
    echo '</div>';
    echo '</div>';

    echo '<div class="wp-activity-logger-table-wrap">';
    echo '<table class="wp-activity-logger-table widefat striped">';
    echo '<thead><tr>';
    echo '<th>' . esc_html__('ID', 'kovalenko-admin-change-audit') . '</th>';
    echo '<th>' . esc_html__('User', 'kovalenko-admin-change-audit') . '</th>';
    echo '<th>' . esc_html__('Activity', 'kovalenko-admin-change-audit') . '</th>';
    echo '<th>' . esc_html__('IP address', 'kovalenko-admin-change-audit') . '</th>';
    echo '<th>' . esc_html(sprintf(
        /* translators: %s: timezone identifier */
        __('Timestamp (%s)', 'kovalenko-admin-change-audit'),
        kovalenko_admin_change_audit_timezone_name()
    )) . '</th>';
    echo '</tr></thead>';
    echo '<tbody data-log-rows>';

    foreach ($payload['items'] as $item) {
        kovalenko_admin_change_audit_render_table_row($item);
    }

    if ($payload['items'] === []) {
        echo '<tr data-empty-state><td colspan="5">' . esc_html__('No matching logs found.', 'kovalenko-admin-change-audit') . '</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    echo '<div class="wp-activity-logger-pagination">';
    echo '<button type="button" class="button" data-page-direction="prev">' . esc_html__('Previous', 'kovalenko-admin-change-audit') . '</button>';
    echo '<span class="wp-activity-logger-pagination-summary" data-pagination-summary>';
    echo esc_html(
        sprintf(
            /* translators: %1$d: total number of logs */
            __('Showing %1$d logs', 'kovalenko-admin-change-audit'),
            $pagination['totalLogs']
        )
    );
    echo '</span>';
    echo '<button type="button" class="button" data-page-direction="next">' . esc_html__('Next', 'kovalenko-admin-change-audit') . '</button>';
    echo '</div>';
    echo '</section>';

    echo '<script type="application/json" id="wp-activity-logger-initial-state">' . esc_html($initial_payload ?: '{}') . '</script>';
    echo '</div>';
}

function kovalenko_admin_change_audit_render_security_panel(string $page_url, string $security_saved, string $security_error): void
{
    echo '<section class="wp-activity-logger-security-card">';
    echo '<div class="wp-activity-logger-security-copy">';
    echo '<p class="wp-activity-logger-eyebrow">' . esc_html__('Protection', 'kovalenko-admin-change-audit') . '</p>';
    echo '<h3>' . esc_html__('Hidden screen access', 'kovalenko-admin-change-audit') . '</h3>';
    echo '<p>' . esc_html__('Only the plugin owner account can open this screen. You can add a second password here when you want an extra lock before the logs are shown.', 'kovalenko-admin-change-audit') . '</p>';
    echo '<p><strong>' . esc_html__('Private URL:', 'kovalenko-admin-change-audit') . '</strong> <a href="' . esc_url($page_url) . '">' . esc_html($page_url) . '</a></p>';

    if ($security_saved === 'updated') {
        echo '<div class="notice notice-success inline"><p>' . esc_html__('The access password was updated.', 'kovalenko-admin-change-audit') . '</p></div>';
    } elseif ($security_saved === 'removed') {
        echo '<div class="notice notice-success inline"><p>' . esc_html__('The access password was removed. Hidden owner-only access is still active.', 'kovalenko-admin-change-audit') . '</p></div>';
    } elseif ($security_error === 'mismatch') {
        echo '<div class="notice notice-error inline"><p>' . esc_html__('Password and confirmation must match.', 'kovalenko-admin-change-audit') . '</p></div>';
    }

    $password_status = kovalenko_admin_change_audit()->has_saved_password()
        ? __('Saved password active', 'kovalenko-admin-change-audit')
        : __('No saved password yet', 'kovalenko-admin-change-audit');

    echo '<p class="wp-activity-logger-password-status">' . esc_html($password_status) . '</p>';
    echo '</div>';

    echo '<form method="post" action="' . esc_url($page_url) . '" class="wp-activity-logger-security-form">';
    wp_nonce_field('kovalenko_admin_change_audit_save_security');
    echo '<input type="hidden" name="kovalenko_admin_change_audit_save_security" value="1">';
    echo '<label for="wpal-new-password">' . esc_html__('New access password', 'kovalenko-admin-change-audit') . '</label>';
    echo '<input id="wpal-new-password" type="password" name="kovalenko_admin_change_audit_new_password" class="regular-text" autocomplete="new-password">';
    echo '<label for="wpal-confirm-password">' . esc_html__('Confirm password', 'kovalenko-admin-change-audit') . '</label>';
    echo '<input id="wpal-confirm-password" type="password" name="kovalenko_admin_change_audit_confirm_password" class="regular-text" autocomplete="new-password">';
    echo '<div class="wp-activity-logger-security-actions">';
    echo '<button type="submit" class="button button-primary">' . esc_html__('Save password', 'kovalenko-admin-change-audit') . '</button>';
    echo '<button type="submit" class="button button-secondary" name="kovalenko_admin_change_audit_remove_password" value="1">' . esc_html__('Remove password', 'kovalenko-admin-change-audit') . '</button>';
    echo '</div>';
    echo '</form>';
    echo '</section>';
}

function kovalenko_admin_change_audit_render_timezone_panel(string $page_url, string $timezone_saved, string $timezone_error): void
{
    $timezone_name = kovalenko_admin_change_audit_timezone_name();

    echo '<section class="wp-activity-logger-security-card">';
    echo '<div class="wp-activity-logger-security-copy">';
    echo '<p class="wp-activity-logger-eyebrow">' . esc_html__('Timezone', 'kovalenko-admin-change-audit') . '</p>';
    echo '<h3>' . esc_html__('Viewer and schedule timezone', 'kovalenko-admin-change-audit') . '</h3>';
    echo '<p>' . esc_html__('Choose which timezone the log timestamps, date filters, and nightly maintenance schedule should use. The default is America/Edmonton.', 'kovalenko-admin-change-audit') . '</p>';
    echo '<p><strong>' . esc_html__('Current timezone:', 'kovalenko-admin-change-audit') . '</strong> ' . esc_html($timezone_name) . '</p>';

    if ($timezone_saved === 'updated') {
        echo '<div class="notice notice-success inline"><p>' . esc_html__('Timezone settings were updated.', 'kovalenko-admin-change-audit') . '</p></div>';
    } elseif ($timezone_error === 'invalid') {
        echo '<div class="notice notice-error inline"><p>' . esc_html__('Please choose a valid timezone.', 'kovalenko-admin-change-audit') . '</p></div>';
    }

    echo '</div>';

    echo '<form method="post" action="' . esc_url($page_url) . '" class="wp-activity-logger-security-form">';
    wp_nonce_field('kovalenko_admin_change_audit_save_timezone');
    echo '<input type="hidden" name="kovalenko_admin_change_audit_save_timezone" value="1">';
    echo '<label for="wpal-timezone">' . esc_html__('Timezone', 'kovalenko-admin-change-audit') . '</label>';
    echo '<select id="wpal-timezone" name="kovalenko_admin_change_audit_timezone" class="regular-text">';

    foreach (timezone_identifiers_list() as $identifier) {
        echo '<option value="' . esc_attr($identifier) . '"' . selected($timezone_name, $identifier, false) . '>' . esc_html($identifier) . '</option>';
    }

    echo '</select>';
    echo '<div class="wp-activity-logger-security-actions">';
    echo '<button type="submit" class="button button-primary">' . esc_html__('Save timezone', 'kovalenko-admin-change-audit') . '</button>';
    echo '</div>';
    echo '</form>';
    echo '</section>';
}

function kovalenko_admin_change_audit_render_stat_card(string $label, string $value, string $metric_key): void
{
    echo '<article class="wp-activity-logger-stat-card">';
    echo '<span class="wp-activity-logger-stat-label">' . esc_html($label) . '</span>';
    echo '<strong class="wp-activity-logger-stat-value" data-metric="' . esc_attr($metric_key) . '">' . esc_html($value) . '</strong>';
    echo '</article>';
}

function kovalenko_admin_change_audit_render_select_option(string $value, string $current_value, string $label): void
{
    echo '<option value="' . esc_attr($value) . '"' . selected($current_value, $value, false) . '>' . esc_html($label) . '</option>';
}

function kovalenko_admin_change_audit_render_table_row(array $item): void
{
    echo '<tr data-log-id="' . esc_attr((string) $item['id']) . '">';
    echo '<td>' . esc_html((string) $item['id']) . '</td>';
    echo '<td><span class="wp-activity-logger-user-pill">' . esc_html((string) $item['user']) . '</span></td>';
    echo '<td>' . esc_html((string) $item['activity']) . '</td>';
    echo '<td><code>' . esc_html((string) $item['ipAddress']) . '</code></td>';
    echo '<td>' . esc_html((string) $item['createdAt']) . '</td>';
    echo '</tr>';
}
