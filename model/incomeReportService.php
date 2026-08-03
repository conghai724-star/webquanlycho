<?php
/**
 * Builds report S07-X from actual income_vouchers.  Keeping calculation and
 * Excel rendering here makes the accounting rules testable outside the view.
 */
class incomeReportService {
    private $db;
    public function __construct() { $this->db = database::getInstance(); }

    /** Return unit configuration from markets; market_code is used as fallback QHNS code. */
    public function getUnitConfig($marketId) {
        $market = $this->db->selectOne('SELECT market_id, market_name, market_code FROM markets WHERE market_id=:id', ['id'=>(int)$marketId]);
        if (!$market) throw new RuntimeException('Không tìm thấy thông tin đơn vị/chợ.');
        return ['unit_name'=>$market['market_name'], 'budget_code'=>$market['market_code']];
    }

    /**
     * Calculates opening/closing balances and accumulated movement by month.
     * $fromDate/$toDate support a full year or an arbitrary reporting period.
     */
    public function calculateS07X($marketId, $year, $openingBalance = 0, $fromDate = null, $toDate = null) {
        $year=(int)$year; $fromDate=$fromDate ?: "$year-01-01"; $toDate=$toDate ?: "$year-12-31";
        if (!preg_match('/^'.$year.'-\d{2}-\d{2}$/',$fromDate) || !preg_match('/^'.$year.'-\d{2}-\d{2}$/',$toDate) || $fromDate>$toDate) throw new InvalidArgumentException('Khoảng thời gian báo cáo không hợp lệ.');
        $rows=$this->db->select('SELECT voucher_id,voucher_type,voucher_date,content,amount,document_no FROM income_vouchers WHERE market_id=:market_id AND status != 99 AND voucher_date BETWEEN :year_start AND :to_date ORDER BY voucher_date, voucher_id', ['market_id'=>(int)$marketId,'year_start'=>"$year-01-01",'to_date'=>$toDate]);
        $balance=(float)$openingBalance; $cumulative=0.0; $groups=[];
        // Movements before the selected range establish the first displayed monthly opening balance.
        foreach ($rows as $voucher) {
            if ($voucher['voucher_date'] >= $fromDate) break;
            $movement=$voucher['voucher_type']==='income' ? (float)$voucher['amount'] : -(float)$voucher['amount'];
            $balance += $movement; $cumulative += $movement;
        }
        foreach ($rows as $voucher) if ($voucher['voucher_date'] >= $fromDate) $groups[substr($voucher['voucher_date'],0,7)][]=$voucher;

        $reportRows=[['kind'=>'opening_period','description'=>'- Số dư đầu kỳ','balance'=>(float)$openingBalance]];
        foreach ($groups as $month=>$items) {
            $monthNo=(int)substr($month,5,2); $monthOpening=$balance; $monthIncome=0.0; $monthExpense=0.0;
            $reportRows[]=['kind'=>'opening_month','description'=>'- Số dư đầu tháng '.$monthNo,'balance'=>$monthOpening];
            foreach ($items as $voucher) {
                $income=$voucher['voucher_type']==='income' ? (float)$voucher['amount'] : 0.0;
                $expense=$voucher['voucher_type']==='expense' ? (float)$voucher['amount'] : 0.0;
                $monthIncome += $income; $monthExpense += $expense; $balance += $income-$expense; $cumulative += $income-$expense;
                $reportRows[]=['kind'=>'voucher','date'=>$voucher['voucher_date'],'document_no'=>$voucher['document_no'],'document_date'=>$voucher['voucher_date'],'description'=>$voucher['content'],'income'=>$income ?: null,'expense_content'=>$expense ? $voucher['content'] : null,'expense'=>$expense ?: null,'balance'=>$balance];
            }
            $movement=$monthIncome-$monthExpense;
            $reportRows[]=['kind'=>'month_movement','description'=>'- Cộng PS tháng','income'=>$monthIncome ?: null,'expense'=>$monthExpense ?: null,'balance'=>$movement];
            $reportRows[]=['kind'=>'closing_month','description'=>'- Số dư cuối tháng '.$monthNo,'balance'=>$balance];
            $reportRows[]=['kind'=>'accumulated','description'=>'- Lũy kế từ đầu năm','income'=>$cumulative >= 0 ? $cumulative : null,'expense'=>$cumulative < 0 ? abs($cumulative) : null,'balance'=>$cumulative];
        }
        return ['rows'=>$reportRows,'opening_balance'=>(float)$openingBalance,'closing_balance'=>$balance,'from_date'=>$fromDate,'to_date'=>$toDate];
    }

