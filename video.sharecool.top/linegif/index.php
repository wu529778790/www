<?php
// 公共头部文件
require 'header.php';
// 网页head 标题 标题描述 关键字
title('图片表情制作,表情下载制作,QQ斗图系列', 'Q民云图片,拥有最全的图片功能网站,手持举牌照在线制作,装逼图片在线制作,表情在线制作', '网页关键字,用逗号分隔,可以随意添加');
?>

    <!--瀑布流插件-->
    <script type="text/javascript" src="js/jquery.masonry.min.js"></script>
    
    <!--滚动加载插件-->
    <script type="text/javascript" src="js/jquery.lazyload.min.js"></script>
    
    <style>
    .scrollLoading{
        min-height: 160px;
    }
    #mainList{
        padding-top:15px;
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

<?php
// 网站导航栏
banner(1);
?>

<!--<div class="alert alert-info" role="alert">-->
<!--    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
<!--    <strong><span class="glyphicon glyphicon-bullhorn"></span>&nbsp;好消息：</strong>“表情大全”中的全部表情图均已恢复！&nbsp;<a href="http://zb.mkblog.cn/more.php">点击查看</a>-->
<!--</div>-->

<div id="mainList">    

<?php
function echoTu($url,$title,$img)
{
?>
    <a href="<?php echo $url;?>">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 single">
            <div class="thumbnail">
                <img class="scrollLoading" alt="<?php echo $title;?>表情图制作" title="点击进入“<?php echo $title;?>”表情图制作" src="images/loading.gif" data-original="<?php echo $img;?>">
            </div>   
            <div class="caption">
                <span><?php echo $title;?></span>
            </div>  
        </div>
    </a>
<?php
}

echoTu('pic.php?tu=1', '装逼王我当定了', 'images/1.jpg');

echoTu('pic.php?tu=2', '我很欣赏你', 'images/2.jpg');

echoTu('pic.php?tu=3', '让我们谈谈人生', 'images/3.jpg');

echoTu('pic.php?tu=4', '试试就试试', 'images/4.jpg');

echoTu('pic.php?tu=5', '拜托你了', 'images/5.jpg');

echoTu('pic.php?tu=6', '这时候我只能呵呵', 'images/6.jpg');

echoTu('pic.php?tu=7', '嘿嘿', 'images/7.jpg');

echoTu('pic.php?tu=8', '我要告诉全世界', 'images/8.jpg');

echoTu('pic.php?tu=9', '说的就是你', 'images/9.jpg');

echoTu('pic.php?tu=10', '以我的知识来看', 'images/10.jpg');

echoTu('pic.php?tu=11', '狗狗乖', 'images/11.jpg');

echoTu('pic.php?tu=12', '欺负小朋友', 'images/12.jpg');

echoTu('pic.php?tu=13', '你根本不是司机', 'images/13.jpg');

echoTu('pic.php?tu=14', '我就不', 'images/14.jpg');

echoTu('pic.php?tu=15', '食屎了你', 'images/15.jpg');

echoTu('pic.php?tu=16', '快上车', 'images/16.jpg');

echoTu('pic.php?tu=17', '老夫听着呢', 'images/17.jpg');

echoTu('pic.php?tu=18', '满脸期待', 'images/18.jpg');

echoTu('pic.php?tu=19', '城里人真会玩', 'images/19.jpg');

echoTu('pic.php?tu=20', '又让你装了个漂亮逼', 'images/20.jpg');

echoTu('pic.php?tu=21', 'Doge的凝视', 'images/21.jpg');

echoTu('pic.php?tu=22', '怪我咯', 'images/22.jpg');

echoTu('pic.php?tu=23', '朕要开始装逼了', 'images/23.jpg');

echoTu('pic.php?tu=24', '一巴掌扇你脸上', 'images/24.jpg');

echoTu('pic.php?tu=25', '老哥稳', 'images/25.jpg');

echoTu('pic.php?tu=26', '我也很无奈啊', 'images/26.jpg');

echoTu('pic.php?tu=27', '我可能是假的', 'images/27.jpg');

echoTu('pic.php?tu=28', '装逼界的老大', 'images/28.jpg');

echoTu('maker/?id=2', '“算我输”表情生成', 'api/?types=maker&id=2&cover=true');

echoTu('maker/?id=6', '小明被抓表情生成', 'api/?types=maker&id=6&cover=true');

echoTu('maker/?id=7', '秒打脸对话生成', 'api/?types=maker&id=7&cover=true');

echoTu('maker/?id=8', '友谊的小船', 'api/?types=maker&id=8&cover=true');

echoTu('maker/?id=9', '鸡年大吉表情生成', 'api/?types=maker&id=9&cover=true');

echoTu('maker/?id=12', '王宝强泰囧对话生成', 'api/?types=maker&id=12&cover=true');

echoTu('pic.php?tu=29', '拍桌子', 'images/29.jpg');

echoTu('pic.php?tu=30', '吃我一脚', 'images/30.jpg');

echoTu('pic.php?tu=31', '窗边的doge', 'images/31.jpg');

echoTu('pic.php?tu=32', '葛优躺', 'images/32.jpg');
?>

</div>  <!--id="mainList"--->

<div class="clearfix"></div>

<p class="text-center">更多表情制作正在逐步添加中</p>

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
		effect:"show",     // effect(特效),值有show(直接显示),fadeIn(淡入),slideDown(下拉)等,常用fadeIn
		failurelimit:20,    //加载N张可加区域外的图片
		load:resetPage,     //加载完的回调函数
		threshold :180, //距离屏幕180px即开始加载
		//placeholder : "img/grey.gif", //用图片提前占位
    });
});

window.onresize = function() {resetPage();};
</script>


<?php
//网站底部
footer();
?>