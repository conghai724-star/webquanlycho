<?php

/**
 * Static facade cho các controller/view gọi logic nghiệp vụ Chợ.
 *
 * ponytail: 6 method wrapper thuần (isSuperAdmin, isAdminMarket, currentMarketId,
 * getAccessibleMarketIds, applyScope, checkWritePermission) được thay bằng __callStatic.
 * Chỉ giữ 2 method có logic riêng: checkModuleAccess + requireModuleAccess.
 * 50+ caller không cần sửa gì.
 */
class marketService {

    /**
     * Proxy mọi static call không khai báo sang general singleton.
     * Ví dụ: marketService::isSuperAdmin() → general::getInstance()->isSuperAdmin()
     */
    public static function __callStatic(string $name, array $args) {
        return general::getInstance()->$name(...$args);
    }

    public static function checkModuleAccess(string $module): bool {
        $helper = general::getInstance();
        $userId = (int)($helper->get('user_id') ?? 0);
        $scopeId = (int)$helper->currentMarketId();
        $actorCode = $helper->get('actor_code') ?? '';
        return permissionService::checkAccess($module, $userId, $scopeId, $actorCode);
    }

    public static function requireModuleAccess(string $module) {
        if (!self::checkModuleAccess($module)) {
            header('Location: ' . BASE_URL . 'system/users');
            exit();
        }
    }
}
