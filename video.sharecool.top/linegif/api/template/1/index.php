<?php
if(!defined('MK_Pic_api')) die('非法访问 - Insufficient Permissions');

header("content-type:image/png");

// error_reporting(0); 
$maker = array(
    'str1' => getParam('str1', '斗图终结者'),   // 字符串1
    'font1' => $fonts['yahei'],   // 字体
    'font_size1' => 300,     // 字体大小1
    'image_bg' => $config['path'] . 'bg.png',   // 背景图片
);


$m_str_length = mb_strlen($maker['str1'], 'utf-8');  // 获取字符串长度
if($m_str_length < 7)
{
    $maker['font_size1'] = 85;
    $m_y = 170;
}elseif($m_str_length < 9){
    $maker['font_size1'] = 65;
    $m_y = 166;
}else{
    $maker['str1'] = autowrap($maker['str1'], $maker['font_size1'], $maker['font1'], 3650);    // 自动换行

    if($maker['str1']['lines'] > 1){    // 有多行
        $maker['font_size1'] = 60;
        $m_y = 110;
    }else{
        $maker['font_size1'] = 65;
        $m_y = 166;
    }
    $maker['str1'] = $maker['str1']['content'];
}

$maker['image_bg']= $config['path'] . 'bg.png';


$m_arr = getimagesize($maker['image_bg']); // 高度、宽度、格式(GIF-JPEG/JPG-PNG)、高宽值字符串
$m_width = $m_arr[0];   // 图像宽度
$m_height = $m_arr[1];  // 图像高度


$m_im = imagecreatetruecolor($m_width, $m_height);     // 新建一个 宽x高 的真彩色图像

$m_bg = imagecreatefrompng($maker['image_bg']);     // 创建背景
imagecopy($m_im, $m_bg, 0, 0, 0, 0, $m_width, $m_height);
imagedestroy($m_bg);

$m_color = imagecolorallocate($m_im, 0, 0, 0);  // 创建一个颜色

// 输入：字体大小、角度、字体文件、字符内容
// 输出：左下角x、y、右下角x、y、右上角x、y、左上角x、y
$m_fontBox = imagettfbbox($maker['font_size1'], 0, $maker['font1'], $maker['str1']);
$m_x = ceil(($m_width - $m_fontBox[2]) / 2);  // 获取字符宽度并居中显示

// 输入：句柄、字体大小、角度、坐标点x、y、颜色、字体文件、字符串 (int im, int size, int angle, int x, int y, int col, string fontfile, string text);
imagettftext($m_im, $maker['font_size1'], 0, $m_x, $m_y, $m_color, $maker['font1'], $maker['str1']);  // 在图片上绘制文字

imagepng($m_im);
imagedestroy($m_im);
?>