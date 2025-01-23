<?php
/**
 * Plugin Name:       Slider Date Block
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       copyright-slider-block
 *
 * @package CreateBlock
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_slider_init() {
	register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_slider_init' );

function enqueue_swiper_assets() {
	// Enqueue Swiper CSS
	wp_enqueue_style(
		'swiper-css',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
		array(), // Dependencies
		'11.0.0' // Version
	);

	// Enqueue Swiper JavaScript
	wp_enqueue_script(
		'swiper-js',
		'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
		array(), // Dependencies
		'11.0.0', // Version
		true // Load in the footer
	);
}
add_action( 'wp_enqueue_scripts', 'enqueue_swiper_assets' );
