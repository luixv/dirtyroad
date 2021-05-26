<?php

if ( !defined( 'ABSPATH' ) ) exit;


class BP_Block_Member {

	private  $their_blocked_ids = array(); 	// ids that are blocking you

	private  $your_blocked_ids = array();	// ids that you are blocking

	private  $your_member_type = '';        // your member type

	private  $visiblity = '';

	private  $prompt = '';

	private $this_id = 0;					// your id

	private $template_pack = '';			// the current BP template pack being used: legacy or nouveau

	private $block_admin_form_action = '';
	private $block_create_message = ''; 	// success / error message for actions in wp-admin
	private $block_assign_message = ''; 	// success / error message for actions in wp-admin
	private $block_visibility_message = ''; 	// success / error message for actions in wp-admin
	private $block_prompt_message = ''; 	// success / error message for actions in wp-admin
	private $block_redirect_url_message = ''; 	// success / error message for actions in wp-admin
	private $block_license_message = ''; 	// success / error message for actions in wp-admin

	private static $instance = NULL;

		public static function get_instance() {
			if ( NULL === self::$instance )
				self::$instance = new self;

			return self::$instance;
		}

		protected function __construct() {

			if ( is_multisite() ) {

				if ( ! function_exists( "is_plugin_active_for_network" ) ) {
					require_once( ABSPATH . "/wp-admin/includes/plugin.php" );
				}

			}

			if ( is_multisite() && is_plugin_active_for_network( "bp-block-member/bp-block-member.php" ) ) {
				$this->block_admin_form_action = 'settings.php?page=bp-block-member';
				add_action("network_admin_menu", array( $this, "multisite_admin_menu" ) );
			} else {
				$this->block_admin_form_action = 'options-general.php?page=bp-block-member';
				add_action( "admin_menu", array( $this, "admin_menu" ) );
			}


			$this->this_id 				= bp_loggedin_user_id();
			$this->their_blocked_ids 	= $this->_get_their_blocked_ids();
			$this->your_blocked_ids 	= $this->_get_your_blocked_ids();
			$this->visibility 			= get_site_option( 'bp_block_visibility' );
			$this->prompt 				= get_site_option( 'bp_block_prompt' );
			$this->your_member_type 	= bp_get_member_type( $this->this_id );

			$this->_block_redirect_url_update();
			$this->_block_visibility_update();
			$this->_block_prompt_update();
			$this->_block_roles_update();
			$this->_block_create();
			$this->_bp_block_handle_actions();

			$this->template_pack = bp_get_theme_package_id();


			add_action( 'wp_footer',	array( $this, 'block_button_js' ), 1 );


			add_action( 'bp_pre_user_query_construct',	array( $this, '_members_query' ), 1, 1 );
			add_action( 'bp_init', 						array( $this, '_member_profile'), 99 );
			add_action( 'bp_member_header_actions', 	array( $this, '_profile_page_block_button'), 50 );  //use 50 so block button is last

			// add support for YOUZER profile plugin to add block button on profile
			add_action('yz_after_header_username_head', array( $this, '_profile_page_block_button'), 50 );

			// members loop
			add_action( 'bp_directory_members_actions', array( $this, '_members_loop' ), 1 );

			//wp-admin
			add_action( 'admin_head', array( $this, '_block_admin_styles' ) );
			//add_action( is_multisite() ? 'network_admin_menu' : 'admin_menu', array( $this, '_block_admin_menu' ) );


			add_action( 'admin_init', array( $this, 'pp_block_save_license') );
			add_action( 'admin_init', array( $this, 'pp_block_activate_license') );
			add_action( 'admin_init', array( $this, 'pp_block_deactivate_license') );


			//groups
			if ( bp_is_active( 'groups' ) ) {
				add_filter( 'bp_group_members_user_join_filter', 	array( $this, '_group_members'), 20, 1 );
				add_action( 'bp_group_members_list_item_action',    array( $this, '_group_members_loop' ), 1 );
			}

			//activity
			if ( bp_is_active( 'activity' ) ) {
				add_filter( 'bp_get_send_public_message_button',    array( $this, '_remove_profile_public_message_button'), 1, 1 );
				add_filter( 'bp_activity_get_where_conditions',     array( $this, '_activity_where_query'), 1, 5);
				add_filter( 'bp_activity_can_comment_reply',        array( $this, 'current_comment_reply_check'), 1, 2);
				add_action( 'bp_before_activity_comment',           array( $this, 'before_current_activity_comment') );
			}

			//bbPress  // commented out for now
			/*
			if ( class_exists('bbPress') ) {
				add_filter( 'bbp_get_single_forum_description', array( $this, 'bbp_blocked_forum_description'), 1, 2 );
				//add_filter( 'bbp_get_forum_topic_count', array( $this, 'bbp_blocked_topic_count'), 1, 2 );
				add_filter( 'pre_get_posts',                    array( $this, 'bbp_blocked_replies'), 9, 2 );
				add_filter( 'bbp_before_has_topics_parse_args', array( $this, 'bbp_blocked_topics' ), 10, 1 );
			}
			*/

			//messages
			if ( bp_is_active( 'messages' ) ) {
				add_filter( 'bp_get_send_message_button', 			array( $this, '_remove_profile_private_message_button'), 100, 1 );
				add_action( 'messages_message_before_save', 		array( $this, '_check_recipients' ) );
			}

			// notifications
			add_filter( 'bp_activity_at_name_do_notifications', array( $this, 'maybe_send_mention_email_and_notificaton'), 1, 4 );

			//friends
			if ( bp_is_active( 'friends' ) )
				add_filter( 'bp_get_add_friend_button', array( $this, '_remove_add_friend_button'), 1, 10 );
		}


		public function get_your_blocked_ids() {
			return $this->your_blocked_ids;
		}


		function set_blocked_arrays() {

			$this->their_blocked_ids 	= $this->_get_their_blocked_ids();
			$this->your_blocked_ids 	= $this->_get_your_blocked_ids();

				write_log( 'ids they am blocking - set_blocked_arrays()' );
				write_log( $this->their_blocked_ids );
				write_log( "\n\n" );
		}


