-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 29, 2026 lúc 11:52 AM
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
-- Cấu trúc bảng cho bảng `hicrm_student_profile`
--

CREATE TABLE `hicrm_student_profile` (
  `id` int(11) NOT NULL,
  `student_code` varchar(255) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `student_phone` varchar(255) NOT NULL,
  `student_email` varchar(255) NOT NULL,
  `student_class` int(11) NOT NULL,
  `student_birthday` date NOT NULL,
  `student_gender` int(11) NOT NULL,
  `student_file` varchar(255) DEFAULT NULL,
  `student_major_id` int(11) NOT NULL,
  `student_gpa` decimal(10,0) DEFAULT NULL,
  `student_rank` varchar(255) DEFAULT NULL,
  `student_description` text DEFAULT NULL,
  `student_is_register` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `hicrm_student_profile`
--

INSERT INTO `hicrm_student_profile` (`id`, `student_code`, `student_name`, `student_phone`, `student_email`, `student_class`, `student_birthday`, `student_gender`, `student_file`, `student_major_id`, `student_gpa`, `student_rank`, `student_description`, `student_is_register`) VALUES
(1, 'SV_001', 'Vũ Xuân Cương', '', '', 1, '2016-04-07', 0, 'aaaa', 1, '9', '', '', 0),
(2, 'SV001', 'Vũ', '098939128', 'vxcuong@gmail.com', 12, '2016-04-07', 1, NULL, 1, '9', 'Xuất xắc', 'aaaaa', 0),
(3, 'SV002', 'Vũ Xuân Cương 2', '0963781278', 'cuongvx2@gmail.com', 2, '2016-04-07', 1, NULL, 1, '9', 'Xuất sắc', NULL, 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_student_profile`
--
ALTER TABLE `hicrm_student_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_major_id` (`student_major_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_student_profile`
--
ALTER TABLE `hicrm_student_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
