<?php

add_filter('wpjam_pages', 'wpjam_qiniutek_admin_pages');
add_filter('wpjam_network_pages', 'wpjam_qiniutek_admin_pages');
function wpjam_qiniutek_admin_pages($wpjam_pages){
	$qiniu_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxNS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IuWbvuWxgl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgd2lkdGg9IjE1My44OHB4IiBoZWlnaHQ9IjEwMy4zcHgiIHZpZXdCb3g9IjAgMCAxNTMuODggMTAzLjMiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDE1My44OCAxMDMuMyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8cGF0aCBmaWxsPSIjRkZGRkZGIiBkPSJNMTUzLjY4NCwwLjc5MWMtMC4yNjYtMC40OTctMC44My0wLjk2My0xLjg1LTAuNzI4Yy0xLjk4NCwwLjQ2NS0yNS4xMzksMzkuMTY5LTc0Ljg4NywzOC4wOThoLTAuMDE2DQoJYy05LjQ1MiwwLjIwMy0xOC42MjgtMS4wMy0yNi4xODgtMy4xNTZsLTQuMzI3LTEzLjk4YzAsMC0wLjgwMS0zLjQzOC00LjExNS01LjE5NmMtMi4yOTMtMS4yMDctMy42MzEtMC44MjEtMy45MTEtMC40ODQNCgljLTAuMjUyLDAuMzE2LTAuMjA0LDAuNjUzLTAuMjA0LDAuNjUzbDIuMDUzLDE1LjI2NkMxNC44OTEsMjAuNCwzLjQ3NCwwLjQwMywyLjA0MiwwLjA2M2MtMS4wMTUtMC4yMzUtMS41NzgsMC4yMy0xLjg0NSwwLjcyOA0KCWMtMC40MjcsMC44LTAuMDIxLDEuOTI5LTAuMDIxLDEuOTI5YzcuMTUyLDIxLjMxMSwyMi41ODcsMzguMTI2LDQyLjU2Nyw0Ny4wOWw1LjUwOSwzNi45MzENCgljMC4zNzQsMTAuNTMzLDcuNDE2LDE2LjU1OSwxNi41NjksMTYuNTU5aDI3LjM5YzkuMTUzLDAsMTYuMDA4LTYuNTg4LDE2LjU3NS0xNi41NTlsNS4wMTktMzAuNTM3YzAsMCwwLjA4NS0wLjQyNi0wLjE2Ni0wLjYwNA0KCWMtMC4zMTItMC4xOTEtMi42OTgtMC4yNjQtNy41MjgsMy4zMTRjLTQuODMsMy41ODItNi40NjMsOC43NTYtNi40NjMsOC43NTZzLTUuMjE5LDEyLjY5OS02LjU5MSwxOC4xMjUNCgljLTEuNDQ0LDUuNzEzLTcuODUsNS4yMDMtNy44NSw1LjIwM3MtOS43ODksMC0xNC42ODEsMGMtNC44OTUsMC01LjM5Ni00LjM5NS01LjM5Ni00LjM5NWwtOS4xNi0zMi4yMTUNCgljNi42NzEsMS42ODYsMTMuNjg0LDIuNTY4LDIwLjk2MiwyLjU0M2gwLjAxNmMzNS45NzUsMC4xNDEsNjUuODk3LTIxLjg4Myw3Ni43NTYtNTQuMjEyQzE1My43MDMsMi43MTksMTU0LjExLDEuNTksMTUzLjY4NCwwLjc5MXoiDQoJLz4NCjwvc3ZnPg0K';

	$subs = array();
	$subs['wpjam-qiniutek']	= array('menu_title' => '设置', 'function'=>'option');

	if( wpjam_qiniu_get_setting('bucket') && wpjam_qiniu_get_setting('access') && wpjam_qiniu_get_setting('secret') ){
		$subs['wpjam-qiniutek-update']	= array('menu_title' => '文件更新');
		$subs['wpjam-qiniutek-robots']	= array('menu_title' => 'Robots.txt');
	}

	if(isset($_GET['page']) && $_GET['page']=='wpjam-qiniutek-coupon'){
		$subs['wpjam-qiniutek-coupon']	= array('menu_title' => '充值优惠码');
	}
	
	$wpjam_pages['wpjam-qiniutek']	= array(
		'menu_title'	=> '七牛云存储',		
		'icon'			=> $qiniu_icon,
		'function'		=> 'option',
		'subs'			=> $subs,
		'position'		=> '80.2'
	);

	return $wpjam_pages;
}

add_filter('wpjam_settings', 'wpjam_qiniutek_settings');
function wpjam_qiniutek_settings($wpjam_settings){
	$wpjam_settings['wpjam-qiniutek'] 	= array('sections'=>wpjam_qiniutek_get_option_sections());
	return $wpjam_settings;
}

