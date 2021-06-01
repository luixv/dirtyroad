=== BuddyPress Default Cover Photo ===
Contributors: seventhqueen
Tags: BuddyPress, default cover, profile cover, avatar, group cover, cover, members, groups
Requires at least: 4.1
Tested up to: 5.3.2
Stable tag: 1.6.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
The plugin adds DEFAULT Profile and Group cover settings in WP Admin - Settings - BuddyPress - Settings.

You also have the option to replace default BuddyPress cover photo and use this plugin for the cover functionality. 


Check out this demo to see it in action: 
http://seventhqueen.com/themes/kleo/members/kleoadmin/

== Installation ==
= To Install: =

1.  Download the plugin file
2.  Unzip the file into a folder on your hard drive
3.  Upload the `/buddypress-cover-photo/` folder to the `/wp-content/plugins/` folder on your site
4.  Single-site BuddyPress go to Plugins menu and activate there.
5.  For Multisite visit Network Admin -> Plugins and Network Activate it there.

== Frequently Asked Questions ==

= What other configurations do I need =
No. This plugin needs no configuration.

= How do I set up default cover images =
From WP admin - Settings - BuddyPress - Settings you can set default images for profile and groups.


== Changelog ==

= 1.6.0 =
* Fixed customizer error when BPCP plugin was active

= 1.5.1 =
- Changed some typos in the plugin description

= 1.5 =
* Plugin is now properly prepared for localisation so it can get automatic translation updates trough WordPress https://translate.wordpress.org

= 1.4.1 =
* BuddyPress 2.6 compatibility added

= 1.3 =
* Added option to replace Buddypress functionality with the plugin in Settings - BuddyPress - Settings

= 1.2 =
* Added fallback to Buddypress 2.4 core functionality for Profile/Group Cover. Right now having the plugin active will use BP functionality and use to the old uploaded image until you upload a new one with the new BP interface. The plugins screens are no longer users in BuddyPress 2.4 and we only kept the default image setttings in the WP Admin - Settings - Buddypress - Settings area
* Added Catalan translation

= 1.1.4 =
* Change to the group cover tint inner layer to show also when the user isn't logged in. thanks @sharmstr
* Updated translation files

= 1.1.3 =
* Some extra checks when saving just the position so it won't remove the existing image
* Some fixes to allow other themes to filter the backround html tag

= 1.1.2 =
* Fixed header info section of the plugin that was causing some install problems

= 1.1.1 =
* Fixed a BuddyPress Group editing issue from admin area as in: https://wordpress.org/support/topic/compatibility-problem-with-buddypress
* Added html tag filter to hook into the background element if theme developers need to

= 1.1 =
* Added Group cover
* Added settings for default profile and group covers in Settings - BuddyPress - Settings screen

= 1.0.5 =
* Fixed translations

= 1.0.4 =
* Allow admin to set covers for members

= 1.0.3 =
* Initial release