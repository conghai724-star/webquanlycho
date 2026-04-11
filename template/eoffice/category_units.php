<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[A-Z0-9, '']+$/);

    }, "Vui lòng chỉ nhập ký tự hoa!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"unit_code": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 1
			},
			"unit_name":{
				required: true
			}
			
		},
		messages:{
				unit_code: {
					required: "Vui lòng nhập mã đơn vị tính",
					minlength: "Mã đơn vị tính phải vượt quá 1 ký tự",
					maxlength: "Mã đơn vị tính phải ngắn hơn 15 ký tự"
				},
				unit_name: "Vui lòng nhập tên đơn vị tính"
				
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var unit_code = $("#unit_code").val(); 
			var unit_name = $("#unit_name").val();
			var unit_description = $("#unit_description").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_units",
				data: {
					"method" : method,
					"id" : id,
					"unit_code": unit_code,
					"unit_name": unit_name,
					"unit_description": unit_description
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
		var unit_code = btn.attr("data-code");
		var unit_name = btn.attr("data-name");
		var unit_description = btn.attr("data-description");
		switch(method) {
		  case "update":
				//Update
				$("#unit_code").val(unit_code);
				$("#unit_code").prop('readonly', true);
				$("#data_id").val(id);
				$("#unit_name").val(unit_name);
				$("#form_method").val("update");
				$("#unit_description").val(unit_description);
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa đơn vị tính");
				$("#form-modal").modal("show");
			break;
		  case "new":
				$("#unit_code").val('');
				$("#data_id").val('');
				$("#unit_name").val('');
				$("#form_method").val("new");
				$("#unit_description").val('');
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm đơn vị tính");
				$("#form-modal").modal("show");
			break;
		  default:
			alert("Không tìm thấy tác vụ thực hiện!");
		}
   	});
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var unit_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_units",
			data: {
				"id": id,
				"unit_status": unit_status,
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
            <h5 class="modal-title" id="form_add_title">Thêm đơn vị tính</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-unit">
                  <label>Mã đơn vị tính<span class="text-danger">*</span></label>
                  <input class="form-control" name="unit_code" required type="text" id="unit_code">
               </div>
               <div class="form-unit">
                  <label>Tên đơn vị tính<span class="text-danger">*</span></label>
                  <input class="form-control" name="unit_name" type="text" id="unit_name">
               </div>
               <div class="form-unit">
                  <label>Diễn giải <span class="text-danger">*</span></label>
                  <textarea class="form-control" id = "unit_description" name="unit_description"></textarea>
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
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal"  data-method="new"><i class="fas fa-plus"></i> Thêm đơn vị tính</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã đơn vị tính</th>
                           <th style="width: 10%">Tên đơn vị tính</th>
                           <th style="">Diễn giải</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($units as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->unit_code;?>
                           </td>
                           <td>
                              <?php echo $row->unit_name;?>
                           </td>
                           <td><?php echo $row->unit_description;?></td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->unit_code;?>" data-id="<?php echo $row->unid;?>" data-name="<?php echo $row->unit_name;?>" data-description="<?php echo $row->unit_description;?>"> Sửa</a>
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								 <a href="#" class="dropdown-item btn-action" data-method="active" data-status="<?php echo $row->unit_status;?>" data-id="<?php echo $row->unid;?>"><?php echo ($row->unit_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->unid;?>">Xóa</a>
									 
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