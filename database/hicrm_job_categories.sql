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
-- Cấu trúc bảng cho bảng `hicrm_job_categories`
--

CREATE TABLE `hicrm_job_categories` (
  `id` int(11) NOT NULL,
  `job_category_name` varchar(255) NOT NULL COMMENT 'Tên ngành nghề',
  `job_category_keyword` varchar(255) NOT NULL COMMENT 'Tên không dấu, viết thường, nối bằng dấu _',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `hicrm_job_categories`
--

INSERT INTO `hicrm_job_categories` (`id`, `job_category_name`, `job_category_keyword`, `created_at`) VALUES
(1, 'Công nghệ thông tin', 'cong_nghe_thong_tin', '2026-05-26 15:16:48'),
(2, 'Lập trình phần mềm', 'lap_trinh_phan_mem', '2026-05-26 15:16:48'),
(3, 'Thiết kế đồ họa', 'thiet_ke_do_hoa', '2026-05-26 15:16:48'),
(4, 'Marketing', 'marketing', '2026-05-26 15:16:48'),
(5, 'Digital Marketing', 'digital_marketing', '2026-05-26 15:16:48'),
(6, 'Kinh doanh', 'kinh_doanh', '2026-05-26 15:16:48'),
(7, 'Bán hàng', 'ban_hang', '2026-05-26 15:16:48'),
(8, 'Chăm sóc khách hàng', 'cham_soc_khach_hang', '2026-05-26 15:16:48'),
(9, 'Hành chính - Văn phòng', 'hanh_chinh_van_phong', '2026-05-26 15:16:48'),
(10, 'Nhân sự', 'nhan_su', '2026-05-26 15:16:48'),
(11, 'Kế toán', 'ke_toan', '2026-05-26 15:16:48'),
(12, 'Kiểm toán', 'kiem_toan', '2026-05-26 15:16:48'),
(13, 'Tài chính - Ngân hàng', 'tai_chinh_ngan_hang', '2026-05-26 15:16:48'),
(14, 'Bảo hiểm', 'bao_hiem', '2026-05-26 15:16:48'),
(15, 'Xuất nhập khẩu', 'xuat_nhap_khau', '2026-05-26 15:16:48'),
(16, 'Logistics', 'logistics', '2026-05-26 15:16:48'),
(17, 'Vận tải', 'van_tai', '2026-05-26 15:16:48'),
(18, 'Kho vận', 'kho_van', '2026-05-26 15:16:48'),
(19, 'Thương mại điện tử', 'thuong_mai_dien_tu', '2026-05-26 15:16:48'),
(20, 'Quản trị kinh doanh', 'quan_tri_kinh_doanh', '2026-05-26 15:16:48'),
(21, 'Bất động sản', 'bat_dong_san', '2026-05-26 15:16:48'),
(22, 'Xây dựng', 'xay_dung', '2026-05-26 15:16:48'),
(23, 'Kiến trúc', 'kien_truc', '2026-05-26 15:16:48'),
(24, 'Nội thất', 'noi_that', '2026-05-26 15:16:48'),
(25, 'Điện - Điện tử', 'dien_dien_tu', '2026-05-26 15:16:48'),
(26, 'Cơ khí', 'co_khi', '2026-05-26 15:16:48'),
(27, 'Tự động hóa', 'tu_dong_hoa', '2026-05-26 15:16:48'),
(28, 'Kỹ thuật ô tô', 'ky_thuat_o_to', '2026-05-26 15:16:48'),
(29, 'Sản xuất', 'san_xuat', '2026-05-26 15:16:48'),
(30, 'QA/QC', 'qa_qc', '2026-05-26 15:16:48'),
(31, 'Quản lý chất lượng', 'quan_ly_chat_luong', '2026-05-26 15:16:48'),
(32, 'Dệt may', 'det_may', '2026-05-26 15:16:48'),
(33, 'Da giày', 'da_giay', '2026-05-26 15:16:48'),
(34, 'Nông nghiệp', 'nong_nghiep', '2026-05-26 15:16:48'),
(35, 'Lâm nghiệp', 'lam_nghiep', '2026-05-26 15:16:48'),
(36, 'Thủy sản', 'thuy_san', '2026-05-26 15:16:48'),
(37, 'Thực phẩm - Đồ uống', 'thuc_pham_do_uong', '2026-05-26 15:16:48'),
(38, 'Y tế', 'y_te', '2026-05-26 15:16:48'),
(39, 'Dược', 'duoc', '2026-05-26 15:16:48'),
(40, 'Điều dưỡng', 'dieu_duong', '2026-05-26 15:16:48'),
(41, 'Chăm sóc sức khỏe', 'cham_soc_suc_khoe', '2026-05-26 15:16:48'),
(42, 'Giáo dục', 'giao_duc', '2026-05-26 15:16:48'),
(43, 'Giảng viên', 'giang_vien', '2026-05-26 15:16:48'),
(44, 'Ngoại ngữ', 'ngoai_ngu', '2026-05-26 15:16:48'),
(45, 'Biên phiên dịch', 'bien_phien_dich', '2026-05-26 15:16:48'),
(46, 'Luật', 'luat', '2026-05-26 15:16:48'),
(47, 'Pháp lý', 'phap_ly', '2026-05-26 15:16:48'),
(48, 'Báo chí - Truyền thông', 'bao_chi_truyen_thong', '2026-05-26 15:16:48'),
(49, 'Tổ chức sự kiện', 'to_chuc_su_kien', '2026-05-26 15:16:48'),
(50, 'Du lịch', 'du_lich', '2026-05-26 15:16:48'),
(51, 'Khách sạn', 'khach_san', '2026-05-26 15:16:48'),
(52, 'Nhà hàng', 'nha_hang', '2026-05-26 15:16:48'),
(53, 'Đầu bếp', 'dau_bep', '2026-05-26 15:16:48'),
(54, 'Spa - Làm đẹp', 'spa_lam_dep', '2026-05-26 15:16:48'),
(55, 'Thể dục thể thao', 'the_duc_the_thao', '2026-05-26 15:16:48'),
(56, 'Bảo vệ', 'bao_ve', '2026-05-26 15:16:48'),
(57, 'Lao động phổ thông', 'lao_dong_pho_thong', '2026-05-26 15:16:48'),
(58, 'Sinh viên làm thêm', 'sinh_vien_lam_them', '2026-05-26 15:16:48'),
(59, 'Freelancer', 'freelancer', '2026-05-26 15:16:48'),
(60, 'Part-time', 'part_time', '2026-05-26 15:16:48'),
(61, 'Full-time', 'full_time', '2026-05-26 15:16:48'),
(62, 'Thực tập sinh', 'thuc_tap_sinh', '2026-05-26 15:16:48'),
(63, 'Việc làm online', 'viec_lam_online', '2026-05-26 15:16:48'),
(64, 'Khoa học dữ liệu', 'khoa_hoc_du_lieu', '2026-05-26 15:16:48'),
(65, 'Trí tuệ nhân tạo', 'tri_tue_nhan_tao', '2026-05-26 15:16:48'),
(66, 'An toàn thông tin', 'an_toan_thong_tin', '2026-05-26 15:16:48'),
(67, 'DevOps', 'devops', '2026-05-26 15:16:48'),
(68, 'Tester', 'tester', '2026-05-26 15:16:48'),
(69, 'Game Developer', 'game_developer', '2026-05-26 15:16:48'),
(70, 'Mobile Developer', 'mobile_developer', '2026-05-26 15:16:48'),
(71, 'Frontend Developer', 'frontend_developer', '2026-05-26 15:16:48'),
(72, 'Backend Developer', 'backend_developer', '2026-05-26 15:16:48'),
(73, 'PHP Developer', 'php_developer', '2026-05-26 15:16:48'),
(74, 'Java Developer', 'java_developer', '2026-05-26 15:16:48'),
(75, 'Python Developer', 'python_developer', '2026-05-26 15:16:48'),
(76, 'UI/UX Designer', 'ui_ux_designer', '2026-05-26 15:16:48'),
(77, 'SEO', 'seo', '2026-05-26 15:16:48'),
(78, 'Content Creator', 'content_creator', '2026-05-26 15:16:48'),
(79, 'Copywriter', 'copywriter', '2026-05-26 15:16:48'),
(80, 'TikTok Creator', 'tiktok_creator', '2026-05-26 15:16:48'),
(81, 'Streamer', 'streamer', '2026-05-26 15:16:48'),
(82, 'YouTuber', 'youtuber', '2026-05-26 15:16:48'),
(83, 'MC - Người dẫn chương trình', 'mc_nguoi_dan_chuong_trinh', '2026-05-26 15:16:48'),
(84, 'Nhiếp ảnh', 'nhiep_anh', '2026-05-26 15:16:48'),
(85, 'Quay dựng video', 'quay_dung_video', '2026-05-26 15:16:48'),
(86, 'Game - Esport', 'game_esport', '2026-05-26 15:16:48'),
(87, 'Môi trường', 'moi_truong', '2026-05-26 15:16:48'),
(88, 'Hóa học', 'hoa_hoc', '2026-05-26 15:16:48'),
(89, 'Sinh học', 'sinh_hoc', '2026-05-26 15:16:48'),
(90, 'Điện lạnh', 'dien_lanh', '2026-05-26 15:16:48'),
(91, 'Hàng không', 'hang_khong', '2026-05-26 15:16:48'),
(92, 'Hàng hải', 'hang_hai', '2026-05-26 15:16:48'),
(93, 'Công an - Quân đội', 'cong_an_quan_doi', '2026-05-26 15:16:48'),
(94, 'Công chức - Viên chức', 'cong_chuc_vien_chuc', '2026-05-26 15:16:48'),
(95, 'Phi chính phủ NGO', 'phi_chinh_phu_ngo', '2026-05-26 15:16:48'),
(96, 'Khởi nghiệp', 'khoi_nghiep', '2026-05-26 15:16:48'),
(97, 'Quản lý dự án', 'quan_ly_du_an', '2026-05-26 15:16:48'),
(98, 'Data Analyst', 'data_analyst', '2026-05-26 15:16:48'),
(99, 'Business Analyst', 'business_analyst', '2026-05-26 15:16:48'),
(100, 'Blockchain', 'blockchain', '2026-05-26 15:16:48'),
(101, 'IoT', 'iot', '2026-05-26 15:16:48'),
(102, 'Kỹ sư cầu đường', 'ky_su_cau_duong', '2026-05-26 15:16:48'),
(103, 'Kỹ sư điện', 'ky_su_dien', '2026-05-26 15:16:48'),
(104, 'Kỹ sư cơ khí', 'ky_su_co_khi', '2026-05-26 15:16:48'),
(105, 'Kỹ sư xây dựng', 'ky_su_xay_dung', '2026-05-26 15:16:48'),
(106, 'Tư vấn tuyển sinh', 'tu_van_tuyen_sinh', '2026-05-26 15:16:48'),
(107, 'Tư vấn tài chính', 'tu_van_tai_chinh', '2026-05-26 15:16:48'),
(108, 'Tư vấn bảo hiểm', 'tu_van_bao_hiem', '2026-05-26 15:16:48'),
(109, 'Tư vấn khách hàng', 'tu_van_khach_hang', '2026-05-26 15:16:48');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `hicrm_job_categories`
--
ALTER TABLE `hicrm_job_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `hicrm_job_categories`
--
ALTER TABLE `hicrm_job_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
