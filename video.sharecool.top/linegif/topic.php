<?php
// 公共头部文件
require 'header.php';
// 网页head 标题 标题描述 关键字
title('表情专题', '请修改这里的文字为网页描述', '网页关键字,用逗号分隔,可以随意添加');
?>

    <style>
    .scrollLoading{
        min-height: 130px;
        width: 100%;
        display: block;
    }
    hr{
        margin: 5px 0;
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
banner(4);

function addOne($img, $title, $direction, $url)
{
echo <<<HTML
    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 single">
        <div class="thumbnail">
            <img class="scrollLoading" src="images/loading.gif" data-original="images/topic/{$img}" alt="{$direction}" title="{$direction}">
            <hr>
            <div class="caption1">
                <h3>{$title}</h3>
                <p><a href="{$url}" target="_blank" class="btn btn-primary" role="button" title="前往下载{$direction}">前往下载</a></p>
            </div>
        </div>
    </div> 
HTML;
}
?>
<!--<div class="alert alert-danger alert-dismissable">-->
<!--    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;-->
<!--    </button>-->
<!--<a href="http://yun.mkblog.cn/index.php?share/folder&user=1&sid=Mmjr49sn#" target="_blank">点击下载鸡年大吉表情包</a>-->
<!--</div>-->

<div  style="margin-top:15px;" id="mainList">    

<?php
    addOne('4.jpg', '表情大全打包(一)', '【第四期】本站“表情大全”栏目的表情图片打包下载', 'http://yun.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=862806062677219');
    addOne('5.jpg', '表情大全打包(二)', '【第五期】本站“表情大全”栏目的表情图片打包下载', 'http://yun.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=425669574410737');
    addOne('9.jpg', '表情大全打包(三)', '【第九期】本站“表情大全”栏目的表情图片打包下载', 'http://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=767884928264498');
    addOne('3.gif', '嗷大喵表情大全', '【第三期】嗷大喵表情大全', 'http://yun.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=114418124132005');
    addOne('1.png', '骑行专用表情包', '【第一期】骑行专用表情包', 'http://yun.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=874445758676552');
    addOne('2.png', '金馆长熊猫头大全', '【第二期】金馆长熊猫头大全', 'http://yun.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=1032784696812292');
    addOne('6.jpg', '蓝瘦香菇', '【第六期】蓝瘦香菇', 'http://yun.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=753919038902944');
    addOne('7.gif', 'VIP表情', '【第七期】VIP表情', 'http://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=1008785628083161');
    addOne('8.png', '超大版emoji', '【第八期】超大版emoji', 'http://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=194199252156100');
    addOne('10.gif', 'QQ表情夸张版', '【第十期】QQ表情夸张版', 'http://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=1070132976728592');
    addOne('11.gif', 'QQ原创表情', '【第十一期】QQ原创表情', 'http://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=728905809047799');
    addOne('12.png', '阿鲁表情大全', '【第十二期】阿鲁表情大全', 'http://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=268283196401269');
    addOne('13.png', '社交软件表情', '【第十三期】社交软件表情大全(包含新版QQ、贴吧、微博的默认表情)', 'http://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=382840189618447');
    addOne('14.jpg', '算我输表情', '【第十四期】“算我输”系列表情', 'https://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=714333463110829');
    addOne('15.jpg', '1000张怼人表情', '【第十五期】1000张怼人图片(来自微博 @马克少年)', 'https://pan.baidu.com/pcloud/album/file?album_id=1749760194719635076&uk=2116032069&fsid=532281821230859');
?>

</div>  <!--id="mainList"--->

<div class="clearfix"></div>

<p class="text-center">更多专题表情正在逐步添加中</p>

<?php
//网站底部
footer();
?>