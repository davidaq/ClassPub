<?php
isset($_CONFIG)||die();

function index(&$V){
	if(CID==0)
		$V->set('cname','全部课程');
	else{
		$m=new Model('class');
		$r=$m->getName(array('cid'=>CID));
		$V->set('cname',$r[0]['name']);
	}
	$m=new Model('discus');
	$arg['cid']=CID;
	$arg['countPerPage']=20;
	$arg['start']=(PAGE-1)*$arg['countPerPage'];
	$arg['type']=array('normal','homework','attachment');
	$V->set('topicList',$m->listTopics($arg));
}
function post(&$V){
	$topic=htmlspecialchars(trim($_POST['topic']));
	$text=htmlspecialchars(trim($_POST['text']));
	$cid=ceil($_POST['cid']);
	$type=$_POST['type'];
	$V=new View('notice');
	$V->set('url',$_SERVER['HTTP_REFERER']);
	if(!isset($topic{5})){
		$V->set('message','标题至少6个字符');
		return;
	}
	if(!isset($text{10})){
		$V->set('message','内容至少10个字符');
		return;
	}
	if($type!='homework'){
		if($_SESSION['upl']){
			$type='attachment';
		}else
			$type='normal';
	}
	$m=new Model('discus');
	$arg['uid']=U::uid();
	$arg['cid']=$cid;
	$arg['title']=$topic;
	$arg['text']=$text;
	$arg['type']=$type;
	if($m->newTopic($arg)){
		$V->set('message','主题发表成功！');
	}else{
		$V->set('message','主题发表失败');
	}
}
?>
