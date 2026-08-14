-- =========================================================================
-- Migration script: Tạo bảng web_users cho hệ thống Admin Web & Biên tập viên
-- =========================================================================

CREATE TABLE IF NOT EXISTS `web_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `role` ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm tài khoản quản trị mặc định (Mật khẩu: 123456)
INSERT INTO `web_users` (`username`, `password`, `fullname`, `email`, `phone`, `role`, `status`) 
VALUES 
('admin', '$2y$10$e.wM0d1lJk2c3L4m5N6O7eP8qR9sT0uV1wX2yZ3aB4cC5dE6fG7hI', 'Quản Trị Viên Web', 'admin@web.vn', '0901234567', 'admin', 1),
('editor', '$2y$10$e.wM0d1lJk2c3L4m5N6O7eP8qR9sT0uV1wX2yZ3aB4cC5dE6fG7hI', 'Biên Tập Viên Web', 'editor@web.vn', '0907654321', 'editor', 1)
ON DUPLICATE KEY UPDATE `fullname` = VALUES(`fullname`);
