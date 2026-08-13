<?php
/**
 * View template: Thiết lập Sơ đồ chợ tương tác (Admin Map Editor)
 */
include_once __SITE_PATH . '/model/marketModel.php';
$marketModel = new marketModel();
$marketId = marketService::currentMarketId();
$market = $marketModel->getById($marketId);
?>

<!-- Nạp FontAwesome và các thư viện Leaflet CSS/JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!-- Nạp thư viện thông báo SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Bố cục Editor */
    .map-editor-container {
        display: grid;
        grid-template-columns: 340px 1fr 300px;
        height: calc(100vh - 120px);
        margin: -15px;
        background-color: var(--card-bg, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 8px;
        overflow: hidden;
        transition: grid-template-columns 0.3s ease;
    }
    .map-editor-container.hide-left { grid-template-columns: 0px 1fr 300px; }
    .map-editor-container.hide-right { grid-template-columns: 340px 1fr 0px; }
    .map-editor-container.hide-left.hide-right { grid-template-columns: 0px 1fr 0px; }
    .map-editor-container .editor-panel { transition: opacity 0.3s ease, overflow 0s; }
    .map-editor-container.hide-left > .editor-panel:first-child { opacity: 0; overflow: hidden; pointer-events: none; }
    .map-editor-container.hide-right > .editor-panel-right { opacity: 0; overflow: hidden; pointer-events: none; }

    .editor-panel {
        display: flex;
        flex-direction: column;
        background-color: var(--card-bg, #ffffff);
        border-right: 1px solid var(--border-color, #e2e8f0);
        height: 100%;
        overflow: hidden;
    }

    .editor-panel-right {
        border-right: none;
        border-left: 1px solid var(--border-color, #e2e8f0);
    }

    .panel-header {
        padding: 12px 16px;
        font-weight: 700;
        font-size: 14px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
        background-color: rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .panel-content {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
    }

    .editor-canvas-area {
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .canvas-toolbar {
        height: 50px;
        background-color: var(--card-bg, #ffffff);
        border-bottom: 1px solid var(--border-color, #e2e8f0);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 16px;
        z-index: 1000;
    }

    .toolbar-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #map-canvas-editor {
        flex: 1;
        width: 100%;
        height: 100%;
        background-color: #f1f5f9;
        z-index: 1;
    }

    /* Form gán nhanh & Thuộc tính */
    .property-group {
        margin-bottom: 12px;
    }

    .property-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--text-muted, #64748b);
    }

    .property-input {
        width: 100%;
        height: 34px;
        padding: 6px 10px;
        font-size: 13px;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 6px;
        background: var(--html-bg, #f8fafc);
        color: var(--text-color, #0f172a);
        outline: none;
        box-sizing: border-box;
    }
    .property-input:focus {
        border-color: var(--primary, #0f766e);
    }

    /* Danh sách sạp chưa gán */
    .unmapped-stall-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        margin-bottom: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .unmapped-stall-item:hover {
        background-color: rgba(15, 118, 110, 0.08);
        border-color: var(--primary, #0f766e);
    }

    .unmapped-search {
        width: 100%;
        height: 32px;
        padding: 6px 10px;
        font-size: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    /* Thẻ gán nhanh 1 bước */
    .quick-bind-card {
        background-color: rgba(15, 118, 110, 0.03);
        border: 1px dashed var(--primary, #0f766e);
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 20px;
    }

    /* Các nút công cụ vẽ sạp */
    .draw-tool-btn {
        width: 100%;
        height: 38px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 0 12px;
        gap: 10px;
        font-weight: 600;
        font-size: 13px;
        color: #475569;
        cursor: pointer;
        margin-bottom: 8px;
        transition: all 0.2s;
    }
    .draw-tool-btn:hover, .draw-tool-btn.active {
        background-color: var(--primary, #0f766e);
        color: #ffffff;
        border-color: var(--primary, #0f766e);
    }

    /* Nhãn sạp trên map */
    .leaflet-stall-label-editor {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        font-weight: 800;
        font-size: 9px;
        color: #1e293b;
        text-align: center;
        text-shadow: -1px -1px 0 #fff, 1px -1px 0 #fff, -1px 1px 0 #fff, 1px 1px 0 #fff;
    }

    .badge-status {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
    }
    .badge-status.rented { background-color: #dcfce7; color: #15803d; }
    .badge-status.empty { background-color: #dbeafe; color: #1d4ed8; }
    .badge-status.repairing { background-color: #ffedd5; color: #c2410c; }
    .badge-status.locked { background-color: #fee2e2; color: #dc2626; }
    .permanently-hidden { display: none !important; }

    /* Bảng chú giải màu sạp trên bản đồ */
    .map-legend {
        position: absolute;
        bottom: 12px;
        left: 12px;
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
    
    /* Trạng thái nút toggle đang được kích hoạt */
    .btn.active {
        background-color: var(--primary, #0f766e) !important;
        color: #ffffff !important;
        border-color: var(--primary, #0f766e) !important;
    }

    /* Floating Controls góc trên bên phải */
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
    .control-btn-gps.active {
        border-color: var(--primary, #0f766e) !important;
        color: var(--primary, #0f766e) !important;
        background-color: #ffffff !important;
    }
</style>

<div class="map-editor-container">
    <!-- PANEL TRÁI: Công cụ vẽ & Gán nhanh 1 bước -->
    <div class="editor-panel">
        <div class="panel-header">
            <span><i class="fa-solid fa-toolbox"></i> Hộp Công Cụ</span>
        </div>
        <div class="panel-content">
            <!-- THẺ GÁN TỌA ĐỘ NHANH 1 BƯỚC -->
            <!-- THẺ GÁN TỌA ĐỘ NHANH 1 BƯỚC -->
            <?php
            $unmappedAreas = [];
            if (!empty($stalls)) {
                foreach ($stalls as $st) {
                    if (!empty($st['area_name'])) {
                        $unmappedAreas[$st['area_name']] = true;
                    }
                }
            }
            $unmappedAreas = array_keys($unmappedAreas);
            sort($unmappedAreas);
            ?>

            <div class="quick-bind-card">
                <div style="font-weight: 700; font-size: 13px; color: var(--primary, #0f766e); margin-bottom: 12px; display: flex; align-items: center; justify-content: space-between;">
                    <span><i class="fa-solid fa-bolt"></i> Gán Tọa Độ Nhanh</span>
                    <label style="margin: 0; display: flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 500; color: #475569; cursor: pointer;">
                        <input type="checkbox" id="qb-toggle-mapped" style="width: 14px; height: 14px; cursor: pointer; margin: 0;">
                        <span>Tìm sạp đã gán</span>
                    </label>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div class="property-group">
                        <label for="qb-filter-area">Lọc theo Khu</label>
                        <select id="qb-filter-area" class="property-input" style="font-size: 12px; padding: 5px 8px;">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($unmappedAreas as $area): ?>
                                <option value="<?php echo htmlspecialchars($area); ?>"><?php echo htmlspecialchars($area); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="property-group">
                        <label for="qb-search-input">Tìm sạp nhanh</label>
                        <input type="text" id="qb-search-input" class="property-input" placeholder="Nhập mã sạp, tên tiểu thương..." style="font-size: 12px; padding: 5px 8px;">
                    </div>
                </div>

                <div class="property-group">
                    <label for="qb-stall-code">Sạp cần gán <span style="color: var(--red, #ef4444)">*</span></label>
                    <select id="qb-stall-code" class="property-input">
                        <option value="">-- Chọn Sạp --</option>
                        <?php foreach ($stalls as $st): ?>
                            <option value="<?php echo htmlspecialchars($st['stall_code']); ?>" 
                                    data-stall-id="<?php echo $st['stall_id']; ?>"
                                    data-area-name="<?php echo htmlspecialchars($st['area_name'] ?? ''); ?>"
                                    data-trader-name="<?php echo htmlspecialchars($st['trader_name'] ?? ''); ?>">
                                <?php echo htmlspecialchars($st['stall_code']); ?> 
                                (Khu: <?php echo htmlspecialchars($st['area_name'] ?? 'Chưa rõ'); ?>
                                <?php if (!empty($st['trader_name'])): ?> - TT: <?php echo htmlspecialchars($st['trader_name']); ?><?php endif; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="property-group">
                    <label for="qb-gps-data">Dán tọa độ / Link Google Maps</label>
                    <input type="text" id="qb-gps-data" class="property-input" placeholder="VD: 15.122174, 108.802315 hoặc link Google Maps...">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div class="property-group">
                        <label for="qb-width">Rộng (m)</label>
                        <input type="number" id="qb-width" class="property-input" step="0.1" value="3.0">
                    </div>
                    <div class="property-group">
                        <label for="qb-length">Dài (m)</label>
                        <input type="number" id="qb-length" class="property-input" step="0.1" value="3.0">
                    </div>
                </div>

                <div class="property-group">
                    <label for="qb-rotation">Góc xoay (Độ)</label>
                    <input type="number" id="qb-rotation" class="property-input" min="0" max="359" value="0">
                </div>

                <button class="btn btn-primary btn-block btn-sm" id="btn-quick-bind-run" style="width:100%;">
                    <i class="fa-solid fa-location-crosshairs"></i> Ghi nhận & Định vị sạp
                </button>
            </div>

            <!-- CÔNG CỤ VẼ MỚI -->
            <div style="font-weight: 700; font-size: 12px; margin-bottom: 10px; color: var(--text-muted, #64748b); text-transform: uppercase; letter-spacing: 0.5px;">Vẽ phần tử mới</div>
            <button class="draw-tool-btn" id="tool-draw-stall" data-tool="stall">
                <i class="fa-solid fa-store" style="color: #3b82f6;"></i>
                <span>Vẽ sạp mới (Stall)</span>
            </button>
            <button class="draw-tool-btn" id="tool-draw-street" data-tool="street">
                <i class="fa-solid fa-road" style="color: #64748b;"></i>
                <span>Vẽ Lối đi (Street)</span>
            </button>
            <button class="draw-tool-btn" id="tool-draw-fence" data-tool="fence">
                <i class="fa-solid fa-bars" style="color: #d97706;"></i>
                <span>Vẽ Hàng rào (Fence)</span>
            </button>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px;">
                <button class="draw-tool-btn" id="tool-place-wc" data-tool="utility" style="padding:0 4px; font-size: 11px;">
                    <i class="fa-solid fa-restroom" style="color: #0ea5e9;"></i>
                    <span>Đặt WC</span>
                </button>
                <button class="draw-tool-btn" id="tool-place-office" data-tool="office" style="padding:0 4px; font-size: 11px;">
                    <i class="fa-solid fa-building-user" style="color: #8b5cf6;"></i>
                    <span>Đặt BQL</span>
                </button>
                <button class="draw-tool-btn" id="tool-place-security" data-tool="security-room" style="padding:0 4px; font-size: 11px;">
                    <i class="fa-solid fa-shield-halved" style="color: #475569;"></i>
                    <span>Đặt Bảo vệ</span>
                </button>
            </div>

            <!-- DANH SÁCH SẠP CHƯA GÁN -->
            <div style="font-weight: 700; font-size: 12px; margin: 20px 0 10px; color: var(--text-muted, #64748b); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: space-between;">
                <span id="unmapped-title">Sạp chưa có tọa độ</span>
                <span style="background: rgba(15, 118, 110, 0.1); color: var(--primary, #0f766e); padding: 2px 6px; border-radius: 10px; font-size: 10px;" id="unmapped-count"><?php echo count($unmappedStalls); ?></span>
            </div>

            <div id="unmapped-stalls-list" style="max-height: 250px; overflow-y: auto; border: 1px solid var(--border-color, #e2e8f0); border-radius: 6px; padding: 4px;">
                <?php if (!empty($stalls)): ?>
                    <?php foreach ($stalls as $stall): ?>
                        <div class="unmapped-stall-item" 
                             data-stall-id="<?php echo $stall['stall_id']; ?>" 
                             data-stall-code="<?php echo htmlspecialchars($stall['stall_code']); ?>" 
                             data-area-name="<?php echo htmlspecialchars($stall['area_name'] ?? ''); ?>" 
                             data-trader-name="<?php echo htmlspecialchars($stall['trader_name'] ?? ''); ?>" 
                             onclick="selectUnmappedStall('<?php echo htmlspecialchars($stall['stall_code']); ?>')"
                             style="flex-direction: column; align-items: flex-start; gap: 4px; padding: 8px 10px; height: auto;">
                            
                            <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                                <div>
                                    <i class="fa-solid fa-store" style="margin-right: 6px; color: var(--primary, #0f766e);"></i>
                                    <strong style="font-size: 12px;"><?php echo htmlspecialchars($stall['stall_code']); ?></strong>
                                </div>
                                <span style="font-size: 10px; color: var(--text-muted, #64748b);"><?php echo $stall['area_size']; ?> m²</span>
                            </div>
                            
                            <div style="font-size: 10px; color: var(--text-muted, #64748b); padding-left: 18px; line-height: 1.4;">
                                <div><i class="fa-solid fa-layer-group" style="font-size:9px; width:12px;"></i> Khu: <?php echo htmlspecialchars($stall['area_name'] ?? 'Chưa rõ'); ?></div>
                                <?php if (!empty($stall['trader_name'])): ?>
                                    <div style="color: #0f766e; font-weight: 500;"><i class="fa-solid fa-user" style="font-size:9px; width:12px;"></i> TT: <?php echo htmlspecialchars($stall['trader_name']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- CANVAS CHÍNH: Bản đồ vệ tinh thực tế -->
    <div class="editor-canvas-area">
        <div class="canvas-toolbar">
            <div class="toolbar-group">
                <span style="font-weight: 700; font-size: 13.5px; color: var(--primary, #0f766e);"><i class="fa-solid fa-map-location-dot"></i> Thiết kế bản đồ số chợ (GPS Vệ Tinh)</span>
            </div>

            <div class="toolbar-group" style="gap: 4px;">
                <button class="btn btn-default btn-sm" id="btn-toggle-left" type="button" title="Ẩn/Hiện panel trái" style="border:1px solid #cbd5e1; padding:4px 8px;"><i class="fa-solid fa-table-columns"></i></button>
                <button class="btn btn-default btn-sm" id="btn-toggle-right" type="button" title="Ẩn/Hiện panel phải" style="border:1px solid #cbd5e1; padding:4px 8px;"><i class="fa-solid fa-table-columns fa-flip-horizontal"></i></button>
                <button class="btn btn-default btn-sm" id="btn-toggle-legend" type="button" title="Ẩn/Hiện bộ lọc" style="border:1px solid #cbd5e1; padding:4px 8px;"><i class="fa-solid fa-filter"></i></button>
                <span style="border-left:1px solid #e2e8f0; height:20px;"></span>
                <button class="btn btn-default btn-sm" id="btn-toggle-map-type" type="button" style="border: 1px solid var(--border-color, #cbd5e1);"><i class="fa-solid fa-layer-group"></i> Bản đồ vệ tinh</button>
                <button class="btn btn-outline btn-sm" id="btn-clear-map" style="color: var(--red, #ef4444);"><i class="fa-solid fa-trash-can"></i> Xóa Hết</button>
                <button class="btn btn-primary btn-sm" id="btn-save-map"><i class="fa-solid fa-floppy-disk"></i> Lưu Bản Đồ</button>
            </div>
        </div>

        <!-- Div chứa bản đồ địa lý -->
        <div id="map-canvas-editor" style="position: relative;">
            <!-- Floating Controls -->
            <div class="map-floating-controls">
                <button class="selector-btn-gps control-btn-gps active" id="btn-filter-all-admin" title="Hiển thị tất cả sạp" type="button" style="border:1px solid #cbd5e1; padding:0;"><i class="fa-solid fa-border-all"></i></button>
                <button id="btn-zoom-in-admin" class="control-btn-gps" title="Phóng to" type="button" style="border:1px solid #cbd5e1; padding:0;"><i class="fa-solid fa-plus"></i></button>
                <button id="btn-zoom-out-admin" class="control-btn-gps" title="Thu nhỏ" type="button" style="border:1px solid #cbd5e1; padding:0;"><i class="fa-solid fa-minus"></i></button>
                <button id="btn-reset-map-admin" class="control-btn-gps" title="Căn giữa" type="button" style="border:1px solid #cbd5e1; padding:0;"><i class="fa-solid fa-crosshairs"></i></button>
            </div>
        </div>
    </div>

    <!-- PANEL PHẢI: CHI TIẾT THUỘC TÍNH PHẦN TỬ ĐANG CHỌN -->
    <div class="editor-panel editor-panel-right">
        <div class="panel-header">
            <span><i class="fa-solid fa-sliders"></i> Thuộc Tính</span>
        </div>

        <!-- Bảng lọc & chú giải sạp trên bản đồ (Chuyển sang bên phải, ngoài map) -->
        <div id="map-legend" style="border-bottom: 1px solid var(--border-color, #e2e8f0); padding: 12px 16px; background-color: rgba(0, 0, 0, 0.01);">
            <div style="font-weight: 700; font-size: 13px; color: var(--primary, #0f766e); margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-filter"></i> Lọc & Chú giải sạp
            </div>
            
            <!-- Bộ lọc Khu vực -->
            <div style="margin-bottom: 6px;">
                <select id="map-filter-area" style="width:100%; font-size:12px; padding:6px; border:1px solid #cbd5e1; border-radius:6px; background:#fff; cursor:pointer;">
                    <option value="">-- Tất cả Khu vực --</option>
                    <?php if (!empty($areas)): foreach ($areas as $a): ?>
                        <option value="<?php echo htmlspecialchars($a['area_name']); ?>"><?php echo htmlspecialchars($a['area_name']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            
            <!-- Bộ lọc Ngành hàng -->
            <div style="margin-bottom: 8px;">
                <select id="map-filter-business" style="width:100%; font-size:12px; padding:6px; border:1px solid #cbd5e1; border-radius:6px; background:#fff; cursor:pointer;">
                    <option value="">-- Tất cả Ngành hàng --</option>
                    <?php if (!empty($businessLines)): foreach ($businessLines as $bl): ?>
                        <option value="<?php echo htmlspecialchars($bl['line_name']); ?>"><?php echo htmlspecialchars($bl['line_name']); ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            
            <button id="btn-clear-map-filter" style="width:100%; font-size:11px; padding:4px 0; border:1px solid #cbd5e1; border-radius:6px; background:#f8fafc; cursor:pointer; color:#64748b; margin-bottom:8px; font-weight:600;" type="button">
                <i class="fa-solid fa-xmark"></i> Xóa bộ lọc
            </button>
            
            <div style="border-top: 1px solid #e2e8f0; padding-top: 8px; margin-top: 4px; display: flex; flex-wrap: wrap; gap: 6px 12px; font-size: 11px;">
                <div style="display:flex; align-items:center; gap:4px; color:#475569;"><span style="width:12px; height:12px; border-radius:3px; border:1px solid #22c55e; background:#dcfce7; display:inline-block; flex-shrink:0;"></span> Đã thuê</div>
                <div style="display:flex; align-items:center; gap:4px; color:#475569;"><span style="width:12px; height:12px; border-radius:3px; border:1px solid #3b82f6; background:#dbeafe; display:inline-block; flex-shrink:0;"></span> Trống</div>
                <div style="display:flex; align-items:center; gap:4px; color:#475569;"><span style="width:12px; height:12px; border-radius:3px; border:1px solid #f97316; background:#ffedd5; display:inline-block; flex-shrink:0;"></span> Bảo trì</div>
                <div style="display:flex; align-items:center; gap:4px; color:#475569;"><span style="width:12px; height:12px; border-radius:3px; border:1px solid #ef4444; background:#fee2e2; display:inline-block; flex-shrink:0;"></span> Khóa</div>
            </div>
        </div>

        <div class="panel-content" id="property-panel-content">
            <div style="text-align: center; color: var(--text-muted, #64748b); padding: 40px 10px;" id="no-selection-msg">
                <i class="fa-solid fa-mouse-pointer" style="font-size: 32px; margin-bottom: 12px; opacity: 0.5;"></i>
                <p style="font-size: 13px;">Click chọn một phần tử trên sơ đồ để thiết lập thông số.</p>
            </div>

            <div id="selection-form" style="display: none;">
                <!-- Lock Position Option -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 15px; background: rgba(239, 68, 68, 0.05); padding: 8px 12px; border-radius: 6px; border: 1px dashed rgba(239, 68, 68, 0.3);">
                    <span style="font-size: 12px; font-weight: 700; color: #475569;"><i class="fa-solid fa-location-dot"></i> Vị trí phần tử:</span>
                    <label style="margin: 0; display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; font-weight: 700; color: var(--red, #ef4444);">
                        <input type="checkbox" id="prop-lock-position" checked style="width: 16px; height: 16px; cursor: pointer; margin: 0;">
                        <span id="prop-lock-text">Đang khóa di chuyển</span>
                    </label>
                </div>

                <!-- Loại phần tử -->
                <div class="property-group permanently-hidden">
                    <label>Loại phần tử</label>
                    <input type="text" id="prop-type-name" class="property-input" readonly style="background: rgba(0,0,0,0.03); font-weight: bold;">
                </div>

                <!-- Tên / Nhãn hiển thị -->
                <div class="property-group permanently-hidden">
                    <label for="prop-name">Tên hiển thị / Nhãn</label>
                    <input type="text" id="prop-name" class="property-input" placeholder="Ví dụ: Cổng số 1">
                </div>

                <!-- Chọn Sạp (Nếu là sạp) -->
                <div class="property-group permanently-hidden" id="group-stall-binding">
                    <label for="prop-stall-id">Liên kết Sạp chợ thật <span style="color: var(--red, #ef4444)">*</span></label>
                    <select id="prop-stall-id" class="property-input">
                        <option value="">-- Chọn Sạp chưa gán --</option>
                        <?php foreach ($stalls as $st): ?>
                            <option value="<?php echo $st['stall_id']; ?>"><?php echo htmlspecialchars($st['stall_code']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- GPS Coordinates -->
                <div class="property-group permanently-hidden">
                    <label>Tọa độ GPS (Center Lat, Lng)</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                        <input type="number" id="prop-lat" class="property-input" step="0.000001" placeholder="Vĩ độ">
                        <input type="number" id="prop-lng" class="property-input" step="0.000001" placeholder="Kinh độ">
                    </div>
                </div>

                <!-- Chiều rộng & Chiều dài thực tế (mét) -->
                <div class="permanently-hidden" id="group-stall-dimensions">
                    <div class="property-group">
                        <label for="prop-w-m">Chiều rộng sạp (m)</label>
                        <input type="number" id="prop-w-m" class="property-input" step="0.1" value="3.0">
                    </div>
                    <div class="property-group">
                        <label for="prop-h-m">Chiều dài sạp (m)</label>
                        <input type="number" id="prop-h-m" class="property-input" step="0.1" value="3.0">
                    </div>
                </div>

                <!-- Góc xoay -->
                <div class="property-group permanently-hidden" id="group-rotation-container">
                    <label for="prop-rotation">Góc xoay sạp (Độ)</label>
                    <input type="number" id="prop-rotation" class="property-input" min="0" max="359" step="1" value="0">
                </div>

                <!-- Màu sắc (Nếu không phải sạp) -->
                <div class="property-group" id="group-color-picker">
                    <label for="prop-color">Màu nền tùy chọn</label>
                    <div style="display: flex; gap: 8px;">
                        <input type="color" id="prop-color" class="property-input" style="width: 45px; padding: 2px; height: 32px; cursor: pointer;">
                        <input type="text" id="prop-color-hex" class="property-input" placeholder="#FFFFFF" style="flex: 1;">
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid var(--border-color, #e2e8f0); margin: 20px 0;">

                <button class="btn btn-outline btn-block" id="btn-delete-element" style="width:100%; color: var(--red, #ef4444); border-color: rgba(239, 68, 68, 0.3); background: rgba(239, 68, 68, 0.02);">
                    <i class="fa-solid fa-trash"></i> Xóa Phần Tử Này
                </button>

                <!-- Thẻ xem chi tiết khi liên kết sạp -->
                <div id="stall-info-panel" style="margin-top: 20px; border-top: 1px dashed var(--border-color, #e2e8f0); padding-top: 16px; display: none;">
                    <h5 style="margin: 0 0 12px 0; font-size: 13px; font-weight: 700; color: var(--primary, #0f766e); display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-circle-info"></i> Thông tin sạp thuê
                    </h5>
                    <div style="font-size: 12px; line-height: 1.6; display: flex; flex-direction: column; gap: 8px; background: rgba(0,0,0,0.02); padding: 12px; border-radius: 6px; border: 1px solid var(--border-color, #e2e8f0);">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted, #64748b);">Diện tích:</span>
                            <strong id="stall-info-area">--</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: var(--text-muted);">Đơn giá:</span>
                            <strong id="stall-info-price">--</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">Trạng thái:</span>
                            <span class="badge" id="stall-info-status" style="font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px;">--</span>
                        </div>
                        <div id="stall-info-trader-row" style="display: none; flex-direction: column; gap: 6px; border-top: 1px solid rgba(0,0,0,0.05); padding-top: 8px; margin-top: 4px;">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-muted);">Tiểu thương:</span>
                                <strong id="stall-info-trader" style="color: var(--primary, #0f766e);">--</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<!-- Modal Xác Nhận Xóa Phần Tử -->
<div id="modal-confirm-delete" class="custom-modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; margin: auto; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: 1px solid var(--border-color, #e2e8f0); background: #fff; overflow: hidden; animation: modalFadeIn 0.2s ease-out;">
        <div class="card-header" style="background: #fafbfc; border-bottom: 1px solid #f1f5f9; padding: 16px 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-triangle-exclamation" style="color: var(--red, #ef4444); font-size: 18px;"></i>
            <div class="card-title" style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0;">Xác nhận xóa phần tử</div>
        </div>
        <div class="card-body" style="padding: 20px;">
            <p style="margin: 0 0 20px 0; font-size: 13.5px; color: #475569; line-height: 1.5;">Bạn có chắc chắn muốn xóa phần tử này khỏi sơ đồ? Thao tác này không thể hoàn tác.</p>
            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-outline" id="btn-cancel-delete" style="padding: 8px 16px; font-size: 13px; font-weight: 600;">Hủy</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete" style="padding: 8px 16px; font-size: 13px; font-weight: 600; background-color: var(--red, #ef4444); border-color: var(--red, #ef4444); color: #fff;">Xác nhận xóa</button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
.btn-danger:hover {
    background-color: #dc2626 !important;
    border-color: #dc2626 !important;
}
</style>

<!-- Nạp dữ liệu sạp & Chợ vào JS -->
<script>
    window.DB_STALLS = <?php echo json_encode($stalls); ?>;
    window.MARKET_DATA = <?php echo json_encode($market); ?>;
</script>

<script>
(function () {
    // 1. Hàm hiển thị Toast thông báo bằng SweetAlert2
    function showToast(message, type) {
        var toastConfig = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        // Bộ lọc đã được chuyển vào static panel bên phải nên không cần di chuyển (draggable) nữa

        var iconType = 'info';
        if (type === 'danger') iconType = 'error';
        else if (type === 'success') iconType = 'success';
        else if (type === 'warning') iconType = 'warning';

        toastConfig.fire({
            icon: iconType,
            title: message
        });
    }

    // 2. Khai báo các trạng thái editor
    const market = window.MARKET_DATA || {};
    const marketLat = parseFloat(market.market_latitude) || 15.122174;
    const marketLng = parseFloat(market.market_longitude) || 108.802315;
    const marketZoom = parseInt(market.market_map_zoom) || 19;

    let map;
    let satelliteLayer;
    let flatLayer;
    let currentMapType = 'flat'; // 'flat' hoặc 'satellite'
    let elements = []; // Lưu trữ danh sách phần tử sơ đồ đang chỉnh sửa
    let selectedElement = null; // Phần tử đang chọn
    let activeTool = null; // Công cụ vẽ hiện tại (null, 'stall', 'street', 'fence', 'utility'...)
    let activePolyline = null; // Polyline tạm thời khi đang vẽ đường

    // DOM Elements
    const qbStallCode = document.getElementById('qb-stall-code');
    const qbGpsData = document.getElementById('qb-gps-data');
    const qbWidth = document.getElementById('qb-width');
    const qbLength = document.getElementById('qb-length');
    const qbRotation = document.getElementById('qb-rotation');

    const selectionForm = document.getElementById('selection-form');
    const noSelectionMsg = document.getElementById('no-selection-msg');
    
    // Inputs panel phải
    const propTypeName = document.getElementById('prop-type-name');
    const propName = document.getElementById('prop-name');
    const propStallId = document.getElementById('prop-stall-id');
    const propLat = document.getElementById('prop-lat');
    const propLng = document.getElementById('prop-lng');
    const propWM = document.getElementById('prop-w-m');
    const propHM = document.getElementById('prop-h-m');
    const propRotation = document.getElementById('prop-rotation');
    const propColor = document.getElementById('prop-color');
    const propColorHex = document.getElementById('prop-color-hex');
    const groupStallBinding = document.getElementById('group-stall-binding');
    const groupStallDimensions = document.getElementById('group-stall-dimensions');
    const groupColorPicker = document.getElementById('group-color-picker');
    const stallInfoPanel = document.getElementById('stall-info-panel');

    // 3. Thuật toán lượng giác: chuyển từ Lat/Lng + Kích thước (m) -> 4 góc sạp
    function calculateRectVertices(centerLat, centerLng, widthM, lengthM, rotationDeg) {
        const R = 6378137; // Bán kính Trái Đất (mét)
        const d2r = Math.PI / 180;
        const r2d = 180 / Math.PI;

        const theta = rotationDeg * d2r;
        const cosT = Math.cos(theta);
        const sinT = Math.sin(theta);

        const halfW = widthM / 2;
        const halfL = lengthM / 2;

        const localCorners = [
            { x: -halfW, y: -halfL },
            { x: halfW, y: -halfL },
            { x: halfW, y: halfL },
            { x: -halfW, y: halfL }
        ];

        return localCorners.map(pt => {
            const rx = pt.x * cosT - pt.y * sinT;
            const ry = pt.x * sinT + pt.y * cosT;

            const dLat = (ry / R) * r2d;
            const dLng = (rx / (R * Math.cos(centerLat * d2r))) * r2d;

            return [centerLat + dLat, centerLng + dLng];
        });
    }

    // 4. Khởi tạo bản đồ thiết kế (Nạp ảnh vệ tinh Google Hybrid cao cấp)
    function initEditorMap() {
        map = L.map('map-canvas-editor', {
            zoomControl: false,
            doubleClickZoom: false // Tắt dblclick zoom để dùng làm sự kiện kết thúc vẽ đường
        }).setView([marketLat, marketLng], marketZoom);

        // Sử dụng Google Satellite Hybrid tiles (Hiển thị mái sạp thực tế rất sắc nét)
        satelliteLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            maxZoom: 22,
            maxNativeZoom: 20
        });

        // Sử dụng Carto Light làm bản đồ phẳng tối giản (như bên home/map)
        flatLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 22,
            maxNativeZoom: 20
        });

        // Mặc định hiển thị bản đồ phẳng
        flatLayer.addTo(map);

        // Tải dữ liệu bản đồ đã có từ DB
        loadMapData();

        // Bắt sự kiện click lên bản đồ để lấy tọa độ gán hoặc vẽ sạp
        map.on('click', handleMapClick);

        // Hoàn thành vẽ polyline khi double click trên map
        map.on('dblclick', function (e) {
            if ((activeTool === 'street' || activeTool === 'fence') && selectedElement) {
                // Loại bỏ điểm trùng cuối cùng thường sinh ra do dblclick
                if (selectedElement.waypoints.length > 1) {
                    selectedElement.waypoints.pop();
                }

                // Vẽ lại đa giác hoàn thiện
                drawElementOnMap(selectedElement);
                elements.push(selectedElement);
                selectElement(selectedElement);

                deactivateTools();
                showToast('Đã vẽ xong đường đi/hàng rào!', 'success');
            } else if (selectedElement && !activeTool) {
                // Kiểm tra trạng thái khóa vị trí sạp
                const lockCheckbox = document.getElementById('prop-lock-position');
                const isLocked = lockCheckbox ? lockCheckbox.checked : true;
                
                if (isLocked) {
                    showToast('Vị trí đang Khóa! Hãy bỏ chọn "Đang khóa di chuyển" trong bảng Thuộc Tính để di chuyển sạp.', 'warning');
                    return;
                }

                // Di chuyển phần tử đang chọn đến tọa độ click đúp
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                
                recordState();
                selectedElement.latitude = lat;
                selectedElement.longitude = lng;
                
                propLat.value = lat.toFixed(6);
                propLng.value = lng.toFixed(6);
                
                drawElementOnMap(selectedElement);
                showToast('Đã di chuyển sạp đến vị trí mới thành công!', 'success');
            } else {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                const gpsStr = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                
                // Tự động điền vào ô Gán nhanh bên trái
                if (qbGpsData) {
                    qbGpsData.value = gpsStr;
                }
                
                // Tự động sao chép vào Clipboard
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(gpsStr).then(() => {
                        showToast(`Đã sao chép và tự động điền tọa độ GPS: ${gpsStr}`, 'success');
                    }).catch(() => {
                        showToast(`Đã tự động điền tọa độ GPS: ${gpsStr}`, 'success');
                    });
                } else {
                    showToast(`Đã tự động điền tọa độ GPS: ${gpsStr}`, 'success');
                }
                
                // Ghim ghim tạm thời để trực quan hóa
                const tempMarker = L.marker([lat, lng]).addTo(map);
                tempMarker.bindPopup(`<b>Tọa độ điểm chọn:</b><br>${gpsStr}`).openPopup();
                
                setTimeout(() => {
                    map.removeLayer(tempMarker);
                }, 5000);
            }
        });

        // ===== SỰ KIỆN CHO 4 NÚT ĐIỀU KHIỂN NỔI TRÊN BẢN ĐỒ ADMIN =====
        const btnZoomIn = document.getElementById('btn-zoom-in-admin');
        const btnZoomOut = document.getElementById('btn-zoom-out-admin');
        const btnResetMap = document.getElementById('btn-reset-map-admin');
        
        if (btnZoomIn) {
            btnZoomIn.addEventListener('click', function() {
                if (map) map.zoomIn();
            });
        }
        if (btnZoomOut) {
            btnZoomOut.addEventListener('click', function() {
                if (map) map.zoomOut();
            });
        }
        if (btnResetMap) {
            btnResetMap.addEventListener('click', function() {
                if (map) map.setView([marketLat, marketLng], marketZoom);
            });
        }

        const btnFilterAllAdmin = document.getElementById('btn-filter-all-admin');
        if (btnFilterAllAdmin) {
            btnFilterAllAdmin.addEventListener('click', function() {
                const mapFilterArea = document.getElementById('map-filter-area');
                const mapFilterBusiness = document.getElementById('map-filter-business');
                if (mapFilterArea) mapFilterArea.value = '';
                if (mapFilterBusiness) mapFilterBusiness.value = '';
                
                const qbFilterArea = document.getElementById('qb-filter-area');
                const qbSearchInput = document.getElementById('qb-search-input');
                const qbToggleMapped = document.getElementById('qb-toggle-mapped');
                if (qbFilterArea) qbFilterArea.value = '';
                if (qbSearchInput) qbSearchInput.value = '';
                if (qbToggleMapped) qbToggleMapped.checked = false;
                
                // Highlight nút Tất cả
                btnFilterAllAdmin.classList.add('active');
                
                if (typeof applyMapFilter === 'function') {
                    applyMapFilter();
                }
                if (typeof filterQuickBindStalls === 'function') {
                    filterQuickBindStalls();
                }
            });
        }

        // Thiết lập bộ sự kiện form thuộc tính & gán nhanh
        setupEventBindings();
    }

    // Tải dữ liệu sơ đồ hiện tại của chợ qua API
    function loadMapData() {
        $.ajax({
            type: 'GET',
            url: '<?php echo BASE_URL; ?>api/getMapElements',
            dataType: 'json',
            success: function (response) {
                // Xóa sạch các layer vẽ cũ
                elements.forEach(el => {
                    if (el.layer) map.removeLayer(el.layer);
                    if (el.labelLayer) map.removeLayer(el.labelLayer);
                });
                elements = [];

                const items = response.data || response || [];
                if (items.length > 0) {
                    items.forEach(item => {
                        createElementFromData(item);
                    });
                }
                updateUnmappedStallsBadge();
                if (typeof filterQuickBindStalls === 'function') {
                    filterQuickBindStalls();
                }
            },
            error: function () {
                showToast('Không thể tải dữ liệu sơ đồ chợ từ máy chủ.', 'danger');
            }
        });
    }

    // Vẽ phần tử dựa trên dữ liệu tải từ DB
    function createElementFromData(data) {
        let el = {
            id: data.element_id,
            element_type: data.element_type,
            element_name: data.element_name,
            stall_id: data.element_stall_id,
            stall_code: data.stall_code,
            // pixel fallback
            pos_x: parseInt(data.element_pos_x) || 0,
            pos_y: parseInt(data.element_pos_y) || 0,
            width: parseInt(data.element_width) || 40,
            height: parseInt(data.element_height) || 40,
            
            // GPS fields
            latitude: data.element_latitude ? parseFloat(data.element_latitude) : null,
            longitude: data.element_longitude ? parseFloat(data.element_longitude) : null,
            width_m: data.element_width_m ? parseFloat(data.element_width_m) : 3.0,
            length_m: data.element_length_m ? parseFloat(data.element_length_m) : 3.0,
            rotation: parseInt(data.element_rotation) || 0,
            color: data.element_color,
            waypoints: data.element_waypoints,
            stroke_width: data.element_stroke_width
        };

        drawElementOnMap(el);
        elements.push(el);
    }

    // Thực hiện vẽ đối tượng lên Leaflet Map dựa trên thuộc tính GPS của nó
    function drawElementOnMap(el) {
        // Xóa layer vẽ cũ nếu tồn tại
        if (el.layer) map.removeLayer(el.layer);
        if (el.labelLayer) map.removeLayer(el.labelLayer);

        let layer = null;

        // Vẽ Sạp
        if (el.element_type === 'stall') {
            if (el.latitude && el.longitude) {
                const corners = calculateRectVertices(el.latitude, el.longitude, el.width_m, el.length_m, el.rotation);
                const isSelected = (selectedElement === el);
                const statusColors = getStallStatusColor(el.stall_id);

                layer = L.polygon(corners, {
                    color: isSelected ? '#0f766e' : statusColors.border,
                    fillColor: isSelected ? '#0f766e' : statusColors.fill,
                    fillOpacity: 0.6,
                    weight: isSelected ? 3 : 1.5
                }).addTo(map);

                // Thêm nhãn stall code lên sạp
                const labelIcon = L.divIcon({
                    className: 'leaflet-stall-label-editor',
                    html: el.stall_code || 'SẠP',
                    iconSize: [40, 16],
                    iconAnchor: [20, 8]
                });
                
                el.labelLayer = L.marker([el.latitude, el.longitude], {
                    icon: labelIcon,
                    interactive: false
                }).addTo(map);
            }
        }
        // Vẽ biểu tượng tiện ích (WC, Cổng, Office...)
        else if (el.latitude && el.longitude) {
            const isSelected = (selectedElement === el);
            const markerColor = isSelected ? '#0f766e' : '#475569';
            const iconHtml = `<div style="background-color:${markerColor}; color:#fff; border-radius:50%; width:24px; height:24px; display:flex; align-items:center; justify-content:center; border:2px solid #fff; box-shadow:0 2px 6px rgba(0,0,0,0.2);"><i class="${getIconClass(el.element_type)}" style="font-size:10px;"></i></div>`;

            const icon = L.divIcon({
                className: 'leaflet-marker-utility-editor',
                html: iconHtml,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            layer = L.marker([el.latitude, el.longitude], { icon: icon }).addTo(map);
        }

        if (layer) {
            el.layer = layer;

            // Bắt sự kiện click để chọn đối tượng
            layer.on('click', function (e) {
                L.DomEvent.stopPropagation(e);
                selectElement(el);
            });
        }
    }

    // 5. Thao tác Click trên bản đồ (Lấy tọa độ click hoặc Vẽ)
    function handleMapClick(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        // Nếu đang bật công cụ vẽ đa giác sạp mới: Tạo sạp mới tại vị trí click
        if (activeTool === 'stall') {
            const codeInput = qbStallCode.value.trim().toUpperCase();
            if (!codeInput) {
                showToast('Hãy nhập mã sạp bên khung Gán Nhanh trước khi click đặt sạp!', 'warning');
                return;
            }

            createStallAtGPS(codeInput, lat, lng);
            deactivateTools();
        }
        // Đang đặt tiện ích (WC, Cổng, Office, Bảo vệ)
        else if (['utility', 'office', 'gate', 'security-room'].includes(activeTool)) {
            const tempId = 'temp-' + Date.now();
            let newEl = {
                temp_id: tempId,
                element_type: activeTool,
                element_name: getTypeNameVietnamese(activeTool),
                latitude: lat,
                longitude: lng
            };

            drawElementOnMap(newEl);
            elements.push(newEl);
            selectElement(newEl);

            deactivateTools();
            showToast('Đã đặt tiện ích thành công!', 'success');
        }
    }

    // ponytail: dblclick handler moved inside initEditorMap() — was crashing here because `map` is undefined at IIFE top-level

    // Tạo sạp chợ tại vị trí GPS
    function createStallAtGPS(stallCode, lat, lng) {
        // Kiểm tra xem mã sạp có tồn tại trong DB_STALLS không
        let dbStall = window.DB_STALLS ? window.DB_STALLS.find(s => s.stall_code.toUpperCase() === stallCode) : null;
        if (!dbStall) {
            showToast(`Mã sạp "${stallCode}" không tồn tại trong danh sách của Chợ!`, 'danger');
            return;
        }

        const width = parseFloat(qbWidth.value) || 3.0;
        const length = parseFloat(qbLength.value) || 3.0;
        const rotation = parseInt(qbRotation.value) || 0;

        // Kiểm tra xem sạp đã được vẽ trên bản đồ chưa
        let existingElement = elements.find(el => el.stall_id == dbStall.stall_id);
        if (existingElement) {
            // Cập nhật vị trí và kích thước sạp đã tồn tại
            existingElement.latitude = lat;
            existingElement.longitude = lng;
            existingElement.width_m = width;
            existingElement.length_m = length;
            existingElement.rotation = rotation;

            if (existingElement.layer) map.removeLayer(existingElement.layer);
            if (existingElement.labelLayer) map.removeLayer(existingElement.labelLayer);

            drawElementOnMap(existingElement);
            selectElement(existingElement);
            
            showToast(`Đã di chuyển sạp ${stallCode} đến tọa độ mới thành công!`, 'success');
            return;
        }

        const tempId = 'temp-' + Date.now();
        let newStall = {
            temp_id: tempId,
            element_type: 'stall',
            element_name: stallCode,
            stall_id: dbStall.stall_id,
            stall_code: stallCode,
            latitude: lat,
            longitude: lng,
            width_m: width,
            length_m: length,
            rotation: rotation,
            // default pixel fallback
            pos_x: 0,
            pos_y: 0,
            width: 40,
            height: 40
        };

        drawElementOnMap(newStall);
        elements.push(newStall);
        selectElement(newStall);

        // Xóa khỏi danh sách chưa gán
        removeStallFromUnmappedList(dbStall.stall_id);
        updateUnmappedStallsBadge();
        showToast(`Đã định vị sạp ${stallCode} thành công!`, 'success');
    }

    // 6. GẮN TỌA ĐỘ NHANH 1 BƯỚC (Parser dán link & trích xuất)
    function runQuickBind() {
        const stallCode = qbStallCode.value.trim().toUpperCase();
        const rawGps = qbGpsData.value.trim();

        if (!stallCode) {
            showToast('Vui lòng nhập mã sạp cần gán!', 'warning');
            return;
        }
        if (!rawGps) {
            showToast('Vui lòng dán tọa độ hoặc link Google Maps!', 'warning');
            return;
        }

        // Kiểm tra xem có phải link Google Maps rút ngắn hay không (maps.app.goo.gl hoặc goo.gl/maps)
        const isShortenedUrl = rawGps.toLowerCase().includes('maps.app.goo.gl') || rawGps.toLowerCase().includes('goo.gl/maps');
        
        if (isShortenedUrl) {
            showToast('Đang giải mã liên kết rút gọn, vui lòng chờ...', 'info');
            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL; ?>api/resolveShortenedUrl',
                data: JSON.stringify({ url: rawGps }),
                contentType: 'application/json',
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200 && response.data.latitude && response.data.longitude) {
                        const lat = response.data.latitude;
                        const lng = response.data.longitude;
                        createStallAtGPS(stallCode, lat, lng);
                        map.setView([lat, lng], 21);
                        qbGpsData.value = '';
                        qbStallCode.value = '';
                    } else {
                        showToast('Giải mã link rút gọn thất bại: ' + (response.message || 'Không tìm thấy tọa độ'), 'danger');
                    }
                },
                error: function (xhr) {
                    let msg = 'Lỗi kết nối máy chủ để giải mã link rút gọn.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    showToast(msg, 'danger');
                }
            });
            return;
        }

        // Tách tọa độ GPS bằng các định dạng ưu tiên từ link liên kết hoặc text thường
        let lat = null;
        let lng = null;

        // Ưu tiên 1: Link chứa tọa độ ghim địa điểm chính xác (!3d...!4d)
        let match = rawGps.match(/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/i);
        if (match) {
            lat = parseFloat(match[1]);
            lng = parseFloat(match[2]);
        }
        
        // Ưu tiên 2: Link chứa tham số query=lat,lng hoặc q=lat,lng
        if (lat === null) {
            match = rawGps.match(/(?:query|q)=(-?\d+\.\d+)(?:%2C|,)(-?\d+\.\d+)/i);
            if (match) {
                lat = parseFloat(match[1]);
                lng = parseFloat(match[2]);
            }
        }

        // Ưu tiên 3: Link chứa viewport camera (@lat,lng)
        if (lat === null) {
            match = rawGps.match(/@(-?\d+\.\d+),(-?\d+\.\d+)/);
            if (match) {
                lat = parseFloat(match[1]);
                lng = parseFloat(match[2]);
            }
        }

        // Ưu tiên 4: Dữ liệu copy thô (chuỗi số Lat, Lng phân tách bởi phẩy/space/kí tự bất kì)
        if (lat === null) {
            const gpsRegex = /(-?\d+\.\d+)[,\s/\\|#@:]+\s*(-?\d+\.\d+)/;
            match = rawGps.match(gpsRegex);
            if (match) {
                lat = parseFloat(match[1]);
                lng = parseFloat(match[2]);
            }
        }

        if (lat !== null && lng !== null) {
            // Thực hiện vẽ sạp tại GPS trích xuất được
            createStallAtGPS(stallCode, lat, lng);
            
            // Di chuyển camera bản đồ đến điểm vừa gán
            map.setView([lat, lng], 21);
            
            // Reset ô dán tọa độ để tiện nhập sạp tiếp theo
            qbGpsData.value = '';
            qbStallCode.value = '';
        } else {
            showToast('Không thể trích xuất tọa độ GPS từ dữ liệu dán vào. Vui lòng thử lại!', 'danger');
        }
    }

    // 7. Chọn phần tử hiển thị thông tin ở cột Phải
    function selectElement(el) {
        if (selectedElement === el) return; // Tránh đệ quy lặp lại
        deselectElement(); // Hủy chọn cái cũ trước

        selectedElement = el;

        // Reset nút khóa vị trí về mặc định: Khóa (checked)
        const lockCheckbox = document.getElementById('prop-lock-position');
        if (lockCheckbox) {
            lockCheckbox.checked = true;
            lockCheckbox.dispatchEvent(new Event('change'));
        }

        // Vẽ lại sạp cũ với nét vẽ nổi bật
        drawElementOnMap(selectedElement);

        // Hiển thị form
        noSelectionMsg.style.display = 'none';
        selectionForm.style.display = 'block';

        propTypeName.value = getTypeNameVietnamese(el.element_type);
        propName.value = el.element_name || '';

        // Tọa độ GPS
        propLat.value = el.latitude ? el.latitude.toFixed(6) : '';
        propLng.value = el.longitude ? el.longitude.toFixed(6) : '';

        // Rotation
        propRotation.value = el.rotation || 0;

        // Hiển thị các trường đặc thù theo loại
        if (el.element_type === 'stall') {
            groupStallBinding.style.display = 'block';
            groupStallDimensions.style.display = 'grid';
            groupColorPicker.style.display = 'none';

            propStallId.value = el.stall_id || '';
            propWM.value = el.width_m || 3.0;
            propHM.value = el.length_m || 3.0;

            updateStallInfoPanel(el.stall_id);

            // Đồng bộ sang khung Gán Nhanh bên trái
            const qbToggleMapped = document.getElementById('qb-toggle-mapped');
            const qbStallCode = document.getElementById('qb-stall-code');
            
            if (qbToggleMapped && qbStallCode) {
                // 1. Chuyển bộ lọc sang trạng thái "Tìm sạp đã gán"
                if (!qbToggleMapped.checked) {
                    qbToggleMapped.checked = true;
                    if (typeof filterQuickBindStalls === 'function') filterQuickBindStalls();
                }
                
                // 2. Chọn sạp này trong dropdown nếu chưa chọn
                if (qbStallCode.value !== el.stall_code) {
                    qbStallCode.value = el.stall_code;
                    // Kích hoạt change sự kiện của dropdown nhưng chặn việc gọi ngược selectElement
                    window.isSyncingFromMap = true;
                    qbStallCode.dispatchEvent(new Event('change'));
                    window.isSyncingFromMap = false;
                }
            }
        } else {
            groupStallBinding.style.display = 'none';
            groupStallDimensions.style.display = 'none';
            groupColorPicker.style.display = 'block';

            propColor.value = el.color || '#cbd5e1';
            propColorHex.value = el.color || '#cbd5e1';
            stallInfoPanel.style.display = 'none';
        }
    }

    // Hủy chọn
    function deselectElement() {
        if (selectedElement) {
            const old = selectedElement;
            selectedElement = null;
            // Vẽ lại nét vẽ thường
            drawElementOnMap(old);
        }

        selectionForm.style.display = 'none';
        noSelectionMsg.style.display = 'block';
        stallInfoPanel.style.display = 'none';
    }

    // Cập nhật thông tin sạp chi tiết ở panel thuộc tính
    function updateStallInfoPanel(stallId) {
        if (!stallId) {
            stallInfoPanel.style.display = 'none';
            return;
        }

        const details = window.DB_STALLS ? window.DB_STALLS.find(s => s.stall_id == stallId) : null;
        if (details) {
            stallInfoPanel.style.display = 'block';
            document.getElementById('stall-info-area').textContent = (details.area_size || '--') + ' m²';
            
            const price = parseInt(details.base_price) || 0;
            document.getElementById('stall-info-price').textContent = price > 0 ? price.toLocaleString('vi-VN') + ' đ' : '--';

            const statusBadge = document.getElementById('stall-info-status');
            const code = details.status_code || 'empty';
            statusBadge.className = `badge badge-status ${code}`;
            statusBadge.textContent = getStatusName(code);

            const traderRow = document.getElementById('stall-info-trader-row');
            if (code === 'rented' && details.trader_name) {
                traderRow.style.display = 'flex';
                document.getElementById('stall-info-trader').textContent = details.trader_name;
            } else {
                traderRow.style.display = 'none';
            }
        } else {
            stallInfoPanel.style.display = 'none';
        }
    }

    // 8. Bắt sự kiện chỉnh sửa các input cột Phải
    function setupEventBindings() {
        // Nút chuyển đổi loại bản đồ (Vệ tinh / Bản đồ phẳng)
        const btnToggleMapType = document.getElementById('btn-toggle-map-type');
        if (btnToggleMapType) {
            btnToggleMapType.addEventListener('click', function () {
                if (currentMapType === 'satellite') {
                    map.removeLayer(satelliteLayer);
                    flatLayer.addTo(map);
                    currentMapType = 'flat';
                    btnToggleMapType.innerHTML = '<i class="fa-solid fa-layer-group"></i> Bản đồ vệ tinh';
                } else {
                    map.removeLayer(flatLayer);
                    satelliteLayer.addTo(map);
                    currentMapType = 'satellite';
                    btnToggleMapType.innerHTML = '<i class="fa-solid fa-layer-group"></i> Bản đồ phẳng';
                }
            });
        }

        // ===== TOGGLE ẨN/HIỆN PANEL & BỘ LỌC =====
        const editorContainer = document.querySelector('.map-editor-container');
        const mapLegend = document.getElementById('map-legend');

        document.getElementById('btn-toggle-left').addEventListener('click', function() {
            editorContainer.classList.toggle('hide-left');
            setTimeout(() => map.invalidateSize(), 350);
            this.classList.toggle('active');
        });
        document.getElementById('btn-toggle-right').addEventListener('click', function() {
            editorContainer.classList.toggle('hide-right');
            setTimeout(() => map.invalidateSize(), 350);
            this.classList.toggle('active');
        });
        document.getElementById('btn-toggle-legend').addEventListener('click', function() {
            mapLegend.style.display = mapLegend.style.display === 'none' ? '' : 'none';
            this.classList.toggle('active');
        });

        // Lắng nghe thay đổi nút khóa di chuyển
        const propLockPosition = document.getElementById('prop-lock-position');
        const propLockText = document.getElementById('prop-lock-text');
        if (propLockPosition) {
            propLockPosition.addEventListener('change', function () {
                if (propLockPosition.checked) {
                    propLockText.textContent = 'Đang khóa di chuyển';
                    propLockPosition.parentElement.style.color = 'var(--red, #ef4444)';
                    propLockPosition.parentElement.parentElement.style.background = 'rgba(239, 68, 68, 0.05)';
                    propLockPosition.parentElement.parentElement.style.borderColor = 'rgba(239, 68, 68, 0.3)';
                } else {
                    propLockText.textContent = 'Cho phép di chuyển';
                    propLockPosition.parentElement.style.color = 'var(--primary, #0f766e)';
                    propLockPosition.parentElement.parentElement.style.background = 'rgba(15, 118, 110, 0.05)';
                    propLockPosition.parentElement.parentElement.style.borderColor = 'rgba(15, 118, 110, 0.3)';
                }
            });
        }

        // Nút chạy Gán nhanh 1 bước
        document.getElementById('btn-quick-bind-run').addEventListener('click', runQuickBind);

        // Nút Xóa bản đồ
        document.getElementById('btn-clear-map').addEventListener('click', function () {
            if (confirm('LƯU Ý: Thao tác này sẽ xóa sạch toàn bộ sơ đồ hiện tại và giải phóng tất cả sạp. Bạn có chắc chắn muốn xóa hết?')) {
                elements.forEach(el => {
                    if (el.layer) map.removeLayer(el.layer);
                    if (el.labelLayer) map.removeLayer(el.labelLayer);
                });
                elements = [];
                deselectElement();
                loadMapData(); // reload danh sách sạp
            }
        });

        // Nút Lưu sơ đồ lên database
        document.getElementById('btn-save-map').addEventListener('click', function () {
            const dataToSave = {
                elements: elements.map(el => {
                    return {
                        element_type: el.element_type || null,
                        element_name: el.element_name || null,
                        stall_id: el.stall_id || null,
                        // pixel defaults
                        pos_x: el.pos_x || 0,
                        pos_y: el.pos_y || 0,
                        width: el.width || 40,
                        height: el.height || 40,
                        
                        // GPS fields
                        latitude: el.latitude || null,
                        longitude: el.longitude || null,
                        width_m: el.width_m || null,
                        length_m: el.length_m || null,
                        rotation: el.rotation || 0,
                        color: el.color || null,
                        waypoints: el.waypoints ? (typeof el.waypoints === 'string' ? el.waypoints : JSON.stringify(el.waypoints)) : null,
                        stroke_width: el.stroke_width || null
                    };
                })
            };

            $.ajax({
                type: 'POST',
                url: '<?php echo BASE_URL; ?>api/saveMapElements',
                data: JSON.stringify(dataToSave),
                contentType: 'application/json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?php echo security::getToken(); ?>'
                },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 200) {
                        showToast('Lưu bản đồ số thành công!', 'success');
                        loadMapData(); // tải lại dữ liệu để nhận các ID thật từ database
                    } else {
                        showToast('Lưu sơ đồ thất bại: ' + response.message, 'danger');
                    }
                },
                error: function () {
                    showToast('Không thể kết nối máy chủ để lưu sơ đồ.', 'danger');
                }
            });
        });

        // Lắng nghe thay đổi input thuộc tính bên phải
        propName.addEventListener('input', function () {
            if (selectedElement) {
                selectedElement.element_name = propName.value;
                if (selectedElement.element_type === 'stall') {
                    // Update label marker
                    drawElementOnMap(selectedElement);
                }
            }
        });

        // Chọn sạp liên kết
        propStallId.addEventListener('change', function () {
            if (selectedElement && selectedElement.element_type === 'stall') {
                const oldId = selectedElement.stall_id;
                const newId = propStallId.value;

                if (oldId && oldId != newId) {
                    addStallBackToUnmappedList(oldId, selectedElement.stall_code);
                }

                if (newId) {
                    selectedElement.stall_id = newId;
                    const opt = propStallId.options[propStallId.selectedIndex];
                    selectedElement.stall_code = opt.text;
                    selectedElement.element_name = opt.text;
                    removeStallFromUnmappedList(newId);
                } else {
                    selectedElement.stall_id = null;
                    selectedElement.stall_code = null;
                    selectedElement.element_name = 'SẠP';
                }

                drawElementOnMap(selectedElement);
                updateUnmappedStallsBadge();
                updateStallInfoPanel(newId);
            }
        });

        // Dài, Rộng, Góc xoay, Lat, Lng thay đổi
        [propLat, propLng, propWM, propHM, propRotation].forEach(input => {
            input.addEventListener('change', function () {
                if (selectedElement) {
                    selectedElement.latitude = parseFloat(propLat.value) || null;
                    selectedElement.longitude = parseFloat(propLng.value) || null;
                    selectedElement.width_m = parseFloat(propWM.value) || 3.0;
                    selectedElement.length_m = parseFloat(propHM.value) || 3.0;
                    selectedElement.rotation = parseInt(propRotation.value) || 0;

                    drawElementOnMap(selectedElement);
                }
            });
        });

        // Chọn màu sắc
        propColor.addEventListener('input', function () {
            if (selectedElement) {
                selectedElement.color = propColor.value;
                propColorHex.value = propColor.value;
                drawElementOnMap(selectedElement);
            }
        });
        propColorHex.addEventListener('input', function () {
            const hex = propColorHex.value;
            if (/^#[0-9A-F]{6}$/i.test(hex) && selectedElement) {
                selectedElement.color = hex;
                propColor.value = hex;
                drawElementOnMap(selectedElement);
            }
        });

        // Xóa phần tử đang chọn với modal xác nhận tùy chỉnh
        const modalConfirmDelete = document.getElementById('modal-confirm-delete');
        const btnCancelDelete = document.getElementById('btn-cancel-delete');
        const btnConfirmDelete = document.getElementById('btn-confirm-delete');

        document.getElementById('btn-delete-element').addEventListener('click', function () {
            if (!selectedElement) return;
            modalConfirmDelete.style.display = 'flex';
        });

        btnCancelDelete.addEventListener('click', function () {
            modalConfirmDelete.style.display = 'none';
        });

        // Đóng modal khi click ra ngoài vùng card
        modalConfirmDelete.addEventListener('click', function (e) {
            if (e.target === modalConfirmDelete) {
                modalConfirmDelete.style.display = 'none';
            }
        });

        btnConfirmDelete.addEventListener('click', function () {
            if (!selectedElement) {
                modalConfirmDelete.style.display = 'none';
                return;
            }

            if (selectedElement.element_type === 'stall' && selectedElement.stall_id) {
                addStallBackToUnmappedList(selectedElement.stall_id, selectedElement.stall_code);
            }

            // Loại khỏi map layer
            if (selectedElement.layer) map.removeLayer(selectedElement.layer);
            if (selectedElement.labelLayer) map.removeLayer(selectedElement.labelLayer);

            // Loại khỏi elements array
            const idx = elements.indexOf(selectedElement);
            if (idx > -1) elements.splice(idx, 1);

            // Xóa tham chiếu trước khi gọi deselectElement để tránh bị vẽ đè lại
            selectedElement = null;
            deselectElement();
            updateUnmappedStallsBadge();
            
            modalConfirmDelete.style.display = 'none';
            showToast('Đã xóa phần tử thành công!', 'info');
        });

        // Click các nút công cụ vẽ để kích hoạt tool vẽ
        document.querySelectorAll('.draw-tool-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const tool = btn.getAttribute('data-tool');
                
                if (activeTool === tool) {
                    deactivateTools();
                } else {
                    document.querySelectorAll('.draw-tool-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    activeTool = tool;
                    
                    if (tool === 'stall') {
                        showToast('Nhập mã sạp bên Gán Nhanh, sau đó Click một điểm trên map vệ tinh để đặt sạp.', 'info');
                    } else {
                        showToast('Click một điểm trên map vệ tinh để đặt tiện ích.', 'info');
                    }
                }
            });
        });

        // Khởi tạo và đồng bộ bộ lọc Gán nhanh phía trên
        let allStalls = [];
        const qbFilterArea = document.getElementById('qb-filter-area');
        const qbSearchInput = document.getElementById('qb-search-input');
        const qbStallCode = document.getElementById('qb-stall-code');
        const qbToggleMapped = document.getElementById('qb-toggle-mapped');

        if (qbStallCode) {
            // Backup lại danh sách tất cả sạp
            allStalls = Array.from(qbStallCode.options).map(opt => ({
                value: opt.value,
                text: opt.textContent,
                id: opt.getAttribute('data-stall-id') || '',
                area: opt.getAttribute('data-area-name') || '',
                trader: opt.getAttribute('data-trader-name') || ''
            }));
        }

        window.filterQuickBindStalls = function() {
            const selectedArea = qbFilterArea.value.toLowerCase().trim();
            const searchQuery = qbSearchInput.value.toLowerCase().trim();
            const showMappedOnly = qbToggleMapped ? qbToggleMapped.checked : false;
            const currentVal = qbStallCode.value;

            // Danh sách ID sạp đã được vẽ trên bản đồ
            const mappedIds = elements.map(el => String(el.stall_id)).filter(id => id && id !== 'undefined');

            // Clear options cũ trừ dòng placeholder
            qbStallCode.innerHTML = '<option value="">-- Chọn Sạp --</option>';

            // Lọc và thêm lại các option thỏa mãn
            allStalls.forEach(stall => {
                if (stall.value === "") return;

                const matchesArea = !selectedArea || stall.area.toLowerCase() === selectedArea;
                const matchesSearch = !searchQuery || stall.value.toLowerCase().includes(searchQuery) || stall.trader.toLowerCase().includes(searchQuery);
                
                // Trạng thái đã gán hay chưa gán
                const isMapped = mappedIds.includes(String(stall.id));
                const matchesMappingState = showMappedOnly ? isMapped : !isMapped;

                if (matchesArea && matchesSearch && matchesMappingState) {
                    const opt = document.createElement('option');
                    opt.value = stall.value;
                    opt.textContent = stall.text;
                    opt.setAttribute('data-stall-id', stall.id);
                    opt.setAttribute('data-area-name', stall.area);
                    opt.setAttribute('data-trader-name', stall.trader);
                    qbStallCode.appendChild(opt);
                }
            });

            // Gán lại giá trị cũ nếu còn tồn tại trong list
            qbStallCode.value = currentVal;

            // Kích hoạt sự kiện change để đồng bộ trạng thái khóa/mở khóa sạp
            qbStallCode.dispatchEvent(new Event('change'));

            // === Đồng bộ danh sách cột trái #unmapped-stalls-list ===
            const unmappedTitle = document.getElementById('unmapped-title');
            if (unmappedTitle) {
                unmappedTitle.textContent = showMappedOnly ? 'Sạp đã có tọa độ' : 'Sạp chưa có tọa độ';
            }

            const leftListItems = document.querySelectorAll('#unmapped-stalls-list .unmapped-stall-item');
            let visibleCount = 0;
            leftListItems.forEach(item => {
                const stallId = item.getAttribute('data-stall-id');
                const area = (item.getAttribute('data-area-name') || '').toLowerCase();
                const code = (item.getAttribute('data-stall-code') || '').toLowerCase();
                const trader = (item.getAttribute('data-trader-name') || '').toLowerCase();

                const mArea = !selectedArea || area === selectedArea;
                const mSearch = !searchQuery || code.includes(searchQuery) || trader.includes(searchQuery);
                const isMapped = mappedIds.includes(String(stallId));
                const mState = showMappedOnly ? isMapped : !isMapped;

                if (mArea && mSearch && mState) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            const unmappedCountEl = document.getElementById('unmapped-count');
            if (unmappedCountEl) unmappedCountEl.textContent = visibleCount;
        }

        if (qbFilterArea) {
            qbFilterArea.addEventListener('change', filterQuickBindStalls);
        }
        if (qbSearchInput) {
            qbSearchInput.addEventListener('input', filterQuickBindStalls);
        }
        if (qbToggleMapped) {
            qbToggleMapped.addEventListener('change', filterQuickBindStalls);
        }

        // Chạy filter ban đầu (chỉ hiện sạp chưa gán)
        filterQuickBindStalls();

        // ===== BỘ LỌC SẠP TRÊN BẢN ĐỒ =====
        const mapFilterArea = document.getElementById('map-filter-area');
        const mapFilterBusiness = document.getElementById('map-filter-business');
        const btnClearMapFilter = document.getElementById('btn-clear-map-filter');

        function applyMapFilter() {
            const filterArea = mapFilterArea ? mapFilterArea.value : '';
            const filterBusiness = mapFilterBusiness ? mapFilterBusiness.value : '';
            const hasFilter = filterArea || filterBusiness;

            elements.forEach(el => {
                if (el.element_type !== 'stall' || !el.layer) return;

                if (!hasFilter) {
                    // Không có bộ lọc: khôi phục bình thường
                    const statusColors = getStallStatusColor(el.stall_id);
                    const isSelected = (selectedElement === el);
                    el.layer.setStyle({
                        color: isSelected ? '#0f766e' : statusColors.border,
                        fillColor: isSelected ? '#0f766e' : statusColors.fill,
                        fillOpacity: 0.6,
                        opacity: 1,
                        weight: isSelected ? 3 : 1.5
                    });
                    if (el.labelLayer && el.labelLayer._icon) {
                        el.labelLayer._icon.style.opacity = '1';
                    }
                    return;
                }

                // Kiểm tra khớp bộ lọc
                const details = window.DB_STALLS ? window.DB_STALLS.find(s => s.stall_id == el.stall_id) : null;
                let matchesArea = true;
                let matchesBusiness = true;

                if (filterArea && details) {
                    matchesArea = (details.area_name === filterArea);
                } else if (filterArea && !details) {
                    matchesArea = false;
                }

                if (filterBusiness && details) {
                    // Tìm ngành hàng trong area_description (VD: "Tạp hóa, DCGĐ, hàng khô")
                    const desc = (details.area_description || '').toLowerCase();
                    matchesBusiness = desc.includes(filterBusiness.toLowerCase());
                } else if (filterBusiness && !details) {
                    matchesBusiness = false;
                }

                const isMatch = matchesArea && matchesBusiness;

                if (isMatch) {
                    // Sạp khớp: giữ nguyên màu, tăng viền nổi bật
                    const statusColors = getStallStatusColor(el.stall_id);
                    el.layer.setStyle({
                        color: '#0f766e',
                        fillColor: statusColors.fill,
                        fillOpacity: 0.8,
                        opacity: 1,
                        weight: 3
                    });
                    if (el.labelLayer && el.labelLayer._icon) {
                        el.labelLayer._icon.style.opacity = '1';
                    }
                } else {
                    // Sạp không khớp: làm mờ
                    el.layer.setStyle({
                        color: '#d1d5db',
                        fillColor: '#f3f4f6',
                        fillOpacity: 0.15,
                        opacity: 0.3,
                        weight: 0.5
                    });
                    if (el.labelLayer && el.labelLayer._icon) {
                        el.labelLayer._icon.style.opacity = '0.15';
                    }
                }
            });
        }

        const handleFilterChange = function() {
            applyMapFilter();
            const filterArea = mapFilterArea ? mapFilterArea.value : '';
            const filterBusiness = mapFilterBusiness ? mapFilterBusiness.value : '';
            const hasFilter = filterArea || filterBusiness;
            
            const btnAll = document.getElementById('btn-filter-all-admin');
            if (btnAll) {
                if (hasFilter) {
                    btnAll.classList.remove('active');
                } else {
                    btnAll.classList.add('active');
                }
            }
        };

        if (mapFilterArea) mapFilterArea.addEventListener('change', handleFilterChange);
        if (mapFilterBusiness) mapFilterBusiness.addEventListener('change', handleFilterChange);
        if (btnClearMapFilter) {
            btnClearMapFilter.addEventListener('click', function() {
                if (mapFilterArea) mapFilterArea.value = '';
                if (mapFilterBusiness) mapFilterBusiness.value = '';
                applyMapFilter();
                
                // Khôi phục active của nút Tất cả (biểu tượng fa-border-all)
                const btnAll = document.getElementById('btn-filter-all-admin');
                if (btnAll) btnAll.classList.add('active');
            });
        }

        if (qbStallCode) {
            qbStallCode.addEventListener('change', function () {
                const code = qbStallCode.value;
                if (!code) {
                    // Mở khóa các ô nhập khi chưa chọn sạp
                    qbWidth.disabled = false;
                    qbLength.disabled = false;
                    return;
                }
                
                if (window.DB_STALLS) {
                    const details = window.DB_STALLS.find(s => s.stall_code === code);
                    if (details) {
                        // Khóa các ô nhập lại khi đã chọn sạp
                        qbWidth.disabled = true;
                        qbLength.disabled = true;

                        // Tìm xem sạp này đã được vẽ trên bản đồ chưa
                        const existingElement = elements.find(el => el.stall_id == details.stall_id);
                        
                        if (existingElement && existingElement.latitude && existingElement.longitude) {
                            // 1. Di chuyển camera bản đồ đến sạp đó và zoom sát vào
                            map.setView([existingElement.latitude, existingElement.longitude], 21);
                            
                            // 2. Điền tọa độ GPS hiện có vào ô "Dán tọa độ"
                            qbGpsData.value = `${existingElement.latitude.toFixed(6)}, ${existingElement.longitude.toFixed(6)}`;
                            
                            // 3. Lấy kích thước & góc xoay thực tế hiện tại
                            qbWidth.value = existingElement.width_m || 3.0;
                            qbLength.value = existingElement.length_m || 3.0;
                            qbRotation.value = existingElement.rotation || 0;
                            
                            // 4. Chọn sạp này trên sơ đồ để hiển thị thuộc tính
                            if (!window.isSyncingFromMap) {
                                selectElement(existingElement);
                            }
                        } else {
                            // Sạp chưa được gán: xóa panel bên phải và điền ước lượng diện tích
                            deselectElement();
                            
                            if (details.area_size) {
                                const area = parseFloat(details.area_size);
                                qbWidth.value = 3.0;
                                qbLength.value = (area / 3.0).toFixed(1);
                            }
                            qbRotation.value = 0;
                            qbGpsData.value = ""; // Xóa ô nhập tọa độ
                        }
                    }
                }
            });
        }
    }

    // Tắt các công cụ vẽ đang chạy
    function deactivateTools() {
        document.querySelectorAll('.draw-tool-btn').forEach(b => b.classList.remove('active'));
        activeTool = null;
        activePolyline = null;
    }

    // Click vào danh sách cột trái tự điền vào khung Gán Nhanh
    window.selectUnmappedStall = function (code) {
        // Reset bộ lọc gán nhanh để hiện đầy đủ sạp trước khi gán
        if (qbFilterArea) qbFilterArea.value = "";
        if (qbSearchInput) qbSearchInput.value = "";
        if (qbToggleMapped) qbToggleMapped.checked = false;
        if (typeof filterQuickBindStalls === 'function') filterQuickBindStalls();

        qbStallCode.value = code;
        
        // Kích hoạt sự kiện change để tự động lấy diện tích/tọa độ sạp
        qbStallCode.dispatchEvent(new Event('change'));

        // Chuyển tiêu điểm sang ô dán tọa độ GPS
        qbGpsData.focus();
    };

    // Đưa sạp quay lại cột trái chưa gán
    function addStallBackToUnmappedList(stallId, stallCode) {
        const exist = document.querySelector(`.unmapped-stall-item[data-stall-id="${stallId}"]`);
        if (exist) return;

        const list = document.getElementById('unmapped-stalls-list');
        const emptyMsg = list.querySelector('p');
        if (emptyMsg) emptyMsg.remove();

        const div = document.createElement('div');
        div.className = 'unmapped-stall-item';
        div.setAttribute('data-stall-id', stallId);
        div.setAttribute('data-stall-code', stallCode);
        div.onclick = function () { selectUnmappedStall(stallCode); };

        let area = 10;
        if (window.DB_STALLS) {
            const details = window.DB_STALLS.find(s => s.stall_id == stallId);
            if (details && details.area_size) area = details.area_size;
        }

        div.innerHTML = `
            <div>
                <i class="fa-solid fa-store" style="margin-right: 6px; color: var(--primary, #0f766e);"></i>
                <strong style="font-size: 12px;">${stallCode}</strong>
            </div>
            <span style="font-size: 10px; color: var(--text-muted, #64748b);">${area} m²</span>
        `;
        list.appendChild(div);

        // Thêm option vào select liên kết
        const select = document.getElementById('prop-stall-id');
        const opt = document.createElement('option');
        opt.value = stallId;
        opt.textContent = stallCode;
        select.appendChild(opt);

        // Sắp xếp lại option trong select
        sortSelectOptions(select);
    }

    // Xóa sạp khỏi danh sách chưa gán
    function removeStallFromUnmappedList(stallId) {
        const dom = document.querySelector(`.unmapped-stall-item[data-stall-id="${stallId}"]`);
        if (dom) dom.remove();

        const select = document.getElementById('prop-stall-id');
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value == stallId) {
                select.remove(i);
                break;
            }
        }

        // Loại bỏ khỏi danh sách sạp của bộ lọc Gán nhanh phía trên
        if (window.DB_STALLS) {
            const dbStall = window.DB_STALLS.find(s => s.stall_id == stallId);
            if (dbStall && typeof allStalls !== 'undefined') {
                allStalls = allStalls.filter(s => s.value !== dbStall.stall_code);
                if (typeof filterQuickBindStalls === 'function') {
                    filterQuickBindStalls();
                }
            }
        }
    }

    // Cập nhật số lượng sạp chưa gán ở góc
    function updateUnmappedStallsBadge() {
        const count = document.querySelectorAll('#unmapped-stalls-list .unmapped-stall-item').length;
        document.getElementById('unmapped-count').textContent = count;

        const list = document.getElementById('unmapped-stalls-list');
        if (count === 0 && !list.querySelector('p')) {
            list.innerHTML = `<p style="font-size: 12px; color: var(--text-muted, #64748b); text-align: center; margin-top: 10px;">Đã đưa tất cả sạp lên bản đồ!</p>`;
        }
    }

    function sortSelectOptions(select) {
        const tmp = [];
        // Giữ lại option đầu tiên (-- Chọn sạp --)
        const firstOpt = select.options[0];
        
        for (let i = 1; i < select.options.length; i++) {
            tmp.push({
                text: select.options[i].text,
                value: select.options[i].value
            });
        }
        
        tmp.sort((a, b) => a.text.localeCompare(b.text, undefined, {numeric: true}));
        
        select.innerHTML = '';
        select.appendChild(firstOpt);
        
        tmp.forEach(opt => {
            const o = document.createElement('option');
            o.value = opt.value;
            o.textContent = opt.text;
            select.appendChild(o);
        });
    }

    function getIconClass(type) {
        switch (type) {
            case 'security-room': return 'fa-solid fa-shield-halved';
            case 'utility': return 'fa-solid fa-restroom';
            case 'gate': return 'fa-solid fa-archway';
            case 'office': return 'fa-solid fa-building-user';
            default: return 'fa-solid fa-location-dot';
        }
    }

    // Record State (định nghĩa rỗng cho tương thích mã copy cũ hoặc phục vụ undo stack nâng cao)
    function recordState() {}

    function getTypeNameVietnamese(type) {
        switch (type) {
            case 'stall': return 'Sạp Chợ';
            case 'gate': return 'Cổng Chợ';
            case 'door': return 'Cửa Ra Vào';
            case 'utility': return 'Nhà Vệ Sinh / Tiện ích';
            case 'office': return 'Văn Phòng BQL';
            case 'security-room': return 'Phòng Bảo Vệ';
            default: return 'Khác';
        }
    }

    function getStatusName(statusCode) {
        switch (statusCode) {
            case 'rented': return 'Đã thuê';
            case 'empty': return 'Còn trống';
            case 'repairing': return 'Đang bảo trì';
            case 'locked': return 'Tạm khóa';
            default: return 'Khác';
        }
    }

    // Trả về bảng màu viền/nền sạp theo trạng thái hoạt động
    function getStallStatusColor(stallId) {
        const colorMap = {
            rented:    { fill: '#dcfce7', border: '#22c55e' },
            empty:     { fill: '#dbeafe', border: '#3b82f6' },
            repairing: { fill: '#ffedd5', border: '#f97316' },
            locked:    { fill: '#fee2e2', border: '#ef4444' }
        };
        const fallback = { fill: '#f1f5f9', border: '#94a3b8' };

        if (!stallId || !window.DB_STALLS) return fallback;
        const details = window.DB_STALLS.find(s => s.stall_id == stallId);
        if (!details) return fallback;
        return colorMap[details.status_code] || fallback;
    }

    // Khởi động
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditorMap);
    } else {
        initEditorMap();
    }
})();
</script>
