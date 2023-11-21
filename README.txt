=== Plugin Name ===
Contributors: (this should be a list of wordpress.org userid's)
Donate link: https://https://www.buildupbookings.com//
Tags: comments, spam
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is an integration into the Hostaway Booking Engine. 

== Description ==

This plugin provides a solution to the limitation of widgets from Hostaway and also provides flexibility in the design.

== Shortcodes ==

[display_properties] - This shortcode will display all properties by default and also provide a filter for easier navigation. There's also an option to only show a specific group of properties but this will disable the filtering option.
* Available attributes
    ** group - This attribute accepts the ID of the group you want to display.
    ** filter - This attributee accepts a boolean which would toggle the filter options in the display properties. (Default: true)

== Installation ==

1. Download the zip file from the repository.
2. Go to the WordPress dashboard and navigate to the plugins page.
3. Click "Add plugin" and upload the zip file then click "Install".
4. Find "Hostaway Listings" from the WordPress Dashboard side menu and provide the necessary information. (Client ID, Client Secret)
5. Click "Save Settings" then after that click "Sync Properties" to load all the properties from Hostaway. This might take a while depending on the number of properties available on Hostaway.

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

== Changelog ==

= 1.1 =
- Added fixes to filtering
- Added fixes to listing images

= 1.0 =
- initial plugin

Ordered list:

1. Property display
2. Filtering
3. Shortcodes

Wishlist:
1. Reviews widget
2. Gallery widget
3. Booking widget
4. Single property page

`<?php code(); // goes in backticks ?>`
