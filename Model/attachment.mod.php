<?php die(); ?>
附件模块

%getAttachment		//获取帖子所有附件
$did
SELECT * FROM `{$TP}attachment`
	WHERE `did`=$did

%addAttachment		//添加一个附件记录
$did
$url
$name
INSERT INTO `{$TP}attachment`
	(`did`,`url`,`name`)values($did,$url,$name)

%removeAttachment	//删除附件记录
$uid
$atid
DELETE FROM `{$TP}attachment`
	WHERE `atid`=$atid AND `uid`=$uid
