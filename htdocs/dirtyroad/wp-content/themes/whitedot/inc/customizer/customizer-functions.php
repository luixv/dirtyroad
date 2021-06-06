<?php
/**
 * WhiteDot Customizer Functions
 *
 */

/**
 * Whitedot blog grid wrap
 *
 * @since 1.0.0
 */
add_action('template_redirect', 'whitedot_show_blog_grid_wrap');
function whitedot_show_blog_grid_wrap()
{
	if( 'style-2' === get_theme_mod( 'whitedot_blog_home_layout' ) ) {
        if ( !is_front_page() && is_home() ) {
    		add_action('whitedot_home_loop_before','whitedot_blog_grid_wrap_start', 10);
    		add_action('whitedot_blog_home_pagination','whitedot_blog_grid_wrap_end', 5);
        } elseif ( is_front_page() && is_home() ) {
            add_action('whitedot_home_loop_before','whitedot_blog_grid_wrap_start', 10);
            add_action('whitedot_blog_home_pagination','whitedot_blog_grid_wrap_end', 5);
        } elseif ( is_category() ){
            add_action('whitedot_archive_loop_before','whitedot_blog_grid_wrap_start', 10);
            add_action('whitedot_blog_home_pagination','whitedot_blog_grid_wrap_end', 5);
        }
	}
}

/**
 * Whitedot blog grid wrap start
 *
 * @since 1.0.0
 */
function whitedot_blog_grid_wrap_start(){

    $gridColumn = get_theme_mod('whitedot_blog_home_grid_culmn', 2 );

	?>
		<div class="wd-blog-grid col-<?php echo esc_attr( $gridColumn ) ?>">
	<?php

}

/**
 * Whitedot blog grid wrap end
 *
 * @since 1.0.0
 */
function whitedot_blog_grid_wrap_end(){

	?>
		</div><!-- .wd-blog-grid -->
	<?php

}

/**
 * Display Author Box in Single Post
 *
 * @since 1.0.0
 */
add_action('template_redirect', 'whitedot_display_authorbox');

function whitedot_display_authorbox()
{
  if ( 1 === get_theme_mod( 'whitedot_show_authorbox_in_singlepost', 1 ) ) {
    add_action('whitedot_main_single_content_after','whitedot_post_author', 10);
  }
}


/**
 * Whitedot Customizer Google Fonts
 *
 * @since 1.0.0
 */
