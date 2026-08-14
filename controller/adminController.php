<?php
/**
 * Controller xử lý Quản trị Trang Web, Biên tập Bản đồ số & Các phân hệ Nội dung Web
 * Phân quyền module chuẩn hệ thống Admin cũ (RBAC)
 */
Class adminController extends baseController {

    public function __construct($registry) {
        parent::__construct($registry);

        $action = isset($this->registry->router->action) ? $this->registry->router->action : '';

        // Không bảo vệ trang đăng nhập
        if ($action === 'login') {
            return;
        }

        // 1. Kiểm tra đăng nhập Web Admin
        if (!session::isWebLoggedIn()) {
            header('Location: ' . BASE_URL . 'admin/login');
            exit();
        }

        // 2. Phân quyền chi tiết theo Module chuẩn hệ thống cũ
        $actionModuleMap = [
            'index'                     => 'dashboard',
            'dashboard'                 => 'dashboard',
            'map_editor'                => 'map_editor',
            'map_tree'                  => 'map_tree',
            
            'banners'                   => 'banners',
            'banner_add'                => 'banners',
            'banner_edit'               => 'banners',
            'banner_delete'             => 'banners',
            'about_sections'            => 'banners',
            'about_section_edit'        => 'banners',
            'contact_settings'          => 'banners',
            'intro_settings'            => 'banners',
            
            'posts'                     => 'banners',
            'post_add'                  => 'banners',
            'post_edit'                 => 'banners',
            'post_delete'               => 'banners',
            'post_toggle'               => 'banners',
            'post_upload_inline_image'  => 'banners',
            
            'registrations'             => 'registrations',
            'registration_status'       => 'registrations',
            'registration_delete'       => 'registrations',
            
            'feedbacks'                 => 'feedbacks',
            'feedback_status'           => 'feedbacks',
            'feedback_delete'           => 'feedbacks',
            
            'users'                     => 'users',
            'user_add'                  => 'users',
            'user_edit'                 => 'users',
            'user_delete'               => 'users',
            
            'roles'                     => 'roles',
            'permissions'               => 'roles'
        ];

        if (isset($actionModuleMap[$action])) {
            $requiredModule = $actionModuleMap[$action];
            if (!session::hasWebModule($requiredModule)) {
                http_response_code(403);
                $this->view->app('errors/403', [
                    'title' => '403 Forbidden - Bạn không có quyền truy cập chức năng này'
                ]);
                exit();
            }
        }
    }

    public function index() {
        $this->dashboard();
    }

    /**
     * Trang Dashboard tổng quan Web Admin
     */
    public function dashboard() {
        $db = database::getInstance();

        $totalWebUsers = 0;
        $totalMapElements = 0;
        $totalBanners = 0;
        $pendingRegistrations = 0;
        $newFeedbacks = 0;

        try {
            $userStats = $db->selectOne("SELECT COUNT(*) as total FROM web_users");
            $totalWebUsers = (int)($userStats['total'] ?? 0);
        } catch (Exception $e) {}

        try {
            $mapStats = $db->selectOne("SELECT COUNT(*) as total FROM market_map_elements");
            $totalMapElements = (int)($mapStats['total'] ?? 0);
        } catch (Exception $e) {}

        try {
            $bannerStats = $db->selectOne("SELECT COUNT(*) as total FROM website_banners WHERE banner_status = 1");
            $totalBanners = (int)($bannerStats['total'] ?? 0);
        } catch (Exception $e) {}

        try {
            $regStats = $db->selectOne("SELECT COUNT(*) as total FROM stall_registrations WHERE status = 'pending'");
            $pendingRegistrations = (int)($regStats['total'] ?? 0);
        } catch (Exception $e) {}

        try {
            $fbStats = $db->selectOne("SELECT COUNT(*) as total FROM website_feedbacks WHERE status = 'new' OR fb_status = 1");
            $newFeedbacks = (int)($fbStats['total'] ?? 0);
        } catch (Exception $e) {}

        $this->view->app('dashboard/index', [
            'title' => 'Tổng quan Quản trị Web & Bản đồ số',
            'stats' => [
                'total_web_users' => $totalWebUsers,
                'total_map_elements' => $totalMapElements,
                'total_banners' => $totalBanners,
                'pending_registrations' => $pendingRegistrations,
                'new_feedbacks' => $newFeedbacks
            ]
        ]);
    }

    /**
     * Trang Đăng nhập Web Admin
     */
    public function login() {
        if (session::isWebLoggedIn()) {
            header('Location: ' . BASE_URL . 'admin/dashboard');
            exit();
        }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? $_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
            } else {
                $db = database::getInstance();
                $user = null;
                try {
                    $user = $db->selectOne("SELECT * FROM web_users WHERE (username = :u OR email = :u) AND status = 1", ['u' => $username]);
                } catch (Exception $e) {}

                if ($user) {
                    $storedHash = $user['password'];
                    $passwordOk = (strlen($storedHash) === 32) ? (md5($password) === $storedHash) : (password_verify($password, $storedHash) || md5($password) === $storedHash);

                    if ($passwordOk) {
                        $_SESSION['web_user'] = [
                            'id' => $user['id'],
                            'username' => $user['username'],
                            'fullname' => $user['fullname'],
                            'email' => $user['email'],
                            'phone' => $user['phone'],
                            'role' => $user['role'],
                            'permissions' => $user['permissions'] ?? ''
                        ];
                        header('Location: ' . BASE_URL . 'admin/dashboard');
                        exit();
                    }
                }
                $error = 'Thông tin đăng nhập không chính xác hoặc tài khoản bị khóa.';
            }
        }

        $this->view->app('auth/login', [
            'title' => 'Đăng nhập Quản trị Web',
            'error' => $error
        ]);
    }

    /**
     * Đăng xuất Web Admin
     */
    public function logout() {
        unset($_SESSION['web_user']);
        header('Location: ' . BASE_URL . 'admin/login');
        exit();
    }

    // =========================================================================
    // 1. QUẢN LÝ BẢN ĐỒ SỐ (DIGITAL MAP EDITOR)
    // =========================================================================

    public function map_editor() {
        $db = database::getInstance();

        // 1. Xác định Chợ hiện tại (0 nghĩa là Tất cả các Chợ)
        $marketId = isset($_GET['market_id']) ? (int)$_GET['market_id'] : 0;
        $_SESSION['active_market_id'] = $marketId;

        $markets = [];
        try {
            $markets = $db->select("SELECT market_id, market_code, market_name, market_latitude, market_longitude, market_map_zoom FROM markets WHERE market_status_code != 'deleted' ORDER BY market_id ASC");
        } catch (Exception $e) {}

        $market = null;
        if ($marketId > 0) {
            try {
                $market = $db->selectOne("SELECT * FROM markets WHERE market_id = :id", ['id' => $marketId]);
            } catch (Exception $e) {}
        }

        // 2. Lấy danh sách Phân khu & Ngành hàng của tất cả các chợ (hoặc theo chợ chọn)
        $areas = [];
        try {
            if ($marketId > 0) {
                $areas = $db->select("SELECT a.area_id, a.area_name, a.area_block, a.area_market_id, m.market_name FROM areas a LEFT JOIN markets m ON a.area_market_id = m.market_id WHERE a.area_market_id = :id ORDER BY a.area_name ASC", ['id' => $marketId]);
            } else {
                $areas = $db->select("SELECT a.area_id, a.area_name, a.area_block, a.area_market_id, m.market_name FROM areas a LEFT JOIN markets m ON a.area_market_id = m.market_id ORDER BY a.area_market_id ASC, a.area_name ASC");
            }
        } catch (Exception $e) {}

        $businessLines = [];
        try {
            $businessLines = $db->select("SELECT line_id, line_name FROM business_lines ORDER BY line_name ASC");
        } catch (Exception $e) {}

        // 3. Lấy danh sách sạp chợ
        $stalls = [];
        try {
            $whereMarket = ($marketId > 0) ? "WHERE a.area_market_id = :market_id AND (s.stall_status_id IS NULL OR s.stall_status_id != 99)" : "WHERE (s.stall_status_id IS NULL OR s.stall_status_id != 99)";
            $params = ($marketId > 0) ? ['market_id' => $marketId] : [];

            $stalls = $db->select("
                SELECT s.stall_id, s.stall_code, s.stall_area_size, s.stall_area_id, s.stall_status_id,
                       s.stall_base_price, s.stall_latitude, s.stall_longitude,
                       a.area_name, a.area_block, a.area_lot, a.area_market_id, a.area_description,
                       m.market_name,
                       COALESCE(s.stall_area_size, 10.0) as area_size,
                       t.trader_fullname AS trader_name,
                       bl.line_name AS business_line_name,
                       ss.status_code, ss.status_name,
                       mme.element_id,
                       CASE WHEN (mme.element_id IS NOT NULL OR (s.stall_latitude IS NOT NULL AND s.stall_latitude != 0)) THEN 1 ELSE 0 END AS is_mapped
                FROM stalls s
                LEFT JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN markets m ON a.area_market_id = m.market_id
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                LEFT JOIN contracts c ON c.contract_stall_id = s.stall_id AND c.contract_status_id = (SELECT status_id FROM system_statuses WHERE status_domain = 'contract' AND status_code = 'active')
                LEFT JOIN traders t ON c.contract_trader_id = t.trader_id
                LEFT JOIN business_lines bl ON t.trader_business_line_id = bl.line_id
                LEFT JOIN market_map_elements mme ON mme.element_stall_id = s.stall_id
                {$whereMarket}
                ORDER BY a.area_market_id ASC, s.stall_code ASC
            ", $params);

        } catch (Exception $e) {}

        $unmappedStalls = array_values(array_filter($stalls, function($st) {
            return empty($st['is_mapped']);
        }));

        $mappedStalls = array_values(array_filter($stalls, function($st) {
            return !empty($st['is_mapped']);
        }));

        $this->view->app('map/editor', [
            'title' => 'Biên Tập Bản Đồ Số',
            'markets' => $markets,
            'market' => $market,
            'marketId' => $marketId,
            'areas' => $areas,
            'businessLines' => $businessLines,
            'stalls' => array_values($stalls),
            'unmappedStalls' => $unmappedStalls,
            'mappedStalls' => $mappedStalls
        ]);
    }

    public function map_tree() {
        $db = database::getInstance();
        $marketId = (int)($_GET['market_id'] ?? $_SESSION['active_market_id'] ?? 0);

        $markets = [];
        try {
            $markets = $db->select("SELECT market_id, market_name FROM markets WHERE market_status_code != 'deleted' ORDER BY market_name ASC");
        } catch (Exception $e) {}

        if (empty($marketId) && !empty($markets)) {
            $marketId = (int)$markets[0]['market_id'];
        }
        $_SESSION['active_market_id'] = $marketId;

        include_once __SITE_PATH . '/model/mapModel.php';
        $mapModel = new mapModel();
        $tree = $mapModel->getStallTree();

        $this->view->app('map/tree', [
            'title' => 'Cây Sơ Đồ Bản Đồ',
            'markets' => $markets,
            'marketId' => $marketId,
            'tree' => $tree
        ]);
    }


    // =========================================================================
    // 2. QUẢN LÝ BANNER (HOMEPAGE & INTRODUCE)
    // =========================================================================

    public function banners() {
        $db = database::getInstance();
        $banners = [];
        try {
            $banners = $db->select("SELECT * FROM website_banners WHERE banner_status != -1 ORDER BY banner_order ASC, id DESC");
        } catch (Exception $e) {}

        $rows = [];
        try {
            $rows = $db->select("SELECT setting_key, setting_value FROM website_settings");
        } catch (Exception $e) {}

        $settings = [];
        foreach ($rows as $r) {
            $settings[$r['setting_key']] = $r['setting_value'];
        }

        $activeTab = $_GET['tab'] ?? 'banners';

        $this->view->app('banner/index', [
            'title' => 'Quản Lý Banner & Giới Thiệu Chợ',
            'banners' => $banners,
            'settings' => $settings,
            'activeTab' => $activeTab
        ]);
    }

    public function banner_add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = database::getInstance();
            $title = trim($_POST['banner_title'] ?? '');
            $description = trim($_POST['banner_description'] ?? '');
            $image = trim($_POST['banner_image'] ?? '');
            $link = trim($_POST['banner_link'] ?? '');
            $page = $_POST['banner_page'] ?? 'home';
            $order = (int)($_POST['banner_order'] ?? 1);
            $status = (int)($_POST['banner_status'] ?? 1);

            // Xử lý upload tệp ảnh trực tiếp nếu có
            if (isset($_FILES['banner_file']) && $_FILES['banner_file']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['banner_file']['tmp_name'];
                $name = basename($_FILES['banner_file']['name']);
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                if (in_array($ext, $allowed)) {
                    $newName = 'banner_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $targetDir = 'd:/xampp/htdocs/quanlycho.vn/public/uploads/banners/';
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    if (move_uploaded_file($tmpName, $targetDir . $newName)) {
                        $image = BASE_URL . 'public/uploads/banners/' . $newName;
                    }
                }
            }

            if (!empty($title) && !empty($image)) {
                $db->query("
                    INSERT INTO website_banners (banner_title, banner_description, banner_image, banner_link, banner_page, banner_order, banner_status)
                    VALUES (:title, :description, :image, :link, :page, :order, :status)
                ", [
                    'title' => $title,
                    'description' => $description,
                    'image' => $image,
                    'link' => $link,
                    'page' => $page,
                    'order' => $order,
                    'status' => $status
                ]);
                $_SESSION['flash_success'] = 'Thêm banner mới thành công.';
            }
        }
        header('Location: ' . BASE_URL . 'admin/banners');
        exit();
    }

    public function banner_edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = database::getInstance();
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['banner_title'] ?? '');
            $description = trim($_POST['banner_description'] ?? '');
            $image = trim($_POST['banner_image'] ?? '');
            $link = trim($_POST['banner_link'] ?? '');
            $page = $_POST['banner_page'] ?? 'home';
            $order = (int)($_POST['banner_order'] ?? 1);
            $status = (int)($_POST['banner_status'] ?? 1);

            // Xử lý upload tệp ảnh mới nếu có
            if (isset($_FILES['banner_file']) && $_FILES['banner_file']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['banner_file']['tmp_name'];
                $name = basename($_FILES['banner_file']['name']);
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
                if (in_array($ext, $allowed)) {
                    $newName = 'banner_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $targetDir = 'd:/xampp/htdocs/quanlycho.vn/public/uploads/banners/';
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    if (move_uploaded_file($tmpName, $targetDir . $newName)) {
                        $image = BASE_URL . 'public/uploads/banners/' . $newName;
                    }
                }
            }

            if ($id > 0 && !empty($title) && !empty($image)) {
                $db->query("
                    UPDATE website_banners 
                    SET banner_title = :title, banner_description = :description, banner_image = :image, banner_link = :link, banner_page = :page, banner_order = :order, banner_status = :status
                    WHERE id = :id
                ", [
                    'title' => $title,
                    'description' => $description,
                    'image' => $image,
                    'link' => $link,
                    'page' => $page,
                    'order' => $order,
                    'status' => $status,
                    'id' => $id
                ]);
                $_SESSION['flash_success'] = 'Cập nhật banner thành công.';
            }
        }
        header('Location: ' . BASE_URL . 'admin/banners');
        exit();
    }


    public function banner_delete($args = []) {
        $id = (int)($args[1] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            $db = database::getInstance();
            $db->query("UPDATE website_banners SET banner_status = -1 WHERE id = :id", ['id' => $id]);
            $_SESSION['flash_success'] = 'Xóa banner thành công (Đã chuyển vào lưu trữ).';
        }
        header('Location: ' . BASE_URL . 'admin/banners');
        exit();
    }

    public function update_banner_status() {
        header('Content-Type: application/json; charset=utf-8');
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        $id = (int)($data['id'] ?? 0);
        $status = (int)($data['status'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => 400, 'message' => 'ID banner không hợp lệ.']);
            exit();
        }

        $db = database::getInstance();
        $db->query("UPDATE website_banners SET banner_status = :status WHERE id = :id", [
            'status' => $status,
            'id' => $id
        ]);

        echo json_encode(['status' => 200, 'message' => 'Cập nhật trạng thái banner thành công.']);
        exit();
    }

    public function about_settings() {
        $db = database::getInstance();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sections = $_POST['sections'] ?? [];
            $keepIds = [];

            foreach ($sections as $index => $sec) {
                $id = (int)($sec['id'] ?? 0);
                $title = trim($sec['title'] ?? '');
                $content = trim($sec['content'] ?? '');
                $order = (int)($sec['order'] ?? ($index + 1));
                $status = isset($sec['status']) ? (int)$sec['status'] : 1;

                if (empty($title)) continue;

                if ($id > 0) {
                    $db->query("
                        UPDATE website_about_sections 
                        SET section_title = :title, section_content = :content, section_order = :order, status = :status
                        WHERE id = :id
                    ", [
                        'title' => $title,
                        'content' => $content,
                        'order' => $order,
                        'status' => $status,
                        'id' => $id
                    ]);
                    $keepIds[] = $id;
                } else {
                    $db->query("
                        INSERT INTO website_about_sections (section_title, section_content, section_order, status)
                        VALUES (:title, :content, :order, :status)
                    ", [
                        'title' => $title,
                        'content' => $content,
                        'order' => $order,
                        'status' => $status
                    ]);
                    $newId = $db->lastInsertId();
                    if ($newId) $keepIds[] = $newId;
                }
            }

            // Xóa các mục đã bị gỡ trên giao diện Admin
            if (!empty($keepIds)) {
                $inClause = implode(',', array_map('intval', $keepIds));
                $db->query("DELETE FROM website_about_sections WHERE id NOT IN ($inClause)");
            }

            $_SESSION['flash_success'] = 'Cập nhật danh sách các mục bài viết Giới thiệu thành công!';
            header('Location: ' . BASE_URL . 'admin/about_settings');
            exit();
        }

        $sections = [];
        try {
            $sections = $db->select("SELECT * FROM website_about_sections ORDER BY section_order ASC, id ASC");
        } catch (Exception $e) {}

        $this->view->app('banner/about_settings', [
            'title' => 'Quản Lý Các Mục Bài Giới Thiệu Chợ',
            'sections' => $sections
        ]);
    }

    /**
     * Cấu hình Thông tin liên hệ Website (Địa chỉ, Hotline, Email, Giờ mở cửa...)
     */
    public function contact_settings() {
        $db = database::getInstance();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = $_POST['settings'] ?? [];

            // Xử lý upload file Logo nếu có tải lên
            if (!empty($_FILES['website_logo_file']['name']) && $_FILES['website_logo_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __SITE_PATH . '/public/uploads/banners/';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['website_logo_file']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'ico'];
                if (in_array($ext, $allowed)) {
                    $filename = 'website_logo_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES['website_logo_file']['tmp_name'], $uploadDir . $filename)) {
                        $settings['website_logo'] = BASE_URL . 'public/uploads/banners/' . $filename;
                    }
                }
            }

            // Nếu người dùng chọn xóa logo (quay về logo mặc định)
            if (isset($_POST['remove_website_logo']) && $_POST['remove_website_logo'] == '1') {
                $settings['website_logo'] = '';
            }

            foreach ($settings as $key => $val) {
                $k = trim($key);
                $v = trim($val);
                if (empty($k)) continue;

                $exist = $db->selectOne("SELECT setting_key FROM website_settings WHERE setting_key = :k", ['k' => $k]);
                if ($exist) {
                    $db->query("UPDATE website_settings SET setting_value = :v, updated_at = NOW() WHERE setting_key = :k", ['v' => $v, 'k' => $k]);
                } else {
                    $db->query("INSERT INTO website_settings (setting_key, setting_value, updated_at) VALUES (:k, :v, NOW())", ['k' => $k, 'v' => $v]);
                }
            }

            $_SESSION['flash_success'] = 'Cập nhật Cấu hình Website & Logo thành công!';
            header('Location: ' . BASE_URL . 'admin/contact_settings');
            exit();
        }

        $rows = [];
        try {
            $rows = $db->select("SELECT setting_key, setting_value FROM website_settings");
        } catch (Exception $e) {}

        $settings = [];
        foreach ($rows as $r) {
            $settings[$r['setting_key']] = $r['setting_value'];
        }

        $this->view->app('banner/contact_settings', [
            'title' => 'Cấu Hình Thông Tin Liên Hệ',
            'settings' => $settings
        ]);
    }

    /**
     * Cấu hình nội dung Giới thiệu chợ hiển thị ở khối #gioithieu trên Trang chủ
     */
    public function intro_settings() {
        $db = database::getInstance();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submitted = $_POST['settings'] ?? [];

            // Xử lý upload ảnh minh họa nếu có tải file lên
            if (!empty($_FILES['intro_image_file']['name']) && $_FILES['intro_image_file']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __SITE_PATH . '/public/uploads/banners/';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0777, true);
                }
                $ext = strtolower(pathinfo($_FILES['intro_image_file']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($ext, $allowed)) {
                    $filename = 'home_intro_' . time() . '.' . $ext;
                    if (move_uploaded_file($_FILES['intro_image_file']['tmp_name'], $uploadDir . $filename)) {
                        $submitted['home_intro_image'] = BASE_URL . 'public/uploads/banners/' . $filename;
                    }
                }
            }

            // Xử lý danh sách điểm nổi bật động (Thêm / Bớt tiêu chí)
            if (isset($_POST['intro_points']) && is_array($_POST['intro_points'])) {
                $cleanPoints = [];
                foreach ($_POST['intro_points'] as $pt) {
                    $t = trim($pt['title'] ?? '');
                    $d = trim($pt['desc'] ?? '');
                    if (!empty($t) || !empty($d)) {
                        $cleanPoints[] = ['title' => $t, 'desc' => $d];
                    }
                }
                $submitted['home_intro_points'] = json_encode($cleanPoints, JSON_UNESCAPED_UNICODE);
            }

            foreach ($submitted as $key => $val) {
                $k = trim($key);
                $v = trim($val);
                if (empty($k)) continue;

                $exist = $db->selectOne("SELECT setting_key FROM website_settings WHERE setting_key = :k", ['k' => $k]);
                if ($exist) {
                    $db->query("UPDATE website_settings SET setting_value = :v, updated_at = NOW() WHERE setting_key = :k", ['v' => $v, 'k' => $k]);
                } else {
                    $db->query("INSERT INTO website_settings (setting_key, setting_value, updated_at) VALUES (:k, :v, NOW())", ['k' => $k, 'v' => $v]);
                }
            }

            $_SESSION['flash_success'] = 'Cập nhật Phần Giới thiệu Trang chủ thành công!';
            header('Location: ' . BASE_URL . 'admin/banners?tab=intro');
            exit();
        }

        header('Location: ' . BASE_URL . 'admin/banners?tab=intro');
        exit();
    }

    // =========================================================================
    // 2.5 QUẢN LÝ TIN TỨC & BÀI VIẾT (POSTS)
    // =========================================================================

    private function createPostSlug($str, $id = 0) {
        $str = trim(mb_strtolower($str, 'UTF-8'));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/[\s-]+/', '-', $str);
        $slug = trim($str, '-');
        if (empty($slug)) $slug = 'tin-tuc-' . time();

        $db = database::getInstance();
        $query = "SELECT id FROM website_posts WHERE post_slug = :slug";
        $params = ['slug' => $slug];
        if ($id > 0) {
            $query .= " AND id != :id";
            $params['id'] = $id;
        }
        $exist = $db->selectOne($query, $params);
        if ($exist) {
            $slug .= '-' . rand(100, 999);
        }
        return $slug;
    }

    public function posts() {
        $db = database::getInstance();
        $keyword = trim($_GET['keyword'] ?? '');
        $statusFilter = $_GET['status'] ?? '';

        $sql = "SELECT * FROM website_posts WHERE post_status != -1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (post_title LIKE :kw OR post_summary LIKE :kw)";
            $params['kw'] = "%$keyword%";
        }

        if ($statusFilter !== '') {
            $sql .= " AND post_status = :st";
            $params['st'] = (int)$statusFilter;
        }

        $sql .= " ORDER BY id DESC";

        $posts = [];
        try {
            $posts = $db->select($sql, $params);
        } catch (Exception $e) {}

        $this->view->app('post/index', [
            'title' => 'Quản Lý Tin Tức & Bài Viết',
            'posts' => $posts,
            'keyword' => $keyword,
            'statusFilter' => $statusFilter
        ]);
    }

    public function post_add() {
        $db = database::getInstance();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['post_title'] ?? '');
            $summary = trim($_POST['post_summary'] ?? '');
            $content = trim($_POST['post_content'] ?? '');
            $status = isset($_POST['post_status']) ? (int)$_POST['post_status'] : 1;
            $slug = $this->createPostSlug($title);
            $imageUrl = trim($_POST['image_url'] ?? '');

            // Xử lý upload ảnh nếu có
            if (!empty($_FILES['post_image']['name']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['post_image'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($ext, $allowed)) {
                    $newName = 'post_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $uploadDir = __SITE_PATH . '/public/uploads/posts/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                        $imageUrl = BASE_URL . 'public/uploads/posts/' . $newName;
                    }
                }
            }

            if (!empty($title)) {
                $db->query("
                    INSERT INTO website_posts (post_title, post_slug, post_summary, post_content, post_image, post_status, created_at, updated_at)
                    VALUES (:title, :slug, :summary, :content, :image, :status, NOW(), NOW())
                ", [
                    'title' => $title,
                    'slug' => $slug,
                    'summary' => $summary,
                    'content' => $content,
                    'image' => $imageUrl,
                    'status' => $status
                ]);
                $_SESSION['flash_success'] = 'Đăng bài viết mới thành công!';
                header('Location: ' . BASE_URL . 'admin/posts');
                exit();
            }
        }

        $this->view->app('post/form', [
            'title' => 'Thêm Bài Viết Mới',
            'post' => null
        ]);
    }

    public function post_edit($args = []) {
        $db = database::getInstance();
        $id = (int)($args[1] ?? $_GET['id'] ?? 0);
        $post = $db->selectOne("SELECT * FROM website_posts WHERE id = :id", ['id' => $id]);
        if (!$post) {
            header('Location: ' . BASE_URL . 'admin/posts');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['post_title'] ?? '');
            $summary = trim($_POST['post_summary'] ?? '');
            $content = trim($_POST['post_content'] ?? '');
            $status = isset($_POST['post_status']) ? (int)$_POST['post_status'] : 1;
            $slug = trim($_POST['post_slug'] ?? '');
            if (empty($slug)) $slug = $this->createPostSlug($title, $id);
            $imageUrl = $post['post_image'];

            // Xử lý upload ảnh mới nếu có
            if (!empty($_FILES['post_image']['name']) && $_FILES['post_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['post_image'];
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (in_array($ext, $allowed)) {
                    $newName = 'post_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                    $uploadDir = __SITE_PATH . '/public/uploads/posts/';
                    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
                        $imageUrl = BASE_URL . 'public/uploads/posts/' . $newName;
                    }
                }
            } elseif (!empty($_POST['image_url'])) {
                $imageUrl = trim($_POST['image_url']);
            }

            if (!empty($title)) {
                $db->query("
                    UPDATE website_posts 
                    SET post_title = :title, post_slug = :slug, post_summary = :summary, 
                        post_content = :content, post_image = :image, post_status = :status, updated_at = NOW()
                    WHERE id = :id
                ", [
                    'title' => $title,
                    'slug' => $slug,
                    'summary' => $summary,
                    'content' => $content,
                    'image' => $imageUrl,
                    'status' => $status,
                    'id' => $id
                ]);
                $_SESSION['flash_success'] = 'Cập nhật bài viết thành công!';
                header('Location: ' . BASE_URL . 'admin/posts');
                exit();
            }
        }

        $this->view->app('post/form', [
            'title' => 'Chỉnh Sửa Bài Viết #' . $id,
            'post' => $post
        ]);
    }

    public function post_delete($args = []) {
        $id = (int)($args[1] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            $db = database::getInstance();
            $db->query("UPDATE website_posts SET post_status = -1, updated_at = NOW() WHERE id = :id", ['id' => $id]);
            $_SESSION['flash_success'] = 'Xóa bài viết thành công (Đã chuyển vào lưu trữ).';
        }
        header('Location: ' . BASE_URL . 'admin/posts');
        exit();
    }

    public function post_toggle($args = []) {
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)($args[1] ?? $_POST['id'] ?? 0);
        $status = (int)($_POST['status'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['status' => 400, 'message' => 'ID không hợp lệ.']);
            exit();
        }

        $db = database::getInstance();
        $db->query("UPDATE website_posts SET post_status = :st, updated_at = NOW() WHERE id = :id", [
            'st' => $status,
            'id' => $id
        ]);

        echo json_encode(['status' => 200, 'message' => 'Cập nhật trạng thái bài viết thành công.']);
        exit();
    }

    public function post_upload_inline_image() {
        header('Content-Type: application/json; charset=utf-8');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['inline_image'])) {
            echo json_encode(['status' => 400, 'message' => 'Không tìm thấy tệp tải lên.']);
            exit();
        }

        $file = $_FILES['inline_image'];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['status' => 400, 'message' => 'Lỗi khi tải file (Mã: ' . $file['error'] . ')']);
            exit();
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowed)) {
            echo json_encode(['status' => 400, 'message' => 'Định dạng ảnh không hợp lệ (Chỉ hỗ trợ JPG, PNG, WEBP, GIF).']);
            exit();
        }

        $newName = 'inline_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $uploadDir = __SITE_PATH . '/public/uploads/posts/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newName)) {
            $url = BASE_URL . 'public/uploads/posts/' . $newName;
            echo json_encode(['status' => 200, 'url' => $url, 'message' => 'Tải ảnh thành công!']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Không thể lưu file trên máy chủ.']);
        }
        exit();
    }

    // =========================================================================

    // 3. QUẢN LÝ ĐĂNG KÝ THUÊ SẠP
    // =========================================================================



    public function registrations() {
        $db = database::getInstance();
        $statusFilter = $_GET['status'] ?? '';
        $sql = "SELECT * FROM stall_registrations";
        $params = [];

        if (!empty($statusFilter)) {
            $sql .= " WHERE status = :st";
            $params['st'] = $statusFilter;
        }
        $sql .= " ORDER BY id DESC";

        $registrations = [];
        try {
            $registrations = $db->select($sql, $params);
        } catch (Exception $e) {}

        $this->view->app('registration/index', [
            'title' => 'Quản Lý Đăng Ký Thuê Sạp',
            'registrations' => $registrations
        ]);
    }

    public function registration_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = database::getInstance();
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'pending';
            $adminNote = trim($_POST['admin_note'] ?? '');

            if ($id > 0) {
                $db->query("
                    UPDATE stall_registrations 
                    SET status = :status, admin_note = :note 
                    WHERE id = :id
                ", [
                    'status' => $status,
                    'note' => $adminNote,
                    'id' => $id
                ]);
                $_SESSION['flash_success'] = 'Cập nhật trạng thái đăng ký thuê sạp thành công.';
            }
        }
        header('Location: ' . BASE_URL . 'admin/registrations');
        exit();
    }

    public function registration_delete($args = []) {
        $id = (int)($args[1] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            $db = database::getInstance();
            $db->query("DELETE FROM stall_registrations WHERE id = :id", ['id' => $id]);
            $_SESSION['flash_success'] = 'Xóa yêu cầu đăng ký thuê sạp thành công.';
        }
        header('Location: ' . BASE_URL . 'admin/registrations');
        exit();
    }

    // =========================================================================
    // 4. QUẢN LÝ KHIẾU NẠI & GÓP Ý
    // =========================================================================

    public function feedbacks() {
        $db = database::getInstance();
        $typeFilter = $_GET['type'] ?? '';
        $sql = "SELECT * FROM website_feedbacks";
        $params = [];

        if (!empty($typeFilter)) {
            $sql .= " WHERE type = :tp";
            $params['tp'] = $typeFilter;
        }
        $sql .= " ORDER BY id DESC";

        $feedbacks = [];
        try {
            $feedbacks = $db->select($sql, $params);
        } catch (Exception $e) {}

        $this->view->app('feedback/index', [
            'title' => 'Quản Lý Khiếu Nại & Góp Ý',
            'feedbacks' => $feedbacks
        ]);
    }

    public function feedback_status() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = database::getInstance();
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'resolved';
            $reply = trim($_POST['reply_content'] ?? '');
            $fbStatusVal = ($status === 'resolved') ? 3 : (($status === 'processing') ? 2 : 1);

            if ($id > 0) {
                $db->query("
                    UPDATE website_feedbacks 
                    SET status = :status, fb_status = :fb_status, reply_content = :reply 
                    WHERE id = :id
                ", [
                    'status' => $status,
                    'fb_status' => $fbStatusVal,
                    'reply' => $reply,
                    'id' => $id
                ]);
                $_SESSION['flash_success'] = 'Cập nhật nội dung phản hồi thành công.';
            }
        }
        header('Location: ' . BASE_URL . 'admin/feedbacks');
        exit();
    }


    public function feedback_delete($args = []) {
        $id = (int)($args[1] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            $db = database::getInstance();
            $db->query("DELETE FROM website_feedbacks WHERE id = :id", ['id' => $id]);
            $_SESSION['flash_success'] = 'Xóa phản ánh khiếu nại/góp ý thành công.';
        }
        header('Location: ' . BASE_URL . 'admin/feedbacks');
        exit();
    }

    // =========================================================================
    // 5. QUẢN LÝ TÀI KHOẢN & PHÂN QUYỀN WEB (RBAC)
    // =========================================================================

    public function users() {
        $db = database::getInstance();
        $users = [];
        try {
            $users = $db->select("SELECT * FROM web_users WHERE status != -1 ORDER BY id DESC");
        } catch (Exception $e) {}

        $this->view->app('user/index', [
            'title' => 'Quản Lý Tài Khoản Web & Phân Quyền',
            'users' => $users
        ]);
    }

    public function user_add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = database::getInstance();
            $username = trim($_POST['username'] ?? '');
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = in_array($_POST['role'] ?? '', ['admin', 'editor']) ? $_POST['role'] : 'editor';
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
            
            // Xử lý danh sách module permissions
            $permsInput = $_POST['permissions'] ?? [];
            $permissionsStr = is_array($permsInput) ? implode(',', array_filter($permsInput)) : ($permsInput ?: '');
            if ($role === 'admin') $permissionsStr = 'all';

            if (empty($username) || empty($fullname) || empty($password)) {
                $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ các thông tin bắt buộc.';
                header('Location: ' . BASE_URL . 'admin/user_add');
                exit();
            }

            $exists = $db->selectOne("SELECT id FROM web_users WHERE username = :u", ['u' => $username]);
            if ($exists) {
                $_SESSION['flash_error'] = 'Tên đăng nhập đã tồn tại.';
                header('Location: ' . BASE_URL . 'admin/user_add');
                exit();
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $db->query("
                INSERT INTO web_users (username, password, fullname, email, phone, role, permissions, status)
                VALUES (:username, :password, :fullname, :email, :phone, :role, :permissions, :status)
            ", [
                'username' => $username,
                'password' => $hashedPassword,
                'fullname' => $fullname,
                'email' => $email,
                'phone' => $phone,
                'role' => $role,
                'permissions' => $permissionsStr,
                'status' => $status
            ]);

            $_SESSION['flash_success'] = 'Thêm mới tài khoản và phân quyền thành công.';
            header('Location: ' . BASE_URL . 'admin/users');
            exit();
        }

        $webRoles = [];
        try {
            $webRoles = $db->select("SELECT * FROM web_roles WHERE status = 1 ORDER BY id ASC");
        } catch (Exception $e) {}

        $this->view->app('user/add', [
            'title' => 'Thêm Tài Khoản Web Mới',
            'webRoles' => $webRoles
        ]);
    }

    public function user_edit($args = []) {
        $userId = (int)($args[1] ?? $_GET['id'] ?? 0);
        $db = database::getInstance();
        $user = $db->selectOne("SELECT * FROM web_users WHERE id = :id", ['id' => $userId]);

        if (!$user) {
            header('Location: ' . BASE_URL . 'admin/users');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = trim($_POST['role'] ?? 'editor');
            $status = isset($_POST['status']) ? (int)$_POST['status'] : 1;

            $permsInput = $_POST['permissions'] ?? [];
            $permissionsStr = is_array($permsInput) ? implode(',', array_filter($permsInput)) : ($permsInput ?: '');
            if ($role === 'admin') $permissionsStr = 'all';

            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $db->query("
                    UPDATE web_users 
                    SET fullname = :fullname, email = :email, phone = :phone, password = :password, role = :role, permissions = :permissions, status = :status
                    WHERE id = :id
                ", [
                    'fullname' => $fullname,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $hashedPassword,
                    'role' => $role,
                    'permissions' => $permissionsStr,
                    'status' => $status,
                    'id' => $userId
                ]);
            } else {
                $db->query("
                    UPDATE web_users 
                    SET fullname = :fullname, email = :email, phone = :phone, role = :role, permissions = :permissions, status = :status
                    WHERE id = :id
                ", [
                    'fullname' => $fullname,
                    'email' => $email,
                    'phone' => $phone,
                    'role' => $role,
                    'permissions' => $permissionsStr,
                    'status' => $status,
                    'id' => $userId
                ]);
            }

            $_SESSION['flash_success'] = 'Cập nhật tài khoản và phân quyền thành công.';
            header('Location: ' . BASE_URL . 'admin/users');
            exit();
        }

        $webRoles = [];
        try {
            $webRoles = $db->select("SELECT * FROM web_roles WHERE status = 1 ORDER BY id ASC");
        } catch (Exception $e) {}

        $this->view->app('user/edit', [
            'title' => 'Chỉnh Sửa Tài Khoản & Phân Quyền Web',
            'user' => $user,
            'webRoles' => $webRoles
        ]);
    }


    public function user_delete($args = []) {
        $userId = (int)($args[1] ?? $_GET['id'] ?? 0);
        if ($userId > 0 && $userId != session::getWebUser('id')) {
            $db = database::getInstance();
            $db->query("UPDATE web_users SET status = -1 WHERE id = :id", ['id' => $userId]);
            $_SESSION['flash_success'] = 'Xóa tài khoản thành công (Đã chuyển vào lưu trữ).';
        }
        header('Location: ' . BASE_URL . 'admin/users');
        exit();
    }

    public function roles() {
        $db = database::getInstance();
        $roles = [];
        try {
            $roles = $db->select("SELECT * FROM web_roles ORDER BY id ASC");
        } catch (Exception $e) {}

        $this->view->app('user/roles', [
            'title' => 'Quản Lý Vai Trò Trang Web',
            'roles' => $roles
        ]);
    }

    public function role_add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = database::getInstance();
            $roleName = trim($_POST['role_name'] ?? '');
            $roleCode = trim($_POST['role_code'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $permsInput = $_POST['permissions'] ?? [];

            $permsStr = is_array($permsInput) ? implode(',', array_filter($permsInput)) : '';

            if (!empty($roleName) && !empty($roleCode)) {
                try {
                    $db->query("
                        INSERT INTO web_roles (role_name, role_code, permissions, description, status)
                        VALUES (:role_name, :role_code, :permissions, :description, 1)
                    ", [
                        'role_name' => $roleName,
                        'role_code' => strtolower($roleCode),
                        'permissions' => $permsStr,
                        'description' => $description
                    ]);
                    $_SESSION['flash_success'] = 'Thêm vai trò mới thành công.';
                } catch (Exception $e) {
                    $_SESSION['flash_error'] = 'Lỗi: Mã vai trò đã tồn tại hoặc dữ liệu không hợp lệ.';
                }
            }
        }
        header('Location: ' . BASE_URL . 'admin/roles');
        exit();
    }

    public function role_edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = database::getInstance();
            $id = (int)($_POST['id'] ?? 0);
            $roleName = trim($_POST['role_name'] ?? '');
            $roleCode = trim($_POST['role_code'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $permsInput = $_POST['permissions'] ?? [];

            $permsStr = is_array($permsInput) ? implode(',', array_filter($permsInput)) : '';

            if ($id > 0 && !empty($roleName)) {
                try {
                    $cleanRoleCode = strtolower($roleCode);
                    $db->query("
                        UPDATE web_roles
                        SET role_name = :role_name, role_code = :role_code, permissions = :permissions, description = :description
                        WHERE id = :id
                    ", [
                        'role_name' => $roleName,
                        'role_code' => $cleanRoleCode,
                        'permissions' => $permsStr,
                        'description' => $description,
                        'id' => $id
                    ]);

                    // Đồng bộ quyền mới cập nhật cho toàn bộ tài khoản đang thuộc vai trò này
                    $db->query("
                        UPDATE web_users 
                        SET permissions = :permissions 
                        WHERE role = :role_code
                    ", [
                        'permissions' => $permsStr,
                        'role_code' => $cleanRoleCode
                    ]);

                    $_SESSION['flash_success'] = 'Cập nhật vai trò và đồng bộ phân quyền thành công.';
                } catch (Exception $e) {}
            }
        }
        header('Location: ' . BASE_URL . 'admin/roles');
        exit();
    }

    public function role_delete($args = []) {
        $id = (int)($args[1] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            $db = database::getInstance();
            $db->query("UPDATE web_roles SET status = -1 WHERE id = :id AND role_code != 'admin'", ['id' => $id]);
            $_SESSION['flash_success'] = 'Xóa vai trò thành công.';
        }
        header('Location: ' . BASE_URL . 'admin/roles');
        exit();
    }

    public function permissions() {
        $db = database::getInstance();
        $users = [];
        $webRoles = [];
        try {
            $users = $db->select("SELECT id, username, fullname, email, role, permissions, status FROM web_users WHERE status != -1 ORDER BY id ASC");
            $webRoles = $db->select("SELECT * FROM web_roles WHERE status = 1 ORDER BY id ASC");
        } catch (Exception $e) {}

        $this->view->app('user/permissions', [
            'title' => 'Cấu Hình Phân Quyền Web',
            'users' => $users,
            'webRoles' => $webRoles
        ]);
    }

    public function update_user_permission() {
        header('Content-Type: application/json; charset=utf-8');
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        $userId = (int)($data['user_id'] ?? 0);
        $module = trim($data['module'] ?? '');
        $status = (int)($data['status'] ?? 0);

        if ($userId <= 0 || empty($module)) {
            echo json_encode(['status' => 400, 'message' => 'Dữ liệu không hợp lệ.']);
            exit();
        }

        $db = database::getInstance();
        $user = $db->select("SELECT id, permissions, role FROM web_users WHERE id = :id", ['id' => $userId]);
        if (empty($user)) {
            echo json_encode(['status' => 404, 'message' => 'Tài khoản không tồn tại.']);
            exit();
        }

        $currentPerms = array_filter(array_map('trim', explode(',', $user[0]['permissions'] ?? '')));

        if ($status === 1) {
            if (!in_array($module, $currentPerms)) {
                $currentPerms[] = $module;
            }
        } else {
            $currentPerms = array_values(array_filter($currentPerms, function($m) use ($module) {
                return $m !== $module;
            }));
        }

        $newPermsStr = implode(',', $currentPerms);
        $db->query("UPDATE web_users SET permissions = :perms WHERE id = :id", [
            'perms' => $newPermsStr,
            'id' => $userId
        ]);

        echo json_encode(['status' => 200, 'message' => 'Đã cập nhật quyền thành công.', 'permissions' => $currentPerms]);
        exit();
    }

    public function apply_role_template() {
        header('Content-Type: application/json; charset=utf-8');
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        $userId = (int)($data['user_id'] ?? 0);
        $roleCode = trim($data['role_code'] ?? '');

        if ($userId <= 0 || empty($roleCode)) {
            echo json_encode(['status' => 400, 'message' => 'Dữ liệu không hợp lệ.']);
            exit();
        }

        $db = database::getInstance();
        $role = $db->select("SELECT * FROM web_roles WHERE role_code = :code AND status = 1", ['code' => $roleCode]);
        if (empty($role)) {
            echo json_encode(['status' => 404, 'message' => 'Mẫu vai trò không tồn tại.']);
            exit();
        }

        $roleInfo = $role[0];
        $permsStr = $roleInfo['permissions'] ?? '';
        $db->query("UPDATE web_users SET role = :role, permissions = :perms WHERE id = :id", [
            'role' => $roleCode,
            'perms' => $permsStr,
            'id' => $userId
        ]);

        $permsArr = array_filter(array_map('trim', explode(',', $permsStr)));
        echo json_encode([
            'status' => 200, 
            'message' => 'Đã áp dụng vai trò "' . $roleInfo['role_name'] . '" thành công!', 
            'role_code' => $roleCode,
            'role_name' => $roleInfo['role_name'],
            'permissions' => $permsArr
        ]);
        exit();
    }
}

