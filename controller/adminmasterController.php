<?php
/**
 * Controller quản lý vai trò và các cài đặt hệ thống cấp cao (chỉ dành cho super_market)
 */
class adminmasterController extends baseController {

    public function __construct($registry) {
        parent::__construct($registry);

        // Bảo vệ toàn bộ các action trong adminmasterController
        // Chỉ cho phép super_market (Super Admin) truy cập
        if (!$this->helper->isLoggedIn() || !$this->helper->isSuperAdmin()) {
            header('Location: ' . BASE_URL . 'login');
            exit();
        }
    }

    public function index() {
        $this->roles();
    }

    /**
     * Danh sách vai trò chợ của hệ thống
     */
    public function roles() {
        $db = database::getInstance();
        $roles = $db->select("SELECT * FROM market_roles ORDER BY role_id ASC");

        $this->view->app("user/roles", [
            'title' => 'Quản Lý Vai Trò',
            'roles' => $roles
        ]);
    }

    /**
     * API thêm vai trò mới (POST)
     */
    public function role_save() {
        $db = database::getInstance();
        $name = trim($_POST['role_name'] ?? '');
        $description = trim($_POST['role_description'] ?? '');
        $modules = $_POST['role_modules'] ?? [];

        if (empty($name)) {
            $this->roles_with_error("Tên vai trò không được để trống.");
            return;
        }

        // Kiểm tra trùng tên vai trò
        $exists = $db->selectOne("SELECT 1 FROM market_roles WHERE role_name = :name", ['name' => $name]);
        if ($exists) {
            $this->roles_with_error("Tên vai trò '{$name}' đã tồn tại.");
            return;
        }

        // Ghép mảng các module được chọn thành chuỗi phân cách bằng dấu phẩy
        $permissions = implode(',', array_filter($modules));

        try {
            $db->query("
                INSERT INTO market_roles (role_name, role_description, role_permissions)
                VALUES (:name, :description, :permissions)
            ", [
                'name' => $name,
                'description' => $description,
                'permissions' => $permissions
            ]);

            general::log('create_market_role', "Thêm vai trò chợ mới: {$name} (Quyền: {$permissions})");
            header('Location: ' . BASE_URL . 'adminmaster/roles');
            exit();
        } catch (Exception $e) {
            $this->roles_with_error("Lỗi khi thêm vai trò vào cơ sở dữ liệu: " . $e->getMessage());
        }
    }

    /**
     * Xóa vai trò chợ (GET/POST)
     */
    public function role_delete($para) {
        $id = is_array($para) ? reset($para) : $para;
        if (!$id) {
            header('Location: ' . BASE_URL . 'adminmaster/roles');
            exit();
        }

        // Không cho phép xóa các vai trò mặc định (2, 5, 6, 7)
        if (in_array((int)$id, [2, 5, 6, 7])) {
            $this->roles_with_error("Không thể xóa vai trò mặc định của hệ thống.");
            return;
        }

        $db = database::getInstance();
        try {
            $db->query("DELETE FROM market_roles WHERE role_id = :id", ['id' => $id]);
            general::log('delete_market_role', "Xóa vai trò chợ ID: {$id}");
            header('Location: ' . BASE_URL . 'adminmaster/roles');
            exit();
        } catch (Exception $e) {
            $this->roles_with_error("Lỗi khi xóa vai trò: " . $e->getMessage());
        }
    }

    private function roles_with_error($errorMsg) {
        $db = database::getInstance();
        $roles = $db->select("SELECT * FROM market_roles ORDER BY role_id ASC");

        $this->view->app("user/roles", [
            'title' => 'Quản Lý Vai Trò',
            'roles' => $roles,
            'error' => $errorMsg,
            'post_data' => $_POST
        ]);
    }

    /**
     * API thêm chợ mới (AJAX POST)
     */
    public function addMarket() {
        $this->render->abort405('POST', 'create', 'market');
        $this->render->abort403(marketService::isSuperAdmin(), 'create', 'market');

        $data = [
            'market_code'  => trim($_POST['market_code'] ?? ''),
            'market_name'         => trim($_POST['market_name'] ?? $_POST['name'] ?? ''),
            'market_phone'        => trim($_POST['market_phone'] ?? $_POST['phone'] ?? ''),
            'market_email'        => trim($_POST['market_email'] ?? $_POST['email'] ?? ''),
            'market_manager_name' => trim($_POST['market_manager_name'] ?? $_POST['manager_name'] ?? ''),
            'market_status_code'  => $_POST['market_status_code'] ?? $_POST['status_code'] ?? 'active'
        ];

        $validator = new validator();
        $validator->required('market_name', $data['market_name'], 'Tên chợ không được để trống.')
                  ->required('market_code', $data['market_code'], 'Mã chợ không được để trống.');

        $this->render->abort400($validator, 'create', 'market');

        try {
            $marketModel = new marketModel();
            $marketModel->create($data);
            general::log('create_market', "Tạo mới chợ: {$data['market_name']} (Mã: {$data['market_code']})");
            $this->render->apiResponse('create', 'market', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'create', 'market');
        }
    }

    /**
     * API cập nhật thông tin chợ (AJAX POST)
     */
    public function editMarket() {
        $this->render->abort405('POST', 'update', 'market');
        $this->render->abort403(marketService::isSuperAdmin(), 'update', 'market');
        
        $user_market_market_id = $_POST['id'] ?? $_POST['user_market_market_id'] ?? '';
        $this->render->abort400(!empty($user_market_market_id), 'update', 'market', 'Thiếu ID chợ.');

        $data = [
            'market_code'  => trim($_POST['market_code'] ?? ''),
            'market_name'         => trim($_POST['market_name'] ?? $_POST['name'] ?? ''),
            'market_phone'        => trim($_POST['market_phone'] ?? $_POST['phone'] ?? ''),
            'market_email'        => trim($_POST['market_email'] ?? $_POST['email'] ?? ''),
            'market_manager_name' => trim($_POST['market_manager_name'] ?? $_POST['manager_name'] ?? ''),
            'market_status_code'  => $_POST['market_status_code'] ?? $_POST['status_code'] ?? 'active'
        ];

        $validator = new validator();
        $validator->required('market_name', $data['market_name'], 'Tên chợ không được để trống.')
                  ->required('market_code', $data['market_code'], 'Mã chợ không được để trống.');

        $this->render->abort400($validator, 'update', 'market');

        try {
            $marketModel = new marketModel();
            $marketModel->update($user_market_market_id, $data);
            general::log('update_market', "Cập nhật thông tin chợ: {$data['market_name']} (ID: {$user_market_market_id}, Mã: {$data['market_code']})");
            $this->render->apiResponse('update', 'market', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'update', 'market');
        }
    }

    /**
     * API xóa chợ (AJAX POST)
     */
    public function deleteMarket() {
        $this->render->abort405('POST', 'delete', 'market');
        $this->render->abort403(marketService::isSuperAdmin(), 'delete', 'market');
        
        $user_market_market_id = $_POST['id'] ?? $_POST['user_market_market_id'] ?? '';
        $this->render->abort400(!empty($user_market_market_id), 'delete', 'market', 'Thiếu ID chợ cần xóa.');

        $db = database::getInstance();

        // Ràng buộc 1: Kiểm tra xem chợ có khu vực (areas) nào không
        $hasAreas = $db->selectOne("SELECT 1 FROM areas WHERE area_market_id = :id LIMIT 1", ['id' => $user_market_market_id]);
        if ($hasAreas) {
            $this->render->abort400(false, 'delete', 'market', 'Chợ đang có khu vực hoặc quầy sạp trực thuộc, không thể xóa.');
        }

        // Ràng buộc 2: Kiểm tra xem chợ có nhân viên/quản lý (user_markets) nào đang liên kết không
        $hasUsers = $db->selectOne("SELECT 1 FROM user_markets WHERE user_market_market_id = :id LIMIT 1", ['id' => $user_market_market_id]);
        if ($hasUsers) {
            $this->render->abort400(false, 'delete', 'market', 'Chợ đang được gán cho nhân viên hoặc quản lý, không thể xóa.');
        }

        try {
            $marketModel = new marketModel();
            $marketModel->delete($user_market_market_id);
            general::log('delete_market', "Xóa chợ ID: {$user_market_market_id}");
            $this->render->apiResponse('delete', 'market', true);
        } catch (Exception $e) {
            $this->render->abort500($e, 'delete', 'market');
        }
    }

}
