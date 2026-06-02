<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[A-Z '']+$/);

    }, "Vui lòng nhập ký tự in hoa!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"template_type_code": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 1
			},
			"template_type_name":{
				required: true
			}
			
			
			
		},
		messages:{
				template_type_code: {
					required: "Vui lòng nhập mã loại chứng từ",
					minlength: "Mã loại chứng từ phải vượt quá 1 ký tự",
					maxlength: "Mã loại chứng từ phải ngắn hơn 15 ký tự"
				},
				template_type_name: "Vui lòng nhập tên loại chứng từ"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var template_type_code = $("#template_type_code").val(); 
			var template_type_name = $("#template_type_name").val();
			var template_type_debt = $("#template_type_debt").val();
			var template_type_to = $("#template_type_to").val();
			var template_type_description = $("#template_type_description").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_template_types",
				data: {
					"method" : method,
					"id" : id,
					"template_type_code": template_type_code,
					"template_type_name": template_type_name,
					"template_type_debt": template_type_debt,
					"template_type_to": template_type_to,
					"template_type_description": template_type_description,
					
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
		var template_type_code = btn.attr("data-code");
		var template_type_name = btn.attr("data-name");
		var template_type_debt = btn.attr("data-debt");
		var template_type_to = btn.attr("data-to");
		var template_type_description = btn.attr("data-description");
		switch(method) {
			  case 'update':
				$("#form-modal").modal("show");
				$("#template_type_code").val(template_type_code);
				$("#data_id").val(id);
				$("#template_type_name").val(template_type_name);
				$("#form_method").val("update");
				$("#template_type_debt").val(template_type_debt);
				$("#template_type_to").val(template_type_to);
				$("#template_type_description").val(template_type_description);
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa loại chứng từ ");
				break;
		  case 'duplicate':
				//duplicate
				$("#template_type_code").val(template_type_code);
				$("#data_id").val(id);
				$("#template_type_name").val(template_type_name);
				$("#form_method").val("duplicate");
				
				$("#template_type_debt:selected").val(template_type_debt);
				
				$("#template_type_to").val(template_type_to);
				$("#template_type_description").val(template_type_description);
				$("#btn_submit_modal").html("Nhân bản");
				$("#form_add_title").html("Nhân bản loại chứng từ ");
				$("#form-modal").modal("show");
				break;
			case 'new':
				//duplicate
				$("#template_type_code").val('');
				$("#data_id").val('');
				$("#template_type_name").val('');
				$("#form_method").val("new");
				
				$("#template_type_debt:selected").val('');
				
				$("#template_type_to").val('');
				$("#template_type_description").val('');
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm loại chứng từ ");
				$("#form-modal").modal("show");
				break;
		  default:
			alert('Không tìm thấy tác vụ thực hiện!');
		}
   	});
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var template_type_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_template_types",
			data: {
				"id": id,
				"template_type_status": template_type_status,
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
            <h5 class="modal-title" id="form_add_title">Thêm loại chứng từ</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Mã loại chứng từ<span class="text-danger">*</span></label>
                  <input class="form-control" name="template_type_code" required type="text" id="template_type_code">
               </div>
               <div class="form-group">
                  <label>Tên loại chứng từ<span class="text-danger">*</span></label>
                  <input class="form-control" name="template_type_name" type="text" id="template_type_name">
               </div> 
              
				
				
				<div class="form-group">
                  <label>Diễn giải<span class="text-danger">*</span></label>
                  <textarea rows="3" cols="5" class="form-control"  id='template_type_description' ></textarea>
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
      <div class="col-xl-10 col-md-8" id="table-form-data" >
         <div class="card card-table">
            <div class="card-header">
               <div class="row">
                  <div class="col">
                     <h5 class="card-title"><?php echo $pagetitle;?></h5>
                  </div>
                  <div class="col-auto">
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal"  data-method="new"><i class="fas fa-plus"></i> Thêm loại chứng từ</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã loại chứng từ</th>
                           <th style="width: 10%">Tên loại chứng từ</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($templatetypes as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->template_type_code;?>
                           </td>
                           <td>
                              <?php echo $row->template_type_name;?>
                           </td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->template_type_code;?>" data-name="<?php echo $row->template_type_name;?>" data-to="<?php echo $row->template_type_to;?>" data-debt="<?php echo $row->template_type_debt;?>" data-id="<?php echo $row->ttid;?>" data-description="<?php echo $row->template_type_description;?>">Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								 
								<a href="javascript:void(0);" class="dropdown-item btn-sm-modal" data-method="duplicate" data-code="<?php echo $row->template_type_code;?>" data-name="<?php echo $row->template_type_name;?>" data-to="<?php echo $row->template_type_to;?>" data-debt="<?php echo $row->template_type_debt;?>" data-id="<?php echo $row->ttid;?>" data-description="<?php echo $row->template_type_description;?>">Nhân bản</a>  
								  <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->ttid;?>" data-status="<?php echo $row->template_type_status;?>"><?php echo ($row->template_type_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->ttid;?>">Xóa</a>
									 
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