		// Return false if activity author is blocked by the recipient, iow. user_id
		function maybe_send_mention_email_and_notificaton( $value, $usernames, $user_id, $activity ) {

			if ( in_array( $user_id, $this->their_blocked_ids ) == true ) {
				return false;
			}

			return true;

		}

		/*
		function _block_admin_menu() {

			if ( is_multisite() ) {
				add_submenu_page('settings.php', __( 'BuddyBlock', 'bp'), __( 'BuddyBlock', 'bp' ), 'unblock_member', 'bp-block-member', array( $this, '_block_admin_screen' ) );
			} else {
				add_options_page( __( 'BuddyBlock', 'bp'), __( 'BuddyBlock', 'bp' ), 'unblock_member', 'bp-block-member', array( $this, '_block_admin_screen' ) );
			}
		}
		*/

		function admin_menu() {
			add_options_page( __( 'BuddyBlock', 'bp'), __( 'BuddyBlock', 'bp' ), 'unblock_member', 'bp-block-member', array( $this, '_block_admin_screen' ) );
		}

		function multisite_admin_menu() {
			add_submenu_page('settings.php', __( 'BuddyBlock', 'bp'), __( 'BuddyBlock', 'bp' ), 'unblock_member', 'bp-block-member', array( $this, '_block_admin_screen' ) );
		}


		/*
		 *  Create a Block button wherever
		 */

		public function single_block_button( $target_id = false ) {

			if ( ! $target_id )
				return '---- no target id ----';


			if ( $this->this_id != $target_id )
				$this->_block_button( $target_id );

		}



		function block_button_js() {
			echo '<script type="text/javascript" >
			jQuery(document).ready(function($) {
				$("a.block-button").one("click", function() {
					$(this).click(function () { return false; });
				});
			});
			</script>';
		}



		/* bbPress functions */

		public function bbp_blocked_forum_description( $retstr, $r ) {
			global $wpdb;

			$forum_id = $r['forum_id'];

			if ( ! empty( $this->their_blocked_ids ) ) {

				$blocked_ids = implode(",", $this->their_blocked_ids);
				$blocked_topics_num = $wpdb->get_var( "SELECT COUNT( ID ) FROM {$wpdb->base_prefix}posts WHERE post_author IN ({$blocked_ids}) AND post_parent = $forum_id" );

				if ( $blocked_topics_num != NULL )
					$retstr = '';

			}

			return $retstr;
		}

		public function bbp_blocked_topic_count( $topics, $forum_id ) {
			global $wpdb;

			if ( ! empty( $this->their_blocked_ids ) ) {

				$blocked_ids = implode(",", $this->their_blocked_ids);
				$blocked_topics_num = $wpdb->get_var( "SELECT COUNT( ID ) FROM {$wpdb->base_prefix}posts WHERE post_author IN ({$blocked_ids}) AND post_parent = $forum_id AND post_type = 'topic'" );

				$topics -= $blocked_topics_num;

			}

			return $topics;

		}

		public function bbp_blocked_topics( $args ) {
			global $wpdb;

			if ( ! empty( $this->their_blocked_ids ) ) {

				$blocked_ids = implode(",", $this->their_blocked_ids);
				$blocked_posts = $wpdb->get_col( "SELECT ID FROM {$wpdb->base_prefix}posts WHERE post_author IN ({$blocked_ids}) AND post_type = 'topic'" );

				$args['post__not_in']  = $blocked_posts;

			}

			return $args;

		}



		public function bbp_blocked_replies( $query = false ) {
			global $wpdb;

			// Bail if not a bbPress topic and reply query

			$bb_types = array( bbp_get_topic_post_type(), bbp_get_reply_post_type() );
			if ( in_array( $query->get( 'post_type'), $bb_types ) ) {
				return $query;
			}


			if ( ! empty( $this->their_blocked_ids ) ) {

				$blocked_ids = implode(",", $this->their_blocked_ids);
				$blocked_posts = $wpdb->get_col( "SELECT ID FROM {$wpdb->base_prefix}posts WHERE post_author IN ({$blocked_ids}) AND post_type = 'reply'" );

				//$query->set( 'post__not_in', array(206) );
				$query->set( 'post__not_in', $blocked_posts );
			}

			return $query;
		}

		/* end bbPress functions */




		public function before_current_activity_comment() {
			global $activities_template;

			if ( in_array( $activities_template->activity->current_comment->user_id, $this->their_blocked_ids ) ) {
				$activities_template->activity->current_comment->user_id = '0';
				$activities_template->activity->current_comment->content = 'comment removed';
				$activities_template->activity->current_comment->primary_link = '';
				$activities_template->activity->current_comment->display_name = 'anon';
				$activities_template->activity->current_comment->user_fullname = 'anon';
				//$activities_template->activity->current_comment->date_recorded = '';
			}

			if ( ! $this->visibility )
				return;
			else {
				if ( in_array( $activities_template->activity->current_comment->user_id, $this->your_blocked_ids ) ) {

					$activities_template->activity->current_comment->user_id = '0';
					$activities_template->activity->current_comment->content = 'blocked member comment';
					$activities_template->activity->current_comment->primary_link = '';
					$activities_template->activity->current_comment->display_name = 'blocked member';
					$activities_template->activity->current_comment->user_fullname = 'blocked member';
					//$activities_template->activity->current_comment->date_recorded = '';
				}
			}
		}


		public function current_comment_reply_check( $can_comment, $comment ) {

			if ( $comment->user_id == 0 )
				$can_comment = false;

			return $can_comment;
		}



		// get the ids of everyone blocking you
		private function _get_their_blocked_ids() {
			global $wpdb;

			$target_id = $this->this_id;

			$blocked_ids = $wpdb->get_col( "SELECT user_id FROM {$wpdb->base_prefix}bp_block_member WHERE target_id = '$target_id' ");

			return $blocked_ids;
		}

		// get the ids of everyone you are blocking
		private function _get_your_blocked_ids() {
			global $wpdb;

			$user_id = $this->this_id;

			$blocked_ids = $wpdb->get_col( "SELECT target_id FROM {$wpdb->base_prefix}bp_block_member WHERE user_id = '$user_id' ");

			return $blocked_ids;
		}



