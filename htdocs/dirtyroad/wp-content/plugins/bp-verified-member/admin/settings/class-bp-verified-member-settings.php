<?php
/**
 * Class BP_Verified_Member_Settings
 *
 * @author  themosaurus
 * @since   1.0.0
 * @package bp-verified-member/admin/settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'BP_Verified_Member_Settings' ) ) :
	/**
	 * Class BP_Verified_Member_Settings
	 *
	 * @author themosaurus
	 * @package bp-verified-member/admin/settings
	 */
	class BP_Verified_Member_Settings {

		/**
		 * Option page slug.
		 *
		 * @var string
		 */
		private $page_slug = 'bp-verified-member';

		/**
		 * Option group slug.
		 *
		 * @var string
		 */
		private $option_group = 'bp_verified_member';

		/**
		 * Options default values.
		 *
		 * @var array
		 */
		private $defaults = array();

		/**
		 * BP_Verified_Member_Settings constructor.
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'add_option_page' ) );
			add_action( 'admin_init', array( $this, 'page_init'       ) );

			if ( ! bp_core_do_network_admin() ) {
				add_filter( 'bp_core_get_admin_tabs', array( $this, 'add_tab_in_buddypress_settings' ), 10, 1 );
				add_action( 'bp_admin_head',          array( $this, 'remove_settings_submenu_link'   ), 999   );
			}

			$this->defaults = array(
				"{$this->option_group}_verified_roles"                    => array(),
				"{$this->option_group}_enable_verification_requests"      => false,
				"{$this->option_group}_badge_color"                       => '#1DA1F2',
				"{$this->option_group}_tooltip_content"                   => esc_html__( 'Verified', 'bp-verified-member' ),
				"{$this->option_group}_display_unverified_badge"          => 0,
				"{$this->option_group}_unverified_badge_color"            => '#DD9933',
				"{$this->option_group}_unverified_tooltip_content"        => esc_html__( 'Unverified', 'bp-verified-member' ),
				"{$this->option_group}_display_badge_in_activity_stream"  => 1,
				"{$this->option_group}_display_badge_in_profile_username" => 1,
				"{$this->option_group}_display_badge_in_profile_fullname" => 0,
				"{$this->option_group}_display_badge_in_members_lists"    => 1,
				"{$this->option_group}_display_badge_in_bp_widgets"       => 0,
				"{$this->option_group}_display_badge_in_messages"         => 0,
				"{$this->option_group}_display_badge_in_bbp_topics"       => 1,
				"{$this->option_group}_display_badge_in_bbp_replies"      => 1,
				"{$this->option_group}_display_badge_in_wp_comments"      => 1,
				"{$this->option_group}_display_badge_in_wp_posts"         => 1,
			);
		}

		/**
		 * Add options page.
		 */
		public function add_option_page() {
			$hook = add_options_page(
				esc_html__( 'Verified Member Settings', 'bp-verified-member' ),
				esc_html__( 'Verified Member for BuddyPress', 'bp-verified-member' ),
				'manage_options',
				$this->page_slug,
				array( $this, 'render_settings_page' )
			);

			if ( ! bp_core_do_network_admin() ) {
				add_action( "admin_head-$hook", 'bp_core_modify_admin_menu_highlight' );
			}
		}

		/**
		 * Options page callback.
		 */
		public function render_settings_page() {
			?>

			<div class="wrap">
				<h1><?php esc_html_e( 'Verified Member Settings', 'bp-verified-member' ); ?></h1>

				<?php if ( ! bp_core_do_network_admin() ) : ?>
					<h2 class="nav-tab-wrapper"><?php bp_core_admin_tabs( __( 'Verified Member', 'bp-verified-member' ) ); ?></h2>
				<?php endif; ?>

				<form method="post" action="options.php">
					<?php
					// This prints out all hidden setting fields
					settings_fields( $this->option_group );
					do_settings_sections( $this->page_slug );
					submit_button();
					?>
				</form>
			</div>

			<?php
		}

		/**
		 * Get an option with default value.
		 *
		 * @param string $option_name The requested option name.
		 *
		 * @return mixed The requested option.
		 */
		public function get_option( $option_name ) {
			// Prefix $option_name if not already prefixed
			if ( substr( $option_name, 0, strlen( $this->option_group ) ) !== $this->option_group ) {
				$option_name = $this->option_group . '_' . $option_name;
			}

			return get_option( $option_name, $this->defaults[ $option_name ] );
		}

		/**
		 * Register and add settings and settings fields.
		 */
		public function page_init() {
			$settings = array(
				"{$this->option_group}_verification_section"     => array(
					'title'  => esc_html__( 'Verification Settings', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_verified_roles"               => array(
							'label'       => esc_html__( 'Verified Roles', 'bp-verified-member' ),
							'description' => esc_html__( 'Automatically grant verified badge to the following roles:', 'bp-verified-member' ),
							'type'        => 'multi_checkbox',
							'options'     => $this->get_role_options(),
						),
						"{$this->option_group}_enable_verification_requests" => array(
							'label'       => esc_html__( 'Enable Verification Requests', 'bp-verified-member' ),
							'description' => esc_html__( 'Display a button on user profiles to allow users to send a verification request. The requests will then be displayed for admins in the "Verification Requests" view in the users table.', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
					),
				),
				"{$this->option_group}_verified_badge_section"     => array(
					'title'  => esc_html__( 'Verified badge', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_badge_color" => array(
							'label' => esc_html__( 'Verified Badge Color', 'bp-verified-member' ),
							'type'  => 'color',
						),
						"{$this->option_group}_tooltip_content" => array(
							'label'       => esc_html__( 'Verified Badge Tooltip Content', 'bp-verified-member' ),
							'description' => esc_html__( 'Content of the tooltip displayed when hovering on a verified badge. Empty for no tooltip.', 'bp-verified-member' ),
							'type'        => 'text',
						),
					),
				),
				"{$this->option_group}_unverified_badge_section"     => array(
					'title'  => esc_html__( 'Unverified Badge', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_display_unverified_badge" => array(
							'label'       => esc_html__( 'Display Unverified Badge', 'bp-verified-member' ),
							'description' => esc_html__( 'Display an unverified badge for users who are not verified.', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
						"{$this->option_group}_unverified_badge_color" => array(
							'label' => esc_html__( 'Unverified Badge Color', 'bp-verified-member' ),
							'type'  => 'color',
						),
						"{$this->option_group}_unverified_tooltip_content" => array(
							'label'       => esc_html__( 'Unverified Badge Tooltip Content', 'bp-verified-member' ),
							'description' => esc_html__( 'Content of the tooltip displayed when hovering on a unverified badge. Empty for no tooltip.', 'bp-verified-member' ),
							'type'        => 'text',
						),
					),
				),
				"{$this->option_group}_activity_section"  => array(
					'title'  => esc_html__( 'Activities Settings', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_display_badge_in_activity_stream" => array(
							'label'       => esc_html__( 'Display in Activities', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in Activity Stream', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
					),
				),
				"{$this->option_group}_profile_section"   => array(
					'title'  => esc_html__( 'Profiles Settings', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_display_badge_in_profile_username" => array(
							'label'       => esc_html__( 'Display in Username', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in Profile Username', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
						"{$this->option_group}_display_badge_in_profile_fullname" => array(
							'label'       => esc_html__( 'Display in Fullname', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in Profile Fullname', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
					),
				),
				"{$this->option_group}_directory_section" => array(
					'title'  => esc_html__( 'Directories Settings', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_display_badge_in_members_lists" => array(
							'label'       => esc_html__( 'Display in Directories', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in Members Lists', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
					),
				),
				"{$this->option_group}_bp_widget_section" => array(
					'title'  => esc_html__( 'Widgets Settings', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_display_badge_in_bp_widgets" => array(
							'label'       => esc_html__( 'Display in BP Widgets', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in BuddyPress Widgets', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
					),
				),
				"{$this->option_group}_message_section" => array(
					'title'  => esc_html__( 'Messages Settings', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_display_badge_in_messages" => array(
							'label'       => esc_html__( 'Display in Messages', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in Private Messages', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
					),
				),
				"{$this->option_group}_bbp_section"       => array(
					'title'  => esc_html__( 'bbPress Settings', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_display_badge_in_bbp_topics" => array(
							'label'       => esc_html__( 'Display in Topics', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in BBPress Topics', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
						"{$this->option_group}_display_badge_in_bbp_replies" => array(
							'label'       => esc_html__( 'Display in Replies', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in BBPress Replies', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
					),
				),
				"{$this->option_group}_wp_section"        => array(
					'title'  => esc_html__( 'WordPress Settings', 'bp-verified-member' ),
					'fields' => array(
						"{$this->option_group}_display_badge_in_wp_comments" => array(
							'label'       => esc_html__( 'Display in Comments', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in WordPress Comments', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
						"{$this->option_group}_display_badge_in_wp_posts" => array(
							'label'       => esc_html__( 'Display in Posts', 'bp-verified-member' ),
							'description' => esc_html__( 'Display Verified Badge in WordPress Posts', 'bp-verified-member' ),
							'type'        => 'checkbox',
						),
					),
				),
			);

			foreach ( $settings as $section_id => $section ) {

				// Don't show bbPress settings if bbPress isn't activated
				if ( "{$this->option_group}_bbp_section" === $section_id && ! function_exists( 'bbpress' ) ) {
					continue;
				}

				// Don't show messages settings if BP Better Messages is activated
				if ( "{$this->option_group}_message_section" === $section_id && class_exists( 'BP_Better_Messages' ) ) {
					continue;
				}

				add_settings_section(
					$section_id, // ID
					$section['title'], // Title
					'__return_false', // Callback
					$this->page_slug // Page
				);

				foreach ( $section['fields'] as $field_id => $field ) {
					register_setting(
						$this->option_group, // Option group
						$field_id, // Option name
						array( $this, "sanitize_{$field['type']}" ) // Sanitize
					);

					add_settings_field(
						$field_id, // ID
						$field['label'], // Title
						array( $this, "render_{$field['type']}_field" ), // Callback
						$this->page_slug, // Page
						$section_id, // Section
						array(
							'id'          => $field_id,
							'description' => ! empty( $field['description'] ) ? $field['description'] : '',
							'options'     => ! empty( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : array(),
						) // Callback args
					);
				}
			}
		}

		/**
		 * Sanitize checkbox field.
		 *
		 * @param mixed $input Contains the setting value.
		 *
		 * @return int The sanitized checkbox value
		 */
		public function sanitize_checkbox( $input ) {
			return ! empty( $input ) ? 1 : 0;
		}

		/**
		 * Sanitize multi checkbox field.
		 *
		 * @param mixed $input Contains the setting value.
		 *
		 * @return array The sanitized checkbox value
		 */
		public function sanitize_multi_checkbox( $input ) {
			if ( ! is_array( $input ) ) {
				return array();
			}

			return array_map( 'sanitize_text_field', $input );
		}

		/**
		 * Sanitize color field.
		 *
		 * @param mixed $input Contains the setting value.
		 *
		 * @return string The sanitized color
		 */
		public function sanitize_color( $input ) {
			return sanitize_hex_color( $input );
		}

		/**
		 * Sanitize text field.
		 *
		 * @param mixed $input Contains the setting value.
		 *
		 * @return string The sanitized text
		 */
		public function sanitize_text( $input ) {
			return sanitize_text_field( $input );
		}

		/**
		 * Render a checkbox field.
		 *
		 * @param array $args Field args.
		 */
		public function render_checkbox_field( $args ) {
			if ( empty( $args['id'] ) ) {
				return;
			}

			$checked = ! empty( $this->get_option( $args['id'] ) ) ? 'checked' : '';
			?>
			<input type="checkbox" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" <?php echo esc_attr( $checked ); ?> />
			<?php if ( ! empty( $args['description'] ) ) : ?>
				<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['description'] ); ?></label>
			<?php endif; ?>
			<?php
		}

		/**
		 * Render a multi checkbox field.
		 *
		 * @param array $args Field args.
		 */
		public function render_multi_checkbox_field( $args ) {
			if ( empty( $args['id'] ) || empty( $args['options'] ) ) {
				return;
			}

			if ( ! empty( $args['description'] ) ) : ?>
				<p><?php echo wp_kses_post( $args['description'] ); ?></p>
			<?php endif;

			foreach ( $args['options'] as $value => $label ) : ?>
				<br>
				<label>
					<input type="checkbox" <?php checked( in_array( esc_attr( $value ), $this->get_option( $args['id'] ) ), true ); ?> name="<?php echo esc_attr( $args['id'] ); ?>[]" value="<?php echo esc_attr( $value ); ?>">
					<?php echo esc_html( $label ); ?>
				</label>
			<?php endforeach;
		}

		/**
		 * Render a color field.
		 *
		 * @param array $args Field args.
		 */
		public function render_color_field( $args ) {
			if ( empty( $args['id'] ) ) {
				return;
			}

			?>
			<input type="text" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $this->get_option( $args['id'] ) ); ?>" class="color-picker" />
			<?php if ( ! empty( $args['description'] ) ) : ?>
				<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['description'] ); ?></label>
			<?php endif; ?>
			<?php
		}

		/**
		 * Render a text field.
		 *
		 * @param array $args Field args.
		 */
		public function render_text_field( $args ) {
			if ( empty( $args['id'] ) ) {
				return;
			}

			?>
			<input type="text" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $this->get_option( $args['id'] ) ); ?>" />
			<?php if ( ! empty( $args['description'] ) ) : ?>
				<label for="<?php echo esc_attr( $args['id'] ); ?>"><?php echo wp_kses_post( $args['description'] ); ?></label>
			<?php endif; ?>
			<?php
		}

		/**
		 * Add new tab to BuddyPress settings.
		 *
		 * @param array $tabs The array of tabs.
		 *
		 * @return array The modified array of tabs.
		 */
		public function add_tab_in_buddypress_settings( $tabs ) {
			$tabs['bp_verified_member'] = array(
				'href' => bp_get_admin_url( add_query_arg( array( 'page' => 'bp-verified-member' ), 'options-general.php' ) ),
				'name' => __( 'Verified Member', 'bp-verified-member' ),
			);

			return $tabs;
		}

		/**
		 * Remove submenu link from the Settings menu.
		 */
		public function remove_settings_submenu_link() {
			remove_submenu_page( 'options-general.php', $this->page_slug );
		}

		/**
		 * Get an array of role options where each key is the role slug and each value is the role name
		 *
		 * @return array Array of role options
		 */
		private function get_role_options() {
			$roles        = get_editable_roles();
			$role_options = array();
			foreach ( $roles as $role_slug => $role ) {
				$role_options[ $role_slug ] = $role['name'];
			}

			return $role_options;
		}
	}

endif;

return new BP_Verified_Member_Settings();
