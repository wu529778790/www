<?php 

session_start();error_reporting(0);if($_GET['act']=='logout'){unset($_SESSION);header('location: ./login.php');die;}$tips='';if($_POST['username']&& $_POST['password']){include('../inc/aik.config.php');$admin_name=htmlspecialchars($_POST['username']);$admin_pass=md5ff(htmlspecialchars($_POST['password']));if($admin_name==$aik['admin_name']&& $admin_pass==$aik['admin_pass']){$_SESSION['admin_aik']=base64_decode('ZHkuZWI4OS5jb20=');echo '<script>window.location.href="./index.php";</script>';die;}else{$tips='账号或密码错误！';}}function md5ff($xzv_0=1){return md5($xzv_0.'eb89');}?>
<?='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理登录</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<style>
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,select,input,textarea,button,p,blockquote,th,td {margin:0; padding:0; outline:none;}
body {
 font-size:12px; color:#343434; background:#AACCEE;
}
a{ color:#343434; text-decoration:none}
a:hover{color:#F00;text-decoration:underline}
img{border:0;vertical-align:top;}
h3{font-size:14px;}
ul,ol,li{list-style:none;line-height:180%;}
table{border-collapse:collapse; border-spacing:0;}
input,button,select {color:#000; font:100% arial; vertical-align:middle; overflow:visible;}
select {height:20px; line-height:20px;}

.cl{clear:both; height:0px; overflow:hidden;}
.cl5{clear:both; height:5px; overflow:hidden;}

.in{ background-color:#FFC}

input{ outline:none;}


.tablecss{background:#D6E0EF;margin:0px auto;word-break:break-all;}
.tablecss tr{background:#F8F8F8;}
.tablecss td{ padding:5px 5px; font-size:12px;border:#D6E0EF solid 1px; *border:0px;}
.tablecss textarea{font-family:Courier New;padding:1px 3px 1px 3px;}
.tablecss input{font-family:11px; padding:1px 2px 1px 2px;}
.tablecss tr.header td{ padding:5px 7px 5px 7px; background-color:#525252; color:#FFFFFF;}
.tablecss tr.header td a{ color:#FFF;}

#footer{ text-align:center; clear:both; padding:10px auto; margin:20px; overflow:hidden; height:40px; color:#036}
#footer a{color:#03C}
</style>
<meta name="robots" content="noindex, nofollow" />
<script type="text/javascript">
function ck(){
    if(document.getElementById(\'username\').value==\'\'){
		alert(\'请输入用户名！\');
		document.getElementById(\'username\').focus();
		return false;
	}else if(document.getElementById(\'password\').value==\'\'){
		alert(\'请输入密码！\');
		document.getElementById(\'password\').focus();
		return false;
	}else{
		return true;

	}
}
</script>
<style>
.inp{height:25px; width:170px; font-size:16px; line-height:25px;}
</style>
</head>
<BODY>

<div class="mt2"></div>
<form name="loginform" method="post" action="" onsubmit="return ck();" style="padding:0;">
	<table border="0" cellspacing="1" cellpadding="0" width="400" class="tablecss" style="margin-top:100px; overflow:hidden;">
<tr class="header">
			<td colspan="4" align="center" style=" height:30px; font-size:18px; font-weight:bold;">影视系统登陆</td>
		</tr>
		<tr>
			<td width="117" align="right" valign="middle" class="tx" style="font-size:16px;">用户名：</td>
		  <td width="260" align="left" valign="middle"><input name="username" type="text" class="inp" id="username" value="" size="30" maxlength="32" autocomplete="off"><span class="gray tips">默认:admin</span></td>
		</tr>
		<tr>
			<td valign="middle" align="right" class="tx" style="font-size:16px;">密　码：</td>
		  <td align="left" valign="middle"><input name="password" type="password" class="inp" id="password" value="" size="30" maxlength="64"><span class="gray tips">默认:admin</span></td>
		</tr>
        
		<tr>
			<input name="act" type="hidden" value="go" /><input type="hidden" name="token" value="'; echo md5(time())?><?='"/>   
		  <td align="center" colspan="4"><div class="cl5"></div><input type="submit" name="go" style="height:35px; width:100px;" value="     登 录    ">
<div class="cl5"></div>
<div style="height:30px; color:#F00; text-align:center; line-height:30px;">'; echo $tips?><?='</div>
          </td>
		</tr>
	</table>
<div class="mt2"></div>
</form>
<div class="mt"></div> 
<div class="cl10"></div>
'; include('foot.php');?>
</BODY>
</HTML>
