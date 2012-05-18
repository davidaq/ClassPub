<?php
function index(&$V){
	$m=new Model('message');
	$arguid['uid']=U::uid();
	$r=$m->fetchMessage($arguid);
	foreach($r as $k=>$f){
		$t=time()-strtotime($f['time']);
		if($t<60){
			$r[$k]['time']='刚刚';
		}else if($t<3600)
		{
			$r[$k]['time']=ceil($t/60).'分钟前';
		}else if($t<3600*48){
			$r[$k]['time']=ceil($t/3600).'小时前';
		}
	}
	$V->set('messages',$r);

	$m=new Model('relation');
	$r=$m->getClassMates($arguid);
	$V->set('classMates',$r);
	$r=$m->getTeachers($arguid);
	$V->set('teachers',$r);
	$r=$m->getStudents($arguid);
	$V->set('students',$r);
}
function send(&$V){
	$V=new View('notice');
	$V->set('url',BASE_PATH.'?a=message');
	if(isset($_SESSION['message_time'])&&$_SESSION['message_time']>time()){
		$V->set('message','说这么快，喝口水吧！');
		$_SESSION['message_time']=time()+$_SESSION['message_time_incre']*2;
		return;
	}
	if(isset($_SESSION['message_time_incre'])){
		if(isset($_SESSION['message_time'])){
			$t=$_SESSION['message_time']-time();
			if(($t*2)<$_SESSION['message_time_incre'])
				$_SESSION['message_time_incre']/=$t/20;
		}
		$_SESSION['message_time_incre']+=3;
	}else
		$_SESSION['message_time_incre']=0;
	$_SESSION['message_time']=time()+$_SESSION['message_time_incre'];
	if(isset($_POST['text'])&&isset($_POST['uid'])&&is_numeric($_POST['uid'])){
		$m=new Model('message');
		$arg['from']=U::uid();
		$arg['to']=ceil($_POST['uid']);
		$arg['text']=htmlspecialchars($_POST['text']);
		if($m->sendMessage($arg)){
			$V->set('message','消息已发出');
			return;
		}
	}
	$V->set('message','发送失败');
}
function delete(&$V){
	if(isset($_GET['msid'])){
		$m=new Model('message');
		$arg['msid']=ceil($_GET['msid']);
		$arg['uid']=U::uid();
		$m->deleteMessage($arg);
		die();
	}
}
?>
