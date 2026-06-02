<?php
Class adminController extends baseController
{
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

		$where = array("1=1");
		$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
		$linked_status = isset($_GET['linked_status']) ? trim($_GET['linked_status']) : "";
		$page = (isset($_GET['page']) && (int)$_GET['page'] > 0) ? (int)$_GET['page'] : 1;
		$per_page = 20;

		if($linked_status === "linked"){
			$where[] = "e.is_linked_school = 1";
		}elseif($linked_status === "unlinked"){
			$where[] = "e.is_linked_school = 0";
		}

		if($keyword !== ""){
			$keyword_sql = $db->escapestring($keyword);
			$where[] = "(e.company_name LIKE '%".$keyword_sql."%' OR e.tax_code LIKE '%".$keyword_sql."%' OR reps.representative_name LIKE '%".$keyword_sql."%')";
		}

		$base_sql = "FROM hicrm_employers e
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
					reps.representative_name,
					reps.representative_email,
					reps.representative_phone
					".$base_sql."
					ORDER BY e.id DESC
					LIMIT ".$offset.",".$per_page);

		$this->view->data['active_menu'] = "employers";
		$this->view->data['employers'] = $db->fetch_object();
		$this->view->data['keyword'] = $keyword;
		$this->view->data['linked_status'] = $linked_status;
		$this->view->data['page'] = $page;
		$this->view->data['per_page'] = $per_page;
		$this->view->data['total_employers'] = $total_employers;
		$this->view->data['total_pages'] = $total_pages;
		$this->view->admintmp('employyer');
	}

	public function employers($para = ''){
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
			$this->view->data['roles'] = $userModel->role_user();
			$this->view->data['user'] = $userModel->get_user($para[2]);
			$this->view->data['role_detail'] = $userModel->role_user_detail($userModel->get_user($para[2])->user_group);
			$this->view->data['user_roles'] = $userModel->get_user_role();
			$this->view->data['method'] = 'role';
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
		if(isset($method) && $method == 'add'){
			$this->view->data['method'] = 'add';
			$this->view->show("backend/event-add");


		}elseif(isset($method) && $method == 'edit'){
			$db->query("SELECT * FROM hicrm_events WHERE id = '".$id."'");		
			$event_detai = $db->fetch_object(true);
			// echo $event_detai->event_name;
			$this->view->data['event_detail'] = $event_detai;
			$this->view->data['method'] = 'edit';
			$this->view->show("backend/event-add");
		}elseif(isset($method) && $method == 'detail'){
			$db->query("SELECT * FROM hicrm_events WHERE id = '".$id."'");		
			$event_detai = $db->fetch_object(true);
			// echo $event_detai->event_name;
			$this->view->data['event_detail'] = $event_detai;
			$this->view->data['method'] = 'edit';
			$this->view->show("backend/event-detail");
		}
		else{
		$dmtype = $db->fetch_object();
		$this->view->data["events"] = $events;
		// $this->view->data["dmtype"] = $dmtype;
		$this->view->show("backend/events");
		}
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
