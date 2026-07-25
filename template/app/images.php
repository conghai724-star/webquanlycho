<?php require "header.php"; ?>
<?php
$image_categories = array(0 => 'Mặc định', 1 => 'Banner', 2 => 'Nội dung', 3 => 'Slider');
// $image_devices = array(0 => 'Desktop', 1 => 'Mobile');
$images = is_array($images) ? $images : array();
$page = isset($page) ? (int) $page : 1;
$per_page = isset($per_page) ? (int) $per_page : 20;
$total_images = isset($total_images) ? (int) $total_images : count($images);
$total_pages = isset($total_pages) ? (int) $total_pages : 1;

if (!function_exists('backendImageBuildUrl')) {
    function backendImageBuildUrl($targetPage) {
        return XC_URL.'/admin/images?page='.(int) $targetPage;
    }
}

if (!function_exists('backendImagePaginationItems')) {
    function backendImagePaginationItems($currentPage, $totalPages) {
        $currentPage = max(1, (int) $currentPage);
        $totalPages = max(1, (int) $totalPages);
        if ($totalPages <= 7) {
            return range(1, $totalPages);
        }
        if ($currentPage <= 4) {
            return array(1, 2, 3, 4, 5, 'ellipsis', $totalPages);
        }
        if ($currentPage >= $totalPages - 3) {
            return array(1, 'ellipsis', $totalPages - 4, $totalPages - 3, $totalPages - 2, $totalPages - 1, $totalPages);
        }
        return array(1, 'ellipsis', $currentPage - 1, $currentPage, $currentPage + 1, 'ellipsis', $totalPages);
    }
}
?>
<style>
    .image-library-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px
    }

    .image-box {
        border: 1px solid #e9ecef;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
        transition: .2s
    }

    .image-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(32, 41, 56, .12)
    }

    .image-box-preview {
        height: 180px;
        background: #f4f6f8;
        overflow: hidden;
        cursor: pointer
    }

    .image-box-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: .25s
    }

    .image-box:hover img {
        transform: scale(1.035)
    }

    .image-box-body {
        padding: 14px
    }

    .image-file-name {
        display: block;
        font-weight: 600;
        color: #232d42;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .image-url {
        font-size: 12px;
        color: #8a92a6;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis
    }

    .image-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-top: 13px
    }

    .image-actions .btn {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px
    }

    .empty-library {
        border: 2px dashed #dce1e7;
        border-radius: 14px;
        padding: 70px 20px;
        text-align: center
    }

    .image-preview-modal {
        max-height: 70vh;
        max-width: 100%;
        object-fit: contain
    }

    @media(max-width:767px) {

        .library-filter,
        .library-filter>* {
            width: 100% !important
        }
    }
