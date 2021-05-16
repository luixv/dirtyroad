<?php
/**
 * Automotive Centre: Block Patterns
 *
 * @package Automotive Centre
 * @since   1.0.0
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {

	register_block_pattern_category(
		'automotive-centre',
		array( 'label' => __( 'Automotive Centre', 'automotive-centre' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {
	register_block_pattern(
		'automotive-centre/banner-section',
		array(
			'title'      => __( 'Banner Section', 'automotive-centre' ),
			'categories' => array( 'automotive-centre' ),
			'content'    => "<!-- wp:cover {\"url\":\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/banner.png\",\"id\":6398,\"align\":\"full\",\"className\":\"banner-section\"} -->\n<div class=\"wp-block-cover alignfull has-background-dim banner-section\" style=\"background-image:url(" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/banner.png)\"><div class=\"wp-block-cover__inner-container\"><!-- wp:columns {\"verticalAlignment\":\"center\",\"align\":\"full\"} -->\n<div class=\"wp-block-columns alignfull are-vertically-aligned-center\"><!-- wp:column {\"verticalAlignment\":\"center\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\"><!-- wp:heading {\"textAlign\":\"left\",\"level\":4,\"className\":\"banner-small-heading mb-0\"} -->\n<h4 class=\"has-text-align-left banner-small-heading mb-0\">TE OBTINUIT UT ADEPTO SATIS</h4>\n<!-- /wp:heading -->\n\n<!-- wp:heading {\"textAlign\":\"left\",\"level\":1,\"className\":\"banner-title\"} -->\n<h1 class=\"has-text-align-left banner-title\">TE OBTINUIT UT ADEPTO</h1>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"align\":\"left\",\"className\":\"mt-0 mb-0 text-left\"} -->\n<p class=\"has-text-align-left mt-0 mb-0 text-left\">Lorem Ipsum has been the industrys standard. Lorem Ipsum has been the industrys standard.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:buttons {\"align\":\"left\",\"className\":\"my-0\"} -->\n<div class=\"wp-block-buttons alignleft my-0\"><!-- wp:button {\"borderRadius\":0} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link no-border-radius\">LEARN MORE</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\"></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div></div>\n<!-- /wp:cover -->",
		)
	);

	register_block_pattern(
		'automotive-centre/about-us-section',
		array(
			'title'      => __( 'About Us Section', 'automotive-centre' ),
			'categories' => array( 'automotive-centre' ),
			'content'    => "<!-- wp:cover {\"overlayColor\":\"white\",\"align\":\"full\",\"className\":\"about-outer-box\"} -->\n<div class=\"wp-block-cover alignfull has-white-background-color has-background-dim about-outer-box\"><div class=\"wp-block-cover__inner-container\"><!-- wp:columns {\"align\":\"wide\"} -->\n<div class=\"wp-block-columns alignwide\"><!-- wp:column {\"width\":\"60%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:60%\"><!-- wp:heading {\"textAlign\":\"left\",\"className\":\"about-small-heading mb-0\",\"style\":{\"color\":{\"text\":\"#b1b6b9\"}}} -->\n<h2 class=\"has-text-align-left about-small-heading mb-0 has-text-color\" style=\"color:#b1b6b9\">ABOUT US</h2>\n<!-- /wp:heading -->\n\n<!-- wp:heading {\"textAlign\":\"left\",\"level\":3,\"className\":\"about-heading pb-2\",\"style\":{\"color\":{\"text\":\"#010203\"}}} -->\n<h3 class=\"has-text-align-left about-heading pb-2 has-text-color\" style=\"color:#010203\">TE OBTINUIT UT ADEPTO SATIS</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"align\":\"left\",\"className\":\"text-left\",\"style\":{\"color\":{\"text\":\"#b1b6b9\"}}} -->\n<p class=\"has-text-align-left text-left has-text-color\" style=\"color:#b1b6b9\">Lorem Ipsum has been the industrys standard. Lorem Ipsum has been the industry standard. Lorem Ipsum has been the industrys standard. Lorem Ipsum has been the industrys standard</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph {\"align\":\"left\",\"className\":\"text-left\",\"style\":{\"color\":{\"text\":\"#b1b6b9\"}}} -->\n<p class=\"has-text-align-left text-left has-text-color\" style=\"color:#b1b6b9\">Lorem Ipsum has been the industrys standard. Lorem Ipsum has been the industrys standard.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:buttons -->\n<div class=\"wp-block-buttons\"><!-- wp:button {\"style\":{\"color\":{\"background\":\"#88d055\"}}} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background\" style=\"background-color:#88d055\">LEARN MORE</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"40%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:40%\"><!-- wp:image {\"id\":6411,\"sizeSlug\":\"large\",\"linkDestination\":\"media\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/about-us.png\" alt=\"\" class=\"wp-image-6411\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:paragraph {\"align\":\"center\",\"placeholder\":\"Write title\",\"fontSize\":\"large\"} -->\n<p class=\"has-text-align-center has-large-font-size\"></p>\n<!-- /wp:paragraph --></div></div>\n<!-- /wp:cover -->",
		)
	);
}