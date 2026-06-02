<?php require "config.php";?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Việc Làm  - Tìm việc làm nhanh, tuyển dụng hiệu quả</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/css/style.css?version<?php echo time();?>">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/css/job.css?version<?php echo time();?>">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/css/cv.css?version<?php echo time();?>">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/css/styledetailcv.css?version<?php echo time();?>">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/css/events.css?version<?php echo time();?>">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/css/introduce.css?version<?php echo time();?>">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/css/dbhemployee.css?version<?php echo time();?>">
<link rel="stylesheet" href="<?php echo $template_path;?>/assets/css/register.css?version<?php echo time();?>">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.5.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-ui-dist@1.13.3/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.20.0/dist/additional-methods.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-form@4.3.0/dist/jquery.form.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- APP BAR / BANNER -->
<div class="app-bar">
  <div class="app-bar-inner">
    <div class="app-banner-left">
      <div class="app-banner-icon"><i class="ti ti-building-community"></i></div>
      <div>
        <div class="app-banner-title">Cổng thông tin việc làm Trường Cao đẳng Kon Tum</div>
        <div class="app-banner-sub">Kết nối sinh viên, người tìm việc và doanh nghiệp tuyển dụng uy tín</div>
      </div>
    </div>
    <div class="app-banner-right">
      <div class="app-contact-list">
        <a href="tel:02603860000" class="app-contact-item"><i class="ti ti-phone-call"></i> 0260 3860 000</a>
        <a href="mailto:vieclam@cdkontum.edu.vn" class="app-contact-item"><i class="ti ti-mail"></i> vieclam@cdkontum.edu.vn</a>
        <a href="#" class="app-contact-item"><i class="ti ti-map-pin"></i> Kon Tum</a>
      </div>
    </div>
  </div>
</div>

<!-- HEADER -->
<header class="header">
  <div class="header-top">
    
<div class="header-top-inner">

  <a href="/" class="logo" aria-label="Trang chủ">
    <div class="logo-text"><img src="assets/images/logo2.png" alt="Logo"></div>
  </a>

  <nav class="header-nav desktop-nav">
    <a href="#">Trang chủ</a>
    <a href="http://localhost/frontend/trang-gioi-thieu-vieclam.php">Giới thiệu</a>
    <a href="http://localhost/frontend/tin-tuc.php">Tin tức</a>

    <div class="nav-item">
      <a href="#">Việc làm <i class="ti ti-chevron-down"></i></a>
      <div class="dropdown-menu">
          <a href="http://localhost/frontend/quan-ly-viec-lam.php" class="dropdown-item"><i class="ti ti-bolt"></i> Việc tìm người</a>
          <a href="http://localhost/frontend/quan-ly-ung-vien.php" class="dropdown-item"><i class="ti ti-star"></i> Người tìm việc</a>
      </div>
    </div>

    <div class="nav-item">
      <a href="#">Sàn việc làm <i class="ti ti-chevron-down"></i></a>
      <div class="dropdown-menu">
          <a href="http://localhost/frontend/gioi-thieu-san-viec-lam.php" class="dropdown-item"><i class="ti ti-building"></i> Giới thiệu sàn</a>
          <a href="http://localhost/frontend/quy-trinh-san-viec-lam.php" class="dropdown-item"><i class="ti ti-list-details"></i> Quy trình sàn</a>
          <a href="http://localhost/frontend/ket-qua-san-viec-lam.php" class="dropdown-item"><i class="ti ti-chart-bar"></i> Kết quả sàn</a>
          <a href="http://localhost/frontend/san-viec-lam-online.php" class="dropdown-item"><i class="ti ti-broadcast"></i>Sàn việc làm Online</a>
      </div>
    </div>

    <a href="http://localhost/frontend/huong-dan.php">Hướng dẫn</a>
    <a href="http://localhost/frontend/lien-he.php">Liên hệ</a>
  </nav>

  <div class="header-actions">

        <button type="button" class="btn-login js-login-open"><i class="ti ti-login-2" style="margin-right:4px"></i> Đăng nhập</button>
        <button class="btn-post js-employer-login-open"><i class="ti ti-building"></i>Nhà tuyển dụng</button>
      </div>

      <button class="hamburger" id="hamburgerBtn" aria-label="Mở menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>

  </header>


