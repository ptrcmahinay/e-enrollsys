-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2026 at 05:43 PM
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
-- Database: `enrollmentsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_terms`
--

CREATE TABLE `academic_terms` (
  `id` int(11) NOT NULL,
  `academic_year_id` int(11) DEFAULT NULL,
  `semester` enum('1','2','Mid') NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_terms`
--

INSERT INTO `academic_terms` (`id`, `academic_year_id`, `semester`, `is_active`, `created_at`, `updated_at`, `start_date`, `end_date`) VALUES
(3, NULL, '', 0, '2026-02-08 07:18:38', '2026-02-08 08:11:41', NULL, NULL),
(5, 1, '2', 1, '2026-02-08 10:06:56', '2026-02-08 10:06:56', '2026-02-02', '2026-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `academic_years`
--

CREATE TABLE `academic_years` (
  `id` int(11) NOT NULL,
  `year_label` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_years`
--

INSERT INTO `academic_years` (`id`, `year_label`, `start_date`, `end_date`, `is_active`) VALUES
(1, '2025-2026', '2025-09-01', '2026-06-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `dept_id` int(11) NOT NULL,
  `department_code` varchar(20) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`dept_id`, `department_code`, `department_name`, `logo_path`, `status`) VALUES
(1, 'ITD', 'Information Technology Department', NULL, 'active'),
(2, 'TED', 'Teachers Education Department', NULL, 'active'),
(3, 'MD', 'Management Department', NULL, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `curriculum_id` int(11) NOT NULL,
  `grade` varchar(5) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `instructor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission_name`) VALUES
(5, 'view_admin_dashboard'),
(4, 'view_cashier_dashboard'),
(2, 'view_instructor_dashboard'),
(3, 'view_registrar_dashboard'),
(1, 'view_student_dashboard');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `programs_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `program_code` varchar(20) NOT NULL,
  `program_name` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`programs_id`, `department_id`, `program_code`, `program_name`, `status`) VALUES
(1, 1, 'BSIT', 'Bachelor of Science in Information Technology', 'active'),
(2, 1, 'BSCS', 'Bachelor of Science in Computer Science', 'active'),
(3, 2, 'BEED', 'Bachelor of Elementary Education', 'active'),
(4, 3, 'BSBA', 'Bachelor of Science in Business Administration', 'active'),
(5, 3, 'BSHM', 'Bachelor of Science in Hopitality Management', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `program_curriculum`
--

CREATE TABLE `program_curriculum` (
  `curriculum_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `year_level` char(10) NOT NULL,
  `semester` enum('1st','2nd') DEFAULT NULL,
  `prerequisite_subject_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_curriculum`
--

INSERT INTO `program_curriculum` (`curriculum_id`, `program_id`, `subject_id`, `year_level`, `semester`, `prerequisite_subject_id`) VALUES
(1, 1, 1, '1', '1st', NULL),
(2, 1, 2, '1', '1st', NULL),
(3, 1, 3, '1', '1st', NULL),
(4, 1, 4, '1', '1st', NULL),
(5, 1, 5, '1', '1st', NULL),
(6, 1, 6, '1', '1st', NULL),
(7, 1, 7, '1', '1st', NULL),
(8, 1, 8, '1', '1st', NULL),
(9, 1, 9, '1', '2nd', NULL),
(10, 1, 10, '1', '2nd', NULL),
(11, 1, 11, '1', '2nd', 3),
(12, 1, 12, '1', '2nd', NULL),
(13, 1, 13, '1', '2nd', 6),
(14, 1, 14, '1', '2nd', 5),
(15, 1, 15, '1', '2nd', 7),
(16, 1, 16, '1', '2nd', 8),
(17, 1, 30, '2', '1st', NULL),
(18, 1, 31, '2', '1st', NULL),
(19, 1, 32, '2', '1st', NULL),
(20, 1, 33, '2', '1st', 11),
(21, 1, 34, '2', '1st', 13),
(22, 1, 35, '2', '1st', 13),
(23, 1, 36, '2', '1st', 13),
(24, 1, 37, '2', '1st', 7),
(25, 1, 38, '2', '2nd', NULL),
(26, 1, 39, '2', '2nd', 36),
(27, 1, 40, '2', '2nd', 36),
(28, 1, 41, '2', '2nd', NULL),
(29, 1, 42, '2', '2nd', 35),
(30, 1, 43, '2', '2nd', NULL),
(31, 1, 44, '2', '2nd', 7),
(32, 1, 45, 'Mid Year', NULL, NULL),
(33, 1, 46, 'Mid Year', NULL, 40),
(34, 1, 47, '3', '1st', NULL),
(35, 1, 48, '3', '1st', 56),
(36, 1, 49, '3', '1st', 34),
(37, 1, 50, '3', '1st', NULL),
(38, 1, 51, '3', '1st', 42),
(39, 1, 52, '3', '1st', NULL),
(40, 1, 53, '3', '2nd', 30),
(41, 1, 54, '3', '2nd', 45),
(42, 1, 55, '3', '2nd', 47),
(43, 1, 56, '3', '2nd', 14),
(44, 1, 57, '3', '2nd', 48),
(45, 1, 58, '3', '2nd', 49),
(46, 1, 59, '3', '2nd', 52),
(47, 1, 60, '4', '1st', NULL),
(48, 1, 61, '4', '1st', 40),
(49, 1, 62, '4', '1st', 46),
(50, 1, 63, '4', '1st', 57),
(51, 1, 64, '4', '1st', 52);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roles_id` int(11) NOT NULL,
  `role_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roles_id`, `role_name`) VALUES
(1, 'student'),
(2, 'instructor'),
(3, 'registrar'),
(4, 'cashier'),
(5, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `year_level` int(11) NOT NULL,
  `section_name` varchar(10) NOT NULL,
  `adviser_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `program_id`, `year_level`, `section_name`, `adviser_id`, `created_at`) VALUES