</style>

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="card-title mb-1">Thư viện hình ảnh</h4>
                <p class="mb-0 text-muted"><span id="visibleImageCount"><?php echo count($images); ?></span> / <?php echo $total_images; ?> hình ảnh</p>
            </div>
            <button class="btn btn-success rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addImageModal"><i class="fa-solid fa-plus me-2"></i>Thêm hình ảnh</button>
        </div>
        <div class="card-body">
            <div class="library-filter d-flex flex-wrap justify-content-between gap-2 mb-4">
                <div class="input-group" style="max-width:430px"><span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span><input id="imageSearch" type="search" class="form-control" placeholder="Tìm theo tên hình ảnh..."></div>
                <div class="d-flex gap-2">
                    <!-- <select id="categoryFilter" class="form-select">
                        <option value="">Tất cả danh mục</option><?php foreach($image_categories as $key => $label): ?><option value="<?php echo $key; ?>"><?php echo $label; ?></option><?php endforeach; ?>
                    </select> -->
                   
                </div>
            </div>

            <div id="imageLibraryGrid" class="image-library-grid">
                <?php foreach($images as $image):
                    $extension = strtolower(pathinfo($image->image_url, PATHINFO_EXTENSION));
                    $url = XC_URL.'/uploads/images/'.rawurlencode($image->image_url);
                    $created = strtotime($image->image_created_date);
                ?>
                <article class="image-box" data-name="<?php echo htmlspecialchars(strtolower($image->image_name.' '.$image->image_url)); ?>" data-category="<?php echo (int)$image->image_category; ?>" >
                    <div class="image-box-preview preview-image-btn" data-url="<?php echo htmlspecialchars($url); ?>" data-name="<?php echo htmlspecialchars($image->image_name); ?>"><img src="<?php echo htmlspecialchars($url); ?>" alt="<?php echo htmlspecialchars($image->image_name); ?>" loading="lazy"></div>
                    <div class="image-box-body">
                        <span class="image-file-name" title="<?php echo htmlspecialchars($image->image_name); ?>">
                            <?php echo htmlspecialchars($image->image_name); ?></span>
                        <div class="image-url mt-1" title="<?php echo htmlspecialchars($image->image_url); ?>"><?php echo htmlspecialchars($image->image_url); ?></div>
                        <div class="d-flex flex-wrap gap-1 mt-2"><span class="badge bg-soft-info text-info"></span><span class="badge bg-soft-secondary text-secondary"><?php echo strtoupper(htmlspecialchars($extension)); ?> · <?php echo $created ? date('d/m/Y', $created) : ''; ?></span></div>
                        <div class="image-actions">
                            <button class="btn btn-sm btn-outline-primary copy-name-btn" data-name="<?php echo XC_URL.'/uploads/images/'.($image->image_url); ?>"><i class="fa-regular fa-copy"></i><span></span></button>
                            
                            <button class="btn btn-sm btn-outline-warning edit-image-btn" data-id="<?php echo (int)$image->id; ?>" data-name="<?php echo htmlspecialchars($image->image_name); ?>"> <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-image-btn" data-id="<?php echo (int)$image->id; ?>" data-name="<?php echo htmlspecialchars($image->image_name); ?>"><i class="fa-regular fa-trash-can"></i><span></span></button>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <div id="emptyImageLibrary" class="empty-library <?php echo count($images) ? 'd-none' : ''; ?>"><i class="fa-regular fa-images fa-3x text-muted mb-3"></i>
                <h5>Chưa có hình ảnh phù hợp</h5>
                <p class="text-muted mb-0">Thêm ảnh mới hoặc thay đổi bộ lọc.</p>
            </div>
        </div>
        <?php if ($total_pages > 1): ?>
            <div class="card-footer d-flex flex-wrap justify-content-between align-items-center gap-3">
                <small class="text-muted">Hiển thị <?php echo $per_page; ?> hình mỗi trang.</small>
                <nav aria-label="Phân trang thư viện ảnh">
                    <ul class="pagination mb-0 justify-content-end flex-wrap">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo $page <= 1 ? '#' : htmlspecialchars(backendImageBuildUrl($page - 1), ENT_QUOTES, 'UTF-8'); ?>">Trước</a>
                        </li>
                        <?php foreach (backendImagePaginationItems($page, $total_pages) as $pagination_item): ?>
                            <?php if ($pagination_item === 'ellipsis'): ?>
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            <?php else: ?>
                                <li class="page-item <?php echo (int) $pagination_item === $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo htmlspecialchars(backendImageBuildUrl($pagination_item), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $pagination_item; ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo $page >= $total_pages ? '#' : htmlspecialchars(backendImageBuildUrl($page + 1), ENT_QUOTES, 'UTF-8'); ?>">Sau</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="addImageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="addImageForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Thêm hình ảnh</h5><button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3"><label class="form-label">Tên hình ảnh</label><input class="form-control" name="image_name" required></div>
                
                <div><label class="form-label">Chọn hình ảnh</label><input class="form-control" type="file" name="image_file" accept=".jpg,.jpeg,.png,.gif,.webp,image/*" required>
                    <div class="form-text">Tối đa 10 MB.</div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button><button class="btn btn-success submit-btn">Thêm hình ảnh</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="editImageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editImageForm" class="modal-content" enctype="multipart/form-data">
            <div class="modal-header">
                <h5 class="modal-title">Sửa hình ảnh</h5><button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editImageId">
                <div class="mb-3"><label class="form-label">Tên hình ảnh</label><input class="form-control" name="image_name" id="editImageName" required></div>
                <div class="row">
                   
                   
                </div>
                <div><label class="form-label">Thay hình ảnh mới</label><input class="form-control" type="file" name="image_file" accept=".jpg,.jpeg,.png,.gif,.webp,image/*">
                    <div class="form-text">Để trống nếu không thay tệp ảnh.</div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button><button class="btn btn-warning submit-btn">Lưu thay đổi</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="previewImageModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="previewImageName" class="modal-title"></h5><button class="btn-close" type="button" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-light"><img id="previewImage" class="image-preview-modal" alt=""></div>
        </div>
    </div>
</div>

