<?php
ini_set("error_reporting","E_ALL & ~E_NOTICE");
header("Content-type: text/html;charset=utf-8");
date_Default_TimeZone_set("PRC");
$CS_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//分割线内是可以修改的部分
//*******************************分割线*******************************//

$title = "神族九帝音乐网站";//网站标题
$lxfs = "newbug.xyz";//网站底部联系方式

//*******************************分割线*******************************//


//下面代码请勿修改，否则会出现错误！
$error = "<script>alert('地址错误!');'</script>";
if(isset($_GET['v'])){
//*******************************播放页面*******************************//
	$de64 = base64_decode($_GET['v']);
	$expl = explode('$',$de64);
	$le = $expl[0];
	$id = $expl[1];
	switch ($le) {
		case "kg":
		//*******************************音乐播放页面*******************************//
		$p3_url= "http://m.kugou.com/app/i/getSongInfo.php?hash=".$id."&cmd=playInfo";
		$p3_data = curl_get($p3_url);
		$p3_json = json_decode($p3_data,true);
		$song_url = $p3_json['url'];
		if(empty($song_url)){ exit($error); }
		$song_name = $p3_json['fileName'];
		$title = $song_name.",在线试听,".$title;
		$album_img = $p3_json['album_img'];
		$imgUrl = $p3_json['imgUrl'];
		if(empty($imgUrl)){ $imgUrl = $album_img; }
		$song_img = str_replace("{size}","400",$imgUrl);
		$main = "<div class=\"main\"><div style=\"float:left;width:560px;\"><div style=\"height:140px;border-bottom:1px dashed #ccc;\"><div style=\"float:left;width:390px;\"><h2 style=\"margin-bottom:10px;width:370px;white-space:nowrap;overflow:hidden;\">".$song_name."</h2><p style=\"text-align:center;margin-bottom:13px;\">下载歌曲，请复制歌曲外链后使用浏览器或者软件下载！</p><p style=\"text-align:center;margin-bottom:7px;\"><audio controls=\"controls\" autoplay=\"autoplay\" loop=\"loop\" style=\"width:250px;\"><source src=\"".$song_url."\" type=\"audio/mp3\" /><embed width=\"250\" height=\"30\" src=\"".$song_url."\" /></embed></audio><input style=\"width:0px;height:0px;border:0px\" type=\"text\" id=\"copy_value\" value=\"".$song_url."\" readonly=\"readonly\"><input class=\"copy_btn\" type=\"button\" value=\"一键复制外链\"></p><p style=\"text-align:center;\">本站不存储任何音频，版权均属于各音乐门户，本站仅测试</p></div><div id=\"bdshare\" class=\"bdshare_t bds_tools_32 get-codes-bdshare\"><a class=\"bds_qzone\"></a><a class=\"bds_tsina\"></a><a class=\"bds_tqq\"></a><a class=\"bds_mail\"></a><a class=\"bds_baidu\"></a><a class=\"bds_tqf\"></a><a class=\"bds_qq\"></a><a class=\"bds_mshare\"></a><a class=\"bds_douban\"></a><a class=\"bds_renren\"></a><a class=\"bds_t163\"></a><a class=\"bds_kaixin001\"></a><a class=\"bds_hi\"></a><a class=\"bds_copy\"></a></div></div></div><div style=\"float:right;width:300px;padding:10px;overflow-y:auto;height:200px;border-left:1px dashed #ccc;\">".song_lrc($id)."</div></div>";
		break;
		case "mv":
		//*******************************视频播放页面*******************************//
		$x_url = "http://m.kugou.com/app/i/mv.php?cmd=100&ext=mp4&hash=".$id;
		$data = curl_get($x_url);
		preg_match('/songname":"(.*?)",/is', $data, $nm2);
		preg_match('/singer":"(.*?)",/is', $data, $nm1);
		preg_match_all('/downurl":"(.*?)",/is', $data, $mp4);
		preg_match_all('/backupdownurl":\["(.*?)"\]/is', $data, $bmp4);
		$song_name = $nm1[1]." - ".$nm2[1];
		$mv_url = stripslashes($mp4[1][0]);
		if(empty($mv_url)){	exit($error); }
		$title = $song_name.",mv视频在线观看,".$title;
		$a=array("流畅","标清","高清","超清");
		for($i = 0; $i < 3; $i++){
			$downurla = stripslashes($mp4[1][$i]);
			$downurlb = stripslashes($bmp4[1][$i]);
			if($downurla){
			$down .= "<li style=\"margin:5px 5px;\">".$a[$i]."： <a href=\"".$downurla."\" target=\"_blank\" class=\"btn\">电信线路</a> <a href=\"".$downurlb."\" target=\"_blank\" class=\"btn\">移动线路</a></li>";
			}
		}
		$main = "".$song_name."</h2><script type=\"text/javascript\">player('".$mv_url."');</script><div style=\"height:35px;line-height:35px;\"><div id=\"bdshare\" class=\"bdshare_t bds_tools_32 get-codes-bdshare\"><a class=\"bds_qzone\"></a><a class=\"bds_tsina\"></a><a class=\"bds_tqq\"></a><a class=\"bds_mail\"></a><a class=\"bds_tqf\"></a><a class=\"bds_mshare\"></a><a class=\"bds_douban\"></a><a class=\"bds_renren\"></a><a class=\"bds_t163\"></a><a class=\"bds_hi\"></a><a class=\"bds_copy\"></a></div><a href=\"http://player.youku.com/embed/XMjk4MDE3MTIwMA\" target=\"_blank\"><img src=\"http://wx1.sinaimg.cn/large/6b229b76ly1fj7sy5vde6j203c00u743.jpg\"></a></div></div><div style=\"float:right;width:300px;text-align:center;margin-top:35px;border-left:1px dashed #ccc;\"><img src=\"http://pan.baidu.com/share/qrcode?url=".$mv_url."\" width=\"170px\" height=\"170px\"/></a><h2 style=\"white-space:nowrap;overflow:hidden;\">手机扫码视频下载</h2>注意：复制下载链接地址后在浏览器中打开下载".$down."";
		break;
		default:
		exit($error);
	}
	$main .= "<div class=\"main\"><img width=\"910\" src=\"http://wx2.sinaimg.cn/large/6b229b76ly1fiqa1un2jug20qo02i764.gif\"></div>".random().mv();
}elseif(isset($_GET['p'])){
//*******************************列表页面*******************************//
	$l=$_GET['p'];
	switch ($l){
		case "1":
		$title = "恋爱的歌，献给热恋中的你们，让爱情变得更加甜美！";
		$url = "http://mobilecdn.kugou.com/api/v3/rank/song?pagesize=300&rankid=67&page=1";
		break;
		case "2":
		$title = "TOP排行榜，平民化的音乐和歌手，让音乐更贴近你的生活！";
		$url = "http://mobilecdn.kugou.com/api/v3/rank/song?pagesize=300&rankid=8888&page=1";
		break;
		case "3":
		$title = "DJ舞曲，一首首HIGH翻天，让你犹如亲临迪斯科现场！";
		$url = "http://mobilecdn.kugou.com/api/v3/rank/song?pagesize=300&rankid=70&page=1";
		break;
		default:
		exit($error);
	}
	$main = "<div class=\"main\"><div style=\"float:left;\">".$title."</div><div style=\"float:right;\"><a href=\"?p=1\" class=\"btn\">恋爱的歌</a>&nbsp;&nbsp;<a href=\"?p=2\" class=\"btn\">TOP排行榜</a>&nbsp;&nbsp;<a href=\"?p=3\" class=\"btn\">DJ舞曲</a></div></div>";
	$data = curl_get($url);
	$json = json_decode($data,true);
	$main .= "<div class=\"main\"><ul style=\"background:#fff;overflow:hidden;\">";
	$count_json = count($json['data']['info']);
	for($i = 0; $i < $count_json; $i++){
		$name = $json['data']['info'][$i]['filename'];
		$hash = $json['data']['info'][$i]['hash'];
		$href = str_replace("=","",base64_encode("kg$".$hash));
		$main .= "<li style=\"line-height:30px;height:30px;float:left;width:210px;margin:0 10px;white-space:nowrap;overflow:hidden;\" onmouseover=\"this.style.backgroundColor='#eee'\" onmouseout=\"this.style.backgroundColor='#FFF'\"><a href=\"?v=".$href."\" target=\"_blank\" title=\"".$name."\">".$name."</a></li>";
	}
	$main .= "</ul></div>";
}elseif(isset($_GET['ac'])){
//*******************************搜索页面*******************************//
	$w = htmlspecialchars($_GET['ac']);
	$title = $w."的搜索结果,".$title;
	$url = "http://mobilecdn.kugou.com/api/v3/search/song?format=jsonp&keyword=".$w."&page=1";
	$mv_url = "http://mvsearch.kugou.com/mv_search?keyword=".urlencode($w)."&page=1&pagesize=30&userid=489797698&platform=WebFilter&tag=em&filter=2&iscorrection=1&privilege_filter=0";
	$data = curl_get($url);
	$mv_data = curl_get($mv_url);
	preg_match_all('/"filename":"(.*?)","/is',$data,$nm);
	preg_match_all('/"hash":"(.*?)","/is',$data,$ha);
	preg_match_all('/"FileName":"(.*?)","/is',$mv_data,$mv_nm);
	preg_match_all('/"MvHash":"(.*?)","/is',$mv_data,$mv_ha);
	preg_match_all('/"Pic":"(.*?)","/is',$mv_data,$mv_im);
	$count = count($ha['1']);
	if(empty($count)){	exit("<script>alert('没有找到任何资料!');top.location.href='".$_SERVER["HTTP_REFERER"]."'</script>"); }
	$main = "<div class=\"main\">关键词：【<font color='red'>".$w."</font>】的搜索结果</div><div class=\"main\"><ul style=\"background:#fff;overflow:hidden;\">";
	for($i = 0; $i < $count; $i++){
		$nnmm = $nm['1'][$i];
		$name = str_ireplace($w,"<font color='red'>".$w."</font>",$nnmm);//关键字红色显示
		$hash = $ha['1'][$i];
		$href = str_replace("=","",base64_encode("kg$".$hash));
		$main .= "<li style=\"line-height:30px;height:30px;float:left;width:210px;margin:0 10px;white-space:nowrap;overflow:hidden;\" onmouseover=\"this.style.backgroundColor='#eee'\" onmouseout=\"this.style.backgroundColor='#FFF'\"><a href=\"?v=".$href."\" target=\"_blank\" title=\"".$nm1."\">".$name."</a></li>";
	}
	$main .= "</ul></div>";
	$main .= "<div class=\"main\"><div class=\"mv_list\"><ul>";
	for($c = 0; $c < 28; $c++){
		$gq = stripslashes($mv_nm[1][$c]);
		$mpic = "http://imge.kugou.com/mvhdpic/240/".substr($mv_im[1][$c],0,8)."/".$mv_im[1][$c];
		$hash = $mv_ha[1][$c];
		$href = str_replace("=","",base64_encode("mv$".$hash));
		if($gq){
			$main .= "<li><a href=\"?v=".$href."\" target=\"_blank\"><img src=\"".$mpic."\" title=\"".$gq."\"><span class=\"mv_name\">&nbsp;".$gq."</span></a><span><a href=\"?v=".$href."\" target=\"_blank\">".$gq."</a></span></li>";
		}
	}
	$main .= "</ul></div></div>";
}elseif(isset($_GET['m'])){
//*******************************MV页面*******************************//
	$p=$_GET['m'];
	if(preg_match("/^\+?[1-9][0-9]*$/",$p)){
		$mvurl = "http://www.kugou.com/mvweb/html/index_9_".$p.".html";
		$data = curl_get($mvurl);
	}else{
		exit($error);
	}
	preg_match('/id="mvNum">(.*?)<\/label>/is',$data,$total);
	preg_match('/class="mvlist">(.*?)<\/div>/is',$data,$mvlist);
	$num = "20";
	$nums = $total[1];
	$pnum = ceil($nums/$num);
	$Prev_page = $p - 1;   //上页
	$next_page = $p + 1;   //下页
	if($p > $pnum){
		exit($error);
	}elseif($p < 2){
		$page = "<a class=\"btn\">上一页</a>&nbsp;&nbsp;<a href=\"?m=".$next_page."\" class=\"btn\">下一页</a>";
	}elseif($p > $pnum-1){
		$page = "<a href=\"?m=".$Prev_page."\" class=\"btn\">上一页</a>&nbsp;&nbsp;<a class=\"btn\">下一页</a>";
	}else{
		$page = "<a href=\"?m=".$Prev_page."\" class=\"btn\">上一页</a>&nbsp;&nbsp;<a href=\"?m=".$next_page."\" class=\"btn\">下一页</a>";
	}
	$main = "<div class=\"main\"><div style=\"float:left;\">热舞MV大放送！给你意想不到的试听盛宴！</div><div style=\"float:right;\"><a class=\"btn\">共".$pnum."页</a>&nbsp;&nbsp;<a class=\"btn\">第".$p."页</a>&nbsp;&nbsp;".$page."&nbsp;&nbsp;<span class=\"btn\" title=\"输入页数按回车健!\">电梯直达: <input type=\"text\" style=\"width:30px;\" onkeydown=\"javascript:if((event.keyCode==13)&&(!isNaN(this.value))){location='?m='+this.value;return false;}\"> 页</span></div></div>";
	$title = "热舞MV大放送 - 第".$p."页 ".$title;
	$main .= "<div class=\"main\"><div class=\"mv_list\"><ul>";
	preg_match_all("/<span>(.*)<\/span>/", $mvlist[1], $name);
	preg_match_all('/src="(.*)"/', $mvlist[1], $img);
	for($i = 0; $i < 20; $i++){
		$gq = $name[1][$i];
		$mpic = $img[1][$i];
		$hash = substr(strrchr($mpic,"/"),1,32);
		$href = str_replace("=","",base64_encode("mv$".$hash));
		if($mpic){
			$main .= "<li><a href=\"?v=".$href."\" target=\"_blank\"><img src=\"".$mpic."\" title=\"".$gq."\"><span class=\"mv_name\">&nbsp;".$gq."</span></a><span><a href=\"?v=".$href."\" target=\"_blank\">".$gq."</a></span></li>";
		}
	}
	$main .= "</ul></div></div>";
}else{
	$main = mv().bang();
}
//首页视频
function mv(){
	$output .= "<div class=\"main\"><div class=\"mv_list\"><ul>";
	$kbang = "http://www.kugou.com/mvweb/html/index_13_".rand(1,300).".html";
	$data = curl_get($kbang);
	preg_match('/class="mvlist">(.*?)<\/div>/is',$data,$mvlist);
	preg_match_all("/<span>(.*)<\/span>/", $mvlist[1], $name);
	preg_match_all('/src="(.*)"/', $mvlist[1], $img);
	$su = rand(0,9);
	for($i = $su; $i < $su+8; $i++){
		$gq = $name[1][$i];
		$mpic = $img[1][$i];
		$hash = substr(strrchr($mpic,"/"),1,32);
		//$hash = basename($mpic,".jpg");
		$href = str_replace("=","",base64_encode("mv$".$hash));
		if($mpic){
			$output .= "<li><a href=\"?v=".$href."\" target=\"_blank\"><img src=\"".$mpic."\" title=\"".$gq."\"><span class=\"mv_name\">&nbsp;".$gq."</span></a><span><a href=\"?v=".$href."\" target=\"_blank\">".$gq."</a></span></li>";
		}
	}
	$output .= "</ul></div></div>";
return $output;
}
//首页歌曲
function bang(){
	$kbang = "http://mobilecdn.kugou.com/api/v3/rank/song?pagesize=500&rankid=6666&page=1";
	$data = curl_get($kbang);
	$json = json_decode($data,true);
	$num = $json['data']['total'];
	$time = date('Y-m-d H:i:s',$json['data']['timestamp']);
	$main .= "<div class=\"main\"><div style=\"float:left;\">网络歌曲飙升榜，这里集合网络热歌红歌！</div><div style=\"float:right;\">更新时间：".$time."</div></div>";
	$main .= "<div class=\"main\"><ul class=\"rule_list\">";
	for($i = 0; $i < $num; $i++){
		$k = $i + 1;
		$name = $json['data']['info'][$i]['filename'];
		$hash = $json['data']['info'][$i]['hash'];
		$size = $json['data']['info'][$i]['filesize'];
		$href = str_replace("=","",base64_encode("kg$".$hash));
		if($hash){
			$main .= "<li><div class=\"song\"><div class=\"aleft\"><span>".$k."、</span><a href=\"?v=".$href."\" target=\"_blank\" title=\"".$name."\">".$name."</a></div></div><div class=\"size\">".formatsize($size)."</div></li>";
		}
	}
	$main .= "</ul></div>";
return $main;
}
//分页随机歌曲
function random(){
	$kbang = "http://mobilecdn.kugou.com/api/v3/rank/song?pagesize=500&rankid=23784&page=1";
	$data = curl_get($kbang);
	$json = json_decode($data,true);
	$num = $json['data']['total'] - 20;
	$su = rand(0,$num);
	$main .= "<div class=\"main\"><ul style=\"background:#fff;overflow:hidden;\">";
	for($i = $su; $i < $su + 20; $i++){
		$name = $json['data']['info'][$i]['filename'];
		$hash = $json['data']['info'][$i]['hash'];
		$href = str_replace("=","",base64_encode("kg$".$hash));
		if($hash){
			$main .= "<li style=\"line-height:30px;height:30px;float:left;width:210px;margin:0 10px;white-space:nowrap;overflow:hidden;\" onmouseover=\"this.style.backgroundColor='#eee'\" onmouseout=\"this.style.backgroundColor='#FFF'\"><a href=\"?v=".$href."\" target=\"_blank\" title=\"".$name."\">".$name."</a></li>";
		}
	}
	$main .= "</ul></div>";
return $main;
}
//获取源代码
function curl_get($url){
	$temp = parse_url($url);
	$header = array (
	"Host: {$temp['host']}",
	"Referer: http://{$temp['host']}/"
	);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    $output = curl_exec($ch);
    curl_close($ch);
return $output;
}
//歌词
function song_lrc($id){
	$kgid = "http://m.kugou.com/app/i/krc.php?cmd=100&timelength=243000&hash=".$id;
	$data = curl_get($kgid);
	if($data){
	$href = preg_replace("/\[.*?\]/","<br>",$data);
	$output = "<h2>歌词</h2>".$href;
	}else{
	$output = "<a href=\"https://weidian.com/i/1964964729\" target=\"_blank\"><img src=\"http://ww3.sinaimg.cn/mw690/6b229b76jw1f8qfzhnmvrj20jg0jgdpf.jpg\" width=\"280px\" height=\"200px\"/></a>";
	}
return $output;
}
//大小转换//
function formatsize($size) {
	$prec=3;
	$size = round(abs($size));
	$units = array(0=>" B ", 1=>" KB", 2=>" MB", 3=>" GB", 4=>" TB");
	if ($size==0) return str_repeat(" ", $prec)."0$units[0]";
	$unit = min(4, floor(log($size)/log(2)/10));
	$size = $size * pow(2, -10*$unit);
	$digi = $prec - 1 - floor(log($size)/log(10));
	$size = round($size * pow(10, $digi)) * pow(10, -$digi);
return $size.$units[$unit];
}
// 将UNICODE编码后的内容进行解码，编码后的内容格式：YOKA\u738b （原始：YOKA王）
function unicode_decode($name){
	$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
	preg_match_all($pattern, $name, $matches);
	if (!empty($matches)){
		$name = '';
		for ($j = 0; $j < count($matches[0]); $j++){
			$str = $matches[0][$j];
			if (strpos($str, '\\u') === 0){
				$code = base_convert(substr($str, 2, 2), 16, 10);
				$code2 = base_convert(substr($str, 4), 16, 10);
				$c = chr($code).chr($code2);
				$c = iconv('UCS-2', 'UTF-8', $c);
				$name .= $c;
			}else{
				$name .= $str;
			}
		}
	}
return $name;
}