(1, 1, 1, 'A', NULL, '2026-02-01 15:36:13'),
(2, 1, 1, 'B', NULL, '2026-02-01 15:36:13'),
(3, 1, 1, 'C', NULL, '2026-02-01 15:36:13'),
(4, 1, 2, 'A', NULL, '2026-02-01 15:36:13'),
(5, 1, 2, 'B', NULL, '2026-02-01 15:36:13'),
(6, 1, 2, 'C', NULL, '2026-02-01 15:36:13'),
(7, 1, 3, 'A', NULL, '2026-02-01 15:36:13'),
(8, 1, 3, 'B', NULL, '2026-02-01 15:36:13'),
(9, 1, 4, 'A', NULL, '2026-02-01 15:36:13'),
(10, 1, 4, 'B', NULL, '2026-02-01 15:36:13');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`) VALUES
(1, 'portal_name', 'E-EnrollSys'),
(2, 'portal_logo', '');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `dept_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `users_id`, `full_name`, `email`, `role_id`, `created_at`, `dept_id`) VALUES
(1, 5, 'System Administrator', 'admin@gmail.com', 5, '2026-02-10 15:05:08', NULL),
(2, 6, 'Registrar Staff', 'registrar@gmail.com', 3, '2026-02-10 15:06:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_number` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `program_id` int(11) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_number`, `full_name`, `program_id`, `status`, `created_at`, `section_id`) VALUES
(1, '202310658', 'Patricia Ann C. Mahinay', 1, 'active', '2026-02-01 09:57:42', 7),
(2, '202310494', 'Sanny Gine V. Patan-Patan', 1, 'active', '2026-02-01 14:09:31', 7),
(3, '202310434', 'Cristene C. Rios', 1, 'active', '2026-02-01 14:09:31', 7);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `grade` float DEFAULT NULL,
  `semester` varchar(20) DEFAULT NULL,
  `school_year` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_description` varchar(150) NOT NULL,
  `units` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_code`, `subject_description`, `units`) VALUES
