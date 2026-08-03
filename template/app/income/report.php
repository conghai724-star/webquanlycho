<?php
$balance = $totalIncome - $totalExpense;
$url = BASE_URL . 'admin/income_report';
$monthNames = ['','Tháng 1','Tháng 2','Tháng 3','Tháng 4','Tháng 5','Tháng 6','Tháng 7','Tháng 8','Tháng 9','Tháng 10','Tháng 11','Tháng 12'];
$incomeCategories = $expenseCategories = [];
foreach ($categories as $c) {
    if ($c['category_type'] === 'income') $incomeCategories[] = $c;
    else $expenseCategories[] = $c;
}
$selectedCatName = 'Tất cả danh mục';
if ($categoryId) foreach ($categories as $c) { if ((int)$c['category_id'] === $categoryId) { $selectedCatName = $c['category_name']; break; } }
$monthLabel = $month ? $monthNames[$month] : 'Cả năm';
?>

<!-- ── BỘ LỌC ── -->
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px">
    <div>
        <h5 style="margin:0 0 4px;font-weight:700">Báo cáo Thu Chi <?php echo $month ? $monthNames[$month].'/' : 'năm '; ?><?php echo $year; ?></h5>
        <p style="margin:0;font-size:12px;color:var(--text-muted)"><?php echo $selectedCatName; ?></p>
    </div>
    <form action="<?php echo $url; ?>" method="get" style="display:flex;gap:8px;flex-wrap:wrap;align-items:end">
        <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11px">Năm</label>
            <select class="form-control" name="year" onchange="this.form.submit()" style="width:110px">
                <?php for ($y = date('Y') + 1; $y >= date('Y') - 5; $y--): ?>
                    <option value="<?php echo $y; ?>" <?php echo $y == $year ? 'selected' : ''; ?>><?php echo $y; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11px">Tháng</label>
            <select class="form-control" name="month" onchange="this.form.submit()" style="width:130px">
                <option value="">Cả năm</option>
                <?php for ($mi = 1; $mi <= 12; $mi++): ?>
                    <option value="<?php echo $mi; ?>" <?php echo $month == $mi ? 'selected' : ''; ?>><?php echo $monthNames[$mi]; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group" style="margin:0">
            <label class="form-label" style="font-size:11px">Danh mục</label>
            <select class="form-control" name="category_id" onchange="this.form.submit()" style="min-width:180px">
                <option value="">Tất cả danh mục</option>
                <?php if ($incomeCategories): ?>
                    <optgroup label="── Danh mục Thu">
                        <?php foreach ($incomeCategories as $c): ?>
                            <option value="<?php echo $c['category_id']; ?>" <?php echo $categoryId == $c['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['category_name']); ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
                <?php if ($expenseCategories): ?>
                    <optgroup label="── Danh mục Chi">
                        <?php foreach ($expenseCategories as $c): ?>
                            <option value="<?php echo $c['category_id']; ?>" <?php echo $categoryId == $c['category_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['category_name']); ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
            </select>
        </div>
    </form>
</div>

<!-- ── 3 CARD THỐNG KÊ ── -->
<div class="row col-3" style="margin-bottom:24px">
    <!-- Tổng Thu -->
    <div class="card">
        <div class="stat">
            <div class="stat-icon" style="background-color:rgba(52,168,83,0.1);color:#34A853">
                <i class="fa-solid fa-arrow-trend-up" style="font-size:18px"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng Thu</div>
                <div class="stat-value-row">
                    <span class="stat-value" style="color:#34A853"><?php echo number_format($totalIncome, 0, ',', '.'); ?></span>
                </div>
                <div class="stat-subtext">VNĐ · Năm <?php echo $year; ?></div>
            </div>
        </div>
    </div>
    <!-- Tổng Chi -->
    <div class="card">
        <div class="stat">
            <div class="stat-icon" style="background-color:rgba(234,67,53,0.1);color:#EA4335">
                <i class="fa-solid fa-arrow-trend-down" style="font-size:18px"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Tổng Chi</div>
                <div class="stat-value-row">
                    <span class="stat-value" style="color:#EA4335"><?php echo number_format($totalExpense, 0, ',', '.'); ?></span>
                </div>
                <div class="stat-subtext">VNĐ · Năm <?php echo $year; ?></div>
            </div>
        </div>
    </div>
    <!-- Chênh lệch -->
    <div class="card">
        <div class="stat">
            <div class="stat-icon" style="background-color:rgba(66,133,244,0.1);color:#4285F4">
                <i class="fa-solid fa-scale-balanced" style="font-size:18px"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Chênh lệch (Thu − Chi)</div>
                <div class="stat-value-row">
                    <span class="stat-value" style="color:<?php echo $balance >= 0 ? '#34A853' : '#EA4335'; ?>"><?php echo ($balance >= 0 ? '+' : '') . number_format($balance, 0, ',', '.'); ?></span>
                </div>
                <div class="stat-subtext">VNĐ · <?php echo $balance >= 0 ? 'Thặng dư' : 'Thâm hụt'; ?></div>
            </div>
        </div>
    </div>
</div>

<!-- ── BIỂU ĐỒ CỘT THEO THÁNG ── -->
<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <div class="card-title">Biểu đồ Thu Chi theo tháng — Năm <?php echo $year; ?></div>
    </div>
    <div class="card-body" style="padding:20px">
        <div style="position:relative;height:360px">
            <canvas id="reportChart"></canvas>
        </div>
    </div>
</div>

<?php if ($daily): ?>
<!-- ── BIỂU ĐỒ THEO NGÀY ── -->
<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <div class="card-title">Chi tiết Thu Chi theo ngày — <?php echo $monthNames[$month]; ?>/<?php echo $year; ?></div>
    </div>
    <div class="card-body" style="padding:20px">
        <div style="position:relative;height:320px">
            <canvas id="dailyChart"></canvas>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ── BẢNG TỔNG HỢP THEO THÁNG ── -->
<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <div class="card-title">Bảng tổng hợp Thu Chi</div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tháng</th>
                        <th style="text-align:right">Thu (VNĐ)</th>
                        <th style="text-align:right">Chi (VNĐ)</th>
                        <th style="text-align:right">Chênh lệch</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($monthly as $m => $row): ?>
                        <?php $diff = $row['income'] - $row['expense']; $hasData = $row['income'] > 0 || $row['expense'] > 0; ?>
                        <tr style="<?php echo !$hasData ? 'color:var(--text-muted)' : ''; ?>">
                            <td><?php echo $monthNames[$m]; ?></td>
                            <td style="text-align:right;font-weight:<?php echo $row['income'] > 0 ? '600' : '400'; ?>;color:<?php echo $row['income'] > 0 ? '#34A853' : 'inherit'; ?>"><?php echo number_format($row['income'], 0, ',', '.'); ?></td>
                            <td style="text-align:right;font-weight:<?php echo $row['expense'] > 0 ? '600' : '400'; ?>;color:<?php echo $row['expense'] > 0 ? '#EA4335' : 'inherit'; ?>"><?php echo number_format($row['expense'], 0, ',', '.'); ?></td>
                            <td style="text-align:right;font-weight:600;color:<?php echo $diff >= 0 ? '#34A853' : '#EA4335'; ?>"><?php echo ($diff >= 0 ? '+' : '') . number_format($diff, 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="font-weight:700;border-top:2px solid var(--border-color)">
                        <td>Tổng cộng</td>
                        <td style="text-align:right;color:#34A853"><?php echo number_format($totalIncome, 0, ',', '.'); ?></td>
                        <td style="text-align:right;color:#EA4335"><?php echo number_format($totalExpense, 0, ',', '.'); ?></td>
                        <td style="text-align:right;color:<?php echo $balance >= 0 ? '#34A853' : '#EA4335'; ?>"><?php echo ($balance >= 0 ? '+' : '') . number_format($balance, 0, ',', '.'); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- ── FORM XUẤT EXCEL S07-X ── -->
<div class="card">
    <div class="card-header" style="cursor:pointer" onclick="this.nextElementSibling.hidden=!this.nextElementSibling.hidden;this.querySelector('.chevron').classList.toggle('rotate')">
        <div class="card-title" style="display:flex;align-items:center;gap:8px">
            <i class="fa-solid fa-file-excel" style="color:#34A853"></i>
            Xuất Sổ theo dõi S07-X
            <span class="chevron" style="margin-left:auto;transition:transform .2s">⌄</span>
        </div>
    </div>
    <div class="card-body" hidden>
        <form action="<?php echo BASE_URL; ?>admin/export_s07x" method="get">
            <div class="alert alert-info" style="margin-bottom:20px">Mẫu xuất: <b>S07-X</b> theo Thông tư 70/2019/TT-BTC. Thông tin đơn vị lấy từ cấu hình chợ đang chọn.</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                <div class="form-group"><label class="form-label">Đơn vị</label><input class="form-control" value="<?php echo htmlspecialchars($unit['unit_name']); ?>" readonly></div>
                <div class="form-group"><label class="form-label">Mã QHNS</label><input class="form-control" value="<?php echo htmlspecialchars($unit['budget_code']); ?>" readonly></div>
                <div class="form-group"><label class="form-label">Tên quỹ *</label><input class="form-control" name="fund_name" required placeholder="Ví dụ: Quỹ phát triển hoạt động chợ"></div>
                <div class="form-group"><label class="form-label">Năm *</label><input class="form-control" type="number" name="year" min="2000" max="2100" value="<?php echo $year; ?>" required></div>
                <div class="form-group"><label class="form-label">Số dư đầu kỳ</label><input class="form-control" type="number" min="0" name="opening_balance" value="0" step="1"></div>
                <div class="form-group"><label class="form-label">Kỳ báo cáo</label><div style="display:flex;gap:8px"><input class="form-control" type="date" name="from_date" value="<?php echo $year; ?>-01-01"><input class="form-control" type="date" name="to_date" value="<?php echo $year; ?>-12-31"></div></div>
            </div>
            <div style="margin-top:22px;display:flex;justify-content:flex-end"><button class="btn btn-primary" type="submit"><i class="fa-solid fa-file-excel"></i> Xuất Excel (.xlsx)</button></div>
        </form>
        <p style="font-size:12px;color:var(--text-muted);margin:18px 0 0">Báo cáo tự gom theo tháng có phát sinh, tính số dư đầu/cuối tháng và lũy kế từ đầu năm từ các phiếu Thu/Chi.</p>
    </div>
</div>

<style>.chevron.rotate{transform:rotate(180deg)}</style>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function(){
    const labels = <?php echo json_encode(array_slice($monthNames, 1)); ?>;
    const incomeData = <?php echo json_encode(array_values(array_map(function($m){return $m['income'];}, $monthly))); ?>;
    const expenseData = <?php echo json_encode(array_values(array_map(function($m){return $m['expense'];}, $monthly))); ?>;

    const ctx = document.getElementById('reportChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Thu',
                    data: incomeData,
                    backgroundColor: 'rgba(52, 168, 83, 0.7)',
                    borderColor: '#34A853',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.45,
                    categoryPercentage: 0.7
                },
                {
                    label: 'Chi',
                    data: expenseData,
                    backgroundColor: 'rgba(234, 67, 53, 0.7)',
                    borderColor: '#EA4335',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.45,
                    categoryPercentage: 0.7
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top', labels: { usePointStyle: true, padding: 16 } },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ': ' + new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' đ';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(val) {
                            if (val >= 1e9) return (val / 1e9).toFixed(1) + ' tỷ';
                            if (val >= 1e6) return (val / 1e6).toFixed(0) + ' tr';
                            if (val >= 1e3) return (val / 1e3).toFixed(0) + 'k';
                            return val;
                        }
                    },
                    grid: { color: 'rgba(0,0,0,0.06)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
})();

<?php if ($daily): ?>
// Daily chart
(function(){
    const days = <?php echo json_encode(array_map(function($d) use ($year, $month) { return str_pad($d, 2, '0', STR_PAD_LEFT).'/'.$month; }, array_keys($daily))); ?>;
    const dIncome = <?php echo json_encode(array_values(array_map(function($d){return $d['income'];}, $daily))); ?>;
    const dExpense = <?php echo json_encode(array_values(array_map(function($d){return $d['expense'];}, $daily))); ?>;
    new Chart(document.getElementById('dailyChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: days,
            datasets: [
                { label:'Thu', data:dIncome, backgroundColor:'rgba(52,168,83,0.7)', borderColor:'#34A853', borderWidth:1, borderRadius:3, barPercentage:0.5, categoryPercentage:0.8 },
                { label:'Chi', data:dExpense, backgroundColor:'rgba(234,67,53,0.7)', borderColor:'#EA4335', borderWidth:1, borderRadius:3, barPercentage:0.5, categoryPercentage:0.8 }
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            interaction:{mode:'index',intersect:false},
            plugins:{
                legend:{position:'top',labels:{usePointStyle:true,padding:16}},
                tooltip:{callbacks:{label:function(c){return c.dataset.label+': '+new Intl.NumberFormat('vi-VN').format(c.raw)+' đ'}}}
            },
            scales:{
                y:{beginAtZero:true,ticks:{callback:function(v){if(v>=1e9)return(v/1e9).toFixed(1)+' tỷ';if(v>=1e6)return(v/1e6).toFixed(0)+' tr';if(v>=1e3)return(v/1e3).toFixed(0)+'k';return v}},grid:{color:'rgba(0,0,0,0.06)'}},
                x:{grid:{display:false}}
            }
        }
    });
})();
<?php endif; ?>
</script>
