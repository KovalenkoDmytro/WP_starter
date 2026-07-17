<?php
/**
 * Plugin Name: Kovalenko Admin Change Audit
 * Plugin URI: https://github.com/KovalenkoDmytro/wp_logs_plugin
 * Description: Records key site activity and provides a protected activity log screen for site owners.
 * Version: 2.8.0
 * Author: Dmytro Kovalenko
 * Author URI: https://dmytro-kovalenko.ca
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.2
 * Requires PHP: 8.1
 * Text Domain: kovalenko-admin-change-audit
 * Domain Path: /languages
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

if (version_compare(PHP_VERSION, '8.1', '<')) {
    add_action(
        'admin_notices',
        static function (): void {
            echo '<div class="notice notice-error"><p>';
            echo esc_html__('Kovalenko Admin Change Audit requires PHP 8.1 or newer.', 'kovalenko-admin-change-audit');
            echo '</p></div>';
        }
    );

    return;
}

require_once __DIR__ . '/includes/data_base_queries.php';
require_once __DIR__ . '/includes/class-wp-activity-logger-admin-service.php';
require_once __DIR__ . '/includes/class-wp-activity-logger-event-logger.php';
require_once __DIR__ . '/includes/class-wp-activity-logger-plugin.php';
require_once __DIR__ . '/includes/admin_show_page.php';

function kovalenko_admin_change_audit(): Kovalenko_Admin_Change_Audit_Plugin
{
    static $plugin = null;

    if (! $plugin instanceof Kovalenko_Admin_Change_Audit_Plugin) {
        $plugin = new Kovalenko_Admin_Change_Audit_Plugin(__FILE__);
        $plugin->register_hooks();
    }

    return $plugin;
}

kovalenko_admin_change_audit();
