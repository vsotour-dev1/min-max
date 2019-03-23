<?php

if (!defined('JOMRES_INSTALLER'))
    exit;
$query = "
ALTER TABLE `#__jomres_jintour_profiles` 
ADD  `spaces_guest_types` VARCHAR( 1024 ) NULL DEFAULT NULL AFTER  `tax_rate` ,
ADD  `spaces_table` VARCHAR( 1024 ) NULL DEFAULT NULL AFTER  `spaces_guest_types`;
ALTER TABLE `#__jomres_jintour_profiles` ADD `specific_date` INT( 255 ) NOT NULL ,
ADD `calendar_dates` VARCHAR( 1024 ) NOT NULL ;
ALTER TABLE `#__jomres_jintour_tours` ADD `tour_profile_id` INT( 255 ) NOT NULL AFTER `published` ;
ALTER TABLE `#__jomres_jintour_tours` ADD `spaces_available` VARCHAR( 1024 ) NOT NULL AFTER `spaces_available_adults` ;
ALTER TABLE `#__jomres_jintour_tours` ADD `prices` VARCHAR( 1024 ) NOT NULL AFTER `price_kids` ;
ALTER TABLE `#__jomres_jintour_tours` ADD `spaces_min` VARCHAR( 255 ) NOT NULL AFTER `spaces_available_kids` ;
ALTER TABLE `#__jomres_jintour_tours` ADD `spaces_max` VARCHAR( 255 ) NOT NULL AFTER `spaces_min` ;
ALTER TABLE `#__jomres_jintour_tour_bookings` ADD `spaces` VARCHAR( 1024 ) NULL DEFAULT NULL AFTER `spaces_kids` ;
ALTER TABLE  `#__jomres_jintour_profiles` ADD  `main_price` VARCHAR( 255 ) NOT NULL ;
ALTER TABLE `#__jomres_propertys` CHANGE `lat` `lat` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
ALTER TABLE `#__jomres_propertys` CHANGE `long` `long` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ;
";

doInsertSql($query,"");