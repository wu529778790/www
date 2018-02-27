<?php
// 公共头部文件
require 'header.php';

// 网页head 标题 标题描述 关键字
title('这是网页标题', '这是网页描述', '网页关键字,用逗号分隔,可以随意添加');
?>

<style>
/* 在这里加入页面自定义的样式 */
h3 {margin-top: 50px;}
</style>

<script> 
// 在这里加入页面自定义的js
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
    
<?php
// 网站导航栏
banner('demo');
?>

<!--========================================
网页内容区开始 
==========================================-->

<h2>这是一个示例页面 <small>你可以根据这个示例页面添加自己想要展示的内容</small></h2>

<br>

<h3>引用块文字示例</h3>
<blockquote>
  <p>引用块文字引用块文字引用块文字引用块文字引用块文字引用块文字引用块文字引用块文字引用块文字引用块文字</p>
  <footer>没错，这句话是 <cite>我说的</cite></footer>
</blockquote>

<h3>无序列表示例</h3>
<ul>
  <li>Lorem ipsum dolor sit amet</li>
  <li>Consectetur adipiscing elit</li>
  <li>Integer molestie lorem at massa</li>
  <li>Facilisis in pretium nisl aliquet</li>
  <li>Nulla volutpat aliquam velit
    <ul>
      <li>Phasellus iaculis neque</li>
      <li>Purus sodales ultricies</li>
      <li>Vestibulum laoreet porttitor sem</li>
      <li>Ac tristique libero volutpat at</li>
    </ul>
  </li>
  <li>Faucibus porta lacus fringilla vel</li>
  <li>Aenean sit amet erat nunc</li>
  <li>Eget porttitor lorem</li>
</ul>

<h3>按钮示例</h3>
<button type="button" class="btn btn-default">（默认样式）Default</button>
<button type="button" class="btn btn-primary">（首选项）Primary</button>
<button type="button" class="btn btn-success">（成功）Success</button>
<button type="button" class="btn btn-info">（一般信息）Info</button>
<button type="button" class="btn btn-warning">（警告）Warning</button>
<button type="button" class="btn btn-danger">（危险）Danger</button>
<button type="button" class="btn btn-link">（链接）Link</button>

<h3>带背景色文字示例</h3>
<p class="bg-primary">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
<p class="bg-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
<p class="bg-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
<p class="bg-warning">Etiam porta sem malesuada magna mollis euismod.</p>
<p class="bg-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>

<h3>文字标签示例</h3>
<span class="label label-default">Default</span>
<span class="label label-primary">Primary</span>
<span class="label label-success">Success</span>
<span class="label label-info">Info</span>
<span class="label label-warning">Warning</span>
<span class="label label-danger">Danger</span>

<h3>文字警告条示例</h3>
<div class="alert alert-success" role="alert">
  <strong>Well done!</strong> You successfully read this important alert message.
</div>
<div class="alert alert-info" role="alert">
  <strong>Heads up!</strong> This alert needs your attention, but it's not super important.
</div>
<div class="alert alert-warning" role="alert">
  <strong>Warning!</strong> Better check yourself, you're not looking too good.
</div>
<div class="alert alert-danger" role="alert">
  <strong>Oh snap!</strong> Change a few things up and try submitting again.
</div>

<h3>进度条示例</h3>
<div class="progress">
  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
    <span class="sr-only">40% Complete (success)</span>
  </div>
</div>
<div class="progress">
  <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
    <span class="sr-only">20% Complete</span>
  </div>
</div>
<div class="progress">
  <div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
    <span class="sr-only">60% Complete (warning)</span>
  </div>
</div>
<div class="progress">
  <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
    <span class="sr-only">80% Complete (danger)</span>
  </div>
</div>

<h3>面板示例</h3>
<div class="panel panel-default">
  <div class="panel-heading">Panel heading without title</div>
  <div class="panel-body">
    Panel content
  </div>
</div>
<div class="panel panel-primary">
  <div class="panel-heading">Panel heading without title</div>
  <div class="panel-body">
    Panel content
  </div>
</div>
<div class="panel panel-success">
  <div class="panel-heading">Panel heading without title</div>
  <div class="panel-body">
    Panel content
  </div>
</div>
<div class="panel panel-info">
  <div class="panel-heading">Panel heading without title</div>
  <div class="panel-body">
    Panel content
  </div>
</div>
<div class="panel panel-warning">
  <div class="panel-heading">Panel heading without title</div>
  <div class="panel-body">
    Panel content
  </div>
</div>
<div class="panel panel-danger">
  <div class="panel-heading">Panel heading without title</div>
  <div class="panel-body">
    Panel content
  </div>
</div>


<h3>提示标签示例</h3>
<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="left" title="Tooltip on left">Tooltip on left</button>
<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Tooltip on top">Tooltip on top</button>
<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="Tooltip on bottom">Tooltip on bottom</button>
<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Tooltip on right</button>


<h3>更多内容示例</h3>
<div class="alert alert-info" role="alert">
    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
    因为代码中引入了Bootstrap，因此您可以直接阅读Bootstrap的官方文档以获取更多用法
    <a href="http://v3.bootcss.com/css/" class="alert-link">戳我传送</a>
</div>
<!--========================================
网页内容区结束
==========================================-->

<?php
//网站底部
footer();
?> 