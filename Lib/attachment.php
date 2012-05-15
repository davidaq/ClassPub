<?php
isset($_CONFIG)||die();

if(!isset($_SESSION['upl'])){
	$_SESSION['upl']=array();
}
$dir=scandir('uploads/tmp');
$t=time()-3600;
foreach($dir as $f){
	$f='uploads/tmp/'.$f;
	if(is_dir($f))
		continue;
	if(filemtime($f)<$t){
		unlink($f);
	}
}

function upload(&$V){
	if(!isset($_POST['type']))
		return;
	$attId='atch'.time().rand(100,999);
	if(isset($_FILES['upload'])&&$_POST['type']=='upload'){
		$name=$_FILES['upload']['name'];
		$ext=explode('.',$name);
		$ext=(($t=count($ext))>1)?'.'.$ext[$t-1]:'';
		$url='uploads/tmp/'.U::uid().'_'.$attId.$ext;
		move_uploaded_file($_FILES['upload']['tmp_name'],$url);
		$size=filesize($url);
		$upload=true;
	}else if(isset($_POST['url'])&&$_POST['type']=='linkto'){
		$url=preg_replace('/^javascript/i','',$_POST['url']);
		$name=$_POST['url'];
		$name=preg_split('/[\/\\\\]/',$name);
		$name=$name[count($name)-1];
		$name=htmlspecialchars($name);
		$size=0;
		$upload=false;
	}
	$res=array(
		'name'=>$name,
		'url'=>$url,
		'size'=>$size,
		'upload'=>$upload,
		'id'=>$attId
	);
	$_SESSION['upl'][$attId]=$res;
	die('<body>'.json_encode($res).'</body>');
}

function view(&$V){
	foreach($_SESSION['upl'] as $k=>$f){
		if($f['upload'] and !file_exists($f['url'])){
			unset($_SESSION['upl'][$k]);
		}
	}
	echo json_encode($_SESSION['upl']);
	die();
}

function rm(&$V){
	if(!isset($_POST['id']))
		return;
	if(isset($_SESSION['upl'][$_POST['id']])){
		if($_SESSION['upl'][$_POST['id']]['upload']){
			unlink($_SESSION['upl'][$_POST['id']]['url']);
		}
		unset($_SESSION['upl'][$_POST['id']]);
	}
}
?>
