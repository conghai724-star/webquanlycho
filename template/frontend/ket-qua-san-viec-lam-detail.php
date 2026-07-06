<?php require "header.php"; ?>
<?php
$market_result_detail = isset($market_result_detail) && is_object($market_result_detail) ? $market_result_detail : null;
$market_result_related = isset($market_result_related) && is_array($market_result_related) ? $market_result_related : array();

if (!$market_result_detail) {
  header("Location: ".XC_URL."/ket-qua-san-viec-lam.html");
  exit();
}

if (!function_exists('marketResultDetailImageUrl')) {
  function marketResultDetailImageUrl($value) {
    $value = trim((string)$value);
    if ($value === '') {
      return 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1200&h=680&fit=crop';
    }
    if (preg_match('#^(https?:)?//#i', $value) || strpos($value, 'data:') === 0) {
      return $value;
    }
    return XC_URL.'/uploads/images/'.ltrim($value, '/');
  }
}

if (!function_exists('marketResultDetailLines')) {
  function marketResultDetailLines($value) {
    $value = trim((string)$value);
    if ($value === '') { return array(); }
    $parts = preg_split('/\r\n|\r|\n/', $value);
    $parts = array_filter(array_map('trim', $parts), function($item) {
      return $item !== '';
    });
    return array_values($parts);
  }
}

$implementationItems = marketResultDetailLines($market_result_detail->implementation_content ?? '');
$highlightItems = marketResultDetailLines($market_result_detail->highlight_content ?? '');
?>

