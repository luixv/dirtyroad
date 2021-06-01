<?php

class Youzify_Styling {

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    /**
     * Return the instance of this class.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    function __construct() {

        // Add Filters
        add_action( 'wp_enqueue_scripts', array( $this, 'custom_scheme' ) );

        // Call Global Styling.
        // add_action( 'wp_enqueue_scripts', array( $this, 'global_styles' ) );

    }

    /**
     * Get All Styles
     */
    function get_all_styles( $query = null ) {

        // Get Styles
        $styles = array_merge(
            $this->posts_tab_styling(),
            $this->comments_tab_styling(),
            $this->group_styling(),
            $this->profile_styling(),
            $this->profile404_styling(),
            $this->global_styling()
        );

        if ( $query == 'ids' ) {

            // Get Styles Ids.
            $styles = wp_list_pluck( $styles, 'id' );

            foreach ( $this->get_gradient_elements( array(), true ) as $style ) {
                $styles[] = $style['left_color'];
                $styles[] = $style['right_color'];
            }

        }

        return $styles;
    }

    /**
     * Posts Tab Styling
     */
    function posts_tab_styling() {

        $data = array(
            array(
                'id'        =>  'youzify_post_title_color',
                'selector'  =>  '.youzify-tab-post .youzify-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_post_meta_color',
                'selector'  =>  '.youzify-tab-post .youzify-post-meta ul li, .youzify-tab-post .youzify-post-meta ul li a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_post_meta_icons_color',
                'selector'  =>  '.youzify-tab-post .youzify-post-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_post_text_color',
                'selector'  =>  '.youzify-tab-post .youzify-post-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_post_button_color',
                'selector'  =>  '.youzify-tab-post .youzify-read-more',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_post_button_text_color',
                'selector'  =>  '.youzify-tab-post .youzify-read-more',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_post_button_icon_color',
                'selector'  =>  '.youzify-tab-post .youzify-rm-icon i',
                'property'  =>  'color'
            )
        );

        return $data;
    }

    /**
     * Comments Tab Styling
     */
    function comments_tab_styling() {

        $data = array(
            array(
                'id'        =>  'youzify_comment_author_color',
                'selector'  =>  '.youzify-tab-comment .youzify-comment-fullname',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_comment_username_color',
                'selector'  =>  '.youzify-tab-comment .youzify-comment-author',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_comment_date_color',
                'selector'  =>  '.youzify-tab-comment .youzify-comment-date',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_comment_text_color',
                'selector'  =>  '.youzify-tab-comment .youzify-comment-excerpt p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_comment_button_bg_color',
                'selector'  =>  '.youzify-tab-comment .view-comment-button',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_comment_button_text_color',
                'selector'  =>  '.youzify-tab-comment .view-comment-button',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_comment_button_icon_color',
                'selector'  =>  '.youzify-tab-comment .view-comment-button i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_comment_author_border_color',
                'selector'  =>  '.youzify-tab-comment .youzify-comment-img',
                'property'  =>  'border-color'
            )
        );

        return $data;

    }

    /**
     * Global Styling
     */
    function global_styling() {

        $data = array(
            array(
                'id'        =>  'youzify_plugin_content_width',
                'selector'  =>  '.youzify-hdr-v1 .youzify-cover-content .youzify-inner-content,
                                #youzify-profile-navmenu .youzify-inner-content,
                                .youzify-vertical-layout .youzify-content,
                                .youzify .youzify-boxed-navbar,
                                .youzify .wild-content,
                                #youzify-members-directory,
                                #youzify-groups-list,
                                .youzify-page-main-content,
                                .youzify-header-content,
                                .youzify-cover-content',
                'property'  =>  'max-width',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'youzify_plugin_background',
                'selector'  =>  '.youzify-page',
                'property'  =>  'background-color'
            ),
            // Spacing Styles
            array(
                'id'        =>  'youzify_plugin_margin_top',
                'selector'  =>  '.youzify-page',
                'property'  =>  'margin-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'youzify_plugin_margin_bottom',
                'selector'  =>  '.youzify-page',
                'property'  =>  'margin-bottom',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'youzify_plugin_padding_top',
                'selector'  =>  '.youzify-page',
                'property'  =>  'padding-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'youzify_plugin_padding_bottom',
                'selector'  =>  '.youzify-page',
                'property'  =>  'padding-bottom',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'youzify_plugin_padding_left',
                'selector'  =>  '.youzify-page',
                'property'  =>  'padding-left',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'youzify_plugin_padding_right',
                'selector'  =>  '.youzify-page',
                'property'  =>  'padding-right',
                'unit'      => 'px'
            ),
            // Auhtor Box Styling .
            array(
                'id'        =>  'youzify_author_pattern_opacity',
                'selector'  =>  '.youzify-author.youzify-header-pattern .youzify-header-cover:after',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'youzify_author_overlay_opacity',
                'selector'  =>  '.youzify-author.youzify-header-overlay .youzify-header-cover:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'youzify_author_box_margin_top',
                'selector'  =>  '.youzify-author-box-widget',
                'property'  =>  'margin-top',
                'unit'      =>  'px'
            ),
            array(
                'id'        =>  'youzify_author_box_margin_bottom',
                'selector'  =>  '.youzify-author-box-widget',
                'property'  =>  'margin-bottom',
                'unit'      =>  'px'
            )
        );

        return $data;
    }

