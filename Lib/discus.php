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
		if(!$r){
			$V=new View('notice');
			$V->set('url',BASE_PATH);
			$V->set('message','这个课堂不存在');
			return;
		}
		$V->set('cname',$r[0]['name']);
		$V->set('teacher',$r[0]['teacher']);
		if(U::uid()!=$r[0]['teacher']){
			if(!val($m->have(array('uid'=>U::uid(),'cid'=>CID)))){
				$V=new View('notice');
				$V->set('url',BASE_PATH);
				$V->set('message','您不属于这个课堂，不能访问此页');
				return;
			}
		}
		$cids=array(CID);
	}
		
	if($cids){
		$m=new Model('discus');

		$totalCount=val($m->topicCount(array('cid'=>$cids,'uid'=>U::uid())));
	}
	if(isset($_GET['type'])){
		$arg['type']=array(htmlspecialchars($_GET['type']));
		$V->set('ttype',$arg['type'][0]);
	}else{
		$arg['type']=array('normal','homework','attachment');
		$V->set('ttype','all');
	}
	if(isset($totalCount)&&$totalCount){
		$V->set('topicCount',$totalCount);

		$arg['cid']=$cids;
		$arg['countPerPage']=20;
		$arg['start']=(PAGE-1)*$arg['countPerPage'];
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
	}else{
		$V->set('topicCount',0);
		$V->set('topicList',array());
		$V->set('pager',pager(1,1,1));
	}
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
	$arg['commitol']=isset($_POST['commitol'])&&$_POST['commitol']?1:0;
	if($m->newTopic($arg)){
		$V->set('message','主题发表成功！');
		$did=mysql_insert_id();
		$V->set('url',BASE_PATH.'?a=discus&m=view&cid='.$cid.'&did='.$did);
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
			if($r){
				$hm=new Model('homework');
				$harg['did']=$did;
				foreach($r as $f){
					$arg['to']=$f['uid'];
					$m->sendMessage($arg);
					$harg['uid']=$f['uid'];
					$hm->addHomeworkFor($harg);
				}
			}
		}
		if($_SESSION['upl']){
			$m=new Model('attachment');
			$arg['did']=$did;
			foreach($_SESSION['upl'] as $f){
				if($f['upload']){
					$url=str_replace('/tmp/','/',$f['url']);
					rename($f['url'],$url);
				}else
					$url=$f['url'];
				$arg['url']=$url;
				$arg['name']=$f['name'];
				$arg['isupload']=$f['upload'];
				$arg['size']=$f['size'];
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
	if($r[0]['type']=='homework'&&$r[0]['commitol']){
		$hm=new Model('homework');
		$V->set('doneHomework',$hm->fetchDone(array('did'=>$r[0]['did'])));
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
	
	
	if(U::uid()!=$r[0]['teacher']){
		if(!val($m->have(array('uid'=>U::uid(),'cid'=>CID)))){
			$V=new View('notice');
			$V->set('url',BASE_PATH);
			$V->set('message','您不属于这个课堂，不能访问此页');
			return;
		}
	}
	
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
	$arg['did']=ceil($_POST['did']);
	$arg['cid']=val($m->getCid($arg));
	$arg['uid']=U::uid();
	$arg['text']=htmlspecialchars($_POST['text']);
	$arg['title']='回复：'.val($m->topicName(array('did'=>$arg['did'])));
	ob_clean();
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
				if($f['isupload'])
					@unlink($f['url']);
			}
		}
		$m=new Model('discus');
		$m->removeThread(array('dids'=>$ids));
		
		$dir=scandir('uploads/homework');
		foreach($dir as $f){
			$p=strpos($f,'_');
			if($p!==false){
				$e=strpos($f,'.');
				if($e!==false)
					$t=substr($f,$p+1,$e-$p-1);
				else
					$t=substr($f,$p+1);
				if(in_array($t,$ids)){
					@unlink('uploads/homework/'.$f);
				}
			}
		}
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
function homework(&$V){
	if(isset($_FILES['file'])&&isset($_POST['did'])){
		$V=new View('notice');
		$V->set('url',$_SERVER['HTTP_REFERER']);
		$V->set('message','作业提交失败');
		$name=$_FILES['file']['name'];
		if($name&&$_FILES['file']['tmp_name']){
			$ext=explode('.',$name);
			$ext=(($t=count($ext))>1)?'.'.$ext[$t-1]:'';
			$did=ceil($_POST['did']);
			$url='uploads/homework/'.U::uid().'_'.$did.$ext;
			if(move_uploaded_file($_FILES['file']['tmp_name'],$url)){
				$m=new Model('homework');
				$m->doneHomework(array('uid'=>U::uid(),'did'=>$did));
				$V->set('message','作业提交成功');
			}
		}
	}
}

function homeworkGet(&$V){
	if(isset($_GET['did'])&&isset($_GET['uid'])){
		$did=ceil($_GET['did']);
		$uid=ceil($_GET['uid']);
		$m=new Model('discus');
		$cid=val($m->getCid(array('did'=>$did)));
		$fname=val($m->topicName(array('did'=>$did)));
		$m=new Model('class');
		$class=val($m->getBasic(array('cid'=>$cid)));
		if($uid!=U::uid()&&U::uid()!=$class['teacher']){
			$V=new View('notice');
			$V->set('message','您无权下载这个文件');
			return;
		}
		$fname.='_'.$class['name'];
		
		$dir=scandir('uploads/homework');
		$file=$uid.'_'.$did;
		$len=strlen($file);
		foreach($dir as $f){
			if(substr($f,0,$len)==$file){
				$m=new Model('user');
				$fname=val($m->getName(array('uid'=>$uid))).'_'.$fname;
				$fname.=substr($f,$len);
				header('Content-Description: File Transfer');
				header('Content-Type:Application/Unknow');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length:'.filesize('uploads/homework/'.$f));
				header('Content-Disposition: attachment; filename="'.$fname.'"');
				ob_clean();
				flush();
				readfile('uploads/homework/'.$f);
			}
		}
		$V=new View('notice');
		$V->set('message','没有找到相应的文件');
	}
}
?>
