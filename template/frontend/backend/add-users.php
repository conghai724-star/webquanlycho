<?php include "header.php"; ?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
	$(document).ready(function(e){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký số!");
	$("#form-user").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			
			"user_group":{
				required: true
			},
			
			"user_name":{
				required: true
			},
			"user_address":{
				required: true
			},
			"user_email":{
				required: true
			},
			"user_phone":{
				required: true
			},
			"user_password":{
				required: true
			}
			
			
		},
		messages:{
				user_group: "Vui lòng chọn quyền người dùng",
				user_name: "Vui lòng nhập tên người dùng",
				user_address: "Vui lòng nhập địa chỉ",
				user_email: "Vui lòng email",
				user_phone: "Vui lòng nhập số điện thoại",
				user_password: "Vui lòng nhập mật khẩu"
			}
	});
	$("#form-employee").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"employee_name":{
				required: true
			},
			"employee_branch":{
				required: true
			},
			"employee_address":{
				required: true
			},
			"employee_phone":{
				required: true
			},
			"employee_email":{
				required: true
			},
			"employee_national_id":{
				required: true
			},
			"employee_issue_date":{
				required: true
			},
			"employee_issue_by":{
				required: true
			}
			
		},
		messages:{
				employee_name : "Vui lòng nhập tên nhân viên",
				employee_branch : "Vui lòng chọn đơn vị",
				employee_position: "Vui lòng chọn chức danh",
				employee_department: "Vui lòng chọn phòng ban",
				employee_address: "Vui lòng nhập địa chỉ",
				employee_phone: "Vui lòng nhập số điện thoại",
				employee_email: "Vui lòng nhập email",
				employee_national_id: "Vui lòng nhập CMND",
				employee_issue_date: "Vui lòng nhập ngày cấp",
				employee_issue_by: "Vui lòng nhập nơi cấp"
			}
	});
   	
		$("#addUsers").click(function(e){
		if($("#form-user").valid())
		{
			var user_group = $('#user_group').val();
			var user_fullname = $('#user_fullname').val();
			var user_address = $('#user_address').val();
			var user_email = $('#user_email').val();
			var user_phone = $('#user_phone').val();
			var user_password = $('#user_password')
			$.ajax({ 
				type: "POST",
				url: "<?php echo XC_URL?>/api/addusers",
				data:{
					'user_fullname': user_fullname,
					'user_group': user_group,
					'user_address': user_address,
					'user_email': user_email,
					'user_phone': user_phone,
				},
				dataType: 'json',
				success:function(data){
					console.log(data);
					if(data.status == 200){
						console.log('aa');
						Swal.fire('Test Swal');
						Swal.fire({
						  icon: 'success',
						  title: data.message,
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ window.location.href=data.return_url;     }, 2000);
					}else{
						Swal.fire({
						  icon: 'error',
						  title: "Rất tiếc đã xảy ra lỗi. Vui lòng thử lại sau!",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			});
			return false;
		}
			
		});
	});
	
	
</script>
<style>
label.error{
	color:red;
}
</style>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col-sm-12">
            <h3 class="page-title">Thêm người dùng/user</h3>
            <!-- <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL . "/backend/";?>">Cloud ERP</a></li>
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>/app/users">Khách hàng/NCC</a></li>
               <li class="breadcrumb-item active">Thêm Khách hàng/NCC</li>
            </ul> -->
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Thông tin người dùng</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" id="form-user">
                  <div class="row">
                     <div class="col-md-12" data-select2-id="12">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Họ và tên:</label>
                                 <input type="text" class="form-control" id='user_fullname' name="user_name" >
                              </div>
                           </div>
						    <div class="col-md-4">
                              <div class="form-group">
                                 <label>Email:</label>
                                 <input type="text" class="form-control" id='user_email' name="user_email" >
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group">
                                 <label>Số điện thoại:</label>
                                 <input type="text" class="form-control" id='user_phone' name="user_phone" >
                              </div>
                           </div>
                           </div>
                        </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Địa chỉ:</label>
                                 <input type="text" class="form-control" id='user_address' name="user_address" >
                              </div>
                           </div>
						    <div class="col-md-4">
                              <div class="form-group">
                                 <label>Mật khẩu</label>
                                 <input type="password" class="form-control" id='user_password' name="user_password" >
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group">
                                 <label>Quyền:</label>
                                 <select class="select select2 form-control" id='user_group' name="user_group" >
									<option >Chọn quyền</option>
									<option value="1">Admin</option>
									<option value="2">Biên tập viên</option>
								 
							   </select>
                              </div>
                        </div>
                     </div>
                  </div>
                  <div class="text-end">
                     <button type="button" class="btn btn-primary" id = 'addUsers'>Thêm</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   
   <div class="modal fade bd-example-modal-xl"  id="edit_tax" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-xl">
			<div class="modal-content">
			  <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Thông tin nhân viên</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" id="form-employee">
                  <div class="row">
                     <div class="col-md-6" data-select2-id="12">
                        <div class="row">
							<div class="col-md-6">
                              <div class="form-group">
                                 <label>Mã:</label>
                                 <input type="text" readonly="true" value="<?php echo $employee_code;?>" class="form-control" name = 'user_code' id='employee_code' name="employee_code">
                              </div>
                           </div>
                           
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Tên:</label>
                                 <input type="text" class="form-control" id='employee_name' name="employee_name">
                              </div>
                           </div>
						  
                        </div>
						<div class="form-group">
                           <label>Đơn vị:</label>
						   <select class="select select2" id='employee_branch' name="employee_branch">
							<option></option>
							<?php foreach($branches as $branch){?>
								<option value = '<?php echo $branch->id;?>'><?php echo $branch->branch_name;?></option>
							<?php }?>
							
						   </select>
                        </div>
							<div class = 'row'>
								<div class="col-md-6">
									<div class="form-group">
									   <label>Chức danh:</label>
									   
									   <select class="select select2" id='employee_position' name="employee_position">
										<option disabled >Chọn chức danh</option>
										<?php foreach($positions as $position){?>
											<option value = '<?php echo $position->id;?>'><?php echo $position->position_title;?></option>
										<?php }?>
										
									   </select>
									   
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									   <label>Phòng ban:</label>
									   
									   <select class="select select2" id='employee_department' name="employee_department">
										<option disabled>Chọn phòng ban</option>
										<?php foreach($departments as $department){?>
											<option value = '<?php echo $department->id;?>'><?php echo $department->depart_name;?></option>
										<?php }?>
										
									   </select>
									   
									</div>
								</div>
							</div>
						
						<div class="form-group">
                           <label>Địa chỉ:</label>
                           <input type="text" class="form-control" id='employee_address' name="employee_address">
                        </div>
                       
                        
                     </div>
                     <div class="col-md-6">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Ngày sinh:</label>
                                 <div class="cal-icon">
									<input class="form-control datetimepicker" type="text" id='employee_birthday' name="employee_birthday">
									</div>
                              </div>
							  
                           </div>
                           <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Giới tính:</label>
								 <div class = 'form-control' style='border:none;' id="checkGender">
                                 <input type="radio" name="employee_gender" value="1" id='employee_gender'>
								 <span for="employee_gender">Nam</span>
								 &nbsp; &nbsp; &nbsp;
								 <input type="radio"  name="employee_gender" value="2" id='employee_gender'>
								 <span for="employee_gender">Nữ</span>
								
								 </div>
                              </div>
                           </div>
						   <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Phone: </label>
									<input class="form-control" type="text" id='employee_phone' name="employee_phone">
							
                              </div>
							  
                           </div>
						   <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Email: </label>
									<input class="form-control" type="text" id='employee_email' name="employee_email">
							
                              </div>
							  
                           </div>
						   <div class="col-md-8">
                              <div class="form-group" >
                                 <label>CMND: </label>
									<input class="form-control" type="text" id='employee_national_id' name="employee_national_id">
							
                              </div>
							  
                           </div>
						   
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Ngày cấp:</label>
                                 <div class="cal-icon">
									<input class="form-control datetimepicker" type="text" id='employee_issue_date' name="employee_issue_date" >
									</div>
                              </div>
							  
                           </div>
						   <div class="col-md-12">
                              <div class="form-group" >
                                 <label>Nơi cấp: </label>
									<input class="form-control" type="text" id='employee_issue_by' name="employee_issue_by">
							
                              </div>
							  
                           </div>
						   
						   
                        </div>
                        
                        
                       
                     </div>
                  </div>
                  <div class="text-end">
                     <button type="button" class="btn btn-primary" id='addUsersa'>Thêm</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
			</div>
		  </div>
		</div>
   
   
</div>
      
<?php include_once "footer.php";?>