<?php
/**
 * Plugin hooks file
 *
 * @package mpp-set-profile-cover
 */

// Exit if file access directly over web.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set profile cover
 */
function mpp_spc_set_profile_cover() {

	if ( ! isset ( $_GET['mpp-action'] ) || $_GET['mpp-action'] !== 'set-profile-cover' ) {
		return ;
	}

	// our action is set, make sure we are on the change cover page.
	if ( ! bp_is_user_change_cover_image() ) {
		return;
	}

	// verify request.
	if ( ! isset( $_GET['mpp-nonce'] ) || ! wp_verify_nonce( $_GET['mpp-nonce'], 'mpp-set-profile-cover' ) ) {
		return;
	}

	$media_id = isset( $_GET['mpp-media-id'] ) ? absint( $_GET['mpp-media-id'] ) : 0;

	if ( ! $media_id ) {
		return;
	}

	$media = mpp_get_media( $media_id );

	if ( is_null( $media ) || $media->type !== 'photo' || $media->user_id != bp_loggedin_user_id() ) {
		bp_core_add_message( __( 'You can only use your own photo.', 'mpp-set-profile-cover' ), 'error' );
		return;
	}

	$bp_attachments_uploads_dir = bp_attachments_uploads_dir_get();

	$object_data = array(
		'dir'       => 'members',
		'component' => 'xprofile',
	);

	$cover_subdir   = $object_data['dir'] . '/' . bp_loggedin_user_id() . '/cover-image';
	$cover_dir      = trailingslashit( $bp_attachments_uploads_dir['basedir'] ) . $cover_subdir;

	$cover_image_attachment = new BP_Attachment_Cover_Image();
	$file       = mpp_get_media_path( '', $media );
	$file_array = explode( '/', $file );
	$new_file   = $cover_dir . '/' . end( $file_array );

	if ( wp_mkdir_p( $cover_dir ) ) {
		copy( $file, $new_file );
		$stat  = stat( dirname( $new_file ) );
		$perms = $stat['mode'] & 0000666;
		@ chmod( $new_file, $perms );
	}

	$cover = bp_attachments_cover_image_generate_file( array(
		'file'            => $new_file,
		'component'       => $object_data['component'],
		'cover_image_dir' => $cover_dir,
	), $cover_image_attachment );

	if ( ! $cover ) {
		bp_core_add_message( __( 'Unable to set as cover.', 'mpp-set-profile-cover' ), 'error' );
		return;
	} else {
		bp_core_add_message( __( 'Cover image is added successfully.', 'mpp-set-profile-cover' ), 'success' );
	}
}

add_action( 'bp_screens', 'mpp_spc_set_profile_cover', 200 );

