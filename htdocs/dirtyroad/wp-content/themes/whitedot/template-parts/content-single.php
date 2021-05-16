<?php
/**
 * Template part for displaying single blog content in single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WhiteDot
 */



/**
 * whitedot_main_single_content_before hook.
 *
 *
 * @since 0.1
 */
do_action( 'whitedot_main_single_content_before' ); ?>

<div class="wd-single-wrap <?php if( 'contained' === get_theme_mod( 'whitedot_single_blog_container_layout', 'boxed' ) || whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Contained' ) { ?>is-contained<?php }else { ?>is-boxed<?php } ?> <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_blog_single_sidebar_layout' ) ) { ?>sidebar-disabled<?php }else { ?>sidebar-enabled<?php } ?>">
						
	<?php 
	if ( class_exists( 'Whitedot_Designer' ) && is_singular('post') ) { 
    	if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) { 
    		if ( 1 === get_theme_mod( 'whitedot_single_post_hero_thumbnail', 0 ) ) {}else{ ?>
				<article itemtype="https://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class() ?>>
			<?php }
    	}else{ ?>
			<article itemtype="https://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class() ?>>
		<?php }
    }else{ ?>
		<article itemtype="https://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class() ?>>
	<?php } ?>
	

		<?php
		/**
		 * Functions hooked into whitedot_single_post_before add_action
		 *
		 * @hooked whitedot_thumbnail  - 10
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_single_post_before' ); ?>
		
		<div class="wd-post-content">

			<?php
			/**
			* Functions hooked into whitedot_single_post add_action
			*
			* @hooked whitedot_post_header          - 10
			* @hooked whitedot_post_meta            - 20
			* @hooked whitedot_post_content         - 30
			* @hooked whitedot_single_post_tags     - 40
			*/
			do_action( 'whitedot_single_post' ); ?>
	
		</div><!--.wd-post-content-->

		<?php
		/**
		 * whitedot_single_post_after hook.
		 *
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_single_post_after' ); ?>

	<?php 
	if ( class_exists( 'Whitedot_Designer' ) ) { 
    	if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) { 
    		if ( 1 === get_theme_mod( 'whitedot_single_post_hero_thumbnail', 0 ) ) {}else{ ?>
				</article>
			<?php }
    	}else{ ?>
			</article>
		<?php }
    }else{ ?>
		</article>
	<?php } ?>	
</div><!--.wd-single-wrap-->

<?php
/**
 * Functions hooked into whitedot_main_single_content_after add_action
 *
 * @hooked whitedot_post_author - 10
 * @hooked whitedot_post_comment - 20
 *
 * @since 0.1
 */
do_action( 'whitedot_main_single_content_after' );
