-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2016 at 05:50 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seed`
--

-- --------------------------------------------------------

--
-- Table structure for table `as_auth_fields`
--

CREATE TABLE `as_auth_fields` (
  `id` int(11) UNSIGNED NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `type` enum('state','element') NOT NULL,
  `desc` text,
  `initialized` datetime DEFAULT NULL,
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_auth_fields`
--

INSERT INTO `as_auth_fields` (`id`, `identifier`, `type`, `desc`, `initialized`, `disabled`, `created`, `created_user_id`, `last_updated`, `last_updated_by`) VALUES
(1, 'app.members', 'state', 'Logged in member state (for authorized users of this application).', NULL, 0, '2016-01-23 04:13:03', 1, '2016-05-01 17:53:45', 1),
(2, 'app.admin', 'element', 'Application admin state (backend data management and site settings).', NULL, 0, '2016-02-09 00:40:29', 1, '2016-02-09 00:47:03', 1),
(5, 'cuddle.button', 'element', 'Only cute woodland creatures can access this button.', '2016-05-29 09:09:47', 0, '2016-02-09 01:10:07', 1, '2016-05-29 13:09:47', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_auth_groups`
--

CREATE TABLE `as_auth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `group` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `desc` text,
  `redirect_state` varchar(255) NOT NULL DEFAULT 'app.public.landing',
  `default_group` tinyint(1) NOT NULL DEFAULT '0',
  `super_admin_group` tinyint(1) NOT NULL DEFAULT '0',
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_auth_groups`
--

INSERT INTO `as_auth_groups` (`id`, `group`, `slug`, `desc`, `redirect_state`, `default_group`, `super_admin_group`, `disabled`, `created`, `created_user_id`, `last_updated`, `last_updated_by`) VALUES
(1, 'Guests', 'guests', 'Non authenticated users. Public users.', 'app.public.landing', 0, 0, 0, '2016-01-13 21:00:27', 1, '2016-05-01 17:46:11', 1),
(2, 'Super Admin', 'super-admin', 'Full administrative privileges. Access to everything.', 'app.admin.dashboard', 0, 1, 0, '2016-01-13 21:00:27', 1, '2016-05-01 17:57:50', 1),
(3, 'Member', 'member', 'Authenticated user.', 'app.member.dashboard', 1, 0, 0, '2016-01-25 23:42:00', 1, '2016-05-03 17:45:05', 1),
(4, 'Game Admin', 'game-admin', 'Has the ability to host and edit games.', 'app.member.dashboard', 0, 0, 0, '2016-02-09 01:09:27', 1, '2016-04-13 01:09:13', 1),
(5, 'Venue Admin', 'venue-admin', 'User who signed up with the venue member form.', 'app.member.dashboard', 0, 0, 0, '2016-03-02 02:08:48', 1, '2016-04-13 01:09:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_auth_lookup_group_role`
--

CREATE TABLE `as_auth_lookup_group_role` (
  `id` int(11) UNSIGNED NOT NULL,
  `auth_group_id` int(11) UNSIGNED NOT NULL,
  `auth_role_id` int(11) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_auth_lookup_group_role`
--

INSERT INTO `as_auth_lookup_group_role` (`id`, `auth_group_id`, `auth_role_id`, `created`, `created_user_id`) VALUES
(1, 1, 1, '2016-01-13 21:03:51', 1),
(2, 2, 2, '2016-01-13 21:03:57', 1),
(3, 2, 3, '2016-01-13 21:04:07', 1),
(4, 2, 4, '2016-02-09 01:09:43', 1),
(5, 2, 5, '2016-01-26 01:30:01', 1),
(6, 3, 3, '2016-02-09 05:22:08', 1),
(7, 4, 4, '2016-02-09 05:22:10', 1),
(8, 5, 5, '2016-03-23 12:12:35', 1),
(9, 5, 5, '2016-03-23 12:12:35', 1),
(10, 4, 4, '2016-02-09 05:22:10', 1),
(11, 3, 3, '2016-02-09 05:22:08', 1),
(12, 2, 2, '2016-01-13 21:03:57', 1),
(13, 2, 3, '2016-01-13 21:04:07', 1),
(14, 2, 4, '2016-02-09 01:09:43', 1),
(15, 2, 5, '2016-01-26 01:30:01', 1),
(16, 1, 1, '2016-01-13 21:03:51', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_auth_lookup_role_field`
--

CREATE TABLE `as_auth_lookup_role_field` (
  `id` int(11) UNSIGNED NOT NULL,
  `auth_role_id` int(11) UNSIGNED NOT NULL,
  `auth_field_id` int(11) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_auth_lookup_role_field`
--

INSERT INTO `as_auth_lookup_role_field` (`id`, `auth_role_id`, `auth_field_id`, `created`, `created_user_id`) VALUES
(1, 3, 1, '2016-01-23 04:18:42', 1),
(6, 2, 1, '2016-01-28 20:57:49', 1),
(7, 2, 2, '2016-02-09 00:40:29', 1),
(9, 2, 2, '2016-02-09 00:47:03', 1),
(15, 5, 1, '2016-02-09 05:08:13', 1),
(16, 1, 1, '2016-02-09 05:08:33', 1),
(19, 1, 5, '2016-02-09 05:10:55', 1),
(20, 5, 5, '2016-02-09 05:45:59', 1),
(21, 2, 1, '2016-05-01 17:53:45', 1),
(22, 2, 2, '2016-05-01 17:54:22', 1),
(23, 2, 5, '2016-05-01 17:55:05', 1),
(24, 3, 1, '2016-01-23 04:18:42', 1),
(25, 2, 1, '2016-01-28 20:57:49', 1),
(26, 2, 2, '2016-02-09 00:40:29', 1),
(27, 2, 2, '2016-02-09 00:47:03', 1),
(28, 5, 1, '2016-02-09 05:08:13', 1),
(29, 1, 1, '2016-02-09 05:08:33', 1),
(30, 1, 5, '2016-02-09 05:10:55', 1),
(31, 5, 5, '2016-02-09 05:45:59', 1),
(32, 2, 1, '2016-05-01 17:53:45', 1),
(33, 2, 2, '2016-05-01 17:54:22', 1),
(34, 2, 5, '2016-05-01 17:55:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_auth_lookup_user_group`
--

CREATE TABLE `as_auth_lookup_user_group` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `auth_group_id` int(11) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_auth_lookup_user_group`
--

INSERT INTO `as_auth_lookup_user_group` (`id`, `user_id`, `auth_group_id`, `created`, `created_user_id`) VALUES
(1, 1, 2, '2016-01-13 21:02:04', 1),
(2, 1, 3, '2016-04-27 14:19:59', 1),
(3, 1, 4, '2016-02-09 06:56:54', 1),
(4, 1, 5, '2016-02-25 17:35:29', 2),
(169, 20, 3, '2016-05-06 06:23:24', 20),
(170, 21, 3, '2016-05-06 06:50:52', 21),
(171, 21, 5, '2016-05-06 06:50:53', 21),
(172, 22, 3, '2016-05-12 13:37:47', 22),
(173, 23, 3, '2016-05-12 13:38:31', 23),
(174, 24, 3, '2016-05-12 13:40:01', 24),
(175, 25, 3, '2016-05-17 23:04:19', 25),
(176, 26, 3, '2016-05-17 23:07:32', 26),
(177, 27, 3, '2016-05-22 23:05:39', 27),
(178, 28, 3, '2016-05-22 23:10:02', 28),
(179, 29, 3, '2016-05-22 23:17:07', 29),
(180, 30, 3, '2016-05-22 23:36:11', 30),
(181, 31, 3, '2016-05-22 23:41:34', 31),
(182, 32, 3, '2016-05-22 23:43:47', 32),
(183, 33, 3, '2016-05-22 23:44:50', 33),
(184, 34, 3, '2016-05-22 23:52:17', 34),
(185, 35, 3, '2016-05-23 00:00:29', 35),
(186, 36, 3, '2016-05-23 02:47:53', 36),
(187, 37, 3, '2016-05-23 02:51:41', 37),
(188, 38, 3, '2016-05-23 03:08:32', 38),
(189, 39, 3, '2016-05-23 03:16:31', 39),
(190, 40, 3, '2016-05-23 03:19:14', 40),
(191, 41, 3, '2016-05-23 03:29:54', 41),
(192, 42, 3, '2016-05-23 03:31:41', 42),
(193, 43, 3, '2016-05-23 03:33:03', 43),
(194, 44, 3, '2016-05-23 03:34:17', 44),
(195, 45, 3, '2016-05-23 03:36:06', 45),
(196, 46, 3, '2016-05-23 03:37:29', 46),
(197, 47, 3, '2016-05-23 03:38:52', 47),
(198, 48, 3, '2016-05-23 03:39:36', 48),
(199, 1, 1, '2016-05-23 12:23:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_auth_roles`
--

CREATE TABLE `as_auth_roles` (
  `id` int(11) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `desc` text,
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_auth_roles`
--

INSERT INTO `as_auth_roles` (`id`, `role`, `slug`, `desc`, `disabled`, `created`, `created_user_id`, `last_updated`, `last_updated_by`) VALUES
(1, 'Public', 'public', 'Accessible to non authenticated users.', 0, '2016-01-13 20:52:24', 1, '2016-05-01 18:02:39', 1),
(2, 'Administrative', 'administrative', 'Accessible to system administrators only. These are the people who can access the admin area where the system is configured and sensitive information can be accessed or modified.', 0, '2016-01-13 20:58:48', 1, '2016-01-27 03:05:57', 1),
(3, 'Registered User', 'registered-user', 'Accessible to registered users only. Users must login to access this content.', 0, '2016-01-13 20:58:48', 1, '2016-01-26 17:06:30', 1),
(4, 'Game Host', 'game-host', 'Trivia game host.', 0, '2016-02-09 01:09:43', 1, '2016-05-03 17:44:30', 1),
(5, 'Venue Editor', 'venue-editor', 'Has the ability to edit venues associated with this user.', 0, '2016-03-23 11:48:39', 1, '2016-03-23 11:55:48', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_email_templates`
--

CREATE TABLE `as_email_templates` (
  `id` int(11) UNSIGNED NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `from_email` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `reply_email` varchar(255) DEFAULT NULL,
  `reply_name` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `body_html` text NOT NULL,
  `body_plain` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `last_updated` datetime NOT NULL,
  `last_updated_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_email_templates`
--

INSERT INTO `as_email_templates` (`id`, `identifier`, `from_email`, `from_name`, `reply_email`, `reply_name`, `subject`, `body_html`, `body_plain`, `created`, `created_user_id`, `last_updated`, `last_updated_by`) VALUES
(1, 'SIGNUP_INVITE_PLAYER', 'communications@triviajoint.com', 'TriviaJoint.com Communications', 'communications@triviajoint.com', 'TriviaJoint.com Communications', '[LOCAL] A friend has invited you to join !@0@!', '<p>A player at <a href=''!@1@!'' target=''_blank''>!@0@!</a> would like you to join in on the fun!</p><p>Click the link above or paste the following URL into your browser to signup:</p><p>!@1@!</p>', 'A player at !@0@! would like you to join in on the fun!\\n\\rPaste the following URL into your browser to signup:\\n\\r!@1@!', '2016-04-25 20:59:22', 1, '0000-00-00 00:00:00', 1),
(2, 'SIGNUP_TEAM_INVITE', 'communications@triviajoint.com', 'TriviaJoint.com Communications', 'communications@triviajoint.com', 'TriviaJoint.com Communications', '[LOCAL] Team Up! You''ve been invited to a Trivia Team.', '<p>A player at <a href=''!@1@!'' target=''_blank''>!@0@!</a> would like you to join their team!</p><p>Click the link above or paste the following URL into your browser sign up and join the team ''!@2@!'':</p><p>!@1@!</p>', 'A player at !@0@! would like you to join their team! Paste the following URL into your browser to signup and join the team ''!@2@!'': !@1@!', '2016-04-29 03:45:18', 1, '0000-00-00 00:00:00', 1),
(3, 'TEAM_INVITE_USER', 'communications@triviajoint.com', 'TriviaJoint.com Communications', 'communications@triviajoint.com', 'TriviaJoint.com Communications', '[LOCAL] Team Up! You''ve been invited to a Trivia Team.', '<p>A player at <a href=''!@1@!'' target=''_blank''>!@0@!</a> would like you to join their team!</p><p>Click the link above or paste the following URL into your browser to join team ''!@2@!'':</p><p>!@1@!</p>', 'A player at !@0@! would like you to join their team! Paste the following URL into your browser to join team ''!@2@!'': !@1@!', '2016-04-25 20:57:10', 1, '0000-00-00 00:00:00', 1),
(4, 'SYSTEM_EMAIL_SERVICE_TEST_EMAIL', 'dev@triviajoint.com', 'Trivia Joint Dev Team', 'noreply@triviajoint.com', 'Do Not Reply', 'Test Email From The Dev Team', '<p>This is a <strong>test email</strong> saved in the email templates database table for <a href="!@1@!" target="_blank">!@0@!</a>.</p><p>!@2@!</p>', 'This is a test email saved in the email templates database table for !@0@! - !@1@! !@2@!', '2016-05-16 18:42:03', 1, '0000-00-00 00:00:00', 1),
(5, 'NEW_USER_SIGNED_UP_ADDED_TO_TEAM', 'communications@triviajoint.com', 'TriviaJoint.com Communications', 'communications@triviajoint.com', 'TriviaJoint.com Communications', '[LOCAL]  New Team Member at !@0@!', '<p>Congratulations!</p><p>You are now a player at <a href=''!@1@!'' target=''_blank''>!@0@!</a>!</p><p>You have also been added to team <strong>!@3@!</strong>.</p><p><a href=''!@2@!'' target=''_blank''>Login Here</a> to join a game!</p>', ' Congratulations! You are now a player at !@0@!! You have also been added to team - !@3@!. Login to join a game! !@2@!', '2016-05-23 03:28:26', 1, '0000-00-00 00:00:00', 1),
(6, 'NEW_USER_SIGNED_UP', 'communications@triviajoint.com', 'TriviaJoint.com Communications', 'communications@triviajoint.com', 'TriviaJoint.com Communications', '[LOCAL] New Player at !@0@!', '<p>Congratulations!</p><p>You are now a player at <a href=''!@1@!'' target=''_blank''>!@0@!</a>!</p><p><a href=''!@2@!'' target=''_blank''>Login Here</a> to join the fun!</p>', 'Congratulations! You are now a player at !@0@!. Login to join the fun! !@2@!', '2016-05-23 03:28:26', 1, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_logs_action_tracking`
--

CREATE TABLE `as_logs_action_tracking` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(25) NOT NULL,
  `code` text NOT NULL,
  `action` text NOT NULL,
  `http_referer` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `as_logs_login_location`
--

CREATE TABLE `as_logs_login_location` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(25) DEFAULT NULL,
  `user_agent` text,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_logs_login_location`
--

INSERT INTO `as_logs_login_location` (`id`, `user_id`, `ip_address`, `user_agent`, `created`) VALUES
(1, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-06 06:21:30'),
(2, 20, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-06 06:23:24'),
(3, 21, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-06 06:50:53'),
(4, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-06 06:59:46'),
(5, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-06 12:17:18'),
(6, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-12 11:42:26'),
(7, 23, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-12 13:38:32'),
(8, 24, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-12 13:40:01'),
(9, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-12 16:04:26'),
(10, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', '2016-05-12 17:21:19'),
(11, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-15 17:39:53'),
(12, 25, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-17 23:04:20'),
(13, 26, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-17 23:07:33'),
(14, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-22 20:01:36'),
(15, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-22 21:17:23'),
(16, 27, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-22 23:05:40'),
(17, 28, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-22 23:10:02'),
(18, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-22 23:14:02'),
(19, 29, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-22 23:17:07'),
(20, 33, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-22 23:44:50'),
(21, 34, '127.0.0.1', NULL, '2016-05-22 23:52:18'),
(22, 1, '127.0.0.1', NULL, '2016-05-22 23:53:48'),
(23, 1, '127.0.0.1', NULL, '2016-05-22 23:54:11'),
(24, 1, '127.0.0.1', NULL, '2016-05-22 23:54:26'),
(25, 1, '127.0.0.1', NULL, '2016-05-22 23:54:48'),
(26, 1, '127.0.0.1', NULL, '2016-05-22 23:54:58'),
(27, 1, '127.0.0.1', NULL, '2016-05-22 23:57:28'),
(28, 35, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 00:00:29'),
(29, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 00:25:25'),
(30, 36, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 02:47:53'),
(31, 37, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 02:51:41'),
(32, 38, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 03:08:33'),
(33, 39, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 03:16:31'),
(34, 40, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 03:19:14'),
(35, 41, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 03:29:54'),
(36, 42, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 03:31:41'),
(37, 43, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 03:33:03'),
(38, 44, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 03:34:18'),
(39, 45, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 03:36:06'),
(40, 46, '127.0.0.1', NULL, '2016-05-23 03:37:29'),
(41, 47, '127.0.0.1', NULL, '2016-05-23 03:38:53'),
(42, 48, '127.0.0.1', NULL, '2016-05-23 03:39:36'),
(43, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-23 11:45:33'),
(44, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-26 13:04:38'),
(45, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36', '2016-05-28 12:37:05'),
(46, 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36', '2016-06-04 04:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `as_store_cart_sessions`
--

CREATE TABLE `as_store_cart_sessions` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(3) UNSIGNED NOT NULL DEFAULT '1',
  `origional_price` decimal(7,2) NOT NULL DEFAULT '0.00',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `as_store_categories`
--

CREATE TABLE `as_store_categories` (
  `id` int(11) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_store_categories`
--

INSERT INTO `as_store_categories` (`id`, `category`, `identifier`, `description`, `created`, `created_user_id`, `last_updated`, `last_updated_by`) VALUES
(1, 'Case Studies', 'case-studies', 'Legal case studies.', '2016-05-29 03:31:59', 1, '2016-05-29 03:31:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_store_products`
--

CREATE TABLE `as_store_products` (
  `id` int(11) UNSIGNED NOT NULL,
  `item` varchar(255) NOT NULL DEFAULT '',
  `tagline` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `quantity_available` int(6) UNSIGNED DEFAULT NULL,
  `full_price` decimal(7,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `current_price` decimal(7,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_store_products`
--

INSERT INTO `as_store_products` (`id`, `item`, `tagline`, `description`, `quantity_available`, `full_price`, `current_price`, `created`, `created_user_id`, `last_updated`, `last_updated_by`) VALUES
(1, 'Case No 001', 'Its 001!', 'Case #001. Very important.', NULL, '1578.13', '999.99', '2016-05-29 03:39:56', 1, '2016-05-29 03:39:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_store_tags`
--

CREATE TABLE `as_store_tags` (
  `id` int(11) UNSIGNED NOT NULL,
  `tag` varchar(255) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_store_tags`
--

INSERT INTO `as_store_tags` (`id`, `tag`, `identifier`, `description`, `created`, `created_user_id`, `last_updated`, `last_updated_by`) VALUES
(1, 'Medical Malpractice', 'medical-malpractice', 'Medical malpractice related case studies.', '2016-05-29 03:36:56', 1, '2016-05-29 03:36:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_system_config`
--

CREATE TABLE `as_system_config` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` longtext,
  `description` varchar(255) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(11) UNSIGNED NOT NULL,
  `disabled` tinyint(1) NOT NULL DEFAULT '0',
  `indestructible` tinyint(1) NOT NULL DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_system_config`
--

INSERT INTO `as_system_config` (`id`, `name`, `value`, `description`, `created`, `created_user_id`, `last_updated`, `last_updated_by`, `disabled`, `indestructible`, `locked`) VALUES
(1, 'AUTH_COOKIE_TIMEOUT_HOURS', '72', '', '2016-01-22 04:23:45', 1, '2016-01-25 00:15:01', 1, 0, 1, 1),
(9, 'SMTP_SERVER_HOST', 'smtp.gmail.com', 'PHPMailer: ', '2016-04-08 20:34:32', 1, '2016-05-26 15:50:40', 1, 0, 1, 0),
(10, 'SMTP_SERVER_PORT', '587', 'PHPMailer: ', '2016-04-08 20:34:32', 1, '2016-04-22 22:46:16', 1, 0, 1, 0),
(11, 'SMTP_SERVER_USERNAME', 'rachellcarbone@gmail.com', 'PHPMailer: ', '2016-04-08 20:34:32', 1, '2016-05-26 15:51:21', 1, 0, 1, 0),
(12, 'SMTP_SERVER_PASSWORD', 'zawjtiijdcxdkbte', 'PHPMailer: ', '2016-04-08 20:34:32', 1, '2016-05-26 15:50:12', 1, 0, 1, 0),
(13, 'SMTP_SMTP_DEBUG', '0', 'PHPMailer: ', '2016-04-08 20:34:32', 1, '2016-04-22 21:22:14', 1, 0, 1, 0),
(14, 'SMTP_SECURE', 'tls', 'PHPMailer: ', '2016-04-08 20:34:32', 1, '2016-04-22 22:46:21', 1, 0, 1, 0),
(15, 'SMTP_AUTH', 'true', 'PHPMailer: ', '2016-04-08 20:34:32', 1, '2016-04-19 13:50:11', 1, 0, 1, 0),
(16, 'SMTP_DEBUGOUTPUT', 'error_log', 'PHPMailer: ', '2016-04-19 13:43:41', 1, '2016-04-19 15:37:09', 1, 0, 1, 1),
(17, 'WEBSITE_TITLE', 'AngularSeed.dev', 'The name of the website used for display purposes.', '2016-05-12 11:52:39', 1, '2016-05-12 15:20:41', 1, 0, 1, 0),
(18, 'WEBSITE_URL', 'http://www.seed.dev/', 'Website URL with trailing slash.', '2016-05-12 11:52:39', 1, '2016-05-26 15:30:16', 1, 0, 1, 0),
(22, 'PASSWORD_RESET_EMAIL_FROM', 'communications@triviajoint.com', '', '2016-04-15 10:30:57', 1, '2016-04-15 19:15:48', 1, 0, 1, 1),
(23, 'PASSWORD_RESET_EMAIL_SUBJECT', 'Reset Password link', '', '2016-04-15 10:30:57', 1, '2016-04-15 19:27:23', 1, 0, 1, 1),
(24, 'PASSWORD_RESET_EMAIL_BODY', '<table><tr><td>Dear, !@FIRSTNAME@! !@LASTNAME@!</td></tr><tr><td>Click on the below link to reset password</td></tr><tr><td><a href="!@WEBSITEURL@!/reset_password/!@LINKID@!">!@WEBSITEURL@!/reset_password/!@LINKID@!</a></td></tr></table>', '', '2016-04-15 11:00:32', 1, '2016-04-15 18:20:48', 1, 0, 1, 1),
(25, 'PASSWORD_RESET_ROOT_URL', 'http://triviajointapp', '', '2016-04-15 19:02:12', 1, '2016-04-15 19:07:26', 1, 0, 1, 1),
(26, 'PASSWORD_RESET_SUCCESS_EMAIL_SUBJECT', 'Your Password changed', '', '2016-04-15 10:30:57', 1, '2016-04-15 11:01:30', 1, 0, 1, 1),
(27, 'PASSWORD_RESET_SUCCESS_EMAIL_BODY', '<table><tr><td>Dear, !@FIRSTNAME@! !@LASTNAME@!</td></tr><tr><td>You have successfully changed your password.</td></tr><tr><td><a href="!@WEBSITEURL@!/login">Click here to Login</a></td></tr></table>', '', '2016-04-15 11:00:32', 1, '2016-04-15 18:20:48', 1, 0, 1, 1),
(28, 'PASSWORD_RESET_FAILED_EMAIL_SUBJECT', 'Your Password changed failed', '', '2016-04-15 10:30:57', 1, '2016-04-15 11:01:30', 1, 0, 1, 1),
(29, 'PASSWORD_RESET_FAILED_EMAIL_BODY', '<table><tr><td>Dear, !@FIRSTNAME@! !@LASTNAME@!</td></tr><tr><td>Your last attempts to change password is failed.</td></tr><tr><td><a href="!@WEBSITEURL@!/login">Click here to Login</a></td></tr></table>', '', '2016-04-15 11:00:32', 1, '2016-04-15 18:20:48', 1, 0, 1, 1),
(30, 'PASSWORD_RESET_EMAIL_FROM_NAME', 'Triviajoint', '', '2016-04-18 12:10:05', 1, '2016-04-18 12:10:18', 1, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `as_tokens_auth`
--

CREATE TABLE `as_tokens_auth` (
  `id` int(11) UNSIGNED NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires` datetime NOT NULL,
  `ip_address` varchar(25) DEFAULT NULL,
  `user_agent` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_tokens_auth`
--

INSERT INTO `as_tokens_auth` (`id`, `identifier`, `token`, `user_id`, `created`, `expires`, `ip_address`, `user_agent`) VALUES
(3, 'df2f31104327e05edf05adcf11d3361969715e0f5b389ac7c5c70c65051beec51d0a38a6d075529f72cf65fbb46c25d52dec7b26fb05d08851b64ae96875d93d', '$2y$10$pAVqwFilxsbgw..cn3T9JOv4Au.7p73Y0kSpqPHEbVCsSa8DEVQ1u', 21, '2016-05-06 06:50:53', '2016-05-06 09:50:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36'),
(4, 'aec1722f4fb0751c0cbee67ca9cffb3246ebfd0ba638f4a577e6c5a620210bc8953fcebed16a5091ea788b4bc834c2bc52ce7c565dafdce8dfd00606e29e599f', '$2y$10$1qQcMtzSrE45W0T19QKC/uRjVj.3EKmT10bVcslOJ6.O2FAbmpo3q', 1, '2016-05-06 06:59:46', '2016-05-09 08:59:46', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36'),
(5, '246d719ffffff05155cc76905cddd1ca3f9b1afca9be558fcb0c66bc683bd2958b2cf25e095b199ed0c292bcd696f481ad298661d2ead94d9f25edced06771f5', '$2y$10$oGNWmEBkw.QB/bxELlzZL.VbWr.x99LhSEjaNW3IN65.2Y9IAEM5S', 1, '2016-05-06 12:17:18', '2016-05-09 14:17:18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36'),
(7, 'fe57ccc835902cad760a7e8283d4f2685ac47122b99afea0efde415de2cdac74128600e76ba49d19abbba2c8d9e72c142f6f79331db806a81e23b99fb3925fb8', '$2y$10$7pyTfZT42NQYM6MLU3j0OeQ6iJBT2yA0W0Ko4gyMC0/JOpN1jLQhW', 23, '2016-05-12 13:38:31', '2016-05-12 16:38:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36'),
(8, '0798244c745aa08bab737cb15517b8221f9c0a925adfa268ae8e7d2df017b8f602b8fbc7b0e5d4bcd589807b2a682c8dd074919ff8235c029fe722791a2e3397', '$2y$10$UEv637c0mIm9FS5Eeq/Dh.cmxZ1jw1d5WxUdKZJpnPVhNBuR1JxZ.', 24, '2016-05-12 13:40:01', '2016-05-12 16:40:01', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36'),
(9, '68107c762bdcf9c912080896ea76957036ab4ffd21508289264fab1ae6b54c981cf2b0fa0312b8dd1894707d64358f9fd149b4d938a439fb1274f89c9ed69ce9', '$2y$10$zaKvjQaXFRIWFKruwgcIPOfEgl9Qw6uYUj2vjbxbssMnnzRpsnAhW', 1, '2016-05-12 16:04:26', '2016-05-12 19:04:26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36'),
(10, 'd0965cc3c4be8307e5200b7ac9f9959ba156ad7f057234064d60c3b2c4d9f705fe9edde1e6685a2ae056d50c2cae91e43c27a432bac19edefea830fbd73662ca', '$2y$10$zqG0eNhx3QTm4eo.g/qruOJkjXpelDhb3pQo4Q.3LurypAMA8IQAm', 1, '2016-05-12 17:21:19', '2016-05-15 19:21:19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36'),
(11, 'd7536f36dfd438e45e00f27fda086f34ebec761fd378426f84cb687600d5db62fe67ccee17ccc300be1976dafcc6ec9d732d862c458e6fb89bcd14933eb151e2', '$2y$10$TFNuOjpbucfTFpwwumyXfuRq/9h6VUOYnS6RGx7NP3dG.eR5Bqfxy', 1, '2016-05-15 17:39:53', '2016-05-18 19:39:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(12, '0e93ef24caa31d1928259ccccc6ff8ee2729b7798cc80615f4eb1ed52eb1baf5b890a3c016af529076d5f56333e0b834691eeab06f46b2bd23a50454fd659ee4', '$2y$10$ZTOXyC1htl/WUQeTiD2OVO6NVh8QUGeJR/QCegSwfUBZQPXqmQa1y', 25, '2016-05-17 23:04:20', '2016-05-18 02:04:20', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(13, '37e23fa0e726923fa865f41b234a2c491a55f04d1a567a8dd606895d146fad1e9fb53819a9ff0f2b9edac17ed2f02b4e17ad26b2fc2f89b310ab94595ca9fec2', '$2y$10$92JJ9darAEt6pVBtUaUAtumvbmyqRcKbIz3Kjvr2uRT2HYe0I1lea', 26, '2016-05-17 23:07:33', '2016-05-18 02:07:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(14, 'a59a1ba87245b21a0fb43eaa8ec3c17edb1cc94ebf870327e37ccac6732686ef68324e826cb771ef64f2c7172fa32337d492789f0b8cdb608e9fab39e6d5b014', '$2y$10$LKHKtHA5AmYtjSZnz8XB.e7rd.G1ScgOzno2Y1C6FWRzB1Ab99hMG', 1, '2016-05-22 20:01:36', '2016-05-22 23:01:36', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(15, '7d37e3a160b6370e9fbf3b8744b219ff5ed8f4fc3a8d8b66e6c0e72db221e9a1e72fdf772bb03aaabd98bd72626fca75a3552140d11de6bd9a3515eac7a8be8e', '$2y$10$eaZ5wCiUHVm3783bn9B8s.WIH4DKl3Mza5snO6R42JaolS0DGUn1u', 1, '2016-05-22 21:17:23', '2016-05-25 23:17:23', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(16, 'd639661bdc6e8cc48c3cdcf83781bd5ef96480dd679f31cdcbfc09aaa93359b4d54181082bb492009b3badf39220c03e3389ab901d02e9c59e3a46dbed7a629a', '$2y$10$l0PxqC1vMzwVzN8AaO6n9e2C2PjPo37JR12HpzKhjHYnWqCFDd.OC', 27, '2016-05-22 23:05:39', '2016-05-23 02:05:39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(18, 'df244242b94ab4bf869a7157fdd48d763c407c1a73abbf892f729c4a043065dc81744f9e482ac2d9423bf258e108eead146620f3d68a8d2570a151b37039829c', '$2y$10$8L7fmvv6SMp7YrxbLccuWOLBiDufP.p5i7T8Zfg5rWC2p/fuG32nG', 1, '2016-05-22 23:14:02', '2016-05-26 01:14:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(19, 'c7041f795cf08e18c04ffceadf40c841c93e26ddae66a8da244695833b88b062403ecd31d0c17b55ddcb97493c865999e30d5be0a8a628f3761a79895c8c5d19', '$2y$10$dd.KkDE1ZjSWrG46M.ZVP.Je3farC3v.YHm/B.ZyCXZ1DnxCyge1W', 29, '2016-05-22 23:17:07', '2016-05-23 02:17:07', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(20, '1841a02ce6ab4fdff52bfe35cf4431793186dde09b9e05fb146d78c099072e8795e25e92390224394394e9f715c849857db394f04f5a48587bca90d455e31017', '$2y$10$scselFzZeJUezrhTx/08P.nabzdxIJ1lBlnMU9rVAXiy9ixz1jKcW', 33, '2016-05-22 23:44:50', '2016-05-23 02:44:50', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(21, '6cedca000c50d031c0402ab1c74033531e3a0013a7b8402d9cd6fc57dc08fa50878199b418153b38d30e9f657bb94d6105af94ea6780646d259a5d4f5b2231b9', '$2y$10$WvKlf0FZsAl1DzIiZCSD8OHZ6T8rcKDJ3E/vE74y4zU7RhwzPeL6K', 34, '2016-05-22 23:52:18', '2016-05-23 02:52:18', '127.0.0.1', NULL),
(22, '2674fb754ab7f70141cc5dbdb2117be55005e991fddf94d20f13f9e6c4f944e477cf39c6aa3c7fab4523111eccf11066363480a89eb04cbd6c97073eca2caa0c', '$2y$10$maEV1SQyMYGbN8lm21r3E.WlJgnPNhRo.r5yaxp6b02dMaKUyIJJa', 1, '2016-05-22 23:53:48', '2016-05-23 02:53:48', '127.0.0.1', NULL),
(23, 'c347cfbf18378959041dd909462533d3ab4d29f9f3f8dd8f9ac6b9c5838ff839c40a91a944fd62df2c8c23d1afc661a62cc99f0a597adcf30792f4eed4f6c906', '$2y$10$g5iuXmIVQYcfwhb5jKxaW./mx6coM7LpiFMUYuDgP1mt5xu1KC3tm', 1, '2016-05-22 23:54:10', '2016-05-23 02:54:10', '127.0.0.1', NULL),
(24, 'd156cce8df346a23330edd61ce6b0dc1c844241ace5d4aa494a05699d0d1db0fcd14849d0b2765d8f96b3a494902ac571a00396c0cae2c2bd70be4b5f02b0620', '$2y$10$Uv5MsOwP70P0bwboYqmFAugn6VW9ed.PJc6/GST53Ypa5eh16cFOu', 1, '2016-05-22 23:54:26', '2016-05-23 02:54:26', '127.0.0.1', NULL),
(25, 'f0a6df6708b8a6755fe0f6b596a728748b8fc206c32141d5957567cc9f5538d2aecbfb0a323b8208c7a0b364f422323ed3aac9392c1028f34b6a89f1f9e5e885', '$2y$10$H0oP7qLpv8Ww9jlg9Z3hP.o.N2Ye3pRoFYPtFaDHz/.Y24kDbyptm', 1, '2016-05-22 23:54:48', '2016-05-23 02:54:48', '127.0.0.1', NULL),
(26, '8bdf56a049359db7563ce15bc5c2d9f8b6ef45c2a8178d84dc580c4aa13d88937fa8b3b4eacd4ac640f52dbd9fb8d4be47a8015212c167395a33f2346a931742', '$2y$10$Jb2S4pwexqXHVOMPGkHyPOHFILCFewth30rfqV5vPWABftiU1Z3lm', 1, '2016-05-22 23:54:58', '2016-05-23 02:54:58', '127.0.0.1', NULL),
(27, '76cc1baad51586d4fec333371fe29061c22f584fa56a0334222775d4769e5d902682e72fbda4bf4109c80a64e5ee69665b3a0b37b274b17c1bf80271894e670a', '$2y$10$NSZZ1Lij8JSJzUGvAPrAaepHm8vAXVEsGHXC2Nz/5KN7d8/h4EtRK', 1, '2016-05-22 23:57:28', '2016-05-23 02:57:28', '127.0.0.1', NULL),
(30, '4279bd0e44520edcdb12752dee22bb1f347f8fef2be2903badcdd9aaf3b9d5674261c876ae2f00d331195dcf0dbd6d0b31c349fc375d73ee2a284a7fd230a102', '$2y$10$HFGw9jwyTuxaclRhTkb2.OOBrOJQZ9dtwt6T4atkHVU5XVJ3E3vu.', 36, '2016-05-23 02:47:53', '2016-05-23 05:47:53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(31, '19518435088ba2659ff3d77764c71ab1a088d29371115ee1b545cdb71911abba667e53429c080fb8229625ba4a9e446d6f83f42261961965909fd5dbdb0276cd', '$2y$10$g6acAIcKXs6H8Uk48Ls82.VEPJ9NEPAvSr4u0awADtYZ6jD3fWjUu', 37, '2016-05-23 02:51:41', '2016-05-23 05:51:41', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(32, '86235d0711bb306bd4e57d48feddd53c83e10558ccc4b7e87c48ebdc03950b18799c000edde38552ae88ae965b38e7b47fbe37a6408c33314bfb00e5b63cdd3b', '$2y$10$JsN3bgsKASJWfT0Zs8PRGeeILSJZkDGUJo6BNGWUJ7vglunNM/uJ6', 38, '2016-05-23 03:08:33', '2016-05-23 06:08:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(33, '41966ba2bfacabf8d047deff07118ad71e80f237dd032b923f5c99e1baa2b06bbb99de223c12aaf1ad319054d67bca37189591f835339a15af58b9786aee6253', '$2y$10$9Dc5ovawBmeC/r4E2D32ouao9yVrmRDpz1fYRiffcVyNaww8FBI2a', 39, '2016-05-23 03:16:31', '2016-05-23 06:16:31', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(34, 'f1e3af55f5971f3a11cf0ae90a155c9f63c31612a7b7a6331c07a36de8456896bcccbd8afc9438ab480adf8527e7c554fc57334ec1768364a706942a3e7c66f0', '$2y$10$DCEapde/LW0yCRYJ.BxFmexhG/5fzSkN9i95lYN30NbM.EfdopwsO', 40, '2016-05-23 03:19:14', '2016-05-23 06:19:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(35, '20ebd103e8328b2b95e72ba7f6aed3d3cb7fe24f77aea833325789418e28748de37b27a321a605d52d8300010acf6273dfa5d850ac8deaeb78564269a3900cc6', '$2y$10$fnUElxCVqPdL8SJHM8xKwu2pfpuHAsfkS9aMHQO0EG0c/659hY7Ay', 41, '2016-05-23 03:29:54', '2016-05-23 06:29:54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(36, '50da398dbe85029b9d75f22b4420977cae28fbc7252456fe0c5530e84f9bb5caaa9ef75f2b1ab9986f2cef78017ea282195b00fa929aba84c0867a38ec404024', '$2y$10$6V6I0we8CSyWA2GUz1Gq0Olc9IAemTg5SSbL5tEOaP2BMga.tZrQy', 42, '2016-05-23 03:31:41', '2016-05-23 06:31:41', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(37, '4a3ab47588e7b5caf0e40c1b5a6bdb71c94d83f78e251415c12f0909fe5642434e072d9dce0846cef86e7691787152355175dd4fb27258d48134366e3aa83330', '$2y$10$3TkC7TwobFNTBn.bDWRenuLd3GbO6g/7uRsz7JvQ94mx1.FDiMNya', 43, '2016-05-23 03:33:03', '2016-05-23 06:33:03', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(38, '5be08447809fd0245b8ce54b9cd0cd93a01d54a6af469fcca31074140fcc5ceefee4398707a0b5c4c2b6b332f94e15a82c15c3e904f5be97bea743524238a15d', '$2y$10$0aYdDkT2Sd/zOry4jjLqYe6PwEVxfRBAJmCVshjC0E13l2wIrsezO', 44, '2016-05-23 03:34:18', '2016-05-23 06:34:18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(39, 'ebedb8ffbe16584e3bab07b635289cc23b802c2c631728d7028c02953933c10518f5fff501f94a456b484131b1597647aa983f406c2378dc4fbbec89c5a2dcd8', '$2y$10$gVlRGldyG4ScxWRO33kPMO7Q8dJ4E2s0HMt6Svk3HhpPHbGq80hTu', 45, '2016-05-23 03:36:06', '2016-05-23 06:36:06', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(40, '08182f108104fb14fdd9df0626630960c4fdef8dc17803d4617331807d3552e3ef535290a24780d5b4d2f2f87c42888c491eff5a89c8f2067653975732e31024', '$2y$10$h/gIyo69Y.Ny4dsfyjy6m.1k9Rn6K8XDfpRL3vsBpR5MX4H.A2zCi', 46, '2016-05-23 03:37:29', '2016-05-23 06:37:29', '127.0.0.1', NULL),
(41, '7d56bd96c1d640a4f614af7c101554a6cf28a4066ffb12d203e4de6267a804e03067b9c6b6e858c316d79e54d3ca54ac089cfd85e48848bc6bdaf92dd3f3a4a9', '$2y$10$js3EL53NfPIeM6tl7So6ee97G1qdpNsKKc9wSIMdDNCnQlT8ZdRtu', 47, '2016-05-23 03:38:53', '2016-05-23 06:38:53', '127.0.0.1', NULL),
(42, 'cd4214d95014bdf3f93c5c529d914bea880ba11d5a841c388c1b7650b418ac733badd621596cef374db7776460debe661755fc0ba40d5a5575054a36c0745aac', '$2y$10$pRDQvFu7XNFOAjCQfljoLOlIcpJjarUF25/fJNE7LvMIC6tJnc5GC', 48, '2016-05-23 03:39:36', '2016-05-23 06:39:36', '127.0.0.1', NULL),
(43, '6545491c8d950f4ce54f6597372bbd3b4d16faa225e34536ac3e1d8b7ecf5845dd6256223cd2b01a23ab8cfed19eae0a25f98eec48792101c5b2d3724d3bb0de', '$2y$10$1saRyED2e6WaNFl8RyGYh.v2WNe76Qp9jRPPURuXgw1XwkQrnof2a', 1, '2016-05-23 11:45:33', '2016-05-26 13:45:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(44, 'a339b85dc7e6e4ab95c0d2f3b10ffc49f0d8169fd60378efcb64fe266c19235745c3a3eae015c3a3deb3934b405f86dcbf0ad0449b521cbc38468256fda4dd4e', '$2y$10$YUll1FwdHoh0J6Lz0HLMzu6BXCNFBvAddbPxQS.12mfYE6uNKotAu', 1, '2016-05-26 13:04:38', '2016-05-29 15:04:38', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(45, 'aa14ace59a7de3b3a9dc880291ad8456706ab3dddf7b5defa5e4248930147063567d57860606cd929872336096354edffa126802865627bb80c3fb83ea49d42b', '$2y$10$p3f0YDu3OTLY50ddsPgSXeCYKtPz4VnJM1/NCuRd6siIwme4z9X3W', 1, '2016-05-28 12:37:05', '2016-05-31 14:37:05', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36'),
(46, 'ae9aec65fa58c304cec89abcf753f8c6ac2bc31b43c2458663688658f5f8507e6d2a75bc249372b069fa9a643ca87940b4402f2420a7594cca31483f7bdabc5d', '$2y$10$1meV4oMV6z.P8z9rwU1px.FcoZDf8Eh0GSppIZrRjWD3enzVt4L4S', 1, '2016-06-04 04:06:09', '2016-06-07 06:06:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `as_tokens_player_invites`
--

CREATE TABLE `as_tokens_player_invites` (
  `id` int(11) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `team_id` int(11) UNSIGNED DEFAULT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `response` enum('accepted','declined','','') DEFAULT NULL,
  `name_first` varchar(100) DEFAULT NULL,
  `name_last` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_user_id` int(11) UNSIGNED NOT NULL,
  `expires` datetime NOT NULL,
  `last_visited` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_tokens_player_invites`
--

INSERT INTO `as_tokens_player_invites` (`id`, `token`, `team_id`, `user_id`, `response`, `name_first`, `name_last`, `email`, `phone`, `created`, `created_user_id`, `expires`, `last_visited`) VALUES
(34, 'a6cecfb4bb53a18852817dae78413776a76d1f62', 1, 35, 'accepted', 'Rachel', 'Carbone', 'rachellcarbone+10@gmail.com', '16074354067', '2016-05-22 23:16:14', 1, '2016-05-23 19:16:14', '2016-05-22 23:58:31'),
(36, 'f1f58677a5f96a9ea8cf00b573e48c5e7fa82891', 2, 1, 'accepted', NULL, NULL, 'rachellcarbone@gmail.com', NULL, '2016-05-23 01:04:34', 1, '2016-05-29 21:04:34', NULL),
(37, '806465accfedc1280962141dc6e19e99eca1ca4a', 2, NULL, NULL, NULL, NULL, 'rachel.dotey@gmail.com', NULL, '2016-05-23 01:04:35', 1, '2016-05-29 21:04:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `as_users`
--

CREATE TABLE `as_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name_first` varchar(100) NOT NULL,
  `name_last` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified` datetime DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `accepted_terms` tinyint(1) NOT NULL DEFAULT '0',
  `facebook_id` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_updated_by` int(11) UNSIGNED NOT NULL,
  `disabled` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_users`
--

INSERT INTO `as_users` (`id`, `name_first`, `name_last`, `email`, `email_verified`, `phone`, `accepted_terms`, `facebook_id`, `password`, `created`, `last_updated`, `last_updated_by`, `disabled`) VALUES
(1, 'Rae', 'Carbone', 'rachellcarbone@gmail.com', NULL, '607-435-5555', 0, '', '$2y$10$14BO88yeqo1wx5uOR99dc.u4/xQFFLDJB9iUc/FSgyP5GafVD7RzC', '2016-02-24 01:45:21', '2016-05-23 01:47:41', 1, NULL),
(35, 'Rachel', 'Rachel', 'rachellcarbone+10@gmail.com', NULL, '16074354067', 1, NULL, '$2y$10$NHne/DHhFFrj.RxFr3guHO1wAls071Cpb/rFtWkB0sRJJmgWiXUNG', '2016-05-23 00:00:29', '2016-05-23 00:00:29', 0, NULL),
(36, 'Rachel', 'Carbone', 'rachellcarbone+11@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$6ES.HsFaA6Hn9foX8bFSdOQM/6XOM3yfoyoNMvfOXoD4BztVIcFPW', '2016-05-23 02:47:53', '2016-05-23 02:47:53', 0, NULL),
(37, 'Rachel', 'Carbone', 'rachellcarbone+12@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$TELoYhW2/2j/HISZ3K8Jm.0II3p3niS5jaBZgTQy/8F/5S2KaKmjm', '2016-05-23 02:51:40', '2016-05-23 02:51:40', 0, NULL),
(38, 'Rachel', 'Carbone', 'rachellcarbone+13@gmail.com', NULL, '16074354067', 1, NULL, '$2y$10$7LIl/O34qZLddmxJ2TBSTO/7kYXLdGXlZ030/Z28eE7M3k..tyIGy', '2016-05-23 03:08:32', '2016-05-23 03:08:33', 0, NULL),
(39, 'Rachel', 'Carbone', 'rachellcarbone+14@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$fMXHDgSeSg2Eds90bWfsyuAHBLrV6lqcDaIiTj25ukpp38MNafrLW', '2016-05-23 03:16:31', '2016-05-23 03:16:31', 0, NULL),
(40, 'Rachel', 'Carbone', 'rachellcarbone+15@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$QjPHWerRMLKAPs..cP4im.UsGLcEWQt0FRFVELL41//cMJpI3QclK', '2016-05-23 03:19:14', '2016-05-23 03:19:14', 0, NULL),
(41, 'Rachel', 'Carbone', 'rachellcarbone+16@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$7M2BIqQIMwkUq0YSEC78KOxm0L3oPhDkWznXA..QFd6Lr3GZb4jKy', '2016-05-23 03:29:54', '2016-05-23 03:29:54', 0, NULL),
(42, 'Rachel', 'Carbone', 'rachellcarbone+17@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$swZmvJqFzGQqjRiGeJu7C.dpIjPDayHLAB7Sat06p2zObiZWrk7yy', '2016-05-23 03:31:41', '2016-05-23 03:31:41', 0, NULL),
(43, 'Rachel', 'Carbone', 'rachellcarbone+18@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$7mK.OWm.8mbJPmZlmvkN2OTEKCnB1xFJHMlEdSiZLaPyU.dwZRSXq', '2016-05-23 03:33:02', '2016-05-23 03:33:02', 0, NULL),
(44, 'Rachel', 'Carbone', 'rachellcarbone+19@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$ewkiWut.BAqmUTD8IRyy0u6egGV/K/Vct8pBZoznER28Dsp2tV9FK', '2016-05-23 03:34:17', '2016-05-23 03:34:17', 0, NULL),
(45, 'Rachel', 'Carbone', 'rachellcarbone+20@gmail.com', NULL, '6074354067', 0, NULL, '$2y$10$1J2urMJB3Aqk90ddsRXK2.ROZ.9PwZjew4cMMgh8//qjX68eBL5nq', '2016-05-23 03:36:06', '2016-05-23 03:36:06', 0, NULL),
(46, 'Rachelxyz', 'Carbonexyz', 'rachellcarbone+21@gmail.com', NULL, NULL, 0, NULL, '$2y$10$H3eZdrODIFFbQBjFDe8yCO2x3hyrt9HJOs87SmJbxf9lX/CDHTCkG', '2016-05-23 03:37:29', '2016-05-23 03:37:29', 0, NULL),
(47, 'Rachelxyz', 'Carbonexyz', 'rachellcarbone+22@gmail.com', NULL, NULL, 0, NULL, '$2y$10$HqmX4a5Bnjg/2nNvTTmjjeSx9..qXBeVUyUMtrniqQnxNWxVexfhi', '2016-05-23 03:38:52', '2016-05-23 03:38:52', 0, NULL),
(48, 'Rachelxyz', 'Carbonexyz', 'rachellcarbone+23@gmail.com', NULL, NULL, 0, NULL, '$2y$10$odSdEK2GLjXuerwgN4VSf.tZoWnNIsYOGjUNhoNDy/fhmwLV7/g0O', '2016-05-23 03:39:36', '2016-05-23 03:39:36', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `as_user_additional_info`
--

CREATE TABLE `as_user_additional_info` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `as_user_additional_info`
--

INSERT INTO `as_user_additional_info` (`id`, `user_id`, `question`, `answer`, `created`) VALUES
(1, 27, 'Where did you about from us?', 'Invited by user - 1', '2016-05-22 23:05:39'),
(2, 28, 'Where did you about from us?', 'Invited by user - 1', '2016-05-22 23:10:02'),
(3, 29, 'Where did you about from us?', 'Invited by user - 1', '2016-05-22 23:17:07'),
(4, 33, 'Where did you about from us?', 'Invited by user - 1', '2016-05-22 23:44:50'),
(5, 35, 'Where did you about from us?', 'Invited by user - 1', '2016-05-23 00:00:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `as_auth_fields`
--
ALTER TABLE `as_auth_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identifier` (`identifier`),
  ADD KEY `identifier_2` (`identifier`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `as_auth_groups`
--
ALTER TABLE `as_auth_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group` (`group`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `group_2` (`group`),
  ADD KEY `slug_2` (`slug`);

--
-- Indexes for table `as_auth_lookup_group_role`
--
ALTER TABLE `as_auth_lookup_group_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_group_id` (`auth_group_id`),
  ADD KEY `auth_role_id` (`auth_role_id`),
  ADD KEY `auth_group_id_2` (`auth_group_id`),
  ADD KEY `auth_role_id_2` (`auth_role_id`);

--
-- Indexes for table `as_auth_lookup_role_field`
--
ALTER TABLE `as_auth_lookup_role_field`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_role_id` (`auth_role_id`),
  ADD KEY `auth_element_id` (`auth_field_id`),
  ADD KEY `auth_role_id_2` (`auth_role_id`),
  ADD KEY `auth_field_id` (`auth_field_id`);

--
-- Indexes for table `as_auth_lookup_user_group`
--
ALTER TABLE `as_auth_lookup_user_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `auth_group_id` (`auth_group_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `auth_group_id_2` (`auth_group_id`);

--
-- Indexes for table `as_auth_roles`
--
ALTER TABLE `as_auth_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role` (`role`),
  ADD UNIQUE KEY `slug_2` (`slug`),
  ADD KEY `role_2` (`role`),
  ADD KEY `slug` (`slug`);

--
-- Indexes for table `as_email_templates`
--
ALTER TABLE `as_email_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `as_logs_action_tracking`
--
ALTER TABLE `as_logs_action_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `as_logs_login_location`
--
ALTER TABLE `as_logs_login_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `as_store_cart_sessions`
--
ALTER TABLE `as_store_cart_sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `as_store_categories`
--
ALTER TABLE `as_store_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `identifier` (`identifier`);

--
-- Indexes for table `as_store_products`
--
ALTER TABLE `as_store_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `as_store_tags`
--
ALTER TABLE `as_store_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `as_system_config`
--
ALTER TABLE `as_system_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_name` (`name`),
  ADD KEY `name` (`name`),
  ADD KEY `name_2` (`name`);

--
-- Indexes for table `as_tokens_auth`
--
ALTER TABLE `as_tokens_auth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identifier` (`identifier`);

--
-- Indexes for table `as_tokens_player_invites`
--
ALTER TABLE `as_tokens_player_invites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `as_users`
--
ALTER TABLE `as_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`),
  ADD KEY `facebook_id` (`facebook_id`);

--
-- Indexes for table `as_user_additional_info`
--
ALTER TABLE `as_user_additional_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `as_auth_fields`
--
ALTER TABLE `as_auth_fields`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `as_auth_groups`
--
ALTER TABLE `as_auth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `as_auth_lookup_group_role`
--
ALTER TABLE `as_auth_lookup_group_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `as_auth_lookup_role_field`
--
ALTER TABLE `as_auth_lookup_role_field`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `as_auth_lookup_user_group`
--
ALTER TABLE `as_auth_lookup_user_group`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;
--
-- AUTO_INCREMENT for table `as_auth_roles`
--
ALTER TABLE `as_auth_roles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `as_email_templates`
--
ALTER TABLE `as_email_templates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `as_logs_action_tracking`
--
ALTER TABLE `as_logs_action_tracking`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `as_logs_login_location`
--
ALTER TABLE `as_logs_login_location`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `as_store_cart_sessions`
--
ALTER TABLE `as_store_cart_sessions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `as_store_categories`
--
ALTER TABLE `as_store_categories`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `as_store_products`
--
ALTER TABLE `as_store_products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `as_store_tags`
--
ALTER TABLE `as_store_tags`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `as_system_config`
--
ALTER TABLE `as_system_config`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `as_tokens_auth`
--
ALTER TABLE `as_tokens_auth`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `as_tokens_player_invites`
--
ALTER TABLE `as_tokens_player_invites`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `as_users`
--
ALTER TABLE `as_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `as_user_additional_info`
--
ALTER TABLE `as_user_additional_info`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
