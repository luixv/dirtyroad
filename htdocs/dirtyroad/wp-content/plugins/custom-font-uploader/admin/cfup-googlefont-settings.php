<?php
/**
 * Exit if accessed directly.
 *
 * @package custom-font-uploader
 * @version 1.0.0
 * @author  wbcomdesigns
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$options         = array();
$cfupgf_notices  = array();
$settings        = get_option( 'cfup_general_settings', true );
$api_key         = '';
$apikey_verified = '';
if ( isset( $settings['apikey_settings'] ) ) {
	$api_key = $settings['apikey_settings'];
}

if ( '' != $api_key ) {
	$cfupgf_fonts    = get_option( 'cfupgooglefonts_data' );
	$apikey_verified = get_option( 'cfup_apikey_verified' );
	if ( empty( $cfupgf_fonts ) ) {
		$cfupgf_fonts = cfup_get_google_fonts( $api_key );
		update_option( 'cfupgooglefonts_data', $cfupgf_fonts );
	}
} else {
	$cfupgf_fonts = array();
}

$google_font_data = array();
if ( ! empty( $cfupgf_fonts ) && isset( $cfupgf_fonts->items ) ) {
	foreach ( $cfupgf_fonts->items as $key => $cfupgf_font ) {
		$google_font_data[ $cfupgf_font->family ] = array(
			'font-family' => $cfupgf_font->family,
			'font-file'   => $cfupgf_font->files,
		);
	}
}

// Enqueue the selected google font - save in db.
if ( isset( $_POST['submit-google-fonts'] ) && wp_verify_nonce( $_POST['google-fonts-nonce'], 'cfup-googlefont' ) ) {
	if ( isset( $_POST['font'] ) ) {
		$font = sanitize_text_field( wp_unslash( $_POST['font'] ) );
	}

	$gfonts = get_option( 'googlefont_file_name', true );
	if ( ! is_array( $gfonts ) ) {
		$gfonts = array();
	}
	$gfonts[ $google_font_data[ $font ]['font-family'] ] = $google_font_data[ $font ]['font-file']->regular;
	update_option( 'googlefont_file_name', $gfonts );

	$success_msg  = "<div class='notice updated is-dismissible' id='message'>";
	$success_msg .= '<p>' . esc_html( 'Font Enqueued:', 'cfup' ) . '<strong>' . esc_html( $font, 'cfup' ) . '</strong></p>';
	$success_msg .= '</div>';
	echo $success_msg;
}
$saved_google_fonts = get_option( 'googlefont_file_name', true );
if ( ! is_array( $saved_google_fonts ) ) {
	$saved_google_fonts = array();
}
$sn          = 0;
$div_disable = '';
if ( '' == $api_key && 'no' == $apikey_verified ) {
	$div_disable = 'cfup-google-font-disabled';
}
?>
<div id="wpbody" role="main">
	<div id="wpbody-content" aria-label="Main content" tabindex="0">
	<?php if ( '' == $api_key && 'no' == $apikey_verified ) { ?>
		<?php $general_settings_url = admin_url( 'admin.php?page=custom-font-uploader-settings' ); ?>
			<p class="cfup-google-font-disabled-msg">
				<?php esc_html_e( 'Google API key is missing or is invalid. Please update it in', 'cfup' ); ?>
				<a href="<?php echo esc_attr( $general_settings_url ); ?>">
				<?php esc_html_e( 'general settings!', 'cfup' ); ?> </a></p>
	<?php } ?>
		<div class="wrap <?php echo esc_attr( $div_disable ); ?>">
			<table class="googletbl" width="650" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<h2><?php esc_html_e( 'Select Fonts', 'cfup' ); ?></h2>
						<div class= "gfont">
							<p><?php esc_html_e( 'After selecting and saving font from dropdown it will enqueue particular font in your site', 'cfup' ); ?></p>
							<select name="font" id="googlefont-select" class="webfonts-select" required>
								<option value="">--Select--</option>
								<?php foreach ( $google_font_data as $key => $google_font ) { ?>
									<option value='<?php echo esc_html( $google_font['font-family'], 'cfup' ); ?>'><?php echo esc_html( $google_font['font-family'], 'cfup' ); ?></option>
								<?php } ?>
							</select>
							<p class="submit">
								<?php wp_nonce_field( 'cfup-googlefont', 'google-fonts-nonce' ); ?>
								<input id="submit-cfup-general-settings" name="submit-google-fonts" class="button button-primary" value="<?php esc_html_e( 'Save Font', 'cfup' ); ?>" type="submit">
							</p>
						</div>

						<!--html for previewing fonts-->
				<div class="font-preview-section">
					<h2 class="add_text"><?php esc_html_e( 'H2 tags Preview', 'cfup' ); ?> </h2>
					<h3 class="add_text"><?php esc_html_e( 'H3 tags Preview', 'cfup' ); ?> </h3>
					<p class="add_text"><?php esc_html_e( 'Lorem ipsum dolor sit amet, vide paulo vidisse ex quo, vis dolor pertinax praesent id. No principes disputationi sea, mutat inermis delicatissimi id sed. Est semper moderatius no, et tamquam accommodare his. Wisi numquam scripserit in vix, sumo mandamus moderatius at vim..', 'cfup' ); ?>    <i><?php esc_html_e( 'fast looking italic text?', 'cfup' ); ?></i></p>
				</div>
					</td>
				</tr>
			</table>
	<?php if ( ! empty( $saved_google_fonts ) ) { ?>
				<!--Table structure for deleting google fonts-->
				<table cellspacing="0" class="wp-list-table widefat fixed bookmarks">
					<thead>
						<tr>
							<th width="20"><?php esc_html_e( 'Sn', 'cfup' ); ?></th>
							<th><?php esc_html_e( 'Font', 'cfup' ); ?></th>
							<th width="100"><?php esc_html_e( 'Actions', 'cfup' ); ?></th>
						</tr>
					</thead>
					<tbody>
		<?php foreach ( $saved_google_fonts as $key => $googlefont_name ) { ?>
			<?php $sn++; ?>
							<tr id="delete_googlefont-<?php echo esc_attr( strtolower( preg_replace( '/\s+/', '', $key ) ) ); ?>">
								<td><?php echo esc_html( $sn, 'cfup' ); ?></td>
								<td><?php echo esc_html( $key, 'cfup' ); ?></td>
								<td><a class="delete-googlefont" data-fid="delete_googlefont-<?php echo esc_attr( strtolower( preg_replace( '/\s+/', '', $key ) ) ); ?>" data-delete_font_gkey="<?php echo esc_attr( $key ); ?>"href="javascript:void(0)">Delete</a></td>
							</tr>
	<?php } ?>
					</tbody>
				</table>
	<?php } ?>
		</div>
	</div>
	<div class="clear"></div>
</div>
