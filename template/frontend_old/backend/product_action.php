<?php include_once "header.php"; ?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
	$(document).ready(function(e){
		$('#summernote_products').summernote({
		placeholder: 'Bắt đầu soạn thảo: Bạn có thể kẻ bảng, chèn ảnh, link...',
		tabsize: 2,
		height: 400,
		toolbar: [
			// Các nhóm công cụ
			['style', ['style']],
			['font', ['bold', 'underline', 'clear', 'italic']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']], // Nút kẻ bảng
			['insert', ['link', 'picture', 'video']], // Nút chèn link, ảnh, video
			['view', ['fullscreen', 'codeview', 'help']]
		]
	});
	
	   $.validator.addMethod("alpha", function(value, element){

        return this.optional(element) || value == value.match(/^[0-9, '']+$/);

    }, "Vui lòng nhập ký số!");
	$("#form-product").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"product_name":{
				required: true,
				alpha: true
			},
			"product_category":{
				required: true
			},
			"product_title":{
				required: true
			},
			"product_group":{
				required: true
			},
			
			"product_name":{
				required: true
			},
			"product_address":{
				required: true
			},
			"product_email":{
				required: true
			},
			"product_phone":{
				required: true
			},
			"product_staff":{
				required: true
			}
			
		},
		messages:{
				product_name: "Vui lòng nhập mã số thuế",
				product_category: "Vui lòng chọn loại",
				product_group: "Vui lòng chọn nhóm khách hàng/NCC",
				product_name: "Vui lòng nhập tên khách hàng/NCC",
				product_address: "Vui lòng nhập địa chỉ",
				product_title: "Vui lòng nhập tên khách hàng/NCC",
				product_email: "Vui lòng email",
				product_phone: "Vui lòng nhập số điện thoại",
				product_staff: "Vui lòng chọn nhân viên bán hàng"
			}
	});
	$("#form-employee").validate({
		onfocusout: false,
		onkeyup: false,
		onclick: false,
		rules: {
			"employee_name":{
				required: true
			},
			"employee_branch":{
				required: true
			},
			"employee_address":{
				required: true
			},
			"employee_phone":{
				required: true
			},
			"employee_email":{
				required: true
			},
			"employee_national_id":{
				required: true
			},
			"employee_issue_date":{
				required: true
			},
			"employee_issue_by":{
				required: true
			}
			
		},
		messages:{
				employee_name : "Vui lòng nhập tên nhân viên",
				employee_branch : "Vui lòng chọn đơn vị",
				employee_position: "Vui lòng chọn chức danh",
				employee_department: "Vui lòng chọn phòng ban",
				employee_address: "Vui lòng nhập địa chỉ",
				employee_phone: "Vui lòng nhập số điện thoại",
				employee_email: "Vui lòng nhập email",
				employee_national_id: "Vui lòng nhập CMND",
				employee_issue_date: "Vui lòng nhập ngày cấp",
				employee_issue_by: "Vui lòng nhập nơi cấp"
			}
	});
   		$('#sendDB').click(function(e) {
			// if ($("#frm-action").valid()) {

                var content = $('#summernote_products').summernote('code');
                var method =  $('#method').val();
                console.log(content);
				var formData = new FormData();
				formData.append('product_code', $('#product_code').val());
				formData.append('product_name', $('#product_name').val());
				formData.append('product_unit', $('#product_unit').val());
				formData.append('product_price', $('#product_price').val());
				formData.append('product_discount', $('#product_discount').val());
				formData.append('product_description', content);
				formData.append('product_category', $('#product_category').val());
				formData.append('pid', $('#pid').val());
				formData.append('method',method);
                
				// Upload file
				var file = $('#product_image')[0].files[0];
				if (file) {
					formData.append('product_image', file);
				}
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/productActions",
				data:formData,
				dataType: 'json',
				enctype: 'multipart/form-data',
				processData: false,
				contentType: false,
				success: function(data){
					if(data.status == 200){				
						Swal.fire({
						  icon: 'success',
						  title: data.message,
						  footer: '<a href=""></a>',
						  timer: 1700
						})
						setTimeout(function(){ window.location.href=data.returnUrl;     }, 2000);
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
			// }
		});
		
	});
	
	
