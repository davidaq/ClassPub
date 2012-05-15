<?php
function dodef($g,$d=''){
	if(isset($_GET[$g])){
		define(strtoupper($g),strtolower($_GET[$g]));
	}else{
		define(strtoupper($g),$d);
	}
}
dodef('page',1);
dodef('cls',0);
dodef('keyword','');
?>
