-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 14, 2015 at 08:57 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `newpos`
--

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE IF NOT EXISTS `branch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `address` text NOT NULL,
  `district` varchar(200) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zipcode` varchar(100) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_branch_brand_idx` (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`id`, `name`, `brand_id`, `description`, `address`, `district`, `province`, `zipcode`, `create_time`, `update_time`) VALUES
(2, 'learn.yoyo.com', 11, 'Online', 'online', 'online', 'online', 'online', '2015-09-17 14:22:59', '2015-09-17 16:22:59'),
(4, 'รัชดา', 13, 'รัชดา', 'รัชดา', 'รัชดา', 'กรุงเทพ', '10400', '2015-11-01 01:36:06', '2015-11-01 02:36:06'),
(5, 'Website', 11, 'this will sync with jetpilot.co.th', 'jetpilot.co.th', 'jetpilot.co.th', 'bangkok', '10400', '2015-11-05 06:42:17', '2015-11-05 07:42:17');

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE IF NOT EXISTS `brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `owner_email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `owner_email` (`owner_email`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id`, `name`, `owner_email`, `password`, `create_time`, `update_time`) VALUES
(10, '1234', '1234', 'MTIzNDEyMzQ=', '2015-08-31 19:27:26', '2015-09-05 09:43:10'),
(11, 'BRM', 'brm@brm.com', 'MTIzNEJSTQ==', '2015-08-31 19:27:26', '2015-09-06 08:12:35'),
(13, 'test', 'sompop@gg.com', 'MTIzNHRlc3Q=', '2015-11-01 01:25:12', '2015-11-01 02:21:57');

-- --------------------------------------------------------

--
-- Table structure for table `brand_config`
--

CREATE TABLE IF NOT EXISTS `brand_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `brand_config`
--

INSERT INTO `brand_config` (`id`, `brand_id`, `name`, `value`) VALUES
(5, 11, 'is_connect_woocommerce', '1'),
(6, 11, 'woocommerce_url', 'https://www.jetpilot.co.th'),
(7, 11, 'woocommerce_key', 'ck_dcbc1be1c7eb230f2b86cbed56009d80e8d08d69'),
(9, 11, 'woocommerce_secret', 'cs_b793e2d8f0bc20f67684e7d21d0963c9746c3408'),
(10, 11, 'woocommerce_branch_id', '5'),
(12, 11, 'woocommerce_url_2', 'http://jetpilot.co.th'),
(13, 11, 'woocommerce_user', 'admin'),
(14, 11, 'woocommerce_password', 'tecteam');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_employee_branch1_idx` (`branch_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `branch_id`, `name`, `password`, `level`, `create_time`, `update_time`) VALUES
(1, 2, 'Sompop', '1234', 1, '2015-10-31 09:33:29', '2015-09-14 17:27:22'),
(6, 4, 'sompop', '1234', 1, '2015-11-01 02:26:44', '2015-11-01 03:26:44');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `product_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`product_id`,`branch_id`),
  KEY `fk_product_has_branch_branch1_idx` (`branch_id`),
  KEY `fk_product_has_branch_product1_idx` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE IF NOT EXISTS `invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `amount` int(11) NOT NULL,
  `data` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `paid_amount` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_branch1_idx` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `description` text,
  `image` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_product_type1_idx` (`product_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=241 ;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `product_type_id`, `price`, `description`, `image`, `create_time`, `update_time`) VALUES
