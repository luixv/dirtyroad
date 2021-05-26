<?php
add_action( 'customize_register', 'mp_emmet_customize_backup_register' );

function mp_emmet_customize_backup_register( $wp_customize ) {

	$wp_customize->add_setting( 'save_customizer_backup', array(
		'default'           => 0,
		'sanitize_callback' => 'mp_emmet_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'save_customizer_backup', array(
			'label'       => esc_html__( 'Backup Emmet Option Content', 'emmet-lite' ),
			'description' => esc_html__( 'Emmet options contains significant content creation.', 'emmet-lite' ) . ' ' .
			                 esc_html__( 'Content creation is in the options that require some form of text input.', 'emmet-lite' ) . ' ' .
			                 esc_html__( 'Select the checkbox above to save your content to a Private page, available only to users signed in with admin privileges.', 'emmet-lite' ) . ' ' .
			                 esc_html__( 'You will then be able to access this content while setting up a different theme. ', 'emmet-lite' ),
			'section'     => 'theme_general_section',
			'settings'    => 'save_customizer_backup',
			'type'        => 'checkbox',
			'priority'    => 20
		)
	);

}

add_action( 'customize_save_after', 'mp_emmet_save_customizer_backup' );

function mp_emmet_save_customizer_backup() {
	if ( ! get_theme_mod( 'save_customizer_backup', false ) ) {
		return;
	}

	$page_content = '';
	$page_content .= esc_html__( 'Emmet Customizer Backup', 'emmet-lite' ) . PHP_EOL;
	$page_content .= '<!-- ' . esc_html__( 'This page contains a backup of content created by the Emmet WordPress Theme in customizer.', 'emmet-lite' ) . PHP_EOL;
	$page_content .= esc_html__( 'The purpose for the backup is to prevent content loss on theme switch.', 'emmet-lite' ) . PHP_EOL;
	$page_content .= esc_html__( 'When a user switches themes this content will still be available to the user when setting up their site on the new theme.', 'emmet-lite' ) . PHP_EOL;
	$page_content .= esc_html__( 'Please note the following : ', 'emmet-lite' ) . PHP_EOL;
	$page_content .= ' * ' . esc_html__( 'Leave this page as private, available only to users with admin privileges.', 'emmet-lite' ) . PHP_EOL;
	$page_content .= ' * ' . esc_html__( 'You can delete this page any time and regenerate it from within the Emmet options menu, General section in Customizer.', 'emmet-lite' ) . ' -->' . PHP_EOL;

	$page_content .= mp_emmet_generate_customizer_backup_content();

	$page_attr                   = [];
	$page_attr['post_title']     = 'Emmet Customizer Backup';
	$page_attr['post_type']      = 'page';
	$page_attr['post_status']    = 'private';
	$page_attr['comment_status'] = 'closed';
	$page_attr['post_content']   = $page_content;
	$generated_page              = get_page_by_title( $page_attr['post_title'] );

	if ( isset( $generated_page ) && $generated_page->ID !== '' ) {
		$page_attr['ID'] = $generated_page->ID;
	}

	wp_insert_post( $page_attr );

}

