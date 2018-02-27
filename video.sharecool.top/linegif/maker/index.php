<?php

define('MK_Pic','mengkunsoft');

// 公共头部文件
require '../header.php';

// 请求公用函数模块
require("../inc/com_function.php");

// 设置
$config = array(
    'path' => '',   // 制作器目录
    'api_url' => C('siteurl') . '/api/',    // 程序api地址
    'api_id' => 1,      // api id编号
);

$setting_dft = array(
    'title' => '装逼神器',   // 标题
    'description' => '',    //描述
    'keywords' => '暴走表情大全,聊天图片大全',   //关键字(用英文逗号分隔)
);


$id = getParam('id', 1);   // 获取制作器ID

$config['api_id'] = $id;

$tempPath = "template/{$id}/";

// 表情模板不存在
if(!file_exists($tempPath . "index.php")){
    header('HTTP/1.1 404 Not Found');   //返回404状态
    header("status: 404 Not Found");
    title('该模板不存在');
    echo '<meta http-equiv="refresh" content="0;url=/404.php">';
    banner(5);
    // echo '<h2>该制作器不存在</h2>';
    footer();
    die();
}

$config['path'] = $tempPath;
require($tempPath . "index.php");

$setting = array_merge($setting_dft, $setting);

// 网页head 标题 标题描述 关键字
title($setting['title'], $setting['description'], $setting['keywords']);

// 模板专用 js 文件
echo '<script src="' . C('siteurl') . '/js/maker.js?123"></script>';

?>

<!--装逼模板通用样式文件-->
<style>
/* 图像输出区 */
.output{
    text-align: center;
}
/* 生成的图片 */
#outputPic{
    width: 100%;
    max-width: 400px;
    margin-bottom: 30px;
    transition: all 0.25s ease;
    -webkit-transition: all 0.25s ease;
    -moz-transition: all 0.25s ease;
    -o-transition: all 0.25s ease;
    -ms-transition: all 0.25s ease;
}
#outputPic:hover {
    -moz-box-shadow: 0px 0px 20px #000000;
    -webkit-box-shadow: 0px 0px 20px #000000;
    box-shadow: 0px 0px 20px #000000;
}

/* 提示中的小标题 */
.m-title{
    font-weight: 600;
}
</style>

<?php

// 输出模板所需的头部文件
if (function_exists('maker_head')) maker_head();


// 网站导航栏
banner();

?>

<div class="row">
    <div class="col-md-6 output">
        <img id="outputPic" src="<?php echo C('siteurl'); ?>/images/loading.gif" title="<?php echo $setting['title'];?>图片生成区(点击鼠标右键保存图片)">
    </div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">图片设置</div>
            <div class="panel-body">
                <?php
                    // 输出表情模板控制模块
                    if(function_exists('maker_panel')) maker_panel();
                ?>
                
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p class="m-title">如何保存图片？</p>
                    <p>电脑上：在图片上点击鼠标右键，选择“复制图片”即可直接粘贴到QQ等聊天工具窗口，发送给朋友；</p>
                    <p>手机上：长按生成的图片，选择“保存到本地”。</p>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
//网站底部
footer();
?>