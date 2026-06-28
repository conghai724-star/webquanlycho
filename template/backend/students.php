

<?php require "header.php"; ?>
<script>
$(document).ready(function () {
///-----------Users action-----------
   $(document).on('click', '.create-student-account-btn', function () {
      var $button = $(this);
      $button.prop('disabled', true);
      $.post("<?php echo XC_URL; ?>/api/addstudent", {
         student_code: $button.data('code'), student_name: $button.data('name'), action: 'create'
      }, function (data) {
         if (data.status == 200) Swal.fire('Thành công', data.message, 'success').then(function () { location.reload(); });
         else { Swal.fire('Không thể cấp tài khoản', data.message, 'error'); $button.prop('disabled', false); }
      }, 'json').fail(function () { Swal.fire('Lỗi', 'Không thể kết nối API cấp tài khoản.', 'error'); $button.prop('disabled', false); });
   });

   $(document).on('click', '.student-detail-btn', function () {
      var data = $(this).data();
      $('#detailStudentName').text(data.name || ''); $('#detailStudentCode').text(data.code || '');
      $('#detailStudentPhone').text(data.phone || ''); $('#detailStudentEmail').text(data.email || '');
      $('#detailStudentClass').text(data.class || ''); $('#detailStudentBirthday').text(data.birthday || '');
      $('#detailStudentGender').text(String(data.gender) === '1' ? 'Nam' : (String(data.gender) === '2' ? 'Nữ' : 'Khác'));
      $('#detailStudentMajor').text(data.major || ''); $('#detailStudentGpa').text(data.gpa || '');
   });
   function fillStudentForm(data) {
      data = data || {};
      $('#studentForm')[0].reset();
      $('#studentFormId').val(data.id || '');
      $('#studentFormModalLabel').text(data.id ? 'Chỉnh sửa sinh viên' : 'Thêm sinh viên');
      $('#student_form_student_code').val(data.code || '');
      $('#student_form_student_name').val(data.name || '');
      $('#student_form_student_phone').val(data.phone || '');
      $('#student_form_student_email').val(data.email || '');
      $('#student_form_student_class').val(data.class || '');
      $('#student_form_student_birthday').val(data.birthday || '');
      $('#student_form_student_gender').val(String(data.gender || '0'));
      $('#student_form_student_file').val(data.file || '');
      $('#student_form_student_major_id').val(String(data.major || '1'));
      $('#student_form_student_gpa').val(data.gpa || '');
      $('#student_form_student_rank').val(data.rank || '');
      $('#student_form_student_description').val(data.description || '');
      $('#student_form_student_is_register').val(String(data.register || '0'));
   }

   $(document).on('click', '.btn-open-add-student', function () {
      fillStudentForm({});
   });

   $(document).on('click', '.btn-open-edit-student', function () {
      fillStudentForm($(this).data());
   });

   $('#studentForm').on('submit', function (e) {
      e.preventDefault();
      var $button = $('#studentFormSubmit');
      $button.prop('disabled', true);
      $.ajax({
         type: 'POST',
         url: "<?php echo XC_URL; ?>/api/saveStudentProfile",
         data: $(this).serialize(),
         dataType: 'json',
         success: function (data) {
            if (data.status == 200) {
               $('#studentFormModal').modal('hide');
               Swal.fire({
                  icon: 'success',
                  title: 'Thành công',
                  text: data.message || 'Đã lưu thông tin sinh viên.',
                  timer: 1600,
                  showConfirmButton: false
               });
               setTimeout(function () { location.reload(); }, 1700);
            } else {
               Swal.fire({
                  icon: 'error',
                  title: 'Lỗi',
                  text: data.message || 'Không thể lưu thông tin sinh viên.'
               });
            }
         },
         error: function () {
            Swal.fire({
               icon: 'error',
               title: 'Lỗi',
               text: 'Có lỗi xảy ra khi gọi API lưu sinh viên.'
            });
         },
         complete: function () {
            $button.prop('disabled', false);
         }
      });
   });
    // Reset mật khẩu
    $(document).on('click', '.reset-password-btn', function (e) {
      // e.preventDefault();
      var $btn = $(this);
      var id = $btn.data('id');
      
      $.ajax({
         type: "POST",
         url: "<?php echo XC_URL; ?>/api/resetpassword",
         data: {
            id: id
         },
         dataType: "json",
         success: function (data) {
            if (data.status == 200) {
               Swal.fire({
                  icon: 'success',
                  title: 'Reset mật khẩu thành công',
                  html: 'Mật khẩu của bạn là: <strong style="font-size: 18px; color: #007bff;">' + data.new_password + '</strong>',
                  allowOutsideClick: false,
                  showConfirmButton: true
               });
            } else {
               Swal.fire({
                  icon: 'error',
                  title: 'Lỗi',
                  text: data.message || 'Không thể reset mật khẩu'
               });
            }
         },
         error: function () {
            Swal.fire({
               icon: 'error',
               title: 'Lỗi',
               text: 'Có lỗi xảy ra khi gọi API reset mật khẩu'
            });
         }
      });
   });

    // xóa tài khoản
    $(document).on('click', '.btn-open-delete-student', function (e) {
      e.preventDefault();
      var studentId = $(this).data('student-id');
      var studentStatus = $(this).data('student-status');

      $('#delete_student_id').val(studentId);
      $('#delete_student_status').val(studentStatus);
   });

   $('#confirmDeleteStudentBtn').on('click', function () {
      var id = $('#delete_student_id').val();
      var student_status = $('#delete_student_status').val();

      if (!id) return;

      var $btn = $(this);
      $btn.prop('disabled', true);

      $.ajax({
         type: "POST",
         url: "<?php echo XC_URL; ?>/api/deleteStudentAccount",
         data: {
            id: id,
            student_status: student_status
         },
         dataType: "json",
         success: function (data) {
            if (data.status == 200) {
               $('#deleteUserModal').modal('hide');
               Swal.fire({
                  icon: 'success',
                  title: 'Xóa thành công',
                  timer: 1700,
                  showConfirmButton: false
               });
               setTimeout(function () { location.reload(); }, 1800);
            } else {
               Swal.fire({
                  icon: 'error',
                  title: 'Lỗi',
                  text: data.message || 'Không thể xóa tài khoản'
               });
            }
         },
         error: function () {
            Swal.fire({
               icon: 'error',
               title: 'Lỗi',
               text: 'Có lỗi xảy ra khi gọi API xóa'
            });
         },
         complete: function () {
            $btn.prop('disabled', false);
         }
      });
   });

   $('#studentImportForm').on('submit', function (e) {
      e.preventDefault();
      var formData = new FormData(this);
     
      // console.log(formData);
      var $button = $('#importStudentSubmit');
      $button.prop('disabled', true);

      $.ajax({
         type: 'POST',
         url: "<?php echo XC_URL; ?>/api/insertStudents",
         data: formData,
         processData: false,
         contentType: false,
         dataType: 'json',
         success: function (data) {
            if (data.success) {
               $('#importStudentModal').modal('hide');
               Swal.fire({
                  icon: 'success',
                  title: 'Import hoàn tất',
                  html: 'Đã thêm: <strong>' + data.inserted + '</strong><br>Đã bỏ qua: <strong>' + data.skipped + '</strong>' + (data.errors && data.errors.length ? '<br><pre style="text-align:left; max-height:200px; overflow:auto; white-space:pre-wrap;">' + data.errors.join('\n') + '</pre>' : ''),
                  width: 600
               }).then(function () {
                  location.reload();
               });
            } else {
               Swal.fire({
                  icon: 'error',
                  title: 'Lỗi',
                  text: data.message || 'Không thể import dữ liệu.'
               });
            }
         },
         error: function () {
            Swal.fire({
               icon: 'error',
               title: 'Lỗi',
               text: 'Có lỗi xảy ra khi gọi API import.'
            });
         },
         complete: function () {
            $button.prop('disabled', false);
         }
      });
   });

///-----------END Users action-----------




}); 
  
