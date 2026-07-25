<?php
/**
 * Model quản lý thông tin Tiểu Thương (Traders)
 */
class traderModel {
    private $db;

    public function __construct() {
        $this->db = database::getInstance();
    }

    /**
     * Lấy toàn bộ danh sách tiểu thương kèm công nợ động và bộ lọc tìm kiếm
     */
    public function getAllTraders($search = '', $business_line_id = '', $status = '', $marketId = null) {
        $sql = "SELECT t.*, 
                       (
                           SELECT COALESCE(SUM(b.bill_total_amount - b.bill_paid_amount), 0)
                           FROM bills b
                           JOIN contracts c ON b.bill_contract_id = c.contract_id
                           WHERE c.contract_trader_id = t.trader_id AND b.bill_status IN ('unpaid', 'partially_paid')
                       ) AS total_debt,
                        ss.status_code AS status_code,
                        ss.status_name,
                        sc.color_class,
                        bl.line_name AS business_line_name,
                        bl.line_code AS business_line_code
                FROM traders t
                LEFT JOIN system_statuses ss ON ss.status_id = t.trader_status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                LEFT JOIN business_lines bl ON bl.line_id = t.trader_business_line_id
                WHERE ss.status_code != '99'";
        $params = [];

        if ($marketId) {
            $sql .= " AND t.trader_id IN (
                SELECT DISTINCT c.contract_trader_id 
                FROM contracts c
                JOIN stalls s ON c.contract_stall_id = s.stall_id
                JOIN areas a ON s.stall_area_id = a.area_id
                WHERE a.area_market_id = :market_id
            )";
            $params['market_id'] = $marketId;
        }

        if (!empty($search)) {
            $sql .= " AND (t.trader_fullname LIKE :search1 OR t.trader_phone LIKE :search2 OR t.trader_cccd LIKE :search3 OR t.trader_code LIKE :search4)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        if (!empty($trader_business_line_id)) {
            $sql .= " AND t.trader_business_line_id = :trader_business_line_id";
            $params['trader_business_line_id'] = $trader_business_line_id;
        }

        if (!empty($status)) {
            $sql .= " AND ss.status_code = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY t.trader_id DESC";
        return $this->db->select($sql, $params);
    }

    /**
     * Lấy thông tin chi tiết một tiểu thương
     */
    public function getTraderById($trader_id) {
        $sql = "SELECT t.*, ss.status_code AS status_code, ss.status_name, sc.color_class,
                       bl.line_name AS business_line_name,
                       bl.line_code AS business_line_code
                FROM traders t
                LEFT JOIN system_statuses ss ON ss.status_id = t.trader_status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                LEFT JOIN business_lines bl ON bl.line_id = t.trader_business_line_id
                WHERE t.trader_id = :trader_id AND (ss.status_code IS NULL OR ss.status_code != '99')";
        return $this->db->selectOne($sql, ['trader_id' => $trader_id]);
    }

    /**
     * Thêm tiểu thương mới
     */
    public function createTrader($data) {
        $statusModel = new statusModel();
        $activeStatusId = $statusModel->getIdByCode('trader', 'active');

        $sql = "INSERT INTO traders (trader_code, trader_fullname, trader_phone, trader_cccd, trader_address, trader_business_line_id, trader_description, trader_license_file, trader_status_id) 
                VALUES (:trader_code, :fullname, :phone, :cccd, :address, :business_line_id, :line_description, :license_file, :trader_status_id)";
        
        $params = [
            'trader_code'      => $data['trader_code'],
            'fullname'         => $data['trader_fullname'],
            'phone'            => $data['trader_phone'],
            'cccd'             => $data['trader_cccd'],
            'address'          => $data['trader_address'] ?? null,
            'business_line_id' => $data['trader_business_line_id'] ?: null,
            'line_description'      => $data['trader_description'] ?? null,
            'license_file'     => $data['trader_license_file'] ?? null,
            'trader_status_id'        => $data['trader_status_id'] ?: $activeStatusId
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    /**
     * Cập nhật thông tin tiểu thương
     */
    public function updateTrader($trader_id, $data) {
        $statusModel = new statusModel();
        $activeStatusId = $statusModel->getIdByCode('trader', 'active');

        $sql = "UPDATE traders 
                SET trader_fullname = :fullname, trader_phone = :phone, trader_cccd = :cccd, 
                    trader_address = :address, trader_business_line_id = :business_line_id, 
                    trader_description = :line_description, trader_license_file = :license_file, trader_status_id = :trader_status_id 
                WHERE trader_id = :trader_id";
        
        $params = [
            'trader_id'               => $trader_id,
            'fullname'         => $data['trader_fullname'],
            'phone'            => $data['trader_phone'],
            'cccd'             => $data['trader_cccd'],
            'address'          => $data['trader_address'] ?? null,
            'business_line_id' => $data['trader_business_line_id'] ?: null,
            'line_description'      => $data['trader_description'] ?? null,
            'license_file'     => $data['trader_license_file'] ?? null,
            'trader_status_id'        => $data['trader_status_id'] ?: $activeStatusId
        ];

        return $this->db->query($sql, $params);
    }

    public function deleteTrader($trader_id) {
        $sql = "UPDATE traders 
                SET trader_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'trader' AND status_code = '99') 
                WHERE trader_id = :trader_id";
        return $this->db->query($sql, ['trader_id' => $trader_id]);
    }

    /**
     * Kiểm tra xem Mã tiểu thương đã tồn tại chưa
     */
    public function isTraderCodeExists($code, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM traders WHERE trader_code = :code";
        $params = ['code' => $code];
        if ($excludeId !== null) {
            $sql .= " AND trader_id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        $res = $this->db->selectOne($sql, $params);
        return ($res['count'] ?? 0) > 0;
    }

    /**
     * Kiểm tra xem Số CCCD đã tồn tại chưa
     */
    public function isCccdExists($cccd, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM traders WHERE trader_cccd = :cccd";
        $params = ['cccd' => $cccd];
        if ($excludeId !== null) {
            $sql .= " AND trader_id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        $res = $this->db->selectOne($sql, $params);
        return ($res['count'] ?? 0) > 0;
    }

    /**
     * Lấy danh sách các trạng thái hợp lệ của tiểu thương (loại trừ đã xóa)
     */
    public function getTraderStatuses() {
        $sql = "SELECT * FROM system_statuses WHERE status_domain = 'trader' AND status_code != '99'";
        return $this->db->select($sql);
    }

    /**
     * Lấy danh sách toàn bộ ngành hàng
     */
    public function getBusinessLines() {
        $sql = "SELECT * FROM business_lines ORDER BY line_name ASC";
        return $this->db->select($sql);
    }

    /**
     * Lấy danh sách tiểu thương chưa thuê sạp (khả dụng để gán sạp)
     */
    public function getAvailableTraders() {
        $sql = "SELECT contract_id, trader_fullname, trader_code FROM traders 
                WHERE contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'trader' AND status_code = 'active')
                  AND contract_id NOT IN (SELECT contract_trader_id FROM contracts WHERE status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active'))
                ORDER BY fullname ASC";
        return $this->db->select($sql);
    }
}
