<?php require "header.php"; ?>

<style>
.contact-page{background:linear-gradient(180deg,#f5f8fc 0%,#ffffff 55%,#f4f5f6 100%);overflow:hidden}
.contact-hero{position:relative;padding:58px 20px 42px;background:radial-gradient(circle at 15% 20%,rgba(13,78,150,.16),transparent 30%),radial-gradient(circle at 85% 10%,rgba(132,68,4,.16),transparent 28%),linear-gradient(135deg,#0d4e96 0%,#073763 58%,#844404 100%);color:#fff}
.contact-hero:after{content:'';position:absolute;left:-8%;right:-8%;bottom:-62px;height:110px;background:#fff;border-radius:50% 50% 0 0/100% 100% 0 0}
.contact-hero-inner{max-width:none;margin:0 auto;position:relative;z-index:1;display:grid;grid-template-columns:1.15fr .85fr;gap:34px;align-items:center}
.contact-eyebrow{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.26);padding:7px 14px;border-radius:999px;font-size:13px;font-weight:700;margin-bottom:18px;backdrop-filter:blur(8px)}
.contact-hero h1{font-size:42px;line-height:1.16;font-weight:800;letter-spacing:-1px;margin-bottom:14px;max-width:760px}
.contact-hero p{font-size:15px;line-height:1.8;color:rgba(255,255,255,.86);max-width:650px}
.contact-hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:24px}.contact-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 18px;border-radius:14px;font-weight:800;font-size:14px;transition:.2s;border:1px solid rgba(255,255,255,.22)}
.contact-btn.primary{background:#fff;color:#0d4e96}.contact-btn.secondary{background:rgba(255,255,255,.12);color:#fff}.contact-btn:hover{transform:translateY(-2px);box-shadow:0 14px 32px rgba(0,0,0,.18)}
.contact-hero-card{background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.22);border-radius:28px;padding:22px;box-shadow:0 24px 70px rgba(0,0,0,.18);backdrop-filter:blur(12px)}
.hero-card-top{display:flex;align-items:center;gap:14px;margin-bottom:18px}.hero-card-icon{width:54px;height:54px;border-radius:18px;background:#fff;color:#0d4e96;display:flex;align-items:center;justify-content:center;font-size:27px;box-shadow:0 12px 28px rgba(0,0,0,.14)}
.hero-card-title{font-size:16px;font-weight:800}.hero-card-sub{font-size:12px;color:rgba(255,255,255,.72);margin-top:3px}.hero-mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}.hero-mini{background:rgba(255,255,255,.13);border:1px solid rgba(255,255,255,.17);border-radius:16px;padding:13px}.hero-mini b{display:block;font-size:18px;color:#fff}.hero-mini span{font-size:11px;color:rgba(255,255,255,.72)}
.contact-main{max-width:none;margin:0 auto;padding:54px 20px 44px;position:relative;z-index:2}.contact-grid{display:grid;grid-template-columns:.9fr 1.1fr;gap:22px;align-items:stretch}.contact-panel{background:#fff;border:1px solid #e9eef6;border-radius:26px;box-shadow:0 16px 46px rgba(13,78,150,.08);overflow:hidden}.contact-panel.pad{padding:24px}.panel-title{font-size:22px;font-weight:800;color:#111;margin-bottom:8px}.panel-desc{font-size:13px;color:#687385;line-height:1.7;margin-bottom:22px}.info-list{display:grid;gap:14px}.info-item{display:flex;gap:13px;align-items:flex-start;padding:14px;border:1px solid #edf2f8;border-radius:18px;background:linear-gradient(180deg,#fff,#f8fbff);transition:.2s}.info-item:hover{border-color:#0d4e96;transform:translateY(-2px);box-shadow:0 10px 24px rgba(13,78,150,.08)}.info-icon{width:42px;height:42px;border-radius:14px;background:#eef5ff;color:#0d4e96;display:flex;align-items:center;justify-content:center;font-size:21px;flex-shrink:0}.info-label{font-size:12px;font-weight:700;color:#8a95a6;text-transform:uppercase;letter-spacing:.4px}.info-value{font-size:14px;font-weight:700;color:#1f2937;margin-top:3px;line-height:1.5}.info-note{font-size:12px;color:#788398;margin-top:2px;line-height:1.5}.contact-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:20px}.contact-stat{border-radius:18px;background:#0d4e96;color:#fff;padding:16px 12px;text-align:center}.contact-stat:nth-child(2){background:#844404}.contact-stat:nth-child(3){background:#143d63}.contact-stat b{display:block;font-size:20px}.contact-stat span{font-size:11px;opacity:.78}.map-card{display:flex;flex-direction:column}.map-head{padding:22px 24px 16px;display:flex;justify-content:space-between;gap:16px;align-items:flex-start}.map-title{font-size:22px;font-weight:800;color:#111}.map-desc{font-size:13px;color:#687385;margin-top:7px;line-height:1.6}.map-badge{display:inline-flex;align-items:center;gap:7px;background:#fff7ed;color:#844404;border:1px solid #fed7aa;border-radius:999px;padding:8px 12px;font-size:12px;font-weight:800;white-space:nowrap}.map-wrap{height:458px;margin:0 16px 16px;border-radius:22px;overflow:hidden;border:1px solid #e5ecf5;position:relative;background:#e9eef6}.map-wrap iframe{width:100%;height:100%;border:0;display:block}.contact-form-section{display:grid;grid-template-columns:1fr .95fr;gap:22px;margin-top:22px}.contact-form{background:#fff;border:1px solid #e9eef6;border-radius:26px;padding:24px;box-shadow:0 16px 46px rgba(13,78,150,.08)}.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.form-group{display:flex;flex-direction:column;gap:7px}.form-group.full{grid-column:1/-1}.form-group label{font-size:12px;font-weight:800;color:#334155}.form-group input,.form-group textarea{border:1px solid #dfe7f2;border-radius:14px;padding:12px 14px;font-family:inherit;font-size:14px;outline:none;background:#fbfdff;transition:.18s}.form-group textarea{min-height:120px;resize:vertical}.form-group input:focus,.form-group textarea:focus{border-color:#0d4e96;box-shadow:0 0 0 4px rgba(13,78,150,.1);background:#fff}.submit-btn{margin-top:14px;width:100%;border:0;border-radius:16px;padding:14px 18px;background:linear-gradient(135deg,#0d4e96,#073763);color:#fff;font-weight:800;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:.2s}.submit-btn:hover{transform:translateY(-2px);box-shadow:0 14px 28px rgba(13,78,150,.22)}.support-card{border-radius:26px;padding:24px;background:linear-gradient(135deg,#111827,#0d4e96);color:#fff;position:relative;overflow:hidden;box-shadow:0 18px 48px rgba(13,78,150,.16)}.support-card:before{content:'';position:absolute;width:220px;height:220px;border-radius:50%;right:-70px;top:-70px;background:rgba(255,255,255,.11)}.support-card>*{position:relative}.support-card h3{font-size:24px;margin-bottom:10px}.support-card p{font-size:13px;line-height:1.8;color:rgba(255,255,255,.78);margin-bottom:18px}.support-steps{display:grid;gap:12px}.support-step{display:flex;gap:12px;align-items:flex-start;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.13);border-radius:16px;padding:13px}.support-step i{font-size:22px;color:#ffd08a}.support-step b{display:block;font-size:13px}.support-step span{display:block;font-size:12px;color:rgba(255,255,255,.72);margin-top:3px;line-height:1.5}.faq-strip{margin-top:22px;display:grid;grid-template-columns:repeat(3,1fr);gap:14px}.faq-item{background:#fff;border:1px solid #e9eef6;border-radius:20px;padding:18px;box-shadow:0 10px 28px rgba(15,23,42,.05)}.faq-item i{font-size:24px;color:#0d4e96}.faq-item b{display:block;font-size:14px;margin:8px 0 5px;color:#111}.faq-item span{font-size:12px;line-height:1.6;color:#64748b}
.contact-page label.error{font-size:12px;color:#dc2626}
@media(max-width:1024px){.contact-hero-inner,.contact-grid,.contact-form-section{grid-template-columns:1fr}.contact-hero-card{max-width:560px}.map-wrap{height:390px}.faq-strip{grid-template-columns:1fr 1fr}}
@media(max-width:768px){.contact-hero{padding:42px 16px 38px}.contact-hero h1{font-size:32px}.contact-main{padding:44px 14px 34px}.contact-panel.pad,.contact-form,.support-card{padding:18px}.map-head{padding:18px;flex-direction:column}.map-wrap{margin:0 12px 12px;height:330px}.contact-stats,.faq-strip{grid-template-columns:1fr}.form-grid{grid-template-columns:1fr}.hero-mini-grid{grid-template-columns:1fr 1fr}}
@media(max-width:480px){.contact-hero h1{font-size:26px}.contact-hero p{font-size:13px}.contact-hero-actions{flex-direction:column}.contact-btn{justify-content:center;width:100%}.hero-mini-grid{grid-template-columns:1fr}.panel-title,.map-title{font-size:19px}.info-item{padding:12px}.map-wrap{height:290px;border-radius:18px}.contact-form-section{gap:14px}.contact-page{background:#f6f8fb}}
.contact-page *{box-sizing:border-box}
.contact-page img,.contact-page iframe{max-width:100%}
.contact-page a,.contact-page button,.contact-page input,.contact-page textarea{min-width:0}
.contact-hero-inner,.contact-grid,.contact-form-section,.faq-strip,.hero-mini-grid,.form-grid{min-width:0}
.contact-panel,.contact-form,.support-card,.faq-item,.contact-hero-card{min-width:0}
.info-value,.info-note,.panel-desc,.map-desc,.support-card p,.support-step span,.faq-item span{overflow-wrap:anywhere}
.form-group input,.form-group textarea{width:100%}
</style>

<main class="contact-page">
  <section class="contact-hero">
    <div class="contact-hero-inner">
      <div>
        <div class="contact-eyebrow"><i class="ti ti-headset"></i> Trung tâm hỗ trợ và kết nối việc làm</div>
        <h1>Liên hệ với Cổng thông tin việc làm</h1>
        <p>Chúng tôi luôn sẵn sàng hỗ trợ sinh viên, người tìm việc và nhà tuyển dụng trong quá trình đăng tin, ứng tuyển, tạo CV và kết nối cơ hội nghề nghiệp.</p>
        <div class="contact-hero-actions">
          <a class="contact-btn primary" href="tel:02603860929"><i class="ti ti-phone-call"></i> Gọi ngay</a>
          <a class="contact-btn secondary" href="mailto:vieclam@kontum.edu.vn"><i class="ti ti-mail"></i> Gửi email</a>
        </div>
      </div>
      <div class="contact-hero-card">
        <div class="hero-card-top">
          <div class="hero-card-icon"><i class="ti ti-building-community"></i></div>
          <div>
            <div class="hero-card-title">Trường Cao đẳng Kon Tum</div>
            <div class="hero-card-sub">Đồng hành cùng sinh viên và doanh nghiệp</div>
          </div>
        </div>
        <div class="hero-mini-grid">
          <div class="hero-mini"><b>24/7</b><span>Tiếp nhận thông tin trực tuyến</span></div>
          <div class="hero-mini"><b>48h</b><span>Phản hồi yêu cầu hỗ trợ</span></div>
          <div class="hero-mini"><b>100+</b><span>Đối tác tuyển dụng</span></div>
          <div class="hero-mini"><b>1 chạm</b><span>Kết nối cơ hội việc làm</span></div>
        </div>
      </div>
    </div>
  </section>

  <section class="contact-main">
    <div class="contact-grid">
      <div class="contact-panel pad">
        <h2 class="panel-title">Thông tin đơn vị</h2>
        <p class="panel-desc">Thông tin liên hệ chính thức phục vụ tư vấn tuyển dụng, hỗ trợ ứng viên và tiếp nhận hợp tác doanh nghiệp.</p>
        <div class="info-list">
          <div class="info-item"><div class="info-icon"><i class="ti ti-school"></i></div><div><div class="info-label">Tên đơn vị</div><div class="info-value">Trường Cao đẳng Kon Tum</div><div class="info-note">Cổng thông tin việc làm dành cho sinh viên và người tìm việc</div></div></div>
          <div class="info-item"><div class="info-icon"><i class="ti ti-map-pin"></i></div><div><div class="info-label">Địa chỉ</div><div class="info-value">14 Ngụy Như Kon Tum, phường Ngô Mây, TP. Kon Tum, tỉnh Kon Tum</div><div class="info-note">Vui lòng liên hệ trước khi đến làm việc trực tiếp</div></div></div>
          <div class="info-item"><div class="info-icon"><i class="ti ti-phone"></i></div><div><div class="info-label">Số điện thoại</div><div class="info-value">0260 3860 929</div><div class="info-note">Hỗ trợ trong giờ hành chính</div></div></div>
          <div class="info-item"><div class="info-icon"><i class="ti ti-mail"></i></div><div><div class="info-label">Email</div><div class="info-value">vieclam@kontum.edu.vn</div><div class="info-note">Tiếp nhận hồ sơ, hợp tác tuyển dụng và phản hồi hỗ trợ</div></div></div>
          <div class="info-item"><div class="info-icon"><i class="ti ti-clock-hour-4"></i></div><div><div class="info-label">Thời gian làm việc</div><div class="info-value">Thứ 2 - Thứ 6: 07:30 - 17:00</div><div class="info-note">Nghỉ thứ 7, chủ nhật và các ngày lễ theo quy định</div></div></div>
        </div>
        <div class="contact-stats"><div class="contact-stat"><b>5K+</b><span>Ứng viên</span></div><div class="contact-stat"><b>500+</b><span>Tin tuyển dụng</span></div><div class="contact-stat"><b>98%</b><span>Hài lòng</span></div></div>
      </div>

      <div class="contact-panel map-card">
        <div class="map-head"><div><h2 class="map-title">Bản đồ chỉ đường</h2><p class="map-desc">Tìm vị trí đơn vị trên Google Map để thuận tiện liên hệ, làm việc và kết nối tuyển dụng.</p></div><div class="map-badge"><i class="ti ti-map-2"></i> Google Map</div></div>
        <div class="map-wrap">
          <iframe loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=Tr%C6%B0%E1%BB%9Dng%20Cao%20%C4%91%E1%BA%B3ng%20Kon%20Tum&output=embed"></iframe>
        </div>
      </div>
    </div>

    <div class="contact-form-section">
      <form class="contact-form" id="contactForm" action="#" method="post" novalidate>
        <h2 class="panel-title">Gửi yêu cầu liên hệ</h2>
        <p class="panel-desc">Điền thông tin bên dưới, bộ phận phụ trách sẽ tiếp nhận và phản hồi trong thời gian sớm nhất.</p>
        <div class="form-grid">
          <div class="form-group"><label>Họ và tên</label><input type="text" id="customer_name" name="customer_name" placeholder="Nhập họ và tên"></div>
          <div class="form-group"><label>Số điện thoại</label><input type="tel" id="customer_phone" name="customer_phone" placeholder="Nhập số điện thoại"></div>
          <div class="form-group"><label>Email</label><input type="email" id="customer_email" name="customer_email" placeholder="Nhập email"></div>
          <div class="form-group"><label>Địa chỉ</label><input type="text" id="customer_address" name="customer_address" placeholder="Nhập địa chỉ liên hệ"></div>
          <div class="form-group full"><label>Nội dung</label><textarea id="content" name="content" placeholder="Nhập nội dung cần hỗ trợ..."></textarea></div>
        </div>
        <button class="submit-btn" type="submit"><i class="ti ti-send"></i> Gửi thông tin liên hệ</button>
      </form>

      <div class="support-card">
        <h3>Hỗ trợ nhanh hơn</h3>
        <p>Để quá trình xử lý thuận tiện, vui lòng cung cấp rõ thông tin người liên hệ, nội dung cần hỗ trợ và tài liệu liên quan nếu có.</p>
        <div class="support-steps">
          <div class="support-step"><i class="ti ti-user-check"></i><div><b>Ứng viên / sinh viên</b><span>Hỗ trợ tạo CV, tìm việc phù hợp và theo dõi trạng thái ứng tuyển.</span></div></div>
          <div class="support-step"><i class="ti ti-building"></i><div><b>Nhà tuyển dụng</b><span>Hỗ trợ đăng tin, duyệt tin tuyển dụng và kết nối nguồn ứng viên.</span></div></div>
          <div class="support-step"><i class="ti ti-handshake"></i><div><b>Đối tác doanh nghiệp</b><span>Tiếp nhận đề xuất hợp tác, ngày hội việc làm và chương trình thực tập.</span></div></div>
        </div>
      </div>
    </div>

    <div class="faq-strip">
      <div class="faq-item"><i class="ti ti-file-cv"></i><b>Hỗ trợ CV</b><span>Tư vấn hoàn thiện hồ sơ, cập nhật thông tin cá nhân và xuất CV chuyên nghiệp.</span></div>
      <div class="faq-item"><i class="ti ti-speakerphone"></i><b>Đăng tin tuyển dụng</b><span>Tiếp nhận nhu cầu tuyển dụng, kiểm duyệt nội dung và hiển thị tin phù hợp.</span></div>
      <div class="faq-item"><i class="ti ti-shield-check"></i><b>Bảo mật thông tin</b><span>Cam kết tiếp nhận và xử lý thông tin liên hệ đúng mục đích hỗ trợ.</span></div>
    </div>
  </section>
</main>

<?php require "footer.php"; ?>
<script>
jQuery(function($){
  var $form = $('#contactForm');
  if(!$form.length){
    return;
  }

  $form.validate({
    onfocusout: false,
    onkeyup: false,
    onclick: false,
    errorElement: 'label',
    rules: {
      customer_name: { required: true },
      customer_phone: { required: true },
      customer_email: { required: true, email: true },
      customer_address: { required: true },
      content: { required: true }
    },
    messages: {
      customer_name: { required: 'Vui lòng nhập họ và tên.' },
      customer_phone: { required: 'Vui lòng nhập số điện thoại.' },
      customer_email: {
        required: 'Vui lòng nhập email.',
        email: 'Email không đúng định dạng.'
      },
      customer_address: { required: 'Vui lòng nhập địa chỉ.' },
      content: { required: 'Vui lòng nhập nội dung liên hệ.' }
    },
    submitHandler: function(form, event){
      event.preventDefault();
      var formData = new FormData(form);
      formData.append('rating', 0);

      $.ajax({
        type: 'POST',
        url: '<?php echo XC_URL; ?>/api/addFeedback',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        success:function(data){
          if(data.status == 200){
            Swal.fire({
              toast: true,
              icon: 'success',
              title: data.message,
              showConfirmButton: false,
              timer: 1200,
              timerProgressBar: true,
              didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
              }
            }).then((result) => {
              window.location.href = data.return_url;
            });
          }else{
            Swal.fire({
              icon: 'error',
              title: 'Gửi yêu cầu thất bại!',
              text: data.message,
              footer: '<a href=""></a>'
            });
          }
        },
        error: function(){
          Swal.fire({
            icon: 'error',
            title: 'Gửi yêu cầu thất bại!',
            text: 'Không thể gửi yêu cầu liên hệ, vui lòng thử lại.'
          });
        }
      });

      return false;
    }
  });
});
</script>
