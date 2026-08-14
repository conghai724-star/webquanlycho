<!-- Nạp FontAwesome & SweetAlert2 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.section-card {
    background: var(--bg-surface, #ffffff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 16px;
    position: relative;
    transition: all 0.2s ease-in-out;
}
.section-card:hover {
    border-color: var(--primary, #0f766e);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.drag-handle {
    cursor: move;
    color: var(--text-muted, #94a3b8);
    font-size: 16px;
}

.fmt-btn {
    font-size: 12px;
    padding: 4px 9px;
    border-radius: 4px;
    border: 1px solid var(--border-color, #cbd5e1);
    background: #ffffff;
    color: #334155;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.fmt-btn:hover {
    background: #f1f5f9;
    border-color: var(--primary, #0f766e);
    color: var(--primary, #0f766e);
}
.fmt-btn-clear {
    color: #dc2626;
    border-color: #fca5a5;
    background: #fff5f5;
}
.fmt-btn-clear:hover {
    background: #fee2e2;
    border-color: #ef4444;
    color: #b91c1c;
}
.fmt-divider {
    width: 1px;
    height: 20px;
    background-color: var(--border-color, #cbd5e1);
    margin: 0 2px;
}
</style>

<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
    <div>
        <h4 style="margin: 0; font-weight: 700; color: var(--text-heading);">Quản Lý Các Mục Bài Giới Thiệu Chợ</h4>
        <p style="margin: 4px 0 0 0; font-size: 13px; color: var(--text-muted);">Thêm, bớt, chỉnh sửa tiêu đề và định dạng phong phú (In đậm, In nghiêng, Danh sách, Gỡ thẻ nhanh, Phím tắt Ctrl+Z / Ctrl+Y).</p>
    </div>
    <div style="display: flex; gap: 8px;">
        <a href="<?php echo BASE_URL; ?>admin/banners" class="btn btn-outline" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-images"></i> Quản Lý Banner
        </a>
        <a href="<?php echo BASE_URL; ?>home/about" target="_blank" class="btn btn-primary" style="height: 36px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="fa-solid fa-eye"></i> Xem Trang Công Khai
        </a>
    </div>
</div>

<?php if (isset($_SESSION['flash_success'])): ?>
    <div style="background-color: rgba(46, 125, 50, 0.1); border: 1px solid rgba(46, 125, 50, 0.3); color: #2e7d32; padding: 12px 16px; border-radius: 6px; font-size: 13px; margin-bottom: 16px;">
        <i class="fa-solid fa-circle-check me-2"></i><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?>
    </div>
<?php endif; ?>

<form action="<?php echo BASE_URL; ?>admin/about_settings" method="POST" id="aboutSectionsForm">
    <div id="sectionsContainer">
        <?php if (!empty($sections)): ?>
            <?php foreach ($sections as $idx => $sec): ?>
                <div class="section-card" data-idx="<?php echo $idx; ?>">
                    <input type="hidden" name="sections[<?php echo $idx; ?>][id]" value="<?php echo $sec['id']; ?>">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                            <span class="drag-handle"><i class="fa-solid fa-grip-vertical"></i></span>
                            <span style="font-weight: 700; color: var(--primary, #0f766e); font-size: 14px;">Mục #<span class="section-num"><?php echo $idx + 1; ?></span></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <label style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Thứ tự:</label>
                                <input type="number" name="sections[<?php echo $idx; ?>][order]" value="<?php echo (int)($sec['section_order'] ?? ($idx + 1)); ?>" class="form-control" style="width: 70px; height: 32px; padding: 2px 8px; text-align: center; font-weight: 600;">
                            </div>
                            <button type="button" onclick="removeSection(this)" class="btn btn-outline btn-sm" style="color: #dc2626; border-color: #fca5a5; height: 32px; padding: 0 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-trash-can"></i> Xóa mục
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 12px;">
                        <label class="form-label" style="font-weight: 600; font-size: 13px;">Tiêu đề mục <span style="color:red">*</span></label>
                        <input type="text" name="sections[<?php echo $idx; ?>][title]" class="form-control undoable-input" value="<?php echo htmlspecialchars($sec['section_title']); ?>" required placeholder="Ví dụ: 1. Lịch sử hình thành & Phát triển">
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                            <label class="form-label" style="font-weight: 600; font-size: 13px; margin: 0;">Nội dung chi tiết mục <span style="color:red">*</span></label>
                            
                            <!-- THANH CÔNG CỤ ĐỊNH DẠNG VĂN BẢN VÀ GỠ THẺ NHANH -->
                            <div style="display: flex; gap: 4px; flex-wrap: wrap; align-items: center;">
                                <button type="button" class="fmt-btn" onclick="triggerUndo(this)" title="Hoàn tác (Ctrl+Z)"><i class="fa-solid fa-rotate-left"></i> Hoàn tác</button>
                                <button type="button" class="fmt-btn" onclick="triggerRedo(this)" title="Làm lại (Ctrl+Y)"><i class="fa-solid fa-rotate-right"></i> Làm lại</button>
                                
                                <div class="fmt-divider"></div>

                                <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'b')" title="In đậm / Bỏ in đậm"><i class="fa-solid fa-bold"></i> Đậm</button>
                                <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'i')" title="In nghiêng / Bỏ in nghiêng"><i class="fa-solid fa-italic"></i> Nghiêng</button>
                                <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'u')" title="Gạch chân / Bỏ gạch chân"><i class="fa-solid fa-underline"></i> Gạch chân</button>
                                <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'ul')" title="Danh sách đầu dòng"><i class="fa-solid fa-list-ul"></i> Danh sách</button>
                                <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'highlight')" title="Chữ màu nổi bật" style="color: #0f766e;"><i class="fa-solid fa-highlighter"></i> Nổi bật</button>

                                <div class="fmt-divider"></div>

                                <!-- NÚT BÔI ĐEN VÀ GỠ NHANH TẤT CẢ THẺ ĐỊNH DẠNG -->
                                <button type="button" class="fmt-btn fmt-btn-clear" onclick="formatTextarea(this, 'clear')" title="Bôi đen và bấm nút này để gỡ sạch tất cả các thẻ <> định dạng"><i class="fa-solid fa-eraser"></i> Gỡ định dạng</button>
                            </div>
                        </div>
                        <textarea name="sections[<?php echo $idx; ?>][content]" class="form-control section-content-input undoable-input" rows="5" style="line-height: 1.6; font-size: 13px;" required placeholder="Nhập nội dung chi tiết của mục này..."><?php echo htmlspecialchars($sec['section_content']); ?></textarea>
                        <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">
                            <i class="fa-solid fa-circle-info me-1" style="color: #0f766e;"></i> <b>Mẹo gỡ định dạng nhanh:</b> Bôi đen đoạn chữ có chứa các thẻ <code>&lt;b&gt;</code>, <code>&lt;i&gt;</code>, <code>&lt;ul&gt;</code>... rồi bấm <b>[<i class="fa-solid fa-eraser"></i> Gỡ định dạng]</b> để làm sạch thẻ ngay lập tức mà không cần xóa tay từng dấu ngoặc nhọn.
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 20px;">
        <button type="button" onclick="addNewSection()" class="btn btn-outline" style="border-style: dashed; color: var(--primary, #0f766e); font-weight: 600;">
            <i class="fa-solid fa-plus me-1"></i> Thêm Mục Nội Dung Mới
        </button>

        <div style="display: flex; gap: 10px;">
            <a href="<?php echo BASE_URL; ?>admin/banners" class="btn btn-outline">Hủy bỏ</a>
            <button type="submit" class="btn btn-primary" style="padding: 8px 24px; font-weight: 600;">
                <i class="fa-solid fa-floppy-disk me-1"></i> Lưu Tất Cả Thay Đổi
            </button>
        </div>
    </div>
</form>

<script>
var sectionCount = <?php echo count($sections ?? []); ?>;

// Bộ quản lý Lịch sử Hoàn tác (Undo / Redo History Manager)
class InputHistoryManager {
    constructor(element) {
        this.element = element;
        this.history = [element.value];
        this.cursorHistory = [element.selectionStart || 0];
        this.historyIndex = 0;
        this.maxHistory = 60;
        this.isPerformingHistory = false;
        this.debounceTimer = null;

        this.element.addEventListener('input', () => {
            if (this.isPerformingHistory) return;
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.recordState();
            }, 250);
        });

        this.element.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                if (e.key.toLowerCase() === 'z' && !e.shiftKey) {
                    e.preventDefault();
                    this.undo();
                } else if (e.key.toLowerCase() === 'y' || (e.key.toLowerCase() === 'z' && e.shiftKey)) {
                    e.preventDefault();
                    this.redo();
                }
            }
        });
    }

    recordState() {
        var val = this.element.value;
        if (this.history[this.historyIndex] === val) return;

        this.history = this.history.slice(0, this.historyIndex + 1);
        this.cursorHistory = this.cursorHistory.slice(0, this.historyIndex + 1);

        this.history.push(val);
        this.cursorHistory.push(this.element.selectionStart || val.length);

        if (this.history.length > this.maxHistory) {
            this.history.shift();
            this.cursorHistory.shift();
        } else {
            this.historyIndex++;
        }
    }

    undo() {
        this.recordState();
        if (this.historyIndex > 0) {
            this.historyIndex--;
            this.applyCurrentHistory();
        }
    }

    redo() {
        if (this.historyIndex < this.history.length - 1) {
            this.historyIndex++;
            this.applyCurrentHistory();
        }
    }

    applyCurrentHistory() {
        this.isPerformingHistory = true;
        this.element.value = this.history[this.historyIndex];
        var pos = this.cursorHistory[this.historyIndex] || this.element.value.length;
        this.element.focus();
        try {
            this.element.setSelectionRange(pos, pos);
        } catch (err) {}
        this.isPerformingHistory = false;
    }
}