function wpjam_qiniutek_get_option_sections(){
	$qiniutek_fields = array(
		'host'		=> array('title'=>'七牛域名',			'type'=>'url',		'description'=>'设置为七牛提供的测试域名或者在七牛绑定的域名。<strong>注意要域名前面要加上 http://</strong>。<br />如果博客安装的是在子目录下，比如 http://www.xxx.com/blog，这里也需要带上子目录 /blog '),
		'bucket'	=> array('title'=>'七牛空间名',		'type'=>'text',		'description'=>'设置为你在七牛提供的空间名。'),
		'access'	=> array('title'=>'ACCESS KEY',		'type'=>'text',		'description'=>''),
		'secret'	=> array('title'=>'SECRET KEY',		'type'=>'text',		'description'=>''),
	);

	$local_fields = array(		
		'exts'		=> array('title'=>'扩展名',			'type'=>'text',		'description'=>'设置要缓存静态文件的扩展名，请使用 | 分隔开，|前后都不要留空格。'),
		'dirs'		=> array('title'=>'目录',			'type'=>'text',		'description'=>'设置要缓存静态文件所在的目录，请使用 | 分隔开，|前后都不要留空格。'),
		'local'		=> array('title'=>'本地域名',			'type'=>'url',		'description'=>'如果图片等静态文件存储的域名和网站不同，可通过该字段设置。<br />使用该字段设置静态文件所在的域名之后，请确保 JS 和 CSS 等文件也在该域名下，否则将不会加速。'),
		// 'webp'		=> array('title'=>'WebP格式',		'type'=>'checkbox',	'description'=>'将图片转换成WebP格式，可以压缩到原来的2/3大小。'),
		'imageslim'	=> array('title'=>'图片瘦身',			'type'=>'checkbox',	'description'=>'将存储在七牛的JPEG、PNG格式的图片实时压缩而尽可能不影响画质。'),
		'interlace'	=> array('title'=>'渐进显示',			'type'=>'checkbox',	'description'=>'是否JPEG格式图片渐进显示。'),
		'quality'	=> array('title'=>'图片质量',			'type'=>'number',	'description'=>'1-100之间图片质量，七牛默认为75。','mim'=>1,'max'=>100),
		'jquery'	=> array('title'=>'使用 jQuery 2.0',	'type'=>'checkbox',	'description'=>'jQuery 2.0 不再支持 IE 6/7/8，如果你的网站是面向移动或者不再向低端 IE 用户提供服务，可以勾选该选项。'),
		'google'	=> array('title'=>'Google 前端库',	'type'=>'select',	'options'=>array('-1'=>'继续使用 Google 前端和字体库','disabled'=>'彻底屏蔽 Google 字体库','useso'=>'使用360 网站卫士常用前端公共库 CDN 服务','ustc'=>'使用中科大镜像服务')),
	);

	$thumb_fields = array(
		// 'thumb4admin'=> array('title'=>'后台显示缩略图',	'type'=>'checkbox',	'description'=>'在后台日志和分类列表显示缩略图。'),
		// 'advanced'	=> array('title'=>'高级缩略图',		'type'=>'checkbox',	'description'=>'启用高级缩略图，可以设置分类和标签缩略图。'),
		'default'	=> array('title'=>'默认缩略图',		'type'=>'image',	'description'=>'如果日志没有特色图片，没有第一张图片，也没用高级缩略图的情况下所用的缩略图。可以填本地或者七牛的地址！'),
		'width'		=> array('title'=>'图片最大宽度',		'type'=>'number',	'description'=>'设置博客文章内容中图片的最大宽度，插件会使用将图片缩放到对应宽度，节约流量和加快网站速度加载。'),
		//'new_smileys'=> array('title'=>'使用高清表情',	'type'=>'checkbox',	'description'=>''),
	);

	$remote_fields = array(
		'remote'	=> array('title'=>'保存远程图片',		'type'=>'checkbox',	'description'=>'自动将远程图片镜像到七牛。'),
		'exceptions'=> array('title'=>'例外',			'type'=>'textarea',	'class'=>'regular-text',	'description'=>'如果远程图片的链接中包含以上字符串或者域名，就不会被保存并镜像到七牛。'),
	);

	$watermark_options = array(
		'SouthEast'	=> '右下角',
		'SouthWest'	=> '左下角',
		'NorthEast'	=> '右上角',
		'NorthWest'	=> '左上角',
		'Center'	=> '正中间',
		'West'		=> '左中间',
		'East'		=> '右中间',
		'North'		=> '上中间',
		'South'		=> '下中间',
	);

	$watermark_fields = array(
		'watermark'	=> array('title'=>'水印图片',			'type'=>'image',	'description'=>'水印图片'),
		'disslove'	=> array('title'=>'透明度',			'type'=>'number',	'description'=>'透明度，取值范围1-100，缺省值为100（完全不透明）','min'=>1,	'max'=>100),
		'gravity'	=> array('title'=>'水印位置',			'type'=>'select',	'description'=>'',	'options'=>$watermark_options),
		'dx'		=> array('title'=>'横轴边距',			'type'=>'number',	'description'=>'横轴边距，单位:像素(px)，缺省值为10'),
		'dy'		=> array('title'=>'纵轴边距',			'type'=>'number',	'description'=>'纵轴边距，单位:像素(px)，缺省值为10'),
	);

	$sections = array( 
    	'qiniutek'	=> array('title'=>'七牛设置',		'fields'=>$qiniutek_fields,	'summary'=>'<p>充值可以使用WordPress插件用户专属的优惠码：“<span style="color:red; font-weight:bold;">d706b222</span>”，点击查看<a title="如何使用七牛云存储的优惠码" class="thickbox" href="'.admin_url('admin.php?page=wpjam-qiniutek-coupon').'&amp;TB_iframe=true&amp;width=420&amp;height=480">详细使用指南</a>。</p>',	),
    	'local'		=> array('title'=>'本地设置',		'fields'=>$local_fields,	'callback'=>'',	),
    	'thumb'		=> array('title'=>'缩略图设置',	'fields'=>$thumb_fields,	'summary'=>'<p>*文章获取缩略图的顺序为：特色图片 > 标签缩略图 > 第一张图片 > 分类缩略图 > 默认缩略图。</p>',	),
    	'remote'	=> array('title'=>'远程图片设置',	'fields'=>$remote_fields,	'summary'=>'<p>*自动将远程图片镜像到七牛需要你的博客支持固定链接。<br />*如果前面设置的静态文件域名和博客域名不一致，该功能也可能出问题。<br />*远程 GIF 图片保存到七牛将失去动画效果，所以目前不支持 GIF 图片。</p>',	),
    	'watermark'	=> array('title'=>'水印设置',		'fields'=>$watermark_fields,'callback'=>'',	)
	);

	if(is_network_admin()){
		// unset($sections['qiniutek']);
		unset($sections['thumb']);
		unset($sections['local']['fields']['local']);
		unset($sections['watermark']['fields']['watermark']);
	}elseif(is_blog_admin()){
		unset($sections['local']['fields']['google']);
	}

	return  apply_filters('qiniutek_setting', $sections);
}

