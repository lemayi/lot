-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-02-03 17:31:20
-- 服务器版本： 5.5.36
-- PHP Version: 5.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `lot`
--

-- --------------------------------------------------------

--
-- 表的结构 `shengfuping`
--

CREATE TABLE IF NOT EXISTS `shengfuping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `number` varchar(5) NOT NULL,
  `league` varchar(10) NOT NULL,
  `home_team` varchar(20) NOT NULL,
  `visit_team` varchar(20) NOT NULL,
  `odds_3` float(11,2) NOT NULL,
  `odds_1` float(11,2) NOT NULL,
  `odds_0` float(11,2) NOT NULL,
  `odds_rang_3` float(11,2) NOT NULL,
  `odds_rang_1` float(11,2) NOT NULL,
  `odds_rang_0` float(11,2) NOT NULL,
  `home_score` int(2) NOT NULL DEFAULT '0',
  `visit_score` int(2) NOT NULL DEFAULT '0',
  `rang` int(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `result` tinyint(1) NOT NULL DEFAULT '0',
  `result_rang` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8838 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
