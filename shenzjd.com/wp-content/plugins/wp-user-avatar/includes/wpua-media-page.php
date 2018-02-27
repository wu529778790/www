<?php
/**
 * Media Library view of all avatars in use.
 *
 * @package WP User Avatar
 * @version 1.9.13
 */

/**
 * @since 1.8
 * @uses object $wpua_admin
 * @uses _wpua_get_list_table()
 * @uses add_query_arg()
 * @uses check_admin_referer()
 * @uses current_action()
 * @uses current_user_can()
 * @uses display()
 * @uses esc_url()
 * @uses find_posts_div()
 * @uses get_pagenum()
 * @uses get_search_query
 * @uses number_format_i18n()
 * @uses prepare_items()
 * @uses remove_query_arg()
 * @uses search_box()
 * @uses views()
 * @uses wp_delete_attachment()
 * @uses wp_die()
 * @uses wp_enqueue_script()
 * @uses wp_get_referer()
 * @uses wp_redirect()
 * @uses wp_unslash()
 */

  /** WordPress Administration Bootstrap */
  require_once(ABSPATH.'wp-admin/admin.php');

  if(!current_user_can('upload_files'))
    wp_die(__('You do not have permission to upload files.','wp-user-avatar'));

  global $wpua_admin;

  $wp_list_table = $wpua_admin->_wpua_get_list_table('WP_User_Avatar_List_Table');
  $pagenum = $wp_list_table->get_pagenum();

  // Handle bulk actions
  $doaction = $wp_list_table->current_action();

  if($doaction) {
    check_admin_referer('bulk-media');

    if(isset($_REQUEST['media'])) {
      $post_ids = $_REQUEST['media'];
    } elseif(isset($_REQUEST['ids'])) {
      $post_ids = explode(',', $_REQUEST['ids']);
    }

    $location = esc_url(add_query_arg(array('page' => 'wp-user-avatar-library'), 'admin.php'));
    if($referer = wp_get_referer()) {
      if(false !== strpos($referer, 'admin.php')) {
        $location = remove_query_arg(array('trashed', 'untrashed', 'deleted', 'message', 'ids', 'posted'), $referer);
      }
    }
    switch($doaction) {
      case 'delete':
        if(!isset($post_ids)) {
          break;
        }
        foreach((array) $post_ids as $post_id_del) {
          if(!current_user_can('delete_post', $post_id_del)) {
            wp_die(__('You are not allowed to delete this post.','wp-user-avatar'));
          }
          if(!wp_delete_attachment($post_id_del)) {
            wp_die(__('Error in deleting.','wp-user-avatar'));
          }
        }
      $location = esc_url_raw(add_query_arg('deleted', count($post_ids), $location));
      break;
    }
    wp_redirect($location);
    exit;
  } elseif(!empty($_GET['_wp_http_referer'])) {
    wp_redirect(remove_query_arg(array('_wp_http_referer', '_wpnonce'), wp_unslash($_SERVER['REQUEST_URI'])));
    exit;
  }
  $wp_list_table->prepare_items();
  wp_enqueue_script('wp-ajax-response');
  wp_enqueue_script('jquery-ui-draggable');
  wp_enqueue_script('media');
?>
<div class="wrap">
  <h2>
    <?php _e('Avatars','wp-user-avatar');
      if(!empty($_REQUEST['s'])) {
        printf('<span class="subtitle">'.__('Search results for &#8220;%s&#8221;','wp-user-avatar').'</span>', get_search_query());
      }
    ?>
  </h2>
  <?php
    $message = "";
    if(!empty($_GET['deleted']) && $deleted = absint($_GET['deleted'])) {
      $message = sprintf(_n('Media attachment permanently deleted.', '%d media attachments permanently deleted.', $deleted), number_format_i18n($_GET['deleted']));
      $_SERVER['REQUEST_URI'] = remove_query_arg(array('deleted'), $_SERVER['REQUEST_URI']);
    }
    if(!empty($message)) : ?>
    <div id="message" class="updated"><p><?php echo $message; ?></p></div>
  <?php endif; ?>
  <?php $wp_list_table->views(); ?>
  <form id="posts-filter" action="" method="get">
    <?php $wp_list_table->search_box(__('Search','wp-user-avatar'), 'media'); ?>
    <?php $wp_list_table->display(); ?>
    <div id="ajax-response"></div>
    <?php find_posts_div(); ?>
    <br class="clear" />
  </form>
</div>