add_filter('wpjam-qiniutek_field_validate','wpjam_qiniutek_field_validate');
function wpjam_qiniutek_field_validate( $wpjam_qiniutek ) {
	flush_rewrite_rules();
	return $wpjam_qiniutek;
}

function wpjam_qiniutek_update_page(){
	global $current_admin_url;

	$updates = '';

	if(isset($_GET['refresh'])){
		update_option('timestamp',time());
		wpjam_admin_add_error('已经刷新本地JS和CSS浏览器缓存！');
	}

	$form_fields = array(
		'updates'	=> array('title'=>'', 'type'=>'textarea', 'rows'=>10, 'description'=>'请输入需要更新的文件，每行一个！'),
	);
	
	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

		$data = wpjam_get_form_post($form_fields);

		$updates = $data['updates'];

		$updates_array = explode("\n", $updates);

		foreach ($updates_array as $update) {
			if(trim($update)){
				$update = preg_replace('/\?.*/', '', $update);
				$response = wpjam_qiniutek_delete_file($update);
				if(is_wp_error($response)){
					wpjam_admin_add_error($update.'：（'.$response->get_error_code().'）'.$response->get_error_message(), 'error');
				}else{
					wpjam_admin_add_error($update.'更新成功');
				}
			}
		}
	}

	?>
	<h2>更新文件</h2>

	<?php wpjam_form($form_fields, $current_admin_url, '', '更新文件'); ?>

	<ol>
		<li>点击“更新文件”按钮之后，只要文件后面显示更新成功，即代表更新成功。</li>
		<li>如果实时查看还是旧的文件，可能是你浏览器的缓存，你需要清理下缓存，或者等待自己更新。</li>
		<li>如果你更新的是主题或者插件的JS和CSS文件，可以再次点击下面按钮刷新本地缓存：<br />
		<a class="button" href="<?php echo $current_admin_url.'&refresh'; ?>">刷新本地JS和CSS浏览器缓存</a></li>
		<li>图片缩略图更新七牛是按照按照队列顺序进行的，需要等待一定的时间，只要看到原图更新即可。</li>
	</ol>
	<?php
}

