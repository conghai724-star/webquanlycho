<!-- Nạp thư viện bản đồ số Leaflet CSS và JS từ CDN và FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- ================= HERO BẢN ĐỒ ================= -->
<section class="hero" style="padding: 40px 0 50px;">
    <div class="hero-grid-pattern"></div>
    <div class="container hero-content" style="grid-template-columns: 1fr; text-align: center;">
        <div>
            <div class="hero-eyebrow" style="justify-content: center;"><span class="dot"></span> Bản đồ địa lý số</div>
            <h1>Bản đồ số Chợ & Kiosks</h1>
            <p style="margin: 0 auto; max-width: 90% !important;">Định vị tọa độ thực tế của sạp hàng chợ truyền thống, hiển thị trên nền Google Maps vệ tinh và dẫn đường GPS.</p>
        </div>
    </div>
</section>

<!-- Scoped CSS của Bản đồ số mới -->
<style>
    .map-dashboard-container {
        display: grid;
        grid-template-columns: 350px 1fr;
        height: 700px;
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        margin-bottom: 60px;
        transition: grid-template-columns 0.3s ease;
        position: relative;
    }
    .map-dashboard-container.hide-sidebar {
        grid-template-columns: 0px 1fr;
    }

    /* Sidebar tìm kiếm & thông tin bên trái */
    .map-dashboard-sidebar {
        background-color: #ffffff;
        border-right: 1px solid var(--border-color, #e2e8f0);
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
        transition: opacity 0.3s ease;
    }
    .map-dashboard-container.hide-sidebar .map-dashboard-sidebar {
        opacity: 0;
        pointer-events: none;
    }

    .sidebar-search-area {
        padding: 20px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
        background-color: #fafbfc;
    }
    
    .btn-toggle-sidebar-gps-wrapper {
        display: flex;
        justify-content: flex-start;
        margin-bottom: 12px;
    }
    .btn-toggle-sidebar-gps-btn {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    .btn-toggle-sidebar-gps-btn:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }
    .btn-toggle-sidebar-gps-btn.active {
        background-color: var(--primary, #0f766e);
        color: #ffffff;
        border-color: var(--primary, #0f766e);
    }

    .search-box-gps {
        display: flex;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 4px 8px;
        align-items: center;
        gap: 6px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-box-gps:focus-within {
        border-color: var(--primary, #0f766e);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.15);
    }
    .search-box-gps input {
        border: none !important;
        background: transparent !important;
        flex: 1;
        height: 32px !important;
        padding: 0 !important;
        outline: none !important;
        font-size: 13.5px;
        color: var(--text, #0f172a);
    }
    .search-box-gps i {
        color: #94a3b8;
        font-size: 14px;
    }

    /* Vùng danh sách / thông tin */
    .sidebar-scrollable-content {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .section-title-gps {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.8px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .category-list-gps {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 24px;
    }

    .category-item-gps {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s, transform 0.1s;
        border: 1px solid transparent;
        color: var(--text-secondary, #334155);
        font-size: 13.5px;
    }
    .category-item-gps:hover {
        background-color: #f1f5f9;
        transform: translateX(3px);
    }
    .category-item-gps i {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #ffffff;
    }
    .cat-wc i { background-color: #0ea5e9; }
    .cat-office i { background-color: #8b5cf6; }
    .cat-security i { background-color: #475569; }

    /* Thẻ thông tin sạp chi tiết */
    .stall-detail-card-gps {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
        margin-top: 10px;
        display: none; /* Mặc định ẩn, hiện khi click sạp */
    }

    .stall-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 10px;
    }
    .stall-detail-code {
        font-size: 17px;
        font-weight: 800;
        color: #0f172a;
    }

    .detail-row-gps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
        color: #475569;
    }
    .detail-row-gps strong {
        color: #0f172a;
    }

    .gps-coordinates-badge {
        background-color: #f1f5f9;
        border: 1px dashed #cbd5e1;
        border-radius: 6px;
        padding: 8px;
        font-family: var(--font-mono, monospace);
        font-size: 12px;
        color: #0f766e;
        text-align: center;
        margin: 14px 0;
        word-break: break-all;
    }

    /* Bản đồ bên phải */
    .map-dashboard-viewport {
        position: relative;
        height: 100%;
        background-color: #f1f5f9;
    }

    #map-canvas-gps {
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    /* Floating selector góc trên bên phải */
    .map-floating-selector {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 6px;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .selector-btn-gps {
        border: none;
        background: transparent;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s;
    }
    .selector-btn-gps:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }
    .selector-btn-gps.active {
        background-color: var(--primary, #0f766e);
        color: #ffffff;
    }

    /* Đồng bộ tuyệt đối nút Tất cả hình vuông với các nút control còn lại */
    .control-btn-gps.selector-btn-gps {
        width: 36px !important;
        height: 36px !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06) !important;
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        transition: all 0.2s;
    }

    /* Khi nút Tất cả được kích hoạt (active), chỉ đổi màu viền và màu icon */
    .control-btn-gps.selector-btn-gps.active {
        border-color: var(--primary, #0f766e) !important;
        color: var(--primary, #0f766e) !important;
        background-color: #ffffff !important;
    }

    /* Floating Compass & Controls */
    .map-floating-controls {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .control-btn-gps {
        width: 36px;
        height: 36px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        cursor: pointer;
        color: #475569;
        transition: all 0.2s;
    }
    .control-btn-gps:hover {
        background-color: #f1f5f9;
        color: #0f172a;
    }

    /* Badge trạng thái sạp */
    .badge-gps {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 9999px;
    }
    .badge-gps.rented { background-color: #dcfce7; color: #15803d; }
    .badge-gps.empty { background-color: #dbeafe; color: #1d4ed8; }
    .badge-gps.repairing { background-color: #fef9c3; color: #a16207; }
    .badge-gps.locked { background-color: #fee2e2; color: #b91c1c; }

    /* Custom Leaflet Tooltip & Label */
    .leaflet-stall-label {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        font-weight: 800;
        font-size: 10px;
        color: #1e293b;
        text-align: center;
        text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;
    }

    .btn-block {
        display: flex;
        width: 100%;
        justify-content: center;
        text-decoration: none !important;
        margin-top: 10px;
    }

    /* Responsive grid */
    @media (max-width: 768px) {
        .map-dashboard-container {
            grid-template-columns: 1fr;
            height: 900px;
        }
        .map-dashboard-sidebar {
            height: 380px;
            border-right: none;
            border-bottom: 1px solid var(--border-color, #e2e8f0);
        }
        .map-dashboard-viewport {
            height: 520px;
        }
    }

    /* Bảng chú giải màu sạp trên bản đồ */
    .map-legend {
        position: absolute;
        bottom: 12px;
        right: 12px;
        z-index: 1000;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(6px);
        border-radius: 8px;
        padding: 10px 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        border: 1px solid rgba(0,0,0,0.08);
        font-size: 11px;
    }
    .map-legend-title {
        font-weight: 800;
        font-size: 11px;
        color: #334155;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        cursor: move;
        user-select: none;
    }
    .map-legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 3px;
        color: #475569;
        font-weight: 500;
    }
    .map-legend-swatch {
        width: 14px;
        height: 14px;
        border-radius: 3px;
        border: 1.5px solid rgba(0,0,0,0.15);
        flex-shrink: 0;
    }
</style>

<!-- ================= KHU VỰC BẢN ĐỒ SỐ ĐỊA LÝ ================= -->
<section class="map-section" style="background: none; color: inherit; padding: 10px 0;">
        <!-- Thanh công cụ điều khiển bản đồ bên ngoài -->
        <div class="btn-toggle-sidebar-gps-wrapper" style="display: flex; gap: 8px; align-items: center;">
            <button id="btn-toggle-sidebar-front" class="btn-toggle-sidebar-gps-btn" title="Ẩn/Hiện bảng tìm kiếm" type="button">
                <i class="fa-solid fa-bars"></i>
            </button>
            <button id="btn-toggle-legend-front" class="btn-toggle-sidebar-gps-btn active" title="Ẩn/Hiện bộ lọc sơ đồ" type="button" style="padding: 8px 12px;">
                <i class="fa-solid fa-filter"></i>
            </button>
        </div>

        <div class="map-dashboard-container">
            <!-- CỘT TRÁI: Tìm kiếm, Bộ lọc & Chi tiết sạp -->
            <div class="map-dashboard-sidebar">
                <div class="sidebar-search-area">
                    <div class="search-box-gps" style="margin-bottom: 12px;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="map-search-input" placeholder="Tìm mã sạp (ví dụ: A01, B12)...">
                    </div>
                    
                    <!-- Bộ lọc Phân khu di chuyển từ trên map về đây -->
                    <div>
                        <span class="section-title-gps" style="margin-bottom: 6px; font-size: 10.5px;">Phân khu / Khu vực</span>
                        <div id="area-selector-container" style="display: flex; flex-direction: column; gap: 4px; max-height: 150px; overflow-y: auto; border: 1px solid var(--border-color, #cbd5e1); padding: 6px; border-radius: 8px; background: #fff;">
                        </div>
                    </div>
                </div>

                <div class="sidebar-scrollable-content">
                    <!-- Khung thông tin chi tiết sạp (mặc định ẩn, hiện khi click sạp) -->
                    <div id="stall-detail-card" class="stall-detail-card-gps">
                        <div class="stall-detail-header">
                            <span id="detail-code" class="stall-detail-code">SẠP-A01</span>
                            <span id="detail-status" class="badge-gps empty">Còn trống</span>
                        </div>
                        <div class="detail-row-gps">
                            <span>Khu vực:</span>
                            <strong id="detail-zone">--</strong>
                        </div>
                        <div class="detail-row-gps">
                            <span>Ngành hàng:</span>
                            <strong id="detail-business">--</strong>
                        </div>
                        <div class="detail-row-gps" id="detail-trader-row">
                            <span>Tiểu thương:</span>
                            <strong id="detail-trader">Chưa thuê</strong>
                        </div>
                        
                        <!-- Các nút tương tác -->
                        <a href="#" id="btn-open-google-maps" target="_blank" class="btn btn-primary btn-block btn-sm" style="margin-top: 15px;">
                            <i class="fa-solid fa-map-location-dot"></i> Xem vị trí bản đồ
                        </a>
                        <a href="#" id="btn-register-stall" class="btn btn-success btn-block btn-sm" style="display: none;">
                            <i class="fa-solid fa-file-signature"></i> Đăng ký thuê ngay
                        </a>
                    </div>

                    <!-- Màn hình chờ mặc định khi chưa chọn sạp -->
                    <div id="default-waiting-msg" style="text-align: center; padding: 40px 10px; color: var(--text-muted, #64748b);">
                        <i class="fa-solid fa-map-pin" style="font-size: 36px; color: var(--primary, #0f766e); margin-bottom: 14px; opacity: 0.7;"></i>
                        <p style="font-size: 13.5px; line-height: 1.5; font-weight: 500;">Bấm trực tiếp vào từng sạp hàng trên bản đồ hoặc nhập mã tìm kiếm để hiển thị thông tin chi tiết và định vị GPS.</p>
                    </div>

                    <div style="margin-top: 30px;">
                        <span class="section-title-gps">Chỉ dẫn tiện ích</span>
                        <div class="category-list-gps">
                            <div class="category-item-gps cat-security" onclick="focusOnUtility('security-room')">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Phòng bảo vệ</span>
                            </div>
                            <div class="category-item-gps cat-wc" onclick="focusOnUtility('utility')">
                                <i class="fa-solid fa-restroom"></i>
                                <span>Nhà vệ sinh công cộng</span>
                            </div>
                            <div class="category-item-gps cat-office" onclick="focusOnUtility('office')">
                                <i class="fa-solid fa-building-user"></i>
                                <span>Văn phòng BQL Chợ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: Bản đồ số địa lý Leaflet -->
            <div class="map-dashboard-viewport" style="position: relative;">
                <!-- Floating Controls -->
                <div class="map-floating-controls">
                    <button class="selector-btn-gps control-btn-gps active" id="btn-filter-all-front" title="Hiển thị tất cả sạp" type="button" style="border:1px solid #cbd5e1; padding:0;"><i class="fa-solid fa-border-all"></i></button>
                    <button id="btn-zoom-in-front" class="control-btn-gps" title="Phóng to" type="button" style="border:1px solid #cbd5e1; padding:0;"><i class="fa-solid fa-plus"></i></button>
                    <button id="btn-zoom-out-front" class="control-btn-gps" title="Thu nhỏ" type="button" style="border:1px solid #cbd5e1; padding:0;"><i class="fa-solid fa-minus"></i></button>
                    <button id="btn-reset-map-front" class="control-btn-gps" title="Căn giữa" type="button" style="border:1px solid #cbd5e1; padding:0;"><i class="fa-solid fa-crosshairs"></i></button>
                </div>

                <!-- Bảng lọc & chú giải sạp trên bản đồ -->
                <div class="map-legend" id="map-legend-front">
                    <div class="map-legend-title"><i class="fa-solid fa-filter"></i> Lọc & Chú giải sạp</div>
                    
                    <!-- Bộ lọc Khu vực -->
                    <div style="margin-bottom: 6px;">
                        <select id="map-filter-area-front" style="width:100%; font-size:11px; padding:4px 6px; border:1px solid #cbd5e1; border-radius:4px; background:#fff; cursor:pointer;">
                            <option value="">-- Tất cả Khu vực --</option>
                            <?php if (!empty($areas)): foreach ($areas as $a): ?>
                                <option value="<?php echo htmlspecialchars($a['area_name']); ?>"><?php echo htmlspecialchars($a['area_name']); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    
                    <!-- Bộ lọc Ngành hàng -->
                    <div style="margin-bottom: 8px;">
                        <select id="map-filter-business-front" style="width:100%; font-size:11px; padding:4px 6px; border:1px solid #cbd5e1; border-radius:4px; background:#fff; cursor:pointer;">
                            <option value="">-- Tất cả Ngành hàng --</option>
                            <?php if (!empty($businessLines)): foreach ($businessLines as $bl): ?>
                                <option value="<?php echo htmlspecialchars($bl['line_name']); ?>"><?php echo htmlspecialchars($bl['line_name']); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    
                    <button id="btn-clear-map-filter-front" style="width:100%; font-size:10px; padding:3px 0; border:1px solid #cbd5e1; border-radius:4px; background:#f8fafc; cursor:pointer; color:#64748b; margin-bottom:8px; font-weight:600;" type="button">
                        <i class="fa-solid fa-xmark"></i> Xóa bộ lọc
                    </button>
                    
                    <div style="border-top:1px solid #e2e8f0; padding-top:6px; margin-top:2px;">
                        <div class="map-legend-item"><span class="map-legend-swatch" style="background:#dcfce7; border-color:#22c55e;"></span> Đã thuê</div>
                        <div class="map-legend-item"><span class="map-legend-swatch" style="background:#dbeafe; border-color:#3b82f6;"></span> Còn trống</div>
                        <div class="map-legend-item"><span class="map-legend-swatch" style="background:#ffedd5; border-color:#f97316;"></span> Đang bảo trì</div>
                        <div class="map-legend-item"><span class="map-legend-swatch" style="background:#fee2e2; border-color:#ef4444;"></span> Tạm khóa</div>
                        <div class="map-legend-item"><span class="map-legend-swatch" style="background:#f1f5f9; border-color:#94a3b8;"></span> Chưa xác định</div>
                    </div>
                </div>

                <!-- Bản đồ thực tế -->
                <div id="map-canvas-gps"></div>
            </div>
        </div>

    </div>
</section>

<!-- ================= DANH SÁCH SẠP TRỐNG HỖ TRỢ ĐĂNG KÝ NHANH ================= -->
<section class="bg-gray" style="padding: 60px 0;">
    <div class="container">
        <div style="margin-bottom: 30px;">
            <div class="eyebrow">Cơ hội kinh doanh</div>
            <h2 class="section-title" style="margin-bottom:6px;">Danh sách sạp còn trống hiện tại</h2>
            <p style="color:var(--gray-600); font-size:15px;">Chọn sạp phù hợp trên bản đồ số hoặc danh sách bên dưới để gửi yêu cầu đăng ký thuê trực tuyến</p>
        </div>
        
        <div class="stalls-grid">
            <?php 
            $emptyStalls = array_filter($elements, function($e) {
                return $e['element_type'] === 'stall' && $e['status_code'] === 'empty';
            });
            ?>

            <?php if (!empty($emptyStalls)): ?>
                <?php foreach ($emptyStalls as $st): ?>
                    <div class="stall-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--gray-200); background: #ffffff; padding: 24px; border-radius: var(--radius-lg); display: flex; flex-direction: column; justify-content: space-between; transition: transform 0.2s;">
                        <div class="stall-top" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                            <div>
                                <div class="stall-code" style="font-size: 18px; font-weight: 800; color: var(--gray-900);"><?php echo htmlspecialchars($st['stall_code']); ?></div>
                                <div class="stall-zone" style="font-size: 12px; color: var(--gray-500); margin-top: 4px;">Khu vực: <?php echo htmlspecialchars($st['area_name']); ?></div>
                            </div>
                            <span class="badge badge-vacant" style="background-color: #dbeafe; color: #1d4ed8; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">Còn trống</span>
                        </div>
                        <div class="stall-meta" style="border-top: 1px solid var(--gray-200); border-bottom: 1px solid var(--gray-200); padding: 12px 0; margin-bottom: 16px; font-size: 13.5px; color: var(--gray-600);">
                            <div style="margin-bottom: 8px;">Diện tích: <b style="color: var(--gray-900);"><?php echo $st['stall_area_size']; ?> m²</b></div>
                            <div>Đơn giá: <b style="color: var(--gray-900);"><?php echo number_format($st['stall_base_price'], 0, ',', '.'); ?> đ</b>/tháng</div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button onclick="locateStallByCode('<?php echo htmlspecialchars($st['stall_code']); ?>')" class="btn btn-outline btn-sm" style="flex: 1; padding: 0;"><i class="fa-solid fa-location-dot"></i> Định vị</button>
                            <a href="<?php echo BASE_URL; ?>home/register?stall_code=<?php echo urlencode($st['stall_code']); ?>&area=<?php echo $st['stall_area_size']; ?>" class="btn btn-primary btn-sm" style="flex: 1.2; font-size: 12px;">Thuê ngay</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; background: #ffffff; border-radius: var(--radius-lg); border: 1px dashed var(--gray-300);">
                    <i class="fa-solid fa-store-slash" style="font-size: 40px; color: var(--gray-400); margin-bottom: 12px;"></i>
                    <p style="color: var(--gray-600); font-size: 14.5px;">Hiện tại không còn sạp trống nào trên sơ đồ. Xin vui lòng liên hệ trực tiếp BQL để được hỗ trợ.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Truyền dữ liệu từ PHP sang JS -->
<script>
    window.MAP_ELEMENTS = <?php echo json_encode($elements); ?>;
    window.MARKET_DATA = <?php echo json_encode($market); ?>;
    window.BASE_URL = '<?php echo BASE_URL; ?>';
    
    // Đăng ký sự kiện click nút ẩn/hiện sidebar bên ngoài
    document.addEventListener('DOMContentLoaded', function() {
        const btnToggle = document.getElementById('btn-toggle-sidebar-front');
        const container = document.querySelector('.map-dashboard-container');
        if (btnToggle && container) {
            btnToggle.addEventListener('click', function() {
                container.classList.toggle('hide-sidebar');
                btnToggle.classList.toggle('active');
                // Gọi leaflet update size sau khi transition kết thúc
                setTimeout(() => {
                    if (window.map) window.map.invalidateSize();
                }, 350);
            });
        }

        const btnLegendToggle = document.getElementById('btn-toggle-legend-front');
        const mapLegend = document.getElementById('map-legend-front');
        if (btnLegendToggle && mapLegend) {
            btnLegendToggle.addEventListener('click', function() {
                mapLegend.style.display = mapLegend.style.display === 'none' ? '' : 'none';
                btnLegendToggle.classList.toggle('active');
            });
        }

        // LÀM CHO BỘ LỌC CÓ THỂ DI CHUYỂN (DRAGGABLE) TRÊN FRONTEND
        if (mapLegend) {
            const titleEl = mapLegend.querySelector('.map-legend-title');
            if (titleEl) {
                let isDragging = false;
                let startX, startY, initialLeft, initialTop;

                titleEl.addEventListener('mousedown', function(e) {
                    isDragging = true;
                    const rect = mapLegend.getBoundingClientRect();
                    const viewport = document.querySelector('.map-dashboard-viewport');
                    const containerRect = viewport.getBoundingClientRect();
                    
                    initialLeft = rect.left - containerRect.left;
                    initialTop = rect.top - containerRect.top;
                    
                    mapLegend.style.bottom = 'auto';
                    mapLegend.style.left = initialLeft + 'px';
                    mapLegend.style.top = initialTop + 'px';

                    startX = e.clientX;
                    startY = e.clientY;
                    
                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                    
                    e.preventDefault();
                });

                function onMouseMove(e) {
                    if (!isDragging) return;
                    const dx = e.clientX - startX;
                    const dy = e.clientY - startY;
                    
                    let newLeft = initialLeft + dx;
                    let newTop = initialTop + dy;
                    
                    const viewport = document.querySelector('.map-dashboard-viewport');
                    if (viewport) {
                        const maxLeft = viewport.clientWidth - mapLegend.clientWidth - 10;
                        const maxTop = viewport.clientHeight - mapLegend.clientHeight - 10;
                        
                        newLeft = Math.max(10, Math.min(newLeft, maxLeft));
                        newTop = Math.max(10, Math.min(newTop, maxTop));
                    }

                    mapLegend.style.left = newLeft + 'px';
                    mapLegend.style.top = newTop + 'px';
                }

                function onMouseUp() {
                    isDragging = false;
                    document.removeEventListener('mousemove', onMouseMove);
                    document.removeEventListener('mouseup', onMouseUp);
                }
            }
        }
    });
</script>

<!-- Script khởi tạo Leaflet map & xử lý định vị GPS -->
<script src="<?php echo BASE_URL; ?>template/app/assets/js/pages/home/map-view-gps.js?v=<?php echo time(); ?>"></script>
