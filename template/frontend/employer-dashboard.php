<?php require 'header.php'; ?>



<!-- ===== DASHBOARD ===== -->
<div class="dash-layout">

  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-employer">
      <div class="sidebar-avatar">KT</div>
      <div class="sidebar-name">Công ty TNHH Sản xuất Kon Tum</div>
      <div class="sidebar-role" style="margin-top:6px">
        <span class="badge-link"><i class="ti ti-link" style="font-size:10px"></i> Đã liên kết CĐ Kon Tum</span>
      </div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section-label">Tổng quan</div>
      <div class="nav-item active" onclick="showPage('dashboard')">
        <i class="ti ti-layout-dashboard"></i> Bảng điều khiển
      </div>
      <div class="nav-section-label">Hồ sơ doanh nghiệp</div>
      <div class="nav-item" onclick="showPage('profile')">
        <i class="ti ti-building"></i> Thông tin công ty
      </div>
      <div class="nav-item" onclick="showPage('profile');showProfileTab('link')">
        <i class="ti ti-link"></i> Liên kết trường CĐ
      </div>
      <div class="nav-section-label">Tuyển dụng</div>
      <div class="nav-item" onclick="showPage('jobs')">
        <i class="ti ti-news"></i> Bài đăng tuyển dụng
        <span class="nav-badge">3</span>
      </div>
      <div class="nav-item" onclick="showPage('applicants')">
        <i class="ti ti-user-check"></i> Danh sách ứng viên
        <span class="nav-badge">12</span>
      </div>
      <div class="nav-section-label">Sinh viên</div>
      <div class="nav-item" onclick="showPage('students')">
        <i class="ti ti-school"></i> Kho dữ liệu sinh viên
      </div>
    </nav>
    <div class="sidebar-bottom">
      <div class="sidebar-bottom-link"><i class="ti ti-settings"></i> Cài đặt tài khoản</div>
      <div class="sidebar-bottom-link"><i class="ti ti-help-circle"></i> Trợ giúp & hỗ trợ</div>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="dash-main">

    <!-- ===== PAGE: DASHBOARD ===== -->
    <div class="dash-page active" id="page-dashboard">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-layout-dashboard"></i> Bảng điều khiển</div>
          <div class="page-subtitle">Chào buổi sáng! Đây là tổng quan hoạt động của bạn hôm nay.</div>
        </div>
        <button class="btn-primary" onclick="showPage('jobs');openJobModal()"><i class="ti ti-plus"></i> Đăng tin mới</button>
      </div>

      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="ti ti-news"></i></div>
          <div>
            <div class="stat-value">3</div>
            <div class="stat-label">Bài đăng đang hoạt động</div>
         </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon green"><i class="ti ti-user-check"></i></div>
          <div>
            <div class="stat-value">47</div>
            <div class="stat-label">Bài đăng đã duyệt</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon orange"><i class="ti ti-eye"></i></div>
          <div>
            <div class="stat-value">10</div>
            <div class="stat-label">Bài đăng chờ duyệt</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon purple"><i class="ti ti-check"></i></div>
          <div>
            <div class="stat-value">5</div>
            <div class="stat-label">Bài đăng đã đóng</div>
          </div>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 340px;gap:18px">
        <div>
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="ti ti-news"></i> Bài đăng gần đây</div>
              <button class="btn-secondary btn-sm" onclick="showPage('jobs')">Xem tất cả</button>
            </div>
            <div class="card-body" style="padding:0">
              <div style="padding:14px 20px;border-bottom:1px solid #f0f4fa;display:flex;align-items:center;gap:12px">
                <div class="job-post-status active"></div>
                <div style="flex:1"><div style="font-size:14px;font-weight:700">Nhân viên Kế toán tổng hợp</div><div style="font-size:11px;color:#aaa;margin-top:2px">5 người · 10–15 tr/tháng · Hạn: 30/06/2025</div></div>
              </div>
              <div style="padding:14px 20px;border-bottom:1px solid #f0f4fa;display:flex;align-items:center;gap:12px">
                <div class="job-post-status active"></div>
                <div style="flex:1"><div style="font-size:14px;font-weight:700">Kỹ thuật viên CNC</div><div style="font-size:11px;color:#aaa;margin-top:2px">3 người · Thỏa thuận · Hạn: 15/07/2025</div></div>
              </div>
              <div style="padding:14px 20px;display:flex;align-items:center;gap:12px">
                <div class="job-post-status pending"></div>
                <div style="flex:1"><div style="font-size:14px;font-weight:700">Nhân viên Marketing Online</div><div style="font-size:11px;color:#aaa;margin-top:2px">2 người · 8–12 tr/tháng · Hạn: 20/06/2025</div></div>
              </div>
            </div>
          </div>
        </div>

        <div>
          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="ti ti-link"></i> Trạng thái liên kết</div>
            </div>
            <div class="card-body">
              <div style="text-align:center;padding:8px 0 16px">
                <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:30px">🎓</div>
                <div style="font-size:14px;font-weight:700;color:#111;margin-bottom:4px">Trường CĐ Kon Tum</div>
                <div style="display:inline-flex;align-items:center;gap:5px;background:#dcfce7;color:#16a34a;font-size:12px;font-weight:700;padding:4px 12px;border-radius:20px">
                  <i class="ti ti-check" style="font-size:13px"></i> Đã liên kết
                </div>
              </div>
              <div style="font-size:12px;color:#666;line-height:1.6;background:#f8faff;border-radius:8px;padding:12px;border:1px solid #e8edf5">
                <strong>Biên bản ghi nhớ số 12/BBGN-2024</strong><br>
                Ký ngày 15/01/2024 · Hiệu lực 3 năm<br>
                Nội dung: Tiếp nhận thực tập & ưu tiên tuyển dụng sinh viên ngành Kế toán, CNTT
              </div>
              <button class="btn-secondary btn-sm" style="width:100%;margin-top:12px;justify-content:center" onclick="showPage('profile');showProfileTab('link')">
                <i class="ti ti-edit"></i> Xem chi tiết liên kết
              </button>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <div class="card-title"><i class="ti ti-bell"></i> Thông báo</div>
            </div>
            <div class="card-body" style="padding:0">
              <div style="padding:12px 18px;border-bottom:1px solid #f0f4fa;font-size:12px">
                <div style="font-weight:600;color:#111">Nguyễn Thị Lan đã nộp đơn</div>
                <div style="color:#aaa;margin-top:2px">Vị trí: Kế toán tổng hợp · 2 giờ trước</div>
              </div>
              <div style="padding:12px 18px;border-bottom:1px solid #f0f4fa;font-size:12px">
                <div style="font-weight:600;color:#111">Bài đăng CNC sắp hết hạn</div>
                <div style="color:#e65100;margin-top:2px">Còn 5 ngày · Nhớ gia hạn</div>
              </div>
              <div style="padding:12px 18px;font-size:12px">
                <div style="font-weight:600;color:#111">Sinh viên xuất sắc mới</div>
                <div style="color:#aaa;margin-top:2px">15 sinh viên Kế toán có GPA ≥ 3.5</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== PAGE: COMPANY PROFILE ===== -->
    <div class="dash-page" id="page-profile">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-building"></i> Hồ sơ công ty</div>
          <div class="page-subtitle">Quản lý thông tin và hình ảnh doanh nghiệp của bạn</div>
        </div>
        <button class="btn-primary" onclick="alert('Đã lưu thành công!')"><i class="ti ti-device-floppy"></i> Lưu thay đổi</button>
      </div>

      <!-- Profile Hero Banner -->
      <div class="profile-hero">
        <div class="profile-hero-inner">
          <div class="profile-logo-upload" title="Nhấp để tải logo">
            <span>KT</span>
            <div class="profile-logo-overlay"><i class="ti ti-camera"></i></div>
          </div>
          <div class="profile-hero-info">
            <div class="profile-hero-name">Công ty TNHH Sản xuất & Thương mại Kon Tum</div>
            <div class="profile-hero-meta">
              <span class="profile-meta-tag"><i class="ti ti-map-pin"></i> Kon Tum</span>
              <span class="profile-meta-tag"><i class="ti ti-users"></i> 100–200 nhân viên</span>
              <span class="profile-meta-tag"><i class="ti ti-briefcase"></i> Sản xuất – Cơ khí</span>
            </div>
            <span class="profile-link-status"><i class="ti ti-check-circle"></i> Đã liên kết CĐ Kon Tum</span>
          </div>
        </div>
        <div class="profile-hero-tabs">
          <div class="profile-hero-tab active" onclick="showProfileTab('info')">Thông tin cơ bản</div>
          <div class="profile-hero-tab" onclick="showProfileTab('link')">Liên kết & Hợp đồng</div>
          <div class="profile-hero-tab" onclick="showProfileTab('media')">Hình ảnh & Media</div>
        </div>
      </div>

      <!-- Tab: Info -->
      <div class="tab-panel active" id="profile-tab-info">
        <div class="card">
          <div class="card-body">
            <div class="profile-form-section">
              <div class="profile-form-section-title"><i class="ti ti-building" style="color:#0d4e96"></i> Thông tin chính</div>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Tên công ty / Đơn vị <span class="required">*</span></label>
                  <input class="form-control" type="text" value="Công ty TNHH Sản xuất & Thương mại Kon Tum" placeholder="Tên công ty..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Tên viết tắt / Tên hiển thị</label>
                  <input class="form-control" type="text" value="TNHH Kon Tum" placeholder="Tên ngắn..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Địa chỉ trụ sở chính <span class="required">*</span></label>
                  <input class="form-control" type="text" value="123 Nguyễn Huệ, P. Quyết Thắng, TP. Kon Tum" placeholder="Địa chỉ..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Địa chỉ văn phòng làm việc</label>
                  <input class="form-control" type="text" value="45 Trần Phú, P. Thắng Lợi, TP. Kon Tum" placeholder="Địa chỉ văn phòng..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Lĩnh vực hoạt động (Ngành nghề) <span class="required">*</span></label>
                  <select class="form-control">
                    <option>Sản xuất – Cơ khí – Chế tạo</option>
                    <option>Công nghệ thông tin</option>
                    <option>Xây dựng – Bất động sản</option>
                    <option>Nông nghiệp – Lâm nghiệp</option>
                    <option>Thương mại – Bán lẻ</option>
                    <option>Du lịch – Dịch vụ</option>
                    <option>Y tế – Dược phẩm</option>
                    <option>Giáo dục – Đào tạo</option>
                  </select>
                </div>
                <div class="form-group">
                  <label class="form-label">Quy mô nhân sự <span class="required">*</span></label>
                  <select class="form-control">
                    <option>Dưới 10 người</option>
                    <option>10 – 50 người</option>
                    <option selected>100 – 200 người</option>
                    <option>200 – 500 người</option>
                    <option>Trên 500 người</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="profile-form-section">
              <div class="profile-form-section-title"><i class="ti ti-world" style="color:#0d4e96"></i> Kênh truyền thông & Giới thiệu</div>
              <div class="form-grid">
                <div class="form-group">
                  <label class="form-label">Website công ty</label>
                  <input class="form-control" type="url" value="https://kontumco.vn" placeholder="https://..."/>
                </div>
                <div class="form-group">
                  <label class="form-label">Facebook Fanpage</label>
                  <input class="form-control" type="url" value="https://facebook.com/kontumco" placeholder="https://facebook.com/..."/>
                </div>
                <div class="form-group full">
                  <label class="form-label">Mô tả ngắn gọn về công ty <span class="required">*</span></label>
                  <textarea class="form-control" rows="4">Công ty TNHH Sản xuất & Thương mại Kon Tum là doanh nghiệp hàng đầu tỉnh Kon Tum trong lĩnh vực sản xuất và gia công cơ khí chính xác. Thành lập năm 2010, chúng tôi hiện có hơn 150 nhân viên với đội ngũ kỹ sư giàu kinh nghiệm, trang thiết bị hiện đại và môi trường làm việc năng động, chuyên nghiệp.</textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination demo for profile sections -->
        <div class="pagination">
          <div class="page-btn disabled"><i class="ti ti-chevron-left" style="font-size:13px"></i></div>
          <div class="page-btn active">1</div>
          <div class="page-btn">2</div>
          <div class="page-btn">3</div>
          <div class="page-btn"><i class="ti ti-chevron-right" style="font-size:13px"></i></div>
        </div>
      </div>

      <!-- Tab: Link -->
      <div class="tab-panel" id="profile-tab-link">
        <div class="card">
          <div class="card-header">
            <div class="card-title"><i class="ti ti-link"></i> Liên kết với Trường Cao đẳng Kon Tum</div>
            <button class="btn-primary btn-sm" onclick="alert('Mở form thêm liên kết')"><i class="ti ti-plus"></i> Thêm liên kết</button>
          </div>
          <div class="card-body">
            <div class="link-card linked">
              <div class="link-card-icon"><i class="ti ti-file-check"></i></div>
              <div style="flex:1">
                <div class="link-card-title">Biên bản ghi nhớ hợp tác số 12/BBGN-2024</div>
                <div class="link-card-desc">
                  Ký ngày: <strong>15/01/2024</strong> &nbsp;|&nbsp; Hiệu lực: <strong>3 năm (đến 15/01/2027)</strong><br>
                  Nội dung: Tiếp nhận sinh viên ngành <strong>Kế toán, CNTT, Cơ khí</strong> thực tập tại công ty; ưu tiên tuyển dụng sinh viên tốt nghiệp loại Giỏi trở lên. Hỗ trợ kinh phí đào tạo thực hành tại xưởng.
                </div>
                <span class="link-card-badge"><i class="ti ti-check" style="font-size:11px"></i> Đang hiệu lực</span>
              </div>
              <div style="display:flex;gap:6px;flex-shrink:0">
                <div class="action-btn" title="Chỉnh sửa"><i class="ti ti-edit"></i></div>
                <div class="action-btn danger" title="Xóa"><i class="ti ti-trash"></i></div>
              </div>
            </div>

            <div class="link-card linked" style="margin-top:12px">
              <div class="link-card-icon"><i class="ti ti-writing"></i></div>
              <div style="flex:1">
                <div class="link-card-title">Hợp đồng đặt hàng đào tạo số 05/HĐ-2024</div>
                <div class="link-card-desc">
                  Ký ngày: <strong>20/03/2024</strong> &nbsp;|&nbsp; Thời hạn: <strong>1 năm</strong><br>
                  Nội dung: Đặt hàng đào tạo <strong>20 sinh viên ngành Kỹ thuật Cơ khí</strong> theo chương trình gắn kết doanh nghiệp. Công ty hỗ trợ học bổng 2 tr/sv/tháng và cam kết tuyển dụng 80% sau tốt nghiệp.
                </div>
                <span class="link-card-badge"><i class="ti ti-check" style="font-size:11px"></i> Đang hiệu lực</span>
              </div>
              <div style="display:flex;gap:6px;flex-shrink:0">
                <div class="action-btn" title="Chỉnh sửa"><i class="ti ti-edit"></i></div>
                <div class="action-btn danger" title="Xóa"><i class="ti ti-trash"></i></div>
              </div>
            </div>

            <div class="link-card unlinked" style="margin-top:12px">
              <div class="link-card-icon"><i class="ti ti-clock"></i></div>
              <div style="flex:1">
                <div class="link-card-title">Hợp đồng thực tập số 03/HĐ-2023</div>
                <div class="link-card-desc">
                  Ký ngày: <strong>05/06/2023</strong> &nbsp;|&nbsp; Đã hết hạn: <strong>05/06/2024</strong><br>
                  Nội dung: Tiếp nhận <strong>10 sinh viên</strong> ngành Kế toán thực tập 3 tháng cuối khóa. Đã hoàn thành và kết thúc.
                </div>
                <span class="link-card-badge"><i class="ti ti-x" style="font-size:11px"></i> Đã hết hạn</span>
              </div>
              <div style="display:flex;gap:6px;flex-shrink:0">
                <div class="action-btn" title="Gia hạn"><i class="ti ti-refresh"></i></div>
                <div class="action-btn danger" title="Xóa"><i class="ti ti-trash"></i></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab: Media -->
      <div class="tab-panel" id="profile-tab-media">
        <div class="card">
          <div class="card-body">
            <div class="profile-form-section-title"><i class="ti ti-photo" style="color:#0d4e96"></i> Logo & Ảnh đại diện</div>
            <div style="display:flex;gap:20px;flex-wrap:wrap;margin-bottom:24px">
              <div>
                <div style="font-size:12px;font-weight:700;color:#666;margin-bottom:8px">Logo công ty</div>
                <div style="width:100px;height:100px;border-radius:16px;background:linear-gradient(135deg,#0d4e96,#1e88e5);display:flex;align-items:center;justify-content:center;font-size:36px;font-weight:800;color:#fff;border:3px dashed #b0c4e8;cursor:pointer">KT</div>
                <div style="font-size:11px;color:#aaa;margin-top:6px;text-align:center">Nhấp để thay đổi<br>PNG/JPG, tối đa 2MB</div>
              </div>
              <div>
                <div style="font-size:12px;font-weight:700;color:#666;margin-bottom:8px">Ảnh bìa / Banner</div>
                <div style="width:260px;height:100px;border-radius:12px;background:linear-gradient(135deg,#0d4e96,#1565c0,#1e88e5);display:flex;align-items:center;justify-content:center;border:3px dashed #b0c4e8;cursor:pointer;color:rgba(255,255,255,.6);font-size:13px">
                  <i class="ti ti-upload" style="font-size:28px"></i>
                </div>
                <div style="font-size:11px;color:#aaa;margin-top:6px;text-align:center">Nhấp để tải lên · 1200x400px</div>
              </div>
            </div>
            <div style="background:#f8faff;border-radius:10px;padding:16px;border:1px dashed #b0c4e8;text-align:center;color:#999;font-size:13px;cursor:pointer">
              <i class="ti ti-photo-plus" style="font-size:32px;display:block;margin-bottom:8px;color:#b0c4e8"></i>
              Thêm ảnh môi trường làm việc (tối đa 8 ảnh)
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== PAGE: JOBS ===== -->
    <div class="dash-page" id="page-jobs">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-news"></i> Bài đăng tuyển dụng</div>
          <div class="page-subtitle">Quản lý tất cả tin tuyển dụng của bạn</div>
        </div>
        <button class="btn-primary" onclick="openJobModal()"><i class="ti ti-plus"></i> Đăng tin mới</button>
      </div>

      <div class="filter-bar">
        <input class="form-control search-input" type="text" placeholder="🔍 Tìm theo tên vị trí..."/>
        <select class="form-control">
          <option>Tất cả trạng thái</option>
          <option>Đang hoạt động</option>
          <option>Chờ duyệt</option>
          <option>Đã đóng</option>
        </select>
        <select class="form-control">
          <option>Sắp xếp: Mới nhất</option>
          <option>Cũ nhất</option>
          <option>Nhiều ứng viên nhất</option>
        </select>
      </div>

      <div class="job-posts-list">
        <!-- Job 1 -->
        <div class="job-post-card">
          <div class="job-post-status active" style="margin-top:8px"></div>
          <div class="job-post-info">
            <div class="job-post-title">Nhân viên Kế toán tổng hợp</div>
            <div class="job-post-meta">
              <span class="jpm-tag qty"><i class="ti ti-users"></i> 5 người</span>
              <span class="jpm-tag salary"><i class="ti ti-coin"></i> 10 – 15 triệu/tháng</span>
              <span class="jpm-tag loc"><i class="ti ti-map-pin"></i> Kon Tum</span>
              <span class="jpm-tag type"><i class="ti ti-clock"></i> Full-time · Văn phòng</span>
              <span class="jpm-tag deadline"><i class="ti ti-calendar"></i> Hạn: 30/06/2025</span>
            </div>
            <div class="job-post-footer">
              <span><i class="ti ti-calendar" style="font-size:11px"></i> Đăng ngày 01/06/2025</span>
              <span>·</span>
              <span style="color:#2e7d32;font-weight:600"><i class="ti ti-user-check" style="font-size:11px"></i> 18 ứng viên</span>
              <span>·</span>
              <span style="color:#22c55e;font-weight:600">● Đang hoạt động</span>
            </div>
          </div>
          <div class="job-post-actions">
            <div class="action-btn" title="Xem ứng viên" onclick="showPage('applicants')"><i class="ti ti-user-check"></i></div>
            <div class="action-btn" title="Chỉnh sửa" onclick="openJobModal()"><i class="ti ti-edit"></i></div>
            <div class="action-btn" title="Nhân bản"><i class="ti ti-copy"></i></div>
            <div class="action-btn danger" title="Xóa"><i class="ti ti-trash"></i></div>
          </div>
        </div>
        <!-- Job 2 -->
        <div class="job-post-card">
          <div class="job-post-status active" style="margin-top:8px"></div>
          <div class="job-post-info">
            <div class="job-post-title">Kỹ thuật viên CNC – Gia công cơ khí chính xác</div>
            <div class="job-post-meta">
              <span class="jpm-tag qty"><i class="ti ti-users"></i> 3 người</span>
              <span class="jpm-tag salary"><i class="ti ti-coin"></i> Thỏa thuận</span>
              <span class="jpm-tag loc"><i class="ti ti-map-pin"></i> Kon Tum</span>
              <span class="jpm-tag type"><i class="ti ti-clock"></i> Full-time · Tại xưởng</span>
              <span class="jpm-tag deadline"><i class="ti ti-calendar"></i> Hạn: 15/07/2025</span>
            </div>
            <div class="job-post-footer">
              <span><i class="ti ti-calendar" style="font-size:11px"></i> Đăng ngày 28/05/2025</span>
              <span>·</span>
              <span style="color:#2e7d32;font-weight:600"><i class="ti ti-user-check" style="font-size:11px"></i> 22 ứng viên</span>
              <span>·</span>
              <span style="color:#22c55e;font-weight:600">● Đang hoạt động</span>
            </div>
          </div>
          <div class="job-post-actions">
            <div class="action-btn" title="Xem ứng viên" onclick="showPage('applicants')"><i class="ti ti-user-check"></i></div>
            <div class="action-btn" title="Chỉnh sửa" onclick="openJobModal()"><i class="ti ti-edit"></i></div>
            <div class="action-btn" title="Nhân bản"><i class="ti ti-copy"></i></div>
            <div class="action-btn danger" title="Xóa"><i class="ti ti-trash"></i></div>
          </div>
        </div>
        <!-- Job 3 -->
        <div class="job-post-card">
          <div class="job-post-status pending" style="margin-top:8px"></div>
          <div class="job-post-info">
            <div class="job-post-title">Nhân viên Marketing Online – Quản lý mạng xã hội</div>
            <div class="job-post-meta">
              <span class="jpm-tag qty"><i class="ti ti-users"></i> 2 người</span>
              <span class="jpm-tag salary"><i class="ti ti-coin"></i> 8 – 12 triệu/tháng</span>
              <span class="jpm-tag loc"><i class="ti ti-map-pin"></i> Kon Tum / Remote</span>
              <span class="jpm-tag type"><i class="ti ti-clock"></i> Full-time</span>
              <span class="jpm-tag deadline"><i class="ti ti-calendar"></i> Hạn: 20/06/2025</span>
            </div>
            <div class="job-post-footer">
              <span><i class="ti ti-calendar" style="font-size:11px"></i> Đăng ngày 05/06/2025</span>
              <span>·</span>
              <span style="color:#e65100;font-weight:600"><i class="ti ti-user-check" style="font-size:11px"></i> 7 ứng viên</span>
              <span>·</span>
              <span style="color:#f59e0b;font-weight:600">● Chờ duyệt</span>
            </div>
          </div>
          <div class="job-post-actions">
            <div class="action-btn" title="Xem ứng viên" onclick="showPage('applicants')"><i class="ti ti-user-check"></i></div>
            <div class="action-btn" title="Chỉnh sửa" onclick="openJobModal()"><i class="ti ti-edit"></i></div>
            <div class="action-btn" title="Nhân bản"><i class="ti ti-copy"></i></div>
            <div class="action-btn danger" title="Xóa"><i class="ti ti-trash"></i></div>
          </div>
        </div>
      </div>

      <div class="pagination" style="margin-top:24px">
        <div class="page-btn disabled"><i class="ti ti-chevron-left" style="font-size:13px"></i></div>
        <div class="page-btn active">1</div>
        <div class="page-btn">2</div>
        <div class="page-btn"><i class="ti ti-chevron-right" style="font-size:13px"></i></div>
      </div>
    </div>

    <!-- ===== PAGE: APPLICANTS ===== -->
    <div class="dash-page" id="page-applicants">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-user-check"></i> Danh sách ứng viên</div>
          <div class="page-subtitle">Tổng hợp tất cả hồ sơ đã nộp vào các vị trí của bạn</div>
        </div>
        <button class="btn-secondary"><i class="ti ti-download"></i> Xuất Excel</button>
      </div>

      <div class="card">
        <div class="card-body">
          <div class="filter-bar">
            <input class="form-control search-input" type="text" placeholder="🔍 Tìm theo tên ứng viên..."/>
            <select class="form-control">
              <option>Tất cả vị trí</option>
              <option>Kế toán tổng hợp</option>
              <option>Kỹ thuật viên CNC</option>
              <option>Marketing Online</option>
            </select>
            <select class="form-control">
              <option>Thời gian nộp: Mới nhất</option>
              <option>Cũ nhất</option>
            </select>
            <select class="form-control">
              <option>Tất cả trạng thái</option>
              <option>Mới nộp</option>
              <option>Đang xem xét</option>
              <option>Đã tuyển</option>
              <option>Từ chối</option>
            </select>
          </div>

          <div style="overflow-x:auto">
            <table class="applicant-table">
              <thead>
                <tr>
                  <th>Ứng viên</th>
                  <th>Vị trí ứng tuyển</th>
                  <th>Thời gian nộp</th>
                  <th>Kinh nghiệm</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody id="applicantsBody"></tbody>
            </table>
          </div>

          <div class="pagination" style="margin-top:16px">
            <div class="page-btn disabled"><i class="ti ti-chevron-left" style="font-size:13px"></i></div>
            <div class="page-btn active">1</div>
            <div class="page-btn">2</div>
            <div class="page-btn"><i class="ti ti-chevron-right" style="font-size:13px"></i></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== PAGE: STUDENTS ===== -->
    <div class="dash-page" id="page-students">
      <div class="page-header">
        <div>
          <div class="page-title"><i class="ti ti-school"></i> Kho dữ liệu sinh viên</div>
          <div class="page-subtitle">Tra cứu sinh viên Trường CĐ Kon Tum – Ưu tiên tuyển dụng</div>
        </div>
        <div style="display:flex;gap:8px">
          <!-- <button class="btn-secondary"><i class="ti ti-download"></i> Xuất danh sách</button> -->
          <button class="btn-primary" onclick="showPage('applicants')"><i class="ti ti-mail"></i> Tìm kiếm</button>
        </div>
      </div>

      <div class="student-filter-bar">
        <input class="form-control" type="text" placeholder="🔍 Tìm tên sinh viên..." style="flex:1;min-width:180px"/>
        <select class="form-control" style="min-width:140px">
          <option>Tất cả ngành</option>
          <option>Kế toán</option>
          <option>CNTT</option>
          <option>Cơ khí</option>
          <option>Marketing</option>
          <option>Nhân sự</option>
          <option>Tài chính</option>
          <option>Du lịch</option>
        </select>
        <select class="form-control" style="min-width:120px">
          <option>Tất cả năm học</option>
          <option>Năm 1</option>
          <option>Năm 2</option>
          <option>Năm 3 (Ra trường)</option>
        </select>
        <select class="form-control" style="min-width:140px">
          <option>GPA: Tất cả</option>
          <option>Xuất sắc (≥ 3.6)</option>
          <option>Giỏi (3.2 – 3.59)</option>
          <option>Khá (2.5 – 3.19)</option>
          <option>Trung bình (&lt; 2.5)</option>
        </select>
        <select class="form-control" style="min-width:140px">
          <option>Học lực: Tất cả</option>
          <option>Xuất sắc</option>
          <option>Giỏi</option>
          <option>Khá</option>
          <option>Trung bình</option>
        </select>
        <select class="form-control" style="min-width:130px">
          <option>Sắp xếp: GPA cao</option>
          <option>GPA thấp</option>
          <option>Tên A–Z</option>
          <option>Tuổi trẻ nhất</option>
        </select>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px">
        <div style="font-size:13px;color:#888">Hiển thị <strong style="color:#111">36</strong> sinh viên</div>
        <div style="display:flex;gap:6px">
          <div class="action-btn" title="Dạng lưới" style="background:#0d4e96;border-color:#0d4e96;color:#fff"><i class="ti ti-layout-grid"></i></div>
          <div class="action-btn" title="Dạng danh sách"><i class="ti ti-list"></i></div>
        </div>
      </div>

      <div class="student-grid" id="studentGrid"></div>

      <div class="pagination" style="margin-top:24px">
        <div class="page-btn disabled"><i class="ti ti-chevron-left" style="font-size:13px"></i></div>
        <div class="page-btn active">1</div>
        <div class="page-btn">2</div>
        <div class="page-btn">3</div>
        <div class="page-btn"><i class="ti ti-chevron-right" style="font-size:13px"></i></div>
      </div>
    </div>

  </main>
