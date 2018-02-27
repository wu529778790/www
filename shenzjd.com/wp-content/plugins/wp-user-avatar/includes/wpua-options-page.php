<?php
/**
 * Admin page to change plugin options.
 *
 * @package WP User Avatar
 * @version 1.9.13
 */

/**
 * @since 1.4
 * @uses bool $show_avatars
 * @uses string $upload_size_limit_with_units
 * @uses object $wpua_admin
 * @uses bool $wpua_allow_upload
 * @uses bool $wpua_disable_gravatar
 * @uses bool $wpua_edit_avatar
 * @uses bool $wpua_resize_crop
 * @uses int int $wpua_resize_h
 * @uses bool $wpua_resize_upload
 * @uses int $wpua_resize_w
 * @uses object $wpua_subscriber
 * @uses bool $wpua_tinymce
 * @uses int $wpua_upload_size_limit
 * @uses string $wpua_upload_size_limit_with_units
 * @uses admin_url()
 * @uses apply_filters()
 * @uses checked()
 * @uses do_action()
 * @uses do_settings_fields()
 * @uses get_option()
 * @uses settings_fields()
 * @uses submit_button()
 * @uses wpua_add_default_avatar()
 */

global $show_avatars, $upload_size_limit_with_units, $wpua_admin, $wpua_allow_upload, $wpua_disable_gravatar, $wpua_edit_avatar, $wpua_resize_crop, $wpua_resize_h, $wpua_resize_upload, $wpua_resize_w, $wpua_subscriber, $wpua_tinymce, $wpua_upload_size_limit, $wpua_upload_size_limit_with_units;
$updated = false;
if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true') {
  $updated = true;
}
$hide_size = (bool) $wpua_allow_upload != 1 ? ' style="display:none;"' : "";
$hide_resize = (bool) $wpua_resize_upload != 1 ? ' style="display:none;"' : "";
$wpua_options_page_title = __('WP User Avatar', 'wp-user-avatar');
/**
 * Filter admin page title
 * @since 1.9
 * @param string $wpua_options_page_title
 */
$wpua_options_page_title = apply_filters('wpua_options_page_title', $wpua_options_page_title);
?>

