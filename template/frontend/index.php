
<?php require "header.php"; ?>

<!-- HERO -->
<style>
  .hero-slider {
    position: relative;
    z-index: 10;
    isolation: isolate;
    overflow: visible;
    min-height: 390px;
    background: #0d4e96;
  }
  .hero-slider.location-dropdown-open {
    z-index: 10000;
  }
  .hero-slider-backgrounds,
  .hero-slider-background,
  .hero-slider-overlay {
    position: absolute;
    inset: 0;
  }
  .hero-slider-backgrounds {
    z-index: -3;
    overflow: hidden;
  }
  .hero-slider-background {
    background-position: center;
    background-size: cover;
    opacity: 0;
    transform: scale(1.04);
    transition: opacity .8s ease, transform 7s ease;
  }
  .hero-slider-background.active {
    opacity: 1;
    transform: scale(1);
  }
  .hero-slider-overlay {
    z-index: -2;
    pointer-events: none;
    background: linear-gradient(90deg, rgba(4, 30, 61, .58) 0%, rgba(13, 78, 150, .28) 55%, rgba(4, 30, 61, .2) 100%);
  }
  .hero-slider .hero-inner {
    position: relative;
    z-index: 2;
  }
  .hero-slider h1,
  .hero-slider .hero-sub {
    color: rgba(255, 255, 255, .94);
    text-shadow: 0 2px 14px rgba(0, 0, 0, .55);
  }
  .hero-slider .hero-badge {
    color: #fff;
    background: rgba(4, 30, 61, .25);
    border-color: rgba(255, 255, 255, .42);
    box-shadow: 0 8px 22px rgba(0, 0, 0, .12);
    backdrop-filter: blur(6px);
  }
  .hero-slider .search-wrap {
    background: rgba(255, 255, 255, .28);
    border-color: rgba(255, 255, 255, .55);
    box-shadow: 0 10px 28px rgba(0, 0, 0, .16);
    backdrop-filter: blur(10px);
  }
  .hero-slider .search-wrap:focus-within {
    background: rgba(255, 255, 255, .4);
    border-color: rgba(255, 255, 255, .9);
  }
  .hero-slider .search-input {
    background: transparent;
    color: #fff;
  }
  .hero-slider .search-input::placeholder {
    color: rgba(255, 255, 255, .78);
  }
  .hero-slider .search-icon,
  .hero-slider .search-location,
  .hero-slider .search-location i.pin,
  .hero-slider .search-location i.chevron {
    color: rgba(255, 255, 255, .92);
  }
  .hero-slider .search-divider {
    background: rgba(255, 255, 255, .42);
  }
  .hero-slider .search-btn {
    background: rgba(13, 78, 150, .68);
    border-left: 1px solid rgba(255, 255, 255, .28);
    backdrop-filter: blur(8px);
  }
  .hero-slider .search-btn:hover {
    background: rgba(13, 78, 150, .9);
  }
  .hero-slider .hero-login-card {
    background: rgba(255, 255, 255, .3);
    border-color: rgba(255, 255, 255, .48);
    box-shadow: 0 12px 30px rgba(0, 0, 0, .18);
    backdrop-filter: blur(10px);
  }
  .hero-slider .hero-login-card p,
  .hero-slider .hero-login-card span {
    color: rgba(255, 255, 255, .94);
    text-shadow: 0 1px 8px rgba(0, 0, 0, .42);
  }
  .hero-slider .btn-google {
    color: #fff;
    background: rgba(255, 255, 255, .18);
    border-color: rgba(255, 255, 255, .5);
    backdrop-filter: blur(7px);
  }
  .hero-slider .btn-google:hover {
    background: rgba(255, 255, 255, .3);
    border-color: rgba(255, 255, 255, .85);
  }
  .hero-slider .btn-login-hero {
    background: rgba(13, 78, 150, .68);
    border: 1px solid rgba(255, 255, 255, .36);
    backdrop-filter: blur(7px);
  }
  .hero-slider .btn-login-hero:hover {
    background: rgba(13, 78, 150, .9);
  }
  .hero-slider .location-dropdown {
    z-index: 500;
    background: rgba(255, 255, 255, .88);
    backdrop-filter: blur(14px);
  }
  .hero-slider-nav {
    position: absolute;
    z-index: 5;
    top: 50%;
    width: 44px;
    height: 44px;
    border: 1px solid rgba(255, 255, 255, .55);
    border-radius: 50%;
    display: grid;
    place-items: center;
    background: rgba(4, 30, 61, .46);
    color: #fff;
    cursor: pointer;
    transform: translateY(-50%);
    transition: background .2s ease, transform .2s ease;
  }
  .hero-slider-nav:hover,
  .hero-slider-nav:focus-visible {
    background: #0d4e96;
    transform: translateY(-50%) scale(1.08);
    outline: 2px solid #fff;
    outline-offset: 2px;
  }
  .hero-slider-prev { left: 16px; }
  .hero-slider-next { right: 16px; }
  .hero-slider-dots {
    position: absolute;
    z-index: 5;
    bottom: 14px;
    left: 50%;
    display: flex;
    gap: 8px;
    transform: translateX(-50%);
  }
  .hero-slider-dot {
    width: 9px;
    height: 9px;
    padding: 0;
    border: 1px solid rgba(255, 255, 255, .8);
    border-radius: 99px;
    background: rgba(255, 255, 255, .42);
    cursor: pointer;
    transition: width .25s ease, background .25s ease;
  }
  .hero-slider-dot.active {
    width: 28px;
    background: #fff;
  }
  @media (max-width: 768px) {
    .hero-slider { min-height: 350px; }
    .hero-slider-nav { width: 36px; height: 36px; }
    .hero-slider-prev { left: 6px; }
    .hero-slider-next { right: 6px; }
    .hero-slider .hero-inner { padding-left: 34px; padding-right: 34px; }
  }
  @media (max-width: 480px) {
    .hero-slider .search-wrap {
      position: relative;
    }
    .hero-slider .search-location-box {
      position: static;
    }
    .hero-slider .location-dropdown.open {
      display: flex;
      flex-direction: column;
      top: calc(100% + 8px);
      right: 0;
      bottom: auto;
      left: 0;
      width: 100%;
      max-height: min(55dvh, 420px);
      padding-bottom: 10px;
      border-radius: 14px;
      background: #fff;
      backdrop-filter: none;
      box-shadow: 0 18px 50px rgba(4, 30, 61, .28);
    }
    .hero-slider .location-dropdown-search {
      flex: 0 0 auto;
    }
    .hero-slider .location-list {
      flex: 1 1 auto;
      min-height: 0;
      max-height: none;
      overscroll-behavior: contain;
    }
  }
  @media (prefers-reduced-motion: reduce) {
    .hero-slider-background { transition: opacity .2s ease; transform: none; }
  }
</style>
<section class="hero hero-slider" id="heroSlider" aria-roledescription="carousel" aria-label="Banner việc làm nổi bật">
  <div class="hero-slider-backgrounds" aria-hidden="true">
    <div class="hero-slider-background active" style="background-image:url('https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=2000&q=85')"></div>
    <div class="hero-slider-background" style="background-image:url('https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=2000&q=85')"></div>
    <div class="hero-slider-background" style="background-image:url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=2000&q=85')"></div>
  </div>
  <div class="hero-slider-overlay" aria-hidden="true"></div>
  <button type="button" class="hero-slider-nav hero-slider-prev" id="heroSliderPrev" aria-label="Slider trước">
    <i class="ti ti-chevron-left" aria-hidden="true"></i>
  </button>
  <button type="button" class="hero-slider-nav hero-slider-next" id="heroSliderNext" aria-label="Slider tiếp theo">
    <i class="ti ti-chevron-right" aria-hidden="true"></i>
  </button>
  <div class="hero-inner">
    <div class="hero-left">
      <div class="hero-badge"><i class="ti ti-award"></i> Hệ thống cổng thông tin việc làm</div>
      <h1>Trường Cao đẳng Kon Tum<br>Hệ thống kết nối sinh viên - doanh nghiệp</h1>
      <p class="hero-sub">Tìm việc nhanh chóng. Ứng tuyển dễ dàng.</p>

      <div class="search-wrap" id="heroSearchWrap">
        <i class="ti ti-search search-icon"></i>
        <input class="search-input" type="text" placeholder="Vị trí tuyển dụng, tên công ty..."/>
        <div class="search-divider"></div>
        <div class="search-location-box">
          <div class="search-location" id="searchLocationBtn" role="button" tabindex="0" aria-expanded="false" aria-haspopup="listbox">
            <i class="ti ti-map-pin pin"></i>
            <span class="search-location-label" id="searchLocationLabel">Toàn quốc</span>
            <i class="ti ti-chevron-down chevron"></i>
          </div>
          <div class="location-dropdown" id="locationDropdown" role="listbox" aria-hidden="true">
            <div class="location-dropdown-search">
              <i class="ti ti-search"></i>
              <input type="text" id="locationSearchInput" placeholder="Tìm khu vực..." autocomplete="off" aria-label="Tìm khu vực"/>
            </div>
            <ul class="location-list" id="locationList"></ul>
            <p class="location-empty" id="locationEmpty" hidden>Không tìm thấy khu vực phù hợp</p>
          </div>
        </div>
        <button type="button" class="search-btn"><i class="ti ti-search" style="margin-right:6px;vertical-align:-2px"></i> Tìm việc</button>
      </div>

      <!-- <div class="quick-links">
        <a href="https://vieclam.vn/viec-lam-ha-noi-p73.html" class="quick-link">Việc làm Hà Nội</a>
        <a href="https://vieclam.vn/viec-lam-tp-hcm-p122.html" class="quick-link">Việc làm TPHCM</a>
        <a href="https://vieclam.vn/viec-lam-marketing-o12.html" class="quick-link">Việc làm Marketing</a>
        <a href="https://vieclam.vn/viec-lam-ke-toan-o17.html" class="quick-link">Việc làm kế toán</a>
        <a href="https://vieclam.vn/viec-lam-binh-duong-p119.html" class="quick-link">Việc làm Bình Dương</a>
        <a href="https://vieclam.vn/viec-lam-nhan-su-o22.html" class="quick-link">Tuyển dụng nhân sự</a>
        <a href="https://vieclam.vn/viec-lam-tuyen-nhanh.html" class="quick-link special">⚡ Việc đi làm ngay</a>
        <a href="https://vieclam.vn/tim-kiem-viec-lam-nhanh?is_cv_optional=1" class="quick-link special2">✅ Việc không cần CV</a>
      </div> -->
    </div>

    <div style="width:300px;flex-shrink:0">
      <div class="hero-login-card">
        <p>Đăng nhập để xem ngay việc làm phù hợp hơn!</p>
        <span>Việc làm sẽ được gợi ý theo vị trí, kinh nghiệm và kỹ năng của bạn.</span>
        <div class="btn-google">
          <svg width="18" height="18" viewBox="0 0 18 18"><path fill="#4285F4" d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 002.38-5.88c0-.57-.05-.66-.15-1.18z"/><path fill="#34A853" d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2a4.8 4.8 0 01-7.18-2.54H1.83v2.07A8 8 0 008.98 17z"/><path fill="#FBBC05" d="M4.5 10.52a4.8 4.8 0 010-3.04V5.41H1.83a8 8 0 000 7.18l2.67-2.07z"/><path fill="#EA4335" d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 001.83 5.4L4.5 7.49a4.77 4.77 0 014.48-3.31z"/></svg>
          Đăng nhập bằng Google
        </div>
        <button class="btn-login-hero">Đăng ký</button>
      </div>
    </div>
  </div>
  <div class="hero-slider-dots" id="heroSliderDots" aria-label="Chọn slider"></div>
