<?php
function addClass(&$V){
	if(isset($_POST['name'])&&isset($_POST['description'])){
		$V=new View('notice');
		$V->set('url',BASE_PATH);
		if(!isset($_POST['name']{4})&&!isset($_POST['description']{8})){
			$V->set('message','课堂名称或介绍太短');
			return;
		}
		$m=new Model('class');
		$c=val($m->countTeach(array('uid'=>U::uid())));
		if($c>=3){
			$V->set('message','您已经拥有3个课堂，不能再添加了');
			return;
		}else{
			$arg['uid']=U::uid();
			$arg['name']=htmlspecialchars($_POST['name']);
			$arg['description']=htmlspecialchars($_POST['description']);
			$m->createClass($arg);
			$V->set('message','添加完成');			
		}
	}
}
function removeClass(&$V){
	if(isset($_GET['cid'])){
		$V=new View('notice');
		$V->set('url',$_SERVER['HTTP_REFERER']);
		$m=new Model('class');
		$arg['cid']=ceil($_GET['cid']);
		$arg['uid']=U::uid();
		$m->removeClass($arg);
		$m->leaveClass($arg);
		$V->set('message','课程已从列表删除');
	}	
}

function search(&$V){
	if(isset($_GET['keyword'])){
		$key=trim($_GET['keyword']);
		if(!$key){
			$V=new View('notice');
			$V->set('url',$_SERVER['HTTP_REFERER']);
			$V->set('message','搜索内容不得为空');
			return;
		}
		$key=preg_split('/\s+/',preg_quote(htmlspecialchars($key),'/'));
		$key=implode('|',$key);
		$V->set('pkey',$key);
		if(is_numeric($key)){
			$key="[^0-9]({$key})[^0-9]|[^0-9]({$key})$|^({$key})[^0-9]";
		}
		$m=new Model('class');
		$classes=array();
		foreach($m->getLearn(array('uid'=>U::uid())) as $f){
			$classes[]=$f['cid'];
		}
		foreach($m->getTeach(array('uid'=>U::uid())) as $f){
			$classes[]=$f['cid'];
		}
		$m=new Model('search');
		$arg['key']=$key;
		$V->set('classes',$m->searchClass($arg));
		$arg['cid']=$classes;
		$V->set('posts',$m->searchPosts($arg));
	}
}
function apply(&$V){
	if(isset($_GET['cid'])){
		$V=new View('notice');
		$V->set('url',$_SERVER['HTTP_REFERER']);
		$cid=ceil($_GET['cid']);
		$m=new Model('class');
		$r=val($m->getBasic(array('cid'=>$cid)));
		$arg=array('uid'=>U::uid(),'cid'=>$cid);
		if($r['teacher']==U::uid()){
			$V->set('message','您是此课程的老师了，不能成为自己的学生哦');
			return;
		}elseif(val($m->have($arg))){
			$V->set('message','您已经是此课程的学员了');
			return;
		}else if(val($m->haveOffer(array('uid'=>U::uid(),'cid'=>$cid,'fromclass'=>1))))
		{
			$m->deleteOffer($arg);
			$m->joinClass($arg);
			$V->set('message','您加入了 '.$r['name'].' 课程，成为其中一名学员了');
		}else{
			$arg['fromclass']=0;
			if($m->offer($arg)){
				$m=new Model('message');
				$msg['from']=U::uid();
				$msg['to']=$r['teacher'];
				$url=BASE_PATH.'?a=other&m=offer&uid='.U::uid().'&cid='.$cid;
				$msg['text']=<<<MSG
我对您的课程 {$r['name']} 很感兴趣，想要加入成为一名学员。
点击<a href="{$url}">【这里】</a>表示同意。
MSG;
				$m->sendMessage($msg);
			}
			$V->set('message','您的申请已发出，请等待老师同意');
			ob_clean();
		}
		
	}
}
function offer(&$V){
	if(isset($_GET['cid'])&&isset($_GET['uid'])){
		$V=new View('notice');
		$V->set('url',$_SERVER['HTTP_REFERER']);
		$cid=ceil($_GET['cid']);
		$uid=ceil($_GET['uid']);
		$m=new Model('class');
		$r=val($m->getBasic(array('cid'=>$cid)));
		
		$arg=array('uid'=>$uid,'cid'=>$cid);
		if(U::uid()!=$r['teacher']){
			$V->set('message','非法操作');		
			return;
		}elseif($r['teacher']==$uid){
			$V->set('message','您是此课程的老师了，不能成为自己的学生哦');
			return;
		}elseif(val($m->have($arg))){
			$V->set('message','对方已经是此课程的学员了');
			return;
		}elseif(val($m->haveOffer(array('uid'=>$uid,'cid'=>$cid,'fromclass'=>0))))
		{
			$m->deleteOffer($arg);
			$m->joinClass($arg);
			$V->set('message','您已允许一名学员加入 '.$r['name'].' 课程');
			return;
		}else{
			$arg['fromclass']=1;
			if($m->offer($arg)){
				$m=new Model('message');
				$msg['from']=U::uid();
				$msg['to']=$uid;
				$url=BASE_PATH.'?a=other&m=apply&cid='.$cid;
				$msg['text']=<<<MSG
课程 {$r['name']} 邀请您加入。
点击<a href="{$url}">【这里】</a>表示同意。
MSG;
				$m->sendMessage($msg);
			}
			$V->set('message','您的邀请已发出，请等对方同意');
			ob_clean();
			return;
		}
	}
}
?>
