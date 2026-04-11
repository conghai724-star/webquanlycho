<?php
Class userController extends baseController
{
    public function index()
    {
		//if(!(isset($_SESSION['xID']) && $_SESSION['xID'] != "")){ header("Location: ".XC_URL."/member/login"); }
		//$this->view->show("member_show");
    }
	public function post()
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		$this->view->show("new-post");
	}
	public function profile()
	{
		
		global $db;
		if(isset($_POST['uid']) && $_POST['uid'] != "")
		{
			//var_dump($_POST);
			if($_POST['uid'] == $_SESSION['user']['id'])
			{
				$db->query("SELECT * FROM hicrm_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
				if($db->num_row())
				{
					
					$updatevalue = $_POST['updatevalue'];
					$listupdate = explode(',',$updatevalue);
					//var_dump($listupdate);
					foreach($listupdate as $key)
					{
						$db->query("UPDATE hicrm_users SET ".$key." = '".$_POST[$key]."' WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
					}
				}
			}
			
			
			header("Location: ".XC_URL."/trang-ca-nhan.html");
		}
		else
		{
			if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
			$db->query("SELECT * FROM hicrm_users WHERE id = '".$_SESSION['user']['id']."' LIMIT 1");
			$this->view->data["user"] = $db->fetch_object(true);
			
			//$db->query("SELECT * FROM sms_transactions WHERE trans_uid = '".$_SESSION['user']['id']."' ORDER BY trans_date DESC");
			//$this->view->data["transactions"] = $db->fetch_object();
			$this->view->data["page_name"] = "user";
			$this->view->data["page_title"] = "Thông tin cá nhân";
			$this->view->show("profile");
		}
	}
	public function listing()
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		$db->query("SELECT *, s.id as pid FROM bds_posts as s 
		LEFT JOIN hicrm_provinces as p ON s.post_province = p.id
		LEFT JOIN hicrm_districts as d ON s.post_district = d.id
		LEFT JOIN hicrm_wards as w ON s.post_ward = w.id
		WHERE s.post_author = '".$_SESSION['user']['id']."'
		ORDER BY s.post_create_time DESC
		");
		$this->view->data["count_listing"] = $db->num_row();
		$this->view->data["listing"] = $db->fetch_object();
		$this->view->data["page_name"] = "user";
		$this->view->data["page_title"] = "Tin đăng của bạn";
		$this->view->data["sub_page"] = "user_listing";
		$this->view->show("user_listing");
	}
	public function favorite()
	{
		if(!(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "")){ header("Location: ".XC_URL."/login"); }
		global $db;
		$db->query("SELECT *, s.id as pid FROM bds_posts as s 
		LEFT JOIN hicrm_provinces as p ON s.post_province = p.id
		LEFT JOIN hicrm_districts as d ON s.post_district = d.id
		LEFT JOIN hicrm_wards as w ON s.post_ward = w.id
		WHERE s.id IN (SELECT pid FROM bds_user_favorites WHERE uid = '".$_SESSION['user']['id']."') AND post_type = 1
		ORDER BY s.post_create_time DESC
		");
		$this->view->data["favorites_sale"] = $db->fetch_object();
		$db->query("SELECT *, s.id as pid FROM bds_posts as s 
		LEFT JOIN hicrm_provinces as p ON s.post_province = p.id
		LEFT JOIN hicrm_districts as d ON s.post_district = d.id
		LEFT JOIN hicrm_wards as w ON s.post_ward = w.id
		WHERE s.id IN (SELECT pid FROM bds_user_favorites WHERE uid = '".$_SESSION['user']['id']."') AND post_type = 2
		ORDER BY s.post_create_time DESC
		");
		$this->view->data["favorites_rent"] = $db->fetch_object();
		$this->view->data["page_name"] = "user";
		$this->view->data["page_title"] = "Quan tâm";
		$this->view->data["sub_page"] = "user_favorite";
		$this->view->show("user_favorite");
	}
}