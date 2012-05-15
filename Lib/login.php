<?php
function login(&$V){
	if(isset($_POST['username'])&&isset($_POST['password'])){
		$m=new Model('user');
		$r=$m->call('getByEmail',array('email'=>$_POST['username']));
		if(!$r)
			die('badusername');
		if(md5($_POST['password'])!=$r[0]['password'])
			die('badpassword');
		$_SESSION['uid']=$r[0]['uid'];
		$_SESSION['username']=$r[0]['name'];
		die('ok');
	}
}

function logout(&$V){
	unset($_SESSION['uid']);
	header('location:'.BASE_PATH);
}
?>
