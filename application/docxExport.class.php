<?php
/**
 * Class docxExport
 * Xuất file Microsoft Word (.docx) chuẩn theo mẫu Hợp đồng chợ Quyết Thắng (Nghị định 30/2020/NĐ-CP)
 * Tương thích 100% với Microsoft Word (mọi phiên bản), WPS Office, LibreOffice, Google Docs.
 */
class docxExport {

    public static function translateNumberToWords($number) {
        $dictionary = [
            0 => 'không', 1 => 'một', 2 => 'hai', 3 => 'ba', 4 => 'bốn',
            5 => 'năm', 6 => 'sáu', 7 => 'bảy', 8 => 'tám', 9 => 'chín',
            10 => 'mười', 11 => 'mười một', 12 => 'mười hai', 13 => 'mười ba',
            14 => 'mười bốn', 15 => 'mười lăm', 16 => 'mười sáu', 17 => 'mười bảy',
            18 => 'mười tám', 19 => 'mười chín', 20 => 'hai mươi', 30 => 'ba mươi',
            40 => 'bốn mươi', 50 => 'năm mươi', 60 => 'sáu mươi', 70 => 'bảy mươi',
            80 => 'tám mươi', 90 => 'chín mươi', 100 => 'trăm', 1000 => 'ngàn',
            1000000 => 'triệu', 1000000000 => 'tỷ'
        ];

        if (!is_numeric($number)) return '';
        if ($number < 0) return 'âm ' . self::translateNumberToWords(abs($number));

        $string = '';
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens  = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= ($units == 1) ? ' mốt' : (($units == 5) ? ' lăm' : ' ' . $dictionary[$units]);
                }
                break;
            case $number < 1000:
                $hundreds  = (int)($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' trăm';
                if ($remainder) {
                    $string .= ($remainder < 10) ? ' lẻ ' . self::translateNumberToWords($remainder) : ' ' . self::translateNumberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::translateNumberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= ($remainder < 100) ? ' lẻ ' : ' ';
                    $string .= self::translateNumberToWords($remainder);
                }
                break;
        }

