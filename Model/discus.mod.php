<?php die();?>
交流模块，主要操作discus表

%topicCount		//主题个数
$cid
SELECT COUNT(*)
	FROM `{$TP}discus`
	WHERE `cid`=$cid


%listTopics		//列出主题
$cid
$start						//第几页,的第一个
$countPerPage			//每页显示个数
$type
SELECT `did`,`uid`,`cid`,`time`,`type`,`title`
	FROM `{$TP}discus`
	WHERE ($cid=0 OR `cid`=$cid) AND `reply`=0 AND `type` IN $type
	LIMIT $start	,$countPerPage

%replyCount		//获取多个主题回复个数
$dids	
		 //一个数组，用IN

%newTopic			//新主题
$uid
$cid
$title
$text
$type
INSERT INTO `{$TP}discus`
	(`uid`,`cid`,`title`,`content`,`type`)
	VALUES($uid,$cid,$title,$text,$type)

%replyTopic		//回复主题
$uid
$did
$cid
$text
INSERT INTO `{$TP}discus`
	(`uid`,`content`,`reply`,`cid`)
	VALUES($uid,$text,$did,$cid)


%editThread		//编辑帖子/主题
$uid
$did
$newText
UPDATE `{$TP}discus`
	SET `content`=$newText
	WHERE `uid`=$uid AND `did`=$did

%removeThread	//删除帖子/主题
$uid
$did
DELETE FROM `{$TP}discus`
	WHERE `uid`=$uid AND `did`=$did;
DELETE FROM `{$TP}discus`
	WHERE `reply`=$did;
