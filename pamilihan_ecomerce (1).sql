-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2025 at 05:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pamilihan_ecomerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `receiver_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_email`, `receiver_email`, `message`, `created_at`) VALUES
(26, 'example@gmail.com', 'rider@gmail.com', 'dasdsa', '2025-01-28 00:34:52'),
(27, 'example@gmail.com', 'rider@gmail.com', 'dasdas', '2025-01-28 00:45:39'),
(28, 'example@gmail.com', 'rider@gmail.com', 'das', '2025-01-28 00:56:38'),
(29, 'example@gmail.com', 'rider@gmail.com', 'dasdsa', '2025-01-28 00:57:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_billing_address`
--

CREATE TABLE `tbl_billing_address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `country` varchar(20) NOT NULL,
  `address` varchar(150) NOT NULL,
  `city` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_billing_address`
--

INSERT INTO `tbl_billing_address` (`id`, `user_id`, `full_name`, `phone`, `country`, `address`, `city`) VALUES
(1, 4, 'Example Cust', '09345789782', 'Philippines', 'asdasda', 'Taguig'),
(2, 5, 'Example Customer', '0987654321', 'Philippines', 'asdasdas', 'Taguig'),
(7, 11, '', '', 'Philippines', '', ''),
(8, 12, '', '', 'Philippines', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_color`
--

CREATE TABLE `tbl_color` (
  `color_id` int(11) NOT NULL,
  `color_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_color`
--

INSERT INTO `tbl_color` (`color_id`, `color_name`) VALUES
(1, 'Red'),
(2, 'Black'),
(3, 'Blue'),
(4, 'Yellow'),
(5, 'Green'),
(6, 'White'),
(7, 'Orange'),
(8, 'Brown'),
(9, 'Tan'),
(10, 'Pink'),
(11, 'Mixed'),
(12, 'Lightblue'),
(13, 'Violet'),
(14, 'Light Purple'),
(15, 'Salmon'),
(16, 'Gold'),
(17, 'Gray'),
(18, 'Ash'),
(19, 'Maroon'),
(20, 'Silver'),
(21, 'Dark Clay'),
(22, 'Cognac'),
(23, 'Coffee'),
(24, 'Charcoal'),
(25, 'Navy'),
(26, 'Fuchsia'),
(27, 'Olive'),
(28, 'Burgundy'),
(29, 'Midnight Blue');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_country`
--

CREATE TABLE `tbl_country` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(100) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `tbl_country`
--

INSERT INTO `tbl_country` (`country_id`, `country_name`) VALUES
(1, 'Afghanistan'),
(2, 'Albania'),
(3, 'Algeria'),
(4, 'American Samoa'),
(5, 'Andorra'),
(6, 'Angola'),
(7, 'Anguilla'),
(8, 'Antarctica'),
(9, 'Antigua and Barbuda'),
(10, 'Argentina'),
(11, 'Armenia'),
(12, 'Aruba'),
(13, 'Australia'),
(14, 'Austria'),
(15, 'Azerbaijan'),
(16, 'Bahamas'),
(17, 'Bahrain'),
(18, 'Bangladesh'),
(19, 'Barbados'),
(20, 'Belarus'),
(21, 'Belgium'),
(22, 'Belize'),
(23, 'Benin'),
(24, 'Bermuda'),
(25, 'Bhutan'),
(26, 'Bolivia'),
(27, 'Bosnia and Herzegovina'),
(28, 'Botswana'),
(29, 'Bouvet Island'),
(30, 'Brazil'),
(31, 'British Indian Ocean Territory'),
(32, 'Brunei Darussalam'),
(33, 'Bulgaria'),
(34, 'Burkina Faso'),
(35, 'Burundi'),
(36, 'Cambodia'),
(37, 'Cameroon'),
(38, 'Canada'),
(39, 'Cape Verde'),
(40, 'Cayman Islands'),
(41, 'Central African Republic'),
(42, 'Chad'),
(43, 'Chile'),
(44, 'China'),
(45, 'Christmas Island'),
(46, 'Cocos (Keeling) Islands'),
(47, 'Colombia'),
(48, 'Comoros'),
(49, 'Congo'),
(50, 'Cook Islands'),
(51, 'Costa Rica'),
(52, 'Croatia (Hrvatska)'),
(53, 'Cuba'),
(54, 'Cyprus'),
(55, 'Czech Republic'),
(56, 'Denmark'),
(57, 'Djibouti'),
(58, 'Dominica'),
(59, 'Dominican Republic'),
(60, 'East Timor'),
(61, 'Ecuador'),
(62, 'Egypt'),
(63, 'El Salvador'),
(64, 'Equatorial Guinea'),
(65, 'Eritrea'),
(66, 'Estonia'),
(67, 'Ethiopia'),
(68, 'Falkland Islands (Malvinas)'),
(69, 'Faroe Islands'),
(70, 'Fiji'),
(71, 'Finland'),
(72, 'France'),
(73, 'France, Metropolitan'),
(74, 'French Guiana'),
(75, 'French Polynesia'),
(76, 'French Southern Territories'),
(77, 'Gabon'),
(78, 'Gambia'),
(79, 'Georgia'),
(80, 'Germany'),
(81, 'Ghana'),
(82, 'Gibraltar'),
(83, 'Guernsey'),
(84, 'Greece'),
(85, 'Greenland'),
(86, 'Grenada'),
(87, 'Guadeloupe'),
(88, 'Guam'),
(89, 'Guatemala'),
(90, 'Guinea'),
(91, 'Guinea-Bissau'),
(92, 'Guyana'),
(93, 'Haiti'),
(94, 'Heard and Mc Donald Islands'),
(95, 'Honduras'),
(96, 'Hong Kong'),
(97, 'Hungary'),
(98, 'Iceland'),
(99, 'India'),
(100, 'Isle of Man'),
(101, 'Indonesia'),
(102, 'Iran (Islamic Republic of)'),
(103, 'Iraq'),
(104, 'Ireland'),
(105, 'Israel'),
(106, 'Italy'),
(107, 'Ivory Coast'),
(108, 'Jersey'),
(109, 'Jamaica'),
(110, 'Japan'),
(111, 'Jordan'),
(112, 'Kazakhstan'),
(113, 'Kenya'),
(114, 'Kiribati'),
(115, 'Korea, Democratic People\'s Republic of'),
(116, 'Korea, Republic of'),
(117, 'Kosovo'),
(118, 'Kuwait'),
(119, 'Kyrgyzstan'),
(120, 'Lao People\'s Democratic Republic'),
(121, 'Latvia'),
(122, 'Lebanon'),
(123, 'Lesotho'),
(124, 'Liberia'),
(125, 'Libyan Arab Jamahiriya'),
(126, 'Liechtenstein'),
(127, 'Lithuania'),
(128, 'Luxembourg'),
(129, 'Macau'),
(130, 'Macedonia'),
(131, 'Madagascar'),
(132, 'Malawi'),
(133, 'Malaysia'),
(134, 'Maldives'),
(135, 'Mali'),
(136, 'Malta'),
(137, 'Marshall Islands'),
(138, 'Martinique'),
(139, 'Mauritania'),
(140, 'Mauritius'),
(141, 'Mayotte'),
(142, 'Mexico'),
(143, 'Micronesia, Federated States of'),
(144, 'Moldova, Republic of'),
(145, 'Monaco'),
(146, 'Mongolia'),
(147, 'Montenegro'),
(148, 'Montserrat'),
(149, 'Morocco'),
(150, 'Mozambique'),
(151, 'Myanmar'),
(152, 'Namibia'),
(153, 'Nauru'),
(154, 'Nepal'),
(155, 'Netherlands'),
(156, 'Netherlands Antilles'),
(157, 'New Caledonia'),
(158, 'New Zealand'),
(159, 'Nicaragua'),
(160, 'Niger'),
(161, 'Nigeria'),
(162, 'Niue'),
(163, 'Norfolk Island'),
(164, 'Northern Mariana Islands'),
(165, 'Norway'),
(166, 'Oman'),
(167, 'Pakistan'),
(168, 'Palau'),
(169, 'Palestine'),
(170, 'Panama'),
(171, 'Papua New Guinea'),
(172, 'Paraguay'),
(173, 'Peru'),
(174, 'Philippines'),
(175, 'Pitcairn'),
(176, 'Poland'),
(177, 'Portugal'),
(178, 'Puerto Rico'),
(179, 'Qatar'),
(180, 'Reunion'),
(181, 'Romania'),
(182, 'Russian Federation'),
(183, 'Rwanda'),
(184, 'Saint Kitts and Nevis'),
(185, 'Saint Lucia'),
(186, 'Saint Vincent and the Grenadines'),
(187, 'Samoa'),
(188, 'San Marino'),
(189, 'Sao Tome and Principe'),
(190, 'Saudi Arabia'),
(191, 'Senegal'),
(192, 'Serbia'),
(193, 'Seychelles'),
(194, 'Sierra Leone'),
(195, 'Singapore'),
(196, 'Slovakia'),
(197, 'Slovenia'),
(198, 'Solomon Islands'),
(199, 'Somalia'),
(200, 'South Africa'),
(201, 'South Georgia South Sandwich Islands'),
(202, 'Spain'),
(203, 'Sri Lanka'),
(204, 'St. Helena'),
(205, 'St. Pierre and Miquelon'),
(206, 'Sudan'),
(207, 'Suriname'),
(208, 'Svalbard and Jan Mayen Islands'),
(209, 'Swaziland'),
(210, 'Sweden'),
(211, 'Switzerland'),
(212, 'Syrian Arab Republic'),
(213, 'Taiwan'),
(214, 'Tajikistan'),
(215, 'Tanzania, United Republic of'),
(216, 'Thailand'),
(217, 'Togo'),
(218, 'Tokelau'),
(219, 'Tonga'),
(220, 'Trinidad and Tobago'),
(221, 'Tunisia'),
(222, 'Turkey'),
(223, 'Turkmenistan'),
(224, 'Turks and Caicos Islands'),
(225, 'Tuvalu'),
(226, 'Uganda'),
(227, 'Ukraine'),
(228, 'United Arab Emirates'),
(229, 'United Kingdom'),
(230, 'United States'),
(231, 'United States minor outlying islands'),
(232, 'Uruguay'),
(233, 'Uzbekistan'),
(234, 'Vanuatu'),
(235, 'Vatican City State'),
(236, 'Venezuela'),
(237, 'Vietnam'),
(238, 'Virgin Islands (British)'),
(239, 'Virgin Islands (U.S.)'),
(240, 'Wallis and Futuna Islands'),
(241, 'Western Sahara'),
(242, 'Yemen'),
(243, 'Zaire'),
(244, 'Zambia'),
(245, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `cust_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cust_name` varchar(100) NOT NULL,
  `cust_cname` varchar(100) NOT NULL,
  `cust_email` varchar(100) NOT NULL,
  `cust_phone` varchar(50) NOT NULL,
  `cust_country` varchar(50) NOT NULL,
  `cust_address` text NOT NULL,
  `cust_city` varchar(100) NOT NULL,
  `cust_state` varchar(100) NOT NULL,
  `cust_zip` varchar(30) NOT NULL,
  `cust_b_name` varchar(100) DEFAULT NULL,
  `cust_b_cname` varchar(100) DEFAULT NULL,
  `cust_b_phone` varchar(50) DEFAULT NULL,
  `cust_b_country` varchar(50) DEFAULT NULL,
  `cust_b_address` text DEFAULT NULL,
  `cust_b_city` varchar(100) DEFAULT NULL,
  `cust_b_state` varchar(100) DEFAULT NULL,
  `cust_b_zip` varchar(30) DEFAULT NULL,
  `cust_s_name` varchar(100) DEFAULT NULL,
  `cust_s_cname` varchar(100) DEFAULT NULL,
  `cust_s_phone` varchar(50) DEFAULT NULL,
  `cust_s_country` varchar(50) DEFAULT NULL,
  `cust_s_address` text DEFAULT NULL,
  `cust_s_city` varchar(100) DEFAULT NULL,
  `cust_s_state` varchar(100) DEFAULT NULL,
  `cust_s_zip` varchar(30) DEFAULT NULL,
  `cust_password` varchar(100) NOT NULL,
  `cust_token` varchar(255) NOT NULL,
  `cust_datetime` varchar(100) NOT NULL,
  `cust_timestamp` varchar(100) NOT NULL,
  `cust_status` int(11) NOT NULL,
  `map_long` longtext DEFAULT NULL,
  `map_lat` longtext DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`cust_id`, `user_id`, `cust_name`, `cust_cname`, `cust_email`, `cust_phone`, `cust_country`, `cust_address`, `cust_city`, `cust_state`, `cust_zip`, `cust_b_name`, `cust_b_cname`, `cust_b_phone`, `cust_b_country`, `cust_b_address`, `cust_b_city`, `cust_b_state`, `cust_b_zip`, `cust_s_name`, `cust_s_cname`, `cust_s_phone`, `cust_s_country`, `cust_s_address`, `cust_s_city`, `cust_s_state`, `cust_s_zip`, `cust_password`, `cust_token`, `cust_datetime`, `cust_timestamp`, `cust_status`, `map_long`, `map_lat`, `profile_picture`) VALUES
(2, 5, 'Example Customer', 'none', 'example@gmail.com', '09789434331', 'Philippines', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '9037849827342lkjsdf898342o20as', '', '', 1, NULL, NULL, NULL),
(7, 11, 'Ablanida Ivan ', 'none', 'ejthecoder@gmail.com', '09957939703', '', '', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', 'df84add7094ddbdaf29803f4eb4e1db8', '', '1737915424', 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer_message`
--

CREATE TABLE `tbl_customer_message` (
  `customer_message_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `order_detail` text NOT NULL,
  `cust_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_customer_message`
--

INSERT INTO `tbl_customer_message` (`customer_message_id`, `subject`, `message`, `order_detail`, `cust_id`) VALUES
(9, 'HELLO ', 'order has been delivered', '\nCustomer Name: angeli khang<br>\nCustomer Email: angelikhang@mail.com<br>\nPayment Method: PayPal<br>\nPayment Date: 2024-12-04 23:20:03<br>\nPayment Details: <br>\nTransaction Id: <br>\n        		<br>\nPaid Amount: 13<br>\nPayment Status: Paid<br>\nShipping Status: Shipped<br>\nPayment Id: 1733325603<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Grapes<br>\nSize: <br>\nColor: <br>\nQuantity: 5<br>\nUnit Price: 2<br>\n            \n<br><b><u>Product Item 2</u></b><br>\nProduct Name: Mango<br>\nSize: S<br>\nColor: Yellow<br>\nQuantity: 1<br>\nUnit Price: 2<br>\n            ', 1),
(10, 'HELLO ', 'sasas', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2024-12-05 18:46:23<br>\nPayment Details: <br><br>\nPaid Amount: 3<br>\nPayment Status: Paid<br>\nShipping Status: Completed<br>\nPayment Id: 1733395583<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Mango<br>\nSize: S<br>\nColor: Yellow<br>\nQuantity: 1<br>\nUnit Price: 2<br>\n            ', 2),
(11, 'HELLO ', 'sasas', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2024-12-05 18:46:23<br>\nPayment Details: <br><br>\nPaid Amount: 3<br>\nPayment Status: Paid<br>\nShipping Status: Completed<br>\nPayment Id: 1733395583<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Mango<br>\nSize: S<br>\nColor: Yellow<br>\nQuantity: 1<br>\nUnit Price: 2<br>\n            ', 2),
(12, 'HELLO ', 'sasas', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2024-12-05 18:46:23<br>\nPayment Details: <br><br>\nPaid Amount: 3<br>\nPayment Status: Paid<br>\nShipping Status: Completed<br>\nPayment Id: 1733395583<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Mango<br>\nSize: S<br>\nColor: Yellow<br>\nQuantity: 1<br>\nUnit Price: 2<br>\n            ', 2),
(13, 'HELLO ', 'ggg', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2024-12-05 18:46:23<br>\nPayment Details: <br><br>\nPaid Amount: 3<br>\nPayment Status: Paid<br>\nShipping Status: Completed<br>\nPayment Id: 1733395583<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Mango<br>\nSize: S<br>\nColor: Yellow<br>\nQuantity: 1<br>\nUnit Price: 2<br>\n            ', 2),
(14, 'HELLO ', 'ggg', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2024-12-05 18:46:23<br>\nPayment Details: <br>\nTransaction Details: <br><br>\nPaid Amount: 3<br>\nPayment Status: Paid<br>\nShipping Status: Completed<br>\nPayment Id: 1733395583<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Mango<br>\nSize: S<br>\nColor: Yellow<br>\nQuantity: 1<br>\nUnit Price: 2<br>\n            ', 2),
(15, 'HELLO ', 'ggg', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2024-12-05 18:46:23<br>\nPayment Details: <br>\nTransaction Details: <br><br>\nPaid Amount: 3<br>\nPayment Status: Paid<br>\nShipping Status: Completed<br>\nPayment Id: 1733395583<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Mango<br>\nSize: S<br>\nColor: Yellow<br>\nQuantity: 1<br>\nUnit Price: 2<br>\n            ', 2),
(16, 'HELLO ', 'sasasa', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2024-12-05 18:46:23<br>\nPayment Details: <br>\nTransaction Details: <br><br>\nPaid Amount: 3<br>\nPayment Status: Paid<br>\nShipping Status: Completed<br>\nPayment Id: 1733395583<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Mango<br>\nSize: S<br>\nColor: Yellow<br>\nQuantity: 1<br>\nUnit Price: 2<br>\n            ', 2),
(17, 'asd', 'dsaasd', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2025-01-11 21:11:32<br>\nPayment Details: <br>\nTransaction Details: <br><br>\nPaid Amount: 301<br>\nPayment Status: Completed<br>\nShipping Status: Completed<br>\nPayment Id: 1736601092<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Guava<br>\nSize: <br>\nColor: <br>\nQuantity: 1<br>\nUnit Price: 150<br>\n            \n<br><b><u>Product Item 2</u></b><br>\nProduct Name: Pork Chop<br>\nSize: <br>\nColor: <br>\nQuantity: 1<br>\nUnit Price: 150<br>\n            ', 2),
(18, 'ffffffff', 'fffffff', '\nCustomer Name: Sweet Muffis<br>\nCustomer Email: sweet@gmail.com<br>\nPayment Method: COD<br>\nPayment Date: 2025-01-11 21:11:32<br>\nPayment Details: <br>\nTransaction Details: <br><br>\nPaid Amount: 301<br>\nPayment Status: Completed<br>\nShipping Status: Completed<br>\nPayment Id: 1736601092<br>\n            \n<br><b><u>Product Item 1</u></b><br>\nProduct Name: Guava<br>\nSize: <br>\nColor: <br>\nQuantity: 1<br>\nUnit Price: 150<br>\n            \n<br><b><u>Product Item 2</u></b><br>\nProduct Name: Pork Chop<br>\nSize: <br>\nColor: <br>\nQuantity: 1<br>\nUnit Price: 150<br>\n            ', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_delivery_fee`
--

CREATE TABLE `tbl_delivery_fee` (
  `id` int(11) NOT NULL,
  `cost` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_end_category`
--

CREATE TABLE `tbl_end_category` (
  `ecat_id` int(11) NOT NULL,
  `ecat_name` varchar(255) NOT NULL,
  `mcat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_end_category`
--

INSERT INTO `tbl_end_category` (`ecat_id`, `ecat_name`, `mcat_id`) VALUES
(4, 'Fresh Meat & Seafood', 3),
(5, 'Fresh Produce', 3),
(6, 'Frozen Goods\n', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_faq`
--

CREATE TABLE `tbl_faq` (
  `faq_id` int(11) NOT NULL,
  `faq_title` varchar(255) NOT NULL,
  `faq_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_faq`
--

INSERT INTO `tbl_faq` (`faq_id`, `faq_title`, `faq_content`) VALUES
(1, 'How to find an item?', '<h3 class=\"checkout-complete-box font-bold txt16\" style=\"box-sizing: inherit; text-rendering: optimizeLegibility; margin: 0.2rem 0px 0.5rem; padding: 0px; line-height: 1.4; background-color: rgb(250, 250, 250);\"><font color=\"#222222\" face=\"opensans, Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif\"><span style=\"font-size: 15.7143px;\">We have a wide range of fabulous products to choose from.</span></font></h3><h3 class=\"checkout-complete-box font-bold txt16\" style=\"box-sizing: inherit; text-rendering: optimizeLegibility; margin: 0.2rem 0px 0.5rem; padding: 0px; line-height: 1.4; background-color: rgb(250, 250, 250);\"><span style=\"font-size: 15.7143px; color: rgb(34, 34, 34); font-family: opensans, \"Helvetica Neue\", Helvetica, Helvetica, Arial, sans-serif;\">Tip 1: If you\'re looking for a specific product, use the keyword search box located at the top of the site. Simply type what you are looking for, and prepare to be amazed!</span></h3><h3 class=\"checkout-complete-box font-bold txt16\" style=\"box-sizing: inherit; text-rendering: optimizeLegibility; margin: 0.2rem 0px 0.5rem; padding: 0px; line-height: 1.4; background-color: rgb(250, 250, 250);\"><font color=\"#222222\" face=\"opensans, Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif\"><span style=\"font-size: 15.7143px;\">Tip 2: If you want to explore a category of products, use the Shop Categories in the upper menu, and navigate through your favorite categories where we\'ll feature the best products in each.</span></font><br><br></h3>\r\n'),
(2, 'What is your return policy?', '<p><span style=\"color: rgb(10, 10, 10); font-family: opensans, &quot;Helvetica Neue&quot;, Helvetica, Helvetica, Arial, sans-serif; font-size: 14px; text-align: center;\">You have 15 days to make a refund request after your order has been delivered.</span><br></p>\r\n'),
(3, ' I received a defective/damaged item, can I get a refund?', '<p>In case the item you received is damaged or defective, you could return an item in the same condition as you received it with the original box and/or packaging intact. Once we receive the returned item, we will inspect it and if the item is found to be defective or damaged, we will process the refund along with any shipping fees incurred.<br></p>\r\n'),
(4, 'When are ‘Returns’ not possible?', '<p class=\"a  \" style=\"box-sizing: inherit; text-rendering: optimizeLegibility; line-height: 1.6; margin-bottom: 0.714286rem; padding: 0px; font-size: 14px; color: rgb(10, 10, 10); font-family: opensans, &quot;Helvetica Neue&quot;, Helvetica, Helvetica, Arial, sans-serif; background-color: rgb(250, 250, 250);\">There are a few certain scenarios where it is difficult for us to support returns:</p><ol style=\"box-sizing: inherit; line-height: 1.6; margin-right: 0px; margin-bottom: 0px; margin-left: 1.25rem; padding: 0px; list-style-position: outside; color: rgb(10, 10, 10); font-family: opensans, &quot;Helvetica Neue&quot;, Helvetica, Helvetica, Arial, sans-serif; font-size: 14px; background-color: rgb(250, 250, 250);\"><li style=\"box-sizing: inherit; margin: 0px; padding: 0px; font-size: inherit;\">Return request is made outside the specified time frame, of 15 days from delivery.</li><li style=\"box-sizing: inherit; margin: 0px; padding: 0px; font-size: inherit;\">Product is used, damaged, or is not in the same condition as you received it.</li><li style=\"box-sizing: inherit; margin: 0px; padding: 0px; font-size: inherit;\">Specific categories like innerwear, lingerie, socks and clothing freebies etc.</li><li style=\"box-sizing: inherit; margin: 0px; padding: 0px; font-size: inherit;\">Defective products which are covered under the manufacturer\'s warranty.</li><li style=\"box-sizing: inherit; margin: 0px; padding: 0px; font-size: inherit;\">Any consumable item which has been used or installed.</li><li style=\"box-sizing: inherit; margin: 0px; padding: 0px; font-size: inherit;\">Products with tampered or missing serial numbers.</li><li style=\"box-sizing: inherit; margin: 0px; padding: 0px; font-size: inherit;\">Anything missing from the package you\'ve received including price tags, labels, original packing, freebies and accessories.</li><li style=\"box-sizing: inherit; margin: 0px; padding: 0px; font-size: inherit;\">Fragile items, hygiene related items.</li></ol>\r\n'),
(5, 'What are the items that cannot be returned?', '<p>The items that can not be returned are:</p><p>Clearance items clearly marked as such and displaying a No-Return Policy<br></p><p>When the offer notes states so specifically are items that cannot be returned.</p><p>Items that fall into the below product types-</p><ul><li>Underwear</li><li>Lingerie</li><li>Socks</li><li>Software</li><li>Music albums</li><li>Books</li><li>Swimwear</li><li>Beauty &amp; Fragrances</li><li>Hosiery</li></ul><p>Also, any consumable items that are used or installed cannot be returned. As outlined in consumer Protection Rights and concerning section on non-returnable items<br></p>');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_inventory`
--

CREATE TABLE `tbl_inventory` (
  `id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `b_id` varchar(20) NOT NULL,
  `stock_in` int(11) NOT NULL,
  `stock_out` int(11) NOT NULL,
  `exp_date` varchar(150) NOT NULL,
  `stock_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_inventory`
--

INSERT INTO `tbl_inventory` (`id`, `p_id`, `b_id`, `stock_in`, `stock_out`, `exp_date`, `stock_status`) VALUES
(28, 14, 'B1001', 100, 0, '1736483247', 'Available'),
(29, 14, 'B1001', 50, 0, '1736483348', 'Available'),
(30, 14, 'B1002', 100, 0, '1736483558', 'Available'),
(31, 28, 'B1001', 50, 0, '', 'Available'),
(32, 30, 'B1001', 50, 0, '', 'Available'),
(34, 28, 'B1002', 50, 0, '', 'Available'),
(35, 31, 'B1002', 50, 0, '', 'Available'),
(36, 29, 'B1002', 50, 0, '', 'Available'),
(37, 1, 'B1001', 100, 0, '', 'Available'),
(38, 2, 'B1001', 100, 0, '', 'Available'),
(39, 4, 'B1001', 100, 0, '', 'Available'),
(40, 3, 'B1001', 100, 0, '', 'Available'),
(41, 13, 'B1001', 100, 0, '', 'Available'),
(42, 12, 'B1001', 100, 0, '', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_language`
--

CREATE TABLE `tbl_language` (
  `lang_id` int(11) NOT NULL,
  `lang_name` varchar(255) NOT NULL,
  `lang_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_language`
--

INSERT INTO `tbl_language` (`lang_id`, `lang_name`, `lang_value`) VALUES
(1, 'Currency', '$'),
(2, 'Search product, seller', 'Search product, seller'),
(3, 'Search', 'Search'),
(4, 'Submit', 'Submit'),
(5, 'Update', 'Update'),
(6, 'Read More', 'Read More'),
(7, 'Serial', 'Serial'),
(8, 'Photo', 'Photo'),
(9, 'Login', 'Login'),
(10, 'Customer Login', 'Customer Login'),
(11, 'Click here to login', 'Click here to login'),
(12, 'Back to Login Page', 'Back to Login Page'),
(13, 'Logged in as', 'Logged in as'),
(14, 'Logout', 'Logout'),
(15, 'Register', 'Register'),
(16, 'Customer Registration', 'Customer Registration'),
(17, 'Registration Successful', 'Registration Successful'),
(18, 'Cart', 'Cart'),
(19, 'View Cart', 'View Cart'),
(20, 'Update Cart', 'Update Cart'),
(21, 'Back to Cart', 'Back to Cart'),
(22, 'Checkout', 'Checkout'),
(23, 'Proceed to Checkout', 'Proceed to Checkout'),
(24, 'Orders', 'Orders'),
(25, 'Order History', 'Order History'),
(26, 'Order Details', 'Order Details'),
(27, 'Payment Date and Time', 'Payment Date and Time'),
(28, 'Transaction ID', 'Transaction ID'),
(29, 'Paid Amount', 'Paid Amount'),
(30, 'Payment Status', 'Payment Status'),
(31, 'Payment Method', 'Payment Method'),
(32, 'Payment ID', 'Payment ID'),
(33, 'Payment Section', 'Payment Section'),
(34, 'Select Payment Method', 'Select Payment Method'),
(35, 'Select a Method', 'Select a Method'),
(36, 'PayPal', 'PayPal'),
(37, 'Stripe', 'Stripe'),
(38, 'Bank Deposit', 'Bank Deposit'),
(39, 'Card Number', 'Card Number'),
(40, 'CVV', 'CVV'),
(41, 'Month', 'Month'),
(42, 'Year', 'Year'),
(43, 'Send to this Details', 'Send to this Details'),
(44, 'Transaction Information', 'Transaction Information'),
(45, 'Include transaction id and other information correctly', 'Include transaction id and other information correctly'),
(46, 'Pay Now', 'Pay Now'),
(47, 'Product Name', 'Product Name'),
(48, 'Product Details', 'Product Details'),
(49, 'Categories', 'Categories'),
(50, 'Category:', 'Category:'),
(51, 'All Products Under', 'All Products Under'),
(52, 'Select Size', 'Select Size'),
(53, 'Select Color', 'Select Color'),
(54, 'Product Price', 'Product Price'),
(55, 'Quantity', 'Quantity'),
(56, 'Out of Stock', 'Out of Stock'),
(57, 'Share This', 'Share This'),
(58, 'Share This Product', 'Share This Product'),
(59, 'Product Description', 'Product Description'),
(60, 'Features', 'Features'),
(61, 'Conditions', 'Conditions'),
(62, 'Return Policy', 'Return Policy'),
(63, 'Reviews', 'Reviews'),
(64, 'Review', 'Review'),
(65, 'Give a Review', 'Give a Review'),
(66, 'Write your comment (Optional)', 'Write your comment (Optional)'),
(67, 'Submit Review', 'Submit Review'),
(68, 'You already have given a rating!', 'You already have given a rating!'),
(69, 'You must have to login to give a review', 'You must have to login to give a review'),
(70, 'No description found', 'No description found'),
(71, 'No feature found', 'No feature found'),
(72, 'No condition found', 'No condition found'),
(73, 'No return policy found', 'No return policy found'),
(74, 'Review not found', 'Review not found'),
(75, 'Customer Name', 'Customer Name'),
(76, 'Comment', 'Comment'),
(77, 'Comments', 'Comments'),
(78, 'Rating', 'Rating'),
(79, 'Previous', 'Previous'),
(80, 'Next', 'Next'),
(81, 'Sub Total', 'Sub Total'),
(82, 'Total', 'Total'),
(83, 'Action', 'Action'),
(84, 'Shipping Cost', 'Shipping Cost'),
(85, 'Continue Shopping', 'Continue Shopping'),
(86, 'Update Billing Address', 'Update Billing Address'),
(87, 'Update Shipping Address', 'Update Shipping Address'),
(88, 'Update Billing and Shipping Info', 'Update Billing and Shipping Info'),
(89, 'Dashboard', 'Dashboard'),
(90, 'Welcome to the Dashboard', 'Welcome to the Dashboard'),
(91, 'Back to Dashboard', 'Back to Dashboard'),
(92, 'Subscribe', 'Subscribe'),
(93, 'Subscribe To Our Newsletter', 'Subscribe To Our Newsletter'),
(94, 'Email Address', 'Email Address'),
(95, 'Enter Your Email Address', 'Enter Your Email Address'),
(96, 'Password', 'Password'),
(97, 'Forget Password', 'Forget Password'),
(98, 'Retype Password', 'Retype Password'),
(99, 'Update Password', 'Update Password'),
(100, 'New Password', 'New Password'),
(101, 'Retype New Password', 'Retype New Password'),
(102, 'Full Name', 'Full Name'),
(103, 'Company Name', 'Company Name'),
(104, 'Phone Number', 'Phone Number'),
(105, 'Address', 'Address'),
(106, 'Country', 'Country'),
(107, 'City', 'City'),
(108, 'State', 'State'),
(109, 'Zip Code', 'Zip Code'),
(110, 'About Us', 'About Us'),
(111, 'Featured Posts', 'Featured Posts'),
(112, 'Popular Posts', 'Popular Posts'),
(113, 'Recent Posts', 'Recent Posts'),
(114, 'Contact Information', 'Contact Information'),
(115, 'Contact Form', 'Contact Form'),
(116, 'Our Office', 'Our Office'),
(117, 'Update Profile', 'Update Profile'),
(118, 'Send Message', 'Send Message'),
(119, 'Message', 'Message'),
(120, 'Find Us On Map', 'Find Us On Map'),
(121, 'Congratulation! Payment is successful.', 'Congratulation! Payment is successful.'),
(122, 'Billing and Shipping Information is updated successfully.', 'Billing and Shipping Information is updated successfully.'),
(123, 'Customer Name can not be empty.', 'Customer Name can not be empty.'),
(124, 'Phone Number can not be empty.', 'Phone Number can not be empty.'),
(125, 'Address can not be empty.', 'Address can not be empty.'),
(126, 'You must have to select a country.', 'You must have to select a country.'),
(127, 'City can not be empty.', 'City can not be empty.'),
(128, 'State can not be empty.', 'State can not be empty.'),
(129, 'Zip Code can not be empty.', 'Zip Code can not be empty.'),
(130, 'Profile Information is updated successfully.', 'Profile Information is updated successfully.'),
(131, 'Email Address can not be empty', 'Email Address can not be empty'),
(132, 'Email and/or Password can not be empty.', 'Email and/or Password can not be empty.'),
(133, 'Email Address does not match.', 'Email Address does not match.'),
(134, 'Email address must be valid.', 'Email address must be valid.'),
(135, 'You email address is not found in our system.', 'You email address is not found in our system.'),
(136, 'Please check your email and confirm your subscription.', 'Please check your email and confirm your subscription.'),
(137, 'Your email is verified successfully. You can now login to our website.', 'Your email is verified successfully. You can now login to our website.'),
(138, 'Password can not be empty.', 'Password can not be empty.'),
(139, 'Passwords do not match.', 'Passwords do not match.'),
(140, 'Please enter new and retype passwords.', 'Please enter new and retype passwords.'),
(141, 'Password is updated successfully.', 'Password is updated successfully.'),
(142, 'To reset your password, please click on the link below.', 'To reset your password, please click on the link below.'),
(143, 'PASSWORD RESET REQUEST - YOUR WEBSITE.COM', 'PASSWORD RESET REQUEST - YOUR WEBSITE.COM'),
(144, 'The password reset email time (24 hours) has expired. Please again try to reset your password.', 'The password reset email time (24 hours) has expired. Please again try to reset your password.'),
(145, 'A confirmation link is sent to your email address. You will get the password reset information in there.', 'A confirmation link is sent to your email address. You will get the password reset information in there.'),
(146, 'Password is reset successfully. You can now login.', 'Password is reset successfully. You can now login.'),
(147, 'Email Address Already Exists', 'Email Address Already Exists.'),
(148, 'Sorry! Your account is inactive. Please contact to the administrator.', 'Sorry! Your account is inactive. Please contact to the administrator.'),
(149, 'Change Password', 'Change Password'),
(150, 'Registration Email Confirmation for YOUR WEBSITE', 'Registration Email Confirmation for YOUR WEBSITE.'),
(151, 'Thank you for your registration! Your account has been created. To active your account click on the link below:', 'Thank you for your registration! Your account has been created. To active your account click on the link below:'),
(152, 'Your registration is completed. Please check your email address to follow the process to confirm your registration.', 'Your registration is completed. Please check your email address to follow the process to confirm your registration.'),
(153, 'No Product Found', 'No Product Found'),
(154, 'Add to Cart', 'Add to Cart'),
(155, 'Related Products', 'Related Products'),
(156, 'See all related products from below', 'See all the related products from below'),
(157, 'Size', 'Size'),
(158, 'Color', 'Color'),
(159, 'Price', 'Price'),
(160, 'Please login as customer to checkout', 'Please login as customer to checkout'),
(161, 'Billing Address', 'Billing Address'),
(162, 'Shipping Address', 'Shipping Address'),
(163, 'Rating is Submitted Successfully!', 'Rating is Submitted Successfully!');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_messages`
--

CREATE TABLE `tbl_messages` (
  `id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `rider_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `reply_to` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `c_status` int(11) DEFAULT 1,
  `r_status` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_messages`
--

INSERT INTO `tbl_messages` (`id`, `cust_id`, `rider_id`, `subject`, `message`, `reply_to`, `created_at`, `c_status`, `r_status`) VALUES
(15, 2, 10, 'chat support', 'Hi maam', 2, '2024-12-06 14:19:24', 1, 1),
(17, 2, 10, 'chat support', 'Aha naka maam', 1, '2024-12-06 14:21:22', 1, 1),
(19, 1, 10, 'chat support', 'hello po', 1, '2024-12-06 15:01:07', 0, 1),
(20, 1, 10, 'chat support', 'Hello Maam? asa dapita inyoha\n', 1, '2024-12-06 15:09:45', 0, 1),
(21, 1, 10, 'chat support', 'hello where you', 1, '2024-12-06 15:10:23', 0, 1),
(22, 1, 10, 'chat support', 'going', 1, '2024-12-06 15:11:18', 0, 1),
(23, 1, 10, 'chat support', 'Hello sir waiting po', 2, '2024-12-06 15:15:08', 0, 1),
(24, 1, 10, 'chat support', 'ganina rako dri', 2, '2024-12-06 15:15:35', 0, 1),
(25, 1, 10, 'chat support', 'Ok maam', 1, '2024-12-06 15:46:59', 1, 1),
(26, 1, 10, 'chat support', 'thanks sir', 2, '2024-12-06 23:36:50', 1, 1),
(27, 1, 10, 'chat support', 'here na me', 1, '2024-12-07 00:08:26', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mid_category`
--

CREATE TABLE `tbl_mid_category` (
  `mcat_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `mcat_name` varchar(255) NOT NULL,
  `tcat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_mid_category`
--

INSERT INTO `tbl_mid_category` (`mcat_id`, `seller_id`, `mcat_name`, `tcat_id`) VALUES
(1, 36, 'Seafood', 2),
(2, 36, 'Pork', 2),
(3, 36, 'Beef', 2),
(4, 36, 'Vegetable', 1),
(5, 36, 'Fruits', 1),
(6, 24, 'Beef', 3),
(7, 24, 'Pork', 3),
(8, 3, 'Vegetable', 4),
(9, 3, 'Fruits', 4),
(10, 3, 'Pork', 5),
(11, 3, 'Beef', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `size` varchar(100) NOT NULL,
  `color` varchar(100) NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `unit_price` varchar(50) NOT NULL,
  `payment_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `product_id`, `product_name`, `size`, `color`, `quantity`, `unit_price`, `payment_id`) VALUES
(16, 29, 'Pork Chop', '', '', '3', '145', '1736080195'),
(17, 28, 'Pork Chop', '', '', '1', '150', '1736080195'),
(18, 31, 'Guava', '', '', '1', '150', '1736601092'),
(19, 28, 'Pork Chop', '', '', '1', '150', '1736601092');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_page`
--

CREATE TABLE `tbl_page` (
  `id` int(11) NOT NULL,
  `about_title` varchar(255) NOT NULL,
  `about_content` text NOT NULL,
  `about_banner` varchar(255) NOT NULL,
  `about_meta_title` varchar(255) NOT NULL,
  `about_meta_keyword` text NOT NULL,
  `about_meta_description` text NOT NULL,
  `faq_title` varchar(255) NOT NULL,
  `faq_banner` varchar(255) NOT NULL,
  `faq_meta_title` varchar(255) NOT NULL,
  `faq_meta_keyword` text NOT NULL,
  `faq_meta_description` text NOT NULL,
  `blog_title` varchar(255) NOT NULL,
  `blog_banner` varchar(255) NOT NULL,
  `blog_meta_title` varchar(255) NOT NULL,
  `blog_meta_keyword` text NOT NULL,
  `blog_meta_description` text NOT NULL,
  `contact_title` varchar(255) NOT NULL,
  `contact_banner` varchar(255) NOT NULL,
  `contact_meta_title` varchar(255) NOT NULL,
  `contact_meta_keyword` text NOT NULL,
  `contact_meta_description` text NOT NULL,
  `pgallery_title` varchar(255) NOT NULL,
  `pgallery_banner` varchar(255) NOT NULL,
  `pgallery_meta_title` varchar(255) NOT NULL,
  `pgallery_meta_keyword` text NOT NULL,
  `pgallery_meta_description` text NOT NULL,
  `vgallery_title` varchar(255) NOT NULL,
  `vgallery_banner` varchar(255) NOT NULL,
  `vgallery_meta_title` varchar(255) NOT NULL,
  `vgallery_meta_keyword` text NOT NULL,
  `vgallery_meta_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_page`
--

INSERT INTO `tbl_page` (`id`, `about_title`, `about_content`, `about_banner`, `about_meta_title`, `about_meta_keyword`, `about_meta_description`, `faq_title`, `faq_banner`, `faq_meta_title`, `faq_meta_keyword`, `faq_meta_description`, `blog_title`, `blog_banner`, `blog_meta_title`, `blog_meta_keyword`, `blog_meta_description`, `contact_title`, `contact_banner`, `contact_meta_title`, `contact_meta_keyword`, `contact_meta_description`, `pgallery_title`, `pgallery_banner`, `pgallery_meta_title`, `pgallery_meta_keyword`, `pgallery_meta_description`, `vgallery_title`, `vgallery_banner`, `vgallery_meta_title`, `vgallery_meta_keyword`, `vgallery_meta_description`) VALUES
(1, 'About Us', '<p style=\"border: 0px solid; margin-top: 1.5rem; margin-bottom: 0px;\">Welcome to Pamilihan Online Store</p><p style=\"border: 0px solid; margin-top: 1.5rem; margin-bottom: 0px;\"><span style=\"border: 0px solid;\">We aim to offer our customers a variety of the latest [PRODUCTS_CATEGORY_NAME]. Weâ€™ve come a long way, so we know exactly which direction to take when supplying you with high quality yet budget-friendly products. We offer all of this while providing excellent customer service and friendly support.</span></p><p style=\"border: 0px solid; margin-top: 1.5rem; margin-bottom: 0px;\"><span style=\"border: 0px solid;\">We always keep an eye on the latest trends in [PRODUCTS CATEGORY NAME] and put our customersâ€™ wishes first. That is why we have satisfied customers all over the world, and are thrilled to be a part of the [PRODUCTS CATEGORY NAME] industry.</span></p><p style=\"border: 0px solid; margin-top: 1.5rem; margin-bottom: 0px;\"><span style=\"border: 0px solid;\">The interests of our customers are always top priority for us, so we hope you will enjoy our products as much as we enjoy making them available to you.</span></p><p style=\"\">We make sure you get the best quality outfits with hassle free returns and exchanges policy. We ensure what you see is exactly what you get!</p><ul><li style=\"text-align: justify;\"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">Low Price Guarantee</span></font></li><li style=\"text-align: justify;\"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">24/7 Customer Support</span></font></li><li style=\"text-align: justify;\"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">E-Mail - Text - Call</span></font></li><li style=\"text-align: justify;\"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">We are here for you 24/7 online and via phone.</span></font></li><li style=\"text-align: justify;\"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">Sizing & Color</span></font></li><li style=\"text-align: justify;\"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">Worldwide Shipping</span></font></li><li style=\"text-align: justify;\"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">Weâ€™d love to expand our business Internationally soon.</span></font></li><li style=\"text-align: justify;\"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">Easy Returns</span></font></li></ul><p style=\"text-align: justify; \"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">Bought an outfit but want to return it? We have a 3 days easy return policy. Please mail us at support@ecommercephp.com for more details.</span></font></p><p style=\"text-align: justify; \"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\"><b>Dream Dresses for Every Occasion</b></span></font></p><p style=\"text-align: justify; \"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">Fashionys.com carries all carefully handpicked by our stylists. If youâ€™re interested in a particular model please mail us we will try our best to offer you the loved dress.</span></font></p><p style=\"text-align: justify; \"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\"><b>Verified Security</b></span></font></p><p style=\"text-align: justify; \"><font face=\"apercu, Arial, sans-serif\"><span style=\"font-size: 14px;\">All our transactions are Verified by Norton and with the highest standards of security. Plus, theres a lot to go around too through regular exciting offers and gifts, so spread the word and refer us to everyone from your family, friends and colleagues and get rewarded for it. And to top it all, you can share your user experience by posting reviews. Donâ€™t wait any longer Sign up with us now! start stalking, start buying and start loving and start Introducing the beauty in you.</span></font></p>\r\n', 'about-banner.jpg', 'Pamilihan - About Us', 'about, about us, about fashion, about company, about ecommerce php project', 'Our goal has always been to get the best in you we brought a huge collection whether youâ€™re attending a party, wedding, and all those events that require a WOW dress.', 'FAQ', 'faq-banner.jpg', 'Fashionys.com - FAQ', '', '', 'Blog', 'blog-banner.jpg', 'Ecommerce - Blog', '', '', 'Contact Us', 'contact-banner.jpg', 'Fashionys.com - Contact', '', '', 'Photo Gallery', 'pgallery-banner.jpg', 'Ecommerce - Photo Gallery', '', '', 'Video Gallery', 'vgallery-banner.jpg', 'Ecommerce - Video Gallery', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_payment`
--

CREATE TABLE `tbl_payment` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `payment_date` varchar(50) NOT NULL,
  `txnid` varchar(255) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `card_number` varchar(50) NOT NULL,
  `card_cvv` varchar(10) NOT NULL,
  `card_month` varchar(10) NOT NULL,
  `card_year` varchar(10) NOT NULL,
  `bank_transaction_info` text NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `payment_status` varchar(25) NOT NULL,
  `shipping_status` varchar(20) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  `rider_id` int(11) DEFAULT NULL,
  `rider_email` varchar(45) DEFAULT NULL,
  `shipped_date` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_payment`
--

INSERT INTO `tbl_payment` (`id`, `customer_id`, `customer_name`, `customer_email`, `payment_date`, `txnid`, `paid_amount`, `card_number`, `card_cvv`, `card_month`, `card_year`, `bank_transaction_info`, `payment_method`, `payment_status`, `shipping_status`, `payment_id`, `rider_id`, `rider_email`, `shipped_date`) VALUES
(12, 2, 'Sweet Muffis', 'sweet@gmail.com', '2025-01-11 21:11:32', 'TXN21736601092', 301, '', '', '', '', '', 'COD', 'Completed', 'Completed', '1736601092', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_photo`
--

CREATE TABLE `tbl_photo` (
  `id` int(11) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_photo`
--

INSERT INTO `tbl_photo` (`id`, `caption`, `photo`) VALUES
(1, 'Photo 1', 'photo-1.jpg'),
(2, 'Photo 2', 'photo-2.jpg'),
(3, 'Photo 3', 'photo-3.jpg'),
(4, 'Photo 4', 'photo-4.jpg'),
(5, 'Photo 5', 'photo-5.jpg'),
(6, 'Photo 6', 'photo-6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post`
--

CREATE TABLE `tbl_post` (
  `post_id` int(11) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_slug` varchar(255) NOT NULL,
  `post_content` text NOT NULL,
  `post_date` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `total_view` int(11) NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_keyword` text NOT NULL,
  `meta_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `p_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `p_name` varchar(255) NOT NULL,
  `p_retail` varchar(10) NOT NULL,
  `p_wholesale` varchar(10) NOT NULL,
  `p_w_confirm` tinyint(1) NOT NULL,
  `nearly_expiration` int(11) NOT NULL,
  `critical_level` int(11) NOT NULL,
  `stocks_reorder` int(11) NOT NULL,
  `p_old_price` varchar(10) NOT NULL,
  `p_current_price` varchar(10) NOT NULL,
  `p_qty` int(11) NOT NULL,
  `p_featured_photo` varchar(255) NOT NULL,
  `p_description` text NOT NULL,
  `p_short_description` text NOT NULL,
  `p_feature` text NOT NULL,
  `p_condition` text NOT NULL,
  `p_return_policy` text NOT NULL,
  `p_total_view` int(11) NOT NULL,
  `p_is_featured` int(11) NOT NULL,
  `p_is_active` int(11) NOT NULL,
  `ecat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`p_id`, `u_id`, `p_name`, `p_retail`, `p_wholesale`, `p_w_confirm`, `nearly_expiration`, `critical_level`, `stocks_reorder`, `p_old_price`, `p_current_price`, `p_qty`, `p_featured_photo`, `p_description`, `p_short_description`, `p_feature`, `p_condition`, `p_return_policy`, `p_total_view`, `p_is_featured`, `p_is_active`, `ecat_id`) VALUES
(3, 3, 'Pork Chop', '150', '125', 0, 0, 20, 12, '', '', 0, 'product-featured-3.jpg', '', '<p>1kg</p>', '', '', '', 37, 1, 1, 0),
(4, 3, 'Guava', '150', '125', 0, 0, 20, 12, '', '', 0, 'product-featured-4.jpg', '', '<p>1kg</p>', '', '', '', 154, 1, 1, 0),
(13, 13, 'Fresh Beef Brisket', '249', '', 0, 0, 20, 12, '', '', 0, 'product-featured-13.jpg', '', '', '', '', '', 228, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_category`
--

CREATE TABLE `tbl_product_category` (
  `id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `tcat_id` int(11) NOT NULL,
  `mcat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product_category`
--

INSERT INTO `tbl_product_category` (`id`, `p_id`, `tcat_id`, `mcat_id`) VALUES
(12, 28, 5, 3),
(13, 29, 5, 3),
(14, 30, 4, 1),
(15, 31, 4, 1),
(16, 1, 1, 5),
(17, 2, 3, 7),
(18, 3, 5, 10),
(19, 4, 4, 8),
(20, 5, 5, 10),
(21, 6, 5, 10),
(22, 7, 5, 10),
(23, 8, 5, 10),
(24, 9, 5, 10),
(25, 10, 5, 10),
(26, 11, 5, 10),
(27, 12, 5, 10),
(28, 13, 5, 10);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_color`
--

CREATE TABLE `tbl_product_color` (
  `id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_photo`
--

CREATE TABLE `tbl_product_photo` (
  `pp_id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `p_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_size`
--

CREATE TABLE `tbl_product_size` (
  `id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_product_size`
--

INSERT INTO `tbl_product_size` (`id`, `size_id`, `p_id`) VALUES
(35, 1, 13),
(36, 2, 13),
(45, 2, 4),
(46, 4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_item`
--

CREATE TABLE `tbl_purchase_item` (
  `id` int(11) NOT NULL,
  `type_item` varchar(20) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `seller_id` varchar(20) NOT NULL,
  `product_id` varchar(20) NOT NULL,
  `size_id` varchar(20) NOT NULL,
  `product_price` varchar(50) NOT NULL,
  `product_qty` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_purchase_item`
--

INSERT INTO `tbl_purchase_item` (`id`, `type_item`, `order_id`, `seller_id`, `product_id`, `size_id`, `product_price`, `product_qty`, `status`) VALUES
(1, 'product', 'OW62PFLFR54', '3', '13', '1', '31', '4', 'Completed'),
(2, 'product', 'OMSGXZV3RMC', '3', '3', '2', '150', '2', 'Completed'),
(3, 'product', 'OMSGXZV3RMC', '3', '4', '4', '75', '1', 'Completed'),
(4, 'product', 'OMSGXZV3RMC', '3', '13', '2', '249', '1', 'Completed'),
(5, 'product', 'OR0OWH76MN9', '3', '3', '0', '150', '1', 'Pending'),
(6, 'product', 'OVLPJCA8NJS', '3', '3', '0', '150', '1', 'Pending'),
(7, 'product', 'OLI72VIQ89B', '3', '3', '0', '150', '1', 'Pending'),
(8, 'product', 'OHSTK07P7FH', '3', '4', '2', '150', '1', 'Pending'),
(9, 'product', 'OE56AKRQMBI', '3', '3', '0', '150', '1', 'Pending'),
(10, 'product', 'OG5UG5SES0J', '3', '13', '2', '249', '1', 'Pending'),
(11, 'product', 'OGYIOX6QLD1', '3', '4', '2', '150', '1', 'Pending'),
(12, 'product', 'O6FYTPADBAN', '3', '13', '2', '249', '1', 'Pending'),
(13, 'product', 'O6FYTPADBAN', '3', '4', '2', '150', '1', 'Pending'),
(14, 'product', 'O7LFWKT3YPO', '3', '3', '0', '150', '1', 'Pending'),
(15, 'product', 'OSO6C1NE9ZF', '3', '3', '0', '150', '1', 'Pending'),
(16, 'product', 'O5IPAEV565H', '3', '3', '0', '150', '2', 'Pending'),
(17, 'product', 'OR6MMDLRC9J', '3', '4', '2', '150', '1', 'Pending'),
(18, 'product', 'OR6MMDLRC9J', '13', '13', '2', '249', '1', 'Pending'),
(19, 'product', 'OKUK0XK6EJK', '13', '13', '2', '249', '1', 'Pending'),
(20, 'product', 'OKUK0XK6EJK', '3', '3', '0', '150', '1', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_order`
--

CREATE TABLE `tbl_purchase_order` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `rider_id` varchar(11) NOT NULL,
  `remarks` text NOT NULL,
  `date_and_time` varchar(125) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_purchase_order`
--

INSERT INTO `tbl_purchase_order` (`id`, `order_id`, `customer_id`, `rider_id`, `remarks`, `date_and_time`, `status`, `created_at`) VALUES
(1, 'OW62PFLFR54', 5, '6', 'Completed', '1737103020', 'Completed', '2025-01-27 05:11:28'),
(2, 'OMSGXZV3RMC', 5, '6', 'Completed', '1737269465', 'Completed', '2025-01-27 05:11:28'),
(3, 'OR0OWH76MN9', 5, '', 'Pending', '1737915466', 'Pending', '2025-01-27 05:11:28'),
(4, 'OVLPJCA8NJS', 5, '', 'Pending', '1737915536', 'Pending', '2025-01-27 05:11:28'),
(5, 'OLI72VIQ89B', 5, '', 'Pending', '1737915933', 'Pending', '2025-01-27 05:11:28'),
(6, 'OHSTK07P7FH', 5, '', 'Pending', '1737916006', 'Pending', '2025-01-27 05:11:28'),
(7, 'OE56AKRQMBI', 5, '', 'Pending', '1737916066', 'Pending', '2025-01-27 05:11:28'),
(8, 'OG5UG5SES0J', 5, '', 'Pending', '1737916170', 'Pending', '2025-01-27 05:11:28'),
(9, 'OGYIOX6QLD1', 5, '6', 'Transferred to Rider', '1737917162', 'Rider', '2025-01-27 05:11:28'),
(10, 'O6FYTPADBAN', 5, '', 'Pending', '1737954626', 'Pending', '2025-01-27 05:11:28'),
(11, 'O7LFWKT3YPO', 5, '', 'Pending', '1737954765', 'Pending', '2025-01-27 05:12:45'),
(12, 'OSO6C1NE9ZF', 5, '', 'Pending', '1737954978', 'Pending', '2025-01-27 05:16:18'),
(13, 'O5IPAEV565H', 5, '', 'Accepted by Seller', '1737955013', 'Accepted', '2025-01-28 04:15:01'),
(14, 'OR6MMDLRC9J', 5, '2', 'Transferred to Rider', '1737955975', 'Rider', '2025-01-27 14:43:23'),
(15, 'OKUK0XK6EJK', 5, '6', 'Transferred to Rider', '1737960086', 'Rider', '2025-01-27 14:30:59');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_purchase_payment`
--

CREATE TABLE `tbl_purchase_payment` (
  `id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `total_amount` varchar(20) NOT NULL,
  `transaction_id` varchar(20) NOT NULL,
  `date_and_time` varchar(50) NOT NULL,
  `transaction_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_purchase_payment`
--

INSERT INTO `tbl_purchase_payment` (`id`, `order_id`, `total_amount`, `transaction_id`, `date_and_time`, `transaction_status`) VALUES
(1, 'OW62PFLFR54', '274', 'T9761926589', '1737103020', 'Completed'),
(2, 'OMSGXZV3RMC', '774', 'T2482746774', '1737269465', 'Completed'),
(3, 'OR0OWH76MN9', '300', 'T3401086932', '1737915466', 'Pending'),
(4, 'OVLPJCA8NJS', '300', 'T0810711019', '1737915536', 'Pending'),
(5, 'OLI72VIQ89B', '300', 'T6274966663', '1737915933', 'Pending'),
(6, 'OHSTK07P7FH', '300', 'T5336070054', '1737916006', 'Pending'),
(7, 'OE56AKRQMBI', '300', 'T7273735181', '1737916066', 'Pending'),
(8, 'OG5UG5SES0J', '399', 'T1690073372', '1737916170', 'Pending'),
(9, 'OGYIOX6QLD1', '300', 'T2918891245', '1737917162', 'Pending'),
(10, 'O6FYTPADBAN', '549', 'T7818900343', '1737954626', 'Pending'),
(11, 'O7LFWKT3YPO', '300', 'T5752832293', '1737954765', 'Pending'),
(12, 'OSO6C1NE9ZF', '300', 'T4881307362', '1737954978', 'Pending'),
(13, 'O5IPAEV565H', '450', 'T0184878585', '1737955013', 'Pending'),
(14, 'OR6MMDLRC9J', '549', 'T0666178254', '1737955975', 'Pending'),
(15, 'OKUK0XK6EJK', '549', 'T1425034970', '1737960086', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rating`
--

CREATE TABLE `tbl_rating` (
  `rt_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_realtime_message`
--

CREATE TABLE `tbl_realtime_message` (
  `id` int(11) NOT NULL,
  `from_user` varchar(25) NOT NULL,
  `to_user` varchar(25) NOT NULL,
  `remarks` varchar(100) NOT NULL,
  `date_time` varchar(50) NOT NULL,
  `cust_read_status` int(11) NOT NULL,
  `rider_read_status` int(11) NOT NULL,
  `deleted_at` varchar(50) NOT NULL,
  `chat_status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_recipe`
--

CREATE TABLE `tbl_recipe` (
  `r_id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL,
  `r_name` varchar(255) NOT NULL,
  `r_description` text NOT NULL,
  `r_total_view` varchar(255) NOT NULL,
  `r_featured_photo` varchar(255) NOT NULL,
  `r_is_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_recipe`
--

INSERT INTO `tbl_recipe` (`r_id`, `u_id`, `r_name`, `r_description`, `r_total_view`, `r_featured_photo`, `r_is_active`) VALUES
(1, 3, 'Sinigang', '<p>Step 1:</p><ul><li>For Step 1</li></ul><p>Step 2:</p><ul><li>For Step 2</li></ul>', '120', 'recipe-featured-1.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_recipe_product`
--

CREATE TABLE `tbl_recipe_product` (
  `id` int(11) NOT NULL,
  `u_id` int(11) NOT NULL COMMENT 'This column is for seller id',
  `r_id` int(11) NOT NULL COMMENT 'This column is for recipe id',
  `p_id` int(11) NOT NULL COMMENT 'This column is for product id',
  `s_id` int(11) NOT NULL COMMENT 'This column is for size id',
  `quantity` int(11) NOT NULL,
  `price` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_recipe_product`
--

INSERT INTO `tbl_recipe_product` (`id`, `u_id`, `r_id`, `p_id`, `s_id`, `quantity`, `price`) VALUES
(1, 3, 1, 3, 2, 2, ''),
(2, 3, 1, 4, 4, 1, ''),
(3, 3, 1, 13, 2, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rider`
--

CREATE TABLE `tbl_rider` (
  `id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `license_number` varchar(100) DEFAULT NULL,
  `vehicle_type` varchar(45) DEFAULT NULL,
  `vehicle_model` varchar(50) NOT NULL,
  `vehicle_plate_no` varchar(20) NOT NULL,
  `r_token` varchar(255) DEFAULT NULL,
  `r_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_rider`
--

INSERT INTO `tbl_rider` (`id`, `user_id`, `license_number`, `vehicle_type`, `vehicle_model`, `vehicle_plate_no`, `r_token`, `r_status`) VALUES
(9, 'male', '123456789', 'motorcycle', '', '', NULL, ''),
(10, 'male', '963852741', 'motorcycle', '', '', '', ''),
(11, 'male', NULL, 'motorcycle', '', '', NULL, ''),
(12, 'male', '9878960000001', 'car', '', '', NULL, ''),
(15, '0', '453943', 'Motorcycle', 'VSJK1', '431432', '1736682335', 'Pending'),
(16, '51', '454323', 'Motorcycle', 'TJW153', 'AWTS12', '1736682857', 'Verified'),
(17, '2', '3059837', 'FJLSKD', 'asas', '45345', '1736733440', 'Verified'),
(18, '6', '58394', 'asdasda', 'vhs10', '014583', '1736914221', 'Verified');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_seller`
--

CREATE TABLE `tbl_seller` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `business_title` varchar(100) NOT NULL,
  `business_name` varchar(200) NOT NULL,
  `business_type` varchar(125) NOT NULL,
  `tin_id` varchar(20) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_seller`
--

INSERT INTO `tbl_seller` (`id`, `user_id`, `business_title`, `business_name`, `business_type`, `tin_id`, `status`) VALUES
(1, 24, 'seller1', 'Business Name Seller 1', 'Open-Air Business Venue', '64643445', 'Verified'),
(4, 36, 'myseller3', 'My Seller 3', 'Open-Air Business Venue', '63345123', 'Verified'),
(5, 37, 'asdasd', 'dsadasdasd', 'Open-Air Business Venue', '543534', 'Verified'),
(6, 3, 'asdasdasd', 'dsadasdas', 'Open-Air Business Venue', '543543454', 'Verified'),
(7, 13, 'dasdas', 'dasdas', 'Open-Air Business Venue', '312312312', 'Verified');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_service`
--

CREATE TABLE `tbl_service` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_service`
--

INSERT INTO `tbl_service` (`id`, `title`, `content`, `photo`) VALUES
(5, 'Easy Returns', 'Return any item before 15 days!', 'service-5.png'),
(6, 'Free Shipping', 'Enjoy free shipping inside US.', 'service-6.png'),
(7, 'Fast Shipping', 'Items are shipped within 24 hours.', 'service-7.png'),
(8, 'Satisfaction Guarantee', 'We guarantee you with our quality satisfaction.', 'service-8.png'),
(9, 'Secure Checkout', 'Providing Secure Checkout Options for all', 'service-9.png'),
(10, 'Money Back Guarantee', 'Offer money back guarantee on our products', 'service-10.png');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_service_fee`
--

CREATE TABLE `tbl_service_fee` (
  `id` int(11) NOT NULL,
  `from_p` varchar(20) NOT NULL,
  `to_p` varchar(20) NOT NULL,
  `cost` varchar(20) NOT NULL,
  `delivery_fee` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_service_fee`
--

INSERT INTO `tbl_service_fee` (`id`, `from_p`, `to_p`, `cost`, `delivery_fee`) VALUES
(8, '1', '10', '100', '50'),
(9, '20', '30', '300', '99'),
(10, '11', '20', '200', '57');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_service_fee_all`
--

CREATE TABLE `tbl_service_fee_all` (
  `id` int(11) NOT NULL,
  `cost` varchar(20) NOT NULL,
  `delivery_fee` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `logo` longtext NOT NULL,
  `favicon` longtext NOT NULL,
  `footer_about` text NOT NULL,
  `footer_copyright` text NOT NULL,
  `contact_address` text NOT NULL,
  `contact_email` longtext NOT NULL,
  `contact_phone` longtext NOT NULL,
  `contact_fax` longtext NOT NULL,
  `contact_map_iframe` text NOT NULL,
  `receive_email` longtext NOT NULL,
  `receive_email_subject` longtext NOT NULL,
  `receive_email_thank_you_message` text NOT NULL,
  `forget_password_message` text NOT NULL,
  `total_recent_post_footer` int(11) NOT NULL,
  `total_popular_post_footer` int(11) NOT NULL,
  `total_recent_post_sidebar` int(11) NOT NULL,
  `total_popular_post_sidebar` int(11) NOT NULL,
  `total_featured_product_home` int(11) NOT NULL,
  `total_latest_product_home` int(11) NOT NULL,
  `total_popular_product_home` int(11) NOT NULL,
  `meta_title_home` text NOT NULL,
  `meta_keyword_home` text NOT NULL,
  `meta_description_home` text NOT NULL,
  `banner_login` longtext NOT NULL,
  `banner_registration` longtext NOT NULL,
  `banner_forget_password` longtext NOT NULL,
  `banner_reset_password` longtext NOT NULL,
  `banner_search` longtext NOT NULL,
  `banner_cart` longtext NOT NULL,
  `banner_checkout` longtext NOT NULL,
  `banner_product_category` longtext NOT NULL,
  `banner_blog` longtext NOT NULL,
  `cta_title` longtext NOT NULL,
  `cta_content` text NOT NULL,
  `cta_read_more_text` longtext NOT NULL,
  `cta_read_more_url` longtext NOT NULL,
  `cta_photo` longtext NOT NULL,
  `featured_product_title` longtext NOT NULL,
  `featured_product_subtitle` longtext NOT NULL,
  `latest_product_title` longtext NOT NULL,
  `latest_product_subtitle` longtext NOT NULL,
  `popular_product_title` longtext NOT NULL,
  `popular_product_subtitle` longtext NOT NULL,
  `testimonial_title` longtext NOT NULL,
  `testimonial_subtitle` longtext NOT NULL,
  `testimonial_photo` longtext NOT NULL,
  `blog_title` longtext NOT NULL,
  `blog_subtitle` longtext NOT NULL,
  `newsletter_text` text NOT NULL,
  `paypal_email` longtext NOT NULL,
  `stripe_public_key` longtext NOT NULL,
  `stripe_secret_key` longtext NOT NULL,
  `bank_detail` text NOT NULL,
  `before_head` text NOT NULL,
  `after_body` text NOT NULL,
  `before_body` text NOT NULL,
  `home_service_on_off` int(11) NOT NULL,
  `home_welcome_on_off` int(11) NOT NULL,
  `home_featured_product_on_off` int(11) NOT NULL,
  `home_latest_product_on_off` int(11) NOT NULL,
  `home_popular_product_on_off` int(11) NOT NULL,
  `home_testimonial_on_off` int(11) NOT NULL,
  `home_blog_on_off` int(11) NOT NULL,
  `newsletter_on_off` int(11) NOT NULL,
  `ads_above_welcome_on_off` int(11) NOT NULL,
  `ads_above_featured_product_on_off` int(11) NOT NULL,
  `ads_above_latest_product_on_off` int(11) NOT NULL,
  `ads_above_popular_product_on_off` int(11) NOT NULL,
  `ads_above_testimonial_on_off` int(11) NOT NULL,
  `ads_category_sidebar_on_off` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `logo`, `favicon`, `footer_about`, `footer_copyright`, `contact_address`, `contact_email`, `contact_phone`, `contact_fax`, `contact_map_iframe`, `receive_email`, `receive_email_subject`, `receive_email_thank_you_message`, `forget_password_message`, `total_recent_post_footer`, `total_popular_post_footer`, `total_recent_post_sidebar`, `total_popular_post_sidebar`, `total_featured_product_home`, `total_latest_product_home`, `total_popular_product_home`, `meta_title_home`, `meta_keyword_home`, `meta_description_home`, `banner_login`, `banner_registration`, `banner_forget_password`, `banner_reset_password`, `banner_search`, `banner_cart`, `banner_checkout`, `banner_product_category`, `banner_blog`, `cta_title`, `cta_content`, `cta_read_more_text`, `cta_read_more_url`, `cta_photo`, `featured_product_title`, `featured_product_subtitle`, `latest_product_title`, `latest_product_subtitle`, `popular_product_title`, `popular_product_subtitle`, `testimonial_title`, `testimonial_subtitle`, `testimonial_photo`, `blog_title`, `blog_subtitle`, `newsletter_text`, `paypal_email`, `stripe_public_key`, `stripe_secret_key`, `bank_detail`, `before_head`, `after_body`, `before_body`, `home_service_on_off`, `home_welcome_on_off`, `home_featured_product_on_off`, `home_latest_product_on_off`, `home_popular_product_on_off`, `home_testimonial_on_off`, `home_blog_on_off`, `newsletter_on_off`, `ads_above_welcome_on_off`, `ads_above_featured_product_on_off`, `ads_above_latest_product_on_off`, `ads_above_popular_product_on_off`, `ads_above_testimonial_on_off`, `ads_category_sidebar_on_off`) VALUES
(1, 'logo.png', 'favicon.jpg', '<p>Lorem ipsum dolor sit amet, omnis signiferumque in mei, mei ex enim concludaturque. Senserit salutandi euripidis no per, modus maiestatis scribentur est an.Â Ea suas pertinax has.</p>\r\n', '2024', 'Iligan', 'support@ecommercephp.com', '090909090000', '', '<iframe width=\"425\" height=\"350\" src=\"https://www.openstreetmap.org/export/embed.html?bbox=124.60933685302736%2C8.528058719894872%2C124.89772796630861%2C8.771760446544256&amp;layer=mapnik&amp;marker=8.64992929434691%2C124.75353240966797\" style=\"border: 1px solid black\"></iframe><br/><small><a href=\"https://www.openstreetmap.org/?mlat=8.6499&amp;mlon=124.7535#map=12/8.6499/124.7535\">View Larger Map</a></small>', 'support@ecommercephp.com', 'Visitor Email Message from Ecommerce Site PHP', 'Thank you for sending email. We will contact you shortly.', 'A confirmation link is sent to your email address. You will get the password reset information in there.', 4, 4, 5, 5, 5, 6, 8, 'Pamilihan', 'online fashion store, garments shop, online garments', 'Pamilihan Online Store', 'banner_login.jpg', 'banner_registration.jpg', 'banner_forget_password.jpg', 'banner_reset_password.jpg', 'banner_search.jpg', 'banner_cart.jpg', 'banner_checkout.jpg', 'banner_product_category.jpg', 'banner_blog.jpg', 'Welcome To Our Ecommerce Website', 'Lorem ipsum dolor sit amet, an labores explicari qui, eu nostrum copiosae argumentum has. Latine propriae quo no, unum ridens expetenda id sit, \r\nat usu eius eligendi singulis. Sea ocurreret principes ne. At nonumy aperiri pri, nam quodsi copiosae intellegebat et, ex deserunt euripidis usu. ', 'Read More', '#', 'cta.jpg', 'Featured Products', 'Our list on Top Featured Products', 'Latest Products', 'Our list of recently added products', 'Popular Products', 'Popular products based on customer\'s choice', 'Testimonials', 'See what our clients tell about us', 'testimonial.jpg', 'Latest Blog', 'See all our latest articles and news from below', 'Sign-up to our newsletter for latest promotions and discounts.', 'admin@pamilihan.net', 'pk_test_0SwMWadgu8DwmEcPdUPRsZ7b', 'sk_test_TFcsLJ7xxUtpALbDo1L5c1PN', 'Bank Name: BDO\r\nAccount Number: 123456789\r\nBranch Name: BDO Branch\r\nCountry: PH', '', '<div id=\"fb-root\"></div>\r\n<script>(function(d, s, id) {\r\n  var js, fjs = d.getElementsByTagName(s)[0];\r\n  if (d.getElementById(id)) return;\r\n  js = d.createElement(s); js.id = id;\r\n  js.src = \"//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10&appId=323620764400430\";\r\n  fjs.parentNode.insertBefore(js, fjs);\r\n}(document, \'script\', \'facebook-jssdk\'));</script>', '<!--Start of Tawk.to Script-->\r\n<script type=\"text/javascript\">\r\nvar Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\r\n(function(){\r\nvar s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\r\ns1.async=true;\r\ns1.src=\'https://embed.tawk.to/6776f2e1af5bfec1dbe5f042/1igka9h18\';\r\ns1.charset=\'UTF-8\';\r\ns1.setAttribute(\'crossorigin\',\'*\');\r\ns0.parentNode.insertBefore(s1,s0);\r\n})();\r\n</script>\r\n<!--End of Tawk.to Script-->', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shipping_address`
--

CREATE TABLE `tbl_shipping_address` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `country` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `custLat` varchar(25) NOT NULL,
  `custLng` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_shipping_address`
--

INSERT INTO `tbl_shipping_address` (`id`, `user_id`, `full_name`, `phone`, `country`, `address`, `city`, `custLat`, `custLng`) VALUES
(1, 4, 'Example Cust', '09345789782', 'Philippines', 'asdasda', 'Taguig', '', ''),
(2, 5, 'Example Customer', '0987654321', 'Philippines', 'Cavite-Laguna Expressway, Buenavista II, General Trias, Cavite, Calabarzon, 4107, Philippines', 'Brgy. Sta Rosa 1', '14.3186', '120.9061'),
(7, 11, '', '', 'Philippines', '', '', '', ''),
(8, 12, '', '', 'Philippines', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shipping_cost`
--

CREATE TABLE `tbl_shipping_cost` (
  `shipping_cost_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `amount` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_shipping_cost`
--

INSERT INTO `tbl_shipping_cost` (`shipping_cost_id`, `country_id`, `amount`) VALUES
(1, 228, '11'),
(2, 167, '10'),
(3, 13, '8'),
(4, 230, '0'),
(5, 174, '1'),
(6, 1, '15'),
(7, 1, '15'),
(8, 1, '15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shipping_cost_all`
--

CREATE TABLE `tbl_shipping_cost_all` (
  `sca_id` int(11) NOT NULL,
  `amount` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_shipping_cost_all`
--

INSERT INTO `tbl_shipping_cost_all` (`sca_id`, `amount`) VALUES
(1, '100');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_size`
--

CREATE TABLE `tbl_size` (
  `size_id` int(11) NOT NULL,
  `size_name` varchar(255) NOT NULL,
  `size_value` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_size`
--

INSERT INTO `tbl_size` (`size_id`, `size_name`, `size_value`) VALUES
(1, '1/8kg', '8'),
(2, '1kg', '1'),
(3, '1/4kg', '4'),
(4, '1/2kg', '2');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_slider`
--

CREATE TABLE `tbl_slider` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `heading` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `button_text` varchar(255) NOT NULL,
  `button_url` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_slider`
--

INSERT INTO `tbl_slider` (`id`, `photo`, `heading`, `content`, `button_text`, `button_url`, `position`) VALUES
(1, 'slider-1.png', 'Welcome to PAMILIHAN', 'Shop Online for Fresh Vegatables and Fruits', 'Order Now', './product-category.php?id=4&type=mid-category', 'Center'),
(3, 'slider-3.jpg', '24 Hours Customer Support', 'Lorem ipsum dolor sit amet, an labores explicari qui, eu nostrum copiosae argumentum has.', 'Read More', '#', 'Right'),
(4, 'slider-4.jpg', '', '', '', '', 'Left');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_smtp_settings`
--

CREATE TABLE `tbl_smtp_settings` (
  `id` int(11) NOT NULL,
  `smtp_host` varchar(50) NOT NULL,
  `smtp_port` varchar(10) NOT NULL,
  `smtp_username` varchar(100) NOT NULL,
  `smtp_password` int(11) NOT NULL,
  `smtp_baseurl` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social`
--

CREATE TABLE `tbl_social` (
  `social_id` int(11) NOT NULL,
  `social_name` varchar(30) NOT NULL,
  `social_url` varchar(255) NOT NULL,
  `social_icon` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_social`
--

INSERT INTO `tbl_social` (`social_id`, `social_name`, `social_url`, `social_icon`) VALUES
(1, 'Facebook', 'https://www.facebook.com/#', 'fa fa-facebook'),
(2, 'Twitter', 'https://www.twitter.com/#', 'fa fa-twitter'),
(3, 'LinkedIn', '', 'fa fa-linkedin'),
(4, 'Google Plus', '', 'fa fa-google-plus'),
(5, 'Pinterest', '', 'fa fa-pinterest'),
(6, 'YouTube', 'https://www.youtube.com/#', 'fa fa-youtube'),
(7, 'Instagram', 'https://www.instagram.com/#', 'fa fa-instagram'),
(8, 'Tumblr', '', 'fa fa-tumblr'),
(9, 'Flickr', '', 'fa fa-flickr'),
(10, 'Reddit', '', 'fa fa-reddit'),
(11, 'Snapchat', '', 'fa fa-snapchat'),
(12, 'WhatsApp', 'https://www.whatsapp.com/#', 'fa fa-whatsapp'),
(13, 'Quora', '', 'fa fa-quora'),
(14, 'StumbleUpon', '', 'fa fa-stumbleupon'),
(15, 'Delicious', '', 'fa fa-delicious'),
(16, 'Digg', '', 'fa fa-digg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscriber`
--

CREATE TABLE `tbl_subscriber` (
  `subs_id` int(11) NOT NULL,
  `subs_email` varchar(255) NOT NULL,
  `subs_date` varchar(100) NOT NULL,
  `subs_date_time` varchar(100) NOT NULL,
  `subs_hash` varchar(255) NOT NULL,
  `subs_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_top_category`
--

CREATE TABLE `tbl_top_category` (
  `tcat_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `tcat_name` varchar(255) NOT NULL,
  `show_on_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_top_category`
--

INSERT INTO `tbl_top_category` (`tcat_id`, `seller_id`, `tcat_name`, `show_on_menu`) VALUES
(1, 36, 'Fresh Produce', 1),
(2, 36, 'Fresh Meat & Seafood', 1),
(3, 24, 'Fresh Meat & Seafood', 1),
(4, 3, 'Fresh Produce', 1),
(5, 3, 'Fresh Meat & Seafood', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `role` varchar(30) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Active',
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `reset_code` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `full_name`, `email`, `phone`, `password`, `photo`, `role`, `status`, `reset_token`, `token_expiry`, `reset_code`) VALUES
(1, 'Erick Matte', 'admin@gmail.com', '099999878', '0192023a7bbd73250516f069df18b500', 'user-11.jpeg', 'Admin', 'Active', NULL, NULL, NULL),
(2, 'Ivan', 'rider1@gmail.com', '098192732', '4297f44b13955235245b2497399d7a93', NULL, 'Rider', 'Active', NULL, NULL, NULL),
(3, 'asdasdas', 'seller1@gmail.com', '0891723861', 'a8f5f167f44f4964e6c998dee827110c', 'user-user.png', 'Seller', 'Verified', NULL, NULL, NULL),
(4, 'Adsad asdasdsa', 'customer1@gmail.com', '09218759834', '4297f44b13955235245b2497399d7a93', NULL, 'customer', 'Active', NULL, NULL, NULL),
(5, 'Example Customer', 'example@gmail.com', '0978293452', '4297f44b13955235245b2497399d7a93', '', 'customer', 'Active', 'f5bc57741cd10657bb545124a304d7e5486bb70e0369ef6891b627641af3cc350d03f2ee21bd905e642118e83ce300e40c38', NULL, NULL),
(6, 'New Rider', 'rider@gmail.com', '09428739472', '4297f44b13955235245b2497399d7a93', NULL, 'Rider', 'Active', NULL, NULL, NULL),
(12, 'Ablanida Ivan ', 'ejthecoder@gmail.com', '09957939703', '9b05bca7461a14ab81a1b6b73f59845e', NULL, 'customer', 'Active', '1954dfa43d10bfcc62e2cdce8d1545e05811959846e8bd8734e361d9548bd10b6eb5e3db33d253fab56d9020d193a47a6055', '2025-01-27 07:36:40', NULL),
(13, 'Ivan ablanida', 'ejthecoders@gmail.com', '09957939703', '4297f44b13955235245b2497399d7a93', 'user-image 7.png', 'Seller', 'Verified', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_video`
--

CREATE TABLE `tbl_video` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `iframe_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_video`
--

INSERT INTO `tbl_video` (`id`, `title`, `iframe_code`) VALUES
(1, 'Video 1', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/L3XAFSMdVWU\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>'),
(2, 'Video 2', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/sinQ06YzbJI\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>'),
(4, 'Video 3', '<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/ViZNgU-Yt-Y\" frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_billing_address`
--
ALTER TABLE `tbl_billing_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_color`
--
ALTER TABLE `tbl_color`
  ADD PRIMARY KEY (`color_id`);

--
-- Indexes for table `tbl_country`
--
ALTER TABLE `tbl_country`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`cust_id`),
  ADD UNIQUE KEY `cust_email_UNIQUE` (`cust_email`);

--
-- Indexes for table `tbl_customer_message`
--
ALTER TABLE `tbl_customer_message`
  ADD PRIMARY KEY (`customer_message_id`);

--
-- Indexes for table `tbl_delivery_fee`
--
ALTER TABLE `tbl_delivery_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_end_category`
--
ALTER TABLE `tbl_end_category`
  ADD PRIMARY KEY (`ecat_id`);

--
-- Indexes for table `tbl_faq`
--
ALTER TABLE `tbl_faq`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_language`
--
ALTER TABLE `tbl_language`
  ADD PRIMARY KEY (`lang_id`);

--
-- Indexes for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_mid_category`
--
ALTER TABLE `tbl_mid_category`
  ADD PRIMARY KEY (`mcat_id`);

--
-- Indexes for table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_page`
--
ALTER TABLE `tbl_page`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_photo`
--
ALTER TABLE `tbl_photo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post`
--
ALTER TABLE `tbl_post`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `tbl_product_category`
--
ALTER TABLE `tbl_product_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_color`
--
ALTER TABLE `tbl_product_color`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product_photo`
--
ALTER TABLE `tbl_product_photo`
  ADD PRIMARY KEY (`pp_id`);

--
-- Indexes for table `tbl_product_size`
--
ALTER TABLE `tbl_product_size`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_purchase_item`
--
ALTER TABLE `tbl_purchase_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_purchase_payment`
--
ALTER TABLE `tbl_purchase_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  ADD PRIMARY KEY (`rt_id`);

--
-- Indexes for table `tbl_realtime_message`
--
ALTER TABLE `tbl_realtime_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_recipe`
--
ALTER TABLE `tbl_recipe`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `tbl_recipe_product`
--
ALTER TABLE `tbl_recipe_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_rider`
--
ALTER TABLE `tbl_rider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_seller`
--
ALTER TABLE `tbl_seller`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_service`
--
ALTER TABLE `tbl_service`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_service_fee`
--
ALTER TABLE `tbl_service_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_service_fee_all`
--
ALTER TABLE `tbl_service_fee_all`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shipping_address`
--
ALTER TABLE `tbl_shipping_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_shipping_cost`
--
ALTER TABLE `tbl_shipping_cost`
  ADD PRIMARY KEY (`shipping_cost_id`);

--
-- Indexes for table `tbl_shipping_cost_all`
--
ALTER TABLE `tbl_shipping_cost_all`
  ADD PRIMARY KEY (`sca_id`);

--
-- Indexes for table `tbl_size`
--
ALTER TABLE `tbl_size`
  ADD PRIMARY KEY (`size_id`);

--
-- Indexes for table `tbl_slider`
--
ALTER TABLE `tbl_slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_smtp_settings`
--
ALTER TABLE `tbl_smtp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_social`
--
ALTER TABLE `tbl_social`
  ADD PRIMARY KEY (`social_id`);

--
-- Indexes for table `tbl_subscriber`
--
ALTER TABLE `tbl_subscriber`
  ADD PRIMARY KEY (`subs_id`);

--
-- Indexes for table `tbl_top_category`
--
ALTER TABLE `tbl_top_category`
  ADD PRIMARY KEY (`tcat_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_video`
--
ALTER TABLE `tbl_video`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_billing_address`
--
ALTER TABLE `tbl_billing_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_color`
--
ALTER TABLE `tbl_color`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `tbl_country`
--
ALTER TABLE `tbl_country`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_customer_message`
--
ALTER TABLE `tbl_customer_message`
  MODIFY `customer_message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_delivery_fee`
--
ALTER TABLE `tbl_delivery_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_end_category`
--
ALTER TABLE `tbl_end_category`
  MODIFY `ecat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_faq`
--
ALTER TABLE `tbl_faq`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_inventory`
--
ALTER TABLE `tbl_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `tbl_language`
--
ALTER TABLE `tbl_language`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_mid_category`
--
ALTER TABLE `tbl_mid_category`
  MODIFY `mcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_page`
--
ALTER TABLE `tbl_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_payment`
--
ALTER TABLE `tbl_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tbl_photo`
--
ALTER TABLE `tbl_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_post`
--
ALTER TABLE `tbl_post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_product_category`
--
ALTER TABLE `tbl_product_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tbl_product_color`
--
ALTER TABLE `tbl_product_color`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_product_photo`
--
ALTER TABLE `tbl_product_photo`
  MODIFY `pp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_product_size`
--
ALTER TABLE `tbl_product_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_purchase_item`
--
ALTER TABLE `tbl_purchase_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tbl_purchase_order`
--
ALTER TABLE `tbl_purchase_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_purchase_payment`
--
ALTER TABLE `tbl_purchase_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_rating`
--
ALTER TABLE `tbl_rating`
  MODIFY `rt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_realtime_message`
--
ALTER TABLE `tbl_realtime_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_recipe`
--
ALTER TABLE `tbl_recipe`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_recipe_product`
--
ALTER TABLE `tbl_recipe_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_rider`
--
ALTER TABLE `tbl_rider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_seller`
--
ALTER TABLE `tbl_seller`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_service`
--
ALTER TABLE `tbl_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_service_fee`
--
ALTER TABLE `tbl_service_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_service_fee_all`
--
ALTER TABLE `tbl_service_fee_all`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_shipping_address`
--
ALTER TABLE `tbl_shipping_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_shipping_cost`
--
ALTER TABLE `tbl_shipping_cost`
  MODIFY `shipping_cost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_shipping_cost_all`
--
ALTER TABLE `tbl_shipping_cost_all`
  MODIFY `sca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_size`
--
ALTER TABLE `tbl_size`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_slider`
--
ALTER TABLE `tbl_slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_smtp_settings`
--
ALTER TABLE `tbl_smtp_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_social`
--
ALTER TABLE `tbl_social`
  MODIFY `social_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_subscriber`
--
ALTER TABLE `tbl_subscriber`
  MODIFY `subs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_top_category`
--
ALTER TABLE `tbl_top_category`
  MODIFY `tcat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_video`
--
ALTER TABLE `tbl_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
