<?php
isset($_CONFIG)||die();
class U{
	public static function uid(){
		return isset($_SESSION['uid'])?$_SESSION['uid']:0;
	}
	public static function user(){
		return isset($_SESSION['username'])?$_SESSION['username']:'';
	}
};
?>
