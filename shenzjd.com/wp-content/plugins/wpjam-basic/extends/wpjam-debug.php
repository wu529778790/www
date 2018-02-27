<?php
/*
Plugin Name: Debug
Plugin URI: http://wpjam.net/item/wpjam-basic/
Description: WordPress 错误处理和查看。
Version: 1.0
*/

// add_filter('schedule_event', 'wpjam_debug_schedule_event');
// function wpjam_debug_schedule_event($event){
// 	$blog_id = get_current_blog_id();
// 	if($blog_id == 26){
// 		trigger_error(var_export($event,true));
// 	}
// 	return $event;
// }

remove_action( 'admin_init',		'_wp_check_for_scheduled_split_terms' );

// delete_option( 'cron');

// 自定义 PHP 错误处理
function wpjam_error_handler( $type, $message, $file, $line ) {
	if($type == E_STRICT){
		return true;
	}elseif($type == 8){
		if($message == 'Undefined index: delete' || strpos( $file,'class.wp-scripts.php')){
			return;
		}
	}elseif(strpos($message, 'Incorrect APP1 Exif Identifier Code')!==false) {
		return;
	}elseif(strpos($message, 'gzinflate(): data error')!==false){
		return;
	}

	if(apply_filters('wpjam_debug', false, $type, $message, $file, $line)){
		return;
	}

	$current_time = current_time('Ymd');

	if(is_multisite()){
		$blog_id = get_current_blog_id();

		if(strpos($message, 'http_request_failed')!== false){
			$php_log_file = WP_CONTENT_DIR . '/debug/http_request_failed_'.$current_time.'.log';
		}elseif(strpos($file, 'plugins/weixin-robot-advanced/api/api.php') !== false || strpos($file, 'plugins/weixin-robot-test/api/api.php') !== false){
			$php_log_file = WP_CONTENT_DIR . '/debug/weixin_'.$current_time.'.log';
		}else{
			$php_log_file = WP_CONTENT_DIR . '/debug/php_'.$blog_id.'_'.$current_time.'.log';
		}
	}else{
		$php_log_file = WP_CONTENT_DIR . '/debug/php_'.$current_time.'.log';
	}
	
	$now	= current_time('mysql');
	$url	= wpjam_get_current_page_url();
	$caller	= wp_debug_backtrace_summary();

	if(!empty($_POST)){
		$message	.= "\nPOST：".var_export($_POST, true);
	}

	if(isset($_SERVER['HTTP_REFERER'])){
		$message	.= "\n来源：".$_SERVER['HTTP_REFERER'];
	}

	$log_str= "错误：（{$type}）{$message}\n回调：{$caller}\n文件：{$file}\n行数：{$line}\n地址：{$url}\n时间：{$now}\n\n";

	file_put_contents($php_log_file, $log_str, FILE_APPEND);

	return true;
}
set_error_handler("wpjam_error_handler");

//if(wpjam_basic_get_setting('sql_debug')){
//	add_filter('query','wpjam_record_query',9999);
	function wpjam_record_query( $sql ){
		global $wpdb;

		$current_time = current_time('Ymd');

		if(is_multisite()){
			$blog_id = get_current_blog_id();
			$sql_log_file	= WP_CONTENT_DIR . '/debug/sql_'.$blog_id.'_'.$current_time.'.log';
		}else{
			$sql_log_file	= WP_CONTENT_DIR . '/debug/sql_'.$current_time.'.log';
		}

		$now	= current_time('mysql');
		$url	= wpjam_get_current_page_url();
		$caller	= $wpdb->get_caller();

		if(strpos($caller, 'WP_Query') || strpos($caller, 'WP_Comment_Query') || strpos($caller, 'wp_count_comments')){
			return $sql;
		}

		$log_str= "SQL：{$sql}\n回调：{$caller}\n地址：{$url}\n时间：{$now}\n\n";
		file_put_contents($sql_log_file, $log_str, FILE_APPEND);
		return $sql;
	}
//}

if( isset($_GET['debug'])){
	if(!defined('SAVEQUERIES')) define('SAVEQUERIES', true);
}

