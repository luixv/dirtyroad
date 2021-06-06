<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WhiteDot
 */

get_header();
?>
	<div id="primary" class="full-width content-area">
		<main id="main" class="site-main">

			<section class="error-404 not-found">
				<header class="search-page-header">
					<div class="wd-error-img">
						<img src="<?php echo esc_url(get_template_directory_uri() . "/img/404error.png"); ?>">
					</div>
					<h1 class="search-page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'whitedot' ); ?></h1>
				</header><!-- .page-header -->

				<div class="search-page-content">
					<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try to search here?', 'whitedot' ); ?></p>

					<?php get_search_form();?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->
	
<?php
get_footer();
