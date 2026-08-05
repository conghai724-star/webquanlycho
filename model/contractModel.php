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
        $this->autoUpdateExpiryStatus();
        $sql = "SELECT c.*, t.trader_fullname AS trader_name, t.trader_phone AS trader_phone, t.trader_address AS trader_address,
                       s.stall_code, s.stall_base_price AS price, a.area_name, st.stall_type_name AS stall_type,
                       ss.status_code AS status_code, ss.status_name, sc.color_class,
                       DATEDIFF(c.contract_end_date, CURRENT_DATE) AS days_remaining
                FROM contracts c
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                LEFT JOIN stalls s ON c.contract_stall_id = s.stall_id
                LEFT JOIN stall_types st ON s.stall_type_id = st.stall_type_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN system_statuses ss ON c.contract_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                 WHERE c.contract_status_id != 99 AND (t.trader_id IS NULL OR t.trader_status_id != 99)";
        
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
        $sql = "SELECT c.*, t.trader_fullname AS trader_name, t.trader_phone AS trader_phone, t.trader_cccd AS trader_cccd, t.trader_address AS trader_address,
                       s.stall_code, s.stall_type_id, s.stall_area_size, s.stall_base_price AS price, s.stall_map_coordinate_x, s.stall_map_coordinate_y, a.area_name, a.area_market_id, st.stall_type_name AS stall_type,
                       ss.status_code AS status_code, ss.status_name, sc.color_class,
                       DATEDIFF(c.contract_end_date, CURRENT_DATE) AS days_remaining
                FROM contracts c
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                LEFT JOIN stalls s ON c.contract_stall_id = s.stall_id
                LEFT JOIN stall_types st ON s.stall_type_id = st.stall_type_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN system_statuses ss ON c.contract_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                 WHERE c.contract_id = :contract_id AND c.contract_status_id != 99 AND (t.trader_id IS NULL OR t.trader_status_id != 99)";
        return $this->db->selectOne($sql, ['contract_id' => $contract_id]);
    }

    /**
     * Tạo hợp đồng mới
     */
    public function create($data) {
        $statusModel = new statusModel();
        $activeStatusId = $statusModel->getIdByCode('contract', 'active');

        $sql = "INSERT INTO contracts (contract_trader_id, contract_stall_id, contract_number, contract_name, contract_description, contract_file, contract_sign_date, contract_start_date, contract_end_date, contract_deposit, contract_status_id) 
                VALUES (:contract_trader_id, :contract_stall_id, :contract_number, :contract_name, :contract_description, :contract_file, :contract_sign_date, :contract_start_date, :contract_end_date, :contract_deposit, :contract_status_id)";
        
        $params = [
            'contract_trader_id'       => $data['contract_trader_id'],
            'contract_stall_id'        => $data['contract_stall_id'],
            'contract_number' => $data['contract_number'],
            'contract_name'            => $data['contract_name'],
            'contract_description'     => $data['contract_description'] ?? null,
            'contract_file'   => $data['contract_file'] ?? null,
            'contract_sign_date'       => $data['contract_sign_date'] ?? null,
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

            $this->logHistory($contractId, 'create', $data, 'Lập hợp đồng thuê sạp mới');

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
        $res = $this->db->query($sql, [
            'contract_id' => $contract_id,
            'contract_end_date' => $newEndDate,
            'contract_status_id' => $activeStatusId
        ]);
        if ($res) {
            $this->logHistory($contract_id, 'renew', ['new_end_date' => $newEndDate], 'Gia hạn hợp đồng tới ngày ' . date('d/m/Y', strtotime($newEndDate)));
        }
        return $res;
    }

    /**
     * Thanh lý hợp đồng
     */
    public function liquidate($contract_id) {
        $statusModel = new statusModel();
        $liquidatedStatusId = $statusModel->getIdByCode('contract', 'liquidated') ?: 13;

        // Lấy thông tin hợp đồng trước khi thay đổi trạng thái
        $contract = $this->getById($contract_id);
        if (!$contract) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            // 1. Cập nhật trạng thái hợp đồng thành Thanh lý (liquidated)
            $sql = "UPDATE contracts SET contract_status_id = :contract_status_id WHERE contract_id = :contract_id";
            $this->db->query($sql, ['contract_id' => $contract_id, 'contract_status_id' => $liquidatedStatusId]);

            // 2. Cập nhật sạp về trạng thái empty (trống)
            $stallModel = new stallModel();
            $stallModel->updateStatus($contract['contract_stall_id'], 'empty');

            $this->logHistory($contract_id, 'liquidate', null, 'Thanh lý hợp đồng. Trạng thái sạp chuyển về Trống.');

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
        $terminatedStatusId = $statusModel->getIdByCode('contract', 'terminated') ?: 14;

        // Lấy thông tin hợp đồng trước khi thay đổi trạng thái
        $contract = $this->getById($contract_id);
        if (!$contract) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            // 1. Cập nhật trạng thái hợp đồng thành Chấm dứt trước hạn (terminated)
            $sql = "UPDATE contracts SET contract_status_id = :contract_status_id WHERE contract_id = :contract_id";
            $this->db->query($sql, ['contract_id' => $contract_id, 'contract_status_id' => $terminatedStatusId]);

            // 2. Cập nhật sạp về trạng thái empty (trống)
            $stallModel = new stallModel();
            $stallModel->updateStatus($contract['contract_stall_id'], 'empty');

            $this->logHistory($contract_id, 'terminate', null, 'Chấm dứt hợp đồng trước hạn. Trạng thái sạp chuyển về Trống.');

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
        $deletedStatusId = 99;

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
        $sql = "INSERT INTO contract_appendices (appendix_contract_id, appendix_number, appendix_name, appendix_sign_date, appendix_effect_date, appendix_content, appendix_file) 
                VALUES (:contract_id, :appendix_number, :appendix_name, :sign_date, :effect_date, :content, :file)";
        
        $params = [
            'contract_id'     => $data['contract_id'],
            'appendix_number' => $data['appendix_number'],
            'appendix_name'   => $data['market_name'],
            'sign_date'       => $data['sign_date'],
            'effect_date'     => $data['effect_date'],
            'content'         => $data['content'],
            'file'            => $data['file'] ?? null
        ];

        $res = $this->db->query($sql, $params);
        if ($res) {
            $this->logHistory($data['contract_id'], 'appendix_add', $data, 'Thêm phụ lục hợp đồng số: ' . $data['appendix_number']);
        }
        return $res;
    }

    /**
     * Chỉnh sửa thông tin hợp đồng
     */
    public function updateContractDetails($contractId, $data) {
        $oldContract = $this->getById($contractId);

        $sql = "UPDATE contracts SET 
                    contract_number = :contract_number,
                    contract_name = :contract_name,
                    contract_sign_date = :contract_sign_date,
                    contract_start_date = :contract_start_date,
                    contract_end_date = :contract_end_date,
                    contract_deposit = :contract_deposit,
                    contract_description = :contract_description";
        
        $params = [
            'contract_number'     => $data['contract_number'],
            'contract_name'       => $data['contract_name'],
            'contract_sign_date'  => $data['contract_sign_date'] ?? null,
            'contract_start_date' => $data['contract_start_date'],
            'contract_end_date'   => $data['contract_end_date'],
            'contract_deposit'    => $data['contract_deposit'],
            'contract_description'=> $data['contract_description'],
            'contract_id'         => $contractId
        ];

        if (array_key_exists('contract_file', $data)) {
            $sql .= ", contract_file = :contract_file";
            $params['contract_file'] = $data['contract_file'];
        }

        $sql .= " WHERE contract_id = :contract_id";

        $res = $this->db->query($sql, $params);
        if ($res && $oldContract) {
            // So sánh các giá trị trước và sau khi chỉnh sửa
            $fieldsToCompare = [
                'contract_number'      => 'Số hợp đồng',
                'contract_name'        => 'Tên hợp đồng',
                'contract_sign_date'   => 'Ngày lập hợp đồng',
                'contract_start_date'  => 'Ngày bắt đầu',
                'contract_end_date'    => 'Ngày kết thúc',
                'contract_deposit'     => 'Tiền đặt cọc',
                'contract_description' => 'Mô tả/Ghi chú',
                'contract_file'        => 'File đính kèm'
            ];
            
            $diff = [];
            foreach ($fieldsToCompare as $field => $label) {
                $oldVal = $oldContract[$field] ?? '';
                $newVal = $data[$field] ?? ($oldContract[$field] ?? '');
                
                if ($field === 'contract_file' && !array_key_exists('contract_file', $data)) {
                    continue; // Không cập nhật file thì bỏ qua so sánh file
                }
                
                if ($field === 'contract_deposit') {
                    if ((float)$oldVal !== (float)$newVal) {
                        $diff[$field] = [
                            'label' => $label,
                            'old'   => number_format((float)$oldVal, 0, ',', '.') . ' đ',
                            'new'   => number_format((float)$newVal, 0, ',', '.') . ' đ'
                        ];
                    }
                    continue;
                }
                
                $oldStr = ($oldVal === null) ? '' : trim((string)$oldVal);
                $newStr = ($newVal === null) ? '' : trim((string)$newVal);
                
                if ($oldStr !== $newStr) {
                    $diff[$field] = [
                        'label' => $label,
                        'old'   => $oldStr,
                        'new'   => $newStr
                    ];
                }
            }
            
            $this->logHistory($contractId, 'update', $diff, 'Chỉnh sửa thông tin chi tiết hợp đồng.');
        }
        return $res;
    }

    /**
     * Lấy danh sách trạng thái của hợp đồng
     */
    public function getContractStatuses() {
        $sql = "SELECT * FROM system_statuses WHERE status_domain = 'contract' AND status_id != 99";
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
     * Lấy hợp đồng đang hoạt động hoặc khởi tạo của một sạp
     */
    public function getActiveOrDraftContractByStall($stallId) {
        $sql = "SELECT * FROM contracts 
                WHERE contract_stall_id = :stall_id 
                  AND contract_status_id IN (
                      SELECT status_id 
                      FROM system_statuses 
                      WHERE status_domain = 'contract' 
                        AND status_code IN ('active', 'draft')
                  ) 
                LIMIT 1";
        return $this->db->selectOne($sql, ['stall_id' => $stallId]);
    }

    /**
     * Kiểm tra xem số hợp đồng đã tồn tại chưa
     */
    public function isContractNumberExists($num, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM contracts WHERE contract_number = :num AND contract_status_id != 99";
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

    /**
     * Kích hoạt hợp đồng khởi tạo (cập nhật thông tin và chuyển trạng thái hoạt động)
     */
    public function activateDraftContract($contractId, $data) {
        $statusModel = new statusModel();
        $activeStatusId = $statusModel->getIdByCode('contract', 'active');

        // Cập nhật thông tin hợp đồng
        $sql = "UPDATE contracts 
                SET contract_number = :contract_number, 
                    contract_sign_date = :contract_sign_date,
                    contract_start_date = :contract_start_date, 
                    contract_end_date = :contract_end_date, 
                    contract_deposit = :contract_deposit, 
                    contract_status_id = :contract_status_id";
        
        $params = [
            'contract_id'         => $contractId,
            'contract_number'     => $data['contract_number'],
            'contract_sign_date'  => $data['contract_sign_date'] ?? null,
            'contract_start_date' => $data['contract_start_date'],
            'contract_end_date'   => $data['contract_end_date'],
            'contract_deposit'    => $data['contract_deposit'],
            'contract_status_id'  => $activeStatusId
        ];

        if (array_key_exists('contract_file', $data)) {
            $sql .= ", contract_file = :contract_file";
            $params['contract_file'] = $data['contract_file'];
        }

        $sql .= " WHERE contract_id = :contract_id";

        try {
            $this->db->beginTransaction();
            
            // 1. Cập nhật hợp đồng sang Hoạt động
            $this->db->query($sql, $params);

            // 2. Bảo đảm sạp được đổi trạng thái thành rented (đã thuê)
            $contract = $this->getById($contractId);
            if ($contract) {
                $stallModel = new stallModel();
                $stallModel->updateStatus($contract['contract_stall_id'], 'rented');
            }

            $this->logHistory($contractId, 'activate', $data, 'Kích hoạt hợp đồng thành Hoạt động');

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    /**
     * Tự động cập nhật trạng thái hợp đồng đã hết hạn và đưa hợp đồng về trạng thái Khởi tạo (draft)
     */
    public function autoUpdateExpiryStatus() {
        $today = date('Y-m-d');
        try {
            // Lấy các hợp đồng active đã quá hạn
            $sql = "SELECT contract_id, contract_stall_id FROM contracts 
                    WHERE contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                      AND contract_end_date < :today";
            $expiredContracts = $this->db->select($sql, ['today' => $today]);
            
            if (!empty($expiredContracts)) {
                $statusModel = new statusModel();
                $expiredStatusId = $statusModel->getIdByCode('contract', 'expired') ?: 12;
                $emptyStallStatusId = $statusModel->getIdByCode('stall', 'empty') ?: 3;
                
                $this->db->beginTransaction();
                foreach ($expiredContracts as $c) {
                    // Cập nhật trạng thái hợp đồng thành expired (Hết hạn)
                    $this->db->query("UPDATE contracts SET contract_status_id = :status_id WHERE contract_id = :contract_id", [
                        'status_id' => $expiredStatusId,
                        'contract_id' => $c['contract_id']
                    ]);
                    // Cập nhật trạng thái sạp thành empty (Trống)
                    $this->db->query("UPDATE stalls SET stall_status_id = :status_id WHERE stall_id = :stall_id", [
                        'status_id' => $emptyStallStatusId,
                        'stall_id' => $c['contract_stall_id']
                    ]);
                }
                $this->db->commit();
            }
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('[autoUpdateExpiryStatus] ERROR: ' . $e->getMessage());
        }
    }

    /**
     * Ghi lịch sử chỉnh sửa / thay đổi trạng thái hợp đồng
     */
    public function logHistory($contractId, $action, $changes, $note = null) {
        $userId = session::get('user_id') ?: session::get('user_market_user_id');
        $sql = "INSERT INTO contract_history (history_contract_id, history_action, history_changes, history_note, history_user_id) 
                VALUES (:contract_id, :action, :changes, :note, :user_id)";
        return $this->db->query($sql, [
            'contract_id' => $contractId,
            'action' => $action,
            'changes' => is_array($changes) ? json_encode($changes, JSON_UNESCAPED_UNICODE) : $changes,
            'note' => $note,
            'user_id' => $userId
        ]);
    }

    /**
     * Lấy lịch sử của một hợp đồng
     */
    public function getHistory($contractId) {
        $sql = "SELECT h.*, COALESCE(u.user_fullname, u.user_username) as user_name 
                FROM contract_history h
                LEFT JOIN users u ON h.history_user_id = u.user_id
                WHERE h.history_contract_id = :contract_id
                ORDER BY h.history_id DESC";
        return $this->db->select($sql, ['contract_id' => $contractId]);
    }
    /**
     * Tái kích hoạt hợp đồng (Chuyển sang Khởi tạo - draft)
     */
    public function reactivate($contract_id) {
        $statusModel = new statusModel();
        $draftStatusId = $statusModel->getIdByCode('contract', 'draft') ?: 27;

        $contract = $this->getById($contract_id);
        if (!$contract) {
            return false;
        }

        // Kiểm tra xem sạp hiện tại có sạp nào đang thuê/sử dụng không để tránh chồng lấn
        $stallModel = new stallModel();
        $stall = $stallModel->getById($contract['contract_stall_id']);
        if ($stall && $stall['status'] !== 'empty') {
            throw new Exception("Sạp này hiện tại đã có hợp đồng khác hoặc đang sửa chữa/tạm khóa. Không thể tái kích hoạt!");
        }

        try {
            $this->db->beginTransaction();

            // 1. Cập nhật trạng thái hợp đồng thành Khởi tạo (draft)
            $sql = "UPDATE contracts SET contract_status_id = :contract_status_id WHERE contract_id = :contract_id";
            $this->db->query($sql, ['contract_id' => $contract_id, 'contract_status_id' => $draftStatusId]);

            // 2. Cập nhật sạp về trạng thái rented (đã thuê) vì hợp đồng đã được tái kích hoạt ở dạng khởi tạo
            $stallModel->updateStatus($contract['contract_stall_id'], 'rented');

            $this->logHistory($contract_id, 'reactivate', null, 'Tái kích hoạt hợp đồng, đưa hợp đồng về trạng thái Khởi tạo');

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            throw $e;
        }
    }
}
