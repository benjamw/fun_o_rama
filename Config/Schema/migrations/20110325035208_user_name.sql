ALTER TABLE  `users`
	ADD  `first_name` VARCHAR( 255 ) NOT NULL DEFAULT  '' AFTER  `group_id` ,
	ADD  `last_name` VARCHAR( 255 ) NOT NULL DEFAULT  '' AFTER  `first_name` ;

