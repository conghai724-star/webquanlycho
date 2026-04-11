<?php
Class appController extends baseController
{
    public function index()
    {
	}
	public function setting($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		$this->view->data["active_menu"] = "settings";
		$this->view->data["pagetitle"] = "Thiết lập hệ thống";
		$this->view->show("backend/settings");
	}
	public function promotions($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		$db->query("SELECT * FROM hicrm_promotions as p ORDER BY p.id DESC");
		$this->view->data["promotions"] = $db->fetch_object();
		$this->view->data["active_menu"] = "promotions";
		$this->view->data["pagetitle"] = "Khuyến mãi";
		$this->view->show("promotions");
	}
	public function quotes($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		if(isset($para) && $para[1] == "new")
		{
			$prefix = $this->helper->get_config("QUOTE_PREFIX");
			$db->query("SELECT * FROM hicrm_quotes ORDER BY id DESC LIMIT 1");
			$quote_code = $db->fetch_object(true)->quote_code;
			$quote_code = substr($quote_code,-7);
			$quote_code = $quote_code+1;
			$quote_code = $prefix."".str_pad($quote_code, 7, '0', STR_PAD_LEFT);
			
			$db->query("SELECT * FROM hicrm_employees WHERE employee_status NOT IN(99)");
			$employees = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_customers WHERE customer_status NOT IN(99)");
			$customers = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_category_products WHERE cat_product_status NOT IN(99)");
			$category_product = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_units WHERE unit_status NOT IN(99)"); 
			$units = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_accounts WHERE account_status NOT IN(99)");
			$accounts = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_payment_policies WHERE policy_status NOT IN(99)");
			$payments = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_warehouses WHERE warehouse_status NOT IN(99)");
			$warehouses = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_promotions as p WHERE p.promo_status = 1 ORDER BY p.id DESC");
			$this->view->data["promotions"] = $db->fetch_object();
			$this->view->data['quote_code'] = $quote_code;
			$this->view->data['warehouse'] = $warehouses;
			$this->view->data['payments'] = $payments;
			$this->view->data['units'] = $units;
			$this->view->data['accounts'] = $accounts;
			$this->view->data['category_product'] = $category_product;
			$this->view->data['customers'] = $customers;
			$this->view->data['employees'] = $employees;
			$this->view->show("add-quote");
		}
		elseif(isset($para) && $para[1] == "detail")
		{
			
		}
		else
		{
			$db->query("SELECT *,q.id as qid, (SELECT SUM(quote_product_total) FROM hicrm_quote_details WHERE qid = q.id) as qtotal FROM hicrm_quotes as q 
			LEFT JOIN hicrm_customers as c ON q.quote_customer = c.id
			LEFT JOIN hicrm_users as u ON q.quote_created_by = u.id
			LEFT JOIN hicrm_status as st ON q.quote_status = st.id
			ORDER BY q.id DESC");
			$this->view->data["quotes"] = $db->fetch_object();
			$this->view->data["active_menu"] = "quotes";
			$this->view->data["pagetitle"] = "Danh sách báo giá";
			$this->view->show("quotes");
		}
	}
	public function products($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		if(isset($para) && $para[1] == "new")
		{
			$this->view->show("product_new");
		}
		elseif(isset($para) && $para[1] == "detail")
		{
			
		}
		else
		{
			$db->query("SELECT *, p.id as pid, (SELECT SUM(ware_instock) FROM hicrm_product_warehouses WHERE pid = p.id) as totalinstock FROM hicrm_products as p
			LEFT JOIN hicrm_product_categories as c ON p.product_category = c.id
			LEFT JOIN hicrm_units as u ON p.product_unit = u.id
			LEFT JOIN hicrm_taxs as t ON p.product_tax_id = t.id
			ORDER BY p.id DESC
			");
			$this->view->data["products"] = $db->fetch_object();
			$this->view->data["active_menu"] = "products";
			$this->view->data["pagetitle"] = "Danh sách sản phẩm";
			$this->view->show("products");
			//Danh sách sản phẩm
		}
	}
	//Cong cu dung cu
	public function toolinstruments($para){
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		$this->view->data["active_menu"] = "toolinstruments";
		$title = "Quản lý công cụ dụng cụ";
		$this->view->data["pagetitle"] = $title;
		$this->view->show('tool_instruments');
	}
	//Mua hàng
	public function orders($para){
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		if(isset($para[1]) && $para[1] != ''){
			global $db;
			switch($para[1]){
				case "addorders":
                    //tạo số đơn hàng
                    $db->query("SELECT * FROM hicrm_orders ORDER BY id DESC LIMIT 1");
                    $order_code = $db->fetch_object(true)->order_code;
                    $prefix = $this->helper->get_config("order_prefix");
                    //PREFIX1234567
                    $order_code = substr($order_code,-7);
                    $order_code = $order_code+1;
                    $order_code = $prefix."".str_pad($order_code, 7, '0', STR_PAD_LEFT);
					$db->query("SELECT * FROM hicrm_employees WHERE employee_status NOT IN(99)");
					$employees = $db->fetch_object();
					
					$db->query("SELECT * FROM hicrm_customers WHERE customer_status NOT IN(99)");
					$customers = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_category_products WHERE cat_product_status NOT IN(99)");
					$category_product = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_units WHERE unit_status NOT IN(99)"); 
					$units = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_accounts WHERE account_status NOT IN(99)");
					$accounts = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_payment_policies WHERE policy_status NOT IN(99)");
					$payments = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_warehouses WHERE warehouse_status NOT IN(99)");
					$warehouses = $db->fetch_object();
                    $this->view->data['order_code'] = $order_code;
					$this->view->data['warehouse'] = $warehouses;
					$this->view->data['payments'] = $payments;
					$this->view->data['units'] = $units;
					$this->view->data['accounts'] = $accounts;
					$this->view->data['category_product'] = $category_product;
					$this->view->data['customers'] = $customers;
					$this->view->data['employees'] = $employees;
				$title = "Thêm đơn mua hàng";
				$this->view->data['title'] = $title;
				$page = "add-order";
				break;
				case "order":
					$title = "Đơn mua hàng";  
					$page = "orders"; 
				break; 
				default:
				break;
			
			}
			$this->view->data["active_menu"] = "orders";
			$this->view->data["pagetitle"] = $title;
			$this->view->data["page"] = $page;
			$this->view->show($page);
		}
	}
	//end
	//Kho hang
	public function warehouse(){
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		//Mã Kho
		$db->query("SELECT * FROM hicrm_warehouses ORDER BY id DESC LIMIT 1");
		$lastno = $db->fetch_object(true)->warehouse_code;
		$prefix = $this->helper->get_config("warehouse_prefix");
		//PREFIX1234567
		$lastno = substr($lastno,-7);
		$lastno = $lastno+1;
		$lastno = $prefix."".str_pad($lastno, 7, '0', STR_PAD_LEFT);
		$db->query("SELECT * FROM hicrm_branchs ");
		$data_branchs = $db->fetch_object(true);
		$name_branch = $data_branchs->branch_name;
		
		$db->query("SELECT *,wh.id as whid FROM hicrm_warehouses as wh LEFT JOIN hicrm_status as s ON wh.warehouse_status = s.id WHERE wh.warehouse_status NOT IN(99) ");
		$warehouses = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_branchs");
		$branchs = $db->fetch_object();
		$this->view->data['branchs'] = $branchs;
		$this->view->data['warehouse_code'] = $lastno;
		$this->view->data['name_branch'] = $name_branch;
		$this->view->data['warehouses'] = $warehouses;
		$this->view->show('warehouse');
	}
	//end
	//Tài sản cố định
	public function fixedAssets(){
		if(isset($para[1]) && $para[1] != ''){
			global $db;
			switch($para[1]){
				case "categories" :
					$db->query('SELECT * FROM hicrm_fixed_asset_categories WHERE fixed_cat_status NOT IN(99)');
					$this->view->data['data_fixed_cat'] = $db->fetch_object();
					$page = 'fixed_asset_categories';
					$title = "Loại tài sản cố định";
				break;
			}
			$this->view->data['page'] = $page;
			$this->view->data['title'] = $title;
			$this->view->show($page);
		}
	}
	//end
	public function booking($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		if(isset($para[1]) && $para[1] != "")
		{
		}
		else
		{
			$db->query("SELECT * FROM hicrm_employees");
			$employees = $db->fetch_object();
			$this->view->data["employees"] = $employees;
			$this->view->data["active_menu"] = "booking";
			$this->view->data["pagetitle"] = "Quản lý phòng họp";
			$this->view->show("bookings");
		}
	}
	public function categories($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
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
				case "customergroup":
				{
					$db->query("SELECT *, g.id as gid FROM hicrm_customer_groups as g
					LEFT JOIN hicrm_status as s ON g.group_status = s.id
					WHERE g.group_status NOT IN(99) ORDER BY g.id ASC");
					$this->view->data["groups"] = $db->fetch_object();
					
					$page = "category_customergroup";
					$title = "Nhóm Khách hàng/NCC";
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
				case "supplies":
				{
					$db->query("SELECT *, s.id as sid FROM hicrm_supplies as s LEFT JOIN hicrm_status as st ON s.supplie_status = st.id WHERE s.supplie_status NOT IN(99) ORDER BY s.id ASC");
					$this->view->data["supplies"] = $db->fetch_object();
					$page = "category_supplies";
					$title = "Nhóm vật tư";
					break;
				}
				case "bankaccounts":
				{
					$db->query("SELECT *, ba.id as baid, b.id as bankid FROM hicrm_bank_accounts as ba
					LEFT JOIN hicrm_status as st ON ba.ba_status = st.id 
					LEFT JOIN hicrm_banks as b ON ba.bank_id = b.id WHERE ba.ba_status NOT IN(99) ORDER BY ba.id ASC");
					
					$this->view->data["bankaccounts"] = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_banks");
					$this->view->data['banks'] = $db->fetch_object();
					$page = "category_bank_accounts";
					$title = "Danh sách tài khoản ngân hàng";
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
				case "currencies":
				{
					$db->query("SELECT *, cu.id as cuid FROM hicrm_currencies as cu
					LEFT JOIN hicrm_status as st ON cu.currency_status = st.id
					LEFT JOIN hicrm_accounts as a ON cu.currency_type = a.id
					WHERE cu.currency_status NOT IN(99) ORDER BY cu.id ASC");
					$this->view->data["currencies"] = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_accounts WHERE account_status = 1 ORDER BY id ASC");
					$this->view->data["accounts"] = $db->fetch_object();
					$page = "category_currencies";
					$title = "Danh sách loại tiền ";
					break;
				}
				case "products":
				{
					$db->query("SELECT *, p.id as pid FROM hicrm_category_products as p
					LEFT JOIN hicrm_status as st ON p.cat_product_status = st.id
					LEFT JOIN hicrm_units as u ON p.cat_product_unit = u.id
					WHERE p.cat_product_status NOT IN(99) ORDER BY p.id ASC");
					$this->view->data["products"] = $db->fetch_object();
					$page = "category_products";
					$title = "Danh sách danh mục sản phẩm ";
					break;
				}
				case "spendcollectes":
				{
					$db->query("SELECT *, sc.id as scid FROM hicrm_spend_collectes as sc
					LEFT JOIN hicrm_status as st ON sc.spend_collecte_status = st.id
					WHERE sc.spend_collecte_status NOT IN(99) ORDER BY sc.id ASC");
					$this->view->data["spend_collectes"] = $db->fetch_object();
					$page = "category_spend_collectes";
					$title = "Mục thu/Chi ";
					break;
				}
				case "paymentpolicies":
				{
					$db->query("SELECT *, p.id as pid FROM hicrm_payment_policies as p
					LEFT JOIN hicrm_status as st ON p.policy_status = st.id
					WHERE p.policy_status NOT IN(99) ORDER BY p.id ASC");
					$this->view->data["paymentpolicies"] = $db->fetch_object();
					$page = "category_payment_policies";
					$title = "Điều khoản thanh toán";
					break;
				}
			case "expenseitems":
				{
					$db->query("SELECT *, ex.id as exid FROM hicrm_expense_items as ex
					LEFT JOIN hicrm_status as st ON ex.expense_status = st.id
					WHERE ex.expense_status NOT IN(99) ORDER BY ex.id DESC");
					$this->view->data["expenseitems"] = $db->fetch_object();
					
					$page = "category_expense_items";
					$title = "Khoản mục chi phí";
					
					break;
				}
				case "templatetypes":
				{
					$db->query("SELECT *, tt.id as ttid FROM hicrm_template_types as tt
					LEFT JOIN hicrm_status as st ON tt.template_type_status = st.id
					WHERE tt.template_type_status NOT IN(99) ORDER BY tt.id DESC");
					$this->view->data["templatetypes"] = $db->fetch_object();
					$db->query("SELECT * FROM hicrm_accounts WHERE account_status = 1 ORDER BY id ASC");
					$this->view->data["accounts"] = $db->fetch_object();
					
					$page = "category_template_types";
					$title = "Loại chứng từ";
					
					break;
				}
				case "template":
				{
					$db->query("SELECT * FROM hicrm_templates ORDER BY id ASC");
					$this->view->data["templates"] = $db->fetch_object();
					$page = "category_template";
					$title = "Mẫu chứng từ";
					break;
				}
				default:
				{
					break;
				}
			}
			$this->view->data["active_menu"] = "categories";
			$this->view->data["pagetitle"] = $title;
			$this->view->data["page"] = $page;
			$this->view->show($page);
		}
		else
		{
			header("Location: ".XC_URL);
		}
	}
	public function incomes($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		if(isset($para[1]) && $para[1] == "new")
		{	
			
			$type = $_GET['type'];
			if($type == 1)
			{
				$income_type = "Thu tiền Khách hàng";
			}
			elseif($type == 2)
			{
				$income_type = "Thu hoàn ứng nhân viên";
			}
			$db->query("SELECT * FROM hicrm_incomes ORDER BY id DESC LIMIT 1");
			$lastno = $db->fetch_object(true)->income_no;
			$prefix = $this->helper->get_config("income_prefix");
			//PREFIX1234567
			$lastno = substr($lastno,-7);
			$lastno = $lastno+1;
			$lastno = $prefix."".str_pad($lastno, 7, '0', STR_PAD_LEFT);
			$db->query("SELECT * FROM hicrm_customers WHERE customer_status = 1");
			$customers = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_employees");
			$employees = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_accounts WHERE account_status = 1 ORDER BY id ASC");
			$accounts = $db->fetch_object();
			$this->view->data["income_type_title"] = $income_type;
			$this->view->data["income_type"] = $type;
			$this->view->data["income_code"] = $lastno;
			$this->view->data["customers"] = $customers;
			$this->view->data["accounts"] = $accounts;
			$this->view->data["employees"] = $employees;
			$this->view->data["active_menu"] = "income";
			$this->view->data["pagetitle"] =  $income_type;
			$this->view->show("add-income");
		}
		else
		{
			$db->query("SELECT *,i.id as incomeid,(SELECT SUM(income_amount) FROM hicrm_income_details WHERE income_id = i.id) as sumamount FROM hicrm_incomes as i
			LEFT JOIN hicrm_income_types as it ON i.income_type = it.id
			ORDER BY i.income_accounting_date DESC");
			
			$this->view->data["incomes"] = $db->fetch_object();
			$this->view->data["active_menu"] = "income";
			$this->view->data["pagetitle"] =  "Danh sách phiếu thu";
			$this->view->show("incomes");
			
		}
	}
	
	public function customers($para)
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		
		if(isset($para[1]) && $para[1] == "detail"){
			$id = $para[2];
			global $db;
			$db->query("SELECT * FROM hicrm_customers WHERE id = '".$id."'");
			$user = $db->fetch_object(true);
			$this->view->data['user'] = $user;
			$this->view->show('customer-detail');
		}else{
		$str = '';
		
		if(isset($_GET['keyword']) && $_GET['keyword'] != ''){
			$keyword = $_GET['keyword'];
			$str .= " AND c.customer_name LIKE '%".$keyword."%' OR c.customer_code LIKE '%".$keyword."%' OR c.customer_phone LIKE '%".$keyword."%' OR c.customer_email LIKE '%".$keyword."%'";
		}
		if(isset($_GET['customer_group']) && $_GET['customer_group'] != 0){
			$customer_group = $_GET['customer_group'];
			$str .= " AND c.customer_group = '".$customer_group."'";
			
		}
		if(isset($_GET['customer_status']) && $_GET['customer_status'] != 0){
			$customer_status = $_GET['customer_status'];
			$str .= " AND c.customer_status = '".$customer_status."'";
			
		}
		$db->query("SELECT *,c.id as cid FROM hicrm_customers as c
		LEFT JOIN hicrm_status as s ON c.customer_status = s.id
		WHERE NOT(c.customer_status = 99 ) ".$str."
		ORDER BY c.id DESC");
		$customers = $db->fetch_object();
		if(empty($customers)){
			$db->query("SELECT *,c.id as cid FROM hicrm_customers as c
			LEFT JOIN hicrm_status as s ON c.customer_status = s.id
			WHERE NOT(c.customer_status = 99 )
			ORDER BY c.id DESC");
		$customers = $db->fetch_object();
		}
		$this->view->data["customers"] = $customers;
		
		
		$this->view->show("backend/customers");
		}
		
	}
	//register
	public function register(){
		//$this->view->show('register');
	}
	//end
	
	
	//customers
	
	public function editcustomer($para){
	
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		$id = $para[1];
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
		$db->query("SELECT * FROM hicrm_customers WHERE id = '".$id."'");
		$customer = $db->fetch_object(true);
		$db->query("SELECT * FROM hicrm_employees WHERE employee_status NOT IN(99)");
		$customer_staff = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_customer_groups WHERE group_status NOT IN(99)');
		$customer_groups = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_branchs  ORDER BY id ASC');
		$branches = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments WHERE depart_status NOT IN(99)");
		$departments = $db->fetch_object();
		$this->view->data['departments'] = $departments;
		$this->view->data['positions'] = $positions;
		$this->view->data['branches'] = $branches;
		$this->view->data['customer_staff'] = $customer_staff;
		$this->view->data["customer_code"] = $lastno;
		$this->view->data["employee_code"] = $lastno2;
		$this->view->data['customer_groups'] = $customer_groups;
		$this->view->data['customer_staff'] = $customer_staff;
		$this->view->data['customer'] = $customer;
		$this->view->show('backend/edit-customer');
	}
	public function addcustomer()
	{	
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
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
		$db->query('SELECT * FROM hicrm_customer_groups WHERE group_status NOT IN(99)');
		$customer_groups = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_employees WHERE employee_status NOT IN(99)");
		$customer_staff = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
		$branches = $db->fetch_object();
		$db->query('SELECT * FROM hicrm_positions');
		$positions = $db->fetch_object();
		$db->query("SELECT * FROM hicrm_departments WHERE depart_status NOT IN(99)");
		$departments = $db->fetch_object();
		$this->view->data['departments'] = $departments;
		$this->view->data['positions'] = $positions;
		$this->view->data['branches'] = $branches;
		$this->view->data['customer_groups'] = $customer_groups;
		$this->view->data['customer_staff'] = $customer_staff;
		$this->view->data["customer_code"] = $lastno;
		$this->view->data["employee_code"] = $lastno2;
		
		$this->view->show("add-customer");
	}
	
	public function profile($para){
		
		$id = $para[1];
		echo $para[1] ."/" . $para[2];
		global $db;
		$db->query("SELECT * FROM hicrm_customers WHERE id = '".$id."'");
		$user = $db->fetch_object(true);
		$this->view->data['user'] = $user;
		//$this->view->show('profile');
	}
	//end
	//employees
	public function employees($para=""){
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
			global $db;
			//Ma NV
			$db->query("SELECT * FROM hicrm_employees ORDER BY id DESC LIMIT 1");
			$lastno2 = $db->fetch_object(true)->employee_code;
			$prefix_employee = $this->helper->get_config("employee_prefix"); 
			//PREFIX1234567
			$lastno2 = substr($lastno2,-3);
			$lastno2 = $lastno2+1;
			$lastno2 = $prefix_employee."".str_pad($lastno2, 3, '0', STR_PAD_LEFT);
		if(isset($para[1]) && $para[1] == "detail" ){
			$id = $para[2];
			$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
			LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
			LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
			LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
			WHERE e.id = '".$id."'");
			$employee = $db->fetch_object(true);		
			$this->view->data['employee'] = $employee;
			$this->view->show('backend/employee-detail');
		}else{
			$str = '';
			if(isset($_GET['keyword']) && $_GET['keyword'] != ''){
				$keyword = $_GET['keyword'];
				$str .= " AND e.employee_name LIKE '%".$keyword."%' OR e.employee_code = '".$keyword."' ";
			}
			if(isset($_GET['department']) && $_GET['department'] != 0){
				$department = $_GET['department'];
				$str .= " AND e.employee_department = '".$department."'  ";
			}
			if(isset($_GET['position']) && $_GET['position'] != 0){
				$position = $_GET['position'];
				$str .= " AND e.employee_position = '".$position."'  ";
			}
			
			$db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
			LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
			LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
			LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
			WHERE e.employee_status NOT IN(99) ".$str." ORDER BY e.id DESC");
			$employees = $db->fetch_object();
			//print_r($employees);
			$db->query('SELECT * FROM hicrm_branchs ORDER BY id ASC');
			$branches = $db->fetch_object();
			$db->query('SELECT * FROM hicrm_positions');
			$positions = $db->fetch_object();
			$db->query("SELECT * FROM hicrm_departments WHERE depart_status = 1");
			$departments = $db->fetch_object();
			
			$this->view->data["employee_code"] = $lastno2;
			$this->view->data['employees'] = $employees;
			$this->view->data['branches'] = $branches;
			$this->view->data['positions'] = $positions;
			$this->view->data['departments'] = $departments;
			$this->view->show('backend/employees');
		}
	}
	public function editEmployee($para){
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
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
		$db->query("SELECT * FROM hicrm_departments WHERE depart_status = 1");
		$departments = $db->fetch_object();
		$this->view->data['employees'] = $employees;
		$this->view->data['branches'] = $branches;
		$this->view->data['positions'] = $positions;
		$this->view->data['employee'] = $employee;
		$this->view->data['departments'] = $departments;
		$this->view->show('backend/edit-employee');
	}
	public function calendarEmployee($para){
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		$id = $para[1];
		global $db;
	}
	
}