<?php include "header.php";?>
<script src="https://cdn.tiny.cloud/1/tkufgtchvc7hc3qos6rdr6ijvawfunvusw9sluwsszq0ji0b/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
$(document).ready(function() {
	initmce();
	function initmce()
	{
		
	  tinymce.init({
		  selector: 'textarea.tinymce',
		  height : "480px",
		  plugins: [
			"advlist anchor autolink codesample fullscreen help image imagetools tinydrive",
			"code lists link media noneditable preview",
			" searchreplace table template visualblocks wordcount "
		  ],
		  toolbar: [
			"insertfile a11ycheck undo redo | bold italic code| forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify lineheightselect | bullist numlist | link image tinydrive "
			],
			setup : function(ed)
			{
				ed.on('init', function() 
				{
					this.getDoc().body.style.fontSize = '12';
					this.getDoc().body.style.fontFamily = 'Times New Roman';
				});
			},
		});
	}
	$("#listtemplate").on("change",function()
	{
		var id = $("#listtemplate option:selected").val();
		
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL; ?>/api/gettemplatedetail",
			data: {id: id},
			cache: false,
			dataType:'json',
			success: function(data)
			{
				tinymce.get('template_html').setContent(data.html);
			}
		});
		
		return false;
	});
	$("#btn-save-template").on("click",function()
	{
		var id = $("#listtemplate option:selected").val();
		var html = tinymce.get("template_html").getContent();
		$.ajax({
			type: "POST",
			url: "<?php echo XC_URL; ?>/api/updatetemplate",
			data: {id: id, html: html},
			cache: false,
			dataType:'json',
			success: function(data)
			{
				if(data.status = 200)
				{
					Swal.fire({
					  icon: 'success',
					  title: "Cập nhật thành công",
					  footer: '<a href=""></a>',
					  timer: 1700
					})
					//setTimeout(function(){ location.reload();     }, 2000);
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
      <div class="col-xl-10 col-md-8">
         <div class="card">
            <div class="card-header">
               <div class="row">
                  <div class="col">
                     <h5 class="card-title"><?php echo $pagetitle;?></h5>
                  </div>
                  <div class="col-auto">
                     <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_accounts"><i class="fas fa-plus"></i> Thêm mẫu</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <form action="#">
                  
                  <div class="form-group row">
                     <div class="col-md-12">
                        <select id="listtemplate" class="form-select">
                           <option>-- Chọn mẫu --</option>
						   <?php foreach($templates as $template)
						   {
							?>
                           <option value="<?php echo $template->id;?>"><?php echo $template->template_name;?></option>
						   <?php
						   }
						   ?>
                           
                        </select>
                     </div>
                  </div>
                  <div class="form-group row">
                     <div class="col-md-12">
                        <textarea rows="5" cols="5" id="template_html" class="form-control tinymce" placeholder=""></textarea>
                     </div>
                  </div>
                  <div class="text-end">
					<button type="button" id="btn-save-template" class="btn btn-primary">Lưu mẫu</button>
				</div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<?php include "footer.php";?>