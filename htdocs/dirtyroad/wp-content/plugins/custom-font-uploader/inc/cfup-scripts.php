<?php
/**
 * Exit if accessed directly.
 *
 * @package custom-font-uploader
 * @version 1.0.0
 * @author  wbcomdesigns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Cfup_Styles_Scripts' ) ) {

	/**
	 * Class to add custom scripts and styles for this plugin
	 *
	 * @since  1.0.0
	 * @author Wbcom Designs
	 */
	class Cfup_Styles_Scripts {

		/**
		 * Constructor.
		 *
		 * @since  1.0.0
		 * @access public
		 * @author Wbcom Designs
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'cfup_enqueue_public_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'cfup_enqueue_admin_scripts' ) );
			add_action( 'wp_head', array( $this, 'cfup_custom_fonts_enqueue' ) );
		}

		/**
		 * Actions performed to enqueue scripts on public end.
		 *
		 * @since  1.0.0
		 * @access public
		 * @author Wbcom Designs
		 */
		public function cfup_enqueue_public_scripts() {
			$cfup_google_fonts_options = get_option( 'googlefont_file_name', true );
			if ( ! is_array( $cfup_google_fonts_options ) ) {
				$cfup_google_fonts_options = array();
			}

			// Google api url.
			$googleapis_url = 'http://fonts.googleapis.com/css?family=';

			// Check if ssl is activated and switch to https.
			if ( is_ssl() ) {
				$googleapis_url = str_replace( 'http:', 'https:', $googleapis_url );
			}

			// Enquire only the selected fonts.
			if ( isset( $cfup_google_fonts_options ) ) {
				foreach ( $cfup_google_fonts_options as $cfup_google_font_key => $cfup_google_font ) {
					wp_register_style( 'font-style-' . $cfup_google_font_key, $googleapis_url . $cfup_google_font_key );
					wp_enqueue_style( 'font-style-' . $cfup_google_font_key );
				}
			}
		}

		/**
		 * Actions performed to enqueue scripts on admin end.
		 *
		 * @since  1.0.0
		 * @access public
		 * @author Wbcom Designs
		 */
		public function cfup_enqueue_admin_scripts() {
			if ( strpos( $_SERVER['REQUEST_URI'], 'custom-font-uploader-settings' ) !== false ) {
				wp_enqueue_style( 'cfup-cfup-css', CUSTOM_FONT_UPLOADER_PLUGIN_URL . 'admin/assets/css/cfup.css' );
				wp_enqueue_style( 'cfup-select2css', CUSTOM_FONT_UPLOADER_PLUGIN_URL . 'admin/assets/css/select2.css' );
				wp_enqueue_script( 'cfup-select2js', CUSTOM_FONT_UPLOADER_PLUGIN_URL . 'admin/assets/js/select2.js' );
				wp_enqueue_script( 'custom-font-uploader-admin', plugins_url( 'admin\assets\js\custom-font-uploader-admin.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.0.0', false );

				wp_localize_script(
					'custom-font-uploader-admin',
					'cfu_ajax_object',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
					)
				);
			}
		}

		/**
		 * Actions performed to enqueue custom added fonts.
		 *
		 * @since  1.0.0
		 * @access public
		 * @author Wbcom Designs
		 */
		public function cfup_custom_fonts_enqueue() {
			$cfup_custom_fonts_options = get_option( 'font_file_name', true );
			if ( ! is_array( $cfup_custom_fonts_options ) ) {
				$cfup_custom_fonts_options = array();
			}

			if ( ! empty( $cfup_custom_fonts_options ) ) {
				$custom_css  = '';
				$custom_css .= '<style type="text/css" id="custom_fonts">';
				foreach ( $cfup_custom_fonts_options as  $custom_fontname => $cfup_custom_font ) {
					$css  = '@font-face {';
					$css .= "\n";
					$css .= '   font-family: ' . $custom_fontname . ';';
					$css .= "\n";
					$css .= '   src: url(' . CUSTOM_FONT_UPLOADER_UPLOADS_DIR_URL . $cfup_custom_font . ');';
					$css .= "\n";
					$css .= '   font-weight: normal;';
					$css .= "\n";
					$css .= '}';

					$custom_css .= $css;
				}
				$custom_css .= '</style>';
				echo $custom_css;
			}
		}
	}
	new Cfup_Styles_Scripts();
}