</section>

<script>
  (function () {
    var slider = document.getElementById('heroSlider');
    if (!slider) return;

    var slides = Array.prototype.slice.call(slider.querySelectorAll('.hero-slider-background'));
    var dotsWrap = document.getElementById('heroSliderDots');
    var prev = document.getElementById('heroSliderPrev');
    var next = document.getElementById('heroSliderNext');
    var current = 0;
    var timer = null;
    var interval = 7000;

    function showSlide(index) {
      current = (index + slides.length) % slides.length;
      slides.forEach(function (slide, slideIndex) {
        slide.classList.toggle('active', slideIndex === current);
      });
      Array.prototype.slice.call(dotsWrap.children).forEach(function (dot, dotIndex) {
        var isActive = dotIndex === current;
        dot.classList.toggle('active', isActive);
        dot.setAttribute('aria-current', isActive ? 'true' : 'false');
      });
    }

    function startAutoPlay() {
      window.clearInterval(timer);
      timer = window.setInterval(function () {
        showSlide(current + 1);
      }, interval);
    }

    slides.forEach(function (_, index) {
      var dot = document.createElement('button');
      dot.type = 'button';
      dot.className = index === 0 ? 'hero-slider-dot active' : 'hero-slider-dot';
      dot.setAttribute('aria-label', 'Hiển thị slider ' + (index + 1));
      dot.setAttribute('aria-current', index === 0 ? 'true' : 'false');
      dot.addEventListener('click', function () {
        showSlide(index);
        startAutoPlay();
      });
      dotsWrap.appendChild(dot);
    });

    prev.addEventListener('click', function () {
      showSlide(current - 1);
      startAutoPlay();
    });
    next.addEventListener('click', function () {
      showSlide(current + 1);
      startAutoPlay();
    });
    slider.addEventListener('mouseenter', function () { window.clearInterval(timer); });
    slider.addEventListener('mouseleave', startAutoPlay);
    slider.addEventListener('focusin', function () { window.clearInterval(timer); });
    slider.addEventListener('focusout', startAutoPlay);
    slider.addEventListener('keydown', function (event) {
      if (event.key === 'ArrowLeft') prev.click();
      if (event.key === 'ArrowRight') next.click();
    });

    startAutoPlay();
  }());
</script>

<!-- INDUSTRY TABS -->
<!-- <div class="industry-bar">
  <div class="industry-bar-inner">
    <div class="ind-tab active">Bán sỉ - Bán lẻ - Quản lý cửa hàng</div>
    <div class="ind-tab">Bán hàng - Kinh doanh</div>
    <div class="ind-tab">Marketing</div>
    <div class="ind-tab">Khoa học - Kỹ thuật</div>
    <div class="ind-tab">Kiểm toán</div>
    <div class="ind-more">Tất cả các ngành &rsaquo;</div>
  </div>
</div> -->

<!-- BANNER -->
<div class="banner-section">
  <a href="#" class="banner-img">
    <img src="https://cdn1.vieclam.vn/images/seeker-banner/2025/11/17/desktop_2580x574.jpg"
         alt="Banner Vieclam"
         onerror="this.parentElement.style.background='linear-gradient(135deg,#0d4e96,#ff8a65)';this.style.display='none'"/>
  </a>
