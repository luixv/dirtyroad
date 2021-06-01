<?php

/**
 * Widget Settings.
 */
function youzify_user_badges_widget_settings() {

	global $Youzify_Settings;

	if ( ! defined( 'myCRED_BADGE_VERSION' ) ) {

        $Youzify_Settings->get_field(
            array(
                'msg_type'  => 'info',
                'type'      => 'msgBox',
                'id'        => 'youzify_msgbox_user_badges_widget_notice',
                'title'     => __( 'How to activate user badges widget?', 'youzify' ),
                'msg'       => sprintf( __( 'Please install the <a href="%1s"> MyCRED Plugin</a> and <strong>MyCRED Badges Extension</strong> to activate the user badges widget.'), 'https://wordpress.org/plugins/mycred/' )
            )
        );
	} else {

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'General Settings', 'youzify' ),
                'type'  => 'openBox'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Display Title', 'youzify' ),
                'id'    => 'youzify_wg_user_badges_display_title',
                'desc'  => __( 'Show widget title', 'youzify' ),
                'type'  => 'checkbox'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Widget Title', 'youzify' ),
                'id'    => 'youzify_wg_user_badges_title',
                'desc'  => __( 'Add widget title', 'youzify' ),
                'type'  => 'text'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Loading Effect', 'youzify' ),
                'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
                'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
                'id'    => 'youzify_user_badges_load_effect',
                'type'  => 'select'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Allowed Badges Number', 'youzify' ),
                'id'    => 'youzify_wg_max_user_badges_items',
                'desc'  => __( 'Maximum number of badges to display', 'youzify' ),
                'type'  => 'number'
            )
        );

        $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    }

    do_action( 'youzify_user_badges_widget_settings' );

}