<!-- LOGIN MODAL -->
<div class="login-modal" id="loginModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="loginModalTitle">
  <div class="login-modal-backdrop" id="loginModalBackdrop"></div>
  <div class="login-modal-card" role="document">
    <div class="login-modal-head">
      <button type="button" class="login-modal-close" id="loginModalClose" aria-label="Đóng đăng nhập"><i class="ti ti-x"></i></button>
      <div class="login-modal-title-wrap">
        <div class="login-modal-icon"><i class="ti ti-user-shield"></i></div>
        <div>
          <h3 class="login-modal-title" id="loginModalTitle">Đăng nhập tài khoản</h3>
          <div class="login-modal-sub">Truy cập nhanh để ứng tuyển, quản lý CV và theo dõi việc làm phù hợp.</div>
        </div>
      </div>
    </div>
    <div class="login-modal-body">
      <form class="login-form" action="#" method="post">
        <div class="login-field">
          <label for="loginEmail">Email</label>
          <div class="login-input-wrap">
            <i class="ti ti-mail"></i>
            <input type="email" id="loginEmail" name="email" placeholder="Nhập email của bạn" autocomplete="email" required>
          </div>
        </div>

        <div class="login-field">
          <label for="loginPassword">Mật khẩu</label>
          <div class="login-input-wrap">
            <i class="ti ti-lock"></i>
            <input type="password" id="loginPassword" name="password" placeholder="Nhập mật khẩu" autocomplete="current-password" required>
          </div>
        </div>

        <div class="login-options">
          <!-- <label class="login-remember"><input type="checkbox" name="remember"> Ghi nhớ đăng nhập</label> -->
          <a href="http://localhost/frontend/quen-mat-khau.php" class="login-forgot">Quên mật khẩu?</a>
        </div>

        <button type="submit" class="login-submit"><i class="ti ti-login-2"></i> Đăng nhập</button>

        <div class="login-divider"><span>hoặc</span></div>

        <button type="button" class="login-google">
          <svg viewBox="0 0 48 48" aria-hidden="true">
            <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.1 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
            <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 15.1 19 12 24 12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.1 6.1 29.3 4 24 4 16.3 4 9.6 8.3 6.3 14.7z"/>
            <path fill="#4CAF50" d="M24 44c5.2 0 10-2 13.5-5.2l-6.2-5.2C29.3 35.1 26.8 36 24 36c-5.3 0-9.7-3.3-11.3-7.9l-6.5 5C9.5 39.6 16.2 44 24 44z"/>
            <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.2-4.1 5.6l6.2 5.2C36.9 39.3 44 34 44 24c0-1.3-.1-2.4-.4-3.5z"/>
          </svg>
          Đăng nhập bằng Google
        </button>

        <div class="login-register-note">Bạn chưa có tài khoản? <a href="#">Đăng ký miễn phí</a></div>
      </form>
    </div>
  </div>
</div>

<!-- MOBILE DRAWER MENU -->
<div class="mobile-menu" id="mobileMenu" aria-hidden="true">
  <div class="mobile-menu-backdrop" id="menuBackdrop"></div>
  <div class="mobile-menu-drawer">
    <div class="mobile-menu-header">
      <div class="mm-logo"><span class="red">Việc</span><span class="dark">Làm</span><span class="red"></span></div>
      <button class="mobile-menu-close" id="menuCloseBtn"><i class="ti ti-x"></i></button>
    </div>

    <div class="mm-user-section">
      <div class="mm-user-label">Sẵn sàng để bắt đầu công việc mơ ước?</div>
      <div class="mm-btn-group">
          <button type="button" class="mm-btn-login js-login-open">Đăng nhập</button>
          <button class="mm-btn-ntd" style="background:#333">Đăng ký</button>
      </div>
      <div class="mobile-quick-actions">
        <button class="mm-btn-login"><i class="ti ti-user-plus"></i> Đăng ký</button>
        <button class="mm-btn-ntd"><i class="ti ti-speakerphone"></i> Đăng tin</button>
      </div>
    </div>

    <nav class="mm-nav">
      <div class="mm-nav-section">Khám phá</div>
<div class="mm-nav-item">
    <div class="mm-menu-link" onclick="toggleMobileSubmenu(this)">
        <span><i class="ti ti-briefcase"></i> Việc làm</span>
        <i class="ti ti-chevron-down mm-arrow"></i>
    </div>
    <div class="mm-submenu">
        <a href="#"><i class="ti ti-star"></i> Việc làm tuyển nhanh</a>
        <a href="#"><i class="ti ti-bolt"></i> Việc làm hấp dẫn</a>
    </div>
