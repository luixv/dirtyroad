<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



// add Map tab on Members Directory Page - Legacy Template
function pp_mm_members_tab() {

	$settings = get_site_option( 'bp-member-map-all-settings' );
	extract($settings);

	if ( isset( $map_skip_all ) ) {
		return;
	}

	$requested_url = bp_get_requested_url();

	if( bp_is_members_directory() || ( strpos( $requested_url, BP_MEMBERS_SLUG ) !== false ) && ( strpos( $requested_url, 'membersmap' ) === false ) ) {

		$button_args = array(
			'id'         => 'membersmap',
			'component'  => 'members',
			'link_text'  => __( 'Map', 'bp-member-maps' ),
			'link_title' => __( 'Map', 'bp-member-maps' ),
			'link_class' => 'membersmap no-ajax',
			'link_href'  => trailingslashit( bp_get_members_directory_permalink() . 'membersmap' ),
			'wrapper'    => false,
			'block_self' => false,
			'must_be_logged_in' => false,
		);

		?>
		<li><?php echo bp_get_button( apply_filters( 'bp_get_members_map_button', $button_args ) ); ?></a></li>
		<?php
	}
}
add_action( 'bp_members_directory_member_types', 'pp_mm_members_tab' );


// add Map tab on Members Directory Page - Nouveau Template
function pp_mm_members_tab_nouveau( $nav_items ) {

	$settings = get_site_option( 'bp-member-map-all-settings' );
	extract($settings);

	if ( isset( $map_skip_all ) ) {
		return $nav_items;
	}

	$requested_url = bp_get_requested_url();

	if( bp_is_members_directory() || ( strpos( $requested_url, BP_MEMBERS_SLUG ) !== false ) && ( strpos( $requested_url, 'membersmap' ) === false ) ) {

			$nav_items['membersmap'] = array(
				'component' => 'members',
				'slug'      => 'membersmap', // slug is used because BP_Core_Nav requires it, but it's the scope
				'li_class'  => array('no-ajax'),
				'link'      => trailingslashit( bp_get_members_directory_permalink() . 'membersmap' ),
				//'link'      => site_url( trailingslashit( 'membersmap' ) ),
				'text'      => __( 'Map', 'bp-member-maps' ),
				'count'     => false,  //'',
				'position'  => 15,
			);

	}

	return $nav_items;
}
add_filter( 'bp_nouveau_get_members_directory_nav_items', 'pp_mm_members_tab_nouveau' );


// load the Members Map template
function pp_mm_members_show_map() {

	$requested_url = bp_get_requested_url();

	if( ( strpos( $requested_url, BP_MEMBERS_SLUG ) !== false ) && ( strpos( $requested_url, 'membersmap' ) !== false ) ) {

		$bp = buddypress();
        $bp->current_component = BP_MEMBERS_SLUG;

		new BP_Members_Theme_Compat_Map();

		bp_core_load_template( 'members/members-map' );

	} else {
		return false;
	}

}
add_action( 'bp_actions', 'pp_mm_members_show_map' );

// add path to plugin templates
function pp_mm_register_template_location() {
    return PP_MM_DIR . '/templates/';
}

function pp_mm_template_start() {
    if( function_exists( 'bp_register_template_stack' ) ) {
        bp_register_template_stack( 'pp_mm_register_template_location' );
	}
}
add_action( 'bp_init', 'pp_mm_template_start' );



function pp_mm_enqueue_script() {

	$gapikey = get_site_option( 'pp_gapikey' );

	if ( $gapikey != false ) {

		if ( ! wp_script_is( 'google-places-api', 'registered' ) ) {
		// comment out this conditional if the shortcode is on the Member's page

			wp_register_script( 'google-places-api', '//maps.googleapis.com/maps/api/js?key=' . $gapikey . '&libraries=places' );

			wp_register_script('google-maps-cluster', plugin_dir_url(__FILE__) . 'js/markerclusterer.min.js', array('jquery') );

		}
	}
}
add_action( 'wp_enqueue_scripts', 'pp_mm_enqueue_script' );


// hook in members-map template
function pp_mm_load_map_scripts() {

	if ( wp_script_is( 'google-places-api', 'registered' ) ) {

		wp_enqueue_script( 'google-places-api' );
		wp_enqueue_script( 'google-maps-cluster' );

		wp_print_scripts( 'google-places-api' );
		wp_print_scripts( 'google-maps-cluster' );

	}

} // comment out this hook if the shortcode is on the Members page
add_action( 'bp_members_page_map_scripts', 'pp_mm_load_map_scripts' );


// make sure the selected Location field exists for the All Members Map template
function pp_mm_check_xprofile_field_location( $id ) {
	global $wpdb;

	$bp = buddypress();

	$field = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$bp->profile->table_name_fields} WHERE id = %d", $id ) );

	if ( ! $field ) {
		return false;
	}

	return true;
}


