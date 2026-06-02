
<?php require "header.php"; ?>
<script>
	jQuery(document).ready(function(){
			jQuery("#table-user").on('click', '.btn-delete-user', function(e) {
			var id = jQuery(this).attr("data-id");
			var user_status =  jQuery(this).attr("data-status");
			jQuery.ajax({
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
jQuery.validator.addMethod('strongPassword', function(value, element) {
			return this.optional(element) || /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=[\]{};':"\\|,.<>\/?]).{8,}$/.test(value);
		}, 'Mật khẩu phải tối thiểu 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.');

		jQuery('#frm-action').validate({
				rules: {
					user_username: {
						required: true,
						minlength: 3
					},
					user_email: {
						required: true,
						email: true
					},
					user_password: {
						required: function(element) {
							return jQuery('#method').val() === 'add';
						},
						minlength: 8,
					strongPassword: true
					},
					user_group: {
						required: true
					}
				},
				messages: {
					user_username: {
						required: 'Vui lòng nhập họ và tên',
						minlength: 'Tên đăng nhập phải có ít nhất 3 ký tự'
					},
					user_email: {
						required: 'Vui lòng nhập email',
						email: 'Email không đúng định dạng'
					},
					user_password: {
						required: 'Vui lòng nhập mật khẩu',
						minlength: 'Mật khẩu phải có ít nhất 8 ký tự',
					strongPassword: 'Mật khẩu phải có chữ hoa, chữ thường, số và ký tự đặc biệt'
					},
					user_group: {
						required: 'Vui lòng chọn quyền tài khoản'
					}
				},
				errorClass: 'is-invalid',
				success: function(label) {
					label.remove();
				}
			});

			jQuery('#send_DB').click(function(e){
				e.preventDefault();
				if(jQuery('#frm-action').valid()){
					var full_name = jQuery('#full_name').val();
					var user_email = jQuery('#email').val();
					var user_password = jQuery('#password').val();
					var user_group = jQuery('#user_group').val();
					var method = jQuery('#method').val();
					jQuery.ajax({
						type: "POST",
						url: "<?php echo XC_URL;?>/api/userAction",
						data:{
							'full_name': full_name,
							'user_email': user_email,
							'user_password': user_password,
							'user_group': user_group,
							'method': method
						},
						dataType: 'json',
						success: function(data){
							if(data.status == 200){
								Swal.fire({
								  icon: 'success',
								  title: data.message,
								  footer: '<a href=""></a>',
								  timer: 1700
								})
								setTimeout(function(){ location.href=data.url;     }, 2000);
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
				}
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
                        <h4 class="card-title"><?php echo $pagetitle;?></h4>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="#" method="POST" id="frm-action">
                           <div class="row">
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="full_name">Họ và tên:</label>
                                 <input type="text" value="<?php echo isset($user) ? $user->full_name : ''; ?>" class="form-control" id="full_name" name="full_name" placeholder="Họ và tên">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="email">Email:</label>
                                 <input type="text" value="<?php echo isset($user) ? $user->user_email : ''; ?>" class="form-control" id="email" name="user_email" placeholder="Email">
                              </div>
				  <?php if(isset($method) && $method == "add"){ ?>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="password">Mật khẩu:</label>
                                 <input type="password" value="" class="form-control" id="password" name="user_password" placeholder="Mật khẩu">
								<?php } ?>
							  </div>
                              
							  <!-- <div class="form-group col-md-6">
                                 <label class="form-label" for="user_category">Quyền tài khoản:</label>
                                 <select class='form-control selectpicker' id='user_group' name='user_group' data-live-search="true">
                                   <?php foreach($roles as $role){ ?>
								   <option <?php if(isset($user) && $user->user_group == $role->id) echo "selected"; ?> value="<?php echo $role->id;?>"><?php echo $role->group_name;?></option>
                                   <?php } ?>
                                 </select>
                              </div> -->
								  <div class="text-end">
									  <a href="<?php echo XC_URL; ?>/admin/users"><button type="button" class="btn btn-warning btn-sm">Quay lại</button></a>
									<input type="hidden" id="method" value="<?php echo isset($method) ? $method : ''; ?>">	
									  <button type="button" id='send_DB' class="btn btn-primary btn-sm">Lưu</button>
								  </div>
							</div>
							</div>
						</form>
					 </div>
				  </div>
			   </div>
			</div>
		 </div>
     
      <?php require "footer.php";?>