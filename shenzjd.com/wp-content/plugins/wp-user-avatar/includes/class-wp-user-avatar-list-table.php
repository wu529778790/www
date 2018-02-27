<?php
/**
 * Based on WP_Media_List_Table class.
 *
 * @package WP User Avatar
 * @version 1.9.13
 */

class WP_User_Avatar_List_Table extends WP_List_Table {
  /**
   * Constructor
   * @since 1.8
   * @param array $args
   * @uses array $avatars
   * @uses object $post
   * @uses int $wpua_avatar_default
   * @uses get_query_var()
   * @uses have_posts()
   * @uses the_post()
   * @uses wp_edit_attachments_query
   * @uses WP_Query()
   * @uses wp_reset_query()
   */
  public function __construct($args = array()) {
    global $avatars, $post, $wpua_avatar_default;
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    $q = array(
      'paged' => $paged,
      'post_type' => 'attachment',
      'post_status' => 'inherit',
      'posts_per_page' => '-1',
      'meta_query' => array(
        array(
          'key' => '_wp_attachment_wp_user_avatar',
          'value' => "",
          'compare' => '!='
        )
      )
    );
    $avatars_wp_query = new WP_Query($q);
    $avatars = array();
    while($avatars_wp_query->have_posts()) : $avatars_wp_query->the_post();
      $avatars[] = $post->ID;
    endwhile;
    wp_reset_query();
    // Include default avatar
    $avatars[] = $wpua_avatar_default;
    parent::__construct(array(
      'plural' => 'media',
      'screen' => isset($args['screen']) ? $args['screen'] : null
    ));
  }

  /**
   * Only users with edit_users capability can use this section
   * @since 1.8
   * @uses current_user_can()
   */
  public function ajax_user_can() {
    return current_user_can('edit_users');
  }

  /**
   * Search form
   * @since 1.8
   * @param string $text
   * @param int $input_id
   * @uses _admin_search_query()
   * @uses has_items()
   * @uses submit_button()
   */
  public function search_box($text, $input_id) {
    if(empty($_REQUEST['s']) && !$this->has_items()) {
      return;
    }
    $input_id = $input_id.'-search-input';
    if(!empty($_REQUEST['orderby'])) {
      echo '<input type="hidden" name="orderby" value="'.esc_attr($_REQUEST['orderby']).'" />';
    }
    if(!empty($_REQUEST['order'])) {
      echo '<input type="hidden" name="order" value="'.esc_attr($_REQUEST['order']).'" />';
    }
    if(!empty($_REQUEST['post_mime_type'])) {
      echo '<input type="hidden" name="post_mime_type" value="'.esc_attr($_REQUEST['post_mime_type']).'" />';
    }
    if(!empty($_REQUEST['detached'])) {
      echo '<input type="hidden" name="detached" value="'.esc_attr($_REQUEST['detached']).'" />';
    }
  ?>
    <p class="search-box">
      <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
      <input type="hidden" id="page" name="page" value="wp-user-avatar-library" />
      <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query(); ?>" />
      <?php submit_button($text, 'button', false, false, array('id' => 'search-submit')); ?>
    </p>
  <?php
  }

  /**
   * Return only avatars and paginate results
   * @since 1.8
   * @uses array $avatars
   * @uses wp_edit_attachments_query()
   */
  public function prepare_items() {
    global $avail_post_mime_types, $avatars, $lost, $post, $post_mime_types, $wp_query, $wpdb;
    $q = $_REQUEST;
    $q['post__in'] = $avatars;
    list($post_mime_types, $avail_post_mime_types) = wp_edit_attachments_query($q);
    $this->is_trash = isset($_REQUEST['status']) && $_REQUEST['status'] == 'trash';
    $this->set_pagination_args(array(
      'total_items' => $wp_query->found_posts,
      'total_pages' => $wp_query->max_num_pages,
      'per_page' => $wp_query->query_vars['posts_per_page'],
    ));
  }

  /**
   * Links to available table views
   * @since 1.8
   * @uses array $avatars
   * @uses add_query_arg()
   * @uses number_format_i18n()
   * @return array
   */
  public function get_views() {
    global $avatars;
    $type_links = array();
    $_total_posts = count(array_filter($avatars));
    $class = (empty($_GET['post_mime_type']) && !isset($_GET['status'])) ? ' class="current"' : "";
    $type_links['all'] = sprintf('<a href="%s">', esc_url(add_query_arg(array('page' => 'wp-user-avatar-library'), 'admin.php'))).sprintf(_nx('All <span class="count">(%s)</span>', 'All <span class="count">(%s)</span>', $_total_posts, 'uploaded files'), number_format_i18n($_total_posts)).'</a>';
    return $type_links;
  }

  /**
   * Bulk action available with this table
   * @since 1.8
   * @return array
   */
  public function get_bulk_actions() {
    $actions = array();
    $actions['delete'] = __('Delete Permanently','wp-user-avatar');
    return $actions;
  }

