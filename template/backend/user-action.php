
<?php require "header.php"; ?>
<script>
	$(document).ready(function(){
		 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"user_phone": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 8
			},
			"user_fullname":{
				required: true
			},
			"user_address":{
				required: true
			},
			"user_email":{
				required: true
			},
			"user_dept":{
				required: true
			},
            "user_username":{
				required: true
			},
			"user_group":{
				required: true
			}
		},
		messages:{
				user_phone: {
					required: "Vui lòng nhập số điện thoại",
					minlength: "Số điện thoại phải vượt quá 8 ký tự",
					maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
				},
				user_fulname: "Vui lòng nhập họ tên",
                user_username: "Vui lòng nhập tên tài khoản",
				user_address: "Vui lòng nhập địa chỉ",
				user_email: "Vui lòng nhập email",
				user_dept: "Vui lòng chọn phòng ban",
                user_group:"Vui lòng chọn quyền"
				
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
		
				var user_username = $("#username").val();
                var user_email = $("#email").val();
                var user_password = $("#password").val();
                var user_category = $("#user_category").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/adduser",
				data:{
					'user_username': user_username,
					'user_email': user_email,
					'user_password': user_password,
					'user_category': user_category
					
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
                        <h4 class="card-title"><?php echo $pagetitle;?></h4>
                     </div>
                  </div>
                  <div class="card-body">
                     <div class="new-user-info">
                        <form action="#" method="POST">
                           <div class="row">
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="username">Tên đăng nhập:</label>
                                 <input type="text" value="<?php echo isset($user) ? $user->user_username : ''; ?>" class="form-control" id="username" placeholder="Tên đăng nhập">
                              </div>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="email">Email:</label>
                                 <input type="text" value="<?php echo isset($user) ? $user->user_email : ''; ?>" class="form-control" id="email" placeholder="Email">
                              </div>
							  <?php if(isset($method) && $method == "add"){ ?>
                              <div class="form-group col-md-6">
                                 <label class="form-label" for="password">Mật khẩu:</label>
                                 <input type="password" value="" class="form-control" id="password" placeholder="Mật khẩu">
                              
								</div>
								<?php } ?>
                              
							  <div class="form-group col-md-3">
                                 <label class="form-label" for="user_category">Quyền tài khoản:</label>
                                 <select class='form-control selectpicker' id='user_category'>
                                   <?php foreach($roles as $role){ ?>
								   <option <?php if(isset($user) && $user->user_group == $role->id) echo "selected"; ?> value="<?php echo $role->id;?>"><?php echo $role->group_name;?></option>
                                   <?php } ?>
                                 </select>
                              </div>
								  <div class="text-end">
									  <a href="<?php echo XC_URL; ?>/admin/users"><button type="button" class="btn btn-warning btn-sm">Quay lại</button></a>

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