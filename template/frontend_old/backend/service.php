<?php include_once "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
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
		$("#table-services").on('click', '.btn-delete-service', function(e) {
			var sid = $(this).attr("data-id");
			var service_status =  $(this).attr("data-status");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deleteservices",
				"data": {
					'id': sid,
					'service_status': service_status
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
		$("#table-employee").on('click', '.btn-calendar-employee', function(e) {
			$('#employee_id').val($(this).data('id'));

		});
		$('#updateCalendarEmployee').click(function(e) {
			var eid =  $('#employee_id').val();
			var employee_calendar =  $('#employee_calendar').val();
			var employee_shift = $('#employee_shift').val();
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/calendarEmployee",
				"data": {
					'eid': eid,
					'employee_shift': employee_shift,
					'employee_calendar': employee_calendar
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Lưu thành công",
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
		
		
		$("#table-employee").on('click', '.btn-duplicate-employee', function(e) {
			var eid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/duplicateEmployee",
				"data": {
					'eid': eid
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Nhân bản thành công",
						  text: "Mã Khách hàng/NCC mới: " + data.service_employee_code,
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
::placeholder{
	font-size:12px;
	font-style: italic;
}
.btn-search{
	background-color:white;
	border:none;
}
label.error{
	color:red;
}
</style>
<div class="content container-fluid">
   
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h3 class="page-title">Danh sách <?php echo $category_name;?></h3>
            <ul class="breadcrumb">
               <!-- <li class="breadcrumb-item"><a href="<?php echo XC_URL?>">CloudERP</a></li> -->
               <li class="breadcrumb-item active"><?php echo $category_name;?></li>
            </ul>
         </div>
         <div class="col-auto">
             <a href="<?php echo $id;?>/add" class="btn btn-primary" data-method = 'add' data-toggle="" data-target=".bd-example-modal-lg" >
            Thêm <?php echo $category_name;?>
            </a>
            <!-- <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
            <i class="fas fa-filter"></i>
            </a> -->
         </div>
      </div>
   </div>
   
   </div>
   <div class="row">
      <div class="col-sm-12">
         <div class="card card-table">
            <div class="card-body">
               <div class="table-responsive">
                  <table id="table-services" class="table table-center table-hover datatable">
                     <thead class="thead-light">
                        <tr>
                           <th>STT</th>
                           <th>Tên <?php echo $category_name;?></th>
						   <th>Ảnh đại diện</th>
						   <th>Ngày tạo</th>
                           <th>Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							$i = 1;
							foreach($services as $service)
                           {
                           ?>
                        <tr>
                           <td>
                              <?php echo $i;?>
                           </td>
						   
                           <td >
                              <?php echo $service->service_name;?>
                           </td>
						    <td id='image'>
							<?php if($service->service_image != null){?>
							<img src="<?php echo XC_URL . '/uploads/services/' . $service->service_image; ?>" width="100" height="100"/></td>
							<?php }else{?>
							</td>
							<?php }?>
                           		
                           <td><?php echo $service->service_created_date;?></td>	
                           <td>
                              <div class="btn-group">
								    <a href="<?php echo $id; ?>/edit/<?php echo $service->sid;?>" data-method='update' class="btn btn-sm btn-white text-success btn-edit" >Sửa</a>
								   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									  <!-- <a class="dropdown-item" href="<?php echo $id; ?>/detail/<?php echo $service->sid;?>">Xem</a> -->
									  <a class="dropdown-item btn-delete-service" data-id="<?php echo $service->sid;?>" href="#" data-status="<?php echo $service->service_status;?>">Xoá</a>
									  
								</div>
                           </td>
                        </tr>
                        <?php
							$i++;
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


		<!--- Modal lịch làm việc -->
	<div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="exampleModalToggleLabel"><?php echo $category_name;?></h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2">
					<div class="form-group">
					<label>Ngày:</label>
						</div>	
				</div>
      			<div class="col-md-6">
					<div class="form-group">
					<input type="date"  value="<?php echo date('Y-m-d'); ?>" class="form-control" name='employee_calendar' id='employee_calendar'>
					
				</div>
			</div>
				<div class="col-md-4">
					<div class="form-group" >
						<select class="form-control" name='employee_shift' id='employee_shift' >
							<option value="1">Sáng</option>
							<option value="2">Chiều</option>
						</select>
						
					</div>	
				
				</div>
				</div>
			</div>
			<div class="modal-footer">
				<input type='hidden' value="" id="employee_id"/>
				<button class="btn btn-primary" id='updateCalendarEmployee' data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Lưu</button>
						   
			</div>
			</div>
		</div>
		</div>
		
		</div>
		<!-- <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal">Open first modal</button>	 -->
		<!-- end -->
<!-- <div class="modal fade bd-example-modal-xl"  id="edit_tax" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl"> -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Thông tin Tin tức</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" id="frm-action">
                  <div class="row">
                     <div class="col-md-12" data-select2-id="12">
                        <div class="row">
							<div class="col-md-2">
                              <div class="form-group">
                                 <label>Mã Tin tức:</label>
                                 <input type="text" readonly="true" value="<?php echo $employee_code;?>" class="form-control" name='employee_code' id='employee_code'>
                              </div>
                           </div>
                           
                           <div class="col-md-5">
                              <div class="form-group">
                                 <label>Họ và tên:</label><span class='text-danger'>*</span>
                                 <input type="text" class="form-control" id='employee_name' name="employee_name">
                              </div>
                           </div>
						    <div class="col-md-5">
                               <div class="form-group" >
                                 <label>Ngày sinh:</label><span class='text-danger'>*</span>
                                 <div class="cal-icon">
									<input class="form-control datetimepicker" type="text" id='employee_birthday' name="employee_birthday">
									</div>
                              </div>
                           </div>
						  
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Số điện thoại: </label><span class='text-danger'>*</span>
									<input class="form-control" type="text" id='employee_phone' name="employee_phone">
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Email: </label><span class='text-danger'>*</span>
									<input class="form-control" type="text" id='employee_email' name="employee_email">
							
                            </div>
						</div>
                           <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Giới tính:</label><span class='text-danger'>*</span>
						   		<select class='form-control' id='employee_gender' name='employee_gender'>
						   		<option value='1'>Nam</option>
								<option value='2'>Nữ</option>
								</select>
                                 
								
                              </div>
                           </div>							  
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>CMND: </label><span class='text-danger'>*</span>
									<input class="form-control" type="text" id='employee_national_id' name="employee_national_id">
						
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Ngày cấp:</label><span class='text-danger'>*</span>
                                 <div class="cal-icon">
									<input class="form-control datetimepicker" type="text" id='employee_issue_date' name="employee_issue_date" >
									</div>
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Nơi cấp: </label><span class='text-danger'>*</span>
									<input class="form-control" type="text" id='employee_issue_by' name="employee_issue_by">
							
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Khoa</label><span class='text-danger'>*</span>
									<select class="form-control" id='employee_department'>
										<?php foreach($departments as $departments){?>
										<option value = '<?php echo $departments->id?>'><?php echo $departments->depart_name;?></option>
										<?php }?>
									</select>
							
                              </div>
                           </div>
						   
						   <div class="col-md-8">
                              <div class="form-group" >
                                 <label>Hình ảnh </label>
									<input class="form-control" type="file" id='employee_image' name="employee_image">
							
                              </div>
                           </div>
						   <div class="col-md-12">
                              <div class="form-group" >
                                 <label>Giới thiệu ngắn về Tin tức </label>
								<textarea class='form-control' rows="5" cols="50" name = 'employee_des' id='employee_des'></textarea>
							
                              </div>
                           </div>
                        </div>
                        
                        
                       
                     </div>
                  </div>
                  <div class="text-end">
                     <button type="button" class="btn btn-primary" id = 'addEmployee'>Thêm</button>
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