<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '支付宝五福卡生成器',   // 标题
    'description' => '免费在线生成支付宝五福卡截图',    //描述
    'keywords' => '支付宝五福卡生成器,敬业福,支付宝,福',   //关键字(用英文逗号分隔)
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
    <label for="m_str1">请输入爱国福个数：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入 1~99 之间的整数" maxlength="2" onclick="this.focus();this.select()">
    <!--<select name="str1" id="m_str1" class="form-control"></select>-->
</div>

<div class="form-group">
    <label for="m_str2">请输入富强福个数：</label>
    <input name="str2" type="text" class="form-control" id="m_str2" placeholder="请输入 1~99 之间的整数" maxlength="2" onclick="this.focus();this.select()">
    <!--<select name="str1" id="m_str1" class="form-control"></select>-->
</div>

<div class="form-group">
    <label for="m_str3">请输入和谐福个数：</label>
    <input name="str3" type="text" class="form-control" id="m_str3" placeholder="请输入 1~99 之间的整数" maxlength="2" onclick="this.focus();this.select()">
    <!--<select name="str1" id="m_str1" class="form-control"></select>-->
</div>

<div class="form-group">
    <label for="m_str4">请输入友善福个数：</label>
    <input name="str4" type="text" class="form-control" id="m_str4" placeholder="请输入 1~99 之间的整数" maxlength="2" onclick="this.focus();this.select()">
    <!--<select name="str1" id="m_str1" class="form-control"></select>-->
</div>

<div class="form-group">
    <label for="m_str5">请输入敬业福个数：</label>
    <input name="str5" type="text" class="form-control" id="m_str5" placeholder="请输入 1~99 之间的整数" maxlength="2" onclick="this.focus();this.select()">
    <!--<select name="str1" id="m_str1" class="form-control"></select>-->
</div>

<div class="form-group">
    <button id="m_random" type="button" class="btn btn-success btn-block">随机值</button>
</div>

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>

<!--<div class="alert alert-warning" role="alert">-->
<!--    <p class="m-title">温馨提示：</p>-->
<!--    <p>本微信零钱图生成器仅可用作<strong>装逼娱乐</strong>用途，点击“生成”按钮即代表您已阅读并同意本条款。</p>-->
<!--    <p>因不合理使用本生成器所生成图片造成的纠纷本站概不负责！</p>-->
<!--</div>-->

<script type="text/javascript">
$(function(){
    // 生成随机数
    $("#m_random").click(function(){
        $("#m_str1").val(fRandomBy(1, 99));        // 显示新的金额值
        $("#m_str2").val(fRandomBy(1, 60));        // 显示新的金额值
        $("#m_str3").val(fRandomBy(1, 60));        // 显示新的金额值
        $("#m_str4").val(fRandomBy(1, 99));        // 显示新的金额值
        $("#m_str5").val(fRandomBy(1, 20));        // (敬业福要少些)
        return true;
    })
    
    // 表单被提交
    $("#m_imgSet").submit(function(){
        picBuild(); //调用图像生成函数
        return false;
    });
    
    // m_Init();
    
    $("#m_random").click(); // 一开始就显示随机值
});

// function m_Init(){
//     for(var i = 99; i > 0; i++){
//         $("#m_str1").prepend("<option value='" +i+ "'>" +i+ "</option>");
//         $("#m_str2").prepend("<option value='" +i+ "'>" +i+ "</option>");
//         $("#m_str3").prepend("<option value='" +i+ "'>" +i+ "</option>");
//         $("#m_str4").prepend("<option value='" +i+ "'>" +i+ "</option>");
//         $("#m_str5").prepend("<option value='" +i+ "'>" +i+ "</option>");
//     }
// }

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
