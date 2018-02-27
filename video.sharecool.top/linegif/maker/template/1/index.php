<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '朋友圈大字生成器',   // 标题
    // 'description' => '',    //描述
    // 'keywords' => '',   //关键字(用英文逗号分隔)
);

// 在页面头部输出
function maker_head(){
?>

<?php
}

// 在控制区域输出的内容
function maker_panel(){
    global $config; // 访问全局配置变量
?>

<div class="form-group">
    <label for="strInput1">气泡中的文字内容：</label>
    <input type="text" class="form-control" id="strInput1" placeholder="请输入气泡中的文字内容" value="朋友圈可以发气泡了！" onclick="this.focus();this.select()" onchange ="picBuild();" onpropertychange ="picBuild();" oninput  ="picBuild();">
</div>

<div class="alert alert-info" role="alert">
    <p class="m-title">如何使用？</p>
    <p>在上方输入框中输入你想说的话，然后保存生成的图片，并发送至朋友圈，就可形成在朋友圈发气泡文字的效果。</p>
</div>

<script type="text/javascript">
function picBuild(){
    updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id'].'&str1='?>" + $("#strInput1").val());
}

updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>" + "&cover=true");
// picBuild(); //调用图像生成函数
</script> 

<?php
}

?>
