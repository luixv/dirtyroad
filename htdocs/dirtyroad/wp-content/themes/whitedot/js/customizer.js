/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-name' ).text( to );
      $( '.wd-footer-title span' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
      $( '.footer-site-description' ).text( to );
		} );
	} );

  //main header call to action button text
  wp.customize( 'whitedot_header_calltoaction_text', function( value ) {
    value.bind( function( to ) {
      $( '.main-header-calltoaction' ).text( to );
    } );
  } );

  //header notice
  wp.customize( 'whitedot_header_notice', function( value ) {
    value.bind( function( to ) {
      $( '.whitedot-header-notice' ).text( to );
    } );
  } );

  //header notice call to action button text
  wp.customize( 'call_to_action_text', function( value ) {
    value.bind( function( to ) {
      $( '.header-bar-calltoaction' ).text( to );
    } );
  } );

  //Hide Tagline
  wp.customize( 'whitedot_hide_tagline', function( value ) {
      value.bind( function( to ) {
        if (to === true){
          $( '.site-description' ).css( {
                  'display': 'none'
          } );
          $( '.site-branding' ).css( {
                      'padding-top': '20px'
          } );
        }else{
          $( '.site-description' ).css( {
                  'display': 'block'
          } );
           $( '.site-branding' ).css( {
                  'padding-top': '10px'
          } );
        }
      } );
  });

	// Header 
	wp.customize( 'header_text_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-name, .site-description, .primary-nav li a, .wd-cart a, .wd-cart-mob a, .wd-header-search-btn' ).css( {
					'color': to
				} );
		} );
	} );
  wp.customize( 'site_header_color', function( value ) {
    value.bind( function( to ) {
      $( '.site-header' ).css( {
          'background': to
        } );
    } );
  } );
  wp.customize( 'whitedot_mobile_nav_bg_color', function( value ) {
    value.bind( function( to ) {
      $( '#wd-primary-nav.site-nav' ).css( {
          'background': to
        } );
    } );
  } );
  wp.customize( 'whitedot_mobile_nav_text_color', function( value ) {
    value.bind( function( to ) {
      $( '.primary-nav li a, .sub-menu li a, .mob-menu-toggle' ).css( {
          'color': to
        } );
    } );
  } );

  // Above Header bar
  wp.customize( 'above_header_bar_bg_color', function( value ) {
    value.bind( function( to ) {
      $( '.whitedot-above-header-bar' ).css( {
          'background': to
        } );
    } );
  } );
  wp.customize( 'header_notice_text_color', function( value ) {
    value.bind( function( to ) {
      $( '.whitedot-header-notice, .wd-header_bar-social-icons ul li a' ).css( {
          'color': to
        } );
    } );
  } );
  wp.customize( 'calltoaction_bg_color', function( value ) {
    value.bind( function( to ) {
      $( '.header-bar-calltoaction' ).css( {
          'background': to
        } );
    } );
  } );
  wp.customize( 'calltoaction_text_color', function( value ) {
    value.bind( function( to ) {
      $( '.header-bar-calltoaction' ).css( {
          'color': to
        } );
    } );
  } );
  wp.customize( 'whitedot_above_header_height', function( value ) {
      value.bind( function( to ) {
        if (window.matchMedia('(min-width: 768px)').matches) {
          $( '.whitedot-above-header-bar' ).css( {
                  'height': to + 'px'
          } );
          $( 'span.whitedot-header-notice, .wd-header_bar-social-icons ul li a' ).css( {
                  'line-height': to + 'px'
          } );
        }else{
        }
      } );
  });
  wp.customize( 'above_header_bar_border_color', function( value ) {
    value.bind( function( to ) {
      if (window.matchMedia('(min-width: 768px)').matches) {
        $( '.whitedot-above-header-bar' ).css( {
            'border-color': to
          } );
      }
    } );
  } );

	// Body Text color.
	wp.customize( 'whitedot_body_text_color', function( value ) {
        value.bind( function( to ) {
            $( 'body' ).css( {
                    'color': to
            } );
        } );
  	});

	// Header(h1,h2,h3 .... ) color.
	wp.customize( 'whitedot_header_color', function( value ) {
        value.bind( function( to ) {
            $( 'h1, h2, h3, h4, h5, h6' ).css( {
                    'color': to
            } );
        } );
  	});

  	// Link color.
	wp.customize( 'whitedot_link_color', function( value ) {
        value.bind( function( to ) {
            $( 'a' ).css( {
                    'color': to
            } );
        } );
  	});

    // Body Font Size
  wp.customize( 'whitedot_body_text_font_size', function( value ) {
        value.bind( function( to ) {
            $( 'body' ).css( {
                    'font-size': to + 'px'
            } );
        } );
    });

  	// Body Line Height
	wp.customize( 'whitedot_body_text_line_height', function( value ) {
        value.bind( function( to ) {
            $( 'body' ).css( {
                    'line-height': to / 10
            } );
        } );
  	});

    //Heading Typography
  wp.customize( 'whitedot_h1_font_size', function( value ) {
      value.bind( function( to ) {
          $( 'h1' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_h1_font_weight', function( value ) {
      value.bind( function( to ) {
          $( 'h1' ).css( {
                  'font-weight': to
          } );
      } );
  });
  wp.customize( 'whitedot_h2_font_size', function( value ) {
      value.bind( function( to ) {
          $( 'h2' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_h2_font_weight', function( value ) {
      value.bind( function( to ) {
          $( 'h2' ).css( {
                  'font-weight': to
          } );
      } );
  });
  wp.customize( 'whitedot_h3_font_size', function( value ) {
      value.bind( function( to ) {
          $( 'h3' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_h3_font_weight', function( value ) {
      value.bind( function( to ) {
          $( 'h3' ).css( {
                  'font-weight': to
          } );
      } );
  });
  wp.customize( 'whitedot_sidebar_heading_font_size', function( value ) {
      value.bind( function( to ) {
          $( '.wd-widget-heading' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_sidebar_heading_font_weight', function( value ) {
      value.bind( function( to ) {
          $( '.wd-widget-heading' ).css( {
                  'font-weight': to
          } );
      } );
  });
  wp.customize( 'whitedot_footer_sitetitle_font_size', function( value ) {
      value.bind( function( to ) {
          $( '.wd-footer-title' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_footer_sitetitle_font_weight', function( value ) {
      value.bind( function( to ) {
          $( '.wd-footer-title' ).css( {
                  'font-weight': to
          } );
      } );
  });
  wp.customize( 'whitedot_footer_sitetag_font_size', function( value ) {
      value.bind( function( to ) {
          $( '.footer-site-description' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_footer_sitetag_font_weight', function( value ) {
      value.bind( function( to ) {
          $( '.footer-site-description' ).css( {
                  'font-weight': to
          } );
      } );
  });
  wp.customize( 'whitedot_footer_widget_heading_font_size', function( value ) {
      value.bind( function( to ) {
          $( '.wd-footer-widget-heading' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_footer_widget_heading_font_weight', function( value ) {
      value.bind( function( to ) {
          $( '.wd-footer-widget-heading' ).css( {
                  'font-weight': to
          } );
      } );
  });
  wp.customize( 'whitedot_footer_widget_text_font_size', function( value ) {
      value.bind( function( to ) {
          $( '.wd-footer-widget' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_footer_copyright_font_size', function( value ) {
      value.bind( function( to ) {
          $( '.footer-credit' ).css( {
                  'font-size': to + 'px'
          } );
      } );
  });
  wp.customize( 'whitedot_footer_copyright_font_weight', function( value ) {
      value.bind( function( to ) {
          $( '.footer-credit' ).css( {
                  'font-weight': to
          } );
      } );
  });



    //Footer Credit
  wp.customize( 'whitedot_custom_credit', function( value ) {
    value.bind( function( to ) {
      $( '.footer-credit' ).text( to );
    } );
  } );

    //Outer Container Width
  wp.customize( 'whitedot_outer_container_width', function( value ) {
    value.bind( function( to ) {
      $( '.col-full, .custom-col-full' ).css( {
                    'max-width': to + 'px'
            } );
    } );
  } );

    //Sidebar Width
  wp.customize( 'whitedot_blog_archive_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.has-sidebar.blog .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.has-sidebar.blog #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_single_blog_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.has-sidebar.single-post .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.has-sidebar.single-post #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_page_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.has-sidebar.page-template-default .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.has-sidebar.page-template-default #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_woo_shop_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.has-sidebar.post-type-archive-product .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.has-sidebar.post-type-archive-product #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_woo_single_product_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.has-sidebar.single-product .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.has-sidebar.single-product #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_woo_cart_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.has-sidebar.woocommerce-cart .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.has-sidebar.woocommerce-cart #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_woo_checkout_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.has-sidebar.woocommerce-checkout .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.has-sidebar.woocommerce-checkout #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_course_catalog_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.post-type-archive-course.has-sidebar .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.post-type-archive-course.has-sidebar #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_membership_catalog_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.post-type-archive-llms_membership.has-sidebar .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.post-type-archive-llms_membership.has-sidebar #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_single_course_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.single-course.has-sidebar .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.single-course.has-sidebar #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_single_lesson_sidebar_width', function( value ) {
    value.bind( function( to ) {
      $( '.single-lesson.has-sidebar .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.single-lesson.has-sidebar #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );
  wp.customize( 'whitedot_single_membership_sidebar_layout', function( value ) {
    value.bind( function( to ) {
      $( '.single-llms_membership.has-sidebar .secondary' ).css( {
                    'width': to + '%'
            } );
      $( '.single-llms_membership.has-sidebar #primary' ).css( {
                    'width': 100 - to + '%'
            } );
    } );
  } );

    //Single Blog Inner Width
  wp.customize( 'whitedot_blog_inner_container_width', function( value ) {
    value.bind( function( to ) {
      $( '.single-post .wd-post-content' ).css( {
                    'padding-left': to + 'px'
            } );
      $( '.single-post .wd-post-content' ).css( {
                    'padding-right': to + 'px'
            } );
      $( '.is-boxed .alignfull' ).css( {
                    'margin': '0 -' + to + 'px'
            } );
    } );
  } );

  wp.customize( 'whitedot_single_post_title_alignment', function( value ) {
      value.bind( function( to ) {
          $( '.wd-post-content .wd-post-title.hero-img-exist, .wd-post-content .wd-post-title' ).css( {
                  'text-align': to
          } );
      } );
  });

  wp.customize( 'whitedot_single_post_metadata_alignment', function( value ) {
      value.bind( function( to ) {
          $( '.wd-post-content .single-excerpt-meta' ).css( {
                  'text-align': to
          } );
      } );
  });

    //Single Blog Hero Banner Overlay Opacity
  wp.customize( 'whitedot_single_post_hero_overlay_opacity', function( value ) {
      value.bind( function( to ) {
          $( '.hero-thumb-overlay' ).css( {
                  'opacity': to / 10
          } );
      } );
  });

    //Single Blog Hero Banner Overlay Text Color
  wp.customize( 'whitedot_single_post_hero_text_color', function( value ) {
      value.bind( function( to ) {
          $( '.whitedot-single-post-hero-thumbnail-content .wd-post-title, .whitedot-single-post-hero-thumbnail-content .single-excerpt-meta, .whitedot-single-post-hero-thumbnail-content .single-category-meta li a, .whitedot-single-post-hero-thumbnail-content .excerpt-meta, .whitedot-single-post-hero-thumbnail-content .author a, .whitedot-single-post-hero-thumbnail-content .entry-date, .whitedot-single-post-hero-thumbnail-content .wd-author a' ).css( {
                  'color': to
          } );
      } );
  });

    //Boxed Page Inner Width
  wp.customize( 'whitedot_page_inner_container_width', function( value ) {
    value.bind( function( to ) {
      $( '.page-template-template-no-sidebar-boxed .boxed-layout, .page-template-template-left-sidebar-boxed .boxed-layout, .page-template-template-right-sidebar-boxed .boxed-layout' ).css( {
                    'padding-left': to + '%'
            } );
      $( '.page-template-template-no-sidebar-boxed .boxed-layout, .page-template-template-left-sidebar-boxed .boxed-layout, .page-template-template-right-sidebar-boxed .boxed-layout' ).css( {
                    'padding-right': to + '%'
            } );
    } );
  } );
	
} )( jQuery );
