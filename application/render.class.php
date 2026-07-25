<?php

class render {
	private static $instance;
    private $errors = [];

    private static $entities = [
        'trader'   => 'tiểu thương',
        'user'     => 'người dùng',
        'stall'    => 'sạp',
        'contract' => 'hợp đồng',
        'bill'     => 'hóa đơn',
        'appendix' => 'phụ lục',
        'certificate' => 'giấy tờ/chứng nhận ATTP',
        'market'   => 'chợ'
    ];

    private static $actions = [
        'create' => 'Thêm',
        'add'    => 'Thêm',
        'update' => 'Cập nhật',
        'edit'   => 'Cập nhật',
        'delete' => 'Xóa'
    ];
	
	function __construct() {
		
	}

    public static function getInstance() {
		if (!self::$instance)
		{	
			self::$instance = new render();
		}
		return self::$instance;
	}
	
    public function redertest(){
        return "render test";
    }

    // ==========================================
    // Messaging & Error Aborting Methods
    // ==========================================

    /**
     * Sinh thông báo lỗi tự động theo loại lỗi và đối tượng
     */
    public static function error($type, $entity) {
        $entityName = self::$entities[$entity] ?? $entity;
        
        $templates = [
            'not_found'          => "{$entityName} không tồn tại trên hệ thống",
            'missing_id'         => "thiếu tham số ID {$entityName}",
            'method_not_allowed' => "phương thức không được hỗ trợ"
        ];

        $msg = $templates[$type] ?? null;
        
        // Tự động dịch lỗi thiếu tham số động (Ví dụ: 'missing_trader_code' -> 'thiếu tham số trader_code')
        if ($msg === null && strpos($type, 'missing_') === 0) {
            $paramName = substr($type, 8);
            $msg = "thiếu tham số {$paramName}";
        }
        
        $msg = $msg ?? $type;
        return self::mb_ucfirst($msg);
    }

    /**
     * Sinh thông báo kết quả hành động (Thành công / Thất bại)
     */
    public static function result($action, $entity, $isSuccess, $detail = '') {
        $actionName = self::$actions[$action] ?? $action;
        $entityName = self::$entities[$entity] ?? $entity;

        if ($isSuccess) {
            return "{$actionName} {$entityName} thành công";
        }

        $message = "{$actionName} {$entityName} thất bại";
        if (!empty($detail)) {
            // Danh sách các khóa lỗi hệ thống cần dịch qua hàm error()
            $systemErrors = ['not_found', 'missing_id', 'method_not_allowed'];
            
            if (in_array($detail, $systemErrors) || strpos($detail, 'missing_') === 0) {
                $errorDetail = self::error($detail, $entity);
            } else {
                $errorDetail = self::$entities[$detail] ?? $detail;
            }
            $message .= ": " . $errorDetail;
        }
        return $message;
    }

