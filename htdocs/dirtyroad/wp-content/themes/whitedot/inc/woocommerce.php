<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package WhiteDot
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @return void
 */
function whitedot_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'whitedot_woocommerce_setup' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
// add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function whitedot_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'whitedot_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function whitedot_woocommerce_products_per_page() {

	$wd_shop_perpage = absint( get_theme_mod( 'whitedot_shop_products_per_page', 12 ) );

	return $wd_shop_perpage;
}
add_filter( 'loop_shop_per_page', 'whitedot_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function whitedot_woocommerce_thumbnail_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'whitedot_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function whitedot_woocommerce_loop_columns() {

	$wd_clmns = absint( get_theme_mod( 'whitedot_woo_product_columns', 3 ) );

	return $wd_clmns;
}
add_filter( 'loop_shop_columns', 'whitedot_woocommerce_loop_columns' );


/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function whitedot_woocommerce_related_products_args( $args ) {

	$wd_related_clmns = absint( get_theme_mod( 'whitedot_woo_related_product_column', 3 ) );
	
	$wd_related_perpage = absint( get_theme_mod( 'whitedot_woo_related_product_per_page', 3 ) );

	$defaults = array(
		'posts_per_page' => $wd_related_perpage,
		'columns'        => $wd_related_clmns,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'whitedot_woocommerce_related_products_args' );

if ( ! function_exists( 'whitedot_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function whitedot_woocommerce_product_columns_wrapper() {
		$columns = whitedot_woocommerce_loop_columns();
		echo '<div class="whitedot-product-columns columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'whitedot_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'whitedot_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function whitedot_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'whitedot_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'whitedot_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function whitedot_woocommerce_wrapper_before() {
		?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">
			<?php
	}
}
add_action( 'woocommerce_before_main_content', 'whitedot_woocommerce_wrapper_before' );

if ( ! function_exists( 'whitedot_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function whitedot_woocommerce_wrapper_after() {
			?>
			</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'whitedot_woocommerce_wrapper_after' );

/**
 * Cart Fragments.
 *
 * Ensure cart contents update when products are added to the cart via AJAX.
 *
 * @param array $fragments Fragments to refresh via AJAX.
 * @return array Fragments to refresh via AJAX.
 */
function whitedot_header_add_to_cart_fragment( $fragments ) {
	$cart_count = WC()->cart->get_cart_contents_count();
	ob_start();
	?>
		<a class="wdcart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_html( __( 'View your shopping cart', 'whitedot' ) ); ?>"><?php echo esc_html( $cart_count ); ?></a> 
	<?php

	$fragments['a.wdcart-contents'] = ob_get_clean();

	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'whitedot_header_add_to_cart_fragment' );



/**
 * define the woocommerce_pagination_args callback 
 *
 */
function whitedot_woocommerce_pagination_args( $array ) { 
    
    

    $array = array( 
    	'prev_next' => true, 
    	'prev_text' => '<i class="fa fa-chevron-left"></i>', 
    	'next_text' => '<i class="fa fa-chevron-right"></i>'
    );
	                         


	return $array; 
}; 
         
add_filter( 'woocommerce_pagination_args', 'whitedot_woocommerce_pagination_args', 10, 1 ); 


/*===================================================================================
      Removing Woocommerce product count and adding product filter on its place
===================================================================================*/

/**
 * Display Product Filter
 */
add_action('template_redirect', 'whitedot_show_product_filter');
function whitedot_show_product_filter()
{
	if ( 0 == get_theme_mod( 'whitedot_show_product_filter', 1 ) ) { 

	}else{

		//Removing Product Count
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

		//Adding Product Filter
		add_action('woocommerce_before_shop_loop','whitedot_shop_product_filter', 25);
	}
}

function whitedot_shop_product_filter(){

 ?>

    <div class="whitedot-product-filter">
      <span class="whitedot-filter-button" onclick="filtertoggle()"><i class="fa fa-sliders" aria-hidden="true"></i><?php esc_html_e( 'Filter', 'whitedot' ); ?> </span>
    </div>

    <div id="filter-main" class="filter-wrap"> 
        <span id="remove-filter-wrap" onclick="filterremovetoggle()">&#x292B;</span>     
        <div class="whitedot-filter-widgets">
        	<?php if ( is_active_sidebar( 'whitedot-product-filter' )  ) { ?>
				<?php dynamic_sidebar( 'whitedot-product-filter' ); ?>	
			<?php }else{ ?>
				<?php if( current_user_can('editor') || current_user_can('administrator') ) {  ?>
					<div class="filter-message-wrap">
						<p class="product-filter-message"><?php esc_html_e( 'No Product Filter added.  Here, you can add Product Filter widgets for your customers to filter there products. ', 'whitedot' ); ?><a href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?url=<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>&autofocus[section]=sidebar-widgets-whitedot-product-filter"><?php esc_html_e( 'Add Widgets', 'whitedot' ); ?></a><br><br>

							<?php esc_html_e( 'If you don\'t need product filter, then you can replace the Filter Button with Product Count. ', 'whitedot' ); ?><a href="<?php echo esc_url( home_url() ); ?>/wp-admin/customize.php?url=<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>&autofocus[control]=whitedot_show_product_filter"><?php esc_html_e( 'Replace It', 'whitedot' ); ?></a> </p>
						<span><?php esc_html_e( 'This Message is only visible to Admin.', 'whitedot' ); ?></span>
					</div>
				<?php } ?>
        	<?php } ?>
        </div>      
    </div>

  <?php

}




// display an 'Out of Stock' label on archive pages
add_action( 'woocommerce_after_shop_loop_item_title', 'whitedot_woocommerce_template_loop_stock', 10 );
function whitedot_woocommerce_template_loop_stock() {
    global $product;
    if ( ! $product->managing_stock() && ! $product->is_in_stock() )
        echo '<span class="stock out-of-stock">Out of Stock</span>';
}

/**
 * Display Header Cart
 */
add_action('template_redirect', 'whitedot_display_header_cart');

function whitedot_display_header_cart()
{
  if ( 0 == get_theme_mod( 'whitedot_show_cart_in_header', 1 ) ) { 
    remove_action('whitedot_after_header_navigation','whitedot_header_cart', 10);
	remove_action('whitedot_header_content_after','whitedot_mob_header_cart', 10);
  }else{
  	add_action('whitedot_after_header_navigation','whitedot_header_cart', 10);
	add_action('whitedot_header_content_after','whitedot_mob_header_cart', 10);
  }
}

/**
 * Display Add to Cart
 */
add_action('template_redirect', 'whitedot_show_addtocart');
function whitedot_show_addtocart()
{
  if ( 0 == get_theme_mod( 'whitedot_show_add_to_cart', 1 ) ) { 
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');
  }
}


/**
 * Empty Cart Template
 */
remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_is_empty', 'whitedot_empty_cart_message', 10 );
function whitedot_empty_cart_message() {
	?>
	<div class="whitedot-empty-cart">
		<img src="<?php echo esc_url(get_template_directory_uri() . "/img/empty-cart.png"); ?>">
		<?php echo '<p class="whitedot-cart-empty-msg">' . wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'whitedot' ) ) ) . '</p>'; ?>
		<a class="button alt" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>">Return To Shop</a>
	</div>
	<?php
}


