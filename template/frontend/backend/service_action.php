<?php include "header.php";?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
   $(document).ready(function() {
       $('#summernote_service').summernote({
           placeholder: 'Bắt đầu soạn thảo: Bạn có thể kẻ bảng, chèn ảnh, link...',
           tabsize: 2,
           height: 400,
           toolbar: [
               // Các nhóm công cụ
               ['style', ['style']],
               ['font', ['bold', 'underline', 'clear', 'italic']],
                ['fontname', ['fontname']],   // chọn font chữ
         ['fontsize', ['fontsize']],   // chọn cỡ chữ
               ['color', ['color']],
               ['para', ['ul', 'ol', 'paragraph']],
               ['table', ['table']], // Nút kẻ bảng
               ['insert', ['link', 'picture', 'video']], // Nút chèn link, ảnh, video
               ['view', ['fullscreen', 'codeview', 'help']]
           ]
       });
            $('#sendDB').click(function(e) {
			// if ($("#frm-action").valid()) {
                var content = $('#summernote_service').summernote('code');
                var method =  $('#method').val();
                var service_category = $("service_category").val();

                // console.log(method);
				var formData = new FormData();
				formData.append('service_name', $('#service_name').val());
				formData.append('service_description', content);
				formData.append('service_category', $('#service_category').val());
				formData.append('sid', $('#sid').val());
				formData.append('method',method);
                
				// Upload file
				var file = $('#service_image')[0].files[0];
				if (file) {
					formData.append('service_image', file);
				}
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/services",
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
   // function getContent() {
   //     var content = $('#summernote_gioithieu').summernote('code');
   //    //  alert("Dữ liệu HTML bạn vừa nhập:\n" + content);
   //    //  console.log(content);
   // }

   
</script>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col">
            <h3 class="page-title"><?php echo $method == 'add' ? "Thêm mới ".$category_name : "Chỉnh sửa ".$category_name;?></h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
               <li class="breadcrumb-item active"><?php echo $method == 'add' ? "Thêm mới" : "Chỉnh sửa";?></li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card">
            <div class="card-header">
               <h4 class="page-title">Thông tin chung</h4>
            </div>
            <div class="card-body">
               <form action="#">
                <div class="form-group row">
                     <div class="col-md-2">
                       <label>Tiêu đề</label><span class='text-danger'>*</span>
                     </div>
                     <div class="col-md-10">
                       <input type = 'text' id='service_name' name='service_name' class='form-control' value='<?php echo $service_detail->service_name ?? '';?>'/>
                     </div>
                </div>

                
                <div class="form-group row">
                     <div class="col-md-2">
                       <label>Hình đại diện</label><span class='text-danger'>*</span>
                     </div>
                     <?php if(isset($service_detail->service_image) && $service_detail->service_image != ''){  ?>
                     <div class="col-md-8">
                       <input type = 'file' id='service_image' name='service_image' class='form-control' value=''/>
                     </div>
                      <div class="col-md-2">
                       <img src = "<?php echo XC_URL . '/uploads/services/' . $service_detail->service_image; ?>" widt = '20px' height = '80px' /> 
                     </div>

                     <?php }else{?>
                     <div class="col-md-10">
                       <input type = 'file' id='service_image' name='service_image' class='form-control' value=''/>
                     </div>
                     <?php }?>
                </div>
                  <div class="form-group row">
                     <div class="col-md-12">
                        <textarea id="summernote_service" name="editordata"><?php echo $service_detail->service_description ?? '';?></textarea>
                        <input type='hidden' value='<?php echo $_SESSION['user']['id']; ?>' id = 'uid'/>
                        <input type='hidden' value = '<?php echo $service_detail->id; ?>' id='sid' />
                        <input type='hidden' value = '<?php echo $service_category; ?>' id='service_category' />
                        <input type='hidden' value = '<?php echo $method; ?>' id='method' />
                        <button type="button" id ='sendDB' class="btn btn-primary">Lưu</button>
                     </div>
                  </div>
            </div>
            </form>
         </div>
      </div>
    </div>
   </div>
</div>
</div>
</div>
</div>
<script>
   ClassicEditor.create(document.querySelector('#content'));
</script>
<?php include "footer.php";?>