if( isset($_GET['debug'])){
	// 统计当前页面有多少条 SQL 查询
	add_action('wp_footer','wpjam_debug');
	add_action('admin_footer','wpjam_debug');
	function wpjam_debug(){
		global $wpdb;
		echo "<h2>当前页面共有 <strong>".get_num_queries()."</strong> 条 SQL 查询，花费 <strong>".timer_stop()."</strong> 秒</h2>\n";
		if($wpdb->queries){
			echo "<style>pre{background-color:#EEE;text-align:left;margin:20px;padding:20px; width:540px;}</style>\n";

			echo "<h3>按执行顺序：</h3>\n";
	
			$counter = 0;
			foreach($wpdb->queries as $query){
				echo "<pre>\n";
				echo "<strong>#{$counter} SQL</strong>：<br />\n{$query[0]}<br />\n";
				echo "<strong>耗时</strong>：<br />\n{$query[1]}<br />\n";
				echo "<strong>调用函数</strong>：<br />\n{$query[2]}<br />\n";
				echo "</pre>\n";
				$counter++;		 
			}
 
			echo "<h3>按耗时：</h3>\n";

			$qs = array();
			
			foreach($wpdb->queries as $q){
				$qs[''.$q[1].''] = $q;
			}
			krsort($qs);

			$counter = 0;
			foreach ($qs as $key=>$query){
				echo "<pre>\n";
				echo "<strong>#{$counter} SQL</strong>：<br />\n{$query[0]}<br />\n";
				echo "<strong>耗时</strong>：<br />\n{$query[1]}<br />\n";
				echo "<strong>调用函数</strong>：<br />\n{$query[2]}<br />\n";
				echo "</pre>\n";
				$counter++;		 
			}
		}

		$abslen = strlen(ABSPATH);
		$total_size = 0;

		echo '<br style="clear:both;"/><br/><pre style="width:540px; padding-left:10px;font:14px/140% monospace;background:#FFF;"><ol style="list-style-position:inside;">';
		foreach ( get_included_files() as $i => $path ) {
			$size = filesize( $path );
			$total_size += $size;
			$color = ' style="color:red;"';
			if ( 0 === strpos( $path, WP_PLUGIN_DIR ) ) {
				$color = ' style="color:blue;"';
			} elseif ( 0 === strpos($path, WP_CONTENT_DIR . '/themes' ) ) {
				$color = ' style="color:orange;"';
			}
			// only after WP_CONTENT_DIR check
			if ( 0 === strpos($path, ABSPATH ) ) {
				$path = substr( $path, $abslen );
			}
			if ( 0 === strpos( $path, WPINC ) ) {
				$color = ' style="color:green;"';
			} elseif ( 0 === strpos($path, 'wp-admin' ) ) {
				$color = ' style="color:grey;"';
			}
			printf( '<li%s>%s<span style="padding-left:%spx;display:inline-block;background-color:#FF00FF;border-radius:5px;height:5px;margin-left:5px;"></span></li>',
				$color, esc_html( $path ), round( $size / 512 + 1 ) );
		}
		printf( '<li style="color:black;font-weight:bold;list-style:none;">Total: %s bytes</li>',
			number_format( $total_size, 0, '.', ' ' ) );
		echo '</ol></pre>';


		global $wp_filter, $wp_current_filters;

		// $wp_current_filters = array_unique($wp_current_filters);
		// wpjam_print_r($wp_current_filters);
		// ksort( $hooks );

		foreach( $wp_current_filters as $tag => $hook ){
			if($hook){
				wpjam_dump_hook($tag, $hook);
			}
		}

		// $defined_functions = get_defined_functions();

		// wpjam_print_r($defined_functions['user']);

		// $declared_classes = get_declared_classes();

		// wpjam_print_r($declared_classes);

		// all_trace();

	}


	add_filter('all', 'wpjam_all_filter_function', 99999);
	// add_action('all', 'wpjam_all_filter_function', 99999);
	function wpjam_all_filter_function($value){
		global $wp_current_filter, $wp_current_filters, $wp_filter;

		if(!isset($wp_current_filters)){
			$wp_current_filters[] = array();
		}

		if(!isset($wp_current_filters[$wp_current_filter[0]]) && isset($wp_filter[$wp_current_filter[0]])){
			$wp_current_filters[$wp_current_filter[0]] = $wp_filter[$wp_current_filter[0]];
		}

		return $value;
	}


	function wpjam_dump_hook( $tag, $hook ) {
		$hook	= (array)$hook;
		ksort($hook);

		echo "<pre>>>>>>\t$tag<br>";

		foreach( $hook as $priority => $functions ) {

			echo $priority;

			if(!$functions)	continue;

			foreach( $functions as $function ){
				if(!isset($function['function'])) continue;
				
				if( $function['function'] != 'list_hook_details' ) {

					echo "\t";

					if( is_string( $function['function'] ) )
						echo $function['function'];

					elseif( is_string( $function['function'][0] ) )
						 echo $function['function'][0] . ' -> ' . $function['function'][1];

					elseif( is_object( $function['function'][0] ) )
						echo "(object) " . get_class( $function['function'][0] ) . ' -> ' . $function['function'][1];

					else
						print_r($function);

					echo ' (' . $function['accepted_args'] . ') <br>';
				}
			}
				
		}

		echo '</pre>';
	}

}


// global $profile, $last_time;
// $profile = array();
// $last_time = microtime(true);
 
// function do_profile() {
//     global $profile, $last_time;
//     $bt = debug_backtrace();
//     if (count($bt) <= 1) {
//         return ;
//     }
//     $frame = $bt[1];
//     unset($bt);
//     $function = $frame['function'];
//     if (!isset($profile[$function])) {
//         $profile[$function] = array(
//             'time'  => 0,
//             'calls' => 0
//         );
//     }
//     $profile[$function]['calls']++;
//     $profile[$function]['time'] += (microtime(true) - $last_time);
//     $last_time = microtime(true);
// }
// declare(ticks=1);
// register_tick_function('do_profile');
 
// function show_profile() {
//     global $profile;
//     var_dump($profile);
// }
// register_shutdown_function('show_profile');