</div>
      <a href="#"><i class="ti ti-bolt"></i>Việc làm gấp <i class="ti ti-chevron-right mm-arrow"></i></a>
      <a href="#"><i class="ti ti-file-cv"></i>Tạo CV Online <i class="ti ti-chevron-right mm-arrow"></i></a>
      
      <div class="mm-nav-divider"></div>
      
      <div class="mm-nav-section">Khu vực</div>
      <a href="#"><i class="ti ti-map-pin"></i>TP. Hồ Chí Minh</a>
      <a href="#"><i class="ti ti-map-pin"></i>Hà Nội</a>
      <a href="#"><i class="ti ti-map-pin"></i>Bình Dương</a>
    </nav>

    <div class="mm-bottom">
      <button class="mm-btn-ntd js-employer-login-open" style="width:100%; margin-bottom: 15px;">
        <i class="ti ti-speakerphone"></i> Cho Nhà Tuyển Dụng
      </button>
      <div class="mm-bottom-label">Kết nối xã hội</div>
      <div class="mm-socials">
        <a href="#"><i class="ti ti-brand-facebook"></i></a>
        <a href="#"><i class="ti ti-brand-tiktok"></i></a>
        <a href="#"><i class="ti ti-brand-youtube"></i></a>
      </div>
    </div>
  </div>
</div>
<script>
function toggleMobileSubmenu(el){
  var item = el.closest('.mm-nav-item');
  if(item){ item.classList.toggle('active'); }
}
(function(){
  var btn = document.getElementById('hamburgerBtn');
  var menu = document.getElementById('mobileMenu');
  var closeBtn = document.getElementById('menuCloseBtn');
  var backdrop = document.getElementById('menuBackdrop');
  if (!btn || !menu || !closeBtn || !backdrop) return;

  function openMenu(){
    document.body.classList.add('menu-open');
    btn.classList.add('open');
    btn.setAttribute('aria-expanded', 'true');
    menu.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu(){
    document.body.classList.remove('menu-open');
    btn.classList.remove('open');
    btn.setAttribute('aria-expanded', 'false');
    menu.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  btn.addEventListener('click', function(e){
    e.preventDefault();
    if (document.body.classList.contains('menu-open')) closeMenu();
    else openMenu();
  });
  closeBtn.addEventListener('click', closeMenu);
  backdrop.addEventListener('click', closeMenu);
  document.addEventListener('keydown', function(e){
    if (e.key === 'Escape' && document.body.classList.contains('menu-open')) closeMenu();
  });
})();


(function(){
  var modal = document.getElementById('loginModal');
  var closeBtn = document.getElementById('loginModalClose');
  var backdrop = document.getElementById('loginModalBackdrop');
  var loginButtons = document.querySelectorAll('.js-login-open');
  var firstInput = document.getElementById('loginEmail');
  var lastActiveElement = null;
  if (!modal || !closeBtn || !backdrop || !loginButtons.length) return;

  function closeMobileMenuIfOpen(){
    var hamburgerBtn = document.getElementById('hamburgerBtn');
    var mobileMenu = document.getElementById('mobileMenu');
    if(document.body.classList.contains('menu-open')){
      document.body.classList.remove('menu-open');
      document.body.style.overflow = '';
      if(hamburgerBtn){
        hamburgerBtn.classList.remove('open');
        hamburgerBtn.setAttribute('aria-expanded','false');
      }
      if(mobileMenu){ mobileMenu.setAttribute('aria-hidden','true'); }
    }
  }

  function openLoginModal(){
    lastActiveElement = document.activeElement;
    closeMobileMenuIfOpen();
    modal.classList.add('open');
    modal.setAttribute('aria-hidden','false');
    document.body.classList.add('login-modal-open');
    setTimeout(function(){ if(firstInput) firstInput.focus(); }, 80);
  }

  function closeLoginModal(){
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden','true');
    document.body.classList.remove('login-modal-open');
    if(lastActiveElement && typeof lastActiveElement.focus === 'function'){
      setTimeout(function(){ lastActiveElement.focus(); }, 80);
    }
  }

  loginButtons.forEach(function(button){
    button.addEventListener('click', function(e){
      e.preventDefault();
      openLoginModal();
    });
  });

  closeBtn.addEventListener('click', closeLoginModal);
  backdrop.addEventListener('click', closeLoginModal);
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape' && modal.classList.contains('open')) closeLoginModal();
  });
})();
</script>