?>
<!--
//                            _ooOoo_
//                           o8888888o
//                           88" . "88
//                           (| -_- |)
//                            O\ = /O
//                        ____/'---'\____
//                       .  ' \\| |// '  .
//                       / \\||| : |||// \
//                     / _||||| -:- |||||- \
//                       | | \\\ - /// | |
//                     | \_| ''\---/'' |_/  |
//                      \ .-\__ `-` ___/-. /
//                   ___`. .' /--.--\ '. .`___
//                ."" '< '.___\_<|>_/___.' >' "".
//               | | : `- \`.;`\ _ /`;.`/ -` : | |
//                 \ \ `-. \_ __\ /__ _/ .-` / /
//         ======'-.____`-.___\_____/___.-`____.-'======
//                            '=---='
//         .............................................
//                  佛祖保佑             永无BUG
-->
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title><?php echo $title?></title>
<meta name="keywords" content="<?php echo $title?>,QQ空间背景音乐,音乐外链,歌曲外链,mp3外链,视频外链,背景音乐链接,外链,音乐外链网" />
<meta name="description" content="<?php echo $title?>,音乐试听外链站免费提供音频试听，你可以分享给你的好友。同时生成的音乐外链链接支持引用到各大博客，网站，还可免费设置QQ空间背景音乐，免去你寻找歌曲链接地址的苦恼。" />
<style type="text/css">
/* 公共部分 */
*{padding:0;margin:0;border:0;list-style-type:none;}
body{color:#666666;font:13px "微软雅黑","Lucida Grande",STHeiti,Verdana,Arial,Times,serif;background:url("data:image/gif;base64,R0lGODlhBwAGAKIAAAAAAP///8nJycjIyLm5ubi4uP///wAAACH5BAEAAAYALAAAAAAHAAYAAAMQOCOiTaREVmpU0C6pR8xDAgA7") repeat;}
img{vertical-align:middle;border:0;}
a{-webkit-transition: all 0.25s linear 0.01s; -moz-transition: all 0.25s linear 0.01s; -ms-transition: all 0.25s linear 0.01s; -o-transition: all 0.25s linear 0.01s; transition: all 0.25s linear 0.01s;color:#252525;text-decoration:none;cursor:pointer;}
/* 头部 */
.header{background:#3D3D3D;height:52px;position:fixed;left:0;top:0;width:100%;z-index:10050}
.header .h_main{width:960px;margin:0 auto;position:relative;z-index:10050;height:52px}
.header .logo{width:143px;float:left;display:inline;padding-top:5px}
.header .logo a{display:inline;float:left;width:143px;height:43px;background:url('http://ww1.sinaimg.cn/small/6b229b76jw1fb0zomp6k1g203z014t8h.gif');background-repeat:no-repeat;background-size:143px 43px;}
.header .menus{display:inline;float:left;height:43px;padding-top:5px;position:relative;z-index:10000}
.header .menus li{float:left;display:inline}
.header .menus li .sliding_menu{float:left;display:inline;color:#fff;font-weight:700}
.header .menus li a:hover{color:#6DB823;text-decoration:none}
.header .menus li a{font-size:14px}
.header .menus .m_nav a{text-decoration:none;float:left}
.header .m_nav a{color:#fff;position:relative;margin:0 5px;padding:12px 3px 10px;height:20px}
.header .serach{display:inline;float:left;padding:13px 0 0 13px}
.header .seh_m{float:left}
.header .seh_v{border:0 solid #dad8d8;background:#fff;float:left;height:23px;line-height:23px;outline:medium none;padding:2px 4px;width:120px;border-radius:0}
.header .seh_a{border:0 none;color:#FFF;height:27px;line-height:27px;vertical-align:middle;float:left;font-weight:700;text-decoration:none;cursor:pointer;}
.header .seh_b{background:#f5ad00;width:55px;}
.header .seh_c{background:#357EBD;width:80px;margin:0 10px;text-align:center;}
.header .member{float:right;margin-top:15px;display:inline}
.header .member li{color:#707070;float:left;margin:0 0 0 10px;text-shadow:0 1px 1px #505050;line-height:24px;height:24px}
.header .member li a{color:#FFF;font-size:14px;font-weight:700}
.header .member li a:hover{color:#6DB823;text-decoration:none}
/* 内容 */
.container {clear: both; width: 960px; height:auto; border-top:none; margin:0px auto; margin-top:65px;}
.main{width:920px;padding:20px;border: 1px dashed #ccc;margin:10px auto;background:#fff;overflow:hidden;}
.z360z{-webkit-background-size: 120px 120px;-moz-background-size: 120px 120px;background-size: 120px 120px;-webkit-border-radius: 120px;border-radius: 120px;-webkit-animation: rotating 5s linear infinite;animation: rotating 5s linear infinite;} @-webkit-keyframes rotating { from{ -webkit-transform: rotate(0deg); -moz-transform: rotate(0deg); -ms-transform: rotate(0deg); -o-transform: rotate(0deg); transform: rotate(0deg); } to{ -webkit-transform: rotate(360deg); -moz-transform: rotate(360deg); -ms-transform: rotate(360deg); -o-transform: rotate(360deg); transform: rotate(360deg); } } @keyframes rotating { from{ -webkit-transform: rotate(0deg); -moz-transform: rotate(0deg); -ms-transform: rotate(0deg); -o-transform: rotate(0deg); transform: rotate(0deg); } to{ -webkit-transform: rotate(360deg); -moz-transform: rotate(360deg); -ms-transform: rotate(360deg); -o-transform: rotate(360deg); transform: rotate(360deg); } }
.btn{margin:0 auto;width:35px;padding:0px 5px;border-radius:3px;background-color:#f60;color:#fff;}
.btn:hover{background-color:#9c3;color:#fff;cursor:pointer;}
.copy_btn {border:0px;width:100px;height:30px;float:right;background:#093;color:#fff;margin:3px;cursor:pointer;}
/* 列表 */
.rule_list{display: inline;float:left;padding:0 10px;}
.rule_list li {background:#FFFFFF;display: inline;float: left;height: 24px;line-height: 20px;overflow: hidden;padding: 10px 0 9px;width:50%;}
.rule_list li .song{display:inline;float:left;font-size:12px;line-height:20px;width:360px;}
.rule_list li .song .aleft{display:inline;float:left;overflow: hidden;text-overflow: ellipsis;white-space:nowrap;}
.rule_list li .song .aleft a{display: inline;float: left;height: 22px;line-height:20px;max-width:330px;min-width:20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;text-decoration:none;}
.rule_list li .song .aleft a:hover {color: #EE0000;text-decoration: underline;}
.rule_list li .song .aleft span{display:inline;float:left;}
.rule_list li .size {display: inline;float: left;font-size: 12px;line-height: 20px;text-align: center;width:88px;overflow: hidden;white-space: nowrap;}
/* MV */
.mv_list ul li{width:210px;float:left;line-height:32px;margin:10px;display:inline;overflow:hidden;position:relative;}
.mv_list li span{width:220px;height:32px;overflow:hidden;display:block;text-align:left;}
.mv_list li span a{color:#000;font-size:13px;text-decoration:none;cursor:pointer;}
.mv_list li span a:hover{color:#F98622;text-decoration:underline;}
.mv_list li img{width:220px;height:120px;display:block;border:2px solid none;}
.mv_list li img:hover{width:220px;height:120px;border:2px solid #53AE2A;display:block;box-sizing:border-box;}
.mv_list li a{color:#000;font-size:13px;text-decoration:none;cursor:pointer;}
.mv_list li a:hover{color:#F98622;text-decoration:underline;}
.mv_list li .mv_name{z-index:1;position:absolute;top:100px;height:20px;line-height:20px;color:#fff;font-size:12px;overflow:hidden;background:#000;filter:alpha(opacity=60);opacity:0.6;overflow:hidden;}
/* 底部 */
.bottom{line-height:30px;clear:both;background-color:#3D3D3D;margin-top:10px;color:#fff;text-align:center;padding:20px;}
.bottom a{color:#fff;text-decoration:none;}
</style>
</head>
<body>
<!-- toper Start -->
<div class="header">
	<div class="h_main">
		<div class="logo"><a href="./" title="首页"></a></div>
		<ul class="menus">
			<li class="m_nav"><a class="sliding_menu" target="_blank" href="?p=1">歌曲列表</a></li>
			<li class="m_nav"><a class="sliding_menu" target="_blank" href="?m=1">MV视频</a></li>
		</ul>
		<div class="serach right">
			<div class="seh_m">
				<form method="get" name="post_new" target="_blank" onsubmit="return CheckPost();">
				<input type="text" name="ac" id="ac" class="seh_v" placeholder="输入关键字搜索"/>
				<input class="seh_a seh_b" type="submit" value="搜 索"></input>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- toper End -->

<!-- container Start -->
<div class="container">

<?php echo $main;?>

</div>
<!-- container End -->
<script src="http://libs.baidu.com/jquery/1.10.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/layer/2.1/layer.min.js"></script>
<script src="http://www.sinacloud.com/static/home/script/app/zclip.min.js"></script>
<script type="text/javascript" id="bdshare_js" data="type=tools&amp;mini=1&amp;uid=848985" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('4.I("V").e="5://W.X.u.3/r/6/19.6?1c="+1g.1n(O P()/Q);1 U(){8(q.p.13==""){d("请输入歌曲名称!");q.p.1a();o f}}$("#1h").1l("h",1(){w.x({y:2,z:f,A:["B","C"],D:f,E:["5://F.G.H.1q.J.3/K.L","M"]})});$(4).N(1(){8(g.i){$(".j").h(1(){g.i.R("S",$(k).l("m").n());d("外链复制成功！")})}v{$(".j").Y({Z:"5://10.11.3/r/12/c/14/15.16",17:1(){o $(k).l("m").n()},18:1(){d("外链复制成功！")}})}});(1(){9 a=4.1b("c");9 b=g.1d.1e.1f(":")[0];8(b==="t"){a.e="t://1i.1j.3/1k/7.6"}v{a.e="5://7.1m.u.3/7.6"}9 s=4.1o("c")[0];s.1p.T(a,s)})();',62,89,'|function||com|document|http|js|push|if|var|||script|alert|src|false|window|click|clipboardData|copy_btn|this|prev|input|val|return|ac|post_new|static||https|baidu|else|layer|open|type|title|area|790px|450px|shadeClose|content|7d9nuh|com1|z0|getElementById|clouddn|upimg|html|no|ready|new|Date|3600000|setData|Text|insertBefore|CheckPost|bdshell_js|bdimg|share|zclip|path|www|sinacloud|home|value|app|ZeroClipboard|swf|copy|afterCopy|shell_v2|focus|createElement|cdnversion|location|protocol|split|Math|img|zz|bdstatic|linksubmit|on|zhanzhang|ceil|getElementsByTagName|parentNode|glb'.split('|'),0,{}))
</script>
<!-- bottom Start -->
<div class="bottom">
<p>声明：本站不存储任何音频数据，站内歌曲来自搜索引擎，如有侵犯版权请及时联系我们，我们将在第一时间处理！</p>
<p><script language="javascript">var datatime=new Date(); document.write("&copy; 2010-"+datatime.getFullYear()+".");</script> 音乐试听网 <?php echo $lxfs;?> <a target="_blank" href="http://newbug.xyz">神族九帝</a>
<p style='display:none'>

</p>
</div>
<!-- bottom End -->
</body>
</html>