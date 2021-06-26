<?php
/**
 * Class for rendering link for set as profile photo
 *
 * @package mpp-set-profile-cover
 */

// Exit if the file is accessed directly over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class MPP_SPC_Template_Helper
 */
class MPP_SPC_Template_Helper {

	/**
	 * The constructor.
	 */
	public function __construct() {
		$this->setup();
	}

	/**
	 * Setup callback to MediaPress actions.
	 */
	private function setup() {
		add_action( 'mpp_media_meta', array( $this, 'add_link' ) );
		add_action( 'mpp_lightbox_media_meta', array( $this, 'add_link' ) );
	}

	/**
	 * Add set as profile cover link to media meta.
	 *
	 * @param null|MPP_Media $media media object.
	 */
	public function add_link( $media = null ) {

		$media = mpp_get_media( $media );

		if ( 'photo' != $media->type || $media->user_id != bp_loggedin_user_id() ) {
			return;
		}

		echo $this->get_change_cover_link( $media->id );
	}

	/**
	 * Get link
	 *
	 * @param int    $media_id  Id of media.
	 * @param string $label     Button label.
	 * @param string $css_class Class name.
	 *
	 * @return string
	 */
	public function get_change_cover_link( $media_id, $label = '', $css_class = '' ) {

		if ( ! $label ) {
			$label = __( 'Set Profile Cover', 'mpp-set-profile-cover' );
		}

		$css_class = 'mpp-set-profile-cover ' . $css_class;
		$url       = $this->get_url( $media_id );
		$link      = sprintf( '<a href="%s" class="%s" title="%s">%s</a>', $url, $css_class, $label, $label );

		return $link;
	}

	/**
	 * Get query string
	 *
	 * @param int $media_id Media id.
	 *
	 * @return string
	 */
	public function get_url( $media_id ) {

		$url = trailingslashit( bp_loggedin_user_domain() . bp_get_profile_slug() ) . 'change-cover-image/';
		$url = add_query_arg( array(
			'mpp-media-id'   => $media_id,
			'mpp-action' => 'set-profile-cover',
			'mpp-nonce'  => wp_create_nonce( 'mpp-set-profile-cover' ),
		), $url );

		return $url;
	}
}

new MPP_SPC_Template_Helper();

