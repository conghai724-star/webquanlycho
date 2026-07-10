<?php
require 'header.php';

$jobDetail = isset($job_detail) && is_object($job_detail) ? $job_detail : (object)array();
$relatedJobs = isset($related_jobs) && is_array($related_jobs) ? $related_jobs : array();
$featuredJobs = isset($featured_jobs) && is_array($featured_jobs) ? $featured_jobs : array();
$jobCanApply = !empty($job_can_apply);
$jobIsApplied = !empty($job_is_applied);
$jobApplyMessage = isset($job_apply_message) ? trim((string)$job_apply_message) : '';
$jobApplyMessageType = isset($job_apply_message_type) ? trim((string)$job_apply_message_type) : '';
$jobDeadlineExpired = !empty($job_deadline_expired);
$jobCandidateProfile = isset($job_candidate_profile) && is_object($job_candidate_profile) ? $job_candidate_profile : null;
$jobUrl = general::getInstance()->permalink((int)($jobDetail->id ?? 0), 'job_post');
$siteScriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', (string)$_SERVER['SCRIPT_NAME']) : '';
$siteBasePath = rtrim(dirname($siteScriptName), '/');
if($siteBasePath === '/' || $siteBasePath === '.'){
  $siteBasePath = '';
}
$jobApiBaseUrl = $siteBasePath.'/api';

