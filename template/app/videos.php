<?php include_once "header.php";
$videos = is_array($videos) ? $videos : array();
$video_employees = is_array($video_employees) ? $video_employees : array();
$video_edit = $video_edit ? $video_edit : (object) array(
	'id' => 0,
	'video_name' => '',
	'video_employee' => 0,
	'video_url' => '',
	'video_description' => '',
	'video_status' => 1
);
$page = isset($page) ? (int) $page : 1;
$per_page = isset($per_page) ? (int) $per_page : 20;
$total_videos = isset($total_videos) ? (int) $total_videos : count($videos);
$total_pages = isset($total_pages) ? (int) $total_pages : 1;
$row_offset = max(0, ($page - 1) * $per_page);

if (!function_exists('backendVideoTitleExcerpt')) {
	function backendVideoTitleExcerpt($value, $limit = 60) {
		$value = trim((string) $value);
		if ($value === '') {
			return '';
		}
		if (function_exists('mb_strlen') && function_exists('mb_substr')) {
			return mb_strlen($value, 'UTF-8') > $limit ? mb_substr($value, 0, $limit, 'UTF-8').'...' : $value;
		}
		return strlen($value) > $limit ? substr($value, 0, $limit).'...' : $value;
	}
}

if (!function_exists('backendVideoBuildUrl')) {
	function backendVideoBuildUrl($targetPage) {
		return XC_URL.'/admin/videos?page='.(int) $targetPage;
	}
}

