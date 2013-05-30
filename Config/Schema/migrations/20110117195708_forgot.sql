DROP TABLE IF EXISTS `forgots`;
CREATE TABLE IF NOT EXISTS `forgots` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
`user_id` INT UNSIGNED NOT NULL ,
`token` CHAR( 32 ) NOT NULL ,
`created` DATETIME NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `token` )
) ENGINE = MYISAM ;


-- if this one fails due to already being there, that's fine
ALTER TABLE `users`
	ADD `ident` CHAR( 32 ) DEFAULT NULL AFTER `password` ,
	ADD `token` CHAR( 32 ) DEFAULT NULL AFTER `ident` ,
	ADD INDEX ( `ident` ) ;

