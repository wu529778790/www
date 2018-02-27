<?php

if(!defined('MK_Pic')) die('非法访问 - Insufficient Permissions');

$setting = array(
    'title' => '搞笑奖状在线制作',   // 标题
    // 'description' => '',    //描述
    'keywords' => '搞笑奖状,搞笑证件,奖状证件,在线制作,装逼',   //关键字(用英文逗号分隔)
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
    <label for="m_str1">请输入获奖者姓名：</label>
    <input name="str1" type="text" class="form-control" id="m_str1" placeholder="请输入获奖者姓名" value="张三" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str2">请输入奖项名：</label>
    <input name="str2" type="text" class="form-control" id="m_str2" placeholder="请输入奖项名" value="十大杰出青年" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <label for="m_str3">请输入发奖机构：</label>
    <input name="str3" type="text" class="form-control" id="m_str3" placeholder="请输入发奖机构" value="杰出青年评选中心" onclick="this.focus();this.select()">
</div>

<div class="form-group">
    <button id="m_random" type="button" class="btn btn-success btn-block">换一句</button>
</div>

<div class="form-group">
    <button type="submit" id= "m_submit" class="btn btn-primary btn-block">生成</button>
</div>
</form>


<script type="text/javascript">
$(function(){
    var m_str = [["十大杰出青年","杰出青年评选中心"],
    ["中国好老公","中国老公联盟"],
    ["中国好老婆","中国老婆联盟"],
    ["奥运火炬手","国际奥运协会颁发"],
    ["三八红旗手","世界妇女联合协会"],
    ["中国最帅男青年","帅哥评定机构"],
    ["全球模范生","国际教育总署(WEO)"],
    ["网恋高手","联合国网恋协会"],
    ["最佳打野","菜逼评选中心"],
    ["最佳上单","菜逼评选中心"],
    ["最佳守塔","菜逼评选中心"],
    ["最佳补兵","菜逼评选中心"],
    ["搞 事 王","全国搞事协会"],
    ["吹 逼 王","全国吹逼协会"],
    ["美女之最","世界美女协会"]];
    
    // 换一句对话
    $("#m_random").click(function(){
        var i = fRandomBy(0, m_str.length-1);   // 随机产生一个对白
        $("#m_str2").val(m_str[i][0]);
        $("#m_str3").val(m_str[i][1]);
        // picBuild(); //调用图像生成函数
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
