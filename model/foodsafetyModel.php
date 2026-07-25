<?php
/**
 * Model quản lý An Toàn Thực Phẩm và giấy tờ liên quan (Food Safety)
 */
class foodsafetyModel {
    private $db;

    public function __construct() {
        $this->db = database::getInstance();
    }

    /**
     * Lấy danh sách giấy chứng nhận vệ sinh ATTP, sức khỏe, tập huấn của tiểu thương
     */
    public function getCertificates($traderId = null, $docType = null, $status = null, $search = null, $marketId = null) {
        $sql = "SELECT c.*, dt.doc_type_name AS doc_type, dt.doc_type_code, t.trader_fullname AS trader_name, t.trader_phone AS trader_phone, t.trader_description AS shop_name,
                       bl.line_name AS business_line,
                       ss.status_code AS status_code, ss.status_name, sc.color_class,
                       DATEDIFF(c.attp_expiry_date, CURRENT_DATE) AS days_remaining
                FROM trader_attp c
                LEFT JOIN document_types dt ON c.attp_doc_type_id = dt.doc_type_id
                LEFT JOIN traders t ON c.attp_trader_id = t.trader_id
                LEFT JOIN business_lines bl ON bl.line_id = t.trader_business_line_id
                LEFT JOIN system_statuses ss ON c.attp_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                WHERE ss.status_code != '99' AND (t.trader_id IS NULL OR t.trader_status_id != (SELECT status_id FROM system_statuses WHERE status_domain = 'trader' AND status_code = '99'))";
        
        $params = [];

        if ($marketId) {
            $sql .= " AND t.trader_id IN (
                SELECT DISTINCT c2.contract_trader_id 
                FROM contracts c2
                JOIN stalls s2 ON c2.contract_stall_id = s2.stall_id
                JOIN areas a2 ON s2.stall_area_id = a2.area_id
                WHERE a2.area_market_id = :market_id
            )";
            $params['market_id'] = $marketId;
        }

        if ($traderId) {
            $sql .= " AND c.attp_trader_id = :attp_trader_id";
            $params['attp_trader_id'] = $traderId;
        }

        if ($docType) {
            $sql .= " AND c.attp_doc_type_id = :doc_type";
            $params['doc_type'] = $docType;
        }

        if ($status) {
            $sql .= " AND ss.status_code = :status";
            $params['status'] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (c.attp_doc_number LIKE :search1 OR c.contract_name LIKE :search2 OR t.trader_fullname LIKE :search3)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
        }

