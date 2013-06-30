
ALTER TABLE  `game_types` ADD  `max_team_size` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT  '2' ;
UPDATE  `game_types` SET  `max_team_size` =  '4' WHERE  `game_types`.`id` =1;

ALTER TABLE `matches`
	DROP `sat_out` ,
	CHANGE  `game_id`  `tournament_id` INT( 10 ) UNSIGNED NOT NULL ,
	ADD  `quality` DECIMAL( 6, 4 ) NOT NULL AFTER  `tournament_id` ,
	DROP INDEX  `game_id` ,
	ADD INDEX  `tournament_id` (  `tournament_id` );


DROP TABLE IF EXISTS `tournaments`;
CREATE TABLE IF NOT EXISTS `tournaments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `game_id` int(10) unsigned NOT NULL,
  `tournament_type` varchar(255) NOT NULL,
  `team_size` tinyint(2) unsigned NOT NULL,
  `quality` DECIMAL( 6, 4 ) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `game_id` (`game_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tournament_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
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


DROP TABLE IF EXISTS `player_stats`;
CREATE TABLE IF NOT EXISTS `player_stats` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `player_id` INT UNSIGNED NOT NULL ,
  `game_id` INT UNSIGNED NOT NULL ,
  `wins` INT UNSIGNED NOT NULL ,
  `draws` INT UNSIGNED NOT NULL ,
  `losses` INT UNSIGNED NOT NULL ,
  UNIQUE ( `player_id` , `game_id` )
) ENGINE = MYISAM DEFAULT CHARSET=utf8;