    /**
     * Profile 404 Styling
     */
    function profile404_styling() {

        $data = array(
            array(
                'id'        =>  'youzify_profile_404_title_color',
                'selector'  =>  '.youzify-box-404 h2',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_profile_404_desc_color',
                'selector'  =>  '.youzify-box-404 p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_profile_404_button_txt_color',
                'selector'  =>  '.youzify-box-404 .youzify-box-button',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_profile_404_button_bg_color',
                'selector'  =>  '.youzify-box-404 .youzify-box-button',
                'property'  =>  'background-color'
            )
        );

        return $data;
    }

    /**
     * Group Styling
     */
    function group_styling() {

        $data = array(
            array(
                'id'        =>  'youzify_group_header_bg_color',
                'selector'  =>  '.youzify-group .youzify-header-overlay .youzify-header-cover:before',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_group_header_username_color',
                'selector'  =>  '.youzify-group .youzify-name h2,.youzify-profile .youzify-head-content h2',
                'property'  =>  'color'
            ),array(
                'id'        =>  'youzify_group_header_text_color',
                'selector'  =>  '.youzify-group .youzify-usermeta li span, .youzify-profile .youzify-head-meta',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_group_header_icons_color',
                'selector'  =>  '.youzify-group .youzify-usermeta li i, .youzify-profile .youzify-head-meta i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_group_header_statistics_nbr_color',
                'selector'  =>  '.youzify-group .youzify-user-statistics ul li .youzify-snumber',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_group_header_statistics_title_color',
                'selector'  =>  '.youzify-group .youzify-user-statistics .youzify-sdescription',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_group_header_overlay_opacity',
                'selector'  =>  '.youzify-group .youzify-profile-header.youzify-header-overlay .youzify-header-cover:before,' .
                                '.youzify-group .youzify-hdr-v3 .youzify-inner-content:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'youzify_group_header_pattern_opacity',
                'selector'  =>  '.youzify-group .youzify-profile-header.youzify-header-pattern .youzify-header-cover:after,
                                 .youzify-group .youzify-hdr-v3 .youzify-inner-content:after',
                'property'  =>  'opacity'
            )
        );

        return $data;
    }

