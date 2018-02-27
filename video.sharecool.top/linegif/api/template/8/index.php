<?php
if(!defined('MK_Pic_api')) die('非法访问 - Insufficient Permissions');
header("content-type:image/jpeg");

// error_reporting(0); 
$maker = array(
    'str1' => getParam('str1'),   // 字符串1
    'str2' => getParam('str2'),   // 字符串1
    'str3' => getParam('str3'),   // 字符串1
    'str4' => getParam('str4'),   // 字符串
    'str5' => getParam('str5'),   // 字符串
    'str6' => getParam('str6'),   // 字符串
    'str7' => getParam('str7'),   // 字符串
    'font1' => $fonts['yahei'],   // 少女字体
    'font2' => $fonts['girltype'],   // 少女字体
    'font_size1' => 13,     // 字体大小1
    'font_size2' => 11,     // 字体大小1
    'image_bg' => $config['path'] . 'bg.jpg',   // 背景图片
    'image_bg2' => $config['path'] . 'bg2.jpg',   // 背景图片
    'image_bg3' => $config['path'] . 'bg3.jpg',   // 背景图片
);

switch(getParam('ver', 0)){
    case 1:  // 说沉就沉

$maker['font_size2'] = 10;

$m_arr = getimagesize($maker['image_bg2']); // 高度、宽度、格式(GIF-JPEG/JPG-PNG)、高宽值字符串
$m_width = $m_arr[0];   // 图像宽度
$m_height = $m_arr[1];  // 图像高度

$m_im = imagecreatetruecolor($m_width, $m_height);     // 新建一个 宽x高 的真彩色图像

$m_bg = imagecreatefromjpeg($maker['image_bg2']);     // 创建背景
imagecopy($m_im, $m_bg, 0, 0, 0, 0, $m_width, $m_height);
imagedestroy($m_bg);

$m_color = imagecolorallocate($m_im, 0, 0, 0);  // 创建一个颜色

// 图片第二行标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str1']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 20, $m_color, $maker['font1'], $maker['str1']);  // 在图片上绘制文字

// 输出图片第一个对白(左一)
$maker['str2'] = autowrap($maker['str2'], $maker['font_size2'], $maker['font2'], 110);    // 自动换行
if($maker['str2']['lines'] > 1){
    $m_x = 5;
}else{
    $m_fontBox = imagettfbbox($maker['font_size2'], 0, $maker['font2'], $maker['str2']['content']);
    $m_x = $m_width - $m_fontBox[2] - 200;  // 获取字符宽度并居中显示
}
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 60, $m_color, $maker['font2'], $maker['str2']['content']);  // 在图片上绘制文字

// 输出图片(右一)
$maker['str3'] = autowrap($maker['str3'], $maker['font_size2'], $maker['font2'], 100);    // 自动换行
$m_x = 205;
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 45, $m_color, $maker['font2'], $maker['str3']['content']);  // 在图片上绘制文字

// 图片第二行标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str4']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 160, $m_color, $maker['font1'], $maker['str4']);  // 在图片上绘制文字

// 输出图片第二个对白(左二)
$maker['str5'] = autowrap($maker['str5'], $maker['font_size2'], $maker['font2'], 110);    // 自动换行
if($maker['str5']['lines'] > 1){
    $m_x = 5;
}else{
    $m_fontBox = imagettfbbox($maker['font_size2'], 0, $maker['font2'], $maker['str5']['content']);
    $m_x = $m_width - $m_fontBox[2] - 200;  // 获取字符宽度并居中显示
}
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 200, $m_color, $maker['font2'], $maker['str5']['content']);  // 在图片上绘制文字

// 输出图片第二个对白(左二)
$maker['str6'] = autowrap($maker['str6'], $maker['font_size2'], $maker['font2'], 100);    // 自动换行
$m_x = 205;
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 185, $m_color, $maker['font2'], $maker['str6']['content']);  // 在图片上绘制文字

// 图片最后一个标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str7']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 440, $m_color, $maker['font1'], $maker['str7']);  // 在图片上绘制文字


    break;
    
    case 2:     // 爱情的巨轮
       
$m_arr = getimagesize($maker['image_bg3']); // 高度、宽度、格式(GIF-JPEG/JPG-PNG)、高宽值字符串
$m_width = $m_arr[0];   // 图像宽度
$m_height = $m_arr[1];  // 图像高度

$m_im = imagecreatetruecolor($m_width, $m_height);     // 新建一个 宽x高 的真彩色图像

$m_bg = imagecreatefromjpeg($maker['image_bg3']);     // 创建背景
imagecopy($m_im, $m_bg, 0, 0, 0, 0, $m_width, $m_height);
imagedestroy($m_bg);


$m_color = imagecolorallocate($m_im, 0, 0, 0);  // 创建一个颜色