function mp_emmet_generate_customizer_backup_content() {

	$settings = [
		__( 'Contact Information', 'emmet-lite' ) => [
			__( 'Contact Information 1', 'emmet-lite' ) => get_theme_mod( 'theme_phone_info', false ),
			__( 'Contact Information 2', 'emmet-lite' ) => get_theme_mod( 'theme_location_info', false ),
		],
		__( 'Social Links', 'emmet-lite' ) => [
			__( 'Facebook link', 'emmet-lite' ) => get_theme_mod( 'theme_facebook_link', false ),
			__( 'Twitter link', 'emmet-lite' ) => get_theme_mod( 'theme_twitter_link', false ),
			__( 'LinkedIn link', 'emmet-lite' ) => get_theme_mod( 'theme_linkedin_link', false ),
			__( 'Google+ link', 'emmet-lite' ) => get_theme_mod( 'theme_google_plus_link', false ),
			__( 'Pinterest link', 'emmet-lite' ) => get_theme_mod( 'theme_pinterest_link', false ),
			__( 'Instagram link', 'emmet-lite' ) => get_theme_mod( 'theme_instagram_link', false ),
			__( 'Tumblr link', 'emmet-lite' ) => get_theme_mod( 'theme_tumblr_link', false ),
			__( 'Youtube link', 'emmet-lite' ) => get_theme_mod( 'theme_youtube_link', false ),
			__( 'Vk link', 'emmet-lite' ) => get_theme_mod( 'theme_vk_link', false ),
			__( 'Skype name', 'emmet-lite' ) => get_theme_mod( 'theme_skype_link', false ),
			__( 'Meetup link', 'emmet-lite' ) => get_theme_mod( 'theme_meetup_link', false ),
		],
		__( 'Big Title Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_description', false ),
			__( 'First button label', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_brandbutton_label', false ),
			__( 'First button url', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_brandbutton_url', false ),
			__( 'Second button label', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_whitebutton_label', false ),
			__( 'Second button url', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_whitebutton_url', false ),
			__( 'Background image', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_bg', false ),
			__( 'Video Source MP4', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_video_mp4', false ),
			__( 'Video Source OGG', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_video_ogg', false ),
			__( 'Video Source WEBM', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_video_webm', false ),
			__( 'Video overlay', 'emmet-lite' ) => get_theme_mod( 'theme_bigtitle_video_cover', false ),
			__( 'Shortcode of slider', 'emmet-lite' ) => get_theme_mod( 'theme_mp_slider', false ),
		],
		__( 'First Feature Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_welcome_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_welcome_description', false ),
			__( 'Button label', 'emmet-lite' ) => get_theme_mod( 'theme_welcome_button_label', false ),
			__( 'Button url', 'emmet-lite' ) => get_theme_mod( 'theme_welcome_button_url', false ),
			__( 'Image', 'emmet-lite' ) => get_theme_mod( 'theme_welcome_image', false ),
		],
		__( 'Second Feature Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_third_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_third_description', false ),
			__( 'Button label', 'emmet-lite' ) => get_theme_mod( 'theme_third_button_label', false ),
			__( 'Button url', 'emmet-lite' ) => get_theme_mod( 'theme_third_button_url', false ),
			__( 'Image', 'emmet-lite' ) => get_theme_mod( 'theme_third_image', false ),
		],
		__( 'Accent Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_accent_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_accent_description', false ),
			__( 'Button label', 'emmet-lite' ) => get_theme_mod( 'theme_accent_button_label', false ),
			__( 'Button url', 'emmet-lite' ) => get_theme_mod( 'theme_accent_button_url', false ),
		],
		__( 'Subscribe Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_subscribe_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_subscribe_description', false ),
		],
		__( 'Latest News Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_lastnews_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_lastnews_description', false ),
			__( 'Button label', 'emmet-lite' ) => get_theme_mod( 'theme_lastnews_button_label', false ),
			__( 'Button url', 'emmet-lite' ) => get_theme_mod( 'theme_lastnews_button_url', false ),
		],
		__( 'Features Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_features_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_features_description', false ),
		],
		__( 'Portfolio Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_portfolio_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_portfolio_description', false ),
			__( 'Button label', 'emmet-lite' ) => get_theme_mod( 'theme_portfolio_button_label', false ),
			__( 'Button url', 'emmet-lite' ) => get_theme_mod( 'theme_portfolio_button_url', false ),
		],
		__( 'Packages Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_plan_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_plan_description', false ),
		],
		__( 'Team Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_team_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_team_description', false ),
		],
		__( 'Testimonials Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_testimonials_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_testimonials_description', false ),
		],
		__( 'Contact Us Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_contactus_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_contactus_description', false ),
			__( 'Contact form shortcode', 'emmet-lite' ) => get_theme_mod( 'theme_contactus_shortcode', false ),
			__( 'Email address', 'emmet-lite' ) => get_theme_mod( 'theme_contactus_email', false ),
			__( 'Button label', 'emmet-lite' ) => get_theme_mod( 'theme_contactus_button_label', false ),
		],
		__( 'Call To Action Section', 'emmet-lite' ) => [
			__( 'Title', 'emmet-lite' ) => get_theme_mod( 'theme_install_title', false ),
			__( 'Description', 'emmet-lite' ) => get_theme_mod( 'theme_install_description', false ),
			__( 'First button label', 'emmet-lite' ) => get_theme_mod( 'theme_install_brandbutton_label', false ),
			__( 'First button url', 'emmet-lite' ) => get_theme_mod( 'theme_install_brandbutton_url', false ),
			__( 'Second button label', 'emmet-lite' ) => get_theme_mod( 'theme_install_whitebutton_label', false ),
			__( 'Second button url', 'emmet-lite' ) => get_theme_mod( 'theme_install_whitebutton_url', false ),
		]
	];

	$content = '';

	foreach ( $settings as $title => $theme_mods ) {

		if ( ! array_filter( array_values( $theme_mods ) ) ) {
			continue;
		}

		$content .= '## ' . esc_html( $title ) . ' ##' . PHP_EOL;

		foreach ( $theme_mods as $theme_mod_title => $theme_mod_value ) {
			if ( $theme_mod_value ) {
				$content .= '# ' . esc_html( $theme_mod_title ) . ' #' . PHP_EOL;
				$content .= wp_kses_post( $theme_mod_value ) . PHP_EOL;
			}
		}

		$content .= PHP_EOL;

	}

	return $content;

}
