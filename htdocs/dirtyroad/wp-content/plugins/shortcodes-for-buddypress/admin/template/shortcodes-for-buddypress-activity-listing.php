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
 * @subpackage Shortcodes_For_Buddypress/admin/template
 */
?>
<div class="wb_shortcodes_description">
	<div class="description-title"></div>
	<p><?php esc_html_e( 'BuddyPress Activity Shortcode allows you to embed BuddyPress activities in posts/pages using shortcodes.', 'shortcodes-for-buddypress' ); ?></p>
	<p><?php esc_html_e( 'The [activity-listing] is work as same as site-wide activity page work. ', 'shortcodes-for-buddypress' ); ?>
	</p>
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
				<td><?php esc_html_e( 'page', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( '1', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'Which page of results to fetch. Using page=1 without per_page will result in no pagination.',
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
					'Number of results per page.',
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
					'Maximum number of results to return.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'count_total', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'true', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					'An additional DB query is run to count the total activity items for the query.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'sort', 'shortcodes-for-buddypress' ); ?></td>
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
				<td><?php esc_html_e( 'exclude', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					' Array of activity IDs to exclude.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'in', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					' Array of IDs to limit query by (IN). "in" is intended to be used in conjunction with other filter parameters.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'include', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				esc_html_e(
					' Array of exact activity IDs to query.
						Providing an "include" array will override all other filters passed in the argument array.
						When viewing the permalink page for a single activity item, this value defaults to the ID of
						that item.',
					'shortcodes-for-buddypress'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'display_comments', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'threaded', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php
				echo wp_kses_post(
					'How to handle activity comments. Possible values:
					- "threaded" - comments appear in a threaded tree, under their parent items.
					- "stream" - the activity stream is presented in a flat manner, with comments
								sorted in chronological order alongside other activity items.
					- false - don\'t fetch activity comments at all.
					'
				);
				?>
			</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'scope', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php

				echo wp_kses_post(
					'Use a BuddyPress pre-built filter.
					- "just-me" - retrieves items belonging only to a user; this is equivalent to passing a "user_id" argument.
					- "friends" - retrieves items belonging to the friends of a user.
					- "groups" - retrieves items belonging to groups to which a user belongs to.
					- "favorites" - retrieves a user\'s favorited activity items.
					 - "mentions" - retrieves items where a user has received an @-mention.
					'
				);
				?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'object', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Filters by the `component` column in the database, which is generally the component ID in the case of BuddyPress components, or the plugin slug in the case of plugins.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'user_id', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int|array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'The ID(s) of user(s) whose activity should be fetched.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'action', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Filters by the `type` column in the database.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'primary_id', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int|array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Filters by the `item_id` column in the database.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'secondary_id', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int|array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Filters by the `secondary_item_id` column in the database.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'offset', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'int|array|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Return only activity items with an ID greater than or equal to this one.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'show_hidden', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'false', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Whether to show items marked hide_sitewide.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'spam', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'string|bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'ham_only', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Spam status. "ham_only", "spam_only", or false to show all activity regardless of spam status.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'populate_extras', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'bool', 'shortcodes-for-buddypress' ); ?></td>
				<td><?php esc_html_e( 'true', 'shortcodes-for-buddypress' ); ?></td>
				<td>
				<?php esc_html_e( 'Whether to pre-fetch the activity metadata for the queried items.', 'shortcodes-for-buddypress' ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
