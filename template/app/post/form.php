<!-- Nạp FontAwesome & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.editor-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-bottom: none;
    border-radius: 6px 6px 0 0;
    padding: 6px 10px;
    align-items: center;
}
.editor-btn {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 4px 10px;
    font-size: 12.5px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s;
}
.editor-btn:hover {
    background: #e2e8f0;
    color: #0f172a;
    border-color: #94a3b8;
}
.editor-btn.strip-btn {
    background: #fef2f2;
    color: #dc2626;
    border-color: #fecaca;
}
.editor-btn.strip-btn:hover {
    background: #fee2e2;
    color: #b91c1c;
    border-color: #f87171;
}
.editor-textarea {
    border-radius: 0 0 6px 6px !important;
    border-top: 1px solid #cbd5e1 !important;
    font-family: inherit;
    font-size: 13.5px;
    line-height: 1.6;
}
.img-preview-box {
    width: 100%;
    height: 180px;
    border-radius: 8px;
    border: 2px dashed #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #f8fafc;
    position: relative;
}
.img-preview-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);"><?php echo htmlspecialchars($title ?? 'Soạn Thảo Bài Viết'); ?></h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Soạn nội dung tin tức, kế hoạch hoặc hướng dẫn dành cho tiểu thương và người dân.</p>
    </div>
    <div>
        <a href="<?php echo BASE_URL; ?>admin/posts" class="btn btn-outline" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-arrow-left"></i> Quay lại Danh sách
        </a>
    </div>
</div>

