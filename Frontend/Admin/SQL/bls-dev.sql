-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2016 at 08:54 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bls_mukesh`
--

-- --------------------------------------------------------

--
-- Table structure for table `acl_phinxlog`
--

CREATE TABLE `acl_phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acl_phinxlog`
--

INSERT INTO `acl_phinxlog` (`version`, `migration_name`, `start_time`, `end_time`) VALUES
(20141229162641, 'DbAcl', '2016-08-17 01:32:24', '2016-08-17 01:32:25');

-- --------------------------------------------------------

--
-- Table structure for table `acos`
--

CREATE TABLE `acos` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `acos`
--

INSERT INTO `acos` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, NULL, NULL, 'controllers', 1, 392),
(2, 1, NULL, NULL, 'Admins', 2, 13),
(3, 2, NULL, NULL, 'index', 3, 4),
(4, 2, NULL, NULL, 'view', 5, 6),
(5, 2, NULL, NULL, 'add', 7, 8),
(6, 2, NULL, NULL, 'edit', 9, 10),
(7, 2, NULL, NULL, 'delete', 11, 12),
(8, 1, NULL, NULL, 'Brands', 14, 25),
(9, 8, NULL, NULL, 'index', 15, 16),
(10, 8, NULL, NULL, 'view', 17, 18),
(11, 8, NULL, NULL, 'add', 19, 20),
(12, 8, NULL, NULL, 'edit', 21, 22),
(13, 8, NULL, NULL, 'delete', 23, 24),
(14, 1, NULL, NULL, 'Categories', 26, 37),
(15, 14, NULL, NULL, 'index', 27, 28),
(17, 14, NULL, NULL, 'add', 29, 30),
(18, 14, NULL, NULL, 'edit', 31, 32),
(19, 14, NULL, NULL, 'delete', 33, 34),
(20, 14, NULL, NULL, 'menu', 35, 36),
(21, 1, NULL, NULL, 'CategoryDetails', 38, 49),
(22, 21, NULL, NULL, 'index', 39, 40),
(23, 21, NULL, NULL, 'view', 41, 42),
(24, 21, NULL, NULL, 'add', 43, 44),
(25, 21, NULL, NULL, 'edit', 45, 46),
(26, 21, NULL, NULL, 'delete', 47, 48),
(27, 1, NULL, NULL, 'Colors', 50, 59),
(28, 27, NULL, NULL, 'index', 51, 52),
(29, 27, NULL, NULL, 'add', 53, 54),
(30, 27, NULL, NULL, 'edit', 55, 56),
(31, 27, NULL, NULL, 'delete', 57, 58),
(32, 1, NULL, NULL, 'Configrations', 60, 65),
(33, 32, NULL, NULL, 'index', 61, 62),
(34, 32, NULL, NULL, 'edit', 63, 64),
(35, 1, NULL, NULL, 'Countries', 66, 77),
(36, 35, NULL, NULL, 'index', 67, 68),
(37, 35, NULL, NULL, 'view', 69, 70),
(38, 35, NULL, NULL, 'add', 71, 72),
(39, 35, NULL, NULL, 'edit', 73, 74),
(40, 35, NULL, NULL, 'delete', 75, 76),
(41, 1, NULL, NULL, 'Dashboards', 78, 81),
(42, 41, NULL, NULL, 'index', 79, 80),
(43, 1, NULL, NULL, 'Groups', 82, 93),
(44, 43, NULL, NULL, 'index', 83, 84),
(45, 43, NULL, NULL, 'view', 85, 86),
(46, 43, NULL, NULL, 'add', 87, 88),
(47, 43, NULL, NULL, 'edit', 89, 90),
(48, 43, NULL, NULL, 'delete', 91, 92),
(49, 1, NULL, NULL, 'Logins', 94, 107),
(50, 49, NULL, NULL, 'index', 95, 96),
(51, 49, NULL, NULL, 'logout', 97, 98),
(52, 49, NULL, NULL, 'forget', 99, 100),
(53, 49, NULL, NULL, 'reset', 101, 102),
(54, 49, NULL, NULL, 'AfterForget', 103, 104),
(55, 1, NULL, NULL, 'Orders', 108, 117),
(56, 55, NULL, NULL, 'index', 109, 110),
(57, 55, NULL, NULL, 'view', 111, 112),
(60, 55, NULL, NULL, 'delete', 113, 114),
(61, 1, NULL, NULL, 'OrderShippings', 118, 129),
(62, 61, NULL, NULL, 'index', 119, 120),
(63, 61, NULL, NULL, 'view', 121, 122),
(64, 61, NULL, NULL, 'add', 123, 124),
(65, 61, NULL, NULL, 'edit', 125, 126),
(66, 61, NULL, NULL, 'delete', 127, 128),
(67, 1, NULL, NULL, 'OrdersProducts', 130, 141),
(68, 67, NULL, NULL, 'index', 131, 132),
(69, 67, NULL, NULL, 'view', 133, 134),
(70, 67, NULL, NULL, 'add', 135, 136),
(71, 67, NULL, NULL, 'edit', 137, 138),
(72, 67, NULL, NULL, 'delete', 139, 140),
(73, 1, NULL, NULL, 'OrdersShippings', 142, 153),
(74, 73, NULL, NULL, 'index', 143, 144),
(75, 73, NULL, NULL, 'view', 145, 146),
(76, 73, NULL, NULL, 'add', 147, 148),
(77, 73, NULL, NULL, 'edit', 149, 150),
(78, 73, NULL, NULL, 'delete', 151, 152),
(79, 1, NULL, NULL, 'OrderStatuses', 154, 165),
(80, 79, NULL, NULL, 'index', 155, 156),
(81, 79, NULL, NULL, 'view', 157, 158),
(82, 79, NULL, NULL, 'add', 159, 160),
(83, 79, NULL, NULL, 'edit', 161, 162),
(84, 79, NULL, NULL, 'delete', 163, 164),
(85, 1, NULL, NULL, 'OrderUpdateStatuses', 166, 177),
(86, 85, NULL, NULL, 'index', 167, 168),
(87, 85, NULL, NULL, 'view', 169, 170),
(88, 85, NULL, NULL, 'add', 171, 172),
(89, 85, NULL, NULL, 'edit', 173, 174),
(90, 85, NULL, NULL, 'delete', 175, 176),
(91, 1, NULL, NULL, 'ProductsAttrs', 178, 189),
(92, 91, NULL, NULL, 'index', 179, 180),
(93, 91, NULL, NULL, 'view', 181, 182),
(94, 91, NULL, NULL, 'add', 183, 184),
(95, 91, NULL, NULL, 'edit', 185, 186),
(96, 91, NULL, NULL, 'delete', 187, 188),
(97, 1, NULL, NULL, 'ProductsCategories', 190, 201),
(98, 97, NULL, NULL, 'index', 191, 192),
(99, 97, NULL, NULL, 'view', 193, 194),
(100, 97, NULL, NULL, 'add', 195, 196),
(101, 97, NULL, NULL, 'edit', 197, 198),
(102, 97, NULL, NULL, 'delete', 199, 200),
(103, 1, NULL, NULL, 'Products', 202, 221),
(104, 103, NULL, NULL, 'index', 203, 204),
(105, 103, NULL, NULL, 'view', 205, 206),
(106, 103, NULL, NULL, 'add', 207, 208),
(107, 103, NULL, NULL, 'bulkadd', 209, 210),
(108, 103, NULL, NULL, 'edit', 211, 212),
(109, 103, NULL, NULL, 'delete', 213, 214),
(110, 1, NULL, NULL, 'ProductsImages', 222, 235),
(111, 110, NULL, NULL, 'index', 223, 224),
(112, 110, NULL, NULL, 'view', 225, 226),
(113, 110, NULL, NULL, 'add', 227, 228),
(115, 110, NULL, NULL, 'edit', 229, 230),
(116, 110, NULL, NULL, 'delete', 231, 232),
(117, 110, NULL, NULL, 'deleteimage', 233, 234),
(118, 1, NULL, NULL, 'ProductsMarketingImages', 236, 249),
(119, 118, NULL, NULL, 'index', 237, 238),
(120, 118, NULL, NULL, 'view', 239, 240),
(121, 118, NULL, NULL, 'add', 241, 242),
(123, 118, NULL, NULL, 'edit', 243, 244),
(124, 118, NULL, NULL, 'delete', 245, 246),
(125, 118, NULL, NULL, 'deleteimage', 247, 248),
(126, 1, NULL, NULL, 'ProductsPrices', 250, 261),
(127, 126, NULL, NULL, 'index', 251, 252),
(128, 126, NULL, NULL, 'view', 253, 254),
(129, 126, NULL, NULL, 'add', 255, 256),
(130, 126, NULL, NULL, 'edit', 257, 258),
(131, 126, NULL, NULL, 'delete', 259, 260),
(132, 1, NULL, NULL, 'ProductsPromos', 262, 273),
(133, 132, NULL, NULL, 'index', 263, 264),
(134, 132, NULL, NULL, 'view', 265, 266),
(135, 132, NULL, NULL, 'add', 267, 268),
(136, 132, NULL, NULL, 'edit', 269, 270),
(137, 132, NULL, NULL, 'delete', 271, 272),
(138, 1, NULL, NULL, 'ProductsRelateds', 274, 285),
(139, 138, NULL, NULL, 'index', 275, 276),
(140, 138, NULL, NULL, 'view', 277, 278),
(141, 138, NULL, NULL, 'add', 279, 280),
(142, 138, NULL, NULL, 'edit', 281, 282),
(143, 138, NULL, NULL, 'delete', 283, 284),
(144, 1, NULL, NULL, 'Roles', 286, 297),
(145, 144, NULL, NULL, 'index', 287, 288),
(146, 144, NULL, NULL, 'view', 289, 290),
(147, 144, NULL, NULL, 'add', 291, 292),
(148, 144, NULL, NULL, 'edit', 293, 294),
(149, 144, NULL, NULL, 'delete', 295, 296),
(150, 1, NULL, NULL, 'SliderImages', 298, 309),
(151, 150, NULL, NULL, 'index', 299, 300),
(152, 150, NULL, NULL, 'view', 301, 302),
(153, 150, NULL, NULL, 'add', 303, 304),
(154, 150, NULL, NULL, 'edit', 305, 306),
(155, 150, NULL, NULL, 'delete', 307, 308),
(156, 1, NULL, NULL, 'UserAddresses', 310, 319),
(158, 156, NULL, NULL, 'view', 311, 312),
(159, 156, NULL, NULL, 'add', 313, 314),
(160, 156, NULL, NULL, 'edit', 315, 316),
(161, 156, NULL, NULL, 'delete', 317, 318),
(162, 1, NULL, NULL, 'UserDetails', 320, 331),
(163, 162, NULL, NULL, 'index', 321, 322),
(164, 162, NULL, NULL, 'view', 323, 324),
(165, 162, NULL, NULL, 'add', 325, 326),
(166, 162, NULL, NULL, 'edit', 327, 328),
(167, 162, NULL, NULL, 'delete', 329, 330),
(168, 1, NULL, NULL, 'Users', 332, 349),
(169, 168, NULL, NULL, 'index', 333, 334),
(170, 168, NULL, NULL, 'view', 335, 336),
(171, 168, NULL, NULL, 'add', 337, 338),
(172, 168, NULL, NULL, 'edit', 339, 340),
(173, 168, NULL, NULL, 'delete', 341, 342),
(175, 1, NULL, NULL, '~Products', 350, 351),
(176, 1, NULL, NULL, 'MyProfile', 352, 359),
(177, 176, NULL, NULL, 'index', 353, 354),
(180, 176, NULL, NULL, 'edit', 355, 356),
(182, 176, NULL, NULL, 'changepass', 357, 358),
(183, 1, NULL, NULL, 'Wishlists', 360, 371),
(184, 183, NULL, NULL, 'index', 361, 362),
(185, 183, NULL, NULL, 'view', 363, 364),
(186, 183, NULL, NULL, 'add', 365, 366),
(187, 183, NULL, NULL, 'edit', 367, 368),
(188, 183, NULL, NULL, 'delete', 369, 370),
(189, 1, NULL, NULL, 'Promotions', 372, 387),
(190, 189, NULL, NULL, 'index', 373, 374),
(191, 189, NULL, NULL, 'view', 375, 376),
(192, 189, NULL, NULL, 'add', 377, 378),
(193, 189, NULL, NULL, 'edit', 379, 380),
(194, 189, NULL, NULL, 'delete', 381, 382),
(196, 189, NULL, NULL, 'addPromotion', 383, 384),
(197, 49, NULL, NULL, 'send_email', 105, 106),
(198, 1, NULL, NULL, 'Logs', 388, 391),
(199, 198, NULL, NULL, 'index', 389, 390),
(200, 168, NULL, NULL, 'forgotpassword', 343, 344),
(201, 189, NULL, NULL, 'bulkaddpromo', 385, 386),
(203, 168, NULL, NULL, 'changestatus', 345, 346),
(205, 103, NULL, NULL, 'changestatus', 215, 216),
(206, 103, NULL, NULL, 'changestatus', 217, 218),
(207, 168, NULL, NULL, 'view', 347, 348),
(208, 103, NULL, NULL, 'changestatus', 219, 220),
(210, 55, NULL, NULL, 'invoice', 115, 116);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `group_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_dir` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `group_id`, `image`, `image_dir`, `dob`, `mobile`, `firstname`, `lastname`, `created`, `modified`) VALUES
(24, 'mukesh.kaushal@sdnainfotech.com', '$2y$10$bKtQgpy57TvVevDUGhgDPuguGlOOSUrFUNQ.joI8yVYYvk6ZnM2cu', 1, 'client-3.jpg', 'webroot\\img\\files\\Admins\\image\\', '2016-08-08', '9041160473', 'Mukesh', 'kaushal', '2016-08-17 09:35:37', '2016-08-17 13:09:14'),
(25, 'himani.arora@sdnainfotech.com', '$2y$10$6476QRWdKmSjycaYpR2GK.b7FpzootkQc8CB1oIcsvxlwCEnpecjG', 2, '', 'webroot\\img\\files\\Admins\\image\\', '2016-08-02', '2342334234', 'Himani', 'arora', '2016-08-17 09:36:09', '2016-09-22 11:57:30'),
(26, 'pradeep.danwal@sdnainfotech.com', '$2y$10$MtFvHnhcvkg9SFddGsr01uXkvKJoTTDHwQHTtAq.0q3o2BkpllDj2', 3, 'Penguins.jpg', 'webroot\\img\\files\\Admins\\image\\', '2016-08-08', '9041160473', 'Pradeep', 'dangwal', '2016-08-17 09:36:52', '2016-08-17 09:36:52');

-- --------------------------------------------------------

--
-- Table structure for table `aros`
--

CREATE TABLE `aros` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `foreign_key` int(11) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rght` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aros`
--

INSERT INTO `aros` (`id`, `parent_id`, `model`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Groups', 1, NULL, 1, 4),
(2, NULL, 'Groups', 2, NULL, 9, 12),
(3, NULL, 'Groups', 3, NULL, 5, 8),
(4, 1, 'Admins', 24, NULL, 2, 3),
(5, 2, 'Admins', 25, NULL, 10, 11),
(6, 3, 'Admins', 26, NULL, 6, 7);

-- --------------------------------------------------------

--
-- Table structure for table `aros_acos`
--

CREATE TABLE `aros_acos` (
  `id` int(11) NOT NULL,
  `aro_id` int(11) NOT NULL,
  `aco_id` int(11) NOT NULL,
  `_create` varchar(2) NOT NULL DEFAULT '0',
  `_read` varchar(2) NOT NULL DEFAULT '0',
  `_update` varchar(2) NOT NULL DEFAULT '0',
  `_delete` varchar(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 1, '1', '1', '1', '1'),
(2, 2, 1, '1', '1', '1', '1'),
(3, 2, 168, '-1', '-1', '-1', '-1'),
(4, 2, 41, '1', '1', '1', '1'),
(5, 2, 49, '1', '1', '1', '1'),
(6, 3, 1, '-1', '-1', '-1', '-1'),
(7, 3, 49, '1', '1', '1', '1'),
(8, 3, 41, '1', '1', '1', '1'),
(9, 2, 176, '1', '1', '1', '1'),
(10, 2, 2, '-1', '-1', '-1', '-1'),
(11, 2, 55, '-1', '-1', '-1', '-1'),
(12, 2, 43, '-1', '-1', '-1', '-1'),
(13, 3, 55, '1', '1', '1', '1'),
(14, 3, 79, '1', '1', '1', '1'),
(15, 3, 85, '1', '1', '1', '1'),
(16, 3, 67, '1', '1', '1', '1'),
(17, 3, 73, '1', '1', '1', '1'),
(18, 2, 73, '-1', '-1', '-1', '-1'),
(19, 2, 79, '-1', '-1', '-1', '-1'),
(20, 2, 85, '-1', '-1', '-1', '-1'),
(21, 2, 67, '-1', '-1', '-1', '-1'),
(22, 1, 168, '1', '1', '1', '1'),
(25, 1, 203, '1', '1', '1', '1'),
(26, 1, 208, '1', '1', '1', '1'),
(27, 3, 176, '1', '1', '1', '1'),
(28, 1, 55, '1', '1', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_dir` varchar(255) NOT NULL,
  `templates` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `image`, `image_dir`, `templates`, `status`, `created`, `modified`) VALUES
