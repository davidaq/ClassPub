<?php die();?>
用户模块，用于对用户信息增删改查

%getByEmail		//根据用户邮箱获得用户基本信息
$email
SELECT `uid`,`name`,`password` FROM `{$TP}user`
	WHERE `email`=$email
	
%setPassword		//设置密码
$uid
$oldpassword
$newpassword
UPDATE `{$TP}user`
SET `password`=$newpassword
WHERE `uid`=$uid AND `password`=$oldpassword

%createUser		//创建用户
$email
$password
INSERT  INTO user
	(`email`,`password`)
	VALUES ($email,$password)

%setOptions		//偏好设置
$uid
$defaultPage
$defaultClass
UPDATE `{$TP}profile`
	SET `defaultclass`=$defaultClass,`defaultpage`=$defaultPage
	WHERE `uid`=$uid 

%setDetails		//个人资料设置
$uid
$name
$qq
$phone
UPDATE `{$TP}profile`
	SET `qq`=$qq,`phone`=$phone
	WHERE `uid`=$uid ;
	UPDATE `{$TP}user`
	SET `name`=$name
	WHERE `uid`=$uid ;