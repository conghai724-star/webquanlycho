<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	  
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"department_name": {
				  required: true
			}
		},
		messages: {
				department_name: {
					required: "Vui lòng nhập tên phòng ban"
				}
			}
		
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val(); 
			var department_name = $("#department_name").val();
			console.log(department_name);
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_departments",
				data: {
					"method" : method,
					"id" : id,
					"department_name": department_name
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
		var department_name = btn.attr("data-name");
		switch(method) {
		  case "update":
				$("#data_id").val(id);
				$("#department_name").val(department_name);
				$("#form_method").val("update");
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa phòng ban");
				$("#form-modal").modal("show");
			break;
		  case 'new':
				$("#data_id").val('');
				$("#department_name").val('');
				$("#form_method").val("new");
				$("#btn_submit_modal").html("Thêm");
				$("#form_add_title").html("Thêm phòng ban");
				$("#form-modal").modal("show");
			break;
		  default:
		}
   	});
	
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_departments",
			data: {
				"id": id,
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
            <h5 class="modal-title" id="form_add_title">Thêm phòng ban</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Tên phòng ban<span class="text-danger">*</span></label>
                  <input class="form-control" name="department_name" required type="text" id="department_name">
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
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal" data-method="new"><i class="fas fa-plus"></i> Thêm phòng ban</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Tên phòng ban</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($departments as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->depart_name;?>
                           </td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-name="<?php echo $row->depart_name;?>"  data-id="<?php echo $row->id;?>">Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
                                    <a class="dropdown-item btn-action" data-method="delete"  href="#"  data-id="<?php echo $row->id;?>">Xóa</a>
									 
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