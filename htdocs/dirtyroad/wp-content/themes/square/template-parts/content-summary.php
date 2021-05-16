<?php
/**
 * Template part for displaying posts.
 *
 * @package Square
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('sq-hentry'); ?>>

    <figure class="entry-figure">
        <?php
        if (has_post_thumbnail()):
            $square_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'square-blog-thumb');
            ?>
            <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($square_image[0]); ?>" alt="<?php echo esc_attr(get_the_title()) ?>"></a>
        <?php endif; ?>
    </figure>


    <div class="sq-post-wrapper">
        <header class="entry-header">
            <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

            <?php if ('post' === get_post_type()) : ?>
                <div class="entry-meta">
                    <?php square_posted_on(); ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-content">
            <?php
            $square_blog_format = get_theme_mod('square_blog_format', 'excerpt');
            if ($square_blog_format == 'excerpt') {
                echo square_word_excerpt(get_the_content(), 160);  // WPCS: XSS OK.

                echo '<div class="entry-readmore"><a href="' . esc_url(get_permalink()) . '">' . esc_html__('Read More', 'square') . '<i class="fas fa-chevron-right"></i></a></div>';
            } else {
                the_content(__('Continue reading &rarr;', 'square'));

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'square'),
                    'after' => '</div>',
                ));
            }
            ?>
        </div><!-- .entry-content -->

        <footer class="entry-footer">
            <?php
            square_entry_footer();

            $square_blog_share_buttons = get_theme_mod('square_blog_share_buttons');
            if (!$square_blog_share_buttons) {
                square_social_share();
            }
            ?>
        </footer><!-- .entry-footer -->
    </div>
</article><!-- #post-## -->
