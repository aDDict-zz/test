-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2011 at 09:18 AM
-- Server version: 5.5.17
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `maxima`
--

-- --------------------------------------------------------

--
-- Table structure for table `lang_cat`
--

CREATE TABLE IF NOT EXISTS `lang_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `var` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `lang_cat`
--

INSERT INTO `lang_cat` (`id`, `var`) VALUES
(1, 'menu_entries'),
(2, 'groups');

-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2011 at 09:19 AM
-- Server version: 5.5.17
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `maxima`
--

-- --------------------------------------------------------

--
-- Table structure for table `lang_groups`
--

CREATE TABLE IF NOT EXISTS `lang_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `val` varchar(255) DEFAULT NULL,
  `flag` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `lang_groups`
--

INSERT INTO `lang_groups` (`id`, `val`, `flag`) VALUES
(1, 'hungarian', 'hu'),
(2, 'english', 'en');

-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2011 at 09:19 AM
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

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
(13, 1, 'databases'),
(14, 2, 'groups'),
(15, 2, 'group'),
(16, 2, 'questionnaire'),
(17, 2, 'questionnairis');

-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 24, 2011 at 09:20 AM
-- Server version: 5.5.17
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

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
(26, 13, 2, 'databases'),
(27, 15, 1, 'csoport'),
(28, 15, 2, 'group'),
(29, 14, 1, 'csoportok'),
(30, 14, 2, 'groups'),
(31, 16, 1, 'kérdőív'),
(32, 16, 2, 'questionnaire'),
(33, 17, 1, 'kérdőívek'),
(34, 17, 2, 'questionnairis');

