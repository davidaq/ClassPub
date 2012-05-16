<?php
function setPref(&$V){
	if(isset($_POST['logGo'])&&isset($_POST['class'])){
		$m=new Model('user');
		$arg['uid']=U::uid();
		$arg['defaultPage']=$_POST['logGo'];
		$arg['defaultClass']=$_POST['class'];
		$m->setOptions($arg);

		$V=new View('notice');
		$V->set('url',BASE_PATH.'?a=settings');
		$V->set('message','设置成功');
	}
}

function setDetails(&$V){
	if(isset($_POST['name'])&&isset($_POST['qq'])&&isset($_POST['phone'])){
		$m=new Model('user');
		$arg['uid']=U::uid();
		$arg['name']=$_POST['name'];
		$arg['qq']=$_POST['qq'];
		$arg['phone']=$_POST['phone'];
		$m->setOptions($arg);
	}
}

function setPassword(&$V){
	if(isset($_POST['oldpassword'])&&isset($_POST['newpassword'])&&isset($_POST['newpassword2'])){
		
		$m=new Model('user');
		$arg['uid']=U::uid();
		$arg['name']=$_POST['name'];
		$arg['qq']=$_POST['qq'];
		$arg['phone']=$_POST['phone'];
		$m->setOptions($arg);
	}
}
?>
