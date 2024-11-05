-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2024 at 11:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `essduh_bns_member`
--

-- --------------------------------------------------------

--
-- Table structure for table `beach_content`
--

CREATE TABLE `beach_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beach_content`
--

INSERT INTO `beach_content` (`id`, `title`, `description`, `image_path`) VALUES
(13, 'ความงดงามของธรรมชาติ', 'บรรยากาศที่สงบเงียบ เหมาะสำหรับการพักผ่อนกับครอบครัว หรือเพื่อนฝูง', 'uploads/ข้อมูลหาดทรายน้อย1.jpg'),
(14, 'กิจกรรมหลากหลาย', 'เพลิดเพลินกับกิจกรรมต่างๆ ไม่ว่าจะเป็นการว่ายน้ำ เดินเล่นริมหาด หรือถ่ายภาพสวยๆ', 'uploads/ข้อมูลหาดทรายน้อย5.png'),
(16, ' ภาพวิวหาดทรายน้อยในยามเช้า', 'สัมผัสความเงียบสงบและงดงามของหาดทรายน้อย เขาเต่า ในยามเช้า', 'uploads/ข้อมูลหาดทรายน้อย7.png'),
(20, 'กิจกรรมที่หาดทรายน้อย', 'นอกจากการพักผ่อนบนหาดทรายขาวและน้ำทะเลใสแล้ว หาดทรายน้อยยังมีการเดินป่าใกล้เคียงที่เปิดให้คุณได้ชมธรรมชาติที่งดงาม', 'uploads/ข้อมูลหาดทรายน้อย8.png');

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `business_id` int(11) NOT NULL,
  `business_name` varchar(100) NOT NULL,
  `business_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `contact_info` varchar(50) DEFAULT NULL,
  `website` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,6) NOT NULL,
  `longitude` decimal(10,6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`business_id`, `business_name`, `business_type_id`, `user_id`, `description`, `contact_info`, `website`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(1, 'Badstrand', 26, 6, 'ร้านอาหารตามสั่งทั่วไป', 'ตำบล ปากน้ำปราณ อำเภอหัวหิน ประจวบคีรีขันธ์ 77220', NULL, 12.452869, 99.981666, '2024-11-03 06:21:24', '2024-11-03 06:38:57'),
(4, 'Jim\'s Restaurant', 26, 6, 'ร้านขายอาหารทะเลสด', '0833112070', NULL, 12.452487, 99.981843, '2024-11-03 06:33:27', '2024-11-03 06:38:48'),
(7, 'Boonsong Restaurant', 27, 6, 'วันอาทิตย์\r\n9:00–18:00\r\nวันจันทร์\r\n9:00–18:00\r\nวันอังคาร\r\n9:00–18:00\r\nวันพุธ\r\n11:00–18:00\r\nวันพฤหัสบดี\r\n9:00–18:00\r\nวันศุกร์\r\n9:00–18:00\r\nวันเสาร์\r\n9:00–18:00', '0898088302', NULL, 12.453168, 99.981537, '2024-11-03 06:41:51', '2024-11-03 06:41:51'),
(8, 'RECOMMENDED MENU', 27, 6, 'ร้านอาหาร', '69/19 Soi Moo Baan Kao Tao, Nongkae ตำบล ปากน้ำปรา', NULL, 12.453264, 99.981554, '2024-11-03 06:42:55', '2024-11-03 06:42:55'),
(9, 'Sanae Beach Huahin', 26, 6, 'ห้องพักริบทะเล', '0960372757', NULL, 12.453098, 99.981380, '2024-11-03 06:44:26', '2024-11-03 06:44:26'),
(10, 'T Villas Huahin', 26, 6, 'โรงแรมอันเงียบสงบแห่งนี้ตั้งอยู่ในพื้นที่เขียวชอุ่มที่ขนาบข้างด้วยต้นปาล์มจำนวนมาก อยู่ห่างจากชายหาดที่ใกล้ที่สุดของอ่าวไทยโดยใช้เวลาเดินเพียง 1 นาที นอกจากนี้ยังอยู่ห่างจากสถานีรถไฟเขาเต่า 4 กม. และห่างจากซิเคด้า มาร์เก็ต 14 กม.', '0861551515', NULL, 12.453083, 99.981062, '2024-11-03 06:45:32', '2024-11-03 06:45:32'),
(11, 'บ้านพักตากอากาศ ส่วนบุคคล', 26, 6, 'บ้านเลขที่ 69/1 ถนนทางลงหาดทรายน้อย เขาเต่า, ตำบล หนองแก อำเภอหัวหิน ประจวบคีรีขันธ์ 77110', '0821566696', NULL, 12.453112, 99.980444, '2024-11-03 06:46:35', '2024-11-03 06:46:35'),
(12, 'บ้านเคียงทะเล พูลวิลล่า หัวหิน', 26, 6, 'ตำบล ปากน้ำปราณ อำเภอปราณบุรี ประจวบคีรีขันธ์', '0819281282', NULL, 12.453134, 99.980256, '2024-11-03 06:47:12', '2024-11-03 06:47:12'),
(13, 'โฮม คอฟฟี่', 27, 2, 'ร้านกาแฟ\r\n฿1-100ต่อคน\r\nวันอาทิตย์\r\n9:30–19:00\r\nวันจันทร์\r\n9:30–19:00\r\nวันอังคาร\r\n9:30–19:00\r\nวันพุธ\r\n9:30–19:00\r\nวันพฤหัสบดี\r\n9:30–19:00\r\nวันศุกร์\r\n9:30–19:00\r\nวันเสาร์\r\n9:30–19:00\r\n', 'ตำบล ปากน้ำปราณ อำเภอหัวหิน ประจวบคีรีขันธ์ 77220', NULL, 12.453174, 99.980123, '2024-11-03 06:49:03', '2024-11-03 06:49:03');

-- --------------------------------------------------------

--
-- Table structure for table `business_photos`
--

CREATE TABLE `business_photos` (
  `photo_id` int(11) NOT NULL,
  `business_id` int(11) NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `show_image` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_photos`
