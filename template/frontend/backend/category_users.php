<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
//validate pasword

    $(document).ready(function () {
		
	$("#formaddaccounts").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"user_phone": {
				required: true,
				maxlength: 15,
				minlength: 10
			},
			"user_fullname":{
				required: true
			},
			"user_email":{
				required: true
			},
			"user_address":{
				required: true
			},
			"user_username":{
				required: true
			},
			"user_password":{
				required: true
			},
			"user_group":{
				required: true
			}
			
			
		},
		messages:{
				user_phone: {
					required: "Vui lòng nhập số điện thoại",
					minlength: "Số điện thoại phải vượt quá 10 ký tự",
					maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
				},
				user_fullname: "Vui lòng nhập họ và tên",
				user_email: "Vui lòng nhập email",
				user_address: "Vui lòng nhập địa chỉ",
				user_username: "Vui lòng nhập tên đăng nhập̉",
				user_password: "Vui lòng nhập mật khẩu", 
				user_group: "Vui lòng chọn quyền"
			}
	});
	//------///
       $("#user_password_confirm").keyup(function(){
			var password = $("#user_password").val();
			var confirmPassword = $("#user_password_confirm").val();
       
		if(password.length < 8 ){
			$("#user_password").css('border','1px solid red');
			$("#result_validate").html('Mật khẩu phải lớn hơn 8 ký tự');
			
		}else {
			$("#user_password").css('border','1px solid #dee2e6');
			$("#result_validate").html('');
		}
		if (password != confirmPassword)
            $("#user_password_confirm").css('border','1px solid red');
        else
            $("#user_password_confirm").css('border','1px solid #dee2e6');
	   
	   });
	   $("#btaddaccounts").click(function(){
		if($("#formaddaccounts").valid())
		{
		   var user_fullname = $.trim($('#user_fullname').val());
		   var user_email = $.trim($('#user_email').val());
		   var user_phone = $.trim($('#user_phone').val());
		   var user_address = $.trim($('#user_address').val());
		   var user_username = $.trim($('#user_username').val());
		   var user_password = $.trim($('#user_password').val());
		   var user_password_confirm = $.trim($('#user_password_confirm').val());
		   if(!user_avatar){
			   var user_avatar = '';
		   }else{
			   var user_avatar = $("#user_avatar")[0].files[0].name;
		   }
		   
		   var user_group = $('#user_group').val();
		   var method = $("#fomr-method").val();
		   $.ajax({
			   type:"POST",
			   url: "<?php echo XC_URL;?>/api/addusers",
			   data: {
				   'user_fullname': user_fullname,
				   'user_email':user_email,
				   'user_phone':user_phone,
				   'user_address': user_address,
				   'user_username':user_username,
				   'user_password': user_password,
				   'user_password_confirm': user_password_confirm,
				   'user_avatar': user_avatar,
				   'user_group': user_group
			   },
			   dataType: "json",
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
		   return false;
		}
	   });
	   
	  
	   $('.select').select2({
            minimumResultsForSearch: -1,
            width: '100%'
        });
		$("#table-user").on('click', '.btn-detail', function(e){
			var btn = $(this);
			
			var user_fullname = btn.attr('data-fullname');
			var user_username = btn.attr('data-username');
			var user_email = btn.attr('data-email');
			var user_address = btn.attr('data-address');
			var user_phone = btn.attr('data-phone');
			var user_group = btn.attr('data-group');
			var user_register_time = btn.attr('data-register-time');
			
			console.log(user_register_time);
			$("#account-detail").modal('show');
			$("#de_fullname").html(user_fullname);
			$("#de_username").html(user_username);
			$("#de_email").html(user_email);
			$("#de_phone").html(user_phone);
			$("#de_address").html(user_address);
			if(user_group == 1){
				$("#de_group").html("Quản trị viên");
			}else{
				$("#de_group").html("Quản lý");
			}
			$("#de_register-time").html(user_register_time);
		});
		//delete and active
		$("#table-user").on('click', '.btn-action', function(e) {
			var uid = $(this).attr("data-id");
			var method = $(this).attr('data-method');
			var user_status = $(this).attr('data-status');
			console.log(method);	
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/action_category_users",
				"data": {
					'method': method,
					'id': uid,
					'user_status': user_status
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: data.message,
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
		
			
    });
</script>
<style>
	label.error{
		color:red;
	}
	.table-detail th{
		border-bottom-width:0px;
	}
</style>
<div class="modal fade bd-example-modal-xl"  id="modal_accounts" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-xl">
			<div class="modal-content">
			  <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title text-center" id="form-title">Thông tin người dùng</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" method="POST" id = 'formaddaccounts'>
                  <div class="row">
                     <div class="col-md-6" data-select2-id="12">
                        
							<div class="col-md-12">
                              <div class="form-group">
							   <label>Họ và tên:</label>
							   <input type="text" class="form-control" id='user_fullname' value='' name="user_fullname">
							</div>
                           </div>
                           
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label>Email:</label>
                                 <input type="text" class="form-control" id='user_email'  value='' name="user_email">
								 <p id="error_name" class="hidden text-red error"></p>
								 <p class="text-red hidden error_submit error italic fontSize12"></p>
                              </div>
                           </div>
						    <div class="col-md-12">
                              <div class="form-group">
                                 <label>Số điện thoại:</label>
                                 <input type="text" class="form-control" id='user_phone'  value='' name="user_phone">
                              </div>
                           </div>
						   <div class="col-md-12">
							<div class="form-group">
							   <label>Địa chỉ:</label>
							   <input type="text" class="form-control" id='user_address'  value='' name="user_address">
							</div>
							</div>
                       
                        
                     </div>
                     <div class="col-md-6">
					 <div class="col-md-12">
                        <div class="form-group">
                           <label>Tên đăng nhập:</label>
                           <input type="text" class="form-control" id='user_username'  value='' name="user_username">
                        </div>
						</div>
						<div class="col-md-12">
						<div class="form-group">
                           <label>Mật khẩu:</label>
                           <input type="password" class="form-control" id='user_password'  value='' name="user_password">
						   <p id = "result_validate"></p>
                        </div>
						</div>
						<div class="col-md-12">
						<div class="form-group">
                           <label>Xác nhận mật khẩu:</label>
                           <input type="password" class="form-control" id='user_password_confirm'  value = ''>
						   <p id = "result_validate"></p>
                        </div>
						</div>
						<div class="col-md-12">
						<div class = 'row'>
							<div class = 'col-6'>
								<div class="form-group">
								   <label>Ảnh đại diện:</label>
								   <input type="file" class="form-control" id='user_avatar' value = '' name="user_avatar">
								</div>
							</div>
							<div class = 'col-6'>
								<div class="form-group">
									   <label>Quyền:</label>
									   <select class="select select2" id='user_group' name="user_group">
										<option selected="selected" disabled>Chọn quyền</option>
										<option value = '1'>Quản trị viên</option>
										<option value = '2'>Quản lý</option>
									   </select>
									   
									</div>
							</div>
						</div>
						</div>
                        </div>
                     </div>
                  <div class="text-end">
						<input type="hidden" id="fomr-method" />
						<input type="hidden" id="id" />
                     <button type="button" class="btn btn-primary btn-submit" id='btaddaccounts' >Thêm</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   
			</div>
		  </div>
		</div>
<!-- Modal detail -->
<div id="account-detail" class="modal custom-modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title text-center" id="form_add_title">Chi tiết tài khoản</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
		 <table class="table table-center table-hover mb-0">
                     <thead class="table-detail">
                        <tr >
                           <th style="width: 10%">Họ và tên: </th>
						   <th class="text-secondary" id="de_fullname"> </th>
						 </tr>
						 <tr>
							<th style="width: 20%">Tên đăng nhập: </th>
							<th class="text-secondary" id="de_username"> </th>
						 </tr>
						  <tr>
							<th style="width: 20%">Email: </th>
							<th class="text-secondary" id="de_email"> </th>
						 </tr>
						 <tr>
							<th style="width: 20%">Số điện thoại: </th>
							<th class="text-secondary" id="de_phone"> </th>
						 </tr>
						 <tr> 
							<th style="width: 20%">Địa chỉ: </th>
							<th class="text-secondary" id="de_address"> </th>
						 </tr>
						 <tr>
							<th style="width: 20%">Quyền: </th>
							<th class="text-secondary" id="de_group"> </th>
						 </tr>
						 <tr>
							<th style="width: 20%">Ngày đăng ký: </th>
							<th class="text-secondary" id="de_register-time"> </th>
						 </tr>
                           
                          
                     </thead>
                     
                        
                  </table>
            
         </div>
      </div>
   </div>
</div>
<!-- end -->
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col-sm-6">
            <h3 class="page-title">Danh mục</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
               <li class="breadcrumb-item active">Danh mục</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row"  >
      <div class="col-xl-2 col-md-4">
         <?php include_once "category-sidebar.php";?>
      </div>
      <div class="col-xl-10 col-md-8">
         <div class="card card-table">
            <div class="card-header">
				<div class="row">
					<div class="col">
						<h5 class="card-title"><?php echo $pagetitle;?></h5>
					</div>
					<div class="col-auto">
						<a href="javascript:void(0);" class="btn btn-primary btn-submit" id="add_accounts" data-bs-toggle="modal" data-bs-target="#modal_accounts"><i class="fas fa-plus"></i> Thêm người dùng</a>
					</div>
				</div>
			</div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-center table-hover mb-0 datatable" id="table-user">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 20%">Tài khoản</th>
                           <th style="width: 10%">Email</th>
                           <th style="width: 20%">Nhóm</th>
                           <th style="width: 20%">Ngày tạo</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							$i = 1;
							foreach($users as $user)
                           {
                           ?>
                        <tr>
                           <td>
                              <?php echo $i;?>
                           </td>
                           <td>
                              <?php echo $user->user_username;?>
                           </td>
                           <td>
						   <?php echo $user->user_email;?>
						   </td>
                           <td  class="text-right "><span class="text-<?php echo $user->group_class;?>"><?php echo $user->group_name;?></span></td>
                           <td><?php echo date("H:i d/m/Y",strtotime($user->user_register_time));?></td>
                           <td><span class="badge badge-pill bg-<?php echo $user->status_class;?>-light"><?php echo $user->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
								   <button type="button" id="btn-detail" class="btn btn-sm btn-success btn-detail" data-fullname="<?php echo $user->user_fullname;?>" data-username="<?php echo $user->user_username;?>" data-email="<?php echo $user->user_email?>" data-group="<?php echo $user_group;?>" data-phone="<?php echo $user->user_phone;?>" data-address="<?php echo $user->user_address?>" data-register-time="<?php echo $user->user_register_time;?>">Xem</button>
								   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									    <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $user->uid;?>" data-status="<?php echo $user->user_status;?>"><?php echo ($user->user_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
										<a class="dropdown-item btn-action" href="#" data-method="role" data-id='<?php echo $user->uid;?>' data-status="<?php echo $user->user_status;?>">Phân lại quyền</a>
										<a class="dropdown-item btn-action" href="#" data-method="delete" data-id = '<?php echo $user->uid;?>' data-status="<?php echo $user->user_status;?>">Xóa</a>
								   </div>
								</div>
                           </td>
                        </tr>
                        <?php
                           }
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
<?php include "footer.php";?>