    /**
     * Helper viết hoa chữ cái đầu tiên (hỗ trợ UTF-8 cho Tiếng Việt)
     */
    private static function mb_ucfirst($string) {
        if (empty($string)) return '';
        return mb_strtoupper(mb_substr($string, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($string, 1, null, 'UTF-8');
    }

    /**
     * Trả về phản hồi JSON cho API
     */
    public function apiResponse(string $action, string $entity, bool $isSuccess, string $detail = '', int $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        
        $message = self::result($action, $entity, $isSuccess, $detail);
        
        $response = [
            'status' => $statusCode
        ];
        
        if ($isSuccess) {
            $response['message'] = $message;
        } else {
            $response['error'] = $message;
        }
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

    public function abort405(string $expectedMethod, string $action, string $entity) {
		if ($_SERVER['REQUEST_METHOD'] !== strtoupper($expectedMethod)) {
			$this->apiResponse($action, $entity, false, 'method_not_allowed', 405);
		}
	}

	/**
	 * Chặn lỗi 403 Forbidden nếu điều kiện không thỏa mãn
	 */
	public function abort403(bool $condition, string $action, string $entity, string $detail = 'Bạn không có quyền thực hiện hành động này.') {
		if (!$condition) {
			$this->apiResponse($action, $entity, false, $detail, 403);
		}
	}

	/**
	 * Chặn lỗi 404 Not Found nếu không tìm thấy bản ghi (trả về dữ liệu nếu tìm thấy)
	 */
	public function abort404(object $model, string $method, $id, string $action, string $entity): array {
		$record = $model->$method($id);
		if (!$record) {
			$this->apiResponse($action, $entity, false, 'not_found', 404);
		}
		return $record;
	}

	/**
	 * Chặn lỗi 400 Bad Request đa năng (Thiếu tham số, lỗi validator, hoặc điều kiện logic)
	 */
	public function abort400($check, string $action, string $entity, string $detail = '') {
		// Trường hợp 1: Kiểm tra thiếu tham số (chuỗi hoặc mảng)
		if (is_string($check) || is_array($check)) {
			$params = is_array($check) ? $check : [$check];
			foreach ($params as $param) {
				$val = $_POST[$param] ?? $_GET[$param] ?? null;
				if ($val === null || (is_string($val) && trim($val) === '')) {
					$this->apiResponse($action, $entity, false, "missing_{$param}", 400);
				}
			}
			return;
		}

		// Trường hợp 2: Kiểm tra đối tượng validator hoặc render
		if ($check instanceof validator || $check instanceof render) {
			if (!$check->isValid()) {
				$errors = $check->getErrors();
				$firstError = reset($errors);
				$this->apiResponse($action, $entity, false, $firstError, 400);
			}
			return;
		}

		// Trường hợp 3: Kiểm tra biểu thức logic (boolean/empty)
		if (!$check) {
			$this->apiResponse($action, $entity, false, $detail, 400);
		}
	}

	/**
	 * Chặn lỗi 500 Internal Server Error khi có Exception/Throwable
	 */
	public function abort500(\Throwable $e, string $action, string $entity) {
		$this->apiResponse($action, $entity, false, $e->getMessage(), 500);
	}

    // ==========================================
    // Validation Methods
    // ==========================================

    /**
     * Kiểm tra trường bắt buộc nhập
     */
    public function required($field, $value, $message = "Trường này là bắt buộc.") {
        if (is_array($value)) {
            if (empty($value)) {
                $this->errors[$field] = $message;
            }
        } else {
            if (trim($value) === '') {
                $this->errors[$field] = $message;
            }
        }
        return $this;
    }

    /**
     * Kiểm tra định dạng email
     */
    public function email($field, $value, $message = "Email không đúng định dạng.") {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    /**
     * Kiểm tra số điện thoại Việt Nam hợp lệ
     */
    public function phone($field, $value, $message = "Số điện thoại không hợp lệ.") {
        if (!empty($value)) {
            // Định dạng SĐT Việt Nam: 10 chữ số, bắt đầu bằng 03, 05, 07, 08, 09
            $pattern = '/^(03|05|07|08|09)[0-9]{8}$/';
            if (!preg_match($pattern, $value)) {
                $this->errors[$field] = $message;
            }
        }
        return $this;
    }

    /**
     * Kiểm tra độ dài tối thiểu
     */
    public function minLength($field, $value, $min, $message = null) {
        $msg = $message ?? "Trường này phải có ít nhất {$min} ký tự.";
        if (!empty($value) && mb_strlen($value) < $min) {
            $this->errors[$field] = $msg;
        }
        return $this;
    }

    /**
     * Kiểm tra độ dài tối đa
     */
    public function maxLength($field, $value, $max, $message = null) {
        $msg = $message ?? "Trường này không được vượt quá {$max} ký tự.";
        if (!empty($value) && mb_strlen($value) > $max) {
            $this->errors[$field] = $msg;
        }
        return $this;
    }

    /**
     * Kiểm tra khớp dữ liệu (ví dụ: xác nhận mật khẩu)
     */
    public function matches($field, $value, $compareValue, $message = "Xác nhận giá trị không khớp.") {
        if ($value !== $compareValue) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    public function numeric($field, $value, $message = "Trường này phải là dạng số.") {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field] = $message;
        }
        return $this;
    }

    /**
     * Kiểm tra giá trị tối thiểu
     */
    public function min($field, $value, $min, $message = null) {
        $msg = $message ?? "Trường này phải lớn hơn hoặc bằng {$min}.";
        if (!empty($value) && is_numeric($value) && $value < $min) {
            $this->errors[$field] = $msg;
        }
        return $this;
    }

    /**
     * Thêm lỗi thủ công
     */
    public function addError($field, $message) {
        $this->errors[$field] = $message;
    }

    /**
     * Kiểm tra xem có lỗi nào không
     */
    public function isValid() {
        return empty($this->errors);
    }

    /**
     * Lấy danh sách lỗi
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Lấy lỗi của một trường cụ thể
     */
    public function getError($field) {
        return $this->errors[$field] ?? null;
    }

    // ==========================================
    // File Upload Methods
    // ==========================================

    /**
     * Upload nhiều file đính kèm
     */
    public function uploadMultipleFiles(string $field, string $subDir, array $allowedExtensions, int $limit, string $action, string $entity): array {
        if (!isset($_FILES[$field]) || empty($_FILES[$field]['name']) || $_FILES[$field]['error'][0] === UPLOAD_ERR_NO_FILE) {
            return [];
        }

        $files = $_FILES[$field];
        $uploadedFiles = [];
        $uploadDir = "./uploads/{$subDir}/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileCount = count($files['name']);
        if ($fileCount > $limit) {
            $this->abort400(false, $action, $entity, "Chỉ được phép tải lên tối đa {$limit} tệp tin.");
        }

        for ($i = 0; $i < $fileCount; $i++) {
            $error = $files['error'][$i];
            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($error !== UPLOAD_ERR_OK) {
                $this->abort500(new \Exception("Lỗi upload file: Mã lỗi {$error}"), $action, $entity);
            }

            $name = $files['name'][$i];
            $tmpName = $files['tmp_name'][$i];
            $size = $files['size'][$i];

            if ($size > 15 * 1024 * 1024) {
                $this->abort400(false, $action, $entity, "Tệp tin {$name} vượt quá dung lượng cho phép (tối đa 15MB).");
            }

            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                $this->abort400(false, $action, $entity, "Định dạng tệp tin {$name} không hợp lệ (chỉ chấp nhận: " . implode(', ', $allowedExtensions) . ").");
            }

            $safeName = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", pathinfo($name, PATHINFO_FILENAME));
            $safeName = substr($safeName, 0, 100);
            $newFilename = $safeName . "_" . time() . "_" . uniqid() . "." . $ext;
            $destination = $uploadDir . $newFilename;

            if (move_uploaded_file($tmpName, $destination)) {
                $uploadedFiles[] = $newFilename;
            } else {
                $this->abort500(new \Exception("Không thể di chuyển tệp tin tải lên: {$name}"), $action, $entity);
            }
        }

        return $uploadedFiles;
    }
}

// ========================================================================
// Lightweight Compatibility Shells
// ========================================================================

class validator extends render {}
class message extends render {}

class upload {
    private $subDir;
    private $allowedExtensions;
    private $maxSizeMb;
    private $errors = [];

    public function __construct(string $subDir, array $allowedExtensions = [], int $maxSizeMb = 15) {
        $this->subDir = $subDir;
        $this->allowedExtensions = $allowedExtensions;
        $this->maxSizeMb = $maxSizeMb;
    }

    public function save(string $field) {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            $this->errors[] = "Không có tệp tin nào được chọn.";
            return false;
        }

        $file = $_FILES[$field];
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = "Lỗi upload tệp tin: Mã lỗi " . $file['error'];
            return false;
        }

        $name = $file['name'];
        $tmpName = $file['tmp_name'];
        $size = $file['size'];

        if ($size > $this->maxSizeMb * 1024 * 1024) {
            $this->errors[] = "Tệp tin vượt quá dung lượng cho phép (tối đa {$this->maxSizeMb}MB).";
            return false;
        }

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!empty($this->allowedExtensions) && !in_array($ext, $this->allowedExtensions)) {
            $this->errors[] = "Định dạng tệp tin không hợp lệ (chỉ chấp nhận: " . implode(', ', $this->allowedExtensions) . ").";
            return false;
        }

        $uploadDir = "./uploads/{$this->subDir}/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $safeName = preg_replace("/[^a-zA-Z0-9_\-\.]/", "_", pathinfo($name, PATHINFO_FILENAME));
        $safeName = substr($safeName, 0, 100);
        $newFilename = $safeName . "_" . time() . "_" . uniqid() . "." . $ext;
        $destination = $uploadDir . $newFilename;

        if (move_uploaded_file($tmpName, $destination)) {
            return $newFilename;
        }

        $this->errors[] = "Không thể di chuyển tệp tin tải lên máy chủ.";
        return false;
    }

    public function getErrors(): array {
        return $this->errors;
    }
}
