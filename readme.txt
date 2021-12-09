=== Flags ===

Description:	Allows a scalable SVG flag to be displayed in a post or page using a shortcode.
Version:		1.10.1
Tags:			flags, posts, pages
Author:			azurecurve
Author URI:		https://development.azurecurve.co.uk/
Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/flags/
Download link:	https://github.com/azurecurve/azrcrv-flags/releases/download/v1.10.1/azrcrv-flags.zip
Donate link:	https://development.azurecurve.co.uk/support-development/
Requires PHP:	5.6
Requires:		1.0.0
Tested:			4.9.99
Text Domain:	flags
Domain Path:	/languages
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Allows a scalable SVG flag to be displayed in a post or page using a shortcode.

== Description ==

# Description

Flags allows a scalable SVG flag to be displayed in a post or page using the `[flag]` shortcode

The shortcode usage is `[flag id="gb" width="20px" border="1px solid black"]` where the `id` is the country code shown below; width and border are optional paramaters and can be defaulted from the settings. Shortcode usage of `[flag="gb"]` where default parameters are to be used is also supported.

Defintion of flags can be found at [Wikipedia page ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) (although some additional flags have been included).

Custom flags can be added; if a custom flag with the same name as a standard flag exists, the custom flag will be used.

[Shortcodes In Comments](https://development.azurecurve.co.uk/classicpress-plugins/shortcode-in-comments/) can be used to allow flags in comments and [Shortcodes In Widgets](https://development.azurecurve.co.uk/classicpress-plugins/shortcode-in-widgets/) can allow them in widgets.

This plugin is multisite compatible.

== Installation ==

# Installation Instructions

 * Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/azrcrv-flags/releases/latest/).
 * Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
 * Activate the plugin.
 * Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Frequently Asked Questions ==

# Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugins languages folder/; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

== Changelog ==

# Changelog

### [Version 1.10.1](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.10.1)
 * Update azurecurve menu.
 * Update readme files.

### [Version 1.10.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.10.0)
 * Update azurecurve menu.
 * Add uninstall.
 * Refactor settings page to be accessible using tabs with aria.
 * Update azurecurve menu.
 
### [Version 1.9.1](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.9.1)
 * Save bug with save of custom flag url.
 
### [Version 1.9.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.9.0)
 * Add function to get all flags.
 * Add function to order flags by name (case insensitive).
 * Update names of German states, Italian regions, Polish provinces, US States, Commonwealths and Territories.
 * Change flag output to use img tag.
 
### [Version 1.8.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.8.0)
 * Add flags for: Greater London, Cornwall, Orkney, Shetland, Isle of Skye, City of Rome, German states, Polish provinces, US States, Commonwealths and Territories, Navajo Nation, Republic of Texas and Confederate Battle Flag.
 * Update azurecurve plugin menu.
 * Fix links on settings page.
 
### [Version 1.7.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.7.0)
 * Add functionality for custom flags, including upload of custom flags.
 * Implement [darylldoyle/svg-sanitizer](https://github.com/darylldoyle/svg-sanitizer) to sanitize svg images on upload.
 * Add pirate (skull and crossbones) and pirate2 (skull and crossed cutlasses) flags.

### [Version 1.6.0](https://github.com/azurecurve/azrcrv-flags/releases/tag/v1.6.0)
 * Update plugin to use svg images rather than png ones.
 * Replace png flags with public domain svg images.
 * Add option to set default image width and border attributes.
 * Update flag shortcode to accept id, width and border parameters (backward compatibility has been maintained).
 * Add flags for Italian regions and autonomous regions.
 * Fix bug in azurecurve menu.

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

**azurecurve** was one of the first plugin developers to start developing for Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

Some of the other plugins available from **azurecurve** are:
 * Breadcrumbs - [details](https://development.azurecurve.co.uk/classicpress-plugins/breadcrumbs/) / [download](https://github.com/azurecurve/azrcrv-breadcrumbs/releases/latest/)
 * Call-out Boxes - [details](https://development.azurecurve.co.uk/classicpress-plugins/call-out-boxes/) / [download](https://github.com/azurecurve/azrcrv-call-out-boxes/releases/latest/)
 * Disable FLoC - [details](https://development.azurecurve.co.uk/classicpress-plugins/disable-floc/) / [download](https://github.com/azurecurve/azrcrv-disable-floc/releases/latest/)
 * Icons - [details](https://development.azurecurve.co.uk/classicpress-plugins/icons/) / [download](https://github.com/azurecurve/azrcrv-icons/releases/latest/)
 * Loop Injection - [details](https://development.azurecurve.co.uk/classicpress-plugins/loop-injection/) / [download](https://github.com/azurecurve/azrcrv-loop-injection/releases/latest/)
 * Nearby - [details](https://development.azurecurve.co.uk/classicpress-plugins/nearby/) / [download](https://github.com/azurecurve/azrcrv-nearby/releases/latest/)
 * Page Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/page-index/) / [download](https://github.com/azurecurve/azrcrv-page-index/releases/latest/)
 * Redirect - [details](https://development.azurecurve.co.uk/classicpress-plugins/redirect/) / [download](https://github.com/azurecurve/azrcrv-redirect/releases/latest/)
 * Tag Cloud - [details](https://development.azurecurve.co.uk/classicpress-plugins/tag-cloud/) / [download](https://github.com/azurecurve/azrcrv-tag-cloud/releases/latest/)
 * Timelines - [details](https://development.azurecurve.co.uk/classicpress-plugins/timelines/) / [download](https://github.com/azurecurve/azrcrv-timelines/releases/latest/)