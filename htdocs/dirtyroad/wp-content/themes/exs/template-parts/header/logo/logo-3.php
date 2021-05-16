<?php
/**
 * The logo template file
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

$exs_custom_logo         = exs_option( 'custom_logo' );
$exs_logo_image_class    = ( ! empty( $exs_custom_logo ) ) ? 'with-image' : 'no-image';
$exs_logo_text_primary   = exs_option( 'logo_text_primary' );
$exs_logo_text_secondary = exs_option( 'logo_text_secondary' );
$exs_logo_background     = exs_option( 'logo_background' );
$exs_logo_x_padding      = exs_option( 'logo_padding_horizontal' );
$exs_logo_padding_class  = ( ! empty( $exs_logo_x_padding ) ) ? 'px' : '';

//if no text - get blog name for primary text
if ( empty( $exs_logo_text_primary ) && empty( $exs_logo_text_secondary ) && empty( $exs_custom_logo ) ) {
	$exs_logo_text_primary = get_bloginfo( 'name' );
}
$exs_logo_primary_text_hidden_class = exs_option( 'logo_text_primary_hidden' );
$exs_logo_secondary_text_hidden_class = exs_option( 'logo_text_secondary_hidden' );

//font sizes
$exs_logo_primary_fs = ( ! empty( exs_option( 'logo_text_primary_fs', '' ) ) ) ? 'fs-' . exs_option( 'logo_text_primary_fs', '' ) : 'fs-inherit';
$exs_logo_primary_fs_xl = ( ! empty( exs_option( 'logo_text_primary_fs_xl', '' ) ) ) ? 'fs-xl-' . exs_option( 'logo_text_primary_fs_xl', '' ) : 'fs-xl-inherit';
$exs_logo_secondary_fs = ( ! empty( exs_option( 'logo_text_secondary_fs', '' ) ) ) ? 'fs-' . exs_option( 'logo_text_secondary_fs', '' ) : 'fs-inherit';
$exs_logo_secondary_fs_xl = ( ! empty( exs_option( 'logo_text_secondary_fs_xl', '' ) ) ) ? 'fs-xl-' . exs_option( 'logo_text_secondary_fs_xl', '' ) : 'fs-xl-inherit';
?>
<a id="logo" class="logo logo-between <?php echo esc_attr( $exs_logo_image_class . ' ' . $exs_logo_background . ' ' . $exs_logo_padding_class ); ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" itemprop="url">
	<?php if ( ! empty( $exs_logo_text_primary ) ) : ?>
		<span class="logo-text-primary <?php echo esc_attr( $exs_logo_primary_text_hidden_class ); ?>">
			<span class="<?php echo esc_attr( $exs_logo_primary_fs . ' ' . $exs_logo_primary_fs_xl ); ?>">
			<?php echo wp_kses_post( $exs_logo_text_primary ); ?>
			</span>
		</span><!-- .logo-text-primary -->
		<?php
	endif;

	//image
	if ( ! empty( $exs_custom_logo ) ) {
		echo wp_get_attachment_image( $exs_custom_logo, 'full' );
	}

	if ( ! empty( $exs_logo_text_secondary ) ) :
		?>
		<span class="logo-text-secondary <?php echo esc_attr( $exs_logo_secondary_text_hidden_class ); ?>">
			<span class="<?php echo esc_attr( $exs_logo_secondary_fs . ' ' . $exs_logo_secondary_fs_xl ); ?>">
			<?php echo wp_kses_post( $exs_logo_text_secondary ); ?>
			</span>
	</span><!-- .logo-text-secondary -->
	<?php endif; ?>
</a><!-- .logo -->
