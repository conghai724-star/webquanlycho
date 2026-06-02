<?php include "header.php";?>
<!-- end -->
<div class="content container-fluid">
	<div class="page-header">
	  <div class="row align-items-center">
		 <div class="col">
			<h3 class="page-title">Danh sách thuốc</h3>
			<ul class="breadcrumb">
			   <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>/admin">Trang chủ</a></li>
			   <li class="breadcrumb-item active">Thuốc</li>
			</ul>
		 </div>
		 <div class="col-auto">
			<a href="<?php echo XC_URL?>/admin/products/new" class="btn btn-primary btn-submit"><i class="fas fa-plus"></i> Thêm thuốc</a>
			
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
                           <th>Tên hàng</th>
                           <th style="width: 10%">ĐVT</th>
                           <th style="width: 10%">Đơn giá</th>
                           <th style="width: 10%">Giảm khuyến mãi</th>
						   <th style="width: 15%">Nhóm sản phẩm</th>
						   
						   <th style="width: 10%">Trạng thái</th>
                           <th style="width: 7%" class="text-right">Chức năng</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							$i = 1;
							foreach($products as $product)
                           {
                           ?>
							<tr>
							   <td>
								  <?php echo $i;?>
							   </td>
							   <td>
									<h2 class="table-avatar">
										<a href="#" class="avatar avatar-sm me-2">
											<img class="avatar-img rounded-circle" src="<?php echo XC_URL . '/uploads/products/'.$product->product_image ?? "";?>" alt="User Image">
										</a>
										<a href="#"><b><?php echo $product->product_name;?> <span><?php echo $product->product_code;?></span></b></a>
									</h2>
								</td>
							   <td>
									<?php echo $product->unit_name;?>
							   </td>
							   <td>
									<?php echo number_format($product->product_price,0); ?>
							   </td>
							   <td>
									<?php echo ($product->product_discount) ? number_format($product->product_discount,0) : ""; ?>
							   </td>
							   <td  class="text-right ">
									<?php echo $product->category_name;?>
							   </td>
							   
							   <!-- <td>
								<?php echo number_format($product->totalinstock,0);?>
							   </td> -->
							   <td>
									<?php if($product->product_status == 1)
									{	
									?>
										<span class="text-success">Tồn kho</span>
									<?php
									}
									else
									{
										?>
										<span class="text-danger">Hết</span>
										<?php
									}
									?>
								</td>
							   <td class="text-right">
								  <div class="btn-group">
									   <a href="<?php echo XC_URL?>/admin/products/update/<?php echo $product-> pid;?>" type="button" id="btn-detail" class="btn btn-sm btn-success btn-detail">Sửa</a>
									   <!-- <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									   <span class="sr-only">Toggle Dropdown</span>
									   </button> -->
									   <!-- <div class="dropdown-menu">
											<a class="dropdown-item btn-action" data-method="active"  href="#"  data-id="<?php echo $user->uid;?>" data-status="<?php echo $user->user_status;?>"><?php echo ($user->user_status == 1) ? "Ngưng hoạt động" : "Hoạt động";?></a>
											<a class="dropdown-item btn-action" href="#" data-method="role" data-id='<?php echo $user->uid;?>' data-status="<?php echo $user->user_status;?>">Phân lại quyền</a>
											<a class="dropdown-item btn-action" href="#" data-method="delete" data-id = '<?php echo $user->uid;?>' data-status="<?php echo $user->user_status;?>">Xóa</a>
									   </div> -->
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