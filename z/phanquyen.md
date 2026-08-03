# Hướng dẫn mang hệ thống Phân quyền sang dự án mới

## 1. Tổng quan kiến trúc

Hệ thống phân quyền gồm **3 tầng**:

```
View/Controller → Adapter (marketService) → Lõi RBAC (permissionService) → Database
```

| Tầng | File | Vai trò | Tái sử dụng? |
|---|---|---|---|
| **Lõi RBAC** | `permissionservice.class.php` | Kiểm tra quyền tổng quát (role → DB) | ✅ Copy nguyên |
| **Adapter** | `marketservice.class.php` | Lấy session dự án → truyền vào lõi | ❌ Viết mới (~15 dòng) |
| **Cấu hình** | `config.php` (dòng init) | Ghi đè cấu hình bảng, cột, role | ⚠️ Không bắt buộc (chỉ khi DB khác mặc định) |
| **View** | `permissions.php` | Giao diện quản lý quyền | ⚠️ Tham khảo, sửa theo dự án |
| **Controller** | `systemController.php` (permissions, save_permissions) | Load danh sách + lưu quyền | ⚠️ Tham khảo, sửa theo dự án |

---

## 2. Các file và hàm chi tiết

### 2.1. `permissionservice.class.php` — LÕI RBAC (Copy nguyên)

**Vị trí:** `application/permissionservice.class.php`
**Lưu ý:** Tên file phải viết thường (`permissionservice`) để hỗ trợ autoloader trên Linux.

#### Cấu hình mặc định (`$config`)
```php
private static $config = [
    'table'       => 'user_market_permissions',   // Tên bảng phân quyền
    'user_id_col' => 'permission_user_id',        // Cột user ID
    'scope_col'   => 'permission_market_id',      // Cột phạm vi (chợ/khoa/chi nhánh)
    'module_col'  => 'permission_module_code',    // Cột mã module
    'roles' => [
        'super_market' => ['is_super' => true],              // Quyền cao nhất, bỏ qua kiểm tra
        'admin_market' => ['all_modules_in_scope' => true],  // Quản lý phạm vi, mọi module trong scope
        'admin'        => ['requires_explicit_permissions' => true] // Phải tích quyền cụ thể
    ]
];
```

#### Hàm `init(array $customConfig)`
- **Công dụng:** Ghi đè cấu hình mặc định
- **Khi nào gọi:** Từ `config.php` của dự án mới, nếu bảng/cột/role khác mặc định
- **Không cần gọi:** Nếu sửa trực tiếp `$config` trong file

#### Hàm `checkAccess(string $moduleCode, int $userId, int $scopeId, string $actorCode): bool`
- **Công dụng:** Kiểm tra 1 user có quyền truy cập 1 module tại 1 scope không
- **Cơ chế 3 bước:**
  1. `actorCode` có `is_super: true` → **return true** (không query DB)
  2. `actorCode` có `all_modules_in_scope: true` + `scopeId > 0` → **return true** (không query DB)
  3. Còn lại → **query DB** tìm dòng phân quyền cụ thể → có = true, không = false

---

### 2.2. `marketservice.class.php` — ADAPTER (Viết mới cho mỗi dự án)

**Vị trí:** `application/marketservice.class.php` (đổi tên theo dự án, vd: `hospitalservice.class.php`)

**Vai trò:** Cầu nối giữa session/dữ liệu cụ thể của dự án và `permissionService` tổng quát.

#### Hàm `__callStatic(string $name, array $args)`
- **Công dụng:** Proxy mọi static call (vd: `isSuperAdmin()`, `currentMarketId()`) sang class `general`
- **Khi mang sang dự án khác:** Nếu dự án mới cũng có class `general` tương tự, giữ nguyên. Nếu không, bỏ và viết method cụ thể.

#### Hàm `checkModuleAccess(string $module): bool`
- **Công dụng:** Lấy `userId`, `scopeId`, `actorCode` từ session dự án → gọi `permissionService::checkAccess()`
- **Đây là hàm quan trọng nhất** — nơi duy nhất kết nối session dự án với lõi RBAC
- **Khi mang sang dự án khác:** Sửa cách lấy userId/scopeId/actorCode từ session của dự án đó

#### Hàm `requireModuleAccess(string $module)`
- **Công dụng:** Gọi `checkModuleAccess`, nếu false → redirect về trang mặc định + exit
- **Khi mang sang dự án khác:** Sửa URL redirect

---

### 2.3. `config.php` — CẤU HÌNH (Sửa theo dự án)

Nếu dự án mới có cấu trúc DB khác, gọi `init()`:

