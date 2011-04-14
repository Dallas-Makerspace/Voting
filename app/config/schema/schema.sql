#App sql generated on: 2011-04-14 10:43:45 : 1302795825

DROP TABLE IF EXISTS `ballot_options`;
DROP TABLE IF EXISTS `ballots`;
DROP TABLE IF EXISTS `votes`;


CREATE TABLE `ballot_options` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`ballot_id` int(11) NOT NULL,
	`text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`vote_count` int(11) NOT NULL,	PRIMARY KEY  (`id`),
	KEY `ballot_id` (`ballot_id`))	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=MyISAM;

CREATE TABLE `ballots` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`allowed_votes` int(5) NOT NULL,
	`open_date` datetime NOT NULL,
	`close_date` datetime NOT NULL,
	`public` tinyint(1) DEFAULT 1 NOT NULL,
	`created` datetime NOT NULL,	PRIMARY KEY  (`id`),
	KEY `open_date` (`open_date`, `close_date`))	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=MyISAM;

CREATE TABLE `votes` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`ballot_option_id` int(10) NOT NULL,
	`username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`created` datetime NOT NULL,	PRIMARY KEY  (`id`),
	UNIQUE KEY `ballot_option_id` (`ballot_option_id`, `username`))	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=MyISAM;

