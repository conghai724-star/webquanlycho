<?php require "../header.php"; ?>

<!-- CANDIDATE DETAIL BODY -->
<style>
.candidate-page{background:#f4f5f6;padding:24px 20px 36px}
.cd-container{max-width:none;margin:0 auto}
.cd-breadcrumb{font-size:13px;color:#777;margin-bottom:14px;display:flex;gap:8px;flex-wrap:wrap}
.cd-breadcrumb a{color:#0d4e96;font-weight:700}
.cd-hero{background:linear-gradient(135deg,#0d4e96,#155fae 55%,#844404);border-radius:20px;padding:26px;color:#fff;position:relative;overflow:hidden}
.cd-hero::after{content:"";position:absolute;right:-70px;top:-70px;width:220px;height:220px;background:rgba(255,255,255,.12);border-radius:50%}
.cd-hero-inner{display:flex;gap:20px;align-items:center;position:relative;z-index:1}
.cd-avatar{width:110px;height:110px;border-radius:24px;background:#fff;color:#0d4e96;display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;flex-shrink:0;border:4px solid rgba(255,255,255,.35)}
.cd-hero h1{font-size:28px;line-height:1.3;margin-bottom:6px}
.cd-position{font-size:15px;opacity:.95;margin-bottom:12px}
.cd-tags{display:flex;flex-wrap:wrap;gap:8px}
.cd-tag{background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.28);border-radius:999px;padding:6px 10px;font-size:12px;font-weight:700}
.cd-actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}
.cd-btn{border:none;border-radius:12px;padding:11px 18px;font-size:14px;font-weight:800;cursor:pointer;display:inline-flex;align-items:center;gap:8px}
.cd-btn-primary{background:#fff;color:#0d4e96}
.cd-btn-blue{background:#0d4e96;color:#fff}
.cd-btn-outline{background:rgba(255,255,255,.12);color:#fff;border:1px solid rgba(255,255,255,.35)}
.cd-layout{display:grid;grid-template-columns:1fr 340px;gap:18px;margin-top:18px}
.cd-card{background:#fff;border:1px solid #e9eef5;border-radius:18px;padding:20px;box-shadow:0 4px 18px rgba(13,78,150,.06)}
.cd-card+.cd-card{margin-top:14px}
.cd-title{font-size:18px;font-weight:800;color:#111;margin-bottom:14px;display:flex;align-items:center;gap:8px}
.cd-title i{color:#0d4e96;font-size:20px}
.cd-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
.cd-info{background:#f8fafc;border:1px solid #edf2f7;border-radius:14px;padding:12px}
.cd-label{font-size:11px;color:#888;margin-bottom:5px}
.cd-value{font-size:13px;color:#222;font-weight:700;line-height:1.5}
.cd-content{font-size:14px;color:#444;line-height:1.8}
.cd-content ul{padding-left:18px}
.cd-content li{margin-bottom:7px}
.cd-timeline{display:flex;flex-direction:column;gap:14px}
.cd-time-item{border-left:3px solid #0d4e96;padding-left:14px}
.cd-time-item h4{font-size:14px;color:#111;margin-bottom:4px}
.cd-time-item span{font-size:12px;color:#777}
.cd-time-item p{font-size:13px;color:#555;line-height:1.7;margin-top:6px}
.cd-skill-list{display:flex;flex-wrap:wrap;gap:8px}
.cd-skill{background:#eef5ff;color:#0d4e96;border:1px solid #dbeafe;border-radius:999px;padding:7px 10px;font-size:12px;font-weight:700}
.cd-highlight{background:#eef5ff;border-left:4px solid #0d4e96;border-radius:14px;padding:14px;font-size:13px;line-height:1.7;color:#24415f}
.cd-file{display:flex;align-items:center;gap:10px;background:#f8fafc;border:1px dashed #b8c7d9;border-radius:14px;padding:14px}
.cd-file i{font-size:28px;color:#0d4e96}
.cd-file strong{font-size:13px;color:#111}
.cd-file p{font-size:12px;color:#777;margin-top:3px}
.news-slider{margin-top:22px}
.news-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
.news-controls{display:flex;gap:8px}
.news-btn{width:34px;height:34px;border-radius:50%;border:1px solid #dce5ef;background:#fff;color:#0d4e96;cursor:pointer}
.news-btn:hover{background:#0d4e96;color:#fff}
.news-wrap{overflow:hidden}
.news-track{display:flex;gap:14px;transition:transform .45s ease}
.news-card{min-width:calc((100% - 28px)/3);background:#fff;border:1px solid #e9eef5;border-radius:16px;overflow:hidden;transition:.2s;color:inherit}
.news-card:hover{border-color:#0d4e96;transform:translateY(-3px);box-shadow:0 8px 24px rgba(13,78,150,.1)}
.news-img{height:130px;background:linear-gradient(135deg,#eef5ff,#dbeafe);display:flex;align-items:center;justify-content:center;color:#0d4e96;font-size:34px}
.news-body{padding:14px}
.news-body h3{font-size:14px;color:#111;line-height:1.45;margin-bottom:8px}
.news-body p{font-size:12px;color:#777;line-height:1.6}
.news-dots{display:flex;gap:6px;justify-content:center;margin-top:14px}
.news-dot{width:8px;height:8px;border-radius:999px;border:none;background:#cbd5e1;cursor:pointer;padding:0}
.news-dot.active{width:24px;background:#0d4e96}
@media(max-width:1024px){.cd-layout{grid-template-columns:1fr}.news-card{min-width:calc((100% - 14px)/2)}}
@media(max-width:768px){.candidate-page{padding:18px 14px 28px}.cd-hero-inner{flex-direction:column;align-items:flex-start}.cd-hero h1{font-size:22px}.cd-grid{grid-template-columns:1fr}}
@media(max-width:480px){.candidate-page{padding:14px 12px 24px}.cd-actions{flex-direction:column}.cd-btn{width:100%;justify-content:center}.news-card{min-width:100%}}

/* ===== FEATURED CANDIDATES SIDEBAR ===== */
.cd-featured-candidates{display:flex;flex-direction:column;gap:10px}
.cd-candidate-mini{display:flex;gap:10px;align-items:center;padding:10px;border:1px solid #edf2f7;border-radius:14px;background:#f8fafc;transition:.2s}
.cd-candidate-mini:hover{border-color:#0d4e96;background:#eef5ff;transform:translateY(-1px)}
.cd-candidate-avatar{width:46px;height:46px;border-radius:50%;background:#0d4e96;color:#fff;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:800;flex-shrink:0;border:3px solid #fff;box-shadow:0 2px 8px rgba(13,78,150,.18)}
.cd-candidate-info h4{font-size:13px;color:#111;margin-bottom:3px;line-height:1.35}
.cd-candidate-info p{font-size:11px;color:#666;line-height:1.45;margin:0}
.cd-candidate-meta{display:flex;flex-wrap:wrap;gap:5px;margin-top:6px}
.cd-candidate-meta span{font-size:10px;color:#0d4e96;background:#fff;border:1px solid #dbeafe;border-radius:999px;padding:3px 7px;font-weight:700}


/* ===== CV DOWNLOAD ACTION ===== */
.cd-file-actions{display:flex;gap:8px;margin-top:12px;flex-wrap:wrap}
.cd-download-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;background:#0d4e96;color:#fff;border:none;border-radius:10px;padding:9px 12px;font-size:12px;font-weight:800;transition:.2s}
.cd-download-btn:hover{background:#083e78;transform:translateY(-1px)}
.cd-preview-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;background:#fff;color:#0d4e96;border:1px solid #dbeafe;border-radius:10px;padding:9px 12px;font-size:12px;font-weight:800;transition:.2s}
.cd-preview-btn:hover{background:#eef5ff}
@media(max-width:480px){.cd-file-actions a{width:100%}}

</style>

<main class="candidate-page">
  <div class="cd-container">
    <div class="cd-breadcrumb">
      <a href="/">Trang chủ</a><span>/</span><a href="#">Ứng viên</a><span>/</span><span>Chi tiết hồ sơ</span>
    </div>

    <section class="cd-hero">
      <div class="cd-hero-inner">
        <div class="cd-avatar">NL</div>
        <div>
          <h1>Nguyễn Thị Lan</h1>
          <div class="cd-position"><i class="ti ti-briefcase"></i> Ứng viên vị trí Lập trình viên PHP/MySQL</div>
          <div class="cd-tags">
            <span class="cd-tag">Nữ</span><span class="cd-tag">Sinh ngày: 12/03/2003</span><span class="cd-tag">Kon Tum</span><span class="cd-tag">Mong muốn: 10 - 15 triệu</span>
          </div>
          <div class="cd-actions">
            <button class="cd-btn cd-btn-primary"><i class="ti ti-send"></i> Mời ứng tuyển</button>
            <button class="cd-btn cd-btn-outline"><i class="ti ti-file-cv"></i> Xem CV</button>
            <button class="cd-btn cd-btn-outline"><i class="ti ti-heart"></i> Lưu hồ sơ</button>
          </div>
        </div>
      </div>
    </section>

    <div class="cd-layout">
      <div class="cd-main">
        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-user"></i> Thông tin cá nhân cơ bản</h2>
          <div class="cd-grid">
            <div class="cd-info"><div class="cd-label">Họ và tên</div><div class="cd-value">Nguyễn Thị Lan</div></div>
            <div class="cd-info"><div class="cd-label">Ngày sinh</div><div class="cd-value">12/03/2003</div></div>
            <div class="cd-info"><div class="cd-label">Giới tính</div><div class="cd-value">Nữ</div></div>
            <div class="cd-info"><div class="cd-label">Địa chỉ hiện tại</div><div class="cd-value">TP. Kon Tum, tỉnh Kon Tum</div></div>
            <div class="cd-info"><div class="cd-label">Số điện thoại</div><div class="cd-value">0987 654 321</div></div>
            <div class="cd-info"><div class="cd-label">Email</div><div class="cd-value">nguyenthilan@email.com</div></div>
          </div>
        </section>

        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-school"></i> Học vấn</h2>
          <div class="cd-grid">
            <div class="cd-info"><div class="cd-label">Bằng cấp</div><div class="cd-value">Cao đẳng</div></div>
            <div class="cd-info"><div class="cd-label">Chuyên ngành</div><div class="cd-value">Công nghệ thông tin</div></div>
            <div class="cd-info"><div class="cd-label">Trường</div><div class="cd-value">Trường Cao đẳng Kon Tum</div></div>
            <div class="cd-info"><div class="cd-label">Năm tốt nghiệp</div><div class="cd-value">2025</div></div>
          </div>
        </section>

        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-briefcase"></i> Kinh nghiệm làm việc</h2>
          <div class="cd-timeline">
            <div class="cd-time-item">
              <h4>Công ty Kon Tum Digital - Thực tập sinh Web Developer</h4>
              <span>06/2024 - 12/2024</span>
              <p>Tham gia xây dựng module quản lý bài đăng, xử lý giao diện responsive, viết API cơ bản bằng PHP và MySQL.</p>
            </div>
            <div class="cd-time-item">
              <h4>Dự án cá nhân - Website quản lý tuyển dụng</h4>
              <span>01/2025 - 04/2025</span>
              <p>Thiết kế database, phân quyền người dùng, xây dựng chức năng đăng tin, ứng tuyển và quản lý CV online.</p>
            </div>
          </div>
        </section>

        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-star"></i> Kỹ năng mềm & chuyên môn</h2>
          <div class="cd-skill-list">
            <span class="cd-skill">PHP</span><span class="cd-skill">MySQL</span><span class="cd-skill">HTML/CSS</span><span class="cd-skill">JavaScript</span><span class="cd-skill">jQuery</span><span class="cd-skill">Tiếng Anh cơ bản</span><span class="cd-skill">Làm việc nhóm</span><span class="cd-skill">Giao tiếp</span><span class="cd-skill">Quản lý thời gian</span>
          </div>
        </section>

        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-certificate"></i> Chứng chỉ</h2>
          <div class="cd-content">
            <ul>
              <li>Chứng chỉ Tin học ứng dụng nâng cao.</li>
              <li>Chứng chỉ HTML, CSS, JavaScript cơ bản.</li>
              <li>Chứng chỉ tiếng Anh trình độ A2.</li>
            </ul>
          </div>
        </section>

        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-target-arrow"></i> Mục tiêu nghề nghiệp</h2>
          <div class="cd-highlight">
            Ngắn hạn: Tìm kiếm vị trí lập trình viên PHP/MySQL để phát triển kỹ năng thực tế và tham gia các dự án phần mềm.<br>
            Dài hạn: Trở thành Full-stack Developer, có khả năng phân tích nghiệp vụ, thiết kế hệ thống và dẫn dắt nhóm kỹ thuật.
          </div>
        </section>
      </div>

      <aside class="cd-sidebar">
        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-filter-check"></i> Tiêu chí mong muốn</h2>
          <div class="cd-grid" style="grid-template-columns:1fr">
            <div class="cd-info"><div class="cd-label">Vị trí muốn ứng tuyển</div><div class="cd-value">Lập trình viên PHP/MySQL</div></div>
            <div class="cd-info"><div class="cd-label">Mức lương mong đợi</div><div class="cd-value">10 - 15 triệu/tháng</div></div>
            <div class="cd-info"><div class="cd-label">Địa điểm mong muốn</div><div class="cd-value">Kon Tum, Gia Lai hoặc Remote</div></div>
            <div class="cd-info"><div class="cd-label">Hình thức làm việc</div><div class="cd-value">Full-time</div></div>
          </div>
        </section>

        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-upload"></i> Hồ sơ đính kèm</h2>

          <div class="cd-file">
            <i class="ti ti-file-type-pdf"></i>
            <div>
              <strong>NguyenThiLan_CV.pdf</strong>
              <p>Đã tải lên · PDF · 1.8MB</p>

              <div class="cd-file-actions">
                <a href="uploads/cv/NguyenThiLan_CV.pdf" class="cd-download-btn" download>
                  <i class="ti ti-download"></i> Tải CV về
                </a>

                <a href="uploads/cv/NguyenThiLan_CV.pdf" class="cd-preview-btn" target="_blank">
                  <i class="ti ti-eye"></i> Xem trước
                </a>
              </div>
            </div>
          </div>
        </section>

        <section class="cd-card">
          <button class="cd-btn cd-btn-blue" style="width:100%;justify-content:center"><i class="ti ti-send"></i> Mời ứng viên ứng tuyển</button>
        </section>

        <section class="cd-card">
          <h2 class="cd-title"><i class="ti ti-users-star"></i> Ứng viên tiêu biểu</h2>

          <div class="cd-featured-candidates">
            <a href="#" class="cd-candidate-mini">
              <div class="cd-candidate-avatar">TH</div>
              <div class="cd-candidate-info">
                <h4>Trần Văn Hùng</h4>
                <p>Lập trình viên Frontend</p>
                <div class="cd-candidate-meta">
                  <span>ReactJS</span><span>Kon Tum</span>
                </div>
              </div>
            </a>

            <a href="#" class="cd-candidate-mini">
              <div class="cd-candidate-avatar" style="background:#2e7d32">LM</div>
              <div class="cd-candidate-info">
                <h4>Lê Thị Mai</h4>
                <p>Nhân viên Marketing</p>
                <div class="cd-candidate-meta">
                  <span>SEO</span><span>Gia Lai</span>
                </div>
              </div>
            </a>

            <a href="#" class="cd-candidate-mini">
              <div class="cd-candidate-avatar" style="background:#844404">PB</div>
              <div class="cd-candidate-info">
                <h4>Phạm Quốc Bảo</h4>
                <p>Kế toán tổng hợp</p>
                <div class="cd-candidate-meta">
                  <span>Kế toán</span><span>Full-time</span>
                </div>
              </div>
            </a>

            <a href="#" class="cd-candidate-mini">
              <div class="cd-candidate-avatar" style="background:#6a1b9a">HT</div>
              <div class="cd-candidate-info">
                <h4>Hoàng Thị Thu</h4>
                <p>Chuyên viên nhân sự</p>
                <div class="cd-candidate-meta">
                  <span>HR</span><span>2 năm KN</span>
                </div>
              </div>
            </a>
          </div>
        </section>
      </aside>
    </div>

    <section class="news-slider">
      <div class="news-head">
        <h2 class="cd-title" style="margin-bottom:0"><i class="ti ti-news"></i> Tin tức nổi bật</h2>
        <div class="news-controls">
          <button class="news-btn" onclick="newsSlide(-1)"><i class="ti ti-chevron-left"></i></button>
          <button class="news-btn" onclick="newsSlide(1)"><i class="ti ti-chevron-right"></i></button>
        </div>
      </div>

      <div class="news-wrap">
        <div class="news-track" id="newsTrack">
          <a href="#" class="news-card"><div class="news-img"><i class="ti ti-school"></i></div><div class="news-body"><h3>Trường Cao đẳng Kon Tum đẩy mạnh kết nối doanh nghiệp</h3><p>Cập nhật chương trình hợp tác đào tạo và tuyển dụng sinh viên.</p></div></a>
          <a href="#" class="news-card"><div class="news-img"><i class="ti ti-file-cv"></i></div><div class="news-body"><h3>5 cách viết CV giúp sinh viên mới ra trường nổi bật</h3><p>Mẹo trình bày kỹ năng, kinh nghiệm và mục tiêu nghề nghiệp.</p></div></a>
          <a href="#" class="news-card"><div class="news-img"><i class="ti ti-briefcase"></i></div><div class="news-body"><h3>Những ngành nghề có nhu cầu tuyển dụng cao năm 2026</h3><p>Cơ hội việc làm cho sinh viên các khối ngành kỹ thuật, dịch vụ.</p></div></a>
          <a href="#" class="news-card"><div class="news-img"><i class="ti ti-users"></i></div><div class="news-body"><h3>Kỹ năng phỏng vấn dành cho ứng viên trẻ</h3><p>Chuẩn bị câu trả lời, tác phong và hồ sơ khi gặp nhà tuyển dụng.</p></div></a>
          <a href="#" class="news-card"><div class="news-img"><i class="ti ti-certificate"></i></div><div class="news-body"><h3>Chứng chỉ nào giúp hồ sơ ứng viên cạnh tranh hơn?</h3><p>Gợi ý chứng chỉ ngắn hạn phù hợp với từng nhóm ngành.</p></div></a>
        </div>
      </div>
      <div class="news-dots" id="newsDots"></div>
    </section>
  </div>
</main>

<script>
(function(){
  var current = 0;
  var timer = null;

  function visibleCount(){
    if(window.innerWidth <= 480) return 1;
    if(window.innerWidth <= 1024) return 2;
    return 3;
  }

  function maxSlide(){
    var cards = document.querySelectorAll('.news-card');
    return Math.max(cards.length - visibleCount(), 0);
  }

  function renderDots(){
    var dots = document.getElementById('newsDots');
    if(!dots) return;
    var html = '';
    for(var i=0;i<=maxSlide();i++){
      html += '<button class="news-dot '+(i===current?'active':'')+'" onclick="goNews('+i+')"></button>';
    }
    dots.innerHTML = html;
  }

  function update(){
    var track = document.getElementById('newsTrack');
    var card = document.querySelector('.news-card');
    if(!track || !card) return;

    if(current > maxSlide()) current = 0;
    if(current < 0) current = maxSlide();

    track.style.transform = 'translateX(-' + (current * (card.offsetWidth + 14)) + 'px)';
    renderDots();
  }

  function resetAuto(){
    clearInterval(timer);
    timer = setInterval(function(){
      current++;
      update();
    }, 5000);
  }

  window.newsSlide = function(dir){
    current += dir;
    update();
    resetAuto();
  };

  window.goNews = function(index){
    current = index;
    update();
    resetAuto();
  };

  window.addEventListener('resize', function(){
    current = 0;
    update();
    resetAuto();
  });

  document.addEventListener('DOMContentLoaded', function(){
    update();
    resetAuto();
  });
})();
</script>

<?php require "../footer.php"; ?>