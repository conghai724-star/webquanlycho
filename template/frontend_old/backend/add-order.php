<?php include "header.php";?>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){
   
            
    });

</script>
<style>
	label.error{
		color:red;
		display:none;
	}
	.table-detail th{
		border-bottom-width:0px;
	}
	.ui-autocomplete {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    float: left;
    display: none;
    min-width: 160px;   
    padding: 4px 0;
    margin: 0 0 10px 25px;
    list-style: none;
    background-color: #ffffff;
    border-color: #ccc;
    border-color: rgba(0, 0, 0, 0.2);
    border-style: solid;
    border-width: 1px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    -webkit-background-clip: padding-box;
    -moz-background-clip: padding;
    background-clip: padding-box;
    *border-right-width: 2px;
    *border-bottom-width: 2px;
}
 .ui-menu-item{
	 margin-bottom: 10px;
 }
.ui-menu-item > a.ui-corner-all {
    display: block;
    padding: 3px 15px;
    clear: both;
    font-weight: normal;
    line-height: 50px;
    color: #555555;
    white-space: nowrap;
    text-decoration: none;
}
 
.ui-state-hover, .ui-state-active {
    color: #ffffff;
    text-decoration: none;
    background-color: #0088cc;
    border-radius: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    background-image: none;
}

	
	
</style>
<!-- Modal add category product-->

<!--END modal add category product -->