</div>
<!-- VIỆC LÀM NỔI BẬT -->
<style>
  .featured-job-filters {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 20px;
  }
  .featured-filter-field {
    position: relative;
    min-width: 0;
  }
  .featured-filter-field i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #778395;
    font-size: 18px;
    pointer-events: none;
  }
  .featured-filter-field select {
    width: 100%;
    height: 48px;
    border: 1px solid #d7e4f2;
    border-radius: 8px;
    background: #fff;
    color: #263247;
    font-size: 14px;
    font-weight: 650;
    outline: none;
    padding: 0 38px 0 42px;
    appearance: none;
    cursor: pointer;
  }
  .featured-filter-field:after {
    content: "";
    position: absolute;
    right: 16px;
    top: 50%;
    width: 8px;
    height: 8px;
    border-right: 1.8px solid #263247;
    border-bottom: 1.8px solid #263247;
    transform: translateY(-65%) rotate(45deg);
    pointer-events: none;
  }
  .featured-jobs-empty {
    display: none;
    padding: 24px;
    border: 1px dashed #d7dfe8;
    border-radius: 10px;
    background: #fff;
    color: #667085;
    text-align: center;
  }
  @media (max-width: 900px) {
    .featured-job-filters {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }
  @media (max-width: 560px) {
    .featured-job-filters {
      grid-template-columns: 1fr;
    }
  }
</style>
<section class="section" style="background:#fff">
  <div class="section-inner">
    <div class="section-header">
      <div class="section-title">Việc làm nổi bật hôm nay</div>
      <a href="https://vieclam.vn" class="see-all">Xem tất cả <i class="ti ti-arrow-right"></i></a>
    </div>
    <div class="urgent-jobs-filter featured-filter-bar" aria-label="Bộ lọc việc làm nổi bật">
      <div class="urgent-filter-select" id="featuredFilterSelect">
        <button type="button" class="urgent-filter-toggle" id="featuredFilterToggle" aria-expanded="false" aria-haspopup="listbox">
          <i class="ti ti-filter"></i>
          <span>Lọc theo:</span>
          <strong id="featuredFilterLabel">Lọc theo</strong>
          <i class="ti ti-chevron-down"></i>
        </button>
        <div class="urgent-filter-menu" id="featuredFilterMenu" role="listbox" aria-label="Chọn loại bộ lọc việc làm nổi bật">
          <button type="button" class="urgent-filter-option active" data-featured-filter-type="all" role="option" aria-selected="true">Lọc theo <i class="ti ti-check"></i></button>
          <button type="button" class="urgent-filter-option" data-featured-filter-type="salary" role="option" aria-selected="false">Mức lương <i class="ti ti-check"></i></button>
          <button type="button" class="urgent-filter-option" data-featured-filter-type="location" role="option" aria-selected="false">Địa điểm <i class="ti ti-check"></i></button>
          <button type="button" class="urgent-filter-option" data-featured-filter-type="industry" role="option" aria-selected="false">Ngành nghề <i class="ti ti-check"></i></button>
          <button type="button" class="urgent-filter-option" data-featured-filter-type="experience" role="option" aria-selected="false">Kinh nghiệm <i class="ti ti-check"></i></button>
        </div>
      </div>
      <button type="button" class="urgent-filter-nav prev" id="featuredFilterPrev" aria-label="Cuộn bộ lọc sang trái"><i class="ti ti-chevron-left"></i></button>
      <div class="urgent-filter-chips" id="featuredFilterChips">
        <button type="button" class="urgent-filter-chip active" data-filter-value="all">Tất cả</button>
      </div>
      <button type="button" class="urgent-filter-nav next" id="featuredFilterNext" aria-label="Cuộn bộ lọc sang phải"><i class="ti ti-chevron-right"></i></button>
      <label class="mobile-filter-value">
        <i class="ti ti-cash" id="featuredMobileFilterIcon"></i>
        <select id="featuredMobileFilterValue" aria-label="Giá trị lọc việc làm nổi bật"></select>
      </label>
    </div>
    <div id="jobsSliderWrap" class="jobs-slider-wrap">
      <div id="jobsTrack" class="jobs-track">
        <div class="jobs-grid" id="jobsGrid">

      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
<div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">VIB</div>
          <div>
            <div class="job-title">Chuyên viên Tư vấn Tài chính Cá nhân</div>
            <div class="company-name"><i class="ti ti-building"></i> VIB – Ngân hàng Quốc tế</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      
      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#e3f2fd;color:#1565c0">VPB</div>
          <div>
            <div class="job-title">Nhân Viên Kinh Doanh / Sales Banking</div>
            <div class="company-name"><i class="ti ti-building"></i> VPBank</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">8 – 15 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> TP.HCM</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 3 giờ trước</span>
        </div>
      </div>

      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#e8f5e9;color:#2e7d32">FPT</div>
          <div>
            <div class="job-title">Lập Trình Viên Java / ReactJS Senior</div>
            <div class="company-name"><i class="ti ti-building"></i> FPT Software</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">18 – 35 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-urgent">Tuyển gấp</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 5 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>

      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fce4ec;color:#c62828">MBB</div>
          <div>
            <div class="job-title">Chuyên viên Marketing Digital / Content</div>
            <div class="company-name"><i class="ti ti-building"></i> MBBank</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">12 – 22 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> TP.HCM</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> Hôm nay</span>
        </div>
      </div>

      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#f3e5f5;color:#6a1b9a">VIN</div>
          <div>
            <div class="job-title">Nhân Viên Nhân Sự (HR) – C&B Specialist</div>
            <div class="company-name"><i class="ti ti-building"></i> Vingroup</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 – 18 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> Hôm nay</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>

      <div class="job-card">
        <div class="job-card-header">
          <div class="company-logo" style="background:#e0f2f1;color:#00695c">SAM</div>
          <div>
            <div class="job-title">Kế toán Tổng hợp / General Accountant</div>
            <div class="company-name"><i class="ti ti-building"></i> Samsung Vina</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">9 – 14 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Bình Dương</span>
          <span class="tag tag-type">Full-time</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> Hôm nay</span>
          <span class="urgent-badge">GẤP</span>
        </div>
      </div>

        </div>
      </div>
    </div>

    <div class="jobs-pagination" id="jobsPagination" aria-label="Phân trang việc làm nổi bật">
      <button type="button" class="jobs-nav jobs-nav-prev" id="jobsPrev" aria-label="Trang trước"><i class="ti ti-chevron-left"></i></button>
      <div class="jobs-dots-wrap" id="jobsDots"></div>
      
      <button type="button" class="jobs-nav jobs-nav-next" id="jobsNext" aria-label="Trang sau"><i class="ti ti-chevron-right"></i></button>
    </div>
    <div class="featured-jobs-empty" id="featuredJobsEmpty">Không có việc làm nổi bật phù hợp với bộ lọc đã chọn.</div>
  </div>
</section>

<!-- VIEC LAM TUYEN GAP -->
<style>
  .urgent-jobs-filter {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 16px;
    overflow: visible;
    position: relative;
    z-index: 30;
  }
  .urgent-filter-select {
    position: relative;
    width: 250px;
    max-width: 100%;
    flex-shrink: 0;
  }
  .urgent-filter-toggle {
    width: 100%;
    height: 38px;
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 0 11px;
    border: 1px solid #c8ddf2;
    border-radius: 8px;
    background: #fff;
    color: #6b7280;
    cursor: pointer;
    transition: border-color 0.18s ease, box-shadow 0.18s ease;
  }
  .urgent-filter-select.open .urgent-filter-toggle,
  .urgent-filter-toggle:hover {
    border-color: #0d4e96;
    box-shadow: 0 5px 14px rgba(13, 78, 150, 0.10);
  }
  .urgent-filter-toggle i {
    color: #0d4e96;
    font-size: 17px;
  }
  .urgent-filter-toggle span {
    font-size: 13px;
    white-space: nowrap;
  }
  .urgent-filter-toggle strong {
    color: #263247;
    font-size: 13px;
    font-weight: 700;
  }
  .urgent-filter-toggle .ti-chevron-down {
    margin-left: auto;
    color: #263247;
    font-size: 15px;
  }
  .urgent-filter-menu {
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 6px);
    z-index: 20;
    display: none;
    padding: 6px;
    border: 1px solid #0d4e96;
    border-radius: 10px;
    background: #fff;
    box-shadow: 0 12px 28px rgba(13, 78, 150, 0.14);
  }
  .urgent-filter-select.open .urgent-filter-menu {
    display: block;
  }
  .urgent-filter-option {
    width: 100%;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: 0;
    border-radius: 8px;
    padding: 0 11px;
    background: #fff;
    color: #111827;
    font-size: 13px;
    font-weight: 650;
    text-align: left;
    cursor: pointer;
  }
  .urgent-filter-option.active {
    background: #eef6ff;
    color: #0d4e96;
  }
  .urgent-filter-option i {
    display: none;
    color: #0d4e96;
    font-size: 17px;
  }
  .urgent-filter-option.active i {
    display: inline-block;
  }
  .urgent-filter-nav {
    width: 36px;
    height: 36px;
    border: 0;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #0d4e96;
    background: #eef6ff;
    cursor: pointer;
    flex-shrink: 0;
  }
  .urgent-filter-nav.next {
    color: #0d4e96;
    background: #fff;
    border: 1px solid #0d4e96;
  }
  .urgent-filter-chips {
    display: flex;
    align-items: center;
    gap: 9px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scrollbar-width: none;
    flex: 1;
  }
  .urgent-filter-chips::-webkit-scrollbar {
    display: none;
  }
  .urgent-filter-chip {
    min-width: max-content;
    height: 38px;
    padding: 0 17px;
    border: 1px solid #e5edf5;
    border-radius: 999px;
    background: #fff;
    color: #263247;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .urgent-filter-chip:hover {
    transform: translateY(-1px);
  }
  .urgent-filter-chip.active {
    border-color: #0d4e96;
    background: #0d4e96;
    color: #fff;
    box-shadow: 0 7px 16px rgba(13, 78, 150, 0.16);
  }
  .urgent-jobs-empty {
    display: none;
    padding: 24px;
    border: 1px dashed #d7dfe8;
    border-radius: 10px;
    background: #fff;
    color: #667085;
    text-align: center;
  }
  .mobile-filter-value {
    position: relative;
    display: none;
    flex: 1;
    min-width: 0;
  }
  .mobile-filter-value i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1;
    color: #7f8da0;
    font-size: 18px;
    pointer-events: none;
  }
  .mobile-filter-value select {
    width: 100%;
    height: 38px;
    border: 1px solid #bfd7f0;
    border-radius: 8px;
    background: #fff;
    color: #263247;
    font-size: 13px;
    font-weight: 650;
    outline: none;
    padding: 0 34px 0 38px;
    appearance: none;
  }
  .mobile-filter-value:after {
    content: "";
    position: absolute;
    right: 14px;
    top: 50%;
    width: 8px;
    height: 8px;
    border-right: 1.7px solid #263247;
    border-bottom: 1.7px solid #263247;
    transform: translateY(-65%) rotate(45deg);
    pointer-events: none;
  }
  @media (max-width: 900px) {
    .urgent-jobs-filter {
      align-items: stretch;
      flex-wrap: wrap;
    }
    .urgent-filter-select {
      width: 100%;
    }
    .urgent-filter-chips {
      order: 3;
      flex-basis: 100%;
    }
  }
  @media (max-width: 560px) {
    .urgent-jobs-filter {
      display: grid;
      grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
      gap: 10px;
    }
    .urgent-filter-select {
      width: auto;
    }
    .urgent-filter-toggle {
      height: 42px;
      padding: 0 11px;
    }
    .urgent-filter-toggle span {
      display: none;
    }
    .urgent-filter-toggle strong {
      font-size: 14px;
    }
    .urgent-filter-toggle i {
      font-size: 18px;
    }
    .urgent-filter-chips,
    .urgent-filter-nav {
      display: none;
    }
    .mobile-filter-value {
      display: block;
    }
    .mobile-filter-value select {
      height: 42px;
      font-size: 14px;
    }
    .urgent-filter-chip {
      height: 44px;
      padding: 0 18px;
      font-size: 14px;
    }
  }
