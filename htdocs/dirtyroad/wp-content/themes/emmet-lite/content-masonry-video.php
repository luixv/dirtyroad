<?php
/**
 * The template for displaying posts in the Video post format
 *
 * Used for masonry blog
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('post-in-blog '); ?>>
    <div class="post-masonry">
        <?php echo mp_emmet_get_first_embed_media($post->ID); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <header class="entry-header">
            <h2 class="entry-title h5">
                <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
            </h2>            
        </header> 
        <section class="entry-content">
            <p>
                <?php
                mp_emmet_get_content_theme(107, false);
                ?>
            </p>
        </section>
        <footer class="entry-footer">
            <div class="meta">
                <span class="date-post h6"><?php echo esc_html(get_post_time('F j, Y',false,null,true )); ?></span>
                <?php if (comments_open()) : ?>
                    <a class="comments-count" href="<?php if(!is_single()):the_permalink(); endif; ?>#comments" >
                        <span class="fa fa-comments-o"></span><span><?php comments_number('&nbsp;0', '&nbsp;1', '&nbsp;%'); ?></span>
                    </a>
                <?php endif; ?>
                <div class="clearfix"></div>
            </div>
        </footer>
    </div>
</article><!-- #post -->
