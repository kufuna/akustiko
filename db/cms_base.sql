-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 20, 2015 at 12:38 PM
-- Server version: 5.5.44-0+deb8u1
-- PHP Version: 5.6.13-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bga`
--

-- --------------------------------------------------------

--
-- Table structure for table `cn_action_logs`
--

CREATE TABLE IF NOT EXISTS `cn_action_logs` (
`id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `metadata` text CHARACTER SET utf8 NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `table` varchar(255) CHARACTER SET utf8 NOT NULL,
  `item_id` int(11) NOT NULL,
  `user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cn_admin_dashboard`
--

CREATE TABLE IF NOT EXISTS `cn_admin_dashboard` (
`id` int(11) NOT NULL,
  `icon` text CHARACTER SET utf8 NOT NULL,
  `link` text CHARACTER SET utf8 NOT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `ord` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cn_admin_dashboard`
--

INSERT INTO `cn_admin_dashboard` (`id`, `icon`, `link`, `name`, `ord`) VALUES
(3, '556879ceea19d.png', 'http://orc.connect.ge/admin/ru/module/48/add', 'Add Slide', 0),
(4, '556879dcd4a9b.png', 'http://orc.connect.ge/admin/ru/module/51/add', 'Projects', 1),
(5, '556879d5be247.png', 'http://orc.connect.ge/admin/ru/module/52/add', 'News', 2);

-- --------------------------------------------------------

--
-- Table structure for table `cn_admin_modules`
--

CREATE TABLE IF NOT EXISTS `cn_admin_modules` (
`id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active` int(11) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `namespace` varchar(50) NOT NULL,
  `parent` int(11) NOT NULL,
  `ord` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cn_admin_modules`
--

INSERT INTO `cn_admin_modules` (`id`, `name`, `active`, `icon`, `namespace`, `parent`, `ord`) VALUES
(2, 'Edit the Modules', 1, '', 'modules_edit', 0, 13),
(11, 'Dashboard', 0, '', 'dashboard', 0, 12),
(34, 'Main menu', 1, '', 'menu', 0, 0),
(48, 'Slider', 1, '', 'slider', 0, 1),
(49, 'Home items', 1, '', 'home_items', 58, 0),
(50, 'Contact', 1, '', 'contact', 0, 10),
(52, 'News', 0, '', 'highlights', 0, 9),
(57, 'Programs', 1, '', 'programs', 0, 7),
(58, 'Home page', 1, '', 'home_page', 0, 2),
(59, 'Events', 1, '', 'events', 0, 6),
(60, 'About us', 1, '', 'about', 0, 3),
(61, 'Welcome', 1, '', 'about_welcome', 60, 0),
(62, 'Philosophy', 1, '', 'about_mission', 60, 1),
(63, 'Team', 1, '', 'about_team', 60, 2),
(64, 'Team messages', 1, '', 'team_msgs', 60, 3),
(65, 'Directorate', 1, '', 'directors', 60, 4),
(66, 'Policies', 1, '', 'about_policies', 60, 5),
(67, 'Job opportunities ', 1, '', 'about_jobs', 60, 6),
(68, 'Admission', 1, '', 'admissions', 0, 4),
(69, 'Categories', 1, '', 'event_cats', 59, 0),
(70, 'FAQ', 1, '', 'faq_page', 0, 5),
(71, 'Questions', 1, '', 'faq_questions', 70, 0),
(72, 'Beyond the classroom', 1, '', 'beyond', 0, 8),
(73, 'Activities', 1, '', 'activities', 72, 0),
(74, 'EAL support', 1, '', 'eal', 57, 1),
(75, 'Assesment', 1, '', 'assesment', 57, 0),
(76, 'Brochure', 1, '', 'brochure', 68, 0),
(77, 'How to apply', 1, '', 'apply', 68, 1),
(78, 'Banners', 1, '', 'banners', 0, 11),
(79, 'Bus service', 1, '', 'bus_service', 50, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cn_admin_rights`
--

CREATE TABLE IF NOT EXISTS `cn_admin_rights` (
`id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2753 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cn_admin_rights`
--

INSERT INTO `cn_admin_rights` (`id`, `userid`, `moduleid`) VALUES
(286, 7, 1),
(287, 7, 2),
(288, 7, 3),
(289, 7, 4),
(290, 7, 5),
(291, 7, 6),
(292, 7, 7),
(293, 7, 8),
(310, 8, 1),
(311, 8, 2),
(312, 8, 3),
(313, 8, 4),
(314, 8, 5),
(315, 8, 6),
(316, 8, 7),
(317, 8, 8),
(318, 9, 1),
(319, 9, 2),
(320, 9, 3),
(321, 9, 4),
(322, 9, 5),
(323, 9, 6),
(324, 9, 7),
(325, 9, 8),
(901, 11, 15),
(902, 11, 9),
(903, 11, 12),
(904, 11, 16),
(905, 11, 22),
(906, 11, 21),
(907, 11, 20),
(908, 11, 19),
(909, 11, 18),
(910, 11, 23),
(911, 11, 13),
(912, 11, 14),
(913, 11, 17),
(914, 11, 11),
(915, 11, 2),
(976, 13, 27),
(977, 13, 28),
(978, 13, 29),
(979, 13, 15),
(980, 13, 24),
(981, 13, 25),
(982, 13, 26),
(983, 13, 9),
(984, 13, 12),
(985, 13, 16),
(986, 13, 22),
(987, 13, 21),
(988, 13, 20),
(989, 13, 19),
(990, 13, 18),
(991, 13, 23),
(992, 13, 13),
(993, 13, 14),
(994, 13, 17),
(995, 13, 11),
(996, 13, 2),
(1723, 16, 43),
(1724, 16, 42),
(1725, 16, 40),
(1726, 16, 41),
(1727, 16, 39),
(1728, 16, 34),
(1729, 16, 37),
(1730, 16, 38),
(1731, 16, 35),
(1732, 16, 36),
(1733, 16, 11),
(1734, 16, 2),
(1735, 16, 45),
(1736, 16, 46),
(1737, 16, 44),
(2002, 15, 48),
(2003, 15, 49),
(2004, 15, 53),
(2005, 15, 51),
(2006, 15, 55),
(2007, 15, 56),
(2008, 15, 54),
(2009, 15, 52),
(2010, 15, 50),
(2011, 15, 11),
(2012, 15, 34),
(2013, 15, 2),
(2026, 6, 48),
(2027, 6, 49),
(2028, 6, 53),
(2029, 6, 51),
(2030, 6, 55),
(2031, 6, 56),
(2032, 6, 54),
(2033, 6, 52),
(2034, 6, 50),
(2035, 6, 11),
(2036, 6, 34),
(2037, 6, 2),
(2074, 17, 48),
(2075, 17, 49),
(2076, 17, 53),
(2077, 17, 51),
(2078, 17, 55),
(2079, 17, 56),
(2080, 17, 54),
(2081, 17, 52),
(2082, 17, 50),
(2083, 17, 11),
(2084, 17, 34),
(2085, 17, 2),
(2086, 18, 48),
(2087, 18, 49),
(2088, 18, 53),
(2089, 18, 51),
(2090, 18, 55),
(2091, 18, 56),
(2092, 18, 54),
(2093, 18, 52),
(2094, 18, 50),
(2095, 18, 11),
(2096, 18, 34),
(2097, 18, 2),
(2605, 14, 49),
(2606, 14, 71),
(2607, 14, 73),
(2608, 14, 75),
(2609, 14, 60),
(2610, 14, 76),
(2611, 14, 61),
(2612, 14, 78),
(2613, 14, 69),
(2614, 14, 72),
(2615, 14, 74),
(2616, 14, 77),
(2617, 14, 62),
(2618, 14, 70),
(2619, 14, 63),
(2620, 14, 64),
(2621, 14, 34),
(2622, 14, 58),
(2623, 14, 65),
(2624, 14, 59),
(2625, 14, 66),
(2626, 14, 67),
(2627, 14, 48),
(2628, 14, 68),
(2629, 14, 57),
(2630, 14, 52),
(2631, 14, 50),
(2632, 14, 11),
(2633, 14, 2),
(2663, 1, 49),
(2664, 1, 71),
(2665, 1, 73),
(2666, 1, 75),
(2667, 1, 76),
(2668, 1, 61),
(2669, 1, 79),
(2670, 1, 34),
(2671, 1, 69),
(2672, 1, 74),
(2673, 1, 58),
(2674, 1, 77),
(2675, 1, 62),
(2676, 1, 63),
(2677, 1, 48),
(2678, 1, 78),
(2679, 1, 64),
(2680, 1, 60),
(2681, 1, 65),
(2682, 1, 72),
(2683, 1, 66),
(2684, 1, 70),
(2685, 1, 67),
(2686, 1, 59),
(2687, 1, 68),
(2688, 1, 57),
(2689, 1, 52),
(2690, 1, 50),
(2691, 1, 11),
(2692, 1, 2),
(2723, 12, 69),
(2724, 12, 49),
(2725, 12, 71),
(2726, 12, 73),
(2727, 12, 75),
(2728, 12, 76),
(2729, 12, 61),
(2730, 12, 79),
(2731, 12, 34),
(2732, 12, 58),
(2733, 12, 74),
(2734, 12, 77),
(2735, 12, 62),
(2736, 12, 48),
(2737, 12, 63),
(2738, 12, 78),
(2739, 12, 64),
(2740, 12, 60),
(2741, 12, 65),
(2742, 12, 72),
(2743, 12, 66),
(2744, 12, 70),
(2745, 12, 67),
(2746, 12, 59),
(2747, 12, 68),
(2748, 12, 57),
(2749, 12, 52),
(2750, 12, 50),
(2751, 12, 11),
(2752, 12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cn_admin_users`
--

CREATE TABLE IF NOT EXISTS `cn_admin_users` (
`id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `username` varchar(50) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `rights` varchar(255) NOT NULL,
  `ips` text NOT NULL,
  `random` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cn_admin_users`
--

INSERT INTO `cn_admin_users` (`id`, `email`, `pass`, `username`, `avatar`, `rights`, `ips`, `random`) VALUES
(1, 'ucha4964@gmail.com', '5843ae285ad33c6fa694fd91cc6e8f32', 'Ucha', '', 'modules:texts:texts_add_remove:users:stats:super_user:logs:documentation:trash:seo', '212.72.155.254,127.0.0.1', 'xU0abcSlAx'),
(6, 'david@connect.ge', 'c39260f9901fd674a7657a7df8dfa037', 'david', '', 'modules:texts:texts_add_remove:users:logs:documentation:trash', '212.72.155.254,212.72.155.55,212.72.155.254', 'okwEZ6rWm5'),
(12, 'sopo@connect.ge', '4c8c75936f1553a9b16382f0fcfe1733', 'sopo', '', 'modules:texts:texts_add_remove:users:super_user:logs:documentation:trash:seo', '212.72.155.254', 'OBmwKAMud0'),
(14, 'natia@connect.ge', 'c6d0ee3d5e4bb527195827902b387d37', 'natia', '', 'modules:texts:texts_add_remove:users:stats:super_user:logs:documentation:trash:seo', '212.72.155.254', 'o4ujqQIK5c'),
(15, 'gmosashvili@connect.ge', '9db06bcff9248837f86d1a6bcf41c9e7', 'mosa', '', 'modules:texts:texts_add_remove:users:stats:super_user:logs', '212.72.155.254,127.0.0.1', 'UsGDJY97qA'),
(16, 'savaneli@connect.ge', 'b547c6a652ab68bcee66cc7c8ec45703', 'sava', '', 'modules:texts:texts_add_remove:users:stats:super_user:logs:documentation:trash', '212.72.155.254,127.0.0.1', '');

-- --------------------------------------------------------

--
-- Table structure for table `cn_auth_logs`
--

CREATE TABLE IF NOT EXISTS `cn_auth_logs` (
`id` int(11) NOT NULL,
  `user` text NOT NULL,
  `browser` text NOT NULL,
  `platform` text NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cn_settings`
--

CREATE TABLE IF NOT EXISTS `cn_settings` (
`id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `ord` int(11) NOT NULL,
  `editable` tinyint(4) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cn_settings`
--

INSERT INTO `cn_settings` (`id`, `key`, `value`, `label`, `type`, `ord`, `editable`) VALUES
(1, 'ACCESS_LOG_PATH', '../logs/book.log', 'Apache log file', 'string', 2, 1),
(2, 'DEBUG', '1', 'Development mode', 'boolean', 1, 1),
(3, 'timezone', 'Asia/Tbilisi', 'Time zone', 'string', 3, 1),
(4, 'mb_internal_encoding', 'utf8', 'Multibyte encoding', 'string', 4, 1),
(5, 'DIR_FRONT', 'www', 'Front directory', 'string', 5, 1),
(6, 'DIR_BACK', 'back', 'Back directory', 'string', 6, 1),
(7, 'ROOT', '../', 'Root directory', 'string', 7, 1),
(8, 'DB_HOST', 'localhost', 'Database Host', 'string', 8, 1),
(9, 'DB_USER', 'developer', 'Database user', 'string', 9, 1),
(10, 'DB_PASS', '144144144', 'Database password', 'string', 10, 1),
(11, 'DB_NAME', 'dev_maqro', 'Database name', 'string', 11, 1),
(12, 'ROOT_URL', 'http://maqro.connect.ge/', 'Web root URL', 'string', 12, 1),
(13, 'DOMAIN', 'azimuti.ge', 'Domain name', 'string', 13, 1),
(14, 'DEFAULT_LANG', 'en', 'Default language', 'string', 14, 1),
(15, 'ADMIN_PATH', 'maqro.ge', 'Administrator panel path', 'string', 15, 1),
(16, 'ADMIN_SESSION_NAMESPACE', '_CN_ADMIN_USER', '', 'string', 0, 0),
(17, 'USER_SESSION_NAMESPACE', '_CN_USER', '', 'string', 0, 0),
(18, 'MAIL_HOST', 'mail.connect.ge', 'Webmail host', 'string', 7, 1),
(19, 'MAIL_USER', 'natia@connect.ge', 'Webmail user', 'string', 7, 1),
(20, 'MAIL_PASS', '1992natia', 'Webmail password', 'string', 7, 1),
(21, 'CONTACT_EMAIL', 'natia@connect.ge', 'Contact email', 'string', 7, 1),
(22, 'CAPTCHA_PUBLIC_KEY', '6LcOOQETAAAAAINrO_EYjYQGC8QNpKLr9hsLlGRj', 'Captcha public key', 'string', 7, 1),
(23, 'CAPTCHA_PRIVATE_KEY', '6LcOOQETAAAAAFpcmEW51ce50OMrpdW5XoQNURky', 'Captcha security key', 'string', 7, 1),
(24, 'CFG', '{"SITE_LANGS":["en","ka"],"LANG_NAMES":{"en":"English","ka":"Georgian"},"LANG_SHORT_NAMES":{"en":"ENG","ka":"GEO"},"stats":{"News":"news"}}', '', 'variable', 200, 0),
(25, 'SITE_LANGS', '["en","ka"]', '', 'variable', 201, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cn_trash`
--

CREATE TABLE IF NOT EXISTS `cn_trash` (
`id` int(11) NOT NULL,
  `metadata` text NOT NULL,
  `user` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `table` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cn_visit_stats`
--

CREATE TABLE IF NOT EXISTS `cn_visit_stats` (
`id` int(11) NOT NULL,
  `date` date NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cn_action_logs`
--
ALTER TABLE `cn_action_logs`
 ADD PRIMARY KEY (`id`), ADD KEY `user` (`user`), ADD KEY `table` (`table`), ADD KEY `date` (`date`), ADD KEY `type` (`type`), ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `cn_admin_dashboard`
--
ALTER TABLE `cn_admin_dashboard`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cn_admin_modules`
--
ALTER TABLE `cn_admin_modules`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cn_admin_rights`
--
ALTER TABLE `cn_admin_rights`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cn_admin_users`
--
ALTER TABLE `cn_admin_users`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cn_auth_logs`
--
ALTER TABLE `cn_auth_logs`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cn_settings`
--
ALTER TABLE `cn_settings`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cn_trash`
--
ALTER TABLE `cn_trash`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `cn_visit_stats`
--
ALTER TABLE `cn_visit_stats`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `date` (`date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cn_action_logs`
--
ALTER TABLE `cn_action_logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cn_admin_dashboard`
--
ALTER TABLE `cn_admin_dashboard`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `cn_admin_modules`
--
ALTER TABLE `cn_admin_modules`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `cn_admin_rights`
--
ALTER TABLE `cn_admin_rights`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2753;
--
-- AUTO_INCREMENT for table `cn_admin_users`
--
ALTER TABLE `cn_admin_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `cn_auth_logs`
--
ALTER TABLE `cn_auth_logs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cn_settings`
--
ALTER TABLE `cn_settings`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `cn_trash`
--
ALTER TABLE `cn_trash`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cn_visit_stats`
--
ALTER TABLE `cn_visit_stats`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
