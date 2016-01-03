CREATE TABLE `user` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NULL DEFAULT NULL,
	`status` TINYINT(2) UNSIGNED NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NULL DEFAULT NULL,
	`reminder_hash` VARCHAR(255) NULL DEFAULT NULL,
	`last_login_time` DATETIME NULL DEFAULT NULL,
	`create_time` DATETIME NULL DEFAULT NULL,
	`update_time` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `email` (`email`),
	INDEX `status` (`status`),
	INDEX `reminder_hash` (`reminder_hash`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;
