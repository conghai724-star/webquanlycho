<?php require "header.php"; ?>

<?php
$jobFilters = [
  'location' => [
    'label' => 'Địa điểm',
    'icon' => 'ti ti-map-pin',
    'items' => [
      ['value' => 'all', 'label' => 'Tất cả'],
      ['value' => 'hanoi', 'label' => 'Hà Nội'],
      ['value' => 'tphcm', 'label' => 'TP.HCM'],
      ['value' => 'danang', 'label' => 'Đà Nẵng'],
      ['value' => 'binhduong', 'label' => 'Bình Dương'],
      ['value' => 'cantho', 'label' => 'Cần Thơ'],
    ],
  ],
  'salary' => [
    'label' => 'Mức lương',
    'icon' => 'ti ti-cash',
    'items' => [
      ['value' => 'all', 'label' => 'Tất cả'],
      ['value' => '1-3', 'label' => '1 - 3 triệu'],
      ['value' => '3-5', 'label' => '3 - 5 triệu'],
      ['value' => '5-7', 'label' => '5 - 7 triệu'],
      ['value' => '7-10', 'label' => '7 - 10 triệu'],
      ['value' => '10-15', 'label' => '10 - 15 triệu'],
      ['value' => '15-20', 'label' => '15 - 20 triệu'],
      ['value' => '20+', 'label' => 'Trên 20 triệu'],
    ],
  ],
  'experience' => [
    'label' => 'Kinh nghiệm',
    'icon' => 'ti ti-user-check',
    'items' => [
      ['value' => 'all', 'label' => 'Tất cả'],
      ['value' => 'none', 'label' => 'Chưa có kinh nghiệm'],
      ['value' => '1-2', 'label' => '1 - 2 năm'],
      ['value' => '3-5', 'label' => '3 - 5 năm'],
      ['value' => '5+', 'label' => 'Trên 5 năm'],
    ],
  ],
  'industry' => [
    'label' => 'Ngành nghề',
    'icon' => 'ti ti-briefcase',
    'items' => [
      ['value' => 'all', 'label' => 'Tất cả'],
      ['value' => 'finance', 'label' => 'Tài chính - Ngân hàng'],
      ['value' => 'sales', 'label' => 'Bán hàng - Kinh doanh'],
      ['value' => 'it', 'label' => 'CNTT - Phần mềm'],
      ['value' => 'marketing', 'label' => 'Marketing'],
      ['value' => 'hr', 'label' => 'Nhân sự'],
      ['value' => 'accounting', 'label' => 'Kế toán'],
      ['value' => 'logistics', 'label' => 'Logistics'],
      ['value' => 'logistics', 'label' => 'Logistics'],
      ['value' => 'logistics', 'label' => 'Logistics'],
      ['value' => 'logistics', 'label' => 'Logistics'],
      ['value' => 'logistics', 'label' => 'Logistics'],
      ['value' => 'logistics', 'label' => 'Logistics']
    ],
  ],
];

