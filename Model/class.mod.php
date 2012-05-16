<?php die(); ?>
课程模块，主要操作表：class,s_c

%getLearn		//获取用户所有 学 的课程
$uid 
SELECT name
	FROM `{$TP}class`
	WHERE `cid`IN
		(SELECT cid
			FROM`{$TP}S_C`
			WHERE `uid`=$uid)

%getTeach		//获取用户所有 教 的课程
$uid
SELECT name
FROM `{$TP}class`
WHERE `teacher`=$uid

%createClass		//创建课堂
$uid
$name
$description
INSERT  INTO class
	(`uid`,`name`,`description`)
	VALUES ($uid,$name,$description)

%removeClass		//解散课堂
$uid
$cid
DELETE  
FROM `{$TP}S_C`
	WHERE `cid`=$cid
		
DELETE
	FROM `{$TP}class`
	WHERE `cid`=$cid AND `teacher`=$uid

%joinClass		//加入课堂学习
$uid
$cid
INSERT  INTO S_C
	(`cid`,`uid`)
	VALUES ($cid,$uid)
	
%leaveClass		//离开课堂
$uid
$cid
DELETE 
FROM `{$TP}S_C`
WHERE `cid`=$cid AND `uid`=$uid
