<?php
if(!defined('MK_Pic_api')) die('非法访问 - Insufficient Permissions');

mb_internal_encoding("UTF-8"); // 设置编码

// 设置
$config = array(
    'path' => '',   // 制作器目录
    'font_path' => 'fonts/', // 字体目录
);

// 公共字体
$fonts = array(
    'yahei_bold' => $config['font_path'] . 'yahei_bold.ttf', // 微软雅黑_加粗
    'yahei' => $config['font_path'] . 'yahei.ttf', // 微软雅黑
    'paoxiao' => $config['font_path'] . 'paoxiao.ttf', // 方正咆哮体
    'girltype' => $config['font_path'] . 'GirlType.ttf', // 华康少女字体
    'song_bold' => $config['font_path'] . 'song_bold.ttf', // 方正粗宋
    'kai' => $config['font_path'] . 'kai.ttf', // 楷体
);

/**
 * 文字自动换行模块
 * @param $string 字符串
 * @param $fontsize 字体大小
 * @param $fontface 字体名称
 * @param $width 预设宽度
 * @param $angle 角度
 * @return 换行转换的结果
 */
//字体大小, 角度, 字体名称, 字符串, 预设宽度
function autowrap($string, $fontsize, $fontface, $width, $angle = 0) {
    $str['content'] = "";   // 转换后的内容
    $str['lines'] = 1;      // 转换后的行数
    $str['length'] = mb_strlen($string);    // 长度
    if($str['length'] <= 1){    // 内容太短就没必要转化了
        $str['content'] = $string;
        return $str;
    }
    // 将字符串拆分成一个个单字 保存到数组 letter 中
    for ($i=0; $i<$str['length']; $i++) {
        $letter[] = mb_substr($string, $i, 1);
    }
    foreach ($letter as $l) {
        $teststr = $str['content']." ".$l;  // 测试字符串
        $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
        // 判断拼接后的字符串是否超过预设的宽度
        if (($testbox[2] > $width) && ($str['content'] !== "")) {
            $str['content'] .= "\n";
            $str['lines']++;    // 行数自加
        }
        $str['content'] .= $l;
    }
    $str['height'] = $testbox[1];
    return $str;
}


