<meta charset="UTF-8"/>
<?php
$_CONFIG['DBNAME']='classpub';
require_once('AQFrameWork/AQFrameWork.php');


$m=new Model('message');
$args['uid']=2;
$r=$m->fetchMessage($args);
echo $m->getLastSql();
var_dump($r);
?>