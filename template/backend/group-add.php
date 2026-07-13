<?php require "header.php"; ?>
<?php
$isEdit = isset($method) && $method === 'edit';
$isSuperAdmin = $isEdit && isset($group->id) && (int)$group->id === 1;
$isFrontendGroup = $isEdit && isset($group->id) && in_array((int)$group->id, array(2, 3, 4), true);
$isLockedGroup = $isSuperAdmin || $isFrontendGroup;
$selectedIds = isset($selected_permission_ids) && is_array($selected_permission_ids) ? $selected_permission_ids : array();
$permissionGroups = array();
foreach((isset($permissions) && is_array($permissions)) ? $permissions : array() as $permission){
    $parent = trim((string)$permission->parent_key) !== '' ? $permission->parent_key : 'other_section';
    if(!isset($permissionGroups[$parent])) $permissionGroups[$parent] = array();
    $permissionGroups[$parent][] = $permission;
}
$parentLabels = array(
    'employer_section' => 'Nhà tuyển dụng',
    'news_section' => 'Tin tức và sự kiện',
    'account_section' => 'Tài khoản và phân quyền',
    'system_section' => 'Cấu hình hệ thống',
    'other_section' => 'Chức năng quản trị'
);
?>
<script>
jQuery(function($){
    $('#permission-all').on('change', function(){ $('.permission-item').prop('checked', this.checked); });
    $('.permission-group-all').on('change', function(){
        $('.permission-item[data-group="' + $(this).data('group') + '"]').prop('checked', this.checked);
    });
    $('#btn-save-group').on('click', function(){
        var name = $.trim($('#group_name').val());
        if(name.length < 3){ Swal.fire({icon:'warning', text:'Tên nhóm quyền phải có ít nhất 3 ký tự.'}); return; }
        var data = $('#frm-group').serializeArray();
        $('.permission-item:checked').each(function(){ data.push({name:'permission_ids[]', value:this.value}); });
        var $button = $(this).prop('disabled', true);
        $.ajax({type:'POST', url:'<?php echo XC_URL; ?>/api/addGroup', data:data, dataType:'json'})
            .done(function(result){
                if(Number(result.status) === 200){ Swal.fire({icon:'success', title:result.message, timer:1200, showConfirmButton:false}).then(function(){ location.href=result.url; }); }
                else Swal.fire({icon:'error', title:'Không thể lưu', text:result.message || 'Có lỗi xảy ra.'});
            })
            .fail(function(xhr){ var r=xhr.responseJSON||{}; Swal.fire({icon:'error', title:'Không thể lưu', text:r.message||'Yêu cầu không hợp lệ.'}); })
            .always(function(){ $button.prop('disabled', false); });
    });
});
</script>
<div class="conatiner-fluid content-inner mt-n5 py-0">
 <div class="row"><div class="col-xl-12"><div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
   <div><h4 class="card-title mb-1"><?php echo htmlspecialchars($pagetitle, ENT_QUOTES, 'UTF-8'); ?></h4><small class="text-muted">Quyền được áp dụng cho toàn bộ trang và thao tác thuộc menu tương ứng.</small></div>
   <a href="<?php echo XC_URL; ?>/admin/groups" class="btn btn-secondary btn-sm">Quay lại</a>
  </div>
  <div class="card-body">
   <?php if($isSuperAdmin): ?><div class="alert alert-info">Super Admin luôn có toàn bộ quyền và không thể chỉnh sửa.</div><?php endif; ?>
   <?php if($isFrontendGroup): ?><div class="alert alert-warning">Đây là nhóm tài khoản ngoài trang chủ, không cấp quyền Admin trực tiếp cho nhóm này.</div><?php endif; ?>
   <form id="frm-group">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($admin_csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
    <input type="hidden" name="method" value="<?php echo $isEdit ? 'edit' : 'add'; ?>">
    <input type="hidden" name="group_id" value="<?php echo $isEdit ? (int)$group->id : 0; ?>">
    <div class="row mb-4">
     <div class="col-md-6"><label class="form-label" for="group_name">Tên nhóm quyền <span class="text-danger">*</span></label><input class="form-control" id="group_name" name="group_name" maxlength="255" value="<?php echo $isEdit ? htmlspecialchars($group->group_name, ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo $isLockedGroup ? 'disabled' : ''; ?>></div>
     <div class="col-md-3"><label class="form-label" for="group_class">Class CSS</label><input class="form-control" id="group_class" name="group_class" value="<?php echo $isEdit ? htmlspecialchars($group->group_class, ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo $isLockedGroup ? 'disabled' : ''; ?>></div>
     <div class="col-md-3"><label class="form-label" for="group_icon">Icon</label><input class="form-control" id="group_icon" name="group_icon" value="<?php echo $isEdit ? htmlspecialchars($group->group_icon, ENT_QUOTES, 'UTF-8') : ''; ?>" <?php echo $isLockedGroup ? 'disabled' : ''; ?>></div>
    </div>
    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3"><h5 class="mb-0">Quyền truy cập menu</h5><label class="form-check"><input type="checkbox" class="form-check-input" id="permission-all" <?php echo $isSuperAdmin ? 'checked' : ''; ?> <?php echo $isLockedGroup ? 'disabled' : ''; ?>> Chọn tất cả</label></div>
    <div class="row">
    <?php foreach($permissionGroups as $parent => $items): ?>
     <div class="col-lg-6 mb-3"><div class="border rounded p-3 h-100">
      <label class="form-check fw-bold mb-2"><input type="checkbox" class="form-check-input permission-group-all" data-group="<?php echo htmlspecialchars($parent, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $isSuperAdmin ? 'checked' : ''; ?> <?php echo $isLockedGroup ? 'disabled' : ''; ?>> <?php echo htmlspecialchars(isset($parentLabels[$parent]) ? $parentLabels[$parent] : $parent, ENT_QUOTES, 'UTF-8'); ?></label>
      <?php foreach($items as $permission): $checked=$isSuperAdmin || in_array((int)$permission->id, $selectedIds, true); ?>
       <label class="form-check mb-2"><input type="checkbox" class="form-check-input permission-item" data-group="<?php echo htmlspecialchars($parent, ENT_QUOTES, 'UTF-8'); ?>" value="<?php echo (int)$permission->id; ?>" <?php echo $checked ? 'checked' : ''; ?> <?php echo $isLockedGroup ? 'disabled' : ''; ?>> <?php echo htmlspecialchars($permission->permission_name, ENT_QUOTES, 'UTF-8'); ?> <code><?php echo htmlspecialchars($permission->permission_key, ENT_QUOTES, 'UTF-8'); ?></code></label>
      <?php endforeach; ?>
     </div></div>
    <?php endforeach; ?>
    </div>
    <?php if(!$isLockedGroup): ?><div class="text-end mt-3"><button type="button" id="btn-save-group" class="btn btn-primary">Lưu nhóm quyền</button></div><?php endif; ?>
   </form>
  </div>
 </div></div></div>
</div>
<?php require "footer.php"; ?>