<div class="wrap">
  <h2><?php echo $wpua_options_page_title; ?></h2>
  <table><tr valign="top">
    <td align="top">
  <form method="post" action="<?php echo admin_url('options.php'); ?>">
    <?php settings_fields('wpua-settings-group'); ?>
    <?php do_settings_fields('wpua-settings-group', ""); ?>
    <?php do_action('wpua_donation_message'); ?>
    <table class="form-table">
      <?php
        // Format settings in table rows
        $wpua_before_settings = array();
        /**
         * Filter settings at beginning of table
         * @since 1.9
         * @param array $wpua_before_settings
         */
        $wpua_before_settings = apply_filters('wpua_before_settings', $wpua_before_settings);
        echo implode("", $wpua_before_settings);
      ?>
      <tr valign="top">
        <th scope="row"><?php _e('Settings'); ?></th>
        <td>
          <?php
            // Format settings in fieldsets
            $wpua_settings = array();
            $wpua_settings['tinymce'] = '<fieldset>
              <label for="wp_user_avatar_tinymce">
                <input name="wp_user_avatar_tinymce" type="checkbox" id="wp_user_avatar_tinymce" value="1" '.checked($wpua_tinymce, 1, 0).' />'
                .__('Add avatar button to Visual Editor', 'wp-user-avatar').'
              </label>
            </fieldset>';
            $wpua_settings['upload'] ='<fieldset>
              <label for="wp_user_avatar_allow_upload">
                <input name="wp_user_avatar_allow_upload" type="checkbox" id="wp_user_avatar_allow_upload" value="1" '.checked($wpua_allow_upload, 1, 0).' />'
                .__('Allow Contributors & Subscribers to upload avatars', 'wp-user-avatar').'
              </label>
            </fieldset>';
            $wpua_settings['gravatar'] ='<fieldset>
              <label for="wp_user_avatar_disable_gravatar">
                <input name="wp_user_avatar_disable_gravatar" type="checkbox" id="wp_user_avatar_disable_gravatar" value="1" '.checked($wpua_disable_gravatar, 1, 0).' />'
                .__('Disable Gravatar and use only local avatars', 'wp-user-avatar').'
              </label>
            </fieldset>';
            /**
             * Filter main settings
             * @since 1.9
             * @param array $wpua_settings
             */
            $wpua_settings = apply_filters('wpua_settings', $wpua_settings);
            echo implode("", $wpua_settings);
          ?>
        </td>
      </tr>
    </table>
    <?php
      // Format settings in table
      $wpua_subscriber_settings = array();
      $wpua_subscriber_settings['subscriber-settings'] = '<div id="wpua-contributors-subscribers"'.$hide_size.'>
        <table class="form-table">
          <tr valign="top">
            <th scope="row">
              <label for="wp_user_avatar_upload_size_limit">'
                .__('Upload Size Limit', 'wp-user-avatar').' '.__('(only for Contributors & Subscribers)', 'wp-user-avatar').'
              </label>
            </th>
            <td>
              <fieldset>
                <legend class="screen-reader-text"><span>'.__('Upload Size Limit', 'wp-user-avatar').' '. __('(only for Contributors & Subscribers)', 'wp-user-avatar').'</span></legend>
                <input name="wp_user_avatar_upload_size_limit" type="text" id="wp_user_avatar_upload_size_limit" value="'.$wpua_upload_size_limit.'" class="regular-text" />
                <span id="wpua-readable-size">'.$wpua_upload_size_limit_with_units.'</span>
                <span id="wpua-readable-size-error">'.sprintf(__('%s exceeds the maximum upload size for this site.','wp-user-avatar'), "").'</span>
                <div id="wpua-slider"></div>
                <span class="description">'.sprintf(__('Maximum upload file size: %d%s.','wp-user-avatar'), esc_html(wp_max_upload_size()), esc_html(' bytes ('.$upload_size_limit_with_units.')')).'</span>
              </fieldset>
              <fieldset>
                <label for="wp_user_avatar_edit_avatar">
                  <input name="wp_user_avatar_edit_avatar" type="checkbox" id="wp_user_avatar_edit_avatar" value="1" '.checked($wpua_edit_avatar, 1, 0).' />'
                  .__('Allow users to edit avatars', 'wp-user-avatar').'
                </label>
              </fieldset>
              <fieldset>
                <label for="wp_user_avatar_resize_upload">
                  <input name="wp_user_avatar_resize_upload" type="checkbox" id="wp_user_avatar_resize_upload" value="1" '.checked($wpua_resize_upload, 1, 0).' />'
                  .__('Resize avatars on upload', 'wp-user-avatar').'
                </label>
              </fieldset>
              <fieldset id="wpua-resize-sizes"'.$hide_resize.'>
                <label for="wp_user_avatar_resize_w">'.__('Width','wp-user-avatar').'</label>
                <input name="wp_user_avatar_resize_w" type="number" step="1" min="0" id="wp_user_avatar_resize_w" value="'.get_option('wp_user_avatar_resize_w').'" class="small-text" />
                <label for="wp_user_avatar_resize_h">'.__('Height','wp-user-avatar').'</label>
                <input name="wp_user_avatar_resize_h" type="number" step="1" min="0" id="wp_user_avatar_resize_h" value="'.get_option('wp_user_avatar_resize_h').'" class="small-text" />
                <br />
                <input name="wp_user_avatar_resize_crop" type="checkbox" id="wp_user_avatar_resize_crop" value="1" '.checked('1', $wpua_resize_crop, 0).' />
                <label for="wp_user_avatar_resize_crop">'.__('Crop avatars to exact dimensions', 'wp-user-avatar').'</label>
              </fieldset>
            </td>
          </tr>
        </table>
      </div>';
      /**
       * Filter Subscriber settings
       * @since 1.9
       * @param array $wpua_subscriber_settings
       */
      $wpua_subscriber_settings = apply_filters('wpua_subscriber_settings', $wpua_subscriber_settings);
      echo implode("", $wpua_subscriber_settings);
    ?>
    <table class="form-table">
      <tr valign="top">
      <th scope="row"><?php _e('Avatar Display','wp-user-avatar'); ?></th>
      <td>
        <fieldset>
          <legend class="screen-reader-text"><span><?php _e('Avatar Display','wp-user-avatar'); ?></span></legend>
          <label for="show_avatars">
          <input type="checkbox" id="show_avatars" name="show_avatars" value="1" <?php checked($show_avatars, 1); ?> />
          <?php _e('Show Avatars','wp-user-avatar'); ?>
          </label>
        </fieldset>
        </td>
      </tr>
        <tr valign="top" id="avatar-rating" <?php echo ((bool) $wpua_disable_gravatar == 1) ? 'style="display:none"' : ''?>>
          <th scope="row"><?php _e('Maximum Rating','wp-user-avatar'); ?></th>
          <td>
            <fieldset>
              <legend class="screen-reader-text"><span><?php _e('Maximum Rating','wp-user-avatar'); ?></span></legend>
              <?php
                $ratings = array(
                  'G' => __('G &#8212; Suitable for all audiences','wp-user-avatar'),
                  'PG' => __('PG &#8212; Possibly offensive, usually for audiences 13 and above','wp-user-avatar'),
                  'R' => __('R &#8212; Intended for adult audiences above 17','wp-user-avatar'),
                  'X' => __('X &#8212; Even more mature than above','wp-user-avatar')
                );
                foreach ($ratings as $key => $rating) :
                  $selected = (get_option('avatar_rating') == $key) ? 'checked="checked"' : "";
                  echo "\n\t<label><input type='radio' name='avatar_rating' value='".esc_attr($key)."' $selected/> $rating</label><br />";
                endforeach;
              ?>
            </fieldset>
          </td>
        </tr>
      <tr valign="top">
        <th scope="row"><?php _e('Default Avatar','wp-user-avatar') ?></th>
        <td class="defaultavatarpicker">
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Default Avatar','wp-user-avatar'); ?></span></legend>
            <?php _e('For users without a custom avatar of their own, you can either display a generic logo or a generated one based on their e-mail address.','wp-user-avatar'); ?><br />
            <?php echo $wpua_admin->wpua_add_default_avatar(); ?>
          </fieldset>
        </td>
      </tr>
    </table>
    <?php submit_button(); ?>
  </form>
