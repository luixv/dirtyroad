<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WhiteDot
 */

get_header();
?>

	
		
	<div id="<?php echo whitedot_primary_id(); ?>" class="content-area <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_page_sidebar_layout' ) ) { ?>sidebar-disabled<?php }else { ?>sidebar-enabled<?php } ?>">
		<main id="main" class="site-main">

			<div class="container-layout <?php echo whitedot_container_class(); ?>">

				<?php if (have_posts()) : while (have_posts()) : the_post();

					get_template_part( 'template-parts/content', 'page' );

				endwhile; else : 

					get_template_part( 'template-parts/content', 'none' );

				endif; ?>

			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>
	
<?php get_footer();
