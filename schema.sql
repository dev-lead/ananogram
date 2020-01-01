/**
 * Ananogram Systems, Inc. - All right reserved 2019
 * Author: Stevens Cadet
 * Description: Supporting database, tables, and users to support ananogram application
 **/
CREATE DATABASE IF NOT EXISTS anano_systems CHARACTER SET utf8;
USE `anano_systems`;

#App tables
CREATE TABLE `anano_profiles` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(50) NOT NULL,
	`last_name` VARCHAR(50) NOT NULL,
	`dob` DATE NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL DEFAULT '',
	`security_question` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `email` (`email`)
);

CREATE TABLE `anano_posts` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`heading` VARCHAR(50) NOT NULL,
	`body` TEXT NOT NULL,
	`image_name` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`id`)
);

#APP DB USER
CREATE USER 'ananogram6854'@'localhost' IDENTIFIED BY 'ax6*P4d5)&s4';
GRANT ALL PRIVILEGES ON anano_systems.* TO 'ananogram6854'@'localhost';
FLUSH PRIVILEGES;
