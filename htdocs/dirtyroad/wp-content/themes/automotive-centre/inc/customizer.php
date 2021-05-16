<?php
/**
 * Automotive Centre Theme Customizer
 *
 * @package Automotive Centre
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function automotive_centre_custom_controls() {

    load_template( trailingslashit( get_template_directory() ) . '/inc/custom-controls.php' );
}
add_action( 'customize_register', 'automotive_centre_custom_controls' );

function automotive_centre_customize_register( $wp_customize ) {

	load_template( trailingslashit( get_template_directory() ) . '/inc/icon-picker.php' );

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage'; 
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'blogname', array( 
		'selector' => '.logo .site-title a', 
	 	'render_callback' => 'automotive_centre_customize_partial_blogname', 
	)); 

	$wp_customize->selective_refresh->add_partial( 'blogdescription', array( 
		'selector' => 'p.site-description', 
		'render_callback' => 'automotive_centre_customize_partial_blogdescription', 
	));

	//add home page setting pannel
	$AutomotiveCentreParentPanel = new Automotive_Centre_WP_Customize_Panel( $wp_customize, 'automotive_centre_panel_id', array(
		'capability' => 'edit_theme_options',
		'theme_supports' => '',
		'title'      => esc_html__( 'VW Settings', 'automotive-centre' ),
		'priority' => 10,
	));

	// Layout
	$wp_customize->add_section( 'automotive_centre_left_right', array(
    	'title'      => esc_html__( 'General Settings', 'automotive-centre' ),
		'panel' => 'automotive_centre_panel_id'
	) );

	$wp_customize->add_setting('automotive_centre_width_option',array(
        'default' => 'Full Width',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'
	));
	$wp_customize->add_control(new Automotive_Centre_Image_Radio_Control($wp_customize, 'automotive_centre_width_option', array(
        'type' => 'select',
        'label' => __('Width Layouts','automotive-centre'),
        'description' => __('Here you can change the width layout of Website.','automotive-centre'),
        'section' => 'automotive_centre_left_right',
        'choices' => array(
            'Full Width' => esc_url(get_template_directory_uri()).'/assets/images/full-width.png',
            'Wide Width' => esc_url(get_template_directory_uri()).'/assets/images/wide-width.png',
            'Boxed' => esc_url(get_template_directory_uri()).'/assets/images/boxed-width.png',
    ))));

	// Add Settings and Controls for Layout
	$wp_customize->add_setting('automotive_centre_theme_options',array(
        'default' => 'Right Sidebar',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'	        
	) );
	$wp_customize->add_control('automotive_centre_theme_options', array(
        'type' => 'select',
        'label' => __('Post Sidebar Layout','automotive-centre'),
        'description' => __('Here you can change the sidebar layout for posts. ','automotive-centre'),
        'section' => 'automotive_centre_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','automotive-centre'),
            'Right Sidebar' => __('Right Sidebar','automotive-centre'),
            'One Column' => __('One Column','automotive-centre'),
            'Three Columns' => __('Three Columns','automotive-centre'),
            'Four Columns' => __('Four Columns','automotive-centre'),
            'Grid Layout' => __('Grid Layout','automotive-centre')
        ),
	));

	$wp_customize->add_setting('automotive_centre_page_layout',array(
        'default' => 'One Column',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'
	));
	$wp_customize->add_control('automotive_centre_page_layout',array(
        'type' => 'select',
        'label' => __('Page Sidebar Layout','automotive-centre'),
        'description' => __('Here you can change the sidebar layout for pages. ','automotive-centre'),
        'section' => 'automotive_centre_left_right',
        'choices' => array(
            'Left Sidebar' => __('Left Sidebar','automotive-centre'),
            'Right Sidebar' => __('Right Sidebar','automotive-centre'),
            'One Column' => __('One Column','automotive-centre')
        ),
	) );

	//Pre-Loader
	$wp_customize->add_setting( 'automotive_centre_loader_enable',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ) );
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_loader_enable',array(
        'label' => esc_html__( 'Pre-Loader','automotive-centre' ),
        'section' => 'automotive_centre_left_right'
    )));

	$wp_customize->add_setting('automotive_centre_loader_icon',array(
        'default' => 'Two Way',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'
	));
	$wp_customize->add_control('automotive_centre_loader_icon',array(
        'type' => 'select',
        'label' => __('Pre-Loader Type','automotive-centre'),
        'section' => 'automotive_centre_left_right',
        'choices' => array(
            'Two Way' => __('Two Way','automotive-centre'),
            'Dots' => __('Dots','automotive-centre'),
            'Rotate' => __('Rotate','automotive-centre')
        ),
	) );

	//Topbar
	$wp_customize->add_section( 'automotive_centre_topbar', array(
    	'title'      => __( 'Topbar Settings', 'automotive-centre' ),
		'panel' => 'automotive_centre_panel_id'
	) );

	//Sticky Header
	$wp_customize->add_setting( 'automotive_centre_sticky_header',array(
        'default' => 0,
        'transport' => 'refresh',
        'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ) );
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_sticky_header',array(
        'label' => esc_html__( 'Sticky Header','automotive-centre' ),
        'section' => 'automotive_centre_topbar'
    )));

    $wp_customize->add_setting('automotive_centre_sticky_header_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_sticky_header_padding',array(
		'label'	=> __('Sticky Header Padding','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_search_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ));  
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_search_hide_show',array(
      'label' => esc_html__( 'Show / Hide Search','automotive-centre' ),
      'section' => 'automotive_centre_topbar'
    )));

    $wp_customize->add_setting('automotive_centre_search_icon',array(
		'default'	=> 'fas fa-search',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Automotive_Centre_Fontawesome_Icon_Chooser(
        $wp_customize,'automotive_centre_search_icon',array(
		'label'	=> __('Add Search Icon','automotive-centre'),
		'transport' => 'refresh',
		'section'	=> 'automotive_centre_topbar',
		'setting'	=> 'automotive_centre_search_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('automotive_centre_search_close_icon',array(
		'default'	=> 'fa fa-window-close',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Automotive_Centre_Fontawesome_Icon_Chooser(
        $wp_customize,'automotive_centre_search_close_icon',array(
		'label'	=> __('Add Search Close Icon','automotive-centre'),
		'transport' => 'refresh',
		'section'	=> 'automotive_centre_topbar',
		'setting'	=> 'automotive_centre_search_close_icon',
		'type'		=> 'icon'
	)));

    $wp_customize->add_setting('automotive_centre_search_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_search_font_size',array(
		'label'	=> __('Search Font Size','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_search_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_search_padding_top_bottom',array(
		'label'	=> __('Search Padding Top Bottom','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_search_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_search_padding_left_right',array(
		'label'	=> __('Search Padding Left Right','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_search_border_radius', array(
		'default'              => "",
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_search_border_radius', array(
		'label'       => esc_html__( 'Search Border Radius','automotive-centre' ),
		'section'     => 'automotive_centre_topbar',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('automotive_centre_phone_text', array( 
		'selector' => '.info-box h6', 
		'render_callback' => 'automotive_centre_customize_partial_automotive_centre_phone_text', 
	));

    $wp_customize->add_setting('automotive_centre_phone_icon',array(
		'default'	=> 'fas fa-phone',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Automotive_Centre_Fontawesome_Icon_Chooser(
        $wp_customize,'automotive_centre_phone_icon',array(
		'label'	=> __('Add Phone Icon','automotive-centre'),
		'transport' => 'refresh',
		'section'	=> 'automotive_centre_topbar',
		'setting'	=> 'automotive_centre_phone_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('automotive_centre_phone_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_phone_text',array(
		'label'	=> __('Add Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'PHONE', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_phone_number',array(
		'default'=> '',
		'sanitize_callback'	=> 'automotive_centre_sanitize_phone_number'
	));
	$wp_customize->add_control('automotive_centre_phone_number',array(
		'label'	=> __('Add Phone Number','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '+789 456 1230', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_email_icon',array(
		'default'	=> 'fas fa-envelope-open',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Automotive_Centre_Fontawesome_Icon_Chooser(
        $wp_customize,'automotive_centre_email_icon',array(
		'label'	=> __('Add Email Icon','automotive-centre'),
		'transport' => 'refresh',
		'section'	=> 'automotive_centre_topbar',
		'setting'	=> 'automotive_centre_email_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('automotive_centre_email_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_email_text',array(
		'label'	=> __('Add Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'EMAIL', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_email_address',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_email'
	));
	$wp_customize->add_control('automotive_centre_email_address',array(
		'label'	=> __('Add Email Address','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'example@123.com', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_top_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_top_button_text',array(
		'label'	=> __('Add Button Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'SELL YOUR CAR', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_top_button_url',array(
		'default'=> '',
		'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('automotive_centre_top_button_url',array(
		'label'	=> __('Add Button URL','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'https://example.com/', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_topbar',
		'type'=> 'url'
	));
    
	//Slider
	$wp_customize->add_section( 'automotive_centre_slidersettings' , array(
    	'title'      => __( 'Slider Settings', 'automotive-centre' ),
		'panel' => 'automotive_centre_panel_id'
	) );

	$wp_customize->add_setting( 'automotive_centre_slider_hide_show',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ));  
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_slider_hide_show',array(
      'label' => esc_html__( 'Show / Hide Slider','automotive-centre' ),
      'section' => 'automotive_centre_slidersettings'
    )));

     //Selective Refresh
    $wp_customize->selective_refresh->add_partial('automotive_centre_slider_hide_show',array(
		'selector'        => '.slider-btn a',
		'render_callback' => 'automotive_centre_customize_partial_automotive_centre_slider_hide_show',
	));

	for ( $count = 1; $count <= 4; $count++ ) {
		$wp_customize->add_setting( 'automotive_centre_slider_page' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'automotive_centre_sanitize_dropdown_pages'
		) );
		$wp_customize->add_control( 'automotive_centre_slider_page' . $count, array(
			'label'    => __( 'Select Slider Page', 'automotive-centre' ),
			'description' => __('Slider image size (1500 x 650)','automotive-centre'),
			'section'  => 'automotive_centre_slidersettings',
			'type'     => 'dropdown-pages'
		) );
	}

	$wp_customize->add_setting('automotive_centre_slider_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_slider_button_text',array(
		'label'	=> __('Add Slider Button Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'LEARN MORE', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_slidersettings',
		'type'=> 'text'
	));

	//content layout
	$wp_customize->add_setting('automotive_centre_slider_content_option',array(
        'default' => 'Left',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'
	));
	$wp_customize->add_control(new Automotive_Centre_Image_Radio_Control($wp_customize, 'automotive_centre_slider_content_option', array(
        'type' => 'select',
        'label' => __('Slider Content Layouts','automotive-centre'),
        'section' => 'automotive_centre_slidersettings',
        'choices' => array(
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/slider-content1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/slider-content2.png',
            'Right' => esc_url(get_template_directory_uri()).'/assets/images/slider-content3.png',
    ))));

    //Slider excerpt
	$wp_customize->add_setting( 'automotive_centre_slider_excerpt_number', array(
		'default'              => 30,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_slider_excerpt_number', array(
		'label'       => esc_html__( 'Slider Excerpt length','automotive-centre' ),
		'section'     => 'automotive_centre_slidersettings',
		'type'        => 'range',
		'settings'    => 'automotive_centre_slider_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Opacity
	$wp_customize->add_setting('automotive_centre_slider_opacity_color',array(
      'default'              => 0.5,
      'sanitize_callback' => 'automotive_centre_sanitize_choices'
	));

	$wp_customize->add_control( 'automotive_centre_slider_opacity_color', array(
	'label'       => esc_html__( 'Slider Image Opacity','automotive-centre' ),
	'section'     => 'automotive_centre_slidersettings',
	'type'        => 'select',
	'settings'    => 'automotive_centre_slider_opacity_color',
	'choices' => array(
      '0' =>  esc_attr('0','automotive-centre'),
      '0.1' =>  esc_attr('0.1','automotive-centre'),
      '0.2' =>  esc_attr('0.2','automotive-centre'),
      '0.3' =>  esc_attr('0.3','automotive-centre'),
      '0.4' =>  esc_attr('0.4','automotive-centre'),
      '0.5' =>  esc_attr('0.5','automotive-centre'),
      '0.6' =>  esc_attr('0.6','automotive-centre'),
      '0.7' =>  esc_attr('0.7','automotive-centre'),
      '0.8' =>  esc_attr('0.8','automotive-centre'),
      '0.9' =>  esc_attr('0.9','automotive-centre')
	),
	));

	//Slider height
	$wp_customize->add_setting('automotive_centre_slider_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_slider_height',array(
		'label'	=> __('Slider Height','automotive-centre'),
		'description'	=> __('Specify the slider height (px).','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '500px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_slidersettings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_slider_speed', array(
		'default'  => 3000,
		'sanitize_callback'	=> 'automotive_centre_sanitize_float'
	) );
	$wp_customize->add_control( 'automotive_centre_slider_speed', array(
		'label' => esc_html__('Slider Transition Speed','automotive-centre'),
		'section' => 'automotive_centre_slidersettings',
		'type'  => 'number',
	) );
    
	//About Us section
	$wp_customize->add_section( 'automotive_centre_about_section' , array(
    	'title'      => __( 'About us Settings', 'automotive-centre' ),
		'priority'   => null,
		'panel' => 'automotive_centre_panel_id'
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial( 'automotive_centre_section_title', array( 
		'selector' => '#about-section h2', 
		'render_callback' => 'automotive_centre_customize_partial_automotive_centre_section_title',
	));

	$wp_customize->add_setting('automotive_centre_section_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_section_title',array(
		'label'	=> __('Add Section Title','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'ABOUT US', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_about_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_about_page' , array(
		'default'           => '',
		'sanitize_callback' => 'automotive_centre_sanitize_dropdown_pages'
	) );
	$wp_customize->add_control( 'automotive_centre_about_page' , array(
		'label'    => __( 'Select About Page', 'automotive-centre' ),
		'section'  => 'automotive_centre_about_section',
		'type'     => 'dropdown-pages'
	) );

	//About excerpt
	$wp_customize->add_setting( 'automotive_centre_about_excerpt_number', array(
		'default'              => 30,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_about_excerpt_number', array(
		'label'       => esc_html__( 'About Excerpt length','automotive-centre' ),
		'section'     => 'automotive_centre_about_section',
		'type'        => 'range',
		'settings'    => 'automotive_centre_about_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting('automotive_centre_about_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_about_button_text',array(
		'label'	=> __('Add About Button Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'LEARN MORE', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_about_section',
		'type'=> 'text'
	));

	//Blog Post
	$wp_customize->add_panel( $AutomotiveCentreParentPanel );

	$BlogPostParentPanel = new Automotive_Centre_WP_Customize_Panel( $wp_customize, 'blog_post_parent_panel', array(
		'title' => __( 'Blog Post Settings', 'automotive-centre' ),
		'panel' => 'automotive_centre_panel_id',
	));

	$wp_customize->add_panel( $BlogPostParentPanel );

	// Add example section and controls to the middle (second) panel
	$wp_customize->add_section( 'automotive_centre_post_settings', array(
		'title' => __( 'Post Settings', 'automotive-centre' ),
		'panel' => 'blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('automotive_centre_toggle_postdate', array( 
		'selector' => '.post-main-box h2 a', 
		'render_callback' => 'automotive_centre_customize_partial_automotive_centre_toggle_postdate', 
	));

	$wp_customize->add_setting( 'automotive_centre_toggle_postdate',array(
        'default' => 1,
        'transport' => 'refresh',
        'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ) );
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_toggle_postdate',array(
        'label' => esc_html__( 'Post Date','automotive-centre' ),
        'section' => 'automotive_centre_post_settings'
    )));

    $wp_customize->add_setting( 'automotive_centre_toggle_author',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ) );
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_toggle_author',array(
		'label' => esc_html__( 'Author','automotive-centre' ),
		'section' => 'automotive_centre_post_settings'
    )));

    $wp_customize->add_setting( 'automotive_centre_toggle_comments',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ) );
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_toggle_comments',array(
		'label' => esc_html__( 'Comments','automotive-centre' ),
		'section' => 'automotive_centre_post_settings'
    )));

    $wp_customize->add_setting( 'automotive_centre_toggle_tags',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'automotive_centre_switch_sanitization'
	));
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_toggle_tags', array(
		'label' => esc_html__( 'Tags','automotive-centre' ),
		'section' => 'automotive_centre_post_settings'
    )));

    $wp_customize->add_setting( 'automotive_centre_excerpt_number', array(
		'default'              => 30,
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_excerpt_number', array(
		'label'       => esc_html__( 'Excerpt length','automotive-centre' ),
		'section'     => 'automotive_centre_post_settings',
		'type'        => 'range',
		'settings'    => 'automotive_centre_excerpt_number',
		'input_attrs' => array(
			'step'             => 5,
			'min'              => 0,
			'max'              => 50,
		),
	) );

	//Blog layout
    $wp_customize->add_setting('automotive_centre_blog_layout_option',array(
        'default' => 'Default',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'
    ));
    $wp_customize->add_control(new Automotive_Centre_Image_Radio_Control($wp_customize, 'automotive_centre_blog_layout_option', array(
        'type' => 'select',
        'label' => __('Blog Layouts','automotive-centre'),
        'section' => 'automotive_centre_post_settings',
        'choices' => array(
            'Default' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout2.png',
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/blog-layout3.png',
    ))));

    $wp_customize->add_setting('automotive_centre_excerpt_settings',array(
        'default' => 'Excerpt',
        'transport' => 'refresh',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'
	));
	$wp_customize->add_control('automotive_centre_excerpt_settings',array(
        'type' => 'select',
        'label' => __('Post Content','automotive-centre'),
        'section' => 'automotive_centre_post_settings',
        'choices' => array(
        	'Content' => __('Content','automotive-centre'),
            'Excerpt' => __('Excerpt','automotive-centre'),
            'No Content' => __('No Content','automotive-centre')
        ),
	) );

	$wp_customize->add_setting('automotive_centre_excerpt_suffix',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_excerpt_suffix',array(
		'label'	=> __('Add Excerpt Suffix','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '[...]', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_post_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_blog_pagination_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ));  
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_blog_pagination_hide_show',array(
      'label' => esc_html__( 'Show / Hide Blog Pagination','automotive-centre' ),
      'section' => 'automotive_centre_post_settings'
    )));

	$wp_customize->add_setting( 'automotive_centre_blog_pagination_type', array(
        'default'			=> 'blog-page-numbers',
        'sanitize_callback'	=> 'automotive_centre_sanitize_choices'
    ));
    $wp_customize->add_control( 'automotive_centre_blog_pagination_type', array(
        'section' => 'automotive_centre_post_settings',
        'type' => 'select',
        'label' => __( 'Blog Pagination', 'automotive-centre' ),
        'choices'		=> array(
            'blog-page-numbers'  => __( 'Numeric', 'automotive-centre' ),
            'next-prev' => __( 'Older Posts/Newer Posts', 'automotive-centre' ),
    )));

    // Button Settings
	$wp_customize->add_section( 'automotive_centre_button_settings', array(
		'title' => __( 'Button Settings', 'automotive-centre' ),
		'panel' => 'blog_post_parent_panel',
	));

	$wp_customize->add_setting('automotive_centre_button_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_button_padding_top_bottom',array(
		'label'	=> __('Padding Top Bottom','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_button_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_button_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_button_padding_left_right',array(
		'label'	=> __('Padding Left Right','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_button_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_button_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_button_border_radius', array(
		'label'       => esc_html__( 'Button Border Radius','automotive-centre' ),
		'section'     => 'automotive_centre_button_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('automotive_centre_button_text', array( 
		'selector' => '.post-main-box .more-btn a', 
		'render_callback' => 'automotive_centre_customize_partial_automotive_centre_button_text', 
	));

    $wp_customize->add_setting('automotive_centre_button_text',array(
		'default'=> esc_html__( 'READ MORE', 'automotive-centre' ),
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_button_text',array(
		'label'	=> __('Add Button Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'READ MORE', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_button_settings',
		'type'=> 'text'
	));

	// Related Post Settings
	$wp_customize->add_section( 'automotive_centre_related_posts_settings', array(
		'title' => __( 'Related Posts Settings', 'automotive-centre' ),
		'panel' => 'blog_post_parent_panel',
	));

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('automotive_centre_related_post_title', array( 
		'selector' => '.related-post h3', 
		'render_callback' => 'automotive_centre_customize_partial_automotive_centre_related_post_title', 
	));

    $wp_customize->add_setting( 'automotive_centre_related_post',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ) );
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_related_post',array(
		'label' => esc_html__( 'Related Post','automotive-centre' ),
		'section' => 'automotive_centre_related_posts_settings'
    )));

    $wp_customize->add_setting('automotive_centre_related_post_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_related_post_title',array(
		'label'	=> __('Add Related Post Title','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'Related Post', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_related_posts_settings',
		'type'=> 'text'
	));

   	$wp_customize->add_setting('automotive_centre_related_posts_count',array(
		'default'=> '3',
		'sanitize_callback'	=> 'automotive_centre_sanitize_float'
	));
	$wp_customize->add_control('automotive_centre_related_posts_count',array(
		'label'	=> __('Add Related Post Count','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '3', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_related_posts_settings',
		'type'=> 'number'
	));

	// Single Posts Settings
	$wp_customize->add_section( 'automotive_centre_single_blog_settings', array(
		'title' => __( 'Single Post Settings', 'automotive-centre' ),
		'panel' => 'blog_post_parent_panel',
	));

	$wp_customize->add_setting( 'automotive_centre_single_blog_post_navigation_show_hide',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'automotive_centre_switch_sanitization'
	));
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_single_blog_post_navigation_show_hide', array(
		'label' => esc_html__( 'Post Navigation','automotive-centre' ),
		'section' => 'automotive_centre_single_blog_settings'
    )));

	//navigation text
	$wp_customize->add_setting('automotive_centre_single_blog_prev_navigation_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_single_blog_prev_navigation_text',array(
		'label'	=> __('Post Navigation Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'PREVIOUS', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_single_blog_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_single_blog_next_navigation_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_single_blog_next_navigation_text',array(
		'label'	=> __('Post Navigation Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'NEXT', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_single_blog_settings',
		'type'=> 'text'
	));

    //404 Page Setting
	$wp_customize->add_section('automotive_centre_404_page',array(
		'title'	=> __('404 Page Settings','automotive-centre'),
		'panel' => 'automotive_centre_panel_id',
	));	

	$wp_customize->add_setting('automotive_centre_404_page_title',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('automotive_centre_404_page_title',array(
		'label'	=> __('Add Title','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '404 Not Found', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_404_page_content',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));

	$wp_customize->add_control('automotive_centre_404_page_content',array(
		'label'	=> __('Add Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'Looks like you have taken a wrong turn, Dont worry, it happens to the best of us.', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_404_page',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_404_page_button_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_404_page_button_text',array(
		'label'	=> __('Add Button Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'GO BACK', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_404_page',
		'type'=> 'text'
	));

	//Social Icon Setting
	$wp_customize->add_section('automotive_centre_social_icon_settings',array(
		'title'	=> __('Social Icons Settings','automotive-centre'),
		'panel' => 'automotive_centre_panel_id',
	));	

	$wp_customize->add_setting('automotive_centre_social_icon_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_social_icon_font_size',array(
		'label'	=> __('Icon Font Size','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_social_icon_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_social_icon_padding',array(
		'label'	=> __('Icon Padding','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_social_icon_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_social_icon_width',array(
		'label'	=> __('Icon Width','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_social_icon_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_social_icon_height',array(
		'label'	=> __('Icon Height','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_social_icon_settings',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_social_icon_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_social_icon_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','automotive-centre' ),
		'section'     => 'automotive_centre_social_icon_settings',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Responsive Media Settings
	$wp_customize->add_section('automotive_centre_responsive_media',array(
		'title'	=> __('Responsive Media','automotive-centre'),
		'panel' => 'automotive_centre_panel_id',
	));

    $wp_customize->add_setting( 'automotive_centre_stickyheader_hide_show',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ));  
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_stickyheader_hide_show',array(
      'label' => esc_html__( 'Sticky Header','automotive-centre' ),
      'section' => 'automotive_centre_responsive_media'
    )));

    $wp_customize->add_setting( 'automotive_centre_resp_slider_hide_show',array(
      'default' => 0,
      'transport' => 'refresh',
      'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ));  
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_resp_slider_hide_show',array(
      'label' => esc_html__( 'Show / Hide Slider','automotive-centre' ),
      'section' => 'automotive_centre_responsive_media'
    )));

    $wp_customize->add_setting( 'automotive_centre_sidebar_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ));  
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_sidebar_hide_show',array(
      'label' => esc_html__( 'Show / Hide Sidebar','automotive-centre' ),
      'section' => 'automotive_centre_responsive_media'
    )));

    $wp_customize->add_setting( 'automotive_centre_resp_scroll_top_hide_show',array(
      'default' => 1,
      'transport' => 'refresh',
      'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ));  
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_resp_scroll_top_hide_show',array(
      'label' => esc_html__( 'Show / Hide Scroll To Top','automotive-centre' ),
      'section' => 'automotive_centre_responsive_media'
    )));

    $wp_customize->add_setting('automotive_centre_res_open_menu_icon',array(
		'default'	=> 'fas fa-bars',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Automotive_Centre_Fontawesome_Icon_Chooser(
        $wp_customize,'automotive_centre_res_open_menu_icon',array(
		'label'	=> __('Add Open Menu Icon','automotive-centre'),
		'transport' => 'refresh',
		'section'	=> 'automotive_centre_responsive_media',
		'setting'	=> 'automotive_centre_res_open_menu_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('automotive_centre_res_close_menu_icon',array(
		'default'	=> 'fas fa-times',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Automotive_Centre_Fontawesome_Icon_Chooser(
        $wp_customize,'automotive_centre_res_close_menu_icon',array(
		'label'	=> __('Add Close Menu Icon','automotive-centre'),
		'transport' => 'refresh',
		'section'	=> 'automotive_centre_responsive_media',
		'setting'	=> 'automotive_centre_res_close_menu_icon',
		'type'		=> 'icon'
	)));

	//Footer Text
	$wp_customize->add_section('automotive_centre_footer',array(
		'title'	=> __('Footer Settings','automotive-centre'),
		'panel' => 'automotive_centre_panel_id',
	));	

	//Selective Refresh
	$wp_customize->selective_refresh->add_partial('automotive_centre_footer_text', array( 
		'selector' => '.copyright p', 
		'render_callback' => 'automotive_centre_customize_partial_automotive_centre_footer_text', 
	));
	
	$wp_customize->add_setting('automotive_centre_footer_text',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control('automotive_centre_footer_text',array(
		'label'	=> __('Copyright Text','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( 'Copyright 2019, .....', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_footer',
		'type'=> 'text'
	));	

	$wp_customize->add_setting('automotive_centre_copyright_alingment',array(
        'default' => 'center',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'
	));
	$wp_customize->add_control(new Automotive_Centre_Image_Radio_Control($wp_customize, 'automotive_centre_copyright_alingment', array(
        'type' => 'select',
        'label' => __('Copyright Alignment','automotive-centre'),
        'section' => 'automotive_centre_footer',
        'settings' => 'automotive_centre_copyright_alingment',
        'choices' => array(
            'left' => esc_url(get_template_directory_uri()).'/assets/images/copyright1.png',
            'center' => esc_url(get_template_directory_uri()).'/assets/images/copyright2.png',
            'right' => esc_url(get_template_directory_uri()).'/assets/images/copyright3.png'
    ))));

    $wp_customize->add_setting('automotive_centre_copyright_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_copyright_padding_top_bottom',array(
		'label'	=> __('Copyright Padding Top Bottom','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_hide_show_scroll',array(
    	'default' => 1,
      	'transport' => 'refresh',
      	'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ));  
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_hide_show_scroll',array(
      	'label' => esc_html__( 'Show / Hide Scroll To Top','automotive-centre' ),
      	'section' => 'automotive_centre_footer'
    )));
     //Selective Refresh
	$wp_customize->selective_refresh->add_partial('automotive_centre_scroll_to_top_icon', array( 
		'selector' => '.scrollup i', 
		'render_callback' => 'automotive_centre_customize_partial_automotive_centre_scroll_to_top_icon', 
	));

    $wp_customize->add_setting('automotive_centre_scroll_to_top_icon',array(
		'default'	=> 'fas fa-long-arrow-alt-up',
		'sanitize_callback'	=> 'sanitize_text_field'
	));	
	$wp_customize->add_control(new Automotive_Centre_Fontawesome_Icon_Chooser(
        $wp_customize,'automotive_centre_scroll_to_top_icon',array(
		'label'	=> __('Add Scroll to Top Icon','automotive-centre'),
		'transport' => 'refresh',
		'section'	=> 'automotive_centre_footer',
		'setting'	=> 'automotive_centre_scroll_to_top_icon',
		'type'		=> 'icon'
	)));

	$wp_customize->add_setting('automotive_centre_scroll_to_top_font_size',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_scroll_to_top_font_size',array(
		'label'	=> __('Icon Font Size','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_scroll_to_top_padding',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_scroll_to_top_padding',array(
		'label'	=> __('Icon Top Bottom Padding','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_scroll_to_top_width',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_scroll_to_top_width',array(
		'label'	=> __('Icon Width','automotive-centre'),
		'description'	=> __('Enter a value in pixels Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_scroll_to_top_height',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_scroll_to_top_height',array(
		'label'	=> __('Icon Height','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_footer',
		'type'=> 'text'
	));

	$wp_customize->add_setting( 'automotive_centre_scroll_to_top_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_scroll_to_top_border_radius', array(
		'label'       => esc_html__( 'Icon Border Radius','automotive-centre' ),
		'section'     => 'automotive_centre_footer',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	$wp_customize->add_setting('automotive_centre_scroll_top_alignment',array(
        'default' => 'Right',
        'sanitize_callback' => 'automotive_centre_sanitize_choices'
	));
	$wp_customize->add_control(new Automotive_Centre_Image_Radio_Control($wp_customize, 'automotive_centre_scroll_top_alignment', array(
        'type' => 'select',
        'label' => __('Scroll To Top','automotive-centre'),
        'section' => 'automotive_centre_footer',
        'settings' => 'automotive_centre_scroll_top_alignment',
        'choices' => array(
            'Left' => esc_url(get_template_directory_uri()).'/assets/images/layout1.png',
            'Center' => esc_url(get_template_directory_uri()).'/assets/images/layout2.png',
            'Right' => esc_url(get_template_directory_uri()).'/assets/images/layout3.png'
    ))));

    //Woocommerce settings
	$wp_customize->add_section('automotive_centre_woocommerce_section', array(
		'title'    => __('WooCommerce Layout', 'automotive-centre'),
		'priority' => null,
		'panel'    => 'woocommerce',
	));

    //Woocommerce Shop Page Sidebar
	$wp_customize->add_setting( 'automotive_centre_woocommerce_shop_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ) );
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_woocommerce_shop_page_sidebar',array(
		'label' => esc_html__( 'Shop Page Sidebar','automotive-centre' ),
		'section' => 'automotive_centre_woocommerce_section'
    )));

    //Woocommerce Single Product page Sidebar
	$wp_customize->add_setting( 'automotive_centre_woocommerce_single_product_page_sidebar',array(
		'default' => 1,
		'transport' => 'refresh',
		'sanitize_callback' => 'automotive_centre_switch_sanitization'
    ) );
    $wp_customize->add_control( new Automotive_Centre_Toggle_Switch_Custom_Control( $wp_customize, 'automotive_centre_woocommerce_single_product_page_sidebar',array(
		'label' => esc_html__( 'Single Product Sidebar','automotive-centre' ),
		'section' => 'automotive_centre_woocommerce_section'
    )));

    //Products per page
    $wp_customize->add_setting('automotive_centre_products_per_page',array(
		'default'=> '9',
		'sanitize_callback'	=> 'automotive_centre_sanitize_float'
	));
	$wp_customize->add_control('automotive_centre_products_per_page',array(
		'label'	=> __('Products Per Page','automotive-centre'),
		'description' => __('Display on shop page','automotive-centre'),
		'input_attrs' => array(
            'step'             => 1,
			'min'              => 0,
			'max'              => 50,
        ),
		'section'=> 'automotive_centre_woocommerce_section',
		'type'=> 'number',
	));

    //Products per row
    $wp_customize->add_setting('automotive_centre_products_per_row',array(
		'default'=> '3',
		'sanitize_callback'	=> 'automotive_centre_sanitize_choices'
	));
	$wp_customize->add_control('automotive_centre_products_per_row',array(
		'label'	=> __('Products Per Row','automotive-centre'),
		'description' => __('Display on shop page','automotive-centre'),
		'choices' => array(
            '2' => '2',
			'3' => '3',
			'4' => '4',
        ),
		'section'=> 'automotive_centre_woocommerce_section',
		'type'=> 'select',
	));

	//Products padding
	$wp_customize->add_setting('automotive_centre_products_padding_top_bottom',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_products_padding_top_bottom',array(
		'label'	=> __('Products Padding Top Bottom','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_woocommerce_section',
		'type'=> 'text'
	));

	$wp_customize->add_setting('automotive_centre_products_padding_left_right',array(
		'default'=> '',
		'sanitize_callback'	=> 'sanitize_text_field'
	));
	$wp_customize->add_control('automotive_centre_products_padding_left_right',array(
		'label'	=> __('Products Padding Left Right','automotive-centre'),
		'description'	=> __('Enter a value in pixels. Example:20px','automotive-centre'),
		'input_attrs' => array(
            'placeholder' => __( '10px', 'automotive-centre' ),
        ),
		'section'=> 'automotive_centre_woocommerce_section',
		'type'=> 'text'
	));

	//Products box shadow
	$wp_customize->add_setting( 'automotive_centre_products_box_shadow', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_products_box_shadow', array(
		'label'       => esc_html__( 'Products Box Shadow','automotive-centre' ),
		'section'     => 'automotive_centre_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

	//Products border radius
    $wp_customize->add_setting( 'automotive_centre_products_border_radius', array(
		'default'              => '',
		'transport' 		   => 'refresh',
		'sanitize_callback'    => 'automotive_centre_sanitize_number_range'
	) );
	$wp_customize->add_control( 'automotive_centre_products_border_radius', array(
		'label'       => esc_html__( 'Products Border Radius','automotive-centre' ),
		'section'     => 'automotive_centre_woocommerce_section',
		'type'        => 'range',
		'input_attrs' => array(
			'step'             => 1,
			'min'              => 1,
			'max'              => 50,
		),
	) );

    // Has to be at the top
	$wp_customize->register_panel_type( 'Automotive_Centre_WP_Customize_Panel' );
	$wp_customize->register_section_type( 'Automotive_Centre_WP_Customize_Section' );
}

add_action( 'customize_register', 'automotive_centre_customize_register' );

load_template( trailingslashit( get_template_directory() ) . '/inc/logo/logo-resizer.php' );

if ( class_exists( 'WP_Customize_Panel' ) ) {
  	class Automotive_Centre_WP_Customize_Panel extends WP_Customize_Panel {
	    public $panel;
	    public $type = 'automotive_centre_panel';
	    public function json() {

	      $array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel', ) );
	      $array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
	      $array['content'] = $this->get_content();
	      $array['active'] = $this->active();
	      $array['instanceNumber'] = $this->instance_number;
	      return $array;
    	}
  	}
}

if ( class_exists( 'WP_Customize_Section' ) ) {
  	class Automotive_Centre_WP_Customize_Section extends WP_Customize_Section {
	    public $section;
	    public $type = 'automotive_centre_section';
	    public function json() {

	      $array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section', ) );
	      $array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
	      $array['content'] = $this->get_content();
	      $array['active'] = $this->active();
	      $array['instanceNumber'] = $this->instance_number;

	      if ( $this->panel ) {
	        $array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
	      } else {
	        $array['customizeAction'] = 'Customizing';
	      }
	      return $array;
    	}
  	}
}

// Enqueue our scripts and styles
function automotive_centre_customize_controls_scripts() {
	wp_enqueue_script( 'customizer-controls', get_theme_file_uri( '/assets/js/customizer-controls.js' ), array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'automotive_centre_customize_controls_scripts' );

/**
 * Singleton class for handling the theme's customizer integration.
 *
 * @since  1.0.0
 * @access public
 */
