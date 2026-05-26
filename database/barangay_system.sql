-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 26, 2026 at 02:37 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `barangay_system`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `sp_create_service_request`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_create_service_request` (IN `p_household_id` INT, IN `p_service_id` INT, IN `p_purpose` TEXT, IN `p_delivery_method` VARCHAR(20), IN `p_payment_method` VARCHAR(20), OUT `p_request_id` INT, OUT `p_message` VARCHAR(255))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_message = 'Failed to create service request';
        SET p_request_id = 0;
    END;
    
    START TRANSACTION;
    
    -- Insert service request
    INSERT INTO service_request (household_id, service_id, ref_no, purpose, delivery_method, status)
    VALUES (p_household_id, p_service_id, CONCAT('BRG-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', FLOOR(RAND() * 9000 + 1000)), 
            p_purpose, p_delivery_method, 'pending');
    
    SET p_request_id = LAST_INSERT_ID();
    
    -- Insert payment record
    INSERT INTO payment (request_id, total_amount, payment_method, ref_no, is_paid)
    SELECT p_request_id, s.base_price, p_payment_method, CONCAT('PAY-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', FLOOR(RAND() * 9000 + 1000)), 0
    FROM service s WHERE s.service_id = p_service_id;
    
    COMMIT;
    SET p_message = 'Service request created successfully';
END$$

DROP PROCEDURE IF EXISTS `sp_get_household_profile`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_get_household_profile` (IN `p_household_id` INT)   BEGIN
    -- Basic household info
    SELECT * FROM household WHERE household_id = p_household_id;
    
    -- All residents in household
    SELECT * FROM resident WHERE household_id = p_household_id;
    
    -- Recent service requests (last 5)
    SELECT sr.*, s.service_name, p.is_paid, p.total_amount
    FROM service_request sr
    JOIN service s ON sr.service_id = s.service_id
    LEFT JOIN payment p ON sr.request_id = p.request_id
    WHERE sr.household_id = p_household_id
    ORDER BY sr.date_submitted DESC
    LIMIT 5;
    
    -- Recent complaints (last 5)
    SELECT * FROM complaint
    WHERE household_id = p_household_id
    ORDER BY date_submitted DESC
    LIMIT 5;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','staff') COLLATE utf8mb4_unicode_ci DEFAULT 'staff',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', '2026-05-12 18:46:11');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

