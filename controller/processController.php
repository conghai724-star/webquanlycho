<?php
Class processController extends baseController
{
	public function index()
	{
		echo "Hello, Have a nice day!";
	}
	public function addbookingtieccuoi()
	{
		header("Location: ".XC_URL."/crm/sales/tiec-cuoi/new");
	}
	public function addevent()
	{
		global $db;
		$db->query("INSERT INTO sgt_event_booking(event_title,event_invoiceno, event_date, event_time, event_rest, event_contact, event_staff, event_created_date, event_guests,event_tablelayout, event_sound, event_media, event_backdrop, event_orthers, event_menu_price, event_sound_price, event_media_price, event_deposit, event_payment_method, event_task_sales, event_task_tech, event_task_rest, event_task_kitchen, event_managed_staff) VALUES ('".$_POST['title']."','".$_POST['invoiceno']."','".date("Y-m-d H:i:s",strtotime($_POST['date']))."','".$_POST['timefrom']." - ".$_POST['timeto']."','".$_POST['rest']."','".$_POST['contact']."','".$_SESSION['xID']."','".date("Y-m-d H:i:s")."','".$_POST['guests']."','".$_POST['tablelayout']."','".$_POST['sound']."','".$_POST['media']."','".$_POST['backdrop']."','".$_POST['orther']."','".$_POST['menu_price']."','".$_POST['sound_price']."','".$_POST['media_price']."','".$_POST['deposit']."','".$_POST['payment_method']."','".$_POST['task_sales']."','".$_POST['task_tech']."','".$_POST['task_rest']."','".$_POST['task_kitchen']."','1')");
		
		//print_r($_POST['menus']);
		for($i = 1;$i <count($_POST['menus']);$i++)
		{
			if($_POST['menus'][$i] != "")
			{
				$db->query("INSERT INTO sgt_event_menus(invoiceid,menuid,numintable) VALUES('".$_POST['invoiceno']."','".crm::getInstance()->get_menuid_by_name($_POST['menus'][$i])."','1')");
			}
		}
		for($i = 1;$i <count($_POST['drink']);$i++)
		{
			if($_POST['drink'][$i] != "")
			{
				$db->query("INSERT INTO sgt_event_menus(invoiceid,menuid,numintable) VALUES('".$_POST['invoiceno']."','".crm::getInstance()->get_menuid_by_name($_POST['drink'][$i])."','".$_POST['drinkindesk'][$i]."')");
			}
		}
		header("Location: ".XC_URL."/crm/functionsheet/".$_POST['invoiceno']);
	}
	public function addpost()
	{
		
		if(!(isset($_SESSION['xID']) && $_SESSION['xID'] != "")){ header("Location: ".XC_URL."/crm/login"); }
		if(isset($_POST['title']) && $_POST['title'] != "")
		{
			$title = mysql_real_escape_string($_POST['title']);		
			$content = $_POST['content'];		
			$tags = $_POST['tags'];		
			$category = $_POST['category']; 	
			
			//************* Author Ken Zaki *** Upload *******************/
			$uploaddir_image = '../uploads/images/';
			//$uploadimage = $uploaddir_image .basename($_FILES['bookimage']['name']);
			
			
			
			if($_FILES['featureimage']['name'])
			{
				$imagesf = "du-lich-hai-au-".md5(time())."-".$_FILES['featureimage']['name'];
				move_uploaded_file($_FILES['featureimage']['tmp_name'],"./uploads/images/".$imagesf);
			}
			else
			{
				$imagesf = "du-lich-hai-au.jpg";
			}
			
			//*************************End uploader**********************//
			
			global $db;
			$sql = "INSERT INTO sgt_post(title,content,postdate,author,category,post_image,tags) VALUES('".$title."','".$content."','".date("Y-m-d H:i:s")."','".$_SESSION['xID']."','".$category."','".$imagesf."','".$tags."')";
			$db->query($sql);
			/*
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
			*/
			echo $sql;
		}
	}
	public function addtour()
	{
		global $db;
		$tourid = $_SESSION['tourid'];
		$tourtype = $_POST['tourtype'];
		$depart = $_POST['depart'];
		$return = $_POST['return'];
		$duration = $_POST['duration'];
		$price = $_POST['price'];
		$discount = $_POST['discountprice'];
		$title = $_POST['title'];
		$description = $_POST['tour_description'];
		$note = $_POST['tour_note'];
		$keywords = $_POST['keywords'];
		$include = $_POST['includes'];
		$daytitle1 = $_POST['daytitle1'];
		$reser1 = $_POST['dayrese1'];
		$db->query("INSERT INTO sgt_tours(tourid,tour_title,tour_description,tour_duration,tour_type,tour_depart,tour_return,tour_includes,tour_note,tour_price,tour_promo) VALUES('".$tourid."','".$title."','".$description."','".$duration."','".$tourtype."','".$depart."','".$return."','".$include."','".$note."','".$price."','".$discount."')");
		//$db->query("INSERT INTO sgt_tour_reservation(tourid,dayid,daytitle,dayreservation) VALUES('".$tourid."',1,'".$daytitle1."','".$reser1."')");
	}
	public function addtourdate()
	{
		global $db;
		$db->query("INSERT INTO sgt_tour_reservation(tourid,daytitle,dayreservation) VALUES('".$_POST['tourid']."','".$_POST['daytitle']."','".$_POST['dayreservation']."')");
		header("Location: http://dulichhaiau.com.vn/admin/tour/");
	}
	public function addbooking()
	{
		global $db;
		$tourid = $_SESSION['tourid'];
		//$db->query("INSERT INTO sgt_tours(tourid,tour_title,tour_description,tour_duration,tour_type,tour_depart,tour_return,tour_includes,tour_note,tour_price,tour_promo) VALUES('".$tourid."','".$title."','".$description."','".$duration."','".$tourtype."','".$depart."','".$return."','".$include."','".$note."','".$price."','".$discount."')");
		//$db->query("INSERT INTO sgt_tour_reservation(tourid,dayid,daytitle,dayreservation) VALUES('".$tourid."',1,'".$daytitle1."','".$reser1."')");
		header("Location: ".XC_URL."/crm/airlineticket/booking");
	}
}