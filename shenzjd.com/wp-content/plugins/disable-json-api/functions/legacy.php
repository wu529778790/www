<?php
/**
 * This function gets called if the current version of WordPress is less than 4.7
 * We are able to make use of filters to actually disable the functionality entirely
 */
function DRA_Disable_Via_Filters() {

    // Filters for WP-API version 1.x
    add_filter( 'json_enabled', '__return_false' );
    add_filter( 'json_jsonp_enabled', '__return_false' );

    // Filters for WP-API version 2.x
    add_filter( 'rest_enabled', '__return_false' );
    add_filter( 'rest_jsonp_enabled', '__return_false' );

}
