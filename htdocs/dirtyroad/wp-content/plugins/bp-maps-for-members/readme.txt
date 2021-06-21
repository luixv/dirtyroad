=== BP Maps for Members ===
Contributors: shanebp
Donate link: https://www.philopress.com/donate/
Tags: buddypress, members, maps, buddyboss
Author: PhiloPress
Author URI: https://philopress.com/contact/
Plugin URI: https://philopress.com/products/
Requires at least: 4.1
Tested up to: 5.5.1
Stable tag: 6.9
Copyright (C) 2016-2021  shanebp, PhiloPress

Create and Display Maps for Members in BuddyPress or the BuddyBoss Platform

== Description ==

This plugin creates a Map for all Members and for each Member on their Profile, for BuddyPress or the BuddyBoss Platform.

Features:

* if a Member enters a valid address in their Profile, a map will be available on the 'Location' tab, and they will appear on the Members Directory map
* On the Members Directory page, the 'Map' tab will show all Public Members that have a location
* a shortcode for use on any page.  [membersmap] will show the All Members map
* if you have Member Types, an option is provided to show a filter on the All Members map
* an option is provided to show a distance filter
* if the BP Profile Search plugin, version 5.0 or greater, is activated, an option is provided to use a BPS filter form


For more plugins, please visit https://www.philopress.com/

== Installation ==

1. Upload the zip on the Plugins > Add screen in wp-admin

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Go to Settings -> BP Maps Members and enter your License Key  AND enter your Google Maps API Key. If you don't have a Key, see FAQ.  Adjust other settings to your liking.

4. Install and activate  the free [BP xProfile Location](https://www.philopress.com/products/bp-xprofile-location/ "BP xProfile Location") plugin

5. Go to wp-admin > Users > Profile Fields and create a field of type Location

6. On the front-end of your site, Go to your Profile > Edit and enter an address


== Frequently Asked Questions ==

= Do I need a Google Maps API Key? =
Yes. If you need help, read this tutorial [Google Maps API Key](https://www.philopress.com/google-maps-api-key/ "Google Maps API Key")


= MultiSite support? =

Yes. Network Activated or Site Activated


= Shortcode?  =

Yes. There is a shortcode for the All Members map:  [membersmap]


= Can I add data to the map pin popup? =

Yes. Read this example: bp-maps-for-members/readme-add-popup-field.txt


= Is there a filter hook for member types to be displayed? =

Yes.
$member_type = apply_filters( 'bp_maps_for_members_type_filter', $member_type );
$member_type can be an array of types or a single type as a string


= BP Profile Search support? =

Yes - for addresses created by the BP xProfile Location plugin from PhiloPress.
Both string and distance searches are supported.
See the BP Profile Search option  near the bottom of this page: wp-admin/options-general.php?page=bp-maps-for-members

To use a BP Profile Search form on a page using the [membersmap] shortcode, follow these directions:
   Create a new BPS form, select as its Directory (Results Page) the page containing the Members Map shortcode [membersmap], and set Add to Directory to Yes.
   Then add the BPS shortcode to the page that holds the [membersmap] shortcode


== Changelog ==

= 6.9 =
* select which Member Types appear on the All Members Map

= 6.8 =
* the Member Types filter now shows the Member Type name instead of the slug

= 6.7 =
* fixes a bug re location field options not being saved properly

= 6.6 =
* fixes a bug re BuddyBoss Platform when their Profile Search is not enabled

= 6.5 =
* add distance support for search with the BP Profile Search and BuddyBoss Platform > Profile Search

= 6.4 =
* improved performance of calls to the Google Maps APIs - important!

= 6.3 =
* Add support for member maps with a single group
* This addition is only relevant if you have version 5.0 or higher of the BP Maps for Groups plugin

= 6.2 =
* Tested in WP 5.4
* Improved support for the BuddyBoss Platform

= 6.1 =
* Improved and simpified support for the BP Profile Search plugin

= 6.0 =
* Add a new template for the map pin popup: bp-maps-for-members\templates\members\members-map-item.php
* Add the option to search on the map via keywords
* General template cleanup

= 5.3 =
* Fix bug re 'Limit Number of Members' value in Settings. It was being ignored.

= 5.2 =
* Fix bug re valid zoom value in Settings
* Fix bug re Warning re invalid argument when no members are found

= 5.1 =
* Include a 'Max Zoom Level' option of 18 for the BuddyPress Members Directory Map' on the settings page.

= 5.0 =
* Add support for the BP Profile Search plugin, including a distance filter. See ReadMe FAQs for more info.

= 4.1 =
* Change enqueue handle re Google Maps

= 4.0 =
* Added a Member Distance filter for Members Map. This filter is used for Radial Searches

= 3.2 =
* Added a Member Type filter for Members Map

= 3.1 =
* Fix bug re showing Location fields in the Setting screen in wp-admin

= 3.0 =
* Tested with WP 5.0.1
* Improved support for multisite installations
* Requires version 1.8 of BP xProfile Location plugin

= 2.2 =
* Added shortcode [membersmap] for use on any page.  [membersmap] will show the All Members map

= 2.1 =
* Added License Key

= 2.0 =
* Added requirement for Google Maps API Key

= 1.0 =
* Initial release.

