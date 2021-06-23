<?php

function pp_mm_main_div_class() {
	return ( bp_get_theme_compat_id() == 'nouveau' ? 'buddypress-wrap bp-dir-hori-nav' : '' );
}

function pp_mm_map_filters() {

	$settings = get_site_option( 'bp-member-map-all-settings' );

	extract($settings);

	$show_pp_map_filters = true;

	//if ( bp_is_members_directory() ) {

		// check for the $map_member_filter_bps option from 'bp-member-map-all-settings'
		if ( isset( $map_member_filter_bps ) ) {

			// check if BP Profile Search is active
			if ( PP_BPS ) {
				$show_pp_map_filters = false;
			}
		}

	//}


				if ( isset( $map_member_types ) ) {

					if ( ! empty( $map_member_types ) ) {

						//$member_type = $map_member_types;
						set_query_var( 'member_type', $map_member_types  );
					}

					//write_log( 'pp_mm_map_filters() in pp-mm-template-tags.php' );
					//write_log($map_member_types);

				}




	if ( $show_pp_map_filters ) {

		if ( isset( $map_member_filter_distance ) || isset( $map_member_filter_types )  || isset( $map_member_filter_keywords ) ) {

			if ( isset( $map_member_filter_distance ) ) {

				set_query_var( 'map_member_filter_distance', $map_member_filter_distance );

				set_query_var( 'map_member_distance_measurement', $map_member_distance_measurement );

				if ( isset( $_POST['pp_member_search_radius'] ) ) {

					$pp_member_search_radius = sanitize_text_field( $_POST['pp_member_search_radius'] );

					set_query_var( 'search_radius', $pp_member_search_radius );

				}

				if ( isset( $_POST['pp_member_search_center'] ) ) {

					$search_center = sanitize_text_field( $_POST['pp_member_search_center'] );

					set_query_var( 'search_center', $search_center );

				}

				if ( isset( $_POST['pp_member_search_center_coords'] ) ) {

					$search_coords = sanitize_text_field( $_POST['pp_member_search_center_coords'] );

					set_query_var( 'search_coords', $search_coords );

				}


			}

			if ( isset( $map_member_filter_types ) ) {

				set_query_var( 'map_member_filter_types', $map_member_filter_types );

				if ( isset( $_POST['member-type-filter'] ) ) {

					$member_type = sanitize_text_field( $_POST['member-type-filter'] );

					set_query_var( 'member_type', $member_type );

					//write_log( '$map_member_filter_types' );

				}

			}

			if ( isset( $map_member_filter_keywords ) ) {

				set_query_var( 'map_member_filter_keywords', $map_member_filter_keywords );

				if ( isset( $_POST['pp_member_search_keywords'] ) ) {

					$keywords = sanitize_text_field( $_POST['pp_member_search_keywords'] );

					set_query_var( 'keywords', $keywords );

				}

			}

			bp_get_template_part('members/members-map-filters');

		}

	}
	else {

		do_action( 'bp_before_directory_members_content' ); 	// place to add the BPS search args
		do_action( 'bp_before_directory_members_tabs' ); 		// place to add a BPS search form

	}

}


// map marker
function pp_mm_load_dot() {
	return plugin_dir_url(__FILE__) . 'icons/red-dot.png';
}


// cluster icon for map
function pp_mm_load_cluster_icons() {
	return plugin_dir_url(__FILE__) . 'icons/m';
}


// populate template with passed vars and return
function pp_mm_get_template_html( $template, $args = array() ) {

	ob_start();

	if ( $template ) {
		extract( $args );
		include $template;

	} else {
		echo 'Error: Map item template not found';
	}

	return ob_get_clean();
}


// collect all the member data for use in the map js in member-map.php template
function pp_mm_gather_map_data() {

	$settings = get_site_option( 'bp-member-map-all-settings' );
	extract($settings);

	$google_key = '';
	$location_profile_field = '';
	$members_data = array();

	if ( isset( $map_location_field_all ) ) {

		$key = 'geocode_' . $map_location_field_all;
		// make sure the field exists.
		$location_field = pp_mm_check_xprofile_field_location( $map_location_field_all );

	} else {

		if ( is_super_admin() ) {
			_e( 'You have not set a Location Field for use with this Map. Please go to wp-admin > Settings > BP Maps for Members, select a Location field and Save Settings.', 'bp-member-maps' );
		} else {
			_e( 'A Location Field has not been set for use with this Map. Please contact the Site Administrator.', 'bp-member-maps' );
		}
	}

	//if ( ! isset( $map_zoom_level_all ) ) {
	//	$map_zoom_level_all = 14;
	//}

	if ( ! isset( $map_member_distance_measurement ) ) {
		$map_member_distance_measurement = 'miles';
	}

	if ( ! empty( $key ) && ! empty( $location_field ) ) {

		?>

		<?php // get the template path for use in the loop below,  but prevent display ?>
		<div style="display: none;"><?php $template = bp_get_template_part( 'members/members-map-item' ); ?></div>
		<?php

		$gather = pp_mm_gather_members( $key, $map_member_distance_measurement );

		$member_type = $gather['member_type'];

		$members = apply_filters( 'pp_members_map_members_filter', $gather['members'] );


		if ( empty( $members ) ) {

			settype( $members, "object" );
			$members->results = array();
			$members->total_users = 0;

		}

		$geo_locations = array();
		$geo_names = array();
		$geo_content = array();

		foreach ( $members->results as $member ) {

			$latlng = get_user_meta( $member->ID, $key, true );
			$address = xprofile_get_field_data( $map_location_field_all, $member->ID, 'comma' );

			$member_url = bp_core_get_user_domain( $member->ID );
			$title = '<a href="' . $member_url . '" target="maptab">' . $member->display_name . '</a>';

			$avatar = '';
			$avatar = bp_core_fetch_avatar(
				array(
					'item_id' 	=> $member->ID,
					'type' 		=> 'thumb',  // or 'full'
					'width' 	=> 32,
					'height' 	=> 32,
					'class' 	=> 'avatar',
					'html'		=> true
				)
			);


			$item_args = array( 'avatar' => $avatar, 'title' => $title, 'address' => $address );

			// filter for adding item_args
			$args = apply_filters( 'pp_mm_item_filter', $item_args, $member->ID );

			$geo_content_item = pp_mm_get_template_html( $template, $args );

			//  filter hook re $geo_content_item before adding to array ?

			$geo_content[] = $geo_content_item;

			$geo_locations[] = explode(",", $latlng);
			$geo_names[] 	 = $member->display_name;

		}

		$members_data['geo_names'] 		= $geo_names;
		$members_data['geo_locations'] 	= $geo_locations;
		$members_data['geo_content'] 	= $geo_content;

	}

	return $members_data;

}
