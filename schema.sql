-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 12, 2011 at 10:42 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `dms-voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `ballots`
--

CREATE TABLE IF NOT EXISTS `ballots` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `allowed_votes` smallint(5) unsigned NOT NULL,
  `open_date` datetime NOT NULL,
  `close_date` datetime NOT NULL,
  `public` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `open_date` (`open_date`,`close_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ballots`
--


-- --------------------------------------------------------

--
-- Table structure for table `ballot_options`
--

CREATE TABLE IF NOT EXISTS `ballot_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ballot_id` int(11) unsigned NOT NULL,
  `text` text NOT NULL,
  `vote_count` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ballot_id` (`ballot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ballot_options`
--


-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ballot_option_id` int(10) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ballot_option_id` (`ballot_option_id`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `votes`
--


