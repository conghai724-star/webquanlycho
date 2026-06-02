-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 29, 2026 lúc 11:50 AM
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
-- Cấu trúc bảng cho bảng `hicrm_user_groups`
--

CREATE TABLE `hicrm_user_groups` (
  `id` int(11) NOT NULL,
  `user_role_id` int(11) DEFAULT 0,
  `group_name` varchar(255) NOT NULL,
  `group_class` varchar(255) DEFAULT NULL,
  `group_icon` varchar(255) DEFAULT NULL,
  `group_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `hicrm_user_groups`
--

INSERT INTO `hicrm_user_groups` (`id`, `user_role_id`, `group_name`, `group_class`, `group_icon`, `group_status`) VALUES
(1, 0, 'Quản trị viên', 'primary', NULL, 1),
(2, 0, 'Sinh viên', 'info', NULL, 1),
(3, 0, 'Ứng viên', 'warning', NULL, 1),
(4, 0, 'Nhà tuyển dụng', 'primary', NULL, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_user_groups`
--
ALTER TABLE `hicrm_user_groups`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_user_groups`
--
ALTER TABLE `hicrm_user_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
