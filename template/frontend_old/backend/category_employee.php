<?php include "header.php";?>
<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
         <div class="col-sm-6">
            <h3 class="page-title">Settings</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
               <li class="breadcrumb-item active">Profile Settings</li>
            </ul>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-xl-3 col-md-4">
			<?php include_once "category-sidebar.php";?>
      </div>
	  <!-- End -->
	  <!-- Preferences Setting -->
      <?php if($type =='preferences'){?>
      <div class="col-xl-9 col-md-8">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title">Preferences</h5>
            </div>
            <div class="card-body">
               <form>
                  <div class="row form-group">
                     <label for="currencyLabel" class="col-sm-3 col-form-label input-label">Currency</label>
                     <div class="col-sm-9">
                        <select class="select" id="currencyLabel">
                           <option>USD - US Dollar</option>
                           <option>GBP - British Pound</option>
                           <option>EUR - Euro</option>
                           <option>INR - Indian Rupee</option>
                           <option>AUD - Australian Dollar</option>
                        </select>
                     </div>
                  </div>
                  <div class="row form-group">
                     <label for="languageLabel" class="col-sm-3 col-form-label input-label">Language</label>
                     <div class="col-sm-9">
                        <select class="select" id="languageLabel">
                           <option>English</option>
                           <option>French</option>
                           <option>German</option>
                           <option>Italian</option>
                           <option>Spanish</option>
                        </select>
                     </div>
                  </div>
                  <div class="row form-group">
                     <label for="timezoneLabel" class="col-sm-3 col-form-label input-label">Time Zone</label>
                     <div class="col-sm-9">
                        <select class="select" id="timezoneLabel">
                           <option>English</option>
                           <option>French</option>
                           <option>German</option>
                           <option>Italian</option>
                           <option>Spanish</option>
                        </select>
                     </div>
                  </div>
                  <div class="row form-group">
                     <label for="dateformat" class="col-sm-3 col-form-label input-label">Date Format</label>
                     <div class="col-sm-9">
                        <select class="select" id="dateformat">
                           <option>2020 Nov 09</option>
                           <option>09 Nov 2020</option>
                           <option>09/11/2020</option>
                           <option>09.11.2020</option>
                           <option>09-11-2020</option>
                           <option>11/09/2020</option>
                           <option>2020/11/09</option>
                           <option>2020-11-09</option>
                        </select>
                     </div>
                  </div>
                  <div class="row form-group">
                     <label for="financialyear" class="col-sm-3 col-form-label input-label">Financial Year</label>
                     <div class="col-sm-9">
                        <select class="select" id="financialyear">
                           <option>january-december</option>
                           <option>february-january</option>
                           <option>march-february</option>
                           <option>april-march</option>
                           <option>may-april</option>
                           <option>june-may</option>
                           <option>july-june</option>
                           <option>august-july</option>
                           <option>september-august</option>
                           <option>october-september</option>
                           <option>november-october</option>
                           <option>december-november</option>
                        </select>
                     </div>
                  </div>
                  <label class="row form-group toggle-switch" for="preferencesSwitch1">
                  <span class="col-8 col-sm-9 toggle-switch-content ml-0">
                  <span class="d-block text-dark">Discount Per Item</span>
                  <span class="d-block text-muted">Enable this if you want to add Discount to individual invoice items. By default, Discount is added directly to the invoice.</span>
                  </span>
                  <span class="col-4 col-sm-3">
                  <input type="checkbox" class="toggle-switch-input" id="preferencesSwitch1">
                  <span class="toggle-switch-label ms-auto">
                  <span class="toggle-switch-indicator"></span>
                  </span>
                  </span>
                  </label>
                  <div class="text-end">
                     <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
	  <!-- End -->
	  <!-- Tax Types Setting -->
      <?php }elseif($type == 'tax-types'){?>
      <div class="col-xl-9 col-md-8">
         <div class="card card-table">
            <div class="card-header">
               <div class="row">
                  <div class="col">
                     <h5 class="card-title">Tax Types</h5>
                  </div>
                  <div class="col-auto">
                     <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_tax">Add New Tax</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                  <table class="table table-hover mb-0">
                     <thead class="thead-light">
                        <tr>
                           <th>Tax Name </th>
                           <th>Tax Percentage (%) </th>
                           <th>Status</th>
                           <th class="text-right">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>VAT</td>
                           <td>14%</td>
                           <td>
                              <span class="badge bg-success-light">Active</span>
                           </td>
                           <td class="text-right">
                              <a href="#" data-bs-toggle="modal" data-bs-target="#edit_tax" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                              <a href="#" data-bs-toggle="modal" data-bs-target="#delete_tax" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                           </td>
                        </tr>
                        <tr>
                           <td>GST</td>
                           <td>30%</td>
                           <td>
                              <span class="badge bg-danger-light">Inactive</span>
                           </td>
                           <td class="text-right">
                              <a href="#" data-bs-toggle="modal" data-bs-target="#edit_tax" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                              <a href="#" data-bs-toggle="modal" data-bs-target="#delete_tax" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="add_tax" class="modal custom-modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Add Tax</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form>
                  <div class="form-group">
                     <label>Tax Name <span class="text-danger">*</span></label>
                     <input class="form-control" type="text">
                  </div>
                  <div class="form-group">
                     <label>Tax Percentage (%) <span class="text-danger">*</span></label>
                     <input class="form-control" type="text">
                  </div>
                  <div class="form-group">
                     <label>Status <span class="text-danger">*</span></label>
                     <select class="select">
                        <option>Pending</option>
                        <option>Approved</option>
                     </select>
                  </div>
                  <div class="submit-section">
                     <button class="btn btn-primary submit-btn">Submit</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <div id="edit_tax" class="modal custom-modal fade" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="modal-title">Edit Tax</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <form>
                  <div class="form-group">
                     <label>Tax Name <span class="text-danger">*</span></label>
                     <input class="form-control" value="VAT" type="text">
                  </div>
                  <div class="form-group">
                     <label>Tax Percentage (%) <span class="text-danger">*</span></label>
                     <input class="form-control" value="14%" type="text">
                  </div>
                  <div class="form-group">
                     <label>Status <span class="text-danger">*</span></label>
                     <select class="select">
                        <option>Active</option>
                        <option>Inactive</option>
                     </select>
                  </div>
                  <div class="submit-section">
                     <button class="btn btn-primary submit-btn">Save</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <div class="modal custom-modal fade" id="delete_tax" role="dialog">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-body">
               <div class="modal-icon text-center mb-3">
                  <i class="fas fa-trash-alt text-danger"></i>
               </div>
               <div class="modal-text text-center">
                  <h2>Delete Tax</h2>
                  <p>Are you sure want to delete?</p>
               </div>
            </div>
            <div class="modal-footer text-center">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
               <button type="button" class="btn btn-primary">Delete</button>
            </div>
         </div>
      </div>
   </div>
   <!-- End -->
<!-- Expense Category Setting-->
   <?php }elseif($type=='expense-category'){?>
   <div class="col-xl-9 col-md-8">
      <div class="card card-table">
         <div class="card-header">
            <div class="row">
               <div class="col">
                  <h5 class="card-title">Expense Category</h5>
               </div>
               <div class="col-auto">
                  <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_category">Add New Category</a>
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-striped custom-table mb-0">
                  <thead>
                     <tr>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>Accounts</td>
                        <td>Lorem ipsum dollar</td>
                        <td>
                           <span class="badge bg-success-light">Active</span>
                        </td>
                        <td class="text-end">
                           <a href="#" data-bs-toggle="modal" data-bs-target="#edit_category" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                           <a href="#" data-bs-toggle="modal" data-bs-target="#delete_category" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                        </td>
                     </tr>
                     <tr>
                        <td>Sales</td>
                        <td>Lorem ipsum dollar</td>
                        <td>
                           <span class="badge bg-danger-light">Inactive</span>
                        </td>
                        <td class="text-end">
                           <a href="#" data-bs-toggle="modal" data-bs-target="#edit_category" class="btn btn-sm btn-white text-success me-2"><i class="far fa-edit me-1"></i> Edit</a>
                           <a href="#" data-bs-toggle="modal" data-bs-target="#delete_category" class="btn btn-sm btn-white text-danger me-2"><i class="far fa-trash-alt me-1"></i>Delete</a>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<div id="add_category" class="modal custom-modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Add Expense Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form>
               <div class="form-group">
                  <label>Category <span class="text-danger">*</span></label>
                  <input class="form-control" type="text">
               </div>
               <div class="form-group">
                  <label>Description <span class="text-danger">*</span></label>
                  <textarea class="form-control"></textarea>
               </div>
               <div class="form-group">
                  <label>Status <span class="text-danger">*</span></label>
                  <select class="select">
                     <option>Pending</option>
                     <option>Approved</option>
                  </select>
               </div>
               <div class="submit-section">
                  <button class="btn btn-primary submit-btn">Submit</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div id="edit_category" class="modal custom-modal fade" role="dialog">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Edit Expense Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form>
               <div class="form-group">
                  <label>Category <span class="text-danger">*</span></label>
                  <input class="form-control" value="VAT" type="text">
               </div>
               <div class="form-group">
                  <label>Description <span class="text-danger">*</span></label>
                  <textarea class="form-control"></textarea>
               </div>
               <div class="form-group">
                  <label>Status <span class="text-danger">*</span></label>
                  <select class="select">
                     <option>Active</option>
                     <option>Inactive</option>
                  </select>
               </div>
               <div class="submit-section">
                  <button class="btn btn-primary submit-btn">Save</button>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div class="modal custom-modal fade" id="delete_category" role="dialog">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-body">
            <div class="modal-icon text-center mb-3">
               <i class="fas fa-trash-alt text-danger"></i>
            </div>
            <div class="modal-text text-center">
               <h3>Delete Expense Category</h3>
               <p>Are you sure want to delete?</p>
            </div>
         </div>
         <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary">Delete</button>
         </div>
      </div>
   </div>
</div>
</div>
</div>
<!--end-->
<!--Notifications Setting -->
<?php }elseif($type=='notifications'){?>
<div class="col-xl-9 col-md-8">
   <div class="card">
      <div class="card-header">
         <h5 class="card-title">Notifications</h5>
         <p>Which email notifications would you like to receive when something changes?</p>
      </div>
      <div class="card-body">
         <form>
            <div class="row form-group">
               <label for="notificationmail" class="col-sm-3 col-form-label input-label">Send Notifications to</label>
               <div class="col-sm-9">
                  <input type="email" class="form-control" id="notificationmail">
               </div>
            </div>
            <label class="row form-group toggle-switch" for="notification_switch1">
            <span class="col-8 col-sm-9 toggle-switch-content ms-0">
            <span class="d-block text-dark">Invoice viewed</span>
            <span class="d-block text-muted">When your customer views the invoice sent via dashboard.</span>
            </span>
            <span class="col-4 col-sm-3">
            <input type="checkbox" class="toggle-switch-input" id="notification_switch1">
            <span class="toggle-switch-label ms-auto">
            <span class="toggle-switch-indicator"></span>
            </span>
            </span>
            </label>
            <label class="row form-group toggle-switch" for="notification_switch2">
            <span class="col-8 col-sm-9 toggle-switch-content ms-0">
            <span class="d-block text-dark">Estimate viewed</span>
            <span class="d-block text-muted">When your customer views the estimate sent via dashboard.</span>
            </span>
            <span class="col-4 col-sm-3">
            <input type="checkbox" class="toggle-switch-input" id="notification_switch2">
            <span class="toggle-switch-label ms-auto">
            <span class="toggle-switch-indicator"></span>
            </span>
            </span>
            </label>
            <div class="text-end">
               <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!--end-->
<!-- Change Password Setting -->
<?php }elseif($type=='change-password'){?>
<div class="col-xl-9 col-md-8">
   <div class="card">
      <div class="card-header">
         <h5 class="card-title">Change Password</h5>
      </div>
      <div class="card-body">
         <form>
            <div class="row form-group">
               <label for="current_password" class="col-sm-3 col-form-label input-label">Current Password</label>
               <div class="col-sm-9">
                  <input type="password" class="form-control" id="current_password" placeholder="Enter current password">
               </div>
            </div>
            <div class="row form-group">
               <label for="new_password" class="col-sm-3 col-form-label input-label">New Password</label>
               <div class="col-sm-9">
                  <input type="password" class="form-control" id="new_password" placeholder="Enter new password">
                  <div class="progress progress-md mt-2">
                     <div class="progress-bar bg-danger" role="progressbar" style="width: 2%" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
                  </div>
               </div>
            </div>
            <div class="row form-group">
               <label for="confirm_password" class="col-sm-3 col-form-label input-label">Confirm new password</label>
               <div class="col-sm-9">
                  <div class="mb-3">
                     <input type="password" class="form-control" id="confirm_password" placeholder="Confirm your new password">
                  </div>
                  <h5>Password requirements:</h5>
                  <p class="mb-2">Ensure that these requirements are met:</p>
                  <ul class="list-unstyled small">
                     <li>Minimum 8 characters long - the more, the better</li>
                     <li>At least one lowercase character</li>
                     <li>At least one uppercase character</li>
                     <li>At least one number, symbol</li>
                  </ul>
               </div>
            </div>
            <div class="text-end">
               <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
         </form>
      </div>
   </div>
</div>
<?php }elseif($type == 'delete-account'){?>
<div class="col-xl-9 col-md-8">
   <div class="card">
      <div class="card-header">
         <h5 class="card-title">Delete your account</h5>
      </div>
      <div class="card-body">
         <form>
            <p class="card-text">When you delete your account, you lose access to Kanakku account services, and we permanently delete your personal data.</p>
            <p class="card-text">Are you sure you want to close your account?</p>
            <div class="form-group">
               <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="delete_account">
                  <label class="custom-control-label text-danger" for="delete_account">Confirm that I want to delete my account.</label>
               </div>
            </div>
            <div class="text-end">
               <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
         </form>
      </div>
   </div>
   <!--end-->
   <!-- Profile Setting -->
   <?php }else{?>
   <div class="col-xl-9 col-md-8">
      <div class="card">
         <div class="card-header">
            <h5 class="card-title">Basic information</h5>
         </div>
         <div class="card-body">
            <form>
               <div class="row form-group">
                  <label for="name" class="col-sm-3 col-form-label input-label">Name</label>
                  <div class="col-sm-9">
                     <div class="d-flex align-items-center">
                        <label class="avatar avatar-xxl profile-cover-avatar m-0" for="edit_img">
                        <img id="avatarImg" class="avatar-img" src="<?php echo $template_path; ?>/assets//img/profiles/avatar-02.jpg" alt="Profile Image">
                        <input type="file" id="edit_img">
                        <span class="avatar-edit">
                        <i data-feather="edit-2" class="avatar-uploader-icon shadow-soft"></i>
                        </span>
                        </label>
                     </div>
                  </div>
               </div>
               <div class="row form-group">
                  <label for="name" class="col-sm-3 col-form-label input-label">Name</label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="name" placeholder="Your Name" value="<?php echo $user->user_fullname;?>">
                  </div>
               </div>
               <div class="row form-group">
                  <label for="email" class="col-sm-3 col-form-label input-label">Email</label>
                  <div class="col-sm-9">
                     <input type="email" class="form-control" id="email" placeholder="Email" value="<?php echo $user-> user_email;?>">
                  </div>
               </div>
               <div class="row form-group">
                  <label for="phone" class="col-sm-3 col-form-label input-label">Phone <span class="text-muted">(Optional)</span></label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="phone" placeholder="" value="<?php echo $user-> user_phone;?>">
                  </div>
               </div>
               
               <div class="row form-group">
                  <label for="addressline1" class="col-sm-3 col-form-label input-label">Address </label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="addressline1" placeholder="Your address" value="<?php echo $user->user_address;?>">
                  </div>
               </div>
               <!-- <div class="row form-group">
                  <label for="addressline2" class="col-sm-3 col-form-label input-label">Address line 2 <span class="text-muted">(Optional)</span></label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="addressline2" placeholder="Your address">
                  </div>
               </div> -->
               <!--<div class="row form-group">
                  <label for="zipcode" class="col-sm-3 col-form-label input-label">Zip code</label>
                  <div class="col-sm-9">
                     <input type="text" class="form-control" id="zipcode" placeholder="Your zip code" value="25301">
                  </div>
               </div> -->
               <div class="text-end">
                  <button type="submit" class="btn btn-primary">Save Changes</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <?php }?>
</div>
</div>
</div>
<?php include "footer.php";?>