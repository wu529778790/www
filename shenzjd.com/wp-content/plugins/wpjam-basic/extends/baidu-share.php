<?php
/*
Plugin Name: 百度分享
Plugin URI: http://blog.wpjam.com/project/wpjam-basic/
Description: 使用模板函数<code>baidu_share()</code>插入百度分享按钮。
Version: 1.0
*/
function baidu_share(){
	?>
	<div class="bdsharebuttonbox">
		<a href="#" class="bds_more"	data-cmd="more"></a>
		<a href="#" class="bds_weixin"	data-cmd="weixin"	title="分享到微信"></a>
		<a href="#" class="bds_qzone"	data-cmd="qzone"	title="分享到QQ空间"></a>
		<a href="#" class="bds_tsina"	data-cmd="tsina"	title="分享到新浪微博"></a>
		<a href="#" class="bds_tqq"		data-cmd="tqq"		title="分享到腾讯微博"></a>
		<a href="#" class="bds_bdhome"	data-cmd="bdhome"	title="分享到百度新首页"></a>
		<a href="#" class="bds_douban"	data-cmd="douban"	title="分享到豆瓣网"></a>
		<a href="#" class="bds_sqq"		data-cmd="sqq"		title="分享到QQ好友"></a>
		<a href="#" class="bds_renren"	data-cmd="renren"	title="分享到人人网"></a>
		<a href="#" class="bds_t163"	data-cmd="t163"		title="分享到网易微博"></a>
		<a href="#" class="bds_youdao"	data-cmd="youdao"	title="分享到有道云笔记"></a>
	</div>
	<?php
}

add_action( 'wp_enqueue_scripts', 'baidu_share_enqueue_scripts' );
function baidu_share_enqueue_scripts(){
	wp_enqueue_script( 'baidu_share', 'http://bdimg.share.baidu.com/static/api/js/share.js', '', '', true );

	$baidu_share_handle = apply_filters('baidu_share_handle','baidu_share');
	if(is_single()){
		$l10n	= array(
			'common' 	=> 
				array(
					'bdText'		=> '【'.get_the_title().'】',
					'bdDesc'		=> get_post_excerpt(),
					'bdUrl'			=> get_permalink(),
					'bdPic'			=> wpjam_get_post_thumbnail_src(null, 'full')
				),
			'share'		=> 
				array(
					'bdSize'	=> '32',
					'bdStyle'	=> '0'
				)
		);
	}else{
		if($title	= wp_title('',false)){
			$title	= wp_title('',false);
		}else{
			$title	= get_bloginfo('name');
		}
		
		$l10n	= array(
			'common' 	=> 
				array(
					'bdText'	=> '【'.$title.'】',
				),
			'share'		=> 
				array(
					'bdSize'	=> '24',
				),
			'slide'		=> 
				array(
					'type'		=> 'slide',
					'bdImg'		=> '0',
					'bdPos'		=> 'right',
					'bdTop'		=> '100'
				)
		);
	}

	wpjam_localize_script($baidu_share_handle, 'baidu_share', $l10n);
}
add_action('wp_footer','baidu_share_footer',99);
function baidu_share_footer(){
?>
<script type="text/javascript">
window._bd_share_config=baidu_share;
</script>
<?php 
}
?>