    /** Render and stream multi-sheet Sổ thu and Sổ chi Excel book using SpreadsheetML. */
    public function downloadS07X($marketId, array $options) {
        $year = (int)$options['year'];
        $fundName = trim($options['fund_name']);
        $fromDate = $options['from_date'] ?: "$year-01-01";
        $toDate = $options['to_date'] ?: "$year-12-31";
        $openingBalance = (float)$options['opening_balance'];

        // Lấy thông tin đơn vị
        $unit = $this->getUnitConfig($marketId);

        // Truy vấn dữ liệu cho Sổ thu và Sổ chi (kết hợp lấy tên danh mục)
        $sql = "SELECT v.*, c.category_name 
                FROM income_vouchers v 
                LEFT JOIN income_categories c ON c.category_id = v.category_id 
                WHERE v.market_id = :market_id AND v.status != 99 AND v.voucher_date BETWEEN :from_date AND :to_date 
                ORDER BY v.voucher_date, v.voucher_id";
        
        $allRows = $this->db->select($sql, ['market_id' => (int)$marketId, 'from_date' => $fromDate, 'to_date' => $toDate]);

        // Phân loại data theo sheet
        $incomeVouchers = [];
        $expenseVouchers = [];
        foreach ($allRows as $r) {
            if ($r['voucher_type'] === 'income') {
                $incomeVouchers[] = $r;
            } else {
                $expenseVouchers[] = $r;
            }
        }

        // Định dạng tên file tải về
        $safeFund = preg_replace('/[^\pL\pN_-]+/u', '_', $fundName);
        $filename = 'So_theo_doi_thu_chi_' . $safeFund . '_' . $year . '.xls';

        // Helper gom nhóm dữ liệu theo tháng để in ra báo cáo
        $groupVouchersByMonth = function($vouchers) {
            $grouped = [];
            for ($m = 1; $m <= 12; $m++) {
                $grouped[$m] = [];
            }
            foreach ($vouchers as $v) {
                $m = (int)date('n', strtotime($v['voucher_date']));
                $grouped[$m][] = $v;
            }
            return $grouped;
        };

        $incomeByMonth = $groupVouchersByMonth($incomeVouchers);
        $expenseByMonth = $groupVouchersByMonth($expenseVouchers);

        // Bắt đầu buffer sinh nội dung XML SpreadsheetML
        ob_start();
        echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
        ?>
        <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
         xmlns:o="urn:schemas-microsoft-com:office:office"
         xmlns:x="urn:schemas-microsoft-com:office:excel"
         xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
         xmlns:html="http://www.w3.org/TR/REC-html40">
         <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
          <Author>BQL Chợ</Author>
          <Created><?php echo date('Y-m-d\TH:i:s\Z'); ?></Created>
         </DocumentProperties>
         <Styles>
          <Style ss:ID="Default" ss:Name="Normal">
           <Alignment ss:Vertical="Bottom"/>
           <Borders/>
           <Font ss:FontName="Segoe UI" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>
           <Interior/>
           <NumberFormat/>
           <Protection/>
          </Style>
          <Style ss:ID="Title">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="14" ss:Bold="1"/>
          </Style>
          <Style ss:ID="SubTitleCenter">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="11"/>
          </Style>
          <Style ss:ID="SubTitleCenterBold">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="11" ss:Bold="1"/>
          </Style>
          <Style ss:ID="SubTitleCenterItalic">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="11" ss:Italic="1"/>
          </Style>
          <Style ss:ID="HeaderRight">
           <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="11" ss:Bold="1"/>
          </Style>
          <Style ss:ID="HeaderRightItalic">
           <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="9" ss:Italic="1"/>
          </Style>
          <Style ss:ID="Header">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
           <Borders>
            <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#b0b0b0"/>
            <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#b0b0b0"/>
            <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#b0b0b0"/>
            <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#b0b0b0"/>
           </Borders>
           <Font ss:FontName="Segoe UI" ss:Bold="1" ss:Color="#000000"/>
           <Interior ss:Color="#F2F2F2" ss:Pattern="Solid"/>
          </Style>
          <Style ss:ID="DataCell">
           <Borders>
            <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#d0d0d0"/>
            <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#d0d0d0"/>
            <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#d0d0d0"/>
            <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#d0d0d0"/>
           </Borders>
           <Font ss:FontName="Segoe UI" ss:Size="10"/>
          </Style>
          <Style ss:ID="DataCenter" ss:Parent="DataCell">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
          </Style>
          <Style ss:ID="DataRight" ss:Parent="DataCell">
           <Alignment ss:Horizontal="Right" ss:Vertical="Center"/>
           <NumberFormat ss:Format="#,##0"/>
          </Style>
          <Style ss:ID="TotalCell" ss:Parent="DataCell">
           <Font ss:FontName="Segoe UI" ss:Bold="1" ss:Size="10"/>
           <Interior ss:Color="#F9F9F9" ss:Pattern="Solid"/>
          </Style>
          <Style ss:ID="TotalRight" ss:Parent="DataRight">
           <Font ss:FontName="Segoe UI" ss:Bold="1" ss:Size="10"/>
           <Interior ss:Color="#F9F9F9" ss:Pattern="Solid"/>
          </Style>
          <Style ss:ID="DateFormat" ss:Parent="DataCenter">
           <NumberFormat ss:Format="dd/mm/yyyy"/>
          </Style>
          <Style ss:ID="SignHeader">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="11" ss:Bold="1"/>
          </Style>
          <Style ss:ID="SignSub">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="11" ss:Italic="1"/>
          </Style>
          <Style ss:ID="SignName">
           <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="11" ss:Bold="1"/>
          </Style>
          <Style ss:ID="NoteItalic">
           <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
           <Font ss:FontName="Segoe UI" ss:Size="11" ss:Italic="1"/>
          </Style>
         </Styles>

         <!-- ── WORKSHEET 1: SỔ THU ── -->
         <Worksheet ss:Name="Sổ thu">
          <Table>
           <Column ss:Width="90"/>
           <Column ss:Width="80"/>
           <Column ss:Width="80"/>
           <Column ss:Width="300"/>
           <Column ss:Width="110"/>
           <Column ss:Width="110"/>
           <Column ss:Width="120"/>

           <!-- Header cơ quan -->
           <Row ss:Height="18">
            <Cell ss:MergeAcross="2"><Data ss:Type="String">PHƯỜNG KON TUM</Data></Cell>
           </Row>
           <Row ss:Height="18">
            <Cell ss:MergeAcross="2"><Data ss:Type="String">PHÒNG KT,HT&amp;ĐT</Data></Cell>
           </Row>
           <Row><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>

           <!-- Tiêu đề Sổ -->
           <Row ss:Height="25">
            <Cell ss:MergeAcross="6" ss:StyleID="Title"><Data ss:Type="String">SỔ THU, NỘP CÁC QUỸ TÀI CHÍNH NGOÀI NGÂN SÁCH</Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:MergeAcross="6" ss:StyleID="SubTitleCenterBold"><Data ss:Type="String"><?php echo htmlspecialchars(mb_strtoupper($unit['unit_name'], 'UTF-8'), ENT_QUOTES | ENT_XML1); ?></Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:MergeAcross="6" ss:StyleID="SubTitleCenterItalic"><Data ss:Type="String">Từ ngày <?php echo date('d/m/Y', strtotime($fromDate)); ?> - <?php echo date('d/m/Y', strtotime($toDate)); ?></Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:MergeAcross="6" ss:StyleID="SubTitleCenterItalic"><Data ss:Type="String">Tên tài khoản: <?php echo htmlspecialchars($fundName, ENT_QUOTES | ENT_XML1); ?></Data></Cell>
           </Row>
           <Row><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>

           <!-- Khối Header hai tầng -->
           <Row ss:Height="24">
            <Cell ss:StyleID="Header" ss:MergeDown="1"><Data ss:Type="String">Ngày tháng&#10;ghi sổ</Data></Cell>
            <Cell ss:StyleID="Header" ss:MergeAcross="1"><Data ss:Type="String">Chứng từ</Data></Cell>
            <Cell ss:StyleID="Header" ss:MergeDown="1"><Data ss:Type="String">Diễn giải</Data></Cell>
            <Cell ss:StyleID="Header" ss:MergeAcross="1"><Data ss:Type="String">Giao dịch</Data></Cell>
            <Cell ss:StyleID="Header" ss:MergeDown="1"><Data ss:Type="String">Số còn lại</Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:Index="2" ss:StyleID="Header"><Data ss:Type="String">Số hiệu</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Ngày tháng</Data></Cell>
            <Cell ss:Index="5" ss:StyleID="Header"><Data ss:Type="String">Nợ</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Có</Data></Cell>
           </Row>

           <!-- Hàng chỉ số phụ A, B, C, D, 1, 2, 3 -->
           <Row ss:Height="18">
            <Cell ss:StyleID="Header"><Data ss:Type="String">A</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">B</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">C</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">D</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">1</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">2</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">3</Data></Cell>
           </Row>

           <!-- Số dư đầu kỳ -->
           <Row ss:Height="22">
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Số dư đầu kỳ</Data></Cell>
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $openingBalance; ?></Data></Cell>
           </Row>

           <!-- Đổ dữ liệu có nhóm theo tháng -->
           <?php 
           $currentBalance = $openingBalance;
           $luyKeNo = 0;
           $luyKeCo = 0;

           // Bắt đầu quét qua 12 tháng
           for ($m = 1; $m <= 12; $m++) {
               $monthVouchers = $incomeByMonth[$m];
               if (empty($monthVouchers)) continue;

               // Dòng số dư đầu tháng
               ?>
               <Row ss:Height="22">
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Số dư đầu tháng <?php echo $m; ?></Data></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $currentBalance; ?></Data></Cell>
               </Row>
               <?php

               $monthNo = 0;
               $monthCo = 0;

               // In các phiếu trong tháng
               foreach ($monthVouchers as $v) {
                   $amount = (float)$v['amount'];
                   // Với Sổ thu: Thu tiền nằm ở cột Có (F), cột Nợ (E) = 0
                   $noVal = 0;
                   $coVal = $amount;

                   $currentBalance = $currentBalance + $coVal - $noVal;
                   $monthNo += $noVal;
                   $monthCo += $coVal;
                   $luyKeNo += $noVal;
                   $luyKeCo += $coVal;
                   ?>
                   <Row ss:Height="22">
                    <Cell ss:StyleID="DateFormat"><Data ss:Type="String"><?php echo date('d/m/Y', strtotime($v['voucher_date'])); ?></Data></Cell>
                    <Cell ss:StyleID="DataCenter"><Data ss:Type="String"><?php echo htmlspecialchars($v['document_no'] ?: '-', ENT_QUOTES | ENT_XML1); ?></Data></Cell>
                    <Cell ss:StyleID="DateFormat"><Data ss:Type="String"><?php echo date('d/m/Y', strtotime($v['voucher_date'])); ?></Data></Cell>
                    <Cell ss:StyleID="DataCell"><Data ss:Type="String"><?php echo htmlspecialchars($v['content'], ENT_QUOTES | ENT_XML1); ?></Data></Cell>
                    <Cell ss:StyleID="DataRight"><?php echo $noVal > 0 ? '<Data ss:Type="Number">' . $noVal . '</Data>' : ''; ?></Cell>
                    <Cell ss:StyleID="DataRight"><Data ss:Type="Number"><?php echo $coVal; ?></Data></Cell>
                    <Cell ss:StyleID="DataRight"><Data ss:Type="Number"><?php echo $currentBalance; ?></Data></Cell>
                   </Row>
                   <?php
               }

               // Cộng phát sinh tháng
               ?>
               <Row ss:Height="22">
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Cộng PS tháng</Data></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $monthNo; ?></Data></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $monthCo; ?></Data></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
               </Row>
               <!-- Số dư cuối tháng -->
               <Row ss:Height="22">
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Số dư cuối tháng: <?php echo $m; ?></Data></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $currentBalance; ?></Data></Cell>
               </Row>
               <!-- Lũy kế từ đầu năm -->
               <Row ss:Height="22">
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Lũy kế từ đầu năm</Data></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $luyKeNo; ?></Data></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $luyKeCo; ?></Data></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
               </Row>
               <?php
           }
           ?>

           <!-- Khoảng trống trước ký tên -->
           <Row><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>
           <Row ss:Height="18">
            <Cell ss:Index="5" ss:MergeAcross="2" ss:StyleID="NoteItalic"><Data ss:Type="String">- Sổ này có 02 trang đánh số từ trang 01 đến trang 02</Data></Cell>
           </Row>
           <Row ss:Height="18">
            <Cell ss:Index="5" ss:MergeAcross="2" ss:StyleID="NoteItalic"><Data ss:Type="String">- Ngày mở sổ: <?php echo date('d/m/Y', strtotime($fromDate)); ?></Data></Cell>
           </Row>
           <Row><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>

           <!-- Khối ký tên -->
           <Row ss:Height="20">
            <Cell ss:StyleID="SignHeader" ss:MergeAcross="1"><Data ss:Type="String">NGƯỜI GHI SỔ</Data></Cell>
            <Cell ss:StyleID="SignHeader" ss:MergeAcross="1"><Data ss:Type="String">PHỤ TRÁCH KẾ TOÁN</Data></Cell>
            <Cell ss:StyleID="SignSub" ss:MergeAcross="2"><Data ss:Type="String">Ngày ...... tháng ...... năm .........</Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:Index="5" ss:MergeAcross="2" ss:StyleID="SignHeader"><Data ss:Type="String">TRƯỞNG PHÒNG</Data></Cell>
           </Row>
           <Row ss:Height="18">
            <Cell ss:StyleID="SignSub" ss:MergeAcross="1"><Data ss:Type="String">(Ký, họ tên)</Data></Cell>
            <Cell ss:StyleID="SignSub" ss:MergeAcross="1"><Data ss:Type="String">(Ký, họ tên)</Data></Cell>
            <Cell ss:StyleID="SignSub" ss:MergeAcross="2"><Data ss:Type="String">(Ký, họ tên, đóng dấu)</Data></Cell>
           </Row>
           
           <!-- Dòng cách tạo khoảng trống cho chữ ký -->
           <Row ss:Height="18"><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>
           <Row ss:Height="18"><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>
           <Row ss:Height="18"><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>

           <!-- Tên người ký (Dòng cuối cùng) -->
           <Row ss:Height="20">
            <Cell ss:StyleID="SignName" ss:MergeAcross="1"><Data ss:Type="String">Trương Thảo Linh</Data></Cell>
            <Cell ss:StyleID="SignName" ss:MergeAcross="1"><Data ss:Type="String">Trương Thảo Linh</Data></Cell>
            <Cell ss:StyleID="SignName" ss:MergeAcross="2"><Data ss:Type="String">Phan Thành Trung</Data></Cell>
           </Row>
          </Table>
         </Worksheet>

         <!-- ── WORKSHEET 2: SỔ CHI ── -->
         <Worksheet ss:Name="Sổ chi">
          <Table>
           <Column ss:Width="90"/>
           <Column ss:Width="80"/>
           <Column ss:Width="80"/>
           <Column ss:Width="300"/>
           <Column ss:Width="110"/>
           <Column ss:Width="110"/>
           <Column ss:Width="120"/>

           <!-- Header cơ quan -->
           <Row ss:Height="18">
            <Cell ss:MergeAcross="2"><Data ss:Type="String">PHƯỜNG KON TUM</Data></Cell>
           </Row>
           <Row ss:Height="18">
            <Cell ss:MergeAcross="2"><Data ss:Type="String">PHÒNG KT,HT&amp;ĐT</Data></Cell>
           </Row>
           <Row><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>

           <!-- Tiêu đề Sổ -->
           <Row ss:Height="25">
            <Cell ss:MergeAcross="6" ss:StyleID="Title"><Data ss:Type="String">SỔ CHI TIẾT CÁC QUỸ TÀI CHÍNH NGOÀI NGÂN SÁCH</Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:MergeAcross="6" ss:StyleID="SubTitleCenterBold"><Data ss:Type="String"><?php echo htmlspecialchars(mb_strtoupper($unit['unit_name'], 'UTF-8'), ENT_QUOTES | ENT_XML1); ?></Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:MergeAcross="6" ss:StyleID="SubTitleCenterItalic"><Data ss:Type="String">Từ ngày <?php echo date('d/m/Y', strtotime($fromDate)); ?> - <?php echo date('d/m/Y', strtotime($toDate)); ?></Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:MergeAcross="6" ss:StyleID="SubTitleCenterItalic"><Data ss:Type="String">Tên tài khoản: <?php echo htmlspecialchars($fundName, ENT_QUOTES | ENT_XML1); ?></Data></Cell>
           </Row>
           <Row><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>

           <!-- Khối Header hai tầng -->
           <Row ss:Height="24">
            <Cell ss:StyleID="Header" ss:MergeDown="1"><Data ss:Type="String">Ngày tháng&#10;ghi sổ</Data></Cell>
            <Cell ss:StyleID="Header" ss:MergeAcross="1"><Data ss:Type="String">Chứng từ</Data></Cell>
            <Cell ss:StyleID="Header" ss:MergeDown="1"><Data ss:Type="String">Diễn giải</Data></Cell>
            <Cell ss:StyleID="Header" ss:MergeAcross="1"><Data ss:Type="String">Giao dịch</Data></Cell>
            <Cell ss:StyleID="Header" ss:MergeDown="1"><Data ss:Type="String">Số còn lại</Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:Index="2" ss:StyleID="Header"><Data ss:Type="String">Số hiệu</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Ngày tháng</Data></Cell>
            <Cell ss:Index="5" ss:StyleID="Header"><Data ss:Type="String">Nợ</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">Có</Data></Cell>
           </Row>

           <!-- Hàng chỉ số phụ A, B, C, D, 1, 2, 3 -->
           <Row ss:Height="18">
            <Cell ss:StyleID="Header"><Data ss:Type="String">A</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">B</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">C</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">D</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">1</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">2</Data></Cell>
            <Cell ss:StyleID="Header"><Data ss:Type="String">3</Data></Cell>
           </Row>

           <!-- Số dư đầu kỳ -->
           <Row ss:Height="22">
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Số dư đầu kỳ</Data></Cell>
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalCell"></Cell>
            <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $openingBalance; ?></Data></Cell>
           </Row>

           <!-- Đổ dữ liệu có nhóm theo tháng -->
           <?php 
           $currentBalance = $openingBalance;
           $luyKeNo = 0;
           $luyKeCo = 0;

           // Bắt đầu quét qua 12 tháng
           for ($m = 1; $m <= 12; $m++) {
               $monthVouchers = $expenseByMonth[$m];
               if (empty($monthVouchers)) continue;

               // Dòng số dư đầu tháng
               ?>
               <Row ss:Height="22">
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Số dư đầu tháng <?php echo $m; ?></Data></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $currentBalance; ?></Data></Cell>
               </Row>
               <?php

               $monthNo = 0;
               $monthCo = 0;

               // In các phiếu trong tháng
               foreach ($monthVouchers as $v) {
                   $amount = (float)$v['amount'];
                   // Với Sổ chi: Chi tiền nằm ở cột Nợ (E), cột Có (F) = 0
                   $noVal = $amount;
                   $coVal = 0;

                   $currentBalance = $currentBalance + $coVal - $noVal;
                   $monthNo += $noVal;
                   $monthCo += $coVal;
                   $luyKeNo += $noVal;
                   $luyKeCo += $coVal;
                   ?>
                   <Row ss:Height="22">
                    <Cell ss:StyleID="DateFormat"><Data ss:Type="String"><?php echo date('d/m/Y', strtotime($v['voucher_date'])); ?></Data></Cell>
                    <Cell ss:StyleID="DataCenter"><Data ss:Type="String"><?php echo htmlspecialchars($v['document_no'] ?: '-', ENT_QUOTES | ENT_XML1); ?></Data></Cell>
                    <Cell ss:StyleID="DateFormat"><Data ss:Type="String"><?php echo date('d/m/Y', strtotime($v['voucher_date'])); ?></Data></Cell>
                    <Cell ss:StyleID="DataCell"><Data ss:Type="String"><?php echo htmlspecialchars($v['content'], ENT_QUOTES | ENT_XML1); ?></Data></Cell>
                    <Cell ss:StyleID="DataRight"><Data ss:Type="Number"><?php echo $noVal; ?></Data></Cell>
                    <Cell ss:StyleID="DataRight"><?php echo $coVal > 0 ? '<Data ss:Type="Number">' . $coVal . '</Data>' : ''; ?></Cell>
                    <Cell ss:StyleID="DataRight"><Data ss:Type="Number"><?php echo $currentBalance; ?></Data></Cell>
                   </Row>
                   <?php
               }

               // Cộng phát sinh tháng
               ?>
               <Row ss:Height="22">
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Cộng PS tháng</Data></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $monthNo; ?></Data></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $monthCo; ?></Data></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
               </Row>
               <!-- Số dư cuối tháng -->
               <Row ss:Height="22">
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Số dư cuối tháng: <?php echo $m; ?></Data></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $currentBalance; ?></Data></Cell>
               </Row>
               <!-- Lũy kế từ đầu năm -->
               <Row ss:Height="22">
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
                <Cell ss:StyleID="TotalCell"><Data ss:Type="String">- Lũy kế từ đầu năm</Data></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $luyKeNo; ?></Data></Cell>
                <Cell ss:StyleID="TotalRight"><Data ss:Type="Number"><?php echo $luyKeCo; ?></Data></Cell>
                <Cell ss:StyleID="TotalCell"></Cell>
               </Row>
               <?php
           }
           ?>

           <!-- Khoảng trống trước ký tên -->
           <Row><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>
           <Row ss:Height="18">
            <Cell ss:Index="5" ss:MergeAcross="2" ss:StyleID="NoteItalic"><Data ss:Type="String">- Sổ này có 02 trang đánh số từ trang 01 đến trang 02</Data></Cell>
           </Row>
           <Row ss:Height="18">
            <Cell ss:Index="5" ss:MergeAcross="2" ss:StyleID="NoteItalic"><Data ss:Type="String">- Ngày mở sổ: <?php echo date('d/m/Y', strtotime($fromDate)); ?></Data></Cell>
           </Row>
           <Row><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>

           <!-- Khối ký tên -->
           <Row ss:Height="20">
            <Cell ss:StyleID="SignHeader" ss:MergeAcross="1"><Data ss:Type="String">NGƯỜI GHI SỔ</Data></Cell>
            <Cell ss:StyleID="SignHeader" ss:MergeAcross="1"><Data ss:Type="String">PHỤ TRÁCH KẾ TOÁN</Data></Cell>
            <Cell ss:StyleID="SignSub" ss:MergeAcross="2"><Data ss:Type="String">Ngày ...... tháng ...... năm .........</Data></Cell>
           </Row>
           <Row ss:Height="20">
            <Cell ss:Index="5" ss:MergeAcross="2" ss:StyleID="SignHeader"><Data ss:Type="String">TRƯỞNG PHÒNG</Data></Cell>
           </Row>
           <Row ss:Height="18">
            <Cell ss:StyleID="SignSub" ss:MergeAcross="1"><Data ss:Type="String">(Ký, họ tên)</Data></Cell>
            <Cell ss:StyleID="SignSub" ss:MergeAcross="1"><Data ss:Type="String">(Ký, họ tên)</Data></Cell>
            <Cell ss:StyleID="SignSub" ss:MergeAcross="2"><Data ss:Type="String">(Ký, họ tên, đóng dấu)</Data></Cell>
           </Row>
           
           <!-- Dòng cách tạo khoảng trống cho chữ ký -->
           <Row ss:Height="18"><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>
           <Row ss:Height="18"><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>
           <Row ss:Height="18"><Cell ss:Index="1"><Data ss:Type="String"></Data></Cell></Row>

           <!-- Tên người ký (Dòng cuối cùng) -->
           <Row ss:Height="20">
            <Cell ss:StyleID="SignName" ss:MergeAcross="1"><Data ss:Type="String">Trương Thảo Linh</Data></Cell>
            <Cell ss:StyleID="SignName" ss:MergeAcross="1"><Data ss:Type="String">Trương Thảo Linh</Data></Cell>
            <Cell ss:StyleID="SignName" ss:MergeAcross="2"><Data ss:Type="String">Phan Thành Trung</Data></Cell>
           </Row>
          </Table>
         </Worksheet>
        </Workbook>
        <?php
        $xml = ob_get_clean();

        // Gửi header tải xuống file Excel
        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        echo $xml;
        exit;
    }
}
