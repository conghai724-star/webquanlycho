<?php
/**
 * Controller xử lý các yêu cầu AJAX / API trả về dữ liệu JSON
 */
class apiController extends baseController {

    public function __construct($registry) {
        parent::__construct($registry);

        $action = isset($this->registry->router->action) ? $this->registry->router->action : '';

        // Một số API công khai hoặc chỉ check login chung
        $publicActions = ['login', 'checkSession', 'getStallTree', 'getStallDetails'];
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
                'addCategory'    => 'category',
                'editCategory'   => 'category',
                'deleteCategory' => 'category',
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

    /**
     * API Đăng nhập hệ thống
     */
    public function login()
	{
		$result = array();
		
		$username = trim($_POST["email"] ?? $_POST["username"] ?? '');
		$password = $_POST["password"] ?? '';

		if (!$username || !$password) {
			$result["status"] = "500";
			$result['message'] = 'Vui lòng nhập tài khoản và mật khẩu';
			echo json_encode($result);
			return;
		}

		$db = database::getInstance();

		// 1. Ưu tiên kiểm tra đăng nhập từ bảng web_users (Web Admin & Biên tập viên)
		$webUser = null;
		try {
			$webUser = $db->selectOne("SELECT * FROM web_users WHERE (username = :u OR email = :u) AND status = 1", ['u' => $username]);
		} catch (Exception $e) {
			$webUser = null;
		}

		if ($webUser) {
			if (password_verify($password, $webUser['password'])) {
				session_regenerate_id(true);
				$_SESSION['user_logged_in'] = true;
				$_SESSION['user_id'] = $webUser['id'];
				$_SESSION['user_fullname'] = $webUser['fullname'];
				$_SESSION['user_email'] = $webUser['email'];
				$_SESSION['actor_code'] = 'super_market';
				$_SESSION['web_user'] = [
					'id' => $webUser['id'],
					'username' => $webUser['username'],
					'fullname' => $webUser['fullname'],
					'email' => $webUser['email'],
					'role' => $webUser['role'],
					'permissions' => $webUser['permissions'] ?? 'all'
				];
				
				$allMarkets = $db->select("SELECT market_id FROM markets");
				$allMarketIds = !empty($allMarkets) ? array_column($allMarkets, 'market_id') : [1];
				$_SESSION['active_market_id'] = (int)($allMarketIds[0] ?? 1);
				$_SESSION['accessible_market_ids'] = $allMarketIds;

				$result["status"] = 200;
				$result["contract_name"] = $webUser['fullname'];
				$result['return_url'] = BASE_URL . "admin/dashboard";
				echo json_encode($result);
				return;
			}
		}

		// 2. Kiểm tra tài khoản trong bảng users (Hệ thống chợ truyền thống)
		$userModel = new userModel();
		$row = $userModel->checkLogin($username);

		if (!$row) {
			$result["status"] = "500";
			$result['message'] = 'Tài khoản không tồn tại hoặc đã bị khóa';
			echo json_encode($result);
			return;
		}

		if (!password_verify($password, $row["user_password"])) {
			$result["status"] = "500";
			$result['message'] = 'Mật khẩu không chính xác';
			echo json_encode($result);
			return;
		}

		// Cập nhật trạng thái đăng nhập
		$userModel->updateLoginState($row["user_id"]);

		// Khởi tạo phiên làm việc Session
		session_regenerate_id(true);
		$_SESSION['user_logged_in'] = true;
		$_SESSION['user_id'] = $row["user_id"];
		$_SESSION['user_fullname'] = $row["user_fullname"];
		$_SESSION['user_email'] = $row["user_email"];
		$_SESSION['actor_code'] = $row["actor_code"];

		// Gán toàn quyền Web Admin nếu là super admin
		if ($row["actor_code"] === 'super_market' || $row["actor_code"] === 'admin_market') {
			$_SESSION['web_user'] = [
				'id' => $row["user_id"],
				'username' => $row["user_name"],
				'fullname' => $row["user_fullname"],
				'email' => $row["user_email"],
				'role' => 'admin',
				'permissions' => 'all'
			];
		}

		// Khởi tạo ngữ cảnh chợ
		if ($row["actor_code"] === 'super_market') {
			$allMarkets = $db->select("SELECT market_id FROM markets");
			$allMarketIds = !empty($allMarkets) ? array_column($allMarkets, 'market_id') : [1];
			$_SESSION['active_market_id'] = (int)($allMarketIds[0] ?? 1);
			$_SESSION['accessible_market_ids'] = $allMarketIds;
		} else {
			$userMarkets = $db->select("SELECT user_market_market_id FROM user_markets WHERE user_market_user_id = :uid", ['uid' => $row["user_id"]]);
			$marketIds = !empty($userMarkets) ? array_column($userMarkets, 'user_market_market_id') : [];
			$_SESSION['accessible_market_ids'] = $marketIds;
			$_SESSION['active_market_id'] = !empty($marketIds) ? (int)$marketIds[0] : 0;
		}

		$result["status"] = 200;
		$result["contract_name"] = $row['user_fullname'];
		$result['return_url'] = BASE_URL . "admin/dashboard";
		echo json_encode($result);
	}

    /**
     * API thống kê doanh thu theo tháng (Phục vụ vẽ biểu đồ Chart.js)
     */
    public function getRevenueData() {
        $data = [
            'labels' => ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6'],
            'revenue' => [380000000, 420000000, 400000000, 450000000, 480000000, 450000000],
            'expense' => [120000000, 130000000, 115000000, 140000000, 150000000, 135000000]
        ];

        $this->response($data);
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

    // =========================================================================
    // SƠ ĐỒ CHỢ TƯƠNG TÁC & BẢN ĐỒ SỐ
    // =========================================================================

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

            // Ẩn bớt thông tin nhạy cảm đối với khách vãng lai
            if (!$this->helper->isLoggedIn()) {
                if (!empty($details['trader_phone'])) {
                    $details['trader_phone'] = substr($details['trader_phone'], 0, 4) . ' *** ***';
                } else {
                    $details['trader_phone'] = 'Chưa cập nhật';
                }
                $details['trader_address'] = 'Đăng nhập để xem';
                $details['trader_cccd'] = 'Đăng nhập để xem';
                $details['contract_file'] = null;
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

        $accessible = marketService::getAccessibleMarketIds();
        if (!in_array((int)$marketId, $accessible) && !marketService::isSuperAdmin()) {
            $this->response(['status' => 403, 'message' => 'Không có quyền truy cập chợ này.'], 403);
        }

        session::set('active_market_id', (int)$marketId);
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

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if ($data === null || !isset($data['elements'])) {
            $this->response(['status' => 400, 'message' => 'Dữ liệu sơ đồ không hợp lệ.'], 400);
        }

        try {
            include_once __SITE_PATH . '/model/mapModel.php';
            $mapModel = new mapModel();
            $mapModel->saveElements($data['elements']);
            
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

    /**
     * API giải mã link Google Maps rút gọn để trích xuất tọa độ GPS (AJAX POST)
     */
    public function resolveShortenedUrl() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->response(['status' => 405, 'message' => 'Phương thức không được hỗ trợ.'], 405);
            return;
        }

        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        $url = $data['url'] ?? '';

        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            $this->response(['status' => 400, 'message' => 'URL không hợp lệ.'], 400);
            return;
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
            
            $response = curl_exec($ch);
            $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);

            if (empty($effectiveUrl)) {
                $this->response(['status' => 400, 'message' => 'Không thể giải mã link liên kết.'], 400);
                return;
            }

            $lat = null;
            $lng = null;

            if (preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/i', $effectiveUrl, $matches)) {
                $lat = floatval($matches[1]);
                $lng = floatval($matches[2]);
            } elseif (preg_match('/(?:query|q)=(-?\d+\.\d+)(?:%2C|,)(-?\d+\.\d+)/i', $effectiveUrl, $matches)) {
                $lat = floatval($matches[1]);
                $lng = floatval($matches[2]);
            } elseif (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $effectiveUrl, $matches)) {
                $lat = floatval($matches[1]);
                $lng = floatval($matches[2]);
            }

            if ($lat !== null && $lng !== null) {
                $this->response([
                    'status' => 200,
                    'data' => [
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'resolved_url' => $effectiveUrl
                    ]
                ]);
            } else {
                $this->response([
                    'status' => 404, 
                    'message' => 'Không thể tìm thấy tọa độ GPS trong link liên kết đã giải mã. URL đích: ' . $effectiveUrl
                ], 404);
            }
        } catch (Exception $e) {
            $this->response(['status' => 500, 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // QUẢN LÝ DANH MỤC (CATEGORIES)
    // =========================================================================

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
        $validator->required('username', $username, 'Tên đăng nhập không được để trống.')
                  ->required('fullname', $fullname, 'Họ và tên không được để trống.')
                  ->required('password', $password, 'Mật khẩu không được để trống.');

        if (!empty($market_email)) {
            $validator->email('email', $market_email, 'Email không đúng định dạng.');
        }

        if (strlen($password) < 6) {
            $validator->addError('password', 'Mật khẩu phải có ít nhất 6 ký tự.');
        }

        $this->render->abort400($validator, 'create', 'user');

        try {
            $userModel = new userModel();
            $this->render->abort400(!$userModel->isUsernameExists($username), 'create', 'user', 'Tên đăng nhập này đã tồn tại.');

            if (!empty($market_email)) {
                $this->render->abort400(!$userModel->isEmailExists($market_email), 'create', 'user', 'Email này đã tồn tại.');
            }

            $userId = $userModel->create([
                'user_name'     => $username,
                'user_fullname' => $fullname,
                'user_email'    => $market_email,
                'user_password' => password_hash($password, PASSWORD_DEFAULT),
                'actor_code'    => $role,
                'user_status'   => $isActive
            ]);

            if (marketService::isSuperAdmin()) {
                $db->query("DELETE FROM user_markets WHERE user_market_user_id = :uid", ['uid' => $userId]);
                if (!empty($checkedMarkets) && is_array($checkedMarkets)) {
                    foreach ($checkedMarkets as $mId) {
                        $mId = (int)$mId;
                        if ($mId > 0) {
                            $db->query("INSERT INTO user_markets (user_market_user_id, user_market_market_id) VALUES (:uid, :mid)", [
                                'uid' => $userId,
                                'mid' => $mId
                            ]);
                        }
                    }
                }
            }

            general::log('create_user', "Thêm mới tài khoản: {$username} ({$fullname}) - Vai trò: {$role} (ID: {$userId})");

            $this->response([
                'status' => 200,
                'message' => 'Thêm mới tài khoản thành công!',
                'user_market_market_id' => $userId
            ]);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'user');
        }
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

            $db->query("
                UPDATE users 
                SET user_fullname = :fullname, user_email = :email 
                WHERE user_id = :id
            ", [
                'fullname' => $fullname,
                'email' => $email,
                'id' => $userId
            ]);

            if (!empty($password)) {
                $userModel->updatePassword($userId, $password);
            }

            $_SESSION['user_fullname'] = $fullname;

            general::log('update_profile', "Cập nhật thông tin cá nhân của bản thân (Họ tên mới: {$fullname}, Email mới: {$email}" . (!empty($password) ? ", Thay đổi mật khẩu" : "") . ")");
            $this->render->apiResponse('update', 'user', true, 'Cập nhật thông tin cá nhân thành công.');
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'user');
        }
    }
}
