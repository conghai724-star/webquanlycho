<?php
/**
 * Controller xử lý các yêu cầu AJAX / API trả về dữ liệu JON
 */
class apiController extends baseController {

    public function __construct($registry) {
        parent::__construct($registry);

        $action = isset($this->registry->router->action) ? $this->registry->router->action : '';

        // Một số API công khai hoặc chỉ check login chung
        $publicActions = ['login', 'checkSession'];
        if (in_array($action, $publicActions)) {
            return;
        }

        // Bắt buộc đăng nhập cho toàn bộ các API còn lại
        if (!$this->helper->isLoggedIn()) {
            $this->response(['error' => 'Unauthorized'], 401);
        }

        // Phân quyền chi tiết cho tài khoản nhân viên thường (actor_code == 'admin')
        if ($this->helper->get('actor_code') === 'admin') {
            $actionModuleMap = [
                'addTrader'                     => 'trader',
                'editTrader'                    => 'trader',
                'deleteTrader'                  => 'trader',
                
                'addStall'                      => 'stall',
                'editStall'                     => 'stall',
                'deleteStall'                   => 'stall',
                'getNextStallCode'              => 'stall',
                'getAvailableStallsForTransfer' => 'stall',
                
                'getAvailableTraders'           => 'contract',
                'assignStall'                   => 'contract',
                'transferStall'                 => 'contract',
                'addContract'                   => 'contract',
                'editContract'                  => 'contract',
                'deleteContract'                => 'contract',
                
                'addUtility'                    => 'utilities',
                'editUtility'                   => 'utilities',
                
                'addBill'                       => 'finance',
                'editBill'                      => 'finance',
                'deleteBill'                    => 'finance',
                'recordPayment'                 => 'finance',
                'addIncome'                     => 'finance',
                'editIncome'                    => 'finance',
                'deleteIncome'                  => 'finance',
                'addExpense'                    => 'finance',
                'editExpense'                   => 'finance',
                'deleteExpense'                 => 'finance',
                
                'addFoodSafety'                 => 'foodsafety',
                'editFoodSafety'                => 'foodsafety',
                'deleteFoodSafety'              => 'foodsafety',
                
                'addCategory'                   => 'category',
                'editCategory'                  => 'category',
                'deleteCategory'                => 'category',
            ];
            
            if (isset($actionModuleMap[$action])) {
                $requiredModule = $actionModuleMap[$action];
                if (!marketService::checkModuleAccess($requiredModule)) {
                    $this->response(['error' => 'Bạn không có quyền thực hiện thao tác này.'], 403);
                }
            }
        }
    }

    /**
     * Phương thức index bắt buộc của baseController
     */
    public function index() {
        $this->response(['error' => 'API Endpoint not found.'], 404);
    }


