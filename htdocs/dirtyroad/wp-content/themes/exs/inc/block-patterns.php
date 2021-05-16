<?php
/**
 * Block patterns support
 *
 * @package WordPress
 * @subpackage ExS
 * @since 0.1.0
 * @link https://developer.wordpress.org/block-editor/developers/block-api/block-patterns/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'register_block_pattern_category' ) ) {
	return;
}

if ( ! function_exists( 'exs_register_theme_block_patterns' ) ) {
	function exs_register_theme_block_patterns() {
		register_block_pattern_category(
			'exs',
			array( 'label' => esc_html__( 'ExS', 'exs' ) )
		);

		$exs_patterns = apply_filters(
			'exs_block_patterns',
			array(
				'exs/title-with-subtitle'             => array(
					'title'       => esc_html__( 'Title with subtitle', 'exs' ),
					'description' => esc_html__( 'Title heading with sub title and separator below it.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'title-with-subtitle' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'title', 'subtitle', 'heading' ),
				),
				'exs/cols-3-feature-image-boxes'      => array(
					'title'       => esc_html__( 'Three featured columns', 'exs' ),
					'description' => esc_html__( 'Three columns with image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-feature-image-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-feature-blocks'           => array(
					'title'       => esc_html__( 'Four featured columns', 'exs' ),
					'description' => esc_html__( 'Four columns with image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-feature-blocks' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-feature-blocks-left'      => array(
					'title'       => esc_html__( 'Four featured columns', 'exs' ),
					'description' => esc_html__( 'Four columns with left aligned image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-feature-blocks-left' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-3-feature-side-image-boxes' => array(
					'title'       => esc_html__( 'Three side featured columns', 'exs' ),
					'description' => esc_html__( 'Three columns with side image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-feature-side-image-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-6-feature-blocks'           => array(
					'title'       => esc_html__( 'Six featured columns', 'exs' ),
					'description' => esc_html__( 'Six columns with image boxes with titles.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-6-feature-blocks' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-progress'                 => array(
					'title'       => esc_html__( 'Four columns with progress', 'exs' ),
					'description' => esc_html__( 'Four columns with progress image boxes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-progress' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'progress', 'image box' ),
				),
				'exs/cols-4-team-members'             => array(
					'title'       => esc_html__( 'Four columns with team', 'exs' ),
					'description' => esc_html__( 'Four columns with team members photo and description.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-team-members' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'features', 'image box' ),
				),
				'exs/cols-4-contacts'                 => array(
					'title'       => esc_html__( 'Four columns with contacts', 'exs' ),
					'description' => esc_html__( 'Four columns with contact info.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-4-contacts' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'contact', 'contacts', 'image box' ),
				),
				'exs/cols-2-blockquotes'              => array(
					'title'       => esc_html__( 'Two columns with blockquotes', 'exs' ),
					'description' => esc_html__( 'Two columns with testimonials blockquotes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-blockquotes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'testimonials', 'blockquotes' ),
				),
				'exs/cols-2-blockquotes-simple'              => array(
					'title'       => esc_html__( 'Two columns with simple blockquotes', 'exs' ),
					'description' => esc_html__( 'Two columns with simple testimonials blockquotes.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-blockquotes-simple' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'testimonials', 'blockquotes' ),
				),
				'exs/cover-call-to-action'            => array(
					'title'       => esc_html__( 'Cover call to action', 'exs' ),
					'description' => esc_html__( 'Call to action cover block with title and button.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cover-call-to-action' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'cover', 'call', 'action' ),
				),
				'exs/cols-2-cta-side-boxes'            => array(
					'title'       => esc_html__( '2 columns call to action', 'exs' ),
					'description' => esc_html__( 'Call to action in two columns', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-cta-side-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'call', 'action' ),
				),
				'exs/cols-3-text-actions'            => array(
					'title'       => esc_html__( 'Text columns call to action', 'exs' ),
					'description' => esc_html__( 'Call to action columns block with text and button.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-3-text-actions' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'call', 'action' ),
				),
				'exs/cols-2-call-to-action'           => array(
					'title'       => esc_html__( 'Two columns call to action', 'exs' ),
					'description' => esc_html__( 'Call to action text block with heading and button in two columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-call-to-action' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action', 'columns' ),
				),
				'exs/cta-1'                          => array(
					'title'       => esc_html__( 'Call to action heading', 'exs' ),
					'description' => esc_html__( 'Call to action heading text with button.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cta-1' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action' ),
				),
				'exs/cta-2'                          => array(
					'title'       => esc_html__( 'Call to action heading', 'exs' ),
					'description' => esc_html__( 'Call to action heading text with two buttons.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cta-2' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action' ),
				),
				'exs/media-text-simple'              => array(
					'title'       => esc_html__( 'Side image', 'exs' ),
					'description' => esc_html__( 'Simple side image block.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-text-simple' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action' ),
				),
				'exs/media-text-2-cols'              => array(
					'title'       => esc_html__( 'Call to action side image', 'exs' ),
					'description' => esc_html__( 'Call to action side image block.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'media-text-2-cols' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'call', 'action' ),
				),
				'exs/cols-2-dropcaps'                 => array(
					'title'       => esc_html__( 'Two columns text with dropcaps', 'exs' ),
					'description' => esc_html__( 'Text with two columns with dropcaps.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-dropcaps' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'text', 'columns' ),
				),
				'exs/cols-2-faq'                      => array(
					'title'       => esc_html__( 'Two columns simple text', 'exs' ),
					'description' => esc_html__( 'Text with two columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-faq' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'text', 'columns', 'faq' ),
				),
				'exs/cols-2-image-side-boxes'         => array(
					'title'       => esc_html__( 'Two columns with image', 'exs' ),
					'description' => esc_html__( 'Two columns with image and side image boxes', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-image-side-boxes' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'text', 'columns' ),
				),
				'exs/cols-2-person'                   => array(
					'title'       => esc_html__( 'Two columns with person image', 'exs' ),
					'description' => esc_html__( 'Two columns with person  image and description', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-person' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'text', 'columns', 'about', 'image' ),
				),
				'exs/form-1'           => array(
					'title'       => esc_html__( 'Simple Contact Form', 'exs' ),
					'description' => esc_html__( 'Contact form with name, email and message fields.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'form-1' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'contact', 'columns' ),
				),
				'exs/form-2'           => array(
					'title'       => esc_html__( 'Simple 2 Columns Contact Form', 'exs' ),
					'description' => esc_html__( 'Contact form with name, email and message fields in 2 columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'form-2' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'contact', 'columns' ),
				),
				'exs/pricing-plan-columns'     => array(
					'title'       => esc_html__( 'Pricing Plan Columns', 'exs' ),
					'description' => esc_html__( 'Pricing Plan in four columns.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'pricing-plan-columns' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'pricing', 'plan', 'columns' ),
				),
				'exs/fullwidth-2-cols'     => array(
					'title'       => esc_html__( 'Fullwidth columns with image', 'exs' ),
					'description' => esc_html__( 'Fullwidth columns with one half left image.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'fullwidth-2-cols' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'fullwidth', 'image', 'columns' ),
				),
				'exs/fullwidth-media-left'     => array(
					'title'       => esc_html__( 'Fullwidth left image', 'exs' ),
					'description' => esc_html__( 'Fullwidth media with left one half left image.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'fullwidth-media-left' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'fullwidth', 'image', 'media' ),
				),
				'exs/fullwidth-media-right'     => array(
					'title'       => esc_html__( 'Fullwidth right image', 'exs' ),
					'description' => esc_html__( 'Fullwidth media with right one half right image.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'fullwidth-media-right' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'fullwidth', 'image', 'media' ),
				),
				'exs/fullwidth-screen'     => array(
					'title'       => esc_html__( 'Fullwidth full height cover', 'exs' ),
					'description' => esc_html__( 'Fullwidth and full height cover image.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'fullwidth-screen' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'fullwidth', 'image' ),
				),
				'exs/cols-2-paragraphs'     => array(
					'title'       => esc_html__( 'Two columns paragraphs', 'exs' ),
					'description' => esc_html__( 'Two columns simpoe paragraphs.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-paragraphs' ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'text' ),
				),
				'exs/cols-2-paragraphs-2'     => array(
					'title'       => esc_html__( 'Two columns paragraphs', 'exs' ),
					'description' => esc_html__( 'Two columns simpoe paragraphs.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-paragraphs', array(
							'verticalAlignment' => 'center',
							'align' => 'full',
							'section' => true,
							'colsHighlight' => true,
							'colsBordered' => true,
							'colsShadow' => true,
							'colsShadowHover' => true,
							'colsRounded' => true,
							'colsPadding' => true,
							'colsSingle' => 'cols-single-sm',
							'gap' => 'gap-50',
							'pt' => 'pt-3',
							'pb' => 'pb-3',
							'background' => 'l m',
							'decorTop' => 'decor decor-t',
							'decorBottom' => 'decor decor-b',
					) ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'text' ),
				),
				'exs/cols-2-paragraphs-3'     => array(
					'title'       => esc_html__( 'Two columns paragraphs', 'exs' ),
					'description' => esc_html__( 'Two columns simpoe paragraphs.', 'exs' ),
					'content'     => exs_get_html_markup_from_template( 'cols-2-paragraphs', array(
						'verticalAlignment' => 'center',
						'align' => '',
						'section' => false,
						'colsHighlight' => true,
						'colsBordered' => true,
						'colsShadow' => true,
						'colsShadowHover' => true,
						'colsRounded' => true,
						'colsPadding' => true,
						'colsSingle' => 'cols-single-sm',
						'gap' => 'gap-50',
						'pt' => 'pt-8',
						'pb' => 'pb-2',
						'background' => 'i m',
						'decorTop' => '',
						'decorBottom' => '',
					) ),
					'categories'  => array( 'exs' ),
					'keywords'    => array( 'columns', 'text' ),
				),
			)
		);

		if ( ! empty( $exs_patterns ) ) {
			foreach ( $exs_patterns as $id => $pattern ) {
				register_block_pattern( $id, $pattern );
			}
		}
	}
}
add_action( 'after_setup_theme', 'exs_register_theme_block_patterns' );
