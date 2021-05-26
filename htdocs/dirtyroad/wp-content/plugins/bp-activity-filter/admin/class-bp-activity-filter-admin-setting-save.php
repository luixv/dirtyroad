<?php
/**
 * Defining class for Filter dropdown option for public setting
 */

if ( ! class_exists( 'WbCom_BP_Activity_Filter_Admin_Setting_Save' ) ) {


	class WbCom_BP_Activity_Filter_Admin_Setting_Save {
		
		/**
		 * Constructor
		 */

		public function __construct() {

			/**
			 * Saving option values
			 */

			add_action( 'bp_admin_init', array( $this, 'bp_core_acivity_filter_admin_settings_save' ), 10 );

		}

		/**
		 * Saving options
		 */


		public function bp_core_acivity_filter_admin_settings_save() {

			if ( isset( $_GET['page'] ) && 'bp_activity_filter_settings' == $_GET['page'] && ! empty( $_POST['submit'] ) ) {

				check_admin_referer( 'buddypress-options' );

				$hidden_filters = array();
				$hidden_profile_filters = array();

				if ( ! empty( $_POST['bp-default-filter-name'] ) ) {

					$bp_default_filter_name = sanitize_text_field( $_POST['bp-default-filter-name'] );

					bp_update_option( 'bp-default-filter-name', filter_var( $bp_default_filter_name, FILTER_SANITIZE_STRING ) );

				}

				if ( isset($_POST['bp-hidden-filters-name']) && is_array( $_POST['bp-hidden-filters-name'] ) ) {

					$hidden_filters = array_map( 'sanitize_text_field', wp_unslash( $_POST['bp-hidden-filters-name'] ) );
				}

				bp_update_option( 'bp-hidden-filters-name', $hidden_filters );
				
				if ( ! empty( $_POST['bp-default-profile-filter-name'] ) ) {

					$bp_default_profile_filter_name = sanitize_text_field( $_POST['bp-default-profile-filter-name'] );

					bp_update_option( 'bp-default-profile-filter-name', filter_var( $bp_default_profile_filter_name, FILTER_SANITIZE_STRING ) );

				}

				if ( isset($_POST['bp-hidden-profile-filters-name']) && is_array( $_POST['bp-hidden-profile-filters-name'] ) ) {

					$hidden_profile_filters = array_map( 'sanitize_text_field', wp_unslash( $_POST['bp-hidden-profile-filters-name'] ) );
				}

				bp_update_option( 'bp-hidden-profile-filters-name', $hidden_profile_filters );

			}

		}


	}

}

if ( class_exists( 'WbCom_BP_Activity_Filter_Admin_Setting_Save' ) ) {
	$admin_setting_save_obj = new WbCom_BP_Activity_Filter_Admin_Setting_Save();
}
