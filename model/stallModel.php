<?php
/**
 * Model quản lý Khu vực và Sạp Chợ (Areas & Stalls)
 */
class stallModel {
    private $db;

    public function __construct() {
        $this->db = database::getInstance();
    }

    /* ==========================================================================
       1. Quản lý Khu vực (Areas)
       ========================================================================== */

    public function getAreas($marketId = null) {
        $sql = "SELECT * FROM areas";
        $params = [];
        if ($marketId) {
            $sql .= " WHERE area_market_id = :market_id";
            $params['market_id'] = $marketId;
        }
        $sql .= " ORDER BY area_name ASC";
        return $this->db->select($sql, $params);
    }

    public function createArea($contract_name, $desc = '') {
        $sql = "INSERT INTO areas (area_name, area_description) VALUES (:contract_name, :desc)";
        $this->db->query($sql, ['contract_name' => $contract_name, 'desc' => $desc]);
        return $this->db->lastInsertId();
    }

    /* ==========================================================================
       2. Quản lý Sạp chợ (Stalls)
       ========================================================================== */

    /**
     * Lấy toàn bộ danh sách sạp kèm thông tin khu vực và bộ lọc tìm kiếm
     */
    public function getAll($areaId = null, $status = null, $search = null, $marketId = null) {
        $sql = "SELECT s.*, st.stall_type_name AS stall_type, ss.status_code AS status, ss.status_name, sc.color_class, a.area_name, a.area_block, a.area_lot, t.trader_fullname AS trader_name, bl.line_name AS business_line_name,
                       c.contract_status_id,
                       (SELECT status_code FROM system_statuses WHERE status_id = c.contract_status_id) AS contract_status_code 
                FROM stalls s
                LEFT JOIN stall_types st ON s.stall_type_id = st.stall_type_id
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN contracts c ON c.contract_stall_id = s.stall_id AND c.contract_status_id IN (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code IN ('active', 'draft'))
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                LEFT JOIN business_lines bl ON t.trader_business_line_id = bl.line_id";
        
        $where = [];
        $params = [];

        if ($marketId) {
            $where[] = "a.area_market_id = :market_id";
            $params['market_id'] = $marketId;
        }

        if ($areaId) {
            $where[] = "s.stall_area_id = :stall_area_id";
            $params['stall_area_id'] = $areaId;
        }

        if ($status) {
            if ($status === 'rented') {
                $where[] = "c.contract_id IS NOT NULL";
            } elseif ($status === 'empty') {
                $where[] = "c.contract_id IS NULL AND ss.status_code = 'empty'";
            } else {
                $where[] = "ss.status_code = :status";
                $params['status'] = $status;
            }
        }

        if (!empty($search)) {
            $where[] = "(s.stall_code LIKE :search1 OR a.area_block LIKE :search2 OR a.area_lot LIKE :search3)";
            $params['search1'] = "%$search%";
            $params['search2'] = "%$search%";
            $params['search3'] = "%$search%";
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY a.area_name ASC, s.stall_code ASC";
        return $this->db->select($sql, $params);
    }

    public function getById($stall_id) {
        $sql = "SELECT s.*, st.stall_type_name AS stall_type, ss.status_code AS status, ss.status_name, sc.color_class, a.area_name, a.area_block, a.area_lot, t.trader_fullname AS trader_name, bl.line_name AS business_line_name,
                       c.contract_status_id,
                       (SELECT status_code FROM system_statuses WHERE status_id = c.contract_status_id) AS contract_status_code 
                FROM stalls s 
                LEFT JOIN stall_types st ON s.stall_type_id = st.stall_type_id
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id 
                LEFT JOIN contracts c ON c.contract_stall_id = s.stall_id AND c.contract_status_id IN (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code IN ('active', 'draft'))
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                LEFT JOIN business_lines bl ON t.trader_business_line_id = bl.line_id
                WHERE s.stall_id = :stall_id";
        return $this->db->selectOne($sql, ['stall_id' => $stall_id]);
    }

    public function create($data) {
        $statusModel = new statusModel();
        $emptyStatusId = $statusModel->getIdByCode('stall', 'empty');

        $sql = "INSERT INTO stalls (stall_area_id, stall_code, stall_type_id, stall_area_size, stall_base_price, stall_status_id, stall_map_coordinate_x, stall_map_coordinate_y) 
                VALUES (:stall_area_id, :stall_code, :stall_type_id, :stall_area_size, :stall_base_price, :stall_status_id, :stall_map_coordinate_x, :stall_map_coordinate_y)";
        
        $params = [
            'stall_area_id'       => $data['stall_area_id'],
            'stall_code'    => $data['stall_code'],
            'stall_type_id' => $data['stall_type_id'],
            'stall_area_size'     => $data['stall_area_size'],
            'stall_base_price'    => $data['stall_base_price'],
            'stall_status_id'     => $data['stall_status_id'] ?: $emptyStatusId,
            'stall_map_coordinate_x' => $data['stall_map_coordinate_x'] ?? null,
            'stall_map_coordinate_y' => $data['stall_map_coordinate_y'] ?? null
        ];

        $this->db->query($sql, $params);
        return $this->db->lastInsertId();
    }

    public function update($stall_id, $data) {
        $statusModel = new statusModel();
        $emptyStatusId = $statusModel->getIdByCode('stall', 'empty');

        $sql = "UPDATE stalls 
                SET stall_area_id = :stall_area_id, stall_code = :stall_code, 
                    stall_type_id = :stall_type_id, stall_area_size = :stall_area_size, stall_base_price = :stall_base_price, stall_status_id = :stall_status_id,
                    stall_map_coordinate_x = :stall_map_coordinate_x, stall_map_coordinate_y = :stall_map_coordinate_y
                WHERE stall_id = :stall_id";
        
        $params = [
            'stall_id'            => $stall_id,
            'stall_area_id'       => $data['stall_area_id'],
            'stall_code'    => $data['stall_code'],
            'stall_type_id' => $data['stall_type_id'],
            'stall_area_size'     => $data['stall_area_size'],
            'stall_base_price'    => $data['stall_base_price'],
            'stall_status_id'     => $data['stall_status_id'] ?: $emptyStatusId,
            'stall_map_coordinate_x' => $data['stall_map_coordinate_x'] ?? null,
            'stall_map_coordinate_y' => $data['stall_map_coordinate_y'] ?? null
        ];

        return $this->db->query($sql, $params);
    }

    public function updateStatus($stall_id, $status) {
        if (is_numeric($status)) {
            $statusId = $status;
        } else {
            $statusModel = new statusModel();
            $statusId = $statusModel->getIdByCode('stall', $status);
        }
        $sql = "UPDATE stalls SET stall_status_id = :stall_status_id WHERE stall_id = :stall_id";
        return $this->db->query($sql, ['stall_id' => $stall_id, 'stall_status_id' => $statusId]);
    }

    public function delete($stall_id) {
        $sql = "DELETE FROM stalls WHERE stall_id = :stall_id";
        return $this->db->query($sql, ['stall_id' => $stall_id]);
    }

    /**
     * Kiểm tra xem sạp có hợp đồng nào đang hoạt động không
     */
    public function hasActiveContract($stallId) {
        $sql = "SELECT COUNT(*) as count FROM contracts WHERE contract_stall_id = :contract_stall_id AND contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')";
        $res = $this->db->selectOne($sql, ['contract_stall_id' => $stallId]);
        return ($res['count'] ?? 0) > 0;
    }

    /**
     * Kiểm tra xem mã sạp đã tồn tại chưa
     */
    public function isStallCodeExists($code, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM stalls WHERE stall_code = :code";
        $params = ['code' => $code];
        if ($excludeId !== null) {
            $sql .= " AND stall_id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        $res = $this->db->selectOne($sql, $params);
        return ($res['count'] ?? 0) > 0;
    }

    /**
     * Lấy danh sách các trạng thái của sạp
     */
    public function getStallStatuses() {
        $sql = "SELECT * FROM system_statuses WHERE status_domain = 'stall'";
        return $this->db->select($sql);
    }

    /**
     * Lấy danh sách sạp khả dụng để chuyển đổi
     */
    public function getAvailableStallsForTransfer($excludeId = null) {
        $sql = "SELECT s.stall_id, s.stall_code, a.area_name, ss.status_name, ss.status_code AS status_code,
                       t.trader_fullname AS trader_name
                FROM stalls s
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN contracts c ON c.contract_stall_id = s.stall_id AND c.contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'draft')
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                WHERE s.stall_id NOT IN (
                    SELECT DISTINCT contract_stall_id 
                    FROM contracts 
                    WHERE contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                )";
        
        $params = [];
        if ($excludeId !== null) {
            $sql .= " AND s.stall_id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }

        $sql .= " ORDER BY a.area_name ASC, s.stall_code ASC";
        return $this->db->select($sql, $params);
    }

    /**
     * Thực hiện chuyển đổi hoặc tráo đổi sạp giữa các tiểu thương
     * Trả về string thông báo kết quả
     */
    public function transferStall($currentStall, $newStall, $contract1) {
        try {
            $this->db->beginTransaction();

            $statusModel = new statusModel();
            $emptyStatusId = $statusModel->getIdByCode('stall', 'empty');
            $rentedStatusId = $statusModel->getIdByCode('stall', 'rented');

            $currentStallId = $currentStall['stall_id'];
            $newStallId = $newStall['stall_id'];

            // Kiểm tra trạng thái của sạp mới
            if ($newStall['status'] === 'empty') {
                // Trường hợp 1: Chuyển sang sạp trống (Đơn phương)
                $sqlUpdateContract = "UPDATE contracts SET contract_stall_id = :new_stall_id WHERE contract_id = :contract_id";
                $this->db->query($sqlUpdateContract, [
                    'new_stall_id' => $newStallId,
                    'contract_id'  => $contract1['contract_id']
                ]);

                $this->updateStatus($currentStallId, $emptyStatusId);
                $this->updateStatus($newStallId, $rentedStatusId);
                $message = 'Chuyển đổi sạp thành công!';
            } else {
                // Trường hợp 2: Đổi sạp giữa 2 tiểu thương (Cả hai sạp đều đang ở trạng thái Khởi tạo)
                $sqlContract2 = "SELECT * FROM contracts WHERE contract_stall_id = :stall_id AND contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'draft') LIMIT 1";
                $contract2 = $this->db->selectOne($sqlContract2, ['stall_id' => $newStallId]);
                
                if (!$contract2) {
                    // Nếu sạp mới không có hợp đồng hoạt động/nháp nhưng trạng thái khác empty, vẫn cho phép chuyển đơn phương
                    $sqlUpdateContract = "UPDATE contracts SET contract_stall_id = :new_stall_id WHERE contract_id = :contract_id";
                    $this->db->query($sqlUpdateContract, [
                        'new_stall_id' => $newStallId,
                        'contract_id'  => $contract1['contract_id']
                    ]);
                    $this->updateStatus($currentStallId, $emptyStatusId);
                    $this->updateStatus($newStallId, $rentedStatusId);
                    $message = 'Chuyển đổi sạp sang sạp mới thành công!';
                } else {
                    // Thực hiện tráo đổi (swap) contract_stall_id của 2 hợp đồng khởi tạo
                    $sqlUpdateContract1 = "UPDATE contracts SET contract_stall_id = :new_stall_id WHERE contract_id = :contract_id";
                    $this->db->query($sqlUpdateContract1, [
                        'new_stall_id' => $newStallId,
                        'contract_id'  => $contract1['contract_id']
                    ]);

                    $sqlUpdateContract2 = "UPDATE contracts SET contract_stall_id = :new_stall_id WHERE contract_id = :contract_id";
                    $this->db->query($sqlUpdateContract2, [
                        'new_stall_id' => $currentStallId,
                        'contract_id'  => $contract2['contract_id']
                    ]);

                    // Trạng thái của cả 2 sạp giữ nguyên là 'rented' (đã thuê)
                    $this->updateStatus($currentStallId, $rentedStatusId);
                    $this->updateStatus($newStallId, $rentedStatusId);
                    $message = 'Tráo đổi sạp giữa 2 tiểu thương thành công!';
                }
            }

            $this->db->commit();
            return $message;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Lấy lịch sử thuê sạp
     */
    public function getRentalHistory($stallId) {
        $sql = "SELECT c.*, t.trader_fullname, t.trader_phone, ss.status_name, ss.status_code, sc.color_class
                FROM contracts c
                JOIN traders t ON c.contract_trader_id = t.trader_id
                JOIN system_statuses ss ON c.contract_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                WHERE c.contract_stall_id = :stall_id AND c.contract_status_id != 99
                ORDER BY c.contract_start_date DESC";
        return $this->db->select($sql, ['stall_id' => $stallId]);
    }
}