		// adjust the members query
		function _members_query( $query_array ) {

			if ( ! $this->visibility ) {

				if ( !empty( $this->their_blocked_ids ) )
					$query_array->query_vars['exclude'] = $this->their_blocked_ids;

			}
			else {

				$exclude_ids = array_merge( $this->their_blocked_ids, $this->your_blocked_ids );

				if ( !empty( $exclude_ids ) )
					$query_array->query_vars['exclude'] = $exclude_ids;


				$blocked_member_types = get_user_meta( $this->this_id, 'blocked_member_types' );
 				if ( empty( $blocked_member_types ) )
					$blocked_member_types = array();
				else
					$blocked_member_types = $blocked_member_types[0];

				if ( ! empty( $blocked_member_types ) ) {

					$blocked_member_types_names = array();

					foreach( $blocked_member_types as $btype ) {

						$blocked_member_types_names[] = $btype;
					}

					$query_array->query_vars['member_type__not_in'] = $blocked_member_types_names;

				}
			}
		}


		// filter activity
		function _activity_where_query( $where_conditions, $r, $select_sql, $from_sql, $join_sql) {

			if ( ! $this->visibility ) {

				if ( !empty( $this->their_blocked_ids ) ) {

					$blocked_ids = implode(",", $this->their_blocked_ids);

					$where_conditions["blocked_sql"] = "a.user_id NOT IN ({$blocked_ids}) AND a.secondary_item_id NOT IN ({$blocked_ids}) ";

				}
			}
			else {

				$blocked_ids = array_merge( $this->their_blocked_ids, $this->your_blocked_ids );

				// get all member ids that are blocking the current user's member type
				// $this->your_member_type = bp_get_member_type( $this->this_id );

				$member_type_ids = $this->_get_member_type_ids();

				$blocked_ids = array_merge( $blocked_ids, $member_type_ids );

				if ( !empty( $blocked_ids ) ) {

					$blocked_ids = implode(",", $blocked_ids);

					$where_conditions["blocked_sql"] = "a.user_id NOT IN ({$blocked_ids}) AND a.secondary_item_id NOT IN ({$blocked_ids}) ";

				}

			}

			return $where_conditions;

		}

		function _get_member_type_ids() {
			global $wpdb;

			//echo '<br>called _get_member_type_ids()';

			$member_type_ids = array();

			if ( empty ( $this->your_member_type  ) )
				return $member_type_ids;

			$like_type = '%' . $this->your_member_type  . '%';
			$member_type_ids = $wpdb->get_col( "SELECT user_id FROM {$wpdb->base_prefix}usermeta WHERE meta_key = 'blocked_member_types' AND meta_value LIKE '$like_type' " );

			//var_dump( $member_type_ids );

			return $member_type_ids;

		}


		// check if a member is trying to access a blocked profile
		function _member_profile() {

			if ( ! bp_is_user() )
				return;

			if ( bp_is_my_profile() )
				return;

			// redirection url
			$pp_block_url = get_site_option( 'pp-block-url' );

			if ( $pp_block_url == false ) {

				$front_id = get_site_option('page_on_front');

				if ( $front_id != false )
					$pp_block_url = trailingslashit( esc_url( get_permalink( $front_id ) ) );
				else
					$pp_block_url = trailingslashit( site_url() );

			}

			if ( in_array( bp_displayed_user_id(), $this->their_blocked_ids ) ) {
				bp_core_redirect( $pp_block_url );
			}

			if ( ! is_super_admin() ) {

				if ( $this->visibility ) {

					$blocked_member_types = get_user_meta( bp_displayed_user_id(), 'blocked_member_types' );

	 				if ( empty( $blocked_member_types ) )
						$blocked_member_types = array();
					else
						$blocked_member_types = $blocked_member_types[0];

					if ( in_array( $this->your_member_type, $blocked_member_types ) )
						bp_core_redirect( $pp_block_url );

				}
			}

		}

		// insert a custom message if you are blocked or blocking re recipient
		function _override_bp_l10n( $kind ) {
			global $l10n;

			$mo = new MO();

			if ( $kind == 'their' ) {
				$mo->add_entry( array( 'singular' => 'There was an error sending that message, please try again', 'translations' => array( __ ('You have been blocked by one of the persons you are attempting to send a message to.  Your message has not been sent.', 'bp-block-member' ) ) ) );
				$mo->add_entry( array( 'singular' => 'There was a problem sending that reply. Please try again.', 'translations' => array( __ ('You have been blocked by one of the persons you are attempting to send a reply to.  Your reply has not been sent.', 'bp-block-member' ) ) ) );
			}
			else {
				$mo->add_entry( array( 'singular' => 'There was an error sending that message, please try again', 'translations' => array( __ ('You are blocking   one of the persons you are attempting to send a message to.  Your message has not been sent.', 'bp-block-member' ) ) ) );
				$mo->add_entry( array( 'singular' => 'There was a problem sending that reply. Please try again.', 'translations' => array( __ ('You are blocking  one of the persons you are attempting to send a reply to.  Your reply has not been sent.', 'bp-block-member' ) ) ) );
			}

			if ( isset( $l10n['buddypress'] ) )
				$mo->merge_with( $l10n['buddypress'] );

			$l10n['buddypress'] = &$mo;
			unset( $mo );
		}


		// remove members who have blocked you or you have blocked from receiving messages or replies
		function _check_recipients( $message_info ) {

			$recipients = $message_info->recipients;

			$u = 0; // # of recipients in the message that are blocked

			$kind = '';

			foreach ( $recipients as $key => $recipient ) {

				if (($key = array_search( $recipient->user_id, $this->their_blocked_ids )) !== FALSE) {
					$u++;
					$kind = 'their';
				}
				// to prevent harassment
				if (($key = array_search( $recipient->user_id, $this->your_blocked_ids )) !== FALSE) {
					$u++;
					$kind = 'your';
				}
			}

			// if any recipients being blocked, remove everyone from the recipient's list
			// this is done to prevent the message from being sent to anyone and is another spam prevention measure

			if (  $u > 0 && $kind != '' ) {
				$this->_override_bp_l10n( $kind );
				unset( $message_info->recipients );
			}

			return $message_info;
		}


