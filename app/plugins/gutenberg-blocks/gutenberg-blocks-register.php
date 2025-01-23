<?php
/**
 * Plugin Name:       GutenbergBlocksV2
 * Description:       Registers a custom Gutenberg blocks.
 * Version:           1.0
 * Requires at least: 6.0
 * Requires PHP:      7.2
 * Author:            WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       custom-list-block
 */
function register(): void {
	register_block_type( __DIR__ . '/copyright_date_block/build');
}
add_action('init', 'register');
