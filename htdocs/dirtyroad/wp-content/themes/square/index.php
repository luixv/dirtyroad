<?php
/**
 * The main template file.
 *
 * @package Square
 */
get_header();
?>

<header class="sq-main-header">
    <?php
    if (is_home() && 'page' == get_option('show_on_front')) {
        $blog_page_id = get_option('page_for_posts');
        ?>
        <div class="sq-container">
            <h1 class="sq-main-title"><?php echo get_the_title($blog_page_id); ?></h1>
        </div>
        <?php
    }
    ?>
</header><!-- .entry-header -->


<div class="sq-container sq-clearfix">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php if (have_posts()) : ?>

                <?php /* Start the Loop */ ?>
                <?php while (have_posts()) : the_post(); ?>

                    <?php
                    /*
                     * Include the Post-Format-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    get_template_part('template-parts/content', 'summary');
                    ?>

                <?php endwhile; ?>

                <?php the_posts_pagination(); ?>

            <?php else : ?>

                <?php get_template_part('template-parts/content', 'none'); ?>

            <?php endif; ?>

        </main><!-- #main -->
    </div><!-- #primary -->

    <?php get_sidebar(); ?>
</div>

<?php
get_footer();
