<?php
isset($_CONFIG)||die();

function index(&$V){
	$m=new Model('class');
	if(CID==0){
		$V->set('cname','全部课程');
		$V->set('teacher',0);
		$cids=array();
		$arg['uid']=U::uid();
		$r=$m->getTeach($arg);
		foreach($r as $f){
			$cids[]=$f['cid'];
		}
		$r=$m->getLearn($arg);
		foreach($r as $f){
			$cids[]=$f['cid'];
		}
	}else{
		$r=$m->getBasic(array('cid'=>CID));
		$V->set('cname',$r[0]['name']);
		$V->set('teacher',$r[0]['teacher']);
		$cids=array(CID);
	}
		
		
	$m=new Model('discus');

	$r=$m->topicCount(array('cid'=>$cids,'uid'=>U::uid()));
	$totalCount=$r[0]['num'];
	$V->set('topicCount',$totalCount);

	$arg['cid']=$cids;
	$arg['countPerPage']=20;
	$arg['start']=(PAGE-1)*$arg['countPerPage'];
	if(isset($_GET['type'])){
		$arg['type']=array(htmlspecialchars($_GET['type']));
		$V->set('ttype',$arg['type'][0]);
	}else{
		$arg['type']=array('normal','homework','attachment');
		$V->set('ttype','all');
	}
	$r=$m->listTopics($arg);
	$V->set('topicList',$r);
	
	$V->set('pager',pager($totalCount,$arg['countPerPage'],PAGE));

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
		$did=mysql_insert_id();
		$V->set('url',BASE_PATH.'?a=discus&m=view&did='.$did);
		if($type=='homework'){
			$m=new Model('class');
			$r=$m->students(array('cid'=>$cid));
			$m=new Model('message');
			$arg['from']=U::uid();
			$url=BASE_PATH."?a=discus&m=view&cid={$cid}&did={$did}";
			$arg['text']=<<<MESG
有作业啦！！
点击<a href="{$url}" target="_blank">【这里】</a>查看作业详情。
MESG;
			if($r)
			foreach($r as $f){
				$arg['to']=$f['uid'];
				$m->sendMessage($arg);
			}
		}
		if($_SESSION['upl']){
			$m=new Model('attachment');
			$arg['did']=$did;
			foreach($_SESSION['upl'] as $f){
				$url=str_replace('/tmp/','/',$f['url']);
				rename($f['url'],$url);
				$arg['url']=$url;
				$arg['name']=$f['name'];
				$m->addAttachment($arg);
			}
			$_SESSION['upl']=array();
		}
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
	$arg['countPerPage']=10;
	$arg['start']=(PAGE-1)*$arg['countPerPage'];
	$V->set('start',$arg['start']);
	$r=$m->fetchStory($arg);
	if(!$r){
		$V=new View('notice');
		$V->set('url',BASE_PATH.'?a=discus&cid='.CID);
		$V->set('message','这个主题不存在或者已删除！');
		return;
	}
	$V->set('threads',$r);
	$cid=$m->getCid(array('did'=>$did));
	$cid=$cid[0]['cid'];
	
	$r=$m->replyCount(array('dids'=>array($did)));
	$rc=($r)?$r[0]['num']:0;
	$V->set('replyCount',$rc);
	$V->set('pager',pager($rc+1,$arg['countPerPage'],PAGE));
	
	$m=new Model('class');
	$r=$m->getBasic(array('cid'=>$cid));
	$V->set('teacher',$r[0]['teacher']);
	
	$m=new Model('attachment');
	$r=$m->getAttachment(array('did'=>$did));
	foreach($r as $k=>$f){
		$r[$k]['is_image']=$f['isupload']?is_image($f['url']):false;
	}
	$V->set('attachment',$r);
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
function del(&$V){
	if(isset($_POST['ids'])){
		$ids=explode(',',$_POST['ids']);
		$m=new Model('attachment');
		foreach($ids as $did){
			$r=$m->getAttachment(array('did'=>$did));
			foreach($r as $f){
				if($f['upload'])
					@unlink($f['url']);
			}
		}
		$m=new Model('discus');
		$m->removeThread(array('dids'=>$ids));
		die('ok');
	}
}
function lift(&$V){
	if(isset($_POST['ids'])){
		$ids=explode(',',$_POST['ids']);
		$m=new Model('discus');
		$m->liftThread(array('dids'=>$ids));
		die('ok');
	}
}
?>
