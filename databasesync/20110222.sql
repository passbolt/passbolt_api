-- phpMyAdmin SQL Dump
-- version 3.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 21, 2011 at 08:04 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0



/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `passkeeper`
--

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) binary DEFAULT NULL,
  `alias` varchar(255) binary DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM  AUTO_INCREMENT=3 ;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `model`, `alias`) VALUES
(1, 'Category', 'category'),
(2, 'Password', 'password');

-- --------------------------------------------------------

--
-- Table structure for table `aros`
--

CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) binary DEFAULT NULL,
  `alias` varchar(255) binary DEFAULT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM  AUTO_INCREMENT=3 ;

--
-- Dumping data for table `aros`
--

INSERT INTO `aros` (`id`, `model`, `alias`) VALUES
(1, 'Group', 'group'),
(2, 'User', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `aro_id` int(10) NOT NULL,
  `aco_id` int(10) NOT NULL,
  `_create` varchar(2) binary NOT NULL DEFAULT '0',
  `_read` varchar(2) binary NOT NULL DEFAULT '0',
  `_update` varchar(2) binary NOT NULL DEFAULT '0',
  `_delete` varchar(2) binary NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ARO_ACO_KEY` (`aro_id`,`aco_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

--
-- Dumping data for table `aros_acos`
--


-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `comment` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `root_id` int(10) unsigned DEFAULT NULL,
  `lft` mediumint(8) unsigned DEFAULT NULL,
  `rght` mediumint(8) unsigned DEFAULT NULL,
  `level` mediumint(8) unsigned DEFAULT NULL,
  `category_type_id` tinyint(4) unsigned NOT NULL DEFAULT '1' COMMENT 'type of category',
  `created_by` int(11) NOT NULL COMMENT 'user who created it',
  PRIMARY KEY (`id`),
  KEY `rght` (`root_id`,`rght`,`lft`),
  KEY `lft` (`root_id`,`lft`,`rght`),
  KEY `parent_id` (`parent_id`,`created`)
) TYPE=InnoDB  AUTO_INCREMENT=47 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `comment`, `created`, `modified`, `parent_id`, `root_id`, `lft`, `rght`, `level`, `category_type_id`, `created_by`) VALUES
(1, 'Default', NULL, '2010-11-13 11:13:47', '2011-02-18 21:51:01', NULL, 1, 1, 22, 0, 1, 17),
(2, 'kotow', NULL, '2010-11-13 11:13:47', '2011-02-18 21:51:01', 1, 1, 2, 21, 1, 1, 17),
(3, 'neemrana', NULL, '2010-11-13 11:13:47', '2011-02-18 21:51:01', 2, 1, 3, 10, 2, 1, 17),
(4, 'dev', NULL, '2010-11-13 11:13:47', '2011-02-18 21:51:01', 2, 1, 11, 20, 2, 1, 17),
(5, 'prodtest', NULL, '2010-11-13 11:15:33', '2011-02-20 12:55:40', 3, 1, 4, 7, 3, 1, 17),
(7, 'prod', NULL, '2010-11-13 11:15:50', '2011-02-18 21:51:01', 3, 1, 8, 9, 3, 1, 17),
(8, 'test', NULL, '2010-11-15 13:44:53', '2011-02-18 21:51:02', 14, 1, 13, 14, 4, 1, 17),
(11, 'WebDev', NULL, '2010-12-19 18:31:43', '2011-02-18 21:51:01', NULL, 11, 1, 20, 0, 1, 17),
(12, 'prod', NULL, '2010-12-19 21:45:35', '2011-02-18 21:51:02', 11, 11, 2, 13, 1, 1, 17),
(13, 'dev', NULL, '2010-12-19 21:45:39', '2011-02-18 21:51:02', 11, 11, 14, 19, 1, 1, 17),
(14, 'this is a test ', NULL, '2010-12-26 23:39:24', '2011-02-18 21:51:02', 4, 1, 12, 17, 3, 1, 17),
(15, 'nodetest1', NULL, '2010-12-27 20:15:47', '2011-02-18 21:51:02', 5, 1, 5, 6, 4, 1, 18),
(16, 'test1', NULL, '2010-12-30 12:59:04', '2011-02-18 21:51:02', 13, 11, 15, 18, 2, 1, 17),
(17, 'test1', NULL, '2011-01-31 11:36:26', '2011-02-18 21:51:02', 4, 1, 18, 19, 3, 1, 17),
(27, 'test1', NULL, '2011-02-16 18:49:48', '2011-02-18 21:51:01', NULL, 27, 1, 2, 0, 1, 17),
(32, 'ftp', NULL, '2011-02-18 19:17:08', '2011-02-18 21:51:02', 8, NULL, 0, 1, 1, 1, 0),
(38, 'test2', NULL, '2011-02-18 20:32:35', '2011-02-18 21:51:02', 12, 11, 3, 4, 2, 1, 17),
(39, 'test3', NULL, '2011-02-18 20:32:39', '2011-02-18 21:51:02', 12, 11, 5, 6, 2, 1, 17),
(40, 'test4', NULL, '2011-02-18 20:32:45', '2011-02-18 21:51:02', 12, 11, 7, 12, 2, 1, 17),
(41, 'subtest41', NULL, '2011-02-18 20:32:52', '2011-02-18 21:51:03', 40, 11, 8, 9, 3, 1, 17),
(42, 'subtest42', NULL, '2011-02-18 20:33:00', '2011-02-18 21:51:03', 40, 11, 10, 11, 3, 1, 17),
(44, 'subtest41', NULL, '2011-02-18 20:45:38', '2011-02-18 21:51:02', 16, 11, 16, 17, 3, 1, 17),
(46, 'test3', NULL, '2011-02-18 21:52:15', '2011-02-19 13:11:44', 14, 1, 15, 16, 4, 1, 17);