</div>

<!-- ===== MOBILE SIDEBAR TOGGLE ===== -->
<button class="sidebar-toggle" id="sidebarToggle"><i class="ti ti-menu-2"></i></button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===== JOB MODAL ===== -->
<div class="modal-overlay" id="jobModal">
  <div class="modal">
    <div class="modal-header">
      <div class="modal-title"><i class="ti ti-news" style="color:#0d4e96;margin-right:6px"></i> Tạo bài đăng tuyển dụng</div>
      <button class="modal-close" onclick="closeJobModal()"><i class="ti ti-x"></i></button>
    </div>
    <div class="modal-body">
      <!-- Tab Nav trong modal -->
      <div class="tab-nav" style="margin-bottom:18px">
        <button class="tab-btn active" onclick="switchModalTab('basic',this)">Thông tin cơ bản</button>
        <button class="tab-btn" onclick="switchModalTab('require',this)">Yêu cầu ứng viên</button>
        <button class="tab-btn" onclick="switchModalTab('benefit',this)">Phúc lợi & Quyền lợi</button>
        <button class="tab-btn" onclick="switchModalTab('time',this)">Thời gian & Địa điểm</button>
      </div>

      <div class="tab-panel active" id="modal-tab-basic">
        <div class="form-grid">
          <div class="form-group full">
            <label class="form-label">Tên vị trí cần tuyển <span class="required">*</span></label>
            <input class="form-control" type="text" placeholder="VD: Nhân viên Kế toán tổng hợp..."/>
          </div>
          <div class="form-group">
            <label class="form-label">Số lượng tuyển <span class="required">*</span></label>
            <input class="form-control" type="number" placeholder="VD: 3" min="1"/>
          </div>
          <div class="form-group">
            <label class="form-label">Ngành nghề</label>
            <select class="form-control">
              <option>Kế toán – Tài chính</option>
              <option>Công nghệ thông tin</option>
              <option>Cơ khí – Kỹ thuật</option>
              <option>Marketing</option>
              <option>Nhân sự</option>
            </select>
          </div>
          <div class="form-group full">
            <label class="form-label">Mô tả công việc <span class="required">*</span></label>
            <textarea class="form-control" rows="5" placeholder="Liệt kê các đầu việc cụ thể ứng viên sẽ đảm nhận...&#10;- Xử lý chứng từ kế toán, hóa đơn...&#10;- Lập báo cáo tài chính tháng/quý/năm..."></textarea>
          </div>
        </div>
      </div>

      <div class="tab-panel" id="modal-tab-require">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Kinh nghiệm (số năm)</label>
            <select class="form-control">
              <option>Không yêu cầu (sinh viên mới ra trường)</option>
              <option>Dưới 1 năm</option>
              <option>1 – 2 năm</option>
              <option>3 – 5 năm</option>
              <option>Trên 5 năm</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Bằng cấp yêu cầu</label>
            <select class="form-control">
              <option>Không yêu cầu</option>
              <option>Trung cấp trở lên</option>
              <option>Cao đẳng trở lên</option>
              <option>Đại học trở lên</option>
              <option>Thạc sĩ / Tiến sĩ</option>
            </select>
          </div>
          <div class="form-group full">
            <label class="form-label">Kỹ năng chuyên môn</label>
            <textarea class="form-control" rows="3" placeholder="VD: Thành thạo Excel, MISA, phần mềm kế toán..."></textarea>
          </div>
          <div class="form-group full">
            <label class="form-label">Kỹ năng mềm</label>
            <textarea class="form-control" rows="3" placeholder="VD: Giao tiếp tốt, làm việc nhóm, chịu được áp lực..."></textarea>
          </div>
          <div class="form-group full">
            <label class="form-label">Yêu cầu khác</label>
            <textarea class="form-control" rows="2" placeholder="Giới tính, độ tuổi, hình thức... (nếu có)"></textarea>
          </div>
        </div>
      </div>

      <div class="tab-panel" id="modal-tab-benefit">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Mức lương</label>
            <select class="form-control" id="salaryType" onchange="toggleSalaryRange(this.value)">
              <option value="range">Khoảng lương cụ thể</option>
              <option value="negotiate">Thỏa thuận</option>
            </select>
          </div>
          <div class="form-group" id="salaryRangeGroup">
            <label class="form-label">Khoảng lương (triệu VNĐ)</label>
            <div style="display:flex;gap:8px;align-items:center">
              <input class="form-control" type="number" placeholder="Từ" style="width:50%"/> 
              <span style="color:#aaa;font-size:12px">–</span>
              <input class="form-control" type="number" placeholder="Đến" style="width:50%"/>
            </div>
          </div>
          <div class="form-group full">
            <label class="form-label">Bảo hiểm xã hội & Phúc lợi</label>
            <textarea class="form-control" rows="2" placeholder="VD: Đóng BHXH, BHYT đầy đủ theo luật..."></textarea>
          </div>
          <div class="form-group full">
            <label class="form-label">Thưởng & Đãi ngộ</label>
            <textarea class="form-control" rows="2" placeholder="VD: Thưởng KPI, thưởng lễ tết, du lịch hằng năm..."></textarea>
          </div>
          <div class="form-group full">
            <label class="form-label">Môi trường làm việc</label>
            <textarea class="form-control" rows="2" placeholder="VD: Văn phòng hiện đại, team trẻ, ít áp lực..."></textarea>
          </div>
        </div>
      </div>

      <div class="tab-panel" id="modal-tab-time">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">Hình thức làm việc</label>
            <select class="form-control">
              <option>Full-time (Toàn thời gian)</option>
              <option>Part-time (Bán thời gian)</option>
              <option>Thực tập sinh</option>
              <option>Hợp đồng thời vụ</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Địa điểm làm việc</label>
            <select class="form-control">
              <option>Tại văn phòng / Xưởng</option>
              <option>Remote (Làm từ xa)</option>
              <option>Hybrid (Kết hợp)</option>
            </select>
          </div>
          <div class="form-group full">
            <label class="form-label">Địa chỉ làm việc cụ thể</label>
            <input class="form-control" type="text" value="123 Nguyễn Huệ, P. Quyết Thắng, TP. Kon Tum"/>
          </div>
          <div class="form-group">
            <label class="form-label">Thời gian làm việc</label>
            <input class="form-control" type="text" placeholder="VD: Thứ 2 – Thứ 6, 8:00 – 17:00"/>
          </div>
          <div class="form-group">
            <label class="form-label">Hạn chót nộp hồ sơ <span class="required">*</span></label>
            <input class="form-control" type="date"/>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeJobModal()">Hủy</button>
      <button class="btn-primary" onclick="alert('Bài đăng đã được lưu thành công!');closeJobModal()"><i class="ti ti-send"></i> Đăng tuyển dụng</button>
    </div>
  </div>