function wpjam_qiniutek_robots_page(){
	global $current_admin_url;

	$qiniutek_robots = get_option('qiniutek_robots');

	if(!$qiniutek_robots){
		$qiniutek_robots = '
User-agent: *
Disallow: /
User-agent: Googlebot-Image
Allow: /
User-agent: Baiduspider-image
Allow: /
		';
	}

	$form_fields = array(
		'robots'	=> array('title'=>'', 'type'=>'textarea', 'rows'=>10, 'value'=>$qiniutek_robots, 'description'=>'上传 Robots.txt 文件，防止搜索引擎索引镜像的网页。！'),
	);

	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){

		$data = wpjam_get_form_post($form_fields);

		$robots = $data['robots'];

		if($robots){

			update_option('qiniutek_robots',$robots);

			wpjam_qiniutek_delete_file('robots.txt'); // 如果有，先清理。
			$response = wpjam_qiniutek_put('robots.txt', $robots); // 再上传
			if(is_wp_error( $response )){
				wpjam_admin_add_error('：（'.$response->get_error_code().'）'.$response->get_error_message(), 'error');
			}else{
				wpjam_admin_add_error('上传成功');
			}

		}
	}

	?>
	
	<h2>上传 Robots.txt</h2>

	<?php wpjam_form($form_fields, $current_admin_url, '', '更新Robots.txt'); ?>

	<?php
}

function wpjam_qiniutek_coupon_page(){
	?>
	<h2>如何使用七牛云存储的优惠码</h2>
	<p>简单说使用<strong>WordPress插件用户专属的优惠码</strong>“<strong style="color:red;">d706b222</strong>”充值，一次性充值2000元及以内99折，2000元以上则95折</strong>。详细使用流程：</p>
	<p>1. 登陆<a href="http://wpjam.com/go/qiniu" target="_blank">七牛开发者平台</a></p>
	<p>2. 然后点击“充值”，进入充值页面</p>
	<p><img srcset="<?php echo WPJAM_BASIC_PLUGIN_URL; ?>/extends/qiniu/qiniu-coupon.png 2x" src="<?php echo WPJAM_BASIC_PLUGIN_URL; ?>/extends/qiniu/qiniu-coupon.png" alt="使用七牛优惠码" style="max-width:400px;" /></p>
	<p>3. 点击“使用优惠码”，并输入优惠码“<strong><span style="color:red;">d706b222</span></strong>”，点击“使用”。</p>
	<p>4. 输入计划充值的金额，点击“马上充值”，进入支付宝页面，完成支付。</p>
	<p>5. 完成支付后，可至财务->>财务概况->>账户余额 查看实际到账金额。</p>
	<?php
}

function wpjam_qiniutek_delete_file($file){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$file_array = parse_url($file);
	$key = str_replace($file_array['scheme'].'://'.$file_array['host'].'/', '', $file);
	$err = Qiniu_RS_Delete($qiniutek_client, $qiniutek_bucket, $key);

	if($err !== null){
		return new WP_Error($err->Code, $err->Err);
	}else{
		return true;
	}
}

function wpjam_qiniutek_put_file($key, $file){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$putPolicy = new Qiniu_RS_PutPolicy($qiniutek_bucket);
	$upToken = $putPolicy->Token(null);

	if(!function_exists('Qiniu_Put')){
		require_once(WPJAM_BASIC_PLUGIN_DIR."/extends/qiniu/sdk/io.php");
	}

	list($ret, $err) = Qiniu_PutFile($upToken, $key, $file);
	
	if($err !== null){
		return new WP_Error($err->Code, $err->Err);
	}else{
		return true;
	}
}

function wpjam_qiniutek_put($key, $str){
	global $qiniutek_client;

	if(!$qiniutek_client){
		$qiniutek_client = wpjam_get_qiniutek_client();
	}

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	$qiniutek_bucket = $wpjam_qiniutek['bucket'];

	$putPolicy = new Qiniu_RS_PutPolicy($qiniutek_bucket);
	$upToken = $putPolicy->Token(null);

	if(!function_exists('Qiniu_Put')){
		require_once(WPJAM_BASIC_PLUGIN_DIR."/extends/qiniu/sdk/io.php");
	}

	list($ret, $err) = Qiniu_Put($upToken, $key, $str, null);

	if($err !== null){
		return new WP_Error($err->Code, $err->Err);
	}else{
		return true;
	}
}

function wpjam_get_qiniutek_client(){

	$wpjam_qiniutek = get_option( 'wpjam-qiniutek' );
	if(!class_exists('Qiniu_MacHttpClient')){
		require_once(WPJAM_BASIC_PLUGIN_DIR."/extends/qiniu/sdk/rs.php");
	}	
	Qiniu_SetKeys($wpjam_qiniutek['access'], $wpjam_qiniutek['secret']);
	$qiniutek_client = new Qiniu_MacHttpClient(null);

	return $qiniutek_client;
}