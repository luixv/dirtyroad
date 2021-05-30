<?php
/**
 * Class BP_Verified_Member_Admin
 *
 * @author  themosaurus
 * @since   1.0.0
 * @package bp-verified-member/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class BP_Verified_Member_Admin
 *
 * @author themosaurus
 * @package bp-verified-member/admin
 */
class BP_Verified_Member_Admin {

	/**
	 * The class managing the plugin meta box.
	 *
	 * @var BP_Verified_Member_Meta_Box
	 */
	public $meta_box;

	/**
	 * The class managing the plugin settings
	 *
	 * @var BP_Verified_Member_Settings
	 */
	public $settings;

	/**
	 * BP_Verified_Member_Admin constructor.
	 */
	public function __construct() {
		$this->meta_box = require 'meta-box/class-bp-verified-member-meta-box.php';
		$this->settings = require 'settings/class-bp-verified-member-settings.php';

		add_action( 'admin_enqueue_scripts',                                  array( $this, 'enqueue_scripts'                      ), 10, 0 );

		/**
		 * Handle "Verified" column
		 */
		add_filter( 'manage_users_columns',                                   array( $this, 'add_verified_column'                  ), 10, 1 );
		add_filter( 'manage_users_custom_column',                             array( $this, 'verified_column_content'              ), 10, 3 );
		add_action( 'wp_ajax_bp_verified_member_toggle',                      array( $this, 'toggle_verified_member'               ), 10, 0 );

		/**
		 * Handle bulk actions
		 */
		add_filter( 'bulk_actions-users',                                     array( $this, 'register_users_bulk_actions'          ), 10, 1 );
		add_filter( 'handle_bulk_actions-users',                              array( $this, 'handle_users_bulk_actions'            ), 10, 3 );
		add_action( 'admin_notices',                                          array( $this, 'users_bulk_action_notice'             ), 10, 0 );

		/**
		 * Handle verification requests
		 */
		add_action( 'admin_notices',                                          array( $this, 'new_requests_notice'                  ), 10, 0 );
		add_action( 'wp_ajax_bp_verified_member_dismiss_new_requests_notice', array( $this, 'dismiss_new_requests_notice'          ), 10, 0 );
		add_filter( 'views_users',                                            array( $this, 'add_verification_requests_users_view' ), 99, 1 );
		add_action( 'users_list_table_query_args',                            array( $this, 'filter_verification_requests_users'   ), 10, 1 );
	}

	/**
	 * Enqueue scripts in admin
	 */
	public function enqueue_scripts() {
		// Make sure these are loaded
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		// Enqueue admin script
		wp_enqueue_script( 'bp-verified-member-admin', BP_VERIFIED_MEMBER_PLUGIN_DIR_URL . 'assets/js/admin.js', array( 'jquery', 'wp-color-picker' ), BP_VERIFIED_MEMBER_VERSION, true );
		wp_localize_script( 'bp-verified-member-admin', 'bpVerifiedMemberAdmin', array(
			'ajaxUrl'               => admin_url( 'admin-ajax.php' ),
			'verifiedTooltip'       => esc_html__( 'Click to unverify', 'bp-verified-member' ),
			'verifiedByRoleTooltip' => esc_html__( 'User belongs to a verified role', 'bp-verified-member' ),
			'unverifiedTooltip'     => esc_html__( 'Click to verify', 'bp-verified-member' ),
		) );

		// Enqueue dependencies except the main frontend script
		global $bp_verified_member;
		$bp_verified_member->enqueue_scripts();
		wp_dequeue_script( 'bp-verified-member' );
	}

	/**
	 * Add new column in users table.
	 *
	 * @param array $columns The users admin columns.
	 *
	 * @return array $columns The users admin columns.
	 */
	public function add_verified_column( $columns ) {
		$columns['bp-verified-member'] = esc_html__( 'Verified', 'bp-verified-member' );
		return $columns;
	}

