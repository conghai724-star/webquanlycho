<?php include "header.php";?>
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
			"spend_collecte_code": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 1
			},
			"spend_collecte_name":{
				required: true
			},
			"spend_collecte_parent":{
				required: true
			},
			"spend_collecte_type":{
				required: true
			}
			
			
		},
		messages:{
				spend_collecte_code: {
					required: "Vui lòng nhập mã Thu/Chi",
					minlength: "Mã Thu/Chi phải vượt quá 1 ký tự",
					maxlength: "Mã Thu/Chi phải ngắn hơn 15 ký tự"
				},
				spend_collecte_name: "Vui lòng nhập tên Thu/Chi",
				spend_collecte_parent: "Vui lòng chọn Thu/Chi",
				spend_collecte_type: "Vui lòng chọn loại Thu/Chi"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var spend_collecte_code = $("#spend_collecte_code").val(); 
			var spend_collecte_name = $("#spend_collecte_name").val();
			var spend_collecte_description = $("#spend_collecte_description").val();
			var spend_collecte_parent = $("#spend_collecte_parent").val();
			var spend_collecte_type = $("#spend_collecte_type:checked").val();
			var spend_collecte_active = $("#spend_collecte_active:checked").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_spend_collectes",
				data: {
					"method" : method,
					"id" : id,
					"spend_collecte_code": spend_collecte_code,
					"spend_collecte_name": spend_collecte_name,
					"spend_collecte_description": spend_collecte_description,
					"spend_collecte_parent": spend_collecte_parent,
					"spend_collecte_type": spend_collecte_type,
					"spend_collecte_active": spend_collecte_active
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
  
	
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var spend_collecte_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_spend_collectes",
			data: {
				"id": id,
				"spend_collecte_status": spend_collecte_status,
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
	$("#table-form-data").on('click', '.btn-sm-modal', function(e) {
   		var btn = $(this);
   		var method = btn.attr("data-method");
   		var id = btn.attr("data-id");
		var spend_collecte_code = btn.attr("data-code");
		var spend_collecte_name = btn.attr("data-name");
		var spend_collecte_description = btn.attr("data-description");
		var spend_collecte_parent = btn.attr("data-parent");
		function isSelected(){
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL?>/api/cpbSpendcollecte",
				data: {
					'id': id,
					'method': method,
					'spend_collecte_parent': spend_collecte_parent
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						$('#spend_collecte_parent').html(data.data);
						
					}else{
						alert(data.message);
					}
				}
			});
		}
		switch(method) {
		  case "update":
			isSelected()
			//Update
   			$("#spend_collecte_code").val(spend_collecte_code);
   			$("#data_id").val(id);
			$("#form_method").val("update");
			$("#spend_collecte_name").val(spend_collecte_name);
			$("#spend_collecte_description").val(spend_collecte_description);
			$("#spend_collecte_parent").val(spend_collecte_parent);
			$("#spend_collecte_type").val(spend_collecte_type).prop("checked",true);
			$("#spend_collecte_active").val(spend_collecte_type);
			$("#btn_submit_modal").html("Cập nhật");
			$("#form_add_title").html("Sửa Thu/Chi");
   			$("#form-modal").modal("show");
			break;
		  case "duplicate":
				isSelected()
				$("#spend_collecte_code").val(spend_collecte_code);
				$("#data_id").val(id);
				$("#form_method").val("duplicate");
				$("#spend_collecte_name").val(spend_collecte_name);
				$("#spend_collecte_description").val(spend_collecte_description);
				$("#spend_collecte_parent").val(spend_collecte_parent);
				$("#btn_submit_modal").html("Nhân bản");
				$("#form_add_title").html("Nhân bản Thu/Chi");
				$("#form-modal").modal("show");
			break;
			case "new":
				isSelected()
				$("#spend_collecte_code").val('');
				$("#data_id").val('');
				$("#form_method").val("new");
				$("#spend_collecte_name").val('');
				$("#spend_collecte_description").val('');
				$("#spend_collecte_parent").val('');
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm Thu/Chi");
				$("#form-modal").modal("show");
			break;
		  default:
			alert("Không tìm thấy tác vụ thực hiện!");
		}
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
            <h5 class="modal-title" id="form_add_title">Thêm Thu/Chi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
			<div class="row">
				<div class="col-6">
					<div class="form-group">
					  <label>Mã Thu/Chi<span class="text-danger">*</span></label>
					  <input class="form-control" name="spend_collecte_code" required type="text" id="spend_collecte_code">
				   </div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<label>Loại:</label>
							<div class = 'form-control' style = 'border:none;' >
								 <input type="radio" name="spend_collecte_type" value="1" id = 'spend_collecte_type'>
								 <label for="spend_collecte_type">Mục thu</label>
								 &nbsp; &nbsp; &nbsp;
								 <input type="radio"  name="spend_collecte_type" value="2" id = 'spend_collecte_type'>
								 <label for="spend_collecte_type">Mục chi</label>
						
							</div>
				   </div>
				</div>
			</div>
               <div class="form-group">
                  <label>Tên Thu/Chi<span class="text-danger">*</span></label>
                  <input class="form-control" name="spend_collecte_name" type="text" id="spend_collecte_name">
               </div>
							   
			   <div class="form-unit">
                  <label>Thuộc <span class="text-danger">*</span></label>
                  <select class="select select2" id="spend_collecte_parent" name="spend_collecte_parent">
					
					
				  </select>
               </div>
				<div class="form-unit">
                  <label>Diễn giải <span class="text-danger">*</span></label>
                  <textarea class="form-control" id = "spend_collecte_description" name="spend_collecte_description"></textarea>
               </div>
			   <div class="form-unit">
                  <input type="checkbox" id="spend_collecte_active" name="spend_collecte_active" value="1" />
				  <label>Là khoản phát sinh định kỳ hàng tháng <span class="text-danger"></span></label>
                  
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
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal"  data-method="new"><i class="fas fa-plus"></i> Thêm Thu/Chi</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã Thu/Chi</th>
                           <th style="width: 10%">Tên Thu/Chi</th>
						    <th style="width: 10%">Loại</th>
							<th style="width: 10%">Phát sinh định kỳ</th>
						   <th style="width: 10%">Diễn giải</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($spend_collectes as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->spend_collecte_code;?>
                           </td>
                           <td>
                              <?php echo $row->spend_collecte_name;?>
                           </td>
						   <td>
                              <?php echo ($row->spend_collecte_type == 1) ? "Mục thu" : "Mục chi";?>
                           </td>
						   <td class="text-center">
								<input type="checkbox" disabled  <?php echo ($row->spend_collecte_active == 0) ?"" : "checked";?>>
                             
                           </td>
						    <td>
                              <?php echo $row->spend_collecte_description;?>
                           </td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->spend_collecte_code;?>" data-name="<?php echo $row->spend_collecte_name;?>" data-description="<?php echo $row->spend_collecte_description;?>" data-id="<?php echo $row->scid;?>" data-parent="<?php echo $row->spend_collecte_parent;?>" data-type="<?php echo $row->spend_collecte_type?>" data-active="<?php echo $row->spend_collecte_active?>" >Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								 <a href="javascript:void(0);" class="dropdown-item btn-sm-modal" data-method="duplicate" data-code="<?php echo $row->spend_collecte_code;?>" data-name="<?php echo $row->spend_collecte_name;?>" data-description="<?php echo $row->spend_collecte_description;?>" data-id="<?php echo $row->scid;?>" data-parent="<?php echo $row->spend_collecte_parent;?>" data-type="<?php echo $row->spend_collecte_type?>" data-active="<?php echo $row->spend_collecte_active?>" >Nhân bản</a>                        
								  <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->scid;?>" data-status="<?php echo $row->spend_collecte_status;?>"><?php echo ($row->spend_collecte_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->scid;?>">Xóa</a>
									 
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