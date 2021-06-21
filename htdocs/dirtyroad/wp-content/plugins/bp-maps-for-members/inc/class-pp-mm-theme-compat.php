<?php
/**
 * BuddyPress Members Theme Compat for All Members Map Template
 *
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


class BP_Members_Theme_Compat_Map {

	public function __construct() {

		$settings = get_site_option( 'bp-member-map-all-settings' );
		extract($settings);

		if ( isset( $map_skip_all ) ) {
			return;
		}

		add_action( 'bp_setup_theme_compat', array( $this, 'is_members' ) );
	}

	public function is_members() {

		if( ! bp_current_component( 'members' ) ) {
			return;
		}

		add_action( 'bp_template_include_reset_dummy_post_data', array( $this, 'members_create_dummy_post' ) );
		add_filter( 'bp_replace_the_content',                    array( $this, 'members_create_content'    ) );

	}

	public function members_create_dummy_post() {

		bp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_title'     => __( 'Members Map', 'bp-member-maps' ),
			'post_author'    => 0,
			'post_date'      => 0,
			'post_content'   => '',
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'is_page'        => true,
			'comment_status' => 'closed'
		) );

	}

	public function members_create_content() {
		return bp_buffer_template_part( 'members/members-map', null, false );
	}

}
