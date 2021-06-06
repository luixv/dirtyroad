<?php

function whitedot_customizer_css() {

    if( true === get_theme_mod( 'whitedot_advanced_header_layout', false ) && true === get_theme_mod( 'enable_secondary_nav', false ) && 'position-1' === get_theme_mod( 'header_secondary_nav_position' )  ) {
        $secondary_nav_in_header_bar = true;
    }else{
        $secondary_nav_in_header_bar = false;
    }

    if( false === get_theme_mod( 'enable_header_social_icons', false ) && $secondary_nav_in_header_bar == false ) {
        $only_notice_active_in_bar = true;
    }else{
        $only_notice_active_in_bar = false;
    }

    if ( true === get_theme_mod( 'enable_secondary_nav' ) && 'position-1' === get_theme_mod( 'header_secondary_nav_position' ) ) { 
        $header_bar_nav = "on";
    } else{
        $header_bar_nav = "off";
    }

    if ( true === get_theme_mod( 'enable_header_notice' ) || true === get_theme_mod( 'enable_header_social_icons' ) || $header_bar_nav == "on" ) {
        $active_header_bar = "yes";
    }else{
        $active_header_bar = "no";
    }

    if ( true === get_theme_mod( 'enable_secondary_nav' ) && 'position-3' === get_theme_mod( 'header_secondary_nav_position' ) ) { 
        $secondary_nav_below = "yes";
    } else{
        $secondary_nav_below = "no";
    }

    if ( true === get_theme_mod( 'whitedot_advanced_header_layout' ) && 'position-3' === get_theme_mod( 'header_primary_nav_position' ) ) { 
        $primary_nav_above = "yes";
    } else{
        $primary_nav_above = "no";
    }

    if ( true === get_theme_mod( 'whitedot_advanced_header_layout' ) && 'position-2' === get_theme_mod( 'header_primary_nav_position' ) ) { 
        $primary_nav_below = "yes";
    } else{
        $primary_nav_below = "no";
    }

    if ( $primary_nav_below == "yes" || $primary_nav_above == "yes" ) {
        $primary_nav_taking_space = "yes";
    }else{
        $primary_nav_taking_space = "no";
    }

    if ( $primary_nav_above == "no" && $primary_nav_below == "no" && $secondary_nav_below == "no" ) {
        $nav_above_or_below = "no";
    }else{
        $nav_above_or_below = "yes";
    }

    if ( $active_header_bar == "yes" && $nav_above_or_below == "no" ) {
        $only_header_bar_active = "yes";
    }else{
        $only_header_bar_active = "no";
    }

    if ( $primary_nav_taking_space == "yes" && $active_header_bar == "no" && $secondary_nav_below == "no" ) {
        $only_primary_nav_taking_space = "yes";
    }else{
        $only_primary_nav_taking_space = "no";
    }

    if ( $secondary_nav_below == "yes" && $primary_nav_taking_space == "no" && $active_header_bar == "no" ) { 
        $only_secondary_nav_taking_space = "yes";
    } else{
        $only_secondary_nav_taking_space = "no";
    }

    if ( $active_header_bar == "yes" && $primary_nav_taking_space == "yes" && $secondary_nav_below == "no" ) {
        $primary_nav_and_header_bar = "yes";
    }else{
        $primary_nav_and_header_bar = "no";
    }

    if ( $active_header_bar == "yes" && $primary_nav_taking_space == "no" && $secondary_nav_below == "yes" ) {
        $secondary_nav_and_header_bar = "yes";
    }else{
        $secondary_nav_and_header_bar = "no";
    }
    if ( $active_header_bar == "no" && $primary_nav_above == "yes" && $secondary_nav_below == "yes" ) {
        $primary_and_secondary_nav = "yes";
    }else{
        $primary_and_secondary_nav = "no";
    }
    if ( $active_header_bar == "yes" && $primary_nav_above == "yes" && $secondary_nav_below == "yes" ) {
        $primary_nav_secondary_nav_and_bar = "yes";
    }else{
        $primary_nav_secondary_nav_and_bar = "no";
    }

    if ( $active_header_bar == "no" && $primary_nav_taking_space == "no" && $secondary_nav_below == "no" ) {
        $default_header = "yes";
    }else{
        $default_header = "no";
    }

    if ( true === get_theme_mod( 'whitedot_advanced_header_layout', false ) && true === get_theme_mod( 'enable_secondary_nav', false ) && false === get_theme_mod( 'hide_secondary_nav_in_mobile', false ) ) {
        $mobile_secondary_nav = "yes";
    }else{
        $mobile_secondary_nav = "no";
    }

    //Blog
    if( true === get_theme_mod( 'single_hero_data_hide_author', false ) && true === get_theme_mod( 'single_hero_data_hide_category', false ) && true === get_theme_mod( 'single_hero_data_hide_date', false ) ) {
        $single_hero_data_all_hidden = true;
    }else{
        $single_hero_data_all_hidden = false;
    }

    // Color mods 
    $body_text_color = get_theme_mod('whitedot_body_text_color');
    $contained_background_color = get_theme_mod('whitedot_contained_layout_background_color', '#fcfcfc');
    $header_color = get_theme_mod('whitedot_header_color');
    $link_color = get_theme_mod('whitedot_link_color');
    $link_hover_color = get_theme_mod('whitedot_link_hover_color');
    $header_text_color = get_theme_mod('header_text_color');
    $container_width = get_theme_mod('whitedot_outer_container_width');
    $single_blog_inner_width = get_theme_mod('whitedot_blog_inner_container_width', 50 );
    $boxed_page_inner_width = get_theme_mod('whitedot_page_inner_container_width', 30);
    //Typography
    $font_size = get_theme_mod('whitedot_body_text_font_size');
    $line_height = get_theme_mod('whitedot_body_text_line_height');
    $h1_font_size = get_theme_mod('whitedot_h1_font_size');
    $h1_font_weight = get_theme_mod('whitedot_h1_font_weight');
    $h2_font_size = get_theme_mod('whitedot_h2_font_size');
    $h2_font_weight = get_theme_mod('whitedot_h2_font_weight');
    $h3_font_size = get_theme_mod('whitedot_h3_font_size');
    $h3_font_weight = get_theme_mod('whitedot_h3_font_weight');
    $sidebar_heading_font_size = get_theme_mod('whitedot_sidebar_heading_font_size');
    $sidebar_heading_font_weight = get_theme_mod('whitedot_sidebar_heading_font_weight');
    $footer_sitetitle_font_size = get_theme_mod( 'whitedot_footer_sitetitle_font_size' );
    $footer_sitetitle_font_weight = get_theme_mod( 'whitedot_footer_sitetitle_font_weight' );
    $footer_sitetag_font_size = get_theme_mod( 'whitedot_footer_sitetag_font_size' );
    $footer_sitetag_font_weight = get_theme_mod( 'whitedot_footer_sitetag_font_weight' );
    $footer_widget_heading_font_size = get_theme_mod( 'whitedot_footer_widget_heading_font_size' );
    $footer_widget_heading_font_weight = get_theme_mod( 'whitedot_footer_widget_heading_font_weight' );
    $footer_widget_text_font_size = get_theme_mod( 'whitedot_footer_widget_text_font_size' );
    $footer_copyright_font_size = get_theme_mod( 'whitedot_footer_copyright_font_size' );
    $footer_copyright_font_weight = get_theme_mod( 'whitedot_footer_copyright_font_weight' );
    //header
    $site_header_color = get_theme_mod('site_header_color');
    $header_nav_link_hover_color = get_theme_mod('header_nav_link_hover_color');
    $above_header_bar_bg_color = get_theme_mod('above_header_bar_bg_color');
    $above_header_bar_border_color = get_theme_mod('above_header_bar_border_color');
    $above_header_bar_link_hover_color = get_theme_mod('above_header_bar_link_hover_color');
    $header_notice_text_color = get_theme_mod('header_notice_text_color');
    $calltoaction_bg_color = get_theme_mod('calltoaction_bg_color');
    $calltoaction_text_color = get_theme_mod('calltoaction_text_color');
    $calltoaction_hover_bg_color = get_theme_mod('calltoaction_hover_bg_color');
    $calltoaction_hover_text_color = get_theme_mod('calltoaction_hover_text_color');
    $whitedot_mobile_hamberger_color = get_theme_mod('whitedot_mobile_hamberger_color');
    $whitedot_mobile_nav_bg_color = get_theme_mod('whitedot_mobile_nav_bg_color');
    $whitedot_mobile_nav_text_color = get_theme_mod('whitedot_mobile_nav_text_color');
    $above_header_height = get_theme_mod('whitedot_above_header_height');
    $above_header_text_font_size = get_theme_mod('whitedot_above_header_text_font_size');
    //color
    $whitedot_primary_color = get_theme_mod('whitedot_primary_color');
    $whitedot_primary_hover_color = get_theme_mod('whitedot_primary_hover_color');
    $whitedot_primary_color = get_theme_mod('whitedot_primary_color');
    $whitedot_primary_hover_color = get_theme_mod('whitedot_primary_hover_color');
    $whitedot_boxed_layout_bg_color = get_theme_mod('whitedot_boxed_layout_bg_color');
    $whitedot_sidebar_bg_color = get_theme_mod('whitedot_sidebar_bg_color');
    $whitedot_sidebar_title_color = get_theme_mod('whitedot_sidebar_title_color');
    $whitedot_sidebar_title_border_color = get_theme_mod('whitedot_sidebar_title_border_color');
    $whitedot_sidebar_text_color = get_theme_mod('whitedot_sidebar_text_color');
    $whitedot_sidebar_link_color = get_theme_mod('whitedot_sidebar_link_color');
    $whitedot_sidebar_link_hover_color = get_theme_mod('whitedot_sidebar_link_hover_color');
    $temp_header_text_color = get_theme_mod('temp_header_text_color');
    $temp_header_nav_link_hover_color = get_theme_mod('temp_header_nav_link_hover_color');
    //Blog
    $single_post_meta_color = get_theme_mod('whitedot_single_post_meta_color');
    $single_post_title_color = get_theme_mod('whitedot_single_post_title_color');
    $single_post_excerpt_color = get_theme_mod('whitedot_single_post_excerpt_color');
    $single_post_hero_overlay_color = get_theme_mod('whitedot_single_post_hero_overlay_color');
    $single_post_hero_overlay_opacity = get_theme_mod('whitedot_single_post_hero_overlay_opacity');
    $single_post_hero_text_color = get_theme_mod('whitedot_single_post_hero_text_color');
    $single_post_title_alignment = get_theme_mod('whitedot_single_post_title_alignment');
    $single_post_metadata_alignment = get_theme_mod('whitedot_single_post_metadata_alignment');
    
    
?>

<style type="text/css">

.is-boxed .alignfull,
.is-boxed .alignwide
 {
    margin: 0 -<?php echo esc_attr( $single_blog_inner_width) ?>px;
    width: calc(100% + <?php echo esc_attr( $single_blog_inner_width) ?>px + <?php echo esc_attr( $single_blog_inner_width) ?>px);
}

.has-sidebar .is-contained.sidebar-enabled .alignfull,
.is-contained .alignwide
 {
    margin: 0 -25px;
    width: calc(100% + 50px);
}

figure.alignfull{
    max-width: unset;
}

.has-sidebar .sidebar-disabled.is-contained .alignfull,
.no-sidebar .is-contained .alignfull{
    margin-left: calc(-100vw/2 + 100%/2);
    margin-right: calc(-100vw/2 + 100%/2);
    max-width: 100vw;
    width: auto;
}




.page-template .boxed .alignfull,
.page-template .boxed .alignwide
 {
    margin: 0 -<?php echo esc_attr( $boxed_page_inner_width) ?>px;
    width: calc(100% + <?php echo esc_attr( $boxed_page_inner_width) ?>px + <?php echo esc_attr( $boxed_page_inner_width) ?>px);
}

.sidebar-none.contained .alignfull,
.no-sidebar .sidebar-left.contained .alignfull,
.no-sidebar .sidebar-right.contained .alignfull,
.page-template-default #primary.sidebar-disabled .alignfull{
    margin-left: calc(-100vw/2 + 100%/2);
    margin-right: calc(-100vw/2 + 100%/2);
    max-width: 100vw;
    width: auto;
}


