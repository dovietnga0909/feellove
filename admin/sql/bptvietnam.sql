--
-- Database: `bptvietnam`
--

-- --------------------------------------------------------

--
-- Table structure for table `airlines`
--

CREATE TABLE `airlines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `code` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `airlines` (`id`, `name`, `code`) VALUES
(1, 'Vietnam Airlines', 'VN'),
(2, ' Jetstar Pacific', 'BL'),
(3, 'VietJet Air', 'VJ');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `birthday` date NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `address` varchar(500) NOT NULL,
  `travel_type` tinyint(4) NOT NULL,
  `occasion` tinyint(4) NOT NULL,
  `happy_or_not` tinyint(4) NOT NULL,
  `budget` tinyint(4) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `ip_address` varchar(10) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `latitude` float(10,6) NOT NULL,
  `longitude` float(10,6) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `is_top_hotel` tinyint(4) NOT NULL,
  `destination_code` varchar(3) NOT NULL,
  `is_flight_destination` tinyint(4) NOT NULL,
  `is_flight_group` tinyint(4) NOT NULL,
  `flight_tips` varchar(5000) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `url_title_history` varchar(200) NOT NULL,
  `position` int(11) NOT NULL,
  `position_flight` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `destination_hotel`
--

CREATE TABLE `destination_hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `type_id` varchar(50) NOT NULL,
  `group_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `flight_basic_prices`
--

CREATE TABLE `flight_basic_prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `airline_id` int(11) NOT NULL,
  `flight_route_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `flight_routes`
--

CREATE TABLE `flight_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_destination_id` int(11) NOT NULL,
  `to_destination_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_show_vietnam_flight_page` tinyint(4) NOT NULL,
  `is_show_flight_destination_page` tinyint(4) NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `flight_users`
--

CREATE TABLE `flight_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_booking_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `birth_day` date DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) NOT NULL,
  `star` tinyint(4) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `url_title_history` varchar(200) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `default_cancellation` int(11) NOT NULL,
  `facilities` varchar(500) NOT NULL,
  `latitude` float(10,6) NOT NULL,
  `longitude` float(10,6) NOT NULL,
  `infant_age_util` tinyint(4) NOT NULL,
  `children_age_to` tinyint(4) NOT NULL,
  `extra_bed_requires_from` tinyint(4) NOT NULL,
  `children_stay_free` tinyint(4) NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `infants_policy` varchar(200) NOT NULL,
  `children_policy` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `name` (`name`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hotel_facilities`
--

CREATE TABLE `hotel_facilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(4) NOT NULL,
  `joining_date` datetime NOT NULL,
  `phone` varchar(25) CHARACTER SET utf8 NOT NULL,
  `fax` varchar(25) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `address` varchar(300) CHARACTER SET utf8 NOT NULL,
  `website` varchar(200) CHARACTER SET utf8 NOT NULL,
  `payment_type` tinyint(4) NOT NULL,
  `service_type` tinyint(4) NOT NULL,
  `bank_account_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `bank_account_number` varchar(20) CHARACTER SET utf8 NOT NULL,
  `bank_branch_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `accountant_contact_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `accountant_contact_phone` varchar(25) CHARACTER SET utf8 NOT NULL,
  `accountant_contact_email` varchar(25) CHARACTER SET utf8 NOT NULL,
  `reservation_contact_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `reservation_contact_phone` varchar(25) CHARACTER SET utf8 NOT NULL,
  `reservation_contact_email` varchar(25) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `caption` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` tinyint(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

CREATE TABLE `rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `date_modified` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `role_rights`
--

CREATE TABLE `role_rights` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `right_id` int(11) NOT NULL,
  `privilege` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room_photos`
--

CREATE TABLE `room_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `is_main_photo` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `number_of_rooms` tinyint(4) NOT NULL,
  `max_extra_beds` tinyint(4) NOT NULL,
  `max_occupancy` tinyint(4) NOT NULL,
  `max_children` tinyint(4) NOT NULL,
  `rack_rate` int(11) NOT NULL,
  `min_rate` int(11) NOT NULL,
  `minimum_room_size` tinyint(4) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `view_id` tinyint(4) NOT NULL,
  `bed_config` int(11) NOT NULL,
  `facilities` varchar(500) NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `partner_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `signature` varchar(500) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `last_login` int(11) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `remember_code` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `username_2` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `advertises`
--

CREATE TABLE IF NOT EXISTS `advertises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(4) NOT NULL,
  `data_type` tinyint(4) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `week_day` int(11) NOT NULL,
  `display_on` int(11) NOT NULL,
  `link` text CHARACTER SET utf8 NOT NULL,
  `show_time_from` time NOT NULL,
  `show_time_to` time NOT NULL,
  `all_hotel_des` tinyint(4) NOT NULL,
  `all_flight_des` tinyint(4) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `ad_destinations`
--

CREATE TABLE IF NOT EXISTS `ad_destinations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advertise_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `module` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ad_photos`
--

CREATE TABLE IF NOT EXISTS `ad_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `advertise_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `display_on` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cancellations`
--

CREATE TABLE IF NOT EXISTS `cancellations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `fit` tinyint(4) NOT NULL,
  `fit_cutoff` tinyint(4) NOT NULL,
  `git_cutoff` tinyint(4) NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE IF NOT EXISTS `promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `cancellation_id` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `offer` varchar(300) CHARACTER SET utf8 NOT NULL,
  `promotion_type` tinyint(4) NOT NULL,
  `minimum_stay` tinyint(4) NOT NULL,
  `maximum_stay` tinyint(4) NOT NULL,
  `book_date_from` date NOT NULL,
  `book_date_to` date NOT NULL,
  `stay_date_from` date NOT NULL,
  `stay_date_to` date NOT NULL,
  `book_time_from` time NOT NULL,
  `book_time_to` time NOT NULL,
  `day_before_check_in` int(11) NOT NULL,
  `display_on` int(11) NOT NULL,
  `check_in_on` int(11) NOT NULL,
  `discount_type` tinyint(4) NOT NULL,
  `apply_on` tinyint(4) NOT NULL,
  `minimum_room` tinyint(4) NOT NULL,
  `recurring_benefit` tinyint(4) NOT NULL,
  `get_1` int(11) NOT NULL,
  `get_2` int(11) NOT NULL,
  `get_3` int(11) NOT NULL,
  `get_4` int(11) NOT NULL,
  `get_5` int(11) NOT NULL,
  `get_6` int(11) NOT NULL,
  `get_7` int(11) NOT NULL,
  `room_type` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `promotion_room_types`
--

CREATE TABLE IF NOT EXISTS `promotion_room_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `offer_note` varchar(300) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `room_rates`
--

CREATE TABLE IF NOT EXISTS `room_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `full_occupancy_rate` int(11) DEFAULT NULL,
  `triple_rate` int(11) DEFAULT NULL,
  `double_rate` int(11) DEFAULT NULL,
  `single_rate` int(11) DEFAULT NULL,
  `additional_person_rate` int(11) DEFAULT NULL,
  `extra_bed_rate` int(11) DEFAULT NULL,
  `description` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `has_surcharge` tinyint(4) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `surcharges`
--

CREATE TABLE IF NOT EXISTS `surcharges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `week_day` tinyint(4) NOT NULL,
  `charge_type` tinyint(4) NOT NULL,
  `amount` int(11) NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `destination_places`
--

CREATE TABLE `destination_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destination_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `hotel_price_froms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `price_origin` int(11) NOT NULL,
  `price_from` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `name` varchar(200) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `news_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `news_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `width` smallint(6) NOT NULL,
  `height` smallint(6) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------
ALTER TABLE `photos` ADD `height` SMALLINT NOT NULL AFTER `name`;
ALTER TABLE `photos` ADD `width` SMALLINT NOT NULL AFTER `name`;
ALTER TABLE `hotels` ADD `check_out` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `children_stay_free`;
ALTER TABLE `hotels` ADD `check_in` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `children_stay_free`;

ALTER TABLE `facilities` ADD `is_important` TINYINT NOT NULL AFTER `hotel_id`;
ALTER TABLE `facilities` ADD `position` INT NOT NULL AFTER `is_important`;


-- --------------------------------------------------------


ALTER TABLE `facilities` ADD `cruise_id` INT NOT NULL AFTER `hotel_id`;

-- --------------------------------------------------------

ALTER TABLE `photos` ADD `cruise_id` INT NOT NULL AFTER `hotel_id`, ADD `tour_id` INT NOT NULL AFTER `cruise_id`;
ALTER TABLE `room_photos` ADD `cabin_id` INT NOT NULL AFTER `room_id`;

ALTER TABLE `flight_users` ADD `checked_baggage` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_reference` text CHARACTER SET utf8 NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_booking_id` int(11) NOT NULL,
  `lasted_payment_code` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `customer_bookings` CHANGE `flight_short_desc` `flight_short_desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

CREATE TABLE IF NOT EXISTS `bpv_promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) CHARACTER SET utf8 NOT NULL,
  `url_title` varchar(500) NOT NULL,
  `code` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `expired_date` date NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `init_nr_booked` int(11) NOT NULL,
  `max_nr_booked` int(11) NOT NULL,
  `current_nr_booked` int(11) NOT NULL,
  `hotel_discount_type` tinyint(4) NOT NULL,
  `hotel_get` int(11) NOT NULL,
  `hotel_get_max` int(11) NOT NULL,
  `flight_discount_type` tinyint(4) NOT NULL,
  `flight_get` int(11) NOT NULL,
  `flight_get_max` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `bpv_promotion_hotels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpv_promotion_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `amount` int(11) NOT NULL,
  `expired_date` date NOT NULL,
  `delivered` tinyint(4) NOT NULL,
  `used` tinyint(4) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_used_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------


ALTER TABLE `hotels` ADD `extra_cancellation` TEXT NOT NULL AFTER `children_policy`;


-------- Update on 19/05/2014: Cruisea and Tour tables --------------------------------------------------------

ALTER TABLE `photos` ADD `tour_photo_type` TINYINT NOT NULL AFTER `tour_id` ;

--
-- Table structure for table `tours`
--

CREATE TABLE `tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `short_name` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `url_title_history` varchar(200) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `rate_type` tinyint(4) NOT NULL,
  `duration` tinyint(4) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `tour_highlight` varchar(1000) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `position` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `cruises`
