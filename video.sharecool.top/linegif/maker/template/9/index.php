<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '鸡年大吉表情包生成器',   // 标题
    // 'description' => '免费在线生成友谊的小船说翻就翻图片',    //描述
    'keywords' => '鸡年大吉,说鸡不说吧,童男童女,拜年',   //关键字(用英文逗号分隔)
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
    <label for="m_str1">请选择版本：</label>
    <select class="form-control" name="ver" id="m_ver">
        <option value="0">童男</option>
        <option value="1">童女</option>
    </select>
</div>
    
<div class="form-group">
    <label for="m_str1">请输入文字内容：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入文字内容" value="祝你鸡年大吉吧" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>


<script type="text/javascript">
$(function(){
    $("#m_ver").change(function(){
        picBuild(); //调用图像生成函数
        return true;
    })

    // 表单被提交
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

// 默认显示封面
updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>" + "&cover=true");
// picBuild(); //调用图像生成函数
</script> 

<?php
}

?>
