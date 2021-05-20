<?php
/**
 * @package Automotive Centre
 * Setup the WordPress core custom header feature.
 *
 * @uses automotive_centre_header_style()
*/
function automotive_centre_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'automotive_centre_custom_header_args', array(
		'default-text-color'     => 'fff',
		'header-text' 			 =>	false,
		'width'                  => 1600,
		'height'                 => 183,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'automotive_centre_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'automotive_centre_custom_header_setup' );

if ( ! function_exists( 'automotive_centre_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see automotive_centre_custom_header_setup().
 */
add_action( 'wp_enqueue_scripts', 'automotive_centre_header_style' );

function automotive_centre_header_style() {
	//Check if user has defined any header image.
	if ( get_header_image() ) :
	$custom_css = "
        .home-page-header{
			background-image:url('".esc_url(get_header_image())."');
			background-position: center top;
		}";
	   	wp_add_inline_style( 'automotive-centre-basic-style', $custom_css );
	endif;
}
endif;