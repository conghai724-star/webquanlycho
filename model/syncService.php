<?php
/**
 * Service xử lý Đồng bộ Cơ sở dữ liệu và Cài đặt Hiển thị / Ẩn thông tin
 * Hỗ trợ 2 chế độ:
 * 1. Đồng bộ qua API Cầu Nối (Khuyên dùng khi 2 web ở 2 Hosting thật khác nhau)
 * 2. Đồng bộ qua Kết nối CSDL Trực tiếp (Dự phòng khi 2 DB chung máy chủ)
 */
class syncService {

    /**
     * Lấy toàn bộ cấu hình đồng bộ & hiển thị từ website_settings
     */
    public static function getSettings() {
        $db = database::getInstance();
        $keys = [
            'hide_trader_name',
            'hide_stall_price',
            'last_sync_time',
            'last_sync_log'
        ];

        $settings = [];
        try {
            $inList = "'" . implode("','", $keys) . "'";
            $rows = $db->select("SELECT setting_key, setting_value FROM website_settings WHERE setting_key IN ($inList)");
            foreach ($rows as $r) {
                $settings[$r['setting_key']] = $r['setting_value'];
            }
        } catch (Exception $e) {}

        return [
            'hide_trader_name' => $settings['hide_trader_name'] ?? '0',
            'hide_stall_price' => $settings['hide_stall_price'] ?? '0',
            'last_sync_time'   => $settings['last_sync_time'] ?? '',
            'last_sync_log'    => $settings['last_sync_log'] ?? 'Chưa có thông tin đồng bộ.'
        ];
    }

    /**
     * Cập nhật cài đặt vào website_settings
     */
    public static function saveSetting($key, $value) {
        $db = database::getInstance();
        $db->query("
            INSERT INTO website_settings (setting_key, setting_value) 
            VALUES (:key, :val) 
            ON DUPLICATE KEY UPDATE setting_value = :val
        ", ['key' => $key, 'val' => $value]);
    }

    /**
     * Phân tách host và port từ chuỗi dạng "127.0.0.1:3307" hoặc "localhost"
     */
    private static function parseHostPort($hostStr) {
        $host = $hostStr;
        $port = 3306;
        if (strpos($hostStr, ':') !== false) {
            $parts = explode(':', $hostStr, 2);
            $host = $parts[0];
            $port = (int)$parts[1];
        }
        return [$host, $port];
    }

    /**
     * Gửi yêu cầu HTTP cURL an toàn tới URL API bên App
     */
    private static function fetchHttp($url) {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) QuanLyChoSyncEngine/2.0');
            $response = curl_exec($ch);
            $err = curl_error($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($err) {
                return ['success' => false, 'message' => 'Lỗi cURL: ' . $err];
            }
            if ($httpCode >= 400) {
                return ['success' => false, 'message' => "Máy chủ App trả về mã lỗi HTTP {$httpCode}."];
            }
            return ['success' => true, 'body' => $response];
        } else {
            $context = stream_context_create([
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
                'http' => ['timeout' => 60]
            ]);
            $response = @file_get_contents($url, false, $context);
            if ($response === false) {
                return ['success' => false, 'message' => 'Không thể kết nối đến URL API bên App qua file_get_contents.'];
            }
            return ['success' => true, 'body' => $response];
        }
    }