</script>
<style>
label.error{
	color:red;
}
</style>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col-sm-12">
            <h3 class="page-title"><?php echo $method == 'add' ? "Thêm mới thuốc" : "Sửa thông tin thuốc";?></h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Trang chủ</a></li>
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>/admin/products">Thuốc</a></li>
               <li class="breadcrumb-item active"><?php echo $method == 'add' ? "Thêm mới thuốc" : "Sửa thông tin thuốc";?></li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Thông tin cơ bản</h5>
            </div>
            <div class="card-body">
               <form action="#" data-select2-id="13" id="form-product">
                  <div class="row">
                     <div class="col-md-6" data-select2-id="12">
                        <div class="row">
							<div class="col-md-4">
                              <div class="form-group">
                                 <label>Mã thuốc:</label><span class='text-danger'>*</span>
                                 <input type="text" value='<?php echo $product->product_code ?? '';?>' class="form-control" name='product_code' id='product_code'>
                              </div>
                           </div>
						   <div class="col-md-8">
							<div class="form-group">
                                 <label>Tên thuốc:</label><span class='text-danger'>*</span>
                                 <input type="text" class="form-control" id='product_name' name="product_name" value="<?php echo $product->product_name ?? '';?>">
                              </div>
                           </div>
						  
						   <div class="col-md-6">
                                <div class="form-group">
                                 <label>Giá bán:</label><span class='text-danger'>*</span>
                                 <input type="number" class="form-control" id='product_price' name="product_price" value="<?php echo $product->product_price ?? '';?>">
                              </div>
                           </div>
						   <div class="col-md-6">
                                <div class="form-group">
                                 <label>Giảm giá (nếu có):</label>
                                 <input type="number" class="form-control" id='product_discount' name="product_discount" value="<?php echo $product->product_discount ?? '';?>">
                              </div>
                           </div>
						  
						   
                        </div>
						
                       
                     </div>
                     <div class="col-md-6">
                        <div class="row">
						   <div class="col-md-6">
                              <div class="form-group">
                                 <label>Loại:</label><span class='text-danger'>*</span>
                                 <select class="select select2 form-control" id='product_category' name="product_category">
									<option >----Chọn----</option>
									<?php foreach($product_categories as $category){?>
									<option value="<?php echo $category->id;?>"  <?php if($product->product_category == $category->id) echo 'selected = selected'; ?>><?php echo $category->category_name;?></option>
									<?php }?>
								 
							   </select>
                              </div>
                           </div>
						    <div class="col-md-6">
                              <div class="form-group">
                                 <label>Đơn vị tính:</label><span class='text-danger'>*</span>
                                 <select class="select select2 form-control" id='product_unit' name="product_unit">
									<option>----Chọn----</option>
									<?php foreach($units as $unit){?>
									<option value="<?php echo $unit->id;?>" <?php if($product->product_unit == $unit->id) echo 'selected = selected'; ?>><?php echo $unit->unit_name;?>
									</option>									
									<?php }?>
							   </select>
                              </div>
                           </div>
						   
							<?php if($product -> product_image != ''){ ?>
							<div class="col-md-8">
							<div class="form-group" >
								<label>Hình ảnh:</label><span class='text-danger'>*</span>
								<input type="file" class="form-control" id='product_image' name="image">
							</div>
							</div>
							<div class="col-md-4">
							<div class="form-group" >
							 <img src = "<?php echo XC_URL . '/uploads/products/' . $product->product_image; ?>" widt = '15px' height = '60px' /> 
							</div>
							</div>
							<?php }else{?>
						   <div class="col-md-12">
                              <div class="form-group" >
                                 <label>Hình ảnh:</label><span class='text-danger'>*</span>
                                 <input type="file" class="form-control" id='product_image' name="image">
                              </div>
                           </div>
						   <?php }?>
                        </div>
                     </div>
					  <div class="form-group">
                           <label>Mô tả:</label><span class='text-danger'>*</span>
						    <textarea id="summernote_products"  placeholder="Mô tả về thuốc" name="editordata"><?php echo $product->product_description ?? '';?></textarea>
                        </div>
                  </div>
                  <div class="text-end">
                     <button type="button" class="btn btn-primary" id = 'sendDB'>Lưu</button>
					 <input type='hidden' value='<?php echo $method?>' id = 'method' />
					 <input type='hidden' value='<?php echo $product->pid?>' id = 'pid' />
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   
   
   
</div>
      
<?php include_once "footer.php";?>