</div>
<!-- ===== CHANGE PASSWORD MODAL ===== -->
<div class="modal-overlay password-modal-overlay" id="changePasswordModal" aria-hidden="true">
  <div class="modal password-modal" role="dialog" aria-modal="true" aria-labelledby="changePasswordTitle">
    <form id="changePasswordForm" novalidate>
      <div class="modal-header">
        <div class="modal-title" id="changePasswordTitle"><i class="ti ti-key" style="color:#0d4e96;margin-right:6px"></i> Đổi mật khẩu</div>
        <button type="button" class="modal-close" data-change-password-close aria-label="Đóng"><i class="ti ti-x"></i></button>
      </div>
      <div class="modal-body">
        <div class="change-password-success" id="changePasswordSuccess"><i class="ti ti-circle-check"></i> Mật khẩu đã được kiểm tra hợp lệ.</div>

        <div class="password-field" data-field="oldPassword">
          <label class="form-label" for="oldPassword">Mật khẩu cũ <span class="required">*</span></label>
          <div class="password-input-wrap">
            <i class="ti ti-lock"></i>
            <input class="form-control" id="oldPassword" name="oldPassword" type="password" autocomplete="current-password" placeholder="Nhập mật khẩu hiện tại">
            <button type="button" class="password-toggle" data-toggle-password="oldPassword" aria-label="Hiện/ẩn mật khẩu"><i class="ti ti-eye"></i></button>
          </div>
          <div class="password-error"></div>
        </div>

        <div class="password-field" data-field="newPassword">
          <label class="form-label" for="newPassword">Mật khẩu mới <span class="required">*</span></label>
          <div class="password-input-wrap">
            <i class="ti ti-shield-lock"></i>
            <input class="form-control" id="newPassword" name="newPassword" type="password" autocomplete="new-password" placeholder="Tối thiểu 8 ký tự">
            <button type="button" class="password-toggle" data-toggle-password="newPassword" aria-label="Hiện/ẩn mật khẩu"><i class="ti ti-eye"></i></button>
          </div>
          <div class="password-error"></div>
        </div>

        <div class="password-field" data-field="confirmPassword">
          <label class="form-label" for="confirmPassword">Xác nhận mật khẩu mới <span class="required">*</span></label>
          <div class="password-input-wrap">
            <i class="ti ti-lock-check"></i>
            <input class="form-control" id="confirmPassword" name="confirmPassword" type="password" autocomplete="new-password" placeholder="Nhập lại mật khẩu mới">
            <button type="button" class="password-toggle" data-toggle-password="confirmPassword" aria-label="Hiện/ẩn mật khẩu"><i class="ti ti-eye"></i></button>
          </div>
          <div class="password-error"></div>
        </div>

        <div class="password-hint"><strong>Yêu cầu:</strong> nhập đủ các trường, mật khẩu mới tối thiểu 8 ký tự, khác mật khẩu cũ và phần xác nhận phải trùng khớp.</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" data-change-password-close>Hủy</button>
        <button type="submit" class="btn-primary"><i class="ti ti-check"></i> Xác nhận</button>
      </div>
    </form>
  </div>
