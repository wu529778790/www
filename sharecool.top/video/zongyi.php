<?php 

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
<title>综艺列表-'; echo $aik['title'];?><?="</title>
<link rel='stylesheet' id='main-css'  href='css/style.css' type='text/css' media='all' />
<link rel='stylesheet' id='main-css'  href='css/movie.css' type='text/css' media='all' />
<script type='text/javascript' src='http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js?ver=0.5'></script>
<meta name=\"keywords\" content=\"综艺排行\">
<meta name=\"description\" content=\""; echo $aik['title'];?><?='-综艺排行">
<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
</head>
<body class="page-template page-template-pages page-template-posts-tvshow page-template-pagesposts-tvshow-php page page-id-10">
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
$link='http://www.360kan.com/zongyi/list.php?cat=all&pageno=1';$link0=base64_encode($link);?>
<a href='?m=<?php echo $link0;?>' target='_self'>全部</a>
<?php
include 'list.php';$page=$_GET['page'];foreach($zycat as $kcat=>$vcat){$flzyname=$zyname[$kcat];$flid='http://www.360kan.com/zongyi/list.php?cat='.$vcat.'&pageno=1';$flid2=base64_encode($flid);echo "<a href='?m=$flid2' target='_self'>$flzyname</a>";}?>
<?='</dd>
</dl>
</div>
</div>
<div class="m-g">
<div class="b-listtab-main">
<div class="s-tab-main">
                    <ul class="list g-clear">
                    ';
$flid1=$_GET['m'];$arr=explode('pageno',$flid1);$yourneed=$arr[0];$yema=base64_decode($yourneed);$arr=explode('pageno',$yema);$yemama=$arr[0];$mama='pageno=';$flid2=''.$yemama.$mama.$page.'';$rurl=file_get_contents($flid2);$vname='#<span class="s1">(.*?)</span>#';$vlist='#<a class="js-tongjic" href="(.*?)">#';$vstar='# <p class="star">(.*?)</p>#';$vimg="#<div class=\"cover g-playicon\">\r\n                                <img src=\"(.*?)\">\r\n#";$bflist='#<a data-daochu(.*?) href="(.*?)" class="js-site-btn btn btn-play"></a>#';$jishu='#<span class="hint">(.*?)</span> #';$yuming='http://www.360kan.com';preg_match_all($vname,$rurl,$xarr);preg_match_all($vlist,$rurl,$xarr1);preg_match_all($vstar,$rurl,$xarr2);preg_match_all($vimg,$rurl,$xarr3);preg_match_all($bflist,$rurl,$xarr4);preg_match_all($jishu,$rurl,$xarr5);$xname=$xarr[1];$xlist=$xarr1[1];$xstar=$xarr2[1];$ximg=$xarr3[1];$xbflist=$xarr4[1];$xjishu=$xarr5[1];foreach($xname as $key=>$xvau){$do=$yuming.$xlist[$key];$do1=base64_encode($do);$cc='./play.php?play=';$ccb=$cc.$do1;echo "<li class='item'>
    <a class='js-tongjic' href='$ccb' title='$xvau' target='_blank'>
<div class='cover g-playicon'>
<img src='$ximg[$key]' alt='$xvau'/>
  <span class='hint'>$xjishu[$key]</span> </div>
  <div class='detail'>
 <p class='title g-clear'>
 <span class='s1'>$xvau</span>
   <span class='s2'></span>
                                </p>
                                <p class='star'>$xstar[$key]</p>
 </div>
 </a>
</li>";}?>
<?='
</li>
  </ul>
      </div>


    </div>
</div> 
        ';  $b=(strpos($flid2,'cat='));$c=(strpos($flid2,'&p'));$ye=substr($flid2,$b+4,$b-$c-1);if($ye==108){$fenye=('14');}elseif($ye==104){$fenye=('10');}elseif($ye==102||$ye==101){$fenye=('23');}elseif($ye==110||$ye==109){$fenye=('12');}elseif($ye==111){$fenye=('7');}elseif($ye==116||$ye==112){$fenye=('5');}elseif($ye==115||$ye==113){$fenye=('8');}elseif($ye==114){$fenye=('6');}elseif($ye==119||$ye==117){$fenye=('4');}elseif($ye==118){$fenye=('2');}else{$fenye=('24');}?>
 <div class="paging"><?php echo getPageHtml($page,$fenye,'zongyi.php?m='.$yourneed.'pageno=');?><a>共<?php echo $fenye;?><?='页</a>
</div>
<div class="asst asst-list-footer">'; echo $aik['zongyi_ad'];?></div></section>
<?php  include 'footer.php';?>
</body></html><?php  ?>