(1, 'GNED 02', 'Ethics', 3),
(2, 'GNED 05', 'Purposive Communication', 3),
(3, 'GNED 11', 'Kontekswalisadong Komunikasyon sa Filipino', 3),
(4, 'COSC 50', 'Discrete Structure', 3),
(5, 'DCIT 21', 'Introduction to Computing', 3),
(6, 'DCIT 22', 'Programming 1', 3),
(7, 'FITT 1', 'Movement Enhancement', 2),
(8, 'NSTP 1', 'National Service Training Program 1', 3),
(9, 'GNED 01', 'Arts Appreciation', 3),
(10, 'GNED 06', 'Science, Technology, and Society', 3),
(11, 'GNED 12', 'Dalumat Ng/Sa Filipino', 3),
(12, 'GNED 03', 'Mathematics in the Moderm World', 3),
(13, 'DCIT 23', 'Computer Programming 2', 3),
(14, 'ITEC 50', 'Web System and Technologies 1', 3),
(15, 'FITT 2', 'Fitness Exercise', 2),
(16, 'NSTP 2', 'National Service Training Program 2', 3),
(30, 'GNED 04', 'Mga Babasahin Hinggil sa Kasaysayan ng Pilipinas', 3),
(31, 'GNED 07', 'The Contemporary World', 3),
(32, 'GNED 10', 'Gender and Society', 3),
(33, 'GNED 14', 'panitikang Panlipunan', 3),
(34, 'ITEC 55', 'Platform Technolgoies', 3),
(35, 'DCIT 24', 'Information Management', 3),
(36, 'DCIT 50', 'Object Oriented Programming', 3),
(37, 'FITT 3', 'Fitness Exercise', 2),
(38, 'GNED 08', 'Understanding the Self', 3),
(39, 'DCIT 25', 'Data Structure and Algorithm', 3),
(40, 'ITEC 60', 'Integrated Programming and Technologies 1', 3),
(41, 'ITEC 65', 'Open Source Technology', 3),
(42, 'DCIT 55', 'Advanced Database System', 3),
(43, 'ITEC 70', 'Multimedia Systems', 3),
(44, 'FITT 4', 'Physical Activities Towards Health and Fitness 2', 2),
(45, 'STAT 2', 'Aplied Statistics', 3),
(46, 'ITEC 75', 'System Integration and Architecture', 3),
(47, 'ITEC 80', 'Introduction to Human Computer Interaction', 3),
(48, 'ITEC 85', 'Information Assurance and Security 1', 3),
(49, 'ITEC 90', 'Network Fundamentals', 3),
(50, 'INSY 55', 'System Analysis and Design', 3),
(51, 'DCIT 26', 'Application Development and Emerging Technologies', 3),
(52, 'DCIT 60', 'Methods of Research', 3),
(53, 'GNED 09', 'Rizal: Life, Works, and Writings', 3),
(54, 'ITEC 95', 'Quantitative Methods', 3),
(55, 'ITEC 101', 'Human Computer Interaction 2', 3),
(56, 'ITEC 106', 'Web System and Technologies 2', 3),
(57, 'ITEC 100', 'Information Security and Assurance 2', 3),
(58, 'ITEC 105', 'Network Management', 3),
(59, 'ITEC 200A', 'Capstone Project and Research 1', 3),
(60, 'DCIT 65', 'Social and Professional Issues', 3),
(61, 'ITEC 111', 'Integrated Programming and Technologies 2', 3),
(62, 'ITEC 116', 'System Integration and Architecture 2', 3),
(63, 'ITEC 110', 'Systems Administration and Maintenance', 3),
(64, 'ITEC 200B', 'Capstone Project and Research 2', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `username`, `email`, `password`, `created_at`, `student_id`) VALUES
(2, NULL, 'ptrcmahinay@gmail.com', '$2y$10$BGPJOnUFV6V0vGmgjkGD7.Npvdd7t6Vk7gHSteHVnrGMiVfB3nZrS', '2026-02-01 13:54:16', 1),
(3, '', 'sannygine0@gmail.com', '$2y$10$QFbf3eaaJXqqD2/A6SCsgOjWM3RqdlzweZmg8LLLLg0UtjCSonDJW', '2026-02-01 14:11:16', 2),
(4, NULL, 'cristenerios1@gmail.com', '$2y$10$Y8BTxfBPy./uuhJa9T9wAuMJUYB13xzFnt5C9JjoE3JHiPPKTZTgG', '2026-02-01 14:24:30', 3),
(5, 'admin1', 'admin1@gmail.com', '$2y$10$N9hpCJbxcdEmcgVvfj/ED.DMx0hb2vIA8feyWmK4LdY8pGHItzbfS', '2026-02-03 16:39:26', NULL),
(6, 'registrar1', 'registrar1@gmail.com', '$2y$10$8AO7AY53VueumSA2rPJqrugJ0ZkksllI4yIkGtfbtKoxq.C3GYNki', '2026-02-03 17:22:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(2, 1),
(3, 1),
(4, 1),
(5, 5),
(6, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_terms`
--
ALTER TABLE `academic_terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`dept_id`),
  ADD UNIQUE KEY `department_code` (`department_code`);

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `curriculum_id` (`curriculum_id`),
  ADD KEY `fk_instructor` (`instructor_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `permission_name` (`permission_name`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`programs_id`),
  ADD UNIQUE KEY `program_code` (`program_code`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `program_curriculum`
--
ALTER TABLE `program_curriculum`
  ADD PRIMARY KEY (`curriculum_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `prerequisite_subject_id` (`prerequisite_subject_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roles_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `program_id` (`program_id`,`year_level`,`section_name`),
  ADD KEY `adviser_id` (`adviser_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `dept_id` (`dept_id`),
  ADD KEY `fk_staff_user` (`users_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_users_students` (`student_id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_terms`
--
ALTER TABLE `academic_terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `dept_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `programs_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `program_curriculum`
--
ALTER TABLE `program_curriculum`
  MODIFY `curriculum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `roles_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `staff` (`staff_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`curriculum_id`) REFERENCES `program_curriculum` (`curriculum_id`);

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`dept_id`) ON UPDATE CASCADE;

--
-- Constraints for table `program_curriculum`
--
ALTER TABLE `program_curriculum`
  ADD CONSTRAINT `program_curriculum_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`programs_id`),
  ADD CONSTRAINT `program_curriculum_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `program_curriculum_ibfk_3` FOREIGN KEY (`prerequisite_subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`roles_id`),
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`programs_id`),
  ADD CONSTRAINT `sections_ibfk_2` FOREIGN KEY (`adviser_id`) REFERENCES `staff` (`staff_id`);

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`roles_id`),
  ADD CONSTRAINT `staff_ibfk_2` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`dept_id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`programs_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`);

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `student_subjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_students` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `user_roles_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`roles_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
