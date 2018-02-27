<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '朋友圈尖叫体生成器',   // 标题
    'description' => '免费在线生成风靡朋友圈的尖叫体，无二维码无广告，让您尽情装逼！你就是朋友圈里最亮的星！',    //描述
    'keywords' => '朋友圈尖叫体生成器,尖叫体',   //关键字(用英文逗号分隔)
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
    <label for="m_font">请选择字体：</label>
    <select class="form-control" name="font" id="m_font">
        <option value="0">微软雅黑</option>
        <option value="1">方正咆哮</option>
        <option value="2">华康少女</option>
    </select>
</div>
    
<div class="form-group">
    <label for="m_str1">请输入文字内容：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入文字内容(9字内)" value="祝你鸡年大吉吧"  maxlength="9" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <div class="checkbox">
        <label>
            <input name="color" id="m_color" type="checkbox" value="true">
            五颜六色
        </label>
    </div>
</div>

<div class="form-group">
    <button id="m_random" type="button" class="btn btn-success btn-block">换一句</button>
</div>

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>

<div class="alert alert-info" role="alert">
    <p class="m-title">如何使用？</p>
    <p>生成图片 > 保存图片 > 将图片发送至朋友圈，就可以开始装逼啦！</p>
    <p><a href="##" id="m_tips" class="alert-link">点击查看使用范例</a></p>
</div>

<script type="text/javascript">
$(function(){
    var m_str = ["友谊的小船说翻就翻", "ILoveYou", "你看我牛逼不", "不约", "哈哈哈哈哈"];   // 随机一句的句子内容
    
    // 换一句对话
    $("#m_random").click(function(){
        var i = fRandomBy(0, m_str.length-1);   // 随机产生一个对白
        $("#m_str1").val(m_str[i]);
        picBuild(); //调用图像生成函数
        return true;
    })
    
    // 显示使用说明
    $("#m_tips").click(function(){
        updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>&cover=2");
        return false;
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
    var imgUrl = "<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>&" + $("#m_imgSet").serialize();
    if($("#m_color").is(':checked') == true) imgUrl = imgUrl + fRandomBy(0, 100000);    // 保证随机颜色每次都不一样
    updatePic(imgUrl);
}

// 通用图像生成完毕后回调函数
function buildOver(){
    $("#m_submit").html("生成");
    $('#m_submit').removeAttr("disabled");     // 恢复生成按钮的使用
}

// 默认显示封面
updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>&cover=true");
// picBuild(); //调用图像生成函数
</script> 

<?php
}

?>
