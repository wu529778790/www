<?php
if(!defined('MK_Pic_api')) die('非法访问 - Insufficient Permissions');

header("content-type:image/jpeg");

// error_reporting(0); 
$maker = array(
    'str1' => getParam('str1'),   // 字符串1
    'str2' => getParam('str2'),   // 字符串2
    'font1' => $fonts['yahei'],   // 字体
    'font_size1' => 75,     // 字体大小1
    'font_size2' => 15,     // 字体大小1
    'image_bg' => $config['path'] . 'bg.jpg',   // 背景图片
);


$m_arr = getimagesize($maker['image_bg']); // 高度、宽度、格式(GIF-JPEG/JPG-PNG)、高宽值字符串
$m_width = $m_arr[0];   // 图像宽度
$m_height = $m_arr[1];  // 图像高度

$m_im = imagecreatetruecolor($m_width, $m_height);     // 新建一个 宽x高 的真彩色图像

$m_bg = imagecreatefromjpeg($maker['image_bg']);     // 创建背景
imagecopy($m_im, $m_bg, 0, 0, 0, 0, $m_width, $m_height);
imagedestroy($m_bg);


$m_color = imagecolorallocate($m_im, 8, 31, 63);  // 创建一个颜色

// 输出第一行
// 输入：句柄、字体大小、角度、坐标点x、y、颜色65、字体文件、字符串 (int im, int size, int angle, int x, int y, int col, string fontfile, string text);

$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str1']);
$m_x = ($m_width - $m_fontBox[2]) / 2;  // 获取字符宽度并居中显示
if(preg_match('/》/', $maker['str1'])) $m_x = $m_x - 50;        // 修正居中误差
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 150, $m_color, $maker['font1'], $maker['str1']);  // 在图片上绘制文字

// $m_color = imagecolorallocate($m_im, 255, 255, 255);  // 创建一个颜色
// $m_color = imagecolorallocate($m_im, 89, 115, 140);  // 创建一个颜色

$maker['str2'] = '领衔主演：'.$maker['str2'];

$m_fontBox = imagettfbbox($maker['font_size2'], 0, $maker['font1'], $maker['str2']);
$m_x = ($m_width - $m_fontBox[2]) / 2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 200, $m_color, $maker['font1'], $maker['str2']);  // 在图片上绘制文字

imagejpeg($m_im);
imagedestroy($m_im);