		//	Remove members that have blocked you from group->members screen
		function _group_members( $sql ) {

			$exclude = implode( ',', $this->their_blocked_ids );

			if ( !empty( $exclude ) ) {
				$exclude_sql = " AND m.user_id NOT IN ({$exclude}) ";

				$pos = strpos( $sql, 'ORDER' );

				$sql = substr_replace($sql, $exclude_sql, $pos, 0);
			}

			return $sql;
		}

		/*
		 *  Create Block button on Profile page
		*/

		function _profile_page_block_button() {

			if ( bp_is_my_profile() )
				return;

			$target_id = bp_displayed_user_id();

			if ( $this->this_id != $target_id )
				$this->_block_button( $target_id );

		}




		/*
		 *  remove action buttons on Profile page if you are blocking them - to prevent harassment
		*/


		function _remove_profile_public_message_button( $button ) {
			$target_id = bp_displayed_user_id();

			if ( in_array( $target_id, $this->your_blocked_ids ) )
				$button = NULL;

			return $button;
		}

		function _remove_profile_private_message_button( $button ) {
			$target_id = bp_displayed_user_id();

			if ( in_array( $target_id, $this->your_blocked_ids ) )
				$button = '';

			return $button;

		}

		function _remove_add_friend_button( $button ) {

			if ( bp_is_user() )
				$target_id = bp_displayed_user_id();
			else
				$target_id = bp_get_member_user_id();

			if ( in_array( $target_id, $this->your_blocked_ids ) ) {

				if ( class_exists( 'Youzer' ) ) {
					$button = array( 'id' => 0 );
				} else {
					$button = '';
				}

			}
			return $button;
		}

		/*
		 *  Create Block buttons in Members loop
		*/

		function _members_loop() {

			$target_id = bp_get_member_user_id();

			if ( $this->this_id != $target_id )
				$this->_block_button( $target_id );

		}


		/*
		 *  Create Block buttons in Group Members loop
		*/

		function _group_members_loop() {

			$target_id = bp_get_group_member_id();

			if ( $this->this_id != $target_id )
				$this->_block_button( $target_id );

		}



		/*
		 * Create and handle the Block button
		*/

		function _block_button( $target_id ) {

			if ( !$target_id )
				return;

			if ( user_can( $target_id, 'unblock_member' ) )
				return;

			if ( in_array( $target_id, $this->your_blocked_ids ) ) {
				$block_button_text = __('UnBlock', 'bp-block-member');
				$block_button_title = __('Allow this member to see you.', 'bp-block-member');
				$style = 'style="color: #CC0000"';
				$action = 'unblock';
				$confirm = '';
			}
			else {
				$block_button_text = __('Block', 'bp-block-member');
				$block_button_title = __('Block this member from seeing you.', 'bp-block-member');
				$style = '';
				$action = 'block';

				if ( $this->prompt ) {
					$confirm_text = __('Are you sure you want to block that member?', 'bp-block-member');
					$confirm = 'onclick="return confirm(' . "'" . $confirm_text  . "'" . ')"';
				} else {
					$confirm = '';
				}
			}


			$pp_bp_block_button = '<a class="block-button" ' . $style . ' id="' . $target_id . '" href="' . $this->_bp_block_link( $target_id, $action ) . '"' .  $confirm  . ' title="' . $block_button_title . '" >' .  $block_button_text . '</a>';

			if ( $this->template_pack == 'legacy' ) {

				$pp_bp_block_button = '<div class="generic-button block-member" id="block-member-' . $target_id . '">' .  $pp_bp_block_button . '</div>';

			} elseif ( $this->template_pack == 'nouveau' ) {

				if ( bp_is_members_directory() ) {

					$pp_bp_block_button = '<li class="generic-button">&nbsp;&nbsp;' .  $pp_bp_block_button . '</li>';

				} else {

					$pp_bp_block_button = '<li class="generic-button">' .  $pp_bp_block_button . '</li>';
				}
			}

			$block_button = apply_filters( 'pp_bp_block_button', $pp_bp_block_button, $target_id, $action, $style );

			echo $block_button;

		}


		function _bp_block_link( $target_id, $action ) {

			$token = wp_create_nonce( 'block-' . $target_id );

			$partial = '/?action=' . $action . '&id=' . $this->this_id . '&target=' . $target_id . '&token=' . $token;


			if ( bp_is_user() )
				$url = bp_core_get_user_domain( $target_id ) . $partial;
			elseif ( bp_is_directory() )
				$url = site_url() . '/' . bp_get_members_root_slug() . $partial;
			elseif ( bp_is_group_members() )
				$url = site_url() . '/' . bp_get_groups_root_slug() . '/' . bp_get_current_group_slug() . '/' . bp_get_members_root_slug() . $partial;
			elseif ( is_page() ) {
				global $post;
				$url = get_page_link( $post->ID )  . '?action=' . $action . '&id=' . $this->this_id . '&target=' . $target_id . '&token=' . $token;
			}
			else {

				$url =  '?action=' . $action . '&id=' . $this->this_id . '&target=' . $target_id . '&token=' . $token;
			}

				// after Order By or Search ( ajax ) bp_get_group_permalink() does not work
				// PHP Notice:  Trying to get property of non-object in .../wp-content/plugins/buddypress/bp-groups/bp-groups-template.php on line 775
				// $url = bp_get_group_permalink() . '/between/' . bp_get_members_root_slug() . $partial;


			return $url;

			/*
			// this causes the url to point to wp-admin/admin-ajax.php after Order By or Search - due to BP ajax call
			return apply_filters( 'bp_profile_block_link', esc_url( add_query_arg( array(
				'action'    => $action,
				'id'        => $this->this_id,
				'target'    => $target_id,
				'token'     => wp_create_nonce( 'block-' . $target_id )
			) ) ), $this->this_id, $target_id );
			*/
		}

		function _bp_block_handle_actions() {

			if ( !isset( $_REQUEST['action'] ) || !isset( $_REQUEST['id'] ) || !isset( $_REQUEST['token'] ) || !isset( $_REQUEST['target'] ) ) return;

			if ( ! wp_verify_nonce( $_REQUEST['token'], 'block-' . $_REQUEST['target'] ) )
                die( 'Block Button Security Check - Failed' );

			switch ( $_REQUEST['action'] ) {
				case 'unblock' :
					$this->_unblock( $_REQUEST['id'], $_REQUEST['target'] );
				break;

				case 'block' :
					$this->_block( $_REQUEST['id'], $_REQUEST['target'] );
				break;

				default :
					do_action( 'bp_block_action' );
				break;
			}

			wp_safe_redirect( esc_url_raw( remove_query_arg( array( 'action', 'id', 'target', 'token' ) ) ) );
			exit();
		}

