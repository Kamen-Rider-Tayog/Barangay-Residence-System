-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 26, 2026 at 09:32 PM
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
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `content`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'New Barangay Hall Office Hours', 'Starting January 15, 2024, our office hours will be Monday to Friday, 8:00 AM to 5:00 PM. No noon breaks.', 1, '2024-01-05 01:00:00', '2024-01-05 01:00:00'),
(2, 'Free Medical Mission', 'Free medical checkup and dental services on February 20, 2024 at the barangay covered court. 8 AM - 3 PM.', 1, '2024-02-10 02:30:00', '2024-02-10 02:30:00'),
(3, 'Community Clean-Up Drive', 'Join us for a community cleanup every last Saturday of the month. Meet at barangay hall at 6 AM.', 1, '2024-03-01 00:00:00', '2024-03-01 00:00:00'),
(4, 'Voters Registration Schedule', 'COMELEC will be at barangay hall for voters registration every Tuesday and Thursday, 9 AM to 4 PM.', 1, '2024-06-15 05:30:00', '2024-06-15 05:30:00'),
(5, 'Scholarship Application Open', 'Barangay scholarship for deserving students now open. Deadline: September 30, 2024.', 1, '2024-08-01 01:15:00', '2024-08-01 01:15:00'),
(6, 'Christmas Gift Giving', 'Annual Christmas gift giving for senior citizens and children on December 22, 2024 at 2 PM.', 1, '2024-12-01 03:00:00', '2024-12-01 03:00:00'),
(7, 'Road Repairs Announcement', 'Main road will be closed for repairs from January 10-20, 2025. Please use alternate route.', 1, '2025-01-05 00:30:00', '2025-01-05 00:30:00'),
(8, 'Free Anti-Rabies Vaccination', 'Free anti-rabies vaccination for dogs and cats on March 15, 2025. Register at barangay hall.', 1, '2025-03-01 06:00:00', '2025-03-01 06:00:00'),
(9, 'Barangay Assembly', 'Barangay Assembly on June 30, 2025 at 2 PM. Discuss annual budget and projects.', 1, '2025-06-15 02:00:00', '2025-06-15 02:00:00'),
(10, 'First Time Job Seeker Act', 'Free barangay clearances and police clearances for first time job seekers. Just bring your ID.', 1, '2025-09-01 01:30:00', '2025-09-01 01:30:00'),
(11, 'Holiday Schedule Announcement', 'Barangay hall closed on December 24-25, 2024 and December 31 - January 1, 2025.', 1, '2025-12-15 03:30:00', '2025-12-15 03:30:00'),
(12, 'New Online Service Portal', 'You can now request barangay documents online! Visit our website to learn more.', 1, '2026-01-10 00:00:00', '2026-01-10 00:00:00'),
(13, 'Fire Prevention Month', 'Fire safety seminar on March 5, 2026 at 9 AM. Everyone is encouraged to attend.', 1, '2026-02-20 05:45:00', '2026-02-20 05:45:00'),
(14, 'Flu Vaccination Drive', 'Free flu vaccination for senior citizens on April 15, 2026. Please bring senior citizen ID.', 1, '2026-04-01 02:00:00', '2026-04-01 02:00:00'),
(15, 'Barangay Sports Fest', 'Barangay sports festival on May 30, 2026. Register your team at barangay hall.', 1, '2026-05-15 01:00:00', '2026-05-15 01:00:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=116 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`complaint_id`, `household_id`, `ref_no`, `subject`, `description`, `category`, `date_submitted`, `status`, `priority`, `admin_response`) VALUES
(1, 3, 'CMP-20240315-3001', 'Loud neighbor parties until midnight', 'Neighbor constantly plays loud music until 2 AM. We have young children who need sleep.', 'noise', '2024-03-15 14:30:00', 'resolved', 'high', 'We have talked to the neighbor. They agreed to lower volume after 10 PM.'),
(2, 7, 'CMP-20240620-3002', 'Uncollected garbage for 2 weeks', 'Garbage truck has not visited our street for two weeks. Waste is piling up.', 'waste', '2024-06-20 00:15:00', 'resolved', 'high', 'Scheduled special collection today. Route has been adjusted.'),
(3, 12, 'CMP-20240910-3003', 'Street light not working', 'The street light in front of our house has been broken for a month. Very dark at night.', 'infrastructure', '2024-09-10 11:45:00', 'resolved', 'medium', 'Replaced the bulb yesterday. Light is now working.'),
(4, 15, 'CMP-20241105-3004', 'Neighbor encroaching property', 'Neighbor built a fence that extends into our property line.', 'peace_order', '2024-11-05 02:30:00', 'dismissed', 'medium', 'After investigation, fence is within property line. Both parties advised to settle amicably.'),
(5, 18, 'CMP-20241220-3005', 'Stray dogs roaming the area', 'Stray dogs are becoming aggressive and chasing children.', 'peace_order', '2024-12-20 08:00:00', 'resolved', 'high', 'Animal control has been deployed. Dogs have been relocated to shelter.'),
(6, 5, 'CMP-20250210-3006', 'Flooding during heavy rain', 'Our street floods every time it rains heavily. Drainage system is clogged.', 'infrastructure', '2025-02-10 01:20:00', 'resolved', 'high', 'Drainage system has been cleaned and repaired. Flooding has reduced.'),
(7, 9, 'CMP-20250418-3007', 'Noise from construction site', 'Construction work starts at 5 AM. Too early and disrupts sleep.', 'noise', '2025-04-17 23:00:00', 'reviewing', 'medium', 'We have issued a warning to the contractor. Investigation ongoing.'),
(8, 22, 'CMP-20250625-3008', 'Illegal parking blocking driveway', 'Cars park in front of our driveway daily. We cannot get in and out.', 'peace_order', '2025-06-25 00:45:00', 'resolved', 'high', 'Added no-parking signs. Regular patrols conducted. Issue resolved.'),
(9, 25, 'CMP-20250830-3009', 'Potholes on main road', 'Main road has large potholes that damage vehicles. Dangerous for motorcycles.', 'infrastructure', '2025-08-30 03:15:00', 'pending', 'high', 'Road repair scheduled for next week. Temporary warning signs placed.'),
(10, 28, 'CMP-20251012-3010', 'Unresponsive barangay staff', 'Went to barangay hall 3 times but staff are always on break or not helping.', 'other', '2025-10-12 05:30:00', 'resolved', 'medium', 'Staff retraining conducted. Thank you for your feedback.'),
(11, 10, 'CMP-20251205-3011', 'Loud videoke every night', 'Neighbor sings karaoke until 11 PM. Very loud and disturbing.', 'noise', '2025-12-05 13:00:00', 'pending', 'medium', NULL),
(12, 14, 'CMP-20260115-3012', 'Broken water pipe leaking', 'Water pipe on our street has been leaking for a week. Wasting water.', 'infrastructure', '2026-01-15 00:00:00', 'resolved', 'high', 'Pipe has been replaced. Water supply restored.'),
(13, 19, 'CMP-20260220-3013', 'Foul smell from nearby piggery', 'Piggery behind our house emits strong smell. Affects our breathing.', 'waste', '2026-02-20 02:30:00', 'pending', 'high', 'Piggery owner advised to improve waste management. Follow-up inspection next week.'),
(14, 23, 'CMP-20260318-3014', 'Speeding tricycles', 'Tricycles speed through our street. Almost hit children playing.', 'peace_order', '2026-03-18 07:45:00', 'pending', 'high', 'Speed bumps will be installed next month. Increased patrols.'),
(15, 27, 'CMP-20260422-3015', 'Cockfighting noise every weekend', 'Neighbor holds cockfighting every Sunday. Very loud and disturbing.', 'noise', '2026-04-22 06:00:00', 'pending', 'medium', NULL),
(16, 4, 'CMP-20260501-3016', 'Drainage clogged again', 'Same area flooding again after the rain last night.', 'infrastructure', '2026-05-01 01:30:00', 'reviewing', 'high', 'Engineer assigned to inspect. Will provide update within 3 days.'),
(17, 8, 'CMP-20260505-3017', 'Missing street signs', 'Street name signs are missing. Delivery drivers cannot find our house.', 'infrastructure', '2026-05-05 03:00:00', 'pending', 'low', 'New signs ordered. Will be installed next week.'),
(18, 11, 'CMP-20260510-3018', 'Neighbor throwing trash in our yard', 'Neighbor throws garbage over our fence. We have security footage.', 'peace_order', '2026-05-10 08:20:00', 'pending', 'high', NULL),
(19, 16, 'CMP-20260515-3019', 'Dog barking all night', 'Neighbor\'s dog barks continuously from 10 PM to 6 AM. Cannot sleep.', 'noise', '2026-05-14 23:30:00', 'pending', 'medium', 'Warning issued to dog owner. Will monitor situation.'),
(20, 21, 'CMP-20260520-3020', 'Sidewalk obstructed by vendor', 'Vendor sets up stall on sidewalk. Pedestrians forced to walk on road.', 'peace_order', '2026-05-20 01:15:00', 'pending', 'low', NULL),
(21, 24, 'CMP-20260522-3021', 'Dead animal in creek', 'Dead pig floating in the creek behind our house. Very bad smell.', 'waste', '2026-05-22 06:30:00', 'pending', 'high', 'Animal control dispatched for removal. Area to be disinfected.'),
(22, 29, 'CMP-20260525-3022', 'Unfinished road repair', 'Road was dug up 3 weeks ago for pipe repair. Still not paved back.', 'infrastructure', '2026-05-25 02:00:00', 'pending', 'medium', NULL),
(23, 1, 'CMP-20260526-3023', 'Noise from construction on Sunday', 'Construction workers start at 7 AM on Sundays. Day of rest should be observed.', 'noise', '2026-05-26 00:00:00', 'pending', 'medium', NULL),
(24, 2, 'CMP-20260526-3024', 'Garbage truck comes too early', 'Garbage collection at 4 AM wakes everyone up.', 'waste', '2026-05-26 05:00:00', 'pending', 'low', 'Schedule adjusted to 6 AM starting next week.'),
(25, 6, 'CMP-20260527-3025', 'Street light out again', 'The street light we reported last month is broken again.', 'infrastructure', '2026-05-27 00:30:00', 'pending', 'medium', NULL),
(26, 13, 'CMP-20260527-3026', 'Loud arguments from neighbor', 'Couple next door fights loudly every night. Concerned for their safety.', 'peace_order', '2026-05-27 03:00:00', 'pending', 'high', NULL),
(27, 17, 'CMP-20260527-3027', 'Mosquito problem due to stagnant water', 'Abandoned lot has stagnant water. Breeding ground for mosquitoes.', 'waste', '2026-05-27 06:45:00', 'pending', 'medium', NULL),
(28, 20, 'CMP-20260527-3028', 'Unregistered tricycle operations', 'Unregistered tricycles are operating and causing accidents.', 'peace_order', '2026-05-27 08:30:00', 'pending', 'high', NULL),
(29, 31, 'CMP-20240120-4001', 'Noisy neighbor', 'Neighbor plays loud music every night until midnight.', 'noise', '2024-01-20 14:15:00', 'resolved', 'high', 'Warning issued. Noise reduced.'),
(30, 32, 'CMP-20240210-4002', 'Garbage collection missed', 'Truck missed our street for 3 weeks.', 'waste', '2024-02-10 00:30:00', 'resolved', 'high', 'Special collection done.'),
(31, 33, 'CMP-20240305-4003', 'Street light outage', 'Street light has been out for 2 months.', 'infrastructure', '2024-03-05 11:45:00', 'resolved', 'medium', 'Light bulb replaced.'),
(32, 34, 'CMP-20240325-4004', 'Property dispute', 'Neighbor built fence on our property.', 'peace_order', '2024-03-25 02:00:00', 'resolved', 'high', 'Survey conducted. Fence adjusted.'),
(33, 35, 'CMP-20240415-4005', 'Stray dogs', 'Aggressive stray dogs roaming the area.', 'peace_order', '2024-04-15 08:30:00', 'resolved', 'high', 'Animal control deployed.'),
(34, 36, 'CMP-20240505-4006', 'Flooding issue', 'Street floods every time it rains.', 'infrastructure', '2024-05-05 01:15:00', 'resolved', 'high', 'Drainage cleaned.'),
(35, 37, 'CMP-20240525-4007', 'Construction noise', 'Construction starts at 5 AM daily.', 'noise', '2024-05-24 22:45:00', 'resolved', 'medium', 'Contractor warned.'),
(36, 38, 'CMP-20240615-4008', 'Illegal parking', 'Cars block our driveway regularly.', 'peace_order', '2024-06-15 00:00:00', 'resolved', 'medium', 'No-parking signs installed.'),
(37, 39, 'CMP-20240705-4009', 'Potholes on road', 'Large potholes damaging vehicles.', 'infrastructure', '2024-07-05 03:30:00', 'resolved', 'high', 'Road repaired.'),
(38, 40, 'CMP-20240725-4010', 'Loud videoke', 'Neighbor sings karaoke until midnight.', 'noise', '2024-07-25 15:00:00', 'resolved', 'medium', 'Noise ordinance enforced.'),
(39, 41, 'CMP-20240815-4011', 'Garbage burning', 'Neighbor burns trash releasing toxic smoke.', 'waste', '2024-08-15 09:00:00', 'resolved', 'high', 'Violator fined. Burning stopped.'),
(40, 42, 'CMP-20240905-4012', 'Broken drainage cover', 'Missing manhole cover poses danger.', 'infrastructure', '2024-09-04 23:30:00', 'resolved', 'high', 'Cover replaced.'),
(41, 43, 'CMP-20240925-4013', 'Noise from sari-sari store', 'Store plays loud music all day.', 'noise', '2024-09-25 06:00:00', 'resolved', 'low', 'Store owner agreed to lower volume.'),
(42, 44, 'CMP-20241015-4014', 'Unregistered tricycle', 'Tricycle without franchise causing accidents.', 'peace_order', '2024-10-15 01:00:00', 'resolved', 'medium', 'Tricycle impounded.'),
(43, 45, 'CMP-20241105-4015', 'Dead animal in creek', 'Dead pig causing foul smell.', 'waste', '2024-11-05 02:30:00', 'resolved', 'high', 'Animal removed. Area disinfected.'),
(44, 46, 'CMP-20241125-4016', 'No electricity for 3 days', 'Power outage not yet fixed.', 'infrastructure', '2024-11-25 10:00:00', 'resolved', 'high', 'Electric coop contacted. Power restored.'),
(45, 47, 'CMP-20241215-4017', 'Neighbor threatening behavior', 'Neighbor shouting threats.', 'peace_order', '2024-12-15 12:00:00', 'reviewing', 'high', 'Investigation ongoing.'),
(46, 48, 'CMP-20241228-4018', 'Dirty canal', 'Canal clogged with garbage.', 'waste', '2024-12-28 00:00:00', 'resolved', 'medium', 'Canal cleaned.'),
(47, 49, 'CMP-20250110-4019', 'Loud party every weekend', 'Neighbor hosts parties until 3 AM.', 'noise', '2025-01-09 18:00:00', 'pending', 'medium', NULL),
(48, 50, 'CMP-20250130-4020', 'Garbage truck damages gate', 'Truck hit our gate and left.', 'waste', '2025-01-29 23:00:00', 'resolved', 'high', 'Barangay paid for repairs.'),
(49, 51, 'CMP-20250220-4021', 'Street flooding', 'Waist-deep flood during heavy rain.', 'infrastructure', '2025-02-20 07:30:00', '', 'high', 'Pumping station activated.'),
(50, 52, 'CMP-20250310-4022', 'Barking dog all night', 'Dog barks continuously from 10 PM to 6 AM.', 'noise', '2025-03-09 21:00:00', 'resolved', 'medium', 'Owner warned. Dog trained.'),
(51, 53, 'CMP-20250330-4023', 'Sidewalk obstruction', 'Vendor blocks entire sidewalk.', 'peace_order', '2025-03-30 01:00:00', 'pending', 'low', NULL),
(52, 54, 'CMP-20250420-4024', 'Water interruption', 'No water for 5 days.', 'infrastructure', '2025-04-20 00:00:00', 'resolved', 'high', 'Pipe repaired. Water restored.'),
(53, 55, 'CMP-20250510-4025', 'Rats infestation', 'Garbage attracts rats in the area.', 'waste', '2025-05-10 02:00:00', 'resolved', 'medium', 'Rat poisoning conducted.'),
(54, 56, 'CMP-20250530-4026', 'Neighbor throws trash in our yard', 'Garbage thrown over fence.', 'peace_order', '2025-05-30 08:00:00', 'pending', 'high', NULL),
(55, 57, 'CMP-20250620-4027', 'Cockfighting noise', 'Cockfighting every Sunday morning.', 'noise', '2025-06-20 00:00:00', 'resolved', 'medium', 'Illegal gambling stopped.'),
(56, 58, 'CMP-20250710-4028', 'Broken street sign', 'Missing street name sign.', 'infrastructure', '2025-07-10 03:00:00', 'resolved', 'low', 'New sign installed.'),
(57, 59, 'CMP-20250730-4029', 'Foul smell from piggery', 'Nearby piggery emits unbearable smell.', 'waste', '2025-07-30 01:30:00', '', 'high', 'Piggery ordered to improve waste management.'),
(58, 60, 'CMP-20250820-4030', 'Children throwing stones', 'Kids throwing stones at houses.', 'peace_order', '2025-08-20 07:00:00', 'resolved', 'medium', 'Parents called. Issue resolved.'),
(59, 61, 'CMP-20250910-4031', 'Construction dust', 'Construction causes too much dust.', 'noise', '2025-09-10 06:00:00', 'pending', 'low', NULL),
(60, 62, 'CMP-20250930-4032', 'Clogged drainage', 'Drainage clogged causing flood.', 'infrastructure', '2025-09-30 00:30:00', 'resolved', 'high', 'Drainage cleaned.'),
(61, 63, 'CMP-20251020-4033', 'Neighbor poisoning dogs', 'Suspected dog poisoning in area.', 'peace_order', '2025-10-20 10:00:00', 'reviewing', 'high', 'Investigation ongoing.'),
(62, 64, 'CMP-20251110-4034', 'Loud church bell', 'Church bell rings too early and too loud.', 'noise', '2025-11-09 21:30:00', 'resolved', 'low', 'Church agreed to lower volume.'),
(63, 65, 'CMP-20251130-4035', 'Open drainage hazard', 'Open drainage poses danger to children.', 'infrastructure', '2025-11-29 23:00:00', 'resolved', 'high', 'Cover installed.'),
(64, 66, 'CMP-20251220-4036', 'Dumping site near houses', 'Neighbors dump garbage in vacant lot.', 'waste', '2025-12-20 02:00:00', 'pending', 'medium', NULL),
(65, 67, 'CMP-20251228-4037', 'Noise from welding shop', 'Welding shop operates until midnight.', 'noise', '2025-12-28 15:00:00', 'resolved', 'medium', 'Shop ordered to close by 8 PM.'),
(66, 68, 'CMP-20260105-4038', 'Road sinking', 'Road surface sinking in front of house.', 'infrastructure', '2026-01-05 00:00:00', '', 'high', 'Engineer inspection scheduled.'),
(67, 69, 'CMP-20260115-4039', 'Neighbor harasses children', 'Neighbor shouts at playing children.', 'peace_order', '2026-01-15 08:00:00', 'pending', 'high', NULL),
(68, 70, 'CMP-20260125-4040', 'Garbage not collected for a month', 'No garbage truck for 4 weeks.', 'waste', '2026-01-25 01:00:00', 'resolved', 'high', 'Route adjusted. Collection resumed.'),
(69, 71, 'CMP-20260205-4041', 'Videoke all night', 'Karaoke from 8 PM to 4 AM daily.', 'noise', '2026-02-04 19:00:00', 'pending', 'medium', NULL),
(70, 72, 'CMP-20260215-4042', 'Flooded street again', 'Street floods even with light rain.', 'infrastructure', '2026-02-15 05:00:00', '', 'high', 'Drainage system upgrade planned.'),
(71, 73, 'CMP-20260225-4043', 'Trash thrown in river', 'People dumping garbage in river.', 'waste', '2026-02-25 02:30:00', 'resolved', 'medium', 'Anti-dumping signs installed.'),
(72, 74, 'CMP-20260305-4044', 'Neighbor blocks driveway', 'Cars parked blocking entrance.', 'peace_order', '2026-03-04 23:00:00', 'pending', 'low', NULL),
(73, 75, 'CMP-20260315-4045', 'Loud music from bar', 'Nearby bar plays loud music until 3 AM.', 'noise', '2026-03-14 17:00:00', '', 'high', 'Bar warned. Noise reduced.'),
(74, 76, 'CMP-20260325-4046', 'Pothole accident', 'Motorcycle fell due to large pothole.', 'infrastructure', '2026-03-25 00:00:00', 'resolved', 'high', 'Pothole repaired.'),
(75, 77, 'CMP-20260405-4047', 'Dog attacks', 'Neighbor\'s dog bites people.', 'peace_order', '2026-04-05 09:00:00', '', 'high', 'Dog impounded. Owner fined.'),
(76, 78, 'CMP-20260415-4048', 'Construction on Sunday', 'Construction noise every Sunday morning.', 'noise', '2026-04-14 23:00:00', 'pending', 'medium', NULL),
(77, 79, 'CMP-20260425-4049', 'Broken water pipe', 'Water pipe burst flooding street.', 'infrastructure', '2026-04-24 22:00:00', 'resolved', 'high', 'Pipe repaired immediately.'),
(78, 80, 'CMP-20260501-4050', 'Illegal vendor on sidewalk', 'Vendors block pedestrian walkway.', 'peace_order', '2026-05-01 01:00:00', 'pending', 'low', NULL),
(79, 81, 'CMP-20260502-4051', 'Noise from karaoke bar', 'Karaoke bar too loud until dawn.', 'noise', '2026-05-01 18:00:00', '', 'medium', 'Warning issued.'),
(80, 82, 'CMP-20260503-4052', 'Garbage pile burning', 'Neighbor burns garbage causing smoke.', 'waste', '2026-05-03 07:00:00', 'pending', 'high', NULL),
(81, 83, 'CMP-20260504-4053', 'Street light cluster outage', 'Whole block street lights not working.', 'infrastructure', '2026-05-04 11:00:00', '', 'medium', 'Electrician dispatched.'),
(82, 84, 'CMP-20260505-4054', 'Threats from neighbor', 'Neighbor threatened physical harm.', 'peace_order', '2026-05-05 12:00:00', 'pending', 'high', NULL),
(83, 85, 'CMP-20260506-4055', 'Loud arguments', 'Couple fights loudly every night.', 'noise', '2026-05-06 14:00:00', 'pending', 'medium', NULL),
(84, 86, 'CMP-20260507-4056', 'Flooding due to clogged canal', 'Canal clogged causing flood.', 'infrastructure', '2026-05-07 00:30:00', 'resolved', 'high', 'Canal cleared.'),
(85, 87, 'CMP-20260508-4057', 'Stray cats infestation', 'Stray cats multiplying in area.', 'waste', '2026-05-08 02:00:00', 'pending', 'low', NULL),
(86, 88, 'CMP-20260509-4058', 'Noise from church bell', 'Church bell rings every hour all night.', 'noise', '2026-05-09 15:00:00', '', 'medium', 'Church agreed to stop night ringing.'),
(87, 89, 'CMP-20260510-4059', 'Cracked sidewalk', 'Sidewalk cracked posing tripping hazard.', 'infrastructure', '2026-05-09 23:30:00', 'pending', 'low', NULL),
(88, 90, 'CMP-20260511-4060', 'Neighbor cuts our tree', 'Neighbor cut our tree without permission.', 'peace_order', '2026-05-11 06:00:00', '', 'medium', 'Barangay mediation scheduled.'),
(89, 91, 'CMP-20260512-4061', 'Loud videoke until 2 AM', 'Neighbor videoke nightly until early morning.', 'noise', '2026-05-11 17:00:00', 'pending', 'high', NULL),
(90, 92, 'CMP-20260513-4062', 'Dirty water from pipe', 'Pipe releases dirty water on street.', 'waste', '2026-05-13 01:00:00', 'resolved', 'medium', 'Pipe fixed.'),
(91, 93, 'CMP-20260514-4063', 'Missing manhole cover', 'Open manhole dangerous for kids.', 'infrastructure', '2026-05-14 08:00:00', 'resolved', 'high', 'Cover replaced.'),
(92, 94, 'CMP-20260515-4064', 'Neighbor threw rocks at house', 'Rocks thrown at our roof.', 'peace_order', '2026-05-15 13:00:00', 'pending', 'high', NULL),
(93, 95, 'CMP-20260516-4065', 'Drunk neighbors fighting', 'Drunk neighbors fighting on street.', 'peace_order', '2026-05-16 15:30:00', '', 'high', 'Police called. Situation controlled.'),
(94, 96, 'CMP-20260517-4066', 'No water for a week', 'Water interruption for 7 days.', 'infrastructure', '2026-05-16 23:00:00', 'pending', 'high', NULL),
(95, 97, 'CMP-20260518-4067', 'Garbage attracts flies', 'Uncollected garbage causing fly infestation.', 'waste', '2026-05-18 03:00:00', '', 'medium', 'Special collection scheduled.'),
(96, 98, 'CMP-20260519-4068', 'Loud motorcycle racing', 'Motorcycles race on street at night.', 'noise', '2026-05-19 15:00:00', 'pending', 'high', NULL),
(97, 99, 'CMP-20260520-4069', 'Sinking road', 'Road sinking deeper each week.', 'infrastructure', '2026-05-20 00:00:00', '', 'high', 'Engineering assessment ongoing.'),
(98, 100, 'CMP-20260521-4070', 'Neighbor enters our property', 'Neighbor trespasses without permission.', 'peace_order', '2026-05-21 05:00:00', 'pending', 'medium', NULL),
(99, 101, 'CMP-20260522-4071', 'Construction dust daily', 'Dust from construction affects breathing.', 'noise', '2026-05-22 01:30:00', 'pending', 'medium', NULL),
(100, 102, 'CMP-20260523-4072', 'Overflowing septic tank', 'Neighbor\'s septic tank overflows.', 'waste', '2026-05-23 02:00:00', '', 'high', 'Owner ordered to fix immediately.'),
(101, 103, 'CMP-20260524-4073', 'No street lighting for a month', 'Street dark for 4 weeks.', 'infrastructure', '2026-05-24 10:30:00', 'pending', 'high', NULL),
(102, 104, 'CMP-20260525-4074', 'Neighbor steals plants', 'Plants stolen from our garden.', 'peace_order', '2026-05-24 23:00:00', 'pending', 'low', NULL),
(103, 105, 'CMP-20260526-4075', 'Loud party every night', 'Neighbor parties until sunrise daily.', 'noise', '2026-05-25 19:00:00', 'pending', 'high', NULL),
(104, 106, 'CMP-20260526-4076', 'Drainage emits foul smell', 'Canal smells like sewage.', 'waste', '2026-05-26 01:00:00', '', 'medium', 'Cleaning scheduled.'),
(105, 107, 'CMP-20260526-4077', 'Cracked wall due to construction', 'Neighbor\'s construction cracked our wall.', 'infrastructure', '2026-05-26 06:00:00', 'pending', 'high', NULL),
(106, 108, 'CMP-20260527-4078', 'Neighbor threatens to kill dog', 'Neighbor threatened to poison our dog.', 'peace_order', '2026-05-27 00:00:00', 'pending', 'high', NULL),
(107, 109, 'CMP-20260527-4079', 'Karaoke from 6 AM', 'Neighbor starts videoke at 6 AM.', 'noise', '2026-05-26 22:30:00', 'pending', 'medium', NULL),
(108, 110, 'CMP-20260527-4080', 'Garbage scattered by dogs', 'Stray dogs scatter garbage bags.', 'waste', '2026-05-26 23:00:00', '', 'low', 'New garbage bins to be provided.'),
(109, 111, 'CMP-20260527-4081', 'Flooding again', 'Street flooded after 1 hour of rain.', 'infrastructure', '2026-05-27 07:00:00', 'pending', 'high', NULL),
(110, 112, 'CMP-20260527-4082', 'Neighbor blocks sidewalk', 'Neighbor\'s plants block sidewalk.', 'peace_order', '2026-05-27 01:30:00', 'pending', 'low', NULL),
(111, 113, 'CMP-20260527-4083', 'Loud church bells', 'Church bells ring too frequently.', 'noise', '2026-05-27 04:00:00', 'pending', 'low', NULL),
(112, 114, 'CMP-20260527-4084', 'Buried garbage burning', 'Underground garbage burning emits smoke.', 'waste', '2026-05-27 08:00:00', '', 'medium', 'Investigation ongoing.'),
(113, 115, 'CMP-20260527-4085', 'No street signs', 'Street name signs missing for years.', 'infrastructure', '2026-05-27 02:00:00', 'pending', 'low', NULL),
(114, 116, 'CMP-20260527-4086', 'Neighbor harasses guests', 'Neighbor shouts at our visitors.', 'peace_order', '2026-05-27 03:30:00', 'pending', 'medium', NULL),
(115, 117, 'CMP-20260527-4087', 'Construction noise weekends', 'Construction all day Saturday and Sunday.', 'noise', '2026-05-27 00:00:00', 'pending', 'medium', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=131 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `household`
--

INSERT INTO `household` (`household_id`, `email`, `password`, `address`, `phase_no`, `preferred_language`, `created_at`) VALUES
(1, 'juansantos@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 1, Lot 5, Maharlika St, Phase 1', 'Phase 1', 'tagalog', '2024-01-15 01:23:45'),
(2, 'maria.reyes@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 2, Lot 8, Rizal St, Phase 1', 'Phase 1', 'english', '2024-02-20 06:15:30'),
(3, 'josecruz@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 3, Lot 12, Bonifacio St, Phase 2', 'Phase 2', 'tagalog', '2024-03-10 02:45:22'),
(4, 'anagarcia@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 5, Lot 3, Luna St, Phase 2', 'Phase 2', 'tagalog', '2024-04-05 08:30:15'),
(5, 'pedromendoza@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 7, Lot 15, Mabini St, Phase 3', 'Phase 3', 'english', '2024-05-18 03:20:40'),
(6, 'rosaflores@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 4, Lot 9, Del Pilar St, Phase 1', 'Phase 1', 'tagalog', '2024-06-22 01:10:33'),
(7, 'antoniotorres@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 8, Lot 22, Javier St, Phase 3', 'Phase 3', 'english', '2024-07-30 06:55:18'),
(8, 'teresa.aquino@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 6, Lot 7, Santiago St, Phase 2', 'Phase 2', 'tagalog', '2024-08-14 00:25:47'),
(9, 'manuelcastillo@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 10, Lot 18, Maharlika St, Phase 4', 'Phase 4', 'english', '2024-09-03 05:40:22'),
(10, 'elenavillanueva@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 9, Lot 11, Rizal St, Phase 3', 'Phase 3', 'tagalog', '2024-10-19 08:15:55'),
(11, 'ricardogonzales@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 12, Lot 25, Bonifacio St, Phase 4', 'Phase 4', 'english', '2024-11-11 03:30:12'),
(12, 'luzramirez@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 11, Lot 14, Luna St, Phase 4', 'Phase 4', 'tagalog', '2024-12-05 01:05:33'),
(13, 'fernandodelacruz@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 14, Lot 6, Mabini St, Phase 5', 'Phase 5', 'tagalog', '2025-01-20 06:20:44'),
(14, 'celiafernandez@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 13, Lot 19, Del Pilar St, Phase 5', 'Phase 5', 'english', '2025-02-14 02:10:18'),
(15, 'rogeliolopez@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 15, Lot 28, Javier St, Phase 5', 'Phase 5', 'tagalog', '2025-03-08 07:45:29'),
(16, 'nievesrivera@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 16, Lot 4, Santiago St, Phase 1', 'Phase 1', 'english', '2025-04-22 00:30:51'),
(17, 'gregoriogomez@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 18, Lot 30, Maharlika St, Phase 2', 'Phase 2', 'tagalog', '2025-05-17 04:25:37'),
(18, 'pilardiaz@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 17, Lot 10, Rizal St, Phase 2', 'Phase 2', 'tagalog', '2025-06-30 09:40:13'),
(19, 'felicianoalvarez@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 19, Lot 21, Bonifacio St, Phase 3', 'Phase 3', 'english', '2025-07-25 01:55:46'),
(20, 'corazonnavarro@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 20, Lot 16, Luna St, Phase 3', 'Phase 3', 'tagalog', '2025-08-12 03:05:22'),
(21, 'dantesantos@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 22, Lot 27, Mabini St, Phase 4', 'Phase 4', 'english', '2025-09-05 06:50:33'),
(22, 'lourdesreyes@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 21, Lot 13, Del Pilar St, Phase 4', 'Phase 4', 'tagalog', '2025-10-18 02:15:44'),
(23, 'ramoncruz@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 24, Lot 9, Javier St, Phase 5', 'Phase 5', 'tagalog', '2025-11-22 08:35:19'),
(24, 'milagrosgarcia@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 23, Lot 24, Santiago St, Phase 5', 'Phase 5', 'english', '2025-12-15 05:25:48'),
(25, 'ernestomendoza@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 25, Lot 2, Maharlika St, Phase 1', 'Phase 1', 'tagalog', '2026-01-10 01:40:15'),
(26, 'consueloflores@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 26, Lot 17, Rizal St, Phase 1', 'Phase 1', 'english', '2026-02-08 06:55:27'),
(27, 'renetorres@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 28, Lot 29, Bonifacio St, Phase 2', 'Phase 2', 'tagalog', '2026-03-14 03:20:36'),
(28, 'gloriaaquino@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 27, Lot 20, Luna St, Phase 2', 'Phase 2', 'tagalog', '2026-04-02 07:10:42'),
(29, 'romeocastillo@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 29, Lot 7, Mabini St, Phase 3', 'Phase 3', 'english', '2026-05-01 00:45:53'),
(30, 'julietavillanueva@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 30, Lot 23, Del Pilar St, Phase 3', 'Phase 3', 'tagalog', '2026-05-20 04:30:18'),
(31, 'household1@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 101, Lot 1, Maharlika St, Phase 1', 'Phase 1', 'tagalog', '2024-01-05 00:00:00'),
(32, 'household2@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 102, Lot 2, Rizal St, Phase 1', 'Phase 1', 'english', '2024-01-12 01:15:00'),
(33, 'household3@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 103, Lot 3, Bonifacio St, Phase 1', 'Phase 1', 'tagalog', '2024-01-20 02:30:00'),
(34, 'household4@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 104, Lot 4, Luna St, Phase 1', 'Phase 1', 'english', '2024-02-01 03:45:00'),
(35, 'household5@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 105, Lot 5, Mabini St, Phase 1', 'Phase 1', 'tagalog', '2024-02-14 05:00:00'),
(36, 'household6@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 106, Lot 6, Del Pilar St, Phase 1', 'Phase 1', 'english', '2024-02-28 06:15:00'),
(37, 'household7@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 107, Lot 7, Javier St, Phase 2', 'Phase 2', 'tagalog', '2024-03-05 07:30:00'),
(38, 'household8@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 108, Lot 8, Santiago St, Phase 2', 'Phase 2', 'english', '2024-03-15 08:45:00'),
(39, 'household9@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 109, Lot 9, Maharlika St, Phase 2', 'Phase 2', 'tagalog', '2024-03-25 00:00:00'),
(40, 'household10@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 110, Lot 10, Rizal St, Phase 2', 'Phase 2', 'english', '2024-04-02 01:15:00'),
(41, 'household11@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 111, Lot 11, Bonifacio St, Phase 2', 'Phase 2', 'tagalog', '2024-04-12 02:30:00'),
(42, 'household12@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 112, Lot 12, Luna St, Phase 3', 'Phase 3', 'english', '2024-04-22 03:45:00'),
(43, 'household13@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 113, Lot 13, Mabini St, Phase 3', 'Phase 3', 'tagalog', '2024-05-01 05:00:00'),
(44, 'household14@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 114, Lot 14, Del Pilar St, Phase 3', 'Phase 3', 'english', '2024-05-11 06:15:00'),
(45, 'household15@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 115, Lot 15, Javier St, Phase 3', 'Phase 3', 'tagalog', '2024-05-21 07:30:00'),
(46, 'household16@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 116, Lot 16, Santiago St, Phase 3', 'Phase 3', 'english', '2024-06-01 08:45:00'),
(47, 'household17@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 117, Lot 17, Maharlika St, Phase 4', 'Phase 4', 'tagalog', '2024-06-10 00:00:00'),
(48, 'household18@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 118, Lot 18, Rizal St, Phase 4', 'Phase 4', 'english', '2024-06-20 01:15:00'),
(49, 'household19@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 119, Lot 19, Bonifacio St, Phase 4', 'Phase 4', 'tagalog', '2024-06-30 02:30:00'),
(50, 'household20@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 120, Lot 20, Luna St, Phase 4', 'Phase 4', 'english', '2024-07-08 03:45:00'),
(51, 'household21@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 121, Lot 21, Mabini St, Phase 4', 'Phase 4', 'tagalog', '2024-07-18 05:00:00'),
(52, 'household22@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 122, Lot 22, Del Pilar St, Phase 5', 'Phase 5', 'english', '2024-07-28 06:15:00'),
(53, 'household23@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 123, Lot 23, Javier St, Phase 5', 'Phase 5', 'tagalog', '2024-08-05 07:30:00'),
(54, 'household24@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 124, Lot 24, Santiago St, Phase 5', 'Phase 5', 'english', '2024-08-15 08:45:00'),
(55, 'household25@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 125, Lot 25, Maharlika St, Phase 5', 'Phase 5', 'tagalog', '2024-08-25 00:00:00'),
(56, 'household26@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 126, Lot 26, Rizal St, Phase 5', 'Phase 5', 'english', '2024-09-03 01:15:00'),
(57, 'household27@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 127, Lot 27, Bonifacio St, Phase 1', 'Phase 1', 'tagalog', '2024-09-13 02:30:00'),
(58, 'household28@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 128, Lot 28, Luna St, Phase 1', 'Phase 1', 'english', '2024-09-23 03:45:00'),
(59, 'household29@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 129, Lot 29, Mabini St, Phase 1', 'Phase 1', 'tagalog', '2024-10-01 05:00:00'),
(60, 'household30@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 130, Lot 30, Del Pilar St, Phase 1', 'Phase 1', 'english', '2024-10-11 06:15:00'),
(61, 'household31@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 131, Lot 1, Javier St, Phase 2', 'Phase 2', 'tagalog', '2024-10-21 07:30:00'),
(62, 'household32@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 132, Lot 2, Santiago St, Phase 2', 'Phase 2', 'english', '2024-11-01 08:45:00'),
(63, 'household33@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 133, Lot 3, Maharlika St, Phase 2', 'Phase 2', 'tagalog', '2024-11-11 00:00:00'),
(64, 'household34@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 134, Lot 4, Rizal St, Phase 2', 'Phase 2', 'english', '2024-11-21 01:15:00'),
(65, 'household35@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 135, Lot 5, Bonifacio St, Phase 2', 'Phase 2', 'tagalog', '2024-12-01 02:30:00'),
(66, 'household36@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 136, Lot 6, Luna St, Phase 3', 'Phase 3', 'english', '2024-12-11 03:45:00'),
(67, 'household37@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 137, Lot 7, Mabini St, Phase 3', 'Phase 3', 'tagalog', '2024-12-21 05:00:00'),
(68, 'household38@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 138, Lot 8, Del Pilar St, Phase 3', 'Phase 3', 'english', '2025-01-02 06:15:00'),
(69, 'household39@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 139, Lot 9, Javier St, Phase 3', 'Phase 3', 'tagalog', '2025-01-12 07:30:00'),
(70, 'household40@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 140, Lot 10, Santiago St, Phase 3', 'Phase 3', 'english', '2025-01-22 08:45:00'),
(71, 'household41@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 141, Lot 11, Maharlika St, Phase 4', 'Phase 4', 'tagalog', '2025-02-01 00:00:00'),
(72, 'household42@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 142, Lot 12, Rizal St, Phase 4', 'Phase 4', 'english', '2025-02-11 01:15:00'),
(73, 'household43@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 143, Lot 13, Bonifacio St, Phase 4', 'Phase 4', 'tagalog', '2025-02-21 02:30:00'),
(74, 'household44@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 144, Lot 14, Luna St, Phase 4', 'Phase 4', 'english', '2025-03-03 03:45:00'),
(75, 'household45@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 145, Lot 15, Mabini St, Phase 4', 'Phase 4', 'tagalog', '2025-03-13 05:00:00'),
(76, 'household46@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 146, Lot 16, Del Pilar St, Phase 5', 'Phase 5', 'english', '2025-03-23 06:15:00'),
(77, 'household47@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 147, Lot 17, Javier St, Phase 5', 'Phase 5', 'tagalog', '2025-04-02 07:30:00'),
(78, 'household48@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 148, Lot 18, Santiago St, Phase 5', 'Phase 5', 'english', '2025-04-12 08:45:00'),
(79, 'household49@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 149, Lot 19, Maharlika St, Phase 5', 'Phase 5', 'tagalog', '2025-04-22 00:00:00'),
(80, 'household50@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 150, Lot 20, Rizal St, Phase 5', 'Phase 5', 'english', '2025-05-02 01:15:00'),
(81, 'household51@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 151, Lot 21, Bonifacio St, Phase 1', 'Phase 1', 'tagalog', '2025-05-12 02:30:00'),
(82, 'household52@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 152, Lot 22, Luna St, Phase 1', 'Phase 1', 'english', '2025-05-22 03:45:00'),
(83, 'household53@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 153, Lot 23, Mabini St, Phase 1', 'Phase 1', 'tagalog', '2025-06-01 05:00:00'),
(84, 'household54@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 154, Lot 24, Del Pilar St, Phase 1', 'Phase 1', 'english', '2025-06-11 06:15:00'),
(85, 'household55@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 155, Lot 25, Javier St, Phase 2', 'Phase 2', 'tagalog', '2025-06-21 07:30:00'),
(86, 'household56@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 156, Lot 26, Santiago St, Phase 2', 'Phase 2', 'english', '2025-07-01 08:45:00'),
(87, 'household57@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 157, Lot 27, Maharlika St, Phase 2', 'Phase 2', 'tagalog', '2025-07-11 00:00:00'),
(88, 'household58@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 158, Lot 28, Rizal St, Phase 2', 'Phase 2', 'english', '2025-07-21 01:15:00'),
(89, 'household59@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 159, Lot 29, Bonifacio St, Phase 2', 'Phase 2', 'tagalog', '2025-08-01 02:30:00'),
(90, 'household60@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 160, Lot 30, Luna St, Phase 3', 'Phase 3', 'english', '2025-08-11 03:45:00'),
(91, 'household61@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 161, Lot 1, Mabini St, Phase 3', 'Phase 3', 'tagalog', '2025-08-21 05:00:00'),
(92, 'household62@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 162, Lot 2, Del Pilar St, Phase 3', 'Phase 3', 'english', '2025-09-01 06:15:00'),
(93, 'household63@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 163, Lot 3, Javier St, Phase 3', 'Phase 3', 'tagalog', '2025-09-11 07:30:00'),
(94, 'household64@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 164, Lot 4, Santiago St, Phase 3', 'Phase 3', 'english', '2025-09-21 08:45:00'),
(95, 'household65@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 165, Lot 5, Maharlika St, Phase 4', 'Phase 4', 'tagalog', '2025-10-01 00:00:00'),
(96, 'household66@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 166, Lot 6, Rizal St, Phase 4', 'Phase 4', 'english', '2025-10-11 01:15:00'),
(97, 'household67@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 167, Lot 7, Bonifacio St, Phase 4', 'Phase 4', 'tagalog', '2025-10-21 02:30:00'),
(98, 'household68@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 168, Lot 8, Luna St, Phase 4', 'Phase 4', 'english', '2025-11-01 03:45:00'),
(99, 'household69@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 169, Lot 9, Mabini St, Phase 4', 'Phase 4', 'tagalog', '2025-11-11 05:00:00'),
(100, 'household70@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 170, Lot 10, Del Pilar St, Phase 5', 'Phase 5', 'english', '2025-11-21 06:15:00'),
(101, 'household71@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 171, Lot 11, Javier St, Phase 5', 'Phase 5', 'tagalog', '2025-12-01 07:30:00'),
(102, 'household72@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 172, Lot 12, Santiago St, Phase 5', 'Phase 5', 'english', '2025-12-11 08:45:00'),
(103, 'household73@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 173, Lot 13, Maharlika St, Phase 5', 'Phase 5', 'tagalog', '2025-12-21 00:00:00'),
(104, 'household74@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 174, Lot 14, Rizal St, Phase 1', 'Phase 1', 'english', '2026-01-02 01:15:00'),
(105, 'household75@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 175, Lot 15, Bonifacio St, Phase 1', 'Phase 1', 'tagalog', '2026-01-12 02:30:00'),
(106, 'household76@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 176, Lot 16, Luna St, Phase 1', 'Phase 1', 'english', '2026-01-22 03:45:00'),
(107, 'household77@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 177, Lot 17, Mabini St, Phase 1', 'Phase 1', 'tagalog', '2026-02-01 05:00:00'),
(108, 'household78@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 178, Lot 18, Del Pilar St, Phase 1', 'Phase 1', 'english', '2026-02-11 06:15:00'),
(109, 'household79@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 179, Lot 19, Javier St, Phase 2', 'Phase 2', 'tagalog', '2026-02-21 07:30:00'),
(110, 'household80@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 180, Lot 20, Santiago St, Phase 2', 'Phase 2', 'english', '2026-03-03 08:45:00'),
(111, 'household81@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 181, Lot 21, Maharlika St, Phase 2', 'Phase 2', 'tagalog', '2026-03-13 00:00:00'),
(112, 'household82@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 182, Lot 22, Rizal St, Phase 2', 'Phase 2', 'english', '2026-03-23 01:15:00'),
(113, 'household83@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 183, Lot 23, Bonifacio St, Phase 2', 'Phase 2', 'tagalog', '2026-04-02 02:30:00'),
(114, 'household84@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 184, Lot 24, Luna St, Phase 3', 'Phase 3', 'english', '2026-04-12 03:45:00'),
(115, 'household85@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 185, Lot 25, Mabini St, Phase 3', 'Phase 3', 'tagalog', '2026-04-22 05:00:00'),
(116, 'household86@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 186, Lot 26, Del Pilar St, Phase 3', 'Phase 3', 'english', '2026-05-02 06:15:00'),
(117, 'household87@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 187, Lot 27, Javier St, Phase 3', 'Phase 3', 'tagalog', '2026-05-12 07:30:00'),
(118, 'household88@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 188, Lot 28, Santiago St, Phase 3', 'Phase 3', 'english', '2026-05-22 08:45:00'),
(119, 'household89@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 189, Lot 29, Maharlika St, Phase 4', 'Phase 4', 'tagalog', '2024-01-08 00:30:00'),
(120, 'household90@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 190, Lot 30, Rizal St, Phase 4', 'Phase 4', 'english', '2024-02-18 01:45:00'),
(121, 'household91@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 191, Lot 1, Bonifacio St, Phase 4', 'Phase 4', 'tagalog', '2024-03-22 03:00:00'),
(122, 'household92@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 192, Lot 2, Luna St, Phase 4', 'Phase 4', 'english', '2024-04-28 04:15:00'),
(123, 'household93@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 193, Lot 3, Mabini St, Phase 5', 'Phase 5', 'tagalog', '2024-05-30 05:30:00'),
(124, 'household94@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 194, Lot 4, Del Pilar St, Phase 5', 'Phase 5', 'english', '2024-06-25 06:45:00'),
(125, 'household95@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 195, Lot 5, Javier St, Phase 5', 'Phase 5', 'tagalog', '2024-07-19 08:00:00'),
(126, 'household96@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 196, Lot 6, Santiago St, Phase 5', 'Phase 5', 'english', '2024-08-22 00:15:00'),
(127, 'household97@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 197, Lot 7, Maharlika St, Phase 1', 'Phase 1', 'tagalog', '2024-09-28 01:30:00'),
(128, 'household98@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 198, Lot 8, Rizal St, Phase 1', 'Phase 1', 'english', '2024-10-15 02:45:00'),
(129, 'household99@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 199, Lot 9, Bonifacio St, Phase 1', 'Phase 1', 'tagalog', '2024-11-18 04:00:00'),
(130, 'household100@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Blk 200, Lot 10, Luna St, Phase 1', 'Phase 1', 'english', '2024-12-22 05:15:00');

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
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `request_id`, `total_amount`, `payment_method`, `ref_no`, `is_paid`, `paid_at`) VALUES
(1, 1, 50.00, 'cash', 'PAY-20240115-2001', 1, '2024-01-16 01:00:00'),
(2, 2, 30.00, 'cash', 'PAY-20240220-2002', 1, '2024-02-21 02:30:00'),
(3, 3, 50.00, 'gcash', 'PAY-20240310-2003', 1, '2024-03-10 07:00:00'),
(4, 4, 200.00, 'cash', 'PAY-20240405-2004', 1, '2024-04-06 06:15:00'),
(5, 5, 25.00, 'cash', 'PAY-20240518-2005', 1, '2024-05-19 00:45:00'),
(6, 6, 0.00, 'cash', 'PAY-20240622-2006', 1, '2024-06-23 03:20:00'),
(7, 7, 500.00, 'gcash', 'PAY-20240730-2007', 0, NULL),
(8, 8, 15.00, 'cash', 'PAY-20240814-2008', 1, '2024-08-15 01:30:00'),
(9, 9, 40.00, 'cash', 'PAY-20240903-2009', 1, '2024-09-04 05:00:00'),
(10, 10, 0.00, 'cash', 'PAY-20241019-2010', 1, '2024-10-20 08:30:00'),
(11, 11, 100.00, 'gcash', 'PAY-20241111-2011', 1, '2024-11-11 04:00:00'),
(12, 12, 0.00, 'cash', 'PAY-20241205-2012', 1, '2024-12-06 02:15:00'),
(13, 13, 50.00, 'cash', 'PAY-20250120-2013', 1, '2025-01-21 06:45:00'),
(14, 14, 30.00, 'cash', 'PAY-20250214-2014', 1, '2025-02-15 03:30:00'),
(15, 15, 50.00, 'gcash', 'PAY-20250308-2015', 0, NULL),
(16, 16, 25.00, 'cash', 'PAY-20250422-2016', 1, '2025-04-23 01:00:00'),
(17, 17, 200.00, 'cash', 'PAY-20250517-2017', 0, NULL),
(18, 18, 0.00, 'cash', 'PAY-20250630-2018', 1, '2025-07-01 00:30:00'),
(19, 19, 500.00, 'gcash', 'PAY-20250725-2019', 0, NULL),
(20, 20, 15.00, 'cash', 'PAY-20250812-2020', 1, '2025-08-13 02:00:00'),
(21, 21, 40.00, 'cash', 'PAY-20250905-2021', 0, NULL),
(22, 22, 0.00, 'cash', 'PAY-20251018-2022', 1, '2025-10-19 06:30:00'),
(23, 23, 100.00, 'gcash', 'PAY-20251122-2023', 0, NULL),
(24, 24, 0.00, 'cash', 'PAY-20251215-2024', 1, '2025-12-16 01:45:00'),
(25, 25, 50.00, 'cash', 'PAY-20251220-2025', 0, NULL),
(26, 26, 30.00, 'cash', 'PAY-20251225-2026', 0, NULL),
(27, 27, 50.00, 'gcash', 'PAY-20260110-2027', 1, '2026-01-11 02:00:00'),
(28, 28, 25.00, 'cash', 'PAY-20260208-2028', 0, NULL),
(29, 29, 200.00, 'cash', 'PAY-20260314-2029', 0, NULL),
(30, 30, 0.00, 'cash', 'PAY-20260402-2030', 1, '2026-04-03 05:15:00'),
(31, 31, 500.00, 'gcash', 'PAY-20260410-2031', 0, NULL),
(32, 32, 15.00, 'cash', 'PAY-20260415-2032', 0, NULL),
(33, 33, 40.00, 'cash', 'PAY-20260420-2033', 1, '2026-04-21 06:00:00'),
(34, 34, 0.00, 'cash', 'PAY-20260425-2034', 0, NULL),
(35, 35, 100.00, 'gcash', 'PAY-20260428-2035', 0, NULL),
(36, 36, 0.00, 'cash', 'PAY-20260501-2036', 1, '2026-05-02 03:30:00'),
(37, 37, 50.00, 'cash', 'PAY-20260503-2037', 0, NULL),
(38, 38, 30.00, 'gcash', 'PAY-20260505-2038', 0, NULL),
(39, 39, 50.00, 'cash', 'PAY-20260507-2039', 1, '2026-05-08 01:45:00'),
(40, 40, 25.00, 'cash', 'PAY-20260509-2040', 0, NULL),
(41, 41, 200.00, 'gcash', 'PAY-20260511-2041', 0, NULL),
(42, 42, 0.00, 'cash', 'PAY-20260513-2042', 1, '2026-05-14 08:00:00'),
(43, 43, 500.00, 'cash', 'PAY-20260515-2043', 0, NULL),
(44, 44, 15.00, 'cash', 'PAY-20260517-2044', 0, NULL),
(45, 45, 40.00, 'gcash', 'PAY-20260519-2045', 1, '2026-05-20 02:30:00'),
(46, 46, 0.00, 'cash', 'PAY-20260521-2046', 0, NULL),
(47, 47, 100.00, 'cash', 'PAY-20260522-2047', 0, NULL),
(48, 48, 0.00, 'gcash', 'PAY-20260523-2048', 1, '2026-05-24 06:15:00'),
(49, 49, 50.00, 'cash', 'PAY-20260524-2049', 0, NULL),
(50, 50, 30.00, 'cash', 'PAY-20260525-2050', 0, NULL),
(51, 51, 50.00, 'gcash', 'PAY-20260526-2051', 1, '2026-05-26 04:00:00'),
(52, 52, 25.00, 'cash', 'PAY-20260526-2052', 0, NULL),
(53, 53, 200.00, 'cash', 'PAY-20260526-2053', 0, NULL),
(54, 54, 0.00, 'cash', 'PAY-20260526-2054', 0, NULL),
(55, 55, 500.00, 'gcash', 'PAY-20260527-2055', 0, NULL),
(56, 56, 15.00, 'cash', 'PAY-20260527-2056', 0, NULL),
(57, 57, 40.00, 'cash', 'PAY-20260527-2057', 0, NULL),
(58, 58, 0.00, 'cash', 'PAY-20260527-2058', 0, NULL),
(59, 61, 50.00, 'cash', 'PAY-20240110-3001', 1, '2024-01-11 01:00:00'),
(60, 62, 25.00, 'cash', 'PAY-20240120-3002', 1, '2024-01-21 02:30:00'),
(61, 63, 500.00, 'gcash', 'PAY-20240205-3003', 1, '2024-02-06 06:15:00'),
(62, 64, 200.00, 'cash', 'PAY-20240215-3004', 1, '2024-02-16 03:00:00'),
(63, 65, 15.00, 'cash', 'PAY-20240228-3005', 1, '2024-02-29 05:30:00'),
(64, 66, 40.00, 'cash', 'PAY-20240310-3006', 1, '2024-03-11 00:45:00'),
(65, 67, 30.00, 'cash', 'PAY-20240320-3007', 1, '2024-03-21 01:30:00'),
(66, 68, 0.00, 'cash', 'PAY-20240401-3008', 1, '2024-04-02 02:15:00'),
(67, 69, 100.00, 'gcash', 'PAY-20240412-3009', 1, '2024-04-13 03:45:00'),
(68, 70, 0.00, 'cash', 'PAY-20240425-3010', 1, '2024-04-26 05:00:00'),
(69, 71, 50.00, 'cash', 'PAY-20240505-3011', 1, '2024-05-06 06:30:00'),
(70, 72, 0.00, 'cash', 'PAY-20240518-3012', 1, '2024-05-19 00:00:00'),
(71, 73, 50.00, 'cash', 'PAY-20240601-3013', 1, '2024-06-02 01:15:00'),
(72, 74, 25.00, 'cash', 'PAY-20240615-3014', 1, '2024-06-16 02:45:00'),
(73, 75, 500.00, 'gcash', 'PAY-20240628-3015', 0, NULL),
(74, 76, 200.00, 'cash', 'PAY-20240710-3016', 1, '2024-07-11 03:30:00'),
(75, 77, 15.00, 'cash', 'PAY-20240722-3017', 1, '2024-07-23 04:45:00'),
(76, 78, 40.00, 'cash', 'PAY-20240805-3018', 1, '2024-08-06 05:15:00'),
(77, 79, 30.00, 'cash', 'PAY-20240818-3019', 1, '2024-08-19 06:00:00'),
(78, 80, 0.00, 'cash', 'PAY-20240901-3020', 1, '2024-09-02 00:30:00'),
(79, 81, 100.00, 'gcash', 'PAY-20240915-3021', 0, NULL),
(80, 82, 0.00, 'cash', 'PAY-20240928-3022', 1, '2024-09-29 01:45:00'),
(81, 83, 50.00, 'cash', 'PAY-20241010-3023', 1, '2024-10-11 02:00:00'),
(82, 84, 25.00, 'cash', 'PAY-20241022-3024', 1, '2024-10-23 03:15:00'),
(83, 85, 50.00, 'cash', 'PAY-20241105-3025', 0, NULL),
(84, 86, 25.00, 'cash', 'PAY-20241118-3026', 1, '2024-11-19 04:30:00'),
(85, 87, 500.00, 'gcash', 'PAY-20241201-3027', 0, NULL),
(86, 88, 200.00, 'cash', 'PAY-20241215-3028', 1, '2024-12-16 05:45:00'),
(87, 89, 15.00, 'cash', 'PAY-20241220-3029', 1, '2024-12-21 06:15:00'),
(88, 90, 40.00, 'cash', 'PAY-20241228-3030', 1, '2024-12-29 00:00:00'),
(89, 91, 30.00, 'cash', 'PAY-20250105-3031', 1, '2025-01-06 01:30:00'),
(90, 92, 0.00, 'cash', 'PAY-20250115-3032', 1, '2025-01-16 02:45:00'),
(91, 93, 100.00, 'gcash', 'PAY-20250128-3033', 1, '2025-01-29 03:00:00'),
(92, 94, 0.00, 'cash', 'PAY-20250210-3034', 1, '2025-02-11 04:15:00'),
(93, 95, 50.00, 'cash', 'PAY-20250222-3035', 0, NULL),
(94, 96, 0.00, 'cash', 'PAY-20250305-3036', 1, '2025-03-06 05:30:00'),
(95, 97, 50.00, 'cash', 'PAY-20250318-3037', 0, NULL),
(96, 98, 25.00, 'cash', 'PAY-20250401-3038', 1, '2025-04-02 06:45:00'),
(97, 99, 500.00, 'gcash', 'PAY-20250412-3039', 0, NULL),
(98, 100, 200.00, 'cash', 'PAY-20250425-3040', 1, '2025-04-26 00:15:00'),
(99, 101, 15.00, 'cash', 'PAY-20250508-3041', 1, '2025-05-09 01:30:00'),
(100, 102, 40.00, 'cash', 'PAY-20250520-3042', 1, '2025-05-21 02:45:00'),
(101, 103, 30.00, 'cash', 'PAY-20250602-3043', 1, '2025-06-03 03:00:00'),
(102, 104, 0.00, 'cash', 'PAY-20250615-3044', 0, NULL),
(103, 105, 100.00, 'gcash', 'PAY-20250628-3045', 0, NULL),
(104, 106, 0.00, 'cash', 'PAY-20250710-3046', 1, '2025-07-11 04:15:00'),
(105, 107, 50.00, 'cash', 'PAY-20250722-3047', 1, '2025-07-23 05:30:00'),
(106, 108, 0.00, 'cash', 'PAY-20250805-3048', 1, '2025-08-06 06:45:00'),
(107, 109, 50.00, 'cash', 'PAY-20250818-3049', 0, NULL),
(108, 110, 25.00, 'cash', 'PAY-20250901-3050', 0, NULL),
(109, 111, 500.00, 'gcash', 'PAY-20250914-3051', 1, '2025-09-15 00:00:00'),
(110, 112, 200.00, 'cash', 'PAY-20250928-3052', 1, '2025-09-29 01:15:00'),
(111, 113, 15.00, 'cash', 'PAY-20251010-3053', 1, '2025-10-11 02:30:00'),
(112, 114, 40.00, 'cash', 'PAY-20251022-3054', 0, NULL),
(113, 115, 30.00, 'cash', 'PAY-20251105-3055', 1, '2025-11-06 03:45:00'),
(114, 116, 0.00, 'cash', 'PAY-20251118-3056', 0, NULL),
(115, 117, 100.00, 'gcash', 'PAY-20251201-3057', 1, '2025-12-02 05:00:00'),
(116, 118, 0.00, 'cash', 'PAY-20251215-3058', 1, '2025-12-16 06:15:00'),
(117, 119, 50.00, 'cash', 'PAY-20251220-3059', 0, NULL),
(118, 120, 0.00, 'cash', 'PAY-20251228-3060', 1, '2025-12-29 00:30:00'),
(119, 121, 50.00, 'cash', 'PAY-20260105-3061', 0, NULL),
(120, 122, 25.00, 'cash', 'PAY-20260115-3062', 1, '2026-01-16 01:45:00'),
(121, 123, 500.00, 'gcash', 'PAY-20260128-3063', 0, NULL),
(122, 124, 200.00, 'cash', 'PAY-20260210-3064', 0, NULL),
(123, 125, 15.00, 'cash', 'PAY-20260222-3065', 1, '2026-02-23 02:00:00'),
(124, 126, 40.00, 'cash', 'PAY-20260305-3066', 0, NULL),
(125, 127, 30.00, 'cash', 'PAY-20260318-3067', 1, '2026-03-19 03:15:00'),
(126, 128, 0.00, 'cash', 'PAY-20260401-3068', 0, NULL),
(127, 129, 100.00, 'gcash', 'PAY-20260415-3069', 1, '2026-04-16 04:30:00'),
(128, 130, 0.00, 'cash', 'PAY-20260428-3070', 0, NULL),
(129, 131, 50.00, 'cash', 'PAY-20260502-3071', 0, NULL),
(130, 132, 0.00, 'cash', 'PAY-20260505-3072', 1, '2026-05-06 05:45:00'),
(131, 133, 50.00, 'cash', 'PAY-20260508-3073', 0, NULL),
(132, 134, 25.00, 'cash', 'PAY-20260511-3074', 0, NULL),
(133, 135, 500.00, 'gcash', 'PAY-20260514-3075', 1, '2026-05-15 06:00:00'),
(134, 136, 200.00, 'cash', 'PAY-20260517-3076', 0, NULL),
(135, 137, 15.00, 'cash', 'PAY-20260520-3077', 0, NULL),
(136, 138, 40.00, 'cash', 'PAY-20260523-3078', 1, '2026-05-24 00:15:00'),
(137, 139, 30.00, 'cash', 'PAY-20260525-3079', 1, '2026-05-26 01:30:00'),
(138, 140, 0.00, 'cash', 'PAY-20260527-3080', 0, NULL),
(139, 141, 100.00, 'gcash', 'PAY-20260527-3081', 0, NULL),
(140, 142, 0.00, 'cash', 'PAY-20260527-3082', 0, NULL),
(141, 143, 50.00, 'cash', 'PAY-20260527-3083', 1, '2026-05-27 02:45:00'),
(142, 144, 0.00, 'cash', 'PAY-20260527-3084', 0, NULL),
(143, 145, 50.00, 'cash', 'PAY-20260527-3085', 0, NULL),
(144, 146, 25.00, 'cash', 'PAY-20260527-3086', 0, NULL),
(145, 147, 500.00, 'gcash', 'PAY-20260527-3087', 1, '2026-05-27 03:30:00'),
(146, 148, 200.00, 'cash', 'PAY-20260527-3088', 0, NULL),
(147, 149, 15.00, 'cash', 'PAY-20260527-3089', 0, NULL),
(148, 150, 40.00, 'cash', 'PAY-20260527-3090', 0, NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=254 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `resident`
--

INSERT INTO `resident` (`resident_id`, `household_id`, `first_name`, `last_name`, `suffix`, `age`, `is_voter`, `is_head`, `relationship_to_head`, `contact_no`, `profile_photo_url`) VALUES
(1, 1, 'Juan', 'Santos', '', 45, 1, 1, 'Head', '09171234567', 'juan.santos@brgy.com'),
(2, 1, 'Maria', 'Santos', '', 42, 1, 0, 'Spouse', '09171234568', 'maria.santos@brgy.com'),
(3, 1, 'Jose', 'Santos', '', 18, 1, 0, 'Child', '09171234569', 'jose.santos@brgy.com'),
(4, 1, 'Ana', 'Santos', '', 15, 0, 0, 'Child', NULL, 'ana.santos@brgy.com'),
(5, 2, 'Maria', 'Reyes', '', 38, 1, 1, 'Head', '09181234567', 'maria.reyes@brgy.com'),
(6, 2, 'Pedro', 'Reyes', '', 40, 1, 0, 'Spouse', '09181234568', 'pedro.reyes@brgy.com'),
(7, 2, 'Luisa', 'Reyes', '', 12, 0, 0, 'Child', NULL, 'luisa.reyes@brgy.com'),
(8, 2, 'Rosa', 'Reyes', '', 8, 0, 0, 'Child', NULL, 'rosa.reyes@brgy.com'),
(9, 2, 'Ricardo', 'Reyes', '', 70, 1, 0, 'Parent', '09181234569', 'ricardo.reyes@brgy.com'),
(10, 3, 'Jose', 'Cruz', 'Jr.', 35, 1, 1, 'Head', '09191234567', 'jose.cruz@brgy.com'),
(11, 3, 'Teresa', 'Cruz', '', 33, 1, 0, 'Spouse', '09191234568', 'teresa.cruz@brgy.com'),
(12, 3, 'Manuel', 'Cruz', '', 10, 0, 0, 'Child', NULL, 'manuel.cruz@brgy.com'),
(13, 3, 'Elena', 'Cruz', '', 6, 0, 0, 'Child', NULL, 'elena.cruz@brgy.com'),
(14, 3, 'Luz', 'Cruz', '', 65, 1, 0, 'Parent', '09191234569', 'luz.cruz@brgy.com'),
(15, 4, 'Ana', 'Garcia', '', 52, 1, 1, 'Head', '09201234567', 'ana.garcia@brgy.com'),
(16, 4, 'Rogelio', 'Garcia', '', 55, 1, 0, 'Spouse', '09201234568', 'rogelio.garcia@brgy.com'),
(17, 4, 'Celia', 'Garcia', '', 25, 1, 0, 'Child', '09201234569', 'celia.garcia@brgy.com'),
(18, 4, 'Fernando', 'Garcia', '', 22, 1, 0, 'Child', '09201234570', 'fernando.garcia@brgy.com'),
(19, 5, 'Pedro', 'Mendoza', 'III', 48, 1, 1, 'Head', '09211234567', 'pedro.mendoza@brgy.com'),
(20, 5, 'Nieves', 'Mendoza', '', 46, 1, 0, 'Spouse', '09211234568', 'nieves.mendoza@brgy.com'),
(21, 5, 'Gregorio', 'Mendoza', '', 20, 1, 0, 'Child', '09211234569', 'gregorio.mendoza@brgy.com'),
(22, 5, 'Pilar', 'Mendoza', '', 17, 0, 0, 'Child', NULL, 'pilar.mendoza@brgy.com'),
(23, 5, 'Feliciano', 'Mendoza', '', 14, 0, 0, 'Child', NULL, 'feliciano.mendoza@brgy.com'),
(24, 6, 'Rosa', 'Flores', '', 60, 1, 1, 'Head', '09221234567', 'rosa.flores@brgy.com'),
(25, 6, 'Dante', 'Flores', '', 38, 1, 0, 'Child', '09221234568', 'dante.flores@brgy.com'),
(26, 6, 'Lourdes', 'Flores', '', 35, 1, 0, 'Child', '09221234569', 'lourdes.flores@brgy.com'),
(27, 6, 'Ramon', 'Flores', 'Jr.', 30, 1, 0, 'Child', '09221234570', 'ramon.flores@brgy.com'),
(28, 7, 'Antonio', 'Torres', '', 33, 1, 1, 'Head', '09231234567', 'antonio.torres@brgy.com'),
(29, 7, 'Milagros', 'Torres', '', 31, 1, 0, 'Spouse', '09231234568', 'milagros.torres@brgy.com'),
(30, 7, 'Ernesto', 'Torres', '', 5, 0, 0, 'Child', NULL, 'ernesto.torres@brgy.com'),
(31, 7, 'Consuelo', 'Torres', '', 3, 0, 0, 'Child', NULL, 'consuelo.torres@brgy.com'),
(32, 8, 'Teresa', 'Aquino', '', 44, 1, 1, 'Head', '09241234567', 'teresa.aquino@brgy.com'),
(33, 8, 'Rene', 'Aquino', '', 47, 1, 0, 'Spouse', '09241234568', 'rene.aquino@brgy.com'),
(34, 8, 'Gloria', 'Aquino', '', 19, 1, 0, 'Child', '09241234569', 'gloria.aquino@brgy.com'),
(35, 8, 'Romeo', 'Aquino', '', 16, 0, 0, 'Child', NULL, 'romeo.aquino@brgy.com'),
(36, 8, 'Julieta', 'Aquino', '', 12, 0, 0, 'Child', NULL, 'julieta.aquino@brgy.com'),
(37, 9, 'Manuel', 'Castillo', '', 41, 1, 1, 'Head', '09251234567', 'manuel.castillo@brgy.com'),
(38, 9, 'Corazon', 'Castillo', '', 39, 1, 0, 'Spouse', '09251234568', 'corazon.castillo@brgy.com'),
(39, 9, 'Juan', 'Castillo', '', 11, 0, 0, 'Child', NULL, 'juan.castillo@brgy.com'),
(40, 9, 'Maria', 'Castillo', '', 7, 0, 0, 'Child', NULL, 'maria.castillo@brgy.com'),
(41, 10, 'Elena', 'Villanueva', '', 29, 1, 1, 'Head', '09261234567', 'elena.villanueva@brgy.com'),
(42, 10, 'Jose', 'Villanueva', '', 8, 0, 0, 'Child', NULL, 'jose.villanueva@brgy.com'),
(43, 10, 'Ana', 'Villanueva', '', 5, 0, 0, 'Child', NULL, 'ana.villanueva@brgy.com'),
(44, 10, 'Luz', 'Villanueva', '', 60, 1, 0, 'Parent', '09261234568', 'luz.villanueva@brgy.com'),
(45, 11, 'Ricardo', 'Gonzales', '', 50, 1, 1, 'Head', '09271234567', 'ricardo.gonzales@brgy.com'),
(46, 11, 'Nieves', 'Gonzales', '', 48, 1, 0, 'Spouse', '09271234568', 'nieves.gonzales@brgy.com'),
(47, 11, 'Pedro', 'Gonzales', '', 22, 1, 0, 'Child', '09271234569', 'pedro.gonzales@brgy.com'),
(48, 11, 'Rosa', 'Gonzales', '', 18, 1, 0, 'Child', '09271234570', 'rosa.gonzales@brgy.com'),
(49, 12, 'Luz', 'Ramirez', '', 55, 1, 1, 'Head', '09281234567', 'luz.ramirez@brgy.com'),
(50, 12, 'Antonio', 'Ramirez', '', 58, 1, 0, 'Spouse', '09281234568', 'antonio.ramirez@brgy.com'),
(51, 12, 'Maria', 'Ramirez', '', 30, 1, 0, 'Child', '09281234569', 'maria.ramirez@brgy.com'),
(52, 12, 'Jose', 'Ramirez', '', 28, 1, 0, 'Child', '09281234570', 'jose.ramirez@brgy.com'),
(53, 12, 'Ana', 'Ramirez', '', 25, 1, 0, 'Child', '09281234571', 'ana.ramirez@brgy.com'),
(54, 13, 'Fernando', 'Dela Cruz', '', 36, 1, 1, 'Head', '09291234567', 'fernando.delacruz@brgy.com'),
(55, 13, 'Teresa', 'Dela Cruz', '', 34, 1, 0, 'Spouse', '09291234568', 'teresa.delacruz@brgy.com'),
(56, 13, 'Manuel', 'Dela Cruz', '', 8, 0, 0, 'Child', NULL, 'manuel.delacruz@brgy.com'),
(57, 14, 'Celia', 'Fernandez', '', 47, 1, 1, 'Head', '09301234567', 'celia.fernandez@brgy.com'),
(58, 14, 'Rogelio', 'Fernandez', '', 50, 1, 0, 'Spouse', '09301234568', 'rogelio.fernandez@brgy.com'),
(59, 14, 'Elena', 'Fernandez', '', 20, 1, 0, 'Child', '09301234569', 'elena.fernandez@brgy.com'),
(60, 15, 'Rogelio', 'Lopez', 'Jr.', 32, 1, 1, 'Head', '09311234567', 'rogelio.lopez@brgy.com'),
(61, 15, 'Milagros', 'Lopez', '', 30, 1, 0, 'Spouse', '09311234568', 'milagros.lopez@brgy.com'),
(62, 15, 'Ernesto', 'Lopez', '', 6, 0, 0, 'Child', NULL, 'ernesto.lopez@brgy.com'),
(63, 16, 'Nieves', 'Rivera', '', 62, 1, 1, 'Head', '09321234567', 'nieves.rivera@brgy.com'),
(64, 16, 'Gregorio', 'Rivera', '', 40, 1, 0, 'Child', '09321234568', 'gregorio.rivera@brgy.com'),
(65, 16, 'Pilar', 'Rivera', '', 38, 1, 0, 'Child', '09321234569', 'pilar.rivera@brgy.com'),
(66, 17, 'Gregorio', 'Gomez', '', 28, 1, 1, 'Head', '09331234567', 'gregorio.gomez@brgy.com'),
(67, 17, 'Corazon', 'Gomez', '', 26, 1, 0, 'Spouse', '09331234568', 'corazon.gomez@brgy.com'),
(68, 17, 'Dante', 'Gomez', '', 3, 0, 0, 'Child', NULL, 'dante.gomez@brgy.com'),
(69, 18, 'Pilar', 'Diaz', '', 45, 1, 1, 'Head', '09341234567', 'pilar.diaz@brgy.com'),
(70, 18, 'Feliciano', 'Diaz', '', 48, 1, 0, 'Spouse', '09341234568', 'feliciano.diaz@brgy.com'),
(71, 18, 'Lourdes', 'Diaz', '', 19, 1, 0, 'Child', '09341234569', 'lourdes.diaz@brgy.com'),
(72, 18, 'Ramon', 'Diaz', '', 15, 0, 0, 'Child', NULL, 'ramon.diaz@brgy.com'),
(73, 19, 'Feliciano', 'Alvarez', '', 53, 1, 1, 'Head', '09351234567', 'feliciano.alvarez@brgy.com'),
(74, 19, 'Consuelo', 'Alvarez', '', 51, 1, 0, 'Spouse', '09351234568', 'consuelo.alvarez@brgy.com'),
(75, 19, 'Rene', 'Alvarez', '', 27, 1, 0, 'Child', '09351234569', 'rene.alvarez@brgy.com'),
(76, 20, 'Corazon', 'Navarro', '', 34, 1, 1, 'Head', '09361234567', 'corazon.navarro@brgy.com'),
(77, 20, 'Gloria', 'Navarro', '', 10, 0, 0, 'Child', NULL, 'gloria.navarro@brgy.com'),
(78, 20, 'Romeo', 'Navarro', '', 7, 0, 0, 'Child', NULL, 'romeo.navarro@brgy.com'),
(79, 20, 'Julieta', 'Navarro', '', 4, 0, 0, 'Child', NULL, 'julieta.navarro@brgy.com'),
(80, 21, 'Dante', 'Santos', '', 49, 1, 1, 'Head', '09371234567', 'dante.santos@brgy.com'),
(81, 21, 'Luz', 'Santos', '', 46, 1, 0, 'Spouse', '09371234568', 'luz.santos@brgy.com'),
(82, 21, 'Fernando', 'Santos', '', 21, 1, 0, 'Child', '09371234569', 'fernando.santos@brgy.com'),
(83, 22, 'Lourdes', 'Reyes', '', 31, 1, 1, 'Head', '09381234567', 'lourdes.reyes@brgy.com'),
(84, 22, 'Rogelio', 'Reyes', '', 9, 0, 0, 'Child', NULL, 'rogelio.reyes@brgy.com'),
(85, 22, 'Celia', 'Reyes', '', 6, 0, 0, 'Child', NULL, 'celia.reyes@brgy.com'),
(86, 23, 'Ramon', 'Cruz', '', 42, 1, 1, 'Head', '09391234567', 'ramon.cruz@brgy.com'),
(87, 23, 'Nieves', 'Cruz', '', 40, 1, 0, 'Spouse', '09391234568', 'nieves.cruz@brgy.com'),
(88, 23, 'Gregorio', 'Cruz', '', 14, 0, 0, 'Child', NULL, 'gregorio.cruz@brgy.com'),
(89, 23, 'Pilar', 'Cruz', '', 11, 0, 0, 'Child', NULL, 'pilar.cruz@brgy.com'),
(90, 24, 'Milagros', 'Garcia', '', 38, 1, 1, 'Head', '09401234567', 'milagros.garcia@brgy.com'),
(91, 24, 'Ernesto', 'Garcia', '', 36, 1, 0, 'Spouse', '09401234568', 'ernesto.garcia@brgy.com'),
(92, 24, 'Consuelo', 'Garcia', '', 12, 0, 0, 'Child', NULL, 'consuelo.garcia@brgy.com'),
(93, 25, 'Ernesto', 'Mendoza', '', 44, 1, 1, 'Head', '09411234567', 'ernesto.mendoza@brgy.com'),
(94, 25, 'Rene', 'Mendoza', '', 42, 1, 0, 'Spouse', '09411234568', 'rene.mendoza@brgy.com'),
(95, 25, 'Gloria', 'Mendoza', '', 18, 1, 0, 'Child', '09411234569', 'gloria.mendoza@brgy.com'),
(96, 25, 'Romeo', 'Mendoza', '', 15, 0, 0, 'Child', NULL, 'romeo.mendoza@brgy.com'),
(97, 26, 'Consuelo', 'Flores', '', 55, 1, 1, 'Head', '09421234567', 'consuelo.flores@brgy.com'),
(98, 26, 'Julieta', 'Flores', '', 28, 1, 0, 'Child', '09421234568', 'julieta.flores@brgy.com'),
(99, 26, 'Juan', 'Flores', '', 25, 1, 0, 'Child', '09421234569', 'juan.flores@brgy.com'),
(100, 27, 'Rene', 'Torres', '', 35, 1, 1, 'Head', '09431234567', 'rene.torres@brgy.com'),
(101, 27, 'Maria', 'Torres', '', 33, 1, 0, 'Spouse', '09431234568', 'maria.torres@brgy.com'),
(102, 27, 'Jose', 'Torres', '', 7, 0, 0, 'Child', NULL, 'jose.torres@brgy.com'),
(103, 27, 'Ana', 'Torres', '', 4, 0, 0, 'Child', NULL, 'ana.torres@brgy.com'),
(104, 28, 'Gloria', 'Aquino', '', 29, 1, 1, 'Head', '09441234567', 'gloria.aquino@brgy.com'),
(105, 28, 'Pedro', 'Aquino', '', 5, 0, 0, 'Child', NULL, 'pedro.aquino@brgy.com'),
(106, 29, 'Romeo', 'Castillo', '', 47, 1, 1, 'Head', '09451234567', 'romeo.castillo@brgy.com'),
(107, 29, 'Rosa', 'Castillo', '', 45, 1, 0, 'Spouse', '09451234568', 'rosa.castillo@brgy.com'),
(108, 29, 'Antonio', 'Castillo', '', 20, 1, 0, 'Child', '09451234569', 'antonio.castillo@brgy.com'),
(109, 29, 'Teresa', 'Castillo', '', 17, 0, 0, 'Child', NULL, 'teresa.castillo@brgy.com'),
(110, 30, 'Julieta', 'Villanueva', '', 41, 1, 1, 'Head', '09461234567', 'julieta.villanueva@brgy.com'),
(111, 30, 'Manuel', 'Villanueva', '', 43, 1, 0, 'Spouse', '09461234568', 'manuel.villanueva@brgy.com'),
(112, 30, 'Elena', 'Villanueva', '', 16, 0, 0, 'Child', NULL, 'elena.villanueva@brgy.com'),
(113, 30, 'Luz', 'Villanueva', '', 13, 0, 0, 'Child', NULL, 'luz.villanueva@brgy.com'),
(114, 31, 'Victor', 'Santos', '', 42, 1, 1, 'Head', '09170000001', 'victor.santos@brgy.com'),
(115, 31, 'Lilian', 'Santos', '', 38, 1, 0, 'Spouse', '09170000002', 'lilian.santos@brgy.com'),
(116, 31, 'Ronald', 'Santos', '', 15, 0, 0, 'Child', NULL, 'ronald.santos@brgy.com'),
(117, 32, 'Lilian', 'Reyes', '', 35, 1, 1, 'Head', '09170000003', 'lilian.reyes@brgy.com'),
(118, 32, 'Mark', 'Reyes', '', 8, 0, 0, 'Child', NULL, 'mark.reyes@brgy.com'),
(119, 33, 'Roberto', 'Cruz', '', 50, 1, 1, 'Head', '09170000004', 'roberto.cruz@brgy.com'),
(120, 33, 'Cecilia', 'Cruz', '', 48, 1, 0, 'Spouse', '09170000005', 'cecilia.cruz@brgy.com'),
(121, 33, 'Paolo', 'Cruz', '', 22, 1, 0, 'Child', '09170000006', 'paolo.cruz@brgy.com'),
(122, 33, 'Sophia', 'Cruz', '', 18, 1, 0, 'Child', '09170000007', 'sophia.cruz@brgy.com'),
(123, 34, 'Gloria', 'Garcia', '', 60, 1, 1, 'Head', '09170000008', 'gloria.garcia@brgy.com'),
(124, 34, 'Ramon', 'Garcia', '', 62, 1, 0, 'Spouse', '09170000009', 'ramon.garcia@brgy.com'),
(125, 35, 'Eduardo', 'Mendoza', '', 45, 1, 1, 'Head', '09170000010', 'eduardo.mendoza@brgy.com'),
(126, 35, 'Marilou', 'Mendoza', '', 43, 1, 0, 'Spouse', '09170000011', 'marilou.mendoza@brgy.com'),
(127, 35, 'John', 'Mendoza', '', 12, 0, 0, 'Child', NULL, 'john.mendoza@brgy.com'),
(128, 35, 'Anna', 'Mendoza', '', 9, 0, 0, 'Child', NULL, 'anna.mendoza@brgy.com'),
(129, 36, 'Fe', 'Flores', '', 55, 1, 1, 'Head', '09170000012', 'fe.flores@brgy.com'),
(130, 36, 'Nestor', 'Flores', '', 58, 1, 0, 'Spouse', '09170000013', 'nestor.flores@brgy.com'),
(131, 37, 'Domingo', 'Torres', '', 40, 1, 1, 'Head', '09170000014', 'domingo.torres@brgy.com'),
(132, 37, 'Luzviminda', 'Torres', '', 39, 1, 0, 'Spouse', '09170000015', 'luzviminda.torres@brgy.com'),
(133, 37, 'Jericho', 'Torres', '', 10, 0, 0, 'Child', NULL, 'jericho.torres@brgy.com'),
(134, 38, 'Evelyn', 'Aquino', '', 33, 1, 1, 'Head', '09170000016', 'evelyn.aquino@brgy.com'),
(135, 38, 'Kevin', 'Aquino', '', 5, 0, 0, 'Child', NULL, 'kevin.aquino@brgy.com'),
(136, 39, 'Rolando', 'Castillo', '', 48, 1, 1, 'Head', '09170000017', 'rolando.castillo@brgy.com'),
(137, 39, 'Belinda', 'Castillo', '', 46, 1, 0, 'Spouse', '09170000018', 'belinda.castillo@brgy.com'),
(138, 39, 'Maricel', 'Castillo', '', 20, 1, 0, 'Child', '09170000019', 'maricel.castillo@brgy.com'),
(139, 40, 'Nenita', 'Villanueva', '', 52, 1, 1, 'Head', '09170000020', 'nenita.villanueva@brgy.com'),
(140, 41, 'Rogelio', 'Gonzales', '', 38, 1, 1, 'Head', '09170000021', 'rogelio.gonzales@brgy.com'),
(141, 41, 'Josephine', 'Gonzales', '', 36, 1, 0, 'Spouse', '09170000022', 'josephine.gonzales@brgy.com'),
(142, 42, 'Anita', 'Ramirez', '', 44, 1, 1, 'Head', '09170000023', 'anita.ramirez@brgy.com'),
(143, 42, 'Ramoncito', 'Ramirez', '', 46, 1, 0, 'Spouse', '09170000024', 'ramoncito.ramirez@brgy.com'),
(144, 42, 'Grace', 'Ramirez', '', 14, 0, 0, 'Child', NULL, 'grace.ramirez@brgy.com'),
(145, 43, 'Ricardo', 'Dela Cruz', '', 30, 1, 1, 'Head', '09170000025', 'ricardo.delacruz@brgy.com'),
(146, 43, 'Michelle', 'Dela Cruz', '', 28, 1, 0, 'Spouse', '09170000026', 'michelle.delacruz@brgy.com'),
(147, 43, 'Angelo', 'Dela Cruz', '', 3, 0, 0, 'Child', NULL, 'angelo.delacruz@brgy.com'),
(148, 44, 'Virginia', 'Fernandez', '', 65, 1, 1, 'Head', '09170000027', 'virginia.fernandez@brgy.com'),
(149, 45, 'Orlando', 'Lopez', '', 41, 1, 1, 'Head', '09170000028', 'orlando.lopez@brgy.com'),
(150, 45, 'Cynthia', 'Lopez', '', 39, 1, 0, 'Spouse', '09170000029', 'cynthia.lopez@brgy.com'),
(151, 45, 'Bryan', 'Lopez', '', 11, 0, 0, 'Child', NULL, 'bryan.lopez@brgy.com'),
(152, 46, 'Zenaida', 'Rivera', '', 49, 1, 1, 'Head', '09170000030', 'zenaida.rivera@brgy.com'),
(153, 46, 'Mario', 'Rivera', '', 51, 1, 0, 'Spouse', '09170000031', 'mario.rivera@brgy.com'),
(154, 47, 'Reynaldo', 'Gomez', '', 36, 1, 1, 'Head', '09170000032', 'reynaldo.gomez@brgy.com'),
(155, 47, 'Aurora', 'Gomez', '', 34, 1, 0, 'Spouse', '09170000033', 'aurora.gomez@brgy.com'),
(156, 47, 'Jay', 'Gomez', '', 7, 0, 0, 'Child', NULL, 'jay.gomez@brgy.com'),
(157, 48, 'Leticia', 'Diaz', '', 58, 1, 1, 'Head', '09170000034', 'leticia.diaz@brgy.com'),
(158, 49, 'Armando', 'Alvarez', '', 44, 1, 1, 'Head', '09170000035', 'armando.alvarez@brgy.com'),
(159, 49, 'Remedios', 'Alvarez', '', 42, 1, 0, 'Spouse', '09170000036', 'remedios.alvarez@brgy.com'),
(160, 50, 'Rosario', 'Navarro', '', 37, 1, 1, 'Head', '09170000037', 'rosario.navarro@brgy.com'),
(161, 50, 'Allan', 'Navarro', '', 13, 0, 0, 'Child', NULL, 'allan.navarro@brgy.com'),
(162, 51, 'Jaime', 'Santos', '', 53, 1, 1, 'Head', '09170000038', 'jaime.santos@brgy.com'),
(163, 51, 'Lucia', 'Santos', '', 51, 1, 0, 'Spouse', '09170000039', 'lucia.santos@brgy.com'),
(164, 52, 'Milagros', 'Reyes', '', 47, 1, 1, 'Head', '09170000040', 'milagros.reyes@brgy.com'),
(165, 53, 'Leopoldo', 'Cruz', '', 39, 1, 1, 'Head', '09170000041', 'leopoldo.cruz@brgy.com'),
(166, 53, 'Teresita', 'Cruz', '', 37, 1, 0, 'Spouse', '09170000042', 'teresita.cruz@brgy.com'),
(167, 54, 'Concepcion', 'Garcia', '', 61, 1, 1, 'Head', '09170000043', 'concepcion.garcia@brgy.com'),
(168, 55, 'Felipe', 'Mendoza', '', 46, 1, 1, 'Head', '09170000044', 'felipe.mendoza@brgy.com'),
(169, 55, 'Adelaida', 'Mendoza', '', 44, 1, 0, 'Spouse', '09170000045', 'adelaida.mendoza@brgy.com'),
(170, 55, 'Joseph', 'Mendoza', '', 16, 1, 0, 'Child', '09170000046', 'joseph.mendoza@brgy.com'),
(171, 56, 'Crisanta', 'Flores', '', 32, 1, 1, 'Head', '09170000047', 'crisanta.flores@brgy.com'),
(172, 57, 'Marcelo', 'Torres', '', 55, 1, 1, 'Head', '09170000048', 'marcelo.torres@brgy.com'),
(173, 57, 'Angela', 'Torres', '', 53, 1, 0, 'Spouse', '09170000049', 'angela.torres@brgy.com'),
(174, 58, 'Salvacion', 'Aquino', '', 42, 1, 1, 'Head', '09170000050', 'salvacion.aquino@brgy.com'),
(175, 58, 'Christian', 'Aquino', '', 17, 1, 0, 'Child', '09170000051', 'christian.aquino@brgy.com'),
(176, 59, 'Efren', 'Castillo', '', 49, 1, 1, 'Head', '09170000052', 'efren.castillo@brgy.com'),
(177, 59, 'Lorna', 'Castillo', '', 47, 1, 0, 'Spouse', '09170000053', 'lorna.castillo@brgy.com'),
(178, 60, 'Perla', 'Villanueva', '', 34, 1, 1, 'Head', '09170000054', 'perla.villanueva@brgy.com'),
(179, 60, 'Randy', 'Villanueva', '', 36, 1, 0, 'Spouse', '09170000055', 'randy.villanueva@brgy.com'),
(180, 60, 'Kim', 'Villanueva', '', 6, 0, 0, 'Child', NULL, 'kim.villanueva@brgy.com'),
(181, 61, 'Nicanor', 'Gonzales', '', 67, 1, 1, 'Head', '09170000056', 'nicanor.gonzales@brgy.com'),
(182, 62, 'Purificacion', 'Ramirez', '', 41, 1, 1, 'Head', '09170000057', 'purificacion.ramirez@brgy.com'),
(183, 62, 'Roderick', 'Ramirez', '', 10, 0, 0, 'Child', NULL, 'roderick.ramirez@brgy.com'),
(184, 63, 'Danilo', 'Dela Cruz', '', 38, 1, 1, 'Head', '09170000058', 'danilo.delacruz@brgy.com'),
(185, 63, 'Analyn', 'Dela Cruz', '', 35, 1, 0, 'Spouse', '09170000059', 'analyn.delacruz@brgy.com'),
(186, 64, 'Priscilla', 'Fernandez', '', 52, 1, 1, 'Head', '09170000060', 'priscilla.fernandez@brgy.com'),
(187, 65, 'Eriberto', 'Lopez', '', 44, 1, 1, 'Head', '09170000061', 'eriberto.lopez@brgy.com'),
(188, 65, 'Nora', 'Lopez', '', 42, 1, 0, 'Spouse', '09170000062', 'nora.lopez@brgy.com'),
(189, 65, 'Arvin', 'Lopez', '', 8, 0, 0, 'Child', NULL, 'arvin.lopez@brgy.com'),
(190, 66, 'Ofelia', 'Rivera', '', 59, 1, 1, 'Head', '09170000063', 'ofelia.rivera@brgy.com'),
(191, 67, 'Rene', 'Gomez', '', 33, 1, 1, 'Head', '09170000064', 'rene.gomez@brgy.com'),
(192, 67, 'Lilibeth', 'Gomez', '', 31, 1, 0, 'Spouse', '09170000065', 'lilibeth.gomez@brgy.com'),
(193, 67, 'Angel', 'Gomez', '', 4, 0, 0, 'Child', NULL, 'angel.gomez@brgy.com'),
(194, 68, 'Herminia', 'Diaz', '', 48, 1, 1, 'Head', '09170000066', 'herminia.diaz@brgy.com'),
(195, 69, 'Teodoro', 'Alvarez', '', 57, 1, 1, 'Head', '09170000067', 'teodoro.alvarez@brgy.com'),
(196, 69, 'Linda', 'Alvarez', '', 55, 1, 0, 'Spouse', '09170000068', 'linda.alvarez@brgy.com'),
(197, 70, 'Mercedes', 'Navarro', '', 43, 1, 1, 'Head', '09170000069', 'mercedes.navarro@brgy.com'),
(198, 70, 'Jerome', 'Navarro', '', 19, 1, 0, 'Child', '09170000070', 'jerome.navarro@brgy.com'),
(199, 71, 'Isidro', 'Santos', '', 61, 1, 1, 'Head', '09170000071', 'isidro.santos@brgy.com'),
(200, 72, 'Juliana', 'Reyes', '', 39, 1, 1, 'Head', '09170000072', 'juliana.reyes@brgy.com'),
(201, 72, 'Francis', 'Reyes', '', 11, 0, 0, 'Child', NULL, 'francis.reyes@brgy.com'),
(202, 73, 'Fernando', 'Cruz', '', 47, 1, 1, 'Head', '09170000073', 'fernando.cruz@brgy.com'),
(203, 73, 'Marilyn', 'Cruz', '', 45, 1, 0, 'Spouse', '09170000074', 'marilyn.cruz@brgy.com'),
(204, 74, 'Loreta', 'Garcia', '', 54, 1, 1, 'Head', '09170000075', 'loreta.garcia@brgy.com'),
(205, 75, 'Alfredo', 'Mendoza', '', 42, 1, 1, 'Head', '09170000076', 'alfredo.mendoza@brgy.com'),
(206, 75, 'Catherine', 'Mendoza', '', 40, 1, 0, 'Spouse', '09170000077', 'catherine.mendoza@brgy.com'),
(207, 75, 'Patrick', 'Mendoza', '', 13, 0, 0, 'Child', NULL, 'patrick.mendoza@brgy.com'),
(208, 76, 'Remedios', 'Flores', '', 66, 1, 1, 'Head', '09170000078', 'remedios.flores@brgy.com'),
(209, 77, 'Emmanuel', 'Torres', '', 34, 1, 1, 'Head', '09170000079', 'emmanuel.torres@brgy.com'),
(210, 77, 'Jennifer', 'Torres', '', 32, 1, 0, 'Spouse', '09170000080', 'jennifer.torres@brgy.com'),
(211, 78, 'Consuelo', 'Aquino', '', 50, 1, 1, 'Head', '09170000081', 'consuelo.aquino@brgy.com'),
(212, 79, 'Benjamin', 'Castillo', '', 38, 1, 1, 'Head', '09170000082', 'benjamin.castillo@brgy.com'),
(213, 79, 'Rebecca', 'Castillo', '', 36, 1, 0, 'Spouse', '09170000083', 'rebecca.castillo@brgy.com'),
(214, 79, 'David', 'Castillo', '', 5, 0, 0, 'Child', NULL, 'david.castillo@brgy.com'),
(215, 80, 'Violeta', 'Villanueva', '', 45, 1, 1, 'Head', '09170000084', 'violeta.villanueva@brgy.com'),
(216, 81, 'Romeo', 'Gonzales', '', 56, 1, 1, 'Head', '09170000085', 'romeo.gonzales@brgy.com'),
(217, 81, 'Corazon', 'Gonzales', '', 54, 1, 0, 'Spouse', '09170000086', 'corazon.gonzales@brgy.com'),
(218, 82, 'Soledad', 'Ramirez', '', 47, 1, 1, 'Head', '09170000087', 'soledad.ramirez@brgy.com'),
(219, 82, 'Albert', 'Ramirez', '', 21, 1, 0, 'Child', '09170000088', 'albert.ramirez@brgy.com'),
(220, 83, 'Arturo', 'Dela Cruz', '', 52, 1, 1, 'Head', '09170000089', 'arturo.delacruz@brgy.com'),
(221, 83, 'Natalia', 'Dela Cruz', '', 50, 1, 0, 'Spouse', '09170000090', 'natalia.delacruz@brgy.com'),
(222, 84, 'Adoracion', 'Fernandez', '', 44, 1, 1, 'Head', '09170000091', 'adoracion.fernandez@brgy.com'),
(223, 85, 'Ramon', 'Lopez', '', 39, 1, 1, 'Head', '09170000092', 'ramon.lopez@brgy.com'),
(224, 85, 'Marites', 'Lopez', '', 37, 1, 0, 'Spouse', '09170000093', 'marites.lopez@brgy.com'),
(225, 85, 'Jomar', 'Lopez', '', 9, 0, 0, 'Child', NULL, 'jomar.lopez@brgy.com'),
(226, 86, 'Gaudencio', 'Rivera', '', 62, 1, 1, 'Head', '09170000094', 'gaudencio.rivera@brgy.com'),
(227, 87, 'Visitacion', 'Gomez', '', 41, 1, 1, 'Head', '09170000095', 'visitacion.gomez@brgy.com'),
(228, 87, 'Rommel', 'Gomez', '', 14, 0, 0, 'Child', NULL, 'rommel.gomez@brgy.com'),
(229, 88, 'Anselmo', 'Diaz', '', 48, 1, 1, 'Head', '09170000096', 'anselmo.diaz@brgy.com'),
(230, 88, 'Cristina', 'Diaz', '', 46, 1, 0, 'Spouse', '09170000097', 'cristina.diaz@brgy.com'),
(231, 89, 'Petronila', 'Alvarez', '', 58, 1, 1, 'Head', '09170000098', 'petronila.alvarez@brgy.com'),
(232, 90, 'Hector', 'Navarro', '', 43, 1, 1, 'Head', '09170000099', 'hector.navarro@brgy.com'),
(233, 90, 'Glenda', 'Navarro', '', 41, 1, 0, 'Spouse', '09170000100', 'glenda.navarro@brgy.com'),
(234, 91, 'Jesus', 'Santos', '', 49, 1, 1, 'Head', '09170000101', 'jesus.santos@brgy.com'),
(235, 92, 'Lydia', 'Reyes', '', 37, 1, 1, 'Head', '09170000102', 'lydia.reyes@brgy.com'),
(236, 93, 'Marcelino', 'Cruz', '', 55, 1, 1, 'Head', '09170000103', 'marcelino.cruz@brgy.com'),
(237, 94, 'Rosalinda', 'Garcia', '', 44, 1, 1, 'Head', '09170000104', 'rosalinda.garcia@brgy.com'),
(238, 95, 'Severino', 'Mendoza', '', 51, 1, 1, 'Head', '09170000105', 'severino.mendoza@brgy.com'),
(239, 96, 'Felicidad', 'Flores', '', 60, 1, 1, 'Head', '09170000106', 'felicidad.flores@brgy.com'),
(240, 97, 'Abelardo', 'Torres', '', 46, 1, 1, 'Head', '09170000107', 'abelardo.torres@brgy.com'),
(241, 98, 'Beatriz', 'Aquino', '', 39, 1, 1, 'Head', '09170000108', 'beatriz.aquino@brgy.com'),
(242, 99, 'Carlos', 'Castillo', '', 48, 1, 1, 'Head', '09170000109', 'carlos.castillo@brgy.com'),
(243, 100, 'Dolores', 'Villanueva', '', 57, 1, 1, 'Head', '09170000110', 'dolores.villanueva@brgy.com'),
(244, 91, 'Ester', 'Santos', '', 47, 1, 0, 'Spouse', '09170000111', 'ester.santos@brgy.com'),
(245, 92, 'Rafael', 'Reyes', '', 40, 1, 0, 'Spouse', '09170000112', 'rafael.reyes@brgy.com'),
(246, 93, 'Celia', 'Cruz', '', 53, 1, 0, 'Spouse', '09170000113', 'celia.cruz@brgy.com'),
(247, 94, 'Rogelio', 'Garcia', '', 48, 1, 0, 'Spouse', '09170000114', 'rogelio.garcia@brgy.com'),
(248, 95, 'Luz', 'Mendoza', '', 49, 1, 0, 'Spouse', '09170000115', 'luz.mendoza@brgy.com'),
(249, 96, 'Pedro', 'Flores', '', 62, 1, 0, 'Spouse', '09170000116', 'pedro.flores@brgy.com'),
(250, 97, 'Cora', 'Torres', '', 44, 1, 0, 'Spouse', '09170000117', 'cora.torres@brgy.com'),
(251, 98, 'Rolando', 'Aquino', '', 42, 1, 0, 'Spouse', '09170000118', 'rolando.aquino@brgy.com'),
(252, 99, 'Norma', 'Castillo', '', 46, 1, 0, 'Spouse', '09170000119', 'norma.castillo@brgy.com'),
(253, 100, 'Ernesto', 'Villanueva', '', 60, 1, 0, 'Spouse', '09170000120', 'ernesto.villanueva@brgy.com');

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
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_request`
--

INSERT INTO `service_request` (`request_id`, `household_id`, `service_id`, `ref_no`, `purpose`, `date_submitted`, `status`, `delivery_method`) VALUES
(1, 1, 1, 'BRG-20240115-1001', 'Employment requirement', '2024-01-15 01:30:00', 'completed', 'pickup'),
(2, 2, 7, 'BRG-20240220-1002', 'School enrollment', '2024-02-20 06:15:00', 'completed', 'pickup'),
(3, 3, 11, 'BRG-20240310-1003', 'Job application', '2024-03-10 02:45:00', 'completed', 'delivery'),
(4, 4, 4, 'BRG-20240405-1004', 'Sari-sari store permit', '2024-04-05 08:30:00', 'completed', 'pickup'),
(5, 5, 2, 'BRG-20240518-1005', 'Government ID requirement', '2024-05-18 03:20:00', 'completed', 'pickup'),
(6, 6, 8, 'BRG-20240622-1006', 'First time job seeker', '2024-06-22 01:10:00', 'completed', 'pickup'),
(7, 7, 3, 'BRG-20240730-1007', 'House renovation', '2024-07-30 06:55:00', 'approved', 'delivery'),
(8, 8, 5, 'BRG-20240814-1008', 'Annual tax payment', '2024-08-14 00:25:00', 'completed', 'pickup'),
(9, 9, 6, 'BRG-20240903-1009', 'School requirement', '2024-09-03 05:40:00', 'completed', 'pickup'),
(10, 10, 10, 'BRG-20241019-1010', 'Financial assistance', '2024-10-19 08:15:00', 'completed', 'pickup'),
(11, 11, 9, 'BRG-20241111-1011', 'Food business requirement', '2024-11-11 03:30:00', 'completed', 'delivery'),
(12, 12, 12, 'BRG-20241205-1012', 'Provincial travel', '2024-12-05 01:05:00', 'completed', 'pickup'),
(13, 13, 1, 'BRG-20250120-1013', 'Employment requirement', '2025-01-20 06:20:00', 'completed', 'pickup'),
(14, 14, 7, 'BRG-20250214-1014', 'School enrollment', '2025-02-14 02:10:00', 'completed', 'delivery'),
(15, 15, 11, 'BRG-20250308-1015', 'Job application', '2025-03-08 07:45:00', 'approved', 'pickup'),
(16, 16, 2, 'BRG-20250422-1016', 'Government ID requirement', '2025-04-22 00:30:00', 'completed', 'pickup'),
(17, 17, 4, 'BRG-20250517-1017', 'Food stall permit', '2025-05-17 04:25:00', 'processing', 'pickup'),
(18, 18, 8, 'BRG-20250630-1018', 'First time job seeker', '2025-06-30 09:40:00', 'completed', 'pickup'),
(19, 19, 3, 'BRG-20250725-1019', 'Fence construction', '2025-07-25 01:55:00', 'pending', 'delivery'),
(20, 20, 5, 'BRG-20250812-1020', 'Annual tax payment', '2025-08-12 03:05:00', 'completed', 'pickup'),
(21, 21, 6, 'BRG-20250905-1021', 'School requirement', '2025-09-05 06:50:00', 'approved', 'pickup'),
(22, 22, 10, 'BRG-20251018-1022', 'Financial assistance', '2025-10-18 02:15:00', 'completed', 'pickup'),
(23, 23, 9, 'BRG-20251122-1023', 'Food business requirement', '2025-11-22 08:35:00', 'pending', 'delivery'),
(24, 24, 12, 'BRG-20251215-1024', 'Provincial travel', '2025-12-15 05:25:00', 'completed', 'pickup'),
(25, 25, 1, 'BRG-20251220-1025', 'Employment requirement', '2025-12-20 02:00:00', 'processing', 'pickup'),
(26, 26, 7, 'BRG-20251225-1026', 'School enrollment', '2025-12-25 01:30:00', 'pending', 'pickup'),
(27, 27, 11, 'BRG-20260110-1027', 'Job application', '2026-01-10 01:40:00', 'approved', 'delivery'),
(28, 28, 2, 'BRG-20260208-1028', 'Government ID requirement', '2026-02-08 06:55:00', 'processing', 'pickup'),
(29, 29, 4, 'BRG-20260314-1029', 'Retail store permit', '2026-03-14 03:20:00', 'pending', 'pickup'),
(30, 30, 8, 'BRG-20260402-1030', 'First time job seeker', '2026-04-02 07:10:00', 'approved', 'pickup'),
(31, 1, 3, 'BRG-20260410-1031', 'Garage construction', '2026-04-10 05:25:00', 'processing', 'delivery'),
(32, 2, 5, 'BRG-20260415-1032', 'Annual tax payment', '2026-04-15 00:45:00', 'pending', 'pickup'),
(33, 3, 6, 'BRG-20260420-1033', 'School requirement', '2026-04-20 08:20:00', 'approved', 'pickup'),
(34, 4, 10, 'BRG-20260425-1034', 'Financial assistance', '2026-04-25 02:30:00', 'processing', 'pickup'),
(35, 5, 9, 'BRG-20260428-1035', 'Food business requirement', '2026-04-28 06:15:00', 'pending', 'delivery'),
(36, 6, 12, 'BRG-20260501-1036', 'Provincial travel', '2026-05-01 00:45:00', 'approved', 'pickup'),
(37, 7, 1, 'BRG-20260503-1037', 'Employment requirement', '2026-05-03 03:30:00', 'processing', 'pickup'),
(38, 8, 7, 'BRG-20260505-1038', 'School enrollment', '2026-05-05 01:15:00', 'pending', 'delivery'),
(39, 9, 11, 'BRG-20260507-1039', 'Job application', '2026-05-07 06:00:00', 'approved', 'pickup'),
(40, 10, 2, 'BRG-20260509-1040', 'Government ID requirement', '2026-05-09 02:45:00', 'processing', 'pickup'),
(41, 11, 4, 'BRG-20260511-1041', 'Online shop permit', '2026-05-11 05:20:00', 'pending', 'pickup'),
(42, 12, 8, 'BRG-20260513-1042', 'First time job seeker', '2026-05-13 07:30:00', 'approved', 'pickup'),
(43, 13, 3, 'BRG-20260515-1043', 'House extension', '2026-05-15 00:00:00', 'processing', 'delivery'),
(44, 14, 5, 'BRG-20260517-1044', 'Annual tax payment', '2026-05-17 08:45:00', 'pending', 'pickup'),
(45, 15, 6, 'BRG-20260519-1045', 'School requirement', '2026-05-19 01:30:00', 'approved', 'pickup'),
(46, 16, 10, 'BRG-20260521-1046', 'Financial assistance', '2026-05-21 03:00:00', 'processing', 'pickup'),
(47, 17, 9, 'BRG-20260522-1047', 'Food business requirement', '2026-05-22 06:30:00', 'pending', 'delivery'),
(48, 18, 12, 'BRG-20260523-1048', 'Provincial travel', '2026-05-23 02:15:00', 'approved', 'pickup'),
(49, 19, 1, 'BRG-20260524-1049', 'Employment requirement', '2026-05-24 05:45:00', 'processing', 'pickup'),
(50, 20, 7, 'BRG-20260525-1050', 'School enrollment', '2026-05-25 01:00:00', 'pending', 'pickup'),
(51, 21, 11, 'BRG-20260526-1051', 'Job application', '2026-05-26 00:30:00', 'approved', 'delivery'),
(52, 22, 2, 'BRG-20260526-1052', 'Government ID requirement', '2026-05-26 02:20:00', 'pending', 'pickup'),
(53, 23, 4, 'BRG-20260526-1053', 'Business permit renewal', '2026-05-26 05:00:00', 'processing', 'pickup'),
(54, 24, 8, 'BRG-20260526-1054', 'First time job seeker', '2026-05-26 07:45:00', 'pending', 'pickup'),
(55, 25, 3, 'BRG-20260527-1055', 'Roof repair', '2026-05-27 01:15:00', 'pending', 'delivery'),
(56, 26, 5, 'BRG-20260527-1056', 'Annual tax payment', '2026-05-27 03:30:00', 'pending', 'pickup'),
(57, 27, 6, 'BRG-20260527-1057', 'School requirement', '2026-05-27 06:00:00', 'pending', 'pickup'),
(58, 28, 10, 'BRG-20260527-1058', 'Financial assistance', '2026-05-27 08:20:00', 'pending', 'pickup'),
(59, 31, 1, 'BRG-20240110-2001', 'Employment requirement', '2024-01-10 00:30:00', 'completed', 'pickup'),
(60, 32, 2, 'BRG-20240120-2002', 'School enrollment', '2024-01-20 01:45:00', 'completed', 'pickup'),
(61, 33, 3, 'BRG-20240205-2003', 'House renovation', '2024-02-05 02:15:00', 'completed', 'delivery'),
(62, 34, 4, 'BRG-20240215-2004', 'Store permit', '2024-02-15 03:30:00', 'completed', 'pickup'),
(63, 35, 5, 'BRG-20240228-2005', 'Annual tax', '2024-02-28 05:45:00', 'completed', 'pickup'),
(64, 36, 6, 'BRG-20240310-2006', 'School requirement', '2024-03-10 06:00:00', 'completed', 'pickup'),
(65, 37, 7, 'BRG-20240320-2007', 'Proof of residence', '2024-03-20 00:00:00', 'completed', 'pickup'),
(66, 38, 8, 'BRG-20240401-2008', 'First time job', '2024-04-01 01:15:00', 'completed', 'pickup'),
(67, 39, 9, 'BRG-20240412-2009', 'Food business', '2024-04-12 02:30:00', 'completed', 'delivery'),
(68, 40, 10, 'BRG-20240425-2010', 'Financial aid', '2024-04-25 03:45:00', 'completed', 'pickup'),
(69, 41, 11, 'BRG-20240505-2011', 'Police clearance', '2024-05-05 05:00:00', 'completed', 'pickup'),
(70, 42, 12, 'BRG-20240518-2012', 'Provincial travel', '2024-05-18 06:15:00', 'completed', 'pickup'),
(71, 43, 1, 'BRG-20240601-2013', 'Job application', '2024-06-01 00:30:00', 'completed', 'pickup'),
(72, 44, 2, 'BRG-20240615-2014', 'ID requirement', '2024-06-15 01:45:00', 'completed', 'pickup'),
(73, 45, 3, 'BRG-20240628-2015', 'Fence construction', '2024-06-28 02:30:00', 'approved', 'delivery'),
(74, 46, 4, 'BRG-20240710-2016', 'Business renewal', '2024-07-10 03:00:00', 'completed', 'pickup'),
(75, 47, 5, 'BRG-20240722-2017', 'Community tax', '2024-07-22 05:30:00', 'completed', 'pickup'),
(76, 48, 6, 'BRG-20240805-2018', 'Good moral', '2024-08-05 06:45:00', 'completed', 'pickup'),
(77, 49, 7, 'BRG-20240818-2019', 'Residency proof', '2024-08-18 00:15:00', 'completed', 'pickup'),
(78, 50, 8, 'BRG-20240901-2020', 'First time job seeker', '2024-09-01 01:30:00', 'completed', 'pickup'),
(79, 51, 9, 'BRG-20240915-2021', 'Health certificate', '2024-09-15 02:45:00', 'processing', 'delivery'),
(80, 52, 10, 'BRG-20240928-2022', 'Indigency', '2024-09-28 04:00:00', 'completed', 'pickup'),
(81, 53, 11, 'BRG-20241010-2023', 'Police clearance', '2024-10-10 05:15:00', 'completed', 'pickup'),
(82, 54, 12, 'BRG-20241022-2024', 'Travel pass', '2024-10-22 06:30:00', 'completed', 'pickup'),
(83, 55, 1, 'BRG-20241105-2025', 'Employment', '2024-11-05 00:00:00', 'approved', 'pickup'),
(84, 56, 2, 'BRG-20241118-2026', 'Barangay ID', '2024-11-18 01:15:00', 'completed', 'pickup'),
(85, 57, 3, 'BRG-20241201-2027', 'Building permit', '2024-12-01 02:30:00', 'pending', 'delivery'),
(86, 58, 4, 'BRG-20241215-2028', 'Business permit', '2024-12-15 03:45:00', 'completed', 'pickup'),
(87, 59, 5, 'BRG-20241220-2029', 'Cedula', '2024-12-20 05:00:00', 'completed', 'pickup'),
(88, 60, 6, 'BRG-20241228-2030', 'Good moral', '2024-12-28 06:15:00', 'completed', 'pickup'),
(89, 61, 7, 'BRG-20250105-2031', 'Residency', '2025-01-05 00:30:00', 'completed', 'pickup'),
(90, 62, 8, 'BRG-20250115-2032', 'First time job', '2025-01-15 01:45:00', 'completed', 'pickup'),
(91, 63, 9, 'BRG-20250128-2033', 'Health cert', '2025-01-28 02:00:00', 'approved', 'delivery'),
(92, 64, 10, 'BRG-20250210-2034', 'Indigency', '2025-02-10 03:15:00', 'completed', 'pickup'),
(93, 65, 11, 'BRG-20250222-2035', 'Police clearance', '2025-02-22 04:30:00', 'processing', 'pickup'),
(94, 66, 12, 'BRG-20250305-2036', 'Travel pass', '2025-03-05 05:45:00', 'completed', 'pickup'),
(95, 67, 1, 'BRG-20250318-2037', 'Employment', '2025-03-18 06:00:00', 'pending', 'pickup'),
(96, 68, 2, 'BRG-20250401-2038', 'ID requirement', '2025-04-01 00:00:00', 'approved', 'pickup'),
(97, 69, 3, 'BRG-20250412-2039', 'Renovation', '2025-04-12 01:15:00', 'processing', 'delivery'),
(98, 70, 4, 'BRG-20250425-2040', 'Store permit', '2025-04-25 02:30:00', 'completed', 'pickup'),
(99, 71, 5, 'BRG-20250508-2041', 'Tax payment', '2025-05-08 03:45:00', 'completed', 'pickup'),
(100, 72, 6, 'BRG-20250520-2042', 'School req', '2025-05-20 05:00:00', 'approved', 'pickup'),
(101, 73, 7, 'BRG-20250602-2043', 'Residency', '2025-06-02 06:15:00', 'completed', 'pickup'),
(102, 74, 8, 'BRG-20250615-2044', 'First time job', '2025-06-15 00:30:00', 'pending', 'pickup'),
(103, 75, 9, 'BRG-20250628-2045', 'Health cert', '2025-06-28 01:45:00', 'processing', 'delivery'),
(104, 76, 10, 'BRG-20250710-2046', 'Financial aid', '2025-07-10 02:00:00', 'completed', 'pickup'),
(105, 77, 11, 'BRG-20250722-2047', 'Police clearance', '2025-07-22 03:15:00', 'approved', 'pickup'),
(106, 78, 12, 'BRG-20250805-2048', 'Travel', '2025-08-05 04:30:00', 'completed', 'pickup'),
(107, 79, 1, 'BRG-20250818-2049', 'Job app', '2025-08-18 05:45:00', 'processing', 'pickup'),
(108, 80, 2, 'BRG-20250901-2050', 'ID', '2025-09-01 06:00:00', 'pending', 'pickup'),
(109, 81, 3, 'BRG-20250914-2051', 'Building', '2025-09-14 00:00:00', 'completed', 'delivery'),
(110, 82, 4, 'BRG-20250928-2052', 'Business', '2025-09-28 01:15:00', 'approved', 'pickup'),
(111, 83, 5, 'BRG-20251010-2053', 'Cedula', '2025-10-10 02:30:00', 'completed', 'pickup'),
(112, 84, 6, 'BRG-20251022-2054', 'Good moral', '2025-10-22 03:45:00', 'processing', 'pickup'),
(113, 85, 7, 'BRG-20251105-2055', 'Residency', '2025-11-05 05:00:00', 'completed', 'pickup'),
(114, 86, 8, 'BRG-20251118-2056', 'First time job', '2025-11-18 06:15:00', 'pending', 'pickup'),
(115, 87, 9, 'BRG-20251201-2057', 'Health cert', '2025-12-01 00:30:00', 'approved', 'delivery'),
(116, 88, 10, 'BRG-20251215-2058', 'Indigency', '2025-12-15 01:45:00', 'completed', 'pickup'),
(117, 89, 11, 'BRG-20251220-2059', 'Police', '2025-12-20 02:00:00', 'processing', 'pickup'),
(118, 90, 12, 'BRG-20251228-2060', 'Travel', '2025-12-28 03:15:00', 'completed', 'pickup'),
(119, 91, 1, 'BRG-20260105-2061', 'Employment', '2026-01-05 00:00:00', 'pending', 'pickup'),
(120, 92, 2, 'BRG-20260115-2062', 'ID', '2026-01-15 01:15:00', 'approved', 'pickup'),
(121, 93, 3, 'BRG-20260128-2063', 'Renovation', '2026-01-28 02:30:00', 'processing', 'delivery'),
(122, 94, 4, 'BRG-20260210-2064', 'Permit', '2026-02-10 03:45:00', 'pending', 'pickup'),
(123, 95, 5, 'BRG-20260222-2065', 'Tax', '2026-02-22 05:00:00', 'approved', 'pickup'),
(124, 96, 6, 'BRG-20260305-2066', 'School', '2026-03-05 06:15:00', 'processing', 'pickup'),
(125, 97, 7, 'BRG-20260318-2067', 'Residency', '2026-03-18 00:30:00', 'completed', 'pickup'),
(126, 98, 8, 'BRG-20260401-2068', 'First time job', '2026-04-01 01:45:00', 'pending', 'pickup'),
(127, 99, 9, 'BRG-20260415-2069', 'Health', '2026-04-15 02:00:00', 'approved', 'delivery'),
(128, 100, 10, 'BRG-20260428-2070', 'Indigency', '2026-04-28 03:15:00', 'processing', 'pickup'),
(129, 31, 11, 'BRG-20260502-2071', 'Police clearance', '2026-05-02 04:30:00', 'pending', 'pickup'),
(130, 32, 12, 'BRG-20260505-2072', 'Travel pass', '2026-05-05 05:45:00', 'approved', 'pickup'),
(131, 33, 1, 'BRG-20260508-2073', 'Job application', '2026-05-08 06:00:00', 'processing', 'pickup'),
(132, 34, 2, 'BRG-20260511-2074', 'Barangay ID', '2026-05-11 00:15:00', 'pending', 'pickup'),
(133, 35, 3, 'BRG-20260514-2075', 'Building permit', '2026-05-14 01:30:00', 'approved', 'delivery'),
(134, 36, 4, 'BRG-20260517-2076', 'Business permit', '2026-05-17 02:45:00', 'processing', 'pickup'),
(135, 37, 5, 'BRG-20260520-2077', 'Community tax', '2026-05-20 04:00:00', 'pending', 'pickup'),
(136, 38, 6, 'BRG-20260523-2078', 'Good moral', '2026-05-23 05:15:00', 'approved', 'pickup'),
(137, 39, 7, 'BRG-20260525-2079', 'Residency', '2026-05-25 06:30:00', 'completed', 'pickup'),
(138, 40, 8, 'BRG-20260527-2080', 'First time job', '2026-05-27 00:00:00', 'pending', 'pickup'),
(139, 41, 9, 'BRG-20260527-2081', 'Health certificate', '2026-05-27 01:15:00', 'processing', 'delivery'),
(140, 42, 10, 'BRG-20260527-2082', 'Indigency', '2026-05-27 02:30:00', 'pending', 'pickup'),
(141, 43, 11, 'BRG-20260527-2083', 'Police', '2026-05-27 03:45:00', 'approved', 'pickup'),
(142, 44, 12, 'BRG-20260527-2084', 'Travel', '2026-05-27 05:00:00', 'pending', 'pickup'),
(143, 45, 1, 'BRG-20260527-2085', 'Employment', '2026-05-27 06:15:00', 'processing', 'pickup'),
(144, 46, 2, 'BRG-20260527-2086', 'ID', '2026-05-27 07:30:00', 'pending', 'pickup'),
(145, 47, 3, 'BRG-20260527-2087', 'Permit', '2026-05-27 00:45:00', 'approved', 'delivery'),
(146, 48, 4, 'BRG-20260527-2088', 'Business', '2026-05-27 02:00:00', 'pending', 'pickup'),
(147, 49, 5, 'BRG-20260527-2089', 'Tax', '2026-05-27 03:15:00', 'processing', 'pickup'),
(148, 50, 6, 'BRG-20260527-2090', 'Moral', '2026-05-27 04:30:00', 'pending', 'pickup');

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
`total_households` bigint
,`total_residents` bigint
,`total_requests` bigint
,`total_complaints` bigint
,`pending_requests` decimal(23,0)
,`pending_complaints` decimal(23,0)
,`total_revenue` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_monthly_requests`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_monthly_requests`;
CREATE TABLE IF NOT EXISTS `vw_monthly_requests` (
`month` varchar(7)
,`total_requests` bigint
,`completed` decimal(23,0)
,`pending` decimal(23,0)
,`rejected` decimal(23,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_resident_requests`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vw_resident_requests`;
CREATE TABLE IF NOT EXISTS `vw_resident_requests` (
`request_id` int
,`household_id` int
,`service_id` int
,`ref_no` varchar(50)
,`purpose` text
,`date_submitted` timestamp
,`status` enum('pending','approved','processing','completed','rejected')
,`delivery_method` enum('pickup','delivery')
,`service_name` varchar(100)
,`base_price` decimal(10,2)
,`is_paid` tinyint(1)
,`total_amount` decimal(10,2)
,`payment_method` enum('cash','gcash','bank_transfer')
,`resident_id` int
,`resident_name` varchar(101)
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

-- --------------------------------------------------------

--
-- Structure for view `vw_resident_requests`
--
DROP TABLE IF EXISTS `vw_resident_requests`;

DROP VIEW IF EXISTS `vw_resident_requests`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_resident_requests`  AS SELECT `sr`.`request_id` AS `request_id`, `sr`.`household_id` AS `household_id`, `sr`.`service_id` AS `service_id`, `sr`.`ref_no` AS `ref_no`, `sr`.`purpose` AS `purpose`, `sr`.`date_submitted` AS `date_submitted`, `sr`.`status` AS `status`, `sr`.`delivery_method` AS `delivery_method`, `s`.`service_name` AS `service_name`, `s`.`base_price` AS `base_price`, `p`.`is_paid` AS `is_paid`, `p`.`total_amount` AS `total_amount`, `p`.`payment_method` AS `payment_method`, `r`.`resident_id` AS `resident_id`, concat(`r`.`first_name`,' ',`r`.`last_name`) AS `resident_name` FROM (((`service_request` `sr` join `service` `s` on((`sr`.`service_id` = `s`.`service_id`))) left join `payment` `p` on((`sr`.`request_id` = `p`.`request_id`))) join `resident` `r` on((`sr`.`household_id` = `r`.`household_id`))) WHERE (`r`.`is_head` = 1) ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
