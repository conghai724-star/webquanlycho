<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9 '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"ba_account": {
				required: true,
				alpha: true,
				maxlength: 20,
				minlength: 7
			},
			"ba_branch":{
				required: true
			},
			"ba_holder":{
				required: true
			},
			"bank_id":{
				required: true
			}
			
		},
		messages:{
				ba_account: {
					required: "Vui lòng nhập số tài khoản",
					minlength: "Mã nhóm phải vượt quá 7 ký tự",
					maxlength: "Mã nhóm phải ngắn hơn 20 ký tự"
				},
				ba_branch: "Vui lòng nhập địa chỉ chi nhánh",
				ba_holder: "Vui lòng nhập chủ tài khoản",
				bank_id: "Vui lòng chọn ngân hàng"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var ba_account = $("#ba_account").val(); 
			var ba_branch = $("#ba_branch").val();
			var bank_id = $("#bank_id").val();
			var ba_holder = $("#ba_holder").val();
			var ba_description = $("#ba_description").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_bankaccounts",
				data: {
					"method" : method,
					"id" : id,
					"ba_account": ba_account,
					"ba_branch": ba_branch,
					"bank_id": bank_id,
					"ba_holder":ba_holder,
					"ba_description": ba_description
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
		var ba_account = btn.attr("data-account");
		var ba_holder = btn.attr("data-name");
		var ba_branch = btn.attr("data-branch");
		var bank_id = btn.attr("data-bank-id");
		var ba_description = btn.attr("data-description");
		function isSelected(){
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL?>/api/cmpAccountbank",
			data:{
				'id': id,
				'method': method,
				'bankid': bank_id
			},
			dataType: 'json',
			success: function(data){
				if(data.status == 200){
					$("#bank_id").html(data.data);
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
   			$("#ba_account").val(ba_account);
   			$("#data_id").val(id);
   			$("#ba_holder").val(ba_holder);
			$("#form_method").val("update");
			$("#ba_branch").val(ba_branch);
			$("#ba_description").val(ba_description);
			$("#btn_submit_modal").html("Cập nhật");
			$("#form_add_title").html("Sửa tài khoản ngân hàng");
   			$("#form-modal").modal("show");
			break;
		  case "duplicate":
			isSelected();
			$("#ba_account").val(ba_account);
			$("#data_id").val(id);
			$("#ba_holder").val(ba_holder);
			$("#form_method").val("duplicate");
			$("#ba_description").val(ba_description);
			$("#ba_branch").val(ba_branch);
			$("#bank_id").val(bank_id);
			$("#btn_submit_modal").html("Nhân bản");
			$("#form_add_title").html("Nhân bản tài khoản ngân hàng");
			$("#form-modal").modal("show");
			break;
			case "new":
			isSelected();
			$("#ba_account").val('');
			$("#data_id").val('');
			$("#ba_holder").val('');
			$("#form_method").val("new");
			$("#ba_description").val('');
			$("#ba_branch").val('');
			$("#bank_id").val('');
			$("#btn_submit_modal").html("Thêm");
			$("#form_add_title").html("Thêm tài khoản ngân hàng");
			$("#form-modal").modal("show");
			break;
		  default:
			alert("Không tìm thấy tác vụ thực hiện!");
		}
   		
   	});
	
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var ba_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_bankaccounts",
			data: {
				"id": id,
				"ba_status": ba_status,
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
            <h5 class="modal-title" id="form_add_title">Thêm tài khoản ngân hàng</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Số tài khoản<span class="text-danger">*</span></label>
                  <input class="form-control" name="ba_account" required type="text" id="ba_account">
               </div>
               <div class="form-group">
                  <label>Tên ngân hàng<span class="text-danger">*</span></label>
                  <select id="bank_id" name="bank_id" class="select select2">
					
					
				  </select>
               </div>
			   <div class="form-group">
                  <label>Chi nhánh<span class="text-danger">*</span></label>
                  <input class="form-control" name="ba_branch" type="text" id="ba_branch">
               </div>
			   <div class="form-group">
                  <label>Chủ tài khoản<span class="text-danger">*</span></label>
                  <input class="form-control" name="ba_holder" type="text" id="ba_holder">
               </div>
				<div class="form-group">
                  <label>Diễn giải<span class="text-danger">*</span></label>
				  <textarea class="form-control" id="ba_description" name="ba_description"></textarea>
                
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
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal" data-method="new"><i class="fas fa-plus">&nbsp;</i>Thêm tài khoản ngân hàng</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
						   <th style="width: 10%">Số tài khoản</th>
						   <th style="width: 10%">Tên ngân hàng</th>
                           <th style="width: 10%">Tên chi nhánh ngân hàng</th>
                           <th style="width: 10%">Chủ tài khoản</th>
						   <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($bankaccounts as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
						    <td>
                              <?php echo $row->ba_account;?>
                           </td>
                           <td>
                              <?php echo $row->bank_name;?>
                           </td>
						  
                           <td>
                              <?php echo $row->ba_branch;?>
                           </td>
						   <td>
                              <?php echo $row->ba_holder;?>
                           </td>
						   
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-account="<?php echo $row->ba_account;?>" data-name="<?php echo $row->ba_holder;?>" data-id="<?php echo $row->baid;?>" data-branch="<?php echo $row->ba_branch;?>"  data-bank-id="<?php echo $row->bank_id;?>" data-description="<?php echo $row->ba_description;?>">Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								  <a href="javascript:void(0);" class="dropdown-item btn-sm-modal" data-method="duplicate" data-account="<?php echo $row->ba_account;?>" data-name="<?php echo $row->ba_holder;?>" data-id="<?php echo $row->baid;?>" data-branch="<?php echo $row->ba_branch;?>"  data-bank-id="<?php echo $row->bank_id;?>" data-description="<?php echo $row->ba_description;?>">Nhân bản</a>
								  <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->baid;?>" data-status="<?php echo $row->ba_status;?>"><?php echo ($row->ba_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->baid;?>">Xóa</a>
									 
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