<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '友谊的小船说翻就翻',   // 标题
    'description' => '免费在线生成友谊的小船说翻就翻图片',    //描述
    'keywords' => '友谊的小船',   //关键字(用英文逗号分隔)
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
        <option value="0">小船说翻就翻</option>
        <option value="1">小船说沉就沉</option>
        <option value="2">小船成为爱情的巨轮</option>
    </select>
</div>
    
<div class="form-group">
    <label for="m_str1">请输入第一个标题：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入第一个标题内容" value="乘坐在友谊之船的两个好朋友" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str2">请输入左一对话：</label>
    <input name="str2" type="text" class="form-control" id="m_str2" placeholder="请输入左边第一个对话(可空)" value="" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str3">请输入右一对话：</label>
    <input name="str3" type="text" class="form-control" id="m_str3" placeholder="请输入右边第一个对话(可空)" value="" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str4">请输入第二个标题：</label>
    <input name="str4" type="text" class="form-control" id="m_str4" placeholder="请输入第二个标题内容" value="如果一方脱单" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str5">请输入左二对话：</label>
    <input name="str5" type="text" class="form-control" id="m_str5" placeholder="请输入左边第二个对话(可空)" value="" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str6">请输入右二对话：</label>
    <input name="str6" type="text" class="form-control" id="m_str6" placeholder="请输入右边第二个对话(可空)" value="我有女朋友了" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str7">请输入最终结果：</label>
    <input name="str7" type="text" class="form-control" id="m_str7" placeholder="请输入最终的结果" value="友谊的小船说翻就翻" onclick="this.focus();this.select()">
</div>

<!--<div class="form-group">-->
<!--    <button id="m_random" type="button" class="btn btn-success btn-block">换一句</button>-->
<!--</div>-->

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>

<div class="alert alert-warning" role="alert">
    <p class="m-title">重要提示：</p>
    <p>“友谊的小船”系列漫画形象原作者为<a href="http://weibo.com/zld8" class="alert-link" rel="nofollow" target="_blank">@喃东尼</a></p>
    <p>未经原作者允许，所生成的图像<strong>不得用于商业用途</strong></p>
</div>

<script type="text/javascript">
$(function(){
    var m_str = [["乘坐在友谊之船的两个好朋友","","","如果一方脱单","","我有女朋友了","友谊的小船说翻就翻"],
    ["乘坐在友谊之船的两个好朋友","","","如果一方脱单","","我有女朋友了","友谊的小船说沉就沉"],
    ["乘坐在友谊之船的两个好朋友","我喜欢你！","我也喜欢你！","","mua","mua","他们友谊的小船就会升华为爱情的巨轮"]];
    
    // 换一句对话
    $("#m_ver").change(function(){
        var i = $("#m_ver").val();
        $("#m_str1").val(m_str[i][0]);
        $("#m_str2").val(m_str[i][1]);
        $("#m_str3").val(m_str[i][2]);
        $("#m_str4").val(m_str[i][3]);
        $("#m_str5").val(m_str[i][4]);
        $("#m_str6").val(m_str[i][5]);
        $("#m_str7").val(m_str[i][6]);
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
