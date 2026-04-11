<?php

Class ajaxController extends baseController
{
    public function index()
    {
        echo "Hello! sangtd@xiao.vn";
    }
	public function userlogin()
	{
		$username = mysql_real_escape_string($_POST["username"]);
        $password = md5(mysql_real_escape_string($_POST["password"]));
        $member_login = $this->model->get('memberloginModel')->user_login($username,$password);
		if($member_login)
		{
			echo $_SESSION['xUser'];
		}
		else
		{
			echo "error";
		}
	}
	public function checkroom()
	{
		$fromdate = str_replace('/', '-', $_POST['checkindate']);
		$todate = str_replace('/', '-', $_POST['checkoutdate']);
		$rl = '<thead class="hidden-xs"><tr class="active">
									<th>Loại phòng</th>
									<th>Ưu đãi và quyền lợi</th>
									<th>Tối đa</th>
									<th>Giá phòng/đêm</th>
									<th>Số phòng</th>
									<th></th>
								</tr>
								</thead>
								<tbody>';
		$listroom = booking::getInstance()->get_room_list($_POST['id']);
		foreach($listroom as $room)
		{
			$room_status = booking::getInstance()->check_room_availability($room->hrid, $room->room_count, $_POST['number'], date('Y-m-d',strtotime($fromdate)), date('Y-m-d',strtotime($todate)));
			$rstatus = '';
			if($room_status > 0)
			{
				$rstatus = '<button type="submit" data="'.$room_status.' " class="btn btn-primary">Đặt phòng <i class="fa fa-angle-right" aria-hidden="true"></i></button>';
			}
			else
			{
				$rstatus = '<button type="submit" data="'.$room_status.' " class="btn disabled">Hết phòng <i class="fa fa-angle-right" aria-hidden="true"></i></button>';
			}
			
			$rl = $rl.'
			
<tr>
				<th class="room-name col-md-12" colspan="6">'.$room->room_name.'</th>
			</tr>
			<tr class="room-info">
				<td align="center">
							<img src="'.XC_URL.'/images/no-image.jpg" alt="'.$room->room_name.'" width="100px">     
						 
				</td>
				<td>
					
				</td>
				<td class="max-guest" align="center">
									'.$room->room_guests.' khách
							</td>
				<td class="price" align="center">'.number_format($room->room_fare_normal*(1 - $room->room_discount/100), 0, ',', '.').'đ</td>
				<td align="center">
					<select class="form-control" id="number-room">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
									</select>
				</td>
				<td align="center">
					<form action="'.XC_URL.'/dat-phong" method="POST">
						<input type="hidden" name="_token" value="WboopnUiMbJKj5a5A8i7I4brK2LE9Kezl84z8bSh">
						<input type="hidden" name="hotel_id" value="'.$_POST['id'].'">
						<input type="hidden" name="hotel_room" value="'.$room->hrid.'">
						<input type="hidden" name="date_checkin" value="'.$fromdate.'">
						<input type="hidden" name="date_checkout" value="'.$todate.'">
						<input type="hidden" name="number" value="'.$_POST['number'].'">
						<input type="hidden" name="adult" value="'.$_POST['adult'].'">
						<input type="hidden" name="tracking" value="">
						'.$rstatus.'
					</form>
				</td>
			</tr>';
		}
		$rl = $rl.'
								</tbody>';
		echo $rl;
	}
	
	//Start HR Ajax
	public function get_team()
	{
		global $db;
		$db->query("SELECT * FROM sgt_teams WHERE team_department = '".$_POST['depart']."'");
		$listteam = $db->fetch_object(false);
		echo '<option selected disabled="disabled">Chọn ban/nhóm</option>';
		foreach($listteam as $t)
		{
			echo '<option value="'.$t->id.'">'.$t->team_name.'</option>';
		}
	}
	public function getmenu()
	{
		global $db;
		$db->query("SELECT * FROM sgt_menu");
		$row_set = $db->fetch_object(false);
		echo json_encode($row_set);
	}
	public function automenu()
	{
		$type  = $_GET['type'];
		global $db;
		$keyword = general::getInstance()->bodau_keyword($_GET['term']);
		if($type == 2)
		{
			$db->query("SELECT * FROM sgt_menu WHERE (title LIKE '%".$keyword."%' OR khongdau LIKE '%".$keyword."%') AND menutype='10'");
		}
		else
		{
			$db->query("SELECT * FROM sgt_menu WHERE (title LIKE '%".$keyword."%' OR khongdau LIKE '%".$keyword."%')  AND NOT(menutype='10')");
		}
		$listmenu = $db->fetch_object(false);
		$data = array();
		foreach($listmenu as $menu)
		{
			array_push($data,$menu->title);
		}
		echo json_encode($data);
	}
	public function adduser()
	{
		global $db;
		$now = date("Y-m-d H:i:s");
		$email_token = substr(md5(date("d-m-Y H:i:s")),2,32);
		$sms_token = rand(1111,9999);
		$db->query("INSERT INTO sgt_users(username,password,firstname,lastname,user_birthday,email,user_mobile,user_group,user_team,created_date,user_status,email_token,sms_code) VALUES('".$_POST['username']."','".md5($_POST['password'])."','".$_POST['firstname']."','".$_POST['lastname']."','".date("Y-m-d",strtotime($_POST['user_birthday']))."','".$_POST['email']."','".$_POST['user_mobile']."','".$_POST['group']."','".$_POST['team']."','".$now."',0,'".$email_token."','".$sms_token."')");
		baseMailler::getInstance()->newaccount($_POST['firstname']." ".$_POST['lastname'],$_POST['email'],$_POST['username'],XC_URL."/crm/active/".$email_token);
		sms::getInstance()->sendnewsms($_POST['user_mobile'],"Seagull - Ma kich hoat tai khoan: ".$_POST['username']." cua ban tai Seagull Travel CRM la: ".$sms_token." - LH: 1900 1088",$now,"1");
	}
	public function deletecustomer()
	{
		global $db;
		$db->query("DELETE FROM sgt_customers WHERE id='".$_POST['customerid']."'");
	}
	public function deletecompany()
	{
		global $db;
		$db->query("DELETE FROM sgt_company WHERE id='".$_POST['companyid']."'");
	}
	public function deletemenu()
	{
		global $db;
		$db->query("DELETE FROM sgt_menu WHERE id='".$_POST['menuid']."'");
	}
	public function deleteres()
	{
		global $db;
		$db->query("DELETE FROM sgt_rest WHERE id='".$_POST['resid']."'");
	}
	//END HR Ajax
	
	public function getdistrict()
	{
		global $db;
		$province = $_POST['province'];
		$db->query("SELECT * FROM sgt_district WHERE province = '".$province."'");
		$listd = $db->fetch_object(false);
		echo '<option selected disabled="disabled">Chọn huyện/thị xã</option>';
		foreach($listd as $d)
		{
			echo '<option value="'.$d->id.'">'.$d->district_name.'</option>';
		}
	}
	public function addcompany()
	{
		global $db;
		$bus_name = $_POST['bus_name'];
		$bus_enname = $_POST['bus_enname'];
		$bus_shortname = $_POST['bus_shortname'];
		$bus_date = date("Y-m-d H:i:s",strtotime($_POST['bus_date']));
		$bus_areas = $_POST['bus_areas'];
		$bus_address = $_POST['bus_address'];
		$bus_phone = $_POST['bus_phone'];
		$bus_email = $_POST['bus_email'];
		$bus_taxid = $_POST['bus_taxid'];
		$bus_province = $_POST['bus_province'];
		$bus_district = $_POST['bus_district'];
		echo $db->query("INSERT INTO sgt_company(name,en_name,shortname,anniverday,business_areas,address,province,district,tax_id,phone,email,managed_staff,created_date) VALUES('".$bus_name."','".$bus_enname."','".$bus_shortname."','".$bus_date."','".$bus_areas."','".$bus_address."','".$bus_province."','".$bus_district."','".$bus_taxid."','".$bus_phone."','".$bus_email."','".$_SESSION['xID']."','".date("Y-m-d H:i:s")."')");
	}
	public function addmenu()
	{
		global $db;
		$db->query("INSERT INTO sgt_menu(title,menutype,price) VALUES('".$_POST['menu_name']."','".$_POST['menu_type']."','".$_POST['menu_price']."')");
	}
	public function addres()
	{
		global $db;
		$db->query("INSERT INTO sgt_rest(title,capacity) VALUES('".$_POST['res_name']."','".$_POST['res_capa']."')");
	}
	public function updatemenu()
	{
		global $db;
		$db->query("UPDATE sgt_menu SET title = '".$_POST['menu_name']."',menutype = '".$_POST['menu_type']."',price = '".$_POST['menu_price']."' WHERE id = '".$_POST['menu_id']."'");
	}
	public function updateres()
	{
		global $db;
		$db->query("UPDATE sgt_rest SET title = '".$_POST['res_name']."',capacity = '".$_POST['res_capa']."' WHERE id = '".$_POST['res_id']."'");
	}
	public function addcustomers()
	{
		$starttime = microtime(true);
		
		global $db;
		$title = $_POST['title'];
		$sex = 0;
		if($title == "Ông" || $title == "Anh")
		{
			$sex = 1;
		}
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$birthday = date("Y-m-d H:i:s",strtotime($_POST['birthday']));
		$phone = $_POST['phone'];
		$mobile = $_POST['mobile'];
		$email = $_POST['email'];
		$address = $_POST['address'];
		$province = $_POST['province'];
		$district = $_POST['district'];
		$company = $_POST['company'];
		$depart = $_POST['depart'];
		$business_email = $_POST['business_email'];
		$business_phone = $_POST['business_phone'];
		$managed_staff = $_POST['managed_staff'];
		$customer_number = $_POST['customer_number'];
		$sharing = $_POST['sharing'];
		$updated_date = date("Y-m-d H:i:s");
		$created_date = date("Y-m-d H:i:s");
		$customer_resource = $_POST['customer_resource'];
		$db->query("INSERT INTO sgt_customers(customer_number,firstname,lastname,sex,title,birthday,company,company_depart,address,province,district,phone,email,mobile,business_phone,business_email,managed_staff,created_staff,sharing,customer_resource,created_date,updated_date) VALUES('".$customer_number."','".$firstname."','".$lastname."','".$sex."','".$title."','".$birthday."','".$company."','".$depart."','".$address."','".$province."','".$district."','".$phone."','".$email."','".$mobile."','".$business_phone."','".$business_email."','".$managed_staff."','".$_SESSION['xID']."','".$sharing."','".$customer_resource."','".$created_date."','".$updated_date."')");
		$endtime = microtime(true);
		$duration = $endtime - $starttime;
		echo $duration;
	}
	public function quicksearchcustomer()
	{
		global $db;
		$keyword = $_POST['keyword'];
		if(count($keyword) == 0)
		{
			$db->query("SELECT *, c.email as email, m.name as company_name FROM sgt_customers as c 
			INNER JOIN sgt_district as d ON c.district = d.id
			INNER JOIN sgt_province as p ON c.province = p.id
			INNER JOIN sgt_company as m ON c.company = m.id
			ORDER BY c.id DESC LIMIT 30");
		}
		else
		{
			$db->query("SELECT *,c.email as email, m.name as company_name FROM sgt_customers as c 
			INNER JOIN sgt_company as m ON c.company = m.id 
			INNER JOIN sgt_district as d ON c.district = d.id
				INNER JOIN sgt_province as p ON c.province = p.id
			WHERE firstname LIKE '%".$keyword."%' OR lastname LIKE '%".$keyword."%' OR c.email LIKE '%".$keyword."%' OR mobile LIKE '%".$keyword."%' OR m.name LIKE '%".$keyword."%' ORDER BY c.id DESC LIMIT 30");
					}
			$listc = $db->fetch_object(false);
				echo '<tr>
									 <th><i class="icon_menu"></i> No.</th>
									 <th><i class="icon_profile"></i> Họ và tên</th>
									 <th><i class="icon_calendar"></i> Ngày sinh</th>
									 <th><i class="icon_mail_alt"></i> Email</th>
									 <th><i class="icon_pin_alt"></i> Công ty</th>
									 <th><i class="icon_pin_alt"></i> Khu vực</th>
									 <th><i class="icon_mobile"></i> Di động</th>
									 <th><i class="icon_cogs"></i> Thao tác</th>
								  </tr>';
			$i = 1;
			  foreach($listc as $cus)
			  {
				  echo '<tr>
				  <td>'.$i.'</td>
				 <td>'.$cus->firstname.' '.$cus->lastname.'</td>
				 <td>'.date("d-m-Y",strtotime($cus->birthday)).'</td>
				 <td>'.$cus->email.'</td>
				 <td>'.$cus->company_name.'</td>
				 <td>'.$cus->district_name.' - '.$cus->province_name.'</td>
				 <td>'.$cus->mobile.'</td>
				 <td>
				  <div class="btn-group">
					  <a class="btn btn-primary" alt="Xem chi tiết" href="#"><i class="icon-eye-open"></i></a>
					  <a class="btn btn-success" alt="Sửa" href="#"><i class="icon-edit"></i></a>
					  <a class="btn btn-danger" alt="Xóa" href="#"><i class="icon_close_alt2"></i></a>
				  </div>
				  </td>
			  </tr>';
				  $i++;
			  }

	}
	public function quicksearchtask()
	{
		global $db;
		$keyword = $_POST['keyword'];
		if(count($keyword) == 0)
		{
			$db->query("SELECT * FROM sgt_task_assigns as a
			INNER JOIN sgt_tasks as t ON a.taskid = t.id
			WHERE a.assigned_staff = '".$_SESSION['xID']."' AND NOT(t.task_status = 3) LIMIT 20");
		}
		else
		{
			$db->query("SELECT * FROM sgt_task_assigns as a
			INNER JOIN sgt_tasks as t ON a.taskid = t.id
			WHERE (t.name LIKE '%".$keyword."%' OR t.description LIKE '%".$keyword."%') AND a.assigned_staff = '".$_SESSION['xID']."' AND NOT(t.task_status = 3) LIMIT 20");
					}
			$tasks = $db->fetch_object(false);
			$i = 1;
			  foreach($tasks as $task)
			  {
				  echo '<tr>
								  <td>'.$i.'</td>
                                 <td><b>'.$task->name.'</b></td>
                                 <td>'.date("d-m-Y",strtotime($task->start_time)).'</td>
                                 <td>'.date("d-m-Y",strtotime($task->due_time)).'</td>
                                 <td>'.hr::getInstance()->priority($task->priority).'</td>
                                 <td><span class="badge '.hr::getInstance()->typelabel($task->progress).'">'.hr::getInstance()->tasktype($task->task_status).' '.round($task->progress,0).'%</span></td>
                                 <td>'.hr::getInstance()->shorten_name($task->managed_staff).'</td>
                                 <td>
                                  <div class="btn-group">
                                      <a class="btn btn-primary" alt="Xem chi tiết" href="#"><i class="icon-eye-open"></i></a>
                                      <a class="btn btn-success" alt="Sửa" href="#"><i class="icon-edit"></i></a>
                                      <a class="btn btn-danger" alt="Xóa" href="#"><i class="icon_close_alt2"></i></a>
                                  </div>
                                  </td>
                              </tr>';
				  $i++;
			  }

	}
	public function quicksearchuser()
	{
		global $db;
		$keyword = $_POST['keyword'];
		if(count($keyword) == 0)
		{
			$db->query("SELECT * FROM sgt_users as u 
			LEFT JOIN sgt_user_groups as g ON u.user_group = g.id
			LEFT JOIN sgt_teams as t ON u.user_team = t.id
			ORDER BY u.id DESC LIMIT 30");
		}
		else
		{
			$db->query("SELECT * FROM sgt_users as u 
			LEFT JOIN sgt_user_groups as g ON u.user_group = g.id
			LEFT JOIN sgt_teams as t ON u.user_team = t.id
			WHERE firstname LIKE '%".$keyword."%' OR lastname LIKE '%".$keyword."%' OR email LIKE '%".$keyword."%' OR user_mobile LIKE '%".$keyword."%' OR team_name LIKE '%".$keyword."%' ORDER BY u.id DESC LIMIT 30");
		}
			$listc = $db->fetch_object(false);
				echo '<tr>
									 <th><i class="icon_menu"></i> No.</th>
                                 <th><i class="icon_profile"></i> Họ và tên</th>
                                 <th><i class="icon_calendar"></i> Ngày sinh</th>
                                 <th><i class="icon_mail_alt"></i> Email</th>
                                 <th><i class="icon_pin_alt"></i> Nhóm</th>
                                 <th><i class="icon_pin_alt"></i> Phòng/Ban</th>
                                 <th><i class="icon_mobile"></i> Di động</th>
                                 <th><i class="icon_cogs"></i> Thao tác</th>
								  </tr>';
			$i = 1;
			  foreach($listc as $u)
			  {
				  echo '<tr>
								  <td>'.$i.'</td>
                                 <td>'.$u->firstname." ".$u->lastname.'</td>
                                 <td>'.date("d-m-Y",strtotime($u->user_birthday)).'</td>
                                 <td>'.$u->email.'</td>
                                 <td>'.$u->group_name.'</td>
                                 <td>'.$u->team_name.'</td>
                                 <td>'.$u->user_mobile.'</td>
                                 <td>
                                  <div class="btn-group">
                                      <a class="btn btn-primary" alt="Xem chi tiết" href="#"><i class="icon-eye-open"></i></a>
                                      <a class="btn btn-success" alt="Sửa" href="#"><i class="icon-edit"></i></a>
                                      <a class="btn btn-danger" alt="Xóa" href="#"><i class="icon_close_alt2"></i></a>
                                  </div>
                                  </td>
                              </tr>';
				  $i++;
			  }

	}
	public function addcustomer()
	{
		if(isset($_POST['acform']) && $_POST['acform'] != null)
		{
			
		}
		else
		{
			
		}
	}
	private function createfav($favname, $xid)
	{
		global $db;
		$db->query("INSERT INTO xiaob_bst_flat(xid,tenbst) VALUES('".$xid."','".$favname."')");
		$db->query("SELECT id FROM xiaob_bst_flat WHERE xid = '".$xid."' ORDER BY id DESC LIMIT 1");
		return $db->fetch_object(true)->id;
	}
	private function checkinfav($bookid,$favid)
	{
		global $db;
		$db->query("SELECT * FROM xiaob_bst WHERE bookid = '".$bookid."' AND mabst = '".$favid."'");
		$db->fetch_object(false);
		return $db->num_row();
	}
	public function ajaxlike()
	{
		$uid = $_POST['xid'];
		$bookid = $_POST['bookid'];
		$favid = $_POST['favid'];
		if(isset($_POST['favname']) && $_POST['favname'] != "")
		{
			$favid = $this->createfav($_POST['favname'],$uid);
		}
		if($this->checkinfav($bookid,$favid))
		{
			echo "Tài liệu này đã có trong bộ sưu tập của bạn.";
		}
		else
		{
			global $db;
			$db->query("INSERT INTO xiaob_bst(bookid,mabst) VALUES('".$bookid."','".$favid."')");
			echo "Thêm thành công tài liệu vào BST";
		}
	}
	public function deletebook()
	{
		$bookid = $_POST['bookid'];
		global $db;
		$db->query("DELETE FROM `xiaob_book` WHERE bookid='".$bookid."'");
	}
	public function removelike()
	{
		$bookid = $_POST['bookid'];
		global $db;
		$db->query("DELETE FROM `xiaob_yeuthich` WHERE bookid='".$bookid."' AND xid = '".$_SESSION['xID']."'");
		
	}
	public function likebook()
	{
		$bookid = $_POST['bookid'];
		global $db;
		$db->query("INSERT INTO xiaob_yeuthich(bookid,xid) VALUES('".$bookid."','".$_SESSION['xID']."')");
	}
	public function subscribe()
	{
		$email = $_POST['email'];
		global $db;
		$db->query("SELECT * FROM xdata_subscribe WHERE xemail = '".$email."'");
		if($db->num_row())
		{
			echo "error";
		}
		else
		{
			$db->query("INSERT INTO xdata_subscribe(xemail,status) VALUES('".$email."',1)");
			echo "success";
		}
	}
	public function ajaxloadfav()
	{
		$xid = $_POST['xid'];
		global $db;
		$db->query("SELECT * FROM xiaob_bst_flat WHERE xid = '".$xid."'");
		$listfav = $db->fetch_object(false);
		foreach($listfav as $f)
		{
			echo '<option value="'.$f->id.'">'.$f->tenbst.'</option>';
		}
	}
	public function checkusername()
	{
		global $db;
		$db->query("SELECT * FROM sgt_users WHERE username = '".$_POST['username']."'");
		if($db->num_row())
		{
			echo "false";
		}
		else
		{
			echo "true";
		}
	}
	public function checkemail()
	{
		global $db;
		$db->query("SELECT * FROM sgt_users  WHERE email = '".$_POST['email']."'");
		if($db->num_row())
		{
			echo "false";
		}
		else
		{
			echo "true";
		}
	}
	public function ajaxaddfav()
	{
		$xid = $_POST['xid'];
		$bookid = $_POST['bookid'];
		$xid = $_POST['xid'];
	}
	public function filter_by_subject()
	{
		global $db;
		$subject = $_POST['sid'];
		$catid = $_POST['catid'];
        $db->query("SELECT * FROM xiaob_book WHERE bookcat = '".$catid."' AND booksubj = '".$subject."' ORDER BY bookid DESC LIMIT 60");
        $listbook = $db->fetch_object();
			foreach($listbook as $book)
			{
				$e = $i%2;
			?>
					                     <li class="catitem" id="<?php echo $e;?>">
						                        <div class="box_img"><img alt="<?php echo $book->bookname;?>" src="http://thuviengiaoduc.edu.vn/upload/thumb/<?php echo $book->bookimage;?>" onerror='this.src="http://thuviengiaoduc.edu.vn/images/thuviengiaoduc.edu.vn.jpg"'></div>
                        <div class="thongtindetai">
                            <div class="tootip_title_thuvienOnlinePage_Index">
                                <a href="<?php echo general::getInstance()->permalink($book->bookid,'book');?>" class="link_title_thuvienOnlinePage_Index clsCheckUser"><?php echo $book->bookname;?></a>
								<br>
								
                                 
                            </div>                                        
                            <ul class="thongke_news">              
                                <li>Lượt xem: <span><?php echo $book->bookview;?></span></li>
								<li class="line">|</li>
                                <li>Lượt tải: <span><?php echo $book->bookdown;?></span></li>
                                
                            </ul>
                            <a href="<?php echo general::getInstance()->permalink($book->bookid,'book');?>" class="xemtiep clsCheckUser">Xem chi tiết...</a>
                        </div>
                    </li>
					<?php
					}
	}
	public function resendactivecode()
	{
		$token = $_POST['token'];
		global $db;
		$db->query("SELECT * FROM sgt_users WHERE email_token = '".$token."' LIMIT 1");
		$users = $db->fetch_object(true);
		$results = sms::getInstance()->sendnewsms($users->user_mobile,"Ma xac thuc tai khoan cua ban tai SeagullCRM la: ".$users->sms_code."",date("d-m-Y H:i:s"),"1");
		echo $users->user_mobile;
	}
	public function newsms()
	{
		//echo "1";
		$to = $_POST['to'];
		$content = $_POST['content'];
		$senddate = $_POST['senddate'];
		$results = sms::getInstance()->sendnewsms($to,$content,$senddate,"1");
		echo $results;
	}
		public function menu()
	{
		$parent = $_POST['parent'];
		switch($parent)
		{
			case "danhmuc":
			{
				echo '
				<div style="margin-left: 20px;">
                        <a href="'.XC_URL.'/crm/category/menu">Thực đơn</a><span>|</span>
                    </div>
                    <div>
                        <a href="'.XC_URL.'/crm/category/restaurants">Nhà hàng</a><span>|</span>
                    </div>
					<div>
                        <a href="'.XC_URL.'/crm/category/loai-tiec">Loại tiệc - Hội nghị</a><span>|</span>
                    </div>
				</div>
				';
				break;
			}
			case "khachhang":
			{
				echo '
				<div style="margin-left: 20px;">
                        <a href="'.XC_URL.'/crm/customers/new">Thêm mới</a><span>|</span>
                    </div>
                    <div>
                        <a href="'.XC_URL.'/crm/customers">Danh sách khách hàng</a><span>|</span>
                    </div>
					<div>
                        <a href="'.XC_URL.'/crm/company">Công ty/Đơn vị</a><span>|</span>
                    </div>
					<div>
                        <a href="'.XC_URL.'/crm/customers/resource">Nguồn khách hàng</a><span>|</span>
                    </div>
				</div>
				';
				break;
			}
			case "kehoach":
			{
				echo '
				<div style="margin-left: 20px;">
                        <a href="">Kees hoach 1</a><span>|</span>
                    </div>
                    <div>
                        <a href="">Ke hoach 2</a><span>|</span>
                    </div>
				</div>
				';
				break;
			}
			case "banhang":
			{
				echo '
				<div style="margin-left: 20px;">
                        <a href="'.XC_URL.'/crm/">Đặt vé</a><span>|</span>
                    </div>
                    <div>
                        <a href="'.XC_URL.'/crm/">Đặt tour</a><span>|</span>
                    </div>
					<div>
                        <a href="'.XC_URL.'/crm/">Đặt xe</a><span>|</span>
                    </div>
					<div>
                        <a href="'.XC_URL.'/crm/sales/tiec-cuoi">Đặt tiệc cưới</a><span>|</span>
                    </div>
					<div>
                        <a href="'.XC_URL.'/crm/sales/hoi-nghi">Đặt hội nghị</a><span>|</span>
                    </div>
				</div>
				';
				break;
			}
			default:
			{
				break;
			}
		}
	}
	public function testroom()
	{
		global $db;
		$db->query("SELECT * FROM gt_room_closings WHERE roomid = '2' AND from_date between '2019-07-24' and '2019-07-25'");
		$closing = $db->fetch_object(false);
		//$status = true;
		foreach($closing as $close)
		{
			echo 6 - $close->stock." ".$close->stock."<br>";
		}
	}
}