</style>
<section class="section" style="background:#f4f7fb">
  <div class="section-inner">
    <div class="section-header">
      <div class="section-title">Việc làm tuyển gấp</div>
      <a href="https://vieclam.vn/viec-lam-tuyen-nhanh.html" class="see-all">Xem tất cả <i class="ti ti-arrow-right"></i></a>
    </div>

    <div class="urgent-jobs-filter" aria-label="Lọc việc làm tuyển gấp">
      <div class="urgent-filter-select" id="urgentFilterSelect">
        <button type="button" class="urgent-filter-toggle" id="urgentFilterToggle" aria-expanded="false" aria-haspopup="listbox">
          <i class="ti ti-filter"></i>
          <span>Lọc theo:</span>
          <strong id="urgentFilterLabel">Lọc theo</strong>
          <i class="ti ti-chevron-down"></i>
        </button>
        <div class="urgent-filter-menu" id="urgentFilterMenu" role="listbox" aria-label="Chọn loại bộ lọc">
          <button type="button" class="urgent-filter-option active" data-filter-type="all" role="option" aria-selected="true">Lọc theo <i class="ti ti-check"></i></button>
          <button type="button" class="urgent-filter-option" data-filter-type="location" role="option" aria-selected="false">Địa điểm <i class="ti ti-check"></i></button>
          <button type="button" class="urgent-filter-option" data-filter-type="salary" role="option" aria-selected="false">Mức lương <i class="ti ti-check"></i></button>
          <button type="button" class="urgent-filter-option" data-filter-type="experience" role="option" aria-selected="false">Kinh nghiệm <i class="ti ti-check"></i></button>
          <button type="button" class="urgent-filter-option" data-filter-type="industry" role="option" aria-selected="false">Ngành nghề <i class="ti ti-check"></i></button>
        </div>
      </div>
      <button type="button" class="urgent-filter-nav prev" id="urgentSalaryPrev" aria-label="Cuộn bộ lọc sang trái"><i class="ti ti-chevron-left"></i></button>
      <div class="urgent-filter-chips" id="urgentSalaryChips">
        <button type="button" class="urgent-filter-chip active" data-filter-value="all">Tất cả</button>
      </div>
      <button type="button" class="urgent-filter-nav next" id="urgentSalaryNext" aria-label="Cuộn bộ lọc sang phải"><i class="ti ti-chevron-right"></i></button>
      <label class="mobile-filter-value">
        <i class="ti ti-map-pin" id="urgentMobileFilterIcon"></i>
        <select id="urgentMobileFilterValue" aria-label="Giá trị lọc việc làm tuyển gấp"></select>
      </label>
    </div>

    <div class="jobs-grid" id="urgentJobsGrid">
      <div class="job-card urgent-job-card" data-salary="7-10" data-location="tphcm" data-experience="none" data-industry="sales">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fff3e0;color:#e65100">MWG</div>
          <div>
            <div class="job-title">Nhân Viên Bán Hàng Đi Làm Ngay</div>
            <div class="company-name"><i class="ti ti-building"></i> Thế Giới Di Động</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">7 - 10 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> TP.HCM</span>
          <span class="tag tag-urgent">Tuyển gấp</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> Hôm nay</span>
          <span class="urgent-badge">GẤP</span>
        </div>
      </div>
      <div class="job-card urgent-job-card" data-salary="10-15" data-location="hanoi" data-experience="1-2" data-industry="logistics">
        <div class="job-card-header">
          <div class="company-logo" style="background:#e3f2fd;color:#1565c0">GHTK</div>
          <div>
            <div class="job-title">Điều Phối Kho Vận / Logistics</div>
            <div class="company-name"><i class="ti ti-building"></i> Giao Hàng Tiết Kiệm</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">10 - 15 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-urgent">Tuyển gấp</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 1 giờ trước</span>
          <span class="urgent-badge">GẤP</span>
        </div>
      </div>
      <div class="job-card urgent-job-card" data-salary="15-20" data-location="danang" data-experience="3-5" data-industry="it">
        <div class="job-card-header">
          <div class="company-logo" style="background:#e8f5e9;color:#2e7d32">FPT</div>
          <div>
            <div class="job-title">Tester Automation Tuyển Gấp</div>
            <div class="company-name"><i class="ti ti-building"></i> FPT Software</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">15 - 20 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Đà Nẵng</span>
          <span class="tag tag-urgent">Tuyển gấp</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 2 giờ trước</span>
          <span class="hot-badge">HOT</span>
        </div>
      </div>
      <div class="job-card urgent-job-card" data-salary="5-7" data-location="cantho" data-experience="none" data-industry="service">
        <div class="job-card-header">
          <div class="company-logo" style="background:#fce4ec;color:#c62828">GS</div>
          <div>
            <div class="job-title">Nhân Viên Chăm Sóc Khách Hàng</div>
            <div class="company-name"><i class="ti ti-building"></i> Golden Service</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">5 - 7 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Cần Thơ</span>
          <span class="tag tag-urgent">Tuyển gấp</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> Hôm nay</span>
          <span class="urgent-badge">GẤP</span>
        </div>
      </div>
      <div class="job-card urgent-job-card" data-salary="3-5" data-location="binhduong" data-experience="none" data-industry="sales">
        <div class="job-card-header">
          <div class="company-logo" style="background:#f3e5f5;color:#6a1b9a">CF</div>
          <div>
            <div class="job-title">Thu Ngân Cửa Hàng Ca Xoay</div>
            <div class="company-name"><i class="ti ti-building"></i> City Food</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">3 - 5 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Bình Dương</span>
          <span class="tag tag-urgent">Tuyển gấp</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> Hôm nay</span>
          <span class="urgent-badge">GẤP</span>
        </div>
      </div>
      <div class="job-card urgent-job-card" data-salary="1-3" data-location="hanoi" data-experience="none" data-industry="hr">
        <div class="job-card-header">
          <div class="company-logo" style="background:#e0f2f1;color:#00695c">PT</div>
          <div>
            <div class="job-title">Thực Tập Sinh Tuyển Dụng Part-time</div>
            <div class="company-name"><i class="ti ti-building"></i> People Talent</div>
          </div>
        </div>
        <div class="job-card-tags">
          <span class="tag tag-salary">1 - 3 triệu</span>
          <span class="tag tag-location"><i class="ti ti-map-pin" style="font-size:10px"></i> Hà Nội</span>
          <span class="tag tag-urgent">Tuyển gấp</span>
        </div>
        <div class="job-card-footer">
          <span class="job-date"><i class="ti ti-clock"></i> 3 giờ trước</span>
          <span class="urgent-badge">GẤP</span>
        </div>
      </div>
    </div>
    <div class="urgent-jobs-empty" id="urgentJobsEmpty">Không có việc làm tuyển gấp phù hợp với bộ lọc đã chọn.</div>
    <div class="jobs-pagination" id="urgentJobsPagination" aria-label="Phân trang việc làm tuyển gấp">
      <button type="button" class="jobs-nav jobs-nav-prev" id="urgentJobsPrev" aria-label="Trang trước"><i class="ti ti-chevron-left"></i></button>
      <div class="jobs-dots-wrap" id="urgentJobsDots"></div>
      <button type="button" class="jobs-nav jobs-nav-next" id="urgentJobsNext" aria-label="Trang sau"><i class="ti ti-chevron-right"></i></button>
    </div>
  </div>
</section>