<form action="<?php echo $post ? BASE_URL . 'admin/post_edit/' . $post['id'] : BASE_URL . 'admin/post_add'; ?>" method="POST" enctype="multipart/form-data">
    <div style="display: grid; grid-template-columns: 2.2fr 1fr; gap: 24px; align-items: start;">
        
        <!-- CỘT TRÁI: TIÊU ĐỀ & NỘI DUNG -->
        <div class="card" style="background: var(--bg-surface, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 8px; padding: 24px;">
            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 700; font-size: 13.5px;">Tiêu đề bài viết <span style="color:red">*</span></label>
                <input type="text" id="postTitleInput" name="post_title" class="form-control" style="font-size: 15px; font-weight: 600;" value="<?php echo htmlspecialchars($post['post_title'] ?? ''); ?>" required placeholder="Ví dụ: Kế hoạch nâng cấp hệ thống PCCC khu chợ B..." oninput="autoGenerateSlug(this.value)">
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 600; font-size: 12.5px; color: var(--text-muted);">Đường dẫn tĩnh (Slug URL)</label>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 12px; color: var(--text-muted);"><?php echo BASE_URL; ?>home/post_detail/</span>
                    <input type="text" id="postSlugInput" name="post_slug" class="form-control form-control-sm" value="<?php echo htmlspecialchars($post['post_slug'] ?? ''); ?>" placeholder="tu-dong-sinh-tu-tieu-de">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px;">Tóm tắt ngắn gọn bài viết</label>
                <textarea name="post_summary" class="form-control" rows="3" placeholder="Đoạn văn ngắn 2-3 câu giới thiệu nội dung chính (sẽ hiển thị ở danh sách bài viết và thẻ mô tả SEO)..."><?php echo htmlspecialchars($post['post_summary'] ?? ''); ?></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label class="form-label" style="font-weight: 700; font-size: 13.5px; margin: 0;">Nội dung chi tiết bài viết <span style="color:red">*</span></label>
                    <div style="font-size: 11.5px; color: var(--text-muted);">
                        <i class="fa-solid fa-keyboard me-1"></i>Hỗ trợ <code>Ctrl+Z</code> và <code>Ctrl+Y</code>
                    </div>
                </div>

                <!-- THANH CÔNG CỤ SOẠN THẢO -->
                <div class="editor-toolbar">
                    <button type="button" class="editor-btn" onclick="undoContent()" title="Hoàn tác (Ctrl+Z)"><i class="fa-solid fa-rotate-left"></i></button>
                    <button type="button" class="editor-btn" onclick="redoContent()" title="Làm lại (Ctrl+Y)"><i class="fa-solid fa-rotate-right"></i></button>
                    <span style="color: #cbd5e1; margin: 0 2px;">|</span>
                    <button type="button" class="editor-btn" onclick="formatContent('b')" title="In đậm"><b>B</b></button>
                    <button type="button" class="editor-btn" onclick="formatContent('i')" title="In nghiêng"><i>I</i></button>
                    <button type="button" class="editor-btn" onclick="formatContent('u')" title="Gạch chân"><u>U</u></button>
                    <span style="color: #cbd5e1; margin: 0 2px;">|</span>
                    <button type="button" class="editor-btn" onclick="formatContent('h2')" title="Tiêu đề mục lớn">H2</button>
                    <button type="button" class="editor-btn" onclick="formatContent('h3')" title="Tiêu đề mục vừa">H3</button>
                    <button type="button" class="editor-btn" onclick="formatContent('p')" title="Đoạn văn">P</button>
                    <button type="button" class="editor-btn" onclick="formatContent('list')" title="Danh sách gạch đầu dòng"><i class="fa-solid fa-list-ul"></i></button>
                    <span style="color: #cbd5e1; margin: 0 2px;">|</span>
                    <button type="button" class="editor-btn" style="background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe;" onclick="triggerInlineImageUpload()" title="Chèn ảnh vào vị trí con trỏ trong bài viết">
                        <i class="fa-solid fa-image"></i> Chèn Hình Ảnh
                    </button>
                    <button type="button" class="editor-btn" style="background:#f0fdf4; color:#15803d; border-color:#bbf7d0;" onclick="insertImageGallery()" title="Chèn lưới 2 ảnh cạnh nhau">
                        <i class="fa-solid fa-images"></i> Lưới 2 Ảnh
                    </button>
                    <span style="color: #cbd5e1; margin: 0 2px;">|</span>
                    <button type="button" class="editor-btn strip-btn" onclick="stripFormatting()" title="Gỡ sạch thẻ định dạng khỏi vùng bôi đen">
                        <i class="fa-solid fa-eraser"></i> Gỡ định dạng
                    </button>
                </div>

                <!-- Input upload ảnh ẩn dùng cho chèn ảnh nội dung -->
                <input type="file" id="inlineImageFileInput" style="display:none;" accept="image/*" onchange="handleInlineImageUpload(this)">

                <textarea id="postContentArea" name="post_content" class="form-control editor-textarea" rows="16" required placeholder="Nhập nội dung bài viết chi tiết tại đây..."><?php echo htmlspecialchars($post['post_content'] ?? ''); ?></textarea>

            </div>
        </div>

        <!-- CỘT PHẢI: ẢNH ĐẠI DIỆN & TRẠNG THÁI XUẤT BẢN -->
        <div>
            <!-- Khối Xuất bản -->
            <div class="card" style="background: var(--bg-surface, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                <h5 style="margin-top: 0; margin-bottom: 14px; font-weight: 700; color: var(--text-heading); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <i class="fa-solid fa-paper-plane me-1"></i> Xuất Bản
                </h5>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="font-weight: 600; font-size: 13px;">Trạng thái bài viết</label>
                    <select name="post_status" class="form-control" style="font-weight: 600;">
                        <option value="1" <?php echo (($post['post_status'] ?? 1) == 1) ? 'selected' : ''; ?>>🟢 Xuất bản công khai</option>
                        <option value="0" <?php echo (($post['post_status'] ?? 1) == 0) ? 'selected' : ''; ?>>⚪ Lưu bản nháp (Ẩn)</option>
                    </select>
                </div>

                <?php if ($post): ?>
                    <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;">
                        <div>Ngày tạo: <b><?php echo date('d/m/Y H:i', strtotime($post['created_at'])); ?></b></div>
                        <div>Cập nhật: <b><?php echo date('d/m/Y H:i', strtotime($post['updated_at'])); ?></b></div>
                    </div>
                <?php endif; ?>

                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; height: 40px; font-weight: 700; font-size: 14px;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> <?php echo $post ? 'Lưu Thay Đổi' : 'Đăng Bài Viết'; ?>
                    </button>
                    <?php if ($post): ?>
                        <a href="<?php echo BASE_URL; ?>home/post_detail/<?php echo $post['post_slug']; ?>" target="_blank" class="btn btn-outline" style="width: 100%; height: 36px; display: inline-flex; align-items: center; justify-content: center; gap: 6px; font-size: 13px;">
                            <i class="fa-solid fa-eye"></i> Xem Bài Viết Trên Web
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Khối Ảnh Đại Diện -->
            <div class="card" style="background: var(--bg-surface, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 8px; padding: 20px;">
                <h5 style="margin-top: 0; margin-bottom: 14px; font-weight: 700; color: var(--text-heading); border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <i class="fa-solid fa-image me-1"></i> Ảnh Đại Diện (Thumbnail)
                </h5>

                <div class="img-preview-box" id="previewContainer" style="margin-bottom: 14px;">
                    <?php if (!empty($post['post_image'])): ?>
                        <img id="previewImg" src="<?php echo htmlspecialchars($post['post_image']); ?>" alt="Preview">
                    <?php else: ?>
                        <div id="noImgText" style="text-align: center; color: var(--text-muted); font-size: 12.5px;">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size: 28px; opacity: 0.4; margin-bottom: 6px; display: block;"></i>
                            Chưa có ảnh đại diện
                        </div>
                        <img id="previewImg" src="" alt="Preview" style="display: none;">
                    <?php endif; ?>
                </div>

                <div class="form-group" style="margin-bottom: 12px;">
                    <label class="form-label" style="font-weight: 600; font-size: 12px;">Tải ảnh mới từ máy tính</label>
                    <input type="file" name="post_image" id="fileInput" class="form-control" accept="image/*" onchange="handleFileSelect(this)">
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-weight: 600; font-size: 12px; color: var(--text-muted);">Hoặc dán URL hình ảnh trực tiếp</label>
                    <input type="text" name="image_url" id="imageUrlInput" class="form-control form-control-sm" value="<?php echo htmlspecialchars($post['post_image'] ?? ''); ?>" placeholder="https://..." oninput="handleUrlInput(this.value)">
                </div>
            </div>

        </div>

    </div>