<script>
    $(function() {
        function notify(icon, title, text) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text || '',
                timer: icon === 'success' ? 1400 : undefined,
                showConfirmButton: icon !== 'success'
            });
        }

        function submitAjax(form, url) {
            var $form = $(form),
                $btn = $form.find('.submit-btn');
            $btn.prop('disabled', true);
            $.ajax({
                url: url,
                type: 'POST',
                data: new FormData(form),
                processData: false,
                contentType: false,
                dataType: 'json'
            }).done(function(r) {
                if (Number(r.status) === 200) {
                    notify('success', 'Thành công', r.message);
                    setTimeout(function() {
                        location.reload()
                    }, 1450)
                } else {
                    notify('error', 'Không thể thực hiện', r.message)
                }
            }).fail(function() {
                notify('error', 'Lỗi', 'Không thể kết nối API')
            }).always(function() {
                $btn.prop('disabled', false)
            })
        }
        $('#addImageForm').on('submit', function(e) {
            e.preventDefault();
            submitAjax(this, '<?php echo XC_URL; ?>/api/libraryImageAdd')
        });
        $('#editImageForm').on('submit', function(e) {
            e.preventDefault();
            submitAjax(this, '<?php echo XC_URL; ?>/api/libraryImageUpdate')
        });
        $('.edit-image-btn').on('click', function() {
            $('#editImageId').val(this.dataset.id);
            $('#editImageName').val(this.dataset.name);
            $('#editImageCategory').val(this.dataset.category);
            $('#editImageDevice').val(this.dataset.device);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('editImageModal')).show()
        });
        $('.delete-image-btn').on('click', function() {
            var id = this.dataset.id,
                name = this.dataset.name;
            Swal.fire({
                icon: 'warning',
                title: 'Xóa hình ảnh?',
                text: name,
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
                confirmButtonColor: '#dc3545'
            }).then(function(x) {
                if (!x.isConfirmed) return;
                $.post('<?php echo XC_URL; ?>/api/libraryImageDelete', {
                    id: id
                }, function(r) {
                    if (Number(r.status) === 200) {
                         Swal.fire({
                          toast: true,
                          // position: 'bottom-end',
                          icon: 'success',
                          title: 'Đã xóa',
                          showConfirmButton: false,
                          timer: 1200,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                          }
                      });
                        setTimeout(function() {
                            location.reload()
                        }, 1450)
                    } else {
                        notify('error', 'Không thể xóa', r.message)
                    }
                }, 'json').fail(function() {
                    notify('error', 'Lỗi', 'Không thể kết nối API')
                })
            })
        });
        var grid = document.getElementById('imageLibraryGrid'),
            cards = Array.from(grid.querySelectorAll('.image-box'));

        function filter() {
            var q = $('#imageSearch').val().toLowerCase().trim(),
                category = $('#categoryFilter').val(),
                device = $('#deviceFilter').val(),
                visible = 0;
            cards.forEach(function(c) {
                var show = (!q || c.dataset.name.indexOf(q) !== -1) && (!category || c.dataset.category === category) && (!device || c.dataset.device === device);
                c.classList.toggle('d-none', !show);
                if (show) visible++
            });
            $('#visibleImageCount').text(visible);
            $('#emptyImageLibrary').toggleClass('d-none', visible !== 0)
        }
        $('#imageSearch').on('input', filter);
        $('#categoryFilter,#deviceFilter').on('change', filter);
        $('.copy-name-btn').on('click', function() {
            var b = this,
                done = function() {
                    var old = b.innerHTML;
                    Swal.fire({
                          toast: true,
                          // position: 'bottom-end',
                          icon: 'success',
                          title: "Đã copy",
                          showConfirmButton: false,
                          timer: 1200,
                          timerProgressBar: true,
                          didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                          }
                      });
                    setTimeout(function() {
                        b.innerHTML = old
                    }, 1400)
                };
            if (navigator.clipboard) {
                navigator.clipboard.writeText(b.dataset.name).then(done)
            } else {
                var t = $('<textarea>').val(b.dataset.name).appendTo('body').select();
                document.execCommand('copy');
                t.remove();
                done()
            }
        });
        $('.preview-image-btn').on('click', function() {
            $('#previewImage').attr('src', this.dataset.url).attr('alt', this.dataset.name);
            $('#previewImageName').text(this.dataset.name);
            bootstrap.Modal.getOrCreateInstance(document.getElementById('previewImageModal')).show()
        });
    });
</script>
<?php require "footer.php"; ?>
