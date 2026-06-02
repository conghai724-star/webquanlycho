-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 29, 2026 lúc 11:53 AM
-- Phiên bản máy phục vụ: 10.4.21-MariaDB
-- Phiên bản PHP: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `vieclam.vn`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hicrm_job_posts`
--

CREATE TABLE `hicrm_job_posts` (
  `id` bigint(20) NOT NULL,
  `employer_id` bigint(20) NOT NULL,
  `job_category_id` int(11) DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `quantity` smallint(6) DEFAULT 1,
  `job_description` text NOT NULL,
  `experience_years` tinyint(4) DEFAULT 0,
  `degree_required` varchar(20) DEFAULT NULL COMMENT 'none | high_school | intermediate | college | university | postgraduate',
  `professional_skills` text DEFAULT NULL,
  `soft_skills` text DEFAULT NULL,
  `salary_id` int(11) DEFAULT NULL,
  `benefits_description` text DEFAULT NULL,
  `work_type` varchar(20) DEFAULT NULL COMMENT 'full_time | part_time | remote | hybrid',
  `address_detail` varchar(500) DEFAULT NULL,
  `deadline` date NOT NULL,
  `status` varchar(20) DEFAULT 'pending' COMMENT 'draft | pending | published | closed | rejected',
  `published_at` datetime DEFAULT NULL,
  `views_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_job_posts`
--
ALTER TABLE `hicrm_job_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employer_id` (`employer_id`),
  ADD KEY `job_category_id` (`job_category_id`),
  ADD KEY `province_id` (`province_id`),
  ADD KEY `salary_id` (`salary_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_job_posts`
--
ALTER TABLE `hicrm_job_posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
