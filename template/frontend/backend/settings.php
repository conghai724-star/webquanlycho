<?php include "header.php";?>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col-sm-6">
            <h3 class="page-title">Thiết lập</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Trang chủ</a></li>
               <li class="breadcrumb-item active">Cài đặt</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-12">
         <div class="card bg-white">
            <div class="card-body">
               <ul class="nav nav-tabs nav-tabs-solid nav-justified">
                  <li class="nav-item"><a class="nav-link active" href="#solid-justified-tab1" data-bs-toggle="tab">Phòng khám và Nhà thuốc</a></li>
                  <!--<li class="nav-item"><a class="nav-link" href="#solid-justified-tab2" data-bs-toggle="tab">Hệ thống</a></li>-->
                  <!--<li class="nav-item"><a class="nav-link" href="#solid-justified-tab3" data-bs-toggle="tab">Thanh toán</a></li>-->
                  <!--<li class="nav-item"><a class="nav-link" href="#solid-justified-tab4" data-bs-toggle="tab">APIs</a></li>-->
                  <!--<li class="nav-item"><a class="nav-link" href="#solid-justified-tab5" data-bs-toggle="tab">Tích hợp</a></li>-->
               </ul>
               <div class="tab-content">
                  <div class="tab-pane show active" id="solid-justified-tab1">
                     <form>
                        <!--<div class="row form-group">-->
                        <!--   <label for="name" class="col-sm-3 col-form-label input-label">Logo</label>-->
                        <!--   <div class="col-sm-9">-->
                        <!--      <div class="d-flex align-items-center">-->
                        <!--         <label class="avatar avatar-company profile-cover-avatar m-0" for="edit_logo">-->
                        <!--         <img id="company_logo" class="avatar-img" src="<?php echo ($this->helper->get_config("company_logo"))? $upload_path."/company/".$this->helper->get_config("company_logo") : $template_path."/assets/img/logo.png";?>" alt="Profile Image">-->
                        <!--         <input type="file" id="edit_logo">-->
                        <!--         <span class="avatar-edit">-->
                        <!--         <i data-feather="edit-2" class="avatar-uploader-icon shadow-soft"></i>-->
                        <!--         </span>-->
                        <!--         </label>-->
                        <!--      </div>-->
                        <!--   </div>-->
                        <!--</div>-->
                        <div class="row form-group">
                           <label for="name" class="col-sm-3 col-form-label input-label">Tên đơn vị</label>
                           <div class="col-sm-9">
                              <input type="text" class="form-control" id="website_name" name="company_name" placeholder="Tên đơn vị " value="<?php echo $this->helper->get_config("website_name");?>">
                           </div>
                        </div>
                        <div class="row form-group">
                        <!--   <label for="company_tax_id" class="col-sm-3 col-form-label input-label">Mã số thuế</label>-->
                        <!--   <div class="col-sm-9">-->
                        <!--      <input type="text" class="form-control" id="company_tax_id" name="company_tax_id" placeholder="Mã số thuế" value="<?php echo $this->helper->get_config("company_tax_id");?>">-->
                        <!--   </div>-->
                        <!--</div>-->
                        <div class="row form-group">
                           <label for="company_phone" class="col-sm-3 col-form-label input-label">Điện thoại</label>
                           <div class="col-sm-9">
                              <input type="text" class="form-control" id="company_phone" name="company_phone" placeholder="Điện thoại" value="<?php echo $this->helper->get_config("site_phone");?>">
                           </div>
                        </div>
                        <div class="row form-group">
                           <label for="email" class="col-sm-3 col-form-label input-label">Email</label>
                           <div class="col-sm-9">
                              <input type="email" class="form-control" id="email" placeholder="Email" value="<?php echo $this->helper->get_config("site_email");?>">
                           </div>
                        </div>
                        <!--<div class="row form-group">-->
                        <!--   <label for="company_website" class="col-sm-3 col-form-label input-label">Website</label>-->
                        <!--   <div class="col-sm-9">-->
                        <!--      <input type="text" class="form-control" id="company_website" name="company_website" placeholder="Địa chỉ website" value="<?php echo $this->helper->get_config("company_website");?>">-->
                        <!--   </div>-->
                        <!--</div>-->
                        <div class="row form-group">
                           <label for="company_address" class="col-sm-3 col-form-label input-label">Địa chỉ </label>
                           <div class="col-sm-9">
                              <input type="text" class="form-control" id="company_address" name="company_address" placeholder="Địa chỉ" value="<?php echo $this->helper->get_config("site_address");?>">
                           </div>
                        </div>
                        <div class="text-end">
                           <!--<button type="submit" class="btn btn-primary">Lưu thay đổi</button>-->
                        </div>
                     </form>
                  </div>
      <!--            <div class="tab-pane" id="solid-justified-tab2">-->
      <!--               <form>-->
      <!--                  <div class="row form-group">-->
      <!--                     <label for="currencyLabel" class="col-sm-3 col-form-label input-label">Loại tiền tệ</label>-->
      <!--                     <div class="col-sm-9">-->
      <!--                        <select class="select select2" id="currencyLabel">-->
      <!--                           <option value="1">VNĐ - Đồng Việt Nam</option>-->
      <!--                           <option value="2">USD - Đô la Mỹ</option>-->
      <!--                           <option value="3">GBP - Bảng Anh</option>-->
      <!--                           <option value="4">EUR - Euro</option>-->
      <!--                        </select>-->
      <!--                     </div>-->
      <!--                  </div>-->
      <!--                  <div class="row form-group">-->
      <!--                     <label for="languageLabel" class="col-sm-3 col-form-label input-label">Ngôn ngữ hệ thống</label>-->
      <!--                     <div class="col-sm-9">-->
      <!--                        <select class="select select2" id="languageLabel">-->
      <!--                           <option value="vi">Tiếng Việt</option>-->
      <!--                           <option value="en">English</option>-->
      <!--                        </select>-->
      <!--                     </div>-->
      <!--                  </div>-->
      <!--                  <div class="row form-group">-->
      <!--                     <label for="dateformat" class="col-sm-3 col-form-label input-label">Định dạng ngày giờ</label>-->
      <!--                     <div class="col-sm-9">-->
      <!--                        <select class="select select2" id="dateformat">-->
      <!--                           <option>2020 Nov 09</option>-->
      <!--                           <option>09 Nov 2020</option>-->
      <!--                           <option>09/11/2020</option>-->
      <!--                           <option>09.11.2020</option>-->
      <!--                           <option>09-11-2020</option>-->
      <!--                           <option>11/09/2020</option>-->
      <!--                           <option>2020/11/09</option>-->
      <!--                           <option>2020-11-09</option>-->
      <!--                        </select>-->
      <!--                     </div>-->
      <!--                  </div>-->
      <!--                  <div class="row form-group">-->
      <!--                     <label for="financialyear" class="col-sm-3 col-form-label input-label">Năm tài chính</label>-->
      <!--                     <div class="col-sm-9">-->
      <!--                        <select class="select select2" id="financialyear">-->
      <!--                           <option value="1">Tháng 1 - Tháng 12</option>-->
      <!--                           <option value="2">Tháng 2 - Tháng 1</option>-->
      <!--                           <option value="3">Tháng 3 - Tháng 2</option>-->
      <!--                           <option value="4">Tháng 4 - Tháng 3</option>-->
      <!--                           <option value="5">Tháng 5 - Tháng 4</option>-->
      <!--                           <option value="6">Tháng 6 - Tháng 5</option>-->
      <!--                           <option value="7">Tháng 7 - Tháng 6</option>-->
      <!--                           <option value="8">Tháng 8 - Tháng 7</option>-->
      <!--                           <option value="9">Tháng 9 - Tháng 8</option>-->
      <!--                           <option value="10">Tháng 10 - Tháng 9</option>-->
      <!--                           <option value="11">Tháng 11 - Tháng 10</option>-->
      <!--                           <option value="12">Tháng 12 - Tháng 11</option>-->
      <!--                        </select>-->
      <!--                     </div>-->
      <!--                  </div>-->
      <!--                  <div class="row form-group">-->
      <!--                     <label for="notificationmail" class="col-sm-3 col-form-label input-label">Email nhận thông báo</label>-->
      <!--                     <div class="col-sm-9">-->
      <!--                        <input type="email" class="form-control" id="notificationmail">-->
      <!--                     </div>-->
      <!--                  </div>-->
      <!--                  <label class="row form-group toggle-switch" for="notification_switch1">-->
      <!--                  <span class="col-8 col-sm-9 toggle-switch-content ms-0">-->
      <!--                  <span class="d-block text-dark">Hoá đơn mới</span>-->
      <!--                  <span class="d-block text-muted">Nhận thông báo khi phát sinh hoá đơn mua hàng, bán hàng mới.</span>-->
      <!--                  </span>-->
      <!--                  <span class="col-4 col-sm-3">-->
      <!--                  <input type="checkbox" class="toggle-switch-input" id="notification_switch1">-->
      <!--                  <span class="toggle-switch-label ms-auto">-->
      <!--                  <span class="toggle-switch-indicator"></span>-->
      <!--                  </span>-->
      <!--                  </span>-->
      <!--                  </label>-->
      <!--                  <label class="row form-group toggle-switch" for="notification_switch2">-->
      <!--                  <span class="col-8 col-sm-9 toggle-switch-content ms-0">-->
      <!--                  <span class="d-block text-dark">Báo cáo tự động</span>-->
      <!--                  <span class="d-block text-muted">Nhận báo cáo tự động sau khi kiểm toán đêm.</span>-->
      <!--                  </span>-->
      <!--                  <span class="col-4 col-sm-3">-->
      <!--                  <input type="checkbox" class="toggle-switch-input" id="notification_switch2">-->
      <!--                  <span class="toggle-switch-label ms-auto">-->
      <!--                  <span class="toggle-switch-indicator"></span>-->
      <!--                  </span>-->
      <!--                  </span>-->
      <!--                  </label>-->
      <!--                  <div class="text-end">-->
      <!--                     <button type="submit" class="btn btn-primary">Lưu thay đổi</button>-->
      <!--                  </div>-->
      <!--               </form>-->
      <!--            </div>-->
      <!--            <div class="tab-pane" id="solid-justified-tab3">-->
      <!--               <div class="row">-->
      <!--                  <div class="col-xl-4">-->
      <!--                     <div class="card flex-fill">-->
      <!--                        <div class="card-body">-->
      <!--                           <form action="#">-->
      <!--                              <div class="form-group row">-->
      <!--                                 <label class="col-lg-4 col-form-label">Gói dịch vụ</label>-->
      <!--                                 <div class="col-lg-8">-->
      <!--                                    <input type="text" readonly="true" value="ClourERP S01" style="font-weight: bold;" class="form-control">-->
      <!--                                 </div>-->
      <!--                              </div>-->
      <!--                              <div class="form-group row">-->
      <!--                                 <label class="col-lg-4 col-form-label">Ngày đăng ký</label>-->
      <!--                                 <div class="col-lg-8">-->
      <!--                                    <input type="text" readonly="true" value="29/10/2021" style="font-weight: bold;" class="form-control">-->
      <!--                                 </div>-->
      <!--                              </div>-->
      <!--                              <div class="form-group row">-->
      <!--                                 <label class="col-lg-4 col-form-label">Ngày hết hạn</label>-->
      <!--                                 <div class="col-lg-8">-->
      <!--                                    <input type="text" readonly="true" value="29/10/2022" style="font-weight: bold;" class="form-control">-->
      <!--                                 </div>-->
      <!--                              </div>-->
      <!--                              <div class="form-group row">-->
      <!--                                 <label class="col-lg-4 col-form-label">Đăng ký cho</label>-->
      <!--                                 <div class="col-lg-8">-->
      <!--                                    <input type="text" readonly="true" style="font-weight: bold;" value="Công ty Cổ Phần Nhập Khẩu Và Phân Phối Thức Ăn Thú Cưng Việt Mỹ" class="form-control">-->
      <!--                                 </div>-->
      <!--                              </div>-->
      <!--                              <div class="form-group row">-->
      <!--                                 <label class="col-lg-4 col-form-label">Số đăng ký</label>-->
      <!--                                 <div class="col-lg-8">-->
      <!--                                    <input type="text" readonly="true" style="font-weight: bold;" value="ERPS2541361" class="form-control">-->
      <!--                                 </div>-->
      <!--                              </div>-->
						<!--			<div class="text-end">-->
						<!--			   <button type="submit" class="btn btn-success">Nâng cấp</button>-->
						<!--			</div>-->
      <!--                           </form>-->
      <!--                        </div>-->
      <!--                     </div>-->
      <!--                  </div>-->
      <!--                  <div class="col-xl-8">-->
      <!--                     <div class="card card-table">-->
      <!--                        <div class="card-header">-->
      <!--                           <h5 class="card-title">Lịch sử thanh toán</h5>-->
      <!--                        </div>-->
      <!--                        <div class="card-body">-->
      <!--                           <div class="table-responsive">-->
      <!--                              <table class="table table-stripped table-hover datatable">-->
      <!--                                 <thead class="thead-light">-->
      <!--                                    <tr>-->
      <!--                                       <th>Số hoá đơn</th>-->
      <!--                                       <th>Ngày tạo</th>-->
      <!--                                       <th>Số tiền</th>-->
      <!--                                       <th>Nội dung</th>-->
      <!--                                       <th>Ngày hết hạn</th>-->
      <!--                                       <th>Trạng thái</th>-->
      <!--                                       <th>Ngày thanh toán</th>-->
      <!--                                    </tr>-->
      <!--                                 </thead>-->
      <!--                                 <tbody>-->
      <!--                                    <tr>-->
      <!--                                       <td><a href="view-invoice.html">INV-65ZTE15</a></td>-->
      <!--                                       <td>29/10/2021</td>-->
      <!--                                       <td>0</td>-->
      <!--                                       <td>Đăng ký gói EPR-01</td>-->
      <!--                                       <td>29/11/2021</td>-->
      <!--                                       <td><span class="badge bg-success-light">Paid</span></td>-->
      <!--                                       <td>18:05 29/11/2021</td>-->
      <!--                                    </tr>-->
      <!--                                 </tbody>-->
      <!--                              </table>-->
      <!--                           </div>-->
      <!--                        </div>-->
      <!--                     </div>-->
      <!--                  </div>-->
      <!--               </div>-->
      <!--            </div>-->
      <!--            <div class="tab-pane" id="solid-justified-tab4">-->
      <!--               <div class="row">-->
						<!--<div class="col-lg-12">-->
						<!--<div class="card">-->
						<!--	<div class="card-body">-->
						<!--		<form action="#">-->
						<!--			<div class="form-group mb-0 row">-->
						<!--				<label class="col-form-label col-md-2">API Key</label>-->
						<!--				<div class="col-md-10">-->
						<!--					<div class="input-group mb-3">-->
						<!--						<span class="input-group-text" id="basic-addon1"><i class="fas fa-key"></i></span>-->
						<!--						<input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">-->
						<!--						<button class="btn btn-primary" type="button"><i class="fas fa-copy"></i></button>-->
						<!--					</div>-->
						<!--				</div>-->
						<!--			</div>-->
						<!--			<div class="form-group mb-0 row">-->
						<!--				<label class="col-form-label col-md-2">API Secrect</label>-->
						<!--				<div class="col-md-10">-->
						<!--					<div class="input-group mb-3">-->
						<!--						<span class="input-group-text" id="basic-addon1"><i class="fas fa-user-shield"></i></span>-->
						<!--						<input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">-->
						<!--						<button class="btn btn-primary" type="button"><i class="fas fa-eye"></i></button>-->
						<!--						<button class="btn btn-primary" type="button"><i class="fas fa-copy"></i></button>-->
						<!--					</div>-->
						<!--				</div>-->
						<!--			</div>-->
						<!--<div class="form-group row">-->
						<!--<label class="col-form-label col-lg-2">Endpoint</label>-->
						<!--<div class="col-lg-10">-->
						<!--<div class="input-group">-->
						<!--<span class="input-group-text" id="basic-addon3"><?php echo XC_URL;?>/</span>-->
						<!--<input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">-->
						<!--</div>-->
						<!--</div>-->
						<!--</div>-->
						<!--</form>-->
						<!--</div>-->
						<!--</div>-->

						<!--</div>-->
						<!--</div>-->
      <!--            </div>-->
      <!--            <div class="tab-pane" id="solid-justified-tab5">-->
      <!--               Gói dịch vụ của Quý Khách chưa đủ điều kiện sử dụng tính năng này-->
      <!--            </div>-->
      <!--         </div>-->
      <!--      </div>-->
         </div>
      </div>
   </div>
</div>
</div>
</div>
</div>
</div>
<?php include "footer.php";?>