<?php include "header.php";?> 

<script>
    $(document).ready(function(){
 
  //API add
  $('#addImage').click(function(e) {
    var formData = new FormData();
    formData.append('image_name', $('#image_name').val());
    formData.append('image_usercreate', $('#image_usercreate').val());
    // Upload file
    var file = $('#image_file')[0].files[0];
    if (file) {
      formData.append('image_file', file);
    }
    $.ajax({
      type: "POST",
      url: " <?php echo XC_URL; ?>/api/addImage",
      data : formData,
      dataType: 'json',
      enctype: 'multipart/form-data',
      processData: false,
      contentType: false,
      success: function(data) {
        if (data.status == 200) {
          Swal.fire({
            icon: 'success',
            title: "Thêm thành công",
            footer: '<a href = "" > </a>',
            timer: 1700
          })
          setTimeout(function() {
            location.reload();
          }, 2000);
        } else {
          Swal.fire({
            icon: 'error',
            title: "Lỗi",
            text: data.message,
            footer: '<a href = "" > </a>'
          })
        }
      }
    });
  });
  //end
  $("#table-images").on('click', '.btn-delete-image', function(e) {
			var iid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/deleteImage",
				"data": {
					'iid': iid
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
		
  });
</script>
<script>
 /**
   * Hàm xử lý chính khi bấm nút Sao chép
   */
  function copyAction(btn) {
    // Lấy text từ thẻ span nằm cùng cấp với nút
    const textToCopy = btn.parentElement.querySelector('span').innerText;
    // Dùng Clipboard API để copy
    navigator.clipboard.writeText(textToCopy).then(() => {
      showToast("Đã copy");
    }).catch(err => {
      console.error('Không thể copy', err);
    });
  }
  /**
   * Hàm hiển thị thông báo (Toast) - Đã fix lỗi null appendChild
   */
  function showToast(message) {
    // Tìm container, nếu chưa có thì tạo mới
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      document.body.appendChild(container);
    }
    // Tạo element toast
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
	<span style="margin-right:8px">✓</span> ${message}`;
    container.appendChild(toast);
    // Kích hoạt animation hiện ra
    setTimeout(() => toast.classList.add('show'), 10);
    // Tự động xóa sau 3 giây
    setTimeout(() => {
      toast.classList.remove('show');
      // Đợi hiệu ứng trượt ẩn đi rồi mới xóa khỏi HTML
      setTimeout(() => toast.remove(), 400);
    }, 3000);
  }

</script>
<style>
  .copy-box {
    /* display: flex;
            justify-content: space-between;
            /* align-items: center; */
    gap: 15px;
    */
  }

  .btn-copy {
    background: #e1e6e2ff;
    color: #3617faff;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 13px;
    white-space: nowrap;
    transition: 0.3s;
  }

  .btn-copy:hover {
    background: #3617faff;
    transform: scale(1.05);
    color: white;
  }

  /* 2. CSS cho Thông báo (Toast) - Tự động hiển thị ở góc */
  #toast-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
  }

  .toast {
    background: #3617faff;
    color: white;
    padding: 12px 20px;
    border-radius: 6px;
    margin-top: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    /* Hiệu ứng trượt */
    transform: translateX(120%);
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .toast.show {
    transform: translateX(0);
  }
</style>
<div class="content container-fluid">
  <div class="page-header">
    <div class="row">
      <div class="col-xl-12 col-md-12">
        <div class="card card-table">
          <div class="card-header">
            <div class="row">
              <div class="col">
                <h5 class="card-title">Hình ảnh</h5>
              </div>
              <div class="col-auto">
                <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_tax">Thêm hình ảnh</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover mb-0" id='table-images'>
                <thead class="thead-light">
                  <tr>
                    <th>Tên hình ảnh</th>
                    <th>Hình ảnh </th>
                    <th>Đường dẫn</th>
                    <th class="text-right">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($images as $image){ ?>
                  <tr>
                    
                    <td> <?php echo $image->image_name;?> </td>
                    <td> <img src="<?php echo XC_URL . '/uploads/images/' . $image->image_url; ?>" width="40" height="40"/></td></td>
                    <td>
                      <div class="copy-box">
                        <span><?php echo XC_URL . '/uploads/images/' . $image->image_url;  ?></span>
                        <i class="far fa-copy me-1 btn-copy" onclick="copyAction(this)"></i>
                      </div>
                    </td>
                    <td class="text-right">
                      <a href="#" data-bs-toggle="modal" data-bs-target="#delete_tax" class="btn btn-sm btn-white text-danger me-2 btn-delete-image" data-id="<?php echo $image->imageid;?>">
                        <i class="far fa-trash-alt me-1"></i></a>
                        
                    </td>
                   
                  </tr>
                   <?php }?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="add_tax" class="modal custom-modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Thêm hình ảnh</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="#" id="frm-action">
              <div class="form-group">
                <label>Tên hình <span class="text-danger">*</span>
                </label>
                <input class="form-control" type="text" id='image_name' required>
              </div>
              <div class="form-group">
                <label>Hình ảnh <span class="text-danger">*</span>
                </label>
                <input class="form-control" type="file" id='image_file' required>
              </div>
              <div class="submit-section">
                <input type='hidden' value="<?php echo $_SESSION['user']['id'];?>" id='image_usercreate' />
                <button class="btn btn-primary submit-btn" id='addImage'>Thêm</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <!-- <div class="modal custom-modal fade" id="delete_tax" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <div class="modal-icon text-center mb-3">
              <i class="fas fa-trash-alt text-danger"></i>
            </div>
            <div class="modal-text text-center">
              <h2>Xóa hình ảnh</h2>
              <p>Bạn có chắc sẽ xóa hình ảnh</p>
            </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button type="button" class="btn btn-danger">Xóa</button>
          </div>
        </div>
      </div>
    </div> -->
  </div>
</div>
</div> <?php include "footer.php";?>