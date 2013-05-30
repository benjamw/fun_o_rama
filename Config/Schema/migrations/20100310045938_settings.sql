DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
`id` int( 10 ) unsigned NOT NULL AUTO_INCREMENT ,
`name` varchar( 255 ) NOT NULL ,
`value` text NOT NULL ,
`type` varchar( 255 ) NOT NULL DEFAULT 'text',
`default` text DEFAULT NULL ,
`modified` datetime NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM DEFAULT CHARSET = latin1;

