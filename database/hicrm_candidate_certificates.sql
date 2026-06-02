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
-- Cấu trúc bảng cho bảng `hicrm_candidate_certificates`
--

CREATE TABLE `hicrm_candidate_certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `cert_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chứng chỉ/bằng cấp ngắn hạn',
  `issuer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổ chức cấp',
  `issued_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `cert_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'File/link chứng chỉ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chứng chỉ của ứng viên';

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_candidate_certificates`
--
ALTER TABLE `hicrm_candidate_certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_candidate` (`candidate_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_candidate_certificates`
--
ALTER TABLE `hicrm_candidate_certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `hicrm_candidate_certificates`
--
ALTER TABLE `hicrm_candidate_certificates`
  ADD CONSTRAINT `hicrm_candidate_certificates_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `hicrm_candidates` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
