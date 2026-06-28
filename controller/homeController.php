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
    public function manage_applicants($para = array()){
        global $db;
        $keyword = trim(isset($_GET['keyword']) ? $_GET['keyword'] : '');
        $provinceId = intval(isset($_GET['province_id']) ? $_GET['province_id'] : 0);
        $categoryId = intval(isset($_GET['job_category_id']) ? $_GET['job_category_id'] : 0);
        $salaryId = intval(isset($_GET['salary_id']) ? $_GET['salary_id'] : 0);
        $degree = trim(isset($_GET['degree']) ? $_GET['degree'] : '');
        $workType = trim(isset($_GET['work_type']) ? $_GET['work_type'] : '');
        $page = max(1, intval(isset($_GET['page']) ? $_GET['page'] : 1));
        $perPage = 16;

        $where = array("ca.status = 3", "ca.is_seeking = 1", "(u.id IS NULL OR u.user_status = 1)");
        if($keyword !== ''){
            $search = $db->escapestring($keyword);
            $where[] = "(ca.full_name LIKE '%".$search."%' OR ca.desired_position LIKE '%".$search."%' OR ca.soft_skills LIKE '%".$search."%' OR ca.phone LIKE '%".$search."%' OR ca.school_name LIKE '%".$search."%' OR ca.address_detail LIKE '%".$search."%' OR ca.career_goal LIKE '%".$search."%' OR u.user_email LIKE '%".$search."%' OR u.user_phone LIKE '%".$search."%' OR jc.job_category_name LIKE '%".$search."%')";
        }
        if($provinceId > 0){ $where[] = "ca.desired_province_id = '".$provinceId."'"; }
        if($categoryId > 0){ $where[] = "ca.major = '".$categoryId."'"; }
        if($salaryId > 0){ $where[] = "ca.desired_salary = '".$salaryId."'"; }
        if($degree !== ''){ $where[] = "ca.degree = '".$db->escapestring($degree)."'"; }
        if($workType !== ''){ $where[] = "ca.desired_work_type = '".$db->escapestring($workType)."'"; }

        $baseSql = "FROM hicrm_candidates ca
            LEFT JOIN hicrm_users u ON u.id = ca.user_id
            LEFT JOIN hicrm_job_categories jc ON jc.id = ca.major
            LEFT JOIN hicrm_provinces current_pr ON current_pr.id = ca.province_id
            LEFT JOIN hicrm_provinces desired_pr ON desired_pr.id = ca.desired_province_id
            LEFT JOIN hicrm_salary sal ON sal.id = ca.desired_salary
            WHERE ".implode(' AND ', $where);
        $db->query("SELECT COUNT(ca.id) AS total ".$baseSql);
        $totalCandidates = intval($db->fetch_object(true)->total);
        $totalPages = max(1, ceil($totalCandidates / $perPage));
        if($page > $totalPages){ $page = $totalPages; }
        $offset = ($page - 1) * $perPage;

        $db->query("SELECT ca.*, u.user_email, u.user_phone, u.user_group,
                jc.job_category_name, current_pr.province_name, desired_pr.province_name AS desired_province_name, sal.salary_name,
                COALESCE((SELECT FLOOR(SUM(DATEDIFF(COALESCE(ce.end_date, CURDATE()), ce.start_date)) / 365)
                    FROM hicrm_candidate_experiences ce WHERE ce.candidate_id = ca.id), 0) AS experience_years
            ".$baseSql."
            ORDER BY ca.updated_at DESC, ca.id DESC
            LIMIT ".$offset.",".$perPage);
        $candidates = $db->fetch_object();

        $db->query("SELECT id, province_name FROM hicrm_provinces WHERE EXISTS (SELECT 1 FROM hicrm_candidates ca WHERE ca.desired_province_id = hicrm_provinces.id AND ca.status = 3 AND ca.is_seeking = 1) ORDER BY province_name ASC");
        $candidateProvinces = $db->fetch_object();
        $db->query("SELECT id, job_category_name FROM hicrm_job_categories WHERE EXISTS (SELECT 1 FROM hicrm_candidates ca WHERE ca.major = hicrm_job_categories.id AND ca.status = 3 AND ca.is_seeking = 1) ORDER BY job_category_name ASC");
        $candidateCategories = $db->fetch_object();
        $db->query("SELECT id, salary_name FROM hicrm_salary WHERE EXISTS (SELECT 1 FROM hicrm_candidates ca WHERE ca.desired_salary = hicrm_salary.id AND ca.status = 3 AND ca.is_seeking = 1) ORDER BY id ASC");
        $candidateSalaries = $db->fetch_object();
        $db->query("SELECT DISTINCT degree FROM hicrm_candidates WHERE status = 3 AND is_seeking = 1 AND degree IS NOT NULL AND degree <> '' ORDER BY degree ASC");
        $candidateDegrees = $db->fetch_object();
        $db->query("SELECT DISTINCT desired_work_type FROM hicrm_candidates WHERE status = 3 AND is_seeking = 1 AND desired_work_type IS NOT NULL AND desired_work_type <> '' ORDER BY desired_work_type ASC");
        $candidateWorkTypes = $db->fetch_object();

        $this->view->data['candidates'] = $candidates;
        $this->view->data['candidate_filters'] = array('keyword' => $keyword, 'province_id' => $provinceId, 'job_category_id' => $categoryId, 'salary_id' => $salaryId, 'degree' => $degree, 'work_type' => $workType);
        $this->view->data['candidate_provinces'] = $candidateProvinces;
        $this->view->data['candidate_categories'] = $candidateCategories;
        $this->view->data['candidate_salaries'] = $candidateSalaries;
        $this->view->data['candidate_degrees'] = $candidateDegrees;
        $this->view->data['candidate_work_types'] = $candidateWorkTypes;
        $this->view->data['candidate_page'] = $page;
        $this->view->data['candidate_total_pages'] = $totalPages;
        $this->view->data['candidate_total'] = $totalCandidates;
        $this->view->show("quan-ly-ung-vien");
    }
    public function manage_jobs($para = array()){
        global $db;
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : "";
        $province_id = isset($_GET['province_id']) ? intval($_GET['province_id']) : 0;
        $job_category_id = isset($_GET['job_category_id']) ? intval($_GET['job_category_id']) : 0;
        $salary_id = isset($_GET['salary_id']) ? intval($_GET['salary_id']) : 0;
        $work_type = isset($_GET['work_type']) ? trim($_GET['work_type']) : "";
        $post_type = isset($_GET['post_type']) ? trim($_GET['post_type']) : "";
        $employer_id = isset($_GET['employer_id']) ? intval($_GET['employer_id']) : 0;
        $page = 1;
        if(is_array($para) && count($para) > 0){
            foreach($para as $index => $value){
                if($value === "page" && isset($para[$index + 1]) && intval($para[$index + 1]) > 0){
                    $page = intval($para[$index + 1]);
                    break;
                }
                if(intval($value) > 0){
                    $page = intval($value);
                    break;
                }
            }
        }
        if(isset($_GET['page']) && intval($_GET['page']) > 0){
            $page = intval($_GET['page']);
        }
        $per_page = 20;

        $where = array("p.status = 'published'");
        if($keyword !== ""){
            $keyword_sql = $db->escapestring($keyword);
            $where[] = "(p.title LIKE '%".$keyword_sql."%' OR p.job_description LIKE '%".$keyword_sql."%' OR e.company_name LIKE '%".$keyword_sql."%' OR c.job_category_name LIKE '%".$keyword_sql."%')";
        }
        if($province_id > 0){
            $where[] = "p.province_id = '".$province_id."'";
        }
        if($job_category_id > 0){
            $where[] = "p.job_category_id = '".$job_category_id."'";
        }
        if($salary_id > 0){
            $where[] = "p.salary_id = '".$salary_id."'";
        }
        if($work_type !== "" && $work_type !== "all"){
            $where[] = "p.work_type = '".$db->escapestring($work_type)."'";
        }
        if($post_type === "urgent"){
            $where[] = "p.job_post_type IN ('urgent', 'hot')";
        }
        if($employer_id > 0){
            $where[] = "p.employer_id = '".$employer_id."'";
        }

        $base_sql = "FROM hicrm_job_posts p
            LEFT JOIN hicrm_employers e ON e.id = p.employer_id
            LEFT JOIN hicrm_job_categories c ON c.id = p.job_category_id
            LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
            LEFT JOIN hicrm_salary s ON s.id = p.salary_id
            WHERE ".implode(" AND ", $where);

        $db->query("SELECT COUNT(p.id) AS total ".$base_sql);
        $total_jobs = intval($db->fetch_object(true)->total);
        $total_pages = max(1, ceil($total_jobs / $per_page));
        if($page > $total_pages){ $page = $total_pages; }
        $offset = ($page - 1) * $per_page;

        $db->query("SELECT p.*, e.company_name, e.logo_url, c.job_category_name, pr.province_name, s.salary_name
            ".$base_sql."
            ORDER BY FIELD(p.job_post_type, 'hot', 'urgent', 'normal'), p.published_at DESC, p.created_at DESC, p.id DESC
            LIMIT ".$offset.",".$per_page);
        $jobs = $db->fetch_object();

        $db->query("SELECT * FROM hicrm_employers ORDER BY created_at DESC");
        $this->view->data['company'] = $db->fetch_object();
        $db->query("SELECT id, job_category_name FROM hicrm_job_categories ORDER BY job_category_name ASC");
        $this->view->data['job_categories'] = $db->fetch_object();
        $db->query("SELECT id, province_name FROM hicrm_provinces ORDER BY province_name ASC");
        $this->view->data['job_provinces'] = $db->fetch_object();
        $db->query("SELECT id, salary_name FROM hicrm_salary ORDER BY id ASC");
        $this->view->data['salaries'] = $db->fetch_object();
        $this->view->data['jobs'] = $jobs;
        $this->view->data['job_filters'] = array(
            'keyword' => $keyword,
            'province_id' => $province_id,
            'job_category_id' => $job_category_id,
            'salary_id' => $salary_id,
            'work_type' => $work_type,
            'post_type' => $post_type,
            'employer_id' => $employer_id
        );
        $this->view->data['page'] = $page;
        $this->view->data['per_page'] = $per_page;
        $this->view->data['total_jobs'] = $total_jobs;
        $this->view->data['total_pages'] = $total_pages;
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
    public function events($para = array()){
        global $db;
        $detailId = is_array($para) && isset($para[1]) ? intval($para[1]) : 0;
        if($detailId > 0){
            return $this->news_detail($para);
        }
        $keyword = trim(isset($_GET['keyword']) ? $_GET['keyword'] : '');
        $activeSection = isset($_GET['section']) ? trim($_GET['section']) : 'all';
        if(!in_array($activeSection, array('all','site','employer','seeker'), true)){ $activeSection = 'all'; }
        $sectionConfig = array(
            'site' => array('types' => array(0,3), 'page_param' => 'site_page', 'per_page' => 9),
            'employer' => array('types' => array(1), 'page_param' => 'employer_page', 'per_page' => 6),
            'seeker' => array('types' => array(2), 'page_param' => 'seeker_page', 'per_page' => 9)
        );
        $sections = array();
        $counts = array('all' => 0);
        foreach($sectionConfig as $key => $config){
            $page = max(1, intval(isset($_GET[$config['page_param']]) ? $_GET[$config['page_param']] : 1));
            $where = array("event_status = 1", "event_type IN (".implode(',', array_map('intval', $config['types'])).")");
            if($keyword !== ''){
                $kw = $db->escapestring($keyword);
                $where[] = "(event_name LIKE '%".$kw."%' OR event_description LIKE '%".$kw."%' OR event_content LIKE '%".$kw."%')";
            }
            $whereSql = implode(' AND ', $where);
            $db->query("SELECT COUNT(id) AS total FROM hicrm_events WHERE ".$whereSql);
            $total = intval($db->fetch_object(true)->total);
            $counts[$key] = $total;
            $counts['all'] += $total;
            $totalPages = max(1, ceil($total / $config['per_page']));
            if($page > $totalPages){ $page = $totalPages; }
            $offset = ($page - 1) * $config['per_page'];
            $db->query("SELECT * FROM hicrm_events WHERE ".$whereSql." ORDER BY event_hot DESC, event_created_date DESC, id DESC LIMIT ".$offset.",".$config['per_page']);
            $sections[$key] = array('items' => $db->fetch_object(), 'page' => $page, 'total_pages' => $totalPages, 'total' => $total, 'page_param' => $config['page_param']);
        }
        $db->query("SELECT * FROM hicrm_events WHERE event_status = 1 ORDER BY event_hot DESC, event_created_date DESC, id DESC LIMIT 5");
        $this->view->data['featured_news'] = $db->fetch_object();
        $this->view->data['news_sections'] = $sections;
        $this->view->data['news_counts'] = $counts;
        $this->view->data['news_keyword'] = $keyword;
        $this->view->data['news_active_section'] = $activeSection;
        $this->view->show("tin-tuc");
    }
    public function news_detail($para = array()){
        global $db;
        $db->query("CREATE TABLE IF NOT EXISTS `hicrm_event_comments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `event_id` int(11) NOT NULL,
            `parent_id` int(11) DEFAULT NULL,
            `comment_name` varchar(255) NOT NULL,
            `comment_email` varchar(255) DEFAULT NULL,
            `comment_content` text NOT NULL,
            `admin_reply` text DEFAULT NULL,
            `reply_user_id` int(11) DEFAULT NULL,
            `status` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT NULL,
            `replied_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_event_status_created` (`event_id`,`status`,`created_at`),
            KEY `idx_status_created` (`status`,`created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $newsId = is_array($para) && isset($para[1]) && preg_match('/^(\d+)/', (string)$para[1], $matches) ? intval($matches[1]) : 0;
        if($newsId <= 0 && isset($_GET['id'])){ $newsId = intval($_GET['id']); }
        if($newsId <= 0){ header("Location: ".XC_URL."/tin-tuc-su-kien.html"); exit(); }
        $db->query("SELECT * FROM hicrm_events WHERE id = '".$newsId."' AND event_status = 1 LIMIT 1");
        if($db->num_row() <= 0){ header("Location: ".XC_URL."/tin-tuc-su-kien.html"); exit(); }
        $news = $db->fetch_object(true);
        $db->query("SELECT * FROM hicrm_events WHERE event_status = 1 AND id <> '".$newsId."' AND event_type = '".intval($news->event_type)."' ORDER BY event_hot DESC, event_created_date DESC, id DESC LIMIT 5");
        $this->view->data['news_detail'] = $news;
        $this->view->data['related_news'] = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_events WHERE event_status = 1 AND id <> '".$newsId."' ORDER BY event_created_date DESC, id DESC LIMIT 6");
        $this->view->data['more_news'] = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_event_comments WHERE event_id = '".$newsId."' AND status = 1 ORDER BY created_at DESC, id DESC");
        $this->view->data['news_comments'] = $db->fetch_object();
        $this->view->show("tintuc_detail");
    }
    public function add_news_comment() {
        global $db;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $eventId = isset($_POST['event_id']) ? intval($_POST['event_id']) : 0;
            $parentId = isset($_POST['parent_id']) && $_POST['parent_id'] !== '' ? intval($_POST['parent_id']) : 'NULL';
            $name = trim(isset($_POST['comment_name']) ? $_POST['comment_name'] : '');
            $email = trim(isset($_POST['comment_email']) ? $_POST['comment_email'] : '');
            $content = trim(isset($_POST['comment_content']) ? $_POST['comment_content'] : '');

            if ($eventId > 0 && $name !== '' && $content !== '') {
                $escName = $db->escapestring($name);
                $escEmail = $db->escapestring($email);
                $escContent = $db->escapestring($content);
                $parentIdVal = $parentId === 'NULL' ? 'NULL' : intval($parentId);

                $db->query("INSERT INTO `hicrm_event_comments` (`event_id`, `parent_id`, `comment_name`, `comment_email`, `comment_content`, `status`, `created_at`) 
                            VALUES ('".$eventId."', ".$parentIdVal.", '".$escName."', '".$escEmail."', '".$escContent."', 1, NOW())");
                
                header('Content-Type: application/json');
                echo json_encode(array('status' => 'success', 'message' => 'Bình luận thành công!'));
                exit();
            }
        }
        header('Content-Type: application/json');
        echo json_encode(array('status' => 'error', 'message' => 'Dữ liệu không hợp lệ.'));
        exit();
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
    private function candidateProfileCompleteness($candidate){
        if(!$candidate){ return 0; }
        $fields = array(
            'full_name', 'date_of_birth', 'gender', 'phone', 'user_email', 'avatar_url', 'province_id', 'address_detail',
            'degree', 'major', 'graduation_year', 'school_name', 'soft_skills', 'career_goal_short', 'career_goal_long',
            'desired_position', 'desired_salary', 'desired_province_id', 'desired_work_type', 'cv_url'
        );
        $completed = 0;
        foreach($fields as $field){
            $value = isset($candidate->$field) ? $candidate->$field : null;
            if($value !== null && trim((string)$value) !== '' && $value !== '0'){
                $completed++;
            }
        }
        return (int)round(($completed / count($fields)) * 100);
    }

    public function candidateDashboard($para = array()){
        global $db;
        // if(!isset($_SESSION['user']['id']) || $_SESSION['user']['id'] === '' || (string)($_SESSION['user']['group'] ?? '') !== '4'){
        //     header("Location: ".XC_URL);
        //     exit();
        // }

        $sessionUserId = (int)$_SESSION['user']['id'];
        $uid = $db->escapestring($sessionUserId);
        $db->query("SELECT * FROM hicrm_users WHERE id = '".$uid."' LIMIT 1");
        if($db->num_row() <= 0){
            header("Location: ".XC_URL);
            exit();
        }
        $user = $db->fetch_object(true);

        $requestedCandidateId = 0;
        if(is_array($para) && isset($para[1])){ $requestedCandidateId = (int)$para[1]; }
        if($requestedCandidateId <= 0 && isset($_GET['id'])){ $requestedCandidateId = (int)$_GET['id']; }

        $candidate = false;
        if($requestedCandidateId > 0){
            $db->query("SELECT * FROM hicrm_candidates WHERE id = '".intval($requestedCandidateId)."' AND user_id = '".$uid."' LIMIT 1");
            if($db->num_row() > 0){
                $candidate = $db->fetch_object(true);
            }else{
                $db->query("SELECT id FROM hicrm_candidates WHERE user_id = '".$uid."' LIMIT 1");
                if($db->num_row() > 0){
                    $ownCandidate = $db->fetch_object(true);
                    header("Location: ".XC_URL."/quan-ly-ho-so-ung-vien.html/".intval($ownCandidate->id));
                }else{
                    header("Location: ".XC_URL."/quan-ly-ho-so-ung-vien.html");
                }
                exit();
            }
        }else{
            $db->query("SELECT * FROM hicrm_candidates WHERE user_id = '".$uid."' LIMIT 1");
            $candidate = $db->fetch_object(true);
        }
        if(!$candidate){
            $fullName = trim((string)$user->full_name);
            if($fullName === ''){ $fullName = strstr((string)$user->user_email, '@', true) ?: 'Ứng viên'; }
            $phone = isset($user->user_phone) ? $user->user_phone : '';
            $db->query("INSERT INTO hicrm_candidates (user_id, full_name, phone, status, profile_completeness, created_at, updated_at)
                VALUES ('".$uid."', '".$db->escapestring($fullName)."', '".$db->escapestring($phone)."', 1, 0, NOW(), NOW())");
            $db->query("SELECT * FROM hicrm_candidates WHERE user_id = '".$uid."' LIMIT 1");
            $candidate = $db->fetch_object(true);
        }
        $candidate->user_email = isset($user->user_email) ? $user->user_email : '';
        $completeness = $this->candidateProfileCompleteness($candidate);
        if((int)$candidate->profile_completeness !== $completeness){
            $db->query("UPDATE hicrm_candidates SET profile_completeness = '".$completeness."' WHERE id = '".intval($candidate->id)."' LIMIT 1");
            $candidate->profile_completeness = $completeness;
        }

        $db->query("SELECT id, province_name FROM hicrm_provinces ORDER BY province_name ASC");
        $provinces = $db->fetch_object();
        $db->query("SELECT id, job_category_name FROM hicrm_job_categories ORDER BY job_category_name ASC");
        $categories = $db->fetch_object();
        $db->query("SELECT id, salary_name FROM hicrm_salary ORDER BY id ASC");
        $salaries = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_candidate_experiences WHERE candidate_id = '".intval($candidate->id)."' ORDER BY start_date DESC, id DESC");
        $experiences = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_candidate_certificates WHERE candidate_id = '".intval($candidate->id)."' ORDER BY issued_date DESC, id DESC");
        $certificates = $db->fetch_object();

        $applications = array();
        if($this->employerDashboardTableExists('hicrm_job_applications')){
            $db->query("SELECT a.*, p.title, p.work_type, p.deadline, e.company_name, e.logo_url, pr.province_name, s.salary_name
                FROM hicrm_job_applications a
                LEFT JOIN hicrm_job_posts p ON p.id = a.job_post_id
                LEFT JOIN hicrm_employers e ON e.id = p.employer_id
                LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
                LEFT JOIN hicrm_salary s ON s.id = p.salary_id
                WHERE a.candidate_id = '".intval($candidate->id)."'
                ORDER BY a.applied_at DESC, a.id DESC");
            $applications = $db->fetch_object();
        }

        $this->view->data['candidate_user'] = $user;
        $this->view->data['candidate'] = $candidate;
        $this->view->data['candidate_completeness'] = $completeness;
        $this->view->data['candidate_provinces'] = $provinces;
        $this->view->data['candidate_categories'] = $categories;
        $this->view->data['candidate_salaries'] = $salaries;
        $this->view->data['candidate_experiences'] = $experiences;
        $this->view->data['candidate_certificates'] = $certificates;
        $this->view->data['candidate_applications'] = $applications;
        $this->view->show("quan-ly-ho-so-ung-vien");
    }

    public function job_detail($para = array()){
        global $db;
        $jobId = 0;
        if(is_array($para) && isset($para[1])){ $jobId = intval($para[1]); }
        if($jobId <= 0 && isset($_GET['job_id'])){ $jobId = intval($_GET['job_id']); }
        if($jobId <= 0){
            header("Location: ".XC_URL."/quan-ly-viec-lam.html");
            exit();
        }
        $db->query("SELECT p.*, e.company_name, e.logo_url, e.address_detail AS company_address,
                e.company_size, e.description AS company_description, e.website_url, e.verified_status,
                c.job_category_name, pr.province_name, s.salary_name
            FROM hicrm_job_posts p
            LEFT JOIN hicrm_employers e ON e.id = p.employer_id
            LEFT JOIN hicrm_job_categories c ON c.id = p.job_category_id
            LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
            LEFT JOIN hicrm_salary s ON s.id = p.salary_id
            WHERE p.id = '".$jobId."' AND p.status = 'published' LIMIT 1");
        if($db->num_row() <= 0){
            header("Location: ".XC_URL."/quan-ly-viec-lam.html");
            exit();
        }
        $jobDetail = $db->fetch_object(true);
        $this->view->data['job_detail'] = $jobDetail;
        $relatedJobs = array();
        if((int)$jobDetail->job_category_id > 0){
            $db->query("SELECT p.id, p.title, p.deadline, p.work_type, p.job_post_type,
                    e.company_name, e.logo_url, pr.province_name, s.salary_name
                FROM hicrm_job_posts p
                LEFT JOIN hicrm_employers e ON e.id = p.employer_id
                LEFT JOIN hicrm_provinces pr ON pr.id = p.province_id
                LEFT JOIN hicrm_salary s ON s.id = p.salary_id
                WHERE p.status = 'published'
                    AND p.job_category_id = '".intval($jobDetail->job_category_id)."'
                    AND p.id <> '".$jobId."'
                ORDER BY FIELD(p.job_post_type, 'hot', 'urgent', 'normal'), p.published_at DESC, p.id DESC
                LIMIT 4");
            $relatedJobs = $db->fetch_object();
        }
        $this->view->data['related_jobs'] = $relatedJobs;
        $db->query("UPDATE hicrm_job_posts SET views_count = COALESCE(views_count, 0) + 1 WHERE id = '".$jobId."' LIMIT 1");
        $this->view->show("chi-tiet-viec-lam");
    }
    public function candidate_detail($para = array()){
        global $db;
        $candidateId = is_array($para) && isset($para[1]) ? intval($para[1]) : 0;
        if($candidateId <= 0 && isset($_GET['candidate_id'])){ $candidateId = intval($_GET['candidate_id']); }
        if($candidateId <= 0){ header("Location: ".XC_URL."/quan-ly-ung-vien.html"); exit(); }

        $db->query("SELECT ca.*, u.user_email, u.user_phone, u.user_group,
                jc.job_category_name, current_pr.province_name, desired_pr.province_name AS desired_province_name, sal.salary_name
            FROM hicrm_candidates ca
            LEFT JOIN hicrm_users u ON u.id = ca.user_id
            LEFT JOIN hicrm_job_categories jc ON jc.id = ca.major
            LEFT JOIN hicrm_provinces current_pr ON current_pr.id = ca.province_id
            LEFT JOIN hicrm_provinces desired_pr ON desired_pr.id = ca.desired_province_id
            LEFT JOIN hicrm_salary sal ON sal.id = ca.desired_salary
            WHERE ca.id = '".$candidateId."' AND ca.status = 3 AND ca.is_seeking = 1
                AND (u.id IS NULL OR u.user_status = 1) LIMIT 1");
        if($db->num_row() <= 0){ header("Location: ".XC_URL."/quan-ly-ung-vien.html"); exit(); }
        $candidate = $db->fetch_object(true);
        $db->query("SELECT * FROM hicrm_candidate_experiences WHERE candidate_id = '".$candidateId."' ORDER BY start_date DESC, id DESC");
        $experiences = $db->fetch_object();
        $db->query("SELECT * FROM hicrm_candidate_certificates WHERE candidate_id = '".$candidateId."' ORDER BY issued_date DESC, id DESC");
        $certificates = $db->fetch_object();
        $db->query("SELECT ca.id, ca.full_name, ca.avatar_url, ca.desired_position, ca.desired_work_type, pr.province_name, jc.job_category_name
            FROM hicrm_candidates ca
            LEFT JOIN hicrm_provinces pr ON pr.id = ca.desired_province_id
            LEFT JOIN hicrm_job_categories jc ON jc.id = ca.major
            LEFT JOIN hicrm_users u ON u.id = ca.user_id
            WHERE ca.status = 3 AND ca.is_seeking = 1 AND ca.id <> '".$candidateId."'
                AND ca.major = '".intval($candidate->major)."' AND (u.id IS NULL OR u.user_status = 1)
            ORDER BY ca.updated_at DESC, ca.id DESC LIMIT 8");
        $this->view->data['candidate_detail'] = $candidate;
        $this->view->data['candidate_detail_experiences'] = $experiences;
        $this->view->data['candidate_detail_certificates'] = $certificates;
        $this->view->data['related_candidates'] = $db->fetch_object();
        $this->view->show("chi-tiet-ung-vien");
    }
    public function employers(){
        global $db;
        
        $uid = $db->escapestring($_SESSION['user']['id']);
        // var_dump($_SESSION['user']);
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
