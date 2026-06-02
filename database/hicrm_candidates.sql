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
-- Cấu trúc bảng cho bảng `hicrm_candidates`
--

CREATE TABLE `hicrm_candidates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Họ và tên',
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL COMMENT 'Địa chỉ hiện tại',
  `address_detail` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `degree` enum('high_school','intermediate','college','university','postgraduate','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bằng cấp cao nhất',
  `major` int(11) DEFAULT NULL COMMENT 'Chuyên ngành',
  `graduation_year` year(4) DEFAULT NULL,
  `school_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Trường đã học',
  `soft_skills` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'JSON: [{skill, level}]',
  `career_goal` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mục tiêu',
  `desired_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Vị trí muốn ứng tuyển',
  `desired_salary` int(11) DEFAULT NULL COMMENT 'Mức lương mong muốn',
  `desired_province_id` int(11) DEFAULT NULL COMMENT 'Địa điểm làm việc mong muốn',
  `desired_work_type` enum('full_time','part_time','remote','hybrid','any') COLLATE utf8mb4_unicode_ci DEFAULT 'any',
  `cv_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'File CV (PDF/DOCX)',
  `cv_uploaded_at` datetime DEFAULT NULL,
  `is_seeking` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Đang tìm việc',
  `profile_completeness` tinyint(3) UNSIGNED DEFAULT 0 COMMENT 'Phần trăm hoàn thiện hồ sơ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Hồ sơ ứng viên/sinh viên tìm việc';

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_candidates`
--
ALTER TABLE `hicrm_candidates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `majob` (`major`) USING BTREE,
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_province` (`province_id`),
  ADD KEY `idx_desired_province` (`desired_province_id`),
  ADD KEY `idx_seeking` (`is_seeking`),
  ADD KEY `desired_salary` (`desired_salary`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_candidates`
--
ALTER TABLE `hicrm_candidates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `hicrm_candidates`
--
ALTER TABLE `hicrm_candidates`
  ADD CONSTRAINT `hicrm_candidates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `hicrm_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hicrm_candidates_ibfk_2` FOREIGN KEY (`province_id`) REFERENCES `hicrm_provinces` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hicrm_candidates_ibfk_3` FOREIGN KEY (`desired_province_id`) REFERENCES `hicrm_provinces` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
