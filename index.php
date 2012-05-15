<?php
session_start();
$_CONFIG['DBNAME']='classpub';
require_once('../3cBasketBall/AQFrameWork/AQFrameWork.php');

$dir=scandir('inc');
foreach($dir as $f){
	if(is_file('inc/'.$f))
		require_once('inc/'.$f);
}

lib_exec();
?>