    /**
     * Styling Data.
     */
    function profile_styling() {

        $data = array(
            // Profile Header Styling,
            array(
                'id'        =>  'youzify_profile_header_bg_color',
                'selector'  =>  '.youzify-profile .youzify-header-overlay .youzify-header-cover:before',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_profile_header_username_color',
                'selector'  =>  '.youzify-profile .youzify-name h2,.youzify-profile .youzify-head-content h2',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_profile_header_text_color',
                'selector'  =>  '.youzify-profile .youzify-usermeta li span, .youzify-profile .youzify-head-meta',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_profile_header_icons_color',
                'selector'  =>  '.youzify-profile .youzify-usermeta li i, .youzify-profile .youzify-head-meta i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_profile_header_statistics_nbr_color',
                'selector'  =>  '.youzify-profile .youzify-user-statistics ul li .youzify-snumber',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_profile_header_statistics_title_color',
                'selector'  =>  '.youzify-profile .youzify-user-statistics .youzify-sdescription',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_profile_header_overlay_opacity',
                'selector'  =>  '.youzify-profile .youzify-profile-header.youzify-header-overlay .youzify-header-cover:before,' .
                                '.youzify-profile .youzify-hdr-v3 .youzify-inner-content:before',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'youzify_profile_header_pattern_opacity',
                'selector'  =>  '.youzify-profile .youzify-profile-header.youzify-header-pattern .youzify-header-cover:after,
                                 .youzify-profile .youzify-hdr-v3 .youzify-inner-content:after',
                'property'  =>  'opacity'
            ),
            array(
                'id'        =>  'youzify_navbar_bg_color',
                'selector'  =>  '#youzify-profile-navmenu',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_navbar_icons_color',
                'selector'  =>  '.youzify .youzify-profile-navmenu li i,' .
                                '.youzify-profile .youzify-nav-settings .youzify-settings-icon',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_navbar_icons_color',
                'selector'  =>  '.youzify-profile .youzify-responsive-menu span::before,' .
                                '.youzify-profile .youzify-responsive-menu span::after,' .
                                '.youzify-profile .youzify-responsive-menu span',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_navbar_links_color',
                'selector'  =>  '.youzify .youzify-profile-navmenu a,.youzify-profile .youzify-settings-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_navbar_links_hover_color',
                'selector'  =>  '.youzify .youzify-profile-navmenu a:hover',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_navbar_menu_border_color',
                'selector'  =>  '.youzify .youzify-profile-navmenu .youzify-navbar-item.youzify-active-menu',
                'property'  =>  'border-color'
            ),
            // Pagination Tab Styling .
            array(
                'id'        =>  'youzify_paginationbg_color',
                'selector'  =>  '.youzify-pagination .page-numbers,.youzify-pagination .youzify-pagination-pages',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_pagination_text_color',
               'selector'  =>  '.youzify-pagination .page-numbers,.youzify-pagination .youzify-pagination-pages',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_paginationcurrent_bg_color',
                'selector'  =>  '.youzify-pagination .page-numbers.current',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_pagination_current_text_color',
                'selector'  =>  '.youzify-pagination .current .youzify-page-nbr',
                'property'  =>  'color'
            ),
            // Widgets Styling .
            array(
                'id'        =>  'youzify_wgs_title_bg',
                'selector'  =>  '.youzify-widget .youzify-widget-head',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wgs_title_border_color',
                'selector'  =>  '.youzify-widget .youzify-widget-head',
                'property'  =>  'border-color'
            ),
            array(
                'id'        =>  'youzify_wgs_title_color',
                'selector'  =>  '.youzify-widget .youzify-widget-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wgs_title_icon_color',
                'selector'  =>  '.youzify-widget-title i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wgs_title_icon_bg',
                'selector'  =>  '.youzify-wg-title-icon-bg .youzify-widget-title i',
                'property'  =>  'background-color'
            ),
            // Widget - About Me - Styling .
            array(
                'id'        =>  'youzify_wg_aboutme_title_color',
                'selector'  =>  '.youzify-aboutme-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_aboutme_desc_color',
                'selector'  =>  '.youzify-aboutme-description',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_aboutme_txt_color',
                'selector'  =>  '.youzify-aboutme-bio',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_aboutme_head_border_color',
                'selector'  =>  '.youzify-aboutme-head:after',
                'property'  =>  'background-color'
            ),
            // Widget - Project - Styling .
            array(
                'id'        =>  'youzify_wg_project_color',
                'selector'  =>  '.youzify-project-container',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_project_type_bg_color',
                'selector'  =>  '.youzify-project-content .youzify-project-type',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_project_type_txt_color',
                'selector'  =>  '.youzify-project-content .youzify-project-type',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_project_title_color',
                'selector'  =>  '.youzify-project-content .youzify-project-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_project_meta_txt_color',
                'selector'  =>  '.youzify-project-content .youzify-project-meta ul li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_project_meta_icon_color',
                'selector'  =>  '.youzify-project-content .youzify-project-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_project_desc_color',
                'selector'  =>  '.youzify-project-content .youzify-project-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_project_tags_color',
                'selector'  =>  '.youzify-project-content .youzify-project-tags li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_project_tags_bg_color',
                'selector'  =>  '.youzify-project-content .youzify-project-tags li',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_project_tags_hashtag_color',
                'selector'  =>  '.youzify-project-content .youzify-project-tags .youzify-tag-symbole',
                'property'  =>  'color'
            ),
            // Widget - User Tags - Styling .
            array(
                'id'        =>  'youzify_wg_user_tags_title_color',
                'selector'  =>  '.youzify-widget .youzify-user-tags .youzify-utag-name',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_user_tags_icon_color',
                'selector'  =>  '.youzify-widget .youzify-user-tags .youzify-utag-name i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_user_tags_desc_color',
                'selector'  =>  '.youzify-widget .youzify-user-tags .youzify-utag-description',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_user_tags_background',
                'selector'  =>  '.youzify-widget .youzify-user-tags .youzify-utag-values .youzify-utag-value-item',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_user_tags_color',
                'selector'  =>  '.youzify-widget .youzify-user-tags .youzify-utag-values .youzify-utag-value-item,
                .youzify-widget .youzify-user-tags .youzify-utag-values .youzify-utag-value-item a',
                'property'  =>  'color'
            ),
            // Widget - Post - Styling .
            array(
                'id'        =>  'youzify_wg_post_type_bg_color',
                'selector'  =>  '.youzify-post-content .youzify-post-type',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_post_type_txt_color',
                'selector'  =>  '.youzify-post-content .youzify-post-type',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_title_color',
                'selector'  =>  '.youzify-post-content .youzify-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_meta_txt_color',
                'selector'  =>  '.youzify-post-content .youzify-post-meta ul li',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_meta_icon_color',
                'selector'  =>  '.youzify-post-content .youzify-post-meta ul li i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_text_color',
                'selector'  =>  '.youzify-post-content .youzify-post-text p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_tags_color',
                'selector'  =>  '.youzify-post-content .youzify-post-tags li a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_tags_bg_color',
                'selector'  =>  '.youzify-post-content .youzify-post-tags li',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_post_tags_hashtag_color',
                'selector'  =>  '.youzify-post-content .youzify-post-tags .youzify-tag-symbole',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_rm_color',
                'selector'  =>  '.youzify-post .youzify-read-more',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_rm_icon_color',
                'selector'  =>  '.youzify-post .youzify-read-more i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_post_rm_bg_color',
                'selector'  =>  '.youzify-post .youzify-read-more',
                'property'  =>  'background-color'
            ),
            // Widget - Services - Styling .
            array(
                'id'        =>  'youzify_wg_service_icon_bg_color',
                'selector'  =>  '.youzify-service-item .youzify-service-icon i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_service_icon_color',
                'selector'  =>  '.youzify-service-item .youzify-service-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_service_title_color',
                'selector'  =>  '.youzify-service-item .youzify-item-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_service_text_color',
                'selector'  =>  '.youzify-service-item .youzify-item-content p',
                'property'  =>  'color'
            ),
            // Widget - Portfolio - Styling .
            array(
                'id'        =>  'youzify_wg_portfolio_title_border_color',
                'selector'  =>  '.youzify-portfolio .youzify-portfolio-content figcaption h3:after',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_portfolio_button_color',
                'selector'  =>  '.youzify-portfolio .youzify-portfolio-content figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_portfolio_button_txt_color',
                'selector'  =>  '.youzify-portfolio .youzify-portfolio-content figcaption a i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_portfolio_button_hov_color',
                'selector'  =>  '.youzify-portfolio .youzify-portfolio-content figcaption a i:hover',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_portfolio_button_txt_hov_color',
                'selector'  =>  '.youzify-portfolio .youzify-portfolio-content figcaption a i:hover',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_portfolio_button_border_hov_color',
                'selector'  =>  '.youzify-portfolio .youzify-portfolio-content figcaption a:hover',
                'property'  =>  'border-color'
            ),
            // Widget - Instagram - Styling .
            array(
                'id'        =>  'youzify_wg_instagram_img_icon_bg_color',
                'selector'  =>  '.youzify-instagram .youzify-portfolio-content figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_instagram_img_icon_color',
                'selector'  =>  '.youzify-instagram .youzify-portfolio-content figcaption a i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_instagram_img_icon_bg_color_hover',
                'selector'  =>  '.youzify-instagram .youzify-portfolio-content figcaption a i:hover',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_instagram_img_icon_color_hover',
                'selector'  =>  '.youzify-instagram .youzify-portfolio-content figcaption a i:hover',
                'property'  =>  'color'
            ),
            // Widget - Flickr - Styling .
            array(
                'id'        =>  'youzify_wg_flickr_img_bg_color',
                'selector'  =>  '.youzify-flickr-photos figcaption',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_flickr_img_icon_bg_color',
                'selector'  =>  '.youzify-flickr-photos figcaption a i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_flickr_img_icon_color',
                'selector'  =>  '.youzify-flickr-photos figcaption a i',
                'property'  =>  'color'
            ),
            // Widget - Recent Posts - Styling .
            array(
                'id'        =>  'youzify_wg_rposts_title_color',
                'selector'  =>  '.youzify-recent-posts .youzify-post-head .youzify-post-title a',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_rposts_date_color',
                'selector'  =>  '.youzify-recent-posts .youzify-post-meta ul li',
                'property'  =>  'color'
            ),
            // Widget - infos - Styling .
            array(
                'id'        =>  'youzify_infos_wg_title_color',
                'selector'  =>  '.youzify .youzify-infos-content .youzify-info-label',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_infos_wg_value_color',
                'selector'  =>  '.youzify .youzify-infos-content .youzify-info-data',
                'property'  =>  'color'
            ),
            // Widget - Quote - Styling .
            array(
                'id'        =>  'youzify_wg_quote_content_bg',
                'selector'  =>  '.youzify .youzify-quote-main-content',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_quote_icon_bg',
                'selector'  =>  '.youzify .youzify-quote-icon',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_quote_txt',
                'selector'  =>  '.youzify .youzify-quote-main-content blockquote',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_quote_icon',
                'selector'  =>  '.youzify .youzify-quote-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_quote_owner',
                'selector'  =>  '.youzify .youzify-quote-owner',
                'property'  =>  'color'
            // Widget - Link - Styling .
            ),
            array(
                'id'        =>  'youzify_wg_link_content_bg',
                'selector'  =>  '.youzify .youzify-link-main-content',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_link_icon_bg',
                'selector'  =>  '.youzify .youzify-link-icon i',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_link_txt',
                'selector'  =>  '.youzify .youzify-link-main-content p',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_link_icon',
                'selector'  =>  '.youzify .youzify-link-icon i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_link_url',
                'selector'  =>  '.youzify .youzify-link-url',
                'property'  =>  'color'
            ),
            // Widget - Video - Styling .
            array(
                'id'        =>  'youzify_wg_video_title_color',
                'selector'  =>  '.youzify .youzify-video-head .youzify-video-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_wg_video_desc_color',
                'selector'  =>  '.youzify .youzify-video-head .youzify-video-desc',
                'property'  =>  'color'
            ),
            // Scroll to top Styling .
            array(
                'id'        =>  'youzify_scroll_button_color',
                'selector'  =>  '.youzify-scrolltotop i:hover',
                'property'  =>  'background-color'
            ),
            // Widget Slideshow .
            array(
                'id'        =>  'youzify_wg_slideshow_pagination_color',
                'selector'  =>  '.youzify .owl-theme .owl-controls .owl-page span',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_wg_slideshow_np_color',
                'selector'  =>  '.youzify .owl-buttons div::before, .owl-buttons div::after',
                'property'  =>  'background-color'
            ),
            // Author Box Widget
            array(
                'id'        =>  'youzify_abox_button_icon_color',
                'selector'  =>  '.youzify-author-box-widget .youzify-button i',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_abox_button_txt_color',
                'selector'  =>  '.youzify-author-box-widget .youzify-button .youzify-button-title',
                'property'  =>  'color'
            ),
            array(
                'id'        =>  'youzify_abox_button_bg_color',
                'selector'  =>  '.youzify-author-box-widget .youzify-button',
                'property'  =>  'background-color'
            ),
            // Verified accounts
            array(
                'id'        =>  'youzify_verified_badge_background_color',
                'selector'  =>  '.youzify-account-verified',
                'property'  =>  'background-color'
            ),
            array(
                'id'        =>  'youzify_verified_badge_icon_color',
                'selector'  =>  '.youzify-account-verified',
                'property'  =>  'color'
            )
        );

        return $data;
    }

