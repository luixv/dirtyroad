<?php

if ( !defined( 'BUDDYBLOCK_VERSION' ) ) exit;

function bp_block_member_init() {

	if ( is_user_logged_in() ) {

		require_once( dirname( __FILE__ ) . '/class-bp-block-member.php' );

		BP_Block_Member::get_instance();

	}

	if ( is_admin() ) {

		if ( ! class_exists('WP_List_Table') ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		require_once( dirname( __FILE__ ) . '/admin/class-bp-block-member-list-admin.php' );

	}


}
add_action( 'bp_init', 'bp_block_member_init' );

