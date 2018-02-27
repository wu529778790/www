<?php
define('MK_Pic','mengkunsoft');

// 公共头部文件
require 'header.php';

// 请求公用函数模块
require("inc/com_function.php");

// 网页head 标题 标题描述 关键字
title('表情图大全', '请修改这里的文字为网页描述', '网页关键字,用逗号分隔,可以随意添加');


$loadNum = 30;  // 每页加载的表情数

?>

    <style>
    .scrollLoading{
        min-height: 160px;

    }
    @media screen and (max-width: 600px) {  /*针对小屏幕的优化*/
        .scrollLoading{
            height: 160px!important;
        }
    }
    .single:hover{
        -webkit-animation: dance 5s infinite ease-in-out;
        animation: dance 5s infinite ease-in-out;
    }
    </style>

    <!--瀑布流插件-->
    <script type="text/javascript" src="js/jquery.masonry.min.js"></script>
    
    <!--滚动加载插件-->
    <script type="text/javascript" src="js/jquery.lazyload.min.js"></script>
    
    <script type="text/javascript">
    function resetPage(){   //将页面图片进行瀑布排版处理
        var $container = $('#mainList');	
        $container.imagesLoaded(function(){
            $container.masonry({
                itemSelector: '.single',
                columnWidth: 0, //每两列之间的间隙为0像素
                isAnimated: true    //开启滑动动画
            });
        });
    }
    
    $(document).ready(function(){
    	resetPage();
    });
    
    $(function() {
    	$("img").lazyload({
		effect:"fadeIn",     // effect(特效),值有show(直接显示),fadeIn(淡入),slideDown(下拉)等,常用fadeIn
		failurelimit:5,    //加载N张可加区域外的图片
		load:resetPage,     //加载完的回调函数
		threshold :180, //距离屏幕180px即开始加载
		//placeholder : "img/grey.gif", //用图片提前占位
	});
    });

    window.onresize = function() {resetPage();};
    </script>

<?php
// 网站导航栏
banner(3);
?>

<p class="text-center">长按或点击鼠标右键可以将图片发送给你的朋友们</p>

<ul class="nav nav-tabs">
    <li role="presentation"><a href="more.php">顺序浏览</a></li>
    <li role="presentation" class="active"><a href="rand.php">手气不错</a></li>
</ul>

<div  style="margin-top:15px;" id="mainList">

<?php

$imgName = readPic(C('imgPath'));

$imgRamdom = array_rand($imgName, $loadNum);

foreach($imgRamdom as $img)
{
    echo'
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 single">
        <div class="thumbnail">
            <img src="images/loading.gif" data-original="'.C('imgPath').'/'.$imgName[$img].'" class="scrollLoading">
        </div>
    </div>
    ';
}

?>

</div><!--id="mainList"-->

<div class="clearfix"></div>

<nav>
    <ul class="pager">
    <li><a href="#" onclick="javascript:location.reload();"><span class="glyphicon glyphicon-refresh"></span>&nbsp; 换一批</a></li>
    </ul>
</nav>

<?php
//网站底部
footer();
?>