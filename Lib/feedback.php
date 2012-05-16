<?php
isset($_CONFIG)||die();

function add(&$V){
	if(isset($_POST['text'])){
		$m=new Model('feedback');
		$m->feedback(array('text'=>$_POST['text'],'uid'=>U::uid()));
		header(BASE_PATH.'?a=feedback');
		die();
	}
	$V=new VIEW('notice');	
}
?>