  /**
   * Current action from bulk actions list
   * @since 1.8
   * @uses current_action()
   * @return string|bool
   */
  public function current_action() {
    return parent::current_action();
  }

  /**
   * Checks whether table has items
   * @since 1.8
   * @uses have_posts()
   * @return bool
   */
  public function has_items() {
    return have_posts();
  }

  /**
   * Message displayed when no items
   * @since 1.8
   */
  public function no_items() {
    _e('No media attachments found.','wp-user-avatar');
  }

  /**
   * Columns in this table
   * @since 1.8
   * @return array
   */
  public function get_columns() {
    $columns = array();
    $columns['cb'] = '<input type="checkbox" />';
    $columns['icon'] = "";
    $columns['title'] = _x('File', 'column name');
    $columns['author'] = __('Author','wp-user-avatar');
    $columns['parent'] = _x('Uploaded to', 'column name');
    $columns['date'] = _x('Date', 'column name');
    return $columns;
  }

  /**
   * Sortable columns in this table
   * @since 1.8
   * @return array
   */
  public function get_sortable_columns() {
    return array(
      'title' => 'title',
      'author' => 'author',
      'date' => array('date', true)
    );
  }

  /**
   * Display for rows in table
   * @since 1.8
   * @uses object $post
   * @uses object $wpdb
   * @uses object $wpua_functions
   * @uses add_filter()
   * @uses _draft_or_post_title()
   * @uses _media_states()
   * @uses current_user_can()
   * @uses get_attached_file()
   * @uses get_current_user_id()
   * @uses get_edit_post_link()
   * @uses get_edit_user_link()
   * @uses get_post_mime_type()
   * @uses get_the_author()
   * @uses get_the_author_meta()
   * @uses get_userdata()
   * @uses have_posts()
   * @uses the_post()
   * @uses wpua_get_attachment_image()
   */
  public function display_rows() {
    global $post, $wpdb, $wpua_functions;
    add_filter('the_title','esc_html');
    $alt = "";
    while (have_posts()) : the_post();
      $user_can_edit = current_user_can('edit_post', $post->ID);
      if($this->is_trash && $post->post_status != 'trash' || !$this->is_trash && $post->post_status == 'trash') {
        continue;
      }
      $alt = ('alternate' == $alt) ? "" : 'alternate';
      $post_owner = (get_current_user_id() == $post->post_author) ? 'self' : 'other';
      $att_title = _draft_or_post_title();
  ?>
    <tr id='post-<?php echo $post->ID; ?>' class='<?php echo trim($alt.' author-'.$post_owner.' status-'.$post->post_status); ?>' valign="top">
      <?php
        list($columns, $hidden) = $this->get_column_info();
        foreach($columns as $column_name => $column_display_name) {
          $class = "class='$column_name column-$column_name'";
          $style = "";
          if(in_array($column_name, $hidden)) {
            $style = ' style="display:none;"';
          }
          $attributes = $class.$style;
          switch($column_name) {
            case 'cb':
            ?>
              <th scope="row" class="check-column">
                <?php if($user_can_edit) { ?>
                  <label class="screen-reader-text" for="cb-select-<?php the_ID(); ?>"><?php echo sprintf(__('Select %s','wp-user-avatar'), $att_title);?></label>
                  <input type="checkbox" name="media[]" id="cb-select-<?php the_ID(); ?>" value="<?php the_ID(); ?>" />
                <?php } ?>
              </th>
            <?php
            break;
            case 'icon':
              $attributes = 'class="column-icon media-icon"'.$style;
              ?>
                <td <?php echo $attributes ?>><?php
                  if($thumb = $wpua_functions->wpua_get_attachment_image($post->ID, array(80, 60), true)) {
                    if($this->is_trash || !$user_can_edit) {
                      echo $thumb;
                    } else {
                  ?>
                    <a href="<?php echo get_edit_post_link($post->ID, true); ?>" title="<?php echo esc_attr(sprintf(__('Edit &#8220;%s&#8221;'), $att_title)); ?>">
                      <?php echo $thumb; ?>
                    </a>
                  <?php } }
                  ?>
                </td>
              <?php
            break;
            case 'title':
          ?>
        <td <?php echo $attributes ?>><strong>
          <?php if($this->is_trash || !$user_can_edit) {
            echo $att_title;
          } else { ?>
          <a href="<?php echo get_edit_post_link($post->ID, true); ?>"
            title="<?php echo esc_attr(sprintf(__('Edit &#8220;%s&#8221;'), $att_title)); ?>">
            <?php echo $att_title; ?></a>
          <?php };
          _media_states($post); ?></strong>
          <p>
            <?php
              if(preg_match('/^.*?\.(\w+)$/', get_attached_file($post->ID), $matches)) {
                echo esc_html(strtoupper($matches[1]));
              } else {
                echo strtoupper(str_replace('image/', "", get_post_mime_type()));
              }
            ?>
          </p>
          <?php echo $this->row_actions($this->_get_row_actions($post, $att_title)); ?>
        </td>
      <?php
        break;
        case 'author':
      ?>
        <td <?php echo $attributes ?>>
          <?php
            printf('<a href="%s">%s</a>',
              esc_url(add_query_arg(array('author' => get_the_author_meta('ID')), 'upload.php')),
              get_the_author()
            ); ?>
        </td>
      <?php
        break;
        case 'date':
          if('0000-00-00 00:00:00' == $post->post_date) {
            $h_time = __('Unpublished','wp-user-avatar');
          } else {
            $m_time = $post->post_date;
            $time = get_post_time('G', true, $post, false);
            if ((abs($t_diff = time() - $time)) < DAY_IN_SECONDS) {
              if ($t_diff < 0)
                $h_time = sprintf(__('%s from now','wp-user-avatar'), human_time_diff($time));
              else
                $h_time = sprintf(__('%s ago','wp-user-avatar'), human_time_diff($time));
            } else {
              $h_time = mysql2date(__('Y/m/d','wp-user-avatar'), $m_time);
            }
          }
      ?>
      <td <?php echo $attributes ?>><?php echo $h_time ?></td>
      <?php
        break;
        case 'parent':
        global $blog_id, $wpdb;
        // Find all users with this WPUA
        $wpua_metakey = $wpdb->get_blog_prefix($blog_id).'user_avatar';
        $wpuas = $wpdb->get_results($wpdb->prepare("SELECT wpum.user_id FROM $wpdb->usermeta AS wpum, $wpdb->users AS wpu WHERE wpum.meta_key = %s AND wpum.meta_value = %d AND wpum.user_id = wpu.ID ORDER BY wpu.user_login", $wpua_metakey, $post->ID));
        // Find users without WPUA
        $nowpuas = $wpdb->get_results($wpdb->prepare("SELECT wpu.ID FROM $wpdb->users AS wpu, $wpdb->usermeta AS wpum WHERE wpum.meta_key = %s AND wpum.meta_value = %d AND wpum.user_id = wpu.ID ORDER BY wpu.user_login", $wpua_metakey, ""));
        $user_array = array();
      ?>
        <td <?php echo $attributes ?>>
          <strong>
          <?php
            if(!empty($wpuas)) {
              foreach($wpuas as $usermeta) {
                $user = get_userdata($usermeta->user_id);
                $user_array[] = '<a href="'.get_edit_user_link($user->ID).'">'.$user->user_login.'</a>';
              }
            } else {
              foreach($nowpuas as $usermeta) {
                $user = get_userdata($usermeta->ID);
                $user_array[] = '<a href="'.get_edit_user_link($user->ID).'">'.$user->user_login.'</a>';
              }
            }
          ?>
          <?php echo implode(', ', array_filter($user_array)); ?>
          </strong>
        </td>
      <?php
        break;
        }
      }
    ?>
    </tr>
  <?php endwhile;
  }

