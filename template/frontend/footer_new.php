<style>
:root {
    --p-blue: #1760a5;
    --p-orange: #e36928;
    --light-bg: #f8fbff; /* Màu nền sáng chủ đạo */
    --card-bg: #ffffff;
    --text-dark: #2c3e50;
    --text-muted: #5d6d7e;
}

.premium-footer.light-theme {
    background-color: var(--light-bg);
    /* font-family: 'Quicksand', sans-serif; */
    position: relative;
    color: var(--text-dark);
    overflow: hidden;
    border-top: 1px solid rgba(23, 96, 165, 0.1);
}

.container-custom {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 25px;
}

/* Wave Divider - Chỉnh màu trùng với màu trang web phía trên footer */
.footer-wave {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
}
.footer-wave svg {
    display: block;
    width: calc(100% + 1.3px);
    height: 40px;
}
.footer-wave .shape-fill { fill: #ffffff; } /* Đổi màu này để tiệp với nền section trên nó */

.footer-main-content { padding: 80px 0 60px 0; }

.footer-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr 0.8fr;
    gap: 50px;
}

/* Brand Styles */
.logo-text-top {
    font-size: 16px;
    letter-spacing: 1.5px;
    color: var(--p-blue);
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}
.logo-text-main {
    font-size: 24px;
    line-height: 1.2;
    margin-bottom: 20px;
    color: var(--p-blue);
    font-weight: 700;
}
.logo-text-main span { color: var(--p-orange); font-weight: 800; }

.brand-desc {
    font-size: 14px;
    line-height: 1.7;
    color: var(--text-muted);
    margin-bottom: 25px;
}

.social-pills .social-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    background: var(--card-bg);
    color: var(--p-blue);
    border-radius: 50%;
    margin-right: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    transition: 0.3s;
    text-decoration: none;
}
.social-pills .social-pill:hover {
    background: var(--p-blue);
    color: #fff;
    transform: translateY(-3px);
}

/* Contact Items */
.footer-heading {
    font-size: 17px;
    margin-bottom: 30px;
    position: relative;
    font-weight: 700;
    color: var(--p-blue);
}
.footer-heading::before {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 30px;
    height: 3px;
    background: var(--p-orange);
}

.contact-item-premium {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    background: var(--card-bg);
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(23, 96, 165, 0.05); /* Đổ bóng xanh nhạt */
    transition: 0.3s;
}
.contact-item-premium:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 20px rgba(23, 96, 165, 0.1);
}

.icon-box {
    width: 40px;
    height: 40px;
    background: var(--p-blue);
    color: #fff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 18px;
}

.text-box span { display: block; font-size: 12px; color: var(--text-muted); }
.text-box strong { font-size: 14px; color: var(--text-dark); }
.highlight-orange { color: var(--p-orange) !important; font-size: 17px !important; }

/* Action Styles */
.work-time-badge {
    background: #fff;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 25px;
    border: 1px solid rgba(23, 96, 165, 0.08);
}
.time-row {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    padding: 8px 0;
    border-bottom: 1px dashed rgba(23, 96, 165, 0.1);
    color: var(--text-dark);
}
.time-row:last-child { border-bottom: none; }

.footer-cta-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: var(--p-orange);
    color: #fff;
    padding: 14px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 700;
    transition: 0.4s;
    box-shadow: 0 8px 20px rgba(227, 105, 40, 0.2);
}
.footer-cta-btn:hover {
    background: var(--p-blue);
    box-shadow: 0 8px 20px rgba(23, 96, 165, 0.2);
    transform: translateY(-2px);
}

/* Copyright Premium */
.footer-copyright-premium {
    background: #fff;
    padding: 20px 0;
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom:100px;
    border-top: 1px solid rgba(23, 96, 165, 0.05);
}
.copy-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.copy-flex span { color: var(--p-blue); font-weight: 600; }
.footer-policy-links a {
    color: var(--text-muted);
    text-decoration: none;
    margin-left: 20px;
}
.footer-policy-links a:hover { color: var(--p-blue); }