    /**
     * Lưu mảng dữ liệu từ API vào CSDL Web với khớp nối cột động
     */
    private static function importTableData($connWeb, $tableName, $rows) {
        if (empty($rows) || !is_array($rows)) return 0;

        // 1. Lấy danh sách cột thực tế của bảng bên Web
        $webColsRes = $connWeb->query("SHOW COLUMNS FROM `$tableName`");
        if (!$webColsRes) return 0;
        $webCols = [];
        $primaryKey = '';
        while ($r = $webColsRes->fetch_assoc()) {
            $webCols[] = $r['Field'];
            if ($r['Key'] === 'PRI' && empty($primaryKey)) {
                $primaryKey = $r['Field'];
            }
        }

        // 2. Lấy danh sách cột từ dữ liệu nguồn API
        $sampleRow = $rows[0];
        $srcCols = array_keys($sampleRow);

        // 3. Tìm các cột chung
        $commonCols = array_values(array_intersect($srcCols, $webCols));
        if (empty($commonCols)) return 0;

        $colsListStr = implode('`, `', $commonCols);

        $updateParts = [];
        foreach ($commonCols as $c) {
            if ($c !== $primaryKey) {
                $updateParts[] = "`$c` = VALUES(`$c`)";
            }
        }
        $onDuplicateStr = !empty($updateParts) ? " ON DUPLICATE KEY UPDATE " . implode(', ', $updateParts) : "";

        $count = 0;
        foreach ($rows as $row) {
            $escapedVals = [];
            foreach ($commonCols as $c) {
                $val = $row[$c] ?? null;
                if ($val === null) {
                    $escapedVals[] = "NULL";
                } else {
                    $escapedVals[] = "'" . $connWeb->real_escape_string($val) . "'";
                }
            }
            $insertSql = "INSERT INTO `$tableName` (`$colsListStr`) VALUES (" . implode(', ', $escapedVals) . ")$onDuplicateStr";
            if ($connWeb->query($insertSql)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * PHƯƠNG THỨC ĐỒNG BỘ CHÍNH: Tự động chạy chế độ API hoặc Direct DB
     */
    public static function syncFromApp() {
        // 1. Kết nối CSDL đích (Web)
        list($webHost, $webPort) = self::parseHostPort(DB_HOST);
        $connWeb = @new mysqli($webHost, DB_USER, DB_PASSWORD, DB_NAME, $webPort);
        if ($connWeb->connect_error) {
            return [
                'success' => false,
                'message' => 'Không thể kết nối đến CSDL Web: ' . $connWeb->connect_error
            ];
        }
        $connWeb->set_charset('utf8mb4');

        $stats = [
            'markets'        => 0,
            'areas'          => 0,
            'business_lines' => 0,
            'stalls'         => 0,
            'traders'        => 0
        ];

        // =========================================================================
        // CHẾ ĐỘ 1: ĐỒNG BỘ QUA API CẦU NỐI (ƯU TIÊN KHI 2 WEB Ở 2 HOST THẬT)
        // =========================================================================
        if (defined('APP_SYNC_API_URL') && !empty(APP_SYNC_API_URL)) {
            $apiUrl = APP_SYNC_API_URL;
            $token = defined('APP_SYNC_SECRET_KEY') ? APP_SYNC_SECRET_KEY : '';
            $fullUrl = $apiUrl . (strpos($apiUrl, '?') !== false ? '&' : '?') . 'action=export&token=' . urlencode($token);

            $httpRes = self::fetchHttp($fullUrl);
            if (!$httpRes['success']) {
                $connWeb->close();
                return [
                    'success' => false,
                    'message' => 'Không thể lấy dữ liệu từ API App: ' . $httpRes['message']
                ];
            }

            $json = json_decode($httpRes['body'], true);
            if (!$json || !isset($json['success']) || !$json['success']) {
                $connWeb->close();
                $errDetail = $json['message'] ?? 'Phản hồi từ API App không hợp lệ hoặc sai mã Secret Key.';
                return [
                    'success' => false,
                    'message' => $errDetail
                ];
            }

            $payload = $json['data'] ?? [];

            try {
                $connWeb->query("SET FOREIGN_KEY_CHECKS = 0");

                $stats['markets']        = self::importTableData($connWeb, 'markets', $payload['markets'] ?? []);
                $stats['areas']          = self::importTableData($connWeb, 'areas', $payload['areas'] ?? []);
                $stats['business_lines'] = self::importTableData($connWeb, 'business_lines', $payload['business_lines'] ?? []);
                $stats['stalls']         = self::importTableData($connWeb, 'stalls', $payload['stalls'] ?? []);
                $stats['traders']        = self::importTableData($connWeb, 'traders', $payload['traders'] ?? []);

                $connWeb->query("SET FOREIGN_KEY_CHECKS = 1");

                $nowStr = date('H:i:s d/m/Y');
                $logMsg = "Đồng bộ API thành công: {$stats['markets']} chợ, {$stats['areas']} khu vực, {$stats['business_lines']} ngành hàng, {$stats['stalls']} sạp, {$stats['traders']} tiểu thương.";
                
                self::saveSetting('last_sync_time', $nowStr);
                self::saveSetting('last_sync_log', $logMsg);

                $connWeb->close();

                return [
                    'success' => true,
                    'message' => $logMsg,
                    'stats' => $stats,
                    'time' => $nowStr
                ];
            } catch (Exception $e) {
                $connWeb->query("SET FOREIGN_KEY_CHECKS = 1");
                $connWeb->close();
                return [
                    'success' => false,
                    'message' => 'Lỗi xử lý dữ liệu: ' . $e->getMessage()
                ];
            }
        }

        // =========================================================================
        // CHẾ ĐỘ 2: ĐỒNG BỘ QUA KẾT NỐI DATABASE TRỰC TIẾP (DỰ PHÒNG)
        // =========================================================================
        $hostStr  = defined('APP_DB_HOST') ? APP_DB_HOST : '127.0.0.1:3307';
        $dbName   = defined('APP_DB_NAME') ? APP_DB_NAME : 'quanlycho.vn';
        $user     = defined('APP_DB_USER') ? APP_DB_USER : 'root';
        $password = defined('APP_DB_PASSWORD') ? APP_DB_PASSWORD : '';

        list($host, $port) = self::parseHostPort($hostStr);

        $connApp = @new mysqli($host, $user, $password, $dbName, $port);
        if ($connApp->connect_error) {
            $connWeb->close();
            return [
                'success' => false,
                'message' => 'Không thể kết nối đến CSDL App: ' . $connApp->connect_error
            ];
        }
        $connApp->set_charset('utf8mb4');

        try {
            $connWeb->query("SET FOREIGN_KEY_CHECKS = 0");

            $tables = ['markets', 'areas', 'business_lines', 'stalls', 'traders'];
            foreach ($tables as $tbl) {
                $appRes = $connApp->query("SELECT * FROM `$tbl`");
                $rows = [];
                if ($appRes) {
                    while ($r = $appRes->fetch_assoc()) {
                        $rows[] = $r;
                    }
                }
                $stats[$tbl] = self::importTableData($connWeb, $tbl, $rows);
            }

            $connWeb->query("SET FOREIGN_KEY_CHECKS = 1");

            $nowStr = date('H:i:s d/m/Y');
            $logMsg = "Đồng bộ DB thành công: {$stats['markets']} chợ, {$stats['areas']} khu vực, {$stats['business_lines']} ngành hàng, {$stats['stalls']} sạp, {$stats['traders']} tiểu thương.";
            
            self::saveSetting('last_sync_time', $nowStr);
            self::saveSetting('last_sync_log', $logMsg);

            $connApp->close();
            $connWeb->close();

            return [
                'success' => true,
                'message' => $logMsg,
                'stats' => $stats,
                'time' => $nowStr
            ];
        } catch (Exception $e) {
            $connWeb->query("SET FOREIGN_KEY_CHECKS = 1");
            $connApp->close();
            $connWeb->close();
            return [
                'success' => false,
                'message' => 'Lỗi trong quá trình đồng bộ: ' . $e->getMessage()
            ];
        }
    }
}
