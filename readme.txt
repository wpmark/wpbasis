=== Plugin Name ===
Contributors: wpmarkuk
Donate link: http://markwilkinson.me/saythanks
Tags: dashboard, utility, framework
Requires at least: 3.9
Tested up to: 4.1
Stable tag: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Basis is a WordPress plugin that speeds up your WordPress development process by providing functions we add to every site.

== Description ==

WP Basis is a WordPress plugin that speeds up your WordPress development process by providing functions we add to every site. It cleans up the dashboard for your clients users providing a simpler version of the WordPress dashboard. It also contains a number of functions, hooks and filters to allow you to amend things in the WordPress dashboard quickly and easily.

== Installation ==

To install the plugin:

1. Upload `wpbasis` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the settings page under Settings > WP Basis

== Frequently Asked Questions ==

None so far!

== Changelog ==

= 1.3.1 =
* Move the meta boxes framework into the old folder. Should not be used in this plugin anymore, instead loaded as a mu-plugin.

= 1.3 =
* Remove site options by default - can be turned on with a filter
* Remove post type descriptions by default - can be turned on with a filter
* Allow wpbasis_featured_img_url to get url for a specific post
* Moves code no longer used into an old folder

= 1.2 =
* adds a filter to allow developers to change the location, within the theme of the login logo.

= 1.1 =
* Adds a filter to allows developers to easily remove the site options page.

= 1.0 =
* Remove the default WordPress update nag
* Add custom update nag with filterable contact URL

= 0.8 =
* Add filter to turn on media buttons for the footer text in the site options screen
* Update tested upto tag in readme.txt file to WordPress 4.0

= 0.7 =
* Post type descriptions are based through wpautop rather than the content filters as anything added to the content filters is outputted with the description such as jetpack sharing items.

= 0.6 =
* Removed pages from having a post type description.

= 0.5 =
* Addition of post type images in the post type description screen. Good for use as a banner image for post type archives

= 0.4 =
* Allow site options to be more easily removed from the site options admin screen
* Corrected a typo in an array on the site options screen

= 0.2 =
* Renamed the post type descriptions front end function as the function name made little sense.
* Corrected an error where two filters had the same name in the admin area.

= 0.1 =
* Initial Release

== Upgrade Notice ==
Update through the WordPress admin as notified.