-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.17 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for lpdbtest
CREATE DATABASE IF NOT EXISTS `lpdbtest` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `lpdbtest`;


-- Dumping structure for table lpdbtest.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reminder_hash` varchar(255) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `reminder_hash` (`reminder_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table lpdbtest.user: ~1 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `status`, `email`, `password`, `reminder_hash`, `last_login_time`, `create_time`, `update_time`) VALUES
	(1, 'Akos', 50, 'brta.akos@gmail.com', '$1$X11.Uf0.$emcHI8soujeE1kzTd1UWT1', NULL, NULL, '2016-01-03 17:07:05', '2016-01-03 17:07:05');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

CREATE DATABASE IF NOT EXISTS `lpdbtest_us` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `lpdbtest_us`;

-- Dumping structure for table lpdbtest_us.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reminder_hash` varchar(255) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `reminder_hash` (`reminder_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table lpdbtest.user: ~1 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `status`, `email`, `password`, `reminder_hash`, `last_login_time`, `create_time`, `update_time`) VALUES
	(2, 'Akos_us', 50, 'brta.akos+us@gmail.com', '$1$X11.Uf0.$emcHI8soujeE1kzTd1UWT1', NULL, NULL, '2016-01-03 17:07:05', '2016-01-03 17:07:05');

-- Dumping database structure for lpdbtest_shard_1
CREATE DATABASE IF NOT EXISTS `lpdbtest_shard_1` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `lpdbtest_shard_1`;


-- Dumping structure for table lpdbtest_shard_1.user_details
CREATE TABLE IF NOT EXISTS `user_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table lpdbtest_shard_1.user_details: ~1 rows (approximately)
/*!40000 ALTER TABLE `user_details` DISABLE KEYS */;
INSERT INTO `user_details` (`id`, `user_id`, `description`) VALUES
	(1, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus aliquet libero a dapibus cursus. Maecenas id dignissim mi. Proin porta enim diam, vel placerat dui porttitor et. Fusce fringilla congue leo sit amet rutrum. Sed ut dolor ante. Fusce ac augue varius, egestas diam ut, dapibus nibh. Nam dictum sit amet erat id volutpat. Integer finibus velit consectetur, volutpat diam sed, rhoncus nibh. Morbi accumsan sit amet sem eu imperdiet.');
/*!40000 ALTER TABLE `user_details` ENABLE KEYS */;

-- Dumping database structure for lpdbtest_shard_1_us
CREATE DATABASE IF NOT EXISTS `lpdbtest_shard_1_us` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `lpdbtest_shard_1_us`;


-- Dumping structure for table lpdbtest_shard_1_us.user_details
CREATE TABLE IF NOT EXISTS `user_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table lpdbtest_shard_1.user_details: ~1 rows (approximately)
/*!40000 ALTER TABLE `user_details` DISABLE KEYS */;
INSERT INTO `user_details` (`id`, `user_id`, `description`) VALUES
	(2, 2, 'Proin porta enim diam, vel placerat dui porttitor et. Fusce fringilla congue leo sit amet rutrum. Sed ut dolor ante. Fusce ac augue varius, egestas diam ut, dapibus nibh. Nam dictum sit amet erat id volutpat. Integer finibus velit consectetur, volutpat diam sed, rhoncus nibh. Morbi accumsan sit amet sem eu imperdiet.');
/*!40000 ALTER TABLE `user_details` ENABLE KEYS */;


-- Dumping database structure for lpdbtest_shard_2
CREATE DATABASE IF NOT EXISTS `lpdbtest_shard_2` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `lpdbtest_shard_2`;


-- Dumping structure for table lpdbtest_shard_2.user_blob
CREATE TABLE IF NOT EXISTS `user_blob` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `blob_b64` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table lpdbtest_shard_2.user_blob: ~2 rows (approximately)
/*!40000 ALTER TABLE `user_blob` DISABLE KEYS */;
INSERT INTO `user_blob` (`id`, `user_id`, `blob_b64`) VALUES
	(1, 1, 'YmxvYjE='),
	(2, 1, 'YmxvYjI=');
/*!40000 ALTER TABLE `user_blob` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
