<?php
error_reporting(0);
$picID= (int)$_GET['tu'];
if($picID==""){$picID=1;}
//echo $picID;
$picUrl=getimagesize("images/".$picID.".jpg");
$picWidth=$picUrl["0"];////获取图片的宽
$picHeight=$picUrl["1"];///获取图片的高
//echo $picWidth."<br>";
//echo $picHeight."<br>";

$xml_array=simplexml_load_file("images/".$picID.".xml"); //将XML中的数据,读取到数组对象中 
foreach($xml_array as $tmp){ 
    $picy=$tmp->y;
    $picfontsize=$tmp->fontsize;
    $picword=$tmp->picword;
}
$picword = empty($picword) ? '我有图片我不怕' : $picword;
//echo $tmp->id."-".$picy."-".$picfont."<br>"; 
// 公共头部文件
require 'header.php';
// 网页head 标题 标题描述 关键字
title($picword.' - 斗图表情制作','','');
?>

    <!-- 页面自定义css文件 -->
    <link rel="stylesheet" href="style/pic.css" />
    
<?php
// 网站导航栏
banner();
?>

<div id="mainCenter">
    <img id="downPic" class="shadow" src="">
    <canvas id="myCanvas" width="<?php echo $picWidth; ?>" height="<?php echo $picHeight; ?>" style="none;">您的浏览器不支持canvas标签。</canvas> 
    <br /><br />
    <p>在下方输入文字。在图片上使用鼠标右键或长按保存</p>
    <input name="text" type="text" class="form-control" placeholder="在这里输入" id="text" onclick="this.focus();this.select()" onchange ="draw();" onpropertychange ="draw();" oninput  ="draw();" value="<?php echo $picword; ?>" />
</div>
<script type="text/javascript">
function draw(){
    var canvas = document.getElementById("myCanvas");   //获取Canvas对象(画布) 
    var write = document.getElementById("text").value   //获取文本的值
    if(canvas.getContext){  //简单地检测当前浏览器是否支持Canvas对象，以免在一些不支持html5的浏览器中提示语法错误 
        var ctx = canvas.getContext("2d");  //获取对应的CanvasRenderingContext2D对象(画笔)   
        var img = new Image();  //创建新的图片对象   
        img.src = "images/<?php echo $picID; ?>.jpg";   //指定图片的URL   
        img.onload = function(){   //浏览器加载图片完毕后再绘制图片   
            ctx.drawImage(img, 0, 0, <?php echo $picWidth; ?>, <?php echo $picHeight; ?>);  //以Canvas画布上的坐标(10,10)为起始点，绘制图像 //图像的宽度和高度分别缩放到350px和100px        
            ctx.font = "<?php echo $picfontsize."px"; ?> 微软雅黑"; //设置字体样式 
            ctx.fillStyle = "black";    //设置字体填充颜色 
            ctx.textAlign = "center";   //设置文本的水平对对齐方式
            ctx.fillText(write, canvas.width/2, <?php echo $picy; ?>);  //从坐标点(x,y)开始绘制文字
            
            var myImage = canvas.toDataURL("image/png");    //转化为图像数据
            var imageElement = document.getElementById("downPic");  //获取一个图像NODE
            imageElement.src = myImage;
    	};
    }  
}
draw(); //进页面时先绘制一次
</script> 

<?php
//网站底部
footer();
?> 