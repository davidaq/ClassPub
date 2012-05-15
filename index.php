<?php
session_start();
$_CONFIG['DBNAME']='classpub';
require_once('../3cBasketBall/AQFrameWork/AQFrameWork.php');

$dir=scandir('inc');
foreach($dir as $f){
	if(is_file('inc/'.$f))
		require_once('inc/'.$f);
}

if(U::uid()==0){
	if(isset($_GET['a'])&&$_GET['a']!='login')
		$_GET['a']='index';
}

lib_exec();
?>
