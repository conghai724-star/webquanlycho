<?php
Class orderController extends baseController
{
    public function index()
    {
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		
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
		$db->query("SELECT *, o.id as oid,staff.user_fullname as staff_fullname FROM ow_orders as o
		LEFT JOIN ow_users as u ON o.uid = u.id
		LEFT JOIN ow_order_status as ot ON o.order_status = ot.id
		LEFT JOIN hicrm_users as staff ON o.order_staff = staff.id
		ORDER BY o.order_time DESC LIMIT ".$cp*$spp.",".$spp);
		$this->view->data["orders"] = $db->fetch_object();
		$this->view->data['totalpost'] = $totalsms;
		$this->view->data["page"] = $page;
		$this->view->data["spp"] = $spp;
		$this->view->data['totalpage'] = $totalpage;
		$this->view->data["page_name"] = "sms_list";
		$this->view->data["page_title"] = "Danh sách SMS";
		$this->view->show("orders");
    }
	public function detail($para)
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$code = $para[1];
		$db->query("SELECT *,o.id as oid FROM ow_orders as o
		LEFT JOIN ow_order_status as os ON o.order_status = os.id
		WHERE order_code = '".$code."' ORDER BY o.order_time DESC LIMIT 1");
		if($db->num_row())
		{
			$this->view->data["order"] = $order = $db->fetch_object(true);
			$db->query("SELECT * FROM ow_order_items WHERE oid = '".$order->oid."'");
			$this->view->data["items"] = $db->fetch_object();
			$db->query("SELECT * FROM ow_users as u
			LEFT JOIN ow_customer_address as ud ON u.id = ud.uid
			LEFT JOIN ow_wards as w ON ud.wardid = w.id
			LEFT JOIN ow_districts as d ON ud.districtid = d.id
			LEFT JOIN ow_provinces as p ON ud.provinceid = p.id
			WHERE u.id = '".$order->uid."'");
			$this->view->data["order_user"] = $db->fetch_object(true);
			$db->query("SELECT * FROM ow_transactions WHERE trans_type = '2' AND trans_status = 2 AND trans_data = '".$order->oid."' ORDER BY trans_time DESC");
			$this->view->data["transactions"] = $db->fetch_object();
			$db->query("SELECT * FROM ow_order_updates as ou
			LEFT JOIN hicrm_users as u ON ou.uid = u.id
			LEFT JOIN ow_update_type as ut ON ou.update_type = ut.id
			WHERE oid = '".$order->oid."' ORDER BY update_time DESC");
			$this->view->data["updates"] = $db->fetch_object();
			
			$db->query("SELECT * FROM ow_order_notes as n
			LEFT JOIN hicrm_users as u ON n.uid = u.id
			WHERE n.oid = '".$order->oid."' ORDER BY n.note_time DESC");
			$this->view->data["notes"] = $db->fetch_object();
			$db->query("SELECT *  FROM hicrm_users as u
			WHERE user_group IN (2,3)
			ORDER BY user_register_time DESC");
			$this->view->data["staff"] = $db->fetch_object();
			$this->view->show("order_detail");
		}
		else
		{
			header("Location: ".XC_URL."/order");
		}
	}
	public function scan()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		$this->view->show("scan");
	}
	public function packages()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$db->query("SELECT *, p.id as pid FROM ow_packages as p
		LEFT JOIN hicrm_users as u ON p.pack_created_by = u.id
		LEFT JOIN ow_package_status as s ON p.pack_status = s.id
		LEFT JOIN ow_package_type as t ON p.pack_type = t.id
		ORDER BY pack_create_time DESC");
		$this->view->data["packs"] = $db->fetch_object();
		$db->query("SELECT * FROM ow_package_type");
		$this->view->data["packtype"] = $db->fetch_object();
		$db->query("SELECT * FROM ow_package_status");
		$this->view->data["packstatus"] = $db->fetch_object();
		$this->view->show("packages");
	}
	public function package($para)
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		global $db;
		$code = $para[1];
		$db->query("SELECT *, p.id as pid FROM ow_packages as p
		LEFT JOIN hicrm_users as u ON p.pack_created_by = u.id
		LEFT JOIN ow_package_status as s ON p.pack_status = s.id
		LEFT JOIN ow_package_type as t ON p.pack_type = t.id
		WHERE pack_code = '".$code."' LIMIT 1
		");
		$this->view->data["pack"] = $pack = $db->fetch_object(true);
		$db->query("SELECT * FROM ow_package_items as pi
		LEFT JOIN ow_order_items as oi ON pi.itemid = oi.id
		LEFT JOIN ow_orders as o ON oi.oid = o.id
		WHERE pid = '".$pack->pid."' ORDER BY pi_time DESC");
		$this->view->data["countitem"] = $db->num_row();
		$this->view->data["pack_item"] = $db->fetch_object();
		$db->query("SELECT * FROM ow_package_tracking as pt
		LEFT JOIN hicrm_users as u ON pt.staffid = u.id
		WHERE pid = '".$pack->pid."' ORDER BY tracking_time DESC");
		$this->view->data["pack_tracking"] = $db->fetch_object();
		$this->view->show("package");
	}
}




