<?php

/*
 * Customize mp-restaurant-menu
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet
 */

class Emmet_MP_Restaurant_Menu {

	public function __construct() {
		$this->remove_action_plugin();
		$this->add_action_plugin();
	}

	public function remove_action_plugin() {
		remove_action( 'mprm_single_before_wrapper', 'mprm_theme_wrapper_before' );
		remove_action( 'mprm_single_after_wrapper', 'mprm_theme_wrapper_after' );

		remove_action( 'mprm_category_before_wrapper', 'mprm_theme_wrapper_before' );
		remove_action( 'mprm_category_after_wrapper', 'mprm_theme_wrapper_after' );

		remove_action( 'mprm_tag_before_wrapper', 'mprm_theme_wrapper_before' );
		remove_action( 'mprm_tag_after_wrapper', 'mprm_theme_wrapper_after' );
	}

	public function add_action_plugin() {
// category
		add_action( 'mprm_after_category_header', array( $this, 'mprm_after_category_header' ) );
		add_action( 'mprm_category_after_wrapper', array( $this, 'mprm_after_wrapper' ) );
// item
		add_action( 'mprm_after_menu_item_header', array( $this, 'mprm_after_menu_item_header' ) );
		add_action( 'mprm_single_after_wrapper', array( $this, 'mprm_single_after_wrapper' ) );
// tag
		add_action( 'mprm_tag_before_wrapper', array( $this, 'mprm_tag_before_wrapper' ) );
		add_action( 'mprm_tag_after_wrapper', array( $this, 'mprm_tag_after_wrapper' ) );
	}


	function mprm_after_menu_item_header() {
		echo '<div class="container main-containe">';
	}

	function mprm_single_after_wrapper() {
		echo '</div>';
	}

	function mprm_after_category_header() {
		echo '<div class="container main-containe">';
	}

	function mprm_after_wrapper() {
		echo '</div>';
	}

	function mprm_tag_before_wrapper() {
		echo '<div class="container main-container">';
	}

	function mprm_main_wrapper_class() {
		echo '</div>';
	}

}

new Emmet_MP_Restaurant_Menu();