</div>
<script>
// ===== DATA =====
const APPLICANTS = [
  {name:"Nguyễn Thị Lan",color:"#0d4e96",initials:"NL",position:"Kế toán tổng hợp",submitted:"10/06/2025 08:30",exp:"Không yêu cầu",status:"new"},
  {name:"Trần Văn Hùng",color:"#1565c0",initials:"TH",position:"Kỹ thuật viên CNC",submitted:"09/06/2025 14:15",exp:"1 – 2 năm",status:"review"},
  {name:"Lê Thị Mai",color:"#2e7d32",initials:"LM",position:"Marketing Online",submitted:"08/06/2025 09:00",exp:"Không yêu cầu",status:"hired"},
  {name:"Phạm Quốc Bảo",color:"#c62828",initials:"PB",position:"Kỹ thuật viên CNC",submitted:"07/06/2025 16:45",exp:"3 – 5 năm",status:"review"},
  {name:"Hoàng Thị Thu",color:"#6a1b9a",initials:"HT",position:"Kế toán tổng hợp",submitted:"06/06/2025 10:20",exp:"Không yêu cầu",status:"new"},
  {name:"Vũ Minh Khoa",color:"#00695c",initials:"VK",position:"Kỹ thuật viên CNC",submitted:"05/06/2025 11:00",exp:"1 – 2 năm",status:"rejected"},
  {name:"Đặng Thị Hoa",color:"#e65100",initials:"ĐH",position:"Marketing Online",submitted:"04/06/2025 14:00",exp:"Không yêu cầu",status:"new"},
  {name:"Bùi Văn Nam",color:"#1a237e",initials:"BN",position:"Kế toán tổng hợp",submitted:"03/06/2025 09:30",exp:"1 – 2 năm",status:"review"},
];