    /**
     * Helper xuất phản hồi JSON
     */
    protected function response($data, $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }
    public function login()
	{
		$result = array();
		
		$username = trim($_POST["email"] ?? '');
		$password = $_POST["password"] ?? '';

		if (!$username || !$password) {
			$result["status"] = "500";
			$result['message'] = 'Vui lòng nhập tài khoản và mật khẩu';
			echo json_encode($result);
			return;
		}

		$db = database::getInstance();
		$row = $db->selectOne("
			SELECT u.*, sa.actor_code 
			FROM users u 
			LEFT JOIN system_actors sa ON u.user_actor_id = sa.actor_id
			WHERE u.user_username = :username AND u.user_is_active = 1
		", ['username' => $username]);

		if (!$row) {
			general::log('login_failed', "Đăng nhập thất bại. Tài khoản không tồn tại hoặc bị khóa: {$username}");
			$result["status"] = "500";
			$result['message'] = 'Thông tin tài khoản hoặc mật khẩu không chính xác';
			echo json_encode($result);
			return;
		}

		// Hỗ trợ cả md5 (legacy) và bcrypt (mới)
		$storedHash = $row['user_password'];
		$passwordOk = false;
		if (strlen($storedHash) === 32) {
			// Legacy md5
			$passwordOk = (md5($password) === $storedHash);
		} else {
			// Bcrypt (password_hash)
			$passwordOk = password_verify($password, $storedHash);
		}

		if (!$passwordOk) {
			$_SESSION['user_id'] = $row['user_id'];
			general::log('login_failed', "Đăng nhập thất bại. Sai mật khẩu cho tài khoản: {$username}");
			unset($_SESSION['user_id']);
			$result["status"] = "500";
			$result['message'] = 'Thông tin tài khoản hoặc mật khẩu không chính xác';
			echo json_encode($result);
			return;
		}

		// Chỉ cho phép đăng nhập nếu có vai trò phù hợp (admin, admin_market, super_market)
		if (!in_array($row['actor_code'] ?? 'admin', ['admin', 'admin_market', 'super_market'])) {
			$_SESSION['user_id'] = $row['user_id'];
			general::log('login_failed', "Đăng nhập thất bại. Tài khoản không có quyền truy cập trang quản trị: {$username}");
			unset($_SESSION['user_id']);
			$result["status"] = "500";
			$result['message'] = 'Tài khoản không có quyền truy cập trang quản trị này';
			echo json_encode($result);
			return;
		}

		// Đăng nhập thành công — set session
		$_SESSION['user_id'] = $row['user_id']; // Phải set user_id trước để general::log nhận diện đúng user đang ghi log
		general::log('login', "Đăng nhập hệ thống thành công. Tài khoản: {$username}");

		$_SESSION['user']['id'] = $row['user_id'];
		$_SESSION['user']['market_email'] = $row['user_email'];
		$_SESSION['user']['fullname'] = $row['user_fullname'];
		$_SESSION['user_fullname'] = $row['user_fullname'];
		$_SESSION['username'] = $row['user_username'];
		$_SESSION['user']['group'] = $row['user_group'];
		$_SESSION['LoggedIn'] = 1;
		$_SESSION['user_logged_in'] = true;
		$_SESSION['actor_code'] = $row['actor_code'] ?? 'admin';
		$_SESSION['user_group'] = $row['user_group'];
		$_SESSION['user_market_user_id'] = $row['user_id'];

		// Set active_market_id dựa trên role
		$actorCode = $row['actor_code'] ?? 'admin';
		if ($actorCode === 'admin') {
			// Nhân viên vận hành: lấy chợ đầu tiên được gán
			$market = $db->selectOne("
				SELECT um.user_market_market_id 
				FROM user_markets um 
				WHERE um.user_market_user_id = :uid LIMIT 1
			", ['uid' => $row['user_id']]);
			$_SESSION['active_market_id'] = $market ? (int)$market['user_market_market_id'] : 0;
		} else {
			// super_market / admin_market: bắt đầu ở trang tổng (active_market_id = 0)
			$_SESSION['active_market_id'] = 0;
		}

		$result["status"] = 200;
		$result["contract_name"] = $_SESSION['user']['fullname'];
		if (in_array($actorCode, ['super_market', 'admin_market'])) {
			$result['return_url'] = XC_URL."/system/dashboard";
		} else {
			$result['return_url'] = XC_URL."/admin";
		}
		echo json_encode($result);
	}


    /**
     * API thống kê doanh thu theo tháng (Phục vụ vẽ biểu đồ Chart.js)
     */
    public function getRevenueData() {
        // Dữ liệu giả lập doanh thu 6 tháng gần nhất
        $data = [
            'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
            'revenue' => [380000000, 420000000, 400000000, 450000000, 480000000, 450000000],
            'expense' => [120000000, 130000000, 115000000, 140000000, 150000000, 135000000]
        ];

        $this->response($data);
    }
//--------------BẮT ĐẦU QUẢN LÝ TIỂU THƯƠNG--------------//
    /**
     * API lọc và tìm kiếm tiểu thương qua AJAX
     */
    public function filterTraders() {
        $search = $_GET['q'] ?? '';
        $business_line = $_GET['business_line'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        try {
            $traderModel = new traderModel();
            $marketId = marketService::currentMarketId();
            $traders = $traderModel->getAllTraders($search, $business_line, $status, $marketId);

            $limit = 15;
            $totalRecords = count($traders);
            $totalPages = ceil($totalRecords / $limit);
            if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $paginatedTraders = array_slice($traders, $offset, $limit);

            // Nạp template table_rows.php để sinh ra HTML
            ob_start();
            $traders = $paginatedTraders;
            require DIR_TEMPLATE . '/trader/table_rows.php';
            $html = ob_get_clean();

            // Sinh HTML phân trang
            $baseUrl = BASE_URL . 'admin/traders';
            $queryParams = [];
            if (!empty($search)) $queryParams['q'] = $search;
            if (!empty($business_line)) $queryParams['business_line'] = $business_line;
            if (!empty($status)) $queryParams['status'] = $status;
            $paginationHtml = general::getPaginationHtml($page, $totalPages, $baseUrl, $queryParams);

            // Sinh query string mới phục vụ cập nhật các link export file
            $queryString = http_build_query([
                'q' => $search,
                'business_line' => $business_line,
                'status' => $status,
                'page' => $page
            ]);

            $this->response([
                'status' => 200,
                'total' => $totalRecords,
                'html' => $html,
                'paginationHtml' => $paginationHtml,
                'queryString' => $queryString
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API xóa tiểu thương (AJAX POST)
     */
    public function deleteTrader() {
        $this->render->abort405('POST', 'delete', 'trader');
        $this->render->abort400('id', 'delete', 'trader');

        $trader_id = $_POST['id'];

        try {
            $traderModel = new traderModel();
            $trader = $this->render->abort404($traderModel, 'getTraderById', $trader_id, 'delete', 'trader');

            $traderModel->deleteTrader($trader_id);
            general::log('delete_trader', "Xóa tiểu thương: {$trader['trader_fullname']} (Mã: {$trader['trader_code']}, ID: {$trader_id})");
            $this->render->apiResponse('delete', 'trader', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'delete', 'trader');
        }
    }

    public function addTrader() {
        $this->render->abort405('POST', 'create', 'trader');

        $statusModel = new statusModel();
        $activeTraderStatusId = $statusModel->getIdByCode('trader', 'active');

        $data = [
            'trader_code'             => $_POST['trader_code'] ?? '',
            'trader_fullname'         => $_POST['fullname'] ?? '',
            'trader_phone'            => $_POST['phone'] ?? '',
            'trader_cccd'             => $_POST['cccd'] ?? '',
            'trader_address'          => $_POST['address'] ?? '',
            'trader_business_line_id' => $_POST['business_line_id'] ?? null,
            'trader_description'      => $_POST['description'] ?? '',
            'trader_status_id'        => $_POST['status'] ?: $activeTraderStatusId,
            'trader_market_id'        => marketService::currentMarketId()
        ];

        // Xác thực dữ liệu
        $validator = new validator();
        $validator->required('trader_code', $data['trader_code'], 'Mã tiểu thương không được để trống.')
                  ->required('fullname', $data['trader_fullname'], 'Họ tên không được để trống.')
                  ->required('phone', $data['trader_phone'], 'Số điện thoại không được để trống.')
                  ->phone('phone', $data['trader_phone'], 'Số điện thoại không đúng định dạng Việt Nam.')
                  ->required('cccd', $data['trader_cccd'], 'Số CCCD không được để trống.');

        $this->render->abort400($validator, 'create', 'trader');

        try {
            $traderModel = new traderModel();

            // Tự động sinh mã tiểu thương theo market_code của chợ
            $marketId = $data['trader_market_id'];
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
                $data['trader_code'] = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            // Kiểm tra trùng lặp
            $this->render->abort400(!$traderModel->isTraderCodeExists($data['trader_code']), 'create', 'trader', 'Mã tiểu thương đã tồn tại trên hệ thống');
            $this->render->abort400(!$traderModel->isCccdExists($data['trader_cccd']), 'create', 'trader', 'Số CCCD đã tồn tại trên hệ thống');

            // Xử lý upload nhiều tài liệu đính kèm (nếu có)
            $uploadedFiles = $this->render->uploadMultipleFiles('license_files', 'traders', ['jpg', 'jpeg', 'png', 'pdf'], 10, 'create', 'trader');
            $data['trader_license_file'] = !empty($uploadedFiles) ? json_encode($uploadedFiles) : null;

            $traderModel->createTrader($data);
            general::log('create_trader', "Thêm tiểu thương mới: {$data['trader_fullname']} (Mã: {$data['trader_code']})");
            $this->render->apiResponse('create', 'trader', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'trader');
        }
    }

    public function editTrader() {
        $this->render->abort405('POST', 'update', 'trader');
        $this->render->abort400('id', 'update', 'trader');

        $trader_id = $_POST['id'];

        $statusModel = new statusModel();
        $activeTraderStatusId = $statusModel->getIdByCode('trader', 'active');

        $data = [
            'trader_fullname'         => $_POST['fullname'] ?? '',
            'trader_phone'            => $_POST['phone'] ?? '',
            'trader_cccd'             => $_POST['cccd'] ?? '',
            'trader_address'          => $_POST['address'] ?? '',
            'trader_business_line_id' => $_POST['business_line_id'] ?? null,
            'trader_description'      => $_POST['description'] ?? '',
            'trader_status_id'        => $_POST['status'] ?: $activeTraderStatusId
        ];

        // Xác thực dữ liệu
        $validator = new validator();
        $validator->required('fullname', $data['trader_fullname'], 'Họ tên không được để trống.')
                  ->required('phone', $data['trader_phone'], 'Số điện thoại không được để trống.')
                  ->phone('phone', $data['trader_phone'], 'Số điện thoại không đúng định dạng Việt Nam.')
                  ->required('cccd', $data['trader_cccd'], 'Số CCCD không được để trống.');

        $this->render->abort400($validator, 'update', 'trader');

        try {
            $traderModel = new traderModel();
            $trader = $this->render->abort404($traderModel, 'getTraderById', $trader_id, 'update', 'trader');
            $data['trader_market_id'] = $trader['trader_market_id'] ?? null;

            // Kiểm tra trùng lặp số CCCD (loại trừ bản ghi hiện tại)
            $this->render->abort400(!$traderModel->isCccdExists($data['trader_cccd'], $trader_id), 'update', 'trader', 'Số CCCD đã tồn tại trên hệ thống');

            // Xử lý các file cũ còn lại sau khi người dùng xóa bớt trên giao diện
            $existingFiles = [];
            if (!empty($trader['trader_license_file'])) {
                $decoded = json_decode($trader['trader_license_file'], true);
                $existingFiles = is_array($decoded) ? $decoded : [$trader['trader_license_file']];
            }
            $keptFiles = $_POST['existing_files'] ?? [];
            $existingFiles = array_intersect($existingFiles, $keptFiles);

            // Xử lý upload các file mới
            $uploadedFiles = $this->render->uploadMultipleFiles('license_files', 'traders', ['jpg', 'jpeg', 'png', 'pdf'], 10, 'update', 'trader');
            $finalFiles = array_merge($existingFiles, $uploadedFiles);
            $data['trader_license_file'] = !empty($finalFiles) ? json_encode(array_values($finalFiles)) : null;

            $traderModel->updateTrader($trader_id, $data);
            general::log('update_trader', "Cập nhật tiểu thương: {$data['trader_fullname']} (Mã: {$trader['trader_code']}, ID: {$trader_id})");
            $this->render->apiResponse('update', 'trader', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'trader');
        }
    }

    public function filterStalls() {
        $search = $_GET['q'] ?? '';
        $area_id = $_GET['area_id'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        try {
            $stallModel = new stallModel();
            $marketId = marketService::currentMarketId();
            $stalls = $stallModel->getAll($area_id ?: null, $status ?: null, $search ?: null, $marketId);

            $limit = 15;
            $totalRecords = count($stalls);
            $totalPages = ceil($totalRecords / $limit);
            if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $paginatedStalls = array_slice($stalls, $offset, $limit);

            ob_start();
            $stalls = $paginatedStalls;
            require DIR_TEMPLATE . '/stall/table_rows.php';
            $html = ob_get_clean();

            // Sinh HTML phân trang
            $baseUrl = BASE_URL . 'admin/stalls';
            $queryParams = [];
            if (!empty($search)) $queryParams['q'] = $search;
            if (!empty($area_id)) $queryParams['area_id'] = $area_id;
            if (!empty($status)) $queryParams['status'] = $status;
            $paginationHtml = general::getPaginationHtml($page, $totalPages, $baseUrl, $queryParams);

            $queryString = http_build_query([
                'q' => $search,
                'area_id' => $area_id,
                'status' => $status,
                'page' => $page
            ]);

            $this->response([
                'status' => 200,
                'total' => $totalRecords,
                'html' => $html,
                'paginationHtml' => $paginationHtml,
                'queryString' => $queryString
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API lấy mã sạp tự động tiếp theo theo khu vực/chợ (AJAX GET)
     */
    public function getNextStallCode() {
        $this->render->abort405('GET', 'read', 'stall');
        $areaId = $_GET['area_id'] ?? '';
        if (!$areaId) {
            $this->response(['status' => 400, 'message' => 'Khu vực không hợp lệ.'], 400);
        }

        $db = database::getInstance();
        $area = $db->selectOne("
            SELECT a.area_market_id, m.market_code 
            FROM areas a 
            JOIN markets m ON a.area_market_id = m.market_id 
            WHERE a.area_id = :area_id
        ", ['area_id' => $areaId]);

        $nextCode = '';
        if ($area && !empty($area['market_code'])) {
            $cleanCode = preg_replace('/[^a-zA-Z0-9]/', '', $area['market_code']);
            $cleanCode = strtoupper($cleanCode);

            $sqlMax = "SELECT stall_code FROM stalls s 
                       JOIN areas a ON s.stall_area_id = a.area_id 
                       WHERE a.area_market_id = :market_id AND s.stall_code LIKE :prefix";
            $existingStalls = $db->select($sqlMax, [
                'market_id' => $area['area_market_id'],
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
            $nextCode = $cleanCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        }

        $this->response([
            'status' => 200,
            'next_code' => $nextCode
        ]);
    }

    /**
     * API thêm sạp mới (AJAX POST)
     */
    public function addStall() {
        $this->render->abort405('POST', 'create', 'stall');

        $statusModel = new statusModel();
        $emptyStatusId = $statusModel->getIdByCode('stall', 'empty');

        $x = $_POST['x'] ?? '';
        $y = $_POST['y'] ?? '';
        $area_size = '';
        if (is_numeric($x) && is_numeric($y)) {
            $area_size = (float)$x * (float)$y;
        }

        $basePrice = $_POST['base_price'] ?? '';
        if (is_string($basePrice)) {
            $basePrice = str_replace('.', '', $basePrice);
        }

        $data = [
            'stall_area_id'       => $_POST['area_id'] ?? '',
            'stall_code'          => $_POST['stall_code'] ?? '',
            'stall_type_id'       => $_POST['stall_type_id'] ?? '',
            'stall_area_size'     => $area_size,
            'stall_base_price'    => $basePrice,
            'stall_status_id'     => $_POST['status'] ?: $emptyStatusId,
            'stall_map_coordinate_x' => is_numeric($x) ? (float)$x : null,
            'stall_map_coordinate_y' => is_numeric($y) ? (float)$y : null
        ];

        $validator = new validator();
        $validator->required('area_id', $data['stall_area_id'], 'Khu vực không được để trống.')
                  ->required('stall_code', $data['stall_code'], 'Mã sạp không được để trống.')
                  ->required('stall_type_id', $data['stall_type_id'], 'Vui lòng chọn loại sạp.')
                  ->required('x', $x, 'Chiều dài không được để trống.')
                  ->numeric('x', $x, 'Chiều dài phải là dạng số.')
                  ->min('x', $x, 0.01, 'Chiều dài phải lớn hơn 0.')
                  ->required('y', $y, 'Chiều rộng không được để trống.')
                  ->numeric('y', $y, 'Chiều rộng phải là dạng số.')
                  ->min('y', $y, 0.01, 'Chiều rộng phải lớn hơn 0.')
                  ->required('base_price', $data['stall_base_price'], 'Đơn giá thuê không được để trống.')
                  ->numeric('base_price', $data['stall_base_price'], 'Đơn giá thuê phải là dạng số.')
                  ->min('base_price', $data['stall_base_price'], 0, 'Đơn giá thuê không được âm.');

        $this->render->abort400($validator, 'create', 'stall');

        try {
            $stallModel = new stallModel();

            // Tự động sinh mã sạp theo market_code của chợ
            $db = database::getInstance();
            $area = $db->selectOne("
                SELECT a.area_market_id, m.market_code 
                FROM areas a 
                JOIN markets m ON a.area_market_id = m.market_id 
                WHERE a.area_id = :area_id
            ", ['area_id' => $data['stall_area_id']]);

            if ($area && !empty($area['market_code'])) {
                // Loại bỏ tất cả ký tự không phải chữ và số (ví dụ: P.KT -> PKT)
                $cleanCode = preg_replace('/[^a-zA-Z0-9]/', '', $area['market_code']);
                $cleanCode = strtoupper($cleanCode);

                // Lấy tất cả sạp hiện có của chợ có mã sạp khớp tiền tố
                $sqlMax = "SELECT stall_code FROM stalls s 
                           JOIN areas a ON s.stall_area_id = a.area_id 
                           WHERE a.area_market_id = :market_id AND s.stall_code LIKE :prefix";
                $existingStalls = $db->select($sqlMax, [
                    'market_id' => $area['area_market_id'],
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
                $data['stall_code'] = $cleanCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }

            $this->render->abort400(!$stallModel->isStallCodeExists($data['stall_code']), 'create', 'stall', 'Mã sạp đã tồn tại trên hệ thống');

            $stallModel->create($data);
            general::log('create_stall', "Thêm sạp mới: {$data['stall_code']} (Khu vực ID: {$data['stall_area_id']}, Diện tích: {$data['stall_area_size']}m2)");
            $this->render->apiResponse('create', 'stall', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'stall');
        }
    }

    public function editStall() {
        $this->render->abort405('POST', 'update', 'stall');
        $this->render->abort400('id', 'update', 'stall');

        $stall_id = $_POST['id'];

        $statusModel = new statusModel();
        $emptyStatusId = $statusModel->getIdByCode('stall', 'empty');

        $x = $_POST['x'] ?? '';
        $y = $_POST['y'] ?? '';
        $area_size = '';
        if (is_numeric($x) && is_numeric($y)) {
            $area_size = (float)$x * (float)$y;
        }

        $basePrice = $_POST['base_price'] ?? '';
        if (is_string($basePrice)) {
            $basePrice = str_replace('.', '', $basePrice);
        }

        $data = [
            'stall_area_id'       => $_POST['area_id'] ?? '',
            'stall_code'          => $_POST['stall_code'] ?? '',
            'stall_type_id'       => $_POST['stall_type_id'] ?? '',
            'stall_area_size'     => $area_size,
            'stall_base_price'    => $basePrice,
            'stall_status_id'     => $_POST['status'] ?: $emptyStatusId,
            'stall_map_coordinate_x' => is_numeric($x) ? (float)$x : null,
            'stall_map_coordinate_y' => is_numeric($y) ? (float)$y : null
        ];

        $validator = new validator();
        $validator->required('area_id', $data['stall_area_id'], 'Khu vực không được để trống.')
                  ->required('stall_code', $data['stall_code'], 'Mã sạp không được để trống.')
                  ->required('stall_type_id', $data['stall_type_id'], 'Vui lòng chọn loại sạp.')
                  ->required('x', $x, 'Chiều dài không được để trống.')
                  ->numeric('x', $x, 'Chiều dài phải là dạng số.')
                  ->min('x', $x, 0.01, 'Chiều dài phải lớn hơn 0.')
                  ->required('y', $y, 'Chiều rộng không được để trống.')
                  ->numeric('y', $y, 'Chiều rộng phải là dạng số.')
                  ->min('y', $y, 0.01, 'Chiều rộng phải lớn hơn 0.')
                  ->required('base_price', $data['stall_base_price'], 'Đơn giá thuê không được để trống.')
                  ->numeric('base_price', $data['stall_base_price'], 'Đơn giá thuê phải là dạng số.')
                  ->min('base_price', $data['stall_base_price'], 0, 'Đơn giá thuê không được âm.');

        $this->render->abort400($validator, 'update', 'stall');

        try {
            $stallModel = new stallModel();
            $this->render->abort404($stallModel, 'getById', $stall_id, 'update', 'stall');

            $this->render->abort400(!$stallModel->isStallCodeExists($data['stall_code'], $stall_id), 'update', 'stall', 'Mã sạp đã tồn tại trên hệ thống');

            $stallModel->update($stall_id, $data);
            general::log('update_stall', "Cập nhật sạp: {$data['stall_code']} (ID: {$stall_id}, Diện tích: {$data['stall_area_size']}m2)");
            $this->render->apiResponse('update', 'stall', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'stall');
        }
    }

    public function deleteStall() {
        $this->render->abort405('POST', 'delete', 'stall');
        $this->render->abort400('id', 'delete', 'stall');

        $stall_id = $_POST['id'];

        try {
            $stallModel = new stallModel();
            $stall = $this->render->abort404($stallModel, 'getById', $stall_id, 'delete', 'stall');

            // Nếu sạp có hợp đồng Hoạt động, chặn không cho xóa
            if ($stall['contract_status_code'] === 'active') {
                $this->render->abort400(false, 'delete', 'stall', 'Sạp đang có hợp đồng hoạt động, không thể xóa.');
            }

            // Nếu sạp có hợp đồng Khởi tạo, tiến hành xóa hợp đồng đó trước để dọn dẹp
            if ($stall['contract_status_code'] === 'draft') {
                $db = database::getInstance();
                $db->query("DELETE FROM contracts WHERE contract_stall_id = :stall_id AND contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'draft')", ['stall_id' => $stall_id]);
            }

            $stallModel->delete($stall_id);
            general::log('delete_stall', "Xóa sạp: {$stall['stall_code']} (ID: {$stall_id})");
            $this->render->apiResponse('delete', 'stall', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'delete', 'stall');
        }
    }

    public function getEmptyStalls() {
        try {
            $stallModel = new stallModel();
            $stalls = $stallModel->getAll(null, 'empty');
            $this->response($stalls);
        } catch (Exception $e) {
            $this->response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API lấy danh sách sạp khả dụng để chuyển đổi (AJAX GET)
     */
    public function getAvailableStallsForTransfer() {
        try {
            $excludeId = $_GET['exclude_id'] ?? null;
            $marketId = $_SESSION['active_market_id'] ?? null;
            $stallModel = new stallModel();
            $stalls = $stallModel->getAvailableStallsForTransfer($excludeId, $marketId);
            $this->response($stalls);
        } catch (Exception $e) {
            $this->response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API lấy danh sách tiểu thương chưa thuê sạp (AJAX GET)
     */
    public function getAvailableTraders() {
        try {
            $traderModel = new traderModel();
            $marketId = $_SESSION['active_market_id'] ?? null;
            $traders = $traderModel->getAvailableTraders($marketId);
            $this->response($traders);
        } catch (Exception $e) {
            $this->response(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * API gán sạp nhanh cho tiểu thương (AJAX POST)
     */
    public function assignStall() {
        $this->render->abort405('POST', 'create', 'contract');
        
        // Hỗ trợ đồng thời cả key stall_id/trader_id từ AJAX và contract_stall_id/contract_trader_id
        if (isset($_POST['stall_id'])) {
            $_POST['contract_stall_id'] = $_POST['stall_id'];
        }
        if (isset($_POST['trader_id'])) {
            $_POST['contract_trader_id'] = $_POST['trader_id'];
        }

        $this->render->abort400(['contract_stall_id', 'contract_trader_id'], 'create', 'contract', 'Vui lòng chọn đầy đủ sạp và tiểu thương.');

        $stallId = $_POST['contract_stall_id'];
        $traderId = $_POST['contract_trader_id'];

        try {
            $stallModel = new stallModel();
            $stall = $stallModel->getById($stallId);
            $this->render->abort400($stall && $stall['status'] === 'empty', 'create', 'contract', 'Sạp này không còn trống để cho thuê.');

            $statusModel = new statusModel();
            $draftStatusId = $statusModel->getIdByCode('contract', 'draft') ?: 27;

            $contractModel = new contractModel();
            $contractData = [
                'contract_trader_id' => $traderId,
                'contract_stall_id' => $stallId,
                'contract_name' => 'Hợp đồng thuê sạp ' . $stall['stall_code'],
                'contract_number' => 'HĐ-GAN-' . date('Ymd') . '-' . rand(100, 999),
                'contract_start_date' => date('Y-m-d'),
                'contract_end_date' => date('Y-m-d', strtotime('+1 year')),
                'contract_deposit' => $stall['stall_base_price'] * 2,
                'contract_status_id' => $draftStatusId
            ];

            $contractModel->create($contractData);
            general::log('assign_stall', "Gán nhanh sạp: {$stall['stall_code']} (ID: {$stallId}) cho tiểu thương ID: {$traderId} (Khởi tạo HĐ số: {$contractData['contract_number']})");
            
            $this->response([
                'status' => 200,
                'message' => 'Gán sạp thành công! Hợp đồng thuê sạp đang ở trạng thái Khởi tạo.'
            ]);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'contract');
        }
    }

    /**
     * API chuyển đổi sạp của tiểu thương (AJAX POST)
     */
    public function transferStall() {
        $this->render->abort405('POST', 'update', 'stall');
        $this->render->abort400(['current_stall_id', 'new_stall_id'], 'update', 'stall', 'Thiếu thông tin sạp hiện tại hoặc sạp mới.');

        $currentStallId = $_POST['current_stall_id'];
        $newStallId = $_POST['new_stall_id'];

        try {
            $stallModel = new stallModel();
            $currentStall = $stallModel->getById($currentStallId);
            $newStall = $stallModel->getById($newStallId);
            $this->render->abort400($currentStall && $newStall, 'update', 'stall', 'Không tìm thấy thông tin sạp.');

            $contractModel = new contractModel();
            $contract1 = $contractModel->getActiveOrDraftContractByStall($currentStallId);
            $this->render->abort400($contract1 !== null && $contract1 !== false, 'update', 'stall', 'Không tìm thấy hợp đồng hợp lệ cho sạp hiện tại.');

            $message = $stallModel->transferStall($currentStall, $newStall, $contract1);
            general::log('transfer_stall', "Chuyển nhượng sạp từ sạp cũ: {$currentStall['stall_code']} (ID: {$currentStallId}) sang sạp mới: {$newStall['stall_code']} (ID: {$newStallId})");

            $this->response([
                'status' => 200,
                'message' => $message
            ]);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'stall');
        }
    }


    /**
     * API dùng chung kiểm tra sự tồn tại (trùng lặp) của mã/CCCD thời gian thực (blur check)
     * GET api/checkExists?type=[stall_code|trader_code|cccd]&value=xxx&exclude_id=yyy
     */
    public function checkExists() {
        $type = $_GET['type'] ?? '';
        $value = $_GET['value'] ?? '';
        $excludeId = $_GET['exclude_id'] ?? null;

        if (empty($type) || empty($value)) {
            $this->response(['exists' => false]);
        }

        try {
            $exists = false;
            switch ($type) {
                case 'stall_code':
                    $stallModel = new stallModel();
                    $exists = $stallModel->isStallCodeExists($value, $excludeId);
                    break;
                case 'trader_code':
                    $traderModel = new traderModel();
                    $exists = $traderModel->isTraderCodeExists($value, $excludeId);
                    break;
                case 'cccd':
                    $traderModel = new traderModel();
                    $exists = $traderModel->isCccdExists($value, $excludeId);
                    break;
                case 'contract_number':
                    $db = database::getInstance();
                    $chk = $db->selectOne("SELECT COUNT(*) as count FROM contracts WHERE contract_number = :num AND contract_status_id != 99", ['num' => $value]);
                    $exists = ($chk['count'] ?? 0) > 0;
                    break;
                case 'doc_number':
                    $foodsafetyModel = new foodsafetyModel();
                    $exists = $foodsafetyModel->isDocNumberExists($value, $excludeId);
                    break;
            }
            $this->response(['exists' => $exists]);
        } catch (Exception $e) {
            $this->response(['exists' => false, 'error' => $e->getMessage()]);
        }
    }

    //--------------BẮT ĐẦU QUẢN LÝ HỢP ĐỒNG--------------//

    /**
     * API lọc và tìm kiếm hợp đồng qua AJAX
     */
    public function filterContracts() {
        $search = $_GET['q'] ?? '';
        $status = $_GET['status'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        try {
            $contractModel = new contractModel();
            $marketId = marketService::currentMarketId();
            $contracts = $contractModel->getAll($status ?: null, $search ?: null, $marketId);

            $limit = 15;
            $totalRecords = count($contracts);
            $totalPages = ceil($totalRecords / $limit);
            if ($page > $totalPages && $totalPages > 0) $page = $totalPages;
            $offset = ($page - 1) * $limit;
            $paginatedContracts = array_slice($contracts, $offset, $limit);

            ob_start();
            $contracts = $paginatedContracts;
            require DIR_TEMPLATE . '/contract/table_rows.php';
            $html = ob_get_clean();

            // Sinh HTML phân trang
            $baseUrl = BASE_URL . 'admin/contracts';
            $queryParams = [];
            if (!empty($search)) $queryParams['q'] = $search;
            if (!empty($status)) $queryParams['status'] = $status;
            $paginationHtml = general::getPaginationHtml($page, $totalPages, $baseUrl, $queryParams);

            $queryString = http_build_query([
                'q' => $search,
                'status' => $status,
                'page' => $page
            ]);

            $this->response([
                'status' => 200,
                'total' => $totalRecords,
                'html' => $html,
                'paginationHtml' => $paginationHtml,
                'queryString' => $queryString
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API thêm hợp đồng mới (AJAX POST)
     */
    public function addContract() {
        $this->render->abort405('POST', 'create', 'contract');

        $deposit = $_POST['deposit'] ?? $_POST['contract_deposit'] ?? 0;
        if (is_string($deposit)) {
            $deposit = str_replace('.', '', $deposit);
        }

        $data = [
            'contract_trader_id'     => $_POST['trader_id'] ?? $_POST['contract_trader_id'] ?? '',
            'contract_stall_id'      => $_POST['stall_id'] ?? $_POST['contract_stall_id'] ?? '',
            'contract_number'        => $_POST['contract_number'] ?? '',
            'contract_name'          => $_POST['contract_name'] ?? $_POST['name'] ?? $_POST['market_name'] ?? '',
            'contract_description'   => $_POST['contract_description'] ?? $_POST['description'] ?? $_POST['actor_description'] ?? '',
            'contract_sign_date'     => $_POST['contract_sign_date'] ?? $_POST['sign_date'] ?? '',
            'contract_start_date'    => $_POST['start_date'] ?? $_POST['contract_start_date'] ?? '',
            'contract_end_date'      => $_POST['end_date'] ?? $_POST['contract_end_date'] ?? '',
            'contract_deposit'       => $deposit,
        ];

        // Xác thực dữ liệu
        $validator = new validator();
        $validator->required('trader_id', $data['contract_trader_id'], 'Vui lòng chọn tiểu thương.')
                  ->required('stall_id', $data['contract_stall_id'], 'Vui lòng chọn sạp chợ.')
                  ->required('contract_number', $data['contract_number'], 'Số hợp đồng không được để trống.')
                  ->required('contract_name', $data['contract_name'], 'Tên hợp đồng không được để trống.')
                  ->required('contract_sign_date', $data['contract_sign_date'], 'Vui lòng nhập ngày lập hợp đồng.')
                  ->required('start_date', $data['contract_start_date'], 'Vui lòng nhập ngày bắt đầu.')
                  ->required('end_date', $data['contract_end_date'], 'Vui lòng nhập ngày hết hạn.')
                  ->required('deposit', $data['contract_deposit'], 'Vui lòng nhập tiền đặt cọc.');

        $this->render->abort400($validator, 'create', 'contract');
        $this->render->abort400(strtotime($data['contract_start_date']) <= strtotime($data['contract_end_date']), 'create', 'contract', 'Ngày bắt đầu không được lớn hơn ngày kết thúc.');

        try {
            $contractModel = new contractModel();
            
            // Kiểm tra trùng số hợp đồng
            $this->render->abort400(!$contractModel->isContractNumberExists($data['contract_number']), 'create', 'contract', 'Số hợp đồng này đã tồn tại trên hệ thống.');

            // Kiểm tra xem sạp có đang trống hay không
            $stallModel = new stallModel();
            $stall = $stallModel->getById($data['contract_stall_id']);
            $this->render->abort400($stall && $stall['status'] === 'empty', 'create', 'contract', 'Sạp được chọn không còn trống để cho thuê.');

            // Xử lý upload file PDF đính kèm (nếu có)
            if (isset($_FILES['contract_file']) && $_FILES['contract_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploader = new upload('contracts', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'], 15);
                $savedFile = $uploader->save('contract_file');
                $this->render->abort400($savedFile !== false, 'create', 'contract', 'Lỗi tải file hợp đồng: ' . ($uploader->getErrors()[0] ?? ''));
                $data['contract_file'] = $savedFile;
            }

            $contractModel->create($data);
            general::log('create_contract', "Tạo mới hợp đồng số: {$data['contract_number']} (Hợp đồng: {$data['contract_name']})");
            $this->render->apiResponse('create', 'contract', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'contract');
        }
    }

    /**
     * API gia hạn hợp đồng (AJAX POST)
     */
    public function renewContract() {
        $this->render->abort405('POST', 'update', 'contract');
        $this->render->abort400(['contract_id', 'new_end_date'], 'update', 'contract', 'Thiếu thông tin gia hạn.');

        $contractId = $_POST['contract_id'];
        $newEndDate = $_POST['new_end_date'];

        try {
            $contractModel = new contractModel();
            $contract = $this->render->abort404($contractModel, 'getById', $contractId, 'update', 'contract');

            $this->render->abort400(strtotime($newEndDate) > strtotime($contract['contract_end_date']), 'update', 'contract', 'Ngày gia hạn mới phải sau ngày hết hạn hiện tại (' . $contract['contract_end_date'] . ').');

            $contractModel->renew($contractId, $newEndDate);
            general::log('renew_contract', "Gia hạn hợp đồng số: " . ($contract['contract_number'] ?? $contractId) . " (ID: {$contractId}) đến ngày: {$newEndDate}");
            $this->render->apiResponse('update', 'contract', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'contract');
        }
    }

    public function activateContract() {
        $this->render->abort405('POST', 'update', 'contract');
        $this->render->abort400(['contract_id', 'contract_number', 'contract_sign_date', 'contract_start_date', 'contract_end_date', 'contract_deposit'], 'update', 'contract');

        $contractId = $_POST['contract_id'];
        $deposit = $_POST['contract_deposit'] ?? 0;
        if (is_string($deposit)) {
            $deposit = str_replace('.', '', $deposit);
        }

        $data = [
            'contract_number'     => $_POST['contract_number'],
            'contract_sign_date'  => $_POST['contract_sign_date'],
            'contract_start_date' => $_POST['contract_start_date'],
            'contract_end_date'   => $_POST['contract_end_date'],
            'contract_deposit'    => $deposit
        ];

        try {
            $contractModel = new contractModel();
            $this->render->abort404($contractModel, 'getById', $contractId, 'update', 'contract');

            // Kiểm tra trùng lặp số hợp đồng
            $this->render->abort400(!$contractModel->isContractNumberExists($data['contract_number'], $contractId), 'update', 'contract', 'Số hợp đồng này đã tồn tại trên hệ thống.');

            // Kiểm tra ngày bắt đầu và kết thúc
            $this->render->abort400(strtotime($data['contract_end_date']) > strtotime($data['contract_start_date']), 'update', 'contract', 'Ngày kết thúc hợp đồng phải sau ngày bắt đầu.');

            // Xử lý upload nhiều file đính kèm (nếu có) và gỡ file
            $remaining = json_decode($_POST['remaining_files'] ?? '[]', true);
            if (!is_array($remaining)) {
                $remaining = [];
            }

            $newUploadedFiles = [];
            if (isset($_FILES['contract_files']) && !empty($_FILES['contract_files']['name'][0])) {
                $filesCount = count($_FILES['contract_files']['name']);
                for ($i = 0; $i < $filesCount; $i++) {
                    if ($_FILES['contract_files']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    
                    $tempKey = 'temp_contract_file_' . $i;
                    $_FILES[$tempKey] = [
                        'name' => $_FILES['contract_files']['name'][$i],
                        'type' => $_FILES['contract_files']['type'][$i],
                        'tmp_name' => $_FILES['contract_files']['tmp_name'][$i],
                        'error' => $_FILES['contract_files']['error'][$i],
                        'size' => $_FILES['contract_files']['size'][$i]
                    ];
                    
                    $uploader = new upload('contracts', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'], 15);
                    $savedFile = $uploader->save($tempKey);
                    
                    unset($_FILES[$tempKey]);
                    
                    if ($savedFile === false) {
                        $this->render->abort400(false, 'update', 'contract', 'Lỗi tải file hợp đồng: ' . ($uploader->getErrors()[0] ?? ''));
                    }
                    
                    $newUploadedFiles[] = $savedFile;
                }
            }

            $allFiles = array_merge($remaining, $newUploadedFiles);
            $data['contract_file'] = !empty($allFiles) ? json_encode($allFiles, JSON_UNESCAPED_UNICODE) : null;

            $contractModel->activateDraftContract($contractId, $data);
            general::log('activate_contract', "Kích hoạt hợp đồng số: {$data['contract_number']} (ID: {$contractId})");
            
            $this->response([
                'status' => 200,
                'message' => 'Kích hoạt hợp đồng thành công và đã chuyển sang trạng thái Hoạt động!'
            ]);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'contract');
        }
    }

    /**
     * API thanh lý hợp đồng (AJAX POST)
     */
    public function liquidateContract() {
        $this->render->abort405('POST', 'update', 'contract');
        $this->render->abort400('contract_id', 'update', 'contract');

        $contractId = $_POST['contract_id'];

        try {
            $contractModel = new contractModel();
            $this->render->abort404($contractModel, 'getById', $contractId, 'update', 'contract');

            $contract = $contractModel->getById($contractId);
            $contractModel->liquidate($contractId);
            general::log('liquidate_contract', "Thanh lý hợp đồng số: " . ($contract['contract_number'] ?? $contractId) . " (ID: {$contractId})");
            $this->render->apiResponse('update', 'contract', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'contract');
        }
    }

    /**
     * API chấm dứt hợp đồng trước hạn (AJAX POST)
     */
    public function terminateContract() {
        $this->render->abort405('POST', 'update', 'contract');
        $this->render->abort400('contract_id', 'update', 'contract');

        $contractId = $_POST['contract_id'];

        try {
            $contractModel = new contractModel();
            $this->render->abort404($contractModel, 'getById', $contractId, 'update', 'contract');

            $contract = $contractModel->getById($contractId);
            $contractModel->terminate($contractId);
            general::log('terminate_contract', "Chấm dứt trước hạn hợp đồng số: " . ($contract['contract_number'] ?? $contractId) . " (ID: {$contractId})");
            $this->render->apiResponse('update', 'contract', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'contract');
        }
    }

    /**
     * API xóa mềm hợp đồng (AJAX POST - status_id = 99)
     */
    public function deleteContract() {
        $this->render->abort405('POST', 'delete', 'contract');
        $this->render->abort400('contract_id', 'delete', 'contract');

        $contractId = $_POST['contract_id'];

        try {
            $contractModel = new contractModel();
            $this->render->abort404($contractModel, 'getById', $contractId, 'delete', 'contract');

            $contract = $contractModel->getById($contractId);
            $contractModel->softDelete($contractId);
            general::log('delete_contract', "Xóa mềm hợp đồng số: " . ($contract['contract_number'] ?? $contractId) . " (ID: {$contractId})");
            $this->render->apiResponse('delete', 'contract', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'delete', 'contract');
        }
    }

    /**
     * API tái kích hoạt hợp đồng (AJAX POST)
     */
    public function reactivateContract() {
        $this->render->abort405('POST', 'update', 'contract');
        $this->render->abort400('contract_id', 'update', 'contract');

        $contractId = $_POST['contract_id'];

        try {
            $contractModel = new contractModel();
            $this->render->abort404($contractModel, 'getById', $contractId, 'update', 'contract');

            $contract = $contractModel->getById($contractId);
            $contractModel->reactivate($contractId);
            general::log('reactivate_contract', "Tái kích hoạt hợp đồng số: " . ($contract['contract_number'] ?? $contractId) . " (ID: {$contractId})");
            $this->render->apiResponse('update', 'contract', true, 'Tái kích hoạt hợp đồng thành công!');
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'contract');
        }
    }

    /**
     * API lấy lịch sử chỉnh sửa hợp đồng (AJAX GET)
     */
    public function getContractHistory() {
        $contractId = $_GET['contract_id'] ?? 0;
        if (!$contractId) {
            $this->response(['status' => 400, 'message' => 'Thiếu contract_id'], 400);
        }

        try {
            $contractModel = new contractModel();
            $history = $contractModel->getHistory($contractId);
            $this->response([
                'status' => 200,
                'data' => $history
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API chỉnh sửa thông tin hợp đồng (AJAX POST)
     */
    public function editContract() {
        $this->render->abort405('POST', 'update', 'contract');
        $this->render->abort400('contract_id', 'update', 'contract');

        $contractId = $_POST['contract_id'];
        $deposit = $_POST['contract_deposit'] ?? $_POST['deposit'] ?? 0;
        if (is_string($deposit)) {
            $deposit = str_replace('.', '', $deposit);
        }

        $data = [
            'contract_number'     => $_POST['contract_number'] ?? '',
            'contract_name'       => $_POST['contract_name'] ?? $_POST['name'] ?? '',
            'contract_sign_date'  => $_POST['contract_sign_date'] ?? $_POST['sign_date'] ?? '',
            'contract_start_date' => $_POST['contract_start_date'] ?? $_POST['start_date'] ?? '',
            'contract_end_date'   => $_POST['contract_end_date'] ?? $_POST['end_date'] ?? '',
            'contract_deposit'    => $deposit,
            'contract_description'=> $_POST['contract_description'] ?? $_POST['description'] ?? '',
        ];

        $validator = new validator();
        $validator->required('contract_number', $data['contract_number'], 'Số hợp đồng không được để trống.')
                  ->required('contract_name', $data['contract_name'], 'Tên hợp đồng không được để trống.')
                  ->required('contract_sign_date', $data['contract_sign_date'], 'Vui lòng nhập ngày lập hợp đồng.')
                  ->required('contract_start_date', $data['contract_start_date'], 'Vui lòng chọn ngày bắt đầu.')
                  ->required('contract_end_date', $data['contract_end_date'], 'Vui lòng chọn ngày kết thúc.');

        $this->render->abort400($validator, 'update', 'contract');

        try {
            $contractModel = new contractModel();

            // Chặn sửa hợp đồng ở trạng thái thanh lý, chấm dứt, hết hạn
            $contract = $contractModel->getById($contractId);
            if ($contract) {
                $statusId = (int)($contract['contract_status_id'] ?? 0);
                if (in_array($statusId, [12, 13, 14])) {
                    $this->render->apiResponse('update', 'contract', false, 'Hợp đồng đã ở trạng thái thanh lý, chấm dứt hoặc hết hạn, không được phép sửa.', 400);
                }
            }
            
            // Kiểm tra trùng số hợp đồng (loại trừ hợp đồng hiện tại)
            $this->render->abort400(!$contractModel->isContractNumberExists($data['contract_number'], $contractId), 'update', 'contract', 'Số hợp đồng này đã tồn tại trên hệ thống.');
            
            // Kiểm tra ngày
            $this->render->abort400(strtotime($data['contract_end_date']) > strtotime($data['contract_start_date']), 'update', 'contract', 'Ngày kết thúc hợp đồng phải sau ngày bắt đầu.');

            // Xử lý upload nhiều file đính kèm (nếu có) và gỡ file
            $remaining = json_decode($_POST['remaining_files'] ?? '[]', true);
            if (!is_array($remaining)) {
                $remaining = [];
            }

            $newUploadedFiles = [];
            if (isset($_FILES['contract_files']) && !empty($_FILES['contract_files']['name'][0])) {
                $filesCount = count($_FILES['contract_files']['name']);
                for ($i = 0; $i < $filesCount; $i++) {
                    if ($_FILES['contract_files']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    
                    $tempKey = 'temp_contract_file_' . $i;
                    $_FILES[$tempKey] = [
                        'name' => $_FILES['contract_files']['name'][$i],
                        'type' => $_FILES['contract_files']['type'][$i],
                        'tmp_name' => $_FILES['contract_files']['tmp_name'][$i],
                        'error' => $_FILES['contract_files']['error'][$i],
                        'size' => $_FILES['contract_files']['size'][$i]
                    ];
                    
                    $uploader = new upload('contracts', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'], 15);
                    $savedFile = $uploader->save($tempKey);
                    
                    unset($_FILES[$tempKey]);
                    
                    if ($savedFile === false) {
                        $this->render->abort400(false, 'update', 'contract', 'Lỗi tải file hợp đồng: ' . ($uploader->getErrors()[0] ?? ''));
                    }
                    
                    $newUploadedFiles[] = $savedFile;
                }
            }

            $allFiles = array_merge($remaining, $newUploadedFiles);
            $data['contract_file'] = !empty($allFiles) ? json_encode($allFiles, JSON_UNESCAPED_UNICODE) : null;

            $contractModel->updateContractDetails($contractId, $data);
            general::log('update_contract', "Cập nhật hợp đồng số: {$data['contract_number']} (ID: {$contractId}, Tên: {$data['contract_name']})");
            $this->response([
                'status' => 200,
                'message' => 'Cập nhật thông tin hợp đồng thành công!'
            ]);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'contract');
        }
    }

    /**
     * API thêm phụ lục hợp đồng (AJAX POST)
     */
    public function addContractAppendix() {
        $this->render->abort405('POST', 'create', 'appendix');

        $data = [
            'contract_id'     => $_POST['contract_id'] ?? '',
            'appendix_number' => $_POST['appendix_number'] ?? '',
            'market_name'     => $_POST['name'] ?? ($_POST['market_name'] ?? ''),
            'sign_date'       => $_POST['sign_date'] ?? '',
            'effect_date'     => $_POST['effect_date'] ?? '',
            'content'         => $_POST['content'] ?? '',
        ];

        $validator = new validator();
        $validator->required('contract_id', $data['contract_id'], 'Thiếu ID hợp đồng.')
                  ->required('appendix_number', $data['appendix_number'], 'Số phụ lục không được để trống.')
                  ->required('market_name', $data['market_name'], 'Tên phụ lục không được để trống.')
                  ->required('sign_date', $data['sign_date'], 'Vui lòng nhập ngày ký.')
                  ->required('effect_date', $data['effect_date'], 'Vui lòng nhập ngày có hiệu lực.')
                  ->required('content', $data['content'], 'Nội dung phụ lục không được để trống.');

        $this->render->abort400($validator, 'create', 'appendix');

        try {
            $contractModel = new contractModel();
            // Kiểm tra trùng số phụ lục
            $this->render->abort400(!$contractModel->isAppendixNumberExists($data['appendix_number']), 'create', 'appendix', 'Số phụ lục này đã tồn tại trên hệ thống.');

            // Xử lý upload nhiều file phụ lục (nếu có)
            $uploadedFiles = [];
            if (isset($_FILES['appendix_files']) && !empty($_FILES['appendix_files']['name'][0])) {
                $filesCount = count($_FILES['appendix_files']['name']);
                for ($i = 0; $i < $filesCount; $i++) {
                    if ($_FILES['appendix_files']['error'][$i] === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    
                    // Tạo một mục giả lập trong $_FILES cho từng tệp để tái sử dụng upload helper
                    $tempKey = 'temp_appendix_file_' . $i;
                    $_FILES[$tempKey] = [
                        'name' => $_FILES['appendix_files']['name'][$i],
                        'type' => $_FILES['appendix_files']['type'][$i],
                        'tmp_name' => $_FILES['appendix_files']['tmp_name'][$i],
                        'error' => $_FILES['appendix_files']['error'][$i],
                        'size' => $_FILES['appendix_files']['size'][$i]
                    ];
                    
                    $uploader = new upload('contracts/appendices', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'], 15);
                    $savedFile = $uploader->save($tempKey);
                    
                    // Dọn dẹp tệp tạm trong $_FILES
                    unset($_FILES[$tempKey]);
                    
                    if ($savedFile === false) {
                        $this->render->abort400(false, 'create', 'appendix', 'Lỗi tải file phụ lục: ' . ($uploader->getErrors()[0] ?? ''));
                    }
                    
                    $uploadedFiles[] = $savedFile;
                }
            }

            if (!empty($uploadedFiles)) {
                $data['file'] = json_encode($uploadedFiles, JSON_UNESCAPED_UNICODE);
            } else {
                $data['file'] = null;
            }

            $contractModel->addAppendix($data);
            general::log('create_appendix', "Thêm phụ lục hợp đồng số: {$data['appendix_number']} cho hợp đồng ID: {$data['contract_id']} (Tên phụ lục: {$data['market_name']})");
            $this->render->apiResponse('create', 'appendix', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'appendix');
        }
    }

    /**
     * API lấy danh sách phụ lục hợp đồng (AJAX GET)
     */
    public function getContractAppendices() {
        $contractId = $_GET['contract_id'] ?? null;
        if (!$contractId) {
            $this->response(['status' => 400, 'message' => 'Thiếu ID hợp đồng.'], 400);
        }

        try {
            $contractModel = new contractModel();
            $appendices = $contractModel->getAppendices($contractId);
            
            // Đồng bộ định dạng cột trả về để khớp với UI JS
            $mapped = array_map(function($app) {
                return [
                    'appendix_id'          => $app['appendix_id'],
                    'appendix_contract_id' => $app['appendix_contract_id'],
                    'appendix_number'      => $app['appendix_number'],
                    'name'                 => $app['appendix_name'],
                    'sign_date'            => $app['appendix_sign_date'],
                    'effect_date'          => $app['appendix_effect_date'],
                    'content'              => $app['appendix_content'],
                    'file'                 => $app['appendix_file'],
                    'created_at'           => $app['appendix_created_at']
                ];
            }, $appendices);

            $this->response([
                'status' => 200,
                'data' => $mapped
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //--------------BẮT ĐẦU QUẢN LÝ AN TOÀN THỰC PHẨM (ATTP)--------------//

    /**
     * API lọc và tìm kiếm giấy tờ vệ sinh ATTP qua AJAX
     */
    public function filterCertificates() {
        $search = $_GET['q'] ?? '';
        $docType = $_GET['doc_type'] ?? '';
        $status = $_GET['status'] ?? '';

        try {
            $foodsafetyModel = new foodsafetyModel();
            $foodsafetyModel->autoUpdateExpiryStatus();
            $marketId = marketService::currentMarketId();
            $certificates = $foodsafetyModel->getCertificates(null, $docType ?: null, $status ?: null, $search ?: null, $marketId);

            ob_start();
            require DIR_TEMPLATE . '/foodsafety/table_rows.php';
            $html = ob_get_clean();

            $queryString = http_build_query([
                'q' => $search,
                'doc_type' => $docType,
                'status' => $status
            ]);

            $this->response([
                'status' => 200,
                'total' => count($certificates),
                'html' => $html,
                'queryString' => $queryString
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API lấy chi tiết một giấy tờ vệ sinh ATTP (AJAX GET)
     */
    public function getCertificateDetail() {
        $user_market_market_id = $_GET['id'] ?? $_GET['user_market_market_id'] ?? null;
        if (!$user_market_market_id) {
            $this->response(['status' => 400, 'message' => 'Thiếu ID giấy tờ.'], 400);
        }
        try {
            $foodsafetyModel = new foodsafetyModel();
            $cert = $foodsafetyModel->getById($user_market_market_id);
            if (!$cert) {
                $this->response(['status' => 404, 'message' => 'Không tìm thấy giấy tờ.'], 404);
            }
            $this->response([
                'status' => 200,
                'data' => $cert
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API thêm giấy tờ vệ sinh ATTP mới (AJAX POST)
     */
    /**
     * API thêm giấy tờ vệ sinh ATTP mới (AJAX POST)
     */
    public function addCertificate() {
        $this->render->abort405('POST', 'create', 'certificate');

        $data = [
            'attp_trader_id'   => $_POST['trader_id'] ?? '',
            'attp_doc_type_id' => $_POST['doc_type_id'] ?? '',
            'attp_doc_number'  => $_POST['doc_number'] ?? '',
            'attp_name'        => $_POST['attp_name'] ?? ($_POST['market_name'] ?? ''),
            'attp_description' => $_POST['attp_description'] ?? ($_POST['actor_description'] ?? ''),
            'attp_issuer'      => $_POST['issuer'] ?? '',
            'attp_issue_date'  => $_POST['issue_date'] ?? '',
            'attp_expiry_date' => $_POST['expiry_date'] ?? '',
        ];

        // Validation các trường bắt buộc
        $validator = new validator();
        $validator->required('trader_id', $data['attp_trader_id'], 'Bạn phải chọn tiểu thương.')
                  ->required('doc_type_id', $data['attp_doc_type_id'], 'Bạn phải chọn loại giấy tờ.')
                  ->required('doc_number', $data['attp_doc_number'], 'Bạn phải nhập số giấy tờ/chứng nhận.')
                  ->required('attp_name', $data['attp_name'], 'Bạn phải nhập tên giấy tờ.')
                  ->required('issue_date', $data['attp_issue_date'], 'Bạn phải nhập ngày hiệu lực bắt đầu.')
                  ->required('expiry_date', $data['attp_expiry_date'], 'Bạn phải nhập ngày hiệu lực kết thúc.');

        $this->render->abort400($validator, 'create', 'certificate');
        $this->render->abort400(strtotime($data['attp_issue_date']) <= strtotime($data['attp_expiry_date']), 'create', 'certificate', 'Ngày hiệu lực bắt đầu không được lớn hơn ngày hết hạn.');

        try {
            $foodsafetyModel = new foodsafetyModel();
            $this->render->abort400(!$foodsafetyModel->isDocNumberExists($data['attp_doc_number']), 'create', 'certificate', 'Số giấy tờ/chứng nhận này đã tồn tại trên hệ thống.');

            // Xử lý upload file đính kèm (nếu có)
            if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploader = new upload('foodsafety', ['jpg', 'jpeg', 'png', 'pdf'], 15);
                $savedFile = $uploader->save('certificate_file');
                $this->render->abort400($savedFile !== false, 'create', 'certificate', 'Lỗi tải file đính kèm: ' . ($uploader->getErrors()[0] ?? ''));
                $data['attp_file'] = $savedFile;
            }

            $foodsafetyModel->createCertificate($data);
            general::log('create_certificate', "Thêm chứng nhận ATTP mới: {$data['attp_name']} (Số: {$data['attp_doc_number']}, ID tiểu thương: {$data['attp_trader_id']})");
            $this->render->apiResponse('create', 'certificate', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'certificate');
        }
    }

    /**
     * API cập nhật giấy tờ vệ sinh ATTP (AJAX POST)
     */
    public function editCertificate() {
        $this->render->abort405('POST', 'update', 'certificate');
        $this->render->abort400('id', 'update', 'certificate');

        $attp_id = $_POST['id'];

        $data = [
            'attp_trader_id'   => $_POST['trader_id'] ?? '',
            'attp_doc_type_id' => $_POST['doc_type_id'] ?? '',
            'attp_doc_number'  => $_POST['doc_number'] ?? '',
            'attp_name'        => $_POST['attp_name'] ?? ($_POST['market_name'] ?? ''),
            'attp_description' => $_POST['attp_description'] ?? ($_POST['actor_description'] ?? ''),
            'attp_issuer'      => $_POST['issuer'] ?? '',
            'attp_issue_date'  => $_POST['issue_date'] ?? '',
            'attp_expiry_date' => $_POST['expiry_date'] ?? '',
        ];

        // Validation các trường bắt buộc
        $validator = new validator();
        $validator->required('trader_id', $data['attp_trader_id'], 'Bạn phải chọn tiểu thương.')
                  ->required('doc_type_id', $data['attp_doc_type_id'], 'Bạn phải chọn loại giấy tờ.')
                  ->required('doc_number', $data['attp_doc_number'], 'Bạn phải nhập số giấy tờ/chứng nhận.')
                  ->required('attp_name', $data['attp_name'], 'Bạn phải nhập tên giấy tờ.')
                  ->required('issue_date', $data['attp_issue_date'], 'Bạn phải nhập ngày hiệu lực bắt đầu.')
                  ->required('expiry_date', $data['attp_expiry_date'], 'Bạn phải nhập ngày hiệu lực kết thúc.');

        $this->render->abort400($validator, 'update', 'certificate');
        $this->render->abort400(strtotime($data['attp_issue_date']) <= strtotime($data['attp_expiry_date']), 'update', 'certificate', 'Ngày hiệu lực bắt đầu không được lớn hơn ngày hết hạn.');

        try {
            $foodsafetyModel = new foodsafetyModel();
            $this->render->abort400(!$foodsafetyModel->isDocNumberExists($data['attp_doc_number'], $attp_id), 'update', 'certificate', 'Số giấy tờ/chứng nhận này đã tồn tại trên hệ thống.');

            // Xử lý upload file đính kèm (nếu có)
            if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] !== UPLOAD_ERR_NO_FILE) {
                $uploader = new upload('foodsafety', ['jpg', 'jpeg', 'png', 'pdf'], 15);
                $savedFile = $uploader->save('certificate_file');
                $this->render->abort400($savedFile !== false, 'update', 'certificate', 'Lỗi tải file đính kèm: ' . ($uploader->getErrors()[0] ?? ''));
                $data['attp_file'] = $savedFile;
            }

            // Tự động kiểm tra hạn để cập nhật status_id
            $today = date('Y-m-d');
            $statusModel = new statusModel();
            if ($data['attp_expiry_date'] < $today) {
                $data['attp_status_id'] = $statusModel->getIdByCode('attp', 'expired');
            } else {
                $data['attp_status_id'] = $statusModel->getIdByCode('attp', 'valid');
            }

            $foodsafetyModel->updateCertificate($attp_id, $data);
            general::log('update_certificate', "Cập nhật chứng nhận ATTP: {$data['attp_name']} (Số: {$data['attp_doc_number']}, ID: {$attp_id})");
            $this->render->apiResponse('update', 'certificate', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'certificate');
        }
    }

    /**
     * API xóa mềm giấy tờ vệ sinh ATTP (AJAX POST)
     */
    public function deleteCertificate() {
        $this->render->abort405('POST', 'delete', 'certificate');
        $this->render->abort400('id', 'delete', 'certificate');

        $attp_id = $_POST['id'];

        try {
            $foodsafetyModel = new foodsafetyModel();
            $this->render->abort404($foodsafetyModel, 'getById', $attp_id, 'delete', 'certificate');

            $cert = $foodsafetyModel->getById($attp_id);
            $foodsafetyModel->deleteCertificate($attp_id);
            general::log('delete_certificate', "Xóa chứng nhận ATTP: " . ($cert['attp_name'] ?? $attp_id) . " (Số: " . ($cert['attp_doc_number'] ?? '') . ", ID: {$attp_id})");
            $this->render->apiResponse('delete', 'certificate', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'delete', 'certificate');
        }
    }

    //--------------BẮT ĐẦU SƠ ĐỒ CHỢ TƯƠNG TÁC--------------//

    /**
     * API lấy danh sách các phần tử bản đồ (AJAX GET)
     */
    public function getMapElements() {
        try {
            $mapModel = new mapModel();
            $elements = $mapModel->getElements();
            $this->response([
                'status' => 200,
                'data' => $elements
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API lấy cấu trúc cây phân cấp sạp chợ (AJAX GET)
     */
    public function getStallTree() {
        try {
            $mapModel = new mapModel();
            $tree = $mapModel->getStallTree();
            $this->response([
                'status' => 200,
                'data' => $tree
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API lấy thông tin chi tiết đầy đủ của sạp, hợp đồng và tiểu thương (AJAX GET)
     */
    public function getStallDetails() {
        $stallId = $_GET['id'] ?? $_GET['user_market_market_id'] ?? 0;
        if (!$stallId) {
            $this->response(['status' => 400, 'message' => 'Thiếu ID sạp chợ.'], 400);
        }

        try {
            $mapModel = new mapModel();
            $details = $mapModel->getFullDetails($stallId);
            if (!$details) {
                $this->response(['status' => 404, 'message' => 'Không tìm thấy sạp chợ hoặc không có quyền truy cập.'], 404);
            }
            $this->response([
                'status' => 200,
                'data' => $details
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API Thay đổi active_market_id trong Session (AJAX GET)
     */
    public function changeMarketScope() {
        $marketId = (int)($_GET['id'] ?? $_GET['user_market_market_id'] ?? 0);

        // Nếu chuyển về Trang Tổng (user_market_market_id = 0)
        if ($marketId === 0) {
            if (marketService::isSuperAdmin() || marketService::isAdminMarket()) {
                session::set('active_market_id', 0);
                session::delete('accessible_market_ids');
                $this->response([
                    'status' => 200,
                    'message' => 'Chuyển đổi sang Trang tổng thành công!'
                ]);
            } else {
                $this->response(['status' => 403, 'message' => 'Bạn không có quyền xem Trang tổng hợp.'], 403);
            }
        }

        // Kiểm tra xem user có quyền truy cập chợ này không
        $accessible = marketService::getAccessibleMarketIds();
        if (!in_array((int)$marketId, $accessible) && !marketService::isSuperAdmin()) {
            $this->response(['status' => 403, 'message' => 'Không có quyền truy cập chợ này.'], 403);
        }

        // Lưu vào Session
        session::set('active_market_id', (int)$marketId);
        
        // Reset cache danh sách chợ
        session::delete('accessible_market_ids');

        $this->response([
            'status' => 200,
            'message' => 'Chuyển đổi chợ thành công!'
        ]);
    }



    /**
     * API lưu cấu hình các phần tử bản đồ (AJAX POST)
     */
    public function saveMapElements() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->response(['status' => 405, 'message' => 'Phương thức không được hỗ trợ.'], 405);
        }

        // Đọc dữ liệu JSON gửi lên
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if ($data === null || !isset($data['elements'])) {
            $this->response(['status' => 400, 'message' => 'Dữ liệu sơ đồ không hợp lệ.'], 400);
        }

        try {
            $mapModel = new mapModel();
            $mapModel->saveElements($data['elements']);
            general::log('save_map', "Lưu/Cấu hình lại sơ đồ thiết kế bản đồ chợ (Số phần tử: " . count($data['elements']) . ")");
            
            $this->response([
                'status' => 200,
                'message' => 'Lưu sơ đồ chợ thành công!'
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 500,
                'message' => 'Lưu sơ đồ thất bại: ' . $e->getMessage()
            ], 500);
        }
    }

    //--------------KẾT THÚC SƠ ĐỒ CHỢ TƯƠNG TÁC--------------//

    //--------------BẮT ĐẦU QUẢN LÝ DANH MỤC--------------//

    /**
     * API lấy chi tiết một danh mục (AJAX GET)
     */
    public function getCategoryDetail() {
        $this->render->abort405('GET', 'view', 'category');
        
        $user_market_market_id = $_GET['id'] ?? $_GET['user_market_market_id'] ?? '';
        $type = $_GET['type'] ?? '';
        
        $this->render->abort400($user_market_market_id && $type, 'view', 'category', 'Thiếu ID danh mục hoặc Loại danh mục.');

        try {
            $categoryModel = new categoryModel();
            $item = $categoryModel->getItemById($type, $user_market_market_id);
            if (!$item) {
                $this->httpAbortResponse('view', 'category', false, 'not_found', 404);
            }

            $this->response([
                'status' => 200,
                'data' => $item
            ]);
        } catch (Exception $e) {
            $this->render->abort500($e, 'view', 'category');
        }
    }

    /**
     * API thêm danh mục mới (AJAX POST)
     */
    public function addCategory() {
        $this->render->abort405('POST', 'create', 'category');
        $this->render->abort400('type', 'create', 'category', 'Thiếu loại danh mục.');

        $type = $_POST['type'];
        $categoryModel = new categoryModel();

        try {
            $data = [];
            $validator = new validator();

            if ($type === 'area') {
                $data = [
                    'area_name'        => $_POST['area_name'] ?? '',
                    'area_block'       => $_POST['area_block'] ?? '',
                    'area_lot'         => $_POST['area_lot'] ?? '',
                    'area_description' => $_POST['area_description'] ?? ''
                ];
                $validator->required('area_name', $data['area_name'], 'Tên khu vực không được để trống.');
                $this->render->abort400($validator, 'create', 'category');
                
                // Kiểm tra trùng tên
                $this->render->abort400(!$categoryModel->isCodeExists('area', 'area_name', $data['area_name']), 'create', 'category', 'Tên khu vực này đã tồn tại.');

            } elseif ($type === 'stall_type') {
                $data = [
                    'stall_type_code'        => $_POST['stall_type_code'] ?? '',
                    'stall_type_name'        => $_POST['stall_type_name'] ?? '',
                    'stall_type_description' => $_POST['stall_type_description'] ?? ''
                ];
                $validator->required('stall_type_code', $data['stall_type_code'], 'Mã loại sạp không được để trống.')
                          ->required('stall_type_name', $data['stall_type_name'], 'Tên loại sạp không được để trống.');
                 $this->render->abort400($validator, 'create', 'category');

                $this->render->abort400(!$categoryModel->isCodeExists('stall_type', 'stall_type_code', $data['stall_type_code']), 'create', 'category', 'Mã loại sạp này đã tồn tại.');

            } elseif ($type === 'business_line') {
                $data = [
                    'line_code'        => $_POST['line_code'] ?? '',
                    'line_name'        => $_POST['line_name'] ?? '',
                    'line_description' => $_POST['line_description'] ?? ''
                ];
                $validator->required('line_code', $data['line_code'], 'Mã ngành hàng không được để trống.')
                          ->required('line_name', $data['line_name'], 'Tên ngành hàng không được để trống.');
                $this->render->abort400($validator, 'create', 'category');

                $this->render->abort400(!$categoryModel->isCodeExists('business_line', 'line_code', $data['line_code']), 'create', 'category', 'Mã ngành hàng này đã tồn tại.');

            } elseif ($type === 'document_type') {
                $data = [
                    'doc_type_code'        => $_POST['doc_type_code'] ?? '',
                    'doc_type_name'        => $_POST['doc_type_name'] ?? '',
                    'doc_type_description' => $_POST['doc_type_description'] ?? ''
                ];
                $validator->required('doc_type_code', $data['doc_type_code'], 'Mã loại giấy tờ không được để trống.')
                          ->required('doc_type_name', $data['doc_type_name'], 'Tên loại giấy tờ không được để trống.');
                $this->render->abort400($validator, 'create', 'category');

                $this->render->abort400(!$categoryModel->isCodeExists('document_type', 'doc_type_code', $data['doc_type_code']), 'create', 'category', 'Mã loại giấy tờ này đã tồn tại.');
            } else {
                $this->render->abort400(false, 'create', 'category', 'Loại danh mục không hợp lệ.');
            }

            $itemId = $categoryModel->createItem($type, $data);
            
            $categoryNames = [
                'area' => 'Khu vực',
                'stall_type' => 'Loại sạp',
                'business_line' => 'Ngành hàng',
                'document_type' => 'Loại giấy tờ'
            ];
            $friendlyTypeName = $categoryNames[$type] ?? $type;
            $catName = $data['area_name'] ?? $data['stall_type_name'] ?? $data['line_name'] ?? $data['doc_type_name'] ?? '';
            general::log('create_category', "Thêm mới danh mục {$friendlyTypeName}: {$catName} (ID: {$itemId})");

            $this->response([
                'status' => 200,
                'message' => 'Thêm mới danh mục thành công!',
                'user_market_market_id' => $itemId
            ]);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'category');
        }
    }

    /**
     * API cập nhật danh mục (AJAX POST)
     */
    public function editCategory() {
        $this->render->abort405('POST', 'update', 'category');
        
        $user_market_market_id = $_POST['id'] ?? $_POST['user_market_market_id'] ?? '';
        $type = $_POST['type'] ?? '';
        $this->render->abort400($user_market_market_id && $type, 'update', 'category', 'Thiếu thông tin danh mục.');
        $categoryModel = new categoryModel();

        // Kiểm tra tồn tại
        $item = $categoryModel->getItemById($type, $user_market_market_id);
        if (!$item) {
            $this->httpAbortResponse('update', 'category', false, 'not_found', 404);
        }

        try {
            $data = [];
            $validator = new validator();

            if ($type === 'area') {
                $data = [
                    'area_name'        => $_POST['area_name'] ?? '',
                    'area_block'       => $_POST['area_block'] ?? '',
                    'area_lot'         => $_POST['area_lot'] ?? '',
                    'area_description' => $_POST['area_description'] ?? ''
                ];
                $validator->required('area_name', $data['area_name'], 'Tên khu vực không được để trống.');
                $this->render->abort400($validator, 'update', 'category');
                
                $this->render->abort400(!$categoryModel->isCodeExists('area', 'area_name', $data['area_name'], $user_market_market_id), 'update', 'category', 'Tên khu vực này đã tồn tại.');

            } elseif ($type === 'stall_type') {
                $data = [
                    'stall_type_code'        => $_POST['stall_type_code'] ?? '',
                    'stall_type_name'        => $_POST['stall_type_name'] ?? '',
                    'stall_type_description' => $_POST['stall_type_description'] ?? ''
                ];
                $validator->required('stall_type_code', $data['stall_type_code'], 'Mã loại sạp không được để trống.')
                          ->required('stall_type_name', $data['stall_type_name'], 'Tên loại sạp không được để trống.');
                $this->render->abort400($validator, 'update', 'category');

                $this->render->abort400(!$categoryModel->isCodeExists('stall_type', 'stall_type_code', $data['stall_type_code'], $user_market_market_id), 'update', 'category', 'Mã loại sạp này đã tồn tại.');

            } elseif ($type === 'business_line') {
                $data = [
                    'line_code'        => $_POST['line_code'] ?? '',
                    'line_name'        => $_POST['line_name'] ?? '',
                    'line_description' => $_POST['line_description'] ?? ''
                ];
                $validator->required('line_code', $data['line_code'], 'Mã ngành hàng không được để trống.')
                          ->required('line_name', $data['line_name'], 'Tên ngành hàng không được để trống.');
                $this->render->abort400($validator, 'update', 'category');

                $this->render->abort400(!$categoryModel->isCodeExists('business_line', 'line_code', $data['line_code'], $user_market_market_id), 'update', 'category', 'Mã ngành hàng này đã tồn tại.');

            } elseif ($type === 'document_type') {
                $data = [
                    'doc_type_code'        => $_POST['doc_type_code'] ?? '',
                    'doc_type_name'        => $_POST['doc_type_name'] ?? '',
                    'doc_type_description' => $_POST['doc_type_description'] ?? ''
                ];
                $validator->required('doc_type_code', $data['doc_type_code'], 'Mã loại giấy tờ không được để trống.')
                          ->required('doc_type_name', $data['doc_type_name'], 'Tên loại giấy tờ không được để trống.');
                $this->render->abort400($validator, 'update', 'category');

                $this->render->abort400(!$categoryModel->isCodeExists('document_type', 'doc_type_code', $data['doc_type_code'], $user_market_market_id), 'update', 'category', 'Mã loại giấy tờ này đã tồn tại.');
            } else {
                $this->render->abort400(false, 'update', 'category', 'Loại danh mục không hợp lệ.');
            }

            $categoryModel->updateItem($type, $user_market_market_id, $data);
            
            $categoryNames = [
                'area' => 'Khu vực',
                'stall_type' => 'Loại sạp',
                'business_line' => 'Ngành hàng',
                'document_type' => 'Loại giấy tờ'
            ];
            $friendlyTypeName = $categoryNames[$type] ?? $type;
            $catName = $data['area_name'] ?? $data['stall_type_name'] ?? $data['line_name'] ?? $data['doc_type_name'] ?? '';
            general::log('update_category', "Cập nhật danh mục {$friendlyTypeName}: {$catName} (ID: {$user_market_market_id})");

            $this->response([
                'status' => 200,
                'message' => 'Cập nhật danh mục thành công!'
            ]);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'category');
        }
    }

    /**
     * API xóa danh mục (AJAX POST)
     */
    public function deleteCategory() {
        $this->render->abort405('POST', 'delete', 'category');

        $user_market_market_id = $_POST['id'] ?? $_POST['user_market_market_id'] ?? $_GET['id'] ?? $_GET['user_market_market_id'] ?? '';
        $type = $_POST['type'] ?? $_GET['type'] ?? '';

        $this->render->abort400($user_market_market_id && $type, 'delete', 'category', 'Thiếu thông tin danh mục cần xóa.');
        $categoryModel = new categoryModel();

        // Kiểm tra tồn tại
        $item = $categoryModel->getItemById($type, $user_market_market_id);
        if (!$item) {
            $this->httpAbortResponse('delete', 'category', false, 'not_found', 404);
        }

        try {
            $categoryModel->deleteItem($type, $user_market_market_id);
            
            $categoryNames = [
                'area' => 'Khu vực',
                'stall_type' => 'Loại sạp',
                'business_line' => 'Ngành hàng',
                'document_type' => 'Loại giấy tờ'
            ];
            $friendlyTypeName = $categoryNames[$type] ?? $type;
            $catName = $item['area_name'] ?? $item['stall_type_name'] ?? $item['line_name'] ?? $item['doc_type_name'] ?? '';
            general::log('delete_category', "Xóa danh mục {$friendlyTypeName}: {$catName} (ID: {$user_market_market_id})");

            $this->response([
                'status' => 200,
                'message' => 'Xóa danh mục thành công!'
            ]);
        } catch (Exception $e) {
            $this->response([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * API tạo tài khoản nhân viên mới (AJAX POST)
     */
    public function addUser() {
        $this->render->abort405('POST', 'create', 'user');
        $this->render->abort403(marketService::isSuperAdmin() || marketService::isAdminMarket(), 'create', 'user');

        $db = database::getInstance();
        $username = trim($_POST['username'] ?? '');
        $fullname = trim($_POST['fullname'] ?? '');
        $market_email = trim($_POST['email'] ?? $_POST['market_email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'admin';
        $status = $_POST['status'] ?? 'active';
        $checkedMarkets = $_POST['markets'] ?? [];

        if (marketService::isAdminMarket() && $role !== 'admin') {
            $role = 'admin';
        }

        $isActive = ($status === 'active') ? 1 : 0;

        $validator = new validator();
        $validator->required('username', $username, 'Vui lòng nhập tên đăng nhập.')
                  ->required('password', $password, 'Vui lòng nhập mật khẩu.')
                  ->required('fullname', $fullname, 'Vui lòng nhập họ tên.')
                  ->email('market_email', $market_email, 'Email không đúng định dạng.');

        $this->render->abort400($validator, 'create', 'user');

        try {
            $userModel = new userModel();
            $this->render->abort400(!$userModel->getByUsername($username), 'create', 'user', 'Tên đăng nhập đã tồn tại.');
            if ($market_email) {
                $this->render->abort400(!$userModel->getByEmail($market_email), 'create', 'user', 'market_email này đã được đăng ký cho tài khoản khác.');
            }

            $actor = $db->selectOne("SELECT actor_id FROM system_actors WHERE actor_code = :code", ['code' => $role]);
            $actorId = $actor ? (int)$actor['actor_id'] : 3;

            $newUserId = $userModel->create([
                'username' => $username,
                'password' => $password,
                'fullname' => $fullname,
                'email' => $market_email,
                'user_group' => ($role === 'super_market') ? 1 : 2,
                'actor_id' => $actorId,
                'is_active' => $isActive
            ]);

            if ($role !== 'super_market') {
                if (marketService::isSuperAdmin()) {
                    $allowedMarkets = array_column($db->select("SELECT market_id FROM markets WHERE market_status_code = 'active'"), 'market_id');
                } else {
                    $managerUserId = session::get('user_market_user_id');
                    $allowedMarkets = array_column($db->select("
                        SELECT m.market_id 
                        FROM user_markets um
                        JOIN markets m ON um.user_market_market_id = m.market_id
                        WHERE um.user_market_user_id = :manager_id AND m.market_status_code = 'active'
                    ", ['manager_id' => $managerUserId]), 'market_id');
                }

                $marketRolesInput = $_POST['market_roles'] ?? [];

                foreach ($checkedMarkets as $mId) {
                    if (in_array((int)$mId, $allowedMarkets)) {
                        if ($role === 'admin_market') {
                            $selectedRoleId = 4; // Quản lý chợ
                        } else {
                            $selectedRoleId = (int)($marketRolesInput[$mId] ?? 2);
                            $roleExists = $db->selectOne("SELECT 1 FROM market_roles WHERE role_id = :rid AND status_id != 99", ['rid' => $selectedRoleId]);
                            if (!$roleExists) {
                                $selectedRoleId = 2; // Default to Nhân viên tổng hợp
                            }
                        }

                        $db->query("
                            INSERT INTO user_markets (user_market_user_id, user_market_market_id, user_market_role_id)
                            VALUES (:user_id, :market_id, :role_id)
                        ", [
                            'user_id' => $newUserId,
                            'market_id' => $mId,
                            'role_id' => $selectedRoleId
                        ]);

                        // Sync permissions for Employee (admin)
                        if ($role === 'admin') {
                            $roleRow = $db->selectOne("SELECT role_permissions FROM market_roles WHERE role_id = :rid", ['rid' => $selectedRoleId]);
                            $permissionsStr = $roleRow ? ($roleRow['role_permissions'] ?? '') : '';
                            $permsArray = array_filter(explode(',', $permissionsStr));
                            
                            // Clear and insert
                            $db->query("DELETE FROM user_market_permissions WHERE permission_user_id = :uid AND permission_market_id = :mid", [
                                'uid' => $newUserId,
                                'mid' => $mId
                            ]);
                            foreach ($permsArray as $permCode) {
                                $db->query("INSERT INTO user_market_permissions (permission_user_id, permission_market_id, permission_module_code) VALUES (:uid, :mid, :mod)", [
                                    'uid' => $newUserId,
                                    'mid' => $mId,
                                    'mod' => $permCode
                                ]);
                            }
                        }
                    }
                }
            }

            general::log('create_user', "Tạo mới tài khoản nhân viên: {$username} (ID: {$newUserId}, Họ tên: {$fullname}, Vai trò: {$role})");
            $this->render->apiResponse('create', 'user', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'user');
        }
    }

    /**
     * API cập nhật tài khoản nhân viên (AJAX POST)
     */
    public function editUser() {
        $this->render->abort405('POST', 'update', 'user');
        $this->render->abort403(marketService::isSuperAdmin() || marketService::isAdminMarket(), 'update', 'user');
        
        $id = $_POST['id'] ?? $_POST['market_id'] ?? $_POST['user_market_market_id'] ?? '';
        $this->render->abort400(!empty($id), 'update', 'user', 'Thiếu ID tài khoản cần sửa.');

        $db = database::getInstance();
        $userModel = new userModel();

        $user = $db->selectOne("
            SELECT u.*, sa.actor_code 
            FROM users u 
            LEFT JOIN system_actors sa ON u.user_actor_id = sa.actor_id 
            WHERE u.user_id = :id
        ", ['id' => $id]);

        $this->render->abort400($user !== null, 'update', 'user', 'Không tìm thấy tài khoản nhân viên.');

        if (!marketService::isSuperAdmin()) {
            $managerUserId = session::get('user_market_user_id');
            $isAssociated = $db->selectOne("
                SELECT 1 FROM user_markets 
                WHERE user_market_user_id = :target_id AND user_market_market_id IN (
                    SELECT user_market_market_id FROM user_markets WHERE user_market_user_id = :manager_id
                )
            ", ['target_id' => $id, 'manager_id' => $managerUserId]);

            $this->render->abort403($isAssociated || $user['actor_code'] === 'admin', 'update', 'user', 'Bạn không có quyền chỉnh sửa tài khoản này.');
        }

        $fullname = trim($_POST['fullname'] ?? '');
        $market_email = trim($_POST['email'] ?? $_POST['market_email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? $user['actor_code'];
        $status = $_POST['status'] ?? ($user['user_is_active'] ? 'active' : 'inactive');
        $checkedMarkets = $_POST['markets'] ?? [];

        if (marketService::isAdminMarket()) {
            $role = 'admin';
        }

        $isActive = ($status === 'active') ? 1 : 0;

        $validator = new validator();
        $validator->required('fullname', $fullname, 'Vui lòng nhập họ tên.')
                  ->email('market_email', $market_email, 'Email không đúng định dạng.');

        $this->render->abort400($validator, 'update', 'user');

        try {
            $dupUser = $userModel->getByEmail($market_email);
            if ($dupUser && $dupUser['user_id'] != $id) {
                $this->render->abort400(false, 'update', 'user', 'Email này đã được sử dụng bởi một tài khoản khác.');
            }

            $actor = $db->selectOne("SELECT actor_id FROM system_actors WHERE actor_code = :code", ['code' => $role]);
            $actorId = $actor ? (int)$actor['actor_id'] : 3;

            $userModel->update($id, [
                'fullname' => $fullname,
                'email' => $market_email,
                'user_group' => ($role === 'super_market') ? 1 : 2,
                'actor_id' => $actorId,
                'is_active' => $isActive
            ]);

            if (!empty($password)) {
                $userModel->updatePassword($id, $password);
            }

            if (marketService::isSuperAdmin()) {
                $marketsScopeList = $db->select("SELECT market_id AS id FROM markets WHERE market_status_code = 'active'");
            } else {
                $managerUserId = session::get('user_market_user_id');
                $marketsScopeList = $db->select("
                    SELECT m.market_id AS id 
                    FROM user_markets um
                    JOIN markets m ON um.user_market_market_id = m.market_id
                    WHERE um.user_market_user_id = :manager_id AND m.market_status_code = 'active'
                ", ['manager_id' => $managerUserId]);
            }
            $marketsScopeIds = array_column($marketsScopeList, 'id');

            if (!empty($marketsScopeIds)) {
                $placeholders = implode(',', array_map(function($i) { return ":m{$i}"; }, range(0, count($marketsScopeIds) - 1)));
                $deleteParams = ['id' => $id];
                foreach ($marketsScopeIds as $idx => $mId) {
                    $deleteParams["m{$idx}"] = $mId;
                }
                $db->query("DELETE FROM user_markets WHERE user_market_user_id = :id AND user_market_market_id IN ($placeholders)", $deleteParams);
                $db->query("DELETE FROM user_market_permissions WHERE permission_user_id = :id AND permission_market_id IN ($placeholders)", $deleteParams);
            }

            if ($role !== 'super_market') {
                $marketRolesInput = $_POST['market_roles'] ?? [];

                foreach ($checkedMarkets as $mId) {
                    if (in_array((int)$mId, $marketsScopeIds)) {
                        if ($role === 'admin_market') {
                            $selectedRoleId = 4; // Quản lý chợ
                        } else {
                            $selectedRoleId = (int)($marketRolesInput[$mId] ?? 2);
                            $roleExists = $db->selectOne("SELECT 1 FROM market_roles WHERE role_id = :rid AND status_id != 99", ['rid' => $selectedRoleId]);
                            if (!$roleExists) {
                                $selectedRoleId = 2; // Default to Nhân viên tổng hợp
                            }
                        }

                        $db->query("
                            INSERT INTO user_markets (user_market_user_id, user_market_market_id, user_market_role_id)
                            VALUES (:user_id, :market_id, :role_id)
                        ", [
                            'user_id' => $id,
                            'market_id' => $mId,
                            'role_id' => $selectedRoleId
                        ]);

                        // Sync permissions for Employee (admin)
                        if ($role === 'admin') {
                            $roleRow = $db->selectOne("SELECT role_permissions FROM market_roles WHERE role_id = :rid", ['rid' => $selectedRoleId]);
                            $permissionsStr = $roleRow ? ($roleRow['role_permissions'] ?? '') : '';
                            $permsArray = array_filter(explode(',', $permissionsStr));
                            
                            // Clear and insert
                            $db->query("DELETE FROM user_market_permissions WHERE permission_user_id = :uid AND permission_market_id = :mid", [
                                'uid' => $id,
                                'mid' => $mId
                            ]);
                            foreach ($permsArray as $permCode) {
                                $db->query("INSERT INTO user_market_permissions (permission_user_id, permission_market_id, permission_module_code) VALUES (:uid, :mid, :mod)", [
                                    'uid' => $id,
                                    'mid' => $mId,
                                    'mod' => $permCode
                                ]);
                            }
                        }
                    }
                }
            }

            // If the role is no longer an employee (admin), clean up all their module permissions
            if ($role !== 'admin') {
                $db->query("DELETE FROM user_market_permissions WHERE permission_user_id = :uid", ['uid' => $id]);
            }

            general::log('update_user', "Cập nhật tài khoản nhân viên: {$user['user_username']} (ID: {$id}, Họ tên: {$fullname}, Vai trò: {$role})");
            $this->render->apiResponse('update', 'user', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'user');
        }
    }
     /*/------------------------ QUẢN LÝ THU CHI ------------------------
       API below accepts multipart forms so attachments can be saved together
       with vouchers.  Deletes are soft deletes (status=99), never hard deletes. */
    private function incomeApiGuard() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->response(['status'=>405, 'message'=>'Chỉ hỗ trợ POST.'], 405);
        if (!session::isLoggedIn()) $this->response(['status'=>403, 'message'=>'Vui lòng đăng nhập.'], 403);
    }
    private function incomeApiMarketId() { return (int)marketService::currentMarketId(); }
    public function saveIncomeCategory() {
        $this->incomeApiGuard();
        $type=$_POST['type'] ?? ''; $name=trim($_POST['name'] ?? '');
        if (!in_array($type, ['income','expense'], true) || $name==='') $this->response(['status'=>400,'message'=>'Loại và tên danh mục là bắt buộc.'],400);
        try { $id=(new incomeModel())->saveCategory($this->incomeApiMarketId(), ['category_id'=>(int)($_POST['category_id']??0),'type'=>$type,'name'=>$name,'note'=>trim($_POST['note']??'')]); $this->response(['status'=>200,'message'=>'Đã lưu danh mục.','id'=>$id]); }
        catch (Exception $e) { $this->response(['status'=>500,'message'=>'Không thể lưu danh mục: '.$e->getMessage()],500); }
    }
    public function deleteIncomeCategory() {
        $this->incomeApiGuard(); $id=(int)($_POST['category_id']??0);
        if (!$id) $this->response(['status'=>400,'message'=>'Thiếu danh mục cần xóa.'],400);
        try { (new incomeModel())->deleteCategory($this->incomeApiMarketId(),$id); $this->response(['status'=>200,'message'=>'Đã xóa danh mục.']); }
        catch (Exception $e) { $this->response(['status'=>500,'message'=>$e->getMessage()],500); }
    }
    public function saveIncomeVoucher() {
        $this->incomeApiGuard();
        $type=$_POST['type']??''; $amount=(float)str_replace([',',' ', '.'],['','',''],$_POST['amount']??'0');
        if (!in_array($type,['income','expense'],true) || empty($_POST['voucher_date']) || trim($_POST['content']??'')==='' || $amount<=0) $this->response(['status'=>400,'message'=>'Ngày, nội dung và số tiền hợp lệ là bắt buộc.'],400);
        $marketId=$this->incomeApiMarketId(); $model=new incomeModel(); $voucherId=(int)($_POST['voucher_id']??0);
        $categoryId=(int)($_POST['category_id']??0);
        if ($categoryId) {
            $category=$model->category($marketId,$categoryId);
            if (!$category || $category['category_type']!==$type) $this->response(['status'=>400,'message'=>'Danh mục không hợp lệ.'],400);
        }
        $existing=$voucherId ? $model->voucher($marketId,$voucherId) : null;
        if ($voucherId && !$existing) $this->response(['status'=>404,'message'=>'Không tìm thấy phiếu.'],404);
        $attachment=$existing['attachment_path']??null;
        if (!empty($_FILES['attachment']['name'])) {
            if ($_FILES['attachment']['error']!==UPLOAD_ERR_OK || $_FILES['attachment']['size']>10*1024*1024) $this->response(['status'=>400,'message'=>'Tệp không hợp lệ hoặc vượt quá 10MB.'],400);
            $ext=strtolower(pathinfo($_FILES['attachment']['name'],PATHINFO_EXTENSION));
            if (!in_array($ext,['pdf','doc','docx','xls','xlsx','jpg','jpeg','png'],true)) $this->response(['status'=>400,'message'=>'Định dạng tệp không được hỗ trợ.'],400);
            $folder=DIR_ROOT.'/uploads/income'; if (!is_dir($folder) && !mkdir($folder,0755,true)) $this->response(['status'=>500,'message'=>'Không tạo được thư mục tệp.'],500);
            $attachment=time().'_'.bin2hex(random_bytes(6)).'.'.$ext;
            if (!move_uploaded_file($_FILES['attachment']['tmp_name'],$folder.'/'.$attachment)) $this->response(['status'=>500,'message'=>'Không lưu được tệp đính kèm.'],500);
        }
        try {
            $id=$model->saveVoucher($marketId,(int)session::get('user_id',0),['voucher_id'=>$voucherId,'type'=>$type,'category_id'=>$categoryId,'voucher_date'=>$_POST['voucher_date'],'content'=>trim($_POST['content']),'amount'=>$amount,'document_no'=>trim($_POST['document_no']??''),'payer_name'=>trim($_POST['payer_name']??''),'collector_name'=>trim($_POST['collector_name']??''),'beneficiary_name'=>trim($_POST['beneficiary_name']??''),'attachment_path'=>$attachment]);
            
            $voucherTypeLabel = ($type === 'income') ? 'Thu' : 'Chi';
            $actionLabel = $voucherId ? 'Cập nhật' : 'Tạo mới';
            general::log('save_voucher', "{$actionLabel} phiếu {$voucherTypeLabel} ID: {$id} (Nội dung: " . trim($_POST['content']) . ", Số tiền: " . number_format($amount) . "đ)");

            $this->response(['status'=>200,'message'=>'Đã lưu phiếu.','id'=>$id]);
        } catch (Exception $e) { $this->response(['status'=>500,'message'=>'Không thể lưu phiếu: '.$e->getMessage()],500); }
    }
    public function deleteIncomeVoucher() {
        $this->incomeApiGuard(); $id=(int)($_POST['voucher_id']??0);
        if (!$id) $this->response(['status'=>400,'message'=>'Thiếu phiếu cần xóa.'],400);
        try { 
            $voucher = (new incomeModel())->voucher($this->incomeApiMarketId(), $id);
            (new incomeModel())->deleteVoucher($this->incomeApiMarketId(),$id); 
            
            $voucherDesc = $voucher ? "Nội dung: " . $voucher['content'] . ", Số tiền: " . number_format($voucher['amount']) . "đ" : "ID: {$id}";
            general::log('delete_voucher', "Xóa phiếu thu/chi ID: {$id} ({$voucherDesc})");

            $this->response(['status'=>200,'message'=>'Đã xóa phiếu.']); 
        }
        catch (Exception $e) { $this->response(['status'=>500,'message'=>$e->getMessage()],500); }
    }

    /**
     * API cập nhật thông tin cá nhân của user đang đăng nhập (AJAX POST)
     */
    public function updateProfile() {
        $this->render->abort405('POST', 'update', 'user');
        
        $userId = session::get('user_id');
        if (!$userId) {
            $this->response(['error' => 'Bạn chưa đăng nhập.'], 401);
        }

        $db = database::getInstance();
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? $_POST['market_email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        $validator = new validator();
        $validator->required('fullname', $fullname, 'Vui lòng nhập họ tên.')
                  ->email('email', $email, 'Email không đúng định dạng.');

        if (!empty($password)) {
            if (strlen($password) < 6) {
                $validator->addError('password', 'Mật khẩu phải có ít nhất 6 ký tự.');
            }
            if ($password !== $confirm_password) {
                $validator->addError('confirm_password', 'Mật khẩu xác nhận không khớp.');
            }
        }

        $this->render->abort400($validator, 'update', 'user');

        try {
            $userModel = new userModel();
            $dupUser = $userModel->getByEmail($email);
            if ($dupUser && $dupUser['user_id'] != $userId) {
                $this->render->abort400(false, 'update', 'user', 'Email này đã được sử dụng bởi một tài khoản khác.');
            }

            // Cập nhật thông tin cơ bản
            $db->query("
                UPDATE users 
                SET user_fullname = :fullname, user_email = :email 
                WHERE user_id = :id
            ", [
                'fullname' => $fullname,
                'email' => $email,
                'id' => $userId
            ]);

            // Cập nhật mật khẩu nếu có nhập
            if (!empty($password)) {
                $userModel->updatePassword($userId, $password);
            }

            // Cập nhật họ tên hiển thị trong session
            $_SESSION['user_fullname'] = $fullname;

            general::log('update_profile', "Cập nhật thông tin cá nhân của bản thân (Họ tên mới: {$fullname}, Email mới: {$email}" . (!empty($password) ? ", Thay đổi mật khẩu" : "") . ")");
            $this->render->apiResponse('update', 'user', true, 'Cập nhật thông tin cá nhân thành công.');
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'user');
        }
    }
}

