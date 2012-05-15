<?php die();?>
交流模块，主要操作discus表

%topicCount		//主题个数
$cid

%listTopics		//列出主题
$cid
$page						//第几页
$countPerPage			//每页显示个数

%replyCount		//获取多个主题回复个数
$dids				//一个数组，用IN

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
