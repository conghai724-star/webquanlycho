<?php include "header.php";?>
<!-- end -->
<div class="content container-fluid">
	<div class="page-header">
	  <div class="row align-items-center">
		 <div class="col">
			<h3 class="page-title">Danh sách báo giá</h3>
			<ul class="breadcrumb">
			   <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
			   <li class="breadcrumb-item active">Báo giá</li>
			</ul>
		 </div>
		 <div class="col-auto">
			<a href="<?php echo XC_URL?>/app/quotes/new" class="btn btn-primary btn-submit"><i class="fas fa-plus"></i> Tạo báo giá</a>
			
			<a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
			<i class="fas fa-filter"></i>
			</a>
		 </div>
	  </div>
   </div>
   <div class="row"  >
      <div class="col-sm-12">
         <div class="card card-table">
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-center table-hover mb-0 datatable" id="table-user">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 5%">No.</th>
                           <th style="width: 10%">Số báo giá</th>
                           <th style="width: 10%">Ngày báo giá</th>
                           <th style="width: 10%">Khách hàng</th>
                           <th style="width: 10%">Tổng cộng</th>
                           <th style="width: 10%">Giảm giá</th>
						   <th style="width: 10%">Nhân viên</th>
						   <th style="width: 10%">Tình trạng</th>
                           <th style="width: 10%" class="text-right">Chức năng</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							$i = 1;
							foreach($quotes as $quote)
                           {
                           ?>
							<tr>
							   <td>
								  <?php echo $i;?>
							   </td>
							   <td>
									<a href="#"><b><?php echo $quote->quote_code;?></b></a>
							   </td>
							   <td>
								<?php echo date("H:i d/m/Y",strtotime($quote->quote_created_time));?>
							   </td>
							   <td>
								<?php echo $quote->customer_name;?>
							   </td>
							   <td  class="text-right ">
									<?php echo number_format($quote->qtotal,0); ?>
							   </td>
							   
							   <td><?php echo number_format($quote->quote_discount,0); ?></td>
							   <td>
								<?php echo $quote->user_fullname;?>
							   </td>
							   <td><span class="badge badge-pill bg-<?php echo $quote->status_class;?>-light"><?php echo $quote->status_label;?></span></td>
							   <td class="text-right">
								  <div class="btn-group">
									   <button type="button" id="btn-detail" class="btn btn-sm btn-success btn-detail">Xem</button>
									   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									   <span class="sr-only">Toggle Dropdown</span>
									   </button>
									   <div class="dropdown-menu">
											<a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $user->uid;?>" data-status="<?php echo $user->user_status;?>"><?php echo ($user->user_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
											<a class="dropdown-item btn-action" href="#" data-method="role" data-id='<?php echo $user->uid;?>' data-status="<?php echo $user->user_status;?>">Phân lại quyền</a>
											<a class="dropdown-item btn-action" href="#" data-method="delete" data-id = '<?php echo $user->uid;?>' data-status="<?php echo $user->user_status;?>">Xóa</a>
									   </div>
									</div>
							   </td>
							</tr>
                        <?php
							$i++;
                           }
                           ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

</div>
<?php include "footer.php";?>