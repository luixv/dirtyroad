<?php
/**
 * Template part for displaying blog home
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WhiteDot
 */



/**
 * whitedot_main_blog_home_before hook.
 *
 *
 * @since 0.1
 */
do_action( 'whitedot_main_blog_home_before' ); ?>

<div class = "wd-single-post <?php if ( has_post_thumbnail() ) { ?>has-thumb<?php }else{?>no-thumb<?php } ?>">
	<article itemtype="https://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class() ?>>

		<?php
		/**
		 * Functions hooked into whitedot_blog_home_content_before add_action
		 *
		 * @hooked whitedot_blog_list_thumbnail  - 10
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_blog_home_content_before' ); ?>

		<div class="wd-excerpt-container <?php if ( ! has_post_thumbnail() ) { ?>full<?php } ?>">

			<?php
			/**
			 * Functions hooked into whitedot_blog_home_content add_action
			 *
			 * @hooked whitedot_blog_list_meta  - 10
			 * @hooked whitedot_blog_list_header  - 20
			 * @hooked whitedot_blog_list_excerpt  - 30
			 *
			 * @since 0.1
			 */
			do_action( 'whitedot_blog_home_content' ); ?>

		</div>

		<?php
		/**
		 * whitedot_blog_home_content_after hook
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_blog_home_content_after' ); ?>

	</article>
</div>

<?php
/**
 * whitedot_main_blog_home_after hook.
 *
 *
 * @since 0.1
 */
do_action( 'whitedot_main_blog_home_after' ); 