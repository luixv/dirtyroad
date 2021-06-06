<?php
/**
 * The template for displaying blog home
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WhiteDot
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		/**
		 * whitedot_home_loop_before hook.
		 *
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_home_loop_before' ); ?>

		<?php if (have_posts()) : while (have_posts()) : the_post();

			get_template_part( 'template-parts/content', 'home', get_post_type() );

		endwhile; 

		/**
		 * whitedot_blog_home_pagination hook.
		 *
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_blog_home_pagination' ); 

		else : 

			get_template_part( 'template-parts/content', 'none' );
			
		endif; ?>

		<?php
		/**
		 * whitedot_home_loop_after hook.
		 *
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_home_loop_after' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>


<?php get_footer();