        return $string;
    }

    public static function calculateMonths($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        $totalMonths = ($interval->y * 12) + $interval->m;
        if ($interval->d > 0) {
            $totalMonths += round($interval->d / 30, 1);
        }
        return $totalMonths ?: 12;
    }

    /**
     * Tạo file docx và gửi stream về trình duyệt download
     */
    public static function exportContract($contract, $config = []) {
        $start_ts = strtotime($contract['contract_start_date']);
        $end_ts = strtotime($contract['contract_end_date']);
        $sign_ts = !empty($contract['contract_sign_date']) ? strtotime($contract['contract_sign_date']) : $start_ts;
        $months = self::calculateMonths($contract['contract_start_date'], $contract['contract_end_date']);

        $area_size = (float)($contract['stall_area_size'] ?? 0);
        $price_per_m2 = (float)($contract['price'] ?? 0);

        if ($area_size > 0) {
            $monthly_rent = round($price_per_m2 * $area_size);
            $total_amount = round($monthly_rent * $months);
        } else {
            $monthly_rent = $price_per_m2;
            $total_amount = round($monthly_rent * $months);
        }

        $total_amount_words = ucfirst(trim(self::translateNumberToWords($total_amount))) . ' đồng chẵn';

        // Kích thước dài x rộng
        $size = (float)($contract['stall_area_size'] ?? 4);
        $width = isset($contract['stall_map_coordinate_y']) && (float)$contract['stall_map_coordinate_y'] > 0 ? (float)$contract['stall_map_coordinate_y'] : null;
        $length = isset($contract['stall_map_coordinate_x']) && (float)$contract['stall_map_coordinate_x'] > 0 ? (float)$contract['stall_map_coordinate_x'] : null;

        if ($width === null || $length === null) {
            if ($size == 4) { $width = 2; $length = 2; }
            elseif ($size == 6) { $width = 2; $length = 3; }
            elseif ($size == 8) { $width = 2; $length = 4; }
            elseif ($size == 9) { $width = 3; $length = 3; }
            else {
                $width = round(sqrt($size), 1);
                $length = $width > 0 ? round($size / $width, 1) : 0;
            }
        }

        $calc = [
            'start_ts' => $start_ts,
            'end_ts' => $end_ts,
            'sign_ts' => $sign_ts,
            'months' => $months,
            'area_size' => $area_size,
            'price_per_m2' => $price_per_m2,
            'monthly_rent' => $monthly_rent,
            'total_amount' => $total_amount,
            'total_amount_words' => $total_amount_words,
            'width' => $width,
            'length' => $length
        ];

        // Xây dựng nội dung XML
        $xml = self::buildDocumentXml($contract, $config, $calc);

        // Chuẩn bị file docx từ mẫu chuẩn
        $templatePath = __SITE_PATH . '/template/app/contract/contract_template.docx';
        if (!file_exists($templatePath)) {
            $templatePath = __SITE_PATH . '/z/HỢP ĐỒNG MẪU CHỢ QTR.docx';
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'docx_');
        if (file_exists($templatePath)) {
            copy($templatePath, $tempFile);
            $zip = new ZipArchive();
            if ($zip->open($tempFile) === true) {
                $zip->addFromString('word/document.xml', $xml);
                $zip->close();
            } else {
                throw new Exception("Không thể mở tệp mẫu DOCX.");
            }
        } else {
            // Fallback tạo mới
            self::createFallbackZip($tempFile, $xml);
        }

        // Tên file tải về
        $cleanContractNum = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $contract['contract_number'] ?: 'HopDong');
        $filename = 'HopDong_' . $cleanContractNum . '.docx';

        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate');
        header('Pragma: public');
        header('Expires: 0');

        readfile($tempFile);
        @unlink($tempFile);
        exit();
    }

    private static function e($str) {
        return htmlspecialchars((string)$str, ENT_XML1, 'UTF-8');
    }

    private static function run($text, $options = []) {
        $bold = !empty($options['bold']) ? '<w:b/><w:bCs/>' : '';
        $italic = !empty($options['italic']) ? '<w:i/><w:iCs/>' : '';
        $underline = !empty($options['underline']) ? '<w:u w:val="single"/>' : '';
        $sizeVal = !empty($options['size']) ? ($options['size'] * 2) : 28;
        $size = '<w:sz w:val="' . $sizeVal . '"/><w:szCs w:val="' . $sizeVal . '"/>';

        return '<w:r>
            <w:rPr>
                <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman" w:cs="Times New Roman"/>
                ' . $bold . '
                ' . $italic . '
                ' . $underline . '
                ' . $size . '
                <w:lang w:val="vi-VN"/>
            </w:rPr>
            <w:t xml:space="preserve">' . self::e($text) . '</w:t>
        </w:r>';
    }

    private static function p($runs, $options = []) {
        $align = $options['align'] ?? 'both'; // both = justify, center, left, right
        $indent = !empty($options['indent']) ? '<w:ind w:firstLine="567"/>' : ''; // 567 dxa = 1cm
        $before = isset($options['before']) ? (int)$options['before'] : 60;
        $after = isset($options['after']) ? (int)$options['after'] : 60;
        $line = isset($options['line']) ? (int)$options['line'] : 276; // 1.15 line spacing

        $spacing = '<w:spacing w:before="' . $before . '" w:after="' . $after . '" w:line="' . $line . '" w:lineRule="auto"/>';

        $runsXml = '';
        if (is_array($runs)) {
            foreach ($runs as $r) {
                if (is_string($r)) {
                    $runsXml .= $r;
                }
            }
        } else {
            $runsXml = $runs;
        }

        return '<w:p>
            <w:pPr>
                <w:jc w:val="' . $align . '"/>
                ' . $indent . '
                ' . $spacing . '
                <w:rPr>
                    <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman" w:cs="Times New Roman"/>
                    <w:sz w:val="28"/>
                    <w:szCs w:val="28"/>
                    <w:lang w:val="vi-VN"/>
                </w:rPr>
            </w:pPr>
            ' . $runsXml . '
        </w:p>';
    }

    private static function pSimple($text, $options = []) {
        $runOpt = [
            'bold' => $options['bold'] ?? false,
            'italic' => $options['italic'] ?? false,
            'underline' => $options['underline'] ?? false,
            'size' => $options['size'] ?? 14
        ];
        return self::p(self::run($text, $runOpt), $options);
    }

    private static function buildDocumentXml($contract, $config, $calc) {
        $signD = date('d', $calc['sign_ts']);
        $signM = date('m', $calc['sign_ts']);
        $signY = date('Y', $calc['sign_ts']);
        $startStr = date('d/m/Y', $calc['start_ts']);
        $endStr = date('d/m/Y', $calc['end_ts']);

        $govAgency1 = !empty($config['gov_agency_1']) ? $config['gov_agency_1'] : 'UBND PHƯỜNG KON TUM';
        $govAgency2 = !empty($config['gov_agency_2']) ? $config['gov_agency_2'] : 'PHÒNG KT,HT&ĐT';
        $titleSuffix = !empty($config['contract_title_suffix']) ? $config['contract_title_suffix'] : 'TẠI CÁC CHỢ HẠNG 3 PHƯỜNG KON TUM';
        $repAHeader = !empty($config['rep_a_header']) ? $config['rep_a_header'] : 'Đại diện Tổ quản lý các chợ hạng 3 trên địa bàn phường Kon Tum - Trưởng phòng Kinh tế, Hạ tầng và Đô thị (gọi tắt là Bên A):';
        $repAName1 = $config['rep_a_name_1'] ?? 'Phan Thành Trung';
        $repAPos1 = $config['rep_a_position_1'] ?? 'Tổ trưởng';
        $repAName2 = $config['rep_a_name_2'] ?? 'Trương Thảo Linh';
        $repAPos2 = $config['rep_a_position_2'] ?? 'Tài chính - Kế Toán';
        $repAAddress = $config['rep_a_address'] ?? '342 Nguyễn Huệ, phường Kon Tum, tỉnh Quảng Ngãi';
        $repAPhone = $config['rep_a_phone'] ?? '';
        $repAFax = $config['rep_a_fax'] ?? '';
        $repABankAcc = $config['rep_a_bank_account'] ?? '';
        $repABankName = $config['rep_a_bank_name'] ?? '';
        $payDueDay = $config['payment_due_day'] ?? '10';
        $payGrace = $config['payment_grace_period'] ?? '10';

        $traderName = mb_strtoupper($contract['trader_name'] ?? '', 'UTF-8');
        $traderAddress = !empty($contract['trader_address']) ? $contract['trader_address'] : '..........................................................................................';
        $traderPhone = !empty($contract['trader_phone']) ? $contract['trader_phone'] : '....................................................................................................';
        $traderCccd = !empty($contract['trader_cccd']) ? $contract['trader_cccd'] : '................................................................................';
        $contractNum = $contract['contract_number'] ?: '......../2026/HĐTQLC';
        $areaName = $contract['area_name'] ?? '';
        $stallType = ($contract['stall_type'] ?? '') ?: 'Hàng khô';

        $body = '';

        // 1. Header Table: Bảng 3 hàng x 2 cột (3x2 Table theo Nghị định 30/2020/NĐ-CP - căn giữa bảng, trên dưới 0pt)
        $body .= '<w:tbl>
            <w:tblPr>
                <w:tblStyle w:val="TableGrid"/>
                <w:tblW w:w="9782" w:type="dxa"/>
                <w:jc w:val="center"/>
                <w:tblInd w:w="-176" w:type="dxa"/>
                <w:tblBorders>
                    <w:top w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:left w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:bottom w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:right w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:insideH w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:insideV w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                </w:tblBorders>
                <w:tblCellMar>
                    <w:top w:w="0" w:type="dxa"/>
                    <w:bottom w:w="0" w:type="dxa"/>
                    <w:left w:w="108" w:type="dxa"/>
                    <w:right w:w="108" w:type="dxa"/>
                </w:tblCellMar>
            </w:tblPr>
            <w:tblGrid>
                <w:gridCol w:w="4112"/>
                <w:gridCol w:w="5670"/>
            </w:tblGrid>
            <!-- HÀNG 1 -->
            <w:tr>
                <w:tc>
                    <w:tcPr><w:tcW w:w="4112" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
                    ' . self::pSimple($govAgency1, ['align' => 'center', 'bold' => false, 'size' => 13, 'before' => 0, 'after' => 0]) . '
                </w:tc>
                <w:tc>
                    <w:tcPr><w:tcW w:w="5670" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
                    ' . self::pSimple('CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM', ['align' => 'center', 'bold' => true, 'size' => 13, 'before' => 0, 'after' => 0]) . '
                </w:tc>
            </w:tr>
            <!-- HÀNG 2: Có đường kẻ nét vẽ Shapes theo NĐ 30/2020/NĐ-CP -->
            <w:tr>
                <w:trPr><w:trHeight w:val="377"/></w:trPr>
                <w:tc>
                    <w:tcPr><w:tcW w:w="4112" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
                    <w:p>
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="auto"/>
                            <w:rPr><w:b/><w:sz w:val="26"/><w:szCs w:val="26"/><w:lang w:val="vi-VN"/></w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr><w:b/><w:sz w:val="26"/><w:szCs w:val="26"/><w:lang w:val="vi-VN"/></w:rPr>
                            <w:t>' . self::e($govAgency2) . '</w:t>
                        </w:r>
                        <w:r>
                            <w:rPr><w:noProof/><w:sz w:val="28"/><w:szCs w:val="28"/></w:rPr>
                            <mc:AlternateContent>
                                <mc:Choice Requires="wps">
                                    <w:drawing>
                                        <wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="251660288" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">
                                            <wp:simplePos x="0" y="0"/>
                                            <wp:positionH relativeFrom="column"><wp:posOffset>744855</wp:posOffset></wp:positionH>
                                            <wp:positionV relativeFrom="paragraph"><wp:posOffset>206375</wp:posOffset></wp:positionV>
                                            <wp:extent cx="1066800" cy="0"/>
                                            <wp:effectExtent l="8255" t="11430" r="10795" b="7620"/>
                                            <wp:wrapNone/>
                                            <wp:docPr id="3" name="Line 2"/>
                                            <wp:cNvGraphicFramePr><a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"/></wp:cNvGraphicFramePr>
                                            <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                                                <a:graphicData uri="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">
                                                    <wps:wsp>
                                                        <wps:cNvCnPr><a:cxnSpLocks noChangeShapeType="1"/></wps:cNvCnPr>
                                                        <wps:spPr bwMode="auto">
                                                            <a:xfrm><a:off x="0" y="0"/><a:ext cx="1066800" cy="0"/></a:xfrm>
                                                            <a:prstGeom prst="line"><a:avLst/></a:prstGeom>
                                                            <a:noFill/>
                                                            <a:ln w="9525"><a:solidFill><a:srgbClr val="000000"/></a:solidFill><a:round/><a:headEnd/><a:tailEnd/></a:ln>
                                                        </wps:spPr>
                                                        <wps:bodyPr/>
                                                    </wps:wsp>
                                                </a:graphicData>
                                            </a:graphic>
                                        </wp:anchor>
                                    </w:drawing>
                                </mc:Choice>
                                <mc:Fallback>
                                    <w:pict>
                                        <v:line id="Line 2" style="position:absolute;z-index:251660288;visibility:visible;mso-wrap-style:square;" from="58.65pt,16.25pt" to="142.65pt,16.25pt"/>
                                    </w:pict>
                                </mc:Fallback>
                            </mc:AlternateContent>
                        </w:r>
                    </w:p>
                </w:tc>
                <w:tc>
                    <w:tcPr><w:tcW w:w="5670" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
                    <w:p>
                        <w:pPr>
                            <w:jc w:val="center"/>
                            <w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="auto"/>
                            <w:rPr><w:b/><w:sz w:val="28"/><w:szCs w:val="28"/><w:lang w:val="vi-VN"/></w:rPr>
                        </w:pPr>
                        <w:r>
                            <w:rPr><w:b/><w:sz w:val="28"/><w:szCs w:val="28"/><w:lang w:val="vi-VN"/></w:rPr>
                            <w:t>Độc lập - Tự do - Hạnh phúc</w:t>
                        </w:r>
                        <w:r>
                            <w:rPr><w:b/><w:noProof/><w:sz w:val="28"/><w:szCs w:val="28"/></w:rPr>
                            <mc:AlternateContent>
                                <mc:Choice Requires="wps">
                                    <w:drawing>
                                        <wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="251661312" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">
                                            <wp:simplePos x="0" y="0"/>
                                            <wp:positionH relativeFrom="column"><wp:posOffset>689610</wp:posOffset></wp:positionH>
                                            <wp:positionV relativeFrom="paragraph"><wp:posOffset>206375</wp:posOffset></wp:positionV>
                                            <wp:extent cx="2055495" cy="0"/>
                                            <wp:effectExtent l="11430" t="11430" r="9525" b="7620"/>
                                            <wp:wrapNone/>
                                            <wp:docPr id="2" name="Line 3"/>
                                            <wp:cNvGraphicFramePr><a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"/></wp:cNvGraphicFramePr>
                                            <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                                                <a:graphicData uri="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">
                                                    <wps:wsp>
                                                        <wps:cNvCnPr><a:cxnSpLocks noChangeShapeType="1"/></wps:cNvCnPr>
                                                        <wps:spPr bwMode="auto">
                                                            <a:xfrm><a:off x="0" y="0"/><a:ext cx="2055495" cy="0"/></a:xfrm>
                                                            <a:prstGeom prst="line"><a:avLst/></a:prstGeom>
                                                            <a:noFill/>
                                                            <a:ln w="9525"><a:solidFill><a:srgbClr val="000000"/></a:solidFill><a:round/><a:headEnd/><a:tailEnd/></a:ln>
                                                        </wps:spPr>
                                                        <wps:bodyPr/>
                                                    </wps:wsp>
                                                </a:graphicData>
                                            </a:graphic>
                                        </wp:anchor>
                                    </w:drawing>
                                </mc:Choice>
                                <mc:Fallback>
                                    <w:pict>
                                        <v:line id="Line 3" style="position:absolute;z-index:251661312;visibility:visible;mso-wrap-style:square;" from="54.3pt,16.25pt" to="216.15pt,16.25pt"/>
                                    </w:pict>
                                </mc:Fallback>
                            </mc:AlternateContent>
                        </w:r>
                    </w:p>
                </w:tc>
            </w:tr>
            <!-- HÀNG 3 -->
            <w:tr>
                <w:tc>
                    <w:tcPr><w:tcW w:w="4112" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
                    ' . self::pSimple('Số: ' . $contractNum, ['align' => 'center', 'bold' => false, 'size' => 14, 'before' => 0, 'after' => 0]) . '
                </w:tc>
                <w:tc>
                    <w:tcPr><w:tcW w:w="5670" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
                    ' . self::pSimple('', ['align' => 'center', 'size' => 14, 'before' => 0, 'after' => 0]) . '
                </w:tc>
            </w:tr>
        </w:tbl>';

        // Enter bảng (1 dòng cách sau bảng)
        $body .= '<w:p><w:pPr><w:jc w:val="center"/><w:spacing w:before="0" w:after="0" w:line="240" w:lineRule="auto"/><w:rPr><w:sz w:val="28"/><w:szCs w:val="28"/></w:rPr></w:pPr></w:p>';

        // 2. Tiêu đề Hợp đồng (trên 0pt, dưới 2pt = before 0, after 40 dxa) có đường nét vẽ Line Shape
        $body .= self::pSimple('HỢP ĐỒNG DỊCH VỤ', ['align' => 'center', 'bold' => true, 'size' => 14, 'before' => 0, 'after' => 40]);
        $body .= self::pSimple('SỬ DỤNG DIỆN TÍCH BÁN HÀNG ' . $titleSuffix, ['align' => 'center', 'bold' => true, 'size' => 14, 'before' => 0, 'after' => 40]);
        
        $body .= '<w:p>
            <w:pPr>
                <w:jc w:val="center"/>
                <w:spacing w:before="40" w:after="140" w:line="240" w:lineRule="auto"/>
            </w:pPr>
            <w:r>
                <w:rPr><w:noProof/><w:sz w:val="28"/><w:szCs w:val="28"/></w:rPr>
                <mc:AlternateContent>
                    <mc:Choice Requires="wps">
                        <w:drawing>
                            <wp:anchor distT="0" distB="0" distL="114300" distR="114300" simplePos="0" relativeHeight="251662336" behindDoc="0" locked="0" layoutInCell="1" allowOverlap="1">
                                <wp:simplePos x="0" y="0"/>
                                <wp:positionH relativeFrom="column"><wp:posOffset>2282190</wp:posOffset></wp:positionH>
                                <wp:positionV relativeFrom="paragraph"><wp:posOffset>9525</wp:posOffset></wp:positionV>
                                <wp:extent cx="1171575" cy="0"/>
                                <wp:effectExtent l="9525" t="6985" r="9525" b="12065"/>
                                <wp:wrapNone/>
                                <wp:docPr id="1" name="AutoShape 4"/>
                                <wp:cNvGraphicFramePr><a:graphicFrameLocks xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main"/></wp:cNvGraphicFramePr>
                                <a:graphic xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
                                    <a:graphicData uri="http://schemas.microsoft.com/office/word/2010/wordprocessingShape">
                                        <wps:wsp>
                                            <wps:cNvCnPr><a:cxnSpLocks noChangeShapeType="1"/></wps:cNvCnPr>
                                            <wps:spPr bwMode="auto">
                                                <a:xfrm><a:off x="0" y="0"/><a:ext cx="1171575" cy="0"/></a:xfrm>
                                                <a:prstGeom prst="straightConnector1"><a:avLst/></a:prstGeom>
                                                <a:noFill/>
                                                <a:ln w="9525"><a:solidFill><a:srgbClr val="000000"/></a:solidFill><a:round/><a:headEnd/><a:tailEnd/></a:ln>
                                            </wps:spPr>
                                            <wps:bodyPr/>
                                        </wps:wsp>
                                    </a:graphicData>
                                </a:graphic>
                            </wp:anchor>
                        </w:drawing>
                    </mc:Choice>
                    <mc:Fallback>
                        <w:pict>
                            <v:line id="AutoShape 4" style="position:absolute;margin-left:179.7pt;margin-top:.75pt;width:92.25pt;height:0;z-index:251662336;visibility:visible;mso-wrap-style:square;" from="0,0" to="92.25pt,0"/>
                        </w:pict>
                    </mc:Fallback>
                </mc:AlternateContent>
            </w:r>
        </w:p>';

        // 3. Căn cứ pháp lý (trên dưới 3pt = before 60, after 60 dxa)
        $legalGrounds = explode("\n", $config['legal_grounds'] ?? '');
        if (empty(array_filter(array_map('trim', $legalGrounds)))) {
            $legalGrounds = [
                'Căn cứ Bộ Luật dân sự ngày 24 tháng 11 năm 2015;',
                'Căn cứ Nghị định số 60/2024/NĐ-CP ngày 05 tháng 6 năm 2024 của Chính phủ về phát triển và quản lý chợ;',
                'Căn cứ Quyết định số 131/QĐ-UBND ngày 06/8/2025 của UBND phường Kon Tum về việc thành lập Tổ quản lý các chợ hạng 3 trên địa bàn phường Kon Tum;',
                'Căn cứ nhu cầu sử dụng diện tích bán hàng của hộ kinh doanh và xét khả năng đáp ứng của đơn vị.'
            ];
        }
        foreach ($legalGrounds as $g) {
            $g = trim($g);
            if ($g !== '') {
                $body .= self::pSimple($g, ['align' => 'both', 'italic' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
            }
        }

        // 4. Ngày tháng ký
        $body .= self::pSimple("Hôm nay, ngày {$signD} tháng {$signM} năm {$signY} chúng tôi gồm có:", ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        // 5. Đại diện Bên A
        $body .= self::pSimple($repAHeader, ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        if (!empty($repAName1)) {
            $body .= self::pSimple("1. Ông (Bà): {$repAName1}; Chức vụ: {$repAPos1};", ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        }
        if (!empty($repAName2)) {
            $body .= self::pSimple("2. Ông (Bà): {$repAName2}; Chức vụ: {$repAPos2}.", ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        }
        $body .= self::pSimple("Địa chỉ: {$repAAddress}", ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        
        $phoneFaxStr = '';
        if (!empty($repAPhone)) $phoneFaxStr .= "Điện thoại: {$repAPhone}";
        if (!empty($repAFax)) $phoneFaxStr .= "      Fax: {$repAFax}";
        if (!empty($phoneFaxStr)) {
            $body .= self::pSimple($phoneFaxStr, ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        }
        if (!empty($repABankAcc)) {
            $bankStr = "Tài khoản: {$repABankAcc}";
            if (!empty($repABankName)) $bankStr .= " Tại: {$repABankName}";
            $body .= self::pSimple($bankStr, ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        }

        // 6. Đại diện Bên B
        $body .= self::pSimple('Đại diện người thuê sử dụng mặt bằng (gọi tắt là Bên B):', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        
        // Dòng họ tên Bên B: "Ông (Bà): " (thường) + "TÊN TIỂU THƯƠNG" (In hoa đậm)
        $body .= self::p([
            self::run('Ông (Bà): ', ['size' => 14]),
            self::run($traderName, ['bold' => true, 'size' => 14])
        ], ['align' => 'left', 'indent' => true, 'before' => 60, 'after' => 60]);

        $body .= self::pSimple("Địa chỉ thường trú: {$traderAddress}", ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple("Điện thoại: {$traderPhone}", ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple("Căn cước công dân số: {$traderCccd}", ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        // 7. Lời mở đầu điều khoản
        $body .= self::pSimple("Sau khi hai bên bàn bạc và đi đến thống nhất về nội dung thu tiền dịch vụ sử dụng diện tích bán hàng và thu gom, vận chuyển rác thải năm {$signY} gồm có các điều khoản như sau:", ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        // 8. Điều 1
        $body .= self::pSimple('Điều 1. Nội dung hợp đồng', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple("Bên A thỏa thuận cho bên B thuê sử dụng mặt bằng bán hàng năm {$signY}: 01 sạp (lô); vị trí: {$areaName}, cụ thể như sau:", ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple("Diện tích sử dụng: {$calc['area_size']} m² (chiều dài {$calc['length']}m x chiều rộng {$calc['width']}m)", ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple("Mặt hàng kinh doanh: {$stallType}", ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        // 9. Điều 2
        $body .= self::pSimple('Điều 2. Giá dịch vụ, phương thức thanh toán và thời hạn hợp đồng.', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('2.1 Giá dịch vụ:', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('Thực hiện theo giá dịch vụ của cơ quan có thẩm quyền ban hành theo từng thời điểm, khi thay đổi vị trí hoặc tăng, giảm diện tích mặt bằng thì hai bên sẽ thống nhất điều chỉnh giá thuê mặt bằng theo thực tế và các quy định hiện hành của Nhà nước:', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Dịch vụ sử dụng diện tích bán hàng:', ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        if ($calc['area_size'] > 0) {
            $priceText = number_format($calc['price_per_m2'], 0, ',', '.') . ' đồng/m² x ' . $calc['area_size'] . 'm² x ' . $calc['months'] . ' tháng = ' . number_format($calc['total_amount'], 0, ',', '.') . ' đồng.';
        } else {
            $priceText = number_format($calc['monthly_rent'], 0, ',', '.') . ' đồng/tháng x ' . $calc['months'] . ' tháng = ' . number_format($calc['total_amount'], 0, ',', '.') . ' đồng.';
        }
        $body .= self::pSimple($priceText, ['align' => 'left', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple("(Bằng chữ: {$calc['total_amount_words']});", ['align' => 'left', 'italic' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        
        $body .= self::p([
            self::run('Tổng giá trị hợp đồng: ' . number_format($calc['total_amount'], 0, ',', '.') . ' đồng ', ['bold' => true, 'size' => 14]),
            self::run('(Bằng chữ: ', ['size' => 14]),
            self::run($calc['total_amount_words'], ['italic' => true, 'size' => 14]),
            self::run(')', ['size' => 14])
        ], ['align' => 'left', 'indent' => true, 'before' => 60, 'after' => 60]);

        $body .= self::pSimple('2.2 Phương thức thanh toán:', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Bên B phải thực hiện thanh toán tiền sử dụng diện tích bán hàng, các khoản phí, lệ phí, thuế theo quy định hiện hành vào Ngân sách nhà nước cho Tổ quản lý và Đội thuế theo đúng thời gian đã quy định. Nếu trễ phải nộp phạt quá hạn theo quy định.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple("- Bên B phải thanh toán cho bên A các khoản dịch vụ nêu trên cũng như các khoản khác (nếu có) vào ngày {$payDueDay} hàng tháng. Trong vòng {$payGrace} ngày sử dụng diện tích kinh doanh mà không thanh toán tiền thuê thì bên A có quyền hủy bỏ hợp đồng, thu hồi vị trí kinh doanh mà không cần sự thỏa thuận của bên B và xử lý theo quy định chung của Nhà nước.", ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        $body .= self::pSimple('2.3 Thời hạn hợp đồng:', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple("- Thời gian sử dụng diện tích bán hàng {$calc['months']} tháng từ ngày {$startStr} đến ngày {$endStr}.", ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Hết thời hạn sử dụng diện tích bán hàng ghi trên hợp đồng mà cả hai bên có nhu cầu tiếp tục gia hạn hợp đồng thì tiến hành ký kết tiếp hợp đồng cho thời gian sử dụng diện tích bán hàng tiếp theo. Nếu không có nhu cầu gia hạn thì tiến hành thanh lý hợp đồng, Bên B hoàn trả lại mặt bằng diện tích bán hàng cho Bên A. Tuyệt đối không được phép mua bán, sang nhượng dưới bất kỳ hình thức nào.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        // 10. Điều 3, 4, 5
        $body .= self::pSimple('Điều 3. Trách nhiệm của bên A', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Bên A chính thức công nhận bên B được quyền sử dụng vị trí (sạp) đã thuê để kinh doanh khi bên B chấp hành đúng nội quy chợ và kinh doanh đúng ngành hàng đăng ký và nộp đủ tiền thuê mặt bằng.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Bên A tạo điều kiện thuận lợi cho bên B để kinh doanh mua bán như:', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('+ Hướng dẫn bên B lập đầy đủ thủ tục hợp lệ theo quy định của Nhà nước để kinh doanh như: đăng ký giấy phép kinh doanh, kê khai nộp thuế theo quy định của pháp luật,....', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('+ Thường xuyên tu sửa mặt bằng, chống mưa nắng, hệ thống thoát nước để bên B kinh doanh có hiệu quả.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        $body .= self::pSimple('Điều 4. Trách nhiệm của bên B', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Chấp hành nghiêm chỉnh các chủ trương chính sách của Nhà nước và nội qui chợ, thực hiện đầy đủ thủ tục và nghĩa vụ với Nhà nước trước khi kinh doanh theo quy định của pháp luật hiện hành.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Kinh doanh đúng diện tích, vị trí, nội dung thỏa thuận trong hợp đồng sử dụng diện tích bán hàng tại chợ; không được tự ý tháo dỡ, sửa chữa trang thiết bị, cơi nới thùng sạp, sắp xếp hàng hóa ra ngoài diện tích ghi trong hợp đồng.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Không được hoán đổi địa điểm, vị trí đã thuê, lấn chiếm diện tích lối đi công cộng, chất xếp hàng hóa, che chắn bạt, dù làm che khuất ánh sáng, tầm nhìn và sự thông thoáng của chợ, ảnh hưởng đến các hộ kinh doanh khác và công tác phòng cháy chữa cháy tại chợ.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Không tự ý sang nhượng cho người khác vào kinh doanh, nếu sử dụng diện tích bán hàng để sang nhượng, mua bán dưới bất kỳ hình thức nào đều xem là bất hợp pháp. Tổ quản lý sẽ thanh lý hợp đồng và sẽ thu hồi vô điều kiện.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Trường hợp tạm nghỉ kinh doanh vẫn phải đóng tiền sử dụng diện tích bán hàng theo quy định. Nếu không còn kinh doanh tại chợ thì phải làm đơn trả lại mặt bằng. Trường hợp ốm đau, sinh đẻ... được phép cho người nhà ra bán thay nhưng phải làm giấy ủy quyền có xác nhận của chính quyền địa phương và được Tổ quản lý thống nhất bằng văn bản.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('- Hàng hóa, tài sản kinh doanh chủ hộ phải tự ý giữ gìn bảo quản. Tổ quản lý chợ không chịu trách nhiệm về mọi sự hư hỏng, cháy, mất mát xảy ra. Nếu có nhu cầu bảo quản hàng hóa thì bên B mua bảo hiểm hàng hóa với cơ quan bảo hiểm theo quy định.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        $body .= self::pSimple('Điều 5. Điều khoản chung', ['align' => 'left', 'bold' => true, 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('Trong quá trình sử dụng mặt bằng kinh doanh mua bán, nếu có nhu cầu quy hoạch, sắp xếp lại vị trí kinh doanh hoặc do công trình phải cải tạo, sửa chữa, thì Tổ quản lý sẽ thu hồi lại mặt bằng kinh doanh và có trách nhiệm báo trước 30 ngày, chủ hộ kinh doanh tự giải quyết hàng hóa và trả lại mặt bằng cho Tổ quản lý theo quy định. Tổ quản lý chợ sẽ thanh toán lại số tiền cho các hộ kinh doanh đã nộp khi chưa hết thời hạn hợp đồng hoặc khấu trừ lại sau khi Chợ đã hoàn thành việc sửa chữa..', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('Hai bên cam kết thực hiện đầy đủ các điều khoản của hợp đồng. Trong quá trình thực hiện có vấn đề phát sinh mới thì hai bên cùng thỏa thuận, bàn bạc và đi đến thống nhất để đảm bảo lợi ích đôi bên.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('Nếu một trong hai bên vi phạm hợp đồng, thì sẽ bị xử lý theo quy định của pháp luật hiện hành.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('Sau khi hợp đồng hết hiệu lực, nếu bên B có nhu cầu tiếp tục sử dụng diện tích bán hàng để kinh doanh thì hai bên cùng bàn bạc. Nếu thống nhất sẽ tiếp tục hợp đồng cho thời gian tiếp theo.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);
        $body .= self::pSimple('Hợp đồng được lập thành 02 bản, mỗi bên giữ 01 bản, có giá trị pháp lý như nhau./.', ['align' => 'both', 'indent' => true, 'size' => 14, 'before' => 60, 'after' => 60]);

        // 11. Bảng ký tên (2 cột không viền, căn giữa bảng, trên dưới 0pt)
        $body .= '<w:tbl>
            <w:tblPr>
                <w:tblStyle w:val="TableGrid"/>
                <w:tblW w:w="9782" w:type="dxa"/>
                <w:jc w:val="center"/>
                <w:tblInd w:w="-176" w:type="dxa"/>
                <w:tblBorders>
                    <w:top w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:left w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:bottom w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:right w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:insideH w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                    <w:insideV w:val="none" w:sz="0" w:space="0" w:color="auto"/>
                </w:tblBorders>
                <w:tblCellMar>
                    <w:top w:w="0" w:type="dxa"/>
                    <w:bottom w:w="0" w:type="dxa"/>
                    <w:left w:w="108" w:type="dxa"/>
                    <w:right w:w="108" w:type="dxa"/>
                </w:tblCellMar>
            </w:tblPr>
            <w:tblGrid>
                <w:gridCol w:w="4891"/>
                <w:gridCol w:w="4891"/>
            </w:tblGrid>
            <w:tr>
                <w:tc>
                    <w:tcPr><w:tcW w:w="4891" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
                    ' . self::pSimple('ĐẠI DIỆN BÊN B', ['align' => 'center', 'bold' => true, 'size' => 14, 'before' => 80, 'after' => 0]) . '
                    ' . self::pSimple('(Ký, ghi rõ họ tên)', ['align' => 'center', 'italic' => true, 'size' => 11, 'before' => 0, 'after' => 1200]) . '
                </w:tc>
                <w:tc>
                    <w:tcPr><w:tcW w:w="4891" w:type="dxa"/><w:vAlign w:val="center"/></w:tcPr>
                    ' . self::pSimple('ĐẠI DIỆN BÊN A', ['align' => 'center', 'bold' => true, 'size' => 14, 'before' => 0, 'after' => 0]) . '
                    ' . self::pSimple(mb_strtoupper($repAPos1, 'UTF-8'), ['align' => 'center', 'bold' => true, 'size' => 14, 'before' => 0, 'after' => 1200]) . '
                </w:tc>
            </w:tr>
        </w:tbl>';

        // 12. Cấu hình trang A4 chuẩn (Khổ A4: 11909 x 16834, Margin: Top 1134 = 2cm, Bottom 1134 = 2cm, Left 1701 = 3cm, Right 1134 = 2cm)
        $sectPr = '<w:sectPr>
            <w:pgSz w:w="11909" w:h="16834" w:code="9"/>
            <w:pgMar w:top="1134" w:right="1134" w:bottom="1134" w:left="1701" w:header="720" w:footer="720" w:gutter="0"/>
            <w:cols w:space="720"/>
            <w:docGrid w:linePitch="360"/>
        </w:sectPr>';

        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas"
            xmlns:cx="http://schemas.microsoft.com/office/drawing/2014/chartex"
            xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006"
            xmlns:o="urn:schemas-microsoft-com:office:office"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
            xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math"
            xmlns:v="urn:schemas-microsoft-com:vml"
            xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing"
            xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing"
            xmlns:w10="urn:schemas-microsoft-com:office:word"
            xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml"
            xmlns:w15="http://schemas.microsoft.com/office/word/2012/wordml"
            xmlns:w16cex="http://schemas.microsoft.com/office/word/2018/wordml/cex"
            xmlns:w16cid="http://schemas.microsoft.com/office/word/2016/wordml/cid"
            xmlns:w16="http://schemas.microsoft.com/office/word/2018/wordml"
            xmlns:w16sdtdh="http://schemas.microsoft.com/office/word/2020/wordml/sdtdatahash"
            xmlns:w16se="http://schemas.microsoft.com/office/word/2015/wordml/symex"
            xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup"
            xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk"
            xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml"
            xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape"
            mc:Ignorable="w14 w15 w16se w16cid w16 w16cex w16sdtdh wp14">
    <w:body>
        ' . $body . '
        ' . $sectPr . '
    </w:body>
</w:document>';
    }

    private static function createFallbackZip($targetFile, $xml) {
        $zip = new ZipArchive();
        if ($zip->open($targetFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception("Không thể tạo tệp nén DOCX.");
        }

        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
    <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
    <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
    <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
    <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
    <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        $docRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        $zip->addFromString('word/_rels/document.xml.rels', $docRels);

        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:docDefaults>
        <w:rPrDefault>
            <w:rPr>
                <w:rFonts w:ascii="Times New Roman" w:hAnsi="Times New Roman" w:cs="Times New Roman"/>
                <w:sz w:val="28"/>
                <w:szCs w:val="28"/>
                <w:lang w:val="vi-VN"/>
            </w:rPr>
        </w:rPrDefault>
        <w:pPrDefault>
            <w:pPr>
                <w:spacing w:line="276" w:lineRule="auto" w:before="60" w:after="60"/>
            </w:pPr>
        </w:pPrDefault>
    </w:docDefaults>
</w:styles>';
        $zip->addFromString('word/styles.xml', $styles);

        $coreProps = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:dcmitype="http://purl.org/dc/dcmitype/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <dc:title>Hợp đồng dịch vụ</dc:title>
    <dc:creator>Tổ quản lý chợ</dc:creator>
</cp:coreProperties>';
        $zip->addFromString('docProps/core.xml', $coreProps);

        $appProps = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
    <Application>Microsoft Office Word</Application>
</Properties>';
        $zip->addFromString('docProps/app.xml', $appProps);

        $zip->addFromString('word/document.xml', $xml);
        $zip->close();
    }
}
