<?php
/**
 * Model quản lý Hợp Đồng Thuê Sạp (Contracts)
 */
class contractModel {
    private $db;

    public function __construct() {
        $this->db = database::getInstance();
    }

    /**
     * Lấy toàn bộ danh sách hợp đồng
     */
    public function getAll($status = null, $search = null, $marketId = null) {
        $sql = "SELECT c.*, t.trader_fullname AS trader_name, t.trader_phone AS trader_phone, s.stall_code, s.stall_base_price AS price, a.area_name,
                       ss.status_code AS status_code, ss.status_name, sc.color_class,
                       DATEDIFF(c.contract_end_date, CURRENT_DATE) AS days_remaining
                FROM contracts c
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                LEFT JOIN stalls s ON c.contract_stall_id = s.stall_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN system_statuses ss ON c.contract_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                WHERE ss.status_code != '99' AND (t.trader_id IS NULL OR t.trader_status_id != (SELECT status_id FROM system_statuses WHERE status_domain = 'trader' AND status_code = '99'))";
        
        $params = [];

        if ($marketId) {
            $sql .= " AND a.area_market_id = :market_id";
            $params['market_id'] = $marketId;
        }

        if ($status) {
            $sql .= " AND ss.status_code = :status";
            $params['status'] = $status;
        }

        if (!empty($search)) {
            $sql .= " AND (c.contract_number LIKE :search1 OR c.contract_name LIKE :search2 OR t.trader_fullname LIKE :search3 OR s.stall_code LIKE :search4)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
            $params['search4'] = "%$search%";
        }

        $sql .= " ORDER BY c.contract_id DESC";
        return $this->db->select($sql, $params);
    }

    /**
     * Lấy thông tin chi tiết một hợp đồng
     */
    public function getById($contract_id) {
        $sql = "SELECT c.*, t.trader_fullname AS trader_name, t.trader_phone AS trader_phone, t.trader_cccd AS trader_cccd,
                       s.stall_code, s.stall_type, s.stall_area_size, s.stall_base_price AS price, a.area_name,
                       ss.status_code AS status_code, ss.status_name, sc.color_class,
                       DATEDIFF(c.contract_end_date, CURRENT_DATE) AS days_remaining
                FROM contracts c
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                LEFT JOIN stalls s ON c.contract_stall_id = s.stall_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN system_statuses ss ON c.contract_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                WHERE c.contract_id = :contract_id AND ss.status_code != '99' AND (t.trader_id IS NULL OR t.trader_status_id != (SELECT status_id FROM system_statuses WHERE status_domain = 'trader' AND status_code = '99'))";
        return $this->db->selectOne($sql, ['contract_id' => $contract_id]);
    }

