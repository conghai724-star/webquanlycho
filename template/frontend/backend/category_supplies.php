<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[A-Z,0-9 '']+$/);

    }, "Vui lòng nhập ký tự in hoa!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"supplie_code": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 1
			},
			"supplie_name":{
				required: true
			}
			
			
		},
		messages:{
				supplie_code: {
					required: "Vui lòng nhập mã nhóm",
					minlength: "Mã nhóm phải vượt quá 1 ký tự",
					maxlength: "Mã nhóm phải ngắn hơn 15 ký tự"
				},
				supplie_name: "Vui lòng nhập tên nhóm"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var supplie_code = $("#supplie_code").val(); 
			var supplie_name = $("#supplie_name").val();
			var supplie_parent = $("#supplie_parent").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_supplies",
				data: {
					"method" : method,
					"id" : id,
					"supplie_code": supplie_code,
					"supplie_name": supplie_name,
					"supplie_parent": supplie_parent
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
		var supplie_code = btn.attr("data-code");
		var supplie_name = btn.attr("data-name");
		var supplie_parent = btn.attr("data-parent");
		switch(method) {
		  case "update":
				$("#supplie_code").val(supplie_code);
				$("#data_id").val(id);
				$("#supplie_name").val(supplie_name);
				$("#form_method").val("update");
				$("#supplie_name").val(supplie_name);
				$("#supplie_parent").val(supplie_parent);
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa nhóm vật tư");
				$("#form-modal").modal("show");
			break;
		  case "duplicate":
				$("#supplie_code").val(supplie_code);
				$("#data_id").val(id);
				$("#supplie_name").val(supplie_name);
				$("#form_method").val("duplicate");
				$("#supplie_name").val(supplie_name);
				$("#supplie_parent").val(supplie_parent);
				$("#btn_submit_modal").html("Nhân bản");
				$("#form_add_title").html("Nhân bản nhóm vật tư");
				$("#form-modal").modal("show");
			break;
			case "new":
				$("#supplie_code").val('');
				$("#data_id").val('');
				$("#supplie_name").val('');
				$("#form_method").val("new");
				$("#supplie_name").val('');
				$("#supplie_parent").val('');
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm nhóm vật tư");
				$("#form-modal").modal("show");
			break;
			
		  default:
			// code block
		}
		
   		
   	});
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var supplie_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_supplies",
			data: {
				"id": id,
				"supplie_status": supplie_status,
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
            <h5 class="modal-title" id="form_add_title">Thêm nhóm vật tư</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Mã nhóm<span class="text-danger">*</span></label>
                  <input class="form-control" name="supplie_code" required type="text" id="supplie_code">
               </div>
               <div class="form-group">
                  <label>Tên nhóm<span class="text-danger">*</span></label>
                  <input class="form-control" name="supplie_name" type="text" id="supplie_name">
               </div> 
               
				<div class="form-group">
					<label>Thuộc nhóm<span class="text-danger">*</span></label>
					<select class="select select2" name="supplie_parent" id="supplie_parent">
						<option value="0"> Trống </option>
						<?php 
				   $i = 1;
				   foreach($supplies as $row)
									   {
									   ?>
						<option value="<?php echo $row->sid;?>"><?php echo $row->supplie_name;?></option>
					<?php
				   }
				   ?>
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
      <div class="col-xl-10 col-md-8" id="table-form-data" >
         <div class="card card-table">
            <div class="card-header">
               <div class="row">
                  <div class="col">
                     <h5 class="card-title"><?php echo $pagetitle;?></h5>
                  </div>
                  <div class="col-auto">
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal"  data-method="new"><i class="fas fa-plus"></i> Thêm nhóm vật tư</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã nhóm</th>
                           <th style="width: 10%">Tên nhóm</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($supplies as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->supplie_code;?>
                           </td>
                           <td>
                              <?php echo $row->supplie_name;?>
                           </td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->supplie_code;?>" data-name="<?php echo $row->supplie_name;?>" data-parent="<?php echo $row->supplie_parent;?>" data-id="<?php echo $row->sid;?>">Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								 
								 <a href="javascript:void(0);" class="dropdown-item btn-sm-modal" data-method="duplicate" data-code="<?php echo $row->supplie_code;?>" data-name="<?php echo $row->supplie_name;?>" data-parent="<?php echo $row->supplie_parent;?>" data-id="<?php echo $row->sid;?>">Nhân bản</a> 
								  <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->sid;?>" data-status="<?php echo $row->supplie_status;?>"><?php echo ($row->supplie_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->sid;?>">Xóa</a>
									 
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