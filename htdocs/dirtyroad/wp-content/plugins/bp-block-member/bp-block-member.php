<?php
/*
Plugin Name: BuddyBlock
Description: Allows a BuddyPress member to block another member. Admin controls provided under Settings.
Version: 4.2
Author: PhiloPress
Author URI: https://philopress.com/
Domain Path: /languages/
Copyright (C) 2013-2019  shanebp, PhiloPress
*/

// this version does not use ajax due to non-harressment considerations

if ( !defined( 'ABSPATH' ) ) exit;

define( 'BUDDYBLOCK_VERSION', '4.1' );
define( 'PP_BLOCK_STORE_URL', 'https://www.philopress.com/' );
define( 'PP_BLOCK_MEMBERS', 'BuddyBlock' );



function bp_block_member_include() {

    require_once( dirname( __FILE__ ) . '/inc/bp-block-member-core.php' );

	load_plugin_textdomain( 'bp-block-member', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	if ( ! is_admin() ) {
		require_once( dirname( __FILE__ ) . '/inc/bp-block-member-profile.php' );
	}

}
add_action( 'bp_include', 'bp_block_member_include', 100 );

function bp_block_member_install() {
	global $wpdb;

	$table_name = $wpdb->base_prefix . "bp_block_member";

	if ( $wpdb->get_var("show tables like '$table_name'") != $table_name ) {
        $sql = "CREATE TABLE $table_name (
            id MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            target_id BIGINT UNSIGNED NOT NULL
            );";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	if ( ! get_site_option( 'bp_block_roles' ) ) {

		add_site_option( 'bp_block_roles', 'administrator,super_admin', '', 'yes' );
		$role = get_role( 'administrator' );
		$role->add_cap( 'unblock_member' );

	}

	if ( ! get_site_option( 'pp_block_license_key' ) ) {
		add_site_option( 'pp_block_license_key', '' );
	}

	if ( ! get_site_option( 'pp_block_license_status' ) ) {
		add_site_option( 'pp_block_license_status', '' );
	}

}
register_activation_hook( __FILE__, 'bp_block_member_install' );


function bp_block_member_add_settings_link( $links ) {

	$link = array();

	if ( is_multisite() && ! is_plugin_active_for_network( "bp-block-member/bp-block-member.php" ) ) {
		$link = array( '<a href="' . admin_url( 'options-general.php?page=bp-block-member' ) . '">Settings</a>', );
	} elseif ( ! is_multisite() ) {
		$link = array( '<a href="' . admin_url( 'options-general.php?page=bp-block-member' ) . '">Settings</a>', );
	}

	return array_merge( $links, $link );
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'bp_block_member_add_settings_link' );

function bp_block_member_add_settings_link_multisite( $links ) {

	$link = array();

	if ( is_multisite() && is_plugin_active_for_network( "bp-block-member/bp-block-member.php" ) ) {
		$link = array( '<a href="' . admin_url( 'network/settings.php?page=bp-block-member' ) . '">Settings</a>', );
	}

	return array_merge( $links, $link );
}
add_filter( 'network_admin_plugin_action_links_' . plugin_basename(__FILE__), 'bp_block_member_add_settings_link_multisite' );


function bp_block_member_plugin_updater() {

	if ( ! class_exists( 'PP_Block_Members_Plugin_Updater' ) ) {
		include( dirname( __FILE__ ) . '/inc/admin/class-PP_Block_Members_Plugin_Updater.php' );
	}

	$license_key = trim( get_site_option( 'pp_block_license_key' ) );

	$edd_updater = new PP_Block_Members_Plugin_Updater( PP_BLOCK_STORE_URL, __FILE__, array(
			'version' 	=> '4.2',
			'license' 	=> $license_key,
			'item_name' => PP_BLOCK_MEMBERS,
			'author' 	=> 'PhiloPress'
		)
	);

}
//add_action( 'admin_init', 'bp_block_member_plugin_updater', 0 );
