-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2015 at 04:24 AM
-- Server version: 5.6.25-log
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_portal_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `collector_projects`
--

CREATE TABLE IF NOT EXISTS `collector_projects` (
  `fk_project_id` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `conferences`
--

CREATE TABLE IF NOT EXISTS `conferences` (
  `pk_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `conference_date` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `conference_end_date` date DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `num_days` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `conference_projects`
--

CREATE TABLE IF NOT EXISTS `conference_projects` (
  `fk_project_id` int(10) unsigned NOT NULL,
  `fk_conference_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `conf_projs`
--

CREATE TABLE IF NOT EXISTS `conf_projs` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_conference_id` int(10) unsigned DEFAULT NULL,
  `fk_project_id` int(10) unsigned DEFAULT NULL,
  `int_amount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL,
  `num_days` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `pk_id` int(10) unsigned NOT NULL,
  `type` smallint(6) NOT NULL,
  `is_source` tinyint(3) NOT NULL DEFAULT '1',
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `salutation` tinyint(4) NOT NULL DEFAULT '10',
  `degree` varchar(255) NOT NULL DEFAULT '10',
  `email1` varchar(255) DEFAULT NULL,
  `email1_type` tinyint(4) DEFAULT NULL,
  `email2` varchar(255) DEFAULT NULL,
  `email2_type` tinyint(4) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `phone1` varchar(30) DEFAULT NULL,
  `phone1_type` tinyint(4) DEFAULT NULL,
  `phone2` varchar(30) DEFAULT NULL,
  `phone2_type` tinyint(4) DEFAULT NULL,
  `phone3` varchar(30) DEFAULT NULL,
  `phone3_type` tinyint(4) DEFAULT NULL,
  `specialty` varchar(255) NOT NULL DEFAULT '1000',
  `reliability` tinyint(4) NOT NULL DEFAULT '10',
  `recontact` tinyint(1) NOT NULL DEFAULT '1',
  `background` text,
  `notes` text,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL,
  `imported` tinyint(1) NOT NULL DEFAULT '0',
  `zipcode` varchar(5) DEFAULT NULL,
  `language` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8124 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`pk_id`, `type`, `is_source`, `first_name`, `last_name`, `salutation`, `degree`, `email1`, `email1_type`, `email2`, `email2_type`, `title`, `phone1`, `phone1_type`, `phone2`, `phone2_type`, `phone3`, `phone3_type`, `specialty`, `reliability`, `recontact`, `background`, `notes`, `fk_created_by_user`, `created`, `fk_last_changed_user`, `last_changed`, `imported`, `zipcode`, `language`, `area`) VALUES
(8123, 8, 4, 'Jeremy', 'Jenkins', 2, '10', '', 1, '', 2, '', '', 1, '', 2, '', 3, '1000', 10, 2, '', '', 5, '2015-09-05', 5, '2015-09-05', 0, NULL, '1000', '1000');

-- --------------------------------------------------------

--
-- Table structure for table `contact_orgs`
--

CREATE TABLE IF NOT EXISTS `contact_orgs` (
  `fk_contact_id` int(10) unsigned NOT NULL,
  `fk_organization_id` int(10) unsigned NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(2) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '1',
  `is_current` tinyint(1) NOT NULL DEFAULT '1',
  `zipcode` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contact_projects`
--

CREATE TABLE IF NOT EXISTS `contact_projects` (
  `fk_contact_id` int(10) unsigned NOT NULL,
  `fk_project_id` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contractors`
--

CREATE TABLE IF NOT EXISTS `contractors` (
  `pk_id` int(10) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contractor_projects`
--

CREATE TABLE IF NOT EXISTS `contractor_projects` (
  `fk_project_id` int(10) unsigned NOT NULL,
  `fk_contact_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deliverables`
--

CREATE TABLE IF NOT EXISTS `deliverables` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_project_id` int(10) unsigned DEFAULT NULL,
  `int_start_date` date NOT NULL,
  `notes` text,
  `clientinteraction` int(10) unsigned NOT NULL DEFAULT '0',
  `type` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_contractor_id` int(10) unsigned DEFAULT NULL,
  `fk_project_id` int(10) unsigned DEFAULT NULL,
  `int_amount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `int_date` date NOT NULL,
  `notes` text,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL,
  `other_expense` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL,
  `link` text NOT NULL,
  `title` text,
  `type` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hours`
--

CREATE TABLE IF NOT EXISTS `hours` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_contractor_id` int(10) unsigned DEFAULT NULL,
  `fk_project_id` int(10) unsigned DEFAULT NULL,
  `int_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `int_date` date NOT NULL,
  `notes` text,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE IF NOT EXISTS `interviews` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL,
  `fk_contact_id` int(10) unsigned DEFAULT NULL,
  `int_number` int(10) unsigned NOT NULL DEFAULT '1',
  `int_date` date NOT NULL,
  `approach` smallint(6) NOT NULL,
  `rate` varchar(255) DEFAULT NULL,
  `paid` tinyint(2) NOT NULL DEFAULT '2',
  `method` tinyint(4) NOT NULL DEFAULT '1',
  `credibility` tinyint(4) NOT NULL DEFAULT '1',
  `confidential` text,
  `int_background` text,
  `source_comments` text,
  `analyst_comments` text,
  `int_notes` text,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL,
  `imported` tinyint(1) NOT NULL DEFAULT '0',
  `is_activity` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=9819 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `interview_conferences`
--

CREATE TABLE IF NOT EXISTS `interview_conferences` (
  `fk_interview_id` int(10) unsigned NOT NULL,
  `fk_conference_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `interview_projects`
--

CREATE TABLE IF NOT EXISTS `interview_projects` (
  `fk_interview_id` int(10) unsigned NOT NULL,
  `fk_project_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE IF NOT EXISTS `organizations` (
  `pk_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `notes` text,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL,
  `imported` tinyint(1) NOT NULL DEFAULT '0',
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `state` varchar(12) DEFAULT NULL,
  `zipcode` varchar(5) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3620 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_client_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `fk_poc_id` int(10) unsigned DEFAULT NULL,
  `fk_pm_id` int(10) unsigned NOT NULL,
  `notes` text,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL,
  `imported` tinyint(1) NOT NULL DEFAULT '0',
  `value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `hourly_rate` decimal(15,2) NOT NULL DEFAULT '0.00',
  `specialty` varchar(255) NOT NULL DEFAULT '1000',
  `poc` varchar(255) DEFAULT NULL,
  `fk_target_id` int(11) DEFAULT NULL,
  `is_life_science` tinyint(1) NOT NULL DEFAULT '1',
  `industry` varchar(256) NOT NULL DEFAULT '1000',
  `fk_dir_id` int(10) unsigned NOT NULL,
  `prefix` int(11) NOT NULL DEFAULT '0',
  `bd_poc` varchar(255) DEFAULT NULL,
  `months` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB AUTO_INCREMENT=323 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned DEFAULT NULL,
  `fk_project_id` int(10) unsigned DEFAULT NULL,
  `effort` tinyint(4) NOT NULL DEFAULT '10',
  `int_start_date` date NOT NULL,
  `int_end_date` date NOT NULL,
  `notes` text,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL,
  `fk_contractor_id` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `pk_id` int(10) unsigned NOT NULL DEFAULT '0',
  `fk_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `timeout` int(10) unsigned NOT NULL DEFAULT '0',
  `remote_ip` varchar(15) NOT NULL,
  `session_data` text,
  `system_msg` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`pk_id`, `fk_user_id`, `start_time`, `timeout`, `remote_ip`, `session_data`, `system_msg`) VALUES
(465657749, 0, 1441416736, 3600, '::1', NULL, NULL),
(688011209, 0, 1441418000, 3600, '::1', NULL, NULL),
(709196028, 5, 1441419504, 3600, '::1', NULL, '{"type":"success","text":"The password has been changed successfully."}'),
(742449243, 0, 1441419417, 3600, '::1', NULL, NULL),
(882085350, 0, 1441417732, 3600, '::1', NULL, NULL),
(933045003, 0, 1441418580, 3600, '::1', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `statusupdates`
--

CREATE TABLE IF NOT EXISTS `statusupdates` (
  `pk_id` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned DEFAULT NULL,
  `fk_contractor_id` int(10) unsigned DEFAULT NULL,
  `fk_project_id` int(10) unsigned DEFAULT NULL,
  `int_start_date` date NOT NULL,
  `notes` text,
  `status` int(10) unsigned NOT NULL DEFAULT '0',
  `concern` int(10) unsigned NOT NULL DEFAULT '0',
  `resolution` text,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` date NOT NULL,
  `fk_last_changed_user` int(10) unsigned DEFAULT NULL,
  `last_changed` date NOT NULL,
  `resolved_date` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=535 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `userlogs`
--

CREATE TABLE IF NOT EXISTS `userlogs` (
  `pk_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `fk_created_by_user` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3690 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userlogs`
--

INSERT INTO `userlogs` (`pk_id`, `name`, `description`, `fk_created_by_user`, `created`) VALUES
(3642, '1', NULL, 5, '2015-08-29 23:42:17'),
(3643, '2', NULL, 5, '2015-08-29 23:43:42'),
(3644, '1', NULL, 5, '2015-08-29 23:44:06'),
(3645, '7', NULL, 5, '2015-08-29 23:44:30'),
(3646, '3', NULL, 5, '2015-08-29 23:44:50'),
(3647, '8', NULL, 5, '2015-08-29 23:47:12'),
(3648, '7', NULL, 5, '2015-08-29 23:47:30'),
(3649, '1', NULL, 5, '2015-08-30 10:28:56'),
(3650, '3', NULL, 5, '2015-08-30 10:29:00'),
(3651, '5', NULL, 5, '2015-08-30 10:29:11'),
(3652, '6', NULL, 5, '2015-08-30 10:34:46'),
(3653, '3', NULL, 5, '2015-08-30 10:37:51'),
(3654, '5', NULL, 5, '2015-08-30 10:37:53'),
(3655, '6', NULL, 5, '2015-08-30 10:37:57'),
(3656, '1', NULL, 5, '2015-09-04 21:32:17'),
(3657, '3', NULL, 5, '2015-09-04 21:32:31'),
(3658, '9', NULL, 5, '2015-09-04 21:34:23'),
(3659, '9', NULL, 5, '2015-09-04 21:34:26'),
(3660, '2', NULL, 5, '2015-09-04 21:48:49'),
(3661, '1', NULL, 5, '2015-09-04 21:48:52'),
(3662, '3', NULL, 5, '2015-09-04 21:48:57'),
(3663, '2', NULL, 5, '2015-09-04 21:49:05'),
(3664, '1', NULL, 5, '2015-09-04 21:53:20'),
(3665, '5', NULL, 5, '2015-09-04 21:53:23'),
(3666, '6', NULL, 5, '2015-09-04 21:53:27'),
(3667, '3', NULL, 5, '2015-09-04 21:53:53'),
(3668, '8', NULL, 5, '2015-09-04 21:54:54'),
(3669, '9', NULL, 5, '2015-09-04 21:55:01'),
(3670, '9', NULL, 5, '2015-09-04 21:55:04'),
(3671, '7', NULL, 5, '2015-09-04 21:55:21'),
(3672, '2', NULL, 5, '2015-09-04 21:55:32'),
(3673, '1', NULL, 5, '2015-09-04 22:03:00'),
(3674, '5', NULL, 5, '2015-09-04 22:03:03'),
(3675, '3', NULL, 5, '2015-09-04 22:03:05'),
(3676, '6', NULL, 5, '2015-09-04 22:03:10'),
(3677, '7', NULL, 5, '2015-09-04 22:03:20'),
(3678, '8', NULL, 5, '2015-09-04 22:03:25'),
(3679, '9', NULL, 5, '2015-09-04 22:03:28'),
(3680, '9', NULL, 5, '2015-09-04 22:03:30'),
(3681, '9', NULL, 5, '2015-09-04 22:03:34'),
(3682, '8', NULL, 5, '2015-09-04 22:04:17'),
(3683, '9', NULL, 5, '2015-09-04 22:04:19'),
(3684, '5', NULL, 5, '2015-09-04 22:04:20'),
(3685, '2', NULL, 5, '2015-09-04 22:16:53'),
(3686, '1', NULL, 5, '2015-09-04 22:16:57'),
(3687, '3', NULL, 5, '2015-09-04 22:16:59'),
(3688, '7', NULL, 5, '2015-09-04 22:17:01'),
(3689, '7', NULL, 5, '2015-09-04 22:18:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `pk_id` int(10) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `temp` tinyint(1) NOT NULL DEFAULT '0',
  `imported` tinyint(1) NOT NULL DEFAULT '0',
  `level` tinyint(4) NOT NULL DEFAULT '2',
  `created` date DEFAULT NULL,
  `last_login` date DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `interview_defaults` varchar(256) DEFAULT NULL,
  `source_defaults` varchar(256) DEFAULT NULL,
  `project_defaults` varchar(256) DEFAULT NULL,
  `reset_pwd` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`pk_id`, `username`, `email`, `password`, `temp`, `imported`, `level`, `created`, `last_login`, `active`, `interview_defaults`, `source_defaults`, `project_defaults`, `reset_pwd`) VALUES
(0, 'guest', NULL, '', 0, 0, 1, '2011-04-17', NULL, 1, NULL, NULL, NULL, 1),
(3, 'jthornburg', 'joshuathornburg@hotmail.com', 'b2d5dbb4b572d603f49b8f8842216eed', 0, 0, 5, '2015-04-17', NULL, 1, '2.1.3.7.9.6', '3.2.4.1.0.17', '1.3.6.4.7', 0),
(5, 'cwennerholm', '', '05a671c66aefea124cc08b76ea6d30bb', 0, 0, 5, '2015-04-17', NULL, 1, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_conferences`
--

CREATE TABLE IF NOT EXISTS `user_conferences` (
  `fk_conference_id` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_conf_projs`
--

CREATE TABLE IF NOT EXISTS `user_conf_projs` (
  `fk_conf_proj_id` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
  `pk_id` int(10) unsigned NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`pk_id`, `first_name`, `last_name`) VALUES
(3, 'Josh', 'Thornburg'),
(5, 'Carl', 'Wennerholm');

-- --------------------------------------------------------

--
-- Table structure for table `user_projects`
--

CREATE TABLE IF NOT EXISTS `user_projects` (
  `fk_project_id` int(10) unsigned NOT NULL,
  `fk_user_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collector_projects`
--
ALTER TABLE `collector_projects`
  ADD PRIMARY KEY (`fk_project_id`,`fk_user_id`),
  ADD KEY `coll_fk_user_id` (`fk_user_id`);

--
-- Indexes for table `conferences`
--
ALTER TABLE `conferences`
  ADD PRIMARY KEY (`pk_id`);

--
-- Indexes for table `conference_projects`
--
ALTER TABLE `conference_projects`
  ADD PRIMARY KEY (`fk_project_id`,`fk_conference_id`),
  ADD KEY `cop_fk_conference_id` (`fk_conference_id`);

--
-- Indexes for table `conf_projs`
--
ALTER TABLE `conf_projs`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `confproj_fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `confproj_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `confproj_fk_conference_id` (`fk_conference_id`),
  ADD KEY `confproj_fk_project_id` (`fk_project_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`);

--
-- Indexes for table `contact_orgs`
--
ALTER TABLE `contact_orgs`
  ADD PRIMARY KEY (`fk_contact_id`,`fk_organization_id`),
  ADD KEY `co_fk_organization_id` (`fk_organization_id`);

--
-- Indexes for table `contact_projects`
--
ALTER TABLE `contact_projects`
  ADD PRIMARY KEY (`fk_contact_id`,`fk_project_id`),
  ADD KEY `ip_fk_project_id` (`fk_project_id`);

--
-- Indexes for table `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`pk_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `contractor_projects`
--
ALTER TABLE `contractor_projects`
  ADD PRIMARY KEY (`fk_project_id`,`fk_contact_id`),
  ADD KEY `up_fk_contact_id` (`fk_contact_id`);

--
-- Indexes for table `deliverables`
--
ALTER TABLE `deliverables`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `del_fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `del_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `del_fk_project_id` (`fk_project_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `exp_fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `exp_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `exp_fk_contractor_id` (`fk_contractor_id`),
  ADD KEY `exp_fk_project_id` (`fk_project_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `hours`
--
ALTER TABLE `hours`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `hou_fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `hou_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `hou_fk_contractor_id` (`fk_contractor_id`),
  ADD KEY `hou_fk_project_id` (`fk_project_id`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `int_fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `int_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `i_fk_user_id` (`fk_user_id`),
  ADD KEY `i_fk_contact_id` (`fk_contact_id`);

--
-- Indexes for table `interview_conferences`
--
ALTER TABLE `interview_conferences`
  ADD PRIMARY KEY (`fk_interview_id`,`fk_conference_id`),
  ADD KEY `ic_fk_conference_id` (`fk_conference_id`);

--
-- Indexes for table `interview_projects`
--
ALTER TABLE `interview_projects`
  ADD PRIMARY KEY (`fk_interview_id`,`fk_project_id`),
  ADD KEY `ip_fk_project_id` (`fk_project_id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`pk_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `org_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `org_fk_last_changed_user` (`fk_last_changed_user`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`pk_id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `proj_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `proj_fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `p_fk_client_id` (`fk_client_id`),
  ADD KEY `p_fk_pm_id` (`fk_pm_id`),
  ADD KEY `p_fk_poc_id` (`fk_poc_id`),
  ADD KEY `p_fk_target_id` (`fk_target_id`),
  ADD KEY `p_fk_dir_id` (`fk_dir_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `res_fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `res_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `res_fk_user_id` (`fk_user_id`),
  ADD KEY `res_fk_project_id` (`fk_project_id`),
  ADD KEY `res_fk_contractor_id` (`fk_contractor_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `start_time` (`start_time`),
  ADD KEY `fk_user_id` (`fk_user_id`);

--
-- Indexes for table `statusupdates`
--
ALTER TABLE `statusupdates`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `upd_fk_last_changed_user` (`fk_last_changed_user`),
  ADD KEY `upd_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `upd_fk_user_id` (`fk_user_id`),
  ADD KEY `upd_fk_project_id` (`fk_project_id`),
  ADD KEY `upd_fk_contractor_id` (`fk_contractor_id`);

--
-- Indexes for table `userlogs`
--
ALTER TABLE `userlogs`
  ADD PRIMARY KEY (`pk_id`),
  ADD KEY `usl_name` (`name`),
  ADD KEY `usl_fk_created_by_user` (`fk_created_by_user`),
  ADD KEY `usl_created` (`created`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`pk_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_conferences`
--
ALTER TABLE `user_conferences`
  ADD PRIMARY KEY (`fk_conference_id`,`fk_user_id`),
  ADD KEY `up_fk_user_id` (`fk_user_id`);

--
-- Indexes for table `user_conf_projs`
--
ALTER TABLE `user_conf_projs`
  ADD PRIMARY KEY (`fk_conf_proj_id`,`fk_user_id`),
  ADD KEY `up_fk_user_id` (`fk_user_id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`pk_id`);

--
-- Indexes for table `user_projects`
--
ALTER TABLE `user_projects`
  ADD PRIMARY KEY (`fk_project_id`,`fk_user_id`),
  ADD KEY `up_fk_user_id` (`fk_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `conferences`
--
ALTER TABLE `conferences`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=140;
--
-- AUTO_INCREMENT for table `conf_projs`
--
ALTER TABLE `conf_projs`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8124;
--
-- AUTO_INCREMENT for table `contractors`
--
ALTER TABLE `contractors`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `deliverables`
--
ALTER TABLE `deliverables`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hours`
--
ALTER TABLE `hours`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9819;
--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3620;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=323;
--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `statusupdates`
--
ALTER TABLE `statusupdates`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=535;
--
-- AUTO_INCREMENT for table `userlogs`
--
ALTER TABLE `userlogs`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3690;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=116;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `collector_projects`
--
ALTER TABLE `collector_projects`
  ADD CONSTRAINT `coll_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coll_fk_user_id` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `conference_projects`
--
ALTER TABLE `conference_projects`
  ADD CONSTRAINT `cop_fk_conference_id` FOREIGN KEY (`fk_conference_id`) REFERENCES `conferences` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cop_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `contact_orgs`
--
ALTER TABLE `contact_orgs`
  ADD CONSTRAINT `co_fk_contact_id` FOREIGN KEY (`fk_contact_id`) REFERENCES `contacts` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `co_fk_organization_id` FOREIGN KEY (`fk_organization_id`) REFERENCES `organizations` (`pk_id`) ON UPDATE CASCADE;

--
-- Constraints for table `contractor_projects`
--
ALTER TABLE `contractor_projects`
  ADD CONSTRAINT `cp_fk_contact_id` FOREIGN KEY (`fk_contact_id`) REFERENCES `contacts` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cp_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `deliverables`
--
ALTER TABLE `deliverables`
  ADD CONSTRAINT `del_fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `del_fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `del_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE SET NULL;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `exp_fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `exp_fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `exp_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE SET NULL;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hours`
--
ALTER TABLE `hours`
  ADD CONSTRAINT `hou_fk_contractor_id` FOREIGN KEY (`fk_contractor_id`) REFERENCES `contacts` (`pk_id`),
  ADD CONSTRAINT `hou_fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `hou_fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `hou_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE SET NULL;

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `i_fk_contact_id` FOREIGN KEY (`fk_contact_id`) REFERENCES `contacts` (`pk_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `i_fk_user_id` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`pk_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `int_fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `int_fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `interview_conferences`
--
ALTER TABLE `interview_conferences`
  ADD CONSTRAINT `ic_fk_conference_id` FOREIGN KEY (`fk_conference_id`) REFERENCES `conferences` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ic_fk_interview_id` FOREIGN KEY (`fk_interview_id`) REFERENCES `interviews` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `interview_projects`
--
ALTER TABLE `interview_projects`
  ADD CONSTRAINT `ip_fk_interview_id` FOREIGN KEY (`fk_interview_id`) REFERENCES `interviews` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ip_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `organizations`
--
ALTER TABLE `organizations`
  ADD CONSTRAINT `org_fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `org_fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `p_fk_client_id` FOREIGN KEY (`fk_client_id`) REFERENCES `organizations` (`pk_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `p_fk_pm_id` FOREIGN KEY (`fk_pm_id`) REFERENCES `users` (`pk_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `p_fk_poc_id` FOREIGN KEY (`fk_poc_id`) REFERENCES `contacts` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `proj_fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `proj_fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `res_fk_contractor_id` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`pk_id`),
  ADD CONSTRAINT `res_fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `res_fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `res_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE SET NULL;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sess_fk_user_id` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `statusupdates`
--
ALTER TABLE `statusupdates`
  ADD CONSTRAINT `upd_fk_contractor_id` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`pk_id`),
  ADD CONSTRAINT `upd_fk_created_by_user` FOREIGN KEY (`fk_created_by_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `upd_fk_last_changed_user` FOREIGN KEY (`fk_last_changed_user`) REFERENCES `users` (`pk_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `upd_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_info`
--
ALTER TABLE `user_info`
  ADD CONSTRAINT `ui_fk_user_id` FOREIGN KEY (`pk_id`) REFERENCES `users` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_projects`
--
ALTER TABLE `user_projects`
  ADD CONSTRAINT `up_fk_project_id` FOREIGN KEY (`fk_project_id`) REFERENCES `projects` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `up_fk_user_id` FOREIGN KEY (`fk_user_id`) REFERENCES `users` (`pk_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
