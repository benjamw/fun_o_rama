ALTER TABLE  `players` ADD  `active` BOOLEAN NOT NULL DEFAULT TRUE ,
ADD INDEX (  `active` ) ;

