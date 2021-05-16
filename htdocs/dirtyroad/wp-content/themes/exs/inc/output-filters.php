<?php
/**
 * Template HTML output filters
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//remove 'has-post-thumbnail' post_class if appropriate single post layout is selected
//add 'no-post-thumbnail' if there is no post thumbnail added
if ( ! function_exists( 'exs_filter_post_class' ) ) :
	function exs_filter_post_class( $exs_classes, $additional_class, $post_id ) {
		$exs_special_slug = exs_get_post_special_category_slug();
		if ( is_single() && empty( $exs_special_slug ) && 'title-section-image' === exs_option( 'blog_single_layout', '' ) ) {
			$exs_key = array_search( 'has-post-thumbnail', $exs_classes, true );
			unset( $exs_classes[ $exs_key ] );
		}
		if (
			post_password_required( $post_id )
			||
			is_attachment()
			||
			! has_post_thumbnail( $post_id )
			||
			! file_exists( get_attached_file( get_post_thumbnail_id( $post_id ) ) )
		) {
			$exs_classes[] = 'no-post-thumbnail';
		}

		return $exs_classes;
	}
endif;
add_filter( 'post_class', 'exs_filter_post_class', 10, 3 );

// Wraps the title's each word in separate span
if ( ! function_exists( 'exs_filter_widget_title' ) ) :
	/**
	 * Wraps the title words in spans.
	 *
	 * @param string $exs_title The string.
	 *
	 * @return string          The modified string.
	 */
	function exs_filter_widget_title( $exs_title ) {
		//RSS escaping HTML in title and break it

		if ( empty( $exs_title ) ) {
			return;
		}

		if ( stripos( $exs_title, 'rss' ) !== false ) {
			return $exs_title;
		}

		// Cut the title into words.
		$exs_words = explode( ' ', $exs_title );

		$exs_array = array();

		foreach ( $exs_words as $exs_index => $exs_word ) {
			$exs_counter = $exs_index + 1;
			$exs_array[] = '<span class="widget-title-word widget-title-word-' . esc_attr( $exs_counter ) . '">' . esc_html( $exs_word ) . '</span>';
		}

		return implode( ' ', $exs_array );
	}
endif;
add_filter( 'widget_title', 'exs_filter_widget_title', 999 );

//Since 5.4 this do not needed?
//filter calendar widget to fix validation errors
if ( ! function_exists( 'exs_filter_widget_calendar_html' ) ) :
	function exs_filter_widget_calendar_html( $exs_html ) {
		//get tfoot
		$exs_tfoot = preg_match( '/<tfoot>(.|\n)*<\/tfoot>/', $exs_html, $exs_match );
		//remove tfoot from table
		$exs_html = preg_replace( '/<tfoot>(.|\n)*<\/tfoot>/', '', $exs_html );
		//attach tfoot after tbody
		if ( ! empty( $exs_match[0] ) ) {
			$exs_html = str_replace( '</tbody>', "</tbody>\n\t" . $exs_match[0], $exs_html );
		}

		return $exs_html;
	} //exs_filter_widget_calendar_html()
endif;
add_filter( 'get_calendar', 'exs_filter_widget_calendar_html' );

//wrapping in a span widgets categories and archives items count - but skip dropdowns
if ( ! function_exists( 'exs_filter_add_span_to_arhcive_widget_count' ) ) :
	function exs_filter_add_span_to_arhcive_widget_count( $exs_links ) {
		if ( stristr( $exs_links, '<option' ) ) {
			return $exs_links;
		}

		//for woo categories widget
		$exs_links = str_replace( '<span class="count">(', '<span class="count"><span class="count-open">(</span>', $exs_links );

		//for categories widget
		$exs_links = str_replace( '</a> (', '</a> <span class="count"><span class="count-open">(</span>', $exs_links );
		//for archive widget
		$exs_links = str_replace( '&nbsp;(', ' <span class="count"><span class="count-open">(</span>', $exs_links );
		$exs_links = preg_replace( '/([0-9]+)\)/', '$1<span class="count-close">)</span></span>', $exs_links );

		//putting span before link for styling purpose
		$exs_links = preg_replace( '~(<a href=.*</a>) (<span class="count"><span class="count-open">\(</span>([0-9]*)<span class="count-close">\)</span></span>)~', '$2$1', $exs_links );

		return $exs_links;
	}
endif;
add_filter( 'wp_list_categories', 'exs_filter_add_span_to_arhcive_widget_count' );
add_filter( 'get_archives_link', 'exs_filter_add_span_to_arhcive_widget_count' );