-- --------------------------------------------------------

--
-- Table structure for table `category_types`
--

CREATE TABLE IF NOT EXISTS `category_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) binary NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(255) binary NOT NULL,
  `live` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM  AUTO_INCREMENT=3 ;

--
-- Dumping data for table `category_types`
--

INSERT INTO `category_types` (`id`, `name`, `description`, `icon`, `live`) VALUES
(1, 'default', 0x64656661756c742074797065206f662063617465676f7279, 'default.png', 1),
(2, 'root', 0x646174616261736520747970652c20666f722074686520726f6f7420666f6c646572, 'database.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) NOT NULL,
  `category_rel_id` int(11) DEFAULT NULL,
  `aco_id` int(11) DEFAULT NULL,
  `aco_ref_id` int(11) DEFAULT NULL,
  `details` text,
  `date` timestamp NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=MyISAM  AUTO_INCREMENT=49 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `action_id`, `category_rel_id`, `aco_id`, `aco_ref_id`, `details`, `date`, `created_by`) VALUES
(15, 3, 5, 1, 5, NULL, '2011-02-20 18:20:47', 17),
(14, 1, 5, 1, 5, NULL, '2011-02-20 18:20:24', 17),
(13, 1, 5, 1, 5, NULL, '2011-02-20 14:41:30', 17),
(12, 1, 15, 1, 15, NULL, '2011-02-20 14:41:29', 17),
(11, 1, 5, 1, 5, NULL, '2011-02-20 14:41:28', 17),
(9, 1, 15, 1, 15, NULL, '2011-02-20 14:20:13', 17),
(10, 1, 7, 1, 7, NULL, '2011-02-20 14:26:46', 17),
(16, 20, 5, 2, 81, NULL, '2011-02-20 18:21:18', 17),
(17, 1, 5, 1, 5, NULL, '2011-02-20 18:23:19', 17),
(18, 1, 5, 1, 5, NULL, '2011-02-20 18:25:33', 17),
(19, 3, 5, 1, 5, 0x7b226f6c645f6e616d65223a2270726f6431222c226e65775f6e616d65223a2270726f6474657374227d, '2011-02-20 18:25:41', 17),
(20, 1, 5, 1, 5, NULL, '2011-02-20 18:27:06', 17),
(21, 21, 5, 2, 81, NULL, '2011-02-20 18:28:59', 17),
(22, 1, 5, 1, 5, NULL, '2011-02-20 18:29:23', 17),
(23, 1, 5, 1, 5, NULL, '2011-02-20 18:42:41', 17),
(24, 1, 5, 1, 5, NULL, '2011-02-20 18:48:12', 17),
(25, 1, 5, 1, 5, NULL, '2011-02-20 18:48:42', 17),
(26, 1, 5, 1, 5, NULL, '2011-02-20 18:56:47', 17),
(27, 1, 5, 1, 5, NULL, '2011-02-20 18:57:19', 17),
(28, 1, 5, 1, 5, NULL, '2011-02-20 18:57:45', 17),
(29, 1, 5, 1, 5, NULL, '2011-02-20 18:58:04', 17),
(30, 1, 5, 1, 5, NULL, '2011-02-20 18:58:34', 17),
(31, 1, 5, 1, 5, NULL, '2011-02-20 18:59:05', 17),
(32, 1, 5, 1, 5, NULL, '2011-02-20 18:59:42', 17),
(33, 1, 5, 1, 5, NULL, '2011-02-20 18:59:52', 17),
(34, 1, 5, 1, 5, NULL, '2011-02-20 19:00:07', 17),
(35, 1, 5, 1, 5, NULL, '2011-02-20 19:01:13', 17),
(36, 1, 5, 1, 5, NULL, '2011-02-20 19:01:43', 17),
(37, 1, 5, 1, 5, NULL, '2011-02-20 19:02:27', 17),
(38, 1, 5, 1, 5, NULL, '2011-02-20 19:02:51', 17),
(39, 1, 5, 1, 5, NULL, '2011-02-20 19:03:10', 17),
(40, 1, 5, 1, 5, NULL, '2011-02-20 19:05:21', 17),
(41, 1, 5, 1, 5, NULL, '2011-02-20 19:53:44', 17),
(42, 1, 5, 1, 5, NULL, '2011-02-20 19:54:01', 17),
(43, 1, 5, 1, 5, NULL, '2011-02-20 20:09:54', 17),
(44, 1, 5, 1, 5, NULL, '2011-02-20 20:13:58', 17),
(45, 1, 5, 1, 5, NULL, '2011-02-20 20:15:08', 17),
(46, 1, 5, 1, 5, NULL, '2011-02-20 20:16:18', 17),
(47, 1, 5, 1, 5, NULL, '2011-02-20 20:23:36', 17),
(48, 1, 5, 1, 5, NULL, '2011-02-20 20:25:32', 17);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) binary NOT NULL,
  `parent_id` int(11) NOT NULL,
  `root_id` int(11) NOT NULL,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) TYPE=InnoDB  AUTO_INCREMENT=52 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `parent_id`, `root_id`, `lft`, `rght`, `level`) VALUES
(43, 'Webdev', 0, 43, 1, 8, 0),
(44, 'Programmers', 43, 43, 2, 5, 1),
(45, 'Designers', 43, 43, 6, 7, 1),
(46, 'Team Leaders', 44, 43, 3, 4, 2),
(47, 'Sysadmin', 0, 47, 1, 2, 0),
(51, 'test3', 0, 51, 1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `groups_users`
--

CREATE TABLE IF NOT EXISTS `groups_users` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`)
) TYPE=MyISAM;