const STATUS_MAP = {
  new: {label:"Mới nộp", cls:"new"},
  review: {label:"Đang xem xét", cls:"review"},
  hired: {label:"Đã tuyển", cls:"hired"},
  rejected: {label:"Từ chối", cls:"rejected"}
};

const STUDENTS = [
  {name:"Nguyễn Thị Lan",dob:"2003",major:"Kế toán",color:"#0d4e96",initials:"NL",gpa:3.8,year:"Năm 3",rank:"Xuất sắc"},
  {name:"Trần Văn Hùng",dob:"2002",major:"CNTT",color:"#1565c0",initials:"TH",gpa:3.5,year:"Năm 3",rank:"Giỏi"},
  {name:"Lê Thị Mai",dob:"2003",major:"Marketing",color:"#2e7d32",initials:"LM",gpa:3.2,year:"Năm 2",rank:"Giỏi"},
  {name:"Phạm Quốc Bảo",dob:"2002",major:"Kinh doanh",color:"#c62828",initials:"PB",gpa:2.9,year:"Năm 3",rank:"Khá"},
  {name:"Hoàng Thị Thu",dob:"2003",major:"Nhân sự",color:"#6a1b9a",initials:"HT",gpa:3.7,year:"Năm 2",rank:"Xuất sắc"},
  {name:"Vũ Minh Khoa",dob:"2002",major:"Cơ khí",color:"#00695c",initials:"VK",gpa:3.4,year:"Năm 3",rank:"Giỏi"},
  {name:"Đặng Thị Hoa",dob:"2003",major:"Thiết kế",color:"#e65100",initials:"ĐH",gpa:3.1,year:"Năm 1",rank:"Khá"},
  {name:"Bùi Văn Nam",dob:"2002",major:"Tài chính",color:"#1a237e",initials:"BN",gpa:3.9,year:"Năm 3",rank:"Xuất sắc"},
  {name:"Nguyễn Anh Tuấn",dob:"2003",major:"Logistics",color:"#33691e",initials:"NT",gpa:2.7,year:"Năm 2",rank:"Khá"},
  {name:"Trịnh Thị Nga",dob:"2002",major:"Du lịch",color:"#880e4f",initials:"TN",gpa:3.6,year:"Năm 3",rank:"Xuất sắc"},
  {name:"Phan Quang Vinh",dob:"2003",major:"Xây dựng",color:"#4e342e",initials:"PV",gpa:2.4,year:"Năm 2",rank:"TB"},
  {name:"Lương Thị Linh",dob:"2002",major:"Y tế",color:"#00838f",initials:"LL",gpa:3.8,year:"Năm 3",rank:"Xuất sắc"},
];

