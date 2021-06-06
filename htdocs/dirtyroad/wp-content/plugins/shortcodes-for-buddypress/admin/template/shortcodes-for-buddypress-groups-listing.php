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
	<p><?php esc_html_e( ' The [groups-listing] shortcode allows you to display listing of groups in posts/pages.', 'shortcodes-for-buddypress' ); ?></p>
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
				<td><?php esc_html_e( 'alphabetical', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Shorthand for certain orderby/order combinations. "newest", "active",
					"popular", "alphabetical", "random". When present, will override
					orderby and order params.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>			
			<tr>
				<td><?php esc_html_e( 'order', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'DESC', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Sort activies by "ASC" or "DESC" ',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'orderby', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'last_activity', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Property to sort by. "date_created", "last_activity",
					"total_member_count", "name", "random".',
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
					'Does NOT affect query. May change the reported number of total groups
					found, but not the actual number of found groups. Default: false.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'show_hidden', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Whether to include hidden groups in results.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'page_arg', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'grpage', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Query argument used for pagination.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'user_id', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'If provided, results will be limited to groups of which the specified user is a member.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'slug', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'If provided, only the group with the matching slug will be returned.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'search_terms', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'If provided, only groups whose names or descriptions match the search terms will be returned.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'group_type', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					' Array or comma-separated list of group types to limit results to.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'group_type__in', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '-', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of group types to limit results to.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'group_type__not_in', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of group types that will be excluded from results.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'meta_query', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'An array of meta_query conditions.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'include', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of group IDs. Results will be limited to groups within the list.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'exclude', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of group IDs. Results will exclude the listed groups.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'parent_id', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'null', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Array or comma-separated list of group IDs. Results will include only child groups of the listed groups.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'update_meta_cache', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'true', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Whether to fetch groupmeta for queried groups.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'container_class', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'group', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'The container class of group listing loop.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
