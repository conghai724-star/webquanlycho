<?php
/**
 * View in hợp đồng thuê sạp
 */
function translateNumberToWords($number) {
    $hyphen      = ' ';
    $conjunction = ' ';
    $separator   = ' ';
    $negative    = 'âm ';
    $decimal     = ' phẩy ';
    $dictionary  = array(
        0                   => 'không',
        1                   => 'một',
        2                   => 'hai',
        3                   => 'ba',
        4                   => 'bốn',
        5                   => 'năm',
        6                   => 'sáu',
        7                   => 'bảy',
        8                   => 'tám',
        9                   => 'chín',
        10                  => 'mười',
        11                  => 'mười một',
        12                  => 'mười hai',
        13                  => 'mười ba',
        14                  => 'mười bốn',
        15                  => 'mười lăm',
        16                  => 'mười sáu',
        17                  => 'mười bảy',
        18                  => 'mười tám',
        19                  => 'mười chín',
        20                  => 'hai mươi',
        30                  => 'ba mươi',
        40                  => 'bốn mươi',
        50                  => 'năm mươi',
        60                  => 'sáu mươi',
        70                  => 'bảy mươi',
        80                  => 'tám mươi',
        90                  => 'chín mươi',
        100                 => 'trăm',
        1000                => 'ngàn',
        1000000             => 'triệu',
        1000000000          => 'tỷ'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if ($number < 0) {
        return $negative . translateNumberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                if ($units == 1) {
                    $string .= $conjunction . 'mốt';
                } elseif ($units == 5) {
                    $string .= $conjunction . 'lăm';
                } else {
                    $string .= $conjunction . $dictionary[$units];
                }
            }
            break;
        case $number < 1000:
            $hundreds  = (int) ($number / 100);
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                if ($remainder < 10) {
                    $string .= $conjunction . 'lẻ ' . translateNumberToWords($remainder);
                } else {
                    $string .= $conjunction . translateNumberToWords($remainder);
                }
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = translateNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction . 'lẻ ' : $separator;
                $string .= translateNumberToWords($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function calculateMonths($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    
    $years = $interval->y;
    $months = $interval->m;
    $days = $interval->d;
    
    $totalMonths = ($years * 12) + $months;
    if ($days > 0) {
        $totalMonths += round($days / 30, 1);
    }
    return $totalMonths;
}

$start_ts = strtotime($contract['contract_start_date']);
$end_ts = strtotime($contract['contract_end_date']);
$sign_ts = !empty($contract['contract_sign_date']) ? strtotime($contract['contract_sign_date']) : $start_ts;
$months = calculateMonths($contract['contract_start_date'], $contract['contract_end_date']);

$area_size = (float)($contract['stall_area_size'] ?? 0);
$price_per_m2 = (float)($contract['price'] ?? 0);

if ($area_size > 0) {
    $monthly_rent = round($price_per_m2 * $area_size);
    $total_amount = round($monthly_rent * $months);
} else {
    $monthly_rent = $price_per_m2;
    $total_amount = round($monthly_rent * $months);
}

$total_amount_words = ucfirst(trim(translateNumberToWords($total_amount))) . ' đồng chẵn';

$size = (float)($contract['stall_area_size'] ?? 4);
$width = isset($contract['stall_map_coordinate_y']) && (float)$contract['stall_map_coordinate_y'] > 0 ? (float)$contract['stall_map_coordinate_y'] : null;
$length = isset($contract['stall_map_coordinate_x']) && (float)$contract['stall_map_coordinate_x'] > 0 ? (float)$contract['stall_map_coordinate_x'] : null;

if ($width === null || $length === null) {
    if ($size == 4) {
        $width = 2;
        $length = 2;
    } elseif ($size == 6) {
        $width = 2;
        $length = 3;
    } elseif ($size == 8) {
        $width = 2;
        $length = 4;
    } elseif ($size == 9) {
        $width = 3;
        $length = 3;
    } else {
        $width = round(sqrt($size), 1);
        $length = $width > 0 ? round($size / $width, 1) : 0;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>In hợp đồng - <?php echo htmlspecialchars($contract['contract_number']); ?></title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14pt;
            line-height: 1.15;
            color: #000;
            margin: 0;
            padding: 20px 0;
            background-color: #f1f3f4;
        }

        .no-print-bar {
            background-color: #fff;
            padding: 12px 24px;
            margin: -20px auto 20px auto;
            width: 210mm;
            box-sizing: border-box;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dadce0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .btn-print {
            background-color: #1abb9c;
            color: white;
            border: none;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 4px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-print:hover {
            background-color: #15947e;
        }

        .paper {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 20mm 20mm 30mm;
            box-sizing: border-box;
            background-color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .paper div, .paper p {
            margin-top: 3pt;
            margin-bottom: 3pt;
            padding: 0;
            text-align: justify;
        }

        .paper .p-ind { text-indent: 1cm; }

        .header-table { width: calc(100% + 20mm); margin-left: -10mm; margin-right: -10mm; border-collapse: collapse; padding: 0; margin-bottom: 0; }
        .header-table td { vertical-align: middle; padding: 0 4px; margin: 0; }
        .left-header { width: 42%; text-align: center; font-size: 13pt; line-height: 1.15; white-space: nowrap; }
        .right-header { width: 58%; text-align: center; font-size: 13pt; line-height: 1.15; white-space: nowrap; }
        .right-header .national-title { font-weight: bold; font-size: 13pt; text-transform: uppercase; }
        .national-subtitle { font-weight: bold; font-size: 14pt; }
        .line-dec { width: 65px; margin: 3px auto 0 auto; border-bottom: 1px solid #000; }
        .line-dec-long { width: 175px; margin: 3px auto 0 auto; border-bottom: 1px solid #000; }
        .paper .doc-title { text-align: center; font-weight: bold; font-size: 14pt; margin-top: 0; margin-bottom: 2pt; text-transform: uppercase; line-height: 1.15; }
        .paper .doc-subtitle { text-align: center; font-weight: bold; font-size: 14pt; margin-top: 0; margin-bottom: 2pt; text-transform: uppercase; line-height: 1.15; }
        .section-title { font-weight: bold; margin-top: 3pt; margin-bottom: 3pt; text-indent: 1cm; }
        .indent-block { margin-left: 20px; margin-top: 3pt; margin-bottom: 3pt; }
        .signature-table { width: 100%; margin: 6pt auto 0 auto; border-collapse: collapse; padding: 0; }
        .signature-table td { width: 50%; text-align: center; vertical-align: middle; font-size: 14pt; line-height: 1.15; padding: 0; margin: 0; }
        .signature-table td div { text-align: center; }
        .signature-title { font-weight: bold; }

        @media print {
            .no-print-bar { display: none; }
            body { padding: 0; margin: 0; background-color: #fff; }
            .paper { width: 100%; min-height: auto; padding: 0; box-shadow: none; }
            @page { size: A4; margin: 20mm 20mm 20mm 30mm; }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <span style="font-size: 14px; font-weight: 500; color: #3c4043;">
            Xem thử bản in hợp đồng
            <?php if (!empty($allConfigs) && count($allConfigs) > 1): ?>
                 |  Mẫu in:
                <select id="config_select" style="padding: 4px 8px; border-radius: 4px; border: 1px solid #ccc; outline: none; margin-left: 4px; font-size: 13px;">
                    <?php foreach ($allConfigs as $cfg): ?>
                        <option value="<?php echo $cfg['config_id']; ?>" <?php echo isset($selectedConfig['config_id']) && (int)$cfg['config_id'] === (int)$selectedConfig['config_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cfg['template_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </span>
        <div style="display: flex; gap: 8px;">
            <a href="<?php echo BASE_URL; ?>admin/contract_export_docx/<?php echo $contract['contract_id']; ?><?php echo !empty($selectedConfig['config_id']) ? '?config_id=' . $selectedConfig['config_id'] : ''; ?>" class="btn-print" style="background-color: #2b579a; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;" title="Tải hợp đồng về định dạng Microsoft Word (.docx)">
                📄 Xuất file Word (.docx)
            </a>
            <button onclick="window.print()" class="btn-print">🖨 In hợp đồng</button>
            <button onclick="window.close()" class="btn-print" style="background-color: #5f6368;">Đóng</button>
        </div>
    </div>

    <div class="paper">
        <table class="header-table" style="margin-bottom: 0;">
            <tr style="margin: 0; padding: 0;">
                <td class="left-header" style="padding: 0; margin: 0; line-height: 1; font-weight: normal;">
                    <?php echo htmlspecialchars(trim(preg_replace('/\s+/', ' ', $selectedConfig['gov_agency_1'] ?? ''))); ?>
                </td>
                <td class="right-header" style="padding: 0; margin: 0; line-height: 1;">
                    <span class="national-title">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</span>
                </td>
            </tr>
            <tr style="margin: 0; padding: 0;">
                <td class="left-header" style="padding: 0; margin: 0; line-height: 1; font-weight: bold;">
                    <?php echo htmlspecialchars(trim(preg_replace('/\s+/', ' ', $selectedConfig['gov_agency_2'] ?? ''))); ?>
                    <div class="line-dec"></div>
                </td>
                <td class="right-header" style="padding: 0; margin: 0; line-height: 1;">
                    <span class="national-subtitle">Độc lập - Tự do - Hạnh phúc</span>
                    <div class="line-dec-long"></div>
                </td>
            </tr>
            <tr style="margin: 0; padding: 0;">
                <td class="left-header" style="padding: 0; margin: 0; line-height: 1; font-weight: normal; font-size: 14pt;">
                    Số: <?php echo htmlspecialchars($contract['contract_number'] ?: '..... ...'); ?>
                </td>
                <td class="right-header" style="padding: 0; margin: 0; line-height: 1;">
                    &nbsp;
                </td>
            </tr>
        </table>

        <!-- Enter bảng: 1 dòng cách -->
        <div style="height: 14pt; margin: 0; padding: 0;"></div>

        <div class="doc-title">HỢP ĐỒNG DỊCH VỤ</div>
        <div class="doc-subtitle">SỬ DỤNG DIỆN TÍCH BÁN HÀNG <?php echo nl2br(htmlspecialchars($selectedConfig['contract_title_suffix'] ?? '')); ?></div>
        <div class="line-dec" style="width: 70px; margin: 2pt auto 14pt auto;"></div>

        <div style="font-style: italic;">
            <?php
            $grounds = explode("\n", $selectedConfig['legal_grounds'] ?? '');
            foreach ($grounds as $g) {
                $g = trim($g);
                if ($g !== '') {
                    echo '<div class="p-ind">' . htmlspecialchars($g) . '</div>';
                }
            }
            ?>
        </div>

        <div class="p-ind">
            Hôm nay, ngày <?php echo date('d', $sign_ts); ?> tháng <?php echo date('m', $sign_ts); ?> năm <?php echo date('Y', $sign_ts); ?> chúng tôi gồm có:
        </div>

        <div class="section-title"><?php echo nl2br(htmlspecialchars($selectedConfig['rep_a_header'] ?? '')); ?></div>
        <?php if (!empty($selectedConfig['rep_a_name_1'])): ?>
            <div class="p-ind" style="text-align: left;">1. Ông (Bà): <?php echo htmlspecialchars($selectedConfig['rep_a_name_1']); ?>;        Chức vụ: <?php echo htmlspecialchars($selectedConfig['rep_a_position_1']); ?>;</div>
        <?php endif; ?>
        <?php if (!empty($selectedConfig['rep_a_name_2'])): ?>
            <div class="p-ind" style="text-align: left;">2. Ông (Bà): <?php echo htmlspecialchars($selectedConfig['rep_a_name_2']); ?>;        Chức vụ: <?php echo htmlspecialchars($selectedConfig['rep_a_position_2']); ?>.</div>
        <?php endif; ?>
        <div class="p-ind" style="text-align: left;">Địa chỉ: <?php echo htmlspecialchars($selectedConfig['rep_a_address']); ?></div>
        <?php if (!empty($selectedConfig['rep_a_phone']) || !empty($selectedConfig['rep_a_fax'])): ?>
            <div class="p-ind" style="text-align: left;">
                <?php if (!empty($selectedConfig['rep_a_phone'])): ?>Điện thoại: <?php echo htmlspecialchars($selectedConfig['rep_a_phone']); ?><?php endif; ?>
                <?php if (!empty($selectedConfig['rep_a_fax'])): ?>    Fax: <?php echo htmlspecialchars($selectedConfig['rep_a_fax']); ?><?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($selectedConfig['rep_a_bank_account'])): ?>
            <div class="p-ind" style="text-align: left;">Tài khoản: <?php echo htmlspecialchars($selectedConfig['rep_a_bank_account']); ?><?php if (!empty($selectedConfig['rep_a_bank_name'])): ?> Tại: <?php echo htmlspecialchars($selectedConfig['rep_a_bank_name']); ?><?php endif; ?></div>
        <?php endif; ?>

        <div class="section-title">Đại diện người thuê sử dụng mặt bằng (gọi tắt là Bên B):</div>
        <div class="p-ind" style="text-align: left;">Ông (Bà): <strong><?php echo mb_strtoupper(htmlspecialchars($contract['trader_name'] ?? ''), 'UTF-8'); ?></strong></div>
        <div class="p-ind" style="text-align: left;">Địa chỉ thường trú: <?php echo !empty($contract['trader_address']) ? htmlspecialchars($contract['trader_address']) : '..........................................................................................'; ?></div>
        <div class="p-ind" style="text-align: left;">Điện thoại: <?php echo !empty($contract['trader_phone']) ? htmlspecialchars($contract['trader_phone']) : '....................................................................................................'; ?></div>
        <div class="p-ind" style="text-align: left;">Căn cước công dân số: <?php echo !empty($contract['trader_cccd']) ? htmlspecialchars($contract['trader_cccd']) : '................................................................................'; ?></div>

        <div class="p-ind">
            Sau khi hai bên bàn bạc và đi đến thống nhất về nội dung thu tiền dịch vụ sử dụng diện tích bán hàng và thu gom, vận chuyển rác thải năm <?php echo date('Y', $sign_ts); ?> gồm có các điều khoản như sau:
        </div>

        <div class="section-title">Điều 1. Nội dung hợp đồng</div>
        <div class="p-ind">Bên A thỏa thuận cho bên B thuê sử dụng mặt bằng bán hàng năm <?php echo date('Y', $sign_ts); ?>: 01 sạp (lô); vị trí: <?php echo htmlspecialchars($contract['area_name'] ?? ''); ?>, cụ thể như sau:</div>
        <div class="p-ind">Diện tích sử dụng: <?php echo htmlspecialchars((float)($contract['stall_area_size'] ?? 0)); ?> m² <em>(chiều dài <?php echo $length; ?>m x chiều rộng <?php echo $width; ?>m)</em></div>
        <div class="p-ind">Mặt hàng kinh doanh: <?php echo htmlspecialchars(($contract['stall_type'] ?? '') ?: 'Hàng khô'); ?></div>

        <div class="section-title">Điều 2. Giá dịch vụ, phương thức thanh toán và thời hạn hợp đồng.</div>
        <div class="p-ind"><strong>2.1 Giá dịch vụ:</strong></div>
        <div class="p-ind">Thực hiện theo giá dịch vụ của cơ quan có thẩm quyền ban hành theo từng thời điểm, khi thay đổi vị trí hoặc tăng, giảm diện tích mặt bằng thì hai bên sẽ thống nhất điều chỉnh giá thuê mặt bằng theo thực tế và các quy định hiện hành của Nhà nước:</div>
        <div class="p-ind">- Dịch vụ sử dụng diện tích bán hàng:</div>
        <div class="p-ind">
            <?php if ($area_size > 0): ?>
                <?php echo number_format($price_per_m2, 0, ',', '.'); ?> đồng/m² x <?php echo htmlspecialchars((float)$area_size); ?> m² x <?php echo $months; ?> tháng = <?php echo number_format($total_amount, 0, ',', '.'); ?> đồng.
            <?php else: ?>
                <?php echo number_format($monthly_rent, 0, ',', '.'); ?> đồng/tháng x <?php echo $months; ?> tháng = <?php echo number_format($total_amount, 0, ',', '.'); ?> đồng.
            <?php endif; ?>
        </div>
        <div class="p-ind">(Bằng chữ: <em><?php echo $total_amount_words; ?></em>);</div>
        <div class="p-ind"><strong>Tổng giá trị hợp đồng: <?php echo number_format($total_amount, 0, ',', '.'); ?> đồng</strong> (Bằng chữ: <em><?php echo $total_amount_words; ?></em>)</div>
        
        <div class="p-ind"><strong>2.2 Phương thức thanh toán:</strong></div>
        <div class="p-ind">- Bên B phải thực hiện thanh toán tiền sử dụng diện tích bán hàng, các khoản phí, lệ phí, thuế theo quy định hiện hành vào Ngân sách nhà nước cho Tổ quản lý và Đội thuế theo đúng thời gian đã quy định. Nếu trễ phải nộp phạt quá hạn theo quy định.</div>
        <div class="p-ind">- Bên B phải thanh toán cho bên A các khoản dịch vụ nêu trên cũng như các khoản khác (nếu có) vào ngày <strong><?php echo htmlspecialchars($selectedConfig['payment_due_day'] ?? '10'); ?> hàng tháng. Trong vòng <?php echo htmlspecialchars($selectedConfig['payment_grace_period'] ?? '10'); ?> ngày</strong> sử dụng diện tích kinh doanh mà không thanh toán tiền thuê thì bên A có quyền hủy bỏ hợp đồng, thu hồi vị trí kinh doanh mà không cần sự thỏa thuận của bên B và xử lý theo quy định chung của Nhà nước.</div>
        
        <div class="p-ind"><strong>2.3 Thời hạn hợp đồng:</strong></div>
        <div class="p-ind">- Thời gian sử dụng diện tích bán hàng <?php echo $months; ?> tháng từ ngày <?php echo date('d/m/Y', $start_ts); ?> đến ngày <?php echo date('d/m/Y', $end_ts); ?>.</div>
        <div class="p-ind">- Hết thời hạn sử dụng diện tích bán hàng ghi trên hợp đồng mà cả hai bên có nhu cầu tiếp tục gia hạn hợp đồng thì tiến hành ký kết tiếp hợp đồng cho thời gian sử dụng diện tích bán hàng tiếp theo. Nếu không có nhu cầu gia hạn thì tiến hành thanh lý hợp đồng, Bên B hoàn trả lại mặt bằng diện tích bán hàng cho Bên A. Tuyệt đối không được phép mua bán, sang nhượng dưới bất kỳ hình thức nào.</div>

        <div class="section-title">Điều 3. Trách nhiệm của bên A</div>
        <div class="p-ind">- Bên A chính thức công nhận bên B được quyền sử dụng vị trí (sạp) đã thuê để kinh doanh khi bên B chấp hành đúng nội quy chợ và kinh doanh đúng ngành hàng đăng ký và nộp đủ tiền thuê mặt bằng.</div>
        <div class="p-ind">- Bên A tạo điều kiện thuận lợi cho bên B để kinh doanh mua bán như:</div>
        <div class="p-ind">+ Hướng dẫn bên B lập đầy đủ thủ tục hợp lệ theo quy định của Nhà nước để kinh doanh như: đăng ký giấy phép kinh doanh, kê khai nộp thuế theo quy định của pháp luật,....</div>
        <div class="p-ind">+ Thường xuyên tu sửa mặt bằng, chống mưa nắng, hệ thống thoát nước để bên B kinh doanh có hiệu quả.</div>

        <div class="section-title">Điều 4. Trách nhiệm của bên B</div>
        <div class="p-ind">- Chấp hành nghiêm chỉnh các chủ trương chính sách của Nhà nước và nội qui chợ, thực hiện đầy đủ thủ tục và nghĩa vụ với Nhà nước trước khi kinh doanh theo quy định của pháp luật hiện hành.</div>
        <div class="p-ind">- Kinh doanh đúng diện tích, vị trí, nội dung thỏa thuận trong hợp đồng sử dụng diện tích bán hàng tại chợ; không được tự ý tháo dỡ, sửa chữa trang thiết bị, cơi nới thùng sạp, sắp xếp hàng hóa ra ngoài diện tích ghi trong hợp đồng.</div>
        <div class="p-ind">- Không được hoán đổi địa điểm, vị trí đã thuê, lấn chiếm diện tích lối đi công cộng, chất xếp hàng hóa, che chắn bạt, dù làm che khuất ánh sáng, tầm nhìn và sự thông thoáng của chợ, ảnh hưởng đến các hộ kinh doanh khác và công tác phòng cháy chữa cháy tại chợ.</div>
        <div class="p-ind">- Không tự ý sang nhượng cho người khác vào kinh doanh, nếu sử dụng diện tích bán hàng để sang nhượng, mua bán dưới bất kỳ hình thức nào đều xem là bất hợp pháp. Tổ quản lý sẽ thanh lý hợp đồng và sẽ thu hồi vô điều kiện.</div>
        <div class="p-ind">- Trường hợp tạm nghỉ kinh doanh vẫn phải đóng tiền sử dụng diện tích bán hàng theo quy định. Nếu không còn kinh doanh tại chợ thì phải làm đơn trả lại mặt bằng. Trường hợp ốm đau, sinh đẻ... được phép cho người nhà ra bán thay nhưng phải làm giấy ủy quyền có xác nhận của chính quyền địa phương và được Tổ quản lý thống nhất bằng văn bản.</div>
        <div class="p-ind">- Hàng hóa, tài sản kinh doanh chủ hộ phải tự ý giữ gìn bảo quản. Tổ quản lý chợ không chịu trách nhiệm về mọi sự hư hỏng, cháy, mất mát xảy ra. Nếu có nhu cầu bảo quản hàng hóa thì bên B mua bảo hiểm hàng hóa với cơ quan bảo hiểm theo quy định.</div>

        <div class="section-title">Điều 5. Điều khoản chung</div>
        <div class="p-ind">Trong quá trình sử dụng mặt bằng kinh doanh mua bán, nếu có nhu cầu quy hoạch, sắp xếp lại vị trí kinh doanh hoặc do công trình phải cải tạo, sửa chữa, thì Tổ quản lý sẽ thu hồi lại mặt bằng kinh doanh và có trách nhiệm báo trước 30 ngày, chủ hộ kinh doanh tự giải quyết hàng hóa và trả lại mặt bằng cho Tổ quản lý theo quy định. Tổ quản lý chợ sẽ thanh toán lại số tiền cho các hộ kinh doanh đã nộp khi chưa hết thời hạn hợp đồng hoặc khấu trừ lại sau khi Chợ đã hoàn thành việc sửa chữa.</div>
        <div class="p-ind">Hai bên cam kết thực hiện đầy đủ các điều khoản của hợp đồng. Trong quá trình thực hiện có vấn đề phát sinh mới thì hai bên cùng thỏa thuận, bàn bạc và đi đến thống nhất để đảm bảo lợi ích đôi bên.</div>
        <div class="p-ind">Nếu một trong hai bên vi phạm hợp đồng, thì sẽ bị xử lý theo quy định của pháp luật hiện hành.</div>
        <div class="p-ind">Sau khi hợp đồng hết hiệu lực, nếu bên B có nhu cầu tiếp tục sử dụng diện tích bán hàng để kinh doanh thì hai bên cùng bàn bạc. Nếu thống nhất sẽ tiếp tục hợp đồng cho thời gian tiếp theo.</div>
        <div class="p-ind">Hợp đồng được lập thành 02 bản, mỗi bên giữ 01 bản, có giá trị pháp lý như nhau./.</div>

        <table class="signature-table">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div style="margin-top: 4pt; margin-bottom: 0; line-height: 1.15; text-align: center;">
                        <span class="signature-title">ĐẠI DIỆN BÊN B</span>
                    </div>
                    <div style="margin-top: 0; margin-bottom: 0; font-style: italic; font-size: 11pt; text-align: center; line-height: 1.15;">(Ký, ghi rõ họ tên)</div>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <div style="margin-top: 0; margin-bottom: 0; line-height: 1.15; text-align: center;">
                        <span class="signature-title">ĐẠI DIỆN BÊN A</span>
                    </div>
                    <div style="margin-top: 0; margin-bottom: 0; font-weight: bold; text-transform: uppercase; text-align: center; line-height: 1.15;"><?php echo htmlspecialchars(mb_strtoupper($selectedConfig['rep_a_position_1'] ?? '', 'UTF-8')); ?></div>
                </td>
            </tr>
        </table>
    </div>

    <script>
    document.getElementById('config_select')?.addEventListener('change', function() {
        var url = new URL(window.location.href);
        url.searchParams.set('config_id', this.value);
        window.location.href = url.toString();
    });
    </script>
</body>
</html>