// ===== NAVIGATION =====
function showPage(id) {
  document.querySelectorAll('.dash-page').forEach(p => p.classList.remove('active'));
  document.getElementById('page-' + id).classList.add('active');
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  event && event.target && event.target.closest('.nav-item') && event.target.closest('.nav-item').classList.add('active');
  // close sidebar on mobile
  if (window.innerWidth <= 900) closeSidebar();
}

// ===== PROFILE TABS =====
function showProfileTab(tab) {
  document.querySelectorAll('#page-profile .tab-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('profile-tab-' + tab).classList.add('active');
  document.querySelectorAll('.profile-hero-tab').forEach((t,i) => {
    t.classList.toggle('active', ['info','link','media'][i] === tab);
  });
}

// ===== MODAL TABS =====
function switchModalTab(tab, btn) {
  document.querySelectorAll('#jobModal .tab-panel').forEach(p => p.classList.remove('active'));
  document.getElementById('modal-tab-' + tab).classList.add('active');
  document.querySelectorAll('#jobModal .tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

// ===== JOB MODAL =====
function openJobModal() {
  document.getElementById('jobModal').classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeJobModal() {
  document.getElementById('jobModal').classList.remove('show');
  document.body.style.overflow = '';
}
document.getElementById('jobModal').addEventListener('click', function(e) {
  if (e.target === this) closeJobModal();
});

// ===== SALARY TOGGLE =====
function toggleSalaryRange(val) {
  document.getElementById('salaryRangeGroup').style.display = val === 'negotiate' ? 'none' : '';
}

// ===== RENDER APPLICANTS =====
function renderApplicants() {
  const tbody = document.getElementById('applicantsBody');
  tbody.innerHTML = APPLICANTS.map(a => {
    const s = STATUS_MAP[a.status];
    return `<tr>
      <td>
        <div class="applicant-name-cell">
          <div class="applicant-avatar" style="background:${a.color}">${a.initials}</div>
          <div>
            <div class="applicant-name">${a.name}</div>
            <div class="applicant-sub">Xem hồ sơ</div>
          </div>
        </div>
      </td>
      <td style="font-weight:600;color:#444">${a.position}</td>
      <td style="color:#888;white-space:nowrap">${a.submitted}</td>
      <td style="color:#666">${a.exp}</td>
      <td><span class="status-pill ${s.cls}">${s.label}</span></td>
      <td>
        <div style="display:flex;gap:4px">
          <div class="action-btn" title="Xem hồ sơ"><i class="ti ti-eye"></i></div>
          <div class="action-btn" title="Liên hệ"><i class="ti ti-mail"></i></div>
          <div class="action-btn danger" title="Từ chối"><i class="ti ti-x"></i></div>
        </div>
      </td>
    </tr>`;
  }).join('');
}

// ===== RENDER STUDENTS =====
function renderStudents() {
  const grid = document.getElementById('studentGrid');
  grid.innerHTML = STUDENTS.map(s => {
    const gpaClass = s.gpa >= 3.6 ? 'high' : s.gpa >= 3.2 ? 'mid' : 'low';
    const tagClass = s.gpa >= 3.6 ? 'gpa-high' : s.gpa >= 3.2 ? 'gpa-mid' : 'gpa-low';
    return `<div class="student-card">
      <div class="student-card-avatar" style="background:${s.color}">${s.initials}</div>
      <div class="student-card-name">${s.name}</div>
      <div class="student-card-major">${s.major} · ${s.dob}</div>
      <div class="student-card-gpa ${gpaClass}">${s.gpa.toFixed(1)}</div>
      <div style="font-size:10px;color:#aaa;margin-bottom:6px">GPA</div>
      <div class="student-card-tags">
        <span class="student-tag ${tagClass}">${s.rank}</span>
        <span class="student-tag year">${s.year}</span>
      </div>
      <div style="margin-top:10px">
        <button class="btn-primary btn-sm" style="width:100%;justify-content:center;font-size:11px;padding:6px" onclick="alert('Đã gửi lời mời tới ${s.name}!')">
          <i class="ti ti-mail" style="font-size:12px"></i> Mời ứng tuyển
        </button>
      </div>
    </div>`;
  }).join('');
}

// ===== MOBILE SIDEBAR =====
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const sidebarToggle = document.getElementById('sidebarToggle');

function openSidebar() {
  sidebar.classList.add('open');
  sidebarOverlay.classList.add('show');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  sidebar.classList.remove('open');
  sidebarOverlay.classList.remove('show');
  document.body.style.overflow = '';
}
sidebarToggle.addEventListener('click', openSidebar);
sidebarOverlay.addEventListener('click', closeSidebar);

// ===== INIT =====
renderApplicants();
renderStudents();
</script>

<script>
(function(){
  document.body.classList.add('employer-logged-in');

  function closeAllUserMenus(except){
    document.querySelectorAll('.employer-user-menu.open').forEach(function(menu){
      if(menu !== except) menu.classList.remove('open');
    });
  }

  function buildUserMenu(){
    var actions = document.querySelector('.header-actions');
    if(!actions || actions.querySelector('.employer-user-menu')) return;
    actions.querySelectorAll('.btn-login,.btn-post,.js-login-open,.js-employer-login-open').forEach(function(el){
      el.style.display = 'none';
    });

    var menu = document.createElement('div');
    menu.className = 'employer-user-menu';
    menu.innerHTML = ''+
      '<button type="button" class="employer-user-button" aria-haspopup="true" aria-expanded="false">'+
        '<span class="employer-user-avatar"><i class="ti ti-user"></i></span>'+
        '<span>Kon Tum Digital</span>'+
        '<i class="ti ti-chevron-down employer-user-chevron"></i>'+
      '</button>'+
      '<div class="employer-user-dropdown">'+
        '<div class="employer-user-head"><strong>Kon Tum Digital</strong><span>Nhà tuyển dụng</span></div>'+
        '<a href="#" class="employer-user-link"><i class="ti ti-user-circle"></i> Tài khoản</a>'+
        '<a href="#" class="employer-user-link" data-change-password-open><i class="ti ti-key"></i> Đổi mật khẩu</a>'+
        '<a href="logout.php" class="employer-user-link logout"><i class="ti ti-logout-2"></i> Đăng xuất</a>'+
      '</div>';
    actions.appendChild(menu);

    var button = menu.querySelector('.employer-user-button');
    button.addEventListener('click', function(e){
      e.stopPropagation();
      var open = !menu.classList.contains('open');
      closeAllUserMenus(menu);
      menu.classList.toggle('open', open);
      button.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  function patchMobileMenu(){
    var userSection = document.querySelector('.mobile-menu .mm-user-section');
    if(!userSection || userSection.dataset.employerPatched === '1') return;
    userSection.dataset.employerPatched = '1';
    userSection.innerHTML = ''+
      '<div class="employer-mobile-user">'+
        '<span class="employer-user-avatar"><i class="ti ti-user"></i></span>'+
        '<div><strong>Kon Tum Digital</strong><span>Nhà tuyển dụng</span></div>'+
      '</div>'+
      '<div class="mm-btn-group">'+
        '<a class="mm-btn-login" href="#" data-change-password-open><i class="ti ti-key"></i> Đổi mật khẩu</a>'+
        '<a class="mm-btn-ntd" href="logout.php"><i class="ti ti-logout-2"></i> Đăng xuất</a>'+
      '</div>';
  }

  document.addEventListener('click', function(){ closeAllUserMenus(null); });
  document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeAllUserMenus(null); });
  if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', function(){ buildUserMenu(); patchMobileMenu(); });
  } else {
    buildUserMenu(); patchMobileMenu();
  }
})();
</script>
<script>
(function(){
  var modal = document.getElementById('changePasswordModal');
  var form = document.getElementById('changePasswordForm');
  var success = document.getElementById('changePasswordSuccess');
  if(!modal || !form) return;

  var fields = {
    oldPassword: document.getElementById('oldPassword'),
    newPassword: document.getElementById('newPassword'),
    confirmPassword: document.getElementById('confirmPassword')
  };

  function fieldWrap(name){
    return form.querySelector('[data-field="' + name + '"]');
  }

  function setError(name, message){
    var wrap = fieldWrap(name);
    if(!wrap) return;
    var error = wrap.querySelector('.password-error');
    wrap.classList.toggle('invalid', !!message);
    if(error) error.textContent = message || '';
  }

  function clearErrors(){
    Object.keys(fields).forEach(function(name){ setError(name, ''); });
    if(success) success.classList.remove('show');
  }

  function validateChangePasswordForm(){
    var oldValue = fields.oldPassword.value.trim();
    var newValue = fields.newPassword.value.trim();
    var confirmValue = fields.confirmPassword.value.trim();
    var valid = true;
    clearErrors();

    if(!oldValue){ setError('oldPassword', 'Vui lòng nhập mật khẩu cũ.'); valid = false; }
    if(!newValue){
      setError('newPassword', 'Vui lòng nhập mật khẩu mới.'); valid = false;
    } else if(newValue.length < 8){
      setError('newPassword', 'Mật khẩu mới phải có tối thiểu 8 ký tự.'); valid = false;
    } else if(oldValue && newValue === oldValue){
      setError('newPassword', 'Mật khẩu mới phải khác mật khẩu cũ.'); valid = false;
    }
    if(!confirmValue){
      setError('confirmPassword', 'Vui lòng xác nhận mật khẩu mới.'); valid = false;
    } else if(newValue && confirmValue !== newValue){
      setError('confirmPassword', 'Xác nhận mật khẩu mới không khớp.'); valid = false;
    }
    return valid;
  }

  function openChangePasswordModal(){
    form.reset();
    clearErrors();
    modal.classList.add('show');
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow = 'hidden';
    setTimeout(function(){ fields.oldPassword.focus(); }, 80);
  }

  function closeChangePasswordModal(){
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow = '';
    form.reset();
    clearErrors();
  }

  document.addEventListener('click', function(e){
    var opener = e.target.closest('[data-change-password-open]');
    if(opener){
      e.preventDefault();
      document.querySelectorAll('.employer-user-menu.open').forEach(function(menu){ menu.classList.remove('open'); });
      document.body.classList.remove('menu-open');
      openChangePasswordModal();
      return;
    }
    if(e.target.closest('[data-change-password-close]')){
      e.preventDefault();
      closeChangePasswordModal();
    }
  });

  modal.addEventListener('click', function(e){
    if(e.target === modal) closeChangePasswordModal();
  });

  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape' && modal.classList.contains('show')) closeChangePasswordModal();
  });

  Object.keys(fields).forEach(function(name){
    fields[name].addEventListener('input', validateChangePasswordForm);
  });

  document.querySelectorAll('[data-toggle-password]').forEach(function(button){
    button.addEventListener('click', function(){
      var input = document.getElementById(button.getAttribute('data-toggle-password'));
      if(!input) return;
      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      button.innerHTML = '<i class="ti ' + (show ? 'ti-eye-off' : 'ti-eye') + '"></i>';
    });
  });

  form.addEventListener('submit', function(e){
    e.preventDefault();
    if(!validateChangePasswordForm()) return;
    if(success) success.classList.add('show');
    setTimeout(function(){
      alert('Đổi mật khẩu thành công!');
      closeChangePasswordModal();
    }, 250);
  });
})();
</script>
<?php require 'footer.php'; ?>
