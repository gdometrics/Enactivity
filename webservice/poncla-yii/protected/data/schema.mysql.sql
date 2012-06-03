-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 29, 2011 at 07:42 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `poncla_yii`
--

-- --------------------------------------------------------

--
-- Table structure for table `activerecordlog`
--

CREATE TABLE IF NOT EXISTS `activerecordlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupId` int(11) unsigned NOT NULL,
  `focalModel` varchar(45) NOT NULL,
  `focalModelId` int(11) unsigned NOT NULL,
  `model` varchar(45) NOT NULL,
  `modelId` int(11) unsigned NOT NULL,
  `action` varchar(20) DEFAULT NULL,
  `modelAttribute` varchar(45) DEFAULT NULL,
  `oldAttributeValue` mediumtext,
  `newAttributeValue` mediumtext,
  `userId` int(11) unsigned DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `groupId` (`groupId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=154 ;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `group_profile`
--

CREATE TABLE IF NOT EXISTS `group_profile` (
  `groupId` int(11) unsigned NOT NULL,
  `description` varchar(4000) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `group_user`
--

CREATE TABLE IF NOT EXISTS `group_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupId` int(11) unsigned NOT NULL,
  `userId` int(11) unsigned NOT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'Pending',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groupId_2` (`groupId`,`userId`),
  KEY `groupId` (`groupId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupId` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `isTrash` tinyint(1) NOT NULL DEFAULT '0',
  `starts` datetime DEFAULT NULL,
  `rootId` int(11) unsigned NOT NULL,
  `lft` int(11) unsigned NOT NULL,
  `rgt` int(11) unsigned NOT NULL,
  `level` int(11) unsigned NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `groupId` (`groupId`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`),
  KEY `level` (`level`),
  KEY `root` (`rootId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Table structure for table `task_user`
--

CREATE TABLE IF NOT EXISTS `task_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userId` int(11) unsigned DEFAULT NULL,
  `taskId` int(11) unsigned NOT NULL,
  `isCompleted` tinyint(1) NOT NULL DEFAULT '0',
  `isTrash` tinyint(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userId_taskId` (`userId`,`taskId`),
  KEY `userId` (`userId`),
  KEY `taskId` (`taskId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `token` varchar(40) NOT NULL,
  `password` varchar(40) DEFAULT NULL,
  `firstName` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `timeZone` varchar(20) NOT NULL DEFAULT 'America/Los_Angeles',
  `status` varchar(15) NOT NULL DEFAULT 'Pending',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `lastLogin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`groupId`) REFERENCES `group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_user`
--
ALTER TABLE `task_user`
  ADD CONSTRAINT `task_user_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `task_user_ibfk_2` FOREIGN KEY (`taskId`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;