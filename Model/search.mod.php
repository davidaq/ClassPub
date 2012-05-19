<?php die()?>
搜索模块

%searchClass
$key
SELECT * FROM `{$TP}class`
	WHERE `name` rlike $key
		OR `description` rlike $key
	ORDER BY `cid` DESC
	LIMIT 10
		
%searchPosts
$key
$cid
SELECT d.`time`,d.`title`,left(d.`content`,100) `text`,d.`did`,d.`cid`,d.`did`,u.`name` user
	FROM `{$TP}discus` d JOIN `{$TP}user` u
		ON d.`uid`=u.`uid`
	WHERE `cid` IN $cid AND
		(d.`title` rlike $key
		OR d.`content` rlike $key)
	ORDER BY d.`time`
