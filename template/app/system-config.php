<?php require "header.php"; ?>
<!-- <?php
$selected_config = null;
$config_payload = array();
if(isset($configs) && is_array($configs) && count($configs) > 0) {
    foreach($configs as $config_item) {
        $config_payload[$config_item->id] = array(
            'id' => $config_item->id,
            'key' => $config_item->config_key,
            'value' => $config_item->config_value
        );
        if(($selected_config_id != "" && $config_item->id == $selected_config_id) || ($selected_config === null && $selected_config_id == "")) {
            $selected_config = $config_item;
        }
    }
}
?> -->
<style>
    .config-list {
        max-height: 640px;
        overflow-y: auto;
    }
    .config-item {
        border: 0;
        border-bottom: 1px solid #edf0f5;
        border-radius: 0;
        cursor: pointer;
        padding: 14px 16px;
        text-align: left;
        width: 100%;
    }
    .config-item.active,
    .config-item:hover {
        background: #f2f7ff;
        color: var(--bs-primary);
    }
    .config-key {
        font-weight: 600;
        word-break: break-word;
    }
    .config-value-preview {
        color: #6c757d;
        display: block;
        font-size: 13px;
        margin-top: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .config-empty-state {
        align-items: center;
        color: #6c757d;
        display: flex;
        justify-content: center;
        min-height: 320px;
        text-align: center;
    }
</style>

<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="header-title">
                        <h4 class="card-title mb-0">Cấu hình tham số</h4>
                    </div>
                    <span class="badge bg-soft-primary text-primary">
                        <?php echo (isset($configs) && is_array($configs)) ? count($configs) : 0; ?> tham số
                    </span>
                </div>
                <div class="card-body">
                    <?php if(isset($updated) && $updated == "1"): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            <div>Cập nhật tham số thành công.</div>
                        </div>
                    <?php endif; ?>

                    <div class="row g-4">
                        <div class="col-lg-4 col-xl-3">
                            <div class="card mb-0">
                                <div class="card-header">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                                        <input type="search" class="form-control" id="configSearch" placeholder="Tìm tham số">
                                    </div>
                                </div>
                                <div class="card-body p-0 config-list" id="configList">
                                    <?php if(isset($configs) && is_array($configs) && count($configs) > 0): ?>
                                        <?php foreach($configs as $config_item): ?>
                                            <button type="button"
                                                    class="config-item <?php echo ($selected_config && $selected_config->id == $config_item->id) ? 'active' : ''; ?>"
                                                    data-id="<?php echo htmlspecialchars($config_item->id); ?>"
                                                    data-key="<?php echo htmlspecialchars($config_item->config_key); ?>">
                                                <span class="config-key"><?php echo htmlspecialchars($config_item->config_key); ?></span>
                                                <span class="config-value-preview"><?php echo htmlspecialchars($config_item->config_value); ?></span>
                                            </button>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="config-empty-state p-4">Chưa có tham số cấu hình.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 col-xl-9">
                            <div class="card mb-0">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="header-title">
                                        <h5 class="mb-0" id="selectedConfigTitle">
                                            <?php echo $selected_config ? htmlspecialchars($selected_config->config_key) : 'Chọn tham số'; ?>
                                        </h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php if($selected_config): ?>
                                        <form method="post" action="<?php echo XC_URL; ?>/admin/config" id="configForm">
                                            <input type="hidden" name="config_id" id="configId" value="<?php echo htmlspecialchars($selected_config->id); ?>">

                                            <div class="mb-3">
                                                <label class="form-label">Mã tham số</label>
                                                <input type="text" class="form-control" id="configKey" value="<?php echo htmlspecialchars($selected_config->config_key); ?>" readonly>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label">Giá trị tham số</label>
                                                <textarea class="form-control" name="config_value" id="configValue" rows="10"><?php echo htmlspecialchars($selected_config->config_value); ?></textarea>
                                            </div>

                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="reset" class="btn btn-light">
                                                    <i class="fa-solid fa-rotate-left me-1"></i> Khôi phục
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa-solid fa-floppy-disk me-1"></i> Lưu thay đổi
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <div class="config-empty-state">Chọn một tham số ở danh sách bên trái để chỉnh sửa.</div>
                                    <?php endif; ?>
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
jQuery(function($) {
    var configData = <?php echo json_encode($config_payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    var originalValue = $('#configValue').val();

    $('.config-item').on('click', function() {
        var $item = $(this);
        var config = configData[$item.data('id')];
        if (!config) {
            return;
        }
        $('.config-item').removeClass('active');
        $item.addClass('active');

        $('#configId').val(config.id);
        $('#configKey').val(config.key);
        $('#configValue').val(config.value);
        $('#selectedConfigTitle').text(config.key);
        originalValue = config.value;
    });

    $('#configForm').on('reset', function() {
        setTimeout(function() {
            $('#configValue').val(originalValue);
        }, 0);
    });

    $('#configSearch').on('input', function() {
        var keyword = $(this).val().toLowerCase();
        $('.config-item').each(function() {
            var config = configData[$(this).data('id')];
            var haystack = config ? (config.key + ' ' + config.value).toLowerCase() : '';
            $(this).toggle(haystack.indexOf(keyword) !== -1);
        });
    });
});
</script>

<?php require "footer.php"; ?>
