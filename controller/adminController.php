<?php
Class adminController extends baseController
{
	private function ensureAdminPermissionTables()
	{
		global $db;
		$db->query("CREATE TABLE IF NOT EXISTS hicrm_admin_menu_permissions (
			id int(11) NOT NULL AUTO_INCREMENT,
			permission_key varchar(100) NOT NULL,
			permission_name varchar(255) NOT NULL,
			parent_key varchar(100) DEFAULT NULL,
			sort_order int(11) NOT NULL DEFAULT 0,
			permission_status int(11) NOT NULL DEFAULT 1,
			PRIMARY KEY (id),
			UNIQUE KEY uniq_permission_key (permission_key)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
		$db->query("CREATE TABLE IF NOT EXISTS hicrm_user_group_permissions (
			id int(11) NOT NULL AUTO_INCREMENT,
			group_id int(11) NOT NULL,
			permission_id int(11) NOT NULL,
			PRIMARY KEY (id),
			UNIQUE KEY uniq_group_permission (group_id, permission_id),
			KEY idx_group_id (group_id),
			KEY idx_permission_id (permission_id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
	}

	private function getAdminMenuDefinitions()
	{
		return array(
			array('key' => 'dashboard', 'name' => 'Trang chủ', 'parent' => '', 'sort' => 10),
			array('key' => 'employers', 'name' => 'Quản lý nhà tuyển dụng', 'parent' => 'employer_section', 'sort' => 20),
			array('key' => 'employer_posts', 'name' => 'Quản lý bài đăng tuyển dụng', 'parent' => 'employer_section', 'sort' => 21),
			array('key' => 'candidates', 'name' => 'Quản lý ứng viên', 'parent' => '', 'sort' => 30),
			array('key' => 'students', 'name' => 'Quản lý sinh viên', 'parent' => '', 'sort' => 40),
			array('key' => 'events', 'name' => 'Quản lý tin tức & sự kiện', 'parent' => 'news_section', 'sort' => 50),
			array('key' => 'news_comments', 'name' => 'Quản lý bình luận tin tức', 'parent' => 'news_section', 'sort' => 51),
			array('key' => 'google_meet', 'name' => 'Sàn việc làm online', 'parent' => '', 'sort' => 60),
			array('key' => 'users', 'name' => 'Quản lý tài khoản', 'parent' => 'account_section', 'sort' => 70),
			array('key' => 'groups', 'name' => 'Quản lý nhóm quyền', 'parent' => 'account_section', 'sort' => 71),
			array('key' => 'images', 'name' => 'Thư viện hình ảnh', 'parent' => '', 'sort' => 80),
			array('key' => 'videos', 'name' => 'Thư viện video', 'parent' => '', 'sort' => 90),
			array('key' => 'config', 'name' => 'Danh mục tham số', 'parent' => 'system_section', 'sort' => 100),
			array('key' => 'settings', 'name' => 'Cài đặt hệ thống', 'parent' => 'system_section', 'sort' => 101)
		);
	}

	private function seedAdminMenuPermissions()
	{
		global $db;
		foreach($this->getAdminMenuDefinitions() as $menu){
			$db->query("INSERT IGNORE INTO hicrm_admin_menu_permissions(permission_key, permission_name, parent_key, sort_order, permission_status)
				VALUES ('".$db->escapestring($menu['key'])."','".$db->escapestring($menu['name'])."','".$db->escapestring($menu['parent'])."','".intval($menu['sort'])."','1')");
		}
	}

	private function getCurrentAdminUser()
	{
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){
			return null;
		}
		$db->query("SELECT u.*, g.group_name
			FROM hicrm_users AS u
			LEFT JOIN hicrm_user_groups AS g ON g.id = u.user_group
			WHERE u.id = '".intval($_SESSION['user']['id'])."' LIMIT 1");
		return $db->fetch_object(true);
	}

	private function getUserAllowedMenuKeys($user)
	{
		global $db;
		if(!$user){
			return array();
		}
		if(intval($user->user_group) === 1){
			return array('*');
		}
		$group_id = intval($user->user_group);
		if($group_id <= 0){
			return array();
		}
		$db->query("SELECT p.permission_key
			FROM hicrm_user_group_permissions AS gp
			INNER JOIN hicrm_admin_menu_permissions AS p ON p.id = gp.permission_id
			WHERE gp.group_id = '".$group_id."' AND p.permission_status NOT IN(99)");
		$rows = $db->fetch_object();
		$allowed = array();
		if(is_array($rows)){
			foreach($rows as $row){
				$allowed[] = $row->permission_key;
			}
		}
		return $allowed;
	}

	private function adminHasMenuPermission($allowed_keys, $permission_key = '')
	{
		if($permission_key === ''){
			return true;
		}
		if(in_array('*', $allowed_keys, true)){
			return true;
		}
		return in_array($permission_key, $allowed_keys, true);
	}

	private function prepareAdminAccess($permission_key = '')
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){
			header("Location: ".XC_URL."/admin/login");
			return false;
		}
		$this->ensureAdminPermissionTables();
		$this->seedAdminMenuPermissions();
		$user = $this->getCurrentAdminUser();
		$allowed_keys = $this->getUserAllowedMenuKeys($user);
		$this->view->data['current_admin_user'] = $user;
		$this->view->data['allowed_admin_menu'] = $allowed_keys;
		if(!$this->adminHasMenuPermission($allowed_keys, $permission_key)){
			$this->view->data['page_title'] = "Bạn không có quyền truy cập trang này";
			$this->view->data['page_description'] = "Tài khoản hiện tại chưa được cấp quyền cho chức năng quản trị này.";
			$this->view->show('404');
			return false;
		}
		return true;
	}

    public function index()
    {
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); return; }
		$db->query("SELECT * FROM hicrm_users WHERE id = '".$_SESSION['user']['id']."'");
		
		$user = $db->fetch_object(true);
		if($user->user_group == 1){
			$this->view->admintmp('index');
		}else{
			$this->view->data['page_title'] = "Bạn không có quyền truy cập trang này";
			$this->view->data['page_description'] = "Xin lỗi, Trang này yêu cầu quyền quản trị.";
			$this->view->show('404');
		}
		// $this->view->show("backend/index");
		
		
		
    }
	public function login()
	{ 
		
		$this->view->admintmp("login");
	}
	public function employyer($para = ''){
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); return; }
		if(!$this->adminTableExists('hicrm_employers')){
			$this->renderAdminNotice(
				"Quản lý nhà tuyển dụng",
				"Không tìm thấy bảng hicrm_employers trong cơ sở dữ liệu hiện tại.",
				"employers"
			);
			return;
		}

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employer_action'])){
			$action = trim($_POST['employer_action']);
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

			if($action === 'save'){
				$company_name = trim(isset($_POST['company_name']) ? $_POST['company_name'] : '');
				$tax_code = trim(isset($_POST['tax_code']) ? $_POST['tax_code'] : '');
				$province_id = isset($_POST['province_id']) && $_POST['province_id'] !== '' ? intval($_POST['province_id']) : 0;
				$company_size = trim(isset($_POST['company_size']) ? $_POST['company_size'] : '');
				$website_url = trim(isset($_POST['website_url']) ? $_POST['website_url'] : '');
				$fanpage_url = trim(isset($_POST['fanpage_url']) ? $_POST['fanpage_url'] : '');
				$address_detail = trim(isset($_POST['address_detail']) ? $_POST['address_detail'] : '');
				$description = trim(isset($_POST['description']) ? $_POST['description'] : '');

				if($company_name === ''){
					$this->setAdminFlash('info', 'Vui lòng nhập tên nhà tuyển dụng.');
					$this->adminRedirect('/admin/employers');
				}

				if($tax_code !== ''){
					$db->query("SELECT id FROM hicrm_employers WHERE tax_code = '".$db->escapestring($tax_code)."' AND id <> '".$id."' LIMIT 1");
					if($db->num_row() > 0){
						$this->setAdminFlash('info', 'Mã số thuế đã tồn tại trong hệ thống.');
						$this->adminRedirect('/admin/employers');
					}
				}

				$logo_upload = $this->adminUploadEmployerLogo('logo_file');
				if($logo_upload['status'] !== 200 && $logo_upload['status'] !== 204){
					$this->setAdminFlash('info', $logo_upload['message']);
					$this->adminRedirect('/admin/employers');
				}

				if($id > 0){
					$db->query("SELECT id FROM hicrm_employers WHERE id = '".$id."' LIMIT 1");
					if(!$db->num_row()){
						$this->setAdminFlash('info', 'Không tìm thấy nhà tuyển dụng cần cập nhật.');
						$this->adminRedirect('/admin/employers');
					}

					$fields = array(
						"company_name = '".$db->escapestring($company_name)."'"
					);
					if($this->adminColumnExists('hicrm_employers', 'tax_code')){
						$fields[] = "tax_code = ".($tax_code !== '' ? "'".$db->escapestring($tax_code)."'" : "NULL");
					}
					if($this->adminColumnExists('hicrm_employers', 'province_id')){
						$fields[] = "province_id = ".($province_id > 0 ? "'".$province_id."'" : "NULL");
					}
					if($this->adminColumnExists('hicrm_employers', 'company_size')){
						$fields[] = "company_size = ".($company_size !== '' ? "'".$db->escapestring($company_size)."'" : "NULL");
					}
					if($this->adminColumnExists('hicrm_employers', 'website_url')){
						$fields[] = "website_url = ".($website_url !== '' ? "'".$db->escapestring($website_url)."'" : "NULL");
					}
					if($this->adminColumnExists('hicrm_employers', 'fanpage_url')){
						$fields[] = "fanpage_url = ".($fanpage_url !== '' ? "'".$db->escapestring($fanpage_url)."'" : "NULL");
					}
					if($this->adminColumnExists('hicrm_employers', 'address_detail')){
						$fields[] = "address_detail = ".($address_detail !== '' ? "'".$db->escapestring($address_detail)."'" : "NULL");
					}
					if($this->adminColumnExists('hicrm_employers', 'description')){
						$fields[] = "description = ".($description !== '' ? "'".$db->escapestring($description)."'" : "NULL");
					}
					if($logo_upload['status'] === 200 && $this->adminColumnExists('hicrm_employers', 'logo_url')){
						$fields[] = "logo_url = '".$db->escapestring($logo_upload['path'])."'";
					}
					if($this->adminColumnExists('hicrm_employers', 'updated_at')){
						$fields[] = "updated_at = NOW()";
					}

					$db->query("UPDATE hicrm_employers SET ".implode(', ', $fields)." WHERE id = '".$id."' LIMIT 1");
					$this->setAdminFlash('success', 'Đã cập nhật nhà tuyển dụng.');
				}else{
					$owner_user_id = 0;
					if($this->adminColumnExists('hicrm_employers', 'user_id')){
						$owner_user_id = $this->createAdminEmployerOwner($company_name);
						if($owner_user_id <= 0){
							$this->setAdminFlash('info', 'Không thể tạo tài khoản đại diện cho nhà tuyển dụng.');
							$this->adminRedirect('/admin/employers');
						}
					}

					$columns = array('company_name');
					$values = array("'".$db->escapestring($company_name)."'");

					if($this->adminColumnExists('hicrm_employers', 'user_id')){
						$columns[] = 'user_id';
						$values[] = "'".$owner_user_id."'";
					}
					if($this->adminColumnExists('hicrm_employers', 'tax_code')){
						$columns[] = 'tax_code';
						$values[] = $tax_code !== '' ? "'".$db->escapestring($tax_code)."'" : "NULL";
					}
					if($this->adminColumnExists('hicrm_employers', 'province_id')){
						$columns[] = 'province_id';
						$values[] = $province_id > 0 ? "'".$province_id."'" : "NULL";
					}
					if($this->adminColumnExists('hicrm_employers', 'company_size')){
						$columns[] = 'company_size';
						$values[] = $company_size !== '' ? "'".$db->escapestring($company_size)."'" : "NULL";
					}
					if($this->adminColumnExists('hicrm_employers', 'website_url')){
						$columns[] = 'website_url';
						$values[] = $website_url !== '' ? "'".$db->escapestring($website_url)."'" : "NULL";
					}
					if($this->adminColumnExists('hicrm_employers', 'fanpage_url')){
						$columns[] = 'fanpage_url';
						$values[] = $fanpage_url !== '' ? "'".$db->escapestring($fanpage_url)."'" : "NULL";
					}
					if($this->adminColumnExists('hicrm_employers', 'address_detail')){
						$columns[] = 'address_detail';
						$values[] = $address_detail !== '' ? "'".$db->escapestring($address_detail)."'" : "NULL";
					}
					if($this->adminColumnExists('hicrm_employers', 'description')){
						$columns[] = 'description';
						$values[] = $description !== '' ? "'".$db->escapestring($description)."'" : "NULL";
					}
					if($this->adminColumnExists('hicrm_employers', 'logo_url')){
						$columns[] = 'logo_url';
						$values[] = $logo_upload['status'] === 200 ? "'".$db->escapestring($logo_upload['path'])."'" : "NULL";
					}
					if($this->adminColumnExists('hicrm_employers', 'is_linked_school')){
						$columns[] = 'is_linked_school';
						$values[] = "'0'";
					}
					if($this->adminColumnExists('hicrm_employers', 'created_at')){
						$columns[] = 'created_at';
						$values[] = "NOW()";
					}
					if($this->adminColumnExists('hicrm_employers', 'updated_at')){
						$columns[] = 'updated_at';
						$values[] = "NOW()";
					}

					$db->query("INSERT INTO hicrm_employers (".implode(', ', $columns).") VALUES (".implode(', ', $values).")");
					$new_employer_id = intval($db->insert_id());
					$this->updateAdminEmployerOwnerEmployee($owner_user_id, $new_employer_id);
					$this->setAdminFlash('success', 'Đã thêm nhà tuyển dụng mới.');
				}
			}elseif($action === 'link' && $id > 0){
				$db->query("SELECT id FROM hicrm_employers WHERE id = '".$id."' LIMIT 1");
				if($db->num_row()){
					$update_fields = array("is_linked_school = 1");
					if($this->adminColumnExists('hicrm_employers', 'updated_at')){
						$update_fields[] = "updated_at = NOW()";
					}
					$db->query("UPDATE hicrm_employers SET ".implode(', ', $update_fields)." WHERE id = '".$id."' LIMIT 1");
					$this->setAdminFlash('success', 'Đã liên kết nhà tuyển dụng với nhà trường.');
				}else{
					$this->setAdminFlash('info', 'Không tìm thấy nhà tuyển dụng cần liên kết.');
				}
			}

			$this->adminRedirect('/admin/employers');
		}

		$where = array("1=1");
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
		$linked_status = isset($_GET['linked_status']) ? trim($_GET['linked_status']) : "";
		$page = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
		$per_page = 10;
		$province_name_column = $this->getFirstExistingColumn('hicrm_provinces', array('province_name', 'place_name'));
		$province_join = $province_name_column !== '' ? "LEFT JOIN hicrm_provinces p ON p.id = e.province_id" : "";
		$province_select = $province_name_column !== '' ? "p.".$province_name_column." AS province_name," : "'' AS province_name,";
		$post_summary_join = "";
		$post_summary_select = "0 AS total_posts,";

		if($this->adminTableExists('hicrm_job_posts')){
			$post_where = "1=1";
			if($this->adminColumnExists('hicrm_job_posts', 'status')){
				$post_where .= " AND IFNULL(status, 'pending') <> '99'";
			}
			$post_summary_join = "LEFT JOIN (
				SELECT employer_id, COUNT(*) AS total_posts
				FROM hicrm_job_posts
				WHERE ".$post_where."
				GROUP BY employer_id
			) jp ON jp.employer_id = e.id";
			$post_summary_select = "COALESCE(jp.total_posts, 0) AS total_posts,";
		}

		if($linked_status === "linked"){
			$where[] = "e.is_linked_school = 1";
		}elseif($linked_status === "unlinked"){
			$where[] = "e.is_linked_school = 0";
		}

		if($keyword !== ""){
			$keyword_sql = $db->escapestring($keyword);
			$where[] = "(e.company_name LIKE '%".$keyword_sql."%' OR IFNULL(e.tax_code, '') LIKE '%".$keyword_sql."%' OR IFNULL(e.address_detail, '') LIKE '%".$keyword_sql."%' OR IFNULL(reps.representative_name, '') LIKE '%".$keyword_sql."%'".($province_name_column !== '' ? " OR IFNULL(p.".$province_name_column.", '') LIKE '%".$keyword_sql."%'" : "").")";
		}

		$base_sql = "FROM hicrm_employers e
					".$province_join."
					".$post_summary_join."
					LEFT JOIN (
						SELECT employee_id,
							GROUP_CONCAT(DISTINCT full_name SEPARATOR ', ') AS representative_name,
							GROUP_CONCAT(DISTINCT user_email SEPARATOR ', ') AS representative_email,
							GROUP_CONCAT(DISTINCT user_phone SEPARATOR ', ') AS representative_phone
						FROM hicrm_users
						WHERE user_group = '2' AND user_deleted_at IS NULL
						GROUP BY employee_id
					) reps ON reps.employee_id = e.id
					WHERE ".implode(" AND ", $where);

		$db->query("SELECT COUNT(e.id) AS total ".$base_sql);
		$total_employers = (int)$db->fetch_object(true)->total;
		$total_pages = max(1, ceil($total_employers / $per_page));
		if($page > $total_pages){
			$page = $total_pages;
		}
		$offset = ($page - 1) * $per_page;

		$db->query("SELECT e.*,
					".$province_select."
					".$post_summary_select."
					reps.representative_name,
					reps.representative_email,
					reps.representative_phone
					".$base_sql."
					ORDER BY e.is_linked_school DESC, e.id DESC
					LIMIT ".$offset.",".$per_page);

		$this->view->data['active_menu'] = "employers";
		$this->view->data['employers'] = $db->fetch_object();
		$this->view->data['keyword'] = $keyword;
		$this->view->data['linked_status'] = $linked_status;
		$this->view->data['page'] = $page;
		$this->view->data['per_page'] = $per_page;
		$this->view->data['total_employers'] = $total_employers;
		$this->view->data['total_pages'] = $total_pages;
		$this->view->data['employer_flash'] = $this->getAdminFlash();
		$this->view->data['province_options'] = $province_name_column !== '' ? $this->getAdminReferenceOptions('hicrm_provinces', $province_name_column) : array();
		$this->view->admintmp('employyer');
	}

	public function employers($para = ''){
		if(isset($para[1]) && $para[1] == 'posts'){
			$this->employerposts($para);
			return;
		}
		$this->employyer($para);
	}
	public function register()
{
   

    // GET: hiển thị trang đăng ký
    $this->view->admintmp("register");
}
	
	public function logout(){
		session_unset();
		header('Location:' .XC_URL. '/admin/login');
	}

	private function renderAdminNotice($title, $description, $active_menu = '')
	{
		$this->view->data['active_menu'] = $active_menu;
		$this->view->data['notice_title'] = $title;
		$this->view->data['notice_description'] = $description;
		$this->view->admintmp("admin-notice");
	}

	private function adminRedirect($path)
	{
		header("Location: ".XC_URL.$path);
		exit();
	}

	private function setAdminFlash($type, $message)
	{
		$_SESSION['admin_flash'] = array(
			'type' => $type,
			'message' => $message
		);
	}

	private function getAdminFlash()
	{
		$flash = isset($_SESSION['admin_flash']) ? $_SESSION['admin_flash'] : null;
		unset($_SESSION['admin_flash']);
		return $flash;
	}

	private function adminTableExists($table_name)
	{
		global $db;
		$db->query("SHOW TABLES LIKE '".$db->escapestring($table_name)."'");
		return $db->num_row() > 0;
	}

	private function adminColumnExists($table_name, $column_name)
	{
		global $db;
		if(!$this->adminTableExists($table_name)){
			return false;
		}
		$db->query("SHOW COLUMNS FROM `".$db->escapestring($table_name)."` LIKE '".$db->escapestring($column_name)."'");
		return $db->num_row() > 0;
	}

	private function ensureAdminFeatureTables()
	{
		global $db;
		$db->query("CREATE TABLE IF NOT EXISTS hicrm_news_comments (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			news_id bigint(20) DEFAULT NULL,
			user_id bigint(20) DEFAULT NULL,
			parent_id bigint(20) DEFAULT NULL,
			author_name varchar(190) DEFAULT NULL,
			author_email varchar(190) DEFAULT NULL,
			comment_content text NOT NULL,
			admin_reply text DEFAULT NULL,
			comment_status tinyint(4) NOT NULL DEFAULT 1,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_news_id (news_id),
			KEY idx_user_id (user_id),
			KEY idx_parent_id (parent_id),
			KEY idx_comment_status (comment_status)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

		$db->query("CREATE TABLE IF NOT EXISTS hicrm_google_meet (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			title varchar(255) NOT NULL,
			meet_url varchar(500) NOT NULL,
			host_name varchar(190) DEFAULT NULL,
			meeting_date date DEFAULT NULL,
			start_time varchar(20) DEFAULT NULL,
			end_time varchar(20) DEFAULT NULL,
			description text DEFAULT NULL,
			meet_status tinyint(4) NOT NULL DEFAULT 1,
			sort_order int(11) NOT NULL DEFAULT 0,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_meet_status (meet_status)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

		$db->query("CREATE TABLE IF NOT EXISTS hicrm_videos (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			title varchar(255) NOT NULL,
			video_url varchar(500) NOT NULL,
			thumbnail_url varchar(500) DEFAULT NULL,
			description text DEFAULT NULL,
			video_status tinyint(4) NOT NULL DEFAULT 1,
			sort_order int(11) NOT NULL DEFAULT 0,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_video_status (video_status)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
	}

	private function adminStatusLabel($status)
	{
		$map = array(
			1 => array('label' => 'Chờ duyệt', 'class' => 'warning'),
			2 => array('label' => 'Từ chối', 'class' => 'danger'),
			3 => array('label' => 'Đã duyệt', 'class' => 'success'),
			99 => array('label' => 'Đã xóa', 'class' => 'secondary')
		);
		return isset($map[(int)$status]) ? $map[(int)$status] : array('label' => 'Không xác định', 'class' => 'secondary');
	}

	private function getAdminReferenceOptions($table_name, $label_column, $conditions = '')
	{
		global $db;
		if(!$this->adminTableExists($table_name) || !$this->adminColumnExists($table_name, $label_column)){
			return array();
		}
		$sql = "SELECT id, ".$label_column." AS label FROM ".$table_name;
		if($conditions != ''){
			$sql .= " WHERE ".$conditions;
		}
		$sql .= " ORDER BY label ASC";
		$db->query($sql);
		$rows = $db->fetch_object();
		return is_array($rows) ? $rows : array();
	}

	private function getEmployerOptions()
	{
		global $db;
		if(!$this->adminTableExists('hicrm_employers')){
			return array();
		}
		$db->query("SELECT id, company_name AS label FROM hicrm_employers ORDER BY company_name ASC");
		$rows = $db->fetch_object();
		return is_array($rows) ? $rows : array();
	}

	private function adminUploadEmployerLogo($field_name = 'logo_file', $max_size = 2097152)
	{
		if(!isset($_FILES[$field_name]) || $_FILES[$field_name]['error'] == 4){
			return array('status' => 204, 'path' => '');
		}
		if($_FILES[$field_name]['error'] != 0){
			return array('status' => 400, 'message' => 'Tệp avatar tải lên không hợp lệ.');
		}
		if($_FILES[$field_name]['size'] > $max_size){
			return array('status' => 400, 'message' => 'Dung lượng avatar vượt quá giới hạn cho phép.');
		}

		$file_ext = strtolower(pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION));
		$allowed = array('jpg', 'jpeg', 'png', 'webp', 'gif');
		if(!in_array($file_ext, $allowed)){
			return array('status' => 400, 'message' => 'Avatar chỉ hỗ trợ JPG, PNG, WEBP hoặc GIF.');
		}
		if(!@getimagesize($_FILES[$field_name]['tmp_name'])){
			return array('status' => 400, 'message' => 'Tệp tải lên không phải hình ảnh hợp lệ.');
		}

		$upload_dir = './uploads/employers/';
		if(!is_dir($upload_dir)){
			mkdir($upload_dir, 0777, true);
		}

		$file_name = 'logo_admin_'.date('YmdHis').'_'.rand(1000, 9999).'.'.$file_ext;
		$target = $upload_dir.$file_name;
		if(!move_uploaded_file($_FILES[$field_name]['tmp_name'], $target)){
			return array('status' => 500, 'message' => 'Không thể lưu avatar nhà tuyển dụng.');
		}

		return array('status' => 200, 'path' => 'uploads/employers/'.$file_name);
	}

	private function createAdminEmployerOwner($company_name)
	{
		global $db;
		if(!$this->adminTableExists('hicrm_users')){
			return 0;
		}

		$seed = date('YmdHis').rand(1000, 9999);
		$email = 'employer-'.$seed.'@vieclam.local';
		$username = 'employer_'.$seed;
		$display_name = 'Tài khoản '.$company_name;
		$password = md5($seed);
		$columns = array();
		$values = array();

		if($this->adminColumnExists('hicrm_users', 'employee_id')){
			$columns[] = 'employee_id';
			$values[] = "'0'";
		}
		if($this->adminColumnExists('hicrm_users', 'user_username')){
			$columns[] = 'user_username';
			$values[] = "'".$db->escapestring($username)."'";
		}
		if($this->adminColumnExists('hicrm_users', 'full_name')){
			$columns[] = 'full_name';
			$values[] = "'".$db->escapestring($display_name)."'";
		}
		if($this->adminColumnExists('hicrm_users', 'user_email')){
			$columns[] = 'user_email';
			$values[] = "'".$db->escapestring($email)."'";
		}
		if($this->adminColumnExists('hicrm_users', 'user_password')){
			$columns[] = 'user_password';
			$values[] = "'".$db->escapestring($password)."'";
		}
		if($this->adminColumnExists('hicrm_users', 'user_group')){
			$columns[] = 'user_group';
			$values[] = "'2'";
		}
		if($this->adminColumnExists('hicrm_users', 'user_status')){
			$columns[] = 'user_status';
			$values[] = "'1'";
		}
		if($this->adminColumnExists('hicrm_users', 'user_is_subscribed')){
			$columns[] = 'user_is_subscribed';
			$values[] = "'0'";
		}
		if($this->adminColumnExists('hicrm_users', 'user_created_at')){
			$columns[] = 'user_created_at';
			$values[] = "NOW()";
		}
		if($this->adminColumnExists('hicrm_users', 'user_updated_at')){
			$columns[] = 'user_updated_at';
			$values[] = "NOW()";
		}

		if(empty($columns)){
			return 0;
		}

		$db->query("INSERT INTO hicrm_users (".implode(',', $columns).") VALUES (".implode(',', $values).")");
		return intval($db->insert_id());
	}

	private function updateAdminEmployerOwnerEmployee($user_id, $employer_id)
	{
		global $db;
		if($user_id <= 0 || $employer_id <= 0 || !$this->adminColumnExists('hicrm_users', 'employee_id')){
			return;
		}
		$db->query("UPDATE hicrm_users SET employee_id = '".intval($employer_id)."' WHERE id = '".intval($user_id)."' LIMIT 1");
	}

	private function getFirstExistingColumn($table_name, $columns = array())
	{
		if(!is_array($columns)){
			$columns = array($columns);
		}
		foreach($columns as $column){
			if($this->adminColumnExists($table_name, $column)){
				return $column;
			}
		}
		return '';
	}

	private function normalizeJobPostStatus($status)
	{
		$allowed = array('draft','pending','published','closed','rejected');
		return in_array($status, $allowed) ? $status : 'pending';
	}

	public function users($para)
	{
		
		$userModel = $this->model->get('userModel');
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		if(isset($para[1]) && $para[1] == "add"){
			$this->view->data['method'] = 'add';
			$user_category = $userModel->get_user_category();
			$this->view->data['user_category'] = $user_category;
			$this->view->data['roles'] = $userModel->role_user();
			$this->view->data['pagetitle'] = 'Thêm tài khoản';
			$this->view->admintmp("user-action");
		}elseif(isset($para[1]) && $para[1] == "edit"){
			$this->view->data['method'] = 'edit';
			$this->view->data['user_categories'] = $userModel->get_user_category();
			$this->view->data['roles'] = $userModel->role_user();
			$this->view->data['user'] = $userModel->get_user($para[2]);
			$this->view->data['pagetitle'] = 'Sửa tài khoản';
			$this->view->admintmp("user-action");
		}elseif(isset($para[1]) && $para[1] == "detail"){
			$this->view->data['method'] = 'detail';
			$this->view->data['pagetitle'] = 'Chi tiết tài khoản';
			$this->view->admintmp("user-action");
		}elseif(isset($para[1]) && $para[1] == "role"){
			if(!isset($para[2]) || $para[2] == ""){
				$this->renderAdminNotice(
					"Phân quyền tài khoản",
					"Vui lòng chọn một tài khoản trong danh sách quản lý tài khoản để thực hiện phân quyền chi tiết.",
					"users_role"
				);
				return;
			}
			$this->view->data['roles'] = $userModel->role_user();
			$this->view->data['user'] = $userModel->get_user($para[2]);
			$this->view->data['role_detail'] = $userModel->role_user_detail($userModel->get_user($para[2])->user_group);
			$this->view->data['user_roles'] = $userModel->get_user_role();
			$this->view->data['method'] = 'role';
			$this->view->data['active_menu'] = 'users_role';
			$this->view->data['pagetitle'] = 'Thêm nhóm quyền';
			$this->view->admintmp("user-role");
		}
		else{
			$this->view->data['active_menu'] = "users";
			$this->view->data['users'] = $userModel->get_user_list();
			$this->view->admintmp("users");
		}
		
	}
	//end users

	public function candidates($para = array())
	{
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }

		$has_status = $this->adminColumnExists('hicrm_candidates', 'status');
		$has_updated_at = $this->adminColumnExists('hicrm_candidates', 'updated_at');
		$has_user_id = $this->adminColumnExists('hicrm_candidates', 'user_id');
		$has_full_name = $this->adminColumnExists('hicrm_candidates', 'full_name');
		$has_phone = $this->adminColumnExists('hicrm_candidates', 'phone');
		$has_desired_position = $this->adminColumnExists('hicrm_candidates', 'desired_position');
		$has_desired_province_id = $this->adminColumnExists('hicrm_candidates', 'desired_province_id');
		$has_major = $this->adminColumnExists('hicrm_candidates', 'major');
		$has_desired_salary = $this->adminColumnExists('hicrm_candidates', 'desired_salary');
		$has_users_table = $this->adminTableExists('hicrm_users');

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['candidate_action'])){
			$id = isset($_POST['candidate_id']) ? intval($_POST['candidate_id']) : 0;
			if($id > 0){
				switch($_POST['candidate_action']){
					case 'approve':
						if($has_status){
							$fields = array("status = 3");
							if($has_updated_at){
								$fields[] = "updated_at = NOW()";
							}
							$db->query("UPDATE hicrm_candidates SET ".implode(', ', $fields)." WHERE id = '".$id."' LIMIT 1");
							$this->setAdminFlash('success', 'Đã phê duyệt hồ sơ ứng viên.');
						}else{
							$this->setAdminFlash('warning', 'Bảng ứng viên hiện chưa có cột trạng thái để phê duyệt.');
						}
						break;
					case 'reject':
						if($has_status){
							$fields = array("status = 2");
							if($has_updated_at){
								$fields[] = "updated_at = NOW()";
							}
							$db->query("UPDATE hicrm_candidates SET ".implode(', ', $fields)." WHERE id = '".$id."' LIMIT 1");
							$this->setAdminFlash('success', 'Đã từ chối hồ sơ ứng viên.');
						}else{
							$this->setAdminFlash('warning', 'Bảng ứng viên hiện chưa có cột trạng thái để từ chối.');
						}
						break;
					case 'delete':
						if($has_status){
							$fields = array("status = 99");
							if($has_updated_at){
								$fields[] = "updated_at = NOW()";
							}
							$db->query("UPDATE hicrm_candidates SET ".implode(', ', $fields)." WHERE id = '".$id."' LIMIT 1");
						}else{
							$db->query("DELETE FROM hicrm_candidates WHERE id = '".$id."' LIMIT 1");
						}
						$this->setAdminFlash('success', 'Đã xóa hồ sơ ứng viên.');
						break;
				}
			}
			$this->adminRedirect('/admin/candidates');
		}

		$where = array("1=1");
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$status_filter = isset($_GET['status']) ? trim($_GET['status']) : '';
		if($keyword !== ''){
			$kw = $db->escapestring($keyword);
			$search_parts = array();
			if($has_full_name){
				$search_parts[] = "ca.full_name LIKE '%".$kw."%'";
			}
			if($has_phone){
				$search_parts[] = "ca.phone LIKE '%".$kw."%'";
			}
			if($has_desired_position){
				$search_parts[] = "ca.desired_position LIKE '%".$kw."%'";
			}
			if($has_users_table && $has_user_id && $this->adminColumnExists('hicrm_users', 'user_email')){
				$search_parts[] = "u.user_email LIKE '%".$kw."%'";
			}
			if(!empty($search_parts)){
				$where[] = "(".implode(' OR ', $search_parts).")";
			}
		}
		if($has_status && $status_filter !== '' && is_numeric($status_filter)){
			$where[] = "ca.status = '".intval($status_filter)."'";
		}elseif($has_status){
			$where[] = "IFNULL(ca.status,1) <> 99";
		}

		$status_select = $has_status ? "ca.status AS status" : "1 AS status";
		$province_name_column = $this->getFirstExistingColumn('hicrm_provinces', array('province_name', 'place_name'));
		$job_category_name_column = $this->getFirstExistingColumn('hicrm_job_categories', array('job_category_name', 'category_name', 'type_name'));
		$salary_name_column = $this->getFirstExistingColumn('hicrm_salary', array('salary_name', 'title', 'name'));
		$user_join = ($has_users_table && $has_user_id) ? "LEFT JOIN hicrm_users u ON ca.user_id = u.id" : "";
		$user_email_select = ($has_users_table && $has_user_id && $this->adminColumnExists('hicrm_users', 'user_email')) ? "u.user_email AS user_email" : "'' AS user_email";
		$user_phone_select = ($has_users_table && $has_user_id && $this->adminColumnExists('hicrm_users', 'user_phone')) ? "u.user_phone AS user_phone" : "'' AS user_phone";
		$province_join = ($province_name_column !== '' && $has_desired_province_id) ? "LEFT JOIN hicrm_provinces p ON ca.desired_province_id = p.id" : "";
		$category_join = ($job_category_name_column !== '' && $has_major) ? "LEFT JOIN hicrm_job_categories jc ON ca.major = jc.id" : "";
		$salary_join = ($salary_name_column !== '' && $has_desired_salary) ? "LEFT JOIN hicrm_salary s ON ca.desired_salary = s.id" : "";
		$province_select = $province_join !== "" ? "p.".$province_name_column." AS province_name" : "'' AS province_name";
		$category_select = $category_join !== "" ? "jc.".$job_category_name_column." AS job_category_name" : "'' AS job_category_name";
		$salary_select = $salary_join !== "" ? "s.".$salary_name_column." AS salary_name" : "'' AS salary_name";
		$order_by = $has_updated_at ? "ca.updated_at DESC, ca.id DESC" : "ca.id DESC";
		$select_fields = array(
			"ca.*",
			$status_select,
			$user_email_select,
			$user_phone_select,
			$province_select,
			$category_select,
			$salary_select
		);

		$db->query("SELECT ".implode(', ', $select_fields)."
			FROM hicrm_candidates ca
			".$user_join."
			".$province_join."
			".$category_join."
			".$salary_join."
			WHERE ".implode(' AND ', $where)."
			ORDER BY ".$order_by);
		$candidates = $db->fetch_object();

		$detail = null;
		$experiences = array();
		$certificates = array();
		$detail_id = isset($_GET['detail']) ? intval($_GET['detail']) : 0;
		if($detail_id > 0){
			$db->query("SELECT ".implode(', ', $select_fields)."
				FROM hicrm_candidates ca
				".$user_join."
				".$province_join."
				".$category_join."
				".$salary_join."
				WHERE ca.id = '".$detail_id."' LIMIT 1");
			$detail = $db->fetch_object(true);
			if($detail && $this->adminTableExists('hicrm_candidate_experiences')){
				$experience_order = $this->adminColumnExists('hicrm_candidate_experiences', 'start_date') ? "start_date DESC, id DESC" : "id DESC";
				$db->query("SELECT * FROM hicrm_candidate_experiences WHERE candidate_id = '".$detail_id."' ORDER BY ".$experience_order);
				$experiences = $db->fetch_object();
			}
			if($detail && $this->adminTableExists('hicrm_candidate_certificates')){
				$certificate_order = $this->adminColumnExists('hicrm_candidate_certificates', 'issued_date') ? "issued_date DESC, id DESC" : "id DESC";
				$db->query("SELECT * FROM hicrm_candidate_certificates WHERE candidate_id = '".$detail_id."' ORDER BY ".$certificate_order);
				$certificates = $db->fetch_object();
			}
		}

		$this->view->data['active_menu'] = "candidates";
		$this->view->data['candidates'] = is_array($candidates) ? $candidates : array();
		$this->view->data['candidate_detail'] = $detail;
		$this->view->data['candidate_experiences'] = is_array($experiences) ? $experiences : array();
		$this->view->data['candidate_certificates'] = is_array($certificates) ? $certificates : array();
		$this->view->data['candidate_keyword'] = $keyword;
		$this->view->data['candidate_status_filter'] = $status_filter;
		$this->view->data['candidate_flash'] = $this->getAdminFlash();
		$this->view->admintmp("candidates");
	}

	// Quản lý nhóm quyền
	public function groups($para)
	{
		$userModel = $this->model->get('userModel');
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }

		if(isset($para[1]) && $para[1] == "add"){
			$this->view->data['method'] = 'add';
			$this->view->data['pagetitle'] = 'Thêm nhóm quyền mới';
			$this->view->data['user_roles'] = $userModel->get_user_role(); // Load quyền chi tiết để chọn
			$this->view->admintmp("group-add");
		}else{
			$this->view->data['active_menu'] = "groups";
			$this->view->data['groups'] = $userModel->role_user(); // Load nhóm quyền
			$this->view->data['pagetitle'] = 'Danh sách nhóm quyền';
			$this->view->admintmp("groups");
		}
	}
	//end groups

	public function editusers($para){
		$id = $para[1];
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, u.id as uid FROM hicrm_users as u 
					LEFT JOIN hicrm_status as s ON u.user_status = s.id
					LEFT JOIN hicrm_user_groups as g ON u.user_group = g.id
					LEFT JOIN hicrm_departments as d ON u.user_dept = d.id
					 WHERE u.user_status NOT IN(99) and u.id = '$id'
					");
		$user = $db->fetch_object(true);
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments");
		$departments = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_user_groups");
		$user_group = $db->fetch_object();
		$this->view->data['departments'] = $departments;
		$this->view->data['user_group'] = $user_group;
		$this->view->data['user'] = $user;
		$this->view->show('backend/edit-user');
	}
	
	//add customers
	public function addusers()
	{	
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		$db->query("SELECT * FROM hicrm_customers ORDER BY id DESC LIMIT 1");
		$lastno = $db->fetch_object(true)->customer_code;
		$prefix = $this->helper->get_config("customer_prefix");
		//PREFIX1234567
		$lastno = substr($lastno,-7);
		$lastno = $lastno+1;
		$lastno = $prefix."".str_pad($lastno, 7, '0', STR_PAD_LEFT);
		//Ma NV
		$db->query("SELECT * FROM hicrm_employees ORDER BY id DESC LIMIT 1");
		$lastno2 = $db->fetch_object(true)->employee_code;
		$prefix_employee = $this->helper->get_config("employee_prefix");
		//PREFIX1234567
		$lastno2 = substr($lastno2,-7);
		$lastno2 = $lastno2+1;
		$lastno2 = $prefix_employee."".str_pad($lastno2, 7, '0', STR_PAD_LEFT);
		
		$db->query("SELECT * FROM hicrm_employees");
		$customer_staff = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
		$branches = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments");
		$departments = $db->fetch_object();
		$this->view->data['departments'] = $departments;
		$this->view->data['positions'] = $positions;
		$this->view->data['branches'] = $branches; 
		$this->view->data['customer_staff'] = $customer_staff;
		$this->view->data["customer_code"] = $lastno;
		$this->view->data["employee_code"] = $lastno2;
		$this->view->show("backend/add-users");
	}
	//end

	// Quản lý sinh viên
	public function students($para)
	{
		global $db;
		
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		if(isset($para[1]) && $para[1] == "add"){
			$this->view->data['method'] = 'add';
			$user_category = $userModel->get_user_category();
			$this->view->data['user_category'] = $user_category;
			$this->view->data['roles'] = $userModel->role_user();
			$this->view->data['pagetitle'] = 'Thêm tài khoản';
			$this->view->admintmp("user-action");
		}elseif(isset($para[1]) && $para[1] == "edit"){
			$this->view->data['method'] = 'edit';
			$this->view->data['user_categories'] = $userModel->get_user_category();
			$this->view->data['roles'] = $userModel->role_user();
			$this->view->data['user'] = $userModel->get_user($para[2]);
			$this->view->data['pagetitle'] = 'Sửa tài khoản';
			$this->view->admintmp("user-action");
		}elseif(isset($para[1]) && $para[1] == "detail"){
			$this->view->data['method'] = 'detail';
			$this->view->data['pagetitle'] = 'Chi tiết tài khoản';
			$this->view->admintmp("user-action");
		}elseif(isset($para[1]) && $para[1] == "role"){
			$this->view->data['roles'] = $userModel->role_user();
			$this->view->data['user'] = $userModel->get_user($para[2]);
			$this->view->data['role_detail'] = $userModel->role_user_detail($userModel->get_user($para[2])->user_group);
			$this->view->data['user_roles'] = $userModel->get_user_role();
			$this->view->data['method'] = 'role';
			$this->view->data['pagetitle'] = 'Thêm nhóm quyền';
			$this->view->admintmp("user-role");
		}
		else{
			//get student list
			$db->query("SELECT * FROM hicrm_student_profile ORDER BY id DESC"); 
			$this->view->data['active_menu'] = "students";
			$this->view->data['students'] = $db->fetch_object();
			$this->view->admintmp("students");
		}
		
	}
	//end
	public function gioithieu($para){
		$id = $para[1];
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		// $db->query("SELECT *, t.id as tid FROM hicrm_type as t 
		// 			LEFT JOIN hicrm_status as s ON u.user_status = s.id
		// 			LEFT JOIN hicrm_user_groups as g ON u.user_group = g.id
		// 			LEFT JOIN hicrm_departments as d ON u.user_dept = d.id
		// 			 WHERE u.user_status NOT IN(99) and u.id = '$id'
		// 			");
		$db->query("SELECT *, i.id as iid FROM hicrm_introduce  as i
					LEFT JOIN hicrm_categories as c ON i.introduce_category = c.id
					where i.id = '".$id."' " );
		$introduce = $db->fetch_object(true);
		$db->query("SELECT * FROM hicrm_introduce ORDER BY introduce_orderby ASC");		
		$category = $db->fetch_object();
		$this->view->data['id'] = $id;
		$this->view->data['introduce'] = $introduce;
		$this->view->data['category'] = $category;
		$this->view->show('backend/gioithieu');
	}
	public function dmType(){
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		$db->query("SELECT *, t.id as tid FROM hicrm_type as t 
					LEFT JOIN hicrm_dmtype as dmt ON t.type_detail = dmt.id
					 WHERE t.type_status NOT IN(99)");
		$type = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_dmtype");
		$dmtype = $db->fetch_object();
		$this->view->data["type"] = $type;
		$this->view->data["dmtype"] = $dmtype;
		$this->view->show("backend/dmtype");
	}
	public function dmimages(){
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		$db->query("SELECT *, i.id as imageid FROM hicrm_images as i 
					LEFT JOIN hicrm_status as s ON i.image_status = s.id
					LEFT JOIN hicrm_users as u ON i.image_user_created = u.id
					 WHERE i.image_status NOT IN(99) ORDER BY i.image_created_date DESC");
		$images = $db->fetch_object();
		$this->view->data["images"] = $images;
		$this->view->show("backend/dmimage");
	}

	public function images($para = array())
	{
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); return; }
		$db->query("SELECT * FROM hicrm_images WHERE image_status NOT IN(99) ORDER BY id DESC");
		$this->view->data['active_menu'] = "images";
		$this->view->data['images'] = $db->fetch_object();
		$this->view->admintmp("images");
	}
	public function bookings(){
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		$db->query("SELECT *, b.id as ibk FROM hicrm_bookings as b 
					LEFT JOIN hicrm_booking_status as bs ON b.booking_status = bs.id
					LEFT JOIN hicrm_employees as e ON b.booking_doctor = e.id
					ORDER BY b.booking_created_date DESC");
		$bookings = $db->fetch_object();
		$this->view->data["bookings"] = $bookings;
		$this->view->show("backend/bookings");
	}
	// public function news(){
	// 	global $db;
	// 	if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
	// 	$db->query("SELECT *, t.id as tid FROM hicrm_type as t 
	// 				LEFT JOIN hicrm_dmtype as dmt ON t.type_detail = dmt.id
	// 				 WHERE t.type_status NOT IN(99)");
	// 	$type = $db->fetch_object();
	// 	$db->query("SELECT * FROM hicrm_dmtype");
	// 	$dmtype = $db->fetch_object();
	// 	$this->view->data["type"] = $type;
	// 	$this->view->data["dmtype"] = $dmtype;
	// 	$this->view->show("backend/dmnews");
	// }
	public function news($para){
		global $db;
		$method = $para[1];
		$id = $para[2];
		// echo $id;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		$db->query("SELECT *, n.id as nid FROM hicrm_news as n
					LEFT JOIN hicrm_users as u ON n.new_user_created = u.id
					LEFT JOIN hicrm_type as t ON n.new_type = t.type_detail WHERE new_status NOT IN (99) ORDER BY n.new_created_date DESC
					");
		$news = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_dmtype");
		if(isset($method) && $method == 'add'){
			$this->view->data['method'] = 'add';
			
			$this->view->show("backend/new-add");


		}elseif(isset($method) && $method == 'edit'){
			$db->query("SELECT * FROM hicrm_news WHERE id = '".$id."'");		
			$new_detai = $db->fetch_object(true);
			// echo $new_detai->new_name;
			$this->view->data['new_detail'] = $new_detai;
			$this->view->data['method'] = 'edit';
			$this->view->show("backend/new-add");
		}elseif(isset($method) && $method == 'detail'){
			$db->query("SELECT * FROM hicrm_news WHERE id = '".$id."'");		
			$new_detai = $db->fetch_object(true);
			// echo $new_detai->new_name;
			$this->view->data['new_detail'] = $new_detai;
			$this->view->data['method'] = 'edit';
			$this->view->show("backend/new-detail");
		}
		else{
		$dmtype = $db->fetch_object();
		$this->view->data["news"] = $news;
		$this->view->data["dmtype"] = $dmtype;
		$this->view->show("backend/news");
		}
	}
	public function events($para){
		global $db;
		$method = $para[1];
		$id = $para[2];
		// echo $id;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		$db->query("SELECT *, e.id as eid FROM hicrm_events as e
					LEFT JOIN hicrm_users as u ON e.event_user_created = u.id
					LEFT JOIN hicrm_categories as c ON e.event_type = c.id WHERE e.event_status NOT IN (99) ORDER BY e.event_created_date DESC
					");
		$events = $db->fetch_object();
		// $db->query("SELECT * FROM hicrm_dmtype");
		$this->view->data['active_menu'] = "events";
		if(isset($method) && $method == 'add'){
			$this->view->data['method'] = 'add';
			$this->view->admintmp("event-form");


		}elseif(isset($method) && $method == 'edit'){
			$db->query("SELECT * FROM hicrm_events WHERE id = '".$id."'");		
			$event_detai = $db->fetch_object(true);
			// echo $event_detai->event_name;
			$this->view->data['event_detail'] = $event_detai;
			$this->view->data['method'] = 'edit';
			$this->view->admintmp("event-form");
		}elseif(isset($method) && $method == 'detail'){
			$db->query("SELECT * FROM hicrm_events WHERE id = '".$id."'");		
			$event_detai = $db->fetch_object(true);
			// echo $event_detai->event_name;
			$this->view->data['event_detail'] = $event_detai;
			$this->view->data['method'] = 'edit';
			$this->view->admintmp("event-detail");
		}
		else{
		$dmtype = $db->fetch_object();
		$this->view->data["events"] = $events;
		// $this->view->data["dmtype"] = $dmtype;
		$this->view->admintmp("events");
		}
	}

	public function newscomments($para = array())
	{
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		$this->ensureAdminFeatureTables();

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newscomment_action'])){
			$id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
			if($_POST['newscomment_action'] == 'reply' && $id > 0){
				$reply = isset($_POST['admin_reply']) ? $db->escapestring(trim($_POST['admin_reply'])) : '';
				$db->query("UPDATE hicrm_news_comments SET admin_reply = '".$reply."', updated_at = NOW() WHERE id = '".$id."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã lưu phản hồi bình luận.');
			}elseif($_POST['newscomment_action'] == 'delete' && $id > 0){
				$db->query("DELETE FROM hicrm_news_comments WHERE id = '".$id."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã xóa bình luận.');
			}
			$this->adminRedirect('/admin/newscomments');
		}

		$where = array("1=1");
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$news_title_column = $this->getFirstExistingColumn('hicrm_news', array('new_title', 'news_title', 'title', 'post_title'));
		if($keyword !== ''){
			$kw = $db->escapestring($keyword);
			$keyword_where = array(
				"nc.author_name LIKE '%".$kw."%'",
				"nc.author_email LIKE '%".$kw."%'",
				"nc.comment_content LIKE '%".$kw."%'"
			);
			if($news_title_column !== ''){
				$keyword_where[] = "n.".$news_title_column." LIKE '%".$kw."%'";
			}
			$where[] = "(".implode(" OR ", $keyword_where).")";
		}
		$news_join = $news_title_column !== '' ? "LEFT JOIN hicrm_news n ON nc.news_id = n.id" : "";
		$news_select = $news_title_column !== '' ? "n.".$news_title_column." AS new_title," : "'' AS new_title,";
		$db->query("SELECT nc.*, ".$news_select." u.full_name AS user_name
			FROM hicrm_news_comments nc
			LEFT JOIN hicrm_users u ON nc.user_id = u.id
			".$news_join."
			WHERE ".implode(' AND ', $where)."
			ORDER BY nc.created_at DESC, nc.id DESC");
		$comments = $db->fetch_object();

		$this->view->data['active_menu'] = "newscomments";
		$this->view->data['news_comments'] = is_array($comments) ? $comments : array();
		$this->view->data['newscomment_keyword'] = $keyword;
		$this->view->data['newscomment_flash'] = $this->getAdminFlash();
		$this->view->admintmp("newscomments");
	}
	public function products($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		
		if(isset($para) && $para[1] == "new")
		{
			$db->query("SELECT * FROM hicrm_units WHERE unit_status NOT IN (99)");
			$this->view->data["units"] = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_product_categories");
			$this->view->data["product_categories"] = $db->fetch_object();
			$this->view->data['method'] = $para[1];
			$this->view->show("backend/product_action");
		}elseif(isset($para) && $para[1] == "update"){
			$id = $para[2];
			$db->query("SELECT * FROM hicrm_units WHERE unit_status NOT IN (99)");
			$this->view->data["units"] = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_product_categories");
			$this->view->data["product_categories"] = $db->fetch_object();
			$db->query("SELECT *, p.id as pid FROM hicrm_products as p
			LEFT JOIN hicrm_product_categories as c ON p.product_category = c.id
			LEFT JOIN hicrm_units as u ON p.product_unit = u.id WHERE p.id = '".$id."'");
			$this->view->data["product"] = $db->fetch_object(true);
			$this->view->data['method'] = $para[1];
			
			$this->view->show("backend/product_action");
		}
		elseif(isset($para) && $para[1] == "detail")
		{
			
		}
		else
		{
			$db->query("SELECT *, p.id as pid FROM hicrm_products as p
			LEFT JOIN hicrm_product_categories as c ON p.product_category = c.id
			LEFT JOIN hicrm_units as u ON p.product_unit = u.id
			LEFT JOIN hicrm_taxs as t ON p.product_tax_id = t.id
			ORDER BY p.id DESC
			");
			$this->view->data["products"] = $db->fetch_object();
			$this->view->data["active_menu"] = "products";
			$this->view->data["pagetitle"] = "Danh sách sản phẩm";
			$this->view->show("backend/products");
			//Danh sách sản phẩm
		}
	}
	public function service($para){
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		if(isset($para) && $para[2] == "add"){
			$db->query("SELECT * FROM hicrm_categories WHERE id = '".$para[1]."'");
			$nameCategory = $db->fetch_object(true)->category_name;
			$this->view->data['category_name'] = $nameCategory;
			$this->view->data['service_category'] = $para[1];
			$this->view->data['method'] = 'add';
			$this->view->show('backend/service_action');
		}elseif(isset($para) && $para[2] == "edit"){
			$db->query("SELECT * FROM hicrm_service WHERE id = '".$para[3]."'");
			$service_detail = $db->fetch_object(true);
			$db->query("SELECT * FROM hicrm_categories WHERE id = '".$para[1]."'");
			$nameCategory = $db->fetch_object(true)->category_name;
			$this->view->data['category_name'] = $nameCategory;
			$this->view->data['service_detail'] = $service_detail;

			$this->view->data['service_category'] = $para[1];
			$this->view->data['service_id'] = $para[3];
			$this->view->data['method'] = 'edit';
			$this->view->show('backend/service_action');

		}elseif(isset($para) && $para[2] == "detail"){
			$db->query("SELECT * FROM hicrm_service WHERE id = '".$para[3]."'");
			$service_detail = $db->fetch_object(true);
			$this->view->data['service_detail'] = $service_detail;
			$this->view->data['id'] = $para[1];
			$this->view->show('backend/service_detail');
			
		}else{
			$db->query("SELECT * FROM hicrm_categories WHERE id = '".$para[1]."'");
			$nameCategory = $db->fetch_object(true)->category_name;
			$db->query("SELECT *, s.id as sid FROM hicrm_service as s
						LEFT JOIN hicrm_categories as c ON s.service_category = c.id WHERE c.id = '".$para[1]."'AND s.service_status NOT IN(99) ORDER BY s.service_created_date DESC
			");
			$services = $db->fetch_object();
			$this->view->data['category_name'] = $nameCategory;
			$this->view->data['id'] = $para[1];
			$this->view->data['services'] = $services;
			$this->view->show("backend/service");
		}

		
	}
	public function lichcongtac($para){
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		if(isset($para) && $para[1] == "add"){
			$db->query("SELECT * FROM hicrm_calendar_works WHERE id = '".$para[1]."'");
			$calendar_work_name = $db->fetch_object(true)->calendar_work_name;
			$this->view->data['calendar_work_name'] = $calendar_work_name;
			$this->view->data['method'] = 'add';
			$this->view->show('backend/lichcongtac_action');
		}elseif(isset($para) && $para[1] == "edit"){
			$db->query("SELECT *, w.id as wid FROM hicrm_calendar_works as w
						LEFT JOIN hicrm_users as u ON w.calendar_work_user_created = u.id
			 WHERE w.id = '".$para[2]."'");
			$calendar_work_detail = $db->fetch_object(true);
			$this->view->data['calendar_work_detail'] = $calendar_work_detail;
			$this->view->data['id'] = $para[1];
			$this->view->data['method'] = 'edit';
			$this->view->show('backend/lichcongtac_action');
		}else{
			$db->query("SELECT *, w.id as wid FROM hicrm_calendar_works as w
						LEFT JOIN hicrm_users as u ON w.calendar_work_user_created = u.id ORDER BY w.calendar_work_created_date DESC
			 ");
			$calendar_work = $db->fetch_object();
			$this->view->data['calendar_work'] = $calendar_work;
			$this->view->data['id'] = $para[1];
			$this->view->show("backend/lichcongtac");
		}

		
	}
	public function categories($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		// echo $para[1];
		if(isset($para[1]) && $para[1] != "")
		{
			global $db;
			switch($para[1])
			{
				case "users":
				{
					$db->query("SELECT *, u.id as uid FROM hicrm_users as u 
					LEFT JOIN hicrm_status as s ON u.user_status = s.id
					LEFT JOIN hicrm_user_groups as g ON u.user_group = g.id WHERE u.user_status NOT IN(99)
					");
					$this->view->data["users"] = $db->fetch_object();
					$page = "category_users";
					$title = "Quản lý người dùng";
					break;
				}
				case "accounts":
				{
					$db->query("SELECT *, a.id as aid FROM hicrm_accounts as a
					LEFT JOIN hicrm_status as s ON a.account_status = s.id
					WHERE a.account_status NOT IN (99) ORDER BY a.id ASC");
					$accounts = $db->fetch_object();
					$this->view->data['accounts'] = $accounts;
					$page = "category_accounts";
					$title = "Hệ thống tài khoản";
					break;
				}
				
				case "departments":
				{
					$db->query("SELECT * FROM hicrm_departments WHERE depart_status NOT IN(99) ORDER BY id DESC");
					$this->view->data["departments"] = $db->fetch_object();
					$page = "category_departments";
					$active_menu = "category_departments";
					$title = "Danh sách phòng ban";
					break;
				}
				
				case "units":
				{
					$db->query("SELECT *, un.id as unid FROM hicrm_units as un
					LEFT JOIN hicrm_status as st ON un.unit_status = st.id WHERE un.unit_status NOT IN(99) ORDER BY un.id ASC");
					$this->view->data["units"] = $db->fetch_object();
					$page = "category_units";
					$title = "Danh sách đơn vị tính ";
					break;
				}
				
				case "products":
				{
					// echo 'sss';
					$db->query("SELECT *, p.id as pid FROM hicrm_product_categories as p
					LEFT JOIN hicrm_status as st ON p.category_status = st.id
					WHERE p.category_status NOT IN(99) ORDER BY p.id ASC");
					$this->view->data["category_products"] = $db->fetch_object();
					$page = "category_products";
					$title = "Danh sách danh mục loại thuốc";
					$add = "Thêm loại thuốc";
					$this->view->data["pagetitle"] = $title;
					$this->view->data["add"] = $add;

					$this->view->show('backend/categories');
					break;
				}
				case "general":
				{
					global $db;
					if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
					$db->query("SELECT *, c.id as cid FROM hicrm_categories as c
								LEFT JOIN hicrm_category_parent as cp ON c.category_parent = cp.id
								WHERE c.category_status NOT IN(99)");
					$category = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_category_parent");
					$category_parent = $db->fetch_object();
					$this->view->data['general'] = 'Danh mục cha';
					$this->view->data["category"] = $category;
					$this->view->data["category_parent"] = $category_parent;
					$this->view->show("backend/categories");
					break;
				}
				
				default:
				{
					break;
				}
			}
			$this->view->data["active_menu"] = "categories";
			$this->view->data["pagetitle"] = $title;
			// $this->view->data["backend/categories"] = $page;
			$this->view->show('categories');
		}
		else
		{
			header("Location: ".XC_URL."/admin");
		}
	}
	
	public function profile(){
		$model_user = $this->model->get('user');
		$get_user = $model_user -> get_user($_SESSION['user']['id']);
		$this->view->data['user'] = $get_user;
		$this->view->show('profile');
	}
	public function setting(){
		//echo $para[1];
		$type = $_GET['type'];
		$model_user = $this->model->get('user');
		$get_user = $model_user -> get_user($_SESSION['user']['id']);
		$this->view->data['user'] = $get_user;
		$this->view->data['type'] = $type;
		
		$this->view->show('backend/settings');
	}

	public function settings($para = array())
	{
		$this->renderAdminNotice(
			"Cài đặt hệ thống",
			"Trang cài đặt hệ thống chuyên sâu đang được tách riêng. Hiện tại bạn có thể dùng mục Danh mục tham số để cập nhật các cấu hình hiện có.",
			"settings"
		);
	}

	public function change_password($para = array())
	{
		$this->renderAdminNotice(
			"Đổi mật khẩu quản trị",
			"Chức năng đổi mật khẩu đang được khôi phục lại biểu mẫu riêng. Tạm thời menu đã truy cập được bình thường.",
			"users"
		);
	}
	
	public function employees($para = ''){
		
		global $db;
		//Ma NV
		$db->query("SELECT * FROM hicrm_employees ORDER BY id DESC LIMIT 1");
		$lastno2 = $db->fetch_object(true)->employee_code;
		$prefix_employee = $this->helper->get_config("employee_prefix");
		//PREFIX1234567
		$lastno2 = substr($lastno2,-7);
		$lastno2 = $lastno2+1;
		$lastno2 = $prefix_employee."".str_pad($lastno2, 7, '0', STR_PAD_LEFT);
		if(isset($para[1]) && $para[1] == "detail" ){
		$id = $para[2];
		$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
		LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
		LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
		LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
		WHERE e.id = '".$id."'");
		$employee = $db->fetch_object(true);
		
		$this->view->data['employee'] = $employee;
		$this->view->show('employee-detail');
		}else{
			$str = '';
			if(isset($_GET['keyword']) && $_GET['keyword'] != ''){
				$keyword = $_GET['keyword'];
				$str .= " AND e.employee_name LIKE '%".$keyword."%' OR e.employee_code = '".$keyword."' ";
			}
			
			$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
			LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
			LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
			LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
			WHERE e.employee_status = '1' ".$str." ORDER BY e.id ASC");
			$employees = $db->fetch_object();
			//print_r($employees);
			$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
			$branches = $db->fetch_object();
			$db->query('SELECT * FROM hicrm_positions');
			$positions = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_departments");
			$departments = $db->fetch_object();
			$this->view->data["employee_code"] = $lastno2;
			$this->view->data['employees'] = $employees;
			$this->view->data['branches'] = $branches;
			$this->view->data['positions'] = $positions;
			$this->view->data['departments'] = $departments;
			$this->view->show('employees');
		}
	}
	public function editEmployee_($para){
		$id = $para[1];
		global $db;
		$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
		LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
		LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
		LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
		WHERE e.id = '".$id."'");
		$employee = $db->fetch_object(true);
		$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
		$branches = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments");
		$departments = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_departments");
			$departments = $db->fetch_object();
		$this->view->data['employees'] = $employees;
		$this->view->data['branches'] = $branches;
		$this->view->data['positions'] = $positions;
		$this->view->data['employee'] = $employee;
		$this->view->data['departments'] = $departments;
		$this->view->show('edit-employee');
	}
	public function editEmployee($para){
		$id = $para[1];
		global $db;
		$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
		LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
		LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
		LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
		WHERE e.id = '".$id."'");
		$employee = $db->fetch_object(true);
		$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
		$branches = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments");
		$departments = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_departments");
			$departments = $db->fetch_object();
		$this->view->data['employees'] = $employees;
		$this->view->data['branches'] = $branches;
		$this->view->data['positions'] = $positions;
		$this->view->data['employee'] = $employee;
		$this->view->data['departments'] = $departments;
		$this->view->show('backend/edit-employee');
	}
	
	//end account
	private function countorderbydate($date)
	{
		global $db;
		$sqlorder = ($_SESSION['staff']['group'] != 1)? "AND order_staff = '".$_SESSION['staff']['id']."'" : "";
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE date(order_time) = '".date("Y-m-d",strtotime($date))."' ".$sqlorder);
		return $db->fetch_object(true)->countorder;
	}
	private function countorderbydatedeposited($date)
	{
		global $db;
		$sqlorder = ($_SESSION['staff']['group'] != 1)? "AND order_staff = '".$_SESSION['staff']['id']."'" : "";
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE order_status > 1 AND date(order_time) = '".date("Y-m-d",strtotime($date))."' ".$sqlorder);
		return $db->fetch_object(true)->countorder;
	}
	public function config()
    {
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); return; }

		if(isset($_POST['config_id']) && $_POST['config_id'] != "")
		{
			$config_id = $db->escapestring($_POST['config_id']);
			$config_value = isset($_POST['config_value']) ? $db->escapestring($_POST['config_value']) : "";
			$db->query("UPDATE hicrm_configs SET config_value = '".$config_value."' WHERE id = '".$config_id."'");
			header("Location: ".XC_URL."/admin/config?updated=1&id=".$config_id);
			return;
		}

		$db->query("SELECT * FROM hicrm_configs ORDER BY id ASC");
		$this->view->data['active_menu'] = "config";
		$this->view->data['configs'] = $db->fetch_object();
		$this->view->data['selected_config_id'] = isset($_GET['id']) ? $_GET['id'] : "";
		$this->view->data['updated'] = isset($_GET['updated']) ? $_GET['updated'] : "";
		$this->view->admintmp("system-config");
		
    }

	public function googlemeet($para = array())
	{
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		$this->ensureAdminFeatureTables();

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['googlemeet_action'])){
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			if($_POST['googlemeet_action'] === 'save'){
				$title = $db->escapestring(trim(isset($_POST['title']) ? $_POST['title'] : ''));
				$url = $db->escapestring(trim(isset($_POST['meet_url']) ? $_POST['meet_url'] : ''));
				$host = $db->escapestring(trim(isset($_POST['host_name']) ? $_POST['host_name'] : ''));
				$meeting_date = $db->escapestring(trim(isset($_POST['meeting_date']) ? $_POST['meeting_date'] : ''));
				$start_time = $db->escapestring(trim(isset($_POST['start_time']) ? $_POST['start_time'] : ''));
				$end_time = $db->escapestring(trim(isset($_POST['end_time']) ? $_POST['end_time'] : ''));
				$description = $db->escapestring(trim(isset($_POST['description']) ? $_POST['description'] : ''));
				$status = isset($_POST['meet_status']) ? intval($_POST['meet_status']) : 1;
				$sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
				if($id > 0){
					$db->query("UPDATE hicrm_google_meet SET
						title = '".$title."',
						meet_url = '".$url."',
						host_name = '".$host."',
						meeting_date = ".($meeting_date !== '' ? "'".$meeting_date."'" : "NULL").",
						start_time = '".$start_time."',
						end_time = '".$end_time."',
						description = '".$description."',
						meet_status = '".$status."',
						sort_order = '".$sort_order."',
						updated_at = NOW()
						WHERE id = '".$id."' LIMIT 1");
					$this->setAdminFlash('success', 'Đã cập nhật phiên Google Meet.');
				}else{
					$db->query("INSERT INTO hicrm_google_meet(title, meet_url, host_name, meeting_date, start_time, end_time, description, meet_status, sort_order, created_at, updated_at)
						VALUES ('".$title."','".$url."','".$host."',".($meeting_date !== '' ? "'".$meeting_date."'" : "NULL").",'".$start_time."','".$end_time."','".$description."','".$status."','".$sort_order."',NOW(),NOW())");
					$this->setAdminFlash('success', 'Đã thêm phiên Google Meet mới.');
				}
			}elseif($_POST['googlemeet_action'] === 'delete' && $id > 0){
				$db->query("DELETE FROM hicrm_google_meet WHERE id = '".$id."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã xóa phiên Google Meet.');
			}
			$this->adminRedirect('/admin/googlemeet');
		}

		$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
		$edit_item = null;
		if($edit_id > 0){
			$db->query("SELECT * FROM hicrm_google_meet WHERE id = '".$edit_id."' LIMIT 1");
			$edit_item = $db->fetch_object(true);
		}
		$db->query("SELECT * FROM hicrm_google_meet ORDER BY meeting_date DESC, sort_order ASC, id DESC");
		$items = $db->fetch_object();
		$this->view->data['active_menu'] = "googlemeet";
		$this->view->data['googlemeet_items'] = is_array($items) ? $items : array();
		$this->view->data['googlemeet_edit'] = $edit_item;
		$this->view->data['googlemeet_flash'] = $this->getAdminFlash();
		$this->view->admintmp("googlemeet");
	}

	public function videos($para = array())
	{
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		$this->ensureAdminFeatureTables();

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_action'])){
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			if($_POST['video_action'] === 'save'){
				$title = $db->escapestring(trim(isset($_POST['title']) ? $_POST['title'] : ''));
				$video_url = $db->escapestring(trim(isset($_POST['video_url']) ? $_POST['video_url'] : ''));
				$thumbnail_url = $db->escapestring(trim(isset($_POST['thumbnail_url']) ? $_POST['thumbnail_url'] : ''));
				$description = $db->escapestring(trim(isset($_POST['description']) ? $_POST['description'] : ''));
				$status = isset($_POST['video_status']) ? intval($_POST['video_status']) : 1;
				$sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 0;
				if($id > 0){
					$db->query("UPDATE hicrm_videos SET
						title = '".$title."',
						video_url = '".$video_url."',
						thumbnail_url = '".$thumbnail_url."',
						description = '".$description."',
						video_status = '".$status."',
						sort_order = '".$sort_order."',
						updated_at = NOW()
						WHERE id = '".$id."' LIMIT 1");
					$this->setAdminFlash('success', 'Đã cập nhật video.');
				}else{
					$db->query("INSERT INTO hicrm_videos(title, video_url, thumbnail_url, description, video_status, sort_order, created_at, updated_at)
						VALUES ('".$title."','".$video_url."','".$thumbnail_url."','".$description."','".$status."','".$sort_order."',NOW(),NOW())");
					$this->setAdminFlash('success', 'Đã thêm video mới.');
				}
			}elseif($_POST['video_action'] === 'delete' && $id > 0){
				$db->query("DELETE FROM hicrm_videos WHERE id = '".$id."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã xóa video.');
			}
			$this->adminRedirect('/admin/videos');
		}

		$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
		$edit_item = null;
		if($edit_id > 0){
			$db->query("SELECT * FROM hicrm_videos WHERE id = '".$edit_id."' LIMIT 1");
			$edit_item = $db->fetch_object(true);
		}
		$db->query("SELECT * FROM hicrm_videos ORDER BY sort_order ASC, id DESC");
		$items = $db->fetch_object();
		$this->view->data['active_menu'] = "videos";
		$this->view->data['videos'] = is_array($items) ? $items : array();
		$this->view->data['video_edit'] = $edit_item;
		$this->view->data['video_flash'] = $this->getAdminFlash();
		$this->view->admintmp("videos");
	}
	
	public function agency($para)
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		if(isset($para[1]) && $para[1] != "")
		{
			$db->query("SELECT *, (SELECT count(id) FROM ow_orders WHERE uid = u.id) as countpost, (SELECT sum(order_total) FROM ow_orders WHERE uid = u.id) as sumorder, (SELECT count(id) FROM ow_users WHERE user_referal = u.id) as countreferal,u.id as uid FROM ow_users as u
			LEFT JOIN ow_agency as a ON u.id = a.uid
			LEFT JOIN ow_agency_level as al ON a.agent_level = al.id
			LEFT JOIN ow_customer_address as ud ON u.id = ud.uid
			LEFT JOIN ow_wards as w ON ud.wardid = w.id
			LEFT JOIN ow_districts as d ON ud.districtid = d.id
			LEFT JOIN ow_provinces as p ON ud.provinceid = p.id
			WHERE u.id = '".$para[1]."'
			ORDER BY user_created_time DESC");
			if($db->num_row())
			{
				$this->view->data["user"] = $user = $db->fetch_object(true);
				$spp = 10;
				$page = 1;
				if(isset($_GET['page']) && $_GET['page'] != "")
				{
					$page = $_GET['page'];
				}
				$cp = $page - 1;
				$db->query("SELECT * FROM ow_orders WHERE uid = '".$user->uid."'");
				$totalsms = $db->num_row();
				$totalpage = $totalsms/$spp;
				$sql = "SELECT *, o.id as oid,staff.user_fullname as staff_fullname FROM ow_orders as o
				LEFT JOIN ow_users as u ON o.uid = u.id
				LEFT JOIN ow_order_status as ot ON o.order_status = ot.id
				LEFT JOIN hicrm_users as staff ON o.order_staff = staff.id
				WHERE o.uid = '".$user->uid."'
				ORDER BY o.order_time DESC LIMIT ".$cp*$spp.",".$spp;
				$db->query($sql);
				
				$this->view->data["orders"] = $db->fetch_object();
				$this->view->data['totalpost'] = $totalsms;
				$this->view->data["page"] = $page;
				$this->view->data["spp"] = $spp;
				$this->view->data['totalpage'] = $totalpage;
				$db->query("SELECT *, (SELECT count(id) FROM ow_orders WHERE uid = u.id) as countreforder FROM ow_users as u 
				WHERE user_referal = '".$user->uid."'");
				$this->view->data["refusers"] = $db->fetch_object();
				$this->view->show("agency_detail");
			}
			else
			{
				header("Location: ".XC_URL."/admin/agency");
			}
		}
		else
		{
			$db->query("SELECT *, (SELECT count(id) FROM ow_orders WHERE uid = u.id) as countpost FROM ow_users as u
			LEFT JOIN ow_agency as a ON u.id = a.uid
			LEFT JOIN ow_agency_level as al ON a.agent_level = al.id
			WHERE u.user_is_agency = 1 OR u.user_is_agency = 2
			ORDER BY user_created_time DESC");
			$this->view->data["users"] = $db->fetch_object();
			$this->view->show("agency");
		}
		
	}
	public function transactions()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, t.id as tid FROM ow_transactions as t
		LEFT JOIN ow_users as u ON t.uid = u.id
		ORDER BY trans_time DESC");
		$this->view->data["transactions"] = $db->fetch_object();
		$db->query("SELECT * FROM ow_users ORDER BY id DESC");
		$this->view->data["users"] = $db->fetch_object();
		$this->view->show("transactions");
	}
	public function deposites()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, t.id as tid FROM ow_transactions as t
		LEFT JOIN ow_users as u ON t.uid = u.id
		WHERE t.trans_type = 1
		ORDER BY trans_time DESC");
		$this->view->data["transactions"] = $db->fetch_object();
		$db->query("SELECT * FROM ow_users ORDER BY id DESC");
		$this->view->data["users"] = $db->fetch_object();
		$this->view->data["page"] = "deposite";
		$this->view->data["title"] = "Danh sách giao dịch nạp tiền";
		$this->view->show("transactions");
	}
	public function withdrawal()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, t.id as tid FROM ow_transactions as t
		LEFT JOIN ow_users as u ON t.uid = u.id
		WHERE t.trans_type = 3
		ORDER BY trans_time DESC");
		$this->view->data["transactions"] = $db->fetch_object();
		$this->view->data["page"] = "withdrawal";
		$this->view->data["title"] = "Danh sách giao dịch rút tiền";
		$this->view->show("transactions");
	}
	public function orders()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$query = ($_GET['keyword'] != "")? " AND (u.user_firstname LIKE '%".$_GET['keyword']."%' OR o.order_code = '".$_GET['keyword']."')" : "";
		$spp = 40;
		$page = 1;
		if(isset($_GET['page']) && $_GET['page'] != "")
		{
			$page = $_GET['page'];
		}
		$cp = $page - 1;
		$db->query("SELECT * FROM ow_orders");
		
		$totalsms = $db->num_row();
		$totalpage = $totalsms/$spp;
		if($_SESSION['staff']['group'] == 1)
		{
			$db->query("SELECT *, o.id as oid,staff.user_fullname as staff_fullname FROM ow_orders as o
			LEFT JOIN ow_users as u ON o.uid = u.id
			LEFT JOIN ow_order_status as ot ON o.order_status = ot.id
			LEFT JOIN hicrm_users as staff ON o.order_staff = staff.id
			WHERE o.order_status > 0 ".$query."
			ORDER BY o.order_time DESC LIMIT ".$cp*$spp.",".$spp);
		}
		else
		{
			$db->query("SELECT *, o.id as oid,staff.user_fullname as staff_fullname FROM ow_orders as o
			LEFT JOIN ow_users as u ON o.uid = u.id
			LEFT JOIN ow_order_status as ot ON o.order_status = ot.id
			LEFT JOIN hicrm_users as staff ON o.order_staff = staff.id
			WHERE order_status >0 AND order_staff = '".$_SESSION['staff']['id']."'".$query."
			ORDER BY o.order_time DESC LIMIT ".$cp*$spp.",".$spp);
		}
		$this->view->data["orders"] = $db->fetch_object();
		$this->view->data['totalpost'] = $totalsms;
		$this->view->data["page"] = $page;
		$this->view->data["spp"] = $spp;
		$this->view->data['totalpage'] = $totalpage;
		$this->view->data["page_name"] = "sms_list";
		$this->view->data["page_title"] = "Danh sách SMS";
		$this->view->show("orders");
	}
	public function places()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, p.id as placeid, (SELECT COUNT(*) FROM bds_posts WHERE post_district = p.place_district) as countpost FROM bds_places as p
		LEFT JOIN hicrm_districts as d ON p.place_district = d.id
		LEFT JOIN hicrm_provinces as pr ON p.place_province = pr.id
		");
		$this->view->data["places"] = $db->fetch_object();
		$this->view->show("places");
	}
	public function departments()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM hicrm_departments ORDER BY id DESC
		");
		$this->view->data["departments"] = $db->fetch_object();
		$this->view->show("departments");
	}
	public function feedback()
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM hicrm_customer_feedback ORDER BY create_date DESC
		");
		$this->view->data["feedback"] = $db->fetch_object();
		$this->view->show("backend/feedback");
	}
	public function fees()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM ow_fixed_fees ORDER BY fee_min ASC");
		$this->view->data["fees"] = $db->fetch_object();
		$this->view->data["page_name"] = "fees";
		$this->view->show("fees");
	}
	public function projects()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM bds_projects ORDER BY id DESC
		");
		$this->view->data["projects"] = $db->fetch_object();
		$this->view->show("project-manager");
	}
	public function provinces()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, (SELECT COUNT(*) FROM bds_posts WHERE post_province = p.id) as countpost FROM hicrm_provinces as p ORDER BY id ASC");
		$this->view->data["provinces"] = $db->fetch_object();
		$this->view->show("province_manager");
	}
	public function menu()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM bds_menus ORDER BY menu_order ASC");
		$this->view->data["menus"] = $db->fetch_object();
		$this->view->show("menu_manager");
	}
	// public function categories()
	// {
	// 	if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
	// 	global $db;
	// 	$db->query("SELECT *, (SELECT COUNT(*) FROM bds_posts WHERE post_category = c.id) as countpost FROM bds_categories as c ORDER BY id ASC");
	// 	$this->view->data["categories"] = $db->fetch_object();
	// 	$this->view->show("categories_manager");
	// }
	public function news_1()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		
		$db->query("SELECT *, p.id as pid FROM bds_news as p 
		LEFT JOIN hicrm_users as u ON p.news_author = u.id
		LEFT JOIN bds_news_categories as c ON p.news_category = c.id
		ORDER BY p.news_date DESC");
		$this->view->data["posts"] = $db->fetch_object();
		$db->query("SELECT * FROM bds_news_categories");
		$this->view->data["news_cat"] = $db->fetch_object();
		$this->view->data["page_name"] = "news_all";
		$this->view->data["page_title"] = "Danh sách sự kiện";
		$this->view->data["news"] = $db->fetch_object();
		$this->view->show("news_manager");
	}
	public function pages()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		
		$db->query("SELECT *, p.id as pid FROM bds_pages as p 
		LEFT JOIN hicrm_users as u ON p.page_author = u.id
		ORDER BY p.page_date DESC");
		$this->view->data["posts"] = $db->fetch_object();
		$this->view->data["page_name"] = "pages_all";
		$this->view->data["page_title"] = "Danh sách sự kiện";
		$this->view->data["news"] = $db->fetch_object();
		$this->view->show("page_manager");
	}
	public function category()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT * FROM bds_categories ORDER BY id ASC");
		$this->view->data["categories"] = $db->fetch_object();
		$this->view->show("category_manager");
	}
	
	public function postsss($para)
	{
		switch($para[1])
		{
			case "new":
			{
				$this->view->show("post-add");
				break;
			}
			default:
				break;
		}
		
	}

	public function employerposts($para = array())
	{
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		if(!$this->adminTableExists('hicrm_job_posts')){
			$this->renderAdminNotice(
				"Quản lý bài đăng tuyển dụng",
				"Không tìm thấy bảng hicrm_job_posts trong cơ sở dữ liệu hiện tại.",
				"post_employers"
			);
			return;
		}

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employer_post_action'])){
			$action = $_POST['employer_post_action'];
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			$has_published_at = $this->adminColumnExists('hicrm_job_posts', 'published_at');

			if($action === 'save'){
				$title = $db->escapestring(trim(isset($_POST['title']) ? $_POST['title'] : ''));
				$job_description = $db->escapestring(trim(isset($_POST['job_description']) ? $_POST['job_description'] : ''));
				$deadline = $db->escapestring(trim(isset($_POST['deadline']) ? $_POST['deadline'] : ''));
				$status = $this->normalizeJobPostStatus(isset($_POST['status']) ? trim($_POST['status']) : 'pending');
				$employer_id = isset($_POST['employer_id']) ? intval($_POST['employer_id']) : 0;
				$job_category_id = isset($_POST['job_category_id']) && $_POST['job_category_id'] !== '' ? intval($_POST['job_category_id']) : 'NULL';
				$province_id = isset($_POST['province_id']) && $_POST['province_id'] !== '' ? intval($_POST['province_id']) : 'NULL';
				$salary_id = isset($_POST['salary_id']) && $_POST['salary_id'] !== '' ? intval($_POST['salary_id']) : 'NULL';
				$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
				$experience_years = isset($_POST['experience_years']) ? intval($_POST['experience_years']) : 0;
				$degree_required = $db->escapestring(trim(isset($_POST['degree_required']) ? $_POST['degree_required'] : ''));
				$work_type = $db->escapestring(trim(isset($_POST['work_type']) ? $_POST['work_type'] : ''));
				$address_detail = $db->escapestring(trim(isset($_POST['address_detail']) ? $_POST['address_detail'] : ''));
				$benefits = $db->escapestring(trim(isset($_POST['benefits_description']) ? $_POST['benefits_description'] : ''));
				if($id > 0){
					$db->query("UPDATE hicrm_job_posts SET
						employer_id = '".$employer_id."',
						job_category_id = ".($job_category_id === 'NULL' ? "NULL" : "'".$job_category_id."'").",
						province_id = ".($province_id === 'NULL' ? "NULL" : "'".$province_id."'").",
						title = '".$title."',
						quantity = '".$quantity."',
						job_description = '".$job_description."',
						experience_years = '".$experience_years."',
						degree_required = '".$degree_required."',
						salary_id = ".($salary_id === 'NULL' ? "NULL" : "'".$salary_id."'").",
						benefits_description = '".$benefits."',
						work_type = '".$work_type."',
						address_detail = '".$address_detail."',
						deadline = '".$deadline."',
						status = '".$status."'".
						($has_published_at ? ($status === 'published' ? ",
						published_at = COALESCE(published_at, NOW())" : "") : "").",
						updated_at = NOW()
						WHERE id = '".$id."' LIMIT 1");
					$this->setAdminFlash('success', 'Đã cập nhật bài đăng tuyển dụng.');
				}else{
					$insert_columns = "employer_id, job_category_id, province_id, title, quantity, job_description, experience_years, degree_required, salary_id, benefits_description, work_type, address_detail, deadline, status";
					$insert_values = "'".$employer_id."',".($job_category_id === 'NULL' ? "NULL" : "'".$job_category_id."'").",".($province_id === 'NULL' ? "NULL" : "'".$province_id."'").",'".$title."','".$quantity."','".$job_description."','".$experience_years."','".$degree_required."',".($salary_id === 'NULL' ? "NULL" : "'".$salary_id."'").",'".$benefits."','".$work_type."','".$address_detail."','".$deadline."','".$status."'";
					if($has_published_at){
						$insert_columns .= ", published_at";
						$insert_values .= ",".($status === 'published' ? "NOW()" : "NULL");
					}
					$insert_columns .= ", created_at, updated_at";
					$insert_values .= ", NOW(), NOW()";
					$db->query("INSERT INTO hicrm_job_posts(".$insert_columns.") VALUES (".$insert_values.")");
					$this->setAdminFlash('success', 'Đã thêm bài đăng tuyển dụng.');
				}
			}elseif($action === 'delete' && $id > 0){
				if($this->adminColumnExists('hicrm_job_posts', 'status')){
					$db->query("UPDATE hicrm_job_posts SET status = 99, updated_at = NOW() WHERE id = '".$id."' LIMIT 1");
				}else{
					$db->query("DELETE FROM hicrm_job_posts WHERE id = '".$id."' LIMIT 1");
				}
				$this->setAdminFlash('success', 'Đã xóa bài đăng tuyển dụng.');
			}elseif($action === 'approve' && $id > 0){
				$approve_fields = array(
					"status = 'published'",
					"updated_at = NOW()"
				);
				if($has_published_at){
					$approve_fields[] = "published_at = NOW()";
				}
				$db->query("UPDATE hicrm_job_posts SET ".implode(', ', $approve_fields)." WHERE id = '".$id."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã duyệt và xuất bản bài đăng tuyển dụng.');
			}elseif($action === 'approve_selected'){
				$selected_ids = isset($_POST['selected_ids']) && is_array($_POST['selected_ids']) ? $_POST['selected_ids'] : array();
				$selected_ids = array_values(array_filter(array_map('intval', $selected_ids), function($value){
					return $value > 0;
				}));
				if(!empty($selected_ids)){
					$approve_fields = array(
						"status = 'published'",
						"updated_at = NOW()"
					);
					if($has_published_at){
						$approve_fields[] = "published_at = NOW()";
					}
					$db->query("UPDATE hicrm_job_posts SET ".implode(', ', $approve_fields)." WHERE id IN (".implode(',', $selected_ids).")");
					$this->setAdminFlash('success', 'Đã duyệt và xuất bản '.count($selected_ids).' bài đăng tuyển dụng.');
				}else{
					$this->setAdminFlash('info', 'Vui lòng chọn ít nhất một bài đăng để duyệt.');
				}
			}elseif($action === 'import' && isset($_FILES['import_file']['tmp_name']) && is_uploaded_file($_FILES['import_file']['tmp_name'])){
				$handle = fopen($_FILES['import_file']['tmp_name'], 'r');
				$imported = 0;
				if($handle){
					$header = fgetcsv($handle, 0, ',');
					if(is_array($header)){
						$header = array_map('trim', $header);
						while(($row = fgetcsv($handle, 0, ',')) !== false){
							$item = array();
							foreach($header as $idx => $key){
								$item[$key] = isset($row[$idx]) ? trim($row[$idx]) : '';
							}
							$title = isset($item['title']) ? $db->escapestring($item['title']) : '';
							if($title === ''){ continue; }
							$employer_id = isset($item['employer_id']) ? intval($item['employer_id']) : 0;
							$job_category_id = (isset($item['job_category_id']) && $item['job_category_id'] !== '') ? intval($item['job_category_id']) : "NULL";
							$province_id = (isset($item['province_id']) && $item['province_id'] !== '') ? intval($item['province_id']) : "NULL";
							$salary_id = (isset($item['salary_id']) && $item['salary_id'] !== '') ? intval($item['salary_id']) : "NULL";
							$quantity = isset($item['quantity']) ? intval($item['quantity']) : 1;
							$description = isset($item['job_description']) ? $db->escapestring($item['job_description']) : '';
							$experience = isset($item['experience_years']) ? intval($item['experience_years']) : 0;
							$degree = isset($item['degree_required']) ? $db->escapestring($item['degree_required']) : '';
							$work_type = isset($item['work_type']) ? $db->escapestring($item['work_type']) : '';
							$address = isset($item['address_detail']) ? $db->escapestring($item['address_detail']) : '';
							$benefits = isset($item['benefits_description']) ? $db->escapestring($item['benefits_description']) : '';
							$deadline = isset($item['deadline']) && $item['deadline'] !== '' ? $db->escapestring($item['deadline']) : date('Y-m-d', strtotime('+30 days'));
							$status = $this->normalizeJobPostStatus(isset($item['status']) ? $item['status'] : 'pending');
							$insert_columns = "employer_id, job_category_id, province_id, title, quantity, job_description, experience_years, degree_required, salary_id, benefits_description, work_type, address_detail, deadline, status";
							$insert_values = "'".$employer_id."',".($job_category_id === "NULL" ? "NULL" : "'".$job_category_id."'").",".($province_id === "NULL" ? "NULL" : "'".$province_id."'").",'".$title."','".$quantity."','".$description."','".$experience."','".$degree."',".($salary_id === "NULL" ? "NULL" : "'".$salary_id."'").",'".$benefits."','".$work_type."','".$address."','".$deadline."','".$status."'";
							if($has_published_at){
								$insert_columns .= ", published_at";
								$insert_values .= ",".($status === 'published' ? "NOW()" : "NULL");
							}
							$insert_columns .= ", created_at, updated_at";
							$insert_values .= ", NOW(), NOW()";
							$db->query("INSERT INTO hicrm_job_posts(".$insert_columns.") VALUES (".$insert_values.")");
							$imported++;
						}
					}
					fclose($handle);
				}
				$this->setAdminFlash('success', 'Đã import '.$imported.' bài đăng tuyển dụng từ file CSV.');
			}
			$this->adminRedirect('/admin/employers/posts');
		}

		$where = array("1=1");
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$page = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
		$per_page = 10;
		if($keyword !== ''){
			$kw = $db->escapestring($keyword);
			$where[] = "(p.title LIKE '%".$kw."%' OR e.company_name LIKE '%".$kw."%' OR c.job_category_name LIKE '%".$kw."%')";
		}
		if($this->adminColumnExists('hicrm_job_posts', 'status')){
			$where[] = "IFNULL(p.status,'pending') <> '99'";
		}

		$base_sql = "FROM hicrm_job_posts p
			LEFT JOIN hicrm_employers e ON p.employer_id = e.id
			LEFT JOIN hicrm_job_categories c ON p.job_category_id = c.id
			LEFT JOIN hicrm_provinces pr ON p.province_id = pr.id
			LEFT JOIN hicrm_salary s ON p.salary_id = s.id
			WHERE ".implode(' AND ', $where);

		$db->query("SELECT COUNT(p.id) AS total ".$base_sql);
		$total_posts = (int)$db->fetch_object(true)->total;
		$total_pages = max(1, ceil($total_posts / $per_page));
		if($page > $total_pages){
			$page = $total_pages;
		}
		$offset = ($page - 1) * $per_page;

		$db->query("SELECT p.*,
			e.company_name,
			c.job_category_name,
			pr.province_name,
			s.salary_name
			".$base_sql."
			ORDER BY p.created_at DESC, p.id DESC
			LIMIT ".$offset.",".$per_page);
		$posts = $db->fetch_object();

		$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
		$detail_id = isset($_GET['detail']) ? intval($_GET['detail']) : 0;
		$edit_item = null;
		$detail_item = null;
		if($edit_id > 0){
			$db->query("SELECT * FROM hicrm_job_posts WHERE id = '".$edit_id."' LIMIT 1");
			$edit_item = $db->fetch_object(true);
		}
		if($detail_id > 0){
			$db->query("SELECT p.*, e.company_name, c.job_category_name, pr.province_name, s.salary_name
				FROM hicrm_job_posts p
				LEFT JOIN hicrm_employers e ON p.employer_id = e.id
				LEFT JOIN hicrm_job_categories c ON p.job_category_id = c.id
				LEFT JOIN hicrm_provinces pr ON p.province_id = pr.id
				LEFT JOIN hicrm_salary s ON p.salary_id = s.id
				WHERE p.id = '".$detail_id."' LIMIT 1");
			$detail_item = $db->fetch_object(true);
		}

		$this->view->data['active_menu'] = "post_employers";
		$this->view->data['employer_posts'] = is_array($posts) ? $posts : array();
		$this->view->data['employer_post_edit'] = $edit_item;
		$this->view->data['employer_post_detail'] = $detail_item;
		$this->view->data['employer_post_flash'] = $this->getAdminFlash();
		$this->view->data['employer_post_keyword'] = $keyword;
		$this->view->data['employer_options'] = $this->getEmployerOptions();
		$this->view->data['job_category_options'] = $this->getAdminReferenceOptions('hicrm_job_categories', 'job_category_name');
		$this->view->data['province_options'] = $this->getAdminReferenceOptions('hicrm_provinces', 'province_name');
		$this->view->data['salary_options'] = $this->getAdminReferenceOptions('hicrm_salary', 'salary_name');
		$this->view->data['page'] = $page;
		$this->view->data['per_page'] = $per_page;
		$this->view->data['total_posts'] = $total_posts;
		$this->view->data['total_pages'] = $total_pages;
		$this->view->admintmp("employer-posts");
	}
	
	public function upload()
	{
		include('./class.uploader.php');
		$uploader = new Uploader();
		$data = $uploader->upload($_FILES['files'], array(
			'limit' => 10, //Maximum Limit of files. {null, Number}
			'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
			'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
			'required' => false, //Minimum one file is required for upload {Boolean}
			'uploadDir' => './uploads/images/tour/', //Upload directory {String}
			'title' => array('name'), //New file name {null, String, Array} *please read documentation in README.md
			'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
			'perms' => null, //Uploaded file permisions {null, Number}
			'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
			'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
			'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
			'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
			'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
			'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
		));
		
		if($data['isComplete']){
			$files = $data['data'];
			print_r($files);
			global $db;
			$db->query("INSERT INTO sgt_tour_images(tourid,image_path,thumb_path,images_type) VALUES('".$_SESSION['tourid']."','".$files['metas'][0]['name']."','".$files['metas'][0]['name']."','1')");
		}

		if($data['hasErrors']){
			$errors = $data['errors'];
			print_r($errors);
		}
		
		function onFilesRemoveCallback($removed_files){
			foreach($removed_files as $key=>$value){
				$file = '../uploads/' . $value;
				if(file_exists($file)){
					unlink($file);
				}
			}
			
			return $removed_files;
		}
	}
}
