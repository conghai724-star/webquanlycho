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
			"currency_code": {
				required: true,
				
				alpha: true,
				maxlength: 15,
				minlength: 1
			},
			"currency_name":{
				required: true
			},
			"currency_rate":{
				required: true
			},
			"currency_type":{
				required: true
			}
			
			
		},
		messages:{
				currency_code: {
					required: "Vui lòng nhập mã loại tiền",
					minlength: "Mã loại tiền phải vượt quá 1 ký tự",
					maxlength: "Mã loại tiền phải ngắn hơn 15 ký tự"
				},
				currency_name: "Vui lòng nhập tên loại tiền"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var currency_code = $("#currency_code").val(); 
			var currency_name = $("#currency_name").val();
			var currency_rate = $("#currency_rate").val();
			var currency_type = $("#currency_type").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_currencies",
				data: {
					"method" : method,
					"id" : id,
					"currency_code": currency_code,
					"currency_name": currency_name,
					"currency_rate": currency_rate,
					"currency_type": currency_type
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
   	$("#table-form-data").on('click', '.btn-sm-modal', function(e) {
   		var btn = $(this);
   		var method = btn.attr("data-method");
   		var id = btn.attr("data-id");
		var currency_code = btn.attr("data-code");
		var currency_name = btn.attr("data-name");
		var currency_rate = btn.attr("data-rate");
		var currency_type = btn.attr("data-type");
		function isSelected(){
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL?>/api/cpbCurrency",
				data: {
					'id': id,
					'method': method,
					'currency_type': currency_type
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						$('#currency_type').html(data.data);
						
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
				$("#currency_code").val(currency_code);
				$("#data_id").val(id);
				$("#form_method").val("update");
				$("#currency_name").val(currency_name);
				$("#currency_rate").val(currency_rate);
				$("#currency_type").val(currency_type);
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa loại tiền");
				$("#form-modal").modal("show");
			break;
		  case "new":
			isSelected();
				$("#currency_code").val('');
				$("#data_id").val('');
				$("#form_method").val("new");
				$("#currency_name").val('');
				$("#currency_rate").val('');
				$("#currency_type").val('');
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm loại tiền");
				$("#form-modal").modal("show");
			break;
		  default:
			alert("Không tìm thấy tác vụ thực hiện!");
		}
   		
   	});
	
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var currency_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_currencies",
			data: {
				"id": id,
				"currency_status": currency_status,
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
	
   });
   
</script>
<style>
	label.error{
		color:red;
	}
</style>
<div id="form-modal" class="modal custom-modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="form_add_title">Thêm loại tiền</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Mã loại tiền<span class="text-danger">*</span></label>
                  <input class="form-control" name="currency_code" required type="text" id="currency_code">
               </div>
               <div class="form-group">
                  <label>Tên loại tiền<span class="text-danger">*</span></label>
                  <input class="form-control" name="currency_name" type="text" id="currency_name">
               </div> 
               
			   <div class="form-group">
                  <label>Tỉ giá chuyển đổi<span class="text-danger">*</span></label>
                  <input class="form-control" name="currency_rate" type="text" id="currency_rate">
               </div> 
               
				<div class="form-group">
					<label>Thuộc loại<span class="text-danger">*</span></label>
					<select class="select select2" name="currency_type" id="currency_type">
					
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
   <div class="row">
      <div class="col-xl-2 col-md-4">
         <?php include_once "category-sidebar.php";?>
      </div>
      <div class="col-xl-10 col-md-8" id="table-form-data">
         <div class="card card-table">
            <div class="card-header">
               <div class="row">
                  <div class="col">
                     <h5 class="card-title"><?php echo $pagetitle;?></h5>
                  </div>
                  <div class="col-auto">
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal"  data-method="new"><i class="fas fa-plus"></i> Thêm loại tiền</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã tiền</th>
                           <th style="width: 10%">Tên tiền</th>
						   <th style="width: 10%">Tỉ giá quy đổi</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($currencies as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->currency_code;?>
                           </td>
                           <td>
                              <?php echo $row->currency_name;?>
                           </td>
						    <td>
                              <?php echo $row->currency_rate;?>
                           </td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->currency_code;?>" data-name="<?php echo $row->currency_name;?>" data-rate="<?php echo $row->currency_rate;?>" data-type="<?php echo $row->currency_type;?>" data-id="<?php echo $row->cuid;?>">Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								  <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->cuid;?>" data-status="<?php echo $row->currency_status;?>"><?php echo ($row->currency_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->cuid;?>">Xóa</a>
									 
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