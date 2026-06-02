<?php include_once "header.php"; ?>
<div class="content">
   <div class="container">
      <div class="row">
         <div class="col-md-4 col-lg-4 col-xl-3 theiaStickySidebar">
            <?php include_once "user-sidebar.php";?>
         </div>
         <div class="col-md-8 col-lg-8 col-xl-9">
			<div class="progress-example card">
			   <div class="card-header">
				  <h4 class="card-title">Trở thành diễn giả tại CESTalk</h4>
			   </div>
			   <div class="card-body pb-0">
					<blockquote>
						<p class="mb-0">Nhằm tạo ra nhiều giá trị đóng góp cho cộng đồng và xã hội. CESTalk hoan nghênh anh/chị tham gia vào các chương trình phi lợi nhuận của CESTalk. Vui lòng hoàn thành các bước dưới đây để tham gia vào Cộng đồng Speaker CESTalk.</p>
					</blockquote>
				  <div class="row">
					 <div class="col-md-12">
						<div class="progress">
						   <div class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					 </div>
				  </div>
			   </div>
			</div>
            <div class="card" id="register-step-1">
				<div class="card-body">
					<h4 class="card-title">Giới thiệu chung về bản thân</h4>
					<div class="form-group mb-0">
						<textarea id="user_bio" class="form-control" rows="5"></textarea>
					</div>
					<div class="submit-section mt-3">
                        <button type="submit" class="btn btn-primary submit-btn" id="btn-save-step-1">Lưu và tiếp tục <i class="fa-solid fa-circle-arrow-right"></i></button>
                     </div>
				</div>
			</div>
			<div class="card" id="register-step-2">
			   <div class="card-body">
				  <h4 class="card-title">Đơn vị công tác</h4>
				  <div class="row form-row">
					 <div class="col-md-6">
						<div class="form-group">
						   <label>Công ty</label>
						   <input type="text" class="form-control">
						</div>
					 </div>
					 <div class="col-md-6">
						<div class="form-group">
						   <label>Chức vụ</label>
						   <input type="text" class="form-control">
						</div>
					 </div>
					 <div class="col-md-12">
						<div class="form-group">
						   <label>Hình ảnh về Công ty</label>
						   <form action="#" class="dropzone dz-clickable">
							  <div class="dz-default dz-message"><span>Chọn hoặc kéo thả ảnh để tải lên</span></div>
						   </form>
						</div>
						<div class="upload-wrap">
						   <div class="upload-images">
							  <img src="assets/img/features/feature-01.jpg" alt="Upload Image">
							  <a href="javascript:void(0);" class="btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
						   </div>
						   <div class="upload-images">
							  <img src="assets/img/features/feature-02.jpg" alt="Upload Image">
							  <a href="javascript:void(0);" class="btn btn-icon btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
						   </div>
						</div>
					 </div>
				  </div>
				  <div class="submit-section mt-3">
					<button type="submit" class="btn btn-primary submit-btn" id="btn-save-step-2">Lưu và tiếp tục <i class="fa-solid fa-circle-arrow-right"></i></button>
				 </div>
			   </div>
			</div>
         </div>
      </div>
   </div>
</div>
<?php include_once "footer.php"; ?>