<div class="content container-fluid">
   <div class="page-header">
      <div class="row">
            <h3 class="page-title">Đơn mua hàng</h3>
            <ul class="breadcrumb">
               <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
               <li class="breadcrumb-item active">Đơn mua hàng</li>
            </ul>
      </div>
   </div>
   <form id="formorders" >
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h5 class="card-title text-center" id="form-title"><?php echo $title;?></h5>
            </div>
            <div class="card-body">
                  <div class="row">
                     <div class="col-md-5" >
							<div class="col-md-12">
                              <div class="form-group">
							   <label>Nhà cung cấp</label>
							   <select class="select select2 " name="order_customer_id" id="order_customer_id">
								<option>Chọn</option>
										<?php 
								   foreach($customers as $row){?>
										<option value="<?php echo $row->id;?>"><?php echo $row->customer_code ." - ". $row->customer_name;?></option>
									<?php
								   }
								   ?>
									</select>
							</div>
                           </div>
                           
                           <div class="col-md-12">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										 <label>Mã số thuế</label>
										 <input type="text" class="form-control" id='mst' name="mst" readonly>
									</div>
								</div>
								<div class="col-md-6">
								<div class="form-group">
										 <label>Người liên hệ</label>
										 <input type="text" class="form-control" id='order_name_contact' name="order_name_contact">
								</div>
								</div>
							</div>
                              
                           </div>
						   <div class="col-md-12">
							<div class="form-group">
									<label>Nhân viên mua hàng<span class="text-danger">*</span></label>
									<select class="select select2" name="order_employee_id" id="order_employee_id">
									<option disabled selected="selected">Chọn nhân viên</option>
										<?php 
								   foreach($employees as $row)
													   {
													   ?>
										<option value="<?php echo $row->id;?>"><?php echo $row->employee_name;?></option>
									<?php
								   }
								   ?>
									</select>
								</div>
							</div>
						   <div class="col-md-12">
							   <div class="row">
								   <div class="col-md-6">
										<div class="form-group">
											<label>Thuộc công ty</label>
											<input type="text" readonly id="company_name" class="form-control"/>
										</div>
								   </div>
								   <div class="col-md-6">
								 		<div class="form-group">
											<label>Tình trạng thanh toán</label>
											<select name="order_payment_active" id="order_payment_active" class="select select2">
											<option disabled selected="selected">Chọn</option>
												<?php 
												foreach($employees as $row)
																	{
																	?>
														<option value="<?php echo $row->id;?>"><?php echo $row->employee_name;?></option>
													<?php
												}
												?>
												</select>
										</div>
								   </div>
							   </div>
                              
                           </div>
                     </div>
                     <div class="col-md-5">
					 <div class="col-md-12">
                        <div class="form-group">
                           <label>Địa chỉ</label>
                           <input type="text" class="form-control" id='address' name="address" readonly>
                        </div>
						</div>
						
						
						<div class="col-md-12">
							<div class="form-group">
							   <label>Nhập kho</label>
							  <select class="select select2" name="order_warehouse_id" id="order_warehouse_id">
									<option disabled selected="selected">Lựa chọn</option>	
										<?php 
								   foreach($warehouse as $row)
													   {
													   ?>
										<option value="<?php echo $row->id;?>"><?php echo $row->warehouse_name;?></option>
									<?php
								   }
								   ?>
									</select>
							</div>
						</div>
						
						<div class="col-md-12">
							<div class="row">
								
								<div class="col-md-8">
										<div class="form-group">
										   <label>Điều khoản thanh toán</label>
										   <select class="select select2" name="order_payment_policy_id" id="order_payment_policy_id">
											<option disabled selected="selected">Chọn</option>
													<?php 
										   foreach($payments as $payment){
										   ?>
													<option value="<?php echo $payment->id;?>"><?php echo $payment->policy_code." - ".$payment->policy_title;?></option>
												<?php
											   }
											   ?>
								</select>
										</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										   <label>Số ngày được nợ</label>
										   <input type="" class="form-control" id='order_number_debt' name="order_number_debt" readonly>
									</div>
								</div> 
							</div>
								
						</div>
						<div class="col-md-12">
                              <div class="form-group">
                                 <label>Diễn giải</label>
                                 <input type="text" class="form-control" id='order_description' name="order_description">
                              </div>
                           </div>
						</div>
						
						<div class="col-md-2">
					 <div class="col-md-12">
                        <div class="form-group">
                           <label>Ngày đơn hàng</label>
                           <input type="date"  value="<?php echo date('Y-m-d'); ?>"class="form-control" id='order_date' name="order_date">
                        </div>
						</div>
						<div class="col-md-12"> 
						<div class="form-group">
                           <label>Số đơn hàng</label>
                           <input type="text" value="<?php echo $order_code;?>" class="form-control" id='order_code'  name="order_code" readonly >
                        </div>
						</div>
						<div class="col-md-12">
						<div class="form-group">
                           <label>Tình trạng đơn hàng</label>
						  <select class="select select2" id="order_active" name="order_active">
							<option value='1'>Chưa thực hiện</option>
							<option value='2'>Đang thực hiện</option>
							<option value='3'>Hoàn thành</option>
							<option value='4'>Hủy bỏ</option> 
						  </select>
                        </div>
						</div>
						<div class="col-md-12">
						<div class="form-group">
                           <label>Ngày giao hàng</label>
                           <input type="date" class="form-control" id='order_delivery_date' name="order_delivery_date">
                        </div>
						</div>
						
                        </div>
						
                     </div>
					 <h6>Hàng tiền</h6>
					<!-- hang tien -->
					<table class="table table-center table-hover mb-0 table-bordered" id="table-order">
                     <thead class="thead-light">
                        <tr>
                           <th style="width: 15%">Mã hàng  
                           <th style="width: 20%">Tên hàng</th>
                           <th style="width: 10%">DVT</th>
                           <th style="width: 10%">Số lượng</th>
						   <th style="width: 15%">Đơn giá</th>
						   <th style="width: 15%">Thành tiền</th>
						   <th>% Thuế GTGT</th>
						   <th>Tiền thuế GTGT</th>
						   <th style="width: 10%">Giảm giá %</th>
                        </tr>
                     </thead>
                     <tbody class="box-table-product"  id="row-add-content">
                        <tr>
                           <td>
								<div class="autocomplete">
										<input id="product_code_1" type="text" name="product_code" placeholder="Mã sản phẩm" class="product_code form-control" autocomplete="off" >
										<div class="box-content-product">

										</div>
								</div>
                           </td>
						   <td>
                             <input value="" class="form-control cat_product_name_id"  name="cat_product_name_id" id="cat_product_name_id_1"  />
                           </td>
						   <td>
								<select name="product_unit_id" id="product_unit_id1" class="select select2">
									<?php foreach($units as $unit){ ?>
									<option value="<?php echo $unit->id;?>"><?php echo $unit->unit_name;?></option>
									<?php }?>
								</select>
							 
                           </td>
						   <td>
                             <input value="" class="form-control product_quantity" type="number" min='0' id="product_quantity_1" name="product_quantity">
                           </td>
						   <td>
                             <input value="" class=" form-control product_price"  name="product_price" id="product_price_1"/>
                           </td>
						   <td>
                             <input value="" class=" form-control product_into_money"  name="product_into_money" id="product_into_money_1" readonly />
                           </td>
						    <td>
							 <select class="select select2 product_vat_tax"  name="product_vat_tax" id="product_vat_tax1">
								<option value="0">0</option>
								<option value="5">5</option>
								<option value="10">10</option>
								<option value="0">KCT</option>
								
							 </select>
                           </td>
						    <td>
                             <input value="" class=" form-control product_money_vat_tax" id="product_money_vat_tax_1" readonly />
                           </td>
						   <td>
                             <input value="0" type="number" class=" form-control product_discount" id="product_discount_1"  />
                           </td>
						   
                           
                        </tr>
                     </tbody>
					 <div>
                  </table>		
					<br>
					  
					<a href="#" id="add-row-order" class="add-row-order">Thêm dòng</a>
					 <!-- end-->
					 <div class="text-end">
					 	<span>Tổng tiền thuế GTGT: <span id="total-amount-vat-tax" ></span> </span>
						<br>Tổng thành tiền: <span id="total-amount" ></span> </br>
						<h6>Tổng cộng: <span id="sum-money" ></span> </h6>
						<p style="font-style: italic;"> Bằng chữ:  <span id="total-amount-text"></span></p>
					 </div>
					 <br>
                  <div class="text-end">
						
						<input type="hidden" id="fomr-method" />
						<input type="hidden" id="id" />
						
                     <button type="button" class="btn btn-primary btn-submit" id='btnaddorder' >Thêm</button>
                  </div>
               </form>
			 
            </div>
</div>

</div>
<script src="<?php echo $template_path;?>/assets/js/jquery-order.js" type="text/javascript"></script>
<?php include "footer.php";?>