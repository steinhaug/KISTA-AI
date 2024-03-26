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

ALTER TABLE `kistaai_users__sessions`
	CHANGE COLUMN `id` `user_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `sessionid` `session_id` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8mb3_swedish_ci' AFTER `user_id`,
	CHANGE COLUMN `userid` `google_id` INT(11) UNSIGNED NOT NULL AFTER `session_id`,
	CHANGE COLUMN `validto` `valid_to` DATETIME NULL DEFAULT NULL AFTER `google_id`,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`user_id`) USING BTREE,
	DROP INDEX `sessionid`,
	ADD INDEX `sessionid` (`session_id`) USING BTREE;

ALTER TABLE `kistaai_users__google`
	CHANGE COLUMN `id` `google_id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `google_id` `account_id` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `google_id`,
	CHANGE COLUMN `name` `account_name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `account_id`,
	CHANGE COLUMN `email` `account_email` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `account_name`,
	CHANGE COLUMN `profile_image` `account_picture` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `account_email`,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`google_id`) USING BTREE,
	DROP INDEX `google_id`,
	ADD UNIQUE INDEX `google_id` (`account_id`) USING BTREE;

ALTER TABLE `kistaai_users__google`
	CHANGE COLUMN `account_id` `google_account_id` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `google_id`,
	DROP INDEX `google_id`,
	ADD UNIQUE INDEX `google_id` (`google_account_id`) USING BTREE;

ALTER TABLE `kistaai_users__google`
	CHANGE COLUMN `google_id` `user_google_id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `google_account_id` `google_id` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `user_google_id`,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`user_google_id`) USING BTREE,
	DROP INDEX `google_id`,
	ADD UNIQUE INDEX `google_id` (`google_id`) USING BTREE;
ALTER TABLE `kistaai_users__google`
	CHANGE COLUMN `google_id` `account_id` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `user_google_id`,
	DROP INDEX `google_id`,
	ADD UNIQUE INDEX `google_id` (`account_id`) USING BTREE;

ALTER TABLE `kistaai_users__sessions`
	ADD COLUMN `user_agent` VARCHAR(255) NOT NULL DEFAULT '' AFTER `valid_to`;

/* * * * * * */






CREATE TABLE `kistaai_replicate__uploads` (
	`reid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Replicate Upload ID',
	`uuid` VARCHAR(36) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	`replicate_id` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'ID returned from API' COLLATE 'utf8mb4_danish_ci',
	`user_id` INT(10) NULL DEFAULT NULL COMMENT 'KISTA-AI UserID',
	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated` DATETIME NULL DEFAULT NULL,
	`stylename` VARCHAR(128) NULL DEFAULT NULL COLLATE 'utf8mb4_danish_ci',
	`realname` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Presentation' COLLATE 'utf8mb4_danish_ci',
	`filename` VARCHAR(255) NULL DEFAULT NULL COMMENT 'On disk' COLLATE 'utf8mb4_danish_ci',
	`extension` VARCHAR(32) NULL DEFAULT NULL COLLATE 'utf8mb4_danish_ci',
	`filesize` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`thumbnail` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	`status` VARCHAR(64) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	`log` TEXT NOT NULL COLLATE 'utf8mb4_danish_ci',
	`error` TEXT NOT NULL COLLATE 'utf8mb4_danish_ci',
	PRIMARY KEY (`reid`) USING BTREE
)
COLLATE='utf8mb4_danish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
CREATE TABLE `kistaai_replicate__images` (
	`image_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`uuid` VARCHAR(36) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	`reid` INT(10) NULL DEFAULT NULL,
	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`url` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	`filename` VARCHAR(255) NULL DEFAULT NULL COMMENT 'On disk' COLLATE 'utf8mb4_danish_ci',
	`extension` VARCHAR(32) NULL DEFAULT NULL COLLATE 'utf8mb4_danish_ci',
	`filesize` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`thumbnail` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	`status` VARCHAR(64) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	PRIMARY KEY (`image_id`) USING BTREE
)
COLLATE='utf8mb4_danish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;

ALTER TABLE `kistaai_replicate__uploads`
	ADD COLUMN `data` TEXT NOT NULL AFTER `status`;


* * *

ALTER TABLE `kistaai_replicate__uploads`
	CHANGE COLUMN `replicate_id` `replicate_id` VARCHAR(26) NOT NULL DEFAULT '' COMMENT 'ID returned from API' COLLATE 'utf8mb4_danish_ci' AFTER `uuid`;

CREATE TABLE `kistaai_replicate__hooks` (
	`whid` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'WebHook ID',
	`processed` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
	`replicate_id` VARCHAR(128) NOT NULL DEFAULT '' COMMENT 'ID returned from API' COLLATE 'utf8mb4_danish_ci',
	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`json` JSON NOT NULL,
	PRIMARY KEY (`whid`) USING BTREE
)
COLLATE='utf8mb4_danish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=6
;

* * * *

ALTER TABLE `kistaai_replicate__uploads`
	CHANGE COLUMN `stylename` `stylename` VARCHAR(128) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci' AFTER `updated`,
	CHANGE COLUMN `realname` `realname` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Presentation' COLLATE 'utf8mb4_danish_ci' AFTER `stylename`,
	ADD COLUMN `filehash` VARCHAR(64) NOT NULL DEFAULT '' AFTER `realname`,
	CHANGE COLUMN `filename` `filename` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'On disk' COLLATE 'utf8mb4_danish_ci' AFTER `filehash`,
	CHANGE COLUMN `extension` `extension` VARCHAR(32) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci' AFTER `filename`;

/* * *  */ 

ALTER TABLE `kistaai_replicate__images`
	ADD COLUMN `deleted` TINYINT NOT NULL DEFAULT 0 AFTER `image_id`;

ALTER TABLE `kistaai_replicate__images`
	ADD COLUMN `updated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP() AFTER `created`;