--
-- Dumping data for table `groups_users`
--

INSERT INTO `groups_users` (`group_id`, `user_id`) VALUES
(44, 17),
(45, 9),
(46, 9),
(47, 9),
(47, 17),
(47, 36),
(51, 9),
(51, 17);

-- --------------------------------------------------------

--
-- Table structure for table `passwords`
--

CREATE TABLE IF NOT EXISTS `passwords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) binary NOT NULL,
  `username` varchar(50) binary NOT NULL,
  `password` varchar(255) binary NOT NULL,
  `url` varchar(255) binary NOT NULL,
  `comment` text,
  `live` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'is the password deleted ?',
  PRIMARY KEY (`id`)
) TYPE=MyISAM  AUTO_INCREMENT=82 ;

--
-- Dumping data for table `passwords`
--

INSERT INTO `passwords` (`id`, `category_id`, `title`, `username`, `password`, `url`, `comment`, `live`) VALUES
(1, 0, 'administrator', 'kevin', 'KNOWLEDGE', '', 0x636f6d6d656e742074657374, 1),
(2, 0, 'test', 'test', 'test', 'test', 0x646173647361, 1),
(3, 0, '', '', '', '', '', 1),
(4, 0, 'this is a test', 'kevos', 'â—â—â—â—â—â—â—â—â—â—â—', '92.121.93.82', 0x7465737432, 1),
(5, 0, 'test', 'test', 'test', '91.121.93.82', 0x74657374, 1),
(6, 0, 'test1', 'dsadsa', 'dsadsa', 'dsadsa', 0x647361647361, 1),
(7, 0, 'test', 'test', 'test', 'test', 0x64736164736461647361, 1),
(8, 0, 'test', 'test', 'test', '91.121.93.82', 0x646173646173646173, 1),
(9, 58, 'passwordcat2', 'test', 'test', '91.121.93.82', 0x74657374636f6d6d656e74, 1),
(10, 56, 'passwordtest1', 'kevin', 'knowledge', '91.121.93.82', 0x647361647361647361, 1),
(11, 59, 'my foot', 'kev', 'password', '91.121.93.82', 0x7468697320697320612074657374, 1),
(12, 56, 'test1', 'kmuller', 'knowledge', '91.121.93.82', 0x7468697320697320612074657374, 1),
(13, 56, 'kevos', 'knowledge', 'knowledge', '91.121.93.82', 0x74657374, 1),
(14, 56, 'test1', 'test2', 'test4', 'test5', 0x6461646173646173, 1),
(15, 56, 'kevos', 'knowledge', 'dadasd', 'dsadasd', 0x646461736461646173, 1),
(16, 56, 'test3', 'kevos', 'knowledge', '91.121.93.82', 0x646173647361647361, 1),
(17, 56, 'test4', 'knowledge', 'knowledge', '91.121.93.82', 0x64616473647361, 1),
(18, 56, 'test1', 'test2', 'test4', '91.121.93.82', 0x6461736461, 1),
(19, 56, 'root', 'password', '91.121.93.82', 'dasdas', 0x646173647361, 1),
(20, 56, 'test1', 'test', 'dasdsa', 'dsada', 0x6461647361, 1),
(21, 56, 'dsad', 'dasdas', 'daads', 'dasda', 0x6461736461, 1),
(31, 0, '', '', '', '', NULL, 1),
(23, 5, 'testprod', 'mynameisgnu', 'knowledge', '91.121.93.82', 0x7468697320697320612074657374, 1),
(24, 8, 'vidhat', 'vidhat', 'dadass', 'dasdsa', 0x6461736473617361, 1),
(25, 0, 'dev server ssh', 'mynameisgnu', '53psh9q1b6', '91.121.93.82', 0x746869732070617373776f7264206973207468652070617373776f7264206f66207468652064657620736572766572, 1),
(26, 0, 'dev server ssh', 'kevos', 'knowledge', '91.121.93.82', 0x7468697320697320612074657374, 1),
(27, 13, 'dev server ssh', 'kevos', 'knowledge', '91.121.93.82', 0x7468697320697320612074657374, 1),
(28, 13, 'dev server ftp', 'mynameisgnu', 'knowledge', 'http://www.enova-tech.net', 0x7468697320697320612074657374, 1),
(29, 5, 'ced admin', 'ced', 'w8nrjpbyd4', '91.121.93.82', 0x646173646173, 1),
(30, 0, 'root', 'kevos1', 'dmk3tzyx47', '91.121.93.82', 0x7465737420636f6d6d656e74, 1),
(32, 0, '', '', '', '', NULL, 1),
(33, 0, '', '', '', '', NULL, 1),
(34, 14, 'root1', 'root123', 'test', '91.121.83.25', '', 1),
(35, 14, 'root2', 'root2', 'papamaman', '123.45.67.82', '', 1),
(36, 14, 'root3', 'root3', 'vw6nxychb1', '123.65.74.34', '', 1),
(37, 0, '', '', '', '', NULL, 0),
(38, 0, '', '', '', '', NULL, 1),
(80, 15, 'password', 'root', 'knowledge', '91.121.93.82', 0x7465737431, 1),
(41, 46, 'administrator', 'kevin', 'KNOWLEDGE', '', 0x636f6d6d656e742074657374, 1),
(42, 46, 'test', 'test', 'test', 'test', 0x646173647361, 1),
(43, 46, '', '', '', '', '', 1),
(44, 46, 'this is a test', 'kevos', 'â—â—â—â—â—â—â—â—â—â—â—', '92.121.93.82', 0x7465737432, 1),
(45, 46, 'test', 'test', 'test', '91.121.93.82', 0x74657374, 1),
(46, 46, 'test1', 'dsadsa', 'dsadsa', 'dsadsa', 0x647361647361, 1),
(47, 46, 'test', 'test', 'test', 'test', 0x64736164736461647361, 1),
(48, 46, 'test', 'test', 'test', '91.121.93.82', 0x646173646173646173, 1),
(49, 46, 'passwordcat2', 'test', 'test', '91.121.93.82', 0x74657374636f6d6d656e74, 1),
(50, 46, 'passwordtest1', 'kevin', 'knowledge', '91.121.93.82', 0x647361647361647361, 1),
(51, 46, 'my foot', 'kev', 'password', '91.121.93.82', 0x7468697320697320612074657374, 1),
(52, 46, 'test1', 'kmuller', 'knowledge', '91.121.93.82', 0x7468697320697320612074657374, 1),
(53, 46, 'kevos', 'knowledge', 'knowledge', '91.121.93.82', 0x74657374, 1),
(54, 46, 'test1', 'test2', 'test4', 'test5', 0x6461646173646173, 1),
(55, 46, 'kevos', 'knowledge', 'dadasd', 'dsadasd', 0x646461736461646173, 1),
(56, 46, 'test3', 'kevos', 'knowledge', '91.121.93.82', 0x646173647361647361, 1),
(57, 46, 'test4', 'knowledge', 'knowledge', '91.121.93.82', 0x64616473647361, 1),
(58, 46, 'test1', 'test2', 'test4', '91.121.93.82', 0x6461736461, 1),
(59, 46, 'root', 'password', '91.121.93.82', 'dasdas', 0x646173647361, 1),
(60, 46, 'test1', 'test', 'dasdsa', 'dsada', 0x6461647361, 1),
(61, 46, 'dsad', 'dasdas', 'daads', 'dasda', 0x6461736461, 1),
(62, 46, '', '', '', '', NULL, 1),
(63, 46, 'testprod', 'mynameisgnu', 'knowledge', '91.121.93.82', 0x7468697320697320612074657374, 1),
(64, 46, 'vidhat', 'vidhat', 'dadass', 'dasdsa', 0x6461736473617361, 1),
(65, 46, 'dev server ssh', 'mynameisgnu', '53psh9q1b6', '91.121.93.82', 0x746869732070617373776f7264206973207468652070617373776f7264206f66207468652064657620736572766572, 1),
(66, 46, 'dev server ssh', 'kevos', 'knowledge', '91.121.93.82', 0x7468697320697320612074657374, 1),
(67, 46, 'dev server ssh', 'kevos', 'knowledge', '91.121.93.82', 0x7468697320697320612074657374, 1),
(68, 46, 'dev server ftp', 'mynameisgnu', 'knowledge', 'http://www.enova-tech.net', 0x7468697320697320612074657374, 1),
(69, 46, 'ced admin', 'ced', 'w8nrjpbyd4', '91.121.93.82', 0x646173646173, 1),
(70, 46, 'root', 'kevos1', 'dmk3tzyx47', '91.121.93.82', 0x7465737420636f6d6d656e74, 1),
(71, 46, '', '', '', '', NULL, 1),
(72, 46, '', '', '', '', NULL, 1),
(73, 46, 'root1', 'root123', 'test', '91.121.83.25', '', 1),
(74, 46, 'root2', 'root2', 'papamaman', '123.45.67.82', '', 1),
(75, 46, 'root3', 'root3', 'vw6nxychb1', '123.65.74.34', '', 1),
(76, 46, '', '', '', '', NULL, 0),
(77, 46, '', '', '', '', NULL, 1),
(78, 46, 'root', 'kevos', 'knowledge', '91.121.93.82', 0x74657374, 1),
(79, 46, 'kevin', 'kevin', 'test1', '132.83', '', 1),
(81, 5, 'new password', 'root', 'v6t7gp00bk', '91.121.93.82', 0x7468697320697320612074657374, 0);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aco_id` tinyint(4) NOT NULL,
  `aco_ref_id` int(10) unsigned NOT NULL,
  `aro_id` tinyint(4) NOT NULL,
  `aro_ref_id` int(10) unsigned NOT NULL,
  `_read` tinyint(1) NOT NULL,
  `_write` tinyint(1) NOT NULL,
  `_manage` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) TYPE=MyISAM  AUTO_INCREMENT=69 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `aco_id`, `aco_ref_id`, `aro_id`, `aro_ref_id`, `_read`, `_write`, `_manage`) VALUES
