<?php die(); ?>
消息模块

%fetchMessage		//获取消息
$uid
SELECT * 
	FROM `{$TP}message`
	WHERE `to`=$uid

%sendMessage			//发送消息
$to
$from
$text
INSERT  INTO message(`to`,`from`,`text`)
VALUES ($to,$from,$text)

%deleteMessage		//删除消息
$uid
$msid
DELETE 
FROM `{$TP}message` 
WHERE `uid`=$uid AND `msid`=$msid
