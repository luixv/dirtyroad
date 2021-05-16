<?php
/**
 * The footer section blank template file
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( is_customize_preview() ) {
	echo '<div id="to-top-wrap">';
}

$exs_to_top = exs_option( 'totop', '' );
//page totop button
if ( ! empty( $exs_to_top ) ) :
	?>
	<a id="to-top" href="#body">
		<span class="screen-reader-text">
			<?php esc_html_e( 'Go to top', 'exs' ); ?>
		</span>
	</a>
<?php
endif; //totop_enabled

if ( is_customize_preview() ) {
	echo '</div>';
}
