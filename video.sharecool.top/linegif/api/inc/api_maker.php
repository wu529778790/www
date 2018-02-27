<?php

if(!defined('MK_Pic_api')) die('非法访问 - Insufficient Permissions');

$id = getParam('id');   // 获取制作模板ID

if($id == '') {
    echoMsg(-1, 'id为空');
    die();
}

$tempPath = "template/{$id}/";      // 拼接模板api目录

if(!file_exists($tempPath . "index.php")) {     // 接口文件不存在
    echoMsg(-1, '图片接口不存在');
    die();
}

$isGetCover = getParam('cover', '');        // 是否是请求封面

if($isGetCover != ''){
    if($isGetCover == 'true') $isGetCover = '';         // 支持多张封面
    $isGetCover = 'cover' .$isGetCover. '.jpg';
    header('Location: ' . $tempPath . $isGetCover);    //header跳转
    die();
}

require("inc/maker_com.php");
$config['path'] = $tempPath;    // 把制作器目录带到全局变量供后面调用使用
require($tempPath . "index.php");
