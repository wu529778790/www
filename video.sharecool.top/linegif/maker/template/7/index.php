<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '秒打脸对话生成器',   // 标题
    // 'description' => '免费在线生成支付宝五福卡截图',    //描述
    'keywords' => '秒打脸,饭真香,我就是饿死,王境泽,打脸图',   //关键字(用英文逗号分隔)
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
    <label for="m_str1">请输入第一句对话：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入第一句内容" value="我就是饿死" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str2">请输入第二句对话：</label>
    <input name="str2" type="text" class="form-control" id="m_str2" placeholder="请输入第二句内容" value="死外边，从这里跳下去" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str3">请输入第三句对话：</label>
    <input name="str3" type="text" class="form-control" id="m_str3" placeholder="请输入第三句内容" value="不会吃你们一点东西" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str4">请输入第四句对话：</label>
    <input name="str4" type="text" class="form-control" id="m_str4" placeholder="请输入第四句内容" value="真香" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <button id="m_random" type="button" class="btn btn-success btn-block">换一句</button>
</div>

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>

<div class="alert alert-info" role="alert">
    <p class="m-title">图片小故事：</p>
    <p>本图片中的主人公叫王境泽，<a href="http://www.mgtv.com/b/49902/658296.html" class="alert-link" rel="nofollow" target="_blank">《变形计》第八季之《远山的抉择》</a>城市主人公。</p>
    <p>节目开始时，王境泽嫌交换过去的乡村环境不好，条件不好，不想呆了。农村家里的爷爷就一直在劝他，然后王境泽就很生气，于是就有了这一段经典的对话。之后过了一两个小时，闹腾饿了，又只能妥协吃饭。当然饿的时候吃什么都香啊，所以就说那饭真香。</p>
</div>

<script type="text/javascript">
$(function(){
    var m_str = [["我就是饿死","死外边，从这里跳下去","不会吃你们一点东西","真香"],
    ["我就是一辈子9级","只签到，只看帖","也不再水一个帖子！","还是水贴经验涨得快"],
    ["我就是饿死","死外边，从这里跳下去","不会再刷一分钱深渊","今天又出了5个SS"],
    ["我就是装备再差","见面就被干死","也不会再给TX充钱","又出新活动了，先充200"],
    ["我就是饿死，两个月不上贴吧","死外边，从这里跳下去","不会骗你们一点经验","又快升级了"]];
    
    // 换一句对话
    $("#m_random").click(function(){
        var i = fRandomBy(0, m_str.length-1);   // 随机产生一个对白
        $("#m_str1").val(m_str[i][0]);
        $("#m_str2").val(m_str[i][1]);
        $("#m_str3").val(m_str[i][2]);
        $("#m_str4").val(m_str[i][3]);
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
