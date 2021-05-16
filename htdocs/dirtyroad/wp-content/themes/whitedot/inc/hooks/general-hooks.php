<?php
/**
 * General Hooks
 *
 * @package WhiteDot
 */

/**
 * Whitedot Search Form
 *
 * @since 1.0.0
 */
function whitedot_search_page_form(){

	?>
	<h1 class="wd-search-page-title">
		<?php
		/* translators: %s: search query. */
		printf( esc_html__( 'Search Results for: %s', 'whitedot' ), '<span>' . get_search_query() . '</span>' );
		?>
	</h1>
	<div class="wd-search-page-form">
		<?php get_search_form(); ?>	
	</div>
	<br />
	<?php

}


/**
 * Whitedot Main SideBar
 *
 * @since 1.0.0
 */
function whitedot_main_sidebar(){

	if ( is_active_sidebar( 'sidebar-1' )  ) : ?>
		<div itemtype="http://schema.org/WPSideBar" itemscope class="secondary">
			<div class="wd-sidebar">
				<div class="wd-widget-area">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>	
				</div><!--.wd-widget-area-->
			</div><!--.wd-sidebar-->
		</div><!--.secondary-->
	<?php endif; 

}

/**
 * Whitedot Main SideBar Left
 *
 * @since 1.2.3
 */
function whitedot_main_sidebar_left(){

	if ( is_active_sidebar( 'sidebar-1' )  ) : ?>
		<div itemtype="http://schema.org/WPSideBar" itemscope class="secondary-left">
			<div class="wd-sidebar">
				<div class="wd-widget-area">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>	
				</div><!--.wd-widget-area-->
			</div><!--.wd-sidebar-->
		</div><!--.secondary-->
	<?php endif; 

}

/**
 * Whitedot Main SideBar Right
 *
 * @since 1.2.3
 */
function whitedot_main_sidebar_right(){

	if ( is_active_sidebar( 'sidebar-1' )  ) : ?>
		<div itemtype="http://schema.org/WPSideBar" itemscope class="secondary-right">
			<div class="wd-sidebar">
				<div class="wd-widget-area">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>	
				</div><!--.wd-widget-area-->
			</div><!--.wd-sidebar-->
		</div><!--.secondary-->
	<?php endif; 

}


/**
 * WhiteDot Post Thumbnail
 *
 * @since 1.0.0
 */
function whitedot_thumbnail(){

	if ( has_post_thumbnail() ): ?>				
			<div class='wd-single-featured-img'>
				<?php whitedot_post_thumbnail(); ?>
			</div><!--.wd-single-featured-img-->
	<?php endif;

}


/**
 * WhiteDot Post Header
 *
 * @since 1.0.0
 */
function whitedot_post_header(){

	if ( class_exists( 'Whitedot_Designer' ) ) { 
		if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) { 
			if ( is_singular( 'post' ) ) {
				if ( 1 === get_theme_mod( 'whitedot_single_post_hero_thumbnail', 0 ) && true === get_theme_mod( 'single_post_title_in_hero_banner', false ) ) {
					the_title( '<span class="wd-post-title hero-img-exist">', '</span>' );
				}else{ 
					the_title( '<h1 itemprop="headline" class="wd-post-title">', '</h1>' ); 
				}
			}else{
				the_title( '<h1 itemprop="headline" class="wd-post-title">', '</h1>' );
			}
		}else{ 
			the_title( '<h1 itemprop="headline" class="wd-post-title">', '</h1>' ); 
		}
	}else{ 
		the_title( '<h1 itemprop="headline" class="wd-post-title">', '</h1>' ); 
	} 

}


/**
 * WhiteDot Post Meta
 *
 * @since 1.0.0
 */
function whitedot_post_meta(){
	?>
	<div class="single-excerpt-meta">
		<span class="wd-author">
			<?php esc_html_e( 'By', 'whitedot' ); ?> <span itemprop="author"><?php the_author_posts_link(); ?></span>
		</span>
		<span class="wd-date" >
			<time itemprop="datePublished"><?php whitedot_posted_on(); ?></time>
		</span>

		<span class="single-category-meta">
			<?php echo apply_filters( 'whitedot_meta_category_text', __( 'Category : ', 'whitedot' ) ); ?><span><?php the_category(); ?></span>
		</span>	

	</div>
	<?php
}

