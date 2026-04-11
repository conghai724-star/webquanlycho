<?php
Class reportController extends baseController
{
	public function index()
	{
		if(!(isset($_SESSION['staff']['id']) && $_SESSION['staff']['id'] != "")){ header("Location: ".XC_URL."/admin/login"); }
		
		global $db;
		$to = date("Y-m-d");
		$from = date("Y-m-d",strtotime("-30 days"));
		$sqluser = ($_SESSION['staff']['group'] != 1)? "AND user_staff = '".$_SESSION['staff']['id']."'" : "";
		$db->query("SELECT count(*) as countcus FROM ow_users WHERE DATE(user_created_time) BETWEEN '".$from."' AND '".$to."' ".$sqluser);
		$this->view->data["countcus"] = $db->fetch_object(true)->countcus;
		$sqlorder = ($_SESSION['staff']['group'] != 1)? "AND order_staff = '".$_SESSION['staff']['id']."'" : "";
		$db->query("SELECT count(*) as countorder FROM ow_orders WHERE 
		DATE(order_time) BETWEEN '".$from."' AND '".$to."' ".$sqlorder);
		$this->view->data["countorder"] = $db->fetch_object(true)->countorder;
		$sqltrans = ($_SESSION['staff']['group'] != 1)? "AND u.user_staff = '".$_SESSION['staff']['id']."'" : "";
		$db->query("SELECT sum(trans_amount) as sumdeposite FROM ow_transactions as t
		LEFT JOIN ow_users as u ON t.uid = u.id
		WHERE trans_type = 1 AND trans_status = 2 AND DATE(trans_time) BETWEEN '".$from."' AND '".$to."' ".$sqltrans);
		$this->view->data["sumdeposite"] = $db->fetch_object(true)->sumdeposite;
		$db->query("SELECT sum(order_total) as sumorder FROM ow_orders WHERE order_status > 1 AND DATE(order_time) BETWEEN '".$from."' AND '".$to."' ".$sqlorder);
		$this->view->data["sumorder"] = $db->fetch_object(true)->sumorder;
		$this->view->data["select_title"] = "30 ngày qua";
		$rto = $newdate = date("Y-m-d");
		
		$rfrom = date("Y-m-d",strtotime("-6 days"));
		$daydata = array();
		$orderdata = array();
		$orderdata2 = array();
		
		for($i = 6;$i >= 0 ;$i--)
		{	
			array_push($daydata,"'".date("d/m",strtotime($newdate))."'");
			array_push($orderdata,$this->countorderbydate($newdate));
			array_push($orderdata2,$this->countorderbydatedeposited($newdate));
			$newdate = date("Y-m-d",strtotime($newdate." -1 day"));
			
		}
		
		$this->view->data["day_data"] = implode(",",$daydata);
		$this->view->data["order_data"] = implode(",",$orderdata);
		$this->view->data["order_data2"] = implode(",",$orderdata2);
		
		$db->query("SELECT *, o.id as oid,staff.user_fullname as staff_fullname FROM ow_orders as o
		LEFT JOIN ow_users as u ON o.uid = u.id
		LEFT JOIN ow_order_status as ot ON o.order_status = ot.id
		LEFT JOIN hicrm_users as staff ON o.order_staff = staff.id
		WHERE o.order_staff = '".$_SESSION['staff']['id']."'
		ORDER BY o.order_time DESC LIMIT 10");
		$this->view->data["orders"] = $db->fetch_object();
		$this->view->show("report");
	}
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
}