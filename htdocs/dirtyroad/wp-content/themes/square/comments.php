<?php
/**
 * The template for displaying comments.
 *
 * @package Square
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php // You can start editing here -- including this comment!  ?>

    <?php if (have_comments()) : ?>

        <h3 class="comments-title">
            <?php
            $comment_count = get_comments_number();

            printf(// WPCS: XSS OK.
                    /* translators: 1: comment count number */
                    esc_html(_nx('%1$s Comment', '%1$s Comments', $comment_count, 'comments title', 'square')), number_format_i18n($comment_count)
            );
            ?>
        </h3>

        <?php the_comments_navigation(); ?>

        <ul class="comment-list">
            <?php
            wp_list_comments(array(
                'callback' => 'square_comment'
            ));
            ?>
        </ul><!-- .comment-list -->

    <?php endif; // Check for have_comments().  ?>

    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
        ?>
        <p class="no-comments"><?php esc_html_e('Comments are closed.', 'square'); ?></p>
    <?php endif; ?>

    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields = array(
        'author' =>
        '<div class="author-email-url hs-clearfix"><p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) .
        '" size="30"' . $aria_req . ' placeholder="' . esc_attr__('Name', 'square') . ( $req ? '*' : '' ) . '" /></p>',
        'email' =>
        '<p class="comment-form-email"><input id="email" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) .
        '" size="30"' . $aria_req . ' placeholder="' . esc_attr__('Email', 'square') . ( $req ? '*' : '' ) . '" /></p>',
        'url' =>
        '<p class="comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) .
        '" size="30" placeholder="' . esc_attr__('Website', 'square') . '" /></p></div>',
    );


    $args = array(
        'fields' => apply_filters('comment_form_default_fields', $fields),
        'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . esc_attr__('Comment', 'square') . '">' .
        '</textarea></p>',
    );
    ?>

    <?php comment_form($args); ?>

</div><!-- #comments -->

