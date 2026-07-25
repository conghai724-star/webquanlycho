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
				formData.append('event_name', $('#event_name').val());
				formData.append('event_description', $('#event_description').val());
				formData.append('event_content', content);
				formData.append('event_user_created', $('#uid').val());
				formData.append('nid', $('#nid').val());
				formData.append('method',method);
                
				// Upload file
				var file = $('#event_image')[0].files[0];
				if (file) {
					formData.append('event_image', file);
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
            <h3 class="page-title">Thông tin chi tiết</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
               <li class="breadcrumb-item active">Thông tin chi tiết</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card">
            <!-- <div class="card-header">
               <h4 class="page-title">Thông tin tin tức</h4>
              
            </div> -->
            <div class="card-body">
                <div class='row'>
                    <div class='col-2'>Tiêu đề tin tức: </div>
                    <div class = 'col-8'><?php echo $event_detail -> event_name;?></div><p>
                    <div class = 'col-2'>Mô tả ngắn:</div>
                    <div class = 'col-10'><?php echo $event_detail -> event_description;?></div><p></p>
                    <div class = 'col-2'>Nội dung:</div>
                    <div class = 'col-10'><?php echo $event_detail -> event_content;?></div>

                </div>  

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
