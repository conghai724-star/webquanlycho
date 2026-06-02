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
			"expense_code": {
				required: true,
				alpha: true,
				maxlength: 15,
				minlength: 1
			},
			"expense_name":{
				required: true
			}
			
			
			
		},
		messages:{
				expense_code: {
					required: "Vui lòng nhập mã khoản mục chi phí",
					minlength: "Mã khoản mục chi phí phải vượt quá 1 ký tự",
					maxlength: "Mã khoản mục chi phí phải ngắn hơn 15 ký tự"
				},
				expense_name: "Vui lòng nhập tên khoản mục chi phí"
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var expense_code = $("#expense_code").val(); 
			var expense_name = $("#expense_name").val();
			var expense_parent = $("#expense_parent").val();
			var expense_description = $("#expense_description").val();
			
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_expenseitems",
				data: {
					"method" : method,
					"id" : id,
					"expense_code": expense_code,
					"expense_name": expense_name,
					"expense_parent": expense_parent,
					"expense_description": expense_description
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
		var expense_code = btn.attr("data-code");
		var expense_name = btn.attr("data-name");
		var expense_parent = btn.attr("data-parent");
		var expense_description = btn.attr("data-description");
		console.log(expense_parent);
		function isSelected(){
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL?>/api/cpbExpenseitem",
				data: {
					'id': id,
					'method': method,
					'expense_parent': expense_parent
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						$('#expense_parent').html(data.data);
						
					}else{
						alert(data.message);
					}
				}
			});
		}
		switch(method) {
		  case "update":
				isSelected();
				$("#expense_code").val(expense_code);
				$("#data_id").val(id);
				$("#expense_name").val(expense_name);
				$("#form_method").val("update");
				$("#expense_description").val(expense_description);
				$("#expense_parent").val(expense_parent);
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa khoản mục chi phí ");
				
				$("#form-modal").modal("show");
			break;
		  case 'new':
				isSelected();
				$("#expense_code").val('');
				$("#data_id").val('');
				$("#expense_name").val('');
				$("#form_method").val("new");
				$("#expense_description").val('');
				$("#expense_parent").val('');
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm khoản mục chi phí ");
				
				$("#form-modal").modal("show");
			break;
		  default:
			alert("Không tìm thấy tác vụ thực hiện!");
		}
   		
   	});
	
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var expense_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_expenseitems",
			data: {
				"id": id,
				"expense_status": expense_status,
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
            <h5 class="modal-title" id="form_add_title">Thêm khoản mục chi phí</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Mã khoản mục chi phí<span class="text-danger">*</span></label>
                  <input class="form-control" name="expense_code" required type="text" id="expense_code">
               </div>
               <div class="form-group">
                  <label>Tên khoản mục chi phí<span class="text-danger">*</span></label>
                  <input class="form-control" name="expense_name" type="text" id="expense_name">
               </div> 
               
				<div class="form-group">
					<label>Thuộc nhóm<span class="text-danger">*</span></label>
					<select class="select select2" name="expense_parent" id="expense_parent">
						
					</select>
				</div>
				<div class="form-group">
                  <label>Diễn giải<span class="text-danger">*</span></label>
                  <textarea rows="3" cols="5" class="form-control"  id='expense_description' ></textarea>
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
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal" data-method="new"><i class="fas fa-plus"></i> Thêm khoản mục chi phí</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã khoản mục chi phí</th>
                           <th style="width: 10%">Tên khoản mục chi phí</th>
						    <th style="width: 10%">Diễn giải</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
					 
                        <?php 
						$i=1;
                           foreach($expenseitems as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->expense_code;?>
                           </td>
                           <td>
                              <?php echo $row->expense_name;?>
                           </td>
						   <td>
                              <?php echo $row->expense_description;?>
                           </td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->expense_code;?>" data-name="<?php echo $row->expense_name;?>" data-description="<?php echo $row->expense_description;?>" data-id="<?php echo $row->exid;?>" data-parent="<?php echo $row->expense_parent;?>">Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								  <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->exid;?>" data-status="<?php echo $row->expense_status;?>"><?php echo ($row->expense_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->exid;?>">Xóa</a>
									 
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