/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function ($) {
    // Site title and description.
    wp.customize('blogname', function (value) {
        value.bind(function (to) {
            $('.sq-site-title a').text(to);
        });
    });
    wp.customize('blogdescription', function (value) {
        value.bind(function (to) {
            $('.sq-site-description').text(to);
        });
    });
    // Header text color.
    wp.customize('header_textcolor', function (value) {
        value.bind(function (to) {
            if ('blank' === to) {
                $('.sq-site-title a, .sq-site-description').css({
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.sq-site-title a, .sq-site-description').css({
                    'clip': 'auto',
                    'color': to,
                    'position': 'relative'
                });
            }
        });
    });

    // Header background color
    wp.customize('square_header_bg', function (value) {
        value.bind(function (to) {
            if ('sq-white' === to) {
                $('#sq-masthead').addClass('sq-white');
            } else {
                $('#sq-masthead').removeClass('sq-white');
            }
        });
    });

    // Slider Caption Text
    wp.customize('square_slider_title1', function (value) {
        value.bind(function (to) {
            $('.sq-slide-count1 .sq-slide-cap-title').text(to);
        });
    });

    wp.customize('square_slider_title2', function (value) {
        value.bind(function (to) {
            $('.sq-slide-count2 .sq-slide-cap-title').text(to);
        });
    });

    wp.customize('square_slider_title3', function (value) {
        value.bind(function (to) {
            $('.sq-slide-count3 .sq-slide-cap-title').text(to);
        });
    });

    wp.customize('square_slider_subtitle1', function (value) {
        value.bind(function (to) {
            $('.sq-slide-count1 .sq-slide-cap-desc').text(to);
        });
    });

    wp.customize('square_slider_subtitle2', function (value) {
        value.bind(function (to) {
            $('.sq-slide-count2 .sq-slide-cap-desc').text(to);
        });
    });

    wp.customize('square_slider_subtitle3', function (value) {
        value.bind(function (to) {
            $('.sq-slide-count3 .sq-slide-cap-desc').text(to);
        });
    });

    // Featured Post Icons
    wp.customize('square_featured_page_icon1', function (value) {
        value.bind(function (to) {
            $('.sq-featured-post1 i').removeClass().addClass(to);
        });
    });

    wp.customize('square_featured_page_icon2', function (value) {
        value.bind(function (to) {
            $('.sq-featured-post2 i').removeClass().addClass(to);
        });
    });

    wp.customize('square_featured_page_icon3', function (value) {
        value.bind(function (to) {
            $('.sq-featured-post3 i').removeClass().addClass(to);
        });
    });

    wp.customize('square_tab_title1', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list1 span').text(to);
        });
    });

    wp.customize('square_tab_title2', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list2 span').text(to);
        });
    });

    wp.customize('square_tab_title3', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list3 span').text(to);
        });
    });

    wp.customize('square_tab_title4', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list4 span').text(to);
        });
    });

    wp.customize('square_tab_title5', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list5 span').text(to);
        });
    });

    wp.customize('square_tab_icon1', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list1 i').removeClass().addClass(to);
        });
    });

    wp.customize('square_tab_icon2', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list2 i').removeClass().addClass(to);
        });
    });

    wp.customize('square_tab_icon3', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list3 i').removeClass().addClass(to);
        });
    });

    wp.customize('square_tab_icon4', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list4 i').removeClass().addClass(to);
        });
    });

    wp.customize('square_tab_icon5', function (value) {
        value.bind(function (to) {
            $('.sq-tab-list5 i').removeClass().addClass(to);
        });
    });

    wp.customize('square_logo_title', function (value) {
        value.bind(function (to) {
            $('#sq-logo-section .sq-section-title').text(to);
        });
    });

    wp.customize('square_social_facebook', function (value) {
        value.bind(function (to) {
            $('.sq-facebook').attr('href', to);
        });
    });

    wp.customize('square_social_twitter', function (value) {
        value.bind(function (to) {
            $('.sq-twitter').attr('href', to);
        });
    });

    wp.customize('square_social_pinterest', function (value) {
        value.bind(function (to) {
            $('.sq-pinterest').attr('href', to);
        });
    });

    wp.customize('square_social_youtube', function (value) {
        value.bind(function (to) {
            $('.sq-youtube').attr('href', to);
        });
    });

    wp.customize('square_social_linkedin', function (value) {
        value.bind(function (to) {
            $('.sq-linkedin').attr('href', to);
        });
    });

})(jQuery);
