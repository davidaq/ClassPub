<?php die(); ?>
课程模块，主要操作表：class,s_c

%getBasic
$cid
SELECT `name`,`teacher` FROM `{$TP}class`
	WHERE `cid`=$cid

%getLearn		//获取用户所有 学 的课程
$uid 
SELECT cid,name
	FROM `{$TP}class`
	WHERE `cid` IN
		(SELECT cid
			FROM`{$TP}s_c`
			WHERE `uid`=$uid)

%have
$cid
$uid
SELECT COUNT(*) FROM `{$TP}s_c`
	WHERE `cid`=$cid AND `uid`=$uid

%countTeach
$uid
SELECT COUNT(*) as num
	FROM `{$TP}class`
	WHERE `teacher`=$uid

%getTeach		//获取用户所有 教 的课程
$uid
SELECT cid,name
	FROM `{$TP}class`
	WHERE `teacher`=$uid

%createClass		//创建课堂
$uid
$name
$description
INSERT  INTO class
	(`teacher`,`name`,`description`)
	VALUES ($uid,$name,$description)

%removeClass		//解散课堂
$uid
$cid
DELETE FROM `{$TP}class`
	WHERE `cid`=$cid AND `teacher`=$uid

%students			//学生名单
$cid
SELECT a.`name`,a.`uid`
	FROM `{$TP}user` a JOIN `{$TP}s_c` b
		ON a.`uid`=b.`uid`
	WHERE `cid`=$cid

%joinClass		//加入课堂学习
$uid
$cid
INSERT  INTO s_c
	(`cid`,`uid`)
	VALUES ($cid,$uid)

%leaveClass		//离开课堂
$uid
$cid
DELETE FROM `{$TP}s_c`
	WHERE `cid`=$cid AND `uid`=$uid
	
%offer
$uid
$cid
$fromclass
INSERT INTO `{$TP}offer`
	(`uid`,`cid`,`fromclass`)VALUES($uid,$cid,$fromclass)

%haveOffer
$uid
$cid
$fromclass
SELECT COUNT(*)
	FROM `{$TP}offer`
	WHERE `uid`=$uid AND `cid`=$cid AND `fromclass`=$fromclass

%deleteOffer
$uid
$cid
DELETE FROM `{$TP}offer`
	WHERE `uid`=$uid AND `cid`=$cid

%refreshOffer
DELETE FROM `{$TP}offer`
	WHERE `time`<NOW()-172800
