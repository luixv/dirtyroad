<?php

/**
 * Square functions and definitions.
 *
 * @package Square
 */
if (!defined('SQUARE_VERSION')) {
    $square_get_theme = wp_get_theme();
    $square_version = $square_get_theme->Version;
    define('SQUARE_VERSION', $square_version);
}


if (!function_exists('square_setup')) :

//Sets up theme defaults and registers support for various WordPress features.

    function square_setup() {
        // Make theme available for translation.
        load_theme_textdomain('square', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        //Let WordPress manage the document title.
        add_theme_support('title-tag');

        //Support for woocommerce
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        //Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');
        add_image_size('square-about-thumb', 400, 420, true);
        add_image_size('square-blog-thumb', 800, 420, true);

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'square'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        // Set up the WordPress core custom background feature.
        add_theme_support('custom-background', apply_filters('square_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        add_theme_support('custom-logo', array(
            'height' => 60,
            'width' => 300,
            'flex-height' => true,
            'flex-width' => true,
            'header-text' => array('.sq-site-title', '.sq-site-description'),
        ));

        // Add support for Block Styles.
        add_theme_support('wp-block-styles');

        // Add support for full and wide align images.
        add_theme_support('align-wide');

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for responsive embedded content.
        add_theme_support('responsive-embeds');

        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, icons, and column width.
         */
        add_editor_style(array('css/editor-style.css', square_fonts_url()));
    }

endif; // square_setup
add_action('after_setup_theme', 'square_setup');

function square_content_width() {
    $GLOBALS['content_width'] = apply_filters('square_content_width', 800);
}

add_action('after_setup_theme', 'square_content_width', 0);

//Enables the Excerpt meta box in Page edit screen.
function square_add_excerpt_support_for_pages() {
    add_post_type_support('page', 'excerpt');
}

add_action('init', 'square_add_excerpt_support_for_pages');

//If Custom Logo is uploaded, remove the backward compatibility for header image
function square_remove_header_image() {
    $custom_logo_enabled = get_theme_mod('square_custom_logo_enabled', false);
    if (!$custom_logo_enabled && has_custom_logo()) {
        set_theme_mod('square_custom_logo_enabled', true);
        set_theme_mod('header_image', '');
    }
}

add_action('init', 'square_remove_header_image');

//Register widget area.
function square_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Right Sidebar', 'square'),
        'id' => 'square-right-sidebar',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Left Sidebar', 'square'),
        'id' => 'square-left-sidebar',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Shop Sidebar', 'square'),
        'id' => 'square-shop-sidebar',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 1', 'square'),
        'id' => 'square-footer1',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h5 class="widget-title">',
        'after_title' => '</h5>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 2', 'square'),
        'id' => 'square-footer2',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h5 class="widget-title">',
        'after_title' => '</h5>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 3', 'square'),
        'id' => 'square-footer3',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h5 class="widget-title">',
        'after_title' => '</h5>',
    ));

    register_sidebar(array(
        'name' => esc_html__('Footer 4', 'square'),
        'id' => 'square-footer4',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h5 class="widget-title">',
        'after_title' => '</h5>',
    ));

    register_sidebar(array(
        'name' => esc_html__('About Footer', 'square'),
        'id' => 'square-about-footer',
        'description' => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h5 class="widget-title">',
        'after_title' => '</h5>',
    ));
}

add_action('widgets_init', 'square_widgets_init');

