--
-- Database: `maxima`
--

-- --------------------------------------------------------

--
-- Table structure for table `lang_values`
--

CREATE TABLE IF NOT EXISTS `lang_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `var_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `lang_values`
--

INSERT INTO `lang_values` (`id`, `var_id`, `group_id`, `value`) VALUES
(1, 1, 1, 'felhasználó'),
(2, 1, 2, 'user'),
(3, 2, 1, 'bejelentkezés'),
(4, 2, 2, 'login'),
(5, 3, 1, 'kijelentkezés'),
(6, 3, 2, 'logout'),
(7, 4, 1, 'tartalom'),
(8, 4, 2, 'content'),
(9, 5, 1, 'tag'),
(10, 5, 2, 'member'),
(11, 6, 1, 'tagok'),
(12, 6, 2, 'members'),
(13, 7, 1, 'csoportok'),
(14, 7, 2, 'groups'),
(15, 8, 1, 'csoport'),
(16, 8, 2, 'group'),
(17, 9, 1, 'backend'),
(18, 9, 2, 'backend'),
(19, 10, 1, 'frontend'),
(20, 10, 2, 'frontend'),
(21, 11, 1, 'statisztika'),
(22, 11, 2, 'statistic'),
(23, 12, 1, 'adatbázis'),
(24, 12, 2, 'database'),
(25, 13, 1, 'adatbázisok'),
(26, 13, 2, 'databases');

-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2011 at 05:25 PM
-- Server version: 5.5.17
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `maxima`
--

-- --------------------------------------------------------

--
-- Table structure for table `lang_variables`
--

CREATE TABLE IF NOT EXISTS `lang_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) DEFAULT NULL,
  `var` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `lang_variables`
--

INSERT INTO `lang_variables` (`id`, `cat_id`, `var`) VALUES
(1, 1, 'user'),
(2, 1, 'login'),
(3, 1, 'logout'),
(4, 1, 'content'),
(5, 1, 'member'),
(6, 1, 'members'),
(7, 1, 'groups'),
(8, 1, 'group'),
(9, 1, 'backend'),
(10, 1, 'frontend'),
(11, 1, 'statistic'),
(12, 1, 'database'),
(13, 1, 'databases');


CREATE TABLE IF NOT EXISTS `lang_groups` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`val` varchar(255) DEFAULT NULL,
	`flag` varchar(5) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS `lang_cat` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`var` varchar(50) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0;