function jobDetailH($value){
  return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function jobDetailAsset($value){
  $value = trim((string)$value);
  if($value === ''){
    return '';
  }
  if(preg_match('#^(https?:)?//#i', $value)){
    return $value;
  }
  return XC_URL.'/'.ltrim($value, '/');
}

function jobDetailTextLines($value, $fallback = ''){
  $value = trim((string)$value);
  if($value === ''){
    $value = $fallback;
  }
  if($value === ''){
    return array();
  }
  $value = str_replace(array("\r\n", "\r"), "\n", strip_tags($value));
  $parts = preg_split('/\n+/', $value);
  $items = array();
  foreach($parts as $part){
    $part = trim($part, " \t\n\r\0\x0B-•");
    if($part !== ''){
      $items[] = $part;
    }
  }
  return $items;
}

function jobDetailDate($value, $fallback = 'Đang cập nhật'){
  $time = $value ? strtotime((string)$value) : false;
  return $time ? date('d/m/Y', $time) : $fallback;
}

function jobDetailDeadlineLabel($value){
  $time = $value ? strtotime((string)$value.' 23:59:59') : false;
  if(!$time){
    return 'Hạn cuối nộp hồ sơ: Đang cập nhật';
  }
  if($time < time()){
    return 'Hết hạn nộp hồ sơ';
  }
  return 'Hạn cuối nộp hồ sơ: '.date('d/m/Y', $time);
}

function jobDetailInitials($text){
  $text = trim((string)$text);
  if($text === ''){
    return 'VL';
  }
  $words = preg_split('/\s+/u', $text);
  $initials = '';
  foreach($words as $word){
    if($word === ''){
      continue;
    }
    $initials .= mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8');
    if(mb_strlen($initials, 'UTF-8') >= 2){
      break;
    }
  }
  return $initials !== '' ? $initials : 'VL';
}

$jobTitle = trim((string)($jobDetail->title ?? 'Chi tiết tuyển dụng'));
$companyName = trim((string)($jobDetail->company_name ?? 'Nhà tuyển dụng'));
$companyLogo = jobDetailAsset($jobDetail->logo_url ?? '');
$salaryName = trim((string)($jobDetail->salary_name ?? 'Thỏa thuận'));
$vacancies = trim((string)($jobDetail->vacancies ?? $jobDetail->quantity ?? 'Đang cập nhật'));
$provinceName = trim((string)($jobDetail->province_name ?? 'Đang cập nhật'));
$workType = trim((string)($jobDetail->work_type ?? ''));
$workTypeLabelMap = array(
  'full_time' => 'Full-time',
  'part_time' => 'Part-time',
  'remote' => 'Remote',
  'hybrid' => 'Hybrid',
  'internship' => 'Thực tập',
  'contract' => 'Hợp đồng'
);
$workTypeLabel = isset($workTypeLabelMap[$workType]) ? $workTypeLabelMap[$workType] : ($workType !== '' ? $workType : 'Đang cập nhật');
$companyAddress = trim((string)($jobDetail->company_address ?? 'Đang cập nhật'));
$companySize = trim((string)($jobDetail->company_size ?? 'Đang cập nhật'));
$websiteUrl = trim((string)($jobDetail->website_url ?? ''));
$jobCategoryName = trim((string)($jobDetail->job_category_name ?? 'Đang cập nhật'));
$jobDescriptionItems = jobDetailTextLines($jobDetail->description ?? $jobDetail->responsibilities ?? '', 'Nội dung công việc đang được cập nhật.');
$jobRequirementItems = jobDetailTextLines($jobDetail->requirements ?? '', 'Yêu cầu ứng viên đang được cập nhật.');
$jobBenefitItems = jobDetailTextLines($jobDetail->benefits ?? '', 'Quyền lợi đang được cập nhật.');
$jobShareUrl = $jobUrl;
?>

<main class="job-detail-page">
<div class="jd-container">

<div class="jd-breadcrumb">
<a href="<?php echo XC_URL; ?>">Trang chủ</a>
<span>/</span>
<a href="<?php echo XC_URL; ?>/quan-ly-viec-lam.html">Việc làm</a>
<span>/</span>
<span>Chi tiết tuyển dụng</span>
</div>

<section class="jd-hero">
<div class="jd-hero-main">
<div class="jd-logo">
  <?php if($companyLogo !== ''){ ?>
  <img src="<?php echo jobDetailH($companyLogo); ?>" alt="<?php echo jobDetailH($companyName); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:16px">
  <?php }else{ ?>
  <?php echo jobDetailH(jobDetailInitials($companyName)); ?>
  <?php } ?>
</div>

<div class="jd-title-wrap">
<h1><?php echo jobDetailH($jobTitle); ?></h1>
<div class="jd-company-name">
<i class="ti ti-building"></i> <?php echo jobDetailH($companyName); ?>
</div>

<div class="jd-tags">
<span class="jd-tag"><?php echo jobDetailH($salaryName); ?></span>
<span class="jd-tag">Tuyển <?php echo jobDetailH($vacancies); ?></span>
<span class="jd-tag"><?php echo jobDetailH($provinceName); ?></span>
<span class="jd-tag"><?php echo jobDetailH($workTypeLabel); ?></span>
</div>
</div>
</div>

<div class="jd-actions">
  <?php if($jobCanApply){ ?>
  <button class="jd-btn jd-btn-primary" type="button" id="jobApplyBtn" data-job-id="<?php echo intval($jobDetail->id ?? 0); ?>">
  <i class="ti ti-send"></i> Ứng tuyển ngay
  </button>
  <?php }elseif($jobIsApplied){ ?>
  <button class="jd-btn jd-btn-primary" type="button" disabled>
  <i class="ti ti-circle-check"></i> Đã ứng tuyển
  </button>
  <?php } ?>

  <button class="jd-btn jd-btn-outline" type="button" onclick="window.location.href='<?php echo jobDetailH(XC_URL.'/quan-ly-viec-lam.html'); ?>'">
  <i class="ti ti-arrow-left"></i> Quay lại danh sách
  </button>
</div>
<?php if($jobApplyMessage !== ''){ ?>
<div class="jd-highlight" style="margin-top:16px;color:<?php echo $jobApplyMessageType === 'success' ? '#15803d' : '#b42318'; ?>;background:<?php echo $jobApplyMessageType === 'success' ? '#eefbf3' : '#fff1f2'; ?>;border-color:<?php echo $jobApplyMessageType === 'success' ? '#b7ebc6' : '#fecdd3'; ?>;">
  <?php echo jobDetailH($jobApplyMessage); ?>
</div>
<?php } ?>
</section>

<div class="jd-layout">

<div class="jd-main">

<section class="jd-card">
<h2 class="jd-section-title">
<i class="ti ti-building"></i> Thông tin doanh nghiệp
</h2>

<div class="jd-company-head">
<div class="jd-company-logo-small">
  <?php if($companyLogo !== ''){ ?>
  <img src="<?php echo jobDetailH($companyLogo); ?>" alt="<?php echo jobDetailH($companyName); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
  <?php }else{ ?>
  <?php echo jobDetailH(jobDetailInitials($companyName)); ?>
  <?php } ?>
</div>

<div>
<h3><?php echo jobDetailH($companyName); ?></h3>
<p>Lĩnh vực: <?php echo jobDetailH($jobCategoryName); ?></p>
<div class="jd-verified">
<i class="ti ti-circle-check"></i>
<?php echo intval($jobDetail->verified_status ?? 0) === 1 ? 'Đã liên kết với Trường Cao đẳng Kon Tum' : 'Thông tin doanh nghiệp đã được xác minh'; ?>
</div>
</div>
</div>

<div class="jd-info-grid">
<div class="jd-info-item">
<div class="jd-info-label">Địa chỉ</div>
<div class="jd-info-value"><?php echo jobDetailH($companyAddress); ?></div>
</div>

<div class="jd-info-item">
<div class="jd-info-label">Quy mô nhân sự</div>
<div class="jd-info-value"><?php echo jobDetailH($companySize); ?></div>
</div>

<div class="jd-info-item">
<div class="jd-info-label">Website</div>
<div class="jd-info-value"><?php echo $websiteUrl !== '' ? '<a href="'.jobDetailH($websiteUrl).'" target="_blank" rel="noopener">'.jobDetailH($websiteUrl).'</a>' : 'Đang cập nhật'; ?></div>
</div>

<div class="jd-info-item">
<div class="jd-info-label">Ngành nghề</div>
<div class="jd-info-value"><?php echo jobDetailH($jobCategoryName); ?></div>
</div>
</div>

<div class="jd-highlight" style="margin-top:16px">
<?php echo jobDetailH(trim((string)($jobDetail->company_description ?? 'Doanh nghiệp đang mở rộng cơ hội tuyển dụng và sẵn sàng kết nối cùng ứng viên phù hợp.'))); ?>
</div>
</section>

<section class="jd-card">
<h2 class="jd-section-title">
<i class="ti ti-briefcase"></i> Thông tin tuyển dụng
</h2>

<div class="jd-info-grid">
<div class="jd-info-item">
<div class="jd-info-label">Vị trí tuyển dụng</div>
<div class="jd-info-value"><?php echo jobDetailH($jobTitle); ?></div>
</div>

<div class="jd-info-item">
<div class="jd-info-label">Số lượng tuyển</div>
<div class="jd-info-value"><?php echo jobDetailH($vacancies); ?></div>
</div>

<div class="jd-info-item">
<div class="jd-info-label">Hình thức làm việc</div>
<div class="jd-info-value"><?php echo jobDetailH($workTypeLabel); ?></div>
</div>

<div class="jd-info-item">
<div class="jd-info-label">Hạn nộp hồ sơ</div>
<div class="jd-info-value"><?php echo jobDetailH($jobDeadlineExpired ? 'Hết hạn nộp hồ sơ' : jobDetailDate($jobDetail->deadline ?? '', 'Đang cập nhật')); ?></div>
</div>
</div>
</section>

<section class="jd-card">
<h2 class="jd-section-title">
<i class="ti ti-list-check"></i> Mô tả công việc
</h2>

<div class="jd-content">
<ul>
<?php foreach($jobDescriptionItems as $item){ ?>
<li><?php echo jobDetailH($item); ?></li>
<?php } ?>
</ul>
</div>
</section>

<section class="jd-card">
<h2 class="jd-section-title">
<i class="ti ti-user-check"></i> Yêu cầu ứng viên
</h2>

<div class="jd-content">
<ul>
<?php foreach($jobRequirementItems as $item){ ?>
<li><?php echo jobDetailH($item); ?></li>
<?php } ?>
</ul>
</div>
</section>

<section class="jd-card">
<h2 class="jd-section-title">
<i class="ti ti-gift"></i> Quyền lợi
</h2>

<div class="jd-content">
<ul>
<?php foreach($jobBenefitItems as $item){ ?>
<li><?php echo jobDetailH($item); ?></li>
<?php } ?>
</ul>
</div>
</section>

<section class="jd-card">
<h2 class="jd-section-title">
<i class="ti ti-share-3"></i> Chia sẻ bài đăng
</h2>

<div class="jd-share">
<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($jobShareUrl); ?>" target="_blank" rel="noopener"><i class="ti ti-brand-facebook"></i></a>
</div>
</section>

</div>

<div class="jd-sidebar">

<section class="jd-card">
<div class="jd-deadline">
<i class="ti ti-calendar-due"></i>
<?php echo jobDetailH(jobDetailDeadlineLabel($jobDetail->deadline ?? '')); ?>
</div>
</section>

<section class="jd-card">
<h2 class="jd-section-title">
<i class="ti ti-map-pin"></i> Địa điểm làm việc
</h2>

<div class="jd-side-list">
<div class="jd-side-item">
<i class="ti ti-building"></i>
<span><?php echo jobDetailH($companyName); ?></span>
</div>

<div class="jd-side-item">
<i class="ti ti-map-pin"></i>
<span><?php echo jobDetailH($companyAddress); ?></span>
</div>

<div class="jd-side-item">
<i class="ti ti-clock"></i>
<span><?php echo jobDetailH($workTypeLabel); ?></span>
</div>
</div>
</section>

<section class="jd-card">
<h2 class="jd-section-title">
<i class="ti ti-news"></i> Bài viết liên quan
</h2>

<div class="jd-related-list">
<?php if(count($relatedJobs) > 0){ foreach($relatedJobs as $relatedJob){
  $relatedUrl = general::getInstance()->permalink((int)$relatedJob->id, 'job_post');
?>
<a href="<?php echo jobDetailH($relatedUrl); ?>" class="jd-related-item">
<div class="jd-related-icon"><i class="ti ti-briefcase"></i></div>
<div class="jd-related-content">
<h4><?php echo jobDetailH($relatedJob->title ?? 'Việc làm liên quan'); ?></h4>
<p><?php echo jobDetailH(($relatedJob->company_name ?? 'Nhà tuyển dụng').' · '.jobDetailDeadlineLabel($relatedJob->deadline ?? '')); ?></p>
</div>
</a>
<?php }}else{ ?>
<div class="jd-highlight">Chưa có việc làm liên quan cùng ngành nghề.</div>
<?php } ?>
</div>
</section>

</div>

</div>


<section class="jd-featured">
<div class="jd-slider-top">
<h2 class="jd-section-title" style="margin-bottom:0">
<i class="ti ti-star"></i> Bài đăng nổi bật
</h2>

<div class="jd-slider-controls">
<button class="jd-slider-btn" type="button" onclick="jdFeaturedSlide(-1)">
<i class="ti ti-chevron-left"></i>
</button>
<button class="jd-slider-btn" type="button" onclick="jdFeaturedSlide(1)">
<i class="ti ti-chevron-right"></i>
</button>
</div>
</div>

<div class="jd-slider-wrap jd-featured-slider">
<div class="jd-slider-track" id="jdFeaturedTrack">
<?php if(count($featuredJobs) > 0){ foreach($featuredJobs as $featuredJob){
  $featuredUrl = general::getInstance()->permalink((int)$featuredJob->id, 'job_post');
  $featuredLogo = jobDetailAsset($featuredJob->logo_url ?? '');
  $featuredCompany = trim((string)($featuredJob->company_name ?? 'Nhà tuyển dụng'));
?>
<a href="<?php echo jobDetailH($featuredUrl); ?>" class="jd-feature-card">
<div class="jd-feature-head">
<div class="jd-feature-logo">
  <?php if($featuredLogo !== ''){ ?>
  <img src="<?php echo jobDetailH($featuredLogo); ?>" alt="<?php echo jobDetailH($featuredCompany); ?>" style="width:100%;height:100%;object-fit:cover;border-radius:14px">
  <?php }else{ ?>
  <?php echo jobDetailH(jobDetailInitials($featuredCompany)); ?>
  <?php } ?>
</div>
<div class="jd-feature-info">
<h3><?php echo jobDetailH($featuredJob->title ?? 'Việc làm mới'); ?></h3>
<p><?php echo jobDetailH($featuredCompany); ?></p>
</div>
</div>
<div class="jd-feature-tags">
<span><?php echo jobDetailH($featuredJob->salary_name ?? 'Thỏa thuận'); ?></span>
<span><?php echo jobDetailH($featuredJob->province_name ?? 'Đang cập nhật'); ?></span>
<span><?php echo jobDetailH(isset($workTypeLabelMap[$featuredJob->work_type ?? '']) ? $workTypeLabelMap[$featuredJob->work_type] : ($featuredJob->work_type ?? 'Đang tuyển')); ?></span>
</div>
<div class="jd-feature-footer">
<span class="jd-feature-deadline"><?php echo jobDetailH(jobDetailDeadlineLabel($featuredJob->deadline ?? '')); ?></span>
<span class="jd-feature-view">Xem chi tiết</span>
</div>
</a>
<?php }} ?>
</div>
</div>

<?php if(count($featuredJobs) === 0){ ?>
<div class="jd-highlight">Chưa có bài đăng nổi bật để hiển thị.</div>
<?php } ?>
<div class="jd-slider-dots" id="jdFeaturedDots"></div>
</section>

</div>
</main>

<script>
(function(){
  var current = 0;
  var timer = null;
  var delay = 5000;

  function getVisibleCount(){
    if(window.innerWidth <= 560) return 1;
    if(window.innerWidth <= 1024) return 2;
    return 3;
  }

  function getMaxSlide(){
    var track = document.getElementById('jdFeaturedTrack');
    if(!track) return 0;
    var cards = track.querySelectorAll('.jd-feature-card');
    return Math.max(cards.length - getVisibleCount(), 0);
  }

  function renderDots(){
    var dotsWrap = document.getElementById('jdFeaturedDots');
    if(!dotsWrap) return;

    var max = getMaxSlide();
    if(max <= 0){
      dotsWrap.innerHTML = '';
      return;
    }
    var html = '';
    for(var i = 0; i <= max; i++){
      html += '<button type="button" class="jd-slider-dot ' + (i === current ? 'active' : '') + '" onclick="jdFeaturedGo(' + i + ')"></button>';
    }
    dotsWrap.innerHTML = html;
  }

  function updateSlider(){
    var track = document.getElementById('jdFeaturedTrack');
    if(!track) return;
    var cards = track.querySelectorAll('.jd-feature-card');
    if(!cards.length) return;

    var max = getMaxSlide();
    if(current > max) current = 0;
    if(current < 0) current = max;

    var gap = 14;
    var cardWidth = cards[0].offsetWidth + gap;
    track.style.transition = 'transform .45s ease';
    track.style.transform = 'translateX(-' + (current * cardWidth) + 'px)';
    renderDots();
  }

  function resetAuto(){
    clearInterval(timer);
    if(getMaxSlide() <= 0) return;
    timer = setInterval(function(){
      current += 1;
      updateSlider();
    }, delay);
  }

  window.jdFeaturedSlide = function(step){
    current += step;
    updateSlider();
    resetAuto();
  };

  window.jdFeaturedGo = function(index){
    current = index;
    updateSlider();
    resetAuto();
  };

  window.addEventListener('resize', function(){
    updateSlider();
    resetAuto();
  });

  updateSlider();
  resetAuto();
})();

(function(){
  var applyButton = document.getElementById('jobApplyBtn');
  if(!applyButton) return;

  applyButton.addEventListener('click', function(){
    var jobId = applyButton.getAttribute('data-job-id') || '';
    if(!jobId) return;

    applyButton.disabled = true;
    fetch('<?php echo $jobApiBaseUrl; ?>/applyJob', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: new URLSearchParams({ job_id: jobId }).toString()
    })
      .then(function(response){
        return response.text().then(function(text){
          var result = null;
          try {
            result = text ? JSON.parse(text) : null;
          } catch (parseError) {
            throw new Error(text ? text.replace(/<[^>]*>/g, ' ').trim() : 'Hệ thống đang trả về dữ liệu không hợp lệ.');
          }
          return result;
        });
      })
      .then(function(result){
        if(result && result.requires_verification && result.return_url){
          window.location.href = result.return_url;
          return;
        }
        if(!result || Number(result.status) !== 200){
          throw new Error((result && result.message) || 'Không thể ứng tuyển công việc này.');
        }
        if(window.Swal){
          Swal.fire({
            icon: 'success',
            title: 'Ứng tuyển thành công',
            text: result.message
          }).then(function(){
            window.location.reload();
          });
        }else{
          window.alert(result.message);
          window.location.reload();
        }
      })
      .catch(function(error){
        if(window.Swal){
          Swal.fire({ icon: 'error', title: 'Không thể ứng tuyển', text: error.message });
        }else{
          window.alert(error.message);
        }
      })
      .finally(function(){
        applyButton.disabled = false;
      });
  });
})();
</script>
