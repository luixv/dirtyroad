<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/plugins
 * @since      1.0.0
 *
 * @package    Shortcodes_For_Buddypress
 * @subpackage Shortcodes_For_Buddypress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Shortcodes_For_Buddypress
 * @subpackage Shortcodes_For_Buddypress/admin
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Shortcodes_For_Buddypress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Shortcodes_For_Buddypress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Shortcodes_For_Buddypress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( 'settings_page_bpsh-shortcodes' === $hook ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/shortcodes-for-buddypress-admin.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Shortcodes_For_Buddypress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Shortcodes_For_Buddypress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( 'settings_page_bpsh-shortcodes' === $hook ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/shortcodes-for-buddypress-admin.js', array( 'jquery' ), $this->version, false );
		}

	}

	public function buddypress_shortcodes_option_page() {
		add_submenu_page( 'options-general.php', esc_html__( 'Shortcodes for BP', $this->plugin_name ), esc_html__( 'Shortcodes for BP', $this->plugin_name ), 'manage_options', 'bpsh-shortcodes', array( $this, 'buddypress_shortcodes_option_page_callback' ) );
	}

	public function buddypress_shortcodes_option_page_callback() {
		?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<div class="bpsh-admin-screen">
				<div class="bpsh-admin-content">
					<h1 class="wp-heading-inline"><?php esc_html_e( 'Wbcom Designs – Shortcodes & Elementor Widgets For BuddyPress', 'shortcodes-for-buddypress' ); ?></h1>
					<?php
					if ( isset( $_GET['tab'] ) ) {
						$this->buddypress_shortcode_admin_tabs( $_GET['tab'] );
						$this->buddypress_shortcode_option_pages( $_GET['tab'] );
					} else {
						$this->buddypress_shortcode_admin_tabs( 'general' );
						$this->buddypress_shortcode_option_pages( 'general' );
					}
					?>
				</div>
			</div>
	</div>
		<?php
	}

	public function buddypress_shortcode_admin_tabs( $current = 'general' ) {
		$tabs = array(
			'general'               => __( 'General', 'shortcodes-for-buddypress' ),
			'activity-listing'      => __( 'Activities', 'shortcodes-for-buddypress' ),
			'members-listing'       => __( 'Members', 'shortcodes-for-buddypress' ),
			'groups-listing'        => __( 'Groups', 'shortcodes-for-buddypress' ),
			'notifications-listing' => __( 'User Notification', 'shortcodes-for-buddypress' ),
		);
		echo '<h2 class="nav-tab-wrapper">';
		foreach ( $tabs as $tab => $name ) {
			$class = ( $tab == $current ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab ' . $class . '" href="?page=bpsh-shortcodes&tab=' . $tab . '">' . $name . '</a>';

		}
		echo '</h2>';

	}

	public function buddypress_shortcode_option_pages( $current = 'general' ) {
		if ( empty( $current ) ) {
			return;
		}
		switch ( $current ) {
			case 'general':
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/template/shortcodes-for-buddypress-general.php';
				break;
			case 'activity-listing':
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/template/shortcodes-for-buddypress-activity-listing.php';
				break;
			case 'members-listing':
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/template/shortcodes-for-buddypress-members-listing.php';
				break;
			case 'groups-listing':
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/template/shortcodes-for-buddypress-groups-listing.php';
				break;
			case 'notifications-listing':
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/template/shortcodes-for-buddypress-notifications-listing.php';
				break;
			default:
					require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/template/shortcodes-for-buddypress-general.php';
				break;
		}

	}

	public function buddypress_shortcodes_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page=bpsh-shortcodes">' . esc_html__( 'Documentation', 'shortcodes-for-buddypress' ) . '</a>';
		array_push( $links, $settings_link );

		return $links;
	}
}