</form>

<script>
// Quản lý Lịch sử Hoàn tác (Undo/Redo Stack)
class InputHistoryManager {
    constructor(textarea) {
        this.textarea = textarea;
        this.undoStack = [textarea.value];
        this.redoStack = [];
        this.isTypingTimer = null;

        textarea.addEventListener('input', () => {
            clearTimeout(this.isTypingTimer);
            this.isTypingTimer = setTimeout(() => {
                this.saveState();
            }, 300);
        });

        textarea.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'z' && !e.shiftKey) {
                e.preventDefault();
                this.undo();
            } else if ((e.ctrlKey || e.metaKey) && (e.key.toLowerCase() === 'y' || (e.shiftKey && e.key.toLowerCase() === 'z'))) {
                e.preventDefault();
                this.redo();
            }
        });
    }

    saveState() {
        const val = this.textarea.value;
        if (this.undoStack[this.undoStack.length - 1] !== val) {
            this.undoStack.push(val);
            if (this.undoStack.length > 50) this.undoStack.shift();
            this.redoStack = [];
        }
    }

    undo() {
        if (this.undoStack.length > 1) {
            const current = this.undoStack.pop();
            this.redoStack.push(current);
            const prev = this.undoStack[this.undoStack.length - 1];
            this.textarea.value = prev;
        }
    }

    redo() {
        if (this.redoStack.length > 0) {
            const next = this.redoStack.pop();
            this.undoStack.push(next);
            this.textarea.value = next;
        }
    }
}

const historyManager = new InputHistoryManager(document.getElementById('postContentArea'));

function undoContent() { historyManager.undo(); }
function redoContent() { historyManager.redo(); }

// Tự động sinh Slug URL
function autoGenerateSlug(str) {
    <?php if (!$post): ?>
    let slug = str.toLowerCase().trim();
    slug = slug.replace(/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/g, 'a');
    slug = slug.replace(/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/g, 'e');
    slug = slug.replace(/(ì|í|ị|ỉ|ĩ)/g, 'i');
    slug = slug.replace(/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/g, 'o');
    slug = slug.replace(/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/g, 'u');
    slug = slug.replace(/(ỳ|ý|ỵ|ỷ|ỹ)/g, 'y');
    slug = slug.replace(/(đ)/g, 'd');
    slug = slug.replace(/[^a-z0-9-\s]/g, '');
    slug = slug.replace(/[\s-]+/g, '-');
    slug = slug.replace(/^-+|-+$/g, '');
    document.getElementById('postSlugInput').value = slug;
    <?php endif; ?>
}

