<?php die(); ?>
课程模块，主要操作表：class,s_c

%getLearn		//获取用户所有 学 的课程
$uid

%getTeach		//获取用户所有 教 的课程
$uid

%createClass		//创建课堂
$uid
$name
$description

%removeClass		//解散课堂
$uid
$cid

%joinClass		//加入课堂学习
$uid
$cid

%leaveClass		//离开课堂
$uid
$cid
