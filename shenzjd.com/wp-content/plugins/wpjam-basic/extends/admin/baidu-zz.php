<?php
add_filter('wpjam_basic_sub_pages', 'wpjam_basic_add_baidu_zz_sub_page');
function wpjam_basic_add_baidu_zz_sub_page($subs){
	$subs['wpjam-baidu-zz']	= array('menu_title'=>'百度站长', 	'function'=>'option');
	return $subs;
}

add_filter('wpjam_settings', 'wpjam_baidu_zz_settings');
function wpjam_baidu_zz_settings($wpjam_settings){
	$wpjam_settings['wpjam-baidu-zz']	= array(
		'sections'	=>	array(
			'link_notify'=> array(
				'title'=>'', 
				'fields'=>array(
					'link'	=> array('title'=>'链接提交',		'type'=>'fieldset',	'fields'=>array(
						'site'	=> array('title'=>'站点 (site)',	'type'=>'text'),
						'token'	=> array('title'=>'密钥 (token)',	'type'=>'text'),
					))
				)
			)
		)	
	);

	return $wpjam_settings;
}
