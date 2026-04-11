<?php

Class serviceController Extends baseController
{
	public function index()
    {
        
	}
	public function send()
	{
		$apikey = $_POST['api_key'];
		$phone = $_POST['phone'];
		$message = $_POST['message'];
		global $db;
		$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
		$uid = $db->fetch_object(true)->uid;
		$db->query("SELECT * FROM sms_users WHERE id = '".$uid."' LIMIT 1");
		$user = $db->fetch_object(true);
		if($user->user_balance < 1000)
		{
			$result["status"] = "503";
			$result["message"] = "Số dư tài khoản không đủ để gửi tin";
			$result["data"] = array();	
		}
		else
		{
			$db->query("INSERT INTO sms_sents(sent_uid,sent_to,sent_at,sent_content,sent_status) VALUES('".$uid."','".$phone."','".date("Y-m-d H:i:s")."','".$message."',0)");
			$db->query("UPDATE sms_users SET user_balance = user_balance - 1000 WHERE id = '".$uid."'");
			$result["status"] = "200";
			$result["message"] = "Success";
			$result["data"] = array();	
		}
		echo json_encode($result);
	}
	public function group($para)
	{
		global $db;
		$result = array();
		if(isset($para[1]) && $para[1] != "")
		{
			if($para[1] == "add")
			{
				$apikey = $_POST['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$name = $_POST['name'];
					$db->query("SELECT * FROM sms_list WHERE uid = '".$uid."' AND list_name = '".$name."'");
					if($db->num_row())
					{
						$result["status"] = "503";
						$result["message"] = "Danh sách này đã tồn tại";
					}
					else
					{
						$db->query("INSERT INTO sms_list(uid,list_name) VALUES('".$uid."','".$name."')");
						$result["status"] = "200";
						$result["message"] = "Success";
					}
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}	
			}
			elseif($para[1] == "update")
			{
				$apikey = $_POST['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$lid = $_POST['id'];
					$db->query("SELECT * FROM sms_list WHERE uid = '".$uid."' AND lid = '".$lid."'");
					if(!$db->num_row())
					{
						$result["status"] = "503";
						$result["message"] = "Dữ liệu không tồn tại";
					}
					else
					{
						if($_POST['name'] != "")
						{
							$db->query("UPDATE sms_list SET list_name = '".$_POST['name']."' WHERE lid = ".$lid);
							$result["status"] = "200";
							$result["message"] = "Success";
						}
						else
						{
							$result["status"] = "500";
							$result["message"] = "Nội dung không hợp lệ";
						}
						
					}
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}
			}
			elseif($para[1] == "delete")
			{
				$apikey = $_POST['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$cid = $_POST['id'];
					$db->query("SELECT * FROM sms_list WHERE uid = '".$uid."' AND lid = '".$cid."'");
					if(!$db->num_row())
					{
						$result["status"] = "503";
						$result["message"] = "Dữ liệu không tồn tại";
					}
					else
					{
						$db->query("DELETE FROM sms_list WHERE uid = '".$uid."' AND lid = '".$cid."'");
						$result["status"] = "200";
						$result["message"] = "Success";
					}
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}
			}
			elseif($para[1] == "list")
			{
				$apikey = $_GET['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$groups = array();
					$db->query("SELECT * FROM sms_list WHERE uid = '".$uid."'");
					$total = $db->num_row();
					$listcontact = $db->fetch_object();
					foreach($listcontact as $c)
					{
						$subc = array();
						$subc["id"] = $c->lid;
						$subc["name"] = $c->list_name;
						array_push($groups,$subc);
					}
					$result["status"] = "200";
					$result["message"] = "Success";
					$result["total"] = $total;
					$result["data"] = $groups;
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}	
			}
			else
			{
				$result["status"] = "500";
				$result["message"] = "Thao tác không hợp lệ!";
			}
		}
		else
		{
			$result["status"] = "500";
			$result["message"] = "Thao tác không hợp lệ!";
		}
		echo json_encode($result);
	}
	public function account($para)
	{
		global $db;
		$result = array();
		if(isset($para[1]) && $para[1] != "")
		{
			if($para[1] == "balance")
			{
				$apikey = $_GET['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$groups = array();
					$db->query("SELECT * FROM sms_users WHERE id = '".$uid."'");
					$user = $db->fetch_object(true);
					$result["status"] = "200";
					$result["message"] = "Success";
					$data = array();
					$data["id"] = $uid;
					$data["balance"] = $user->user_balance;
					$data["currency"] = "VND";
					$result["data"] = $data;
					
					
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}	
			}
			else
			{
				$result["status"] = "500";
				$result["message"] = "Thao tác không hợp lệ!";
			}
		}
		else
		{
			$result["status"] = "500";
			$result["message"] = "Thao tác không hợp lệ!";
		}
		echo json_encode($result);
	}
	public function history($para)
	{
		global $db;
		$result = array();
		if(isset($para[1]) && $para[1] != "")
		{
			if($para[1] == "transactions")
			{
				$apikey = $_GET['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$trans = array();
					$db->query("SELECT * FROM sms_transactions WHERE trans_uid = '".$uid."'");
					$total = $db->num_row();
					$listtrans = $db->fetch_object();
					foreach($listtrans as $c)
					{
						$subc = array();
						$subc["code"] = $c->trans_code;
						$subc["time"] = $c->trans_date;
						$subc["type"] = ($c->trans_type == "1")? "Nạp tiền" : "Rút tiền";
						$subc["amount"] = $c->trans_amount;
						$subc["method"] = ($c->trans_method == "1")? "Chuyển khoản" : "Khác";
						$subc["note"] = $c->trans_note;
						$subc["status"] = ($c->trans_status == "1")? "Thành công" : "Đang chờ";
						array_push($trans,$subc);
					}
					$result["status"] = "200";
					$result["message"] = "Success";
					$result["total"] = $total;
					$result["data"] = $trans;
					
					
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}	
			}
			elseif($para[1] == "sent")
			{
				$apikey = $_GET['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$sents = array();
					$db->query("SELECT * FROM sms_sents WHERE sent_uid = '".$uid."' AND sent_status = 1 ORDER BY sent_created_time DESC LIMIT 100");
					$total = $db->num_row();
					$listtrans = $db->fetch_object();
					foreach($listtrans as $c)
					{
						$subc = array();
						$subc["to"] = $c->sent_to;
						$subc["create_time"] = $c->sent_created_time;
						$subc["sent_time"] = $c->sent_at;
						$subc["content"] = $c->sent_content;
						$subc["brandname"] = "BRAND_R";
						if($c->sent_status == "1")
						{
							$subc["status"] =  "Thành công";
						}
						elseif($c->sent_status == "2")
						{
							$subc["status"] =  "Không thành công";
						}
						else
						{
							$subc["status"] =  "Đang gửi";
						}
						array_push($sents,$subc);
					}
					$result["status"] = "200";
					$result["message"] = "Success";
					$result["total"] = $total;
					$result["data"] = $sents;
					
					
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}	
			}
			elseif($para[1] == "sending")
			{
				$apikey = $_GET['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$sents = array();
					$db->query("SELECT * FROM sms_sents WHERE sent_uid = '".$uid."' AND sent_status = 0 ORDER BY sent_created_time DESC LIMIT 100");
					$total = $db->num_row();
					$listtrans = $db->fetch_object();
					foreach($listtrans as $c)
					{
						$subc = array();
						$subc["to"] = $c->sent_to;
						$subc["create_time"] = $c->sent_created_time;
						$subc["sent_time"] = $c->sent_at;
						$subc["content"] = $c->sent_content;
						$subc["brandname"] = "BRAND_R";
						if($c->sent_status == "1")
						{
							$subc["status"] =  "Thành công";
						}
						elseif($c->sent_status == "2")
						{
							$subc["status"] =  "Không thành công";
						}
						else
						{
							$subc["status"] =  "Đang gửi";
						}
						array_push($sents,$subc);
					}
					$result["status"] = "200";
					$result["message"] = "Success";
					$result["total"] = $total;
					$result["data"] = $sents;
					
					
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}	
			}
			else
			{
				$result["status"] = "500";
				$result["message"] = "Thao tác không hợp lệ!";
			}
		}
		else
		{
			$result["status"] = "500";
			$result["message"] = "Thao tác không hợp lệ!";
		}
		echo json_encode($result);
	}
	public function contact($para)
	{
		global $db;
		$result = array();
		if(isset($para[1]) && $para[1] != "")
		{
			if($para[1] == "add")
			{
				$apikey = $_POST['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$firstname = $_POST['firstname'];
					$lastname = $_POST['lastname'];
					$phone = $_POST['phone'];
					$group = $_POST['group'];
					$db->query("SELECT * FROM sms_list_contacts WHERE uid = '".$uid."' AND contact_phone = '".$phone."' AND contact_list = '".$group."'");
					if($db->num_row())
					{
						$result["status"] = "503";
						$result["message"] = "Liên hệ này đã tồn tại";
					}
					else
					{
						$db->query("INSERT INTO sms_list_contacts(uid,contact_firstname,contact_lastname,contact_phone,contact_list) VALUES('".$uid."','".$firstname."','".$lastname."','".$phone."','".$group."')");
						$result["status"] = "200";
						$result["message"] = "Success";
					}
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}	
			}
			elseif($para[1] == "list")
			{
				$apikey = $_GET['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$contacts = array();
					if(isset($_GET['group']) && $_GET['group'] != "")
					{
						$sql = "SELECT * FROM sms_list_contacts WHERE uid = '".$uid."' AND contact_list = '".$_GET['group']."'";
					}
					else
					{
						$sql = "SELECT * FROM sms_list_contacts WHERE uid = '".$uid."'";
					}
					$db->query($sql);
					$total = $db->num_row();
					$listcontact = $db->fetch_object();
					foreach($listcontact as $c)
					{
						$subc = array();
						$subc["id"] = $c->cid;
						$subc["firstname"] = $c->contact_firstname;
						$subc["lastname"] = $c->contact_lastname;
						$subc["phone"] = $c->contact_phone;
						$subc["group"] = $c->contact_list;
						array_push($contacts,$subc);
					}
					$result["status"] = "200";
					$result["message"] = "Success";
					$result["total"] = $total;
					$result["data"] = $contacts;
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}	
			}
			elseif($para[1] == "delete")
			{
				$apikey = $_POST['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$cid = $_POST['id'];
					$db->query("SELECT * FROM sms_list_contacts WHERE uid = '".$uid."' AND cid = '".$cid."'");
					if(!$db->num_row())
					{
						$result["status"] = "503";
						$result["message"] = "Dữ liệu không tồn tại";
					}
					else
					{
						$db->query("DELETE FROM sms_list_contacts WHERE uid = '".$uid."' AND cid = '".$cid."'");
						$result["status"] = "200";
						$result["message"] = "Success";
					}
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}
			}
			elseif($para[1] == "remove")
			{
				$apikey = $_POST['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$cid = $_POST['id'];
					$db->query("SELECT * FROM sms_list_contacts WHERE uid = '".$uid."' AND cid = '".$cid."' AND contact_list = '".$_POST['group']."'");
					if(!$db->num_row())
					{
						$result["status"] = "503";
						$result["message"] = "Dữ liệu không tồn tại";
					}
					else
					{
						$db->query("UPDATE sms_list_contacts SET contact_list = 0 WHERE uid = '".$uid."' AND cid = '".$cid."'");
						$result["status"] = "200";
						$result["message"] = "Success";
					}
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}
			}
			elseif($para[1] == "update")
			{
				$apikey = $_POST['api_key'];
				$db->query("SELECT * FROM sms_apis WHERE api_key = '".$apikey."' LIMIT 1");
				$countrow = $db->num_row();
				if($countrow > 0)
				{
					$uid = $db->fetch_object(true)->uid;
					$cid = $_POST['id'];
					$db->query("SELECT * FROM sms_list_contacts WHERE uid = '".$uid."' AND cid = '".$cid."'");
					if(!$db->num_row())
					{
						$result["status"] = "503";
						$result["message"] = "Dữ liệu không tồn tại";
					}
					else
					{
						$sql = "";
						if($_POST['phone'] != "")
						{
							$sql .= ",contact_phone = '".$_POST['phone']."'";
						}
						if($_POST['firstname'] != "")
						{
							$sql .= ",contact_firstname = '".$_POST['firstname']."'";
						}
						if($_POST['lastname'] != "")
						{
							$sql .= ",contact_lastname = '".$_POST['lastname']."'";
						}
						if($_POST['group'] != "")
						{
							$sql .= ",contact_list = '".$_POST['group']."'";
						}
						$psql = "UPDATE sms_list_contacts SET para1 = NULL".$sql." WHERE cid = ".$cid."";
						$db->query($psql);
						$result["status"] = "200";
						$result["message"] = "Success";
					}
				}
				else
				{
					$result["status"] = "403";
					$result["message"] = "Không tìm thấy tài khoản";
				}
			}
			else
			{
				$result["status"] = "500";
				$result["message"] = "Thao tác không hợp lệ!";
			}
			
		}
		else
		{
			$result["status"] = "500";
			$result["message"] = "Thao tác không hợp lệ!";
		}
		echo json_encode($result);
	}
	public function connect()
	{
		$apikey = $_POST['api_key'];
		//$brand = $_POST['brand'];
		$result = array();
		$token = sha1(mt_rand(1, 90000) . 'SALT');
		$result["status"] = "200";
		$result["access_token"] = $token;
		$result["data"] = array();
		$result["data"]["key"] = $_POST["api_key"];
		echo json_encode($result);
	}
	public function jobs()
	{
		global $db;
		//$access_token = $_POST['access_token'];
		$result = array();
		$result["result"] = "200";
		$result["data"] = array();
		$datajob = array();
		$db->query("SELECT * FROM print_jobs WHERE job_brand = '1' AND job_status = '0' ORDER BY id");
		//{"result":"200","data":{"jobs":[{"jobid":"1","jobbrand":"1","invoice":{"invoiceid":"1","invoicecode":"TESTINVOICE002","invoicedate":"19\/09\/2019 09:09:09","invoicestaff":"THAI DINH SANG","invoicepos":"1","invoicetotal":"2,000,000","invoicetype":"1"}}]}}
		$jobs = $db->fetch_object(false);
		foreach($jobs as $job)
		{
			$listjob = array();
			$listjob["jobid"] = $job->id;
			$listjob["jobbrand"] = $job->job_brand;
			$db->query("SELECT * FROM print_invoices WHERE jobid = '".$job->id."'");
			$invoice = $db->fetch_object(true);
			$invoicedata = array();
			$invoicedata["invoiceid"] = $invoice->id;
			$invoicedata["invoicecode"] = $invoice->invoice_no;
			$invoicedata["invoicedate"] = $invoice->invoice_date;
			$invoicedata["invoicestaff"] = $invoice->invoice_staff;
			$invoicedata["invoicepos"] = $invoice->invoice_pos;
			$invoicedata["invoicetotal"] = $invoice->invoice_total;
			$invoicedata["invoicetype"] = $invoice->invoice_type;
			$listjob["invoice"] = $invoicedata;
			array_push($datajob,$listjob);
		}
		$result["data"]["jobs"] = $datajob;
		echo json_encode($result);
	}
	public function invoice()
	{
		global $db;
		//$access_token = $_POST['access_token'];
		$invoiceid = $_POST['invoiceid'];
		$invoiceid = "1";
		$result = array();
		$result["status"] = "200";
		$result["data"] = array();
		$invoicedata = array();
		$db->query("SELECT * FROM print_invoices WHERE id = '".$invoiceid."'");
		$invoice = $db->fetch_object(true);
		$invoicedata["invoiceid"] = $invoice->id;
		$invoicedata["invoicecode"] = $invoice->invoice_no;
		$invoicedata["invoicedate"] = $invoice->invoice_date;
		$invoicedata["invoicestaff"] = $invoice->invoice_staff;
		$invoicedata["invoicepos"] = $invoice->invoice_pos;
		$invoicedata["invoicetotal"] = $invoice->invoice_total;
		$invoicedata["invoicetype"] = $invoice->invoice_type;
		
		
		$invdetail = array();
		$db->query("SELECT * FROM print_invoice_details WHERE invoice = '".$invoice->id."'");
		$invoicedetails = $db->fetch_object(false);
		foreach($invoicedetails as $inv)
		{
			$row = array();
			$row["product"] = $inv->product;
			$row["qty"] = $inv->qty;
			$row["unit"] = $inv->unit;
			$row["price"] = $inv->price;
			array_push($invdetail,$row);
		}
		$invoicedata["details"] = $invdetail;
		$result["data"] = $invoicedata;
		$db->query("UPDATE print_jobs SET job_status = '1' WHERE id = '".$invoice->jobid."'");
		echo json_encode($result);
	}
}

?>
