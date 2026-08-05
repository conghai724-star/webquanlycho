-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3307
-- Thời gian đã tạo: Th8 03, 2026 lúc 08:29 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlycho.vn`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `areas`
--

CREATE TABLE `areas` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `area_block` varchar(50) DEFAULT NULL,
  `area_lot` varchar(50) DEFAULT NULL,
  `area_description` text DEFAULT NULL,
  `area_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `area_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `area_market_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `areas`
--

INSERT INTO `areas` (`area_id`, `area_name`, `area_block`, `area_lot`, `area_description`, `area_created_at`, `area_updated_at`, `area_market_id`) VALUES
(1, 'Khu A (Quần áo)', 'Dãy A', 'Lô 1', 'Khu vực chuyên doanh quần áo, giày dép và phụ kiện thời trang', '2026-07-08 09:32:18', '2026-07-11 13:09:20', 1),
(2, 'Khu A', 'Dãy B', 'Lô 2', 'Khu vực chuyên doanh thực phẩm tươi sống, rau củ quả và đồ khô', '2026-07-08 09:32:18', '2026-07-16 20:41:03', 2),
(3, 'Khu B', 'Dãy C', 'Lô 3', 'Khu vực chuyên kinh doanh dịch vụ ăn uống, nước giải khát', '2026-07-08 09:32:18', '2026-07-16 20:41:09', 2),
(4, 'Khu C', '3TTTT', '1gg', 'ggg', '2026-07-11 12:23:59', '2026-07-16 20:41:13', 3),
(5, '8', '8', '8', '8', '2026-07-11 13:16:51', '2026-07-11 13:16:51', 1),
(10, '2', '2', '2', '2', '2026-07-30 11:55:26', '2026-07-30 11:55:26', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bills`
--

CREATE TABLE `bills` (
  `bill_id` int(11) NOT NULL,
  `bill_contract_id` int(11) NOT NULL,
  `bill_code` varchar(50) NOT NULL,
  `bill_invoice_date` date NOT NULL,
  `bill_due_date` date NOT NULL,
  `bill_rent_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_electric_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_water_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_management_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_sanitation_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_security_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_other_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bill_status` varchar(20) NOT NULL DEFAULT 'unpaid',
  `bill_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `bill_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bills`
--

INSERT INTO `bills` (`bill_id`, `bill_contract_id`, `bill_code`, `bill_invoice_date`, `bill_due_date`, `bill_rent_amount`, `bill_electric_amount`, `bill_water_amount`, `bill_management_fee`, `bill_sanitation_fee`, `bill_security_fee`, `bill_other_fee`, `bill_total_amount`, `bill_paid_amount`, `bill_status`, `bill_created_at`, `bill_updated_at`) VALUES
(1, 1, 'HD-202606-001', '2026-06-25', '2026-07-10', 3500000.00, 450000.00, 120000.00, 100000.00, 50000.00, 50000.00, 0.00, 4270000.00, 0.00, 'unpaid', '2026-07-08 09:32:18', '2026-07-08 09:32:18'),
(2, 2, 'HD-202606-002', '2026-06-25', '2026-07-10', 1500000.00, 750000.00, 176000.00, 100000.00, 50000.00, 50000.00, 0.00, 2626000.00, 2626000.00, 'paid', '2026-07-08 09:32:18', '2026-07-08 09:32:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `business_lines`
--

CREATE TABLE `business_lines` (
  `line_id` int(11) NOT NULL,
  `line_code` varchar(50) NOT NULL,
  `line_name` varchar(100) NOT NULL,
  `line_description` text DEFAULT NULL,
  `line_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `line_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `business_lines`
--

INSERT INTO `business_lines` (`line_id`, `line_code`, `line_name`, `line_description`, `line_created_at`, `line_updated_at`) VALUES
(1, 'THOI_TRANG', 'Thời trang & May mặc', 'Quần áo, giày dép, phụ kiện thời trang', '2026-07-08 09:32:18', '2026-07-08 09:32:18'),
(2, 'THUC_PHAM', 'Thực phẩm tươi sống', 'Thịt, cá, rau củ quả, thực phẩm hàng ngày', '2026-07-08 09:32:18', '2026-07-08 09:32:18'),
(3, 'AM_THUC', 'Ẩm thực & Đồ uống', 'Đồ ăn chín, nước giải khát, quán ăn', '2026-07-08 09:32:18', '2026-07-08 09:32:18'),
(9, '1', '1', '1', '2026-07-30 11:30:14', '2026-07-30 11:30:14'),
(11, '2', '2', '2', '2026-07-30 11:55:15', '2026-07-30 11:55:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contracts`
--

CREATE TABLE `contracts` (
  `contract_id` int(11) NOT NULL,
  `contract_trader_id` int(11) NOT NULL,
  `contract_stall_id` int(11) NOT NULL,
  `contract_number` varchar(100) NOT NULL,
  `contract_name` varchar(255) NOT NULL,
  `contract_description` text DEFAULT NULL,
  `contract_file` varchar(255) DEFAULT NULL,
  `contract_sign_date` date DEFAULT NULL,
  `contract_start_date` date NOT NULL,
  `contract_end_date` date NOT NULL,
  `contract_deposit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `contract_status_id` int(11) NOT NULL DEFAULT 11,
  `contract_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `contract_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contracts`
--

INSERT INTO `contracts` (`contract_id`, `contract_trader_id`, `contract_stall_id`, `contract_number`, `contract_name`, `contract_description`, `contract_file`, `contract_sign_date`, `contract_start_date`, `contract_end_date`, `contract_deposit`, `contract_status_id`, `contract_created_at`, `contract_updated_at`) VALUES
(1, 1, 6, 'HĐ-SA01-2026', 'Hợp đồng thuê sạp SẠP-A01', 'Hợp đồng thuê sạp thời trang Kiot cao cấp', NULL, '2026-01-01', '2026-01-01', '2026-12-31', 7000000.00, 11, '2026-07-08 09:32:18', '2026-07-27 19:12:51'),
(2, 2, 1, 'HĐ-SB01-2026', 'Hợp đồng thuê sạp SẠP-B01111', 'Hợp đồng thuê sạp thực phẩm tươi sống', NULL, '2026-01-15', '2026-01-15', '2050-05-05', 3000000.00, 11, '2026-07-08 09:32:18', '2026-07-27 19:12:51'),
(3, 3, 5, 'HĐ-SA05-2026', 'Hợp đồng thuê sạp SẠP-A05g', 'Hợp đồng thuê sạp quần áo tiêu chuẩn', NULL, '2026-02-01', '2026-02-01', '2026-08-01', 4000000.00, 14, '2026-07-08 09:32:18', '2026-07-27 20:21:02'),
(4, 4, 3, 'HĐ-GAN-20260710-298', 'Hợp đồng thuê sạp SẠP-A03', NULL, NULL, '2026-07-10', '2026-07-10', '2027-07-10', 4000000.00, 99, '2026-07-10 08:46:46', '2026-07-27 19:12:51'),
(5, 4, 7, 'HĐ-GAN-20260710-156', 'Hợp đồng thuê sạp SẠP-B02', NULL, NULL, '2026-07-10', '2026-07-10', '2027-07-10', 3000000.00, 13, '2026-07-10 08:48:47', '2026-07-27 19:12:51'),
(6, 3, 4, 'HĐ-GAN-20260710-718', 'Hợp đồng thuê sạp SẠP-A03', NULL, NULL, '2026-07-10', '2026-07-10', '2027-07-10', 4000000.00, 13, '2026-07-10 15:13:14', '2026-07-27 19:12:51'),
(7, 4, 11, 'HĐ-GAN-20260711-124', 'Hợp đồng thuê sạp 1', NULL, NULL, '2026-07-11', '2026-07-11', '2027-07-11', 2.00, 11, '2026-07-11 13:10:02', '2026-07-27 19:12:51'),
(8, 10, 16, 'HĐ-GAN-20260727-899', 'Hợp đồng thuê sạp 1111111111', '', '[\"H___P______NG_M___U_CH____QTR_1785102141_6a667f3d628a5.docx\"]', '2026-07-27', '2026-07-27', '2027-07-08', 444400.00, 13, '2026-07-26 17:45:58', '2026-07-27 19:12:51'),
(9, 11, 5, 'HĐ-GAN-20260727-37811', 'Hợp đồng thuê sạp SẠP-A021', '1', NULL, '2025-07-27', '2025-07-27', '2026-07-27', 2000.00, 99, '2026-07-26 18:24:09', '2026-07-27 19:29:37'),
(10, 12, 3, 'HĐ-GAN-20260727-999', 'Hợp đồng thuê sạp SẠP-A05', NULL, NULL, '2026-07-27', '2026-07-27', '2027-07-27', 4000000.00, 99, '2026-07-26 18:24:14', '2026-07-27 19:12:51'),
(11, 12, 14, '11111111111111111111111', '6666666', '1', NULL, '2026-11-13', '2026-11-11', '2027-11-11', 4000000.00, 11, '2026-07-27 04:17:47', '2026-07-31 19:52:52'),
(12, 3, 16, 'HĐ-GAN-20260728-562', 'Hợp đồng thuê sạp 1111111111', NULL, NULL, NULL, '2026-07-28', '2027-07-28', 444400.00, 99, '2026-07-27 19:29:54', '2026-07-27 19:31:12'),
(13, 3, 16, 'HĐ-GAN-20260728-356', 'Hợp đồng thuê sạp 1111111111', NULL, NULL, '2026-07-27', '2026-07-28', '2027-07-28', 444400.00, 11, '2026-07-27 19:31:29', '2026-07-27 19:31:49'),
(14, 6, 4, 'HĐ-GAN-20260728-185', 'Hợp đồng thuê sạp SẠP-A04', NULL, NULL, '2026-07-31', '2026-07-28', '2027-07-28', 4000000.00, 11, '2026-07-27 20:29:13', '2026-07-31 22:07:36'),
(15, 6, 3, 'HĐ-GAN-20260728-366', 'Hợp đồng thuê sạp SẠP-A03', NULL, NULL, '2026-07-07', '2026-07-28', '2027-07-28', 4000000.00, 99, '2026-07-27 20:29:38', '2026-07-27 22:09:08'),
(16, 2, 19, 'HĐ-GAN-20260728-921', 'Hợp đồng thuê sạp 567', NULL, NULL, '2026-07-28', '2026-07-28', '2027-07-28', 246.00, 11, '2026-07-28 01:33:05', '2026-07-31 22:10:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contract_appendices`
--

CREATE TABLE `contract_appendices` (
  `appendix_id` int(11) NOT NULL,
  `appendix_contract_id` int(11) NOT NULL,
  `appendix_number` varchar(100) NOT NULL,
  `appendix_name` varchar(255) NOT NULL,
  `appendix_sign_date` date NOT NULL,
  `appendix_effect_date` date NOT NULL,
  `appendix_content` text NOT NULL,
  `appendix_file` varchar(255) DEFAULT NULL,
  `appendix_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `appendix_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contract_appendices`
--

INSERT INTO `contract_appendices` (`appendix_id`, `appendix_contract_id`, `appendix_number`, `appendix_name`, `appendix_sign_date`, `appendix_effect_date`, `appendix_content`, `appendix_file`, `appendix_created_at`, `appendix_updated_at`) VALUES
(1, 1, 'PL-SA01-2026-01', 'Phụ lục điều chỉnh đơn giá', '2026-06-01', '2026-06-15', 'Điều chỉnh đơn giá thuê từ 3,500,000đ thành 3,800,000đ kể từ ngày 15/06/2026 do nâng cấp Kiot.', NULL, '2026-07-08 09:32:18', '2026-07-08 09:32:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contract_history`
--

CREATE TABLE `contract_history` (
  `history_id` int(11) NOT NULL,
  `history_contract_id` int(11) NOT NULL,
  `history_action` varchar(50) NOT NULL,
  `history_changes` text DEFAULT NULL,
  `history_note` text DEFAULT NULL,
  `history_user_id` int(11) DEFAULT NULL,
  `history_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `contract_history`
--

INSERT INTO `contract_history` (`history_id`, `history_contract_id`, `history_action`, `history_changes`, `history_note`, `history_user_id`, `history_created_at`) VALUES
(1, 2, 'activate', '{\"contract_number\":\"HĐ-SB01-2026\",\"contract_start_date\":\"2026-01-15\",\"contract_end_date\":\"2050-05-05\",\"contract_deposit\":\"3000000\"}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-26 19:57:09'),
(2, 1, 'appendix_add', '{\"contract_id\":1,\"appendix_number\":\"PL-TEST-999\",\"market_name\":\"Tên phụ lục test\",\"sign_date\":\"2026-07-27\",\"effect_date\":\"2026-08-01\",\"content\":\"Nội dung phụ lục test\",\"file\":null}', 'Thêm phụ lục hợp đồng số: PL-TEST-999', NULL, '2026-07-26 20:40:46'),
(3, 1, 'appendix_add', '{\"contract_id\":1,\"appendix_number\":\"PL-MULTIPLE-FILE-TEST\",\"market_name\":\"Phụ lục tải nhiều file test\",\"sign_date\":\"2026-07-27\",\"effect_date\":\"2026-08-01\",\"content\":\"Nội dung phụ lục đính kèm 3 file tài liệu.\",\"file\":\"[\\\"test_file_1.pdf\\\",\\\"test_file_2.png\\\",\\\"test_file_3.jpg\\\"]\"}', 'Thêm phụ lục hợp đồng số: PL-MULTIPLE-FILE-TEST', NULL, '2026-07-26 20:44:50'),
(4, 9, 'activate', '{\"contract_number\":\"HĐ-GAN-20260727-378\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-27\",\"contract_deposit\":\"4000000\"}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-26 20:45:29'),
(5, 9, 'update', '{\"contract_number\":\"HĐ-GAN-20260727-378\",\"contract_name\":\"Hợp đồng thuê sạp SẠP-A0\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-27\",\"contract_deposit\":\"4000000\",\"contract_description\":\"\"}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 21:20:21'),
(6, 8, 'update', '{\"contract_number\":\"HĐ-GAN-20260727-899\",\"contract_name\":\"Hợp đồng thuê sạp 1111111111\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-08\",\"contract_deposit\":\"444400\",\"contract_description\":\"\",\"contract_file\":\"danh_sach_tieu_thuong__2__1785101147_6a667b5bd0aca.pdf\"}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 21:25:47'),
(7, 8, 'update', '{\"contract_number\":\"HĐ-GAN-20260727-899\",\"contract_name\":\"Hợp đồng thuê sạp 1111111111\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-08\",\"contract_deposit\":\"444400\",\"contract_description\":\"\",\"contract_file\":null}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 21:42:05'),
(8, 8, 'update', '{\"contract_number\":\"HĐ-GAN-20260727-899\",\"contract_name\":\"Hợp đồng thuê sạp 1111111111\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-08\",\"contract_deposit\":\"444400\",\"contract_description\":\"\",\"contract_file\":\"[\\\"danh_sach_tieu_thuong__2__1785101147_6a667b5bd0aca.pdf\\\",\\\"H___P______NG_M___U_CH____QTR__1__1785102141_6a667f3d6200c.docx\\\",\\\"H___P______NG_M___U_CH____QTR_1785102141_6a667f3d628a5.docx\\\"]\"}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 21:42:21'),
(9, 8, 'update', '{\"contract_number\":\"HĐ-GAN-20260727-899\",\"contract_name\":\"Hợp đồng thuê sạp 1111111111\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-08\",\"contract_deposit\":\"444400\",\"contract_description\":\"\",\"contract_file\":null}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 21:42:30'),
(10, 8, 'update', '{\"contract_number\":\"HĐ-GAN-20260727-899\",\"contract_name\":\"Hợp đồng thuê sạp 1111111111\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-08\",\"contract_deposit\":\"444400\",\"contract_description\":\"\",\"contract_file\":\"[\\\"H___P______NG_M___U_CH____QTR_1785102141_6a667f3d628a5.docx\\\"]\"}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 21:47:27'),
(11, 9, 'update', '{\"contract_number\":\"HĐ-GAN-20260727-378\",\"contract_name\":\"Hợp đồng thuê sạp SẠP-A011\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-27\",\"contract_deposit\":\"4000000\",\"contract_description\":\"\",\"contract_file\":null}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 21:54:25'),
(12, 9, 'update', '{\"contract_number\":\"HĐ-GAN-20260727-378\",\"contract_name\":\"Hợp đồng thuê sạp SẠP-A011\",\"contract_start_date\":\"2026-07-27\",\"contract_end_date\":\"2027-07-27\",\"contract_deposit\":\"40000000\",\"contract_description\":\"\",\"contract_file\":null}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 21:58:09'),
(13, 9, 'update', '{\"contract_name\":{\"label\":\"Tên hợp đồng\",\"old\":\"Hợp đồng thuê sạp SẠP-A011\",\"new\":\"Hợp đồng thuê sạp SẠP-A0111\"}}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 22:15:31'),
(14, 9, 'update', '{\"contract_number\":{\"label\":\"Số hợp đồng\",\"old\":\"HĐ-GAN-20260727-378\",\"new\":\"HĐ-GAN-20260727-37811\"},\"contract_name\":{\"label\":\"Tên hợp đồng\",\"old\":\"Hợp đồng thuê sạp SẠP-A0111\",\"new\":\"Hợp đồng thuê sạp SẠP-A021\"},\"contract_start_date\":{\"label\":\"Ngày bắt đầu\",\"old\":\"2026-07-27\",\"new\":\"2025-07-27\"},\"contract_end_date\":{\"label\":\"Ngày kết thúc\",\"old\":\"2027-07-27\",\"new\":\"2026-07-27\"},\"contract_deposit\":{\"label\":\"Tiền đặt cọc\",\"old\":\"40.000.000 đ\",\"new\":\"2.000 đ\"},\"contract_description\":{\"label\":\"Mô tả\\/Ghi chú\",\"old\":\"\",\"new\":\"1\"}}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 22:18:27'),
(15, 2, 'update', '{\"contract_name\":{\"label\":\"Tên hợp đồng\",\"old\":\"Hợp đồng thuê sạp SẠP-B01\",\"new\":\"Hợp đồng thuê sạp SẠP-B01111\"}}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-26 22:22:38'),
(16, 11, 'create', '{\"contract_trader_id\":\"12\",\"contract_stall_id\":\"14\",\"contract_number\":\"11111111111111111111111\",\"contract_name\":\"6666666\",\"contract_description\":\"1\",\"contract_start_date\":\"2026-11-11\",\"contract_end_date\":\"2027-11-11\",\"contract_deposit\":\"4000000\"}', 'Lập hợp đồng thuê sạp mới', 4, '2026-07-27 04:17:47'),
(17, 9, 'activate', '{\"contract_number\":\"HĐ-GAN-20260727-37811\",\"contract_sign_date\":\"2025-07-27\",\"contract_start_date\":\"2025-07-27\",\"contract_end_date\":\"2026-07-27\",\"contract_deposit\":\"2000\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-27 19:27:37'),
(18, 9, 'activate', '{\"contract_number\":\"HĐ-GAN-20260727-37811\",\"contract_sign_date\":\"2025-07-27\",\"contract_start_date\":\"2025-07-27\",\"contract_end_date\":\"2026-07-27\",\"contract_deposit\":\"2000\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-27 19:28:03'),
(19, 9, 'activate', '{\"contract_number\":\"HĐ-GAN-20260727-37811\",\"contract_sign_date\":\"2025-07-27\",\"contract_start_date\":\"2025-07-27\",\"contract_end_date\":\"2026-07-27\",\"contract_deposit\":\"2000\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-27 19:28:10'),
(20, 9, 'activate', '{\"contract_number\":\"HĐ-GAN-20260727-37811\",\"contract_sign_date\":\"2025-07-27\",\"contract_start_date\":\"2025-07-27\",\"contract_end_date\":\"2026-07-27\",\"contract_deposit\":\"2000\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-27 19:28:17'),
(21, 11, 'update', '{\"contract_sign_date\":{\"label\":\"Ngày lập hợp đồng\",\"old\":\"2026-11-11\",\"new\":\"2026-11-13\"}}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-27 19:29:25'),
(22, 12, 'create', '{\"contract_trader_id\":\"3\",\"contract_stall_id\":\"16\",\"contract_name\":\"Hợp đồng thuê sạp 1111111111\",\"contract_number\":\"HĐ-GAN-20260728-562\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":444400,\"contract_status_id\":27}', 'Lập hợp đồng thuê sạp mới', 4, '2026-07-27 19:29:54'),
(23, 13, 'create', '{\"contract_trader_id\":\"3\",\"contract_stall_id\":\"16\",\"contract_name\":\"Hợp đồng thuê sạp 1111111111\",\"contract_number\":\"HĐ-GAN-20260728-356\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":444400,\"contract_status_id\":27}', 'Lập hợp đồng thuê sạp mới', 4, '2026-07-27 19:31:29'),
(24, 13, 'activate', '{\"contract_number\":\"HĐ-GAN-20260728-356\",\"contract_sign_date\":\"2026-07-27\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":\"444400\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-27 19:31:49'),
(25, 3, 'update', '[]', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-27 20:20:40'),
(26, 3, 'update', '{\"contract_name\":{\"label\":\"Tên hợp đồng\",\"old\":\"Hợp đồng thuê sạp SẠP-A05\",\"new\":\"Hợp đồng thuê sạp SẠP-A05g\"}}', 'Chỉnh sửa thông tin chi tiết hợp đồng.', 4, '2026-07-27 20:21:02'),
(27, 14, 'create', '{\"contract_trader_id\":\"6\",\"contract_stall_id\":\"4\",\"contract_name\":\"Hợp đồng thuê sạp SẠP-A04\",\"contract_number\":\"HĐ-GAN-20260728-185\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":4000000,\"contract_status_id\":27}', 'Lập hợp đồng thuê sạp mới', 4, '2026-07-27 20:29:13'),
(28, 15, 'create', '{\"contract_trader_id\":\"6\",\"contract_stall_id\":\"3\",\"contract_name\":\"Hợp đồng thuê sạp SẠP-A03\",\"contract_number\":\"HĐ-GAN-20260728-366\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":4000000,\"contract_status_id\":27}', 'Lập hợp đồng thuê sạp mới', 4, '2026-07-27 20:29:38'),
(29, 15, 'activate', '{\"contract_number\":\"HĐ-GAN-20260728-366\",\"contract_sign_date\":\"2026-07-07\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":\"4000000\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-27 21:59:34'),
(30, 15, 'liquidate', NULL, 'Thanh lý hợp đồng, đưa hợp đồng về trạng thái Khởi tạo', 4, '2026-07-27 22:00:12'),
(31, 15, 'activate', '{\"contract_number\":\"HĐ-GAN-20260728-366\",\"contract_sign_date\":\"2026-07-07\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":\"4000000\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-27 22:00:25'),
(32, 11, 'terminate', NULL, 'Chấm dứt hợp đồng trước hạn. Trạng thái sạp chuyển về Trống.', 4, '2026-07-27 22:13:25'),
(33, 11, 'reactivate', NULL, 'Tái kích hoạt hợp đồng, đưa hợp đồng về trạng thái Khởi tạo', 4, '2026-07-27 22:14:23'),
(34, 16, 'create', '{\"contract_trader_id\":\"2\",\"contract_stall_id\":\"19\",\"contract_name\":\"Hợp đồng thuê sạp 567\",\"contract_number\":\"HĐ-GAN-20260728-921\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":246,\"contract_status_id\":27}', 'Lập hợp đồng thuê sạp mới', 4, '2026-07-28 01:33:05'),
(35, 16, 'activate', '{\"contract_number\":\"HĐ-GAN-20260728-921\",\"contract_sign_date\":\"2026-07-28\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":\"246\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-28 01:33:24'),
(36, 11, 'activate', '{\"contract_number\":\"11111111111111111111111\",\"contract_sign_date\":\"2026-11-13\",\"contract_start_date\":\"2026-11-11\",\"contract_end_date\":\"2027-11-11\",\"contract_deposit\":\"4000000\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 4, '2026-07-31 19:52:52'),
(37, 14, 'activate', '{\"contract_number\":\"HĐ-GAN-20260728-185\",\"contract_sign_date\":\"2026-07-31\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":\"4000000\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 2, '2026-07-31 22:07:36'),
(38, 16, 'liquidate', NULL, 'Thanh lý hợp đồng. Trạng thái sạp chuyển về Trống.', 2, '2026-07-31 22:07:51'),
(39, 16, 'reactivate', NULL, 'Tái kích hoạt hợp đồng, đưa hợp đồng về trạng thái Khởi tạo', 2, '2026-07-31 22:09:55'),
(40, 16, 'activate', '{\"contract_number\":\"HĐ-GAN-20260728-921\",\"contract_sign_date\":\"2026-07-28\",\"contract_start_date\":\"2026-07-28\",\"contract_end_date\":\"2027-07-28\",\"contract_deposit\":\"246\",\"contract_file\":null}', 'Kích hoạt hợp đồng thành Hoạt động', 2, '2026-07-31 22:10:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `document_types`
--

CREATE TABLE `document_types` (
  `doc_type_id` int(11) NOT NULL,
  `doc_type_code` varchar(50) NOT NULL,
  `doc_type_name` varchar(100) NOT NULL,
  `doc_type_description` text DEFAULT NULL,
  `doc_type_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `doc_type_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `document_types`
--

INSERT INTO `document_types` (`doc_type_id`, `doc_type_code`, `doc_type_name`, `doc_type_description`, `doc_type_created_at`, `doc_type_updated_at`) VALUES
(1, 'attp', 'Giấy chứng nhận ATTP', NULL, '2026-07-10 16:05:52', '2026-07-10 16:05:52'),
(2, 'suc_khoe', 'Giấy khám sức khỏe', NULL, '2026-07-10 16:05:52', '2026-07-10 16:05:52'),
(3, 'tap_huan', 'Chứng nhận tập huấn ATTP', NULL, '2026-07-10 16:05:52', '2026-07-10 16:05:52'),
(4, 'h', 't', 't', '2026-07-11 12:25:18', '2026-07-11 12:25:18'),
(6, '1', '1', '1', '2026-07-30 11:30:20', '2026-07-30 11:30:20'),
(7, '2', '2', '2', '2026-07-30 11:55:08', '2026-07-30 11:55:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `food_safety_inspections`
--

CREATE TABLE `food_safety_inspections` (
  `inspection_id` int(11) NOT NULL,
  `inspection_title` varchar(255) NOT NULL,
  `inspection_team` varchar(255) NOT NULL,
  `inspection_planned_date` date NOT NULL,
  `inspection_actual_date` date DEFAULT NULL,
  `inspection_status` varchar(20) NOT NULL DEFAULT 'planned',
  `inspection_notes` text DEFAULT NULL,
  `inspection_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `inspection_market_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `food_safety_inspections`
--

INSERT INTO `food_safety_inspections` (`inspection_id`, `inspection_title`, `inspection_team`, `inspection_planned_date`, `inspection_actual_date`, `inspection_status`, `inspection_notes`, `inspection_created_at`, `inspection_market_id`) VALUES
(1, 'Kiểm tra định kỳ quý 2/2026', 'Ban quản lý chợ + Phòng Y tế Quận', '2026-07-15', NULL, 'planned', 'Kiểm tra giấy khám sức khỏe và trang bị bảo hộ của hộ kinh doanh tươi sống và thực phẩm chín.', '2026-07-08 09:32:18', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `food_safety_violations`
--

CREATE TABLE `food_safety_violations` (
  `violation_id` int(11) NOT NULL,
  `violation_trader_id` int(11) NOT NULL,
  `violation_inspection_id` int(11) DEFAULT NULL,
  `violation_code` varchar(50) NOT NULL,
  `violation_date` date NOT NULL,
  `violation_description` text NOT NULL,
  `violation_penalty_measure` text NOT NULL,
  `violation_status` varchar(20) NOT NULL DEFAULT 'pending',
  `violation_resolved_date` date DEFAULT NULL,
  `violation_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `violation_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `food_safety_violations`
--

INSERT INTO `food_safety_violations` (`violation_id`, `violation_trader_id`, `violation_inspection_id`, `violation_code`, `violation_date`, `violation_description`, `violation_penalty_measure`, `violation_status`, `violation_resolved_date`, `violation_created_at`, `violation_updated_at`) VALUES
(1, 2, NULL, 'BBVP-0089', '2026-06-20', 'Không đeo găng tay khi chế biến, bày thực phẩm chín không che đậy gây mất vệ sinh', 'Phạt cảnh cáo, đình chỉ sạp 3 ngày', 'resolved', '2026-06-23', '2026-07-08 09:32:18', '2026-07-08 09:32:18');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `income_categories`
--

CREATE TABLE `income_categories` (
  `category_id` int(10) UNSIGNED NOT NULL,
  `market_id` int(11) NOT NULL,
  `category_type` enum('income','expense') NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_note` varchar(500) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=active, 99=soft deleted',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `income_categories`
--

INSERT INTO `income_categories` (`category_id`, `market_id`, `category_type`, `category_name`, `category_note`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'income', 'Thu tiền thuê sạp', 'Thu tiền thuê mặt bằng kinh doanh hàng tháng', 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(3, 1, 'income', 'Thu tiền điện nước', 'Thu tiền điện nước từ các hộ tiểu thương', 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(4, 1, 'income', 'Phí vệ sinh & môi trường', 'Thu phí thu gom rác thải', 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(5, 1, 'income', 'Phí quản lý chợ', 'Phí quản lý và an ninh', 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(6, 1, 'expense', 'Chi lương nhân viên BQL', 'Chi lương hàng tháng cho ban quản lý', 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(7, 1, 'expense', 'Chi điện nước chung', 'Chi thanh toán hóa đơn điện nước chung của chợ', 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(8, 1, 'expense', 'Chi sửa chữa bảo trì', 'Chi sửa chữa hạ tầng, phòng cháy chữa cháy', 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(9, 1, 'expense', 'Chi văn phòng phẩm', 'Chi mua sắm CCDC và văn phòng phẩm', 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `income_vouchers`
--

CREATE TABLE `income_vouchers` (
  `voucher_id` int(10) UNSIGNED NOT NULL,
  `market_id` int(11) NOT NULL,
  `voucher_type` enum('income','expense') NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `voucher_date` date NOT NULL,
  `content` varchar(1000) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `document_no` varchar(100) DEFAULT NULL,
  `payer_name` varchar(255) DEFAULT NULL,
  `collector_name` varchar(255) DEFAULT NULL,
  `beneficiary_name` varchar(255) DEFAULT NULL,
  `attachment_path` varchar(500) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=active, 99=soft deleted',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `income_vouchers`
--

INSERT INTO `income_vouchers` (`voucher_id`, `market_id`, `voucher_type`, `category_id`, `voucher_date`, `content`, `amount`, `document_no`, `payer_name`, `collector_name`, `beneficiary_name`, `attachment_path`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(2, 1, 'income', 2, '2026-01-05', 'Thu tiền thuê sạp đợt 1 tháng 01/2026', 120000000.00, 'PT-2601-001', 'Tiểu thương chợ Trung Tâm', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(3, 1, 'income', 3, '2026-01-10', 'Thu tiền điện nước tiêu dùng sạp tháng 12/2025', 18500000.00, 'PT-2601-002', 'Các hộ tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(4, 1, 'income', 4, '2026-01-15', 'Thu phí vệ sinh tháng 01/2026', 12000000.00, 'PT-2601-003', 'Các hộ tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(5, 1, 'expense', 6, '2026-01-28', 'Chi lương BQL tháng 01/2026', 45000000.00, 'PC-2601-001', NULL, NULL, 'Nhân viên BQL', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(6, 1, 'expense', 7, '2026-01-18', 'Thanh toán tiền điện tổng tháng 01/2026 cho EVN', 12500000.00, 'PC-2601-002', NULL, NULL, 'Công ty Điện lực', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(7, 1, 'expense', 9, '2026-01-12', 'Mua văn phòng phẩm chuẩn bị tết 2026', 3500000.00, 'PC-2601-003', NULL, NULL, 'Nhà sách Fahasa', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(8, 1, 'income', 2, '2026-02-06', 'Thu tiền thuê sạp đợt 1 tháng 02/2026', 125000000.00, 'PT-2602-001', 'Tiểu thương chợ Trung Tâm', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(9, 1, 'income', 3, '2026-02-12', 'Thu tiền điện nước tiêu dùng sạp tháng 01/2026', 22000000.00, 'PT-2602-002', 'Các hộ tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(10, 1, 'income', 5, '2026-02-15', 'Thu phí quản lý dịch vụ chợ tháng 02/2026', 15000000.00, 'PT-2602-003', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(11, 1, 'expense', 6, '2026-02-27', 'Chi lương BQL tháng 02/2026', 45000000.00, 'PC-2602-001', NULL, NULL, 'Nhân viên BQL', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(12, 1, 'expense', 7, '2026-02-20', 'Chi tiền điện nước chung tháng 02/2026', 15000000.00, 'PC-2602-002', NULL, NULL, 'Công ty Điện lực & Nước sạch', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(13, 1, 'expense', 8, '2026-02-14', 'Bảo trì hệ thống PCCC khu A chợ', 8200000.00, 'PC-2602-003', NULL, NULL, 'Thiết bị PCCC An Bình', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(14, 1, 'income', 2, '2026-03-05', 'Thu tiền thuê sạp tháng 03/2026', 118000000.00, 'PT-2603-001', 'Tiểu thương chợ Trung Tâm', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(15, 1, 'income', 3, '2026-03-10', 'Thu tiền điện nước tiêu dùng tháng 02/2026', 17000000.00, 'PT-2603-002', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(16, 1, 'expense', 6, '2026-03-28', 'Chi lương BQL tháng 03/2026', 45000000.00, 'PC-2603-001', NULL, NULL, 'Nhân viên BQL', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(17, 1, 'expense', 7, '2026-03-19', 'Chi thanh toán điện tổng tháng 03/2026', 11000000.00, 'PC-2603-002', NULL, NULL, 'Điện lực', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(18, 1, 'income', 2, '2026-04-05', 'Thu tiền thuê sạp tháng 04/2026', 130000000.00, 'PT-2604-001', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(19, 1, 'income', 3, '2026-04-10', 'Thu tiền điện nước tiêu dùng tháng 03/2026', 19800000.00, 'PT-2604-002', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(20, 1, 'income', 4, '2026-04-15', 'Thu phí vệ sinh tháng 04/2026', 12500000.00, 'PT-2604-003', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(21, 1, 'expense', 6, '2026-04-29', 'Chi lương BQL tháng 04/2026', 45000000.00, 'PC-2604-001', NULL, NULL, 'Nhân viên BQL', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(22, 1, 'expense', 7, '2026-04-18', 'Chi tiền điện nước tổng tháng 04/2026', 13400000.00, 'PC-2604-002', NULL, NULL, 'Nhà cung cấp', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(23, 1, 'expense', 8, '2026-04-12', 'Sửa mái tôn khu B bị dột chuẩn bị mùa mưa', 24500000.00, 'PC-2604-003', NULL, NULL, 'Cơ khí Minh Đức', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(24, 1, 'income', 2, '2026-05-05', 'Thu tiền thuê sạp tháng 05/2026', 122000000.00, 'PT-2605-001', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(25, 1, 'income', 3, '2026-05-11', 'Thu tiền điện nước tiêu dùng tháng 04/2026', 24000000.00, 'PT-2605-002', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(26, 1, 'income', 5, '2026-05-15', 'Thu phí quản lý tháng 05/2026', 14800000.00, 'PT-2605-003', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(27, 1, 'expense', 6, '2026-05-28', 'Chi lương BQL tháng 05/2026', 45000000.00, 'PC-2605-001', NULL, NULL, 'Nhân viên BQL', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(28, 1, 'expense', 7, '2026-05-20', 'Chi tiền điện nước chung tháng 05/2026', 16200000.00, 'PC-2605-002', NULL, NULL, 'EVN', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(29, 1, 'expense', 9, '2026-05-08', 'Mua mực in và giấy A4 cho văn phòng', 1200000.00, 'PC-2605-003', NULL, NULL, 'Cửa hàng văn phòng phẩm', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(30, 1, 'income', 2, '2026-06-06', 'Thu tiền thuê sạp tháng 06/2026', 120000000.00, 'PT-2606-001', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(31, 1, 'income', 3, '2026-06-12', 'Thu tiền điện nước tiêu dùng tháng 05/2026', 26500000.00, 'PT-2606-002', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(32, 1, 'income', 4, '2026-06-15', 'Thu phí vệ sinh tháng 06/2026', 12000000.00, 'PT-2606-003', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(33, 1, 'expense', 6, '2026-06-27', 'Chi lương BQL tháng 06/2026 (tăng lương cơ bản)', 48000000.00, 'PC-2606-001', NULL, NULL, 'Nhân viên BQL', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(34, 1, 'expense', 7, '2026-06-19', 'Chi tiền điện nước chung tháng 06/2026', 18900000.00, 'PC-2606-002', NULL, NULL, 'EVN', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(35, 1, 'expense', 8, '2026-06-10', 'Thay thế hệ thống bóng đèn chiếu sáng dãy hành lang', 5600000.00, 'PC-2606-003', NULL, NULL, 'Cửa hàng thiết bị điện', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(36, 1, 'income', 2, '2026-07-05', 'Thu tiền thuê sạp tháng 07/2026', 128000000.00, 'PT-2607-001', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(37, 1, 'income', 3, '2026-07-10', 'Thu tiền điện nước tiêu dùng tháng 06/2026', 28000000.00, 'PT-2607-002', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(38, 1, 'income', 5, '2026-07-15', 'Thu phí quản lý tháng 07/2026', 15500000.00, 'PT-2607-003', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(39, 1, 'expense', 6, '2026-07-29', 'Chi lương BQL tháng 07/2026', 48000000.00, 'PC-2607-001', NULL, NULL, 'Nhân viên BQL', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(40, 1, 'expense', 7, '2026-07-21', 'Chi tiền điện nước chung tháng 07/2026', 20500000.00, 'PC-2607-002', NULL, NULL, 'EVN', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(41, 1, 'income', 2, '2026-08-03', 'Thu tiền thuê sạp tháng 08/2026', 135000000.00, 'PT-2608-001', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(42, 1, 'income', 3, '2026-08-12', 'Thu tiền điện nước tiêu dùng tháng 07/2026', 32000000.00, 'PT-2608-002', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(43, 1, 'income', 4, '2026-08-15', 'Thu phí vệ sinh tháng 08/2026', 13000000.00, 'PT-2608-003', 'Tiểu thương', NULL, NULL, NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(44, 1, 'expense', 6, '2026-08-28', 'Chi lương BQL tháng 08/2026', 48000000.00, 'PC-2608-001', NULL, NULL, 'Nhân viên BQL', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(45, 1, 'expense', 7, '2026-08-20', 'Chi tiền điện nước chung tháng 08/2026', 23000000.00, 'PC-2608-002', NULL, NULL, 'EVN', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55'),
(46, 1, 'expense', 8, '2026-08-08', 'Sơn lại cổng chào Chợ Trung Tâm', 15000000.00, 'PC-2608-003', NULL, NULL, 'Đại lý sơn Kova', NULL, 1, 1, '2026-08-03 11:33:55', '2026-08-03 11:33:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `markets`
--

CREATE TABLE `markets` (
  `market_id` int(11) NOT NULL,
  `market_code` varchar(50) NOT NULL,
  `market_name` varchar(150) NOT NULL,
  `market_phone` varchar(20) DEFAULT NULL,
  `market_email` varchar(100) DEFAULT NULL,
  `market_manager_name` varchar(100) DEFAULT NULL,
  `market_logo` varchar(255) DEFAULT NULL,
  `market_province_id` int(11) DEFAULT NULL,
  `market_district_id` int(11) DEFAULT NULL,
  `market_ward_id` int(11) DEFAULT NULL,
  `market_latitude` decimal(10,8) DEFAULT NULL,
  `market_longitude` decimal(11,8) DEFAULT NULL,
  `market_status_code` varchar(50) DEFAULT 'active',
  `market_created_by` int(11) DEFAULT NULL,
  `market_updated_by` int(11) DEFAULT NULL,
  `market_created_at` datetime DEFAULT current_timestamp(),
  `market_updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `markets`
--

INSERT INTO `markets` (`market_id`, `market_code`, `market_name`, `market_phone`, `market_email`, `market_manager_name`, `market_logo`, `market_province_id`, `market_district_id`, `market_ward_id`, `market_latitude`, `market_longitude`, `market_status_code`, `market_created_by`, `market_updated_by`, `market_created_at`, `market_updated_at`) VALUES
(1, 'CHO_TT', 'Chợ Trung Tâm', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2026-07-11 20:09:20', '2026-07-11 20:09:20'),
(2, 'CHO_BT', '11111111', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2026-07-16 19:35:05', '2026-07-17 02:25:36'),
(3, 'CHO_AD', 'Controller Test Update', '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2026-07-16 19:35:05', '2026-07-17 01:40:00'),
(4, '111', '11111', '111', 'conghai724@gmail.com', '111', NULL, NULL, NULL, NULL, NULL, NULL, 'deleted', NULL, NULL, '2026-07-17 02:39:42', '2026-07-17 02:55:01'),
(5, '1', '1', '0987654321', 'conghai724@gmail.com', '111', NULL, NULL, NULL, NULL, NULL, NULL, 'active', NULL, NULL, '2026-07-17 02:55:23', '2026-07-17 02:55:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `market_map_elements`
--

CREATE TABLE `market_map_elements` (
  `element_id` int(11) NOT NULL,
  `element_type` varchar(50) NOT NULL,
  `element_name` varchar(100) DEFAULT NULL,
  `element_stall_id` int(11) DEFAULT NULL,
  `element_pos_x` int(11) NOT NULL DEFAULT 100,
  `element_pos_y` int(11) NOT NULL DEFAULT 100,
  `element_width` int(11) NOT NULL DEFAULT 80,
  `element_height` int(11) NOT NULL DEFAULT 60,
  `element_rotation` int(11) NOT NULL DEFAULT 0,
  `element_color` varchar(20) DEFAULT NULL,
  `element_waypoints` text DEFAULT NULL,
  `element_stroke_width` int(11) DEFAULT 24
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `market_map_elements`
--

INSERT INTO `market_map_elements` (`element_id`, `element_type`, `element_name`, `element_stall_id`, `element_pos_x`, `element_pos_y`, `element_width`, `element_height`, `element_rotation`, `element_color`, `element_waypoints`, `element_stroke_width`) VALUES
(141, 'stall', 'SẠP-A01', 1, 100, 160, 40, 40, 0, NULL, NULL, 24),
(142, 'stall', 'SẠP-B12', 8, 100, 280, 40, 40, 0, NULL, NULL, 24),
(143, 'stall', 'SẠP-C02', 10, 100, 380, 40, 40, 0, NULL, NULL, 24),
(144, 'fence', 'Hàng rào', NULL, 640, 60, 316, 176, 0, '#ddc9b0', '[{\"x\":648,\"y\":228},{\"x\":828,\"y\":228},{\"x\":948,\"y\":68}]', 16),
(145, 'fence', 'Hàng rào', NULL, 60, 140, 336, 428, 0, '#ddc9b0', '[{\"x\":68,\"y\":528},{\"x\":68,\"y\":148},{\"x\":208,\"y\":148},{\"x\":248,\"y\":148},{\"x\":308,\"y\":148},{\"x\":388,\"y\":148},{\"x\":388,\"y\":560}]', 16),
(146, 'fence', 'Hàng rào', NULL, 432, 272, 316, 136, 0, '#ddc9b0', '[{\"x\":440,\"y\":400},{\"x\":640,\"y\":400},{\"x\":740,\"y\":280}]', 16),
(147, 'fence', 'Hàng rào', NULL, 372, 560, 256, 16, 0, '#ddc9b0', '[{\"x\":380,\"y\":568},{\"x\":620,\"y\":568}]', 16);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `receipts_payments`
--

CREATE TABLE `receipts_payments` (
  `transaction_id` int(11) NOT NULL,
  `transaction_code` varchar(50) NOT NULL,
  `transaction_type` varchar(10) NOT NULL,
  `transaction_amount` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_category` varchar(100) NOT NULL,
  `transaction_note` text DEFAULT NULL,
  `transaction_reference_id` int(11) DEFAULT NULL,
  `transaction_created_by` int(11) NOT NULL,
  `transaction_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `transaction_market_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `receipts_payments`
--

INSERT INTO `receipts_payments` (`transaction_id`, `transaction_code`, `transaction_type`, `transaction_amount`, `transaction_date`, `transaction_category`, `transaction_note`, `transaction_reference_id`, `transaction_created_by`, `transaction_created_at`, `transaction_market_id`) VALUES
(1, 'PT-0001', 'receipt', 2626000.00, '2026-06-28', 'Thu tiền hóa đơn', 'Thu tiền hóa đơn HD-202606-002 sạp SẠP-B01 tháng 06/2026', 2, 3, '2026-07-08 09:32:18', 1),
(2, 'PC-0001', 'payment', 12500000.00, '2026-06-29', 'Điện nước chung', 'Thanh toán tiền điện tổng của chợ tháng 06/2026 cho EVN', NULL, 3, '2026-07-08 09:32:18', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stalls`
--

CREATE TABLE `stalls` (
  `stall_id` int(11) NOT NULL,
  `stall_area_id` int(11) NOT NULL,
  `stall_code` varchar(50) NOT NULL,
  `stall_type_id` int(11) NOT NULL,
  `stall_area_size` decimal(10,2) NOT NULL,
  `stall_base_price` decimal(15,2) NOT NULL,
  `stall_status_id` int(11) NOT NULL DEFAULT 3,
  `stall_map_coordinate_x` decimal(10,2) DEFAULT NULL,
  `stall_map_coordinate_y` decimal(10,2) DEFAULT NULL,
  `stall_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stall_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `stalls`
--

INSERT INTO `stalls` (`stall_id`, `stall_area_id`, `stall_code`, `stall_type_id`, `stall_area_size`, `stall_base_price`, `stall_status_id`, `stall_map_coordinate_x`, `stall_map_coordinate_y`, `stall_created_at`, `stall_updated_at`) VALUES
(1, 1, 'SẠP-A01', 2, 5.00, 4.00, 4, 100.00, 160.00, '2026-07-08 09:32:18', '2026-07-10 16:05:52'),
(2, 1, 'SẠP-A02', 2, 15.00, 3500000.00, 3, NULL, NULL, '2026-07-08 09:32:18', '2026-07-26 18:32:46'),
(3, 1, 'SẠP-A03', 2, 10.00, 2000000.00, 3, NULL, NULL, '2026-07-08 09:32:18', '2026-07-27 22:09:08'),
(4, 1, 'SẠP-A04', 2, 10.00, 2000000.00, 4, NULL, NULL, '2026-07-08 09:32:18', '2026-07-27 20:29:13'),
(5, 1, 'SẠP-A05', 2, 10.00, 2000000.00, 3, NULL, NULL, '2026-07-08 09:32:18', '2026-07-27 19:29:37'),
(6, 2, 'SẠP-B01', 2, 8.00, 1500000.00, 4, NULL, NULL, '2026-07-08 09:32:18', '2026-07-26 18:33:16'),
(7, 2, 'SẠP-B02', 2, 8.00, 1500000.00, 3, NULL, NULL, '2026-07-08 09:32:18', '2026-07-26 18:32:46'),
(8, 3, 'SẠP-B12', 2, 12.00, 1800000.00, 3, 100.00, 280.00, '2026-07-08 09:32:18', '2026-07-16 12:36:43'),
(9, 4, 'SẠP-C01', 2, 12.00, 2500000.00, 3, NULL, NULL, '2026-07-08 09:32:18', '2026-07-16 12:36:43'),
(10, 4, 'SẠP-C02', 2, 12.00, 2500000.00, 3, 100.00, 380.00, '2026-07-08 09:32:18', '2026-07-26 18:32:46'),
(11, 4, '1', 12, 1.00, 2.00, 4, NULL, NULL, '2026-07-11 13:09:53', '2026-07-16 11:52:36'),
(12, 4, 'hhhhh', 12, 66.00, 66.00, 3, NULL, NULL, '2026-07-16 14:06:01', '2026-07-16 14:06:01'),
(13, 1, 'TEST-STALL-6a65464f4a1ed', 1, 15.50, 2000000.00, 3, NULL, NULL, '2026-07-25 23:27:11', '2026-07-25 23:27:11'),
(14, 1, 'TEST-STALL-6a6546e5bd5f2', 1, 15.50, 2000000.00, 4, NULL, NULL, '2026-07-25 23:29:41', '2026-07-27 22:14:23'),
(16, 1, '1111111111', 3, 2222.00, 222200.00, 4, NULL, NULL, '2026-07-25 23:40:40', '2026-07-27 19:31:29'),
(17, 1, '11111111111', 3, 1111111.00, 11111111.00, 3, NULL, NULL, '2026-07-26 17:34:08', '2026-07-26 17:34:08'),
(18, 1, '9876', 6, 6666.00, 6666.00, 3, NULL, NULL, '2026-07-26 17:34:40', '2026-07-26 17:34:40'),
(19, 1, '567', 6, 45.00, 123.00, 4, 5.00, 9.00, '2026-07-28 01:32:16', '2026-07-31 22:09:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stall_types`
--

CREATE TABLE `stall_types` (
  `stall_type_id` int(11) NOT NULL,
  `stall_type_code` varchar(50) NOT NULL,
  `stall_type_name` varchar(100) NOT NULL,
  `stall_type_description` text DEFAULT NULL,
  `stall_type_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stall_type_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `stall_types`
--

INSERT INTO `stall_types` (`stall_type_id`, `stall_type_code`, `stall_type_name`, `stall_type_description`, `stall_type_created_at`, `stall_type_updated_at`) VALUES
(1, 'kiot', 'Kiot', NULL, '2026-07-10 16:05:52', '2026-07-10 16:05:52'),
(2, 'quay_hang', 'Quầy hàng', NULL, '2026-07-10 16:05:52', '2026-07-10 16:05:52'),
(3, 'mat_bang_trong', 'Mặt bằng trống', NULL, '2026-07-10 16:05:52', '2026-07-10 16:05:52'),
(6, 'kiki', 'KIKI', '', '2026-07-11 11:43:07', '2026-07-11 11:43:07'),
(12, 'a', 'g', 'g', '2026-07-11 12:24:53', '2026-07-11 12:24:53'),
(15, '1', '1', '1', '2026-07-30 11:29:54', '2026-07-30 11:29:54'),
(16, '2', '2', '2', '2026-07-30 11:55:21', '2026-07-30 11:55:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `status_colors`
--

CREATE TABLE `status_colors` (
  `color_id` int(11) NOT NULL,
  `color_class` varchar(50) NOT NULL,
  `color_description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `status_colors`
--

INSERT INTO `status_colors` (`color_id`, `color_class`, `color_description`) VALUES
(1, 'status-green', 'Xanh lá (Thành công, Hoạt động, Đã thanh toán)'),
(2, 'status-red', 'Đỏ (Khóa, Hủy, Chưa thanh toán, Lỗi)'),
(3, 'status-gray', 'Xám (Trống, Hết hạn, Đã xóa)'),
(4, 'status-orange', 'Cam (Đang sửa, Tạm dừng)'),
(5, 'status-blue', 'Xanh dương (Thanh lý, Thông tin phụ)'),
(6, 'status-yellow', 'Vàng (Kế hoạch, Chờ xử lý)'),
(7, 'status-white', 'Trắng (Trống/Chưa sử dụng)');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_actors`
--

CREATE TABLE `system_actors` (
  `actor_id` int(11) NOT NULL,
  `actor_code` varchar(50) NOT NULL,
  `actor_name` varchar(100) NOT NULL,
  `actor_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `system_actors`
--

INSERT INTO `system_actors` (`actor_id`, `actor_code`, `actor_name`, `actor_description`) VALUES
(1, 'super_market', 'Quản trị viên tối cao (Super Admin)', 'Toàn quyền'),
(2, 'admin_market', 'Quản trị viên(Market Manager)', 'Quyền'),
(3, 'admin', 'Nhân viên vận hành(Staff)', 'Tùy');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_logs`
--

CREATE TABLE `system_logs` (
  `log_id` int(11) NOT NULL,
  `log_user_id` int(11) DEFAULT NULL,
  `log_action_type` varchar(50) NOT NULL,
  `log_action_description` text NOT NULL,
  `log_ip_address` varchar(45) DEFAULT NULL,
  `log_user_agent` text DEFAULT NULL,
  `log_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `system_logs`
--

INSERT INTO `system_logs` (`log_id`, `log_user_id`, `log_action_type`, `log_action_description`, `log_ip_address`, `log_user_agent`, `log_created_at`) VALUES
(1, 1, 'login', 'Đăng nhập hệ thống thành công', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0.0.0', '2026-07-08 09:32:18'),
(2, 1, 'create', 'Thêm mới phụ lục hợp đồng số PL-SA01-2026-01 cho HĐ-SA01-2026', '192.168.1.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/126.0.0.0', '2026-07-08 09:32:18'),
(3, 2, 'update_trader', 'Cập nhật tiểu thương: tt2 (Mã: tt2, ID: 12)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:07:01'),
(4, 2, 'reactivate_contract', 'Tái kích hoạt hợp đồng số: HĐ-GAN-20260728-921 (ID: 16)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:09:55'),
(5, 2, 'activate_contract', 'Kích hoạt hợp đồng số: HĐ-GAN-20260728-921 (ID: 16)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:10:00'),
(6, 4, 'update_category', 'Cập nhật danh mục Khu vực: 1 (ID: 7)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:16:17'),
(7, 4, 'delete_category', 'Xóa danh mục Khu vực: 1 (ID: 7)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:16:33'),
(8, 2, 'save_voucher', 'Tạo mới phiếu Thu ID: 1 (Nội dung: 1, Số tiền: 1đ)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:21:51'),
(9, 2, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:25:53'),
(10, NULL, 'login_failed', 'Đăng nhập thất bại. Sai mật khẩu cho tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:26:06'),
(11, 2, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:26:11'),
(12, 2, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:27:15'),
(13, NULL, 'login_failed', 'Đăng nhập thất bại. Sai mật khẩu cho tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:27:36'),
(14, 2, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:27:40'),
(15, 4, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: vanhanh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:28:53'),
(16, 2, 'update_permissions', 'Gán quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:42:27'),
(17, 2, 'update_permissions', 'Gán quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:42:33'),
(18, 2, 'update_permissions', 'Gán quyền phân hệ \'An toàn thực phẩm\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:42:33'),
(19, 2, 'update_permissions', 'Thu hồi quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:42:38'),
(20, 2, 'update_permissions', 'Gán quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:42:40'),
(21, 2, 'update_permissions', 'Thu hồi quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:42:43'),
(22, 2, 'update_permissions', 'Gán quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:42:46'),
(23, 2, 'update_permissions', 'Thu hồi quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: 11111111', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:50:05'),
(24, 2, 'update_permissions', 'Gán quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: 11111111', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 22:50:08'),
(25, 2, 'update_trader', 'Cập nhật tiểu thương: tt2 (Mã: tt2, ID: 12)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 23:07:38'),
(26, 2, 'update_trader', 'Cập nhật tiểu thương: tt22 (Mã: tt2, ID: 12)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-07-31 23:07:46'),
(27, 2, 'update_permissions', 'Gán quyền phân hệ \'Tiểu thương\' cho nhân viên: vanhanh tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-01 02:05:59'),
(28, 2, 'update_permissions', 'Gán quyền phân hệ \'An toàn thực phẩm\' cho nhân viên: vanhanh tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-01 02:05:59'),
(29, 2, 'update_user', 'Cập nhật tài khoản nhân viên: 222 (ID: 8, Họ tên: 222, Vai trò: admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-01 03:36:17'),
(30, 1, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-01 03:41:29'),
(31, 2, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-01 05:30:40'),
(32, 2, 'update_permissions', 'Thu hồi quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-01 05:31:03'),
(33, 2, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:38:44'),
(34, NULL, 'login_failed', 'Đăng nhập thất bại. Tài khoản không tồn tại hoặc bị khóa: adminadmin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:51:02'),
(35, 4, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: vanhanh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:51:15'),
(36, NULL, 'login_failed', 'Đăng nhập thất bại. Tài khoản không tồn tại hoặc bị khóa: vanhanhadmin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:51:51'),
(37, 1, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:52:21'),
(38, 1, 'update_permissions', 'Thu hồi quyền phân hệ \'An toàn thực phẩm\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:53:40'),
(39, 1, 'update_permissions', 'Thu hồi quyền phân hệ \'Sạp chợ\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:53:40'),
(40, 1, 'update_permissions', 'Thu hồi quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:55:07'),
(41, 1, 'update_permissions', 'Gán quyền phân hệ \'Hợp đồng\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:55:07'),
(42, 1, 'update_permissions', 'Gán quyền phân hệ \'Tài chính\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:55:07'),
(43, 1, 'update_permissions', 'Thu hồi quyền phân hệ \'An toàn thực phẩm\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:55:07'),
(44, 1, 'update_permissions', 'Gán quyền phân hệ \'Sạp chợ\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:55:21'),
(45, 1, 'update_permissions', 'Thu hồi quyền phân hệ \'Sạp chợ\' cho nhân viên: 222 tại chợ: Controller Test Update', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 00:55:34'),
(46, 1, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: admin', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 01:44:47'),
(47, 2, 'update_permissions', 'Gán quyền phân hệ \'Sạp chợ\' cho nhân viên: vanhanh tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 02:20:01'),
(48, 4, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: vanhanh', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 02:20:28'),
(49, 1, 'update_permissions', 'Gán quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 02:51:52'),
(50, 2, 'update_trader', 'Cập nhật tiểu thương: hhhhhhhhhhhhh (Mã: 2hhhhhhhh, ID: 10)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 03:38:44'),
(51, 2, 'update_permissions', 'Thu hồi quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 03:38:50'),
(52, 2, 'update_permissions', 'Gán quyền phân hệ \'Tiểu thương\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 03:38:52'),
(53, 2, 'update_permissions', 'Gán quyền phân hệ \'Sạp chợ\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 06:26:04'),
(54, 2, 'update_permissions', 'Gán quyền phân hệ \'An toàn thực phẩm\' cho nhân viên: 222 tại chợ: Chợ Trung Tâm', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 06:26:07'),
(55, 3, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: ketoan1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 06:26:45'),
(56, 2, 'login', 'Đăng nhập hệ thống thành công. Tài khoản: nhanvien1', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 06:27:07'),
(57, 2, 'create_user', 'Tạo mới tài khoản nhân viên: nhanvien11 (ID: 9, Họ tên: 11111111, Vai trò: admin)', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '2026-08-03 06:27:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_statuses`
--

CREATE TABLE `system_statuses` (
  `status_id` int(11) NOT NULL,
  `status_domain` varchar(50) NOT NULL,
  `status_code` varchar(50) NOT NULL,
  `status_name` varchar(100) NOT NULL,
  `status_color_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `system_statuses`
--

INSERT INTO `system_statuses` (`status_id`, `status_domain`, `status_code`, `status_name`, `status_color_id`) VALUES
(1, 'user', 'active', 'Hoạt động', 1),
(2, 'user', 'locked', 'Bị khóa', 2),
(3, 'stall', 'empty', 'Trống', 7),
(4, 'stall', 'rented', 'Đã thuê', 1),
(5, 'stall', 'repairing', 'Đang sửa chữa', 6),
(6, 'stall', 'locked', 'Tạm khóa', 2),
(7, 'trader', 'active', 'Đang kinh doanh', 1),
(8, 'trader', 'suspended', 'Tạm dừng', 6),
(9, 'trader', 'closed', 'Ngừng kinh doanh', 2),
(11, 'contract', 'active', 'Hoạt động', 1),
(12, 'contract', 'expired', 'Hết hạn', 3),
(13, 'contract', 'liquidated', 'Thanh lý', 5),
(14, 'contract', 'terminated', 'Chấm dứt trước hạn', 2),
(15, 'bill', 'unpaid', 'Chưa thanh toán', 2),
(16, 'bill', 'partially_paid', 'Trả một phần', 6),
(17, 'bill', 'paid', 'Đã thanh toán', 1),
(18, 'attp', 'valid', 'Còn hạn', 1),
(19, 'attp', 'expired', 'Hết hạn', 2),
(20, 'inspection', 'planned', 'Chưa thực hiện', 6),
(21, 'inspection', 'completed', 'Đã thực hiện', 1),
(22, 'inspection', 'cancelled', 'Đã hủy', 2),
(23, 'violation', 'pending', 'Đang xử lý', 2),
(24, 'violation', 'resolved', 'Đã chấp hành xong', 1),
(27, 'contract', 'draft', 'Khởi tạo', 6),
(99, 'global', '99', 'Đã xóa', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `traders`
--

CREATE TABLE `traders` (
  `trader_id` int(11) NOT NULL,
  `trader_code` varchar(50) NOT NULL,
  `trader_fullname` varchar(100) NOT NULL,
  `trader_phone` varchar(15) NOT NULL,
  `trader_cccd` varchar(20) NOT NULL,
  `trader_business_line_id` int(11) DEFAULT NULL,
  `trader_address` text DEFAULT NULL,
  `trader_description` text DEFAULT NULL,
  `trader_license_file` text DEFAULT NULL,
  `trader_status_id` int(11) NOT NULL,
  `trader_market_id` int(11) DEFAULT NULL,
  `trader_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `trader_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `traders`
--

INSERT INTO `traders` (`trader_id`, `trader_code`, `trader_fullname`, `trader_phone`, `trader_cccd`, `trader_business_line_id`, `trader_address`, `trader_description`, `trader_license_file`, `trader_status_id`, `trader_market_id`, `trader_created_at`, `trader_updated_at`) VALUES
(1, 'TT-0001', 'Nguyễn Thị Thu Hà', '0912345678', '001195001234', 1, '12 Phố Huế, Hai Bà Trưng, Hà Nội', NULL, NULL, 7, 2, '2026-07-08 09:32:18', '2026-07-26 16:31:17'),
(2, 'TT-0002', 'Trần Văn Hoàng', '0987654321', '002196005678', 2, '45 Đại Cồ Việt, Bách Khoa, Hà Nội', NULL, NULL, 7, 1, '2026-07-08 09:32:18', '2026-07-26 16:31:17'),
(3, 'TT-0003', 'Phạm Minh Tuấn', '0905112233', '003197009012', 1, '78 Lò Đúc, Đống Đa, Hà Nội', '', NULL, 7, 1, '2026-07-08 09:32:18', '2026-07-26 16:31:17'),
(4, 'TT-0004', 'Mai Thị Thoaa', '0934556670', '004198003456', 2, '99 Bạch Mai, Hai Bà Trưng, Hà Nội', '', NULL, 7, 1, '2026-07-08 09:32:18', '2026-07-26 23:04:51'),
(5, '1', '1', '0353878958', '123456789012', 3, '1', '1', NULL, 99, 1, '2026-07-11 13:07:40', '2026-07-26 19:37:49'),
(6, 'TEST-TRADER-6a654774a9ec3', 'Nguyễn Văn Test', '0912345670', '001203386176', 1, '123 Đường Test, Hà Nội', 'Bán hàng thử nghiệm', '[\"danh_sach_tieu_thuong__3__1785085047_6a663c778cb84.pdf\",\"danh_sach_tieu_thuong__2__1785085047_6a663c778dfb2.pdf\"]', 7, 1, '2026-07-25 23:32:04', '2026-07-26 23:04:56'),
(7, '111111111111111', '111111111', '0987654321', '123456789054', NULL, '11111111111', '11111111111', NULL, 99, 1, '2026-07-25 23:41:25', '2026-07-26 19:37:49'),
(8, '1111111111111', '1111111111', '0987654321', '987654321321', 3, '111111', '111111111', NULL, 99, 1, '2026-07-25 23:44:13', '2026-07-26 19:37:49'),
(9, '2211111111111', '1111111111111', '0987654326', '111111111116', NULL, '1', '1', NULL, 99, 1, '2026-07-25 23:55:44', '2026-07-26 19:37:49'),
(10, '2hhhhhhhh', 'hhhhhhhhhhhhh', '0987654321', '222222222212', 3, 'h', 'h', NULL, 7, 1, '2026-07-26 17:38:43', '2026-07-26 17:38:43'),
(11, 'tt1', 'tt11', '0987654321', '123456789456', 3, 'tt1', 'tt1', NULL, 7, 1, '2026-07-26 18:22:44', '2026-07-26 20:26:50'),
(12, 'tt2', 'tt22', '0987654321', '654456654456', 3, 'tt2', '', NULL, 7, 1, '2026-07-26 18:23:46', '2026-07-31 23:07:46'),
(13, '777', '777', '0987654321', '222222222111', 3, '1', '1', NULL, 9, 3, '2026-07-26 23:17:32', '2026-07-26 23:17:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trader_attp`
--

CREATE TABLE `trader_attp` (
  `attp_id` int(11) NOT NULL,
  `attp_trader_id` int(11) NOT NULL,
  `attp_doc_type_id` int(11) NOT NULL,
  `attp_doc_number` varchar(100) NOT NULL,
  `attp_name` varchar(255) NOT NULL,
  `attp_description` text DEFAULT NULL,
  `attp_file` varchar(255) DEFAULT NULL,
  `attp_status_id` int(11) NOT NULL DEFAULT 18,
  `attp_issuer` varchar(150) DEFAULT NULL,
  `attp_issue_date` date NOT NULL,
  `attp_expiry_date` date NOT NULL,
  `attp_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `attp_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `trader_attp`
--

INSERT INTO `trader_attp` (`attp_id`, `attp_trader_id`, `attp_doc_type_id`, `attp_doc_number`, `attp_name`, `attp_description`, `attp_file`, `attp_status_id`, `attp_issuer`, `attp_issue_date`, `attp_expiry_date`, `attp_created_at`, `attp_updated_at`) VALUES
(1, 2, 1, '123/2025/ATTP-HN', 'Giấy chứng nhận cơ sở đủ điều kiện ATTP', 'Giấy chứng nhận ATTP cho sạp kinh doanh giò chả', NULL, 18, 'Chi cục ATTP Hà Nội', '2025-05-10', '2028-05-10', '2026-07-08 09:32:18', '2026-07-10 16:05:53'),
(2, 2, 2, 'GKSK-9988', 'Giấy khám sức khỏe định kỳ năm 2026', 'Giấy khám sức khỏe tiểu thương Trần Văn Hoàng', NULL, 19, 'Bệnh viện Quận 1', '2026-01-10', '2026-07-28', '2026-07-08 09:32:18', '2026-07-30 11:02:47'),
(3, 4, 1, '456/2024/ATTP-HN', 'Giấy chứng nhận cơ sở đủ điều kiện ATTP', 'Giấy chứng nhận kinh doanh rau củ quả sạch', NULL, 19, 'Chi cục ATTP Hà Nội', '2026-07-07', '2026-07-09', '2026-07-08 09:32:18', '2026-07-10 16:05:53'),
(4, 4, 3, 'TH-ATTP-2026', 'Giấy xác nhận tập huấn kiến thức ATTP', 'Xác nhận tập huấn kiến thức an toàn thực phẩm sạp Mai Lê', NULL, 18, 'Trung tâm Y tế Dự phòng Quận', '2026-02-15', '2029-02-15', '2026-07-08 09:32:18', '2026-07-10 16:05:53'),
(5, 12, 3, '111111111111111111111', '1111', '', 'danh_sach_tieu_thuong__2__1785111338_6a66a32a99c19.pdf', 18, '9999', '2025-07-11', '2026-08-28', '2026-07-11 13:11:45', '2026-07-27 00:29:01'),
(6, 4, 3, '4', '44', '', 'file_6a5241b2ac9b37.35448875.pdf', 18, '4', '2026-11-11', '2026-12-11', '2026-07-11 13:14:26', '2026-07-11 13:16:36'),
(7, 1, 1, '111111111111', '11111111', 'hhh', 'danh_sach_tieu_thuong__2__1785106370_6a668fc2ab5f6.pdf', 18, '1111111111', '2025-11-11', '2026-11-11', '2026-07-26 22:52:50', '2026-07-26 22:52:50'),
(8, 12, 3, '1111111111111', '111111111111111', '1111', NULL, 18, '111111111111', '2025-11-11', '2026-09-28', '2026-07-26 23:05:38', '2026-07-27 00:29:56'),
(9, 11, 4, '123', '123', '123', 'danh_sach_tieu_thuong__3__1785107178_6a6692ea94eb5.pdf', 99, '123', '2025-11-11', '2026-11-11', '2026-07-26 23:06:18', '2026-07-26 23:08:00'),
(10, 12, 4, '1111', '1111', '1', NULL, 18, '1111', '2026-11-11', '2027-11-11', '2026-07-27 00:38:48', '2026-07-27 00:38:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_username` varchar(50) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_is_active` tinyint(1) NOT NULL DEFAULT 1,
  `user_reset_token` varchar(255) DEFAULT NULL,
  `user_reset_expires_at` datetime DEFAULT NULL,
  `user_group` int(11) NOT NULL DEFAULT 2,
  `user_actor_id` int(11) NOT NULL,
  `user_status` int(1) NOT NULL,
  `user_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `user_username`, `user_password`, `user_fullname`, `user_email`, `user_is_active`, `user_reset_token`, `user_reset_expires_at`, `user_group`, `user_actor_id`, `user_status`, `user_created_at`, `user_updated_at`) VALUES
(1, 'admin', '$2y$10$SBwGNnuGDBqyBCiuAI3psuJe2y4uFAHpESc9v1bq5aGe.afbSATS6', 'Quáº£n trá»‹ tá»‘i cao (Super Market)', 'bql.cho@gmail.com', 1, NULL, NULL, 1, 1, 3, '2026-07-08 09:32:18', '2026-07-31 19:48:39'),
(2, 'nhanvien1', '$2y$10$INIFxkRp6LY6iI.PmA8SzOQ/pS8jzf79290WE4EE8YyNAx8U5aYM6', '(Admin Market)', 'nvthu.cho@gmail.com', 1, NULL, NULL, 2, 2, 0, '2026-07-08 09:32:18', '2026-07-31 21:49:18'),
(3, 'ketoan1', '$2y$10$INIFxkRp6LY6iI.PmA8SzOQ/pS8jzf79290WE4EE8YyNAx8U5aYM6', '11111111', 'ketoan.cho@gmail.com', 1, NULL, NULL, 2, 3, 0, '2026-07-08 09:32:18', '2026-07-16 20:07:55'),
(4, 'vanhanh', '$2y$10$P5F9QrxXqYAJyk970mcagOlnh6XGdKbdfsUCgGNQyl6VUqSGayQBe', 'Công Hải HAHA', 'conghai724123@gmail.com', 1, NULL, NULL, 2, 3, 0, '2026-07-16 20:23:33', '2026-07-31 21:52:31'),
(8, '222', '$2y$10$Cx5VlSn2b7iLNKC4l7B8/e/aCdndTaf8wKX0uEygiElRvOpfdPjLC', '222', 'conghai72224@gmail.com', 1, NULL, NULL, 2, 3, 0, '2026-07-31 21:53:04', '2026-07-31 21:53:04'),
(9, 'nhanvien11', '$2y$10$rKWArWZH5ocrET1rAogEquPGYRJjtaYAgrsZ.dO1Pv.ma1tXRRvKK', '11111111', 'conghai7241111@gmail.com', 1, NULL, NULL, 2, 3, 0, '2026-08-03 06:27:33', '2026-08-03 06:27:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_markets`
--

CREATE TABLE `user_markets` (
  `user_market_id` int(11) NOT NULL,
  `user_market_user_id` int(11) NOT NULL,
  `user_market_market_id` int(11) NOT NULL,
  `user_market_role_id` int(11) NOT NULL,
  `user_market_created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_markets`
--

INSERT INTO `user_markets` (`user_market_id`, `user_market_user_id`, `user_market_market_id`, `user_market_role_id`, `user_market_created_at`) VALUES
(2, 2, 1, 2, '2026-07-11 20:09:21'),
(4, 2, 2, 4, '2026-07-16 19:35:05'),
(5, 2, 3, 4, '2026-07-16 19:35:05'),
(40, 3, 5, 2, '2026-07-17 03:22:37'),
(47, 3, 1, 2, '2026-08-01 03:53:51'),
(48, 3, 2, 2, '2026-08-01 03:53:51'),
(49, 3, 3, 2, '2026-08-01 03:53:51'),
(67, 4, 1, 2, '2026-08-01 04:52:22'),
(68, 4, 2, 2, '2026-08-01 04:52:22'),
(69, 4, 3, 2, '2026-08-01 04:52:22'),
(73, 8, 1, 2, '2026-08-01 10:36:17'),
(74, 8, 2, 2, '2026-08-01 10:36:17'),
(75, 8, 3, 2, '2026-08-01 10:36:17'),
(76, 9, 1, 2, '2026-08-03 13:27:33'),
(77, 9, 2, 2, '2026-08-03 13:27:33'),
(78, 9, 3, 2, '2026-08-03 13:27:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_market_permissions`
--

CREATE TABLE `user_market_permissions` (
  `permission_id` int(11) NOT NULL,
  `permission_user_id` int(11) NOT NULL,
  `permission_market_id` int(11) NOT NULL,
  `permission_module_code` varchar(50) NOT NULL,
  `permission_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_market_permissions`
--

INSERT INTO `user_market_permissions` (`permission_id`, `permission_user_id`, `permission_market_id`, `permission_module_code`, `permission_created_at`) VALUES
(1, 3, 1, 'finance', '2026-07-16 12:03:26'),
(5, 3, 2, 'finance', '2026-07-16 12:35:05'),
(8, 3, 3, 'trader', '2026-07-16 12:35:05'),
(15, 3, 3, 'stall', '2026-07-16 20:19:28'),
(16, 3, 1, 'contract', '2026-07-16 20:19:29'),
(17, 3, 2, 'contract', '2026-07-16 20:19:29'),
(23, 4, 3, 'contract', '2026-07-16 20:28:29'),
(25, 4, 3, 'finance', '2026-07-16 20:28:33'),
(32, 4, 3, 'foodsafety', '2026-07-16 20:35:29'),
(33, 4, 3, 'stall', '2026-07-16 20:35:31'),
(40, 4, 2, 'trader', '2026-07-31 21:50:09'),
(41, 4, 2, 'contract', '2026-07-31 21:50:09'),
(42, 4, 2, 'stall', '2026-07-31 21:50:09'),
(43, 4, 2, 'finance', '2026-07-31 21:50:09'),
(44, 4, 2, 'foodsafety', '2026-07-31 21:50:09'),
(51, 8, 1, 'contract', '2026-07-31 21:56:10'),
(52, 8, 1, 'finance', '2026-07-31 21:56:14'),
(58, 8, 2, 'stall', '2026-07-31 21:56:42'),
(64, 8, 2, 'trader', '2026-07-31 22:50:08'),
(65, 4, 1, 'trader', '2026-08-01 02:05:59'),
(66, 4, 1, 'foodsafety', '2026-08-01 02:05:59'),
(67, 8, 3, 'contract', '2026-08-03 00:55:07'),
(68, 8, 3, 'finance', '2026-08-03 00:55:07'),
(70, 4, 1, 'stall', '2026-08-03 02:20:01'),
(72, 8, 1, 'trader', '2026-08-03 03:38:52'),
(73, 8, 1, 'stall', '2026-08-03 06:26:04'),
(74, 8, 1, 'foodsafety', '2026-08-03 06:26:07');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `utility_readings`
--

CREATE TABLE `utility_readings` (
  `reading_id` int(11) NOT NULL,
  `reading_stall_id` int(11) NOT NULL,
  `reading_date` date NOT NULL,
  `reading_electric_old` int(11) NOT NULL,
  `reading_electric_new` int(11) NOT NULL,
  `reading_water_old` int(11) NOT NULL,
  `reading_water_new` int(11) NOT NULL,
  `reading_created_by` int(11) NOT NULL,
  `reading_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `utility_readings`
--

INSERT INTO `utility_readings` (`reading_id`, `reading_stall_id`, `reading_date`, `reading_electric_old`, `reading_electric_new`, `reading_water_old`, `reading_water_new`, `reading_created_by`, `reading_created_at`) VALUES
(1, 1, '2026-06-25', 1540, 1690, 240, 255, 2, '2026-07-08 09:32:18'),
(2, 6, '2026-06-25', 3200, 3450, 410, 432, 2, '2026-07-08 09:32:18');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`area_id`),
  ADD UNIQUE KEY `area_name` (`area_name`),
  ADD KEY `idx_areas_market` (`area_market_id`);

--
-- Chỉ mục cho bảng `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`bill_id`),
  ADD UNIQUE KEY `bill_code` (`bill_code`),
  ADD KEY `bill_contract_id` (`bill_contract_id`);

--
-- Chỉ mục cho bảng `business_lines`
--
ALTER TABLE `business_lines`
  ADD PRIMARY KEY (`line_id`),
  ADD UNIQUE KEY `line_code` (`line_code`);

--
-- Chỉ mục cho bảng `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contract_id`),
  ADD UNIQUE KEY `contract_number` (`contract_number`),
  ADD KEY `contract_trader_id` (`contract_trader_id`),
  ADD KEY `contract_status_id` (`contract_status_id`),
  ADD KEY `idx_contracts_stall` (`contract_stall_id`);

--
-- Chỉ mục cho bảng `contract_appendices`
--
ALTER TABLE `contract_appendices`
  ADD PRIMARY KEY (`appendix_id`),
  ADD UNIQUE KEY `appendix_number` (`appendix_number`),
  ADD KEY `appendix_contract_id` (`appendix_contract_id`);

--
-- Chỉ mục cho bảng `contract_history`
--
ALTER TABLE `contract_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `history_contract_id` (`history_contract_id`);

--
-- Chỉ mục cho bảng `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`doc_type_id`),
  ADD UNIQUE KEY `doc_type_code` (`doc_type_code`);

--
-- Chỉ mục cho bảng `food_safety_inspections`
--
ALTER TABLE `food_safety_inspections`
  ADD PRIMARY KEY (`inspection_id`),
  ADD KEY `idx_inspections_market` (`inspection_market_id`);

--
-- Chỉ mục cho bảng `food_safety_violations`
--
ALTER TABLE `food_safety_violations`
  ADD PRIMARY KEY (`violation_id`),
  ADD UNIQUE KEY `violation_code` (`violation_code`),
  ADD KEY `violation_trader_id` (`violation_trader_id`),
  ADD KEY `violation_inspection_id` (`violation_inspection_id`);

--
-- Chỉ mục cho bảng `income_categories`
--
ALTER TABLE `income_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `idx_income_categories_market` (`market_id`,`category_type`,`status`);

--
-- Chỉ mục cho bảng `income_vouchers`
--
ALTER TABLE `income_vouchers`
  ADD PRIMARY KEY (`voucher_id`),
  ADD KEY `idx_income_vouchers_market` (`market_id`,`voucher_type`,`status`,`voucher_date`),
  ADD KEY `fk_income_voucher_category` (`category_id`);

--
-- Chỉ mục cho bảng `markets`
--
ALTER TABLE `markets`
  ADD PRIMARY KEY (`market_id`),
  ADD UNIQUE KEY `market_code` (`market_code`),
  ADD KEY `idx_market_status` (`market_status_code`),
  ADD KEY `idx_market_location` (`market_province_id`,`market_district_id`);

--
-- Chỉ mục cho bảng `market_map_elements`
--
ALTER TABLE `market_map_elements`
  ADD PRIMARY KEY (`element_id`),
  ADD UNIQUE KEY `uk_stall` (`element_stall_id`);

--
-- Chỉ mục cho bảng `receipts_payments`
--
ALTER TABLE `receipts_payments`
  ADD PRIMARY KEY (`transaction_id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`),
  ADD KEY `transaction_created_by` (`transaction_created_by`),
  ADD KEY `idx_receipts_market` (`transaction_market_id`);

--
-- Chỉ mục cho bảng `stalls`
--
ALTER TABLE `stalls`
  ADD PRIMARY KEY (`stall_id`),
  ADD UNIQUE KEY `stall_code` (`stall_code`),
  ADD KEY `stall_status_id` (`stall_status_id`),
  ADD KEY `fk_stalls_type` (`stall_type_id`),
  ADD KEY `idx_stalls_area` (`stall_area_id`);

--
-- Chỉ mục cho bảng `stall_types`
--
ALTER TABLE `stall_types`
  ADD PRIMARY KEY (`stall_type_id`),
  ADD UNIQUE KEY `stall_type_code` (`stall_type_code`);

--
-- Chỉ mục cho bảng `status_colors`
--
ALTER TABLE `status_colors`
  ADD PRIMARY KEY (`color_id`),
  ADD UNIQUE KEY `color_class` (`color_class`);

--
-- Chỉ mục cho bảng `system_actors`
--
ALTER TABLE `system_actors`
  ADD PRIMARY KEY (`actor_id`),
  ADD UNIQUE KEY `actor_code` (`actor_code`);

--
-- Chỉ mục cho bảng `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `log_user_id` (`log_user_id`);

--
-- Chỉ mục cho bảng `system_statuses`
--
ALTER TABLE `system_statuses`
  ADD PRIMARY KEY (`status_id`),
  ADD UNIQUE KEY `uk_domain_code` (`status_domain`,`status_code`),
  ADD KEY `status_color_id` (`status_color_id`);

--
-- Chỉ mục cho bảng `traders`
--
ALTER TABLE `traders`
  ADD PRIMARY KEY (`trader_id`),
  ADD UNIQUE KEY `trader_code` (`trader_code`),
  ADD UNIQUE KEY `trader_cccd` (`trader_cccd`),
  ADD KEY `trader_status_id` (`trader_status_id`),
  ADD KEY `trader_business_line_id` (`trader_business_line_id`),
  ADD KEY `fk_traders_market` (`trader_market_id`);

--
-- Chỉ mục cho bảng `trader_attp`
--
ALTER TABLE `trader_attp`
  ADD PRIMARY KEY (`attp_id`),
  ADD KEY `attp_trader_id` (`attp_trader_id`),
  ADD KEY `attp_status_id` (`attp_status_id`),
  ADD KEY `fk_attp_doc_type` (`attp_doc_type_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`user_username`),
  ADD KEY `fk_users_actor` (`user_actor_id`);

--
-- Chỉ mục cho bảng `user_markets`
--
ALTER TABLE `user_markets`
  ADD PRIMARY KEY (`user_market_id`),
  ADD UNIQUE KEY `uk_user_market_role` (`user_market_user_id`,`user_market_market_id`,`user_market_role_id`),
  ADD KEY `user_market_market_id` (`user_market_market_id`),
  ADD KEY `user_market_role_id` (`user_market_role_id`),
  ADD KEY `idx_user_markets_query` (`user_market_user_id`,`user_market_market_id`);

--
-- Chỉ mục cho bảng `user_market_permissions`
--
ALTER TABLE `user_market_permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `uk_user_market_module` (`permission_user_id`,`permission_market_id`,`permission_module_code`),
  ADD KEY `permission_market_id` (`permission_market_id`);

--
-- Chỉ mục cho bảng `utility_readings`
--
ALTER TABLE `utility_readings`
  ADD PRIMARY KEY (`reading_id`),
  ADD KEY `reading_stall_id` (`reading_stall_id`),
  ADD KEY `reading_created_by` (`reading_created_by`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `areas`
--
ALTER TABLE `areas`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `bills`
--
ALTER TABLE `bills`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `business_lines`
--
ALTER TABLE `business_lines`
  MODIFY `line_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `contract_appendices`
--
ALTER TABLE `contract_appendices`
  MODIFY `appendix_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `contract_history`
--
ALTER TABLE `contract_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `document_types`
--
ALTER TABLE `document_types`
  MODIFY `doc_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `food_safety_inspections`
--
ALTER TABLE `food_safety_inspections`
  MODIFY `inspection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `food_safety_violations`
--
ALTER TABLE `food_safety_violations`
  MODIFY `violation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `category_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `income_vouchers`
--
ALTER TABLE `income_vouchers`
  MODIFY `voucher_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT cho bảng `markets`
--
ALTER TABLE `markets`
  MODIFY `market_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `market_map_elements`
--
ALTER TABLE `market_map_elements`
  MODIFY `element_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT cho bảng `receipts_payments`
--
ALTER TABLE `receipts_payments`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `stalls`
--
ALTER TABLE `stalls`
  MODIFY `stall_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `stall_types`
--
ALTER TABLE `stall_types`
  MODIFY `stall_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `status_colors`
--
ALTER TABLE `status_colors`
  MODIFY `color_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `system_actors`
--
ALTER TABLE `system_actors`
  MODIFY `actor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT cho bảng `system_statuses`
--
ALTER TABLE `system_statuses`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT cho bảng `traders`
--
ALTER TABLE `traders`
  MODIFY `trader_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `trader_attp`
--
ALTER TABLE `trader_attp`
  MODIFY `attp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `user_markets`
--
ALTER TABLE `user_markets`
  MODIFY `user_market_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT cho bảng `user_market_permissions`
--
ALTER TABLE `user_market_permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT cho bảng `utility_readings`
--
ALTER TABLE `utility_readings`
  MODIFY `reading_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `areas`
--
ALTER TABLE `areas`
  ADD CONSTRAINT `fk_areas_market` FOREIGN KEY (`area_market_id`) REFERENCES `markets` (`market_id`);

--
-- Các ràng buộc cho bảng `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`bill_contract_id`) REFERENCES `contracts` (`contract_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`contract_trader_id`) REFERENCES `traders` (`trader_id`),
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`contract_stall_id`) REFERENCES `stalls` (`stall_id`),
  ADD CONSTRAINT `contracts_ibfk_3` FOREIGN KEY (`contract_status_id`) REFERENCES `system_statuses` (`status_id`);

--
-- Các ràng buộc cho bảng `contract_appendices`
--
ALTER TABLE `contract_appendices`
  ADD CONSTRAINT `contract_appendices_ibfk_1` FOREIGN KEY (`appendix_contract_id`) REFERENCES `contracts` (`contract_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `contract_history`
--
ALTER TABLE `contract_history`
  ADD CONSTRAINT `contract_history_ibfk_1` FOREIGN KEY (`history_contract_id`) REFERENCES `contracts` (`contract_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `food_safety_inspections`
--
ALTER TABLE `food_safety_inspections`
  ADD CONSTRAINT `fk_inspections_market` FOREIGN KEY (`inspection_market_id`) REFERENCES `markets` (`market_id`);

--
-- Các ràng buộc cho bảng `food_safety_violations`
--
ALTER TABLE `food_safety_violations`
  ADD CONSTRAINT `food_safety_violations_ibfk_1` FOREIGN KEY (`violation_trader_id`) REFERENCES `traders` (`trader_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `food_safety_violations_ibfk_2` FOREIGN KEY (`violation_inspection_id`) REFERENCES `food_safety_inspections` (`inspection_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `income_vouchers`
--
ALTER TABLE `income_vouchers`
  ADD CONSTRAINT `fk_income_voucher_category` FOREIGN KEY (`category_id`) REFERENCES `income_categories` (`category_id`);

--
-- Các ràng buộc cho bảng `market_map_elements`
--
ALTER TABLE `market_map_elements`
  ADD CONSTRAINT `market_map_elements_ibfk_1` FOREIGN KEY (`element_stall_id`) REFERENCES `stalls` (`stall_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `receipts_payments`
--
ALTER TABLE `receipts_payments`
  ADD CONSTRAINT `fk_receipts_market` FOREIGN KEY (`transaction_market_id`) REFERENCES `markets` (`market_id`),
  ADD CONSTRAINT `receipts_payments_ibfk_1` FOREIGN KEY (`transaction_created_by`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `stalls`
--
ALTER TABLE `stalls`
  ADD CONSTRAINT `fk_stalls_type` FOREIGN KEY (`stall_type_id`) REFERENCES `stall_types` (`stall_type_id`),
  ADD CONSTRAINT `stalls_ibfk_1` FOREIGN KEY (`stall_area_id`) REFERENCES `areas` (`area_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stalls_ibfk_2` FOREIGN KEY (`stall_status_id`) REFERENCES `system_statuses` (`status_id`);

--
-- Các ràng buộc cho bảng `system_logs`
--
ALTER TABLE `system_logs`
  ADD CONSTRAINT `system_logs_ibfk_1` FOREIGN KEY (`log_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `system_statuses`
--
ALTER TABLE `system_statuses`
  ADD CONSTRAINT `system_statuses_ibfk_1` FOREIGN KEY (`status_color_id`) REFERENCES `status_colors` (`color_id`);

--
-- Các ràng buộc cho bảng `traders`
--
ALTER TABLE `traders`
  ADD CONSTRAINT `fk_traders_market` FOREIGN KEY (`trader_market_id`) REFERENCES `markets` (`market_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `traders_ibfk_1` FOREIGN KEY (`trader_status_id`) REFERENCES `system_statuses` (`status_id`),
  ADD CONSTRAINT `traders_ibfk_2` FOREIGN KEY (`trader_business_line_id`) REFERENCES `business_lines` (`line_id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `trader_attp`
--
ALTER TABLE `trader_attp`
  ADD CONSTRAINT `fk_attp_doc_type` FOREIGN KEY (`attp_doc_type_id`) REFERENCES `document_types` (`doc_type_id`),
  ADD CONSTRAINT `trader_attp_ibfk_1` FOREIGN KEY (`attp_trader_id`) REFERENCES `traders` (`trader_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trader_attp_ibfk_2` FOREIGN KEY (`attp_status_id`) REFERENCES `system_statuses` (`status_id`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_actor` FOREIGN KEY (`user_actor_id`) REFERENCES `system_actors` (`actor_id`);

--
-- Các ràng buộc cho bảng `user_markets`
--
ALTER TABLE `user_markets`
  ADD CONSTRAINT `user_markets_ibfk_1` FOREIGN KEY (`user_market_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_markets_ibfk_2` FOREIGN KEY (`user_market_market_id`) REFERENCES `markets` (`market_id`);

--
-- Các ràng buộc cho bảng `user_market_permissions`
--
ALTER TABLE `user_market_permissions`
  ADD CONSTRAINT `user_market_permissions_ibfk_1` FOREIGN KEY (`permission_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_market_permissions_ibfk_2` FOREIGN KEY (`permission_market_id`) REFERENCES `markets` (`market_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `utility_readings`
--
ALTER TABLE `utility_readings`
  ADD CONSTRAINT `utility_readings_ibfk_1` FOREIGN KEY (`reading_stall_id`) REFERENCES `stalls` (`stall_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `utility_readings_ibfk_2` FOREIGN KEY (`reading_created_by`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