/* Responsive */
@media (max-width: 992px) {
    .footer-grid { grid-template-columns: 1fr 1fr; }
    .footer-action-col { grid-column: span 2; }
}
@media (max-width: 600px) {
    .footer-grid { grid-template-columns: 1fr; }
    .footer-action-col { grid-column: span 1; }
    .copy-flex { flex-direction: column; text-align: center; gap: 10px; }
}
</style>
<footer class="premium-footer light-theme">
    <div class="footer-wave">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
        </svg>
    </div>

    <div class="footer-main-content">
        <div class="container-custom">
            <div class="footer-grid">
                
                <div class="footer-brand-col">
                    <div class="footer-logo-area">
                        <span class="logo-text-top">PHÒNG KHÁM ĐA KHOA &amp; NHÀ THUỐC</span>
                        <h2 class="logo-text-main"><span>TRƯỜNG CAO ĐẲNG KON TUM</span></h2>
                    </div>
                    <p class="brand-desc">
                        Hệ thống chăm sóc sức khỏe toàn diện với đội ngũ bác sĩ, dược sĩ giàu kinh nghiệm, mang lại sự an tâm tuyệt đối cho bệnh nhân.
                    </p>
                    <div class="social-pills">
                        <a href="<?php echo $this->helper->get_config('site_facebook'); ?>" class="social-pill"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://zalo.me/<?php echo $this->helper->get_config('site_phonezalo'); ?>" class="social-pill"><i class=""> <img src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg"></i></a>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo $this->helper->get_config('site_email'); ?>" class="social-pill"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>

                <div class="footer-contact-col">
                    <h3 class="footer-heading">LIÊN HỆ</h3>
                    <div class="contact-card">
                        <div class="contact-item-premium">
                            <div class="icon-box"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="text-box">
                                <span>Địa chỉ phòng khám:</span>
                                <strong><?php echo $this->helper->get_config('site_address'); ?></strong>
                            </div>
                        </div>
                        <div class="contact-item-premium">
                            <div class="icon-box"><i class="fas fa-phone-volume"></i></div>
                            <div class="text-box">
                                <span>Hotline hỗ trợ 24/7:</span>
                                <strong class="highlight-orange"><?php echo $this->helper->get_config('site_hotline'); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-action-col">
                    <h3 class="footer-heading">THỜI GIAN LÀM VIỆC</h3>
                    <div class="work-time-badge">
                        <div class="time-row">
                            <span>Phòng khám <br> Đa khoa:</span>
                            <strong>Sáng 07:00 - 11:00 
                            <br>Chiều 13:00 - 17:00 </br>
                            (Thứ Hai - Thứ Sáu)</strong>
                        </div>
                        <div class="time-row">
                            <span>Nhà thuốc:</span>
                            <strong>07:00 - 21:00 (hằng ngày)</strong>
                        </div>
                    </div>
                    <a href="<?php echo XC_URL; ?>/dang-ky-lich-kham.html" class="footer-cta-btn">
                        <span>ĐẶT LỊCH NGAY</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-copyright-premium">
        <div class="container-custom">
            <div class="copy-flex">
                <p>&copy; 2026 Bản quyền thuộc về <span>Phòng khám Đa khoa và Nhà thuốc Trường Cao đẳng Kon Tum</span>.</p>
                <div class="footer-policy-links">
                    <a href="<?php echo XC_URL;?>/bmi-online/7-do-chi-so-can-nang-chieu-cao-online.html">Đo chỉ số cân nặng - Chiều cao (BMI) online</a><br>
                    <a href="#">Chính sách bảo mật</a>
                    <a href="#">Điều khoản sử dụng</a>
                </div>
            </div>
        </div>
    </div>

    <div id="p-fixbot">
	<div class="is_pc">

<div class="action-bar">
    <a href="<?php echo $this->helper->get_config('site_facebook'); ?>" class="action-item">
        <div class="icon-wrap">
            <div class="icon">
                <i class="fa-solid fa-comment-medical"></i>
            </div>
        </div>
        <span class="text">TƯ VẤN</span>
    </a>

    <a href="tel:<?php echo $this->helper->get_config('site_phone');  ?>" class="action-item">
        <div class="icon-wrap">
            <div class="icon phone-ring ">
                <i class="fa-solid fa-phone"></i>
            </div>
        </div>
        <span class="text">GỌI ĐIỆN</span>
    </a>

    <a href="<?php echo XC_URL;?>/dang-ky-lich-kham.html" class="action-item">
        <div class="icon-wrap">
            <div class="icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <span class="text">ĐẶT HẸN</span>
    </a>