<?php if( class_exists( 'LifterLMS' ) ) { ?>
    <?php if ( is_llms_checkout() ) { ?>
        .has-sidebar #primary{
            width: 100%!important;
            float: none!important;
        }
        .has-sidebar .secondary{
            display: none;
        }
    <?php } ?>
<?php } ?>

<?php if( class_exists( 'WooCommerce' ) ) { ?>
    <?php if ( !is_user_logged_in() ) { ?>
        @media (min-width: 768px){
            .woocommerce-account .site-main, .woocommerce-account .site-footer {
                margin-left: 0!important;
            }
        }
    <?php } ?>
<?php } ?>
<?php if( !class_exists( 'WP_Embedly' ) ) { ?>

    .llms-video-wrapper {
        text-align: unset;
        margin-bottom: 0;
    }

    .embed-container,
    .llms-video-wrapper .center-video { 
        position: relative; 
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        max-width: 100%;
    } 

    .embed-container iframe,
    .embed-container object,
    .embed-container embed,
    .llms-video-wrapper .center-video iframe,
    .llms-video-wrapper .center-video object,
    .llms-video-wrapper .center-video embed { 
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
<?php } ?>
/* Color */
body{
    color: <?php echo esc_attr( $body_text_color ) ?>;
}
h1, h2, h3, h4, h5, h6{
    color: <?php echo esc_attr( $header_color ) ?>;
}
a{
    color: <?php echo esc_attr( $link_color ) ?>;
}
a:hover{
    color: <?php echo esc_attr( $link_hover_color ) ?>;
}
.col-full, 
.custom-col-full{
    max-width: <?php echo esc_attr( $container_width) ?>px;
}
.single-post .wd-post-content {
    padding: 20px <?php echo esc_attr( $single_blog_inner_width) ?>px;
}

.page-template-template-no-sidebar-boxed .boxed-layout{
    padding: 30px <?php echo esc_attr( $boxed_page_inner_width) ?>px;
}

<?php if (whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) != 'Boxed') { ?>

    <?php if( 'contained' === get_theme_mod( 'whitedot_single_blog_container_layout', 'boxed' ) || whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Contained' ) { ?>

        <?php if( 'sidebarright' === get_theme_mod( 'whitedot_blog_single_sidebar_layout', 'sidebarright' ) ) { ?>

            @media (min-width: 768px){

                .whitedot-post-contained .secondary .wd-widget{
                    padding-top: 0;
                    padding-right: 0;
                    padding-left: 50px;
                }

                .whitedot-post-contained .secondary .wd-sidebar {
                    border-left: 1px solid #eee;
                    margin-left: -1px;
                }

                .whitedot-post-contained #primary {
                    padding-right: 50px;
                    border-right: 1px solid #eee;
                }

            }
        <?php } ?>

        <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_blog_single_sidebar_layout', 'sidebarright' ) ) { ?>

            @media (min-width: 768px){
                .whitedot-post-contained .secondary .wd-widget{
                    padding-top: 0;
                    padding-left: 0;
                    padding-right: 50px;
                }

                .whitedot-post-contained .secondary .wd-sidebar {
                    border-right: 1px solid #eee;
                    margin-right: -1px;
                }

                .whitedot-post-contained #primary {
                    padding-left: 50px;
                    border-left: 1px solid #eee;
                }
            }

        <?php } ?>

        <?php if ( class_exists( 'Whitedot_Designer' ) ) { ?>
            <?php if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) { ?>
                <?php if( false === get_theme_mod( 'whitedot_blog_single_show_title', true ) && false === get_theme_mod( 'whitedot_blog_single_metadate', true ) && false === get_theme_mod( 'whitedot_blog_single_metaauthor', true ) && false === get_theme_mod( 'whitedot_blog_single_metacategory', true ) ) { ?>

                    .single-post .wd-custom-content{
                        padding-top: 0;
                        margin-top: -20px;
                    }

                <?php } ?>
            <?php } ?>
        <?php } ?>
    <?php } ?>

