<?php
/*
Plugin Name: 百度站长
Plugin URI: http://blog.wpjam.com/project/wpjam-basic/
Description: 支持百度站长链接提交。
Version: 1.0
*/

// 修改文章
add_action('post_updated', 'wpjam_post_updated_notify_baidu_zz', 10, 3);
function wpjam_post_updated_notify_baidu_zz($post_id, $post_after, $post_before){
	if($post_after->post_status == 'publish' && $post_before->post_status != 'publish'){
		wpjam_notify_baidu_zz($post_id);
	}
}

// 直接新增
add_action('save_post', 'wpjam_save_post_notify_baidu_zz', 10, 3);
function wpjam_save_post_notify_baidu_zz($post_id, $post, $update){
	if(!$update && $post->post_status == 'publish'){
		wpjam_notify_baidu_zz($post_id);
	}
}

function wpjam_notify_baidu_zz($post_id){
	$url	= apply_filters('baiduz_zz_post_link', get_permalink($post_id), $post_id);

	$site	= wpjam_get_setting('wpjam-baidu-zz', 'site');
	$token	= wpjam_get_setting('wpjam-baidu-zz', 'token');

	if($site && $token){
		$baidu_zz_api_url	= 'http://data.zz.baidu.com/urls?site='.$site.'&token='.$token.'&type=original';

		$response	= wp_remote_post($baidu_zz_api_url, array(
			'headers'	=> array('Accept-Encoding'=>'','Content-Type'=>'text/plain'),
			'sslverify'	=> false,
			'blocking'	=> false,
			'body'		=> $url
		));
		// wpjam_print_r($response);
		// exit;
	}
}

add_action( 'wp_enqueue_scripts', 'wpjam_baidu_zz_enqueue_scripts' );
function wpjam_baidu_zz_enqueue_scripts(){
	if(is_404()) return;
	wp_enqueue_script( 'baidu_zz_push', '//push.zhanzhang.baidu.com/push.js', '', '', true );
}


