<?php

/**
 * Static facade used by the adminmaster controllers and models.
 *
 * The underlying market/session logic lives in the existing `general`
 * singleton.  This facade keeps the newer module's static API compatible
 * without duplicating its authorization rules.
 */
class marketService {
    private static function helper() {
        return general::getInstance();
    }

    public static function isSuperAdmin(): bool {
        return self::helper()->isSuperAdmin();
    }

    public static function isAdminMarket(): bool {
        return self::helper()->isAdminMarket();
    }

    public static function currentMarketId(): int {
        return self::helper()->currentMarketId();
    }

    public static function getAccessibleMarketIds(): array {
        return self::helper()->getAccessibleMarketIds();
    }

    public static function applyScope(string $sql, string $alias = ''): string {
        return self::helper()->applyScope($sql, $alias);
    }

    public static function checkWritePermission($marketId) {
        return self::helper()->checkWritePermission($marketId);
    }

    public static function checkModuleAccess(string $module): bool {
        return self::helper()->checkModuleAccess($module);
    }

    public static function requireModuleAccess(string $module) {
        return self::helper()->requireModuleAccess($module);
    }
}