<?php } ?>


<?php if( 'contained' === get_theme_mod( 'whitedot_blog_home_container_layout', 'boxed' ) ) { ?>
    body.blog{
        background: <?php echo $contained_background_color; ?>;
    }
    .blog .wd-single-post,
    .blog .wd-widget,
    .wd-excerpt-container{
        background: transparent;
        box-shadow: none;
    }
    .blog .wd-widget{
        padding-top: 0;
    }
<?php } ?>


.whitedot-page-contained,
.whitedot-post-contained{
    background: <?php echo $contained_background_color; ?>;
}

<?php if (whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) != 'Boxed') { ?>

    <?php if( 'contained' === get_theme_mod( 'whitedot_page_container_layout', 'boxed' ) || whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Contained' ) { ?>

        <?php if( 'sidebarright' === get_theme_mod( 'whitedot_page_sidebar_layout', 'sidebarright' ) ) { ?>

        @media (min-width: 768px){

            .whitedot-page-contained .secondary .wd-widget{
                padding-top: 0;
                padding-right: 0;
                padding-left: 50px;
            }

            .whitedot-page-contained .secondary .wd-sidebar {
                border-left: 1px solid #eee;
                margin-left: -1px;
            }

            .whitedot-page-contained #primary {
                padding-right: 50px;
                border-right: 1px solid #eee;
            }

        }

        <?php } ?>

        <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_page_sidebar_layout', 'sidebarright' ) ) { ?>

        @media (min-width: 768px){
            .whitedot-page-contained .secondary .wd-widget{
                padding-top: 0;
                padding-left: 0;
                padding-right: 50px;
            }

            .whitedot-page-contained .secondary .wd-sidebar {
                border-right: 1px solid #eee;
                margin-right: -1px;
            }

            .whitedot-page-contained #primary {
                padding-left: 50px;
                border-left: 1px solid #eee;
            }
        }

        <?php } ?>

    <?php } ?>
    
