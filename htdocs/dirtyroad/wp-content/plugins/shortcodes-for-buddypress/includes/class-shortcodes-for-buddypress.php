<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wbcomdesigns.com/plugins
 * @since      1.0.0
 *
 * @package    Shortcodes_For_Buddypress
 * @subpackage Shortcodes_For_Buddypress/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Shortcodes_For_Buddypress
 * @subpackage Shortcodes_For_Buddypress/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Shortcodes_For_Buddypress {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Shortcodes_For_Buddypress_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SHORTCODES_FOR_BUDDYPRESS_VERSION' ) ) {
			$this->version = SHORTCODES_FOR_BUDDYPRESS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name     = 'shortcodes-for-buddypress';
		$this->plugin_basename = SHORTCODES_FOR_BUDDYPRESS_PLUGIN_BASENAME;
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Shortcodes_For_Buddypress_Loader. Orchestrates the hooks of the plugin.
	 * - Shortcodes_For_Buddypress_i18n. Defines internationalization functionality.
	 * - Shortcodes_For_Buddypress_Admin. Defines all hooks for the admin area.
	 * - Shortcodes_For_Buddypress_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shortcodes-for-buddypress-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shortcodes-for-buddypress-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-shortcodes-for-buddypress-admin.php';

		/**
		 * The class responsible review on plugin by user.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-shortcode-for-buddypress-user-review.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shortcodes-for-buddypress-public.php';

		$this->loader = new Shortcodes_For_Buddypress_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Shortcodes_For_Buddypress_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Shortcodes_For_Buddypress_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Shortcodes_For_Buddypress_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'plugin_action_links_' . $this->plugin_basename, $plugin_admin, 'buddypress_shortcodes_settings_link' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'buddypress_shortcodes_option_page' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Shortcodes_For_Buddypress_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// $this->loader->add_filter( 'bp_is_current_component', $plugin_public, 'buddypress_shortcodes_bp_is_current_component', 5, 1 );
		$this->loader->add_filter( 'bp_current_component', $plugin_public, 'buddypress_shortcodes_bp_current_component', 5, 1 );

		$this->loader->add_filter( 'bp_nouveau_get_search_primary_object', $plugin_public, 'buddypress_shortcodes_bp_nouveau_get_search_primary_object', 10, 1 );

		$this->loader->add_filter( 'body_class', $plugin_public, 'buddypress_shortcodes_body_classes' );
		$this->loader->add_shortcode( 'activity-listing', $plugin_public, 'buddypress_shortcodes_activity_listing' );
		$this->loader->add_shortcode( 'members-listing', $plugin_public, 'buddypress_shortcodes_members_listing' );
		$this->loader->add_shortcode( 'groups-listing', $plugin_public, 'buddypress_shortcodes_group_listing' );
		$this->loader->add_shortcode( 'notifications-listing', $plugin_public, 'buddypress_shortcodes_notifications' );

		$this->loader->add_filter( 'bp_core_get_js_strings', $plugin_public, 'buddypress_shortcodes_activity_localize_scripts', 10, 1 );
		
		$this->loader->add_filter( 'bp_nouveau_register_scripts', $plugin_public, 'buddypress_shortcodes_bp_nouveau_register_scripts', 20 );
		
		$this->loader->add_filter( 'bp_core_get_js_strings', $plugin_public, 'buddypress_shortcodes_activity_heartbeat_strings', 20, 1 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Shortcodes_For_Buddypress_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
