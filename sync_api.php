<?php
/**
 * =========================================================================
 * FILE CẦU NỐI ĐỒNG BỘ DỮ LIỆU ĐỘC LẬP (DÀNH CHO HOST CỦA APP QUẢN LÝ)
 * =========================================================================
 * Hướng dẫn sử dụng:
 * 1. Thả file này vào thư mục gốc của Web App Quản Lý (Ví dụ: https://app.tenmiencuaban.com/sync_api.php)
 * 2. Đặt mã khóa bí mật $SECRET_KEY trùng với APP_SYNC_SECRET_KEY bên Web.
 * 3. File này chạy độc lập 100%, không làm ảnh hưởng hay thay đổi bất kỳ code nào của App.
 */

// 1. Mã khóa bảo mật (Hãy đổi mã này nếu muốn)
$SECRET_KEY = 'CHO_QN_SYNC_SECURE_KEY_2026_ABCXYZ';

// Kiểm tra mã khóa từ request (Chống người ngoài truy cập & Chống Timing Attacks)
$token = (string)($_GET['token'] ?? $_POST['token'] ?? ($_SERVER['HTTP_X_SYNC_TOKEN'] ?? ''));
if (empty($token) || !hash_equals((string)$SECRET_KEY, $token)) {
    header('HTTP/1.1 403 Forbidden');
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Lỗi xác thực: Sai Secret Key hoặc không có quyền truy cập!']);
    exit;
}

// 2. Tự động tìm nạp kết nối Database nội bộ của App
$conn = null;

// Thử nạp từ config.php của App nếu có
if (file_exists(__DIR__ . '/config.php')) {
    include_once __DIR__ . '/config.php';
    if (defined('DB_HOST') && defined('DB_USER') && defined('DB_NAME')) {
        $host = DB_HOST;
        $port = defined('DB_PORT') ? (int)DB_PORT : 3306;
        if (strpos($host, ':') !== false) {
            $parts = explode(':', $host, 2);
            $host = $parts[0];
            $port = (int)$parts[1];
        }
        $conn = @new mysqli($host, DB_USER, defined('DB_PASSWORD') ? DB_PASSWORD : (defined('DB_PASS') ? DB_PASS : ''), DB_NAME, $port);
    }
}

// Nếu chưa kết nối được, cấu hình thủ công tại đây (nếu App không dùng config chuẩn)
if (!$conn || $conn->connect_error) {
    // THAY ĐỔI THÔNG TIN DB APP NỘI BỘ TẠI ĐÂY NẾU CẦN:
    $LOCAL_DB_HOST = '127.0.0.1';
    $LOCAL_DB_PORT = 3306;
    $LOCAL_DB_NAME = 'quanlycho.vn';
    $LOCAL_DB_USER = 'root';
    $LOCAL_DB_PASS = '';

    $conn = @new mysqli($LOCAL_DB_HOST, $LOCAL_DB_USER, $LOCAL_DB_PASS, $LOCAL_DB_NAME, $LOCAL_DB_PORT);
}

if (!$conn || $conn->connect_error) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối CSDL nội bộ trên máy chủ App: ' . ($conn ? $conn->connect_error : 'Không thể kết nối')]);
    exit;
}

$conn->set_charset('utf8mb4');

// 3. Trích xuất dữ liệu của 5 bảng cốt lõi
$action = $_GET['action'] ?? 'export';

if ($action === 'ping') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'message' => 'Cầu nối API hoạt động bình thường!']);
    exit;
}

$data = [
    'markets'        => [],
    'areas'          => [],
    'business_lines' => [],
    'stalls'         => [],
    'traders'        => []
];

$tables = [
    'markets'        => 'SELECT * FROM `markets`',
    'areas'          => 'SELECT * FROM `areas`',
    'business_lines' => 'SELECT * FROM `business_lines`',
    'stalls'         => 'SELECT * FROM `stalls`',
    'traders'        => 'SELECT * FROM `traders`'
];

foreach ($tables as $tbl => $sql) {
    $res = $conn->query($sql);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $data[$tbl][] = $r;
        }
    }
}

$conn->close();

// 4. Trả về JSON cho Web
header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'success' => true,
    'timestamp' => time(),
    'counts' => [
        'markets'        => count($data['markets']),
        'areas'          => count($data['areas']),
        'business_lines' => count($data['business_lines']),
        'stalls'         => count($data['stalls']),
        'traders'        => count($data['traders'])
    ],
    'data' => $data
], JSON_UNESCAPED_UNICODE);
