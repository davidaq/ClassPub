-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 05 月 21 日 14:55
-- 服务器版本: 5.5.16
-- PHP 版本: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `classpub`
--

-- --------------------------------------------------------

--
-- 表的结构 `attachment`
--

DROP TABLE IF EXISTS `attachment`;
CREATE TABLE IF NOT EXISTS `attachment` (
  `atid` int(11) NOT NULL AUTO_INCREMENT,
  `did` int(11) NOT NULL,
  `url` text NOT NULL,
  `name` text NOT NULL,
  `size` int(11) NOT NULL,
  `isupload` tinyint(1) NOT NULL,
  PRIMARY KEY (`atid`),
  KEY `did` (`did`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- 表的结构 `class`
--

DROP TABLE IF EXISTS `class`;
CREATE TABLE IF NOT EXISTS `class` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `ispublic` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `teacher` int(11) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`cid`),
  KEY `name` (`name`),
  KEY `teacher` (`teacher`),
  KEY `ispublic` (`ispublic`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `discus`
--

DROP TABLE IF EXISTS `discus`;
CREATE TABLE IF NOT EXISTS `discus` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `cid` int(11) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `reply` int(11) NOT NULL DEFAULT '0',
  `type` enum('normal','homework','attachment') NOT NULL,
  `content` mediumtext NOT NULL,
  `title` varchar(255) NOT NULL,
  `commitol` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`),
  KEY `uid` (`uid`,`reply`),
  KEY `reply` (`reply`),
  KEY `time` (`time`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- 表的结构 `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
  PRIMARY KEY (`fid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `homework`
--

DROP TABLE IF EXISTS `homework`;
CREATE TABLE IF NOT EXISTS `homework` (
  `did` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`did`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `msid` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`msid`),
  KEY `to` (`to`),
  KEY `from` (`from`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

-- --------------------------------------------------------

--
-- 表的结构 `offer`
--

DROP TABLE IF EXISTS `offer`;
CREATE TABLE IF NOT EXISTS `offer` (
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fromclass` tinyint(1) NOT NULL,
  PRIMARY KEY (`uid`,`cid`),
  KEY `cid` (`cid`),
  KEY `fromclass` (`fromclass`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `profile`
--

DROP TABLE IF EXISTS `profile`;
CREATE TABLE IF NOT EXISTS `profile` (
  `uid` int(11) NOT NULL,
  `defaultpage` enum('index','message','discus') NOT NULL,
  `defaultclass` int(11) NOT NULL DEFAULT '0',
  `qq` varchar(11) DEFAULT NULL,
  `phone` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `s_c`
--

DROP TABLE IF EXISTS `s_c`;
CREATE TABLE IF NOT EXISTS `s_c` (
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY (`uid`,`cid`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 触发器 `user`
--
DROP TRIGGER IF EXISTS `auto_add_profile`;
DELIMITER //
CREATE TRIGGER `auto_add_profile` AFTER INSERT ON `user`
 FOR EACH ROW BEGIN
INSERT INTO `profile` set `uid`=(new.`uid`);
END
//
DELIMITER ;

--
-- 限制导出的表
--

--
-- 限制表 `attachment`
--
ALTER TABLE `attachment`
  ADD CONSTRAINT `attachment_ibfk_1` FOREIGN KEY (`did`) REFERENCES `discus` (`did`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacher`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- 限制表 `discus`
--
ALTER TABLE `discus`
  ADD CONSTRAINT `discus_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `discus_ibfk_3` FOREIGN KEY (`cid`) REFERENCES `class` (`cid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- 限制表 `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- 限制表 `homework`
--
ALTER TABLE `homework`
  ADD CONSTRAINT `homework_ibfk_1` FOREIGN KEY (`did`) REFERENCES `discus` (`did`) ON DELETE CASCADE,
  ADD CONSTRAINT `homework_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE;

--
-- 限制表 `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`from`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`to`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- 限制表 `offer`
--
ALTER TABLE `offer`
  ADD CONSTRAINT `offer_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE,
  ADD CONSTRAINT `offer_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `class` (`cid`) ON DELETE CASCADE;

--
-- 限制表 `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- 限制表 `s_c`
--
ALTER TABLE `s_c`
  ADD CONSTRAINT `s_c_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `s_c_ibfk_2` FOREIGN KEY (`cid`) REFERENCES `class` (`cid`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