<script>
  (function () {
    var featuredGrid = document.getElementById('jobsGrid');
    var featuredEmpty = document.getElementById('featuredJobsEmpty');
    var featuredFilterSelect = document.getElementById('featuredFilterSelect');
    var featuredFilterToggle = document.getElementById('featuredFilterToggle');
    var featuredFilterLabel = document.getElementById('featuredFilterLabel');
    var featuredFilterChips = document.getElementById('featuredFilterChips');
    var featuredMobileFilterValue = document.getElementById('featuredMobileFilterValue');
    var featuredMobileFilterIcon = document.getElementById('featuredMobileFilterIcon');
    var featuredFilterPrev = document.getElementById('featuredFilterPrev');
    var featuredFilterNext = document.getElementById('featuredFilterNext');
    var featuredFilterOptions = Array.prototype.slice.call(document.querySelectorAll('[data-featured-filter-type]'));

    function normalizeText(value) {
      return (value || '').toLowerCase();
    }

    function detectSalary(text) {
      var lower = normalizeText(text);
      if (lower.indexOf('1') > -1 && lower.indexOf('3') > -1 && lower.indexOf('triệu') > -1) return '1-3';
      if (lower.indexOf('3') > -1 && lower.indexOf('5') > -1 && lower.indexOf('triệu') > -1) return '3-5';
      if (lower.indexOf('5') > -1 && lower.indexOf('7') > -1 && lower.indexOf('triệu') > -1) return '5-7';
      if (lower.indexOf('7') > -1 && lower.indexOf('10') > -1 && lower.indexOf('triệu') > -1) return '7-10';
      if (lower.indexOf('10') > -1 && lower.indexOf('15') > -1 && lower.indexOf('triệu') > -1) return '10-15';
      if (lower.indexOf('15') > -1 && lower.indexOf('20') > -1 && lower.indexOf('triệu') > -1) return '15-20';
      if (lower.indexOf('20') > -1 || lower.indexOf('35') > -1 || lower.indexOf('22') > -1) return '20+';
      return '';
    }

    function detectIndustry(text) {
      var lower = normalizeText(text);
      if (lower.indexOf('marketing') > -1 || lower.indexOf('content') > -1) return 'marketing';
      if (lower.indexOf('java') > -1 || lower.indexOf('react') > -1 || lower.indexOf('fpt') > -1) return 'it';
      if (lower.indexOf('nhân sự') > -1 || lower.indexOf('hr') > -1 || lower.indexOf('c&b') > -1) return 'hr';
      if (lower.indexOf('kế toán') > -1 || lower.indexOf('accountant') > -1) return 'accounting';
      if (lower.indexOf('kinh doanh') > -1 || lower.indexOf('sales') > -1 || lower.indexOf('bán') > -1) return 'sales';
      if (lower.indexOf('tài chính') > -1 || lower.indexOf('ngân hàng') > -1 || lower.indexOf('bank') > -1 || lower.indexOf('vib') > -1 || lower.indexOf('vpb') > -1 || lower.indexOf('mbbank') > -1) return 'finance';
      return '';
    }

    function detectLocation(text, index) {
      var lower = normalizeText(text);
      if (lower.indexOf('tp.hcm') > -1 || lower.indexOf('hcm') > -1) return 'tphcm';
      if (lower.indexOf('bình dương') > -1 || lower.indexOf('bÃ¬nh dÆ°Æ¡ng') > -1) return 'binhduong';
      if (lower.indexOf('đà nẵng') > -1 || lower.indexOf('Ä‘Ã  náºµng') > -1) return 'danang';
      if (lower.indexOf('hà nội') > -1 || lower.indexOf('hÃ  ná»™i') > -1) return 'hanoi';
      return index % 3 === 0 ? 'hanoi' : (index % 3 === 1 ? 'tphcm' : 'binhduong');
    }

    if (featuredGrid) {
      var featuredCards = Array.prototype.slice.call(featuredGrid.querySelectorAll('.job-card'));
      var expValues = ['1-2', '1-2', '3-5', 'none', '5+', '1-2'];
      var featuredActiveFilterType = 'all';
      var featuredActiveFilterValue = 'all';
      var featuredFilterLabels = {
        all: 'Lọc theo',
        salary: 'Mức lương',
        location: 'Địa điểm',
        industry: 'Ngành nghề',
        experience: 'Kinh nghiệm'
      };
      var featuredFilterIcons = {
        all: 'ti ti-filter',
        salary: 'ti ti-cash',
        location: 'ti ti-map-pin',
        industry: 'ti ti-briefcase',
        experience: 'ti ti-user-check'
      };
      var featuredChipSets = {
        all: [
          { value: 'all', label: 'Tất cả' }
        ],
        salary: [
          { value: 'all', label: 'Tất cả' },
          { value: '1-3', label: '1 - 3 triệu' },
          { value: '3-5', label: '3 - 5 triệu' },
          { value: '5-7', label: '5 - 7 triệu' },
          { value: '7-10', label: '7 - 10 triệu' },
          { value: '10-15', label: '10 - 15 triệu' },
          { value: '15-20', label: '15 - 20 triệu' },
          { value: '20+', label: 'Trên 20 triệu' }
        ],
        location: [
          { value: 'all', label: 'Tất cả' },
          { value: 'hanoi', label: 'Hà Nội' },
          { value: 'tphcm', label: 'TP.HCM' },
          { value: 'binhduong', label: 'Bình Dương' },
          { value: 'danang', label: 'Đà Nẵng' }
        ],
        industry: [
          { value: 'all', label: 'Tất cả' },
          { value: 'finance', label: 'Tài chính - Ngân hàng' },
          { value: 'sales', label: 'Bán hàng - Kinh doanh' },
          { value: 'it', label: 'CNTT - Phần mềm' },
          { value: 'marketing', label: 'Marketing' },
          { value: 'hr', label: 'Nhân sự' },
          { value: 'accounting', label: 'Kế toán' }
        ],
        experience: [
          { value: 'all', label: 'Tất cả' },
          { value: 'none', label: 'Chưa có kinh nghiệm' },
          { value: '1-2', label: '1 - 2 năm' },
          { value: '3-5', label: '3 - 5 năm' },
          { value: '5+', label: 'Trên 5 năm' }
        ]
      };

      featuredCards.forEach(function (card, index) {
        var text = card.innerText || card.textContent || '';
        card.setAttribute('data-featured-salary', detectSalary(text));
        card.setAttribute('data-featured-location', detectLocation(text, index));
        card.setAttribute('data-featured-industry', detectIndustry(text));
        card.setAttribute('data-featured-experience', expValues[index % expValues.length]);
      });

      function applyFeaturedFilters() {
        var visibleCount = 0;

        featuredCards.forEach(function (card) {
          var isVisible = featuredActiveFilterType === 'all' || featuredActiveFilterValue === 'all' || card.getAttribute('data-featured-' + featuredActiveFilterType) === featuredActiveFilterValue;

          card.style.display = isVisible ? '' : 'none';
          if (isVisible) visibleCount++;
        });

        if (featuredEmpty) {
          featuredEmpty.style.display = visibleCount ? 'none' : 'block';
        }
      }

      function renderFeaturedChips() {
        if (!featuredFilterChips) return;
        var items = featuredChipSets[featuredActiveFilterType] || [];
        featuredFilterChips.innerHTML = '';
        if (featuredMobileFilterValue) {
          featuredMobileFilterValue.innerHTML = '';
        }
        if (featuredMobileFilterIcon) {
          featuredMobileFilterIcon.className = featuredFilterIcons[featuredActiveFilterType] || 'ti ti-filter';
        }

        items.forEach(function (item) {
          var chip = document.createElement('button');
          chip.type = 'button';
          chip.className = item.value === featuredActiveFilterValue ? 'urgent-filter-chip active' : 'urgent-filter-chip';
          chip.setAttribute('data-filter-value', item.value);
          chip.textContent = item.label;
          chip.addEventListener('click', function () {
            featuredActiveFilterValue = item.value;
            Array.prototype.slice.call(featuredFilterChips.querySelectorAll('.urgent-filter-chip')).forEach(function (currentChip) {
              currentChip.classList.toggle('active', currentChip === chip);
            });
            applyFeaturedFilters();
          });
          featuredFilterChips.appendChild(chip);

          if (featuredMobileFilterValue) {
            var option = document.createElement('option');
            option.value = item.value;
            option.textContent = item.label;
            option.selected = item.value === featuredActiveFilterValue;
            featuredMobileFilterValue.appendChild(option);
          }
        });

        featuredFilterChips.scrollLeft = 0;
      }

      if (featuredFilterToggle && featuredFilterSelect) {
        featuredFilterToggle.addEventListener('click', function () {
          var isOpen = featuredFilterSelect.classList.toggle('open');
          featuredFilterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
      }

      featuredFilterOptions.forEach(function (option) {
        option.addEventListener('click', function () {
          featuredActiveFilterType = option.getAttribute('data-featured-filter-type');
          featuredActiveFilterValue = 'all';

          if (featuredFilterLabel) {
            featuredFilterLabel.textContent = featuredFilterLabels[featuredActiveFilterType] || '';
          }
          featuredFilterOptions.forEach(function (item) {
            var isActive = item === option;
            item.classList.toggle('active', isActive);
            item.setAttribute('aria-selected', isActive ? 'true' : 'false');
          });
          if (featuredFilterSelect) {
            featuredFilterSelect.classList.remove('open');
          }
          if (featuredFilterToggle) {
            featuredFilterToggle.setAttribute('aria-expanded', 'false');
          }

          renderFeaturedChips();
          applyFeaturedFilters();
        });
      });

      document.addEventListener('click', function (event) {
        if (featuredFilterSelect && !featuredFilterSelect.contains(event.target)) {
          featuredFilterSelect.classList.remove('open');
          if (featuredFilterToggle) {
            featuredFilterToggle.setAttribute('aria-expanded', 'false');
          }
        }
      });

      if (featuredFilterPrev && featuredFilterChips) {
        featuredFilterPrev.addEventListener('click', function () {
          featuredFilterChips.scrollBy({ left: -220, behavior: 'smooth' });
        });
      }
      if (featuredFilterNext && featuredFilterChips) {
        featuredFilterNext.addEventListener('click', function () {
          featuredFilterChips.scrollBy({ left: 220, behavior: 'smooth' });
        });
      }
      if (featuredMobileFilterValue) {
        featuredMobileFilterValue.addEventListener('change', function () {
          featuredActiveFilterValue = featuredMobileFilterValue.value;
          Array.prototype.slice.call(featuredFilterChips.querySelectorAll('.urgent-filter-chip')).forEach(function (chip) {
            chip.classList.toggle('active', chip.getAttribute('data-filter-value') === featuredActiveFilterValue);
          });
          applyFeaturedFilters();
        });
      }

      renderFeaturedChips();
    }

    var chipsWrap = document.getElementById('urgentSalaryChips');
    var urgentGrid = document.getElementById('urgentJobsGrid');
    var emptyState = document.getElementById('urgentJobsEmpty');
    var prevBtn = document.getElementById('urgentSalaryPrev');
    var nextBtn = document.getElementById('urgentSalaryNext');
    var urgentJobsPrev = document.getElementById('urgentJobsPrev');
    var urgentJobsNext = document.getElementById('urgentJobsNext');
    var urgentJobsDots = document.getElementById('urgentJobsDots');
    var urgentJobsPagination = document.getElementById('urgentJobsPagination');
    var urgentFilterSelect = document.getElementById('urgentFilterSelect');
    var urgentFilterToggle = document.getElementById('urgentFilterToggle');
    var urgentFilterLabel = document.getElementById('urgentFilterLabel');
    var urgentMobileFilterValue = document.getElementById('urgentMobileFilterValue');
    var urgentMobileFilterIcon = document.getElementById('urgentMobileFilterIcon');
    var urgentFilterOptions = Array.prototype.slice.call(document.querySelectorAll('.urgent-filter-option'));

    if (!chipsWrap || !urgentGrid) return;

    var chips = [];
    var cards = Array.prototype.slice.call(urgentGrid.querySelectorAll('.urgent-job-card'));
    var activeFilterType = 'all';
    var activeFilterValue = 'all';
    var urgentPage = 0;
    var urgentPageSize = 3;
    var urgentFilterLabels = {
      all: 'Lọc theo',
      location: 'Địa điểm',
      salary: 'Mức lương',
      experience: 'Kinh nghiệm',
      industry: 'Ngành nghề'
    };
    var urgentFilterIcons = {
      all: 'ti ti-filter',
      location: 'ti ti-map-pin',
      salary: 'ti ti-cash',
      experience: 'ti ti-user-check',
      industry: 'ti ti-briefcase'
    };
    var urgentChipSets = {
      all: [
        { value: 'all', label: 'Tất cả' }
      ],
      location: [
        { value: 'all', label: 'Tất cả' },
        { value: 'hanoi', label: 'Hà Nội' },
        { value: 'tphcm', label: 'TP.HCM' },
        { value: 'danang', label: 'Đà Nẵng' },
        { value: 'binhduong', label: 'Bình Dương' },
        { value: 'cantho', label: 'Cần Thơ' }
      ],
      salary: [
        { value: 'all', label: 'Tất cả' },
        { value: '1-3', label: '1 - 3 triệu' },
        { value: '3-5', label: '3 - 5 triệu' },
        { value: '5-7', label: '5 - 7 triệu' },
        { value: '7-10', label: '7 - 10 triệu' },
        { value: '10-15', label: '10 - 15 triệu' },
        { value: '15-20', label: '15 - 20 triệu' }
      ],
      experience: [
        { value: 'all', label: 'Tất cả' },
        { value: 'none', label: 'Chưa có kinh nghiệm' },
        { value: '1-2', label: '1 - 2 năm' },
        { value: '3-5', label: '3 - 5 năm' },
        { value: '5+', label: 'Trên 5 năm' }
      ],
      industry: [
        { value: 'all', label: 'Tất cả' },
        { value: 'sales', label: 'Bán hàng - Kinh doanh' },
        { value: 'logistics', label: 'Kho vận - Logistics' },
        { value: 'it', label: 'CNTT - Phần mềm' },
        { value: 'service', label: 'Chăm sóc khách hàng' },
        { value: 'hr', label: 'Nhân sự' }
      ]
    };

    function getUrgentFilteredCards() {
      return cards.filter(function (card) {
        return activeFilterType === 'all' || activeFilterValue === 'all' || card.getAttribute('data-' + activeFilterType) === activeFilterValue;
      });
    }

    function renderUrgentChips() {
      var items = urgentChipSets[activeFilterType] || [];
      chipsWrap.innerHTML = '';
      if (urgentMobileFilterValue) {
        urgentMobileFilterValue.innerHTML = '';
      }
      if (urgentMobileFilterIcon) {
        urgentMobileFilterIcon.className = urgentFilterIcons[activeFilterType] || 'ti ti-filter';
      }

      items.forEach(function (item) {
        var chip = document.createElement('button');
        chip.type = 'button';
        chip.className = item.value === activeFilterValue ? 'urgent-filter-chip active' : 'urgent-filter-chip';
        chip.setAttribute('data-filter-value', item.value);
        chip.textContent = item.label;
        chip.addEventListener('click', function () {
          activeFilterValue = item.value;
          urgentPage = 0;
          chips.forEach(function (currentChip) {
            currentChip.classList.toggle('active', currentChip === chip);
          });
          renderUrgentJobs();
        });
        chipsWrap.appendChild(chip);

        if (urgentMobileFilterValue) {
          var option = document.createElement('option');
          option.value = item.value;
          option.textContent = item.label;
          option.selected = item.value === activeFilterValue;
          urgentMobileFilterValue.appendChild(option);
        }
      });

      chips = Array.prototype.slice.call(chipsWrap.querySelectorAll('.urgent-filter-chip'));
      chipsWrap.scrollLeft = 0;
    }

    function renderUrgentPagination(totalPages) {
      if (!urgentJobsDots) return;
      urgentJobsDots.innerHTML = '';

      for (var i = 0; i < totalPages; i++) {
        var dot = document.createElement('button');
        dot.type = 'button';
        dot.className = i === urgentPage ? 'sv-dot active' : 'sv-dot';
        dot.setAttribute('aria-label', 'Trang ' + (i + 1));
        dot.setAttribute('data-page', i);
        dot.addEventListener('click', function () {
          urgentPage = parseInt(this.getAttribute('data-page'), 10);
          renderUrgentJobs();
        });
        urgentJobsDots.appendChild(dot);
      }
    }

    function renderUrgentJobs() {
      var filteredCards = getUrgentFilteredCards();
      var totalPages = Math.max(1, Math.ceil(filteredCards.length / urgentPageSize));

      if (urgentPage >= totalPages) urgentPage = totalPages - 1;

      cards.forEach(function (card) {
        card.style.display = 'none';
      });

      filteredCards.forEach(function (card, index) {
        var isOnPage = index >= urgentPage * urgentPageSize && index < (urgentPage + 1) * urgentPageSize;
        card.style.display = isOnPage ? '' : 'none';
      });

      if (emptyState) {
        emptyState.style.display = filteredCards.length ? 'none' : 'block';
      }
      if (urgentJobsPagination) {
        urgentJobsPagination.style.display = filteredCards.length > urgentPageSize ? '' : 'none';
      }
      if (urgentJobsPrev) {
        urgentJobsPrev.disabled = urgentPage === 0;
      }
      if (urgentJobsNext) {
        urgentJobsNext.disabled = urgentPage >= totalPages - 1;
      }

      renderUrgentPagination(filteredCards.length > urgentPageSize ? totalPages : 0);
    }

    if (urgentFilterToggle && urgentFilterSelect) {
      urgentFilterToggle.addEventListener('click', function () {
        var isOpen = urgentFilterSelect.classList.toggle('open');
        urgentFilterToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      });
    }

    urgentFilterOptions.forEach(function (option) {
      option.addEventListener('click', function () {
        activeFilterType = option.getAttribute('data-filter-type');
        activeFilterValue = 'all';
        urgentPage = 0;

        if (urgentFilterLabel) {
          urgentFilterLabel.textContent = urgentFilterLabels[activeFilterType] || '';
        }
        urgentFilterOptions.forEach(function (item) {
          var isActive = item === option;
          item.classList.toggle('active', isActive);
          item.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });
        if (urgentFilterSelect) {
          urgentFilterSelect.classList.remove('open');
        }
        if (urgentFilterToggle) {
          urgentFilterToggle.setAttribute('aria-expanded', 'false');
        }

        renderUrgentChips();
        renderUrgentJobs();
      });
    });

    document.addEventListener('click', function (event) {
      if (urgentFilterSelect && !urgentFilterSelect.contains(event.target)) {
        urgentFilterSelect.classList.remove('open');
        if (urgentFilterToggle) {
          urgentFilterToggle.setAttribute('aria-expanded', 'false');
        }
      }
    });

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        chipsWrap.scrollBy({ left: -220, behavior: 'smooth' });
      });
    }
    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        chipsWrap.scrollBy({ left: 220, behavior: 'smooth' });
      });
    }
    if (urgentMobileFilterValue) {
      urgentMobileFilterValue.addEventListener('change', function () {
        activeFilterValue = urgentMobileFilterValue.value;
        urgentPage = 0;
        chips.forEach(function (chip) {
          chip.classList.toggle('active', chip.getAttribute('data-filter-value') === activeFilterValue);
        });
        renderUrgentJobs();
      });
    }
    if (urgentJobsPrev) {
      urgentJobsPrev.addEventListener('click', function () {
        if (urgentPage > 0) {
          urgentPage--;
          renderUrgentJobs();
        }
      });
    }
    if (urgentJobsNext) {
      urgentJobsNext.addEventListener('click', function () {
        var totalPages = Math.ceil(getUrgentFilteredCards().length / urgentPageSize);
        if (urgentPage < totalPages - 1) {
          urgentPage++;
          renderUrgentJobs();
        }
      });
    }

    renderUrgentChips();
    renderUrgentJobs();
  })();
