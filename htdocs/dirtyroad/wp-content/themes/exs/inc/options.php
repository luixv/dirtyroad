<?php
/**
 * Theme options
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//default values for customizer or other theme options
if ( ! function_exists( 'exs_get_default_options_array' ) ) :
	function exs_get_default_options_array() {

		//fonts choises:
		// Open Sans
		// Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese
		// Roboto
		// Lato
		// Raleway
		// Montserrat
		// PT Sans
		// Source Sans Pro
		// Oswald
		// Lora
		// Work Sans

		$exs_options = array(
			'demo_number'                           => '',
			'colorLight'                            => '#ffffff',
			'colorFont'                             => '#555555',
			'colorFontMuted'                        => '#666666',
			'colorBackground'                       => '#f7f7f7',
			'colorBorder'                           => '#e1e1e1',
			'colorDark'                             => '#444444',
			'colorDarkMuted'                        => '#222222',
			'colorMain'                             => '#a17de8',
			'colorMain2'                            => '#8a8dff',
			'intro_block_heading'                   => '',
			'intro_position'                        => '',
			'intro_layout'                          => '',
			'intro_fullscreen'                      => '',
			'intro_background'                      => '',
			'intro_background_image'                => '',
			'intro_background_image_cover'          => '1',
			'intro_background_image_fixed'          => '1',
			'intro_background_image_overlay'        => '',
			'intro_image_animation'                 => 'zoomIn',
			'intro_heading'                         => '',
			'intro_heading_animation'               => 'fadeInUp',
			'intro_description'                     => '',
			'intro_description_animation'           => 'fadeInUp',
			'intro_button_text_first'               => '',
			'intro_button_url_first'                => '',
			'intro_button_first_animation'          => 'fadeInUp',
			'intro_button_text_second'              => '',
			'intro_button_url_second'               => '',
			'intro_button_second_animation'         => 'fadeInUp',
			'intro_shortcode'                       => '',
			'intro_alignment'                       => 'text-center',
			'intro_extra_padding_top'               => 'pt-5',
			'intro_extra_padding_bottom'            => 'pb-5',
			'intro_font_size'                       => '',
			'intro_teasers_block_heading'           => '',
			'intro_teaser_section_layout'           => '',
			'intro_teaser_section_background'       => '',
			'intro_teaser_section_padding_top'      => 'pt-5',
			'intro_teaser_section_padding_bottom'   => 'pb-5',
			'intro_teaser_font_size'                => '',
			'intro_teaser_layout'                   => 'text-center',
			'intro_teaser_heading'                  => '',
			'intro_teaser_description'              => '',
			'logo'                                  => '1',
			'logo_text_primary'                     => '',
			'logo_text_secondary'                   => '',
			'header_top_tall'                       => '',
			'logo_background'                       => '',
			'logo_padding_horizontal'               => '',
			'preset'                                => '',
			'skin'                                  => '',
			'main_container_width'                  => '1140',
			'blog_container_width'                  => '',
			'blog_single_container_width'           => '960',
			'preloader'                             => '',
			'box_fade_in'                           => '',
			'totop'                                 => '1',
			'assets_min'                            => '1',
			'jquery_to_footer'                      => '',
			'buttons_uppercase'                     => '',
			'buttons_bold'                          => '',
			'buttons_colormain'                     => '',
			'buttons_outline'                       => '',
			'buttons_radius'                        => '',
			'meta_email'                            => '',
			'meta_email_label'                      => '',
			'meta_phone'                            => '',
			'meta_phone_label'                      => '',
			'meta_address'                          => '',
			'meta_address_label'                    => '',
			'meta_opening_hours'                    => '',
			'meta_opening_hours_label'              => '',
			'meta_facebook'                         => '',
			'meta_twitter'                          => '',
			'meta_youtube'                          => '',
			'meta_instagram'                        => '',
			'meta_pinterest'                        => '',
			'meta_linkedin'                         => '',
			'meta_github'                           => '',
			'side_nav_position'                     => '',
			'side_nav_background'                   => 'l',
			'side_nav_sticked'                      => '',
			'side_nav_header_overlap'               => '',
			'header_toggler_menu_side'              => '1',
			'side_nav_logo_position'                => '',
			'side_nav_meta_position'                => 'bottom',
			'side_nav_social_position'              => 'bottom',
			'header_image_background_image_cover'   => '1',
			'header_image_background_image_fixed'   => '1',
			'header_image_background_image_overlay' => '',
			'header'                                => '1',
			'header_fluid'                          => '1',
			'header_background'                     => 'l',
			'header_toplogo_background'             => 'l',
			'header_align_main_menu'                => 'menu-right',
			'header_toggler_menu_main'              => '1',
			'header_absolute'                       => '',
			'header_transparent'                    => '',
			'header_border_top'                     => '',
			'header_border_bottom'                  => '',
			'header_font_size'                      => '',
			'header_sticky'                         => '',
			'header_search'                         => 'button',
			'header_button_text'                    => '',
			'header_button_url'                     => '',
			'header_topline_options_heading'        => '',
			'topline'                               => '',
			'topline_fluid'                         => '1',
			'topline_background'                    => 'i c',
			'meta_topline_text'                     => '',
			'topline_font_size'                     => 'fs-14',
			'title'                                 => '1',
			'title_show_title'                      => '1',
			'title_show_breadcrumbs'                => '1',
			'title_show_search'                     => '',
			'title_background'                      => '',
			'title_border_top'                      => '',
			'title_border_bottom'                   => '',
			'title_extra_padding_top'               => 'pt-3',
			'title_extra_padding_bottom'            => 'pb-1',
			'title_font_size'                       => '',
			'title_background_image'                => '',
			'title_background_image_cover'          => '1',
			'title_background_image_fixed'          => '1',
			'title_background_image_overlay'        => '',
			'title_single_post_meta_heading'           => '',
			'title_blog_single_hide_meta_icons'        => '',
			'title_blog_single_show_author'            => '',
			'title_blog_single_show_author_avatar'     => '',
			'title_blog_single_before_author_word'     => '',
			'title_blog_single_show_date'              => '',
			'title_blog_single_before_date_word'       => '',
			'title_blog_single_show_human_date'        => '',
			'title_blog_single_show_categories'        => '',
			'title_blog_single_before_categories_word' => '',
			'title_blog_single_show_tags'              => '',
			'title_blog_single_before_tags_word'       => '',
			'title_blog_single_show_comments_link'     => '',
			'main_sidebar_width'                    => '25',
			'main_gap_width'                        => '',
			'main_sidebar_sticky'                   => '1',
			'main_extra_padding_top'                => '',
			'main_extra_padding_bottom'             => 'pb-5',
			'main_font_size'                        => '',
			'sidebar_font_size'                     => 'fs-14',
			'footer'                                => '1',
			'footer_layout_gap'                     => '30',
			'footer_fluid'                          => '',
			'footer_background'                     => '',
			'footer_border_top'                     => '',
			'footer_border_bottom'                  => '',
			'footer_extra_padding_top'              => 'pt-5',
			'footer_extra_padding_bottom'           => 'pb-3',
			'footer_font_size'                      => 'fs-14',
			'footer_background_image'               => '',
			'footer_background_image_cover'         => '1',
			'footer_background_image_fixed'         => '1',
			'footer_background_image_overlay'       => '',
			'copyright'                             => '1',
			'copyright_text'                        => esc_html__( '&copy; [year] - All rights reserved', 'exs' ),
			'copyright_fluid'                       => '',
			'copyright_background'                  => '',
			'copyright_extra_padding_top'           => 'pt-2',
			'copyright_extra_padding_bottom'        => 'pb-2',
			'copyright_font_size'                   => 'fs-14',
			'copyright_background_image'            => '',
			'copyright_background_image_cover'      => '',
			'copyright_background_image_fixed'      => '',
			'copyright_background_image_overlay'    => '',
			'font_body_heading'                     => '',
			'font_body'                             => '{"font":"","variant":[],"subset":[]}',
			'font_headings_heading'                 => '',
			'font_headings'                         => '{"font":"","variant":[],"subset":[]}',
			'blog_layout'                           => '',
			'blog_layout_gap'                       => '30',
			'blog_sidebar_position'                 => 'right',
			'blog_page_name'                        => '',
			'blog_show_full_text'                   => '',
			'blog_excerpt_length'                   => '20',
			'search_excerpt_length'                 => '20',
			'blog_read_more_text'                   => '',
			'blog_hide_taxonomy_type_name'          => '',
			'blog_meta_options_heading'             => '',
			'blog_hide_meta_icons'                  => '',
			'blog_show_author'                      => '1',
			'blog_before_author_word'               => '',
			'blog_show_date'                        => '1',
			'blog_before_date_word'                 => '',
			'blog_show_categories'                  => '1',
			'blog_before_categories_word'           => '',
			'blog_show_tags'                        => '1',
			'blog_before_tags_word'                 => '',
			'blog_show_comments_link'               => 'number',
			'blog_single_layout'                    => '',
			'blog_single_sidebar_position'          => 'no',
			'blog_single_show_author_bio'           => '1',
			'blog_single_author_bio_about_word'     => '',
			'blog_single_post_nav_heading'          => '',
			'blog_single_post_nav'                  => 'title',
			'blog_single_post_nav_word_prev'        => '',
			'blog_single_post_nav_word_next'        => '',
			'blog_single_related_posts_heading'     => '',
			'blog_single_related_posts'             => 'list',
			'blog_single_related_posts_title'       => '',
			'blog_single_related_posts_number'      => '3',
			'blog_single_meta_options_heading'      => '',
			'blog_single_hide_meta_icons'           => '',
			'blog_single_show_author'               => '1',
			'blog_single_before_author_word'        => '',
			'blog_single_show_date'                 => '1',
			'blog_single_before_date_word'          => '',
			'blog_single_show_categories'           => '1',
			'blog_single_before_categories_word'    => '',
			'blog_single_show_tags'                 => '1',
			'blog_single_before_tags_word'          => '',
			'blog_single_show_comments_link'        => 'number',
			'category_portfolio_heading'            => '',
			'category_portfolio'                    => '',
			'category_portfolio_layout'             => 'cols-absolute-no-meta 3',
			'category_portfolio_layout_gap'         => '5',
			'category_portfolio_sidebar_position'   => 'no',
			'category_services_heading'             => '',
			'category_services'                     => '',
			'category_services_layout'              => 'cols-excerpt 3',
			'category_services_layout_gap'          => '60',
			'category_services_sidebar_position'    => 'no',
			'category_team_heading'                 => '',
			'category_team'                         => '',
			'category_team_layout'                  => 'cols-excerpt 3',
			'category_team_layout_gap'              => '50',
			'category_team_sidebar_position'        => 'no',
			'animation_enabled'                     => '',
			'animation_sidebar_widgets'             => '',
			'animation_footer_widgets'              => '',
			'animation_feed_posts'                  => '',
			'animation_feed_posts_thumbnail'        => '',
			'message_top_heading'                   => '',
			'message_top_id'                        => '',
			'message_top_text'                      => '',
			'message_top_close_button_text'         => '',
			'message_top_background'                => 'l m',
			'message_top_font_size'                 => '',
			'message_bottom_heading'                => '',
			'message_bottom_id'                     => '',
			'message_bottom_text'                   => '',
			'message_bottom_close_button_text'      => '',
			'message_bottom_background'             => 'l m',
			'message_bottom_font_size'              => '',
			'message_bottom_layout'                 => '',
			'message_bottom_bordered'               => '',
			'message_bottom_shadow'                 => '',
			'message_bottom_rounded'                => '',
			'intro_teaser_image_1'                  => '',
			'intro_teaser_title_1'                  => '',
			'intro_teaser_text_1'                   => '',
			'intro_teaser_link_1'                   => '',
			'intro_teaser_button_text_1'            => '',
			'intro_teaser_image_2'                  => '',
			'intro_teaser_title_2'                  => '',
			'intro_teaser_text_2'                   => '',
			'intro_teaser_link_2'                   => '',
			'intro_teaser_button_text_2'            => '',
			'intro_teaser_image_3'                  => '',
			'intro_teaser_title_3'                  => '',
			'intro_teaser_text_3'                   => '',
			'intro_teaser_link_3'                   => '',
			'intro_teaser_button_text_3'            => '',
			'intro_teaser_image_4'                  => '',
			'intro_teaser_title_4'                  => '',
			'intro_teaser_text_4'                   => '',
			'intro_teaser_link_4'                   => '',
			'intro_teaser_button_text_4'            => '',
			'contact_message_success'               => esc_html__( 'Message was sent!', 'exs' ),
			'contact_message_fail'                  => esc_html__( 'There was an error during message sending!', 'exs' ),
		);

		return apply_filters( 'exs_default_theme_options', $exs_options );
	}
endif;

//get theme option from default or from customizer
if ( ! function_exists( 'exs_option' ) ) :
	function exs_option( $exs_option_name, $exs_default_value = '' ) {
		//get theme defaults
		$exs_defaults = exs_get_default_options_array();

		//lowest priority is basic default value from theme defaults
		$exs_return = ( isset( $exs_defaults[ $exs_option_name ] ) ) ? $exs_defaults[ $exs_option_name ] : $exs_default_value;

		unset( $exs_defaults );

		//theme_mods are higher - if not empty - overriding value from theme default
		$exs_return = get_theme_mod( $exs_option_name, $exs_return );

		return $exs_return;
	}
endif;

//layout options array. Used global in customizer and for categories
if ( ! function_exists( 'exs_get_feed_layout_options' ) ) :
	function exs_get_feed_layout_options( $exs_category = false ) {
		if ( empty( $exs_category ) ) {
			$exs_first_element = esc_html__( 'Default - top featured image', 'exs' );
		} else {
			$exs_first_element = esc_html__( 'Inherit from Customizer settings', 'exs' );
		}

		$exs_return = apply_filters(
			'exs_feed_layout_options',
			array(
				''                                => $exs_first_element,
				'default-centered'                => esc_html__( 'Center aligned', 'exs' ),
				'default-wide-image'              => esc_html__( 'Wide featured image', 'exs' ),
				'meta-top'                        => esc_html__( 'Meta above image', 'exs' ),
				'meta-side'                       => esc_html__( 'Side post meta', 'exs' ),
				'default-absolute'                => esc_html__( 'Image with meta overlap', 'exs' ),
				'side'                            => esc_html__( 'Side featured image', 'exs' ),
				'side-small'                      => esc_html__( 'Side small featured image', 'exs' ),
				'side-small 2'                    => esc_html__( 'Side small featured image - 2 columns', 'exs' ),
				'side-small 2 masonry'            => esc_html__( 'Side small featured image - 2 columns Masonry', 'exs' ),
				'title-only'                      => esc_html__( 'Only title (no image, meta and excerpt)', 'exs' ),
				'title-meta-only'                 => esc_html__( 'Only title and meta (no image and excerpt)', 'exs' ),
				'cols 2'                          => esc_html__( 'Grid - 2 columns', 'exs' ),
				'cols 3'                          => esc_html__( 'Grid - 3 columns', 'exs' ),
				'cols 4'                          => esc_html__( 'Grid - 4 columns', 'exs' ),
				'cols-absolute 2'                 => esc_html__( 'Grid - meta overlap - 2 cols', 'exs' ),
				'cols-absolute 3'                 => esc_html__( 'Grid - meta overlap - 3 cols', 'exs' ),
				'cols-absolute 4'                 => esc_html__( 'Grid - meta overlap - 4 cols', 'exs' ),
				'cols-absolute-no-meta 2'         => esc_html__( 'Grid - title overlap - 2 cols', 'exs' ),
				'cols-absolute-no-meta 3'         => esc_html__( 'Grid - title overlap - 3 cols', 'exs' ),
				'cols-absolute-no-meta 4'         => esc_html__( 'Grid - title overlap - 4 cols', 'exs' ),
				'cols-excerpt 2'                  => esc_html__( 'Grid - centered excerpt no meta - 2 cols', 'exs' ),
				'cols-excerpt 3'                  => esc_html__( 'Grid - centered excerpt no meta - 3 cols', 'exs' ),
				'cols-excerpt 4'                  => esc_html__( 'Grid - centered excerpt no meta - 4 cols', 'exs' ),
				'cols 2 masonry'                  => esc_html__( 'Masonry - 2 columns', 'exs' ),
				'cols 3 masonry'                  => esc_html__( 'Masonry - 3 columns', 'exs' ),
				'cols 4 masonry'                  => esc_html__( 'Masonry - 4 columns', 'exs' ),
				'cols-absolute 2 masonry'         => esc_html__( 'Masonry - meta overlap - 2 cols', 'exs' ),
				'cols-absolute 3 masonry'         => esc_html__( 'Masonry - meta overlap - 3 cols', 'exs' ),
				'cols-absolute 4 masonry'         => esc_html__( 'Masonry - meta overlap - 4 cols', 'exs' ),
				'cols-absolute-no-meta 2 masonry' => esc_html__( 'Masonry - title overlap - 2 cols', 'exs' ),
				'cols-absolute-no-meta 3 masonry' => esc_html__( 'Masonry - title overlap - 3 cols', 'exs' ),
				'cols-absolute-no-meta 4 masonry' => esc_html__( 'Masonry - title overlap - 4 cols', 'exs' ),
				'cols-excerpt 2 masonry'          => esc_html__( 'Masonry - centered excerpt no meta - 2 cols', 'exs' ),
				'cols-excerpt 3 masonry'          => esc_html__( 'Masonry - centered excerpt no meta - 3 cols', 'exs' ),
				'cols-excerpt 4 masonry'          => esc_html__( 'Masonry - centered excerpt no meta - 4 cols', 'exs' ),
			)
		);

		return $exs_return;
	}
endif;

//gap options array. Used global in customizer and for categories
if ( ! function_exists( 'exs_get_feed_layout_gap_options' ) ) :
	function exs_get_feed_layout_gap_options( $exs_category = false ) {
		if ( empty( $exs_category ) ) {
			$exs_first_element = esc_html__( 'Default - none', 'exs' );
		} else {
			$exs_first_element = esc_html__( 'Inherit from Customizer settings', 'exs' );
		}

		$exs_return = apply_filters(
			'exs_feed_layout_gap_options',
			array(
				''   => $exs_first_element,
				'1'  => esc_html__( '1px', 'exs' ),
				'2'  => esc_html__( '2px', 'exs' ),
				'3'  => esc_html__( '3px', 'exs' ),
				'4'  => esc_html__( '4px', 'exs' ),
				'5'  => esc_html__( '5px', 'exs' ),
				'10' => esc_html__( '10px', 'exs' ),
				'15' => esc_html__( '15px', 'exs' ),
				'20' => esc_html__( '20px', 'exs' ),
				'30' => esc_html__( '30px', 'exs' ),
				'40' => esc_html__( '40px', 'exs' ),
				'50' => esc_html__( '50px', 'exs' ),
				'60' => esc_html__( '60px', 'exs' ),
			)
		);

		return $exs_return;
	}
endif;


//layout options array. Used global in customizer and for single posts
if ( ! function_exists( 'exs_get_post_layout_options' ) ) :
	function exs_get_post_layout_options( $exs_category = false ) {
		if ( empty( $exs_category ) ) {
			$exs_first_element = esc_html__( 'Default - top featured image', 'exs' );
		} else {
			$exs_first_element = esc_html__( 'Inherit from Customizer settings', 'exs' );
		}

		$exs_return = apply_filters(
			'exs_post_layout_options',
			array(
				''                    => $exs_first_element,
				'wide-image'          => esc_html__( 'Wide featured image', 'exs' ),
				'meta-top'            => esc_html__( 'Post meta above featured image', 'exs' ),
				'meta-image'          => esc_html__( 'Post meta on featured image', 'exs' ),
				'meta-side'           => esc_html__( 'Side post meta', 'exs' ),
				'bottom-meta'         => esc_html__( 'Bottom post meta', 'exs' ),
				'title-section-image' => esc_html__( 'Title section featured image', 'exs' ),
			)
		);

		return $exs_return;
	}
endif;


//sidebar options array. Used global in customizer and for categories
if ( ! function_exists( 'exs_get_sidebar_position_options' ) ) :
	function exs_get_sidebar_position_options( $exs_category = false ) {
		if ( empty( $exs_category ) ) {
			$exs_first_element = esc_html__( 'Default', 'exs' );
		} else {
			$exs_first_element = esc_html__( 'Inherit from Customizer settings', 'exs' );
		}

		$exs_return = array(
			'right' => esc_html__( 'Right sidebar', 'exs' ),
			'left'  => esc_html__( 'Left sidebar', 'exs' ),
			'no'    => esc_html__( 'No sidebar', 'exs' ),
		);

		if ( $exs_category ) {
			$exs_return = array( $exs_first_element ) + $exs_return;
		}

		return $exs_return;
	}
endif;

//animation options array.
if ( ! function_exists( 'exs_get_animation_options' ) ) :
	function exs_get_animation_options() {

		$exs_return = array(
			''             => esc_html__( 'None', 'exs' ),
			'bounce'       => esc_html__( 'bounce', 'exs' ),
			'flash'        => esc_html__( 'flash', 'exs' ),
			'pulse'        => esc_html__( 'pulse', 'exs' ),
			'rubberBand'   => esc_html__( 'rubberBand', 'exs' ),
			'shake'        => esc_html__( 'shake', 'exs' ),
			'headShake'    => esc_html__( 'headShake', 'exs' ),
			'swing'        => esc_html__( 'swing', 'exs' ),
			'tada'         => esc_html__( 'tada', 'exs' ),
			'wobble'       => esc_html__( 'wobble', 'exs' ),
			'jello'        => esc_html__( 'jello', 'exs' ),
			'heartBeat'    => esc_html__( 'heartBeat', 'exs' ),
			'bounceIn'     => esc_html__( 'bounceIn', 'exs' ),
			'fadeIn'       => esc_html__( 'fadeIn', 'exs' ),
			'fadeInDown'   => esc_html__( 'fadeInDown', 'exs' ),
			'fadeInLeft'   => esc_html__( 'fadeInLeft', 'exs' ),
			'fadeInRight'  => esc_html__( 'fadeInRight', 'exs' ),
			'fadeInUp'     => esc_html__( 'fadeInUp', 'exs' ),
			'flip'         => esc_html__( 'flip', 'exs' ),
			'flipInX'      => esc_html__( 'flipInX', 'exs' ),
			'flipInY'      => esc_html__( 'flipInY', 'exs' ),
			'lightSpeedIn' => esc_html__( 'lightSpeedIn', 'exs' ),
			'jackInTheBox' => esc_html__( 'jackInTheBox', 'exs' ),
			'zoomIn'       => esc_html__( 'zoomIn', 'exs' ),
		);

		return $exs_return;
	}
endif;

//special categories process

/**
 * Tests if any of a post's assigned categories are descendants of target categories
 *
 * @param int|array $exs_cats The target categories. Integer ID or array of integer IDs
 * @param int|object $exs_post The post. Omit to test the current post in the Loop or main query
 *
 * @return bool True if at least 1 of the post's categories is a descendant of any of the target categories
 * @see  get_term_by() You can get a category by name or slug, then pass ID to this function
 * @uses get_term_children() Passes $exs_cats
 * @uses in_category() Passes $exs_post (can be empty)
 * @version 2.7
 * @link http://codex.wordpress.org/Function_Reference/in_category#Testing_if_a_post_is_in_a_descendant_category
 */
