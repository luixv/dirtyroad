<?php
/**
 * BuddyPress - Members Notifications Loop
 *
 * @since 3.0.0
 * @version 3.1.0
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
<div id="buddypress" class="buddypress-wrap bp-dir-hori-nav <?php echo esc_attr( $notifications_atts['container_class'] ); ?>">
	<input type="hidden" data-bp-filter="notifications" value="<?php echo esc_attr( $notifications_atts['bpsh_query'] ); ?>" />		
	<?php
	if ( bp_has_notifications( $args ) ) :

		bp_nouveau_pagination( 'top' );
		?>

		<form action="" method="post" id="notifications-bulk-management" class="standard-form">
			<table class="notifications bp-tables-user">
				<thead>
					<tr>
						<th class="icon"></th>
						<th class="bulk-select-all"><input id="select-all-notifications" type="checkbox"><label class="bp-screen-reader-text" for="select-all-notifications"><?php esc_html_e( 'Select all', 'buddypress' ); ?></label></th>
						<th class="title"><?php esc_html_e( 'Notification', 'buddypress' ); ?></th>
						<th class="date">
							<?php esc_html_e( 'Date Received', 'buddypress' ); ?>
							<?php bp_nouveau_notifications_sort_order_links(); ?>
						</th>
						<th class="actions"><?php esc_html_e( 'Actions', 'buddypress' ); ?></th>
					</tr>
				</thead>

				<tbody>

					<?php
					while ( bp_the_notifications() ) :
						bp_the_notification();
						?>

						<tr>
							<td></td>
							<td class="bulk-select-check"><label for="<?php bp_the_notification_id(); ?>"><input id="<?php bp_the_notification_id(); ?>" type="checkbox" name="notifications[]" value="<?php bp_the_notification_id(); ?>" class="notification-check"><span class="bp-screen-reader-text"><?php esc_html_e( 'Select this notification', 'buddypress' ); ?></span></label></td>
							<td class="notification-description"><?php bp_the_notification_description(); ?></td>
							<td class="notification-since"><?php bp_the_notification_time_since(); ?></td>
							<td class="notification-actions"><?php bp_the_notification_action_links(); ?></td>
						</tr>

					<?php endwhile; ?>

				</tbody>
			</table>

			<?php wp_nonce_field( 'notifications_bulk_nonce', 'notifications_bulk_nonce' ); ?>
		</form>

		<?php bp_nouveau_pagination( 'bottom' ); ?>

	<?php else : ?>

		<?php bp_nouveau_user_feedback( 'member-notifications-none' ); ?>

	<?php endif; ?>
</div>
