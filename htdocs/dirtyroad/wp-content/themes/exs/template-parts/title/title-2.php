<?php
/**
 * The title section template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$exs_fluid            = exs_option( 'title_fluid' ) ? '-fluid' : '';
$exs_show_title       = exs_option( 'title_show_title', '' );
$exs_show_breadcrumbs = exs_breadcrumbs_enabled();
$exs_show_search      = exs_option( 'title_show_search', '' );

$exs_title_background     = exs_option( 'title_background', '' );
$exs_extra_padding_top    = exs_option( 'title_extra_padding_top', '' );
$exs_extra_padding_bottom = exs_option( 'title_extra_padding_bottom', '' );
$exs_border_top           = exs_option( 'title_border_top', '' );
$exs_border_bottom        = exs_option( 'title_border_bottom', '' );
$exs_font_size            = exs_option( 'title_font_size', '' );
$exs_main_css_classes     = exs_get_page_main_section_css_classes();
$exs_background_image     = exs_section_background_image_array( 'title' );

?>
<section id="title" class="title title-2 title-inline <?php echo esc_attr( $exs_title_background . ' ' . $exs_font_size . ' ' . $exs_background_image['class'] . ' ' . $exs_main_css_classes ); ?>"
	<?php echo ( ! empty( $exs_background_image['url'] ) ) ? 'style="background-image: url(' . esc_url( $exs_background_image['url'] ) . ');"' : ''; ?>
>
	<?php
	if ( 'full' === $exs_border_top ) {
		echo wp_kses_post( '<hr class="section-hr">' );
	}
	?>
	<div class="container<?php echo esc_attr( $exs_fluid ); ?> <?php echo esc_attr( $exs_extra_padding_top . ' ' . $exs_extra_padding_bottom ); ?>">
		<?php
		if ( 'container' === $exs_border_top ) {
			echo wp_kses_post( '<hr class="section-hr">' );
		}
		if ( ! empty( $exs_show_title ) ) {
			?>
			<h1 itemprop="headline"><?php get_template_part( 'template-parts/title/title-text' ); ?></h1>
			<?php
		} //show_title

		if ( ! empty( $exs_show_breadcrumbs ) ) {
			exs_breadcrumbs();
		}
		if ( ! empty( $exs_show_search ) ) {
			get_search_form();
		}

		if( is_single() ) :
			echo '<div class="entry-footer">';
			exs_entry_meta( true, true, true, true, true, false, true );
			echo '</div>';
		endif;

		if ( 'container' === $exs_border_bottom ) {
			echo wp_kses_post( '<hr class="section-hr">' );
		}
		?>
	</div><!-- .container -->
	<?php
	if ( 'full' === $exs_border_bottom ) {
		echo wp_kses_post( '<hr class="section-hr">' );
	}
	?>
</section><!-- #title -->
