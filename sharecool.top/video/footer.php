<?php 
?><?='<div class="foornav">
	<a href="'; echo $aik['pcdomain']?><?='"><span><img src="images/index.png"/>首页</span></a>
	<a href="./movie.php?m='; echo $do11;?><?='"><span><img src="images/video.png"/>电影</span></a>
	<a href="./tv.php?u='; echo $do21;?><?='"><span><img src="images/dianshi.png"/>电视剧</span></a>
	<a href="./zongyi.php?m='; echo $do31;?><?='"><span><img src="images/dianshiju.png"/>综艺</span></a>
	<a href="./dongman.php?m='; echo $do41;?><?='"><span><img src="http://i2.letvimg.com/lc04_iscms/201607/14/15/52/2076a8d5b2d44f2f90cae2a2848f12b6.png"/>动漫</span></a>
	'; echo stripslashes($aik['end_ad']);?>
<?='</div>
<footer class="footer">
<div class="branding branding-black">
	<div class="container" style="text-align: center;">
		<h2>'; echo stripslashes($aik['sitename']);?><?=' - 海量高清VIP视频免费观看</h2>
'; echo stripslashes($aik['youlian']);?>
<?='			</div>
</div>


<p style="padding: 0 4px;">'; echo stripslashes($aik['foot']);?><br/>管理员邮箱：<?php echo stripslashes($aik['admin_email']);?><br/>&copy; 2017 <a href="<?php echo stripslashes($aik['pcdomain']);?>"><?php echo stripslashes($aik['sitename']);?><?='</a>&nbsp; <a href="http://dy.eb89.com">'; echo stripslashes($aik['icp']);?><?='</a>&nbsp; 
        本站主题由 '; echo stripslashes($aik['homelink']);?> 提供 &nbsp; <?php echo stripslashes($aik['tongji']);?><?=' </footer>
	<div class="rewards-popover-mask" etap="rewards-close"></div>
	<div class="rewards-popover">
		<h3>觉得本站还不错就打赏一下吧！</h3>
				<div class="rewards-popover-item">
			<h4>支付宝扫一扫打赏</h4>
			'; echo stripslashes($aik['zfb_ad']);?>
<?='		</div>
						<div class="rewards-popover-item">
			<h4>微信扫一扫打赏</h4>
			'; echo stripslashes($aik['wx_ad']);?>
<?="		</div>
				<span class=\"rewards-popover-close\" etap=\"rewards-close\"></span>
	</div> 

<script type='text/javascript' src='js/main.js'></script>";?>
