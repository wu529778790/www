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
<title>'; echo stripslashes($aik['title']);?><?="</title>
<link rel='stylesheet' id='main-css'  href='css/style.css' type='text/css' media='all' />
<link rel='stylesheet' id='main-css'  href='css/index.css' type='text/css' media='all' />
<script type='text/javascript' src='http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js?ver=0.5'></script>
<meta name=\"keywords\" content=\""; echo stripslashes($aik['keywords']);?><?='">
<meta name="description" content="'; echo stripslashes($aik['description']);?><?='">
<!--[if lt IE 9]><script src="js/html5.js"></script><![endif]-->
</head>
<body class="home blog">
';  include 'header.php';?>
<?='<div id="homeso">
<form method="post" id="soform" style="text-align: center;float: none" action="./seacher.php">
'; echo stripslashes($aik['logo_ss']);?><?='<br><br>
<input tabindex="2" class="homesoin" id="sos" name="wd" type="text" placeholder="输入你要观看的视频" value="">
<button id="button" tabindex="3" class="homesobtn" type="submit"><i class="fa">观看</i></button>
</form>


</div>
';
 $do1='http://www.360kan.com/dianying/list.php?cat=all&pageno=1';$do2='http://www.360kan.com/dianshi/list.php?cat=all&pageno=1';$do11=base64_encode($do1);$do21=base64_encode($do2);?>
<?='<section class="container">
<div class="single-strong">最新热门电影推荐<span class="chak"><a href="./movie.php?m='; echo $do11;?><?='">查看更多</a></span></div>
<div class="b-listtab-main">
<div class="s-tab-main">
<ul class="list g-clear">


';  include './data/dyjx2.php';foreach($namearr[1] as $key=>$value){$gul=$yuming.$listarr[1][$key];$cd=$host.'/alist.php?id='.$gul;$guq=$listarr[1][$key];$_GET['id']=$gul;$zimg=$imgarr[1][$key];$zname=$namearr[1][$key];$fname=$fnamearr[1][$key];$nname=$nnamearr[1][$key];$zstar=$stararr[1][$key];$jiami=base64_encode($gul);$tok=base64_encode($gul);$chuandi=$host.'/inc/b.php?id='.$jiami;echo "
		   <li  class='item'><a class='js-tongjic' href='./play.php?play=$tok' title='$zname' target='_blank'>
         <div class='cover g-playicon'>
          <img src='$zimg' alt='$zname' />
          <span class='pay'>推荐</span>       <span class='hint'>$nname</span>
         </div>
         <div class='detail'>
          <p class='title g-clear'>
		    <span class='s1'>$zname</span>
			<span class='s2'>$fname</span></p>
           <p class='star'>$zstar</p>
          </div>
         </a></li>";}?>
<?='</ul>
</div>
</div>


<div class="single-strong">最新热门电视剧推荐<span class="chak"><a href="./movie.php?m='; echo $do21;?><?='">查看更多</a></span></div>
<div class="b-listtab-main">
<div class="s-tab-main">
<ul class="list g-clear">
';  include './data/tvjx.php';foreach($namearr[1] as $key=>$value){$gul=$yuming.$listarr[1][$key];$cd=$host.'/alist.php?id='.$gul;$guq=$listarr[1][$key];$_GET['id']=$gul;$zimg=$imgarr[1][$key];$zname=$namearr[1][$key];$nname=$nnamearr[1][$key];$zstar=$stararr[1][$key];$jiami=base64_encode($gul);$chuandi='./play.php?play='.$jiami;echo "<li class='item'><a class='js-tongjic' href='$chuandi' title='$zname'>
         <div class='cover g-playicon'>
          <img src='$zimg' alt='$zname' />
          <span class='hint'>$nname</span>
         </div>
         <div class='detail'>
		 <p class='title g-clear'>
           <span class='s1'>$zname</span>
           <span class='s2'></span></p>
         <p class='star'>$zstar</p>
          </div>
         </a></li>";}?>
<?='</ul>
</div>
</div>
<div class="single-strong">最新热门综艺推荐<span class="chak"><a href="./movie.php?m='; echo $do21;?><?='">查看更多</a></span></div>
<div class="b-listtab-main">
<div class="s-tab-main">
<ul class="list g-clear">
';  include './data/zydy.php';foreach($namearr[1] as $key=>$value){$gul=$yuming.$listarr[1][$key];$cd=$host.'/alist.php?id='.$gul;$guq=$listarr[1][$key];$_GET['id']=$gul;$zimg=$imgarr[1][$key];$zname=$namearr[1][$key];$nname=$nnamearr[1][$key];$zstar=$stararr[1][$key];$jiami=base64_encode($gul);$chuandi='./play.php?play='.$jiami;echo "<li class='item'><a class='js-tongjic' href='$chuandi' title='$zname'>
         <div class='cover g-playicon'>
          <img src='$zimg' alt='$zname' />
          <span class='hint'>$nname</span>
         </div>
         <div class='detail'>
		 <p class='title g-clear'>
           <span class='s1'>$zname</span>
           <span class='s2'></span></p>
         <p class='star'>$zstar</p>
          </div>
         </a></li>";}?>
<?='</ul>
</div>
</div>
<div class="single-strong">最新热门动漫推荐<span class="chak"><a href="./movie.php?m='; echo $do21;?><?='">查看更多</a></span></div>
<div class="b-listtab-main">
<div class="s-tab-main">
<ul class="list g-clear">
';  include './data/dmdy.php';foreach($namearr[1] as $key=>$value){$gul=$yuming.$listarr[1][$key];$cd=$host.'/alist.php?id='.$gul;$guq=$listarr[1][$key];$_GET['id']=$gul;$zimg=$imgarr[1][$key];$zname=$namearr[1][$key];$nname=$nnamearr[1][$key];$zstar=$stararr[1][$key];$jiami=base64_encode($gul);$chuandi='./play.php?play='.$jiami;echo "<li class='item'><a class='js-tongjic' href='$chuandi' title='$zname'>
         <div class='cover g-playicon'>
          <img src='$zimg' alt='$zname' />
          <span class='hint'>$nname</span>
         </div>
         <div class='detail'>
		 <p class='title g-clear'>
           <span class='s1'>$zname</span>
           <span class='s2'></span></p>
         <p class='star'>$zstar</p>
          </div>
         </a></li>";}?>
<?='</ul>
</div>
</div>
</section>
';  include 'footer.php';?>
</body>
</html>