(114, 'PL-26', 37, 800, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Thongs_0008_IMG_0792.jpg', '2015-11-13 08:57:09', '2015-11-13 08:57:09'),
(115, 'PL-25', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Thongs_0007_IMG_0791.jpg', '2015-11-13 08:57:15', '2015-11-13 08:57:15'),
(116, 'PL-24', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JP_LOGO_SS.jpg', '2015-11-13 08:57:25', '2015-11-13 08:57:25'),
(117, 'PL-23', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JP_LOGO_LS.jpg', '2015-11-13 08:57:36', '2015-11-13 08:57:36'),
(118, 'PL-22', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JP_ICON_SS.jpg', '2015-11-13 08:57:47', '2015-11-13 08:57:47'),
(119, 'PL-21', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JP_ICON_LS.jpg', '2015-11-13 08:57:57', '2015-11-13 08:57:57'),
(120, 'PM-78', 38, 50, '<p>test edit</p>\n', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-S14_UNDERSHORT.jpg', '2015-11-13 08:58:08', '2015-11-13 08:58:08'),
(121, 'PM-77', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-S14_ESCAPE_GEAR_BAG.jpg', '2015-11-13 08:58:16', '2015-11-13 08:58:16'),
(122, 'PA-02', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-PISTON-KEYRING-002.jpg', '2015-11-13 08:58:23', '2015-11-13 08:58:23'),
(123, 'PA-01', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-S13_TEAM_DECAL.jpg', '2015-11-13 08:58:29', '2015-11-13 08:58:29'),
(124, 'PM-76', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NOMAD-GLASSES-yel.jpg', '2015-11-13 08:58:33', '2015-11-13 08:58:33'),
(125, 'PM-75', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NOMAD-GLASSES-red.jpg', '2015-11-13 08:58:37', '2015-11-13 08:58:37'),
(126, 'PM-74', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NOMAD-GLASSES-brown.jpg', '2015-11-13 08:58:39', '2015-11-13 08:58:39'),
(127, 'PM-73', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NOMAD-GLASSES-blue.jpg', '2015-11-13 08:58:46', '2015-11-13 08:58:46'),
(128, 'PM-72', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NOMAD-GLASSES-blk.jpg', '2015-11-13 08:58:53', '2015-11-13 08:58:53'),
(129, 'PM-71', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NITRO-GLASSES_yel.jpg', '2015-11-13 08:58:56', '2015-11-13 08:58:56'),
(130, 'PM-70', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NITRO-GLASSES_red.jpg', '2015-11-13 08:59:01', '2015-11-13 08:59:01'),
(131, 'PM-69', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NITRO-GLASSES_brown.jpg', '2015-11-13 08:59:15', '2015-11-13 08:59:15'),
(132, 'PM-68', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NITRO-GLASSES_blue.jpg', '2015-11-13 08:59:25', '2015-11-13 08:59:25'),
(133, 'PM-67', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-FREERIDE-GLASSES_black.jpg', '2015-11-13 08:59:32', '2015-11-13 08:59:32'),
(134, 'PM-66', 38, 1500, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Men-logo-ss-rashie.jpg', '2015-11-13 08:59:40', '2015-11-13 08:59:40'),
(135, 'PM-65', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Men-logo-ls-rashie.jpg', '2015-11-13 08:59:48', '2015-11-13 08:59:48'),
(136, 'PM-64', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Men-corp-ss-rashie.jpg', '2015-11-13 08:59:56', '2015-11-13 08:59:56'),
(137, 'PM-63', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Men-corp-ls-rashie.jpg', '2015-11-13 09:00:04', '2015-11-13 09:00:04'),
(138, 'PM-62', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-luxe.jpg', '2015-11-13 09:00:13', '2015-11-13 09:00:13'),
(139, 'PM-61', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-kids-rashie-ss.jpg', '2015-11-13 09:00:22', '2015-11-13 09:00:22'),
(140, 'PM-60', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-kids-rashie-ls.jpg', '2015-11-13 09:00:30', '2015-11-13 09:00:30'),
(141, 'PM-59', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JPI_1154.jpg', '2015-11-13 09:00:41', '2015-11-13 09:00:41'),
(142, 'PM-58', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Jetpilot-Watersports-Caps106.jpg', '2015-11-13 09:00:48', '2015-11-13 09:00:48'),
(143, 'PM-57', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Jetpilot-Watersports-Caps103.jpg', '2015-11-13 09:00:54', '2015-11-13 09:00:54'),
(144, 'PM-56', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Jetpilot-Watersports-Caps101-copy.jpg', '2015-11-13 09:01:02', '2015-11-13 09:01:02'),
(145, 'PM-55', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Jetpilot-Watersports-Caps100.jpg', '2015-11-13 09:01:13', '2015-11-13 09:01:13'),
(146, 'PM-54', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Jetpilot-Watersports-Caps99.jpg', '2015-11-13 09:01:24', '2015-11-13 09:01:24'),
(147, 'PM-53', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Jetpilot-Watersports-Acc48.jpg', '2015-11-13 09:01:34', '2015-11-13 09:01:34'),
(148, 'PM-52', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-IMG_0909.jpg', '2015-11-13 09:01:41', '2015-11-13 09:01:41'),
(149, 'PM-51', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Jetpilot-Watersports-Acc114.jpg', '2015-11-13 09:01:52', '2015-11-13 09:01:52'),
(150, 'PM-50', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Jetpilot-Watersports-Acc113-copy.jpg', '2015-11-13 09:02:00', '2015-11-13 09:02:00'),
(151, 'PM-49', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-IMG_0168.jpg', '2015-11-13 09:02:08', '2015-11-13 09:02:08'),
(152, 'PM-48', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-FREERIDE-GLASSES_red.jpg', '2015-11-13 09:02:16', '2015-11-13 09:02:16'),
(153, 'PM-47', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0036_IMG_0955.jpg', '2015-11-13 09:02:22', '2015-11-13 09:02:22'),
(154, 'PM-46', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0033_IMG_0953.jpg', '2015-11-13 09:02:29', '2015-11-13 09:02:29'),
(155, 'PM-45', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0031_IMG_0949.jpg', '2015-11-13 09:02:36', '2015-11-13 09:02:36'),
(156, 'PM-44', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0016_IMG_0943.jpg', '2015-11-13 09:02:44', '2015-11-13 09:02:44'),
(157, 'PM-43', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0015_IMG_0939.jpg', '2015-11-13 09:02:50', '2015-11-13 09:02:50'),
(158, 'PM-42', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0014_IMG_0932.jpg', '2015-11-13 09:02:54', '2015-11-13 09:02:54'),
(159, 'PM-41', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0013_IMG_0916.jpg', '2015-11-13 09:02:58', '2015-11-13 09:02:58'),
(160, 'PM-40', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0012_IMG_0897.jpg', '2015-11-13 09:03:07', '2015-11-13 09:03:07'),
(161, 'PM-39', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0011_IMG_0893.jpg', '2015-11-13 09:03:10', '2015-11-13 09:03:10'),
(162, 'PM-38', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0010_IMG_0961.jpg', '2015-11-13 09:03:14', '2015-11-13 09:03:14'),
(163, 'PM-37', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0009_IMG_0957.jpg', '2015-11-13 09:03:20', '2015-11-13 09:03:20'),
(164, 'PM-36', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0008_IMG_0872.jpg', '2015-11-13 09:03:31', '2015-11-13 09:03:31'),
(165, 'PM-35', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0007_IMG_0889.jpg', '2015-11-13 09:03:36', '2015-11-13 09:03:36'),
(166, 'PM-34', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0006_IMG_0882.jpg', '2015-11-13 09:03:41', '2015-11-13 09:03:41'),
(167, 'PM-33', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0005_IMG_0880.jpg', '2015-11-13 09:03:45', '2015-11-13 09:03:45'),
(168, 'PM-32', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0003_IMG_0875.jpg', '2015-11-13 09:03:49', '2015-11-13 09:03:49'),
(169, 'PM-31', 38, 600, '', 'http://localhost/ba/web/uploads/169.jpg', '2015-12-08 21:50:51', '2015-12-08 21:50:51'),
(170, 'PM-30', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0001_IMG_0858.jpg', '2015-11-13 09:03:59', '2015-11-13 09:03:59'),
(171, 'PM-29', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Caps_0000_IMG_0915.jpg', '2015-11-13 09:04:02', '2015-11-13 09:04:02'),
(172, 'PM-28', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-BOTTLE_OPENER-copy.jpg', '2015-11-13 09:04:06', '2015-11-13 09:04:06'),
(173, 'PM-27', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-BODY_GEAR_bag.jpg', '2015-11-13 09:04:09', '2015-11-13 09:04:09'),
(174, 'PM-26', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Bags_0023_IMG_1036.jpg', '2015-11-13 09:04:12', '2015-11-13 09:04:12'),
(175, 'PM-25', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Bags_0021_IMG_1025.jpg', '2015-11-13 09:04:17', '2015-11-13 09:04:17'),
(176, 'PM-24', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Bags_0009_IMG_1004.jpg', '2015-11-13 09:04:19', '2015-11-13 09:04:19'),
(177, 'PM-23', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Bags_0008_IMG_0998.jpg', '2015-11-13 09:04:23', '2015-11-13 09:04:23'),
(178, 'PM-22', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F-Bags_0001_IMG_0972.jpg', '2015-11-13 09:04:27', '2015-11-13 09:04:27'),
(179, 'PM-21', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_ADDICT-GLASSES_mbred.jpg', '2015-11-13 09:04:31', '2015-11-13 09:04:31'),
(180, 'PW-012', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4299-F-ฟ้า1.jpg', '2015-11-13 09:04:35', '2015-11-13 09:04:35'),
(181, 'PW-011', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4298-F-แดง1.jpg', '2015-11-13 09:04:39', '2015-11-13 09:04:39'),
(182, 'PW-010', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4297-F-ใน-ดำฟ้า1.jpg', '2015-11-13 09:04:54', '2015-11-13 09:04:54'),
(183, 'PW-009', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4296-F-เขียว.jpg', '2015-11-13 09:04:59', '2015-11-13 09:04:59'),
(184, 'PW-008', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4292-F-ดำ1.jpg', '2015-11-13 09:05:03', '2015-11-13 09:05:03'),
(185, 'PW-007', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4288-น้ำเงิน1.jpg', '2015-11-13 09:05:08', '2015-11-13 09:05:08'),
(186, 'PW-006', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4231-F-ฟ้า.jpg', '2015-11-13 09:05:14', '2015-11-13 09:05:14'),
(187, 'PW-005', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4228-F-แดง.jpg', '2015-11-13 09:05:17', '2015-11-13 09:05:17'),
(188, 'PW-004', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4209-F-ชมพู.jpg', '2015-11-13 09:05:21', '2015-11-13 09:05:21'),
(189, 'PW-003', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4208-F-ชมพู1.jpg', '2015-11-13 09:05:27', '2015-11-13 09:05:27'),
(190, 'PW-002', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4201-F-เหลือง.jpg', '2015-11-13 09:05:30', '2015-11-13 09:05:30'),
(191, 'PW-001', 40, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/JA4145-F-แดง.jpg', '2015-11-13 09:05:38', '2015-11-13 09:05:38'),
(192, 'PM-20', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0015_IMG_0812.jpg', '2015-11-13 09:05:43', '2015-11-13 09:05:43'),
(193, 'PM-19', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0013_IMG_0970.jpg', '2015-11-13 09:05:51', '2015-11-13 09:05:51'),
(194, 'PM-18', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0012_IMG_0967.jpg', '2015-11-13 09:05:54', '2015-11-13 09:05:54'),
(195, 'PM-17', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0011_IMG_0851.jpg', '2015-11-13 09:05:57', '2015-11-13 09:05:57'),
(196, 'PM-16', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0010_IMG_0848.jpg', '2015-11-13 09:06:01', '2015-11-13 09:06:01'),
(197, 'PM-15', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0009_IMG_0839.jpg', '2015-11-13 09:06:07', '2015-11-13 09:06:07'),
(198, 'PM-14', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0008_IMG_0837.jpg', '2015-11-13 09:06:11', '2015-11-13 09:06:11'),
(199, 'PM-13', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0007_IMG_0817.jpg', '2015-11-13 09:06:14', '2015-11-13 09:06:14'),
(200, 'PM-012', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0005_IMG_0748.jpg', '2015-11-13 09:06:17', '2015-11-13 09:06:17'),
(201, 'PM-011', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0004_IMG_1105.jpg', '2015-11-13 09:06:21', '2015-11-13 09:06:21'),
(202, 'PM-010', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0004_IMG_0747.jpg', '2015-11-13 09:06:25', '2015-11-13 09:06:25'),
(203, 'PM-009', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0003_IMG_1103.jpg', '2015-11-13 09:06:29', '2015-11-13 09:06:29'),
(204, 'PM-008', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0001_IMG_0742.jpg', '2015-11-13 09:06:34', '2015-11-13 09:06:34'),
(205, 'PM-007', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0000_IMG_0741.jpg', '2015-11-13 09:06:37', '2015-11-13 09:06:37'),
(206, 'PM-006', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_2S14703_ROCK_OUT_BOARK.jpg', '2015-11-13 09:06:42', '2015-11-13 09:06:42'),
(207, 'PM-005', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_2S13_VORTEX_THONGS.jpg', '2015-11-13 09:06:46', '2015-11-13 09:06:46'),
(208, 'PM-004', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_PI_54001.jpg', '2015-11-13 09:06:48', '2015-11-13 09:06:48'),
(209, 'PM-003', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_PI_5799.jpg', '2015-11-13 09:06:52', '2015-11-13 09:06:52'),
(210, 'PM-002', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_PI_5803.jpg', '2015-11-13 09:06:57', '2015-11-13 09:06:57'),
(211, 'PM-001', 38, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_PI_5400.jpg', '2015-11-13 09:07:02', '2015-11-13 09:07:02'),
(212, 'PL-020', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Jetpilot-Girls-Wetsuits24.jpg', '2015-11-13 09:07:08', '2015-11-13 09:07:08'),
(213, 'PL-019', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Jetpilot-Girls-Wetsuits23.jpg', '2015-11-13 09:07:19', '2015-11-13 09:07:19'),
(214, 'PL-018', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_IMG_1100.jpg', '2015-11-13 09:07:23', '2015-11-13 09:07:23'),
(215, 'PL-017', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_IMG_1097.jpg', '2015-11-13 09:07:26', '2015-11-13 09:07:26'),
(216, 'PL-016', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_IMG_0977.jpg', '2015-11-13 09:07:32', '2015-11-13 09:07:32'),
(217, 'PL-015', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_GIRL-WALLET-01.jpg', '2015-11-13 09:07:36', '2015-11-13 09:07:36'),
(218, 'PL-014', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Caps_0015_IMG_0939.jpg', '2015-11-13 09:07:41', '2015-11-13 09:07:41'),
(219, 'PL-013', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Caps_0005_IMG_088F_0.jpg', '2015-11-13 09:07:49', '2015-11-13 09:07:49'),
(220, 'PL-012', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Caps_0016_IMG_0943.jpg', '2015-11-13 09:07:51', '2015-11-13 09:07:51'),
(221, 'PL-011', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Caps_0003_IMG_0875.jpg', '2015-11-13 09:07:55', '2015-11-13 09:07:55'),
(222, 'PL-010', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Bags_0029_IMG_1116.jpg', '2015-11-13 09:07:58', '2015-11-13 09:07:58'),
(223, 'PL-009', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Bags_0011_IMG_1016.jpg', '2015-11-13 09:08:04', '2015-11-13 09:08:04'),
(224, 'PL-008', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Bags_0004_IMG_0979.jpg', '2015-11-13 09:08:09', '2015-11-13 09:08:09'),
(225, 'PL-007', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0014_IMG_0793.jpg', '2015-11-13 09:08:13', '2015-11-13 09:08:13'),
(226, 'PL-006', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0003_IMG_0745.jpg', '2015-11-13 09:08:21', '2015-11-13 09:08:21'),
(227, 'PL-005', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0002_IMG_0744.jpg', '2015-11-13 09:08:24', '2015-11-13 09:08:24'),
(228, 'PL-004', 39, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_Acc_0000_Layer-2.jpg', '2015-11-13 09:08:28', '2015-11-13 09:08:28'),
(229, 'PL-003', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_PI_5801.jpg', '2015-11-13 09:08:38', '2015-11-13 09:08:38'),
(230, 'PL-002', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_PI_5796.jpg', '2015-11-13 09:08:44', '2015-11-13 09:08:44'),
(231, 'PL-001', 37, 0, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/09/F_PI_5400.jpg', '2015-11-13 09:08:48', '2015-11-13 09:08:48'),
(232, 'Vortex w15 youth tee', 40, 5000, '<p>asdasdasdasd</p>\n', 'https://www.jetpilot.co.th/wp-content/uploads/2015/07/p3.jpg', '2015-11-13 09:08:51', '2015-11-13 09:08:51'),
(233, 'Vortex w15 youth tee', 40, 750, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/07/p1.jpg', '2015-11-13 09:08:56', '2015-11-13 09:08:56'),
(234, 'Vortex w15 youth tee', 40, 1100, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/07/p2.jpg', '2015-11-13 09:08:59', '2015-11-13 09:08:59'),
(235, 'Vortex w15 youth tee', 40, 1400, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/07/p4.jpg', '2015-11-13 09:09:03', '2015-11-13 09:09:03'),
(236, 'Vortex w15 youth tee', 40, 1500, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/07/p5.jpg', '2015-11-13 09:09:06', '2015-11-13 09:09:06'),
(237, 'Vortex w15 youth tee', 40, 50, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/07/p6.jpg', '2015-11-13 09:09:09', '2015-11-13 09:09:09'),
(238, 'Vortex w15 youth tee', 40, 1550, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/07/p7.jpg', '2015-11-13 09:09:15', '2015-11-13 09:09:15'),
(239, 'Vortex w15 youth tee', 40, 50, '', 'https://www.jetpilot.co.th/wp-content/uploads/2015/07/p8.jpg', '2015-11-13 09:09:22', '2015-11-13 09:09:22'),
(240, 'test', 37, 30, 'test', NULL, '2015-11-18 08:30:00', '2015-11-18 08:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_has_product_type`
--

CREATE TABLE IF NOT EXISTS `product_has_product_type` (
  `product_id` int(11) DEFAULT NULL,
  `product_type_id` int(11) DEFAULT NULL,
  KEY `product_id` (`product_id`,`product_type_id`),
  KEY `product_type_id` (`product_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_has_product_type`
--

INSERT INTO `product_has_product_type` (`product_id`, `product_type_id`) VALUES
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 37),
(NULL, 38),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 39),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(NULL, 40),
(169, 37),
(169, 39),
(240, 37);

-- --------------------------------------------------------

--
-- Table structure for table `product_meta`
--

CREATE TABLE IF NOT EXISTS `product_meta` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_meta`
--

INSERT INTO `product_meta` (`product_id`, `name`, `value`, `create_time`) VALUES
(114, 'woocommerce_id', '782', '2015-11-13 08:57:15'),
(115, 'woocommerce_id', '783', '2015-11-13 08:57:25'),
(116, 'woocommerce_id', '784', '2015-11-13 08:57:36'),
(117, 'woocommerce_id', '785', '2015-11-13 08:57:47'),
(118, 'woocommerce_id', '786', '2015-11-13 08:57:57'),
(119, 'woocommerce_id', '787', '2015-11-13 08:58:08'),
(120, 'woocommerce_id', '788', '2015-11-13 08:58:16'),
(121, 'woocommerce_id', '789', '2015-11-13 08:58:22'),
(122, 'woocommerce_id', '790', '2015-11-13 08:58:29'),
(123, 'woocommerce_id', '791', '2015-11-13 08:58:33'),
(124, 'woocommerce_id', '792', '2015-11-13 08:58:37'),
(125, 'woocommerce_id', '793', '2015-11-13 08:58:39'),
(126, 'woocommerce_id', '794', '2015-11-13 08:58:46'),
(127, 'woocommerce_id', '795', '2015-11-13 08:58:53'),
(128, 'woocommerce_id', '796', '2015-11-13 08:58:56'),
(129, 'woocommerce_id', '797', '2015-11-13 08:59:01'),
(130, 'woocommerce_id', '798', '2015-11-13 08:59:15'),
(131, 'woocommerce_id', '799', '2015-11-13 08:59:25'),
(132, 'woocommerce_id', '800', '2015-11-13 08:59:32'),
(133, 'woocommerce_id', '801', '2015-11-13 08:59:40'),
(134, 'woocommerce_id', '802', '2015-11-13 08:59:48'),
(135, 'woocommerce_id', '803', '2015-11-13 08:59:56'),
(136, 'woocommerce_id', '804', '2015-11-13 09:00:04'),
(137, 'woocommerce_id', '805', '2015-11-13 09:00:13'),
(138, 'woocommerce_id', '806', '2015-11-13 09:00:22'),
(139, 'woocommerce_id', '807', '2015-11-13 09:00:30'),
(140, 'woocommerce_id', '808', '2015-11-13 09:00:41'),
(141, 'woocommerce_id', '809', '2015-11-13 09:00:48'),
(142, 'woocommerce_id', '810', '2015-11-13 09:00:54'),
(143, 'woocommerce_id', '811', '2015-11-13 09:01:02'),
(144, 'woocommerce_id', '812', '2015-11-13 09:01:12'),
(145, 'woocommerce_id', '813', '2015-11-13 09:01:24'),
(146, 'woocommerce_id', '814', '2015-11-13 09:01:34'),
(147, 'woocommerce_id', '815', '2015-11-13 09:01:41'),
(148, 'woocommerce_id', '816', '2015-11-13 09:01:52'),
(149, 'woocommerce_id', '817', '2015-11-13 09:02:00'),
(150, 'woocommerce_id', '818', '2015-11-13 09:02:08'),
(151, 'woocommerce_id', '819', '2015-11-13 09:02:16'),
(152, 'woocommerce_id', '820', '2015-11-13 09:02:22'),
(153, 'woocommerce_id', '821', '2015-11-13 09:02:29'),
(154, 'woocommerce_id', '822', '2015-11-13 09:02:36'),
(155, 'woocommerce_id', '823', '2015-11-13 09:02:44'),
(156, 'woocommerce_id', '824', '2015-11-13 09:02:49'),
(157, 'woocommerce_id', '825', '2015-11-13 09:02:54'),
(158, 'woocommerce_id', '826', '2015-11-13 09:02:58'),
(159, 'woocommerce_id', '827', '2015-11-13 09:03:07'),
(160, 'woocommerce_id', '828', '2015-11-13 09:03:10'),
(161, 'woocommerce_id', '829', '2015-11-13 09:03:14'),
(162, 'woocommerce_id', '830', '2015-11-13 09:03:20'),
(163, 'woocommerce_id', '831', '2015-11-13 09:03:31'),
(164, 'woocommerce_id', '832', '2015-11-13 09:03:36'),
(165, 'woocommerce_id', '833', '2015-11-13 09:03:41'),
(166, 'woocommerce_id', '834', '2015-11-13 09:03:45'),
(167, 'woocommerce_id', '835', '2015-11-13 09:03:49'),
(168, 'woocommerce_id', '836', '2015-11-13 09:03:53'),
(169, 'woocommerce_id', '837', '2015-11-13 09:03:59'),
(170, 'woocommerce_id', '838', '2015-11-13 09:04:02'),
(171, 'woocommerce_id', '839', '2015-11-13 09:04:06'),
(172, 'woocommerce_id', '840', '2015-11-13 09:04:09'),
(173, 'woocommerce_id', '841', '2015-11-13 09:04:12'),
(174, 'woocommerce_id', '842', '2015-11-13 09:04:17'),
(175, 'woocommerce_id', '843', '2015-11-13 09:04:19'),
(176, 'woocommerce_id', '844', '2015-11-13 09:04:23'),
(177, 'woocommerce_id', '845', '2015-11-13 09:04:27'),
(178, 'woocommerce_id', '846', '2015-11-13 09:04:31'),
(179, 'woocommerce_id', '847', '2015-11-13 09:04:34'),
(180, 'woocommerce_id', '848', '2015-11-13 09:04:39'),
(181, 'woocommerce_id', '849', '2015-11-13 09:04:54'),
(182, 'woocommerce_id', '850', '2015-11-13 09:04:59'),
(183, 'woocommerce_id', '851', '2015-11-13 09:05:03'),
(184, 'woocommerce_id', '852', '2015-11-13 09:05:08'),
(185, 'woocommerce_id', '853', '2015-11-13 09:05:14'),
(186, 'woocommerce_id', '854', '2015-11-13 09:05:17'),
(187, 'woocommerce_id', '855', '2015-11-13 09:05:21'),
(188, 'woocommerce_id', '856', '2015-11-13 09:05:27'),
(189, 'woocommerce_id', '857', '2015-11-13 09:05:30'),
(190, 'woocommerce_id', '858', '2015-11-13 09:05:38'),
(191, 'woocommerce_id', '859', '2015-11-13 09:05:43'),
(192, 'woocommerce_id', '860', '2015-11-13 09:05:51'),
(193, 'woocommerce_id', '861', '2015-11-13 09:05:54'),
(194, 'woocommerce_id', '862', '2015-11-13 09:05:57'),
(195, 'woocommerce_id', '863', '2015-11-13 09:06:01'),
(196, 'woocommerce_id', '864', '2015-11-13 09:06:07'),
(197, 'woocommerce_id', '865', '2015-11-13 09:06:11'),
(198, 'woocommerce_id', '866', '2015-11-13 09:06:14'),
(199, 'woocommerce_id', '867', '2015-11-13 09:06:17'),
(200, 'woocommerce_id', '868', '2015-11-13 09:06:21'),
(201, 'woocommerce_id', '869', '2015-11-13 09:06:25'),
(202, 'woocommerce_id', '870', '2015-11-13 09:06:28'),
(203, 'woocommerce_id', '871', '2015-11-13 09:06:34'),
(204, 'woocommerce_id', '872', '2015-11-13 09:06:37'),
(205, 'woocommerce_id', '873', '2015-11-13 09:06:42'),
(206, 'woocommerce_id', '874', '2015-11-13 09:06:46'),
(207, 'woocommerce_id', '875', '2015-11-13 09:06:48'),
(208, 'woocommerce_id', '876', '2015-11-13 09:06:52'),
(209, 'woocommerce_id', '877', '2015-11-13 09:06:56'),
(210, 'woocommerce_id', '878', '2015-11-13 09:07:02'),
(211, 'woocommerce_id', '879', '2015-11-13 09:07:08'),
(212, 'woocommerce_id', '880', '2015-11-13 09:07:19'),
(213, 'woocommerce_id', '881', '2015-11-13 09:07:22'),
(214, 'woocommerce_id', '882', '2015-11-13 09:07:26'),
(215, 'woocommerce_id', '883', '2015-11-13 09:07:32'),
(216, 'woocommerce_id', '884', '2015-11-13 09:07:36'),
(217, 'woocommerce_id', '885', '2015-11-13 09:07:41'),
(218, 'woocommerce_id', '886', '2015-11-13 09:07:49'),
(219, 'woocommerce_id', '887', '2015-11-13 09:07:51'),
(220, 'woocommerce_id', '888', '2015-11-13 09:07:55'),
(221, 'woocommerce_id', '889', '2015-11-13 09:07:58'),
(222, 'woocommerce_id', '890', '2015-11-13 09:08:04'),
(223, 'woocommerce_id', '891', '2015-11-13 09:08:09'),
(224, 'woocommerce_id', '892', '2015-11-13 09:08:13'),
(225, 'woocommerce_id', '893', '2015-11-13 09:08:21'),
(226, 'woocommerce_id', '894', '2015-11-13 09:08:24'),
(227, 'woocommerce_id', '895', '2015-11-13 09:08:28'),
(228, 'woocommerce_id', '896', '2015-11-13 09:08:38'),
(229, 'woocommerce_id', '897', '2015-11-13 09:08:44'),
(230, 'woocommerce_id', '898', '2015-11-13 09:08:48'),
(231, 'woocommerce_id', '899', '2015-11-13 09:08:51'),
(232, 'woocommerce_id', '900', '2015-11-13 09:08:56'),
(233, 'woocommerce_id', '901', '2015-11-13 09:08:59'),
(234, 'woocommerce_id', '902', '2015-11-13 09:09:03'),
(235, 'woocommerce_id', '903', '2015-11-13 09:09:06'),
(236, 'woocommerce_id', '904', '2015-11-13 09:09:09'),
(237, 'woocommerce_id', '905', '2015-11-13 09:09:15'),
(238, 'woocommerce_id', '906', '2015-11-13 09:09:22'),
(239, 'woocommerce_id', '907', '2015-11-13 09:09:30'),
(240, 'woocommerce_id', '911', '2015-11-18 08:30:08');

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE IF NOT EXISTS `product_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_type_brand1_idx` (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `product_type`
--

INSERT INTO `product_type` (`id`, `name`, `brand_id`, `create_time`, `update_time`) VALUES
(37, 'Ladies Apparel', 11, '2015-11-13 08:15:25', '2015-11-13 08:15:22'),
(38, 'Mens Apparel', 11, '2015-11-13 08:23:05', '2015-11-13 08:23:03'),
(39, 'Accessories', 11, '2015-11-13 08:58:23', '2015-11-13 08:58:22'),
(40, 'Watersports', 11, '2015-11-13 09:04:35', '2015-11-13 09:04:34');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE IF NOT EXISTS `receipt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` text NOT NULL,
  `branch_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_receipt_branch1_idx` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `branch_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_sale_branch1_idx` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sale_has_product`
--

CREATE TABLE IF NOT EXISTS `sale_has_product` (
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sale_id`,`product_id`),
  KEY `fk_sale_has_product_product1_idx` (`product_id`),
  KEY `fk_sale_has_product_sale1_idx` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branch`
--
ALTER TABLE `branch`
  ADD CONSTRAINT `fk_branch_brand` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `brand_config`
--
ALTER TABLE `brand_config`
  ADD CONSTRAINT `brand_config_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `fk_employee_branch1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_product_has_branch_branch1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_product_has_branch_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `fk_invoice_branch1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_product_type1` FOREIGN KEY (`product_type_id`) REFERENCES `product_type` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `product_has_product_type`
--
ALTER TABLE `product_has_product_type`
  ADD CONSTRAINT `product_has_product_type_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `product_has_product_type_ibfk_2` FOREIGN KEY (`product_type_id`) REFERENCES `product_type` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `product_meta`
--
ALTER TABLE `product_meta`
  ADD CONSTRAINT `product_meta_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_type`
--
ALTER TABLE `product_type`
  ADD CONSTRAINT `fk_product_type_brand1` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `receipt`
--
ALTER TABLE `receipt`
  ADD CONSTRAINT `fk_receipt_branch1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `fk_sale_branch1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `sale_has_product`
--
ALTER TABLE `sale_has_product`
  ADD CONSTRAINT `fk_sale_has_product_product1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_sale_has_product_sale1` FOREIGN KEY (`sale_id`) REFERENCES `sale` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