(1, 'Dell', 'Demo-Logo.jpg', 'webroot\\img\\files\\Brands\\image\\', '1', '1', '2016-09-22 07:41:30', '2016-09-27 10:18:23'),
(2, 'Faber', 'Branding1.png', 'webroot\\img\\files\\Brands\\image\\', '2', '1', '2016-09-27 08:35:03', '2016-09-27 10:18:31'),
(3, 'Pelikan', 'yougov-brandindex-logo.jpg', 'webroot\\img\\files\\Brands\\image\\', '3', '1', '2016-09-27 08:35:48', '2016-09-27 10:18:38'),
(4, 'Scwan', 'youtube-hed-2016_0.png', 'webroot\\img\\files\\Brands\\image\\', '1', '1', '2016-09-27 08:36:57', '2016-09-27 10:18:48');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created`, `modified`) VALUES
(1, 9, '2016-09-22 00:00:00', '2016-09-22 17:38:44');

-- --------------------------------------------------------

--
-- Table structure for table `cart_products`
--

CREATE TABLE `cart_products` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `prod_quantity` int(11) NOT NULL,
  `promotion_code` int(255) NOT NULL,
  `final_item_price` varchar(255) NOT NULL,
  `discount_rate` varchar(255) NOT NULL,
  `final_price` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cart_products`
