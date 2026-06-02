<?php require "header.php"; ?>

<style>
.online-fair-page{background:#f5f8fc;color:#102033;overflow:hidden}.online-fair-page *{box-sizing:border-box}.online-container{max-width:none;margin:0 auto;padding:0 20px}.online-hero{position:relative;background:linear-gradient(135deg,#0d4e96 0%,#123b64 55%,#884807 100%);color:#fff;padding:74px 0 62px;isolation:isolate}.online-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 14% 20%,rgba(255,255,255,.22),transparent 28%),radial-gradient(circle at 86% 14%,rgba(255,212,135,.2),transparent 30%);z-index:-1}.online-hero:after{content:"";position:absolute;left:-6%;right:-6%;bottom:-58px;height:110px;background:#f5f8fc;border-radius:50% 50% 0 0/100% 100% 0 0;z-index:-1}.online-hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:42px;align-items:center}.online-kicker,.online-label{display:inline-flex;align-items:center;gap:8px;border-radius:999px;font-size:12px;font-weight:850}.online-kicker{border:1px solid rgba(255,255,255,.25);background:rgba(255,255,255,.13);padding:8px 14px;margin-bottom:18px}.online-hero h1{font-size:48px;line-height:1.1;font-weight:900;letter-spacing:-1px;margin:0 0 16px}.online-hero h1 span{color:#ffe2a3}.online-hero p{font-size:16px;line-height:1.82;color:rgba(255,255,255,.86);max-width:690px}.online-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}.online-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:999px;padding:13px 20px;font-size:14px;font-weight:850;transition:.2s;border:0;cursor:pointer}.online-btn.primary{background:#fff;color:#0d4e96;box-shadow:0 16px 34px rgba(0,0,0,.18)}.online-btn.ghost{border:1px solid rgba(255,255,255,.26);background:rgba(255,255,255,.1);color:#fff}.online-btn:hover{transform:translateY(-2px)}.online-preview{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:30px;padding:20px;box-shadow:0 28px 80px rgba(0,0,0,.22);backdrop-filter:blur(12px)}.preview-head{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:16px}.preview-head b{font-size:17px}.live-pill{display:inline-flex;align-items:center;gap:7px;background:#dcfce7;color:#166534;border:1px solid #86efac;border-radius:999px;padding:7px 11px;font-size:12px;font-weight:900}.live-dot{width:8px;height:8px;border-radius:50%;background:#22c55e;box-shadow:0 0 0 5px rgba(34,197,94,.16)}.preview-card{background:#fff;color:#102033;border-radius:22px;padding:18px}.preview-company{display:flex;gap:13px;align-items:center;margin-bottom:16px}.preview-logo{width:58px;height:58px;border-radius:18px;background:linear-gradient(135deg,#0d4e96,#2d8be0);color:#fff;display:grid;place-items:center;font-weight:900;font-size:18px}.preview-company h3{font-size:17px;margin-bottom:4px}.preview-company span{font-size:12px;color:#667085}.preview-bars{display:grid;gap:11px}.preview-line{display:grid;grid-template-columns:120px 1fr;gap:12px;align-items:center;font-size:12px;color:#667085}.preview-track{height:10px;background:#edf2f7;border-radius:999px;overflow:hidden}.preview-track span{display:block;height:100%;border-radius:999px;background:linear-gradient(90deg,#0d4e96,#22c55e)}.online-main{position:relative;z-index:2;padding:58px 0 64px}.online-label{background:#eef5ff;color:#0d4e96;border:1px solid #d7e8ff;padding:7px 13px}.online-section-head{display:flex;align-items:flex-end;justify-content:space-between;gap:24px;margin-bottom:24px}.online-section-head h2{font-size:34px;line-height:1.22;margin:14px 0 8px;color:#101828}.online-section-head p{font-size:14px;color:#667085;line-height:1.75;max-width:720px}.meeting-summary{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px}.summary-card{background:#fff;border:1px solid #e6edf7;border-radius:22px;padding:20px;box-shadow:0 14px 36px rgba(13,78,150,.06)}.summary-card i{width:42px;height:42px;border-radius:15px;background:#eef5ff;color:#0d4e96;display:grid;place-items:center;font-size:24px;margin-bottom:12px}.summary-card b{display:block;font-size:28px;color:#0d4e96}.summary-card span{display:block;font-size:13px;color:#667085;margin-top:4px;line-height:1.45}.meeting-toolbar{display:flex;align-items:center;justify-content:space-between;gap:14px;background:#fff;border:1px solid #e6edf7;border-radius:22px;padding:14px;margin-bottom:18px;box-shadow:0 12px 30px rgba(13,78,150,.05)}.toolbar-tabs{display:flex;gap:8px;flex-wrap:wrap}.toolbar-tab{display:inline-flex;align-items:center;gap:7px;border-radius:999px;padding:9px 13px;font-size:12px;font-weight:850;background:#f8fbff;border:1px solid #e2ebf6;color:#334155}.toolbar-tab.active{background:#0d4e96;border-color:#0d4e96;color:#fff}.toolbar-note{font-size:12px;color:#667085;line-height:1.5}.meetings-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}.meeting-card{background:#fff;border:1px solid #e6edf7;border-radius:24px;padding:18px;box-shadow:0 16px 42px rgba(13,78,150,.07);transition:.2s;min-width:0;position:relative;overflow:hidden}.meeting-card:hover{transform:translateY(-4px);border-color:#b9d7f6;box-shadow:0 20px 48px rgba(13,78,150,.12)}.meeting-card:before{content:"";position:absolute;right:-70px;top:-80px;width:180px;height:180px;border-radius:50%;background:#eef5ff;opacity:.9}.meeting-card.closed:before{background:#f1f5f9}.meeting-card>*{position:relative}.meeting-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:16px}.company-block{display:flex;gap:12px;align-items:center;min-width:0}.company-avatar{width:56px;height:56px;border-radius:18px;color:#fff;display:grid;place-items:center;font-size:18px;font-weight:900;box-shadow:0 14px 28px rgba(13,78,150,.15);flex:0 0 auto}.company-avatar.blue{background:linear-gradient(135deg,#0d4e96,#2d8be0)}.company-avatar.green{background:linear-gradient(135deg,#087f5b,#22c55e)}.company-avatar.orange{background:linear-gradient(135deg,#884807,#f59e0b)}.company-avatar.purple{background:linear-gradient(135deg,#5b21b6,#a855f7)}.company-avatar.red{background:linear-gradient(135deg,#991b1b,#ef4444)}.company-name{font-size:17px;font-weight:900;color:#101828;line-height:1.35;overflow-wrap:anywhere}.company-sub{font-size:12px;color:#667085;margin-top:3px}.status-badge{display:inline-flex;align-items:center;gap:7px;border-radius:999px;padding:7px 10px;font-size:12px;font-weight:900;white-space:nowrap}.status-badge.open{background:#dcfce7;color:#166534;border:1px solid #86efac}.status-badge.closed{background:#f1f5f9;color:#475569;border:1px solid #cbd5e1}.status-badge:before{content:"";width:7px;height:7px;border-radius:50%;background:currentColor}.meeting-info{display:grid;gap:10px;margin-top:14px}.meeting-row{display:flex;gap:10px;align-items:flex-start;border:1px solid #edf2f7;background:#fbfdff;border-radius:16px;padding:11px}.meeting-row i{width:28px;height:28px;border-radius:10px;background:#eef5ff;color:#0d4e96;display:grid;place-items:center;font-size:17px;flex:0 0 auto}.meeting-label{display:block;font-size:11px;font-weight:850;color:#8a96a8;text-transform:uppercase;letter-spacing:.25px}.meeting-value{display:block;font-size:14px;font-weight:850;color:#172033;margin-top:2px;line-height:1.4}.meeting-footer{display:flex;gap:10px;align-items:center;margin-top:16px}.meeting-action{flex:1;display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:14px;padding:12px 14px;background:#0d4e96;color:#fff;font-size:13px;font-weight:900}.meeting-action.closed{background:#e2e8f0;color:#64748b}.meeting-more{width:44px;height:44px;border-radius:14px;background:#eef5ff;color:#0d4e96;display:grid;place-items:center;font-size:20px}.online-note-section{padding:62px 0;background:#fff}.note-grid{display:grid;grid-template-columns:.95fr 1.05fr;gap:22px;align-items:stretch}.note-panel{background:#f8fbff;border:1px solid #e6edf7;border-radius:26px;padding:24px}.note-panel h3{font-size:22px;margin:12px 0 8px}.note-panel p{font-size:14px;color:#667085;line-height:1.7}.note-list{display:grid;gap:10px;margin-top:18px}.note-item{display:flex;gap:10px;align-items:flex-start;background:#fff;border:1px solid #edf2f7;border-radius:16px;padding:13px;font-size:13px;line-height:1.55;color:#334155}.note-item i{color:#16a34a;font-size:18px;flex:0 0 auto}.online-cta{padding:54px 0;background:linear-gradient(135deg,#884807,#0d4e96);color:#fff}.online-cta-inner{display:flex;align-items:center;justify-content:space-between;gap:26px}.online-cta h2{font-size:30px;line-height:1.25;margin-bottom:8px}.online-cta p{font-size:14px;color:rgba(255,255,255,.78);line-height:1.7;max-width:700px}.meeting-card,.summary-card,.note-panel{min-width:0}.meeting-value,.company-name,.note-panel p,.note-item{overflow-wrap:anywhere}@media(max-width:1060px){.online-hero-grid,.note-grid{grid-template-columns:1fr}.online-preview{max-width:680px;width:100%;margin:0 auto}.meeting-summary{grid-template-columns:repeat(2,1fr)}.meetings-grid{grid-template-columns:repeat(2,1fr)}.online-section-head{flex-direction:column;align-items:flex-start}.online-cta-inner{flex-direction:column;align-items:flex-start}}@media(max-width:760px){.online-container{padding:0 16px}.online-hero{padding:52px 0 50px}.online-hero h1{font-size:36px;letter-spacing:0}.online-hero p{font-size:14px}.online-main,.online-note-section{padding:46px 0}.online-section-head h2,.online-cta h2{font-size:27px}.meeting-summary,.meetings-grid{grid-template-columns:1fr}.meeting-toolbar{align-items:flex-start;flex-direction:column}.toolbar-tabs{width:100%}.toolbar-tab{flex:1;justify-content:center}.meeting-top{flex-direction:column}.status-badge{align-self:flex-start}.preview-line{grid-template-columns:1fr}.online-cta .online-btn{width:100%}}@media(max-width:480px){.online-container{padding:0 12px}.online-hero h1{font-size:29px}.online-kicker,.online-label{font-size:12px;padding:7px 10px}.online-actions{flex-direction:column}.online-btn{width:100%}.online-preview,.meeting-card,.note-panel{padding:16px;border-radius:22px}.summary-card{padding:16px;border-radius:18px}.summary-card b{font-size:24px}.company-block{align-items:flex-start}.company-avatar{width:50px;height:50px;border-radius:16px}.meeting-footer{flex-direction:column}.meeting-action,.meeting-more{width:100%}.online-cta{padding:42px 0}}
</style>

<main class="online-fair-page">
  <section class="online-hero">
    <div class="online-container online-hero-grid">
      <div>
        <div class="online-kicker"><i class="ti ti-broadcast"></i> Sàn việc làm Online</div>
        <h1>Lịch meeting phỏng vấn trực tuyến <span>cho từng doanh nghiệp</span></h1>
        <p>Trang hiển thị các buổi meeting tuyển dụng online theo từng công ty. Mỗi box thể hiện trạng thái, số lượng ứng viên phỏng vấn, thời gian mở và ngày phỏng vấn để sinh viên dễ theo dõi.</p>
        <div class="online-actions">
          <a class="online-btn primary" href="#meetingList"><i class="ti ti-video"></i> Xem lịch meeting</a>
          <a class="online-btn ghost" href="ket-qua-san-viec-lam.php"><i class="ti ti-chart-bar"></i> Kết quả sàn</a>
        </div>
      </div>

      <div class="online-preview">
        <div class="preview-head"><b>Meeting đang nổi bật</b><span class="live-pill"><span class="live-dot"></span> Đang mở</span></div>
        <div class="preview-card">
          <div class="preview-company">
            <div class="preview-logo">FPT</div>
            <div><h3>FPT Software</h3><span>Phỏng vấn Fresher Developer</span></div>
          </div>
          <div class="preview-bars">
            <div class="preview-line"><span>Ứng viên đăng ký</span><div class="preview-track"><span style="width:82%"></span></div></div>
            <div class="preview-line"><span>Lịch phỏng vấn</span><div class="preview-track"><span style="width:64%"></span></div></div>
            <div class="preview-line"><span>Phản hồi doanh nghiệp</span><div class="preview-track"><span style="width:76%"></span></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="online-main" id="meetingList">
    <div class="online-container">
      <div class="online-section-head">
        <div>
          <span class="online-label"><i class="ti ti-calendar-time"></i> Danh sách meeting</span>
          <h2>Các buổi phỏng vấn online</h2>
          <p>Mỗi box là một meeting tuyển dụng của doanh nghiệp. Bạn có thể thay các card mẫu này bằng dữ liệu PHP từ database.</p>
        </div>
      </div>

      <div class="meeting-summary">
        <div class="summary-card"><i class="ti ti-video"></i><b>08</b><span>Meeting phỏng vấn online</span></div>
        <div class="summary-card"><i class="ti ti-door-enter"></i><b>05</b><span>Meeting đang mở đăng ký</span></div>
        <div class="summary-card"><i class="ti ti-users"></i><b>286</b><span>Ứng viên được hẹn phỏng vấn</span></div>
        <div class="summary-card"><i class="ti ti-building"></i><b>08</b><span>Công ty tham gia tuyển dụng</span></div>
      </div>

      <div class="meeting-toolbar">
        <div class="toolbar-tabs">
          <span class="toolbar-tab active"><i class="ti ti-layout-grid"></i> Tất cả</span>
          <span class="toolbar-tab"><i class="ti ti-circle-check"></i> Đang mở</span>
          <span class="toolbar-tab"><i class="ti ti-circle-x"></i> Đã đóng</span>
        </div>
        <div class="toolbar-note">Dữ liệu mẫu: trạng thái có thể đổi bằng PHP theo thời gian mở và ngày phỏng vấn.</div>
      </div>

      <div class="meetings-grid">
        <article class="meeting-card">
          <div class="meeting-top"><div class="company-block"><div class="company-avatar blue">FPT</div><div><div class="company-name">FPT Software</div><div class="company-sub">Fresher Developer</div></div></div><span class="status-badge open">Đang mở</span></div>
          <div class="meeting-info"><div class="meeting-row"><i class="ti ti-users"></i><div><span class="meeting-label">Số lượng ứng viên phỏng vấn</span><span class="meeting-value">42 ứng viên</span></div></div><div class="meeting-row"><i class="ti ti-clock"></i><div><span class="meeting-label">Thời gian mở</span><span class="meeting-value">08:00 - 17:00</span></div></div><div class="meeting-row"><i class="ti ti-calendar-event"></i><div><span class="meeting-label">Ngày phỏng vấn</span><span class="meeting-value">28/05/2026</span></div></div></div>
          <div class="meeting-footer"><a class="meeting-action" href="#"><i class="ti ti-video-plus"></i> Tham gia meeting</a><a class="meeting-more" href="#" aria-label="Chi tiết FPT Software"><i class="ti ti-chevron-right"></i></a></div>
        </article>

        <article class="meeting-card">
          <div class="meeting-top"><div class="company-block"><div class="company-avatar green">TG</div><div><div class="company-name">Thế Giới Di Động</div><div class="company-sub">Tư vấn bán hàng</div></div></div><span class="status-badge open">Đang mở</span></div>
          <div class="meeting-info"><div class="meeting-row"><i class="ti ti-users"></i><div><span class="meeting-label">Số lượng ứng viên phỏng vấn</span><span class="meeting-value">36 ứng viên</span></div></div><div class="meeting-row"><i class="ti ti-clock"></i><div><span class="meeting-label">Thời gian mở</span><span class="meeting-value">09:00 - 16:30</span></div></div><div class="meeting-row"><i class="ti ti-calendar-event"></i><div><span class="meeting-label">Ngày phỏng vấn</span><span class="meeting-value">30/05/2026</span></div></div></div>
          <div class="meeting-footer"><a class="meeting-action" href="#"><i class="ti ti-video-plus"></i> Tham gia meeting</a><a class="meeting-more" href="#" aria-label="Chi tiết Thế Giới Di Động"><i class="ti ti-chevron-right"></i></a></div>
        </article>

        <article class="meeting-card closed">
          <div class="meeting-top"><div class="company-block"><div class="company-avatar orange">VN</div><div><div class="company-name">Vinpearl Kon Tum</div><div class="company-sub">Nhân sự dịch vụ</div></div></div><span class="status-badge closed">Đã đóng</span></div>
          <div class="meeting-info"><div class="meeting-row"><i class="ti ti-users"></i><div><span class="meeting-label">Số lượng ứng viên phỏng vấn</span><span class="meeting-value">28 ứng viên</span></div></div><div class="meeting-row"><i class="ti ti-clock"></i><div><span class="meeting-label">Thời gian mở</span><span class="meeting-value">08:30 - 15:00</span></div></div><div class="meeting-row"><i class="ti ti-calendar-event"></i><div><span class="meeting-label">Ngày phỏng vấn</span><span class="meeting-value">20/05/2026</span></div></div></div>
          <div class="meeting-footer"><span class="meeting-action closed"><i class="ti ti-lock"></i> Đã kết thúc</span><a class="meeting-more" href="#" aria-label="Chi tiết Vinpearl Kon Tum"><i class="ti ti-chevron-right"></i></a></div>
        </article>

        <article class="meeting-card">
          <div class="meeting-top"><div class="company-block"><div class="company-avatar purple">MB</div><div><div class="company-name">MB Bank</div><div class="company-sub">Giao dịch viên tập sự</div></div></div><span class="status-badge open">Đang mở</span></div>
          <div class="meeting-info"><div class="meeting-row"><i class="ti ti-users"></i><div><span class="meeting-label">Số lượng ứng viên phỏng vấn</span><span class="meeting-value">31 ứng viên</span></div></div><div class="meeting-row"><i class="ti ti-clock"></i><div><span class="meeting-label">Thời gian mở</span><span class="meeting-value">13:30 - 17:30</span></div></div><div class="meeting-row"><i class="ti ti-calendar-event"></i><div><span class="meeting-label">Ngày phỏng vấn</span><span class="meeting-value">02/06/2026</span></div></div></div>
          <div class="meeting-footer"><a class="meeting-action" href="#"><i class="ti ti-video-plus"></i> Tham gia meeting</a><a class="meeting-more" href="#" aria-label="Chi tiết MB Bank"><i class="ti ti-chevron-right"></i></a></div>
        </article>

        <article class="meeting-card closed">
          <div class="meeting-top"><div class="company-block"><div class="company-avatar red">VL</div><div><div class="company-name">Viettel Logistics</div><div class="company-sub">Điều phối vận hành</div></div></div><span class="status-badge closed">Đã đóng</span></div>
          <div class="meeting-info"><div class="meeting-row"><i class="ti ti-users"></i><div><span class="meeting-label">Số lượng ứng viên phỏng vấn</span><span class="meeting-value">24 ứng viên</span></div></div><div class="meeting-row"><i class="ti ti-clock"></i><div><span class="meeting-label">Thời gian mở</span><span class="meeting-value">09:00 - 14:00</span></div></div><div class="meeting-row"><i class="ti ti-calendar-event"></i><div><span class="meeting-label">Ngày phỏng vấn</span><span class="meeting-value">16/05/2026</span></div></div></div>
          <div class="meeting-footer"><span class="meeting-action closed"><i class="ti ti-lock"></i> Đã kết thúc</span><a class="meeting-more" href="#" aria-label="Chi tiết Viettel Logistics"><i class="ti ti-chevron-right"></i></a></div>
        </article>

        <article class="meeting-card">
          <div class="meeting-top"><div class="company-block"><div class="company-avatar blue">KT</div><div><div class="company-name">Kon Tum Tourism</div><div class="company-sub">Hướng dẫn viên du lịch</div></div></div><span class="status-badge open">Đang mở</span></div>
          <div class="meeting-info"><div class="meeting-row"><i class="ti ti-users"></i><div><span class="meeting-label">Số lượng ứng viên phỏng vấn</span><span class="meeting-value">18 ứng viên</span></div></div><div class="meeting-row"><i class="ti ti-clock"></i><div><span class="meeting-label">Thời gian mở</span><span class="meeting-value">10:00 - 15:30</span></div></div><div class="meeting-row"><i class="ti ti-calendar-event"></i><div><span class="meeting-label">Ngày phỏng vấn</span><span class="meeting-value">05/06/2026</span></div></div></div>
          <div class="meeting-footer"><a class="meeting-action" href="#"><i class="ti ti-video-plus"></i> Tham gia meeting</a><a class="meeting-more" href="#" aria-label="Chi tiết Kon Tum Tourism"><i class="ti ti-chevron-right"></i></a></div>
        </article>
      </div>
    </div>
  </section>

  <section class="online-note-section">
    <div class="online-container note-grid">
      <article class="note-panel">
        <span class="online-label"><i class="ti ti-info-circle"></i> Gợi ý vận hành</span>
        <h3>Thông tin meeting nên được cập nhật theo trạng thái thật</h3>
        <p>Khi nối dữ liệu PHP, trạng thái có thể xác định bằng thời gian hiện tại so với thời gian mở và ngày phỏng vấn.</p>
      </article>
      <article class="note-panel">
        <span class="online-label"><i class="ti ti-checklist"></i> Dữ liệu cần có</span>
        <div class="note-list">
          <div class="note-item"><i class="ti ti-circle-check"></i> Tên công ty và vị trí/phòng ban tuyển dụng.</div>
          <div class="note-item"><i class="ti ti-circle-check"></i> Trạng thái: Đang mở hoặc Đã đóng.</div>
          <div class="note-item"><i class="ti ti-circle-check"></i> Số lượng ứng viên phỏng vấn, thời gian mở, ngày phỏng vấn.</div>
          <div class="note-item"><i class="ti ti-circle-check"></i> Link meeting hoặc link chi tiết nếu cần mở rộng sau này.</div>
        </div>
      </article>
    </div>
  </section>

  <section class="online-cta">
    <div class="online-container online-cta-inner">
      <div><h2>Doanh nghiệp muốn tạo meeting tuyển dụng online?</h2><p>Liên hệ bộ phận phụ trách sàn việc làm để đăng ký lịch phỏng vấn, số lượng ứng viên và đường dẫn meeting.</p></div>
      <div class="online-actions"><a class="online-btn primary" href="lien-he.php"><i class="ti ti-message-2"></i> Liên hệ đăng ký</a><a class="online-btn ghost" href="quy-trinh-san-viec-lam.php"><i class="ti ti-route"></i> Xem quy trình</a></div>
    </div>
  </section>
</main>

<?php require "footer.php"; ?>