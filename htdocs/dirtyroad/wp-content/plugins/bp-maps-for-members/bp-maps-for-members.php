<?php
/*
 * Plugin Name: BP Maps for Members
 * Description: Create and Display Maps for Members in BuddyPress or the BuddyBoss Platform
 * Version: 6.9
 * Author: PhiloPress
 * Author URI: https://philopress.com/
 * Text Domain: bp-member-maps
 * Domain Path: /languages/
 * Copyright (C) 2016-2021  shanebp, PhiloPress
 */


if ( !defined( 'ABSPATH' ) ) exit;

define( 'PP_MAPS_MEMBERS_STORE_URL', 'https://www.philopress.com/' );
define( 'PP_BP_MAPS_MEMBERS', 'BP Maps for Members' );

function pp_mm_bp_check() {

	if ( ! class_exists('BuddyPress') ) {
		add_action( 'admin_notices', 'pp_mm_install_buddypress_buddyboss_notice' );
	}

	//if( ! function_exists( 'xpp_loc_install_buddypress_buddyboss_notice' ) ) {
	//	add_action( 'admin_notices', 'pp_mm_install_bp_profile_location_notice' );
	//}

}
add_action('plugins_loaded', 'pp_mm_bp_check', 999);


function pp_mm_bp_conflicts() {

	if ( is_plugin_active( 'bp-distance-search/bds-main.php' ) ) {
		deactivate_plugins( '/bp-distance-search/bds-main.php' );
	}

	if ( ! is_plugin_active( 'bp-xprofile-location/loader.php' ) ) {
		add_action( 'admin_notices', 'pp_mm_install_bp_profile_location_notice' );
	}
}
add_action( 'admin_init', 'pp_mm_bp_conflicts' );


function pp_mm_install_buddypress_buddyboss_notice() {
	echo '<div id="message" class="error fade"><p style="line-height: 150%">';
	_e('BP Maps for Members requires the BuddyPress plugin or the BuddyBoss Platform plugin. Please install either of those plugins or deactivate the BP xProfile Location plugin.', 'bp-member-maps');
	echo '</p></div>';
}

function pp_mm_install_bp_profile_location_notice() {
	echo '<div id="message" class="error fade"><p style="line-height: 150%">';
	_e('BP Maps for Members requires the free BP xProfile Location plugin from PhiloPress. Please install BP xProfile Location or deactivate BP Maps for Members.', 'bp-member-maps');
	echo '</p></div>';
}


function pp_mm_init() {

	define( 'PP_MM_DIR', dirname( __FILE__ ) );

	load_plugin_textdomain( 'bp-member-maps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );


	if ( is_admin() || is_network_admin() ) {

		require_once( PP_MM_DIR . '/inc/admin/class-pp-mm-maps-admin.php' );

		$instance = PP_Member_Maps_Admin::get_instance();

	} else {

		require_once( PP_MM_DIR . '/inc/class-pp-mm-theme-compat.php' );

		require_once( PP_MM_DIR . '/inc/pp-mm-profile.php' );

	}

	require_once( PP_MM_DIR . '/inc/pp-mm-template-tags.php' );
	require_once( PP_MM_DIR . '/inc/pp-mm-functions.php' );

	//if ( PP_BPS ) {

		require_once( PP_MM_DIR . '/inc/pp-mm-bps-support.php' );

	//}

}
add_action( 'bp_include', 'pp_mm_init' );


function pp_mm_activation() {

	pp_create_mm_options();

}
register_activation_hook(__FILE__, 'pp_mm_activation');


function pp_mm_uninstall () {
	delete_site_option("bp-member-map-single-settings");
	delete_site_option("bp-member-map-all-settings");
}
register_uninstall_hook( __FILE__, 'pp_mm_uninstall');


function pp_create_mm_options() {

	if ( ! get_site_option( 'bp-member-map-single-settings' ) ) {

		$settings_single = array();

		$settings_single["map_type"] = "roadmap";
		$settings_single["map_zoom_level"] = 10;
		$settings_single["map_height"] = 200;

		add_site_option( 'bp-member-map-single-settings', $settings_single );

	}

	if ( ! get_site_option( 'bp-member-map-all-settings' ) ) {

		$settings_all = array();

		$settings_all["pp_mm_address"] = "Chicago, IL, USA";
		$settings_all["pp_mm_latlng"] = "41.88,-87.623";
		$settings_all["map_type_all"] = "roadmap";
		$settings_all["map_zoom_level_all"] = 16;
		$settings_all["map_height_all"] = 500;
		$settings_all["map_limit_all"] = -1;
		$settings_all["map_member_distance_measurement"] = 'miles';

		add_site_option( 'bp-member-map-all-settings', $settings_all );

	}


	if ( ! get_site_option( 'pp_mm_license_key' ) ) {
		add_site_option( 'pp_mm_license_key', '' );
	}

	if ( ! get_site_option( 'pp_mm_license_status' ) ) {
		add_site_option( 'pp_mm_license_status', '' );
	}

}


function pp_mm_add_settings_link( $links ) {
	$link = array( '<a href="' . admin_url( 'options-general.php?page=bp-maps-for-members' ) . '">Settings</a>', );
	return array_merge( $links, $link );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'pp_mm_add_settings_link' );


function pp_mm_plugin_updater() {

	if( !class_exists( 'PP_Members_Maps_Plugin_Updater' ) ) {
		include( dirname( __FILE__ ) . '/inc/admin/PP_Members_Maps_Plugin_Updater.php' );
	}

	$license_key = trim( get_site_option( 'pp_mm_license_key' ) );

	$edd_updater = new PP_Members_Maps_Plugin_Updater( PP_MAPS_MEMBERS_STORE_URL, __FILE__, array(
			'version' 	=> '6.9', 				// current version number
			'license' 	=> $license_key, 		// license key
			'item_name' => PP_BP_MAPS_MEMBERS, 	// name of this plugin
			'author' 	=> 'PhiloPress'         // author of this plugin
		)
	);

}
add_action( 'admin_init', 'pp_mm_plugin_updater', 0 );
