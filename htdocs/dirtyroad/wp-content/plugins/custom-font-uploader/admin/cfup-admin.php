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

if ( ! class_exists( 'Cfup_AdminPage' ) ) {

	/**
	 * Add admin page for displaying buddypress fitness settings.
	 *
	 * @package custom-font-uploader
	 * @version 1.0.0
	 * @author  wbcomdesigns
	 */
	class Cfup_AdminPage {
		private $plugin_slug  = 'custom-font-uploader-settings',
		$plugin_settings_tabs = array();

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function __construct() {
			add_action( 'admin_menu', array( $this, 'cfup_add_menu_page' ) );
			add_action( 'admin_init', array( $this, 'cfup_register_general_settings' ) );
			add_action( 'admin_init', array( $this, 'cfup_register_custom_font_settings' ) );
			add_action( 'admin_init', array( $this, 'cfup_register_google_font_settings' ) );
			add_action( 'admin_init', array( $this, 'cfup_register_support_settings' ) );
		}

		/**
		 * Actions performed to create a custom menu on loading admin_menu.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_add_menu_page() {
			// add_menu_page(__('Custom Font Uploader', 'cfup'), __('Font Uploader', 'cfup'), 'manage_options', $this->plugin_slug, array( $this, 'cfup_admin_settings_page' ), 'dashicons-editor-textcolor', 4);

			if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
				add_menu_page( esc_html__( 'WB Plugins', 'cfup' ), esc_html__( 'WB Plugins', 'cfup' ), 'manage_options', 'wbcomplugins', array( $this, 'cfup_admin_settings_page' ), 'dashicons-lightbulb', 59 );
				add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'cfup' ), esc_html__( 'General', 'cfup' ), 'manage_options', 'wbcomplugins' );
			}
			add_submenu_page( 'wbcomplugins', esc_html__( 'Custom Font Uploader Settings Page', 'cfup' ), esc_html__( 'Font Uploader', 'cfup' ), 'manage_options', 'custom-font-uploader-settings', array( $this, 'cfup_admin_settings_page' ) );
		}

		/**
		 * Actions performed to create a custom setting page.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_admin_settings_page() {
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'cfup-general-settings';
			?>
		 <div class="wrap">
			 <div class="blpro-header">
				<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
				<h1 class="wbcom-plugin-heading">
					<?php esc_html_e( 'Custom Font Uploader : Enqueue your fonts from Dashboard', 'cfup' ); ?>
				</h1>
			</div>
			<div class="wbcom-admin-settings-page">
				<div class="wbcom-tabs-section">
					<?php $this->cfup_plugin_settings_tabs(); ?>
				</div>
				<div class="wbcom-tab-content">
					<p><?php esc_html_e( 'This plugin lets you upload your own font files and apply them to any element of your website.', 'cfup' ); ?></p>
					<form action="" method="POST" id="<?php echo $tab; ?>-settings-form" enctype="multipart/form-data">
					<?php do_settings_sections( $tab ); ?>
					</form>
				</div>
			</div>
		 </div>
			<?php
		}

		/**
		 * Actions performed to create a custom setting tab.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_plugin_settings_tabs() {
			$current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'cfup-general-settings';
			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}

		/**
		 * Actions performed to create a general setting tab.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_register_general_settings() {
			$this->plugin_settings_tabs['cfup-general-settings'] = __( 'General', 'cfup' );
			register_setting( 'cfup-general-settings', 'cfup-general-settings' );
			add_settings_section( 'section_general', ' ', array( &$this, 'cfup_general_settings_section' ), 'cfup-general-settings' );
		}

		/**
		 * Actions performed to create a general setting content.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_general_settings_section() {
			if ( file_exists( dirname( __FILE__ ) . '/cfup-general-settings.php' ) ) {
				include_once dirname( __FILE__ ) . '/cfup-general-settings.php';
			}
		}

		/**
		 * Actions performed to create a custom font setting tab.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_register_custom_font_settings() {
			$this->plugin_settings_tabs['custom-font-uploader-settings'] = __( 'Upload Fonts', 'cfup' );
			register_setting( 'custom-font-uploader-settings', 'custom-font-uploader-settings' );
			add_settings_section( 'section_custom_font', ' ', array( &$this, 'cfup_custom_fonts_section' ), 'custom-font-uploader-settings' );
		}

		/**
		 * Actions performed to create a custom font setting content.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_custom_fonts_section() {
			if ( file_exists( dirname( __FILE__ ) . '/cfup-customfont-settings.php' ) ) {
				include_once dirname( __FILE__ ) . '/cfup-customfont-settings.php';
			}
		}

		/**
		 * Actions performed to create a google font setting tab.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_register_google_font_settings() {
			$this->plugin_settings_tabs['google-font-uploader-settings'] = __( 'Google Fonts', 'cfup' );
			register_setting( 'google-font-uploader-settings', 'google-font-uploader-settings' );
			add_settings_section( 'section_google_font', ' ', array( &$this, 'cfup_google_fonts_section' ), 'google-font-uploader-settings' );
		}

		/**
		 * Actions performed to create a google font setting content.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_google_fonts_section() {
			if ( file_exists( dirname( __FILE__ ) . '/cfup-googlefont-settings.php' ) ) {
				include_once dirname( __FILE__ ) . '/cfup-googlefont-settings.php';
			}
		}

		/**
		 * Actions performed to create a support setting tab.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_register_support_settings() {
			 $this->plugin_settings_tabs['cfup-support'] = __( 'Support', 'cfup' );
			register_setting( 'cfup-support', 'cfup-support' );
			add_settings_section( 'section_support', ' ', array( &$this, 'cfup_section_support' ), 'cfup-support' );
		}

		/**
		 * Actions performed to create a support section.
		 *
		 * @version 1.0.0
		 * @author  wbcomdesigns
		 */
		function cfup_section_support() {
			if ( file_exists( dirname( __FILE__ ) . '/cfup-support.php' ) ) {
				include_once dirname( __FILE__ ) . '/cfup-support.php';
			}
		}
	}
	new Cfup_AdminPage();
}
