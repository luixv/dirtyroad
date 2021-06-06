<?php
/**
 * Header Hooks
 *
 * @package WhiteDot
 */

/**
 * WhiteDot Header Content
 *
 * @since 1.0.0
 */
function whitedot_header_content(){

?>

<header itemtype="http://schema.org/WPHeader" itemscope="itemscope" id="masthead" class="site-header not-transparent">

	<?php
	/**
	 * whitedot_before_header_wrap hook.
	 *
	 * @since 1.0.0
	 *
	 */
	do_action( 'whitedot_before_header_wrap' ); ?>

	<div class="main-header clear not-transparent">

		<div class="col-full">

			<?php
			/**
			 * whitedot_header_content_before hook.
			 *
			 * @since 1.0.0
			 *
			 * @hooked whitedot_header_hamburger - 10
			 */
			do_action( 'whitedot_header_content_before' );?>

			
			<div itemscope itemtype="http://schema.org/Organization">

				<?php
				/**
				 * whitedot_header_branding hook.
				 *
				 * @since 1.0.0
				 *
				 * @hooked whitedot_header_logo - 10
				 * @hooked whitedot_header_identity - 20
				 */
				do_action( 'whitedot_header_branding' );?>
		
			</div>

			<?php
			/**
			 * whitedot_header_nav hook.
			 *
			 * @since 1.0.0
			 *
			 * @hooked whitedot_header_navigation - 10
			 */
			do_action( 'whitedot_header_nav' );?>

			<?php
			/**
			 * whitedot_header_content_after hook.
			 *
			 * @since 1.0.0
			 *
			 * @hooked whitedot_mob_header_cart - 10
			 */
			do_action( 'whitedot_header_content_after' );?>

			
		</div><!-- .col-full -->

	</div><!-- .main-header -->

	<?php 
	/**
	 * whitedot_after_header_wrap hook.
	 *
	 * @since 1.0
	 *
	 */
	do_action( 'whitedot_after_header_wrap' ); ?>
	
</header><!-- #masthead -->

<?php 
}

/**
 * WhiteDot Header Content
 *
 * @since 1.0.0
 */
function whitedot_temp_header_content(){

?>

<header itemtype="http://schema.org/WPHeader" itemscope="itemscope" id="masthead" class="site-header is-transparent <?php if ( 1 === get_theme_mod( 'whitedot_sticky_transparent_header', 0 ) || whitedot_settings_get_meta( 'whitedot_settings_sticky_header' ) === 'Enabled' ) { ?>transparent-fixed-head<?php } ?>">

	<?php
	/**
	 * whitedot_before_header_wrap hook.
	 *
	 * @since 1.0.0
	 *
	 */
	do_action( 'whitedot_before_header_wrap' ); ?>

	<div class="transparent-main-header clear <?php if ( 1 === get_theme_mod( 'whitedot_sticky_transparent_header', 0 ) || whitedot_settings_get_meta( 'whitedot_settings_sticky_header' ) === 'Enabled' ) { ?>transparent-main-fixed-head<?php } ?>">

		<div class="col-full">

			<?php
			/**
			 * whitedot_header_content_before hook.
			 *
			 * @since 1.0.0
			 *
			 * @hooked whitedot_header_hamburger - 10
			 */
			do_action( 'whitedot_header_content_before' );?>

			
			<div itemscope itemtype="http://schema.org/Organization">

				<?php
				/**
				 * whitedot_header_branding hook.
				 *
				 * @since 1.0.0
				 *
				 * @hooked whitedot_temp_header_logo - 10
				 * @hooked whitedot_temp_header_identity - 20
				 */
				do_action( 'whitedot_temp_header_branding' );?>
		
			</div>

			<?php
			/**
			 * whitedot_header_nav hook.
			 *
			 * @since 1.0.0
			 *
			 * @hooked whitedot_header_navigation - 10
			 */
			do_action( 'whitedot_header_nav' );?>

			<?php
			/**
			 * whitedot_header_content_after hook.
			 *
			 * @since 1.0.0
			 *
			 * @hooked whitedot_mob_header_cart - 10
			 */
			do_action( 'whitedot_header_content_after' );?>

			
		</div><!-- .col-full -->

	</div><!-- .main-header -->

	<?php 
	/**
	 * whitedot_after_header_wrap hook.
	 *
	 * @since 1.0
	 *
	 */
	do_action( 'whitedot_after_header_wrap' ); ?>
	
</header><!-- #masthead -->

<?php 
}



/**
 * WhiteDot Header Hamburger
 *
 * @since 1.0.0
 */
function whitedot_header_hamburger(){

?>

<button class="wd-hamburger wd-hamburger--htx" onclick="wd_menu_toggle()">
	<span><?php esc_html_e( 'toggle menu', 'whitedot' ); ?></span>
</button>

<?php

}