// Khởi tạo Undo Manager cho từng ô nhập
var undoManagers = new WeakMap();
function bindUndoManagers() {
    document.querySelectorAll('.undoable-input').forEach(function(el) {
        if (!undoManagers.has(el)) {
            undoManagers.set(el, new InputHistoryManager(el));
        }
    });
}
bindUndoManagers();

function triggerUndo(btn) {
    var formGroup = btn.closest('.form-group');
    var textarea = formGroup.querySelector('textarea');
    if (textarea && undoManagers.has(textarea)) {
        undoManagers.get(textarea).undo();
    }
}

function triggerRedo(btn) {
    var formGroup = btn.closest('.form-group');
    var textarea = formGroup.querySelector('textarea');
    if (textarea && undoManagers.has(textarea)) {
        undoManagers.get(textarea).redo();
    }
}

function stripHtmlTags(str) {
    return (str || '').replace(/<\/?[^>]+(>|$)/gi, '');
}

function formatTextarea(btn, tag) {
    var formGroup = btn.closest('.form-group');
    var textarea = formGroup.querySelector('textarea');
    if (!textarea) return;

    if (undoManagers.has(textarea)) {
        undoManagers.get(textarea).recordState();
    }

    var start = textarea.selectionStart;
    var end = textarea.selectionEnd;
    var text = textarea.value;
    var selectedText = text.substring(start, end);

    var replacement = '';

    if (tag === 'clear') {
        // GỠ SẠCH TẤT CẢ CÁC THẺ HTML TRONG ĐOẠN BÔI ĐEN HOẶC TOÀN BỘ Ô NHẬP
        if (selectedText && selectedText.length > 0) {
            replacement = stripHtmlTags(selectedText);
        } else {
            // Nếu không bôi đen, gỡ sạch toàn bộ ô nhập
            textarea.value = stripHtmlTags(text);
            if (undoManagers.has(textarea)) {
                undoManagers.get(textarea).recordState();
            }
            textarea.focus();
            return;
        }
    } else if (tag === 'b') {
        // Bật / tắt thông minh thẻ in đậm
        var trimmed = selectedText.trim();
        if (/^<b>[\s\S]*<\/b>$/i.test(trimmed)) {
            replacement = trimmed.replace(/^<b>/i, '').replace(/<\/b>$/i, '');
        } else if (/^<strong>[\s\S]*<\/strong>$/i.test(trimmed)) {
            replacement = trimmed.replace(/^<strong>/i, '').replace(/<\/strong>$/i, '');
        } else {
            replacement = '<b>' + (selectedText || 'Văn bản in đậm') + '</b>';
        }
    } else if (tag === 'i') {
        // Bật / tắt thông minh thẻ in nghiêng
        var trimmed = selectedText.trim();
        if (/^<i>[\s\S]*<\/i>$/i.test(trimmed)) {
            replacement = trimmed.replace(/^<i>/i, '').replace(/<\/i>$/i, '');
        } else if (/^<em>[\s\S]*<\/em>$/i.test(trimmed)) {
            replacement = trimmed.replace(/^<em>/i, '').replace(/<\/em>$/i, '');
        } else {
            replacement = '<i>' + (selectedText || 'Văn bản in nghiêng') + '</i>';
        }
    } else if (tag === 'u') {
        // Bật / tắt thông minh thẻ gạch chân
        var trimmed = selectedText.trim();
        if (/^<u>[\s\S]*<\/u>$/i.test(trimmed)) {
            replacement = trimmed.replace(/^<u>/i, '').replace(/<\/u>$/i, '');
        } else {
            replacement = '<u>' + (selectedText || 'Văn bản gạch chân') + '</u>';
        }
    } else if (tag === 'ul') {
        if (selectedText) {
            var lines = selectedText.split('\n');
            replacement = '<ul>\n' + lines.map(function(l) { return '  <li>' + l.trim() + '</li>'; }).join('\n') + '\n</ul>';
        } else {
            replacement = '<ul>\n  <li><b>Mục 1</b>: Mô tả...</li>\n  <li><b>Mục 2</b>: Mô tả...</li>\n</ul>';
        }
    } else if (tag === 'highlight') {
        replacement = '<strong style="color: #0f766e;">' + (selectedText || 'Văn bản nổi bật') + '</strong>';
    }

    textarea.focus();
    // Sử dụng document.execCommand('insertText') để giữ Undo Stack tự nhiên của trình duyệt nếu hỗ trợ
    var executed = false;
    try {
        executed = document.execCommand('insertText', false, replacement);
    } catch (e) {}

    if (!executed) {
        textarea.value = text.substring(0, start) + replacement + text.substring(end);
        var newCursorPos = start + replacement.length;
        textarea.setSelectionRange(newCursorPos, newCursorPos);
    }

    if (undoManagers.has(textarea)) {
        undoManagers.get(textarea).recordState();
    }
}

