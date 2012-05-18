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
	$r=$m->listTopics($arg);
	$V->set('topicList',$r);

	$dids=array();
	foreach($r as $f){
		$dids[]=$f['did'];
	}
	
	$replys=array();
	if($dids){
		unset($arg);
		$arg['dids']=$dids;
		$r=$m->replyCount($arg);
		$authors=array();
		foreach($r as $f){
			$replys[$f['reply']]['count']=$f['num'];
			$replys[$f['reply']]['last']=$f['last'];
			$authors[$f['last']]=1;
		}
		if($authors){
			$arg['dids']=array_keys($authors);
			$r=$m->authors($arg);
			foreach($r as $f){
				$authors[$f['did']]=$f['name'];
			}
			$V->set('replyer',$authors);
		}
	}
	$V->set('replys',$replys);
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

function view(&$V){
	if(!isset($_GET['did'])||!is_numeric($_GET['did'])){
		$V=new View('notice');
		$V->set('message','非法访问');
		$V->set('url',BASE_PATH);
	}
	$did=ceil($_GET['did']);
	$V->set('did',$did);
	$m=new Model('discus');
	$arg['did']=$did;
	$arg['countPerPage']=12;
	$arg['start']=(PAGE-1)*$arg['countPerPage'];
	$V->set('start',$arg['start']);
	$r=$m->fetchStory($arg);
	$V->set('threads',$r);
	
	$r=$m->replyCount(array('dids'=>array($did)));
	$V->set('replyCount',$r[0]['num']);
	$V->set('pager',pager($r[0]['num']+1,$arg['countPerPage'],PAGE));
}
function reply(&$V){
	if(!isset($_POST['text'])&&!isset($_POST['did'])||!is_numeric($_POST['did'])){
		die();
	}
	if(strlen(trim($_POST['text']))<10)
		die('回复至少10个字符');
	$m=new Model('discus');
	$arg['uid']=U::uid();
	$arg['did']=ceil($_POST['did']);
	$arg['text']=htmlspecialchars($_POST['text']);
	if($m->replyTopic($arg))
		die('ok');
	else
		die('数据库错误');
}
?>
