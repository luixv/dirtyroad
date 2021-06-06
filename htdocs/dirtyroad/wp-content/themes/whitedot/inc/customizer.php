<?php
/**
 * WhiteDot Theme Customizer
 *
 * @package WhiteDot
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function whitedot_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_setting('custom_logo')->transport =  'refresh';
  
	//Background
  $wp_customize->get_control( 'background_color' )->section   = 'background_image';
  $wp_customize->get_section( 'background_image' )->title     = __( 'Background', 'whitedot' );
  $wp_customize->get_section( 'background_image' )->panel     = 'whitedot_color_settings_panel';

  $wp_customize->add_setting( 'whitedot_contained_layout_background_color' , array(
      'transport'  => 'refresh',
      'default'    =>  '#fcfcfc',
      'sanitize_callback' => 'sanitize_hex_color',
      )
  );

  $wp_customize->add_control( 
      new WP_Customize_Color_Control( 
      $wp_customize, 
      'whitedot_contained_layout_background_color', 
      array(
          'label'      => __( 'Contained Layout Backgroung Color', 'whitedot' ),
          'section'    => 'background_image'
      ) ) 
  );

  //Hide Tagline
  $wp_customize->add_setting( 'whitedot_hide_tagline' , array(
        'transport' => 'refresh',
        'default'    =>  '',
        'sanitize_callback' => 'whitedot_sanitize_checkbox',
        
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'whitedot_hide_tagline',
            array(
                'label'          => __( 'Hide Tagline', 'whitedot' ),
                'section'        => 'title_tagline',
                'type'           => 'checkbox',
                
            )
        )
    );

	//Header Section

    if ( class_exists( 'Whitedot_Designer' ) ) {
      $wp_customize->add_section( 'whitedot_header_settings_section' , array(
          'title'      => __('Main Header','whitedot'),
          'priority'   => 10,
          'panel'      => 'whitedot_header_settings_panel'
      ) );
    }else{
      $wp_customize->add_section( 'whitedot_header_settings_section' , array(
          'title'      => __('Header Settings','whitedot'),
          'priority'   => 20,
      ) );
    }

    $wp_customize->get_control( 'header_image' )->section   = 'whitedot_header_settings_section';

    $wp_customize->add_setting( 'header_styles' , array(
        'default' => 'style-1',
        'sanitize_callback' => 'whitedot_sanitize_choice',
        
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'header_styles',
            array(
                'label'          => __( 'Header Styles', 'whitedot' ),
                'section'        => 'whitedot_header_settings_section',
                'settings'       => 'header_styles',
                'type'           => 'select',
                'choices'        => array(
                    'style-1'      => 'Default',
                    'style-2'       => 'Centered'
                )
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_show_search_in_header',
       array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_switch_sanitization'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_search_in_header',
       array(
          'label' => esc_html__( 'Header Search', 'whitedot' ),
          'section' => 'whitedot_header_settings_section'
       )
    ) );

    if ( class_exists( 'Whitedot_Designer' ) ) {
      if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) {
        $wp_customize->add_setting( 'header_text_color' , array(
            'transport'  => 'postMessage',
            'default'    =>  '#666',
            'sanitize_callback' => 'sanitize_hex_color',
            
            )
        );

        $wp_customize->add_control( 
            new WP_Customize_Color_Control( 
            $wp_customize, 
            'header_text_color', 
            array(
                'label'      => __( 'Primary Menu Color', 'whitedot' ),
                'section'    => 'whitedot_header_settings_section',
                'priority' => 10,
                'section'    => 'whitedot_header_color_section',
            ) ) 
        );
      }else{
        $wp_customize->add_setting( 'header_text_color' , array(
            'transport'  => 'postMessage',
            'default'    =>  '#666',
            'sanitize_callback' => 'sanitize_hex_color',
            
            )
        );

        $wp_customize->add_control( 
            new WP_Customize_Color_Control( 
            $wp_customize, 
            'header_text_color', 
            array(
                'label'      => __( 'Header Text Color', 'whitedot' ),
                'section'    => 'whitedot_header_settings_section',
                'settings'   => 'header_text_color',
            ) ) 
        );
      }
    }else{
      $wp_customize->add_setting( 'header_text_color' , array(
          'transport'  => 'postMessage',
          'default'    =>  '#666',
          'sanitize_callback' => 'sanitize_hex_color',
          
          )
      );

      $wp_customize->add_control( 
          new WP_Customize_Color_Control( 
          $wp_customize, 
          'header_text_color', 
          array(
              'label'      => __( 'Header Text Color', 'whitedot' ),
              'section'    => 'whitedot_header_settings_section',
              'settings'   => 'header_text_color',
          ) ) 
      );
    }

    if ( !class_exists( 'Whitedot_Designer' ) ) {
      $wp_customize->add_setting( 'whitedot_buy_premium_header',
             array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text'
             )
          );
           
      $wp_customize->add_control( new WhiteDot_Upgrade_Notice_Custom_Control( $wp_customize, 'whitedot_buy_premium_header',
         array(
            'label' => __( 'Try WhiteDot Premium', 'whitedot' ),
            'description' => __( 'There are a lot of awesome customization optons in our premium add-on - WHITEDOT DESIGNER. - <ul><li>Sticky Header</li><li>Transparent Header</li><li>Above Header Bar</li><li>Color Options</li><li>Social Icons</li><li>Mobile Header Customization</li><li>Mobile Hamburger Animation</li></ul> and a lot more.... Start your 7 days Free Trial (No Credit Card Required) and unlock all the premium features now.', 'whitedot' ),
            'section' => 'whitedot_header_settings_section',
            'priority'     => 200,
         )
      ) );
    }

    //Color Settings

    $wp_customize->add_panel( 'whitedot_color_settings_panel' , array(
        'priority' => 40,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => __( 'Colors & Backgrounds', 'whitedot' ),
        )
    );

    if ( class_exists( 'Whitedot_Designer' ) ) {

      if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) {

        $wp_customize->add_section( 'whitedot_color_settings_section' , array(
            'title'      => __('Main Color Palette','whitedot'),
            'priority'   => 1,
            'panel'      => 'whitedot_color_settings_panel'
        ) );

      }else{

        $wp_customize->add_section( 'whitedot_color_settings_section' , array(
            'title'      => __('Color Options','whitedot'),
            'priority'   => 1,
            'panel'      => 'whitedot_color_settings_panel'
        ) );

      }

    }else{
      $wp_customize->add_section( 'whitedot_color_settings_section' , array(
          'title'      => __('Color Options','whitedot'),
          'priority'   => 1,
          'panel'      => 'whitedot_color_settings_panel'
      ) );
    }

    $wp_customize->add_setting( 'whitedot_body_text_color' , array(
        'transport'  => 'postMessage',
        'default'    =>  '#333',
        'sanitize_callback' => 'sanitize_hex_color',
        
        )
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
        $wp_customize, 
        'whitedot_body_text_color', 
        array(
            'label'      => __( 'Text Color', 'whitedot' ),
            'section'    => 'whitedot_color_settings_section',
        ) ) 
    );

    $wp_customize->add_setting( 'whitedot_header_color' , array(
        'transport'  => 'postMessage',
        'default'    =>  '#777',
        'sanitize_callback' => 'sanitize_hex_color',
        
        )
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
        $wp_customize, 
        'whitedot_header_color', 
        array(
            'label'      => __( 'Header Color', 'whitedot' ),
            'section'    => 'whitedot_color_settings_section',
        ) ) 
    );

    $wp_customize->add_setting( 'whitedot_link_color' , array(
        'transport'  => 'refresh',
        'default'    =>  '#e5554e',
        'sanitize_callback' => 'sanitize_hex_color',
        
        )
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
        $wp_customize, 
        'whitedot_link_color', 
        array(
            'label'      => __( 'Link Color', 'whitedot' ),
            'section'    => 'whitedot_color_settings_section',
        ) ) 
    );

    $wp_customize->add_setting( 'whitedot_link_hover_color' , array(
        'transport'  => 'refresh',
        'default'    =>  '#ce4039',
        'sanitize_callback' => 'sanitize_hex_color',
        
        )
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
        $wp_customize, 
        'whitedot_link_hover_color', 
        array(
            'label'      => __( 'Link Hover Color', 'whitedot' ),
            'section'    => 'whitedot_color_settings_section',
        ) ) 
    );

    if ( !class_exists( 'Whitedot_Designer' ) ) {
      $wp_customize->add_setting( 'whitedot_buy_premium_color',
             array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text'
             )
          );
           
      $wp_customize->add_control( new WhiteDot_Upgrade_Notice_Custom_Control( $wp_customize, 'whitedot_buy_premium_color',
         array(
            'label' => __( 'Try WhiteDot Premium', 'whitedot' ),
            'description' => __( 'There are a lot more color optons in our premium add-on - WHITEDOT DESIGNER. Start your 7 days Free Trial (No Credit Card Required) and unlock all the premium features now.', 'whitedot' ),
            'section' => 'whitedot_color_settings_section',
            'priority'     => 200,
         )
      ) );
    }

    //Container Layout
    $wp_customize->add_section( 'whitedot_container_layout_section' , array(
        'title'      => __('Container Layout','whitedot'),
        'priority'   => 20,
    ) );

    $wp_customize->add_setting( 'whitedot_outer_container_width',
       array(
          'default' => 1200,
          'transport' => 'postMessage',
          'sanitize_callback' => 'whitedot_sanitize_integer'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_outer_container_width',
       array(
          'label' => esc_html__( 'Container Width', 'whitedot' ),
          'section' => 'whitedot_container_layout_section',
          'input_attrs' => array(
             'min' => 500, 
             'max' => 1500, 
             'step' => 1, 
          ),
       )
    ) );

    $wp_customize->add_setting( 'container_layout_page_title' , array(
        'sanitize_callback' => 'sanitize_text',
        )
    );

    $wp_customize->add_control(
        new whitedot_custom_title(
            $wp_customize,
            'container_layout_page_title',
            array(
                'label'          => __( 'Page', 'whitedot' ),
                'section'        => 'whitedot_container_layout_section',
                'type'           => 'custom-title'
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_page_inner_container_width',
       array(
          'default' => 30,
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_sanitize_integer'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_page_inner_container_width',
       array(
          'label' => esc_html__( 'Boxed Page Inner Container Width', 'whitedot' ),
          'section' => 'whitedot_container_layout_section',
          'input_attrs' => array(
             'min' => 30, 
             'max' => 300, 
             'step' => 1, 
          ),
       )
    ) );

    $wp_customize->add_setting( 'whitedot_page_container_layout',
         array(
            'default' => 'boxed',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_choice'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Text_Radio_Button_Custom_Control( $wp_customize, 'whitedot_page_container_layout',
         array(
            'label' => __( 'Container Layout', 'whitedot' ),
            'section' => 'whitedot_container_layout_section',
            'choices' => array(
               'boxed' => __( 'Boxed', 'whitedot' ), 
               'contained' => __( 'Contained', 'whitedot' )
            )
         )
      ) );

    $wp_customize->add_setting( 'container_layout_blog_home_title' , array(
        'sanitize_callback' => 'sanitize_text',
        )
    );

    $wp_customize->add_control(
        new whitedot_custom_title(
            $wp_customize,
            'container_layout_blog_home_title',
            array(
                'label'          => __( 'Blog Archive', 'whitedot' ),
                'section'        => 'whitedot_container_layout_section',
                'type'           => 'custom-title'
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_blog_home_container_layout',
         array(
            'default' => 'boxed',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_choice'
         )
      );
       
    $wp_customize->add_control( new WhiteDot_Text_Radio_Button_Custom_Control( $wp_customize, 'whitedot_blog_home_container_layout',
       array(
          'label' => __( 'Container Layout', 'whitedot' ),
          'section' => 'whitedot_container_layout_section',
          'choices' => array(
             'boxed' => __( 'Boxed', 'whitedot' ), 
             'contained' => __( 'Contained', 'whitedot' )
          )
       )
    ) );

    $wp_customize->add_setting( 'container_layout_single_blog_title' , array(
        'sanitize_callback' => 'sanitize_text',
        )
    );

    $wp_customize->add_control(
        new whitedot_custom_title(
            $wp_customize,
            'container_layout_single_blog_title',
            array(
                'label'          => __( 'Single Blog', 'whitedot' ),
                'section'        => 'whitedot_container_layout_section',
                'type'           => 'custom-title'
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_blog_inner_container_width',
       array(
          'default' => 50,
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_sanitize_integer'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_blog_inner_container_width',
       array(
          'label' => esc_html__( 'Single Blog Inner Container Width', 'whitedot' ),
          'section' => 'whitedot_container_layout_section',
          'input_attrs' => array(
             'min' => 0, 
             'max' => 300, 
             'step' => 1, 
          ),
       )
    ) );

    $wp_customize->add_setting( 'whitedot_single_blog_container_layout',
         array(
            'default' => 'boxed',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_choice'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Text_Radio_Button_Custom_Control( $wp_customize, 'whitedot_single_blog_container_layout',
         array(
            'label' => __( 'Container Layout', 'whitedot' ),
            'section' => 'whitedot_container_layout_section',
            'choices' => array(
               'boxed' => __( 'Boxed', 'whitedot' ), 
               'contained' => __( 'Contained', 'whitedot' )
            )
         )
      ) );

    //Typography

    if ( class_exists( 'Whitedot_Designer' ) ) {
      if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) {
          $wp_customize->add_section( 'whitedot_typography_settings_section' , array(
              'title'      => __('Base Typography','whitedot'),
              'priority'   => 10,
              'panel'      => 'whitedot_typography_settings_panel'
          ) );
      }else{
        $wp_customize->add_section( 'whitedot_typography_settings_section' , array(
            'title'      => __('Typography','whitedot'),
            'priority'   => 40,
        ) );
      }
    }else{
      $wp_customize->add_section( 'whitedot_typography_settings_section' , array(
          'title'      => __('Typography','whitedot'),
          'priority'   => 40,
      ) );
    }

    $wp_customize->add_setting( 'whitedot_google_fonts' , array(
        'default' => 'font-1',
        'sanitize_callback' => 'whitedot_sanitize_choice',
        
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'whitedot_google_fonts',
            array(
                'label'          => __( 'Font', 'whitedot' ),
                'section'        => 'whitedot_typography_settings_section',
                'settings'       => 'whitedot_google_fonts',
                'type'           => 'select',
                'choices'        => array(
                    'font-1'        => 'Default Font',
                    'font-2'        => 'ABeeZee',
                    'font-3'        => 'Abel',
                    'font-4'        => 'Actor',
                    'font-5'        => 'Advent Pro',
                    'font-6'        => 'Anaheim',
                    'font-7'        => 'Andada',
                    'font-7-1'      => 'Alfa Slab One',
                    'font-8'        => 'Bad Script',
                    'font-9'        => 'Barlow',
                    'font-10'       => 'Bellefair',
                    'font-11'       => 'BenchNine',
                    'font-12'       => 'Bubbler One',
                    'font-13'       => 'Cabin',
                    'font-14'       => 'Cairo',
                    'font-15'       => 'Capriola',
                    'font-16'       => 'Catamaran',
                    'font-17'       => 'Chathura',
                    'font-18'       => 'Delius',
                    'font-19'       => 'Delius Swash Caps',
                    'font-20'       => 'Didact Gothic',
                    'font-21'       => 'Dosis',
                    'font-21-2'     => 'Mr Dafoe',
                    'font-22'       => 'EB Garamond',
                    'font-23'       => 'Economica',
                    'font-24'       => 'El Messiri',
                    'font-25'       => 'Electrolize',
                    'font-26'       => 'Encode Sans',
                    'font-27'       => 'Encode Sans Condensed',
                    'font-28'       => 'Encode Sans Expanded',
                    'font-29'       => 'Englebert',
                    'font-30'       => 'Enriqueta',
                    'font-31'       => 'Esteban',
                    'font-32'       => 'Exo',
                    'font-33'       => 'Expletus Sans',
                    'font-34'       => 'Josefin Slab',
                    // 'font-35'       => 'Dosis',
                    // 'font-36'       => 'Dosis',
                    // 'font-37'       => 'Dosis',
                    // 'font-38'       => 'Dosis',
                    // 'font-39'       => 'Dosis',
                    // 'font-40'       => 'Dosis',
                    // 'font-41'       => 'Dosis',
                    // 'font-42'       => 'Dosis',
                    // 'font-43'       => 'Dosis',
                    // 'font-44'       => 'Dosis',
                    // 'font-45'       => 'Dosis',
                    // 'font-46'       => 'Dosis',
                    // 'font-47'       => 'Dosis',
                    // 'font-48'       => 'Dosis',
                    // 'font-49'       => 'Dosis',
                    // 'font-50'       => 'Dosis',
                    // 'font-51'       => 'Dosis',
                    // 'font-52'       => 'Dosis',
                    // 'font-53'       => 'Dosis',
                    // 'font-54'       => 'Dosis',
                    // 'font-55'       => 'Dosis',
                    // 'font-56'       => 'Dosis',
                    // 'font-57'       => 'Dosis',
                    // 'font-58'       => 'Dosis',
                    // 'font-59'       => 'Dosis',
                    // 'font-60'       => 'Dosis',
                )
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_body_text_font_size',
       array(
          'default' => 16,
          'transport' => 'postMessage',
          'sanitize_callback' => 'whitedot_sanitize_integer'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_body_text_font_size',
       array(
          'label' => esc_html__( 'Body Font Size', 'whitedot' ),
          'section' => 'whitedot_typography_settings_section',
          'input_attrs' => array(
             'min' => 1, 
             'max' => 25, 
             'step' => 1, 
          ),
       )
    ) );

    $wp_customize->add_setting( 'whitedot_body_text_line_height',
       array(
          'default' => 16,
          'transport' => 'postMessage',
          'sanitize_callback' => 'whitedot_sanitize_integer'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_body_text_line_height',
       array(
          'label' => esc_html__( 'Line Height', 'whitedot' ),
          'section' => 'whitedot_typography_settings_section',
          'input_attrs' => array(
             'min' => 10,
             'max' => 40,
             'step' => 1,
          ),
       )
    ) );

    if ( !class_exists( 'Whitedot_Designer' ) ) {
      $wp_customize->add_setting( 'whitedot_buy_premium_typography',
             array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text'
             )
          );
           
      $wp_customize->add_control( new WhiteDot_Upgrade_Notice_Custom_Control( $wp_customize, 'whitedot_buy_premium_typography',
         array(
            'label' => __( 'Try WhiteDot Premium', 'whitedot' ),
            'description' => __( 'There are a lot more Typography optons in our premium add-on - WHITEDOT DESIGNER. Start your 7 days Free Trial (No Credit Card Required) and unlock all the premium features now.', 'whitedot' ),
            'section' => 'whitedot_typography_settings_section',
            'priority'     => 200,
         )
      ) );
    }


    //SideBar Section
    $wp_customize->add_section( 'whitedot_sidebar_settings_section' , array(
        'title'      => __('Page','whitedot'),
        'priority'   => 50,
        'panel'        => 'whitedot_blog_panel',
    ) );

    $wp_customize->add_setting( 'whitedot_page_sidebar_layout',
       array(
          'default' => 'sidebarright',
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_page_sidebar_layout',
       array(
          'label' => __( 'Page Sidebar Layout', 'whitedot' ),
          'section' => 'whitedot_sidebar_settings_section',
          'choices' => array(
             'sidebarleft' => array(
                'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                'name' => __( 'Left Sidebar', 'whitedot' ) 
             ),
             'sidebarnone' => array(
                'image' => get_template_directory_uri() . '/img/fullwidth.png',
                'name' => __( 'No Sidebar', 'whitedot' )
             ),
             'sidebarright' => array(
                'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                'name' => __( 'Right Sidebar', 'whitedot' )
             )
          )
       )
    ) );

    $wp_customize->add_setting( 'whitedot_page_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_page_sidebar_width',
         array(
            'label' => esc_html__( 'Page Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_sidebar_settings_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

    /*////////////////////////////////////////////////////////////////////////
                                    Blog Panel                               
    ////////////////////////////////////////////////////////////////////////*/

    $wp_customize->add_panel( 'whitedot_blog_panel' , array(
        'priority' => 60,
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'title' => __( 'Blog & Page Settings', 'whitedot' ),
        
        )
    );

    //Blog Home/Archive
    $wp_customize->add_section( 'whitedot_blog_archive_section' , array(
        'title'      => __('Blog Home/Archive','whitedot'),
        'priority'   => 10,
        'panel'        => 'whitedot_blog_panel',
    ) );

    $wp_customize->add_setting( 'whitedot_blog_archive_sidebar_layout',
       array(
          'default' => 'sidebarright',
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_blog_archive_sidebar_layout',
       array(
          'label' => __( 'Sidebar Layout', 'whitedot' ),
          'section' => 'whitedot_blog_archive_section',
          'choices' => array(
             'sidebarleft' => array(  
                'image' =>  get_template_directory_uri() . '/img/left-sidebar.png',
                'name' => __( 'Left Sidebar', 'whitedot' ) //
             ),
             'sidebarnone' => array(
                'image' => get_template_directory_uri() . '/img/fullwidth.png',
                'name' => __( 'No Sidebar', 'whitedot' )
             ),
             'sidebarright' => array(
                'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                'name' => __( 'Right Sidebar', 'whitedot' )
             )
          )
       )
    ) );

    $wp_customize->add_setting( 'whitedot_blog_archive_sidebar_width',
       array(
          'default' => 30,
          'transport' => 'postMessage',
          'sanitize_callback' => 'whitedot_sanitize_integer'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_blog_archive_sidebar_width',
       array(
          'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
          'section' => 'whitedot_blog_archive_section',
          'input_attrs' => array(
             'min' => 20, 
             'max' => 50, 
             'step' => 1, 
          ),
       )
    ) );

    if ( class_exists( 'Whitedot_Designer' ) ) {
      if ( whitedot_designer()->is__premium_only() && whitedot_designer()->can_use_premium_code() ) {
     
        $wp_customize->add_setting( 'whitedot_blog_home_layout' , array(
            'default' => 'style-1',
            'sanitize_callback' => 'whitedot_sanitize_choice',
            
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'whitedot_blog_home_layout',
                array(
                    'label'          => __( 'Blog Layout', 'whitedot' ),
                    'description'          => __( 'Premium Layout Designs Unlocked!', 'whitedot' ),
                    'section'        => 'whitedot_blog_archive_section',
                    'settings'       => 'whitedot_blog_home_layout',
                    'type'           => 'select',
                    'choices'        => array(
                        'style-1'      => 'Full Width(Default)',
                        'style-2'      => 'Grid',
                        'style-3'      => 'Image Left Side',
                        'style-4'      => 'Image Right Side',
                        'style-5'      => 'Image zig-zag Sides'
                    )
                )
            )
        );

      }else{

        $wp_customize->add_setting( 'whitedot_blog_home_layout' , array(
            'default' => 'style-1',
            'sanitize_callback' => 'whitedot_sanitize_choice',
            
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Control(
                $wp_customize,
                'whitedot_blog_home_layout',
                array(
                    'label'          => __( 'Blog Layout', 'whitedot' ),
                    'description'          => __( 'Premium Layout Designs are available in Whitedot Designer Premium Adoon', 'whitedot' ),
                    'section'        => 'whitedot_blog_archive_section',
                    'settings'       => 'whitedot_blog_home_layout',
                    'type'           => 'select',
                    'choices'        => array(
                        'style-1'      => 'Full Width(Default)',
                        'style-2'       => 'Grid'
                    )
                )
            )
        );

      }
    }else{

      $wp_customize->add_setting( 'whitedot_blog_home_layout' , array(
          'default' => 'style-1',
          'sanitize_callback' => 'whitedot_sanitize_choice',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_blog_home_layout',
              array(
                  'label'          => __( 'Blog Layout', 'whitedot' ),
                  'description'          => __( 'Premium Layout Designs are available in Whitedot Designer Premium Adoon', 'whitedot' ),
                  'section'        => 'whitedot_blog_archive_section',
                  'settings'       => 'whitedot_blog_home_layout',
                  'type'           => 'select',
                  'choices'        => array(
                      'style-1'      => 'Full Width(Default)',
                      'style-2'       => 'Grid'
                  )
              )
          )
      );

    }

    $wp_customize->add_setting( 'whitedot_blog_home_grid_culmn',
       array(
          'default' => 2,
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_sanitize_integer'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_blog_home_grid_culmn',
       array(
          'label' => esc_html__( 'Grid Columns', 'whitedot' ),
          'section' => 'whitedot_blog_archive_section',
          'input_attrs' => array(
             'min' => 1, 
             'max' => 4, 
             'step' => 1, 
          ),
       )
    ) );

    $wp_customize->add_setting( 'whitedot_blog_home_metadate' , array(
        'transport' => 'refresh',
        'default'    =>  'true',
        'sanitize_callback' => 'whitedot_sanitize_checkbox',
        
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'whitedot_blog_home_metadate',
            array(
                'label'          => __( 'Show Meta Date', 'whitedot' ),
                'section'        => 'whitedot_blog_archive_section',
                'type'           => 'checkbox',
                
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_blog_home_metaauthor' , array(
        'transport' => 'refresh',
        'default'    =>  'true',
        'sanitize_callback' => 'whitedot_sanitize_checkbox',
        
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'whitedot_blog_home_metaauthor',
            array(
                'label'          => __( 'Show Meta Author', 'whitedot' ),
                'section'        => 'whitedot_blog_archive_section',
                'type'           => 'checkbox',
                
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_blog_home_pagination_style',
       array(
          'default' => 'page-num',
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_sanitize_choice'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Text_Radio_Button_Custom_Control( $wp_customize, 'whitedot_blog_home_pagination_style',
       array(
          'label' => __( 'Pageination Style', 'whitedot' ),
          'section' => 'whitedot_blog_archive_section',
          'choices' => array(
             'next-prev' => __( 'Next-Previous', 'whitedot' ), 
             'page-num' => __( 'Page Numbers', 'whitedot' )
          )
       )
    ) );

    if ( !class_exists( 'Whitedot_Designer' ) ) {
      $wp_customize->add_setting( 'whitedot_buy_premium_blog_archive',
             array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text'
             )
          );
           
      $wp_customize->add_control( new WhiteDot_Upgrade_Notice_Custom_Control( $wp_customize, 'whitedot_buy_premium_blog_archive',
         array(
            'label' => __( 'Try WhiteDot Premium', 'whitedot' ),
            'description' => __( 'There are a lot of awesome customization optons and premium blog layouts in our premium add-on - WHITEDOT DESIGNER. Start your 7 days Free Trial (No Credit Card Required) and unlock all the premium features now.', 'whitedot' ),
            'section' => 'whitedot_blog_archive_section',
            'priority'     => 200,
         )
      ) );
    }

    //Single Post
    $wp_customize->add_section( 'whitedot_blog_single_section' , array(
        'title'      => __('Single Post','whitedot'),
        'priority'   => 20,
        'panel'        => 'whitedot_blog_panel',
    ) );

    $wp_customize->add_setting( 'whitedot_blog_single_sidebar_layout',
       array(
          'default' => 'sidebarright',
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_blog_single_sidebar_layout',
       array(
          'label' => __( 'Sidebar Layout', 'whitedot' ),
          'section' => 'whitedot_blog_single_section',
          'priority'   => 10,
          'choices' => array(
             'sidebarleft' => array(  
                'image' =>  get_template_directory_uri() . '/img/left-sidebar.png',
                'name' => __( 'Left Sidebar', 'whitedot' ) //
             ),
             'sidebarnone' => array(
                'image' => get_template_directory_uri() . '/img/fullwidth.png',
                'name' => __( 'No Sidebar', 'whitedot' )
             ),
             'sidebarright' => array(
                'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                'name' => __( 'Right Sidebar', 'whitedot' )
             )
          )
       )
    ) );

    $wp_customize->add_setting( 'whitedot_single_blog_sidebar_width',
       array(
          'default' => 30,
          'transport' => 'postMessage',
          'sanitize_callback' => 'whitedot_sanitize_integer'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_single_blog_sidebar_width',
       array(
          'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
          'section' => 'whitedot_blog_single_section',
          'input_attrs' => array(
             'min' => 20, 
             'max' => 50, 
             'step' => 1, 
          ),
       )
    ) );

    $wp_customize->add_setting( 'whitedot_blog_single_metadate' , array(
        'transport' => 'refresh',
        'default'    =>  'true',
        'sanitize_callback' => 'whitedot_sanitize_checkbox',
        
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'whitedot_blog_single_metadate',
            array(
                'label'          => __( 'Show Meta Date', 'whitedot' ),
                'section'        => 'whitedot_blog_single_section',
                'type'           => 'checkbox',
                'priority'   => 20,
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_blog_single_metaauthor' , array(
        'transport' => 'refresh',
        'default'    =>  'true',
        'sanitize_callback' => 'whitedot_sanitize_checkbox',
        
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'whitedot_blog_single_metaauthor',
            array(
                'label'          => __( 'Show Meta Author', 'whitedot' ),
                'section'        => 'whitedot_blog_single_section',
                'type'           => 'checkbox',
                'priority'   => 30,
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_blog_single_metacategory' , array(
        'transport' => 'refresh',
        'default'    =>  'true',
        'sanitize_callback' => 'whitedot_sanitize_checkbox',
        
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'whitedot_blog_single_metacategory',
            array(
                'label'          => __( 'Show Meta Category', 'whitedot' ),
                'section'        => 'whitedot_blog_single_section',
                'type'           => 'checkbox',
                'priority'   => 40,
            )
        )
    );

    $wp_customize->add_setting( 'whitedot_show_authorbox_in_singlepost',
       array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_switch_sanitization'
       )
    );
     
    $wp_customize->add_control( new whitedot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_authorbox_in_singlepost',
       array(
          'label' => esc_html__( 'Author Box Below Post', 'whitedot' ),
          'section' => 'whitedot_blog_single_section',
          'priority'   => 50
       )
    ) );

    if ( !class_exists( 'Whitedot_Designer' ) ) {
      $wp_customize->add_setting( 'whitedot_buy_premium_single_blog',
             array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text'
             )
          );
           
      $wp_customize->add_control( new WhiteDot_Upgrade_Notice_Custom_Control( $wp_customize, 'whitedot_buy_premium_single_blog',
         array(
            'label' => __( 'Try WhiteDot Premium', 'whitedot' ),
            'description' => __( 'There are a lot of awesome customization optons and premium blog layouts in our premium add-on - WHITEDOT DESIGNER. Start your 7 days Free Trial (No Credit Card Required) and unlock all the premium features now.', 'whitedot' ),
            'section' => 'whitedot_blog_single_section',
            'priority'     => 200,
         )
      ) );
    }

    if ( class_exists( 'WooCommerce' ) ) {

      /*////////////////////////////////////////////////////////////////////////
                                      WooCommerce Panel                               
      ////////////////////////////////////////////////////////////////////////*/

      $wp_customize->add_panel( 'whitedot_woocommerce_panel' , array(
          'priority' => 70,
          'capability' => 'edit_theme_options',
          'theme_supports' => '',
          'title' => __( 'WooCommerce Settings', 'whitedot' ),
          
          )
      );

      //WooCommerce General
      $wp_customize->add_section( 'whitedot_woocommerce_general_section' , array(
          'title'      => __('General','whitedot'),
          'priority'   => 10,
          'panel'        => 'whitedot_woocommerce_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_show_cart_in_header',
         array(
            'default' => 1,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_switch_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_cart_in_header',
         array(
            'label' => esc_html__( 'Header Mini Cart', 'whitedot' ),
            'section' => 'whitedot_woocommerce_general_section'
         )
      ) );

      //WooCommerce Shop
      $wp_customize->add_section( 'whitedot_woocommerce_shop_section' , array(
          'title'      => __('Shop','whitedot'),
          'priority'   => 20,
          'panel'        => 'whitedot_woocommerce_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_woo_shop_sidebar_layout',
         array(
            'default' => 'sidebarnone',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_woo_shop_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_woocommerce_shop_section',
            'choices' => array(
               'sidebarleft' => array(  
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png',
                  'name' => __( 'Left Sidebar', 'whitedot' ) //
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_woo_shop_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_woo_shop_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_woocommerce_shop_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_woo_product_columns',
         array(
            'default' => 3,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_woo_product_columns',
         array(
            'label' => esc_html__( 'Product Columns', 'whitedot' ),
            'section' => 'whitedot_woocommerce_shop_section',
            'input_attrs' => array(
               'min' => 1, 
               'max' => 6, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_shop_products_per_page',
         array(
            'default' => 12,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_shop_products_per_page',
         array(
            'label' => esc_html__( 'Product Per Page ', 'whitedot' ),
            'section' => 'whitedot_woocommerce_shop_section',
            'input_attrs' => array(
               'min' => 1, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_shop_product_column_tablet' , array(
          'default' => 'column-2',
          'sanitize_callback' => 'whitedot_sanitize_choice',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_shop_product_column_tablet',
              array(
                  'label'          => __( 'Product Column (Tablet)', 'whitedot' ),
                  'section'        => 'whitedot_woocommerce_shop_section',
                  'type'           => 'select',
                  'choices'        => array(
                      'column-1'      => 'Two Columns (No Spacing)',
                      'column-2'       => 'Two Columns (With Spacing)',
                      'column-3'       => 'One Column',
                  )
              )
          )
      );

      $wp_customize->add_setting( 'whitedot_shop_product_column_mobile' , array(
          'default' => 'column-3',
          'sanitize_callback' => 'whitedot_sanitize_choice',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_shop_product_column_mobile',
              array(
                  'label'          => __( 'Product Column (Mobile)', 'whitedot' ),
                  'section'        => 'whitedot_woocommerce_shop_section',
                  'type'           => 'select',
                  'choices'        => array(
                      'column-1'      => 'Two Columns (No Spacing)',
                      'column-2'       => 'Two Columns (With Spacing)',
                      'column-3'       => 'One Column',
                  )
              )
          )
      );

      $wp_customize->add_setting( 'whitedot_show_add_to_cart',
         array(
            'default' => 1,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_switch_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_add_to_cart',
         array(
            'label' => esc_html__( 'Show Add to Cart Button', 'whitedot' ),
            'section' => 'whitedot_woocommerce_shop_section'
         )
      ) );

      $wp_customize->add_setting( 'whitedot_show_product_filter',
         array(
            'default' => 1,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_switch_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_product_filter',
         array(
            'label' => esc_html__( 'Show Product Filter', 'whitedot' ),
            'section' => 'whitedot_woocommerce_shop_section'
         )
      ) );

      $wp_customize->add_setting( 'whitedot_woo_shop_filter_layout',
         array(
            'default' => 'right',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_choice'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Text_Radio_Button_Custom_Control( $wp_customize, 'whitedot_woo_shop_filter_layout',
         array(
            'label' => __( 'Shop Filter Layout', 'whitedot' ),
            'section' => 'whitedot_woocommerce_shop_section',
            'choices' => array(
               'left' => __( 'Left', 'whitedot' ), 
               'right' => __( 'Right', 'whitedot' )
            )
         )
      ) );

      //WooCommerce Single Product
      $wp_customize->add_section( 'whitedot_woocommerce_single_product_section' , array(
          'title'      => __('Single Product','whitedot'),
          'priority'   => 30,
          'panel'        => 'whitedot_woocommerce_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_woo_single_product_sidebar_layout',
         array(
            'default' => 'sidebarnone',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_woo_single_product_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_woocommerce_single_product_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' ) 
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_woo_single_product_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_woo_single_product_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_woocommerce_single_product_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_woo_related_product_column',
         array(
            'default' => 3,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_woo_related_product_column',
         array(
            'label' => esc_html__( 'Related Product Columns', 'whitedot' ),
            'section' => 'whitedot_woocommerce_single_product_section',
            'input_attrs' => array(
               'min' => 1, 
               'max' => 6, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_woo_related_product_per_page',
         array(
            'default' => 3,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_woo_related_product_per_page',
         array(
            'label' => esc_html__( 'Related Product Per Page', 'whitedot' ),
            'section' => 'whitedot_woocommerce_single_product_section',
            'input_attrs' => array(
               'min' => 1, 
               'max' => 12, 
               'step' => 1, 
            ),
         )
      ) );

      //WooCommerce Cart
      $wp_customize->add_section( 'whitedot_woocommerce_cart_section' , array(
          'title'      => __('Cart','whitedot'),
          'priority'   => 40,
          'panel'        => 'whitedot_woocommerce_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_woo_cart_sidebar_layout',
         array(
            'default' => 'sidebarnone',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_woo_cart_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_woocommerce_cart_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' )
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_woo_cart_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_woo_cart_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_woocommerce_cart_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      //WooCommerce Checkout
      $wp_customize->add_section( 'whitedot_woocommerce_checkout_section' , array(
          'title'      => __('Checkout','whitedot'),
          'priority'   => 40,
          'panel'        => 'whitedot_woocommerce_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_woo_checkout_sidebar_layout',
         array(
            'default' => 'sidebarnone',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_woo_checkout_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_woocommerce_checkout_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' )
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_woo_checkout_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_woo_checkout_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_woocommerce_checkout_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );
    }

    if ( class_exists( 'LifterLMS' ) ) {
      /*////////////////////////////////////////////////////////////////////////
                                  LifterLMS Panel                               
      ////////////////////////////////////////////////////////////////////////*/

      $wp_customize->add_panel( 'whitedot_lifterlms_panel' , array(
          'priority' => 80,
          'capability' => 'edit_theme_options',
          'theme_supports' => '',
          'title' => __( 'LifterLMS Settings', 'whitedot' ),
          
          )
      );

      //Course Catalog
      $wp_customize->add_section( 'whitedot_course_catalog_section' , array(
          'title'      => __('Course Catelog','whitedot'),
          'priority'   => 10,
          'panel'        => 'whitedot_lifterlms_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_course_catalog_sidebar_layout',
         array(
            'default' => 'sidebarnone',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_course_catalog_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_course_catalog_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' )
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_course_catalog_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_course_catalog_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_course_catalog_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_course_catalog_column',
         array(
            'default' => 3,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_course_catalog_column',
         array(
            'label' => esc_html__( 'Course Catalog Columns', 'whitedot' ),
            'section' => 'whitedot_course_catalog_section',
            'input_attrs' => array(
               'min' => 1, 
               'max' => 6, 
               'step' => 1, 
            ),
         )
      ) );

      //Membership Catalog
      $wp_customize->add_section( 'whitedot_membership_catalog_section' , array(
          'title'      => __('Membership Catelog','whitedot'),
          'priority'   => 20,
          'panel'        => 'whitedot_lifterlms_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_membership_catalog_sidebar_layout',
         array(
            'default' => 'sidebarnone',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_membership_catalog_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_membership_catalog_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' )
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_membership_catalog_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_membership_catalog_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_membership_catalog_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_membership_catalog_column',
         array(
            'default' => 3,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_membership_catalog_column',
         array(
            'label' => esc_html__( 'Membership Catalog Columns', 'whitedot' ),
            'section' => 'whitedot_membership_catalog_section',
            'input_attrs' => array(
               'min' => 1, 
               'max' => 6, 
               'step' => 1, 
            ),
         )
      ) );

      //Dashboard
      $wp_customize->add_section( 'whitedot_llms_dashboard_section' , array(
          'title'      => __('Dashboard','whitedot'),
          'priority'   => 25,
          'panel'        => 'whitedot_lifterlms_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_llms_dashboard_sidebar_layout',
         array(
            'default' => 'sidebarnone',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_llms_dashboard_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_llms_dashboard_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' )
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_show_dashboard_nav_icon',
         array(
            'default' => 0,
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_switch_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_dashboard_nav_icon',
         array(
            'label' => esc_html__( 'Show Navigation Icons', 'whitedot' ),
            'section' => 'whitedot_llms_dashboard_section'
         )
      ) );

      //Single Course 
      $wp_customize->add_section( 'whitedot_single_course_section' , array(
          'title'      => __('Single Course','whitedot'),
          'priority'   => 30,
          'panel'        => 'whitedot_lifterlms_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_single_course_sidebar_layout',
         array(
            'default' => 'sidebarright',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_single_course_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_single_course_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' )
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_single_course_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_single_course_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_single_course_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_single_course_metaauthor' , array(
          'transport' => 'refresh',
          'default'    =>  'true',
          'sanitize_callback' => 'whitedot_sanitize_checkbox',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_single_course_metaauthor',
              array(
                  'label'          => __( 'Show Meta Author(Below Title)', 'whitedot' ),
                  'section'        => 'whitedot_single_course_section',
                  'type'           => 'checkbox',
                  
              )
          )
      );

      $wp_customize->add_setting( 'whitedot_single_course_metadate' , array(
          'transport' => 'refresh',
          'default'    =>  'true',
          'sanitize_callback' => 'whitedot_sanitize_checkbox',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_single_course_metadate',
              array(
                  'label'          => __( 'Show Meta Date(Below Title)', 'whitedot' ),
                  'section'        => 'whitedot_single_course_section',
                  'type'           => 'checkbox',
                  
              )
          )
      );

      //Single Lesson 
      $wp_customize->add_section( 'whitedot_single_lesson_section' , array(
          'title'      => __('Single Lesson','whitedot'),
          'priority'   => 40,
          'panel'        => 'whitedot_lifterlms_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_single_lesson_sidebar_layout',
         array(
            'default' => 'sidebarright',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_single_lesson_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_single_lesson_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' )
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_single_lesson_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_single_lesson_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_single_lesson_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_single_lesson_metaauthor' , array(
          'transport' => 'refresh',
          'default'    =>  'true',
          'sanitize_callback' => 'whitedot_sanitize_checkbox',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_single_lesson_metaauthor',
              array(
                  'label'          => __( 'Show Meta Author(Below Title)', 'whitedot' ),
                  'section'        => 'whitedot_single_lesson_section',
                  'type'           => 'checkbox',
                  
              )
          )
      );

      $wp_customize->add_setting( 'whitedot_single_lesson_metadate' , array(
          'transport' => 'refresh',
          'default'    =>  'true',
          'sanitize_callback' => 'whitedot_sanitize_checkbox',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_single_lesson_metadate',
              array(
                  'label'          => __( 'Show Meta Date(Below Title)', 'whitedot' ),
                  'section'        => 'whitedot_single_lesson_section',
                  'type'           => 'checkbox',
                  
              )
          )
      );

      //Single Membership 
      $wp_customize->add_section( 'whitedot_single_membership_section' , array(
          'title'      => __('Single Membership','whitedot'),
          'priority'   => 50,
          'panel'        => 'whitedot_lifterlms_panel',
      ) );

      $wp_customize->add_setting( 'whitedot_single_membership_sidebar_layout',
         array(
            'default' => 'sidebarnone',
            'transport' => 'refresh',
            'sanitize_callback' => 'whitedot_image_radio_options_sanitization'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Image_Radio_Button_Custom_Control( $wp_customize, 'whitedot_single_membership_sidebar_layout',
         array(
            'label' => __( 'Sidebar Layout', 'whitedot' ),
            'section' => 'whitedot_single_membership_section',
            'choices' => array(
               'sidebarleft' => array(
                  'image' =>  get_template_directory_uri() . '/img/left-sidebar.png', 
                  'name' => __( 'Left Sidebar', 'whitedot' )
               ),
               'sidebarnone' => array(
                  'image' => get_template_directory_uri() . '/img/fullwidth.png',
                  'name' => __( 'No Sidebar', 'whitedot' )
               ),
               'sidebarright' => array(
                  'image' => get_template_directory_uri() . '/img/right-sidebar.png',
                  'name' => __( 'Right Sidebar', 'whitedot' )
               )
            )
         )
      ) );

      $wp_customize->add_setting( 'whitedot_single_membership_sidebar_width',
         array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'whitedot_sanitize_integer'
         )
      );
       
      $wp_customize->add_control( new WhiteDot_Slider_Custom_Control( $wp_customize, 'whitedot_single_membership_sidebar_width',
         array(
            'label' => esc_html__( 'Sidebar Width', 'whitedot' ),
            'section' => 'whitedot_single_membership_section',
            'input_attrs' => array(
               'min' => 20, 
               'max' => 50, 
               'step' => 1, 
            ),
         )
      ) );

      $wp_customize->add_setting( 'whitedot_single_membership_metaauthor' , array(
          'transport' => 'refresh',
          'default'    =>  'true',
          'sanitize_callback' => 'whitedot_sanitize_checkbox',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_single_membership_metaauthor',
              array(
                  'label'          => __( 'Show Meta Author(Below Title)', 'whitedot' ),
                  'section'        => 'whitedot_single_membership_section',
                  'type'           => 'checkbox',
                  
              )
          )
      );

      $wp_customize->add_setting( 'whitedot_single_membership_metadate' , array(
          'transport' => 'refresh',
          'default'    =>  'true',
          'sanitize_callback' => 'whitedot_sanitize_checkbox',
          
          )
      );

      $wp_customize->add_control(
          new WP_Customize_Control(
              $wp_customize,
              'whitedot_single_membership_metadate',
              array(
                  'label'          => __( 'Show Meta Date(Below Title)', 'whitedot' ),
                  'section'        => 'whitedot_single_membership_section',
                  'type'           => 'checkbox',
                  
              )
          )
      );
    }

    //Footer Settings
    $wp_customize->add_section( 'whitedot_footer_settings_section' , array(
        'title'      => __('Footer Settings','whitedot'),
        'priority'   => 110,
    ) );

    $wp_customize->add_setting( 'whitedot_show_footer_branding',
       array(
          'default' => 1,
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_switch_sanitization'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_footer_branding',
       array(
          'label' => esc_html__( 'Footer Branding', 'whitedot' ),
          'section' => 'whitedot_footer_settings_section',
          'priority'   => 20,
       )
    ) );

    $wp_customize->add_setting( 'whitedot_show_footer_social_icons',
       array(
          'default' => 0,
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_switch_sanitization'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_footer_social_icons',
       array(
          'label' => esc_html__( 'Social Icons', 'whitedot' ),
          'description' => esc_html__( "Don't forget to configure 'Social Icons' Menu in Menus Settings.", "whitedot" ),
          'section' => 'whitedot_footer_settings_section',
          'priority'   => 50,
       )
    ) );

    $wp_customize->add_setting( 'whitedot_show_footer_backtotop',
       array(
          'default' => 0,
          'transport' => 'refresh',
          'sanitize_callback' => 'whitedot_switch_sanitization'
       )
    );
     
    $wp_customize->add_control( new WhiteDot_Toggle_Switch_Custom_control( $wp_customize, 'whitedot_show_footer_backtotop',
       array(
          'label' => esc_html__( 'Scroll to Top Button', 'whitedot' ),
          'section' => 'whitedot_footer_settings_section',
          'priority'   => 20,
       )
    ) );

    if ( !class_exists( 'Whitedot_Designer' ) ) {
      $wp_customize->add_setting( 'whitedot_buy_premium_footer',
             array(
                'default' => '',
                'sanitize_callback' => 'sanitize_text'
             )
          );
           
      $wp_customize->add_control( new WhiteDot_Upgrade_Notice_Custom_Control( $wp_customize, 'whitedot_buy_premium_footer',
         array(
            'label' => __( 'Try WhiteDot Premium', 'whitedot' ),
            'description' => __( 'There are a lot of awesome customization optons for footer in our premium add-on - WHITEDOT DESIGNER. Start your 7 days Free Trial (No Credit Card Required) and unlock all the premium features now.', 'whitedot' ),
            'section' => 'whitedot_footer_settings_section',
            'priority'     => 200,
         )
      ) );
    }


}


/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function whitedot_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function whitedot_customize_partial_blogdescription() {
	bloginfo( 'description' );
}


// Sanitize text 
function sanitize_text( $text ) {
    
    return sanitize_text_field( $text );

}

/**
 * Image Radio Button Options Text sanitization
 *
 * @param  string   Input to be sanitized (either a string containing a single string or multiple, separated by commas)
 * @return string   Sanitized input
 */
if ( ! function_exists( 'whitedot_image_radio_options_sanitization' ) ) {
    function whitedot_image_radio_options_sanitization( $input ) {
        if ( strpos( $input, ',' ) !== false) {
            $input = explode( ',', $input );
        }
        if( is_array( $input ) ) {
            foreach ( $input as $key => $value ) {
                $input[$key] = sanitize_text_field( $value );
            }
            $input = implode( ',', $input );
        }
        else {
            $input = sanitize_text_field( $input );
        }
        return $input;
    }
}

// Sanitize checkbox 
function whitedot_sanitize_checkbox( $checked ) {

  return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

// Sanitize radio
function whitedot_sanitize_choice( $input, $setting ) {

  $input = sanitize_key( $input );

  $choices = $setting->manager->get_control( $setting->id )->choices;

  return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}


/**
 * Only allow values between a certain minimum & maxmium range
 *
 * @param  number   Input to be sanitized
 * @return number   Sanitized input
 */
if ( ! function_exists( 'whitedot_in_range' ) ) {
    function whitedot_in_range( $input, $min, $max ){
        if ( $input < $min ) {
            $input = $min;
        }
        if ( $input > $max ) {
            $input = $max;
        }
    return $input;
    }
}


/**
 * Array sanitization
 *
 * @param  array    Input to be sanitized
 * @return array    Sanitized input
 */
if ( ! function_exists( 'whitedot_array_sanitization' ) ) {
    function whitedot_array_sanitization( $input ) {
        if( is_array( $input ) ) {
            foreach ( $input as $key => $value ) {
                $input[$key] = sanitize_text_field( $value );
            }
        }
        else {
            $input = '';
        }
        return $input;
    }
}

/**
 * Switch sanitization
 *
 * @param  string       Switch value
 * @return integer  Sanitized value
 */
if ( ! function_exists( 'whitedot_switch_sanitization' ) ) {
    function whitedot_switch_sanitization( $input ) {
        if ( true === $input ) {
            return 1;
        } else {
            return 0;
        }
    }
}

/**
 * Integer sanitization
 *
 * @param  string       Input value to check
 * @return integer  Returned integer value
 */
if ( ! function_exists( 'whitedot_sanitize_integer' ) ) {
    function whitedot_sanitize_integer($input){
      $input = absint($input);

      // If the input is an absolute integer, return it.
      // otherwise, return the default.
      return ($input ? $input : $setting->default);
    }
}

/**
   * Sanitize integers that can use decimals.
   *
   */
if ( ! function_exists( 'whitedot_sanitize_decimal_integer' ) ) {
  
  function whitedot_sanitize_decimal_integer( $input ) {
    return abs( floatval( $input ) );
  }
}

/**
 * Alpha Color (Hex & RGBa) sanitization
 *
 * @param  string Input to be sanitized
 * @return string Sanitized input
 */
if ( ! function_exists( 'whitedot_hex_rgba_sanitization' ) ) {
  function whitedot_hex_rgba_sanitization( $input, $setting ) {
    if ( empty( $input ) || is_array( $input ) ) {
      return $setting->default;
    }

    if ( false === strpos( $input, 'rgba' ) ) {
      // If string doesn't start with 'rgba' then santize as hex color
      $input = sanitize_hex_color( $input );
    } else {
      // Sanitize as RGBa color
      $input = str_replace( ' ', '', $input );
      sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
      $input = 'rgba(' . skyrocket_in_range( $red, 0, 255 ) . ',' . skyrocket_in_range( $green, 0, 255 ) . ',' . skyrocket_in_range( $blue, 0, 255 ) . ',' . skyrocket_in_range( $alpha, 0, 1 ) . ')';
    }
    return $input;
  }
}