--

CREATE TABLE `cruises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) NOT NULL,
  `star` float NOT NULL,
  `description` varchar(5000) NOT NULL,
  `cruise_type` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `url_title_history` varchar(200) NOT NULL,
  `partner_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `default_cancellation` int(11) NOT NULL,
  `facilities` varchar(500) NOT NULL,
  `latitude` float(10,6) NOT NULL,
  `longitude` float(10,6) NOT NULL,
  `infant_age_util` tinyint(4) NOT NULL,
  `children_age_to` tinyint(4) NOT NULL,
  `extra_bed_requires_from` tinyint(4) NOT NULL,
  `children_stay_free` tinyint(4) NOT NULL,
  `extra_cancellation` text NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `infants_policy` varchar(200) NOT NULL,
  `children_policy` varchar(200) NOT NULL,
  `check_out` varchar(200) NOT NULL,
  `check_in` varchar(200) NOT NULL,
  `shuttle_bus` varchar(200) NOT NULL,
  `guide` varchar(200) DEFAULT NULL,
  `cancellation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  FULLTEXT KEY `name_2` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `cruise_facilities`
--

CREATE TABLE `cruise_facilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cruise_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `cabins`
--

CREATE TABLE `cabins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `picture` varchar(200) NOT NULL,
  `number_of_cabins` tinyint(4) NOT NULL,
  `max_extra_beds` tinyint(4) NOT NULL,
  `max_occupancy` tinyint(4) NOT NULL,
  `max_children` tinyint(4) NOT NULL,
  `rack_rate` int(11) NOT NULL,
  `min_rate` int(11) NOT NULL,
  `included_breakfast` tinyint(4) NOT NULL,
  `minimum_cabin_size` FLOAT NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `bed_config` int(11) NOT NULL,
  `facilities` varchar(500) NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `accommodations`
--

CREATE TABLE IF NOT EXISTS `accommodations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `cruise_cabin_id` int(11) NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `itineraries`
--

