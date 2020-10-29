=== Flags ===

Description:	Allows a 16x16 flag to be displayed in a post or page using a shortcode.
Version:		1.5.0
Tags:			flags, posts, pages
Author:			azurecurve
Author URI:		https://development.azurecurve.co.uk/
Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/flags/
Download link:	https://github.com/azurecurve/azrcrv-flags/releases/download/v1.5.0/azrcrv-flags.zip
Donate link:	https://development.azurecurve.co.uk/support-development/
Requires PHP:	5.6
Requires:		1.0.0
Tested:			4.9.99
Text Domain:	flags
Domain Path:	/languages
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Allows a 16x16 flag to be displayed in a post or page using a shortcode.

== Description ==

# Description

Allows a 16x16 flag to be displayed in a post of page using a &#91;flag&#93; shortcode.

Format of shortcode is &#91;flag=gb&#93; to display the flag of the United Kingdom of Great Britain and Northern Ireland; 247 flags are included.

Defintion of flags can be found at [Wikipedia page ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) the admin settings page lists all flags.

Adding any png file to the plugins /images folder will make them available from the shortcode; this allows the replacement of the included flags with alternatives if required.

[Shortcodes In Comments](https://development.azurecurve.co.uk/classicpress-plugins/shortcode-in-comments/) can be used to allow flags in comments and [Shortcodes In Widgets](https://development.azurecurve.co.uk/classicpress-plugins/shortcode-in-widgets/) can allow them in widgets.

This plugin is multisite compatible.

== Installation ==

# Installation Instructions

 * Download the plugin from [GitHub](https://github.com/azurecurve/azrcrv-flags/releases/latest/).
 * Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
 * Activate the plugin.
 * Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Frequently Asked Questions ==

# Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot fie is in the plugins languages folder and can also be downloaded from the plugin page on https://development.azurecurve.co.uk; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

== Changelog ==

# Changelog

### [Version 1.5.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.5.0)
 * Fix plugin action link to use admin_url() function.
 * Update azurecurve plugin menu.
 * Amend to only load css when shortcode on page.

### [Version 1.4.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.4.0)
 * Add name of Netherlands Antilles flag.
 * Update name of Vatican City, Catalonia and Ulster flags.
 * Add plugin icon and banner.

### [Version 1.3.4](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.3.4)
 * Fix problem with update of Update Manager class to v2.0.0.
 
### [Version 1.3.3](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.3.3)
 * Fix bug with plugin menu.
 * Update plugin menu css.

### [Version 1.3.2](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.3.2)
 * Upgrade azurecurve plugin to store available plugins in options.
 
### [Version 1.3.1](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.3.1)
 * Update Update Manager class to v2.0.0.
 * Update action link.
 * Update azurecurve menu icon with compressed image.
 * Update flags with compressed images.
 
### [Version 1.3.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.3.0)
 * Add image filename to list of available flags.
 * Add flags for Curaçao, South Sudan and Ulster.
 * Replace Libya flag.

### [Version 1.2.1](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.2.1)
 * Fix bug with incorrect language load text domain.

### [Version 1.2.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.2.0)
 * Add integration with Update Manager for automatic updates.
 * Fix issue with display of azurecurve menu.
 * Change settings page heading.
 * Add load_plugin_textdomain to handle translations.
 
### [Version 1.1.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.1.0)
 * Exclude index.php from image listing on admin page.
 * Fix issue with flags not displaying correctly on admin page.
 * Replace Isle of Mann flag with working one.
 * Amend sort order of flags to alphabetical order on admin page.
 * Add name of flags on admin page.
 * Change alt parameter of flag output to display country name.

### [Version 1.0.1](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.0.1)
 * Update azurecurve menu for easier maintenance.
 * Move require of azurecurve menu below security check.

### [Version 1.0.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.0.0)
 * Initial release for ClassicPress forked from azurecurve Flags WordPress Plugin.

== Other Notes ==

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://codepotent.com/classicpress/plugins/update-manager/) by [CodePotent](https://codepotent.com/) for fully integrated, no hassle, updates.

Some of the top plugins available from **azurecurve** are:
* [Add Twitter Cards](https://development.azurecurve.co.uk/classicpress-plugins/add-twitter-cards/)
* [Breadcrumbs](https://development.azurecurve.co.uk/classicpress-plugins/breadcrumbs/)
* [Series Index](https://development.azurecurve.co.uk/classicpress-plugins/series-index/)
* [To Twitter](https://development.azurecurve.co.uk/classicpress-plugins/to-twitter/)
* [Theme Switcher](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/)
* [Toggle Show/Hide](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/)