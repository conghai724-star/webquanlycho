<?php include_once "header.php"; ?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>

<script>
	$(document).ready(function(e){
		 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"employee_phone": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 8
			},
			"employee_national_id": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 5
			},
			"employee_name":{
				required: true
			},
			"employee_branch":{
				required: true
			},
			"employee_position":{
				required: true
			},
			"employee_address":{
				required: true
			},
			"employee_birthday":{
				required: true
			},
			"employee_gender":{
				required: true
			},
			"employee_email":{
				required: true
			},
			"employee_department":{
				required: true
			},
			"employee_issue_date":{
				required: true
			},
			"employee_issue_by":{
				required: true
			},
			"employee_issue_date":{
				required: true
			}
			
			
			
		},
		messages:{
				employee_national_id: {
					required: "Vui lòng số CMND",
					minlength: "số CMND phải vượt quá 5 ký tự",
					maxlength: "số CMND phải ngắn hơn 15 ký tự"
				},
				employee_phone: {
					required: "Vui lòng nhập số điện thoại",
					minlength: "Số điện thoại phải vượt quá 8 ký tự",
					maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
				},
				employee_name: "Vui lòng nhập tên nhân viên",
				employee_branch: "Vui lòng chọn đơn vị",
				employee_position: "Vui lòng chọn chức danh",
				employee_address: "Vui lòng nhập địa chỉ",
				employee_birthday: "Vui lòng nhập ngày sinh",
				employee_gender: "Vui lòng chọn giới tính",
				employee_email: "Vui lòng nhập email",
				employee_department: "Vui lòng chọn phòng ban",
				employee_issue_date: "Vui lòng nhập ngày cấp",
				employee_issue_by: "Vui lòng nhập nơi cấp"
				
			}
	});
		$('#updateEmployee').click(function(e){
			if($("#frm-action").valid())
			{
				var employeeid = $('#employeeid').val();
				var employee_code = $('#employee_code').val();
				var employee_name = $('#employee_name').val();
				var employee_branch = $('#employee_branch').val();
				var employee_position = $('#employee_position').val();
				var employee_address = $('#employee_address').val();
				var employee_birthday = $('#employee_birthday').val();
				var employee_gender = $('#employee_gender:checked').val();
				var employee_phone = $('#employee_phone').val();
				var employee_email = $('#employee_email').val();
				var employee_department = $('#employee_department').val();
				var employee_national_id = $('#employee_national_id').val();
				var employee_issue_date = $('#employee_issue_date').val();
				var employee_issue_by = $('#employee_issue_by').val();
				var employee_created_date = $('#employee_created_date').val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/updateEmployee",
				data:{
					'employeeid': employeeid,
					'employee_code': employee_code,
					'employee_name': employee_name,
					'employee_branch': employee_branch,
					'employee_position': employee_position,
					'employee_address': employee_address,
					'employee_birthday': employee_birthday,
					'employee_email': employee_email,
					'employee_gender': employee_gender,
					'employee_phone': employee_phone,
					'employee_department': employee_department,
					'employee_national_id': employee_national_id,
					'employee_issue_date': employee_issue_date,
					'employee_issue_by': employee_issue_by,
					'employee_created_date': employee_created_date
					
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Cập nhật thành công",
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ window.location.href="<?php echo XC_URL;?>/app/employees";     }, 2000);
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
<style>
	label.error{
		color:red;
	}
</style>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col-sm-12">
            <h3 class="page-title">Employees</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
               <li class="breadcrumb-item"><a href="customers.html">Employees</a></li>
               <li class="breadcrumb-item active">Add Employees</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Thông tin nhân viên</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" id="frm-action">
                  <div class="row">
                     <div class="col-md-6" data-select2-id="12">
                        <div class="row">
							<div class="col-md-6">
                              <div class="form-group">
                                 <label>Mã:</label>
                                 <input type="text" readonly="true" value="<?php echo $employee->employee_code;?>" class="form-control" name='employee_code' id='employee_code'>
                              </div>
                           </div>
                           
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Tên:</label>
                                 <input type="text" class="form-control" id='employee_name' value="<?php echo $employee->employee_name;?>" name="employee_name">
                              </div>
                           </div>
						  
                        </div>
						<div class="form-group">
                           <label>Đơn vị:</label>
						   <select class="select select2" id='employee_branch' name="employee_branch">
							<option></option>
							<?php foreach($branches as $branch){?>
								<option  value = '<?php echo $branch->id;?>' <?php echo ($employee->employee_branch == $branch->id) ? 'selected=selected' : '';?>><?php echo $branch->branch_name;?></option>
							<?php }?>
							
						   </select>
                        </div>
							<div class = 'row'>
								<div class="col-md-6">
									<div class="form-group">
									   <label>Chức danh:</label>
									   
									   <select class="select select2" id='employee_position' name="employee_position">
										<option>Chọn chức danh</option>
										<?php foreach($positions as $position){?>
											<option value = '<?php echo $position->id;?>' <?php echo ($employee->employee_position == $position->id) ? 'selected=selected' : '';?>><?php echo $position->position_title;?></option>
										<?php }?>
										
									   </select>
									   
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
									   <label>Phòng ban:</label>
									   
									   <select class="select select2" id='employee_department' name="employee_department">
										<option>Chọn phòng ban</option>
										<?php foreach($departments as $department){?>
											<option value = '<?php echo $department->id;?>'  <?php echo ($employee->employee_department == $department->id) ? 'selected=selected' : '';?>><?php echo $department->depart_name;?></option>
										<?php }?>
										
									   </select>
									   
									</div>
								</div>
							</div>
						
						<div class="form-group">
                           <label>Địa chỉ:</label>
                           <input type="text" class="form-control" id='employee_address' value = "<?php echo $employee->employee_address;?>" name="employee_address">
                        </div>
                       
                        
                     </div>
                     <div class="col-md-6">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Ngày sinh:</label>
                                 <div class="cal-icon">
									<input class="form-control datetimepicker" type="text" id='employee_birthday' value="<?php echo date("d-m-Y", strtotime($employee->employee_birthday)) ;?>" name="employee_birthday">
									</div>
                              </div>
							  
                           </div>
                           <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Giới tính:</label>
								 <div class = 'form-control' style = 'border:none;' >
                                 <input type="radio" name="contact" value="1" id='employee_gender' <?php echo ($employee->employee_gender == 1) ? 'checked' : '';?> name="employee_gender">
								 <label for="employee_gender">Nam</label>
								 &nbsp; &nbsp; &nbsp;
								 <input type="radio"  name="contact" value="2" id='employee_gender'  <?php echo ($employee->employee_gender == 2) ? 'checked' : '';?> name="employee_gender">
								 <label for="employee_gender">Nữ</label>
								
								 </div>
                              </div>
                           </div>
						   <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Phone: </label>
									<input class="form-control" type="text" id='employee_phone' value = "<?php echo $employee->employee_phone;?>" name="employee_phone">
							
                              </div>
							  
                           </div>
						   <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Email: </label>
									<input class="form-control" type="text" id='employee_email' value = "<?php echo $employee->employee_email;?>" name="employee_email">
							
                              </div>
							  
                           </div>
						   <div class="col-md-8">
                              <div class="form-group" >
                                 <label>CMND: </label>
									<input class="form-control" type="text" id='employee_national_id' value ="<?php echo $employee->employee_national_id;?>" name="employee_national_id">
							
                              </div>
							  
                           </div>
						   
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Ngày cấp:</label>
                                 <div class="cal-icon">
									<input class="form-control datetimepicker" type="text" id='employee_issue_date' value="<?php echo date("d-m-Y", strtotime($employee->employee_issue_date));?>" name="employee_issue_date">
									</div>
                              </div>
							  
                           </div>
						   <div class="col-md-12">
                              <div class="form-group" >
                                 <label>Nơi cấp: </label>
									<input class="form-control" type="text" id='employee_issue_by' value = "<?php echo $employee->employee_issue_by;?>" name="employee_issue_by">
							
                              </div>
							  
                           </div>
						   
						   
                        </div>
                        
                        
                       
                     </div>
                  </div>
                  <div class="text-end">
				  <input type = "hidden" value = "<?php echo $employee->employee_created_date;?>" id = 'employee_created_date' />
				  <input type = "hidden" value = "<?php echo $employee->employee_branch;?>" id = 'employee_branch' />
					<input type = "hidden" value = "<?php echo $employee->employeeid;?>" id = 'employeeid' />
                     <button type="button" class="btn btn-primary" id = 'updateEmployee'>Cập Nhật</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   
   
   
   
</div>
      
<?php include_once "footer.php";?>