CREATE TABLE IF NOT EXISTS `itineraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `accommodation` text CHARACTER SET utf8 NOT NULL,
  `activities` text CHARACTER SET utf8 NOT NULL,
  `notes` text CHARACTER SET utf8 NOT NULL,
  `meals` varchar(50) CHARACTER SET utf8 NOT NULL,
  `photos` varchar(500) NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_modified` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `destination_tours`
--

CREATE TABLE `destination_tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `hidden` tinyint(4) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-------- Update on 22/05/2014: Tour Price tables --------------------------------------------------------

--
-- Table structure for table `tour_rate_actions`
--

CREATE TABLE `tour_rate_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `week_day` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `tour_room_rate_actions`
--

CREATE TABLE `tour_room_rate_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_rate_action_id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `1_pax_rate` int(11) NOT NULL,
  `2_pax_rate` int(11) NOT NULL,
  `3_pax_rate` int(11) NOT NULL,
  `4_pax_rate` int(11) NOT NULL,
  `5_pax_rate` int(11) NOT NULL,
  `children_rate` int(11) NOT NULL,
  `infant_rate` int(11) NOT NULL,
  `1_pax_net` int(11) NOT NULL,
  `2_pax_net` int(11) NOT NULL,
  `3_pax_net` int(11) NOT NULL,
  `4_pax_net` int(11) NOT NULL,
  `5_pax_net` int(11) NOT NULL,
  `children_net` int(11) NOT NULL,
  `infant_net` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `tour_rates`
--

CREATE TABLE `tour_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accommodation_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `1_pax_rate` int(11) NOT NULL,
  `2_pax_rate` int(11) NOT NULL,
  `3_pax_rate` int(11) NOT NULL,
  `4_pax_rate` int(11) NOT NULL,
  `5_pax_rate` int(11) NOT NULL,
  `children_rate` int(11) NOT NULL,
  `infant_rate` int(11) NOT NULL,
  `1_pax_net` int(11) NOT NULL,
  `2_pax_net` int(11) NOT NULL,
  `3_pax_net` int(11) NOT NULL,
  `4_pax_net` int(11) NOT NULL,
  `5_pax_net` int(11) NOT NULL,
  `children_net` int(11) NOT NULL,
  `infant_net` int(11) NOT NULL,
  `has_surcharge` tinyint(4) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `tour_price_froms`