(56, 1, 44, 1, 43, 1, 0, 0),
(54, 1, 41, 2, 17, 1, 1, 1),
(53, 1, 41, 2, 9, 0, 0, 0),
(46, 1, 0, 2, 9, 1, 0, 0),
(45, 1, 0, 2, 9, 1, 0, 0),
(51, 1, 41, 1, 43, 1, 0, 0),
(34, 1, 7, 1, 43, 1, 0, 0),
(33, 1, 8, 1, 43, 1, 1, 1),
(32, 1, 3, 1, 43, 1, 0, 0),
(52, 1, 41, 1, 47, 1, 1, 0),
(39, 1, 32, 1, 43, 1, 0, 0),
(29, 1, 14, 1, 43, 1, 1, 1),
(28, 1, 4, 1, 43, 1, 0, 0),
(57, 1, 44, 1, 47, 1, 1, 0),
(58, 1, 44, 2, 17, 1, 1, 1),
(59, 1, 44, 2, 9, 0, 0, 0),
(68, 1, 46, 2, 9, 1, 0, 0),
(67, 1, 46, 2, 11, 1, 1, 1),
(66, 1, 46, 2, 17, 1, 1, 1),
(65, 1, 46, 1, 43, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(50) binary DEFAULT NULL,
  `password` char(40) binary DEFAULT NULL,
  `name` varchar(100) binary NOT NULL,
  `email` varchar(255) binary NOT NULL,
  `address` text,
  `admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'is the user a super admin ?',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `live` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) TYPE=MyISAM  AUTO_INCREMENT=37 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `email`, `address`, `admin`, `active`, `created`, `created_by`, `modified`, `live`) VALUES
(9, 'achoudhary', 'b5205b26332f43d3c11229e310aa510cb8f1ae59', 'Alka Choudhary', 'alka@gmail.com', 0x33302072616a616d616e6e617220737472656574, 0, 1, '2010-11-14 00:00:00', 0, '2011-02-09 20:06:34', 1),
(8, 'vidhat2', 'test', 'vidhatanand great', 'vidhatanand@gmail.com', 0x6438382f32206f6b686c612070686173652049, 0, 1, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(10, 'jeanclaude', 'password', 'Jean Claude Vandamme', 'jcvandamme@gmail.com', 0x61647265737365206465206a65616e20636c61756465, 0, 1, '2010-11-20 19:25:16', 0, '2010-11-20 19:25:16', 1),
(11, 'marieantoinette', 'pasword', 'Marie Antoinette', 'marie@gmail.com', 0x74657374, 0, 1, '2010-11-20 19:28:43', 0, '2010-11-20 19:28:43', 1),
(17, 'kevos', '84d4499edb3acb12838afb28c996db067cd3b64a', 'Kevin Muller2', 'mynameisgnu@gmail.com', 0x33302072616a616d616e6e617220737472656574, 1, 1, '2010-12-19 17:47:18', 0, '2011-02-05 20:27:22', 1),
(18, 'achoudhary', 'd1eac109fd31167f7f3effb5372c9786565ec621', 'Alka1 Choudhary1', 'achoudhary@enova-tech.net', '', 0, 1, '2010-12-27 19:47:10', 0, '2010-12-27 19:47:10', 1),
(36, 'test5', '3483593dc93bf1a1c40aa712494383fa758fb16c', 'test5', 'test5', '', 0, 1, '2011-02-06 12:11:46', 17, '2011-02-06 12:11:46', 1);
