<?php
/**
 * BuddyPress - Members Notifications Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 * @version 3.0.0
 */

global $notifications_atts;


$user_id = ( isset( $notifications_atts['user_id'] ) && $notifications_atts['user_id'] != '' ) ? : get_current_user_id();
$args    = array(
	'user_id'    => $user_id,
	'is_new'     => true,
	'page'       => $notifications_atts['page'],
	'per_page'   => $notifications_atts['per_page'],
	'max'        => $notifications_atts['per_page'],
	'sort_order' => $notifications_atts['order'],
);

add_filter(
	'bp_current_action',
	function( $current_action ) {
		return 'unread';
	}
);
?>
<div id="buddypress" class="<?php echo esc_attr( $notifications_atts['container_class'] ); ?>">

	<?php if ( bp_has_notifications( $args ) ) : ?>

		<h2 class="bp-screen-reader-text">
		<?php
			/* translators: accessibility text */
			esc_html_e( 'Unread notifications', 'buddypress' );
		?>
		</h2>

		<div id="pag-top" class="pagination no-ajax">
			<div class="pag-count" id="notifications-count-top">
				<?php bp_notifications_pagination_count(); ?>
			</div>

			<div class="pagination-links" id="notifications-pag-top">
				<?php bp_notifications_pagination_links(); ?>
			</div>
		</div>

		<?php bp_get_template_part( 'members/single/notifications/notifications-loop' ); ?>

		<div id="pag-bottom" class="pagination no-ajax">
			<div class="pag-count" id="notifications-count-bottom">
				<?php bp_notifications_pagination_count(); ?>
			</div>

			<div class="pagination-links" id="notifications-pag-bottom">
				<?php bp_notifications_pagination_links(); ?>
			</div>
		</div>

	<?php else : ?>

		<?php bp_get_template_part( 'members/single/notifications/feedback-no-notifications' ); ?>

	<?php endif; ?>
</div>