// Định dạng văn bản nhanh
function formatContent(tag) {
    const area = document.getElementById('postContentArea');
    const start = area.selectionStart;
    const end = area.selectionEnd;
    const sel = area.value.substring(start, end);
    let replacement = '';

    if (tag === 'b') {
        replacement = `<b>${sel || 'Văn bản in đậm'}</b>`;
    } else if (tag === 'i') {
        replacement = `<i>${sel || 'Văn bản in nghiêng'}</i>`;
    } else if (tag === 'u') {
        replacement = `<u>${sel || 'Văn bản gạch chân'}</u>`;
    } else if (tag === 'h2') {
        replacement = `\n<h2>${sel || 'Tiêu đề mục H2'}</h2>\n`;
    } else if (tag === 'h3') {
        replacement = `\n<h3>${sel || 'Tiêu đề mục H3'}</h3>\n`;
    } else if (tag === 'p') {
        replacement = `\n<p>${sel || 'Nội dung đoạn văn...'}</p>\n`;
    } else if (tag === 'list') {
        if (sel) {
            const lines = sel.split('\n').filter(l => l.trim().length > 0);
            replacement = '\n<ul>\n' + lines.map(l => `    <li>${l.trim()}</li>`).join('\n') + '\n</ul>\n';
        } else {
            replacement = '\n<ul>\n    <li>Nội dung mục 1</li>\n    <li>Nội dung mục 2</li>\n</ul>\n';
        }
    }

    area.setRangeText(replacement, start, end, 'select');
    historyManager.saveState();
    area.focus();
}

// Gỡ bỏ thẻ HTML khỏi đoạn bôi đen
function stripFormatting() {
    const area = document.getElementById('postContentArea');
    const start = area.selectionStart;
    const end = area.selectionEnd;
    const sel = area.value.substring(start, end);

    if (!sel) {
        Swal.fire({
            icon: 'info',
            title: 'Chưa bôi đen văn bản',
            text: 'Vui lòng dùng chuột bôi đen đoạn văn bản cần gỡ định dạng trước khi bấm nút này.',
            timer: 2500,
            showConfirmButton: false
        });
        return;
    }

    const stripped = sel.replace(/<[^>]*>/g, '');
    area.setRangeText(stripped, start, end, 'select');
    historyManager.saveState();
    area.focus();
}

// Xem trước ảnh khi chọn file
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('previewImg');
            const noImg = document.getElementById('noImgText');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (noImg) noImg.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Xem trước ảnh khi gõ URL
function handleUrlInput(url) {
    if (url.trim().length > 5) {
        const preview = document.getElementById('previewImg');
        const noImg = document.getElementById('noImgText');
        preview.src = url;
        preview.style.display = 'block';
        if (noImg) noImg.style.display = 'none';
    }
}

