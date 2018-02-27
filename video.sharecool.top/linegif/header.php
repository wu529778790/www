<?php
ob_start();
session_start();
header('Content-type: text/html; charset=utf-8');
error_reporting(0);

require(dirname(__FILE__).'/config.php');

function title($title, $description=null, $keywords=null){
    $title = $title.' - '.C('webTitle');    ?>
<!doctype html> 
<html> 
<head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?php echo $title;?></title>
    <meta name="description" content="<?php echo $description;?>"/>
    <meta name="keywords" content="<?php echo $keywords;?>"/>
    
    <!-- 新 Bootstrap 核心 CSS 文件 -->
    <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    
    <!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    
    <!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    
    <script>
    //防止CDN静态资源抽风
    if (!window.jQuery) {
        document.write('<link rel="stylesheet" href="<?php echo C('siteurl');?>/style/bootstrap.min.css">');
        document.write('<script src="<?php echo C('siteurl');?>/js/jquery.min.js"><\/script>');
        document.write('<script src="<?php echo C('siteurl');?>/js/bootstrap.min.js"><\/script>');
    }
    </script>
    
    
    <!-- 页面通用自定义css文件 -->
    <link rel="stylesheet" href="<?php echo C('siteurl');?>/style/style.css" />
    
    <?php
    echo '<!-- 输出网站相关信息，供页面内的 js 文件调用 -->
    <script>
        var mkSiteInfo = { siteUrl: "'.C('siteurl').'", debug: false }
    </script>';
    ?>
    
    <!--[if lt IE 9]>
        <script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!--返回顶部插件-->
    <script type="text/javascript" src="<?php echo C('siteurl');?>/js/scrolltopcontrol.js"></script>
    
    
<?php
}

function banner($choose=null){
$myactive[$choose]=' class="active"';
?>
<!--公共banner部分开始（本banner由代码动态生成）-->
</head> 

<body>

<nav class="navbar navbar-default navbar-fixed-top">
<div class="container">
    
<div class="container-fluid">

<div class="navbar-header">
    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#my-navbar-collapse" aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" title="点击回首页" href="<?php echo C('siteurl');?>"><?php echo C('webTitle');?></a>
</div>

<div class="collapse navbar-collapse" id="my-navbar-collapse">
    <ul class="nav navbar-nav">
        <!-- 导航栏链接开始 -->
        <li<?php echo $myactive[1];?>>
            <a href="<?php echo C('siteurl');?>" title="我的表情，自己造">
                表情制作
            </a>
        </li>
        
        <li<?php echo $myactive[5];?>>
            <a href="<?php echo C('siteurl');?>/zb.php" title="无形装逼，最为致命">
                装逼神器
            </a>
        </li>
        
        <li<?php echo $myactive[3];?>>
            <a href="<?php echo C('siteurl');?>/rand.php" title="你要的，都在这">
                表情大全
            </a>
        </li>
        
        <li<?php echo $myactive[4];?>>
            <a href="<?php echo C('siteurl');?>/topic.php" title="终于可以一次打包下载所有的表情了！">
                表情专题
            </a>
        </li>
        
        <li<?php echo $myactive['search'];?>>
            <a href="<?php echo C('siteurl');?>/search.php" title="想要什么表情图？尽管搜！">
                表情搜索
            </a>
        </li>
        
        <li<?php echo $myactive['daohang'];?>>
            <a href="http://www.xiaodao.la/i-wz-40255301.html" target="_blank" title="本站提供的网红举牌制作供应实用">
                举牌制作
            </a>
        </li>
        
        <li<?php echo $myactive['demo'];?>>
            <a href="<?php echo C('siteurl');?>/demo.php" title="示例页面">
                示例页面
            </a>
        </li>
        
        
        <li<?php echo $myactive[2];?>>
            <a href="<?php echo C('siteurl');?>/about.php" title="装了逼，还想跑？">
                关注我们
            </a>
        </li>
        <!--导航栏链接结束-->
    </ul>
    <form class="navbar-form navbar-right search-nav" role="search" action="<?php echo C('siteurl');?>/search.php" method="get">
        <div class="form-group">
            <input type="text" class="form-control" name="wd" placeholder="请输入表情关键字">
        </div>
        <button type="submit" class="btn btn-default">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
        </button>
    </form>
</div><!-- /.navbar-collapse -->

</div><!-- /.container-fluid -->
</div><!--class="container"-->
</nav>


<div class="container">

<!--公共banner部分结束-->

<?php
}

function footer(){
?>
<!--公共foot部分开始（本footer由代码动态生成）-->

</div><!--class="container"中部容器-->

<div class="copyright">
    <div class="container text-center">
        <span>
            Copyright &copy; <a href="<?php echo C('siteurl');?>"><?php echo C('webTitle')?></a>
        </span> |
        <span>
            <a href="http://www.miibeian.gov.cn/" target="_blank"><?php echo C('miibeian')?></a>
        </span>
        <span class="hidden-xs"> |</span>
        <br class="visible-xs-inline">
        <span>
            <!--<img src="http://www.beian.gov.cn/portal/download"> -->
            <a href="http://www.beian.gov.cn/portal/registerSystemInfo" target="_blank"><?php echo C('beian')?></a>
        </span>
    </div>
</div>

<!--站长统计代码（您可以将这里的代码改为您自己的站点统计代码）-->
<script type="text/javascript">
    var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
    document.write(unescape("%3Cspan style='display:none;' id='cnzz_stat_icon_1257545628'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D1257545628' type='text/javascript'%3E%3C/script%3E"));
</script>
<!--站长统计代码（您可以将这里的代码改为您自己的站点统计代码）-->

</body> 
</html> 
<!--公共foot部分结束-->
<?php
}
