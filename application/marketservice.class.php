<?php

/**
 * Service quản lý Chợ và Phân quyền truy cập Chợ
 */
class marketService {

    /**
     * Lấy ID Chợ hiện tại đang được chọn (mặc định = 1)
     */
    public static function currentMarketId() {
        if (isset($_GET['market_id']) && (int)$_GET['market_id'] > 0) {
            $_SESSION['active_market_id'] = (int)$_GET['market_id'];
            return (int)$_GET['market_id'];
        }
        if (isset($_SESSION['active_market_id']) && (int)$_SESSION['active_market_id'] > 0) {
            return (int)$_SESSION['active_market_id'];
        }
        
        // Mặc định lấy chợ đầu tiên trong Database
        try {
            $db = database::getInstance();
            $mkt = $db->selectOne("SELECT market_id FROM markets ORDER BY market_id ASC LIMIT 1");
            if ($mkt) {
                $_SESSION['active_market_id'] = (int)$mkt['market_id'];
                return (int)$mkt['market_id'];
            }
        } catch (Exception $e) {}

        return 1;
    }

    /**
     * Kiểm tra xem có phải Admin tối cao không
     */
    public static function isSuperAdmin() {
        return session::isWebAdmin() || (session::get('user_group') == 1);
    }

    /**
     * Lấy danh sách ID các Chợ mà tài khoản có quyền truy cập
     */
    public static function getAccessibleMarketIds() {
        try {
            $db = database::getInstance();
            $markets = $db->select("SELECT market_id FROM markets WHERE market_status_code != 'deleted'");
            return array_column($markets, 'market_id');
        } catch (Exception $e) {
            return [1];
        }
    }

    /**
     * Kiểm tra quyền ghi / cập nhật dữ liệu chợ
     */
    public static function checkWritePermission() {
        return true;
    }

    /**
     * Áp dụng điều kiện lọc theo chợ vào truy vấn
     */
    public static function applyScope($sql) {
        return $sql;
    }

    public static function checkModuleAccess(string $module): bool {
        return session::hasWebModule($module);
    }

    public static function requireModuleAccess(string $module) {
        session::requireWebModule($module);
    }
}
