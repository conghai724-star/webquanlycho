<?php

Class homeController Extends baseController
{
	public function index()
    {
        
    }
    public function introduce(){
        $this->view->show("gioi-thieu");
    }
   
    public function guidelines(){
        $this->view->show("huong-dan");
    }
    public function manage_applicants(){
        global $db;
        $db->query("SELECT * FROM hicrm_users WHERE user_group = '4' ORDER BY id DESC");
        $applicants = $db->fetch_object();
        $this->view->data['applicants'] = $applicants;
        $this->view->show("quan-ly-ung-vien");
    }
    public function manage_jobs(){
        global $db;
        $db->query("SELECT * FROM hicrm_employers ORDER BY created_at DESC");
        $company = $db->fetch_object();
        $this->view->data['company'] = $company;
        $this->view->show("quan-ly-viec-lam");
    }
    public function introduce_jobs(){
        $this->view->show("gioi-thieu-san-viec-lam");
    }
    public function introduce_process(){
        $this->view->show("quy-trinh-san-viec-lam");
    }
     public function results_jobs(){
        $this->view->show("ket-qua-san-viec-lam");
    }
    public function online_jobs(){
        $this->view->show("san-viec-lam-online");
    }
    public function contact(){
        $this->view->show("lien-he");
    }
    public function events(){
        $this->view->show("tin-tuc");
    }
    public function register(){
        global $db;
        $db->query("SELECT * FROM hicrm_employers ORDER BY created_at DESC");
        $company = $db->fetch_object();
        $this->view->data['company'] = $company;
        $this->view->show("dang-ky");
    }
    public function logout(){
		session_unset();
		header('Location:' .XC_URL);
	}
    private function employerDashboardContext(){
        global $db;
        $user = null;
        $employer = null;

        if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != ""){
            $uid = $db->escapestring($_SESSION['user']['id']);
            $db->query("SELECT * FROM hicrm_users WHERE id = '".$uid."' AND user_group = '2' LIMIT 1");
            if($db->num_row() > 0){
                $user = $db->fetch_object(true);
            }
        }

        if(!$user){
            $db->query("SELECT * FROM hicrm_users WHERE user_group = '2' ORDER BY employee_id DESC, id ASC LIMIT 1");
            if($db->num_row() > 0){
                $user = $db->fetch_object(true);
            }
        }

        if($user && intval($user->employee_id) > 0){
            $db->query("SELECT e.*, c.job_category_name FROM hicrm_employers e LEFT JOIN hicrm_job_categories c ON e.job_category_id = c.id WHERE e.id = '".intval($user->employee_id)."' LIMIT 1");
            if($db->num_row() > 0){
                $employer = $db->fetch_object(true);
            }
        }

        if(!$employer){
            $db->query("SELECT e.*, c.job_category_name FROM hicrm_employers e LEFT JOIN hicrm_job_categories c ON e.job_category_id = c.id ORDER BY e.id ASC LIMIT 1");
            if($db->num_row() > 0){
                $employer = $db->fetch_object(true);
            }
        }

        return array('user' => $user, 'employer' => $employer);
    }

    private function employerDashboardStats($employer_id){
        global $db;
        $stats = array('total' => 0, 'published' => 0, 'pending' => 0, 'closed' => 0);
        if(!$employer_id){
            return $stats;
        }

        $db->query("SELECT status, COUNT(*) AS total FROM hicrm_job_posts WHERE employer_id = '".intval($employer_id)."' GROUP BY status");
        $rows = $db->fetch_object();
        foreach($rows as $row){
            $stats['total'] += intval($row->total);
            if(isset($stats[$row->status])){
                $stats[$row->status] = intval($row->total);
            }
        }
        return $stats;
    }

    private function employerDashboardTableExists($table_name){
        global $db;
        $table_name = $db->escapestring($table_name);
        $db->query("SHOW TABLES LIKE '".$table_name."'");
        return $db->num_row() > 0;
    }

    public function employers(){
        global $db;
        
        $uid = $db->escapestring($_SESSION['user']['id']);
        if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != "" && $_SESSION['user']['group'] == '2'){
           $context = $this->employerDashboardContext();
            $employer = $context['employer'];
            $employer_id = $employer ? intval($employer->id) : 0;

            $db->query("SELECT * FROM hicrm_job_categories ORDER BY job_category_name ASC");
            $job_categories = $db->fetch_object();

            $db->query("SELECT id, province_code, province_name, province_keyword, created_at FROM hicrm_provinces ORDER BY province_name ASC");
            $job_provinces = $db->fetch_object();

            $job_posts = array();
            if($employer_id > 0){
                $db->query("SELECT p.*, c.job_category_name FROM hicrm_job_posts p LEFT JOIN hicrm_job_categories c ON p.job_category_id = c.id WHERE p.employer_id = '".$employer_id."' ORDER BY p.created_at DESC, p.id DESC");
                $job_posts = $db->fetch_object();
            }

            $db->query("SELECT s.*, c.job_category_name FROM hicrm_student_profile s LEFT JOIN hicrm_job_categories c ON s.student_major_id = c.id ORDER BY s.student_gpa DESC, s.id DESC LIMIT 60");
            $students = $db->fetch_object();

            if($this->employerDashboardTableExists('hicrm_candidates')){
                $db->query("SELECT ca.*, u.user_email, u.user_phone, jc.job_category_name FROM hicrm_candidates ca LEFT JOIN hicrm_users u ON ca.user_id = u.id LEFT JOIN hicrm_job_categories jc ON ca.major = jc.id ORDER BY ca.updated_at DESC, ca.id DESC LIMIT 60");
                $candidates = $db->fetch_object();
            }else{
                $db->query("SELECT id, full_name, user_email, user_phone, user_created_at AS updated_at FROM hicrm_users WHERE user_group = '4' ORDER BY id DESC LIMIT 60");
                $candidates = $db->fetch_object();
            }
            $db->query("SELECT * FROM hicrm_salary ORDER BY id ASC");
            $salary = $db->fetch_object();

            $this->view->data['employer_user'] = $context['user'];
            $this->view->data['employer'] = $employer;
            $this->view->data['job_categories'] = $job_categories;
            $this->view->data['job_provinces'] = $job_provinces;
            $this->view->data['job_posts'] = $job_posts;
            $this->view->data['job_stats'] = $this->employerDashboardStats($employer_id);
            $this->view->data['students'] = $students;
            $this->view->data['candidates'] = $candidates;
            $this->view->data['salary'] = $salary;
            $this->view->show("employer-dashboard");
        }else{
            header("Location: ".XC_URL);
            exit();
        }
        
        
       
    }
    public function verify_email($para){
            global $db;
            if(isset($para[1]) && $para[1] != "")
            {
                $token = $para[1];
                $db->query("SELECT * FROM hicrm_users WHERE user_email_verify_token='$token' AND user_is_verified=0");
                $user_email_verified_at = $db->fetch_object(true)->user_email_verified_at;
                if($db->num_row() > 0)
                {
                    $db->query("UPDATE hicrm_users SET user_is_verified=1, user_email_verify_token='' WHERE user_email_verify_token='$token'");
                   $page_title = "Chúc mừng! Xác thực email thành công";
                   $page_description = "Cảm ơn bạn đã xác thực email. Bạn có thể đăng nhập vào hệ thống ngay bây giờ.";
                    $this->view->data['page_description'] = $page_description;
                    $this->view->data['page_title'] = $page_title;
                    $this->view->data['verify_email'] = 1;
                    $this->view->show("404");
                }elseif (strtotime($user_email_verified_at) < time()) {
                        $this->view->data['page_title'] = "⏰ Link đã hết hạn!";
                        $this->view->data['page_description'] = "Liên kết xác thực đã hết hạn. Vui lòng đăng ký lại để nhận liên kết mới.";
                        $this->view->data['verify_email'] = 0; 
                        $this->view->show("404"); 
                } else
                {
                    $page_title = "Xác thực email thất bại";
                    $page_description = "Liên kết xác thực đã được sử dụng. Vui lòng kiểm tra lại hoặc liên hệ với bộ phận hỗ trợ.";
                    $this->view->data['page_description'] = $page_description;
                    $this->view->data['page_title'] = $page_title;
                    $this->view->show("404");
                }
            }
    }
}