<?php } ?>

/*--Header--*/
.sub-menu li a{
	color: #777!important;
}
.site-name{
	color: <?php echo esc_attr( $header_text_color ); ?>!important;
}
.menu-item-has-children:after{
	color: <?php echo esc_attr( $header_text_color ); ?>70;
}
.site-description,
.primary-nav li a,
.wd-cart a,
.wd-cart-mob a,
.wd-header-search-btn {
	color: <?php echo esc_attr( $header_text_color ); ?>;
}
<?php if( true === get_theme_mod( 'whitedot_hide_tagline', false ) ) { ?>
    @media (min-width: 768px){
        .site-description{
            position: absolute;
            top: -1000px;
            font-size: .1px;
        }
        .site-branding{
            padding-top: 20px;
        }
    }
<?php } ?>

<?php if( 'style-2' === get_theme_mod( 'header_styles' ) ) { ?> 
    @media (min-width: 768px){
    	.site-header{
    		min-height: auto;
    	}
        .site-branding {
            width: 100%;
            text-align: center;
        }
        #wd-primary-nav {
            width: 100%;
        }
        .primary-nav {
            text-align: center;
        }
        #primary-menu {
			display: inline-block;
			vertical-align: top;
		}
        .wd-site-logo {
            width: 100%;
            text-align: center;
        }
        <?php if ( true == get_theme_mod( 'whitedot_fixed_header', false ) ) { ?> 
        .site-content {
            margin-top: 160px!important;
        }
        <?php } ?>
        .has-wp-cart, .has-header-search {
            margin-right: 0;
        }
    }
<?php } ?>
<?php if(get_header_image()){ ?>
	.site-header{
		background-image: url(<?php header_image(); ?>);
		background-repeat: no-repeat;
		background-position: center;
		background-size: cover ;
	}
	.sub-menu .menu-item-has-children:after{
		color: #00000030;
	}
<?php } ?>

/*--Sidebar--*/
<?php if( 'sidebarleft' === get_theme_mod( 'whitedot_page_sidebar_layout' ) ) { ?> 
@media (min-width: 768px){
    .has-sidebar.page-template-default #primary {
        float: right;
        width: 70%;
    }
    .has-sidebar.page-template-default .secondary {
        float: left;
    }
}
<?php } ?>
<?php if( 'sidebarright' === get_theme_mod( 'whitedot_page_sidebar_layout' ) ) { ?> 
@media (min-width: 768px){
    .has-sidebar.page-template-default #primary {
        float: left;
        width: 70%;
    }
    .has-sidebar.page-template-default .secondary {
        float: right;
    }
}
<?php } ?>
<?php if( 'sidebarnone' === get_theme_mod( 'whitedot_page_sidebar_layout' ) ) { ?> 
    .has-sidebar.page-template-default #primary {
        float: none!important;
        width: 100%!important;
    }
    .has-sidebar.page-template-default .secondary {
        display: none;
    }
<?php } ?>

