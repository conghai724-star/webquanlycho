<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[A-Z0-9, '']+$/);

    }, "Vui lòng nhập ký tự hoa!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"cat_product_code": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 1
			},
			"cat_product_name":{
				required: true
			},
			"cat_product_parent":{
				required: true
			},
			"cat_product_unit":{
				required: true
			}
			
		},
		messages:{
				cat_product_code: {
					required: "Vui lòng nhập mã danh mục sản phẩm",
					minlength: "Mã danh mục sản phẩm phải vượt quá 1 ký tự",
					maxlength: "Mã danh mục sản phẩm phải ngắn hơn 15 ký tự"
				},
				cat_product_name: "Vui lòng nhập tên danh mục sản phẩm",
				cat_product_parent: "Vui lòng chọn danh mục sản phẩm",
				cat_product_unit: "Vui lòng chọn đơn vị tính",
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var cat_product_code = $("#cat_product_code").val(); 
			var cat_product_name = $("#cat_product_name").val();
			var cat_product_description = $("#cat_product_description").val();
			var cat_product_parent = $("#cat_product_parent").val();
			var cat_product_unit = $("#cat_product_unit").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_products",
				data: {
					"method" : method,
					"id" : id,
					"cat_product_code": cat_product_code,
					"cat_product_name": cat_product_name,
					"cat_product_description": cat_product_description,
					"cat_product_parent": cat_product_parent,
					"cat_product_unit": cat_product_unit
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
		var cat_product_code = btn.attr("data-code");
		var cat_product_name = btn.attr("data-name");
		var cat_product_description = btn.attr("data-description");
		var cat_product_parent = btn.attr("data-parent");
		var cat_product_unit = btn.attr("data-unit");
		function isSelected(){
		$.ajax({
				type: "POST",
				url: "<?php echo XC_URL?>/api/cpbCategoryproduct",
				data: {
					'id': id,
					'method': method,
					'cat_product_parent': cat_product_parent,
					'cat_product_unit': cat_product_unit
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						$('#cat_product_parent').html(data.data_category);
						$('#cat_product_unit').html(data.data_unit);
						console.log(data.data);
					}
				}
			});
	}
		switch(method) {
		  case "update":
			//Ajax để hiển thị trường danh mục
			isSelected();
				$("#cat_product_code").val(cat_product_code);
				$("#cat_product_code").prop("readonly", true);
				$("#data_id").val(id);
				$("#form_method").val("update");
				$("#cat_product_name").val(cat_product_name);
				$("#cat_product_description").val(cat_product_description);
				$("#cat_product_parent").val(cat_product_parent);
				$("#cat_product_unit").val(cat_product_unit);
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa danh mục sản phẩm");
				$("#form-modal").modal("show");
				
			break;
		  case "duplicate":
			//duplicate
			isSelected();
				$("#cat_product_code").val(cat_product_code);
				$("#cat_product_code").prop("readonly", false);
				$("#data_id").val(id);
				$("#form_method").val("duplicate");
				$("#cat_product_name").val(cat_product_name);
				$("#cat_product_description").val(cat_product_description);
				$("#cat_product_parent").val(cat_product_parent);
				$("#cat_product_unit").val(cat_product_unit);
				$("#btn_submit_modal").html("Nhân bản");
				$("#form_add_title").html("Nhân bản danh mục sản phẩm");
				$("#form-modal").modal("show");
			break;
			case "new":
			isSelected();
				$("#cat_product_code").val('');
				$("#cat_product_code").prop("readonly", false);
				$("#data_id").val('');
				$("#form_method").val("new");
				$("#cat_product_name").val('');
				$("#cat_product_description").val('');
				$("#cat_product_parent").val('');
				$("#cat_product_unit").val('');
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm danh mục sản phẩm");
				$("#form-modal").modal("show");
			break;
		  default:
			// code block
		}
		
   		
   		
   	});
	
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var cat_product_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_products",
			data: {
				"id": id,
				"cat_product_status": cat_product_status,
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
            <h5 class="modal-title" id="form_add_title">Thêm danh mục sản phẩm</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Mã danh mục sản phẩm<span class="text-danger">*</span></label>
                  <input class="form-control" name="cat_product_code" required type="text" id="cat_product_code">
               </div>
               <div class="form-group">
                  <label>Tên danh mục sản phẩm<span class="text-danger">*</span></label>
                  <input class="form-control" name="cat_product_name" type="text" id="cat_product_name">
               </div> 
			   <div class="form-group">
                  <label>Danh mục <span class="text-danger">*</span></label>
                  <select class="select select2" id="cat_product_parent" name="cat_product_parent">
					
				
				  </select>
               </div>
			   <div class="form-group">
                  <label>Đơn vị tính <span class="text-danger">*</span></label>
                  <select class="select select2" id="cat_product_unit" name="cat_product_unit">
					
				
				  </select>
               </div>
				<div class="form-group">
                  <label>Diễn giải <span class="text-danger">*</span></label>
                  <textarea class="form-control" id = "cat_product_description" name="cat_product_description"></textarea>
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
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal" data-method="new"><i class="fas fa-plus"></i> Thêm danh mục sản phẩm</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã danh mục sản phẩm</th>
                           <th style="width: 10%">Tên danh mục sản phẩm</th>
						   <th style="width: 10%">Đơn vị tính</th>
						   <th style="width: 10%">Diễn giải</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($products as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->cat_product_code;?>
                           </td>
                           <td>
                              <?php echo $row->cat_product_name;?>
                           </td>
						   <td>
                              <?php echo $row->unit_name;?>
                           </td>
						    <td>
                              <?php echo $row->cat_product_description;?>
                           </td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->cat_product_code;?>" data-name="<?php echo $row->cat_product_name;?>" data-description="<?php echo $row->cat_product_description;?>" data-id="<?php echo $row->pid;?>" data-parent="<?php echo $row->cat_product_parent;?>" data-unit="<?php echo $row->cat_product_unit;?>">Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								  <a href="javascript:void(0);" class="dropdown-item btn-sm-modal" data-method="duplicate" data-code="<?php echo $row->cat_product_code;?>" data-name="<?php echo $row->cat_product_name;?>" data-description="<?php echo $row->cat_product_description;?>" data-id="<?php echo $row->pid;?>" data-parent="<?php echo $row->cat_product_parent;?>" data-unit="<?php echo $row->cat_product_unit;?>">Nhân bản</a>  
								  <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->pid;?>" data-status="<?php echo $row->cat_product_status;?>"><?php echo ($row->cat_product_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->pid;?>">Xóa</a>
									 
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