<?php
$bangdan=file_get_contents('http://www.360kan.com/dianying/index.html');
//print_r($bangdan);
$bdzz='#<a title="(.*?)" href="(.*?)" class="name">(.*?)</a>[\s\S]+?<span class="vv">(.*?)</span>#';
preg_match_all($bdzz, $bangdan,$bdarr);
//print_r($bdarr);
$bdname=$bdarr[1];//榜单名字
$bdurl=$bdarr[2];//榜单链接
$bdliang=$bdarr[4];
//以上为电影榜单列表
$tvbangdan=file_get_contents('http://www.360kan.com/dianshi/index.html');
$tvbdzz='#<a title="(.*?)" href="(.*?)" class="name">(.*?)</a>[\s\S]+?<span class="vv">(.*?)</span>#';
preg_match_all($tvbdzz, $tvbangdan,$tvbdarr);
//print_r($tvbdarr);
$tvbdname=$tvbdarr[1];//榜单名字
$tvbdurl=$tvbdarr[2];//榜单链接
$tvbdliang=$tvbdarr[4];
//以上为电视剧榜单