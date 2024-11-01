<?php
/*
 * Plugin Name: Very Simple Custom Textwidget
 * Description: This is a very simple plugin to add a custom text widget in your sidebar. For more info please check readme file.
 * Version: 3.5
 * Author: Guido
 * Author URI: https://www.guido.site
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: very-simple-custom-textwidget
 * Domain Path: /translation
 */

// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// load plugin text domain
function vsct_init() { 
	load_plugin_textdomain( 'very-simple-custom-textwidget', false, dirname( plugin_basename( __FILE__ ) ) . '/translation' );
}
add_action('plugins_loaded', 'vsct_init');

// enqueue plugin scripts
function vsct_frontend_scripts() {	
	if(!is_admin())	{
		wp_enqueue_style('vsct_style', plugins_url('/css/vsct-style.css',__FILE__));
	}
}
add_action('wp_enqueue_scripts', 'vsct_frontend_scripts');

// register widget
function register_vsct_widget() {
	register_widget( 'vsct_widget' );
}
add_action( 'widgets_init', 'register_vsct_widget' );

// include widget file
include 'vsct-widget.php';
