
<meta charset="UTF-8"/>
<?php
$_CONFIG['DBNAME']='classpub';
require_once('AQFrameWork/AQFrameWork.php');


$m=new Model('discus');
$args['uid']=2;
$args['did']=8;
$r=$m->removeThread($args);

//var_dump($r);

?>