<?php die(); ?>
消息模块

%getUnreadCount
$uid
SELECT COUNT(*) num
	FROM `{$TP}message`
	WHERE `to`=$uid AND `read`=0
	
%readAll
$uid
UPDATE `{$TP}message`
	SET `read`=1
	WHERE `to`=$uid

%fetchMessage		//获取消息
$uid
SELECT a.*,b.name
	FROM `{$TP}message` a JOIN `{$TP}user` b
		ON a.from=b.uid
	WHERE `to`=$uid
	ORDER BY `time` DESC

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
WHERE `to`=$uid AND `msid`=$msid
