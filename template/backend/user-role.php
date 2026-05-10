
<?php require "header.php"; ?>
<script>
	$(document).ready(function(){
		 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#frm-role").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			username: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			password: {
				required: function(element) {
					return $("#method").val() === "add";
				},
				minlength: 6
			},
			user_category: {
				required: true
			},
			user_group: {
				required: true
			}
		},
		messages:{
			username: "Vui lòng nhập tên đăng nhập",
			email: {
				required: "Vui lòng nhập email",
				email: "Email không hợp lệ"
			},
			password: {
				required: "Vui lòng nhập mật khẩu",
				minlength: "Mật khẩu phải ít nhất 6 ký tự"
			},
			user_category: "Vui lòng chọn loại tài khoản",
			user_group: "Vui lòng chọn nhóm quyền"
		}
	});
		$("#table-user").on('click', '.btn-delete-user', function(e) {
			var id = $(this).attr("data-id");
			var user_status =  $(this).attr("data-status");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deleteuser",
				"data": {
					'id': id,
					'user_status': user_status
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Xoá thành công",
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ location.reload();     }, 2000);
					}else{
						Swal.fire({
						  icon: 'error',
						  title: "Lỗi",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			
			});
			return false;
		});
		
		
		$("#table-user").on('click', '.btn-duplicate-user', function(e) {
			var eid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/duplicateuser",
				"data": {
					'eid': eid
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Nhân bản thành công",
						  text: "Mã Khách hàng/NCC mới: " + data.new_user_code,
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ location.reload();     }, 2000);
					}else{
						Swal.fire({
						  icon: 'error',
						  title: "Lỗi",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			
			});
			return false;
		});
	
		$('#send_DB').click(function(e){
			if(!$("#frm-role").valid()) {
				return false;
			}

			var user_username = $("#username").val();
			var user_email = $("#email").val();
			var user_password = $("#password").val();
			var user_category = $("#user_category").val();
			var user_group = $("#user_group").val();
			var user_roles = [];
			$('input[name="user_roles[]"]:checked').each(function() {
				user_roles.push($(this).val());
			});

			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/adduser",
				data:{
					'user_username': user_username,
					'user_email': user_email,
					'user_password': user_password,
					'user_category': user_category,
					'user_group': user_group,
					'user_roles': user_roles
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Thêm thành công",
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ location.reload();     }, 2000);
					}else{
						Swal.fire({
						  icon: 'error',
						  title: "Lỗi",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			});
		});
	
		
	});
</script>
    
      <div class="conatiner-fluid content-inner mt-n5 py-0">
      <div>
         <div class="row">
            
            <div class="col-xl-12 col-lg-12">
               <div class="card">
                  <div class="card-header d-flex justify-content-between">
                     <div class="header-title">
                        <h4 class="card-title">Phân quyền:</h4>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="#" method="POST" id="frm-role">
                           <div class="row">
                              <!-- Bên trái: Thông tin cơ bản -->
                              <div class="col-md-4">
                                 <h5>Thông tin tài khoản</h5>
                                 <br>
                                 <div class="form-group mb-2">
                                    <label class="form-label" for="username">Tên đăng nhập: <?php echo isset($user) ? $user->user_username : ''; ?></label>
                                    
                                 </div>
                                 <div class="form-group mb-2">
                                    <label class="form-label" for="email">Email: <?php echo isset($user) ? $user->user_email : ''; ?></label>
                                    
                                 </div>
                                 
                              </div>

                              <!-- Bên phải: Nhóm quyền và quyền chi tiết -->
                              <div class="col-md-8">
                                 <h5>Phân quyền</h5>

                                 <!-- Nhóm quyền -->
                                 <div class="form-group mb-3">
                                    <label class="form-label">Nhóm quyền:</label>
                                    <select class='form-control selectpicker' id='user_group' name='user_group'>
                                       <option value="">Chọn nhóm quyền</option>
                                       <?php if(isset($roles) && is_array($roles)): ?>
                                       <?php foreach($roles as $group): ?>
                                       <option <?php if(isset($user) && $user->user_group == $group->id) echo "selected"; ?> value="<?php echo $group->id; ?>"><?php echo $group->group_name; ?></option>
                                       <?php endforeach; ?>
                                       <?php endif; ?>
                                    </select>
                                 </div>

                                 <!-- Quyền chi tiết -->
                                 <div class="form-group">
                                    <label class="form-label">Quyền chi tiết:</label>
                                    <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                       <?php if(isset($user_roles) && is_array($user_roles)): ?>
                                       <?php foreach($user_roles as $role): ?>
                                       <div class="form-check">
                                          <input class="form-check-input" type="checkbox" id="role_<?php echo $role->id; ?>" name="user_roles[]" value="<?php echo $role->id; ?>"
                                                 <?php if(isset($user_roles_selected) && in_array($role->id, $user_roles_selected)) echo "checked"; ?>>
                                          <label class="form-check-label" for="role_<?php echo $role->id; ?>">
                                             <?php echo $role->role_name; ?>
                                          </label>
                                       </div>
                                       <?php endforeach; ?>
                                       <?php else: ?>
                                       <p class="text-muted">Không có quyền nào được định nghĩa.</p>
                                       <?php endif; ?>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="text-end mt-3">
                              <button type="button" id='send_DB' class="btn btn-primary btn-sm">Lưu</button>
                           </div>
                        </form>
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
      <?php require "footer.php";?>