if (!function_exists('square_fonts_url')) :

    /**
     * Register Google fonts for Square.
     *
     * @since Square 1.0
     *
     * @return string Google fonts URL for the theme.
     */
    function square_fonts_url() {
        $fonts_url = '';
        $fonts = array();
        $subsets = 'latin,latin-ext';

        /*
         * Translators: If there are characters in your language that are not supported
         * by Open Sans, translate this to 'off'. Do not translate into your own language.
         */
        if ('off' !== _x('on', 'Open Sans font: on or off', 'square')) {
            $fonts[] = 'Open Sans:400,300,600,700';
        }

        /*
         * Translators: If there are characters in your language that are not supported
         * by Inconsolata, translate this to 'off'. Do not translate into your own language.
         */
        if ('off' !== _x('on', 'Roboto Condensed font: on or off', 'square')) {
            $fonts[] = 'Roboto Condensed:300italic,400italic,700italic,400,300,700';
        }

        /*
         * Translators: To add an additional character subset specific to your language,
         * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
         */
        $subset = _x('no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'square');

        if ('cyrillic' == $subset) {
            $subsets .= ',cyrillic,cyrillic-ext';
        } elseif ('greek' == $subset) {
            $subsets .= ',greek,greek-ext';
        } elseif ('devanagari' == $subset) {
            $subsets .= ',devanagari';
        } elseif ('vietnamese' == $subset) {
            $subsets .= ',vietnamese';
        }

        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urlencode(implode('|', $fonts)),
                'subset' => urlencode($subsets),
                'display' => 'swap'
                    ), '//fonts.googleapis.com/css');
        }

        return $fonts_url;
    }

endif;

/**
 * Enqueue scripts and styles.
 */
function square_scripts() {
    wp_enqueue_script('modernizr', get_template_directory_uri() . '/js/modernizr.js', array(), SQUARE_VERSION, true);
    wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/js/owl.carousel.js', array('jquery'), SQUARE_VERSION, true);
    wp_enqueue_script('jquery-superfish', get_template_directory_uri() . '/js/jquery.superfish.js', array('jquery'), SQUARE_VERSION, true);

    if (is_page_template('templates/home-template.php') || is_front_page()) {
        wp_enqueue_script('square-draggabilly', get_template_directory_uri() . '/js/draggabilly.pkgd.min.js', array('jquery'), SQUARE_VERSION, true);
        wp_enqueue_script('square-elastiStack', get_template_directory_uri() . '/js/elastiStack.js', array('jquery'), SQUARE_VERSION, true);
    }

    wp_enqueue_script('square-custom', get_template_directory_uri() . '/js/square-custom.js', array('jquery'), SQUARE_VERSION, true);

    wp_enqueue_style('square-fonts', square_fonts_url(), array(), NULL);
    wp_enqueue_style('animate', get_template_directory_uri() . '/css/animate.css', array(), SQUARE_VERSION);
    wp_enqueue_style('font-awesome-4.7.0', get_template_directory_uri() . '/css/font-awesome-4.7.0.css', array(), SQUARE_VERSION);
    wp_enqueue_style('font-awesome-5.2.0', get_template_directory_uri() . '/css/font-awesome-5.2.0.css', array(), SQUARE_VERSION);
    wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), SQUARE_VERSION);
    wp_enqueue_style('square-style', get_stylesheet_uri(), array(), SQUARE_VERSION);
    wp_add_inline_style('square-style', square_dymanic_styles());

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'square_scripts');

/**
 * Enqueue admin style
 */
function square_admin_scripts() {
    wp_enqueue_media();
    wp_enqueue_style('square-admin-style', get_template_directory_uri() . '/inc/css/admin-style.css', array(), SQUARE_VERSION);
    wp_enqueue_script('square-admin-scripts', get_template_directory_uri() . '/inc/js/admin-scripts.js', array('jquery'), SQUARE_VERSION, true);
}

add_action('admin_enqueue_scripts', 'square_admin_scripts');
add_action('elementor/editor/before_enqueue_scripts', 'square_admin_scripts');

if (!function_exists('wp_body_open')) {

    function wp_body_open() {
        do_action('wp_body_open');
    }

}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/square-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Woocommerce additions
 */
require get_template_directory() . '/inc/woo-functions.php';

/**
 * Load Custom Metabox
 */
require get_template_directory() . '/inc/square-metabox.php';

/**
 * Welcome Page.
 */
require get_template_directory() . '/welcome/welcome.php';

/**
 * Dynamic Styles additions.
 */
require get_template_directory() . '/inc/style.php';
/**
 * Widgets additions.
 */
require get_template_directory() . '/inc/widgets/widget-fields.php';
require get_template_directory() . '/inc/widgets/widget-contact-info.php';
require get_template_directory() . '/inc/widgets/widget-personal-info.php';
require get_template_directory() . '/inc/widgets/widget-latest-post.php';
