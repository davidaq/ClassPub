<?php die();?>
用户模块，用于对用户信息增删改查

%getByEmail		//根据用户邮箱获得用户基本信息
$email
SELECT `uid`,`name`,`password` FROM `{$TP}user`
	WHERE `email`=$email
