<?php
include_once 'header.php';
$customers = isset($job_support_customers) && is_array($job_support_customers) ? $job_support_customers : array();
$filters = isset($job_support_customer_filters) && is_array($job_support_customer_filters) ? $job_support_customer_filters : array();
$jobOptions = isset($job_support_job_options) && is_array($job_support_job_options) ? $job_support_job_options : array();
$page = isset($job_support_customer_page) ? max(1, (int)$job_support_customer_page) : 1;
$perPage = isset($job_support_customer_per_page) ? max(1, (int)$job_support_customer_per_page) : 20;
$totalCustomers = isset($job_support_customer_total) ? max(0, (int)$job_support_customer_total) : count($customers);
$totalPages = isset($job_support_customer_total_pages) ? max(1, (int)$job_support_customer_total_pages) : 1;
$rowOffset = ($page - 1) * $perPage;

function jobSupportAdminH($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function jobSupportAdminPageUrl($targetPage, $filters){
    $query = is_array($filters) ? $filters : array();
    $query['page'] = max(1, (int)$targetPage);
    foreach($query as $key => $value){
        if($value === '' || $value === 0 || $value === '0'){
            unset($query[$key]);
        }
    }
    return XC_URL.'/admin/jobsupportcustomers?'.http_build_query($query);
}

function jobSupportAdminJobUrl($jobId, $title){
    if((int)$jobId <= 0){
        return '#';
    }
    return general::getInstance()->permalink((int)$jobId, 'job_post');
}
?>

<style>
.jsc-filter-card{border:0;box-shadow:0 5px 22px rgba(37,55,80,.07)}
.jsc-filter-card .form-label{font-size:12px;font-weight:700;color:#526176;margin-bottom:6px}
.jsc-summary{display:flex;align-items:center;gap:10px;color:#64748b;font-size:13px}
.jsc-summary strong{color:#25364d;font-size:18px}
.jsc-contact a{color:#0d6efd;text-decoration:none}.jsc-contact a:hover{text-decoration:underline}
.jsc-job{min-width:220px;max-width:360px}.jsc-job a{font-weight:700;color:#244a7c;text-decoration:none}.jsc-job a:hover{color:#0d6efd;text-decoration:underline}
.jsc-table th{white-space:nowrap}.jsc-table td{vertical-align:middle}
@media(max-width:767px){.jsc-filter-actions .btn{width:100%}.jsc-job{min-width:190px}}
</style>

<div class="content container-fluid">
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Quản lý khách hàng</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active">Khách hàng gửi thông tin hỗ trợ tìm việc</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card jsc-filter-card mb-4">
        <div class="card-body">
            <form method="get" action="<?php echo XC_URL; ?>/admin/jobsupportcustomers" class="row g-3 align-items-end">
                <div class="col-xl-3 col-md-6">
                    <label class="form-label" for="filterFullName">Họ và tên</label>
                    <input class="form-control" id="filterFullName" type="text" name="full_name" value="<?php echo jobSupportAdminH(isset($filters['full_name']) ? $filters['full_name'] : ''); ?>" placeholder="Nhập họ và tên">
                </div>
                <div class="col-xl-2 col-md-6">
                    <label class="form-label" for="filterPhone">SĐT</label>
                    <input class="form-control" id="filterPhone" type="text" name="phone" value="<?php echo jobSupportAdminH(isset($filters['phone']) ? $filters['phone'] : ''); ?>" placeholder="Nhập số điện thoại">
                </div>
                <div class="col-xl-3 col-md-6">
                    <label class="form-label" for="filterEmail">Email</label>
                    <input class="form-control" id="filterEmail" type="text" name="email" value="<?php echo jobSupportAdminH(isset($filters['email']) ? $filters['email'] : ''); ?>" placeholder="Nhập email">
                </div>
                <div class="col-xl-4 col-md-6">
                    <label class="form-label" for="filterJobKeyword">Tìm theo bài tuyển dụng</label>
                    <input class="form-control" id="filterJobKeyword" type="text" name="job_keyword" value="<?php echo jobSupportAdminH(isset($filters['job_keyword']) ? $filters['job_keyword'] : ''); ?>" placeholder="Nhập tên bài tuyển dụng">
                </div>
                <div class="col-xl-4 col-md-6">
                    <label class="form-label" for="filterJobId">Lọc bài tuyển dụng</label>
                    <select class="form-select" id="filterJobId" name="job_id">
                        <option value="0">Tất cả bài tuyển dụng</option>
                        <?php foreach($jobOptions as $jobOption): ?>
                            <option value="<?php echo (int)$jobOption->id; ?>" <?php echo (int)(isset($filters['job_id']) ? $filters['job_id'] : 0) === (int)$jobOption->id ? 'selected' : ''; ?>>
                                <?php echo jobSupportAdminH($jobOption->title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <label class="form-label" for="filterDateFrom">Từ ngày</label>
                    <input class="form-control" id="filterDateFrom" type="date" name="date_from" value="<?php echo jobSupportAdminH(isset($filters['date_from']) ? $filters['date_from'] : ''); ?>">
                </div>
                <div class="col-xl-2 col-md-3 col-6">
                    <label class="form-label" for="filterDateTo">Đến ngày</label>
                    <input class="form-control" id="filterDateTo" type="date" name="date_to" value="<?php echo jobSupportAdminH(isset($filters['date_to']) ? $filters['date_to'] : ''); ?>">
                </div>
                <div class="col-xl-4 col-md-6 jsc-filter-actions">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-primary flex-grow-1" type="submit"><i class="fa-solid fa-magnifying-glass me-1"></i>Tìm kiếm</button>
                        <a class="btn btn-light border flex-grow-1" href="<?php echo XC_URL; ?>/admin/jobsupportcustomers"><i class="fa-solid fa-rotate-left me-1"></i>Làm mới</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-table">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="card-title mb-1">Danh sách khách hàng</h4>
                <small class="text-muted">Thông tin khách hàng đã gửi từ modal hỗ trợ tại trang chi tiết việc làm.</small>
            </div>
            <div class="jsc-summary"><span>Tổng cộng</span><strong><?php echo number_format($totalCustomers); ?></strong><span>khách hàng</span></div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-center mb-0 jsc-table">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Họ và tên</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Ngày gửi</th>
                            <th>Bài job</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($customers)): ?>
                        <?php $stt = $rowOffset + 1; foreach($customers as $customer): ?>
                            <tr>
                                <td><?php echo $stt++; ?></td>
                                <td><div class="fw-semibold text-dark"><?php echo jobSupportAdminH($customer->full_name); ?></div></td>
                                <td class="jsc-contact"><a href="tel:<?php echo jobSupportAdminH($customer->phone); ?>"><?php echo jobSupportAdminH($customer->phone); ?></a></td>
                                <td class="jsc-contact"><a href="mailto:<?php echo jobSupportAdminH($customer->email); ?>"><?php echo jobSupportAdminH($customer->email); ?></a></td>
                                <td><?php echo !empty($customer->created_at) ? jobSupportAdminH(date('d/m/Y H:i', strtotime($customer->created_at))) : ''; ?></td>
                                <td class="jsc-job">
                                    <?php if(!empty($customer->job_title)): ?>
                                        <a href="<?php echo jobSupportAdminH(jobSupportAdminJobUrl($customer->job_id, $customer->job_title)); ?>" target="_blank" rel="noopener">
                                            <?php echo jobSupportAdminH($customer->job_title); ?>
                                        </a>
                                        <small class="d-block text-muted">ID: <?php echo (int)$customer->job_id; ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Bài tuyển dụng #<?php echo (int)$customer->job_id; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-5">Không tìm thấy khách hàng phù hợp.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if($totalPages > 1): ?>
            <div class="card-footer">
                <nav aria-label="Phân trang khách hàng">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo jobSupportAdminH(jobSupportAdminPageUrl(max(1, $page - 1), $filters)); ?>">Trước</a>
                        </li>
                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        for($pageNumber = $startPage; $pageNumber <= $endPage; $pageNumber++):
                        ?>
                            <li class="page-item <?php echo $pageNumber === $page ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo jobSupportAdminH(jobSupportAdminPageUrl($pageNumber, $filters)); ?>"><?php echo $pageNumber; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo jobSupportAdminH(jobSupportAdminPageUrl(min($totalPages, $page + 1), $filters)); ?>">Sau</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once 'footer.php'; ?>
