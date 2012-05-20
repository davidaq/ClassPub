<?php
isset($_CONFIG)||die();
function login(&$V){
	if(isset($_POST['username'])&&isset($_POST['password'])){
		$m=new Model('user');
		$r=$m->getByEmail(array('email'=>$_POST['username']));
		if(!$r)
			die('badusername');
		if(md5($_POST['password'])!=$r[0]['password'])
			die('badpassword');
		$_SESSION['uid']=$r[0]['uid'];
		$_SESSION['username']=$r[0]['name'];
		$_SESSION['defaultPage']=val($m->getDefaultPage($r[0]));
		die('ok');
	}
}

function logout(&$V){
	unset($_SESSION['uid']);
	header('location:'.BASE_PATH);
}

function register(&$V){
	if(isset($_POST['username'])&&isset($_POST['password'])){
		$m=new Model('user');
		$arg['name']='新注册用户';
		$arg['email']=$_POST['username'];
		$arg['password']=md5($_POST['password']);
		$r=$m->createUser($arg);
		ob_clean();
		if(!$r)
			die('badusername');
		$_SESSION['uid']=mysql_insert_id();
		$_SESSION['username']=$arg['name'];
		$_SESSION['defaultPage']='settings';
		die('ok');
	}
}
?>
