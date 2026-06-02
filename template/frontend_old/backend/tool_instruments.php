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
   	$("#table-employee").on('click', '.btn-delete-employee', function(e) {
   		var eid = $(this).attr("data-id");
   		var employee_status =  $(this).attr("data-status");
   		$.ajax({
   			"type": "POST",
   			"url": "<?php echo XC_URL; ?>/api/deleteEmployee",
   			"data": {
   				'eid': eid,
   				'employee_status': employee_status
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
   					  text: "Mã Khách hàng/NCC mới: " + data.new_employee_code,
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
   
   	$('#addEmployee').click(function(e){
   		if($("#frm-action").valid())
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
   		}
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
            <h3 class="page-title"><?php echo $pagetitle; ?></h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL?>">CloudERP</a></li>
               <li class="breadcrumb-item active">Công cụ dụng cụ</li>
            </ul>
         </div>
         <div class="col-auto">
            <a href="#" data-bs-toggle="modal" data-bs-target="#edit_tax" class="btn btn-primary me-1" >
            <i class="fas fa-plus"></i>Thêm
            </a>
            <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
            <i class="fas fa-filter"></i>
            </a>
         </div>
      </div>
   </div>
   <form action = "" method = "GET">
      <div id="filter_inputs" class="card filter-card">
         <div class="card-body pb-0">
            <div class="row">
               <div class="col-sm-6 col-md-6">
                  <div class="form-group">
                     <label>Từ khoá</label>
                     <input type="text" name="keyword" class="form-control" placeholder="Nhập vào mã, tên nhân viên" >
                  </div>
               </div>
               <div class="col-sm-6 col-md-2">
                  <div class="form-group">
                     <label>Phòng ban</label>
                     <select class="select select2" name="department" >
                        <option disabled selected = 'selected'>Chọn phòng ban</option>
                        <?php foreach($departments as $department){?>
                        <option value="<?php echo $department->id;?>"><?php echo $department->depart_name;?></option>
                        <?php }?>
                     </select>
                  </div>
               </div>
               <div class="col-sm-6 col-md-2">
                  <div class="form-group">
                     <label>Chức danh</label>
                     <select class="select select2" name="position">
                        <option disabled selected = 'selected'>Chọn chức danh</option>
                        <?php foreach($positions as $position){?>
                        <option value="<?php echo $position->id?>"><?php echo $position->position_title;?></option>
                        <?php }?>
                     </select>
                  </div>
               </div>
               <div class="col-sm-6 col-md-2">
                  <label>&nbsp;</label>
                  <button type="submit" class="btn btn-block btn-outline-primary active" id = 'filter_customer' name = "" style="margin-top:22px;">Lọc</button>
               </div>
            </div>
         </div>
      </div>
   </form>
</div>
<div class="row">
   <div class="col-sm-12">
      <div class="card card-table">
         <div class="card-body">
            <div class="table-responsive">
               <table id="table-employee" class="table table-center table-hover datatable">
                  <thead class="thead-light">
                     <tr>
                        <th>STT</th>
                        <th>Số chứng từ</th>
                        <th>Ngày ghi tăng</th>
                        <th>Mã CCDC</th>
                        <th>Tên CCDC</th>
                        <th>Số lượng ghi tăng</th>
                        <th>Giá trị ghi tăng</th>
                        <th>Số kỳ phân bổ</th>
                        <th class="text-right">Thao tác</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $i = 1;
                        foreach($employees as $employee)
                                            {
                                            ?>
                     <tr>
                        <td>
                           <?php echo $i;?>
                        </td>
                        <td>
                           <?php echo $employee->employee_code;?>
                        </td>
                        <td>
                           <?php echo $employee->employee_name;?>
                        </td>
                        <td><?php echo $employee->branch_name;?></td>
                        <td><?php echo $employee->position_title;?></td>
                        <?php if($employee->depart_status == 1){?>
                        <td><?php echo $employee->depart_name; ?></td>
                        <?php }else{?>
                        <td><?php echo ''; ?></td>
                        <?php }?>
                        <td  class="text-right">0</td>
                        <td><?php echo $employee->employee_phone;?></td>
                        <td class="text-right">
                           <div class="btn-group">
                              <a href="editEmployee/<?php echo $employee->employeeid;?>" class="btn btn-sm btn-white text-success btn-edit" >Sửa</a>
                              <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="sr-only">Toggle Dropdown</span>
                              </button>
                              <div class="dropdown-menu">
                                 <a class="dropdown-item" href="employees/detail/<?php echo $employee->employeeid;?>">Xem</a>
                                 <a class="dropdown-item btn-duplicate-employee" data-id="<?php echo $employee->employeeid;?>" href="#">Nhân bản</a>
                                 <a class="dropdown-item btn-delete-employee" data-id="<?php echo $employee->employeeid;?>" href="#" data-status="<?php echo $employee->employee_status;?>">Xoá</a>
                                 <!--<div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Tạo phiếu thu</a>
                                    <a class="dropdown-item" href="#">Tạo phiếu chi</a>-->
                              </div>
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
<div class="modal fade bd-example-modal-xl"  id="edit_tax" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header">
                     <h5 class="card-title">Ghi tăng CCDC</h5>
                  </div>
                  <div class="card-body">
                     <form action="#" data-select2-id="13" id="frm-action">
                        <div class="row">
                           <div class="col-md-6" data-select2-id="12">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label>Số CT ghi tăng</label>
                                       <input type="text" class="form-control" name='	tool_voucher_number' id='	tool_voucher_number'>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label>Ngày ghi tăng</label>
                                       <input type="date" class="form-control" id='tool_date' name="tool_date">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label>Mã CCDC</label>
                                       <input type="text" class="form-control" id='tool_code' name="tool_code">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label>Loại CCDC</label>
                                       <select class="select select2" id="tool_category_id" name="tool_category_id" >
                                          <option disabled selected = 'selected'>Chọn</option>
                                          <?php foreach($departments as $department){?>
                                          <option value="<?php echo $department->id;?>"><?php echo $department->depart_name;?></option>
                                          <?php }?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <label>Tên CCDC</label>
                                    <input type="text" class="form-control" id='tool_name' name="tool_name">
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <input type="checkbox" id='tool_is_stop' name="tool_is_stop">
                                       <label>Ngừng phân bổ</label>
                                    </div>
                                 </div>
                                 <div class="col-md-8">
                                    <div class="form-group">
                                       <label>Lý do ngừng phân bổ</label>
                                       <input class="form-control" type="text" id='tool_reason_stop' name="tool_reason_stop">	
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="row">
                                 <div class="col-md-6">
                                    <div class="form-group" >
                                       <label>Số kỳ phân bổ</label>              
                                       <input class="form-control" type="text" id='tool_allotment_time' name="tool_allotment_time">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                       <label>Số tiền PB hằng kỳ</label>
                                       <input class="form-control" type="text" id='tool_allotment_money' name="tool_allotment_money">
                                    </div>
                                 </div>
                                 <div class="form-group" >
                                    <label>Lý do ghi tăng</label>
                                    <input class="form-control" type="text" id='tool_description' name="tool_description">
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group" >
                                       <label>Đơn vị tính</label>              
                                       <select class="select select2" id="tool_unit" name="tool_unit" >
                                          <option disabled selected = 'selected'>Chọn</option>
                                          <?php foreach($departments as $department){?>
                                          <option value="<?php echo $department->id;?>"><?php echo $department->depart_name;?></option>
                                          <?php }?>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group" >
                                       <label>Số lượng</label>
                                       <input class="form-control" type="text" id='tool_quantity' name="tool_quantity">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group" >
                                       <label>Đơn giá</label>
                                       <input class="form-control" type="text" id='tool_price' name="tool_price">
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group" >
                                       <label>Thành tiền</label>
                                       <input class="form-control" type="text" id='tool_total_money' name="tool_total_money" >
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