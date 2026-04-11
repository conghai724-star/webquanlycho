<?php
/**
 * Project: thuvien.
 * File: memberController.php.
 * Author: Ken Zaki
 * Email: kenzaki@xiao.vn
 * Create Date: 18:50 - 05/10/2013
 * Website: www.xiao.vn
 */
Class memberController extends baseController
{
    public function index()
    {
		if(!(isset($_SESSION['xID']) && $_SESSION['xID'] != "")){ header("Location: ".XC_URL."/member/login"); }
		$this->view->show("member_show");
    }
	public function panel($para)
	{
		if(isset($_SESSION['xID']) && $_SESSION['xID'] != "")
		{
			switch($para[1])
			{
				case "post":
				{
					$this->view->show("member_show");
					break;
				}
				case "like":
				{
					$this->view->show("member_like_post");
					break;
				}
				case "bst":
				{
					$this->view->show("member_bosuutap");
					break;
				}
				case "info":
				{
					$this->view->show("member_info");
					break;
				}
				case "naptien":
				{
					$this->view->show("member_napthe");
					break;
				}
				case "ruttien":
				{
					$this->view->show("member_ruttien");
					break;
				}
				case "history":
				{
					$this->view->show("member_payment_history");
					break;
				}
				default:
				{
					break;
				}
			}
		}
		else
		{
			header("Location: ".XC_URL."/member/login");
		}
	}
	public function show($para)
	{
		if(member::getInstance()->get_xid_by_username($para[1]))
		{
			$user = $para[1];
			$xid = member::getInstance()->get_xid_by_username($user);
			$u = array();
			$u['username'] = $user;
			$u['xid'] = $xid;
			$u['bookpost'] = general::getInstance()->countbook("bookpuber",$xid);
			$u['bookbst'] = general::getInstance()->countbst($xid);
			$u['booktotalview'] = general::getInstance()->count_view_by_member($xid);
			$u['booktotaldown'] = general::getInstance()->count_download_by_member($xid);
			$u['regdate'] = date("d-m-Y",strtotime(member::getInstance()->account($xid,"regdate")));
			$u['totalfriend'] = member::getInstance()->count_friend($xid);
			$u['totalmedal'] = member::getInstance()->count_medal($xid);
			$u['slogan'] = member::getInstance()->get_member_info($xid,"slogan");
			$u['avatar'] = member::getInstance()->account($xid,"avatar");
			$u['facebook'] = member::getInstance()->get_member_info($xid,"facebook");
			$u['xgroup'] = member::getInstance()->xgroup(member::getInstance()->account($xid,"xgroup"));
			$u['libscore'] = member::getInstance()->get_score($xid,"8317808");
			$this->view->data['u'] = $u;
			$this->view->show("member_index");
		}
		else
		{
			echo "Tài khoản không tồn tại....!";
		}
		
	}
    public function ajaxregister()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $hoten = $_POST['hoten'];
        $sex = $_POST['sex'];
        $dngay = $_POST['day'];
        $mngay = $_POST['thang'];
        $yngay = $_POST['nam'];
        $diachi = $_POST['diachi'];
        $tinhthanh = $_POST['national'];
        $noicongtac = $_POST['noicongtac'];
        echo $username." - ".$password." - ".$email." - ".$hoten." - ".$sex." - ".$dngay." - ".$mngay." - ".$yngay." - ".$diachi." - ".$tinhthanh." - ".$noicongtac;

		/*
		if(isset($_POST['username']) && $_POST['username'] != "")
		{	
			// $xid = ?
			$username = $_POST['username'];
			$password = md5($_POST['password']);
			$email = $_POST['email'];
			$hoten = $_POST['hoten'];
			$sex = $_POST['sex'];
			$dngay = $_POST['day'];
			$mngay = $_POST['thang'];
			$yngay = $_POST['nam'];
			$birthday = $yngay'..'$mngay'..'$dngay;
			$diachi = $_POST['diachi'];
			$tinhthanh = $_POST['national'];
			$noicongtac = $_POST['noicongtac'];
			if(!general::getInstance()->checkaccount("username",$username))
			{
			   $this->view->data['status'] = "Xin lỗi, tài khoản này đã tồn tại, nhấn <a href='".XC_URL."/member/register'>vào đây</a> để nhập lại thông tin";
			   $this->view->show("notifi");		   
			}
			elseif(!general::getInstance()->checkaccount("email",$email))
			{
				$this->view->data['status'] = "Xin lỗi, email này đã tồn tại, nhấn <a href='".XC_URL."/member/register'>vào đây</a> để nhập lại thông tin";
				$this->view->show("notifi");
			}
			else
			{
				$this->model->get("registerModel")->register($xid,$username,$password,$email);
				$this->model->get("registerModel")->insertInfo($xid,$firstname,$name,$othername,$birthday,$sex,$diachi,$tinhthanh);
				$this->view->data['status'] = "Đăng ký thành công!";
				header("Location: ".XC_URL."/member/login");
			}	
		}
		else
		{
			$this->view->show('register');
		}
		*/
    }
	public function spost()
	{
		$this->view->show("post");
	}
	public function login()
	{
		if(isset($_SESSION['xID']) && $_SESSION['xID'] != "")
		{
			header("Location: ".XC_URL);
		}
		else
		{
			$this->view->show("login");
		}
	}
    public function register()
    {
		if(isset($_POST['username']) && $_POST['username'] != "")
		{		
			$xid = general::getInstance()->generateid("account");
            $this->view->data['username'] = $username = $_POST['username'];
            $password = $_POST['password'];
            $this->view->data['email'] = $email = $_POST['email'];
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$gender = $_POST['gender'];
			$dob = $_POST['byear']."-".$_POST['bmonth']."-".$_POST['bday'];
			$about = $_POST['about'];
			$this->model->get("registerModel")->updateinfo($xid,$username,$password,$email,$firstname,$lastname,$gender,$dob,$about);
			$this->view->data['status'] = 'Chúc mừng <span class="user">'.$username.'</span> đã đăng ký thành công tài khoản tại <a href="http://thuviengiaoduc.edu.vn">Thư Viện Giáo Dục Trực Tuyến</a>. Mã tài khoản: <span class="user">'.$xid.'</span>. Một email kích hoạt vừa gửi đến địa chỉ: <span class="user">"'.$email.'"</span> của bạn, hãy kiểm tra email (kể cả hộp thư rác để kích hoạt tài khoản. Nhấn <a href="'.XC_URL.'">vào đây</a> để trở về trang chủ.';
			$this->view->show('register');
		}
		else
		{
			$this->view->show('register');
		}
    }
	//========
    public function ajaxcheckcaptcha()
    {
        //echo $_SESSION['captcha'];
        if(isset($_POST['code']) && $_POST['code'] != "")
        {
            $code = $_POST['code'];
            if(trim(strtolower($code)) == $_SESSION['captcha'])
            {
                echo "OK";
            }
            else
            {
                echo "ERROR";
            }
        }
        else
        {
            echo 'ERROR';
        }
    }
	public function ajaxlogin()
	{
		$username = mysql_real_escape_string($_POST["username"]);
        $password = md5(mysql_real_escape_string($_POST["password"]));
        $member_login = $this->model->get('memberloginModel')->check_login($username,$password);
		if($member_login)
		{
			echo $_SESSION['xUser'];
		}
		else
		{
			echo "error";
		}
	}
	public function logout()
	{
		//session_start();
		session_destroy();
		header("Location: ".XC_URL);
	}
    public function ajaxsubs()
    {
        if(isset($_POST['email']) && $_POST['email'] != "")
        {
            $email = $_POST['email'];
            if(member::getInstance()->check_subs($email))
            {
                return false;
            }
            else
            {
                global $db;
                $db->query("INSERT INTO xdata_subscribe(xemail) VALUES('".$email."')");
            }
        }
        else
        {
            echo 'ERROR';
        }
    }
	public function apilogin()
	{
		if(isset($_GET['provider']))
        {
        	$provider = $_GET['provider'];
        	echo general::getInstance()->apilogin($provider);
        }
	}
	public function post()
	{ 	
		if(!(isset($_SESSION['xID']) && $_SESSION['xID'] != "")){ header("Location: ".XC_URL."/member/login"); }
		if(isset($_POST['bookname']) && $_POST['bookname'] != "")
		{
		$bookname = mysql_real_escape_string($_POST['bookname']);		
		$bookcat = $_POST['bookcat'];		
		$booksubj = $_POST['booksubj'];		
		$bookcontent = $_POST['bookcontent']; 	
		$bookpuber = $_SESSION['xID'];		
		$bookauthor = $_POST ['bookauthor'];		
		$bookyear = $_POST['bookyear'];		
		$bookpubdate = date("Y-m-d H:i:s");	
		$bookgrade = $_POST['bookgrade']; 			
		$bookprice = $_POST['bookprice']; 	
		
		//************* Author Ken Zaki *** Upload *******************/
		$uploaddir_file = '../upload/docs/';
		$uploaddir_image = '../upload/thumb/';
		$uploadfile = $uploaddir_file .basename($_FILES['bookfile']['name']);
		$uploadimage = $uploaddir_image .basename($_FILES['bookimage']['name']);
		
		
		$bookfile = "thu-vien-giao-duc-".md5(time())."-".$_FILES['bookfile']['name'];
        move_uploaded_file($_FILES['bookfile']['tmp_name'],"./upload/docs/".$bookfile);
		if($_FILES['bookimage']['name'])
		{
			$bookimage = "thu-vien-giao-duc-".md5(time())."-".$_FILES['bookimage']['name'];
			move_uploaded_file($_FILES['bookimage']['tmp_name'],"./upload/thumb/".$bookimage);
		}
		else
		{
			$bookimage = "thuviengiaoduc.edu.vn.jpg";
		}
		
		
		//*************************End uploader**********************//
		
		if(!book::getInstance()->checkbook("bookname",$bookname))
		{
			
			 $this->view->data['status'] = 'Xin lỗi, tài liệu <span class="user">'.$_POST['bookname'].'</span> đã tồn tại, Vui lòng kiểm tra lại thông tin hoặc liên hệ BQT. <a href="'.XC_URL.'">Quay về trang chủ</a> hoặc <a href="'.XC_URL.'/member/post">Gửi tài liệu mới</a>.';
			 $this->view->show("post");
		}
		else
		{
			$this->model->get("tailieuModel")->insertBook($bookname,$bookcat,$booksubj,$bookcontent,$bookimage,$bookpuber,$bookauthor,$bookyear,$bookpubdate,$bookgrade,$bookfile,"0","0",rand(14,28),$bookprice,"0","0");
			$this->view->data['status'] = 'Cảm ơn bạn đã gửi tài liệu <span class="user">'.$_POST['bookname'].'</span>, tài liệu này cần kiểm duyệt nội dung, vì vậy nó sẽ xuất hiện sau ít phút nữa. Nhấn <a href="'.XC_URL.'">vào đây</a> để xem trước hoặc vào đây để trở về trang chủ.';
			$this->view->show("post");
		}
			
		}
		else
		{
			$this->view->show("post");	
		}
	}

}