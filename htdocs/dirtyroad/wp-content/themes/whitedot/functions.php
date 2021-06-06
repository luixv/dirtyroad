<?php
/**
 * WhiteDot functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WhiteDot
 */



//WhiteDot Setup
add_action( 'after_setup_theme', 'whitedot_setup' );

//WhiteDot Content Width
add_action( 'after_setup_theme', 'whitedot_content_width', 0 );

//Main Widget
add_action( 'widgets_init', 'whitedot_widgets_init' );

//Footer Widget
add_action( 'widgets_init', 'whitedot_footer_widgets_init' );

//Woocommerce Product Filter Widget
add_action( 'widgets_init', 'whitedot_woo_product_filter_widgets_init' );

//Enqueue Customizer Google Fonts
add_action( 'wp_enqueue_scripts', 'whitedot_customizer_google_fonts' );

//Enque Js Files
add_action( 'wp_enqueue_scripts', 'whitedot_scripts' );

//Enque Admin CSS Files
add_action( 'admin_enqueue_scripts', 'whitedot_enqueue_custom_admin_style' );

//Enque Customizer Js Files
add_action( 'customize_preview_init', 'whitedot_customize_preview_js' );

//Custom js for Theme Customizer Control
add_action( 'customize_controls_enqueue_scripts', 'whitedot_customizer_control_js' );

//WhiteDot Regidter Customizer Settings
add_action( 'customize_register', 'whitedot_customize_register' ); 

//WhiteDot Customizer CSS to wp_head
add_action( 'wp_head', 'whitedot_customizer_css' );

//Customizer CSS
add_action( 'customize_controls_print_styles', 'whitedot_customizer_styles' );

//Integrating LifterLMS Sidebars
add_filter( 'llms_get_theme_default_sidebar', 'whitedot_llms_sidebar_function' );

if ( class_exists( 'Whitedot_Designer' ) ) {
	if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) { 
		add_action( 'customize_controls_enqueue_scripts', 'whitedot_customizer_hide_show_control_js' );
	}
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Gutenberg Compatibility.
 */
require get_template_directory() . '/inc/gutenberg.php';

/**
 * Customizer Functions additions.
 */
require get_template_directory() . '/inc/customizer/customizer-functions.php';

/**
 * Customizer Styles additions.
 */
require get_template_directory() . '/inc/customizer/customizer-styles.php';

/**
 * Customizer Custom Controls additions.
 */
require get_template_directory() . '/inc/customizer/custom-controls.php';

/**
 * Header Hooks
 */
require get_template_directory() . '/inc/hooks/header-hooks.php';

/**
 * Footer Hooks
 */
require get_template_directory() . '/inc/hooks/footer-hooks.php';

/**
 * General Hooks
 */
require get_template_directory() . '/inc/hooks/general-hooks.php';

/**
 * Hooks Actions
 */
require get_template_directory() . '/inc/hooks/hooks-actions.php';

/**
 * WhiteDot Setup
 */
require get_template_directory() . '/inc/function-parts/setup.php';

/**
 * WhiteDot Enqueue
 */
require get_template_directory() . '/inc/function-parts/enqueue.php';

/**
 * Meta Box
 */
require get_template_directory() . '/inc/meta-box.php';

/**
 * Theme Options Page
 */
require get_template_directory() . '/inc/options.php';

/**
 * Schema
 */
require get_template_directory() . '/inc/schema.php';


/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Load LifterLMS compatibility file.
 */
if ( class_exists( 'LifterLMS' ) ) {
	require get_template_directory() . '/inc/lifterlms.php';
}

/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function whitedot_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}else{
		return 35;
	}
}
add_filter( 'excerpt_length', 'whitedot_excerpt_length', 999 );