// Mở hộp thoại chọn tải ảnh từ máy tính hoặc dán URL ảnh
function triggerInlineImageUpload() {
    Swal.fire({
        title: 'Chèn Hình Ảnh Vào Bài Viết',
        html: `
            <div style="text-align: left; font-size: 13.5px;">
                <p style="color: #64748b; margin-bottom: 14px;">Bạn có thể tải ảnh trực tiếp từ máy tính hoặc dán link ảnh từ nguồn bên ngoài:</p>
                <div style="display: flex; gap: 10px; margin-bottom: 14px;">
                    <button type="button" class="btn btn-primary btn-sm" style="flex: 1; padding: 8px;" onclick="Swal.close(); document.getElementById('inlineImageFileInput').click();">
                        <i class="fa-solid fa-cloud-arrow-up me-1"></i> Chọn tệp ảnh từ máy tính
                    </button>
                </div>
                <div style="border-top: 1px dashed #cbd5e1; padding-top: 12px; margin-top: 12px;">
                    <label style="font-weight: 600; display: block; margin-bottom: 4px;">Hoặc dán URL hình ảnh:</label>
                    <input type="text" id="swalImgUrl" class="form-control form-control-sm" placeholder="https://..." style="margin-bottom: 8px;">
                    <label style="font-weight: 600; display: block; margin-bottom: 4px;">Chú thích ảnh (Caption):</label>
                    <input type="text" id="swalImgCaption" class="form-control form-control-sm" placeholder="Ví dụ: Hình ảnh khu vực kinh doanh...">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Chèn ảnh URL',
        cancelButtonText: 'Đóng',
        preConfirm: () => {
            const url = document.getElementById('swalImgUrl').value.trim();
            const caption = document.getElementById('swalImgCaption').value.trim();
            if (!url) {
                Swal.showValidationMessage('Vui lòng nhập URL ảnh hoặc chọn Tải ảnh từ máy tính');
                return false;
            }
            return { url, caption };
        }
    }).then((res) => {
        if (res.isConfirmed && res.value) {
            insertFigureHtml(res.value.url, res.value.caption);
        }
    });
}

// Xử lý upload ảnh từ máy tính qua AJAX và chèn ngay vào nội dung
function handleInlineImageUpload(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const formData = new FormData();
    formData.append('inline_image', file);

    Swal.fire({
        title: 'Đang tải ảnh lên...',
        text: 'Vui lòng chờ trong giây lát',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch('<?php echo BASE_URL; ?>admin/post_upload_inline_image', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 200) {
            Swal.fire({
                title: 'Tải ảnh thành công!',
                text: 'Nhập chú thích ảnh (tùy chọn):',
                input: 'text',
                inputPlaceholder: 'Ví dụ: Ban Quản lý kiểm tra an toàn thực phẩm...',
                showCancelButton: true,
                confirmButtonText: 'Chèn vào bài viết',
                cancelButtonText: 'Bỏ qua chú thích'
            }).then((capRes) => {
                const caption = capRes.value ? capRes.value.trim() : '';
                insertFigureHtml(data.url, caption);
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Lỗi tải ảnh', text: data.message || 'Không thể tải ảnh lên.' });
        }
    })
    .catch(err => {
        Swal.fire({ icon: 'error', title: 'Lỗi kết nối', text: 'Có lỗi xảy ra khi tải ảnh lên máy chủ.' });
    })
    .finally(() => {
        input.value = '';
    });
}

// Hàm chèn mã thẻ <figure> vào vị trí con trỏ
function insertFigureHtml(url, caption = '') {
    const area = document.getElementById('postContentArea');
    const start = area.selectionStart;
    const end = area.selectionEnd;
    const capHtml = caption ? `\n    <figcaption style="font-size: 13.5px; color: #64748b; font-style: italic; margin-top: 8px; text-align: center;">${caption}</figcaption>` : '';
    const figure = `\n<figure style="text-align: center; margin: 28px 0;">\n    <img src="${url}" alt="${caption || 'Hình ảnh bài viết'}" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">${capHtml}\n</figure>\n`;

    area.setRangeText(figure, start, end, 'end');
    historyManager.saveState();
    area.focus();
}

// Chèn bộ sưu tập / Lưới 2 ảnh cạnh nhau
function insertImageGallery() {
    const area = document.getElementById('postContentArea');
    const start = area.selectionStart;
    const end = area.selectionEnd;
    const gallery = `\n<div class="post-img-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin: 28px 0;">\n    <figure style="text-align: center; margin: 0;">\n        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?q=80&w=600" alt="Ảnh 1" style="width: 100%; height: 220px; object-fit: cover; border-radius: 8px;">\n        <figcaption style="font-size: 12.5px; color: #64748b; font-style: italic; margin-top: 6px;">Chú thích hình ảnh 1</figcaption>\n    </figure>\n    <figure style="text-align: center; margin: 0;">\n        <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?q=80&w=600" alt="Ảnh 2" style="width: 100%; height: 220px; object-fit: cover; border-radius: 8px;">\n        <figcaption style="font-size: 12.5px; color: #64748b; font-style: italic; margin-top: 6px;">Chú thích hình ảnh 2</figcaption>\n    </figure>\n</div>\n`;

    area.setRangeText(gallery, start, end, 'end');
    historyManager.saveState();
    area.focus();
}

</script>