    /**
     * Custom Styling.
     */
    function custom_styling( $component = 'global' ) {

        // Get Active Styles.
        $active_styles = youzify_option( 'youzify_active_styles' );

        if ( empty( $active_styles ) ) {
            return;
        }

        if ( ! empty( $component ) ) {

            switch ( $component ) {

                case 'global':
                    $page_styles = $this->global_styling();
                    break;

                case 'posts':
                    $page_styles = $this->posts_tab_styling();
                    break;

                case 'comments':
                    $page_styles = $this->comments_tab_styling();
                    break;

                case 'profile':
                    $page_styles = $this->profile_styling();
                    break;

                case 'groups':
                    $page_styles = $this->group_styling();
                    break;

                case '404_profile':
                    $page_styles = $this->profile404_styling();
                    break;

                default:
                    break;
            }

        }

        foreach ( $page_styles as $key => $data ) {
            if ( ! in_array( $data['id'], $active_styles ) ) {
                unset( $page_styles[ $key ] );
            }
        }

        if ( empty( $page_styles ) ) {
            return;
        }

        // Custom Styling File.
        wp_enqueue_style( 'youzify-customStyle', YOUZIFY_ADMIN_ASSETS . 'css/custom-script.css' );

        // Print Styles
        foreach ( $page_styles as $key ) {

            // Get Data.
            $selector = $key['selector'];
            $property = $key['property'];

            $option = youzify_option( $key['id'] );
            $option = isset( $option['color'] ) ? $option['color'] : $option;
            if ( empty( $key['type'] ) && ! empty( $option ) ) {
                $unit = isset( $key['unit'] ) ? $key['unit'] : null;
                $custom_css = "
                    $selector {
                	$property: $option$unit !important;
                    }";
                wp_add_inline_style( 'youzify-customStyle', $custom_css );
            }
        }
    }

