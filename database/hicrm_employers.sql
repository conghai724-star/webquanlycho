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
-- Cấu trúc bảng cho bảng `hicrm_employers`
--

CREATE TABLE `hicrm_employers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên công ty/đơn vị',
  `logo_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Logo công ty',
  `cover_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ảnh bìa',
  `tax_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã số thuế',
  `job_category_id` int(11) DEFAULT NULL COMMENT 'Lĩnh vực hoạt động',
  `company_size` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mô tả ngắn về công ty',
  `website_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fanpage_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province_id` int(11) DEFAULT NULL,
  `address_detail` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Địa chỉ chi tiết trụ sở',
  `is_linked_school` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Đã liên kết với trường CĐ Kon Tum',
  `link_summary` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nội dung ngắn gọn về liên kết',
  `link_document_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'File đính kèm hợp đồng/biên bản',
  `verified_status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verified_at` datetime DEFAULT NULL,
  `reject_reason` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lý do từ chối (nếu bị rejected)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Thông tin nhà tuyển dụng/doanh nghiệp';

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_employers`
--
ALTER TABLE `hicrm_employers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `tax_code` (`tax_code`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_job_categories` (`job_category_id`),
  ADD KEY `idx_province` (`province_id`),
  ADD KEY `idx_verified` (`verified_status`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_employers`
--
ALTER TABLE `hicrm_employers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `hicrm_employers`
--
ALTER TABLE `hicrm_employers`
  ADD CONSTRAINT `hicrm_employers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `hicrm_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hicrm_employers_ibfk_2` FOREIGN KEY (`job_category_id`) REFERENCES `hicrm_job_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hicrm_employers_ibfk_3` FOREIGN KEY (`province_id`) REFERENCES `hicrm_provinces` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
