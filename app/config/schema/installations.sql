-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 18, 2012 at 09:04 PM
-- Server version: 5.1.49
-- PHP Version: 5.2.17-0.dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `passboltapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `installations`
--

CREATE TABLE IF NOT EXISTS `installations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `dbhost` varchar(255) NOT NULL,
  `dbname` varchar(255) NOT NULL,
  `dbusername` varchar(255) NOT NULL,
  `dbpassword` varchar(255) NOT NULL,
  `validation_code` varchar(5) NOT NULL COMMENT 'code used for validation',
  `validated` tinyint(1) NOT NULL COMMENT 'has the account been validated',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `installations`
--

