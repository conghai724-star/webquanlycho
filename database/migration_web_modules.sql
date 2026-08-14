-- =========================================================================
-- Migration script: Tạo các bảng dữ liệu Phân hệ Web (Banner, Đăng ký thuê sạp, Khiếu nại/Góp ý)
-- =========================================================================

-- 1. Bảng quản lý Banner (Trang chủ & Trang giới thiệu)
CREATE TABLE IF NOT EXISTS `website_banners` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `banner_title` VARCHAR(255) NOT NULL,
  `banner_image` VARCHAR(500) NOT NULL,
  `banner_link` VARCHAR(500) DEFAULT NULL,
  `banner_page` ENUM('home', 'about', 'all') DEFAULT 'home',
  `banner_order` INT DEFAULT 0,
  `banner_status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Bảng tiếp nhận Đăng ký Thuê sạp công khai
CREATE TABLE IF NOT EXISTS `stall_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `cccd` VARCHAR(20) DEFAULT NULL,
  `market_id` INT DEFAULT NULL,
  `stall_code` VARCHAR(50) DEFAULT NULL,
  `business_item` VARCHAR(255) DEFAULT NULL,
  `note` TEXT DEFAULT NULL,
  `status` ENUM('pending', 'contacted', 'approved', 'rejected') DEFAULT 'pending',
  `admin_note` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Bảng tiếp nhận Khiếu nại & Góp ý từ người dân / tiểu thương
CREATE TABLE IF NOT EXISTS `website_feedbacks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `fullname` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `type` ENUM('feedback', 'complaint') DEFAULT 'feedback',
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `status` ENUM('new', 'processing', 'resolved') DEFAULT 'new',
  `reply_content` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Bổ sung cột permissions vào web_users nếu chưa tồn tại
ALTER TABLE `web_users` ADD COLUMN `permissions` TEXT DEFAULT NULL AFTER `role`;

-- Cập nhật quyền mặc định cho admin và editor
UPDATE `web_users` SET `permissions` = 'all' WHERE `role` = 'admin';
UPDATE `web_users` SET `permissions` = 'dashboard,map_editor,map_tree,banners' WHERE `role` = 'editor';

-- 5. Đổ dữ liệu mẫu cho Banners
INSERT INTO `website_banners` (`banner_title`, `banner_image`, `banner_link`, `banner_page`, `banner_order`, `banner_status`)
VALUES
('Hệ Thống Quản Lý Chợ Tỉnh Quảng Ngãi', 'https://images.unsplash.com/photo-1533900298318-6b8da08a523e?q=80&w=1200&auto=format&fit=crop', 'http://localhost/quanlycho.vn/home/map', 'home', 1, 1),
('Bản Đồ Số Tương Tác Sạp Chợ Số 1', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=1200&auto=format&fit=crop', 'http://localhost/quanlycho.vn/home/map', 'about', 2, 1)
ON DUPLICATE KEY UPDATE `banner_title` = VALUES(`banner_title`);

-- 6. Đổ dữ liệu mẫu cho Đăng ký thuê sạp
INSERT INTO `stall_registrations` (`fullname`, `phone`, `email`, `cccd`, `business_item`, `note`, `status`)
VALUES
('Nguyễn Văn A', '0912345678', 'nguyenvana@gmail.com', '079123456789', 'Thời trang quần áo', 'Tôi muốn đăng ký thuê 1 sạp kinh doanh đồ may mặc tại Khu A.', 'pending'),
('Trần Thị B', '0987654321', 'tranthib@gmail.com', '079987654321', 'Thực phẩm tươi sống', 'Đăng ký kiot bán hoa quả sạch.', 'contacted')
ON DUPLICATE KEY UPDATE `fullname` = VALUES(`fullname`);

-- 7. Đổ dữ liệu mẫu cho Khiếu nại & Góp ý
INSERT INTO `website_feedbacks` (`fullname`, `phone`, `email`, `type`, `title`, `content`, `status`)
VALUES
('Lê Văn C', '0905111222', 'levanc@gmail.com', 'feedback', 'Góp ý hệ thống chiếu sáng khu B', 'Khu B buổi tối hơi tối, đề nghị BQL bổ sung thêm đèn chiếu sáng.', 'new'),
('Phạm Thị D', '0905333444', 'phamthid@gmail.com', 'complaint', 'Phản ánh tình trạng lấn chiếm lối đi', 'Một số sạp khu A để hàng hóa ra ngoài lối đi chung gây cản trở giao thông.', 'processing')
ON DUPLICATE KEY UPDATE `fullname` = VALUES(`fullname`);
