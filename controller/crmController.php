<?php
Class crmController extends baseController
{
	
	public function index()
	{
		$this->checklogin();
		$this->view->admintmp("index");
	}
	public function active($para)
	{
		$token = $para[1];
		global $db;
		$db->query("SELECT * FROM sgt_users WHERE email_token = '".$token."' LIMIT 1");
		$this->view->data['token'] = $token;
		$this->view->data['check'] = $db->num_row();
		$this->view->data['users'] = $db->fetch_object(true);
		$this->view->admintmp("active");
	}
	public function checklogin()
	{
		if(!(isset($_SESSION['xID']) && $_SESSION['xID'] != ""))
		{
			header("Location: ".XC_URL."/crm/login");
		}
	}
	public function logout()
	{
		//session_start();
		session_destroy();
		header("Location: ".XC_URL."/crm");
	}
	public function schedule()
	{
		$this->checklogin();
		//$this->view->admintmp("login");
	}
	public function inbox()
	{
		$this->view->admintmp("inbox");
	}
	public function emailabc($para)
	{
		$name = "Quang, Le Ngoc";
		$email = "quanghaiau.qn@gmail.com";
		echo baseMailler::getInstance()->cmsn($name,$email);
		//echo baseMailler::getInstance()->sendtask("Seagull Travel","no-reply@xiao.vn",$name,$email,"New Task Status T00087","");
		//echo baseMailler::getInstance()->newaccount("Thai Dinh Sang","sangtd@xiao.vn","sangtd",XC_URL."/crm/active/213123124sadasd1212");
		//echo $email." - ".$name;
		//echo baseMailler::getInstance()->send2("Seagull Travel","sangtd@xiao.vn","Sang Thai Dinh","kenzakivn@gmail.com","Thu gui tu VeMayBayHaiAu","Noi dung thu gui tu VeMayBayHaiAu");
		//echo baseMailler::getInstance()->sendersmtp("Ken Zaki","kenzakivn@gmail.com","test",$content,$value);
	}
	public function tasklist()
	{
		$this->view->data['tasks'] = hr::getInstance()->get_running_task($_SESSION['xID']);
		$this->view->admintmp("tasks");
	}
	public function testabcd()
	{
		//echo md5(date("d-m-Y H:i:s"))."<br>";
		echo substr(md5(date("d-m-Y H:i:s")),2,32);
		//3b1393d647f307a0420d6182609e3844

	}
	public function tour($para)
	{
		$this->view->admintmp("accessdenine");
	}
	public function login()
	{
		$this->view->admintmp("login");
	}
	public function send_mail($email,$subject,$msg) {
		 $api_key="key-901ede91ccd250f9b78b6923f98996f4";/* Api Key got from https://mailgun.com/cp/my_account */
		 $domain ="sandboxfab42856df0c462ba99f8d56b18f4a7c.mailgun.org";/* Domain Name you given to Mailgun */
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		 curl_setopt($ch, CURLOPT_USERPWD, 'api:'.$api_key);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		 curl_setopt($ch, CURLOPT_URL, 'https://api.mailgun.net/v2/'.$domain.'/messages');
		 curl_setopt($ch, CURLOPT_POSTFIELDS, array(
		  'from' => 'Sang TD <sangtd@xiao.vn>',
		  'to' => $email,
		  'subject' => $subject,
		  'html' => $msg
		 ));
		 $result = curl_exec($ch);
		 curl_close($ch);
		 return $result;
		}
	public function users($para)
	{
		switch($para[1])
		{
			case "new":
			{
				//$this->view->admintmp("newcustomer");
				break;
			}
			default:
			{
				$this->view->data['userlists'] = $this->model->get("userm001Model")->get_user_lists();
				$this->view->admintmp("users");
				break;
			}
		}
	}
	public function sms($para)
	{
		switch($para[1])
		{
			case "new":
			{
				//$this->view->admintmp("newcustomer");
				break;
			}
			case "sent":
			{
				$this->view->admintmp("sms_sent");
				break;
			}
			default:
			{
				$this->view->admintmp("smscampain");
				break;
			}
		}
		
	}
	public function customers($para)
	{
		switch($para[1])
		{
			case "new":
			{
				$this->view->admintmp("newcustomer");
				break;
			}
			case "detail":
			{
				//echo $para[2];
				$this->view->data['cus'] = $this->model->get("customerModel")->get_customer_detail($para[2]);
				$this->view->admintmp("customer_detail");
				break;
			}
			default:
			{
				$this->view->data['customerlists'] = $this->model->get("customerModel")->get_customers_list();
				$this->view->admintmp("customers");
				break;
			}
		}
		
	}
	public function company($para)
	{
		switch($para[1])
		{
			case "detail":
			{
				echo $para[2];
				$this->view->admintmp("company_detail");
				break;
			}
			default:
			{
				$this->view->data['companylist'] = $this->model->get("companyModel")->get_companys_list();
				$this->view->admintmp("companys");
				break;
			}
		}
	}
	public function category($para)
	{
		$this->checklogin();
		switch($para[1])
		{
			case "menu":
			{
				$this->view->data['listmenu'] = crm::getInstance()->get_list_menu();
				$this->view->admintmp("thucdon");
				break;
			}
			case "restaurants":
			{
				$this->view->data['listres'] = crm::getInstance()->get_list_res();
				$this->view->admintmp("nhahang");
				break;
			}
			default:
			{
				break;
			}
		}
	}
	public function sales($para)
	{
		$this->checklogin();
		switch($para[1])
		{
			case "tiec-cuoi":
			{
				$this->view->admintmp("tieccuoi");
				break;
			}
			case "hoi-nghi":
			{
				$this->view->admintmp("event-booking");
				break;
			}
			default:
			{
				break;
			}
		}
	}
	public function functionsheet($para)
	{
		$this->view->data['invoiceid'] = $para[1];
		$this->view->admintmp("functionsheet");
	}
	public function airlineticket($para)
	{
		$this->checklogin();
		switch($para[1])
		{
			case "promotion":
			{
				$this->view->data['promolist'] = $this->model->get("promotionModel")->get_promotion();
				$this->view->admintmp("airpromotion");
				break;
			}
			case "booking":
			{
				if($_SESSION['xGroup'] == 5)
				{
					$this->view->data['listbooking'] = booking::getInstance()->get_list_booking_by_staff($_SESSION['xID']);
				}
				else
				{
					$this->view->data['listbooking'] = booking::getInstance()->get_list_booking_by_all();
				}
				$this->view->admintmp("booking");
				break;
			}
			case "new":
			{
				$this->view->admintmp("newbooking");
				break;
			}
			default:
			{
				break;
			}
		}
	}
}