if ( ! function_exists( 'exs_post_is_in_descendant_category' ) ) :
	function exs_post_is_in_descendant_category( $exs_cats, $exs_post = null ) {
		foreach ( (array) $exs_cats as $exs_cat ) {
			// get_term_children() accepts integer ID only
			$exs_descendants = get_term_children( (int) $exs_cat, 'category' );
			if ( $exs_descendants && in_category( $exs_descendants, $exs_post ) ) {
				return true;
			}
		}

		return false;
	}
endif;

//get selected categories from customizer options
if ( ! function_exists( 'exs_get_special_categories_from_options' ) ) :
	function exs_get_special_categories_from_options() {

		return array_filter(
			array(
				'services'  => exs_option( 'category_services', '' ),
				'portfolio' => exs_option( 'category_portfolio', '' ),
				'team'      => exs_option( 'category_team', '' ),
			)
		);

	}
endif;

//get special categories IDs array
if ( ! function_exists( 'exs_get_special_categories_from_options_ids' ) ) :
	function exs_get_special_categories_from_options_ids() {

		$exs_cats   = exs_get_special_categories_from_options();
		$exs_return = array();
		foreach ( $exs_cats as $exs_key => $exs_cat ) {
			$exs_return[] = $exs_cat;
		}

		return $exs_return;

	}
