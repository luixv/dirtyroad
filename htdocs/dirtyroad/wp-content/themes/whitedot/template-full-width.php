<?php
/**
 * The template for displaying Left Sidebar pages.
 *
 * Template Name: Full Width (Page Builder)
 *
 * @package whitedot
 */

get_header();
?>
		
	<div class="content-page-builder">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

			<div class="page-builder-wrap">
				<?php the_content(); ?>
			</div>

		<?php endwhile; else : 

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

	</div><!-- .content-page-builder -->
	
<?php get_footer();

