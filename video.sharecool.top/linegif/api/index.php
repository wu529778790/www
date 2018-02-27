<?php
define('MK_Pic','mengkunsoft');
define('MK_Pic_api','mengkunsoft');

error_reporting(0);

// 请求公用函数模块
require("../inc/com_function.php");

$types = getParam('types');         // 获取请求的接口

if($types == '') {
    echoMsg(-1, 'api类型为空');
    die();
}

$tempPath = "inc/api_{$types}.php";      // 拼接api文件

if(!file_exists($tempPath)) {            // 接口内容文件不存在
    echoMsg(-1, '图片接口不存在');
    die();
}

require($tempPath);                 // 转到api文件执行

die();