</div>
</div>
	
		
		
		 
		
<div class="action-bar">
    <a href="<?php echo $this->helper->get_config('site_facebook'); ?>" class="action-item">
        <div class="icon-wrap">
            <div class="icon">
                <i class="fa-solid fa-comment-medical"></i>
            </div>
        </div>
        <span class="text">TƯ VẤN</span>
    </a>

    <a href="tel:<?php echo $this->helper->get_config('site_phone'); ?>" class="action-item">
        <div class="icon-wrap">
            <div class="icon phone-ring ">
                <i class="fa-solid fa-phone"></i>
            </div>
        </div>
        <span class="text">GỌI ĐIỆN</span>
    </a>

    <a href="<?php echo XC_URL;?>/dang-ky-lich-kham.html" class="action-item">
        <div class="icon-wrap">
            <div class="icon">
                <i class="fa-solid fa-calendar-check"></i>
            </div>
        </div>
        <span class="text">ĐẶT HẸN</span>
    </a>
</div>

</div>
</footer>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		document.body.addEventListener('click', function(e) {
			// Mở popup khi click vào nút đặt hẹn
			if (e.target.closest('a.btn-popup-dathen')) {
				e.preventDefault();
				var popup = document.getElementById('popup-datlichkham');
				if (popup) {
					popup.style.display = 'block';
					popup.classList.add('active');
				}
			}
			// Đóng popup khi click vào nút đóng
			if (e.target.closest('#popup-datlichkham .close-popup')) {
				e.preventDefault();
				var popup = document.getElementById('popup-datlichkham');
				if (popup) {
					popup.style.display = 'none';
					popup.classList.remove('active');
				}
			}
		});
	});
</script>
<div class="is_mobi">
  <div id="popup_callto" style="display: none;">
    <div id="callto_ovl"></div>
    <div id="callto_wrap">
      <a href="tel:<?php echo $this->helper->get_config('site_phone'); ?>"><i class="fa fa-phone" aria-hidden="true"></i> <b>Gọi <?php echo $this->helper->get_config('site_phone'); ?></b></a>
      <a href="tel:02227300222"><i class="fa fa-phone" aria-hidden="true"></i> <b>Gọi <?php echo $this->helper->get_config('site_phone'); ?></b></a>
      <span id="callto_close"><b>Hủy</b></span>
    </div>
  </div>
</div>
<div class="chat-sticky" style="display: none;">
	<a href="https://zalo.me/<?php echo $this->helper->get_config('site_phonezalo'); ?> " class="clickChatZalo" title="Chat Zalo">
		<img src="<?php echo $template_path; ?>/assets/themes/dkcd/img/logo-zalo-60.png" alt="zalo logo" >
	</a>
</div>
<div class="is_mobi">
  <div id="menuM" class="">
    <div id="menuM-wrp">
      <div id="menuM-head">
        <img src="wp-content/themes/dkcd/img/dkvs-hmn.jpg" alt="">
        <span id="menuM-cls"></span>
      </div>
      <div class="menuCustom">
        <ul id="menuM-lst">
          <li class="icMenu-1 menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-1062 current_page_item menu-item-2233"><a href="<?php echo XC_URL;?>">Trang chủ</a></li>

<li class="benh-hau-mon menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-5181"><a href="#">Giới thiệu</a>
<ul class="sub-menu">
	<li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5152"><a href="<?php echo XC_URL; ?>/gioi-thieu/1-ve-chung-toi.html">Về chúng tôi</a></li>
	<li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5156"><a href="<?php echo XC_URL; ?>/gioi-thieu/3-co-cau-to-chuc.html">Cơ cấu tổ chức</a></li>
	<li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5155"><a href="<?php echo XC_URL; ?>/gioi-thieu/2-co-so-ha-tang.html">Cơ sở hạ tầng</a></li>
