<?php
/**
 * Controller xử lý các trang quản trị hệ thống, tài khoản, phân quyền và quản lý chợ
 */
Class systemController extends baseController {

    // =========================================================================
    // 1. KHỞI TẠO & TRANG TỔNG QUAN (CORE & DASHBOARD)
    // =========================================================================

    public function __construct($registry) {
        parent::__construct($registry);

        $action = isset($this->registry->router->action) ? $this->registry->router->action : '';

        // Cho phép admin cấp 3 truy cập trang nhật ký hoạt động của bản thân
        $allowedActors = ['super_market', 'admin_market'];
        if ($action === 'logs') {
            $allowedActors[] = 'admin';
        }

        // Bảo vệ toàn bộ các action trong systemController
        if (!$this->helper->isLoggedIn() || !in_array($this->helper->get('actor_code'), $allowedActors)) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }

        // Chỉ cho phép super_market (Super Admin) quản lý chợ
        if (strpos($action, 'market') === 0) {
            if (!$this->helper->isSuperAdmin()) {
                header('Location: ' . BASE_URL . 'system/users');
                exit();
            }
        }
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $db = database::getInstance();
        $accMarkets = marketService::getAccessibleMarketIds();

        if (empty($accMarkets)) {
            $stats = [
                'total_markets' => 0,
                'total_stalls' => 0,
                'rented_stalls' => 0,
                'occupancy_rate' => 0,
                'total_revenue' => 0,
                'inactive_users' => 0
            ];
            $marketsList = [];
        } else {
            $marketIdsStr = implode(',', $accMarkets);

            // 1. Số lượng chợ
            $totalMarkets = count($accMarkets);

            // 2. Thống kê sạp
            $stallStats = $db->selectOne("
                SELECT COUNT(*) as total_stalls,
                       SUM(CASE WHEN ss.status_code = 'rented' THEN 1 ELSE 0 END) as rented_stalls
                FROM stalls s
                JOIN areas a ON s.stall_area_id = a.area_id
                JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                WHERE a.area_market_id IN ($marketIdsStr) AND s.stall_status_id != 99
            ");
            $totalStalls = (int)($stallStats['total_stalls'] ?? 0);
            $rentedStalls = (int)($stallStats['rented_stalls'] ?? 0);
            $occupancyRate = $totalStalls > 0 ? round(($rentedStalls / $totalStalls) * 100) : 0;

            // 3. Thống kê doanh thu tháng này
            $revenueStats = $db->selectOne("
                SELECT SUM(b.bill_total_amount) as total_revenue
                FROM bills b
                JOIN contracts c ON b.bill_contract_id = c.contract_id
                JOIN stalls s ON c.contract_stall_id = s.stall_id
                JOIN areas a ON s.stall_area_id = a.area_id
                WHERE a.area_market_id IN ($marketIdsStr)
                  AND b.bill_status = 'paid'
                  AND DATE_FORMAT(b.bill_invoice_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')
            ");
            $totalRevenue = (float)($revenueStats['total_revenue'] ?? 0);

            // 4. Số tài khoản không hoạt động
            if (marketService::isSuperAdmin()) {
                $inactiveUsers = (int)($db->selectOne("SELECT COUNT(*) as cnt FROM users WHERE user_is_active = 0")['cnt'] ?? 0);
            } else {
                $managerUserId = $this->helper->get('user_id');
                $inactiveUsers = (int)($db->selectOne("
                    SELECT COUNT(DISTINCT u.user_id) as cnt
                    FROM users u
                    JOIN user_markets um ON u.user_id = um.user_market_user_id
                    WHERE u.user_is_active = 0 
                      AND um.user_market_market_id IN ($marketIdsStr)
                ")['cnt'] ?? 0);
            }

            $stats = [
                'total_markets' => $totalMarkets,
                'total_stalls' => $totalStalls,
                'rented_stalls' => $rentedStalls,
                'occupancy_rate' => $occupancyRate,
                'total_revenue' => $totalRevenue,
                'inactive_users' => $inactiveUsers
            ];

            // 5. Danh sách các chợ với số liệu cụ thể
            $marketsRaw = $db->select("
                SELECT m.market_id AS id, m.market_name AS name, m.market_code AS code,
                       (SELECT COUNT(*) FROM stalls s JOIN areas a ON s.stall_area_id = a.area_id WHERE a.area_market_id = m.market_id AND s.stall_status_id != 99) as total_stalls,
                       (SELECT COUNT(*) FROM stalls s JOIN areas a ON s.stall_area_id = a.area_id JOIN system_statuses ss ON s.stall_status_id = ss.status_id WHERE a.area_market_id = m.market_id AND ss.status_code = 'rented' AND s.stall_status_id != 99) as rented_stalls,
                       (SELECT SUM(b.bill_total_amount) FROM bills b JOIN contracts c ON b.bill_contract_id = c.contract_id JOIN stalls s ON c.contract_stall_id = s.stall_id JOIN areas a ON s.stall_area_id = a.area_id WHERE a.area_market_id = m.market_id AND b.bill_status = 'paid' AND DATE_FORMAT(b.bill_invoice_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')) as monthly_revenue
                FROM markets m
                WHERE m.market_id IN ($marketIdsStr)
                ORDER BY m.market_name ASC
            ");
            
            $marketsList = [];
            foreach ($marketsRaw as $m) {
                $marketsList[] = [
                    'id' => (int)$m['id'],
                    'name' => $m['name'],
                    'code' => $m['code'],
                    'total_stalls' => (int)$m['total_stalls'],
                    'rented_stalls' => (int)$m['rented_stalls'],
                    'monthly_revenue' => (float)($m['monthly_revenue'] ?? 0)
                ];
            }
        }

        $this->view->app("dashboard/main_dashboard", [
            'title' => 'Trang Tổng Quan Hợp Nhất',
            'stats' => $stats,
            'markets' => $marketsList
        ]);
    }

    // =========================================================================
    // 2. QUẢN LÝ TÀI KHOẢN NGƯỜI DÙNG (USER ACCOUNT MANAGEMENT)
    // =========================================================================

    public function users() {
        $db = database::getInstance();
        $isSuper = $this->helper->isSuperAdmin();

        $search = trim($_GET['q'] ?? '');
        $selectedMarket = trim($_GET['market_id'] ?? '');

        // Lấy danh sách chợ phục vụ cho thẻ select bộ lọc
        if ($isSuper) {
            $marketsList = $db->select("
                SELECT market_id AS id, market_name AS name 
                FROM markets 
                WHERE market_status_code = 'active'
            ");
        } else {
            $managerUserId = $this->helper->get('user_id');
            $marketsList = $db->select("
                SELECT m.market_id AS id, m.market_name AS name 
                FROM user_markets um
                JOIN markets m ON um.user_market_market_id = m.market_id
                WHERE um.user_market_user_id = :manager_id AND m.market_status_code = 'active'
            ", ['manager_id' => $managerUserId]);
        }

        // Tạo câu SQL cơ bản
        $sql = "SELECT DISTINCT u.user_id, u.user_username AS username, u.user_fullname AS fullname, u.user_email AS email, u.user_is_active AS is_active, sa.actor_code, sa.actor_name
                FROM users u
                LEFT JOIN system_actors sa ON u.user_actor_id = sa.actor_id
                LEFT JOIN user_markets um ON u.user_id = um.user_market_user_id
                WHERE 1=1";

        $params = [];

        // Hạn chế quyền nếu là admin_market
        if (!$isSuper) {
            $managerUserId = $this->helper->get('user_id');
            $rows = $db->select("
                SELECT user_market_market_id 
                FROM user_markets 
                WHERE user_market_user_id = :manager_id
            ", ['manager_id' => $managerUserId]);
            $marketIds = array_column($rows, 'user_market_market_id');

            if (empty($marketIds)) {
                $sql .= " AND 1=0"; // Không có quyền xem bất cứ chợ nào
            } else {
                $placeholders = implode(',', array_map(function($i) { return ":manager_m{$i}"; }, range(0, count($marketIds) - 1)));
                foreach ($marketIds as $idx => $mId) {
                    $params["manager_m{$idx}"] = $mId;
                }
                $sql .= " AND um.user_market_market_id IN ($placeholders) AND sa.actor_code = 'admin'";
            }
        }

        // Lọc theo Chợ nếu chọn
        if ($selectedMarket !== '') {
            // Nếu là admin_market, đảm bảo market_id thuộc quyền quản lý
            if (!$isSuper) {
                if (in_array((int)$selectedMarket, $marketIds)) {
                    $sql .= " AND um.user_market_market_id = :selected_market";
                    $params['selected_market'] = $selectedMarket;
                } else {
                    $sql .= " AND 1=0"; // Không được phép lọc chợ ngoài phạm vi
                }
            } else {
                $sql .= " AND um.user_market_market_id = :selected_market";
                $params['selected_market'] = $selectedMarket;
            }
        }

        // Tìm kiếm theo từ khóa
        if ($search !== '') {
            $sql .= " AND (u.user_username LIKE :search OR u.user_fullname LIKE :search OR u.user_email LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $sql .= " ORDER BY u.user_id DESC";
        $users = empty($params) ? $db->select($sql) : $db->select($sql, $params);

        // Phân trang
        $limit = 15;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $totalRecords = count($users);
        $totalPages = ceil($totalRecords / $limit);
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        $offset = ($page - 1) * $limit;
        $paginatedUsers = array_slice($users, $offset, $limit);

        $this->view->app("user/index", [
            'users' => $paginatedUsers,
            'marketsList' => $marketsList,
            'search' => $search,
            'selectedMarket' => $selectedMarket,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ]);
    }

    /**
     * Trang khai báo tài khoản nhân viên mới
     */
    public function user_add() {
        $db = database::getInstance();
        $isSuper = $this->helper->isSuperAdmin();

        if ($isSuper) {
            $marketsList = $db->select("
                SELECT market_id AS id, market_name AS name 
                FROM markets 
                WHERE market_status_code = 'active'
            ");
        } else {
            $managerUserId = $this->helper->get('user_id');
            $marketsList = $db->select("
                SELECT m.market_id AS id, m.market_name AS name 
                FROM user_markets um
                JOIN markets m ON um.user_market_market_id = m.market_id
                WHERE um.user_market_user_id = :manager_id AND m.market_status_code = 'active'
            ", ['manager_id' => $managerUserId]);
        }

        $this->view->app("user/add", ['marketsList' => $marketsList]);
    }

    /**
     * Trang chỉnh sửa tài khoản nhân viên
     */
    public function user_edit($para) {
        $id = is_array($para) ? reset($para) : $para;
        if (!$id) {
            header('Location: ' . BASE_URL . 'system/users');
            exit();
        }

        $db = database::getInstance();
        $user = $db->selectOne("
            SELECT u.*, u.user_email AS email, u.user_fullname AS fullname, u.user_is_active AS is_active, u.user_username AS username, sa.actor_code 
            FROM users u 
            LEFT JOIN system_actors sa ON u.user_actor_id = sa.actor_id 
            WHERE u.user_id = :id
        ", ['id' => $id]);

        if (!$user) {
            header('Location: ' . BASE_URL . 'system/users');
            exit();
        }

        $isSuper = $this->helper->isSuperAdmin();

        if (!$isSuper) {
            // Kiểm tra xem tài khoản này có thuộc các chợ mà manager quản lý hay không
            $managerUserId = $this->helper->get('user_id');
            $isAssociated = $db->selectOne("
                SELECT 1 FROM user_markets 
                WHERE user_market_user_id = :target_id 
                  AND user_market_market_id IN (
                      SELECT user_market_market_id FROM user_markets WHERE user_market_user_id = :manager_id
                  )
            ", ['target_id' => $id, 'manager_id' => $managerUserId]);

            if (!$isAssociated || $user['actor_code'] !== 'admin') {
                header('Location: ' . BASE_URL . 'system/users');
                exit();
            }

            $marketsList = $db->select("
                SELECT m.market_id AS id, m.market_name AS name 
                FROM user_markets um
                JOIN markets m ON um.user_market_market_id = m.market_id
                WHERE um.user_market_user_id = :manager_id AND m.market_status_code = 'active'
            ", ['manager_id' => $managerUserId]);
        } else {
            $marketsList = $db->select("
                SELECT market_id AS id, market_name AS name 
                FROM markets 
                WHERE market_status_code = 'active'
            ");
        }

        $assignedMarketsRows = $db->select("
            SELECT user_market_market_id AS market_id 
            FROM user_markets 
            WHERE user_market_user_id = :id
        ", ['id' => $id]);
        $assignedMarkets = array_column($assignedMarketsRows, 'market_id');

        $this->view->app("user/edit", [
            'user' => $user, 
            'marketsList' => $marketsList, 
            'assignedMarkets' => $assignedMarkets
        ]);
    }

    /**
     * Bật/tắt khóa tài khoản nhân viên (AJAX POST)
     */
    public function user_toggle_status($para) {
        $id = is_array($para) ? reset($para) : $para;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu ID tài khoản.']);
            exit();
        }

        $db = database::getInstance();
        $user = $db->selectOne("
            SELECT u.*, sa.actor_code 
            FROM users u 
            LEFT JOIN system_actors sa ON u.user_actor_id = sa.actor_id 
            WHERE u.user_id = :id
        ", ['id' => $id]);

        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy tài khoản.']);
            exit();
        }

        $isSuper = $this->helper->isSuperAdmin();

        if (!$isSuper) {
            $managerUserId = $this->helper->get('user_id');
            $isAssociated = $db->selectOne("
                SELECT 1 FROM user_markets 
                WHERE user_market_user_id = :target_id 
                  AND user_market_market_id IN (
                      SELECT user_market_market_id FROM user_markets WHERE user_market_user_id = :manager_id
                  )
            ", ['target_id' => $id, 'manager_id' => $managerUserId]);

            if (!$isAssociated || $user['actor_code'] !== 'admin') {
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thao tác trên tài khoản này.']);
                exit();
            }
        }

        $newStatus = $user['user_is_active'] ? 0 : 1;
        $db->query("UPDATE users SET user_is_active = :status WHERE user_id = :id", ['status' => $newStatus, 'id' => $id]);

        $actionWord = ($newStatus === 1) ? "Mở khóa" : "Khóa";
        general::log('toggle_user_status', "{$actionWord} tài khoản nhân viên: {$user['user_username']} (ID: {$id}, Họ tên: {$user['user_fullname']})");

        echo json_encode(['success' => true, 'new_status' => $newStatus]);
        exit();
     }
 
    // =========================================================================
    // 3. QUẢN LÝ PHÂN QUYỀN (PERMISSIONS MANAGEMENT)
    // =========================================================================

    public function permissions() {
        $db = database::getInstance();
        $isSuper = $this->helper->isSuperAdmin();

        $search = trim($_GET['q'] ?? '');
        $selectedMarket = trim($_GET['market_id'] ?? '');

        // Lấy danh sách toàn bộ chợ thuộc phạm vi quản lý để làm bộ lọc dropdown
        if ($isSuper) {
            $filterMarkets = $db->select("
                SELECT market_id, market_name 
                FROM markets 
                WHERE market_status_code = 'active'
            ");
        } else {
            $managerUserId = $this->helper->get('user_id');
            $filterMarkets = $db->select("
                SELECT m.market_id, m.market_name 
                FROM user_markets um
                JOIN markets m ON um.user_market_market_id = m.market_id
                WHERE um.user_market_user_id = :manager_id AND m.market_status_code = 'active'
            ", ['manager_id' => $managerUserId]);
        }

        // Quyết định danh sách chợ sẽ hiển thị trên bảng
        if ($selectedMarket !== '') {
            $selectedMarketId = (int)$selectedMarket;
            $allowedMarketIds = array_column($filterMarkets, 'market_id');
            if (in_array($selectedMarketId, $allowedMarketIds)) {
                $managedMarkets = $db->select("
                    SELECT market_id, market_name 
                    FROM markets 
                    WHERE market_id = :m_id
                ", ['m_id' => $selectedMarketId]);
            } else {
                $managedMarkets = []; // Chọn chợ ngoài phạm vi quản lý
            }
        } else {
            $managedMarkets = $filterMarkets;
        }

        // Lấy danh sách nhân viên có lọc theo từ khóa tìm kiếm q và theo chợ
        $sql = "SELECT DISTINCT u.user_id AS id, u.user_username AS username, u.user_fullname AS fullname, u.user_email AS email
                FROM users u
                JOIN system_actors sa ON u.user_actor_id = sa.actor_id
                JOIN user_markets um ON u.user_id = um.user_market_user_id
                WHERE sa.actor_code = 'admin'";

        $params = [];

        // Hạn chế quyền nếu là admin_market
        if (!$isSuper) {
            $allowedMarketIds = array_column($filterMarkets, 'market_id');
            if (empty($allowedMarketIds)) {
                $sql .= " AND 1=0";
            } else {
                $placeholders = implode(',', array_map(function($i) { return ":manager_m{$i}"; }, range(0, count($allowedMarketIds) - 1)));
                foreach ($allowedMarketIds as $idx => $mId) {
                    $params["manager_m{$idx}"] = $mId;
                }
                $sql .= " AND um.user_market_market_id IN ($placeholders)";
            }
        }

        // Lọc theo chợ (nếu chọn)
        if ($selectedMarket !== '') {
            $sql .= " AND um.user_market_market_id = :selected_market";
            $params['selected_market'] = $selectedMarket;
        }

        // Tìm kiếm theo từ khóa
        if ($search !== '') {
            $sql .= " AND (u.user_username LIKE :search OR u.user_fullname LIKE :search OR u.user_email LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $sql .= " ORDER BY u.user_id DESC";
        $staffList = empty($params) ? $db->select($sql) : $db->select($sql, $params);

        $permissions = [];
        $permsList = $db->select("SELECT permission_user_id, permission_market_id, permission_module_code FROM user_market_permissions");
        foreach ($permsList as $p) {
            $permissions[$p['permission_user_id']][$p['permission_market_id']][$p['permission_module_code']] = 1;
        }

        $this->view->app("user/permissions", [
            'staffList' => $staffList, 
            'managedMarkets' => $managedMarkets, 
            'filterMarkets' => $filterMarkets,
            'permissions' => $permissions,
            'search' => $search,
            'selectedMarket' => $selectedMarket
        ]);
    }

    /**
     * Lưu phân quyền (AJAX POST)
     */
    public function save_permissions() {
        $db = database::getInstance();
        $userId = $_POST['user_id'] ?? '';
        $marketId = $_POST['market_id'] ?? '';
        $module = $_POST['module'] ?? '';
        $active = $_POST['active'] ?? $_POST['checked'] ?? 0;

        if (!$userId || !$marketId || !$module) {
            echo json_encode(['success' => false, 'message' => 'Thiếu tham số.']);
            exit();
        }

        // Kiểm tra quyền của người quản lý hiện tại
        if (!$this->helper->isSuperAdmin()) {
            $managerUserId = $this->helper->get('user_id');
            // Người quản lý phải quản lý market này
            $ownsMarket = $db->selectOne("
                SELECT 1 FROM user_markets 
                WHERE user_market_user_id = :manager_id AND user_market_market_id = :market_id
            ", ['manager_id' => $managerUserId, 'market_id' => $marketId]);
            
            // Nhân viên được phân quyền cũng phải liên kết với market này
            $staffOwnsMarket = $db->selectOne("
                SELECT 1 FROM user_markets 
                WHERE user_market_user_id = :user_id AND user_market_market_id = :market_id
            ", ['user_id' => $userId, 'market_id' => $marketId]);

            if (!$ownsMarket || !$staffOwnsMarket) {
                echo json_encode(['success' => false, 'message' => 'Bạn không có quyền thao tác trên chợ này.']);
                exit();
            }
        }

        if ($active == 1) {
            // Kiểm tra xem đã tồn tại chưa
            $exists = $db->selectOne("
                SELECT 1 FROM user_market_permissions 
                WHERE permission_user_id = :user_id 
                  AND permission_market_id = :market_id 
                  AND permission_module_code = :module
            ", [
                'user_id' => $userId,
                'market_id' => $marketId,
                'module' => $module
            ]);

            if (!$exists) {
                $db->query("
                    INSERT INTO user_market_permissions (permission_user_id, permission_market_id, permission_module_code)
                    VALUES (:user_id, :market_id, :module)
                ", [
                    'user_id' => $userId,
                    'market_id' => $marketId,
                    'module' => $module
                ]);
            }
        } else {
            $db->query("
                DELETE FROM user_market_permissions 
                WHERE permission_user_id = :user_id 
                  AND permission_market_id = :market_id 
                  AND permission_module_code = :module
            ", [
                'user_id' => $userId,
                'market_id' => $marketId,
                'module' => $module
            ]);
        }

        $targetUser = $db->selectOne("SELECT user_username FROM users WHERE user_id = :id", ['id' => $userId]);
        $targetMarket = $db->selectOne("SELECT market_name FROM markets WHERE market_id = :id", ['id' => $marketId]);
        $targetUsername = $targetUser ? $targetUser['user_username'] : "ID {$userId}";
        $targetMarketName = $targetMarket ? $targetMarket['market_name'] : "ID {$marketId}";

        $moduleNames = [
            'trader' => 'Tiểu thương',
            'stall' => 'Sạp chợ',
            'contract' => 'Hợp đồng',
            'finance' => 'Tài chính',
            'foodsafety' => 'An toàn thực phẩm'
        ];
        $friendlyModule = $moduleNames[$module] ?? $module;

        $actionWord = ($active == 1) ? "Gán" : "Thu hồi";
        general::log('update_permissions', "{$actionWord} quyền phân hệ '{$friendlyModule}' cho nhân viên: {$targetUsername} tại chợ: {$targetMarketName}");

        echo json_encode(['success' => true]);
        exit();
    }

    // =========================================================================
    // 4. QUẢN LÝ CHỢ (MARKET MANAGEMENT - ONLY SUPER ADMIN)
    // =========================================================================

    /**
     * Danh sách Chợ trong hệ thống (Chỉ Super Admin)
     */
    public function markets() {
        $db = database::getInstance();
        $search = $_GET['q'] ?? '';

        $sql = "SELECT 
                    market_id AS id, 
                    market_code, 
                    market_name AS name, 
                    market_phone AS phone, 
                    market_email AS email, 
                    market_manager_name AS manager_name, 
                    market_status_code AS status_code
                FROM markets";

        if (!empty($search)) {
            $sql .= " WHERE market_name LIKE :search OR market_code LIKE :search";
            $markets = $db->select($sql, ['search' => "%$search%"]);
        } else {
            $markets = $db->select($sql);
        }

        // Phân trang
        $limit = 15;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $totalRecords = count($markets);
        $totalPages = ceil($totalRecords / $limit);
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        $offset = ($page - 1) * $limit;
        $paginatedMarkets = array_slice($markets, $offset, $limit);

        $this->view->app("market/index", [
            'markets' => $paginatedMarkets,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ]);
    }

    /**
     * Trang thêm Chợ mới (Chỉ Super Admin)
     */
    public function market_add() {
        $this->view->app("market/add");
    }

    /**
     * Trang chỉnh sửa Chợ (Chỉ Super Admin)
     */
    public function market_edit($para) {
        $id = is_array($para) ? reset($para) : $para;
        if (!$id) {
            header('Location: ' . BASE_URL . 'system/markets');
            exit();
        }

        $db = database::getInstance();
        $market = $db->selectOne("
            SELECT *
            FROM markets 
            WHERE market_id = :id
        ", ['id' => $id]);

        if (!$market) {
            header('Location: ' . BASE_URL . 'system/markets');
            exit();
        }

        $this->view->app("market/edit", ['market' => $market]);
    }

    // =========================================================================
    // 5. NHẬT KÝ HOẠT ĐỘNG (SYSTEM ACTIVITY LOGS)
    // =========================================================================

    /**
     * Trang hiển thị Nhật ký hoạt động hệ thống
     */
    public function logs() {
        $db = database::getInstance();
        $isSuper = $this->helper->isSuperAdmin();

        $search = trim($_GET['q'] ?? '');
        $actionType = trim($_GET['action_type'] ?? '');

        // Lấy danh sách toàn bộ các loại log hoạt động để đưa vào select filter
        $actionTypes = [
            'login' => 'Đăng nhập thành công',
            'login_failed' => 'Đăng nhập thất bại',
            'create_user' => 'Thêm nhân viên mới',
            'update_user' => 'Cập nhật nhân viên',
            'toggle_user_status' => 'Khóa/Mở khóa tài khoản',
            'update_permissions' => 'Cập nhật phân quyền',
            'update_profile' => 'Cập nhật hồ sơ cá nhân',
            'create_trader' => 'Thêm tiểu thương',
            'update_trader' => 'Sửa tiểu thương',
            'delete_trader' => 'Xóa tiểu thương',
            'create_stall' => 'Thêm sạp',
            'update_stall' => 'Sửa sạp',
            'delete_stall' => 'Xóa sạp',
            'assign_stall' => 'Gán sạp nhanh',
            'transfer_stall' => 'Chuyển nhượng sạp',
            'create_contract' => 'Tạo hợp đồng',
            'update_contract' => 'Sửa hợp đồng',
            'delete_contract' => 'Xóa mềm hợp đồng',
            'renew_contract' => 'Gia hạn hợp đồng',
            'activate_contract' => 'Kích hoạt hợp đồng',
            'liquidate_contract' => 'Thanh lý hợp đồng',
            'terminate_contract' => 'Chấm dứt hợp đồng',
            'reactivate_contract' => 'Khôi phục hợp đồng',
            'create_appendix' => 'Thêm phụ lục hợp đồng',
            'create_certificate' => 'Thêm chứng nhận ATTP',
            'update_certificate' => 'Sửa chứng nhận ATTP',
            'delete_certificate' => 'Xóa chứng nhận ATTP',
            'save_map' => 'Thiết kế sơ đồ bản đồ',
            'create_category' => 'Thêm danh mục',
            'update_category' => 'Sửa danh mục',
            'delete_category' => 'Xóa danh mục',
            'create_market' => 'Thêm chợ mới',
            'update_market' => 'Sửa thông tin chợ',
            'delete_market' => 'Xóa chợ',
            'save_voucher' => 'Lập/Sửa phiếu thu chi',
            'delete_voucher' => 'Xóa phiếu thu chi'
        ];

        // Xây dựng câu SQL truy vấn chính
        $baseSql = " FROM system_logs l
                    LEFT JOIN users u ON l.log_user_id = u.user_id
                    LEFT JOIN system_actors sa ON u.user_actor_id = sa.actor_id
                    LEFT JOIN user_markets um ON u.user_id = um.user_market_user_id
                    WHERE 1=1";

        $params = [];

        $actorCode = $this->helper->get('actor_code');
        $currentUserId = $this->helper->get('user_id');

        // Ràng buộc quyền xem nhật ký theo cấp độ tài khoản
        if ($actorCode === 'admin') {
            // Admin cấp 3: chỉ xem logs của chính mình
            $baseSql .= " AND l.log_user_id = :current_id";
            $params['current_id'] = $currentUserId;
        } elseif (!$isSuper) {
            // Admin cấp 2: xem logs bản thân + nhân viên thuộc chợ mình quản lý
            $baseSql .= " AND (
                l.log_user_id = :manager_id
                OR um.user_market_market_id IN (
                    SELECT user_market_market_id FROM user_markets WHERE user_market_user_id = :manager_id
                )
            )";
            $params['manager_id'] = $currentUserId;
        }

        // Lọc theo Loại thao tác
        if ($actionType !== '') {
            $baseSql .= " AND l.log_action_type = :action_type";
            $params['action_type'] = $actionType;
        }

        // Tìm kiếm theo từ khóa (tên, mô tả)
        if ($search !== '') {
            $baseSql .= " AND (u.user_username LIKE :search OR u.user_fullname LIKE :search OR l.log_action_description LIKE :search OR l.log_ip_address LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        // Đếm tổng số dòng
        $countResult = $db->selectOne("SELECT COUNT(DISTINCT l.log_id) AS total" . $baseSql, $params);
        $totalRecords = (int)($countResult['total'] ?? 0);

        // Phân trang
        $limit = 15;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $totalPages = ceil($totalRecords / $limit);
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        $offset = ($page - 1) * $limit;

        // Truy vấn danh sách
        $sql = "SELECT DISTINCT l.*, u.user_username AS username, u.user_fullname AS fullname, sa.actor_name" . $baseSql . " ORDER BY l.log_id DESC LIMIT $offset, $limit";
        $logs = empty($params) ? $db->select($sql) : $db->select($sql, $params);

        $this->view->app("user/logs", [
            'title' => 'Nhật ký hoạt động',
            'logs' => $logs,
            'actionTypes' => $actionTypes,
            'search' => $search,
            'selectedActionType' => $actionType,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ]);
    }
}
