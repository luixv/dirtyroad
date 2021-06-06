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
	<p><?php esc_html_e( 'The [members-listing] shortcode allows you to display listing of members listing in posts/pages.', 'shortcodes-for-buddypress' ); ?></p>
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
				<td><?php esc_html_e( 'The title for the activities. Title will display on the top of the activities.', 'shortcodes-for-buddypress' ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'type', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'active', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Sort order. Accepts "active", "random", "newest", "popular", "online", "alphabetical".',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>			
			<tr>
				<td><?php esc_html_e( 'page', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int|bool ', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'Page of results to display.', 'shortcodes-for-buddypress' ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'per_page', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '20', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e( 'Number of results per page.', 'shortcodes-for-buddypress' );
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
					'Maximum number of results to return.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'page_arg', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'upage', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'The string used as a query parameter in pagination links.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'include', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|int|string|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Limit results by a list of user IDs. Accepts an array, a
					single integer, a comma-separated list of IDs, or false (to
					disable this limiting). Accepts "active", "alphabetical","newest", or "random".',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'exclude', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|string|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Exclude users from results by ID. Accepts an array, a single
					integer, a comma-separated list of IDs, or false (to disable
					this limiting).',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'user_ids', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|string|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'An array or comma-separated list of IDs, or false (to
					disable this limiting).',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'user_id', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '0', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'If provided, results are limited to the friends of the specified user.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'member_type', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|array', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of member types to limit results to.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'member_type__in', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|array', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of member types to limit results to.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'member_type__not_in', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|array', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of member types to exclude from results.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'include_member_role', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|array', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of member role to include in results.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'exclude_member_role', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|array', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of member role to exclude in results.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'search_terms', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Limit results by a search term',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'meta_key', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'  Limit results by the presence of a usermeta key.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'meta_value', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'mixed', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'  When used with meta_key, limits results by the a matching usermeta value.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'populate_extras', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'true', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Whether to fetch optional data, such as friend counts.',
					'shortcodes-for-buddypress'
				);
				?>
			</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'container_class', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'members', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'The class of conainer of member loop.',
					'shortcodes-for-buddypress'
				);
				?>
			</td>
			</tr>
		</tbody>
	</table>
</div>
