<?php 
?>﻿<?php
include('./inc/aik.config.php');include('./inc/fenye.php');?>
<?='<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="cache-control" content="no-siteapp">
<title>搞笑列表-'; echo $aik['title'];?><?="</title>
<link rel='stylesheet' id='main-css'  href='css/style.css' type='text/css' media='all' />
<link rel='stylesheet' id='main-css'  href='css/gaoxiao.css' type='text/css' media='all' />
<script type='text/javascript' src='http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js?ver=0.5'></script>
<meta name=\"keywords\" content=\"搞笑排行\">
<meta name=\"description\" content=\""; echo $aik['title'];?><?='-搞笑排行">
<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
</head>

<body class="page-template page-template-pages page-template-posts-film page-template-pagesposts-film-php page page-id-9">
';  include 'header.php';?>
<?='<section class="container"><div class="fenlei">
<div class="b-listfilter" style="padding: 0px;">
<style>
#noall{
	background-color: #ff6651;
    color: #fff;
}
</style>
<dl class="b-listfilter-item js-listfilter" style="padding-left: 0px;height:auto;padding-right:0px;">
<dd class="item g-clear js-listfilter-content" style="margin: 0;">
';
 $do1='http://list.youku.com/category/video/c_94_g__d_1_s_1_p_1';$do2='http://list.youku.com/category/video/c_94_g_235_d_1_s_1_p_1';$do3='http://list.youku.com/category/video/c_94_g_236_d_1_s_1_p_1';$do4='http://list.youku.com/category/video/c_94_g_238_d_1_s_1_p_1';$do5='http://list.youku.com/category/video/c_94_g_3072_d_1_s_1_p_1';$do11=base64_encode($do1);$do21=base64_encode($do2);$do31=base64_encode($do3);$do41=base64_encode($do4);$do51=base64_encode($do5);?>
<a href="?m=<?php echo $do11;?>">全部</a>
<a href="?m=<?php echo $do21;?>">恶搞短片</a>
<a href="?m=<?php echo $do31;?>">搞笑自拍</a>
<a href="?m=<?php echo $do41;?>">搞笑动物</a>
<a href="?m=<?php echo $do51;?>">搞笑达人</a>
<?php
include 'list.php';$page=$_GET['page'];?>
<?='</dd>
</dl>
</div>
</div>
<div class="m-g">
<div class="b-listtab-main">
<div class="box-bd">
<ul class="mod-pic">	
';
$flid1=$_GET['m'];$arr=explode('p_',$flid1);$yourneed=$arr[0];$yema=base64_decode($yourneed);$arr=explode('p_',$yema);$yemama=$arr[0];$mama='p_';$flid2=''.$yemama.$mama.$page.'';$rurl=file_get_contents($flid2);$vname='#<div class="yk-col4 "><div class="yk-pack p-list" taglog=""><div class="p-thumb"><a href="(.*?)" target="_blank" title="(.*?)"></a><i class="bg"></i><img class="quic"  src="(.*?)" alt="(.*?)"/></div><ul class="p-info pos-bottom"><li class="status"><span class="p-time hover-hide"><i class="ibg"></i><span>(.*?)</span></span></li></ul><ul class="info-list"><li class="title"><a href="(.*?)" target="_blank" title="(.*?)">(.*?)</a></li><li class=" ">(.*?)</li></ul></div></div>#';preg_match_all($vname,$rurl,$xarr);preg_match_all($yeshu,$rurl,$xarr1);$xbflist=$xarr[1];$xname=$xarr[2];$ximg=$xarr[3];$shijian=$xarr[5];$lianye=$xarr1[1];$fenye=$xarr1[2];foreach($xname as $key=>$xvau){$do=$xbflist[$key];$do1=base64_encode($do);$cc='./mplay.php?play=';$ccb=$cc.$do1;echo "
	<li>							
<a class='hele-text' href='$cc$do1' target='_blank'>$xname[$key]</a>
<a href='$cc$do1' target='_blank'><img src='$ximg[$key]' border='0' width='120' height='120' alt='$xname[$key]'></a><span>$shijian[$key]</span>								
								</li>
";}?>
<?='  </ul>
      </div>


    </div>
</div> <div class="paging">
';  $b=(strpos($yema,'g_'));$c=(strpos($yema,'_d'));$ye=substr($yema,$b+2,$b-$c-7);if($ye==235){$fenye=('25');}elseif($ye==236){$fenye=('12');}elseif($ye==238){$fenye=('15');}elseif($ye==307){$fenye=('1');}else{$fenye=('25');}echo getPageHtml($page,$fenye,'gaoxiao.php?m='.$yourneed.'p_');?><a>共<?php echo $fenye;?><?='页</a></div>
<div class="asst asst-list-footer">'; echo $aik['movie_ad'];?></div></section>
<?php  include 'footer.php';?>
</body></html>
