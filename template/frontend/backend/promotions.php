<?php include_once "header.php"; ?>
         
            <div class="content container-fluid">
               <div class="page-header">
                  <div class="row align-items-center">
                     <div class="col">
                        <h3 class="page-title">Chương trình khuyến mãi</h3>
                        <ul class="breadcrumb">
                           <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
                           <li class="breadcrumb-item active">Chương trình khuyến mãi</li>
                        </ul>
                     </div>
                     <div class="col-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tạo chương trình</button>
							<div class="dropdown-menu" style="margin: 0px;">
								<a class="dropdown-item" href="<?php echo XC_URL;?>/app/incomes/new?type=1">Khuyến mãi</a>
								<a class="dropdown-item" href="<?php echo XC_URL;?>/app/incomes/new?type=2">Mã giảm giá</a>
							</div>
						</div>
                        
                        <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
                        <i class="fas fa-filter"></i>
                        </a>
                     </div>
                  </div>
               </div>
               <div id="filter_inputs" class="card filter-card">
                  <div class="card-body pb-0">
                     <div class="row">
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Customer:</label>
                              <input type="text" class="form-control">
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <label>Status:</label>
                              <select class="select">
                                 <option>Select Status</option>
                                 <option value="Draft">Draft</option>
                                 <option value="Sent">Sent</option>
                                 <option value="Viewed">Viewed</option>
                                 <option value="Expired">Expired</option>
                                 <option value="Accepted">Accepted</option>
                                 <option value="Rejected">Rejected</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <label>From</label>
                              <div class="cal-icon">
                                 <input class="form-control datetimepicker" type="text">
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2">
                           <div class="form-group">
                              <label>To</label>
                              <div class="cal-icon">
                                 <input class="form-control datetimepicker" type="text">
                              </div>
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Estimate Number</label>
                              <input type="text" class="form-control">
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-12">
                     <div class="card card-table">
                        <div class="card-body">
                           <div class="table-responsive">
                              <table class="table table-stripped table-hover datatable">
                                 <thead class="thead-light">
                                    <tr>
                                       <th style="width: 5%">No.</th>
                                       <th>Tên chương trình</th>
                                       <th style="width: 10%">Loại ưu đãi</th>
									   <th style="width: 7%">Ngày tạo</th>
                                       <th style="width: 12%">Đối tượng</th>
                                       <th style="width: 7%">Số lượng</th>
                                       <th style="width: 7%">Ngày hết hạn</th>
                                       <th style="width: 7%" class="text-end">Chức năng</th>
                                    </tr>
                                 </thead>
                                 <tbody>
									<?php 
									$i = 1;
									foreach($promotions as $promo)
									{
										
									?>
                                    <tr>
                                       <td><span style="font-weight: bold"><?php echo $i;?></span></td>
                                       <td>
											<span style="font-weight: bold"><?php echo ($promo->promo_type == 2) ? '<span class="badge bg-primary">'.$promo->promo_code.'</span> '.$promo->promo_name : $promo->promo_name;?></span>
                                       </td>
                                       <td><?php echo ($promo->promo_type == 1)? "Khuyến mãi" : "Mã giảm giá";?></td>
                                       <td><?php echo date("d/m/Y",strtotime($promo->promo_created_time));?></td>
                                       <td>
											<?php 
												//1 tất cả đơn hàng, 2 đơn hàng theo giá trị, 3 khách hàng, 4 sản phẩm
												if($promo->promo_for == 1)
												{
													echo "Tất cả đơn hàng";
												}
												elseif($promo->promo_for == 2)
												{
													echo "Theo giá trị";
												}
												elseif($promo->promo_for == 3)
												{
													echo "Theo Khách hàng";
												}
												elseif($promo->promo_for == 4)
												{
													echo "Theo sản phẩm";
												}
												else
												{
													echo "Tất cả đơn hàng";
												}
											?>
										</td>
                                       <td><?php echo ($promo->promo_qty == 0)? number_format($promo->promo_used,0)."/~" : number_format($promo->promo_used,0)."/".number_format($promo->promo_qty,0);?></td>
                                       <td><?php echo ($promo->promo_expried == 1)? date("d/m/Y",strtotime($promo->promo_to)) : "Không hết hạn";?></td>
                                       <td class="text-end">
										<div class="btn-group">
										   <button type="button" class="btn btn-sm btn-success">Xem</button>
										   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										   <span class="sr-only">Toggle Dropdown</span>
										   </button>
										   <div class="dropdown-menu">
											  <a class="dropdown-item" href="#"><i class="far fa-eye me-2"></i> Xem</a>
											  <a class="dropdown-item" href="#"><i class="far fa-edit me-2"></i> Sửa</a>
											  <?php if($promo->promo_status == 1)
											  {
												?>
											  <a class="dropdown-item btn-deactive-customer" data-id="<?php echo $promo->id;?>" href="#"><i class="fas fa-times"></i> Ngừng sử dụng</a>
											 <?php
											  }
											  else
											  {
												  ?>
												 <a class="dropdown-item btn-active-customer" data-id="<?php echo $promo->id;?>" href="#"><i class="fas fa-check"></i> Kích hoạt</a>
												  <?php
											  }
											 ?>
											  <a class="dropdown-item btn-duplicate-customer" data-id="5" href="#"><i class="far fa-copy"></i> Nhân bản</a>
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
      <?php include_once "footer.php"; ?>