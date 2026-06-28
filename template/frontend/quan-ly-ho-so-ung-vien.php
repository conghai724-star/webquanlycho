<?php require 'header.php'; ?>
<?php
$candidateProfileH = function($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); };
$candidate = isset($candidate) && $candidate ? $candidate : (object)array();
$candidateUser = isset($candidate_user) && $candidate_user ? $candidate_user : (object)array();
$provinces = isset($candidate_provinces) && is_array($candidate_provinces) ? $candidate_provinces : array();
$categories = isset($candidate_categories) && is_array($candidate_categories) ? $candidate_categories : array();
$salaries = isset($candidate_salaries) && is_array($candidate_salaries) ? $candidate_salaries : array();
$experiences = isset($candidate_experiences) && is_array($candidate_experiences) ? $candidate_experiences : array();
$certificates = isset($candidate_certificates) && is_array($candidate_certificates) ? $candidate_certificates : array();
$applications = isset($candidate_applications) && is_array($candidate_applications) ? $candidate_applications : array();
$completeness = isset($candidate_completeness) ? (int)$candidate_completeness : 0;
$approvalStatus = isset($candidate->status) ? (int)$candidate->status : 0;
$rawSkills = isset($candidate->soft_skills) ? trim((string)$candidate->soft_skills) : '';
$decodedSkills = $rawSkills !== '' ? json_decode($rawSkills, true) : array();
if(!is_array($decodedSkills)){ $decodedSkills = array_filter(array_map('trim', explode(',', $rawSkills))); }
$skillsText = implode(', ', $decodedSkills);
$fullName = trim((string)($candidate->full_name ?? $candidateUser->full_name ?? 'Ứng viên'));
$initial = $fullName !== '' ? mb_strtoupper(mb_substr($fullName, 0, 1, 'UTF-8'), 'UTF-8') : 'U';
$avatarUrl = trim((string)($candidate->avatar_url ?? ''));
if($avatarUrl !== '' && !preg_match('#^https?://#i', $avatarUrl)){ $avatarUrl = XC_URL.'/'.ltrim($avatarUrl, '/'); }
$cvUrl = trim((string)($candidate->cv_url ?? ''));
if($cvUrl !== '' && !preg_match('#^https?://#i', $cvUrl)){ $cvUrl = XC_URL.'/'.ltrim($cvUrl, '/'); }
$shortGoal = trim((string)($candidate->career_goal_short ?? ''));
$longGoal = trim((string)($candidate->career_goal_long ?? $candidate->career_goal ?? ''));
$approved = $approvalStatus === 2;
$pendingApproval = $approvalStatus === 1;
$statusText = $approved ? 'Hồ sơ đã được phê duyệt' : ($pendingApproval ? 'Hồ sơ đang chờ phê duyệt' : 'Hồ sơ chưa gửi phê duyệt');
$statusClass = $approved ? 'approved' : ($pendingApproval ? 'pending' : 'draft');
$workTypes = array('full_time' => 'Toàn thời gian', 'part_time' => 'Bán thời gian', 'remote' => 'Làm việc từ xa', 'hybrid' => 'Linh hoạt', 'any' => 'Không giới hạn');
$degrees = array('high_school' => 'THPT', 'intermediate' => 'Trung cấp', 'college' => 'Cao đẳng', 'university' => 'Đại học', 'postgraduate' => 'Sau đại học', 'other' => 'Khác');
$applicationStatus = array(
  'submitted' => array('Đã nộp hồ sơ', 'submitted'),
  'reviewing' => array('Đang xem xét', 'reviewing'),
  'interview' => array('Mời phỏng vấn', 'interview'),
  'offered' => array('Đã nhận việc', 'offered'),
  'rejected' => array('Chưa phù hợp', 'rejected'),
  'withdrawn' => array('Đã rút hồ sơ', 'withdrawn')
);
?>

