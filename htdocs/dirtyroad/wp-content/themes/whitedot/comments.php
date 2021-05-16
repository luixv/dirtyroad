<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WhiteDot
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

// You can start editing here -- including this comment!
if ( comments_open() || have_comments() ) :

	?>
	<div itemtype = "http://schema.org/comment" itemscope class="wd-single-post-comment wd-comment">
		<div id="comments" class="comments-area">
			<h2 class="comments-title">
				<?php
				$whitedot_comment_count = get_comments_number();
				if ( '1' === $whitedot_comment_count ) {
					printf(
						/* translators: 1: title. */
						esc_html__( 'One Comment', 'whitedot' )
					);
				} else {
					printf( // WPCS: XSS OK.
						/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( '%1$s Comment', '%1$s Comments', $whitedot_comment_count, 'comments title', 'whitedot' ) ),
						number_format_i18n( $whitedot_comment_count )
					);
				}
				?>
			</h2><!-- .comments-title -->

			<?php the_comments_navigation(); ?>

			<ol class="comment-list">
				<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
				) );
				?>
			</ol><!-- .comment-list -->

			<?php
			the_comments_navigation();

			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() ) :
				?>
				<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'whitedot' ); ?></p>
				<?php
			endif;

		

			comment_form();
			?>

		</div><!-- #comments -->
	</div><!--.wd-single-post-comment-->
	<?php

endif; // Check for have_comments().