/*--WooCommerce--*/
<?php if( class_exists( 'WooCommerce' ) ) { ?>
    <?php if ( 0 == get_theme_mod( 'whitedot_show_cart_in_header', 1 ) ) { ?>
        .primary-nav{
            margin-right: 0!important;
        }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_woo_single_product_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .has-sidebar.single-product #primary {
            float: right;
            width: 70%;
        }
        .has-sidebar.single-product .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_woo_single_product_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .has-sidebar.single-product #primary {
            float: left;
            width: 70%;
        }
        .has-sidebar.single-product .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_woo_single_product_sidebar_layout' ) ) { ?> 
        .has-sidebar.single-product #primary {
            float: none!important;
            width: 100%!important;
        }
        .has-sidebar.single-product .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_woo_shop_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .has-sidebar.post-type-archive-product #primary,
        .has-sidebar.tax-product_cat #primary,
        .has-sidebar.tax-product_tag #primary {
            float: right;
            width: 70%;
        }
        .has-sidebar.post-type-archive-product .secondary,
        .has-sidebar.tax-product_cat .secondary,
        .has-sidebar.tax-product_tag .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_woo_shop_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .has-sidebar.post-type-archive-product #primary,
        .has-sidebar.tax-product_cat #primary,
        .has-sidebar.tax-product_tag #primary {
            float: left;
            width: 70%;
        }
        .has-sidebar.post-type-archive-product .secondary,
        .has-sidebar.tax-product_cat .secondary,
        .has-sidebar.tax-product_tag .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_woo_shop_sidebar_layout' ) ) { ?> 
        .has-sidebar.post-type-archive-product #primary,
        .has-sidebar.tax-product_cat #primary,
        .has-sidebar.tax-product_tag #primary {
            float: none!important;
            width: 100%!important;
        }
        .has-sidebar.post-type-archive-product .secondary,
        .has-sidebar.tax-product_cat .secondary,
        .has-sidebar.tax-product_tag .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_woo_cart_sidebar_layout', 'sidebarnone' ) ) { ?> 
    @media (min-width: 768px){
        .has-sidebar.woocommerce-cart #primary {
            float: right;
            width: 70%;
        }
        .has-sidebar.woocommerce-cart .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_woo_cart_sidebar_layout', 'sidebarnone' ) ) { ?> 
    @media (min-width: 768px){
        .has-sidebar.woocommerce-cart #primary {
            float: left;
            width: 70%;
        }
        .has-sidebar.woocommerce-cart .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_woo_cart_sidebar_layout', 'sidebarnone' ) ) { ?> 
        .has-sidebar.woocommerce-cart #primary {
            float: none!important;
            width: 100%!important;
        }
        .has-sidebar.woocommerce-cart .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_woo_checkout_sidebar_layout', 'sidebarnone' ) ) { ?> 
    @media (min-width: 768px){
        .has-sidebar.woocommerce-checkout #primary {
            float: right;
            width: 70%;
        }
        .has-sidebar.woocommerce-checkout .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_woo_checkout_sidebar_layout', 'sidebarnone' ) ) { ?> 
    @media (min-width: 768px){
        .has-sidebar.woocommerce-checkout #primary {
            float: left;
            width: 70%;
        }
        .has-sidebar.woocommerce-checkout .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_woo_checkout_sidebar_layout', 'sidebarnone' ) ) { ?> 
        .has-sidebar.woocommerce-checkout #primary {
            float: none!important;
            width: 100%!important;
        }
        .has-sidebar.woocommerce-checkout .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( 'left' === get_theme_mod( 'whitedot_woo_shop_filter_layout' ) ) { ?> 
        #filter-main::-webkit-scrollbar {
            display: none;
        }
        #filter-main.active {
            left: 0;
            transition: .4s;
        }
        #filter-main {
            left: -1000px;
        }
        #remove-filter-wrap{
            float: right;
        }
        .whitedot-filter-widgets{
            clear: both;
        }
    <?php } ?>
    <?php if( 'column-2' === get_theme_mod( 'whitedot_shop_product_column_tablet' ) ) { ?> 
    @media (max-width: 767px) and (min-width: 499px){
        .woocommerce ul.products li.product,
        .woocommerce-page ul.products li.product {
            width: 48%!important;
            margin-bottom: 1%!important;
            margin: 1%!important;
            border: 0!important;
            box-shadow: 0 0 9px rgba(0,0,0,0.1);
        }
    }
    <?php } ?>
    <?php if( 'column-3' === get_theme_mod( 'whitedot_shop_product_column_tablet' ) ) { ?> 
    @media (max-width: 767px) and (min-width: 499px){
        .woocommerce ul.products li.product,
        .woocommerce-page ul.products li.product {
            width: 100%!important;
            margin-bottom: 3%!important;
            border: 0!important;
            box-shadow: 0 0 9px rgba(0,0,0,0.1);

        }
    }
    <?php } ?>

    <?php if( 'column-2' === get_theme_mod( 'whitedot_shop_product_column_mobile' ) ) { ?> 
    @media (max-width: 500px){
        .woocommerce ul.products li.product,
        .woocommerce-page ul.products li.product {
            width: 48%!important;
            margin-bottom: 1%!important;
            margin: 1%!important;
        }
    }
    <?php } ?>
    <?php if( 'column-3' === get_theme_mod( 'whitedot_shop_product_column_mobile' ) ) { ?> 
    @media (max-width: 500px){
        .woocommerce ul.products li.product,
        .woocommerce-page ul.products li.product {
            width: 100%!important;
            margin-bottom: 3%!important;
        }
    }
    <?php } ?>
<?php } ?>

