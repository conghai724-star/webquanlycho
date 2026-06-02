<?php include "header.php";?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
   $(document).ready(function() {
       $('#summernote_new').summernote({
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
            $('#sendDB').click(function(e) {
			// if ($("#frm-action").valid()) {
                var content = $('#summernote_new').summernote('code');
                var method =  $('#method').val();
                // console.log(method);
				var formData = new FormData();
				formData.append('new_name', $('#new_name').val());
				formData.append('new_description', $('#new_description').val());
				formData.append('new_content', content);
				formData.append('new_user_created', $('#uid').val());
				formData.append('nid', $('#nid').val());
				formData.append('method',method);
                
				// Upload file
				var file = $('#new_image')[0].files[0];
				if (file) {
					formData.append('new_image', file);
				}
			$.ajax({
				type: "POST",
				url: "<?php echo XC_URL;?>/api/news",
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
            <h3 class="page-title"><?php echo $method == 'add' ? "Thêm mới tin tức" : "Sửa tin tức";?></h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
               <li class="breadcrumb-item active"><?php echo $method == 'add' ? "Thêm mới tin tức" : "Sửa tin tức";?></li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card">
            <div class="card-header">
               <h4 class="page-title">Thông tin tin tức</h4>
            </div>
            <div class="card-body">
               <form action="#">
                <div class="form-group row">
                     <div class="col-md-2">
                       <label>Tên tin tức</label><span class='text-danger'>*</span>
                     </div>
                     <div class="col-md-10">
                       <input type = 'text' id='new_name' name='new_name' class='form-control' value='<?php echo $new_detail->new_name ?? '';?>'/>
                     </div>
                </div>

                <div class="form-group row">
                     <div class="col-md-2">
                       <label>Mô tả ngắn</label><span class='text-danger'>*</span>
                     </div>
                     <div class="col-md-10">
                       <input type = 'text' id='new_description' name='new_description' class='form-control' value='<?php echo $new_detail->new_description ?? '';?>'/>
                     </div>
                </div>
                <div class="form-group row">
                     <div class="col-md-2">
                       <label>Hình đại diện</label><span class='text-danger'>*</span>
                     </div>
                     <?php if(isset($new_detail->new_image) && $new_detail->new_image != ''){  ?>
                     <div class="col-md-8">
                       <input type = 'file' id='new_image' name='new_image' class='form-control' value=''/>
                     </div>
                      <div class="col-md-2">
                       <img src = "<?php echo XC_URL . '/uploads/news/' . $new_detail->new_image; ?>" widt = '20px' height = '80px' /> 
                     </div>

                     <?php }else{?>
                     <div class="col-md-10">
                       <input type = 'file' id='new_image' name='new_image' class='form-control' value=''/>
                     </div>
                     <?php }?>
                </div>
                  <div class="form-group row">
                     <div class="col-md-12">
                        <textarea id="summernote_new" name="editordata"><?php echo $new_detail->new_content ?? '';?></textarea>
                        <input type='hidden' value='<?php echo $_SESSION['user']['id']; ?>' id = 'uid'/>
                        <input type='hidden' value = '<?php echo $new_detail->id; ?>' id='nid' />
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