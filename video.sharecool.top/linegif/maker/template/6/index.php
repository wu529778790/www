<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '小明被抓生成器',   // 标题
    // 'description' => '免费在线生成支付宝五福卡截图',    //描述
    'keywords' => '小明被抓生成器,小明拿伞,我能送你回家吗',   //关键字(用英文逗号分隔)
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
    <label for="m_str1">请输入被抓者姓名：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="被抓者姓名" value="小明" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str2">请输入底部文字：</label>
    <input name="str2" type="text" class="form-control" id="m_str2" placeholder="底部显示的文字" value="老实点，每次扫黄都有你" onclick="this.focus();this.select()">
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
