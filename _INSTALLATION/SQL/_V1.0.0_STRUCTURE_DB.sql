SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


--
-- Database: `bidding_db`
--
CREATE DATABASE IF NOT EXISTS `bidding_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bidding_db`;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempt`
--

CREATE TABLE IF NOT EXISTS `login_attempt` (
    `login_attempt_id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(`login_attempt_id`),
    `user_id` int(11) NOT NULL DEFAULT 0,
    `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `locationIP` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
    `user_id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(`user_id`),
    `username` varchar(200) NOT NULL DEFAULT '',
    UNIQUE(`username`),
    `password` varchar(500) NOT NULL DEFAULT '',
    `user_type` varchar(20) NOT NULL DEFAULT '',

    `locationIP` varchar(50) NOT NULL DEFAULT '',
    `updatedBy` varchar(50) NOT NULL DEFAULT '',
    `updatedDT` datetime NOT NULL DEFAULT '1990-01-01',
    `createdBy` varchar(50) NOT NULL DEFAULT '',
    `createdDT` datetime NOT NULL DEFAULT '1990-01-01'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--
DROP TABLE IF EXISTS `member`;
CREATE TABLE IF NOT EXISTS `member` (
    `member_id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(`member_id`),
    `user_id` int(11) NOT NULL DEFAULT 0,
    `name` varchar(500) NOT NULL DEFAULT '',
    `contact` varchar(50) NOT NULL DEFAULT '',
    `address1` varchar(500) NOT NULL DEFAULT '',
    `address2` varchar(500) NOT NULL DEFAULT '',

    `locationIP` varchar(50) NOT NULL DEFAULT '',
    `updatedBy` varchar(50) NOT NULL DEFAULT '',
    `updatedDT` datetime NOT NULL DEFAULT '1990-01-01',
    `createdBy` varchar(50) NOT NULL DEFAULT '',
    `createdDT` datetime NOT NULL DEFAULT '1990-01-01'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------


INSERT INTO `user`(`user_id`,`username`,`password`,`user_type`,`locationIP`,`updatedBy`,`updatedDT`,`createdBy`,`createdDT`)
VALUES(1,'admin','d033e22ae348aeb5660fc2140aec35850c4da997','admin','127.0.0.1','1',NOW(),'1',NOW());

--
-- Table structure for table `bid_product`
--
DROP TABLE IF EXISTS `bid_product`;
CREATE TABLE IF NOT EXISTS `bid_product` (
    `bid_product_id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(`bid_product_id`),
    `name` varchar(500) NOT NULL DEFAULT '',
    `description` varchar(3000) NOT NULL DEFAULT '',
    `increment_value` double NOT NULL DEFAULT 0,
    `actual_value` double NOT NULL DEFAULT 0,
    `sold_value` double NOT NULL DEFAULT 0,
    `earned_value` double NOT NULL DEFAULT 0,
    `locationIP` varchar(50) NOT NULL DEFAULT '',
    `updatedBy` varchar(50) NOT NULL DEFAULT '',
    `updatedDT` datetime NOT NULL DEFAULT '1990-01-01',
    `createdBy` varchar(50) NOT NULL DEFAULT '',
    `createdDT` datetime NOT NULL DEFAULT '1990-01-01'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `bid_product`(`name`,`description`,`increment_value`,`actual_value`,`sold_value`,`earned_value`,`locationIP`,`updatedBy`,`updatedDT`,`createdBy`,`createdDT`)
VALUES(1,'test','test',1,200,300,0,'127.0.0.1','1',NOW(),'1',NOW());

-- --------------------------------------------------------
--
-- Table structure for table `bid_history`/*Nicholas.3May2015::this table does not need 5stars_columns*/
--
DROP TABLE IF EXISTS `bid_history`;
CREATE TABLE IF NOT EXISTS `bid_history` (
    `bid_history_id` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(`bid_history_id`),
    `user_id` int NOT NULL DEFAULT 0,
    `bid_product_id` int NOT NULL DEFAULT 0,
    `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `locationIP` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------