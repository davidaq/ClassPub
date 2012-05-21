<?php
isset($_CONFIG)||die();

function index(&$V){
	$m=new Model('user');
	$r=$m->getSettings(array('uid'=>U::uid()));
	$V->set('cfg',$r[0]);
}

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
		
		$m->setDetails($arg);

		$V=new View('notice');
		$V->set('url',BASE_PATH.'?a=settings');
		$_SESSION['username']=$_POST['name'];
		$V->set('message','设置成功');
	}
}

function setPassword(&$V){
	if(isset($_POST['oldpassword'])&&isset($_POST['newpassword'])&&isset($_POST['newpassword2'])){

		$V=new View('notice');
		$V->set('url',BASE_PATH.'?a=settings');
		
		if($_POST['newpassword']!=$_POST['newpassword2'])
		{
			$V->set('message','密码重复不正确');
		}else{
			$m=new Model('user');
			$arg['uid']=U::uid();
			$arg['oldpassword']=md5($_POST['oldpassword']);
			$arg['newpassword']=md5($_POST['newpassword']);
			if($m->setPassword($arg)){
				$V->set('message','密码设置成功');
			}else{
				$V->set('message','密码设置失败');			
			}
		}
	}
}
?>
