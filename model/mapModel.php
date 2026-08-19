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
     * Lấy danh sách toàn bộ phần tử trên bản đồ kèm thông tin liên kết sạp theo chợ (marketId = 0 lấy tất cả chợ)
     */
    public function getElements($marketId = 0) {
        $marketId = (int)$marketId;

        $whereClause = "";
        $params = [];
        if ($marketId > 0) {
            $whereClause = " WHERE (mme.element_market_id = :mid OR (mme.element_market_id IS NULL AND a.area_market_id = :mid)) ";
            $params['mid'] = $marketId;
        }

        $sql = "SELECT mme.*,
                       COALESCE(a.area_market_id, mme.element_market_id, m.market_id) AS element_market_id,
                       COALESCE(a.area_market_id, mme.element_market_id, m.market_id) AS market_id,
                       m.market_name,
                       s.stall_code, st.stall_type_name AS stall_type, s.stall_area_size, s.stall_base_price,
                       ss.status_code AS status_code, ss.status_name, sc.color_class,
                       a.area_name, a.area_block, a.area_lot, a.area_description,
                       t.trader_fullname AS trader_name, t.trader_phone AS trader_phone,
                       con.contract_number, con.contract_end_date,
                       bl.line_name AS business_line_name
                FROM market_map_elements mme
                LEFT JOIN stalls s ON mme.element_stall_id = s.stall_id
                LEFT JOIN stall_types st ON s.stall_type_id = st.stall_type_id
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN markets m ON (COALESCE(a.area_market_id, mme.element_market_id) = m.market_id)
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                LEFT JOIN contracts con ON con.contract_stall_id = s.stall_id AND con.contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                LEFT JOIN traders t ON con.contract_trader_id = t.trader_id
                LEFT JOIN business_lines bl ON t.trader_business_line_id = bl.line_id
                " . $whereClause . "
                ORDER BY mme.element_id ASC";
        
        $elements = $this->db->select($sql, $params);

        // Áp dụng cấu hình quyền riêng tư từ website_settings
        include_once __SITE_PATH . '/model/syncService.php';
        $privacy = syncService::getSettings();
        $hideTrader = ($privacy['hide_trader_name'] ?? '0') === '1';
        $hidePrice  = ($privacy['hide_stall_price'] ?? '0') === '1';

        if ($hideTrader || $hidePrice) {
            foreach ($elements as &$el) {
                if ($hideTrader && !empty($el['trader_name'])) {
                    $el['trader_name'] = 'Đang kinh doanh';
                }
                if ($hidePrice) {
                    $el['stall_base_price'] = 0;
                    $el['price_hidden'] = true;
                }
            }
            unset($el);
        }

        return $elements;
    }

    /**
     * Lưu toàn bộ phần tử sơ đồ chợ theo market_id
     */
    public function saveElements($elements = [], $marketId = 0) {
        if (is_numeric($elements) && is_array($marketId)) {
            $tmp = $elements;
            $elements = $marketId;
            $marketId = $tmp;
        }
        $marketId = (int)$marketId;

        try {
            $this->db->beginTransaction();

            // Thu thập danh sách sạp trong sơ đồ mới
            $incomingStallIds = [];
            foreach ($elements as $el) {
                if (!empty($el['stall_id'])) {
                    $incomingStallIds[] = (int)$el['stall_id'];
                }
            }

            if ($marketId === 0) {
                // Xóa toàn bộ sơ đồ cũ khi lưu toàn cục
                $this->db->query("DELETE FROM market_map_elements");
                if (!empty($incomingStallIds)) {
                    $inStallsStr = implode(',', array_unique($incomingStallIds));
                    $this->db->query("
                        UPDATE stalls SET 
                            stall_map_coordinate_x = NULL, 
                            stall_map_coordinate_y = NULL, 
                            stall_latitude = NULL, 
                            stall_longitude = NULL
                        WHERE stall_id NOT IN ($inStallsStr)
                    ");
                } else {
                    $this->db->query("
                        UPDATE stalls SET 
                            stall_map_coordinate_x = NULL, 
                            stall_map_coordinate_y = NULL, 
                            stall_latitude = NULL, 
                            stall_longitude = NULL
                    ");
                }
            } else {
                // 1. Xóa sạch sơ đồ cũ của chợ này
                $this->db->query("DELETE FROM market_map_elements WHERE element_market_id = :mid", ['mid' => $marketId]);

                // Nếu các sạp này từng được map ở bất kỳ đâu, xóa để tránh xung đột
                if (!empty($incomingStallIds)) {
                    $inStallsStr = implode(',', array_unique($incomingStallIds));
                    $this->db->query("DELETE FROM market_map_elements WHERE element_stall_id IN ($inStallsStr)");
                    $this->db->query("
                        UPDATE stalls s 
                        JOIN areas a ON s.stall_area_id = a.area_id 
                        SET s.stall_map_coordinate_x = NULL, s.stall_map_coordinate_y = NULL, s.stall_latitude = NULL, s.stall_longitude = NULL
                        WHERE a.area_market_id = :mid AND s.stall_id NOT IN ($inStallsStr)
                    ", ['mid' => $marketId]);
                } else {
                    $this->db->query("
                        UPDATE stalls s 
                        JOIN areas a ON s.stall_area_id = a.area_id 
                        SET s.stall_map_coordinate_x = NULL, s.stall_map_coordinate_y = NULL, s.stall_latitude = NULL, s.stall_longitude = NULL
                        WHERE a.area_market_id = :mid
                    ", ['mid' => $marketId]);
                }
            }

            // 2. Chèn các phần tử sơ đồ mới
            $sqlInsert = "INSERT INTO market_map_elements 
                            (element_market_id, element_type, element_name, element_stall_id, element_pos_x, element_pos_y, element_width, element_height, element_rotation, element_color, element_waypoints, element_stroke_width, element_latitude, element_longitude, element_width_m, element_length_m, element_coords_gps) 
                          VALUES 
                            (:market_id, :element_type, :element_name, :stall_id, :pos_x, :pos_y, :width, :height, :rotation, :color, :waypoints, :stroke_width, :latitude, :longitude, :width_m, :length_m, :coords_gps)";

            $sqlUpdateStall = "UPDATE stalls SET 
                                stall_map_coordinate_x = :pos_x, 
                                stall_map_coordinate_y = :pos_y,
                                stall_latitude = :latitude,
                                stall_longitude = :longitude
                               WHERE stall_id = :stall_id";

            $seenStallIds = [];
            foreach ($elements as $el) {
                $rawStallId = !empty($el['stall_id']) ? (int)$el['stall_id'] : null;
                // Tránh gán 1 sạp_id cho nhiều hình khối khác nhau trên canvas
                if ($rawStallId !== null) {
                    if (in_array($rawStallId, $seenStallIds)) {
                        $rawStallId = null; // nếu bị duplicate trong payload, chỉ gán cho hình đầu tiên
                    } else {
                        $seenStallIds[] = $rawStallId;
                    }
                }

                // Tự động tìm đúng Chợ thực tế của sạp (tránh tình trạng đứng ở Chợ 1 gán sạp Chợ 2 bị lệch)
                $elementMarketId = $marketId;
                if ($rawStallId !== null) {
                    $stallArea = $this->db->select("
                        SELECT a.area_market_id 
                        FROM stalls s 
                        JOIN areas a ON s.stall_area_id = a.area_id 
                        WHERE s.stall_id = :sid 
                        LIMIT 1
                    ", ['sid' => $rawStallId]);
                    if (!empty($stallArea) && !empty($stallArea[0]['area_market_id'])) {
                        $elementMarketId = (int)$stallArea[0]['area_market_id'];
                    }
                } elseif (!empty($el['market_id'])) {
                    $elementMarketId = (int)$el['market_id'];
                }

                $params = [
                    'market_id'    => $elementMarketId,
                    'element_type' => $el['element_type'] ?? null,
                    'element_name' => ($el['element_name'] ?? null) ?: null,
                    'stall_id'     => $rawStallId,
                    'pos_x'        => (int)($el['pos_x'] ?? 0),
                    'pos_y'        => (int)($el['pos_y'] ?? 0),
                    'width'        => (int)($el['width'] ?? 40),
                    'height'       => (int)($el['height'] ?? 40),
                    'rotation'     => (int)($el['rotation'] ?? 0),
                    'color'        => $el['color'] ?? null,
                    'waypoints'    => isset($el['waypoints']) ? (is_array($el['waypoints']) ? json_encode($el['waypoints'], JSON_UNESCAPED_UNICODE) : $el['waypoints']) : null,
                    'stroke_width' => isset($el['stroke_width']) ? (int)$el['stroke_width'] : 24,
                    'latitude'     => isset($el['latitude']) && $el['latitude'] !== '' && $el['latitude'] !== null ? (float)$el['latitude'] : null,
                    'longitude'    => isset($el['longitude']) && $el['longitude'] !== '' && $el['longitude'] !== null ? (float)$el['longitude'] : null,
                    'width_m'      => isset($el['width_m']) && $el['width_m'] !== '' && $el['width_m'] !== null ? (float)$el['width_m'] : null,
                    'length_m'     => isset($el['length_m']) && $el['length_m'] !== '' && $el['length_m'] !== null ? (float)$el['length_m'] : null,
                    'coords_gps'   => isset($el['coords_gps']) ? (is_array($el['coords_gps']) ? json_encode($el['coords_gps'], JSON_UNESCAPED_UNICODE) : $el['coords_gps']) : null,
                ];

                $this->db->query($sqlInsert, $params);

                // Nếu là sạp, đồng bộ tọa độ ngược về bảng stalls
                if (($el['element_type'] ?? '') === 'stall' && !empty($rawStallId)) {
                    $this->db->query($sqlUpdateStall, [
                        'pos_x'    => (int)($el['pos_x'] ?? 0),
                        'pos_y'    => (int)($el['pos_y'] ?? 0),
                        'latitude' => isset($el['latitude']) && $el['latitude'] !== '' && $el['latitude'] !== null ? (float)$el['latitude'] : null,
                        'longitude'=> isset($el['longitude']) && $el['longitude'] !== '' && $el['longitude'] !== null ? (float)$el['longitude'] : null,
                        'stall_id' => $rawStallId
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
     * Lấy danh sách các sạp chưa được gán vào sơ đồ của chợ
     */
     public function getUnmappedStalls($marketId = 0) {
         $marketId = (int)$marketId;
         if ($marketId <= 0) {
             $marketId = (int)marketService::currentMarketId();
         }

         $sql = "SELECT s.stall_id, s.stall_code, s.stall_base_price, s.stall_area_size, ss.status_name, sc.color_class,
                        a.area_name, t.trader_fullname AS trader_name
                 FROM stalls s
                 LEFT JOIN areas a ON s.stall_area_id = a.area_id
                 LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                 LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                 LEFT JOIN contracts con ON con.contract_stall_id = s.stall_id AND con.contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                 LEFT JOIN traders t ON con.contract_trader_id = t.trader_id
                 WHERE s.stall_id NOT IN (SELECT element_stall_id FROM market_map_elements WHERE element_stall_id IS NOT NULL AND element_market_id = :mid)
                   AND ss.status_code != '99'
                   AND a.area_market_id = :mid
                 ORDER BY s.stall_code ASC";
         
         return $this->db->select($sql, ['mid' => $marketId]);
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
                'price' => $hidePrice ? 0 : (float)$row['stall_base_price'],
                'price_formatted' => $hidePrice ? 'Liên hệ BQL' : number_format((float)$row['stall_base_price'], 0, ',', '.') . ' đ',
                'status_code' => $row['status_code'],
                'status_name' => $row['status_name'],
                'color_class' => $row['color_class'],
                'trader_name' => ($hideTrader && !empty($row['trader_name'])) ? 'Đang kinh doanh' : ($row['trader_name'] ?: '')
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
        $details = $this->db->selectOne($sql, ['id' => $stallId]);

        if ($details) {
            include_once __SITE_PATH . '/model/syncService.php';
            $privacy = syncService::getSettings();
            if (($privacy['hide_trader_name'] ?? '0') === '1') {
                if (!empty($details['trader_fullname'])) {
                    $details['trader_fullname'] = 'Đang kinh doanh';
                }
            }
            if (($privacy['hide_stall_price'] ?? '0') === '1') {
                $details['stall_base_price'] = 'Liên hệ BQL';
                $details['price_formatted'] = 'Liên hệ BQL';
            }
        }

        return $details;
    }
}
