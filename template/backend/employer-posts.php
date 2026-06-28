<?php require "header.php"; ?>
<?php
$employer_posts = is_array($employer_posts) ? $employer_posts : array();
$employer_options = is_array($employer_options) ? $employer_options : array();
$job_category_options = is_array($job_category_options) ? $job_category_options : array();
$province_options = is_array($province_options) ? $province_options : array();
$salary_options = is_array($salary_options) ? $salary_options : array();
$page = isset($page) ? (int)$page : 1;
$per_page = isset($per_page) ? (int)$per_page : 10;
$total_posts = isset($total_posts) ? (int)$total_posts : count($employer_posts);
$total_pages = isset($total_pages) ? (int)$total_pages : 1;
$row_offset = max(0, ($page - 1) * $per_page);
$employer_post_edit = $employer_post_edit ? $employer_post_edit : (object) array(
   'id' => 0,
   'employer_id' => '',
   'job_category_id' => '',
   'province_id' => '',
   'title' => '',
   'quantity' => 1,
   'job_description' => '',
   'experience_years' => 0,
   'degree_required' => '',
   'salary_id' => '',
   'benefits_description' => '',
   'work_type' => '',
   'address_detail' => '',
   'deadline' => '',
   'status' => 'pending'
);
$post_statuses = array(
   'draft' => 'Nháp',
   'pending' => 'Chờ duyệt',
   'published' => 'Đang đăng',
   'closed' => 'Đóng',
   'rejected' => 'Từ chối'
);
$e = function ($value) {
   return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$buildUrl = function ($targetPage) {
   return XC_URL.'/admin/employers/posts?page='.(int) $targetPage;
};
$importTemplateUrl = XC_URL.'/uploads/employer-posts-import-template.csv';
?>

<style>
   .post-toolbar {
      gap: 12px;
   }
   .post-bulk-bar {
      gap: 12px;
   }
   .post-bulk-actions,
   .post-action-group {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
   }
   .post-check-cell {
      width: 52px;
      text-align: center;
   }
   .post-check-input {
      width: 18px;
      height: 18px;
      cursor: pointer;
   }
   .post-icon-btn {
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
   .post-icon-btn:hover {
      transform: translateY(-1px);
      color: #079aa2;
      border-color: rgba(7, 154, 162, 0.35);
   }
   .post-icon-btn.approved,
   .post-icon-btn:disabled {
      color: #0d8b4c;
      background: #ecfbf3;
      border-color: rgba(17, 167, 92, 0.2);
      cursor: not-allowed;
   }
   .post-detail-grid {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
   }
   .post-detail-item {
      padding: 14px 16px;
      border-radius: 16px;
      background: #f8fbfd;
      border: 1px solid rgba(58, 79, 114, 0.08);
   }
   .post-detail-item.full {
      grid-column: 1 / -1;
   }
   .post-detail-label {
      display: block;
      margin-bottom: 6px;
      font-size: 12px;
      font-weight: 600;
      color: #6b7a90;
      text-transform: uppercase;
      letter-spacing: .04em;
   }
   .post-import-note {
      padding: 12px 14px;
      border-radius: 14px;
      background: #f8fbfd;
      border: 1px solid rgba(58, 79, 114, 0.08);
   }
   .post-empty {
      padding: 28px;
      text-align: center;
      color: #6b7a90;
   }
   @media (max-width: 767px) {
      .post-detail-grid {
         grid-template-columns: 1fr;
      }
      .post-toolbar,
      .post-bulk-bar,
      .post-bulk-actions,
      .modal-footer {
         flex-direction: column;
         align-items: stretch !important;
      }
      .post-toolbar .btn,
      .post-bulk-actions .btn,
      .modal-footer .btn {
         width: 100%;
         justify-content: center;
      }
      .modal-dialog {
         margin: .75rem;
      }
   }
</style>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <?php if (!empty($employer_post_flash)): ?>
      <div class="alert alert-<?php echo $employer_post_flash['type'] === 'success' ? 'success' : 'info'; ?> rounded-3">
         <?php echo $e($employer_post_flash['message']); ?>
      </div>
   <?php endif; ?>

   <div class="row mb-4">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between flex-wrap post-toolbar">
                  <div>
                     <h4 class="card-title mb-1">Danh sách bài đăng tuyển dụng</h4>
                     <p class="mb-0 text-muted">Quản lý bài đăng, import nhanh từ CSV và duyệt xuất bản ngay trên trang admin.</p>
                  </div>
                  <div class="d-flex gap-2 flex-wrap">
                     <button type="button" class="btn btn-success d-inline-flex align-items-center gap-2" id="btn-open-import-modal">
                        <i class="fa-solid fa-file-import"></i>
                        <span>Import</span>
                     </button>
                     <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-2" id="btn-open-post-modal">
                        <i class="fa-solid fa-plus"></i>
                        <span>Thêm bài đăng</span>
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
               <div class="header-title">
                  <h4 class="card-title mb-0">Danh sách bài đăng tuyển dụng</h4>
                  <small class="text-muted">Tổng cộng <?php echo number_format($total_posts, 0, ',', '.'); ?> bài đăng, hiển thị <?php echo $per_page; ?> mục mỗi trang.</small>
               </div>
            </div>
            <div class="card-body px-0">
               <form method="post" id="bulkApproveForm" class="px-4 pb-3 border-bottom">
                  <input type="hidden" name="employer_post_action" value="approve_selected">
                  <div class="d-flex align-items-center justify-content-between flex-wrap post-bulk-bar">
                     <div class="post-bulk-actions">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                           <i class="fa-solid fa-circle-check"></i>
                           <span>Duyệt đã chọn</span>
                        </button>
                        <a href="<?php echo $e($importTemplateUrl); ?>" class="btn btn-light d-inline-flex align-items-center gap-2" download>
                           <i class="fa-regular fa-file-lines"></i>
                           <span>Tải file mẫu import</span>
                        </a>
                     </div>
                     <small class="text-muted">Chọn nhiều bài đăng rồi bấm duyệt để chuyển trạng thái sang published.</small>
                  </div>
               </form>
               <div class="table-responsive">
                  <table class="table table-bordered table-hover align-middle mb-0" role="grid">
                     <thead>
                        <tr class="ligth">
                           <th class="post-check-cell">
                              <input type="checkbox" class="form-check-input post-check-input" id="select-all-posts" aria-label="Chọn tất cả">
                           </th>
                           <th>STT</th>
                           <th>Tiêu đề</th>
                           <th>Nhà tuyển dụng</th>
                           <th>Khu vực</th>
                           <th>Hạn nộp</th>
                           <th>Trạng thái</th>
                           <th style="min-width: 280px">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (empty($employer_posts)): ?>
                           <tr>
                              <td colspan="8" class="post-empty">Chưa có bài đăng tuyển dụng nào.</td>
                           </tr>
                        <?php else: ?>
                           <?php foreach ($employer_posts as $index => $item): ?>
                              <?php
                              $status = isset($item->status) ? (string) $item->status : 'pending';
                              $statusClass = $status === 'published' ? 'success' : ($status === 'pending' ? 'warning' : ($status === 'rejected' ? 'danger' : 'secondary'));
                              $isPublished = $status === 'published';
                              ?>
                              <tr>
                                 <td class="post-check-cell">
                                    <input
                                       type="checkbox"
                                       class="form-check-input post-check-input post-row-check"
                                       name="selected_ids[]"
                                       value="<?php echo (int) $item->id; ?>"
                                       form="bulkApproveForm"
                                       aria-label="Chọn bài đăng #<?php echo (int) $item->id; ?>"
                                       <?php echo $isPublished ? 'disabled' : ''; ?>
                                    >
                                 </td>
                                 <td><?php echo $row_offset + $index + 1; ?></td>
                                 <td>
                                    <div class="fw-semibold"><?php echo $e($item->title); ?></div>
                                    <small class="text-muted">#<?php echo (int) $item->id; ?><?php echo isset($item->job_category_name) && $item->job_category_name !== '' ? ' · '.$e($item->job_category_name) : ''; ?></small>
                                 </td>
                                 <td><?php echo $e(isset($item->company_name) ? $item->company_name : ''); ?></td>
                                 <td><?php echo $e(isset($item->province_name) ? $item->province_name : ''); ?></td>
                                 <td><?php echo $e(isset($item->deadline) ? $item->deadline : ''); ?></td>
                                 <td>
                                    <span class="badge bg-<?php echo $statusClass; ?>">
                                       <?php echo isset($post_statuses[$status]) ? $post_statuses[$status] : $e($status); ?>
                                    </span>
                                 </td>
                                 <td>
                                    <div class="post-action-group">
                                       <button
                                          type="button"
                                          class="post-icon-btn btn-view-post"
                                          title="Chi tiết"
                                          data-id="<?php echo (int) $item->id; ?>"
                                          data-title="<?php echo $e($item->title); ?>"
                                          data-company_name="<?php echo $e(isset($item->company_name) ? $item->company_name : ''); ?>"
                                          data-job_category_name="<?php echo $e(isset($item->job_category_name) ? $item->job_category_name : ''); ?>"
                                          data-province_name="<?php echo $e(isset($item->province_name) ? $item->province_name : ''); ?>"
                                          data-salary_name="<?php echo $e(isset($item->salary_name) ? $item->salary_name : ''); ?>"
                                          data-job_description="<?php echo $e(isset($item->job_description) ? $item->job_description : ''); ?>"
                                          data-benefits_description="<?php echo $e(isset($item->benefits_description) ? $item->benefits_description : ''); ?>"
                                          data-address_detail="<?php echo $e(isset($item->address_detail) ? $item->address_detail : ''); ?>"
                                          data-work_type="<?php echo $e(isset($item->work_type) ? $item->work_type : ''); ?>"
                                          data-status="<?php echo $e($status); ?>"
                                          data-deadline="<?php echo $e(isset($item->deadline) ? $item->deadline : ''); ?>"
                                          data-published_at="<?php echo $e(isset($item->published_at) ? $item->published_at : ''); ?>"
                                       >
                                          <i class="fa-regular fa-eye"></i>
                                       </button>
                                       <button
                                          type="button"
                                          class="post-icon-btn btn-edit-post"
                                          title="Sửa"
                                          data-id="<?php echo (int) $item->id; ?>"
                                          data-employer_id="<?php echo (int) $item->employer_id; ?>"
                                          data-job_category_id="<?php echo isset($item->job_category_id) ? (int) $item->job_category_id : 0; ?>"
                                          data-province_id="<?php echo isset($item->province_id) ? (int) $item->province_id : 0; ?>"
                                          data-title="<?php echo $e($item->title); ?>"
                                          data-quantity="<?php echo isset($item->quantity) ? (int) $item->quantity : 1; ?>"
                                          data-job_description="<?php echo $e(isset($item->job_description) ? $item->job_description : ''); ?>"
                                          data-experience_years="<?php echo isset($item->experience_years) ? (int) $item->experience_years : 0; ?>"
                                          data-degree_required="<?php echo $e(isset($item->degree_required) ? $item->degree_required : ''); ?>"
                                          data-salary_id="<?php echo isset($item->salary_id) ? (int) $item->salary_id : 0; ?>"
                                          data-benefits_description="<?php echo $e(isset($item->benefits_description) ? $item->benefits_description : ''); ?>"
                                          data-work_type="<?php echo $e(isset($item->work_type) ? $item->work_type : ''); ?>"
                                          data-address_detail="<?php echo $e(isset($item->address_detail) ? $item->address_detail : ''); ?>"
                                          data-deadline="<?php echo $e(isset($item->deadline) ? $item->deadline : ''); ?>"
                                          data-status="<?php echo $e($status); ?>"
                                       >
                                          <i class="fa-regular fa-pen-to-square"></i>
                                       </button>
                                       <form method="post" class="d-inline">
                                          <input type="hidden" name="id" value="<?php echo (int) $item->id; ?>">
                                          <button class="post-icon-btn <?php echo $isPublished ? 'approved' : ''; ?>" name="employer_post_action" value="approve" title="<?php echo $isPublished ? 'Đã duyệt' : 'Duyệt bài đăng'; ?>" <?php echo $isPublished ? 'disabled' : 'onclick="return confirm(\'Duyệt và xuất bản bài đăng này?\')"'; ?>>
                                             <i class="fa-solid fa-circle-check"></i>
                                          </button>
                                       </form>
                                       <form method="post" class="d-inline">
                                          <input type="hidden" name="id" value="<?php echo (int) $item->id; ?>">
                                          <button class="post-icon-btn" name="employer_post_action" value="delete" title="Xóa" onclick="return confirm('Xóa bài đăng tuyển dụng này?')">
                                             <i class="fa-regular fa-trash-can"></i>
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
                     <nav aria-label="Phân trang bài đăng tuyển dụng">
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

<div class="modal fade" id="postFormModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
         <form method="post" id="postForm">
            <input type="hidden" name="employer_post_action" value="save">
            <input type="hidden" name="id" id="post-form-id" value="0">
            <div class="modal-header">
               <div>
                  <h5 class="modal-title" id="postFormTitle">Thêm bài đăng</h5>
                  <small class="text-muted">Tạo mới hoặc chỉnh sửa bài đăng tuyển dụng trong modal.</small>
               </div>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
               <div class="row g-3">
                  <div class="col-md-6">
                     <label class="form-label">Nhà tuyển dụng</label>
                     <select class="form-control" name="employer_id" id="post-employer-id" required>
                        <option value="">Chọn nhà tuyển dụng</option>
                        <?php foreach ($employer_options as $opt): ?>
                           <option value="<?php echo (int) $opt->id; ?>"><?php echo $e($opt->label); ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Tiêu đề tuyển dụng</label>
                     <input class="form-control" name="title" id="post-title" required>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Ngành nghề</label>
                     <select class="form-control" name="job_category_id" id="post-job-category-id">
                        <option value="">Chọn</option>
                        <?php foreach ($job_category_options as $opt): ?>
                           <option value="<?php echo (int) $opt->id; ?>"><?php echo $e($opt->label); ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="col-md-6">
                     <label class="form-label">Khu vực</label>
                     <select class="form-control" name="province_id" id="post-province-id">
                        <option value="">Chọn</option>
                        <?php foreach ($province_options as $opt): ?>
                           <option value="<?php echo (int) $opt->id; ?>"><?php echo $e($opt->label); ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="col-md-4">
                     <label class="form-label">Mức lương</label>
                     <select class="form-control" name="salary_id" id="post-salary-id">
                        <option value="">Chọn</option>
                        <?php foreach ($salary_options as $opt): ?>
                           <option value="<?php echo (int) $opt->id; ?>"><?php echo $e($opt->label); ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="col-md-4">
                     <label class="form-label">Số lượng</label>
                     <input type="number" class="form-control" name="quantity" id="post-quantity" min="1" value="1">
                  </div>
                  <div class="col-md-4">
                     <label class="form-label">Kinh nghiệm (năm)</label>
                     <input type="number" class="form-control" name="experience_years" id="post-experience-years" min="0" value="0">
                  </div>
                  <div class="col-md-4">
                     <label class="form-label">Bằng cấp</label>
                     <input class="form-control" name="degree_required" id="post-degree-required">
                  </div>
                  <div class="col-md-4">
                     <label class="form-label">Hình thức</label>
                     <input class="form-control" name="work_type" id="post-work-type">
                  </div>
                  <div class="col-md-4">
                     <label class="form-label">Hạn nộp</label>
                     <input type="date" class="form-control" name="deadline" id="post-deadline" required>
                  </div>
                  <div class="col-md-12">
                     <label class="form-label">Địa chỉ cụ thể</label>
                     <input class="form-control" name="address_detail" id="post-address-detail">
                  </div>
                  <div class="col-md-12">
                     <label class="form-label">Mô tả công việc</label>
                     <textarea class="form-control" name="job_description" id="post-job-description" rows="4"></textarea>
                  </div>
                  <div class="col-md-12">
                     <label class="form-label">Phúc lợi</label>
                     <textarea class="form-control" name="benefits_description" id="post-benefits-description" rows="3"></textarea>
                  </div>
                  <div class="col-md-4">
                     <label class="form-label">Trạng thái</label>
                     <select class="form-control" name="status" id="post-status">
                        <?php foreach ($post_statuses as $key => $label): ?>
                           <option value="<?php echo $key; ?>"><?php echo $e($label); ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
               <button type="submit" class="btn btn-primary">Lưu bài đăng</button>
            </div>
         </form>
      </div>
   </div>
</div>

<div class="modal fade" id="postImportModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
         <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="employer_post_action" value="import">
            <div class="modal-header">
               <div>
                  <h5 class="modal-title">Import bài đăng (CSV)</h5>
                  <small class="text-muted">Tải file CSV để thêm nhanh nhiều bài đăng cùng lúc.</small>
               </div>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
               <div class="post-import-note mb-3">
                  <div class="fw-semibold mb-1">File mẫu import</div>
                  <p class="mb-2 text-muted">Tải file CSV mẫu để điền đúng cấu trúc cột trước khi import hàng loạt.</p>
                  <a href="<?php echo $e($importTemplateUrl); ?>" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-2" download>
                     <i class="fa-regular fa-file-lines"></i>
                     <span>Tải file mẫu</span>
                  </a>
               </div>
               <div class="form-group">
                  <label class="form-label">Chọn file CSV</label>
                  <input type="file" class="form-control" name="import_file" accept=".csv" required>
                  <small class="text-muted d-block mt-2">Header hỗ trợ: employer_id, title, job_category_id, province_id, quantity, job_description, experience_years, degree_required, salary_id, benefits_description, work_type, address_detail, deadline, status</small>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
               <button type="submit" class="btn btn-success">Import dữ liệu</button>
            </div>
         </form>
      </div>
   </div>
</div>

<div class="modal fade" id="postDetailModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
         <div class="modal-header">
            <div>
               <h5 class="modal-title" id="post-detail-title">Chi tiết bài đăng</h5>
               <small class="text-muted">Thông tin chi tiết của bài đăng tuyển dụng.</small>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
         </div>
         <div class="modal-body">
            <div class="post-detail-grid">
               <div class="post-detail-item">
                  <span class="post-detail-label">Nhà tuyển dụng</span>
                  <div id="detail-company-name">-</div>
               </div>
               <div class="post-detail-item">
                  <span class="post-detail-label">Ngành nghề</span>
                  <div id="detail-job-category-name">-</div>
               </div>
               <div class="post-detail-item">
                  <span class="post-detail-label">Khu vực</span>
                  <div id="detail-province-name">-</div>
               </div>
               <div class="post-detail-item">
                  <span class="post-detail-label">Mức lương</span>
                  <div id="detail-salary-name">-</div>
               </div>
               <div class="post-detail-item">
                  <span class="post-detail-label">Trạng thái</span>
                  <div id="detail-status">-</div>
               </div>
               <div class="post-detail-item">
                  <span class="post-detail-label">Hạn nộp</span>
                  <div id="detail-deadline">-</div>
               </div>
               <div class="post-detail-item full">
                  <span class="post-detail-label">Địa chỉ</span>
                  <div id="detail-address-detail">-</div>
               </div>
               <div class="post-detail-item full">
                  <span class="post-detail-label">Mô tả công việc</span>
                  <div id="detail-job-description">-</div>
               </div>
               <div class="post-detail-item full">
                  <span class="post-detail-label">Phúc lợi</span>
                  <div id="detail-benefits-description">-</div>
               </div>
               <div class="post-detail-item full">
                  <span class="post-detail-label">Ngày xuất bản</span>
                  <div id="detail-published-at">-</div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   (function () {
      var formModal = new bootstrap.Modal(document.getElementById('postFormModal'));
      var importModal = new bootstrap.Modal(document.getElementById('postImportModal'));
      var detailModal = new bootstrap.Modal(document.getElementById('postDetailModal'));
      var form = document.getElementById('postForm');
      var formTitle = document.getElementById('postFormTitle');
      var bulkApproveForm = document.getElementById('bulkApproveForm');
      var selectAllPosts = document.getElementById('select-all-posts');
      var postRowChecks = Array.prototype.slice.call(document.querySelectorAll('.post-row-check'));

      function setValue(id, value) {
         var field = document.getElementById(id);
         if (field) {
            field.value = value || '';
         }
      }

      function resetForm() {
         form.reset();
         document.getElementById('post-form-id').value = 0;
         document.getElementById('post-quantity').value = 1;
         document.getElementById('post-experience-years').value = 0;
         document.getElementById('post-status').value = 'pending';
         formTitle.textContent = 'Thêm bài đăng';
      }

      document.getElementById('btn-open-post-modal').addEventListener('click', function () {
         resetForm();
         formModal.show();
      });

      document.getElementById('btn-open-import-modal').addEventListener('click', function () {
         importModal.show();
      });

      if (selectAllPosts) {
         selectAllPosts.addEventListener('change', function () {
            postRowChecks.forEach(function (checkbox) {
               if (!checkbox.disabled) {
                  checkbox.checked = selectAllPosts.checked;
               }
            });
            selectAllPosts.indeterminate = false;
         });

         postRowChecks.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
               var activeChecks = postRowChecks.filter(function (item) {
                  return !item.disabled;
               });
               var checkedCount = activeChecks.filter(function (item) {
                  return item.checked;
               }).length;
               selectAllPosts.checked = activeChecks.length > 0 && checkedCount === activeChecks.length;
               selectAllPosts.indeterminate = checkedCount > 0 && checkedCount < activeChecks.length;
            });
         });
      }

      if (bulkApproveForm) {
         bulkApproveForm.addEventListener('submit', function (event) {
            var checkedItems = postRowChecks.filter(function (checkbox) {
               return checkbox.checked && !checkbox.disabled;
            });
            if (checkedItems.length === 0) {
               event.preventDefault();
               window.alert('Vui lòng chọn ít nhất một bài đăng để duyệt.');
               return;
            }
            if (!window.confirm('Duyệt và xuất bản các bài đăng đã chọn?')) {
               event.preventDefault();
            }
         });
      }

      document.querySelectorAll('.btn-edit-post').forEach(function (button) {
         button.addEventListener('click', function () {
            var data = button.dataset;
            setValue('post-form-id', data.id);
            setValue('post-employer-id', data.employer_id);
            setValue('post-job-category-id', data.job_category_id);
            setValue('post-province-id', data.province_id);
            setValue('post-title', data.title);
            setValue('post-quantity', data.quantity);
            setValue('post-job-description', data.job_description);
            setValue('post-experience-years', data.experience_years);
            setValue('post-degree-required', data.degree_required);
            setValue('post-salary-id', data.salary_id);
            setValue('post-benefits-description', data.benefits_description);
            setValue('post-work-type', data.work_type);
            setValue('post-address-detail', data.address_detail);
            setValue('post-deadline', data.deadline);
            setValue('post-status', data.status);
            formTitle.textContent = 'Sửa bài đăng';
            formModal.show();
         });
      });

      document.querySelectorAll('.btn-view-post').forEach(function (button) {
         button.addEventListener('click', function () {
            var data = button.dataset;
            document.getElementById('post-detail-title').textContent = data.title || 'Chi tiết bài đăng';
            document.getElementById('detail-company-name').textContent = data.company_name || '-';
            document.getElementById('detail-job-category-name').textContent = data.job_category_name || '-';
            document.getElementById('detail-province-name').textContent = data.province_name || '-';
            document.getElementById('detail-salary-name').textContent = data.salary_name || '-';
            document.getElementById('detail-status').textContent = data.status || '-';
            document.getElementById('detail-deadline').textContent = data.deadline || '-';
            document.getElementById('detail-address-detail').textContent = data.address_detail || '-';
            document.getElementById('detail-job-description').textContent = data.job_description || '-';
            document.getElementById('detail-benefits-description').textContent = data.benefits_description || '-';
            document.getElementById('detail-published-at').textContent = data.published_at || 'Chưa xuất bản';
            detailModal.show();
         });
      });

      <?php if ((int) $employer_post_edit->id > 0): ?>
      resetForm();
      setValue('post-form-id', '<?php echo (int) $employer_post_edit->id; ?>');
      setValue('post-employer-id', '<?php echo (int) $employer_post_edit->employer_id; ?>');
      setValue('post-job-category-id', '<?php echo (int) $employer_post_edit->job_category_id; ?>');
      setValue('post-province-id', '<?php echo (int) $employer_post_edit->province_id; ?>');
      setValue('post-title', '<?php echo $e($employer_post_edit->title); ?>');
      setValue('post-quantity', '<?php echo (int) $employer_post_edit->quantity; ?>');
      setValue('post-job-description', '<?php echo $e($employer_post_edit->job_description); ?>');
      setValue('post-experience-years', '<?php echo (int) $employer_post_edit->experience_years; ?>');
      setValue('post-degree-required', '<?php echo $e($employer_post_edit->degree_required); ?>');
      setValue('post-salary-id', '<?php echo (int) $employer_post_edit->salary_id; ?>');
      setValue('post-benefits-description', '<?php echo $e(isset($employer_post_edit->benefits_description) ? $employer_post_edit->benefits_description : ''); ?>');
      setValue('post-work-type', '<?php echo $e($employer_post_edit->work_type); ?>');
      setValue('post-address-detail', '<?php echo $e($employer_post_edit->address_detail); ?>');
      setValue('post-deadline', '<?php echo $e($employer_post_edit->deadline); ?>');
      setValue('post-status', '<?php echo $e($employer_post_edit->status); ?>');
      formTitle.textContent = 'Sửa bài đăng';
      formModal.show();
      <?php endif; ?>
   })();
</script>

<?php require "footer.php"; ?>