//wrapping tag links in span
if ( ! function_exists( 'exs_filter_add_spans_to_tag_links' ) ) :
	function exs_filter_add_spans_to_tag_links( $exs_html ) {

		$exs_html = str_replace( '<a', '<span><a', $exs_html );
		$exs_html = str_replace( '</a>', '</a></span>', $exs_html );

		return $exs_html;
	}
endif;
add_filter( 'wp_tag_cloud', 'exs_filter_add_spans_to_tag_links' );

//wrapping "category" word in title area in a span
if ( ! function_exists( 'exs_filter_wrap_cat_title_before_colon_in_span' ) ) :
	function exs_filter_wrap_cat_title_before_colon_in_span( $exs_title ) {
		$exs_hide_tax_name_title = exs_option( 'blog_hide_taxonomy_type_name', false );
		if ( is_category() && exs_get_post_special_category_slug() ) {
			$exs_hide_tax_name_title = true;
		}
		if ( empty( $exs_hide_tax_name_title ) ) {
			return preg_replace( '/^.*: /', '<span class="taxonomy-name-title">${0}</span>', $exs_title );
		} else {
			return preg_replace( '/^.*: /', '', $exs_title );
		}
	}
endif;
add_filter( 'get_the_archive_title', 'exs_filter_wrap_cat_title_before_colon_in_span' );

/**
 * Fix active class in nav for blog page and special cats or post inside special cat.
 *
 * @param array $menu_items Menu items.
 * @return array
 */
if ( ! function_exists( 'exs_filter_nav_menu_item_classes' ) ) :
	function exs_filter_nav_menu_item_classes( $menu_items ) {
		if ( ! is_category( exs_get_special_categories_from_options_ids_with_children() ) && ! is_singular( 'post' ) ) {
			return $menu_items;
		}

		$page_for_posts = (int) get_option( 'page_for_posts' );

		$exs_special_slug = is_singular( 'post' ) ? exs_get_post_special_category_slug() : false;

		if ( ! empty( $menu_items ) && is_array( $menu_items ) ) {
			foreach ( $menu_items as $key => $menu_item ) {
				$classes = (array) $menu_item->classes;
				$menu_id = (int) $menu_item->object_id;

				// Unset active class for blog page.
				if ( ( $page_for_posts === $menu_id && is_category() ) || $exs_special_slug ) {
					$menu_items[ $key ]->current = false;

					if ( in_array( 'current_page_parent', $classes, true ) ) {
						unset( $classes[ array_search( 'current_page_parent', $classes, true ) ] );
					}
				}

				$menu_items[ $key ]->classes = array_unique( $classes );
			}
		}

		return $menu_items;
	}
endif;
add_filter( 'wp_nav_menu_objects', 'exs_filter_nav_menu_item_classes' );

// add icon to edit comment link
if ( ! function_exists( 'exs_filter_edit_comment_link' ) ) :
	function exs_filter_edit_comment_link( $edit_comment_html ) {
		$edit_comment_html = str_replace( '<span class="edit-link">', '<span class="edit-link"> ', $edit_comment_html );

		return $edit_comment_html;
	}
endif;
add_filter( 'edit_comment_link', 'exs_filter_edit_comment_link' );

// add 'data-hover' attribute to nav menu link
if ( ! function_exists( 'exs_filter_menu_item_data_hover_attribute' ) ) :
	function exs_filter_menu_item_data_hover_attribute( $atts, $item, $args, $depth ) {
		$atts['data-hover'] = $item->title;
		return $atts;
	}
endif;
add_filter( 'nav_menu_link_attributes', 'exs_filter_menu_item_data_hover_attribute', 4, 10 );

//add ALT text on post thumbnail if it is empty
if ( ! function_exists( 'exs_filter_post_thumbnail_add_alt_text_if_empty' ) ) :
	function exs_filter_post_thumbnail_add_alt_text_if_empty( $html, $post_id ) {
		return str_replace( 'alt=""', 'alt="' . esc_attr( get_the_title( $post_id ) ) . '"', $html );
	}
endif;
add_filter( 'post_thumbnail_html', 'exs_filter_post_thumbnail_add_alt_text_if_empty', 10, 2 );

