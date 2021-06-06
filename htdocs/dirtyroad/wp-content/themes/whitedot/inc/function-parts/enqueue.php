<?php
/**
 * Enqueue scripts and styles.
 */

//Customizer CSS
function whitedot_customizer_styles() {

	wp_register_style( 'whitedot-customizer-css', get_template_directory_uri() . '/css/minified/customizer.min.css', NULL, NULL, 'all' );
	wp_enqueue_style( 'whitedot-customizer-css' );

}

//Script
function whitedot_scripts() {
	wp_enqueue_style( 'whitedot-style', get_stylesheet_uri() );

	wp_enqueue_style('font-awesome-min', get_stylesheet_directory_uri() . '/css/font-awesome.min.css'); 

	// wp_enqueue_style('whitedot-style-minified', get_stylesheet_directory_uri() . '/css/minified/main-style.min.css', array(), '1.0.94' );

	wp_enqueue_style('whitedot-style-minified', get_stylesheet_directory_uri() . '/css/unminified/main-style.css', array(), '1.0.94' );
	
	wp_enqueue_script('whitedot-main-js', get_template_directory_uri() . '/js/script.js', array('jquery'), '', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function whitedot_customize_preview_js() {
	wp_enqueue_script( 'whitedot-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}

/**
 * JS file for Customizer custom controls.
 */
function whitedot_customize_custom_js() {
	wp_enqueue_script( 'whitedot-customizer-custom', get_template_directory_uri() . '/js/customizer-custom.js', array( 'customize-preview' ), '20151215', true );
}

/**
 * Custom js for Theme Customizer Control
 */
function whitedot_customizer_control_js() {
    wp_enqueue_script( 'whitedot_customizer_control', get_template_directory_uri() . '/js/customizer-control.js', array( 'jquery', 'customize-controls' ), '20151215', true );
}

/**
 * Register and enqueue a custom stylesheet in the WordPress admin.
 */
function whitedot_enqueue_custom_admin_style() {
        wp_register_style( 'whitedot-admin-css', get_template_directory_uri() . '/css/unminified/admin.css', false, '1.0.94' );
        wp_enqueue_style( 'whitedot-admin-css' );
}

/**
 * Custom js for Theme Customizer Hide-Show Control
 */
function whitedot_customizer_hide_show_control_js() {
    wp_enqueue_script( 'whitedot_customizer_hide_show_control', get_template_directory_uri() . '/js/customizer-control-hide-show.js', array( 'jquery', 'customize-controls' ), '20180623', true );
}

/**
 * Adding Editor Style CSS File
 */
function whitedot_editor_styles_css() {
    wp_enqueue_style( 'whitedot-editor-style', get_template_directory_uri() . '/css/editor-style.css' );
    // wp_enqueue_style( 'theme-slug-fonts', theme_slug_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'whitedot_editor_styles_css' );