/*--LifterLMS--*/
<?php if( class_exists( 'LifterLMS' ) ) { ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_course_catalog_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .post-type-archive-course.has-sidebar #primary {
            float: left;
            width: 70%;
        }
        .post-type-archive-course.has-sidebar .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_course_catalog_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .post-type-archive-course.has-sidebar #primary {
            float: right;
            width: 70%;
        }
        .post-type-archive-course.has-sidebar .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_course_catalog_sidebar_layout' ) ) { ?> 
        .post-type-archive-course.has-sidebar #primary {
            float: none!important;
            width: 100%!important;
        }
        .post-type-archive-course.has-sidebar .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_membership_catalog_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .post-type-archive-llms_membership.has-sidebar #primary {
            float: left;
            width: 70%;
        }
        .post-type-archive-llms_membership.has-sidebar .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_membership_catalog_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .post-type-archive-llms_membership.has-sidebar #primary {
            float: right;
            width: 70%;
        }
        .post-type-archive-llms_membership.has-sidebar .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_membership_catalog_sidebar_layout' ) ) { ?> 
        .post-type-archive-llms_membership.has-sidebar #primary {
            float: none!important;
            width: 100%!important;
        }
        .post-type-archive-llms_membership.has-sidebar .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_single_course_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .single-course.has-sidebar #primary {
            float: left;
            width: 70%;
        }
        .single-course.has-sidebar .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_single_course_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .single-course.has-sidebar #primary {
            float: right;
            width: 70%;
        }
        .single-course.has-sidebar .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_single_course_sidebar_layout' ) ) { ?> 
        .single-course.has-sidebar #primary {
            float: none!important;
            width: 100%!important;
        }
        .single-course.has-sidebar .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_single_lesson_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .single-lesson.has-sidebar #primary {
            float: left;
            width: 70%;
        }
        .single-lesson.has-sidebar .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_single_lesson_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .single-lesson.has-sidebar #primary {
            float: right;
            width: 70%;
        }
        .single-lesson.has-sidebar .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_single_lesson_sidebar_layout' ) ) { ?> 
        .single-lesson.has-sidebar #primary {
            float: none!important;
            width: 100%!important;
        }

        .single-lesson.has-sidebar .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( 'sidebarright' === get_theme_mod( 'whitedot_single_membership_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .single-llms_membership.has-sidebar #primary {
            float: left;
            width: 70%;
        }
        .single-llms_membership.has-sidebar .secondary {
            float: right;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_single_membership_sidebar_layout' ) ) { ?> 
    @media (min-width: 768px){
        .single-llms_membership.has-sidebar #primary {
            float: right;
            width: 70%;
        }
        .single-llms_membership.has-sidebar .secondary {
            float: left;
            display: block;
        }
    }
    <?php } ?>
    <?php if( 'sidebarnone' === get_theme_mod( 'whitedot_single_membership_sidebar_layout' ) ) { ?> 
        .single-llms_membership.has-sidebar #primary {
            float: none!important;
            width: 100%!important;
        }
        .single-llms_membership.has-sidebar .secondary {
            display: none;
        }
    <?php } ?>
    <?php if( false === get_theme_mod( 'whitedot_single_course_metaauthor', true ) ) { ?>
        .single-course .wd-author{
            display: none;
        }
    <?php } ?>
    <?php if( false === get_theme_mod( 'whitedot_single_course_metadate', true ) ) { ?>
        .single-course .wd-date,
        .single-course .wd-author:after{
            display: none;
        }
    <?php } ?>
    <?php if( false === get_theme_mod( 'whitedot_single_lesson_metaauthor', true ) ) { ?>
        .single-lesson .wd-author{
            display: none;
        }
    <?php } ?>
    <?php if( false === get_theme_mod( 'whitedot_single_lesson_metadate', true ) ) { ?>
        .single-lesson .wd-date,
        .single-lesson .wd-author:after{
            display: none;
        }
    <?php } ?>
    <?php if( false === get_theme_mod( 'whitedot_single_membership_metaauthor', true ) ) { ?>
        .single-llms_membership .wd-author{
            display: none;
        }
    <?php } ?>
    <?php if( false === get_theme_mod( 'whitedot_single_membership_metadate', true ) ) { ?>
        .single-llms_membership .wd-date,
        .single-llms_membership .wd-author:after{
            display: none;
        }
    <?php } ?>
    <?php if ( 1 == get_theme_mod( 'whitedot_show_dashboard_nav_icon', 0 ) ) { ?>
        .llms-student-dashboard .llms-sd-items li a:before {
            display: inline-block;
            font: normal normal normal 14px/1 FontAwesome;
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            content: "\f0f6";
            line-height: 1.618;
            margin-left: 0.5407911001em;
            width: 1.41575em;
            text-align: left;
            opacity: .25;
        }
        li.llms-sd-item.current a:before,
        li.llms-sd-item a:hover:before{
        opacity: 1;
        transition: .3s;
        }
        li.llms-sd-item.dashboard a:before{
        content: "\f0e4";
        }
        li.llms-sd-item.view-courses a:before{
        content: "\f24d";
        }
        li.llms-sd-item.view-achievements a:before{
        content: "\f19d";
        }
        li.llms-sd-item.notifications a:before{
        content: "\f0a2";
        }
        li.llms-sd-item.edit-account a:before{
        content: "\f2c0";
        }
        li.llms-sd-item.redeem-voucher a:before{
        content: "\f02c";
        }
        li.llms-sd-item.orders a:before{
        content: "\f07a";
        }
        li.llms-sd-item.signout a:before{
        content: "\f08b";
        }
    <?php } ?>
<?php } ?>

/*--Blog--*/

<?php if( 'sidebarright' === get_theme_mod( 'whitedot_blog_single_sidebar_layout' ) ) { ?> 
@media (min-width: 768px){
    .has-sidebar.single-post #primary {
        float: left;
    }
    .has-sidebar.single-post .secondary {
        float: right;
        display: block;
    }
}
<?php } ?>
<?php if( 'sidebarleft' === get_theme_mod( 'whitedot_blog_single_sidebar_layout' ) ) { ?> 
@media (min-width: 768px){
    .has-sidebar.single-post #primary {
        float: right;
    }
    .has-sidebar.single-post .secondary {
        float: left;
        display: block;
    }
}
<?php } ?>
<?php if( 'sidebarnone' === get_theme_mod( 'whitedot_blog_single_sidebar_layout' ) ) { ?> 
    .has-sidebar.single-post #primary {
        float: none!important;
        width: 100%!important;
    }
    .has-sidebar.single-post .secondary {
        display: none;
    }
