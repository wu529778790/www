<?php
/**
 * Defines all of administrative, activation, and deactivation settings.
 *
 * @package WP User Avatar
 * @version 1.9.13
 */

class WP_User_Avatar_Admin {
  /**
   * Constructor
   * @since 1.8
   * @uses bool $show_avatars
   * @uses add_action()
   * @uses add_filter()
   * @uses load_plugin_textdomain()
   * @uses register_activation_hook()
   * @uses register_deactivation_hook()
   */
  public function __construct() {
    global $show_avatars;
    // Initialize default settings
    register_activation_hook(WPUA_DIR.'wp-user-avatar.php', array($this, 'wpua_options'));
    // Settings saved to wp_options
    add_action('admin_init', array($this, 'wpua_options'));
    // Remove subscribers edit_posts capability
    // Translations
    load_plugin_textdomain('wp-user-avatar', "", WPUA_FOLDER.'/lang');
    // Admin menu settings
    add_action('admin_menu', array($this, 'wpua_admin'));
    add_action('admin_init', array($this, 'wpua_register_settings'));
    // Default avatar
    add_filter('default_avatar_select', array($this, 'wpua_add_default_avatar'), 10);
    add_filter('whitelist_options', array($this, 'wpua_whitelist_options'), 10);
    // Additional plugin info
    add_filter('plugin_action_links', array($this, 'wpua_action_links'), 10, 2);
    add_filter('plugin_row_meta', array($this, 'wpua_row_meta'), 10, 2);
    // Hide column in Users table if default avatars are enabled
    if((bool) $show_avatars == 0) {
      add_filter('manage_users_columns', array($this, 'wpua_add_column'), 10, 1);
      add_filter('manage_users_custom_column', array($this, 'wpua_show_column'), 10, 3);
    }
    // Media states
    add_filter('display_media_states', array($this, 'wpua_add_media_state'), 10, 1);
	
  }

  /**
   * Settings saved to wp_options
   * @since 1.4
   * @uses add_option()
   */
  public function wpua_options() {
    
    add_option('avatar_default_wp_user_avatar', "");
    add_option('wp_user_avatar_allow_upload', '0');
    add_option('wp_user_avatar_disable_gravatar', '0');
    add_option('wp_user_avatar_edit_avatar', '1');
    add_option('wp_user_avatar_resize_crop', '0');
    add_option('wp_user_avatar_resize_h', '96');
    add_option('wp_user_avatar_resize_upload', '0');
    add_option('wp_user_avatar_resize_w', '96');
    add_option('wp_user_avatar_tinymce', '1');
    add_option('wp_user_avatar_upload_size_limit', '0');	

    if(wp_next_scheduled( 'wpua_has_gravatar_cron_hook' )){
      $cron=get_option('cron');
      $new_cron='';
      foreach($cron as $key=>$value)
      {
        if(is_array($value))
        {
        if(array_key_exists('wpua_has_gravatar_cron_hook',$value))
        unset($cron[$key]);
        }
      }
      update_option('cron',$cron);
  }



  }

  /**
   * On deactivation
   * @since 1.4
   * @uses int $blog_id
   * @uses object $wpdb
   * @uses get_blog_prefix()
   * @uses get_option()
   * @uses update_option()
   */
  public function wpua_deactivate() {
    global $blog_id, $wpdb;
    $wp_user_roles = $wpdb->get_blog_prefix($blog_id).'user_roles';
    // Get user roles and capabilities
    $user_roles = get_option($wp_user_roles);
    // Remove subscribers edit_posts capability
    unset($user_roles['subscriber']['capabilities']['edit_posts']);
    update_option($wp_user_roles, $user_roles);
    // Reset all default avatars to Mystery Man
    update_option('avatar_default', 'mystery');
	
  }

  /**
   * Add options page and settings
   * @since 1.4
   * @uses add_menu_page()
   * @uses add_submenu_page()
   */
  public function wpua_admin() {
    add_menu_page(__('WP User Avatar', 'wp-user-avatar'), __('Avatars', 'wp-user-avatar'), 'manage_options', 'wp-user-avatar', array($this, 'wpua_options_page'), WPUA_URL.'images/wpua-icon.png');
    add_submenu_page('wp-user-avatar', __('Settings' , 'wp-user-avatar'), __('Settings' , 'wp-user-avatar'), 'manage_options', 'wp-user-avatar', array($this, 'wpua_options_page'));
    $hook = add_submenu_page('wp-user-avatar', __('Library','wp-user-avatar'), __('Library', 'wp-user-avatar'), 'manage_options', 'wp-user-avatar-library', array($this, 'wpua_media_page'));
    add_action("load-$hook", array($this, 'wpua_media_screen_option'));
    add_filter('set-screen-option', array($this, 'wpua_set_media_screen_option'), 10, 3);
  }

  /**
   * Checks if current page is settings page
   * @since 1.8.3
   * @uses string $pagenow
   * @return bool
   */
  public function wpua_is_menu_page() {
    global $pagenow;
    $is_menu_page = ($pagenow == 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'wp-user-avatar') ? true : false;
    return (bool) $is_menu_page;
  }