endif;


//get special categories IDs array with children
if ( ! function_exists( 'exs_get_special_categories_from_options_ids_with_children' ) ) :
	function exs_get_special_categories_from_options_ids_with_children() {

		$exs_cats   = exs_get_special_categories_from_options_ids();
		$exs_return = array();
		foreach ( $exs_cats as $exs_cat_id ) {
			$exs_children = get_term_children( $exs_cat_id, 'category' );
			$exs_return[] = $exs_cat_id;
			foreach ( $exs_children as $exs_child_id ) {
				$exs_return[] = $exs_child_id;
			}
		}

		return $exs_return;

	}
endif;

//adding minus at the front of each special category
if ( ! function_exists( 'exs_get_special_categories_from_options_with_minus' ) ) :
	function exs_get_special_categories_from_options_with_minus() {

		$exs_cats   = exs_get_special_categories_from_options();
		$exs_return = array();
		foreach ( $exs_cats as $exs_key => $exs_cat ) {
			$exs_return[] = '-' . $exs_cat;
		}

		return $exs_return;

	}
endif;

//check if post in special categories
if ( ! function_exists( 'exs_get_post_special_category_slug' ) ) :
	function exs_get_post_special_category_slug() {
		$exs_special_cats          = exs_get_special_categories_from_options();
		$exs_id                    = get_the_ID();
		$exs_special_category_slug = false;

		foreach ( $exs_special_cats as $exs_slug => $exs_cat_id ) {
			//https://wordpress.stackexchange.com/questions/155332/check-if-a-post-is-in-any-child-category-of-a-parent-category
			if ( in_category( $exs_cat_id, $exs_id ) || exs_post_is_in_descendant_category( $exs_cat_id, $exs_id ) ) {
				$exs_special_category_slug = $exs_slug;
				break;
			}
		}

		return $exs_special_category_slug;
	}
