
ALTER TABLE `game_types` ADD `max_team_size` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT '2';
UPDATE `game_types` SET `max_team_size` = '4' WHERE `game_types`.`id` = 1;


DROP TABLE IF EXISTS `matches`;
CREATE TABLE IF NOT EXISTS `matches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `quality` decimal(6,4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `winning_team_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `winning_team_id` (`winning_team_id`),
  KEY `tournament_id` (`tournament_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `matches_teams`;
CREATE TABLE IF NOT EXISTS `matches_teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `match_id` int(10) unsigned NOT NULL,
  `team_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `match_id` (`match_id`,`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


TRUNCATE TABLE `players_teams`;


DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_seed` smallint(2) NULL DEFAULT NULL,
  `seed` smallint(2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tournament_id` (`tournament_id`),
  KEY `seed` (`seed`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tournaments`;
CREATE TABLE IF NOT EXISTS `tournaments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `tournament_type` varchar(255) NOT NULL,
  `team_size` tinyint(2) unsigned NOT NULL,
  `quality` decimal(6, 4) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


