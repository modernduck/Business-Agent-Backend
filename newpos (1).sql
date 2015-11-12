-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 12, 2015 at 08:33 AM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

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
`id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `address` text NOT NULL,
  `district` varchar(200) NOT NULL,
  `province` varchar(100) NOT NULL,
  `zipcode` varchar(100) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

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
`id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `owner_email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

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
`id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brand_config`
--

INSERT INTO `brand_config` (`id`, `brand_id`, `name`, `value`) VALUES
(5, 11, 'is_connect_woocommerce', '1'),
(6, 11, 'woocommerce_url', 'https://www.jetpilot.co.th'),
(7, 11, 'woocommerce_key', 'ck_dcbc1be1c7eb230f2b86cbed56009d80e8d08d69'),
(9, 11, 'woocommerce_secret', 'cs_b793e2d8f0bc20f67684e7d21d0963c9746c3408'),
(10, 11, 'woocommerce_branch_id', '5');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE IF NOT EXISTS `employee` (
`id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
  `update_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`product_id`, `branch_id`, `count`, `update_time`) VALUES
(9, 4, 20, '2015-11-01 04:19:22');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE IF NOT EXISTS `invoice` (
`id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `amount` int(11) NOT NULL,
  `data` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `paid_amount` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
`id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `description` text,
  `image` text,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `product_type_id`, `price`, `description`, `image`, `create_time`, `update_time`) VALUES
(4, 'jujux', 6, 900, 'asdasd', NULL, '2015-09-09 18:15:19', '2015-09-09 20:15:19'),
(9, 'product A', 18, 500, 'sad', NULL, '2015-11-01 03:19:11', '2015-11-01 04:19:11'),
(61, 'PL-26', 32, 1000, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Thongs_0008_IMG_0792.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(62, 'PL-25', 32, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-Thongs_0007_IMG_0791.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(63, 'PL-24', 32, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JP_LOGO_SS.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(64, 'PL-23', 32, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JP_LOGO_LS.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(65, 'PL-22', 32, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JP_ICON_SS.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(66, 'PL-21', 32, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-JP_ICON_LS.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(67, 'PM-78', 33, 50, 'test edit', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-S14_UNDERSHORT.jpg', '2015-11-10 21:14:12', '2015-11-10 22:14:12'),
(68, 'PM-77', 33, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-S14_ESCAPE_GEAR_BAG.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(69, 'PA-02', 34, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-PISTON-KEYRING-002.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(70, 'PA-01', 34, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-S13_TEAM_DECAL.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(71, 'PM-76', 34, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NOMAD-GLASSES-yel.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(72, 'PM-75', 34, 0, '', 'http://www.jetpilot.co.th/wp-content/uploads/2015/10/F-NOMAD-GLASSES-red.jpg', '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(93, 'test meta2', 33, 42, '12345', 'http://localhost/npop.in.th/newpos/service/web/uploads/93.jpg', '2015-11-11 08:03:44', '2015-11-11 09:03:44');

-- --------------------------------------------------------

--
-- Table structure for table `product_has_product_type`
--

CREATE TABLE IF NOT EXISTS `product_has_product_type` (
  `product_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `product_meta`
--

CREATE TABLE IF NOT EXISTS `product_meta` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_meta`
--

INSERT INTO `product_meta` (`product_id`, `name`, `value`, `create_time`) VALUES
(61, 'woocommerce_id', '718', '2015-11-05 10:10:17'),
(62, 'woocommerce_id', '715', '2015-11-05 10:10:17'),
(63, 'woocommerce_id', '712', '2015-11-05 10:10:17'),
(64, 'woocommerce_id', '709', '2015-11-05 10:10:17'),
(65, 'woocommerce_id', '706', '2015-11-05 10:10:17'),
(66, 'woocommerce_id', '702', '2015-11-05 10:10:17'),
(67, 'woocommerce_id', '699', '2015-11-05 10:10:17'),
(68, 'woocommerce_id', '696', '2015-11-05 10:10:17'),
(69, 'woocommerce_id', '693', '2015-11-05 10:10:17'),
(70, 'woocommerce_id', '690', '2015-11-05 10:10:17'),
(71, 'woocommerce_id', '687', '2015-11-05 10:10:17'),
(72, 'woocommerce_id', '684', '2015-11-05 10:10:17'),
(93, 'woocommerce_id', '761', '2015-11-11 08:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_type`
--

CREATE TABLE IF NOT EXISTS `product_type` (
`id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `product_type`
--

INSERT INTO `product_type` (`id`, `name`, `brand_id`, `create_time`, `update_time`) VALUES
(6, 'ZZXXVV', 10, '2015-09-06 06:10:25', '2015-09-06 08:10:25'),
(7, 'AAA', 10, '2015-09-06 06:05:29', '2015-09-06 08:05:29'),
(18, 'test1', 13, '2015-11-01 03:19:00', '2015-11-01 04:19:00'),
(32, 'Ladies Apparel', 11, '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(33, 'Mens Apparel', 11, '2015-11-05 10:10:17', '2015-11-05 11:10:17'),
(34, 'Accessories', 11, '2015-11-05 10:10:17', '2015-11-05 11:10:17');

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE IF NOT EXISTS `receipt` (
`id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `amount` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` text NOT NULL,
  `branch_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
`id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sale_has_product`
--

CREATE TABLE IF NOT EXISTS `sale_has_product` (
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `count` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_branch_brand_idx` (`brand_id`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `owner_email` (`owner_email`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `brand_config`
--
ALTER TABLE `brand_config`
 ADD PRIMARY KEY (`id`), ADD KEY `brand_id` (`brand_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_employee_branch1_idx` (`branch_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
 ADD PRIMARY KEY (`product_id`,`branch_id`), ADD KEY `fk_product_has_branch_branch1_idx` (`branch_id`), ADD KEY `fk_product_has_branch_product1_idx` (`product_id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_invoice_branch1_idx` (`branch_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_product_product_type1_idx` (`product_type_id`);

--
-- Indexes for table `product_has_product_type`
--
ALTER TABLE `product_has_product_type`
 ADD KEY `product_id` (`product_id`,`product_type_id`), ADD KEY `product_type_id` (`product_type_id`);

--
-- Indexes for table `product_meta`
--
ALTER TABLE `product_meta`
 ADD PRIMARY KEY (`product_id`,`name`);

--
-- Indexes for table `product_type`
--
ALTER TABLE `product_type`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_product_type_brand1_idx` (`brand_id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_receipt_branch1_idx` (`branch_id`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_sale_branch1_idx` (`branch_id`);

--
-- Indexes for table `sale_has_product`
--
ALTER TABLE `sale_has_product`
 ADD PRIMARY KEY (`sale_id`,`product_id`), ADD KEY `fk_sale_has_product_product1_idx` (`product_id`), ADD KEY `fk_sale_has_product_sale1_idx` (`sale_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `brand_config`
--
ALTER TABLE `brand_config`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT for table `product_type`
--
ALTER TABLE `product_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
ADD CONSTRAINT `product_has_product_type_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `product_has_product_type_ibfk_2` FOREIGN KEY (`product_type_id`) REFERENCES `product_type` (`id`) ON DELETE CASCADE;

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
