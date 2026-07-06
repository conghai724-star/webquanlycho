<?php
global $db;
$session_id = session_id();
$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$page_url = $_SERVER['REQUEST_URI'] ?? '';
$current_time = date('Y-m-d H:i:s');
$current_date = date('Y-m-d');

if ($session_id) {
    $session_id_esc = $db->escapestring($session_id);
    $ip_address_esc = $db->escapestring($ip_address);
    $user_agent_esc = $db->escapestring(substr($user_agent, 0, 255));
    $page_url_esc = $db->escapestring(substr($page_url, 0, 500));
    
    // Log visit in database (Insert or update last_seen)
    $db->query("INSERT INTO hicrm_website_visits (session_id, ip_address, user_agent, page_url, visit_date, first_seen, last_seen)
                VALUES ('$session_id_esc', '$ip_address_esc', '$user_agent_esc', '$page_url_esc', '$current_date', '$current_time', '$current_time')
                ON DUPLICATE KEY UPDATE last_seen = '$current_time', page_url = '$page_url_esc'");
}
?>

<!-- FOOTER MOBILE BEAUTIFUL REDESIGN -->
<style>
  /* ===== NEW FOOTER REDESIGN: 3 - 6 - 3 ===== */
  .footer {
    background: linear-gradient(180deg, #ffffff 0%, #f7fbff 58%, #eef6ff 100%);
    color: #607086;
    padding: 60px 0 20px;
    font-family: 'Inter', system-ui, sans-serif;
    border-top: 1px solid #dfe8f5;
    margin-top: 0;
  }
  .footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
  }
  .footer-top-custom {
    display: grid;
    grid-template-columns: repeat(12, minmax(0, 1fr));
    gap: 40px;
    padding-bottom: 40px;
    border-bottom: 1px solid #dfe8f5;
  }
  .footer-col-3 {
    grid-column: span 3;
  }
  .footer-col-6 {
    grid-column: span 6;
  }
  .footer-logo-custom {
    font-size: 24px;
    font-weight: 800;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
  }
  .footer-logo-custom .blue {
    color: #0d4e96;
  }
  .footer-logo-custom .white {
    color: #1f2937;
  }
  .footer-webname {
    color: #152238;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 20px;
    line-height: 1.5;
  }
  .footer-info-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }
  .footer-info-item {
    display: flex;
    gap: 10px;
    font-size: 13.5px;
    line-height: 1.5;
  }
  .footer-info-item i {
    color: #0d4e96;
    font-size: 16px;
    flex-shrink: 0;
    margin-top: 2px;
  }
  .footer-info-item span {
    color: #5a6b82;
  }
  .footer-col-title {
    color: #152238;
    font-size: 16px;
    font-weight: 800;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .footer-intro {
    font-size: 14px;
    line-height: 1.8;
    color: #607086;
    margin-bottom: 25px;
  }
  .footer-links-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }
  .footer-static-link {
    color: #607086;
    font-size: 14px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
  }
  .footer-static-link:hover {
    color: #0d4e96;
    transform: translateX(4px);
  }
  .footer-static-link::before {
    content: "•";
    color: #0d4e96;
    font-weight: bold;
  }
  .footer-map-container {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(13, 78, 150, 0.08);
    border: 1px solid #dfe8f5;
    margin-bottom: 20px;
    background: #fff;
  }
  .footer-stats-box {
    background: rgba(255, 255, 255, 0.82);
    border-radius: 12px;
    padding: 16px;
    border: 1px solid #dfe8f5;
    box-shadow: 0 10px 24px rgba(13, 78, 150, 0.06);
  }
  .footer-stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13.5px;
    padding: 8px 0;
    border-bottom: 1px dashed #d8e5f3;
  }
  .footer-stat-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }
  .footer-stat-row:first-child {
    padding-top: 0;
  }
  .footer-stat-label {
    color: #607086;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .footer-stat-label i {
    color: #0d4e96;
  }
  .footer-stat-value {
    color: #152238;
    font-weight: 700;
  }
  .footer-bottom-custom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    font-size: 13px;
    color: #66768a;
    flex-wrap: wrap;
    gap: 15px;
  }
  .footer-bottom-custom a {
    color: #607086;
    text-decoration: none;
    margin-left: 20px;
    transition: color 0.3s, background 0.3s, border-color 0.3s;
    padding: 8px 12px;
    border-radius: 999px;
    background: #fff;
    border: 1px solid #dfe8f5;
  }
  .footer-bottom-custom a:hover {
    color: #fff;
    background: #0d4e96;
    border-color: #0d4e96;
  }
  @media (max-width: 992px) {
    .footer-col-3, .footer-col-6 {
      grid-column: span 12;
    }
    .footer-top-custom {
      gap: 30px;
    }
    .footer-links-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<footer class="footer">
  <div class="footer-inner">
    <div class="footer-top-custom">
      <div class="footer-col-3">
        <div class="footer-logo-custom">
          <span class="blue">Việc</span><span class="white">Làm</span>
        </div>
        <div class="footer-webname">Hệ thống cổng thông tin việc làm</div>
        <ul class="footer-info-list">
          <li class="footer-info-item">
            <i class="ti ti-phone"></i>
            <span>SĐT: 0282312312</span>
          </li>
          <li class="footer-info-item">
            <i class="ti ti-map-pin"></i>
            <span>Địa chỉ: 14 Ngụy Như Kon Tum, TP. Kon Tum, Tỉnh Kon Tum</span>
          </li>
          <li class="footer-info-item">
            <i class="ti ti-mail"></i>
            <span>Email: vieclam@cdkontum.edu.vn</span>
          </li>
        </ul>
      </div>

      <div class="footer-col-6">
        <h4 class="footer-col-title">Giới Thiệu Và Liên Kết</h4>
        <p class="footer-intro">
          Cổng thông tin việc làm hỗ trợ kết nối sinh viên, người tìm việc và doanh nghiệp tuyển dụng.
          Tìm việc nhanh chóng, ứng tuyển thuận tiện và theo dõi cơ hội nghề nghiệp trên cùng một nền tảng.
        </p>
        <div class="footer-links-grid">
          <a href="<?php echo XC_URL; ?>/gioi-thieu.html" class="footer-static-link">Giới thiệu website</a>
          <a href="<?php echo XC_URL; ?>/huong-dan.html" class="footer-static-link">Hướng dẫn sử dụng</a>
          <a href="<?php echo XC_URL; ?>/dieu-khoan-su-dung.html" class="footer-static-link">Điều khoản sử dụng</a>
          <a href="<?php echo XC_URL; ?>/lien-he.html" class="footer-static-link">Chính sách bảo mật</a>
          <a href="<?php echo XC_URL; ?>/quy-trinh-san-viec-lam.html" class="footer-static-link">Quy trình sàn việc làm</a>
          <a href="<?php echo XC_URL; ?>/san-viec-lam-online.html" class="footer-static-link">Sàn việc làm online</a>
          <a href="<?php echo XC_URL; ?>/quan-ly-viec-lam.html" class="footer-static-link">Danh sách việc làm</a>
          <a href="<?php echo XC_URL; ?>/quan-ly-ung-vien.html" class="footer-static-link">Hồ sơ ứng viên</a>
        </div>
      </div>

      <div class="footer-col-3">
        <div class="footer-map-container">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.062226259074!2d107.99401777590895!3d14.538804978864756!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x316c1faf744ba953%3A0xb3ab20d2d3a4362a!2zVHLGsOG7nW5nIENhbyDEkeG6s25nIEtvbiBUdW0!5e0!3m2!1svi!2s!4v1720250000000!5m2!1svi!2s" width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="footer-stats-box">
          <div class="footer-stat-row">
            <span class="footer-stat-label"><i class="ti ti-users"></i> Đang online:</span>
            <span class="footer-stat-value">12</span>
          </div>
          <div class="footer-stat-row">
            <span class="footer-stat-label"><i class="ti ti-calendar-event"></i> Truy cập hôm qua:</span>
            <span class="footer-stat-value">500</span>
          </div>
          <div class="footer-stat-row">
            <span class="footer-stat-label"><i class="ti ti-chart-line"></i> Tổng lượt truy cập:</span>
            <span class="footer-stat-value">1000</span>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom-custom">
      <div class="footer-bottom-text">© 2026 Cổng thông tin việc làm. Kết nối cơ hội nghề nghiệp cho sinh viên, người lao động và doanh nghiệp.</div>
      <div class="footer-bottom-links">
        <a href="<?php echo XC_URL; ?>/dieu-khoan-su-dung.html">Điều khoản</a>
        <a href="<?php echo XC_URL; ?>/lien-he.html">Bảo mật</a>
        <a href="#">Cookie</a>
      </div>
    </div>
  </div>
</footer>

<!-- FLOAT WIDGET: Zalo, Facebook, Chat -->
<div class="float-widget" id="floatWidget">
  <div class="chat-panel" id="chatPanel" aria-hidden="true">
    <div class="chat-panel-header">
      <div class="chat-panel-header-info">
        <div class="chat-panel-avatar"><i class="ti ti-headset"></i></div>
        <div>
          <div class="chat-panel-title">Việc làm hỗ trợ</div>
          <div class="chat-panel-status">Đang trực tuyến</div>
        </div>
      </div>
      <button type="button" class="chat-panel-close" id="chatPanelClose" aria-label="Đóng chat"><i class="ti ti-x"></i></button>
    </div>
    <div class="chat-panel-body" id="chatPanelBody"><div class="chat-messages" id="chatMessages"></div></div>
    <div class="chat-suggestions-wrap">
      <p class="chat-suggestions-label">Gợi ý cho bạn</p>
      <div class="chat-suggestions" id="chatSuggestions" role="list"></div>
    </div>
  </div>

  <div class="float-actions">
    <a href="<?php echo $this->helper->get_config('zalo_chat_url'); ?>" class="float-action-btn float-zalo" target="_blank" rel="noopener noreferrer" title="Chat Zalo" aria-label="Zalo">
      <svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M24 4C12.95 4 4 11.84 4 21.54c0 5.12 2.56 9.7 6.58 12.72l-1.7 6.22 6.8-3.58C17.9 38.28 20.86 39 24 39c11.05 0 20-7.84 20-17.46S35.05 4 24 4z"/></svg>
    </a>
    <a href="<?php echo $this->helper->get_config('facebook_url'); ?>" class="float-action-btn float-fb" target="_blank" rel="noopener noreferrer" title="Facebook" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
    <button type="button" class="float-btn" id="chatToggleBtn" title="Chat trực tuyến" aria-label="Mở chat" aria-expanded="false"><i class="ti ti-message-circle" id="chatToggleIcon"></i></button>
  </div>
</div>
</html>
<script src="<?php echo $template_path;?>/assets/js/chatbot.js?v=<?php echo filemtime(__SITE_PATH.'/template/frontend/assets/js/chatbot.js'); ?>"></script>
<script src="<?php echo $template_path;?>/assets/js/jscore.min.js?v=<?php echo filemtime(__SITE_PATH.'/template/frontend/assets/js/jscore.min.js'); ?>"></script>
