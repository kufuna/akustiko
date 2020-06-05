-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2019 at 05:43 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `base_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cn_action_logs`
--

CREATE TABLE `cn_action_logs` (
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

CREATE TABLE `cn_admin_dashboard` (
  `id` int(11) NOT NULL,
  `icon` text CHARACTER SET utf8 NOT NULL,
  `link` text CHARACTER SET utf8 NOT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `ord` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cn_admin_modules`
--

CREATE TABLE `cn_admin_modules` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active` int(11) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `namespace` varchar(50) NOT NULL,
  `parent` int(11) NOT NULL,
  `ord` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cn_admin_modules`
--

INSERT INTO `cn_admin_modules` (`id`, `name`, `active`, `icon`, `namespace`, `parent`, `ord`) VALUES
(1, 'Edit the Modules', 1, '', 'modules_edit', 0, 0),
(2, 'Dashboard', 1, '', 'dashboard', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cn_admin_rights`
--

CREATE TABLE `cn_admin_rights` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cn_admin_rights`
--

INSERT INTO `cn_admin_rights` (`id`, `userid`, `moduleid`) VALUES
(1, 14, 1),
(2, 14, 2),
(3, 6, 1),
(4, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cn_admin_users`
--

CREATE TABLE `cn_admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `username` varchar(50) NOT NULL,
  `avatar` varchar(100) NOT NULL,
  `rights` varchar(255) NOT NULL,
  `ips` text NOT NULL,
  `random` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cn_admin_users`
--

INSERT INTO `cn_admin_users` (`id`, `email`, `pass`, `username`, `avatar`, `rights`, `ips`, `random`) VALUES
(6, 'david@connect.ge', 'c39260f9901fd674a7657a7df8dfa037', 'david', '', 'modules:texts:texts_add_remove:users:super_user:logs:documentation:trash:seo', '212.72.155.254,212.72.155.55,212.72.155.254', 'okwEZ6rWm5'),
(14, 'natia@connect.ge', 'c6d0ee3d5e4bb527195827902b387d37', 'natia', '', 'modules:texts:texts_add_remove:users:super_user:logs:documentation:trash:seo', '212.72.155.254,127.0.0.1', 'o4ujqQIK5c');

-- --------------------------------------------------------

--
-- Table structure for table `cn_auth_logs`
--

CREATE TABLE `cn_auth_logs` (
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

CREATE TABLE `cn_settings` (
  `id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `ord` int(11) NOT NULL,
  `editable` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(24, 'CFG', '{\"SITE_LANGS\":[\"en\",\"ka\"],\"LANG_NAMES\":{\"en\":\"English\",\"ka\":\"Georgian\"},\"LANG_SHORT_NAMES\":{\"en\":\"ENG\",\"ka\":\"GEO\"},\"stats\":{\"News\":\"news\"}}', '', 'variable', 200, 0),
(25, 'SITE_LANGS', '[\"en\",\"ka\"]', '', 'variable', 201, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cn_trash`
--

CREATE TABLE `cn_trash` (
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

CREATE TABLE `cn_visit_stats` (
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
  ADD PRIMARY KEY (`id`),
  ADD KEY `user` (`user`),
  ADD KEY `table` (`table`),
  ADD KEY `date` (`date`),
  ADD KEY `type` (`type`),
  ADD KEY `item_id` (`item_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `cn_visit_stats`
--
ALTER TABLE `cn_visit_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cn_admin_modules`
--
ALTER TABLE `cn_admin_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cn_admin_rights`
--
ALTER TABLE `cn_admin_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `cn_admin_users`
--
ALTER TABLE `cn_admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cn_auth_logs`
--
ALTER TABLE `cn_auth_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cn_settings`
--
ALTER TABLE `cn_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
