<?php
/**
 * Model quản lý trạng thái hệ thống tập trung (System Statuses)
 */
class statusModel {
    private $db;
    private static $cache = [];

    public function __construct() {
        $this->db = database::getInstance();
    }

    /**
     * Lấy ID của trạng thái dựa vào domain và code
     * @param string $domain (user, stall, trader, contract, bill, etc.)
     * @param string $code (active, empty, rented, etc.)
     * @return int|null
     */
    public function getIdByCode($domain, $code) {
        $cacheKey = "{$domain}_{$code}";
        if (isset(self::$cache[$cacheKey])) {
            return self::$cache[$cacheKey];
        }

        $sql = "SELECT status_id FROM system_statuses WHERE status_domain = :domain AND status_code = :code LIMIT 1";
        $res = $this->db->selectOne($sql, [
            'domain' => $domain,
            'code'   => $code
        ]);

        $id = $res ? (int)$res['status_id'] : null;
        self::$cache[$cacheKey] = $id;
        return $id;
    }
}