</ul>
</li>
<li class="benh-hau-mon menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-5181"><a href="#">Dịch vụ phòng khám</a>
<ul class="sub-menu">
	<li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5152"><a href="<?php echo XC_URL; ?>/dich-vu/6-chuyen-khoa.html">Khám chữa bệnh dịch vụ</a></li>
  <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5152"><a href="<?php echo XC_URL; ?>/dich-vu/7-goi-kham.html">Khám chữa bệnh BHYT</a></li>
</ul>
	
</li>

<li class="benh-hau-mon menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-5181"><a href="#">Nhà thuốc</a>
<ul class="sub-menu">
	<li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5152"><a href="<?php echo XC_URL; ?>/gioi-thieu/5-gioi-thieu-nha-thuoc.html">Giới thiệu nhà thuốc</a></li>
	<li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5152"><a href="<?php echo XC_URL; ?>/nha-thuoc.html">Danh mục sản phẩm</a></li>
  <li class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-5152"><a href="<?php echo XC_URL; ?>/gioi-thieu/6-chinh-sach-ban-hang.html">Chính sách bán hàng</a></li>
</ul>
	
</li>

<li class="icMenu-4 menu-item menu-item-type-post_type menu-item-object-page menu-item-5033"><a href="<?php echo XC_URL; ?>/doi-ngu-bac-si.html">Đội ngũ bác sĩ</a></li>
<li class="icMenu-4 menu-item menu-item-type-post_type menu-item-object-page menu-item-5033"><a href="<?php echo XC_URL; ?>/lich-cong-tac.html">Lịch công tác</a></li>
<li class="icMenu-4 menu-item menu-item-type-post_type menu-item-object-page menu-item-5033"><a href="<?php echo XC_URL; ?>/tin-tuc-su-kien.html">Tin tức & sự kiện</a></li>
<li class="icMenu-5 menu-item menu-item-type-post_type menu-item-object-page menu-item-47"><a href="<?php echo XC_URL; ?>/lien-he.html">Liên hệ</a></li>
      <div id="menuM-hotline">
          <!-- <span>Hotline tư vấn</span>
          <div class="menuM-hotline-ct">
            <i id="menuM-ic1" class="allicon"></i>
            <div class="menuM-hl">
              <strong id="p1"><a href="tel:02227300222">0222 730 0222</a></strong>
              <strong id="p2"><a href="tel:0865678169">086 5678 169</a></strong>
            </div>
          </div> -->
        </div>
      </div>

    </div>
  </div>
  <div id="ovl-menuM" class="ovl-bg"></div>
</div>


<script type='text/javascript' src='<?php echo $template_path; ?>/assets/include/js/jquery/jqueryb8ff.js?ver=1.12.4'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/include/js/jquery/jquery-migrate.min330a.js?ver=1.4.1'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/sc_boai/assets/script122b.js?ver=1770353212'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/cwp_toc/assets/js/cwp-toc.js'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/pkvs-hide-category/assets/js/script.js'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/includey/js/jquery/form-validation.js'></script>

<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', function () {
    let hiddenCategories = [];

    if (hiddenCategories && hiddenCategories.length > 0) {
        document.querySelectorAll('li a').forEach(function (link) {
            try {
                // Lấy pathname từ URL (loại bỏ domain và query string)
                let url = new URL(link.html);
                let pathname = url.pathname;
                
                hiddenCategories.forEach(function (category) {
                    // So sánh pathname với category pattern
                    // Hỗ trợ cả /slug/ và /slug (có và không có "/" ở cuối)
                    let categoryNoSlash = category.replace(/\/$/, '');
                    if (pathname === category || pathname === categoryNoSlash) {
                        link.closest('li').style.display = 'none';
                    }
                });
            } catch (e) {
                // Nếu URL không hợp lệ (relative URL), sử dụng cách kiểm tra cũ
                hiddenCategories.forEach(function (category) {
                    if (link.href.includes(category)) {
                        link.closest('li').style.display = 'none';
                    }
                });
            }
        });
    }
});
</script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/themes/dkcd/a/all4bf4.js?ver=1.0.3'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/themes/dkcd/a/boai_show_popup9632.js?ver=1.2.3'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/wpcomp-quiz/assets/js/script.js'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var wpminiHsinjectorSettings = {"initial_delay":"10000","notification_duration":"6000","notification_interval":"10000"};
/* ]]> */
</script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/wpmini-script-injector/assets/js/script.js'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/include/js/wp-embed.min.js'></script>