<style>
.mr-detail-page{background:#f5f8fc;color:#102033;overflow:hidden}.mr-detail-page *{box-sizing:border-box}.mrd-container{max-width:none;margin:0 auto;padding:0 20px}.mrd-hero{position:relative;background:linear-gradient(135deg,#0d4e96 0%,#123b64 54%,#884807 100%);color:#fff;padding:72px 0 58px;isolation:isolate}.mrd-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 14% 22%,rgba(255,255,255,.22),transparent 28%),radial-gradient(circle at 86% 12%,rgba(255,210,132,.2),transparent 30%);z-index:-1}.mrd-hero:after{content:"";position:absolute;left:-6%;right:-6%;bottom:-58px;height:110px;background:#f5f8fc;border-radius:50% 50% 0 0/100% 100% 0 0;z-index:-1}.mrd-breadcrumb{display:flex;gap:8px;flex-wrap:wrap;align-items:center;font-size:13px;color:rgba(255,255,255,.82);margin-bottom:18px}.mrd-breadcrumb a{color:#fff;text-decoration:none}.mrd-hero-grid{display:grid;grid-template-columns:1fr .96fr;gap:38px;align-items:center}.mrd-date{display:inline-flex;align-items:center;gap:8px;border-radius:999px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.22);padding:8px 14px;font-size:12px;font-weight:800;margin-bottom:16px}.mrd-hero h1{font-size:44px;line-height:1.14;font-weight:900;letter-spacing:-1px;margin:0 0 14px}.mrd-hero p{font-size:16px;line-height:1.8;color:rgba(255,255,255,.86);max-width:720px}.mrd-media{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:28px;padding:18px;box-shadow:0 24px 70px rgba(0,0,0,.22)}.mrd-media img{width:100%;height:360px;object-fit:cover;border-radius:22px;display:block}.mrd-main{position:relative;z-index:2;padding:56px 0 64px}.mrd-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px}.mrd-stat{background:#fff;border:1px solid #e6edf7;border-radius:22px;padding:22px;box-shadow:0 16px 42px rgba(13,78,150,.07)}.mrd-stat b{display:block;font-size:30px;color:#0d4e96}.mrd-stat span{display:block;font-size:13px;color:#667085;line-height:1.5;margin-top:5px}.mrd-layout{display:grid;grid-template-columns:1.05fr .95fr;gap:22px;align-items:start}.mrd-panel{background:#fff;border:1px solid #e6edf7;border-radius:26px;padding:24px;box-shadow:0 18px 46px rgba(13,78,150,.08)}.mrd-panel h2,.mrd-panel h3{font-size:22px;margin-bottom:12px;color:#101828}.mrd-panel p{font-size:14px;color:#475467;line-height:1.8}.mrd-content{font-size:15px;color:#344054;line-height:1.85}.mrd-content p{margin-bottom:14px}.mrd-list{display:grid;gap:10px;margin:0;padding:0;list-style:none}.mrd-list li{display:flex;gap:10px;align-items:flex-start;font-size:14px;line-height:1.7;color:#344054}.mrd-list i{color:#16a34a;font-size:18px;flex:0 0 auto}.mrd-note{margin-top:18px;border-radius:20px;background:linear-gradient(135deg,#102033,#0d4e96);color:#fff;padding:18px;font-size:14px;line-height:1.8}.mrd-note b{color:#ffd08a}.mrd-related{display:grid;gap:12px;margin-top:18px}.mrd-related-item{display:grid;grid-template-columns:74px 1fr;gap:12px;align-items:center;text-decoration:none;background:#f8fbff;border:1px solid #e6edf7;border-radius:18px;padding:12px}.mrd-related-item img{width:74px;height:74px;border-radius:16px;object-fit:cover}.mrd-related-item strong{display:block;font-size:14px;color:#101828;line-height:1.45}.mrd-related-item span{display:block;font-size:12px;color:#667085;margin-top:4px}.mrd-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:18px}.mrd-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:999px;padding:13px 18px;font-size:14px;font-weight:800;text-decoration:none}.mrd-btn.primary{background:#0d4e96;color:#fff}.mrd-btn.ghost{background:#eef5ff;border:1px solid #d8e8ff;color:#0d4e96}@media(max-width:1024px){.mrd-hero-grid,.mrd-layout{grid-template-columns:1fr}.mrd-stats{grid-template-columns:repeat(2,1fr)}}@media(max-width:760px){.mrd-container{padding:0 16px}.mrd-hero{padding:52px 0 48px}.mrd-hero h1{font-size:34px;letter-spacing:0}.mrd-hero p{font-size:14px}.mrd-main{padding:46px 0}.mrd-stats{grid-template-columns:1fr}.mrd-media img{height:240px}}@media(max-width:480px){.mrd-container{padding:0 12px}.mrd-hero h1{font-size:28px}.mrd-panel{padding:18px;border-radius:22px}.mrd-stat{padding:18px;border-radius:18px}.mrd-stat b{font-size:25px}.mrd-actions{flex-direction:column}.mrd-btn{width:100%}}
</style>

<main class="mr-detail-page">
  <section class="mrd-hero">
    <div class="mrd-container">
      <div class="mrd-breadcrumb">
        <a href="<?php echo XC_URL; ?>">Trang chủ</a>
        <i class="ti ti-chevron-right"></i>
        <a href="<?php echo XC_URL; ?>/ket-qua-san-viec-lam.html">Kết quả sàn</a>
        <i class="ti ti-chevron-right"></i>
        <span><?php echo htmlspecialchars($market_result_detail->result_title ?? 'Chi tiết kết quả sàn', ENT_QUOTES, 'UTF-8'); ?></span>
      </div>
      <div class="mrd-hero-grid">
        <div>
          <div class="mrd-date"><i class="ti ti-calendar"></i> <?php echo !empty($market_result_detail->result_date) ? date('d/m/Y', strtotime($market_result_detail->result_date)) : 'Đang cập nhật'; ?></div>
          <h1><?php echo htmlspecialchars($market_result_detail->result_title ?? 'Chi tiết kết quả sàn', ENT_QUOTES, 'UTF-8'); ?></h1>
          <p><?php echo htmlspecialchars($market_result_detail->result_summary ?? '', ENT_QUOTES, 'UTF-8'); ?></p>
          <div class="mrd-actions">
            <a class="mrd-btn primary" href="<?php echo XC_URL; ?>/ket-qua-san-viec-lam.html"><i class="ti ti-arrow-left"></i> Quay lại danh sách</a>
            <a class="mrd-btn ghost" href="<?php echo XC_URL; ?>/lien-he.html"><i class="ti ti-message-2"></i> Liên hệ hợp tác</a>
          </div>
        </div>
        <div class="mrd-media">
          <img src="<?php echo htmlspecialchars(marketResultDetailImageUrl($market_result_detail->result_image ?? ''), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($market_result_detail->result_title ?? 'Kết quả sàn', ENT_QUOTES, 'UTF-8'); ?>">
        </div>
      </div>
    </div>
  </section>

  <section class="mrd-main">
    <div class="mrd-container">
      <div class="mrd-stats">
        <div class="mrd-stat"><b><?php echo (int)($market_result_detail->company_total ?? 0); ?></b><span>Doanh nghiệp tham gia</span></div>
        <div class="mrd-stat"><b><?php echo (int)($market_result_detail->position_total ?? 0); ?></b><span>Vị trí tuyển dụng</span></div>
        <div class="mrd-stat"><b><?php echo (int)($market_result_detail->profile_total ?? 0); ?></b><span>Hồ sơ ứng tuyển</span></div>
        <div class="mrd-stat"><b><?php echo (int)($market_result_detail->interview_total ?? 0); ?></b><span>Lượt phỏng vấn</span></div>
      </div>

      <div class="mrd-layout">
        <div class="mrd-panel">
          <h2>Nội dung chi tiết</h2>
          <div class="mrd-content">
            <?php echo !empty($market_result_detail->result_content) ? $market_result_detail->result_content : '<p>Đang cập nhật nội dung chi tiết cho đợt kết nối này.</p>'; ?>
          </div>
          <?php if (!empty($market_result_detail->note_content)): ?>
            <div class="mrd-note"><b>Ghi chú:</b> <?php echo nl2br(htmlspecialchars($market_result_detail->note_content, ENT_QUOTES, 'UTF-8')); ?></div>
          <?php endif; ?>
        </div>

        <div style="display:grid;gap:22px;">
          <div class="mrd-panel">
            <h3>Nội dung triển khai</h3>
            <?php if (!empty($implementationItems)): ?>
              <ul class="mrd-list">
                <?php foreach ($implementationItems as $implementationItem): ?>
                  <li><i class="ti ti-circle-check"></i><span><?php echo htmlspecialchars($implementationItem, ENT_QUOTES, 'UTF-8'); ?></span></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p>Chưa có nội dung triển khai chi tiết.</p>
            <?php endif; ?>
          </div>

          <div class="mrd-panel">
            <h3>Kết quả nổi bật</h3>
            <?php if (!empty($highlightItems)): ?>
              <ul class="mrd-list">
                <?php foreach ($highlightItems as $highlightItem): ?>
                  <li><i class="ti ti-sparkles"></i><span><?php echo htmlspecialchars($highlightItem, ENT_QUOTES, 'UTF-8'); ?></span></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p>Chưa có kết quả nổi bật được cập nhật.</p>
            <?php endif; ?>
          </div>

          <?php if (!empty($market_result_related)): ?>
            <div class="mrd-panel">
              <h3>Đợt kết nối khác</h3>
              <div class="mrd-related">
                <?php foreach ($market_result_related as $relatedItem): ?>
                  <a class="mrd-related-item" href="<?php echo htmlspecialchars(general::getInstance()->permalink((int)$relatedItem->id, 'market_result'), ENT_QUOTES, 'UTF-8'); ?>">
                    <img src="<?php echo htmlspecialchars(marketResultDetailImageUrl($relatedItem->result_image ?? ''), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($relatedItem->result_title ?? 'Kết quả sàn', ENT_QUOTES, 'UTF-8'); ?>">
                    <div>
                      <strong><?php echo htmlspecialchars($relatedItem->result_title ?? 'Kết quả sàn', ENT_QUOTES, 'UTF-8'); ?></strong>
                      <span><?php echo !empty($relatedItem->result_date) ? date('d/m/Y', strtotime($relatedItem->result_date)) : 'Đang cập nhật'; ?></span>
                    </div>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require "footer.php"; ?>