--

CREATE TABLE `tour_price_froms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `price_origin` int(11) NOT NULL,
  `price_from` int(11) NOT NULL,
  `date` date NOT NULL,
  `range_index` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `promotion_tours`
--

CREATE TABLE `promotion_tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `offer_note` varchar(300) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


--
-- Table structure for table `tour_promotion_details`
--

CREATE TABLE `tour_promotion_details` (
  `id` int(11) NOT NULL,
  `tour_promotion_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL,
  `accommodation_id` int(11) NOT NULL,
  `offer_note` varchar(300) NOT NULL,
  `offer_rate` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `surcharge_tours`
--

CREATE TABLE `surcharge_tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surcharge_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `charge_amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `surcharges` ADD `cruise_id` INT NOT NULL AFTER `hotel_id`;
ALTER TABLE `hotels` CHANGE `star` `star` FLOAT( 4 ) NOT NULL;

ALTER TABLE `news` ADD `picture` VARCHAR( 200 ) NOT NULL AFTER `name`;
ALTER TABLE `news_photos` ADD `is_main_photo` TINYINT NOT NULL AFTER `news_id`;

ALTER TABLE `customer_bookings` ADD `tour_id` INT NOT NULL AFTER `hotel_id` ;

-------- Update on 06/06/2014: Tour Price tables --------------------------------------------------------

ALTER TABLE `tour_rates` ADD `single_sup_rate` INT NOT NULL AFTER `infant_net`;
ALTER TABLE `tour_rates` ADD `single_sup_net` INT NOT NULL AFTER `infant_net`;
ALTER TABLE `tour_room_rate_actions` ADD `single_sup_net` INT NOT NULL AFTER `infant_net`;
ALTER TABLE `tour_room_rate_actions` ADD `single_sup_rate` INT NOT NULL AFTER `infant_net`;

ALTER TABLE `tours` ADD `service_includes` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `tour_highlight`;
ALTER TABLE `tours` ADD `service_excludes` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `tour_highlight`;


-------- Update 09/06/2014 -------

ALTER TABLE `promotions` ADD `cruise_id` INT NOT NULL AFTER `hotel_id`;

ALTER TABLE `promotion_tours` ADD `get` INT NOT NULL AFTER `offer_note`;

ALTER TABLE `tour_promotion_details` CHANGE `offer_rate` `get` INT( 11 ) NOT NULL;

ALTER TABLE `tour_promotion_details` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `tour_promotion_details` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `id` );


-------- Update 12/06/2014 -------

ALTER TABLE `surcharges` ADD `adult_amount` INT NOT NULL AFTER `amount`;
ALTER TABLE `surcharges` ADD `children_amount` INT NOT NULL AFTER `adult_amount`;

ALTER TABLE `surcharge_tours` CHANGE `charge_amount` `adult_amount` INT( 11 ) NOT NULL;
ALTER TABLE `surcharge_tours` ADD `children_amount` INT NOT NULL AFTER `adult_amount`;

ALTER TABLE `tours` ADD `notes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `service_includes`;

ALTER TABLE `surcharges` ADD `apply_all` TINYINT NOT NULL AFTER `description` ;


-------- Update 18/06/2014 -------

--
-- Table structure for table `reviews`
--


CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `customer_type` int(11) NOT NULL,
  `customer_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `customer_city` int(11) NOT NULL,
  `total_score` decimal(10,1) NOT NULL,
  `review_date` datetime NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `review_content` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `vote_up` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `review_score`
--

CREATE TABLE IF NOT EXISTS `review_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review_id` int(11) NOT NULL,
  `score` decimal(10,1) NOT NULL,
  `score_type` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `hotels` ADD `review_number` SMALLINT NOT NULL AFTER `check_out` ,
ADD `review_score` DECIMAL( 10, 1 ) NOT NULL AFTER `review_number` ;

ALTER TABLE `tours` ADD `review_number` SMALLINT NOT NULL AFTER `notes` ,
ADD `review_score` DECIMAL( 10, 1 ) NOT NULL AFTER `review_number` ;

ALTER TABLE `cruises` ADD `review_number` SMALLINT NOT NULL AFTER `cancellation_id` ,
ADD `review_score` DECIMAL( 10, 1 ) NOT NULL AFTER `review_number` ;


-------- Update 30/06/2014 -------

ALTER TABLE `bpv_promotions` ADD `cruise_discount_type` TINYINT NOT NULL AFTER `flight_get_max` ,
ADD `cruise_get` INT NOT NULL AFTER `cruise_discount_type` ,
ADD `cruise_get_max` INT NOT NULL AFTER `cruise_get` ;

--
-- Table structure for table `bpv_promotion_cruises`
--

CREATE TABLE `bpv_promotion_cruises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpv_promotion_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-------- Update 02/07/2014 -------

ALTER TABLE `partners` CHANGE `accountant_contact_name` `sale_contact_name` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `partners` CHANGE `accountant_contact_phone` `sale_contact_phone` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `partners` CHANGE `accountant_contact_email` `sale_contact_email` VARCHAR( 25 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `partners` CHANGE `sale_contact_email` `sale_contact_email` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE `partners` CHANGE `reservation_contact_email` `reservation_contact_email` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

ALTER TABLE `partners` ADD `skype_contact` VARCHAR( 100 ) NOT NULL AFTER `reservation_contact_email` ,
ADD `yahoo_contact` VARCHAR( 100 ) NOT NULL AFTER `skype_contact` ;

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `size` float NOT NULL,
  `description` varchar(500) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-------- Update 09/07/2014 -------

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `password` text CHARACTER SET utf8,
  `authorize` text CHARACTER SET utf8,
  `active` tinyint(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;


-------- Update 17/07/2014 -------
ALTER TABLE `cabins` ADD `included_vat` TINYINT NOT NULL AFTER `included_breakfast` ;


---------Update 05/08/2014 -------

ALTER TABLE `accounts` ADD `username` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
ADD `register` int(2) NOT NULL,
ADD `user_modified_id` int(11) NOT NULL,
ADD `date_modified` datetime DEFAULT NULL,
ADD `deleted` tinyint(1) NOT NULL;

---------Update 06/08/2014 -------

ALTER TABLE `accounts` ADD `date_created` datetime NULL;
UPDATE `accounts` SET `date_created` = NOW() WHERE `date_created` IS NULL;
UPDATE `accounts` SET `register` = 1 WHERE `phone` IS NULL;
UPDATE `accounts` SET `register` = 2 WHERE `phone` IS NOT NULL;
UPDATE `accounts` SET `date_modified` = NOW() WHERE `date_modified` IS NULL;


---------Update 07/08/2014 -------

ALTER TABLE `destinations` ADD `keywords` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `name` ,
ADD FULLTEXT (
`keywords`
);

ALTER TABLE `hotels` ADD `keywords` VARCHAR( 1000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `name` ,
ADD FULLTEXT (
`keywords`
);


--------- Update 19/08/2014 : toanlk -------

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `is_hot` tinyint(4) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `link` varchar(500) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE `tour_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `destination_tours` ADD `is_land_tour` TINYINT NOT NULL AFTER `destination_id`;

ALTER TABLE `tours` ADD `departure_type` TINYINT NOT NULL AFTER `review_score`;
ALTER TABLE `tours` ADD `departure_date_type` TINYINT NOT NULL AFTER `departure_type`;
ALTER TABLE `tours` ADD `departure_specific_date` VARCHAR( 500 ) NOT NULL AFTER `departure_type`;
ALTER TABLE `tours` ADD `is_outbound` TINYINT NOT NULL AFTER `review_score` ;

CREATE TABLE `tour_departures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destination_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `service_includes` varchar(5000) NOT NULL,
  `service_excludes` varchar(5000) NOT NULL,
  `departure_date_type` tinyint(4) NOT NULL,
  `departure_specific_date` varchar(1000) NOT NULL,
  `position` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `tour_departure_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_id` int(11) NOT NULL,
  `tour_departure_id` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `weekdays` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `destination_tours` ADD `is_show_on_route` TINYINT NOT NULL AFTER `is_land_tour`;

ALTER TABLE `itineraries` ADD `transportations` VARCHAR( 50 ) NOT NULL AFTER `meals`;

UPDATE `destination_tours` SET `is_show_on_route` = 1;

--
-- Table structure for table `tour_departure_itineraries`
--

CREATE TABLE `tour_departure_itineraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `itinerary_id` int(11) NOT NULL,
  `tour_departure_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `tour_departure_rates`
--

CREATE TABLE `tour_departure_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tour_departure_id` int(11) NOT NULL,
  `tour_rate_action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


---------Update 22/08/2014 -------

ALTER TABLE `destinations` ADD `marketing_title` varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `keywords`,
ADD `description_short` text CHARACTER SET utf8,
ADD `travel_tip` text CHARACTER SET utf8,
ADD `is_tour_highlight_destination` TINYINT(4) NOT NULL,
ADD `is_tour_destination_group` TINYINT(4) NOT NULL,
ADD `is_tour_includes_all_children_destination` TINYINT(4) NOT NULL,
ADD `is_tour_departure_destination` TINYINT(4) NOT NULL;

CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `destination_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photos` varchar(500) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `date_modified` datetime NOT NULL,
  `date_created` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `photos` ADD `destination_id` INT(11) NOT NULL;

---------Update 25/08/2014 -------

ALTER TABLE `cancellations` ADD `service_type` INT(11) NOT NULL AFTER `content`

---------Update 25/08/2014 khuyenpv --------
ALTER TABLE `flight_users` ADD `nationality` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
ADD `passport` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
ADD `passportexp` DATE NULL

--------- Update 27/08/2014 : toanlk -------

ALTER TABLE `tour_rates` ADD `tour_departure_id` INT NOT NULL AFTER `date`;

ALTER TABLE `tour_price_froms` ADD `tour_departure_id` INT NOT NULL AFTER `promotion_id`;

ALTER TABLE `tours` ADD `cancellation_id` INT NOT NULL AFTER `departure_date_type` ,
ADD `infant_age_util` TINYINT NOT NULL AFTER `cancellation_id` ,
ADD `children_age_to` TINYINT NOT NULL AFTER `infant_age_util` ,
ADD `infants_policy` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `children_age_to`;

ALTER TABLE `tours` ADD `children_policy` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `infants_policy` ,
ADD `extra_cancellation` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `children_policy`;

ALTER TABLE `promotions` ADD `tour_id` INT NOT NULL AFTER `cruise_id`;

ALTER TABLE `promotion_tours` ADD `tour_departure_id` INT NOT NULL AFTER `cruise_id`;

ALTER TABLE `tour_promotion_details` ADD `tour_departure_id` INT NOT NULL AFTER `accommodation_id`;

--------- Update 29/08/2014 -------

ALTER TABLE `advertises` ADD `all_tour_des` TINYINT(4)  NOT NULL,
ADD `all_tour_cat_des` TINYINT(4)  NOT NULL,
ADD `position`	INT(11) NOT NULL;

CREATE TABLE IF NOT EXISTS `ad_tours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advertise_id`  int(11) NOT NULL,
  `category_id`  int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--------- Update 08/09/2014 -------

ALTER TABLE `categories` ADD `picture` varchar(255)  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `link`;


---- Update 09.09.2014 by Khuyenpv -----
ALTER TABLE `tours` DROP `rate_type`;
ALTER TABLE `tours` ADD `code` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `short_name`;

ALTER TABLE `destinations` ADD `nr_tour_domistic` INT NOT NULL ,
ADD `nr_tour_outbound` INT NOT NULL;

ALTER TABLE `tours` ADD `routes` TEXT NOT NULL AFTER `extra_cancellation`;

--------- Update 12/09/2014 by toanlk -------

ALTER TABLE `news` ADD `category` INT NOT NULL AFTER `type`;

ALTER TABLE `categories` ADD `url_title` VARCHAR(200) NOT NULL AFTER `name`;


---------- Update 15.09.2014 by khuyenpv -------------
ALTER TABLE `destinations` ADD `is_outbound` TINYINT NOT NULL;
RENAME TABLE `ad_tours` TO `ad_tour_categories` ;

---------- Update 16.09.2014 by nguyenson -------------
ALTER TABLE `users` ADD `avatar` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `skype_acc`;


---------- Update 17.09.2014 by khuyenpv --------------
ALTER TABLE `bpv_promotions` ADD `tour_discount_type` TINYINT NOT NULL AFTER `cruise_get_max` ,
ADD `tour_get` INT NOT NULL AFTER `tour_discount_type` ,
ADD `tour_get_max` INT NOT NULL AFTER `tour_get`;

CREATE TABLE IF NOT EXISTS `bpv_promotion_tours` (
`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`bpv_promotion_id` int( 11 ) NOT NULL ,
`destination_id` int( 11 ) NOT NULL ,
`tour_id` int( 11 ) NOT NULL ,
`tour_get` int( 11 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM DEFAULT CHARSET = latin1 AUTO_INCREMENT =1;


---------- Update 18/09/2014 by toanlk --------------

ALTER TABLE `news` CHANGE `content` `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `news` ADD `short_description` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `content`,
ADD `source` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `short_description`;

ALTER TABLE `news` ADD `link` VARCHAR(200) NOT NULL AFTER `source`;

---------- Update 19.09.2014 by khuyenpv ------------
ALTER TABLE `airlines` ADD `is_domistic` TINYINT NOT NULL ,
ADD `description` TEXT NOT NULL ,
ADD `deleted` TINYINT NOT NULL ,
ADD `user_created_id` INT NOT NULL ,
ADD `user_modified_id` INT NOT NULL ,
ADD `date_created` DATETIME NOT NULL ,
ADD `date_modified` DATETIME NOT NULL;

ALTER TABLE `airlines` ADD `position` INT NOT NULL AFTER `description`;
ALTER TABLE `airlines` ADD `url_title` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `name`;

CREATE TABLE IF NOT EXISTS `flight_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL,
  `name` varchar(200) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `position` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `flight_categories` ADD `description` TEXT NOT NULL AFTER `end_date`;

CREATE TABLE IF NOT EXISTS `flight_photos` (
`id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`name` varchar( 300 ) NOT NULL ,
`airline_id` int( 11 ) NOT NULL ,
`flight_category_id` int( 11 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM DEFAULT CHARSET = latin1 AUTO_INCREMENT =1;

---------- Update 25/09/2014 by toanlk --------------

ALTER TABLE `tour_price_froms` ADD `mid_range_index` TINYINT NOT NULL AFTER `range_index`;

------ update 26/09/2014 by Tin ---
ALTER TABLE `vouchers` ADD `status` TINYINT NOT NULL AFTER `used`;
ALTER TABLE `vouchers` ADD `log` TEXT NOT NULL AFTER `status`;

------ update 26/09/2014 by Khuyepv-----
ALTER TABLE `ad_photos` ADD `version` TINYINT NOT NULL AFTER `display_on`;

---------- Update 02/10/2014 by toanlk --------------

ALTER TABLE `tours` ADD `night` TINYINT NOT NULL AFTER `duration`;

------ update 03/10/2014 by NguyenSon-----
ALTER TABLE `users` ADD `allow_assign_request` TINYINT(4) NOT NULL AFTER `avatar`;


------ update 21.10.2014 by khuyenpv ----
ALTER TABLE `service_reservations` ADD `airport_from` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `flight_class` ,
ADD `airport_to` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `airport_from` ,
ADD `flight_way` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `airport_to` ,
ADD `flight_type` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `flight_way` ,
ADD `flight_pnr` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `flight_type` ,
ADD `adt_price` INT NOT NULL AFTER `flight_pnr` ,
ADD `chd_price` INT NOT NULL AFTER `adt_price` ,
ADD `inf_price` INT NOT NULL AFTER `chd_price` ,
ADD `tax_fee` INT NOT NULL AFTER `inf_price` ,
ADD `baggage_kg` INT NOT NULL AFTER `tax_fee`;

ALTER TABLE `customer_bookings` ADD `flight_from` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `flight_short_desc` ,
ADD `flight_to` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `flight_from` ,
ADD `flight_depart` DATE NOT NULL AFTER `flight_to` ,
ADD `flight_return` DATE NULL AFTER `flight_depart`;

ALTER TABLE `service_reservations` ADD `airline_name` VARCHAR( 300 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `airline`;

ALTER TABLE `customer_bookings` ADD `vnisc_boooking_id` INT NOT NULL AFTER `flight_return`;

ALTER TABLE `customer_bookings` ADD `vnisc_booking_code` VARCHAR( 100 ) NOT NULL AFTER `vnisc_boooking_id`;


ALTER TABLE `service_reservations` ADD `flight_from_code` VARCHAR( 10 ) NOT NULL AFTER `flight_to` ,
ADD `flight_to_code` VARCHAR( 10 ) NOT NULL AFTER `flight_from_code`;

ALTER TABLE `customer_bookings` ADD `is_flight_domistic` TINYINT NOT NULL AFTER `flight_return`;

--- update 27.10.2014 by Khuyenpv ---
ALTER TABLE `service_reservations` ADD `fare_rule_short` TEXT NOT NULL AFTER `fare_rules`;
ALTER TABLE `service_reservations` CHANGE `fare_rule_short` `fare_rule_short` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `flight_users` ADD `date_created` DATETIME NOT NULL ,
ADD `date_modified` DATETIME NOT NULL ,
ADD `user_created_id` INT NOT NULL ,
ADD `user_modified_id` INT NOT NULL ,
ADD `deleted` TINYINT NOT NULL;

--- update 26/11/2014 ----

ALTER TABLE `bpv_promotion_cruises` ADD `cruise_get` INT NOT NULL;

ALTER TABLE `flight_users` ADD `ticket_number` VARCHAR( 200 ) NOT NULL AFTER `checked_baggage`;

--- update 01/02/2015 by NguyenSon----

CREATE TABLE IF NOT EXISTS `newsletters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `template_type` tinyint(4) DEFAULT NULL,
  `customer_gender` tinyint(4) DEFAULT NULL,
  `customer_type` int(11) DEFAULT NULL,
  `content` text,
  `status` tinyint(4) DEFAULT NULL,
  `nr_total_email` int(11) DEFAULT NULL,
  `nr_send_success` int(11) DEFAULT NULL,
  `nr_send_false` int(11) DEFAULT NULL,
  `nr_view_email` int(11) DEFAULT NULL,
  `nr_view_email_online` int(11) DEFAULT NULL,
  `user_created_id` int(11) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `user_modified_id` int(11) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `user_send_id` int(11) NOT NULL,
  `user_stop_id` int(11) NOT NULL,
  `date_last_send` datetime DEFAULT NULL,
  `date_stop` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `newsletter_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) NOT NULL,
  `newsletter_id` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `user_created_id` int(11) NOT NULL,
  `user_modified_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `newsletter_promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletter_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `tour_id` int(11) NOT NULL,
  `cruise_id` int(11) NOT NULL,
  `promotion_name` varchar(255) DEFAULT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `price_origin` int(11) DEFAULT NULL,
  `price_from` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--------- update 25/105/2015 by toanlk ---------

ALTER TABLE `bpv_promotions` ADD `is_multiple_time` TINYINT NOT NULL DEFAULT '1' AFTER `tour_get_max`;

CREATE TABLE IF NOT EXISTS `bpv_promotion_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bpv_promotion_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--------- END ---------
