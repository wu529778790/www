<?php
// 公共头部文件
require 'header.php';
// 网页head 标题 标题描述 关键字
title('装逼神器', '请修改这里的文字为网页描述', '网页关键字,用逗号分隔,可以随意添加');
?>

    <!--瀑布流插件-->
    <script type="text/javascript" src="js/jquery.masonry.min.js"></script>
    
    <script type="text/javascript">
    function pubuPaiban(){
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
    	pubuPaiban();
    });
    
    window.onresize = function() {pubuPaiban();};
    </script>

<?php
// 网站导航栏
banner(5);
?>
<div style="margin-top:15px;" id="mainList">    

<?php
function echoTu($url,$title,$img)
{
?>
    <a href="<?php echo $url;?>">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 single">
            <div class="thumbnail">
                <img alt="<?php echo $title;?>装逼图制作" title="点击进入“<?php echo $title;?>”装逼图制作" src="<?php echo $img;?>">
            </div>   
            <div class="caption">
                <span><?php echo $title;?></span>
            </div>  
        </div>
    </a>
<?php
}

echoTu('maker/?id=13', '灾难大片海报生成', 'api/?types=maker&id=13&cover=true');

echoTu('maker/?id=11', '搞笑奖状在线制作', 'api/?types=maker&id=11&cover=true');

echoTu('maker/?id=1', '朋友圈气泡大字图生成器', 'api/?types=maker&id=1&cover=true');

echoTu('maker/?id=10', '朋友圈尖叫体生成器', 'api/?types=maker&id=10&cover=true');

echoTu('maker/?id=3', '支付宝余额图生成器', 'api/?types=maker&id=3&cover=true');

echoTu('maker/?id=4', '微信零钱图生成器', 'api/?types=maker&id=4&cover=true');

echoTu('maker/?id=5', '支付宝五福图生成器', 'api/?types=maker&id=5&cover=true');
?>


</div>  <!--id="mainList"--->

<div class="clearfix"></div>

<p class="text-center">更多装逼图片制作正在逐步添加中</p>

<?php
//网站底部
footer();
?>