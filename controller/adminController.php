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
            if (!$this->helper->isLoggedIn() || $this->helper->get('actor_code') !== 'admin') {
                header('Location: ' . BASE_URL . 'login');
                exit();
            }
        }
    }

    public function index() {
        $this->dashboard();
    }
    
    public function login()
	{ 
		// Nếu đã đăng nhập đúng role admin, chuyển hướng thẳng vào dashboard
		if ($this->helper->isLoggedIn() && $this->helper->get('actor_code') === 'admin') {
			header('Location: ' . BASE_URL . 'admin/dashboard');
			exit();
		}
		$this->view->app("auth/login");
	}

    public function dashboard() {
        $marketId = $this->helper->currentMarketId();
        $db = database::getInstance();
        
        $stallStats = $db->selectOne("
            SELECT COUNT(*) as total_stalls,
                   SUM(CASE WHEN ss.status_code = 'rented' THEN 1 ELSE 0 END) as rented_stalls,
                   SUM(CASE WHEN ss.status_code = 'empty' THEN 1 ELSE 0 END) as empty_stalls,
                   SUM(CASE WHEN ss.status_code = 'repairing' THEN 1 ELSE 0 END) as repairing_stalls
            FROM stalls s
            JOIN areas a ON s.stall_area_id = a.area_id
            JOIN system_statuses ss ON s.stall_status_id = ss.status_id
            WHERE a.area_market_id = :market_id AND ss.status_code != '99'
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
        $areaId = $_GET['stall_area_id'] ?? '';
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

        $this->view('backend/stall/index', [
            'title' => 'Quản Lý Sạp Chợ',
            'stalls' => $stalls,
            'areas' => $areas,
            'statuses' => $statuses,
            'stats' => $stats,
            'search' => $search,
            'area_filter' => $areaId,
            'status_filter' => $status
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

        $this->view('backend/contract/index', [
            'title' => 'Hợp Đồng Thuê Sạp',
            'contracts' => $contracts,
            'statuses' => $statuses,
            'status_filter' => $status,
            'search' => $search
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
            ['stall_id' => 1, 'bill_code' => 'HĐ-0626-001', 'stall_code' => 'SẠP-A01', 'trader_name' => 'Nguyễn Thị Thu Hà', 'period' => '06/2026', 'total_amount' => 3650000, 'due_date' => '10/07/2026', 'status' => 'unpaid'],
            ['stall_id' => 2, 'bill_code' => 'HĐ-0626-002', 'stall_code' => 'SẠP-B01', 'trader_name' => 'Trần Văn Hoàng', 'period' => '06/2026', 'total_amount' => 5480000, 'due_date' => '10/07/2026', 'status' => 'paid'],
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
        $foodsafetyModel = new foodsafetyModel();
        
        $certificates = [];
        $statuses = [];

        $marketId = marketService::currentMarketId();

        try {
            // Tự động cập nhật trạng thái hết hạn trước khi hiển thị
            $foodsafetyModel->autoUpdateExpiryStatus();
            
            $certificates = $foodsafetyModel->getCertificates(null, null, null, null, $marketId);
            $statuses = $foodsafetyModel->getAttpStatuses();
        } catch (Exception $e) {
            error_log('[foodsafety] EXCEPTION: ' . $e->getMessage());
        }

        $this->view('backend/foodsafety/index', [
            'title' => 'An Toàn Thực Phẩm',
            'certificates' => $certificates,
            'statuses' => $statuses
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
        try {
            $areas = $stallModel->getAreas($marketId);
            $statuses = $stallModel->getStallStatuses();
            $stallTypes = $categoryModel->getItems('stall_type');
        } catch (Exception $e) {
            error_log('[stall_add] EXCEPTION: ' . $e->getMessage());
        }

        $this->view('backend/stall/add', [
            'title'      => 'Khai Báo Sạp Chợ Mới',
            'data'       => ['stall_area_id' => '', 'stall_code' => '', 'stall_type_id' => '', 'stall_area_size' => '', 'stall_base_price' => '', 'stall_status_id' => 3],
            'areas'      => $areas,
            'statuses'   => $statuses,
            'stallTypes' => $stallTypes
        ]);
    }

    /**
     * Chỉnh sửa Sạp chợ (chỉ GET - hiển thị form)
     */
    public function stall_edit($stall_id) {
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

        $marketId = marketService::currentMarketId();
        try {
            $stall = $stallModel->getById($stall_id);
            if (!$stall) {
                throw new Exception('Không tìm thấy sạp chợ yêu cầu.');
            }
            $areas = $stallModel->getAreas($marketId);
            $statuses = $stallModel->getStallStatuses();
            $stallTypes = $categoryModel->getItems('stall_type');
        } catch (Exception $e) {
            session::set('error_message', $e->getMessage());
            header('Location: ' . BASE_URL . 'admin/stalls');
            exit();
        }

        $this->view('backend/stall/edit', [
            'title'      => 'Chỉnh Sửa Sạp Chợ',
            'stall'      => $stall,
            'areas'      => $areas,
            'statuses'   => $statuses,
            'stallTypes' => $stallTypes
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
        try {
            $traderModel = new traderModel();
            // Lấy danh sách tiểu thương đang hoạt động
            $traders = $traderModel->getAllTraders(null, null, 'active');

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
        $stall_id = $_GET['stall_id'] ?? null;
        if (!$stall_id) {
            header('Location: ' . BASE_URL . 'admin/foodsafety');
            exit();
        }

        $certificate = null;
        $traders = [];
        $documentTypes = [];
        try {
            $foodsafetyModel = new foodsafetyModel();
            $certificate = $foodsafetyModel->getById($stall_id);
            if (!$certificate) {
                header('Location: ' . BASE_URL . 'admin/foodsafety');
                exit();
            }

            $traderModel = new traderModel();
            // Lấy danh sách tiểu thương đang hoạt động
            $traders = $traderModel->getAllTraders('', '', 'active');

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
                                   WHERE ss.status_code != '99' 
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

        $this->view('backend/trader/index', [
            'title' => 'Quản Lý Tiểu Thương',
            'traders' => $traders,
            'business_lines' => $business_lines,
            'statuses' => $statuses,
            'search' => $search,
            'business_line_filter' => $business_line,
            'status_filter' => $status
        ]);
    }

    /**
     * Thêm tiểu thương mới (chỉ GET - hiển thị form)
     */
    public function trader_add() {
        $statuses = [];
        $business_lines = [];
        try {
            $traderModel = new traderModel();
            $statuses = $traderModel->getTraderStatuses();
            $business_lines = $traderModel->getBusinessLines();
        } catch (Exception $e) {}

        $this->view('backend/trader/add', [
            'title'          => 'Thêm Tiểu Thương Mới',
            'data'           => ['trader_code' => '', 'fullname' => '', 'phone' => '', 'cccd' => '', 'address' => '', 'business_line_id' => '', 'description' => '', 'status_id' => 7],
            'statuses'       => $statuses,
            'business_lines' => $business_lines
        ]);
    }

    /**
     * Sửa thông tin tiểu thương (chỉ GET - hiển thị form)
     */
    public function trader_edit($id) {
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
                $t['fullname'],
                $t['phone'],
                $t['cccd'],
                $t['address'] ?? '',
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
        require DIR_TEMPLATE . '/backend/trader/print.php';
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
}