// create 'membersmap' shortcode
function pp_mm_shortcode() {

	ob_start();

	bp_get_template_part('members/members-map');

	return ob_get_clean();

}
add_shortcode( 'membersmap', 'pp_mm_shortcode' );


// create 'membersmap-single' shortcode
function pp_mm_single_shortcode() {

	ob_start();

	if ( bp_is_user() ) {

		bp_get_template_part('members/single/member-single-map');

	} else {

		echo '<br>' . _e('The "membersmap-single" shortcode can only be used on a Profile page.', 'bp-member-maps');
	}

	return ob_get_clean();

}
add_shortcode( 'membersmap-single', 'pp_mm_single_shortcode' );


function pp_mm_member_login_modified($user_login) {

	// remove spaces, remove @, change dot to dash

	$user_login = preg_replace('/\s/', '', $user_login);

	$user_login = str_replace('@', '', $user_login);

	$user_login = str_replace('.', '-', $user_login);

	return $user_login;

}

/*
 * Radial Distance Search Functions moved to BP xProfile Location plugin
 */

function pp_mm_gather_members( $key, $map_member_distance_measurement ) {

	$settings = get_site_option( 'bp-member-map-all-settings' );

	extract($settings);

	if ( isset( $map_limit_all ) && $map_limit_all > 0 ) {

	} else {
		$map_limit_all = 0;
	}
	$member_type = '';

	if ( isset( $_POST["member-type-filter"] ) && $_POST["member-type-filter"]  != '-1' ) {

		$member_type = sanitize_text_field( $_POST["member-type-filter"] );
	} else {


		if ( isset( $map_member_types ) ) {

			//write_log( 'from pp_mm_gather_members()' );
			//write_log($map_member_types);

			if ( ! empty( $map_member_types ) ) {

				$member_type = $map_member_types;
			}

		}

	}

	$user_ids = array ();

	if ( isset( $_POST['pp_member_search_center_coords'] ) && ! empty( $_POST['pp_member_search_center_coords'] ) ) {

		$coords = sanitize_text_field( $_POST['pp_member_search_center_coords'] );

		$coords = explode( ',', $coords );

		$lat = (float) $coords[0];
		$lng = (float) $coords[1];

		$radius = (int) sanitize_text_field( $_POST['pp_member_search_radius'] );

		$user_ids = array();

		$earthRadius = 3959;  // miles
		if ( $map_member_distance_measurement == 'kilometers' ) {
			$earthRadius = 6371;
		}

		// function is in the BP xProfile Location plugin
		$user_ids = pp_location_members_radial_distance( $lat, $lng, $radius, $key, $earthRadius );

		if ( empty ( $user_ids ) ) {

			$gather['members'] = $user_ids;

			$gather['member_type'] = $member_type;

			return $gather;

		}


	} elseif ( PP_BPS ) {

		//use BPS to filter member ids if that option is selected in Settings, filter func is in bp xprofile location
		if ( isset( $map_member_filter_bps ) ) {

			$user_ids = bps_filter_pp_location_member_ids( $user_ids );

		}

	} elseif ( PP_BOSS ) {

		if ( function_exists('bp_ps_get_request') ) {

			$request = bp_ps_get_request( 'search' );

			$request_keys = array_keys( $request );

			if ( ! empty( $request_keys ) ) {

				$members_boss = bp_ps_search( $request);

				if ( $members_boss['validated'] ) {

					$user_ids = $members_boss['users'];
				}

			}

		}

	}


	if ( isset( $_POST['pp_member_search_keywords'] ) && ! empty( $_POST['pp_member_search_keywords'] ) ) {
		$search_terms = bp_esc_like( wp_kses_normalize_entities( $_POST['pp_member_search_keywords'] ) );
	} else {
		$search_terms = false;
	}


	$user_ids = apply_filters( 'bp_maps_for_members_user_ids_filter', $user_ids );

	$member_type = apply_filters( 'bp_maps_for_members_type_filter', $member_type );

	$args = array(
		'type'				=> 'active',
		'per_page'			=> $map_limit_all,
		'populate_extras'	=> false,
		'member_type'		=> $member_type,
		'meta_key'			=> $key,
		'include'			=> $user_ids,
		'search_terms'      => $search_terms,
	);


	$gather = array();

	$gather['members'] = new BP_User_Query( $args );


	//write_log( 'pp_mm_gather_members() in pp-mm-functions.php' );
	//write_log( $gather['members'] );

	$gather['member_type'] = $member_type;

	return $gather;

}

/*
function member_type_test_filter( $member_type ) {
	$member_type = 'cats';
	return $member_type;
}
add_filter('bp_maps_for_members_type_filter', 'member_type_test_filter');
*/
