<?php
isset($_CONFIG)||die();
class U{
	public static function uid(){
		return isset($_SESSION['uid'])?$_SESSION['uid']:0;
	}
};
?>
