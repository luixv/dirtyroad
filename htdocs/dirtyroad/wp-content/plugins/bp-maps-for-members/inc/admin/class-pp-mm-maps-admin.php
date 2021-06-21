<?php

class PP_Member_Maps_Admin {

	private $settings_message = '';

	static $instance = false;

	public static function get_instance() {
		if( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct() {
		$this->hooks();
	}

	protected function hooks() {

		add_action( 'admin_enqueue_scripts',  array( $this, 'maps_scripts' ), 1000 );

		add_action( 'admin_init', array( $this, 'pp_mm_save_license') );
		add_action( 'admin_init', array( $this, 'pp_mm_activate_license') );
		add_action( 'admin_init', array( $this, 'pp_mm_deactivate_license') );

		if ( is_multisite() ) {

			if ( ! function_exists( "is_plugin_active_for_network" ) ) {
				require_once( ABSPATH . "/wp-admin/includes/plugin.php" );
			}

		}

		if ( is_multisite() && is_plugin_active_for_network( "bp-maps-for-members/loader.php" ) ) {
			add_action("network_admin_menu", array( $this, "multisite_admin_menu" ) );
		} else {
			add_action( "admin_menu", array( $this, "admin_menu" ) );
		}

	}

	function admin_menu() {
		add_options_page(  __( "BP Maps for Members", "bp-member-maps"), __( "BP Maps - Members", "bp-member-maps" ), "manage_options", "bp-maps-for-members", array( $this, "settings_admin_screen" ) );
	}

	function multisite_admin_menu() {
		add_submenu_page( "settings.php", __( "BP Maps for Members", "bp-member-maps"), __( "BP Maps - Members", "bp-member-maps" ), "manage_options", "bp-maps-for-members", array( $this, "settings_admin_screen" ) );
	}

	// add scripts
	function maps_scripts( $hook ) {

        if ( 'settings_page_bp-maps-for-members' != $hook  ) {
           return;
		}

		$gapikey = get_site_option( 'pp_gapikey' );

		if ( $gapikey != false ) {

			wp_register_script( 'google-places-api', '//maps.googleapis.com/maps/api/js?key=' . $gapikey . '&libraries=places' );
			wp_print_scripts( 'google-places-api' );

			wp_enqueue_script('pp-mm-script', plugins_url( 'js/pp-mm.js', dirname( __FILE__ ) ), array('jquery'), '6.4.0' );

		}
	}


	function update(){

		if ( ! empty( $_POST["bp-member-maps-save"] ) ) {

			if ( ! wp_verify_nonce( $_POST["_wpnonce"],"bp-member-maps" ) ) {
				die(__("Security check failed", "bp-member-maps"));
			}

			if ( $_POST["gapikey"] != ''  ) {
				update_site_option( "pp_gapikey", $_POST["gapikey"] );
			}

			$valid_map_types = array( 'roadmap', 'satellite', 'terrain', 'hybrid' );
			$valid_map_zooms = array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18' );

			// update settings for single member map

			$settings_single = array();

			$map_type = $_POST["map-type"];
			if ( $map_type && in_array( $map_type, $valid_map_types ) ) {
				$settings_single["map_type"] = $map_type;
			} else {
				$settings_single["map_type"] = 'roadmap';
			}

			$map_zoom_level = $_POST["map-zoom-level"];
			if ( $map_zoom_level && in_array( $map_zoom_level, $valid_map_zooms ) ) {
				$settings_single["map_zoom_level"] = $map_zoom_level;
			} else {
				$settings_single["map_zoom_level"] = 10;
			}

			$map_height = sanitize_text_field( $_POST["map-height"] );
			if ( $map_height && ( $map_height >= 50 && $map_height <= 640 ) ) {
				$settings_single["map_height"] = $map_height;
			} else {
				$settings_single["map_height"] = 200;
			}

			if ( isset( $_POST["map-location-field"] ) )  {
				$settings_single["map_location_field"] = $_POST["map-location-field"];
			}

			if ( isset( $_POST["map-member-single-tab-skip"] ) )  {
				$settings_single["map_member_single_tab_skip"] = '1';
			}


			update_site_option("bp-member-map-single-settings", $settings_single);


			// update settings for all members map

			$settings_all = array();

			//$map_address = sanitize_text_field( $_POST["pp-mm-address"] );
			//$settings_all["pp_mm_address"] = $map_address;

			//$map_latlng = sanitize_text_field( $_POST["pp-mm-latlng"] );
			//$settings_all["pp_mm_latlng"] = $map_latlng;

			$map_type = $_POST["map-type-all"];
			if ( $map_type && in_array( $map_type, $valid_map_types ) ) {
				$settings_all["map_type_all"] = $map_type;
			} else {
				$settings_all["map_type_all"] = 'roadmap';
			}


			$map_zoom_level = $_POST["map-zoom-level-all"];
			if ( $map_zoom_level && in_array( $map_zoom_level, $valid_map_zooms ) )
				$settings_all["map_zoom_level_all"] = $map_zoom_level;
			else
				$settings_all["map_zoom_level_all"] = 16;


			$map_height = sanitize_text_field( $_POST["map-height-all"] );
			if ( $map_height && ( $map_height >= 50 && $map_height <= 640 ) ) {
				$settings_all["map_height_all"] = $map_height;
			} else {
				$settings_all["map_height_all"] = 500;
			}


			if ( isset( $_POST["map-location-field-all"] ) )  {
				$settings_all["map_location_field_all"] = $_POST["map-location-field-all"];
			}


			$map_limit = sanitize_text_field( $_POST["map-limit-all"] );
			if ( $map_limit && ( $map_limit > 0 ) ) {
				$settings_all["map_limit_all"] = $map_limit;
			} else {
				$settings_all["map_limit_all"] = 0;
			}

			if ( isset( $_POST["map-member-filter-types"] ) ) {
				$settings_all["map_member_filter_types"] = 1;
			}

			if ( isset( $_POST["map-member-filter-distance"] ) ) {
				$settings_all["map_member_filter_distance"] = 1;
			}

			if ( isset( $_POST["map-member-distance-measurement"] ) ) {
				$settings_all["map_member_distance_measurement"] = sanitize_text_field( $_POST["map-member-distance-measurement"] );
			} else {
				$settings_all["map_member_distance_measurement"] = 'miles';
			}

			if ( isset( $_POST["map-member-filter-bps"] ) ) {
				$settings_all["map_member_filter_bps"] = 1;
			}

			if ( isset( $_POST["map-member-filter-keywords"] ) ) {
				$settings_all["map_member_filter_keywords"] = 1;
			}

			if ( isset( $_POST["map-skip-all"] ) ) {
				$settings_all["map_skip_all"] = 1;
			}

			if ( isset( $_POST["map_member_types"] ) ) {
				$settings_all["map_member_types"] = $_POST["map_member_types"];
			}

			update_site_option("bp-member-map-all-settings", $settings_all);


			$this->settings_message = __("Settings Updated", "bp-member-maps");
		}
	}


	function settings_admin_screen() {

		if( !is_super_admin() )
			return;

		$this->update();

		$settings_single = get_site_option( 'bp-member-map-single-settings' );

		//write_log( $settings_single );

		extract($settings_single);

		$settings_all = get_site_option( 'bp-member-map-all-settings' );

		//write_log( $settings_all );

		extract($settings_all);

		if ( ! isset( $map_member_filter_types ) ) {
			$map_member_filter_types = 0;
		}

		if ( ! isset( $map_member_filter_distance ) ) {
			$map_member_filter_distance = 0;
		}

		if ( ! isset( $map_member_distance_measurement ) ) {
			$map_member_distance_measurement = 0;
		}

		if ( ! isset( $map_zoom_level_all ) ) {
			$map_zoom_level_all = 14;
		}

		if ( ! isset( $map_member_filter_bps ) ) {
			$map_member_filter_bps = 0;
		}

		if ( ! isset( $map_member_single_tab_skip ) ) {
			$map_member_single_tab_skip = 0;
		}

		if ( ! isset( $map_member_filter_keywords ) ) {
			$map_member_filter_keywords = 0;
		}

		if ( ! isset( $map_skip_all ) ) {
			$map_skip_all = 0;
		}

		if ( ! isset( $map_member_types ) ) {
			$map_member_types = array();
		}

		$location_fields = $this->get_xprofile_fields_location();

		$gapikey = get_site_option( 'pp_gapikey' );
		if ( ! $gapikey ) {
			$gapikey = '';
		}

		$license 	= get_site_option( 'pp_mm_license_key' );
		$status 	= get_site_option( 'pp_mm_license_status' );

		?>

		<div class="wrap">

			<h2><?php _e('BP Maps for Members License Options'); ?></h2>

			<form method="post" action="">

				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('License Key', 'bp-member-maps'); ?>
							</th>
							<td>
								<input id="pp_mm_license_key" name="pp_mm_license_key" type="text" class="regular-text" placeholder="Paste Your License Key Here" value="<?php esc_attr_e( $license ); ?>" />
								<label class="description" for="pp_mm_license_key"><em><?php _e('Enter your license key', 'bp-member-maps'); ?></em></label>
							</td>
						</tr>

						<!-- <tr><td>response:</td><td><pre><?php //var_dump( get_site_option( 'edd_response') );?></pre></td></tr>-->


						<?php if( false !== $license ) { ?>
							<tr valign="top">
								<th scope="row" valign="top">
									<?php _e('Activate License'); ?>
								</th>
								<td>
									<?php if( $status !== false && $status == 'valid' ) { ?>
										<span style="color:#32cd32;"><?php _e('Your License is Active', 'bp-member-maps' ); ?></span>
										<?php wp_nonce_field( 'pp_mm_lic_nonce', 'pp_mm_lic_nonce' ); ?>
										&nbsp;&nbsp;<input type="submit" class="button-secondary" name="pp_mm_license_deactivate" value="<?php _e('Deactivate License', 'bp-member-maps'); ?>"/>
									<?php } else {
										wp_nonce_field( 'pp_mm_lic_nonce', 'pp_mm_lic_nonce' ); ?>
										<input type="submit" class="button-secondary" name="pp_mm_license_activate" value="<?php _e('Activate License', 'bp-member-maps'); ?>"/>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>

						<tr valign="top">
							<td>
								<?php wp_nonce_field( 'pp_mm_lic_save_nonce', 'pp_mm_lic_save_nonce' ); ?>
								<input class="button-primary" type="submit"  name="bp-member-maps-lic-save" value="<?php _e("Save License Key", "bp-member-maps");?>" />
							</td>
							<td>&nbsp;<em><?php _e("You must Save your Key before you can Activate your License", "bp-member-maps");?></em></td>
						</tr>
					</tbody>
				</table>

			</form>
			<hr>
		</div>

		<div class="wrap">

			<h2><?php _e( "BP Maps for Members Settings", "bp-member-maps"); ?></h2>

			<?php if ( $this->settings_message ) : ?>
				<div class="updated fade" id="message">
					<?php echo $this->settings_message;	?>
				</div>
			<?php endif; ?>

			<form action="" method="post" name="bp-member-maps-settings-form"  class="standard-form">

			<table class="fat-wide" id="pp-mm-settings" cellspacing="10" cellpadding="5">

				<tr><td colspan="2"><h3><?php _e( "Google Maps API Key", "bp-member-maps"); ?></h3></td></tr>

				<tr>
					<td style="vertical-align:top"><?php _e("Your Key", "bp-member-maps");?></td>

					<td>
						<input type="text" size="45" name="gapikey" placeholder="Paste Your Google Maps API Key Here" value="<?php echo $gapikey; ?>" />
						<br><?php _e("A Key is required. If you do not have one, follow these instructions:", "bp-member-maps");?>
						<br><a href="https://www.philopress.com/google-maps-api-key/" target="_blank">Get a Google Maps API Key</a>
					</td>
				</tr>

				<tr>
					<td style="vertical-align:top"><?php _e("Validate Google API Key", "bp-member-maps");?></td>

					<td>
						<input type="text" size="40" id="pp-mm-location" name="pp-mm-location" placeholder="<?php echo __( 'Start typing an address...', 'bp-member-maps' ); ?>" value="" />
						<br><?php _e("If Google displays a list of addresses as you type - your Google Maps API Key is <strong>valid</strong>.", "bp-member-maps");?>
						<br><?php _e("Otherwise there is a <strong>problem</strong> with your key. Open your browser's javascript console for error info supplied by Google.", "bp-member-maps");?>
					</td>
				</tr>

				<tr><td colspan="2"><h3><?php _e( "Member Profile Map", "bp-member-maps"); ?></h3></td></tr>

				<tr>
					<td><?php _e("Skip Location Tab", "bp-member-maps");?></td>

					<td align="left">
						<input type="checkbox" id="map-member-single-tab-skip" name="map-member-single-tab-skip" value="1" <?php checked( $map_member_single_tab_skip, 1 ); ?> />
						<?php _e("Do <strong>NOT</strong> show a <em>Location</em> tab on member profiles", "bp-member-maps");?>
					</td>
				</tr>


				<tr>
					<td><?php _e("Map Type", "bp-member-maps");?></td>

					<td align="left">
						<select name="map-type">
							<option value="roadmap" <?php if($map_type == "roadmap") echo "selected=selected";?>><?php _e("Roadmap","bp-member-maps");?></option>
							<option value="satellite" <?php if($map_type == "satellite") echo "selected=selected";?>><?php _e("Satellite","bp-member-maps");?></option>
							<option value="terrain" <?php if($map_type == "terrain") echo "selected=selected";?>><?php _e("Terrain","bp-member-maps");?></option>
							<option value="hybrid" <?php if($map_type == "hybrid") echo "selected=selected";?>><?php _e("Hybrid","bp-member-maps");?></option>
						</select>
					</td>
				</tr>

				<tr>
					<td><?php _e("Zoom Level", "bp-member-maps");?></td>

					<td>
						<select name="map-zoom-level">
							<?php for ($i=1;$i<=18;$i++):?>
								<option value="<?php echo $i;?>" <?php if($map_zoom_level==$i) echo "selected=selected";?>><?php echo $i;?></option>
							<?php endfor;?>
						</select>
					</td>
				</tr>

				<tr>
					<td><?php _e("Map Width", "bp-member-maps");?></td>

					<td>100%&nbsp; &nbsp;<em><?php _e("Required so that map is responsive", "bp-member-maps");?></em></td>
				</tr>

				<tr>
					<td><?php _e("Map Height", "bp-member-maps");?></td>

					<td>
						<input type="text" size="3" name="map-height" value="<?php echo $map_height; ?>" />px  &nbsp; &nbsp;<em><?php _e("Cannot be less than 50 or greater than 640", "bp-member-maps");?></em>

					</td>
				</tr>

				<tr>
					<td style="vertical-align:top"><?php _e("Location Fields", "bp-member-maps");?></td>

				<?php if ( empty( $location_fields ) ) : ?>

					<td>
						<?php echo _e("There are no Location profile fields, so maps will not be created. Please go to wpadmin > Users > Profile Fields and add a Location field.", "bp-member-maps"); ?>
					</td>
				</tr>

				<?php elseif ( count( $location_fields ) > 0 ) : ?>

					<td>

						<?php _e( "Select Location field for use on Individual Member Maps:", "bp-member-maps"); ?>
						<br>

						<?php

							if ( isset( $map_location_field ) ) {

								//write_log( '$map_location_field: ' . $map_location_field);

								$checked = '';

								foreach ( $location_fields as $lf ) {

									if ( $map_location_field == $lf->id ) {
										$checked = 'checked="checked"';
									}

								?>
									<br><input type="radio" name="map-location-field" value="<?php echo $lf->id; ?>" <?php echo $checked; ?> /> <?php echo $lf->name; ?> <br>

									<?php $checked = ''; ?>

								<?php
								}

							}
							else {
								foreach ( $location_fields as $lf ) {

								?>
									<input type="radio" name="map-location-field" value="<?php echo $lf->id; ?>" /> <?php echo $lf->name; ?> <br>

								<?php
								}
							}
						?>

					</td>
				</tr>

				<?php endif; ?>

				<tr><td colspan="2"><hr></td></tr>



				<tr><td colspan="2"><h3><?php _e( "All Members Map", "bp-member-maps"); ?></h3></td></tr>

				<tr>
					<td><?php _e("Skip Directory Map", "bp-member-maps");?></td>

					<td align="left">
						<input type="checkbox" id="map-skip-all" name="map-skip-all" value="1" <?php checked( $map_skip_all, 1 ); ?> />
						<?php _e("Do <strong>NOT</strong> add a Members Map to the Members Directory.", "bp-member-maps");?>
					</td>
				</tr>

				<tr>
					<td><?php _e("Map Type", "bp-member-maps");?></td>

					<td align="left">
						<select name="map-type-all">
							<option value="roadmap" <?php if($map_type_all == "roadmap") echo "selected=selected";?>><?php _e("Roadmap","bp-member-maps");?></option>
							<option value="satellite" <?php if($map_type_all == "satellite") echo "selected=selected";?>><?php _e("Satellite","bp-member-maps");?></option>
							<option value="terrain" <?php if($map_type_all == "terrain") echo "selected=selected";?>><?php _e("Terrain","bp-member-maps");?></option>
							<option value="hybrid" <?php if($map_type_all == "hybrid") echo "selected=selected";?>><?php _e("Hybrid","bp-member-maps");?></option>
						</select>
					</td>
				</tr>


				<tr>
					<td><?php _e("Max Zoom Level", "bp-member-maps");?></td>

					<td>
						<select name="map-zoom-level-all">
							<?php for ($i=1;$i<=18;$i++):?>
								<option value="<?php echo $i;?>" <?php if( $map_zoom_level_all == $i ) echo "selected=selected";?>><?php echo $i;?></option>
							<?php endfor;?>
						</select>
						&nbsp;<em><?php _e("Recommended setting is 16, but increase it if some members are very close together", "bp-member-maps");?></em>
					</td>
				</tr>


				<tr>
					<td><?php _e("Map Width", "bp-member-maps");?></td>

					<td>100%&nbsp; &nbsp;<em><?php _e("Required so that map is responsive", "bp-member-maps");?></em></td>
				</tr>

				<tr>
					<td><?php _e("Map Height", "bp-member-maps");?></td>

					<td>
						<input type="text" size="3" name="map-height-all" value="<?php echo $map_height_all;?>" />px  &nbsp; &nbsp;<em><?php _e("Cannot be less than 50 or greater than 640", "bp-member-maps");?></em>

					</td>
				</tr>


				<tr>
					<td style="vertical-align:top"><?php _e("Location Fields", "bp-member-maps");?></td>

				<?php if ( empty( $location_fields ) ) : ?>

					<td>
						<strong><?php echo _e("There are no Location profile fields, so maps will not be created. Please go to Profile Fields and add a Location field.", "bp-member-maps"); ?></strong>
					</td>
				</tr>

				<?php elseif ( count( $location_fields ) > 0 ) : ?>

					<td>
						<?php _e( "Select Location field for use on Members Directory Map:", "bp-member-maps"); ?>
						<br>

						<?php
							if ( isset( $map_location_field_all ) ) {

								$checked = '';

								foreach ( $location_fields as $lf ) {

									if ( $map_location_field_all == $lf->id ) {
										$checked = 'checked="checked"';
									}

								?>
									<br><input type="radio" name="map-location-field-all" value="<?php echo $lf->id; ?>" <?php echo $checked; ?> /> <?php echo $lf->name; ?> <br>
									<?php $checked = ''; ?>

								<?php
								}

							}
							else {

								foreach ( $location_fields as $lf ) {
								?>
									<input type="radio" name="map-location-field-all" value="<?php echo $lf->id; ?>" /> <?php echo $lf->name; ?> <br>

								<?php
								}
							}
						?>

					</td>
				</tr>

				<?php endif; ?>

				<tr>
					<td style="vertical-align:top"><?php _e("Limit Number of Members", "bp-member-maps");?></td>

					<td>
						<?php _e("If you have many members with Location data, you may want to limit the number of members shown.", "bp-member-maps");?>
						<br>
						<input type="text" size="3" name="map-limit-all" value="<?php echo $map_limit_all;?>" /> &nbsp;<?php _e("Enter an integer.","bp-member-maps");?>
						&nbsp; <?php _e("Zero means there is no limit.", "bp-member-maps");?>
						<br>
						<?php _e("Members will be culled based on their 'Recently Active' status.", "bp-member-maps");?>
					</td>
				</tr>

				<tr class="pp-filters">
					<td style="vertical-align:top"><?php _e("Member Type Display", "bp-member-maps");?></td>

					<td>

						<?php

						$member_types =  bp_get_member_types( array(), 'objects' );

						if ( empty( $member_types ) ) {

							_e("This setting is unavailable because there are no Member Types.", "bp-member-maps");

						} else {

							//echo 'mts: '; var_dump(  $member_types );
							//echo 'map_member_types: '; var_dump(  $map_member_types );

							_e("Select Member Types to be shown on the All Members Map. <br>If none are selected, all member types will be shown.",  "bp-member-maps");

							$mt_checkboxes = '<br>';

							foreach ( $member_types as $mt ) {

								$checked = '';

								if ( in_array( $mt->name, $map_member_types ) )
									$checked = ' checked';

								$mt_checkboxes .= '<input type="checkbox" class="" id="map_member_types" name="map_member_types[]" ';

								$mt_checkboxes .= 'value="' . $mt->name . '"' . $checked . ' /> &nbsp;' . $mt->labels['name'] . '<br>';

							}

							echo $mt_checkboxes;

						}

						?>


					</td>
				</tr>

				<tr class="pp-filters">
					<td style="vertical-align:top"><?php _e("Member Type Filter", "bp-member-maps");?></td>

					<td>

						<?php

						$member_types =  bp_get_member_types(); // bp_get_member_types( array(), 'objects' );

						if ( empty( $member_types ) ) {

							_e("This setting is unavailable because there are no Member Types.", "bp-member-maps");

						} else {

							//echo 'mts: '; var_dump(  $member_types );

							?>
							<input type="checkbox" class="check-map-member-filter-pp" id="map-member-filter-types" name="map-member-filter-types" value="1" <?php checked( $map_member_filter_types, 1 ); ?> /> &nbsp;
							<?php _e("Show a dropdown selector to filter by Member Type",  "bp-member-maps");?>

						<?php
						}
						?>


					</td>
				</tr>

				<tr class="pp-filters">
					<td style="vertical-align:top"><?php _e("Member Distance Filter", "bp-member-maps");?></td>

					<td>

						<input type="checkbox" class="check-map-member-filter-pp" id="map-member-filter-distance" name="map-member-filter-distance" value="1" <?php checked( $map_member_filter_distance, 1 ); ?> />
						&nbsp; <?php _e("Show a Distance filter.", "bp-member-maps");?>
						&nbsp;&nbsp;&nbsp;<?php _e("Measurement:", "bp-member-maps");?>
							<input type="radio"  name="map-member-distance-measurement" value="miles" <?php checked( $map_member_distance_measurement, 'miles' ); ?> > Miles
							&nbsp;&nbsp;&nbsp;
							<input type="radio" name="map-member-distance-measurement" value="kilometers" <?php checked( $map_member_distance_measurement, 'kilometers' ); ?> > Kilometers
					</td>
				</tr>

				<tr class="pp-filters">
					<td style="vertical-align:top"><?php _e("Member Keyword Filter", "bp-member-maps");?></td>

					<td>

						<input type="checkbox" class="check-map-member-filter-pp" id="map-member-filter-keywords" name="map-member-filter-keywords" value="1" <?php checked( $map_member_filter_keywords, 1 ); ?> />
						&nbsp; <?php _e("Show text input for keyword search. Will search xProfile fields.", "bp-member-maps");?>

					</td>
				</tr>


				<?php

				$show_bps_option = false;

				if ( PP_BPS ) {
					$show_bps_option = true;
				}

				?>

				<?php if ( $show_bps_option ) : ?>

					<tr class="bps">
						<td style="vertical-align:top"><?php _e("BP Profile Search", "bp-member-maps");?></td>

						<td>

							<input type="checkbox" class="check-map-member-filter-bps" id="map-member-filter-bps" name="map-member-filter-bps" value="1" <?php checked( $map_member_filter_bps, 1 ); ?> /> &nbsp;

								<?php _e("Use the <em>BP Profile Search</em> form interface to filter members on the All Members Map page.", "bp-member-maps");?>
								<br>
								<?php _e("Selecting this option means that the <em>Member Type</em>, <em>Member Distance</em> and <em>Member Keyword</em> filters will <strong>not</strong> be used.", "bp-member-maps");?>
								<br>
								<?php _e("See the FAQs in the readme file for more info.", "bp-member-maps");?>


						</td>
					</tr>

				<?php endif; ?>

				<tr>
					<td colspan="2">
						<?php wp_nonce_field("bp-member-maps");?>
						<input class="button-primary" type="submit"  name="bp-member-maps-save" value="<?php _e("Save Settings", "bp-member-maps");?>" />
					</td>
				</tr>

			</table>
			</form>
		</div>

		<script>

			jQuery(document).ready(function ($) {

				$(".check-map-member-filter-bps").click(function(){

					$("#map-member-filter-types").prop("checked", false);

					$("#map-member-filter-distance").prop("checked", false);

					$("#map-member-filter-keywords").prop("checked", false);

				});

				$(".check-map-member-filter-pp").click(function(){
					$("#map-member-filter-bps").prop("checked", false);
				});

			});

		</script>

   <?php
   }


	// get all existing Location fields used in wp-admin > Settings
	private function get_xprofile_fields_location() {
		global $wpdb;

		$location_fields = $wpdb->get_results( "SELECT id, name FROM {$wpdb->base_prefix}bp_xprofile_fields WHERE type = 'location' " );

		return $location_fields;
	}


	function pp_mm_save_license() {

		if ( ! empty( $_POST["bp-member-maps-lic-save"] ) ) {

		 	if( ! check_admin_referer( 'pp_mm_lic_save_nonce', 'pp_mm_lic_save_nonce' ) ) {
				return;
			}

			$old = get_site_option( 'pp_mm_license_key' );
			$new = trim( $_POST["pp_mm_license_key"] );

			if( $old && $old !=  $new ) {
				delete_option( 'pp_mm_license_status' ); // new license has been entered, so must reactivate
			}

			update_site_option( 'pp_mm_license_key', $new );

		}
	}

	function pp_mm_activate_license() {

		if( isset( $_POST['pp_mm_license_activate'] ) ) {

		 	if( ! check_admin_referer( 'pp_mm_lic_nonce', 'pp_mm_lic_nonce' ) ) {
				return;
			}

			$license = trim( get_site_option( 'pp_mm_license_key' ) );

			$api_params = array(
				'edd_action'=> 'activate_license',
				'license' 	=> $license,
				'item_name' => urlencode( PP_BP_MAPS_MEMBERS ), // the name of our product in EDD
				'url'       => home_url()
			);

			$response = wp_remote_post( PP_MAPS_MEMBERS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( is_wp_error( $response ) ) {
				var_dump( $response );
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			//	update_site_option( 'edd_response', $license_data);
			// $license_data->license will be either "valid" or "invalid"

			update_site_option( 'pp_mm_license_status', $license_data->license );

		}
	}

	function pp_mm_deactivate_license() {

		if( isset( $_POST['pp_mm_license_deactivate'] ) ) {

		 	if( ! check_admin_referer( 'pp_mm_lic_nonce', 'pp_mm_lic_nonce' ) ) {
				return;
			}

			$license = trim( get_site_option( 'pp_mm_license_key' ) );

			$api_params = array(
				'edd_action'=> 'deactivate_license',
				'license' 	=> $license,
				'item_name' => urlencode( PP_BP_MAPS_MEMBERS ), // the name of our product in EDD
				'url'       => home_url()
			);

			$response = wp_remote_post( PP_MAPS_MEMBERS_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

			if ( is_wp_error( $response ) ) {
				return false;
			}

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data->license == 'deactivated' ) {
				delete_option( 'pp_mm_license_status' );
			}

		}
	}

}

