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
			"booking_phone": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 8
			},
			"booking_national_id": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 5
			},
			"booking_name":{
				required: true
			},
			"booking_branch":{
				required: true
			},
			"booking_position":{
				required: true
			},
			"booking_address":{
				required: true
			},
			"booking_birthday":{
				required: true
			},
			"booking_gender":{
				required: true
			},
			"booking_email":{
				required: true
			},
			"booking_department":{
				required: true
			},
			"booking_issue_date":{
				required: true
			},
			"booking_issue_by":{
				required: true
			},
			"booking_issue_date":{
				required: true
			}
			
			
			
		},
		messages:{
				booking_national_id: {
					required: "Vui lòng số CMND",
					minlength: "số CMND phải vượt quá 5 ký tự",
					maxlength: "số CMND phải ngắn hơn 15 ký tự"
				},
				booking_phone: {
					required: "Vui lòng nhập số điện thoại",
					minlength: "Số điện thoại phải vượt quá 8 ký tự",
					maxlength: "Số điện thoại phải ngắn hơn 15 ký tự"
				},
				booking_name: "Vui lòng nhập tên nhân viên",
				booking_branch: "Vui lòng chọn đơn vị",
				booking_position: "Vui lòng chọn chức danh",
				booking_address: "Vui lòng nhập địa chỉ",
				booking_birthday: "Vui lòng nhập ngày sinh",
				booking_gender: "Vui lòng chọn giới tính",
				booking_email: "Vui lòng nhập email",
				booking_department: "Vui lòng chọn phòng ban",
				booking_issue_date: "Vui lòng nhập ngày cấp",
				booking_issue_by: "Vui lòng nhập nơi cấp"
				
			}
	});
		$("#table-booking").on('click', '.btn-delete-booking', function(e) {
			var eid = $(this).attr("data-id");
			var booking_status =  $(this).attr("data-status");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deletebooking",
				"data": {
					'eid': eid,
					'booking_status': booking_status
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
		$("#table-booking").on('click', '.btn-calendar-booking', function(e) {
			$('#booking_id').val($(this).data('id'));

		});
		$('#updateCalendarbooking').click(function(e) {
			var eid =  $('#booking_id').val();
			var booking_calendar =  $('#booking_calendar').val();
			var booking_shift = $('#booking_shift').val();
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/calendarbooking",
				"data": {
					'eid': eid,
					'booking_shift': booking_shift,
					'booking_calendar': booking_calendar
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
		
		
		$("#table-booking").on('click', '.btn-approve', function(e) {
			var bid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/approveBooking",
				"data": {
					'bid': bid
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Đã duyệt",
						//   text: "Mã Khách hàng/NCC mới: " + data.new_booking_code,
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
	
		$('#addbooking').click(function(e) {
			if ($("#frm-action").valid()) {
				var formData = new FormData();

				formData.append('booking_code', $('#booking_code').val());
				formData.append('booking_name', $('#booking_name').val());
				formData.append('booking_address', $('#booking_address').val());
				formData.append('booking_birthday', $('#booking_birthday').val());
				formData.append('booking_gender', $('#booking_gender').val());
				formData.append('booking_phone', $('#booking_phone').val());
				formData.append('booking_email', $('#booking_email').val());
				formData.append('booking_department', $('#booking_department').val());
				formData.append('booking_national_id', $('#booking_national_id').val());
				formData.append('booking_issue_date', $('#booking_issue_date').val());
				formData.append('booking_issue_by', $('#booking_issue_by').val());
				formData.append('booking_des', $('#booking_des').val());

				// Upload file
				var file = $('#booking_image')[0].files[0];
				if (file) {
					formData.append('booking_image', file);
				}
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addbooking",
				data:formData,
				dataType: 'json',
				enctype: 'multipart/form-data',
				processData: false,
				contentType: false,
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
            <h3 class="page-title">Danh sách bệnh nhân đặt lịch khám bệnh</h3>
            <ul class="breadcrumb">
               <!-- <li class="breadcrumb-item"><a href="<?php echo XC_URL?>">CloudERP</a></li> -->
               <!-- <li class="breadcrumb-item active">Bác sĩ</li> -->
            </ul>
         </div>
         <!-- <div class="col-auto">
             <a href="#" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-lg" >
            Đăng ký lịch khám
            </a> -->
            <!-- <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
            <i class="fas fa-filter"></i>
            </a> -->
         <!-- </div> -->
      </div>
   </div>
   
   </div>
   <div class="row">
      <div class="col-sm-12">
         <div class="card card-table">
            <div class="card-body">
               <div class="table-responsive">
                  <table id="table-booking" class="table table-center table-hover datatable">
                     <thead class="thead-light">
                        <tr>
                           <th>STT</th>
                           <th>Tên bệnh nhân</th>
						   <th>Số điện thoại</th>
						   <th>Giới tính</th>
						   <th>Năm sinh</th>
                           <th>Địa chỉ</th>
						   <th>Bác sĩ khám</th>
						   <th>Thời gian</th>
						   <th>Trạng thái</th>
                           <th>Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							$i = 1;
							foreach($bookings as $booking)
                           {
                           ?>
                        <tr>
                           <td>
                              <?php echo $i;?>
                           </td>
						   
                           <td >
                              <?php echo $booking->booking_person_name;?>
                           </td>
						   <td>
							<?php echo $booking->booking_person_phone;?>
						   </td>
						    <td>
                              <?php if($booking->booking_person_gender == 1){echo "Nam";}else{ echo "Nữ";}?>
                           </td>
                           <td><?php echo $booking->booking_person_year;?></td>
                           <td><?php echo $booking->booking_person_address;?></td>
                           <td><?php echo $booking->booking_name;?></td>
                           <td><?php 
						   echo !empty($booking->booking_date) ? date('d-m-Y', strtotime($booking->booking_date)) : 'Chưa đặt ngày';
							?>
								<span > <?php 
								echo  !empty($booking->booking_hour) ? " - " . $booking->booking_hour : '';
								?></span>
							</td>
						   
                           <td class='text-<?php echo $booking->bk_status_class;?>'><?php echo $booking->bk_status_label;?></td>
                           </td>
                           <td>
                              <div class="btn-group">
								    <a href="#" data-status="<?php echo $booking->booking_status;?>" data-id='<?php echo $booking->ibk; ?>' class="btn btn-sm btn-white text-success btn-approve" >Duyệt</a>
								   <!-- <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									  <a class="dropdown-item text-primary" href="#" data-id="<?php echo $booking->ibk;?>">Sửa</a>
									  <a class="dropdown-item btn-delete-booking text-danger" data-id="<?php echo $booking->ibk;?>" href="#" data-status="<?php echo $booking->booking_status;?>">Hủy</a>
									  
									
									  <div class="dropdown-divider"></div>
									  
									  <a class="dropdown-item btn-calendar-booking text-warning" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-id='<?php echo $booking->bookingid;?>' href="#">Lịch khám bệnh</a>
								   </div> -->
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


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Lịch khám của bệnh nhân</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" id="frm-action">
                  <div class="row">
                     <div class="col-md-12" data-select2-id="12">
                        <div class="row">
							<div class="col-md-6">
                              <div class="form-group">
                                 <label> Họ và tên: </label>
                                 <input type="text" value="<?php echo $booking_code;?>" class="form-control" name='booking_code' id='booking_code'>
                              </div>
                           </div>
                           
                           <div class="col-md-6">
                              <div class="form-group">
                                 <label>Điện thoại:</label><span class='text-danger'>*</span>
                                 <input type="text" class="form-control" id='booking_name' name="booking_name">
                              </div>
                           </div>
						    <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Giới tính:</label><span class='text-danger'>*</span>
						   		<select class='form-control' id='booking_gender' name='booking_gender'>
						   		<option value='1'>Nam</option>
								<option value='2'>Nữ</option>
								</select>
                              </div>
                           </div>

						   <div class="col-md-6">
                              <div class="form-group" >
                                 <label>Chọn bác sĩ</label><span class='text-danger'>*</span>
									<select class="form-control" id='booking_department'>
										<?php foreach($doctors as $doctor){?>
										<option value = '<?php echo $doctor->id?>'><?php echo $doctor->booking_name;?></option>
										<?php }?>
									</select>
                              </div>
                           </div>
						  
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="row">
                           <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Tuổi: </label><span class='text-danger'>*</span>
									<input class="form-control" type="text" id='booking_phone' name="booking_phone" min = '4' max = '4' placeholder="VD:1970">
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Email: </label><span class='text-danger'>*</span>
									<input class="form-control" type="text" id='booking_email' name="booking_email">
							
                            </div>
						</div>
                           <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Giới tính:</label><span class='text-danger'>*</span>
						   		<select class='form-control' id='booking_gender' name='booking_gender'>
						   		<option value='1'>Nam</option>
								<option value='2'>Nữ</option>
								</select>
                                 
								
                              </div>
                           </div>							  
						   
						  
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Địa chỉ: </label><span class='text-danger'>*</span>
									<input class="form-control" type="text" id='booking_issue_by' name="booking_issue_by">
							
                              </div>
                           </div>
						   <div class="col-md-4">
                              <div class="form-group" >
                                 <label>Chọn bác sĩ</label><span class='text-danger'>*</span>
									<select class="form-control" id='booking_department'>
										<?php foreach($doctors as $doctor){?>
										<option value = '<?php echo $doctor->id?>'><?php echo $doctor->booking_name;?></option>
										<?php }?>
									</select>
							
                              </div>
                           </div>
						   
						  
						   <div class="col-md-12">
                              <div class="form-group" >
                                 <label>Giới thiệu ngắn về Bác sĩ </label>
								<textarea class='form-control' rows="5" cols="50" name = 'booking_des' id='booking_des'></textarea>
							
                              </div>
                           </div>
                        </div>
                        
                        
                       
                     </div>
                  </div>
                  <div class="text-end">
                     <button type="button" class="btn btn-primary" id = 'addbooking'>Thêm</button>
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