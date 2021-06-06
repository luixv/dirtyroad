<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/plugins
 * @since      1.0.0
 *
 * @package    Shortcodes_For_Buddypress
 * @subpackage Shortcodes_For_Buddypress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Shortcodes_For_Buddypress
 * @subpackage Shortcodes_For_Buddypress/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Shortcodes_For_Buddypress_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/shortcodes-for-buddypress-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/shortcodes-for-buddypress-public.js', array( 'jquery' ), $this->version, false );

	}

	public function buddypress_shortcodes_bp_is_current_component( $current_component ) {
		global $post;

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'activity-listing' ) ) ) {
			$current_component = 'activity';
		}

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'members-listing' ) || has_shortcode( $post->post_content, 'notification' ) ) ) {
			$current_component = 'members';
		}

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'groups-listing' ) ) ) {
			$current_component = 'groups';
		}

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'notifications-listing' ) ) ) {
			$current_component = 'notifications';
		}

		return $current_component;
	}

	public function buddypress_shortcodes_bp_current_component( $current_component ) {
		global $post;

		if ( $current_component == '' ) {
			// $current_component = 'activity';
		}

		if ( empty( $post ) && ! isset( $_REQUEST['object'] ) ) {
			return $current_component;
		}

		if ( isset( $_REQUEST['object'] ) && $_REQUEST['object'] != '' ) {
			$current_component = $_REQUEST['object'];
		}
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'post_update' ) {
			$current_component = 'activity';
		}

		if ( isset( $post->ID ) && $post->ID != '' && $post->ID != '0' ) {
			$_elementor_controls_usage = get_post_meta( $post->ID, '_elementor_controls_usage', true );
			if ( ! empty( $_elementor_controls_usage ) ) {
				foreach ( $_elementor_controls_usage as $key => $value ) {
					if ( $key == 'buddypress_shortcode_activity_widget' ) {
						$current_component = 'activity';
						break;
					}

					if ( $key == 'buddypress_shortcode_members_widget' ) {
						$current_component = 'members';
						break;
					}

					if ( $key == 'buddypress_shortcode_groups_widget' ) {
						$current_component = 'groups';
						break;
					}
				}
			}
		}

		/* Set Current component activity when submit post from activity listing shortcode */
		if ( isset( $_REQUEST['data']['bp_activity_last_recorded'] ) && $_REQUEST['data']['bp_activity_last_recorded'] != '' ) {
			$current_component = 'activity';
		}

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'notifications-listing' ) ) && isset( $post->ID ) && $post->ID != '' && $post->ID != '0' ) {
			$current_component = 'notifications';

			/* redirect to home page when user not loggedin on notifications listing page */
			if ( ! is_user_logged_in() ) {
				wp_redirect( site_url( '/' ) );
				exit();
			}
		}

		return $current_component;
	}

	public function buddypress_shortcodes_body_classes( $classes ) {
		global $post;

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'activity-listing' ) || has_shortcode( $post->post_content, 'members-listing' ) || has_shortcode( $post->post_content, 'groups-listing' ) || has_shortcode( $post->post_content, 'notifications-listing' ) ) ) {
			$classes[] = 'bpsh-buddypress';
			$classes[] = 'buddypress';
		}

		return $classes;
	}

	public function buddypress_shortcodes_bp_nouveau_get_search_primary_object( $object ) {
		global $post;

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'activity-listing' ) ) ) {
			$object = 'dir';
		}

		return $object;
	}

	/**
	 * Include EDD sell services template file.
	 *
	 * @since    1.0.0
	 */
	public function buddypress_shortcodes_template( $file_name ) {

		$template_name = 'shortcodes-for-buddypress/' . $file_name;

		if ( file_exists( STYLESHEETPATH . '/' . $template_name ) ) {

			include STYLESHEETPATH . '/' . $template_name;

		} elseif ( file_exists( TEMPLATEPATH . '/' . $template_name ) ) {

			include TEMPLATEPATH . '/' . $template_name;

		} else {

			include SHORTCODES_FOR_BUDDYPRESS_PLUGIN_DIR . 'templates/' . $file_name;
		}
	}


	/**
	 * Call BuddyPress Activity Listing shortcode
	 *
	 * @since    1.0.0
	 */
	public function buddypress_shortcodes_activity_listing( $atts, $content = null ) {
		global $activity_atts;
		$bpsh_query = build_query( $atts );
		if ( ! empty( $bpsh_query ) ) {
			$bpsh_query = '&' . $bpsh_query;
		}
		$default_atts = array(
			'title'            => '', // title of the section
			'display_comments' => 'threaded',
			'include'          => false, // pass an activity_id or string of IDs comma-separated
			'exclude'          => false, // pass an activity_id or string of IDs comma-separated
			'in'               => false, // comma-separated list or array of activity IDs among which to search
			'sort'             => 'DESC', // sort DESC or ASC
			'page'             => false, // which page to load
			'per_page'         => 10, // how many per page
			'max'              => false, // max number to return
			'count_total'      => true,
			'scope'            => false,
			// Filtering
			'user_id'          => false, // user_id to filter on
			'object'           => false, // object to filter on e.g. groups, profile, status, friends
			'action'           => false, // action to filter on e.g. activity_update, new_forum_post, profile_updated
			'primary_id'       => false, // object ID to filter on e.g. a group_id or forum_id or blog_id etc.
			'secondary_id'     => false, // secondary object ID to filter on e.g. a post_id
						// Searching
			'search_terms'     => false, // specify terms to search on
			'use_compat'       => ( function_exists( 'bp_use_theme_compat_with_current_theme' ) ) ? bp_use_theme_compat_with_current_theme() : '',
			'allow_posting'    => false, // experimental, some of the themes may not support it.
			'container_class'  => 'activity', // default container,
			'hide_on_activity' => 1, // hide on user and group activity pages
			'bpsh_query'       => $bpsh_query,
		);

		$activity_atts = shortcode_atts( $default_atts, $atts );
		extract( $activity_atts );

		if ( isset( $atts['hide_on_activity'] ) && $atts['hide_on_activity'] && ( function_exists( 'bp_is_activity_component' ) && bp_is_activity_component() ||
				function_exists( 'bp_is_group_home' ) && bp_is_group_home() ) ) {
			return '';
		}

		$_bp_theme_package = get_option( '_bp_theme_package_id' );

		ob_start();

		if ( function_exists( 'bp_is_active' ) ) {

			if ( $title != '' ) { ?>
		  <h3 class="section-title"><span><?php echo esc_html( $title ); ?></span></h3>
				<?php
			}
			if ( $_bp_theme_package == 'legacy' ) {
				$this->buddypress_shortcodes_template( 'legacy/activity/bp-shortcodes-activity.php' );
			} else {
				wp_enqueue_script( 'bp-nouveau-activity' );
				$this->buddypress_shortcodes_template( 'nouveau/activity/bp-shortcodes-activity.php' );
			}
		}

		return ob_get_clean();
	}

	/**
	 * Call BuddyPress Members Listing shortcode
	 *
	 * @since    1.0.0
	 */
	public function buddypress_shortcodes_members_listing( $atts, $content = null ) {

		global $members_atts;
		if ( ! empty( $atts ) && array_key_exists( 'exclude_member_role', $atts ) ) {

			if ( strpos( $atts['exclude_member_role'], ',' ) !== false ) {
				$exclude_roles = explode( ',', $atts['exclude_member_role'] );
			} else {
				$exclude_roles = $atts['exclude_member_role'];
			}
			if ( isset( $atts['exclude'] ) && strpos( $atts['exclude'], ',' ) !== false ) {
				$exclude_ids = explode( ',', $atts['exclude'] );
			} else {
				$exclude_ids = array();
			}
			$users_data     = get_users( array( 'role__in' => $exclude_roles ) );
			$uid_collection = array();
			foreach ( $users_data as $key => $users ) {
				$uid_collection[] = $users->ID;
			}
			$u               = array_unique( array_merge( $uid_collection, $exclude_ids ) );
			$atts['exclude'] = rtrim( implode( ',', $u ), ',' );
			unset( $atts['exclude_member_role'] );
		}
		if ( ! empty( $atts ) && array_key_exists( 'include_member_role', $atts ) ) {
			if ( strpos( $atts['include_member_role'], ',' ) !== false ) {
				$exclude_roles = explode( ',', $atts['include_member_role'] );
			} else {
				$exclude_roles = $atts['include_member_role'];
			}
			if ( isset( $atts['include'] ) && strpos( $atts['include'], ',' ) !== false ) {
				$exclude_ids = explode( ',', $atts['include'] );
			} else {
				$exclude_ids = array();
			}
			$users_data     = get_users( array( 'role__in' => $exclude_roles ) );
			$uid_collection = array();
			foreach ( $users_data as $key => $users ) {
				$uid_collection[] = $users->ID;
			}
			$u               = array_unique( array_merge( $uid_collection, $exclude_ids ) );
			$atts['include'] = rtrim( implode( ',', $u ), ',' );
			unset( $atts['include_member_role'] );
		}

		$bpsh_query = build_query( $atts );
		if ( ! empty( $bpsh_query ) ) {
			$bpsh_query = '&' . $bpsh_query;
		}
		$default_atts = array(
			'title'               => '',
			'type'                => 'active', // Type: active ( default ) | random | newest | popular | online | alphabetical.
			'page'                => false,
			'per_page'            => 20,
			'max'                 => false,
			'page_arg'            => 'upage', // See https://buddypress.trac.wordpress.org/ticket/3679.
			'include'             => false, // Pass a user_id or a list (comma-separated or array) of user_ids to only show these users.
			'exclude'             => false, // Pass a user_id or a list (comma-separated or array) of user_ids to exclude these users.
			'user_id'             => false, // Pass a user_id to only show friends of this user.
			'user_ids'            => false, // Pass a user_id to only show friends of this user.
			'member_type'         => false, // Can be a comma-separated list.
			'include_member_role' => '', // Can be a comma-separated list.
			'exclude_member_role' => '', // Can be a comma-separated list.
			'member_type__in'     => '',
			'member_type__not_in' => '',
			'search_terms'        => false,
			( function_exists( 'bp_use_theme_compat_with_current_theme' ) ) ? bp_use_theme_compat_with_current_theme() : '',
			'meta_key'            => false, // Only return users with this usermeta.
			'meta_value'          => false, // Only return users where the usermeta value matches. Requires meta_key.
			'populate_extras'     => true,      // Fetch usermeta? Friend count, last active etc.
			'container_class'     => 'members', // default container,
			'bpsh_query'          => $bpsh_query,
		);

		$members_atts = shortcode_atts( $default_atts, $atts );

		update_option( 'bpsh_membe_queryargs', $bpsh_query );
		extract( $members_atts );

		$_bp_theme_package = get_option( '_bp_theme_package_id' );
		ob_start();

		if ( function_exists( 'bp_is_active' ) ) {

			if ( $title != '' ) {
				?>
				<h3 class="section-title"><span><?php echo $title; ?></span></h3>
				<?php
			}
			if ( $_bp_theme_package == 'legacy' ) {
				$this->buddypress_shortcodes_template( 'legacy/members/bp-shortcodes-members.php' );
			} else {
				$this->buddypress_shortcodes_template( 'nouveau/members/bp-shortcodes-members.php' );
			}
		}

		return ob_get_clean();
	}


	/**
	 * Call BuddyPress Group Listing shortcode
	 *
	 * @since    1.0.0
	 */
	public function buddypress_shortcodes_group_listing( $atts, $content = null ) {
		global $groups_atts;

		/* Check Groups Components not activated then return shortcode */
		$bp_components = get_option( 'bp-active-components' );
		if ( ! isset( $bp_components['groups'] ) ) {
			return $content;
		}

		/* Check Scope is personal then pass current user id */
		if ( isset( $atts['scope'] ) && $atts['scope'] == 'personal' ) {
			$user_id         = ( bp_displayed_user_id() ) ? bp_displayed_user_id() : bp_loggedin_user_id();
			$atts['user_id'] = $user_id;
		}
		$bpsh_query = build_query( $atts );
		if ( ! empty( $bpsh_query ) ) {
			$bpsh_query = '&' . $bpsh_query;
		}

		$default_atts = array(
			'title'              => '',
			'type'               => 'alphabetical', // popular, alphabetical, invites, single-group
			'order'              => 'DESC',
			'orderby'            => 'last_activity',
			'page'               => 1,
			'per_page'           => 20,
			'max'                => false,
			'show_hidden'        => false,
			'page_arg'           => 'grpage',
			'user_id'            => false,
			'slug'               => false,
			'search_terms'       => false,
			'group_type'         => false,
			'group_type__in'     => '',
			'group_type__not_in' => '',
			'meta_query'         => false,
			( function_exists( 'bp_use_theme_compat_with_current_theme' ) ) ? bp_use_theme_compat_with_current_theme() : '',
			'include'            => false,
			'exclude'            => false,
			'parent_id'          => null,
			'update_meta_cache'  => true,
			'container_class'    => 'group', // default container,
			'bpsh_query'         => $bpsh_query,
		);

		$groups_atts = shortcode_atts( $default_atts, $atts );
		extract( $groups_atts );

		$_bp_theme_package = get_option( '_bp_theme_package_id' );
		ob_start();
		if ( function_exists( 'bp_is_active' ) ) {

			if ( $title != '' ) {
				?>
				<h3 class="section-title"><span><?php echo $title; ?></span></h3>
				<?php
			}

			if ( $_bp_theme_package == 'legacy' ) {
				$this->buddypress_shortcodes_template( 'legacy/groups/bp-shortcodes-groups.php' );
			} else {
				$this->buddypress_shortcodes_template( 'nouveau/groups/bp-shortcodes-groups.php' );
			}
		}
		return ob_get_clean();
	}

	/**
	 * Display Loggedin User Notifications
	 *
	 * @since    1.0.0
	 */
	public function buddypress_shortcodes_notifications( $atts, $content = null ) {
		global $notifications_atts;
		$bpsh_query = build_query( $atts );
		if ( ! empty( $bpsh_query ) ) {
			$bpsh_query = '&' . $bpsh_query;
		}

		$default_atts = array(
			'title'           => '',
			'order'           => 'DESC',
			'page'            => 1,
			'per_page'        => 20,
			'max'             => '',
			'container_class' => 'notification', // default container,
			'bpsh_query'      => $bpsh_query,
		);

		$notifications_atts = shortcode_atts( $default_atts, $atts );
		extract( $notifications_atts );

		$_bp_theme_package = get_option( '_bp_theme_package_id' );

		ob_start();
		if ( function_exists( 'bp_is_active' ) && is_user_logged_in() ) {

			if ( $title != '' ) {
				?>
				<h3 class="section-title"><span><?php echo $title; ?></span></h3>
				<?php
			}
			if ( $_bp_theme_package == 'legacy' ) {
				$this->buddypress_shortcodes_template( 'legacy/notifications/notifications-loop.php' );
			} else {
				$this->buddypress_shortcodes_template( 'nouveau/notifications/notifications-loop.php' );
			}
		} else {

		}

		return ob_get_clean();
	}

	/*
	 * Set Activity Params when activity elementor widget load on page
	 */
	public function buddypress_shortcodes_activity_localize_scripts( $params = array() ) {
		global $post;
		$current_component = '';
		if ( isset( $post->ID ) && $post->ID != '' && $post->ID != '0' ) {
			$_elementor_controls_usage = get_post_meta( get_the_ID(), '_elementor_controls_usage', true );
			$current_component         = '';
			if ( ! empty( $_elementor_controls_usage ) ) {
				foreach ( $_elementor_controls_usage as $key => $value ) {
					if ( $key == 'buddypress_shortcode_activity_widget' ) {
						$current_component = 'activity';
						break;
					}
				}
			}
		}

		if ( isset( $post->post_content ) && ( has_shortcode( $post->post_content, 'activity-listing' ) ) ) {
			$current_component = 'activity';
		}

		if ( $current_component == '' ) {
			return $params;
		}
		$activity_params = array(
			'user_id'    => bp_loggedin_user_id(),
			'object'     => 'user',
			'backcompat' => array(
				'before_post_form'  => (bool) has_action( 'bp_before_activity_post_form' ),
				'post_form_options' => (bool) has_action( 'bp_activity_post_form_options' ),
			),
			'post_nonce' => wp_create_nonce( 'post_update', '_wpnonce_post_update' ),
		);

		$user_displayname = bp_get_loggedin_user_fullname();

		if ( buddypress()->avatar->show_avatars ) {
			$width           = bp_core_avatar_thumb_width();
			$height          = bp_core_avatar_thumb_height();
			$activity_params = array_merge(
				$activity_params,
				array(
					'avatar_url'    => bp_get_loggedin_user_avatar(
						array(
							'width'  => $width,
							'height' => $height,
							'html'   => false,
						)
					),
					'avatar_width'  => $width,
					'avatar_height' => $height,
					'user_domain'   => bp_loggedin_user_domain(),
					'avatar_alt'    => sprintf(
						   /* translators: %s: member name */
						__( 'Profile photo of %s', 'buddypress' ),
						$user_displayname
					),
				)
			);
		}

		/**
		 * Filters the included, specific, Action buttons.
		 *
		 * @since 3.0.0
		 *
		 * @param array $value The array containing the button params. Must look like:
		 * array( 'buttonid' => array(
		 *  'id'      => 'buttonid',                            // Id for your action
		 *  'caption' => __( 'Button caption', 'text-domain' ),
		 *  'icon'    => 'dashicons-*',                         // The dashicon to use
		 *  'order'   => 0,
		 *  'handle'  => 'button-script-handle',                // The handle of the registered script to enqueue
		 * );
		 */
		$activity_buttons = apply_filters( 'bp_nouveau_activity_buttons', array() );

		if ( ! empty( $activity_buttons ) ) {
			$activity_params['buttons'] = bp_sort_by_key( $activity_buttons, 'order', 'num' );

			// Enqueue Buttons scripts and styles
			foreach ( $activity_params['buttons'] as $key_button => $buttons ) {
				if ( empty( $buttons['handle'] ) ) {
					continue;
				}

				if ( wp_style_is( $buttons['handle'], 'registered' ) ) {
					wp_enqueue_style( $buttons['handle'] );
				}

				if ( wp_script_is( $buttons['handle'], 'registered' ) ) {
					wp_enqueue_script( $buttons['handle'] );
				}

				unset( $activity_params['buttons'][ $key_button ]['handle'] );
			}
		}

		// Activity Objects
		if ( ! bp_is_single_item() && ! bp_is_user() ) {
			$activity_objects = array(
				'profile' => array(
					'text'                     => __( 'Post in: Profile', 'buddypress' ),
					'autocomplete_placeholder' => '',
					'priority'                 => 5,
				),
			);

			// the groups component is active & the current user is at least a member of 1 group
			if ( bp_is_active( 'groups' ) && bp_has_groups(
				array(
					'user_id' => bp_loggedin_user_id(),
					'max'     => 1,
				)
			) ) {
				$activity_objects['group'] = array(
					'text'                     => __( 'Post in: Group', 'buddypress' ),
					'autocomplete_placeholder' => __( 'Start typing the group name...', 'buddypress' ),
					'priority'                 => 10,
				);
			}

			/**
			 * Filters the activity objects to apply for localized javascript data.
			 *
			 * @since 3.0.0
			 *
			 * @param array $activity_objects Array of activity objects.
			 */
			$activity_params['objects'] = apply_filters( 'bp_nouveau_activity_objects', $activity_objects );
		}

		$activity_strings = array(
			'whatsnewPlaceholder' => sprintf( __( "What's new, %s?", 'buddypress' ), bp_get_user_firstname( $user_displayname ) ),
			'whatsnewLabel'       => __( 'Post what\'s new', 'buddypress' ),
			'whatsnewpostinLabel' => __( 'Post in', 'buddypress' ),
			'postUpdateButton'    => __( 'Post Update', 'buddypress' ),
			'cancelButton'        => __( 'Cancel', 'buddypress' ),
		);

		if ( bp_is_group() ) {
			$activity_params = array_merge(
				$activity_params,
				array(
					'object'  => 'group',
					'item_id' => bp_get_current_group_id(),
				)
			);
		}

		$params['activity'] = array(
			'params'  => $activity_params,
			'strings' => $activity_strings,
		);

		return $params;
	}

	/**
	 * @param $texts
	 *
	 * @return false|string
	 */
	public function sfb_go_pro_template( $texts ) {
		ob_start();

		?>
		<div class="elementor-nerd-box">
			<div class="elementor-nerd-box-title"><?php echo $texts['title']; ?></div>
			<?php foreach ( $texts['messages'] as $message ) : ?>
				<div class="elementor-nerd-box-message"><?php echo $message; ?></div>
			<?php endforeach; ?>

			<?php if ( $texts['link'] ) : ?>
				<a class="elementor-button elementor-panel-scheme-title" href="<?php echo $texts['link']; ?>"
				   target="_blank">
					<?php echo __( 'Go PRO', 'stax-buddy-builder' ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php

		return ob_get_clean();
	}
	
	public function buddypress_shortcodes_bp_nouveau_register_scripts( $scripts_args ) {
		
		if ( function_exists('buddypress') && isset(buddypress()->buddyboss )) {			
			return $scripts_args;
		}
		
		if ( isset($scripts_args['bp-nouveau'])) {
			$scripts_args['bp-nouveau']['file'] = SHORTCODES_FOR_BUDDYPRESS_PLUGIN_URL. 'public/js/buddypress-nouveau%s.js';
		}		
		return $scripts_args;
	}
	
	public function buddypress_shortcodes_activity_heartbeat_strings( $strings = array() ) {
		global $post;
		
		$flg = false;
		if ( isset($post->ID) && $post->ID != '' && $post->ID != '0') {
			$_elementor_controls_usage = get_post_meta($post->ID, '_elementor_controls_usage', true);
			if (  !empty($_elementor_controls_usage)) {
				foreach($_elementor_controls_usage as $key=>$value) {
					if ( $key == 'buddypress_shortcode_activity_widget' ) {
						$flg = true;
						break;
					}
				}
			}
		}
		if ( $flg == true ) {
			unset($strings['pulse']);			
		}
		
		return $strings;
	}

}
