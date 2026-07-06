<?php require "header.php"; ?>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build/ckeditor.js"></script>
<?php
$event = isset($event_detail) ? $event_detail : (object)array();
$event_categories = is_array($event_categories) ? $event_categories : array();
$is_edit = isset($method) && $method === 'edit';
$current_user_id = isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : 0;
function adminEventFormH($value){ return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function adminEventFormImage($image){ $image = trim((string)$image); return $image === '' ? '' : (strpos($image, 'http') === 0 ? $image : XC_URL.'/uploads/events/'.ltrim($image, '/')); }
$image_src = adminEventFormImage($event->event_image ?? '');
?>
<style>
.event-editor-card{max-width:none}
.event-current-image{width:180px;height:120px;border-radius:10px;background:#eef3f8;display:flex;align-items:center;justify-content:center;overflow:hidden;color:#3a57e8}
.event-current-image img{width:100%;height:100%;object-fit:cover}
.event-editor-fallback-notice{display:none;margin-top:12px;margin-bottom:10px}
.event-fallback-wrap{display:none;margin-top:12px}
.event-fallback-toolbar{display:flex;flex-wrap:wrap;gap:6px;padding:10px;border:1px solid #dbe4f0;border-bottom:0;border-radius:10px 10px 0 0;background:#f8fafc}
.event-fallback-toolbar button,.event-fallback-toolbar select{min-height:34px;border:1px solid #d5dfeb;border-radius:7px;background:#fff;color:#1f2937;padding:5px 9px}
.event-fallback-toolbar button:hover{background:#eef3ff;color:#3a57e8}
.event-fallback-editor{min-height:420px;border:1px solid #dbe4f0;border-radius:0 0 10px 10px;padding:14px;background:#fff;outline:none;overflow:auto}
.event-fallback-editor:focus{border-color:#3a57e8;box-shadow:0 0 0 .2rem rgba(58,87,232,.12)}
</style>
<div class="conatiner-fluid content-inner mt-n5 py-0">
   <form class="card event-editor-card" id="eventForm" enctype="multipart/form-data">
      <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
         <div>
            <h4 class="card-title mb-1"><?php echo $is_edit ? 'Chỉnh sửa tin tức/sự kiện' : 'Thêm tin tức/sự kiện'; ?></h4>
         </div>
         <div class="d-flex gap-2">
            <a class="btn btn-light" href="<?php echo XC_URL; ?>/admin/events"><i class="fa-solid fa-arrow-left me-1"></i>Quay lại</a>
            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk me-1"></i>Lưu</button>
         </div>
      </div>
      <div class="card-body">
         <input type="hidden" name="id" value="<?php echo (int)($event->id ?? 0); ?>">
         <input type="hidden" name="eid" value="<?php echo (int)($event->id ?? 0); ?>">
         <input type="hidden" name="method" value="<?php echo $is_edit ? 'edit' : 'add'; ?>">
         <input type="hidden" name="user_id" value="<?php echo $current_user_id; ?>">
         <input type="hidden" name="event_user_created" value="<?php echo $current_user_id; ?>">
         <div class="row g-3">
            <div class="col-lg-8">
               <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
               <input class="form-control" name="event_name" id="eventName" value="<?php echo adminEventFormH($event->event_name ?? ''); ?>" required>
            </div>
            <div class="col-lg-4">
               <label class="form-label">Loại</label>
               <select class="form-select" name="event_type">
                  <option value="0">Chưa phân loại</option>
                  <?php foreach($event_categories as $category): ?><option value="<?php echo (int)$category->id; ?>" <?php echo (int)($event->event_type ?? 0) === (int)$category->id ? 'selected' : ''; ?>><?php echo adminEventFormH($category->category_name); ?></option><?php endforeach; ?>
               </select>
            </div>
            <div class="col-md-4">
               <label class="form-label">Trạng thái</label>
               <select class="form-select" name="event_status">
                  <option value="1" <?php echo (int)($event->event_status ?? 1) === 1 ? 'selected' : ''; ?>>Hiển thị</option>
                  <option value="0" <?php echo (int)($event->event_status ?? 1) === 0 ? 'selected' : ''; ?>>Ẩn</option>
               </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
               <div class="form-check form-switch mb-2">
                  <input class="form-check-input" type="checkbox" value="1" name="event_hot" id="eventHot" <?php echo (int)($event->event_hot ?? 0) === 1 ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="eventHot">Đánh dấu nổi bật</label>
               </div>
            </div>
            <div class="col-md-8">
               <label class="form-label">Ảnh đại diện</label>
               <input class="form-control" type="file" name="event_image" accept="image/*">
               <small class="text-muted">Hỗ trợ JPG, PNG, WEBP, GIF. Tối đa 5MB.</small>
            </div>
            <div class="col-md-4">
               <?php if($image_src !== ''): ?><div class="event-current-image"><img src="<?php echo adminEventFormH($image_src); ?>" alt="<?php echo adminEventFormH($event->event_name ?? ''); ?>"></div><?php endif; ?>
            </div>
            <div class="col-md-12">
               <label class="form-label">Mô tả ngắn</label>
               <textarea class="form-control" name="event_description" rows="3"><?php echo adminEventFormH($event->event_description ?? ''); ?></textarea>
            </div>
            <div class="col-md-12">
               <label class="form-label">Nội dung <span class="text-danger">*</span></label>
               <textarea class="form-control" name="eventContent" id="eventContent"><?php echo adminEventFormH($event->event_content ?? ''); ?></textarea>
              
               
            </div>
         </div>
      </div>
   </form>
</div>
<script>
jQuery(function($){
   var fallbackActive = false;
 CKEDITOR.ClassicEditor.create(document.querySelector('textarea[name="eventContent"]'), {
        // 1. Cấu hình Toolbar (Giữ nguyên các tính năng soạn thảo mạnh mẽ)
        toolbar: {
            items: [
                'findAndReplace', 'selectAll', '|',
                'heading', '|',
                'bold', 'italic', 'strikethrough', 'underline', 'subscript', 'superscript', 'removeFormat', '|',
                'bulletedList', 'numberedList', 'todoList', '|',
                'outdent', 'indent', '|',
                'undo', 'redo', '-',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                'alignment', '|',
                'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', '|',
                'specialCharacters', 'horizontalLine', '|',
                'sourceEditing'
            ],
            shouldNotGroupWhenFull: true
        },

        // 2. KHẮC PHỤC TRIỆT ĐỂ: Loại bỏ AI và Cloud Plugins
        removePlugins: [
            // Các plugin AI (Gây lỗi ai-invalid-license-key)
            'AIAssistant', 
            'OpenAIAssistant',
            'CKBox', 
            'CKFinder', 
            'EasyImage', 
            'Base64UploadAdapter',
            
            // Các plugin Collaboration & Premium (Gây lỗi channel-id)
            'ExportPdf', 'ExportWord', 'Pagination', 'WProofreader', 'MathType',
            'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges', 
            'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 
            'TrackChanges', 'TrackChangesData', 'RevisionHistory',
            'SlashCommand', 'Template', 'DocumentOutline', 'FormatPainter', 
            'TableOfContents', 'PasteFromOfficeEnhanced'
        ],

        // 3. Các cấu hình bổ trợ
        language: 'vi',
        placeholder: 'Nhập nội dung tại đây...',
        fontSize: {
            options: [ 10, 12, 14, 'default', 18, 20, 22 ],
            supportAllValues: true
        },
        link: {
            addTargetToExternalLinks: true,
            defaultProtocol: 'https://'
        }
    })
    .then(editor => {
        window.editor = editor;
        console.log('Đã khởi tạo thành công - Không còn lỗi bản quyền!');
    })
    .catch(error => {
        console.error('Lỗi khởi tạo:', error);
    });


   

   function initFallbackEditor(){
      fallbackActive = true;
      $('#eventFallbackEditor').html($('#eventContent').val());
      $('#eventContent').hide();
      $('#eventEditorFallbackNotice').show();
      $('#eventFallbackWrap').show();
   }

   function syncFallbackEditor(){
      if(fallbackActive){
         $('#eventContent').val($('#eventFallbackEditor').html());
      }
   }

   function getEventContent(){
      if(window.editor && typeof window.editor.getData === 'function'){
         return window.editor.getData();
      }
      syncFallbackEditor();
      return $('#eventContent').val();
   }

   $('#eventFallbackToolbar').on('mousedown', 'button', function(e){
      e.preventDefault();
   });

   $('#eventFallbackToolbar').on('click', 'button', function(){
      var command = $(this).data('command');
      var action = $(this).data('action');
      $('#eventFallbackEditor').focus();
      if(command){
         document.execCommand(command, false, null);
      }
      if(action === 'link'){
         var link = window.prompt('Nhập URL liên kết');
         if(link){ document.execCommand('createLink', false, link); }
      }
      if(action === 'image'){
         var image = window.prompt('Nhập URL hình ảnh');
         if(image){ document.execCommand('insertImage', false, image); }
      }
      if(action === 'table'){
         document.execCommand('insertHTML', false, '<table border="1" cellpadding="6" cellspacing="0" style="width:100%;border-collapse:collapse"><tbody><tr><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table><p></p>');
      }
      syncFallbackEditor();
   });

   $('#eventFallbackToolbar').on('change', 'select', function(){
      $('#eventFallbackEditor').focus();
      document.execCommand($(this).data('command'), false, this.value);
      syncFallbackEditor();
   });

   $('#eventFallbackEditor').on('input blur', syncFallbackEditor);

   $('#eventForm').on('submit', function(e){
      e.preventDefault();
      var data = new FormData(this);
      data.set('event_content', getEventContent());
      if(!$.trim($('#eventName').val()) || !$.trim(data.get('event_content'))){
         Swal.fire({icon:'warning',title:'Thiếu thông tin',text:'Vui lòng nhập tiêu đề và nội dung.'});
         return;
      }
      $.ajax({
         type:'POST',url:'<?php echo XC_URL; ?>/api/events',data:data,processData:false,contentType:false,dataType:'json',
         success:function(resp){ if(resp.status==200){ Swal.fire({icon:'success',title:resp.message||'Lưu thành công',timer:1400,showConfirmButton:false}); setTimeout(function(){ window.location.href='<?php echo XC_URL; ?>/admin/events'; },1500); } else { Swal.fire({icon:'error',title:'Lỗi',text:resp.message||'Không thể lưu tin tức/sự kiện'}); } },
         error:function(){ Swal.fire({icon:'error',title:'Lỗi',text:'Có lỗi xảy ra khi gọi API lưu dữ liệu'}); }
      });
   });
});
</script>
<?php require "footer.php"; ?>
