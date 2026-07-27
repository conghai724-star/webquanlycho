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
$months = calculateMonths($contract['contract_start_date'], $contract['contract_end_date']);

$price_per_m2 = $contract['stall_area_size'] > 0 ? round($contract['price'] / $contract['stall_area_size']) : 0;
$total_amount = round($contract['price'] * $months);

$total_amount_words = ucfirst(trim(translateNumberToWords($total_amount))) . ' đồng chẵn';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>In hợp đồng - <?php echo htmlspecialchars($contract['contract_number']); ?></title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 13pt; /* Word standard is 13pt or 14pt */
            line-height: 1.35;
            color: #000;
            margin: 0;
            padding: 40px;
            background-color: #fff;
        }

        .no-print-bar {
            background-color: #f1f3f4;
            padding: 12px 24px;
            margin: -40px -40px 40px -40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dadce0;
        }

        .btn-print {
            background-color: #1a73e8;
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
            background-color: #1557b0;
        }

        .paper {
            width: 170mm;
            margin: 0 auto;
        }

        /* Centralized paragraph and block styling to prevent double spacing */
        .paper div, .paper p {
            margin-top: 0;
            margin-bottom: 6px; /* standard 6pt spacing in Word */
            padding: 0;
            text-align: justify;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .left-header {
            width: 45%;
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
        }

        .right-header {
            width: 55%;
            text-align: center;
            font-size: 11pt;
        }

        .right-header .national-title {
            font-weight: bold;
            font-size: 12pt;
            text-transform: uppercase;
        }

        .national-subtitle {
            font-weight: bold;
            font-size: 11pt;
        }

        .line-dec {
            width: 80px;
            margin: 3px auto 0 auto;
            border-bottom: 1px solid #000;
        }

        .line-dec-long {
            width: 150px;
            margin: 3px auto 0 auto;
            border-bottom: 1px solid #000;
        }

        .doc-title {
            text-align: center;
            font-weight: bold;
            font-size: 15pt;
            margin-top: 15px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .doc-subtitle {
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .section-title {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 4px;
        }

        .indent-block {
            margin-left: 20px;
            margin-bottom: 6px;
        }

        .signature-table {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 65px;
        }

        @media print {
            .no-print-bar {
                display: none;
            }
            body {
                padding: 0;
            }
            @page {
                size: A4;
                margin: 20mm 15mm 20mm 15mm;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <span style="font-size: 14px; font-weight: 500; color: #3c4043;">Xem thử bản in hợp đồng</span>
        <div style="display: flex; gap: 8px;">
            <button onclick="window.print()" class="btn-print">
                🖨️ In hợp đồng
            </button>
            <button onclick="window.close()" class="btn-print" style="background-color: #5f6368;">
                Đóng
            </button>
        </div>
    </div>

    <div class="paper">
        <table class="header-table">
            <tr>
                <td class="left-header">
                    UBND PHƯỜNG KON TUM<br>
                    TỔ QUẢN LÝ CHỢ HẠNG 3
                    <div class="line-dec"></div>
                </td>
                <td class="right-header">
                    <span class="national-title">CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</span><br>
                    <span class="national-subtitle">Độc lập - Tự do - Hạnh phúc</span>
                    <div class="line-dec-long"></div>
                    <div style="margin-top: 5px; font-style: italic; font-size: 10pt;">
                        Kon Tum, ngày <?php echo date('d', $start_ts); ?> tháng <?php echo date('m', $start_ts); ?> năm <?php echo date('Y', $start_ts); ?>
                    </div>
                </td>
            </tr>
        </table>

        <div style="font-size: 11pt; margin-bottom: 12px;">
            Số: <?php echo htmlspecialchars($contract['contract_number']); ?>/HĐTQLC
        </div>

        <div class="doc-title">HỢP ĐỒNG DỊCH VỤ</div>
        <div class="doc-subtitle">SỬ DỤNG DIỆN TÍCH BÁN HÀNG TẠI CÁC CHỢ HẠNG 3<br>PHƯỜNG KON TUM</div>

        <div style="font-style: italic; margin-bottom: 12px; font-size: 11pt;">
            - Căn cứ Bộ Luật dân sự ngày 24 tháng 11 năm 2015;<br>
            - Căn cứ Nghị định số 60/2024/NĐ-CP ngày 05 tháng 6 năm 2024 của Chính phủ về phát triển và quản lý chợ;<br>
            - Căn cứ Quyết định số 131/QĐ-UBND ngày 06/8/2025 của UBND phường Kon Tum về việc thành lập Tổ quản lý các chợ hạng 3 trên địa bàn phường Kon Tum;<br>
            - Căn cứ nhu cầu sử dụng diện tích bán hàng của hộ kinh doanh và xét khả năng đáp ứng của đơn vị.
        </div>

        <div>
            Hôm nay, ngày <?php echo date('d', $start_ts); ?> tháng <?php echo date('m', $start_ts); ?> năm <?php echo date('Y', $start_ts); ?> chúng tôi gồm có:
        </div>

        <div class="section-title">Bên A: Đại diện Tổ quản lý các chợ hạng 3 trên địa bàn phường Kon Tum (Bên cho thuê)</div>
        <div class="indent-block">
            1. Ông: <strong>Phan Thành Trung</strong>; Chức vụ: Tổ trưởng;<br>
            2. Bà: <strong>Trương Thảo Linh</strong>; Chức vụ: Tài chính - Kế Toán.<br>
            Địa chỉ: 342 Nguyễn Huệ, phường Kon Tum, tỉnh Quảng Ngãi.<br>
            Tài khoản: 3723.0.1153520.00000 Tại: Phòng Giao dịch số 23 - KBNN khu vực XV.
        </div>

        <div class="section-title">Bên B: Đại diện người thuê sử dụng mặt bằng (Bên thuê)</div>
        <div class="indent-block">
            Ông (Bà): <strong><?php echo mb_strtoupper(htmlspecialchars($contract['trader_name']), 'UTF-8'); ?></strong><br>
            Địa chỉ thường trú: <?php echo htmlspecialchars($contract['trader_address'] ?: 'Chưa cập nhật'); ?><br>
            Điện thoại: <?php echo htmlspecialchars($contract['trader_phone']); ?><br>
            Căn cước công dân số: <?php echo htmlspecialchars($contract['trader_cccd'] ?: 'Chưa cập nhật'); ?>
        </div>

        <div>
            Sau khi hai bên bàn bạc và đi đến thống nhất về nội dung thu tiền dịch vụ sử dụng diện tích bán hàng và thu gom, vận chuyển rác thải tại các chợ hạng 3 trên địa bàn phường Kon Tum với các điều khoản như sau:
        </div>

        <div class="section-title">Điều 1. Nội dung hợp đồng</div>
        <div class="indent-block">
            Bên A thỏa thuận cho bên B thuê sử dụng mặt bằng bán hàng tại các chợ hạng 3 trên địa bàn phường Kon Tum:<br>
            - Vị trí thuê: <strong><?php echo htmlspecialchars($contract['stall_code']); ?></strong> (Khu vực: <?php echo htmlspecialchars($contract['area_name']); ?>)<br>
            - Diện tích sử dụng: <strong><?php echo htmlspecialchars($contract['stall_area_size']); ?> m²</strong><br>
            - Ngành hàng kinh doanh: <strong><?php echo htmlspecialchars($contract['stall_type'] ?: 'Hàng khô'); ?></strong>
        </div>

        <div class="section-title">Điều 2. Giá dịch vụ, phương thức thanh toán và thời hạn hợp đồng</div>
        <div class="indent-block">
            <strong>2.1 Giá dịch vụ:</strong><br>
            - Đơn giá thuê: <?php echo number_format($price_per_m2, 0, ',', '.'); ?> đồng/m²/tháng.<br>
            - Thành tiền mỗi tháng: <strong><?php echo number_format($contract['price'], 0, ',', '.'); ?> đồng/tháng</strong>.<br>
            - Tổng giá trị hợp đồng thuê mặt bằng (cho <?php echo $months; ?> tháng): <strong><?php echo number_format($total_amount, 0, ',', '.'); ?> đồng</strong>.<br>
            - <em>(Bằng chữ: <?php echo $total_amount_words; ?>)</em>.<br>
            - Tiền đặt cọc thế chân mặt bằng: <strong><?php echo number_format($contract['contract_deposit'], 0, ',', '.'); ?> đồng</strong>.<br><br>
            
            <strong>2.2 Phương thức thanh toán:</strong><br>
            - Bên B phải thực hiện thanh toán tiền sử dụng diện tích bán hàng đúng hạn vào ngày 10 hàng tháng.<br>
            - Trong vòng 10 ngày sử dụng diện tích kinh doanh mà không thanh toán tiền thuê thì bên A có quyền hủy bỏ hợp đồng, thu hồi vị trí kinh doanh mà không cần sự thỏa thuận của bên B.<br><br>
            
            <strong>2.3 Thời hạn hợp đồng:</strong><br>
            - Thời gian sử dụng diện tích bán hàng: <strong><?php echo $months; ?> tháng</strong> từ ngày <strong><?php echo date('d/m/Y', $start_ts); ?></strong> đến ngày <strong><?php echo date('d/m/Y', $end_ts); ?></strong>.<br>
            - Hết thời hạn hợp đồng, nếu hai bên có nhu cầu tiếp tục gia hạn thì tiến hành ký kết phụ lục hoặc hợp đồng mới. Nếu không gia hạn, Bên B hoàn trả mặt bằng sạch cho Bên A. Tuyệt đối không được phép mua bán, sang nhượng dưới bất kỳ hình thức nào.
        </div>

        <div class="section-title">Điều 3. Trách nhiệm của bên A</div>
        <div class="indent-block">
            - Bàn giao mặt bằng sạp kinh doanh đúng diện tích và vị trí cho bên B.<br>
            - Đảm bảo an ninh trật tự chung và tạo điều kiện thuận lợi cho hoạt động kinh doanh hợp pháp của Bên B.
        </div>

        <div class="section-title">Điều 4. Trách nhiệm của bên B</div>
        <div class="indent-block">
            - Sử dụng mặt bằng đúng mục đích kinh doanh, giữ gìn vệ sinh chung và chấp hành nghiêm chỉnh nội quy chợ.<br>
            - Không tự ý cơi nới, sửa chữa, thay đổi hiện trạng sạp khi chưa được sự đồng ý của bên A.<br>
            - Thanh toán tiền thuê và các chi phí điện, nước đầy đủ, đúng thời hạn.
        </div>

        <div class="section-title">Điều 5. Điều khoản chung</div>
        <div class="indent-block">
            - Hai bên cam kết thực hiện đầy đủ các điều khoản của hợp đồng. Mọi phát sinh tranh chấp sẽ được ưu tiên bàn bạc, giải quyết bằng thương lượng.<br>
            - Hợp đồng được lập thành 02 bản có giá trị pháp lý như nhau, mỗi bên giữ 01 bản để thực hiện.
        </div>

        <table class="signature-table">
            <tr>
                <td>
                    <span class="signature-title">ĐẠI DIỆN BÊN B</span><br>
                    <em>(Ký, ghi rõ họ tên)</em>
                </td>
                <td>
                    T/M TỔ QUẢN LÝ CHỢ HẠNG 3 (BÊN A)<br>
                    <span class="signature-title">TỔ TRƯỞNG</span><br>
                    <em>(Ký, đóng dấu và ghi rõ họ tên)</em>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
