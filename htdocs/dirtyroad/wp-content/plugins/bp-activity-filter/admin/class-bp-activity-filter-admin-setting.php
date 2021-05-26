<?php
/**
 * Defining class if not exist for admin setting
 */
if ( ! class_exists( 'WbCom_BP_Activity_Filter_Admin_Setting' ) ) {



	class WbCom_BP_Activity_Filter_Admin_Setting {

		/**
		 * Constructor
		 */
		public function __construct() {

			/**
			 * You need to hook bp_register_admin_settings to register your settings
			 */
			add_action( 'admin_menu', array( &$this, 'bp_activity_filter_admin_menu' ), 100 );
			add_action( 'network_admin_menu', array( &$this, 'bp_activity_filter_admin_menu' ), 100 );

			add_action( 'wp_ajax_bp_activity_filter_save_display_settings', array( $this, 'bp_activity_filter_save_display_settings' ) );

			add_action( 'wp_ajax_nopriv_bp_activity_filter_save_display_settings', array( $this, 'bp_activity_filter_save_display_settings' ) );

			add_action( 'wp_ajax_bp_activity_filter_save_hide_settings', array( $this, 'bp_activity_filter_save_hide_settings' ) );

			add_action( 'wp_ajax_nopriv_bp_activity_filter_save_hide_settings', array( $this, 'bp_activity_filter_save_hide_settings' ) );

			add_action( 'wp_ajax_bp_activity_filter_save_cpt_settings', array( $this, 'bp_activity_filter_save_cpt_settings' ) );

			add_action( 'wp_ajax_nopriv_bp_activity_filter_save_cpt_settings', array( $this, 'bp_activity_filter_save_cpt_settings' ) );
		}

		/**
		 * BP Share activity filter
		 *
		 * @access public
		 * @since    1.0.0
		 */
		public function bp_activity_filter_admin_menu() {
			if ( is_network_admin() ) {
				$admin_url = 'network/admin.php?page=bp_activity_filter_settings';
			} else {
				$admin_url = 'admin.php?page=bp_activity_filter_settings';
			}

			if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
				add_menu_page( esc_html__( 'WB Plugins', 'bp-activity-filter' ), esc_html__( 'WB Plugins', 'bp-activity-filter' ), 'manage_options', 'wbcomplugins', array( $this, 'bp_activity_filter_section_settings' ), 'dashicons-lightbulb', 59 );
				add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'bp-activity-filter' ), esc_html__( 'General', 'bp-activity-filter' ), 'manage_options', 'wbcomplugins' );
			}
			add_submenu_page( 'wbcomplugins', esc_html__( 'BP Activity Filter', 'bp-activity-filter' ), esc_html__( 'BP Activity Filter', 'bp-activity-filter' ), 'manage_options', 'bp_activity_filter_settings', array( $this, 'bp_activity_filter_section_settings' ) );
		}

		/**
		 * Settings page content
		 *
		 * @access public
		 * @since    1.0.0
		 */
		public function bp_activity_filter_section_settings() {
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'bpaf_display_activity';
			?>
			<div id="wpbody-content" class="bpaf-setting-page" aria-label="Main content" tabindex="0">

				<div class="wrap">

					<div class="bpaf-header">
						<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
						<h1 class="wbcom-plugin-heading">
							<?php esc_html_e( 'BuddyPress Activity Filter Settings', 'bp-activity-filter' ); ?>
						</h1>
					</div>

					<div id="bpaf_setting_error_settings_updated" class="updated settings-error notice is-dismissible">

						<p><strong><?php _e( 'Settings saved.', 'bp-activity-filter' ); ?></strong></p>

						<button type="button" class="notice-dismiss">

							<span class="screen-reader-text"><?php _e( 'Dismiss this notice.', 'bp-activity-filter' ); ?></span>

						</button>

					</div>
					<div class="wbcom-admin-settings-page">
						<?php $this->bpaf_plugin_settings_tabs( $tab ); ?>
					</div>
					<?php
		}

				/**
				 * Get all labels.
				 *
				 * @access public
				 * @since    1.0.0
				 */
		public function bpaf_get_labels() {
			/* Argument to pass in callback */
			$filter_actions = buddypress()->activity->actions;
			$actions        = array();
			foreach ( get_object_vars( $filter_actions ) as $property => $value ) {
				$actions[] = $property;
			}
			$labels = array();
			foreach ( $actions as $key => $value ) {
				foreach ( get_object_vars( $filter_actions->$value ) as $prop => $val ) {
					if ( ! empty( $val['label'] ) ) {
						$labels [ $val['key'] ] = $val ['label'];
					} else {
						$labels [ $val['key'] ] = $val ['value'];
					}
				}
			}

			// On member pages, default to 'member', unless this is a user's Groups activity.

			$context = '';
			if ( bp_is_user() ) {
				if ( bp_is_active( 'groups' ) && bp_is_current_action( bp_get_groups_slug() ) ) {
					$context = 'member_groups';
				} else {
					$context = 'member';
				}

				// On individual group pages, default to 'group'.
			} elseif ( bp_is_active( 'groups' ) && bp_is_group() ) {
				$context = 'group';
				// 'activity' everywhere else.
			} else {
				$context = 'activity';
			}

			$default_filters = array();
			// Walk through the registered actions, and prepare an the select box options.

			foreach ( bp_activity_get_actions() as $actions ) {
				foreach ( $actions as $action ) {
					if ( ! in_array( $context, (array) $action['context'] ) ) {
						continue;
					}

					// Friends activity collapses two filters into one.

					if ( in_array( $action['key'], array( 'friendship_accepted', 'friendship_created' ) ) ) {
						$action['key'] = 'friendship_accepted,friendship_created';
					}
					$default_filters[ $action['key'] ] = $action['label'];
				}
			}

			foreach ( $default_filters as $key => $value ) {
				if ( ! array_key_exists( $key, $labels ) ) {
					$labels[ $key ] = $value;
				}
			}

			$labels = array_reverse( array_unique( array_reverse( $labels ) ) );
			$labels = array_reverse( $labels );
			return $labels;
		}

				/**
				 * Display tabs
				 *
				 * @access public
				 * @since    1.0.0
				 */
		public function bpaf_plugin_settings_tabs( $current ) {
			$bpaf_tabs = array(
				'bpaf_display_activity' => esc_html__( 'Default Filter', 'bp-activity-filter' ),
				'bpaf_hide_activity'    => esc_html__( 'Remove Activity', 'bp-activity-filter' ),
				'bpaf_cpt_activity'     => esc_html__( 'CPT Activites', 'bp-activity-filter' ),
			);

			$tab_html = '<div class="wbcom-tabs-section"><h2 class="nav-tab-wrapper">';
			foreach ( $bpaf_tabs as $bpaf_tab => $bpaf_name ) {
				$class     = ( $bpaf_tab == $current ) ? 'nav-tab-active' : '';
				$tab_html .= '<a class="nav-tab ' . $class . '" href="admin.php?page=bp_activity_filter_settings&tab=' . $bpaf_tab . '">' . $bpaf_name . '</a>';
			}

			$tab_html .= '</h2></div>';
			echo $tab_html;
			$this->bpaf_include_admin_setting_tabs( $current );
		}

				/**
				 * Display content according tabs
				 *
				 * @access public
				 * @since    1.0.0
				 */
		function bpaf_include_admin_setting_tabs( $bpaf_tab ) {
			$bpaf_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : $bpaf_tab;

			switch ( $bpaf_tab ) {
				case 'bpaf_display_activity':
					$this->bpaf_display_activity_section();
					break;
				case 'bpaf_hide_activity':
					$this->bpaf_hide_activity_section();
					break;
				case 'bpaf_cpt_activity':
					$this->bpaf_cpt_activity_section();
					break;
				default:
					$this->bpaf_display_activity_section();
					break;
			}
		}

				/**
				 * Display content of Display Activity tab section
				 *
				 * @access public
				 * @since    1.0.0
				 */
		public function bpaf_display_activity_section() {

			global $bp;

			$defult_activity_stream = bp_get_option( 'bp-default-filter-name' );

			$hidden_activity_stream = bp_get_option( 'bp-hidden-filters-name' );

			$actions = bp_activity_get_actions_for_context( 'activity' );
			$labels  = array();
			foreach ( $actions as $action ) {
				// Friends activity collapses two filters into one.
				if ( in_array( $action['key'], array( 'friendship_accepted', 'friendship_created' ) ) ) {
					$action['key'] = 'friendship_accepted,friendship_created';
				}

				if ( ! array_key_exists( $action['key'], $labels ) ) {
					$labels[ $action['key'] ] = $action['label'];
				}
			}
			?>
					<div class="wbcom-tab-content">
						<form method="post" novalidate="novalidate" id="bp_activity_filter_display_setting_form" >
							<h2><?php echo __( 'Apply Default Filter on Activity Page', 'bp-activity-filter' ); ?></h2>
							<table class="filter-table form-table" >
							<?php
							/* if you use bp_get_option(), then you are sure to get the option for the blog BuddyPress is activated on */
							$bp_default_activity_value = bp_get_option( 'bp-default-filter-name' );
							$bp_hidden_filters_value   = bp_get_option( 'bp-hidden-filters-name' );
							if ( is_array( $bp_hidden_filters_value ) && in_array( $bp_default_activity_value, $bp_hidden_filters_value ) ) {
								bp_update_option( 'bp-default-filter-name', '-1' );
							}
							$bp_default_activity_value = bp_get_option( 'bp-default-filter-name' );
							if ( empty( $bp_default_activity_value ) ) {
								$bp_default_activity_value = -1;
							}
							?>
								<td>
									<table>
										<tr>
											<td class="filter-option">
												<label>
												<input id="bp-activity-filter-everything-radio" name="bp-default-filter-name" type="radio" value="-1"  <?php echo ( $bp_default_activity_value == -1 ) ? 'checked=checked' : ' '; ?>/>
												<?php _e( 'Everything', 'bp-activity-filter' ); ?></label>
											</td>
										</tr>
								<?php
								foreach ( $labels as $key => $value ) :
									if ( ! empty( $value ) ) {
										$hide_active = '';
										if ( ! empty( $bp_hidden_filters_value ) ) {
											if ( in_array( $key, $bp_hidden_filters_value ) ) {
												$hide_active = "disabled = 'disabled'";
											}
										}
										?>
												<tr>
													<td class="filter-option">
														<label for="<?php echo $key . '_radio'; ?>">
														<input id="<?php echo $key . '_radio'; ?>" name="bp-default-filter-name" type="radio" value="<?php echo $key; ?>"
												<?php
												echo ( $bp_default_activity_value == $key ) ? 'checked=checked ' : ' ';
												echo $hide_active;
												?>
															   />
														<?php _e( $value, 'bp-activity-filter' ); ?></label>
													</td>
												</tr>
												<?php
									}
								endforeach;
								?>
									</table>
								</td>
							</table>
							<br /><br />
							<h2><?php echo __( 'Apply Default Filter on Profile Activity Page', 'bp-activity-filter' ); ?></h2>
							
							<table class="filter-table form-table" >
							<?php
							/* if you use bp_get_option(), then you are sure to get the option for the blog BuddyPress is activated on */
							$bp_default_activity_value = bp_get_option( 'bp-default-profile-filter-name' );
							$bp_hidden_filters_value   = bp_get_option( 'bp-hidden-profile-filters-name' );
							if ( is_array( $bp_hidden_filters_value ) && in_array( $bp_default_activity_value, $bp_hidden_filters_value ) ) {
								//bp_update_option( 'bp-default-filter-name', '-1' );
							}
							$bp_default_activity_value = bp_get_option( 'bp-default-profile-filter-name' );
							if ( empty( $bp_default_activity_value ) ) {
								$bp_default_activity_value = -1;
							}
							?>
								<td>
									<table>
										<tr>
											<td class="filter-option">
												<label>
												<input id="bp-activity-filter-everything-radio" name="bp-default-profile-filter-name" type="radio" value="-1"  <?php echo ( $bp_default_activity_value == -1 ) ? 'checked=checked' : ' '; ?>/>
												<?php _e( 'Everything', 'bp-activity-filter' ); ?></label>
											</td>
										</tr>
								<?php
								unset($labels['new_member']);
								unset($labels['updated_profile']);
								foreach ( $labels as $key => $value ) :
									if ( ! empty( $value ) ) {
										$hide_active = '';
										if ( ! empty( $bp_hidden_filters_value ) ) {
											if ( in_array( $key, $bp_hidden_filters_value ) ) {
												$hide_active = "disabled = 'disabled'";
											}
										}
										?>
												<tr>
													<td class="filter-option">
													<label for="<?php echo $key . '_profile_radio'; ?>">
														<input id="<?php echo $key . '_profile_radio'; ?>" name="bp-default-profile-filter-name" type="radio" value="<?php echo $key; ?>"
												<?php
												echo ( $bp_default_activity_value == $key ) ? 'checked=checked ' : ' ';
												echo $hide_active;
												?>
															   />
														<?php _e( $value, 'bp-activity-filter' ); ?></label>
													</td>
												</tr>
												<?php
									}
								endforeach;
								?>
									</table>
								</td>
							</table>
							
							<div class="submit">
								<a id="bp_activity_filter_display_setting_form_submit" class="button-primary"><?php _e( 'Save Settings', 'bp-activity-filter' ); ?></a>
								<div class="spinner"></div>
							</div>
						</form>
					</div>
					<?php
		}

				/**
				 * Display content of Hide Activity tab section
				 *
				 * @access public
				 * @since    1.0.0
				 */
		public function bpaf_hide_activity_section() {

			global $bp;

			$skip_activity = array(
				'0' => 'updated_profile',
				'1' => 'activity_update',
				'2' => 'new_blog_post',
				'3' => 'new_blog_comment',
				'4' => 'group_details_updated',
			);

			$actions = bp_activity_get_actions_for_context( 'activity' );
			$labels  = array();
			foreach ( $actions as $action ) {
				// Friends activity collapses two filters into one.
				if ( in_array( $action['key'], array( 'friendship_accepted', 'friendship_created' ) ) ) {
					$action['key'] = 'friendship_accepted,friendship_created';
				}

				if ( ! array_key_exists( $action['key'], $labels ) ) {
					$labels[ $action['key'] ] = $action['label'];
				}
			}
			/* if you use bp_get_option(), then you are sure to get the option for the blog BuddyPress is activated on */

			$bp_default_activity_value = bp_get_option( 'bp-default-filter-name' );

			$bp_hidden_filters_value = bp_get_option( 'bp-hidden-filters-name' );

			?>
					<div class="wbcom-tab-content">
						<form method="post" novalidate="novalidate" id="bp_activity_filter_hide_setting_form" >
							<h2><?php echo __( 'Remove Activity', 'bp-activity-filter' ); ?></h2>
							<table class="filter-table form-table" >
								<tr>
									<td>
										<table>
																											<!-- <tr>
																												<td class="filter-option">
																													<input id="bp-activity-filter-everything-checkbox" name="bp-hidden-filters-name[]" type="checkbox" value="-1"  disabled="disabled" />
																													<label for="bp-hidden-filters-name"><?php // _e( 'Everything', 'bp-activity-filter' ); ?></label>
																												</td>
																											</tr> -->
									<?php
									foreach ( $labels as $key => $value ) :
										if ( ! empty( $value ) ) {
											$default_active = '';
											// echo $bp_default_activity_value;
											if ( $bp_default_activity_value == $key ) {
												$default_active = "disabled = 'disabled'";
											}
											?>
													<tr>
														<td class="filter-option">
															<input id="<?php echo $key . '-checkbox'; ?>" name="bp-hidden-filters-name[]" type="checkbox" value="<?php echo $key; ?>"
													<?php
													echo ( ( ! empty( $bp_hidden_filters_value ) && is_array( $bp_hidden_filters_value ) ) && in_array( $key, $bp_hidden_filters_value ) ) ? 'checked' : ' ';
													echo $default_active;
													?>
																   />
															<label for="bp-hidden-filters-name"><?php _e( $value, 'bp-activity-filter' ); ?></label>
														</td>
													</tr>
													<?php
										}
									endforeach;
									?>
										</table>
									</td>
								</tr>
							</table>
							<p class="description"><?php echo __( 'Any checked activity type will not be recorded as a new activity. ', 'bp-activity-filter' ); ?></p>
							<div class="submit">
								<a id="bp_activity_filter_hide_setting_form_submit" class="button-primary"><?php _e( 'Save Settings', 'bp-activity-filter' ); ?></a>
								<div class="spinner"></div>
							</div>
						</form>
					</div>
					<?php
		}

				/**
				 * Display content of Display Activity tab section
				 *
				 * @access public
				 * @since    1.0.0
				 */
		public function bpaf_cpt_activity_section() {

			$cpt_filter_val = bp_get_option( 'bp-cpt-filters-settings' );
			?>
					<div class="wbcom-tab-content">
						<form method="post" novalidate="novalidate" id="bp_activity_filter_cpt_setting_form" >
							<h2><?php echo esc_html__( 'Enable Post Type Activites', 'bp-activity-filter' ); ?></h2>

							<table class="filter-table form-table" >
								<thead>
								<th class="th-title"><?php echo esc_html__( 'Post Type', 'bp-activity-filter' ); ?></th>
								<th class="th-title"><?php echo esc_html__( 'Enable/Disable', 'bp-activity-filter' ); ?></th>
								<th class="th-title"><?php echo esc_html__( 'Name for activities', 'bp-activity-filter' ); ?></th>
								</thead>
						<?php
						$args = array(
							'public'              => true,
							'_builtin'            => false,
							'exclude_from_search' => false,
						);

						$output   = 'names'; // names or objects, note names is the default
						$operator = 'and'; // 'and' or 'or'

						$post_types = get_post_types( $args, $output, $operator );

						echo '<tbody>';

						if ( ! empty( $post_types ) && is_array( $post_types ) ) :

							foreach ( $post_types as $post_type ) {

								$post_details = get_post_type_object( $post_type );

								if ( ! empty( $cpt_filter_val ) ) {
									$saved_settings = ( isset( $cpt_filter_val['bpaf_admin_settings'][ $post_type ] ) ) ? $cpt_filter_val['bpaf_admin_settings'][ $post_type ] : array();
								}

								if ( ! empty( $saved_settings ) && array_key_exists( 'display_type', $saved_settings ) ) {
									$display_type = $saved_settings['display_type'];
								} else {
									$display_type = '';
								}

								if ( ! empty( $saved_settings ) && array_key_exists( 'group', $saved_settings ) ) {

									$group = $saved_settings['group'];
								} else {

									$group = '';
								}

								if ( isset( $saved_settings['new_label'] ) ) {
									$value = $saved_settings['new_label'];
								} else {
									$value = '';
								}
								?>

									<tr>

										<td scope="row" data-title="Post Type"><label class="filter-description" ><?php echo $post_details->label; ?></label></td>
										<td class="filter-option" data-title="Enable/Disable">
											<input id="<?php echo $post_type . '_radio'; ?>" name="<?php echo "bpaf_admin_settings[$post_type][display_type]"; ?>" type="checkbox" value="enable" <?php checked( $display_type, 'enable' ); ?> />
										</td>
										<td class="filter-option" data-title="Upload Lable">
											<input id="<?php echo $post_type . '_text'; ?>" name='<?php echo "bpaf_admin_settings[$post_type][new_label]"; ?>' type="text" value="<?php echo $value; ?>" />
										</td>
									</tr>

								<?php
							}

						else :
							echo '<div class="notice">';
							echo '<p class="description">' . __( 'Sorry, it seems you do not have any custom post type available to allow in the activity stream.', 'bp-activity-filter') . '</p>';
							echo '</div>';

						endif;

						?>
								</tbody>
							</table>

							<div class="submit">
								<a id="bp_activity_filter_cpt_setting_form_submit" class="button-primary"><?php _e( 'Save Settings', 'bp-activity-filter' ); ?></a>
								<div class="spinner"></div>
							</div>

						</form>
					</div>
					<?php
					exit;
		}

				/**
				 * Save content of Display Activity tab section
				 *
				 * @access public
				 * @since    1.0.0
				 */
		public function bp_activity_filter_save_display_settings() {

			parse_str( $_POST['form_data'], $setting_form_data );

			$form_details = filter_var_array( $setting_form_data, FILTER_SANITIZE_STRING );

			$bp_default_filter_name = $form_details['bp-default-filter-name'];
			
			$bp_default_profile_filter_name = $form_details['bp-default-profile-filter-name'];

			bp_update_option( 'bp-default-filter-name', $bp_default_filter_name );
			
			bp_update_option( 'bp-default-profile-filter-name', $bp_default_profile_filter_name );

			exit;
		}

				/**
				 * Save content of Hide Activity tab section
				 *
				 * @access public
				 * @since    1.0.0
				 */
		public function bp_activity_filter_save_hide_settings() {

			parse_str( $_POST['form_data'], $setting_form_data );

			$form_details = filter_var_array( $setting_form_data, FILTER_SANITIZE_STRING );

			$bp_hidden_filter_name = $form_details['bp-hidden-filters-name'];

			bp_update_option( 'bp-hidden-filters-name', $bp_hidden_filter_name );

			exit;
		}

				/**
				 * Save content of Custom post type Activity tab section
				 *
				 * @access public
				 * @since    1.0.0
				 */
		public function bp_activity_filter_save_cpt_settings() {

			parse_str( $_POST['form_data'], $cpt_settings_data );

			$cpt_settings_details = filter_var_array( $cpt_settings_data, FILTER_SANITIZE_STRING );
			bp_update_option( 'bp-cpt-filters-settings', $cpt_settings_details );

			exit;
		}

	}

}



if ( class_exists( 'WbCom_BP_Activity_Filter_Admin_Setting' ) ) {
	$admin_setting_obj = new WbCom_BP_Activity_Filter_Admin_Setting();
}
