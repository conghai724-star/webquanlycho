<?php
/**
 * Generic Permission Authorization Service (Generic RBAC)
 * 
 * ponytail: Lõi phân quyền tổng quát. Cấu hình mặc định bên dưới là của dự án Chợ.
 * Khi sao chép sang dự án khác, chỉ cần gọi permissionService::init([...]) từ config.php
 * của dự án đó để ghi đè, hoặc sửa trực tiếp $config bên dưới.
 * File name is lowercase 'permissionservice.class.php' to support case-sensitive Linux autoloaders.
 */
class permissionService {

    // Cấu hình mặc định (dự án Chợ - ghi đè bằng init() nếu dùng cho dự án khác)
    private static $config = [
        'table' => 'user_market_permissions',
        'user_id_col' => 'permission_user_id',
        'scope_col' => 'permission_market_id',
        'module_col' => 'permission_module_code',
        'roles' => [
            'super_market' => ['is_super' => true],
            'admin_market' => ['all_modules_in_scope' => true],
            'admin'        => ['requires_explicit_permissions' => true]
        ]
    ];

    /**
     * Nạp cấu hình tùy biến cho hệ thống phân quyền của từng dự án cụ thể
     */
    public static function init(array $customConfig) {
        self::$config = array_replace_recursive(self::$config, $customConfig);
    }

    /**
     * Hàm kiểm tra quyền tổng quát
     * 
     * @param string $moduleCode Mã phân hệ cần kiểm tra
     * @param int $userId ID người dùng cần kiểm tra
     * @param int $scopeId ID phạm vi (ví dụ: market_id, clinic_id, school_id). Truyền 0 nếu không phân chia phạm vi.
     * @param string $actorCode Mã vai trò hiện tại của người dùng
     * @return bool
     */
    public static function checkAccess(string $moduleCode, int $userId, int $scopeId = 0, string $actorCode = ''): bool {
        if (!$userId) {
            return false;
        }

        $roles = self::$config['roles'];

        // 1. Kiểm tra vai trò tối cao (Super Admin) -> Bỏ qua kiểm tra, cho phép luôn
        if (isset($roles[$actorCode]['is_super']) && $roles[$actorCode]['is_super'] === true) {
            return true;
        }

        // 2. Kiểm tra vai trò quản lý theo phạm vi (Scope/Branch Admin) -> Cho phép mọi module thuộc phạm vi quản lý
        if (isset($roles[$actorCode]['all_modules_in_scope']) && $roles[$actorCode]['all_modules_in_scope'] === true) {
            return $scopeId > 0;
        }

        // 3. Kiểm tra quyền cụ thể được tích chọn trong cơ sở dữ liệu
        $db = database::getInstance();
        $table = self::$config['table'];
        $userCol = self::$config['user_id_col'];
        $scopeCol = self::$config['scope_col'];
        $moduleCol = self::$config['module_col'];

        $sql = "SELECT 1 FROM {$table} WHERE {$userCol} = :user_id AND {$moduleCol} = :module";
        $params = [
            'user_id' => $userId,
            'module'  => $moduleCode
        ];

        // Nếu bảng phân quyền có phân theo phạm vi và phạm vi hiện tại là hợp lệ
        if (!empty($scopeCol) && $scopeId > 0) {
            $sql .= " AND {$scopeCol} = :scope_id";
            $params['scope_id'] = $scopeId;
        }

        $res = $db->selectOne($sql, $params);
        return !empty($res);
    }
}
?>