$jobs = [
  [
    'logo' => 'VIB',
    'logo_bg' => '#fff3e0',
    'logo_color' => '#e65100',
    'title' => 'Chuyên viên Tư vấn Tài chính Cá nhân',
    'company' => 'VIB - Ngân hàng Quốc tế',
    'location' => 'hanoi',
    'location_text' => 'Hà Nội',
    'industry' => 'finance',
    'industry_text' => 'Tài chính - Ngân hàng',
    'salary' => '10-15',
    'salary_text' => '10 - 15 triệu',
    'experience' => '1-2',
    'deadline' => '30/06/2026',
    'urgent' => true,
  ],
  [
    'logo' => 'FPT',
    'logo_bg' => '#e8f5e9',
    'logo_color' => '#2e7d32',
    'title' => 'Lập trình viên PHP / Laravel',
    'company' => 'FPT Software',
    'location' => 'danang',
    'location_text' => 'Đà Nẵng',
    'industry' => 'it',
    'industry_text' => 'CNTT - Phần mềm',
    'salary' => '20+',
    'salary_text' => '20 - 35 triệu',
    'experience' => '3-5',
    'deadline' => '15/07/2026',
    'urgent' => true,
  ],
  [
    'logo' => 'MWG',
    'logo_bg' => '#e3f2fd',
    'logo_color' => '#1565c0',
    'title' => 'Nhân viên bán hàng đi làm ngay',
    'company' => 'Thế Giới Di Động',
    'location' => 'tphcm',
    'location_text' => 'TP.HCM',
    'industry' => 'sales',
    'industry_text' => 'Bán hàng - Kinh doanh',
    'salary' => '7-10',
    'salary_text' => '7 - 10 triệu',
    'experience' => 'none',
    'deadline' => '05/07/2026',
    'urgent' => true,
  ],
  [
    'logo' => 'MBB',
    'logo_bg' => '#fce4ec',
    'logo_color' => '#c62828',
    'title' => 'Chuyên viên Marketing Digital',
    'company' => 'MBBank',
    'location' => 'hanoi',
    'location_text' => 'Hà Nội',
    'industry' => 'marketing',
    'industry_text' => 'Marketing',
    'salary' => '15-20',
    'salary_text' => '15 - 20 triệu',
    'experience' => '1-2',
    'deadline' => '10/07/2026',
    'urgent' => false,
  ],
  [
    'logo' => 'GHTK',
    'logo_bg' => '#e0f2f1',
    'logo_color' => '#00695c',
    'title' => 'Điều phối kho vận / Logistics',
    'company' => 'Giao Hàng Tiết Kiệm',
    'location' => 'binhduong',
    'location_text' => 'Bình Dương',
    'industry' => 'logistics',
    'industry_text' => 'Logistics',
    'salary' => '10-15',
    'salary_text' => '10 - 15 triệu',
    'experience' => '1-2',
    'deadline' => '20/07/2026',
    'urgent' => true,
  ],
  [
    'logo' => 'VIN',
    'logo_bg' => '#f3e5f5',
    'logo_color' => '#6a1b9a',
    'title' => 'Nhân viên Nhân sự C&B',
    'company' => 'Vingroup',
    'location' => 'tphcm',
    'location_text' => 'TP.HCM',
    'industry' => 'hr',
    'industry_text' => 'Nhân sự',
    'salary' => '10-15',
    'salary_text' => '10 - 15 triệu',
    'experience' => '3-5',
    'deadline' => '28/06/2026',
    'urgent' => false,
  ],
  [
    'logo' => 'SAM',
    'logo_bg' => '#eef6ff',
    'logo_color' => '#0d4e96',
    'title' => 'Kế toán tổng hợp',
    'company' => 'Samsung Vina',
    'location' => 'cantho',
    'location_text' => 'Cần Thơ',
    'industry' => 'accounting',
    'industry_text' => 'Kế toán',
    'salary' => '7-10',
    'salary_text' => '7 - 10 triệu',
    'experience' => '1-2',
    'deadline' => '12/07/2026',
    'urgent' => true,
  ],
];
?>
<main class="jobs-manage-page">
  <div class="jobs-manage-inner">
    <section class="job-search-panel" aria-label="Tìm kiếm và lọc việc làm">
      <div class="job-search-main">
        <label class="job-search-field">
          <i class="ti ti-search"></i>
          <input type="text" id="keywordSearch" placeholder="Nhập từ khóa tìm kiếm ở đây!" autocomplete="off">
        </label>
        <label class="job-search-field">
          <i class="ti ti-map-pin"></i>
          <select id="topLocationFilter" aria-label="Địa điểm">
            <option value="all">Địa điểm</option>
            <?php foreach ($jobFilters['location']['items'] as $item): ?>
            <option value="<?= htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="job-search-field">
          <i class="ti ti-briefcase"></i>
          <select id="topIndustryFilter" aria-label="Ngành nghề">
            <option value="all">Ngành nghề</option>
            <?php foreach ($jobFilters['industry']['items'] as $item): ?>
            <option value="<?= htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <button type="button" class="job-search-submit" id="jobSearchBtn">TÌM KIẾM</button>
      </div>

      <div class="job-filter-row">
        <!-- <label class="job-filter-select">
          <select id="timeFilter" aria-label="Thời gian"><option value="all">Tất cả thời gian</option><option value="today">Hôm nay</option><option value="week">7 ngày qua</option></select>
        </label> -->
        <label class="job-filter-select">
          <select id="workTypeFilter" aria-label="Loại hình"><option value="all">Tất cả loại hình</option><option value="fulltime">Full-time</option><option value="parttime">Part-time</option></select>
        </label>
        <label class="job-filter-select">
          <select id="topSalaryFilter" aria-label="Mức lương">
            <option value="all">Mức lương</option>
            <?php foreach ($jobFilters['salary']['items'] as $item): ?>
            <option value="<?= htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <!--  -->
        <label class="job-filter-select">
          <select id="topExperienceFilter" aria-label="Kinh nghiệm">
            <option value="all">Tất cả kinh nghiệm</option>
            <?php foreach ($jobFilters['experience']['items'] as $item): ?>
            <option value="<?= htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label class="job-filter-select">
          <select id="postTypeFilter" aria-label="Loại tin"><option value="all">Tất cả loại tin</option><option value="urgent">Tuyển gấp</option></select>
        </label>
        <button type="button" class="job-filter-clear" id="jobsFilterReset">Xóa lọc</button>
      </div>
    </section>

    <div class="jobs-toolbar">
      <div class="jobs-toolbar-title">
        <h1>Danh sách việc làm</h1>
        <p>Các vị trí đang tuyển dụng phù hợp với tiêu chí tìm kiếm.</p>
      </div>
      <div class="jobs-manage-count"><i class="ti ti-briefcase"></i> <span id="jobsVisibleCount"><?= count($jobs) ?></span> việc làm phù hợp</div>
    </div>

    <section class="jobs-results-wrap">
      <div class="jobs-results" id="jobsResults">
        <?php foreach ($jobs as $job): ?>
        <article
          class="job-box<?= $job['urgent'] ? ' is-urgent' : '' ?>"
          data-title="<?= htmlspecialchars(strtolower($job['title'] . ' ' . $job['company']), ENT_QUOTES, 'UTF-8') ?>"
          data-location="<?= htmlspecialchars($job['location'], ENT_QUOTES, 'UTF-8') ?>"
          data-salary="<?= htmlspecialchars($job['salary'], ENT_QUOTES, 'UTF-8') ?>"
          data-experience="<?= htmlspecialchars($job['experience'], ENT_QUOTES, 'UTF-8') ?>"
          data-industry="<?= htmlspecialchars($job['industry'], ENT_QUOTES, 'UTF-8') ?>"
          data-urgent="<?= $job['urgent'] ? 'urgent' : 'normal' ?>"
        >
          <span class="job-urgent-badge"><i class="ti ti-bolt"></i> Tuyển gấp</span>
          <div class="job-box-head">
            <div class="job-logo" style="background:<?= htmlspecialchars($job['logo_bg'], ENT_QUOTES, 'UTF-8') ?>;color:<?= htmlspecialchars($job['logo_color'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($job['logo'], ENT_QUOTES, 'UTF-8') ?></div>
            <div>
              <h2 class="job-box-title"><?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?></h2>
              <div class="job-box-company"><i class="ti ti-building"></i> <?= htmlspecialchars($job['company'], ENT_QUOTES, 'UTF-8') ?></div>
            </div>
          </div>
          <div class="job-box-tags">
            <span class="job-box-tag"><i class="ti ti-map-pin"></i><?= htmlspecialchars($job['location_text'], ENT_QUOTES, 'UTF-8') ?></span>
            <span class="job-box-tag"><i class="ti ti-briefcase"></i><?= htmlspecialchars($job['industry_text'], ENT_QUOTES, 'UTF-8') ?></span>
            <span class="job-box-tag"><i class="ti ti-cash"></i><?= htmlspecialchars($job['salary_text'], ENT_QUOTES, 'UTF-8') ?></span>
          </div>
          <div class="job-box-deadline"><i class="ti ti-calendar-due"></i> Hạn nộp hồ sơ: <?= htmlspecialchars($job['deadline'], ENT_QUOTES, 'UTF-8') ?></div>
        </article>
        <?php endforeach; ?>
      </div>
      <div class="jobs-empty" id="jobsEmpty">Không có việc làm phù hợp với bộ lọc đã chọn.</div>
      <div class="jobs-pagination" id="jobsPagination" aria-label="Phân trang việc làm">
        <button type="button" class="jobs-page-btn" id="jobsPrevPage" aria-label="Trang trước"><i class="ti ti-chevron-left"></i></button>
        <div class="jobs-pagination-pages" id="jobsPaginationPages"></div>
        <button type="button" class="jobs-page-btn" id="jobsNextPage" aria-label="Trang sau"><i class="ti ti-chevron-right"></i></button>
      </div>
    </section>
  </div>
