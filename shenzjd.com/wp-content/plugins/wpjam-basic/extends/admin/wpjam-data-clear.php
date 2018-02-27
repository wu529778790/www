<?php

add_filter('wpjam_basic_sub_pages', 'wpjam_data_clear_admin_page');
function wpjam_data_clear_admin_page($wpjam_sub_pages)
{
    $wpjam_sub_pages['wpjam-data-clear'] = array(
        'menu_title' => '数据清理',
        'function' => 'wpjam_data_clear_page',
    );
    return $wpjam_sub_pages;
}

function wpjam_data_clear_page(){

	global $wpdb,$plugin_page;

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		check_admin_referer( $plugin_page );

		if(isset($_POST['delete_revision'])){
			$revison_count = $wpdb->query("DELETE a,b,c FROM {$wpdb->posts} a LEFT JOIN {$wpdb->term_relationships} b ON (a.ID = b.object_id) LEFT JOIN {$wpdb->postmeta} c ON (a.ID = c.post_id) WHERE a.post_type = 'revision'");
			if($revison_count) wpjam_admin_add_error($revison_count.' 条日志修订记录已经被清理');
		}

		if(isset($_POST['delete_postmeta'])){
			$useless_postmeta_count = $wpdb->query("DELETE pm FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id WHERE p.ID IS NULL");
			if($useless_postmeta_count) wpjam_admin_add_error($useless_postmeta_count.' 条无用的postmeta记录已经被清理。');
		}

		if(isset($_POST['delete_tag'])){
			$useless_tag_count = $wpdb->query("DELETE a,b,c FROM {$wpdb->terms} AS a LEFT JOIN {$wpdb->term_taxonomy} AS c ON a.term_id = c.term_id LEFT JOIN {$wpdb->term_relationships} AS b ON b.term_taxonomy_id = c.term_taxonomy_id WHERE ( c.taxonomy = 'post_tag' AND c.count = 0);");
			if($useless_tag_count) wpjam_admin_add_error($useless_postmeta_count.' 个无用的标签已经被清理。');
		}

		if(isset($_POST['delete_comment'])){
			$useless_comment_count = $wpdb->query("DELETE c FROM {$wpdb->comments} c WHERE comment_approved in ('0','spam');");
			if($useless_comment_count) wpjam_admin_add_error($useless_comment_count.' 条未审核或者垃圾留言已经被清理。');

			$useless_commentmeta_count = $wpdb->query("DELETE cm FROM {$wpdb->commentmeta} cm LEFT JOIN {$wpdb->comments} c ON c.comment_ID = cm.comment_id WHERE c.comment_ID IS NULL");
			if($useless_commentmeta_count) wpjam_admin_add_error($useless_commentmeta_count.' 条无用的commentmeta已经被清理。');
		}
	}

	$revison_count			= $wpdb->get_var("SELECT count(*) FROM {$wpdb->posts} WHERE `post_type` = 'revision'");
	$useless_tag_count		= $wpdb->get_var("SELECT count(*) From {$wpdb->terms} wt INNER JOIN {$wpdb->term_taxonomy} wtt ON wt.term_id=wtt.term_id WHERE wtt.taxonomy='post_tag' AND wtt.count=0;");
	$useless_postmeta_count	= $wpdb->get_var("SELECT count(*) FROM {$wpdb->postmeta} pm LEFT JOIN {$wpdb->posts} wp ON wp.ID = pm.post_id WHERE wp.ID IS NULL");
	$useless_comment_count	= $wpdb->get_var("SELECT count(*) FROM {$wpdb->comments} WHERE comment_approved in ('0','spam');");

	$form_fields = array(
		'delete_revision'		=> array('title'=>'日志修订',		'type'=>'checkbox',	'description'=>'<strong>'.$revison_count.'</strong> 条日志修订记录'),
		'delete_postmeta'		=> array('title'=>'Postmeta',	'type'=>'checkbox',	'description'=>'<strong>'.$useless_postmeta_count.'</strong> 条无用的postmeta记录'),
		'delete_tag'			=> array('title'=>'标签',		'type'=>'checkbox',	'description'=>'<strong>'.$useless_tag_count.'</strong> 个无用的标签'),
		'delete_comment'		=> array('title'=>'留言',		'type'=>'checkbox',	'description'=>'<strong>'.$useless_comment_count.'</strong> 条未审核或者垃圾留言'),
	);

	$from_url 		= admin_url('admin.php?page='.$plugin_page);

	?>
	<h2>数据清理</h2>

	<p>清理 WordPress 冗余数据</p>

	<?php wpjam_form($form_fields, $from_url, $plugin_page, '一键清理？');?>

	<?php

	/*<p style="color:red;font-weight:bold;">该优化动作直接操作 WordPress 数据库，为了数据的安全，请事先做好数据备份</p>

	<h3>其他问题</h3>
	<p>WPJAM Basic 主要功能是屏蔽一些 WordPress 很少用到的功能和清理 WordPress 数据库中冗余数据，但是 WordPress 真正需要性能提升是需要<a href="http://blog.wpjam.com/m/wordpress-memcached/">内存缓存</a>。</p>
	<p><strong style="color:red;">如果你需要支持内存缓存的主机，请联系 Denis，QQ：11497107。</strong></p>
	*/?>
<?php
}