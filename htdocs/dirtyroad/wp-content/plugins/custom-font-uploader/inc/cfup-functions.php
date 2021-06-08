<?php
/**
 * Exit if accessed directly.
 *
 * @package custom-font-uploader
 * @version 1.0.0
 * @author  wbcomdesigns
 */

add_action( 'wp_ajax_delete_customfont', 'delete_customfont' );
add_action( 'wp_ajax_nopriv_delete_customfont', 'delete_customfont' );

/**
 * Function for deleting fonts using upload method.
 *
 * @version 1.0.0
 * @author  wbcomdesigns
 */
function delete_customfont() {

	$fonts_db_data    = get_option( 'font_file_name', true );
	$delckey          = sanitize_text_field( wp_unslash( $_POST['del_key'] ) );
	$custom_font_file = CUSTOM_FONT_UPLOADER_UPLOADS_DIR_PATH . $fonts_db_data[ $delckey ];
	unlink( realpath( $custom_font_file ) );
	unset( $fonts_db_data[ $delckey ] );
	update_option( 'font_file_name', $fonts_db_data );
	echo 'custom-font-deleted';
	die;
}

add_action( 'wp_ajax_delete_googlefont', 'delete_googlefont' );
add_action( 'wp_ajax_nopriv_delete_googlefont', 'delete_googlefont' );

/**
 * Function for deleting fonts using google fonts.
 *
 * @version 1.0.0
 * @author  wbcomdesigns
 */
function delete_googlefont() {
	$gfonts_db_data = get_option( 'googlefont_file_name', true );
	if ( isset( $_POST['del_gkey'] ) ) {
		$del_gkey = sanitize_text_field( wp_unslash( $_POST['del_gkey'] ) );
	}

	unset( $gfonts_db_data[ $del_gkey ] );
	update_option( 'googlefont_file_name', $gfonts_db_data );
	echo 'google-font-deleted';
	die;
}

/**
 * Get google fonts through google api and pass it in curl.
 *
 * @version 1.0.0
 * @author  wbcomdesigns
 * @param   string $api_key contain api key for font.
 */
function cfup_get_google_fonts( $api_key ) {
	$api_url  = 'https://www.googleapis.com/webfonts/v1/webfonts';
	$params   = array( 'key' => $api_key );
	$url      = add_query_arg( $params, esc_url_raw( $api_url ) );
	$response = wp_remote_get( esc_url_raw( $url ) );

	// Check the response code.
	$response_code    = wp_remote_retrieve_response_code( $response );
	$response_message = wp_remote_retrieve_response_message( $response );

	if ( 200 != $response_code && ! empty( $response_message ) ) {
		return new WP_Error( $response_code, $response_message );
	} elseif ( 200 != $response_code ) {
		return new WP_Error( $response_code, 'Unknown error occurred' );
	} else {
		// Everything seems OK, retreive the fonts.
		return json_decode( wp_remote_retrieve_body( $response ) );
	}
}

/*
 * Add CFU Custom font group in elementor font group
 *
 */
add_filter( 'elementor/fonts/groups', 'cfup_elementor_group', 20 );
function cfup_elementor_group( $font_groups ) {
	$new_group[ 'cfu-custom-fonts' ] = __( 'CFU Custom', 'custom-font-uploader' );
	$font_groups                   = $new_group + $font_groups;
	return $font_groups;
}

/*
 * Add CFU Custom font lists in elementor fonts
 *
 */
add_filter( 'elementor/fonts/additional_fonts', 'cfup_elementor_additional_fonts', 20 );
function cfup_elementor_additional_fonts( $additional_fonts ) {
	$custom_fonts = get_option( 'font_file_name', true );
	if ( ! is_array( $custom_fonts ) ) {
		$custom_fonts = array();
	}
	if ( !empty($custom_fonts) ) {
		foreach( $custom_fonts as $key=>$value) {
			$additional_fonts[ $key ] = 'cfu-custom-fonts';
		}
	}	
	return $additional_fonts;
}


// Beaver builder theme customizer, beaver buidler page builder.
add_filter( 'fl_theme_system_fonts', 'cfup_bb_custom_fonts'  );
add_filter( 'fl_builder_font_families_system','cfup_bb_custom_fonts' );
function cfup_bb_custom_fonts( $bb_fonts ) {
	
	$fonts = get_option( 'font_file_name', true );
	if ( ! is_array( $fonts ) ) {
		$fonts = array();
	}
	
	$custom_fonts = array();	
	if ( ! empty( $fonts ) ) {
		foreach ( $fonts as $font_family_name => $fonts_url ) {
			$custom_fonts[ $font_family_name ] = array(
				'fallback' => 'Verdana, Arial, sans-serif',
				'weights'  => array( '100', '200', '300', '400', '500', '600', '700', '800', '900' ),
			);
		}
	}
	
	return array_merge( $bb_fonts, $custom_fonts );
}



add_action( 'astra_customizer_font_list','cfup_add_customizer_font_list'  );
function cfup_add_customizer_font_list( $value) {
	
	$fonts = get_option( 'font_file_name', true );
	if ( ! is_array( $fonts ) ) {
		$fonts = array();
	}

	echo '<optgroup label="' . esc_attr( 'CFU Custom' ) . '">';

	foreach ( $fonts as $font => $links ) {
		echo '<option value="' . esc_attr( $font ) . '" ' . selected( $font, $value, false ) . '>' . esc_attr( $font ) . '</option>';
	}
}

add_filter( 'kirki_fonts_standard_fonts', 'cfup_kirki_fonts_all' );
function cfup_kirki_fonts_all( $kirki_fonts ){
	$fonts = get_option( 'font_file_name', true );
	if ( ! is_array( $fonts ) ) {
		$fonts = array();
	}

	if ( ! empty( $fonts ) ) {
		foreach ( $fonts as $font_family_name => $fonts_url ) {
			$kirki_fonts[ $font_family_name ] = array(
				'label' => $font_family_name,
				'stack' => $font_family_name.', Verdana, Arial, sans-serif',
			);
		}
	}
	return $kirki_fonts;
}