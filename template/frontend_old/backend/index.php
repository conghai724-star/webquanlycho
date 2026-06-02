
<?php include "header.php"; ?>
         
            <div class="content container-fluid">
               <div class="row">
                  
                  <div class="col-xl-3 col-sm-6 col-12">
                     <div class="card">
                        <div class="card-body">
                           <div class="dash-widget-header">
                              <span class="dash-widget-icon bg-2">
                              <i class="fas fa-users"></i>
                              </span>
                              <div class="dash-count">
                                 <div class="dash-title">Khách hàng đặt lịch khám</div>
                                 <div class="dash-counts">
                                    <p style='color:red;'>5</p>
                                 </div>
                              </div>
                           </div>
                           <div class="progress progress-sm mt-3">
                              <div class="progress-bar bg-6" role="progressbar" style="width: 65%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                           </div>
                        </div>
                     </div>
                  </div>
                  
               </div>
               <!-- <div class="row">
                  <div class="col-xl-7 d-flex">
                     <div class="card flex-fill">
                        <div class="card-header">
                           <div class="d-flex justify-content-between align-items-center">
                              <h5 class="card-title">Chỉ số bán hàng</h5>
                              <div class="dropdown">
                                 <button class="btn btn-white btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                 Tháng
                                 </button>
                                 <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                       <a href="javascript:void(0);" class="dropdown-item">Tuần</a>
                                    </li>
                                    <li>
                                       <a href="javascript:void(0);" class="dropdown-item">Tháng</a>
                                    </li>
                                    <li>
                                       <a href="javascript:void(0);" class="dropdown-item">Năm</a>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="card-body">
                           <div class="d-flex align-items-center justify-content-between flex-wrap flex-md-nowrap">
                              <div class="w-md-100 d-flex align-items-center mb-3">
                                 <div>
                                    <span>Total Sales</span>
                                    <p class="h3 text-primary me-5">$1000</p>
                                 </div>
                                 <div>
                                    <span>Receipts</span>
                                    <p class="h3 text-success me-5">$1000</p>
                                 </div>
                                 <div>
                                    <span>Expenses</span>
                                    <p class="h3 text-danger me-5">$300</p>
                                 </div>
                                 <div>
                                    <span>Earnings</span>
                                    <p class="h3 text-dark me-5">$700</p>
                                 </div>
                              </div>
                           </div>
                           <div id="sales_chart"></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-5 d-flex">
                     <div class="card flex-fill">
                        <div class="card-header">
                           <div class="d-flex justify-content-between align-items-center">
                              <h5 class="card-title">Invoice Analytics</h5>
                              <div class="dropdown">
                                 <button class="btn btn-white btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                 Monthly
                                 </button>
                                 <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                       <a href="javascript:void(0);" class="dropdown-item">Weekly</a>
                                    </li>
                                    <li>
                                       <a href="javascript:void(0);" class="dropdown-item">Monthly</a>
                                    </li>
                                    <li>
                                       <a href="javascript:void(0);" class="dropdown-item">Yearly</a>
                                    </li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <div class="card-body">
                           <div id="invoice_chart"></div>
                           <div class="text-center text-muted">
                              <div class="row">
                                 <div class="col-4">
                                    <div class="mt-4">
                                       <p class="mb-2 text-truncate"><i class="fas fa-circle text-primary me-1"></i> Invoiced</p>
                                       <h5>$ 2,132</h5>
                                    </div>
                                 </div>
                                 <div class="col-4">
                                    <div class="mt-4">
                                       <p class="mb-2 text-truncate"><i class="fas fa-circle text-success me-1"></i> Received</p>
                                       <h5>$ 1,763</h5>
                                    </div>
                                 </div>
                                 <div class="col-4">
                                    <div class="mt-4">
                                       <p class="mb-2 text-truncate"><i class="fas fa-circle text-danger me-1"></i> Pending</p>
                                       <h5>$ 973</h5>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div> -->
               <div class="row">
                  <div class="col-md-12 col-sm-12">
                     <div class="card">
                        <div class="card-header">
                           <div class="row">
                              <div class="col">
                                 <h5 class="card-title">Chi tiết lịch hẹn khám</h5>
                              </div>
                              <div class="col-auto">
                                 <a href="#" class="btn-right btn btn-sm btn-outline-primary">
                                 View All
                                 </a>
                              </div>
                           </div>
                        </div>
                        <div class="card-body">
                           <div class="mb-3">
                              <div class="progress progress-md rounded-pill mb-3">
                                 <div class="progress-bar bg-success" role="progressbar" style="width: 47%" aria-valuenow="47" aria-valuemin="0" aria-valuemax="100"></div>
                                 <div class="progress-bar bg-warning" role="progressbar" style="width: 28%" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100"></div>
                                 <div class="progress-bar bg-danger" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                 <div class="progress-bar bg-info" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                              </div>
                              
                           </div>
                           <div class="table-responsive">
                              <table class="table table-stripped table-hover">
                                 <thead class="thead-light">
                                    <tr>
                                       <th>Họ và tên</th>
                                       <th>Số điện thoại</th>
                                       <th>Thời gian khám</th>
                                       <th>Status</th>
                                       <th class="text-right">Action</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                       <td>
                                          <h2 class="table-avatar">
                                             <a href="profile.html">Vũ Xuân Cương</a>
                                          </h2>
                                       </td>
                                       <td>0828 228 339</td>
                                       <td>01/01/2025 </td>
                                       <td><span class="badge bg-success-light">Chưa xác nhận</span></td>
                                       <td class="text-right">
                                          <div class="dropdown dropdown-action">
                                             <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                             <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="edit-invoice.html"><i class="far fa-edit me-2"></i>Edit</a>
                                                <a class="dropdown-item" href="view-invoice.html"><i class="far fa-eye me-2"></i>View</a>
                                                <a class="dropdown-item" href="javascript:void(0);"><i class="far fa-trash-alt me-2"></i>Delete</a>
                                                <a class="dropdown-item" href="javascript:void(0);"><i class="far fa-check-circle me-2"></i>Mark as sent</a>
                                                <a class="dropdown-item" href="javascript:void(0);"><i class="far fa-paper-plane me-2"></i>Send Invoice</a>
                                                <a class="dropdown-item" href="javascript:void(0);"><i class="far fa-copy me-2"></i>Clone Invoice</a>
                                             </div>
                                          </div>
                                       </td>
                                    </tr>
                                   
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
                  
               </div>
            </div>
         </div>
<?php include "footer.php"; ?>