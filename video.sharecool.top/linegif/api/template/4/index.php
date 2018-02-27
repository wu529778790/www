<?php
if(!defined('MK_Pic_api')) die('非法访问 - Insufficient Permissions');

header("content-type:image/png");

// error_reporting(0); 
$maker = array(
    'str1' => getParam('str1', '¥100,000.00'),   // 字符串1
    'font1' => $fonts['yahei'],   // 字体
    'font_size1' => 32,     // 字体大小1
    'image_bg' => $config['path'] . 'bg.jpg',   // 背景图片
);


$m_arr = getimagesize($maker['image_bg']); // 高度、宽度、格式(GIF-JPEG/JPG-PNG)、高宽值字符串
$m_width = $m_arr[0];   // 图像宽度
$m_height = $m_arr[1];  // 图像高度

$m_im = imagecreatetruecolor($m_width, $m_height);     // 新建一个 宽x高 的真彩色图像


// imagecreatefromgif()：创建一块画布，并从 GIF 文件或 URL 地址载入一副图像
// imagecreatefromjpeg()：创建一块画布，并从 JPEG 文件或 URL 地址载入一副图像
// imagecreatefrompng()：创建一块画布，并从 PNG 文件或 URL 地址载入一副图像
// imagecreatefromwbmp()：创建一块画布，并从 WBMP 文件或 URL 地址载入一副图像
// imagecreatefromstring()：创建一块画布，并从字符串中的图像流新建一副图像
$m_bg = imagecreatefromjpeg($maker['image_bg']);     // 创建背景
imagecopy($m_im, $m_bg, 0, 0, 0, 0, $m_width, $m_height);
imagedestroy($m_bg);

$m_color = imagecolorallocate($m_im, 53, 53, 53);  // 创建一个颜色

// 输入：字体大小、角度、字体文件、字符内容
// 输出：左下角x、y、右下角x、y、右上角x、y、左上角x、y
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str1']);
$m_x = ($m_width - $m_fontBox[2]) / 2;  // 获取字符宽度并居中显示


// 输出第一行
// 输入：句柄、字体大小、角度、坐标点x、y、颜色、字体文件、字符串 (int im, int size, int angle, int x, int y, int col, string fontfile, string text);
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 282, $m_color, $maker['font1'], $maker['str1']);  // 在图片上绘制文字



imagepng($m_im);
imagedestroy($m_im);
?>