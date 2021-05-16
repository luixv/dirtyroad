<?php
/**
 * The WooCommerce cart dropdown template file
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

if ( ! is_cart() && ! is_checkout() && exs_option( 'header_cart_dropdown' ) ) :
	?>
	<div class="cart-dropdown">
		<a class="dropdown-toggle" href="#" role="button" id="dropdown-cart-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="wc-icon-cart"></span>
			<?php
			echo '<span class="cart-count">';
		if ( WC()->cart->get_cart_contents_count() !== 0 ) {
			echo esc_html( WC()->cart->get_cart_contents_count() );
		}
			echo '</span>';
		?>
		</a>
		<div class="cart-dropdown-menu dropdown-menu-right" id="dropdown-cart" aria-labelledby="dropdown-cart-toggle">
			<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
		</div>
	</div>
	<?php
endif; //is_cart check