<?php } ?>
<?php if( 'sidebarright' === get_theme_mod( 'whitedot_blog_archive_sidebar_layout' ) ) { ?> 
@media (min-width: 768px){
    .has-sidebar.blog #primary,
    .has-sidebar.archive.category #primary {
        float: left;
    }
    .has-sidebar.blog .secondary,
    .has-sidebar.archive.category .secondary {
        float: right;
        display: block;
    }
}
<?php } ?>
<?php if( 'sidebarleft' === get_theme_mod( 'whitedot_blog_archive_sidebar_layout' ) ) { ?> 
@media (min-width: 768px){
    .has-sidebar.blog #primary,
    .has-sidebar.archive.category #primary {
        float: right;
    }
    .has-sidebar.blog .secondary,
    .has-sidebar.archive.category .secondary {
        float: left;
        display: block;
    }
}
<?php } ?>
<?php if( 'sidebarnone' === get_theme_mod( 'whitedot_blog_archive_sidebar_layout' ) ) { ?> 
    .has-sidebar.blog #primary,
    .has-sidebar.archive.category #primary {
        float: none!important;
        width: 100%!important;
    }
    .has-sidebar.blog .secondary,
    .has-sidebar.archive.category .secondary {
        display: none;
    }
<?php } ?>
<?php if( 'style-2' === get_theme_mod( 'whitedot_blog_home_layout' ) ) { ?> 
    @media (min-width: 600px){
        .wd-blog-grid.col-2 .wd-single-post {
            width: 47%;
            float: left;
            margin: 0 1.5% 3% 1.5%;
        }
        .wd-blog-grid.col-3 .wd-single-post {
            width: 31%;
            float: left;
            margin: 0 1.15% 2.3% 1.15%;
            margin-top: 0;
        }
        .wd-blog-grid.col-4 .wd-single-post {
            width: 23%;
            float: left;
            margin: 0 1% 2% 1%;
            margin-top: 0;
        }
        .blog .wd-blog-grid {
            display: flex;
            flex-wrap: wrap;
        }
        .wd-loop-featured-img {
             padding-bottom: 60%;
        }
    }
    .wd-post-pagination {
        margin-top: 1em;
    }
<?php } ?>
<?php if( false === get_theme_mod( 'whitedot_blog_home_metadate', true ) ) { ?>
    .blog .excerpt-meta .date,
    .archive.category .excerpt-meta .date{
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute !important;
        width: 1px;
        word-wrap: normal !important;
    }
<?php } ?>
<?php if( false === get_theme_mod( 'whitedot_blog_home_metaauthor', true ) ) { ?>
    .blog .excerpt-meta .author,
    .blog .excerpt-meta .date:after,
    .archive.category .excerpt-meta .author,
    .archive.category .excerpt-meta .date:after{
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute !important;
        width: 1px;
        word-wrap: normal !important;
    }
<?php } ?>
<?php if( false === get_theme_mod( 'whitedot_blog_single_metadate', true ) && false === get_theme_mod( 'whitedot_blog_single_metaauthor', true ) && false === get_theme_mod( 'whitedot_blog_single_metacategory', true ) ) { ?>
    .wd-post-content .wd-post-title,
    .wd-post-content .wd-post-title.hero-img-exist{
        margin-bottom: 0;
    }
<?php } ?>
<?php if( false === get_theme_mod( 'whitedot_blog_single_metadate', true ) ) { ?>
    .wd-post-content .single-excerpt-meta .wd-date{
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute !important;
        width: 1px;
        word-wrap: normal !important;
    }
<?php } ?>
<?php if( false === get_theme_mod( 'whitedot_blog_single_metaauthor', true ) ) { ?>
    .wd-post-content .single-excerpt-meta .wd-author{
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute !important;
        width: 1px;
        word-wrap: normal !important;
    }
<?php } ?>
<?php if( false === get_theme_mod( 'whitedot_blog_single_metacategory', true ) ) { ?>
    .wd-post-content .single-excerpt-meta .single-category-meta,
    .wd-post-content .single-excerpt-meta .wd-date:after{
        border: 0;
        clip: rect(1px, 1px, 1px, 1px);
        clip-path: inset(50%);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute !important;
        width: 1px;
        word-wrap: normal !important;
    }
<?php } ?>
/*Sidebar Width*/
@media(min-width: 768px){
   .has-sidebar.blog .secondary {
    width: <?php echo get_theme_mod('whitedot_blog_archive_sidebar_width') ?>%;
    }
    .has-sidebar.blog #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_blog_archive_sidebar_width') ?>% );
    } 
    .has-sidebar.single-post .secondary {
        width: <?php echo get_theme_mod('whitedot_single_blog_sidebar_width') ?>%;
    }
    .has-sidebar.single-post #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_single_blog_sidebar_width') ?>% );
    }
    .has-sidebar.page-template-default .secondary {
        width: <?php echo get_theme_mod('whitedot_page_sidebar_width') ?>%;
    }
    .has-sidebar.page-template-default #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_page_sidebar_width') ?>% );
    }
    .has-sidebar.post-type-archive-product .secondary {
        width: <?php echo get_theme_mod('whitedot_woo_shop_sidebar_width') ?>%;
    }
    .has-sidebar.post-type-archive-product #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_woo_shop_sidebar_width') ?>% );
    }
    .has-sidebar.single-product .secondary {
        width: <?php echo get_theme_mod('whitedot_woo_single_product_sidebar_width') ?>%;
    }
    .has-sidebar.single-product #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_woo_single_product_sidebar_width') ?>% );
    }
    .has-sidebar.woocommerce-cart .secondary {
        width: <?php echo get_theme_mod('whitedot_woo_cart_sidebar_width') ?>%;
    }
    .has-sidebar.woocommerce-cart #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_woo_cart_sidebar_width') ?>% );
    }
    .has-sidebar.woocommerce-checkout .secondary {
        width: <?php echo get_theme_mod('whitedot_woo_checkout_sidebar_width') ?>%;
    }
    .has-sidebar.woocommerce-checkout #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_woo_checkout_sidebar_width') ?>% );
    }
    .post-type-archive-course.has-sidebar .secondary {
        width: <?php echo get_theme_mod('whitedot_course_catalog_sidebar_width') ?>%;
    }
    .post-type-archive-course.has-sidebar #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_course_catalog_sidebar_width') ?>% );
    }
    .post-type-archive-llms_membership.has-sidebar .secondary {
        width: <?php echo get_theme_mod('whitedot_membership_catalog_sidebar_width') ?>%;
    }
    .post-type-archive-llms_membership.has-sidebar #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_membership_catalog_sidebar_width') ?>% );
    }
    .single-course.has-sidebar .secondary {
        width: <?php echo get_theme_mod('whitedot_single_course_sidebar_width') ?>%;
    }
    .single-course.has-sidebar #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_single_course_sidebar_width') ?>% );
    }
    .single-lesson.has-sidebar .secondary {
        width: <?php echo get_theme_mod('whitedot_single_lesson_sidebar_width') ?>%;
    }
    .single-lesson.has-sidebar #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_single_lesson_sidebar_width') ?>% );
    }
    .single-llms_membership.has-sidebar .secondary {
        width: <?php echo get_theme_mod('whitedot_single_membership_sidebar_layout') ?>%;
    }
    .single-llms_membership.has-sidebar #primary {
        width: calc( 100% - <?php echo get_theme_mod('whitedot_single_membership_sidebar_layout') ?>% );
    }
}
/*--Typography--*/
<?php if( 'font-2' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?> 
    body, 
    button,
    input{
        font-family: 'ABeeZee', sans-serif;
    }
<?php }elseif ('font-3' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Abel', sans-serif;
    }
