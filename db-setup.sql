CREATE TABLE `kistaai_users__sessions` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`sessionid` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8_swedish_ci',
	`userid` INT(11) UNSIGNED NOT NULL,
	`validto` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE,
	KEY (`sessionid`) USING BTREE
)
COLLATE='utf8_swedish_ci'
ENGINE=InnoDB,
AUTO_INCREMENT=1;

CREATE TABLE `kistaai_uploaded_files` (
	`upload_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) NULL DEFAULT NULL,
	`created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated` DATETIME NULL DEFAULT NULL,
	`realname` VARCHAR(255) NULL DEFAULT NULL COMMENT 'Presentation' COLLATE 'utf8mb4_danish_ci',
	`filename` VARCHAR(255) NULL DEFAULT NULL COMMENT 'On disk' COLLATE 'utf8mb4_danish_ci',
	`extension` VARCHAR(32) NULL DEFAULT NULL COLLATE 'utf8mb4_danish_ci',
	`filesize` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`thumbnail` VARCHAR(255) NOT NULL DEFAULT '' COLLATE 'utf8mb4_danish_ci',
	PRIMARY KEY (`upload_id`) USING BTREE
)
COLLATE='utf8mb4_danish_ci'
ENGINE=InnoDB
AUTO_INCREMENT=1
;
ALTER TABLE `kistaai_uploaded_files`
	ADD COLUMN `status` VARCHAR(64) NOT NULL DEFAULT '' AFTER `thumbnail`,
	ADD COLUMN `error` TEXT NULL AFTER `status`;
ALTER TABLE `kistaai_uploaded_files`
	ADD COLUMN `log` TEXT NULL DEFAULT NULL AFTER `status`;
