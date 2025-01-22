<?php

/**
 * Plugin Name: Google review Widgets
 * Description: Elementor custom widgets for instead of using Google review.
 * Version:     1.0.0
 * Author:      Dmytro Kovalenko
 * Author URI:  https://www.dmytrokovalenko.online/
 * Text Domain: google-review
 *
 * Elementor tested up to: 3.5.0
 * Elementor Pro tested up to: 3.5.0
 */

use Elementor\Widgets_Manager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Register Widgets.
 *
 * Include widget file and register widget class.
 *
 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 *@since 1.0.0
 */
function register_essential_custom_widgets( Widgets_Manager $widgets_manager ): void
{

    require_once( __DIR__ . '/widgets/googleRewie-widget.php' );  // include the widget file

    $widgets_manager->register( new \Essential_Elementor_Google_Review_Widget() );  // register the widget

}
add_action( 'elementor/widgets/register', 'register_essential_custom_widgets' );