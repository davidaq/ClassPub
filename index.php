<?php
ob_start();
session_start();
$_CONFIG['DBNAME']='classpub';
require_once('AQFrameWork/AQFrameWork.php');

$dir=scandir('inc');
foreach($dir as $f){
	if(is_file('inc/'.$f))
		require_once('inc/'.$f);
}

if(U::uid()==0){
	if(isset($_GET['a'])&&$_GET['a']!='login'){
		$_GET['a']='index';
		unset($_GET['m']);
	}
}else if(isset($_SESSION['defaultPage'])&&$_SESSION['defaultPage']){
	$_GET['a']=$_SESSION['defaultPage'];
	unset($_GET['m']);
	unset($_SESSION['defaultPage']);
}

lib_exec();
?>
