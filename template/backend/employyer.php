<?php require "header.php"; ?>
<?php
$employers = is_array($employers) ? $employers : array();
$province_options = is_array($province_options) ? $province_options : array();
$employer_flash = isset($employer_flash) ? $employer_flash : null;
$keyword = isset($keyword) ? $keyword : '';
$linked_status = isset($linked_status) ? $linked_status : '';
$page = isset($page) ? (int)$page : 1;
$per_page = isset($per_page) ? (int)$per_page : 10;
$total_employers = isset($total_employers) ? (int)$total_employers : count($employers);
$total_pages = isset($total_pages) ? (int)$total_pages : 1;
$row_offset = max(0, ($page - 1) * $per_page);
$e = function ($value) {
   return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};
$assetUrl = function ($path) {
   $path = trim((string)$path);
   if ($path === '') {
      return '';
   }
   if (preg_match('/^https?:\/\//i', $path)) {
      return $path;
   }
   return XC_URL.'/'.ltrim($path, '/');
};
$initials = function ($name) {
   $name = trim((string)$name);
   if ($name === '') {
      return 'NTD';
   }
   $parts = preg_split('/\s+/u', $name);
   $letters = '';
   foreach ($parts as $part) {
      if ($part === '') {
         continue;
      }
      $letters .= function_exists('mb_substr') ? mb_substr($part, 0, 1, 'UTF-8') : substr($part, 0, 1);
      if (strlen($letters) >= 2) {
         break;
      }
   }
   return strtoupper($letters !== '' ? $letters : 'NTD');
};
$buildUrl = function ($targetPage) use ($keyword, $linked_status) {
   $params = array('page' => $targetPage);
   if ($keyword !== '') {
      $params['keyword'] = $keyword;
   }
   if ($linked_status !== '') {
      $params['linked_status'] = $linked_status;
   }
   return XC_URL.'/admin/employers?'.http_build_query($params);
};
?>