/**
 * WhiteDot Post Content
 *
 * @since 1.0.0
 */
function whitedot_post_content(){
	?>
	<div class="wd-custom-content" itemprop="text">
		<?php
		/**
		 * whitedot_single_post_before hook.
		 *
		 *
		 * @since 0.1
		 */
		do_action( 'whitedot_single_post_content_before' ); ?>

		<?php the_content(); ?>

		<?php
		/**
		* Functions hooked into whitedot_single_post_content_after add_action
		*
		* @hooked whitedot_single_post_pagination - 10
		*/
		do_action( 'whitedot_single_post_content_after' ); ?>
	
		
		

	</div><!--.wd-custom-content-->

	<?php
}

/**
 * WhiteDot Post Pagination
 *
 * @since 1.0.0
 */
function whitedot_post_pagination(){

	wp_link_pages( array(
				'before'      => '<div itemtype = "http://schema.org/SiteNavigationElement/Pagination" itemscope class="wd-single-pagenation"><span class="page-links-title">' . __( 'Pages:', 'whitedot' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span itemprop="url" class="page-num">',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'whitedot' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
}

/**
 * WhiteDot Single Post Tags
 *
 * @since 1.0.0
 */
function whitedot_single_post_tags(){
	?>
	<?php if (has_tag()) { ?>
	<span itemprop="keywords" class="wd-post-tags"><?php the_tags('', '' ,''); ?></span>
	<?php } ?>
	<?php
}

/**
 * WhiteDot Post Author
 *
 * @since 1.0.0
 */
function whitedot_post_author(){
	?>
	<div itemtype="http://schema.org/Person" itemscope class="wd-about-author">
		<div class="wd-author-avatar" itemprop="image">
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 200 ); ?>
		</div>
		<div class="wd-author-info">
			<div class="wd-author-name <?php if ( empty ( the_author_meta('user_description') ) ) { ?>no-desc<?php } ?>">
				<?php esc_html_e( '', 'whitedot' ); ?><span itemprop="name"><?php the_author_posts_link(); ?></span>
			</div>
			<div class="wd-author-description" itemprop="description">
				<?php if ( ! empty ( the_author_meta('user_description') ) ) { ?>
					<?php the_author_meta('user_description'); ?>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
}

function whitedot_post_comment(){
	comments_template();
}


/**
 * WhiteDot Blog List Thumbnail
 *
 * @since 1.0.0
 */
function whitedot_blog_list_thumbnail(){

	if ( has_post_thumbnail() ): ?>
		<a class="wd-featured-img-link" href="<?php the_permalink(); ?>">
			<meta itemprop="image" content="<?php the_post_thumbnail_url(); ?>"></meta>
			<div itemprop="image" class='wd-loop-featured-img' style='background-image: url(<?php if ( has_post_thumbnail() ) { the_post_thumbnail_url();} ?>)'>
			</div>
		</a>	
	<?php endif;

}

/**
 * Whitedot Blog List Meta
 *
 * @since 1.0.0
 */
function whitedot_blog_list_meta(){

	?>
	<div class="excerpt-meta">
		<span class="date">
			<time itemprop="datePublished"><?php whitedot_posted_on(); ?></time>
		</span>	
		<span class="author">
			<span itemprop="author"><?php whitedot_posted_by(); ?></span>
		</span>		
	</div>
	<?php

}

/**
 * Whitedot Archive Header Header
 *
 * @since 1.0.0
 */
function whitedot_archive_head(){

	?>
	<header class="page-header">
		<?php
		the_archive_title( '<h1 class="page-title">', '</h1>' );
		the_archive_description( '<div class="archive-description">', '</div>' );
		?>
	</header><!-- .page-header -->
	<?php

}

/**
 * Whitedot Blog List Header
 *
 * @since 1.0.0
 */
function whitedot_blog_list_header(){

	?>
	<div class='wd-excerpt-header'>
		<h2 rel="bookmark" itemprop="headline" class='wd-excerpt-title'>
			<a itemprop="url" href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
			</a>
		</h2>
	</div>
	<?php

}

/**
 * Whitedot Blog List Excerpt
 *
 * @since 1.0.0
 */
function whitedot_blog_list_excerpt(){

	?>
	<div class='wd-excerpt-content' itemprop="text">
		<article>

			<?php
			/**
			 * whitedot_blog_excerpt_content hook.
			 *
			 * @hooked whitedot_blog_excerpt_main  - 10
			 * @hooked whitedot_blog_excerpt_readmore  - 20
			 *
			 * @since 0.1
			 */
			do_action( 'whitedot_blog_excerpt_content' ); ?>

		</article>
	</div>
	<?php

}

/**
 * Whitedot Blog Excerpt Main
 *
 * @since 1.0.0
 */
function whitedot_blog_excerpt_main(){

	?>
	<p>
		<?php the_excerpt(); ?>
	</p>
	<?php

}

/**
 * Whitedot Blog Excerpt Main
 *
 * @since 1.1.08
 */
function whitedot_blog_excerpt_readmore(){

	?>
	<div class="wd-more-link-wrapper">
		<a itemprop="url" class="more-link button" href="<?php the_permalink(); ?>">
			<?php echo apply_filters( 'read_the_post_text', __( 'Read the Post', 'whitedot' ) ); ?>
			<span class="screen-reader-text">
				<?php the_title(); ?>
			</span>
		</a>
	</div>
	<?php

}

			

/**
 * WhiteDot Blog Home Pagination (With Page Number)
 *
 * @since 1.0.0
 */
function whitedot_blog_home_number_pagination(){

	?><div class="wd-post-pagination"><?php
	the_posts_pagination( array(
			'prev_text'          => '<i class="fa fa-chevron-left"></i>',
			'next_text'          => '<i class="fa fa-chevron-right"></i>',
			'before_page_number' => '<span class="screen-reader-text">' . __( 'Page', 'whitedot' ) . ' </span>',
		) );
	?></div><?php

}

/**
 * WhiteDot Blog Home Pagination (Only Next-Previous Sign)
 *
 * @since 1.0.0
 */
function whitedot_blog_home_icon_pagination(){

	?><div class="wd-post-pagination">
		<ul role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
			<li class="wd-prev" itemprop="url">
				<?php previous_posts_link('<i class="fa fa-chevron-left"></i>') ?>
			</li>
			<li class="wd-next" itemprop="url">
				<?php next_posts_link('<i class="fa fa-chevron-right"></i>') ?>
			</li>
		</ul>
	</div><!--.wd-post-pagination --><?php

}



/**
 * WhiteDot Footer Credits
 *
 * @since 1.0.0
 */

add_action('whitedot_footer_info_content','whitedot_footer_credits', 10);

function whitedot_footer_credits(){

	?>
	<p class="footer-credit"><?php

	printf( '%1$s &copy %2$s - ',
		esc_html_e( 'COPYRIGHT', 'whitedot' ),
		esc_html( date( 'Y' ) )
	);

	bloginfo('name');

	?>  //  <?php

	printf( '%1$s -  <a href="%2$s" target="_blank">%3$s</a>',
		esc_html_e( 'Designed By', 'whitedot' ),
		esc_url( 'https://zeetheme.com' ),
		esc_html( __( 'ZeeTheme', 'whitedot' ) )
	);

	?>
	</p>
	<?php
}


/**
 * Swastika Go to top Button
 *
 * @since 1.1.02
 */
function whitedot_gototop_btn(){

?>

	<a href="#0" class="to-top" title="Back To Top"><i class="fa fa-angle-up" aria-hidden="true"></i></a>

<?php

}



/**
 * Whitedot Page Boxed Container Open
 *
 * @since 1.0.0
 */
function whitedot_boxed_container_open(){

	?>
	<div class="boxed-layout">
	<?php

}

/**
 * Whitedot Page Boxed Container Close
 *
 * @since 1.0.0
 */
function whitedot_boxed_container_close(){

	?>
	</div><!-- .boxed-layout -->
	<?php

}


