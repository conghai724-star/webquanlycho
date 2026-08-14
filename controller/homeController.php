<?php

Class homeController Extends baseController
{
    private function ensureMarketResultTable()
    {
        global $db;
        $db->query("CREATE TABLE IF NOT EXISTS hicrm_market_results (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            result_title varchar(255) NOT NULL,
            result_summary text DEFAULT NULL,
            result_content longtext DEFAULT NULL,
            result_image varchar(500) DEFAULT NULL,
            result_date date DEFAULT NULL,
            company_total int(11) NOT NULL DEFAULT 0,
            position_total int(11) NOT NULL DEFAULT 0,
            profile_total int(11) NOT NULL DEFAULT 0,
            interview_total int(11) NOT NULL DEFAULT 0,
            implementation_content longtext DEFAULT NULL,
            highlight_content longtext DEFAULT NULL,
            note_content text DEFAULT NULL,
            result_status tinyint(4) NOT NULL DEFAULT 1,
            created_by bigint(20) unsigned DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_result_status_date (result_status, result_date),
            KEY idx_created_by (created_by)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    private function showFrontend($viewName, $activePage = 'home') {

        global $db;
        $this->view->data['activePage'] = $activePage;
        if (!isset($this->view->data['settings'])) {
            $settings = [];
            try {
                $rows = $db->select("SELECT setting_key, setting_value FROM website_settings");
                foreach ($rows as $r) {
                    $settings[$r['setting_key']] = $r['setting_value'];
                }
            } catch (Exception $e) {}
            $this->view->data['settings'] = $settings;
        }
        $settings = $this->view->data['settings'] ?? [];
        include __SITE_PATH . '/template/frontend/layouts/header.php';
        include __SITE_PATH . '/template/frontend/layouts/navbar.php';
        $this->view->show("home/" . $viewName);
        include __SITE_PATH . '/template/frontend/layouts/footer.php';
    }


    public function index()
    {
        global $db;

        // 1. Thống kê lượt truy cập (View counter)
        try {
            $db->query("UPDATE website_stats SET stat_value = stat_value + 1 WHERE stat_key = 'total_views'");
            $viewsRow = $db->selectOne("SELECT stat_value FROM website_stats WHERE stat_key = 'total_views'");
            $this->view->data['total_views'] = $viewsRow ? $viewsRow['stat_value'] : 3541;
        } catch (Exception $e) {
            $this->view->data['total_views'] = 3541;
        }

        // 2. Lấy danh sách banner trượt Trang chủ
        try {
            $this->view->data['banners'] = $db->select("SELECT * FROM website_banners WHERE banner_status = 1 AND banner_page IN ('home', 'all') ORDER BY banner_order ASC, id DESC");
        } catch (Exception $e) {
            $this->view->data['banners'] = [];
        }


        // 3. Lấy 3 tin tức/thông báo mới nhất
        try {
            $this->view->data['posts'] = $db->select("SELECT * FROM website_posts WHERE post_status = 1 ORDER BY created_at DESC LIMIT 3");
        } catch (Exception $e) {
            $this->view->data['posts'] = [];
        }

        // 4. Lấy số liệu thống kê thực tế để hiển thị ở trang chủ
        try {
            // Tổng số chợ (markets)
            $marketsRow = $db->selectOne("SELECT COUNT(*) AS total FROM markets WHERE market_status_code = 'active'");
            $this->view->data['total_markets'] = $marketsRow ? (int)$marketsRow['total'] : 0;

            // Tổng số khu vực (areas)
            $areasRow = $db->selectOne("SELECT COUNT(*) AS total FROM areas");
            $this->view->data['total_areas'] = $areasRow ? (int)$areasRow['total'] : 0;

            // Tổng số sạp (stalls)
            $stallsRow = $db->selectOne("SELECT COUNT(*) AS total FROM stalls");
            $this->view->data['total_stalls'] = $stallsRow ? (int)$stallsRow['total'] : 0;

            // Tổng tiểu thương hoạt động (traders)
            $tradersRow = $db->selectOne("SELECT COUNT(*) AS total FROM traders");
            $this->view->data['total_traders'] = $tradersRow ? (int)$tradersRow['total'] : 0;

            // Tổng sạp còn trống thực tế (vacant stalls)
            $vacantRow = $db->selectOne("
                SELECT COUNT(*) AS total 
                FROM stalls s 
                JOIN areas a ON s.stall_area_id = a.area_id
                JOIN markets m ON a.area_market_id = m.market_id
                JOIN system_statuses ss ON s.stall_status_id = ss.status_id 
                WHERE ss.status_code = 'empty' AND m.market_status_code = 'active'
            ");
            $this->view->data['total_vacant_stalls'] = $vacantRow ? (int)$vacantRow['total'] : 0;

            // 5. Danh sách sạp thực tế đang còn trống trên toàn hệ thống các chợ
            $vacantStalls = $db->select("
                SELECT s.stall_id, s.stall_code, s.stall_area_size, s.stall_base_price,
                       a.area_name, a.area_description,
                       m.market_id, m.market_name
                FROM stalls s
                JOIN areas a ON s.stall_area_id = a.area_id
                JOIN markets m ON a.area_market_id = m.market_id
                JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                WHERE ss.status_code = 'empty' AND m.market_status_code = 'active'
                ORDER BY s.stall_id ASC
                LIMIT 6
            ");
            $this->view->data['vacantStalls'] = $vacantStalls;
        } catch (Exception $e) {
            $this->view->data['total_markets'] = 0;
            $this->view->data['total_areas'] = 0;
            $this->view->data['total_stalls'] = 0;
            $this->view->data['total_traders'] = 0;
            $this->view->data['total_vacant_stalls'] = 0;
            $this->view->data['vacantStalls'] = [];
        }

        $this->showFrontend('index', 'home');
    }
    public function introduce(){
        $this->about();
    }
   
    public function guidelines(){
        $this->map_tree();
    }

    public function about() {
        global $db;
        try {
            $this->view->data['banners'] = $db->select("SELECT * FROM website_banners WHERE banner_status = 1 AND banner_page IN ('about', 'all') ORDER BY banner_order ASC");
        } catch (Exception $e) {
            $this->view->data['banners'] = [];
        }

        try {
            $sections = $db->select("SELECT * FROM website_about_sections WHERE status = 1 ORDER BY section_order ASC, id ASC");
            $this->view->data['about_sections'] = $sections;
        } catch (Exception $e) {
            $this->view->data['about_sections'] = [];
        }

        $this->showFrontend('about', 'about');
    }

    /**
     * Hiển thị trang Đăng ký Thuê Sạp và xử lý nộp hồ sơ
     */
    public function register() {
        global $db;
        $success = false;
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cccd = trim($_POST['cccd'] ?? '');
            $stallCode = trim($_POST['stall_code'] ?? '');
            $businessItem = trim($_POST['business_item'] ?? $_POST['business_line'] ?? '');
            $note = trim($_POST['note'] ?? '');
            $areaName = trim($_POST['area_name'] ?? '');
            $areaSize = trim($_POST['area_size'] ?? $_POST['area'] ?? '');

            if (!empty($areaName) || !empty($areaSize)) {
                $extraInfo = "\n[Thông tin sạp]: Khu vực: $areaName | Sạp: $stallCode | Diện tích: {$areaSize}m²";
                $note = trim($note . $extraInfo);
            }

            if (!empty($fullname) && !empty($phone)) {
                $db->query("
                    INSERT INTO stall_registrations (fullname, phone, email, cccd, stall_code, business_item, note, status)
                    VALUES (:fullname, :phone, :email, :cccd, :stall_code, :business_item, :note, 'pending')
                ", [
                    'fullname' => $fullname,
                    'phone' => $phone,
                    'email' => $email,
                    'cccd' => $cccd,
                    'stall_code' => $stallCode,
                    'business_item' => $businessItem,
                    'note' => $note
                ]);
                $success = true;
            } else {
                $error = 'Vui lòng điền đầy đủ Họ tên và Số điện thoại.';
            }
        }

        // Lấy danh sách các chợ
        $markets = [];
        try {
            $markets = $db->select("SELECT market_id, market_code, market_name FROM markets WHERE market_status_code = 'active' ORDER BY market_id ASC");
        } catch (Exception $e) {}

        // Lấy danh sách các khu vực CÒN SẠP TRỐNG (stall_status_id = 3) kèm thông tin chợ
        $areas = [];
        try {
            $areas = $db->select("
                SELECT a.area_id, a.area_name, a.area_description, a.area_market_id,
                       COALESCE(m.market_name, 'Chợ chung') AS market_name,
                       COUNT(s.stall_id) AS empty_count
                FROM areas a
                LEFT JOIN markets m ON a.area_market_id = m.market_id
                INNER JOIN stalls s ON a.area_id = s.stall_area_id
                WHERE s.stall_status_id = 3
                GROUP BY a.area_id, a.area_name, a.area_description, a.area_market_id, m.market_name
                HAVING empty_count > 0
                ORDER BY a.area_market_id ASC, a.area_id ASC
            ");
        } catch (Exception $e) {}

        // Lấy danh sách các sạp CÒN TRỐNG (stall_status_id = 3) kèm market_id
        $stalls = [];
        try {
            $stalls = $db->select("
                SELECT s.stall_id, s.stall_code, s.stall_area_id, a.area_market_id, s.stall_area_size, s.stall_base_price, s.stall_status_id,
                       COALESCE(ss.status_name, 'Trống') AS status_name
                FROM stalls s
                JOIN areas a ON s.stall_area_id = a.area_id
                LEFT JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                WHERE s.stall_status_id = 3
                ORDER BY s.stall_area_id ASC, s.stall_code ASC
            ");
        } catch (Exception $e) {}

        // Danh sách sạp trống đầy đủ hiển thị dạng Card trực quan trên trang Đăng ký
        $vacantStallsCardList = [];
        try {
            $vacantStallsCardList = $db->select("
                SELECT s.stall_id, s.stall_code, s.stall_area_size, s.stall_base_price,
                       a.area_id, a.area_name, a.area_description,
                       m.market_id, m.market_name
                FROM stalls s
                JOIN areas a ON s.stall_area_id = a.area_id
                JOIN markets m ON a.area_market_id = m.market_id
                JOIN system_statuses ss ON s.stall_status_id = ss.status_id
                WHERE ss.status_code = 'empty' AND m.market_status_code = 'active'
                ORDER BY m.market_id ASC, a.area_id ASC, s.stall_id ASC
            ");
        } catch (Exception $e) {}

        $this->view->data['markets'] = $markets;
        $this->view->data['areas'] = $areas;
        $this->view->data['stalls'] = $stalls;
        $this->view->data['vacantStallsCardList'] = $vacantStallsCardList;
        $this->view->data['success'] = $success;
        $this->view->data['error'] = $error;

        $this->showFrontend('register', 'register');
    }

    /**
     * Tiếp nhận Đăng ký Thuê Sạp từ Website công khai (API/AJAX Fallback)
     */
    public function submit_registration() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $db;
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $cccd = trim($_POST['cccd'] ?? '');
            $businessItem = trim($_POST['business_item'] ?? '');
            $note = trim($_POST['note'] ?? '');

            if (!empty($fullname) && !empty($phone)) {
                $db->query("
                    INSERT INTO stall_registrations (fullname, phone, email, cccd, business_item, note, status)
                    VALUES (:fullname, :phone, :email, :cccd, :business_item, :note, 'pending')
                ", [
                    'fullname' => $fullname,
                    'phone' => $phone,
                    'email' => $email,
                    'cccd' => $cccd,
                    'business_item' => $businessItem,
                    'note' => $note
                ]);
                $_SESSION['flash_success'] = 'Gửi yêu cầu đăng ký thuê sạp thành công! BQL sẽ liên hệ lại với quý khách.';
            } else {
                $_SESSION['flash_error'] = 'Vui lòng nhập đầy đủ họ tên và số điện thoại.';
            }
        }
        header('Location: ' . BASE_URL . 'home/index');
        exit();
    }

    /**
     * Tiếp nhận Khiếu nại / Góp ý từ Website công khai
     */
    public function submit_feedback() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $db;
            $fullname = trim($_POST['fullname'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $type = in_array($_POST['type'] ?? '', ['feedback', 'complaint']) ? $_POST['type'] : 'feedback';
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');

            if (!empty($title) && !empty($content)) {
                $db->query("
                    INSERT INTO website_feedbacks (type, title, fb_fullname, fb_phone, fb_email, fb_content, fb_status)
                    VALUES (:type, :title, :fullname, :phone, :email, :content, 1)
                ", [
                    'type' => $type,
                    'title' => $title,
                    'fullname' => $fullname,
                    'phone' => $phone,
                    'email' => $email,
                    'content' => $content
                ]);
                $_SESSION['flash_success'] = 'Cảm ơn quý khách đã gửi ý kiến phản ánh / đóng góp cho BQL!';
            } else {
                $_SESSION['flash_error'] = 'Vui lòng nhập đầy đủ tiêu đề và nội dung phản ánh.';
            }
        }
        header('Location: ' . BASE_URL . 'home/contact');
        exit();
    }


    public function map() {
        global $db;
        include_once __SITE_PATH . '/model/mapModel.php';
        include_once __SITE_PATH . '/model/marketModel.php';
        $mapModel = new mapModel();
        $marketModel = new marketModel();
        
        $markets = [];
        try {
            $markets = $db->select("SELECT market_id, market_code, market_name, market_latitude, market_longitude, market_map_zoom FROM markets WHERE market_status_code = 'active' ORDER BY market_id ASC");
        } catch (Exception $e) {}

        $marketId = isset($_GET['market_id']) ? (int)$_GET['market_id'] : 0;

        $currentMarket = null;
        if ($marketId > 0) {
            foreach ($markets as $m) {
                if ((int)$m['market_id'] === $marketId) {
                    $currentMarket = $m;
                    break;
                }
            }
        }

        $this->view->data['markets'] = $markets;
        $this->view->data['marketId'] = $marketId;
        $this->view->data['market'] = $currentMarket;
        // Luôn tải đầy đủ tất cả phần tử và khu vực của toàn bộ các chợ trên cùng 1 bản đồ
        $this->view->data['elements'] = $mapModel->getElements(0);
        $this->view->data['areas'] = $db->select("SELECT a.area_id, a.area_name, a.area_description, a.area_market_id, m.market_name FROM areas a LEFT JOIN markets m ON a.area_market_id = m.market_id ORDER BY a.area_market_id ASC, a.area_name ASC");
        $this->view->data['businessLines'] = $db->select("SELECT line_id, line_name FROM business_lines ORDER BY line_name ASC");

        $this->showFrontend('map', 'map');
    }

    public function map_tree() {
        $this->showFrontend('tree', 'map_tree');
    }

    public function traders() {
        global $db;
        $isLoggedIn = $this->helper->isLoggedIn();
        
        $search = isset($_GET['search']) ? trim((string)$_GET['search']) : '';
        $marketId = (int)($_GET['market_id'] ?? 0);
        $businessLineId = (int)($_GET['business_line_id'] ?? $_GET['line_id'] ?? 0);
        $areaId = (int)($_GET['area_id'] ?? 0);
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $markets = [];
        try {
            $markets = $db->select("SELECT market_id, market_code, market_name FROM markets WHERE market_status_code = 'active' ORDER BY market_id ASC");
        } catch (Exception $e) {}

        // Danh sách ngành hàng
        $businessLines = [];
        try {
            $businessLines = $db->select("SELECT line_id, line_name FROM business_lines ORDER BY line_name ASC");
        } catch (Exception $e) {}

        // Danh sách khu vực
        $areas = [];
        try {
            if ($marketId > 0) {
                $areas = $db->select("SELECT area_id, area_name, area_market_id FROM areas WHERE area_market_id = :mid ORDER BY area_name ASC", ['mid' => $marketId]);
            } else {
                $areas = $db->select("SELECT a.area_id, a.area_name, a.area_market_id, m.market_name FROM areas a LEFT JOIN markets m ON a.area_market_id = m.market_id ORDER BY a.area_market_id ASC, a.area_name ASC");
            }
        } catch (Exception $e) {}

        $selectedMarketName = '';
        if ($marketId > 0) {
            foreach ($markets as $m) {
                if ((int)$m['market_id'] === $marketId) {
                    $selectedMarketName = $m['market_name'];
                    break;
                }
            }
        }

        $whereConditions = [];
        $queryParams = [];
        
        if ($search !== '') {
            $whereConditions[] = "(t.trader_fullname LIKE :search OR t.trader_code LIKE :search OR bl.line_name LIKE :search)";
            $queryParams['search'] = '%' . $search . '%';
        }

        if ($marketId > 0) {
            $whereConditions[] = "(a.area_market_id = :mid OR t.trader_market_id = :mid OR m.market_id = :mid)";
            $queryParams['mid'] = $marketId;
        }

        if ($businessLineId > 0) {
            $whereConditions[] = "t.trader_business_line_id = :blid";
            $queryParams['blid'] = $businessLineId;
        }

        if ($areaId > 0) {
            $whereConditions[] = "a.area_id = :aid";
            $queryParams['aid'] = $areaId;
        }

        $whereClause = !empty($whereConditions) ? " WHERE " . implode(" AND ", $whereConditions) : "";

        // Count total traders matching all filters
        try {
            $countSql = "SELECT COUNT(DISTINCT t.trader_id) AS total 
                         FROM traders t 
                         LEFT JOIN business_lines bl ON t.trader_business_line_id = bl.line_id
                         LEFT JOIN contracts c ON c.contract_trader_id = t.trader_id
                         LEFT JOIN stalls s ON s.stall_id = c.contract_stall_id
                         LEFT JOIN areas a ON a.area_id = s.stall_area_id
                         LEFT JOIN markets m ON m.market_id = a.area_market_id" . $whereClause;
            
            $totalRow = $db->selectOne($countSql, $queryParams);
            $totalTraders = $totalRow ? (int)$totalRow['total'] : 0;
        } catch (Exception $e) {
            $totalTraders = 0;
        }

        $totalPages = ceil($totalTraders / $limit);
        if ($page > $totalPages && $totalPages > 0) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        // Fetch paginated traders
        try {
            $sql = "SELECT t.trader_code, t.trader_fullname AS fullname, t.trader_phone AS phone, t.trader_address AS address, 
                           bl.line_name AS business_line_name,
                           COALESCE(m.market_name, m_direct.market_name, 'Chợ Phường Quyết Thắng') AS market_name,
                           COALESCE(a.area_name, 'Khu kinh doanh') AS area_name,
                           s.stall_code
                    FROM traders t
                    LEFT JOIN business_lines bl ON t.trader_business_line_id = bl.line_id
                    LEFT JOIN contracts c ON c.contract_trader_id = t.trader_id
                    LEFT JOIN stalls s ON s.stall_id = c.contract_stall_id
                    LEFT JOIN areas a ON a.area_id = s.stall_area_id
                    LEFT JOIN markets m ON m.market_id = a.area_market_id
                    LEFT JOIN markets m_direct ON m_direct.market_id = t.trader_market_id" . 
                    $whereClause . "
                    GROUP BY t.trader_id
                    ORDER BY t.trader_fullname ASC 
                    LIMIT $limit OFFSET $offset";
            
            $traders = $db->select($sql, $queryParams);
        } catch (Exception $e) {
            $traders = [];
        }

        if (!$isLoggedIn) {
            foreach ($traders as &$trader) {
                if (!empty($trader['phone'])) {
                    $trader['phone'] = substr($trader['phone'], 0, 4) . ' *** ***';
                } else {
                    $trader['phone'] = 'Chưa cập nhật';
                }
                $trader['address'] = 'Đăng nhập để xem';
            }
        }
        
        $this->view->data['markets'] = $markets;
        $this->view->data['businessLines'] = $businessLines;
        $this->view->data['areas'] = $areas;
        $this->view->data['marketId'] = $marketId;
        $this->view->data['businessLineId'] = $businessLineId;
        $this->view->data['areaId'] = $areaId;
        $this->view->data['selectedMarketName'] = $selectedMarketName;
        $this->view->data['traders'] = $traders;
        $this->view->data['isLoggedIn'] = $isLoggedIn;
        $this->view->data['currentPage'] = $page;
        $this->view->data['totalPages'] = $totalPages;
        $this->view->data['totalTraders'] = $totalTraders;
        $this->view->data['searchQuery'] = $search;

        $this->showFrontend('traders', 'traders');
    }

    public function login() {
        header("Location: " . BASE_URL . "login");
        exit();
    }
    public function unverified_account()
    {
        $pending = isset($_SESSION['frontend_pending_verification']) && is_array($_SESSION['frontend_pending_verification'])
            ? $_SESSION['frontend_pending_verification']
            : array();
        $scriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', (string)$_SERVER['SCRIPT_NAME']) : '';
        $basePath = rtrim(dirname($scriptName), '/');
        if($basePath === '/' || $basePath === '.'){
            $basePath = '';
        }

        $email = isset($pending['email']) ? trim((string)$pending['email']) : '';
        $this->view->data['page_title'] = 'Tài khoản của bạn chưa xác thực';
        $this->view->data['page_description'] = $email !== ''
            ? 'Tài khoản của bạn chưa được xác thực. Vui lòng chọn "Xác thực ngay" để có thể đăng nhập. Hệ thống sẽ gửi lại liên kết xác thực về '.$email.'.'
            : 'Tài khoản của bạn chưa được xác thực. Vui lòng chọn "Xác thực ngay" để có thể đăng nhập. Hệ thống sẽ gửi lại liên kết xác thực về email đã đăng ký.';
        $this->view->data['verify_email'] = 0;
        $this->view->data['page_action_label'] = 'Xác thực ngay';
        $this->view->data['page_action_api'] = $basePath.'/api/resendVerificationEmail';
        $this->view->data['page_action_payload'] = array(
            'user_id' => isset($pending['user_id']) ? intval($pending['user_id']) : 0,
            'email' => $email
        );
        $this->view->show("404");
    }
    public function manage_applicants($para = array()){
        global $db;
        $keyword = trim(isset($_GET['keyword']) ? $_GET['keyword'] : '');
        $provinceId = intval(isset($_GET['province_id']) ? $_GET['province_id'] : 0);
        $categoryId = intval(isset($_GET['job_category_id']) ? $_GET['job_category_id'] : 0);
        $salaryId = intval(isset($_GET['salary_id']) ? $_GET['salary_id'] : 0);
        $degree = trim(isset($_GET['degree']) ? $_GET['degree'] : '');
        $workType = trim(isset($_GET['work_type']) ? $_GET['work_type'] : '');
        $page = max(1, intval(isset($_GET['page']) ? $_GET['page'] : 1));
        $perPage = 16;

        $where = array("ca.status = 3", "ca.is_seeking = 1", "(u.id IS NULL OR u.user_status = 1)");
        if($keyword !== ''){
            $search = $db->escapestring($keyword);
            $where[] = "(ca.full_name LIKE '%".$search."%' OR ca.desired_position LIKE '%".$search."%' OR ca.soft_skills LIKE '%".$search."%' OR ca.phone LIKE '%".$search."%' OR ca.school_name LIKE '%".$search."%' OR ca.address_detail LIKE '%".$search."%' OR ca.career_goal LIKE '%".$search."%' OR u.user_email LIKE '%".$search."%' OR u.user_phone LIKE '%".$search."%' OR jc.job_category_name LIKE '%".$search."%')";
        }
        if($provinceId > 0){ $where[] = "ca.desired_province_id = '".$provinceId."'"; }
        if($categoryId > 0){ $where[] = "ca.major = '".$categoryId."'"; }
        if($salaryId > 0){ $where[] = "ca.desired_salary = '".$salaryId."'"; }
        if($degree !== ''){ $where[] = "ca.degree = '".$db->escapestring($degree)."'"; }
        if($workType !== ''){ $where[] = "ca.desired_work_type = '".$db->escapestring($workType)."'"; }

        $baseSql = "FROM hicrm_candidates ca
            LEFT JOIN hicrm_users u ON u.id = ca.user_id
            LEFT JOIN hicrm_job_categories jc ON jc.id = ca.major
            LEFT JOIN hicrm_provinces current_pr ON current_pr.id = ca.province_id
            LEFT JOIN hicrm_provinces desired_pr ON desired_pr.id = ca.desired_province_id
            LEFT JOIN hicrm_salary sal ON sal.id = ca.desired_salary
            WHERE ".implode(' AND ', $where);
        $db->query("SELECT COUNT(ca.id) AS total ".$baseSql);
        $totalCandidates = intval($db->fetch_object(true)->total);
        $totalPages = max(1, ceil($totalCandidates / $perPage));
        if($page > $totalPages){ $page = $totalPages; }
        $offset = ($page - 1) * $perPage;

        $db->query("SELECT ca.*, u.user_email, u.user_phone, u.user_group,
                jc.job_category_name, current_pr.province_name, desired_pr.province_name AS desired_province_name, sal.salary_name,
                COALESCE((SELECT FLOOR(SUM(DATEDIFF(COALESCE(ce.end_date, CURDATE()), ce.start_date)) / 365)
                    FROM hicrm_candidate_experiences ce WHERE ce.candidate_id = ca.id), 0) AS experience_years
            ".$baseSql."
            ORDER BY ca.updated_at DESC, ca.id DESC
            LIMIT ".$offset.",".$perPage);
        $candidates = $db->fetch_object();

        $db->query("SELECT id, province_name FROM hicrm_provinces WHERE EXISTS (SELECT 1 FROM hicrm_candidates ca WHERE ca.desired_province_id = hicrm_provinces.id AND ca.status = 3 AND ca.is_seeking = 1) ORDER BY province_name ASC");
        $candidateProvinces = $db->fetch_object();
        $db->query("SELECT id, job_category_name FROM hicrm_job_categories WHERE EXISTS (SELECT 1 FROM hicrm_candidates ca WHERE ca.major = hicrm_job_categories.id AND ca.status = 3 AND ca.is_seeking = 1) ORDER BY job_category_name ASC");
        $candidateCategories = $db->fetch_object();
        $db->query("SELECT id, salary_name FROM hicrm_salary WHERE EXISTS (SELECT 1 FROM hicrm_candidates ca WHERE ca.desired_salary = hicrm_salary.id AND ca.status = 3 AND ca.is_seeking = 1) ORDER BY id ASC");
        $candidateSalaries = $db->fetch_object();
        $db->query("SELECT DISTINCT degree FROM hicrm_candidates WHERE status = 3 AND is_seeking = 1 AND degree IS NOT NULL AND degree <> '' ORDER BY degree ASC");
        $candidateDegrees = $db->fetch_object();
        $db->query("SELECT DISTINCT desired_work_type FROM hicrm_candidates WHERE status = 3 AND is_seeking = 1 AND desired_work_type IS NOT NULL AND desired_work_type <> '' ORDER BY desired_work_type ASC");
        $candidateWorkTypes = $db->fetch_object();

        $this->view->data['candidates'] = $candidates;
        $this->view->data['candidate_filters'] = array('keyword' => $keyword, 'province_id' => $provinceId, 'job_category_id' => $categoryId, 'salary_id' => $salaryId, 'degree' => $degree, 'work_type' => $workType);
        $this->view->data['candidate_provinces'] = $candidateProvinces;
        $this->view->data['candidate_categories'] = $candidateCategories;
        $this->view->data['candidate_salaries'] = $candidateSalaries;
        $this->view->data['candidate_degrees'] = $candidateDegrees;
        $this->view->data['candidate_work_types'] = $candidateWorkTypes;
        $this->view->data['candidate_page'] = $page;
        $this->view->data['candidate_total_pages'] = $totalPages;
        $this->view->data['candidate_total'] = $totalCandidates;
        $this->view->show("quan-ly-ung-vien");
    }
    public function manage_jobs($para = array()){
        global $db;
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
        $province_id = isset($_GET['province_id']) ? intval($_GET['province_id']) : 0;
        $job_category_id = isset($_GET['job_category_id']) ? intval($_GET['job_category_id']) : 0;
        $salary_id = isset($_GET['salary_id']) ? intval($_GET['salary_id']) : 0;
        $work_type = isset($_GET['work_type']) ? trim($_GET['work_type']) : "";
        $post_type = isset($_GET['post_type']) ? trim($_GET['post_type']) : "";
        $employer_id = isset($_GET['employer_id']) ? intval($_GET['employer_id']) : 0;
        $page = 1;
        if(is_array($para) && count($para) > 0){
            foreach($para as $index => $value){
                if($value === "page" && isset($para[$index + 1]) && intval($para[$index + 1]) > 0){
                    $page = intval($para[$index + 1]);
                    break;
                }
                if(intval($value) > 0){
                    $page = intval($value);
                    break;
                }
            }
        }
        if(isset($_GET['page']) && intval($_GET['page']) > 0){
            $page = intval($_GET['page']);
        }
        $per_page = 20;

        $where = array("p.status = 'published'");
        if($keyword !== ""){
            $keyword_sql = $db->escapestring($keyword);
            $where[] = "(p.title LIKE '%".$keyword_sql."%' OR p.job_description LIKE '%".$keyword_sql."%' OR e.company_name LIKE '%".$keyword_sql."%' OR c.job_category_name LIKE '%".$keyword_sql."%')";
        }
        if($province_id > 0){
            $where[] = "p.province_id = '".$province_id."'";
        }
        if($job_category_id > 0){
            $where[] = "p.job_category_id = '".$job_category_id."'";
        }
        if($salary_id > 0){
            $where[] = "p.salary_id = '".$salary_id."'";
        }
        if($work_type !== "" && $work_type !== "all"){
            $where[] = "p.work_type = '".$db->escapestring($work_type)."'";
        }
        if($post_type === "urgent"){
            $where[] = "p.job_post_type IN ('urgent', 'hot')";
        }
        if($employer_id > 0){
            $where[] = "p.employer_id = '".$employer_id."'";
        }

        $base_sql = "FROM hicrm_job_posts p
            LEFT JOIN hicrm_employers e ON e.id = p.employer_id
            LEFT JOIN hicrm_job_categories c ON c.id = p.job_category_id
            LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
            LEFT JOIN hicrm_salary s ON s.id = p.salary_id
            WHERE ".implode(" AND ", $where);

        $db->query("SELECT COUNT(p.id) AS total ".$base_sql);
        $total_jobs = intval($db->fetch_object(true)->total);
        $total_pages = max(1, ceil($total_jobs / $per_page));
        if($page > $total_pages){ $page = $total_pages; }
        $offset = ($page - 1) * $per_page;

        $db->query("SELECT p.*, e.company_name, e.logo_url, c.job_category_name, pr.province_name, s.salary_name
            ".$base_sql."
            ORDER BY FIELD(p.job_post_type, 'hot', 'urgent', 'normal'), p.published_at DESC, p.created_at DESC, p.id DESC
            LIMIT ".$offset.",".$per_page);
        $jobs = $db->fetch_object();

        $db->query("SELECT * FROM hicrm_employers ORDER BY created_at DESC");
        $this->view->data['company'] = $db->fetch_object();
        $db->query("SELECT id, job_category_name FROM hicrm_job_categories ORDER BY job_category_name ASC");
        $this->view->data['job_categories'] = $db->fetch_object();
        $db->query("SELECT id, province_name FROM hicrm_provinces ORDER BY province_name ASC");
        $this->view->data['job_provinces'] = $db->fetch_object();
        $db->query("SELECT id, salary_name FROM hicrm_salary ORDER BY id ASC");
        $this->view->data['salaries'] = $db->fetch_object();
        $this->view->data['jobs'] = $jobs;
        $this->view->data['job_filters'] = array(
            'keyword' => $keyword,
            'province_id' => $province_id,
            'job_category_id' => $job_category_id,
            'salary_id' => $salary_id,
            'work_type' => $work_type,
            'post_type' => $post_type,
            'employer_id' => $employer_id
        );
        $this->view->data['page'] = $page;
        $this->view->data['per_page'] = $per_page;
        $this->view->data['total_jobs'] = $total_jobs;
        $this->view->data['total_pages'] = $total_pages;
        $this->view->show("quan-ly-viec-lam");
    }
    public function introduce_jobs(){
        $this->view->show("gioi-thieu-san-viec-lam");
    }
    public function introduce_process(){
        $this->view->show("quy-trinh-san-viec-lam");
    }
     public function results_jobs($para = array()){
        global $db;
        $this->ensureMarketResultTable();
        $page = 1;
        if(is_array($para) && count($para) > 0){
            foreach($para as $index => $value){
                if($value === "page" && isset($para[$index + 1]) && intval($para[$index + 1]) > 0){
                    $page = intval($para[$index + 1]);
                    break;
                }
                if(intval($value) > 0){
                    $page = intval($value);
                    break;
                }
            }
        }
        if(isset($_GET['page']) && intval($_GET['page']) > 0){
            $page = intval($_GET['page']);
        }
        $perPage = 10;

        $db->query("SELECT COUNT(id) AS total FROM hicrm_market_results WHERE result_status = 1");
        $totalResults = intval($db->fetch_object(true)->total);
        $totalPages = max(1, ceil($totalResults / $perPage));
        if($page > $totalPages){ $page = $totalPages; }
        $offset = ($page - 1) * $perPage;

        $db->query("SELECT * FROM hicrm_market_results WHERE result_status = 1 ORDER BY result_date DESC, id DESC LIMIT ".$offset.",".$perPage);
        $results = $db->fetch_object();

        $db->query("SELECT 
                COUNT(id) AS total_rounds,
                COALESCE(SUM(company_total), 0) AS total_companies,
                COALESCE(SUM(position_total), 0) AS total_positions,
                COALESCE(SUM(profile_total), 0) AS total_profiles,
                COALESCE(SUM(interview_total), 0) AS total_interviews
            FROM hicrm_market_results WHERE result_status = 1");
        $summary = $db->fetch_object(true);

        $this->view->data['market_results'] = is_array($results) ? $results : array();
        $this->view->data['market_results_page'] = $page;
        $this->view->data['market_results_per_page'] = $perPage;
        $this->view->data['market_results_total'] = $totalResults;
        $this->view->data['market_results_total_pages'] = $totalPages;
        $this->view->data['market_results_summary'] = $summary;
        $this->view->show("ket-qua-san-viec-lam");
    }
    public function results_detail($para = array()){
        global $db;
        $this->ensureMarketResultTable();
        $resultId = is_array($para) && isset($para[1]) && preg_match('/^(\d+)/', (string)$para[1], $matches) ? intval($matches[1]) : 0;
        if($resultId <= 0 && isset($_GET['id'])){ $resultId = intval($_GET['id']); }
        if($resultId <= 0){ header("Location: ".XC_URL."/ket-qua-san-viec-lam.html"); exit(); }

        $db->query("SELECT * FROM hicrm_market_results WHERE id = '".$resultId."' AND result_status = 1 LIMIT 1");
        if($db->num_row() <= 0){ header("Location: ".XC_URL."/ket-qua-san-viec-lam.html"); exit(); }
        $result = $db->fetch_object(true);

        $db->query("SELECT id, result_title, result_date, result_image FROM hicrm_market_results WHERE result_status = 1 AND id <> '".$resultId."' ORDER BY result_date DESC, id DESC LIMIT 4");
        $related = $db->fetch_object();

        $this->view->data['market_result_detail'] = $result;
        $this->view->data['market_result_related'] = is_array($related) ? $related : array();
        $this->view->show("ket-qua-san-viec-lam-detail");
    }
    public function online_jobs(){
        global $db;
        $meetings = array();

        $db->query("SHOW TABLES LIKE 'hicrm_google_meets'");
        if($db->num_row() > 0){
            $db->query("SELECT gm.*, e.company_name, p.title AS job_title
                FROM hicrm_google_meets gm
                LEFT JOIN hicrm_employers e ON gm.employer_id = e.id
                LEFT JOIN hicrm_job_posts p ON gm.job_post_id = p.id
                WHERE gm.status = 1
                ORDER BY gm.meeting_time DESC, gm.id DESC");
            $meetings = $db->fetch_object();
        }

        $this->view->data['online_meetings'] = is_array($meetings) ? $meetings : array();
        $this->view->show("san-viec-lam-online");
    }
    public function contact() {
        global $db;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = isset($_POST['fullname']) ? trim((string)$_POST['fullname']) : '';
            $phone = isset($_POST['phone']) ? trim((string)$_POST['phone']) : '';
            $email = isset($_POST['email']) ? trim((string)$_POST['email']) : '';
            $title = isset($_POST['title']) ? trim((string)$_POST['title']) : '';
            $type = in_array($_POST['type'] ?? '', ['feedback', 'complaint', 'other']) ? $_POST['type'] : 'feedback';
            $content = isset($_POST['content']) ? trim((string)$_POST['content']) : '';


            if ($fullname === '' || $phone === '' || $content === '') {
                $this->view->data['error'] = 'Vui lòng điền đầy đủ các thông tin bắt buộc (*).';
            } else {
                try {
                    $db->query("INSERT INTO `website_feedbacks` (`type`, `title`, `fb_fullname`, `fb_phone`, `fb_email`, `fb_content`, `fb_status`, `created_at`) 
                                VALUES (:type, :title, :fullname, :phone, :email, :content, 1, NOW())", [
                        'type' => $type,
                        'title' => $title,
                        'fullname' => $fullname,
                        'phone' => $phone,
                        'email' => $email === '' ? null : $email,
                        'content' => $content
                    ]);
                    $this->view->data['success'] = true;
                } catch (Exception $e) {
                    $this->view->data['error'] = 'Có lỗi xảy ra khi gửi phản ánh. Vui lòng thử lại sau.';
                }
            }
        }
        $this->showFrontend('contact', 'contact');
    }

    public function events($para = array()){
        global $db;
        $detailId = is_array($para) && isset($para[1]) ? intval($para[1]) : 0;
        if($detailId > 0){
            return $this->news_detail($para);
        }
        $keyword = trim(isset($_GET['keyword']) ? $_GET['keyword'] : '');
        $activeSection = isset($_GET['section']) ? trim($_GET['section']) : 'all';
        if(!in_array($activeSection, array('all','site','employer','seeker'), true)){ $activeSection = 'all'; }
        $sectionConfig = array(
            'site' => array('types' => array(0,3), 'page_param' => 'site_page', 'per_page' => 9),
            'employer' => array('types' => array(1), 'page_param' => 'employer_page', 'per_page' => 6),
            'seeker' => array('types' => array(2), 'page_param' => 'seeker_page', 'per_page' => 9)
        );
        $sections = array();
        $counts = array('all' => 0);
        foreach($sectionConfig as $key => $config){
            $page = max(1, intval(isset($_GET[$config['page_param']]) ? $_GET[$config['page_param']] : 1));
            $where = array("event_status = 1", "event_type IN (".implode(',', array_map('intval', $config['types'])).")");
            if($keyword !== ''){
                $kw = $db->escapestring($keyword);
                $where[] = "(event_name LIKE '%".$kw."%' OR event_description LIKE '%".$kw."%' OR event_content LIKE '%".$kw."%')";
            }
            $whereSql = implode(' AND ', $where);
            $db->query("SELECT COUNT(id) AS total FROM hicrm_events WHERE ".$whereSql);
            $total = intval($db->fetch_object(true)->total);
            $counts[$key] = $total;
            $counts['all'] += $total;
            $totalPages = max(1, ceil($total / $config['per_page']));
            if($page > $totalPages){ $page = $totalPages; }
            $offset = ($page - 1) * $config['per_page'];
            $db->query("SELECT * FROM hicrm_events WHERE ".$whereSql." ORDER BY event_hot DESC, event_created_date DESC, id DESC LIMIT ".$offset.",".$config['per_page']);
            $sections[$key] = array('items' => $db->fetch_object(), 'page' => $page, 'total_pages' => $totalPages, 'total' => $total, 'page_param' => $config['page_param']);
        }
        $db->query("SELECT * FROM hicrm_events WHERE event_status = 1 ORDER BY event_hot DESC, event_created_date DESC, id DESC LIMIT 5");
        $this->view->data['featured_news'] = $db->fetch_object();
        $this->view->data['news_sections'] = $sections;
        $this->view->data['news_counts'] = $counts;
        $this->view->data['news_keyword'] = $keyword;
        $this->view->data['news_active_section'] = $activeSection;
        $this->view->show("tin-tuc");
    }
    public function news_detail($para = array()){
        global $db;
        $db->query("CREATE TABLE IF NOT EXISTS `hicrm_event_comments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `event_id` int(11) NOT NULL,
            `parent_id` int(11) DEFAULT NULL,
            `user_id` int(11) DEFAULT NULL,
            `comment_name` varchar(255) NOT NULL,
            `comment_email` varchar(255) DEFAULT NULL,
            `comment_content` text NOT NULL,
            `admin_reply` text DEFAULT NULL,
            `reply_user_id` int(11) DEFAULT NULL,
            `status` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT NULL,
            `replied_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_event_status_created` (`event_id`,`status`,`created_at`),
            KEY `idx_status_created` (`status`,`created_at`),
            KEY `idx_user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $db->query("SHOW COLUMNS FROM `hicrm_event_comments` LIKE 'user_id'");
        if($db->num_row() <= 0){
            $db->query("ALTER TABLE `hicrm_event_comments` ADD COLUMN `user_id` int(11) DEFAULT NULL AFTER `parent_id`");
        }
        $newsId = is_array($para) && isset($para[1]) && preg_match('/^(\d+)/', (string)$para[1], $matches) ? intval($matches[1]) : 0;
        if($newsId <= 0 && isset($_GET['id'])){ $newsId = intval($_GET['id']); }
        if($newsId <= 0){ header("Location: ".XC_URL."/tin-tuc-su-kien.html"); exit(); }
        $db->query("SELECT * FROM hicrm_events WHERE id = '".$newsId."' AND event_status = 1 LIMIT 1");
        if($db->num_row() <= 0){ header("Location: ".XC_URL."/tin-tuc-su-kien.html"); exit(); }
        $news = $db->fetch_object(true);
        $db->query("SELECT * FROM hicrm_events WHERE event_status = 1 AND id <> '".$newsId."' AND event_type = '".intval($news->event_type)."' ORDER BY event_hot DESC, event_created_date DESC, id DESC LIMIT 5");
        $this->view->data['news_detail'] = $news;
        $this->view->data['related_news'] = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_events WHERE event_status = 1 AND id <> '".$newsId."' ORDER BY event_created_date DESC, id DESC LIMIT 6");
        $this->view->data['more_news'] = $db->fetch_object();
        $db->query("SELECT ec.*,
                COALESCE(NULLIF(u.full_name, ''), NULLIF(ec.comment_name, ''), 'An danh') AS commenter_name,
                COALESCE(NULLIF(ru.full_name, ''), 'Ban quan tri') AS reply_user_name
            FROM hicrm_event_comments ec
            LEFT JOIN hicrm_users u ON u.id = ec.user_id
            LEFT JOIN hicrm_users ru ON ru.id = ec.reply_user_id
            WHERE ec.event_id = '".$newsId."' AND ec.status = 1
            ORDER BY COALESCE(ec.parent_id, ec.id) DESC, ec.parent_id ASC, ec.created_at ASC, ec.id ASC");
        $this->view->data['news_comments'] = $db->fetch_object();
        $this->view->show("tintuc_detail");
    }
    public function add_news_comment() {
        global $db;
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!(isset($_SESSION['user']['id']) && intval($_SESSION['user']['id']) > 0)){
                echo json_encode(array('status' => 'error', 'message' => 'Vui lòng đăng nhập để bình luận.', 'login_required' => true));
                exit();
            }
            $eventId = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
            $parentId = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? intval($_POST['parent_id']) : 'NULL';
            $content = trim(isset($_POST['comment_content']) ? $_POST['comment_content'] : '');
            $userId = intval($_SESSION['user']['id']);

            $db->query("SELECT full_name, user_email FROM hicrm_users WHERE id = '".$userId."' LIMIT 1");
            $user = $db->num_row() > 0 ? $db->fetch_object(true) : null;
            $name = $user && trim((string)$user->full_name) !== '' ? trim((string)$user->full_name) : (strstr((string)($_SESSION['user']['email'] ?? ''), '@', true) ?: 'Tài khoản');
            $email = $user && isset($user->user_email) ? trim((string)$user->user_email) : trim((string)($_SESSION['user']['email'] ?? ''));

            if ($eventId > 0 && $content !== '') {
                $escName = $db->escapestring($name);
                $escEmail = $db->escapestring($email);
                $escContent = $db->escapestring($content);
                $parentIdVal = $parentId === 'NULL' ? 'NULL' : intval($parentId);

                $db->query("INSERT INTO `hicrm_event_comments` (`event_id`, `parent_id`, `user_id`, `comment_name`, `comment_email`, `comment_content`, `status`, `created_at`) 
                            VALUES ('".$eventId."', ".$parentIdVal.", '".$userId."', '".$escName."', '".$escEmail."', '".$escContent."', 1, NOW())");
                
                echo json_encode(array('status' => 'success', 'message' => 'Bình luận thành công!'));
                exit();
            }
        }
        echo json_encode(array('status' => 'error', 'message' => 'Dữ liệu không hợp lệ.'));
        exit();
    }

    public function posts() {


        global $db;
        try {
            $this->view->data['posts'] = $db->select("SELECT * FROM website_posts WHERE post_status = 1 ORDER BY created_at DESC");
        } catch (Exception $e) {
            $this->view->data['posts'] = [];
        }
        $this->showFrontend('posts', 'posts');
    }

    public function post_detail($args) {
        global $db;
        $argsVal = array_values($args);
        $slug = isset($argsVal[0]) ? trim((string)$argsVal[0]) : '';
        if ($slug === '') {
            header("Location: " . BASE_URL);
            exit();
        }

        try {
            $post = $db->selectOne("SELECT * FROM website_posts WHERE post_slug = :slug AND post_status = 1", ['slug' => $slug]);
            if (!$post) {
                header("Location: " . BASE_URL);
                exit();
            }
            $this->view->data['post'] = $post;
            $this->view->data['title'] = htmlspecialchars($post['post_title']) . ' — Cổng thông tin Chợ';
            
            // Get other recent posts
            $recentPosts = $db->select("SELECT * FROM website_posts WHERE post_slug != :slug AND post_status = 1 ORDER BY created_at DESC LIMIT 3", ['slug' => $slug]);
            $this->view->data['recentPosts'] = $recentPosts ? $recentPosts : [];
        } catch (Exception $e) {
            header("Location: " . BASE_URL);
            exit();
        }
        $this->showFrontend('post_detail', 'posts');
    }
    private function currentForgotPasswordUser()
    {
        global $db;
        $user = null;
        if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] !== ''){
            $db->query("SELECT id, full_name, user_email, user_phone FROM hicrm_users WHERE id = '".intval($_SESSION['user']['id'])."' LIMIT 1");
            if($db->num_row() > 0){
                $user = $db->fetch_object(true);
            }
        }
        return $user;
    }
    private function findUserForPasswordReset($email, $phone = '')
    {
        global $db;
        $email = trim((string)$email);
        $phone = trim((string)$phone);
        if($email === '' && $phone === ''){
            return null;
        }

        $conditions = array();
        if($email !== ''){
            $conditions[] = "user_email = '".$db->escapestring($email)."'";
        }
        if($phone !== ''){
            $conditions[] = "user_phone = '".$db->escapestring($phone)."'";
        }
        if(empty($conditions)){
            return null;
        }

        $db->query("SELECT id, full_name, user_email, user_phone, user_status
            FROM hicrm_users
            WHERE (".implode(' OR ', $conditions).")
            ORDER BY id DESC
            LIMIT 5");
        $users = $db->fetch_object();
        if(empty($users)){
            return null;
        }

        foreach((array)$users as $user){
            $emailMatched = $email === '' || strcasecmp(trim((string)$user->user_email), $email) === 0;
            $phoneMatched = $phone === '' || trim((string)$user->user_phone) === $phone;
            if($emailMatched && $phoneMatched && (int)$user->user_status === 1){
                return $user;
            }
        }

        foreach((array)$users as $user){
            if($email !== '' && strcasecmp(trim((string)$user->user_email), $email) === 0 && (int)$user->user_status === 1){
                return $user;
            }
        }

        return null;
    }
    private function buildResetToken()
    {
        try {
            return bin2hex(random_bytes(32));
        } catch (Exception $e) {
            return sha1(uniqid((string)mt_rand(), true).microtime(true));
        }
    }
    private function forgotPasswordDisplayName($user, $fallbackEmail = '')
    {
        $name = $user && isset($user->full_name) ? trim((string)$user->full_name) : '';
        if($name !== ''){ return $name; }
        $fallbackEmail = trim((string)$fallbackEmail);
        if($fallbackEmail !== '' && strpos($fallbackEmail, '@') !== false){
            return strstr($fallbackEmail, '@', true);
        }
        return 'Người dùng';
    }
    public function forgot_password($para = array())
    {
        global $db;
        $prefillUser = $this->currentForgotPasswordUser();
        $form = array(
            'full_name' => $prefillUser ? trim((string)$prefillUser->full_name) : '',
            'email' => $prefillUser ? trim((string)$prefillUser->user_email) : '',
            'phone' => $prefillUser ? trim((string)$prefillUser->user_phone) : ''
        );
        $message = '';
        $messageType = '';

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $form['email'] = trim(isset($_POST['email']) ? $_POST['email'] : '');
            $form['phone'] = trim(isset($_POST['phone']) ? $_POST['phone'] : '');

            if($form['email'] === '' || !filter_var($form['email'], FILTER_VALIDATE_EMAIL)){
                $message = 'Vui lòng nhập đúng địa chỉ email để nhận liên kết đổi mật khẩu.';
                $messageType = 'error';
            } else {
                $matchedUser = $this->findUserForPasswordReset($form['email'], $form['phone']);
                if($matchedUser){
                    $form['full_name'] = trim((string)$matchedUser->full_name);
                    $token = $this->buildResetToken();
                    $expiresAt = date('Y-m-d H:i:s', time() + 300);
                    $db->query("UPDATE hicrm_users SET
                        user_reset_token = '".$db->escapestring($token)."',
                        user_reset_token_expires = '".$db->escapestring($expiresAt)."',
                        user_updated_at = NOW()
                        WHERE id = '".intval($matchedUser->id)."'
                        LIMIT 1");

                    $resetLink = XC_URL.'/doi-mat-khau.php?token='.rawurlencode($token);
                    $emailSent = baseMailler::getInstance()->sendPasswordResetEmail(
                        $this->forgotPasswordDisplayName($matchedUser, $form['email']),
                        $form['email'],
                        $resetLink,
                        'Yêu cầu đổi mật khẩu hệ thống Cổng thông tin việc làm'
                    );

                    if($emailSent){
                        $message = 'Hệ thống đã gửi đường link đổi mật khẩu về email của bạn. Vui lòng kiểm tra email, liên kết chỉ có hiệu lực trong vòng 5 phút.';
                        $messageType = 'success';
                    } else {
                        $message = 'Không thể gửi email lúc này. Vui lòng kiểm tra cấu hình SMTP và thử lại.';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'Nếu thông tin bạn nhập khớp với tài khoản trong hệ thống, đường link đổi mật khẩu sẽ được gửi về email trong vòng ít phút.';
                    $messageType = 'success';
                }
            }
        }

        $this->view->data['forgot_password_form'] = $form;
        $this->view->data['forgot_password_message'] = $message;
        $this->view->data['forgot_password_message_type'] = $messageType;
        $this->view->show("quen-mat-khau");
    }
    public function reset_password($para = array())
    {
        global $db;
        $token = trim(isset($_GET['token']) ? $_GET['token'] : '');
        if($token === '' && is_array($para) && isset($para[1])){
            $token = trim((string)$para[1]);
        }

        $state = array(
            'token' => $token,
            'full_name' => '',
            'email' => '',
            'is_valid' => false,
            'is_expired' => false,
            'message' => '',
            'message_type' => '',
        );

        $user = null;
        if($token !== ''){
            $db->query("SELECT id, full_name, user_email, user_reset_token, user_reset_token_expires
                FROM hicrm_users
                WHERE user_reset_token = '".$db->escapestring($token)."'
                LIMIT 1");
            if($db->num_row() > 0){
                $user = $db->fetch_object(true);
                $state['full_name'] = trim((string)$user->full_name);
                $state['email'] = trim((string)$user->user_email);
                $expiresTime = strtotime((string)$user->user_reset_token_expires);
                if($expiresTime !== false && $expiresTime >= time()){
                    $state['is_valid'] = true;
                } else {
                    $state['is_expired'] = true;
                    $state['message'] = 'Liên kết đổi mật khẩu đã hết hạn. Vui lòng thực hiện quên mật khẩu lại để nhận đường link mới.';
                    $state['message_type'] = 'error';
                }
            } else {
                $state['message'] = 'Liên kết đổi mật khẩu không hợp lệ hoặc đã được sử dụng.';
                $state['message_type'] = 'error';
            }
        } else {
            $state['message'] = 'Thiếu mã xác thực để đổi mật khẩu.';
            $state['message_type'] = 'error';
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $token = trim(isset($_POST['token']) ? $_POST['token'] : $token);
            $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
            $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

            if($token !== '' && !$user){
                $db->query("SELECT id, full_name, user_email, user_reset_token, user_reset_token_expires
                    FROM hicrm_users
                    WHERE user_reset_token = '".$db->escapestring($token)."'
                    LIMIT 1");
                if($db->num_row() > 0){
                    $user = $db->fetch_object(true);
                    $state['full_name'] = trim((string)$user->full_name);
                    $state['email'] = trim((string)$user->user_email);
                }
            }

            $expiresTime = ($user && isset($user->user_reset_token_expires)) ? strtotime((string)$user->user_reset_token_expires) : false;
            if(!$user || $expiresTime === false || $expiresTime < time()){
                $state['is_valid'] = false;
                $state['is_expired'] = true;
                $state['message'] = 'Liên kết đổi mật khẩu đã hết hạn hoặc không hợp lệ.';
                $state['message_type'] = 'error';
            } elseif($newPassword === '' || strlen($newPassword) < 6){
                $state['message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
                $state['message_type'] = 'error';
                $state['is_valid'] = true;
            } elseif($newPassword !== $confirmPassword){
                $state['message'] = 'Xác nhận mật khẩu mới chưa khớp.';
                $state['message_type'] = 'error';
                $state['is_valid'] = true;
            } else {
                $db->query("UPDATE hicrm_users SET
                    user_password = '".md5($db->escapestring($newPassword))."',
                    user_reset_token = NULL,
                    user_reset_token_expires = NULL,
                    user_updated_at = NOW()
                    WHERE id = '".intval($user->id)."'
                    LIMIT 1");
                $state['is_valid'] = false;
                $state['message'] = 'Đổi mật khẩu thành công. Bạn có thể đăng nhập lại bằng mật khẩu mới.';
                $state['message_type'] = 'success';
            }
        }

        $this->view->data['reset_password_state'] = $state;
        $this->view->show("doi-mat-khau");
    }
    public function logout(){
		session_unset();
		header('Location:' .XC_URL);
	}
    private function employerDashboardContext(){
        global $db;
        $user = null;
        $employer = null;

        if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != ""){
            $uid = $db->escapestring($_SESSION['user']['id']);
            $db->query("SELECT * FROM hicrm_users WHERE id = '".$uid."' AND user_group = '2' LIMIT 1");
            if($db->num_row() > 0){
                $user = $db->fetch_object(true);
            }
        }

        if(!$user){
            $db->query("SELECT * FROM hicrm_users WHERE user_group = '2' ORDER BY employee_id DESC, id ASC LIMIT 1");
            if($db->num_row() > 0){
                $user = $db->fetch_object(true);
            }
        }

        if($user && intval($user->employee_id) > 0){
            $db->query("SELECT e.*, c.job_category_name FROM hicrm_employers e LEFT JOIN hicrm_job_categories c ON e.job_category_id = c.id WHERE e.id = '".intval($user->employee_id)."' LIMIT 1");
            if($db->num_row() > 0){
                $employer = $db->fetch_object(true);
            }
        }

        if(!$employer){
            $db->query("SELECT e.*, c.job_category_name FROM hicrm_employers e LEFT JOIN hicrm_job_categories c ON e.job_category_id = c.id ORDER BY e.id ASC LIMIT 1");
            if($db->num_row() > 0){
                $employer = $db->fetch_object(true);
            }
        }

        return array('user' => $user, 'employer' => $employer);
    }

    private function employerDashboardStats($employer_id){
        global $db;
        $stats = array('total' => 0, 'published' => 0, 'pending' => 0, 'closed' => 0);
        if(!$employer_id){
            return $stats;
        }

        $db->query("SELECT status, COUNT(*) AS total FROM hicrm_job_posts WHERE employer_id = '".intval($employer_id)."' GROUP BY status");
        $rows = $db->fetch_object();
        foreach($rows as $row){
            $stats['total'] += intval($row->total);
            if(isset($stats[$row->status])){
                $stats[$row->status] = intval($row->total);
            }
        }
        return $stats;
    }

    private function employerDashboardTableExists($table_name){
        global $db;
        $table_name = $db->escapestring($table_name);
        $db->query("SHOW TABLES LIKE '".$table_name."'");
        return $db->num_row() > 0;
    }
    private function candidateUserContext(){
        global $db;
        if(!(isset($_SESSION['user']['id']) && intval($_SESSION['user']['id']) > 0)){
            return array('user' => null, 'candidate' => null);
        }
        $userId = intval($_SESSION['user']['id']);
        $db->query("SELECT * FROM hicrm_users WHERE id = '".$userId."' AND user_status = 1 LIMIT 1");
        $user = $db->num_row() > 0 ? $db->fetch_object(true) : null;
        $candidate = null;
        if($user){
            $db->query("SELECT * FROM hicrm_candidates WHERE user_id = '".$userId."' LIMIT 1");
            if($db->num_row() > 0){
                $candidate = $db->fetch_object(true);
            }
        }
        return array('user' => $user, 'candidate' => $candidate);
    }
    private function candidateProfileCompleteness($candidate){
        if(!$candidate){ return 0; }
        $fields = array(
            'full_name', 'date_of_birth', 'gender', 'phone', 'user_email', 'avatar_url', 'province_id', 'address_detail',
            'degree', 'major', 'graduation_year', 'school_name', 'soft_skills', 'career_goal_short', 'career_goal_long',
            'desired_position', 'desired_salary', 'desired_province_id', 'desired_work_type', 'cv_url'
        );
        $completed = 0;
        foreach($fields as $field){
            $value = isset($candidate->$field) ? $candidate->$field : null;
            if($value !== null && trim((string)$value) !== '' && $value !== '0'){
                $completed++;
            }
        }
        return (int)round(($completed / count($fields)) * 100);
    }

    public function candidateDashboard($para = array()){
        global $db;
        // if(!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] === '' || (string)($_SESSION['user']['group'] ?? '') !== '4'){
        //     header("Location: ".XC_URL);
        //     exit();
        // }

        $sessionUserId = (int)$_SESSION['user']['id'];
        $uid = $db->escapestring($sessionUserId);
        $db->query("SELECT * FROM hicrm_users WHERE id = '".$uid."' LIMIT 1");
        if($db->num_row() <= 0){
            header("Location: ".XC_URL);
            exit();
        }
        $user = $db->fetch_object(true);

        $requestedCandidateId = 0;
        if(is_array($para) && isset($para[1])){ $requestedCandidateId = (int)$para[1]; }
        if($requestedCandidateId <= 0 && isset($_GET['id'])){ $requestedCandidateId = (int)$_GET['id']; }

        $candidate = false;
        if($requestedCandidateId > 0){
            $db->query("SELECT * FROM hicrm_candidates WHERE id = '".intval($requestedCandidateId)."' AND user_id = '".$uid."' LIMIT 1");
            if($db->num_row() > 0){
                $candidate = $db->fetch_object(true);
            }else{
                $db->query("SELECT id FROM hicrm_candidates WHERE user_id = '".$uid."' LIMIT 1");
                if($db->num_row() > 0){
                    $ownCandidate = $db->fetch_object(true);
                    header("Location: ".XC_URL."/quan-ly-ho-so-ung-vien.html/".intval($ownCandidate->id));
                }else{
                    header("Location: ".XC_URL."/quan-ly-ho-so-ung-vien.html");
                }
                exit();
            }
        }else{
            $db->query("SELECT * FROM hicrm_candidates WHERE user_id = '".$uid."' LIMIT 1");
            $candidate = $db->fetch_object(true);
        }
        if(!$candidate){
            $fullName = trim((string)$user->full_name);
            if($fullName === ''){ $fullName = strstr((string)$user->user_email, '@', true) ?: 'Ứng viên'; }
            $phone = isset($user->user_phone) ? $user->user_phone : '';
            $db->query("INSERT INTO hicrm_candidates (user_id, full_name, phone, status, profile_completeness, created_at, updated_at)
                VALUES ('".$uid."', '".$db->escapestring($fullName)."', '".$db->escapestring($phone)."', 1, 0, NOW(), NOW())");
            $db->query("SELECT * FROM hicrm_candidates WHERE user_id = '".$uid."' LIMIT 1");
            $candidate = $db->fetch_object(true);
        }
        $candidate->user_email = isset($user->user_email) ? $user->user_email : '';
        $completeness = $this->candidateProfileCompleteness($candidate);
        if((int)$candidate->profile_completeness !== $completeness){
            $db->query("UPDATE hicrm_candidates SET profile_completeness = '".$completeness."' WHERE id = '".intval($candidate->id)."' LIMIT 1");
            $candidate->profile_completeness = $completeness;
        }

        $db->query("SELECT id, province_name FROM hicrm_provinces ORDER BY province_name ASC");
        $provinces = $db->fetch_object();
        $db->query("SELECT id, job_category_name FROM hicrm_job_categories ORDER BY job_category_name ASC");
        $categories = $db->fetch_object();
        $db->query("SELECT id, salary_name FROM hicrm_salary ORDER BY id ASC");
        $salaries = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_candidate_experiences WHERE candidate_id = '".intval($candidate->id)."' ORDER BY start_date DESC, id DESC");
        $experiences = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_candidate_certificates WHERE candidate_id = '".intval($candidate->id)."' ORDER BY issued_date DESC, id DESC");
        $certificates = $db->fetch_object();

        $applications = array();
        if($this->employerDashboardTableExists('hicrm_job_applications')){
            $db->query("SELECT a.*, p.title, p.work_type, p.deadline, e.company_name, e.logo_url, pr.province_name, s.salary_name
                FROM hicrm_job_applications a
                LEFT JOIN hicrm_job_posts p ON p.id = a.job_post_id
                LEFT JOIN hicrm_employers e ON e.id = p.employer_id
                LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
                LEFT JOIN hicrm_salary s ON s.id = p.salary_id
                WHERE a.candidate_id = '".intval($candidate->id)."'
                ORDER BY a.applied_at DESC, a.id DESC");
            $applications = $db->fetch_object();
        }

        $this->view->data['candidate_user'] = $user;
        $this->view->data['candidate'] = $candidate;
        $this->view->data['candidate_completeness'] = $completeness;
        $this->view->data['candidate_provinces'] = $provinces;
        $this->view->data['candidate_categories'] = $categories;
        $this->view->data['candidate_salaries'] = $salaries;
        $this->view->data['candidate_experiences'] = $experiences;
        $this->view->data['candidate_certificates'] = $certificates;
        $this->view->data['candidate_applications'] = $applications;
        $this->view->show("quan-ly-ho-so-ung-vien");
    }

    public function job_detail($para = array()){
        global $db;
        $jobId = 0;
        if(is_array($para) && isset($para[1])){ $jobId = intval($para[1]); }
        if($jobId <= 0 && isset($_GET['job_id'])){ $jobId = intval($_GET['job_id']); }
        if($jobId <= 0){
            header("Location: ".XC_URL."/quan-ly-viec-lam.html");
            exit();
        }
        $db->query("SELECT p.*, e.company_name, e.logo_url, e.address_detail AS company_address,
                e.company_size, e.description AS company_description, e.website_url, e.verified_status,
                c.job_category_name, pr.province_name, s.salary_name
            FROM hicrm_job_posts p
            LEFT JOIN hicrm_employers e ON e.id = p.employer_id
            LEFT JOIN hicrm_job_categories c ON c.id = p.job_category_id
            LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
            LEFT JOIN hicrm_salary s ON s.id = p.salary_id
            WHERE p.id = '".$jobId."' AND p.status = 'published' LIMIT 1");
        if($db->num_row() <= 0){
            header("Location: ".XC_URL."/quan-ly-viec-lam.html");
            exit();
        }
        $jobDetail = $db->fetch_object(true);
        $this->view->data['job_detail'] = $jobDetail;

        $candidateContext = $this->candidateUserContext();
        $candidateUser = $candidateContext['user'];
        $candidateProfile = $candidateContext['candidate'];
        $canApply = false;
        $isApplied = false;
        $applyMessage = '';
        $applyMessageType = '';
        $deadlineExpired = false;
        if(!empty($jobDetail->deadline)){
            $deadlineExpired = strtotime((string)$jobDetail->deadline.' 23:59:59') < time();
        }
        if(!(isset($_SESSION['user']['id']) && intval($_SESSION['user']['id']) > 0)){
            $applyMessage = 'Vui lòng đăng nhập tài khoản để ứng tuyển.';
            $applyMessageType = 'error';
        }elseif(!$candidateUser || !in_array(intval($candidateUser->user_group ?? 0), array(3, 4), true)){
            $applyMessage = 'Chỉ tài khoản ứng viên mới có thể ứng tuyển việc làm.';
            $applyMessageType = 'error';
        }elseif(isset($candidateUser->user_is_verified) && intval($candidateUser->user_is_verified) !== 1){
            $applyMessage = 'Tài khoản của bạn chưa được xác thực. Vui lòng xác thực tài khoản trước khi ứng tuyển.';
            $applyMessageType = 'error';
        }elseif(!$candidateProfile || intval($candidateProfile->status ?? 0) !== 3){
            $applyMessage = 'Nếu bạn muốn ứng tuyển, Vui lòng cập nhật hồ sơ đầy đủ để được phê duyệt.';
            $applyMessageType = 'error';
        }elseif($deadlineExpired){
            $applyMessage = 'Bài đăng đã hết hạn nộp hồ sơ.';
            $applyMessageType = 'error';
        }else{
            $canApply = true;
        }

        $relatedJobs = array();
        if((int)$jobDetail->job_category_id > 0){
            $db->query("SELECT p.id, p.title, p.deadline, p.work_type, p.job_post_type,
                    e.company_name, e.logo_url, pr.province_name, s.salary_name
                FROM hicrm_job_posts p
                LEFT JOIN hicrm_employers e ON e.id = p.employer_id
                LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
                LEFT JOIN hicrm_salary s ON s.id = p.salary_id
                WHERE p.status = 'published'
                    AND p.job_category_id = '".intval($jobDetail->job_category_id)."'
                    AND p.id <> '".$jobId."'
                ORDER BY FIELD(p.job_post_type, 'hot', 'urgent', 'normal'), p.published_at DESC, p.id DESC
                LIMIT 4");
            $relatedJobs = $db->fetch_object();
        }
        if($candidateProfile && $this->employerDashboardTableExists('hicrm_job_applications')){
            $db->query("SELECT id, status, applied_at FROM hicrm_job_applications WHERE candidate_id = '".intval($candidateProfile->id)."' AND job_post_id = '".$jobId."' LIMIT 1");
            if($db->num_row() > 0){
                $applicationRow = $db->fetch_object(true);
                $isApplied = true;
                $canApply = false;
                $applyMessage = 'Bạn đã ứng tuyển công việc này vào ngày '.(!empty($applicationRow->applied_at) ? date('d/m/Y', strtotime($applicationRow->applied_at)) : date('d/m/Y')).'.';
                $applyMessageType = 'success';
            }
        }

        $db->query("SELECT p.id, p.title, p.deadline, p.work_type, p.job_post_type,
                e.company_name, e.logo_url, pr.province_name, s.salary_name
            FROM hicrm_job_posts p
            LEFT JOIN hicrm_employers e ON e.id = p.employer_id
            LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
            LEFT JOIN hicrm_salary s ON s.id = p.salary_id
            WHERE p.status = 'published' AND p.id <> '".$jobId."'
            ORDER BY COALESCE(p.published_at, p.created_at) DESC, p.id DESC
            LIMIT 8");
        $featuredJobs = $db->fetch_object();

        $this->view->data['related_jobs'] = is_array($relatedJobs) ? $relatedJobs : array();
        $this->view->data['featured_jobs'] = is_array($featuredJobs) ? $featuredJobs : array();
        $this->view->data['job_can_apply'] = $canApply;
        $this->view->data['job_is_applied'] = $isApplied;
        $this->view->data['job_apply_message'] = $applyMessage;
        $this->view->data['job_apply_message_type'] = $applyMessageType;
        $this->view->data['job_deadline_expired'] = $deadlineExpired;
        $this->view->data['job_candidate_profile'] = $candidateProfile;

        if(empty($_SESSION['job_support_csrf_token'])){
            try {
                $_SESSION['job_support_csrf_token'] = bin2hex(random_bytes(32));
            } catch (Exception $e) {
                $_SESSION['job_support_csrf_token'] = md5(uniqid((string)mt_rand(), true));
            }
        }
        $showJobSupportModal = empty($_SESSION['job_support_modal_shown']);
        if($showJobSupportModal){
            $_SESSION['job_support_modal_shown'] = time();
        }
        $this->view->data['show_job_support_modal'] = $showJobSupportModal;
        $this->view->data['job_support_csrf_token'] = $_SESSION['job_support_csrf_token'];

        $db->query("UPDATE hicrm_job_posts SET views_count = COALESCE(views_count, 0) + 1 WHERE id = '".$jobId."' LIMIT 1");
        $this->view->show("chi-tiet-viec-lam");
    }
    public function candidate_detail($para = array()){
        global $db;
        $candidateId = is_array($para) && isset($para[1]) ? intval($para[1]) : 0;
        if($candidateId <= 0 && isset($_GET['candidate_id'])){ $candidateId = intval($_GET['candidate_id']); }
        if($candidateId <= 0){ header("Location: ".XC_URL."/quan-ly-ung-vien.html"); exit(); }

        $db->query("SELECT ca.*, u.user_email, u.user_phone, u.user_group,
                jc.job_category_name, current_pr.province_name, desired_pr.province_name AS desired_province_name, sal.salary_name
            FROM hicrm_candidates ca
            LEFT JOIN hicrm_users u ON u.id = ca.user_id
            LEFT JOIN hicrm_job_categories jc ON jc.id = ca.major
            LEFT JOIN hicrm_provinces current_pr ON current_pr.id = ca.province_id
            LEFT JOIN hicrm_provinces desired_pr ON desired_pr.id = ca.desired_province_id
            LEFT JOIN hicrm_salary sal ON sal.id = ca.desired_salary
            WHERE ca.id = '".$candidateId."' AND ca.status = 3 AND ca.is_seeking = 1
                AND (u.id IS NULL OR u.user_status = 1) LIMIT 1");
        if($db->num_row() <= 0){ header("Location: ".XC_URL."/quan-ly-ung-vien.html"); exit(); }
        $candidate = $db->fetch_object(true);
        $db->query("SELECT * FROM hicrm_candidate_experiences WHERE candidate_id = '".$candidateId."' ORDER BY start_date DESC, id DESC");
        $experiences = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_candidate_certificates WHERE candidate_id = '".$candidateId."' ORDER BY issued_date DESC, id DESC");
        $certificates = $db->fetch_object();
        $db->query("SELECT ca.id, ca.full_name, ca.avatar_url, ca.desired_position, ca.desired_work_type, pr.province_name, jc.job_category_name
            FROM hicrm_candidates ca
            LEFT JOIN hicrm_provinces pr ON pr.id = ca.desired_province_id
            LEFT JOIN hicrm_job_categories jc ON jc.id = ca.major
            LEFT JOIN hicrm_users u ON u.id = ca.user_id
            WHERE ca.status = 3 AND ca.is_seeking = 1 AND ca.id <> '".$candidateId."'
                AND ca.major = '".intval($candidate->major)."' AND (u.id IS NULL OR u.user_status = 1)
            ORDER BY ca.updated_at DESC, ca.id DESC LIMIT 8");
        $this->view->data['candidate_detail'] = $candidate;
        $this->view->data['candidate_detail_experiences'] = $experiences;
        $this->view->data['candidate_detail_certificates'] = $certificates;
        $this->view->data['related_candidates'] = $db->fetch_object();
        $this->view->show("chi-tiet-ung-vien");
    }
    public function employers(){
        global $db;
        
        $uid = $db->escapestring($_SESSION['user']['id']);
        // var_dump($_SESSION['user']);
        if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "" && $_SESSION['user']['group'] == '2'){
           $context = $this->employerDashboardContext();
            $employer = $context['employer'];
            $employer_id = $employer ? intval($employer->id) : 0;

            $db->query("SELECT * FROM hicrm_job_categories ORDER BY job_category_name ASC");
            $job_categories = $db->fetch_object();

            $db->query("SELECT id, province_code, province_name, province_keyword, created_at FROM hicrm_provinces ORDER BY province_name ASC");
            $job_provinces = $db->fetch_object();

            $job_posts = array();
            $job_application_counts = array();
            $job_applicants_map = array();
            if($employer_id > 0){
                $db->query("SELECT p.*, c.job_category_name FROM hicrm_job_posts p LEFT JOIN hicrm_job_categories c ON p.job_category_id = c.id WHERE p.employer_id = '".$employer_id."' ORDER BY p.created_at DESC, p.id DESC");
                $job_posts = $db->fetch_object();
            }

            $db->query("SELECT s.*, c.job_category_name FROM hicrm_student_profile s LEFT JOIN hicrm_job_categories c ON s.student_major_id = c.id ORDER BY s.student_gpa DESC, s.id DESC LIMIT 60");
            $students = $db->fetch_object();

            if($this->employerDashboardTableExists('hicrm_job_applications')){
                $db->query("SELECT a.id AS application_id, a.status AS application_status, a.applied_at,
                        p.id AS applied_job_post_id, p.title AS applied_job_title,
                        ca.*, u.user_email, u.user_phone, jc.job_category_name
                    FROM hicrm_job_applications a
                    INNER JOIN hicrm_job_posts p ON p.id = a.job_post_id
                    INNER JOIN hicrm_candidates ca ON ca.id = a.candidate_id
                    LEFT JOIN hicrm_users u ON ca.user_id = u.id
                    LEFT JOIN hicrm_job_categories jc ON ca.major = jc.id
                    WHERE p.employer_id = '".$employer_id."'
                    ORDER BY a.applied_at DESC, a.id DESC
                    ");
                $candidates = $db->fetch_object();
                if(is_array($candidates)){
                    foreach($candidates as $candidate){
                        $job_post_id = isset($candidate->applied_job_post_id) ? intval($candidate->applied_job_post_id) : 0;
                        if($job_post_id <= 0){
                            continue;
                        }
                        if(!isset($job_application_counts[$job_post_id])){
                            $job_application_counts[$job_post_id] = 0;
                        }
                        $job_application_counts[$job_post_id]++;
                        if(!isset($job_applicants_map[$job_post_id])){
                            $job_applicants_map[$job_post_id] = array();
                        }
                        $candidate_id = isset($candidate->id) ? intval($candidate->id) : 0;
                        $job_applicants_map[$job_post_id][] = array(
                            'candidate_id' => $candidate_id,
                            'candidate_name' => isset($candidate->full_name) ? (string)$candidate->full_name : '',
                            'candidate_email' => isset($candidate->user_email) ? (string)$candidate->user_email : '',
                            'candidate_phone' => isset($candidate->user_phone) ? (string)$candidate->user_phone : '',
                            'candidate_position' => isset($candidate->desired_position) ? (string)$candidate->desired_position : '',
                            'candidate_degree' => isset($candidate->degree) ? (string)$candidate->degree : '',
                            'applied_at' => isset($candidate->applied_at) ? (string)$candidate->applied_at : '',
                            'application_status' => isset($candidate->application_status) ? (string)$candidate->application_status : 'submitted',
                            'candidate_url' => $candidate_id > 0 ? general::getInstance()->permalink($candidate_id, 'candidate_profile') : '#'
                        );
                    }
                }
            }elseif($this->employerDashboardTableExists('hicrm_candidates')){
                $db->query("SELECT ca.*, u.user_email, u.user_phone, jc.job_category_name FROM hicrm_candidates ca LEFT JOIN hicrm_users u ON ca.user_id = u.id LEFT JOIN hicrm_job_categories jc ON ca.major = jc.id ORDER BY ca.updated_at DESC, ca.id DESC LIMIT 60");
                $candidates = $db->fetch_object();
            }else{
                $db->query("SELECT id, full_name, user_email, user_phone, user_created_at AS updated_at FROM hicrm_users WHERE user_group = '4' ORDER BY id DESC LIMIT 60");
                $candidates = $db->fetch_object();
            }
            $db->query("SELECT * FROM hicrm_salary ORDER BY id ASC");
            $salary = $db->fetch_object();

            $this->view->data['employer_user'] = $context['user'];
            $this->view->data['employer'] = $employer;
            $this->view->data['job_categories'] = $job_categories;
            $this->view->data['job_provinces'] = $job_provinces;
            $this->view->data['job_posts'] = $job_posts;
            $this->view->data['job_application_counts'] = $job_application_counts;
            $this->view->data['job_applicants_map'] = $job_applicants_map;
            $this->view->data['job_stats'] = $this->employerDashboardStats($employer_id);
            $this->view->data['students'] = $students;
            $this->view->data['candidates'] = $candidates;
            $this->view->data['salary'] = $salary;
            $this->view->show("employer-dashboard");
        }else{
            header("Location: ".XC_URL);
            exit();
        }
        
        
       
    }
    public function verify_email($para){
            global $db;
            if(isset($para[1]) && $para[1] != "")
            {
                $token = $para[1];
                $db->query("SELECT * FROM hicrm_users WHERE user_email_verify_token='$token' AND user_is_verified=0");
                $user_email_verified_at = $db->fetch_object(true)->user_email_verified_at;
                if($db->num_row() > 0)
                {
                    $db->query("UPDATE hicrm_users SET user_is_verified=1, user_email_verify_token='' WHERE user_email_verify_token='$token'");
                   $page_title = "Chúc mừng! Xác thực email thành công";
                   $page_description = "Cảm ơn bạn đã xác thực email. Bạn có thể đăng nhập vào hệ thống ngay bây giờ.";
                    $this->view->data['page_description'] = $page_description;
                    $this->view->data['page_title'] = $page_title;
                    $this->view->data['verify_email'] = 1;
                    $this->view->show("404");
                }elseif (strtotime($user_email_verified_at) < time()) {
                        $this->view->data['page_title'] = "⏰ Link đã hết hạn!";
                        $this->view->data['page_description'] = "Liên kết xác thực đã hết hạn. Vui lòng đăng ký lại để nhận liên kết mới.";
                        $this->view->data['verify_email'] = 0; 
                        $this->view->show("404"); 
                } else
                {
                    $page_title = "Xác thực email thất bại";
                    $page_description = "Liên kết xác thực đã được sử dụng. Vui lòng kiểm tra lại hoặc liên hệ với bộ phận hỗ trợ.";
                    $this->view->data['page_description'] = $page_description;
                    $this->view->data['page_title'] = $page_title;
                    $this->view->show("404");
                }
            }
    }
}