  /**
   * Actions for rows in table
   * @since 1.8
   * @uses object $post
   * @uses string $att_title
   * @uses _draft_or_post_title()
   * @uses current_user_can()
   * @uses get_edit_post_link()
   * @uses get_permalink()
   * @uses wp_nonce_url()
   * @return array
   */
  public function _get_row_actions($post, $att_title) {
    $actions = array();
    if(current_user_can('edit_post', $post->ID) && !$this->is_trash) {
      $actions['edit'] = '<a href="'.get_edit_post_link($post->ID, true).'">'.__('Edit','wp-user-avatar').'</a>';
    }
    if(current_user_can('delete_post', $post->ID)) {
      if($this->is_trash) {
        $actions['untrash'] = "<a class='submitdelete' href='".wp_nonce_url("post.php?action=untrash&amp;post=$post->ID", 'untrash-post_'.$post->ID)."'>".__('Restore','wp-user-avatar')."</a>";
      } elseif (EMPTY_TRASH_DAYS && MEDIA_TRASH) {
        $actions['trash'] = "<a class='submitdelete' href='".wp_nonce_url("post.php?action=trash&amp;post=$post->ID", 'trash-post_'.$post->ID)."'>".__('Trash','wp-user-avatar')."</a>";
      }
      if($this->is_trash || !EMPTY_TRASH_DAYS || !MEDIA_TRASH) {
        $delete_ays = (!$this->is_trash && !MEDIA_TRASH) ? " onclick='return showNotice.warn();'" : "";
        $actions['delete'] = "<a class='submitdelete'$delete_ays href='".wp_nonce_url("post.php?action=delete&amp;post=$post->ID", 'delete-post_'.$post->ID)."'>".__('Delete Permanently','wp-user-avatar')."</a>";
      }
    }
    if(!$this->is_trash) {
      $title = _draft_or_post_title($post->post_parent);
      $actions['view'] = '<a href="'.get_permalink($post->ID).'" title="'.esc_attr(sprintf(__('View &#8220;%s&#8221;'), $title)).'" rel="permalink">'.__('View','wp-user-avatar').'</a>';
    }
    return $actions;
  }
}
