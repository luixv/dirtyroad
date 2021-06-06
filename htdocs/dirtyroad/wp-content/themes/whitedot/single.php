<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WhiteDot
 */

get_header();
?>
	<div id="<?php echo whitedot_primary_id(); ?>" class="content-area">
		<main id="main" class="site-main">
		<?php if (have_posts()) : while (have_posts()) : the_post(); 

			get_template_part( 'template-parts/content', 'single', get_post_type() );

		endwhile; else : 

			get_template_part( 'template-parts/content', 'none' );
			
		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>

<?php get_footer();
