<?php

/**
 * Plugin Schemes Settings.
 */

function youzify_schemes_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title'     => __( 'Info', 'youzify' ),
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'youzify_msgbox_profile_schemes',
            'msg'       => __( 'If you want to use the <strong>Custom Profile Scheme Color</strong>, make sure that you <strong>enabled</strong> the custom scheme button.', 'youzify' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Custom Scheme?', 'youzify' ),
            'desc'  => __( 'Wanna use custom scheme color?', 'youzify' ),
            'id'    => 'youzify_enable_profile_custom_scheme',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Custom Scheme', 'youzify' ),
            'desc'  => __( 'Profile custom scheme color', 'youzify' ),
            'id'    => 'youzify_profile_custom_scheme_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Profile Schemes', 'youzify' ),
            'class' => 'uk-img-radius youzify-plugin-schemes uk-center-elements',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'        =>  'youzify_profile_scheme',
            'use_class' => true,
            'type'      => 'imgSelect',
            'opts'      => array(
                'youzify-blue-scheme', 'youzify-orange-scheme', 'youzify-red-scheme', 'youzify-green-scheme',
                'youzify-crimson-scheme', 'youzify-aqua-scheme', 'youzify-purple-scheme', 'youzify-brown-scheme',
                'youzify-yellow-scheme', 'youzify-pink-scheme', 'youzify-darkblue-scheme', 'youzify-darkgreen-scheme',
                'youzify-darkorange-scheme', 'youzify-gray-scheme', 'youzify-lightblue-scheme', 'youzify-darkgray-scheme'
            )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}