=== Reading Time ===
Contributors: whiletrue
Donate link: http://www.whiletrue.it/
Tags: reading time, estimate time, reading, time, word count, post, page
Requires at least: 2.9+
Tested up to: 4.2
Stable tag: 1.2.9

Reading Time shows the estimated reading time and puts an animated progress bar inside the post. 

== Description ==
Reading Time shows the estimated reading time of the post, in seconds or minutes. 
The estimate is automatic; a custom value for a single post can be inserted using a Custom Field named "readingtime".

For more info: http://www.whiletrue.it/reading-time-for-wordpress/

== Installation ==
1. Upload `reading_time.php` and `unisntall.php` into the `/wp-content/plugins/reading_time` directory
2. Set your favourite values in the `Settings->Reading Time` menu in Wordpress
3. Activate the plugin through the `Plugins` menu in WordPress
4. Enjoy!

== Frequently Asked Questions ==
= How is the estimation done? =
The estimation is based on the "speed" value stored in the settings menu.
The value for speed should be chosen between a span of 150 words per minute (slightly slower than average) to 250 words per minute (slightly higher than average); if not set, a default value of 200 is used.

== Screenshots ==
1. Sample content with the progress bar almost filled


== Features In Next Release ==
* Display setting: always / only for selected tags or categories / only when the `readingtime` custom tag is set


== Changelog ==

= 1.2.9 =
* Plugin tested up WordPress 4.2

= 1.2.8 =
* Plugin tested up WordPress 4.1

= 1.2.7 =
* Added: "readingtime_text" CSS class

= 1.2.6 =
* Added: Donate link
* Changed: Readme update

= 1.2.5 =
* Plugin tested up WordPress 3.8

= 1.2.4 =
* Added: Reading time in minutes option

= 1.2.3 =
* Plugin tested up WordPress 3.6

= 1.2.2 =
* Changed: Default speed set to 200 words per minute

= 1.2.1 =
* Fixed: Uninstall bugfix

= 1.2.0 =
* Added: New progress bar display setting
* Fixed: Settings sanitization

= 1.1.0 =
* Added: Default settings value
* Added: New position setting, above or below the post
* Added: Uninstall function

= 1.0.0 =
Initial version


== Upgrade Notice == 

= 1.2.0 =
No upgrade issues known

= 1.1.0 =
No upgrade issues known

= 1.0.0 =
Initial version
