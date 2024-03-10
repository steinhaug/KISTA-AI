/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE IF NOT EXISTS `kistaai_uploaded_files` (
  `upload_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL,
  `realname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci DEFAULT NULL COMMENT 'Presentation',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci DEFAULT NULL COMMENT 'On disk',
  `extension` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci DEFAULT NULL,
  `filesize` int(10) unsigned NOT NULL DEFAULT '0',
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci NOT NULL DEFAULT '',
  `reciepe_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci NOT NULL DEFAULT '',
  `reciepe` text COLLATE utf8mb4_danish_ci NOT NULL,
  `status` varchar(64) COLLATE utf8mb4_danish_ci NOT NULL DEFAULT '',
  `log` text COLLATE utf8mb4_danish_ci,
  `error` text COLLATE utf8mb4_danish_ci,
  PRIMARY KEY (`upload_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

CREATE TABLE IF NOT EXISTS `kistaai_uploaded_files__openai` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `upload_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `valid_from` datetime NOT NULL,
  `valid_to` datetime DEFAULT NULL,
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_danish_ci,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uploadId` (`upload_id`) USING BTREE,
  KEY `userId` (`user_id`) USING BTREE,
  KEY `validTo` (`valid_to`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_danish_ci;

CREATE TABLE IF NOT EXISTS `kistaai_users__sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sessionid` varchar(255) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL DEFAULT '',
  `userid` int(11) unsigned NOT NULL,
  `validto` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `sessionid` (`sessionid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

CREATE TABLE IF NOT EXISTS `kistaai_users__sessions_data` (
  `session_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data` text COLLATE utf8_swedish_ci NOT NULL,
  PRIMARY KEY (`session_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;


CREATE TABLE `kistaai_users__google` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `google_id` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
 `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
 `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
 `profile_image` text COLLATE utf8mb4_unicode_ci NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `google_id` (`google_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=UTF8MB4_UNICODE_CI;


CREATE TABLE `kistaai_contact_form` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`created` DATETIME NULL DEFAULT NULL,
	`name` VARCHAR(255) NULL DEFAULT NULL COMMENT '' COLLATE 'utf8mb4_danish_ci',
	`email` VARCHAR(255) NULL DEFAULT NULL COMMENT '' COLLATE 'utf8mb4_danish_ci',
	`tel` VARCHAR(32) NULL DEFAULT NULL COLLATE 'utf8mb4_danish_ci',
	`message` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_danish_ci',
	PRIMARY KEY (`id`) USING BTREE
) COLLATE='utf8mb4_danish_ci' ENGINE=InnoDB AUTO_INCREMENT=1;

CREATE TABLE `kistaai_uploaded_files__reciepes` (
	`reciepe_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`upload_id` INT(10) NULL DEFAULT NULL,
	`user_id` INT(10) NULL DEFAULT NULL,
	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated` DATETIME NULL DEFAULT NULL,
	`image` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	`thumbnail` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	`reciepe` TEXT NOT NULL COLLATE 'utf8mb4_danish_ci',
	PRIMARY KEY (`reciepe_id`) USING BTREE
) COLLATE='utf8mb4_danish_ci' ENGINE=InnoDB AUTO_INCREMENT=1;