<style>
   .employer-toolbar {
      gap: 12px;
   }
   .employer-search-card .form-control,
   .employer-search-card .form-select,
   .employer-modal .form-control,
   .employer-modal .form-select,
   .employer-modal .form-control:focus,
   .employer-modal .form-select:focus {
      box-shadow: none;
   }
   .employer-avatar {
      width: 52px;
      height: 52px;
      border-radius: 16px;
      overflow: hidden;
      background: linear-gradient(135deg, #d9f3ff 0%, #f3fbff 100%);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: #0f6c8d;
      border: 1px solid rgba(15, 108, 141, 0.12);
   }
   .employer-avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
   }
   .employer-name {
      font-weight: 600;
      color: #172b4d;
   }
   .employer-meta {
      font-size: 13px;
      color: #6b7a90;
   }
   .employer-action-group {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
   }
   .employer-icon-btn {
      width: 36px;
      height: 36px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(58, 79, 114, 0.12);
      background: #fff;
      color: #314866;
      transition: all .2s ease;
   }
   .employer-icon-btn:hover {
      transform: translateY(-1px);
      color: #079aa2;
      border-color: rgba(7, 154, 162, 0.35);
   }
   .employer-icon-btn.is-linked,
   .employer-icon-btn:disabled {
      color: #11a75c;
      background: #ecfbf3;
      border-color: rgba(17, 167, 92, 0.22);
      cursor: not-allowed;
   }
   .employer-status-badge {
      min-width: 104px;
      border-radius: 999px;
      padding: 8px 12px;
      font-size: 12px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
   }
   .employer-status-badge.linked {
      background: #ecfbf3;
      color: #0d8b4c;
   }
   .employer-status-badge.unlinked {
      background: #fff5e8;
      color: #bf6a00;
   }
   .employer-empty {
      padding: 28px;
      text-align: center;
      color: #6b7a90;
   }
   .employer-detail-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
   }
   .employer-detail-item {
      padding: 14px 16px;
      border-radius: 16px;
      background: #f8fbfd;
      border: 1px solid rgba(58, 79, 114, 0.08);
   }
   .employer-detail-item.full {
      grid-column: 1 / -1;
   }
   .employer-detail-label {
      display: block;
      margin-bottom: 6px;
      font-size: 12px;
      font-weight: 600;
      color: #6b7a90;
      text-transform: uppercase;
      letter-spacing: .04em;
   }
   .employer-preview {
      width: 92px;
      height: 92px;
      border-radius: 20px;
      overflow: hidden;
      background: linear-gradient(135deg, #d9f3ff 0%, #f3fbff 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      font-weight: 700;
      color: #0f6c8d;
      border: 1px solid rgba(15, 108, 141, 0.12);
   }
   .employer-preview img {
      width: 100%;
      height: 100%;
      object-fit: cover;
   }
   @media (max-width: 767px) {
      .employer-detail-grid {
         grid-template-columns: 1fr;
      }
      .employer-toolbar {
         flex-direction: column;
         align-items: stretch !important;
      }
   }
</style>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <?php if (!empty($employer_flash)): ?>
      <div class="alert alert-<?php echo $employer_flash['type'] === 'success' ? 'success' : 'info'; ?> rounded-3">
         <?php echo $e($employer_flash['message']); ?>
      </div>
   <?php endif; ?>

   <div class="row mb-4">
      <div class="col-sm-12">
         <div class="card employer-search-card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between flex-wrap employer-toolbar mb-3">
                  <div>
                     <h4 class="card-title mb-1">Quản lý nhà tuyển dụng</h4>
                                    <small class="text-muted">Tổng cộng <?php echo number_format($total_employers, 0, ',', '.'); ?> nhà tuyển dụng, hiển thị 10 mục mỗi trang.</small>

                  </div>
                  <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" id="btn-open-create-modal">
                     <i class="fa-solid fa-plus"></i>
                     <span>Thêm nhà tuyển dụng</span>
                  </button>
               </div>
               <form method="get" action="<?php echo XC_URL; ?>/admin/employers">
                  <div class="row g-3 align-items-end">
                     <div class="col-lg-6">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" name="keyword" value="<?php echo $e($keyword); ?>" placeholder="Tên nhà tuyển dụng, mã số thuế, tỉnh/thành...">
                     </div>
                     <div class="col-lg-3">
                        <label class="form-label">Đối tác</label>
                        <select class="form-select" name="linked_status">
                           <option value="">Tất cả</option>
                           <option value="linked" <?php echo $linked_status === 'linked' ? 'selected' : ''; ?>>Đã liên kết</option>
                           <option value="unlinked" <?php echo $linked_status === 'unlinked' ? 'selected' : ''; ?>>Chưa liên kết</option>
                        </select>
                     </div>
                     <div class="col-lg-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Lọc dữ liệu</button>
                        <a href="<?php echo XC_URL; ?>/admin/employers" class="btn btn-light flex-grow-1">Làm mới</a>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <!-- <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
               <div class="header-title">
                  <h4 class="card-title mb-1">Danh sách nhà tuyển dụng</h4>
                  <small class="text-muted">Tổng cộng <?php echo number_format($total_employers, 0, ',', '.'); ?> nhà tuyển dụng, hiển thị 10 mục mỗi trang.</small>
               </div>
            </div> -->
            <div class="card-body px-0">
               <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                     <thead>
                        <tr class="ligth">
                           <th style="width: 80px;">STT</th>
                           <th style="width: 90px;">Avatar</th>
                           <th>Tên nhà tuyển dụng</th>
                           <th style="width: 180px;">Tỉnh/thành</th>
                           <th style="width: 140px;">Số bài đăng</th>
                           <th style="width: 150px;">Đối tác</th>
                           <th style="width: 170px;">Chức năng</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (empty($employers)): ?>
                           <tr>
                              <td colspan="7" class="employer-empty">Chưa có nhà tuyển dụng phù hợp với điều kiện tìm kiếm.</td>
                           </tr>
                        <?php else: ?>
                           <?php foreach ($employers as $index => $item): ?>
                              <?php
                                 $logo = $assetUrl(isset($item->logo_url) ? $item->logo_url : '');
                                 $companyName = isset($item->company_name) ? $item->company_name : '';
                                 $provinceName = isset($item->province_name) ? $item->province_name : '';
                                 $totalPosts = isset($item->total_posts) ? (int)$item->total_posts : 0;
                                 $isLinked = isset($item->is_linked_school) && (int)$item->is_linked_school === 1;
                              ?>
                              <tr>
                                 <td class="fw-semibold"><?php echo $row_offset + $index + 1; ?></td>
                                 <td>
                                    <div class="employer-avatar">
                                       <?php if ($logo !== ''): ?>
                                          <img src="<?php echo $e($logo); ?>" alt="<?php echo $e($companyName); ?>">
                                       <?php else: ?>
                                          <span><?php echo $e($initials($companyName)); ?></span>
                                       <?php endif; ?>
                                    </div>
                                 </td>
                                 <td>
                                    <div class="employer-name"><?php echo $e($companyName); ?></div>
                                    <div class="employer-meta"><?php echo $e(isset($item->tax_code) ? $item->tax_code : 'Chưa có mã số thuế'); ?></div>
                                 </td>
                                 <td><?php echo $e($provinceName !== '' ? $provinceName : 'Chưa cập nhật'); ?></td>
                                 <td><span class="badge bg-soft-primary text-primary"><?php echo number_format($totalPosts, 0, ',', '.'); ?> bài</span></td>
                                 <td>
                                    <span class="employer-status-badge <?php echo $isLinked ? 'linked' : 'unlinked'; ?>">
                                       <i class="fa-solid <?php echo $isLinked ? 'fa-circle-check' : 'fa-clock'; ?>"></i>
                                       <?php echo $isLinked ? 'Đã liên kết' : 'Chưa liên kết'; ?>
                                    </span>
                                 </td>
                                 <td>
                                    <div class="employer-action-group">
                                       <button
                                          type="button"
                                          class="employer-icon-btn btn-view-employer"
                                          title="Chi tiết"
                                          data-id="<?php echo (int)$item->id; ?>"
                                          data-company_name="<?php echo $e($companyName); ?>"
                                          data-logo_url="<?php echo $e(isset($item->logo_url) ? $item->logo_url : ''); ?>"
                                          data-province_id="<?php echo isset($item->province_id) ? (int)$item->province_id : 0; ?>"
                                          data-province_name="<?php echo $e($provinceName); ?>"
                                          data-tax_code="<?php echo $e(isset($item->tax_code) ? $item->tax_code : ''); ?>"
                                          data-company_size="<?php echo $e(isset($item->company_size) ? $item->company_size : ''); ?>"
                                          data-website_url="<?php echo $e(isset($item->website_url) ? $item->website_url : ''); ?>"
                                          data-fanpage_url="<?php echo $e(isset($item->fanpage_url) ? $item->fanpage_url : ''); ?>"
                                          data-address_detail="<?php echo $e(isset($item->address_detail) ? $item->address_detail : ''); ?>"
                                          data-description="<?php echo $e(isset($item->description) ? $item->description : ''); ?>"
                                          data-total_posts="<?php echo $totalPosts; ?>"
                                          data-is_linked_school="<?php echo $isLinked ? 1 : 0; ?>"
                                          data-created_at="<?php echo $e(isset($item->created_at) ? $item->created_at : ''); ?>"
                                          data-updated_at="<?php echo $e(isset($item->updated_at) ? $item->updated_at : ''); ?>"
                                       >
                                          <i class="fa-regular fa-eye"></i>
                                       </button>
                                       <button
                                          type="button"
                                          class="employer-icon-btn btn-edit-employer"
                                          title="Sửa"
                                          data-id="<?php echo (int)$item->id; ?>"
                                          data-company_name="<?php echo $e($companyName); ?>"
                                          data-logo_url="<?php echo $e(isset($item->logo_url) ? $item->logo_url : ''); ?>"
                                          data-province_id="<?php echo isset($item->province_id) ? (int)$item->province_id : 0; ?>"
                                          data-province_name="<?php echo $e($provinceName); ?>"
                                          data-tax_code="<?php echo $e(isset($item->tax_code) ? $item->tax_code : ''); ?>"
                                          data-company_size="<?php echo $e(isset($item->company_size) ? $item->company_size : ''); ?>"
                                          data-website_url="<?php echo $e(isset($item->website_url) ? $item->website_url : ''); ?>"
                                          data-fanpage_url="<?php echo $e(isset($item->fanpage_url) ? $item->fanpage_url : ''); ?>"
                                          data-address_detail="<?php echo $e(isset($item->address_detail) ? $item->address_detail : ''); ?>"
                                          data-description="<?php echo $e(isset($item->description) ? $item->description : ''); ?>"
                                       >
                                          <i class="fa-regular fa-pen-to-square"></i>
                                       </button>
                                       <form method="post" class="d-inline">
                                          <input type="hidden" name="employer_action" value="link">
                                          <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>">
                                          <button type="submit" class="employer-icon-btn <?php echo $isLinked ? 'is-linked' : ''; ?>" title="<?php echo $isLinked ? 'Đã liên kết' : 'Liên kết'; ?>" <?php echo $isLinked ? 'disabled' : 'onclick="return confirm(\'Liên kết nhà tuyển dụng này với nhà trường?\')"' ; ?>>
                                             <i class="fa-solid <?php echo $isLinked ? 'fa-link' : 'fa-link'; ?>"></i>
                                          </button>
                                       </form>
                                    </div>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        <?php endif; ?>
                     </tbody>
                  </table>
               </div>

               <?php if ($total_pages > 1): ?>
                  <div class="px-4 py-3 border-top">
                     <nav aria-label="Phân trang nhà tuyển dụng">
                        <ul class="pagination mb-0 justify-content-end flex-wrap">
                           <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                              <a class="page-link" href="<?php echo $page <= 1 ? '#' : $e($buildUrl($page - 1)); ?>">Trước</a>
                           </li>
                           <?php for ($p = 1; $p <= $total_pages; $p++): ?>
                              <li class="page-item <?php echo $p === $page ? 'active' : ''; ?>">
                                 <a class="page-link" href="<?php echo $e($buildUrl($p)); ?>"><?php echo $p; ?></a>
                              </li>
                           <?php endfor; ?>
                           <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                              <a class="page-link" href="<?php echo $page >= $total_pages ? '#' : $e($buildUrl($page + 1)); ?>">Sau</a>
                           </li>
                        </ul>
                     </nav>
                  </div>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade employer-modal" id="employerFormModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
         <form method="post" enctype="multipart/form-data" id="employerForm">
            <input type="hidden" name="employer_action" value="save">
            <input type="hidden" name="id" id="employer-form-id" value="0">
            <div class="modal-header">
               <div>
                  <h5 class="modal-title" id="employerFormTitle">Thêm nhà tuyển dụng</h5>
                  <small class="text-muted">Thông tin thêm và sửa đều được xử lý ngay trên trang quản trị.</small>
               </div>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
               <div class="row g-4">
                  <div class="col-lg-3">
                     <div class="employer-preview" id="employerLogoPreview">NTD</div>
                     <div class="mt-3">
                        <label class="form-label">Avatar</label>
                        <input type="file" class="form-control" name="logo_file" id="employer-logo-file" accept=".jpg,.jpeg,.png,.webp,.gif">
                     </div>
                  </div>
                  <div class="col-lg-9">
                     <div class="row g-3">
                        <div class="col-md-6">
                           <label class="form-label">Tên nhà tuyển dụng</label>
                           <input type="text" class="form-control" name="company_name" id="employer-company-name" required>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Mã số thuế</label>
                           <input type="text" class="form-control" name="tax_code" id="employer-tax-code">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Tỉnh/thành</label>
                           <select class="form-select" name="province_id" id="employer-province-id">
                              <option value="">Chọn tỉnh/thành</option>
                              <?php foreach ($province_options as $province): ?>
                                 <option value="<?php echo (int)$province->id; ?>"><?php echo $e($province->label); ?></option>
                              <?php endforeach; ?>
                           </select>
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Quy mô công ty</label>
                           <input type="text" class="form-control" name="company_size" id="employer-company-size" placeholder="Ví dụ: 50-100 nhân sự">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Website</label>
                           <input type="text" class="form-control" name="website_url" id="employer-website-url" placeholder="https://...">
                        </div>
                        <div class="col-md-6">
                           <label class="form-label">Fanpage</label>
                           <input type="text" class="form-control" name="fanpage_url" id="employer-fanpage-url" placeholder="https://...">
                        </div>
                        <div class="col-12">
                           <label class="form-label">Địa chỉ chi tiết</label>
                           <input type="text" class="form-control" name="address_detail" id="employer-address-detail">
                        </div>
                        <div class="col-12">
                           <label class="form-label">Mô tả</label>
                           <textarea class="form-control" name="description" id="employer-description" rows="5"></textarea>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
               <button type="submit" class="btn btn-primary">Lưu thông tin</button>
            </div>
         </form>
      </div>
   </div>
</div>

<div class="modal fade employer-modal" id="employerDetailModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <div>
               <h5 class="modal-title" id="employer-detail-title">Chi tiết nhà tuyển dụng</h5>
               <small class="text-muted">Thông tin tổng quan và trạng thái liên kết.</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
         </div>
         <div class="modal-body">
            <div class="d-flex align-items-center gap-3 mb-4">
               <div class="employer-preview" id="detail-logo-preview">NTD</div>
               <div>
                  <h5 class="mb-1" id="detail-company-name">Nhà tuyển dụng</h5>
                  <div class="text-muted" id="detail-province-name">Chưa cập nhật tỉnh/thành</div>
               </div>
            </div>
            <div class="employer-detail-grid">
               <div class="employer-detail-item">
                  <span class="employer-detail-label">Mã số thuế</span>
                  <div id="detail-tax-code">Chưa cập nhật</div>
               </div>
               <div class="employer-detail-item">
                  <span class="employer-detail-label">Số bài đăng</span>
                  <div id="detail-total-posts">0 bài</div>
               </div>
               <div class="employer-detail-item">
                  <span class="employer-detail-label">Quy mô công ty</span>
                  <div id="detail-company-size">Chưa cập nhật</div>
               </div>
               <div class="employer-detail-item">
                  <span class="employer-detail-label">Đối tác</span>
                  <div id="detail-linked-status">Chưa liên kết</div>
               </div>
               <div class="employer-detail-item full">
                  <span class="employer-detail-label">Website</span>
                  <div id="detail-website-url">Chưa cập nhật</div>
               </div>
               <div class="employer-detail-item full">
                  <span class="employer-detail-label">Fanpage</span>
                  <div id="detail-fanpage-url">Chưa cập nhật</div>
               </div>
               <div class="employer-detail-item full">
                  <span class="employer-detail-label">Địa chỉ</span>
                  <div id="detail-address-detail">Chưa cập nhật</div>
               </div>
               <div class="employer-detail-item full">
                  <span class="employer-detail-label">Mô tả</span>
                  <div id="detail-description">Chưa có mô tả.</div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   (function () {
      var formModalEl = document.getElementById('employerFormModal');
      var detailModalEl = document.getElementById('employerDetailModal');
      var formModal = formModalEl ? new bootstrap.Modal(formModalEl) : null;
      var detailModal = detailModalEl ? new bootstrap.Modal(detailModalEl) : null;
      var form = document.getElementById('employerForm');
      var formTitle = document.getElementById('employerFormTitle');
      var formId = document.getElementById('employer-form-id');
      var fieldCompanyName = document.getElementById('employer-company-name');
      var fieldTaxCode = document.getElementById('employer-tax-code');
      var fieldProvinceId = document.getElementById('employer-province-id');
      var fieldCompanySize = document.getElementById('employer-company-size');
      var fieldWebsiteUrl = document.getElementById('employer-website-url');
      var fieldFanpageUrl = document.getElementById('employer-fanpage-url');
      var fieldAddressDetail = document.getElementById('employer-address-detail');
      var fieldDescription = document.getElementById('employer-description');
      var logoFile = document.getElementById('employer-logo-file');
      var logoPreview = document.getElementById('employerLogoPreview');

      function getInitials(name) {
         if (!name) {
            return 'NTD';
         }
         var parts = name.trim().split(/\s+/).filter(Boolean);
         var letters = parts.slice(0, 2).map(function (part) {
            return part.charAt(0);
         }).join('');
         return (letters || 'NTD').toUpperCase();
      }

      function resolveAsset(path) {
         if (!path) {
            return '';
         }
         if (/^https?:\/\//i.test(path)) {
            return path;
         }
         return '<?php echo XC_URL; ?>/' + String(path).replace(/^\/+/, '');
      }

      function setPreview(element, path, name) {
         if (!element) {
            return;
         }
         var asset = resolveAsset(path);
         if (asset) {
            element.innerHTML = '<img src="' + asset.replace(/"/g, '&quot;') + '" alt="' + String(name || '').replace(/"/g, '&quot;') + '">';
         } else {
            element.textContent = getInitials(name);
         }
      }

      function fillForm(data) {
         formId.value = data.id || 0;
         fieldCompanyName.value = data.company_name || '';
         fieldTaxCode.value = data.tax_code || '';
         fieldProvinceId.value = data.province_id || '';
         fieldCompanySize.value = data.company_size || '';
         fieldWebsiteUrl.value = data.website_url || '';
         fieldFanpageUrl.value = data.fanpage_url || '';
         fieldAddressDetail.value = data.address_detail || '';
         fieldDescription.value = data.description || '';
         if (logoFile) {
            logoFile.value = '';
         }
         setPreview(logoPreview, data.logo_url || '', data.company_name || '');
      }

      function resetForm() {
         form.reset();
         formId.value = 0;
         formTitle.textContent = 'Thêm nhà tuyển dụng';
         setPreview(logoPreview, '', '');
      }

      var createButton = document.getElementById('btn-open-create-modal');
      if (createButton) {
         createButton.addEventListener('click', function () {
            resetForm();
            if (formModal) {
               formModal.show();
            }
         });
      }

      document.querySelectorAll('.btn-edit-employer').forEach(function (button) {
         button.addEventListener('click', function () {
            var data = button.dataset;
            fillForm(data);
            formTitle.textContent = 'Sửa nhà tuyển dụng';
            if (formModal) {
               formModal.show();
            }
         });
      });

      document.querySelectorAll('.btn-view-employer').forEach(function (button) {
         button.addEventListener('click', function () {
            var data = button.dataset;
            document.getElementById('employer-detail-title').textContent = 'Chi tiết nhà tuyển dụng';
            document.getElementById('detail-company-name').textContent = data.company_name || 'Nhà tuyển dụng';
            document.getElementById('detail-province-name').textContent = data.province_name || 'Chưa cập nhật tỉnh/thành';
            document.getElementById('detail-tax-code').textContent = data.tax_code || 'Chưa cập nhật';
            document.getElementById('detail-total-posts').textContent = (data.total_posts || '0') + ' bài';
            document.getElementById('detail-company-size').textContent = data.company_size || 'Chưa cập nhật';
            document.getElementById('detail-linked-status').textContent = String(data.is_linked_school) === '1' ? 'Đã liên kết với nhà trường' : 'Chưa liên kết';
            document.getElementById('detail-address-detail').textContent = data.address_detail || 'Chưa cập nhật';
            document.getElementById('detail-description').textContent = data.description || 'Chưa có mô tả.';

            var website = document.getElementById('detail-website-url');
            if (data.website_url) {
               website.innerHTML = '<a href="' + data.website_url.replace(/"/g, '&quot;') + '" target="_blank" rel="noopener noreferrer">' + data.website_url + '</a>';
            } else {
               website.textContent = 'Chưa cập nhật';
            }

            var fanpage = document.getElementById('detail-fanpage-url');
            if (data.fanpage_url) {
               fanpage.innerHTML = '<a href="' + data.fanpage_url.replace(/"/g, '&quot;') + '" target="_blank" rel="noopener noreferrer">' + data.fanpage_url + '</a>';
            } else {
               fanpage.textContent = 'Chưa cập nhật';
            }

            setPreview(document.getElementById('detail-logo-preview'), data.logo_url || '', data.company_name || '');

            if (detailModal) {
               detailModal.show();
            }
         });
      });

      if (logoFile) {
         logoFile.addEventListener('change', function (event) {
            var file = event.target.files && event.target.files[0];
            if (!file) {
               return;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
               logoPreview.innerHTML = '<img src="' + e.target.result + '" alt="Avatar">';
            };
            reader.readAsDataURL(file);
         });
      }
   })();
</script>

<?php require "footer.php"; ?>
