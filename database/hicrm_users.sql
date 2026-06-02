-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 29, 2026 lúc 11:49 AM
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
-- Cấu trúc bảng cho bảng `hicrm_users`
--

CREATE TABLE `hicrm_users` (
  `id` bigint(20) NOT NULL,
  `student_id` int(11) DEFAULT 0,
  `user_username` varchar(191) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(191) NOT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_group` varchar(20) NOT NULL COMMENT 'admin | employer | student | member',
  `user_status` varchar(20) NOT NULL DEFAULT 'pending' COMMENT 'active | inactive | banned | pending',
  `user_avatar_url` varchar(500) DEFAULT NULL,
  `user_reset_token` varchar(255) DEFAULT NULL,
  `user_reset_token_expires` datetime DEFAULT NULL,
  `user_two_fa_enabled` tinyint(4) DEFAULT 0,
  `user_two_fa_secret` varchar(255) DEFAULT NULL COMMENT 'TOTP secret key',
  `user_two_fa_method` varchar(10) DEFAULT NULL COMMENT 'totp | sms | email',
  `user_email_verified_at` datetime DEFAULT NULL,
  `user_email_verify_token` varchar(255) DEFAULT NULL,
  `user_last_login_at` datetime DEFAULT NULL,
  `user_last_login_ip` varchar(45) DEFAULT NULL,
  `user_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_updated_at` datetime NOT NULL,
  `user_deleted_at` datetime DEFAULT NULL COMMENT 'Soft delete',
  `user_is_subscribed` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `hicrm_users`
--

INSERT INTO `hicrm_users` (`id`, `student_id`, `user_username`, `full_name`, `user_email`, `user_phone`, `user_password`, `user_group`, `user_status`, `user_avatar_url`, `user_reset_token`, `user_reset_token_expires`, `user_two_fa_enabled`, `user_two_fa_secret`, `user_two_fa_method`, `user_email_verified_at`, `user_email_verify_token`, `user_last_login_at`, `user_last_login_ip`, `user_created_at`, `user_updated_at`, `user_deleted_at`, `user_is_subscribed`) VALUES
(1, 0, 'vuxuancuong', 'Vũ Xuân Cương', 'cuongmedia@gmail.com', NULL, '365097819ec24498d11c8445f9812ba8', '1', '1', NULL, NULL, '2026-05-27 16:00:56', 0, NULL, NULL, '2026-05-27 16:00:56', NULL, '2026-05-27 16:00:56', NULL, '2026-05-27 14:01:33', '2026-05-27 16:00:56', '2026-05-27 16:00:56', 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_users`
--
ALTER TABLE `hicrm_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_username` (`user_username`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_users`
--
ALTER TABLE `hicrm_users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