// 输出图片标题
// 输入：句柄、字体大小、角度、坐标点x、y、颜色65、字体文件、字符串 (int im, int size, int angle, int x, int y, int col, string fontfile, string text);
// 图片第二行标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str1']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 30, $m_color, $maker['font1'], $maker['str1']);  // 在图片上绘制文字

// 输出图片第一个对白(左一)
$maker['str2'] = autowrap($maker['str2'], $maker['font_size2'], $maker['font2'], 130);    // 自动换行
if($maker['str2']['lines'] > 1){
    $m_x = 20;
}else{
    $m_fontBox = imagettfbbox($maker['font_size2'], 0, $maker['font2'], $maker['str2']['content']);
    $m_x = $m_width - $m_fontBox[2] - 270;  // 获取字符宽度并居中显示
}
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 60, $m_color, $maker['font2'], $maker['str2']['content']);  // 在图片上绘制文字

// 输出图片(左二)
$maker['str3'] = autowrap($maker['str3'], $maker['font_size2'], $maker['font2'], 130);    // 自动换行
$m_x = 275;
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 65, $m_color, $maker['font2'], $maker['str3']['content']);  // 在图片上绘制文字

// 图片第二行标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str4']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 210, $m_color, $maker['font1'], $maker['str4']);  // 在图片上绘制文字

// 输出图片第二个对白(左一)
$maker['str5'] = autowrap($maker['str5'], $maker['font_size2'], $maker['font2'], 130);    // 自动换行
if($maker['str5']['lines'] > 1){
    $m_x = 20;
}else{
    $m_fontBox = imagettfbbox($maker['font_size2'], 0, $maker['font2'], $maker['str5']['content']);
    $m_x = $m_width - $m_fontBox[2] - 270;  // 获取字符宽度并居中显示
}
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 260, $m_color, $maker['font2'], $maker['str5']['content']);  // 在图片上绘制文字

// 输出图片第二个对白(左二)
$maker['str6'] = autowrap($maker['str6'], $maker['font_size2'], $maker['font2'], 130);    // 自动换行
$m_x = 275;
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 245, $m_color, $maker['font2'], $maker['str6']['content']);  // 在图片上绘制文字

// 图片最后一个标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str7']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 395, $m_color, $maker['font1'], $maker['str7']);  // 在图片上绘制文字
       
        
    break;
    
    default:   // 说翻就翻

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


$m_color = imagecolorallocate($m_im, 0, 0, 0);  // 创建一个颜色

// 输出图片标题
// 输入：句柄、字体大小、角度、坐标点x、y、颜色65、字体文件、字符串 (int im, int size, int angle, int x, int y, int col, string fontfile, string text);
// 图片第二行标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str1']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 30, $m_color, $maker['font1'], $maker['str1']);  // 在图片上绘制文字

// 输出图片第一个对白(左一)
$maker['str2'] = autowrap($maker['str2'], $maker['font_size2'], $maker['font2'], 130);    // 自动换行
if($maker['str2']['lines'] > 1){
    $m_x = 20;
}else{
    $m_fontBox = imagettfbbox($maker['font_size2'], 0, $maker['font2'], $maker['str2']['content']);
    $m_x = $m_width - $m_fontBox[2] - 270;  // 获取字符宽度并居中显示
}
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 60, $m_color, $maker['font2'], $maker['str2']['content']);  // 在图片上绘制文字

// 输出图片(左二)
$maker['str3'] = autowrap($maker['str3'], $maker['font_size2'], $maker['font2'], 130);    // 自动换行
$m_x = 275;
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 65, $m_color, $maker['font2'], $maker['str3']['content']);  // 在图片上绘制文字

// 图片第二行标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str4']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 230, $m_color, $maker['font1'], $maker['str4']);  // 在图片上绘制文字

// 输出图片第二个对白(左一)
$maker['str5'] = autowrap($maker['str5'], $maker['font_size2'], $maker['font2'], 130);    // 自动换行
if($maker['str5']['lines'] > 1){
    $m_x = 20;
}else{
    $m_fontBox = imagettfbbox($maker['font_size2'], 0, $maker['font2'], $maker['str5']['content']);
    $m_x = $m_width - $m_fontBox[2] - 270;  // 获取字符宽度并居中显示
}
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 260, $m_color, $maker['font2'], $maker['str5']['content']);  // 在图片上绘制文字

// 输出图片第二个对白(左二)
$maker['str6'] = autowrap($maker['str6'], $maker['font_size2'], $maker['font2'], 130);    // 自动换行
$m_x = 275;
imagettftext($m_im, $maker['font_size2'], 0, $m_x, 265, $m_color, $maker['font2'], $maker['str6']['content']);  // 在图片上绘制文字

// 图片最后一个标题
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str7']);
$m_x = ($m_width - $m_fontBox[2])/2;  // 获取字符宽度并居中显示
imagettftext($m_im, $maker['font_size1'], 0, $m_x, 450, $m_color, $maker['font1'], $maker['str7']);  // 在图片上绘制文字


}



imagejpeg($m_im);
imagedestroy($m_im);
?>