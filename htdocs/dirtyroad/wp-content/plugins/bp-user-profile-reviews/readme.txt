=== BuddyPress Member Reviews ===
Contributors: wbcomdesigns, vapvarun
Donate link: https://wbcomdesigns.com/donate/
Tags: buddypress, members
Requires at least: 4.0
Tested up to: 5.6.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

This plugin  allows only site members to add reviews to the buddypress members on the site. But the member can not review itself. And if the visitor is not logged in, he can only see the listing of the reviews but can not review.  The review form allows the members to even rate the member's profile out of 5 points with multiple review criteria.

You can add multiple criteria for review. And you can change the positions of those Criteria.  Review form shows on the member's profile but you can show review form on another page just by shortcode. BP member profile review plugin adds a widget for display top reviewed member listing .

It is built to be reliable, scalable, secure and flexible. We have worked hard to make it easy to use and we will love your feedback in making it better.

= Links =

*	[Plugin url](https://wbcomdesigns.com/downloads/buddypress-user-profile-reviews/ "BuddyPress Member Review" )
*	[Demo]( https://demos.wbcomdesigns.com/wbcomplugins/login "BuddyPress Member Review Demo")
*	[Support](https://wbcomdesigns.com/helpdesk/article-categories/buddypress-user-profile-reviews/)
*	[Github Development Repo](https://github.com/wbcomdesigns/buddypress-member-review/)


== Installation ==

1. Upload the entire "buddypress-member-review" folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Where do I get support?
We request you to use [Wbcom Designs](https://wbcomdesigns.com/contact/) for all the support questions. We love helping. Using Wbcom Designs allows us to assist you better and quicker.

= How can we sumit review to a member profile by using this plugin? =

When visiting the "/members" section in the site, go for single profile view page, there you can see a menu namely, "Reviews" that will allow you (if you're a site member) to add profile review.

= How can we add more rating criteria for review form ? =

Just go to "Dashboard->Review->BP member review setting page" and click Criteria tab and click Add criteria button and click save setting button to update review criteria settings.

= How can I delete Criteria when I do not need it ? =

Just go to "Dashboard->Review->BP member review setting page" and click Criteria tab and you can see there are many settings like delete the Criteria or make them enable/desable or change their position.

= What is the Top Members widget and how to use it ? =

Members Review widgets display list of members on site front-end . When you successfully activate Buddypress member review plugin. Then you can see Members reivew widget in the widget section.

= Can I use the review form on any other page? =

Yes you can use the review form on other page, just go to "Dashboard->Review->BP member review setting page" and click shortcode tab and copy review from shortcode and paste it on the other page.

== Screenshots ==
1. Members Directory View
2. Members Single Profile View
3. Members Add Review section
4. Plugin Settings

== Changelog ==
= 2.4.1 =
* Fix: New reviews tab should follow the same slug as parent.
* Fix: BP nav bar link will also use the dynamic review slug from options.

= 2.4.0 =
* Fix: ( #123) Allow to change review url when label is changed
* Fix: (#122) Fixed escaping functions
* Fix: (#110) Updated Language file and changed the strings
* Fix: (#103) Fixed hide review button in backend settings

= 2.3.0 =
* Fix: (#76) Fixed php notices and warnings
* Fix: (#74) Fixed review action is showing in notification
* Fix: #72)Changed review date format
* Fix: (#73) Fixed blank bbpress notifications

= 2.2.0 =
* Fix: Remove redclare function.
* Fix: (#64) updated message string on bad review.
* Fix: (#57) Fix admin does not get notification to approve the review.
* Fix: (#63) Fix filter tabs are not working.
* Fix: (#62) Fix Anonymous Review

= 2.1.0 =
* Fix - (#59) Update style
* Fix - (#53) Member ratings and Top Members ratings UI managed
* Fix - Added rating widget.
* Fix - (#52)-Date (when the review is posted) issue.

= 2.0.1 =
* Fix - Compatibility with BuddyPress 4.3.0. #48
* Fix - Fixed file not found error.

= 2.0.0 =
* Fix - Compatibility with BuddyPress 4.1.0. #43
* Fix - BuddyPress notification issue.
* Fix - Fixed Translation string issue.
* Enhancement - Improve Backend UI where you can manage all wbcom plugin's settings at one place.
* Enhancement - Added plural label for Review.
* Enhancement - Added shortcode for display top reviews on page.
* Enhancement - Added French translation files – credits to Jean Pierre Michaud

= 1.0.8 =
* Fix - Undefined variable issue in js code.

= 1.0.7 =
* Enhancement - Added admin setting to enable/disable review listing at member directory.
* Enhancement - Added admin setting to enable/disable View Review link at member directory.
* Enhancement - Added option for sending reviews anonymously with admin enable/disable setting.
* Fix - Ajax response issue while saving admin settings under General setting tab.

= 1.0.6 =
* Fix - Admin Styles css and js files are not applied

= 1.0.5 =
* Enhancement - Code Quality Improvment with WPCS
* Fix - 404 errors when BP member page slug is different

= 1.0.4 =
* Enhancement - Add support for member directory
* Enhancement - Added Google Rating Snippet meta tags
* Enhancement - Simplified rating view on single member profile
* Fix - Removed JS tab for add review and added real sub nav to add reviews

= 1.0.3 =
* Fix - Review header cleanup

= 1.0.2 =
* Fix - Added validation for shortcode review form and member review form for both condition with & without criteria fields.
* Fix - Remove number on hover of bar rating in member's header section & in plugin widget.
* Fix - UI improvement in plugin admin settings.
* Enhancement - Added functionality of multiple review.

= 1.0.1 =
* Layout Improvement
* Added Auto approve reviews options
* Added Email Notification options
* Added BuddyPress Notifcation options
* Added Exclude Members for review options
* Removed option to rate yourself

== Upgrade Notice ==
= 1.0.0 =
This version is the initial version of the plugin with basic review adding functionality.
