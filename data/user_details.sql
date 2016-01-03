CREATE TABLE `user_details` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) UNSIGNED NOT NULL,
	`description` TEXT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `user_id` (`user_id`)
)
ENGINE=InnoDB
;