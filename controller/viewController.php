<?php
/**
 * Project: thuvien.
 * File: viewController.php.
 * Author: Ken Zaki
 * Email: kenzaki@xiao.vn
 * Create Date: 09:54 - 07/10/2013
 * Website: www.xiao.vn
 */
Class viewController extends baseController
{
    public function index()
    {

    }
    public function ajaxbaoxau()
    {
        if(isset($_POST['id']) && $_POST['id'] != "")
        {
            $bookid = $_POST['id'];
            global $db;
            $db->query("INSERT INTO xiaob_baoxau(bookid) VALUES(".$bookid.")");
        }
        else
        {
            echo 'ERROR';
        }
    }
    public function ajaxlike()
    {
        if(isset($_POST['id']) && $_POST['id'] != "")
        {
            $bookid = $_POST['id'];
            global $db;
            $db->query("INSERT INTO xiaob_yeuthich(bookid,xid) VALUES(".$bookid.",'".$_SESSION['xID']."')");
        }
        else
        {
            echo 'ERROR';
        }
    }
    public function ajaxrate()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_POST['id']) && $_POST['id'] != "")
        {
            $bookid = $_POST['id'];
            global $db;
            $db->query("SELECT * FROM xiaob_bookrate WHERE bookid = '".$bookid."' AND ipadd = '".$ip."'");
            if(!$db->num_row())
            {
                $this->model->get("m2027Model")->updatediem($bookid,$ip);
                $db->query("SELECT bookscore FROM xiaob_book WHERE bookid = ".$bookid);
                $b = $db->fetch_object($first_row = true);
                echo $b->bookscore;
            }
            else
            {
              echo 'ERROR';
            }

        }
        else
        {
            echo 'ERROR';
        }
    }
    public function doc($para)
    {
        //$_SESSION['xID'] = '7221111111';
        $this->view->data['bookid'] = $masach = $this->func->getid($para[1]);
        if($this->func->checkid($masach,"xiaob_book","bookid"))
        {
				$this->view->data['xdata'] = $masach;//
				$this->model->get('m4011Model')->updateview($masach);//
				$this->view->data['books'] = $books = $this->model->get('viewModel')->getbook($masach);//			
				
				$this->view->data['bcat'] = $catname = $this->func->get_category($books->bookcat);
				$this->view->data['buper'] = $fullname = $this->member->get_member_fullname($books->bookpuber);
				$this->view->data['commentlist'] = $this->model->get('commentModel')->get_comment($masach);//d
				$this->view->data['baicungtacgia'] = $this->model->get('m4211Model')->baicungtacgia($books->bookpuber);//
				$this->view->data['baicungmon'] = $this->model->get('m4231Model')->baicungmon($books->booksubj);//
				$this->view->data['baicungchude'] = $this->model->get('m4331Model')->baicungchude($books->bookname);//
				$this->view->data['tags'] = $this->model->get('m4606Model')->get_tags($masach);
				
				$this->view->data['bookurl'] = $bookurl = "".XC_URL."/upload/docs/".$books->bookfile;//
				$this->view->data['type'] =  pathinfo($bookurl, PATHINFO_EXTENSION);//
				$this->view->show('view-doc');//
        }
        else
        {
            $this->view->data['notifi'] = "Xin lỗi, không tồn tại tài liệu này!";
			//echo "sai rồi";
            $this->view->show('404');
        }
    }
    public function subject($para)
    {
        $mamon = $this->func->getid($para[1]);
        if($this->func->checkid($mamon,"xdata_monhoc","mamon"))
        {
            $spp = 60;
            $page = 1;
            if(isset($_GET['page']) && $_GET['page'] != "")
            {
                $page = $_GET['page'];
            }
            $cp = $page - 1;
            $this->view->data['sodulieu']= $sodu_lieu = general::getInstance()->bookcount("booksubj = '".$mamon."'");
            $sotrang = $sodu_lieu/$spp;
            $sql = "SELECT * FROM xiaob_book WHERE booksubj = '".$mamon."' ORDER BY bookid DESC LIMIT ".$cp*$spp.",".$spp;
            $this->view->data['listbook'] = $this->model->get('m3201Model')->bookquery($sql,false);
            $this->view->data['mamon'] = $mamon;
            $this->view->data['count'] = $sodu_lieu;
            $this->view->data['sotrang'] = $sotrang;
            $this->view->show('subject');
        }
        else
        {
            $this->view->data['notifi'] = "Xin lỗi, không tồn tại môn này!";
            $this->view->show('404');
        }
    }
    public function grade($para)
    {
        $grade = $this->func->getid($para[1]);
        if($this->func->checkid($grade,"xdata_khoilop","khoilop"))
        {
            $spp = 60;
            $page = 1;
            if(isset($_GET['page']) && $_GET['page'] != "")
            {
                $page = $_GET['page'];
            }
            $cp = $page - 1;
            $this->view->data['sodulieu']= $sodu_lieu = general::getInstance()->bookcount("bookgrade = '".$grade."'");
            $sotrang = $sodu_lieu/$spp;
            $sql = "SELECT * FROM xiaob_book WHERE bookgrade = '".$grade."' ORDER BY bookid DESC LIMIT ".$cp*$spp.",".$spp;
            $this->view->data['listbook'] = $this->model->get('m3201Model')->bookquery($sql,false);
            $this->view->data['grade'] = $grade;
            $this->view->data['count'] = $sodu_lieu;
            $this->view->data['sotrang'] = $sotrang;
            $this->view->show('grade');
        }
        else
        {
            $this->view->data['notifi'] = "Xin lỗi, không tồn tại khối lớp này!";
            $this->view->show('404');
        }
    }
	public function school($para)
	{
		$this->view->show("school");
	}
	public function page($para)
	{
		$this->view->data['pageid'] = $pageid = $this->func->getid($para[1]);
		$this->view->show("page");
	}
	public function listxe()
	{
		$this->view->show("list-xe");
	}
	public function carbooking()
	{
		$this->view->show("car-book");
	}
	public function diemden($para)
	{
		$place = $this->func->getid($para[1]);
        if($this->func->checkid($place,"sgt_tour_place","id"))
        {
			$placedata = $this->model->get("placeModel")->get_place_info($place);
			$chil = tour::getInstance()->get_children_place($place);
			$chilid = implode(",",$chil);
			$this->view->data['place_title'] = "Các Tour <b>".$placedata->place_name."</b> khởi hành từ <b>Quy Nhơn</b>";
			$querystr ="SELECT * FROM sgt_tour_schedule as s INNER JOIN sgt_tours as t ON s.tourid = t.tourid WHERE t.tour_return = '".$place."' OR t.tour_return IN (".$chilid.")";
			global $db;
			$db->query($querystr);
			$this->view->data['results'] = $db->fetch_object(false);			
			$this->view->show("diemden");
		}
		else
        {
            $this->view->show('404');
        }
	}
	public function tourtet()
	{
		$this->view->show("tourtet");
	}
	public function search()
	{
		if(isset($_GET['departure']) && $_GET['departure'] != "")
		{ 		
			$this->view->data['depart'] = $depart = tour::getInstance()->get_place_id($_GET['departure']); 
		}
		else
		{	
			$this->view->data['depart'] = $depart = tour::getInstance()->get_place_id("quy-nhon");
		}
		if(isset($_GET['arrival']) && $_GET['arrival'] != "")
		{ 
			$this->view->data['arrival'] = $arrival = tour::getInstance()->get_place_id($_GET['arrival']);
		}
		else
		{
			$this->view->data['arrival'] = $arrival = null;
		}
		
		if(isset($_GET['startdate']) && $_GET['startdate'] != ""){ $startdate = $_GET['startdate']; }else{ $startdate = date("d-m-Y");}
		if(isset($_GET['enddate']) && $_GET['enddate'] != ""){ $enddate = $_GET['enddate']; }else{ 
		$enddate = date('d-m-Y', strtotime(date("d-m-Y"). ' + 180 days'));
		}
		if(isset($_GET['price']) && $_GET['price'] != "")
		{ 
			if($_GET['price'] == "<1000000")
			{
				$pricetag = "(t.tour_price BETWEEN 0 AND 1000000)";
			}				
			elseif($_GET['price'] == "1000000-2000000")
			{
				$pricetag = "(t.tour_price BETWEEN 1000000 AND 2000000)";
			}
			elseif($_GET['price'] == "2000000-5000000")
			{
				$pricetag = "(t.tour_price BETWEEN 2000000 AND 5000000)";
			}
			elseif($_GET['price'] == ">5000000")
			{
				$pricetag = "(t.tour_price BETWEEN 5000000 AND 500000000)";
			}
			else
			{
				$pricetag = "(t.tour_price BETWEEN 0 AND 500000000)";
			}
		}
		else
		{ 
			$pricetag = "(t.tour_price BETWEEN 0 AND 500000000)";
		}
		if($arrival != null)
		{
			$this->view->data['search_title'] = "Các Tour đi <b>".$arrival->place_name."</b> khởi hành từ <b>".$depart->place_name."</b>";
			$this->view->data['search_url'] = XC_URL."/tim-kiem/?departure=".$depart->place_slug."&arrival=".$arrival->place_slug;
			$querystr ="SELECT * FROM sgt_tour_schedule as s INNER JOIN sgt_tours as t ON s.tourid = t.tourid WHERE t.tour_depart = '".$depart->id."' AND t.tour_return = '".$arrival->id."' AND s.start_date > '".date("Y-m-d",strtotime($startdate))."' AND s.start_date < '".date("Y-m-d",strtotime($enddate))."' AND ".$pricetag." ORDER BY s.start_date"; 
		}
		else
		{
			$this->view->data['search_title'] = "Các Tour khởi hành từ <b>".$depart->place_name."</b>";
			$this->view->data['search_url'] = XC_URL."/tim-kiem/?departure=".$depart->place_slug;
			$querystr ="SELECT * FROM sgt_tour_schedule as s INNER JOIN sgt_tours as t ON s.tourid = t.tourid WHERE t.tour_depart = '".$depart->id."' AND s.start_date > '".date("Y-m-d",strtotime($startdate))."' AND s.start_date < '".date("Y-m-d",strtotime($enddate))."' AND ".$pricetag." ORDER BY s.start_date"; 
		}
		global $db;
		$db->query($querystr);
		$this->view->data['results'] = $db->fetch_object(false);
		$this->view->data['startdate'] = $startdate;
		$this->view->data['enddate'] = $enddate;
		$this->view->show("search");
	}
	public function tour($para)
	{
		$this->view->data['tourid'] = $tourid = $this->func->getid($para[1]);
		if($this->func->checkid($tourid,"sgt_tours","tourid"))
		{
			$this->view->data['tour'] = $tour = $this->model->get("m4123Model")->get_singel_tour($tourid);
			$this->view->data['tour_images'] = tour::getInstance()->get_images($tourid);
			$this->view->data['schedule'] = tour::getInstance()->tour_recent_schedule($tourid);
			if($tour->tour_promo == 0)
			{
				$this->view->data['price'] = number_format($tour->tour_price, 0, ',', '.').' VNĐ';
			}
			else
			{
				$this->view->data['price'] = number_format($tour->tour_promo, 0, ',', '.').' VNĐ';
			}
			$this->view->show("tour");
		}
		else
		{
			$this->view->show("404");
		}
		
	}
	public function blank()
	{
		$this->view->show("blank-tour");
	}
	public function post($para)
	{
		$this->view->data['postid'] = $postid = $this->func->getid($para[1]);
        if($this->func->checkid($postid,"sgt_post","id"))
        {
				global $db;
				$db->query("SELECT * FROM sgt_post as p INNER JOIN sgt_category as c ON p.category = c.catid WHERE p.id = '".$postid."' LIMIT 1");
				$this->view->data['post'] = $db->fetch_object(true);
				$this->view->show("post");
        }//125k
        else
        {
            $this->view->data['notifi'] = "Xin lỗi, bài viết này không tồn tại!";
            $this->view->show('404');
        }
	}
    public function category($para)
    {
		$this->view->show('category');
		/*
        $cat = $this->func->getid($para[1]);
        if($this->func->checkid($cat,"xiaob_cat","catid"))
        {
			$this->view->data['catid'] = $cat;
            $this->view->show('category');
        }
        else
        {
            $this->view->data['notifi'] = "Xin lỗi, không tồn tại danh mục này!";
            $this->view->show('404');
        }
		*/
    }
	public function schedule()
	{
		$this->view->show("tour-schedule");
	}
	public function albums()
	{
		$this->view->show("albums");
	}
	public function uploadalbum()
	{
		$this->view->show("upload-album");
	}
	public function album()
	{
		$this->view->show("album");
	}
    public function bst($para)
    {
        $bstid = general::getInstance()->getid($para[1]);
        if($this->func->checkid($bstid,"xiaob_bst_flat","id"))
        {
            global $db;
            $spp = 10;
            $page = 1;
            if(isset($_GET['page']) && $_GET['page'] != "")
            {
                $page = $_GET['page'];
            }
            $cp = $page - 1;
            $db->query("SELECT * FROM xiaob_bst WHERE mabst = ".$bstid." LIMIT ".$cp*$spp.",".$spp);
            $this->view->data['list'] = $db->fetch_object();
            $sodu_lieu = $db->num_row();
            $sotrang = $sodu_lieu/$spp;
            $this->view->data['count'] = $sodu_lieu;
            $this->view->data['mabst'] = $bstid;
            $this->view->data['sotrang'] = $sotrang;
            $this->view->show('bst');
        }
        else
        {
            $this->view->data['notifi'] = "Xin lỗi, không tồn tại danh mục này!";
            $this->view->show('404');
        }
    }
}