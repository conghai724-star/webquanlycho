<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
   $(document).ready(function(){
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9 '']+$/);

    }, "Vui lòng nhập ký tự số!");
	$("#frm-action").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"policy_code": {
				required: true,
				maxlength: 15,
				minlength: 1
			},
			"policy_title":{
				required: true
				
			},
			"policy_debt_day":{
				required: true,
				alpha: true
			}
			
			
		},
		messages:{
				policy_code: {
					required: "Vui lòng nhập mã điều khoản",
					minlength: "Mã điều khoản phải vượt quá 1 ký tự",
					maxlength: "Mã điều khoản phải ngắn hơn 15 ký tự"
				},
				policy_title: "Vui lòng nhập tên điều khoản",
				policy_debt_day: "Vui lòng nhập số ngày được nợ"
				
			}
	});
   	
   	$("#btn_submit_modal").click(function(){
		 
   		if($("#frm-action").valid())
		{
			var method = $("#form_method").val();
			var id = $("#data_id").val();
			var policy_code = $("#policy_code").val(); 
			var policy_title = $("#policy_title").val();
			var policy_debt_day = $("#policy_debt_day").val();
			var policy_comission = $("#policy_comission").val();
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/category_payment_policies",
				data: {
					"method" : method,
					"id" : id,
					"policy_code": policy_code,
					"policy_title": policy_title,
					"policy_debt_day": policy_debt_day,
					"policy_comission": policy_comission
					
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
		var policy_code = btn.attr("data-code");
		var policy_title = btn.attr("data-title");
		var policy_debt_day = btn.attr("data-debt-day");
		var policy_comission = btn.attr("data-comission");
		//console.log(method);
		switch(method) {
		  case "update":
		  
				//Update
				$("#policy_code").val(policy_code);
				$("#data_id").val(id);
				$("#policy_title").val(policy_title);
				$("#form_method").val("update");
				$("#policy_debt_day").val(policy_debt_day);
				$("#policy_comission").val(policy_comission);
				$("#btn_submit_modal").html("Cập nhật");
				$("#form_add_title").html("Sửa điều khoản thanh toán");
				$("#form-modal").modal("show");
			break;
		  case "duplicate":
			
   			//duplicate
   			$("#policy_code").val(policy_code);
   			$("#data_id").val(id);
   			$("#policy_title").val(policy_title);
			$("#form_method").val("duplicate");
			$("#policy_debt_day").val(policy_debt_day);
			$("#policy_comission").val(policy_comission);
			$("#btn_submit_modal").html("Nhân bản");
			$("#form_add_title").html("Nhân bản điều khoản thanh toán");
   			$("#form-modal").modal("show");
			break;
		 case "new":
		
			//duplicate
			$("#policy_code").val('');
			$("#data_id").val('');
			$("#policy_title").val('');
			$("#form_method").val("new");
			$("#policy_debt_day").val('');
			$("#policy_comission").val('');
			$("#btn_submit_modal").html("Thêm");
			$("#form_add_title").html("Thêm điều khoản thanh toán");
			$("#form-modal").modal("show");
			break;
		  default:
			alert("Không tìm thấy tác vụ thực hiện!");
		}
   		
   	});
	
	$("#table-form-data").on('click', '.btn-action', function(e) {
		var id = $(this).attr('data-id');
		var policy_status = $(this).attr('data-status');
		var method = $(this).attr('data-method');
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL;?>/api/action_payment_policies",
			data: {
				"id": id,
				"policy_status": policy_status,
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
            <h5 class="modal-title" id="form_add_title">Thêm điều khoản thanh toán</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="frm-action" >
               <div class="form-group">
                  <label>Mã điều khoản<span class="text-danger">*</span></label>
                  <input class="form-control" name="policy_code" required type="text" id="policy_code">
               </div>
               <div class="form-group">
                  <label>Tên điều khoản<span class="text-danger">*</span></label>
                  <input class="form-control" name="policy_title" type="text" id="policy_title">
               </div> 
			   <div class="form-group">
                  <label>Số ngày được nợ (Ngày)<span class="text-danger">*</span></label>
                  <input class="form-control" name="policy_debt_day" type="text" id="policy_debt_day">
               </div> 
			   <div class="form-group">
                  <label>Thời hạn thưởng chiết khấu (Ngày)<span class="text-danger">*</span></label>
                  <input class="form-control" name="policy_comission" type="text" id="policy_comission">
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
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm-modal" data-method="new" ><i class="fas fa-plus"></i> Thêm điều khoản điều khoản</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table  class="table table-center table-hover mb-0 datatable">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">STT</th>
                           <th style="width: 10%">Mã điều khoản</th>
                           <th style="width: 10%">Tên điều khoản</th>
						   <th style="width: 10%">Số ngày được nợ</th>
						   <th style="width: 10%">Thời hạn hưởng CK (ngày)</th>
                           <th style="width: 10%">Trạng thái</th>
                           <th style="width: 10%" class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
                           $i = 1;
                           foreach($paymentpolicies as $row)
                                               {
                                               ?>
                        <tr>
                           <td>
                              <?php echo $i++;?>
                           </td>
                           <td>
                              <?php echo $row->policy_code;?>
                           </td>
                           <td>
                              <?php echo $row->policy_title;?>
                           </td>
						   <td class="text-center">
                              <?php echo $row->policy_debt_day;?>
                           </td >
						    <td class="text-center">
                              <?php echo $row->policy_comission;?>
                           </td>
                           <td><span class="badge badge-pill bg-<?php echo $row->status_class;?>-light"><?php echo $row->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
							  <a href="javascript:void(0);" class="btn btn-sm btn-success btn-sm-modal" data-method="update" data-code="<?php echo $row->policy_code;?>" data-title="<?php echo $row->policy_title;?>" data-debt-day="<?php echo $row->policy_debt_day;?>" data-id="<?php echo $row->pid;?>" data-comission="<?php echo $row->policy_comission?>">Sửa</a>                                 
                                 <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span class="sr-only">Toggle Dropdown</span>
                                 </button>
                                 <div class="dropdown-menu">
								 
								  <a href="javascript:void(0);" class="dropdown-item btn-sm-modal" data-method="duplicate" data-code="<?php echo $row->policy_code;?>" data-title="<?php echo $row->policy_title;?>" data-debt-day="<?php echo $row->policy_debt_day;?>" data-id="<?php echo $row->pid;?>" data-comission="<?php echo $row->policy_comission?>">Nhân bản</a>
								  <a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $row->pid;?>" data-status="<?php echo $row->policy_status;?>"><?php echo ($row->policy_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
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