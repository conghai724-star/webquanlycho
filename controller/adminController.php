<?php
/**
 * Controller xử lý các trang quản trị của Ban Quản Lý
 */
Class adminController extends baseController {
    public function __construct($registry) {
        parent::__construct($registry);

        // Bảo vệ toàn bộ các action trong adminController (trừ login)
        $action = isset($this->registry->router->action) ? $this->registry->router->action : '';
        if ($action !== 'login') {
            if (!$this->helper->isLoggedIn() || !in_array($this->helper->get('actor_code'), ['admin', 'admin_market', 'super_market'])) {
                header('Location: ' . BASE_URL . 'login');
                exit();
            }

            // Phân quyền chi tiết cho tài khoản nhân viên thường (actor_code == 'admin')
            if ($this->helper->get('actor_code') === 'admin') {
                // Bản đồ ánh xạ Action -> Module Code tương ứng
                $actionModuleMap = [
                    'index'                 => 'dashboard',
                    'dashboard'             => 'dashboard',
                    'profile'               => 'profile',
                    'change_password'       => 'profile',
                    
                    'stalls'                => 'stall',
                    'stall_add'             => 'stall',
                    'stall_edit'            => 'stall',
                    'map_editor'            => 'stall',
                    
                    'map_tree'              => 'map_tree',
                    
                    'traders'               => 'trader',
                    'trader_add'            => 'trader',
                    'trader_edit'           => 'trader',
                    'trader_export_excel'   => 'trader',
                    'trader_export_pdf'     => 'trader',
                    
                    'contracts'             => 'contract',
                    'contract_add'          => 'contract',
                    'contract_print'        => 'contract',
                    
                    'utilities'             => 'utilities',
                    'utility_add'           => 'utilities',
                    
                    'bills'                 => 'finance',
                    'bill_add'              => 'finance',
                    'transactions'          => 'finance',
                    'transaction_add'       => 'finance',
                    'income'                => 'finance',
                    'expense'               => 'finance',
                    'income_categories'     => 'finance',
                    'income_report'         => 'finance',
                    'export_s07x'           => 'finance',
                    
                    'foodsafety'            => 'foodsafety',
                    'foodsafety_add'        => 'foodsafety',
                    'foodsafety_edit'       => 'foodsafety',
                    
                    'categories'            => 'category',
                ];

                if (isset($actionModuleMap[$action])) {
                    $requiredModule = $actionModuleMap[$action];
                    if (!marketService::checkModuleAccess($requiredModule)) {
                        // Nếu gọi qua AJAX, trả về JSON 403, ngược lại hiển thị trang lỗi 403
                        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                            header('Content-Type: application/json');
                            http_response_code(403);
                            echo json_encode(['error' => 'Bạn không có quyền truy cập chức năng này.']);
                        } else {
                            http_response_code(403);
                            $this->view->app('errors/403', [
                                'title' => '403 Forbidden - Truy cập bị từ chối'
                            ]);
                        }
                        exit();
                    }
                }
            }
        }
    }

    public function index() {
        $this->dashboard();
    }
    
    public function login()
	{ 
		// Nếu đã đăng nhập đúng role, chuyển hướng thẳng vào dashboard tương ứng
		if ($this->helper->isLoggedIn()) {
			$actorCode = $this->helper->get('actor_code');
			if (in_array($actorCode, ['super_market', 'admin_market'])) {
				header('Location: ' . BASE_URL . 'system/dashboard');
				exit();
			} elseif ($actorCode === 'admin') {
				header('Location: ' . BASE_URL . 'admin/dashboard');
				exit();
			}
		}
		$this->view->app("auth/login");
	}

    public function dashboard() {
        $marketId = $this->helper->currentMarketId();
        if ($marketId === 0 && ($this->helper->get('actor_code') === 'super_market' || $this->helper->get('actor_code') === 'admin_market')) {
            header('Location: ' . BASE_URL . 'system/dashboard');
            exit();
        }
        $db = database::getInstance();
        
        $stallStats = $db->selectOne("
            SELECT COUNT(*) as total_stalls,
                   SUM(CASE WHEN ss.status_code = 'rented' THEN 1 ELSE 0 END) as rented_stalls,
                   SUM(CASE WHEN ss.status_code = 'empty' THEN 1 ELSE 0 END) as empty_stalls,
                   SUM(CASE WHEN ss.status_code = 'repairing' THEN 1 ELSE 0 END) as repairing_stalls
            FROM stalls s
            JOIN areas a ON s.stall_area_id = a.area_id
            JOIN system_statuses ss ON s.stall_status_id = ss.status_id
            WHERE a.area_market_id = :market_id AND s.stall_status_id != 99
        ", ['market_id' => $marketId]);
        
        $traderStats = $db->selectOne("
            SELECT COUNT(DISTINCT t.trader_id) as total_traders
            FROM traders t
            JOIN contracts c ON c.contract_trader_id = t.trader_id
            JOIN stalls s ON c.contract_stall_id = s.stall_id
            JOIN areas a ON s.stall_area_id = a.area_id
            JOIN system_statuses cs ON c.contract_status_id = cs.status_id
            WHERE a.area_market_id = :market_id AND cs.status_code = 'active'
        ", ['market_id' => $marketId]);
        
        $revenueStats = $db->selectOne("
            SELECT SUM(b.bill_total_amount) as total_revenue
            FROM bills b
            JOIN contracts c ON b.bill_contract_id = c.contract_id
            JOIN stalls s ON c.contract_stall_id = s.stall_id
            JOIN areas a ON s.stall_area_id = a.area_id
            WHERE a.area_market_id = :market_id 
              AND b.bill_status = 'paid'
              AND DATE_FORMAT(b.bill_invoice_date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')
        ", ['market_id' => $marketId]);

        $stats = [
            'total_stalls' => (int)($stallStats['total_stalls'] ?? 0),
            'rented_stalls' => (int)($stallStats['rented_stalls'] ?? 0),
            'empty_stalls' => (int)($stallStats['empty_stalls'] ?? 0),
            'repairing_stalls' => (int)($stallStats['repairing_stalls'] ?? 0),
            'total_traders' => (int)($traderStats['total_traders'] ?? 0),
            'revenue_this_month' => (float)($revenueStats['total_revenue'] ?? 0),
        ];

        $this->view('dashboard/index', [
            'title' => 'Bảng Điều Khiển',
            'stats' => $stats
        ]);
        
    }

    /**
     * Trang tùy biến chủ đề giao diện (Theme generator)
     */
    public function theme() {
        $this->view('backend/setting/theme', [
            'title' => 'Tùy Biến Giao Diện'
        ]);
    }

    /**
     * Phân hệ Quản lý Sạp chợ
     */
    public function stalls() {
        $areaId = $_GET['area_id'] ?? $_GET['stall_area_id'] ?? '';
        $status = $_GET['status'] ?? '';
        $search = $_GET['q'] ?? '';

        $stallModel = new stallModel();
        
        $stalls = [];
        $areas = [];
        $statuses = [];
        $stats = [
            'total' => 0,
            'rented' => 0,
            'empty' => 0,
            'repairing' => 0,
            'locked' => 0
        ];

        $marketId = marketService::currentMarketId();

        try {
            $stalls = $stallModel->getAll($areaId ?: null, $status ?: null, $search ?: null, $marketId);
            $areas = $stallModel->getAreas($marketId);
            $statuses = $stallModel->getStallStatuses();

            // Lấy toàn bộ sạp của chợ này để tính thống kê
            $allStalls = $stallModel->getAll(null, null, null, $marketId);
            $stats['total'] = count($allStalls);
            foreach ($allStalls as $s) {
                if ($s['status'] === 'rented') $stats['rented']++;
                elseif ($s['status'] === 'empty') $stats['empty']++;
                elseif ($s['status'] === 'repairing') $stats['repairing']++;
                elseif ($s['status'] === 'locked') $stats['locked']++;
            }
        } catch (Exception $e) {
            error_log('[stalls] EXCEPTION: ' . $e->getMessage());
        }

        // Phân trang
        $limit = 15;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $totalRecords = count($stalls);
        $totalPages = ceil($totalRecords / $limit);
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        $offset = ($page - 1) * $limit;
        $paginatedStalls = array_slice($stalls, $offset, $limit);

        $this->view('backend/stall/index', [
            'title' => 'Quản Lý Sạp Chợ',
            'stalls' => $paginatedStalls,
            'areas' => $areas,
            'statuses' => $statuses,
            'stats' => $stats,
            'search' => $search,
            'area_filter' => $areaId,
            'status_filter' => $status,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ]);
    }

    /**
     * Phân hệ Hợp đồng thuê sạp
     */
    public function contracts() {
        $status = $_GET['status'] ?? '';
        $search = $_GET['q'] ?? '';
        $contractModel = new contractModel();
        
        $contracts = [];
        $statuses = [];
        
        $marketId = marketService::currentMarketId();

        try {
            $contracts = $contractModel->getAll($status ?: null, $search ?: null, $marketId);
            $statuses = $contractModel->getContractStatuses();
        } catch (Exception $e) {
            error_log('[contracts] EXCEPTION: ' . $e->getMessage());
        }

        // Phân trang
        $limit = 15;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $totalRecords = count($contracts);
        $totalPages = ceil($totalRecords / $limit);
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        $offset = ($page - 1) * $limit;
        $paginatedContracts = array_slice($contracts, $offset, $limit);

        $this->view('backend/contract/index', [
            'title' => 'Hợp Đồng Thuê Sạp',
            'contracts' => $paginatedContracts,
            'statuses' => $statuses,
            'status_filter' => $status,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ]);
    }

    /**
     * Phân hệ Ghi số Điện & Nước
     */
    public function utilities() {
        $readings = [
            ['stall_id' => 1, 'period' => '06/2026', 'stall_code' => 'SẠP-A01', 'old_electric' => 1540, 'new_electric' => 1690, 'old_water' => 240, 'new_water' => 255, 'recorded_date' => '25/06/2026', 'recorder' => 'Lê Thị Bình'],
            ['stall_id' => 2, 'period' => '06/2026', 'stall_code' => 'SẠP-B01', 'old_electric' => 3200, 'new_electric' => 3450, 'old_water' => 410, 'new_water' => 432, 'recorded_date' => '25/06/2026', 'recorder' => 'Lê Thị Bình'],
        ];

        $this->view('backend/finance/utilities', [
            'title' => 'Chỉ Số Điện & Nước',
            'readings' => $readings
        ]);
    }

    /**
     * Phân hệ Hóa đơn dịch vụ
     */
    public function bills() {
        $bills = [
            ['stall_id' => 1, 'bill_code' => 'HĐ-0626-001', 'stall_code' => 'SẠP-A01', 'trader_name' => 'Nguyễn Thị Thu Hà', 'period' => '06/2026', 'bill_total_amount' => 3650000, 'bill_due_date' => '10/07/2026', 'bill_status' => 'unpaid'],
            ['stall_id' => 2, 'bill_code' => 'HĐ-0626-002', 'stall_code' => 'SẠP-B01', 'trader_name' => 'Trần Văn Hoàng', 'period' => '06/2026', 'bill_total_amount' => 5480000, 'bill_due_date' => '10/07/2026', 'bill_status' => 'paid'],
        ];

        $this->view('backend/finance/bills', [
            'title' => 'Hóa Đơn Dịch Vụ',
            'bills' => $bills
        ]);
    }

    /**
     * Phân hệ Phiếu thu - Phiếu chi
     */
    public function transactions() {
        $transactions = [
            ['stall_id' => 1, 'transaction_code' => 'PT-0001', 'type' => 'receipt', 'target' => 'Trần Văn Hoàng (SẠP-B01)', 'amount' => 5480000, 'note' => 'Thu tiền hóa đơn tháng 06/2026', 'date' => '28/06/2026', 'creator' => 'Nguyễn Văn An'],
            ['stall_id' => 2, 'transaction_code' => 'PC-0001', 'type' => 'payment', 'target' => 'Công ty Điện lực Hà Nội', 'amount' => 12500000, 'note' => 'Thanh toán tiền điện tổng của chợ tháng 06/2026', 'date' => '29/06/2026', 'creator' => 'Nguyễn Văn An'],
        ];

        $this->view('backend/finance/transactions', [
            'title' => 'Thu - Chi Tài Chính',
            'transactions' => $transactions
        ]);
    }

    public function foodsafety() {
        $search = $_GET['q'] ?? '';
        $docType = $_GET['doc_type'] ?? '';
        $status = $_GET['status'] ?? '';
        
        $foodsafetyModel = new foodsafetyModel();
        $categoryModel = new categoryModel();
        
        $certificates = [];
        $statuses = [];
        $documentTypes = [];

        $marketId = marketService::currentMarketId();

        try {
            // Tự động cập nhật trạng thái hết hạn trước khi hiển thị
            $foodsafetyModel->autoUpdateExpiryStatus();
            
            $certificates = $foodsafetyModel->getCertificates(null, $docType ?: null, $status ?: null, $search ?: null, $marketId);
            $statuses = $foodsafetyModel->getAttpStatuses();
            $documentTypes = $categoryModel->getItems('document_type');
        } catch (Exception $e) {
            error_log('[foodsafety] EXCEPTION: ' . $e->getMessage());
        }

        // Phân trang
        $limit = 15;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $totalRecords = count($certificates);
        $totalPages = ceil($totalRecords / $limit);
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        $offset = ($page - 1) * $limit;
        $paginatedCertificates = array_slice($certificates, $offset, $limit);

        $this->view('backend/foodsafety/index', [
            'title' => 'An Toàn Thực Phẩm',
            'certificates' => $paginatedCertificates,
            'statuses' => $statuses,
            'documentTypes' => $documentTypes,
            'search' => $search,
            'doc_type_filter' => $docType,
            'status_filter' => $status,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ]);
    }

    /**
     * Phân hệ Quản lý tài khoản & phân quyền
     */
    public function users() {
        header("Location: " . BASE_URL . "system/users");
        exit();
    }

    /**
     * Thêm Sạp chợ mới (chỉ GET - hiển thị form)
     */
    public function stall_add() {
        $stallModel = new stallModel();
        $categoryModel = new categoryModel();
        $areas = [];
        $statuses = [];
        $stallTypes = [];

        $marketId = marketService::currentMarketId();
        $nextStallCode = '';
        try {
            $areas = $stallModel->getAreas($marketId);
            $statuses = $stallModel->getStallStatuses();
            $stallTypes = $categoryModel->getItems('stall_type');

            $db = database::getInstance();
            $market = $db->selectOne("SELECT market_code FROM markets WHERE market_id = :market_id", ['market_id' => $marketId]);
            if ($market && !empty($market['market_code'])) {
                $cleanCode = preg_replace('/[^a-zA-Z0-9]/', '', $market['market_code']);
                $cleanCode = strtoupper($cleanCode);

                $sqlMax = "SELECT stall_code FROM stalls s 
                           JOIN areas a ON s.stall_area_id = a.area_id 
                           WHERE a.area_market_id = :market_id AND s.stall_code LIKE :prefix";
                $existingStalls = $db->select($sqlMax, [
                    'market_id' => $marketId,
                    'prefix' => $cleanCode . '-%'
                ]);

                $maxNumber = 0;
                foreach ($existingStalls as $stall) {
                    $code = $stall['stall_code'];
                    $parts = explode('-', $code);
                    $numPart = end($parts);
                    if (is_numeric($numPart)) {
                        $val = (int)$numPart;
                        if ($val > $maxNumber) {
                            $maxNumber = $val;
                        }
                    }
                }
                $nextNumber = $maxNumber + 1;
                $nextStallCode = $cleanCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        } catch (Exception $e) {
            error_log('[stall_add] EXCEPTION: ' . $e->getMessage());
        }

        $statusModel = new statusModel();
        $emptyStatusId = $statusModel->getIdByCode('stall', 'empty');

        $this->view('backend/stall/add', [
            'title'      => 'Khai Báo Sạp Chợ Mới',
            'data'       => [
                'stall_area_id' => '', 
                'stall_code' => $nextStallCode, 
                'stall_type_id' => '', 
                'stall_area_size' => '', 
                'stall_base_price' => '', 
                'stall_status_id' => $emptyStatusId, 
                'stall_map_coordinate_x' => '', 
                'stall_map_coordinate_y' => ''
            ],
            'areas'      => $areas,
            'statuses'   => $statuses,
            'stallTypes' => $stallTypes
        ]);
    }

    /**
     * Chỉnh sửa Sạp chợ (chỉ GET - hiển thị form)
     */
    public function stall_edit($stall_id) {
        if (is_array($stall_id)) {
            $stall_id = reset($stall_id);
        }
        if (!$stall_id) {
            header('Location: ' . BASE_URL . 'admin/stalls');
            exit();
        }

        $stallModel = new stallModel();
        $categoryModel = new categoryModel();
        $stall = null;
        $areas = [];
        $statuses = [];
        $stallTypes = [];
        $rentalHistory = [];

        $marketId = marketService::currentMarketId();
        try {
            $stall = $stallModel->getById($stall_id);
            if (!$stall) {
                throw new Exception('Không tìm thấy sạp chợ yêu cầu.');
            }
            $areas = $stallModel->getAreas($marketId);
            $statuses = $stallModel->getStallStatuses();
            $stallTypes = $categoryModel->getItems('stall_type');
            $rentalHistory = $stallModel->getRentalHistory($stall_id);
        } catch (Exception $e) {
            session::set('error_message', $e->getMessage());
            header('Location: ' . BASE_URL . 'admin/stalls');
            exit();
        }

        $this->view('backend/stall/edit', [
            'title'         => 'Chỉnh Sửa Sạp Chợ',
            'stall'         => $stall,
            'areas'         => $areas,
            'statuses'      => $statuses,
            'stallTypes'    => $stallTypes,
            'rentalHistory' => $rentalHistory
        ]);
    }

    /**
     * Lập Hợp đồng mới
     */
    public function contract_add() {
        $traders = [];
        $emptyStalls = [];
        
        try {
            $traderModel = new traderModel();
            // Lấy tiểu thương hoạt động
            $traders = $traderModel->getAllTraders('', '', 'active');
            
            $stallModel = new stallModel();
            // Lấy các sạp trống
            $emptyStalls = $stallModel->getAll(null, 'empty');
        } catch (Exception $e) {
            error_log('[contract_add] EXCEPTION: ' . $e->getMessage());
        }

        $this->view('backend/contract/add', [
            'title' => 'Lập Hợp Đồng Thuê Sạp',
            'traders' => $traders,
            'emptyStalls' => $emptyStalls
        ]);
    }

    /**
     * Ghi chỉ số điện nước mới (Mockup Form)
     */
    public function utility_add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session::set('success_message', 'Ghi nhận chỉ số điện nước thành công!');
            header('Location: ' . BASE_URL . 'admin/utilities');
            exit();
        }
        $this->view('backend/finance/utility_add', ['title' => 'Ghi Số Điện Nước Mới']);
    }

    /**
     * Lập hóa đơn mới (Mockup Form / View Action)
     */
    public function bill_add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session::set('success_message', 'Lập hóa đơn mới thành công!');
            header('Location: ' . BASE_URL . 'admin/bills');
            exit();
        }

        $contracts = [];
        try {
            $contractModel = new contractModel();
            $contracts = $contractModel->getAll();
        } catch (Exception $e) {
            $contracts = [
                ['stall_id' => 1, 'contract_code' => 'HĐ-2026-0001', 'trader_name' => 'Nguyễn Thị Thu Hà', 'stall_code' => 'SẠP-A01'],
                ['stall_id' => 2, 'contract_code' => 'HĐ-2026-0002', 'trader_name' => 'Trần Văn Hoàng', 'stall_code' => 'SẠP-B01'],
            ];
        }

        $this->view('backend/finance/bill_add', [
            'title' => 'Lập Hóa Đơn Mới',
            'contracts' => $contracts
        ]);
    }

    /**
     * Lập phiếu thu - chi (Mockup Form)
     */
    public function transaction_add() {
        $type = $_GET['type'] ?? 'receipt';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $msg = ($_POST['type'] === 'receipt') ? 'Lập phiếu thu thành công!' : 'Lập phiếu chi thành công!';
            session::set('success_message', $msg);
            header('Location: ' . BASE_URL . 'admin/transactions');
            exit();
        }
        $this->view('backend/finance/transaction_add', [
            'title' => ($type === 'receipt') ? 'Lập Phiếu Thu Tài Chính' : 'Lập Phiếu Chi Tài Chính',
            'type' => $type
        ]);
    }

    public function foodsafety_add() {
        $traders = [];
        $documentTypes = [];
        $marketId = marketService::currentMarketId();
        try {
            $traderModel = new traderModel();
            // Lấy danh sách tiểu thương đang hoạt động thuộc chợ hiện tại
            $traders = $traderModel->getAllTraders(null, null, 'active', $marketId);

            $categoryModel = new categoryModel();
            $documentTypes = $categoryModel->getItems('document_type');
        } catch (Exception $e) {
            error_log('[foodsafety_add] EXCEPTION: ' . $e->getMessage());
        }

        $this->view('backend/foodsafety/add', [
            'title' => 'Khai Báo Chứng Nhận ATTP',
            'traders' => $traders,
            'documentTypes' => $documentTypes
        ]);
    }

    public function foodsafety_edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . BASE_URL . 'admin/foodsafety');
            exit();
        }

        $certificate = null;
        $traders = [];
        $documentTypes = [];
        $marketId = marketService::currentMarketId();
        try {
            $foodsafetyModel = new foodsafetyModel();
            $certificate = $foodsafetyModel->getById($id);
            if (!$certificate) {
                header('Location: ' . BASE_URL . 'admin/foodsafety');
                exit();
            }

            $traderModel = new traderModel();
            // Lấy danh sách tiểu thương đang hoạt động thuộc chợ hiện tại
            $traders = $traderModel->getAllTraders('', '', 'active', $marketId);

            $categoryModel = new categoryModel();
            $documentTypes = $categoryModel->getItems('document_type');
        } catch (Exception $e) {
            error_log('[foodsafety_edit] EXCEPTION: ' . $e->getMessage());
        }

        $this->view('backend/foodsafety/edit', [
            'title' => 'Chỉnh Sửa Chứng Nhận ATTP',
            'certificate' => $certificate,
            'traders' => $traders,
            'documentTypes' => $documentTypes
        ]);
    }

    /**
     * Trang thiết lập sơ đồ chợ tương tác dành cho Admin
     */
    public function map_editor() {
        $stalls = [];
        $unmappedStalls = [];
        try {
            $mapModel = new mapModel();
            $unmappedStalls = $mapModel->getUnmappedStalls();
            
            $db = database::getInstance();
            $stalls = $db->select("SELECT s.stall_id, s.stall_code, s.stall_type, s.stall_area_size, s.stall_base_price, 
                                          ss.status_name, ss.status_code AS status_code, sc.color_class,
                                          a.area_name, a.area_block, a.area_lot,
                                          t.trader_fullname AS trader_name, t.trader_phone AS trader_phone,
                                          con.contract_number, con.end_date AS contract_end_date
                                   FROM stalls s 
                                   LEFT JOIN areas a ON s.stall_area_id = a.area_id
                                   JOIN system_statuses ss ON s.stall_status_id = ss.status_id 
                                   LEFT JOIN status_colors sc ON ss.status_color_id = sc.color_id
                                   LEFT JOIN contracts con ON con.stall_id = s.stall_id AND con.status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                                   LEFT JOIN traders t ON con.trader_id = t.trader_id
                                   WHERE s.stall_status_id != 99 
                                   ORDER BY s.stall_code ASC");
        } catch (Exception $e) {
            error_log('[map_editor] EXCEPTION: ' . $e->getMessage());
        }

        $this->view('backend/map/editor', [
            'title' => 'Thiết lập Sơ đồ chợ tương tác',
            'unmappedStalls' => $unmappedStalls,
            'stalls' => $stalls
        ]);
    }

    /**
     * Trang sơ đồ cây sạp chợ tương tác dành cho Admin
     */
    public function map_tree() {
        $this->view('backend/map/tree', [
            'title' => 'Sơ đồ Cây sạp chợ tương tác'
        ]);
    }

    /**
     * Tạo tài khoản nhân viên mới
     */
    public function user_add() {
        header("Location: " . BASE_URL . "system/user_add");
        exit();
    }

    public function user_edit($id = null) {
        header("Location: " . BASE_URL . "system/user_edit/" . $id);
        exit();
    }

    public function user_toggle_status($id = null) {
        header("Location: " . BASE_URL . "system/user_toggle_status/" . $id);
        exit();
    }

    /**
     * Phân hệ Quản lý Tiểu thương
     */
    public function traders() {
        $traderModel = new traderModel();
        
        $search = $_GET['q'] ?? '';
        $business_line = $_GET['business_line'] ?? '';
        $status = $_GET['status'] ?? '';
        
        $traders = [];
        $business_lines = [];
        $statuses = [];
        
        $marketId = marketService::currentMarketId();

        try {
            // Lấy danh sách tiểu thương theo bộ lọc
            $traders = $traderModel->getAllTraders($search, $business_line, $status, $marketId);
            $statuses = $traderModel->getTraderStatuses();
            
            // Lấy danh sách các ngành hàng từ DB
            $business_lines = $traderModel->getBusinessLines();
        } catch (Exception $e) {
            // Fallback khi lỗi cơ sở dữ liệu
            error_log('[traders] EXCEPTION: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            $traders = [];
            $business_lines = [];
            $statuses = [];
        }

        // Phân trang
        $limit = 15;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $totalRecords = count($traders);
        $totalPages = ceil($totalRecords / $limit);
        if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
        $offset = ($page - 1) * $limit;
        $paginatedTraders = array_slice($traders, $offset, $limit);

        $this->view('backend/trader/index', [
            'title' => 'Quản Lý Tiểu Thương',
            'traders' => $paginatedTraders,
            'business_lines' => $business_lines,
            'statuses' => $statuses,
            'search' => $search,
            'business_line_filter' => $business_line,
            'status_filter' => $status,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalRecords' => $totalRecords
        ]);
    }

    /**
     * Thêm tiểu thương mới (chỉ GET - hiển thị form)
     */
    public function trader_add() {
        $statuses = [];
        $business_lines = [];
        $nextTraderCode = '';

        $marketId = marketService::currentMarketId();
        try {
            $traderModel = new traderModel();
            $statuses = $traderModel->getTraderStatuses();
            $business_lines = $traderModel->getBusinessLines();

            $db = database::getInstance();
            $market = $db->selectOne("SELECT market_code FROM markets WHERE market_id = :market_id", ['market_id' => $marketId]);
            if ($market && !empty($market['market_code'])) {
                $cleanCode = preg_replace('/[^a-zA-Z0-9]/', '', $market['market_code']);
                $cleanCode = strtoupper($cleanCode);
                $prefix = 'TT-' . $cleanCode;

                $sqlMax = "SELECT trader_code FROM traders 
                           WHERE trader_market_id = :market_id AND trader_code LIKE :prefix";
                $existingTraders = $db->select($sqlMax, [
                    'market_id' => $marketId,
                    'prefix' => $prefix . '-%'
                ]);

                $maxNumber = 0;
                foreach ($existingTraders as $t) {
                    $code = $t['trader_code'];
                    $parts = explode('-', $code);
                    $numPart = end($parts);
                    if (is_numeric($numPart)) {
                        $val = (int)$numPart;
                        if ($val > $maxNumber) {
                            $maxNumber = $val;
                        }
                    }
                }
                $nextNumber = $maxNumber + 1;
                $nextTraderCode = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        } catch (Exception $e) {
            error_log('[trader_add] EXCEPTION: ' . $e->getMessage());
        }

        $statusModel = new statusModel();
        $activeTraderStatusId = $statusModel->getIdByCode('trader', 'active');

        $this->view('backend/trader/add', [
            'title'          => 'Thêm Tiểu Thương Mới',
            'data'           => [
                'trader_code' => $nextTraderCode, 
                'fullname' => '', 
                'phone' => '', 
                'cccd' => '', 
                'address' => '', 
                'business_line_id' => '', 
                'description' => '', 
                'status_id' => $activeTraderStatusId
            ],
            'statuses'       => $statuses,
            'business_lines' => $business_lines
        ]);
    }

    /**
     * Sửa thông tin tiểu thương (chỉ GET - hiển thị form)
     */
    public function trader_edit($id) {
        if (is_array($id)) {
            $id = reset($id);
        }
        if (!$id) {
            header('Location: ' . BASE_URL . 'admin/traders');
            exit();
        }

        $traderModel = new traderModel();

        try {
            $trader = $traderModel->getTraderById($id);
            if (!$trader) {
                throw new Exception(message::error('not_found', 'trader'));
            }
        } catch (Exception $e) {
            session::set('error_message', $e->getMessage());
            header('Location: ' . BASE_URL . 'admin/traders');
            exit();
        }

        $statuses = [];
        $business_lines = [];
        try {
            $statuses = $traderModel->getTraderStatuses();
            $business_lines = $traderModel->getBusinessLines();
        } catch (Exception $e) {}

        $this->view('backend/trader/edit', [
            'title'          => 'Chỉnh Sửa Tiểu Thương',
            'trader'         => $trader,
            'statuses'       => $statuses,
            'business_lines' => $business_lines
        ]);
    }

    /**
     * Xuất danh sách tiểu thương ra file Excel (.xlsx thật sự)
     */
    public function trader_export_excel() {
        $traderModel = new traderModel();
        $search = $_GET['q'] ?? '';
        $business_line = $_GET['business_line'] ?? '';
        $status = $_GET['status'] ?? '';

        try {
            $traders = $traderModel->getAllTraders($search, $business_line, $status);
        } catch (Exception $e) {
            $traders = [];
        }

        $headers = ['Mã tiểu thương', 'Họ và tên', 'Số điện thoại', 'Số CCCD', 'Địa chỉ', 'Ngành hàng', 'Trạng thái', 'Công nợ (đ)'];
        $rows = [];
        foreach ($traders as $t) {
            $rows[] = [
                $t['trader_code'],
                $t['trader_fullname'],
                $t['trader_phone'],
                $t['trader_cccd'],
                $t['trader_address'] ?? '',
                $t['business_line_name'] ?? 'Chưa cập nhật',
                $t['status_name'] ?? 'Không rõ',
                (int)($t['total_debt'] ?? 0)
            ];
        }

        SimpleXlsx::download('danh_sach_tieu_thuong.xlsx', $headers, $rows);
    }

    /**
     * Xuất danh sách tiểu thương ra file PDF (tải xuống trực tiếp)
     */
    public function trader_export_pdf() {
        $traderModel = new traderModel();
        $search = $_GET['q'] ?? '';
        $business_line = $_GET['business_line'] ?? '';
        $status = $_GET['status'] ?? '';

        try {
            $traders = $traderModel->getAllTraders($search, $business_line, $status);
        } catch (Exception $e) {
            $traders = [];
        }

        // Mô tả bộ lọc
        $filterParts = [];
        if ($search) $filterParts[] = 'Từ khóa: ' . $search;
        if ($status) $filterParts[] = 'Trạng thái: ' . $status;
        if ($business_line && !empty($traders)) {
            $filterParts[] = 'Ngành hàng: ' . ($traders[0]['business_line_name'] ?? $business_line);
        }
        $filterDesc = implode(' | ', $filterParts);

        // Sinh nội dung HTML cho PDF
        ob_start();
        $title = 'Báo cáo danh sách tiểu thương';
        require DIR_TEMPLATE . '/trader/print.php';
        $html = ob_get_clean();

        // Nạp mPDF từ vendor của dự án khác trên cùng máy chủ
        $autoload = 'D:/xampp/htdocs/vieclam.vn/application/vendor/autoload.php';
        if (!file_exists($autoload)) {
            // Fallback: mở trang HTML nếu mPDF không có
            header('Content-Type: text/html; charset=utf-8');
            echo $html;
            exit();
        }

        require_once $autoload;

        // mPDF cũ có Deprecated warning trên PHP 8.x — tắt tạm để không in ra output
        // ponytail: bỏ dòng này khi nâng cấp lên mPDF 8.x mới hơn
        $prevErrLevel = error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
        ob_start(); // bắt bất kỳ output thừa nào (whitespace, warning leak)

        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => 'A4-L',   // A4 ngang để bảng không bị chật
            'margin_left'   => 12,
            'margin_right'  => 12,
            'margin_top'    => 14,
            'margin_bottom' => 14,
        ]);
        $mpdf->SetTitle('Báo cáo tiểu thương');
        $mpdf->WriteHTML($html);

        ob_end_clean(); // xóa hết output cũ trước khi gửi binary PDF
        error_reporting($prevErrLevel);

        $mpdf->Output('danh_sach_tieu_thuong.pdf', \Mpdf\Output\Destination::DOWNLOAD);
        exit();
    }



    /**
     * Đổi mật khẩu cá nhân
     */
    public function change_password() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $validator = new validator();
            $validator->required('old_password', $oldPassword, 'Vui lòng nhập mật khẩu hiện tại.')
                      ->required('new_password', $newPassword, 'Vui lòng nhập mật khẩu mới.')
                      ->minLength('new_password', $newPassword, 6, 'Mật khẩu mới phải từ 6 ký tự trở lên.')
                      ->required('confirm_password', $confirmPassword, 'Vui lòng xác nhận mật khẩu mới.')
                      ->matches('confirm_password', $confirmPassword, $newPassword, 'Xác nhận mật khẩu mới không khớp.');

            if ($validator->isValid()) {
                $userModel = new userModel();
                $userId = session::get('user_id');
                $user = $userModel->getByUsername(session::get('username'));

                if ($user && password_verify($oldPassword, $user['password'])) {
                    try {
                        $userModel->updatePassword($userId, $newPassword);
                        $success = 'Đổi mật khẩu thành công!';
                    } catch (Exception $e) {
                        $error = 'Lỗi hệ thống: ' . $e->getMessage();
                    }
                } else {
                    $error = 'Mật khẩu hiện tại không chính xác.';
                }
            } else {
                $errors = $validator->getErrors();
                $error = reset($errors);
            }
        }

        $this->view('backend/auth/change_password', [
            'title' => 'Đổi Mật Khẩu',
            'error' => $error,
            'success' => $success
        ]);
    }
    /**
     * Quản lý các danh mục hệ thống (Khu vực, Loại sạp, Ngành hàng, Loại giấy tờ)
     */
    public function categories() {
        $categoryModel = new categoryModel();

        // Chuẩn bị dữ liệu ban đầu cho các danh mục
        $areas = $categoryModel->getItems('area');
        $stallTypes = $categoryModel->getItems('stall_type');
        $businessLines = $categoryModel->getItems('business_line');
        $documentTypes = $categoryModel->getItems('document_type');

        $this->view('backend/category/index', [
            'title'         => 'Quản Lý Danh Mục',
            'areas'         => $areas,
            'stallTypes'    => $stallTypes,
            'businessLines' => $businessLines,
            'documentTypes' => $documentTypes
        ]);
    }

    /**
     * Giao diện phân quyền phân hệ cho nhân viên (chỉ dành cho admin_market và super_market)
     */
    public function permissions() {
        header("Location: " . BASE_URL . "system/permissions");
        exit();
    }

    public function save_permissions() {
        header("Location: " . BASE_URL . "system/save_permissions");
        exit();
    }

    public function markets() {
        header("Location: " . BASE_URL . "system/markets");
        exit();
    }

    public function market_add() {
        header("Location: " . BASE_URL . "system/market_add");
        exit();
    }

    public function market_edit($id) {
        header("Location: " . BASE_URL . "system/market_edit/" . $id);
        exit();
    }

    public function contract_print($contract_id) {
        if (is_array($contract_id)) {
            $contract_id = reset($contract_id);
        }
        if (!$contract_id) {
            header('Location: ' . BASE_URL . 'admin/contracts');
            exit();
        }

        $contractModel = new contractModel();
        $contract = $contractModel->getById($contract_id);
        if (!$contract) {
            header('Location: ' . BASE_URL . 'admin/contracts');
            exit();
        }

        $db = database::getInstance();
        $marketId = $contract['area_market_id'] ?? 0;
        
        $allConfigs = $db->select("SELECT * FROM market_contract_configs WHERE market_id = :mId AND status_id != 99 ORDER BY config_id ASC", ['mId' => $marketId]);
        
        $configId = isset($_GET['config_id']) ? (int)$_GET['config_id'] : 0;
        $selectedConfig = null;
        
        if ($configId > 0) {
            foreach ($allConfigs as $cfg) {
                if ((int)$cfg['config_id'] === $configId) {
                    $selectedConfig = $cfg;
                    break;
                }
            }
        }
        
        if (!$selectedConfig && !empty($allConfigs)) {
            foreach ($allConfigs as $cfg) {
                if ((int)$cfg['is_default'] === 1) {
                    $selectedConfig = $cfg;
                    break;
                }
            }
            if (!$selectedConfig) {
                $selectedConfig = $allConfigs[0];
            }
        }
        
        // Fallback defaults if no configs found in DB
        if (!$selectedConfig) {
            $selectedConfig = [
                'gov_agency_1' => 'UBND PHƯỜNG KON TUM',
                'gov_agency_2' => 'PHÒNG KT,HT&ĐT',
                'contract_title_suffix' => 'TẠI CÁC CHỢ HẠNG 3 PHƯỜNG KON TUM',
                'rep_a_header' => 'Đại diện Tổ quản lý các chợ hạng 3 trên địa bàn phường Kon Tum - Trưởng phòng Kinh tế, Hạ tầng và Đô thị (gọi tắt là Bên A):',
                'rep_a_name_1' => 'Phan Thành Trung',
                'rep_a_position_1' => 'Tổ trưởng',
                'rep_a_name_2' => 'Trương Thảo Linh',
                'rep_a_position_2' => 'Tài chính - Kế Toán',
                'rep_a_address' => '342 Nguyễn Huệ, phường Kon Tum, tỉnh Kon Tum',
                'rep_a_phone' => '',
                'rep_a_fax' => '',
                'rep_a_bank_account' => '',
                'rep_a_bank_name' => '',
                'legal_grounds' => "Căn cứ Bộ Luật dân sự ngày 24 tháng 11 năm 2015;\nCăn cứ Nghị định số 60/2024/NĐ-CP ngày 05 tháng 6 năm 2024 của Chính phủ về phát triển và quản lý chợ;\nCăn cứ Quyết định số 131/QĐ-UBND ngày 06/8/2025 của UBND phường Kon Tum về việc thành lập Tổ quản lý các chợ hạng 3 trên địa bàn phường Kon Tum;\nCăn cứ nhu cầu sử dụng diện tích bán hàng của hộ kinh doanh và xét khả năng đáp ứng của đơn vị."
            ];
        }

        // standalone view without theme panels
        require_once DIR_TEMPLATE . '/contract/print.php';
        exit();
    }

    protected function view($templatePath, $data = []) {
        // Giải nén mảng thành các biến độc lập
        extract($data);

        // Nạp layout trên
        if (file_exists(DIR_TEMPLATE . '/layouts/header.php')) {
            require_once DIR_TEMPLATE . '/layouts/header.php';
        }
        if (file_exists(DIR_TEMPLATE . '/layouts/sidebar.php')) {
            require_once DIR_TEMPLATE . '/layouts/sidebar.php';
        }
        if (file_exists(DIR_TEMPLATE . '/layouts/navbar.php')) {
            require_once DIR_TEMPLATE . '/layouts/navbar.php';
        }

        // Nạp nội dung trang con
        $templatePathClean = str_replace('backend/', '', $templatePath);
        $viewFile = DIR_TEMPLATE . '/' . $templatePathClean . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "<div class='container-fluid'><p class='text-danger'>Không tìm thấy giao diện: {$templatePathClean} (gốc: {$templatePath})</p></div>";
        }

        // Nạp layout dưới
        if (file_exists(DIR_TEMPLATE . '/layouts/footer.php')) {
            require_once DIR_TEMPLATE . '/layouts/footer.php';
        }
    }
     /**
     * Sổ thu: tải dữ liệu thật, có lọc và phân trang 20 phiếu/trang.
     */
    public function income() {
        $this->incomeLedger('income');
    }

    /** Sổ chi được tách trang với sổ thu để dễ nghiệp vụ và phân quyền sau này. */
    public function expense() {
        $this->incomeLedger('expense');
    }

    private function incomeLedger($type) {
        $marketId = marketService::currentMarketId();
        $filters = [
            'q' => trim($_GET['q'] ?? ''), 'category_id' => (int)($_GET['category_id'] ?? 0),
            'from_date' => $_GET['from_date'] ?? '', 'to_date' => $_GET['to_date'] ?? ''
        ];
        $page = max(1, (int)($_GET['page'] ?? 1));
        $model = new incomeModel(); // ensureSchema() makes a fresh database ready automatically.
        $result = $model->vouchers($marketId, $type, $filters, $page, 20);
        $this->view('backend/income/ledger', [
            'title' => $type === 'income' ? 'Quản Lý Thu' : 'Quản Lý Chi',
            'ledgerType' => $type, 'categories' => $model->categories($marketId, $type),
            'vouchers' => $result['rows'], 'total' => $result['total'], 'pages' => $result['pages'],
            'page' => $page, 'filters' => $filters
        ]);
    }

    /** Danh mục thu và chi có chung một giao diện, tách bằng hai tab. */
    public function income_categories() {
        $marketId = marketService::currentMarketId();
        $model = new incomeModel();
        $this->view('backend/income/categories', [
            'title' => 'Danh Mục Thu Chi',
            'incomeCategories' => $model->categories($marketId, 'income'),
            'expenseCategories' => $model->categories($marketId, 'expense')
        ]);
    }

    /** Form xuất S07-X + Dashboard thống kê thu chi theo tháng. */
    public function income_report() {
        $marketId = marketService::currentMarketId();
        $year = (int)($_GET['year'] ?? date('Y'));
        $categoryId = (int)($_GET['category_id'] ?? 0) ?: null;
        $month = (int)($_GET['month'] ?? 0) ?: null;

        $model = new incomeModel();
        $monthly = $model->getMonthlyReport($marketId, $year, $categoryId);

        $totalIncome = $totalExpense = 0;
        foreach ($monthly as $m) { $totalIncome += $m['income']; $totalExpense += $m['expense']; }

        // Daily breakdown when a specific month is selected
        $daily = $month ? $model->getDailyReport($marketId, $year, $month, $categoryId) : null;

        $service = new incomeReportService();
        $this->view('backend/income/report', [
            'title' => 'Báo Cáo Thu Chi',
            'unit' => $service->getUnitConfig($marketId),
            'year' => $year,
            'month' => $month,
            'categoryId' => $categoryId,
            'monthly' => $monthly,
            'daily' => $daily,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'categories' => $model->allCategories($marketId),
        ]);
    }

    /** Streams the .xlsx response; calculations/rendering stay in incomeReportService. */
    public function export_s07x() {
        $marketId = marketService::currentMarketId();
        $year = (int)($_GET['year'] ?? date('Y'));
        try {
            (new incomeReportService())->downloadS07X($marketId, [
                'year' => $year,
                'fund_name' => trim($_GET['fund_name'] ?? ''),
                'opening_balance' => (float)str_replace([',',' '], '', $_GET['opening_balance'] ?? 0),
                'from_date' => $_GET['from_date'] ?? $year.'-01-01',
                'to_date' => $_GET['to_date'] ?? $year.'-12-31'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo '<h3>Không thể xuất báo cáo S07-X</h3><p>'.htmlspecialchars($e->getMessage()).'</p>';
        }
    }

    /**
     * Trang thông tin cá nhân của người dùng đang đăng nhập
     */
    public function profile() {
        $userId = session::get('user_id');
        $db = database::getInstance();

        // Lấy thông tin tài khoản
        $user = $db->selectOne("
            SELECT u.*, u.user_username AS username, u.user_fullname AS fullname, u.user_email AS email, sa.actor_name, sa.actor_code 
            FROM users u
            LEFT JOIN system_actors sa ON u.user_actor_id = sa.actor_id
            WHERE u.user_id = :id
        ", ['id' => $userId]);

        if (!$user) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }

        // Lấy danh sách chợ trực thuộc
        $assignedMarkets = $db->select("
            SELECT m.market_id, m.market_name 
            FROM user_markets um
            JOIN markets m ON um.user_market_market_id = m.market_id
            WHERE um.user_market_user_id = :id AND m.market_status_code = 'active'
            ORDER BY m.market_name ASC
        ", ['id' => $userId]);

        // Lấy danh sách các quyền phân hệ của user này
        $userPermsRows = $db->select("
            SELECT permission_market_id, permission_module_code 
            FROM user_market_permissions 
            WHERE permission_user_id = :id
        ", ['id' => $userId]);

        $permissions = [];
        foreach ($userPermsRows as $p) {
            $permissions[$p['permission_market_id']][] = $p['permission_module_code'];
        }

        // Tên thân thiện của các phân hệ chức năng
        $moduleNames = [
            'trader' => 'Tiểu thương',
            'stall' => 'Sạp chợ',
            'contract' => 'Hợp đồng',
            'finance' => 'Tài chính',
            'foodsafety' => 'An toàn thực phẩm'
        ];

        // Định dạng dữ liệu để nạp vào template
        $this->view('backend/user/profile', [
            'title' => 'Thông tin cá nhân',
            'user' => $user,
            'assignedMarkets' => $assignedMarkets,
            'permissions' => $permissions,
            'moduleNames' => $moduleNames
        ]);
    }

    // =========================================================================
    // QUẢN LÝ MẪU IN HỢP ĐỒNG (APIS CHO ADMIN CẤP 2 & CẤP 1)
    // =========================================================================

    /**
     * API thêm mẫu in hợp đồng (AJAX POST)
     */
    public function addContractConfig() {
        $this->render->abort405('POST', 'create', 'contract_config');
        $this->render->abort403(marketService::isSuperAdmin() || marketService::isAdminMarket(), 'create', 'contract_config');

        $data = $this->getContractConfigData();

        $validator = new validator();
        $validator->required('template_name', $data['template_name'], 'Tên mẫu không được để trống.')
                  ->required('market_id', $data['market_id'], 'Thiếu ID chợ.');

        $this->render->abort400($validator, 'create', 'contract_config');

        $marketId = $data['market_id'];
        if (!marketService::isSuperAdmin()) {
            $accMarkets = marketService::getAccessibleMarketIds();
            if (!in_array((int)$marketId, $accMarkets)) {
                $this->render->apiResponse('create', 'contract_config', false, 'Bạn không có quyền thực hiện hành động này tại chợ này.', 403);
            }
        }

        try {
            $db = database::getInstance();

            // Nếu đánh dấu mặc định, bỏ mặc định các mẫu khác cùng chợ
            if ($data['is_default']) {
                $db->query("UPDATE market_contract_configs SET is_default = 0 WHERE market_id = :mId", ['mId' => $marketId]);
            }

            $db->query("INSERT INTO market_contract_configs 
                (market_id, template_name, gov_agency_1, gov_agency_2, contract_title_suffix, rep_a_header, rep_a_name_1, rep_a_position_1, rep_a_name_2, rep_a_position_2, rep_a_address, rep_a_phone, rep_a_fax, rep_a_bank_account, rep_a_bank_name, legal_grounds, is_default, payment_due_day, payment_grace_period)
                VALUES (:market_id, :template_name, :gov_agency_1, :gov_agency_2, :contract_title_suffix, :rep_a_header, :rep_a_name_1, :rep_a_position_1, :rep_a_name_2, :rep_a_position_2, :rep_a_address, :rep_a_phone, :rep_a_fax, :rep_a_bank_account, :rep_a_bank_name, :legal_grounds, :is_default, :payment_due_day, :payment_grace_period)", $data);

            general::log('create_contract_config', "Tạo mẫu in hợp đồng: {$data['template_name']} (Chợ ID: {$marketId})");
            $this->render->apiResponse('create', 'contract_config', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'contract_config');
        }
    }

    /**
     * API cập nhật mẫu in hợp đồng (AJAX POST)
     */
    public function editContractConfig() {
        $this->render->abort405('POST', 'update', 'contract_config');
        $this->render->abort403(marketService::isSuperAdmin() || marketService::isAdminMarket(), 'update', 'contract_config');

        $configId = $_POST['config_id'] ?? '';
        $this->render->abort400(!empty($configId), 'update', 'contract_config', 'Thiếu ID cấu hình.');

        $db = database::getInstance();
        $config = $db->selectOne("SELECT * FROM market_contract_configs WHERE config_id = :id", ['id' => $configId]);
        if (!$config) {
            $this->render->apiResponse('update', 'contract_config', false, 'Không tìm thấy cấu hình cần cập nhật.', 404);
        }

        $marketId = $config['market_id'];
        if (!marketService::isSuperAdmin()) {
            $accMarkets = marketService::getAccessibleMarketIds();
            if (!in_array((int)$marketId, $accMarkets)) {
                $this->render->apiResponse('update', 'contract_config', false, 'Bạn không có quyền cập nhật mẫu in tại chợ này.', 403);
            }
        }

        $data = $this->getContractConfigData();

        $validator = new validator();
        $validator->required('template_name', $data['template_name'], 'Tên mẫu không được để trống.');

        $this->render->abort400($validator, 'update', 'contract_config');

        try {
            if ($data['is_default']) {
                $db->query("UPDATE market_contract_configs SET is_default = 0 WHERE market_id = :mId", ['mId' => $marketId]);
            }

            $data['config_id'] = $configId;
            $db->query("UPDATE market_contract_configs SET
                template_name = :template_name, gov_agency_1 = :gov_agency_1, gov_agency_2 = :gov_agency_2,
                contract_title_suffix = :contract_title_suffix, rep_a_header = :rep_a_header,
                rep_a_name_1 = :rep_a_name_1, rep_a_position_1 = :rep_a_position_1,
                rep_a_name_2 = :rep_a_name_2, rep_a_position_2 = :rep_a_position_2,
                rep_a_address = :rep_a_address, rep_a_phone = :rep_a_phone, rep_a_fax = :rep_a_fax,
                rep_a_bank_account = :rep_a_bank_account, rep_a_bank_name = :rep_a_bank_name,
                legal_grounds = :legal_grounds, is_default = :is_default,
                payment_due_day = :payment_due_day, payment_grace_period = :payment_grace_period
                WHERE config_id = :config_id", $data);

            general::log('update_contract_config', "Cập nhật mẫu in hợp đồng ID: {$configId}");
            $this->render->apiResponse('update', 'contract_config', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'contract_config');
        }
    }

    /**
     * API xóa mẫu in hợp đồng (AJAX POST)
     */
    public function deleteContractConfig() {
        $this->render->abort405('POST', 'delete', 'contract_config');
        // Chỉ Super Admin được phép xóa
        $this->render->abort403(marketService::isSuperAdmin(), 'delete', 'contract_config');

        $configId = $_POST['config_id'] ?? '';
        $this->render->abort400(!empty($configId), 'delete', 'contract_config', 'Thiếu ID cấu hình cần xóa.');

        try {
            $db = database::getInstance();
            $db->query("UPDATE market_contract_configs SET status_id = 99 WHERE config_id = :id", ['id' => $configId]);
            general::log('delete_contract_config', "Xóa mẫu in hợp đồng ID: {$configId}");
            $this->render->apiResponse('delete', 'contract_config', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'delete', 'contract_config');
        }
    }

    /**
     * API chuyển trạng thái mẫu in hợp đồng (AJAX POST)
     */
    public function toggleContractConfigStatus() {
        $this->render->abort405('POST', 'update', 'contract_config');
        $this->render->abort403(marketService::isSuperAdmin() || marketService::isAdminMarket(), 'update', 'contract_config');

        $configId = $_POST['config_id'] ?? '';
        $newStatusCode = $_POST['status'] ?? '';
        $this->render->abort400(!empty($configId) && in_array($newStatusCode, ['active', 'inactive']), 'update', 'contract_config', 'Dữ liệu không hợp lệ.');

        $db = database::getInstance();
        $config = $db->selectOne("SELECT * FROM market_contract_configs WHERE config_id = :id", ['id' => $configId]);
        if (!$config) {
            $this->render->apiResponse('update', 'contract_config', false, 'Không tìm thấy cấu hình.', 404);
        }

        if (!marketService::isSuperAdmin()) {
            $accMarkets = marketService::getAccessibleMarketIds();
            if (!in_array((int)$config['market_id'], $accMarkets)) {
                $this->render->apiResponse('update', 'contract_config', false, 'Không có quyền.', 403);
            }
        }

        // Lấy status_id từ system_statuses
        $newStatus = $db->selectOne("SELECT status_id FROM system_statuses WHERE status_domain = 'contract_config' AND status_code = :code", ['code' => $newStatusCode]);
        if (!$newStatus) {
            $this->render->apiResponse('update', 'contract_config', false, 'Trạng thái không hợp lệ.', 400);
        }

        try {
            $db->query("UPDATE market_contract_configs SET status_id = :sid WHERE config_id = :id", [
                'sid' => $newStatus['status_id'],
                'id' => $configId
            ]);
            general::log('toggle_contract_config_status', "Chuyển trạng thái mẫu in ID: {$configId} → {$newStatusCode}");
            $this->render->apiResponse('update', 'contract_config', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'contract_config');
        }
    }

    /**
     * Helper: Lấy dữ liệu từ POST cho contract config
     */
    private function getContractConfigData() {
        return [
            'market_id'             => $_POST['market_id'] ?? '',
            'template_name'         => trim($_POST['template_name'] ?? ''),
            'gov_agency_1'          => trim($_POST['gov_agency_1'] ?? ''),
            'gov_agency_2'          => trim($_POST['gov_agency_2'] ?? ''),
            'contract_title_suffix' => trim($_POST['contract_title_suffix'] ?? ''),
            'rep_a_header'          => trim($_POST['rep_a_header'] ?? ''),
            'rep_a_name_1'          => trim($_POST['rep_a_name_1'] ?? ''),
            'rep_a_position_1'      => trim($_POST['rep_a_position_1'] ?? ''),
            'rep_a_name_2'          => trim($_POST['rep_a_name_2'] ?? ''),
            'rep_a_position_2'      => trim($_POST['rep_a_position_2'] ?? ''),
            'rep_a_address'         => trim($_POST['rep_a_address'] ?? ''),
            'rep_a_phone'           => trim($_POST['rep_a_phone'] ?? ''),
            'rep_a_fax'             => trim($_POST['rep_a_fax'] ?? ''),
            'rep_a_bank_account'    => trim($_POST['rep_a_bank_account'] ?? ''),
            'rep_a_bank_name'       => trim($_POST['rep_a_bank_name'] ?? ''),
            'legal_grounds'         => trim($_POST['legal_grounds'] ?? ''),
            'is_default'            => (int)($_POST['is_default'] ?? 0),
            'payment_due_day'       => trim($_POST['payment_due_day'] ?? '10'),
            'payment_grace_period'  => trim($_POST['payment_grace_period'] ?? '10')
        ];
    }
}