</script>

<!-- VIỆC LÀM THEO NGHỀ NGHIỆP -->
<section class="section" style="background:#f4f5f6">
  <div class="section-inner">
    <div class="section-header">
      <div class="section-title">Việc làm theo nghề nghiệp</div>
      <a href="https://vieclam.vn/viec-lam/viec-lam-theo-nganh-nghe" class="see-all">Xem thêm <i class="ti ti-arrow-right"></i></a>
    </div>
    <div class="cats-grid">
      <a href="https://vieclam.vn/viec-lam-hanh-chinh-thu-ky-o1.html" class="cat-card">
        <div class="cat-icon-wrap">📋</div>
        <div class="cat-name">Hành chính - Thư ký</div>
        <div class="cat-count">12.450 việc làm</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-an-ninh-bao-ve-o2.html" class="cat-card">
        <div class="cat-icon-wrap">🛡️</div>
        <div class="cat-name">An ninh - Bảo vệ</div>
        <div class="cat-count">4.200 việc làm</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-thiet-ke-sang-tao-nghe-thuat-o3.html" class="cat-card">
        <div class="cat-icon-wrap">🎨</div>
        <div class="cat-name">Thiết kế - Sáng tạo</div>
        <div class="cat-count">8.730 việc làm</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-kien-truc-thiet-ke-noi-ngoai-that-o4.html" class="cat-card">
        <div class="cat-icon-wrap">🏗️</div>
        <div class="cat-name">Kiến trúc - Nội thất</div>
        <div class="cat-count">7.320 việc làm</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-khach-san-nha-hang-du-lich-o5.html" class="cat-card">
        <div class="cat-icon-wrap">🍽️</div>
        <div class="cat-name">Khách sạn - Nhà hàng</div>
        <div class="cat-count">8.670 việc làm</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-ban-si-ban-le-quan-ly-cua-hang-o6.html" class="cat-card">
        <div class="cat-icon-wrap">🛒</div>
        <div class="cat-name">Bán lẻ - Cửa hàng</div>
        <div class="cat-count">15.400 việc làm</div>
      </a>
      <a href="#" class="cat-card">
        <div class="cat-icon-wrap">💻</div>
        <div class="cat-name">CNTT - Phần mềm</div>
        <div class="cat-count">18.230 việc làm</div>
      </a>
      <a href="#" class="cat-card">
        <div class="cat-icon-wrap">📣</div>
        <div class="cat-name">Marketing - PR</div>
        <div class="cat-count">11.540 việc làm</div>
      </a>
      <a href="#" class="cat-card">
        <div class="cat-icon-wrap">💰</div>
        <div class="cat-name">Kế toán - Kiểm toán</div>
        <div class="cat-count">9.870 việc làm</div>
      </a>
      <a href="#" class="cat-card">
        <div class="cat-icon-wrap">🔧</div>
        <div class="cat-name">Kỹ thuật - Cơ khí</div>
        <div class="cat-count">13.890 việc làm</div>
      </a>
      <a href="#" class="cat-card">
        <div class="cat-icon-wrap">👥</div>
        <div class="cat-name">Nhân sự - C&B</div>
        <div class="cat-count">7.450 việc làm</div>
      </a>
      <a href="https://vieclam.vn/viec-lam/viec-lam-theo-nganh-nghe" class="cat-card" style="border-style:dashed;background:#fafafa">
        <div class="cat-icon-wrap" style="background:#f5f5f5">➕</div>
        <div class="cat-name" style="color:#0d4e96">Xem tất cả</div>
        <div class="cat-count">30+ ngành nghề</div>
      </a>
    </div>
  </div>
</section>

