<?php
/**
 * This file magically runs when the plugin is deleted
 *
 * Direct from https://developer.wordpress.org/plugins/the-basics/uninstall-methods/
 */

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

$option_name = 'DRA_route_whitelist';

delete_option( $option_name );

// for site options in Multisite
delete_site_option( $option_name );
