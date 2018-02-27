<?php
define('MK_Pic','mengkunsoft');

// 公共头部文件
require 'header.php';

// 请求公用函数模块
require("inc/com_function.php");

// 网页head 标题 标题描述 关键字
title('表情搜索','','');
?>

    <!--滚动加载插件-->
    <script type="text/javascript" src="js/jquery.lazyload.min.js"></script>

    <style type="text/css">
        .title{
            margin-bottom: 40px;
        }
        #mainList{
            margin-top: 20px;
        }
        #mainList img{
            width: 100%;
        }
        .hottag{
            max-width: 600px;
            margin: 40px auto;
        }
        .hottag p{
            display: inline-block;
        }
        .sougouLogo{
            display: inline-block;
            width: 100px;
            border: none;
            margin-top: -2px;
        }
    </style>

<?php
// 网站导航栏
banner('search');
?>

<!--
本搜索功能的图片全部来自搜狗表情搜索（http://pic.sogou.com/pic/emo/）
-->

<div class="text-center title">
    <h2 title="本搜索功能由搜狗表情搜索提供">表情搜索 <small>beta</small></h2>
</div>

<div class="row">
    <form class="form-inline" id="search">
        <div class="form-group col-xs-12">
            <div class="input-group col-sm-11 col-sm-offset-1 col-md-10 col-md-offset-2 col-lg-10 col-lg-offset-2 col-xs-12 col-sm-12">
                <input type="text" name="wd" id="query" value="<?php echo htmlspecialchars(getParam('wd'));?>" placeholder="请输入表情关键字" class="form-control" required/>
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    </button>
                </span>
            </div><!-- /input-group -->
        </div>
    </form>
</div>

<div id="mainList"></div>

<p onclick="loadPic();" id="loadMore" class="text-center" hidden>疯狂加载中</p>

<div class="panel panel-default hottag">
  <div class="panel-body">
    热搜词：
<?php 
    function showTag($name)
    {
        $class = array("btn-primary", "btn-success", "btn-info", "btn-warning", "btn-danger");  //"btn-default", 
        $rand_keys = array_rand($class);
        echo '  <p><button type="button" class="btn '.$class[$rand_keys].' btn-sm" onclick="search(this);" title="点击搜索包含 '.$name.' 的表情包">'.$name.'</button></p>'."\n";
    }
    showTag('暴走漫画');
    showTag('斗图应答');
    showTag('金馆长');
    showTag('熊本熊');
    showTag('撩妹');
    showTag('喵星人');
    showTag('猫和老鼠');
    showTag('贱贱蘑菇头');
    showTag('小岳岳');
    showTag('可达鸭');
    showTag('纯文字');
    showTag('滑稽');
    showTag('装逼');
    showTag('柯南');
    showTag('天线宝宝');
    showTag('弹幕');
    showTag('doge');
    showTag('尔康');
    showTag('套路');
    showTag('爸爸');
    showTag('微信');
    showTag('高能');
    showTag('智商');
    showTag('群');
    showTag('法医秦明');
    showTag('老九门');
?>
  </div>
</div>

<p class="text-center text-muted">* 搜索结果由 <a href="https://www.sogou.com/" target="_blank"><img class="sougouLogo" src="images/others/sougou.png"></a> 提供</p>

<!--页面核心js-->
<script type="text/javascript" src="js/search.js"></script>

<?php
//网站底部
footer();
?>