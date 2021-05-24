<?php
/**
 * BuddyPress Member Review display tab.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $bupr;
?>
<div class="wbcom-tab-content">
	<form method="post" action="options.php">
		<?php
		settings_fields( 'bupr_admin_display_options' );
		do_settings_sections( 'bupr_admin_display_options' );
		?>
		<p class="description">
			<b><?php esc_html_e( 'Note', 'bp-member-reviews' ); ?>:</b>
			<?php esc_html_e( 'If and only if you have added a criteria for review.', 'bp-member-reviews' ); ?>
		</p>
		<h2 class="title">
			<?php esc_html_e( 'Labels', 'bp-member-reviews' ); ?>
		</h2>
		<table class="form-table">		
			<tbody>
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Review', 'bp-member-reviews' ); ?></label>
					</th>
					<td>
						<input type="text" name="bupr_admin_display_options[bupr_review_title]" id="bupr_member_tab_title" value="<?php echo esc_attr( $bupr['review_label'] ); ?>">
						<p class="description">
							<?php esc_html_e( 'Change Labels from BuddyPress tab and review form.', 'bp-member-reviews' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Review( Plural )', 'bp-member-reviews' ); ?></label>
					</th>
					<td>
						<input type="text" name="bupr_admin_display_options[bupr_review_title_plural]" id="bupr_member_tab_title_plural" value="<?php echo esc_attr( $bupr['review_label_plural'] ); ?>">
						<p class="description">
							<?php esc_html_e( 'This option provides flexibility to change plural of Review.', 'bp-member-reviews' ); ?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<h2 class="title">
			<?php esc_html_e( 'Colors ', 'bp-member-reviews' ); ?>
		</h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php esc_html_e( 'Rating color', 'bp-member-reviews' ); ?></label>
					</th>
					<td>
						<input type="text" name="bupr_admin_display_options[bupr_star_color]" id="bupr_display_color" class="bupr-admin-color-picker" value="<?php echo esc_attr( $bupr['rating_color'] ); ?>">
						<p class="description">
							<?php esc_html_e( 'This option lets you to change star rating color.', 'bp-member-reviews' ); ?>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
	</form>
</div>					