    /**
     * Tạo hợp đồng mới
     */
    public function create($data) {
        $statusModel = new statusModel();
        $activeStatusId = $statusModel->getIdByCode('contract', 'active');

        $sql = "INSERT INTO contracts (contract_trader_id, contract_stall_id, contract_number, contract_name, contract_description, contract_file, contract_start_date, contract_end_date, contract_deposit, contract_status_id) 
                VALUES (:contract_trader_id, :contract_stall_id, :contract_number, :contract_name, :contract_description, :contract_file, :contract_start_date, :contract_end_date, :contract_deposit, :contract_status_id)";
        
        $params = [
            'contract_trader_id'       => $data['contract_trader_id'],
            'contract_stall_id'        => $data['contract_stall_id'],
            'contract_number' => $data['contract_number'],
            'contract_name'            => $data['contract_name'],
            'contract_description'     => $data['contract_description'] ?? null,
            'contract_file'   => $data['contract_file'] ?? null,
            'contract_start_date'      => $data['contract_start_date'],
            'contract_end_date'        => $data['contract_end_date'],
            'contract_deposit'         => $data['contract_deposit'],
            'contract_status_id'       => $data['contract_status_id'] ?? $activeStatusId
        ];

        try {
            $this->db->beginTransaction();
            
            // 1. Tạo hợp đồng
            $this->db->query($sql, $params);
            $contractId = $this->db->lastInsertId();

            // 2. Cập nhật trạng thái sạp thành 'rented' (đã thuê)
            $stallModel = new stallModel();
            $stallModel->updateStatus($data['contract_stall_id'], 'rented');

            $this->db->commit();
            return $contractId;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Cập nhật trạng thái hợp đồng trực tiếp
     */
    public function updateStatus($contract_id, $status) {
        if (is_numeric($status)) {
            $statusId = $status;
        } else {
            $statusModel = new statusModel();
            $statusId = $statusModel->getIdByCode('contract', $status);
        }
        $sql = "UPDATE contracts SET contract_status_id = :contract_status_id WHERE contract_id = :contract_id";
        return $this->db->query($sql, ['contract_id' => $contract_id, 'contract_status_id' => $statusId]);
    }

    /**
     * Gia hạn hợp đồng
     */
    public function renew($contract_id, $newEndDate) {
        $statusModel = new statusModel();
        $activeStatusId = $statusModel->getIdByCode('contract', 'active');

        $sql = "UPDATE contracts SET contract_end_date = :contract_end_date, contract_status_id = :contract_status_id WHERE contract_id = :contract_id";
        return $this->db->query($sql, [
            'contract_id' => $contract_id,
            'contract_end_date' => $newEndDate,
            'contract_status_id' => $activeStatusId
        ]);
    }

    /**
     * Thanh lý hợp đồng
     */
    public function liquidate($contract_id) {
        $statusModel = new statusModel();
        $liquidatedStatusId = $statusModel->getIdByCode('contract', 'liquidated');

        // Lấy thông tin hợp đồng trước khi thay đổi trạng thái
        $contract = $this->getById($contract_id);
        if (!$contract) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            // 1. Cập nhật trạng thái hợp đồng
            $sql = "UPDATE contracts SET contract_status_id = :contract_status_id WHERE contract_id = :contract_id";
            $this->db->query($sql, ['contract_id' => $contract_id, 'contract_status_id' => $liquidatedStatusId]);

            // 2. Trả sạp về trạng thái empty (trống)
            $stallModel = new stallModel();
            $stallModel->updateStatus($contract['contract_stall_id'], 'empty');

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Chấm dứt hợp đồng trước hạn
     */
    public function terminate($contract_id) {
        $statusModel = new statusModel();
        $terminatedStatusId = $statusModel->getIdByCode('contract', 'terminated');

        // Lấy thông tin hợp đồng trước khi thay đổi trạng thái
        $contract = $this->getById($contract_id);
        if (!$contract) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            // 1. Cập nhật trạng thái hợp đồng
            $sql = "UPDATE contracts SET contract_status_id = :contract_status_id WHERE contract_id = :contract_id";
            $this->db->query($sql, ['contract_id' => $contract_id, 'contract_status_id' => $terminatedStatusId]);

            // 2. Trả sạp về trạng thái empty (trống)
            $stallModel = new stallModel();
            $stallModel->updateStatus($contract['contract_stall_id'], 'empty');

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Xóa mềm hợp đồng (99)
     */
    public function softDelete($contract_id) {
        $statusModel = new statusModel();
        $deletedStatusId = $statusModel->getIdByCode('contract', '99');

        // Lấy thông tin hợp đồng trước khi thay đổi trạng thái
        $contract = $this->getById($contract_id);
        if (!$contract) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            // 1. Cập nhật trạng thái hợp đồng thành 99 (Đã xóa)
            $sql = "UPDATE contracts SET contract_status_id = :contract_status_id WHERE contract_id = :contract_id";
            $this->db->query($sql, ['contract_id' => $contract_id, 'contract_status_id' => $deletedStatusId]);

            // 2. Trả sạp về trạng thái empty (trống)
            $stallModel = new stallModel();
            $stallModel->updateStatus($contract['contract_stall_id'], 'empty');

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Lấy danh sách phụ lục của hợp đồng
     */
    public function getAppendices($contractId) {
        $sql = "SELECT * FROM contract_appendices WHERE appendix_contract_id = :contract_id ORDER BY appendix_id DESC";
        return $this->db->select($sql, ['contract_id' => $contractId]);
    }

    /**
     * Thêm phụ lục hợp đồng
     */
    public function addAppendix($data) {
        $sql = "INSERT INTO contract_appendices (appendix_contract_id, appendix_number, contract_name, appendix_sign_date, appendix_effect_date, appendix_content, appendix_file) 
                VALUES (:contract_id, :appendix_number, :contract_name, :sign_date, :effect_date, :content, :file)";
        
        $params = [
            'contract_id'     => $data['contract_id'],
            'appendix_number' => $data['appendix_number'],
            'contract_name'            => $data['contract_name'],
            'sign_date'       => $data['sign_date'],
            'effect_date'     => $data['effect_date'],
            'content'         => $data['content'],
            'file'            => $data['file'] ?? null
        ];

        return $this->db->query($sql, $params);
    }

    /**
     * Lấy danh sách trạng thái của hợp đồng
     */
    public function getContractStatuses() {
        $sql = "SELECT * FROM system_statuses WHERE status_domain = 'contract' AND status_code != '99'";
        return $this->db->select($sql);
    }

    /**
     * Lấy hợp đồng đang hoạt động của một sạp
     */
    public function getActiveContractByStall($stallId) {
        $sql = "SELECT * FROM contracts WHERE contract_stall_id = :stall_id AND contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active') LIMIT 1";
        return $this->db->selectOne($sql, ['stall_id' => $stallId]);
    }

    /**
     * Kiểm tra xem số hợp đồng đã tồn tại chưa
     */
    public function isContractNumberExists($num, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM contracts WHERE contract_number = :num AND contract_status_id != (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = '99')";
        $params = ['num' => $num];
        if ($excludeId !== null) {
            $sql .= " AND contract_id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        $res = $this->db->selectOne($sql, $params);
        return ($res['count'] ?? 0) > 0;
    }

    /**
     * Kiểm tra xem số phụ lục đã tồn tại chưa
     */
    public function isAppendixNumberExists($num, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM contract_appendices WHERE appendix_number = :num";
        $params = ['num' => $num];
        if ($excludeId !== null) {
            $sql .= " AND appendix_id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        $res = $this->db->selectOne($sql, $params);
        return ($res['count'] ?? 0) > 0;
    }
}