function whitedot_customizer_google_fonts() {

	if( 'font-2' === get_theme_mod( 'whitedot_google_fonts' ) ) {  

        wp_enqueue_style( 'whitedot-google-font-ABeeZee', 'https://fonts.googleapis.com/css?family=ABeeZee:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-3' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Abel', 'https://fonts.googleapis.com/css?family=Abel:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-4' === get_theme_mod( 'whitedot_google_fonts' ) ) {

        wp_enqueue_style( 'whitedot-google-font-Actor', 'https://fonts.googleapis.com/css?family=Actor:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-5' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Advent-Pro', 'https://fonts.googleapis.com/css?family=Advent+Pro:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-6' === get_theme_mod( 'whitedot_google_fonts' ) ) {

        wp_enqueue_style( 'whitedot-google-font-Anaheim', 'https://fonts.googleapis.com/css?family=Anaheim:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-7' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Andada', 'https://fonts.googleapis.com/css?family=Andada:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-7-1' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Alfa-Slab-One', 'https://fonts.googleapis.com/css?family=Alfa+Slab+One:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-8' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Bad-Script', 'https://fonts.googleapis.com/css?family=Bad+Script:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-9' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Barlow', 'https://fonts.googleapis.com/css?family=Barlow:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-10' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Bellefair', 'https://fonts.googleapis.com/css?family=Bellefair:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-11' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-BenchNine', 'https://fonts.googleapis.com/css?family=BenchNine:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-12' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Bubbler-One', 'https://fonts.googleapis.com/css?family=Bubbler+One:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-13' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Cabin', 'https://fonts.googleapis.com/css?family=Cabin:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-14' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Cairo', 'https://fonts.googleapis.com/css?family=Cairo:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-15' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Capriola', 'https://fonts.googleapis.com/css?family=Capriola:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-16' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Catamaran', 'https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-17' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Chathura', 'https://fonts.googleapis.com/css?family=Chathura:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-18' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Delius', 'https://fonts.googleapis.com/css?family=Delius:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-19' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Delius-Swash-Caps', 'https://fonts.googleapis.com/css?family=Delius+Swash+Caps:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-20' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Didact-Gothic', 'https://fonts.googleapis.com/css?family=Didact+Gothic:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-21' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Dosis', 'https://fonts.googleapis.com/css?family=Dosis:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-21-2' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Mr-Dafoe', 'https://fonts.googleapis.com/css?family=Mr+Dafoe:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-22' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-EB-Garamond', 'https://fonts.googleapis.com/css?family=EB+Garamond:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-23' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Economica', 'https://fonts.googleapis.com/css?family=Economica:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-24' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-El-Messiri', 'https://fonts.googleapis.com/css?family=El+Messiri:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-25' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Electrolize', 'https://fonts.googleapis.com/css?family=Electrolize:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-26' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Encode-Sans', 'https://fonts.googleapis.com/css?family=Encode+Sans:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-27' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Encode-Sans-Condensed', 'https://fonts.googleapis.com/css?family=Encode+Sans+Condensed:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-28' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Encode-Sans-Expanded', 'https://fonts.googleapis.com/css?family=Encode+Sans+Expanded:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-29' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Englebert', 'https://fonts.googleapis.com/css?family=Englebert:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-30' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Enriqueta', 'https://fonts.googleapis.com/css?family=Enriqueta:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-31' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Esteban', 'https://fonts.googleapis.com/css?family=Esteban:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-32' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Exo', 'https://fonts.googleapis.com/css?family=Exo:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-33' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Expletus-Sans', 'https://fonts.googleapis.com/css?family=Expletus+Sans:100,200,300,400,500,600,700,800,900', false );

    }elseif ('font-34' === get_theme_mod( 'whitedot_google_fonts' ) ) { 

        wp_enqueue_style( 'whitedot-google-font-Josefin-Slab', 'https://fonts.googleapis.com/css?family=Josefin+Slab:100,200,300,400,500,600,700,800,900', false );

    }else{
        wp_enqueue_style( 'whitedot_google_fonts', 'https://fonts.googleapis.com/css?family=Varela+Round:100,200,300,400,500,600,700,800,900', false ); 
    }

}


/**
 * Whitedot Show Footer Branding
 *
 * @since 1.0.0
 */
add_action('template_redirect', 'whitedot_display_footer_branding');
function whitedot_display_footer_branding()
{
    if ( 0 == get_theme_mod( 'whitedot_show_footer_branding', 1 ) ) { 

    }else{

        add_action('whitedot_footer_content','whitedot_footer_branding', 10);
    }
}


/**
 * Whitedot Show Footer Social Icons
 *
 * @since 1.0.0
 */
add_action('template_redirect', 'whitedot_display_footer_social_icons');
function whitedot_display_footer_social_icons()
{
    if ( 1 == get_theme_mod( 'whitedot_show_footer_social_icons', 0 ) ) { 
        
        add_action('whitedot_footer_branding_content','whitedot_social_links', 30);
    }
}


/**
 * Whitedot Blog Pagination Style
 *
 * @since 1.0.0
 */
add_action('template_redirect', 'whitedot_customizer_pagination_style');
function whitedot_customizer_pagination_style()
{
    if( 'next-prev' === get_theme_mod( 'whitedot_blog_home_pagination_style' ) ) { 

        remove_action( 'whitedot_blog_home_pagination', 'whitedot_blog_home_number_pagination', 10 );
        add_action( 'whitedot_blog_home_pagination', 'whitedot_blog_home_icon_pagination', 10 );
    }else{
        add_action( 'whitedot_blog_home_pagination', 'whitedot_blog_home_number_pagination', 10 );
        remove_action( 'whitedot_blog_home_pagination', 'whitedot_blog_home_icon_pagination', 10 );
    }
}

/**
 * LifterLMS Dashboard sidebar layout
 *
 * @since 1.0.0
 */
add_action('lifterlms_before_student_dashboard_content', 'whitedot_llms_dashboard_custom_css', 1);
function whitedot_llms_dashboard_custom_css()
{
   do_action( 'whitedot_dashboard_style' );
}

/**
 * LifterLMS Dashboard sidebar layout
 *
 * @since 1.0.0
 */
add_action('whitedot_dashboard_style', 'whitedot_llms_dashboard_sidebar_css', 1);
function whitedot_llms_dashboard_sidebar_css()
{
   ?>
   <style type="text/css">
        <?php if( 'sidebarleft' === get_theme_mod( 'whitedot_llms_dashboard_sidebar_layout' ) ) { ?> 

        @media (min-width: 768px){
            .has-sidebar #primary {
                float: right!important;
                width: 68%;
            }

            .has-sidebar .secondary {
                float: left!important;
                display: block;
            }
        }

        <?php } elseif( 'sidebarright' === get_theme_mod( 'whitedot_llms_dashboard_sidebar_layout' ) ) { ?> 

        @media (min-width: 768px){
            .has-sidebar #primary {
                float: left!important;
                width: 68%;
            }

            .has-sidebar .secondary {
                float: right!important;
                display: block;
            }
        }

        <?php } else { ?> 

            .has-sidebar #primary {
                float: none!important;
                width: 100%!important;
            }

            .has-sidebar .secondary {
                display: none;
            }

        <?php } ?>
   </style>
   <?php
}

