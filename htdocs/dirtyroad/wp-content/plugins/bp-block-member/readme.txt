=== BuddyBlock ===
Contributors: philopress.com
Author URI: https://philopress.com/contact/
Plugin URI: https://philopress.com/products/
Requires at least: 4.0
Tested up to: 5.2
Stable tag: 4.2
Copyright (C) 2013-2019  shanebp, PhiloPress

== Description ==
BuddyBlock is a BuddyPress plugin.

See admin page under Settings -> BuddyBlock

Creates a 'Block' / 'UnBlock' button on profile pages of other members, next to the 'Private Message' button.
Creates a 'Block' / 'UnBlock' button on member loops and group member loops.
Create a profile screen for each member, under Settings > Blocked Members, showing all the members they have blocked.
Each member, under  Settings > Blocked Members can chose to hide Member Types.
So if there are Man and Woman member types and you only want to see Women in the Members Directory - Select 'Men' to hide all men.

If you Block another member, you will not appear to that member on, so far:
1. Members Page
2. Activity Page
3. Group Members Page

Member Count is NOT adjusted on #1 or #3

If a blocked member tries to access your profile page, they will be redirected to your site home url.

If a blocked member tries to send you a private message or reply, they will see a custom error.




== Installation ==

1. Unzip and then upload the 'bp-block-member' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Active your License by entering the Key on Settings > BuddyBlock

Note:
Activating this plugin will create a new table.
Deactivation will _not_ remove the table.
Deleting this plugin will remove the table.


CSS:
On themes where the Block button requires more space, you can copy the example
css ruleset from the folder /css/buddy-block.css to your themes stylesheet to add vertical spacing.

== Frequently Asked Questions ==

= Can I add a Block button somewhere else ? =
Yes.
$target_id = 2;  // you need to set the target ID - DO NOT USE the current user ID
BP_Block_Member::get_instance()->single_block_button( $target_id );

= Can I filter the Block button ? =
Yes, by using this filter hook:  apply_filters( 'pp_bp_block_button', $pp_bp_block_button, $target_id, $action, $style );
You can also write some css for: class="block-button"

= Youzer ? =
No. This plugin no longer includes support for the Youzer profiles plugin.

= Multisite
Supported for both network and single site activation

== Changelog ==

= 4.2 =
* tested with WP 5.2
* fix bug re option to show a prompt when blocking a member

= 4.1 =
* added support for Youzer
* added a filter for the block button

= 4.0 =
* tested with WP 5.1
* fix bug that allowed @mentions to create a notification and send an email from a blocked member.
* add a Settings option "Show Prompt on Block". If selected, an 'Are you sure?' prompt will appear when a member tries to block somebody.

= 3.0 =
* tested with WP 5.0.2
* improved support for multisite installations

= 2.1 =
* fix possible security issue

= 2.0 =
* add License Key

= 1.7 =
* add support for BuddyBoss Members Types display of a Member Type on a Page via a shortcode

= 1.6 =
* add ability to hide Member Types
* add ability to set a custom redirect url

= 1.4 =
* close the recent XSS vulnerability found in add_query_arg and remove_query_arg

== Upgrade Notice ==

= 4.2 =
* tested with WP 5.2
* fix bug re option to show a prompt when blocking a member

= 4.1 =
* added support for Youzer
* added a filter for the block button

= 4.0 =
* tested with WP 5.1
* fix bug that allowed @mentions to create a notification and send an email from a blocked member.
* add a Settings option "Show Prompt on Block". If selected, an 'Are you sure?' prompt will appear when a member tries to block somebody.

= 3.0 =
* tested with WP 5.0.2
* improved support for multisite installations

= 2.1 =
* fix possible security issue

= 2.0 =
* add License Key

= 1.7 =
* add support for BuddyBoss Members Types display of a Member Type on a Page via a shortcode


= 1.6 =
* add ability to hide Member Types
* add ability to set a custom redirect url

= 1.4 =
* close the recent XSS vulnerability found in add_query_arg and remove_query_arg
