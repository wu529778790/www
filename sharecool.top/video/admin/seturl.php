<?php 
include('config.php'); 
$tips = '';
include('admincore.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php include('inc.php'); ?>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dragsort-0.4.min.js"></script>
</head>

<body>
<?php $nav = 'seturl';include('head.php'); ?>

<div id="hd_main">
  <div align="center"><?php echo $tips?></div>
 <form name="configform" id="configform" action="./seturl.php?act=seturl&t=<?php echo time()?>" method="post">
<input name="edit" id="edit" type="hidden" value="1" />
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="tablecss">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" class="tablecss">
<tr class="thead">
<td colspan="10" align="center">尝鲜影片发布<a href="http://bbs.woaik.com/forum.php?mod=viewthread&tid=11&extra=" target="_blank">【发布说明】</a></td>
</tr>
<tr style="color:#999;">
    <td valign="top" style="padding-left:20px;"><span style="color:blue">★、提示：你可以在此处自定义添加自己喜爱的影片发布至首页电影尝鲜板块。</br>
    </td>
</tr>
<?php
if(is_file('../data/aik.seturl.php')){
include('../data/aik.seturl.php');
if(is_array($seturl)){
foreach($seturl['title'] as $k=>$v){
?>
<tr>
    <td valign="top" style="padding-left:15px;">
		<span class="gray tips">类型 </span>
		<select name="seturl[type][]">
		<option value="1"<?php echo $seturl['type'][$k]==1?' selected="selected"':''?>>网页</option>
		<option value="2"<?php echo $seturl['type'][$k]==2?' selected="selected"':''?>>直链</option>
		<option value="3"<?php echo $seturl['type'][$k]==3?' selected="selected"':''?>>站内</option>
		</select>	
		<span class="gray tips">片名 </span>
		<input name="seturl[title][]" type="text" value="<?php echo $seturl['title'][$k]?>" size="20" />
		<span class="gray tips">图片 </span><input name="seturl[img][]" type="text" value="<?php echo $seturl['img'][$k]?>" size="20" />		
		<textarea name="seturl[newurl][]" cols="80" rows="3"><?php $seturl['newurl'][] = str_replace("\\'","'",$seturl['newurl'][$k]);echo htmlspecialchars($seturl['newurl'][$k])?></textarea><span class="gray tips"> 影片链接</span>
	</td>
</tr>
<?php
}
}
}
?>
<tr>
    <td valign="top" style="padding-left:15px;">
		<span class="gray tips">类型 </span>
		<select name="seturl[type][]">
		<option value="1">网页</option>
		<option value="2">直链</option>
		<option value="3">站内</option>
		</select>	
		<span class="gray tips">片名 </span>
		<input name="seturl[title][]" type="text" value="" size="20" />
		<span class="gray tips">图片 </span>
		<input name="seturl[img][]" type="text" value="" size="20" />
		<textarea name="seturl[newurl][]" cols="80" rows="3"><?php $seturl['newurl'][] = str_replace("\\'","'",$seturl['newurl'][$k]);?></textarea><span class="gray tips"> 影片链接</span>
    </td>
    </tr>
<tr id="fbox">
<td colspan="10" align="left" style="padding-left:20px;"><input id="configSave" type="submit" value="     保 存     ">   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; (删除一条请清空该条的片名后保存)</td>
</tr>
</table>
</form>
</div>
<?php include('foot.php'); ?>
</body>
</html>
