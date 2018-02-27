<?php 
?>﻿<?php
include('./inc/aik.config.php');?>
<?php
error_reporting(0);$q=$_GET['wd'];$seach=file_get_contents('http://so.360kan.com/index.php?kw='.$q);$szz='#js-playicon" title="(.*?)"\s*data#';$szz1='#a href="(.*?)" class="g-playicon js-playicon"#';$szz2='#<img src="(.*?)" alt="(.*?)" \/>[\s\S]+?</a>\n</div>#';$szz3='#(<b>(.*?)</b><span>(.*?)</span></li></ul>)?<ul class="index-(.*?)-ul g-clear">(\n\s*)?<li>(\n\s*)?<b>类型：</b>(\n\s*)?<span>(.*?)</span>#';$szz4='#<span class="playtype">(.*?)</span>#';$szz5='#href="(.*?)" class="btn#';preg_match_all($szz,$seach,$sarr);preg_match_all($szz1,$seach,$sarr1);preg_match_all($szz2,$seach,$sarr2);preg_match_all($szz3,$seach,$sarr3);preg_match_all($szz4,$seach,$sarr4);preg_match_all($szz5,$seach,$sarr5);$one=$sarr[1];$two=$sarr2[1];$three=$sarr3[3];$si=$sarr1[1];$wu=$sarr4[1];$liu=$sarr5[1];?>
<?='<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="cache-control" content="no-siteapp">
<title>追热剧-最新电视剧-好看电视剧-最新电视剧排行-'; echo $aik['title'];?><?="</title>
<link rel='stylesheet' id='main-css'  href='css/style.css' type='text/css' media='all' />
<link rel='stylesheet' id='main-css'  href='css/tv.css' type='text/css' media='all' />
<script type='text/javascript' src='http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js?ver=0.5'></script>
<meta name=\"keywords\" content=\"追热剧-最新电视剧-好看电视剧-最新电视剧排行\">
<meta name=\"description\" content=\""; echo $aik['title'];?><?='-追热剧-最新电视剧-好看电视剧-最新电视剧排行">
<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
</head>

<body class="page-template page-template-pages page-template-posts-tvshow page-template-pagesposts-tvshow-php page page-id-10">
';  include 'header.php';?>
<?='<section class="container">
<div style="text-align: center;padding: 10px 0;color: #FF7562;font-size: 12px;">温馨提示:请点击搜索【'; echo $q?><?='】结果的标题或封面图进行观看</div>
<div class="m-g">
<div class="b-listtab-main">
<div class="s-tab-main">
<ul class="list g-clear">
<style>
.zanwu{position:absolute;left:0px;bottom:0px;height:100%;background:#000;display:block;zoom:1;filter:alpha(opacity=40);opacity:0.5;width:100%;color:#fff;}
</style>
'; foreach($one as $ni=>$cs){$sijm=base64_encode($si[$ni]);$ye=substr($liu[$ni],11,3);if($ye==360){echo "
<li class='item'>
<script type='text/javascript'>
function docheck()
{
       alert('暂无播放资源，请等待更新!');
}
</script>
<a href='#' onclick='docheck()'>
<div class='cover zanwu'>
<img  src='$two[$ni]' alt='$cs' style='display: block;'>
<span class='hint'>暂无播放资源</span> </div>
  <div class='detail'>
 <p class='title g-clear'>
 <span class='s1'>$cs</span>
 <span class='s2'></span> </p>
 <p class='star'>$wu[$ni]<font color='#FF0000'></font></p>

</div>
</a>
</li>

";}else{echo "
<li class='item'>
<a class='js-tongjic' href='./play.php?play=$sijm' title='$cs' target='_blank'>
<div class='cover g-playicon'>
<img  src='$two[$ni]' alt='$cs' style='display: block;'>
<span class='hint'>$three[$ni]</span> </div>
  <div class='detail'>
 <p class='title g-clear'>
 <span class='s1'>$cs</span>
 <span class='s2'></span> </p>
 <p class='star'>$wu[$ni]</p>
 
</div>
</a>
</li>
";}}?><?='                   

  </ul>
      </div>


    </div>
</div> 

<div class="asst asst-list-footer">'; echo $aik['movie_ad'];?></div></section>
<?php  include 'footer.php';?>
</body></html><?php  ?>
