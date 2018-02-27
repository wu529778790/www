<?php 
?>﻿<?php
error_reporting(0);$player=base64_decode($_GET['play']);?>

<?php
include('./inc/aik.config.php');?>
<?='<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta http-equiv="cache-control" content="no-siteapp">
<link rel=\'stylesheet\' id=\'main-css\'  href=\'css/style.css\' type=\'text/css\' media=\'all\' />
<link rel=\'stylesheet\' id=\'main-css\'  href=\'css/play.css\' type=\'text/css\' media=\'all\' />
<script type=\'text/javascript\' src=\'http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js?ver=0.5\'></script>

<meta name="keywords" content="搞笑视频-播放页">
<title>搞笑视频-正在播放-'; echo $aik['sitename'];?><?='</title>
<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
</head>
<style>
  .single-strong {color: #606060;font-size: 14px;font-weight: 700;border-left: 3px #ff5c5c solid;padding-left: 10px;display: block;line-height: 32px;margin: 10px 0;}
.html5play iframe{
	    height: 600px;
}
@media only screen and (max-width:420px){
.html5play iframe{
	    height: 260px;
}
}
</style>

<body class="page-template page-template-pages page-template-posts-play page-template-pagesposts-play-php page page-id-16">
';  include 'header.php';?>
<?='<div class="single-post">
<section class="container">
    <div class="content-wrap">
    	<div class="content">
<div class="asst asst-post_header">'; echo $aik['bofang_ad'];?><?='</div>
<div class="sptitle"><h1>
'; echo $timu;?><?='</h1></div>
<div id="bof">
</div>
<div class="am-cf"></div>
<div class="am-panel am-panel-default">
<div class="am-panel-bd">
<div class="html5play">
<iframe name="ajax" src="'; echo $aik['jiekou1'];?>http:<?php echo $player;?><?='" marginwidth="0" marginheight="0" border="0" scrolling="no" frameborder="0" topmargin="0" width="100%" height="100%"></iframe>
</div>
<div style="clear: both;"></div> 
</div>
</div>
<!--PC和WAP自适应版-->
'; echo $aik['changyan'];?>
<?='<div class="article-actions clearfix">
 <div class="shares">
        <strong>分享到：</strong>
        <a href="javascript:;" data-url="'; echo $aik['pcdomain'];?><?='" class="share-weixin" title="分享到微信"><i class="fa"></i></a><a etap="share" data-share="qzone" class="share-qzone" title="分享到QQ空间"><i class="fa"></i></a><a etap="share" data-share="weibo" class="share-tsina" title="分享到新浪微博"><i class="fa"></i></a><a etap="share" data-share="tqq" class="share-tqq" title="分享到腾讯微博"><i class="fa"></i></a><a etap="share" data-share="qq" class="share-sqq" title="分享到QQ好友"><i class="fa"></i></a><a etap="share" data-share="renren" class="share-renren" title="分享到人人网"><i class="fa"></i></a><a etap="share" data-share="douban" class="share-douban" title="分享到豆瓣网"><i class="fa"></i></a>
    </div>   
 <a href="javascript:;" class="action-rewards" etap="rewards">打赏</a>
    </div> 
</div>
    	</div>
';  include 'sidebar.php';?>
</section>
</div>
<?php  include 'footer.php';?>
</body>
</html>