        $sql .= " ORDER BY c.attp_expiry_date ASC";
        return $this->db->select($sql, $params);
    }

    /**
     * Lấy thông tin một giấy chứng nhận theo attp_status_id
     */
    public function getById($attp_id) {
        $sql = "SELECT c.*, dt.doc_type_name AS doc_type, dt.doc_type_code, t.trader_fullname AS trader_name, t.trader_phone AS trader_phone, t.trader_description AS shop_name,
                       ss.status_code AS status_code, ss.status_name, sc.color_class,
                       DATEDIFF(c.attp_expiry_date, CURRENT_DATE) AS days_remaining
                FROM trader_attp c
                LEFT JOIN document_types dt ON c.attp_doc_type_id = dt.doc_type_id
                LEFT JOIN traders t ON c.attp_trader_id = t.trader_id
                LEFT JOIN system_statuses ss ON c.attp_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                WHERE c.attp_id = :attp_id AND ss.status_code != '99' AND (t.trader_id IS NULL OR t.trader_status_id != (SELECT status_id FROM system_statuses WHERE status_domain = 'trader' AND status_code = '99'))";
        
        return $this->db->selectOne($sql, ['attp_id' => $attp_id]);
    }

    public function createCertificate($data) {
        $statusModel = new statusModel();
        $validStatusId = $statusModel->getIdByCode('attp', 'valid');

        $sql = "INSERT INTO trader_attp (attp_trader_id, attp_doc_type_id, attp_doc_number, attp_name, attp_description, attp_file, attp_status_id, attp_issuer, attp_issue_date, attp_expiry_date)
                VALUES (:attp_trader_id, :attp_doc_type_id, :attp_doc_number, :attp_name, :attp_description, :attp_file, :attp_status_id, :attp_issuer, :attp_issue_date, :attp_expiry_date)";
        
        $params = [
            'attp_trader_id'   => $data['attp_trader_id'],
            'attp_doc_type_id' => $data['attp_doc_type_id'],
            'attp_doc_number'  => $data['attp_doc_number'],
            'attp_name'        => $data['attp_name'],
            'attp_description' => $data['attp_description'] ?? null,
            'attp_file'        => $data['attp_file'] ?? null,
            'attp_status_id'   => $data['attp_status_id'] ?? $validStatusId,
            'attp_issuer'      => $data['attp_issuer'] ?? null,
            'attp_issue_date'  => $data['attp_issue_date'],
            'attp_expiry_date' => $data['attp_expiry_date']
        ];

        return $this->db->query($sql, $params);
    }

    /**
     * Cập nhật thông tin giấy tờ vệ sinh ATTP
     */
    public function updateCertificate($attp_id, $data) {
        $sql = "UPDATE trader_attp 
                SET attp_trader_id = :attp_trader_id,
                    attp_doc_type_id = :attp_doc_type_id, 
                    attp_doc_number = :attp_doc_number, 
                    attp_name = :attp_name, 
                    attp_description = :attp_description, 
                    attp_issuer = :attp_issuer, 
                    attp_issue_date = :attp_issue_date, 
                    attp_expiry_date = :attp_expiry_date";
        
        $params = [
            'attp_id'          => $attp_id,
            'attp_trader_id'   => $data['attp_trader_id'],
            'attp_doc_type_id' => $data['attp_doc_type_id'],
            'attp_doc_number'  => $data['attp_doc_number'],
            'attp_name'        => $data['attp_name'],
            'attp_description' => $data['attp_description'] ?? null,
            'attp_issuer'      => $data['attp_issuer'] ?? null,
            'attp_issue_date'  => $data['attp_issue_date'],
            'attp_expiry_date' => $data['attp_expiry_date']
        ];

        if (isset($data['attp_file'])) {
            $sql .= ", attp_file = :attp_file";
            $params['attp_file'] = $data['attp_file'];
        }

        if (isset($data['attp_status_id'])) {
            $sql .= ", attp_status_id = :attp_status_id";
            $params['attp_status_id'] = $data['attp_status_id'];
        }

        $sql .= " WHERE attp_id = :attp_id";
        return $this->db->query($sql, $params);
    }

    /**
     * Xóa mềm giấy tờ (99)
     */
    public function deleteCertificate($attp_id) {
        $statusModel = new statusModel();
        $deletedStatusId = $statusModel->getIdByCode('attp', '99');

        $sql = "UPDATE trader_attp SET attp_status_id = :attp_status_id WHERE attp_id = :attp_id";
        return $this->db->query($sql, [
            'attp_id' => $attp_id,
            'attp_status_id' => $deletedStatusId
        ]);
    }

    /**
     * Lấy danh sách trạng thái giấy tờ vệ sinh ATTP (trừ 99)
     */
    public function getAttpStatuses() {
        $sql = "SELECT * FROM system_statuses WHERE status_domain = 'attp' AND status_code != '99' ORDER BY status_id ASC";
        return $this->db->select($sql);
    }

    public function autoUpdateExpiryStatus() {
        $today = date('Y-m-d');
        $sql = "UPDATE trader_attp 
                SET attp_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'attp' AND status_code = 'expired') 
                WHERE attp_expiry_date < :today 
                  AND attp_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'attp' AND status_code = 'valid')";
        return $this->db->query($sql, ['today' => $today]);
    }

    /**
     * Kiểm tra xem số chứng nhận đã tồn tại chưa
     */
    public function isDocNumberExists($num, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM trader_attp WHERE attp_doc_number = :num AND attp_status_id != (SELECT status_id FROM system_statuses WHERE status_domain = 'attp' AND status_code = '99')";
        $params = ['num' => $num];
        if ($excludeId !== null) {
            $sql .= " AND attp_id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        $res = $this->db->selectOne($sql, $params);
        return ($res['count'] ?? 0) > 0;
    }
}