    /**
     * Custom Scheme.
     */
    function custom_scheme() {

        // Check if is using a custom scheme is enabled.
        if ( 'on' != youzify_option( 'youzify_enable_profile_custom_scheme', 'off' ) ) {
            return;
        }

        // Get Custom Scheme Color
        $scheme_color = youzify_option( 'youzify_profile_custom_scheme_color' );

        if ( empty( $scheme_color ) )  {
            return;
        }

        $scheme_color = $scheme_color['color'];


        // Custom Styling File.
        wp_enqueue_style( 'youzify-customStyle', YOUZIFY_ADMIN_ASSETS . 'css/custom-script.css' );

        $pattern = 'url(' . YOUZIFY_ASSETS . 'images/dotted-bg.png)';

        $custom_css = "
.youzify div.item-list-tabs li.youzify-activity-show-search .youzify-activity-show-search-form i,
#youzify-wall-nav .item-list-tabs li#activity-filter-select label,
.youzify-media-filter .youzify-filter-item .youzify-current-filter,
.youzify-community-hashtags .youzify-hashtag-item:hover,
.youzify table tfoot tr,
.youzify table thead tr,
#youzify-group-body h1:before,
.youzify-product-actions .youzify-addtocart,
.youzify .checkout_coupon,
.youzify .youzify-wc-box-title h3,
.youzify .woocommerce-customer-details h2,
.youzify .youzify-wc-main-content .track_order .form-row button,
.youzify-view-order .youzify-wc-main-content > p mark.order-status,
.youzify .youzify-wc-main-content button[type='submit'],
.youzify .youzify-wc-main-content #payment #place_order,
.youzify .youzify-wc-main-content h3,
.youzify .wc-proceed-to-checkout a.checkout-button,
.youzify .wc-proceed-to-checkout a.checkout-button:hover,
.youzify .youzify-wc-main-content .woocommerce-checkout-review-order table.shop_table tfoot .order-total,
.youzify .youzify-wc-main-content .woocommerce-checkout-review-order table.shop_table thead,
.youzify .youzify-wc-main-content table.shop_table td a.woocommerce-MyAccount-downloads-file:before,
.youzify .youzify-wc-main-content table.shop_table td a.view:before,
.youzify table.shop_table.order_details tfoot tr:last-child,
.youzify .youzify-wc-main-content table.shop_table td.actions .coupon button,
.youzify .youzify-wc-main-content table.shop_table td.woocommerce-orders-table__cell-order-number a,
.youzify .youzify-wc-main-content table.shop_table thead,
.youzify-forums-topic-item .youzify-forums-topic-icon i,
.youzify-forums-forum-item .youzify-forums-forum-icon i,
div.bbp-submit-wrapper button,
#bbpress-forums li.bbp-header,
#bbpress-forums .bbp-search-form #bbp_search_submit,
#bbpress-forums #bbp-search-form #bbp_search_submit,
.widget_display_search #bbp_search_submit,
.widget_display_forums li a:before,
.widget_display_views li .bbp-view-title:before,
.widget_display_topics li:before,
#bbpress-forums li.bbp-footer,
.bbp-pagination .page-numbers.current,
.youzify-items-list-widget .youzify-list-item .youzify-item-action .youzify-add-button i,
#youzify-members-list .youzify-user-actions .friendship-button .requested,
.youzify-wall-embed .youzify-embed-action .friendship-button a.requested,
.youzify-widget .youzify-user-tags .youzify-utag-values .youzify-utag-value-item,
.item-list-tabs #search-message-form #messages_search_submit,
#youzify-groups-list .action .group-button .membership-requested,
#youzify-members-list .youzify-user-actions .friendship-button a,
#youzify-groups-list .action .group-button .request-membership,
.youzify-wall-embed .youzify-embed-action .friendship-button a,
.youzify-group-manage-members-search #members_search_submit,
#youzify-groups-list .action .group-button .accept-invite,
.notifications-options-nav #notification-bulk-manage,
.notifications .notification-actions .mark-read span,
.sitewide-notices .thread-options .activate-notice,
#youzify-groups-list .action .group-button .join-group,
.youzify-social-buttons .friendship-button a.requested,
#youzify-directory-search-box form input[type=submit],
.youzify-user-actions .friendship-button a.requested,
.youzify-wall-embed .youzify-embed-action .group-button a,
#youzify-group-buttons .group-button a.join-group,
.messages-notices .thread-options .read span,
.youzify-social-buttons .friendship-button a,
#search-members-form #members_search_submit,
.messages-options-nav #messages-bulk-manage,
.youzify-group-settings-tab input[type='submit'],
.youzify-user-actions .friendship-button a.add,
#group-settings-form input[type='submit'],
.youzify-product-content .youzify-featured-product,
.my-friends #friend-list .action a.accept,
.youzify-wall-new-post .youzify-post-more-button,
.group-request-list .action .accept a,
#message-recipients .highlight-icon i,
.youzify-pagination .page-numbers.current,
.youzify-project-content .youzify-project-type,
.youzify-author .youzify-account-settings,
.youzify-product-actions .youzify-addtocart,
.group-button.request-membership,
#send_message_form .submit #send,
#send-invite-form .submit input,
#send-reply #send_reply_button,
.youzify-wall-actions .youzify-wall-post,
.youzify-post-content .youzify-post-type,
.youzify-nav-effect .youzify-menu-border,
#group-create-tabs li.current,
.group-button.accept-invite,
.youzify-tab-post .youzify-read-more,
.group-button.join-group,
.youzify-service-icon i:hover,
.youzify-loading .youzify_msg,
.youzify-scrolltotop i:hover,
.youzify-post .youzify-read-more,
.youzify-author .youzify-login,
.pagination .current,
.youzify-tab-title-box,
.youzify-wall-file-post,
.button.accept {
            background-color: $scheme_color !important;
        }

@media screen and ( max-width: 768px ) {
#youzify .youzify-group div.item-list-tabs li.last label,
#youzify .youzify-profile div.item-list-tabs li.last label,
#youzify .youzify-directory-filter .item-list-tabs li#groups-order-select label,
#youzify .youzify-directory-filter .item-list-tabs li#members-order-select label {
    background-color: $scheme_color !important;
    color: #fff;
}
}
        .youzify-bbp-topic-head-meta .youzify-bbp-head-meta-last-updated a:not(.bbp-author-name),
        .widget_display_topics li .topic-author a.bbp-author-name,
        .activity-header .activity-head p a:not(:first-child),
        #message-recipients .highlight .highlight-meta a,
        .thread-sender .thread-from .from .thread-count,
        .youzify-profile-navmenu .youzify-navbar-item a:hover i,
        .widget_display_replies li a.bbp-author-name,
        .youzify-profile-navmenu .youzify-navbar-item a:hover,
        .youzify-link-main-content .youzify-link-url:hover,
        .youzify-wall-new-post .youzify-post-title a:hover,
        .youzify-recent-posts .youzify-post-title a:hover,
        .youzify-post-content .youzify-post-title a:hover,
        .youzify-group-settings-tab fieldset legend,
        .youzify-wall-link-data .youzify-wall-link-url,
        .youzify-tab-post .youzify-post-title a:hover,
        .youzify-project-tags .youzify-tag-symbole,
        .youzify-post-tags .youzify-tag-symbole,
        .youzify-group-navmenu li a:hover {
            color: $scheme_color !important;
        }