	/**
	 * Add verified column content.
	 *
	 * @param string $output The column output.
	 * @param array $column_name The current column name.
	 * @param int $user_id The current user id.
	 *
	 * @return string The column output.
	 */
	public function verified_column_content( $output, $column_name, $user_id ) {
		if ( 'bp-verified-member' === $column_name ) {
			/** @var $bp_verified_member BP_Verified_Member */
			global $bp_verified_member;

			if ( $bp_verified_member->is_user_verified( $user_id ) ) {
				$output .= $bp_verified_member->get_verified_badge();

				$is_verified_by_role = $bp_verified_member->is_user_verified_by_role( $user_id );
			}
			else {
				$output .= $bp_verified_member->get_unverified_badge();
			}

			$output = '<a href="#" class="bp-verified-member-toggle' . ( ! empty( $is_verified_by_role ) ? ' bp-verified-by-role' : '' ) .'" data-user-id="' . esc_attr( $user_id ) . '" data-bp-verified-member-toggle-nonce="' . esc_attr( wp_create_nonce( 'bp-verified-member-toggle' ) ) . '">' . $output . '</a>';
		}

		return $output;
	}

	/**
	 * Ajax action to toggle the verified status of a user
	 */
	public function toggle_verified_member() {
		if ( empty( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'bp-verified-member-toggle' ) ) {
			wp_send_json_error( 'invalid_nonce' );
		}

		if ( empty( $_POST['userId'] ) ) {
			wp_send_json_error( 'missing_user_id' );
		}

		$user_id      = intval( $_POST['userId'] );
		$was_verified = boolval( get_user_meta( $user_id, $this->meta_box->meta_keys['verified'], true ) );

		update_user_meta( $user_id, $this->meta_box->meta_keys['verified'], ! $was_verified );

		global $bp_verified_member;
		$output = $was_verified ? $bp_verified_member->get_unverified_badge() : $bp_verified_member->get_verified_badge();

		wp_send_json_success( $output );
	}

	/**
	 * Add a bulk action for users
	 *
	 * @param array $bulk_actions The list of bulk actions
	 *
	 * @return array The list of bulk actions
	 */
	public function register_users_bulk_actions( $bulk_actions ) {
		$bulk_actions['bp-verified-member_verify']   = esc_html__( 'Verify', 'bp-verified-member' );
		$bulk_actions['bp-verified-member_unverify'] = esc_html__( 'Unverify', 'bp-verified-member' );
		return $bulk_actions;
	}

	/**
	 * Handle the "verify" bulk actions for users
	 *
	 * @param string $redirect_to The redirection url after the bulk action is processed
	 * @param string $doaction The bulk action being processed
	 * @param array $user_ids The ids of the users being processed
	 *
	 * @return string The redirection url after the bulk action is processed
	 */
	public function handle_users_bulk_actions( $redirect_to, $doaction, $user_ids ) {
		if ( 'bp-verified-member_verify' === $doaction ) {
			foreach ( $user_ids as $user_id ) {
				update_user_meta( $user_id, $this->meta_box->meta_keys['verified'], true );
			}

			$redirect_to = add_query_arg( 'bp-verified-member_bulk_verified', count( $user_ids ), $redirect_to );
		}
		elseif ( 'bp-verified-member_unverify' === $doaction ) {
			foreach ( $user_ids as $user_id ) {
				update_user_meta( $user_id, $this->meta_box->meta_keys['verified'], false );
			}

			$redirect_to = add_query_arg( 'bp-verified-member_bulk_unverified', count( $user_ids ), $redirect_to );
		}

		return $redirect_to;
	}

	/**
	 * Display success message after processing users bulk action
	 */
	public function users_bulk_action_notice() {
		if ( ! empty( $_GET['bp-verified-member_bulk_verified'] ) ) {
			$verified_count = intval( $_GET['bp-verified-member_bulk_verified'] );

			printf( '<div id="message" class="updated fade"><p>' . _n( 'Verified %s user.', 'Verified %s users.', $verified_count, 'bp-verified-member' ) . '</p></div>', $verified_count );
		}

		if ( ! empty( $_GET['bp-verified-member_bulk_unverified'] ) ) {
			$verified_count = intval( $_GET['bp-verified-member_bulk_unverified'] );

			printf( '<div id="message" class="updated fade"><p>' . _n( 'Unverified %s user.', 'Unverified %s users.', $verified_count, 'bp-verified-member' ) . '</p></div>', $verified_count );
		}
	}