		function _unblock( $user_id, $target_id ) {
			global $wpdb;

			if ( $user_id != $this->this_id )
				return;

			$wpdb->query( $wpdb->prepare(
				"DELETE FROM {$wpdb->base_prefix}bp_block_member WHERE user_id = %d AND target_id = %d",
				$user_id, $target_id
				)
			);

		}

		function _block( $user_id, $target_id ) {
			global $wpdb;

			if ( $user_id != $this->this_id )
				return;

			$wpdb->query(  $wpdb->prepare(
				"INSERT INTO {$wpdb->base_prefix}bp_block_member (user_id, target_id) VALUES (%d, %d)",
				$user_id, $target_id
				)
			);

			if ( bp_is_active( 'friends' ) ) {

				$is_friend = friends_check_friendship_status( $user_id, $target_id ); // friends_check_friendship( $user_id, $target_id );

				if ( $is_friend != 'not_friends' ) {

					friends_remove_friend( $user_id, $target_id );

					if ( bp_is_active( 'notifications' ) )
						bp_notifications_delete_notifications_by_item_id( $user_id, $target_id, 'friends', 'friendship_request', $secondary_item_id = false );

					//if ( $is_friend != 'is_friends' ) // friends_remove_friend substracts 1 even if the request is pending
					//	friends_update_friend_totals( $user_id, $target_id );

				}
				else {
					if ( bp_is_active( 'notifications' ) )
						bp_notifications_delete_notifications_by_item_id( $user_id, $target_id, 'friends', 'friendship_request', $secondary_item_id = false );
				}
			}
		}



	/**
	 *	menu page in wp-admin
	*/





	function pp_block_save_license() {

		if ( ! empty( $_POST["pp-block-lic-save"] ) ) {

		 	if ( ! check_admin_referer( 'pp_block_lic_save_nonce', 'pp_block_lic_save_nonce' ) )
				return;

			$old = get_site_option( 'pp_block_license_key' );
			$new = trim( $_POST["pp_block_license_key"] );

			if ( $old && $old !=  $new ) {
				delete_site_option( 'pp_block_license_status' ); // new license has been entered, so must reactivate
			}

			update_site_option( 'pp_block_license_key', $new );

			$this->block_license_message .=
					"<div class='updated below-h2'>" .  __('License Key has been saved.', 'bp-block-member') . "</div>";

		}
	}



