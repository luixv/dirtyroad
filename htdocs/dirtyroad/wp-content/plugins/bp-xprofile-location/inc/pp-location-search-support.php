<?php


if ( PP_BPS ) {

	add_action ('bps_custom_field', 'pp_loc_profile_search_field_distance');

	function pp_loc_profile_search_field_distance ($f) {

		if ($f->type != 'location') {
			return;
		}

		$f->format = 'location';
		$f->script_handle = 'google-places-api';
		$f->search = 'pp_loc_profile_search_field_radial';
	}

}

if ( PP_BOSS ) {

	function pp_loc_boss_ps_fields( $fields ) {

		//write_log( $fields );
		foreach( $fields as $key => $field ) {

			if ( isset( $field->type )  && $field->type == 'location' ) {

				$field->format = 'location';
				$field->script_handle = 'google-places-api';
				$field->search = 'pp_loc_profile_search_field_radial';

			}

		}

		return $fields;
	}
	add_filter( 'bp_ps_add_fields', 'pp_loc_boss_ps_fields', 999 );

}

function pp_loc_profile_search_field_radial ($f) {

	$filter = $f->filter; 	// the current search mode
	$results = array();

	if ($filter != 'distance') {

		if ( PP_BPS ) {
			return bps_xprofile_search ( $f ); 	// handles the text search
		} else if ( PP_BOSS ) {
			return bp_ps_xprofile_search ( $f ); 	// handles the text search
		}
	}
	else {
		$results = pp_location_bp_profile_search( $f );	// array of user IDs within the radial search
	}

	return $results;
}


// support for radial search in BP Profile Search
function pp_location_bp_profile_search( $args ) {

	$center_lat = (float) $args->value['lat'];
	$center_lng = (float) $args->value['lng'];
	$radius		= (int)   $args->value['distance'];
	$field_id	= (int)   $args->id;
	$key 		= 'geocode_' . $field_id;

	$earthRadius = 3959;  // miles
	if (  $args->value['units'] == 'km' ) {
		$earthRadius = 6371;
	}

	$user_ids = pp_location_members_radial_distance( $center_lat, $center_lng, $radius, $key, $earthRadius );

	//write_log( 'xprofile');
	//write_log( $user_ids );

	//$user_ids = array( 1 );

	return $user_ids;

}

function pp_location_members_radial_distance( $center_lat, $center_lng, $radius, $key, $earthRadius ) {
	global $wpdb;

	if ( intval( $radius ) < 1 ) {
		$raidus = 10;
	}

	// get all member coords
	$user_coords = $wpdb->get_results( " SELECT user_id, meta_value FROM $wpdb->usermeta WHERE meta_key = '$key' ");

	$user_ids = array ();

	if ( $user_coords ) {

		foreach ( $user_coords as $user ) {

			$coords = explode( ',', $user->meta_value );

			$lat = (float) $coords[0];
			$lng = (float) $coords[1];

			$check_radial = pp_location_member_check_radial( $center_lat, $center_lng, $lat, $lng, $earthRadius );

			if ( $check_radial <= $radius ) {

				$user_ids[] = $user->user_id;
			}

		}
	}

	return $user_ids;

}

function pp_location_member_check_radial( $center_lat, $center_lng, $lat, $lng, $earthRadius ) {

	// convert from degrees to radians
	$latFrom = deg2rad($center_lat);
	$lonFrom = deg2rad($center_lng);
	$latTo = deg2rad($lat);
	$lonTo = deg2rad($lng);

	$latDelta = $latTo - $latFrom;
	$lonDelta = $lonTo - $lonFrom;

	$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

	return $angle * $earthRadius;

}



/*  BPS filters */

add_filter ('pp_location_bps_filter_member_ids', 'bps_filter_pp_location_member_ids');
function bps_filter_pp_location_member_ids( $user_ids ) {

	$request = bps_get_request ('search');	// get the search request for this directory
	if ( !empty ($request) ) {
		$results = bps_search ($request);
		if ($results['validated'])			// a valid search has been run
		{
			$user_ids = $results['users'];
		}
	}

	return $user_ids;
}


add_filter ('bps_current_page', 'pp_location_current_page', 999 );
function pp_location_current_page ($current) {

	foreach (bps_directories() as $dir) {

		if ($current == $dir->path. 'membersmap/')  {

			$current = $dir->path . 'membersmap/';

			return $current;

		}

	}

	return $current;
}


add_filter ('bps_add_directory', 'pp_location_add_my_directories');		// add the [membersmap] shortcode pages as directories
function pp_location_add_my_directories ($dirs) {

	$pages = get_pages ();
	foreach ($pages as $page)  if (has_shortcode ($page->post_content, 'membersmap'))
	{
		$dir = new stdClass;
		$dir->id = $page->ID;
		$dir->title = $page->post_title;
		$dir->path = parse_url (get_page_link ($page->ID), PHP_URL_PATH);
		$dirs[$page->ID] = $dir;
	}

	return $dirs;
}


add_action ('bps_before_search_form', 'pp_location_bps_form_action');
function pp_location_bps_form_action ($form) {

	$requested_url = bp_get_requested_url();

	if( ( strpos( $requested_url, BP_MEMBERS_SLUG ) !== false ) && ( strpos( $requested_url, 'membersmap' ) !== false ) ) {
		$form->action = $requested_url;
	}

}

/* end BPS filters */

