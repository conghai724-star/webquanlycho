<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
 $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9]+$/);

    }, "Vui lòng chỉ nhập số!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"account_number": {
				required: true,
				alpha: true,
				maxlength: 10,
				minlength: 1
			},
			"account_name":{
				required: true
			},
			"account_name_en": {
				required:true
			},
			"account_type": {
				required:true
			},
			"account_parent": {
				required:true
			}
			
		},
		messages:{
				account_number: {
					required: "Vui lòng nhập số tài khoản",
					minlength: "Số tài khoản phải vượt quá 1 ký tự",
					maxlength: "Số tài khoản phải ngắn hơn 10 ký tự"
				},
				account_name: "Vui lòng nhập tên tài khoản-VN",
				account_name_en: "Vui lòng nhập tên tài khoản-EN",
				account_type: "Vui lòng nhập loại tài khoản",
				account_parent: "Vui lòng nhập loại tiền"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var account_number = $("#account_number").val(); 
			var account_name = $("#account_name").val();
			var account_name_en = $("#account_name_en").val();
			var account_type = $("#account_type").val();
			var account_description = $("#account_description").val();
			var account_parent = $("#account_parent").val();
			console.log(method);
			
			$.ajax({
				type: "POST",
					url: "<?php echo XC_URL;?>/api/category_accounts",
				
				data: {
					"method" : method,
					"aid" : id,
					"account_number": account_number,
					"account_name": account_name,
					"account_name_en": account_name_en,
					"account_type": account_type,
					"account_description": account_description,
					"account_parent": account_parent
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
   		var aid = btn.attr("data-id");
		var account_number = btn.attr("data-number");
		var account_name = btn.attr("data-name");
		var account_name_en = btn.attr("data-name-en");
		var account_description = btn.attr("data-description");
		var account_type = btn.attr("data-type");
		var account_parent = btn.attr("data-parent");
		switch(method) {
		  case "update":
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/cpbaccounts",
				data:{
					"id": aid,
					"method": method
					
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						//console.log(data.data);
						$("#account_parent").html(data.data);
					}
						
				}
			});
			$("#account_number").val(account_number);
   			$("#data_id").val(aid);
   			$("#account_name").val(account_name);
			$("#form_method").val("update");
			$("#account_name_en").val(account_name_en);
			$("#account_description").val(account_description);
			$("#account_type option:selected").val(account_type);
			
			$("#account_parent").val(account_parent);
			$("#account_number").prop("readonly",true);
			$("#btn_submit_modal").html("Cập nhật");
			$("#form_add_title").html("Sửa tài khoản");
   			$("#form-modal").modal("show");
			break;
		  case 'new':
			  $.ajax({
					type: "POST",
					url: "<?php echo XC_URL;?>/api/cpbaccounts",
					data:{
						"id": aid,
						"method": method
						
					},
					dataType: 'json',
					success: function(data){
						if(data.status == 200){
							//console.log(data.data);
							$("#account_parent").html(data.data);
						}
							
					}
				});
			$("#account_number").val('');
   			$("#account_name").val('');
			$("#form_method").val("new");
			$("#account_name_en").val('');
			$("#account_description").val('');
			$("#account_type").val('');
			$("#account_parent").val('');
			$("#account_number").prop("readonly",false);
			$("#btn_submit_modal").html("Thêm");
			$("#form_add_title").html("Thêm tài khoản");
   			$("#form-modal").modal("show");
			break;
		  default:
			// code block
		}
	});
   		
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var aid = $(this).attr("data-id");
		var account_status = $(this).attr("data-status");
		var method = $(this).attr('data-method');
		$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL;?>/api/actionaccount",
				"data": {
					'aid': aid,
					'method': method,
					'account_status': account_status
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: data.message,
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ location.reload();     },2000);
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
	 $("#table-form-data").on('click', '.btn-delete', function(e) {
		var aid = $(this).attr("data-id");
		var account_status = $(this).attr("data-status");
		$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL;?>/api/activeaccount",
				"data": {
					'aid': aid,
					'account_status': account_status
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						
						setTimeout(function(){ location.reload();     });
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
            <h5 class="modal-title" id="form_add_title">Thêm tài khoản</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
				<div class="form-group">
					<label>Số tài khoản<span class="text-danger">*</span></label>
					<input class="form-control" name="account_number" required type="text" id="account_number">
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Loại tài khoản<span class="text-danger">*</span></label>
							<select class="select select2 is_selected" name="account_type" id="account_type">
								<option  disabled value="0" selected="selected">Chọn loại</option>
								<option value="1">Dư nợ</option>
								<option value="2">Dư có</option>
								<option value="3">Lưỡng tính</option>
							</select>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label>Tài khoản<span class="text-danger">*</span></label>
							<select class="select select2 is_selected" name="account_parent" id="account_parent">
							
							
							</select>
						</div>
					</div>
				</div>
               
               <div class="form-group">
                  <label>Tên tài khoản/VN<span class="text-danger">*</span></label>
                  <input class="form-control" name="account_name" type="text" id="account_name">
               </div>
			   <div class="form-group">
                  <label>Tên tài khoản/EN<span class="text-danger">*</span></label>
                  <input class="form-control" name="account_name_en" type="text" id="account_name_en">
               </div>
               
               <div class="form-group">
                  <label>Diễn giải<span class="text-danger">*</span></label>
                  <textarea class="form-control" id = "account_description" name="account_description"></textarea>
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
						<a href="javascript:void(0);" class="btn btn-primary btn-sm btn-sm-modal" id="btnadd" data-method="new"><i class="fas fa-plus"></i> Thêm tài khoản</a> 
					</div>
				</div>
			</div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">Số TK</th>
                           <th style="width: 20%">Tên tài khoản</th>
                           <th style="width: 10%">Tính chất</th>
                           <th style="width: 20%">Tên tiếng Anh</th>
                           <th style="width: 20%">Diễn giải</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach($accounts as $account)
                           {
                           ?>
                        <tr>
                           <td>
                              <?php echo $account->account_number;?>
                           </td>
                           <td>
                              <?php echo $account->account_name;?>
                           </td>
                           <td><?php 
						   if($account->account_type == 1)
						   {
							   echo "Dư nợ";
						   }
						   elseif($account->account_type == 2)
						   {
							   echo "Dư có";
						   }
						   else
						   {
							   echo "Lưỡng tính";
						   }
						   ?></td>
                           <td  class="text-right"><?php echo $account->account_name_en;?></td>
                           <td><?php echo $account->account_description;?></td>
                           <td><span class="badge badge-pill bg-<?php echo $account->status_class;?>-light"><?php echo $account->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							   <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-number="<?php echo $account->account_number;?>" data-id="<?php echo $account->aid;?>" data-name="<?php echo $account->account_name;?>" data-description="<?php echo $account->account_description;?>" data-name-en="<?php echo $account->account_name_en;?>" data-type="<?php echo $account->account_type;?>" data-parent="<?php echo $account->account_parent;?>" > Sửa</a>
								   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									<a class="dropdown-item btn-action" href="#" data-id = "<?php echo $account->aid;?>" data-status="<?php echo $account->account_status;?>" data-method="active"><?php echo ($account->account_status == 1) ? "Ngừng hoạt động" : "Kích hoạt";?></a>
									  <a class="dropdown-item btn-action" href="#" data-id = "<?php echo $account->aid;?>" data-method="delete">Xóa</a>
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