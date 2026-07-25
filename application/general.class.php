<?php
/**
 * Project: thuvien.
 * File: general.class.php.
 * Author: Ken Zaki
 * Email: kenzaki@xiao.vn
 * Create Date: 08:50 - 07/10/2013
 * Website: www.xiao.vn
 */
Class general{


    /*
     * @Variables array
     * @access public
     */
    private static $instance;

    /**
     *
     * @constructor
     *
     * @access public
     *
     * @return void
     *
     */
    function __construct() {

    }

    public function getInstance() {
        if (!self::$instance)
        {
            self::$instance = new general();
        }
        return self::$instance;
    }
	public function get_config($key)
	{
		global $db;
		$db->query("SELECT config_value FROM hicrm_configs WHERE config_key = '".$key."' LIMIT 1");
		return $db->fetch_object(true)->config_value;
	}
	public function _s($string)
	{
		if(isset($_SESSION['language']) && $_SESSION['language'] != "")
		{
			$language = $_SESSION['language'];
		}
		else
		{
			$language = 'english';
		}
		include __SITE_PATH.'/languages/'.$language.'.php';
		echo $translation[$string];
	}
	private function get_permission()
	{
		global $db;
		$db->query("SELECT * FROM hicrm_permission_datas WHERE depart = '".$_SESSION['staff']['department']."'");
		$listper = $db->fetch_object();
		$permissions = array();
		foreach($listper as $per)
		{
			array_push($permissions,$per->permission_id);
		}
		return $permissions;
	}
	public function check_permission($role)
	{
		if($_SESSION['staff']['group'] == 1)
		{
			return true;
		}
		else
		{
			$listpermissionbydepart = $this->get_permission();
			return in_array($role,$listpermissionbydepart);
		}
	}
	public function get_status_list()
	{
		global $db;
		$db->query("SELECT * FROM ow_order_status ORDER BY id ASC");
		return $db->fetch_object();
	}
	//=============== GENERAL DATA =============//
	public function count_post_by_category($catid)
	{
		global $db;
		$db->query("SELECT count(*) as countdata FROM bds_posts WHERE post_category = '".$catid."'");
		return $db->fetch_object(true)->countdata;
	}
	//General Data
	public function get_hotel_data($hotelid,$data)
	{
		global $db;
		$db->query("SELECT * FROM gt_hotels WHERE hid = '".$hotelid."' LIMIT 1");
		return $db->fetch_object(true)->$data;
	}
	public function get_place_name_by_hotel($hotelid)
	{
		global $db;
		$db->query("SELECT place_name FROM gt_hotels as h INNER JOIN gt_places as p ON h.hotel_place = p.plid WHERE hid = '".$hotelid."'");
		return $db->fetch_object(true)->place_name;
	}
	public function get_place_name($placeid)
	{
		global $db;
		$db->query("SELECT place_name FROM gt_places WHERE plid = '".$placeid."'");
		return $db->fetch_object(true)->place_name;
	}
	public function get_province_name($provinceid)
	{
		global $db;
		$db->query("SELECT * FROM gt_provinces WHERE prid = '".$provinceid."'");
		return $db->fetch_object(true)->province_name;
	}
	public function get_district_name($districtid)
	{
		global $db;
		$db->query("SELECT * FROM gt_districts WHERE dtid = '".$districtid."'");
		return $db->fetch_object(true)->district_name;
	}
	public function get_ward_name($wardid)
	{
		global $db;
		$db->query("SELECT * FROM gt_wards WHERE wardid = '".$wardid."'");
		return $db->fetch_object(true)->ward_name;
	}
	public function get_full_address($hotelid)
	{
		global $db;
		$db->query("SELECT hotel_address,hotel_province,hotel_district,hotel_ward FROM gt_hotels WHERE hid = '".$hotelid."'");
		$h = $db->fetch_object(true);
		return $h->hotel_address.", ".$this->get_ward_name($h->hotel_ward).", ".$this->get_district_name($h->hotel_district).", ".$this->get_province_name($h->hotel_province);
	}
	public function get_facilities($fid)
	{
		global $db;
		$db->query("SELECT * FROM gt_facilities WHERE fcid IN (".$fid.")");
		return $db->fetch_object(false);
	}
	public function get_hotel_images($hotelid)
	{
		global $db;
		$db->query("SELECT * FROM gt_hotel_images WHERE hotelid = '".$hotelid."'");
		return $db->fetch_object(false);
	}
	//End General Data
	
    public function getid($strings)
    {
        $ids = explode("-", $strings);
        $id = $ids[0];
        return $id;
    }
	
	
    public function checkid($id,$table,$idfield)
    {
        global $db;
        $db->query("SELECT * FROM ".$table." WHERE ".$idfield." = '".$id."'");
        if($db->num_row())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function get_category($catid)
    {
        if($catid != "")
        {
            global $db;
            $blog = $db->query("SELECT * FROM xiaob_cat WHERE catid=".$catid);
            $me = $db->fetch_object($first_row = true);
            return $me->catname;
        }
        else return "";
    }
	public function get_payment_history($xid)
	{
		global $db;
		$db->query("SELECT * FROM xdata_payment WHERE xid = '".$xid."' ORDER BY paytime DESC");
		return $db->fetch_object(false);
	}
    public function get_grade($gradeid)
    {
        if($gradeid != "")
        {
            global $db;
            $blog = $db->query("SELECT * FROM xdata_khoilop WHERE khoilop=".$gradeid);
            $me = $db->fetch_object($first_row = true);
            return $me->tenkhoilop;
        }
        else return "";
    }
    public function get_subject($subjid)
    {
        if($subjid != "")
        {
            global $db;
            $blog = $db->query("SELECT * FROM xdata_monhoc WHERE mamon=".$subjid);
            $me = $db->fetch_object($first_row = true);
            return $me->tenmon;
        }
        else return "";
    }
	public function get_monhoc($mamon)
    {
        if($mamon != "")
        {
            global $db;
            $blog = $db->query("SELECT * FROM xdata_monhoc WHERE mamon='".$mamon."'");
            $me = $db->fetch_object(true);
            return $me->tenmon;
        }
        else return ""; 
    }
    public function get_mem_account($xid,$info)
    {
        if(isset($xid) && $xid != "" && isset($info) && $info != "")
        {
            global $db;
            $db->query("SELECT ".$info." FROM xdata_account WHERE xid = ".$xid);
            $acc = $db->fetch_object($first_row = true);
            return $acc->$info;
        }
        else
        {
            return "";
        }
    }
	public function price_mask($price)
	{
		return "";
	}
    public function get_mem_info($xid,$info)
    {
        if(isset($xid) && $xid != "" && isset($info) && $info != "")
        {
            global $db;
            $db->query("SELECT ".$info." FROM xdata_info WHERE xid = ".$xid);
            $acc = $db->fetch_object($first_row = true);
            return $acc->$info;
        }
        else
        {
            return "";
        }
    }
   public function bodau($title)
{
    // 1. Chuẩn hóa chuỗi
    $title = trim($title);
    $title = mb_strtolower($title, 'UTF-8');

    // 2. Bỏ dấu tiếng Việt
    $viet = [
        'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
        'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
        'ì','í','ị','ỉ','ĩ',
        'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
        'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
        'ỳ','ý','ỵ','ỷ','ỹ',
        'đ'
    ];

    $latin = [
        'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
        'e','e','e','e','e','e','e','e','e','e','e',
        'i','i','i','i','i',
        'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
        'u','u','u','u','u','u','u','u','u','u','u',
        'y','y','y','y','y',
        'd'
    ];

    $title = str_replace($viet, $latin, $title);

    // 3. Xóa ký tự đặc biệt (chỉ giữ a-z 0-9 và khoảng trắng)
    $title = preg_replace('/[^a-z0-9\s]/', '', $title);

    // 4. Đổi khoảng trắng thành dấu -
    $title = preg_replace('/\s+/', '-', $title);

    // 5. Xóa dấu - dư
    $title = trim($title, '-');

    return $title;
}


	public function bodau_ten($title) {
        $title = preg_replace('/(")/','',$title);
        $url_pattern = array('` &(amp;|"| |"|#)?[a-z0-9]+;`i', '`[^a-z0-9]`i');

        $title = htmlentities($title, ENT_COMPAT, 'utf-8');
        $title = preg_replace( '`&([a-z]+)(acute|uml|circ|quot|grave|ring|cedil|slash|tilde|caron|lig);`i', "\\1", $title );
        $title = preg_replace('`\[.*\]`U','',$title);

        $title = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $title);
        $title = preg_replace("/(À|Á|Ả|Ã|Ạ|Ằ|Ắ|Ẳ|Ẵ|Ặ|Ầ|Ấ|Ẩ|Ẫ|Ậ)/", 'A', $title);
        $title = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $title);
        $title = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ể|Ễ|Ệ)/", 'E', $title);
        $title = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $title);
        $title = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $title);
        $title = preg_replace("/(-)/", '', $title);
        $title = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $title);
        $title = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ổ|Ỗ|Ộ|Ở|Ờ|Ớ|Ở|Ỡ|Ợ)/", 'O', $title);
        $title = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $title);
        $title = preg_replace("/(Ù|Ú|Ủ|Ũ|Ụ|Ừ|Ứ|Ử|Ữ|Ự)/", 'U', $title);
        $title = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $title);
        $title = preg_replace("/(Ỳ|Ý|Ỷ|Ỹ|Ỵ)/", 'Y', $title);
        $title = preg_replace("/(đ)/", 'd', $title);
        $title = preg_replace("/(Đ)/", 'D', $title);
        $title = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $title);
        $title = preg_replace($url_pattern , '-', $title);
        $title = preg_replace("/(--)/",'-',$title);
        return $title;
    }
	public function bodau_keyword($title) {
        $unicode = array(
           'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
           'd'=>'đ',
           'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
           'i'=>'í|ì|ỉ|ĩ|ị',
           'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
           'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
           'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
           'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
           'D'=>'Đ',
           'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
           'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
           'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
           'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
           'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
       );
	  foreach($unicode as $nonUnicode=>$uni){
		   $title = preg_replace("/($uni)/i", $nonUnicode, $title);
	  }

        return $title;
    }
	
    public function permalink($id,$type)
    {
        global $db;
        $fs = "";
        switch($type)
        {
			case "post":
			{
				$db->query("SELECT * FROM bds_posts WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/tin-rao/".$bl->id."-".$this->bodau($bl->post_title).".html";
				break;
			}case "category_product":
			{
				$db->query("SELECT * FROM hicrm_product_categories WHERE id = ".$id."");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/nha-thuoc/category/".$bl->id."-".$this->bodau($bl->category_name).".html";
				break;
			}
		     case "bmi_online":
			{
				$db->query("SELECT *, i.id as iid FROM hicrm_introduce as i 
                LEFT JOIN hicrm_categories as c ON i.introduce_id_type = c.id WHERE c.id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/bmi-online/".$bl->id."-".$this->bodau($bl->category_name).".html";
				break;
			}case "services":
			{
				$db->query("SELECT * FROM hicrm_categories WHERE id = ".$id."");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/dich-vu/".$bl->id."-".$this->bodau($bl->category_name).".html";
				break;
			} case "service_detail":
			{
				$db->query("SELECT * FROM hicrm_service WHERE id = ".$id."");
				$bl = $db->fetch_object(true);
                $db->query("SELECT * FROM hicrm_categories WHERE id = '".$bl->service_category."'");
                $id_category = $db->fetch_object(true);
				$fs = XC_URL."/dich-vu/".$id_category->id."-".$this->bodau($id_category->category_name)."/".$bl->id."-".$this->bodau($bl->service_name).".html";
				break;
			}
            case "employee":
			{
				$db->query("SELECT * FROM hicrm_employees WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/bac-si/".$bl->id."-".$this->bodau($bl->employee_name).".html";
				break;
			}case "doctors":
			{
				$db->query("SELECT * FROM hicrm_employees WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/doi-ngu-bac-si/".$bl->id."-".$this->bodau($bl->employee_name).".html";
				break;
			}
            case "events":
			{
				$db->query("SELECT * FROM hicrm_events WHERE id = '1'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/tin-tuc-su-kien/".$bl->id."-".$this->bodau($bl->category_name).".html";
				break;
			}
             case "introduce":
			{
				$db->query("SELECT *, i.id as iid FROM hicrm_introduce as i 
                LEFT JOIN hicrm_categories as c ON i.introduce_id_type = c.id WHERE c.id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/gioi-thieu/".$bl->id."-".$this->bodau($bl->category_name).".html";
				break;
			}
            case "event":
			{
				$db->query("SELECT * FROM hicrm_events WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/tin-tuc-su-kien/".$bl->id."-".$this->bodau($bl->event_name).".html";
				break;
			}
            case "market_result":
			{
				$db->query("SELECT * FROM hicrm_market_results WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/ket-qua-san-viec-lam/".$bl->id."-".$this->bodau($bl->result_title).".html";
				break;
			}
            case "depart":
			{
				$db->query("SELECT * FROM hicrm_departments WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/doi-ngu-bac-si/".$bl->id."-".$this->bodau($bl->depart_name).".html";
				break;
			}
            
			case "project":
			{
				$db->query("SELECT * FROM bds_projects WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/du-an/".$bl->id."-".$this->bodau($bl->project_name).".html";
				break;
			}
			case "page":
			{
				$db->query("SELECT * FROM bds_pages WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/gioi-thieu/".$bl->id."-".$this->bodau($bl->page_title).".html";
				break;
			}
			case "user":
			{
				$db->query("SELECT * FROM hicrm_users WHERE id = '".$id."'");
				$bl = $db->fetch_object(true);
				$fs = XC_URL."/thanh-vien/".$bl->id."-".$this->bodau($bl->user_fullname).".html";
				break;
			}
			case "place":
			{
				$fs = XC_URL."/diem-den/".$id."-".$this->bodau($this->get_place_name($id)).".html";
				break;
			}
            case "subject":
            {
                $db->query("SELECT * FROM xdata_monhoc WHERE mamon = '".$id."'");
                $bl = $db->fetch_object($first_row = true);
                $fs = XC_URL."/subject/".$id."-".$this->bodau($bl->tenmon);
                break;
            }
			case "publisher":
            {
                $db->query("SELECT username FROM xdata_account WHERE xid = '".$id."'");
                $bl = $db->fetch_object($first_row = true);
                $fs = XC_URL."/user/".$this->bodau($bl->username);
                break;
            }
            case "cat":
            {
                $db->query("SELECT * FROM sgt_category WHERE catid = ".$id."");
                $bl = $db->fetch_object($first_row = true);
                $fs = XC_URL."/chuyen-muc/".$id."-".$this->bodau($bl->cat_title);
                break;
            }
			case "post2":
            {
                $db->query("SELECT * FROM sgt_post WHERE id = ".$id."");
                $bl = $db->fetch_object($first_row = true);
                $fs = XC_URL."/bai-viet/".$id."-".$this->bodau($bl->title).".html";
                break;
            }
            case "nhathuoc":
            {
                $db->query("SELECT * FROM hicrm_product_categories WHERE id = ".$id."");
                $bl = $db->fetch_object($first_row = true);
                $fs = XC_URL."/nha-thuoc/".$id."-".$this->bodau($bl->category_name).".html";
                break;
            }
            case "bst":
            {
                $db->query("SELECT * FROM xiaob_bst_flat WHERE id = ".$id);
                $bst = $db->fetch_object($first_row = true);
                $fs = $id."-".$this->bodau($bst->tenbst);
                break;
            }
			case "schoollevel":
            {
                $db->query("SELECT * FROM xiaob_school_level WHERE id = ".$id);
                $bst = $db->fetch_object($first_row = true);
                $fs = $id."-".$this->bodau($bst->levelname);
                break;
            }
            case "job_post":
            {
                $db->query("SELECT id, title FROM hicrm_job_posts WHERE id = '".$id."' LIMIT 1");
                $bl = $db->fetch_object(true);
                $slug = isset($bl->title) ? $this->bodau($bl->title) : 'chi-tiet-viec-lam';
                $fs = XC_URL."/chi-tiet-viec-lam/".$id."-".$slug.".html";
                break;
            }
            case "candidate_profile":
            {
                $db->query("SELECT id, full_name FROM hicrm_candidates WHERE id = '".$id."' LIMIT 1");
                $bl = $db->fetch_object(true);
                $slug = isset($bl->full_name) ? $this->bodau($bl->full_name) : 'chi-tiet-ung-vien';
                $fs = XC_URL."/chi-tiet-ung-vien/".$id."-".$slug.".html";
                break;
            }
            case "manage_jobs_page":
            {
                $fs = XC_URL."/quan-ly-viec-lam.html/page/".intval($id);
                break;
            }
            case "grade":
            {
                $db->query("SELECT * FROM xiaob_grade WHERE id = ".$id);
                $bst = $db->fetch_object($first_row = true);
                $fs = $id."-".$this->bodau($bst->gradename);
                break;
            }
            
            default:
                break;
        }
        return $fs;
    }
	public function get_ds_khoilop($caphoc)
	{
		global $db;
		if($caphoc == "*")
		{
			$db->query("SELECT * FROM xdata_khoilop ORDER BY khoilop");
			return $db->fetch_object(false);
		}
		else
		{
			$db->query("SELECT * FROM xdata_khoilop WHERE caphoc = '".$caphoc."' ORDER BY khoilop");
			return $db->fetch_object(false);
		}
	}
    public function get_bst($xid)
    {
        if(isset($xid) && $xid != "")
        {
            global $db;
            $db->query("SELECT * FROM xiaob_bst_flat WHERE xid = '".$xid."'");
            return $db->fetch_object();
        }
        else
        {
            return null;
        }
    }
	public function count_bst($bstid)
	{
		global $db;
		$db->query("SELECT count(bookid) as c FROM xiaob_bst WHERE mabst = '".$bstid."'");
		return $db->fetch_object(true)->c;
	}
    private function URNR($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }
    public function random_bst()
    {
        global $db;
        $db->query("SELECT * FROM xiaob_bst_flat ORDER BY xview DESC");
        $c = $db->num_row();
        $b = $this->URNR(1,$c,3);
        return $b;

    }
    public function get_bst_list($bstid)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_bst WHERE mabst = ".$bstid." ORDER BY bookid DESC LIMIT 4");
        return $db->fetch_object();
    }
    public function bst_info($id)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_bst_flat WHERE id = ".$id);
        return $db->fetch_object($first_row = true);
    }
    public function get_top_post($num)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_memlog ORDER BY upload DESC LIMIT ".$num);
        return $db->fetch_object();
    }
    public function get_subject_list()
    {
        global $db;
        $db->query("SELECT * FROM xdata_monhoc WHERE mamon != '8258378' ORDER BY tenmon");
        return $db->fetch_object();
    }
	public function get_category_list()
    {
        global $db;
        $db->query("SELECT * FROM xiaob_cat ORDER BY catid");
        return $db->fetch_object();
    }
	public function get_book_by_member($xid,$limit = 3)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_book WHERE bookpuber = ".$xid." ORDER BY bookid DESC LIMIT ".$limit);
        return $db->fetch_object();
    }
	public function get_activity($xid,$limit = 3)
    {
        global $db;
        $db->query("SELECT * FROM xdata_activity WHERE xid = ".$xid." AND apptype = '2345999' ORDER BY time DESC LIMIT ".$limit);
        return $db->fetch_object();
    }
	public function activyname($actid)
	{
		global $db;
		$db->query("SELECT acti_name FROM xdata_activity_flat WHERE acti_id = '".$actid."' LIMIT 1");
		return $db->fetch_object(true)->acti_name;
	}
	public function get_lop($khoilop)
	{
		global $db;
		$db->query("SELECT * FROM xdata_khoilop WHERE khoilop = '".$khoilop."' LIMIT 1");
		return $db->fetch_object(true)->tenkhoilop."";
	}
	public function get_book_member_like($xid,$limit = 3)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_yeuthich WHERE xid = '".$xid."' ORDER BY id DESC LIMIT ".$limit);
        if($db->num_row())
		{
			$listbook = $db->fetch_object();
			$arr = array();
			foreach($listbook as $book)
			{
				array_push($arr,$book->bookid);
			}
		}
		else
		{
			$arr = array(0);
		}
		$a = implode(",",$arr);
		$db->query("SELECT * FROM xiaob_book WHERE bookid IN ($a)");
		return $db->fetch_object(false);
    }
	public function checkliked($xid,$bookid)
	{
		global $db;
		$db->query("SELECT * FROM xiaob_yeuthich WHERE xid ='".$xid."' AND bookid = '".$bookid."'");
		if($db->num_row())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	public function countbook($by,$id)
	{
		global $db;
		$db->query("SELECT * FROM xiaob_book WHERE ".$by." = '".$id."'");
		return $db->num_row();
	}
	public function countbst($xid)
	{
		global $db;
		$db->query("SELECT * FROM xiaob_bst_flat WHERE xid = '".$xid."'");
		return $db->num_row();
	}
	public function count_view_by_member($xid)
	{
		global $db;
		$db->query("SELECT SUM(bookview) as total FROM xiaob_book WHERE bookpuber = '".$xid."'");
		return ($db->fetch_object(true)->total)+0;
	}
	public function count_download_by_member($xid)
	{
		global $db;
		$db->query("SELECT SUM(bookdown) as total FROM xiaob_book WHERE bookpuber = '".$xid."'");
		return ($db->fetch_object(true)->total)+0;
	}
    public function get_book_by_subject($subjectid,$limit = 3)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_book WHERE booksubj = ".$subjectid." ORDER BY bookid DESC LIMIT ".$limit);
        return $db->fetch_object();
    }
	public function generateRandomString($length = 10) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
			return $randomString;
	}
    public function get_newest_book_by_subject($subjectid)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_book WHERE booksubj = ".$subjectid." ORDER BY bookid DESC");
        return $db->fetch_object($first_row = true);
    }
	public function get_book_by_category($catid,$orderby = "bookid",$limit = 20)
	{
		global $db;
        $db->query("SELECT * FROM xiaob_book WHERE bookcat = ".$catid." ORDER BY ".$orderby." DESC LIMIT ".$limit);
        return $db->fetch_object();
	}
	public function get_top_view_book($limit = 5)
	{
		global $db;
		$db->query("SELECT * FROM xiaob_book ORDER BY bookview DESC LIMIT ".$limit);
		return $db->fetch_object(false);
	}
	public function get_top_download_book($limit = 5)
	{
		global $db;
		$db->query("SELECT * FROM xiaob_book ORDER BY bookdown DESC LIMIT ".$limit);
		return $db->fetch_object(false);
	}
    public function get_top_book_by_category($catid,$limit = 3)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_book WHERE bookcat = ".$catid." ORDER BY bookid DESC LIMIT ".$limit);
        return $db->fetch_object();
    }
    public function get_grade_by_level($levelid)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_level_grade WHERE levelid = ".$levelid);
        return $db->fetch_object();
    }
    public function get_top_member_by_score($top = 5)
    {
        global $db;
        $db->query("SELECT * FROM xdata_score WHERE appid = '8317808' ORDER BY score DESC LIMIT ".$top);
        return $db->fetch_object();
    }
    public function get_event_info($eventid)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_event WHERE id = ".$eventid);
        return $db->fetch_object($first_row = true);
    }
    public function bookcount($w)
    {
        global $db;
        $db->query("SELECT * FROM xiaob_book WHERE ".$w);
        return $db->num_row();
    }
    public function analytic_page($str)
    {
        $ids = explode("-", $str);
        return $ids[1];
    }
    public function get_bst_info($mabst,$info)
    {
        global $db;
        $db->query("SELECT ".$info." FROM xiaob_bst_flat WHERE id = ".$mabst);
        $s = $db->fetch_object($first_row = true);
        return $s->$info;
    }
    public function get_list_tinhthanh()
    {
        global $db;
        $db->query("SELECT * FROM xdata_tinhthanh ORDER BY tentinh DESC");
        return $db->fetch_object();
    }
	function time_ago($timestamp)
	{
		
		$time_ago = strtotime($timestamp);
		$cur_time   = time();
		$time_elapsed   = $cur_time - $time_ago;
		$seconds    = $time_elapsed ;
		$minutes    = round($time_elapsed / 60 );
		$hours      = round($time_elapsed / 3600);
		$days       = round($time_elapsed / 86400 );
		$weeks      = round($time_elapsed / 604800);
		$months     = round($time_elapsed / 2600640 );
		$years      = round($time_elapsed / 31207680 );
		//return $cur_time;
		if($seconds <= 60){
			return "$seconds giây trước";
		}else if($minutes <=60){
			if($minutes==1){
				return "một phút trước";
			}else{
				return "$minutes phút trước";
			}
		}else if($hours <=24){
			if($hours==1){
				return "một giờ trước";
			}else{
				return "$hours giờ trước";
			}
		}else if($days <= 7){
			if($days==1){
				return "Hôm qua";
			}else{
				return "$days ngày trước";
			}
		}else if($weeks <= 4.3){
			if($weeks==1){
				return "một tuần trước";
			}else{
				return "$weeks tuần trước";
			}
		}else if($months <=12){
			if($months==1){
				return "một tháng trước";
			}else{
				return "$months tháng trước";
			}
		}else{
			if($years==1){
				return "một năm trước";
			}else{
				return "$years năm trước";
			}
		}
	}
	public function apilogin($provider)
	{
		include('hybridauth/config.php');
        include('hybridauth/Hybrid/Auth.php');
		try{
        	
        	$hybridauth = new Hybrid_Auth( $config );
        	
        	$authProvider = $hybridauth->authenticate($provider);

	        $user_profile = $authProvider->getUserProfile();
	        
			if($user_profile && isset($user_profile->identifier))
	        {
	        	echo "<b>Name</b> :".$user_profile->displayName."<br>";
	        	echo "<b>Profile URL</b> :".$user_profile->profileURL."<br>";
	        	echo "<b>Image</b> :".$user_profile->photoURL."<br> ";
	        	echo "<img src='".$user_profile->photoURL."'/><br>";
	        	echo "<b>Email</b> :".$user_profile->email."<br>";	        		        		        	
	        	echo "<br> <a href='logout.php'>Logout</a>";
	        }	        

			}
			catch( Exception $e )
			{ 
			
				 switch( $e->getCode() )
				 {
                        case 0 : echo "Unspecified error."; break;
                        case 1 : echo "Hybridauth configuration error."; break;
                        case 2 : echo "Provider not properly configured."; break;
                        case 3 : echo "Unknown or disabled provider."; break;
                        case 4 : echo "Missing provider application credentials."; break;
                        case 5 : echo "Authentication failed. "
                                         . "The user has canceled the authentication or the provider refused the connection.";
                                 break;
                        case 6 : echo "User profile request failed. Most likely the user is not connected "
                                         . "to the provider and he should to authenticate again.";
                                 $twitter->logout();
                                 break;
                        case 7 : echo "User not connected to the provider.";
                                 $twitter->logout();
                                 break;
                        case 8 : echo "Provider does not support this feature."; break;
                }

                // well, basically your should not display this to the end user, just give him a hint and move on..
                echo "<br /><br /><b>Original error message:</b> " . $e->getMessage();

                echo "<hr /><h3>Trace</h3> <pre>" . $e->getTraceAsString() . "</pre>";

			}
	}
	
	
	/*
	*Author: TuyenHH 
	*/
	// START
	 public function checkaccount($type,$value)
    {
        global $db;
        $db->query("SELECT * FROM xdata_account WHERE ".$type."= '".$value."'");
        if($db->num_row())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
	//END
	
	public function checkxid($id)
    {
        global $db;
        $db->query("SELECT * FROM ow_transactions WHERE trans_code = '".$id."'");
        if($db->num_row())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    public function checkpostid($id)
    {
        global $db;
        $db->query("SELECT * FROM raovat_ad WHERE postid = '".$id."'");
        if($db->num_row())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
	public function check_pack_id($id)
    {
        global $db;
        $db->query("SELECT * FROM ow_packages WHERE pack_code = '".$id."'");
        if($db->num_row())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
	public function check_transaction_id($id)
    {
        global $db;
        $db->query("SELECT * FROM ow_transactions WHERE trans_code = '".$id."'");
        if($db->num_row())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
	public function createid()
    {
        $id = "VRM";
        $rdc = rand(7000000,7999999);
        $id = $id."".$rdc;
        return $id;
    }
	public function create_pack_id()
    {
        $id = "";
        $rdc = rand(3000000000,7999999999);
        $id = $id."".$rdc;
        return $id;
    }
    public function createpostid()
    {
        $id = "";
        $rdc = rand(1000000,9999999);
        $id = $id."".$rdc;
        return $id;
    }
	public function createcatid()
    {
        $id = "";
        $rdc = rand(2200000,2299999);
        $id = $id."".$rdc;
        return $id;
    }
	public function create_transaction_id()
    {
        $id = "";
        $rdc = rand(5200000,5699999);
        $id = $id."".$rdc;
        return $id;
    }
    public function generateid($type)
    {
        $id = "";
        switch($type)
        {
			
            case "transaction":
            {
                $id = $this->create_transaction_id();
                do
                {
                    $id = $this->create_transaction_id();
                }
                while(!$this->check_transaction_id($id));
                break;
            }
			case "package":
			{
				$id = $this->create_pack_id();
                do
                {
                    $id = $this->create_pack_id();
                }
                while(!$this->check_pack_id($id));
                break;
			}
            case "post":
            {
                $id = $this->createpostid();
                do
                {
                    $id = $this->createpostid();
                }
                while(!$this->checkpostid($id));
                break;
            }
			case "cat":
			{
				$id = $this->createcatid();
                do
                {
                    $id = $this->createcatid();
                }
                while(!$this->checkpostid($id));
                break;
			}
            default:
                break;
        }
        return $id;
    }
	public function get_day_name($timestamp) 
	{
		$today = new DateTime(); // This object represents current date/time
		$today->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

		$match_date = DateTime::createFromFormat( "Y.m.d\\TH:i", $timestamp );
		$match_date->setTime( 0, 0, 0 ); // reset time part, to prevent partial comparison

		$diff = $today->diff( $match_date );
		$diffDays = (integer)$diff->format( "%R%a" ); // Extract days count in interval
		$name = "";
		switch( $diffDays ) {
			case 0:
				$name = "Hôm nay";
				break;
			case -1:
				$name =  "Hôm qua";
				break;
			case +1:
				$name = "Ngày mai";
				break;
			default:
				$name = date("d/m/Y",strtotime($timestamp));
				break;
		}
		return $name;
	}
	function relative_date($time) {
 
		$today = strtotime(date('M j'));
		 
		$reldays = ($time - $today)/86400;
		 
		if ($reldays >= 0 && $reldays < 1) {
		 
		return 'Hôm nay';
		 
		} else if ($reldays >= 1 && $reldays < 2) {
		 
		return 'Ngày mai';
		 
		} else if ($reldays >= -1 && $reldays < 0) {
		 
		return 'Hôm qua';
		 
		}
		 
		if (abs($reldays) < 7) {
		 
		if ($reldays > 0) {
		 
		$reldays = floor($reldays);
		 
		return  $reldays . ' ngày nữa' . ($reldays != 1 ? '' : '');
		 
		} else {
		 
		$reldays = abs(floor($reldays));
		 
		return $reldays . ' ngày trước' . ($reldays != 1 ? '' : '') ;
		 
		}
		 
		}
		 
		if (abs($reldays) < 182) {
		 
		return date('d/m',$time ? $time : time());
		 
		} else {
		 
		return date('d/m',$time ? $time : time());
		 
		}
 
	}
    function limit_text($text, $limit) {
    if (mb_strlen($text, 'UTF-8') <= $limit) {
        return $text;
    }
    return mb_substr($text, 0, $limit, 'UTF-8') . '...';
}
    public function get_image($category){
    global $db;    
    $db->query("SELECT * FROM hicrm_images WHERE image_status NOT IN(99) AND image_category = '".$category."'  ");
    return $db->fetch_object(true);
    }
    public function get_doctors(){
        global $db;
         $db->query("SELECT *, e.id as employeeid FROM hicrm_employees as e 
		LEFT JOIN hicrm_branchs as b ON e.employee_branch = b.id 
		LEFT JOIN hicrm_departments as d ON e.employee_department = d.id
		LEFT JOIN hicrm_positions as p ON e.employee_position = p.id
		WHERE e.employee_status NOT IN (99)");
		 $employee = $db->fetch_object();
         return $employee;
}
 public function get_events($litmit){
        global $db;
         $db->query("SELECT * FROM hicrm_events WHERE event_status NOT IN (99) ORDER BY event_created_date DESC LIMIT $litmit");
		 $events = $db->fetch_object();
         return $events;
}
 function get_banners($type){
        global $db;
        $db->query("SELECT * FROM hicrm_images WHERE image_status NOT IN (99) AND image_category = '3' AND image_device = '".$type."' ");
        return $db->fetch_object();
    }
 function get_images($category){
    global $db;
    $db->query("SELECT * FROM hicrm_images WHERE image_status NOT IN (99) AND image_category = '".$category."'");
    return $db->fetch_object(true);
}

/**
     * Kiểm tra xem người dùng hiện tại có phải là Quản trị tối cao (Super Admin) không.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $actorCode = $this->get('actor_code');
        return $actorCode === 'super_market';
    }

    /**
     * Kiểm tra xem người dùng hiện tại có phải là Quản lý chợ (Market Manager) không.
     *
     * @return bool
     */
    public function isAdminMarket(){
        $actorCode = $this->get('actor_code');
        return $actorCode === 'admin_market';
    }
    

    /**
     * Kiểm tra quyền truy cập phân hệ (module) cụ thể đối với chợ hiện tại.
     *
     * @param string $module Tên phân hệ cần kiểm tra ('trader', 'stall', 'contract', 'finance', 'foodsafety')
     * @return bool
     */
    public function checkModuleAccess(string $module): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Quản trị tối cao có toàn quyền trên mọi phân hệ
        if (self::isSuperAdmin()) {
            return true;
        }

        $currentMarketId = self::currentMarketId();
        $accessibleMarkets = self::getAccessibleMarketIds();

        // Kiểm tra xem user có quyền truy cập vào chợ hiện tại không
        if (!in_array($currentMarketId, $accessibleMarkets)) {
            return false;
        }

        // 2. Quản lý chợ (admin_market) có toàn quyền trên các phân hệ thuộc chợ của mình
        if (self::isAdminMarket()) {
            return true;
        }

        // 3. Nhân viên vận hành (admin) chỉ được vào phân hệ được tick chọn
        $userId = $this->get('user_id');
        if (!$userId) {
            return false;
        }

        $db = database::getInstance();
        $res = $db->selectOne("
            SELECT id 
            FROM user_market_permissions 
            WHERE user_id = :user_id AND market_id = :market_id AND module_code = :module_code
        ", [
            'user_id'     => $userId,
            'market_id'   => $currentMarketId,
            'module_code' => $module
        ]);

        return !empty($res);
    }

    /**
     * Yêu cầu quyền truy cập phân hệ, nếu không có quyền sẽ hiển thị trang 403 hoặc trả về lỗi JSON.
     *
     * @param string $module Tên phân hệ
     */
    public function requireModuleAccess(string $module) {
        if (!self::checkModuleAccess($module)) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(403);
                echo json_encode([
                    'status'  => 403,
                    'message' => 'Bạn không có quyền truy cập chức năng này tại chợ đang chọn.'
                ], JSON_UNESCAPED_UNICODE);
                exit();
            } else {
                http_response_code(403);
                // Hiển thị giao diện báo lỗi 403
                echo "<div style='padding: 50px; text-align: center; font-family: sans-serif;'>";
                echo "<h1 style='color: #e74c3c; font-size: 48px; margin-bottom: 10px;'>403 Forbidden</h1>";
                echo "<p style='color: #7f8c8d; font-size: 18px;'>Bạn không có quyền truy cập vào phân hệ này tại chợ hiện tại.</p>";
                echo "<a href='" . XC_URL . "admin/dashboard' style='display: inline-block; margin-top: 20px; padding: 10px 20px; background: #3498db; color: #fff; text-decoration: none; border-radius: 4px;'>Quay lại Trang chủ</a>";
                echo "</div>";
                exit();
            }
        }
    }

    /**
     * Lấy ID chợ hiện tại đang hoạt động từ Session.
     * Nếu chưa chọn, tự động lấy chợ hợp lệ đầu tiên hoặc mặc định là 1.
     *
     * @return int
     */
    public function currentMarketId(): int {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $marketId = $this->get('active_market_id');
        if ($marketId) {
            return (int)$marketId;
        }

        // Tìm chợ hợp lệ đầu tiên được quyền truy cập
        $accessible = self::getAccessibleMarketIds();
        if (!empty($accessible)) {
            $marketId = $accessible[0];
        } else {
            $marketId = 1; // Mặc định dự phòng
        }

        // `general` is itself the session helper.  This class does not have a
        // `$helper` dependency (only controllers do), so using it here caused
        // the admin dashboard to fail before it could render.
        $this->set('active_market_id', (int)$marketId);
        return (int)$marketId;
    }

    /**
     * Lấy danh sách các ID chợ mà người dùng hiện tại được phép truy cập.
     * Super Admin có quyền truy cập toàn bộ các chợ đang hoạt động.
     *
     * @return array
     */
    public function getAccessibleMarketIds(): array {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cached = $this->get('accessible_market_ids');
        if (is_array($cached)) {
            return $cached;
        }

        $userId = $this->get('user_id');
        if (!$userId) {
            return [];
        }

        $db = database::getInstance();
        if (self::isSuperAdmin()) {
            // Super Admin truy cập toàn bộ các chợ đang hoạt động
            $rows = $db->select("SELECT id FROM markets WHERE status_code = 'active'");
            $ids = array_map(function($r) { return (int)$r['id']; }, $rows);
        } else {
            // Nhân viên hoặc Quản lý chợ chỉ truy cập các chợ được phân công trong user_markets
            $rows = $db->select("
                SELECT market_id 
                FROM user_markets um
                JOIN markets m ON um.market_id = m.id
                WHERE um.user_id = :user_id AND m.status_code = 'active'
            ", ['user_id' => $userId]);
            $ids = array_map(function($r) { return (int)$r['market_id']; }, $rows);
        }

        $this->set('accessible_market_ids', $ids);
        return $ids;
    }

    /**
     * Tự động bổ sung điều kiện lọc theo phạm vi chợ (market_id) vào câu truy vấn SQL.
     *
     * @param string $sql Câu truy vấn gốc
     * @param string $alias Tên alias của bảng (nếu có, ví dụ: 'a' hoặc 'stalls')
     * @return string Câu truy vấn đã được bổ sung điều kiện lọc
     */
    public function applyScope(string $sql, string $alias = ''): string {
        $marketId = self::currentMarketId();
        
        $prefix = $alias ? "{$alias}." : "";
        $condition = "{$prefix}market_id = {$marketId}";
        
        // Kiểm tra xem đã có mệnh đề WHERE trong câu truy vấn chưa
        if (stripos($sql, 'where') !== false) {
            return $sql . " AND " . $condition;
        } else {
            return $sql . " WHERE " . $condition;
        }
    }

    /**
     * Kiểm tra quyền thực hiện thao tác ghi/sửa dữ liệu đối với một chợ cụ thể.
     * Ném ra Exception nếu không có quyền.
     *
     * @param int|string $marketId ID chợ cần kiểm tra
     * @throws Exception
     */
    public function checkWritePermission($marketId) {
        $accessible = self::getAccessibleMarketIds();
        if (!in_array((int)$marketId, $accessible) && !self::isSuperAdmin()) {
            throw new Exception("Bạn không có quyền thực hiện thao tác tại chợ này.");
        }
    }
     public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Lấy giá trị từ session
     */
    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Xóa một key trong session
     */
    public function delete($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Hủy toàn bộ session (Đăng xuất)
     */
    public function destroy() {
        session_unset();
        session_destroy();
    }

    /**
     * Kiểm tra xem người dùng đã đăng nhập chưa
     */
    public function isLoggedIn() {
        return self::get('user_logged_in') === true;
    }

    /**
     * Yêu cầu đăng nhập, nếu chưa đăng nhập sẽ chuyển hướng
     */
    public function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: ' . BASE_URL . 'home/login');
            exit();
        }
    }

    /**
     * Kiểm tra vai trò của người dùng hiện tại
     * @param string|array $allowedRoles Danh sách vai trò được cho phép
     */
    public function hasRole($allowedRoles) {
        $userRole = self::get('user_role');
        if (!$userRole) {
            return false;
        }

        if (is_array($allowedRoles)) {
            return in_array($userRole, $allowedRoles);
        }

        return $userRole === $allowedRoles;
    }

    /**
     * Yêu cầu vai trò cụ thể, nếu không đủ quyền sẽ chuyển hướng 403
     */
    public function requireRole($allowedRoles) {
        self::requireLogin();
        if (!self::hasRole($allowedRoles)) {
            header('Location: ' . BASE_URL . 'errors/forbidden');
            exit();
        }
    }

    public function requireAdmin() {
        self::requireLogin();
        $group = self::get('user_group');
        if ($group != 1 && $group != 2) {
            header('Location: ' . BASE_URL . 'errors/forbidden');
            exit();
        }
    }
    public function test(){
        return "Hello from General class!";
    }
	
	
}
