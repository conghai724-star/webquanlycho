<?php include "header.php";?>
<!-- end -->
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
            <h3 class="page-title">Mua hàng</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
               <li class="breadcrumb-item active">Mua hàng</li>
            </ul>
      </div>
   </div>
   <div class="row"  >
      <div class="col-sm-12">
         <div class="card card-table">
            <div class="card-header">
				<div class="row">
					<div class="col">
						<h5 class="card-title"><?php echo $pagetitle;?></h5>
					</div>
					<div class="col-auto">
						<a href="<?php echo XC_URL?>/app/orders/addorders" class="btn btn-primary btn-submit"><i class="fas fa-plus"></i> Thêm đơn mua hàng</a>
					</div>
				</div>
			</div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-center table-hover mb-0 datatable" id="table-user">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 10%">Ngày đơn hàng</th>
                           <th style="width: 10%">Số đơn hàng</th>
                           <th style="width: 10%">Ngày giao hàng</th>
                           <th style="width: 10%">Nhà cung cấp</th>
                           <th style="width: 10%">Diễn giải</th>
						   <th style="width: 10%">Giá trị đơn hàng</th>
						   <th style="width: 10%">Tình trạng</th>
                           <th style="width: 10%" class="text-right">Chức năng</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php 
							$i = 1;
							foreach($users as $user)
                           {
                           ?>
                        <tr>
                           <td>
                              <?php echo $i;?>
                           </td>
                           <td>
                              <?php echo $user->user_username;?>
                           </td>
                           <td>
						   <?php echo $user->user_email;?>
						   </td>
                           <td  class="text-right "><span class="text-<?php echo $user->group_class;?>"><?php echo $user->group_name;?></span></td>
                           <td><?php echo date("H:i d/m/Y",strtotime($user->user_register_time));?></td>
                           <td><span class="badge badge-pill bg-<?php echo $user->status_class;?>-light"><?php echo $user->status_label;?></span></td>
                           <td class="text-right">
                              <div class="btn-group">
								   <button type="button" id="btn-detail" class="btn btn-sm btn-success btn-detail" data-fullname="<?php echo $user->user_fullname;?>" data-username="<?php echo $user->user_username;?>" data-email="<?php echo $user->user_email?>" data-group="<?php echo $user_group;?>" data-phone="<?php echo $user->user_phone;?>" data-address="<?php echo $user->user_address?>" data-register-time="<?php echo $user->user_register_time;?>">Xem</button>
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