
<meta charset="UTF-8"/>
<?php
$_CONFIG['DBNAME']='classpub';
require_once('AQFrameWork/AQFrameWork.php');


$m=new Model('discus');
$args['cid']=1;
$args['page']=1;
$args['countPerPage']=10;
$r=$m->topicCount($args);

var_dump($r);

?>