<?php
/**
 * The template for displaying archive pages
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
			 * @since 1.1.06
			 */
			do_action( 'whitedot_archive_loop_before' ); ?>

			<?php if ( have_posts() ) : ?>

				<?php
				/**
				 * whitedot_home_loop_before hook.
				 *
				 *
				 * @since 1.1.08
				 */
				do_action( 'whitedot_archive_header' ); ?>

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-home.php (where home is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'home', get_post_type() );

			endwhile;

				/**
				 * whitedot_blog_home_pagination hook.
				 *
				 *
				 * @since 1.0
				 */
				do_action( 'whitedot_blog_home_pagination' ); 

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

			<?php
			/**
			 * whitedot_home_loop_after hook.
			 *
			 *
			 * @since 1.1.06
			 */
			do_action( 'whitedot_archite_loop_after' ); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>

<?php get_footer();
