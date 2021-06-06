<?php
/**
 * Footer Hooks
 *
 * @package WhiteDot
 */


/**
 * WhiteDot Footer Branding
 *
 * @since 1.0.0
 */
function whitedot_footer_branding(){

?>
	<div itemscope itemtype="http://schema.org/Organization" class="wd-footer-branding">

		<?php
		/**
		 * whitedot_single_post_content_after hook.
		 *
		 * @hooked whitedot_footer_site_title  		- 10
		 * @hooked whitedot_footer_site_description - 20
		 * @hooked whitedot_footer_social_links - 30
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_footer_branding_content' );?>
		
	</div><!--.wd-footer-branding -->

<?php

}

/**
 * WhiteDot Footer Site Title
 *
 * @since 1.0.0
 */
function whitedot_footer_site_title(){

?>
	<a class="wd-footer-title" itemprop="url" href="<?php echo esc_url( home_url() ); ?>"><span itemprop="name"><?php bloginfo('name'); ?></span></a>
<?php

}

/**
 * WhiteDot Footer Site Description
 *
 * @since 1.0.0
 */
function whitedot_footer_site_description(){

?>
	<p itemprop="description" class="footer-site-description"><?php bloginfo( 'description' ); ?></p>
<?php

}


/**
 * WhiteDot Footer Site Widgets
 *
 * @since 1.0.0
 */
function whitedot_footer_widgets(){

?>
	<div class="col-full">

		<?php if ( is_active_sidebar( 'sidebar-footer' )  ) : ?>
			 <div class="wd-footer-columns">
			 	<?php dynamic_sidebar( 'sidebar-footer' ); ?>
			 </div><!--.wd-footer-columns -->
		<?php endif; ?>
		
	</div><!--.col-full-->
<?php

}

/**
 * whitedot Social Icons
 *
 * @since 1.0.5
 */
function whitedot_social_links(){
?>
	<div class="wd-social-icons">

		<?php
		if ( has_nav_menu( 'social-icons' ) ) {

			wp_nav_menu(
				array(
					'theme_location'  => 'social-icons',
					'container'       => 'nav',
					'container_id'    => 'menu-social-icons',
					'container_class' => 'menu',
					'menu_id'         => 'menu-social-media-items',
					'menu_class'      => 'menu-items',
					'depth'           => 1,
					'fallback_cb'     => '',
				)
			);
		}
		?>

	</div><!--.wd-social-icons -->

<?php

}

/**
 * WhiteDot Footer Info
 *
 * @since 1.0.0
 */
function whitedot_footer_info(){

?>

	<div class="wd-footer-info">

		<?php do_action( 'whitedot_footer_info_content' ); ?>
		
	</div><!--.wd-footer-info-->

<?php

}