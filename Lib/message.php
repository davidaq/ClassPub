<?php
function index(&$V){
	$m=new Model('message');
	$arguid['uid']=U::uid();
	$r=$m->fetchMessage($arguid);
	$V->set('messages',$r);
	$m=new Model('relation');
	$r=$m->getClassMates($arguid);
	$V->set('classMates',$r);
	$r=$m->getTeachers($arguid);
	$V->set('teachers',$r);
	$r=$m->getStudents($arguid);
	$V->set('students',$r);
}
?>
