<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '算我输表情制作',   // 标题
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

<form id="m_imgSet">
<div class="form-group">
    <label for="m_str1">顶部显示的文字：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入顶部的文字内容" value="你尽管集福" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str2">讲台上的文字：</label>
    <input name="str2" type="text" class="form-control" id="m_str2" placeholder="请输入讲台上的文字内容" value="支付宝" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str3">底部显示的文字：</label>
    <input name="str3" type="text" class="form-control" id="m_str3" placeholder="请输入底部的文字内容" value="分到一块算我输" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>
<script type="text/javascript">
$(function(){
    $("#m_imgSet").submit(function(){
        picBuild(); //调用图像生成函数
        return false;
    });
});

// 通用图像生成函数
function picBuild(){
    $("#m_submit").html("生成中...");
    $('#m_submit').attr('disabled',"true");    // 生成过程中不许再次点击
    updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>" + "&" + $("#m_imgSet").serialize());
}

// 通用图像生成完毕后回调函数
function buildOver(){
    $("#m_submit").html("生成");
    $('#m_submit').removeAttr("disabled");     // 恢复生成按钮的使用
}

updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>" + "&cover=true");
// picBuild(); //调用图像生成函数
</script> 

<?php
}

?>
