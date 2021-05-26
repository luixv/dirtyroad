<?php

/*
 * Customize mp-timetable
 * @package WordPress
 * @subpackage Emmet
 * @since Emmet
 */

class Emmet_MP_Timetable {

	public function __construct() {
		$this->remove_action_plugin();
		$this->add_action_plugin();
	}

	public function remove_action_plugin() {
		remove_action( 'mptt-single-mp-column-before-wrapper', 'mptt_theme_wrapper_before' );
		remove_action( 'mptt-single-mp-column-after-wrapper', 'mptt_theme_wrapper_after' );
		remove_action( 'mptt-single-mp-event-before-wrapper', 'mptt_theme_wrapper_before' );
		remove_action( 'mptt-single-mp-event-after-wrapper', 'mptt_theme_wrapper_after' );
	}

	public function add_action_plugin() {
		add_action( 'mptt-single-mp-event-before-wrapper', array( $this, 'mptt_before_wrapper' ) );
		add_action( 'mptt-single-mp-event-after-wrapper', array( $this, 'mptt_after_wrapper' ) );
		add_action( 'mptt-single-mp-column-before-wrapper', array( $this, 'mptt_before_wrapper' ) );
		add_action( 'mptt-single-mp-column-after-wrapper', array( $this, 'mptt_after_wrapper' ) );
	}

	function mptt_before_wrapper() {
		echo '<div class="container main-container">';
	}

	function mptt_after_wrapper() {
		echo '</div>';
	}

}

new Emmet_MP_Timetable();