<!-- SINH VIÊN NỔI BẬT -->
<?php
$featuredStudents = [
  ['name' => 'Nguyễn Thị Lan', 'dob' => '12/03/2003', 'major' => 'Kế toán', 'color' => '#0d4e96', 'initials' => 'NL'],
  ['name' => 'Trần Văn Hùng', 'dob' => '05/07/2002', 'major' => 'CNTT', 'color' => '#1565c0', 'initials' => 'TH'],
  ['name' => 'Lê Thị Mai', 'dob' => '20/01/2003', 'major' => 'Marketing', 'color' => '#2e7d32', 'initials' => 'LM'],
  ['name' => 'Phạm Quốc Bảo', 'dob' => '15/09/2002', 'major' => 'Kinh doanh', 'color' => '#c62828', 'initials' => 'PB'],
  ['name' => 'Hoàng Thị Thu', 'dob' => '08/11/2003', 'major' => 'Nhân sự', 'color' => '#6a1b9a', 'initials' => 'HT'],
  ['name' => 'Vũ Minh Khoa', 'dob' => '25/04/2002', 'major' => 'Kỹ thuật', 'color' => '#00695c', 'initials' => 'VK'],
  ['name' => 'Đặng Thị Hoa', 'dob' => '17/06/2003', 'major' => 'Thiết kế', 'color' => '#e65100', 'initials' => 'ĐH'],
  ['name' => 'Bùi Văn Nam', 'dob' => '03/02/2002', 'major' => 'Tài chính', 'color' => '#1a237e', 'initials' => 'BN'],
  ['name' => 'Nguyễn Anh Tuấn', 'dob' => '29/08/2003', 'major' => 'Logistics', 'color' => '#33691e', 'initials' => 'NT'],
  ['name' => 'Trịnh Thị Nga', 'dob' => '11/12/2002', 'major' => 'Du lịch', 'color' => '#880e4f', 'initials' => 'TN'],
  ['name' => 'Phan Quang Vinh', 'dob' => '07/05/2003', 'major' => 'Xây dựng', 'color' => '#4e342e', 'initials' => 'PV'],
  ['name' => 'Lương Thị Linh', 'dob' => '22/10/2002', 'major' => 'Y tế', 'color' => '#00838f', 'initials' => 'LL'],
  ['name' => 'Cao Văn Đức', 'dob' => '14/01/2003', 'major' => 'Luật', 'color' => '#37474f', 'initials' => 'CĐ'],
  ['name' => 'Đinh Thị Hằng', 'dob' => '30/07/2002', 'major' => 'Ngân hàng', 'color' => '#0d4e96', 'initials' => 'ĐH'],
  ['name' => 'Hồ Quốc Toản', 'dob' => '18/03/2003', 'major' => 'Báo chí', 'color' => '#c62828', 'initials' => 'HT'],
  ['name' => 'Mai Thị Tuyết', 'dob' => '09/11/2002', 'major' => 'Ngoại ngữ', 'color' => '#2e7d32', 'initials' => 'MT'],
  ['name' => 'Lê Hoàng Nam', 'dob' => '26/06/2003', 'major' => 'Điện tử', 'color' => '#1565c0', 'initials' => 'LN'],
  ['name' => 'Trần Thị Bình', 'dob' => '02/09/2002', 'major' => 'Môi trường', 'color' => '#388e3c', 'initials' => 'TB'],
  ['name' => 'Ngô Văn Thành', 'dob' => '13/04/2003', 'major' => 'Cơ khí', 'color' => '#5e35b1', 'initials' => 'NT'],
  ['name' => 'Đoàn Thị Phương', 'dob' => '21/02/2002', 'major' => 'Quản trị', 'color' => '#f57f17', 'initials' => 'ĐP'],
  ['name' => 'Võ Minh Trí', 'dob' => '05/12/2003', 'major' => 'CNTT', 'color' => '#00695c', 'initials' => 'VT'],
  ['name' => 'Chu Thị Lan', 'dob' => '19/08/2002', 'major' => 'Kế toán', 'color' => '#ad1457', 'initials' => 'CL'],
  ['name' => 'Dương Văn Long', 'dob' => '27/05/2003', 'major' => 'Kiến trúc', 'color' => '#4a148c', 'initials' => 'DL'],
  ['name' => 'Lý Thị Kim', 'dob' => '08/01/2002', 'major' => 'Marketing', 'color' => '#006064', 'initials' => 'LK'],
  ['name' => 'Huỳnh Văn Phúc', 'dob' => '16/09/2003', 'major' => 'Tài chính', 'color' => '#0d4e96', 'initials' => 'HP'],
  ['name' => 'Nguyễn Thị Yến', 'dob' => '04/03/2002', 'major' => 'Nhân sự', 'color' => '#880e4f', 'initials' => 'NY'],
  ['name' => 'Tô Quang Hải', 'dob' => '23/07/2003', 'major' => 'Logistics', 'color' => '#1b5e20', 'initials' => 'TH'],
  ['name' => 'Kiều Thị Nga', 'dob' => '11/11/2002', 'major' => 'Thiết kế', 'color' => '#bf360c', 'initials' => 'KN'],
  ['name' => 'Bùi Hoàng Minh', 'dob' => '28/04/2003', 'major' => 'Xây dựng', 'color' => '#37474f', 'initials' => 'BM'],
  ['name' => 'Phan Thị Thảo', 'dob' => '06/02/2002', 'major' => 'Du lịch', 'color' => '#00838f', 'initials' => 'PT'],
  ['name' => 'Vương Văn Đạt', 'dob' => '15/10/2003', 'major' => 'Kỹ thuật', 'color' => '#283593', 'initials' => 'VĐ'],
  ['name' => 'Hoàng Thị Liên', 'dob' => '01/06/2002', 'major' => 'Y tế', 'color' => '#c62828', 'initials' => 'HL'],
  ['name' => 'Lưu Minh Quân', 'dob' => '20/08/2003', 'major' => 'Ngân hàng', 'color' => '#2e7d32', 'initials' => 'LQ'],
  ['name' => 'Đặng Thị Oanh', 'dob' => '09/12/2002', 'major' => 'Luật', 'color' => '#6a1b9a', 'initials' => 'ĐO'],
  ['name' => 'Trần Văn Khải', 'dob' => '17/03/2003', 'major' => 'Báo chí', 'color' => '#e65100', 'initials' => 'TK'],
  ['name' => 'Lê Thị Diệu', 'dob' => '03/09/2002', 'major' => 'Ngoại ngữ', 'color' => '#00695c', 'initials' => 'LD'],
];
$svPages = array_chunk($featuredStudents, 12);
?>
<section class="sv-section">
  <div class="section-inner">
    <div class="section-header">
      <div class="section-title">Sinh viên nổi bật</div>
      <a href="#" class="see-all">Xem tất cả <i class="ti ti-arrow-right"></i></a>
    </div>

    <div id="svSliderWrap" style="overflow:hidden;position:relative;border-radius:12px">
      <div id="svTrack" style="display:flex;transition:transform 0.55s cubic-bezier(0.4,0,0.2,1);will-change:transform">
        <?php foreach ($svPages as $pageStudents): ?>
        <div class="sv-grid" style="min-width:100%;box-sizing:border-box;flex-shrink:0">
          <?php foreach ($pageStudents as $s): ?>
          <div class="sv-card">
            <span class="sv-badge">Sinh viên</span>
            <span class="sv-badge-xuat-sac">Xuất sắc</span>
            <div class="sv-avatar-wrap">
              <div class="sv-avatar-fallback" style="background:<?= htmlspecialchars($s['color'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($s['initials'], ENT_QUOTES, 'UTF-8') ?></div>
            </div>
            <div class="sv-name" title="<?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?></div>
            <div class="sv-dob"><i class="ti ti-calendar" style="font-size:10px;vertical-align:-1px"></i> <?= htmlspecialchars($s['dob'], ENT_QUOTES, 'UTF-8') ?></div>
            <div class="sv-major"><?= htmlspecialchars($s['major'], ENT_QUOTES, 'UTF-8') ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="sv-pagination jobs-pagination" id="svPagination" aria-label="Phân trang sinh viên nổi bật">
      <button type="button" class="jobs-nav jobs-nav-prev" id="svPrev" aria-label="Trang trước"><i class="ti ti-chevron-left"></i></button>
      <div class="jobs-dots-wrap" id="svDotsWrap">
        <?php for ($i = 0, $svPageCount = count($svPages); $i < $svPageCount; $i++): ?>
        <button type="button" class="sv-dot<?= $i === 0 ? ' active' : '' ?>" onclick="svGoTo(<?= $i ?>)" aria-label="Trang <?= $i + 1 ?>"></button>
        <?php endfor; ?>
      </div>
      <button type="button" class="jobs-nav jobs-nav-next" id="svNext" aria-label="Trang sau"><i class="ti ti-chevron-right"></i></button>
    </div>
  </div>
</section>

<!-- NHÀ TUYỂN DỤNG NỔI BẬT -->
<section class="section" style="background:#fff">
  <div class="section-inner">
    <div class="section-header">
      <div class="section-title">Nhà tuyển dụng nổi bật</div>
      <a href="#" class="see-all">Xem tất cả <i class="ti ti-arrow-right"></i></a>
    </div>
    <div class="employer-logos">
      <div class="employer-card"><div class="emp-text" style="color:#1565c0;font-size:15px;font-weight:800">VIB</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#1b5e20;font-size:15px;font-weight:800">VPBank</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#e65100;font-size:15px;font-weight:800">FPT</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#880e4f;font-size:15px;font-weight:800">Vingroup</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#01579b;font-size:15px;font-weight:800">Samsung</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#4a148c;font-size:15px;font-weight:800">Masan</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#b71c1c;font-size:15px;font-weight:800">MBBank</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#1a237e;font-size:15px;font-weight:800">BIDV</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#33691e;font-size:15px;font-weight:800">Viettel</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#f57f17;font-size:15px;font-weight:800">Shopee</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#006064;font-size:15px;font-weight:800">Grab</div></div>
      <div class="employer-card"><div class="emp-text" style="color:#c62828;font-size:15px;font-weight:800">Lazada</div></div>
    </div>
  </div>
</section>

