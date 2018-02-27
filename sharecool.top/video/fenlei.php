<?php 
?>﻿<?php
error_reporting(0);header('Content-type:text/html;charset=utf-8');include './inc/config.php';$page=$_GET['page'];$page1=$_GET['page']+=1;$page2=$_GET['page']-1;$pageurl=$host.'/?page='.$page1;$pageurl1=$host.'/?page='.$page2;$jjj=$_GET['listid'];$iu=base64_decode($jjj);$info=file_get_contents($iu);print_r($info);define('360','www.360kan.com');$yuming='http://www.360kan.com';$vname='#<span class="s1">(.*?)</span>#';$vlist='#<a class="js-tongjic" href="(.*?)">#';$vstar='# <p class="star">(.*?)</p>#';$vimg="#<div class=\"cover g-playicon\">\r\n                                <img src=\"(.*?)\">\r\n#";$bflist='#<a data-daochu(.*?) href="(.*?)" class="js-site-btn btn btn-play"></a>#';$array=array();preg_match_all($vname,$info,$namearr);preg_match_all($vlist,$info,$listarr);preg_match_all($vstar,$info,$stararr);preg_match_all($vimg,$info,$imgarr);foreach($namearr[1] as $key=>$value){$gul=$yuming.$listarr[1][$key];$cd=$host.'/alist.php?id='.$gul;$guq=$listarr[1][$key];$_GET['id']=$gul;$zimg=$imgarr[1][$key];$zname=$namearr[1][$key];$zstar=$stararr[1][$key];$jiami=base64_encode($gul);$chuandi=$host.'/inc/b.php?id='.$jiami;echo " <li class='item'>
    <a class='js-tongjic' href='$chuandi'>
    <div class='cover g-playicon'>
    <img src='$zimg'>
    <span class='pay'>超清</span>                                <span class='hint'>2016</span>                            </div>
    <div class='detail'>
    <p class='title g-clear'>
    <span class='s1'>$zname</span>
    <span class='s2'>4.9</span>
    </p>
    <p class='star'>$zstar</p>
    </div>
    </a>
    </li>  ";}echo "<div monitor-desc='分页' id='js-ew-page' data-block='js-ew-page'  class='ew-page'>
<a href='$pageurl' target='_self' class='btn'>下一页&gt;</a></div>";
