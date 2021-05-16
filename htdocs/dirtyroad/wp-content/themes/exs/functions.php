<?php
/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'EXS_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

//https://developer.wordpress.org/themes/basics/linking-theme-files-directories/#linking-to-theme-directories
define( 'EXS_THEME_URI', get_parent_theme_file_uri() );
define( 'EXS_THEME_PATH', get_parent_theme_file_path() );
define( 'EXS_DEV_MODE', is_dir( EXS_THEME_PATH . '/dev' ) );
define( 'EXS_EXTRA', is_dir( EXS_THEME_PATH . '/extra' ) );

//THEME MAIN CLASS
require_once EXS_THEME_PATH . '/inc/exs.php';

//THEME SETUP
//theme support
//image sizes
//register menus
//register sidebars
require_once EXS_THEME_PATH . '/inc/setup.php';

//THEME OPTIONS helpers and default options
require_once EXS_THEME_PATH . '/inc/options.php';

//STATIC ASSETS
require_once EXS_THEME_PATH . '/inc/static.php';

//HTML OUTPUT FILTERS
require_once EXS_THEME_PATH . '/inc/output-filters.php';

//WooCommerce support
if ( class_exists( 'WooCommerce' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/woocommerce.php';
}
//EDD support
if ( class_exists( 'Easy_Digital_Downloads' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/edd.php';
}
//bbPress support
if ( class_exists( 'bbPress' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/bbpress.php';
}
//BuddyPress support
if ( class_exists( 'BuddyPress' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/buddypress.php';
}
//WP Job Manager support
if ( class_exists( 'WP_Job_Manager' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/wp-job-manager.php';
}
//Post Views Counter
if ( class_exists( 'Post_Views_Counter' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/post-views-counter.php';
}

//Posts Like Dislike
if ( class_exists( 'PLD_Comments_like_dislike' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/posts-like-dislike.php';
}

//Comments Like Dislike
if ( class_exists( 'CLD_Comments_like_dislike' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/comments-like-dislike.php';
}

//Events Calendar
if ( class_exists( 'Tribe__Events__Main' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/events-calendar.php';
}

//LearnPress
if ( class_exists( 'LearnPress' ) ) {
	require_once EXS_THEME_PATH . '/inc/integrations/learnpress.php';
}

if ( EXS_EXTRA ) {

	if ( ! function_exists( 'exs_fs' ) ) {
		// Create a helper function for easy SDK access.
		function exs_fs() {

			global $exs_fs;

			if ( ! isset( $exs_fs ) ) {
				// Include Freemius SDK.
				require_once EXS_THEME_PATH . '/freemius/start.php';

				$exs_fs = fs_dynamic_init( array(
					'id'                  => '6216',
					'slug'                => 'exs',
					'premium_slug'        => 'exs-pro',
					'type'                => 'theme',
					'public_key'          => 'pk_7520d603913d7eb6187fc471f4ac7',
					'is_premium'          => true,
					'premium_suffix'      => 'Pro',
					// If your theme is a serviceware, set this option to false.
					'has_premium_version' => true,
					'has_addons'          => false,
					'has_paid_plans'      => true,
					'menu'                => array(
						'slug'           => 'exs-theme',
						'parent'         => array(
							'slug' => 'themes.php',
						),
					),
				) );
			}

			return $exs_fs;
		}

		// Init Freemius.
		exs_fs();
		// Signal that SDK was initiated.
		do_action( 'exs_fs_loaded' );
	}

	require_once EXS_THEME_PATH . '/extra/functions.php';
}

//only for front end
if ( ! is_admin() ) {

	//TEMPLATE HELPERS
	require_once EXS_THEME_PATH . '/inc/template-helpers.php';

}

//only for admin
if ( is_admin() ) {

	//TGM plugin activation and demo-content
	require_once EXS_THEME_PATH . '/inc/tgm-plugin-activation/plugins.php';

	if ( function_exists( 'register_block_pattern_category' ) ) :
		require_once EXS_THEME_PATH . '/inc/block-patterns.php';
	endif;

}

//only for customizer
//if ( is_admin() || is_customize_preview() || EXS_DEV_MODE ) {
if ( is_customize_preview() || EXS_DEV_MODE ) {

	//CUSTOMIZER INIT
	require_once EXS_THEME_PATH . '/inc/customizer.php';

}

if ( EXS_DEV_MODE ) :
	require_once EXS_THEME_PATH . '/dev/extensions/functions.php';
endif;

//only if our fields plugin not activated and if is_admin
if ( is_admin() && ! class_exists( 'Exs_Fields_Taxonomy' ) && EXS_EXTRA ) {
	require_once EXS_THEME_PATH . '/extra/taxonomy-options/class-exs-fields.php';
	require_once EXS_THEME_PATH . '/extra/taxonomy-options/class-exs-fields-taxonomy.php';
}
