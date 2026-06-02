<?php require "header_new.php"; ?>
  
 <style>
.eoffice-wrapper {
    background: #fff;
    border: 1px solid #ced4da;
    border-radius: 4px;
    margin-bottom: 25px;
    font-family: Arial, sans-serif;
}

.vnpt-header-bar {
    background: #1760a5;
    color: #fff;
    padding: 12px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.vnpt-header-bar h2 {
    margin: 0;
    font-size: 15px;
    text-transform: uppercase;
    font-weight: bold;
    color: #fff;
}

.filter-panel {
    padding: 15px;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    gap: 10px;
    align-items: center;
}

.filter-label {
    font-size: 12px;
    font-weight: bold;
    color: #444;
    white-space: nowrap;
}

.vnpt-combo-gop {
    flex-grow: 1;
    padding: 8px;
    border: 1px solid #1760a5;
    border-radius: 3px;
    font-size: 14px;
    color: #333;
    outline: none;
}

.vnpt-btn-view {
    background: #e36928;
    color: #fff;
    border: none;
    padding: 9px 20px;
    border-radius: 3px;
    font-weight: bold;
    cursor: pointer;
    text-transform: uppercase;
    font-size: 12px;
    transition: 0.2s;
}

.vnpt-btn-view:hover {
    background: #1760a5;
}

.pdf-display-zone {
    background: #525659;
    padding: 10px;
}

iframe#pdfFrame {
    width: 100%;
    height: 800px;
    border: none;
    background: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.pdf-info-bar {
    background: #fff;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #dee2e6;
}

#fileTitle {
    font-size: 13px;
    font-weight: bold;
    color: #1760a5;
    text-transform: uppercase;
}
</style>

</section>
<div id="breadcrumbs">
    <div class="ctn">
        <div id="crumbs"><a href="index.html">Trang chủ</a> <span>/</span> <a class="current">Lịch công tác</a></div>
    </div>
</div>
<div class="ctn">
    <div class="row">
        <div id="main" class="col-12 clg-12">
            <article id="catePts">

                <div id="pstDetail">
                    <div id="pstCntn">
                        <div class="eoffice-wrapper">
                            <div class="vnpt-header-bar">
                                <h2>Lịch công tác điện tử</h2>
                                <span id="currentDate" style="font-size: 11px; opacity: 0.9;"></span>
                            </div>
                           <form action="#" method="POST" id="myForm" onsubmit="return false;">
                                <div class="filter-panel">
                                    <span class="filter-label">CHỌN LỊCH:</span>
                                    <select id="combinedPicker" class="vnpt-combo-gop">
                                        <?php foreach($calendar_word as $calendar_word){ ?>
                                        <option <?php echo ($id == $calendar_word->id) ? "selected=selected" : "";?> value="<?php echo $calendar_word->id;  ?>"><?php echo $calendar_word->calendar_work_name;?></option>
                                    <?php } ?>
                                    </select>
                                    <button class="vnpt-btn-view" id='btnview'>XEM LỊCH</button>
                                </div>
                            </form>
                            <div class="pdf-info-bar">
                                <span id="fileTitle">Vui lòng chọn tuần công tác</span>
                                <a href="#" id="btnDownload" download target="_blank"
                                    style="background: #1760a5; color: #fff; padding: 5px 12px; border-radius: 3px; font-size: 11px; text-decoration: none; font-weight: bold;">
                                    TẢI PDF
                                </a>
                            </div>
                            <div class="pdf-display-zone">
                                <?php if(isset($calendar_now)){?>
                                <iframe id="pdfFrame" src="<?php echo XC_URL."/uploads/files/".$calendar_now;?>"></iframe>
                                <?php }else{
                                    echo "<b>Hiện tại không có lịch công tác nào!</b>";
                                }?>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

        </div>
    </div>
    </main>
    <script>
        function updatePdfViewer() {
           
            const val = document.getElementById('combinedPicker').value;
            // const [year, week] = val.split('-');
            // const title = "CHI TIẾT LỊCH LÀM VIỆC TUẦN " + week + " NĂM " + year;
            // document.getElementById('fileTitle').innerText = title;
            // Quy tắc đặt tên file: lich_2026_tuan_09.pdf
            // const fileName = `lich_${year}_tuan_${week}.pdf`;
            const pdfPath = "https://storage-vnportal.vnpt.vn/gov-tha/7508/FileQuanTriTinTuc/L%E1%BB%8ACH%20C%C3%94NG%20T%C3%81C%20TU%E1%BA%A6N%2009.2026639076038906808923.pdf";

            document.getElementById('pdfFrame').src = pdfPath;
            document.getElementById('btnDownload').href = pdfPath;
        }
    </script>
    <?php require "footer_new.php"; ?>
  <script>
 jQuery(document).ready(function($){
   $('#btnview').click(function(e) {
       
        var id = $('#combinedPicker').val(); 
        
        var selectedText = $('#combinedPicker').find('option:selected').text();
        $('#fileTitle').text("CHI TIẾT LỊCH LÀM VIỆC " + selectedText.toUpperCase());
        $.ajax({
            type: "POST",
            url: '<?php echo XC_URL;?>/api/load_work_schedule',
            data: { 
                id: id,
                title: selectedText 
            },
            dataType:'json',
            success: function(data) {
                const pdfPath = data.src;
                document.getElementById('pdfFrame').src = pdfPath;
                document.getElementById('btnDownload').href = pdfPath;
                console.log("Dữ liệu trả về từ API:", response);
                // Giả sử API trả về link file, bạn cập nhật iframe
                // $('#pdfViewerFrame').attr('src', response.fileUrl);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi API:", error);
            }
        });
    });
});
</script>