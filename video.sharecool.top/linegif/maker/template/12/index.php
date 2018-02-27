<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '王宝强对话生成器',   // 标题
    // 'description' => '免费在线生成支付宝五福卡截图',    //描述
    'keywords' => '王宝强,泰囧,超贱,泰囧搞笑对话,人在囧途',   //关键字(用英文逗号分隔)
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
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入第一句内容" value="" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str2">请输入第二句对话：</label>
    <input name="str2" type="text" class="form-control" id="m_str2" placeholder="请输入第二句内容" value="" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str3">请输入第三句对话：</label>
    <input name="str3" type="text" class="form-control" id="m_str3" placeholder="请输入第三句内容" value="" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <button id="m_random" type="button" class="btn btn-success btn-block">换一句</button>
</div>

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>

<div class="alert alert-info" role="alert">
    <p class="m-title">图片出处：</p>
    <p>图片出自徐峥导演的电影《人再囧途之泰囧》</p>
    <p>《泰囧》讲述了商业成功人士徐朗（徐峥 饰）用了五年时间发明了一种叫“油霸”的神奇产品——每次汽车加油只需加到三分之二，再滴入2滴“油霸”，油箱的汽油就会变成满满一箱。徐朗的同学，兼商业竞争对手高博（黄渤 饰）想把这个发明一次性卖给法国人。但徐朗坚决不同意，他希望深入开发研究， 把“油霸”发扬光大，得到更远的收益。两个人各抒己见，争论不休，一直无果。由于两人股份相同，唯有得到公司最大股东周扬的授权书，方可达到各自目的。当得知周扬在泰国后，徐朗立刻启程寻找。而高博获悉后将一枚跟踪器放在徐朗身上一起去了泰国。飞机上，徐朗遇到了王宝（王宝强 饰），别有心机地想利用他来摆脱对手高博的追赶，可他不仅没甩掉王宝，还成了他的“贴身保姆”…… 究竟徐朗和高博谁会最终拿到周扬的授权书？而三个各怀目的的人，又将带来一段如何爆笑的泰国神奇之旅？</p>
    <p>想看这部电影？<a href="http://bangumi.bilibili.com/movie/10041" class="alert-link" rel="nofollow" target="_blank">点击传送</a></p>
</div>

<script type="text/javascript">
$(function(){
    var m_str = [["就要开学了吧","作业还没开始做吧","我们没作业，哈哈哈！"],
    ["在学校对不对？","还没放假对不对？","我都到家啦，哈哈哈！"],
    ["是不是要考试啦？","还没开始复习吧？","等死吧你！"],
    ["快三十了吧？","还没有对象？","我孩子都有啦！"]];
    
    // 换一句对话
    $("#m_random").click(function(){
        var i = fRandomBy(0, m_str.length-1);   // 随机产生一个对白
        $("#m_str1").val(m_str[i][0]);
        $("#m_str2").val(m_str[i][1]);
        $("#m_str3").val(m_str[i][2]);
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
