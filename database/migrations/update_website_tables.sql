--
-- DDL for Website & Frontend Tables
--

CREATE TABLE IF NOT EXISTS `website_banners` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `banner_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `banner_image` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
    `banner_link` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `banner_order` int(11) NOT NULL DEFAULT 0,
    `banner_status` tinyint(4) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_posts` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `post_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `post_summary` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
    `post_image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `post_status` tinyint(4) NOT NULL DEFAULT 1,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_post_slug` (`post_slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_downloads` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doc_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `doc_file` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
    `doc_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `doc_downloads_count` int(11) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_feedbacks` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `fb_fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `fb_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `fb_email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `fb_content` text COLLATE utf8mb4_unicode_ci NOT NULL,
    `fb_status` tinyint(4) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `rental_registrations` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `fullname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `cccd` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
    `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `zone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
    `area` int(11) DEFAULT NULL,
    `business_line` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `status` tinyint(4) NOT NULL DEFAULT 0,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `website_stats` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `stat_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
    `stat_value` bigint(20) NOT NULL DEFAULT 0,
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `idx_stat_key` (`stat_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Seed Data
--

INSERT INTO `website_stats` (`stat_key`, `stat_value`) VALUES 
('total_views', 3541)
ON DUPLICATE KEY UPDATE `stat_key` = `stat_key`;

INSERT INTO `website_banners` (`banner_title`, `banner_image`, `banner_link`, `banner_order`, `banner_status`) VALUES
('Cổng thông tin Chợ Quảng Ngãi', 'https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=1200', '#', 1, 1),
('Đăng ký thuê sạp trực tuyến nhanh chóng', 'https://images.unsplash.com/photo-1533900298318-6b8da08a523e?q=80&w=1200', 'home/register', 2, 1)
ON DUPLICATE KEY UPDATE `banner_title` = `banner_title`;

INSERT INTO `website_posts` (`post_title`, `post_slug`, `post_summary`, `post_content`, `post_image`, `post_status`) VALUES
('Thông báo về việc đăng ký thuê sạp đợt 2/2026 tại Chợ Trung Tâm', 'thong-bao-dang-ky-thue-sap-dot-2-2026', 'Ban quản lý Chợ Trung Tâm thông báo tiếp nhận hồ sơ đăng ký thuê sạp thương mại đợt 2 bắt đầu từ ngày 15/08/2026.', 'Ban quản lý Chợ Trung Tâm xin thông báo đến toàn thể bà con tiểu thương và nhân dân về việc tiếp nhận đăng ký thuê các vị trí sạp kinh doanh đợt 2 năm 2026. Các ngành hàng bao gồm thời trang, thực phẩm sạch và ẩm thực. Mọi thông tin chi tiết xin liên hệ trực tiếp văn phòng Ban quản lý hoặc nộp đơn trực tuyến qua cổng đăng ký.', 'https://images.unsplash.com/photo-1595535373192-1697a7ab1ff1?q=80&w=600', 1),
('Kế hoạch kiểm tra vệ sinh an toàn thực phẩm quý 3/2026', 'ke-hoach-kiem-tra-vsattp-quy-3-2026', 'Phối hợp với Sở Y tế thực hiện thanh kiểm tra định kỳ các hộ kinh doanh thực phẩm tươi sống.', 'Để đảm bảo sức khỏe cho người tiêu dùng và giữ vững chất lượng dịch vụ của chợ, Ban quản lý phối hợp cùng đoàn liên ngành của Chi cục VSATTP tỉnh Quảng Ngãi sẽ tiến hành thanh kiểm tra định kỳ các hộ kinh doanh ngành hàng thực phẩm tươi sống, ăn uống từ ngày 20/08/2026. Đề nghị các hộ chuẩn bị đầy đủ hồ sơ khám sức khỏe và cam kết đảm bảo vệ sinh.', 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?q=80&w=600', 1)
ON DUPLICATE KEY UPDATE `post_title` = `post_title`;
