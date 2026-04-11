<?php include "header.php";?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>s
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script> -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/super-build/ckeditor.js"></script>

<script>
   $(document).ready(function() {
    CKEDITOR.ClassicEditor.create(document.querySelector('textarea[name="summernote_gioithieu"]'), {
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


      $('#updatePage').click(function(e){
                  var userid = $('#uid').val();
                  var content = window.editor.getData();
               
                  var type_id = $('#tid').val();
               $.ajax({
                  type: "POST",
                  url: "<?php echo XC_URL;?>/api/updateIntroduce",
                  data:{
                     'userid': userid,
                     'content': content,
                     'type_id': type_id
                     
                  },
                  dataType: 'json',
                  success: function(data){
                     if(data.status == 200){
                        Swal.fire({
                        icon: 'success',
                        title: "Thành công",
                        footer: '<a href=""></a>',
                        timer: 1700
                        })
                        setTimeout(function(){window.location.reload();     }, 2000);	
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
            <h3 class="page-title">Trang tĩnh</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
               <li class="breadcrumb-item active">Trang tĩnh</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-lg-12">
         <div class="card">
            <div class="card-header">
               <h4 class="page-title"><?php echo $introduce -> category_name;?></h4>
            </div>
            <div class="card-body">
               <form action="#">
               
                <div class="col-md-10">
                        <textarea id="summernote_gioithieu" name="summernote_gioithieu"><?php echo $introduce->introduce_content;?></textarea>
                        <input type='hidden' value='<?php echo $_SESSION['user']['id']; ?>' id = 'uid'/>
                        <input type='hidden' value = '<?php echo $id; ?>' id='tid' />
                        <button type="button" id ='updatePage' class="btn btn-primary">Lưu</button>
                     </div>
                </div>
                  <!--<div class="form-group row">-->
                  <!--   <div class="col-md-10">-->
                  <!--      <textarea id="summernote_gioithieu" name="summernote_gioithieu"><?php echo $introduce->introduce_content;?></textarea>-->
                  <!--      <input type='hidden' value='<?php echo $_SESSION['user']['id']; ?>' id = 'uid'/>-->
                  <!--      <input type='hidden' value = '<?php echo $id; ?>' id='tid' />-->
                  <!--      <button type="button" id ='updatePage' class="btn btn-primary">Lưu</button>-->
                  <!--   </div>-->
                  <!--</div>-->
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
<?php include "footer.php";?>