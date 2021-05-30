<?php
/*
Plugin Name: Force Login
Plugin URI: https://wordpress.org/plugins/wp-force-login/
Description: Easily hide your WordPress site from public viewing by requiring visitors to log in first. Activate to turn on.
Version: 5.5
Author: Kevin Vess
Author URI: http://vess.me/

Text Domain: wp-force-login
Domain Path: /languages

License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function v_forcelogin() {

	// Exceptions for AJAX, Cron, or WP-CLI requests
	if ( ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
		return;
	}

	// Redirect unauthorized visitors
	if ( ! is_user_logged_in() ) {
		// Get visited URL
		$schema = isset( $_SERVER['HTTPS'] ) && 'on' === $_SERVER['HTTPS'] ? 'https://' : 'http://';
		$url = $schema . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		/**
		 * Whitelist filter.
		 *
		 * @since 3.0.0
		 * @deprecated 5.5.0 Use {@see 'v_forcelogin_bypass'} instead.
		 *
		 * @param array An array of absolute URLs.
		 */
		$allowed = apply_filters_deprecated( 'v_forcelogin_whitelist', array( array() ), '5.5.0', 'v_forcelogin_bypass' );

		/**
		 * Bypass filter.
		 *
		 * @since 5.0.0
		 * @since 5.2.0 Added the `$url` parameter.
		 *
		 * @param bool Whether to disable Force Login. Default false.
		 * @param string $url The visited URL.
		 */
		$bypass = apply_filters( 'v_forcelogin_bypass', in_array( $url, $allowed ), $url );

		if ( preg_replace( '/\?.*/', '', $url ) !== preg_replace( '/\?.*/', '', wp_login_url() ) && ! $bypass ) {
			// Determine redirect URL
			$redirect_url = apply_filters( 'v_forcelogin_redirect', $url );
			// Set the headers to prevent caching
			nocache_headers();
			// Redirect
			wp_safe_redirect( wp_login_url( $redirect_url ), 302 );
			exit;
		}
	} elseif ( function_exists( 'is_multisite' ) && is_multisite() ) {
		// Only allow Multisite users access to their assigned sites
		if ( ! is_user_member_of_blog() && ! current_user_can( 'setup_network' ) ) {
			wp_die( __( "You're not authorized to access this site.", 'wp-force-login' ), get_option( 'blogname' ) . ' &rsaquo; ' . __( 'Error', 'wp-force-login' ) );
		}
	}
}
add_action( 'template_redirect', 'v_forcelogin' );

/**
 * Restrict REST API for authorized users only
 *
 * @since 5.1.0
 * @param WP_Error|null|bool $result WP_Error if authentication error, null if authentication
 *                              method wasn't used, true if authentication succeeded.
 *
 * @return WP_Error|null|bool
 */
function v_forcelogin_rest_access( $result ) {
	if ( null === $result && ! is_user_logged_in() ) {
		return new WP_Error( 'rest_unauthorized', __( 'Only authenticated users can access the REST API.', 'wp-force-login' ), array( 'status' => rest_authorization_required_code() ) );
	}
	return $result;
}
add_filter( 'rest_authentication_errors', 'v_forcelogin_rest_access', 99 );

/*
 * Localization
 */
function v_forcelogin_load_textdomain() {
	load_plugin_textdomain( 'wp-force-login', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'v_forcelogin_load_textdomain' );


/**
 * Bypass Force Login to allow for exceptions.
 *
 * @param bool $bypass Whether to disable Force Login. Default false.
 * @param string $visited_url The visited URL.
 * @return bool
 */
function my_forcelogin_bypass( $bypass, $visited_url ) {
  // Allow all single posts
  // ADD LINK TO GDPR
  if ( is_single() ) {
    $bypass = true;
  }

  // Allow these absolute URLs
  $allowed = array(
    home_url( '/register/' ),
    home_url( '/faq/' ),	
    home_url( '/about/' ),
	home_url( '/contact-us/' ),
	home_url( '/impressum/' )
  );
  if ( ! $bypass ) {
    $bypass = in_array( $visited_url, $allowed );
  }

  return $bypass;
}
add_filter( 'v_forcelogin_bypass', 'my_forcelogin_bypass', 10, 2 );