/**
 * WhiteDot Header Logo
 *
 * @since 1.0.0
 */
function whitedot_header_logo(){

	if ( has_custom_logo() ) {?>
		<div class="wd-site-logo <?php if ( get_theme_mod( 'whitedot_mobile_header_logo' ) ) : ?>mobile-logo-active<?php endif; ?>"> <span><?php the_custom_logo(); ?></span></div>
	<?php } 

}

/**
 * whitedot Home Template Header Logo
 *
 * @since 1.0
 */
function whitedot_temp_header_logo(){

	if ( get_theme_mod( 'whitedot_home_temp_logo' ) ) { ?>

		<div class="wd-home-temp-logo <?php if ( get_theme_mod( 'whitedot_mobile_header_logo' ) ) : ?>mobile-logo-active<?php endif; ?>"> 
			<span>
				<a itemprop="url" href="<?php echo esc_url( home_url() ); ?>">
					<img itemprop="logo" class="home-temp-logo-img" src="<?php echo esc_url( get_theme_mod( 'whitedot_home_temp_logo' ) ); ?>">
				</a>
			</span>
		</div>

	<?php }

	if ( has_custom_logo() ) {?>
		<div class="wd-site-logo <?php if ( get_theme_mod( 'whitedot_home_temp_logo' ) ) : ?>temp-logo<?php endif; ?> <?php if ( get_theme_mod( 'whitedot_mobile_header_logo' ) ) : ?>mobile-logo-active<?php endif; ?>"> 
			<span>
				<?php the_custom_logo(); ?>
			</span>
		</div>
	<?php } 

}



/**
 * WhiteDot Header Identity
 *
 * @since 1.0.0
 */
function whitedot_header_identity(){

?>

	<div class="site-branding <?php if ( get_theme_mod( 'whitedot_mobile_header_logo' ) ) : ?>mobile-logo-active<?php endif; ?>" <?php if ( has_custom_logo() ) : ?>style="position: absolute; font-size: 1px; top: -300px;"<?php endif; ?>>
		<?php 
			if ( is_front_page() && is_home() ) : ?>
				<h1><a itemprop="url" class="site-name" href="<?php echo esc_url( home_url() ); ?>"><span itemprop="name"><?php bloginfo('name'); ?></span></a></h1>
		 <?php else :?>
		 	<a itemprop="url" class="site-name" href="<?php echo esc_url( home_url() ); ?>"><span itemprop="name"><?php bloginfo('name'); ?></span></a>
		 <?php endif; ?>

		<?php 
		$whitedot_description = get_bloginfo( 'description', 'display' );
		if ( $whitedot_description || is_customize_preview() ) :
			?>
			<p itemprop="description" class="site-description"><?php echo $whitedot_description; /* WPCS: xss ok. */ ?></p>
		<?php endif; ?>
	</div>

<?php 

}

/**
 * WhiteDot Header Identity
 *
 * @since 1.0.0
 */
function whitedot_temp_header_identity(){

?>

	<div class="site-branding <?php if ( get_theme_mod( 'whitedot_mobile_header_logo' ) ) : ?>mobile-logo-active<?php endif; ?>" <?php if ( has_custom_logo() || get_theme_mod( 'whitedot_home_temp_logo' ) ) : ?>style="position: absolute; font-size: 1px; top: -3000px;"<?php endif; ?>>
		<?php 
			if ( is_front_page() && is_home() ) : ?>
				<h1><a itemprop="url" class="site-name" href="<?php echo esc_url( home_url() ); ?>"><span itemprop="name"><?php bloginfo('name'); ?></span></a></h1>
		 <?php else :?>
		 	<a itemprop="url" class="site-name" href="<?php echo esc_url( home_url() ); ?>"><span itemprop="name"><?php bloginfo('name'); ?></span></a>
		 <?php endif; ?>

		<?php 
		$whitedot_description = get_bloginfo( 'description', 'display' );
		if ( $whitedot_description || is_customize_preview() ) :
			?>
			<p itemprop="description" class="site-description"><?php echo $whitedot_description; /* WPCS: xss ok. */ ?></p>
		<?php endif; ?>
	</div>

<?php 

}

/**
 * WhiteDot Header Navigation
 *
 * @since 1.0.0
 */
function whitedot_header_navigation(){
	?>

	<div id="wd-primary-nav" class="site-nav">

		<?php
		/**
		 * whitedot_before_header_navigation hook.
		 *
		 * @since 1.0.0
		 *
		 */
		do_action( 'whitedot_before_header_navigation' ); ?>

		<nav itemtype="http://schema.org/SiteNavigationElement" itemscope class="primary-nav <?php if ( class_exists( 'WooCommerce' ) ) {?>has-wp-cart<?php } ?> <?php if ( 1 == get_theme_mod( 'whitedot_show_search_in_header', 1 ) ) { ?>has-header-search<?php } ?>">
			<?php 
				$defaults = array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				);
				wp_nav_menu ( $defaults );
			 ?>
		</nav>

		<?php
		/**
		 * whitedot_after_header_navigation hook.
		 *
		 * @hooked whitedot_header_cart - 10
		 *
		 * @since 1.0.0
		 *
		 */
		do_action( 'whitedot_after_header_navigation' ); ?>

	</div>