final class Automotive_Centre_Customize {

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up initial actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		// Register panels, sections, settings, controls, and partials.
		add_action( 'customize_register', array( $this, 'sections' ) );

		// Register scripts and styles for the controls.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_scripts' ), 0 );
	}

	/**
	 * Sets up the customizer sections.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $manager
	 * @return void
	*/
	public function sections( $manager ) {

		// Load custom sections.
		load_template( trailingslashit( get_template_directory() ) . '/inc/section-pro.php' );

		// Register custom section types.
		$manager->register_section_type( 'Automotive_Centre_Customize_Section_Pro' );

		// Register sections.
		$manager->add_section(new Automotive_Centre_Customize_Section_Pro($manager,'automotive_centre_upgrade_pro_link',array(
			'priority'   => 1,
			'title'    => esc_html__( 'AUTOMOTIVE PRO', 'automotive-centre' ),
			'pro_text' => esc_html__( 'UPGRADE PRO', 'automotive-centre' ),
			'pro_url'  => esc_url('https://www.vwthemes.com/themes/automotive-wordpress-theme/'),
		)));

		$manager->add_section(new Automotive_Centre_Customize_Section_Pro($manager,'automotive_centre_get_started_link',array(
				'priority'   => 1,
				'title'    => esc_html__( 'DOCUMENATATION', 'automotive-centre' ),
				'pro_text' => esc_html__( 'DOCS', 'automotive-centre' ),
				'pro_url'  => admin_url('themes.php?page=automotive_centre_guide'),
		)));
	}

	/**
	 * Loads theme customizer CSS.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function enqueue_control_scripts() {

		wp_enqueue_script( 'automotive-centre-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/js/customize-controls.js', array( 'customize-controls' ) );

		wp_enqueue_style( 'automotive-centre-customize-controls', trailingslashit( esc_url(get_template_directory_uri()) ) . '/assets/css/customize-controls.css' );
	}
}

// Doing this customizer thang!
Automotive_Centre_Customize::get_instance();