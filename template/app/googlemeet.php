<?php require "header.php"; ?>
<?php
$googlemeet_items = is_array($googlemeet_items) ? $googlemeet_items : array();
$googlemeet_employers = is_array($googlemeet_employers) ? $googlemeet_employers : array();
$googlemeet_job_posts = is_array($googlemeet_job_posts) ? $googlemeet_job_posts : array();
$googlemeet_edit = $googlemeet_edit ? $googlemeet_edit : (object) array(
   'id' => 0,
   'meeting_time' => '',
   'employer_id' => 0,
   'job_post_id' => 0,
   'candidate_emails' => '',
   'meet_url' => '',
   'status' => 1
);
?>
<script>
$(document).ready(function () {
   var $table = $('#googlemeet-list-table');
   if ($.fn.DataTable && $table.length) {
      $table.DataTable({
         dom: '<"row align-items-center mb-3"<"col-md-6"l><"col-md-6"f>><"table-responsive border-bottom"rt><"row align-items-center mt-3"<"col-md-6"i><"col-md-6"p>><"clear">',
         pageLength: 10,
         order: [[0, 'desc']],
         columnDefs: [{ targets: [6], orderable: false, searchable: false }],
         language: {
            search: 'Tìm kiếm:',
            lengthMenu: 'Hiển thị _MENU_ phiên',
            info: 'Hiển thị _START_ - _END_ trong _TOTAL_ phiên',
            infoEmpty: 'Không có phiên Google Meet',
            zeroRecords: 'Không tìm thấy phiên phù hợp',
            paginate: { first: 'Đầu', last: 'Cuối', next: 'Sau', previous: 'Trước' }
         }
      });
   }

   var modalElement = document.getElementById('googleMeetModal');
   var googleMeetModal = modalElement ? new bootstrap.Modal(modalElement) : null;
   var form = document.getElementById('googleMeetForm');

   function resetGoogleMeetForm() {
      if (!form) return;
      form.reset();
      form.querySelector('[name="id"]').value = '0';
      form.querySelector('[name="status"]').value = '1';
      document.getElementById('googleMeetModalLabel').textContent = 'Thêm phiên Google Meet';
      var submitLabel = document.getElementById('googleMeetSubmitLabel');
      if (submitLabel) submitLabel.textContent = 'Thêm mới';
   }

   function setValue(name, value) {
      var field = form.querySelector('[name="' + name + '"]');
      if (field) field.value = value == null ? '' : value;
   }

   $('[data-googlemeet-create]').on('click', function () {
      resetGoogleMeetForm();
      if (googleMeetModal) googleMeetModal.show();
   });

   $('[data-googlemeet-edit]').on('click', function () {
      resetGoogleMeetForm();
      var $btn = $(this);
      setValue('id', $btn.data('id'));
      setValue('meeting_time', $btn.data('meeting_time'));
      setValue('employer_id', $btn.data('employer_id'));
      setValue('job_post_id', $btn.data('job_post_id'));
      setValue('candidate_emails', $btn.data('candidate_emails'));
      setValue('meet_url', $btn.data('meet_url'));
      setValue('status', $btn.data('status'));
      document.getElementById('googleMeetModalLabel').textContent = 'Sửa phiên Google Meet';
      var submitLabel = document.getElementById('googleMeetSubmitLabel');
      if (submitLabel) submitLabel.textContent = 'Lưu cập nhật';
      if (googleMeetModal) googleMeetModal.show();
   });

   <?php if ((int)$googlemeet_edit->id > 0): ?>
   resetGoogleMeetForm();
   setValue('id', '<?php echo (int)$googlemeet_edit->id; ?>');
   setValue('meeting_time', '<?php echo htmlspecialchars($googlemeet_edit->meeting_time ? date('Y-m-d\TH:i', strtotime($googlemeet_edit->meeting_time)) : '', ENT_QUOTES); ?>');
   setValue('employer_id', '<?php echo (int)$googlemeet_edit->employer_id; ?>');
   setValue('job_post_id', '<?php echo (int)$googlemeet_edit->job_post_id; ?>');
   setValue('candidate_emails', <?php echo json_encode((string)$googlemeet_edit->candidate_emails); ?>);
   setValue('meet_url', <?php echo json_encode((string)$googlemeet_edit->meet_url); ?>);
   setValue('status', '<?php echo (int)$googlemeet_edit->status; ?>');
   document.getElementById('googleMeetModalLabel').textContent = 'Sửa phiên Google Meet';
   var submitLabelInit = document.getElementById('googleMeetSubmitLabel');
   if (submitLabelInit) submitLabelInit.textContent = 'Lưu cập nhật';
   if (googleMeetModal) googleMeetModal.show();
   <?php endif; ?>
});
</script>

