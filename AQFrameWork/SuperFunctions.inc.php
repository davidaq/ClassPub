<?php
function __error($msg,$level=0){
	global $_CONFIG;
	if($level==0&&$_CONFIG['HIDE_NOTICE'])
	{
		return;
	}
	$head=array('Notice','Warning','Error');
	if($level>2)
		$level=2;
	if($_CONFIG['DEBUG'])
		echo '<p><b>'.$head[$level].':</b> '.$msg.'</p>';
	if($level>=2)
		die();
}
function M($path){	//Initialize a model
	return new Model($path);
}
function V($path){	//Initialize a view
	return new View($path);
}
function lib_exec($action=false,$method=false){
	global $_CONFIG;
	if($action===false)
	{
		$action=isset($_GET['a'])?$_GET['a']:'index';
	}
	$p=$_CONFIG['APP_PATH'].'/Lib/'.$action.'.php';
	$m=true;
	if($method===false)
		$method=isset($_GET['m'])?$_GET['m']:false;
	define('APP_ACTION',$action);
	define('APP_METHOD',$method);
	$V=NULL;
	if(View::exists($Vp=$action.(($method)?'/'.$method:''))){
		$V=new View($Vp);
		$m=false;
	}else if($method===false&&View::exists($Vp=$action.'/index'))
	{
		$V=new View($Vp);
		$m=false;
	}
	if(file_exists($p)){
		function _lets_gogogo($p,$__resvm,&$V){
			global $_CONFIG;
			include($p);
			if($__resvm!==false)
			{
				$__resvm=preg_replace('/[^a-zA-Z0-9]/','',$__resvm);
				if(function_exists($__resvm))
					eval($__resvm.'($V);');
			}else if(function_exists('index')){
				eval('index($V);');
			}
		}
		_lets_gogogo($p,$method,$V);
		$m=false;
	}
	if($V)
		$V->show();
	if($m)
		__error('Action doesn\'t exist '.$action);
}
?>
