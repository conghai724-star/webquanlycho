<?php include_once "header.php";?>
<script>
	$(document).ready(function(){
		$("#table-customer").on('click', '.btn-delete-customer', function(e) {
			var cid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deletecustomer",
				"data": {
					'cid': cid
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
						  title: "Lỗi",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			
			});
			return false;
		});
		$("#table-customer").on('click', '.btn-deactive-customer', function(e) {
			var cid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deactivecustomer",
				"data": {
					'cid': cid
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Cập nhật thành công",
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
		$("#table-customer").on('click', '.btn-active-customer', function(e) {
			var cid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/activecustomer",
				"data": {
					'cid': cid
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Cập nhật thành công",
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
		$("#table-customer").on('click', '.btn-duplicate-customer', function(e) {
			var cid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/duplicatecustomer",
				"data": {
					'cid': cid
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Nhân bản thành công",
						  text: "Mã Khách hàng/NCC mới: " + data.new_customer_code,
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
		$("#checkedAll").change(function(){
			if($(this).is(":checked")){
				$(".checkedItem").prop('checked', $(this).prop('checked'));
				var id = $('#checkedItem').attr("data-id");
				console.log(id);
			}
		});
		
		
		
		
		
	});
</script>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h3 class="page-title">Danh sách Khách hàng/NCC</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL?>">CloudERP</a></li>
               <li class="breadcrumb-item active">Khách hàng/NCC</li>
            </ul>
         </div>
         <div class="col-auto">
            <a href="<?php echo XC_URL?>/app/addcustomer" class="btn btn-primary me-1">
            <i class="fas fa-plus"></i> Thêm Khách hàng/NCC
            </a>
            <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
            <i class="fas fa-filter"></i>
            </a>
         </div>
      </div>
   </div>
   <form action = "<?php echo XC_URL?>/app/customers" method = "GET">
   <div id="filter_inputs" class="card filter-card">
      <div class="card-body pb-0">
         <div class="row">
            <div class="col-sm-6 col-md-6">
               <div class="form-group">
                  <label>Từ khoá</label>
                  <input type="text" name="keyword" class="form-control" id = 'keyword'>
               </div>
            </div>
            <div class="col-sm-6 col-md-2">
               <div class="form-group">
                  <label>Nhóm</label>
                  <select class="select select2" name="customer_group" id = 'group_customer'>
					  <option value="1">Khách hàng</option>
					  <option value="2">Nhà cung cấp</option>
					  
				   </select>
               </div>
            </div>
            <div class="col-sm-6 col-md-2">
               <div class="form-group">
                  <label>Trạng thái</label>
                  <select class="select select2" name="customer_status" id = 'status_customer'>
					  <option value="1">Hoạt động</option>
					  <option value="2">Ngừng hoạt động</option>
					  
				   </select>
               </div>
            </div>
			<div class="col-sm-6 col-md-2 text-end">
				<label>&nbsp;</label>
				<button type="submit" class="btn btn-block btn-outline-primary active" id = 'filter_customer' name = "">Lọc</button>
			</div>
         </div>
      </div>
   </div>
   </form>
   <div class="row">
      <div class="col-sm-12">
         <div class="card card-table">
            <div class="card-body">
               <div class="table-responsive">
                  <table id="table-customer" class="table table-center table-hover datatable">
                     <thead class="thead-light">
                        <tr>
							<!--<th id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1"></label><input id="cb-select-all-1" type="checkbox"></th>-->
							<th><input id="checkedAll" type="checkbox" ></th>
                           <th>Mã KH</th>
                           <th>Tên KH</th>
                           <th>Địa chỉ</th>
                           <th>Công nợ</th>
                           <th>Điện thoại</th>
                           <th>Trạng thái</th>
                           <th class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($customers as $customer)
                           {
                           ?>
                        <tr>
						<td><input id="checkedItem" class = "checkedItem" type="checkbox" name="post[]" value="" data-id ="[<?php echo $customer->cid; ?>]">
							<div class="locked-indicator"></td>
							<!--<th scope="row" class="check-column">			
								<input id="cb-select-15309" type="checkbox" name="post[]" value="">
							<div class="locked-indicator">
							<span class="locked-indicator-icon" aria-hidden="true"></span>
							</div>
							</th>-->
                           <td>
                              <?php echo $customer->customer_code;?>
                           </td>
                           <td>
                              <?php echo $customer->customer_name;?>
                           </td>
                           <td><?php echo $customer->customer_email;?></td>
                           <td  class="text-right"><?php echo number_format($customer->customer_debt,0);?></td>
                           <td><?php echo $customer->customer_phone;?></td>
                           <td><span class="badge badge-pill bg-<?php echo $customer->status_class;?>-light"><?php echo $customer->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
								   <button type="button" class="btn btn-sm btn-success">Lập phiếu bán hàng</button>
								   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									  <a class="dropdown-item" href="<?php echo XC_URL;?>/app/editcustomer/<?php echo $customer->cid;?>">Sửa</a>
									  <a class="dropdown-item" href="customers/detail/<?php echo $customer->cid;?>">Xem</a>
									  <a class="dropdown-item btn-duplicate-customer" data-id="<?php echo $customer->cid;?>" href="#">Nhân bản</a>
									  <a class="dropdown-item btn-delete-customer" data-id="<?php echo $customer->cid;?>" href="#">Xoá</a>
									  <?php if($customer->customer_status == 1)
									  {
										?>
									  <a class="dropdown-item btn-deactive-customer" data-id="<?php echo $customer->cid;?>" href="#">Ngừng sử dụng</a>
									 <?php
									  }
									  else
									  {
										  ?>
										 <a class="dropdown-item btn-active-customer" data-id="<?php echo $customer->cid;?>" href="#">Kích hoạt</a>
										  <?php
									  }
									 ?>
									  <div class="dropdown-divider"></div>
									  <a class="dropdown-item" href="#">Tạo phiếu thu</a>
									  <a class="dropdown-item" href="#">Tạo phiếu chi</a>
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
<?php include_once "footer.php";?>