```php
// config.php của dự án mới
permissionService::init([
    'table'       => 'user_dept_permissions',
    'user_id_col' => 'user_id',
    'scope_col'   => 'department_id',
    'module_col'  => 'module_code',
    'roles' => [
        'director'  => ['is_super' => true],
        'head_dept' => ['all_modules_in_scope' => true],
        'doctor'    => ['requires_explicit_permissions' => true]
    ]
]);
```

---

### 2.4. `permissions.php` (View) — GIAO DIỆN (Tham khảo)

**Vị trí:** `template/app/user/permissions.php`

#### Phần cần sửa khi sang dự án khác:

**Cấu hình module hiển thị** (đầu file):
```php
$permissionConfig = [
    'modules' => [
        // Thay bằng module của dự án mới
        'patient'  => ['name' => 'Bệnh nhân', 'icon' => 'fa-bed'],
        'lab'      => ['name' => 'Xét nghiệm', 'icon' => 'fa-flask'],
        'pharmacy' => ['name' => 'Dược', 'icon' => 'fa-pills'],
    ],
    'roles' => [
        // Mẫu gán nhanh (dropdown) cho từng vai trò
        'bac_si'  => ['name' => 'Bác sĩ', 'permissions' => ['patient' => true, 'lab' => true]],
        'duoc_si' => ['name' => 'Dược sĩ', 'permissions' => ['pharmacy' => true]],
    ]
];
```

**Phần còn lại:** HTML/CSS/JS render tự động dựa trên `$permissionConfig` — hầu như không cần sửa.

---

### 2.5. `systemController.php` — CONTROLLER (Tham khảo)

#### Hàm `permissions()` (dòng 395-488)
- **Công dụng:** Load danh sách nhân viên + quyền hiện tại → render view
- **Khi sang dự án khác:** Sửa query SQL cho phù hợp bảng/cấu trúc mới (vd: bảng `users`, `user_markets` → `doctors`, `user_departments`)

#### Hàm `save_permissions()` (dòng 493-590)
- **Công dụng:** AJAX POST — thêm/xóa dòng quyền trong DB
- **Cơ chế:**
  1. Nhận `user_id`, `market_id` (scope), `module`, `checked` (0/1)
  2. Kiểm tra người gọi có quyền quản lý scope này không
  3. `checked = 1` → INSERT vào bảng phân quyền
  4. `checked = 0` → DELETE khỏi bảng phân quyền
  5. Ghi log hành động
- **Khi sang dự án khác:** Sửa tên bảng/cột trong query

---

## 3. Cấu trúc Database

### Bảng phân quyền (bắt buộc tạo ở dự án mới)

```sql
-- Dự án Chợ:
CREATE TABLE user_market_permissions (
    permission_user_id INT NOT NULL,
    permission_market_id INT NOT NULL,
    permission_module_code VARCHAR(50) NOT NULL,
    PRIMARY KEY (permission_user_id, permission_market_id, permission_module_code)
);

-- Dự án Bệnh viện (ví dụ):
CREATE TABLE user_dept_permissions (
    user_id INT NOT NULL,
    department_id INT NOT NULL,
    module_code VARCHAR(50) NOT NULL,
    PRIMARY KEY (user_id, department_id, module_code)
);
```

**Quy tắc:** Mỗi dòng = 1 user có quyền truy cập 1 module tại 1 scope.
- Có dòng → có quyền
- Không có dòng → không có quyền

---

## 4. Cách sử dụng trong code

### Kiểm tra quyền ở sidebar/menu (ẩn/hiện):
```php
<?php if (marketService::checkModuleAccess('trader')): ?>
    <li>Quản lý Tiểu thương</li>
<?php endif; ?>
```

### Bắt buộc có quyền ở controller (redirect nếu không):
```php
public function traders() {
    marketService::requireModuleAccess('trader');
    // ... code xử lý
}
```

### Kiểm tra trực tiếp qua permissionService (không qua adapter):
```php
$hasAccess = permissionService::checkAccess('trader', $userId, $scopeId, $actorCode);
```

---

## 5. Checklist khi mang sang dự án mới

- [ ] Copy `permissionservice.class.php` vào `application/` (giữ nguyên, không sửa)
- [ ] Tạo bảng phân quyền trong DB (3 cột: user_id, scope_id, module_code)
- [ ] Gọi `permissionService::init([...])` trong `config.php` nếu bảng/cột/role khác mặc định
- [ ] Viết adapter mới (thay `marketService`): hàm `checkModuleAccess` lấy session → gọi `permissionService`
- [ ] Sửa `$permissionConfig` trong view permissions.php (danh sách module + mẫu gán nhanh)
- [ ] Sửa query SQL trong controller cho phù hợp bảng mới
- [ ] Test: toggle quyền → kiểm tra DB → đăng nhập user khác → menu ẩn/hiện đúng

**Thời gian ước tính: ~15–30 phút** (tùy mức độ khác biệt giữa 2 dự án).