<script type='text/javascript' src='<?php echo $template_path; ?>/assets/include/js/jquery/jquery.js'></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/include/js/jquery/jquery.validate.js'></script>
<script type='text/javascript' src='<?php echo $template_path; ?>/assets/include/js/jquery/form-validation.js'></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>

      <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script type="text/javascript">
    jQuery(function($) {
      $('#sec2Wrp,#phbnWrp').slick({
        lazyLoad: 'ondemand',
        infinite: true,
        dots: true,
        arrows: false,
        speed: 300,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [{
          breakpoint: 9999,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
          }
        }, {
          breakpoint: 991.98,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          }
        }]
      });
      $('#mtpkWrp').slick({
        lazyLoad: 'ondemand',
        infinite: true,
        dots: true,
        arrows: false,
        speed: 300,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [{
          breakpoint: 9999,
          settings: "unslick"
        }, {
          breakpoint: 991.98,
          settings: {
            centerMode: true,
            slidesToShow: 1,
            slidesToScroll: 1,
          }
        }]
      });
      $('#sec6Baochi').slick({
        lazyLoad: 'ondemand',
        infinite: true,
        dots: true,
        arrows: false,
        speed: 300,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [{
          breakpoint: 9999,
          settings: {
            slidesToShow: 5,
            slidesToScroll: 5,
          }
        }, {
          breakpoint: 991.98,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        }]
      });
      $('#sec7Wrp').slick({
        lazyLoad: 'ondemand',
        infinite: true,
        dots: true,
        arrows: false,
        speed: 300,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [{
          breakpoint: 9999,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        }, {
          breakpoint: 991.98,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          }
        }]
      });
      //$('.videoPk-thumb').click(function() {
      // let video = '<iframe style="width:95%;" id="ytplayer" type="text/html" src="https://www.youtube.com/embed/LKJyeUMxr7k?autoplay=1&loop=1&color=white&controls=0&modestbranding=1&playsinline=1&rel=0&enablejsapi=1s" frameborder="0" allowfullscreen>';
      //let video = '<iframe style="width:95%;" id="ytplayer" frameborder="0" type="text/html" src="https://www.youtube.com/embed/LKJyeUMxr7k?autoplay=1&controls=0&rel=0&playlist=LKJyeUMxr7k&loop=1&modestbranding=1" width="100%" height="100%"  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
      //$('.videoPk-thumb').replaceWith(video);
      //$('.videoPk-ic').remove();
      //});
      $(".sec8S-item").hover(function() {
        $(".sec8B-item").removeClass("tab-show");
        $(".sec8S-item").removeClass("current");
        $(this).addClass("current");
        var current_tab = $(this).attr("data-id");
        $(current_tab).addClass("tab-show");
        return false;
      });
    });
  </script>
  <script>
    jQuery(function($) {
      $("#pstCntn img").each(function() {
        $(this).attr("data-src", $(this).attr("src"));
        $(this).removeAttr("src");
      });
      $("p").each(function() {
        var $this = $(this);
        if ($this.html().replace(/\s|&nbsp;/g, '').length == 0)
          $this.remove();
      });

    

      //update
      $(".btn-show-change-fontsize").click(function(e) {
        e.preventDefault();
        $(this).toggleClass("active");
        if ($(this).hasClass("active")) {
          $(".show-change-font-size").slideDown(300);
        } else {
          $(".show-change-font-size").slideUp(300);
        }
      });
      $(window).click(function(e) {
        if (
          $(".btn-show-change-fontsize").has(e.target).length == 0 &&
          !$(".btn-show-change-fontsize").is(e.target) &&
          $(".show-change-font-size").has(e.target).length == 0 &&
          !$(".show-change-font-size").is(e.target)
        ) {
          $(".show-change-font-size").slideUp(300);
        }
      });


    });

    function updateTextInputFontsize(val) {
      $(".current-fontsize").html(val);
      $("#pstCntn").css("fontSize", val + "px");
    }
  </script>
      <script type="text/javascript">
        jQuery(function($) {
            $('#pcsvc').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                asNavFor: '#pcsvcThumb'
            });
            $('#pcsvcThumb').slick({
                asNavFor: '#pcsvc',
                autoplaySpeed: 3000,
                centerMode: true,
                focusOnSelect: true,
                autoplay: false,
                infinite: true,
                arrows: true,
                prevArrow: '<button class="slickPrev"><i class="fas fa-chevron-left"></i></button>',
                nextArrow: '<button class="slickNext"><i class="fas fa-chevron-right"></i></button>',
                responsive: [{
                    breakpoint: 9999,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 1,
                        centerPadding: '0',
                    }
                }, {
                    breakpoint: 991.98,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerPadding: '60px',
                    }
                }]
            });
            $('#pcsvcPK-wrp').slick({
                lazyLoad: 'ondemand',
                infinite: true,
                dots: true,
                arrows: false,
                speed: 300,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [{
                    breakpoint: 9999,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                    }
                }, {
                    breakpoint: 991.98,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    }
                }]
            });
      $('.pBs').slick({
        lazyLoad: 'ondemand',
        infinite: true,
        dots: true,
        arrows: false,
        speed: 300,
        // autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 2,
        slidesToScroll: 2,
      });
    });
  </script>



