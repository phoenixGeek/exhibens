/*
SQLyog Community v13.1.1 (64 bit)
MySQL - 10.3.15-MariaDB : Database - exhibens
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`exhibens` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `exhibens`;

/*Table structure for table `groups` */

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `groups` */

insert  into `groups`(`id`,`name`,`description`) values 
(1,'Admin','Administrator'),
(8,'Member','Normal user'),
(26,'Client','Client User'),
(27,'Presentation Author','Presentation Author');

/*Table structure for table `login_attempts` */

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `id` int(11) unsigned NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `login_attempts` */

/*Table structure for table `presentation-video` */

DROP TABLE IF EXISTS `presentation-video`;

CREATE TABLE `presentation-video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `vid` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `segid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `presentation-video` */

/*Table structure for table `presentations` */

DROP TABLE IF EXISTS `presentations`;

CREATE TABLE `presentations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `uid` int(11) NOT NULL,
  `indexid` int(11) DEFAULT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `last_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `public` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

/*Data for the table `presentations` */

/*Table structure for table `segments` */

DROP TABLE IF EXISTS `segments`;

CREATE TABLE `segments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `start` float NOT NULL,
  `end` float NOT NULL,
  `duration` float NOT NULL,
  `videoid` int(11) NOT NULL,
  `path` text NOT NULL,
  `presentation_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4;

/*Data for the table `segments` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(254) NOT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`ip_address`,`username`,`password`,`email`,`activation_selector`,`activation_code`,`forgotten_password_selector`,`forgotten_password_code`,`forgotten_password_time`,`remember_selector`,`remember_code`,`created_on`,`last_login`,`active`,`first_name`,`last_name`,`company`,`phone`) values 
(1,'127.0.0.1','administrator','$2y$12$LZN59CVkS5UQjYX0IxVecOwezlpIVvbNTaV9nZvFELItFJ36lXc5u','admin@admin.com',NULL,'',NULL,NULL,NULL,NULL,NULL,1268889823,1612765575,1,'Admin','istrator','ADMIN','0'),
(57,'::1','AnNguyen','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy','nguyenthian17993@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1608785153,1611142765,1,'An','Nguyen',NULL,NULL),
(58,'210.245.74.17','annguyen3','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy','nguyenthian17993222@gmail.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1609556355,1609556374,1,'An','Nguyen',NULL,NULL),
(59,'210.245.74.17','david','$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy','dkrumholz@strandmanagement.com',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1610548170,1612863117,1,'david','k',NULL,NULL),
(60,'127.0.0.1','topdev','$2y$12$XseznNSkldT9cVy9jsgYHeTc8/srgoEzm77.7wjeb0QCuko7pTCmC','lambodos0228@gmail.com',NULL,NULL,'e29cb5ecbf441fb1ac0d','$2y$10$gNyDTdcjhLnTYNHJv6X1DOrpNzLF27gfdJEljAcli1QLy.WH67SNu',1612277634,NULL,NULL,1612001439,1612001506,1,'lambo','dos',NULL,NULL);

/*Table structure for table `users_groups` */

DROP TABLE IF EXISTS `users_groups`;

CREATE TABLE `users_groups` (
  `id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `users_groups` */

insert  into `users_groups`(`id`,`user_id`,`group_id`) values 
(1,1,1),
(125,1,8),
(130,1,26),
(149,57,8),
(150,57,27),
(0,1,8),
(0,1,8),
(0,1,8),
(0,1,8),
(0,58,8),
(0,58,27),
(0,59,27),
(0,60,8),
(0,60,1);

/*Table structure for table `videos` */

DROP TABLE IF EXISTS `videos`;

CREATE TABLE `videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `uid` int(11) NOT NULL,
  `full_path` varchar(500) NOT NULL,
  `ext` varchar(10) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `thumb_path` varchar(500) DEFAULT NULL,
  `uploaded_on` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4;

/*Data for the table `videos` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
