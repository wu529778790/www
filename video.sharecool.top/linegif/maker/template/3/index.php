<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '支付宝余额图生成器',   // 标题
    'description' => '完全免费的在线支付宝余额图生成器，无水印，无广告，无任何功能限制！咱也当一回“土豪”！',    //描述
    'keywords' => '支付宝余额图生成器,支付宝截图',   //关键字(用英文逗号分隔)
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
    <label for="m_str1">请输入余额：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入余额值" value="283,532.59" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <button id="m_random" type="button" class="btn btn-success btn-block">随机值</button>
</div>

<div class="form-group">
    <button type="submit" type="button" class="btn btn-primary btn-block">生成</button>
</div>
</form>

<div class="alert alert-warning" role="alert">
    <p class="m-title">温馨提示：</p>
    <p>本支付宝余额生成器仅可用作<strong>装逼娱乐</strong>用途，点击“生成”按钮即代表您已阅读并同意本条款。</p>
    <p>因不合理使用本生成器所生成图片造成的纠纷本站概不负责！</p>
</div>

<script type="text/javascript">
$(function(){
    // 生成随机数
    $("#m_random").click(function(){
        var money = fRandomBy(0, 99999999) / 100;   // 生成随机数
        money = fMoney(money, 2);       // 格式化为金额值
        $("#m_str1").val(money);        // 显示新的金额值
        return true;
    })
    
    // 表单被提交
    $("#m_imgSet").submit(function(){
        picBuild(); //调用图像生成函数
        return false;
    });
    
    $("#m_random").click(); // 一开始就显示随机值
});


// 通用图像生成函数
function picBuild(){
    updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>" + "&" + $("#m_imgSet").serialize());
}

updatePic("<?php echo $config['api_url'].'?types=maker&id='.$config['api_id']?>" + "&cover=true");
// picBuild(); //调用图像生成函数
</script> 

<?php
}

?>