        .youzify-bbp-topic-head,
        .youzify .youzify-wc-main-content address .youzify-bullet,
        .youzify-profile-navmenu .youzify-navbar-item.youzify-active-menu,
        .youzify-group-navmenu li.current {
            border-color: $scheme_color !important;
        }

        body .quote-with-img:before,body .youzify-link-content,body .youzify-no-thumbnail,body a.youzify-settings-widget {
            background: $scheme_color $pattern !important;
        }
    ";

    wp_add_inline_style( 'youzify-customStyle', $custom_css );
    }

    /**
     * Gradient Elements.
     */
    function get_gradient_elements( $elements = null, $get_array = false ) {

        $elements = array();

        $elements[] = array(
            'selector'      => 'body .quote-with-img:before',
            'left_color'    => 'youzify_wg_quote_gradient_left_color',
            'right_color'   => 'youzify_wg_quote_gradient_right_color'
        );

        $elements[] = array(
            'selector'      => '.youzify-box-email',
            'left_color'    => 'youzify_ibox_email_bg_left',
            'right_color'   => 'youzify_ibox_email_bg_right'
        );

        $elements[] = array(
            'selector'      => '.youzify-box-phone',
            'left_color'    => 'youzify_ibox_phone_bg_left',
            'right_color'   => 'youzify_ibox_phone_bg_right'
        );

        $elements[] = array(
            'selector'      => '.youzify-box-website',
            'left_color'    => 'youzify_ibox_website_bg_left',
            'right_color'   => 'youzify_ibox_website_bg_right'
        );

        $elements[] = array(
            'selector'      => '.youzify-box-address',
            'left_color'    => 'youzify_ibox_address_bg_left',
            'right_color'   => 'youzify_ibox_address_bg_right'
        );

        $elements[] = array(
            'target'        => 'youzify',
            'pattern'       => 'geometric',
            'selector'      => '.youzify-user-balance-box',
            'left_color'    => 'youzify_user_balance_gradient_left_color',
            'right_color'   => 'youzify_user_balance_gradient_right_color'
        );

        return $elements;
    }

    /**
     * Gradient Styling.
     */
    function gradient_styling( $element ) {

        // Get Options Data
        $left_color  = youzify_option( $element['left_color'] );
        $right_color = youzify_option( $element['right_color'] );

        // Get Colors
        $left_color  = isset( $left_color['color'] ) ? $left_color['color'] : null;
        $right_color =  isset( $right_color['color'] ) ? $right_color['color'] : null;

        // if the one of the values are empty go out.
        if ( ! empty( $left_color ) || ! empty( $right_color ) ) {

            // Get Pattern Data.
            $pattern_type = isset( $element['pattern'] ) ? 'geopattern' : 'dotted-bg';
            $pattern = 'url(' . YOUZIFY_ASSETS . 'images/' . $pattern_type . '.png)';

            echo '<style type="text/css">';
            echo "{$element['selector']} {
                    background: $pattern,linear-gradient(to right, $left_color , $right_color ) !important;
                    background: $pattern,-webkit-linear-gradient(left, $left_color , $right_color ) !important;
                }";
            echo '</style>';
        }
    }

    /**
     * Custom Snippets.
     */
    function custom_snippets( $component ) {

        if ( 'off' == youzify_option( 'youzify_enable_' . $component . '_custom_styling', 'off' ) ) {
            return false;
        }

        // Get CSS Code.
        $custom_css = youzify_option( 'youzify_' . $component . '_custom_styling' );

        if ( empty( $custom_css ) ) {
            return false;
        }

        // Custom Styling File.
        wp_enqueue_style( 'youzify-customStyle', YOUZIFY_ADMIN_ASSETS . 'css/custom-script.css' );

        wp_add_inline_style( 'youzify-customStyle', $custom_css );

    }
}


/**
 * Get a unique instance of Youzify Styling.
 */
function youzify_styling() {
    return Youzify_Styling::get_instance();
}

/**
 * Launch Youzify Styling!
 */
youzify_styling();