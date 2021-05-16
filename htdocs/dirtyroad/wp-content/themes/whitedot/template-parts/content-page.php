<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WhiteDot
 */



/**
 * whitedot_main_content_before hook.
 *
 *
 * @since 0.1
 */
do_action( 'whitedot_main_content_before' ); ?>

<article itemtype="https://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	/**
	 * whitedot_page_content_before hook.
	 *
	 * @hooked whitedot_thumbnail  - 10
	 * @hooked whitedot_post_header  - 20
	 *
	 * @since 0.1
	 */
	do_action( 'whitedot_page_content_before' ); ?>

	<div class="wd-custom-content" itemprop="text">

		<?php
		/**
		 * whitedot_page_custom_before hook.
		 *
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_page_custom_before' ); 

		the_content();


		/**
		 * whitedot_page_custom_after hook.
		 *
		 * @hooked whitedot_post_pagation  - 10
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_page_custom_after' ); 

		
		?>
	</div><!-- .wd-custom-content -->

	<?php
		/**
		 * whitedot_page_content_after hook.
		 *
		 * @hooked whitedot_post_comment  - 10
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_page_content_after' ); 
		?>


</article><!-- #post-<?php the_ID(); ?> -->

<?php
/**
 * whitedot_main_content_after hook.
 *
 *
 * @since 0.1
 */
do_action( 'whitedot_main_content_after' ); ?>
