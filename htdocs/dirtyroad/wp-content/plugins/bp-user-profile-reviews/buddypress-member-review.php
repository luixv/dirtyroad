<?php
/**
 * Plugin Name: Wbcom Designs - BuddyPress Member Reviews
 * Plugin URI: https://wbcomdesigns.com/downloads/buddypress-user-profile-reviews/
 * Description: This plugin  allows only site members to add reviews to the buddypress members on the site. But the member can not review himself/herself. And if the visitor is not logged in, he can only see the listing of the reviews but can not review.  The review form allows the members to even rate the member's profile out of 5 points with multiple review criteria.
 * Version: 2.6.1
 * Author: Wbcom Designs
 * Author URI: https://wbcomdesigns.com
 * License: GPLv2+
 * Text Domain: bp-member-reviews
 * Domain Path: /languages
 *
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Constants used in the plugin.
 */
define( 'BUPR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BUPR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! function_exists( 'bupr_load_textdomain' ) ) {
	add_action( 'init', 'bupr_load_textdomain' );
	/**
	 * Load plugin textdomain.
	 *
	 * @author   Wbcom Designs
	 * @since    1.0.0
	 */
	function bupr_load_textdomain() {
		$domain = 'bp-member-reviews';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, 'languages/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
}



if ( ! function_exists( 'bupr_plugins_files' ) ) {

	add_action( 'plugins_loaded', 'bupr_plugins_files' );

	/**
	 * Include requir files
	 *
	 * @author   Wbcom Designs
	 * @since    1.0.0
	 */
	function bupr_plugins_files() {
		if ( ! class_exists( 'BuddyPress' ) ) {
			add_action( 'admin_notices', 'bupr_admin_notice' );
		} else {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bupr_admin_page_link' );
			/**
			* Include needed files on init
			*/
			$include_files = array(
				'includes/class-buprglobals.php',
				'admin/wbcom/wbcom-admin-settings.php',
				'includes/bupr-scripts.php',
				'admin/bupr-admin.php',
				'admin/class-bupr-admin-feedback.php',
				'includes/bupr-filters.php',
				'includes/bupr-shortcodes.php',
				'includes/widgets/display-review.php',
				'includes/widgets/member-rating.php',
				'includes/bupr-ajax.php',
				'includes/bupr-notification.php',
				'includes/bupr-genral-functions.php',
			);

			foreach ( $include_files as $include_file ) {
				include $include_file;
			}
		}
	}
}

if ( ! function_exists( 'bupr_admin_notice' ) ) {
	/**
	 * Display admin notice
	 *
	 * @author   Wbcom Designs
	 * @since    1.0.0
	 */
	function bupr_admin_notice() {
		$bupr_plugin = esc_html__( 'BuddyPress Member Reviews', 'bp-member-reviews' );
		$bp_plugin   = esc_html__( 'BuddyPress', 'bp-member-reviews' );

		echo '<div class="error"><p>' . sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to function correctly.', 'bp-member-reviews' ), '<strong>' . esc_html( $bupr_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>' ) . '</p></div>';
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}
}

// add_action( 'bp_init', 'bupr_bp_notifications_for_review', 12 );

/**
 * BuddyPress member reviews notification.
 *
 * @author   Wbcom Designs
 * @since    1.0.0
 */
// function bupr_bp_notifications_for_review() {
// include 'includes/bupr-notification.php';
// buddypress()->bupr_bp_review                        = new BUPR_Notifications();
// buddypress()->bupr_bp_review->notification_callback = 'bupr_format_notifications';
// }

/**
 * Settings link for this plugin.
 *
 * @param array $links The plugin setting links array.
 * @return array
 * @author   Wbcom Designs
 * @since    1.0.0
 */
function bupr_admin_page_link( $links ) {
	$page_link = array(
		'<a href="' . admin_url( 'admin.php?page=bp-member-review-settings' ) . '">' . esc_html__( 'Settings', 'bp-member-reviews' ) . '</a>',
	);
	return array_merge( $links, $page_link );
}

/**
 *  Check if buddypress activate.
 */
function bupr_requires_buddypress() {
	if ( ! class_exists( 'Buddypress' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		// deactivate_plugins('buddypress-polls/buddypress-polls.php');
		add_action( 'admin_notices', 'bupr_required_plugin_admin_notice' );
		unset( $_GET['activate'] );
	}
}

add_action( 'admin_init', 'bupr_requires_buddypress' );
/**
 * Throw an Alert to tell the Admin why it didn't activate.
 *
 * @author wbcomdesigns
 * @since  1.0.0
 */
function bupr_required_plugin_admin_notice() {
	$bpquotes_plugin = esc_html__( 'BuddyPress Member Reviews', 'bp-member-reviews' );
	$bp_plugin       = esc_html__( 'BuddyPress', 'bp-member-reviews' );
	echo '<div class="error"><p>';
	echo sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'bp-member-reviews' ), '<strong>' . esc_html( $bpquotes_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>' );
	echo '</p></div>';
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}


/**
 * redirect to plugin settings page after activated
 */

add_action( 'activated_plugin', 'bupr_activation_redirect_settings' );
function bupr_activation_redirect_settings( $plugin ){

	if( $plugin == plugin_basename( __FILE__ ) ) {
		wp_redirect( admin_url( 'admin.php?page=bp-member-review-settings' ) ) ;
		exit;
	}
}

/*
 * Site url translate using WPML
 *
 */

//add_filter( 'site_url', 'bupr_site_url', 99);
function bupr_site_url( $url ) {	
	if ( !is_admin() && strpos($url,'wp-admin') == false) {		
		return untrailingslashit(apply_filters( 'wpml_home_url', $url ));
	} else {
		return $url;
	}
}