  /**
   * Media page
   * @since 1.8
   */
  public function wpua_media_page() {
    require_once(WPUA_INC.'wpua-media-page.php');
  }

  /**
   * Avatars per page
   * @since 1.8.10
   * @uses add_screen_option()
   */
  public function wpua_media_screen_option() {
    $option = 'per_page';
    $args = array(
      'label' => __('Avatars','wp-user-avatar'),
      'default' => 10,
      'option' => 'upload_per_page'
    );
    add_screen_option($option, $args);
  }

  /**
   * Save per page setting
   * @since 1.8.10
   * @param int $status
   * @param string $option
   * @param int $value
   * @return int $status
   */
  public function wpua_set_media_screen_option($status, $option, $value) {
    $status = ($option == 'upload_per_page') ? $value : $status;
    return $status;
  }

  /**
   * Options page
   * @since 1.4
   */
  public function wpua_options_page() {
    require_once(WPUA_INC.'wpua-options-page.php');
  }

  /**
   * Whitelist settings
   * @since 1.9
   * @uses apply_filters()
   * @uses register_setting()
   * @return array
   */
  public function wpua_register_settings() {
    $settings = array();
    $settings[] = register_setting('wpua-settings-group', 'avatar_rating');
    $settings[] = register_setting('wpua-settings-group', 'avatar_default');
    $settings[] = register_setting('wpua-settings-group', 'avatar_default_wp_user_avatar');
    $settings[] = register_setting('wpua-settings-group', 'show_avatars', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_tinymce', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_allow_upload', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_disable_gravatar', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_edit_avatar', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_resize_crop', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_resize_h', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_resize_upload', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_resize_w', 'intval');
    $settings[] = register_setting('wpua-settings-group', 'wp_user_avatar_upload_size_limit', 'intval');
    /**
     * Filter admin whitelist settings
     * @since 1.9
     * @param array $settings
     */
    return apply_filters('wpua_register_settings', $settings);
  }

  /**
   * Add default avatar
   * @since 1.4
   * @uses string $avatar_default
   * @uses string $mustache_admin
   * @uses string $mustache_medium
   * @uses int $wpua_avatar_default
   * @uses bool $wpua_disable_gravatar
   * @uses object $wpua_functions
   * @uses get_avatar()
   * @uses remove_filter()
   * @uses wpua_attachment_is_image()
   * @uses wpua_get_attachment_image_src()
   * @return string
   */
  public function wpua_add_default_avatar() {
    global $avatar_default, $mustache_admin, $mustache_medium, $wpua_avatar_default, $wpua_disable_gravatar, $wpua_functions;
    // Remove get_avatar filter
    remove_filter('get_avatar', array($wpua_functions, 'wpua_get_avatar_filter'));
    // Set avatar_list variable
    $avatar_list = "";
    // Set avatar defaults
    $avatar_defaults = array(
      'mystery' => __('Mystery Man','wp-user-avatar'),
      'blank' => __('Blank','wp-user-avatar'),
      'gravatar_default' => __('Gravatar Logo','wp-user-avatar'),
      'identicon' => __('Identicon (Generated)','wp-user-avatar'),
      'wavatar' => __('Wavatar (Generated)','wp-user-avatar'),
      'monsterid' => __('MonsterID (Generated)','wp-user-avatar'),
      'retro' => __('Retro (Generated)','wp-user-avatar')
    );
    // No Default Avatar, set to Mystery Man
    if(empty($avatar_default)) {
      $avatar_default = 'mystery';
    }
    // Take avatar_defaults and get examples for unknown@gravatar.com
    foreach($avatar_defaults as $default_key => $default_name) {
      $avatar = get_avatar('unknown@gravatar.com', 32, $default_key);
      $selected = ($avatar_default == $default_key) ? 'checked="checked" ' : "";
      $avatar_list .= "\n\t<label><input type='radio' name='avatar_default' id='avatar_{$default_key}' value='".esc_attr($default_key)."' {$selected}/> ";
      $avatar_list .= preg_replace("/src='(.+?)'/", "src='\$1&amp;forcedefault=1'", $avatar);
      $avatar_list .= ' '.$default_name.'</label>';
      $avatar_list .= '<br />';
    }
    // Show remove link if custom Default Avatar is set
    if(!empty($wpua_avatar_default) && $wpua_functions->wpua_attachment_is_image($wpua_avatar_default)) {
      $avatar_thumb_src = $wpua_functions->wpua_get_attachment_image_src($wpua_avatar_default, array(32,32));
      $avatar_thumb = $avatar_thumb_src[0];
      $hide_remove = "";
    } else {
      $avatar_thumb = $mustache_admin;
      $hide_remove = ' class="wpua-hide"';
    }
    // Default Avatar is wp_user_avatar, check the radio button next to it
    $selected_avatar = ((bool) $wpua_disable_gravatar == 1 || $avatar_default == 'wp_user_avatar') ? ' checked="checked" ' : "";
    // Wrap WPUA in div
    $avatar_thumb_img = '<div id="wpua-preview"><img src="'.$avatar_thumb.'" width="32" /></div>';
    // Add WPUA to list
    $wpua_list = "\n\t<label><input type='radio' name='avatar_default' id='wp_user_avatar_radio' value='wp_user_avatar'$selected_avatar /> ";
    $wpua_list .= preg_replace("/src='(.+?)'/", "src='\$1'", $avatar_thumb_img);
    $wpua_list .= ' '.__('WP User Avatar', 'wp-user-avatar').'</label>';
    $wpua_list .= '<p id="wpua-edit"><button type="button" class="button" id="wpua-add" name="wpua-add" data-avatar_default="true" data-title="'._('Choose Image').': '._('Default Avatar').'">'.__('Choose Image','wp-user-avatar').'</button>';
    $wpua_list .= '<span id="wpua-remove-button"'.$hide_remove.'><a href="#" id="wpua-remove">'.__('Remove','wp-user-avatar').'</a></span><span id="wpua-undo-button"><a href="#" id="wpua-undo">'.__('Undo','wp-user-avatar').'</a></span></p>';
    $wpua_list .= '<input type="hidden" id="wp-user-avatar" name="avatar_default_wp_user_avatar" value="'.$wpua_avatar_default.'">';
    $wpua_list .= '<div id="wpua-modal"></div>';
    if((bool) $wpua_disable_gravatar != 1) {
      return $wpua_list.'<div id="wp-avatars">'.$avatar_list.'</div>';
    } else {
      return $wpua_list.'<div id="wp-avatars" style="display:none;">'.$avatar_list.'</div>';
    }
  }

