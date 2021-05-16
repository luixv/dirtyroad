<?php
/**
 * WhiteDot Gutenberg Compatibility
 *
 * @package WhiteDot
 */

/**
 * Declare explicit theme support for LifterLMS course and lesson sidebars
 * @return   void
 */
function whitedot_gutenberg_theme_support(){

	//Wide Alignment
	add_theme_support( 'align-wide' );

	
}
add_action( 'after_setup_theme', 'whitedot_gutenberg_theme_support' );