<?php }elseif ('font-4' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Actor', sans-serif;
    }
<?php }elseif ('font-5' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Advent Pro', sans-serif;
    }
<?php }elseif ('font-6' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Anaheim', sans-serif;
    }
<?php }elseif ('font-7' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Andada', serif;
    }
<?php }elseif ('font-7-1' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Alfa Slab One', cursive;
    }
<?php }elseif ('font-8' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Bad Script', cursive;
    }
<?php }elseif ('font-9' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Barlow', sans-serif;
    }
<?php }elseif ('font-10' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Bellefair', serif;
    }
<?php }elseif ('font-11' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'BenchNine', sans-serif;
    }
<?php }elseif ('font-12' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Bubbler One', sans-serif;
    }
<?php }elseif ('font-13' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Cabin', sans-serif;
    }
<?php }elseif ('font-14' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Cairo', sans-serif;
    }
<?php }elseif ('font-15' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Capriola', sans-serif;
    }
<?php }elseif ('font-16' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Catamaran', sans-serif;
    }
<?php }elseif ('font-17' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Chathura', sans-serif;
    }
<?php }elseif ('font-18' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Delius', cursive;
    }
<?php }elseif ('font-19' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Delius Swash Caps', cursive;
    }
<?php }elseif ('font-20' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Didact Gothic', sans-serif;
    }
<?php }elseif ('font-21' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Dosis', sans-serif;
    }
<?php }elseif ('font-21-2' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Mr Dafoe', cursive;
    }
<?php }elseif ('font-22' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'EB Garamond', serif;
    }
<?php }elseif ('font-23' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Economica', sans-serif;
    }
<?php }elseif ('font-24' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'El Messiri', sans-serif;
    }
<?php }elseif ('font-25' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Electrolize', sans-serif;
    }
<?php }elseif ('font-26' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Encode Sans', sans-serif;
    }
<?php }elseif ('font-27' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Encode Sans Condensed', sans-serif;
    }
<?php }elseif ('font-28' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Encode Sans Expanded', sans-serif;
    }
<?php }elseif ('font-29' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Englebert', sans-serif;
    }
<?php }elseif ('font-30' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Enriqueta', serif;
    }
<?php }elseif ('font-31' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Esteban', serif;
    }
<?php }elseif ('font-32' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Exo', sans-serif;
    }
<?php }elseif ('font-33' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Expletus Sans', cursive;
    }
<?php }elseif ('font-34' === get_theme_mod( 'whitedot_google_fonts' ) ) { ?>
    body, 
    button,
    input{
        font-family: 'Josefin Slab', serif;
    }
<?php } ?>
body{
    font-size: <?php echo esc_attr( $font_size ) ?>px;
    line-height: <?php echo "calc(" . esc_attr( $line_height ) . "/ 10 )" ?>;
}
</style>
<?php 
} 