--

INSERT INTO `cart_products` (`id`, `cart_id`, `prod_id`, `prod_quantity`, `promotion_code`, `final_item_price`, `discount_rate`, `final_price`, `created`, `modified`) VALUES
(1, 1, 1, 10, 1, '100', '0', '1000', '2016-09-21 00:00:00', '2016-09-26 01:14:22'),
(2, 1, 2, 3, 0, '100', '20', '280', '2016-09-20 00:00:00', '2016-09-26 01:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT '',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `lft`, `rght`, `name`, `created`, `modified`) VALUES
(1, NULL, 11, 60, 'Main Category', '2016-08-09 12:50:58', '2016-08-09 07:34:52'),
(2, 1, 11, 60, 'Desktop Stationery', '2016-08-09 12:50:58', '2016-10-03 05:35:56'),
(3, 1, 11, 60, 'Filing Products', '2016-08-09 12:50:58', '2016-10-03 05:36:40'),
(4, 1, 11, 60, 'Packing Supplies', '2016-08-09 12:50:58', '2016-10-03 05:37:44'),
(5, 1, 11, 60, 'Office Equipments', '2016-08-09 12:50:58', '2016-10-03 05:38:35'),
(6, 1, 11, 60, 'Paper Products', '2016-08-09 12:50:58', '2016-10-03 05:40:56'),
(7, 1, 11, 60, 'Ink & Toner', '2016-08-09 12:50:58', '2016-10-03 05:40:09'),
(10, 3, 48, 49, 'Tables', '2016-09-22 06:31:02', '2016-09-23 04:34:16'),
(11, 7, 56, 57, 'Hardware & Pantry Supplies', '2016-09-23 04:32:08', '2016-10-03 05:41:41'),
(12, 7, 58, 59, 'Food & Beverage', '2016-09-23 04:32:53', '2016-10-03 05:42:38'),
(13, 2, 50, 51, 'Letter Trayers & Drawers', '2016-09-27 07:51:28', '2016-09-27 08:15:06'),
(14, 2, 52, 53, 'Badge Reel', '2016-09-27 08:23:31', '2016-09-27 08:23:31'),
(15, 2, 54, 55, 'test', '2016-09-27 09:26:26', '2016-09-27 09:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `category_details`
--

CREATE TABLE `category_details` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_dir` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category_details`
--

INSERT INTO `category_details` (`id`, `category_id`, `image`, `image_dir`, `created`, `modified`) VALUES
(1, 2, 'desktop.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-07-25 09:42:21', '2016-10-03 05:35:56'),
(2, 3, 'filling-accessories.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-07-25 09:42:33', '2016-10-03 05:36:40'),
(3, 4, 'paper.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-08-09 07:30:58', '2016-10-03 05:37:44'),
(4, 5, 'office.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-08-09 07:31:50', '2016-10-03 05:38:35'),
(5, 6, 'writing.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-08-09 07:32:30', '2016-10-03 05:40:56'),
(6, 7, 'DISPLAY.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-08-09 07:33:36', '2016-10-03 05:40:09'),
(9, 10, 'product-template-banner.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-09-22 06:31:02', '2016-09-23 04:34:16'),
(10, 11, 'paper.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-09-23 04:32:08', '2016-10-03 05:41:41'),
(11, 12, 'DISPLAY.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-09-23 04:32:53', '2016-10-03 05:42:38'),
(12, 13, 'hardware-pantry-supplies.jpg', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-09-27 07:51:28', '2016-09-27 08:15:06'),
(13, 14, '', '', '2016-09-27 08:23:31', '2016-09-27 08:23:31'),
(14, 15, 'NoCategoryImage.png', 'webroot\\img\\files\\CategoryDetails\\image\\', '2016-09-27 09:26:26', '2016-09-27 09:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_dir` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `image`, `image_dir`, `created`, `modified`) VALUES
(2, 'azure', 'azure.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:56:47', '2016-07-26 05:56:47'),
(3, 'chartreuse', 'chartreuse.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:57:20', '2016-07-26 05:57:20'),
(4, 'coral', 'coral.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:57:34', '2016-07-26 05:57:34'),
(5, 'crimson', 'crimson.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:57:50', '2016-07-26 05:57:50'),
(6, 'forest green', 'forest_green.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:58:10', '2016-07-26 05:58:10'),
(7, 'golden', 'golden.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:58:24', '2016-07-26 05:58:24'),
(8, 'hot pink', 'hot_pink.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:58:41', '2016-07-26 05:58:41'),
(9, 'ivory', 'ivory.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:58:57', '2016-07-26 05:58:57'),
(10, 'lime', 'lime.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:59:09', '2016-07-26 05:59:09'),
(11, 'maroon', 'maroon.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:59:29', '2016-07-26 05:59:29'),
(12, 'medium blue', 'medium_blue.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 05:59:51', '2016-07-26 05:59:51'),
(13, 'navy', 'navy.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 06:00:05', '2016-07-26 06:00:05'),
(14, 'olive', 'olive.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 06:00:19', '2016-07-26 06:00:19'),
(15, 'royal blue', 'royal_blue.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 06:00:36', '2016-07-26 06:00:36'),
(16, 'salmon', 'salmon.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 06:00:52', '2016-07-26 06:00:52'),
(17, 'teal', 'teal.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 06:01:06', '2016-07-26 06:01:06'),
(18, 'wheat', 'wheat.png', 'webroot\\img\\files\\Colors\\image\\', '2016-07-26 06:01:20', '2016-07-26 06:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `configrations`
--

CREATE TABLE `configrations` (
  `id` int(11) NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `logo_dir` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `facebook_link` varchar(255) NOT NULL,
  `twitter_link` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `gst` varchar(255) NOT NULL,
  `delivery_charge` varchar(255) NOT NULL,
  `min_amt_free_delivery` varchar(255) NOT NULL,
  `adm_fdbk_email` varchar(255) NOT NULL,
  `min_amt_for_promotion` varchar(255) NOT NULL,
  `invoice_msg` text NOT NULL,
  `promo_page_1` varchar(255) NOT NULL,
  `promo_page_2` varchar(255) NOT NULL,
  `promo_page_3` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `configrations`
--

INSERT INTO `configrations` (`id`, `store_name`, `logo`, `logo_dir`, `title`, `facebook_link`, `twitter_link`, `currency`, `gst`, `delivery_charge`, `min_amt_free_delivery`, `adm_fdbk_email`, `min_amt_for_promotion`, `invoice_msg`, `promo_page_1`, `promo_page_2`, `promo_page_3`, `created`, `modified`) VALUES
(1, 'Boon Lay Stationary', 'favicon.jpg', 'webroot\\img\\files\\Configrations\\logo\\', 'BLS | Ecommerce', 'https://facebook.com/bls', 'https://twitter.com/bls', 'SGD', '7', '500', '60', 'kalpna@sdnainfotech.com', '100', 'You got this promotion.', 'value plus', 'solo plus', 'pwp', '2016-07-18 06:44:20', '2016-09-28 11:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `code`, `status`, `created`, `modified`) VALUES
(1, 'India', 'IN', '0', '2016-07-21 08:56:47', '2016-07-26 11:07:34'),
(2, 'United States', 'US', '1', '2016-07-21 08:57:17', '2016-07-21 08:57:17');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `created`, `modified`) VALUES
(1, 'Administrator', '2016-08-17 09:34:10', '2016-08-17 09:34:10'),
(2, 'System Controllers', '2016-08-17 09:34:17', '2016-08-30 05:47:24'),
(3, 'Order Processers', '2016-08-17 09:34:26', '2016-08-17 09:34:26');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `errors` text NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `errors`, `modified_by`, `modified`) VALUES
(1, ' Record no.1: Updated</br> Record no.2: Updated</br> Record no.3: Color "green" not found Record no.3: Updated</br> Record no.4: Updated</br>', 24, '2016-09-09 10:23:52'),
(2, 'Promotion - 1: Child Product it code :FI22MO7-311 not found, Promotion - 1: CASH010 not updated</br>', 24, '2016-09-03 07:05:41');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_addresses_id` int(11) NOT NULL,
  `user_comments` varchar(255) NOT NULL,
  `admin_comments` varchar(255) NOT NULL,
  `transactionCode` int(11) NOT NULL,
  `refrenceCode` int(11) NOT NULL,
  `invoiceCode` int(11) NOT NULL,
  `otherCode` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `user_addresses_id`, `user_comments`, `admin_comments`, `transactionCode`, `refrenceCode`, `invoiceCode`, `otherCode`, `created`, `modified`) VALUES
(1, 2, 1, 'dfg', 'fgdfg', 55, 656, 67676, 676756, '2016-07-19 00:00:00', '2016-07-28 17:28:11'),
(2, 50, 3, 'dummy', 'dummy', 445, 45, 45, 45, '0000-00-00 00:00:00', '2016-08-05 18:38:38');

-- --------------------------------------------------------

--
-- Table structure for table `orders_billings`
--

CREATE TABLE `orders_billings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `postalcode` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL,
  `fax_no` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders_products`
--

CREATE TABLE `orders_products` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `price` varchar(255) NOT NULL,
  `discounted_price` varchar(255) NOT NULL,
  `final_price` varchar(255) NOT NULL,
  `gst_rate` varchar(255) NOT NULL,
  `promotion_applied` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_products`
--

INSERT INTO `orders_products` (`id`, `order_id`, `product_id`, `product_quantity`, `price`, `discounted_price`, `final_price`, `gst_rate`, `promotion_applied`, `created`, `modified`) VALUES
(1, 1, 4, 5, '65', '54', '678', '', '', '2016-07-13 00:00:00', '2016-07-28 17:28:41'),
(17, 1, 46, 0, '', '', '230', '', '', '2016-07-29 07:06:07', '2016-07-29 07:06:07'),
(18, 2, 81, 2, '23', '12', '12', '', '', '0000-00-00 00:00:00', '2016-08-05 18:40:08');

-- --------------------------------------------------------

--
-- Table structure for table `orders_shippings`
--

CREATE TABLE `orders_shippings` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `postalcode` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL,
  `fax_no` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_product_promotions`
--

CREATE TABLE `order_product_promotions` (
  `id` int(11) NOT NULL,
  `orders_product_id` int(11) NOT NULL,
  `promo_code` varchar(255) NOT NULL,
  `formal_message_display` text NOT NULL,
  `discounted_amount_parent` varchar(255) NOT NULL,
  `quantity_parent` int(11) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `associated_product` varchar(255) NOT NULL,
  `child_product_id` varchar(255) NOT NULL,
  `discounted_amount_child` varchar(255) NOT NULL,
  `quantity_child` int(11) NOT NULL,
  `discount_parent_in` varchar(255) NOT NULL,
  `discount_child_in` varchar(255) NOT NULL,
  `discount_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_statuses`
--

INSERT INTO `order_statuses` (`id`, `code`, `name`, `created`, `modified`) VALUES
(1, '1', 'Pending', '2016-07-30 00:00:00', '2016-07-30 13:22:39'),
(2, '2', 'Processing', '2016-07-30 00:00:00', '2016-07-30 13:22:39'),
(3, '3', 'Delivery', '2016-08-01 00:00:00', '2016-08-01 12:17:27'),
(4, '4', 'Completed', '2016-08-01 00:00:00', '2016-08-01 12:17:27'),
(5, '5', 'Cancelled', '2016-09-14 00:00:00', '2016-09-14 15:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_update_statuses`
--

CREATE TABLE `order_update_statuses` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `product_desc` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `item_code`, `title`, `product_desc`, `created`, `modified`, `status`) VALUES
(1, 'TA10DE1-1', 'First Product', 'First Product Description', '2016-09-22 07:41:52', '2016-09-27 13:16:55', 'N'),
(2, 'TA10DE1-2', 'Second Product', 'Second Product Description', '2016-09-22 07:44:02', '2016-10-04 09:18:33', 'N'),
(3, 'TA10DE1-3', 'dffgd', 'dfgfg', '2016-09-22 13:34:47', '2016-09-27 11:14:59', 'N'),
(4, 'LE13DE1-4', 'MULTIFORM 3124071 Office Set La Linea', 'some description about A', '2016-09-27 07:57:14', '2016-10-03 13:26:55', 'N'),
(5, 'LE13DE1-5', 'MULTIFORM 103438D 2-Tier Letter Tray', 'some details for products', '2016-09-27 08:03:30', '2016-10-03 13:27:13', 'N'),
(6, 'LE13DE1-6', 'MULTIFORM 1130800 Letter Tray Combo 2', 'some details', '2016-09-27 08:05:48', '2016-09-29 13:17:51', 'N'),
(7, 'LE13SC4-7', 'MULTIFORM 102244055 The Box Media', 'some detailed about products', '2016-09-27 08:42:50', '2016-09-27 08:42:50', 'N'),
(8, 'LE13PE3-8', 'PRODUCT_NO_IMAGE', 'some details are here.', '2016-09-27 09:30:11', '2016-09-29 13:16:42', 'N'),
(9, 'LE13FA2-9', 'LE13PE3-New brand', 'Test des', '2016-09-27 11:26:51', '2016-09-27 12:08:19', 'Y'),
(10, 'BA14FA2-10', 'Notepad', 'some details are here.', '2016-09-27 11:40:16', '2016-09-27 11:42:34', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `products_attrs`
--

CREATE TABLE `products_attrs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `video_link` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `weight` varchar(255) NOT NULL,
  `packaging` varchar(255) NOT NULL,
  `uom` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `main_promo_1` varchar(255) NOT NULL,
  `main_promo_2` varchar(255) NOT NULL,
  `main_promo_3` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_attrs`
--

INSERT INTO `products_attrs` (`id`, `product_id`, `brand_id`, `model`, `video_link`, `size`, `weight`, `packaging`, `uom`, `quantity`, `main_promo_1`, `main_promo_2`, `main_promo_3`, `created`, `modified`) VALUES
(1, 1, 1, 'GT11', 'fdffd/fghghg.com', '45', '78', '88', 'FG45', 4, '0', 'solo plus', '0', '2016-09-22 07:41:52', '2016-09-27 11:12:14'),
(2, 2, 1, 'GT12', 'http://www.yahoo.com', '56', '88', '45', 'FG55', 4, '0', 'value plus', '0', '2016-09-22 07:44:02', '2016-10-04 09:18:33'),
(3, 3, 1, 'dfgdf', 'dfgd', 'fgd', 'fgdg', 'dfg', 'df', 5, '0', 'pwp', '0', '2016-09-22 13:34:47', '2016-09-27 11:14:59'),
(4, 4, 1, 'M9980', 'http://www.videolink.com', '70 pgs', '10lbs', '21', 'U009', 3, 'value plus', '0', '0', '2016-09-27 07:57:14', '2016-09-27 07:57:14'),
(5, 5, 2, 'NT001', 'http://www.videolink.com', '23', '10lbs', '21', 'U009', 4, 'value plus', '0', '0', '2016-09-27 08:03:30', '2016-09-27 08:39:11'),
(6, 6, 3, 'M9980', 'http://www.videolink.com', '70 pgs', '10lbs', '21', 'U009', 4, 'pwp', '0', '0', '2016-09-27 08:05:48', '2016-09-27 11:11:27'),
(7, 7, 4, 'M0098', 'http://www.videolink.com', '70 pgs', '10lbs', '21', 'U009', 4, 'pwp', '0', '0', '2016-09-27 08:42:50', '2016-09-27 08:42:50'),
(8, 8, 3, 'NT001', 'http://www.videolink.com', '70 pgs', '10lbs', '21', 'U009', 2, '0', '0', '0', '2016-09-27 09:30:11', '2016-09-27 09:30:11'),
(9, 9, 2, 'LE13PE3-New', '', '34', '12', '23', '45', 3, '0', '0', '0', '2016-09-27 11:26:51', '2016-09-27 11:26:51'),
(10, 10, 3, 'M-009', 'http://www.videolink.com', '12lbs', '12', '21', 'U009', 10, 'solo plus', '0', '0', '2016-09-27 11:40:16', '2016-09-27 11:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `products_categories`
--

CREATE TABLE `products_categories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_categories`
--

INSERT INTO `products_categories` (`id`, `category_id`, `product_id`, `created`, `modified`) VALUES
(1, 10, 1, '2016-09-22 07:41:52', '2016-09-22 07:41:52'),
(2, 10, 2, '2016-09-22 07:44:02', '2016-09-22 07:44:02'),
(3, 10, 3, '2016-09-22 13:34:47', '2016-09-22 13:34:47'),
(4, 14, 4, '2016-09-27 07:57:14', '2016-10-03 13:26:55'),
(5, 14, 5, '2016-09-27 08:03:30', '2016-10-03 13:27:13'),
(6, 13, 6, '2016-09-27 08:05:48', '2016-09-27 08:05:48'),
(7, 13, 7, '2016-09-27 08:42:50', '2016-09-27 08:42:50'),
(8, 13, 8, '2016-09-27 09:30:11', '2016-09-27 09:30:11'),
(9, 13, 9, '2016-09-27 11:26:51', '2016-09-27 11:26:51'),
(10, 13, 10, '2016-09-27 11:40:16', '2016-09-27 11:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `products_images`
--

CREATE TABLE `products_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_code` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_dir` varchar(255) NOT NULL,
  `color_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_images`
--

INSERT INTO `products_images` (`id`, `product_id`, `product_code`, `image`, `image_dir`, `color_id`, `status`, `created`, `modified`) VALUES
(2, 2, 'TA10DE1-2CO4', 'Chrysanthemum.jpg', 'webroot\\img\\files\\ProductsImages\\image\\', 4, 1, '2016-09-22 07:44:02', '2016-09-22 07:44:02'),
(3, 3, 'TA10DE1-3CH3', 'Chrysanthemum.jpg', 'webroot\\img\\files\\ProductsImages\\image\\', 3, 1, '2016-09-22 13:34:48', '2016-09-22 13:34:48'),
(4, 1, 'TA10DE1-1FO6', 'prod3.jpg', 'webroot\\img\\files\\ProductsImages\\image\\', 6, 1, '2016-09-23 05:01:44', '2016-09-23 05:01:44'),
(5, 4, 'LE13DE1-4AZ2', 'prod5.jpg', 'webroot\\img\\files\\ProductsImages\\image\\', 2, 1, '2016-09-27 07:57:14', '2016-09-27 07:57:14'),
(6, 5, 'LE13DE1-5CH3', 'prod7.jpg', 'webroot\\img\\files\\ProductsImages\\image\\', 3, 1, '2016-09-27 08:03:30', '2016-09-27 08:03:30'),
(7, 6, 'LE13DE1-6CR5', 'prod6.jpg', 'webroot\\img\\files\\ProductsImages\\image\\', 5, 1, '2016-09-27 08:05:48', '2016-09-27 08:05:48'),
(8, 7, 'LE13SC4-7CO4', 'BLS_15-PromotionsValuesPlus.jpg', 'webroot\\img\\files\\ProductsImages\\image\\', 4, 1, '2016-09-27 08:42:50', '2016-09-27 08:42:50'),
(9, 8, 'LE13PE3-8AZ2', '', '', 2, 1, '2016-09-27 09:30:11', '2016-09-27 09:30:11'),
(10, 9, 'LE13FA2-9RO15', '', '', 15, 1, '2016-09-27 11:26:52', '2016-09-27 11:26:52'),
(11, 10, 'BA14FA2-10CH3', 'american-express.jpg', 'webroot\\img\\files\\ProductsImages\\image\\', 3, 1, '2016-09-27 11:40:16', '2016-09-27 11:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `products_marketing_images`
--

CREATE TABLE `products_marketing_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_dir` varchar(255) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_marketing_images`
--

INSERT INTO `products_marketing_images` (`id`, `product_id`, `image`, `image_dir`, `created`) VALUES
(4, 2, 'Koala.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-22 07:44:02'),
(5, 2, 'Lighthouse.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-22 07:44:02'),
(6, 2, 'Penguins.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-22 07:44:02'),
(7, 2, 'Tulips.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-22 07:44:02'),
(9, 3, 'Lighthouse.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-22 13:34:48'),
(10, 3, 'Penguins.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-22 13:34:48'),
(11, 3, 'Tulips.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-22 13:34:48'),
(12, 1, 'prod1.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-23 05:01:54'),
(13, 1, 'prod2.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-23 05:03:15'),
(14, 1, 'prod2b.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-23 05:03:24'),
(15, 4, 'prod4.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 07:57:14'),
(16, 5, 'prod5.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 08:03:30'),
(17, 6, 'prod6.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 08:05:48'),
(18, 7, 'prod3.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 08:42:50'),
(19, 8, '', '', '2016-09-27 09:30:11'),
(20, 9, 'prod6.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 11:26:51'),
(21, 9, 'prod7.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 11:26:52'),
(22, 10, 'mastercard.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 11:40:16'),
(23, 10, 'nets.jpg', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 11:40:16'),
(24, 10, 'nets-icon.png', 'webroot\\img\\files\\ProductsMarketingImages\\image\\', '2016-09-27 11:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `products_prices`
--

CREATE TABLE `products_prices` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `list_price` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_prices`
--

INSERT INTO `products_prices` (`id`, `product_id`, `list_price`, `created`, `modified`) VALUES
(1, 1, '100.00', '2016-09-22 07:41:52', '2016-09-22 07:41:52'),
(2, 2, '200.00', '2016-09-22 07:44:02', '2016-09-22 07:44:02'),
(3, 3, '67.00', '2016-09-22 13:34:47', '2016-09-22 13:34:47'),
(4, 4, '125.00', '2016-09-27 07:57:14', '2016-09-29 13:18:37'),
(5, 5, '64.00', '2016-09-27 08:03:30', '2016-09-29 13:17:18'),
(6, 6, '10.00', '2016-09-27 08:05:48', '2016-09-29 13:17:51'),
(7, 7, '80.00', '2016-09-27 08:42:50', '2016-09-27 08:42:50'),
(8, 8, '124.00', '2016-09-27 09:30:11', '2016-09-29 13:16:42'),
(9, 9, '250.00', '2016-09-27 11:26:51', '2016-09-27 11:26:51'),
(10, 10, '134.00', '2016-09-27 11:40:16', '2016-09-27 11:40:16');

-- --------------------------------------------------------

--
-- Table structure for table `products_relateds`
--

CREATE TABLE `products_relateds` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `related_product_1` varchar(255) NOT NULL,
  `related_product_2` varchar(255) NOT NULL,
  `related_product_3` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products_relateds`
--

INSERT INTO `products_relateds` (`id`, `product_id`, `related_product_1`, `related_product_2`, `related_product_3`, `created`, `modified`) VALUES
(1, 1, '3', '4', '5', '2016-09-22 07:41:52', '2016-09-27 11:14:02'),
(2, 2, '1', '0', '0', '2016-09-22 07:44:02', '2016-10-04 09:18:33'),
(3, 3, '1', '0', '0', '2016-09-22 13:34:47', '2016-09-27 11:14:59'),
(4, 4, '0', '0', '0', '2016-09-27 07:57:14', '2016-10-03 13:26:55'),
(5, 5, '0', '0', '0', '2016-09-27 08:03:30', '2016-10-03 13:27:13'),
(6, 6, '0', '0', '0', '2016-09-27 08:05:48', '2016-09-29 13:17:51'),
(7, 7, '0', '0', '0', '2016-09-27 08:42:50', '2016-09-27 08:42:50'),
(8, 8, '0', '0', '0', '2016-09-27 09:30:11', '2016-09-29 13:16:42'),
(9, 9, '0', '0', '0', '2016-09-27 11:26:51', '2016-09-27 11:26:51'),
(10, 10, '0', '0', '0', '2016-09-27 11:40:16', '2016-09-27 11:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `promo_code` varchar(255) NOT NULL,
  `formal_message_display` text NOT NULL,
  `product_id` int(11) NOT NULL,
  `discounted_amount_parent` varchar(255) NOT NULL,
  `quantity_parent` int(11) NOT NULL,
  `start_date` varchar(255) NOT NULL,
  `end_date` varchar(255) NOT NULL,
  `associated_product` varchar(255) NOT NULL,
  `child_product_id` varchar(255) NOT NULL,
  `discounted_amount_child` varchar(255) NOT NULL,
  `quantity_child` int(11) NOT NULL,
  `discount_parent_in` varchar(255) NOT NULL,
  `discount_child_in` varchar(255) NOT NULL,
  `discount_type` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `promo_code`, `formal_message_display`, `product_id`, `discounted_amount_parent`, `quantity_parent`, `start_date`, `end_date`, `associated_product`, `child_product_id`, `discounted_amount_child`, `quantity_child`, `discount_parent_in`, `discount_child_in`, `discount_type`, `created`, `modified`) VALUES
(1, 'CASH400', 'THIS is my promo', 1, '1', 1, '23-09-2016', '30-09-2016', '1', '1', '67', 56, '$', '$', 'P', '2016-09-23 05:47:47', '2016-09-26 05:20:14'),
(2, 'CASH200', 'This is Second Promo', 1, '20', 1, '23-09-2016', '26-09-2016', '0', '1', '67', 67, '$', '$', 'B', '2016-09-23 05:50:30', '2016-09-26 04:40:40'),
(3, 'PRO900001', 'Direct Discount on this product', 4, '10', 1, '27-09-2016', '31-10-2016', '1', '1', '0', 1, '%', '$', 'D', '2016-09-27 09:43:19', '2016-09-27 09:43:19'),
(4, 'PRO900006', 'FOC', 6, '0', 1, '27-09-2016', '31-10-2016', '1', '8', '100', 1, '%', '$', 'F', '2016-09-27 09:43:19', '2016-09-27 09:43:19'),
(5, 'PR008', 'Direct Discount', 10, '15', 1, '27-09-2016', '30-10-2016', '1', '1', '0', 0, '%', '$', 'D', '2016-09-27 11:44:14', '2016-09-27 11:44:14'),
(7, 'PRO900003333', 'Direct Discount on this product', 3, '10', 1, '27-09-2016', '31-10-2016', '1', '1', '0', 1, '%', '$', 'D', '2016-09-27 09:43:19', '2016-09-27 09:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `slider_images`
--

CREATE TABLE `slider_images` (
  `id` int(2) NOT NULL,
  `image` varchar(100) NOT NULL,
  `image_dir` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `slider_images`
--

INSERT INTO `slider_images` (`id`, `image`, `image_dir`, `created`, `modified`) VALUES
(2, 'slider-1.jpg', 'webroot\\img\\files\\SliderImages\\image\\', '2016-08-11 00:00:00', '2016-09-23 04:49:28'),
(3, 'get-me-free.jpg', 'webroot\\img\\files\\SliderImages\\image\\', '2016-08-12 11:52:03', '2016-09-23 04:49:50'),
(4, 'food-beverage.jpg', 'webroot\\img\\files\\SliderImages\\image\\', '2016-09-06 11:26:42', '2016-09-23 04:50:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `status`, `password`, `created`, `modified`) VALUES
(9, 'pradeep.danwal@sdnainfotech.com', '1', '827ccb0eea8a706c4c34a16891f84e7b', '2016-08-18 12:24:01', '2016-09-27 13:04:05'),
(30, 'deepak.ra1982@sdnainfotech.com', '1', '123456', '2016-09-29 06:36:12', '2016-09-29 06:36:12'),
(35, 'sflowaskgmail@sdnainfotech.com', '1', '123456', '2016-09-29 07:35:05', '2016-09-29 07:35:05'),
(37, 'sania.mohan@sdnainfotech.com', '1', 'e10adc3949ba59abbe56e057f20f883e', '2016-10-03 09:52:19', '2016-10-03 09:52:19'),
(47, 'deepak.rathi@sdnainfotech.com', '1', '827ccb0eea8a706c4c34a16891f84e7b', '2016-10-04 09:37:58', '2016-10-04 09:37:58'),
(48, 'dfd55f@gmail.com', '1', 'e10adc3949ba59abbe56e057f20f883e', '2016-10-05 06:48:27', '2016-10-05 06:48:27'),
(50, 'kalpna@sdnainfotech.com', '1', 'e10adc3949ba59abbe56e057f20f883e', '2016-10-05 12:51:17', '2016-10-05 12:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `receiver_name` varchar(200) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `postalcode` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL,
  `fax_no` int(11) DEFAULT NULL,
  `block_no` varchar(255) NOT NULL,
  `unit_no` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_addresses`
--

INSERT INTO `user_addresses` (`id`, `user_id`, `receiver_name`, `street_address`, `city`, `state`, `country`, `postalcode`, `telephone`, `fax_no`, `block_no`, `unit_no`, `created`, `modified`) VALUES
(1, 9, '', '12/northway', 'newyork', 'newyork', 'United States', '160101', 2147483647, 75555, 'rtgr435', '566ghg', '2016-07-19 09:45:07', '2016-09-07 10:23:13'),
(2, 9, '', '23 northway nuw', 'new-york', 'newyork chan', 'United States', '3344443', 565456, 65466546, 'fgg', '5yy', '2016-07-19 09:47:48', '2016-09-07 10:22:58'),
(3, 50, 'Himani xxx', '123 SDDD', 'kalpna@sdnainfotech.com', '', 'SINGAPORE', '122001', 2147483647, NULL, '', '', '2016-10-05 13:02:28', '2016-10-05 13:23:11'),
(5, 9, '', '#gfgf7755', 'Manimajra', 'Chandigarh', 'India', '160101', 12435688, 54656546, 'gdfgd45', '6458uu', '2016-09-08 12:16:37', '2016-09-08 12:16:37'),
(6, 9, '', '#g5gg66/5456767', 'Manimajra', 'Chandigarh', 'India', '1235677', 646784666, 444356, 'b66', '756jj', '2016-09-08 12:21:17', '2016-09-08 12:21:17'),
(12, 30, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-09-29 06:36:12', '2016-09-29 06:36:12'),
(17, 35, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-09-29 07:35:05', '2016-09-29 07:35:05'),
(19, 37, '', 'dfdxsf', '', '', 'Singapore', '34r3w4r', 454545, NULL, '', '', '2016-10-03 09:52:19', '2016-10-03 09:52:19'),
(20, 38, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-10-04 07:37:06', '2016-10-04 07:37:06'),
(23, 41, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-10-04 08:52:12', '2016-10-04 08:52:12'),
(24, 42, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-10-04 08:53:40', '2016-10-04 08:53:40'),
(25, 43, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-10-04 08:56:32', '2016-10-04 08:56:32'),
(27, 45, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-10-04 09:04:05', '2016-10-04 09:04:05'),
(28, 46, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-10-04 09:36:23', '2016-10-04 09:36:23'),
(29, 47, '', '133001', '', '', 'Singapore', 'www.gmail.com', 2341113, NULL, '', '', '2016-10-04 09:37:58', '2016-10-04 09:37:58'),
(30, 48, '', '4545', '', '', 'Singapore', '454545', 2147483647, NULL, '', '', '2016-10-05 06:48:28', '2016-10-05 06:48:28'),
(33, 50, '', '45454545', '', '', 'Singapore', '45454545', 45454545, NULL, '', '', '2016-10-05 12:51:17', '2016-10-05 12:51:17'),
(34, 50, 'dee_receiver', '123 SDDD', 'dee_shipping@gmail.com', 'haryana', 'Singapore', '122001', 2147483647, NULL, '', '', '2016-10-05 12:57:52', '2016-10-05 12:57:52');

-- --------------------------------------------------------

--
-- Table structure for table `user_billings`
--

CREATE TABLE `user_billings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `fax_no` varchar(200) DEFAULT NULL,
  `telephone_no` varchar(200) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `block_no` varchar(255) NOT NULL,
  `unit_no` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `postalcode` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL,
  `country` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_billings`
--

INSERT INTO `user_billings` (`id`, `user_id`, `sender_name`, `fax_no`, `telephone_no`, `email_address`, `block_no`, `unit_no`, `street`, `postalcode`, `telephone`, `country`, `created`, `modified`) VALUES
(1, 9, 'Pradeep', '', '', 'pradeep.ddd@gmail.com', '#123', '55', 'Town', '160110', 2147483647, 'india', '2016-09-20 00:00:00', '2016-09-26 12:28:35'),
(3, 50, 'kalpna', '', '98157716781', 'deepak.mmm@gmail.com', '', '67', 'street_123', '122001', 0, 'SINGAPORE', '2016-10-05 12:57:51', '2016-10-05 13:23:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `image_dir` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `blockno` varchar(255) NOT NULL,
  `unitno` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `position` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `firstname`, `lastname`, `image`, `image_dir`, `dob`, `gender`, `mobile`, `blockno`, `unitno`, `company`, `position`, `created`, `modified`) VALUES
(14, 9, 'pradeep', 'dangwal', 'Hydrangeas.jpg', 'webroot\\img\\files\\UserDetails\\image\\', '08-12-1986', 'Female', '12345675678', 'dfg', '34', 'dfg', 'dfg', '2016-08-18 12:24:01', '2016-09-08 12:10:20'),
(26, 30, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', 'comp123', 'deee', '2016-09-29 06:36:12', '2016-09-29 06:36:12'),
(31, 35, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', NULL, 'deee111', '2016-09-29 07:35:05', '2016-09-29 07:35:05'),
(33, 37, 'xxx', 'xxx', '', '', '', '', '', '343', '3434', '', 'drtdrtg', '2016-10-03 09:52:19', '2016-10-03 09:52:19'),
(34, 38, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', 'comp1', 'deee', '2016-10-04 07:37:07', '2016-10-04 07:37:07'),
(37, 41, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', 'comp1', 'deee', '2016-10-04 08:52:12', '2016-10-04 08:52:12'),
(38, 42, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', 'comp1', 'deee', '2016-10-04 08:53:40', '2016-10-04 08:53:40'),
(39, 43, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', 'comp1', 'deee', '2016-10-04 08:56:32', '2016-10-04 08:56:32'),
(41, 45, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', 'comp1', 'deee', '2016-10-04 09:04:05', '2016-10-04 09:04:05'),
(42, 46, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', 'comp1', 'deee', '2016-10-04 09:36:23', '2016-10-04 09:36:23'),
(43, 47, 'blk009', 'un009', '', '', '', '', '', 'street1', 'singapore', 'comp1', 'deee', '2016-10-04 09:37:58', '2016-10-04 09:37:58'),
(44, 48, 'xxx', 'Thakur', '', '', '', '', '', '454554', '34', NULL, '454545', '2016-10-05 06:48:28', '2016-10-05 06:48:28'),
(46, 50, 'Himani', 'xxx', '', '', '', '', '', '454554', '45', NULL, 'software eningeer', '2016-10-05 12:51:17', '2016-10-05 12:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created`, `modified`) VALUES
(1, 49, 7, '2016-10-05 09:47:34', '2016-10-05 09:47:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acl_phinxlog`
--
ALTER TABLE `acl_phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `acos`
--
ALTER TABLE `acos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lft` (`lft`,`rght`),
  ADD KEY `alias` (`alias`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aros`
--
ALTER TABLE `aros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lft` (`lft`,`rght`),
  ADD KEY `alias` (`alias`);

--
-- Indexes for table `aros_acos`
--
ALTER TABLE `aros_acos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aro_id` (`aro_id`,`aco_id`),
  ADD KEY `aco_id` (`aco_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_products`
--
ALTER TABLE `cart_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_details`
--
ALTER TABLE `category_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configrations`
--
ALTER TABLE `configrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_billings`
--
ALTER TABLE `orders_billings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_products`
--
ALTER TABLE `orders_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_shippings`
--
ALTER TABLE `orders_shippings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_product_promotions`
--
ALTER TABLE `order_product_promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_update_statuses`
--
ALTER TABLE `order_update_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_attrs`
--
ALTER TABLE `products_attrs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_categories`
--
ALTER TABLE `products_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_images`
--
ALTER TABLE `products_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_marketing_images`
--
ALTER TABLE `products_marketing_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_prices`
--
ALTER TABLE `products_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_relateds`
--
ALTER TABLE `products_relateds`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promo_code` (`promo_code`);

--
-- Indexes for table `slider_images`
--
ALTER TABLE `slider_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_billings`
--
ALTER TABLE `user_billings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acos`
--
ALTER TABLE `acos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;
--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `aros`
--
ALTER TABLE `aros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `aros_acos`
--
ALTER TABLE `aros_acos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `cart_products`
--
ALTER TABLE `cart_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `category_details`
--
ALTER TABLE `category_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `configrations`
--
ALTER TABLE `configrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `orders_billings`
--
ALTER TABLE `orders_billings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders_products`
--
ALTER TABLE `orders_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `orders_shippings`
--
ALTER TABLE `orders_shippings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_product_promotions`
--
ALTER TABLE `order_product_promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `order_update_statuses`
--
ALTER TABLE `order_update_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `products_attrs`
--
ALTER TABLE `products_attrs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `products_categories`
--
ALTER TABLE `products_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `products_images`
--
ALTER TABLE `products_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `products_marketing_images`
--
ALTER TABLE `products_marketing_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `products_prices`
--
ALTER TABLE `products_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `products_relateds`
--
ALTER TABLE `products_relateds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `slider_images`
--
ALTER TABLE `slider_images`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `user_billings`
--
ALTER TABLE `user_billings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
