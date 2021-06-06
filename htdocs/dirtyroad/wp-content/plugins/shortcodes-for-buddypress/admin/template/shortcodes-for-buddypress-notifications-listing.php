<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wbcomdesigns.com/plugins
 * @since      1.0.0
 *
 * @package    Shortcodes_For_Buddypress
 * @subpackage Shortcodes_For_Buddypress/admin/partials
 */
?>
  
<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wbcomdesigns.com/plugins
 * @since      1.0.0
 *
 * @package    Shortcodes_For_Buddypress
 * @subpackage Shortcodes_For_Buddypress/admin/partials
 */
?>
<div class="wb_shortcodes_description">
	<div class="description-title"></div>
	<p><?php esc_html_e( 'The [notifications-listing] shortcode allows you to display listing of notifications in posts/pages.', 'shortcodes-for-buddypress' ); ?></p>
</div>
<div class="bp-shortcode-attributes shortcode-attributes">
  <table class="widefat fixed striped buddypress-shortcode-lists">
	<thead>
		<tr>
			<td><?php esc_html_e( 'Attribute', 'shortcodes-for-buddypress' ); ?></td>
			<td><?php esc_html_e( 'Type', 'shortcodes-for-buddypress' ); ?></td>
			<td><?php esc_html_e( '	Default', 'shortcodes-for-buddypress' ); ?></td>
			<td><?php esc_html_e( 'Description', 'shortcodes-for-buddypress' ); ?></td>
		</tr>
	</thead>
		<tbody>
			<tr>
				<td><?php esc_html_e( 'title', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'The title for the notification listing. Title will display on the top of the listing.', 'shortcodes-for-buddypress' ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'order', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'DESC', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Sort notifications by "ASC" or "DESC" ',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'page', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '1', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Page offset of results to return.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'per_page', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '20', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					' Number of items to return per page of results.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'max', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false ( unlimited )', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Does NOT affect query. May change the reported number of total notifications
					found, but not the actual number of found notifications. Default: false.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'container_class', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'notification', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'The container class of notification listing loop.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
