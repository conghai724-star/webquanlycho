<?php require "header.php"; ?>

<style>
.guide-page{background:#f4f7fb;overflow:hidden}.guide-container{max-width:none;margin:0 auto;padding:0 20px}.guide-hero{position:relative;background:linear-gradient(135deg,#0d4e96 0%,#1769bd 48%,#844404 100%);padding:76px 0 62px;color:#fff;overflow:hidden}.guide-hero-bg{position:absolute;border-radius:999px;background:rgba(255,255,255,.12);filter:blur(2px)}.guide-hero-bg-1{width:360px;height:360px;right:-120px;top:-140px}.guide-hero-bg-2{width:220px;height:220px;left:-80px;bottom:-90px}.guide-hero-inner{position:relative;display:grid;grid-template-columns:1.1fr .9fr;gap:46px;align-items:center}.guide-kicker,.guide-label{display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:800;letter-spacing:.2px;color:#0d4e96;background:#eef5ff;border:1px solid #d7e8ff;border-radius:999px;padding:8px 14px}.guide-kicker{color:#fff;background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.24);backdrop-filter:blur(8px)}.guide-label.light{background:rgba(255,255,255,.14);border-color:rgba(255,255,255,.22);color:#fff}.guide-hero h1{font-size:46px;line-height:1.12;font-weight:900;letter-spacing:-1.2px;margin:18px 0 16px}.guide-hero h1 span{color:#ffe5a6}.guide-hero p{font-size:16px;line-height:1.8;color:rgba(255,255,255,.88);max-width:660px}.guide-hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}.guide-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:13px 20px;border-radius:999px;font-size:14px;font-weight:800;transition:.2s}.guide-btn:hover{transform:translateY(-2px)}.guide-btn-primary{background:#fff;color:#0d4e96;box-shadow:0 14px 28px rgba(0,0,0,.18)}.guide-btn-light{background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.25)}.guide-hero-card{background:rgba(255,255,255,.96);border-radius:28px;padding:18px;color:#111;box-shadow:0 30px 70px rgba(0,0,0,.25);border:1px solid rgba(255,255,255,.5)}.guide-card-top{display:flex;gap:7px;margin-bottom:16px}.guide-card-top span{width:11px;height:11px;border-radius:50%;background:#dbe5f0}.guide-card-top span:first-child{background:#ff6b6b}.guide-card-top span:nth-child(2){background:#ffd166}.guide-card-top span:nth-child(3){background:#06d6a0}.guide-search-demo{display:flex;gap:14px;align-items:center;background:#f4f7fb;border:1px solid #e5edf7;padding:14px;border-radius:18px;margin-bottom:16px}.guide-search-demo i{font-size:28px;color:#0d4e96}.guide-search-demo strong{display:block;font-size:15px}.guide-search-demo small{display:block;color:#777;margin-top:3px}.guide-mini-steps{display:grid;gap:10px}.guide-mini-steps div{display:flex;align-items:center;gap:12px;border:1px solid #edf1f5;border-radius:16px;padding:12px;background:#fff}.guide-mini-steps .active{border-color:#0d4e96;box-shadow:0 10px 24px rgba(13,78,150,.12)}.guide-mini-steps b{display:grid;place-items:center;width:34px;height:34px;border-radius:12px;background:#eef5ff;color:#0d4e96;font-size:12px}.guide-mini-steps span{font-weight:700;font-size:13px;color:#333}.guide-quick{margin-top:-28px;position:relative;z-index:2}.guide-quick-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}.guide-quick-card{background:#fff;border:1px solid #e8edf5;border-radius:20px;padding:18px;box-shadow:0 12px 30px rgba(13,78,150,.08);transition:.2s}.guide-quick-card:hover{transform:translateY(-4px);border-color:#0d4e96}.guide-quick-card i{font-size:30px;color:#0d4e96}.guide-quick-card strong{display:block;margin:12px 0 5px;font-size:15px;color:#111}.guide-quick-card span{font-size:12px;color:#777;line-height:1.5}.guide-section{padding:64px 0}.guide-section-head{text-align:center;max-width:720px;margin:0 auto 30px}.guide-section-head h2,.guide-video-copy h2,.guide-faq-title h2,.guide-support h2{font-size:32px;line-height:1.25;font-weight:900;color:#101828;margin:14px 0 10px}.guide-section-head p,.guide-faq-title p{color:#667085;line-height:1.7}.guide-tabs input{display:none}.guide-tab-buttons{display:flex;justify-content:center;gap:10px;margin-bottom:24px}.guide-tab-buttons label{display:flex;align-items:center;gap:8px;padding:12px 18px;border-radius:999px;background:#fff;border:1px solid #dfe7f2;color:#445;font-weight:800;font-size:14px;cursor:pointer;transition:.2s}#tab-jobseeker:checked~.guide-tab-buttons label[for="tab-jobseeker"],#tab-employer:checked~.guide-tab-buttons label[for="tab-employer"]{background:#0d4e96;color:#fff;border-color:#0d4e96;box-shadow:0 12px 28px rgba(13,78,150,.18)}.guide-tab-content{display:none;grid-template-columns:1fr 330px;gap:24px;align-items:start}.guide-content-jobseeker{display:grid}#tab-employer:checked~.guide-content-jobseeker{display:none}#tab-employer:checked~.guide-content-employer{display:grid}.guide-timeline{display:grid;gap:14px}.guide-step{display:grid;grid-template-columns:56px 1fr;gap:14px;background:#fff;border:1px solid #e8edf5;border-radius:22px;padding:18px;box-shadow:0 8px 24px rgba(16,24,40,.04);transition:.2s}.guide-step:hover{transform:translateY(-2px);border-color:#b9d5f6}.guide-step-no{width:48px;height:48px;border-radius:16px;background:linear-gradient(135deg,#0d4e96,#1f7bd6);color:#fff;display:grid;place-items:center;font-weight:900}.guide-step-body h3{font-size:17px;color:#111;margin-bottom:7px}.guide-step-body p{font-size:14px;color:#667085;line-height:1.7}.guide-note-card{position:sticky;top:90px;background:linear-gradient(180deg,#ffffff,#eef5ff);border:1px solid #dbeafe;border-radius:24px;padding:24px;box-shadow:0 16px 40px rgba(13,78,150,.1)}.guide-note-card.employer{background:linear-gradient(180deg,#ffffff,#fff7ed);border-color:#fed7aa}.guide-note-card i{width:52px;height:52px;border-radius:18px;background:#0d4e96;color:#fff;display:grid;place-items:center;font-size:28px;margin-bottom:16px}.guide-note-card h3{font-size:20px;margin-bottom:8px;color:#111}.guide-note-card p{font-size:14px;line-height:1.7;color:#667085}.guide-video-section{background:linear-gradient(135deg,#0d4e96,#102a56);padding:68px 0;color:#fff}.guide-video-grid{display:grid;grid-template-columns:.85fr 1.15fr;gap:34px;align-items:center}.guide-video-copy p{color:rgba(255,255,255,.82);line-height:1.8;margin-bottom:18px}.guide-video-copy h2{color:#fff}.guide-video-list{display:grid;gap:10px}.guide-video-list button{display:flex;align-items:center;gap:10px;border:1px solid rgba(255,255,255,.18);background:rgba(255,255,255,.08);color:#fff;border-radius:14px;padding:13px 14px;font-family:inherit;font-weight:800;text-align:left;cursor:pointer}.guide-video-list button.active{background:#fff;color:#0d4e96}.guide-video-frame{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:26px;padding:14px;box-shadow:0 28px 64px rgba(0,0,0,.28)}.guide-video-screen{position:relative;padding-top:56.25%;border-radius:18px;overflow:hidden;background:#000}.guide-video-screen iframe{position:absolute;inset:0;width:100%;height:100%;border:0}.guide-video-caption{display:flex;gap:8px;align-items:flex-start;color:rgba(255,255,255,.8);font-size:12px;line-height:1.5;margin-top:12px}.guide-feature-section{background:#fff}.guide-feature-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}.guide-feature-card{background:#f8fbff;border:1px solid #e5edf7;border-radius:22px;padding:22px;transition:.2s}.guide-feature-card:hover{transform:translateY(-4px);box-shadow:0 18px 36px rgba(13,78,150,.1);background:#fff}.guide-feature-card i{width:48px;height:48px;border-radius:16px;background:#eef5ff;color:#0d4e96;display:grid;place-items:center;font-size:26px;margin-bottom:14px}.guide-feature-card h3{font-size:17px;color:#111;margin-bottom:8px}.guide-feature-card p{font-size:13px;color:#667085;line-height:1.7}.guide-faq{padding:64px 0;background:#f4f7fb}.guide-faq-grid{display:grid;grid-template-columns:.8fr 1.2fr;gap:34px}.guide-faq-list{display:grid;gap:12px}.guide-faq-list details{background:#fff;border:1px solid #e6edf5;border-radius:18px;padding:0 18px;box-shadow:0 8px 24px rgba(16,24,40,.04)}.guide-faq-list summary{cursor:pointer;font-weight:800;color:#111;padding:18px 0;list-style:none}.guide-faq-list summary::-webkit-details-marker{display:none}.guide-faq-list p{border-top:1px solid #edf2f7;padding:14px 0 18px;color:#667085;font-size:14px;line-height:1.7}.guide-support{padding:54px 0;background:linear-gradient(135deg,#844404,#0d4e96);color:#fff}.guide-support-inner{display:flex;align-items:center;justify-content:space-between;gap:30px}.guide-support h2{color:#fff;max-width:620px}.guide-support p{color:rgba(255,255,255,.82);line-height:1.7}.guide-support-cards{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;min-width:430px}.guide-support-cards a{background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:18px;padding:18px;color:#fff}.guide-support-cards i{font-size:28px}.guide-support-cards span{display:block;font-size:12px;color:rgba(255,255,255,.75);margin:10px 0 4px}.guide-support-cards strong{font-size:15px}@media(max-width:1024px){.guide-hero-inner,.guide-video-grid,.guide-faq-grid{grid-template-columns:1fr}.guide-quick-grid,.guide-feature-grid{grid-template-columns:repeat(2,1fr)}.guide-tab-content{grid-template-columns:1fr}.guide-note-card{position:relative;top:auto}.guide-support-inner{flex-direction:column;align-items:flex-start}.guide-support-cards{min-width:0;width:100%}}@media(max-width:768px){.guide-container{padding:0 16px}.guide-hero{padding:48px 0}.guide-hero h1{font-size:34px}.guide-section,.guide-faq{padding:46px 0}.guide-section-head h2,.guide-video-copy h2,.guide-faq-title h2,.guide-support h2{font-size:26px}.guide-tab-buttons{flex-direction:column}.guide-tab-buttons label{justify-content:center}.guide-step{grid-template-columns:1fr}.guide-step-no{width:44px;height:44px}.guide-video-section{padding:48px 0}.guide-support-cards{grid-template-columns:1fr}.guide-hero-card{border-radius:22px}}@media(max-width:520px){.guide-hero h1{font-size:28px}.guide-hero p{font-size:14px}.guide-hero-actions{flex-direction:column}.guide-btn{width:100%}.guide-quick-grid,.guide-feature-grid{grid-template-columns:1fr}.guide-quick{margin-top:-18px}.guide-section-head h2,.guide-video-copy h2,.guide-faq-title h2,.guide-support h2{font-size:22px}.guide-step{padding:15px;border-radius:18px}.guide-mini-steps div{padding:10px}.guide-video-frame{padding:10px;border-radius:20px}.guide-support{padding:42px 0}.float-btn{width:44px;height:44px;right:16px;bottom:16px}}
/* Responsive hardening for the shared header/footer layout */
.guide-page *{box-sizing:border-box}
.guide-page img,.guide-page iframe{max-width:100%}
.guide-page a,.guide-page button{min-width:0}
.guide-tabs{width:100%;overflow:hidden}
.guide-tab-content{min-width:0}
.guide-timeline,.guide-note-card,.guide-video-frame,.guide-feature-card,.guide-faq-list details{min-width:0}
.guide-step-body h3,.guide-step-body p,.guide-feature-card h3,.guide-feature-card p,.guide-faq-list summary,.guide-faq-list p{overflow-wrap:anywhere}
@media(max-width:900px){
  .guide-hero-inner{gap:28px}
  .guide-hero-card{max-width:620px;width:100%;margin:0 auto}
  .guide-video-list{grid-template-columns:repeat(2,minmax(0,1fr))}
}
@media(max-width:640px){
  .guide-hero{padding:38px 0 48px}
  .guide-hero h1{font-size:30px;letter-spacing:0}
  .guide-search-demo{align-items:flex-start}
  .guide-tab-buttons{gap:8px}
  .guide-tab-buttons label{width:100%;font-size:13px;padding:11px 14px}
  .guide-video-list{grid-template-columns:1fr}
  .guide-support-cards a{padding:15px}
}
@media(max-width:420px){
  .guide-container{padding:0 12px}
  .guide-hero h1{font-size:26px}
  .guide-kicker,.guide-label{font-size:12px;padding:7px 10px}
  .guide-hero-card,.guide-note-card{padding:14px;border-radius:18px}
  .guide-search-demo{gap:10px;padding:12px;border-radius:14px}
  .guide-mini-steps div{gap:10px}
  .guide-mini-steps b{width:30px;height:30px;border-radius:10px;flex:0 0 auto}
  .guide-step-no{width:40px;height:40px;border-radius:13px}
  .guide-support-cards strong{font-size:14px}
}
</style>

<main class="guide-page">
  <section class="guide-hero">
    <div class="guide-hero-bg guide-hero-bg-1"></div>
    <div class="guide-hero-bg guide-hero-bg-2"></div>
    <div class="guide-container guide-hero-inner">
      <div class="guide-hero-content">
        <div class="guide-kicker"><i class="ti ti-school"></i> Trung tâm hướng dẫn</div>
        <h1>Hướng dẫn sử dụng <span>Cổng thông tin việc làm</span></h1>
        <p>Tra cứu việc làm, tạo hồ sơ, nộp CV và kết nối nhà tuyển dụng dễ dàng hơn qua các hướng dẫn dạng văn bản hoặc video trực quan.</p>
        <div class="guide-hero-actions">
          <a href="#guide-text" class="guide-btn guide-btn-primary"><i class="ti ti-book-2"></i> Xem hướng dẫn</a>
          <a href="#guide-video" class="guide-btn guide-btn-light"><i class="ti ti-player-play"></i> Xem video</a>
        </div>
      </div>
      <div class="guide-hero-card">
        <div class="guide-card-top">
          <span></span><span></span><span></span>
        </div>
        <div class="guide-search-demo">
          <i class="ti ti-search"></i>
          <div>
            <strong>Tìm việc nhanh</strong>
            <small>Nhập vị trí, ngành nghề hoặc công ty</small>
          </div>
        </div>
        <div class="guide-mini-steps">
          <div class="active"><b>01</b><span>Đăng ký tài khoản</span></div>
          <div><b>02</b><span>Tạo hồ sơ / CV</span></div>
          <div><b>03</b><span>Tìm và ứng tuyển</span></div>
          <div><b>04</b><span>Theo dõi phản hồi</span></div>
        </div>
      </div>
    </div>
  </section>

  <section class="guide-quick">
    <div class="guide-container guide-quick-grid">
      <a href="#student-guide" class="guide-quick-card">
        <i class="ti ti-user-search"></i>
        <strong>Người tìm việc</strong>
        <span>Cách tìm việc, tạo CV, nộp hồ sơ</span>
      </a>
      <a href="#employer-guide" class="guide-quick-card">
        <i class="ti ti-building-store"></i>
        <strong>Nhà tuyển dụng</strong>
        <span>Cách đăng tin, quản lý ứng viên</span>
      </a>
      <a href="#guide-video" class="guide-quick-card">
        <i class="ti ti-video"></i>
        <strong>Video hướng dẫn</strong>
        <span>Xem thao tác trực quan từng bước</span>
      </a>
      <a href="#faq" class="guide-quick-card">
        <i class="ti ti-help-circle"></i>
        <strong>Câu hỏi thường gặp</strong>
        <span>Giải đáp nhanh các vướng mắc</span>
      </a>
    </div>
  </section>

  <section class="guide-section" id="guide-text">
    <div class="guide-container">
      <div class="guide-section-head">
        <span class="guide-label">Hướng dẫn dạng văn bản</span>
        <h2>Bắt đầu sử dụng chỉ trong vài bước</h2>
        <p>Chọn nhóm đối tượng phù hợp và làm theo các bước bên dưới để sử dụng cổng thông tin hiệu quả.</p>
      </div>

      <div class="guide-tabs">
        <input type="radio" name="guideTab" id="tab-jobseeker" checked>
        <input type="radio" name="guideTab" id="tab-employer">
        <div class="guide-tab-buttons">
          <label for="tab-jobseeker"><i class="ti ti-user"></i> Người tìm việc</label>
          <label for="tab-employer"><i class="ti ti-building"></i> Nhà tuyển dụng</label>
        </div>

        <div class="guide-tab-content guide-content-jobseeker" id="student-guide">
          <div class="guide-timeline">
            <div class="guide-step">
              <div class="guide-step-no">01</div>
              <div class="guide-step-body">
                <h3>Đăng ký hoặc đăng nhập tài khoản</h3>
                <p>Người dùng có thể đăng nhập bằng tài khoản hệ thống hoặc Google để lưu hồ sơ, quản lý CV và theo dõi các việc làm đã ứng tuyển.</p>
              </div>
            </div>
            <div class="guide-step">
              <div class="guide-step-no">02</div>
              <div class="guide-step-body">
                <h3>Cập nhật thông tin cá nhân</h3>
                <p>Bổ sung họ tên, số điện thoại, email, ngành nghề quan tâm, kinh nghiệm và kỹ năng để nhà tuyển dụng dễ dàng đánh giá hồ sơ.</p>
              </div>
            </div>
            <div class="guide-step">
              <div class="guide-step-no">03</div>
              <div class="guide-step-body">
                <h3>Tạo CV online hoặc tải CV có sẵn</h3>
                <p>Sử dụng công cụ tạo CV để chọn mẫu, nhập nội dung, tùy chỉnh giao diện hoặc tải lên CV PDF/Word đã chuẩn bị trước.</p>
              </div>
            </div>
            <div class="guide-step">
              <div class="guide-step-no">04</div>
              <div class="guide-step-body">
                <h3>Tìm kiếm và ứng tuyển việc làm</h3>
                <p>Lọc việc làm theo từ khóa, ngành nghề, địa điểm, mức lương. Sau đó bấm “Ứng tuyển” và chọn CV phù hợp để gửi hồ sơ.</p>
              </div>
            </div>
            <div class="guide-step">
              <div class="guide-step-no">05</div>
              <div class="guide-step-body">
                <h3>Theo dõi trạng thái hồ sơ</h3>
                <p>Kiểm tra danh sách việc đã ứng tuyển, phản hồi từ nhà tuyển dụng và cập nhật hồ sơ khi có thay đổi mới.</p>
              </div>
            </div>
          </div>
          <aside class="guide-note-card">
            <i class="ti ti-bulb"></i>
            <h3>Mẹo nhỏ</h3>
            <p>Hồ sơ có ảnh đại diện, số điện thoại chính xác và CV rõ ràng sẽ giúp tăng khả năng được nhà tuyển dụng liên hệ.</p>
          </aside>
        </div>

        <div class="guide-tab-content guide-content-employer" id="employer-guide">
          <div class="guide-timeline">
            <div class="guide-step">
              <div class="guide-step-no">01</div>
              <div class="guide-step-body">
                <h3>Tạo tài khoản nhà tuyển dụng</h3>
                <p>Đăng ký thông tin doanh nghiệp, người phụ trách tuyển dụng, số điện thoại, email và địa chỉ làm việc.</p>
              </div>
            </div>
            <div class="guide-step">
              <div class="guide-step-no">02</div>
              <div class="guide-step-body">
                <h3>Cập nhật hồ sơ doanh nghiệp</h3>
                <p>Thêm logo, lĩnh vực hoạt động, quy mô nhân sự, mô tả ngắn và website/fanpage để tăng độ tin cậy của tin tuyển dụng.</p>
              </div>
            </div>
            <div class="guide-step">
              <div class="guide-step-no">03</div>
              <div class="guide-step-body">
                <h3>Đăng tin tuyển dụng</h3>
                <p>Nhập vị trí tuyển dụng, số lượng, mức lương, địa điểm, mô tả công việc, yêu cầu ứng viên và hạn nộp hồ sơ.</p>
              </div>
            </div>
            <div class="guide-step">
              <div class="guide-step-no">04</div>
              <div class="guide-step-body">
                <h3>Quản lý ứng viên</h3>
                <p>Xem danh sách hồ sơ ứng tuyển, lọc theo vị trí, tải CV và cập nhật trạng thái xử lý hồ sơ.</p>
              </div>
            </div>
            <div class="guide-step">
              <div class="guide-step-no">05</div>
              <div class="guide-step-body">
                <h3>Kết nối với nhà trường</h3>
                <p>Liên hệ bộ phận phụ trách để phối hợp tuyển dụng, đặt hàng đào tạo hoặc tham gia các chương trình kết nối việc làm.</p>
              </div>
            </div>
          </div>
          <aside class="guide-note-card employer">
            <i class="ti ti-speakerphone"></i>
            <h3>Gợi ý đăng tin</h3>
            <p>Tin tuyển dụng nên ghi rõ mức lương, quyền lợi, địa điểm làm việc và thời hạn tuyển để tăng tỷ lệ ứng tuyển phù hợp.</p>
          </aside>
        </div>
      </div>
    </div>
  </section>

  <section class="guide-video-section" id="guide-video">
    <div class="guide-container guide-video-grid">
      <div class="guide-video-copy">
        <span class="guide-label light">Hướng dẫn qua video</span>
        <h2>Xem thao tác trực quan, dễ làm theo</h2>
        <p>Người dùng có thể xem video hướng dẫn tổng quan hoặc từng chức năng riêng: đăng ký tài khoản, tạo CV, ứng tuyển việc làm và quản lý hồ sơ.</p>
        <div class="guide-video-list">
          <button class="active"><i class="ti ti-player-play"></i> Tổng quan hệ thống</button>
          <button><i class="ti ti-file-cv"></i> Tạo và xuất CV</button>
          <button><i class="ti ti-send"></i> Ứng tuyển việc làm</button>
        </div>
      </div>
      <div class="guide-video-frame">
        <div class="guide-video-screen">
          <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Video hướng dẫn sử dụng cổng thông tin việc làm" allowfullscreen></iframe>
        </div>
        <div class="guide-video-caption">
          <i class="ti ti-info-circle"></i>
          <span>Có thể thay link YouTube trong thẻ iframe bằng video hướng dẫn chính thức của đơn vị.</span>
        </div>
      </div>
    </div>
  </section>

  <section class="guide-section guide-feature-section">
    <div class="guide-container">
      <div class="guide-section-head">
        <span class="guide-label">Chức năng chính</span>
        <h2>Người dùng có thể làm gì trên hệ thống?</h2>
      </div>
      <div class="guide-feature-grid">
        <div class="guide-feature-card"><i class="ti ti-search"></i><h3>Tìm việc thông minh</h3><p>Tìm theo ngành nghề, địa điểm, mức lương, loại hình công việc và từ khóa.</p></div>
        <div class="guide-feature-card"><i class="ti ti-file-cv"></i><h3>Tạo CV online</h3><p>Tạo, chỉnh sửa, lưu và xuất CV để sử dụng khi ứng tuyển.</p></div>
        <div class="guide-feature-card"><i class="ti ti-bell-ringing"></i><h3>Nhận thông báo</h3><p>Cập nhật việc làm mới, phản hồi hồ sơ và các thông báo quan trọng.</p></div>
        <div class="guide-feature-card"><i class="ti ti-chart-bar"></i><h3>Quản lý hồ sơ</h3><p>Theo dõi trạng thái ứng tuyển và lịch sử tương tác với nhà tuyển dụng.</p></div>
      </div>
    </div>
  </section>

  <section class="guide-faq" id="faq">
    <div class="guide-container guide-faq-grid">
      <div class="guide-faq-title">
        <span class="guide-label">FAQ</span>
        <h2>Câu hỏi thường gặp</h2>
        <p>Một số thắc mắc phổ biến khi người dùng bắt đầu sử dụng cổng thông tin việc làm.</p>
      </div>
      <div class="guide-faq-list">
        <details open>
          <summary>Tôi có cần tài khoản để xem việc làm không?</summary>
          <p>Người dùng có thể xem danh sách việc làm công khai. Tuy nhiên, để ứng tuyển, lưu việc làm hoặc quản lý CV, bạn nên đăng ký tài khoản.</p>
        </details>
        <details>
          <summary>Làm sao để cập nhật CV sau khi đã tạo?</summary>
          <p>Vào khu vực quản lý tài khoản, chọn mục CV/Hồ sơ, mở CV cần chỉnh sửa và lưu lại phiên bản mới.</p>
        </details>
        <details>
          <summary>Nhà tuyển dụng có thể đăng nhiều tin không?</summary>
          <p>Có. Nhà tuyển dụng có thể tạo nhiều bài đăng tuyển dụng và quản lý ứng viên theo từng vị trí.</p>
        </details>
        <details>
          <summary>Tôi quên mật khẩu thì xử lý thế nào?</summary>
          <p>Chọn chức năng quên mật khẩu tại màn hình đăng nhập, nhập email đã đăng ký và làm theo hướng dẫn khôi phục.</p>
        </details>
      </div>
    </div>
  </section>

  <section class="guide-support">
    <div class="guide-container guide-support-inner">
      <div>
        <span class="guide-label light">Cần hỗ trợ thêm?</span>
        <h2>Liên hệ bộ phận hỗ trợ cổng thông tin việc làm</h2>
        <p>Đội ngũ quản trị sẽ hỗ trợ người tìm việc và nhà tuyển dụng trong quá trình sử dụng hệ thống.</p>
      </div>
      <div class="guide-support-cards">
        <a href="tel:02603868686"><i class="ti ti-phone"></i><span>Hotline</span><strong>0260 3868 686</strong></a>
        <a href="mailto:vieclam@cdkt.edu.vn"><i class="ti ti-mail"></i><span>Email</span><strong>vieclam@cdkt.edu.vn</strong></a>
      </div>
    </div>
  </section>
</main>

<?php require "footer.php"; ?>