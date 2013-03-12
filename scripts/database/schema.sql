SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `empreendemia_dev` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `empreendemia_dev` ;

-- -----------------------------------------------------
-- Table `empreendemia_dev`.`sectors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`sectors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `parent_id` INT(11) NOT NULL DEFAULT '0' ,
  `childs_ids` VARCHAR(255) NULL DEFAULT NULL ,
  `name` VARCHAR(50) NOT NULL ,
  `slug` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id`) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`countries`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`countries` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NOT NULL ,
  `slug` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id`) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`regions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`regions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `country_id` INT(11) NOT NULL ,
  `symbol` VARCHAR(10) NOT NULL ,
  `name` VARCHAR(50) NOT NULL ,
  `slug` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_regions_countries1` (`country_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`cities`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`cities` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `region_id` INT(10) UNSIGNED NOT NULL ,
  `name` VARCHAR(50) NOT NULL ,
  `slug` VARCHAR(70) NOT NULL ,
  UNIQUE INDEX `id` (`id` ASC) ,
  UNIQUE INDEX `slug_unique` (`slug` ASC) ,
  INDEX `region_id` (`region_id` ASC) ,
  PRIMARY KEY (`id`) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`companies`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`companies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `sector_id` INT(11) NOT NULL ,
  `city_id` INT(11) NOT NULL ,
  `reputation` INT(11) NOT NULL DEFAULT '0' ,
  `name` VARCHAR(50) NOT NULL ,
  `slug` VARCHAR(50) NOT NULL ,
  `date_created` DATETIME NULL DEFAULT NULL ,
  `date_updated` DATETIME NULL DEFAULT NULL ,
  `plan` ENUM('gratis','dezconto','premium') NOT NULL DEFAULT 'gratis' ,
  `plan_expiration` DATE NULL DEFAULT NULL ,
  `type` ENUM('freelancer','company') NULL DEFAULT 'company' ,
  `profile` ENUM('all','buyer','seller') CHARACTER SET 'latin2' COLLATE 'latin2_hungarian_ci' NOT NULL DEFAULT 'all' ,
  `status` ENUM('active','deleted') NULL DEFAULT 'active' ,
  `image` VARCHAR(30) NULL DEFAULT NULL ,
  `card_image` VARCHAR(30) NULL DEFAULT NULL ,
  `side_image` VARCHAR(30) CHARACTER SET 'latin2' COLLATE 'latin2_hungarian_ci' NULL DEFAULT NULL ,
  `activity` VARCHAR(50) NULL DEFAULT NULL ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `phone` VARCHAR(20) NULL DEFAULT NULL ,
  `phone2` VARCHAR(20) NULL DEFAULT NULL ,
  `email` VARCHAR(50) NULL DEFAULT NULL ,
  `website` VARCHAR(255) NULL DEFAULT NULL ,
  `address_street` VARCHAR(100) NULL DEFAULT NULL ,
  `address_number` VARCHAR(10) NULL DEFAULT NULL ,
  `address_complement` VARCHAR(50) NULL DEFAULT NULL ,
  `about` TEXT NULL DEFAULT NULL ,
  `slides_url` VARCHAR(255) NULL DEFAULT NULL ,
  `slides_embed` TEXT NULL DEFAULT NULL ,
  `video_url` VARCHAR(255) NULL DEFAULT NULL ,
  `link_blog` VARCHAR(255) NULL DEFAULT NULL ,
  `link_youtube` VARCHAR(255) NULL DEFAULT NULL ,
  `link_vimeo` VARCHAR(255) NULL DEFAULT NULL ,
  `link_slideshare` VARCHAR(255) NULL DEFAULT NULL ,
  `link_facebook` VARCHAR(255) NULL DEFAULT NULL ,
  `contact_twitter` VARCHAR(50) NULL DEFAULT NULL ,
  `contact_skype` VARCHAR(50) NULL DEFAULT NULL ,
  `contact_msn` VARCHAR(50) NULL DEFAULT NULL ,
  `contact_gtalk` VARCHAR(50) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_companies_sectors1` (`sector_id` ASC) ,
  INDEX `fk_companies_cities1` (`city_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`products`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `company_id` INT(11) NOT NULL ,
  `name` VARCHAR(30) NOT NULL ,
  `slug` VARCHAR(30) NOT NULL ,
  `date_created` DATETIME NOT NULL ,
  `date_updated` DATETIME NOT NULL ,
  `description` VARCHAR(100) NULL DEFAULT NULL ,
  `website` VARCHAR(255) NULL DEFAULT NULL ,
  `image` VARCHAR(30) NULL DEFAULT NULL ,
  `sort` INT(11) NULL DEFAULT NULL ,
  `about` TEXT NULL DEFAULT NULL ,
  `image_1` VARCHAR(20) NULL DEFAULT NULL ,
  `subtitle_1` VARCHAR(255) NULL DEFAULT NULL ,
  `image_2` VARCHAR(20) NULL DEFAULT NULL ,
  `subtitle_2` VARCHAR(255) NULL DEFAULT NULL ,
  `image_3` VARCHAR(20) NULL DEFAULT NULL ,
  `subtitle_3` VARCHAR(255) NULL DEFAULT NULL ,
  `image_4` VARCHAR(20) NULL DEFAULT NULL ,
  `subtitle_4` VARCHAR(255) NULL DEFAULT NULL ,
  `image_5` VARCHAR(20) NULL DEFAULT NULL ,
  `subtitle_5` VARCHAR(255) NULL DEFAULT NULL ,
  `offer_status` ENUM('inactive','active') NOT NULL DEFAULT 'inactive' ,
  `offer_description` VARCHAR(255) NULL DEFAULT NULL ,
  `offer_date_created` DATETIME NULL DEFAULT NULL ,
  `offer_date_deadline` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_products_companies1` (`company_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`ads`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`ads` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `product_id` INT(11) NOT NULL ,
  `public` ENUM('all','users') NOT NULL DEFAULT 'all' ,
  `status` ENUM('inactive','active') NOT NULL DEFAULT 'inactive' ,
  `date_created` DATETIME NOT NULL ,
  `date_deadline` DATE NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ads_products1` (`product_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`ads_cities`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`ads_cities` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ad_id` INT(11) NOT NULL ,
  `city_id` INT(11) NOT NULL ,
  `views` INT(11) NOT NULL DEFAULT '0' ,
  `clicks` INT(11) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ads_cities_ads1` (`ad_id` ASC) ,
  INDEX `fk_ads_cities_cities1` (`city_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`ads_sectors`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`ads_sectors` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ad_id` INT(11) NOT NULL ,
  `sector_id` INT(11) NOT NULL ,
  `views` INT(11) NOT NULL DEFAULT '0' ,
  `clicks` INT(11) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ads_sectors_ads1` (`ad_id` ASC) ,
  INDEX `fk_ads_sectors_sectors1` (`sector_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `company_id` INT(11) NULL ,
  `login` VARCHAR(50) NOT NULL ,
  `password` VARCHAR(50) NULL DEFAULT NULL ,
  `group` ENUM('deleted','unconfirmed','user','admin') NOT NULL DEFAULT 'unconfirmed' ,
  `date_created` DATE NOT NULL ,
  `date_updated` DATETIME NULL DEFAULT NULL ,
  `options` VARCHAR(10) NOT NULL DEFAULT '1111111111' ,
  `mails` VARCHAR(10) NOT NULL DEFAULT '1111111111' ,
  `name` VARCHAR(50) NOT NULL ,
  `family_name` VARCHAR(50) NOT NULL ,
  `image` VARCHAR(30) NULL DEFAULT NULL ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `job` VARCHAR(40) NULL DEFAULT NULL ,
  `phone` VARCHAR(20) NULL DEFAULT NULL ,
  `cell_phone` VARCHAR(20) NULL DEFAULT NULL ,
  `email` VARCHAR(50) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_users_companies` (`company_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`budgets`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`budgets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `company_id` INT(11) NULL DEFAULT NULL ,
  `message` VARCHAR(255) NULL DEFAULT NULL ,
  `products` VARCHAR(255) NULL DEFAULT NULL ,
  `status` ENUM('notAnswered','answered') NULL DEFAULT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_budgets_companies1` (`company_id` ASC) ,
  INDEX `fk_budgets_users1` (`user_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`businesses`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`businesses` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `company_id` INT(11) NOT NULL ,
  `to_company_id` INT(11) NOT NULL ,
  `rate` ENUM('+','-') NOT NULL DEFAULT '+' ,
  `testimonial` VARCHAR(255) NULL DEFAULT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_testimonials_users1` (`user_id` ASC) ,
  INDEX `fk_testimonials_companies1` (`company_id` ASC) ,
  INDEX `fk_testimonials_companies2` (`to_company_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`contacts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`contacts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `contact_id` INT(11) NOT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_contacts_users1` (`user_id` ASC) ,
  INDEX `fk_contacts_users2` (`contact_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`demands`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`demands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(50) NULL,
  `email` VARCHAR(50) NULL,
  `city_state` VARCHAR(100) NULL,
  `user_id` INT(11) NULL,
  `sector_id` INT(11) NULL,
  `title` VARCHAR(255) NOT NULL ,
  `slug` VARCHAR(255) NOT NULL ,
  `price` ENUM('null','0','500','1k','5k','10k','10k+') NOT NULL DEFAULT 'null' ,
  `description` VARCHAR(255) NULL DEFAULT NULL ,
  `description_2` VARCHAR(255) NULL DEFAULT NULL ,
  `description_3` VARCHAR(255) NULL DEFAULT NULL ,
  `description_4` VARCHAR(255) NULL DEFAULT NULL ,
  `status` ENUM('inactive','active') NOT NULL DEFAULT 'inactive' ,
  `date_created` DATETIME NOT NULL ,
  `date_deadline` DATE NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_demands_users1` (`user_id` ASC) ,
  INDEX `fk_demands_sectors1` (`sector_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`invites`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`invites` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `invited_id` INT(11) NULL DEFAULT NULL ,
  `email` VARCHAR(50) NOT NULL ,
  `name` VARCHAR(100) NOT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_invites_users1` (`user_id` ASC) ,
  INDEX `fk_invites_users2` (`invited_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`messages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `to_user_id` INT(11) NULL DEFAULT NULL ,
  `to_company_id` INT(11) NULL DEFAULT NULL ,
  `type` ENUM('user','company') NOT NULL DEFAULT 'user' ,
  `parent_id` INT(11) NULL DEFAULT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  `body` VARCHAR(255) NULL DEFAULT NULL ,
  `body_2` VARCHAR(255) NULL DEFAULT NULL ,
  `body_3` VARCHAR(255) NULL DEFAULT NULL ,
  `body_4` VARCHAR(255) NULL DEFAULT NULL ,
  `date` DATETIME NOT NULL ,
  `status_sender` ENUM('sent','deleted') NOT NULL DEFAULT 'sent' ,
  `status_reader` ENUM('unread','read','deleted') NOT NULL DEFAULT 'unread' ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_messages_users1` (`user_id` ASC) ,
  INDEX `fk_messages_users2` (`to_user_id` ASC) ,
  INDEX `fk_messages_companies1` (`to_company_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`notifies`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`notifies` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `from_user_id` INT(11) NULL DEFAULT NULL ,
  `from_company_id` INT(11) NULL DEFAULT NULL ,
  `type` ENUM('simple') NOT NULL DEFAULT 'simple' ,
  `message` VARCHAR(255) NULL DEFAULT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_notifies_users1` (`user_id` ASC) ,
  INDEX `fk_notifies_users2` (`from_user_id` ASC) ,
  INDEX `fk_notifies_companies1` (`from_company_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`subscriptions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`subscriptions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `company_id` INT(11) NOT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_subscriptions_users1` (`user_id` ASC) ,
  INDEX `fk_subscriptions_companies1` (`company_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `empreendemia_dev`.`updates`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `empreendemia_dev`.`updates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NULL DEFAULT NULL ,
  `company_id` INT(11) NULL DEFAULT NULL ,
  `product_id` INT(11) NULL DEFAULT NULL ,
  `type` ENUM('message','companyMessage','newProduct','companyDataUpdate','userDataUpdate','sentPositiveTestimonial','sentNegativeTestimonial','sentRecommendation') NOT NULL DEFAULT 'message' ,
  `text` VARCHAR(255) NULL DEFAULT NULL ,
  `date` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_updates_users1` (`user_id` ASC) ,
  INDEX `fk_updates_companies1` (`company_id` ASC) ,
  INDEX `fk_updates_products1` (`product_id` ASC) )
CHARACTER SET utf8 COLLATE utf8_general_ci
ENGINE = InnoDB;

-- -----------------------------------------------------
-- View `empreendemia_dev`.`view_companies_search`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `empreendemia_dev`.`view_companies_search`;
USE `empreendemia_dev`;
CREATE  OR REPLACE VIEW `empreendemia_dev`.`view_companies_search` AS select sql_no_cache `company`.`id` AS `company_id`,concat_ws(' ',`sector`.`name`,`city`.`name`,`region`.`name`,`region`.`symbol`,`company`.`name`,`company`.`activity`,`company`.`address_street`,`company`.`description`,group_concat(`product`.`name` separator ','),group_concat(`product`.`description` separator ',')) AS `company_text` from ((((`empreendemia_dev`.`companies` `company` join `empreendemia_dev`.`sectors` `sector` on((`company`.`sector_id` = `sector`.`id`))) join `empreendemia_dev`.`cities` `city` on((`company`.`city_id` = `city`.`id`))) join `empreendemia_dev`.`regions` `region` on((`city`.`region_id` = `region`.`id`))) left join `empreendemia_dev`.`products` `product` on((`product`.`company_id` = `company`.`id`))) where (`company`.`status` = 'active') group by `company`.`id`;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
