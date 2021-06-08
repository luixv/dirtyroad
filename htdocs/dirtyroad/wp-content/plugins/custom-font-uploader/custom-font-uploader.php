<?php
/**
 * Plugin Name: Custom Font Uploader
 * Plugin URI: https://wbcomdesigns.com/downloads/custom-font-uploader/
 * Description: This plugin allows you to upload custom font-family using browse and upload as well as using google font-family and have it enqueued in your site.
 * Version: 2.0.0
 * Author: Wbcom Designs
 * Author URI: https://wbcomdesigns.com
 * Text Domain: cfup
 *
 * @package custom-font-uploader
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // If this file is called directly, abort.
}

// Defining constants.
$uploads_dir = wp_upload_dir();
$cons        = array(
	'CUSTOM_FONT_UPLOADER_PLUGIN_PATH'      => plugin_dir_path( __FILE__ ),
	'CUSTOM_FONT_UPLOADER_PLUGIN_URL'       => plugin_dir_url( __FILE__ ),
	'CUSTOM_FONT_UPLOADER_UPLOADS_DIR_URL'  => $uploads_dir['baseurl'] . '/custom_fonts/',
	'CUSTOM_FONT_UPLOADER_UPLOADS_DIR_PATH' => $uploads_dir['basedir'] . '/custom_fonts/',
	'CFUP_TEXT_DOMAIN'                      => 'cfup',
);
foreach ( $cons as $con => $value ) {
	define( $con, $value );
}

// Include needed files.
$include_files = array(
	'inc/cfup-scripts.php',
	'inc/cfup-functions.php',
	'admin/cfup-admin.php',
	'admin/class-cfup-admin-feedback.php',
	'admin/wbcom/wbcom-admin-settings.php',
);
foreach ( $include_files as $include_file ) {
	include_once plugin_dir_path( __FILE__ ) . $include_file;
}

add_action( 'init', 'cfup_load_textdomain' );
/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function cfup_load_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'cfup' );
	load_textdomain( 'cfup', 'languages/cfup-' . $locale . '.mo' );
	load_plugin_textdomain( 'cfup', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
}

// Plugin deactivation hook.
register_deactivation_hook( __FILE__, 'custom_font_plugin_deactivation' );

/**
 * Plugin deactivation functionality.
 *
 * @since  1.0.0
 * @author Wbcom Designs
 */
function custom_font_plugin_deactivation() {
	/*
	delete_option( 'custom_font_data' );
	delete_option( 'cfupgooglefonts_data' );
	delete_option( 'font_file_name' );
	delete_option( 'googlefont_file_name' );
	*/
}

// Plugin activation hook.
register_activation_hook( __FILE__, 'custom_font_plugin_activation' );

/**
 * Plugin activation functionality.
 *
 * @since  1.0.0
 * @author Wbcom Designs
 */
function custom_font_plugin_activation() {
	$cfup_upload     = wp_upload_dir();
	$cfup_upload_dir = $cfup_upload['basedir'];
	$cfup_upload_dir = $cfup_upload_dir . '/custom_fonts/';
	if ( ! file_exists( $cfup_upload_dir ) ) {
		mkdir( $cfup_upload_dir, 0755, true );
	}
}

// Settings link for custom font panel.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'custom_font_admin_page_link' );

/**
 * Settings link for custom font panel.
 *
 * @since  1.0.0
 * @author Wbcom Designs
 * @param  string $links contains plugin setting url.
 */
function custom_font_admin_page_link( $links ) {
	$cfup_links = array(
		'<a href="' . admin_url( 'admin.php?page=custom-font-uploader-settings' ) . '">' . __( 'Settings', 'cfup' ) . '</a>',
		'<a href="https://wbcomdesigns.com/contact/" target="_blank">' . __( 'Support', 'cfup' ) . '</a>',
	);
	return array_merge( $links, $cfup_links );
}