//add spans for all headings in the_content
if ( ! function_exists( 'exs_filter_the_content_add_spans_to_headings' ) ) :
	function exs_filter_the_content_add_spans_to_headings( $html ) {
		//the_title markup:
		//'<span class="hidden" itemscope="itemscope" itemprop="headline" itemtype="https://schema.org/Text">' . get_the_title() .'</span>';

		$exs_headings           = array( '<h1>', '<h2>', '<h3>', '<h4>', '<h5>', '<h6>' );
		$exs_headings_with_atts = array( '<h1.*">', '<h2.*">', '<h3.*">', '<h4.*">', '<h5.*">', '<h6.*">' );
		$exs_headings_end       = array( '</h1>', '</h2>', '</h3>', '</h4>', '</h5>', '</h6>' );
		foreach ( $exs_headings as $exs_heading ) {
			$html = str_replace( $exs_heading, $exs_heading . '<span class="heading-inner">', $html );
		}
		foreach ( $exs_headings_end as $exs_heading ) {
			$html = str_replace( $exs_heading, '</span>' . $exs_heading, $html );
		}
		foreach ( $exs_headings_with_atts as $exs_heading ) {
			$html = preg_replace( '/(' . $exs_heading . ')/', '${1}<span class="heading-inner">', $html );
		}
		return $html;
	}
endif;
add_filter( 'exs_the_content', 'exs_filter_the_content_add_spans_to_headings', 99 );

//add table of contents based on headings
if ( ! function_exists( 'exs_filter_the_content_add_toc_in_post' ) ) :
	function exs_filter_the_content_add_toc_in_post( $html ) {

		//return if not single post
		if ( ! is_singular( 'post' ) || empty( exs_option( 'blog_single_toc_enabled' ) ) ) {
			return $html;
		}

		$toc_html = '';
		$h = preg_match_all('~<h[1-6](.*?)>(.*)</h[1-6]>~i',$html,$matches);

		if ( ! empty( $h ) ) :
			//set IDs for titles
			foreach ( $matches[0] as $key => $heading ) {
				if ( stripos( $heading, ' id=' ) === false ) {

					$pos = strpos( $heading, '>' );
					if ($pos !== false) {
						$new_heading = substr_replace( $heading, ' id="' . sanitize_title( $matches[2][ $key ] ). '">', $pos, strlen('>' ) );
						$html = str_replace( $heading, $new_heading, $html );
					}
				}
			}

			//create a TOC markup
			$toc_title = exs_option( 'blog_single_toc_title', '' );
			$toc_bg = exs_option( 'blog_single_toc_background', '' );
			$toc_mt = exs_option( 'blog_single_toc_mt', 'mt-2' );
			$toc_mb = exs_option( 'blog_single_toc_mb', 'mb-2' );
			$toc_padding = $toc_bg ? ' extra-padding' : '';
			$toc_bordered = exs_option( 'blog_single_toc_bordered', '' ) ? ' bordered' : '';
			$toc_shadow = exs_option( 'blog_single_toc_shadow', '' ) ? ' shadow' : '';
			$toc_rounded = exs_option( 'blog_single_toc_rounded', '' ) ? ' rounded' : '';
			$toc_html .= '<aside class="exs-toc ' . esc_attr( $toc_mt . ' ' . $toc_mb . ' ' . $toc_bg . $toc_padding . $toc_bordered . $toc_shadow . $toc_rounded ) . '">';
			if ( ! empty( $toc_title ) ) {
				$toc_html .= '<h3 class="exs-toc-title mb-05">' . esc_html ( $toc_title ) . '</h3>';
			}
			$toc_html .= '<nav class="exs-toc-nav"><ul class="mb-0">';
			foreach ( $matches[2] as $title ) {
				$toc_html .= '<li><a class="exs-toc-item" href="#' .  sanitize_title( $title ) . '">' . $title . '</a></li>';
			}
			$toc_html .= '</ul></nav></aside>';
		endif; //! empty $h
		return $toc_html . $html;
	}
endif;
add_filter( 'the_content', 'exs_filter_the_content_add_toc_in_post', 99 );

//remove 'role="navigation"' from 'nav' pagination element
if ( ! function_exists( 'exs_filter_navigation_markup_template' ) ) :
	function exs_filter_navigation_markup_template( $html ) {
		$html = str_replace( 'role="navigation" ', '', $html );
		return $html;
	}
endif;
add_filter( 'navigation_markup_template', 'exs_filter_navigation_markup_template' );

//remove menu-container class from nav_menu widget
if ( ! function_exists( 'exs_filter_widget_nav_menu_args' ) ) :
	function exs_filter_widget_nav_menu_args( $args ) {
		$args = wp_parse_args(
			$args,
			array(
				'container' => false,
			)
		);
		return $args;
	}
endif;
add_filter( 'widget_nav_menu_args', 'exs_filter_widget_nav_menu_args' );

//add custom image size to Gutenberg dropdown
add_filter( 'image_size_names_choose', 'exs_filter_image_size_names_choose' );
if ( ! function_exists( 'exs_filter_image_size_names_choose' ) ) :
	function exs_filter_image_size_names_choose( $sizes ) {
		return array_merge( $sizes, array(
			'exs-square' => esc_html__( 'Square', 'exs' ),
		) );
	}
endif;