</script>
<script type="text/javascript">
    WebFontConfig = {
        google: { families: [ 'Roboto:300,400,500,700:vietnamese','Oswald:300,400,500,600,700:vietnamese' ] }
    };
    (function() {
        var wf = document.createElement('script');
        wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1.5.18/webfont.js';
        wf.type = 'text/javascript';
        wf.async = 'true';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(wf, s);
    })();
</script>

<script>
    jQuery(document).ready(function($){ 
        	 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#myForm").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"booking_person_name": {
				required: true
			},
			"booking_person_phone": {
				required: true
			},
			"booking_person_gender":{
				required: true
			},
			"booking_person_year":{
				required: true
			},
			"booking_person_address":{
				required: true
			}
		},
		messages:{
				booking_person_name: {
					required: "Bạn chưa nhập họ và tên"
				},
				booking_person_phone: {
					required: "Bạn chưa nhập số điện thoại"
				},
				booking_person_gender: "Bạn chưa chọn giới tính",
				booking_person_year: "Bạn chưa nhập năm sinh",
				booking_person_address: "Bạn chưa nhập địa chỉ"
				
			}
	});
        $('#btnBooking').click(function(e) {
            			if ($("#myForm").valid()) {

                var formData = new FormData();
                formData.append('booking_person_name', $('#booking_person_name').val());
                formData.append('booking_person_phone', $('#booking_person_phone').val());
                formData.append('booking_person_gender', $('#booking_person_gender').val());
                formData.append('booking_person_year', $('#booking_person_year').val());
                formData.append('booking_person_address', $('#booking_person_address').val());
                formData.append('booking_doctor', $('#booking_doctor').val());
                formData.append('booking_date', $('#booking_date').val());
                formData.append('booking_hour', $('#booking_hour').val());
                formData.append('booking_description', $('#booking_description').val());
            $.ajax({
                type: "POST",
                url: "<?php echo XC_URL;?>/api/addBooking",
                data:formData,
                dataType: 'json',
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                success: function(data){
                    if (data.status == 200) {
                        console.log(data);
                        let timerInterval;

                        Swal.fire({
                            icon: 'success',
                            title: 'Đặt lịch thành công',
                            html: data.message, // Bạn có thể thay bằng data.message
                            footer: 'Hệ thống tự động chuyển hướng sau <b id="countdown" style="color:red; padding: 0 5px;">5</b> giây',
                            timer: 5000,
                            timerProgressBar: true,
                            allowOutsideClick: false, // Ngăn người dùng tắt thông báo sớm
                            didOpen: () => {
                                const b = Swal.getFooter().querySelector('#countdown');
                                let timeLeft = 10;
                                timerInterval = setInterval(() => {
                                    timeLeft--;
                                    if (b) b.textContent = timeLeft;
                                }, 1000);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        }).then((result) => {
                            // Sau 5 giây hoặc khi bấm OK sẽ nhảy về trang chủ
                            window.location.href = '<?php echo XC_URL;?>'; // Thay index.php bằng link trang chủ của bạn
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message
                        });
                    }
                }
            });
            			}
        });
});
</script>
</body>


</html>