</td>
    <td>
    <div id="fc-sidebar">
    <div class="fc-box">
    <h3>WP User Avatar Pro</h3>
    <p><a target="_blank" href="http://codecanyon.net/item/wp-user-avatar-pro/15638832"><img width="500" src="<?php echo WPUA_URL.'images/wp-user-avatar-banner.png'; ?>" /></a></p>
    <p><em>Introducing awesome features to enhance user experience when they upload own avatar.</em></p>
    <p>Pro features include webcam, custom folder, amazon s3 storage, dropbox storage, cropping and priority support.</p>
    <p><a class="button button-primary button-large" target="_blank" href="http://codecanyon.net/item/wp-user-avatar-pro/15638832">Upgrade Now »</a></p>
  </div>
  <div class="fc-box">
    <h4>Looking for support?</h4>
    <p>Use the <a target="_blank" href="http://www.flippercode.com/forums">support forums</a> on flippercode.com.</p>
  </div>

  <div class="fc-box">
    <h4>Your Appreciation</h4>
    <ul class="ul-square">
      <li><a target="_blank" href="http://www.flippercode.com/product/wp-user-avatar/">Upgrade to WP User Avatar Pro</a></li>
      <li><a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/wp-user-avatar?rate=5#postform">Leave a ★★★★★ plugin review on WordPress.org</a></li>
      <li><a target="_blank" href="https://wordpress.org/plugins/wp-user-avatar/">Vote "works" on the WordPress.org plugin page</a></li>
    </ul>
  </div>
</div>
    </td>
  </tr></table>
</div>