/**
 * Display Header Search Button
 *
 * @since 1.0.93
 */
add_action('template_redirect', 'whitedot_display_header_search');

function whitedot_display_header_search()
{
  if ( 0 == get_theme_mod( 'whitedot_show_search_in_header', 1 ) ) { 
    remove_action('whitedot_after_header_navigation','whitedot_header_search_bar', 20);
    remove_action('whitedot_header_content_after','whitedot_mob_header_search_bar', 20);
  }else{
    add_action('whitedot_after_header_navigation','whitedot_header_search_bar', 20);
    add_action('whitedot_header_content_after','whitedot_mob_header_search_bar', 20);
  }
}

/**
 * Display Back to Top Button
 *
 * @since 1.1.02
 */
add_action('template_redirect', 'whitedot_display_backtotop');

function whitedot_display_backtotop()
{
  if ( 1 === get_theme_mod( 'whitedot_show_footer_backtotop', 0 ) ) {
    add_action('whitedot_after_footer','whitedot_gototop_btn', 10);
  }
}

/**
 * Display page container layout
 *
 * @since 1.2.3
 */
function whitedot_single_page_container_layout() {

    if ( is_page() ) {

        if ( whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Contained' || 'contained' === get_theme_mod( 'whitedot_page_container_layout', 'boxed' ) ){

            add_filter( 'body_class', 'whitedot_single_page_contained_layout_class' );

        }elseif ( whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Full Width (Page Builder)' ) {}else{

            add_filter( 'body_class', 'whitedot_single_page_boxed_layout_class' );
        }
        
    }

}
add_action( 'template_redirect', 'whitedot_single_page_container_layout' );

/**
 * Display single post container layout
 *
 * @since 1.2.3
 */
function whitedot_single_post_container_layout() {

    if ( is_single() ) {

        if ( whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Contained' || 'contained' === get_theme_mod( 'whitedot_single_blog_container_layout', 'boxed' ) ){

            add_filter( 'body_class', 'whitedot_single_post_contained_layout_class' );

        }elseif ( whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Full Width (Page Builder)' ) {}else{

            add_filter( 'body_class', 'whitedot_single_post_boxed_layout_class' );

        }
        
    }

}
add_action( 'template_redirect', 'whitedot_single_post_container_layout' );

function whitedot_post_archive_container_layout() {

    if ( is_front_page() && is_home() ) {

        if ( whitedot_settings_get_meta( 'whitedot_settings_content_layout' ) === 'Contained' ){

            add_filter( 'body_class', 'whitedot_post_archive_contained_layout_class' );

        }else{

            add_filter( 'body_class', 'whitedot_post_archive_boxed_layout_class' );

        }
        
    }

}
add_action( 'template_redirect', 'whitedot_post_archive_container_layout' );


function whitedot_single_page_contained_layout_class( $classes ) {
    $classes[] = 'whitedot-page-contained';

    return $classes;
}

function whitedot_single_page_boxed_layout_class( $classes ) {
    $classes[] = 'whitedot-page-boxed';

    return $classes;
}

function whitedot_single_post_contained_layout_class( $classes ) {
    $classes[] = 'whitedot-post-contained';

    return $classes;
}

function whitedot_single_post_boxed_layout_class( $classes ) {
    $classes[] = 'whitedot-post-boxed';

    return $classes;
}

function whitedot_post_archive_contained_layout_class( $classes ) {
    $classes[] = 'whitedot-archive-contained';

    return $classes;
}

function whitedot_post_archive_boxed_layout_class( $classes ) {
    $classes[] = 'whitedot-archive-boxed';

    return $classes;
}






