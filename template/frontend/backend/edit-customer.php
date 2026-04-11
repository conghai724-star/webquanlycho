<?php include_once "header.php"; ?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
	$(document).ready(function(e){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký số!");
	$("#form-customer").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"customer_tax_code":{
				required: true,
				alpha: true
			},
			"customer_type":{
				required: true
			},
			"customer_title":{
				required: true
			},
			"customer_group":{
				required: true
			},
			
			"customer_name":{
				required: true
			},
			"customer_address":{
				required: true
			},
			"customer_email":{
				required: true
			},
			"customer_phone":{
				required: true
			},
			"customer_staff":{
				required: true
			}
			
		},
		messages:{
				customer_tax_code: "Vui lòng nhập mã số thuế",
				customer_type: "Vui lòng chọn loại",
				customer_group: "Vui lòng chọn nhóm khách hàng/NCC",
				customer_name: "Vui lòng nhập tên khách hàng/NCC",
				customer_address: "Vui lòng nhập địa chỉ",
				customer_title: "Vui lòng nhập tên khách hàng/NCC",
				customer_email: "Vui lòng email",
				customer_phone: "Vui lòng nhập số điện thoại",
				customer_staff: "Vui lòng chọn nhân viên bán hàng"
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
   	
		$("#editCustomer").click(function(e){
		if($("#form-customer").valid())
		{
			var customer_code = $('#customer_code').val();
			var customer_tax_code = $('#customer_tax_code').val();
			var customer_type = $('#customer_type').val();
			var customer_title = $('#customer_title').val();
			var customer_name = $('#customer_name').val();
			var customer_group = $('#customer_group').val();
			var customer_address = $('#customer_address').val();
			var customer_note = $('#customer_note').val();
			var customer_email = $('#customer_email').val();
			var customer_phone = $('#customer_phone').val();
			var customer_is_vendor = $('#customer_is_vendor:checked').val();
			if(customer_is_vendor){
				customer_is_vendor = customer_is_vendor;
			}else{
				customer_is_vendor = 0;
			}
			var customer_staff = $('#customer_staff').val();
			var customer_id = $('#customer_id').val();
			$.ajax({ 
				type: "POST",
				url: "<?php echo XC_URL?>/api/editCustomer",
				data:{
					'id': customer_id,
					'customer_code': customer_code,
					'customer_tax_code': customer_tax_code,
					'customer_title': customer_title,
					'customer_name': customer_name,
					'customer_note': customer_note,
					'customer_group':customer_group,
					'customer_type': customer_type,
					'customer_address': customer_address,
					'customer_email': customer_email,
					'customer_phone': customer_phone,
					'customer_is_vendor': customer_is_vendor,
					'customer_staff': customer_staff
				},
				dataType: 'json',
				success:function(data){
					if(data.status == 200){
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
		$('#addEmployee').click(function(e){
		if($("#form-employee").valid())
		{
				var employee_code = $('#employee_code').val();
				var employee_name = $('#employee_name').val();
				var employee_branch = $('#employee_branch').val();
				var employee_position = $('#employee_position').val();
				var employee_address = $('#employee_address').val();
				var employee_birthday = $('#employee_birthday').val();
				var employee_gender = $('#employee_gender:checked').val();
				var employee_phone = $('#employee_phone').val();
				var employee_email = $('#employee_email').val();
				console.log(employee_department)
				if(!employee_gender){
					$("#checkGender").css("border","1px solid red");
					}else{
						$("#checkGender").css("border','none");
					}
				var employee_department = $('#employee_department').val();
				var employee_national_id = $('#employee_national_id').val();
				var employee_issue_date = $('#employee_issue_date').val();
				var employee_issue_by = $('#employee_issue_by').val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addEmployee",
				data:{
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
					'employee_issue_by': employee_issue_by
					
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
			return false;
		}
		});
	});
	
</script>
<style>
label.error{
	color: red;
}
</style>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col-sm-12">
            <h3 class="page-title">Sửa Khách hàng/NCC</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>/app/customers">Khách hàng/NCC</a></li>
               <li class="breadcrumb-item active">Sửa Khách hàng/NCC</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Thông tin cơ bản</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" id='form-customer'>
                  <div class="row">
                     <div class="col-md-6" data-select2-id="12">
                        <div class="row">
							<div class="col-md-4">
                              <div class="form-group">
                                 <label>Mã Khách hàng:</label>
                                 <input type="text" readonly="true" value="<?php echo $customer->customer_code;?>" class="form-control" name = 'customer_code' id = 'customer_code'>
                              </div>
                           </div>
                           
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Mã số thuế:</label>
                                 <input type="text" class="form-control" id='customer_tax_code' name="customer_tax_code" value="<?php echo $customer->customer_tax_code;?>">
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group">
                                 <label>Loại:</label>
                                 <select class="select select2 form-control" id = 'customer_type'>
									<option >Chọn loại</option>
									<option value="1" <?php echo ($customer->customer_type==1) ? 'selected="selected"' : '';?>>Cá nhân</option>
									<option value="2" <?php echo ($customer->customer_type==2) ? 'selected="selected"' : '';?>>Tổ chức</option>
								 
							   </select>
                              </div>
                           </div>
                        </div>
						<div class="form-group">
                           <label>Tên Khách hàng/NCC:</label>
                           <input type="text" class="form-control" id='customer_title' name="customer_title" value="<?php echo $customer->customer_title;?>">
                        </div>
						<div class="form-group">
                           <label>Tên công ty:</label>
                           <input type="text" class="form-control" id='customer_name' name="customer_name" value="<?php echo $customer->customer_name;?>">
                        </div>
                       
                        <div class="form-group" data-select2-id="11">
                           <label>Địa chỉ:</label>
                           <input type="text" class="form-control" id='customer_address' name="customer_address"value="<?php echo $customer->customer_address?>">
                        </div>
                        <div class="form-group">
                           <label>Ghi chú:</label>
                           <textarea rows="3" cols="5" class="form-control" id='customer_note' name="customer_note"><?php echo $customer->customer_note;?></textarea>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Email:</label>
                                 <input type="text" class="form-control" id='customer_email' name="customer_email" value="<?php echo $customer->customer_email;?>">
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Điện thoại:</label>
                                 <input type="text" class="form-control" id='customer_phone' name="customer_phone" value="<?php echo $customer->customer_phone;?>">
                              </div>
                           </div>
                        </div>
                       <div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label>Nhóm khách hàng:</label>
                                 <select class="select select2" id='customer_group' name="customer_group">
								 <option>Chọn nhóm</option>
								 <?php foreach($customer_groups as $customer_group){?>
									<option value="<?php echo $customer_group->id;?>" <?php echo ($customer_group->id==$customer->customer_group) ? 'selected="selected"' : '';?>><?php echo $customer_group->group_name;?></option>
								 <?}?>
							   </select>
                              </div>
                           </div>
                        </div>
						<div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
                                 <label>Nhân viên bán hàng:</label>
								 <div class='' style = "display: flex;">
                                 <select class="select select2" id = 'customer_staff' name="customer_staff">
								 <option>Chọn nhân viên</option>
								 <?php foreach($customer_staff as $row_staff){ ?>
								 <option value = "<?php echo $row_staff -> id;?>" <?php echo ($row_staff->id==$customer->customer_staff) ? 'selected="selected"' : '';?>> <?php echo $row_staff->employee_name;?> 
								 </option>
								  <?php }?>
								 
							   </select>
							    <a href="#" data-bs-toggle="modal" data-bs-target="#edit_tax" class="btn btn-sm btn-white text-success" ><i class="fa fa-plus" style = 'width: 30px; margin-top: 12px;'></i></a>
							   </div>
									
							   
                              </div>
                           </div>
                        </div> 
						 <div class="row">
                           <div class="col-md-12">
                              <div class="form-group">
								<input type="checkbox" id="customer_is_vendor" value='1'/>
								&nbsp;
								  <label>Khách hàng là người bán hàng</label>
                              </div>
                           </div>
                        </div>
                        
                       
                     </div>
                  </div>
                  <div class="text-end">
				  <input type="hidden" value="<?php echo $customer->id?>" id="customer_id"/>
                     <button type="button" class="btn btn-primary" id='editCustomer'>Sửa</button>
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
                                 <input type="text" readonly="true" value="<?php echo $employee_code;?>" class="form-control" name = 'customer_code' id='employee_code' name="employee_code">
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
                     <button type="button" class="btn btn-primary" id='addEmployee'>Thêm</button>
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