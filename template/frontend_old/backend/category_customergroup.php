<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[A-Z, '']+$/);

    }, "Vui lòng chỉ nhập ký tự hoa!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"group_code": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 4
			},
			"group_name":{
				required: true
			},
			"group_color": {
				required:true
			}
			
		},
		messages:{
				group_code: {
					required: "Vui lòng nhập mã nhóm",
					minlength: "Mã nhóm phải vượt quá 4 ký tự",
					maxlength: "Mã nhóm phải ngắn hơn 15 ký tự"
				},
				group_name: "Vui lòng nhập tên nhóm",
				group_color: "Vui lòng nhập màu nhóm"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var group_code = $("#group_code").val(); 
			var group_name = $("#group_name").val();
			var group_color = $("#group_color").val();
			var group_description = $("#group_description").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/customergroup",
				data: {
					"method" : method,
					"gid" : id,
					"group_code": group_code,
					"group_name": group_name,
					"group_color": group_color,
					"group_description": group_description
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
   		var cgid = btn.attr("data-id");
		var group_code = btn.attr("data-code");
		var group_name = btn.attr("data-name");
		var group_color = btn.attr("data-color");
		var group_description = btn.attr("data-description");
		switch(method) {
		  case "update":
			$("#group_code").val(group_code);
   			$("#data_id").val(cgid);
   			$("#group_name").val(group_name);
			$("#form_method").val("update");
			$("#group_color").val(group_color);
			$("#group_description").val(group_description);
			$("#group_code").prop("readonly",true);
			$("#btn_submit_modal").html("Cập nhật");
			$("#form_add_title").html("Sửa nhóm Khách hàng/NCC");
   			$("#form-modal").modal("show");
			break;
		  case "new":
			$("#group_code").val('');
   			$("#data_id").val('');
   			$("#group_name").val('');
			$("#form_method").val("new");
			$("#group_color").val('');
			$("#group_description").val('');
			$("#group_code").prop("readonly",false);
			$("#btn_submit_modal").html("Thêm");
			$("#form_add_title").html("Thêm nhóm Khách hàng/NCC");
   			$("#form-modal").modal("show");
			break;
		  default:
			// code block
		}
   	});
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var gid = $(this).attr('data-id');
		var group_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/actioncustomergroup",
			data: {
				"gid": gid,
				"group_status": group_status,
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
            <h5 class="modal-title" id="form_add_title">Thêm nhóm khách hàng/NCC</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Mã nhóm<span class="text-danger">*</span></label>
                  <input class="form-control" name="group_code" required type="text" id="group_code">
               </div>
               <div class="form-group">
                  <label>Tên nhóm<span class="text-danger">*</span></label>
                  <input class="form-control" name="group_name" type="text" id="group_name">
               </div>
               <div class="form-group">
                  <label>Màu nhóm<span class="text-danger">*</span></label>
                  <input class="form-control" type="text" name="group_color" id="group_color">
               </div>
               <div class="form-group">
                  <label>Diễn giải <span class="text-danger">*</span></label>
                  <textarea class="form-control" id = "group_description" name="group_description"></textarea>
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
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal" data-bs-toggle="modal"  data-method="new"><i class="fas fa-plus"></i> Thêm nhóm khách hàng/NCC</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã nhóm</th>
                           <th style="width: 10%">Tên nhóm</th>
                           <th style="">Diễn giải</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($groups as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->group_code;?>
                           </td>
                           <td>
                              <?php echo $row->group_name;?>
                           </td>
                           <td><?php echo $row->group_description;?></td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->group_code;?>" data-id="<?php echo $row->gid;?>" data-name="<?php echo $row->group_name;?>" data-description="<?php echo $row->group_description;?>" data-color="<?php echo $row->group_color;?>" > Sửa</a>
                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								 <a href="#" class="dropdown-item btn-action" data-method="active" data-status="<?php echo $row->group_status;?>" data-id="<?php echo $row->gid;?>"><?php echo ($row->group_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->gid;?>">Xóa</a>
									 
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