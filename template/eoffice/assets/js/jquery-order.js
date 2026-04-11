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