<?php
/**
 * Whitedot Setup
 */

if ( ! function_exists( 'whitedot_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function whitedot_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on WhiteDot, use a find and replace
		 * to change 'whitedot' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'whitedot', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'whitedot' ),
		) );

		// Social Media Link Icons
		register_nav_menus( array(
			'social-icons' => esc_html__( 'Social Icons', 'whitedot' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/*
		 * Set up the WordPress core custom header feature.
		 */
		add_theme_support( 'custom-header', apply_filters( 'whitedot_custom_header_args', array(
			'width'                  => 1000,
			'height'                 => 200,
			'flex-height'            => true,
			'flex-width'             => true,
			'header-text'			 => false
		) ) );


		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'whitedot_custom_background_args', array(
			'default-color' => 'e9e9e9',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 200,
			'width'       => 800,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;


/**
 * Registers an editor stylesheet for the theme.
 */
function whitedot_add_editor_styles() {
    add_editor_style('/css/editor-style.css');
}
add_action( 'after_setup_theme', 'whitedot_add_editor_styles' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function whitedot_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'whitedot_content_width', 640 );
}


/**
 * Register main widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function whitedot_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'whitedot' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Displays on the side of pages and posts with a sidebar.', 'whitedot' ),
		'before_widget' => '<div div id="%1$s" class="wd-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="wd-widget-heading">',
		'after_title'   => '</h2>',
	) );
}


/**
 * Register footer widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function whitedot_footer_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widgets', 'whitedot' ),
		'id'            => 'sidebar-footer',
		'description'   => esc_html__( 'Displays widgets in the footer of pages and posts.', 'whitedot' ),
		'before_widget' => '<div div id="%1$s" class="wd-footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="wd-footer-widget-heading">',
		'after_title'   => '</h2>',
	) );
}


/**
 * Register cart widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function whitedot_woo_product_filter_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Product Filter Widgets', 'whitedot' ),
		'id'            => 'whitedot-product-filter',
		'description'   => esc_html__( 'This widget displays Product Filter on your shop page. You can add Woocommerce product filter widgets here.', 'whitedot' ),
		'before_widget' => '<div div id="%1$s" class="wd-product-filter %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="wd-product-filter-heading">',
		'after_title'   => '</h2>',
	) );
}


/**
 * Adds a responsive embed wrapper around oEmbed content
 *
 * @param  string $html The oEmbed markup.
 * @param  string $url The URL being embedded.
 * @param  array  $attr An array of attributes.
 *
 * @return string       Updated embed markup.
 */
function responsive_oembed_wrapper( $html, $url, $attr ) {

	$add_whitedot_oembed_wrapper = apply_filters( 'whitedot_responsive_oembed_wrapper_enable', true );

	$allowed_providers = apply_filters(
		'whitedot_allowed_fullwidth_oembed_providers', array(
			'vimeo.com',
			'youtube.com',
			'youtu.be',
			'wistia.com',
			'wistia.net',
			'dailymotion.com',
		)
	);


	if ( $add_whitedot_oembed_wrapper ) {
		$html = ( '' !== $html ) ? '<div class="embed-container">' . $html . '</div>' : '';
	}


	return $html;
}

add_filter( 'embed_oembed_html', 'responsive_oembed_wrapper' , 10, 3 );


/**
 * Adds Default Social Menu for the theme on activation.
 *
 * @since 1.0.5
 *
 */
function social_nav_menu_item( $term_id, $title, $url ) {
    
    wp_update_nav_menu_item($term_id, 0, array(
        'menu-item-title'   =>  $title,
        'menu-item-url'     =>  $url, 
        'menu-item-status'  =>  'publish'
    ) );
    
}

function whitedot_generate_site_nav_menu( $menu_name, $menu_items_array, $location_target ) {
    
    $menu_social = $menu_name;
    wp_create_nav_menu( $menu_social );
    $menu_social_obj = get_term_by( 'name', $menu_social, 'nav_menu' );
    
    foreach( $menu_items_array as $page_name => $page_location ){
        social_nav_menu_item( $menu_social_obj->term_id, $page_name, $page_location );
    }
    
    $menu_social_arr = get_theme_mod( 'nav_menu_locations' );
    $menu_social_arr[$location_target] = $menu_social_obj->term_id;
    set_theme_mod( 'nav_menu_locations', $menu_social_arr );
        
    update_option( 'menu_check', true );
    
}


// Runs when user switches the theme
function whitedot_after_switch_theme() {
	
	//Creating Default Social Navigation   
    $run_menu_maker_once = get_option('menu_check');

    if ( ! $run_menu_maker_once ){
            
        $social_icon_items = array(
            'Facebook'		=>	'https://facebook.com',
            'Twitter'		=>	'https://twitter.com',        
            'Instagram'		=>	'https://instagram.com',
            'Google +'		=>	'https://plus.gooogle.com',
            'Pintrest'		=>	'https://pinterest.ca',
            'Youtube'		=>	'https://youtube.com' 
        );
        whitedot_generate_site_nav_menu( 'Social Icons', $social_icon_items, 'social-icons' );
        
    }
}
add_action( 'after_switch_theme', 'whitedot_after_switch_theme');

//Fallback Menu Page
function fallback_menu_pages() {
    
    $list_pages = '';
    $args = array(
        'sort_order' => 'asc',
        'sort_column' => 'post_title',
        'hierarchical' => 1,
        'child_of' => 0,
        'parent' => -1,
        'offset' => 0,
        'number' => 5,
        'post_type' => 'page',
        'post_status' => 'publish'
    );  
    $pages = get_pages( $args );
        
    foreach( $pages as $key => $page ){
        $list_pages .= '<li><a href = "' . get_permalink( $page->ID ) . '">' . $page->post_title . '</a></li>';
    }
    
    echo esc_attr( $list_pages );
    
}

function whitedot_notice_theme_install_1_1_07_wd() {
	$admin_page = get_current_screen();
    $users_id = get_current_user_id();
    if ( !class_exists( 'Whitedot_Designer' ) ) {
	    if ( !get_user_meta( $users_id, 'whitedot_notice_dismissed_theme_install_1_1_07_wd' ) && $admin_page->base != "appearance_page_whitedot-settings") {
	    	?>
			<div class="notice notice-success whitedot-notice premium">
				<img src="https://res.cloudinary.com/zeetheme/image/upload/v1543600015/WhiteDot%20Addons/WhiteDot_Designer_logo.png">
				<div class="notice-content">
					<p><b><?php _e( 'Start your 7-day free trial for WhiteDot Premium Add-on - WHITEDOT DESIGNER. No credit card is required!! Just Download and install the plugin and activate your free trial. The trial gives you access to all the premium features that you would get as a paying customer.', 'whitedot' ); ?></b>
					<div>
						<a class="whitedot-notice-btn" target="_blank" href="https://zeetheme.com/whitedot-designer-trial/">Start Your Free Trial</a><br>
						<a class="whitedot-notice-btn" target="_blank" href="https://zeetheme.com/plugins/whitedot-designer/">See all Features of WhiteDot Designer</a>
					</div>
					<a href="?whitedot-dismissed-theme-install_1_1_07_wd">
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">Dismiss this notice.</span>
						</button>
					</a>
				</div>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'whitedot_notice_theme_install_1_1_07_wd' );

function whitedot_notice_dismissed_theme_install_1_1_07_wd() {
    $users_id = get_current_user_id();
    if ( isset( $_GET['whitedot-dismissed-theme-install_1_1_07_wd'] ) )
        add_user_meta( $users_id, 'whitedot_notice_dismissed_theme_install_1_1_07_wd', 'true', true );
}
add_action( 'admin_init', 'whitedot_notice_dismissed_theme_install_1_1_07_wd' );


function whitedot_whats_new_notice_dismissed_1_0_2() {
    $users_id = get_current_user_id();
    if ( isset( $_GET['whitedot-whatsnew-notice-dismissed-1-0-2'] ) )
        add_user_meta( $users_id, 'whitedot_whats_new_notice_dismissed_1_0_2', 'true', true );
}
add_action( 'admin_init', 'whitedot_whats_new_notice_dismissed_1_0_2' );



add_action( 'admin_init', 'whitedot_theme_activation_redirect' );
 
function whitedot_theme_activation_redirect() {
    global $pagenow;
    if ( "themes.php" == $pagenow && is_admin() && isset( $_GET['activated'] ) ) {
        wp_redirect('themes.php?page=whitedot-settings');
    }
}







