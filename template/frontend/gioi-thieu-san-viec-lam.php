<?php require "header.php"; ?>

<style>
.jobfair-page{background:#f4f7fb;color:#102033;overflow:hidden}.jobfair-page *{box-sizing:border-box}.jobfair-container{max-width:none;margin:0 auto;padding:0 20px}.jobfair-hero{position:relative;isolation:isolate;background:linear-gradient(135deg,#0d4e96 0%,#11385f 52%,#8a4a08 100%);color:#fff;padding:76px 0 64px}.jobfair-hero:before{content:"";position:absolute;inset:0;background:radial-gradient(circle at 15% 18%,rgba(255,255,255,.22),transparent 28%),radial-gradient(circle at 86% 16%,rgba(255,209,128,.2),transparent 26%);z-index:-1}.jobfair-hero:after{content:"";position:absolute;left:-6%;right:-6%;bottom:-58px;height:110px;background:#f4f7fb;border-radius:50% 50% 0 0/100% 100% 0 0;z-index:-1}.jobfair-hero-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:42px;align-items:center}.jobfair-kicker{display:inline-flex;align-items:center;gap:8px;border:1px solid rgba(255,255,255,.25);background:rgba(255,255,255,.13);backdrop-filter:blur(10px);border-radius:999px;padding:8px 14px;font-size:13px;font-weight:800;margin-bottom:18px}.jobfair-hero h1{font-size:48px;line-height:1.1;font-weight:900;letter-spacing:-1px;margin:0 0 16px}.jobfair-hero h1 span{color:#ffe2a3}.jobfair-hero p{font-size:16px;line-height:1.85;color:rgba(255,255,255,.86);max-width:690px}.jobfair-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:28px}.jobfair-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:999px;padding:13px 20px;font-size:14px;font-weight:850;transition:.2s}.jobfair-btn.primary{background:#fff;color:#0d4e96;box-shadow:0 16px 34px rgba(0,0,0,.18)}.jobfair-btn.ghost{border:1px solid rgba(255,255,255,.26);background:rgba(255,255,255,.1);color:#fff}.jobfair-btn:hover{transform:translateY(-2px)}.jobfair-showcase{position:relative;min-height:430px}.jobfair-orbit{position:absolute;inset:0;border:1px solid rgba(255,255,255,.15);border-radius:32px;background:rgba(255,255,255,.1);box-shadow:0 28px 80px rgba(0,0,0,.22);backdrop-filter:blur(12px);overflow:hidden}.jobfair-orbit:before{content:"";position:absolute;inset:22px;border:1px dashed rgba(255,255,255,.26);border-radius:26px}.jobfair-stage{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:210px;height:210px;border-radius:50%;background:#fff;color:#0d4e96;display:grid;place-items:center;text-align:center;padding:24px;box-shadow:0 24px 55px rgba(0,0,0,.24)}.jobfair-stage i{font-size:46px}.jobfair-stage strong{display:block;font-size:21px;margin-top:8px}.jobfair-stage span{display:block;font-size:12px;color:#64748b;margin-top:4px;line-height:1.5}.jobfair-floating{position:absolute;display:flex;align-items:center;gap:10px;background:#fff;color:#102033;border-radius:18px;padding:13px 14px;box-shadow:0 18px 40px rgba(0,0,0,.18);min-width:188px}.jobfair-floating i{width:38px;height:38px;border-radius:14px;background:#eef5ff;color:#0d4e96;display:grid;place-items:center;font-size:22px}.jobfair-floating b{display:block;font-size:13px}.jobfair-floating small{display:block;color:#64748b;font-size:11px;margin-top:2px}.float-a{left:18px;top:30px}.float-b{right:12px;top:84px}.float-c{left:28px;bottom:44px}.float-d{right:34px;bottom:24px}.jobfair-main{position:relative;z-index:2;padding:58px 0 64px}.jobfair-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:26px}.jobfair-stat{background:#fff;border:1px solid #e6edf7;border-radius:20px;padding:20px;box-shadow:0 14px 36px rgba(13,78,150,.07)}.jobfair-stat b{display:block;font-size:28px;color:#0d4e96}.jobfair-stat span{display:block;font-size:13px;color:#667085;margin-top:5px;line-height:1.5}.section-head{max-width:760px;margin:0 auto 28px;text-align:center}.section-label{display:inline-flex;align-items:center;gap:8px;background:#eef5ff;color:#0d4e96;border:1px solid #d7e8ff;border-radius:999px;padding:7px 13px;font-size:12px;font-weight:850}.section-head h2{font-size:34px;line-height:1.2;margin:14px 0 10px;color:#101828}.section-head p{font-size:14px;color:#667085;line-height:1.75}.audience-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}.audience-card{position:relative;background:#fff;border:1px solid #e6edf7;border-radius:26px;padding:26px;box-shadow:0 18px 46px rgba(13,78,150,.08);overflow:hidden}.audience-card:before{content:"";position:absolute;width:210px;height:210px;border-radius:50%;right:-90px;top:-90px;background:#eef5ff}.audience-card.employer:before{background:#fff2dc}.audience-card>*{position:relative}.audience-icon{width:56px;height:56px;border-radius:18px;background:#0d4e96;color:#fff;display:grid;place-items:center;font-size:30px;margin-bottom:16px}.audience-card.employer .audience-icon{background:#8a4a08}.audience-card h3{font-size:22px;margin-bottom:10px}.audience-card p{font-size:14px;color:#667085;line-height:1.75;margin-bottom:16px}.feature-list{display:grid;gap:10px}.feature-list li{display:flex;gap:10px;align-items:flex-start;font-size:13px;color:#334155;line-height:1.55}.feature-list i{color:#16a34a;font-size:18px;flex:0 0 auto}.process-section{padding:62px 0;background:#fff}.process-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}.process-card{background:#f8fbff;border:1px solid #e4edf8;border-radius:22px;padding:20px;transition:.2s}.process-card:hover{transform:translateY(-4px);box-shadow:0 16px 34px rgba(13,78,150,.08);background:#fff}.process-no{width:42px;height:42px;border-radius:15px;background:linear-gradient(135deg,#0d4e96,#2477c7);color:#fff;display:grid;place-items:center;font-weight:900;margin-bottom:14px}.process-card h3{font-size:16px;margin-bottom:8px}.process-card p{font-size:13px;color:#667085;line-height:1.65}.experience-section{padding:64px 0;background:#f4f7fb}.experience-grid{display:grid;grid-template-columns:.88fr 1.12fr;gap:24px;align-items:stretch}.event-card{background:linear-gradient(135deg,#102033,#0d4e96);color:#fff;border-radius:28px;padding:28px;min-height:100%;box-shadow:0 20px 52px rgba(13,78,150,.18);position:relative;overflow:hidden}.event-card:before{content:"";position:absolute;right:-80px;top:-90px;width:230px;height:230px;border-radius:50%;background:rgba(255,255,255,.12)}.event-card>*{position:relative}.event-date{display:inline-flex;gap:8px;align-items:center;border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.12);border-radius:999px;padding:8px 12px;font-size:12px;font-weight:800;margin-bottom:18px}.event-card h2{font-size:30px;line-height:1.2;margin-bottom:10px}.event-card p{color:rgba(255,255,255,.78);line-height:1.75;font-size:14px}.event-meta{display:grid;gap:10px;margin-top:22px}.event-meta div{display:flex;gap:10px;align-items:center;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.13);padding:12px;border-radius:16px;font-size:13px}.event-meta i{font-size:21px;color:#ffd08a}.timeline{display:grid;gap:12px}.timeline-item{display:grid;grid-template-columns:52px 1fr;gap:14px;background:#fff;border:1px solid #e6edf7;border-radius:20px;padding:16px;box-shadow:0 10px 28px rgba(15,23,42,.05)}.timeline-time{height:52px;border-radius:16px;background:#fff7ed;color:#8a4a08;display:grid;place-items:center;font-weight:900;font-size:13px}.timeline-item h3{font-size:16px;margin-bottom:5px}.timeline-item p{font-size:13px;color:#667085;line-height:1.6}.cta-band{padding:54px 0;background:linear-gradient(135deg,#8a4a08,#0d4e96);color:#fff}.cta-inner{display:flex;align-items:center;justify-content:space-between;gap:28px}.cta-inner h2{font-size:30px;line-height:1.25;margin-bottom:8px}.cta-inner p{font-size:14px;color:rgba(255,255,255,.78);line-height:1.7;max-width:680px}.cta-actions{display:flex;gap:10px;flex-wrap:wrap;flex:0 0 auto}.jobfair-page a,.jobfair-page button{min-width:0}.audience-card,.process-card,.event-card,.timeline-item,.jobfair-stat{min-width:0}.audience-card p,.process-card p,.timeline-item p,.event-card p,.feature-list li{overflow-wrap:anywhere}@media(max-width:1024px){.jobfair-hero-grid,.experience-grid{grid-template-columns:1fr}.jobfair-showcase{max-width:720px;width:100%;margin:0 auto}.jobfair-stats,.process-grid{grid-template-columns:repeat(2,1fr)}.audience-grid{grid-template-columns:1fr}.cta-inner{flex-direction:column;align-items:flex-start}.cta-actions{width:100%}}@media(max-width:760px){.jobfair-container{padding:0 16px}.jobfair-hero{padding:52px 0 50px}.jobfair-hero h1{font-size:36px;letter-spacing:0}.jobfair-hero p{font-size:14px}.jobfair-showcase{min-height:auto}.jobfair-orbit{position:relative;inset:auto;padding:18px;display:grid;gap:12px;border-radius:24px}.jobfair-orbit:before{display:none}.jobfair-stage{position:relative;left:auto;top:auto;transform:none;width:100%;height:auto;border-radius:22px}.jobfair-floating{position:relative;left:auto;right:auto;top:auto;bottom:auto;width:100%;min-width:0}.jobfair-main{padding:44px 0 48px}.section-head h2,.event-card h2,.cta-inner h2{font-size:27px}.process-section,.experience-section{padding:46px 0}.process-grid,.jobfair-stats{grid-template-columns:1fr}.audience-card,.event-card{border-radius:22px;padding:20px}.timeline-item{grid-template-columns:1fr}.timeline-time{width:52px}.cta-actions .jobfair-btn{width:100%}}@media(max-width:480px){.jobfair-container{padding:0 12px}.jobfair-hero h1{font-size:29px}.jobfair-kicker,.section-label{font-size:12px;padding:7px 10px}.jobfair-actions{flex-direction:column}.jobfair-btn{width:100%}.jobfair-stat{padding:16px;border-radius:16px}.jobfair-stat b{font-size:24px}.audience-icon{width:50px;height:50px;border-radius:16px}.audience-card h3{font-size:20px}.process-card{padding:17px;border-radius:18px}.event-meta div{align-items:flex-start}.cta-band{padding:42px 0}}
</style>

<main class="jobfair-page">
  <section class="jobfair-hero">
    <div class="jobfair-container jobfair-hero-grid">
      <div>
        <div class="jobfair-kicker"><i class="ti ti-building-community"></i> Sàn việc làm Kon Tum College</div>
        <h1>Nơi sinh viên gặp đúng doanh nghiệp, doanh nghiệp gặp đúng nhân sự</h1>
        <p>Sàn việc làm là không gian kết nối tuyển dụng trực tiếp giữa nhà trường, sinh viên, cựu sinh viên và doanh nghiệp. Mọi hoạt động từ đăng tin, ứng tuyển, phỏng vấn đến ngày hội việc làm được tổ chức rõ ràng, hiện đại và dễ tiếp cận.</p>
        <div class="jobfair-actions">
          <a class="jobfair-btn primary" href="quan-ly-viec-lam.php"><i class="ti ti-search"></i> Khám phá việc làm</a>
          <a class="jobfair-btn ghost" href="lien-he.php"><i class="ti ti-handshake"></i> Đăng ký hợp tác</a>
        </div>
      </div>
      <div class="jobfair-showcase" aria-label="Mô hình kết nối sàn việc làm">
        <div class="jobfair-orbit">
          <div class="jobfair-stage"><div><i class="ti ti-network"></i><strong>Sàn việc làm</strong><span>Kết nối tuyển dụng đa chiều</span></div></div>
          <div class="jobfair-floating float-a"><i class="ti ti-school"></i><div><b>Nhà trường</b><small>Điều phối, xác thực, hỗ trợ</small></div></div>
          <div class="jobfair-floating float-b"><i class="ti ti-user-search"></i><div><b>Ứng viên</b><small>Tạo hồ sơ và ứng tuyển</small></div></div>
          <div class="jobfair-floating float-c"><i class="ti ti-building-skyscraper"></i><div><b>Doanh nghiệp</b><small>Đăng tin và phỏng vấn</small></div></div>
          <div class="jobfair-floating float-d"><i class="ti ti-calendar-event"></i><div><b>Ngày hội</b><small>Gặp gỡ trực tiếp tại trường</small></div></div>
        </div>
      </div>
    </div>
  </section>

  <section class="jobfair-main">
    <div class="jobfair-container">
      <div class="jobfair-stats">
        <div class="jobfair-stat"><b>500+</b><span>Tin tuyển dụng được cập nhật và kiểm duyệt</span></div>
        <div class="jobfair-stat"><b>100+</b><span>Doanh nghiệp, đơn vị đồng hành tuyển dụng</span></div>
        <div class="jobfair-stat"><b>5.000+</b><span>Sinh viên và ứng viên có thể tiếp cận cơ hội</span></div>
        <div class="jobfair-stat"><b>48h</b><span>Thời gian phản hồi và hỗ trợ thông tin tuyển dụng</span></div>
      </div>

      <div class="section-head">
        <span class="section-label"><i class="ti ti-sparkles"></i> Giá trị cốt lõi</span>
        <h2>Một điểm chạm cho toàn bộ hành trình tuyển dụng</h2>
        <p>Trang được thiết kế để sinh viên tìm thấy cơ hội phù hợp nhanh hơn, còn doanh nghiệp tiếp cận nguồn ứng viên đã được định hướng kỹ năng và nghề nghiệp.</p>
      </div>

      <div class="audience-grid">
        <article class="audience-card">
          <div class="audience-icon"><i class="ti ti-user-heart"></i></div>
          <h3>Dành cho sinh viên và người tìm việc</h3>
          <p>Không chỉ là danh sách việc làm, sàn hỗ trợ người học chuẩn bị hồ sơ, hiểu yêu cầu tuyển dụng và theo dõi quá trình ứng tuyển.</p>
          <ul class="feature-list">
            <li><i class="ti ti-circle-check"></i> Tìm việc theo ngành nghề, địa điểm, hình thức làm việc và mức kinh nghiệm.</li>
            <li><i class="ti ti-circle-check"></i> Tạo CV, cập nhật hồ sơ và gửi ứng tuyển tới doanh nghiệp phù hợp.</li>
            <li><i class="ti ti-circle-check"></i> Nhận thông báo sự kiện, lịch phỏng vấn và ngày hội tuyển dụng.</li>
          </ul>
        </article>

        <article class="audience-card employer">
          <div class="audience-icon"><i class="ti ti-building-factory-2"></i></div>
          <h3>Dành cho doanh nghiệp tuyển dụng</h3>
          <p>Doanh nghiệp có thể giới thiệu thương hiệu, đăng nhu cầu nhân sự và kết nối trực tiếp với ứng viên từ nhà trường.</p>
          <ul class="feature-list">
            <li><i class="ti ti-circle-check"></i> Đăng tin tuyển dụng, thực tập, việc làm bán thời gian và chương trình trainee.</li>
            <li><i class="ti ti-circle-check"></i> Tiếp cận nguồn ứng viên theo ngành học, kỹ năng và khu vực mong muốn.</li>
            <li><i class="ti ti-circle-check"></i> Đăng ký tham gia ngày hội việc làm, workshop và phỏng vấn tại trường.</li>
          </ul>
        </article>
      </div>
    </div>
  </section>

  <section class="process-section">
    <div class="jobfair-container">
      <div class="section-head">
        <span class="section-label"><i class="ti ti-route"></i> Quy trình hoạt động</span>
        <h2>Kết nối rõ ràng trong 4 bước</h2>
        <p>Mỗi bước được tối giản để sinh viên dễ sử dụng, doanh nghiệp dễ theo dõi và nhà trường dễ điều phối chất lượng.</p>
      </div>
      <div class="process-grid">
        <div class="process-card"><div class="process-no">01</div><h3>Doanh nghiệp gửi nhu cầu</h3><p>Thông tin vị trí, yêu cầu, quyền lợi và hình thức tuyển dụng được tiếp nhận qua hệ thống.</p></div>
        <div class="process-card"><div class="process-no">02</div><h3>Nhà trường kiểm duyệt</h3><p>Nội dung được xác thực để đảm bảo phù hợp với sinh viên và định hướng đào tạo.</p></div>
        <div class="process-card"><div class="process-no">03</div><h3>Ứng viên ứng tuyển</h3><p>Sinh viên tìm kiếm, lưu việc làm, gửi hồ sơ và nhận thông báo phản hồi.</p></div>
        <div class="process-card"><div class="process-no">04</div><h3>Kết nối phỏng vấn</h3><p>Lịch phỏng vấn, ngày hội việc làm và kết quả tuyển dụng được hỗ trợ theo từng đợt.</p></div>
      </div>
    </div>
  </section>

  <section class="experience-section">
    <div class="jobfair-container experience-grid">
      <article class="event-card">
        <div class="event-date"><i class="ti ti-calendar-star"></i> Ngày hội việc làm</div>
        <h2>Không gian tuyển dụng trực tiếp, năng động và có định hướng</h2>
        <p>Sàn việc làm không dừng ở môi trường trực tuyến. Các hoạt động kết nối trực tiếp như booth tuyển dụng, phỏng vấn nhanh, tư vấn CV và workshop kỹ năng giúp ứng viên hiểu rõ thị trường lao động hơn.</p>
        <div class="event-meta">
          <div><i class="ti ti-map-pin"></i> Khuôn viên Trường Cao đẳng Kon Tum</div>
          <div><i class="ti ti-users-group"></i> Sinh viên, cựu sinh viên và doanh nghiệp đối tác</div>
          <div><i class="ti ti-microphone-2"></i> Tư vấn nghề nghiệp, phỏng vấn thử, kết nối tuyển dụng</div>
        </div>
      </article>

      <div class="timeline">
        <div class="timeline-item"><div class="timeline-time">08:00</div><div><h3>Đón tiếp và mở gian hàng</h3><p>Doanh nghiệp giới thiệu vị trí tuyển dụng, chương trình thực tập và văn hóa làm việc.</p></div></div>
        <div class="timeline-item"><div class="timeline-time">09:30</div><div><h3>Workshop nghề nghiệp</h3><p>Chia sẻ kỹ năng viết CV, phỏng vấn, chuẩn bị hồ sơ và định hướng ngành nghề.</p></div></div>
        <div class="timeline-item"><div class="timeline-time">13:30</div><div><h3>Phỏng vấn nhanh</h3><p>Ứng viên trao đổi trực tiếp với doanh nghiệp, gửi hồ sơ và nhận phản hồi ban đầu.</p></div></div>
        <div class="timeline-item"><div class="timeline-time">16:00</div><div><h3>Tổng hợp kết nối</h3><p>Nhà trường ghi nhận nhu cầu tuyển dụng, hồ sơ quan tâm và kế hoạch hỗ trợ tiếp theo.</p></div></div>
      </div>
    </div>
  </section>

  <section class="cta-band">
    <div class="jobfair-container cta-inner">
      <div>
        <h2>Sẵn sàng tham gia sàn việc làm?</h2>
        <p>Sinh viên có thể bắt đầu bằng việc tìm kiếm vị trí phù hợp. Doanh nghiệp có thể liên hệ để đăng tin, tham gia ngày hội việc làm hoặc xây dựng chương trình hợp tác tuyển dụng.</p>
      </div>
      <div class="cta-actions">
        <a class="jobfair-btn primary" href="quan-ly-viec-lam.php"><i class="ti ti-briefcase"></i> Xem việc làm</a>
        <a class="jobfair-btn ghost" href="lien-he.php"><i class="ti ti-message-2"></i> Liên hệ ngay</a>
      </div>
    </div>
  </section>
</main>

<?php require "footer.php"; ?>