</main>

<script>
  (function () {
    var cards = Array.prototype.slice.call(document.querySelectorAll('.job-box'));
    var keyword = document.getElementById('keywordSearch');
    var count = document.getElementById('jobsVisibleCount');
    var empty = document.getElementById('jobsEmpty');
    var resetBtn = document.getElementById('jobsFilterReset');
    var searchBtn = document.getElementById('jobSearchBtn');
    var pagination = document.getElementById('jobsPagination');
    var paginationPages = document.getElementById('jobsPaginationPages');
    var prevPageBtn = document.getElementById('jobsPrevPage');
    var nextPageBtn = document.getElementById('jobsNextPage');
    var currentPage = 1;
    var pageSize = 6;
    var filteredCards = cards.slice();
    var filters = {
      location: document.getElementById('topLocationFilter'),
      industry: document.getElementById('topIndustryFilter'),
      salary: document.getElementById('topSalaryFilter'),
      experience: document.getElementById('topExperienceFilter'),
      postType: document.getElementById('postTypeFilter')
    };

    function normalize(value) {
      return (value || '')
        .toString()
        .trim()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/đ/g, 'd');
    }

    function getCardSearchText(card) {
      var title = card.querySelector('.job-box-title');
      var company = card.querySelector('.job-box-company');
      return normalize([
        card.getAttribute('data-title'),
        title ? title.textContent : '',
        company ? company.textContent : ''
      ].join(' '));
    }

    function renderPagination(totalPages) {
      if (!paginationPages) return;
      paginationPages.innerHTML = '';

      for (var i = 1; i <= totalPages; i++) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = i === currentPage ? 'jobs-page-btn active' : 'jobs-page-btn';
        btn.textContent = i;
        btn.setAttribute('aria-label', 'Trang ' + i);
        btn.addEventListener('click', (function (page) {
          return function () {
            currentPage = page;
            renderJobsPage();
          };
        })(i));
        paginationPages.appendChild(btn);
      }
    }

    function renderJobsPage() {
      var totalPages = Math.max(1, Math.ceil(filteredCards.length / pageSize));
      if (currentPage > totalPages) currentPage = totalPages;

      cards.forEach(function (card) {
        card.style.display = 'none';
      });

      filteredCards.forEach(function (card, index) {
        var start = (currentPage - 1) * pageSize;
        var end = start + pageSize;
        card.style.display = index >= start && index < end ? '' : 'none';
      });

      if (pagination) pagination.style.display = filteredCards.length > pageSize ? 'flex' : 'none';
      if (prevPageBtn) prevPageBtn.disabled = currentPage <= 1;
      if (nextPageBtn) nextPageBtn.disabled = currentPage >= totalPages;
      renderPagination(filteredCards.length > pageSize ? totalPages : 0);
    }

    function applyFilters() {
      var keywordValue = normalize(keyword ? keyword.value : '');
      var selected = {
        location: filters.location ? filters.location.value : 'all',
        industry: filters.industry ? filters.industry.value : 'all',
        salary: filters.salary ? filters.salary.value : 'all',
        experience: filters.experience ? filters.experience.value : 'all',
        postType: filters.postType ? filters.postType.value : 'all'
      };

      filteredCards = cards.filter(function (card) {
        var ok = true;
        if (keywordValue && getCardSearchText(card).indexOf(keywordValue) === -1) ok = false;
        if (selected.location !== 'all' && card.getAttribute('data-location') !== selected.location) ok = false;
        if (selected.industry !== 'all' && card.getAttribute('data-industry') !== selected.industry) ok = false;
        if (selected.salary !== 'all' && card.getAttribute('data-salary') !== selected.salary) ok = false;
        if (selected.experience !== 'all' && card.getAttribute('data-experience') !== selected.experience) ok = false;
        if (selected.postType === 'urgent' && card.getAttribute('data-urgent') !== 'urgent') ok = false;
        return ok;
      });

      currentPage = 1;
      if (count) count.textContent = filteredCards.length;
      if (empty) empty.style.display = filteredCards.length ? 'none' : 'block';
      renderJobsPage();
    }

    Object.keys(filters).forEach(function (key) {
      if (filters[key]) filters[key].addEventListener('change', applyFilters);
    });
    if (keyword) keyword.addEventListener('input', applyFilters);
    if (keyword) {
      keyword.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
          event.preventDefault();
          applyFilters();
        }
      });
    }
    if (searchBtn) searchBtn.addEventListener('click', applyFilters);
    if (resetBtn) {
      resetBtn.addEventListener('click', function () {
        if (keyword) keyword.value = '';
        Object.keys(filters).forEach(function (key) {
          if (filters[key]) filters[key].value = 'all';
        });
        applyFilters();
      });
    }
    if (prevPageBtn) {
      prevPageBtn.addEventListener('click', function () {
        if (currentPage > 1) {
          currentPage--;
          renderJobsPage();
        }
      });
    }
    if (nextPageBtn) {
      nextPageBtn.addEventListener('click', function () {
        var totalPages = Math.ceil(filteredCards.length / pageSize);
        if (currentPage < totalPages) {
          currentPage++;
          renderJobsPage();
        }
      });
    }

    applyFilters();
  })();
</script>

<?php require "footer.php"; ?>
