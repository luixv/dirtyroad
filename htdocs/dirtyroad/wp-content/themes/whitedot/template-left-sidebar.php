<?php
/**
 * The template for displaying Left Sidebar pages.
 *
 * Template Name: Left Sidebar
 *
 * @package whitedot
 */

get_header();
?>
		
	<div id="primary-right" class="sidebar-left contained">
		<main id="main" class="site-main">

		<?php if (have_posts()) : while (have_posts()) : the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; else : 

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php if ( is_active_sidebar( 'sidebar-1' )  ) : ?>
		<div itemtype="http://schema.org/WPSideBar" itemscope class="secondary-left">
			<div class="wd-sidebar">
				<div class="wd-widget-area">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>	
				</div><!--.wd-widget-area-->
			</div><!--.wd-sidebar-->
		</div><!--.secondary-->
	<?php endif; ?>
	
<?php get_footer();