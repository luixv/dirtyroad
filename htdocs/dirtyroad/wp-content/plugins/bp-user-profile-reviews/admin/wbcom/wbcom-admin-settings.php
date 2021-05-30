<?php
/**
 * Class to add top header pages of wbcom plugin and additional features.
 *
 * @author   Wbcom Designs
 * @package  BuddyPress_Member_Reviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wbcom_Admin_Settings' ) ) {

	/**
	 * Class to add wbcom plugin's admin settings.
	 *
	 * @author   Wbcom Designs
	 * @since    2.0.0
	 */
	class Wbcom_Admin_Settings {

		/**
		 * Wbcom_Admin_Settings Constructor.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function __construct() {
			add_shortcode( 'wbcom_admin_setting_header', array( $this, 'wbcom_admin_setting_header_html' ) );
			add_action( 'admin_menu', array( $this, 'wbcom_admin_additional_pages' ), 999 );
			add_action( 'admin_enqueue_scripts', array( $this, 'wbcom_enqueue_admin_scripts' ) );
			add_action( 'wp_ajax_wbcom_manage_plugin_installation', array( $this, 'wbcom_do_plugin_action' ) );
		}

		/**
		 * Ajax call to serve action related to plugin's install/activate/deactive.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_do_plugin_action() {
			$action = ! empty( $_POST['plugin_action'] ) ? $_POST['plugin_action'] : false;
			$slug   = ! empty( $_POST['plugin_slug'] ) ? $_POST['plugin_slug'] : false;

			if ( 'install_plugin' == $action ) {
				$this->wbcom_do_plugin_install( $slug );
			} elseif ( 'activate_plugin' == $action ) {
				$this->wbcom_do_plugin_activate( $slug );
			} else {
				$this->wbcom_do_plugin_deactivate( $slug );
			}
			die;
		}

		/**
		 * Function for activate plugin.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function wbcom_do_plugin_activate( $slug ) {
			$plugin_file_path = $this->_get_plugin_file_path_from_slug( $slug );
			activate_plugin( $plugin_file_path );
		}

		/**
		 * Function for deactivate plugin.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function wbcom_do_plugin_deactivate( $slug ) {
			$plugin_file_path = $this->_get_plugin_file_path_from_slug( $slug );
			deactivate_plugins( $plugin_file_path );
		}

		/**
		 * Function for get plugin file name.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function _get_plugin_file_path_from_slug( $slug ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugins_list = get_plugins();
			$keys         = array_keys( $plugins_list );
			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return $key;
				}
			}
			return $slug;
		}

		/**
		 * Function for install plugin.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function wbcom_do_plugin_install( $slug ) {
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			wp_cache_flush();

			$upgrader   = new Plugin_Upgrader();
			$plugin_zip = $this->get_download_url( $slug );
			$installed  = $upgrader->install( $plugin_zip );
			if ( $installed ) {
				$response = array( 'status' => 'installed' );
				echo wp_send_json_success( $response ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				return false;
			}
			exit;
		}

		/**
		 * Function for upgrade plugin.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $plugin_slug Plugin's slug.
		 */
		public function upgrade_plugin( $plugin_slug ) {
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			wp_cache_flush();

			$upgrader = new Plugin_Upgrader();
			$upgraded = $upgrader->upgrade( $plugin_slug );

			return $upgraded;
		}

		/**
		 * Function for return plugin's WordPress repo download url.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function get_download_url( $slug ) {
			return $this->get_wp_repo_download_url( $slug );
		}

		/**
		 * Function for get plugin's WordPress repo download url.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function get_wp_repo_download_url( $slug ) {
			$status = array();
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php'; // for plugins_api..
			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => $slug,
					'fields' => array( 'sections' => false ),
				)
			); // Save on a bit of bandwidth.

			if ( is_wp_error( $api ) ) {
				$status['error'] = $api->get_error_message();
				wp_send_json_error( $status );
			}

			return $api->download_link;
		}

		/**
		 * Function for get all wbcom free plugin's details.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_all_free_plugins() {
			$free_plugins = array(
				'0' => array(
					'name'        => esc_html__( 'Custom Font Uploader', 'bp-member-reviews' ),
					'slug'        => 'custom-font-uploader',
					'description' => esc_html__( 'It also allows you to upload your own custom font to your site and use them using custom css.', 'bp-member-reviews' ),
					'status'      => $this->wbcom_plugin_status( 'custom-font-uploader' ),
					'wp_url'      => 'https://wordpress.org/plugins/custom-font-uploader/',
					'icon'        => 'fa fa-upload',
				),
				'1' => array(
					'name'        => esc_html__( 'BuddyPress Create Group Type', 'bp-member-reviews' ),
					'slug'        => 'bp-create-group-type',
					'description' => esc_html__( 'It will help to create group type for BuddyPress Groups.', 'bp-member-reviews' ),
					'status'      => $this->wbcom_plugin_status( 'bp-create-group-type' ),
					'wp_url'      => 'https://wordpress.org/plugins/bp-create-group-type/',
					'icon'        => 'fa fa-sitemap',
				),
				'2' => array(
					'name'        => esc_html__( 'BuddyPress Member Reviews', 'bp-member-reviews' ),
					'slug'        => 'bp-user-profile-reviews',
					'description' => esc_html__( 'This plugin allows only site members to add reviews to the buddypress members on the site and even rate the member’s profile out of 5 points with multiple review criteria.', 'bp-member-reviews' ),
					'status'      => $this->wbcom_plugin_status( 'bp-user-profile-reviews' ),
					'wp_url'      => 'https://wordpress.org/plugins/bp-user-profile-reviews/',
					'icon'        => 'fa fa-user',
				),
				'3' => array(
					'name'        => esc_html__( 'BuddyPress Group Reviews', 'bp-member-reviews' ),
					'slug'        => 'review-buddypress-groups',
					'description' => esc_html__( 'This plugin allows the BuddyPress Members to give reviews to the BuddyPress groups on the site. The review form allows the users to give text review, even rate the group on the basis of multiple criterias.', 'bp-member-reviews' ),
					'status'      => $this->wbcom_plugin_status( 'review-buddypress-groups' ),
					'wp_url'      => 'https://wordpress.org/plugins/review-buddypress-groups/',
					'icon'        => 'fa fa-2x fa-users',
				),
			);
			return $free_plugins;
		}

		/**
		 * Function for get all wbcom paid plugin's details.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_all_paid_plugins() {
			$paid_plugins = array(
				'0' => array(
					'name'         => esc_html__( 'BuddyPress Moderation Pro', 'bp-member-reviews' ),
					'description'  => esc_html__( 'BuddyPress Community Moderation offers a solution for site owners to keep their communities straight. With community policing strategy, members of the community have an option for moderation sitewide by attaching flags to content created within the various components.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-moderation-pro/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-exclamation-triangle',
				),
				'1' => array(
					'name'         => esc_html__( 'BuddyPress Polls', 'bp-member-reviews' ),
					'description'  => esc_html__( 'Use BuddyPress Polls plugin to create polls inside the activity, let your user response to your polls. Members can create pools like activities, easily votes on them.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-polls/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-bar-chart',
				),
				'2' => array(
					'name'         => esc_html__( 'BuddyPress Hashtags', 'bp-member-reviews' ),
					'description'  => esc_html__( 'Allows members to use hashtags in BuddyPress activities and bbPress topics and ready with widgets.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-hashtags/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-hashtag',
				),
				'3' => array(
					'name'         => esc_html__( 'BuddyPress Profanity', 'bp-member-reviews' ),
					'description'  => esc_html__( 'Use BuddyPress Profanity plugin to censor content in your community! Easily Censor all the unwanted words in activities, private messages contents by specifying a list of keywords to be filtered.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-profanity/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-ban',
				),
				'4' => array(
					'name'         => esc_html__( 'BuddyPress Private Community Pro', 'bp-member-reviews' ),
					'description'  => esc_html__( 'This plugin offers a lockdown for BuddyPress Component and will ask users to log in go further to check profile or any other protected details.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-private-community-pro/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-user-times',
				),
				'5' => array(
					'name'         => esc_html__( 'BuddyPress Profile Pro', 'bp-member-reviews' ),
					'description'  => esc_html__( 'This plugin gives you the power to extend BuddyPress Profiles with repeater fields and groups. You can easily add multiple field groups and display them at member’s profile.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-profile-pro/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-user-circle-o',
				),
				'6' => array(
					'name'         => esc_html__( 'BuddyPress Sticky Post', 'bp-member-reviews' ),
					'description'  => esc_html__( 'This will make it easier for the site administrator to make the pinned activity item recognized first by the community whenever they visit the site.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-sticky-post/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-thumb-tack',
				),
				'7' => array(
					'name'         => esc_html__( 'BuddyPress Auto Friends', 'bp-member-reviews' ),
					'description'  => esc_html__( 'Allow the site admin to select global friends for all his members with BuddyPress Auto friends plugin.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-auto-friends/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-user-plus',
				),
				'8' => array(
					'name'         => esc_html__( 'BuddyPress Quotes', 'bp-member-reviews' ),
					'description'  => esc_html__( 'BuddyPress quotes plugin comes with the feature to let users post their activity updates with interactive backgrounds selection such as colors and images set by the site administrator, so they can tell a more expressive story.', 'bp-member-reviews' ),
					'download_url' => 'https://wbcomdesigns.com/downloads/buddypress-quotes/?utm_source=wporg&utm_medium=plugins&utm_campaign=bp',
					'icon'         => 'fa fa-quote-left',
				),
			);
			return $paid_plugins;
		}

		/**
		 * Function for check plugin is installed or not.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function wbcom_is_plugin_installed( $slug ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$all_plugins = get_plugins();
			$keys        = array_keys( $all_plugins );
			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Function for check plugin's status.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function wbcom_plugin_status( $slug ) {
			if ( $this->wbcom_is_plugin_installed( $slug ) ) {
				if ( $this->wbcom_is_plugin_active( $slug ) ) {
					return 'activated';
				} else {
					return 'installed';
				}
			} else {
				return 'not_installed';
			}
		}

		/**
		 * Function for check plugin is activated or not.
		 *
		 * @since 2.0.0
		 * @access public
		 * @param string $slug Plugin's slug.
		 */
		public function wbcom_is_plugin_active( $slug ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$all_plugins = get_plugins();
			$keys        = array_keys( $all_plugins );
			$response    = false;
			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					if ( is_plugin_active( $key ) ) {
						$response = true;
					}
				}
			}
			return $response;
		}

		/**
		 * Enqueue js & css related to wbcom plugin.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_enqueue_admin_scripts() {
			if ( ! wp_style_is( 'font-awesome', 'enqueued' ) ) {
				wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
			}
			if ( ! wp_script_is( 'wbcom_admin_setting_js', 'enqueued' ) ) {

				wp_register_script(
					$handle    = 'wbcom_admin_setting_js',
					$src       = BUPR_PLUGIN_URL . 'admin/wbcom/assets/js/wbcom-admin-setting.js',
					$deps      = array( 'jquery' ),
					$ver       = time(),
					$in_footer = true
				);
				wp_localize_script(
					'wbcom_admin_setting_js',
					'wbcom_plugin_installer_params',
					array(
						'ajax_url'        => admin_url( 'admin-ajax.php' ),
						'activate_text'   => esc_html__( 'Activate', 'bp-member-reviews' ),
						'deactivate_text' => esc_html__( 'Deactivate', 'bp-member-reviews' ),
					)
				);
				wp_enqueue_script( 'wbcom_admin_setting_js' );

			}

			if ( ! wp_style_is( 'wbcom-admin-setting-css', 'enqueued' ) ) {
				wp_enqueue_style( 'wbcom-admin-setting-css', BUPR_PLUGIN_URL . 'admin/wbcom/assets/css/wbcom-admin-setting.css' );
			}

		}

		/**
		 * Function for add plugin's admin panel header pages.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_admin_additional_pages() {
			add_submenu_page(
				'wbcomplugins',
				esc_html__( 'Our Plugins', 'bp-member-reviews' ),
				esc_html__( 'Our Plugins', 'bp-member-reviews' ),
				'manage_options',
				'wbcom-plugins-page',
				array( $this, 'wbcom_plugins_submenu_page_callback' )
			);
			add_submenu_page(
				'wbcomplugins',
				esc_html__( 'Our Themes', 'bp-member-reviews' ),
				esc_html__( 'Our Themes', 'bp-member-reviews' ),
				'manage_options',
				'wbcom-themes-page',
				array( $this, 'wbcom_themes_submenu_page_callback' )
			);
			add_submenu_page(
				'wbcomplugins',
				esc_html__( 'Support', 'bp-member-reviews' ),
				esc_html__( 'Support', 'bp-member-reviews' ),
				'manage_options',
				'wbcom-support-page',
				array( $this, 'wbcom_support_submenu_page_callback' )
			);
		}

		/**
		 * Function for include wbcom plugins list page.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_plugins_submenu_page_callback() {
			include 'templates/wbcom-plugins-page.php';
		}

		/**
		 * Function for include themes list page.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_themes_submenu_page_callback() {
			include 'templates/wbcom-themes-page.php';
		}

		/**
		 * Function for include support page.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_support_submenu_page_callback() {
			include 'templates/wbcom-support-page.php';
		}

		/**
		 * Shortcode for display top menu header.
		 *
		 * @since 2.0.0
		 * @access public
		 */
		public function wbcom_admin_setting_header_html() {
			$page          = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : 'wbcom-themes-page';
			$plugin_active = $theme_active = $support_active = $settings_active = '';
			switch ( $page ) {
				case 'wbcom-plugins-page':
					$plugin_active = 'is_active';
					break;
				case 'wbcom-themes-page':
					$theme_active = 'is_active';
					break;
				case 'wbcom-support-page':
					$support_active = 'is_active';
					break;
				case 'wbcom-license-page':
					$license_active = 'is_active';
					break;
				default:
					$settings_active = 'is_active';
			}
			?>
			<div id="wb_admin_header" class="wp-clearfix">

				<div id="wb_admin_logo">
					<img src="<?php echo esc_url( BUPR_PLUGIN_URL . 'admin/wbcom/assets/imgs/logowbcom.png' ); ?>">
					<div class="wb_admin_right"></div>
				</div>

				<nav id="wb_admin_nav">
					<ul>
						<li class="wb_admin_nav_item <?php echo esc_attr( $settings_active ); ?>">
							<a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=wbcomplugins' ); ?>" id="wb_admin_nav_trigger_settings">
								<i class="fa fa-sliders"></i>
								<h4><?php esc_html_e( 'Settings', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $plugin_active ); ?>">
							<a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=wbcom-plugins-page' ); ?>" id="wb_admin_nav_trigger_extensions">
								<i class="fa fa-th"></i>
								<h4><?php esc_html_e( 'Our Plugins', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $theme_active ); ?>">
							<a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=wbcom-themes-page' ); ?>" id="wb_admin_nav_trigger_themes">
								<i class="fa fa-magic"></i>
								<h4><?php esc_html_e( 'Our Themes', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $support_active ); ?>">
							<a href="<?php echo esc_url( get_admin_url() . 'admin.php?page=wbcom-support-page' ); ?>" id="wb_admin_nav_trigger_support">
								<i class="fa fa-question-circle"></i>
								<h4><?php esc_html_e( 'Support', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
						<?php do_action( 'wbcom_add_header_menu' ); ?>
					</ul>
				</nav>
			</div>
			<?php
		}

	}

	function instantiate_wbcom_plugin_manager() {
		new Wbcom_Admin_Settings();
	}

	instantiate_wbcom_plugin_manager();
}
