<?php
/**
 * Data access for the income/expense ledger.  The schema is kept here so a
 * deployed database is upgraded automatically the first time this module is opened.
 */
class incomeModel {
    private $db;

    public function __construct() {
        $this->db = database::getInstance();
        $this->ensureSchema();
    }

    public function ensureSchema() {
        $this->db->query("CREATE TABLE IF NOT EXISTS income_categories (
            category_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            market_id INT NOT NULL,
            category_type ENUM('income','expense') NOT NULL,
            category_name VARCHAR(255) NOT NULL,
            category_note VARCHAR(500) NULL,
            status TINYINT NOT NULL DEFAULT 1 COMMENT '1=active, 99=soft deleted',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (category_id), KEY idx_income_categories_market (market_id, category_type, status)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $this->db->query("CREATE TABLE IF NOT EXISTS income_vouchers (
            voucher_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            market_id INT NOT NULL,
            voucher_type ENUM('income','expense') NOT NULL,
            category_id INT UNSIGNED NULL,
            voucher_date DATE NOT NULL,
            content VARCHAR(1000) NOT NULL,
            amount DECIMAL(15,2) NOT NULL,
            document_no VARCHAR(100) NULL,
            payer_name VARCHAR(255) NULL,
            collector_name VARCHAR(255) NULL,
            beneficiary_name VARCHAR(255) NULL,
            attachment_path VARCHAR(500) NULL,
            status TINYINT NOT NULL DEFAULT 1 COMMENT '1=active, 99=soft deleted',
            created_by INT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (voucher_id), KEY idx_income_vouchers_market (market_id, voucher_type, status, voucher_date),
            CONSTRAINT fk_income_voucher_category FOREIGN KEY (category_id) REFERENCES income_categories(category_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function categories($marketId, $type) {
        return $this->db->select('SELECT * FROM income_categories WHERE market_id=:market_id AND category_type=:type AND status != 99 ORDER BY category_name', ['market_id'=>(int)$marketId, 'type'=>$type]);
    }
    public function category($marketId, $id) {
        return $this->db->selectOne('SELECT * FROM income_categories WHERE category_id=:id AND market_id=:market_id AND status != 99', ['id'=>(int)$id, 'market_id'=>(int)$marketId]);
    }
    public function saveCategory($marketId, $data) {
        if (!empty($data['category_id'])) {
            $this->db->query('UPDATE income_categories SET category_name=:name, category_note=:note WHERE category_id=:id AND market_id=:market_id AND status != 99', ['name'=>$data['name'], 'note'=>$data['note'], 'id'=>(int)$data['category_id'], 'market_id'=>(int)$marketId]);
            return (int)$data['category_id'];
        }
        $this->db->query('INSERT INTO income_categories (market_id, category_type, category_name, category_note) VALUES (:market_id,:type,:name,:note)', ['market_id'=>(int)$marketId, 'type'=>$data['type'], 'name'=>$data['name'], 'note'=>$data['note']]);
        return $this->db->lastInsertId();
    }
    public function deleteCategory($marketId, $id) {
        return $this->db->query('UPDATE income_categories SET status=99 WHERE category_id=:id AND market_id=:market_id', ['id'=>(int)$id, 'market_id'=>(int)$marketId]);
    }
    public function vouchers($marketId, $type, $filters, $page, $perPage = 20) {
        $where = ['v.market_id=:market_id', 'v.voucher_type=:type', 'v.status != 99'];
        $params = ['market_id'=>(int)$marketId, 'type'=>$type];
        if (!empty($filters['q'])) { $where[]='(v.content LIKE :q OR v.document_no LIKE :q OR v.payer_name LIKE :q OR v.beneficiary_name LIKE :q)'; $params['q']='%'.$filters['q'].'%'; }
        if (!empty($filters['category_id'])) { $where[]='v.category_id=:category_id'; $params['category_id']=(int)$filters['category_id']; }
        if (!empty($filters['from_date'])) { $where[]='v.voucher_date >= :from_date'; $params['from_date']=$filters['from_date']; }
        if (!empty($filters['to_date'])) { $where[]='v.voucher_date <= :to_date'; $params['to_date']=$filters['to_date']; }
        $condition=implode(' AND ', $where);
        $total=(int)($this->db->selectOne("SELECT COUNT(*) total FROM income_vouchers v WHERE $condition", $params)['total'] ?? 0);
        $offset=max(0, ((int)$page-1)*$perPage);
        $params['limit']=$perPage; $params['offset']=$offset;
        $rows=$this->db->select("SELECT v.*, c.category_name FROM income_vouchers v LEFT JOIN income_categories c ON c.category_id=v.category_id WHERE $condition ORDER BY v.voucher_date DESC, v.voucher_id DESC LIMIT :limit OFFSET :offset", $params);
        return ['rows'=>$rows, 'total'=>$total, 'pages'=>max(1, (int)ceil($total/$perPage))];
    }
    public function voucher($marketId, $id) { return $this->db->selectOne('SELECT * FROM income_vouchers WHERE voucher_id=:id AND market_id=:market_id AND status != 99', ['id'=>(int)$id,'market_id'=>(int)$marketId]); }
    public function saveVoucher($marketId, $userId, $data) {
        $params=['market_id'=>(int)$marketId, 'type'=>$data['type'], 'category_id'=>$data['category_id'] ?: null, 'voucher_date'=>$data['voucher_date'], 'content'=>$data['content'], 'amount'=>$data['amount'], 'document_no'=>$data['document_no'], 'payer_name'=>$data['payer_name'], 'collector_name'=>$data['collector_name'], 'beneficiary_name'=>$data['beneficiary_name'], 'attachment_path'=>$data['attachment_path']];
        if (!empty($data['voucher_id'])) {
            $params['id']=(int)$data['voucher_id'];
            $this->db->query('UPDATE income_vouchers SET category_id=:category_id,voucher_date=:voucher_date,content=:content,amount=:amount,document_no=:document_no,payer_name=:payer_name,collector_name=:collector_name,beneficiary_name=:beneficiary_name,attachment_path=:attachment_path WHERE voucher_id=:id AND market_id=:market_id AND status != 99', $params);
            return $params['id'];
        }
        $params['created_by']=(int)$userId;
        $this->db->query('INSERT INTO income_vouchers (market_id,voucher_type,category_id,voucher_date,content,amount,document_no,payer_name,collector_name,beneficiary_name,attachment_path,created_by) VALUES (:market_id,:type,:category_id,:voucher_date,:content,:amount,:document_no,:payer_name,:collector_name,:beneficiary_name,:attachment_path,:created_by)', $params);
        return $this->db->lastInsertId();
    }
    public function deleteVoucher($marketId, $id) { return $this->db->query('UPDATE income_vouchers SET status=99 WHERE voucher_id=:id AND market_id=:market_id', ['id'=>(int)$id,'market_id'=>(int)$marketId]); }
}
