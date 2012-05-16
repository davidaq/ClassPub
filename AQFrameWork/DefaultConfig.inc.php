<?php
/*
Setup the default configuration values
*/
if(!isset($_CONFIG))
{
	$_CONFIG=array();
}
function __setCFG($key,$val)
{
	global $_CONFIG;
	if(!isset($_CONFIG[$key]))
		$_CONFIG[$key]=$val;
}
__setCFG('CHARSET','utf8');
__setCFG('DBHOST','localhost');
__setCFG('DBNAME','test');
__setCFG('DBUSER','root');
__setCFG('TBLPRE','');
__setCFG('DBPASS','');
__setCFG('APP_PATH',APP_PATH);
__setCFG('DEBUG',true);
__setCFG('HIDE_NOTICE',false);
?>
