<?php include "header.php";?>
<style>
.title_box{
	font-size: 18px;
    font-weight: bold;
}
</style>
<div class="content container-fluid">
 
   <div class="page-header">
    <div class="row">
         <div class="col-sm-12">
            <h3 class="page-title">Thông tin tài khoản</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
               <li class="breadcrumb-item"><a href="#">Quản lý tài khoản</a></li>
               <li class="breadcrumb-item active">Thông tin tài khoản</li>
            </ul>
         </div>
      </div>
      <div class="row align-items-center">
	  
	   <div class="page-title"><span class = 'title_box'><?php echo $user -> user_name;?></span>
	  <div style = 'float: right;'>
	   <button  type="button" class="btn btn-sm btn-success"><a style='color: white;' href="<?php echo XC_URL;?>/admin/editusers/<?php echo $user->uid;?>"  >Sửa</button>
            </a>
			<div class="btn-group">
								   <!-- <button type="button" class="btn btn-sm btn-success">Báo cáo &nbsp;|
								   <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									  <a class="dropdown-item" href="#">Biên bản đối chiếu và xác nhận công nợ</a>
									  <a class="dropdown-item" href="#">Thông báo công nợ</a>
									  
								   </div> -->
								</div>
							</div>
			</div>
	   <div>
	   
            
	  </div>
	  
         <div class="col-6 box-breadcrumb">
			<div class = 'row'>
				<div class = 'col-2'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Họ và tên: </span>
				</div>
				<div class = 'col-10'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $user->user_fullname;?></span>
				</div>
			</div>
			
			<div class = 'row'>
				<div class = 'col-2'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Địa chỉ: </span>
				</div>
				<div class = 'col-10'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $user->user_address;?></span>
				</div>
			</div>
			
			<div class = 'row'>
				<div class = 'col-2'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Khoa: </span>
				</div>
				<div class = 'col-10'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $user->depart_name;?></span>
				</div>
			</div>
			  
			
         </div> 
		 <div class="col-4">
            <div class = 'row'>
				<div class = 'col-6'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Tên tài khoản: </span>
				</div>
				<div class = 'col-6'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $user->user_username;?></span>
				</div>
			</div>
			
			<div class = 'row'>
				<div class = 'col-6'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'>Địa chỉ:  </span>
				</div>
				<div class = 'col-6'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $user	->user_address;?></span>
				</div>
			</div>
			<div class = 'row'>
				<div class = 'col-6'>
					<span class = "breadcrumb-item" style = 'font-weight: bold;'> Quyền:  </span>
				</div>
				<div class = 'col-6'>
					  <span class = "breadcrumb-item title_breadcrumb"><?php echo $user->group_name;?></span>
				</div>
			</div>
         </div> 
         
      </div>
   </div>
   
</div>
<?php include "footer.php";?>