endif;

//get :root colors inline styles string
if ( ! function_exists( 'exs_get_root_colors_inline_styles_string' ) ) :
	function exs_get_root_colors_inline_styles_string() {
		//colors
		$exs_colors_string = '';
		// colorLight
		// colorFont
		// colorFontMuted
		// colorBackground
		// colorBorder
		// colorDark
		// colorDarkMuted
		// colorMain
		// colorMain2
		$exs_colors_string .= exs_option( 'colorLight', '' ) ? '--colorLight:' . sanitize_hex_color( exs_option( 'colorLight', '' ) ) . ';' : '';
		$exs_colors_string .= exs_option( 'colorFont', '' ) ? '--colorFont:' . sanitize_hex_color( exs_option( 'colorFont', '' ) ) . ';' : '';
		$exs_colors_string .= exs_option( 'colorFontMuted', '' ) ? '--colorFontMuted:' . sanitize_hex_color( exs_option( 'colorFontMuted', '' ) ) . ';' : '';
		$exs_colors_string .= exs_option( 'colorBackground', '' ) ? '--colorBackground:' . sanitize_hex_color( exs_option( 'colorBackground', '' ) ) . ';' : '';
		$exs_colors_string .= exs_option( 'colorBorder', '' ) ? '--colorBorder:' . sanitize_hex_color( exs_option( 'colorBorder', '' ) ) . ';' : '';
		$exs_colors_string .= exs_option( 'colorDark', '' ) ? '--colorDark:' . sanitize_hex_color( exs_option( 'colorDark', '' ) ) . ';' : '';
		$exs_colors_string .= exs_option( 'colorDarkMuted', '' ) ? '--colorDarkMuted:' . sanitize_hex_color( exs_option( 'colorDarkMuted', '' ) ) . ';' : '';
		$exs_colors_string .= exs_option( 'colorMain', '' ) ? '--colorMain:' . sanitize_hex_color( exs_option( 'colorMain', '' ) ) . ';' : '';
		$exs_colors_string .= exs_option( 'colorMain2', '' ) ? '--colorMain2:' . sanitize_hex_color( exs_option( 'colorMain2', '' ) ) . ';' : '';

		return apply_filters( 'exs_root_colors_inline_styles_string', $exs_colors_string );
	}