	function pp_block_activate_license() {

		if ( isset( $_POST['pp_block_license_activate'] ) ) {

		 	if ( ! check_admin_referer( 'pp_block_lic_nonce', 'pp_block_lic_nonce' ) )
				return;

			$license = trim( get_site_option( 'pp_block_license_key' ) );

			$api_params = array(
				'edd_action'=> 'activate_license',
				'license' 	=> $license,
				'item_name' => urlencode( PP_BLOCK_MEMBERS ),
				'url'       => home_url()
			);

			$response = wp_remote_post( PP_BLOCK_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( is_wp_error( $response ) ) {
				//var_dump( $response );
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "valid" or "invalid"

			update_site_option( 'pp_block_license_status', $license_data->license );

			$this->block_license_message .=
					"<div class='updated below-h2'>" .  __('License has been activated.', 'bp-block-member') . "</div>";

		}
	}


	function pp_block_deactivate_license() {

		if ( isset( $_POST['pp_block_license_deactivate'] ) ) {

		 	if ( ! check_admin_referer( 'pp_block_lic_nonce', 'pp_block_lic_nonce' ) )
				return;

			$license = trim( get_site_option( 'pp_block_license_key' ) );

			$api_params = array(
				'edd_action'=> 'deactivate_license',
				'license' 	=> $license,
				'item_name' => urlencode( PP_BLOCK_MEMBERS ),
				'url'       => home_url()
			);

			$response = wp_remote_post( PP_BLOCK_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( is_wp_error( $response ) )
				return false;

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' ) {
				delete_site_option( 'pp_block_license_status' );
				$this->block_license_message .=
					"<div class='updated below-h2'>" .  __('License has been deactivated.', 'bp-block-member') . "</div>";
			}
			else
				$this->block_license_message .=
					"<div class='error below-h2'>" .  __('License has NOT been deactivated.', 'bp-block-member') . "</div>";

		}
	}


	function _block_admin_screen() {
		?>
		<div class="wrap">
			<div id="icon-tools" class="icon32"><br/></div>
			<h2><?php _e('BuddyBlock Settings', 'bp-block-member'); ?></h2>

			<?php $this->pp_block_license();  ?>

			<?php $this->_block_redirect_url();  ?>

			<?php $this->_block_visibility();  ?>

			<?php $this->_block_prompt();  ?>

			<?php $this->_block_roles();  ?>

			<?php $this->_block_create_form();  ?>

			<h3><?php _e('Blocked Members', 'bp-block-member'); ?></h3>

			<?php
			$bp_block_member_list_table = new BP_Block_Member_List_Table();
			$bp_block_member_list_table->prepare_items();
			?>

			<form id="notes-filter" method="post">
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php $bp_block_member_list_table->display(); ?>
			</form>
		</div>
	<?php
	}

	function pp_block_license() {

		$license 	= get_site_option( 'pp_block_license_key' );
		$status 	= get_site_option( 'pp_block_license_status' );

	?>
		<script type="text/javascript">
		jQuery(function() {
			jQuery('#license_display').click(function() {
				jQuery('#license_show').toggle();
				return false;
			});
		});
		</script>

		<div class="wrap">

			<h3><a href="#" id="license_display"><?php _e('License Options', 'bp-block-member'); ?></a></h3>

			<?php echo $this->block_license_message; ?>

			<div id="license_show" name="license_show" style="display: none;">

			<form method="post" action="<?php echo $this->block_admin_form_action; ?>">

				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('License Key', 'bp-block-member'); ?>
							</th>
							<td>
								<input id="pp_block_license_key" name="pp_block_license_key" type="text" class="regular-text" placeholder="Paste Your License Key Here" value="<?php esc_attr_e( $license ); ?>" />
								<label class="description" for="pp_block_license_key"><em><?php _e('Enter your license key', 'bp-block-member'); ?></em></label>
							</td>
						</tr>

						<?php if ( false !== $license ) { ?>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php _e('Activate License'); ?>
								</th>
								<td>
									<?php if ( $status !== false && $status == 'valid' ) { ?>
										<span style="color:#32cd32;"><?php _e('Your License is Active', 'bp-block-member' ); ?></span>
										<?php wp_nonce_field( 'pp_block_lic_nonce', 'pp_block_lic_nonce' ); ?>
										&nbsp;&nbsp;<input type="submit" class="button-secondary" name="pp_block_license_deactivate" value="<?php _e('Deactivate License', 'bp-block-member'); ?>"/>
									<?php } else {
										wp_nonce_field( 'pp_block_lic_nonce', 'pp_block_lic_nonce' ); ?>
										<input type="submit" class="button-secondary" name="pp_block_license_activate" value="<?php _e('Activate License', 'bp-block-member'); ?>"/>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>

						<tr valign="top">
							<td>
								<?php wp_nonce_field( 'pp_block_lic_save_nonce', 'pp_block_lic_save_nonce' ); ?>
								<input type="submit"  name="pp-block-lic-save" value="<?php _e("Save License Key", "bp-block-member");?>" />
							</td>
							<td>&nbsp;<em><?php _e("You must Save your Key before you can Activate your License", "bp-block-member");?></em></td>
						</tr>
					</tbody>
				</table>
			</form>
			</div>
		</div>
		<br/>
	<?php
	}

	function _block_admin_styles() {
		$page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if ( 'bp-block-member' != $page )
			return;

		$style_str = '<style type="text/css">';
		$style_str .= '.column-username { width: 20%; }';
		$style_str .= '.column-target { width: 20%; }';
		$style_str .= '.column-unblock_target { width: 60%; }';
		$style_str .= '.alt-color1 { background-color: #fcfcfb; }';
		$style_str .= '.alt-color2 { background-color: #f8f8fb; }';
		$style_str .= '</style>';
		echo $style_str;
	}

	private function _block_redirect_url() {

		if ( !is_super_admin() )
			return;

		// redirection url
		$pp_block_url = get_site_option( 'pp-block-url' );

		if ( $pp_block_url == false ) {

			$pp_block_url = '';

			$front_id = get_site_option('page_on_front');

			if ( $front_id != false )
				$pp_block_url_placeholder = trailingslashit( esc_url( get_permalink( $front_id ) ) );
			else
				$pp_block_url_placeholder = trailingslashit( site_url() );

		}
		else
			$pp_block_url_placeholder = '';

		?>

		<script type="text/javascript">
		jQuery(function() {
			jQuery('#redirect_url_display').click(function() {
				jQuery('#redirect_url_show').toggle();
				return false;
			});
		});
		</script>

		<div class='wrap'>

			<h3><a href="#" id="redirect_url_display"><?php _e('Redirect Url', 'bp-block-member'); ?></a></h3>

			<?php echo $this->block_redirect_url_message; ?>

			<div id="redirect_url_show" name="redirect_url_show" style="display: none;">

				<form action="<?php echo $this->block_admin_form_action; ?>" name="block-redirect-url-form" id="block-redirect-url-form"  method="post" class="standard-form">

					<?php echo __('When a member tries to access a blocked profile, where will they be sent?', 'bp-block-member'); ?>
					<br/>
					<?php echo __('Enter the full URL:', 'bp-block-member'); ?>
					<br/>
					&nbsp;<input type="text" id="pp-block-url" name="pp-block-url" placeholder="<?php echo $pp_block_url_placeholder; ?>" value="<?php echo $pp_block_url; ?>" size="50" />
					<br/>
					<?php echo __('<em>Leave empty to use Home or Front page.</em>', 'bp-block-member'); ?>

					<?php wp_nonce_field('block-url-action', 'block-url-field'); ?>

				<br/><br/>

				<input type="hidden" name="block-redirect-url" value="1"/>
				<input type="submit" name="submit" class="button button-primary" value="<?php _e('Save Url', 'bp-block-member'); ?>"/>
				</form>
			</div>
		</div>
		<br/>
	<?php
	}

	//  update redirect url
	private function _block_redirect_url_update() {

		if ( isset( $_POST['block-redirect-url'] ) ) {

			if ( !wp_verify_nonce($_POST['block-url-field'],'block-url-action') )
				die('Security check');

			if ( !is_super_admin() )
				return;

			if ( ! empty( $_POST['pp-block-url'] ) ) {
				$pp_block_url = esc_url_raw( $_POST['pp-block-url'] );
				$pp_block_url = trailingslashit( $pp_block_url );
				update_site_option( 'pp-block-url', $pp_block_url, true );
			}
			else
				delete_site_option( 'pp-block-url' );


			$this->block_redirect_url_message .=
					"<div class='updated below-h2'>" .  __('Redirect Url has been updated.', 'bp-block-member') . "</div>";

		}
	}


	private function _block_visibility() {

		if ( !is_super_admin() )
			return;
		?>

		<script type="text/javascript">
		jQuery(function() {
			jQuery('#visibility_display').click(function() {
				jQuery('#visibility_show').toggle();
				return false;
			});
		});
		</script>

		<div class='wrap'>

			<h3><a href="#" id="visibility_display"><?php _e('Visibility and Member Types', 'bp-block-member'); ?></a></h3>

			<?php echo $this->block_visibility_message; ?>

			<div id="visibility_show" name="visibility_show" style="display: none;">

				<?php _e('If selected, a blocked member cannot see you AND you cannot see them or their content.', 'bp-block-member'); ?>
				<br/>
				<?php _e('If selected, a member can hide Member Types via their Profile > Settings > Blocked Members.', 'bp-block-member'); ?>

				<br/><br/>

				<?php _e('If not selected, a blocked member cannot see you, but their content will be visible to you.', 'bp-block-member'); ?>
				<br/>
				<?php _e('If not selected, hiding by Member Type will not be available.', 'bp-block-member'); ?>

				<br/><br/>

				<form action="<?php echo $this->block_admin_form_action; ?>" name="block-visibility-form" id="block-visibility-form"  method="post" class="standard-form">

				<?php wp_nonce_field('block-visibility-action', 'block-visibility-field'); ?>

				<?php $option = get_site_option( 'bp_block_visibility' ); ?>

				<input type="checkbox" id="pp-visibility" name="pp-visibility" value="1" <?php checked( $option, 1 ); ?> /> <?php _e("Yes, I want to hide blocked members and their content. And allow hiding by Member Type.", "bp-block-member"); ?>

				<br/><br/>

				<input type="hidden" name="block-visibility" value="1"/>
				<input type="submit" name="submit" class="button button-primary" value="<?php _e('Save Checkbox', 'bp-block-member'); ?>"/>
				</form>
			</div>
		</div>
		<br/>
	<?php
	}

	//  update visibility checkbox
	private function _block_visibility_update() {

		if ( isset( $_POST['block-visibility'] ) ) {

			if ( !wp_verify_nonce($_POST['block-visibility-field'],'block-visibility-action') )
				die('Security check');

			if ( !is_super_admin() )
				return;

			delete_site_option( 'bp_block_visibility' );

			if ( ! empty( $_POST['pp-visibility'] ) )
				update_site_option( 'bp_block_visibility', '1' );


			$this->block_visibility_message .=
					"<div class='updated below-h2'>" .  __('Visibility has been updated.', 'bp-block-member') . "</div>";

		}
	}

	private function _block_prompt() {

		if ( !is_super_admin() )
			return;
		?>

		<script type="text/javascript">
		jQuery(function() {
			jQuery('#prompt_display').click(function() {
				jQuery('#prompt_show').toggle();
				return false;
			});
		});
		</script>

		<div class='wrap'>

			<h3><a href="#" id="prompt_display"><?php _e('Show Prompt on Block', 'bp-block-member'); ?></a></h3>

			<?php echo $this->block_prompt_message; ?>

			<div id="prompt_show" name="prompt_show" style="display: none;">

				<?php _e('If selected, an "Are you sure?" popup will appear when a member tries to block somebody.', 'bp-block-member'); ?>

				<br/><br/>

				<form action="<?php echo $this->block_admin_form_action; ?>" name="block-prompt-form" id="block-prompt-form"  method="post" class="standard-form">

				<?php wp_nonce_field('block-prompt-action', 'block-prompt-field'); ?>

				<?php $option = get_site_option( 'bp_block_prompt' ); ?>

				<input type="checkbox" id="pp-prompt" name="pp-prompt" value="1" <?php checked( $option, 1 ); ?> /> <?php _e("Yes, I want member to confirm that they want to block another member.", "bp-block-member"); ?>

				<br/><br/>

				<input type="hidden" name="block-prompt" value="1"/>
				<input type="submit" name="submit" class="button button-primary" value="<?php _e('Save Checkbox', 'bp-block-member'); ?>"/>
				</form>
			</div>
		</div>
		<br/>
	<?php
	}

	//  update prompt checkbox
	private function _block_prompt_update() {

		if ( isset( $_POST['block-prompt'] ) ) {

			if ( !wp_verify_nonce($_POST['block-prompt-field'],'block-prompt-action') )
				die('Security check');

			if ( !is_super_admin() )
				return;

			delete_site_option( 'bp_block_prompt' );

			if ( ! empty( $_POST['pp-prompt'] ) )
				update_site_option( 'bp_block_prompt', '1' );


			$this->block_prompt_message .=
					"<div class='updated below-h2'>" .  __('Prompt has been updated.', 'bp-block-member') . "</div>";

		}
	}

	// display role access form if administrator
	private function _block_roles(){
		global $wp_roles;

		if ( !is_super_admin() )
			return;

		$all_roles = $wp_roles->roles;
		$current_allowed_roles = explode(",", get_site_option( 'bp_block_roles' ));
		?>

		<script type="text/javascript">
		jQuery(function() {
			jQuery('#assign_user_display').click(function() {
				jQuery('#assign_user_show').toggle();
				return false;
			});
		});
		</script>

		<div class='wrap'>

			<h3><a href="#" id="assign_user_display"><?php _e('Assign User Roles', 'bp-block-member'); ?></a></h3>

			<?php echo $this->block_assign_message; ?>

			<div id="assign_user_show" name="assign_user_show" style="display: none;">

				<?php _e("1. Assigned roles can access the 'Blocked Members' list and 'Block a Member' below.", "bp-block-member"); ?><br/>
				<?php _e('2. Their profile page will not include a Block button.', 'bp-block-member'); ?><br/><br/>

				<form action="<?php echo $this->block_admin_form_action; ?>" name="block-member-access-form" id="block-member-access-form"  method="post" class="standard-form">

				<?php
				wp_nonce_field('allowed-block-roles-action', 'allowed-block-roles-field');
				$role_checkbox_str = "";
				?>

				<ul id="pp-user_roles">

				<?php foreach(  $all_roles as $key => $value ){

					if ( in_array($key, $current_allowed_roles) ) $checked = ' checked="checked"';
					else $checked = '';

					if ( $key == 'administrator' || $key == 'super_admin' ) :?>

						<li><label><input type="checkbox" id="admin-preset-role" name="admin-preset" checked="checked" disabled /> <?php echo ucfirst($key); ?></label></li>

				<?php else: ?>

						<li><label for="pp-allow-roles-<?php echo $key ?>"><input id="pp-allow-roles-<?php echo $key ?>" type="checkbox" name="allow-roles[]" value="<?php echo $key ?>" <?php echo  $checked ; ?> /> <?php echo ucfirst($key); ?></label></li>

				<?php endif;

				}?>

				</ul>
				<input type="hidden" name="block-role-access" value="1"/>
				<input type="submit" name="submit" class="button button-primary" value="<?php _e('Save Changes', 'bp-block-member'); ?>"/>
				</form>
			</div>
		</div>
		<br/>
	<?php
	}


	//  update allowed roles
	private function _block_roles_update() {
		global $wp_roles;

		if ( isset( $_POST['block-role-access'] ) ) {

			if ( !wp_verify_nonce($_POST['allowed-block-roles-field'],'allowed-block-roles-action') )
				die('Security check');

			if ( !is_super_admin() )
				return;

			$all_roles = $wp_roles->roles;

			foreach(  $all_roles as $key => $value ){
				if ( 'administrator' != $key ) {
					$role = get_role( $key );
					$role->remove_cap( 'unblock_member' );
				}
			}

			if ( isset( $_POST['allow-roles'] ) ) {
				foreach( $_POST['allow-roles'] as $key => $value ){

					if ( array_key_exists($value, $all_roles ) ) {
						$new_roles[] = $value;
						$role = get_role( $value );
						$role->add_cap( 'unblock_member' );
					}
				}
				$new_roles = 'administrator,super_admin,' . implode(",", $new_roles);	//echo $new_roles;
			}
			else
				$new_roles = 'administrator,super_admin';

			$updated = update_site_option( 'bp_block_roles', $new_roles );

			if ( $updated )
				$this->block_assign_message .=
						"<div class='updated below-h2'>" .  __('User Roles have been updated.', 'bp-block-member') . "</div>";
			else
				$this->block_assign_message .=
						"<div class='updated below-h2' style='color: red'>" .  __('No changes were detected re User Roles.', 'bp-block-member') . "</div>";

		}
	}

	// show the Block a Member form
	private function _block_create_form() {
	?>

		<script type="text/javascript">
		jQuery(function() {
			jQuery('#block_user_display').click(function() {
				jQuery('#block_user_show').toggle();
				return false;
			});
		});
		</script>

		<div class='wrap'>

			<h3><a href="#" id="block_user_display"><?php _e('Block a Member', 'bp-block-member'); ?></a></h3>

			<?php echo $this->block_create_message; ?>

			<div id="block_user_show" name="block_user_show" style="display: none;">

				<?php _e('You will need the user login names for both members.', 'bp-block-member'); ?><br/><br/>

				<?php _e('Enter the user login names:', 'bp-block-member'); ?><br/>
				<form action="<?php echo $this->block_admin_form_action; ?>" name="block-member-create-form" id="block-member-create-form"  method="post" class="standard-form">

				<?php wp_nonce_field('create-block-action', 'create-block-field'); ?>
				<input type="text" name="member" maxlength="25" value="<?php if ( isset( $_POST['member'] ) ) echo $_POST['member']; ?>" />	&nbsp; <em><?php _e('wants to block', 'bp-block-member'); ?></em> &nbsp; <input type="text" name="target" maxlength="25" value="<?php if ( isset( $_POST['target'] ) ) echo $_POST['target']; ?>" />
				<br/><br/>&nbsp;&nbsp;
				<input type="hidden" name="block-member-create" value="1"/>
				<input type="submit" name="submit" class="button button-primary" value="<?php _e('Create Member Block', 'bp-block-member'); ?>  "/>
				</form>
			</div>
		</div>
		<br/>
	<?php
	}

	// create a block between members if submitted
	private function _block_create() {
		global $wpdb;

		if ( isset( $_POST['block-member-create'] ) ) {

			if ( !wp_verify_nonce($_POST['create-block-field'],'create-block-action') )
				die('Security check');

			if ( !current_user_can( 'unblock_member' ) )
				return false;

			$member_id = $target_id = NULL;

			if ( isset( $_POST['member'] ) && !empty( $_POST['member'] ) ) {
				$member = $_POST['member'];
				$member_id = $wpdb->get_var( $wpdb->prepare(
					"SELECT ID FROM {$wpdb->base_prefix}users WHERE user_login = %s", $member
				) );
				if ( NULL == $member_id )
					$this->block_create_message .=
						"<div class='updated below-h2' style='color: red'>" .
						__('Invalid user login name in the first box.', 'bp-block-member') .
						"</div>";
			}
			else
				$this->block_create_message .=
					"<div class='updated below-h2' style='color: red'>" .
					__('Please enter a user login name in the first box.', 'bp-block-member') .
					"</div>";


			if ( isset( $_POST['target'] ) && !empty( $_POST['target'] ) ) {
				$target = $_POST['target'];
				$target_id = $wpdb->get_var( $wpdb->prepare(
					"SELECT ID FROM {$wpdb->base_prefix}users WHERE user_login = %s", $target
				) );
				if ( NULL == $target_id )
					$this->block_create_message .=
						"<div class='updated below-h2' style='color: red'>" .
						__('Invalid user login name in the second box.', 'bp-block-member') .
						"</div>";
			}
			else
				$this->block_create_message .=
					"<div class='updated below-h2' style='color: red'>" .
					__('Please enter a user login name in the second box.', 'bp-block-member') .
					"</div>";

			if ( ( NULL != $member_id && NULL != $target_id) && ( $member_id != $target_id ) ) {

				//make sure block doesn't already exist.
				$block_id = $wpdb->get_var( $wpdb->prepare(
					"SELECT id FROM {$wpdb->base_prefix}bp_block_member WHERE user_id = %d AND target_id = %d",
					$member_id, $target_id
				) );

				if ( NULL == $block_id ) {
					$new_block = $wpdb->query(  $wpdb->prepare(
						"INSERT INTO {$wpdb->base_prefix}bp_block_member (user_id, target_id) VALUES (%d, %d)",
						$member_id, $target_id
					) );

					if ( !$new_block )
						$this->block_create_message .=
							"<div class='updated below-h2' style='color: red'>" .
							__('There was a database error.', 'bp-block-member') .
							"</div>";
					else
						$this->block_create_message .=
							"<div class='updated below-h2' style='color: green'>{$member} " .
							__('is now blocking', 'bp-block-member') .
							" {$target}.</div>";

				}
				else
					$this->block_create_message .=
						"<div class='updated below-h2' style='color: red'>{$member} " .
						__('is already blocking', 'bp-block-member') .
						" {$target}.</div>";

			}
			else {
				if ( $_POST['member'] == $_POST['target']  && !empty( $_POST['member'] ) )
					$this->block_create_message .=
						"<div class='updated below-h2' style='color: red'>{$member} " .
						__('cannot block themself.', 'bp-block-member') .
						"</div>";
			}
		}
	}

} // end of BP_Block_Member class
