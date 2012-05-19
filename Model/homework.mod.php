<?php die();?>
记录作业是否已提交

%addHomeworkFor
$did
$uid
INSERT INTO `{$TP}homework`
	(`did`,`uid`)VALUES($did,$uid)
	
%doneHomework
$did
$uid
DELETE FROM `{$TP}homework`
	WHERE `did`=$did AND `uid`=$uid
	
%fetchDone
$did
SELECT `uid`,`name`
	FROM `{$TP}user` b
	WHERE `uid` IN (
		SELECT `uid` FROM `{$TP}s_c`
			WHERE `cid`=(SELECT `cid` FROM `{$TP}discus` WHERE `did`=$did)
			AND `uid` NOT IN(
				SELECT `uid` FROM `{$TP}homework` WHERE `did`=$did
			)
	)