<?php }


/**
 * WhiteDot Header Cart
 *
 * @since 1.0.0
 */
function whitedot_header_cart(){

	$cart_count = WC()->cart->get_cart_contents_count();

	if ( class_exists( 'WooCommerce' ) ) {?>
		<span class="wd-cart <?php if ( 1 == get_theme_mod( 'whitedot_show_search_in_header', 1 ) ) { ?>has-sibling<?php } ?>">
			<div class="wd-cart-container"> 
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<i class="fa fa-shopping-cart pkcart-icon" aria-hidden="true"></i>
				</a>
				<a class="wdcart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_html( __( 'View your shopping cart', 'whitedot' ) ); ?>">

					<?php echo esc_html( $cart_count ); ?>
					
				</a> 
			</div><!--.wd-cart-container -->
					<div class="wd_minicart_hover">
						<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
					</div><!--.widget_shopping_cart -->
		</span>
	<?php }

}

/**
 * WhiteDot Header Cart
 *
 * @since 1.0.0
 */
function whitedot_mob_header_cart(){

	$cart_count = WC()->cart->get_cart_contents_count();

	if ( class_exists( 'WooCommerce' ) ) {?>
			<span class="wd-cart-mob <?php if ( 1 == get_theme_mod( 'whitedot_show_search_in_header', 1 ) ) { ?>has-sibling<?php } ?>">
				<div class="wd-cart-container-mob"> 
				<a href="<?php echo esc_url( wc_get_cart_url() ); ?>">
					<i class="fa fa-shopping-cart pkcart-icon" aria-hidden="true"></i>
				</a>
				<a class="wdcart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_html( __( 'View your shopping cart', 'whitedot' ) ); ?>">

					<?php echo esc_html( $cart_count ); ?>
					
				</a> 
			</div><!--wd-cart-container-mob -->
		</span>
	<?php }

}

/**
 * WhiteDot Header Search Bar
 *
 * @since 1.0.93
 */
function whitedot_header_search_bar(){

	?>
	<span class="wd-search">
		<span onclick="wd_search_open()" class="wd-header-search-btn">
			<i class="fa fa-search" aria-hidden="true"></i>
		</span>
	</span>
	<div class="wd-header-search-form" id="wd-header-search">
		<form role="search" method="get" class="wd-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label>
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'whitedot' ); ?></span>
				<input type="search" class="wd-search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'whitedot' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
			</label>
		</form>
	<span onclick="wd_search_close()" class="wd-header-search-close" ><i class="fa fa-times" aria-hidden="true"></i></span>
	</div>
	<?php 

}

/**
 * WhiteDot Mobile Header Search Bar
 *
 * @since 1.0.93
 */
function whitedot_mob_header_search_bar(){

	?>
	<span class="wd-mob-search">
		<span onclick="wd_mob_search_open()" class="wd-mob-header-search-btn">
			<i class="fa fa-search" aria-hidden="true"></i>
		</span>
	</span>
	<div class="wd-mob-header-search-form" id="wd-mob-header-search">
		<form role="search" method="get" class="wd-mob-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label>
				<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'whitedot' ); ?></span>
				<input type="search" class="wd-mob-search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'whitedot' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
			</label>
		</form>
	<span onclick="wd_mob_search_close()" class="wd-mob-header-search-close" ><i class="fa fa-times" aria-hidden="true"></i></span>
	</div>
	<?php 

}

/**
 * Whitedot Mobile Header Logo
 *
 * @since 1.2.2
 */
function whitedot_mob_header_logo(){

	if ( get_theme_mod( 'whitedot_mobile_header_logo' ) ) { ?>

		<div class="wd-mobile-header-logo"> 
			<span>
				<a itemprop="url" href="<?php echo esc_url( home_url() ); ?>">
					<img itemprop="logo" class="mobile-header-logo-img" src="<?php echo esc_url( get_theme_mod( 'whitedot_mobile_header_logo' ) ); ?>">
				</a>
			</span>
		</div>

	<?php }

}

/**
 * WhiteDot Header Content
 *
 * @since 1.2.3
 */
function whitedot_header_column_full_open(){
	?>
	<div class="col-full">
	<?php
}

/**
 * WhiteDot Header Content
 *
 * @since 1.2.3
 */
function whitedot_header_column_full_close(){
	?>
	</div><!-- .col-full -->
	<?php
}
