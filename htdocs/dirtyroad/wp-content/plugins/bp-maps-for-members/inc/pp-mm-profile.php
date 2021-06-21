<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function pp_mm_add_location_profile_tabs() {

	if ( bp_is_user() ) {

		$settings_single = get_site_option( 'bp-member-map-single-settings' );
		extract($settings_single);

		if ( isset( $map_location_field) )  {

			$key = 'geocode_' . $map_location_field;
			$geocode_exists_for_member = get_user_meta( bp_displayed_user_id(), $key, true );

			if ( ! empty( $geocode_exists_for_member ) ) {

				bp_core_new_nav_item( array(
					'name'                  => __( 'Location', 'bp-member-maps' ),
					'slug'                  => 'location',
					'parent_url'            => bp_displayed_user_domain(),
					'parent_slug'           => bp_get_profile_slug(),
					'screen_function'       => 'pp_mm_location_profile_screen',
					'position'              => 239.4,
					'default_subnav_slug'   => 'location'
				) );
			}
		}
	}
}
add_action( 'bp_setup_nav', 'pp_mm_add_location_profile_tabs', 100 );

function pp_mm_location_profile_screen() {
    add_action( 'bp_template_content', 'pp_mm_location_profile_screen_content' );
    bp_core_load_template( 'members/single/plugins' );
}

function pp_mm_location_profile_screen_content() {
	bp_get_template_part('members/single/member-single-map');
}