function addNewSection() {
    var container = document.getElementById('sectionsContainer');
    var idx = sectionCount++;
    var num = container.children.length + 1;

    var html = `
    <div class="section-card" data-idx="${idx}">
        <input type="hidden" name="sections[${idx}][id]" value="0">
        
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 8px; flex: 1;">
                <span class="drag-handle"><i class="fa-solid fa-grip-vertical"></i></span>
                <span style="font-weight: 700; color: var(--primary, #0f766e); font-size: 14px;">Mục #<span class="section-num">${num}</span> (Mới)</span>
            </div>
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <label style="font-size: 12px; color: var(--text-muted); font-weight: 500;">Thứ tự:</label>
                    <input type="number" name="sections[${idx}][order]" value="${num}" class="form-control" style="width: 70px; height: 32px; padding: 2px 8px; text-align: center; font-weight: 600;">
                </div>
                <button type="button" onclick="removeSection(this)" class="btn btn-outline btn-sm" style="color: #dc2626; border-color: #fca5a5; height: 32px; padding: 0 10px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                    <i class="fa-solid fa-trash-can"></i> Xóa mục
                </button>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 12px;">
            <label class="form-label" style="font-weight: 600; font-size: 13px;">Tiêu đề mục <span style="color:red">*</span></label>
            <input type="text" name="sections[${idx}][title]" class="form-control undoable-input" required placeholder="Nhập tiêu đề mục (Ví dụ: 5. Tiêu chuẩn an toàn & Vệ sinh)">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                <label class="form-label" style="font-weight: 600; font-size: 13px; margin: 0;">Nội dung chi tiết mục <span style="color:red">*</span></label>
                <div style="display: flex; gap: 4px; flex-wrap: wrap; align-items: center;">
                    <button type="button" class="fmt-btn" onclick="triggerUndo(this)" title="Hoàn tác (Ctrl+Z)"><i class="fa-solid fa-rotate-left"></i> Hoàn tác</button>
                    <button type="button" class="fmt-btn" onclick="triggerRedo(this)" title="Làm lại (Ctrl+Y)"><i class="fa-solid fa-rotate-right"></i> Làm lại</button>
                    <div class="fmt-divider"></div>
                    <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'b')" title="In đậm / Bỏ in đậm"><i class="fa-solid fa-bold"></i> Đậm</button>
                    <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'i')" title="In nghiêng / Bỏ in nghiêng"><i class="fa-solid fa-italic"></i> Nghiêng</button>
                    <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'u')" title="Gạch chân / Bỏ gạch chân"><i class="fa-solid fa-underline"></i> Gạch chân</button>
                    <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'ul')" title="Danh sách đầu dòng"><i class="fa-solid fa-list-ul"></i> Danh sách</button>
                    <button type="button" class="fmt-btn" onclick="formatTextarea(this, 'highlight')" title="Chữ màu nổi bật" style="color: #0f766e;"><i class="fa-solid fa-highlighter"></i> Nổi bật</button>
                    <div class="fmt-divider"></div>
                    <button type="button" class="fmt-btn fmt-btn-clear" onclick="formatTextarea(this, 'clear')" title="Bôi đen và bấm nút này để gỡ sạch tất cả các thẻ <> định dạng"><i class="fa-solid fa-eraser"></i> Gỡ định dạng</button>
                </div>
            </div>
            <textarea name="sections[${idx}][content]" class="form-control section-content-input undoable-input" rows="5" style="line-height: 1.6; font-size: 13px;" required placeholder="Nhập nội dung chi tiết của mục..."></textarea>
            <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">
                <i class="fa-solid fa-circle-info me-1" style="color: #0f766e;"></i> <b>Mẹo gỡ định dạng nhanh:</b> Bôi đen đoạn chữ có chứa các thẻ <code>&lt;b&gt;</code>, <code>&lt;i&gt;</code>, <code>&lt;ul&gt;</code>... rồi bấm <b>[<i class="fa-solid fa-eraser"></i> Gỡ định dạng]</b> để làm sạch thẻ ngay lập tức mà không cần xóa tay từng dấu ngoặc nhọn.
            </small>
        </div>
    </div>
    `;

    container.insertAdjacentHTML('beforeend', html);
    updateSectionNumbers();
    bindUndoManagers();
}

function removeSection(btn) {
    var card = btn.closest('.section-card');
    if (confirm('Bạn có chắc chắn muốn xóa mục nội dung này?')) {
        card.remove();
        updateSectionNumbers();
    }
}

function updateSectionNumbers() {
    var container = document.getElementById('sectionsContainer');
    var cards = container.querySelectorAll('.section-card');
    cards.forEach(function(card, index) {
        var numEl = card.querySelector('.section-num');
        if (numEl) numEl.innerText = index + 1;
    });
}
</script>