</script>
      <div class="conatiner-fluid content-inner mt-n5 py-0">
        <div>
          <div class="row">
              <div class="col-sm-12">
                <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">

                <div class="header-title">
                  <h4 class="card-title mb-0">
                      Quản lý Sinh viên
                  </h4>
                </div>

              <div class="d-flex gap-2">
                <!-- <a href="<?php echo XC_URL;?>/admin/students/add" class="btn btn-success rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
                    
                    <svg width="20" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg">

                      <path d="M12 5V19" stroke="currentColor"
                          stroke-width="2" stroke-linecap="round"/>

                      <path d="M5 12H19" stroke="currentColor"
                          stroke-width="2" stroke-linecap="round"/>

                    </svg>

                    <span>Thêm sinh viên</span>

                </a> -->
                <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm d-flex align-items-center gap-2 btn-open-add-student" data-bs-toggle="modal" data-bs-target="#studentFormModal">
                    <i class="fa-solid fa-plus"></i>
                    <span>Thêm sinh viên</span>
                </button>
                 <button type="button" class="btn btn-warning rounded-pill px-4 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#importStudentModal">
                    
                    <svg width="20" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg">

                      <path d="M12 5V19" stroke="currentColor"
                          stroke-width="2" stroke-linecap="round"/>

                      <path d="M5 12H19" stroke="currentColor"
                          stroke-width="2" stroke-linecap="round"/>

                    </svg>

                    <span>Import sinh viên</span>

                </button>
            </div>

           </div>
            <div class="card-body px-0">
               <div class="table-responsive">
                  <table id="student-list-table" class="table table-bordered table-hover" role="grid" data-bs-toggle="data-table">
                     <thead>
                        <tr class="ligth">
                           <th>STT</th>
                           <th>Mã sinh viên</th>
                           <th>Họ và tên</th>
                            <th>Điện thoại</th>
                           <th>Email</th>
                           
                           <th style="min-width: 100px">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                      <?php $i = 1; ?>
                        <?php foreach($students as $student): ?>
                        <tr>
                          <td><?php echo $i; ?></td>
                          <td><?php echo $student->student_code ; ?></td>
                          <td><?php echo $student->student_name; ?></td>
                          <td><?php echo $student->student_phone; ?></td>
                          <td><?php echo $student->student_email; ?></td>
                           <td>
                              <div class="flex align-items-center list-student-action">
                              <?php if(!empty($student->uid)): ?>
                              <a class="btn btn-sm btn-icon btn-secondary reset-password-btn" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="Reset mật khẩu" 
                                    href="#" data-id="<?php echo $student->uid; ?>">
                                      <span class="btn-inner">
                                        <i class="fa-solid fa-key"></i>
                                      </span>
                                  </a>
                              <?php else: ?>
                              <button type="button" class="btn btn-sm btn-success create-student-account-btn"
                                 data-code="<?php echo htmlspecialchars($student->student_code, ENT_QUOTES, 'UTF-8'); ?>"
                                 data-name="<?php echo htmlspecialchars($student->student_name, ENT_QUOTES, 'UTF-8'); ?>"
                                 title="Cấp tài khoản"><i class="fa-solid fa-user-plus"></i></button>
                              <?php endif; ?>
                              <button type="button" class="btn btn-sm btn-icon btn-info student-detail-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#studentDetailModal"
                                    data-code="<?php echo htmlspecialchars($student->student_code, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-name="<?php echo htmlspecialchars($student->student_name, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-phone="<?php echo htmlspecialchars($student->student_phone, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-email="<?php echo htmlspecialchars($student->student_email, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-class="<?php echo htmlspecialchars($student->student_class, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-birthday="<?php echo htmlspecialchars($student->student_birthday, ENT_QUOTES, 'UTF-8'); ?>"
                                    data-gender="<?php echo (int)$student->student_gender; ?>"
                                    data-major="<?php echo (int)$student->student_major_id; ?>"
                                    data-gpa="<?php echo htmlspecialchars($student->student_gpa, ENT_QUOTES, 'UTF-8'); ?>"
                                    title="Xem chi tiết">

                                      <span class="btn-inner">

                                          <svg class="icon-20" width="20" viewBox="0 0 24 24" 
                                              fill="none" xmlns="http://www.w3.org/2000/svg">

                                              <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" 
                                                  stroke="currentColor" 
                                                  stroke-width="1.5"/>

                                              <path d="M2 12C3.6 7.8 7.4 5 12 5C16.6 5 20.4 7.8 22 12C20.4 16.2 16.6 19 12 19C7.4 19 3.6 16.2 2 12Z" 
                                                  stroke="currentColor" 
                                                  stroke-width="1.5" 
                                                  stroke-linecap="round" 
                                                  stroke-linejoin="round"/>

                                          </svg>

                                      </span>

                                  </button>
                                 <button type="button" class="btn btn-sm btn-icon btn-warning btn-open-edit-student" data-bs-toggle="modal" data-bs-target="#studentFormModal" title="Chỉnh sửa" data-id="<?php echo (int)$student->id; ?>" data-code="<?php echo htmlspecialchars($student->student_code, ENT_QUOTES, 'UTF-8'); ?>" data-name="<?php echo htmlspecialchars($student->student_name, ENT_QUOTES, 'UTF-8'); ?>" data-phone="<?php echo htmlspecialchars($student->student_phone, ENT_QUOTES, 'UTF-8'); ?>" data-email="<?php echo htmlspecialchars($student->student_email, ENT_QUOTES, 'UTF-8'); ?>" data-class="<?php echo htmlspecialchars($student->student_class, ENT_QUOTES, 'UTF-8'); ?>" data-birthday="<?php echo htmlspecialchars($student->student_birthday, ENT_QUOTES, 'UTF-8'); ?>" data-gender="<?php echo (int)$student->student_gender; ?>" data-file="<?php echo htmlspecialchars($student->student_file, ENT_QUOTES, 'UTF-8'); ?>" data-major="<?php echo (int)$student->student_major_id; ?>" data-gpa="<?php echo htmlspecialchars($student->student_gpa, ENT_QUOTES, 'UTF-8'); ?>" data-rank="<?php echo htmlspecialchars($student->student_rank, ENT_QUOTES, 'UTF-8'); ?>" data-description="<?php echo htmlspecialchars($student->student_description, ENT_QUOTES, 'UTF-8'); ?>" data-register="<?php echo (int)$student->student_is_register; ?>">
                                    <span class="btn-inner">
                                       <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                       </svg>
                                    </span>
                                 </button>
                                 <?php if(!empty($student->uid)): ?><a class="btn btn-sm btn-icon btn-info"  data-bs-toggle="tooltip" data-bs-placement="top" title="Phân quyền" href="<?php echo XC_URL;?>/admin/students/role/<?php echo $student->uid; ?>">
                                    <span class="btn-inner">
                                       <i class="fa-solid fa-shield-halved" ></i>
                                    </span>
                                 </a><?php endif; ?>
                                 <?php if(!empty($student->uid)): ?><a class="btn btn-sm btn-icon btn-danger btn-open-delete-student"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteUserModal"
                                    data-student-id="<?php echo $student->uid; ?>"
                                    data-student-status="<?php echo isset($student->student_status) ? $student->student_status : ''; ?>"
                                    data-bs-placement="top"
                                    title="Delete"
                                    href="#">
                                    <span class="btn-inner">
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                          <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                          <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                  </a><?php endif; ?>
                              </div>
                           </td>
                        </tr>
                          <?php 
                          $i++;
                          endforeach;
                          ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
      </div>
      <div class="btn-download">
          <a class="btn btn-success px-3 py-2" href="https://iqonic.design/product/admin-templates/hope-ui-admin-free-open-source-bootstrap-admin-template/" target="_blank" >
              <svg class="icon-24"  width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path opacity="0.4" d="M17.554 7.29614C20.005 7.29614 22 9.35594 22 11.8876V16.9199C22 19.4453 20.01 21.5 17.564 21.5L6.448 21.5C3.996 21.5 2 19.4412 2 16.9096V11.8773C2 9.35181 3.991 7.29614 6.438 7.29614H7.378L17.554 7.29614Z" fill="currentColor"></path>
                  <path d="M12.5464 16.0374L15.4554 13.0695C15.7554 12.7627 15.7554 12.2691 15.4534 11.9634C15.1514 11.6587 14.6644 11.6597 14.3644 11.9654L12.7714 13.5905L12.7714 3.2821C12.7714 2.85042 12.4264 2.5 12.0004 2.5C11.5754 2.5 11.2314 2.85042 11.2314 3.2821L11.2314 13.5905L9.63742 11.9654C9.33742 11.6597 8.85043 11.6587 8.54843 11.9634C8.39743 12.1168 8.32142 12.3168 8.32142 12.518C8.32142 12.717 8.39743 12.9171 8.54643 13.0695L11.4554 16.0374C11.6004 16.1847 11.7964 16.268 12.0004 16.268C12.2054 16.268 12.4014 16.1847 12.5464 16.0374Z" fill="currentColor"></path>
              </svg>
          </a>
      </div>

      <!--Modal xóa tài khoản -->
      <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="deleteUserModalLabel">Xác nhận xóa</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  Bạn chắc chắn muốn xóa tài khoản này?
                  <input type="hidden" id="delete_student_id" value="">
                  <input type="hidden" id="delete_student_status" value="">
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                  <button type="button" class="btn btn-danger" id="confirmDeleteStudentBtn">Xác nhận</button>
              </div>
            </div>
        </div>
      </div>
      <!-- END Modal xóa tài khoản -->

      <!-- Modal thêm/sửa sinh viên -->
      <div class="modal fade" id="studentFormModal" tabindex="-1" aria-labelledby="studentFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="studentFormModalLabel">Thêm sinh viên</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="studentForm">
              <div class="modal-body">
                <input type="hidden" name="id" id="studentFormId" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="student_form_student_code">Mã sinh viên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="student_code" id="student_form_student_code" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="student_form_student_name">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="student_name" id="student_form_student_name" required>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="student_form_student_phone">Điện thoại</label>
                    <input type="text" class="form-control" name="student_phone" id="student_form_student_phone">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="student_form_student_email">Email</label>
                    <input type="email" class="form-control" name="student_email" id="student_form_student_email">
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="student_form_student_class">Lớp <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="student_class" id="student_form_student_class" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="student_form_student_birthday">Ngày sinh <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="student_birthday" id="student_form_student_birthday" required>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="student_form_student_gender">Giới tính <span class="text-danger">*</span></label>
                    <select class="form-select" name="student_gender" id="student_form_student_gender" required>
                      <option value="0">Khác</option>
                      <option value="1">Nam</option>
                      <option value="2">Nữ</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="student_form_student_major_id">Ngành học <span class="text-danger">*</span></label>
                    <select class="form-select" name="student_major_id" id="student_form_student_major_id" required>
                      <?php foreach(($job_categories ?? array()) as $category): ?>
                        <option value="<?php echo (int)$category->id; ?>"><?php echo htmlspecialchars($category->job_category_name, ENT_QUOTES, 'UTF-8'); ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label" for="student_form_student_gpa">GPA</label>
                    <input type="number" min="0" max="10" step="0.01" class="form-control" name="student_gpa" id="student_form_student_gpa">
                  </div>
                  <!-- <div class="col-md-3">
                    <label class="form-label" for="student_form_student_is_register">Đã cấp tài khoản</label>
                    <select class="form-select" name="student_is_register" id="student_form_student_is_register">
                      <option value="0">Chưa</option>
                      <option value="1">Đã cấp</option>
                    </select>
                  </div> -->
                  <div class="col-md-6">
                    <label class="form-label" for="student_form_student_rank">Xếp loại</label>
                    <input type="text" class="form-control" name="student_rank" id="student_form_student_rank">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="student_form_student_file">File / đường dẫn hồ sơ</label>
                    <input type="text" class="form-control" name="student_file" id="student_form_student_file">
                  </div>
                  <div class="col-12">
                    <label class="form-label" for="student_form_student_description">Mô tả</label>
                    <textarea class="form-control" name="student_description" id="student_form_student_description" rows="3"></textarea>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" id="studentFormSubmit" class="btn btn-primary">Lưu</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- END Modal thêm/sửa sinh viên -->

      <!-- Modal import sinh viên -->
      <div class="modal fade" id="importStudentModal" tabindex="-1" aria-labelledby="importStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="importStudentModalLabel">Import sinh viên từ Excel/CSV</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="studentImportForm" enctype="multipart/form-data">
              <div class="modal-body">
                <div class="mb-3">
                  <label for="student_file" class="form-label">Chọn file Excel hoặc CSV</label>
                  <input type="file" class="form-control" id="student_file" name="student_file" accept=".xlsx,.csv" required>
                </div>
                <div class="mb-3">
                  <p class="mb-1">Tải file mẫu:</p>
                  <a href="<?php echo XC_URL; ?>/uploads/student-import-template.xlsx" class="btn btn-sm btn-outline-primary" download>Tải file mẫu Excel kèm danh mục</a>
                  <p class="small text-muted mt-2">File mẫu hỗ trợ định dạng .csv và .xlsx. Cột bắt buộc: <strong>student_code</strong>, <strong>student_name</strong>.</p>
                </div>
               
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" id="importStudentSubmit" class="btn btn-primary">Import</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- END Modal import sinh viên -->
      <div class="modal fade" id="studentDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
          <div class="modal-header"><h5 class="modal-title">Chi tiết sinh viên</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
          <div class="modal-body"><dl class="row mb-0">
            <dt class="col-5">Họ tên</dt><dd class="col-7" id="detailStudentName"></dd>
            <dt class="col-5">Mã sinh viên</dt><dd class="col-7" id="detailStudentCode"></dd>
            <dt class="col-5">Điện thoại</dt><dd class="col-7" id="detailStudentPhone"></dd>
            <dt class="col-5">Email</dt><dd class="col-7" id="detailStudentEmail"></dd>
            <dt class="col-5">ID lớp</dt><dd class="col-7" id="detailStudentClass"></dd>
            <dt class="col-5">Ngày sinh</dt><dd class="col-7" id="detailStudentBirthday"></dd>
            <dt class="col-5">Giới tính</dt><dd class="col-7" id="detailStudentGender"></dd>
            <dt class="col-5">ID ngành</dt><dd class="col-7" id="detailStudentMajor"></dd>
            <dt class="col-5">GPA</dt><dd class="col-7" id="detailStudentGpa"></dd>
          </dl></div>
        </div></div>
      </div>
     <?php require "footer.php"; ?>