<div class="conatiner-fluid content-inner mt-n5 py-0">
   <?php if(!empty($googlemeet_flash)): ?>
      <div class="alert alert-<?php echo $googlemeet_flash['type'] == 'success' ? 'success' : 'info'; ?> rounded-3"><?php echo htmlspecialchars($googlemeet_flash['message']); ?></div>
   <?php endif; ?>

   <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
         <div class="header-title">
            <h4 class="card-title mb-0">Danh sách phiên Google Meet</h4>
            <small class="text-muted">Quản lý phiên online theo doanh nghiệp, bài đăng tuyển và ứng viên tham gia.</small>
         </div>
         <button type="button" class="btn btn-primary" data-googlemeet-create>
            <i class="fa-solid fa-plus me-1"></i>Thêm mới
         </button>
      </div>
      <div class="card-body px-0">
         <div class="table-responsive">
            <table id="googlemeet-list-table" class="table table-bordered table-hover align-middle mb-0" role="grid">
               <thead>
                  <tr class="ligth">
                     <th>Thời gian</th>
                     <th>Doanh nghiệp</th>
                     <th>Bài đăng</th>
                     <th>Candidate emails</th>
                     <th>Link meet</th>
                     <th>Trạng thái</th>
                     <th style="min-width: 220px">Thao tác</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach($googlemeet_items as $item): ?>
                     <?php $meetingTimeValue = !empty($item->meeting_time) ? date('Y-m-d\TH:i', strtotime($item->meeting_time)) : ''; ?>
                     <tr>
                        <td>
                           <div class="fw-semibold"><?php echo !empty($item->meeting_time) ? htmlspecialchars(date('d/m/Y H:i', strtotime($item->meeting_time))) : ''; ?></div>
                           <small class="text-muted"><?php echo htmlspecialchars(isset($item->created_by_name) ? $item->created_by_name : ''); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars(isset($item->company_name) ? $item->company_name : ''); ?></td>
                        <td><?php echo htmlspecialchars(isset($item->job_title) ? $item->job_title : ''); ?></td>
                        <td><?php echo htmlspecialchars(function_exists('mb_strimwidth') ? mb_strimwidth((string)$item->candidate_emails, 0, 70, '...') : substr((string)$item->candidate_emails, 0, 70)); ?></td>
                        <td><a href="<?php echo htmlspecialchars($item->meet_url); ?>" target="_blank"><?php echo htmlspecialchars($item->meet_url); ?></a></td>
                        <td><span class="badge bg-<?php echo (int)$item->status === 1 ? 'success' : 'secondary'; ?>"><?php echo (int)$item->status === 1 ? 'Hiển thị' : 'Ẩn'; ?></span></td>
                        <td>
                           <div class="d-flex align-items-center gap-2 flex-wrap">
                              <button
                                 type="button"
                                 class="btn btn-sm btn-info text-white"
                                 data-googlemeet-edit
                                 data-id="<?php echo (int)$item->id; ?>"
                                 data-meeting_time="<?php echo htmlspecialchars($meetingTimeValue, ENT_QUOTES); ?>"
                                 data-employer_id="<?php echo (int)$item->employer_id; ?>"
                                 data-job_post_id="<?php echo (int)$item->job_post_id; ?>"
                                 data-candidate_emails="<?php echo htmlspecialchars((string)$item->candidate_emails, ENT_QUOTES); ?>"
                                 data-meet_url="<?php echo htmlspecialchars((string)$item->meet_url, ENT_QUOTES); ?>"
                                 data-status="<?php echo (int)$item->status; ?>"
                              >Sửa</button>
                              <form method="post" class="d-inline">
                                 <input type="hidden" name="id" value="<?php echo (int)$item->id; ?>">
                                 <button class="btn btn-sm btn-danger" name="googlemeet_action" value="delete" onclick="return confirm('Xóa phiên Google Meet này?')">Xóa</button>
                              </form>
                           </div>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="googleMeetModal" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
         <form method="post" id="googleMeetForm">
            <div class="modal-header">
               <h5 class="modal-title" id="googleMeetModalLabel">Thêm phiên Google Meet</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <input type="hidden" name="id" value="0">
               <div class="row">
                  <div class="form-group col-md-6">
                     <label class="form-label">Thời gian meeting</label>
                     <input type="datetime-local" class="form-control" name="meeting_time" required>
                  </div>
                  <div class="form-group col-md-6">
                     <label class="form-label">Trạng thái</label>
                     <select class="form-control" name="status">
                        <option value="1">Hiển thị</option>
                        <option value="0">Ẩn</option>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label class="form-label">Doanh nghiệp</label>
                     <select class="form-control" name="employer_id" required>
                        <option value="">Chọn doanh nghiệp</option>
                        <?php foreach($googlemeet_employers as $employer): ?>
                           <option value="<?php echo (int)$employer->id; ?>"><?php echo htmlspecialchars($employer->company_name); ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="form-group col-md-6">
                     <label class="form-label">Bài đăng tuyển</label>
                     <select class="form-control" name="job_post_id">
                        <option value="">Chọn bài đăng</option>
                        <?php foreach($googlemeet_job_posts as $job_post): ?>
                           <option value="<?php echo (int)$job_post->id; ?>"><?php echo htmlspecialchars(($job_post->company_name ? $job_post->company_name.' - ' : '').$job_post->title); ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="form-label">Candidate emails</label>
                     <textarea class="form-control" name="candidate_emails" rows="4" placeholder="email1@example.com, email2@example.com"></textarea>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="form-label">Link Google Meet</label>
                     <input class="form-control" name="meet_url" required>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
               <button type="submit" class="btn btn-primary" name="googlemeet_action" value="save">
                  <span id="googleMeetSubmitLabel">Thêm mới</span>
               </button>
            </div>
         </form>
      </div>
   </div>
</div>

<?php require "footer.php"; ?>
