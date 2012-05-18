<?php die();?>
交流模块，主要操作discus表

%getCid
$did
SELECT `cid` FROM `{$TP}discus`
	WHERE `did`=$did

%topicCount		//主题个数
$cid
SELECT COUNT(*) num
	FROM `{$TP}discus`
	WHERE `cid` IN $cid AND `reply`=0

%topicCountAll		//主题个数
$uid
SELECT COUNT(*)
	FROM `{$TP}discus`
	WHERE `cid` IN (
		SELECT `cid` FROM `{$TP}s_c`
			WHERE `uid`=$uid
	)


%listTopics		//列出主题
$cid
$start						//第几页,的第一个
$countPerPage			//每页显示个数
$type
SELECT d.`did`,u.`name`,d.`cid`,d.`time`,d.`type`,d.`title`
	FROM `{$TP}discus` d JOIN `{$TP}user` u
		ON u.uid=d.uid
	WHERE `cid` IN $cid AND `reply`=0 AND `type` IN $type
	ORDER BY `time` DESC
	LIMIT $start	,$countPerPage

%replyCount		//获取多个主题回复个数
$dids	
SELECT `reply`,COUNT(*) `num`,MAX(did) `last` FROM `{$TP}discus`
	WHERE `reply` IN $dids
	GROUP BY `reply`

%authors			//获取多个主题的作者
$dids
SELECT d.`did`,u.`name`
	FROM `{$TP}discus` d JOIN `{$TP}user` u
		ON d.`uid`=u.`uid`
	WHERE `did` IN $dids


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
$text
INSERT INTO `{$TP}discus`
	(`uid`,`content`,`reply`)
	VALUES($uid,$text,$did)


%editThread		//编辑帖子/主题
$uid
$did
$newText
UPDATE `{$TP}discus`
	SET `content`=$newText
	WHERE `uid`=$uid AND `did`=$did

%removeThread	//删除帖子/主题
$dids
DELETE FROM `{$TP}discus`
	WHERE `did` IN $dids;
DELETE FROM `{$TP}discus`
	WHERE `reply` IN $dids;
	
%liftThread		//提前主题
$dids
UPDATE `{$TP}discus`
	SET `time`=NOW()
	WHERE `did` IN $dids
	
%fetchStory		//获取一个楼
$did
$start
$countPerPage
SELECT d.*,u.`name` username
	FROM `{$TP}discus` d JOIN `{$TP}user` u
		ON d.`uid`=u.`uid`
	WHERE `did`=$did OR `reply`=$did
	ORDER BY `did` ASC
	LIMIT $start,$countPerPage
