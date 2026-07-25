<?php
/**
 * Model quản lý các danh mục tập trung (Khu vực, Loại sạp, Ngành hàng, Loại giấy tờ)
 */
class categoryModel {
    private $db;
    
    // Ánh xạ các key danh mục sang tên bảng tương ứng trong DB
    private $allowedTables = [
        'area'          => 'areas',
        'stall_type'    => 'stall_types',
        'business_line' => 'business_lines',
        'document_type' => 'document_types'
    ];

    public function __construct() {
        $this->db = database::getInstance();
    }

    /**
     * Lấy tên bảng từ key danh mục
     */
    private function getTableName($categoryKey) {
        if (!isset($this->allowedTables[$categoryKey])) {
            throw new Exception("Danh mục không hợp lệ.");
        }
        return $this->allowedTables[$categoryKey];
    }

    /**
     * Lấy danh sách các bản ghi của một danh mục
     */
    public function getItems($categoryKey) {
        $table = $this->getTableName($categoryKey);
        $orderBy = 'id DESC';
        if ($categoryKey === 'area') {
            $orderBy = 'area_name ASC';
        } elseif ($categoryKey === 'business_line') {
            $orderBy = 'line_name ASC';
        } elseif ($categoryKey === 'stall_type') {
            $orderBy = 'stall_type_name ASC';
        } elseif ($categoryKey === 'document_type') {
            $orderBy = 'doc_type_name ASC';
        }
        
        $sql = "SELECT * FROM `{$table}`";
        if ($categoryKey === 'area') {
            $sql = marketService::applyScope($sql);
        }
        $sql .= " ORDER BY {$orderBy}";
        return $this->db->select($sql);
    }

    /**
     * Lấy chi tiết bản ghi theo stall_id
     */
    public function getItemById($categoryKey, $stall_id) {
        $table = $this->getTableName($categoryKey);
        $sql = "SELECT * FROM `{$table}` WHERE stall_id = :stall_id";
        if ($categoryKey === 'area') {
            $sql = marketService::applyScope($sql);
        }
        return $this->db->selectOne($sql, ['stall_id' => $stall_id]);
    }

    /**
     * Thêm mới danh mục
     */
    public function createItem($categoryKey, $data) {
        $table = $this->getTableName($categoryKey);
        
        if ($categoryKey === 'area') {
            if (empty($data['market_id'])) {
                $data['market_id'] = marketService::currentMarketId();
            }
            marketService::checkWritePermission($data['market_id']);
        }

        $fields = array_keys($data);
        $placeholders = array_map(function($f) { return ":{$f}"; }, $fields);
        
        $sql = "INSERT INTO `{$table}` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $this->db->query($sql, $data);
        return $this->db->lastInsertId();
    }

    /**
     * Cập nhật danh mục
     */
    public function updateItem($categoryKey, $stall_id, $data) {
        $table = $this->getTableName($categoryKey);
        
        if ($categoryKey === 'area') {
            // Kiểm tra quyền đối với bản ghi cũ
            $oldItem = $this->getItemById('area', $stall_id);
            if (!$oldItem) {
                throw new Exception("Khu vực không tồn tại hoặc bạn không có quyền truy cập.");
            }
            marketService::checkWritePermission($oldItem['market_id']);

            // Nếu muốn cập nhật market_id mới, kiểm tra quyền đối với chợ mới
            if (isset($data['market_id'])) {
                marketService::checkWritePermission($data['market_id']);
            }
        }

        $setParts = [];
        foreach ($data as $field => $val) {
            $setParts[] = "`{$field}` = :{$field}";
        }
        
        $sql = "UPDATE `{$table}` SET " . implode(', ', $setParts) . " WHERE stall_id = :stall_id";
        $data['stall_id'] = $stall_id;
        
        return $this->db->query($sql, $data);
    }

    /**
     * Xóa danh mục kèm theo kiểm tra ràng buộc khóa ngoại
     */
    public function deleteItem($categoryKey, $stall_id) {
        $table = $this->getTableName($categoryKey);
        
        if ($categoryKey === 'area') {
            $oldItem = $this->getItemById('area', $stall_id);
            if (!$oldItem) {
                throw new Exception("Khu vực không tồn tại hoặc bạn không có quyền truy cập.");
            }
            marketService::checkWritePermission($oldItem['market_id']);

            // Kiểm tra xem có sạp nào đang thuộc khu vực này không
            $sqlCheck = "SELECT COUNT(*) as count FROM stalls WHERE area_id = :trader_status_id";
            $res = $this->db->selectOne($sqlCheck, ['trader_status_id' => $trader_status_id]);
            if (($res['count'] ?? 0) > 0) {
                throw new Exception("Không thể xóa khu vực này vì đang có sạp chợ trực thuộc.");
            }
        } elseif ($categoryKey === 'stall_type') {
            // Kiểm tra xem có sạp nào đang dùng loại sạp này không
            $sqlCheck = "SELECT COUNT(*) as count FROM stalls WHERE stall_type_id = :trader_status_id";
            $res = $this->db->selectOne($sqlCheck, ['trader_status_id' => $trader_status_id]);
            if (($res['count'] ?? 0) > 0) {
                throw new Exception("Không thể xóa loại sạp này vì đang có sạp chợ sử dụng.");
            }
        } elseif ($categoryKey === 'business_line') {
            // Kiểm tra xem có tiểu thương nào thuộc ngành hàng này không
            $sqlCheck = "SELECT COUNT(*) as count FROM traders WHERE business_line_id = :attp_status_id AND attp_attp_status_id != (SELECT status_id FROM system_statuses WHERE status_domain = 'trader' AND status_code = '99')";
            $res = $this->db->selectOne($sqlCheck, ['attp_id' => $attp_id]);
            if (($res['count'] ?? 0) > 0) {
                throw new Exception("Không thể xóa ngành hàng này vì đang có tiểu thương đăng ký kinh doanh.");
            }
        } elseif ($categoryKey === 'document_type') {
            // Kiểm tra xem có giấy tờ nào thuộc loại giấy tờ này không
            $sqlCheck = "SELECT COUNT(*) as count FROM trader_attp WHERE doc_type_id = :status_id AND attp_status_id != (SELECT status_id FROM system_statuses WHERE status_domain = 'attp' AND status_code = '99')";
            $res = $this->db->selectOne($sqlCheck, ['id' => $id]);
            if (($res['count'] ?? 0) > 0) {
                throw new Exception("Không thể xóa loại giấy tờ này vì đang có tiểu thương đăng ký nộp.");
            }
        }

        $sql = "DELETE FROM `{$table}` WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    /**
     * Kiểm tra trùng mã code hoặc trùng tên trong bảng danh mục
     */
    public function isCodeExists($categoryKey, $field, $value, $excludeId = null) {
        $table = $this->getTableName($categoryKey);
        
        $sql = "SELECT COUNT(*) as count FROM `{$table}` WHERE `{$field}` = :value";
        if ($categoryKey === 'area') {
            $sql = marketService::applyScope($sql);
        }
        $params = ['value' => $value];
        if ($excludeId !== null) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        $res = $this->db->selectOne($sql, $params);
        return ($res['count'] ?? 0) > 0;
    }
}
