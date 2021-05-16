<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WhiteDot
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> >
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php whitedot_body_tag_schema(); ?> <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'whitedot' ); ?></a>

	<?php
	/**
	 * whitedot_before_header hook.
	 *
	 * @since 1.0.0.0
	 *
	 */
	do_action( 'whitedot_before_header' );

	/**
	 * whitedot_header_content hook.
	 *
	 * @since 1.0.0.0
	 *
	 */
	do_action( 'whitedot_header_content' );

	
	/**
	 * whitedot_after_header hook.
	 *
	 * @since 1.0.0.0
	 *
	 */
	do_action( 'whitedot_after_header' );?>



	<div id="content" class="site-content">

		<!-- <div class="col-full"> -->

			<?php
			/**
			 * whitedot_header_end hook.
			 *
			 * @since 1.0.0.2
			 *
		 	 * @hooked whitedot_header_column_full_open   - 10
			 *
			 */
			do_action( 'whitedot_header_end' );