DROP TABLE IF EXISTS `announcement`;
CREATE TABLE IF NOT EXISTS `announcement` (
  `announcement_id` int NOT NULL AUTO_INCREMENT,
  `admin_id` int NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_audience` enum('all','phase1','phase2','phase3') COLLATE utf8mb4_unicode_ci DEFAULT 'all',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`announcement_id`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `announcement_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`announcement_id`),
  KEY `created_by` (`created_by`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `content`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Community Clean-Up', 'Join us for a clean-up initiative in San Francisco this Saturday at 6:00 AM. Please bring your own gloves and trash bags. Meeting point at the barangay hall.', 1, '2026-05-15 02:00:00', '2026-05-26 01:13:33'),
(2, 'Medical Mission', 'Free consultation and medicines available at the barangay hall. Services include blood pressure check, blood sugar test, and dental checkup. First come, first served.', 1, '2026-05-20 02:00:00', '2026-05-26 01:13:33'),
(3, 'Barangay Assembly', 'Everyone is invited to the assembly at 2:00 PM at the barangay covered court. Agenda includes upcoming projects, budget discussion, and community concerns.', 1, '2026-05-25 02:00:00', '2026-05-26 01:13:33'),
(4, 'test', 'test', 1, '2026-05-26 01:31:34', '2026-05-26 01:31:34'),
(5, '1', '1', 1, '2026-05-26 01:33:52', '2026-05-26 01:33:52'),
(6, 'fkc y', '11', 1, '2026-05-26 02:27:44', '2026-05-26 02:27:44');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
CREATE TABLE IF NOT EXISTS `campaigns` (
  `campaign_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `type` enum('voters','assistance','scholarship','medical','permit','other') COLLATE utf8mb4_unicode_ci DEFAULT 'other',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`campaign_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`campaign_id`, `title`, `description`, `start_date`, `end_date`, `type`, `is_active`, `created_at`) VALUES
(1, 'Voters Registration 2026', 'Register as a new voter for the upcoming elections. Bring valid ID and proof of residency.', '2026-05-01 08:00:00', '2026-05-30 17:00:00', 'voters', 1, '2026-05-13 13:01:02');

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

DROP TABLE IF EXISTS `complaint`;
CREATE TABLE IF NOT EXISTS `complaint` (
  `complaint_id` int NOT NULL AUTO_INCREMENT,
  `household_id` int NOT NULL,
  `ref_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('noise','waste','peace_order','infrastructure','other') COLLATE utf8mb4_unicode_ci DEFAULT 'other',
  `date_submitted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','reviewing','resolved','dismissed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `priority` enum('low','medium','high') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `admin_response` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`complaint_id`),
  UNIQUE KEY `ref_no` (`ref_no`),
  KEY `household_id` (`household_id`),
  KEY `idx_complaint_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`complaint_id`, `household_id`, `ref_no`, `subject`, `description`, `category`, `date_submitted`, `status`, `priority`, `admin_response`) VALUES
(1, 1, 'CMP-20260516-1111', 'may namimiss ako', 'miss ko na siya', 'peace_order', '2026-05-16 18:09:02', 'resolved', 'high', 'ako din'),
(2, 1, 'CMP-20260516-5282', 'test', 'test', 'peace_order', '2026-05-16 18:12:00', 'pending', 'medium', NULL),
(3, 1, 'CMP-20260516-4599', 'test', 'test', 'infrastructure', '2026-05-16 18:12:18', 'pending', 'medium', NULL),
(4, 1, 'CMP-20260516-8570', 'test', 'test', 'infrastructure', '2026-05-16 18:16:21', 'pending', 'medium', NULL),
(5, 1, 'CMP-20260516-1320', 'test', 'test', 'infrastructure', '2026-05-16 18:19:23', 'resolved', 'medium', '');

-- --------------------------------------------------------

--
-- Table structure for table `household`
--

DROP TABLE IF EXISTS `household`;
CREATE TABLE IF NOT EXISTS `household` (
  `household_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phase_no` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_language` enum('english','tagalog') COLLATE utf8mb4_unicode_ci DEFAULT 'tagalog',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`household_id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_household_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household`
--

INSERT INTO `household` (`household_id`, `email`, `password`, `address`, `phase_no`, `preferred_language`, `created_at`) VALUES
(1, 'maria.santos@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 5, Lot 12, Phase 1', 'Phase 1', '', '2026-05-12 18:46:11'),
(3, 'test@gmail.com', '$2y$10$9AKRjTUHHzkpfeewtGwulOcTGAbhw8mXLkBs37l4R6G9.akDboHIO', 'Blk 5, Lot 12, Phase 1', 'Phase 2', 'tagalog', '2026-05-25 23:45:06');

-- --------------------------------------------------------

--
-- Table structure for table `localization`
--

DROP TABLE IF EXISTS `localization`;
CREATE TABLE IF NOT EXISTS `localization` (
  `localization_id` int NOT NULL AUTO_INCREMENT,
  `household_id` int NOT NULL,
  `language` enum('english','tagalog') COLLATE utf8mb4_unicode_ci DEFAULT 'tagalog',
  `date_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`localization_id`),
  UNIQUE KEY `unique_household_lang` (`household_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `request_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','gcash','bank_transfer') COLLATE utf8mb4_unicode_ci DEFAULT 'cash',
  `ref_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT '0',
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `ref_no` (`ref_no`),
  KEY `request_id` (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `request_id`, `total_amount`, `payment_method`, `ref_no`, `is_paid`, `paid_at`) VALUES
(1, 1, 50.00, 'cash', 'PAY-20260516-8865', 0, NULL),
(2, 2, 25.00, 'cash', 'PAY-20260516-1478', 0, NULL),
(3, 3, 0.00, 'cash', 'PAY-20260516-1707', 0, NULL),
(4, 4, 0.00, 'cash', 'PAY-20260516-3133', 0, NULL),
(5, 5, 0.00, 'cash', 'PAY-20260516-1051', 0, NULL),
(6, 6, 0.00, 'cash', 'PAY-20260516-2211', 0, NULL),
(7, 7, 0.00, 'cash', 'PAY-20260516-3487', 0, NULL),
(8, 8, 50.00, 'cash', 'PAY-20260516-2359', 0, NULL),
(9, 9, 50.00, 'cash', 'PAY-20260516-7316', 0, NULL),
(10, 10, 50.00, 'cash', 'PAY-20260516-3251', 0, NULL),
(11, 11, 50.00, 'cash', 'PAY-20260516-8698', 0, NULL),
(12, 12, 99999999.99, 'cash', 'PAY-20260516-5594', 0, NULL),
(13, 13, 50.00, 'cash', 'PAY-20260516-3315', 0, NULL),
(14, 14, 50.00, 'cash', 'PAY-20260516-6736', 0, NULL),
(15, 15, 50.00, 'cash', 'PAY-20260516-5424', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `resident`
--

DROP TABLE IF EXISTS `resident`;
CREATE TABLE IF NOT EXISTS `resident` (
  `resident_id` int NOT NULL AUTO_INCREMENT,
  `household_id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `suffix` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `age` int DEFAULT NULL,
  `is_voter` tinyint(1) DEFAULT '0',
  `is_head` tinyint(1) DEFAULT '0',
  `relationship_to_head` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_no` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`resident_id`),
  KEY `household_id` (`household_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resident`
--

INSERT INTO `resident` (`resident_id`, `household_id`, `first_name`, `last_name`, `suffix`, `age`, `is_voter`, `is_head`, `relationship_to_head`, `contact_no`, `profile_photo_url`) VALUES
(1, 1, 'Maria', 'Santos', '', 42, 1, 1, 'Head', '9171234567', 'maria.santos@brgy.com'),
(3, 3, 'test', 'test', '', 32, 1, 1, 'Head', '9171234567', 'test.test@brgy.com'),
(7, 1, 'james', 'james', '', 3, 1, 0, 'Parent', '0', 'james.james@brgy.com');

--
-- Triggers `resident`
--
DROP TRIGGER IF EXISTS `trigger_generate_resident_email`;
DELIMITER $$
CREATE TRIGGER `trigger_generate_resident_email` BEFORE INSERT ON `resident` FOR EACH ROW BEGIN
    DECLARE auto_email VARCHAR(100);
    IF NEW.profile_photo_url IS NULL THEN
        SET auto_email = CONCAT(LOWER(NEW.first_name), '.', LOWER(NEW.last_name), '@brgy.com');
        SET NEW.profile_photo_url = auto_email;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
CREATE TABLE IF NOT EXISTS `service` (
  `service_id` int NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `service_name`, `base_price`, `is_active`) VALUES
(1, 'Barangay Clearance', 50.00, 1),
(2, 'Barangay ID', 25.00, 1),
(3, 'Building Permit', 500.00, 1),
(4, 'Business Permit', 200.00, 1),
(5, 'Cedula (Community Tax)', 15.00, 1),
(6, 'Certificate of Good Moral', 40.00, 1),
(7, 'Certificate of Residency', 30.00, 1),
(8, 'First Time Job Seeker', 0.00, 1),
(9, 'Health Certificate', 100.00, 1),
(10, 'Indigency Certificate', 0.00, 1),
(11, 'Police Clearance', 50.00, 1),
(12, 'Travel Pass', 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `service_request`
--

DROP TABLE IF EXISTS `service_request`;
CREATE TABLE IF NOT EXISTS `service_request` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `household_id` int NOT NULL,
  `service_id` int NOT NULL,
  `ref_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` text COLLATE utf8mb4_unicode_ci,
  `date_submitted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','approved','processing','completed','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `delivery_method` enum('pickup','delivery') COLLATE utf8mb4_unicode_ci DEFAULT 'pickup',
  PRIMARY KEY (`request_id`),
  UNIQUE KEY `ref_no` (`ref_no`),
  KEY `service_id` (`service_id`),
  KEY `idx_request_household` (`household_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_request`
--

INSERT INTO `service_request` (`request_id`, `household_id`, `service_id`, `ref_no`, `purpose`, `date_submitted`, `status`, `delivery_method`) VALUES
(1, 1, 1, 'BRG-20260516-4989', '', '2026-05-16 17:26:20', 'pending', 'pickup'),
(2, 1, 2, 'BRG-20260516-5191', '', '2026-05-16 17:26:54', 'pending', 'delivery'),
(3, 1, 8, 'BRG-20260516-2475', '', '2026-05-16 17:31:21', 'pending', 'delivery'),
(4, 1, 8, 'BRG-20260516-1920', '', '2026-05-16 17:31:29', 'pending', 'delivery'),
(5, 1, 8, 'BRG-20260516-9750', '', '2026-05-16 17:33:26', 'pending', 'pickup'),
(6, 1, 8, 'BRG-20260516-5935', '', '2026-05-16 17:37:43', 'pending', 'pickup'),
(7, 1, 8, 'BRG-20260516-6144', '', '2026-05-16 17:37:54', 'pending', 'pickup'),
(8, 1, 1, 'BRG-20260516-1953', '', '2026-05-16 17:38:02', 'pending', 'pickup'),
(9, 1, 1, 'BRG-20260516-4119', '', '2026-05-16 17:38:29', 'pending', 'pickup'),
(10, 1, 1, 'BRG-20260516-7754', '', '2026-05-16 17:38:35', 'pending', 'pickup'),
(11, 1, 1, 'BRG-20260516-8311', '', '2026-05-16 17:38:54', 'pending', 'pickup'),
(12, 1, 5, 'BRG-20260516-7999', '', '2026-05-16 18:04:51', 'pending', 'pickup'),
(13, 1, 1, 'BRG-20260516-6669', '', '2026-05-16 18:05:03', 'pending', 'pickup'),
(14, 1, 1, 'BRG-20260516-5878', '', '2026-05-16 18:08:08', 'pending', 'pickup'),
(15, 1, 1, 'BRG-20260516-5755', '', '2026-05-16 18:08:13', 'pending', 'pickup');

--
-- Triggers `service_request`
--
DROP TRIGGER IF EXISTS `trigger_update_payment_on_completion`;
DELIMITER $$
CREATE TRIGGER `trigger_update_payment_on_completion` AFTER UPDATE ON `service_request` FOR EACH ROW BEGIN
    IF NEW.status = 'completed' AND OLD.status != 'completed' THEN
        UPDATE payment 
        SET is_paid = 1, paid_at = NOW()
        WHERE request_id = NEW.request_id AND is_paid = 0;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_admin_dashboard`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_admin_dashboard`;
CREATE TABLE IF NOT EXISTS `vw_admin_dashboard` (
`pending_complaints` decimal(23,0)
,`pending_requests` decimal(23,0)
,`total_complaints` bigint
,`total_households` bigint
,`total_requests` bigint
,`total_residents` bigint
,`total_revenue` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_monthly_requests`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_monthly_requests`;
CREATE TABLE IF NOT EXISTS `vw_monthly_requests` (
`completed` decimal(23,0)
,`month` varchar(7)
,`pending` decimal(23,0)
,`rejected` decimal(23,0)
,`total_requests` bigint
);

-- --------------------------------------------------------

--
-- Structure for view `vw_admin_dashboard`
--
DROP TABLE IF EXISTS `vw_admin_dashboard`;

DROP VIEW IF EXISTS `vw_admin_dashboard`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_admin_dashboard`  AS SELECT count(distinct `h`.`household_id`) AS `total_households`, count(distinct `r`.`resident_id`) AS `total_residents`, count(distinct `sr`.`request_id`) AS `total_requests`, count(distinct `c`.`complaint_id`) AS `total_complaints`, sum((case when (`sr`.`status` = 'pending') then 1 else 0 end)) AS `pending_requests`, sum((case when (`c`.`status` = 'pending') then 1 else 0 end)) AS `pending_complaints`, coalesce(sum(`p`.`total_amount`),0) AS `total_revenue` FROM ((((`household` `h` left join `resident` `r` on((`h`.`household_id` = `r`.`household_id`))) left join `service_request` `sr` on((`h`.`household_id` = `sr`.`household_id`))) left join `complaint` `c` on((`h`.`household_id` = `c`.`household_id`))) left join `payment` `p` on(((`sr`.`request_id` = `p`.`request_id`) and (`p`.`is_paid` = 1)))) ;

-- --------------------------------------------------------

--
-- Structure for view `vw_monthly_requests`
--
DROP TABLE IF EXISTS `vw_monthly_requests`;

DROP VIEW IF EXISTS `vw_monthly_requests`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_monthly_requests`  AS SELECT date_format(`service_request`.`date_submitted`,'%Y-%m') AS `month`, count(0) AS `total_requests`, sum((case when (`service_request`.`status` = 'completed') then 1 else 0 end)) AS `completed`, sum((case when (`service_request`.`status` = 'pending') then 1 else 0 end)) AS `pending`, sum((case when (`service_request`.`status` = 'rejected') then 1 else 0 end)) AS `rejected` FROM `service_request` GROUP BY date_format(`service_request`.`date_submitted`,'%Y-%m') ORDER BY `month` DESC ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
