<?php
// 斗图终结者-公用函数模块
if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

/**
 * 获取GET或POST过来的参数
 * @param $key 键值
 * @param $default 默认值
 * @return 获取到的内容（没有则为默认值）
 */
function getParam($key,$default='')
{
    return trim($key && is_string($key) ? (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default)) : $default);
}

/**
 * 输出一条简短的消息（一般是错误消息）
 * @param $code 消息代码
 * @param $msg 消息内容
 */
function echoMsg($code,$msg)    //发出消息
{
    $tempArr = array('code'=>$code,'msg'=>$msg);
    echojson(json_encode($tempArr));
}

/**
 * 输出返回结果，支持输出 json和jsonp 格式
 * @param $data 输出的内容(json格式)
 */
function echoJson($data)    //json和jsonp通用
{
    $callback = getParam('callback');
    if($callback != '') //输出jsonp格式
    {
        die($callback.'('.$data.')');
    }
    else
    {
        die($data);
    }
}

/**
 * 数据库防攻击函数
 * @param $value 值
 * @return 净化后的值
 */
function dbSafe($value)
{
    // 去除斜杠
    if (get_magic_quotes_gpc())
    {
        $value = stripslashes($value);
    }
    $value = mysql_real_escape_string($value);
    return $value;
}

/**
 * 获取IP地址的函数
 * @return IP地址
 */
function getIP()
{
	$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
	if (!ip2long($ip)) {
		$ip = '0.0.0.0';
	}
	return $ip;
}


// 从指定目录中读取图片
// 参数：图片目录
function readPic($dir) {  
    $dh = @opendir ($dir); // 打开目录，返回一个目录流  
    $return = array ();  
    $i = 0;
    while ($file = @readdir($dh)) { // 循环读取目录下的文件  
        if ($file != '.' and $file != '..') {  
            $path = $dir . '/' . $file; // 设置目录，用于含有子目录的情况  
            if (is_dir($path)) {
                // 是目录
            } elseif(is_file($path)) {   
                if(preg_match("/\.(gif|jpeg|jpg|png|bmp)$/i", $file)) $return [] = $file;  
            }  
        }
    }
    @closedir ($dh); // 关闭目录流 
    return $return; // 返回文件  
}