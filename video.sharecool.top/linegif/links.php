<?php
// 公共头部文件
require 'header.php';
// 网页head 标题 标题描述 关键字
title('斗图导航','','斗图导航,表情包网站大全');
?>

    <style type="text/css">
    /*放置链接框的区域*/
    .link-box-area{
        padding-top: 25px;
        overflow: hidden;
        zoom: 1;
    }
    
    /*链接框*/
    .link-box{
        width: 30%;
        display: inline-block;
        background: #EEE;
        height: 150px;
        margin-left: 2.5%;
        margin-bottom: 25px;
        float: left;
        text-decoration: none!important;    /*这里这么处理是因为受下面的display: -webkit-box;影响，下划线又会回来*/
        overflow: hidden;
        -webkit-transition: all .2s linear; /*渐变效果*/
            transition: all .2s linear;
    }
    
    /*链接区域鼠标滑动浮起效果*/
    .link-box:hover{
        z-index: 2; /*设置在顶层显示*/
        -webkit-box-shadow: 0 15px 30px rgba(0,0,0,0.1);    /*添加阴影*/
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        -webkit-transform: translate3d(0, -2px, 0);     /*向上浮动*/
        transform: translate3d(0, -2px, 0);
    }
    
    /*链接名字*/
    .link-box .link-name{
        font-size: 20px;
        color: #15AAEA;
        width: 100%;
        display: inline-block;
        text-align: center;
        margin: 18px 0;
    }
    
    /*链接小图标*/
    .link-box .link-name .link-favicon{
        display: inline-block;
        max-width: 30px;
        height: 30px;
        margin: -3px 2px 0 2px;
        vertical-align: middle;
    }
    
    /*链接描述*/
    .link-box .link-direction{
        display: inline-block;
        padding: 0 14px;
        font-size: 15px;
        line-height: 25px;
        color: #555;
        /*超过三行的内容被自动截断并加上省略号*/
        text-overflow: -o-ellipsis-lastline;    /*最后一行加省略号*/
        overflow: hidden;
        text-overflow: ellipsis;    /*无法容纳的被加上省略号*/
        display: -webkit-box;
        -webkit-line-clamp: 3;  /*超出三行截断*/
        -webkit-box-orient: vertical;
    }
    
    /*网页宽度大于900px,每列显示3个*/
    @media screen and (min-width:900px){
        .link-box[data-role=.link-box-area]:nth-child(3n)
        {
            clear:both;
        }
    }

    /*网页宽度在900px到600px之间,每列显示2个*/
    @media screen and (max-width:900px) and (min-width:600px){
        .link-box[data-role=.link-box-area]:nth-child(2n)
        {
            clear:both;
        }
        .link-box{
            width: 40%;
            height: 150px;
            margin-left: 6.5%;
        }
    }
    
    /*网页宽度小于600px,每列显示1个*/
    @media screen and (max-width:600px){
        .link-box{
            width: 90%;
            height: 150px;
            margin-left: 5%;
            clear:both;
        }
    }
    </style>

<?php
// 网站导航栏
banner('daohang');
?>

<div class="text-center">
    <h2>斗图导航</h2>
    <p class="text-muted">本站的表情模板不够用？这里为您准备了一些强大的同类网站，总有一个适合你！</p>
</div>


<div class="link-box-area">

<?php
echoUrl('http://www.dalalapic.com/diy.html','Dalala','可以“换脸”的表情图制作网站，把好友的照片换上去，斗图还不是稳赢？');

echoUrl('http://www.doubean.com/face/','豆饼表情','一个拥有众多表情图制作器的老牌在线斗图制作网站');

echoUrl('http://www.dtzhuanjia.com/','斗图专家','斗图专家提供表情包免费下载，在线制作表情包，可以用来在QQ，微信，朋友圈等通讯工具进行娱乐及斗图等');

echoUrl('http://www.adoutu.com/pages/makelist.php','爱斗图','能快速在线免费生成各种逗比斗图表情和头像，支持按分类检索表情');

echoUrl('http://zb.mkblog.cn','斗图终结者','一个屌炸天的在线表情包制作网站，这里有大量的表情模板和表情图，助您成为真正的斗图终结者！');

echoUrl('http://pic.sogou.com/pic/emo/','搜狗表情搜索','拥有全网最新、最全的表情包库，你值得拥有！（推荐手机浏览）');

echoUrl('http://zhuangxiabi.com/','装下逼','可以在线免费生成各种装逼图片,一键生成带你名字的结婚证,豪车证,明星举牌,摩天楼表白等等。');

echoUrl('http://deepba.com/','随风装逼神器','在线免费制作各种美女举牌照,id照,搞笑网络证件,搞笑表情在线制作,支付宝转账图等');

echoUrl('http://www.248xyx.com/index.php/home/index/categorylist/cid/6','整人装逼','可以在线制作装逼图片和整蛊图片（来自248游戏）');

echoUrl('http://baozoumanhua.com/zhuangbi/list','暴走装逼神器','诗曰：一入暴漫深似海,处处留心皆是逼;最火爆的原创恶搞漫画制作分享网站,和王尼玛斗图吐槽!');

echoUrl('http://www.jiqie.com/','在线闪图制作','各种QQ头像制作、各种在线非主流动态闪图设计、各种印章在线制作，就在急切网');

//echoUrl('http://cgzp.cdn.novorunning.com/tongcheng/real.html','机票装逼','');

echoUrl('http://www.blackbirld.com/main/','斗图大脸萌(软件)','全新一代的表情包制作软件，作图方便快捷。兼容WinXp、Win7、Win10。优质的素材，让你与小伙伴伙伴一起愉快的斗图。');

//echoUrl('http://www.dougedan.com/','斗个蛋表情网','');

//echoUrl('','','');
?>

<!--<a class="link-box" title="Dalala" href="http://zhuangxiabi.com/" target="_blank">-->
<!--<span class="link-name">-->
<!--<img class="link-favicon" src="http://api.mkblog.cn/favicon/get.php?url=http://zhuangxiabi.com/"/>-->
<!--虚位以待-->
<!--</span>-->
<!--<span class="link-direction">-->
<!--如果要交换友链，请前往留言板。本站只接受有独立域名和实质内容的健康的网站。-->
<!--</span>-->
<!--</a>-->


</div>  <!--class="link-box-area"-->

<p class="text-center">
    <a href="about.php#comments" target="_blank">我还有更好的斗图网站要推荐</a>
</p>

<?php

/**
 * 输出一条链接
 * @param $url 链接地址
 * @param $name 链接名字
 * @param $direction 链接描述
 */
function echoUrl($url,$name,$direction)
{
    ?>
    <a class="link-box" href="<?php echo $url;?>" target="_blank" title="<?php echo $name;?>">
        <span class="link-name">
            <img class="link-favicon" src="http://api.mkblog.cn/favicon/get.php?url=<?php echo $url;?>" alt="<?php echo $name;?>" title="<?php echo $direction;?>" >
            <?php echo $name."\n";?>
        </span>
        <span class="link-direction">
            <?php echo $direction."\n";?>
        </span>
    </a>
    <?php
}

//网站底部
footer();
?>