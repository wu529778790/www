<?php
isset($_POST['url'])?$douyin_link = $_POST['url']:exit(json_encode(array('code'=>0,'msg'=>'未输入抖音链接')));
$parse_url_arr = parse_url($douyin_link);
if(isset($parse_url_arr['host']) and $parse_url_arr['host'] == 'www.douyin.com'){
	$result = getCurl($douyin_link);
	$video_id = GetSubStr($result,'?video_id=','\u0026line=0');
	$aweme_url = 'https://aweme.snssdk.com/aweme/v1/play/?video_id='.$video_id.'&line=0&ratio=720p&media_type=4&vr_type=0&iid=18170287739&device_id=39780991409&os_api=18&app_name=aweme&channel=App%20Store&idfa=118C3CF0-FB58-40F5-AE57-98565581D491&device_platform=iphone&build_number=16211&vid=0B8446C4-2638-4F5C-AEB7-123A4BAAB60B&openudid=cd04743a31ab486a4791eb7d0a51bccaa5e1f70e&device_type=iPhone8%2C2&app_version=1.6.2&version_code=1.6.2&os_version=11.1.1&screen_width=1242&aid=1128&ac=WIFI';
	$video_url = getLocalParse($aweme_url);
	$arr['code'] = 1;
	$arr['cover_url'] = GetSubStr($result,'<meta property="og:image" content="','" />');

	if(is_array($video_url)){
		$arr['video_url'] = $video_url[0];
	}else{
		$arr['video_url'] = $video_url;
	}

	$arr['msg'] = '获取成功';
	echo json_encode($arr);

}else{
	exit(json_encode(array('code'=>0,'msg'=>'抖音链接输入错误')));
}

function getCurl($url,$post=0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}

function GetSubStr($str, $leftStr, $rightStr){
	$left = strpos($str, $leftStr);
	//echo '左边:'.$left;
	$right = strpos($str, $rightStr,$left);
	//echo '<br>右边:'.$right;
	if($left < 0 or $right < $left) return '';
	return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}
function getLocalParse($url){
	$headers = get_headers($url,1);
	if ($headers && $headers['Location']){
		$longUrl = $headers['Location'];
	}
	return $longUrl;
}
