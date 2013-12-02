-- phpMyAdmin SQL Dump
-- version 3.3.9.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 02, 2013 at 07:02 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `buyads`
--

-- --------------------------------------------------------

--
-- Table structure for table `adspace`
--

CREATE TABLE IF NOT EXISTS `adspace` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` int(11) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_bin NOT NULL,
  `type_config` text COLLATE utf8_bin NOT NULL,
  `fallback_html` text COLLATE utf8_bin,
  `url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  `paybyclick_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `paybyclick_price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `paybyclick_minimum` int(11) NOT NULL DEFAULT '0',
  `paybyview_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `paybyview_price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `paybyview_minimum` int(11) NOT NULL DEFAULT '0',
  `paybyduration_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `paybyduration_price` decimal(5,2) NOT NULL DEFAULT '0.00',
  `paybyduration_minimum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE IF NOT EXISTS `campaign` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8_bin NOT NULL,
  `status` enum('created','disabled','banned') COLLATE utf8_bin NOT NULL,
  `parameters` text COLLATE utf8_bin NOT NULL,
  `time_created` datetime NOT NULL,
  `time_updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaignrun`
--

CREATE TABLE IF NOT EXISTS `campaignrun` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(11) unsigned NOT NULL,
  `adspace_id` int(11) unsigned NOT NULL,
  `paytype` enum('click','view','duration') COLLATE utf8_bin NOT NULL,
  `paytype_amount` int(11) NOT NULL,
  `time_created` datetime NOT NULL,
  `time_enabled` datetime NOT NULL,
  `time_disabled` datetime NOT NULL,
  `stats_views` int(11) NOT NULL DEFAULT '0',
  `stats_clicks` int(11) NOT NULL DEFAULT '0',
  `status` enum('created','running','stopped','disabled') COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `campaignupload`
--

CREATE TABLE IF NOT EXISTS `campaignupload` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `campaign_id` int(10) unsigned NOT NULL,
  `file_path` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE IF NOT EXISTS `coupon` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `type` enum('percent','absolute') COLLATE utf8_bin NOT NULL,
  `amount` decimal(6,2) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `uses_per_account` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `login_date` datetime DEFAULT NULL,
  `login_ip` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `paymenttransaction`
--

CREATE TABLE IF NOT EXISTS `paymenttransaction` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `payment_provider` varchar(255) COLLATE utf8_bin NOT NULL,
  `amount_funded` decimal(6,2) NOT NULL,
  `time` datetime NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `details` text COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Table structure for table `spending`
--

CREATE TABLE IF NOT EXISTS `spending` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `type` enum('campaignrun_created') COLLATE utf8_bin NOT NULL,
  `element_id` int(10) unsigned DEFAULT NULL,
  `full_amount` decimal(6,2) NOT NULL,
  `final_amount` decimal(6,2) NOT NULL,
  `coupon_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stats_clicks`
--

CREATE TABLE IF NOT EXISTS `stats_clicks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaignrun_id` int(11) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_row` (`campaignrun_id`,`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=133 ;

-- --------------------------------------------------------

--
-- Table structure for table `stats_views`
--

CREATE TABLE IF NOT EXISTS `stats_views` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `campaignrun_id` int(11) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_row` (`campaignrun_id`,`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2278 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp_click`
--

CREATE TABLE IF NOT EXISTS `temp_click` (
  `campaignrun_id` int(11) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `binary_ip` varbinary(16) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `temp_view`
--

CREATE TABLE IF NOT EXISTS `temp_view` (
  `campaignrun_id` int(11) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `binary_ip` varbinary(16) NOT NULL
) ENGINE=ARCHIVE DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(32) COLLATE utf8_bin NOT NULL,
  `salt` varchar(15) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `role` enum('user','admin') COLLATE utf8_bin NOT NULL,
  `balance` decimal(6,2) NOT NULL DEFAULT '0.00',
  `fund_amount` decimal(6,2) DEFAULT NULL,
  `fund_amount_funded` decimal(6,2) DEFAULT NULL,
  `password_hash` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `password_new` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email_hash` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `email_new` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `register_date` datetime DEFAULT NULL,
  `register_ip` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE IF NOT EXISTS `voucher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `amount` decimal(6,2) NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `website`
--

CREATE TABLE IF NOT EXISTS `website` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;
