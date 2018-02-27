<?php 

include('./inc/aik.config.php');?>
<?='<header class="header">
	<div class="container">
		<h1 class="logo"><a href="'; echo stripslashes($aik['pcdomain']);?>" title="<?php echo stripslashes($aik['keywords']);?>"><?php echo stripslashes($aik['logo_dh']);?><span><?php echo stripslashes($aik['title']);?><?='</span></a></h1>		<div class="sitenav">
		<ul><li id="menu-item-18" class="menu-item"><a href="'; echo stripslashes($aik['pcdomain'])?>">首页</a>
</li>
<?php
 $do1='http://www.360kan.com/dianying/list.php?cat=all&pageno=1';$do2='http://www.360kan.com/dianshi/list.php?cat=all&pageno=1';$do3='http://www.360kan.com/zongyi/list.php?cat=all&pageno=1';$do4='http://www.360kan.com/dongman/list.php?cat=all&pageno=1';$do5='http://list.youku.com/category/video/c_94_d_1_s_1_p_1';$do11=base64_encode($do1);$do21=base64_encode($do2);$do31=base64_encode($do3);$do41=base64_encode($do4);$do51=base64_encode($do5);?>
<?='<li id="menu-item-27" class="menu-item"><a href="./movie.php?m='; echo $do11;?><?='">电影</a></li>
<li id="menu-item-31" class="menu-item"><a href="./tv.php?u='; echo $do21;?><?='">追热剧</a></li>
<li id="menu-item-20" class="menu-item"><a href="./zongyi.php?m='; echo $do31;?><?='">综艺</a></li>
<li id="menu-item-20" class="menu-item"><a href="./dongman.php?m='; echo $do41;?><?='">动漫</a></li>
<li id="menu-item-20" class="menu-item"><a href="./gaoxiao.php?m='; echo $do51;?><?='">搞笑</a></li>
<li id="menu-item-20" class="menu-item">'; echo stripslashes($aik['top1_ad']);?><?='</li>
<li id="menu-item-20" class="menu-item">'; echo stripslashes($aik['top_ad']);?><?='</li>
<li id="menu-item-20" class="menu-item">'; echo stripslashes($aik['top2_ad']);?><?='</li>
</ul>
		</div>
		<span class="sitenav-on"><i class="icon-list"></i></span>
		<span class="sitenav-mask"></span>
					<div class="accounts">
									<a class="account-weixin" href="javascript:;"><i class="fa"></i>
						<div class="account-popover"><div class="account-popover-content">'; echo stripslashes($aik['wx_ad']);?><?='</div></div>
					</a>
																			</div>
							<span class="searchstart-on"><i class="icon-search"></i></span>
			<span class="searchstart-off"><i class="icon-search"></i></span>
			<form method="post" class="searchform" action="./seacher.php" >
				<button tabindex="3" class="sbtn" type="submit"><i class="fa"></i></button><input tabindex="2" class="sinput" name="wd" type="text" placeholder="输入关键字" value="">
			</form>
			</div>
			
</header>';?>
