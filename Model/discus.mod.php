<?php die();?>
交流模块，主要操作discus表

%topicCount		//主题个数
$cid
SELECT COUNT(*)
	FROM `{$TP}discus`
	WHERE `cid`=$cid


%listTopics		//列出主题
$cid
$page						//第几页
$countPerPage			//每页显示个数
SELECT `did`,`uid`,`cid`,`time`,`type`
	FROM `{$TP}discus`
	WHERE `cid`=$cid AND `reply`=0 
	LIMIT ($page-1)*$countPerPage,$countPerPage

%replyCount		//获取多个主题回复个数
$dids	
SELECT 	  		 //一个数组，用IN

%newTopic			//新主题
$uid
$cid
$title
$text
$isHomework

%replyTopic		//回复主题
$uid
$did
$text

%editThread		//编辑帖子/主题
$uid
$did
$newText

%removeThread	//删除帖子/主题
$uid
$did
