<?php
add_filter('wpjam_pages', 'wpjam_debug_admin_pages');
add_filter('wpjam_network_pages', 'wpjam_debug_admin_pages');
function wpjam_debug_admin_pages($wpjam_pages){
	$capability	= (is_multisite())?'manage_site':'manage_options';
	$wpjam_pages['wpjam-debug']	= array(
		'menu_title'	=> 'Debug',
		'capability'	=> $capability,
		'icon'			=> 'dashicons-carrot',
		'position'		=> '25.1'
	);

	return $wpjam_pages;
}

function wpjam_debug_page(){
	global $current_admin_url;
	echo '<h2>WPJAM Debug</h2>';

	$err_msg = '';
	
	if(!defined('WP_DEBUG') || WP_DEBUG == false)
		$err_msg	.=	"<li>在 wp-config.php 加入 <code>define('WP_DEBUG', true);</code></li>";

	if(!defined('WP_DEBUG_DISPLAY') || WP_DEBUG_DISPLAY)
		$err_msg	.=	"<li>在 wp-config.php 加入 <code>define('WP_DEBUG_DISPLAY', false);</code></li>";

	if(!defined('WP_DEBUG_LOG') || WP_DEBUG_LOG == false)
		$err_msg	.=	"<li>在 wp-config.php 加入 <code>define('WP_DEBUG_LOG', true);</code></li>";

	if(!is_dir(WP_CONTENT_DIR.'/'.'debug'))
		$err_msg	.=	"<li>在 wp-content 目录下新建 debug 文件夹，并设置为可写。</li>";

	if($err_msg){
	?>
	<div class="error">
		<p>为了使 WPJAM DEBUG 程序错误跟踪系统运行正常，你需要执行以下操作：</p>
		<ol>
		<?php echo $err_msg; ?>
		</ol>
	</div>
	<?php 
	}else{ 
		$action	= isset($_GET['action'])?$_GET['action']:''; 
		$file	= isset($_GET['file'])?$_GET['file']:''; 

		if($action == 'view'){
			if( file_exists( WP_CONTENT_DIR . '/' . $file ) ) {
				$content = file_get_contents( WP_CONTENT_DIR . '/' . $file );
				echo WP_CONTENT_DIR . '/' . $file.'（<span class="delete" style="font-size:bold;"><a href="'.$current_admin_url.'&action=delete&file='.$file.'">删除</a></span>）';

				echo wpautop( $content );
			}
		}else{
			if ($action == 'delete') {
				if( file_exists( WP_CONTENT_DIR . '/' . $file ) && unlink( WP_CONTENT_DIR . '/' . $file ) ){
					// echo '<div class="updated"><p>删除成功</p></div>';
					
					$redirect_to = add_query_arg( array( 'deleted' => 'true' ), $current_admin_url );

					wp_redirect($redirect_to);
				}
			}
			
			wpjam_debug_list_page();
		}
	} 
	?>
	<?php
}

function wpjam_debug_list_page(){
	global $current_admin_url;
	wpjam_admin_errors();
	?>
	<p>该页用于程序员调试，严重 PHP 错误必须要要修正，PHP 警告信息尽量修正。</p>
	<table class="widefat" cellspacing="0">
		<thead>
			<tr>
				<th>文件</th>
				<th>大小</th>
				<th>类型</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$wpjam_debug_file = WP_CONTENT_DIR.'/debug.log';
		if(file_exists($wpjam_debug_file)){
			$alternate = empty($alternate)?'alternate':'';
			?>
			<tr class="<?php echo $alternate; ?>">
				<td>
					debug.log
					<div class="row-actions">
						<span class="view"><a href="<?php echo $current_admin_url.'&action=view'.'&file=debug.log';?>">查看</a> | </span>
						<span class="delete"><a href="<?php echo $current_admin_url.'&action=delete'.'&file=debug.log';?>">删除</a></span>
					</div>
				</td>
				<td><?php echo size_format(filesize($wpjam_debug_file),2);?>
				<td>严重错误</td>
			</tr>
			<?php 
		}	
		?>
		<?php
		$wpjam_debug_dir = WP_CONTENT_DIR.'/debug';
		// $wpjam_debug_files	= list_files($wpjam_debug_dir);
		// foreach ($wpjam_debug_files as $wpjam_debug_file) {
		// 	# code...
		// }
		$wpjam_debug_files	= array();
		if (is_dir($wpjam_debug_dir)) {
			if ($wpjam_debug_handle = opendir($wpjam_debug_dir)) {   
				while (($wpjam_debug_file = readdir($wpjam_debug_handle)) !== false) {
					if ($wpjam_debug_file!="." && $wpjam_debug_file!=".." && is_file($wpjam_debug_dir.'/'.$wpjam_debug_file)) {
						$wpjam_debug_files[$wpjam_debug_file] = filemtime($wpjam_debug_dir.'/'.$wpjam_debug_file);
					}
				}   
				closedir($wpjam_debug_handle);   
			}   
		}
		arsort($wpjam_debug_files,SORT_NUMERIC); ?>

		<?php foreach ($wpjam_debug_files as $wpjam_debug_file => $filemtime) { $alternate = empty($alternate)?'alternate':''; ?>
			<tr class="<?php echo $alternate; ?>">
				<td>
					<?php echo $wpjam_debug_file; ?>
					<div class="row-actions">
						<span class="view"><a href="<?php echo $current_admin_url.'&action=view'.'&file=debug/'.$wpjam_debug_file;?>">查看</a> | </span>
						<span class="delete"><a href="<?php echo $current_admin_url.'&action=delete'.'&file=debug/'.$wpjam_debug_file;?>">删除</a></span>
					</div>
				</td>
				<td><?php echo size_format(filesize($wpjam_debug_dir.'/'.$wpjam_debug_file),2);?>
				<td>
				<?php if(strpos($wpjam_debug_file, 'php') !== false){ ?>
					PHP 运行警告
				<?php }elseif(strpos($wpjam_debug_file, 'weixin') !== false){ ?>
					微信插件警告
				<?php }elseif(strpos($wpjam_debug_file, 'http_request_failed') !== false){ ?>
					远程链接失败
				<?php }elseif(strpos($wpjam_debug_file, 'sql') !== false){ ?>
					SQL 查询记录
				<?php }?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php
}