<!-- EMPLOYER LOGIN MODAL INTEGRATED -->
<div class="employer-login-modal" id="employerLoginModal" aria-hidden="true">
  <div class="employer-login-backdrop" data-employer-close></div>

  <div class="employer-login-card" role="dialog" aria-modal="true" aria-labelledby="employerLoginTitle">
    <div class="employer-login-left">
      <div class="employer-login-badge">
        <i class="ti ti-building-skyscraper"></i> Không gian tuyển dụng chuyên nghiệp
      </div>

      <h2>Kết nối doanh nghiệp với ứng viên phù hợp nhanh hơn.</h2>
      <p>Đăng nhập để đăng tin tuyển dụng, quản lý ứng viên, theo dõi lịch phỏng vấn và xây dựng thương hiệu tuyển dụng chuyên nghiệp.</p>

      <div class="employer-login-stats">
        <div class="employer-login-stat">
          <strong>2.000+</strong>
          <span>Hồ sơ ứng viên</span>
        </div>
        <div class="employer-login-stat">
          <strong>500+</strong>
          <span>Doanh nghiệp</span>
        </div>
        <div class="employer-login-stat">
          <strong>24/7</strong>
          <span>Hỗ trợ trực tuyến</span>
        </div>
      </div>
    </div>

    <div class="employer-login-right">
      <div class="employer-login-head">
        <button type="button" class="employer-login-close" data-employer-close aria-label="Đóng">
          <i class="ti ti-x"></i>
        </button>
        <div class="employer-login-title" id="employerLoginTitle">Đăng nhập Nhà tuyển dụng</div>
        <div class="employer-login-sub">Quản lý tin tuyển dụng và hồ sơ ứng viên của doanh nghiệp.</div>
      </div>

      <form action="#" method="post">
        <div class="employer-field">
          <label>Email hoặc số điện thoại</label>
          <div class="employer-input-wrap">
            <i class="ti ti-mail"></i>
            <input type="text" name="employer_account" placeholder="Nhập email hoặc số điện thoại" autocomplete="username">
          </div>
        </div>

        <div class="employer-field">
          <label>Mật khẩu</label>
          <div class="employer-input-wrap">
            <i class="ti ti-lock"></i>
            <input type="password" name="employer_password" placeholder="Nhập mật khẩu" autocomplete="current-password">
          </div>
        </div>

        <div class="employer-options">
         
          <a href="http://localhost/frontend/quen-mat-khau.php" class="employer-forgot">Quên mật khẩu?</a>
        </div>

        <button type="submit" class="employer-submit">
          <i class="ti ti-login-2"></i> Đăng nhập Nhà tuyển dụng
        </button>

        

        <div class="employer-note">
          Chưa có tài khoản doanh nghiệp? <a href="#">Đăng ký ngay</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
(function(){
  function onReady(fn){
    if(document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  onReady(function(){
    var modal = document.getElementById('employerLoginModal');
    if(!modal) return;

    var openButtons = document.querySelectorAll('.js-employer-login-open, .btn-post');
    var closeButtons = modal.querySelectorAll('[data-employer-close]');

    function closeOtherMenus(){
      document.body.classList.remove('menu-open');
      var mobileMenu = document.getElementById('mobileMenu');
      var hamburger = document.getElementById('hamburgerBtn');
      if(mobileMenu) mobileMenu.setAttribute('aria-hidden','true');
      if(hamburger){
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded','false');
      }
    }

    function openEmployerModal(e){
      if(e) e.preventDefault();
      closeOtherMenus();
      modal.classList.add('open');
      modal.setAttribute('aria-hidden','false');
      document.body.classList.add('employer-modal-open');
    }

    function closeEmployerModal(){
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden','true');
      document.body.classList.remove('employer-modal-open');
    }

    openButtons.forEach(function(btn){
      btn.addEventListener('click', openEmployerModal);
    });

    closeButtons.forEach(function(btn){
      btn.addEventListener('click', closeEmployerModal);
    });

    document.addEventListener('keydown', function(e){
      if(e.key === 'Escape' && modal.classList.contains('open')){
        closeEmployerModal();
      }
    });
  });
})();
</script>