  /**
   * Add default avatar_default to whitelist
   * @since 1.4
   * @param array $options
   * @return array $options
   */
  public function wpua_whitelist_options($options) {
    $options['discussion'][] = 'avatar_default_wp_user_avatar';
    return $options;
  }

  /**
   * Add actions links on plugin page
   * @since 1.6.6
   * @param array $links
   * @param string $file
   * @return array $links
   */
  public function wpua_action_links($links, $file) { 
    if(basename(dirname($file)) == 'wp-user-avatar') {
      $links[] = '<a href="'.esc_url(add_query_arg(array('page' => 'wp-user-avatar'), admin_url('admin.php'))).'">'.__('Settings','wp-user-avatar').'</a>';
    }
    return $links;
  }

  /**
   * Add row meta on plugin page
   * @since 1.6.6
   * @param array $links
   * @param string $file
   * @return array $links
   */
  public function wpua_row_meta($links, $file) {
    if(basename(dirname($file)) == 'wp-user-avatar') {
      $links[] = '<a href="http://wordpress.org/support/plugin/wp-user-avatar" target="_blank">'.__('Support Forums','wp-user-avatar').'</a>';
    }
    return $links;
  }

  /**
   * Add column to Users table
   * @since 1.4
   * @param array $columns
   * @return array
   */
  public function wpua_add_column($columns) {
    return $columns + array('wp-user-avatar' => __('Avatar','wp-user-avatar'));
  }

  /**
   * Show thumbnail in Users table
   * @since 1.4
   * @param string $value
   * @param string $column_name
   * @param int $user_id
   * @uses int $blog_id
   * @uses object $wpdb
   * @uses object $wpua_functions
   * @uses get_blog_prefix()
   * @uses get_user_meta()
   * @uses wpua_get_attachment_image()
   * @return string $value
   */
  public function wpua_show_column($value, $column_name, $user_id) {
    global $blog_id, $wpdb, $wpua_functions;
    $wpua = get_user_meta($user_id, $wpdb->get_blog_prefix($blog_id).'user_avatar', true);
    $wpua_image = $wpua_functions->wpua_get_attachment_image($wpua, array(32,32));
    if($column_name == 'wp-user-avatar') {
      $value = $wpua_image;
    }
    return $value;
  }

  /**
   * Get list table
   * @since 1.8
   * @param string $class
   * @param array $args
   * @return object
   */
  public function _wpua_get_list_table($class, $args = array()) {
    require_once(WPUA_INC.'class-wp-user-avatar-list-table.php');
    $args['screen'] = 'wp-user-avatar';
    return new $class($args);
  }

  /**
   * Add media states
   * @since 1.4
   * @param array $states
   * @uses object $post
   * @uses int $wpua_avatar_default
   * @uses apply_filters()
   * @uses get_post_custom_values()
   * @return array
   */
  public function wpua_add_media_state($states) {
    global $post, $wpua_avatar_default;
    $is_wpua = get_post_custom_values('_wp_attachment_wp_user_avatar', $post->ID);
    if(!empty($is_wpua)) {
      $states[] = __('Avatar','wp-user-avatar');
    }
    if(!empty($wpua_avatar_default) && ($wpua_avatar_default == $post->ID)) {
      $states[] = __('Default Avatar','wp-user-avatar');
    }
    /**
     * Filter media states
     * @since 1.4
     * @param array $states
     */
    return apply_filters('wpua_add_media_state', $states);
  }
  
}

/**
 * Initialize
 * @since 1.9.2
 */
function wpua_admin_init() {
  global $wpua_admin;
  $wpua_admin = new WP_User_Avatar_Admin();
}
add_action('init', 'wpua_admin_init');
