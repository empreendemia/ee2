/**
 * @author Gaspar
 * @since 25/06/2012
 */

ALTER TABLE `demands` ADD `name` VARCHAR(50) NULL AFTER `id`,
ADD `email` VARCHAR(50) NULL AFTER `name`,
ADD `city_state` VARCHAR(100) NULL AFTER `email`;

/**
 * @author Gaspar
 */
ALTER TABLE `users` ADD `lifecycle` VARCHAR( 10 ) NOT NULL DEFAULT '0000000000' AFTER `mails`;

/**
 * @author Gaspar
 */
ALTER TABLE `demands` CHANGE `user_id` `user_id` INT( 11 ) NULL ;

ALTER TABLE `demands` ADD `name` CHAR( 50 ) NULL COMMENT 'Campo só deve ser usado em usuario deslogado' AFTER `user_id` ,
ADD `email` CHAR( 50 ) NULL COMMENT 'Campo só deve ser usado em usuario deslogado' AFTER `name` ;
