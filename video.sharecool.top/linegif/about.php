<?php
// 公共头部文件
require 'header.php';
// 网页head 标题 标题描述 关键字
title('关于','','关于');
?>
    
<style>
p { margin-left: 5px; text-indent: 2em; margin-bottom: 8px; color: #666;}
.box { color: #555; clear: both; color: #666;}
.box .title { color: #444; border-bottom: 1px dashed #ddd; font-size: 25px; margin-bottom: 10px; margin-top: 10px;}
.box .title span { border-bottom: 1px solid #aaa; display: inline-block; margin-bottom: -1px; text-align: center; padding: 5px 10px; float: none; width: auto;}
.box p { line-height: 1.7em; text-indent: 2em; padding: 3px; margin: 0;}
.box p b { padding-right: 8px;}
.reward-qrcode{
    text-align: center;
}
.reward-qrcode img{
    max-width: 200px;
}
.reward-qrcode p{
    text-indent: 0;
}
</style>


<?php
// 网站导航栏
banner(2);
?>

<div id="about" class="box">
    <h2 class="text-center title"><span>关于我们</span></h2>
    <p>表情图是一家专门用于在线生成斗图表情包的网站</p>
    <p>要斗图？选我们，准没错！</p>
    <p>您可以扫描以下二维码关注我们：</p>
    <div class="row">
        <div class="col-sm-4 reward-qrcode">
            <img src="http://www.yeser.cc/content/templates/FLY/img/wxzf.png" alt="扫一扫关注我们" class="img-thumbnail">
            <p>微信二维码</p>
        </div>
        <div class="col-sm-4 reward-qrcode">
            <img src="http://www.yeser.cc/content/templates/FLY/img/txzf.png" alt="扫一扫关注微博" class="img-thumbnail">
            <p>QQ</p>
        </div>
        <div class="col-sm-4 reward-qrcode">
            <img src="http://www.yeser.cc/content/templates/FLY/img/alzf.png" alt="扫一扫下载手机app" class="img-thumbnail">
            <p>支付宝</p>
        </div>
    </div>
</div>


<div id="notice" class="box">
    <h2 class="text-center title"><span>使用协议</span></h2>
    <strong>关于图片</strong>
    <p>本站所有图片均为用户上传分享，如有侵权，请联系我删除。</p>
    <strong>关于上传</strong>
    <p>本站支持用户自由上传并分享聊天表情图，但在上传过程中请遵守下列条款：</p>
    <p><b>1、</b>严禁发布色情、淫秽、反动、赌博、毒品、侵权的图片标题与描述；</p>
    <p><b>2、</b>严禁上传色情、淫秽、反动、赌博、毒品、侵权等违法的图片，例如：</p>
    <p><b>●</b>包含反动、暴乱、政治隐喻内容的图片；</p>
    <p><b>●</b>以暴力、恐怖、迷信为主题的图片，包括但不限于包含鲜血、尸体、骨架、鬼神等内容的图片；</p>
    <p><b>●</b>以女性和男性的乳、生殖器官为主题的图片；</p>
    <p><b>●</b>以人体彩绘的名义裸露乳、生殖器官部位的图片；</p>
    <p><b>●</b>侮辱、伤害人性和人格的图片；</p>
    <p><b>●</b>"推女郎""ROSI""第四印象""RU1MM""DISI"等尺度过大、姿势不雅、低俗内容的写真图片；</p>
    <p><b>●</b>以卡通、动漫、情趣用品、SM用品、成人用品图片的名义提供包含以上内容的图片。</p>
    <p><b>3、</b>请确定您的图片不包含非法、侵权内容，否则将被我们按照相关法律直接删除，并保留或者提供必要的法律证据！</p>
    <strong>关于搜索</strong>
    <p>本站图片搜索功能由搜狗提供，本站不对搜索结果负责。</p>
</div>




<?php
//网站底部
footer();
?> 