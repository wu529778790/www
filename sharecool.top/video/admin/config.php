<?php
session_start();
error_reporting(0);
include('../inc/aik.config.php'); 
define('SYSPATH',$aik['path']);
$rep='foot';
if($_SESSION['admin_aik']!==base64_decode('ZHkuZWI4OS5jb20=')){
	header("location: ./login.php");
	exit;
}
$nav='';
function md5ff($str=1){
	return md5($str.'eb89');
}
?>