<!-- VIỆC LÀM THEO KHU VỰC -->
<section class="section" style="background:#f4f5f6">
  <div class="section-inner">
    <div class="section-header">
      <div class="section-title">Việc làm theo khu vực</div>
      <a href="https://vieclam.vn/viec-lam/viec-lam-theo-tinh-thanh" class="see-all">Xem thêm <i class="ti ti-arrow-right"></i></a>
    </div>
    <div class="city-grid">
      <a href="https://vieclam.vn/viec-lam-toan-quoc-p136.html" class="city-card">
        <div class="city-icon">🇻🇳</div>
        <div class="city-name">Toàn quốc</div>
        <div class="city-jobs">180.000+ việc</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-ha-noi-p73.html" class="city-card">
        <div class="city-icon">🏙️</div>
        <div class="city-name">Hà Nội</div>
        <div class="city-jobs">52.400 việc</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-tp-hcm-p122.html" class="city-card">
        <div class="city-icon">🌆</div>
        <div class="city-name">TP.HCM</div>
        <div class="city-jobs">68.700 việc</div>
      </a>
      <a href="#" class="city-card">
        <div class="city-icon">🏖️</div>
        <div class="city-name">Đà Nẵng</div>
        <div class="city-jobs">12.300 việc</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-binh-duong-p119.html" class="city-card">
        <div class="city-icon">🏭</div>
        <div class="city-name">Bình Dương</div>
        <div class="city-jobs">18.500 việc</div>
      </a>
      <a href="#" class="city-card">
        <div class="city-icon">🏗️</div>
        <div class="city-name">Đồng Nai</div>
        <div class="city-jobs">9.200 việc</div>
      </a>
      <a href="#" class="city-card">
        <div class="city-icon">🌴</div>
        <div class="city-name">Cần Thơ</div>
        <div class="city-jobs">6.800 việc</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-an-giang-p129.html" class="city-card">
        <div class="city-icon">🌊</div>
        <div class="city-name">An Giang</div>
        <div class="city-jobs">2.100 việc</div>
      </a>
      <a href="https://vieclam.vn/viec-lam-ba-ria-vung-tau-p121.html" class="city-card">
        <div class="city-icon">⛵</div>
        <div class="city-name">Bà Rịa - Vũng Tàu</div>
        <div class="city-jobs">5.400 việc</div>
      </a>
      <a href="#" class="city-card">
        <div class="city-icon">🐉</div>
        <div class="city-name">Hải Phòng</div>
        <div class="city-jobs">8.900 việc</div>
      </a>
      <a href="#" class="city-card">
        <div class="city-icon">🏔️</div>
        <div class="city-name">Lâm Đồng</div>
        <div class="city-jobs">3.200 việc</div>
      </a>
      <a href="#" class="city-card">
        <div class="city-icon">🌺</div>
        <div class="city-name">Khánh Hòa</div>
        <div class="city-jobs">4.100 việc</div>
      </a>
      <a href="#" class="city-card">
        <div class="city-icon">🏘️</div>
        <div class="city-name">Long An</div>
        <div class="city-jobs">5.700 việc</div>
      </a>
      <a href="https://vieclam.vn/viec-lam/viec-lam-theo-tinh-thanh" class="city-card" style="border-style:dashed;background:#fafafa">
        <div class="city-icon">📍</div>
        <div class="city-name" style="color:#0d4e96">Xem thêm</div>
        <div class="city-jobs">49 tỉnh thành</div>
      </a>
    </div>
  </div>
</section>

<!-- TIN TUC NOI BAT -->
<style>
  .featured-news-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
  }
  .featured-news-card {
    display: block;
    overflow: hidden;
    border: 1px solid #e8edf3;
    border-radius: 10px;
    background: #fff;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 6px 18px rgba(13, 78, 150, 0.06);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
  }
  .featured-news-card:hover {
    transform: translateY(-3px);
    border-color: #c9dcf0;
    box-shadow: 0 12px 28px rgba(13, 78, 150, 0.12);
  }
  .featured-news-thumb {
    width: 100%;
    aspect-ratio: 16 / 9;
    overflow: hidden;
    background: #eef3f8;
  }
  .featured-news-thumb img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    transition: transform 0.25s ease;
  }
  .featured-news-card:hover .featured-news-thumb img {
    transform: scale(1.04);
  }
  .featured-news-body {
    padding: 14px 15px 16px;
  }
  .featured-news-title {
    min-height: 44px;
    color: #16324f;
    font-size: 16px;
    font-weight: 750;
    line-height: 1.38;
  }
  .featured-news-desc {
    margin-top: 8px;
    min-height: 42px;
    color: #5f6f80;
    font-size: 13px;
    line-height: 1.55;
  }
  .featured-news-date {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
    color: #8a98a8;
    font-size: 12px;
    font-weight: 600;
  }
  .featured-news-date i {
    font-size: 14px;
    color: #0d4e96;
  }
  @media (max-width: 900px) {
    .featured-news-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }
  @media (max-width: 560px) {
    .featured-news-grid {
      grid-template-columns: 1fr;
    }
    .featured-news-title,
    .featured-news-desc {
      min-height: auto;
    }
  }
</style>
<section class="section" style="background:#fff">
  <div class="section-inner">
    <div class="section-header">
      <div class="section-title">Tin tức nổi bật</div>
      <a href="#" class="see-all">Xem tất cả <i class="ti ti-arrow-right"></i></a>
    </div>
    <div class="featured-news-grid">
      <a href="#" class="featured-news-card">
        <div class="featured-news-thumb">
          <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=900&q=80" alt="Xu hướng tuyển dụng 2026" loading="lazy">
        </div>
        <div class="featured-news-body">
          <div class="featured-news-title">Xu hướng tuyển dụng 2026: Kỹ năng số tiếp tục lên ngôi</div>
          <div class="featured-news-desc">Doanh nghiệp ưu tiên ứng viên có khả năng thích nghi nhanh, tư duy dữ liệu và kinh nghiệm làm việc với công cụ AI.</div>
          <div class="featured-news-date"><i class="ti ti-calendar"></i> 20/05/2026</div>
        </div>
      </a>
      <a href="#" class="featured-news-card">
        <div class="featured-news-thumb">
          <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=900&q=80" alt="Bí quyết phỏng vấn" loading="lazy">
        </div>
        <div class="featured-news-body">
          <div class="featured-news-title">5 bí quyết giúp ứng viên ghi điểm trong buổi phỏng vấn</div>
          <div class="featured-news-desc">Chuẩn bị câu chuyện nghề nghiệp rõ ràng, nghiên cứu công ty và đặt câu hỏi đúng trọng tâm để tạo ấn tượng tốt.</div>
          <div class="featured-news-date"><i class="ti ti-calendar"></i> 18/05/2026</div>
        </div>
      </a>
      <a href="#" class="featured-news-card">
        <div class="featured-news-thumb">
          <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=900&q=80" alt="Cập nhật lương thưởng" loading="lazy">
        </div>
        <div class="featured-news-body">
          <div class="featured-news-title">Cập nhật mức lương phổ biến của các ngành hot hiện nay</div>
          <div class="featured-news-desc">Công nghệ thông tin, kinh doanh, marketing và tài chính vẫn là nhóm ngành có nhu cầu tuyển dụng ổn định.</div>
          <div class="featured-news-date"><i class="ti ti-calendar"></i> 16/05/2026</div>
        </div>
      </a>
      <a href="#" class="featured-news-card">
        <div class="featured-news-thumb">
          <img src="https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=900&q=80" alt="Việc làm cho sinh viên" loading="lazy">
        </div>
        <div class="featured-news-body">
          <div class="featured-news-title">Sinh viên mới ra trường nên chuẩn bị gì khi tìm việc?</div>
          <div class="featured-news-desc">Một CV ngắn gọn, portfolio phù hợp và thái độ học hỏi là nền tảng giúp sinh viên tăng cơ hội trúng tuyển.</div>
          <div class="featured-news-date"><i class="ti ti-calendar"></i> 14/05/2026</div>
        </div>
      </a>
      <a href="#" class="featured-news-card">
        <div class="featured-news-thumb">
          <img src="https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=900&q=80" alt="Môi trường làm việc" loading="lazy">
        </div>
        <div class="featured-news-body">
          <div class="featured-news-title">Môi trường làm việc linh hoạt trở thành lợi thế cạnh tranh</div>
          <div class="featured-news-desc">Nhiều công ty mở rộng chính sách hybrid, tăng phúc lợi sức khỏe và chú trọng trải nghiệm nhân viên.</div>
          <div class="featured-news-date"><i class="ti ti-calendar"></i> 12/05/2026</div>
        </div>
      </a>
      <a href="#" class="featured-news-card">
        <div class="featured-news-thumb">
          <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=900&q=80" alt="Nhà tuyển dụng" loading="lazy">
        </div>
        <div class="featured-news-body">
          <div class="featured-news-title">Nhà tuyển dụng tăng tốc tìm kiếm nhân sự chất lượng cao</div>
          <div class="featured-news-desc">Các vị trí quản lý, chuyên viên kinh doanh và nhân sự công nghệ đang được nhiều doanh nghiệp săn đón.</div>
          <div class="featured-news-date"><i class="ti ti-calendar"></i> 10/05/2026</div>
        </div>
      </a>
    </div>
  </div>
</section>
<!-- CTA EMPLOYER BANNER -->
<div class="cta-banner">
  <div class="cta-inner">
    <div class="cta-text">
      <h2>Bạn là Nhà Tuyển Dụng?</h2>
      <p>Đăng tin tuyển dụng miễn phí, tiếp cận hơn 5 triệu ứng viên tiềm năng. Quản lý hồ sơ ứng tuyển dễ dàng và hiệu quả.</p>
    </div>
    <div class="cta-stats">
      <div class="cta-stat">
        <div class="cta-stat-num">450K+</div>
        <div class="cta-stat-label">Nhà tuyển dụng</div>
      </div>
      <div class="cta-stat">
        <div class="cta-stat-num">5M+</div>
        <div class="cta-stat-label">Ứng viên</div>
      </div>
      <div class="cta-stat">
        <div class="cta-stat-num">1.2M+</div>
        <div class="cta-stat-label">Tin tuyển dụng</div>
      </div>
    </div>
    <button class="btn-cta">Đăng tin tuyển dụng ngay →</button>
  </div>
</div>

<!-- APP DOWNLOAD -->
<!-- <section class="app-section">
  <div class="app-inner">
    <div class="app-text">
      <h2>Tải app Vieclam – Ứng tuyển 1 chạm!</h2>
      <p>Tìm việc làm nhanh hơn, quản lý hồ sơ tiện lợi hơn. Nhận thông báo việc làm phù hợp mọi lúc, mọi nơi.</p>
      <div class="app-badges">
        <div class="app-badge">
          <i class="ti ti-brand-google-play"></i>
          <div class="app-badge-text">
            <span class="small">Tải trên</span>
            <span class="big">Google Play</span>
          </div>
        </div>
        <div class="app-badge">
          <i class="ti ti-brand-apple"></i>
          <div class="app-badge-text">
            <span class="small">Tải trên</span>
            <span class="big">App Store</span>
          </div>
        </div>
      </div>
    </div>
    <div class="app-qr" style="width:100px;height:100px">
      <div style="text-align:center;font-size:11px;color:#aaa;padding:8px">
        <i class="ti ti-qrcode" style="font-size:36px;display:block;margin-bottom:4px;color:#ccc"></i>
        QR tải app
      </div>
    </div>
  </div>
</section> -->

<?php require "footer.php"; ?>
