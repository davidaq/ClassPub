<?php die(); ?>
附件模块

%getBasic
$atid
SELECT `name`,`isupload`,`url` FROM `{$TP}attachment`
	WHERE `atid`=$atid


%getAttachment		//获取帖子所有附件
$did
SELECT * FROM `{$TP}attachment`
	WHERE `did`=$did

%addAttachment		//添加一个附件记录
$did
$url
$name
$isupload
$size
INSERT INTO `{$TP}attachment`
	(`did`,`url`,`name`,`size`,`isupload`)values($did,$url,$name,$size,$isupload)

%removeAttachment	//删除附件记录
$uid
$atid
DELETE FROM `{$TP}attachment`
	WHERE `atid`=$atid AND `uid`=$uid
