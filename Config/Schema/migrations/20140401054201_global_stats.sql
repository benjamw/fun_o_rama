ALTER TABLE `player_stats`
	ADD `global_wins` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `streak`,
	ADD `global_draws` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `global_wins`,
	ADD `global_losses` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `global_draws`,
	ADD `max_streak` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `global_losses`,
	ADD `min_streak` SMALLINT(6) NOT NULL DEFAULT '0' AFTER `max_streak`,
	ADD INDEX(`max_streak`),
	ADD INDEX(`min_streak`);

UPDATE `player_stats`
	SET `global_wins` = `wins`,
		`global_draws` = `draws`,
		`global_losses` = `losses`
WHERE 1;


ALTER TABLE `player_rankings`
	ADD `max_mean` DECIMAL(15,12) DEFAULT '25' AFTER `games_played`,
	ADD `min_mean` DECIMAL(15,12) DEFAULT '25' AFTER `max_mean`,
	ADD INDEX(`max_mean`),
	ADD INDEX(`min_mean`);


