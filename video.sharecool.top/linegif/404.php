<?php
header('HTTP/1.1 404 Not Found');   //返回404状态
header("status: 404 Not Found");
// 公共头部文件
require 'header.php';
// 网页head 标题 标题描述 关键字
title('404', '请修改这里的文字为网页描述', '网页关键字,用逗号分隔,可以随意添加');
?>

    <style>
    body{
        background: #ffffff url(<?php echo C('siteurl'); ?>/images/404/<?php echo mt_rand(1, 6);?>.gif);
    }
    #error-no{
        font-size: 100px;
    }
    .shadow,div{
        text-shadow: 0px 0px 8px #ffffff;
    }
    </style>
    
    <script> 
    var i = 5; 
    var intervalid; 
    intervalid = setInterval("fun()", 1000); 
    function fun() { 
        if (i == 0) { 
            window.location.href = "<?php echo C('siteurl'); ?>"; 
            clearInterval(intervalid); 
        } 
        document.getElementById("mes").innerHTML = i; 
        i--; 
    }
    </script>
    
<?php
// 网站导航栏
banner(2);
?>

<h1 class="text-center shadow" id="error-no">404</h1>

<h1 class="text-center shadow">你好像迷路了</h1>

<h3 class="text-center shadow">
    <a href="<?php echo C('siteurl'); ?>"><span id="mes">5</span>秒后送你回地球</a>
</h3>

<br>

<?php
//网站底部
footer();
?> 