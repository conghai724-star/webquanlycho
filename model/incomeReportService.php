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

    /** Render and stream the official S07-X layout through PhpSpreadsheet. */
    public function downloadS07X($marketId, array $options) {
        $autoload=DIR_ROOT.'/vendor/autoload.php';
        if (!is_file($autoload)) throw new RuntimeException('Thiếu PhpSpreadsheet. Hãy nâng PHP lên tối thiểu 7.4 và chạy composer require phpoffice/phpspreadsheet.');
        require_once $autoload;
        if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\Spreadsheet')) throw new RuntimeException('PhpSpreadsheet chưa sẵn sàng.');
        $year=(int)$options['year']; $fundName=trim($options['fund_name']);
        $data=$this->calculateS07X($marketId,$year,(float)$options['opening_balance'],$options['from_date'] ?: null,$options['to_date'] ?: null);
        $unit=$this->getUnitConfig($marketId);
        $spreadsheet=new \PhpOffice\PhpSpreadsheet\Spreadsheet(); $sheet=$spreadsheet->getActiveSheet(); $sheet->setTitle('S07-X'); $sheet->setShowGridlines(false);
        $sheet->mergeCells('A1:D1')->setCellValue('A1','Đơn vị: '.$unit['unit_name']);
        $sheet->mergeCells('A2:D2')->setCellValue('A2','Mã QHNS: '.$unit['budget_code']);
        $sheet->mergeCells('F1:H1')->setCellValue('F1','Mẫu số: S07-X');
        $sheet->mergeCells('F2:H3')->setCellValue('F2','(Ban hành kèm theo Thông tư số 70/2019/TT-BTC'."\n".'ngày 03/10/2019 của Bộ Tài chính)');
        $sheet->mergeCells('A4:H4')->setCellValue('A4','SỔ THEO DÕI CÁC QUỸ TÀI CHÍNH NGOÀI NGÂN SÁCH');
        $sheet->mergeCells('A5:H5')->setCellValue('A5','Năm: '.$year);
        $sheet->mergeCells('A6:H6')->setCellValue('A6','Tên quỹ: '.$fundName);
        $sheet->mergeCells('A8:A9')->setCellValue('A8','Ngày tháng ghi sổ');
        $sheet->mergeCells('B8:C8')->setCellValue('B8','Chứng từ'); $sheet->setCellValue('B9','Số hiệu')->setCellValue('C9','Ngày tháng');
        $sheet->mergeCells('D8:D9')->setCellValue('D8','Diễn giải'); $sheet->mergeCells('E8:E9')->setCellValue('E8','Tổng số thu (1)');
        $sheet->mergeCells('F8:G8')->setCellValue('F8','Số chi đã sử dụng - Chi tiết'); $sheet->setCellValue('F9','Nội dung (2)')->setCellValue('G9','Số tiền (3)');
        $sheet->mergeCells('H8:H9')->setCellValue('H8','Số còn lại (4)');
        $sheet->fromArray([['A','B','C','D','1','2','3','4']], null, 'A10');
        $headerStyle=['font'=>['bold'=>true],'alignment'=>['horizontal'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,'vertical'=>\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,'wrapText'=>true],'borders'=>['allBorders'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
        $sheet->getStyle('A8:H10')->applyFromArray($headerStyle); $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(14); $sheet->getStyle('A4:A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); $sheet->getStyle('F1:H3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)->setWrapText(true); $sheet->getStyle('F2')->getFont()->setSize(9);
        $sheet->getRowDimension(8)->setRowHeight(28); $sheet->getRowDimension(9)->setRowHeight(30);
        foreach (['A'=>14,'B'=>16,'C'=>14,'D'=>40,'E'=>16,'F'=>34,'G'=>16,'H'=>16] as $col=>$width) $sheet->getColumnDimension($col)->setWidth($width);
        $line=11;
        foreach ($data['rows'] as $row) {
            $sheet->setCellValue('A'.$line,isset($row['date']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($row['date'])) : null);
            $sheet->setCellValue('B'.$line,$row['document_no']??null);
            $sheet->setCellValue('C'.$line,isset($row['document_date']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($row['document_date'])) : null);
            $sheet->setCellValue('D'.$line,$row['description']??null)->setCellValue('E'.$line,$row['income']??null)->setCellValue('F'.$line,$row['expense_content']??null)->setCellValue('G'.$line,$row['expense']??null)->setCellValue('H'.$line,$row['balance']??null);
            $sheet->getStyle('A'.$line.':H'.$line)->applyFromArray(['borders'=>['allBorders'=>['borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]]);
            if ($row['kind']!=='voucher') $sheet->getStyle('A'.$line.':H'.$line)->getFont()->setBold(true)->setItalic(true);
            $line++;
        }
        $sheet->getStyle('A11:A'.$line)->getNumberFormat()->setFormatCode('dd/mm/yyyy'); $sheet->getStyle('C11:C'.$line)->getNumberFormat()->setFormatCode('dd/mm/yyyy'); $sheet->getStyle('E11:E'.$line)->getNumberFormat()->setFormatCode('#,##0'); $sheet->getStyle('G11:H'.$line)->getNumberFormat()->setFormatCode('#,##0'); $sheet->getStyle('E11:E'.$line)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT); $sheet->getStyle('G11:H'.$line)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->freezePane('A11'); $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)->setFitToWidth(1); $sheet->getPageMargins()->setTop(0.4)->setBottom(0.4)->setLeft(0.3)->setRight(0.3);
        $safeFund=preg_replace('/[^\pL\pN_-]+/u','_', $fundName); $filename='S07-X_So_theo_doi_'.$safeFund.'_'.$year.'.xlsx';
        while (ob_get_level()) ob_end_clean(); header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); header('Content-Disposition: attachment; filename="'.$filename.'"'); header('Cache-Control: max-age=0'); (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output'); exit;
    }
}
