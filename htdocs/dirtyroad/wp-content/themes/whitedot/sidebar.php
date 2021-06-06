<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WhiteDot
 */


/**
 * whitedot_before_sidebar hook.
 *
 * @since 1.0.0
 *
 */
do_action( 'whitedot_before_sidebar' );

/**
 * Functions hooked into whitedot_the_sidebar add_action
 *
 * @hooked whitedot_main_sidebar  - 10
 *
 * @since 0.1
 */
do_action( 'whitedot_the_sidebar' );

/**
 * whitedot_after_sidebar hook.
 *
 * @since 1.0.0
 *
 */
do_action( 'whitedot_after_sidebar' );


