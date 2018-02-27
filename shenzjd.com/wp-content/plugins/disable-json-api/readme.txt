=== Disable REST API ===
Contributors: dmchale, tangrufus
Tags: admin, api, json, REST, rest-api, disable
Requires at least: 4.4
Requires PHP: 5.3
Tested up to: 4.8
Stable tag: 1.4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Disable the use of the JSON REST API on your website to unauthenticated users.

== Description ==

** Version 1.4 now supports whitelisting of individual routes within the REST API **

The engine for the API has existed in WordPress since v4.4, but additional functionality and endpoints are a
continual project. While this is very exciting news for many reasons - and many plugins, themes, and even pieces of
WordPress core are already beginning to use the REST API - it is also not functionality that every site admin is going
to want enabled on their website if not necessary.

As of WordPress 4.7, the filters provided for disabling the REST API were removed. To compensate, this plugin will
forcibly return an authentication error to any API requests from sources who are not logged into your website, which
will effectively still prevent unauthorized requests from using the REST API to get information from your website.

For WordPress versions 4.4, 4.5 and 4.6, this plugin makes use of the `rest_enabled` filter provided by the API to
disable the API functionality. However, it is strongly recommended that  all site owners run the most recent version
of WordPress except where absolutely necessary.

== Installation ==

1. Upload the `disable-json-api` directory to the `/wp-content/plugins/` directory via FTP
1. Alternatively, upload the `disable-json-api_v#.#.zip` file to the 'Plugins->Add New' page in your WordPress admin
area
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= How do I know if this plugin is working? =

While logged into WordPress as any user, the REST API will function as intended. Because of this, you must use a new
browser - or Chrome's incognito mode - to test your website with a clean session. Go to yourdomain.com/wp-json/ (or
yourdomain.com/?rest_route=/ if you have pretty permalinks disabled) while NOT LOGGED IN to test the results. You will
see an authentication error returned if the plugin is active. "DRA: Only authenticated users can access the REST API."

= Does this plugin disable all REST API's installed? =

This plugin is ONLY meant to disable endpoints accessible via the default REST API that is part of WordPress itself. If
a plugin or theme chooses to register its namespace with the core REST API, its endpoints will - by default - by
disabled so long as this plugin is active. Namespaces and routes may be whitelisted via this plugin's Settings page.

== Screenshots ==

1. The JSON returned by a website with the API disabled via filters (WP versions 4.4, 4.5, 4.6)
2. The JSON returned by a website with the API disabled via authentication methods (WP versions 4.7+)

== Changelog ==

= 1.4.2 =
* Fixed issue causing unintentional unlocking of endpoints when another WP_Error existed before this plugin did its job

= 1.4.1 =
* Fixed echo of text URL to primary Plugins page in WP Dashboard

= 1.4 =
* Tested for WP v4.8
* Tested for PHP 5.3+
* Added settings screen
* Site Admins may now whitelist routes that they wish to allow unauthenticated access to
* Added `dra_allow_rest_api` filter to the is_logged_in() check, so developers can get more granular with permissions
* Props to @tangrufus for all of the help that went into this release

= 1.3 =
* Tested for WP v4.7
* Adding new functionality to raise authentication errors in 4.7+ for non-logged-in users

= 1.2 =
* Tested for WP v4.5
* Removal of actions which publish REST info to the head and header

= 1.1 =
* Updated to support the new filters created in the 2.0 beta API

= 1.0 =
* Initial Release

== Upgrade Notice ==

= 1.4 =
* Adds support to optionally whitelist individual routes of the REST API via Settings page.

= 1.1 =
* Now with support for the 2.0 beta API filters

= 1.0 =
* Initial Release
