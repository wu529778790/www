<?php 
  include('config.php');include('admincore.php');?>
<?='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
'; include('inc.php');?>
</head>
<body>
<?php $nav='home';include('head.php');?>
<?='<div id="hd_main">
   <div style="margin:20px;">
   ';
 $path=$_SERVER['SCRIPT_NAME'];if(strpos($path,'/admin/')>-1){echo '<div style="text-align:center; color:red; font-size:16px; margin-bottom:15px;">您的后台目录默认为 域名/admin ，为了安全请尽快修改后台目录</div>';}if($aik['admin_name']=='aik' && $aik['admin_pass']=='a13a02276910cbc879f020ed88816512'){echo '<div style="text-align:center; color:red; font-size:16px; margin-bottom:15px;">您的账号密码为系统默认，请尽快修改</div>';}?>
<?='      <table width="600" border="0" class="tablecss" cellspacing="0" cellpadding="0" align="center">
   <tr>
    <td colspan="2" align="center">欢迎使用小彬影院管理系统！</td>
    </tr>
  <tr>
    <td align="right">当前使用版：</td>
    <td><span>V3.7</span></td>
  </tr>
  <tr>
    <td align="right">最新版：</td>
    <td><a href="https://jq.qq.com/?_wv=1027&k=5Wu6Sje" target="_blank"><font color="#FF0000">点击查看</font></a></td>
  </tr>
  <tr>
    <td width="213" align="right">服务器操作系统：</td>
    <td width="387">'; $os=explode(' ',php_uname());echo $os[0];?> (<?php if('/'==DIRECTORY_SEPARATOR){echo $os[2];}else{echo $os[1];}?><?=')</td>
  </tr>
  <tr>
    <td width="213" align="right">服务器解译引擎：</td>
    <td width="387">'; echo $_SERVER['SERVER_SOFTWARE'];?><?='</td>
  </tr>
  <tr>
    <td width="213" align="right">PHP版本：</td>
    <td width="387">'; echo PHP_VERSION?><?='</td>
  </tr>
  <tr>
    <td align="right">域名：</td>
    <td>'; echo $_SERVER['HTTP_HOST']?><?='</td>
  </tr>
  <tr>
    <td align="right">allow_url_fopen：</td>
    <td>'; echo ini_get('allow_url_fopen')?'<span class="green">支持</span>' :'<span class="red">不支持</span>'?><?='</td>
  </tr>
  <tr>
    <td align="right">curl_init：</td>
    <td>'; if(function_exists('curl_init')){echo '<span class="green">支持</span>';}else{echo '<span class="red">不支持</span>';}?><?='</td>
  </tr>

<tr>
    <td align="right">/data/目录权限检测：</td>
    <td>'; echo is_writable('../data/')?'<span class="green">可写</span>' :'<span class="red">不可写</span>'?><?='</td>
  </tr>  

  <tr>
    <td colspan="2" style="line-height:220%; text-indent:28px;">欢迎使用本程序，源码更新加群<a href="https://jq.qq.com/?_wv=1027&k=5Wu6Sje" target="_blank">（QQ群：346302315）</a>，感谢朋友们的支持！</br><font color="red">温馨提示：小彬影院源码【免授权版】免费版，更新来源 QQ群文件：<a href="https://jq.qq.com/?_wv=1027&k=5Wu6Sje" target="_blank">https://jq.qq.com/?_wv=1027&k=5Wu6Sje</a></font></br>免费/永久。</br><font color="red">请添加官方QQ交流群更多！</font></br>
3.7版本修复：</br>
1.修复后台无法登陆bug </br>
2.修复后台保存多出斜杠bug </br>
3.修复前台微信无法显示bug </br>
4.QQ群:346302315 联系群主</br>
5.加入↑官方QQ群了解更多最新资源</td>
    </tr>
   
</table>

   </div>

</div>
'; include('foot.php');?>
</body>
</html>
