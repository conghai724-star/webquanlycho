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
   <div class="row align-items-center">
      <div class="col">
         <h3 class="page-title">Tạo báo giá</h3>
         <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
            <li class="breadcrumb-item active">Báo giá</li>
         </ul>
      </div>
      <div class="col-auto">
      </div>
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
                     <label>Khách hàng</label>
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
                  <div class="row">
					<div class="col-md-6">
                        <div class="form-group">
                           <label>Công nợ hiện tại</label>
                           <input type="text" class="form-control" readonly id="c_debt" name="c_debt">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>Nhân viên bán<span class="text-danger">*</span></label>
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
                     
                  </div>
               </div>
			   <div class="col-md-12">
				   <div class="form-group">
					  <label>Diễn giải</label>
					  <input type="text" class="form-control" id='order_description' name="order_description">
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
                  <div class="row">
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>Email</label>
                           <input type="text" class="form-control" id='cemail' name="email" readonly>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label>Số điện thoại</label>
                           <input type="text" class="form-control" id='cphone' name="phone" readonly>
                        </div>
                     </div>
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
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
							   <label>Giảm giá</label>
							   <select name="order_payment_active" id="order_payment_active" class="select select2">
								  <option disabled selected="selected">Chọn</option>
								  <?php 
									foreach($promotions as $promo)
									{
										$promovalue = '';
										if($promo->promo_type == 1)
										{
											if($promo->promo_discount_type == 1)
											{
												$promovalue = '(Giảm giá: '.number_format($promo->promo_discount_value,0).' VNĐ)';
											}
											else
											{
												$promovalue = '(Giảm giá: '.number_format($promo->promo_discount_value,0).'%)';
											}
										}
										else
										{
											if($promo->promo_discount_type == 1)
											{
												$promovalue = '(Mã giảm giá: '.number_format($promo->promo_discount_value,0).' VNĐ)';
											}
											else
											{
												$promovalue = '(Mã giảm giá: '.number_format($promo->promo_discount_value,0).'%)';
											}
										}
									?>
										<option value="<?php echo $promo->id;?>"><?php echo $promo->promo_name." ".$promovalue;?></option>
									<?php
									}
									?>
							   </select>
							</div>
						 </div>
						
					</div>
			   </div>
            </div>
            <div class="col-md-2">
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Ngày báo giá</label>
                     <input type="date"  value="<?php echo date('Y-m-d'); ?>"class="form-control" id='order_date' name="order_date">
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Số báo giá</label>
                     <input type="text" value="<?php echo $quote_code;?>" class="form-control" id='order_code'  name="order_code" readonly >
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Tình trạng</label>
                     <select class="select select2" id="order_active" name="order_active">
                        <option value='1'>Mới tạo</option>
                        <option value='2'>Đã từ chối</option>
                        <option value='3'>Đã điều chỉnh</option>
                        <option value='5'>Chuyển sang đơn hàng</option>
                     </select>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="form-group">
                     <label>Ngày hết hạn</label>
                     <input type="date" value="<?php echo date('Y-m-d',strtotime("+30 days")); ?>" class="form-control" id='order_delivery_date' name="order_delivery_date">
                  </div>
               </div>
            </div>
            
         </div>
         <h6>Hàng tiền</h6>
         <!-- hang tien -->
         <table class="table table-center table-hover mb-0 table-bordered" id="table-order">
            <thead class="thead-light">
               <tr>
                  <th style="width: 25%">Sản phẩm  
                  <th style="width: 10%">ĐVT</th>
                  <th style="width: 10%">Số lượng</th>
                  <th style="width: 15%">Đơn giá</th>
                  <th>VAT</th>
                  <th style="width: 10%">Giảm giá</th>
				  <th style="width: 15%">Thành tiền</th>
               </tr>
            </thead>
            <tbody class="box-table-product"  id="row-add-content">
               <tr>
                  <td>
                     <input value="" type="hidden" class="form-control product_id"  name="product_id_0" id="product_id_0"  />
                     <input value="" type="text" class="form-control product_name"  name="product_name_0" id="product_name_0"  />
                  </td>
                  <td>
                     <input value="" type="text" class="form-control product_unit"  name="product_unit_0" readonly id="product_unit_0"/>
                  </td>
                  <td>
                     <input value="1" class="form-control product_qty" type="number" min="0" id="product_qty_0" name="product_qty_0">
                  </td>
                  <td>
                     <input value="" type="number" class=" form-control product_price"  name="product_price_0" id="product_price_0"/>
                  </td>
                  <td>
					<input type="text" value="" class="form-control product_tax_value" name="product_tax_value_0" id="product_tax_value_0" readonly>
                  </td>
                  <td>
                     <input type="text" value="" class=" form-control product_discount" id="product_discount_0" name="product_discount_0" readonly />
                  </td>
                  <td>
                     <input value="" type="text" class=" form-control product_subtotal" id="product_subtotal_0"  readonly />
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
<script>
	    $(document).ready(function () {
		//Nha cung cap
		function selectcustomer(){
			$("#order_customer_id").change(function(){
				var customer_id = $("#order_customer_id").val();
				$.ajax({
					type: "POST",
					url: "https://vietmy.clouderp.vn/api/orderCustomerid",
					data:{
						'customer_id': customer_id
					},
					dataType: 'json',
					success: function(data){
						if(data.status == 200){
							$("#mst").val(data.customer_tax_code);
							$("#address").val(data.customer_address);
							$("#cphone").val(data.customer_phone);
							$("#cemail").val(data.customer_email);
							$("#c_debt").val(data.customer_debt);
						}
					}
				});
			});	
		}
		//DK thanh toán
		function selectpaymentpolicy(){
			$("#order_payment_policy_id").change(function(){
				var id_payment = $("#order_payment_policy_id").val();
				$.ajax({
					type: "POST",
					url: "https://vietmy.clouderp.vn/api/orderGetPaymentPolicy",
					data: {
						'id_payment': id_payment
					},
					dataType: 'json',
					success: function(data){
						if(data.status == 200){
							$("#order_number_debt").val(data.data);
						}
					}
				});
				
			});
		}
		//thuoc cong ty
		function selectemployeeonbranch(){
			$("#order_employee_id").change(function(){
				var order_employee_id = $('#order_employee_id').val();
				$.ajax({
					type: "POST",
					url: "https://vietmy.clouderp.vn/api/orderGetBranch",
					data: {
						'order_employee_id': order_employee_id
					},
					dataType: 'json',
					success: function(data){
						if(data.status == 200){
							$("#company_name").val(data.data);
						}
					}
				});
			});
		}
		
		function getInfoInTable(){
			//Thanh tien
				//Tổng tiền khi chọn số lượng
			var total_amount = 0;
			
			$("#table-order").on('change', '.product_quantity', function(e) {
				var quantity = $(this).val();
				var priceElement = $(this).closest('td').next().find('input');
				var price = priceElement.val();
				var into_money = quantity * price;
				//thành tiền
				var elementintomoney = priceElement.closest('td').next().find('input');
				elementintomoney.val(into_money);
				//Tiền thuế
				var elementpercentvattax = elementintomoney.closest('td').next().find('select');
				var percentvattax = elementpercentvattax.val();
				var product_money_vat_tax = (into_money * percentvattax)/100;
				elementpercentvattax.closest('td').next().find('input').val(product_money_vat_tax);
				var summoney = 0;
				var sumvattax = 0;
				$(".product_into_money").each(function(){
					if (!this.value) {
						this.value = 0;
					}
					summoney += parseFloat(this.value);
				});
				$(".product_money_vat_tax").each(function(){
					if (!this.value) {
						this.value = 0;
					}
					sumvattax += parseFloat(this.value);
				});
				var sum = sumvattax + summoney;
				$("#total-amount-vat-tax").text(number_format(sumvattax,0,',','.'))
				$("#total-amount").text(number_format(summoney,0,',','.'));
				$("#sum-money").text(number_format(sum,0,',','.'));
				$("#total-amount-text").html(VNDtoText.read(sum));
				
			});
				//Tổng tiền khi thay đổi đơn giá
			$("#table-order").on('keyup', '.product_price', function(e) {
				var price = $(this).val();
				//console.log(price);
				var quantityElement = $(this).closest('td').prev().find('input');
				var quantity = quantityElement.val();
				var into_money = quantity * price;
				//Thành tiền
				var elementintomoney = $(this).closest('td').next().find('input');
				elementintomoney.val(into_money);
				//Tiền thuế
				var elementpercentvattax = elementintomoney.closest('td').next().find('select');
				var percentvattax = elementpercentvattax.val();
				var product_money_vat_tax = (into_money * percentvattax)/100;
				elementpercentvattax.closest('td').next().find('input').val(product_money_vat_tax);
				var summoney = 0;
				var sumvattax = 0;
				$(".product_into_money").each(function(){
					if (!this.value) {
						this.value = 0;
					}
					summoney += parseFloat(this.value);
				});
				$(".product_money_vat_tax").each(function(){
					if (!this.value) {
						this.value = 0;
					}
					sumvattax += parseFloat(this.value);
				});
				var sum = sumvattax + summoney;
				$("#total-amount-vat-tax").text(number_format(sumvattax,0,',','.'))
				$("#total-amount").text(number_format(summoney,0,',','.'));
				$("#sum-money").text(number_format(sum,0,',','.'));
				$("#total-amount-text").html(VNDtoText.read(sum));
				
			});
			
			//Tien thue GTGT khi chọn % thuế
			$("#table-order").on('change', '.product_vat_tax', function(e) {
				var product_vat_tax = $(this).val();
				var elementintomony = $(this).closest('td').prev().find('input');
				var into_money = elementintomony.val();
				var elementproductmoneyvattax = $(this).closest('td').next().find('input');
				
				var product_money_vat_tax = (into_money * product_vat_tax)/100;
				if(product_money_vat_tax == 'Infinity'){
					product_money_vat_tax = 0;
				}else{
					product_money_vat_tax = product_money_vat_tax;
				}
				elementproductmoneyvattax.val(product_money_vat_tax);
				var summoney = 0;
				var sumvattax = 0;
				$(".product_into_money").each(function(){
					if (!this.value) {
						this.value = 0;
					}
					summoney += parseFloat(this.value);
				});
				$(".product_money_vat_tax").each(function(){
					if (!this.value) {
						this.value = 0;
					}
					sumvattax += parseFloat(this.value);
				});
				var sum = sumvattax + summoney;
				$("#total-amount-vat-tax").text(number_format(sumvattax,0,',','.'))
				$("#total-amount").text(number_format(summoney,0,',','.'));
				$("#sum-money").text(number_format(sum,0,',','.'));
				$("#total-amount-text").html(VNDtoText.read(sum));
			});

			
			//Hien thi ma hang 
			$("#table-order").on('change', '.product_cat_id', function(e) {
				var cat_pr_id = $(this).val();
                console.log("aaaa");
				// var elementproductname = $(this).closest('td').next().find('input');
				// var elementunit = elementproductname.closest('td').next().find('input');
				// //console.log(cat_pr_id);
				// $.ajax({
				// 	type: "POST",
				// 	url: "https://vietmy.clouderp.vn/api/orderCategoryproduct",
				// 	data:{
				// 		'cat_pr_id': cat_pr_id
				// 	},
				// 	dataType: 'json',
				// 	success: function(data){
				// 		if(data.status == 200){
				// 			elementproductname.val(data.cat_product_name);
				// 			elementunit.val(data.cat_product_unit);
				// 		}
				// 	}
				// });
			});
			
		}
		
		//Them dong
		var rowcount = 1;
		function formatState (account) {
		  if (!account.element.value) {
			return account.text;
		  }
		  return account.element.text;
		};
		function formatselect()
		{
			for(var i = 0; i <= rowcount; i++)
			{
				$('#cat_product_id_' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				$('#cat_product_name' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				$('#product_unit_id' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				$('#product_quantity' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				$('#product_price' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				$('#product_into_money' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				$('#product_vat_tax' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				$('#product_money_vat_tax ' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				
			}
			
		}
		function addrowtableorder()
			{
				$("#add-row-order").on("click",function(){
					rowcount++
					//addnewincomerow
					//var cid = $(this).attr("data-id");
					$.ajax({
						"type": "POST",
						"url": "https://vietmy.clouderp.vn/api/addrowtableorder",
						"data": {
							'rowcount': rowcount
						},
						"dataType":'json',
						success:function(data){
							if(data.status == 200){
								$("#row-add-content").append(data.data);
								
								formatselect();
							}else{
								Swal.fire({
								  icon: 'error',
								  title: "Lỗi",
								  text: data.message,
								  footer: '<a href=""></a>'
								})
							}
						}
					
					});
					return false;
					
				});
			}
                $.validator.addMethod("alpha", function(value, element){

                    return this.optional(element) || value == value.match(/^[0-9, '']+$/);
            
                }, "Vui lòng nhập ký số!");
                $("#formorders").validate({
                    onfocusout: false,
                    onkeyup: false,
                    onclick: false,
                    rules: {
                        "order_customer_id":{ required: true },"order_name_contact":{ required: true},"order_warehouse_id":{ required: true },"order_payment_policy_id":{ required: true },"order_payment_active":{ required: true }, "order_active":{ required: true },"order_delivery_date":{ required: true },"product_code":{ required: true },"cat_product_name_id":{ required: true },"product_unit_id":{ required: true },"product_quantity":{required: true }, "product_price":{ required: true }
                        
                        
                    },
                    messages:{
                        order_customer_id: "Vui lòng chọn khách hàng/NCC",
                        order_name_contact: "Vui lòng nhập tên người liên hệ",
                        order_warehouse_id: "Vui lòng chọn thuộc kho",
                        order_payment_policy_id: "Vui lòng chọn điều khoản thanh toán",
                        order_payment_active: "Vui lòng chọn tình trạng thanh toán",
                        order_active: "Vui lòng chọn tình trạng đơn hàng",
                        order_delivery_date: "Vui lòng chọn ngày giao hàng",
                        product_code: "Vui lòng nhập mã sản phẩm",
                        product_unit_id: "Vui lòng chọn đơn vị tính",
                        cat_product_name_id: "Vui lòng nhập tên sản phẩm",
                        product_quantity: "Vui lòng nhập số lượng",
                        product_price: "Vui lòng nhập đơn giá"
                        }
                });
               
             
               
            //insert db
		$("#btnaddorder").click(function(){
            if($("#formorders").valid())
            {
				var order_customer_id       =   $("#order_customer_id").val();
				var order_name_contact      =   $("#order_name_contact").val();
				var order_employee_id       =   $("#order_employee_id").val();
				var order_description       =   $("#order_description").val();
				var order_warehouse_id      =   $("#order_warehouse_id").val();
				var order_payment_policy_id =   $("#order_payment_policy_id").val();
				var order_date              =   $("#order_date").val();
				var order_code              =   $("#order_code").val();
				var order_payment_active    =   $("#order_payment_active").val();
				var order_active            =   $("#order_active").val();
				var order_delivery_date     =   $("#order_delivery_date").val();
                //Thông tin hàng tiền
                var products = new Array();
                for(var i = 1; i <= rowcount; i++){
                    var product = [
                        $("#product_code_"+i).val(),
                        $("#cat_product_name_id_"+i).val(),
                        $("#product_unit_id"+i).val(),
                        $("#product_quantity_"+i).val(),
                        $("#product_price_"+i).val(),
                        $("#product_vat_tax"+i).val(),
                        $("#product_discount_"+i).val(),
                    ];
                    products.push(product);
                }
               
				$.ajax({
					type: "POST",
					url: "https://vietmy.clouderp.vn/api/addorders",
					data:{
						"order_customer_id":order_customer_id,
						"order_name_contact":order_name_contact,
						"order_employee_id":order_employee_id,
						"order_description":order_description,
						"order_warehouse_id": order_warehouse_id,
						"order_payment_policy_id": order_payment_policy_id,
						"order_date": order_date, 
						"order_code": order_code,
						"order_payment_active": order_payment_active,
						"order_active": order_active,
						"order_delivery_date": order_delivery_date,
                        //Thong tin hàng tiền
                        "products": products
					},
					dataType: "json",
					success: function(data){
						Swal.fire({
                            icon: 'success',
                            title: data.message,
                            footer: '<a href=""></a>',
                            timer: 1700
                          })
                          setTimeout(function(){ location.reload();     }, 2000);
					}
				});
                return false;
            }

			});
			$(".product_code").bind(function(){
				console.log('eee');
			})
			$('.product_code').autocomplete({
				serviceUrl: 'https://vietmy.clouderp.vn/api/searchproduct',
				onSelect: function (suggestion) {
					alert('You selected: ' + suggestion.code + ', ' + suggestion.name);
				}
			});
            function autocompleteProductcode(){
                $( ".product_codesss" ).autocomplete({
                    delay: 300,
                    minLength:1,
                    source: function(data, cb){
                    $.ajax({
                        method: "GET",
                        url: "https://vietmy.clouderp.vn/api/autocompleteProductcode",
                        data: {"data": data.term},
                        dataType: 'json',
                        success: function(res){
                            console.log(res); 
                            cb(res);
                        }
                    })
                }
                });
				
            }
       
		//Call function
		selectcustomer();
		selectemployeeonbranch();
		selectcustomer();
		selectpaymentpolicy();
		getInfoInTable();
        autocompleteProductcode();
		addrowtableorder();
		
    });
</script>
<?php include "footer.php";?>