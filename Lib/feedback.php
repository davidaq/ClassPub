<?php
isset($_CONFIG)||die();

function add(&$V){
	if(isset($_POST['text'])){
		$m=new Model('feedback');
		$m->feedback(array('text'=>$_POST['text'],'uid'=>U::uid()));
	}
	$V=new VIEW('notice');	
	$V->set('url',BASE_PATH.'?a=feedback');
	$V->set('message','留言成功');
}
?>
