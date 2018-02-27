<?php

define('MK_Pic','mengkunsoft');

// 公共头部文件
require 'header.php';

// 请求公用函数模块
require("inc/com_function.php");

// 网页head 标题 标题描述 关键字
title('表情图大全','网页描述','网页关键字,用逗号分隔');


$loadNum = 30;  // 每页加载的表情数

$pn = getParam('pn', 1);    // 获取页码

?>

    <!--瀑布流插件-->
    <script type="text/javascript" src="js/jquery.masonry.min.js"></script>
    
    <!--滚动加载插件-->
    <script type="text/javascript" src="js/jquery.lazyload.min.js"></script>
    
    <style>
    .scrollLoading{
        min-height: 160px;
    }
    #selectPage{  /*选择页码*/
        width:108px;
        display: inline-block;
        border:none;
        text-align:center;
        padding:5px 14px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 15px;
        color: #337ab7;
        outline: 0;
    }
    #selectPage:active, #selectPage:hover {
        outline: 0;
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
    	$("#selectPage").val(<?php echo $pn; ?>);
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
    <li role="presentation" class="active"><a href="#">顺序浏览</a></li>
    <li role="presentation"><a href="rand.php">手气不错</a></li>
</ul>

<br>

<!--<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
公告:由于本站使用的图床服务器出了一点小问题，导致部分图片无法显示。目前正在紧急修复中，请稍安勿躁。
</div>-->

<div  style="margin-top:15px;" id="mainList">

<?php

$imgList = readPic(C('imgPath'));

$imgCount = count($imgList);    // 图片总数

$maxPn = floor($imgCount/$loadNum);

if($pn >= $maxPn) $pn = $maxPn;

$fileLines = array_slice($imgList, $imgCount - $pn * $loadNum, $loadNum);

for($i=0; $i<$loadNum; $i++){
    $picUrl = $fileLines[$loadNum - $i - 1];
    echo'
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 single">
        <div class="thumbnail">
            <img src="images/loading.gif" data-original="'.C('imgPath').'/'.$picUrl.'" class="scrollLoading">
        </div>
    </div>
    ';
}
?>

</div><!--id="mainList"-->

<div class="clearfix"></div>

<nav>
    <ul class="pager">
        <li<?php if($pn<=1){echo' class="disabled"><a>';}else{echo '><a href="more.php?pn='.($pn-1).'">';} ?>上一页</a></li>
        <li>
            <select onchange="window.location.href='more.php?pn='+document.getElementById('selectPage').value" id="selectPage" class="form-control">
            <?php
            for($i=1; $i<=$maxPn; $i++){
                echo '<option value="'.$i.'">第'.$i.'页</option>'."\n";
            }
            ?>
            </select>
        </li>
        <li
        <?php 
            if($pn >= $maxPn){
                echo' class="disabled"><a>';
            } else {
                echo '><a href="more.php?pn='.($pn + 1).'">';
            } 
        ?>下一页</a></li>
    </ul>
</nav>

<?php
//网站底部
footer();
?>