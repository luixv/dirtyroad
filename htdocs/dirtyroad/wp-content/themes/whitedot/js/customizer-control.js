/**
 * File customizer-controls.js.
 *
 * Handles the Controls inside the Customizer panel.
 *
 */

(function( $ ) {
    wp.customize.bind( 'ready', function() {

    var customize = this; // Customize object alias.
    customize( 'whitedot_header_calltoaction_toggle', function( value ) {
 

        var Controls = [
            'whitedot_header_calltoaction_text',
            'whitedot_header_calltoaction_url',
            'whitedot_header_calltoaction_visibility'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_theme_button_border_toggle', function( value ) {
 

        var Controls = [
            'whitedot_theme_button_border_color',
            'whitedot_theme_button_top_border',
            'whitedot_theme_button_left_border',
            'whitedot_theme_button_right_border',
            'whitedot_theme_button_bottom_border',
            'whitedot_theme_button_hover_border_color',
            'whitedot_theme_button_hover_top_border',
            'whitedot_theme_button_hover_left_border',
            'whitedot_theme_button_hover_right_border',
            'whitedot_theme_button_hover_bottom_border'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_header_button_border_toggle', function( value ) {
 

        var Controls = [
            'whitedot_header_button_border_color',
            'whitedot_header_button_top_border',
            'whitedot_header_button_left_border',
            'whitedot_header_button_right_border',
            'whitedot_header_button_bottom_border',
            'whitedot_header_button_hover_border_color',
            'whitedot_header_button_hover_top_border',
            'whitedot_header_button_hover_left_border',
            'whitedot_header_button_hover_right_border',
            'whitedot_header_button_hover_bottom_border'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_trans_header_button_border_toggle', function( value ) {
 

        var Controls = [
            'whitedot_trans_header_button_border_color',
            'whitedot_trans_header_button_top_border',
            'whitedot_trans_header_button_left_border',
            'whitedot_trans_header_button_right_border',
            'whitedot_trans_header_button_bottom_border',
            'whitedot_trans_header_button_hover_border_color',
            'whitedot_trans_header_button_hover_top_border',
            'whitedot_trans_header_button_hover_left_border',
            'whitedot_trans_header_button_hover_right_border',
            'whitedot_trans_header_button_hover_bottom_border'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );
 
    var customize = this;
    customize( 'whitedot_show_product_filter', function( value ) {
 

        var Controls = [
            'whitedot_woo_shop_filter_layout'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this;
    customize( 'whitedot_show_footer_branding', function( value ) {
 

        var Controls = [
            'whitedot_show_footer_social_icons'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_social_facebook', function( value ) {
 

        var Controls = [
            'wd_facebook_url'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_social_twitter', function( value ) {
 

        var Controls = [
            'wd_twitter_url'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_social_instagram', function( value ) {
 

        var Controls = [
            'wd_instagram_url'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_social_google', function( value ) {
 

        var Controls = [
            'wd_google_url'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_social_pintrest', function( value ) {
 

        var Controls = [
            'wd_pintrest_url'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_social_youtube', function( value ) {
 

        var Controls = [
            'wd_youtube_url'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_footer_credit_custom_text', function( value ) {
 

        var Controls = [
            'whitedot_custom_credit'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_footer_credit_custom_text', function( value ) {
 

        var Controls = [
            'whitedot_custom_credit'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_single_post_hero_thumbnail', function( value ) {
 

        var Controls = [
            'single_post_title_in_hero_banner',
            'single_post_transparent_header',
            'single_post_data_in_hero_banner',
            'whitedot_single_post_hero_style',
            'whitedot_single_post_hero_overlay_color',
            'whitedot_single_post_hero_overlay_opacity',
            'whitedot_single_post_hero_text_color',
            'single_hero_data_hide_author',
            'single_hero_data_hide_date',
            'single_hero_data_hide_category'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

    var customize = this; // Customize object alias.
    customize( 'whitedot_sticky_transparent_header', function( value ) {
 

        var Controls = [
            'transparent_sticky_header_color',
            'transparent_sticky_header_opacity',
            'temp_sticky_header_text_color',
            'temp_sticky_header_nav_link_hover_color'
        ];

        $.each( Controls, function( index, id ) {
            customize.control( id, function( control ) {
                /**
                 * Toggling function
                 */
                var toggle = function( to ) {
                    control.toggle( to );
                };
 
                // 1. On loading.
                toggle( value.get() );
 
                // 2. On value change.
                value.bind( toggle );
            } );
        } );

    } );

} );
})( jQuery );