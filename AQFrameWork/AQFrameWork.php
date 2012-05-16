<?php
/*
This is a light weight MVC framework made by DavidAQ.
This framework requires the server end to be PHP+MYSQL.
*/
define('APP_PATH',getcwd());
$c=preg_replace_callback('/./',create_function('$m','return preg_quote($m[0],\'/\').\'?\';'),$_SERVER['REQUEST_URI']);
if(0==preg_match('/'.$c.'$/',strtr(APP_PATH,array('\\'=>'/')),$m))
	die('Invalid access');
if(substr($m[0],-1,1)!='/'){
	$m[0].='/';
}
define('BASE_PATH',$m[0]);
if(0==preg_match('/^(?:\w|\.)+/',substr($_SERVER['REQUEST_URI'],strlen(BASE_PATH)),$m))
	$m='index.php';
else
	$m=$m[0];
define('APP_GATE',$m);
require_once('DefaultConfig.inc.php');
require_once('connection.inc.php');
require_once('Model.class.php');
require_once('View.class.php');
require_once('SuperFunctions.inc.php');
?>
