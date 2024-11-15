<?php
/*
Plugin Name: GutenbergBlocksV2
Description: Registers a custom Gutenberg blocks.
Version: 1.0
*/

function register(): void {
	register_block_type(__DIR__ . '/copyright-date-block/build');
}
add_action('init', 'register');