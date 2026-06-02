<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[A-Z, '']+$/);

    }, "Vui lòng nhập ký tự hoa!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"warehouse_name":{
				required: true
			},
			"warehouse_branch_id":{
				required: true
			},
			"warehouse_parent":{
				required: true
			}
			
			
		},
		messages:{
				warehouse_name: "Vui lòng nhập tên kho hàng",
				warehouse_branch_id: "Vui lòng chọn công ty",
				warehouse_parent: "Vui lòng chọn phân hệ"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var warehouse_code = $("#warehouse_code").val(); 
			var warehouse_name = $("#warehouse_name").val();
			var warehouse_parent = $("#warehouse_parent").val();
			var warehouse_branch_id = $("#warehouse_branch_id").val();
			var warehouse_description = $("#warehouse_description").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/warehouses",
				data: {
					"method" : method,
					"id" : id,
					"warehouse_code": warehouse_code,
					"warehouse_name": warehouse_name,
					"warehouse_branch_id": warehouse_branch_id,
					"warehouse_parent": warehouse_parent,
					"warehouse_description": warehouse_description
				},
				dataType: "json",
				success: function(data){
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
		}
   	});
   	$("#table-warehouse").on('click', '.btn-sm-modal', function(e) {
   		var btn = $(this);
   		var method = btn.attr("data-method");
   		var id = btn.attr("data-id");
		var warehouse_code = btn.attr("data-code");
		var warehouse_name = btn.attr("data-name");
		var warehouse_rate = btn.attr("data-rate");
		var warehouse_type = btn.attr("data-type");
		function isSelected(){
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL?>/api/",
				data: {
					'id': id,
					'method': method,
					'warehouse_type': warehouse_type
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						$('#warehouse_type').html(data.data);
						
					}else{
						alert(data.message);
					}
				}
			});
		}
		switch(method) {
		  case "update":
			isSelected();
				//Update
				$("#warehouse_code").val(warehouse_code);
				$('#warehouse_code').prop('readonly', true);
				$("#data_id").val(id);
				$("#form_method").val("update");
				$("#warehouse_name").val(warehouse_name);
				$("#warehouse_rate").val(warehouse_rate);
				$("#warehouse_type").val(warehouse_type);
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa kho hàng");
				$("#form-modal").modal("show");
			break;
		  case "new":
				$("#warehouse_code").val(warehouse_code);
				$('#warehouse_code').prop('readonly', true);
				$("#data_id").val('');
				$("#form_method").val("new");
				$("#warehouse_name").val('');
				$("#warehouse_rate").val('');
				$("#warehouse_type").val('');
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm kho hàng");
				$("#form-modal").modal("show");
			break;
		  default:
			alert("Không tìm thấy tác vụ thực hiện!");
		}
   		
   	});
	
	$("#table-warehouse").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var warehouse_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_warehouses",
			data: {
				"id": id,
				"warehouse_status": warehouse_status,
				"method": method
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
	//Them cong ty
	$("#frm-add-company").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"branch_tax_code": {
				required: true,
				maxlength: 10,
				minlength: 10
			},
			"branch_name":{
				required: true
			},
			"branch_address":{
				required: true
			},
			"branch_phone":{
				required: true
			},
			"branch_email":{
				required: true
			},
			"branch_director":{
				required: true
			}
			
			
		},
		messages:{
				branch_tax_code: {
					required: "Vui lòng nhập mã số thuế",
					minlength: "Mã số thuế phải ít nhất 10 số",
					maxlength: "Mã số thuế không vượt quá 10 số"
				},
				branch_name: "Vui lòng nhập tên công ty",
				branch_address: "Vui lòng nhập địa chỉ công ty",
				branch_phone: "Vui lòng nhập số điện thoại công ty",
				branch_email: "Vui lòng nhập email công ty",
				branch_director: "Vui lòng người đại diện"
			}
	});
	$("#btn_add_company").click(function(){
		if($("#frm-add-company").valid())
		{
			var branch_tax_code = $('#branch_tax_code').val();
			var branch_name = $("#branch_name").val();
			var branch_address = $("#branch_address").val();
			var branch_phone = $("#branch_phone").val();
			var branch_email = $("#branch_email").val();
			var branch_director = $("#branch_director").val();
			var branch_founded_date = $("#branch_founded_date").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL?>/api/addBranchs",
				data: {
					'branch_tax_code': branch_tax_code,
					'branch_name': branch_name,
					'branch_address':branch_address,
					'branch_phone': branch_phone,
					'branch_email': branch_email,
					'branch_director':branch_director,
					'branch_founded_date':branch_founded_date
				},
				dataType: 'json',
				success: function(data){
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
							  title: data.message,
							  footer: '<a href=""></a>',
							  timer: 1700
							})
					}
				}
			});
			return false;
		}
	});
	//end
	
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
<div id="form-modal" class="modal custom-modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="form_add_title">Thêm kho hàng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Mã kho hàng<span class="text-danger">*</span></label>
                  <input class="form-control" name="warehouse_code" required type="text" id="warehouse_code" >
               </div>
               <div class="form-group">
                  <label>Tên kho hàng<span class="text-danger">*</span></label>
                  <input class="form-control" name="warehouse_name" type="text" id="warehouse_name">
               </div> 
                <div class="form-group">
                                 <label>Thuộc công ty:</label>
								 <div class='' style = "display: flex;">
                                 <select class="select select2" id='warehouse_branch_id' name="warehouse_branch_id">
								 <option disabled selected="selected">Chọn công ty</option>
								 <?php foreach($branchs as $row_branch){ ?>
								<option value="<?php echo $row_branch->id;?>"><?php echo $row_branch->branch_name;?></option>
								<?php }?>
								 
							   </select>
							    <a href="#" data-bs-toggle="modal" data-bs-target="#form-add-company" class="btn btn-sm btn-white text-success" ><i class="fa fa-plus" style = 'width: 30px; margin-top: 12px;'></i></a>
							   </div>
									
							   
                              </div>
			   
               <div class="form-group">
                  <label>Diễn giải<span class="text-danger">*</span></label>
                  <input class="form-control" name="warehouse_description" type="text" id="warehouse_description">
               </div>
				<div class="form-group">
					<label>Phân hệ<span class="text-danger">*</span></label>
					<select class="select select2" name="warehouse_parent" id="warehouse_parent">
						<option disabled selected="selected">Chọn</option>
						<option value="0">Mục gốc</option>
						<?php foreach($warehouses as $row_warehouse){?>
							<option value="<?php echo $row_warehouse->id;?>"><?php echo $row_warehouse->warehouse_name;?></option>
						<?php }?>
					</select>
				</div>

        
               <div class="submit-section">
                  <button class="btn btn-primary submit-btn" id="btn_submit_modal" type="button">Thêm mới</button>
                  <input type="hidden" id="form_method" value="new">
				 
                  <input type="hidden" id="data_id" value="">
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- Modal add company -->
	 <div id="form-add-company" class="modal custom-modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="form_add_title">Thêm công ty</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-add-company" >
               <div class="form-group">
                  <label>Tên công ty<span class="text-danger">*</span></label>
                  <input class="form-control" name="branch_name" required type="text" id="branch_name">
               </div>
               
			   <div class="row">
				   <div class="col-sm-6">
					   <div class="form-group">
						   <label>Mã số thuế<span class="text-danger">*</span></label>
							<input class="form-control" name="branch_tax_code" type="text" id="branch_tax_code">
						</div>
				   </div>
				   <div class="col-sm-6">
					   <div class="form-group">
						   <label>Số điện thoại<span class="text-danger">*</span></label>
							<input class="form-control" name="branch_phone" type="text" id="branch_phone">
						</div>
				   </div>
				   
				</div>
				
				<div class="row">
				   <div class="col-sm-6">
					   <div class="form-group">
						   <label>Email<span class="text-danger">*</span></label>
							<input class="form-control" name="branch_email" type="text" id="branch_email">
						</div>
				   </div>
				   <div class="col-sm-6">
					   <div class="form-group">
						   <label>Người đại diện<span class="text-danger">*</span></label>
							<input class="form-control" name="branch_director" type="text" id="branch_director">
						</div>
				   </div>
				   
				</div>
                  
               <div class="form-group">
                  <label>Địa chỉ<span class="text-danger">*</span></label>
                  <input class="form-control" name="branch_address" type="text" id="branch_address">
               </div>
				<div class="form-group">
					<label>Ngày thành lập<span class="text-danger">*</span></label>
					<input class="form-control" name="branch_founded_date" type="date" id="branch_founded_date">
					
				</div>

        
               <div class="submit-section">
                  <button class="btn btn-primary submit-btn" id="btn_add_company" type="button">Thêm mới</button>
                
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<!-- end -->
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
            <h3 class="page-title">Kho hàng - <?php echo $name_branch;?> </h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
               <li class="breadcrumb-item active">Kho hàng</li>
            </ul>
      </div>
   </div>
   <div class="row"  >
      <div class="col-sm-12">
         <div class="card card-table" id="table-warehouse">
            <div class="card-header">
				<div class="row">
					<div class="col">
						<h5 class="card-title">Danh sách kho hàng</h5>
					</div>
					<div class="col-auto">
						<a href="javascript:void(0);" class="btn btn-primary btn-sm-modal"  data-method="new" data-code="<?php echo $warehouse_code;?>"><i class="fas fa-plus"></i> Thêm kho hàng</a>
					</div>
				</div>
			</div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-center table-hover mb-0 datatable" >
                     <thead class="thead-light">
                        <tr>
							<th style="width: 5%">STT</th>
                           <th style="width: 10%">Mã kho</th>
                           <th style="width: 10%">Tên kho</th>
                           <th style="width: 10%">Tồn kho</th>
                           <th style="width: 10%">Diễn giải</th>
						   <th style="width: 10%">Tình trạng</th>
                           <th style="width: 10%" class="text-right">Chức năng</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							$i = 1;
							foreach($warehouses as $row)
                           {
                           ?>
                        <tr>
                           <td>
                              <?php echo $i;?>
                           </td>
                           <td>
                              <?php echo $row->warehouse_code;?>
                           </td>
                           <td>
						   <?php echo $row->warehouse_name;?>
						   </td>
						   <td>
						   <?php echo $row->total_warehouse;?>
						   </td>
							<td>
						   <?php echo $row->warehouse_description;?>
						   </td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
								   <button class="btn btn-sm btn-success" >Xem</button>
								   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									    <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->whid;?>" data-status="<?php echo $row->warehouse_status;?>"><?php echo ($row->warehouse_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
										<a class="dropdown-item btn-sm-modal" href="#" data-method="update" data-id='<?php echo $row->whid;?>' data-code='<?php echo $row->warehouse_code;?>' data-parent="<?php echo $row->warehouse_parent;?>" data-name="<?php echo $row->warehouse_name;?>" data-description="<?php echo $row->warehouse_description;?>" data-branch-id="<?php echo $row->warehouse_branch_id;?>">Sửa</a>
										<a class="dropdown-item btn-action" href="#" data-method="delete" data-id = '<?php echo $row->whid;?>' data-status="<?php echo $row->warehouse_status;?>">Xóa</a>
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