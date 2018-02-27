<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '灾难海报生成器',   // 标题
    // 'description' => '免费在线生成支付宝五福卡截图',    //描述
    'keywords' => '《开学》,《上班》,灾难片,海报,生成器',   //关键字(用英文逗号分隔)
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
    <label for="m_str1">请输入海报标题：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入海报标题" value="" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str2">请输入主演：</label>
    <input name="str2" type="text" class="form-control" id="m_str2" placeholder="请输入领衔主演人名" value="" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <button id="m_random" type="button" class="btn btn-success btn-block">换一个</button>
</div>

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>

<script type="text/javascript">
$(function(){
    var m_str = [["《开学》","全国学生"],
    ["《上班》","广大白领"],
    ["开学","张三"],
    ["补课","张三"]];
    
    // 换一句对话
    $("#m_random").click(function(){
        var i = fRandomBy(0, m_str.length-1);   // 随机产生一个对白
        $("#m_str1").val(m_str[i][0]);
        $("#m_str2").val(m_str[i][1]);
        return true;
    })
    
    // 表单被提交
    $("#m_imgSet").submit(function(){
        picBuild(); //调用图像生成函数
        return false;
    });
    
    $("#m_random").click();

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
