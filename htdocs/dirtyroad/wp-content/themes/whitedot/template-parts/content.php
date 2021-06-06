<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WhiteDot
 */

?>

<article itemtype="https://schema.org/CreativeWork" itemscope="itemscope" id="post-<?php the_ID(); ?>" <?php post_class() ?>>
	<div <?php if ( ! is_singular() ) : ?> class="wd-single-post" <?php endif ?>>
		<?php if ( has_post_thumbnail() ): ?>
				<div class='wd-single-featured-img'>
				<img src="<?php if ( has_post_thumbnail() ) { the_post_thumbnail_url();} ?>" />
				</div><!--.wd-single-featured-img-->
		<?php endif; ?>

		<div class="<?php if ( is_singular() ) : ?>wd-post-content<?php else : ?>wd-excerpt-container<?php endif ?>">
			<?php
			if ( is_singular() ) :
				the_title( '<h1 itemprop="headline" class="wd-post-title">', '</h1>' );
			else :
				the_title( '<h2 itemprop="headline" class="wd-excerpt-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;

			if ( 'post' === get_post_type() ) :
				?>
				<div class="excerpt-meta">
					<span class="date">
						<time itemprop="datePublished"><?php whitedot_posted_on(); ?></time>
					</span>	
					<span> / </span>
					<span class="author">
						<span itemprop="author"><?php whitedot_posted_by(); ?></span>
					</span>
				</div>
			<?php endif; ?>

			<?php if ( is_singular() ) : ?>

				<div class="wd-custom-content">
					
					<?php the_content();

					wp_link_pages( array(
						'before'      => '<div itemtype = "http://schema.org/pagination" itemscope class="wd-single-pagenation"><span class="page-links-title">' . __( 'Pages:', 'whitedot' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span class="page-num">',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'whitedot' ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					) ); ?>
				</div><!--.wd-custom-content-->

			<?php else : ?>

				<div class='wd-excerpt-content'>
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

			<?php endif;  ?>
			
			
			
		</div><!--.wd-post-content-->
	</div>
</article>

<?php 
	if ( is_singular() ) :
		if ( comments_open() ) : ?>
			<div itemtype = "http://schema.org/comment" itemscope="itemscope" class="wd-single-post-comment wd-comment">
				<?php comments_template(); ?>
			</div><!--.wd-single-post-comment-->
		<?php endif; 
	endif; 