--

INSERT INTO `business_photos` (`photo_id`, `business_id`, `photo_url`, `description`, `created_at`, `show_image`) VALUES
(1, 1, '03-11-2024_business_A.jpg', '1', '2024-11-03 08:38:55', 1),
(2, 4, '03-11-2024_business_B.jpg', '1', '2024-11-03 08:40:48', 1),
(3, 7, '03-11-2024_business_C.jpg', '1', '2024-11-03 08:41:18', 1),
(4, 8, '03-11-2024_business_D.jpg', '1', '2024-11-03 08:41:38', 1),
(5, 9, '03-11-2024_business_E.jpg', '1', '2024-11-03 08:41:56', 1),
(6, 10, '03-11-2024_business_F.jpg', '1', '2024-11-03 08:42:13', 1),
(7, 11, '03-11-2024_business_G.jpg', '1', '2024-11-03 08:42:30', 1),
(8, 12, '03-11-2024_business_H.jpg', '1', '2024-11-03 08:43:20', 1),
(9, 13, '03-11-2024_business_I.jpg', '1', '2024-11-03 08:43:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `business_types`
--

CREATE TABLE `business_types` (
  `business_type_id` int(11) NOT NULL,
  `business_type_name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_types`
--

INSERT INTO `business_types` (`business_type_id`, `business_type_name`, `description`) VALUES
(26, 'โรงแรม', 'ธุรกิจโรงแรมและที่พัก'),
(27, 'ร้านอาหาร', 'ธุรกิจร้านอาหาร'),
(28, 'ตัวแทนท่องเที่ยว', 'ธุรกิจตัวแทนท่องเที่ยว'),
(29, 'การท่องเที่ยว', 'ธุรกิจการท่องเที่ยว'),
(30, 'บริการจัดเลี้ยง', 'ธุรกิจจัดเลี้ยงและบริการอาหาร'),
(31, 'รีสอร์ท', 'ธุรกิจรีสอร์ทและสถานที่พักผ่อน'),
(32, 'สปาและสุขภาพ', 'ธุรกิจสปาและสุขภาพ'),
(33, 'การจัดการอีเวนท์', 'ธุรกิจจัดการอีเวนท์และงานเลี้ยง'),
(34, 'สถานบันเทิงกลางคืน', 'ธุรกิจสถานบันเทิงกลางคืน'),
(35, 'โรงภาพยนตร์', 'ธุรกิจโรงภาพยนตร์และบันเทิง'),
(36, 'สวนสนุก', 'ธุรกิจสวนสนุกและสถานที่บันเทิง');

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `link` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `card_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `image`, `title`, `text`, `link`, `user_id`, `card_number`) VALUES
(22, 'uploads/3.png', 'ที่มาของโครงการ', 'ที่ยอดเยี่ยมจากโครงการนี้! มาร่วมเป็นส่วนหนึ่งของประสบการณ์ที่น่าทึ่งนี้กันเถอะ!', '/beach/ที่มาโครงการ.php', 6, 6),
(23, 'uploads/2.png', 'ข้อมูลหาดทรายน้อย', 'หาดทรายน้อย: สถานที่ที่เหมาะสำหรับการพักผ่อนและเพลิดเพลินกับความงามของธรรมชาติ ค้นหาสิ่งที่ทำให้หาดนี้พิเศษ!', '/beach/ข้อมูลหาดทรายน้อย.php', 6, 5),
(24, 'uploads/1.png', 'ภาพบรรยากาศโครงการ', 'มาสัมผัสความสวยงามและความมีชีวิตชีวาของโครงการนี้ที่สร้างบรรยากาศที่น่าจดจำและสนุกสนาน!', '/beach/ภาพบรรยากาศโครงการ.php', 6, 4),
(26, 'uploads/100.png', 'โครงการหาดสะอาดด้วยมือเรา', 'ทำให้หาดทรายของเราสะอาดและสวยงามอีกครั้ง!', '0', 6, 1),
(27, 'uploads/200.png', 'ร่วมมือกันเพื่ออนาคตที่ดีกว่า', 'มาร่วมทำให้หาดของเราสะอาดขึ้นกันเถอะ!', '', 6, 2),
(28, 'uploads/300.png', 'สร้างสรรค์สังคมที่น่าอยู่', 'ทุกคนมีส่วนร่วมในการทำให้ที่นี่ดีขึ้น', '', 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cleanup_activities`
--

CREATE TABLE `cleanup_activities` (
  `activity_id` int(11) NOT NULL,
  `activity_date` date NOT NULL,
  `location` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `total_waste` int(11) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `waste_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cleanup_activities`
--

INSERT INTO `cleanup_activities` (`activity_id`, `activity_date`, `location`, `description`, `total_waste`, `latitude`, `longitude`, `waste_type_id`, `user_id`) VALUES
(28, '2024-10-14', 'หาดทรายน้อย', 'เก็บขยะ', 3, 12.4546710, 99.981575, 1, 6),
(30, '2024-10-16', 'หาดทรายน้อย1', 'เก็บขยะ', 2, 12.4550600, 99.981845, 1, 6),
(31, '2024-10-14', 'หาดทรายน้อย2', 'เก็บขยะ', 3, 12.4542700, 99.981574, 1, 6),
(33, '2024-10-14', 'หาดทรายน้อย3', 'เก็บขยะ', 3, 12.4540560, 99.981548, 1, 6),
(34, '2024-10-16', 'หาดทรายน้อย4', 'เก็บขยะ', 6, 12.4537660, 99.981600, 1, 6),
(35, '2024-10-16', 'หาดทรายน้อย5', 'เก็บขยะ', 6, 12.4534830, 99.981613, 1, 6),
(36, '2024-10-14', 'หาดทรายน้อย6', 'เก็บขยะ', 11, 12.4538930, 99.981686, 1, 6),
(37, '2024-10-14', 'หาดทรายน้อย7', 'เก็บขยะ', 7, 12.4530070, 99.981785, 1, 6),
(38, '2024-10-14', 'หาดทรายน้อย8', 'เก็บขยะ', 2, 12.4521910, 99.981921, 1, 6),
(39, '2024-10-16', 'หาดทรายน้อย9', 'เก็บขยะ', 1, 12.4524040, 99.981749, 1, 6),
(40, '2024-10-16', 'หาดทรายน้อย10', 'เก็บขยะ', 1, 12.4524040, 99.981749, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `cleanup_photos`
--

CREATE TABLE `cleanup_photos` (
  `photo_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `show_image` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cleanup_photos`
--

INSERT INTO `cleanup_photos` (`photo_id`, `activity_id`, `photo_url`, `description`, `show_image`) VALUES
(20, 28, '14-10-2024_cleanup_A.png', 'ทีมงานเก็บขยะ', 1),
(21, 30, '16-10-2024_cleanup_A.png', 'ทีมงานเก็บขยะ', 1),
(22, 31, '14-10-2024_cleanup_B.png', 'ทีมงานเก็บขยะ', 1),
(23, 33, '14-10-2024_cleanup_C.png', 'ทีมงานเก็บขยะ', 1),
(24, 34, '16-10-2024_cleanup_B.png', 'ทีมงานเก็บขยะ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `role_id`, `created_at`, `reset_token`, `reset_token_expires`) VALUES
(2, 'user', 'user@example.com', '1', NULL, NULL, 2, '2024-11-02 09:45:37', NULL, NULL),
(6, 'Admin_ex', 'pushilkun@gmail.com', '1', 'วงศธร', 'ฉาบสีทอง', 1, '2024-11-02 10:39:28', NULL, NULL),
(19, 'D001', 'user1@example.com', '1', '1', '1', 1, '2024-11-03 07:09:04', NULL, NULL),
(20, 'D002', 'user3@example.com', '3', '3', '3', 2, '2024-11-03 07:09:15', NULL, NULL),
(21, 'D003', 'user2@example.com', '1', '2', '2', 1, '2024-11-03 07:12:13', NULL, NULL),
(22, 'D004', 'user4@example.com', '1', 'วงศธร', 'ฉาบสีทอง', NULL, '2024-11-04 09:25:03', NULL, NULL),
(25, '2', '1@3.com', '1', '2', '2', NULL, '2024-11-04 09:31:55', NULL, NULL),
(26, '3', '4@3.com', '1', '3', '3', 1, '2024-11-04 09:33:42', NULL, NULL),
(28, '4', '4@5.com', '4', '4', '4', 1, '2024-11-04 09:35:29', NULL, NULL),
(29, '9', '9@9.com', '9', '9', '9', 1, '2024-11-04 09:38:02', NULL, NULL),
(30, '10', '10@10.com', '10', '10', '10', 1, '2024-11-04 09:42:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `waste_types`
--

CREATE TABLE `waste_types` (
  `waste_id` int(11) NOT NULL,
  `waste_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waste_types`
--

INSERT INTO `waste_types` (`waste_id`, `waste_type`) VALUES
(1, 'พลาสติก'),
(2, 'โลหะ'),
(3, 'กระดาษ'),
(4, 'แก้ว'),
(5, 'อาหาร'),
(6, 'อิฐ'),
(7, 'ไม้'),
(8, 'ผ้า'),
(9, 'อิเล็กทรอนิกส์'),
(10, 'ยาง'),
(11, 'เศษวัสดุก่อสร้าง'),
(12, 'โฟม'),
(13, 'น้ำมัน'),
(14, 'เคมีภัณฑ์'),
(15, 'อื่นๆ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beach_content`
--
ALTER TABLE `beach_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`business_id`),
  ADD UNIQUE KEY `website` (`website`),
  ADD KEY `business_type_id` (`business_type_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `business_photos`
--
ALTER TABLE `business_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD UNIQUE KEY `photo_url` (`photo_url`),
  ADD KEY `business_id` (`business_id`);

--
-- Indexes for table `business_types`
--
ALTER TABLE `business_types`
  ADD PRIMARY KEY (`business_type_id`),
  ADD UNIQUE KEY `business_type_name` (`business_type_name`);

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `card_number` (`card_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cleanup_activities`
--
ALTER TABLE `cleanup_activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `waste_type_id` (`waste_type_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cleanup_photos`
--
ALTER TABLE `cleanup_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `activity_id` (`activity_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `waste_types`
--
ALTER TABLE `waste_types`
  ADD PRIMARY KEY (`waste_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beach_content`
--
ALTER TABLE `beach_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `business_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `business_photos`
--
ALTER TABLE `business_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `business_types`
--
ALTER TABLE `business_types`
  MODIFY `business_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `cleanup_activities`
--
ALTER TABLE `cleanup_activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `cleanup_photos`
--
ALTER TABLE `cleanup_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `waste_types`
--
ALTER TABLE `waste_types`
  MODIFY `waste_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `businesses`
--
ALTER TABLE `businesses`
  ADD CONSTRAINT `businesses_ibfk_1` FOREIGN KEY (`business_type_id`) REFERENCES `business_types` (`business_type_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `businesses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `business_photos`
--
ALTER TABLE `business_photos`
  ADD CONSTRAINT `business_photos_ibfk_1` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`business_id`) ON DELETE CASCADE;

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `cleanup_activities`
--
ALTER TABLE `cleanup_activities`
  ADD CONSTRAINT `cleanup_activities_ibfk_1` FOREIGN KEY (`waste_type_id`) REFERENCES `waste_types` (`waste_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cleanup_activities_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `cleanup_photos`
--
ALTER TABLE `cleanup_photos`
  ADD CONSTRAINT `cleanup_photos_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `cleanup_activities` (`activity_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
