(function () {
    // 1. Khai báo các biến bản đồ và trạng thái
    const elements = window.MAP_ELEMENTS || [];
    const market = window.MARKET_DATA || {};
    const allMarkets = window.ALL_MARKETS || [];
    const activeMarketId = parseInt(window.ACTIVE_MARKET_ID) || 0;
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
    const detailAreaSize = document.getElementById('detail-area-size');
    const detailPrice = document.getElementById('detail-price');
    const detailPriceRow = document.getElementById('detail-price-row');
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

    // Bảng màu nhận diện cho từng Chợ
    const MARKET_THEMES = [
        { stroke: '#0284c7', fill: '#38bdf8', tagBg: '#0369a1' }, // Xanh lam
        { stroke: '#7c3aed', fill: '#a78bfa', tagBg: '#6d28d9' }, // Tím
        { stroke: '#ea580c', fill: '#fb923c', tagBg: '#c2410c' }, // Cam
        { stroke: '#059669', fill: '#34d399', tagBg: '#047857' }  // Lục
    ];

    // 1. Thuật toán tìm Bao Lồi (Convex Hull) từ tập hợp tọa độ sạp thực tế
    function computeConvexHull(points) {
        if (!points || points.length <= 2) return points || [];
        const uniqueMap = new Map();
        points.forEach(p => {
            const key = p[0].toFixed(7) + ',' + p[1].toFixed(7);
            if (!uniqueMap.has(key)) uniqueMap.set(key, p);
        });
        const uniquePts = Array.from(uniqueMap.values());
        if (uniquePts.length <= 2) return uniquePts;

        const sorted = uniquePts.slice().sort((a, b) => a[1] === b[1] ? a[0] - b[0] : a[1] - b[1]);
        function crossProduct(o, a, b) {
            return (a[1] - o[1]) * (b[0] - o[0]) - (a[0] - o[0]) * (b[1] - o[1]);
        }

        const lower = [];
        for (let i = 0; i < sorted.length; i++) {
            while (lower.length >= 2 && crossProduct(lower[lower.length - 2], lower[lower.length - 1], sorted[i]) <= 0) {
                lower.pop();
            }
            lower.push(sorted[i]);
        }

        const upper = [];
        for (let i = sorted.length - 1; i >= 0; i--) {
            while (upper.length >= 2 && crossProduct(upper[upper.length - 2], upper[upper.length - 1], sorted[i]) <= 0) {
                upper.pop();
            }
            upper.push(sorted[i]);
        }

        lower.pop();
        upper.pop();
        return lower.concat(upper);
    }

    // 2. Các hàm hình học hỗ trợ né chéo và chống chồng lấn giữa các chợ
    function latLngDistMeters(p1, p2) {
        const R = 6378137;
        const d2r = Math.PI / 180;
        const dLat = (p2[0] - p1[0]) * d2r;
        const dLng = (p2[1] - p1[1]) * d2r;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(p1[0] * d2r) * Math.cos(p2[0] * d2r) *
                  Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function projectPointToSegment(p, a, b) {
        const dx = b[1] - a[1];
        const dy = b[0] - a[0];
        const lenSq = dx * dx + dy * dy;
        if (lenSq === 0) return { proj: a, t: 0 };

        let t = ((p[1] - a[1]) * dx + (p[0] - a[0]) * dy) / lenSq;
        t = Math.max(0, Math.min(1, t));

        return {
            proj: [a[0] + t * dy, a[1] + t * dx],
            t: t
        };
    }

    function isPointInPolygon(pt, poly) {
        if (!poly || poly.length < 3) return false;
        let inside = false;
        const n = poly.length;
        for (let i = 0, j = n - 1; i < n; j = i++) {
            const xi = poly[i][1], yi = poly[i][0];
            const xj = poly[j][1], yj = poly[j][0];

            const intersect = ((yi > pt[0]) !== (yj > pt[0])) &&
                (pt[1] < (xj - xi) * (pt[0] - yi) / (yj - yi) + xi);
            if (intersect) inside = !inside;
        }
        return inside;
    }

    // Tự động bẻ góc viền bao để né các sạp thuộc Chợ khác (không để chồng lấn)
    function adaptPolygonAvoidObstacles(hull, obstacles, centroid) {
        if (!hull || hull.length < 3 || !obstacles || obstacles.length === 0) return hull || [];

        const R = 6378137;
        const d2r = Math.PI / 180;
        const r2d = 180 / Math.PI;

        const cLat = centroid[0];
        const cLng = centroid[1];

        const degPerMeterLat = r2d / R;
        const degPerMeterLng = r2d / (R * Math.cos(cLat * d2r));

        const n = hull.length;
        const edgeObsMap = {};

        // Phân loại sạp chợ khác vào cạnh gần nhất
        obstacles.forEach(obs => {
            let bestDist = Infinity;
            let bestEdge = -1;
            let bestProj = null;
            let bestT = 0;

            for (let i = 0; i < n; i++) {
                const A = hull[i];
                const B = hull[(i + 1) % n];
                const res = projectPointToSegment(obs, A, B);
                const dist = latLngDistMeters(obs, res.proj);

                if (dist < bestDist) {
                    bestDist = dist;
                    bestEdge = i;
                    bestProj = res.proj;
                    bestT = res.t;
                }
            }

            const isInside = isPointInPolygon(obs, hull);

            // Nếu sạp chợ khác nằm trong polygon hoặc cách viền < 35m -> Bẻ góc né vào trong
            if (isInside || bestDist < 35) {
                if (!edgeObsMap[bestEdge]) edgeObsMap[bestEdge] = [];
                edgeObsMap[bestEdge].push({
                    obs: obs,
                    proj: bestProj,
                    t: bestT,
                    dist: bestDist,
                    isInside: isInside
                });
            }
        });

        const newHull = [];

        for (let i = 0; i < n; i++) {
            const A = hull[i];
            const B = hull[(i + 1) % n];

            newHull.push(A);

            if (!edgeObsMap[i] || edgeObsMap[i].length === 0) continue;

            const edgeObs = edgeObsMap[i];
            edgeObs.sort((a, b) => a.t - b.t);

            const dLat = B[0] - A[0];
            const dLng = B[1] - A[1];
            const edgeLen = Math.sqrt(dLat * dLat + dLng * dLng);
            if (edgeLen === 0) continue;

            const uLat = dLat / edgeLen;
            const uLng = dLng / edgeLen;

            // Vector pháp tuyến hướng vào tâm
            let n1Lat = -uLng * (degPerMeterLat / degPerMeterLng);
            let n1Lng = uLat * (degPerMeterLng / degPerMeterLat);
            const nLen = Math.sqrt(n1Lat * n1Lat + n1Lng * n1Lng);
            n1Lat /= nLen;
            n1Lng /= nLen;

            const midLat = (A[0] + B[0]) / 2;
            const midLng = (A[1] + B[1]) / 2;
            if ((n1Lat * (cLat - midLat) + n1Lng * (cLng - midLng)) < 0) {
                n1Lat = -n1Lat;
                n1Lng = -n1Lng;
            }

            edgeObs.forEach(item => {
                const obs = item.obs;
                const proj = item.proj;

                const clearanceMeters = 25; // Né cách sạp chợ khác 25m an toàn
                const alongMeters = 32;     // Độ rộng 32m dọc theo cạnh

                const clearanceLat = clearanceMeters * degPerMeterLat;
                const clearanceLng = clearanceMeters * degPerMeterLng;
                const alongLat = alongMeters * degPerMeterLat;
                const alongLng = alongMeters * degPerMeterLng;

                const K_left = [
                    proj[0] - alongLat * uLat,
                    proj[1] - alongLng * uLng
                ];

                const obsOffsetLat = (obs[0] - proj[0]);
                const obsOffsetLng = (obs[1] - proj[1]);
                const obsDotN = (obsOffsetLat * n1Lat + obsOffsetLng * n1Lng);

                const depthLat = Math.max(obsDotN + clearanceLat, clearanceLat);
                const depthLng = Math.max(obsDotN + clearanceLng, clearanceLng);

                const K1 = [
                    K_left[0] + depthLat * n1Lat,
                    K_left[1] + depthLng * n1Lng
                ];

                const K_right = [
                    proj[0] + alongLat * uLat,
                    proj[1] + alongLng * uLng
                ];

                const K2 = [
                    K_right[0] + depthLat * n1Lat,
                    K_right[1] + depthLng * n1Lng
                ];

                newHull.push(K_left);
                newHull.push(K1);
                newHull.push(K2);
                newHull.push(K_right);
            });
        }

        return newHull;
    }

    // 3. Mở rộng viền bao quanh sạp ~8m
    function expandPolygonBuffer(hullCoords, bufferMeters = 8) {
        if (!hullCoords || hullCoords.length < 3) return hullCoords || [];
        const R = 6378137;
        const d2r = Math.PI / 180;
        const r2d = 180 / Math.PI;

        let sumLat = 0, sumLng = 0;
        hullCoords.forEach(pt => {
            sumLat += pt[0];
            sumLng += pt[1];
        });
        const cLat = sumLat / hullCoords.length;
        const cLng = sumLng / hullCoords.length;

        return hullCoords.map(pt => {
            const vLat = pt[0] - cLat;
            const vLng = pt[1] - cLng;
            const dist = Math.sqrt(vLat * vLat + vLng * vLng);

            if (dist > 0) {
                const offsetLat = (bufferMeters / R) * r2d;
                const offsetLng = (bufferMeters / (R * Math.cos(cLat * d2r))) * r2d;
                return [pt[0] + (vLat / dist) * offsetLat, pt[1] + (vLng / dist) * offsetLng];
            } else {
                const offsetLat = (bufferMeters / R) * r2d;
                return [pt[0] + offsetLat, pt[1]];
            }
        });
    }

    // 4. Vẽ viền bao quanh (Tự động thích ứng né sạp chợ khác - 1 bao độc lập cho từng Chợ)
    let activeBoundaryLayers = [];
    function renderMarketBoundaries() {
        if (!map) return;
        activeBoundaryLayers.forEach(l => map.removeLayer(l));
        activeBoundaryLayers = [];

        if (!allMarkets || allMarkets.length === 0) return;

        allMarkets.forEach((m, idx) => {
            const theme = MARKET_THEMES[idx % MARKET_THEMES.length];
            const pts = [];

            // Lấy toàn bộ sạp và tiện ích thuộc Chợ này
            const marketElements = elements.filter(el => {
                const mid = parseInt(el.element_market_id || el.market_id || el.area_market_id || 0);
                return mid === parseInt(m.market_id);
            });

            // Lấy tọa độ các sạp thuộc các CHỢ KHÁC để làm vật cản cần né
            const otherMarketPoints = [];
            elements.forEach(el => {
                const mid = parseInt(el.element_market_id || el.market_id || el.area_market_id || 0);
                if (mid > 0 && mid !== parseInt(m.market_id)) {
                    const oLat = parseFloat(el.element_latitude || el.latitude || el.stall_latitude);
                    const oLng = parseFloat(el.element_longitude || el.longitude || el.stall_longitude);
                    if (!isNaN(oLat) && !isNaN(oLng)) {
                        otherMarketPoints.push([oLat, oLng]);
                    }
                }
            });

            marketElements.forEach(el => {
                const rawLat = el.element_latitude || el.latitude || el.stall_latitude;
                const rawLng = el.element_longitude || el.longitude || el.stall_longitude;

                if (rawLat && rawLng) {
                    const lat = parseFloat(rawLat);
                    const lng = parseFloat(rawLng);
                    if (!isNaN(lat) && !isNaN(lng)) {
                        const wm = parseFloat(el.element_width_m || el.width_m) || 3.0;
                        const lm = parseFloat(el.element_length_m || el.length_m) || 3.0;
                        const rot = parseFloat(el.element_rotation || el.rotation) || 0;
                        const corners = calculateRectVertices(lat, lng, wm, lm, rot);
                        corners.forEach(c => pts.push(c));
                    }
                }
            });

            const mLat = parseFloat(m.market_latitude) || 15.122174;
            const mLng = parseFloat(m.market_longitude) || 108.802315;

            let topLat = mLat;
            let topLng = mLng;

            if (pts.length >= 3) {
                // 1. Tạo bao lồi cơ sở
                const rawHull = computeConvexHull(pts);

                // 2. Tính trọng tâm chợ
                let sumLat = 0, sumLng = 0;
                pts.forEach(p => { sumLat += p[0]; sumLng += p[1]; });
                const centroid = [sumLat / pts.length, sumLng / pts.length];

                // 3. Tự động bẻ góc né các sạp chợ khác
                const adaptedHull = adaptPolygonAvoidObstacles(rawHull, otherMarketPoints, centroid);

                // 4. Mở rộng đệm viền ngoài
                const coords = expandPolygonBuffer(adaptedHull, 8);

                const poly = L.polygon(coords, {
                    color: theme.stroke,
                    weight: 2,
                    dashArray: '6, 6',
                    fillColor: theme.fill,
                    fillOpacity: 0.08,
                    smoothFactor: 1.2,
                    interactive: false
                }).addTo(map);
                poly.bringToBack();
                activeBoundaryLayers.push(poly);

                topLat = coords[0][0];
                topLng = coords[0][1];
                coords.forEach(pt => {
                    if (pt[0] > topLat) {
                        topLat = pt[0];
                        topLng = pt[1];
                    }
                });
            } else if (pts.length > 0) {
                let avgLat = 0, avgLng = 0;
                pts.forEach(p => { avgLat += p[0]; avgLng += p[1]; });
                avgLat /= pts.length;
                avgLng /= pts.length;

                const circ = L.circle([avgLat, avgLng], {
                    radius: 20,
                    color: theme.stroke,
                    weight: 2,
                    dashArray: '6, 6',
                    fillColor: theme.fill,
                    fillOpacity: 0.08,
                    interactive: false
                }).addTo(map);
                circ.bringToBack();
                activeBoundaryLayers.push(circ);

                topLat = avgLat + (20 / 6378137) * (180 / Math.PI);
                topLng = avgLng;
            } else if (m.market_latitude && m.market_longitude) {
                const circ = L.circle([mLat, mLng], {
                    radius: 25,
                    color: theme.stroke,
                    weight: 2,
                    dashArray: '6, 6',
                    fillColor: theme.fill,
                    fillOpacity: 0.08,
                    interactive: false
                }).addTo(map);
                circ.bringToBack();
                activeBoundaryLayers.push(circ);

                topLat = mLat + (25 / 6378137) * (180 / Math.PI);
                topLng = mLng;
            }

            // 1 Nhãn tên chợ duy nhất đặt ở đỉnh bao
            const tagIcon = L.divIcon({
                className: 'market-boundary-tag',
                html: `<div style="background: rgba(255, 255, 255, 0.96); backdrop-filter: blur(8px); border: 1.5px solid ${theme.stroke}; color: ${theme.tagBg}; padding: 3px 12px; border-radius: 14px; font-weight: 700; font-size: 11.5px; box-shadow: 0 3px 10px rgba(0,0,0,0.12); display: inline-flex; align-items: center; gap: 6px; cursor: pointer; white-space: nowrap; transform: translate(-50%, -100%); transition: all 0.2s;">
                         <span style="width: 8px; height: 8px; border-radius: 50%; background: ${theme.stroke}; display: inline-block;"></span>
                         <span>${m.market_name}</span>
                       </div>`,
                iconSize: [0, 0]
            });

            const tagMarker = L.marker([topLat, topLng], { icon: tagIcon }).addTo(map);
            tagMarker.on('click', function () {
                switchMarketFocus(m.market_id);
            });
            activeBoundaryLayers.push(tagMarker);
        });
    }

    // 4. Khởi tạo bản đồ số
    function initMap() {
        // Tạo thực thể Leaflet map, truyền sẵn center & zoom để Leaflet khởi tạo chuẩn xác
        map = L.map('map-canvas-gps', {
            center: [marketLat, marketLng],
            zoom: marketZoom,
            zoomControl: false,
            attributionControl: false
        });

        window.map = map; // Gán toàn cục để floating controls gọi

        // Load tile bản đồ nền Light Gray (sạch sẽ, thanh lịch)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 22,
            maxNativeZoom: 20
        }).addTo(map);

        // Đồng bộ dropdown chọn chợ = activeMarketId
        const marketSelect = document.getElementById('market-scope-select');
        if (marketSelect) {
            marketSelect.value = String(activeMarketId);
        }

        // Vẽ các phần tử lên bản đồ
        renderMapElements();

        // Thiết lập góc nhìn bản đồ ban đầu (Bao quát toàn bộ các chợ và sạp)
        if (activeMarketId === 0) {
            const allPoints = [];
            if (allMarkets && allMarkets.length > 0) {
                allMarkets.forEach(m => {
                    if (m.market_latitude && m.market_longitude) {
                        allPoints.push([parseFloat(m.market_latitude), parseFloat(m.market_longitude)]);
                    }
                });
            }
            if (leafElements && leafElements.length > 0) {
                leafElements.forEach(el => {
                    if (el.center) allPoints.push(el.center);
                });
            }

            if (allPoints.length > 1) {
                map.fitBounds(L.latLngBounds(allPoints), { padding: [70, 70], maxZoom: 17 });
            } else if (allPoints.length === 1) {
                map.setView(allPoints[0], 17);
            }
        }

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
                    
                    let typeSuffix = item.element_type;
                    if (typeSuffix === 'security-room') typeSuffix = 'security';
                    else if (typeSuffix === 'utility') typeSuffix = 'wc';
                    
                    const iconClass = `category-item-gps cat-${typeSuffix}`;
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
                const itemMId = parseInt(item.element_market_id || item.market_id || item.area_market_id || 0);
                leafElements.push({
                    id: item.element_id,
                    code: item.stall_code || '',
                    type: item.element_type,
                    layer: layer,
                    areaId: item.stall_area_id || null,
                    marketId: itemMId,
                    data: item,
                    center: center
                });
            }
        });

        // Vẽ viền bao quanh thích ứng sau khi đã nạp đầy đủ sạp
        renderMarketBoundaries();
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

        // Diện tích & Giá thuê tính với diện tích
        const numArea = parseFloat(item.stall_area_size || item.area_size || 0);
        const unitPrice = parseFloat(item.stall_base_price || 0);
        const totalPrice = (unitPrice > 0 && numArea > 0) ? (unitPrice * numArea) : unitPrice;

        if (detailAreaSize) {
            detailAreaSize.textContent = numArea > 0 ? (numArea + ' m²') : 'Đang cập nhật';
        }
        if (detailPrice) {
            if (item.price_hidden || totalPrice <= 0) {
                detailPrice.textContent = 'Liên hệ Ban Quản Lý';
            } else {
                detailPrice.innerHTML = `${totalPrice.toLocaleString('vi-VN')} đ/tháng <span style="font-size:11px; font-weight:normal; color:#64748b;">(${unitPrice.toLocaleString('vi-VN')} đ/m²)</span>`;
            }
        }

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
            const itemMId = parseInt(item.element_market_id || item.market_id || item.area_market_id || 0);
            btnRegisterStall.style.display = 'flex';
            btnRegisterStall.href = baseUrl + `home/register?stall_code=${encodeURIComponent(item.stall_code)}&market_id=${itemMId}&area=${numArea}`;
        } else {
            btnRegisterStall.style.display = 'none';
        }

        // Di chuyển camera camera đến trung tâm sạp
        map.panTo(center);
    }

    // 5. Cấu hình tìm kiếm định vị sạp & tiểu thương
    function setupSearch() {
        const resultsDropdown = document.getElementById('search-results-dropdown');

        function searchStalls(query) {
            query = query.trim().toLowerCase();
            if (!query) return [];

            const curMId = parseInt(window.currentSelectedMarketId) || 0;

            return elements.filter(item => {
                if (item.element_type !== 'stall') return false;

                const itemMId = parseInt(item.element_market_id || item.market_id || item.area_market_id || 0);
                if (curMId > 0 && itemMId !== curMId && !(itemMId === 0 && curMId === 1)) {
                    return false;
                }

                const code = (item.stall_code || '').toLowerCase();
                const trader = (item.trader_name || item.trader_fullname || '').toLowerCase();
                const phone = (item.trader_phone || '').toLowerCase();
                const area = (item.area_name || '').toLowerCase();
                const business = (item.business_line_name || '').toLowerCase();

                return code.includes(query) || 
                       trader.includes(query) || 
                       phone.includes(query) || 
                       area.includes(query) || 
                       business.includes(query);
            });
        }

        function renderDropdown(matches) {
            if (!resultsDropdown) return;

            if (matches.length === 0) {
                resultsDropdown.innerHTML = `<div style="padding: 10px; font-size: 12px; color: #64748b; text-align: center;">Không tìm thấy sạp hoặc tiểu thương phù hợp.</div>`;
                resultsDropdown.style.display = 'block';
                return;
            }

            resultsDropdown.innerHTML = '';
            matches.slice(0, 15).forEach(item => {
                const foundLeaf = leafElements.find(el => el.id === item.element_id);

                const row = document.createElement('div');
                row.style.cssText = 'padding: 8px 10px; border-bottom: 1px solid #f1f5f9; cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 8px; border-radius: 6px; transition: background 0.15s; font-size: 12.5px;';
                row.onmouseover = () => row.style.background = '#f0fdf4';
                row.onmouseout = () => row.style.background = 'transparent';

                const statusBadge = item.status_code === 'rented' 
                    ? '<span style="background:#dcfce7; color:#15803d; font-size:10px; padding:2px 6px; border-radius:10px; font-weight:700;">Đã thuê</span>'
                    : '<span style="background:#dbeafe; color:#1d4ed8; font-size:10px; padding:2px 6px; border-radius:10px; font-weight:700;">Còn trống</span>';

                row.innerHTML = `
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 700; color: #0f766e; display: flex; align-items: center; gap: 6px;">
                            <span>${item.stall_code}</span>
                            ${statusBadge}
                        </div>
                        <div style="font-size: 11.5px; color: #334155; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <i class="fa-solid fa-user" style="font-size: 10px; color: #64748b;"></i> ${item.trader_name || 'Chưa có tiểu thương'}
                        </div>
                        <div style="font-size: 10.5px; color: #64748b; margin-top: 1px;">
                            ${item.area_name || ''} ${item.market_name ? '• ' + item.market_name : ''}
                        </div>
                    </div>
                    <div style="font-size: 11px; color: #0f766e; font-weight: 600;">
                        <i class="fa-solid fa-crosshairs"></i>
                    </div>
                `;

                row.onclick = function () {
                    if (foundLeaf && foundLeaf.center) {
                        selectStall(item, foundLeaf.center, foundLeaf.layer);
                        map.flyTo(foundLeaf.center, 20, { duration: 1.0 });
                    } else {
                        const detailCode = document.getElementById('detail-code');
                        const detailZone = document.getElementById('detail-zone');
                        const detailBusiness = document.getElementById('detail-business');
                        const detailTrader = document.getElementById('detail-trader');
                        const detailTraderRow = document.getElementById('detail-trader-row');
                        const detailStatus = document.getElementById('detail-status');

                        if (detailCode) detailCode.textContent = item.stall_code;
                        if (detailZone) detailZone.textContent = item.area_name || '--';
                        if (detailBusiness) detailBusiness.textContent = item.business_line_name || '--';
                        if (detailTrader) detailTrader.textContent = item.trader_name || 'Chưa thuê';
                        if (detailTraderRow) detailTraderRow.style.display = item.trader_name ? 'flex' : 'none';
                        if (detailStatus) detailStatus.textContent = item.status_name || (item.status_code === 'rented' ? 'Đã thuê' : 'Còn trống');

                        stallDetailCard.style.display = 'block';
                        defaultWaitingMsg.style.display = 'none';
                        alert(`Sạp ${item.stall_code} chưa được gán tọa độ GPS trên sơ đồ.`);
                    }
                    if (resultsDropdown) resultsDropdown.style.display = 'none';
                };

                resultsDropdown.appendChild(row);
            });

            resultsDropdown.style.display = 'block';
        }

        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim();
            if (query.length === 0) {
                if (resultsDropdown) resultsDropdown.style.display = 'none';
                return;
            }
            const matches = searchStalls(query);
            renderDropdown(matches);
        });

        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const query = searchInput.value.trim();
                const matches = searchStalls(query);
                if (matches.length > 0) {
                    const first = matches[0];
                    const foundLeaf = leafElements.find(el => el.id === first.element_id);
                    if (foundLeaf && foundLeaf.center) {
                        selectStall(first, foundLeaf.center, foundLeaf.layer);
                        map.flyTo(foundLeaf.center, 20, { duration: 1.0 });
                    }
                    if (resultsDropdown) resultsDropdown.style.display = 'none';
                } else {
                    alert(`Không tìm thấy sạp hoặc tiểu thương nào khớp với từ khóa "${query}"`);
                }
            }
        });

        // Ẩn dropdown khi click ra ngoài
        document.addEventListener('click', function (e) {
            if (resultsDropdown && !searchInput.contains(e.target) && !resultsDropdown.contains(e.target)) {
                resultsDropdown.style.display = 'none';
            }
        });
    }

    // 6. Xây dựng Bộ lọc phân khu (Area Selector) góc bên trái sidebar
    function renderAreaSelector(targetMarketId = 0) {
        if (!areaSelectorContainer) return;
        areaSelectorContainer.innerHTML = '';
        targetMarketId = parseInt(targetMarketId) || 0;

        // Lấy danh sách duy nhất các khu vực từ dữ liệu elements
        const uniqueAreas = [];
        const checkedIds = new Set();

        elements.forEach(item => {
            const mId = parseInt(item.element_market_id || item.market_id || item.area_market_id || 0);
            if (targetMarketId > 0 && mId !== targetMarketId) return;

            if (item.stall_area_id && !checkedIds.has(item.stall_area_id)) {
                checkedIds.add(item.stall_area_id);
                
                // Tìm tên chợ tương ứng
                let marketName = '';
                const foundM = (typeof allMarkets !== 'undefined' && Array.isArray(allMarkets)) ? allMarkets.find(m => parseInt(m.market_id) === mId) : null;
                if (foundM) {
                    marketName = foundM.market_name;
                }

                uniqueAreas.push({
                    id: item.stall_area_id,
                    name: item.area_name || 'Khu vực',
                    marketId: mId,
                    marketName: marketName || (mId === 1 ? 'Chợ Phường Quyết Thắng' : (mId === 2 ? 'Chợ Trung Tâm Thành Phố' : 'Chợ khác'))
                });
            }
        });

        // Nhóm các khu vực theo Chợ
        const marketGroupMap = new Map();
        uniqueAreas.forEach(area => {
            const mKey = area.marketName || 'Chợ khác';
            if (!marketGroupMap.has(mKey)) {
                marketGroupMap.set(mKey, []);
            }
            marketGroupMap.get(mKey).push(area);
        });

        marketGroupMap.forEach((areaList, marketName) => {
            if (marketGroupMap.size > 1) {
                const header = document.createElement('div');
                header.style.cssText = 'font-size: 10.5px; font-weight: 700; color: #0f766e; padding: 6px 4px 2px; text-transform: uppercase; letter-spacing: 0.3px; display: flex; align-items: center; gap: 4px;';
                header.innerHTML = `<i class="fa-solid fa-store" style="font-size: 10px;"></i> ${marketName}`;
                areaSelectorContainer.appendChild(header);
            }

            areaList.forEach(area => {
                const btn = document.createElement('button');
                btn.className = 'selector-btn-gps';
                btn.textContent = area.name;
                btn.title = `${area.name} (${marketName})`;
                btn.onclick = function () {
                    document.querySelectorAll('.selector-btn-gps').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    filterByArea(area.id);
                };
                areaSelectorContainer.appendChild(btn);
            });
        });
    }

    // Biến lưu trữ ID chợ đang được chọn trên giao diện web
    window.currentSelectedMarketId = activeMarketId;

    // Lọc hiển thị và zoom vừa khít phân khu
    window.filterByArea = function (areaId) {
        // Nếu chọn phân khu, tự động reset các ô chọn bộ lọc nhanh ở legend để tránh xung đột
        const mapFilterArea = document.getElementById('map-filter-area-front');
        const mapFilterBusiness = document.getElementById('map-filter-business-front');
        if (mapFilterArea) mapFilterArea.value = '';
        if (mapFilterBusiness) mapFilterBusiness.value = '';

        const curMId = parseInt(window.currentSelectedMarketId) || 0;
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

            const mId = parseInt(el.marketId || 0);
            const matchesMarket = (curMId === 0) || (mId === curMId) || (mId === 0 && curMId === 1);

            if (areaId === 'all') {
                if (matchesMarket) {
                    if (map && !map.hasLayer(el.layer)) map.addLayer(el.layer);
                    if (el.center) targets.push(el.center);
                } else {
                    if (map) map.removeLayer(el.layer);
                }
            } else {
                if (matchesMarket && el.areaId === areaId) {
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

    // Định vị nhanh danh mục tiện ích công cộng (4 nút chỉ dẫn)
    window.focusOnUtility = function (type) {
        const curMId = parseInt(window.currentSelectedMarketId) || 0;
        const founds = leafElements.filter(el => {
            if (el.type !== type) return false;
            if (curMId === 0) return true;
            const mId = parseInt(el.marketId || 0);
            return mId === curMId || (mId === 0 && curMId === 1);
        });

        if (founds.length > 0) {
            const coords = founds.map(f => f.center);
            const bounds = L.latLngBounds(coords);
            map.fitBounds(bounds, { padding: [50, 50], maxZoom: 21 });
        } else {
            alert('Chưa có thông tin định vị GPS cho tiện ích này tại chợ đang chọn.');
        }
    };

    // Căn camera về trung tâm mặc định của Chợ đang chọn (hoặc toàn bộ các chợ)
    window.resetMapView = function () {
        const curMId = parseInt(window.currentSelectedMarketId) || 0;

        if (curMId === 0) {
            const allPoints = [];
            if (allMarkets && allMarkets.length > 0) {
                allMarkets.forEach(m => {
                    if (m.market_latitude && m.market_longitude) {
                        allPoints.push([parseFloat(m.market_latitude), parseFloat(m.market_longitude)]);
                    }
                });
            }
            if (leafElements && leafElements.length > 0) {
                leafElements.forEach(el => {
                    if (el.center) allPoints.push(el.center);
                });
            }

            if (allPoints.length > 1) {
                map.flyToBounds(L.latLngBounds(allPoints), { padding: [70, 70], maxZoom: 17, duration: 1.2 });
            } else if (allPoints.length === 1) {
                map.flyTo(allPoints[0], 17, { duration: 1.2 });
            }
        } else {
            const targetM = allMarkets.find(m => parseInt(m.market_id) === curMId);
            if (targetM && targetM.market_latitude && targetM.market_longitude) {
                const mLat = parseFloat(targetM.market_latitude);
                const mLng = parseFloat(targetM.market_longitude);
                const mZoom = parseInt(targetM.market_map_zoom) || 19;

                const mStalls = leafElements.filter(el => {
                    const mid = parseInt(el.marketId || 0);
                    return mid === curMId || (mid === 0 && curMId === 1);
                });

                const closeStalls = mStalls.filter(s => {
                    if (!s.center) return false;
                    const dLat = Math.abs(s.center[0] - mLat);
                    const dLng = Math.abs(s.center[1] - mLng);
                    return dLat < 0.0025 && dLng < 0.0025;
                });

                if (closeStalls.length > 0) {
                    const mBounds = L.latLngBounds(closeStalls.map(s => s.center).concat([[mLat, mLng]]));
                    map.flyToBounds(mBounds, { padding: [40, 40], maxZoom: 19.5, duration: 1.2 });
                } else {
                    map.flyTo([mLat, mLng], Math.max(mZoom, 19), { duration: 1.2 });
                }
            }
        }
        
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

    // Chuyển góc nhìn tới từng Chợ trực tiếp trên cùng một bản đồ mà không tải lại trang
    window.switchMarketFocus = function (targetMarketId) {
        targetMarketId = parseInt(targetMarketId) || 0;
        window.currentSelectedMarketId = targetMarketId;
        
        // Đồng bộ ô chọn dropdown trên thanh công cụ
        const marketSelect = document.getElementById('market-scope-select');
        if (marketSelect) marketSelect.value = targetMarketId;

        // 1. Cập nhật lại danh sách phân khu / ngành hàng hiển thị theo chợ ở sidebar
        renderAreaSelector(targetMarketId);

        // 2. Lọc dropdown phân khu ở bảng Legend trên map (#map-filter-area-front)
        const mapFilterArea = document.getElementById('map-filter-area-front');
        if (mapFilterArea) {
            const optgroups = mapFilterArea.querySelectorAll('optgroup');
            if (targetMarketId === 0) {
                optgroups.forEach(og => {
                    og.style.display = '';
                    og.hidden = false;
                    og.querySelectorAll('option[data-market-id]').forEach(opt => {
                        opt.style.display = '';
                        opt.hidden = false;
                    });
                });
            } else {
                optgroups.forEach(og => {
                    const childOpts = og.querySelectorAll('option[data-market-id]');
                    let hasVisible = false;
                    childOpts.forEach(opt => {
                        const optMId = parseInt(opt.getAttribute('data-market-id')) || 0;
                        if (optMId === targetMarketId) {
                            opt.style.display = '';
                            opt.hidden = false;
                            hasVisible = true;
                        } else {
                            opt.style.display = 'none';
                            opt.hidden = true;
                        }
                    });
                    og.style.display = hasVisible ? '' : 'none';
                    og.hidden = !hasVisible;
                });
            }
            mapFilterArea.value = '';
        }

        // 3. Lọc danh sách sạp trống hiển thị bên dưới bản đồ
        const stallCards = document.querySelectorAll('.stalls-grid .stall-card');
        stallCards.forEach(card => {
            const cardMId = parseInt(card.getAttribute('data-market-id')) || 0;
            if (targetMarketId === 0 || cardMId === targetMarketId) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });

        if (targetMarketId === 0) {
            // Xem toàn bộ các chợ: Căn góc nhìn bao quát toàn bộ
            resetMapView();
            // Khôi phục hiển thị tất cả các sạp & phân khu
            leafElements.forEach(el => {
                if (map && !map.hasLayer(el.layer)) map.addLayer(el.layer);
            });
            document.querySelectorAll('.selector-btn-gps').forEach(b => b.classList.remove('active'));
            const btnAll = document.getElementById('btn-filter-all-front');
            if (btnAll) btnAll.classList.add('active');
        } else {
            const targetM = allMarkets.find(m => parseInt(m.market_id) === targetMarketId);
            if (targetM && targetM.market_latitude && targetM.market_longitude) {
                const mLat = parseFloat(targetM.market_latitude);
                const mLng = parseFloat(targetM.market_longitude);
                const mZoom = parseInt(targetM.market_map_zoom) || 19;

                // Tìm tất cả sạp thuộc về chợ này
                const mStalls = leafElements.filter(el => {
                    const mid = parseInt(el.marketId || 0);
                    return mid === targetMarketId || (mid === 0 && targetMarketId === 1);
                });

                // Lọc các điểm sạp trong khuôn viên chợ để zoom cận cảnh sắc nét, loại bỏ điểm ngoại lai làm giãn camera
                const closeStalls = mStalls.filter(s => {
                    if (!s.center) return false;
                    const dLat = Math.abs(s.center[0] - mLat);
                    const dLng = Math.abs(s.center[1] - mLng);
                    return dLat < 0.0025 && dLng < 0.0025;
                });

                if (closeStalls.length > 0) {
                    const mBounds = L.latLngBounds(closeStalls.map(s => s.center).concat([[mLat, mLng]]));
                    map.flyToBounds(mBounds, { padding: [40, 40], maxZoom: 19.5, duration: 1.2 });
                } else {
                    map.flyTo([mLat, mLng], Math.max(mZoom, 19), { duration: 1.2 });
                }
            }
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
