<?php require "header.php"; ?>
<?php
$market_results = isset($market_results) && is_array($market_results) ? $market_results : array();
$summary = isset($market_results_summary) && is_object($market_results_summary) ? $market_results_summary : (object) array(
  'total_rounds' => 0,
  'total_companies' => 0,
  'total_positions' => 0,
  'total_profiles' => 0,
  'total_interviews' => 0
);
$page = isset($market_results_page) ? (int)$market_results_page : 1;
$totalPages = isset($market_results_total_pages) ? (int)$market_results_total_pages : 1;

if (!function_exists('marketResultImageUrl')) {
  function marketResultImageUrl($value) {
    $value = trim((string)$value);
    if ($value === '') {
      return 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=320&h=320&fit=crop';
    }
    if (preg_match('#^(https?:)?//#i', $value) || strpos($value, 'data:') === 0) {
      return $value;
    }
    return XC_URL.'/uploads/images/'.ltrim($value, '/');
  }
}

if (!function_exists('marketResultExcerpt')) {
  function marketResultExcerpt($value, $limit = 150) {
    $value = trim(strip_tags((string)$value));
    if ($value === '') { return ''; }
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
      return mb_strlen($value, 'UTF-8') > $limit ? mb_substr($value, 0, $limit, 'UTF-8').'...' : $value;
    }
    return strlen($value) > $limit ? substr($value, 0, $limit).'...' : $value;
  }
}

if (!function_exists('marketResultPageUrl')) {
  function marketResultPageUrl($targetPage) {
    return XC_URL.'/ket-qua-san-viec-lam.html?page='.(int)$targetPage;
  }
}

if (!function_exists('marketResultPaginationItems')) {
  function marketResultPaginationItems($currentPage, $totalPages) {
    $currentPage = max(1, (int)$currentPage);
    $totalPages = max(1, (int)$totalPages);
    if ($totalPages <= 7) {
      return range(1, $totalPages);
    }
    if ($currentPage <= 4) {
      return array(1, 2, 3, 4, 5, 'ellipsis', $totalPages);
    }
    if ($currentPage >= $totalPages - 3) {
      return array(1, 'ellipsis', $totalPages - 4, $totalPages - 3, $totalPages - 2, $totalPages - 1, $totalPages);
    }
    return array(1, 'ellipsis', $currentPage - 1, $currentPage, $currentPage + 1, 'ellipsis', $totalPages);
  }
}
?>

