<?php die(); ?>
用户关系模块，获取复杂逻辑信息

%getClassMates		//获取用户的所有同学
$uid
SELECT cid
FROM `{$TP}S_C` X
WHERE `X.uid`!=$uid AND `X.cid`IN(SELECT cid
									FROM `{$TP}S_C` Y
									WHERE `Y.uid`=$uid)

%getTeachers			//获取用户所有的老师
$uid
SELECT teacher 
FROM `{$TP}class`
WHERE `cid` IN (SELECT cid
             FROM `{$TP}S_C`
			 WHERE `uid`=$uid)

%getStudents			//获取用户所有的学生
$uid
SELECT uid
FROM `{$TP}S_C`
WHERE `cid` IN (SELECT cid
			  FROM `{$TP}class`
			  WHERE `teacher`=$uid)
