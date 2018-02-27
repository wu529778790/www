<?php
if(!defined('MK_Pic_api')) die('非法访问 - Insufficient Permissions');
header("content-type:image/png");

// error_reporting(0); 
$maker = array(
    'str1' => getParam('str1'),   // 字符串1
    'font1' => array($fonts['yahei_bold'], $fonts['paoxiao'], $fonts['girltype']),   // 少女字体
    'font_size1' => 12,     // 字体大小1
    'width' => 400,
    'height' => 160,
    'color' => getParam('color'),   // 随机颜色
);

$maker['font1'] = $maker['font1'][getParam('font', 0)];    // 设置字体


$m_im = imagecreate($maker['width'], $maker['height']);     // 新建一个 宽x高 的真彩色图像

$m_backColor = imagecolorallocate($m_im, 248, 248, 248);   //第一次调用是设定背景色

$m_color = imagecolorallocate($m_im, 0, 0, 0);  // 创建一个颜色

$m_x = 2;
$m_y = array(120, 60, 35, 28, 25, 20, 16, 16, 16); // Y轴坐标
$maker['font_size1'] = array(100, 50, 30, 25, 20, 16, 12, 12, 12);    // 初始字体大小
$m_sizeAdd = array(40, 40, 40, 30, 20, 14, 10, 7, 5);    // 每输出一次字体的增量
$m_xAdd = array(20, 20, 20, 20, 12, 9, 8, 6, 4);    // 每输出一次X轴的增量
$m_yAdd = array(45, 45, 45, 34, 22, 16, 12, 8, 6);    // 每输出一次 Y 轴的增量


$m_str_length = mb_strlen($maker['str1'], 'utf-8');  // 获取字符串长度

if($m_str_length > 9) $m_str_length = 9;

// 将字符串拆分成一个个单字 保存到数组 letter 中
for ($i=0; $i < $m_str_length; $i++) {
    $maker['str2'][] = mb_substr($maker['str1'], $i, 1);
}

$m_str_length = $m_str_length -1;

for($i = 0; $i < $m_str_length + 1; $i++){
    if($maker['color'] == true) $m_color = imagecolorallocate($m_im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));  // 创建一个颜色
    imagettftext($m_im, $maker['font_size1'][$m_str_length], 0, $m_x, $m_y[$m_str_length], $m_color, $maker['font1'], $maker['str2'][$i]);  // 在图片上绘制文字
    $m_x = $m_x + $maker['font_size1'][$m_str_length] + $m_xAdd[$m_str_length] + $i * 2;
    $m_y[$m_str_length] = $m_y[$m_str_length] + $m_yAdd[$m_str_length];
    $maker['font_size1'][$m_str_length] = $maker['font_size1'][$m_str_length] + $m_sizeAdd[$m_str_length];
}

imagepng($m_im);
imagedestroy($m_im);
?>