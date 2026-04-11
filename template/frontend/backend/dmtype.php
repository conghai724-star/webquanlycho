<?php include_once "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		 $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký tự số!");
	
		$("#table-user").on('click', '.btn-delete-type', function(e) {
			var id = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deleteDmtype",
				"data": {
					'id': id
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Xoá thành công",
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ location.reload();     }, 2000);
					}else{
						Swal.fire({
						  icon: 'error',
						  title: "Lỗi",
						  text: data.message,
						  footer: '<a href=""></a>'
						})
					}
				}
			
			});
			return false;
		});
		
		
		
	
		$('#btn-save').click(function(e){
			
				var type_name = $("#type_name").val();
                var type_detail = $("#type_detail").val();
              
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/addtypedm",
				data:{
					'type_name': type_name,
					'type_detail': type_detail
					
				},
				dataType: 'json',
				success: function(data){
					if(data.status == 200){
						Swal.fire({
						  icon: 'success',
						  title: "Thêm thành công",
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
		});
	
		
	});
</script>
<style>
::placeholder{
	font-size:12px;
	font-style: italic;
}
.btn-search{
	background-color:white;
	border:none;
}
label.error{
	color:red;
}
</style>
<div class="content container-fluid">
   
   <div class="page-header">
      <div class="row align-items-center">
         <div class="col">
            <h3 class="page-title">Danh sách danh mục loại</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL?>">Trang chủ</a></li>
               <li class="breadcrumb-item active">Loại</li>
            </ul>
         </div>
         <div class="col-auto">
             <a href="#" data-bs-toggle="modal" id="btn-add-user" data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-primary me-1" >
            <i class="fas fa-plus"></i>Thêm Loại
            </a>
            <!-- <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
            <i class="fas fa-filter"></i> -->
            </a>
         </div>
      </div>
   </div>
   
   </div>
   <div class="row">
      <div class="col-sm-12">
         <div class="card card-table">
            <div class="card-body">
               <div class="table-responsive">
                  <table id="table-user" class="table table-center table-hover datatable">
                     <thead class="thead-light">
                        <tr>
                           <th>ID</th>
						   <th>Tên danh mục</th>
                           <th>Thuộc loại</th>
                           <th class="text-right">Thao tác</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							foreach($type as $type)
                           {
                           ?>
                        <tr>
                           <td>
                              <?php echo $type ->tid;?>
                           </td>
						   <td>
                              <?php echo $type->type_name;?>
                           </td>
						   <td>
                              <?php 
                             echo $type->dmtype_name;
                              ?>
                           </td>
                           
                           <td class="text-right">
                              <div class="btn-group">
                                     <a class="btn btn-sm btn-white text-danger btn-delete-type" data-id="<?php echo $type->id;?>" href="#">Xoá</a>
								   
								</div>
                           </td>
                        </tr>
                        <?php
							$i++;
                           }
                           ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- ADD--->
                                        
            <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Thêm loại</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="frm-action" action="#">
                    <!-- Hàng 1 -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="input1">Tên loại</label>
                        <input type="text" class="form-control" id="type_name" placeholder="Tên loại" name='type_name'>
                        </div>
                    <!-- Hàng 4 -->
                        <div class="form-group col-md-3">
                        <label for="input7">Thuộc loại</label>
                        <select class="form-control" id='type_detail' name='type_detail'>
                        <?php foreach ($dmtype as $dmtype){?>
                         <option value = '<?php echo $dmtype ->id;?>'><?php echo $dmtype ->dmtype_name;?></option>
                        
                         <?php }?>
                        </select>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <!-- <a href='' class="btn btn-primary" form="formAddUser" id='btn-save'>Thêm</a> -->
                     <button type="button" class="btn btn-primary" id = 'btn-save'>Thêm</button>
                </div>
                
                </div>
            </div>
            </div>
		<!-- end--->
         </div>
    </div>
</div>

</div>


<?php include_once "footer.php";?>