if (!function_exists('backendVideoPaginationItems')) {
	function backendVideoPaginationItems($currentPage, $totalPages) {
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
.video-title-cell{
	max-width: 340px;
}
.video-title-text{
	display: inline-block;
	max-width: 100%;
	font-weight: 600;
	color: #213547;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	vertical-align: middle;
}
.video-description-text{
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
	color: #6c757d;
	font-size: 13px;
	margin-top: 6px;
}
.video-action-group{
	display: flex;
	align-items: center;
	gap: 8px;
	flex-wrap: wrap;
}
.video-icon-btn{
	width: 36px;
	height: 36px;
	border-radius: 12px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	border: 1px solid rgba(58, 79, 114, 0.12);
	background: #fff;
	color: #314866;
	transition: all .2s ease;
}
.video-icon-btn:hover{
	transform: translateY(-1px);
	color: #079aa2;
	border-color: rgba(7, 154, 162, 0.35);
}
.video-icon-btn.is-edit{
	color: #0d8b4c;
}
.video-icon-btn.is-delete{
	color: #dc3545;
}
.video-icon-btn.is-delete:hover{
	color: #b42318;
	border-color: rgba(220, 53, 69, 0.35);
}
.video-url-link{
	font-size: 13px;
	word-break: break-all;
}
.video-modal .modal-dialog{
	max-width: 860px;
}
</style>

<div class="content container-fluid">
	<div class="page-header">
		<div class="row align-items-center">
			<div class="col">
				<h3 class="page-title">Danh s&aacute;ch video</h3>
				<ul class="breadcrumb">
					<li class="breadcrumb-item active">Video</li>
				</ul>
			</div>
			<div class="col-auto">
				<button type="button" class="btn btn-primary" id="openVideoAddModal" data-bs-toggle="modal" data-bs-target="#videoFormModal">Th&ecirc;m m&#7899;i</button>
			</div>
		</div>
	</div>

	<?php if (!empty($video_flash)): ?>
		<div class="alert alert-<?php echo $video_flash['type'] === 'success' ? 'success' : 'info'; ?> rounded-3"><?php echo htmlspecialchars($video_flash['message'], ENT_QUOTES, 'UTF-8'); ?></div>
	<?php endif; ?>

	<div class="card">
		<div class="card-header">
			<div class="row align-items-center">
				<div class="col">
					<h4 class="card-title mb-0">Danh s&aacute;ch video</h4>
				</div>
				<div class="col-auto">
					<small class="text-muted">Hi&#7875;n th&#7883; <?php echo number_format($total_videos, 0, ',', '.'); ?> video</small>
				</div>
			</div>
		</div>
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-center table-hover mb-0">
					<thead class="thead-light">
						<tr>
							<th>STT</th>
							<th>Ti&ecirc;u &#273;&#7873;</th>
							<th>Nh&acirc;n s&#7921;</th>
							<th>Tr&#7841;ng th&aacute;i</th>
							<th>Ng&agrave;y t&#7841;o</th>
							<th>Thao t&aacute;c</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($videos)): ?>
							<?php $i = $row_offset + 1; ?>
							<?php foreach ($videos as $item): ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td class="video-title-cell">
										<div class="video-title-text" title="<?php echo htmlspecialchars($item->video_name, ENT_QUOTES, 'UTF-8'); ?>">
											<?php echo htmlspecialchars(backendVideoTitleExcerpt($item->video_name, 60), ENT_QUOTES, 'UTF-8'); ?>
										</div>
										<?php if (!empty($item->video_url)): ?>
											<div><a class="video-url-link" href="<?php echo htmlspecialchars($item->video_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo htmlspecialchars($item->video_url, ENT_QUOTES, 'UTF-8'); ?></a></div>
										<?php endif; ?>
										<?php if (!empty($item->video_description)): ?>
											<div class="video-description-text"><?php echo htmlspecialchars($item->video_description, ENT_QUOTES, 'UTF-8'); ?></div>
										<?php endif; ?>
									</td>
									<td><?php echo htmlspecialchars(isset($item->responsible_name) && $item->responsible_name !== '' ? $item->responsible_name : 'Chưa gắn', ENT_QUOTES, 'UTF-8'); ?></td>
									<td>
										<span class="badge bg-<?php echo (int) $item->video_status === 1 ? 'success' : 'secondary'; ?>">
											<?php echo (int) $item->video_status === 1 ? 'Hiển thị' : 'Ẩn'; ?>
										</span>
									</td>
									<td><?php echo htmlspecialchars(isset($item->video_created_at) ? $item->video_created_at : '', ENT_QUOTES, 'UTF-8'); ?></td>
									<td>
										<div class="video-action-group">
											<a href="<?php echo htmlspecialchars($item->video_url, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="video-icon-btn" title="Xem video" aria-label="Xem video">
												<i class="fa-regular fa-eye"></i>
											</a>
											<button
												type="button"
												class="video-icon-btn is-edit btn-edit-video"
												title="Chỉnh sửa"
												aria-label="Chỉnh sửa"
												data-bs-toggle="modal"
												data-bs-target="#videoFormModal"
												data-id="<?php echo (int) $item->id; ?>"
												data-name="<?php echo htmlspecialchars($item->video_name, ENT_QUOTES, 'UTF-8'); ?>"
												data-employee="<?php echo (int) $item->video_employee; ?>"
												data-url="<?php echo htmlspecialchars($item->video_url, ENT_QUOTES, 'UTF-8'); ?>"
												data-description="<?php echo htmlspecialchars($item->video_description, ENT_QUOTES, 'UTF-8'); ?>"
												data-status="<?php echo (int) $item->video_status; ?>">
												<i class="fa-regular fa-pen-to-square"></i>
											</button>
											<form method="post" class="d-inline">
												<input type="hidden" name="id" value="<?php echo (int) $item->id; ?>">
												<button type="submit" class="video-icon-btn is-delete" name="video_action" value="delete" onclick="return confirm('Xóa video này?');" title="Xóa" aria-label="Xóa">
													<i class="fa-regular fa-trash-can"></i>
												</button>
											</form>
										</div>
									</td>
								</tr>
								<?php $i++; ?>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="6" class="text-center py-4">Chưa có video nào.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<?php if ($total_pages > 1): ?>
				<div class="px-4 py-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
					<small class="text-muted">Tổng cộng <?php echo number_format($total_videos, 0, ',', '.'); ?> video, hiển thị <?php echo $per_page; ?> video mỗi trang.</small>
					<nav aria-label="Phân trang video">
						<ul class="pagination mb-0 justify-content-end flex-wrap">
							<li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
								<a class="page-link" href="<?php echo $page <= 1 ? '#' : htmlspecialchars(backendVideoBuildUrl($page - 1), ENT_QUOTES, 'UTF-8'); ?>">Trước</a>
							</li>
							<?php foreach (backendVideoPaginationItems($page, $total_pages) as $pagination_item): ?>
								<?php if ($pagination_item === 'ellipsis'): ?>
									<li class="page-item disabled"><span class="page-link">...</span></li>
								<?php else: ?>
									<li class="page-item <?php echo (int) $pagination_item === $page ? 'active' : ''; ?>">
										<a class="page-link" href="<?php echo htmlspecialchars(backendVideoBuildUrl($pagination_item), ENT_QUOTES, 'UTF-8'); ?>"><?php echo (int) $pagination_item; ?></a>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
							<li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
								<a class="page-link" href="<?php echo $page >= $total_pages ? '#' : htmlspecialchars(backendVideoBuildUrl($page + 1), ENT_QUOTES, 'UTF-8'); ?>">Sau</a>
							</li>
						</ul>
					</nav>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="modal fade video-modal" id="videoFormModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="videoFormModalLabel">Th&ecirc;m m&#7899;i video</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form method="post" id="videoForm">
					<input type="hidden" name="id" id="videoFormId" value="<?php echo (int) $video_edit->id; ?>">
					<div class="row">
						<div class="form-group col-lg-6 col-md-12">
							<label class="form-label">Ti&ecirc;u &#273;&#7873; video</label>
							<input type="text" class="form-control" name="video_name" id="videoFormName" value="<?php echo htmlspecialchars($video_edit->video_name, ENT_QUOTES, 'UTF-8'); ?>" required>
						</div>
						<div class="form-group col-lg-6 col-md-12">
							<label class="form-label">Nh&acirc;n s&#7921; ph&#7909; tr&aacute;ch</label>
							<select class="form-control" name="video_employee" id="videoFormEmployee">
								<option value="0">Chưa gắn nhân sự</option>
								<?php foreach ($video_employees as $employee): ?>
									<option value="<?php echo (int) $employee->id; ?>" <?php echo (int) $video_edit->video_employee === (int) $employee->id ? 'selected' : ''; ?>>
										<?php echo htmlspecialchars($employee->employee_name, ENT_QUOTES, 'UTF-8'); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="form-group col-lg-6 col-md-12">
							<label class="form-label">Link video</label>
							<input type="url" class="form-control" name="video_url" id="videoFormUrl" value="<?php echo htmlspecialchars($video_edit->video_url, ENT_QUOTES, 'UTF-8'); ?>" required>
						</div>
						<div class="form-group col-lg-6 col-md-12">
							<label class="form-label">Tr&#7841;ng th&aacute;i</label>
							<select class="form-control" name="video_status" id="videoFormStatus">
								<option value="1" <?php echo (int) $video_edit->video_status === 1 ? 'selected' : ''; ?>>Hiển thị</option>
								<option value="0" <?php echo (int) $video_edit->video_status === 0 ? 'selected' : ''; ?>>Ẩn</option>
							</select>
						</div>
						<div class="form-group col-12">
							<label class="form-label">M&ocirc; t&#7843;</label>
							<textarea class="form-control" name="video_description" id="videoFormDescription" rows="4"><?php echo htmlspecialchars($video_edit->video_description, ENT_QUOTES, 'UTF-8'); ?></textarea>
						</div>
					</div>
					<div class="text-end">
						<button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">&#272;&oacute;ng</button>
						<button type="submit" class="btn btn-primary btn-sm" name="video_action" value="save" id="videoFormSubmit">Th&ecirc;m m&#7899;i</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
(function($){
	function resetVideoForm() {
		$('#videoForm')[0].reset();
		$('#videoFormId').val(0);
		$('#videoFormEmployee').val('0');
		$('#videoFormStatus').val('1');
		$('#videoFormModalLabel').html('Th&ecirc;m m&#7899;i video');
		$('#videoFormSubmit').html('Th&ecirc;m m&#7899;i');
	}

	$(document).on('click', '#openVideoAddModal', function() {
		resetVideoForm();
	});

	$(document).on('click', '.btn-edit-video', function() {
		var button = $(this);
		$('#videoFormId').val(button.data('id'));
		$('#videoFormName').val(button.data('name'));
		$('#videoFormEmployee').val(String(button.data('employee')));
		$('#videoFormUrl').val(button.data('url'));
		$('#videoFormDescription').val(button.data('description'));
		$('#videoFormStatus').val(String(button.data('status')));
		$('#videoFormModalLabel').html('Ch&#7881;nh s&#7917;a video');
		$('#videoFormSubmit').html('L&#432;u c&#7853;p nh&#7853;t');
	});

	<?php if ((int) $video_edit->id > 0): ?>
	$(window).on('load', function() {
		var modalElement = document.getElementById('videoFormModal');
		if (modalElement && window.bootstrap && bootstrap.Modal) {
			var modal = new bootstrap.Modal(modalElement);
			modal.show();
			$('#videoFormModalLabel').html('Ch&#7881;nh s&#7917;a video');
			$('#videoFormSubmit').html('L&#432;u c&#7853;p nh&#7853;t');
		}
	});
	<?php endif; ?>
})(jQuery);
</script>

<?php include_once "footer.php"; ?>
