<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
	<h4 class="page-title"><?php esc_html_e( 'Nothing Found', 'exs' ); ?></h4>
<?php
if ( is_home() && current_user_can( 'publish_posts' ) ) :

	printf(
		'<p>' .
		wp_kses(
			/* translators: 1: link to WP admin new post page. */
			__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'exs' ),
			array(
				'a' => array(
					'href' => array(),
				),
			)
		)
		. '</p>',
		esc_url( admin_url( 'post-new.php' ) )
	);

elseif ( is_search() ) :
	?>

	<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'exs' ); ?></p>
	<?php

else :
	?>

	<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'exs' ); ?></p>
	<?php
	get_search_form();

endif;
