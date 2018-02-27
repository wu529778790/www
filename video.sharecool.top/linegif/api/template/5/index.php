<?php
if(!defined('MK_Pic_api')) die('非法访问 - Insufficient Permissions');

header("content-type:image/png");

// error_reporting(0); 
$maker = array(
    'str1' => getParam('str1', '6'),   // 字符串1
    'str2' => getParam('str2', '6'),   // 字符串1
    'str3' => getParam('str3', '6'),   // 字符串1
    'str4' => getParam('str4', '6'),   // 字符串1
    'str5' => getParam('str5', '6'),   // 字符串1
    'font1' => $fonts['yahei'],   // 字体
    'font_size1' => 14,     // 字体大小1
    'image_bg' => $config['path'] . 'bg.jpg',   // 背景图片
    'image_dot' => $config['path'] . 'dot.png',   // 点图片
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

// 绘制文字背板
$m_dot = imagecreatefrompng($maker['image_dot']);         //获得水印图片  
$m_dotW = imagesx($m_dot);  
$m_dotH = imagesy($m_dot);  
  



// $m_color = imagecolorallocate($m_im, 238, 207, 151);  // 创建一个颜色
// //imageellipse($m_im, 30, 30, 40, 40, $m_color, IMG_ARC_PIE);//画一个圆。参数说明：30，30为圆形的中心坐标；40，40为宽和高，不一样时为椭圆；$red为圆形的颜色（框颜色）
// imagefilledarc($m_im, 200, 600, 20, 20, 0, 0, $m_color, IMG_ARC_PIE); //起始点x、y，宽、高、起始角度、结束角度、颜色、填充


$m_color = imagecolorallocate($m_im, 220, 31, 49);  // 创建一个颜色


// // 输入：字体大小、角度、字体文件、字符内容
// // 输出：左下角x、y、右下角x、y、右上角x、y、左上角x、y
// $m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str1']);
// $m_x = ($m_width - $m_fontBox[2]) / 2;  // 获取字符宽度并居中显示


// 输出第一行
// 输入：句柄、字体大小、角度、坐标点x、y、颜色65、字体文件、字符串 (int im, int size, int angle, int x, int y, int col, string fontfile, string text);
$m_x = 65;
if(mb_strlen($maker['str1'], 'utf-8') > 1){
    imagecopy($m_im, $m_dot, 55, 579, 0, 0, $m_dotW, $m_dotH);           // 显示小点
    $m_x = 59;
}
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 598, $m_color, $maker['font1'], $maker['str1']);  // 在图片上绘制文字

$m_x = 143;
if(mb_strlen($maker['str2'], 'utf-8') > 1){
    imagecopy($m_im, $m_dot, 133, 579, 0, 0, $m_dotW, $m_dotH);           // 显示小点
    $m_x = 137;
}
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 598, $m_color, $maker['font1'], $maker['str2']);  // 在图片上绘制文字

$m_x = 222;
if(mb_strlen($maker['str3'], 'utf-8') > 1){
    imagecopy($m_im, $m_dot, 212, 579, 0, 0, $m_dotW, $m_dotH);           // 显示小点
    $m_x = 216;
}
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 598, $m_color, $maker['font1'], $maker['str3']);  // 在图片上绘制文字

$m_x = 299;
if(mb_strlen($maker['str4'], 'utf-8') > 1){
    imagecopy($m_im, $m_dot, 289, 579, 0, 0, $m_dotW, $m_dotH);           // 显示小点
    $m_x = 293;
}
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 598, $m_color, $maker['font1'], $maker['str4']);  // 在图片上绘制文字

$m_x = 377;
if(mb_strlen($maker['str5'], 'utf-8') > 1){
    imagecopy($m_im, $m_dot, 367, 579, 0, 0, $m_dotW, $m_dotH);           // 显示小点
    $m_x = 371;
}
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 598, $m_color, $maker['font1'], $maker['str5']);  // 在图片上绘制文字

imagedestroy($m_dot);   // 销毁点图

imagepng($m_im);
imagedestroy($m_im);
?>