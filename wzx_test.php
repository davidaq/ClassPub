<meta charset="UTF-8"/>
<?php
$_CONFIG['DBNAME']='classpub';
require_once('AQFrameWork/AQFrameWork.php');


$m=new Model('relation');
$args['uid']=3;
$r=$m->getStudents($args);
echo $m->getLastSql();
var_dump($r);
?>