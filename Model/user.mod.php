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

%createUser		//创建用户
$email

%setOptions		//偏好设置
$uid
$defaultPage
$defaultClass

%setDetails		//个人资料设置
$uid
$name
$qq
$phone
