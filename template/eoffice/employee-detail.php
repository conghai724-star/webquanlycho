<?php include "header.php";?>
<style>
.title_box{
	font-size: 18px;
    font-weight: bold;
}
</style>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row align-items-center">
	  
	   <div class="page-title"><span class = 'title_box'><?php echo $employee -> employee_name;?></span>
	  <div style = 'float: right;'>
	   <a href="<?php echo XC_URL;?>/admin/editEmployee/<?php echo $employee->employeeid;?>" class="btn btn-sm btn-white text-success me-2" >Sửa
            </a>
			<div class="btn-group">
								   <button type="button" class="btn btn-sm btn-success">Báo cáo &nbsp;|
								   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									  <a class="dropdown-item" href="#">Biên bản đối chiếu và xác nhận công nợ</a>
									  <a class="dropdown-item" href="#">Thông báo công nợ</a>
									  
								   </div>
								</div>
							</div>
			</div>
	   <div>
	   
            
	  </div>
	  
         <div class="col-6 box-breadcrumb">
			<div class = 'row'>
				<div class = 'col-2'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Mã: </span>
				</div>
				<div class = 'col-10'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $employee->employee_code;?></span>
				</div>
			</div>
			
			<div class = 'row'>
				<div class = 'col-2'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Địa chỉ: </span>
				</div>
				<div class = 'col-10'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $employee->branch_address;?></span>
				</div>
			</div>
			<div class = 'row'>
				<div class = 'col-2'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Công ty: </span>
				</div>
				<div class = 'col-10'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $employee->branch_name;?></span>
				</div>
			</div>
			<div class = 'row'>
				<div class = 'col-2'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Phòng ban: </span>
				</div>
				<div class = 'col-10'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $employee->depart_name;?></span>
				</div>
			</div>
			<div class = 'row'>
				<div class = 'col-2'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Chức vụ: </span>
				</div>
				<div class = 'col-10'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $employee->position_title;?></span>
				</div>
			</div>
				
			  
			
         </div> 
		 <div class="col-4">
		 <div class = 'row'>
				<div class = 'col-6'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Ngày sinh:  </span>
				</div>
				<div class = 'col-6'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo 
					  date('d/m/Y', strtotime($employee	->employee_birthday));?></span>
				</div>
			</div>
			 <div class = 'row'>
				<div class = 'col-6'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Giới tính: </span>
				</div>
				<div class = 'col-6'>
					  <span class = "breadcrumb-item title_breadcrumb">
					  <?php if($employee->employee_gender == 1){
						  echo '<span> Nam </span>';
					  }else {
						  echo '<span> Nữ </span>';
					  }?>
					  </span>
				</div>
			</div>
            <div class = 'row'>
				<div class = 'col-6'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Điện thoại: </span>
				</div>
				<div class = 'col-6'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $employee->employee_phone;?></span>
				</div>
			</div>
			
			<div class = 'row'>
				<div class = 'col-6'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Số CMND:  </span>
				</div>
				<div class = 'col-6'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $employee	->employee_national_id;?></span>
				</div>
			</div>
			<div class = 'row'>
				<div class = 'col-6'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'> Email:  </span>
				</div>
				<div class = 'col-6'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $employee->employee_email;?></span>
				</div>
			</div>
         </div> 
         <div class="col-2" style = "text-align: end;">
				<span class = "breadcrumb-item" >Nợ còn phải thu: 0</span><p>
				<span class = "breadcrumb-item" style = "color: #ff9933;">Nợ quá hạn:  0</span>
         </div>
      </div>
   </div>
   <div id="filter_inputs" class="card filter-card">
      <div class="card-body pb-0">
         <div class="row">
            <div class="col-md-3">
               <div class="form-group">
                  <label>Customer:</label>
                  <input type="text" class="form-control">
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                  <label>Loại:</label>
                  <select class="select">
                     <option>Ngày hạch toán</option>
                     <option>Số chứng từ</option>
                     <option>Ngày hóa đơn</option>
                     <option>Hạn thanh toán</option>
                     <option>Số còn phải thu/ Số thu trước</option>
                  </select>
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                  <label>From</label>
                  <div class="cal-icon">
                     <input class="form-control datetimepicker" type="text">
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="form-group">
                  <label>To</label>
                  <div class="cal-icon">
                     <input class="form-control datetimepicker" type="text">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-12">
         <div class="card card-table">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-center table-hover datatable">
                     <thead class="thead-light">
                        <tr>
                           <th>Loại</th>
                           <th>Ngày hạch toán</th>
                           <th>Số chứng từ</th>
                           <th>Ngày hóa đơn</th>
                           <th>Hạn thanh toán</th>
                           <th>Số còn phải thu/ Số thu trước</th>
                           <th class="text-end">Chức năng</th>
                        </tr>
                     </thead>
                     <tbody>
                        <!--<tr>
                           <td>Loại</td>
                           <td>
                              15 Nov 2020
                           </td>
                           <td>15 Nov 2020</td>
                           <td>Lorem ipsum dollar...</td>
                           <td>$145</td>
                           <td><span class="badge badge-pill bg-success-light">Approved</span></td>
                           <td class="text-end">
                              <a href="edit-expenses.html" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                              <a href="javascript:void(0);" class="btn btn-sm btn-white text-danger"><i class="far fa-trash-alt me-1"></i>Delete</a>
                           </td>
                        </tr> -->
                        
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