<?php
/**
 * The default template for displaying content
 *
 * Used for  index/archive/search.
 *
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet 1.0
 */
?>
<article class="post no-results not-found">
    <div class="entry-content">
		<h3 class="entry-title"><?php esc_html_e('Nothing Found', 'emmet-lite'); ?></h3>
		<?php if (is_home() && current_user_can('publish_posts')) : ?>
			<p><?php printf('%2$s<a href="%1$s">%3$s</a>.', esc_url(admin_url('post-new.php')), esc_html__('Ready to publish your first post?', 'emmet-lite'), esc_html__('Get started here', 'emmet-lite')); ?></p>
		<?php elseif (is_search()) : ?>
			<p><?php esc_html_e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'emmet-lite'); ?></p>
		<?php else : ?>
			<p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'emmet-lite'); ?></p>
		<?php endif; ?>
		<?php get_search_form(); ?>
    </div><!-- .entry-content -->
</article><!-- #post-0 -->