<style>
.candidate-profile-page{background:#f5f8fc;min-height:calc(100vh - 64px);padding:32px 0 56px;color:#172033}.candidate-profile-shell{max-width:1340px;margin:0 auto;padding:0 20px}.candidate-breadcrumb{display:flex;gap:8px;align-items:center;color:#7b8797;font-size:13px;margin:0 0 18px}.candidate-breadcrumb a{color:#1767c7;text-decoration:none}.candidate-hero{background:linear-gradient(120deg,#0d4e96,#176dc8 62%,#58a4eb);border-radius:22px;padding:28px 32px;color:#fff;display:flex;align-items:center;gap:20px;box-shadow:0 14px 32px rgba(13,78,150,.18);position:relative;overflow:hidden}.candidate-hero:after{content:'';position:absolute;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.12);right:-100px;top:-150px}.candidate-avatar-wrap{position:relative;z-index:1;flex:0 0 88px}.candidate-avatar{width:88px;height:88px;border-radius:50%;border:4px solid rgba(255,255,255,.6);background:#e8f2ff;color:#0d4e96;font-size:32px;font-weight:800;display:flex;align-items:center;justify-content:center;overflow:hidden}.candidate-avatar img{width:100%;height:100%;object-fit:cover}.candidate-avatar-check{position:absolute;right:0;bottom:0;width:28px;height:28px;border:3px solid #fff;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#1687e8;color:#fff;font-size:16px}.candidate-hero-main{position:relative;z-index:1;min-width:0;flex:1}.candidate-hero-main h1{font-size:25px;line-height:1.25;margin:0 0 6px;font-weight:800}.candidate-hero-main p{margin:0;color:rgba(255,255,255,.83);font-size:14px}.candidate-status{display:inline-flex;align-items:center;gap:6px;margin-top:12px;border-radius:20px;padding:5px 10px;font-size:12px;font-weight:700;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.28)}.candidate-status.approved{background:#dbf8eb;color:#12633d;border-color:#b9ecd3}.candidate-status.pending{background:#fff4d9;color:#875500;border-color:#ffe6aa}.candidate-layout{display:grid;grid-template-columns:minmax(0,1fr) 312px;gap:24px;margin-top:24px;align-items:start}.candidate-section-card,.candidate-completion-card,.candidate-applications{background:#fff;border:1px solid #e3eaf3;border-radius:16px;box-shadow:0 5px 18px rgba(21,53,84,.04)}.candidate-section-card{margin-bottom:20px;overflow:hidden}.candidate-section-head{display:flex;align-items:center;gap:10px;padding:18px 22px;border-bottom:1px solid #edf1f6}.candidate-section-head i{width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:9px;background:#e8f2ff;color:#0d4e96;font-size:18px}.candidate-section-head h2{font-size:16px;margin:0;font-weight:800;color:#162033}.candidate-section-head p{margin:3px 0 0;font-size:12px;color:#7a8797}.candidate-section-body{padding:22px}.candidate-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.candidate-field{display:flex;flex-direction:column;gap:6px}.candidate-field.full{grid-column:1/-1}.candidate-field label{font-size:12px;color:#4d5c70;font-weight:700}.candidate-field label .required{color:#d83838}.candidate-field input,.candidate-field select,.candidate-field textarea{width:100%;border:1.5px solid #dce5ef;border-radius:10px;padding:10px 12px;color:#1e293b;font:inherit;font-size:13px;background:#fff;transition:border .18s,box-shadow .18s}.candidate-field input:focus,.candidate-field select:focus,.candidate-field textarea:focus{outline:0;border-color:#176dc8;box-shadow:0 0 0 3px rgba(23,109,200,.1)}.candidate-field textarea{min-height:100px;resize:vertical;line-height:1.55}.candidate-field small{font-size:11px;color:#8793a4}.candidate-switch{display:flex;align-items:center;gap:10px;margin-top:2px;font-size:13px;font-weight:650;color:#304156}.candidate-switch input{width:18px;height:18px;accent-color:#0d4e96}.candidate-side{position:sticky;top:86px}.candidate-completion-card{padding:22px}.candidate-completion-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}.candidate-completion-title{font-size:15px;font-weight:800;color:#172033}.candidate-completion-copy{font-size:12px;color:#77869a;line-height:1.55;margin:5px 0 15px}.candidate-completion-value{color:#0d4e96;font-size:26px;font-weight:850;line-height:1}.candidate-progress{height:10px;background:#e7eef6;border-radius:30px;overflow:hidden}.candidate-progress span{height:100%;display:block;border-radius:inherit;background:linear-gradient(90deg,#0d4e96,#2e9dea);transition:width .25s ease}.candidate-checklist{margin:17px 0 0;padding:0;list-style:none;display:grid;gap:9px}.candidate-checklist li{font-size:12px;color:#68778b;display:flex;gap:7px;align-items:center}.candidate-checklist i{font-size:16px;color:#a5b4c5}.candidate-checklist .done i{color:#1ca76c}.candidate-submit-approval{margin-top:20px;width:100%;border:0;border-radius:10px;background:#0d4e96;color:#fff;font:inherit;font-weight:750;font-size:13px;padding:12px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px}.candidate-submit-approval:hover{background:#093e79}.candidate-submit-approval[hidden]{display:none}.candidate-approval-note{margin-top:16px;border-radius:10px;padding:11px 12px;font-size:12px;line-height:1.55;background:#f4f7fb;color:#6d7b8e}.candidate-approval-note.approved{color:#12633d;background:#e9f9f0}.candidate-approval-note.pending{color:#835807;background:#fff6e2}.candidate-actions{display:flex;justify-content:flex-end;padding-top:2px}.candidate-save{border:0;border-radius:10px;padding:11px 18px;background:#0d4e96;color:#fff;font:inherit;font-size:13px;font-weight:750;cursor:pointer;display:inline-flex;align-items:center;gap:7px}.candidate-save:disabled,.candidate-submit-approval:disabled{opacity:.7;cursor:wait}.candidate-message{margin:0 0 16px;border-radius:10px;padding:11px 13px;font-size:13px;display:none}.candidate-message.error{display:block;background:#fff0f0;color:#b52a2a}.candidate-message.success{display:block;background:#e9f9f0;color:#12633d}.candidate-applications{margin-top:24px;padding:22px}.candidate-applications-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:17px}.candidate-applications h2{margin:0;font-size:18px;font-weight:800}.candidate-applications p{margin:4px 0 0;font-size:12px;color:#78869a}.candidate-app-count{background:#e8f2ff;color:#0d4e96;font-size:12px;font-weight:750;padding:5px 10px;border-radius:20px}.candidate-application-list{display:grid;gap:11px}.candidate-application{display:flex;align-items:center;gap:14px;padding:15px;border:1px solid #e5ecf4;border-radius:13px;transition:.18s background,.18s border}.candidate-application:hover{border-color:#afcceb;background:#fafeff}.candidate-application-logo{height:46px;width:46px;border-radius:11px;background:#e8f2ff;color:#0d4e96;display:flex;align-items:center;justify-content:center;font-weight:850;font-size:15px;overflow:hidden;flex:0 0 auto}.candidate-application-logo img{width:100%;height:100%;object-fit:contain;background:#fff}.candidate-application-main{flex:1;min-width:0}.candidate-application-title{font-size:14px;font-weight:800;color:#182235;text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.candidate-application-title:hover{color:#0d4e96}.candidate-application-company{margin-top:4px;color:#637286;font-size:12px}.candidate-application-meta{margin-top:7px;display:flex;flex-wrap:wrap;gap:6px}.candidate-meta-tag{border-radius:6px;padding:3px 7px;background:#f1f5fa;color:#65758b;font-size:11px}.candidate-application-right{display:flex;flex-direction:column;align-items:flex-end;gap:7px;flex:0 0 auto}.candidate-application-date{font-size:11px;color:#8a97a8}.candidate-application-status{font-size:11px;font-weight:750;border-radius:18px;padding:4px 8px}.candidate-application-status.submitted{background:#e8f2ff;color:#0d4e96}.candidate-application-status.reviewing{background:#fff4d9;color:#875500}.candidate-application-status.interview{background:#f1ebff;color:#7044bb}.candidate-application-status.offered{background:#e6f8ee;color:#127047}.candidate-application-status.rejected,.candidate-application-status.withdrawn{background:#fff0f0;color:#b52a2a}.candidate-empty{padding:34px 16px;text-align:center;border:1px dashed #cbd8e7;border-radius:13px;color:#718095}.candidate-empty i{display:block;font-size:30px;color:#9ab5d5;margin-bottom:8px}.candidate-empty h3{margin:0;font-size:14px;color:#405166}.candidate-empty p{margin:5px 0 0;font-size:12px}.candidate-empty a{display:inline-block;margin-top:12px;color:#0d4e96;font-size:12px;font-weight:750;text-decoration:none}.candidate-approved-label{display:flex;align-items:center;gap:5px;color:#137344;font-weight:750;font-size:12px;margin-top:7px}.candidate-approved-label i{font-size:16px;color:#1687e8}@media(max-width:900px){.candidate-layout{grid-template-columns:1fr}.candidate-side{position:static;order:-1}.candidate-hero{padding:22px}.candidate-completion-card{display:grid;grid-template-columns:1fr auto;column-gap:18px}.candidate-completion-card .candidate-progress,.candidate-completion-card .candidate-checklist,.candidate-completion-card .candidate-submit-approval,.candidate-completion-card .candidate-approval-note{grid-column:1/-1}}@media(max-width:640px){.candidate-profile-page{padding-top:20px}.candidate-profile-shell{padding:0 14px}.candidate-hero{padding:20px;align-items:flex-start}.candidate-avatar-wrap{flex-basis:62px}.candidate-avatar{width:62px;height:62px;font-size:23px}.candidate-hero-main h1{font-size:18px}.candidate-hero-main p{font-size:12px}.candidate-form-grid{grid-template-columns:1fr}.candidate-field.full{grid-column:auto}.candidate-section-body{padding:16px}.candidate-section-head{padding:15px 16px}.candidate-application{align-items:flex-start;flex-wrap:wrap}.candidate-application-right{align-items:flex-start;width:100%;padding-left:60px}.candidate-applications{padding:16px}.candidate-completion-card{display:block}}
.candidate-file-upload{border:1.5px dashed #a9c8e7;background:#f8fbff;border-radius:11px;padding:13px;display:flex;align-items:center;gap:11px;position:relative}.candidate-file-upload i{font-size:23px;color:#0d4e96}.candidate-file-upload strong{font-size:12px;color:#314256;display:block}.candidate-file-upload small{display:block;margin-top:3px}.candidate-file-upload input{position:absolute;inset:0;opacity:0;cursor:pointer}.candidate-file-current{font-size:12px;color:#167047;text-decoration:none;margin-top:7px;display:inline-flex;align-items:center;gap:4px}.candidate-repeat-list{display:grid;gap:12px}.candidate-repeat-item{border:1px solid #e1e9f2;border-radius:12px;padding:15px;background:#fbfdff;position:relative}.candidate-repeat-heading{display:flex;align-items:center;justify-content:space-between;margin-bottom:13px}.candidate-repeat-heading strong{font-size:13px;color:#33455b}.candidate-repeat-remove{border:0;background:#fff0f0;color:#c23a3a;border-radius:7px;padding:5px 8px;cursor:pointer;font:inherit;font-size:11px;font-weight:700}.candidate-repeat-add{margin-top:12px;border:1.5px dashed #9dbfdf;background:#f8fbff;border-radius:9px;color:#0d4e96;font:inherit;font-size:12px;font-weight:750;padding:9px 12px;cursor:pointer;display:inline-flex;align-items:center;gap:5px}.candidate-section-card.search-open{position:relative;z-index:60;overflow:visible}.candidate-search-select{position:relative;width:100%;min-width:0}.candidate-search-select select{display:none}.candidate-search-toggle{height:42px;border:1.5px solid #dce5ef;border-radius:10px;padding:0 12px;background:#fff;color:#1e293b;font:inherit;font-size:13px;display:flex;align-items:center;justify-content:space-between;gap:8px;width:100%;text-align:left;cursor:pointer}.candidate-search-toggle.placeholder{color:#8793a4}.candidate-search-toggle:focus{outline:0;border-color:#176dc8;box-shadow:0 0 0 3px rgba(23,109,200,.1)}.candidate-search-panel{display:none;box-sizing:border-box;position:absolute;z-index:80;right:0;left:auto;top:calc(100% + 6px);width:320px;max-width:calc(100vw - 48px);border:1px solid #d8e4f0;background:#fff;border-radius:12px;box-shadow:0 12px 32px rgba(20,52,84,.18);padding:10px 0;overflow:hidden}.candidate-search-select.open .candidate-search-panel{display:block}.candidate-search-input{box-sizing:border-box;width:calc(100% - 24px)!important;margin:0 12px;border:0!important;background:#f3f6fa!important;border-radius:8px!important;padding:9px 10px!important;font-size:12px!important;box-shadow:none!important}.candidate-search-options{max-height:216px;overflow:auto;margin:8px 0 0;padding:0 6px}.candidate-search-option{width:100%;border:0;background:transparent;border-radius:7px;padding:9px 8px;text-align:left;display:flex;align-items:center;gap:8px;color:#334155;font:inherit;font-size:13px;cursor:pointer}.candidate-search-option:hover,.candidate-search-option.selected{background:#e8f1ff;color:#0d4e96;font-weight:700}.candidate-search-option i{color:#4286ca;font-size:16px}.candidate-search-empty{padding:11px 14px;color:#8793a4;font-size:12px}.candidate-search-select.open{z-index:41}@media(max-width:640px){.candidate-search-panel{right:auto;left:0;width:100%;max-width:none}}
.candidate-field.has-error input,.candidate-field.has-error select,.candidate-field.has-error textarea,.candidate-field.has-error .candidate-search-toggle,.candidate-field.has-error .candidate-file-upload{border-color:#e53935!important;box-shadow:0 0 0 3px rgba(229,57,53,.12)!important;background:#fffafa}.candidate-field.has-error label{color:#b42318}.candidate-field.has-error .candidate-error-text{display:block}.candidate-error-text{display:none;margin-top:2px;color:#d92d20;font-size:11px;font-weight:700}
</style>

<main class="candidate-profile-page">
  <div class="candidate-profile-shell">
    <div class="candidate-breadcrumb"><a href="<?php echo XC_URL; ?>">Trang chủ</a><i class="ti ti-chevron-right"></i><span>Quản lý hồ sơ ứng viên</span></div>

    <section class="candidate-hero">
      <div class="candidate-avatar-wrap">
        <div class="candidate-avatar">
          <?php if($avatarUrl !== ''): ?><img src="<?php echo $candidateProfileH($avatarUrl); ?>" alt="<?php echo $candidateProfileH($fullName); ?>">
          <?php else: ?><?php echo $candidateProfileH($initial); ?><?php endif; ?>
        </div>
        <?php if($approved): ?><span class="candidate-avatar-check" title="Hồ sơ đã được phê duyệt"><i class="ti ti-check"></i></span><?php endif; ?>
      </div>
      <div class="candidate-hero-main">
        <h1><?php echo $candidateProfileH($fullName); ?></h1>
        <p><?php echo $candidateProfileH($candidate->desired_position ?? 'Hoàn thiện hồ sơ để nhận được việc làm phù hợp'); ?></p>
        <span class="candidate-status <?php echo $statusClass; ?>"><i class="ti <?php echo $approved ? 'ti-circle-check' : ($pendingApproval ? 'ti-clock-hour-4' : 'ti-file-pencil'); ?>"></i><?php echo $statusText; ?></span>
        <?php if($approved): ?><div class="candidate-approved-label"><i class="ti ti-rosette-discount-check"></i> Hồ sơ đã được phê duyệt</div><?php endif; ?>
      </div>
    </section>

    <div class="candidate-layout">
      <form class="candidate-profile-form" id="candidateProfileForm" enctype="multipart/form-data" data-has-avatar="<?php echo $avatarUrl !== '' ? '1' : '0'; ?>" data-has-cv="<?php echo $cvUrl !== '' ? '1' : '0'; ?>" novalidate>
        <div class="candidate-message" id="candidateProfileMessage" role="alert"></div>
        <section class="candidate-section-card">
          <div class="candidate-section-head"><i class="ti ti-user-circle"></i><div><h2>Thông tin cá nhân</h2><p>Thông tin giúp nhà tuyển dụng nhận diện và liên hệ với bạn.</p></div></div>
          <div class="candidate-section-body"><div class="candidate-form-grid">
            <div class="candidate-field full"><label>Ảnh đại diện <span class="required">*</span></label><div class="candidate-file-upload"><i class="ti ti-photo-up"></i><div><strong>Tải ảnh JPG, PNG hoặc WEBP</strong><small>Tối đa 2 MB. Ảnh rõ khuôn mặt giúp hồ sơ đáng tin cậy hơn.</small></div><input type="file" name="avatar_file" accept="image/jpeg,image/png,image/webp"></div></div>
            <div class="candidate-field"><label>Họ và tên <span class="required">*</span></label><input name="full_name" value="<?php echo $candidateProfileH($candidate->full_name ?? ''); ?>" required></div>
            <div class="candidate-field"><label>Email <span class="required">*</span></label><input type="email" name="email" value="<?php echo $candidateProfileH($candidateUser->user_email ?? ''); ?>" required></div>
            <div class="candidate-field"><label>Số điện thoại <span class="required">*</span></label><input name="phone" inputmode="tel" value="<?php echo $candidateProfileH($candidate->phone ?? $candidateUser->user_phone ?? ''); ?>" required></div>
            <div class="candidate-field"><label>Ngày sinh <span class="required">*</span></label><input type="date" name="date_of_birth" value="<?php echo $candidateProfileH($candidate->date_of_birth ?? ''); ?>" required></div>
            <div class="candidate-field"><label>Giới tính <span class="required">*</span></label><select name="gender" required><option value="">Chọn giới tính</option><option value="male" <?php echo ($candidate->gender ?? '') === 'male' ? 'selected' : ''; ?>>Nam</option><option value="female" <?php echo ($candidate->gender ?? '') === 'female' ? 'selected' : ''; ?>>Nữ</option><option value="other" <?php echo ($candidate->gender ?? '') === 'other' ? 'selected' : ''; ?>>Khác</option></select></div>
            <div class="candidate-field"><label>Tỉnh/Thành phố <span class="required">*</span></label><div class="candidate-search-select"><select name="province_id" data-searchable="Tìm tỉnh/thành phố" required><option value="">Chọn tỉnh/thành</option><?php foreach($provinces as $province): ?><option value="<?php echo (int)$province->id; ?>" <?php echo (int)($candidate->province_id ?? 0) === (int)$province->id ? 'selected' : ''; ?>><?php echo $candidateProfileH($province->province_name); ?></option><?php endforeach; ?></select></div></div>
            <div class="candidate-field full"><label>Địa chỉ hiện tại <span class="required">*</span></label><input name="address_detail" value="<?php echo $candidateProfileH($candidate->address_detail ?? ''); ?>" placeholder="Số nhà, đường, phường/xã" required></div>
          </div></div>
        </section>

        <section class="candidate-section-card">
          <div class="candidate-section-head"><i class="ti ti-school"></i><div><h2>Hồ sơ chuyên môn</h2><p>Chia sẻ trình độ, định hướng và những kỹ năng bạn có.</p></div></div>
          <div class="candidate-section-body"><div class="candidate-form-grid">
            <div class="candidate-field"><label>Bằng cấp <span class="required">*</span></label><select name="degree" required><option value="">Chọn trình độ</option><?php foreach($degrees as $value => $label): ?><option value="<?php echo $value; ?>" <?php echo ($candidate->degree ?? '') === $value ? 'selected' : ''; ?>><?php echo $label; ?></option><?php endforeach; ?></select></div>
            <div class="candidate-field"><label>Chuyên ngành <span class="required">*</span></label><div class="candidate-search-select"><select name="major" data-searchable="Tìm ngành nghề" required><option value="">Chọn ngành nghề</option><?php foreach($categories as $category): ?><option value="<?php echo (int)$category->id; ?>" <?php echo (int)($candidate->major ?? 0) === (int)$category->id ? 'selected' : ''; ?>><?php echo $candidateProfileH($category->job_category_name); ?></option><?php endforeach; ?></select></div></div>
            <div class="candidate-field"><label>Năm tốt nghiệp <span class="required">*</span></label><input type="number" name="graduation_year" min="1950" max="2100" value="<?php echo $candidateProfileH($candidate->graduation_year ?? ''); ?>" placeholder="VD: 2026" required></div>
            <div class="candidate-field"><label>Trường đào tạo <span class="required">*</span></label><input name="school_name" value="<?php echo $candidateProfileH($candidate->school_name ?? ''); ?>" placeholder="Tên trường/cơ sở đào tạo" required></div>
            <div class="candidate-field full"><label>Kỹ năng mềm <span class="required">*</span></label><input name="soft_skills" value="<?php echo $candidateProfileH($skillsText); ?>" placeholder="VD: Ngoại ngữ, làm việc nhóm, giao tiếp, Excel" required><small>Ngăn cách các kỹ năng bằng dấu phẩy.</small></div>
            <div class="candidate-field"><label>Mục tiêu ngắn hạn <span class="required">*</span></label><textarea name="career_goal_short" placeholder="Mục tiêu trong 6–12 tháng" required><?php echo $candidateProfileH($shortGoal); ?></textarea></div>
            <div class="candidate-field"><label>Mục tiêu dài hạn <span class="required">*</span></label><textarea name="career_goal_long" placeholder="Định hướng nghề nghiệp trong các năm tới" required><?php echo $candidateProfileH($longGoal); ?></textarea></div>
          </div></div>
        </section>

        <section class="candidate-section-card">
          <div class="candidate-section-head"><i class="ti ti-building-briefcase"></i><div><h2>Kinh nghiệm làm việc</h2><p>Thêm kinh nghiệm tại công ty cũ nếu có.</p></div></div>
          <div class="candidate-section-body"><div class="candidate-repeat-list" id="candidateExperiences">
            <?php foreach($experiences as $experience): ?><div class="candidate-repeat-item"><div class="candidate-repeat-heading"><strong>Kinh nghiệm làm việc</strong><button type="button" class="candidate-repeat-remove"><i class="ti ti-trash"></i> Xóa</button></div><div class="candidate-form-grid"><div class="candidate-field"><label>Tên công ty cũ</label><input name="experience_company[]" value="<?php echo $candidateProfileH($experience->company_name); ?>"></div><div class="candidate-field"><label>Vị trí</label><input name="experience_position[]" value="<?php echo $candidateProfileH($experience->position); ?>"></div><div class="candidate-field"><label>Thời gian bắt đầu</label><input type="date" name="experience_start[]" value="<?php echo $candidateProfileH($experience->start_date); ?>"></div><div class="candidate-field"><label>Thời gian kết thúc</label><input type="date" name="experience_end[]" value="<?php echo $candidateProfileH($experience->end_date); ?>"><small>Để trống nếu đang làm việc.</small></div><div class="candidate-field full"><label>Mô tả nhiệm vụ chính</label><textarea name="experience_description[]"><?php echo $candidateProfileH($experience->description); ?></textarea></div></div></div><?php endforeach; ?>
          </div><button type="button" class="candidate-repeat-add" data-add-repeat="experience"><i class="ti ti-plus"></i>Thêm kinh nghiệm</button></div>
        </section>

        <section class="candidate-section-card">
          <div class="candidate-section-head"><i class="ti ti-certificate"></i><div><h2>Chứng chỉ</h2><p>Khai báo các chứng chỉ ngắn hạn, sơ cấp hoặc bằng cấp bổ sung.</p></div></div>
          <div class="candidate-section-body"><div class="candidate-repeat-list" id="candidateCertificates">
            <?php foreach($certificates as $certificate): ?><div class="candidate-repeat-item"><div class="candidate-repeat-heading"><strong>Chứng chỉ</strong><button type="button" class="candidate-repeat-remove"><i class="ti ti-trash"></i> Xóa</button></div><div class="candidate-form-grid"><div class="candidate-field"><label>Tên chứng chỉ</label><input name="certificate_name[]" value="<?php echo $candidateProfileH($certificate->cert_name); ?>"></div><div class="candidate-field"><label>Đơn vị cấp</label><input name="certificate_issuer[]" value="<?php echo $candidateProfileH($certificate->issuer); ?>"></div><div class="candidate-field"><label>Ngày cấp</label><input type="date" name="certificate_issued_date[]" value="<?php echo $candidateProfileH($certificate->issued_date); ?>"></div><div class="candidate-field"><label>Ngày hết hạn</label><input type="date" name="certificate_expiry_date[]" value="<?php echo $candidateProfileH($certificate->expiry_date); ?>"></div><div class="candidate-field full"><label>Liên kết chứng chỉ (nếu có)</label><input type="url" name="certificate_url[]" value="<?php echo $candidateProfileH($certificate->cert_url); ?>" placeholder="https://..."></div></div></div><?php endforeach; ?>
          </div><button type="button" class="candidate-repeat-add" data-add-repeat="certificate"><i class="ti ti-plus"></i>Thêm chứng chỉ</button></div>
        </section>

        <section class="candidate-section-card">
          <div class="candidate-section-head"><i class="ti ti-briefcase-2"></i><div><h2>Thông tin tuyển dụng</h2><p>Thiết lập mong muốn để hệ thống gợi ý công việc phù hợp.</p></div></div>
          <div class="candidate-section-body"><div class="candidate-form-grid">
            <div class="candidate-field"><label>Vị trí mong muốn <span class="required">*</span></label><input name="desired_position" value="<?php echo $candidateProfileH($candidate->desired_position ?? ''); ?>" placeholder="VD: Nhân viên Kế toán" required></div>
            <div class="candidate-field"><label>Mức lương mong muốn <span class="required">*</span></label><select name="desired_salary" required><option value="">Chọn mức lương</option><?php foreach($salaries as $salary): ?><option value="<?php echo (int)$salary->id; ?>" <?php echo (int)($candidate->desired_salary ?? 0) === (int)$salary->id ? 'selected' : ''; ?>><?php echo $candidateProfileH($salary->salary_name); ?></option><?php endforeach; ?></select></div>
            <div class="candidate-field"><label>Địa điểm mong muốn <span class="required">*</span></label><div class="candidate-search-select"><select name="desired_province_id" data-searchable="Tìm địa điểm làm việc" required><option value="">Chọn tỉnh/thành</option><?php foreach($provinces as $province): ?><option value="<?php echo (int)$province->id; ?>" <?php echo (int)($candidate->desired_province_id ?? 0) === (int)$province->id ? 'selected' : ''; ?>><?php echo $candidateProfileH($province->province_name); ?></option><?php endforeach; ?></select></div></div>
            <div class="candidate-field"><label>Hình thức làm việc <span class="required">*</span></label><select name="desired_work_type" required><?php foreach($workTypes as $value => $label): ?><option value="<?php echo $value; ?>" <?php echo ($candidate->desired_work_type ?? 'any') === $value ? 'selected' : ''; ?>><?php echo $label; ?></option><?php endforeach; ?></select></div>
            <div class="candidate-field full"><label>Hồ sơ/CV <span class="required">*</span></label><div class="candidate-file-upload"><i class="ti ti-file-upload"></i><div><strong>Tải CV PDF hoặc DOCX</strong><small>Dung lượng tối đa 5 MB.</small></div><input type="file" name="cv_file" accept=".pdf,.doc,.docx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"></div><?php if($cvUrl !== ''): ?><a class="candidate-file-current" href="<?php echo $candidateProfileH($cvUrl); ?>" target="_blank" rel="noopener"><i class="ti ti-file-check"></i>Xem CV đã tải lên</a><?php endif; ?></div>
            <div class="candidate-field full"><label class="candidate-switch"><input type="checkbox" name="is_seeking" value="1" <?php echo !isset($candidate->is_seeking) || (int)$candidate->is_seeking === 1 ? 'checked' : ''; ?>> Tôi đang tìm việc và muốn nhận gợi ý việc làm phù hợp</label></div>
          </div></div>
        </section>
        <div class="candidate-actions"><button type="submit" class="candidate-save" id="candidateSaveButton"><i class="ti ti-device-floppy"></i>Lưu hồ sơ</button></div>
      </form>

      <aside class="candidate-side">
        <section class="candidate-completion-card" data-approval-status="<?php echo $approvalStatus; ?>">
          <div class="candidate-completion-top"><div><div class="candidate-completion-title">Mức độ hoàn thiện</div><p class="candidate-completion-copy">Điền đầy đủ để tăng cơ hội được nhà tuyển dụng chú ý.</p></div><div class="candidate-completion-value" id="candidateCompletenessValue"><?php echo $completeness; ?>%</div></div>
          <div class="candidate-progress"><span id="candidateCompletenessBar" style="width:<?php echo $completeness; ?>%"></span></div>
          <ul class="candidate-checklist"><li data-check="personal"><i class="ti ti-circle"></i>Thông tin cá nhân</li><li data-check="professional"><i class="ti ti-circle"></i>Hồ sơ chuyên môn</li><li data-check="recruitment"><i class="ti ti-circle"></i>Thông tin tuyển dụng</li></ul>
          <button type="button" class="candidate-submit-approval" id="candidateSubmitApproval" <?php echo ($completeness === 100 && !$pendingApproval && !$approved) ? '' : 'hidden'; ?>><i class="ti ti-send"></i>Gửi phê duyệt</button>
          <div class="candidate-approval-note <?php echo $statusClass; ?>" id="candidateApprovalNote" style='color:red;'><?php echo $approved ? 'Hồ sơ đã được quản trị viên phê duyệt. Dấu tích xanh hiển thị trên ảnh đại diện.' : ($pendingApproval ? 'Hồ sơ đã gửi và đang chờ quản trị viên phê duyệt.' : 'Hoàn thiện 100% hồ sơ để gửi quản trị viên phê duyệt.'); ?></div>
        </section>
      </aside>
    </div>

    <section class="candidate-applications">
      <div class="candidate-applications-header"><div><h2>Việc làm đã ứng tuyển</h2><p>Theo dõi quá trình xử lý các hồ sơ bạn đã gửi.</p></div><span class="candidate-app-count"><?php echo count($applications); ?> việc làm</span></div>
      <?php if(count($applications) > 0): ?><div class="candidate-application-list">
        <?php foreach($applications as $application): ?>
          <?php $applicationInfo = $applicationStatus[$application->status ?? 'submitted'] ?? $applicationStatus['submitted']; $jobUrl = general::getInstance()->permalink((int)$application->job_post_id, 'job_post'); $companyInitial = mb_strtoupper(mb_substr((string)($application->company_name ?? 'V'), 0, 1, 'UTF-8'), 'UTF-8'); ?>
          <article class="candidate-application"><div class="candidate-application-logo"><?php if(!empty($application->logo_url)): ?><img src="<?php echo $candidateProfileH($application->logo_url); ?>" alt="<?php echo $candidateProfileH($application->company_name ?? ''); ?>"><?php else: ?><?php echo $candidateProfileH($companyInitial); ?><?php endif; ?></div><div class="candidate-application-main"><a class="candidate-application-title" href="<?php echo $candidateProfileH($jobUrl); ?>"><?php echo $candidateProfileH($application->title ?? 'Việc làm không còn hiển thị'); ?></a><div class="candidate-application-company"><?php echo $candidateProfileH($application->company_name ?? 'Nhà tuyển dụng'); ?></div><div class="candidate-application-meta"><?php if(!empty($application->province_name)): ?><span class="candidate-meta-tag"><i class="ti ti-map-pin"></i> <?php echo $candidateProfileH($application->province_name); ?></span><?php endif; ?><?php if(!empty($application->salary_name)): ?><span class="candidate-meta-tag"><?php echo $candidateProfileH($application->salary_name); ?></span><?php endif; ?><?php if(!empty($application->deadline)): ?><span class="candidate-meta-tag">Hạn nộp: <?php echo date('d/m/Y', strtotime($application->deadline)); ?></span><?php endif; ?></div></div><div class="candidate-application-right"><span class="candidate-application-date">Nộp ngày <?php echo !empty($application->applied_at) ? date('d/m/Y', strtotime($application->applied_at)) : ''; ?></span><span class="candidate-application-status <?php echo $applicationInfo[1]; ?>"><?php echo $applicationInfo[0]; ?></span></div></article>
        <?php endforeach; ?>
      </div><?php else: ?><div class="candidate-empty"><i class="ti ti-briefcase-off"></i><h3>Bạn chưa ứng tuyển việc làm nào</h3><p>Khám phá các cơ hội phù hợp và gửi hồ sơ ngay khi sẵn sàng.</p><a href="<?php echo XC_URL; ?>/quan-ly-viec-lam.html">Tìm việc làm phù hợp <i class="ti ti-arrow-right"></i></a></div><?php endif; ?>
    </section>
  </div>
</main>

<template id="candidateExperienceTemplate"><div class="candidate-repeat-item"><div class="candidate-repeat-heading"><strong>Kinh nghiệm làm việc</strong><button type="button" class="candidate-repeat-remove"><i class="ti ti-trash"></i> Xóa</button></div><div class="candidate-form-grid"><div class="candidate-field"><label>Tên công ty cũ</label><input name="experience_company[]"></div><div class="candidate-field"><label>Vị trí</label><input name="experience_position[]"></div><div class="candidate-field"><label>Thời gian bắt đầu</label><input type="date" name="experience_start[]"></div><div class="candidate-field"><label>Thời gian kết thúc</label><input type="date" name="experience_end[]"><small>Để trống nếu đang làm việc.</small></div><div class="candidate-field full"><label>Mô tả nhiệm vụ chính</label><textarea name="experience_description[]"></textarea></div></div></div></template>
<template id="candidateCertificateTemplate"><div class="candidate-repeat-item"><div class="candidate-repeat-heading"><strong>Chứng chỉ</strong><button type="button" class="candidate-repeat-remove"><i class="ti ti-trash"></i> Xóa</button></div><div class="candidate-form-grid"><div class="candidate-field"><label>Tên chứng chỉ</label><input name="certificate_name[]"></div><div class="candidate-field"><label>Đơn vị cấp</label><input name="certificate_issuer[]"></div><div class="candidate-field"><label>Ngày cấp</label><input type="date" name="certificate_issued_date[]"></div><div class="candidate-field"><label>Ngày hết hạn</label><input type="date" name="certificate_expiry_date[]"></div><div class="candidate-field full"><label>Liên kết chứng chỉ (nếu có)</label><input type="url" name="certificate_url[]" placeholder="https://..."></div></div></div></template>

<script>
(function(){
  var form = document.getElementById('candidateProfileForm');
  var message = document.getElementById('candidateProfileMessage');
  var saveButton = document.getElementById('candidateSaveButton');
  var approveButton = document.getElementById('candidateSubmitApproval');
  var value = document.getElementById('candidateCompletenessValue');
  var bar = document.getElementById('candidateCompletenessBar');
  if(!form) return;
  var groups = {
    personal: ['full_name','email','phone','date_of_birth','gender','avatar_file','province_id','address_detail'],
    professional: ['degree','major','graduation_year','school_name','soft_skills','career_goal_short','career_goal_long'],
    recruitment: ['desired_position','desired_salary','desired_province_id','desired_work_type','cv_file']
  };
  function filled(name){ var field = form.elements[name]; if(!field) return false; if(field.type === 'file'){ return field.files.length > 0 || (name === 'avatar_file' ? form.getAttribute('data-has-avatar') === '1' : form.getAttribute('data-has-cv') === '1'); } if(field.type === 'number') return Number(field.value) > 0; return String(field.value || '').trim() !== ''; }
  function updateCompleteness(){
    var total = 0, done = 0;
    Object.keys(groups).forEach(function(group){ var allDone = groups[group].every(filled); groups[group].forEach(function(name){ total++; if(filled(name)) done++; }); var item = document.querySelector('[data-check="' + group + '"]'); if(item){ item.classList.toggle('done', allDone); item.querySelector('i').className = allDone ? 'ti ti-circle-check' : 'ti ti-circle'; } });
    var percent = Math.round((done / total) * 100);
    if(value) value.textContent = percent + '%'; if(bar) bar.style.width = percent + '%';
    var status = Number(document.querySelector('.candidate-completion-card').getAttribute('data-approval-status') || 0);
    if(approveButton) approveButton.hidden = !(percent === 100 && status !== 1 && status !== 2);
  }
  Array.prototype.slice.call(form.querySelectorAll('input,select,textarea')).forEach(function(field){ field.addEventListener('input', updateCompleteness); field.addEventListener('change', updateCompleteness); });
  function addRepeat(type){ var target = document.getElementById(type === 'experience' ? 'candidateExperiences' : 'candidateCertificates'); var template = document.getElementById(type === 'experience' ? 'candidateExperienceTemplate' : 'candidateCertificateTemplate'); if(target && template){ target.appendChild(template.content.cloneNode(true)); } }
  document.addEventListener('click', function(event){ var add = event.target.closest('[data-add-repeat]'); if(add){ addRepeat(add.getAttribute('data-add-repeat')); return; } var remove = event.target.closest('.candidate-repeat-remove'); if(remove){ var item = remove.closest('.candidate-repeat-item'); if(item) item.remove(); } });
  function enhanceSearchableSelect(select){
    var wrapper = select.parentElement; if(!wrapper || wrapper.getAttribute('data-enhanced')) return; wrapper.setAttribute('data-enhanced','1');
    var placeholder = select.getAttribute('data-searchable') || 'Tìm kiếm'; var toggle = document.createElement('button'); toggle.type = 'button'; toggle.className = 'candidate-search-toggle';
    var panel = document.createElement('div'); panel.className = 'candidate-search-panel'; panel.innerHTML = '<input class="candidate-search-input" type="search" placeholder="' + placeholder.replace(/"/g, '&quot;') + '"><div class="candidate-search-options"></div>';
    wrapper.appendChild(toggle); wrapper.appendChild(panel); var input = panel.querySelector('input'); var list = panel.querySelector('.candidate-search-options');
    function selectedLabel(){ var option = select.options[select.selectedIndex]; return option && option.value ? option.textContent : (select.options[0] ? select.options[0].textContent : placeholder); }
    function render(query){ var text = String(query || '').toLocaleLowerCase('vi'); list.innerHTML = ''; var matched = 0; Array.prototype.slice.call(select.options).forEach(function(option){ if(!option.value || option.textContent.toLocaleLowerCase('vi').indexOf(text) === -1) return; matched++; var item = document.createElement('button'); item.type='button'; item.className='candidate-search-option' + (option.selected ? ' selected' : ''); item.innerHTML='<i class="ti ti-map-pin"></i><span></span>'; item.querySelector('span').textContent=option.textContent; item.addEventListener('click', function(){ select.value=option.value; select.dispatchEvent(new Event('change', {bubbles:true})); toggle.textContent=selectedLabel(); toggle.classList.remove('placeholder'); wrapper.classList.remove('open'); }); list.appendChild(item); }); if(!matched){ var empty=document.createElement('div'); empty.className='candidate-search-empty'; empty.textContent='Không tìm thấy kết quả phù hợp.'; list.appendChild(empty); } }
    var card = wrapper.closest('.candidate-section-card');
    toggle.textContent=selectedLabel(); toggle.classList.toggle('placeholder', !select.value); toggle.addEventListener('click', function(){ var opened=wrapper.classList.toggle('open'); if(card) card.classList.toggle('search-open', opened); if(opened){ input.value=''; render(''); window.setTimeout(function(){ input.focus(); },20); } }); input.addEventListener('input', function(){ render(input.value); }); select.addEventListener('change', function(){ toggle.textContent=selectedLabel(); toggle.classList.toggle('placeholder', !select.value); if(card) card.classList.remove('search-open'); }); render('');
  }
  Array.prototype.slice.call(document.querySelectorAll('select[data-searchable]')).forEach(enhanceSearchableSelect);
  document.addEventListener('click', function(event){ Array.prototype.slice.call(document.querySelectorAll('.candidate-search-select.open')).forEach(function(wrapper){ if(!wrapper.contains(event.target)){ wrapper.classList.remove('open'); var card = wrapper.closest('.candidate-section-card'); if(card) card.classList.remove('search-open'); } }); });
  updateCompleteness();
  function showMessage(text, type){ if(!message) return; message.textContent = text; message.className = 'candidate-message ' + type; }
  function showCandidateToast(icon, title, timer){
    if(window.Swal && typeof Swal.fire === 'function'){
      Swal.fire({
        toast: true,
        // position: 'bottom-end',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: timer || 1200,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer);
          toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
      });
    }else{
      showMessage(title, icon === 'success' ? 'success' : 'error');
    }
  }
  function candidateFieldWrap(field){ return field ? field.closest('.candidate-field') : null; }
  function setCandidateFieldError(field, hasError){
    var wrap = candidateFieldWrap(field);
    if(!wrap) return;
    wrap.classList.toggle('has-error', !!hasError);
    var errorText = wrap.querySelector('.candidate-error-text');
    if(hasError && !errorText){
      errorText = document.createElement('small');
      errorText.className = 'candidate-error-text';
      errorText.textContent = 'Vui lòng nhập thông tin này.';
      wrap.appendChild(errorText);
    }
  }
  function clearCandidateFieldError(field){
    if(field && field.name && filled(field.name)){
      setCandidateFieldError(field, false);
    }
  }
  function validateCandidateRequiredFields(){
    var firstMissing = null;
    Array.prototype.slice.call(form.querySelectorAll('[required]')).forEach(function(field){
      var isMissing = !filled(field.name);
      setCandidateFieldError(field, isMissing);
      if(isMissing && !firstMissing){ firstMissing = field; }
    });
    if(firstMissing){
      var wrap = candidateFieldWrap(firstMissing);
      if(wrap){ wrap.scrollIntoView({behavior:'smooth', block:'center'}); }
      var focusTarget = wrap ? (wrap.querySelector('.candidate-search-toggle') || (firstMissing.type === 'file' ? wrap.querySelector('.candidate-file-upload') : firstMissing)) : firstMissing;
      if(focusTarget && typeof focusTarget.focus === 'function'){
        window.setTimeout(function(){ try{ focusTarget.focus({preventScroll:true}); }catch(e){ focusTarget.focus(); } }, 260);
      }
      return false;
    }
    return true;
  }
  form.addEventListener('input', function(event){ clearCandidateFieldError(event.target); });
  form.addEventListener('change', function(event){ clearCandidateFieldError(event.target); });
  form.addEventListener('submit', function(event){
    event.preventDefault();
    event.stopImmediatePropagation();
    if(!validateCandidateRequiredFields()){
      showMessage('Lưu thất bại, Bạn chưa điền đầy đủ thông tin.', 'error');
      showCandidateToast('error', 'Lưu thất bại, Bạn chưa điền đầy đủ thông tin.', 1200);
      return;
    }
    if(saveButton) saveButton.disabled = true;
    showMessage('', '');
    fetch('<?php echo XC_URL; ?>/api/candidateProfileSave', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body:new FormData(form) })
      .then(function(response){ return response.json(); })
      .then(function(data){
        if(!data || Number(data.status) !== 200){ throw new Error((data && data.message) || 'Lưu thất bại'); }
        showMessage(data.message, 'success');
        showCandidateToast('success', data.message, 1200);
        window.setTimeout(function(){ window.location.reload(); }, 1250);
      })
      .catch(function(error){
        var errorMessage = error && error.message ? error.message : 'Lưu thất bại';
        showMessage(errorMessage, 'error');
        showCandidateToast('error', errorMessage, 1600);
      })
      .finally(function(){ if(saveButton) saveButton.disabled = false; });
  });
  form.addEventListener('submit', function(event){ event.preventDefault(); var requiredFields = Array.prototype.slice.call(form.querySelectorAll('[required]')); var missing = requiredFields.some(function(field){ return !filled(field.name); }); if(missing){ showMessage('Vui lòng điền đầy đủ các trường bắt buộc trước khi lưu hồ sơ.', 'error'); return; } if(saveButton) saveButton.disabled = true; showMessage('', '');
    fetch('<?php echo XC_URL; ?>/api/candidateProfileSave', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body:new FormData(form) })
      .then(function(response){ return response.json(); }).then(function(result){ if(!result || Number(result.status) !== 200) throw new Error((result && result.message) || 'Không thể lưu hồ sơ.'); showMessage(result.message, 'success'); window.setTimeout(function(){ window.location.reload(); }, 650); })
      .catch(function(error){ showMessage(error.message, 'error'); }).finally(function(){ if(saveButton) saveButton.disabled = false; });
  });
  if(approveButton){ approveButton.addEventListener('click', function(){ approveButton.disabled = true; fetch('<?php echo XC_URL; ?>/api/candidateProfileSubmitApproval', { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8','X-Requested-With':'XMLHttpRequest'} })
      .then(function(response){ return response.json(); }).then(function(result){ if(!result || Number(result.status) !== 200) throw new Error((result && result.message) || 'Không thể gửi phê duyệt.'); showMessage(result.message, 'success'); window.setTimeout(function(){ window.location.reload(); }, 650); })
      .catch(function(error){ showMessage(error.message, 'error'); approveButton.disabled = false; }); }); }
})();
</script>

<?php require 'footer.php'; ?>
