<?php
/**
 * TinyMCE button for Visual Editor.
 *
 * @package WP User Avatar
 * @version 1.9.13
 */

/**
 * Add TinyMCE button
 * @since 1.9.5
 * @uses add_filter()
 * @uses get_user_option()
 */
function wpua_add_buttons() {
  // Add only in Rich Editor mode
  if(get_user_option('rich_editing') == 'true') {
    add_filter('mce_external_plugins', 'wpua_add_tinymce_plugin');
    add_filter('mce_buttons', 'wpua_register_button');
  }
}
add_action('init', 'wpua_add_buttons');

/**
 * Register TinyMCE button
 * @since 1.9.5
 * @param array $buttons
 * @return array
 */
function wpua_register_button($buttons) {
  array_push($buttons, 'separator', 'wpUserAvatar');
  return $buttons;
}

/**
 * Load TinyMCE plugin
 * @since 1.9.5
 * @param array $plugin_array
 * @return array
 */
function wpua_add_tinymce_plugin($plugins) {
  $plugins['wpUserAvatar'] = WPUA_INC_URL.'tinymce/editor_plugin.js';
  return $plugins;
}

/**
 * Call TinyMCE window content via admin-ajax
 * @since 1.4
 */
function wpua_ajax_tinymce() {
  include_once(WPUA_INC.'tinymce/window.php');
  die();
}
add_action('wp_ajax_wp_user_avatar_tinymce', 'wpua_ajax_tinymce');
