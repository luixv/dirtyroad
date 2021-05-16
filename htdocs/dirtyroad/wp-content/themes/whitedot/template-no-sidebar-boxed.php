<?php
/**
 * The template for displaying No Sidebar pages.
 *
 * Template Name: No Sidebar Boxed
 *
 * @package whitedot
 */

get_header();
?>
		
	<div id="primary-full-width" class="sidebar-none boxed">
		<main id="main" class="site-main">
			<div class="boxed-layout">
				<?php if (have_posts()) : while (have_posts()) : the_post();

					get_template_part( 'template-parts/content', 'page' );

				endwhile; else : 

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
	
<?php get_footer();