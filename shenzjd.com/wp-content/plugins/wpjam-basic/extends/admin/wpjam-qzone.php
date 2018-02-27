<?php
add_filter('wpjam_basic_sub_pages','wpjam_basic_add_qzone_sub_page');
function wpjam_basic_add_qzone_sub_page($subs){
	$subs['wpjam-qzone']= array('menu_title'=>'QZone同步',	'function'=>'option',	'option_name'=>'wpjam-basic',	'page_type'=>'default');
	return $subs;
}

add_filter('wpjam-qzone_sections', 'wpjam_qzone_sections');
function wpjam_qzone_sections($sections){
	
	$qzone_fields = array(
		'qq_number'				=> array('title'=>'QQ号码',				'type'=>'text' ),
		'qq_password'			=> array('title'=>'QQ密码',				'type'=>'password' ),
		'qzone_full_text'		=> array('title'=>'同步设置',				'type'=>'checkbox',	'description'=>'同步全文到 QQ空间'	)
	);

	return array( 'wpjam-qzone'	=> array('title'=>'', 'fields'=>$qzone_fields) );
}

add_action('save_post', 'wpjam_qzone_save_post',990,2);
function wpjam_qzone_save_post($post_id, $post){
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;

	if ( ! current_user_can( 'edit_post', $post_id ) ) 
		return;

	if( isset($_POST['qzone_sync']) && wpjam_basic_get_setting('qq_number') && wpjam_basic_get_setting('qq_password')){
		$post = get_post($post_id);
		$subject = get_the_title($post_id);
		if(wpjam_basic_get_setting('qzone_full_text')){
			$message = do_shortcode($post->post_content);
			$message = strip_tags($message,'<img><a>');
			//$message = str_replace("\n", "\n\n", $message);
		}else{
			$message = get_post_excerpt($post_id);
			/*if($first_img  = get_post_first_image($post->post_content)){
				$message = $message."\n\n".'<img src="'.$first_img.'" />';
			}*/
		}
		$message .= "\n\n".'原文链接： <a href="'.get_permalink($post_id).'">'.get_permalink($post_id).'</a>';
		wp_mail(wpjam_basic_get_setting('qq_number')."@qzone.qq.com", $subject, $message);
	}
}

add_action('post_submitbox_misc_actions','wpjam_qzone_post_submitbox_misc');
function wpjam_qzone_post_submitbox_misc( ) {

	if( wpjam_basic_get_setting('qq_number') && wpjam_basic_get_setting('qq_password')){
	?>
		<div class="misc-pub-section"><input name="qzone_sync" id="qzone_sync" type="checkbox" value="1"> <label for="qzone_sync">同步到QQ空间</label> 
		</div>
	<?php
	}
}