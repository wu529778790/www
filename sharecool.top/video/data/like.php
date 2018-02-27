<?php
$fang=$_GET['play'];
$jmfang=base64_decode($fang);
$like=file_get_contents($jmfang);
$likeimg="#   <li  title='(.*?)' class='w-newfigure w-newfigure-180x287'><a href='(.*?)'  class='js-link'><div class='w-newfigure-imglink g-playicon js-playicon'> <img src='(.*?)' data-src='(.*?)' alt='(.*?)'  />#";
preg_match_all($likezz, $like,$likearr);
preg_match_all($likeimg, $like,$likearr1);
$likename=$likearr1[1];
$likeurl=$likearr1[2];
$likeimg=$likearr1[4];
//print_r($likearr1);
foreach ($likename as $ks=>$vs){
    $host="http://www.360kan.com";
    $host1=$host.$likeurl[$ks];
    $hjiami=base64_encode($host1);
    echo "<li title='$vs'  class='w-newfigure w-newfigure-180x287'>
    <a class='js-link' href='./play.php?play=$hjiami' title='$vs' target='_blank'>
    <div class='w-newfigure-imglink g-playicon js-playicon'>
    <img src='$likeimg[$ks]' alt='$vs'/></div>
	 <div class='w-newfigure-detail'><p class='title g-clear'><span class='s1'>$vs</span></p></div></a></li>  ";
}

