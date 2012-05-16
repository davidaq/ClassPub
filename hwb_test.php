<?php
$_CONFIG['DBNAME']='classpub';
require_once('AQFrameWork/AQFrameWork.php');


$m=new Model('user');
$args['email']='123456@qq.com';
$args['password']='fuck';
$m->createUser($args);

?>