	/**
	 * Display a notice when there are new verification requests
	 */
	public function new_requests_notice() {
		// Bail if verification requests are disabled
		if ( ! $this->settings->get_option( 'enable_verification_requests' ) ) {
			return;
		}

		$new_requests = get_transient( 'bp_verified_member_new_requests' );
		if ( ! empty( $new_requests ) ) :
			// Clear transient and bail if we are on the requests view
			if ( $_GET['view'] === 'bp_verified_member_requests' ) {
				delete_transient( 'bp_verified_member_new_requests' );
				return;
			}

			$new_requests_count = count( $new_requests ); ?>

			<div class="notice notice-info is-dismissible bp-verified-member-new-requests-notice">
				<p>
					<span>
						<?php printf( _n( '%1$s user have recently requested to be verified.', '%1$s users have recently requested to be verified.', $new_requests_count, 'bp-verified-member' ), $new_requests_count ); ?>
					</span>
					<a href="<?php echo esc_url( admin_url( 'users.php?view=bp_verified_member_requests' ) ) ?>" class="button button-primary">
						<?php esc_html_e( 'View requests', 'bp-verified-member' ); ?>
					</a>
				</p>
			</div>

		<?php endif;
	}

	/**
	 * Ajax action to dismiss the new requests notice
	 */
	public function dismiss_new_requests_notice() {
		if ( ! is_admin() || ! is_user_logged_in() ) {
			wp_send_json_error();
		}

		delete_transient( 'bp_verified_member_new_requests' );

		wp_send_json_success();
	}

	/**
	 * Add a verification requests view in the user table to filter users who have requested to be verified
	 *
	 * @param array $views Array of users table views
	 *
	 * @return array Modified array of users table views
	 */
	public function add_verification_requests_users_view( $views ) {
		// Bail if verification requests are disabled
		if ( ! $this->settings->get_option( 'enable_verification_requests' ) ) {
			return $views;
		}

		$class = '';

		if ( isset( $_GET['view'] ) && $_GET['view'] === 'bp_verified_member_requests' ) {
			$views['all'] = str_replace( 'class="current"', '', $views['all'] );
			$class = 'current';
		}

		$query_args = array(
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'   => 'bp_verified_member_verification_request',
					'value' => 'pending',
				),
				array(
					'relation' => 'OR',
					array(
						'key'     => $this->meta_box->meta_keys['verified'],
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => $this->meta_box->meta_keys['verified'],
						'value'   => true,
						'compare' => '!=',
					),
				),
			),
			'count_total' => true,
			'fields'      => 'ID',
		);

		$verified_roles = $this->settings->get_option( 'verified_roles' );
		if ( ! empty( $verified_roles ) ) {
			$query_args['role__not_in'] = $verified_roles;
		}

		$verification_requests = new WP_User_Query( $query_args );

		$url  = add_query_arg( 'view', 'bp_verified_member_requests', admin_url( 'users.php' ) );
		$text = sprintf( esc_html__( 'Verification Requests %s', 'bp-verified-member' ), '<span class="count">(' . number_format_i18n( $verification_requests->get_total() ) . ')</span>' );

		$views['bp_verified_member_requests'] = sprintf( '<a href="%1$s" class="%2$s">%3$s</a>', esc_url( $url ), $class, $text );

		return $views;
	}

	/**
	 * Filter user table for the "Verification Requests" view
	 *
	 * @param array $query_args Array of user query args
	 *
	 * @return array Modified query args
	 */
	public function filter_verification_requests_users( $query_args ) {
		// Bail if not on the requests view, or verification requests are disabled
		if (  empty( $_GET['view'] ) || $_GET['view'] !== 'bp_verified_member_requests' || ! $this->settings->get_option( 'enable_verification_requests' ) ) {
			return $query_args;
		}

		if ( empty( $query_args['meta_query'] ) ) {
			$query_args['meta_query'] = array();
		}

		$query_args['meta_query'][] = array(
			'relation' => 'AND',
			array(
				'key'   => 'bp_verified_member_verification_request',
				'value' => 'pending',
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => $this->meta_box->meta_keys['verified'],
					'compare' => 'NOT EXISTS',
				),
				array(
					'key'     => $this->meta_box->meta_keys['verified'],
					'value'   => true,
					'compare' => '!=',
				),
			),
		);

		$verified_roles = $this->settings->get_option( 'verified_roles' );

		if ( ! empty( $verified_roles ) ) {
			if ( empty( $query_args['role__not_in'] ) ) {
				$query_args['role__not_in'] = array();
			}

			$query_args['role__not_in'] = array_unique( array_merge( $query_args['role__not_in'], $verified_roles ) );
		}

		return $query_args;
	}
}
