<?php
/**
 * The header template file
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
//topline text
$exs_text = exs_option( 'meta_topline_text', '' );

$exs_fluid              = exs_option( 'topline_fluid' ) ? '-fluid' : '';
$exs_topline_background = exs_option( 'topline_background', '' );
if ( is_page_template( 'page-templates/header-overlap.php' ) ) {
	$exs_topline_background = 'i';
}
$exs_font_size          = exs_option( 'topline_font_size', '' );
$exs_login_links        = exs_option( 'topline_login_links' );
?>
<div id="topline" class="topline <?php echo esc_attr( $exs_topline_background . ' ' . $exs_font_size ); ?>">
	<div class="container<?php echo esc_attr( $exs_fluid ); ?>">
		<?php if ( ! empty( $exs_text ) ) : ?>
			<div id="topline_dropdown" class="dropdown">
				<button id="topline_dropdown_toggle" class="nav-btn type-dots"
						aria-controls="topline_dropdown"
						aria-expanded="false"
						aria-label="<?php esc_attr_e( 'Topline Info Toggler', 'exs' ); ?>"
				>
					<span></span>
				</button>

				<span class="dropdown-menu dropdown-menu-md topline-custom-text">
					<?php if ( ! empty( $exs_text ) ) : ?>
						<span>
						<?php echo wp_kses_post( $exs_text ); ?>
						</span>
					<?php endif; //text ?>
				</span><!-- .topline-custom-text -->
			</div><!-- #topline_dropdown -->
			<?php
		endif; //! empty text

		if ( ! empty( $exs_login_links ) ) :
			get_template_part( 'template-parts/header/login-links' );
		endif; //search

		exs_social_links();
		?>
	</div><!-- .container -->
</div><!-- #topline -->
