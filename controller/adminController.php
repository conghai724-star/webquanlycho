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
			array('key' => 'customer_feedbacks', 'name' => 'Quản lý phản hồi khách hàng', 'parent' => '', 'sort' => 54),
			array('key' => 'job_support_customers', 'name' => 'Quản lý khách hàng hỗ trợ tìm việc', 'parent' => '', 'sort' => 55),
			array('key' => 'market_results', 'name' => 'Quản lý kết quả sàn', 'parent' => '', 'sort' => 56),
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
		$active_keys = array();
		foreach($this->getAdminMenuDefinitions() as $menu){
			$key = $db->escapestring($menu['key']);
			$active_keys[] = "'".$key."'";
			$db->query("INSERT INTO hicrm_admin_menu_permissions(permission_key, permission_name, parent_key, sort_order, permission_status)
				VALUES ('".$key."','".$db->escapestring($menu['name'])."','".$db->escapestring($menu['parent'])."','".intval($menu['sort'])."','1')
				ON DUPLICATE KEY UPDATE permission_name = VALUES(permission_name), parent_key = VALUES(parent_key), sort_order = VALUES(sort_order), permission_status = 1");
		}
		if(!empty($active_keys)){
			$db->query("UPDATE hicrm_admin_menu_permissions SET permission_status = 0 WHERE permission_key NOT IN (".implode(',', $active_keys).")");
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
			INNER JOIN hicrm_user_groups AS g ON g.id = u.user_group AND g.group_status NOT IN(99)
			WHERE u.id = '".intval($_SESSION['user']['id'])."' AND u.user_status = 1 LIMIT 1");
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
			WHERE gp.group_id = '".$group_id."' AND p.permission_status = 1");
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
		if(!isset($_SESSION['admin_csrf_token']) || !is_string($_SESSION['admin_csrf_token']) || $_SESSION['admin_csrf_token'] === ''){
			try {
				$_SESSION['admin_csrf_token'] = bin2hex(random_bytes(32));
			} catch(Exception $e) {
				$_SESSION['admin_csrf_token'] = md5(uniqid((string)mt_rand(), true));
			}
		}
		$this->view->data['admin_csrf_token'] = $_SESSION['admin_csrf_token'];
		if(!$this->adminHasMenuPermission($allowed_keys, $permission_key)){
			$this->view->data['page_title'] = "Bạn không có quyền truy cập trang này";
			$this->view->data['page_description'] = "Tài khoản hiện tại chưa được cấp quyền cho chức năng quản trị này.";
			http_response_code(403);
			$this->view->admintmp('403');
			return false;
		}
		return true;
	}

    public function index()
    {
		if(!$this->prepareAdminAccess('')){ return; }
		$allowed = $this->view->data['allowed_admin_menu'];
		if($this->adminHasMenuPermission($allowed, 'dashboard')){
			$this->view->data['dashboard_stats'] = $this->getAdminDashboardStats();
			$this->view->data['active_menu'] = 'dashboard';
			$this->view->admintmp('index');
			return;
		}
		$routes = array('employers'=>'/admin/employers','employer_posts'=>'/admin/employers/posts','candidates'=>'/admin/candidates','students'=>'/admin/students','events'=>'/admin/events','news_comments'=>'/admin/newscomments','customer_feedbacks'=>'/admin/customerfeedbacks','job_support_customers'=>'/admin/jobsupportcustomers','market_results'=>'/admin/marketresults','google_meet'=>'/admin/googlemeet','users'=>'/admin/users','groups'=>'/admin/groups','images'=>'/admin/images','videos'=>'/admin/videos','config'=>'/admin/config','settings'=>'/admin/settings');
		foreach($routes as $key => $route){
			if($this->adminHasMenuPermission($allowed, $key)){ header('Location: '.XC_URL.$route); return; }
		}
		http_response_code(403);
		$this->view->data['page_title'] = 'Tài khoản chưa được cấp quyền';
		$this->view->data['page_description'] = 'Vui lòng liên hệ Super Admin để được cấp ít nhất một quyền quản trị.';
		$this->view->admintmp('403');
    }
	public function login()
	{ 
		
		$this->view->admintmp("login");
	}
	public function employyer($para = ''){
		global $db;
		if(!$this->prepareAdminAccess('employers')){ return; }
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

	private function getAdminDashboardStats()
	{
		global $db;

		$stats = array(
			'job_posts' => 0,
			'candidates' => 0,
			'employers' => 0,
			'linked_employers' => 0,
			'unlinked_employers' => 0,
			'published_news' => 0,
			'pending_job_posts' => 0,
			'pending_candidates' => 0,
			'students' => 0
		);

		if($this->adminTableExists('hicrm_job_posts')){
			$where = $this->adminColumnExists('hicrm_job_posts', 'status')
				? " WHERE IFNULL(status, 'pending') <> '99'"
				: "";
			$db->query("SELECT COUNT(id) AS total FROM hicrm_job_posts".$where);
			$row = $db->fetch_object(true);
			$stats['job_posts'] = $row ? (int)$row->total : 0;

			if($this->adminColumnExists('hicrm_job_posts', 'status')){
				$db->query("SELECT COUNT(id) AS total FROM hicrm_job_posts WHERE IFNULL(status, 'pending') = 'pending'");
				$row = $db->fetch_object(true);
				$stats['pending_job_posts'] = $row ? (int)$row->total : 0;
			}
		}

		if($this->adminTableExists('hicrm_candidates')){
			$where = $this->adminColumnExists('hicrm_candidates', 'status')
				? " WHERE IFNULL(status, 1) <> 99"
				: "";
			$db->query("SELECT COUNT(id) AS total FROM hicrm_candidates".$where);
			$row = $db->fetch_object(true);
			$stats['candidates'] = $row ? (int)$row->total : 0;

			if($this->adminColumnExists('hicrm_candidates', 'status')){
				$db->query("SELECT COUNT(id) AS total FROM hicrm_candidates WHERE IFNULL(status, 1) = 1");
				$row = $db->fetch_object(true);
				$stats['pending_candidates'] = $row ? (int)$row->total : 0;
			}
		}

		if($this->adminTableExists('hicrm_employers')){
			$db->query("SELECT COUNT(id) AS total FROM hicrm_employers");
			$row = $db->fetch_object(true);
			$stats['employers'] = $row ? (int)$row->total : 0;

			if($this->adminColumnExists('hicrm_employers', 'is_linked_school')){
				$db->query("SELECT COUNT(id) AS total FROM hicrm_employers WHERE is_linked_school = 1");
				$row = $db->fetch_object(true);
				$stats['linked_employers'] = $row ? (int)$row->total : 0;

				$db->query("SELECT COUNT(id) AS total FROM hicrm_employers WHERE IFNULL(is_linked_school, 0) = 0");
				$row = $db->fetch_object(true);
				$stats['unlinked_employers'] = $row ? (int)$row->total : 0;
			}
		}

		if($this->adminTableExists('hicrm_events')){
			if($this->adminColumnExists('hicrm_events', 'event_status')){
				$db->query("SELECT COUNT(id) AS total FROM hicrm_events WHERE event_status = 1");
			}else{
				$db->query("SELECT COUNT(id) AS total FROM hicrm_events");
			}
			$row = $db->fetch_object(true);
			$stats['published_news'] = $row ? (int)$row->total : 0;
		}

		if($this->adminTableExists('hicrm_students')){
			$where = $this->adminColumnExists('hicrm_students', 'status')
				? " WHERE IFNULL(status, 1) <> 99"
				: "";
			$db->query("SELECT COUNT(id) AS total FROM hicrm_students".$where);
			$row = $db->fetch_object(true);
			$stats['students'] = $row ? (int)$row->total : 0;
		}

		return $stats;
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

		$db->query("CREATE TABLE IF NOT EXISTS hicrm_customer_feedback (
			id int(11) NOT NULL AUTO_INCREMENT,
			customer_name varchar(255) NOT NULL,
			customer_phone varchar(255) NOT NULL,
			customer_email varchar(255) NOT NULL,
			customer_address varchar(255) NOT NULL,
			content text NOT NULL,
			status int(11) NOT NULL DEFAULT 0,
			rating int(11) DEFAULT 0,
			create_date datetime NOT NULL,
			PRIMARY KEY (id),
			KEY idx_status (status),
			KEY idx_create_date (create_date)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

		$db->query("CREATE TABLE IF NOT EXISTS hicrm_google_meets (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			meeting_time datetime DEFAULT NULL,
			employer_id bigint(20) unsigned DEFAULT NULL,
			job_post_id bigint(20) unsigned DEFAULT NULL,
			candidate_emails text DEFAULT NULL,
			meet_url varchar(500) DEFAULT NULL,
			status tinyint(4) NOT NULL DEFAULT 1,
			created_by bigint(20) unsigned DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY idx_meeting_time (meeting_time),
			KEY idx_employer_id (employer_id),
			KEY idx_job_post_id (job_post_id),
			KEY idx_status (status)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

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
		$map = $this->getAdminStatusMap();
		return isset($map[(int)$status]) ? $map[(int)$status] : array('label' => 'Không xác định', 'class' => 'secondary');
	}

	private function normalizeAdminStatusKey($value)
	{
		$value = trim((string)$value);
		if($value === ''){
			return '';
		}
		if(function_exists('iconv')){
			$converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
			if($converted !== false){
				$value = $converted;
			}
		}
		$value = strtolower($value);
		$value = preg_replace('/[^a-z0-9]+/', ' ', $value);
		return trim(preg_replace('/\s+/', ' ', $value));
	}

	private function getAdminStatusMap($status_type = null)
	{
		global $db;
		$default_map = array(
			2 => array('label' => 'Chờ duyệt', 'class' => 'warning'),
			3 => array('label' => 'Phê duyệt', 'class' => 'success'),
			98 => array('label' => 'Từ chối', 'class' => 'danger'),
			99 => array('label' => 'Đã xóa', 'class' => 'secondary')
		);
		if(!$this->adminTableExists('hicrm_status') || !$this->adminColumnExists('hicrm_status', 'status_label')){
			return $default_map;
		}

		$where = '';
		if($status_type !== null && $this->adminColumnExists('hicrm_status', 'status_type')){
			$where = " WHERE status_type = '".intval($status_type)."' OR status_type = '0'";
		}

		$class_select = $this->adminColumnExists('hicrm_status', 'status_class')
			? "status_class"
			: "'' AS status_class";
		$db->query("SELECT id, status_label, ".$class_select." FROM hicrm_status".$where." ORDER BY id ASC");
		$rows = $db->fetch_object();
		if(!is_array($rows) || empty($rows)){
			return $default_map;
		}

		$map = array();
		foreach($rows as $row){
			$status_id = isset($row->id) ? (int)$row->id : 0;
			if($status_id <= 0){
				continue;
			}
			$status_class = isset($row->status_class) ? trim((string)$row->status_class) : '';
			if($status_class === ''){
				$status_class = isset($default_map[$status_id]['class']) ? $default_map[$status_id]['class'] : 'secondary';
			}
			$map[$status_id] = array(
				'label' => isset($row->status_label) ? $row->status_label : (isset($default_map[$status_id]['label']) ? $default_map[$status_id]['label'] : 'Không xác định'),
				'class' => $status_class
			);
		}

		foreach($default_map as $status_id => $status_info){
			if(!isset($map[$status_id])){
				$map[$status_id] = $status_info;
			}
		}

		return $map;
	}

	private function getAdminStatusIdByAliases($aliases = array(), $fallback = 0, $status_type = null)
	{
		$status_map = $this->getAdminStatusMap($status_type);
		if(!is_array($aliases)){
			$aliases = array($aliases);
		}
		$normalized_aliases = array();
		foreach($aliases as $alias){
			$key = $this->normalizeAdminStatusKey($alias);
			if($key !== ''){
				$normalized_aliases[] = $key;
			}
		}
		foreach($status_map as $status_id => $status_info){
			$status_key = $this->normalizeAdminStatusKey(isset($status_info['label']) ? $status_info['label'] : '');
			if($status_key !== '' && in_array($status_key, $normalized_aliases, true)){
				return (int)$status_id;
			}
		}
		return (int)$fallback;
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

	private function escapeSpreadsheetXml($value)
	{
		return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
	}

	private function buildSpreadsheetXmlRow($cells = array())
	{
		$row = '<Row>';
		foreach($cells as $cell){
			$type = isset($cell['type']) ? $cell['type'] : 'String';
			$value = isset($cell['value']) ? $cell['value'] : '';
			$row .= '<Cell><Data ss:Type="'.$this->escapeSpreadsheetXml($type).'">'.$this->escapeSpreadsheetXml($value).'</Data></Cell>';
		}
		$row .= '</Row>';
		return $row;
	}

	private function buildSpreadsheetXmlWorksheet($name, $rows = array())
	{
		return '<Worksheet ss:Name="'.$this->escapeSpreadsheetXml($name).'"><Table>'.implode('', $rows).'</Table></Worksheet>';
	}

	private function buildEmployerPostReferenceMap($options = array())
	{
		$map = array();
		if(!is_array($options)){
			return $map;
		}
		foreach($options as $option){
			if(isset($option->id)){
				$map[(int) $option->id] = isset($option->label) ? (string) $option->label : '';
			}
		}
		return $map;
	}

	private function exportEmployerPostImportTemplate()
	{
		$employers = $this->getEmployerOptions();
		$job_categories = $this->getAdminReferenceOptions('hicrm_job_categories', 'job_category_name');
		$provinces = $this->getAdminReferenceOptions('hicrm_provinces', 'province_name');
		$salaries = $this->getAdminReferenceOptions('hicrm_salary', 'salary_name');

		$data_rows = array();
		$data_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'employer_id'),
			array('value' => 'title'),
			array('value' => 'job_category_id'),
			array('value' => 'province_id'),
			array('value' => 'quantity'),
			array('value' => 'job_description'),
			array('value' => 'experience_years'),
			array('value' => 'degree_required'),
			array('value' => 'salary_id'),
			array('value' => 'benefits_description'),
			array('value' => 'work_type'),
			array('value' => 'address_detail'),
			array('value' => 'deadline'),
			array('value' => 'status')
		));
		$data_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('type' => 'Number', 'value' => ''),
			array('value' => 'Nhân viên kinh doanh'),
			array('type' => 'Number', 'value' => ''),
			array('type' => 'Number', 'value' => ''),
			array('type' => 'Number', 'value' => '2'),
			array('value' => 'Mô tả công việc'),
			array('type' => 'Number', 'value' => '1'),
			array('value' => 'Cao đẳng'),
			array('type' => 'Number', 'value' => ''),
			array('value' => 'BHXH, thưởng KPI'),
			array('value' => 'Toàn thời gian'),
			array('value' => '123 Trần Phú'),
			array('value' => date('Y-m-d', strtotime('+30 days'))),
			array('value' => 'pending')
		));

		$guide_rows = array();
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'Cột'),
			array('value' => 'Bắt buộc'),
			array('value' => 'Kiểu dữ liệu'),
			array('value' => 'Ghi chú')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'employer_id'),
			array('value' => 'Có'),
			array('value' => 'Số nguyên'),
			array('value' => 'Phải tồn tại trong sheet NhaTuyenDung')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'title'),
			array('value' => 'Có'),
			array('value' => 'Chuỗi'),
			array('value' => 'Tiêu đề bài đăng')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'job_category_id'),
			array('value' => 'Không'),
			array('value' => 'Số nguyên'),
			array('value' => 'Nếu nhập phải tồn tại trong sheet NganhNghe')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'province_id'),
			array('value' => 'Không'),
			array('value' => 'Số nguyên'),
			array('value' => 'Nếu nhập phải tồn tại trong sheet TinhThanh')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'quantity'),
			array('value' => 'Có'),
			array('value' => 'Số nguyên >= 1'),
			array('value' => 'Số lượng tuyển')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'experience_years'),
			array('value' => 'Không'),
			array('value' => 'Số nguyên >= 0'),
			array('value' => 'Số năm kinh nghiệm')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'salary_id'),
			array('value' => 'Không'),
			array('value' => 'Số nguyên'),
			array('value' => 'Nếu nhập phải tồn tại trong sheet MucLuong')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'deadline'),
			array('value' => 'Có'),
			array('value' => 'Ngày YYYY-MM-DD'),
			array('value' => 'Ví dụ: '.date('Y-m-d', strtotime('+30 days')))
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'status'),
			array('value' => 'Có'),
			array('value' => 'Chuỗi'),
			array('value' => 'Chỉ nhận: draft, pending, published, closed, rejected')
		));

		$reference_to_rows = function($options, $labelHeader) {
			$rows = array();
			$rows[] = $this->buildSpreadsheetXmlRow(array(
				array('value' => 'id'),
				array('value' => $labelHeader)
			));
			if(is_array($options)){
				foreach($options as $option){
					$rows[] = $this->buildSpreadsheetXmlRow(array(
						array('type' => 'Number', 'value' => isset($option->id) ? (int) $option->id : ''),
						array('value' => isset($option->label) ? $option->label : '')
					));
				}
			}
			return $rows;
		};

		$xml = '<?xml version="1.0" encoding="UTF-8"?>'
			.'<?mso-application progid="Excel.Sheet"?>'
			.'<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
			.' xmlns:o="urn:schemas-microsoft-com:office:office"'
			.' xmlns:x="urn:schemas-microsoft-com:office:excel"'
			.' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
			.$this->buildSpreadsheetXmlWorksheet('DuLieuImport', $data_rows)
			.$this->buildSpreadsheetXmlWorksheet('HuongDan', $guide_rows)
			.$this->buildSpreadsheetXmlWorksheet('NhaTuyenDung', $reference_to_rows($employers, 'company_name'))
			.$this->buildSpreadsheetXmlWorksheet('NganhNghe', $reference_to_rows($job_categories, 'job_category_name'))
			.$this->buildSpreadsheetXmlWorksheet('TinhThanh', $reference_to_rows($provinces, 'province_name'))
			.$this->buildSpreadsheetXmlWorksheet('MucLuong', $reference_to_rows($salaries, 'salary_name'))
			.'</Workbook>';

		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="mau-import-bai-dang-tuyen-dung.xls"');
		header('Cache-Control: max-age=0');
		echo $xml;
		exit();
	}

	private function exportStudentImportTemplate()
	{
		$job_categories = $this->getAdminReferenceOptions('hicrm_job_categories', 'job_category_name');

		$data_rows = array();
		$data_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_code'),
			array('value' => 'student_name'),
			array('value' => 'student_phone'),
			array('value' => 'student_email'),
			array('value' => 'student_class'),
			array('value' => 'student_birthday'),
			array('value' => 'student_gender'),
			array('value' => 'student_major_id'),
			array('value' => 'student_gpa'),
			array('value' => 'student_rank'),
			array('value' => 'student_description')
		));
		$data_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'SV001'),
			array('value' => 'Nguyen Van A'),
			array('value' => '0901234567'),
			array('value' => 'sinhviena@example.com'),
			array('value' => 'CNTT01'),
			array('value' => '2003-09-01'),
			array('type' => 'Number', 'value' => '1'),
			array('type' => 'Number', 'value' => ''),
			array('value' => '8.50'),
			array('value' => 'Gioi'),
			array('value' => 'Sinh vien nam cuoi')
		));

		$guide_rows = array();
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'Cột'),
			array('value' => 'Bắt buộc'),
			array('value' => 'Kiểu dữ liệu'),
			array('value' => 'Ghi chú')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_code'),
			array('value' => 'Có'),
			array('value' => 'Chuỗi'),
			array('value' => 'Không được trùng trong hệ thống')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_name'),
			array('value' => 'Có'),
			array('value' => 'Chuỗi'),
			array('value' => 'Họ và tên sinh viên')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_class'),
			array('value' => 'Có'),
			array('value' => 'Chuỗi'),
			array('value' => 'Ví dụ: CNTT01')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_birthday'),
			array('value' => 'Có'),
			array('value' => 'Ngày YYYY-MM-DD'),
			array('value' => 'Ví dụ: 2003-09-01')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_gender'),
			array('value' => 'Có'),
			array('value' => 'Số nguyên'),
			array('value' => 'Chỉ nhận: 0, 1, 2. Xem sheet GioiTinh')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_major_id'),
			array('value' => 'Có'),
			array('value' => 'Số nguyên'),
			array('value' => 'Phải tồn tại trong sheet NganhHoc')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_email'),
			array('value' => 'Không'),
			array('value' => 'Email'),
			array('value' => 'Nếu nhập phải đúng định dạng email')
		));
		$guide_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'student_gpa'),
			array('value' => 'Không'),
			array('value' => 'Số từ 0 đến 10'),
			array('value' => 'Có thể để trống')
		));

		$gender_rows = array(
			$this->buildSpreadsheetXmlRow(array(
				array('value' => 'id'),
				array('value' => 'gender_name')
			)),
			$this->buildSpreadsheetXmlRow(array(
				array('type' => 'Number', 'value' => '0'),
				array('value' => 'Khac')
			)),
			$this->buildSpreadsheetXmlRow(array(
				array('type' => 'Number', 'value' => '1'),
				array('value' => 'Nam')
			)),
			$this->buildSpreadsheetXmlRow(array(
				array('type' => 'Number', 'value' => '2'),
				array('value' => 'Nu')
			))
		);

		$major_rows = array();
		$major_rows[] = $this->buildSpreadsheetXmlRow(array(
			array('value' => 'id'),
			array('value' => 'job_category_name')
		));
		if(is_array($job_categories)){
			foreach($job_categories as $category){
				$major_rows[] = $this->buildSpreadsheetXmlRow(array(
					array('type' => 'Number', 'value' => isset($category->id) ? (int) $category->id : ''),
					array('value' => isset($category->label) ? $category->label : '')
				));
			}
		}

		$xml = '<?xml version="1.0" encoding="UTF-8"?>'
			.'<?mso-application progid="Excel.Sheet"?>'
			.'<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
			.' xmlns:o="urn:schemas-microsoft-com:office:office"'
			.' xmlns:x="urn:schemas-microsoft-com:office:excel"'
			.' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
			.$this->buildSpreadsheetXmlWorksheet('DuLieuImport', $data_rows)
			.$this->buildSpreadsheetXmlWorksheet('HuongDan', $guide_rows)
			.$this->buildSpreadsheetXmlWorksheet('GioiTinh', $gender_rows)
			.$this->buildSpreadsheetXmlWorksheet('NganhHoc', $major_rows)
			.'</Workbook>';

		header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
		header('Content-Disposition: attachment; filename="mau-import-sinh-vien.xls"');
		header('Cache-Control: max-age=0');
		echo $xml;
		exit();
	}

	private function parseEmployerPostImportSpreadsheetXml($file_path)
	{
		$result = array(
			'header' => array(),
			'rows' => array(),
			'error' => ''
		);
		if(!function_exists('simplexml_load_file')){
			$result['error'] = 'Máy chủ chưa hỗ trợ đọc file XML.';
			return $result;
		}
		$xml = @simplexml_load_file($file_path);
		if(!$xml){
			$result['error'] = 'Không thể đọc file mẫu import XML.';
			return $result;
		}
		$xml->registerXPathNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
		$worksheets = $xml->xpath('//ss:Worksheet');
		if(!is_array($worksheets) || empty($worksheets)){
			$result['error'] = 'Không tìm thấy sheet dữ liệu trong file import.';
			return $result;
		}
		$data_sheet = null;
		foreach($worksheets as $worksheet){
			$attrs = $worksheet->attributes('urn:schemas-microsoft-com:office:spreadsheet');
			$sheet_name = isset($attrs['Name']) ? (string) $attrs['Name'] : '';
			if($sheet_name === 'DuLieuImport'){
				$data_sheet = $worksheet;
				break;
			}
		}
		if($data_sheet === null){
			$data_sheet = $worksheets[0];
		}
		$rows = $data_sheet->xpath('./ss:Table/ss:Row');
		if(!is_array($rows) || empty($rows)){
			$result['error'] = 'Sheet dữ liệu đang trống.';
			return $result;
		}
		foreach($rows as $row_index => $row){
			$cells = $row->xpath('./ss:Cell');
			$values = array();
			if(is_array($cells)){
				foreach($cells as $cell){
					$data_nodes = $cell->xpath('./ss:Data');
					$values[] = isset($data_nodes[0]) ? trim((string) $data_nodes[0]) : '';
				}
			}
			if($row_index === 0){
				$result['header'] = $values;
			}else{
				$result['rows'][] = $values;
			}
		}
		return $result;
	}

	private function parseEmployerPostImportFile($file_path, $extension)
	{
		$extension = strtolower((string) $extension);
		if(in_array($extension, array('xml', 'xls'))){
			return $this->parseEmployerPostImportSpreadsheetXml($file_path);
		}
		$result = array(
			'header' => array(),
			'rows' => array(),
			'error' => ''
		);
		$handle = fopen($file_path, 'r');
		if(!$handle){
			$result['error'] = 'Không thể mở file import.';
			return $result;
		}
		$header = fgetcsv($handle, 0, ',');
		if(is_array($header)){
			$result['header'] = array_map('trim', $header);
			while(($row = fgetcsv($handle, 0, ',')) !== false){
				$result['rows'][] = $row;
			}
		}
		fclose($handle);
		if(empty($result['header'])){
			$result['error'] = 'File import không có dòng tiêu đề.';
		}
		return $result;
	}

	public function users($para)
	{
		if(!$this->prepareAdminAccess('users')){ return; }
		$userModel = $this->model->get('userModel');
		global $db;
		$db->query("SELECT DISTINCT g.* FROM hicrm_user_groups g
			LEFT JOIN hicrm_user_group_permissions gp ON gp.group_id = g.id
			LEFT JOIN hicrm_admin_menu_permissions p ON p.id = gp.permission_id AND p.permission_status = 1
			WHERE g.group_status NOT IN(99) AND (g.id = 1 OR p.id IS NOT NULL)
			ORDER BY g.id ASC");
		$admin_groups = $db->fetch_object();
		if(isset($para[1]) && $para[1] == "add"){
			$this->view->data['method'] = 'add';
			$user_category = $userModel->get_user_category();
			$this->view->data['user_category'] = $user_category;
			$this->view->data['roles'] = $admin_groups;
			$this->view->data['pagetitle'] = 'Thêm tài khoản';
			$this->view->admintmp("user-action");
		}elseif(isset($para[1]) && $para[1] == "edit"){
			$this->view->data['method'] = 'edit';
			$this->view->data['user_categories'] = $userModel->get_user_category();
			$this->view->data['roles'] = $admin_groups;
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
			$this->view->data['admin_groups'] = $admin_groups;
			$this->view->admintmp("users");
		}
		
	}
	//end users

	public function candidates($para = array())
	{
		global $db;
		if(!$this->prepareAdminAccess('candidates')){ return; }

		$candidate_status_map = $this->getAdminStatusMap();
		$candidate_pending_status = $this->getAdminStatusIdByAliases(array('Chờ phê duyệt', 'Chờ duyệt', 'Pending'), 2);
		$candidate_approved_status = $this->getAdminStatusIdByAliases(array('Phê duyệt', 'Đã duyệt', 'Approved'), 3);
		$candidate_rejected_status = $this->getAdminStatusIdByAliases(array('Từ chối', 'Rejected'), 98);
		$candidate_deleted_status = $this->getAdminStatusIdByAliases(array('Xóa', 'Đã xóa', 'Deleted'), 99);

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
							$fields = array("status = '".$candidate_approved_status."'");
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
							$fields = array("status = '".$candidate_rejected_status."'");
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
							$fields = array("status = '".$candidate_deleted_status."'");
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
			$where[] = "IFNULL(ca.status,".$candidate_pending_status.") <> ".$candidate_deleted_status;
		}

		$status_select = $has_status ? "ca.status AS status" : "'".$candidate_pending_status."' AS status";
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
		$this->view->data['candidate_status_map'] = $candidate_status_map;
		$this->view->data['candidate_flash'] = $this->getAdminFlash();
		$this->view->admintmp("candidates");
	}

	// Quản lý nhóm quyền
	public function groups($para)
	{
		global $db;
		if(!$this->prepareAdminAccess('groups')){ return; }
		$db->query("SELECT * FROM hicrm_admin_menu_permissions WHERE permission_status = 1 ORDER BY parent_key, sort_order, id");
		$permissions = $db->fetch_object();

		if(isset($para[1]) && ($para[1] == "add" || $para[1] == "edit")){
			$method = $para[1];
			$group_id = ($method === 'edit' && isset($para[2])) ? intval($para[2]) : 0;
			$group = null;
			$selected = array();
			if($group_id > 0){
				$db->query("SELECT * FROM hicrm_user_groups WHERE id = '".$group_id."' AND group_status NOT IN(99) LIMIT 1");
				$group = $db->fetch_object(true);
				if(!$group){ $this->adminRedirect('/admin/groups'); }
				$db->query("SELECT permission_id FROM hicrm_user_group_permissions WHERE group_id = '".$group_id."'");
				$rows = $db->fetch_object();
				if(is_array($rows)){ foreach($rows as $row){ $selected[] = intval($row->permission_id); } }
			}
			$this->view->data['method'] = $method;
			$this->view->data['group'] = $group;
			$this->view->data['permissions'] = is_array($permissions) ? $permissions : array();
			$this->view->data['selected_permission_ids'] = $selected;
			$this->view->data['pagetitle'] = $method === 'edit' ? 'Phân quyền nhóm' : 'Thêm nhóm quyền mới';
			$this->view->admintmp("group-add");
		}else{
			$this->view->data['active_menu'] = "groups";
			$db->query("SELECT g.*, COUNT(DISTINCT gp.permission_id) AS permission_count, COUNT(DISTINCT u.id) AS user_count
				FROM hicrm_user_groups g
				LEFT JOIN hicrm_user_group_permissions gp ON gp.group_id = g.id
				LEFT JOIN hicrm_users u ON u.user_group = g.id AND u.user_status NOT IN(99)
				WHERE g.group_status NOT IN(99)
				GROUP BY g.id ORDER BY g.id ASC");
			$this->view->data['groups'] = $db->fetch_object();
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
		if(!$this->prepareAdminAccess('students')){ return; }
		global $db;
		
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		if(isset($_GET['download_template']) && (int) $_GET['download_template'] === 1){
			$this->exportStudentImportTemplate();
		}
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
			$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
			$major_filter = isset($_GET['major_id']) ? intval($_GET['major_id']) : 0;
			$gender_filter = isset($_GET['gender']) ? trim((string)$_GET['gender']) : '';
			$register_filter = isset($_GET['register_status']) ? trim((string)$_GET['register_status']) : '';
			$class_filter = isset($_GET['student_class']) ? trim($_GET['student_class']) : '';
			$where = array("1=1");

			if($keyword !== ''){
				$kw = $db->escapestring($keyword);
				$where[] = "(sp.student_name LIKE '%".$kw."%' OR sp.student_code LIKE '%".$kw."%' OR sp.student_email LIKE '%".$kw."%' OR sp.student_phone LIKE '%".$kw."%')";
			}
			if($major_filter > 0){
				$where[] = "sp.student_major_id = '".$major_filter."'";
			}
			if($gender_filter !== '' && in_array($gender_filter, array('0', '1', '2'), true)){
				$where[] = "sp.student_gender = '".intval($gender_filter)."'";
			}
			if($register_filter !== '' && in_array($register_filter, array('0', '1'), true)){
				$where[] = "sp.student_is_register = '".intval($register_filter)."'";
			}
			if($class_filter !== ''){
				$where[] = "sp.student_class = '".$db->escapestring($class_filter)."'";
			}

			// get student list from profile table and map existing student accounts if present
			$db->query("SELECT sp.*, u.id AS uid, u.user_status AS student_status, c.job_category_name
				FROM hicrm_student_profile AS sp
				LEFT JOIN hicrm_users AS u ON u.student_id = sp.id AND u.user_group = '3' AND (u.user_deleted_at IS NULL OR u.user_deleted_at = '')
				LEFT JOIN hicrm_job_categories AS c ON c.id = sp.student_major_id
				WHERE ".implode(' AND ', $where)."
				ORDER BY sp.id DESC");
			$students = $db->fetch_object();
			$this->view->data['job_categories'] = $this->getAdminReferenceOptions('hicrm_job_categories', 'job_category_name');
			$db->query("SELECT DISTINCT student_class FROM hicrm_student_profile WHERE student_class IS NOT NULL AND student_class <> '' ORDER BY student_class ASC");
			$student_classes = $db->fetch_object();
			$this->view->data['active_menu'] = "students";
			$this->view->data['students'] = is_array($students) ? $students : array();
			$this->view->data['student_classes'] = is_array($student_classes) ? $student_classes : array();
			$this->view->data['student_keyword'] = $keyword;
			$this->view->data['student_major_filter'] = $major_filter;
			$this->view->data['student_gender_filter'] = $gender_filter;
			$this->view->data['student_register_filter'] = $register_filter;
			$this->view->data['student_class_filter'] = $class_filter;
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
		if(!$this->prepareAdminAccess('images')){ return; }
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); return; }
		$page = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
		$per_page = 20;
		$base_sql = "FROM hicrm_images WHERE image_status NOT IN(99)";
		$db->query("SELECT COUNT(id) AS total ".$base_sql);
		$total_images = (int)$db->fetch_object(true)->total;
		$total_pages = max(1, ceil($total_images / $per_page));
		if($page > $total_pages){
			$page = $total_pages;
		}
		$offset = ($page - 1) * $per_page;
		$db->query("SELECT * ".$base_sql." ORDER BY id DESC LIMIT ".$offset.",".$per_page);
		$this->view->data['active_menu'] = "images";
		$this->view->data['images'] = $db->fetch_object();
		$this->view->data['page'] = $page;
		$this->view->data['per_page'] = $per_page;
		$this->view->data['total_images'] = $total_images;
		$this->view->data['total_pages'] = $total_pages;
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
		if(!$this->prepareAdminAccess('events')){ return; }
		global $db;
		$method = $para[1];
		$id = $para[2];
		$page = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
		$per_page = 20;
		// echo $id;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		$base_sql = "FROM hicrm_events as e
					LEFT JOIN hicrm_users as u ON e.event_user_created = u.id
					LEFT JOIN hicrm_categories as c ON e.event_type = c.id
					WHERE e.event_status NOT IN (99)";
		$db->query("SELECT COUNT(e.id) AS total ".$base_sql);
		$total_events = (int)$db->fetch_object(true)->total;
		$total_pages = max(1, ceil($total_events / $per_page));
		if($page > $total_pages){
			$page = $total_pages;
		}
		$offset = ($page - 1) * $per_page;
		$db->query("SELECT e.*, e.id as eid, u.full_name AS author_name, c.category_name ".$base_sql." ORDER BY e.event_created_date DESC LIMIT ".$offset.",".$per_page);
		$events = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_categories WHERE category_status NOT IN (99) ORDER BY category_orderby ASC, id DESC");
		$event_categories = $db->fetch_object();
		// $db->query("SELECT * FROM hicrm_dmtype");
		$this->view->data['active_menu'] = "events";
		$this->view->data['event_categories'] = is_array($event_categories) ? $event_categories : array();
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
		$this->view->data["events"] = is_array($events) ? $events : array();
		$this->view->data['page'] = $page;
		$this->view->data['per_page'] = $per_page;
		$this->view->data['total_events'] = $total_events;
		$this->view->data['total_pages'] = $total_pages;
		// $this->view->data["dmtype"] = $dmtype;
		$this->view->admintmp("events");
		}
	}

	public function newscomments($para = array())
	{
		if(!$this->prepareAdminAccess('news_comments')){ return; }
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		$this->ensureAdminFeatureTables();

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newscomment_action'])){
			$id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
			if($_POST['newscomment_action'] == 'reply' && $id > 0){
				$reply = isset($_POST['admin_reply']) ? $db->escapestring(trim($_POST['admin_reply'])) : '';
				$reply_user_id = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;
				$db->query("UPDATE hicrm_event_comments SET admin_reply = '".$reply."', reply_user_id = '".$reply_user_id."', replied_at = NOW(), updated_at = NOW() WHERE id = '".$id."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã lưu phản hồi bình luận.');
			}elseif($_POST['newscomment_action'] == 'delete' && $id > 0){
				$db->query("UPDATE hicrm_event_comments SET status = 99, updated_at = NOW() WHERE id = '".$id."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã ẩn bình luận.');
			}
			$this->adminRedirect('/admin/newscomments');
		}

		$where = array("1=1");
		$where[] = "nc.status <> 99";
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		if($keyword !== ''){
			$kw = $db->escapestring($keyword);
			$keyword_where = array(
				"nc.comment_name LIKE '%".$kw."%'",
				"nc.comment_email LIKE '%".$kw."%'",
				"nc.comment_content LIKE '%".$kw."%'",
				"cu.full_name LIKE '%".$kw."%'",
				"cu.user_email LIKE '%".$kw."%'",
				"cu.user_phone LIKE '%".$kw."%'"
			);
			$keyword_where[] = "e.event_name LIKE '%".$kw."%'";
			$where[] = "(".implode(" OR ", $keyword_where).")";
		}
		$db->query("SELECT nc.*,
			e.event_name AS event_title,
			COALESCE(NULLIF(cu.full_name, ''), NULLIF(nc.comment_name, ''), 'Ẩn danh') AS commenter_name,
			COALESCE(NULLIF(cu.user_email, ''), NULLIF(nc.comment_email, '')) AS commenter_email,
			COALESCE(NULLIF(cu.user_phone, ''), '') AS commenter_phone,
			ru.full_name AS reply_user_name
			FROM hicrm_event_comments nc
			LEFT JOIN hicrm_events e ON nc.event_id = e.id
			LEFT JOIN hicrm_users cu ON nc.user_id = cu.id
			LEFT JOIN hicrm_users ru ON nc.reply_user_id = ru.id
			WHERE ".implode(' AND ', $where)."
			ORDER BY nc.created_at DESC, nc.id DESC");
		$comments = $db->fetch_object();

		$this->view->data['active_menu'] = "newscomments";
		$this->view->data['news_comments'] = is_array($comments) ? $comments : array();
		$this->view->data['newscomment_keyword'] = $keyword;
		$this->view->data['newscomment_flash'] = $this->getAdminFlash();
		$this->view->admintmp("newscomments");
	}

	public function customerfeedbacks($para = array())
	{
		if(!$this->prepareAdminAccess('customer_feedbacks')){ return; }
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		$this->ensureAdminFeatureTables();

		$method = isset($para[1]) ? trim((string)$para[1]) : '';
		$id = isset($para[2]) ? intval($para[2]) : 0;

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_feedback_action'])){
			$action = trim((string)$_POST['customer_feedback_action']);
			$postId = isset($_POST['id']) ? intval($_POST['id']) : 0;

			if($action === 'delete' && $postId > 0){
				$db->query("DELETE FROM hicrm_customer_feedback WHERE id = '".$postId."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã xóa phản hồi khách hàng.');
			}

			$this->adminRedirect('/admin/customerfeedbacks');
		}

		$page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;
		$perPage = 10;
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
		$where = array("1=1");

		if($keyword !== ''){
			$kw = $db->escapestring($keyword);
			$where[] = "(customer_name LIKE '%".$kw."%' OR customer_phone LIKE '%".$kw."%' OR customer_email LIKE '%".$kw."%' OR customer_address LIKE '%".$kw."%' OR content LIKE '%".$kw."%')";
		}

		$baseSql = "FROM hicrm_customer_feedback WHERE ".implode(' AND ', $where);
		$db->query("SELECT COUNT(id) AS total ".$baseSql);
		$totalFeedbacks = intval($db->fetch_object(true)->total);
		$totalPages = max(1, ceil($totalFeedbacks / $perPage));
		if($page > $totalPages){ $page = $totalPages; }
		$offset = ($page - 1) * $perPage;

		$db->query("SELECT * ".$baseSql." ORDER BY create_date DESC, id DESC LIMIT ".$offset.",".$perPage);
		$items = $db->fetch_object();

		$detailItem = null;
		if($method === 'detail' && $id > 0){
			$db->query("SELECT * FROM hicrm_customer_feedback WHERE id = '".$id."' LIMIT 1");
			$detailItem = $db->fetch_object(true);
			if(!$detailItem){
				$this->setAdminFlash('info', 'Không tìm thấy phản hồi khách hàng.');
				$this->adminRedirect('/admin/customerfeedbacks');
			}
		}

		$this->view->data['active_menu'] = "customerfeedbacks";
		$this->view->data['customer_feedbacks'] = is_array($items) ? $items : array();
		$this->view->data['customer_feedback_page'] = $page;
		$this->view->data['customer_feedback_per_page'] = $perPage;
		$this->view->data['customer_feedback_total'] = $totalFeedbacks;
		$this->view->data['customer_feedback_total_pages'] = $totalPages;
		$this->view->data['customer_feedback_keyword'] = $keyword;
		$this->view->data['customer_feedback_detail'] = $detailItem;
		$this->view->data['customer_feedback_flash'] = $this->getAdminFlash();

		if($method === 'detail' && $id > 0){
			$this->view->admintmp("customer-feedback-detail");
			return;
		}

		$this->view->admintmp("customer-feedbacks");
	}

	public function jobsupportcustomers($para = array())
	{
		global $db;
		if(!$this->prepareAdminAccess('job_support_customers')){
			return;
		}
		if(!$this->adminTableExists('hicrm_job_support_requests')){
			$this->renderAdminNotice(
				'Quản lý khách hàng',
				'Không tìm thấy bảng hicrm_job_support_requests trong cơ sở dữ liệu hiện tại.',
				'jobsupportcustomers'
			);
			return;
		}

		$filters = array(
			'full_name' => isset($_GET['full_name']) ? trim((string)$_GET['full_name']) : '',
			'phone' => isset($_GET['phone']) ? trim((string)$_GET['phone']) : '',
			'email' => isset($_GET['email']) ? trim((string)$_GET['email']) : '',
			'job_keyword' => isset($_GET['job_keyword']) ? trim((string)$_GET['job_keyword']) : '',
			'job_id' => isset($_GET['job_id']) ? max(0, intval($_GET['job_id'])) : 0,
			'date_from' => isset($_GET['date_from']) ? trim((string)$_GET['date_from']) : '',
			'date_to' => isset($_GET['date_to']) ? trim((string)$_GET['date_to']) : ''
		);

		foreach(array('date_from', 'date_to') as $dateKey){
			if($filters[$dateKey] !== ''){
				$dateParts = explode('-', $filters[$dateKey]);
				if(count($dateParts) !== 3 || !checkdate(intval($dateParts[1]), intval($dateParts[2]), intval($dateParts[0]))){
					$filters[$dateKey] = '';
				}
			}
		}

		$where = array('1=1');
		if($filters['full_name'] !== ''){
			$where[] = "r.full_name LIKE '%".$db->escapestring($filters['full_name'])."%'";
		}
		if($filters['phone'] !== ''){
			$where[] = "r.phone LIKE '%".$db->escapestring($filters['phone'])."%'";
		}
		if($filters['email'] !== ''){
			$where[] = "r.email LIKE '%".$db->escapestring($filters['email'])."%'";
		}
		if($filters['job_keyword'] !== ''){
			$where[] = "j.title LIKE '%".$db->escapestring($filters['job_keyword'])."%'";
		}
		if($filters['job_id'] > 0){
			$where[] = "r.job_id = '".intval($filters['job_id'])."'";
		}
		if($filters['date_from'] !== ''){
			$where[] = "r.created_at >= '".$db->escapestring($filters['date_from'])." 00:00:00'";
		}
		if($filters['date_to'] !== ''){
			$where[] = "r.created_at <= '".$db->escapestring($filters['date_to'])." 23:59:59'";
		}

		$baseSql = "FROM hicrm_job_support_requests r
			LEFT JOIN hicrm_job_posts j ON j.id = r.job_id
			WHERE ".implode(' AND ', $where);
		$db->query("SELECT COUNT(r.id) AS total ".$baseSql);
		$totalRow = $db->fetch_object(true);
		$totalCustomers = $totalRow ? intval($totalRow->total) : 0;
		$perPage = 20;
		$totalPages = max(1, (int)ceil($totalCustomers / $perPage));
		$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
		if($page > $totalPages){
			$page = $totalPages;
		}
		$offset = ($page - 1) * $perPage;

		$db->query("SELECT r.*, j.title AS job_title, j.status AS job_status
			".$baseSql."
			ORDER BY r.created_at DESC, r.id DESC
			LIMIT ".$offset.", ".$perPage);
		$items = $db->fetch_object();

		$db->query("SELECT DISTINCT j.id, j.title
			FROM hicrm_job_support_requests r
			INNER JOIN hicrm_job_posts j ON j.id = r.job_id
			ORDER BY j.title ASC, j.id DESC");
		$jobOptions = $db->fetch_object();

		$this->view->data['active_menu'] = 'jobsupportcustomers';
		$this->view->data['job_support_customers'] = is_array($items) ? $items : array();
		$this->view->data['job_support_customer_filters'] = $filters;
		$this->view->data['job_support_job_options'] = is_array($jobOptions) ? $jobOptions : array();
		$this->view->data['job_support_customer_page'] = $page;
		$this->view->data['job_support_customer_per_page'] = $perPage;
		$this->view->data['job_support_customer_total'] = $totalCustomers;
		$this->view->data['job_support_customer_total_pages'] = $totalPages;
		$this->view->admintmp('job-support-customers');
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
		if(!$this->prepareAdminAccess('settings')){ return; }
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
		if(!$this->prepareAdminAccess('config')){ return; }
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
		if(!$this->prepareAdminAccess('google_meet')){ return; }
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		$this->ensureAdminFeatureTables();

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['googlemeet_action'])){
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			if($_POST['googlemeet_action'] === 'save'){
				$meeting_time = $db->escapestring(trim(isset($_POST['meeting_time']) ? $_POST['meeting_time'] : ''));
				$employer_id = isset($_POST['employer_id']) ? intval($_POST['employer_id']) : 0;
				$job_post_id = isset($_POST['job_post_id']) ? intval($_POST['job_post_id']) : 0;
				$candidate_emails = $db->escapestring(trim(isset($_POST['candidate_emails']) ? $_POST['candidate_emails'] : ''));
				$meet_url = $db->escapestring(trim(isset($_POST['meet_url']) ? $_POST['meet_url'] : ''));
				$status = isset($_POST['status']) ? intval($_POST['status']) : 1;
				$created_by = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;
				if($id > 0){
					$db->query("UPDATE hicrm_google_meets SET
						meeting_time = ".($meeting_time !== '' ? "'".$meeting_time."'" : "NULL").",
						employer_id = ".($employer_id > 0 ? "'".$employer_id."'" : "NULL").",
						job_post_id = ".($job_post_id > 0 ? "'".$job_post_id."'" : "NULL").",
						candidate_emails = '".$candidate_emails."',
						meet_url = '".$meet_url."',
						status = '".$status."',
						updated_at = NOW()
						WHERE id = '".$id."' LIMIT 1");
					$this->setAdminFlash('success', 'Đã cập nhật phiên Google Meet.');
				}else{
					$db->query("INSERT INTO hicrm_google_meets(meeting_time, employer_id, job_post_id, candidate_emails, meet_url, status, created_by, created_at, updated_at)
						VALUES (".($meeting_time !== '' ? "'".$meeting_time."'" : "NULL").",".($employer_id > 0 ? "'".$employer_id."'" : "NULL").",".($job_post_id > 0 ? "'".$job_post_id."'" : "NULL").",'".$candidate_emails."','".$meet_url."','".$status."','".$created_by."',NOW(),NOW())");
					$this->setAdminFlash('success', 'Đã thêm phiên Google Meet mới.');
				}
			}elseif($_POST['googlemeet_action'] === 'delete' && $id > 0){
				$db->query("DELETE FROM hicrm_google_meets WHERE id = '".$id."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã xóa phiên Google Meet.');
			}
			$this->adminRedirect('/admin/googlemeet');
		}

		$edit_id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
		$edit_item = null;
		if($edit_id > 0){
			$db->query("SELECT * FROM hicrm_google_meets WHERE id = '".$edit_id."' LIMIT 1");
			$edit_item = $db->fetch_object(true);
		}
		$employers = array();
		if($this->adminTableExists('hicrm_employers')){
			$db->query("SELECT id, company_name FROM hicrm_employers ORDER BY company_name ASC");
			$employers = $db->fetch_object();
		}
		$job_posts = array();
		if($this->adminTableExists('hicrm_job_posts')){
			$db->query("SELECT p.id, p.title, p.employer_id, e.company_name
				FROM hicrm_job_posts p
				LEFT JOIN hicrm_employers e ON p.employer_id = e.id
				ORDER BY p.created_at DESC, p.id DESC");
			$job_posts = $db->fetch_object();
		}
		$db->query("SELECT gm.*, e.company_name, p.title AS job_title, u.full_name AS created_by_name
			FROM hicrm_google_meets gm
			LEFT JOIN hicrm_employers e ON gm.employer_id = e.id
			LEFT JOIN hicrm_job_posts p ON gm.job_post_id = p.id
			LEFT JOIN hicrm_users u ON gm.created_by = u.id
			ORDER BY gm.meeting_time DESC, gm.id DESC");
		$items = $db->fetch_object();
		$this->view->data['active_menu'] = "googlemeet";
		$this->view->data['googlemeet_items'] = is_array($items) ? $items : array();
		$this->view->data['googlemeet_edit'] = $edit_item;
		$this->view->data['googlemeet_employers'] = is_array($employers) ? $employers : array();
		$this->view->data['googlemeet_job_posts'] = is_array($job_posts) ? $job_posts : array();
		$this->view->data['googlemeet_flash'] = $this->getAdminFlash();
		$this->view->admintmp("googlemeet");
	}

	public function marketresults($para = array())
	{
		if(!$this->prepareAdminAccess('market_results')){ return; }
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		$this->ensureAdminFeatureTables();

		$method = isset($para[1]) ? trim((string)$para[1]) : '';
		$id = isset($para[2]) ? intval($para[2]) : 0;

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['market_result_action'])){
			$action = trim((string)$_POST['market_result_action']);
			$postId = isset($_POST['id']) ? intval($_POST['id']) : 0;

			if($action === 'save'){
				$title = $db->escapestring(trim(isset($_POST['result_title']) ? $_POST['result_title'] : ''));
				$summary = $db->escapestring(trim(isset($_POST['result_summary']) ? $_POST['result_summary'] : ''));
				$content = $db->escapestring(trim(isset($_POST['result_content']) ? $_POST['result_content'] : ''));
				$image = $db->escapestring(trim(isset($_POST['result_image']) ? $_POST['result_image'] : ''));
				$resultDate = $db->escapestring(trim(isset($_POST['result_date']) ? $_POST['result_date'] : ''));
				$companyTotal = isset($_POST['company_total']) ? intval($_POST['company_total']) : 0;
				$positionTotal = isset($_POST['position_total']) ? intval($_POST['position_total']) : 0;
				$profileTotal = isset($_POST['profile_total']) ? intval($_POST['profile_total']) : 0;
				$interviewTotal = isset($_POST['interview_total']) ? intval($_POST['interview_total']) : 0;
				$implementationContent = $db->escapestring(trim(isset($_POST['implementation_content']) ? $_POST['implementation_content'] : ''));
				$highlightContent = $db->escapestring(trim(isset($_POST['highlight_content']) ? $_POST['highlight_content'] : ''));
				$noteContent = $db->escapestring(trim(isset($_POST['note_content']) ? $_POST['note_content'] : ''));
				$status = isset($_POST['result_status']) ? intval($_POST['result_status']) : 1;
				$createdBy = isset($_SESSION['user']['id']) ? intval($_SESSION['user']['id']) : 0;

				if($title === ''){
					$this->setAdminFlash('info', 'Vui lòng nhập tên đợt kết quả sàn.');
					$this->adminRedirect($postId > 0 ? '/admin/marketresults/edit/'.$postId : '/admin/marketresults/add');
				}

				if($postId > 0){
					$db->query("UPDATE hicrm_market_results SET
						result_title = '".$title."',
						result_summary = '".$summary."',
						result_content = '".$content."',
						result_image = '".$image."',
						result_date = ".($resultDate !== '' ? "'".$resultDate."'" : "NULL").",
						company_total = '".$companyTotal."',
						position_total = '".$positionTotal."',
						profile_total = '".$profileTotal."',
						interview_total = '".$interviewTotal."',
						implementation_content = '".$implementationContent."',
						highlight_content = '".$highlightContent."',
						note_content = '".$noteContent."',
						result_status = '".$status."',
						updated_at = NOW()
						WHERE id = '".$postId."' LIMIT 1");
					$this->setAdminFlash('success', 'Đã cập nhật kết quả sàn.');
				}else{
					$db->query("INSERT INTO hicrm_market_results(
						result_title, result_summary, result_content, result_image, result_date,
						company_total, position_total, profile_total, interview_total,
						implementation_content, highlight_content, note_content,
						result_status, created_by, created_at, updated_at
					) VALUES (
						'".$title."','".$summary."','".$content."','".$image."',".($resultDate !== '' ? "'".$resultDate."'" : "NULL").",
						'".$companyTotal."','".$positionTotal."','".$profileTotal."','".$interviewTotal."',
						'".$implementationContent."','".$highlightContent."','".$noteContent."',
						'".$status."','".$createdBy."',NOW(),NOW()
					)");
					$this->setAdminFlash('success', 'Đã thêm kết quả sàn mới.');
				}
				$this->adminRedirect('/admin/marketresults');
			}elseif($action === 'delete' && $postId > 0){
				$db->query("DELETE FROM hicrm_market_results WHERE id = '".$postId."' LIMIT 1");
				$this->setAdminFlash('success', 'Đã xóa kết quả sàn.');
				$this->adminRedirect('/admin/marketresults');
			}
		}

		$page = (isset($_GET['page']) && intval($_GET['page']) > 0) ? intval($_GET['page']) : 1;
		$perPage = 20;
		$baseSql = "FROM hicrm_market_results mr
			LEFT JOIN hicrm_users u ON mr.created_by = u.id
			WHERE 1=1";
		$db->query("SELECT COUNT(mr.id) AS total ".$baseSql);
		$totalResults = intval($db->fetch_object(true)->total);
		$totalPages = max(1, ceil($totalResults / $perPage));
		if($page > $totalPages){ $page = $totalPages; }
		$offset = ($page - 1) * $perPage;

		$db->query("SELECT mr.*, u.full_name AS created_by_name ".$baseSql." ORDER BY mr.result_date DESC, mr.id DESC LIMIT ".$offset.",".$perPage);
		$items = $db->fetch_object();

		$editItem = null;
		if($method === 'edit' && $id > 0){
			$db->query("SELECT * FROM hicrm_market_results WHERE id = '".$id."' LIMIT 1");
			$editItem = $db->fetch_object(true);
		}

		$this->view->data['active_menu'] = "marketresults";
		$this->view->data['market_results'] = is_array($items) ? $items : array();
		$this->view->data['market_results_page'] = $page;
		$this->view->data['market_results_per_page'] = $perPage;
		$this->view->data['market_results_total'] = $totalResults;
		$this->view->data['market_results_total_pages'] = $totalPages;
		$this->view->data['market_result_edit'] = $editItem;
		$this->view->data['market_result_flash'] = $this->getAdminFlash();

		if($method === 'add'){
			$this->view->admintmp("market-result-form");
			return;
		}
		if($method === 'edit' && $id > 0){
			$this->view->admintmp("market-result-form");
			return;
		}
		$this->view->admintmp("market-results");
	}

	public function videos($para = array())
	{
		if(!$this->prepareAdminAccess('videos')){ return; }
		global $db;
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ $this->adminRedirect('/admin/login'); }
		$this->ensureAdminFeatureTables();

		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['video_action'])){
			$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			if($_POST['video_action'] === 'save'){
				$video_name = $db->escapestring(trim(isset($_POST['video_name']) ? $_POST['video_name'] : ''));
				$video_employee = isset($_POST['video_employee']) ? intval($_POST['video_employee']) : 0;
				$video_url = $db->escapestring(trim(isset($_POST['video_url']) ? $_POST['video_url'] : ''));
				$video_description = $db->escapestring(trim(isset($_POST['video_description']) ? $_POST['video_description'] : ''));
				$status = isset($_POST['video_status']) ? intval($_POST['video_status']) : 1;
				if($id > 0){
					$db->query("UPDATE hicrm_videos SET
						video_name = '".$video_name."',
						video_employee = '".$video_employee."',
						video_url = '".$video_url."',
						video_description = '".$video_description."',
						video_status = '".$status."',
						video_created_at = NOW()
						WHERE id = '".$id."' LIMIT 1");
					$this->setAdminFlash('success', 'Đã cập nhật video.');
				}else{
					$db->query("INSERT INTO hicrm_videos(video_name, video_employee, video_url, video_description, video_status, video_created_at)
						VALUES ('".$video_name."','".$video_employee."','".$video_url."','".$video_description."','".$status."',NOW())");
					$this->setAdminFlash('success', 'Đã thêm video mới.');
				}
			}elseif($_POST['video_action'] === 'delete' && $id > 0){
				$db->query("UPDATE hicrm_videos SET video_status = 99 WHERE id = '".$id."' LIMIT 1");
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
		$employees = array();
		$video_employee_join = "";
		$video_employee_select = "'' AS responsible_name";
		if($this->adminTableExists('hicrm_employees') && $this->adminColumnExists('hicrm_employees', 'employee_name')){
			$db->query("SELECT id, employee_name FROM hicrm_employees WHERE employee_status NOT IN(99) ORDER BY employee_name ASC, id DESC");
			$employees = $db->fetch_object();
			$video_employee_join = "LEFT JOIN hicrm_employees e ON v.video_employee = e.id";
			$video_employee_select = "e.employee_name AS responsible_name";
		}elseif($this->adminTableExists('hicrm_users') && $this->adminColumnExists('hicrm_users', 'full_name')){
			$user_conditions = "1=1";
			if($this->adminColumnExists('hicrm_users', 'user_status')){
				$user_conditions .= " AND user_status NOT IN(99)";
			}
			$db->query("SELECT id, full_name AS employee_name FROM hicrm_users WHERE ".$user_conditions." ORDER BY full_name ASC, id DESC");
			$employees = $db->fetch_object();
			$video_employee_join = "LEFT JOIN hicrm_users e ON v.video_employee = e.id";
			$video_employee_select = "e.full_name AS responsible_name";
		}
		$page = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
		$per_page = 20;
		$base_sql = "FROM hicrm_videos v ".$video_employee_join." WHERE v.video_status NOT IN (99)";
		$db->query("SELECT COUNT(v.id) AS total ".$base_sql);
		$total_row = $db->fetch_object(true);
		$total_videos = isset($total_row->total) ? (int)$total_row->total : 0;
		$total_pages = max(1, ceil($total_videos / $per_page));
		if($page > $total_pages){
			$page = $total_pages;
		}
		$offset = ($page - 1) * $per_page;
		$db->query("SELECT v.*, ".$video_employee_select." ".$base_sql." ORDER BY v.video_created_at DESC, v.id DESC LIMIT ".$offset.",".$per_page);
		$items = $db->fetch_object();
		$this->view->data['active_menu'] = "videos";
		$this->view->data['videos'] = is_array($items) ? $items : array();
		$this->view->data['video_edit'] = $edit_item;
		$this->view->data['video_employees'] = is_array($employees) ? $employees : array();
		$this->view->data['video_flash'] = $this->getAdminFlash();
		$this->view->data['page'] = $page;
		$this->view->data['per_page'] = $per_page;
		$this->view->data['total_videos'] = $total_videos;
		$this->view->data['total_pages'] = $total_pages;
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
		$this->customerfeedbacks(func_get_args());
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
		if(!$this->prepareAdminAccess('employer_posts')){ return; }
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

		if(isset($_GET['download_template']) && (int) $_GET['download_template'] === 1){
			$this->exportEmployerPostImportTemplate();
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
				$file_ext = strtolower(pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION));
				$allowed_ext = array('csv', 'xml', 'xls');
				if(!in_array($file_ext, $allowed_ext)){
					$this->setAdminFlash('info', 'File import không hợp lệ. Vui lòng dùng file mẫu Excel .xls hoặc file CSV.');
				}else{
					$parsed = $this->parseEmployerPostImportFile($_FILES['import_file']['tmp_name'], $file_ext);
					if($parsed['error'] !== ''){
						$this->setAdminFlash('info', $parsed['error']);
					}else{
						$header = array_map('trim', is_array($parsed['header']) ? $parsed['header'] : array());
						$required_header = array(
							'employer_id', 'title', 'job_category_id', 'province_id', 'quantity', 'job_description',
							'experience_years', 'degree_required', 'salary_id', 'benefits_description', 'work_type',
							'address_detail', 'deadline', 'status'
						);
						$missing_header = array_values(array_diff($required_header, $header));
						if(!empty($missing_header)){
							$this->setAdminFlash('info', 'Thiếu cột trong file import: '.implode(', ', $missing_header).'.');
						}else{
							$employer_map = $this->buildEmployerPostReferenceMap($this->getEmployerOptions());
							$job_category_map = $this->buildEmployerPostReferenceMap($this->getAdminReferenceOptions('hicrm_job_categories', 'job_category_name'));
							$province_map = $this->buildEmployerPostReferenceMap($this->getAdminReferenceOptions('hicrm_provinces', 'province_name'));
							$salary_map = $this->buildEmployerPostReferenceMap($this->getAdminReferenceOptions('hicrm_salary', 'salary_name'));
							$allowed_statuses = array('draft','pending','published','closed','rejected');
							$imported = 0;
							$errors = array();

							foreach($parsed['rows'] as $row_index => $row){
								$item = array();
								foreach($header as $idx => $key){
									$item[$key] = isset($row[$idx]) ? trim((string) $row[$idx]) : '';
								}
								$display_row = $row_index + 2;
								$is_empty_row = true;
								foreach($item as $value){
									if(trim((string) $value) !== ''){
										$is_empty_row = false;
										break;
									}
								}
								if($is_empty_row){
									continue;
								}

								$row_errors = array();
								$title_raw = isset($item['title']) ? trim($item['title']) : '';
								if($title_raw === ''){
									$row_errors[] = 'cột title bắt buộc';
								}

								$employer_raw = isset($item['employer_id']) ? trim($item['employer_id']) : '';
								if($employer_raw === ''){
									$row_errors[] = 'cột employer_id bắt buộc';
									$employer_id = 0;
								}elseif(!preg_match('/^\d+$/', $employer_raw)){
									$row_errors[] = 'employer_id phải là số nguyên';
									$employer_id = 0;
								}else{
									$employer_id = (int) $employer_raw;
									if(!isset($employer_map[$employer_id])){
										$row_errors[] = 'employer_id không tồn tại trong danh mục';
									}
								}

								$validate_optional_reference = function($field_name, $label, $map) use ($item, &$row_errors) {
									$raw = isset($item[$field_name]) ? trim($item[$field_name]) : '';
									if($raw === ''){
										return 'NULL';
									}
									if(!preg_match('/^\d+$/', $raw)){
										$row_errors[] = $label.' phải là số nguyên';
										return 'NULL';
									}
									$id = (int) $raw;
									if(!isset($map[$id])){
										$row_errors[] = $label.' không tồn tại trong danh mục';
										return 'NULL';
									}
									return $id;
								};

								$job_category_id = $validate_optional_reference('job_category_id', 'job_category_id', $job_category_map);
								$province_id = $validate_optional_reference('province_id', 'province_id', $province_map);
								$salary_id = $validate_optional_reference('salary_id', 'salary_id', $salary_map);

								$quantity_raw = isset($item['quantity']) ? trim($item['quantity']) : '';
								if($quantity_raw === ''){
									$row_errors[] = 'cột quantity bắt buộc';
									$quantity = 1;
								}elseif(!preg_match('/^\d+$/', $quantity_raw)){
									$row_errors[] = 'quantity phải là số nguyên dương';
									$quantity = 1;
								}else{
									$quantity = max(1, (int) $quantity_raw);
								}

								$experience_raw = isset($item['experience_years']) ? trim($item['experience_years']) : '';
								if($experience_raw === ''){
									$experience = 0;
								}elseif(!preg_match('/^\d+$/', $experience_raw)){
									$row_errors[] = 'experience_years phải là số nguyên >= 0';
									$experience = 0;
								}else{
									$experience = (int) $experience_raw;
								}

								$deadline_raw = isset($item['deadline']) ? trim($item['deadline']) : '';
								if($deadline_raw === ''){
									$row_errors[] = 'cột deadline bắt buộc';
									$deadline = date('Y-m-d', strtotime('+30 days'));
								}else{
									$deadline_obj = DateTime::createFromFormat('Y-m-d', $deadline_raw);
									$deadline_errors = DateTime::getLastErrors();
									$deadline_has_errors = is_array($deadline_errors) && (($deadline_errors['warning_count'] > 0) || ($deadline_errors['error_count'] > 0));
									if(!$deadline_obj || $deadline_has_errors){
										$row_errors[] = 'deadline phải đúng định dạng YYYY-MM-DD';
										$deadline = date('Y-m-d', strtotime('+30 days'));
									}else{
										$deadline = $deadline_obj->format('Y-m-d');
									}
								}

								$status_raw = isset($item['status']) ? trim($item['status']) : 'pending';
								if($status_raw === ''){
									$status_raw = 'pending';
								}
								if(!in_array($status_raw, $allowed_statuses)){
									$row_errors[] = 'status không hợp lệ';
								}
								$status = $this->normalizeJobPostStatus($status_raw);

								if(!empty($row_errors)){
									$errors[] = 'Dòng '.$display_row.': '.implode('; ', $row_errors).'.';
									continue;
								}

								$title = $db->escapestring($title_raw);
								$description = isset($item['job_description']) ? $db->escapestring($item['job_description']) : '';
								$degree = isset($item['degree_required']) ? $db->escapestring($item['degree_required']) : '';
								$work_type = isset($item['work_type']) ? $db->escapestring($item['work_type']) : '';
								$address = isset($item['address_detail']) ? $db->escapestring($item['address_detail']) : '';
								$benefits = isset($item['benefits_description']) ? $db->escapestring($item['benefits_description']) : '';

								$insert_columns = "employer_id, job_category_id, province_id, title, quantity, job_description, experience_years, degree_required, salary_id, benefits_description, work_type, address_detail, deadline, status";
								$insert_values = "'".$employer_id."',".($job_category_id === 'NULL' ? "NULL" : "'".$job_category_id."'").",".($province_id === 'NULL' ? "NULL" : "'".$province_id."'").",'".$title."','".$quantity."','".$description."','".$experience."','".$degree."',".($salary_id === 'NULL' ? "NULL" : "'".$salary_id."'").",'".$benefits."','".$work_type."','".$address."','".$deadline."','".$status."'";
								if($has_published_at){
									$insert_columns .= ", published_at";
									$insert_values .= ",".($status === 'published' ? "NOW()" : "NULL");
								}
								$insert_columns .= ", created_at, updated_at";
								$insert_values .= ", NOW(), NOW()";
								$db->query("INSERT INTO hicrm_job_posts(".$insert_columns.") VALUES (".$insert_values.")");
								$imported++;
							}

							if(!empty($errors)){
								$message = 'Import thành công '.$imported.' bài đăng.';
								if($imported === 0){
									$message = 'Chưa import được bài đăng nào.';
								}
								$message .= ' Lỗi dữ liệu: '.implode(' ', array_slice($errors, 0, 10));
								if(count($errors) > 10){
									$message .= ' Còn thêm '.(count($errors) - 10).' lỗi khác.';
								}
								$this->setAdminFlash('info', $message);
							}else{
								$this->setAdminFlash('success', 'Đã import '.$imported.' bài đăng tuyển dụng từ file dữ liệu.');
							}
						}
					}
				}
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
