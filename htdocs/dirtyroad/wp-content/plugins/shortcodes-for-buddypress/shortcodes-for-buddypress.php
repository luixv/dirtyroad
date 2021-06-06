<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbcomdesigns.com/plugins
 * @since             1.0.0
 * @package           Shortcodes_For_Buddypress
 *
 * @wordpress-plugin
 * Plugin Name:       Wbcom Designs – Shortcodes & Elementor Widgets For BuddyPress
 * Plugin URI:        https://github.com/wbcomdesigns/shortcodes-for-buddypress
 * Description:       This plugin will add an extended feature to BuddyPress that will added Shortcode for Listing Activity Streams, Members and Groups on any post/page in website. It will offer same features like you are getting on BuddyPress mapped component pages.
 * Version:           2.4.0
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com/plugins
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shortcodes-for-buddypress
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SHORTCODES_FOR_BUDDYPRESS_VERSION', '2.4.0' );

if ( ! defined( 'SHORTCODES_FOR_BUDDYPRESS_PLUGIN_DIR' ) ) {
	define( 'SHORTCODES_FOR_BUDDYPRESS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'SHORTCODES_FOR_BUDDYPRESS_PLUGIN_URL' ) ) {
	define( 'SHORTCODES_FOR_BUDDYPRESS_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
}
if ( ! defined( 'SHORTCODES_FOR_BUDDYPRESS_PLUGIN_BASENAME' ) ) {
	define( 'SHORTCODES_FOR_BUDDYPRESS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-shortcodes-for-buddypress-activator.php
 */
function activate_shortcodes_for_buddypress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shortcodes-for-buddypress-activator.php';
	Shortcodes_For_Buddypress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-shortcodes-for-buddypress-deactivator.php
 */
function deactivate_shortcodes_for_buddypress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-shortcodes-for-buddypress-deactivator.php';
	Shortcodes_For_Buddypress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_shortcodes_for_buddypress' );
register_deactivation_hook( __FILE__, 'deactivate_shortcodes_for_buddypress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-shortcodes-for-buddypress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_shortcodes_for_buddypress() {

	$plugin = new Shortcodes_For_Buddypress();
	$plugin->run();

}
run_shortcodes_for_buddypress();


add_action( 'admin_notices', 'shortcodes_for_buddypress_plugin_admin_notice' );
/**
 * Function to through admin notice if BuddyPress is not active.
 */
function shortcodes_for_buddypress_plugin_admin_notice() {

	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}
	$check_active = false;

	if ( ! is_plugin_active_for_network( 'buddypress/bp-loader.php' ) && ! in_array( 'buddypress/bp-loader.php', get_option( 'active_plugins' ) ) ) {
		$check_active = true;
	}

	if ( $check_active ) {
		$bmpro_plugin = 'Shortcode For BuddyPress';
		$bp_plugin    = 'BuddyPress';

		echo '<div class="error"><p>'
		. sprintf( esc_html( __( '%1$s is ineffective as it requires %2$s to be installed and active.', 'shortcodes-for-buddypress' ) ), '<strong>' . esc_html( $bmpro_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>' )
		. '</p></div>';
	}
}


/**
 *  Check if buddypress activate.
 */
function shortcodes_for_buddypress_requires_buddypress() {
	if ( ! class_exists( 'Buddypress' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		// deactivate_plugins('buddypress-polls/buddypress-polls.php');
		add_action( 'admin_notices', 'shortcodes_for_buddypress_required_plugin_admin_notice' );
		unset( $_GET['activate'] );
	}
}

add_action( 'admin_init', 'shortcodes_for_buddypress_requires_buddypress' );
/**
 * Throw an Alert to tell the Admin why it didn't activate.
 *
 * @author wbcomdesigns
 * @since  2.2.0
 */
function shortcodes_for_buddypress_required_plugin_admin_notice() {

	$bpquotes_plugin = esc_html__( ' Shortcode For BuddyPress', 'shortcodes-for-buddypress' );
	$bp_plugin       = esc_html__( 'BuddyPress', 'shortcodes-for-buddypress' );
	echo '<div class="error"><p>';
	echo sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'shortcodes-for-buddypress' ), '<strong>' . esc_html( $bpquotes_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>' );
	echo '</p></div>';
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}



/**
 * BuddyPress Activity Elements.
 *
 * @since 2.2.0
 */
function buddypress_shortcodes_categories_registered( $elements_manager ) {

	$elements_manager->add_category(
		'buddypress-widgets',
		array(
			'title' => 'BuddyPress Widgets',
			'icon'  => 'fa fa-plug',
		)
	);
}

add_action( 'elementor/elements/categories_registered', 'buddypress_shortcodes_categories_registered' );

function shortcodes_for_buddypress_widgets_registered() {
	require plugin_dir_path( __FILE__ ) . 'buddypress-shortcodes-element.php';
	require plugin_dir_path( __FILE__ ) . 'buddypress-members-element.php';
	require plugin_dir_path( __FILE__ ) . 'buddypress-groups-element.php';
}

add_action( 'elementor/widgets/widgets_registered', 'shortcodes_for_buddypress_widgets_registered', 15 );