<style>
.market-results{background:#f5f8fc;color:#102033;overflow:hidden}.market-results *{box-sizing:border-box}.mr-container{max-width:none;margin:0 auto;padding:0 20px}.mr-hero{position:relative;background:linear-gradient(135deg,#0d4e96 0%,#123b64 54%,#884807 100%);color:#fff;padding:74px 0 62px;isolation:isolate}.mr-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 14% 22%,rgba(255,255,255,.22),transparent 28%),radial-gradient(circle at 86% 12%,rgba(255,210,132,.2),transparent 30%);z-index:-1}.mr-hero:after{content:"";position:absolute;left:-6%;right:-6%;bottom:-58px;height:110px;background:#f5f8fc;border-radius:50% 50% 0 0/100% 100% 0 0;z-index:-1}.mr-hero-grid{display:grid;grid-template-columns:1fr .92fr;gap:42px;align-items:center}.mr-kicker,.mr-label{display:inline-flex;align-items:center;gap:8px;border-radius:999px;font-size:12px;font-weight:850}.mr-kicker{border:1px solid rgba(255,255,255,.25);background:rgba(255,255,255,.13);padding:8px 14px;margin-bottom:18px}.mr-hero h1{font-size:48px;line-height:1.1;font-weight:900;letter-spacing:-1px;margin:0 0 16px}.mr-hero h1 span{color:#ffe2a3}.mr-hero p{font-size:16px;line-height:1.82;color:rgba(255,255,255,.86);max-width:690px}.mr-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}.mr-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:999px;padding:13px 20px;font-size:14px;font-weight:850;transition:.2s}.mr-btn.primary{background:#fff;color:#0d4e96;box-shadow:0 16px 34px rgba(0,0,0,.18)}.mr-btn.ghost{border:1px solid rgba(255,255,255,.26);background:rgba(255,255,255,.1);color:#fff}.mr-btn:hover{transform:translateY(-2px)}.mr-dashboard{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:30px;padding:20px;box-shadow:0 28px 80px rgba(0,0,0,.22);backdrop-filter:blur(12px)}.mr-dash-title{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px}.mr-dash-title b{font-size:17px}.mr-dash-title span{font-size:12px;color:rgba(255,255,255,.72)}.mr-meter{display:grid;gap:12px}.mr-meter-row{background:#fff;color:#102033;border-radius:18px;padding:14px}.mr-meter-top{display:flex;align-items:center;justify-content:space-between;gap:10px;font-size:13px;font-weight:800;margin-bottom:10px}.mr-bar{height:10px;border-radius:999px;background:#edf2f7;overflow:hidden}.mr-fill{height:100%;border-radius:999px;background:linear-gradient(90deg,#0d4e96,#22c55e)}.mr-main{position:relative;z-index:2;padding:58px 0 64px}.mr-label{background:#eef5ff;color:#0d4e96;border:1px solid #d7e8ff;padding:7px 13px}.mr-section-head{text-align:center;max-width:780px;margin:0 auto 30px}.mr-section-head h2{font-size:34px;line-height:1.22;margin:14px 0 10px;color:#101828}.mr-section-head p{font-size:14px;color:#667085;line-height:1.75}.mr-kpis{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px}.mr-kpi{background:#fff;border:1px solid #e6edf7;border-radius:22px;padding:22px;box-shadow:0 16px 42px rgba(13,78,150,.07)}.mr-kpi i{width:44px;height:44px;border-radius:15px;background:#eef5ff;color:#0d4e96;display:grid;place-items:center;font-size:24px;margin-bottom:14px}.mr-kpi b{display:block;font-size:30px;color:#0d4e96}.mr-kpi span{display:block;font-size:13px;color:#667085;line-height:1.5;margin-top:5px}.rounds-list{display:grid;grid-template-columns:1fr;gap:12px}.round-card{display:grid;grid-template-columns:78px 1fr auto;gap:14px;align-items:center;background:#fff;border:1px solid #e6edf7;border-radius:22px;padding:14px;box-shadow:0 12px 30px rgba(13,78,150,.06);transition:.2s;min-width:0;text-decoration:none}.round-card:hover{transform:translateY(-2px);border-color:#b9d7f6;box-shadow:0 16px 34px rgba(13,78,150,.1)}.round-avatar{width:78px;height:78px;border-radius:20px;overflow:hidden;background:#e9eef6;flex:0 0 auto}.round-avatar img{width:100%;height:100%;object-fit:cover;display:block}.round-body{min-width:0}.round-name{font-size:16px;font-weight:900;color:#101828;margin-bottom:5px;line-height:1.35}.round-desc{font-size:13px;color:#667085;line-height:1.55;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}.round-date{display:inline-flex;align-items:center;gap:6px;margin-top:10px;background:#f0f7ff;color:#0d4e96;border:1px solid #d5e8ff;border-radius:999px;padding:6px 10px;font-size:12px;font-weight:800}.round-arrow{width:36px;height:36px;border-radius:14px;background:#eef5ff;color:#0d4e96;display:grid;place-items:center;font-size:19px}.mr-empty{background:#fff;border:1px solid #e6edf7;border-radius:22px;padding:30px;text-align:center;color:#667085}.mr-pagination{display:flex;align-items:center;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:28px}.mr-page-link,.mr-page-current,.mr-page-dots{min-width:42px;height:42px;padding:0 14px;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:800}.mr-page-link{background:#fff;border:1px solid #d9e7f6;color:#1d3557;text-decoration:none}.mr-page-link:hover{border-color:#0d4e96;color:#0d4e96}.mr-page-current{background:#0d4e96;border:1px solid #0d4e96;color:#fff}.mr-page-dots{color:#98a2b3}.mr-cta{padding:54px 0;background:linear-gradient(135deg,#884807,#0d4e96);color:#fff}.mr-cta-inner{display:flex;align-items:center;justify-content:space-between;gap:26px}.mr-cta h2{font-size:30px;line-height:1.25;margin-bottom:8px}.mr-cta p{font-size:14px;color:rgba(255,255,255,.78);line-height:1.7;max-width:680px}@media(max-width:1024px){.mr-hero-grid{grid-template-columns:1fr}.mr-dashboard{max-width:680px;width:100%;margin:0 auto}.mr-kpis{grid-template-columns:repeat(2,1fr)}}@media(max-width:760px){.mr-container{padding:0 16px}.mr-hero{padding:52px 0 50px}.mr-hero h1{font-size:36px;letter-spacing:0}.mr-hero p{font-size:14px}.mr-main{padding:46px 0}.mr-section-head h2,.mr-cta h2{font-size:27px}.mr-kpis{grid-template-columns:1fr}.round-card{grid-template-columns:64px 1fr;align-items:start;border-radius:20px}.round-avatar{width:64px;height:64px;border-radius:18px}.round-arrow{display:none}.mr-cta-inner{flex-direction:column;align-items:flex-start}.mr-cta .mr-btn{width:100%}}@media(max-width:480px){.mr-container{padding:0 12px}.mr-hero h1{font-size:29px}.mr-kicker,.mr-label{font-size:12px;padding:7px 10px}.mr-actions{flex-direction:column}.mr-btn{width:100%}.mr-dashboard{padding:18px;border-radius:22px}.mr-kpi{padding:18px;border-radius:18px}.mr-kpi b{font-size:25px}.mr-cta{padding:42px 0}}
</style>

<main class="market-results">
  <section class="mr-hero">
    <div class="mr-container mr-hero-grid">
      <div>
        <div class="mr-kicker"><i class="ti ti-chart-bar"></i> Kết quả sàn việc làm</div>
        <h1>Theo dõi từng đợt kết nối tuyển dụng <span>rõ ràng và trực quan</span></h1>
        <p>Mỗi đợt kết nối được hiển thị bằng một box riêng. Chọn từng box để xem chi tiết chương trình, thống kê tuyển dụng và kết quả nổi bật của đợt kết nối đó.</p>
        <div class="mr-actions">
          <a class="mr-btn primary" href="#connectionRounds"><i class="ti ti-layout-cards"></i> Xem từng đợt</a>
          <a class="mr-btn ghost" href="<?php echo XC_URL; ?>/quy-trinh-san-viec-lam.html"><i class="ti ti-route"></i> Quy trình sàn</a>
        </div>
      </div>
      <div class="mr-dashboard">
        <div class="mr-dash-title">
          <b>Tổng quan kết quả</b>
          <span>Cập nhật theo dữ liệu hệ thống</span>
        </div>
        <div class="mr-meter">
          <div class="mr-meter-row">
            <div class="mr-meter-top"><span>Doanh nghiệp tham gia</span><strong><?php echo (int)$summary->total_companies; ?></strong></div>
            <div class="mr-bar"><div class="mr-fill" style="width:<?php echo min(100, max(22, (int)$summary->total_companies)); ?>%"></div></div>
          </div>
          <div class="mr-meter-row">
            <div class="mr-meter-top"><span>Hồ sơ ứng tuyển</span><strong><?php echo (int)$summary->total_profiles; ?></strong></div>
            <div class="mr-bar"><div class="mr-fill" style="width:<?php echo min(100, max(22, (int)$summary->total_profiles / 10)); ?>%"></div></div>
          </div>
          <div class="mr-meter-row">
            <div class="mr-meter-top"><span>Lượt phỏng vấn</span><strong><?php echo (int)$summary->total_interviews; ?></strong></div>
            <div class="mr-bar"><div class="mr-fill" style="width:<?php echo min(100, max(22, (int)$summary->total_interviews / 8)); ?>%"></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="mr-main" id="connectionRounds">
    <div class="mr-container">
      <div class="mr-section-head">
        <span class="mr-label"><i class="ti ti-calendar-stats"></i> Các đợt kết nối</span>
        <h2>Danh sách kết quả theo từng chương trình</h2>
        <p>Mỗi trang hiển thị tối đa 10 box. Bấm vào từng box để chuyển tới trang chi tiết của đợt kết nối tương ứng.</p>
      </div>

      <div class="mr-kpis">
        <div class="mr-kpi"><i class="ti ti-calendar-event"></i><b><?php echo (int)$summary->total_rounds; ?></b><span>Số lượng đợt kết nối</span></div>
        <div class="mr-kpi"><i class="ti ti-building"></i><b><?php echo (int)$summary->total_companies; ?></b><span>Doanh nghiệp tham gia</span></div>
        <div class="mr-kpi"><i class="ti ti-file-cv"></i><b><?php echo (int)$summary->total_profiles; ?></b><span>Hồ sơ ứng tuyển gửi qua sàn</span></div>
        <div class="mr-kpi"><i class="ti ti-user-check"></i><b><?php echo (int)$summary->total_interviews; ?></b><span>Lượt ứng viên được hẹn phỏng vấn</span></div>
      </div>

      <div class="rounds-list">
        <?php if (!empty($market_results)): ?>
          <?php foreach ($market_results as $item): ?>
            <a class="round-card" href="<?php echo htmlspecialchars(general::getInstance()->permalink((int)$item->id, 'market_result'), ENT_QUOTES, 'UTF-8'); ?>">
              <div class="round-avatar">
                <img src="<?php echo htmlspecialchars(marketResultImageUrl($item->result_image ?? ''), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item->result_title ?? 'Kết quả sàn', ENT_QUOTES, 'UTF-8'); ?>">
              </div>
              <div class="round-body">
                <div class="round-name"><?php echo htmlspecialchars($item->result_title ?? 'Kết quả sàn', ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="round-desc"><?php echo htmlspecialchars(marketResultExcerpt($item->result_summary ?? '', 160), ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="round-date"><i class="ti ti-calendar"></i> <?php echo !empty($item->result_date) ? date('d/m/Y', strtotime($item->result_date)) : 'Đang cập nhật'; ?></div>
              </div>
              <div class="round-arrow"><i class="ti ti-chevron-right"></i></div>
            </a>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="mr-empty">Chưa có dữ liệu kết quả sàn việc làm để hiển thị.</div>
        <?php endif; ?>
      </div>

      <?php if ($totalPages > 1): ?>
        <div class="mr-pagination">
          <?php foreach (marketResultPaginationItems($page, $totalPages) as $paginationItem): ?>
            <?php if ($paginationItem === 'ellipsis'): ?>
              <span class="mr-page-dots">...</span>
            <?php elseif ((int)$paginationItem === $page): ?>
              <span class="mr-page-current"><?php echo (int)$paginationItem; ?></span>
            <?php else: ?>
              <a class="mr-page-link" href="<?php echo htmlspecialchars(marketResultPageUrl((int)$paginationItem), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int)$paginationItem; ?></a>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <section class="mr-cta">
    <div class="mr-container mr-cta-inner">
      <div><h2>Muốn nâng kết quả tuyển dụng ở đợt tiếp theo?</h2><p>Doanh nghiệp có thể đăng ký tham gia sàn hoặc ngày hội việc làm để tiếp cận nguồn ứng viên phù hợp hơn.</p></div>
      <div class="mr-actions"><a class="mr-btn primary" href="<?php echo XC_URL; ?>/lien-he.html"><i class="ti ti-message-2"></i> Liên hệ hợp tác</a><a class="mr-btn ghost" href="<?php echo XC_URL; ?>/gioi-thieu-san-viec-lam.html"><i class="ti ti-building-community"></i> Giới thiệu sàn</a></div>
    </div>
  </section>
</main>

<?php require "footer.php"; ?>
