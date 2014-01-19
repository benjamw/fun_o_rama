ALTER TABLE `player_rankings`
	CHANGE `mean` `mean` DECIMAL(15,12) NULL DEFAULT NULL,
	CHANGE `std_deviation` `std_deviation` DECIMAL(15,12) NULL DEFAULT NULL,
	CHANGE `games_played` `games_played` INT(10) UNSIGNED NOT NULL DEFAULT 0;


ALTER TABLE `player_stats`
	CHANGE `wins` `wins` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	CHANGE `draws` `draws` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	CHANGE `losses` `losses` INT(10) UNSIGNED NOT NULL DEFAULT 0,
	CHANGE `streak` `streak` SMALLINT(6) NOT NULL DEFAULT 0;

