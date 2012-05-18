<?php die(); ?>
用户关系模块，获取复杂逻辑信息

%getClassMates		//获取用户的所有同学
$uid
SELECT `name`,`uid` FROM `{$TP}user`
WHERE `uid` IN(
	SELECT `X`.`uid`
		FROM `{$TP}s_c` X
		WHERE `X`.`uid`!=$uid AND `X`.`cid` IN (SELECT cid
			FROM `{$TP}s_c` Y
			WHERE `Y`.`uid`=$uid)
)

%getTeachers			//获取用户所有的老师
$uid
SELECT `name`,`uid` FROM `{$TP}user`
WHERE `uid` IN(
	SELECT teacher
		FROM `{$TP}class`
		WHERE `cid` IN (SELECT cid
			FROM `{$TP}s_c`
			WHERE `uid`=$uid)
)

%getStudents			//获取用户所有的学生
$uid

SELECT `name`,`uid` FROM `{$TP}user`
WHERE `uid` IN(
	SELECT uid
		FROM `{$TP}s_c`
		WHERE `cid` IN (SELECT cid
			FROM `{$TP}class`
			WHERE `teacher`=$uid)
)
