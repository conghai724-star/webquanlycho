<?php
/**
 * Model quản lý sơ đồ chợ tương tác (Market Map Elements)
 */
class mapModel {
    private $db;

    public function __construct() {
        $this->db = database::getInstance();
    }

    /**
     * Lấy danh sách toàn bộ phần tử trên bản đồ kèm thông tin liên kết sạp
     */
    public function getElements() {
        $sql = "SELECT mme.*,
                       s.stall_code, s.stall_type, s.stall_area_size, s.stall_base_price,
                       ss.status_code AS status_code, ss.status_name, sc.color_class,
                       a.area_name, a.area_block, a.area_lot,
                       t.trader_fullname AS trader_name, t.trader_phone AS trader_phone,
                       con.contract_number, con.end_date AS contract_end_date
                FROM market_map_elements mme
                LEFT JOIN stalls s ON mme.stall_id = s.stall_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                LEFT JOIN contracts con ON con.stall_id = s.stall_id AND con.contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                LEFT JOIN traders t ON con.trader_id = t.trader_id
                ORDER BY mme.element_id ASC";
        
        return $this->db->select($sql);
    }

    /**
     * Lưu cấu hình bản đồ (Xóa cũ nạp mới trong transaction)
     */
    public function saveElements($elements) {
        try {
            $this->db->beginTransaction();

            // 1. Xóa sạch sơ đồ cũ
            $this->db->query("DELETE FROM market_map_elements");

            // Reset tọa độ cũ trên bảng stalls về NULL
            $this->db->query("UPDATE stalls SET stall_map_coordinate_x = NULL, stall_map_coordinate_y = NULL");

            // 2. Chèn các phần tử sơ đồ mới
            $sqlInsert = "INSERT INTO market_map_elements 
                            (element_type, element_name, element_stall_id, element_pos_x, element_pos_y, element_width, element_height, element_rotation, element_color, element_waypoints, element_stroke_width) 
                          VALUES 
                            (:element_type, :element_name, :stall_id, :pos_x, :pos_y, :width, :height, :rotation, :color, :waypoints, :stroke_width)";

            $sqlUpdateStall = "UPDATE stalls SET stall_map_coordinate_x = :pos_x, stall_map_coordinate_y = :pos_y WHERE stall_id = :stall_id";

            foreach ($elements as $el) {
                $params = [
                    'element_type' => $el['element_type'],
                    'element_name' => $el['element_name'] ?: null,
                    'stall_id'     => !empty($el['stall_id']) ? (int)$el['stall_id'] : null,
                    'pos_x'        => (int)$el['pos_x'],
                    'pos_y'        => (int)$el['pos_y'],
                    'width'        => (int)$el['width'],
                    'height'       => (int)$el['height'],
                    'rotation'     => (int)$el['rotation'],
                    'color'        => $el['color'] ?: null,
                    'waypoints'    => isset($el['waypoints']) ? (is_array($el['waypoints']) ? json_encode($el['waypoints'], JSON_UNESCAPED_UNICODE) : $el['waypoints']) : null,
                    'stroke_width' => isset($el['stroke_width']) ? (int)$el['stroke_width'] : 24,
                ];

                $this->db->query($sqlInsert, $params);

                // Nếu là sạp, đồng bộ tọa độ ngược về bảng stalls
                if ($el['element_type'] === 'stall' && !empty($el['stall_id'])) {
                    $this->db->query($sqlUpdateStall, [
                        'pos_x'    => (int)$el['pos_x'],
                        'pos_y'    => (int)$el['pos_y'],
                        'stall_id' => (int)$el['stall_id']
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Lấy danh sách các sạp chưa được gán vào sơ đồ
     */
    public function getUnmappedStalls() {
        $sql = "SELECT s.stall_id, s.stall_code, s.stall_base_price, s.stall_area_size, ss.status_name, sc.color_class
                FROM stalls s
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                WHERE s.stall_id NOT IN (SELECT element_stall_id FROM market_map_elements WHERE element_stall_id IS NOT NULL)
                  AND ss.status_code != '99'
                ORDER BY s.stall_code ASC";
        
        return $this->db->select($sql);
    }

    /**
     * Lấy cấu trúc cây phân cấp sạp chợ (Khu vực -> Dãy -> Lô -> Sạp)
     */
    public function getStallTree() {
        $sql = "SELECT 
                    a.area_name, a.area_block, a.area_lot,
                    s.stall_id AS stall_id, s.stall_code, s.stall_type_id, st.stall_type_name AS stall_type, s.stall_area_size, s.stall_base_price,
                    ss.status_code AS status_code, ss.status_name, sc.color_class,
                    t.trader_fullname AS trader_name
                FROM stalls s
                JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN stall_types st ON s.stall_type_id = st.stall_type_id
                JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                LEFT JOIN contracts c ON c.contract_stall_id = s.stall_id AND c.contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id";
        
        $marketId = marketService::currentMarketId();
        $sql .= " WHERE a.area_market_id = " . (int)$marketId;
        $sql .= " ORDER BY a.area_name ASC, a.area_block ASC, a.area_lot ASC, s.stall_code ASC";
        
        $rawStalls = $this->db->select($sql);

        $tree = [];
        foreach ($rawStalls as $row) {
            $area = $row['area_name'] ?: 'Khu vực khác';
            $block = $row['area_block'] ?: 'Dãy khác';
            $lot = $row['area_lot'] ?: 'Lô khác';

            if (!isset($tree[$area])) {
                $tree[$area] = [];
            }
            if (!isset($tree[$area][$block])) {
                $tree[$area][$block] = [];
            }
            if (!isset($tree[$area][$block][$lot])) {
                $tree[$area][$block][$lot] = [];
            }

            $tree[$area][$block][$lot][] = [
                'stall_id' => (int)$row['stall_id'],
                'code' => $row['stall_code'],
                'type' => $row['stall_type'] ?: 'Quầy hàng',
                'size' => (float)$row['stall_area_size'],
                'price' => (float)$row['stall_base_price'],
                'status_code' => $row['status_code'],
                'status_name' => $row['status_name'],
                'color_class' => $row['color_class'],
                'trader_name' => $row['trader_name'] ?: ''
            ];
        }

        return $tree;
    }

    /**
     * Lấy thông tin chi tiết đầy đủ của sạp, hợp đồng và tiểu thương đang thuê
     */
    public function getFullDetails($stallId) {
        $sql = "SELECT s.*, st.stall_type_name AS stall_type, ss.status_code AS status, ss.status_name, sc.color_class, 
                       a.area_name, a.area_block, a.area_lot, a.area_market_id,
                       c.contract_id AS contract_id, c.contract_number, c.contract_start_date, c.contract_end_date, c.contract_deposit, c.contract_file,
                       t.trader_id AS trader_id, t.trader_code, t.trader_fullname AS trader_fullname, t.trader_phone AS trader_phone, 
                       t.trader_cccd AS trader_cccd, t.trader_address AS trader_address,
                       bl.line_name AS business_line_name
                FROM stalls s 
                LEFT JOIN stall_types st ON s.stall_type_id = st.stall_type_id
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id 
                LEFT JOIN contracts c ON c.contract_stall_id = s.stall_id AND c.contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                LEFT JOIN business_lines bl ON t.trader_business_line_id = bl.line_id
                WHERE s.stall_id = :id";
        
        $marketId = marketService::currentMarketId();
        $sql .= " AND a.area_market_id = " . (int)$marketId;
        return $this->db->selectOne($sql, ['id' => $stallId]);
    }
}
