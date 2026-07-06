<?php require 'header.php'; ?>
<?php
function employer_dash_h($value){
  return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
function employer_dash_initials($name){
  $name = trim((string)$name);
  if($name == '') return 'NTD';
  $parts = preg_split('/\s+/', $name);
  $first = substr($parts[0], 0, 1);
  $last = count($parts) > 1 ? substr($parts[count($parts) - 1], 0, 1) : '';
  return strtoupper($first.$last);
}
function employer_dash_status_label($status){
  $map = array('draft' => 'Nháp', 'pending' => 'Chờ duyệt', 'published' => 'Đang hoạt động', 'closed' => 'Đã đóng', 'rejected' => 'Từ chối');
  return isset($map[$status]) ? $map[$status] : $status;
}
function employer_dash_work_type_label($type){
  $map = array('full_time' => 'Full-time', 'part_time' => 'Part-time', 'remote' => 'Remote', 'hybrid' => 'Hybrid');
  return isset($map[$type]) ? $map[$type] : 'Chưa cập nhật';
}
function employer_dash_date($date){
  if(!$date || $date == '0000-00-00') return '';
  return date('d/m/Y', strtotime($date));
}
function employer_dash_asset($path){
  $path = trim((string)$path);
  if($path == '') return '';
  if(strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) return $path;
  return XC_URL.'/'.ltrim($path, '/');
}
function employer_dash_excerpt($value, $limit = 180){
  $value = trim(strip_tags((string)$value));
  if($value == '') return '';
  if(function_exists('mb_strlen') && function_exists('mb_substr')){
    return mb_strlen($value, 'UTF-8') > $limit ? mb_substr($value, 0, $limit, 'UTF-8').'...' : $value;
  }
  return strlen($value) > $limit ? substr($value, 0, $limit).'...' : $value;
}
function employer_dash_lower($value){
  $value = (string)$value;
  return function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
}
function employer_dash_is_student_candidate($candidate){
  if(isset($candidate->is_student_candidate)){
    return intval($candidate->is_student_candidate) === 1;
  }
  if(isset($candidate->linked_student_profile_id) && intval($candidate->linked_student_profile_id) > 0){
    return true;
  }
  return isset($candidate->school_name) && trim((string)$candidate->school_name) != '';
}
$employer = isset($employer) && $employer ? $employer : (object)array();
$job_posts = isset($job_posts) && is_array($job_posts) ? $job_posts : array();
$job_categories = isset($job_categories) && is_array($job_categories) ? $job_categories : array();
$job_provinces = isset($job_provinces) && is_array($job_provinces) ? $job_provinces : array();
$job_stats = isset($job_stats) && is_array($job_stats) ? $job_stats : array('total' => 0, 'published' => 0, 'pending' => 0, 'closed' => 0);
$students = isset($students) && is_array($students) ? $students : array();
$candidates = isset($candidates) && is_array($candidates) ? $candidates : array();
$account_user = isset($employer_user) && $employer_user ? $employer_user : (object)array();
$account_status_map = array(0 => 'Tạm khóa', 1 => 'Đang hoạt động', 2 => 'Chờ kích hoạt');
$account_group_map = array('1' => 'Quản trị viên', '2' => 'Nhà tuyển dụng', '3' => 'Nhân sự', '4' => 'Ứng viên');
$account_status = isset($account_user->user_status) ? intval($account_user->user_status) : 1;
$account_group = isset($account_user->user_group) ? (string)$account_user->user_group : '';
$account_status_label = isset($account_status_map[$account_status]) ? $account_status_map[$account_status] : 'Không xác định';
$account_group_label = isset($account_group_map[$account_group]) ? $account_group_map[$account_group] : $account_group;
$account_verified_label = isset($account_user->user_is_verified) && intval($account_user->user_is_verified) == 1 ? 'Đã xác thực' : 'Chưa xác thực';
$company_name = isset($employer->company_name) && $employer->company_name != '' ? $employer->company_name : 'Nhà tuyển dụng';
$company_initials = employer_dash_initials($company_name);
$logo_src = isset($employer->logo_url) ? employer_dash_asset($employer->logo_url) : '';
$cover_src = isset($employer->cover_url) ? employer_dash_asset($employer->cover_url) : '';
$linked_label = isset($employer->is_linked_school) && intval($employer->is_linked_school) == 1 ? 'Đã liên kết CĐ Kon Tum' : 'Chưa liên kết CĐ Kon Tum';
$is_linked_school = isset($employer->is_linked_school) && intval($employer->is_linked_school) == 1;
$active_jobs_count = isset($job_stats['published']) ? intval($job_stats['published']) : 0;
$pending_jobs_count = isset($job_stats['pending']) ? intval($job_stats['pending']) : 0;
$closed_jobs_count = isset($job_stats['closed']) ? intval($job_stats['closed']) : 0;
$recent_job_posts = array_slice($job_posts, 0, 5);
$job_edit_payloads = array();
$job_detail_payloads = array();
$candidate_directory_payload = array();
$candidate_directory_category_ids = array();
$candidate_directory_province_ids = array();
$candidate_directory_salary_ids = array();
foreach($job_posts as $job){
  $job_id = isset($job->id) ? intval($job->id) : 0;
  if($job_id <= 0) continue;
  $deadline_value = isset($job->deadline) && $job->deadline && $job->deadline != '0000-00-00' ? date('Y-m-d', strtotime($job->deadline)) : '';
  $job_edit_payloads[$job_id] = array(
    'job_id' => $job_id,
    'title' => isset($job->title) ? $job->title : '',
    'quantity' => isset($job->quantity) ? intval($job->quantity) : 1,
    'job_category_id' => isset($job->job_category_id) ? (string)intval($job->job_category_id) : '',
    'province_id' => isset($job->province_id) ? (string)intval($job->province_id) : '',
    'job_description' => isset($job->job_description) ? $job->job_description : '',
    'experience_years' => isset($job->experience_years) ? $job->experience_years : '',
    'degree_required' => isset($job->degree_required) ? $job->degree_required : '',
    'professional_skills' => isset($job->professional_skills) ? $job->professional_skills : '',
    'soft_skills' => isset($job->soft_skills) ? $job->soft_skills : '',
    'other_requirements' => isset($job->other_requirements) ? $job->other_requirements : '',
    'benefits_description' => isset($job->benefits_description) ? $job->benefits_description : '',
    'rewards_description' => isset($job->rewards_description) ? $job->rewards_description : '',
    'work_environment' => isset($job->work_environment) ? $job->work_environment : '',
    'work_type' => isset($job->work_type) ? $job->work_type : '',
    'salary_id' => isset($job->salary_id) ? (string)intval($job->salary_id) : '',
    'address_detail' => isset($job->address_detail) ? $job->address_detail : '',
    'working_time' => isset($job->working_time) ? $job->working_time : '',
    'deadline' => $deadline_value,
    'status' => isset($job->status) ? $job->status : 'pending'
  );

  $job_detail_payloads[$job_id] = array(
    'id' => $job_id,
    'title' => isset($job->title) ? $job->title : '',
    'status' => isset($job->status) ? employer_dash_status_label($job->status) : '',
    'job_category_id' => isset($job->job_category_id) ? intval($job->job_category_id) : 0,
    'job_category_name' => isset($job->job_category_name) ? $job->job_category_name : '',
    'salary_name' => isset($job->salary_name) ? $job->salary_name : '',
    'province_name' => isset($job->province_name) ? $job->province_name : '',
    'address_detail' => isset($job->address_detail) ? $job->address_detail : '',
    'work_type' => employer_dash_work_type_label(isset($job->work_type) ? $job->work_type : ''),
    'quantity' => isset($job->quantity) ? intval($job->quantity) : 0,
    'deadline' => employer_dash_date(isset($job->deadline) ? $job->deadline : ''),
    'created_at' => employer_dash_date(isset($job->created_at) ? $job->created_at : ''),
    'views_count' => isset($job->views_count) ? intval($job->views_count) : 0,
    'job_description' => isset($job->job_description) ? $job->job_description : '',
    'professional_skills' => isset($job->professional_skills) ? $job->professional_skills : '',
    'soft_skills' => isset($job->soft_skills) ? $job->soft_skills : '',
    'other_requirements' => isset($job->other_requirements) ? $job->other_requirements : '',
    'benefits_description' => isset($job->benefits_description) ? $job->benefits_description : '',
    'rewards_description' => isset($job->rewards_description) ? $job->rewards_description : '',
    'work_environment' => isset($job->work_environment) ? $job->work_environment : ''
  );
}
$applicant_payload = array();
foreach($candidates as $candidate){
  $full_name = isset($candidate->full_name) ? $candidate->full_name : '';
  $is_student_candidate = employer_dash_is_student_candidate($candidate);
  $applicant_payload[] = array(
    'name' => $full_name,
    'color' => '#0d4e96',
    'initials' => employer_dash_initials($full_name),
    'position' => isset($candidate->desired_position) && $candidate->desired_position != '' ? $candidate->desired_position : 'Ứng viên tự do',
    'submitted' => isset($candidate->updated_at) ? date('d/m/Y H:i', strtotime($candidate->updated_at)) : '',
    'exp' => isset($candidate->degree) && $candidate->degree != '' ? $candidate->degree : 'Chưa cập nhật',
    'status' => 'new',
    'is_student' => $is_student_candidate ? 1 : 0
  );

  $candidate_id = isset($candidate->id) ? intval($candidate->id) : 0;
  $candidate_url = $candidate_id > 0 ? general::getInstance()->permalink($candidate_id, 'candidate_profile') : '#';
  $candidate_avatar = isset($candidate->avatar_url) ? employer_dash_asset($candidate->avatar_url) : '';
  $candidate_category_id = isset($candidate->major) ? intval($candidate->major) : 0;
  $candidate_province_id = isset($candidate->desired_province_id) ? intval($candidate->desired_province_id) : 0;
  $candidate_salary_id = isset($candidate->desired_salary) ? intval($candidate->desired_salary) : 0;
  if($candidate_category_id > 0){ $candidate_directory_category_ids[$candidate_category_id] = true; }
  if($candidate_province_id > 0){ $candidate_directory_province_ids[$candidate_province_id] = true; }
  if($candidate_salary_id > 0){ $candidate_directory_salary_ids[$candidate_salary_id] = true; }
  $candidate_directory_payload[] = array(
    'id' => $candidate_id,
    'full_name' => $full_name,
    'initials' => employer_dash_initials($full_name),
    'avatar_url' => $candidate_avatar,
    'candidate_url' => $candidate_url,
    'desired_position' => isset($candidate->desired_position) && $candidate->desired_position != '' ? $candidate->desired_position : 'Ứng viên tự do',
    'job_category_id' => $candidate_category_id,
    'job_category_name' => isset($candidate->job_category_name) ? $candidate->job_category_name : '',
    'desired_province_id' => $candidate_province_id,
    'desired_province_name' => isset($candidate->desired_province_name) ? $candidate->desired_province_name : '',
    'desired_salary' => $candidate_salary_id,
    'salary_name' => isset($candidate->salary_name) ? $candidate->salary_name : '',
    'school_name' => isset($candidate->school_name) ? $candidate->school_name : '',
    'degree' => isset($candidate->degree) ? $candidate->degree : '',
    'cv_url' => isset($candidate->cv_url) ? employer_dash_asset($candidate->cv_url) : '',
    'updated_at' => isset($candidate->updated_at) ? date('d/m/Y H:i', strtotime($candidate->updated_at)) : '',
    'is_student' => $is_student_candidate ? 1 : 0,
    'student_note' => $is_student_candidate ? 'Sinh viên' : '',
    'student_gpa' => isset($candidate->student_gpa) ? $candidate->student_gpa : '',
    'student_rank' => isset($candidate->student_rank) ? $candidate->student_rank : ''
  );
}
$student_payload = array();
foreach($students as $student){
  $student_name = isset($student->student_name) ? $student->student_name : '';
  $student_payload[] = array(
    'name' => $student_name,
    'dob' => isset($student->student_birthday) ? date('Y', strtotime($student->student_birthday)) : '',
    'major' => isset($student->job_category_name) ? $student->job_category_name : '',
    'color' => '#0d4e96',
    'initials' => employer_dash_initials($student_name),
    'gpa' => isset($student->student_gpa) ? floatval($student->student_gpa) : 0,
    'year' => isset($student->student_class) ? 'Lớp '.$student->student_class : '',
    'rank' => isset($student->student_rank) && $student->student_rank != '' ? $student->student_rank : 'Chưa xếp loại'
  );
}
?>



<!-- ===== DASHBOARD ===== -->
<div class="dash-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-employer">
      <div class="sidebar-avatar"><img style="width: 100%; height: 100%; object-fit: cover;" src="<?php echo employer_dash_h($logo_src); ?>" alt="Avatar"></div>
      <div class="sidebar-name"><?php echo employer_dash_h($company_name); ?></div>
      <div class="sidebar-role" style="margin-top:6px">
        <span class="badge-link"><i class="ti ti-link" style="font-size:10px"></i> <?php echo employer_dash_h($linked_label); ?></span>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section-label">Tổng quan</div>
      <div class="nav-item active" onclick="showPage('dashboard')">
        <i class="ti ti-layout-dashboard"></i> Bảng điều khiển
      </div>
      <div class="nav-section-label">Hồ sơ doanh nghiệp</div>
      <div class="nav-item" onclick="showPage('profile')">
        <i class="ti ti-building"></i> Thông tin công ty
      </div>
      <!-- <div class="nav-item" onclick="showPage('profile');showProfileTab('link')">
        <i class="ti ti-link"></i> Liên kết trường CĐ
      </div> -->
      <div class="nav-section-label">Tuyển dụng</div>
      <div class="nav-item" onclick="showPage('jobs')">
        <i class="ti ti-news"></i> Bài đăng tuyển dụng
        <span class="nav-badge"><?php echo count($job_posts); ?></span>
      </div>
      <!-- <div class="nav-item" onclick="showPage('applicants')">
        <i class="ti ti-user-check"></i> Danh sách ứng viên
        <span class="nav-badge"><?php echo count($candidates); ?></span>
      </div> -->
      <?php if($is_linked_school){ ?>
      <div class="nav-section-label">Sinh viên</div>
      <div class="nav-item" onclick="showPage('students')">
        <i class="ti ti-school"></i> Kho dữ liệu sinh viên
      </div>
      <?php } ?>
    </nav>
    <div class="sidebar-bottom">
      <div class="sidebar-bottom-link" onclick="showPage('account')"><i class="ti ti-settings"></i> Cài đặt tài khoản</div>
      <div class="sidebar-bottom-link"><i class="ti ti-help-circle"></i> Trợ giúp & hỗ trợ</div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="dash-main">

    <!-- ===== PAGE: DASHBOARD ===== -->
    <div class="dash-page active" id="page-dashboard">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-layout-dashboard"></i> Bảng điều khiển</div>
          <div class="page-subtitle">Xin chào! Đây là tổng quan hoạt động của bạn.</div>
        </div>
        <button class="btn-primary" onclick="showPage('jobs');openJobModal()"><i class="ti ti-plus"></i> Đăng tin mới</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="ti ti-news"></i></div>
          <div>
            <div class="stat-value"><?php echo $active_jobs_count; ?></div>
            <div class="stat-label">Bài đăng đang hoạt động</div>
         </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon green"><i class="ti ti-user-check"></i></div>
          <div>
            <div class="stat-value"><?php echo count($job_posts); ?></div>
            <div class="stat-label">Bài đăng đã duyệt</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon orange"><i class="ti ti-eye"></i></div>
          <div>
            <div class="stat-value"><?php echo $pending_jobs_count; ?></div>
            <div class="stat-label">Bài đăng chờ duyệt</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon purple"><i class="ti ti-check"></i></div>
          <div>
            <div class="stat-value"><?php echo $closed_jobs_count; ?></div>
            <div class="stat-label">Bài đăng đã đóng</div>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 340px;gap:18px">
        <div>
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="ti ti-news"></i> Bài đăng gần đây</div>
              <button class="btn-secondary btn-sm" onclick="showPage('jobs')">Xem tất cả</button>
            </div>
              <div class="card-body" style="padding:0">
                <?php if(count($recent_job_posts) > 0){ foreach($recent_job_posts as $index => $job){ ?>
                <div style="padding:14px 20px;<?php echo $index < count($recent_job_posts) - 1 ? 'border-bottom:1px solid #f0f4fa;' : ''; ?>display:flex;align-items:center;gap:12px">
                  <div class="job-post-status <?php echo $job->status == 'published' ? 'active' : 'pending'; ?>"></div>
                  <div style="flex:1;min-width:0">
                    <div style="font-size:14px;font-weight:700;color:#162033;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?php echo employer_dash_h($job->title); ?></div>
                    <div style="font-size:11px;color:#7b8794;margin-top:2px">
                      <?php echo intval($job->quantity); ?> người
                      · <?php echo employer_dash_h(isset($job->salary_name) && $job->salary_name != '' ? $job->salary_name : 'Thỏa thuận'); ?>
                      · Hạn: <?php echo employer_dash_h(employer_dash_date($job->deadline)); ?>
                    </div>
                  </div>
                  <button class="btn-secondary btn-sm" type="button" onclick="openJobDetailModal(<?php echo intval($job->id); ?>)">Xem</button>
                </div>
                <?php }}else{ ?>
                <div style="padding:18px 20px;color:#7b8794;font-size:13px">Chưa có bài đăng tuyển dụng nào.</div>
                <?php } ?>
              </div>
          </div>
        </div>

        <div>
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="ti ti-link"></i> Trạng thái liên kết</div>
            </div>
            <div class="card-body">
              <div style="text-align:center;padding:8px 0 16px">
                <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:30px">🎓</div>
                <div style="font-size:14px;font-weight:700;color:#111;margin-bottom:4px">Trường CĐ Kon Tum</div>
                <div style="display:inline-flex;align-items:center;gap:5px;background:#dcfce7;color:#16a34a;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px">
                  <i class="ti ti-check" style="font-size:13px"></i> <?php echo $linked_label;?>
                </div>
              </div>
              <div style="font-size:12px;color:#666;line-height:1.6;background:#f8faff;border-radius:8px;padding:12px;border:1px solid #e8edf5">
                <strong>Đối tác chiến lược. Ưu tiên hàng đầu</strong><br>
                Liên kết với trường CĐ Kon Tum ngày 10/03/2024<br>
              </div>
              <!-- <button class="btn-secondary btn-sm" style="width:100%;margin-top:12px;justify-content:center" onclick="showPage('profile');showProfileTab('link')">
                <i class="ti ti-edit"></i> Xem chi tiết liên kết
              </button> -->
            </div>
          </div>

          <!-- <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="ti ti-bell"></i> Thông báo</div>
            </div>
            <div class="card-body" style="padding:0">
              <div style="padding:12px 18px;border-bottom:1px solid #f0f4fa;font-size:12px">
                <div style="font-weight:600;color:#111">Nguyổn Thị Lan đã nộp đơn</div>
                <div style="color:#aaa;margin-top:2px">Vị trí: Kế toán tổng hợp · 2 giờ trước</div>
              </div>
              <div style="padding:12px 18px;border-bottom:1px solid #f0f4fa;font-size:12px">
                <div style="font-weight:600;color:#111">Bài đăng CNC sắp hết hạn</div>
                <div style="color:#e65100;margin-top:2px">Còn 5 ngày · Nhớ gia hạn</div>
              </div>
              <div style="padding:12px 18px;font-size:12px">
                <div style="font-weight:600;color:#111">Sinh viên xuất sắc mới</div>
                <div style="color:#aaa;margin-top:2px">15 sinh viên Kế toán có GPA ≥ 3.5</div>
              </div>
            </div>
          </div> -->
        </div>
      </div>
    </div>

    <!-- ===== PAGE: COMPANY PROFILE ===== -->
    <div class="dash-page" id="page-profile">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-building"></i> Hồ sơ công ty</div>
          <div class="page-subtitle">Quản lý thông tin và hình ảnh doanh nghiệp của bạn</div>
        </div>
        <button class="btn-primary" type="submit" form="companyProfileForm"><i class="ti ti-device-floppy"></i> Lưu thay đổi</button>
      </div>

      <!-- Profile Hero Banner -->
      <div class="profile-hero">
        <div class="profile-hero-inner">
          <div class="profile-logo-upload">
            <span><img src="<?php echo employer_dash_h($logo_src); ?>" alt="Logo"></span>
            <div class="profile-logo-overlay"><i class="ti ti-camera"></i></div>
          </div>
          <div class="profile-hero-info">
            <div class="profile-hero-name"><?php echo employer_dash_h($company_name); ?></div>
            <div class="profile-hero-meta">
              <span class="profile-meta-tag"><i class="ti ti-map-pin"></i> Kon Tum</span>
              <span class="profile-meta-tag"><i class="ti ti-users"></i> <?php echo employer_dash_h(isset($employer->company_size) && $employer->company_size != '' ? $employer->company_size : 'Chưa cập nhật'); ?></span>
              <span class="profile-meta-tag"><i class="ti ti-briefcase"></i> <?php echo employer_dash_h(isset($employer->job_category_name) && $employer->job_category_name != '' ? $employer->job_category_name : 'Chưa cập nhật'); ?></span>
            </div>
            <span class="profile-link-status"><i class="ti ti-check-circle"></i> <?php echo employer_dash_h($linked_label); ?></span>
          </div>
        </div>
        <div class="profile-hero-tabs">
          <div class="profile-hero-tab active" onclick="showProfileTab('info')">Thông tin cơ bản</div>
          <div class="profile-hero-tab" onclick="showProfileTab('media')">Hình ảnh & Media</div>
        </div>
      </div>

      <!-- Tab: Info -->
      <div class="tab-panel active" id="profile-tab-info">
        <form id="companyProfileForm">
        <div class="card">
          <div class="card-body">
            <div class="profile-form-section">
              <div class="profile-form-section-title"><i class="ti ti-building" style="color:#0d4e96"></i> Thông tin chính</div>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Tên công ty / Đơn vị <span class="required">*</span></label>
                  <input class="form-control" id="company_name" name="company_name" type="text" value="<?php echo employer_dash_h(isset($employer->company_name) ? $employer->company_name : ''); ?>" placeholder="Tên công ty..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Tên viết tắt / Tên hiển thị</label>
                  <input class="form-control" id="tax_code" name="tax_code" type="text" value="<?php echo employer_dash_h(isset($employer->tax_code) ? $employer->tax_code : ''); ?>" placeholder="Mã số thuế..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Địa chỉ trụ sở chính <span class="required">*</span></label>
                  <input class="form-control" id="company_address_detail" name="address_detail" type="text" value="<?php echo employer_dash_h(isset($employer->address_detail) ? $employer->address_detail : ''); ?>" placeholder="Địa chỉ..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Địa chỉ văn phòng làm việc</label>
                  <input class="form-control" type="text" value="45 Trần Phú, P. Thắng Lợi, TP. Kon Tum" placeholder="Địa chỉ văn phòng..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Lĩnh vực hoạt động (Ngành nghề) <span class="required">*</span></label>
                  <select class="form-control" id="company_job_category_id" name="job_category_id">
                    <option value="">Chọn ngành nghề</option>
                    <?php foreach($job_categories as $category){ ?>
                    <option value="<?php echo intval($category->id); ?>" <?php echo (isset($employer->job_category_id) && intval($employer->job_category_id) == intval($category->id)) ? 'selected' : ''; ?>><?php echo employer_dash_h($category->job_category_name); ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Quy mô nhân sự <span class="required">*</span></label>
                  <select class="form-control" id="company_size" name="company_size">
                    <?php foreach(array('Dưới 10 người','10 - 50 người','100 - 200 người','200 - 500 người','Trên 500 người') as $size){ ?>
                    <option value="<?php echo employer_dash_h($size); ?>" <?php echo (isset($employer->company_size) && $employer->company_size == $size) ? 'selected' : ''; ?>><?php echo employer_dash_h($size); ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="profile-form-section">
              <div class="profile-form-section-title"><i class="ti ti-world" style="color:#0d4e96"></i> Kênh truyền thông & Giới thiệu</div>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Website công ty</label>
                  <input class="form-control" id="website_url" name="website_url" type="url" value="<?php echo employer_dash_h(isset($employer->website_url) ? $employer->website_url : ''); ?>" placeholder="https://..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Facebook Fanpage</label>
                  <input class="form-control" id="fanpage_url" name="fanpage_url" type="url" value="<?php echo employer_dash_h(isset($employer->fanpage_url) ? $employer->fanpage_url : ''); ?>" placeholder="https://facebook.com/..."/>
                </div>
                <div class="form-group full">
                  <label class="form-label">Mô tả ngắn gọn về công ty <span class="required">*</span></label>
                  <textarea class="form-control" id="company_description" name="description" rows="4"><?php echo employer_dash_h(isset($employer->description) ? $employer->description : ''); ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        </form>

        <!-- Pagination demo for profile sections -->
       
      </div>

      <!-- Tab: Link -->
      <div class="tab-panel" id="profile-tab-link">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="ti ti-link"></i> Liên kết với Trường Cao đẳng Kon Tum</div>
          </div>
          <div class="card-body">
            <div class="link-card <?php echo (isset($employer->is_linked_school) && intval($employer->is_linked_school) == 1) ? 'linked' : 'unlinked'; ?>">
              <div class="link-card-icon"><i class="ti ti-link"></i></div>
              <div style="flex:1">
                <div class="link-card-title"><?php echo employer_dash_h($linked_label); ?></div>
                <div class="link-card-desc">
                  <?php echo employer_dash_h(isset($employer->link_summary) && $employer->link_summary != '' ? $employer->link_summary : 'Chưa cập nhật nội dung liên kết.'); ?>
                </div>
                <span class="link-card-badge"><i class="ti ti-check" style="font-size:11px"></i> <?php echo employer_dash_h(isset($employer->verified_status) ? $employer->verified_status : 'pending'); ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab: Media -->
      <div class="tab-panel" id="profile-tab-media">
        <div class="card">
          <div class="card-body">
            <form id="companyImagesForm" enctype="multipart/form-data">
              <div class="profile-form-section-title"><i class="ti ti-photo" style="color:#0d4e96"></i> Logo & Ảnh đại diện</div>
              <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:24px">
                <div>
                  <div style="font-size:12px;font-weight:700;color:#666;margin-bottom:8px">Logo công ty</div>
                  <label style="width:100px;height:100px;border-radius:16px;background:linear-gradient(135deg,#0d4e96,#1e88e5);display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;color:#fff;border:3px dashed #b0c4e8;cursor:pointer;overflow:hidden">
                    <?php if($logo_src != ''){ ?>
                    <img id="logoPreview" src="<?php echo employer_dash_h($logo_src); ?>" alt="Logo" style="width:100%;height:100%;object-fit:cover">
                    <?php }else{ ?>
                    <span id="logoPreviewText"><?php echo employer_dash_h($company_initials); ?></span>
                    <img id="logoPreview" src="" alt="Logo" style="width:100%;height:100%;object-fit:cover;display:none">
                    <?php } ?>
                    <input type="file" name="logo_file" id="logoFileInput" accept="image/*" style="display:none">
                  </label>
                  <div style="font-size:11px;color:#aaa;margin-top:6px;text-align:center">Nhấp để thay đổi<br>PNG/JPG/WEBP, tối đa 2MB</div>
                </div>
                
              </div>
              <button type="submit" class="btn-primary"><i class="ti ti-upload"></i> Lưu hình ảnh</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== PAGE: JOBS ===== -->
    <div class="dash-page" id="page-jobs">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-news"></i> Bài đăng tuyển dụng</div>
          <div class="page-subtitle">Quản lý tất cả tin tuyển dụng của bạn</div>
        </div>
        <button class="btn-primary" onclick="openJobModal()"><i class="ti ti-plus"></i> Đăng tin mới</button>
      </div>

      <div class="filter-bar">
        <input class="form-control search-input" type="text" placeholder="Tìm theo tên vị trí..."/>
        <select class="form-control">
          <option>Tất cả trạng thái</option>
          <option>Đang hoạt động</option>
          <option>Chờ duyệt</option>
          <option>Đã đóng</option>
        </select>
        <select class="form-control">
          <option>Sắp xếp: Mới nhất</option>
          <option>Cũ nhất</option>
          <!-- <option>Nhiều ứng viên nhất</option> -->
        </select>
      </div>

      <div class="job-posts-list" id="employerJobPostsList">
        <?php if(count($job_posts) > 0){ foreach($job_posts as $job){ ?>
        <div class="job-post-card dynamic-job-card">
          <div class="job-post-status <?php echo $job->status == 'published' ? 'active' : 'pending'; ?>" style="margin-top:8px"></div>
          <div class="job-post-info">
            <div class="job-post-title"><?php echo employer_dash_h($job->title); ?></div>
            <div class="job-post-meta">
              <span class="jpm-tag qty"><i class="ti ti-users"></i> <?php echo intval($job->quantity); ?> người</span>
              <span class="jpm-tag loc"><i class="ti ti-map-pin"></i> <?php echo employer_dash_h(isset($job->address_detail) && $job->address_detail != '' ? $job->address_detail : 'Chưa cập nhật'); ?></span>
              <span class="jpm-tag type"><i class="ti ti-clock"></i> <?php echo employer_dash_h(employer_dash_work_type_label($job->work_type)); ?></span>
              <span class="jpm-tag deadline"><i class="ti ti-calendar"></i> Hạn: <?php echo employer_dash_h(employer_dash_date($job->deadline)); ?></span>
            </div>
            <div class="job-post-footer">
              <span><i class="ti ti-calendar" style="font-size:11px"></i> Đăng ngày <?php echo employer_dash_h(employer_dash_date($job->created_at)); ?></span>
              <!-- <span>·</span> -->
              <!-- <span style="color:#2e7d32;font-weight:600"><i class="ti ti-eye" style="font-size:11px"></i> <?php echo intval($job->views_count); ?> lượt xem</span> -->
              <span>·</span>
              <span style="color:#0d4e96;font-weight:600">— <?php echo employer_dash_h(employer_dash_status_label($job->status)); ?></span>
            </div>
          </div>
          <div class="job-post-actions">
            <div class="action-btn" title="Xem chi tiết" onclick="openJobDetailModal(<?php echo intval($job->id); ?>)"><i class="ti ti-eye"></i></div>
            <!-- <div class="action-btn" title="Xem ứng viên" onclick="showPage('applicants')"><i class="ti ti-user-check"></i></div> -->
            <div class="action-btn" title="Chỉnh sửa" onclick="openJobModal(<?php echo intval($job->id); ?>)"><i class="ti ti-edit"></i></div>
            <div class="action-btn danger" title="Xóa" onclick="deleteEmployerJob(<?php echo intval($job->id); ?>)"><i class="ti ti-trash"></i></div>
          </div>
        </div>
        <?php }}else{ ?>
        <div class="job-post-card dynamic-empty-job-card">
          <div class="job-post-info">
            <div class="job-post-title">Chưa có bài đăng tuyển dụng</div>
            <div class="job-post-footer">Bấm "Đăng tin mới" để tạo tin đầu tiên.</div>
          </div>
        </div>
        <?php } ?>
      </div>
      
      <div class="pagination" id="employerJobsPagination" style="margin-top:24px;display:none"></div>
    </div>

    <!-- ===== PAGE: APPLICANTS ===== -->
    <div class="dash-page" id="page-applicants">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-user-check"></i> Danh sách ứng viên</div>
          <div class="page-subtitle">Tổng hợp tất cả hồ sơ đã nộp vào các vị trí của bạn</div>
        </div>
        <button class="btn-secondary"><i class="ti ti-download"></i> Xuất Excel</button>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="filter-bar">
            <input class="form-control search-input" type="text" placeholder="Tìm theo tên ứng viên..."/>
            <select class="form-control">
              <option>Tất cả vị trí</option>
              <option>Kế toán tổng hợp</option>
              <option>Kỹ thuật viên CNC</option>
              <option>Marketing Online</option>
            </select>
            <select class="form-control">
              <option>Thời gian nộp: Mới nhất</option>
              <option>Cũ nhất</option>
            </select>
            <select class="form-control">
              <option>Tất cả trạng thái</option>
              <option>Mới nộp</option>
              <option>Đang xem xét</option>
              <option>Đã tuyển</option>
              <option>Từ chối</option>
            </select>
          </div>

          <div style="overflow-x:auto">
            <table class="applicant-table">
              <thead>
                <tr>
                  <th>Ứng viên</th>
                  <th>Vị trí ứng tuyển</th>
                  <th>Thời gian nộp</th>
                  <th>Kinh nghiệm</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody id="applicantsBody">
                <?php if(count($candidates) > 0){ foreach($candidates as $candidate){
                  $candidate_name = isset($candidate->full_name) ? $candidate->full_name : '';
                  $candidate_position = isset($candidate->desired_position) && $candidate->desired_position != '' ? $candidate->desired_position : 'Ứng viên tự do';
                  $candidate_degree = isset($candidate->degree) && $candidate->degree != '' ? $candidate->degree : 'Chưa cập nhật';
                  $candidate_time = isset($candidate->updated_at) && $candidate->updated_at != '' ? date('d/m/Y H:i', strtotime($candidate->updated_at)) : '';
                ?>
                <tr>
                  <td>
                    <div class="applicant-name-cell">
                      <div class="applicant-avatar" style="background:#0d4e96"><?php echo employer_dash_h(employer_dash_initials($candidate_name)); ?></div>
                      <div>
                        <div class="applicant-name"><?php echo employer_dash_h($candidate_name); ?></div>
                        <div class="applicant-sub"><?php echo employer_dash_h(isset($candidate->user_email) ? $candidate->user_email : ''); ?></div>
                      </div>
                    </div>
                  </td>
                  <td style="font-weight:600;color:#444"><?php echo employer_dash_h($candidate_position); ?></td>
                  <td style="color:#888;white-space:nowrap"><?php echo employer_dash_h($candidate_time); ?></td>
                  <td style="color:#666"><?php echo employer_dash_h($candidate_degree); ?></td>
                  <td><span class="status-pill new">Mới nộp</span></td>
                  <td>
                    <div style="display:flex;gap:4px">
                      <div class="action-btn" title="Xem hồ sơ"><i class="ti ti-eye"></i></div>
                      <div class="action-btn" title="Liên hệ"><i class="ti ti-mail"></i></div>
                    </div>
                  </td>
                </tr>
                <?php }}else{ ?>
                <tr><td colspan="6" style="text-align:center;color:#777;padding:18px">Chưa có ứng viên.</td></tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

         
        </div>
      </div>
    </div>

    <!-- ===== PAGE: STUDENTS ===== -->
    <div class="dash-page" id="page-students">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-school"></i> Kho dữ liệu sinh viên</div>
          <div class="page-subtitle">Hiển thị toàn bộ ứng viên, ưu tiên sinh viên lên đầu và hỗ trợ lọc nhanh theo nhu cầu tuyển dụng.</div>
        </div>
      </div>

      <?php if($is_linked_school){ ?>
      <div class="student-filter-bar student-directory-filter-bar">
        <input class="form-control" id="studentKeywordFilter" type="text" placeholder="Lọc theo họ và tên..." style="flex:1;min-width:220px"/>
        <select class="form-control" id="studentTypeFilter" style="min-width:170px">
          <option value="">Tất cả đối tượng</option>
          <option value="1">Sinh viên</option>
          <option value="0">Ứng viên tự do</option>
        </select>
        <select class="form-control" id="studentCategoryFilter" style="min-width:170px">
          <option value="">Tất cả ngành nghề</option>
          <?php foreach($job_categories as $category){ if(!isset($candidate_directory_category_ids[intval($category->id)])) continue; ?>
          <option value="<?php echo intval($category->id); ?>"><?php echo employer_dash_h($category->job_category_name); ?></option>
          <?php } ?>
        </select>
        <select class="form-control" id="studentProvinceFilter" style="min-width:170px">
          <option value="">Tất cả địa điểm</option>
          <?php foreach($job_provinces as $province){ if(!isset($candidate_directory_province_ids[intval($province->id)])) continue; ?>
          <option value="<?php echo intval($province->id); ?>"><?php echo employer_dash_h($province->province_name); ?></option>
          <?php } ?>
        </select>
        <select class="form-control" id="studentSalaryFilter" style="min-width:180px">
          <option value="">Tất cả mức lương</option>
          <?php foreach($salary as $salary_item){ if(!isset($candidate_directory_salary_ids[intval($salary_item->id)])) continue; ?>
          <option value="<?php echo intval($salary_item->id); ?>"><?php echo employer_dash_h($salary_item->salary_name); ?></option>
          <?php } ?>
        </select>
        <button class="btn-secondary" type="button" id="studentFilterReset"><i class="ti ti-refresh"></i> Xóa lọc</button>
      </div>

      <div class="student-directory-summary">
        <div style="font-size:13px;color:#5b6777">Đang hiển thị <strong id="studentVisibleCount" style="color:#111"><?php echo count($candidate_directory_payload); ?></strong> / <?php echo count($candidate_directory_payload); ?> ứng viên <span id="studentPageSummary" style="margin-left:8px;color:#7b8794"></span></div>
        <div class="student-directory-legend">
          <span class="candidate-note student"><i class="ti ti-school"></i> Sinh viên</span>
          <span class="candidate-note"><i class="ti ti-briefcase"></i> Ứng viên tự do</span>
        </div>
      </div>

      <div class="student-grid student-directory-grid" id="studentGrid">
        <?php if(count($candidate_directory_payload) > 0){ foreach($candidate_directory_payload as $candidate_item){ ?>
        <a
          class="student-card student-directory-card"
          href="<?php echo employer_dash_h($candidate_item['candidate_url']); ?>"
          data-name="<?php echo employer_dash_h(employer_dash_lower($candidate_item['full_name'])); ?>"
          data-category="<?php echo intval($candidate_item['job_category_id']); ?>"
          data-province="<?php echo intval($candidate_item['desired_province_id']); ?>"
          data-salary="<?php echo intval($candidate_item['desired_salary']); ?>"
          data-student="<?php echo intval($candidate_item['is_student']); ?>"
        >
          <div class="student-card-avatar" style="background:#0d4e96">
            <?php if($candidate_item['avatar_url'] != ''){ ?>
            <img src="<?php echo employer_dash_h($candidate_item['avatar_url']); ?>" alt="<?php echo employer_dash_h($candidate_item['full_name']); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
            <?php }else{ ?>
            <?php echo employer_dash_h($candidate_item['initials']); ?>
            <?php } ?>
          </div>
          <div class="student-card-name"><?php echo employer_dash_h($candidate_item['full_name']); ?></div>
          <div class="student-card-meta" style="font-size:12px">
            <strong><i class="ti ti-briefcase"></i></strong> <?php echo employer_dash_h($candidate_item['job_category_name'] != '' ? $candidate_item['job_category_name'] : $candidate_item['desired_position']); ?>
          </div>
          <div class="student-card-meta" style="font-size:12px">
            <strong><i class="ti ti-map-pin"></i></strong> <?php echo employer_dash_h($candidate_item['desired_province_name'] != '' ? $candidate_item['desired_province_name'] : 'Chưa cập nhật'); ?>
          </div>
          <span class="btn-primary btn-sm" style="width:100%;justify-content:center;font-size:11px;padding:6px">
            <i class="ti ti-eye" style="font-size:12px"></i> Xem hồ sơ
          </span>
        </a>
        <?php }}else{ ?>
        <div class="student-card"><div class="student-card-name">Chưa có ứng viên để hiển thị</div></div>
        <?php } ?>
      </div>
      <div class="empty-state" id="studentEmptyState" style="display:none">
        <i class="ti ti-user-search"></i>
        <h3>Không có ứng viên phù hợp</h3>
        <p>Hãy điều chỉnh lại bộ lọc họ tên, ngành nghề, địa điểm hoặc mức lương mong đợi.</p>
      </div>
      <div class="student-directory-pagination" id="studentPagination" style="display:none"></div>
      <?php }else{ ?>
      <div class="card">
        <div class="card-body" style="padding:30px">
          <div class="empty-state" style="border:none;background:transparent;padding:0">
            <i class="ti ti-lock-access"></i>
            <h3>Chức năng chỉ dành cho doanh nghiệp đã liên kết trường</h3>
            <p>Khi `is_linked_school = 1`, doanh nghiệp mới có thể truy cập kho dữ liệu sinh viên và danh sách ứng viên ưu tiên.</p>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>

    <!-- ===== PAGE: ACCOUNT ===== -->
    <div class="dash-page" id="page-account">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-user-circle"></i> Quản lý tài khoản</div>
          <div class="page-subtitle">Cập nhật thông tin đăng nhập và liên hệ theo dữ liệu hicrm_users</div>
        </div>
        <button class="btn-primary" type="submit" form="accountForm"><i class="ti ti-device-floppy"></i> Lưu tài khoản</button>
      </div>

      <form id="accountForm">
        <div class="card">
          <div class="card-body">
            <div class="profile-form-section">
              <h3 class="section-title"><i class="ti ti-id"></i> Thông tin tài khoản</h3>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Mã tài khoản</label>
                  <input class="form-control" type="text" value="<?php echo employer_dash_h(isset($account_user->id) ? $account_user->id : ''); ?>" readonly/>
                </div>
                <div class="form-group">
                  <label class="form-label">Tên đăng nhập</label>
                  <input class="form-control" id="account_username" name="user_username" type="text" value="<?php echo employer_dash_h(isset($account_user->user_username) ? $account_user->user_username : ''); ?>" placeholder="Tên đăng nhập"/>
                </div>
                <div class="form-group">
                  <label class="form-label">Họ và tên <span class="required">*</span></label>
                  <input class="form-control" id="account_full_name" name="full_name" type="text" value="<?php echo employer_dash_h(isset($account_user->full_name) ? $account_user->full_name : ''); ?>" placeholder="Nhập họ và tên"/>
                </div>
                <div class="form-group">
                  <label class="form-label">Email <span class="required">*</span></label>
                  <input class="form-control" id="account_email" name="user_email" type="email" value="<?php echo employer_dash_h(isset($account_user->user_email) ? $account_user->user_email : ''); ?>" placeholder="email@domain.com"/>
                </div>
                <div class="form-group">
                  <label class="form-label">Số điện thoại</label>
                  <input class="form-control" id="account_phone" name="user_phone" type="text" value="<?php echo employer_dash_h(isset($account_user->user_phone) ? $account_user->user_phone : ''); ?>" placeholder="Nhập số điện thoại"/>
                </div>
                <div class="form-group">
                  <label class="form-label">Nhóm tài khoản</label>
                  <input class="form-control" type="text" value="<?php echo employer_dash_h($account_group_label); ?>" readonly/>
                </div>
                <div class="form-group">
                  <label class="form-label">Trạng thái</label>
                  <input class="form-control" type="text" value="<?php echo employer_dash_h($account_status_label); ?>" readonly/>
                </div>
                <div class="form-group">
                  <label class="form-label">Xác thực</label>
                  <input class="form-control" type="text" value="<?php echo employer_dash_h($account_verified_label); ?>" readonly/>
                </div>
                <div class="form-group">
                  <label class="form-label">Ngày tạo</label>
                  <input class="form-control" type="text" value="<?php echo employer_dash_h(isset($account_user->user_created_at) ? $account_user->user_created_at : ''); ?>" readonly/>
                </div>
                <div class="form-group">
                  <label class="form-label">Đăng nhập gần nhất</label>
                  <input class="form-control" type="text" value="<?php echo employer_dash_h(isset($account_user->user_last_login_at) ? $account_user->user_last_login_at : ''); ?>" readonly/>
                </div>
                <div class="form-group full">
                  <label class="form-label" style="display:flex;align-items:center;gap:8px;font-weight:500">
                    <input id="account_is_subscribed" name="user_is_subscribed" type="checkbox" value="1" <?php echo isset($account_user->user_is_subscribed) && intval($account_user->user_is_subscribed) == 1 ? 'checked' : ''; ?>/>
                    Nhận thông báo và bản tin qua email
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

  </main>
</div>

<!-- ===== MOBILE SIDEBAR TOGGLE ===== -->
<button class="sidebar-toggle" id="sidebarToggle"><i class="ti ti-menu-2"></i></button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
  .job-field-error {
    color: #dc2626;
    font-size: 12px;
    margin-top: 6px;
    min-height: 16px;
  }
  #jobModal .form-group.invalid .form-control {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, .08);
  }
  #jobModal .form-group.invalid .job-category-trigger {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, .08);
  }
  #jobModal .tab-btn.has-error {
    color: #dc2626;
    border-color: #fecaca;
    background: #fff1f2;
  }
  .job-modal-footer-left,
  .job-modal-footer-right {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  #jobModal .modal {
    max-height: min(90vh, 860px);
  }
  #jobModal #jobPostForm {
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    min-height: 0;
    overflow: hidden;
  }
  #jobModal {
    overflow-y: auto;
    align-items: flex-start;
    overscroll-behavior: contain;
  }
  #jobModal .modal-header {
    position: sticky;
    top: 0;
    z-index: 3;
    background: #fff;
  }
  #jobModal .modal-body {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
    overscroll-behavior: contain;
    padding-bottom: 28px;
  }
  #jobModal .modal-footer {
    flex-shrink: 0;
    background: #fff;
    box-shadow: 0 -10px 24px rgba(15, 23, 42, .06);
    justify-content: space-between;
  }
  @media (max-width: 768px) {
    #jobModal {
      padding-top: 10px;
      padding-bottom: 10px;
    }
    #jobModal .modal-body {
      padding-bottom: 20px;
    }
  }
  .job-category-native,
  .job-province-native {
    display: none;
  }
  .job-category-combobox,
  .job-province-combobox {
    position: relative;
  }
  .job-category-trigger,
  .job-province-trigger {
    width: 100%;
    min-height: 42px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    text-align: left;
    cursor: pointer;
    background: #fff;
  }
  .job-category-trigger span,
  .job-province-trigger span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .job-category-trigger.placeholder span,
  .job-province-trigger.placeholder span {
    color: #9ca3af;
    font-weight: 400;
  }
  .job-category-dropdown,
  .job-province-dropdown {
    display: none;
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 6px);
    z-index: 30;
    padding: 8px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 16px 36px rgba(15, 23, 42, .16);
  }
  .job-category-combobox.open .job-category-dropdown,
  .job-province-combobox.open .job-province-dropdown {
    display: block;
  }
  .job-category-search-wrap,
  .job-province-search-wrap {
    position: relative;
    margin-bottom: 8px;
  }
  .job-category-search-wrap i,
  .job-province-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 16px;
  }
  .job-category-search-input,
  .job-province-search-input {
    width: 100%;
    height: 38px;
    padding: 0 12px 0 36px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    background: #f9fafb;
    outline: none;
    font-size: 13px;
  }
  .job-category-options,
  .job-province-options {
    max-height: 230px;
    overflow-y: auto;
    padding-right: 4px;
  }
  .job-category-option,
  .job-province-option {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 9px;
    min-height: 38px;
    padding: 8px 10px;
    border: 0;
    border-radius: 7px;
    background: transparent;
    color: #374151;
    text-align: left;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
  }
  .job-category-option i,
  .job-province-option i {
    color: #4f7fb8;
    font-size: 16px;
    flex: 0 0 auto;
  }
  .job-category-option:hover,
  .job-category-option.selected,
  .job-province-option:hover,
  .job-province-option.selected {
    background: #e7f0ff;
    color: #0d4e96;
  }
  .job-category-empty,
  .job-province-empty {
    display: none;
    padding: 10px;
    color: #6b7280;
    font-size: 13px;
    text-align: center;
  }
  .student-directory-filter-bar {
    align-items: center;
    flex-wrap: wrap;
  }
  .student-directory-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
    flex-wrap: wrap;
  }
  .student-directory-legend {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
  }
  .candidate-note {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 999px;
    border: 1px solid #dbe4ef;
    background: #fff;
    color: #556274;
    font-size: 12px;
    font-weight: 700;
  }
  .candidate-note.student {
    border-color: #cce4ff;
    background: #eef6ff;
    color: #0d4e96;
  }
  .student-directory-card {
    color: inherit;
    text-decoration: none;
    border: 1px solid #e5edf7;
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .student-directory-grid {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 14px;
  }
  .student-directory-card:hover {
    transform: translateY(-3px);
    border-color: #bdd7f4;
    box-shadow: 0 12px 28px rgba(13, 78, 150, .08);
  }
  .student-directory-card[style*="display: none"] {
    display: none !important;
  }
  .job-detail-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.3fr) minmax(280px, .7fr);
    gap: 18px;
  }
  .job-detail-block {
    padding: 16px;
    border: 1px solid #edf2f7;
    border-radius: 12px;
    background: #f8fbff;
  }
  .job-detail-block h4 {
    margin: 0 0 10px;
    font-size: 14px;
    color: #162033;
  }
  .job-detail-block p {
    margin: 0;
    color: #546274;
    line-height: 1.7;
    white-space: pre-line;
    font-size: 13px;
  }
  .job-detail-facts {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
  }
  .job-detail-fact {
    padding: 12px;
    border-radius: 10px;
    background: #fff;
    border: 1px solid #e7eef6;
  }
  .job-detail-fact span {
    display: block;
    font-size: 11px;
    color: #7a8797;
    margin-bottom: 3px;
  }
  .job-detail-fact strong {
    color: #17243a;
    font-size: 13px;
  }
  .job-detail-slider-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 12px;
  }
  .job-detail-slider-head p {
    margin: 4px 0 0;
    color: #718095;
    font-size: 12px;
  }
  .job-detail-slider-controls {
    display: flex;
    gap: 8px;
  }
  .job-detail-slider-btn {
    width: 36px;
    height: 36px;
    border-radius: 999px;
    border: 1px solid #d5e2f1;
    background: #fff;
    color: #0d4e96;
    cursor: pointer;
  }
  .job-detail-slider-btn:disabled {
    opacity: .45;
    cursor: not-allowed;
  }
  .job-detail-slider-wrap {
    overflow: hidden;
  }
  .job-detail-slider-track {
    display: flex;
    gap: 14px;
    transition: transform .28s ease;
  }
  .job-detail-candidate-card {
    min-width: calc((100% - 28px) / 3);
    max-width: calc((100% - 28px) / 3);
    border: 1px solid #e5edf7;
    border-radius: 16px;
    background: #fff;
    text-decoration: none;
    color: inherit;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .job-detail-candidate-top {
    display: flex;
    gap: 10px;
    align-items: center;
  }
  .job-detail-candidate-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #0d4e96;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    overflow: hidden;
    flex: 0 0 auto;
  }
  .job-detail-candidate-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .job-detail-candidate-name {
    font-size: 14px;
    font-weight: 700;
    color: #152136;
    margin-bottom: 3px;
  }
  .job-detail-candidate-role,
  .job-detail-candidate-meta {
    font-size: 12px;
    color: #6a7686;
    line-height: 1.5;
  }
  .job-detail-candidate-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
  }
  .job-detail-candidate-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    background: #f4f8fc;
    color: #566375;
  }
  .job-detail-candidate-badge.student {
    background: #eef6ff;
    color: #0d4e96;
  }
  .job-detail-empty {
    padding: 18px;
    border: 1px dashed #d7e4f3;
    border-radius: 14px;
    background: #f9fbfd;
    color: #6f7d8d;
    font-size: 13px;
    text-align: center;
  }
  .student-directory-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 18px;
  }
  .student-page-btn {
    min-width: 38px;
    height: 38px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 1px solid #dbe4ef;
    background: #fff;
    color: #425466;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
  }
  .student-page-btn.active {
    background: #0d4e96;
    border-color: #0d4e96;
    color: #fff;
  }
  .student-page-btn:disabled {
    opacity: .45;
    cursor: not-allowed;
  }
  .student-card-meta strong {
    color: #17243a;
  }
  @media(max-width:1024px){
    .student-directory-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }
    .job-detail-layout {
      grid-template-columns: 1fr;
    }
    .job-detail-candidate-card {
      min-width: calc((100% - 14px) / 2);
      max-width: calc((100% - 14px) / 2);
    }
  }
  @media(max-width:640px){
    .student-directory-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .job-detail-facts {
      grid-template-columns: 1fr;
    }
    .job-detail-candidate-card {
      min-width: 100%;
      max-width: 100%;
    }
  }
  @media(max-width:480px){
    .student-directory-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<!-- ===== JOB MODAL ===== -->
<div class="modal-overlay" id="jobModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title" id="jobModalTitle"><i class="ti ti-news" style="color:#0d4e96;margin-right:6px"></i> Tạo bài đăng tuyển dụng</div>
      <button class="modal-close" onclick="closeJobModal()"><i class="ti ti-x"></i></button>
    </div>
    <form id="jobPostForm" novalidate>
    <input type="hidden" id="job_id" name="job_id" value="">
    <input type="hidden" id="job_status" name="status" value="pending">
    <div class="modal-body">
      <!-- Tab Nav trong modal -->
      <div class="tab-nav" style="margin-bottom:18px">
        <button class="tab-btn active" type="button" data-job-tab="basic" onclick="switchModalTab('basic',this,true)">Thông tin cơ bản</button>
        <button class="tab-btn" type="button" data-job-tab="require" onclick="switchModalTab('require',this,true)">Yêu cầu ứng viên</button>
        <button class="tab-btn" type="button" data-job-tab="benefit" onclick="switchModalTab('benefit',this,true)">Phúc lợi & Quyền lợi</button>
        <button class="tab-btn" type="button" data-job-tab="time" onclick="switchModalTab('time',this,true)">Thời gian & Địa điểm</button>
      </div>

      <div class="tab-panel active" id="modal-tab-basic">
        <div class="form-grid">
          <div class="form-group full">
            <label class="form-label">Tên vị trí cần tuyển <span class="required">*</span></label>
            <input class="form-control" id="job_title" name="title" type="text" placeholder="VD: Nhân viên Kế toán tổng hợp..."/>
          </div>
          <div class="form-group">
            <label class="form-label">Số lượng tuyển <span class="required">*</span></label>
            <input class="form-control" id="job_quantity" name="quantity" type="number" placeholder="VD: 3" min="1" value="1"/>
          </div>
          <div class="form-group">
            <label class="form-label">Ngành nghề <span class="required">*</span></label>
            <div class="job-category-combobox" id="jobCategoryCombo">
              <button class="form-control job-category-trigger placeholder" type="button" id="jobCategoryTrigger" aria-haspopup="listbox" aria-expanded="false">
                <span id="jobCategorySelectedText">Chọn ngành nghề</span>
                <i class="ti ti-chevron-down"></i>
              </button>
              <div class="job-category-dropdown" id="jobCategoryDropdown">
                <div class="job-category-search-wrap">
                  <i class="ti ti-search"></i>
                  <input class="job-category-search-input" id="job_category_search" type="text" placeholder="Tìm ngành nghề..."/>
                </div>
                <div class="job-category-options" role="listbox">
                  <?php foreach($job_categories as $category){ ?>
                  <button class="job-category-option" type="button" data-value="<?php echo intval($category->id); ?>" role="option">
                    <i class="ti ti-map-pin"></i>
                    <span><?php echo employer_dash_h($category->job_category_name); ?></span>
                  </button>
                  <?php } ?>
                  <div class="job-category-empty" id="jobCategoryEmpty">Không tìm thấy ngành nghề phù hợp.</div>
                </div>
              </div>
            </div>
            <select class="form-control job-category-native" id="job_category_id" name="job_category_id" tabindex="-1" aria-hidden="true">
              <option value="">Chọn ngành nghề</option>
              <?php foreach($job_categories as $category){ ?>
              <option value="<?php echo intval($category->id); ?>"><?php echo employer_dash_h($category->job_category_name); ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group full">
            <label class="form-label">Mô tả công việc <span class="required">*</span></label>
            <textarea class="form-control" id="job_description" name="job_description" rows="5" placeholder="Liệt kê các đầu việc cụ thể ứng viên sẽ đảm nhận...&#10;- Xử lý chứng từ kế toán, hóa đơn...&#10;- Lập báo cáo tài chính tháng/quý/năm..."></textarea>
          </div>
        </div>
      </div>

      <div class="tab-panel" id="modal-tab-require">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Kinh nghiệm (số năm)</label>
            <input class="form-control" id="experience_years" name="experience_years" type="text" placeholder="VD: 1 - 3 năm kinh nghiệm"/>
          </div>
          <div class="form-group">
            <label class="form-label">Bằng cấp yêu cầu</label>
            <input class="form-control" id="degree_required" name="degree_required" type="text" placeholder="VD: Cao đẳng trở lên"/>

          </div>
          <div class="form-group full">
            <label class="form-label">Kỹ năng chuyên môn</label>
            <textarea class="form-control" id="professional_skills" name="professional_skills" rows="3" placeholder="VD: Thành thạo Excel, MISA, phần mềm kế toán..."></textarea>
          </div>
          <div class="form-group full">
            <label class="form-label">Kỹ năng mềm</label>
            <textarea class="form-control" id="soft_skills" name="soft_skills" rows="3" placeholder="VD: Giao tiếp tốt, làm việc nhóm, chịu được áp lực..."></textarea>
          </div>
          <div class="form-group full">
            <label class="form-label">Yêu cầu khác</label>
            <textarea class="form-control" id="other_requirements" name="other_requirements" rows="2" placeholder="Giới tính, độ tuổi, hình thức... (nếu có)"></textarea>
          </div>
        </div>
      </div>

      <div class="tab-panel" id="modal-tab-benefit">
        <div class="form-grid">
          <div class="form-group full">
            <label class="form-label">Mức lương</label>
            <select class="form-control" id="salaryType" onchange="toggleSalaryRange(this.value)">
              <?php foreach($salary as $type){ ?>
              <option value="<?php echo employer_dash_h($type->id); ?>"><?php  echo employer_dash_h($type->salary_name); ?></option>
              <?php } ?>
              
            </select>
          </div>
          
          <div class="form-group full">
            <label class="form-label">Bảo hiểm xã hội & Phúc lợi</label>
            <textarea class="form-control" id="benefits_description" name="benefits_description" rows="2" placeholder="VD: Đóng BHXH, BHYT đầy đủ theo luật..."></textarea>
          </div>
          <div class="form-group full">
            <label class="form-label">Thưởng & Đãi ngộ</label>
            <textarea class="form-control" id="rewards_description" name="rewards_description" rows="2" placeholder="VD: Thưởng KPI, thưởng lễ tết, du lịch hằng năm..."></textarea>
          </div>
          <div class="form-group full">
            <label class="form-label">Môi trường làm việc</label>
            <textarea class="form-control" id="work_environment" name="work_environment" rows="2" placeholder="VD: Vển phòng hiện đại, team trẻ, ít áp lực..."></textarea>
          </div>
        </div>
      </div>

      <div class="tab-panel" id="modal-tab-time">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Hình thức làm việc</label>
              <input class="form-control" id="work_type" name="work_type" type="text" placeholder="VD: Trực tiếp trên cơ quan"/>

          </div>
          
          <div class="form-group">
            <label class="form-label">Tỉnh / Thành phố</label>
            <div class="job-province-combobox" id="jobProvinceCombo">
              <button class="form-control job-province-trigger placeholder" type="button" id="jobProvinceTrigger" aria-haspopup="listbox" aria-expanded="false">
                <span id="jobProvinceSelectedText">Chọn tỉnh / thành phố</span>
                <i class="ti ti-chevron-down"></i>
              </button>
              <div class="job-province-dropdown" id="jobProvinceDropdown">
                <div class="job-province-search-wrap">
                  <i class="ti ti-search"></i>
                  <input class="job-province-search-input" id="province_search" type="text" placeholder="Tìm tỉnh / thành phố..."/>
                </div>
                <div class="job-province-options" role="listbox">
                  <?php foreach($job_provinces as $province){ ?>
                  <button class="job-province-option" type="button" data-value="<?php echo intval($province->id); ?>" role="option">
                    <i class="ti ti-map-pin"></i>
                    <span><?php echo employer_dash_h($province->province_name); ?></span>
                  </button>
                  <?php } ?>
                  <div class="job-province-empty" id="jobProvinceEmpty">Không tìm thấy tỉnh / thành phố phù hợp.</div>
                </div>
              </div>
            </div>
            <select class="form-control job-province-native" id="province_id" name="province_id" tabindex="-1" aria-hidden="true">
              <option value="">Chọn tỉnh / thành phố</option>
              <?php foreach($job_provinces as $province){ ?>
              <option value="<?php echo intval($province->id); ?>"><?php echo employer_dash_h($province->province_name); ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Địa chỉ làm việc cụ thể</label>
            <input class="form-control" id="job_address_detail" name="address_detail" type="text" value="<?php echo employer_dash_h(isset($employer->address_detail) ? $employer->address_detail : ''); ?>"/>
          </div>
          <div class="form-group">
            <label class="form-label">Thời gian làm việc</label>
            <input class="form-control" id="working_time" name="working_time" type="text" placeholder="VD: Thứ 2 - Thứ 6, 8:00 - 17:00"/>
          </div>
          <div class="form-group">
            <label class="form-label">Hạn chót nộp hồ sơ <span class="required">*</span></label>
            <input class="form-control" id="deadline" name="deadline" type="date"/>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <div class="job-modal-footer-left">
        <button class="btn-secondary" type="button" onclick="closeJobModal()">Hủy</button>
        <button class="btn-secondary" type="button" id="jobPrevBtn" onclick="goJobModalPrev()" style="display:none"><i class="ti ti-chevron-left"></i> Quay lại</button>
      </div>
      <div class="job-modal-footer-right">
        <button class="btn-primary" type="button" id="jobNextBtn" onclick="goJobModalNext()">Tiếp theo <i class="ti ti-chevron-right"></i></button>
        <button class="btn-primary" type="submit" id="jobSubmitBtn" style="display:none"><i class="ti ti-send"></i> Đăng tin ngay</button>
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal-overlay" id="jobDetailModal">
  <div class="modal" style="max-width:1100px">
    <div class="modal-header">
      <div class="modal-title" id="jobDetailModalTitle"><i class="ti ti-eye" style="color:#0d4e96;margin-right:6px"></i> Chi tiết bài đăng</div>
      <button class="modal-close" type="button" onclick="closeJobDetailModal()"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <div class="job-detail-layout">
        <div>
          <div class="job-detail-block">
            <div class="job-detail-facts" id="jobDetailFacts"></div>
          </div>
          <div class="job-detail-block" style="margin-top:14px">
            <h4>Mô tả công việc</h4>
            <p id="jobDetailDescription">Đang cập nhật.</p>
          </div>
          <div class="job-detail-block" style="margin-top:14px">
            <h4>Yêu cầu ứng viên</h4>
            <p id="jobDetailRequirements">Đang cập nhật.</p>
          </div>
          <div class="job-detail-block" style="margin-top:14px">
            <h4>Quyền lợi</h4>
            <p id="jobDetailBenefits">Đang cập nhật.</p>
          </div>
        </div>
        <div>
          <div class="job-detail-block">
            <h4>Kỹ năng chuyên môn</h4>
            <p id="jobDetailSkills">Đang cập nhật.</p>
          </div>
          <div class="job-detail-block" style="margin-top:14px">
            <h4>Kỹ năng mềm</h4>
            <p id="jobDetailSoftSkills">Đang cập nhật.</p>
          </div>
          <div class="job-detail-block" style="margin-top:14px">
            <h4>Môi trường làm việc</h4>
            <p id="jobDetailEnvironment">Đang cập nhật.</p>
          </div>
        </div>
      </div>

      <div class="job-detail-block" style="margin-top:18px">
        <div class="job-detail-slider-head">
          <div>
            <h4 style="margin:0">Ứng viên gợi ý cùng ngành nghề</h4>
            <p id="jobDetailSuggestText">Ưu tiên hiển thị sinh viên ở đầu danh sách.</p>
          </div>
          <div class="job-detail-slider-controls">
            <button class="job-detail-slider-btn" type="button" id="jobDetailPrevBtn" onclick="moveJobCandidateSlide(-1)"><i class="ti ti-chevron-left"></i></button>
            <button class="job-detail-slider-btn" type="button" id="jobDetailNextBtn" onclick="moveJobCandidateSlide(1)"><i class="ti ti-chevron-right"></i></button>
          </div>
        </div>
        <div class="job-detail-slider-wrap">
          <div class="job-detail-slider-track" id="jobDetailCandidateTrack"></div>
        </div>
        <div class="job-detail-empty" id="jobDetailCandidateEmpty" style="display:none">Chưa có ứng viên phù hợp với ngành nghề này.</div>
      </div>
    </div>
  </div>
</div>
<!-- ===== CHANGE PASSWORD MODAL ===== -->
<div class="modal-overlay password-modal-overlay" id="changePasswordModal" aria-hidden="true">
  <div class="modal password-modal" role="dialog" aria-modal="true" aria-labelledby="changePasswordTitle">
    <form id="changePasswordForm" novalidate>
      <div class="modal-header">
        <div class="modal-title" id="changePasswordTitle"><i class="ti ti-key" style="color:#0d4e96;margin-right:6px"></i> Đổi mật khẩu</div>
        <button type="button" class="modal-close" data-change-password-close aria-label="Đóng"><i class="ti ti-x"></i></button>
      </div>
      <div class="modal-body">
        <div class="change-password-success" id="changePasswordSuccess"><i class="ti ti-circle-check"></i> Mật khẩu đã được kiểm tra hợp lệ.</div>

        <div class="password-field" data-field="oldPassword">
          <label class="form-label" for="oldPassword">Mật khẩu cũ <span class="required">*</span></label>
          <div class="password-input-wrap">
            <i class="ti ti-lock"></i>
            <input class="form-control" id="oldPassword" name="oldPassword" type="password" autocomplete="current-password" placeholder="Nhập mật khẩu hiện tại">
            <button type="button" class="password-toggle" data-toggle-password="oldPassword" aria-label="Hiện/ẩn mật khẩu"><i class="ti ti-eye"></i></button>
          </div>
          <div class="password-error"></div>
        </div>

        <div class="password-field" data-field="newPassword">
          <label class="form-label" for="newPassword">Mật khẩu mới <span class="required">*</span></label>
          <div class="password-input-wrap">
            <i class="ti ti-shield-lock"></i>
            <input class="form-control" id="newPassword" name="newPassword" type="password" autocomplete="new-password" placeholder="Tối thiểu 8 ký tự">
            <button type="button" class="password-toggle" data-toggle-password="newPassword" aria-label="Hiện/ẩn mật khẩu"><i class="ti ti-eye"></i></button>
          </div>
          <div class="password-error"></div>
        </div>

        <div class="password-field" data-field="confirmPassword">
          <label class="form-label" for="confirmPassword">Xác nhận mật khẩu mới <span class="required">*</span></label>
          <div class="password-input-wrap">
            <i class="ti ti-lock-check"></i>
            <input class="form-control" id="confirmPassword" name="confirmPassword" type="password" autocomplete="new-password" placeholder="Nhập lại mật khẩu mới">
            <button type="button" class="password-toggle" data-toggle-password="confirmPassword" aria-label="Hiện/ẩn mật khẩu"><i class="ti ti-eye"></i></button>
          </div>
          <div class="password-error"></div>
        </div>

        <div class="password-hint"><strong>Yêu cầu:</strong> nhập đủ các trường, mật khẩu mới tối thiểu 8 ký tự, khác mật khẩu cũ và phần xác nhận phải trùng khớp.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" data-change-password-close>Hủy</button>
        <button type="submit" class="btn-primary"><i class="ti ti-check"></i> Xác nhận</button>
      </div>
    </form>
  </div>
</div>
<script>
var employerJobPayloads = <?php echo json_encode($job_edit_payloads, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
var employerJobDetailPayloads = <?php echo json_encode($job_detail_payloads, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
var employerCandidateDirectory = <?php echo json_encode($candidate_directory_payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
var currentJobCandidateSlide = 0;
var currentJobCandidateStep = 1;
var currentStudentDirectoryPage = 1;
var currentEmployerJobsPage = 1;

// ===== NAVIGATION =====
function showPage(id) {
  document.querySelectorAll('.dash-page').forEach(p => p.classList.remove('active'));
  document.getElementById('page-' + id).classList.add('active');
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  event && event.target && event.target.closest('.nav-item') && event.target.closest('.nav-item').classList.add('active');
  // close sidebar on mobile
  if (window.innerWidth <= 900) closeSidebar();
}

// ===== PROFILE TABS =====
function showProfileTab(tab) {
  document.querySelectorAll('#page-profile .tab-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('profile-tab-' + tab).classList.add('active');
  document.querySelectorAll('.profile-hero-tab').forEach((t,i) => {
    t.classList.toggle('active', ['info','link','media'][i] === tab);
  });
}

// ===== MODAL TABS =====
const jobModalTabs = ['basic', 'require', 'benefit', 'time'];
let currentJobModalTab = 'basic';

const jobTabRequiredFields = {
  basic: [
    { id: 'job_title', message: 'Vui lòng nhập tên vị trí cần tuyển.' },
    { id: 'job_quantity', message: 'Vui lòng nhập số lượng tuyển hợp lệ.', validate: function(value){ return parseInt(value, 10) > 0; } },
    { id: 'job_category_id', message: 'Vui lòng chọn ngành nghề.' },
    { id: 'job_description', message: 'Vui lòng nhập mô tả công việc.' }
  ],
  require: [],
  benefit: [],
  time: [
    { id: 'deadline', message: 'Vui lòng chọn hạn chót nộp hồ sơ.' }
  ]
};

function getJobTabButton(tab) {
  return document.querySelector('#jobModal .tab-btn[data-job-tab="' + tab + '"]');
}

function setJobFieldError(field, message) {
  if (!field) return;
  var group = field.closest('.form-group');
  if (!group) return;
  var error = group.querySelector('.job-field-error');
  if (!error) {
    error = document.createElement('div');
    error.className = 'job-field-error';
    group.appendChild(error);
  }
  group.classList.toggle('invalid', !!message);
  error.textContent = message || '';
}

function clearJobTabErrors(tab) {
  var panel = document.getElementById('modal-tab-' + tab);
  if (!panel) return;
  panel.querySelectorAll('.form-group.invalid').forEach(function(group){
    group.classList.remove('invalid');
  });
  panel.querySelectorAll('.job-field-error').forEach(function(error){
    error.textContent = '';
  });
  var button = getJobTabButton(tab);
  if (button) button.classList.remove('has-error');
}

function clearJobErrors() {
  jobModalTabs.forEach(clearJobTabErrors);
}

function validateJobTab(tab, focusFirstInvalid) {
  var valid = true;
  var firstInvalid = null;
  clearJobTabErrors(tab);
  (jobTabRequiredFields[tab] || []).forEach(function(rule){
    var field = document.getElementById(rule.id);
    if (!field) return;
    var value = (field.value || '').trim();
    var fieldValid = rule.validate ? rule.validate(value) : value !== '';
    if (!fieldValid) {
      setJobFieldError(field, rule.message);
      valid = false;
      if (!firstInvalid) firstInvalid = field;
    }
  });
  var button = getJobTabButton(tab);
  if (button) button.classList.toggle('has-error', !valid);
  if (!valid && focusFirstInvalid && firstInvalid) {
    focusJobField(firstInvalid);
  }
  return valid;
}

function validateJobTabsThrough(targetTab) {
  var targetIndex = jobModalTabs.indexOf(targetTab);
  for (var i = 0; i < targetIndex; i++) {
    if (!validateJobTab(jobModalTabs[i], i === 0)) {
      switchModalTab(jobModalTabs[i], getJobTabButton(jobModalTabs[i]), false);
      return false;
    }
  }
  return true;
}

function updateJobModalFooter() {
  var index = jobModalTabs.indexOf(currentJobModalTab);
  var prevBtn = document.getElementById('jobPrevBtn');
  var nextBtn = document.getElementById('jobNextBtn');
  var submitBtn = document.getElementById('jobSubmitBtn');
  if (prevBtn) prevBtn.style.display = index > 0 ? '' : 'none';
  if (nextBtn) nextBtn.style.display = index < jobModalTabs.length - 1 ? '' : 'none';
  if (submitBtn) submitBtn.style.display = index === jobModalTabs.length - 1 ? '' : 'none';
}

function switchModalTab(tab, btn, shouldValidate) {
  if (shouldValidate && jobModalTabs.indexOf(tab) > jobModalTabs.indexOf(currentJobModalTab) && !validateJobTabsThrough(tab)) {
    return;
  }
  document.querySelectorAll('#jobModal .tab-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('modal-tab-' + tab).classList.add('active');
  document.querySelectorAll('#jobModal .tab-btn').forEach(b => b.classList.remove('active'));
  (btn || getJobTabButton(tab)).classList.add('active');
  currentJobModalTab = tab;
  updateJobModalFooter();
}

function goJobModalNext() {
  var index = jobModalTabs.indexOf(currentJobModalTab);
  if (!validateJobTab(currentJobModalTab, true) || index >= jobModalTabs.length - 1) return;
  var nextTab = jobModalTabs[index + 1];
  switchModalTab(nextTab, getJobTabButton(nextTab), false);
}

function goJobModalPrev() {
  var index = jobModalTabs.indexOf(currentJobModalTab);
  if (index <= 0) return;
  var prevTab = jobModalTabs[index - 1];
  switchModalTab(prevTab, getJobTabButton(prevTab), false);
}

function validateJobPostForm() {
  for (var i = 0; i < jobModalTabs.length; i++) {
    if (!validateJobTab(jobModalTabs[i], i === 0)) {
      switchModalTab(jobModalTabs[i], getJobTabButton(jobModalTabs[i]), false);
      var panel = document.getElementById('modal-tab-' + jobModalTabs[i]);
      var invalidField = panel ? panel.querySelector('.form-group.invalid .form-control') : null;
      if (invalidField) focusJobField(invalidField);
      return false;
    }
  }
  return true;
}

function bindJobCategorySearch() {
  bindJobSearchCombobox({
    comboId: 'jobCategoryCombo',
    triggerId: 'jobCategoryTrigger',
    selectedTextId: 'jobCategorySelectedText',
    emptyId: 'jobCategoryEmpty',
    searchId: 'job_category_search',
    selectId: 'job_category_id',
    optionSelector: '.job-category-option',
    placeholder: 'Chọn ngành nghề'
  });
}

function bindJobProvinceSearch() {
  bindJobSearchCombobox({
    comboId: 'jobProvinceCombo',
    triggerId: 'jobProvinceTrigger',
    selectedTextId: 'jobProvinceSelectedText',
    emptyId: 'jobProvinceEmpty',
    searchId: 'province_search',
    selectId: 'province_id',
    optionSelector: '.job-province-option',
    placeholder: 'Chọn tỉnh / thành phố'
  });
}

function bindJobSearchCombobox(config) {
  var combo = document.getElementById(config.comboId);
  var trigger = document.getElementById(config.triggerId);
  var selectedText = document.getElementById(config.selectedTextId);
  var empty = document.getElementById(config.emptyId);
  var search = document.getElementById(config.searchId);
  var select = document.getElementById(config.selectId);
  if (!combo || !trigger || !selectedText || !search || !select) return;

  var options = Array.prototype.slice.call(combo.querySelectorAll(config.optionSelector));

  function closeDropdown() {
    combo.classList.remove('open');
    trigger.setAttribute('aria-expanded', 'false');
  }

  function openDropdown() {
    combo.classList.add('open');
    trigger.setAttribute('aria-expanded', 'true');
    setTimeout(function(){ search.focus(); }, 40);
  }

  function updateSelectedLabel() {
    var selectedOption = select.options[select.selectedIndex];
    var hasValue = selectedOption && selectedOption.value !== '';
    selectedText.textContent = hasValue ? selectedOption.text : config.placeholder;
    trigger.classList.toggle('placeholder', !hasValue);
    options.forEach(function(option){
      option.classList.toggle('selected', option.getAttribute('data-value') === select.value);
    });
  }

  search.addEventListener('input', function(){
    var keyword = search.value.trim().toLowerCase();
    var visibleCount = 0;
    options.forEach(function(option){
      var visible = keyword === '' || option.textContent.toLowerCase().indexOf(keyword) !== -1;
      option.style.display = visible ? '' : 'none';
      if (visible) visibleCount++;
    });
    if (empty) empty.style.display = visibleCount > 0 ? 'none' : 'block';
  });

  trigger.addEventListener('click', function(e){
    e.stopPropagation();
    combo.classList.contains('open') ? closeDropdown() : openDropdown();
  });

  options.forEach(function(option){
    option.addEventListener('click', function(){
      select.value = option.getAttribute('data-value');
      search.value = '';
      options.forEach(function(item){ item.style.display = ''; });
      if (empty) empty.style.display = 'none';
      updateSelectedLabel();
      closeDropdown();
      select.dispatchEvent(new Event('change'));
    });
  });

  document.addEventListener('click', function(e){
    if (!combo.contains(e.target)) closeDropdown();
  });

  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeDropdown();
  });

  select.addEventListener('change', updateSelectedLabel);
  updateSelectedLabel();
}

function focusJobField(field) {
  if (!field) return;
  if (field.id === 'job_category_id') {
    var trigger = document.getElementById('jobCategoryTrigger');
    if (trigger) trigger.focus();
    return;
  }
  field.focus();
}

function resetJobCategorySearch() {
  resetJobSearchCombobox('jobCategoryCombo', 'jobCategoryTrigger', 'job_category_search', 'jobCategoryEmpty', '.job-category-option');
}

function resetJobProvinceSearch() {
  resetJobSearchCombobox('jobProvinceCombo', 'jobProvinceTrigger', 'province_search', 'jobProvinceEmpty', '.job-province-option');
}

function resetJobSearchCombobox(comboId, triggerId, searchId, emptyId, optionSelector) {
  var combo = document.getElementById(comboId);
  var trigger = document.getElementById(triggerId);
  var search = document.getElementById(searchId);
  var empty = document.getElementById(emptyId);
  if (combo) combo.classList.remove('open');
  if (trigger) trigger.setAttribute('aria-expanded', 'false');
  if (search) search.value = '';
  if (empty) empty.style.display = 'none';
  if (combo) {
    combo.querySelectorAll(optionSelector).forEach(function(option){
      option.style.display = '';
    });
  }
}

function syncJobCategoryCombobox() {
  var select = document.getElementById('job_category_id');
  if (select) select.dispatchEvent(new Event('change'));
}

function syncJobProvinceCombobox() {
  var select = document.getElementById('province_id');
  if (select) select.dispatchEvent(new Event('change'));
}

function setJobFormValue(id, value) {
  var field = document.getElementById(id);
  if (!field) return;
  field.value = value == null ? '' : value;
  field.dispatchEvent(new Event(field.tagName === 'SELECT' ? 'change' : 'input'));
}

function resetJobPostForm() {
  var form = document.getElementById('jobPostForm');
  if (form) form.reset();
  setJobFormValue('job_id', '');
  setJobFormValue('job_status', 'pending');
  syncJobCategoryCombobox();
  syncJobProvinceCombobox();
  clearJobErrors();
  resetJobCategorySearch();
  resetJobProvinceSearch();
}

function fillJobPostForm(job) {
  resetJobPostForm();
  if (!job) return;
  setJobFormValue('job_id', job.job_id || '');
  setJobFormValue('job_status', job.status || 'pending');
  setJobFormValue('job_title', job.title || '');
  setJobFormValue('job_quantity', job.quantity || 1);
  setJobFormValue('job_category_id', job.job_category_id || '');
  setJobFormValue('salaryType', job.salary_id || '');
  setJobFormValue('job_description', job.job_description || '');
  setJobFormValue('experience_years', job.experience_years || '');
  setJobFormValue('degree_required', job.degree_required || '');
  setJobFormValue('professional_skills', job.professional_skills || '');
  setJobFormValue('soft_skills', job.soft_skills || '');
  setJobFormValue('other_requirements', job.other_requirements || '');
  setJobFormValue('benefits_description', job.benefits_description || '');
  setJobFormValue('rewards_description', job.rewards_description || '');
  setJobFormValue('work_environment', job.work_environment || '');
  setJobFormValue('work_type', job.work_type || '');
  setJobFormValue('province_id', job.province_id || '');
  setJobFormValue('job_address_detail', job.address_detail || '');
  setJobFormValue('working_time', job.working_time || '');
  setJobFormValue('deadline', job.deadline || '');
  syncJobCategoryCombobox();
  syncJobProvinceCombobox();
}

function bindJobValidationCleanup() {
  Object.keys(jobTabRequiredFields).forEach(function(tab){
    (jobTabRequiredFields[tab] || []).forEach(function(rule){
      var field = document.getElementById(rule.id);
      if (!field) return;
      var eventName = field.tagName === 'SELECT' ? 'change' : 'input';
      field.addEventListener(eventName, function(){
        setJobFieldError(field, '');
      });
    });
  });
}

// ===== JOB MODAL =====
function openJobModal(jobId) {
  var job = jobId ? employerJobPayloads[String(jobId)] : null;
  document.getElementById('jobModal').classList.add('show');
  resetJobPostForm();
  if (job) fillJobPostForm(job);
  var modalTitle = document.getElementById('jobModalTitle');
  var submitBtn = document.getElementById('jobSubmitBtn');
  if (modalTitle) modalTitle.innerHTML = '<i class="ti ti-news" style="color:#0d4e96;margin-right:6px"></i> ' + (job ? 'Chỉnh sửa bài đăng tuyển dụng' : 'Tạo bài đăng tuyển dụng');
  if (submitBtn) submitBtn.innerHTML = '<i class="ti ti-send"></i> ' + (job ? 'Cập nhật tin' : 'Đăng tin ngay');
  switchModalTab('basic', getJobTabButton('basic'), false);
  document.body.style.overflow = 'hidden';
}
function closeJobModal() {
  document.getElementById('jobModal').classList.remove('show');
  resetJobPostForm();
  document.body.style.overflow = '';
}
document.getElementById('jobModal').addEventListener('click', function(e) {
  if (e.target === this) closeJobModal();
});

function employerEscapeHtml(value) {
  return String(value == null ? '' : value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function employerJobDetailText(value, fallback) {
  var text = String(value == null ? '' : value).trim();
  return text !== '' ? text : (fallback || 'Đang cập nhật.');
}

function renderJobDetailFacts(job) {
  var facts = [
    { label: 'Trạng thái', value: job.status || 'Đang cập nhật' },
    { label: 'Ngành nghề', value: job.job_category_name || 'Chưa phân loại' },
    { label: 'Mức lương', value: job.salary_name || 'Thỏa thuận' },
    { label: 'Địa điểm', value: job.province_name || 'Đang cập nhật' },
    { label: 'Số lượng tuyển', value: (job.quantity || 0) + ' người' },
    { label: 'Hình thức', value: job.work_type || 'Đang cập nhật' },
    { label: 'Hạn nộp', value: job.deadline || 'Đang cập nhật' },
    { label: 'Lượt xem', value: String(job.views_count || 0) }
  ];
  var html = '';
  facts.forEach(function(item){
    html += '<div class="job-detail-fact"><span>' + employerEscapeHtml(item.label) + '</span><strong>' + employerEscapeHtml(item.value) + '</strong></div>';
  });
  document.getElementById('jobDetailFacts').innerHTML = html;
}

function updateJobCandidateSlider() {
  var track = document.getElementById('jobDetailCandidateTrack');
  var cards = track ? track.querySelectorAll('.job-detail-candidate-card') : [];
  var prevBtn = document.getElementById('jobDetailPrevBtn');
  var nextBtn = document.getElementById('jobDetailNextBtn');
  var width = window.innerWidth;
  currentJobCandidateStep = width <= 640 ? 1 : (width <= 1024 ? 2 : 3);
  if (!track || !cards.length) {
    if (prevBtn) prevBtn.disabled = true;
    if (nextBtn) nextBtn.disabled = true;
    return;
  }
  var card = cards[0];
  var style = window.getComputedStyle(track);
  var gap = parseFloat(style.columnGap || style.gap || 14) || 14;
  var step = Math.max(1, currentJobCandidateStep);
  var maxIndex = Math.max(0, cards.length - step);
  if (currentJobCandidateSlide > maxIndex) currentJobCandidateSlide = maxIndex;
  var offset = currentJobCandidateSlide * (card.offsetWidth + gap);
  track.style.transform = 'translateX(-' + offset + 'px)';
  if (prevBtn) prevBtn.disabled = currentJobCandidateSlide <= 0;
  if (nextBtn) nextBtn.disabled = currentJobCandidateSlide >= maxIndex;
}

function moveJobCandidateSlide(direction) {
  var track = document.getElementById('jobDetailCandidateTrack');
  var cards = track ? track.querySelectorAll('.job-detail-candidate-card') : [];
  if (!cards.length) return;
  var step = Math.max(1, currentJobCandidateStep);
  var maxIndex = Math.max(0, cards.length - step);
  currentJobCandidateSlide += direction * step;
  if (currentJobCandidateSlide < 0) currentJobCandidateSlide = 0;
  if (currentJobCandidateSlide > maxIndex) currentJobCandidateSlide = maxIndex;
  updateJobCandidateSlider();
}

function renderJobDetailCandidates(job) {
  var track = document.getElementById('jobDetailCandidateTrack');
  var empty = document.getElementById('jobDetailCandidateEmpty');
  var suggestText = document.getElementById('jobDetailSuggestText');
  if (!track || !empty || !suggestText) return;

  var candidates = employerCandidateDirectory.filter(function(candidate){
    return String(candidate.job_category_id || '') === String(job.job_category_id || '');
  }).sort(function(a, b){
    if ((b.is_student || 0) !== (a.is_student || 0)) return (b.is_student || 0) - (a.is_student || 0);
    return (a.full_name || '').localeCompare(b.full_name || '', 'vi');
  });

  if (!candidates.length) {
    track.innerHTML = '';
    empty.style.display = 'block';
    suggestText.textContent = 'Chưa có ứng viên nào cùng ngành nghề với bài đăng này.';
    currentJobCandidateSlide = 0;
    updateJobCandidateSlider();
    return;
  }

  empty.style.display = 'none';
  suggestText.textContent = 'Có ' + candidates.length + ' ứng viên cùng ngành nghề, sinh viên được ưu tiên hiển thị trước.';
  track.innerHTML = candidates.map(function(candidate){
    var avatarHtml = candidate.avatar_url
      ? '<img src="' + employerEscapeHtml(candidate.avatar_url) + '" alt="' + employerEscapeHtml(candidate.full_name) + '">'
      : employerEscapeHtml(candidate.initials || '');
    var badges = [];
    if (candidate.is_student) badges.push('<span class="job-detail-candidate-badge student"><i class="ti ti-school"></i> Sinh viên</span>');
    if (candidate.salary_name) badges.push('<span class="job-detail-candidate-badge"><i class="ti ti-wallet"></i> ' + employerEscapeHtml(candidate.salary_name) + '</span>');
    if (candidate.desired_province_name) badges.push('<span class="job-detail-candidate-badge"><i class="ti ti-map-pin"></i> ' + employerEscapeHtml(candidate.desired_province_name) + '</span>');
    return '<a class="job-detail-candidate-card" href="' + employerEscapeHtml(candidate.candidate_url || '#') + '">' +
      '<div class="job-detail-candidate-top">' +
        '<div class="job-detail-candidate-avatar">' + avatarHtml + '</div>' +
        '<div>' +
          '<div class="job-detail-candidate-name">' + employerEscapeHtml(candidate.full_name) + '</div>' +
          '<div class="job-detail-candidate-role">' + employerEscapeHtml(candidate.desired_position || 'Ứng viên tự do') + '</div>' +
        '</div>' +
      '</div>' +
      '<div class="job-detail-candidate-meta">' + employerEscapeHtml(candidate.job_category_name || 'Chưa cập nhật ngành nghề') + '</div>' +
      (candidate.school_name ? '<div class="job-detail-candidate-meta">Trường: ' + employerEscapeHtml(candidate.school_name) + '</div>' : '') +
      '<div class="job-detail-candidate-badges">' + badges.join('') + '</div>' +
    '</a>';
  }).join('');

  currentJobCandidateSlide = 0;
  updateJobCandidateSlider();
}

function openJobDetailModal(jobId) {
  var job = employerJobDetailPayloads[String(jobId)];
  if (!job) return;
  document.getElementById('jobDetailModalTitle').innerHTML = '<i class="ti ti-eye" style="color:#0d4e96;margin-right:6px"></i> ' + employerEscapeHtml(job.title || 'Chi tiết bài đăng');
  renderJobDetailFacts(job);
  document.getElementById('jobDetailDescription').textContent = employerJobDetailText(job.job_description);
  document.getElementById('jobDetailRequirements').textContent = employerJobDetailText(job.other_requirements);
  document.getElementById('jobDetailBenefits').textContent = employerJobDetailText((job.benefits_description || '') + ((job.rewards_description || '') ? '\n\n' + job.rewards_description : ''));
  document.getElementById('jobDetailSkills').textContent = employerJobDetailText(job.professional_skills);
  document.getElementById('jobDetailSoftSkills').textContent = employerJobDetailText(job.soft_skills);
  document.getElementById('jobDetailEnvironment').textContent = employerJobDetailText(job.work_environment);
  renderJobDetailCandidates(job);
  document.getElementById('jobDetailModal').classList.add('show');
  document.body.style.overflow = 'hidden';
}

function closeJobDetailModal() {
  document.getElementById('jobDetailModal').classList.remove('show');
  document.body.style.overflow = '';
}

document.getElementById('jobDetailModal').addEventListener('click', function(e) {
  if (e.target === this) closeJobDetailModal();
});

function applyStudentDirectoryFilters() {
  var cards = Array.prototype.slice.call(document.querySelectorAll('#studentGrid .student-directory-card'));
  var emptyState = document.getElementById('studentEmptyState');
  var countEl = document.getElementById('studentVisibleCount');
  var pageSummary = document.getElementById('studentPageSummary');
  var pagination = document.getElementById('studentPagination');
  var grid = document.getElementById('studentGrid');
  if (!cards.length) return;
  var keyword = (document.getElementById('studentKeywordFilter') || {}).value || '';
  var type = (document.getElementById('studentTypeFilter') || {}).value || '';
  var category = (document.getElementById('studentCategoryFilter') || {}).value || '';
  var province = (document.getElementById('studentProvinceFilter') || {}).value || '';
  var salary = (document.getElementById('studentSalaryFilter') || {}).value || '';
  keyword = keyword.trim().toLowerCase();
  var filtered = [];
  cards.forEach(function(card){
    var match = (!keyword || (card.getAttribute('data-name') || '').indexOf(keyword) !== -1)
      && (!type || card.getAttribute('data-student') === type)
      && (!category || card.getAttribute('data-category') === category)
      && (!province || card.getAttribute('data-province') === province)
      && (!salary || card.getAttribute('data-salary') === salary);
    if (match) filtered.push(card);
    card.style.display = 'none';
  });

  var columns = 1;
  if (grid) {
    var template = window.getComputedStyle(grid).gridTemplateColumns || '';
    var matches = template.match(/[^ ]+/g);
    columns = matches && matches.length ? matches.length : 1;
  }
  var perPage = Math.max(1, columns * 4);
  var totalItems = filtered.length;
  var totalPages = Math.max(1, Math.ceil(totalItems / perPage));
  if (currentStudentDirectoryPage > totalPages) currentStudentDirectoryPage = totalPages;
  if (currentStudentDirectoryPage < 1) currentStudentDirectoryPage = 1;

  var start = (currentStudentDirectoryPage - 1) * perPage;
  var end = start + perPage;
  filtered.slice(start, end).forEach(function(card){
    card.style.display = '';
  });

  if (countEl) countEl.textContent = String(totalItems);
  if (pageSummary) pageSummary.textContent = totalItems > 0 ? '(Trang ' + currentStudentDirectoryPage + '/' + totalPages + ')' : '';
  if (emptyState) emptyState.style.display = totalItems > 0 ? 'none' : 'block';
  if (pagination) {
    if (totalItems <= 0) {
      pagination.style.display = 'none';
      pagination.innerHTML = '';
    } else {
      var html = '<button type="button" class="student-page-btn" ' + (currentStudentDirectoryPage <= 1 ? 'disabled' : '') + ' data-page="' + (currentStudentDirectoryPage - 1) + '"><i class="ti ti-chevron-left"></i></button>';
      for (var i = 1; i <= totalPages; i++) {
        html += '<button type="button" class="student-page-btn ' + (i === currentStudentDirectoryPage ? 'active' : '') + '" data-page="' + i + '">' + i + '</button>';
      }
      html += '<button type="button" class="student-page-btn" ' + (currentStudentDirectoryPage >= totalPages ? 'disabled' : '') + ' data-page="' + (currentStudentDirectoryPage + 1) + '"><i class="ti ti-chevron-right"></i></button>';
      pagination.innerHTML = html;
      pagination.style.display = totalPages > 1 ? 'flex' : 'none';
      pagination.querySelectorAll('[data-page]').forEach(function(button){
        button.addEventListener('click', function(){
          if (button.disabled) return;
          currentStudentDirectoryPage = parseInt(button.getAttribute('data-page'), 10) || 1;
          applyStudentDirectoryFilters();
        });
      });
    }
  }
}

function bindStudentDirectoryFilters() {
  ['studentKeywordFilter', 'studentTypeFilter', 'studentCategoryFilter', 'studentProvinceFilter', 'studentSalaryFilter'].forEach(function(id){
    var field = document.getElementById(id);
    if (!field) return;
    field.addEventListener(field.tagName === 'SELECT' ? 'change' : 'input', function(){
      currentStudentDirectoryPage = 1;
      applyStudentDirectoryFilters();
    });
  });
  var resetBtn = document.getElementById('studentFilterReset');
  if (resetBtn) {
    resetBtn.addEventListener('click', function(){
      ['studentKeywordFilter', 'studentTypeFilter', 'studentCategoryFilter', 'studentProvinceFilter', 'studentSalaryFilter'].forEach(function(id){
        var field = document.getElementById(id);
        if (field) field.value = '';
      });
      currentStudentDirectoryPage = 1;
      applyStudentDirectoryFilters();
    });
  }
}

function applyEmployerJobsPagination() {
  var cards = Array.prototype.slice.call(document.querySelectorAll('#employerJobPostsList .dynamic-job-card'));
  var emptyCard = document.querySelector('#employerJobPostsList .dynamic-empty-job-card');
  var pagination = document.getElementById('employerJobsPagination');
  if (!pagination) return;

  if (!cards.length) {
    pagination.style.display = 'none';
    pagination.innerHTML = '';
    if (emptyCard) emptyCard.style.display = '';
    return;
  }

  var perPage = 15;
  var totalPages = Math.max(1, Math.ceil(cards.length / perPage));
  if (currentEmployerJobsPage > totalPages) currentEmployerJobsPage = totalPages;
  if (currentEmployerJobsPage < 1) currentEmployerJobsPage = 1;

  cards.forEach(function(card, index) {
    var start = (currentEmployerJobsPage - 1) * perPage;
    var end = start + perPage;
    card.style.display = index >= start && index < end ? '' : 'none';
  });

  var html = '<button type="button" class="page-btn" ' + (currentEmployerJobsPage <= 1 ? 'disabled' : '') + ' data-page="' + (currentEmployerJobsPage - 1) + '"><i class="ti ti-chevron-left" style="font-size:13px"></i></button>';
  for (var i = 1; i <= totalPages; i++) {
    html += '<button type="button" class="page-btn ' + (i === currentEmployerJobsPage ? 'active' : '') + '" data-page="' + i + '">' + i + '</button>';
  }
  html += '<button type="button" class="page-btn" ' + (currentEmployerJobsPage >= totalPages ? 'disabled' : '') + ' data-page="' + (currentEmployerJobsPage + 1) + '"><i class="ti ti-chevron-right" style="font-size:13px"></i></button>';

  pagination.innerHTML = html;
  pagination.style.display = totalPages > 1 ? 'flex' : 'none';
  pagination.querySelectorAll('[data-page]').forEach(function(button){
    button.addEventListener('click', function(){
      if (button.disabled) return;
      currentEmployerJobsPage = parseInt(button.getAttribute('data-page'), 10) || 1;
      applyEmployerJobsPagination();
    });
  });
}

window.addEventListener('resize', function(){
  var width = window.innerWidth;
  currentJobCandidateStep = width <= 640 ? 1 : (width <= 1024 ? 2 : 3);
  updateJobCandidateSlider();
  applyStudentDirectoryFilters();
});

function showEmployerAjaxResult(data, successTitle, errorTitle) {
  if (data.status == 200) {
    Swal.fire({
      toast: true,
      icon: 'success',
      title: data.message || successTitle,
      showConfirmButton: false,
      timer: 1200,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    }).then((result) => {
      window.location.reload();
    });
  } else {
    Swal.fire({
      icon: 'error',
      title: errorTitle,
      text: data.message || 'Có lỗi xảy ra, vui lòng thử lại.',
      footer: '<a href=""></a>'
    });
  }
}

$(document).ready(function(){
  $("#companyProfileForm").submit(function(){
    var company_name = $('#company_name').val();
    var tax_code = $('#tax_code').val();
    var address_detail = $('#company_address_detail').val();
    var job_category_id = $('#company_job_category_id').val();
    var company_size = $('#company_size').val();
    var website_url = $('#website_url').val();
    var fanpage_url = $('#fanpage_url').val();
    var description = $('#company_description').val();

    $.ajax({
      "type": "POST",
      "url": "<?php echo XC_URL; ?>/api/employercompanyupdate",
      "data": {
        'company_name': company_name,
        'tax_code': tax_code,
        'address_detail': address_detail,
        'job_category_id': job_category_id,
        'company_size': company_size,
        'website_url': website_url,
        'fanpage_url': fanpage_url,
        'description': description
      },
      "dataType": "json",
      success:function(data){
        showEmployerAjaxResult(data, 'Cập nhật thông tin công ty thành công', 'Lưu thông tin công ty thất bại!');
      }
    });
    return false;
  });

  $("#jobPostForm").submit(function(){
    if (!validateJobPostForm()) return false;
    var job_id = $('#job_id').val();
    var status = $('#job_status').val();
    var title = $('#job_title').val();
    var quantity = $('#job_quantity').val();
    var job_category_id = $('#job_category_id').val();
    var job_description = $('#job_description').val();
    var experience_years = $('#experience_years').val();
    var degree_required = $('#degree_required').val();
    var professional_skills = $('#professional_skills').val();
    var soft_skills = $('#soft_skills').val();
    var other_requirements = $('#other_requirements').val();
    var benefits_description = $('#benefits_description').val();
    var rewards_description = $('#rewards_description').val();
    var work_environment = $('#work_environment').val();
    var work_type = $('#work_type').val();
    var province_id = $('#province_id').val();
    var address_detail = $('#job_address_detail').val();
    var working_time = $('#working_time').val();
    var deadline = $('#deadline').val();

    $.ajax({
      "type": "POST",
      "url": "<?php echo XC_URL; ?>/api/employerjobsave",
      "data": {
        'job_id': job_id,
        'status': status,
        'title': title,
        'quantity': quantity,
        'salary_id': $('#salaryType').val(),
        'job_category_id': job_category_id,
        'job_description': job_description,
        'experience_years': experience_years,
        'degree_required': degree_required,
        'professional_skills': professional_skills,
        'soft_skills': soft_skills,
        'other_requirements': other_requirements,
        'benefits_description': benefits_description,
        'rewards_description': rewards_description,
        'work_environment': work_environment,
        'work_type': work_type,
        'province_id': province_id,
        'address_detail': address_detail,
        'working_time': working_time,
        'deadline': deadline
      },
      "dataType": "json",
      success:function(data){
        showEmployerAjaxResult(data, job_id ? 'Cập nhật bài đăng thành công' : 'Lưu bài đăng thành công', job_id ? 'Cập nhật bài đăng thất bại!' : 'Lưu bài đăng thất bại!');
      }
    });
    return false;
  });

  $("#companyImagesForm").submit(function(){
    var formData = new FormData(this);
    $.ajax({
      "type": "POST",
      "url": "<?php echo XC_URL; ?>/api/employerimagesupdate",
      "data": formData,
      "dataType": "json",
      "processData": false,
      "contentType": false,
      success:function(data){
        showEmployerAjaxResult(data, 'Upload hình ảnh thành công', 'Upload hình ảnh thất bại!');
      }
    });
    return false;
  });

  $("#accountForm").submit(function(){
    var user_username = $('#account_username').val();
    var full_name = $('#account_full_name').val();
    var user_email = $('#account_email').val();
    var user_phone = $('#account_phone').val();
    var user_is_subscribed = $('#account_is_subscribed').is(':checked') ? 1 : 0;

    $.ajax({
      "type": "POST",
      "url": "<?php echo XC_URL; ?>/api/employeraccountupdate",
      "data": {
        'user_username': user_username,
        'full_name': full_name,
        'user_email': user_email,
        'user_phone': user_phone,
        'user_is_subscribed': user_is_subscribed
      },
      "dataType": "json",
      success:function(data){
        showEmployerAjaxResult(data, 'Cập nhật tài khoản thành công', 'Cập nhật tài khoản thất bại!');
      }
    });
    return false;
  });

  bindImagePreview('logoFileInput', 'logoPreview', 'logoPreviewText');
  bindImagePreview('coverFileInput', 'coverPreview', 'coverPreviewIcon');
  bindJobCategorySearch();
  bindJobProvinceSearch();
  bindJobValidationCleanup();
  applyEmployerJobsPagination();
  bindStudentDirectoryFilters();
  applyStudentDirectoryFilters();
});

function deleteEmployerJob(id) {
  if (!id || !confirm('Bạn có chắc muốn xóa bài đăng này?')) return;
  $.ajax({
    "type": "POST",
    "url": "<?php echo XC_URL; ?>/api/employerjobdelete",
    "data": {
      "id": id
    },
    "dataType": "json",
    success:function(data){
      showEmployerAjaxResult(data, 'Xóa bài đăng thành công', 'Xóa bài đăng thất bại!');
    }
  });
}

function bindImagePreview(inputId, imageId, placeholderId) {
  const input = document.getElementById(inputId);
  const image = document.getElementById(imageId);
  const placeholder = document.getElementById(placeholderId);
  if (!input || !image) return;

  input.addEventListener('change', function(){
    const file = input.files && input.files[0];
    if (!file) return;
    if (!file.type || file.type.indexOf('image/') !== 0) {
      alert('Vui lòng chọn file hình ảnh.');
      input.value = '';
      return;
    }
    image.src = URL.createObjectURL(file);
    image.style.display = 'block';
    if (placeholder) placeholder.style.display = 'none';
  });
}

// ===== SALARY TOGGLE =====
function toggleSalaryRange(val) {
  document.getElementById('salaryRangeGroup').style.display = val === 'negotiate' ? 'none' : '';
}

// ===== MOBILE SIDEBAR =====
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const sidebarToggle = document.getElementById('sidebarToggle');

function openSidebar() {
  sidebar.classList.add('open');
  sidebarOverlay.classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  sidebar.classList.remove('open');
  sidebarOverlay.classList.remove('show');
  document.body.style.overflow = '';
}
sidebarToggle.addEventListener('click', openSidebar);
sidebarOverlay.addEventListener('click', closeSidebar);

</script>

<script>
(function(){
  var modal = document.getElementById('changePasswordModal');
  var form = document.getElementById('changePasswordForm');
  var success = document.getElementById('changePasswordSuccess');
  if(!modal || !form) return;

  var fields = {
    oldPassword: document.getElementById('oldPassword'),
    newPassword: document.getElementById('newPassword'),
    confirmPassword: document.getElementById('confirmPassword')
  };

  function fieldWrap(name){
    return form.querySelector('[data-field="' + name + '"]');
  }

  function setError(name, message){
    var wrap = fieldWrap(name);
    if(!wrap) return;
    var error = wrap.querySelector('.password-error');
    wrap.classList.toggle('invalid', !!message);
    if(error) error.textContent = message || '';
  }

  function clearErrors(){
    Object.keys(fields).forEach(function(name){ setError(name, ''); });
    if(success) success.classList.remove('show');
  }

  function validateChangePasswordForm(){
    var oldValue = fields.oldPassword.value.trim();
    var newValue = fields.newPassword.value.trim();
    var confirmValue = fields.confirmPassword.value.trim();
    var valid = true;
    clearErrors();

    if(!oldValue){ setError('oldPassword', 'Vui lòng nhập mật khẩu cũ.'); valid = false; }
    if(!newValue){
      setError('newPassword', 'Vui lòng nhập mật khẩu mới.'); valid = false;
    } else if(newValue.length < 8){
      setError('newPassword', 'Mật khẩu mới phải có tối thiểu 8 ký tự.'); valid = false;
    } else if(oldValue && newValue === oldValue){
      setError('newPassword', 'Mật khẩu mới phải khác mật khẩu cũ.'); valid = false;
    }
    if(!confirmValue){
      setError('confirmPassword', 'Vui lòng xác nhận mật khẩu mới.'); valid = false;
    } else if(newValue && confirmValue !== newValue){
      setError('confirmPassword', 'Xác nhận mật khẩu mới không khớp.'); valid = false;
    }
    return valid;
  }

  function openChangePasswordModal(){
    form.reset();
    clearErrors();
    modal.classList.add('show');
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
    setTimeout(function(){ fields.oldPassword.focus(); }, 80);
  }

  function closeChangePasswordModal(){
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
    form.reset();
    clearErrors();
  }

  document.addEventListener('click', function(e){
    var accountOpener = e.target.closest('[data-account-open]');
    if(accountOpener){
      e.preventDefault();
      document.querySelectorAll('.employer-user-menu.open').forEach(function(menu){ menu.classList.remove('open'); });
      document.body.classList.remove('menu-open');
      showPage('account');
      return;
    }

    var opener = e.target.closest('[data-change-password-open]');
    if(opener){
      e.preventDefault();
      document.querySelectorAll('.employer-user-menu.open').forEach(function(menu){ menu.classList.remove('open'); });
      document.body.classList.remove('menu-open');
      openChangePasswordModal();
      return;
    }
    if(e.target.closest('[data-change-password-close]')){
      e.preventDefault();
      closeChangePasswordModal();
    }
  });

  modal.addEventListener('click', function(e){
    if(e.target === modal) closeChangePasswordModal();
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape' && modal.classList.contains('show')) closeChangePasswordModal();
  });

  Object.keys(fields).forEach(function(name){
    fields[name].addEventListener('input', validateChangePasswordForm);
  });

  document.querySelectorAll('[data-toggle-password]').forEach(function(button){
    button.addEventListener('click', function(){
      var input = document.getElementById(button.getAttribute('data-toggle-password'));
      if(!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      button.innerHTML = '<i class="ti ' + (show ? 'ti-eye-off' : 'ti-eye') + '"></i>';
    });
  });

  form.addEventListener('submit', function(e){
    e.preventDefault();
    if(!validateChangePasswordForm()) return;
    if(success) success.classList.add('show');
    setTimeout(function(){
      alert('Đổi mật khẩu thành công!');
      closeChangePasswordModal();
    }, 250);
  });
})();
</script>
<?php require 'footer.php'; ?>

