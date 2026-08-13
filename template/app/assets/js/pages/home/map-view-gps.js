(function () {
    // 1. Khai báo các biến bản đồ và trạng thái
    const elements = window.MAP_ELEMENTS || [];
    const market = window.MARKET_DATA || {};
    const baseUrl = window.BASE_URL || '/';

    const marketLat = parseFloat(market.market_latitude) || 15.122174;
    const marketLng = parseFloat(market.market_longitude) || 108.802315;
    const marketZoom = parseInt(market.market_map_zoom) || 19;

    let map;
    let leafElements = []; // Lưu danh sách các layer vẽ trên map để quản lý
    let selectedLayer = null;

    // DOM Elements
    const searchInput = document.getElementById('map-search-input');
    const defaultWaitingMsg = document.getElementById('default-waiting-msg');
    const stallDetailCard = document.getElementById('stall-detail-card');
    const areaSelectorContainer = document.getElementById('area-selector-container');

    // Detail Panel Elements
    const detailCode = document.getElementById('detail-code');
    const detailStatus = document.getElementById('detail-status');
    const detailZone = document.getElementById('detail-zone');
    const detailBusiness = document.getElementById('detail-business');
    const detailTrader = document.getElementById('detail-trader');
    const detailTraderRow = document.getElementById('detail-trader-row');
    const btnOpenGoogleMaps = document.getElementById('btn-open-google-maps');
    const btnRegisterStall = document.getElementById('btn-register-stall');

    // Thuật toán lượng giác: Tính tọa độ 4 góc hình chữ nhật từ tâm GPS và kích thước (mét)
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
            { x: -halfW, y: -halfL }, // Dưới - Trái
            { x: halfW, y: -halfL },  // Dưới - Phải
            { x: halfW, y: halfL },   // Trên - Phải
            { x: -halfW, y: halfL }   // Trên - Trái
        ];

        return localCorners.map(pt => {
            // Xoay tọa độ cục bộ trong mét
            const rx = pt.x * cosT - pt.y * sinT;
            const ry = pt.x * sinT + pt.y * cosT;

            // Chuyển mét sang độ lệch Lat/Lng
            const dLat = (ry / R) * r2d;
            const dLng = (rx / (R * Math.cos(centerLat * d2r))) * r2d;

            return [centerLat + dLat, centerLng + dLng];
        });
    }

    // Trích xuất mã màu theo trạng thái sạp
    function getStatusColors(statusCode) {
        switch (statusCode) {
            case 'rented':
                return { fill: '#22c55e', border: '#15803d' }; // Xanh lá
            case 'empty':
                return { fill: '#3b82f6', border: '#1d4ed8' }; // Xanh dương
            case 'repairing':
                return { fill: '#eab308', border: '#a16207' }; // Vàng
            case 'locked':
                return { fill: '#ef4444', border: '#b91c1c' }; // Đỏ
            default:
                return { fill: '#94a3b8', border: '#475569' }; // Xám
        }
    }

    // Việt hóa tên trạng thái
    function getStatusName(statusCode) {
        switch (statusCode) {
            case 'rented': return 'Đã thuê';
            case 'empty': return 'Còn trống';
            case 'repairing': return 'Đang bảo trì';
            case 'locked': return 'Tạm khóa';
            default: return 'Khác';
        }
    }

    // Trích xuất Icon tương ứng cho Utility
    function getIconForUtility(type) {
        switch (type) {
            case 'security-room': return '<i class="fa-solid fa-shield-halved"></i>';
            case 'utility': return '<i class="fa-solid fa-restroom"></i>';
            case 'gate': return '<i class="fa-solid fa-archway"></i>';
            case 'office': return '<i class="fa-solid fa-building-user"></i>';
            default: return '<i class="fa-solid fa-location-dot"></i>';
        }
    }

    // 2. Khởi tạo bản đồ số
    function initMap() {
        // Tạo thực thể Leaflet map, tắt các control mặc định để dùng control tự chế
        map = L.map('map-canvas-gps', {
            zoomControl: false,
            attributionControl: false
        }).setView([marketLat, marketLng], marketZoom);

        window.map = map; // Gán toàn cục để floating controls gọi

        // Load tile bản đồ nền Light Gray (sạch sẽ, thanh lịch giống ví dụ)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 22,
            maxNativeZoom: 20
        }).addTo(map);

        // Vẽ các phần tử lên bản đồ
        renderMapElements();

        // Tạo bộ chọn phân khu bên phải
        renderAreaSelector();

        // Cài đặt bộ tìm kiếm sạp
        setupSearch();

        // Cài đặt các nút phóng to, thu nhỏ, căn giữa bên ngoài
        const btnZoomIn = document.getElementById('btn-zoom-in-front');
        const btnZoomOut = document.getElementById('btn-zoom-out-front');
        const btnResetMap = document.getElementById('btn-reset-map-front');
        
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
                resetMapView();
            });
        }

        const btnFilterAllFront = document.getElementById('btn-filter-all-front');
        if (btnFilterAllFront) {
            btnFilterAllFront.addEventListener('click', function() {
                document.querySelectorAll('.selector-btn-gps').forEach(b => b.classList.remove('active'));
                btnFilterAllFront.classList.add('active');
                filterByArea('all');
            });
        }

        // ===== BỘ LỌC NHANH SẠP TRÊN BẢN ĐỒ (ĐỒNG BỘ TỪ ADMIN) =====
        const mapFilterArea = document.getElementById('map-filter-area-front');
        const mapFilterBusiness = document.getElementById('map-filter-business-front');
        const btnClearMapFilter = document.getElementById('btn-clear-map-filter-front');

        function applyMapFilter() {
            const filterArea = mapFilterArea ? mapFilterArea.value : '';
            const filterBusiness = mapFilterBusiness ? mapFilterBusiness.value : '';
            const hasFilter = filterArea || filterBusiness;

            if (hasFilter) {
                // Đưa bản đồ về hiển thị tất cả các lớp trước khi áp dụng làm mờ
                leafElements.forEach(el => {
                    if (map && !map.hasLayer(el.layer)) {
                        map.addLayer(el.layer);
                    }
                });

                // Bỏ active của các nút phân khu ở cột bên trái
                document.querySelectorAll('.selector-btn-gps').forEach(b => b.classList.remove('active'));
            }

            leafElements.forEach(el => {
                const item = elements.find(item => item.element_id === el.id);
                if (!item || item.element_type !== 'stall' || el.type === 'label') return;

                if (!hasFilter) {
                    // Không có bộ lọc: khôi phục bình thường
                    const colors = getStatusColors(item.status_code);
                    const isSelected = (selectedLayer === el.layer);
                    el.layer.setStyle({
                        color: isSelected ? '#0f766e' : colors.border,
                        fillColor: isSelected ? '#0f766e' : colors.fill,
                        fillOpacity: 0.65,
                        opacity: 1,
                        weight: isSelected ? 3 : 1.5
                    });
                    
                    const labelEl = leafElements.find(le => le.id === el.id && le.type === 'label');
                    if (labelEl && labelEl.layer && labelEl.layer._icon) {
                        labelEl.layer._icon.style.opacity = '1';
                    }
                    return;
                }

                // Kiểm tra khớp bộ lọc
                let matchesArea = true;
                let matchesBusiness = true;

                if (filterArea) {
                    matchesArea = (item.area_name === filterArea);
                }

                if (filterBusiness) {
                    const businessName = (item.business_line_name || '').toLowerCase();
                    const desc = (item.area_description || '').toLowerCase();
                    matchesBusiness = businessName.includes(filterBusiness.toLowerCase()) || desc.includes(filterBusiness.toLowerCase());
                }

                const isMatch = matchesArea && matchesBusiness;

                if (isMatch) {
                    // Sạp khớp: giữ nguyên màu sạp, tăng viền nổi bật
                    const colors = getStatusColors(item.status_code);
                    el.layer.setStyle({
                        color: '#0f766e',
                        fillColor: colors.fill,
                        fillOpacity: 0.85,
                        opacity: 1,
                        weight: 3
                    });
                    const labelEl = leafElements.find(le => le.id === el.id && le.type === 'label');
                    if (labelEl && labelEl.layer && labelEl.layer._icon) {
                        labelEl.layer._icon.style.opacity = '1';
                    }
                } else {
                    // Sạp không khớp: làm mờ hẳn đi
                    el.layer.setStyle({
                        color: '#d1d5db',
                        fillColor: '#f3f4f6',
                        fillOpacity: 0.15,
                        opacity: 0.3,
                        weight: 0.5
                    });
                    const labelEl = leafElements.find(le => le.id === el.id && le.type === 'label');
                    if (labelEl && labelEl.layer && labelEl.layer._icon) {
                        labelEl.layer._icon.style.opacity = '0.15';
                    }
                }
            });
        }

        const handleFilterChange = function() {
            applyMapFilter();
            const filterArea = mapFilterArea ? mapFilterArea.value : '';
            const filterBusiness = mapFilterBusiness ? mapFilterBusiness.value : '';
            const hasFilter = filterArea || filterBusiness;
            
            const btnAll = document.getElementById('btn-filter-all-front');
            if (btnAll) {
                if (hasFilter) {
                    btnAll.classList.remove('active');
                } else {
                    document.querySelectorAll('.selector-btn-gps').forEach(b => b.classList.remove('active'));
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

                // Khôi phục nút "Tất cả" của bộ lọc phân khu cũ thành active
                const btnAll = document.querySelector('.selector-btn-gps.control-btn-gps');
                if (btnAll) {
                    document.querySelectorAll('.selector-btn-gps').forEach(b => b.classList.remove('active'));
                    btnAll.classList.add('active');
                }
            });
        }
    }

    // 3. Vẽ tất cả phần tử sạp / đường đi / tiện ích lên bản đồ
    function renderMapElements() {
        // Xóa các phần tử cũ nếu có
        leafElements.forEach(el => map.removeLayer(el.layer));
        leafElements = [];

        elements.forEach(item => {
            let layer = null;
            let center = null;

            // PHƯƠNG ÁN 1: Vẽ sạp dạng đa giác từ tâm Lat/Lng + Dài/Rộng (mét) + Góc xoay
            if (item.element_type === 'stall') {
                const lat = parseFloat(item.element_latitude);
                const lng = parseFloat(item.element_longitude);
                const wM = parseFloat(item.element_width_m) || 3.0;
                const hM = parseFloat(item.element_length_m) || 3.0;
                const rot = parseInt(item.element_rotation) || 0;

                if (!isNaN(lat) && !isNaN(lng)) {
                    // Tính 4 góc hình chữ nhật sạp
                    const corners = calculateRectVertices(lat, lng, wM, hM, rot);
                    center = [lat, lng];

                    const colors = getStatusColors(item.status_code);

                    // Vẽ Polygon sạp
                    layer = L.polygon(corners, {
                        color: colors.border,
                        fillColor: colors.fill,
                        fillOpacity: 0.65,
                        weight: 1.5,
                        className: `stall-polygon stall-${item.stall_code}`
                    }).addTo(map);

                    // Hiển thị Nhãn chữ (Stall code) ở tâm sạp
                    const labelIcon = L.divIcon({
                        className: 'leaflet-stall-label',
                        html: item.stall_code || 'SẠP',
                        iconSize: [40, 16],
                        iconAnchor: [20, 8]
                    });
                    
                    const labelMarker = L.marker(center, {
                        icon: labelIcon,
                        interactive: false // Bỏ tương tác chuột để tránh đè click vào Polygon
                    }).addTo(map);

                    // Lưu nhãn đi kèm để sau này dễ xóa/ẩn cùng sạp
                    leafElements.push({
                        id: item.element_id,
                        code: item.stall_code,
                        type: 'label',
                        layer: labelMarker,
                        areaId: item.stall_area_id
                    });

                    // Bắt sự kiện click vào Polygon sạp
                    layer.on('click', function (e) {
                        L.DomEvent.stopPropagation(e);
                        selectStall(item, center, layer);
                    });
                }
            }
            // Vẽ đường đi hoặc hàng rào từ danh sách mốc tọa độ GPS
            else if (item.element_type === 'street' || item.element_type === 'fence') {
                let coords = [];
                if (item.element_coords_gps) {
                    try {
                        const parsed = typeof item.element_coords_gps === 'string' ? JSON.parse(item.element_coords_gps) : item.element_coords_gps;
                        coords = parsed.map(pt => [parseFloat(pt.lat), parseFloat(pt.lng)]);
                    } catch (e) {
                        coords = [];
                    }
                }

                if (coords.length > 1) {
                    const isFence = item.element_type === 'fence';
                    layer = L.polyline(coords, {
                        color: isFence ? '#94a3b8' : '#cbd5e1',
                        weight: item.stroke_width ? item.stroke_width * 0.15 : (isFence ? 4 : 8), // Quy đổi pixel sang tỷ lệ vẽ
                        dashArray: isFence ? '5, 5' : null,
                        opacity: 0.8
                    }).addTo(map);
                }
            }
            // Vẽ các biểu tượng tiện ích (WC, Cổng, Phòng bảo vệ)
            else if (['security-room', 'utility', 'gate', 'office'].includes(item.element_type)) {
                const lat = parseFloat(item.element_latitude);
                const lng = parseFloat(item.element_longitude);

                if (!isNaN(lat) && !isNaN(lng)) {
                    center = [lat, lng];
                    
                    const iconClass = `category-item-gps cat-${item.element_type === 'security-room' ? 'security' : item.element_type}`;
                    const customHtml = `<div class="${iconClass}" style="padding:0; border:none; background:none; transform:scale(1.1);"><i style="width:26px; height:26px; font-size:11px;">${getIconForUtility(item.element_type)}</i></div>`;

                    const utilityIcon = L.divIcon({
                        className: 'leaflet-utility-icon',
                        html: customHtml,
                        iconSize: [26, 26],
                        iconAnchor: [13, 13]
                    });

                    layer = L.marker(center, { icon: utilityIcon }).addTo(map);
                    
                    // Tạo tooltip nhỏ trên marker tiện ích
                    const title = item.element_name || getTypeNameVietnamese(item.element_type);
                    layer.bindTooltip(title, {
                        direction: 'top',
                        offset: [0, -10]
                    });
                }
            }

            if (layer) {
                leafElements.push({
                    id: item.element_id,
                    code: item.stall_code || '',
                    type: item.element_type,
                    layer: layer,
                    areaId: item.stall_area_id || null,
                    center: center
                });
            }
        });
    }

    // 4. Chọn sạp và hiển thị thông tin chi tiết trên Sidebar
    function selectStall(item, center, layer) {
        // Hoàn tác highlight đa giác cũ
        if (selectedLayer && typeof selectedLayer.setStyle === 'function') {
            const oldColors = getStatusColors(selectedLayer._stallStatus);
            selectedLayer.setStyle({
                color: oldColors.border,
                fillOpacity: 0.65,
                weight: 1.5
            });
        }

        // Highlight đa giác mới
        selectedLayer = layer;
        selectedLayer._stallStatus = item.status_code; // Lưu tạm trạng thái
        selectedLayer.setStyle({
            color: '#0f766e', // Màu Teal thương hiệu làm nổi bật viền
            fillOpacity: 0.85,
            weight: 3
        });

        // Ẩn tin nhắn chờ, hiển thị thẻ thông tin
        defaultWaitingMsg.style.display = 'none';
        stallDetailCard.style.display = 'block';

        // Điền thông tin sạp
        detailCode.textContent = item.stall_code;
        
        const statusName = getStatusName(item.status_code);
        detailStatus.className = `badge-gps ${item.status_code}`;
        detailStatus.textContent = statusName;

        const zoneName = `${item.area_name || 'Chưa phân khu'} ${item.area_block ? '- Dãy ' + item.area_block : ''} ${item.area_lot ? '- Lô ' + item.area_lot : ''}`;
        detailZone.textContent = zoneName;

        // Ngành hàng thực tế của sạp
        let businessName = item.business_line_name;
        if (!businessName && item.area_description) {
            const match = item.area_description.match(/^([^(]+)/);
            businessName = match ? match[1].trim() : item.area_description;
        }
        detailBusiness.textContent = businessName || 'Chưa xác định';

        if (item.status_code === 'rented' && item.trader_name) {
            detailTraderRow.style.display = 'flex';
            detailTrader.textContent = item.trader_name;
        } else {
            detailTraderRow.style.display = 'none';
        }

        // Cập nhật link liên kết sang Google Maps chính thức để xem vệ tinh/chỉ đường
        btnOpenGoogleMaps.href = `https://www.google.com/maps/search/?api=1&query=${center[0]},${center[1]}`;

        // Nút đăng ký nếu sạp trống
        if (item.status_code === 'empty') {
            btnRegisterStall.style.display = 'flex';
            btnRegisterStall.href = baseUrl + `home/register?stall_code=${encodeURIComponent(item.stall_code)}&area=${item.area_size}`;
        } else {
            btnRegisterStall.style.display = 'none';
        }

        // Di chuyển camera camera đến trung tâm sạp
        map.panTo(center);
    }

    // 5. Cấu hình tìm kiếm định vị sạp
    function setupSearch() {
        function executeSearch() {
            const query = searchInput.value.trim().toUpperCase();
            if (!query) return;

            // Tìm phần tử sạp khớp mã
            const found = leafElements.find(el => 
                el.type === 'stall' && 
                el.code.toUpperCase().includes(query)
            );

            if (found && found.center) {
                // Giả lập click để chọn sạp
                const item = elements.find(it => it.element_id === found.id);
                if (item) {
                    selectStall(item, found.center, found.layer);
                }
            } else {
                alert(`Không tìm thấy sạp nào khớp với mã "${query}"`);
            }
        }

        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                executeSearch();
            }
        });
    }

    // 6. Xây dựng Bộ lọc phân khu (Area Selector) góc trên phải map
    function renderAreaSelector() {
        // Lấy danh sách duy nhất các khu vực
        const uniqueAreas = [];
        const checkedIds = new Set();

        elements.forEach(item => {
            if (item.stall_area_id && !checkedIds.has(item.stall_area_id)) {
                checkedIds.add(item.stall_area_id);
                uniqueAreas.push({
                    id: item.stall_area_id,
                    name: item.area_name || 'Khu vực'
                });
            }
        });

        // Thêm nút bấm động vào container
        uniqueAreas.forEach(area => {
            const btn = document.createElement('button');
            btn.className = 'selector-btn-gps';
            btn.textContent = area.name;
            btn.onclick = function () {
                // Active class toggle
                document.querySelectorAll('.selector-btn-gps').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                filterByArea(area.id);
            };
            areaSelectorContainer.appendChild(btn);
        });
    }

    // Lọc hiển thị và zoom vừa khít phân khu
    window.filterByArea = function (areaId) {
        // Nếu chọn phân khu, tự động reset các ô chọn bộ lọc nhanh ở legend để tránh xung đột
        const mapFilterArea = document.getElementById('map-filter-area-front');
        const mapFilterBusiness = document.getElementById('map-filter-business-front');
        if (mapFilterArea) mapFilterArea.value = '';
        if (mapFilterBusiness) mapFilterBusiness.value = '';

        let targets = [];

        leafElements.forEach(el => {
            // Khôi phục lại hiển thị mặc định của sạp (bỏ mờ do bộ lọc nhanh cũ nếu có)
            const item = elements.find(item => item.element_id === el.id);
            if (item && item.element_type === 'stall' && el.type !== 'label') {
                const colors = getStatusColors(item.status_code);
                const isSelected = (selectedLayer === el.layer);
                el.layer.setStyle({
                    color: isSelected ? '#0f766e' : colors.border,
                    fillColor: isSelected ? '#0f766e' : colors.fill,
                    fillOpacity: 0.65,
                    opacity: 1,
                    weight: isSelected ? 3 : 1.5
                });
                
                const labelEl = leafElements.find(le => le.id === el.id && le.type === 'label');
                if (labelEl && labelEl.layer && labelEl.layer._icon) {
                    labelEl.layer._icon.style.opacity = '1';
                }
            }

            if (areaId === 'all') {
                if (map && !map.hasLayer(el.layer)) map.addLayer(el.layer);
                if (el.center) targets.push(el.center);
            } else {
                if (el.areaId === areaId) {
                    if (map && !map.hasLayer(el.layer)) map.addLayer(el.layer);
                    if (el.center) targets.push(el.center);
                } else {
                    if (map) map.removeLayer(el.layer);
                }
            }
        });

        // Zoom vừa khít các sạp được lọc
        if (targets.length > 0) {
            const bounds = L.latLngBounds(targets);
            map.fitBounds(bounds, { padding: [40, 40], maxZoom: 20 });
        } else {
            resetMapView();
        }
    };

    // Định vị nhanh từ các liên kết bên ngoài bản đồ (ví dụ danh sách sạp trống bên dưới)
    window.locateStallByCode = function (stallCode) {
        const found = leafElements.find(el => el.type === 'stall' && el.code === stallCode);
        if (found && found.center) {
            const item = elements.find(it => it.element_id === found.id);
            if (item) {
                selectStall(item, found.center, found.layer);
                // Cuộn mượt màn hình lên đầu bản đồ
                document.querySelector('.map-dashboard-container').scrollIntoView({ behavior: 'smooth' });
            }
        }
    };

    // Định vị nhanh danh mục tiện ích công cộng
    window.focusOnUtility = function (type) {
        const founds = leafElements.filter(el => el.type === type);
        if (founds.length > 0) {
            const coords = founds.map(f => f.center);
            const bounds = L.latLngBounds(coords);
            map.fitBounds(bounds, { padding: [50, 50], maxZoom: 21 });
        } else {
            alert('Chưa có thông tin định vị GPS cho tiện ích này.');
        }
    };

    // Căn camera về trung tâm mặc định của Chợ
    window.resetMapView = function () {
        map.setView([marketLat, marketLng], marketZoom);
        
        // Ẩn chi tiết sạp, hiện lại tin nhắn chờ
        stallDetailCard.style.display = 'none';
        defaultWaitingMsg.style.display = 'block';

        if (selectedLayer && typeof selectedLayer.setStyle === 'function') {
            const oldColors = getStatusColors(selectedLayer._stallStatus);
            selectedLayer.setStyle({
                color: oldColors.border,
                fillOpacity: 0.65,
                weight: 1.5
            });
            selectedLayer = null;
        }
    };

    function getTypeNameVietnamese(type) {
        switch (type) {
            case 'street': return 'Đường đi';
            case 'fence': return 'Hàng rào';
            case 'security-room': return 'Phòng bảo vệ';
            case 'gate': return 'Cổng chợ';
            case 'utility': return 'Nhà vệ sinh (WC)';
            case 'office': return 'Văn phòng BQL';
            default: return 'Khác';
        }
    }

    // Khởi động
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
    }
})();