endif;

//get typography settings inline styles string
if ( ! function_exists( 'exs_get_typography_inline_styles_string' ) ) :
	function exs_get_typography_inline_styles_string() {
		//typography
		$exs_typography_string = '';
		$exs_body_string = '';

		//body
		$exs_body_string .= exs_option( 'typo_body_size' ) ? 'font-size:' . (int) exs_option( 'typo_body_size' ) . 'px;' : '';
		$exs_body_string .= exs_option( 'typo_body_weight' ) ? 'font-weight:' . (int) exs_option( 'typo_body_weight' ) . ';' : '';
		$exs_body_string .= exs_option( 'typo_body_line_height' ) ? 'line-height:' . (float) exs_option( 'typo_body_line_height' ) . 'em;' : '';
		$exs_body_string .= exs_option( 'typo_body_letter_spacing' ) ? 'letter-spacing:' . (float) exs_option( 'typo_body_letter_spacing' ) . 'em;' : '';

		if ( $exs_body_string ) {
			$exs_typography_string = 'body{' . $exs_body_string . '}';
		}

		//paragraph
		$exs_typography_string .= ! empty( exs_option( 'typo_p_margin_bottom' ) ) ? 'p{margin-bottom:' . (float) exs_option( 'typo_p_margin_bottom' ) . 'em;}' : '';

		//headings
		foreach( array( 1, 2, 3, 4, 5, 6 ) as $h ) {
			$h_string = '';
			$h_string .= ! empty( exs_option( 'typo_size_h' . $h ) ) ? 'font-size:' . (float) exs_option( 'typo_size_h' . $h ) . 'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_line_height_h' . $h ) ) ? 'line-height:' . (float) exs_option( 'typo_line_height_h' . $h ) . 'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_letter_spacing_h' . $h ) ) ? 'letter-spacing:' . exs_option( 'typo_letter_spacing_h' . $h ) .'em;' : '';
			$h_string .= ! empty( exs_option( 'typo_weight_h' . $h ) ) ? 'font-weight:' . (int) exs_option( 'typo_weight_h' . $h ) . ';' : '';
			$h_string .= ! empty( exs_option( 'typo_mt_h' . $h ) ) ? 'margin-bottom:' . (float) exs_option( 'typo_mt_h' . $h ) . 'em;': '';
			$h_string .= ! empty( exs_option( 'typo_mb_h' . $h ) ) ? 'margin-top:' . (float) exs_option( 'typo_mb_h' . $h ) . 'em;' : '';

			if ( $h_string ) {
				$exs_typography_string .= 'h' . $h . '{' . $h_string . '}';
			}
		}

		return apply_filters( 'exs_typography_inline_styles_string', $exs_typography_string );
	}
endif;
