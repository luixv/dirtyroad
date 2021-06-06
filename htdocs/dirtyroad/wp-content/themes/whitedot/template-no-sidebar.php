<?php
/**
 * The template for displaying No Sidebar pages.
 *
 * Template Name: No Sidebar
 *
 * @package whitedot
 */

get_header();
?>
		
	<div id="primary-full-width" class="sidebar-none contained">
		<main id="main" class="site-main">

		<?php if (have_posts()) : while (have_posts()) : the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; else : 

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
	
<?php get_footer();