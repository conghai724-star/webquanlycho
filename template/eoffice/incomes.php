<?php include_once "header.php"; ?>
         
            <div class="content container-fluid">
               <div class="page-header">
                  <div class="row align-items-center">
                     <div class="col">
                        <h3 class="page-title">Danh sách phiếu thu</h3>
                        <ul class="breadcrumb">
                           <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
                           <li class="breadcrumb-item active">Phiếu thu</li>
                        </ul>
                     </div>
                     <div class="col-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tạo phiếu</button>
							<div class="dropdown-menu" style="margin: 0px;">
								<a class="dropdown-item" href="<?php echo XC_URL;?>/app/incomes/new?type=1">Thu tiền Khách hàng</a>
								<a class="dropdown-item" href="<?php echo XC_URL;?>/app/incomes/new?type=2">Thu hoàn ứng nhân viên</a>
								<a class="dropdown-item" href="<?php echo XC_URL;?>/app/incomes/new?type=4">Thu khác</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="<?php echo XC_URL;?>/app/incomes/new?type=3">Rút tiền về nhập quỹ</a>
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
                                       <th style="width: 10%">Số chứng từ</th>
                                       <th style="width: 10%">Ngày hạch toán</th>
                                       <th >Diễn giải</th>
                                       <th style="width: 10%">Số tiền</th>
                                       <th style="width: 15%">Đối tượng</th>
                                       <th style="width: 15%">Lý do thu</th>
                                       <th style="width: 7%" class="text-end">Chức năng</th>
                                    </tr>
                                 </thead>
                                 <tbody>
									<?php foreach($incomes as $income)
									{
										
									?>
                                    <tr>
                                       <td><a href="#" style="font-weight: bold"><?php echo $income->income_no;?></a></td>
                                       <td>
											<?php echo date("d/m/Y",strtotime($income->income_accounting_date));?>
                                       </td>
                                       <td><?php echo $income->income_note;?></td>
                                       <td><?php echo number_format($income->sumamount,0);?></td>
                                       <td><?php echo $this->erp->get_object_name($income->income_to,$income->type_to);?></td>
                                       <td><?php echo $income->type_name;?></td>
                                       <td class="text-end">
										<div class="btn-group">
										   <button type="button" class="btn btn-sm btn-success">Xem</button>
										   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										   <span class="sr-only">Toggle Dropdown</span>
										   </button>
										   <div class="dropdown-menu">
											  <a class="dropdown-item" href="#"><i class="far fa-eye me-2"></i> Xem</a>
											  <a class="dropdown-item" href="#"><i class="far fa-edit me-2"></i> Sửa</a>
											  <a class="dropdown-item" href="customers/detail/5"><i class="far fa-trash-alt me-2"></i> Xoá</a>
											  <a class="dropdown-item btn-duplicate-customer" data-id="5" href="#"><i class="far fa-copy"></i> Nhân bản</a>
											  <div class="dropdown-divider"></div>
											  <a class="dropdown-item" href="#"><i class="far fa-check-circle me-2"></i> Phê duyệt</a>
											  <a class="dropdown-item" href="#"><i class="far fa-times-circle me-2"></i> Từ chối</a>
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
      <?php include_once "footer.php"; ?>