<?php include_once "header.php"; ?>
<script>
	$(document).ready(function(e){
		var rowcount = 0;
		function formatState (account) {
		  if (!account.element.value) {
			return account.text;
		  }
		  return account.element.value;
		};
		
		function formatselect()
		{
			for(var i = 0; i <= rowcount; i++)
			{
				$('#income_detail_debit_' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
				$('#income_detail_credit_' + i).select2({
					width: '100%',
					templateSelection: formatState
				});
			}
			$(".row-amount").on("change",function(){
				var total = 0;
				$(".row-amount").each(function(){
					total += +$(this).val();
				});
				
				$("#total-amount").html(number_format(total,0,',','.'));
				$("#amounttotext").html(VNDtoText.read(total));
			});
			$(".btn-add-row").on("click",function(){
			var currentrow = rowcount;
			rowcount++;
			//addnewincomerow
			//var cid = $(this).attr("data-id");
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/addnewincomerow",
				"data": {
					'rowcount': rowcount
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						$("#list-detail").append(data.data);
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
		formatselect();
		
		$(".btn-assdd-row").on('click', function() {
			var $tr    = $(this).closest('.data-row');
			var $clone = $tr.clone();
			$clone.find(':text').val('');
			$tr.after($clone);
		});
		$("#btn-save").on("click",function()
		{
			var income_type 			= 	$("#income_type").val();
			var income_code 			= 	$("#income_code").val();
			var income_to 				= 	$("#income_to").val();
			var income_account_date 	= 	$("#income_account_date").val();
			var income_create_date 		= 	$("#income_create_date").val();
			var income_staff 			= 	$("#income_staff").val();
			var income_note 			= 	$("#income_note").val();
			var income_document 		= 	$("#income_document").val();
			var details = new Array();
			for(var i = 0; i <= rowcount;i++)
			{
				var detail =  [$("#income_detail_description_" + i).val(), $("#income_detail_credit_" + i).val(), $("#income_detail_debit_" + i).val(), $("#income_detail_amount_" + i).val()];
				details.push(detail);
			}
			$.ajax({
				"type": "POST",
				"url": "<?php echo XC_URL; ?>/api/newincome",
				"data": {
					'income_type': income_type,
					'income_code': income_code,
					'income_to': income_to,
					'income_account_date': income_account_date,
					'income_create_date': income_create_date,
					'income_staff': income_staff,
					'income_note': income_note,
					'income_document': income_document,
					'details' : details
				},
				"dataType":'json',
				success:function(data){
					if(data.status == 200){
						$(".btn-print-01").prop("disabled",false);
						$(".btn-print-02").prop("disabled",false);
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
	});
</script>
            <div class="content container-fluid">
               <div class="page-header">
                  <div class="row">
                     <div class="col-sm-12">
                        <h3 class="page-title">Phiếu thu số #<?php echo $income_code;?></h3>
                        <ul class="breadcrumb">
                           <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">Cloud ERP</a></li>
                           <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>/app/incomes">Phiếu thu</a></li>
                           <li class="breadcrumb-item active">Tạo phiếu</li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                     <div class="card">
                        <div class="card-body">
                           <form action="#">
                              <div class="row">
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Loại phiếu:</label>
                                       <input type="text" readonly="true" value="<?php echo $income_type_title;?>" class="form-control" style="font-weight: bold;">
									   <input type="hidden" id="income_type" value="<?php echo $income_type;?>">
                                    </div>
                                 </div>
								 <div class="col-md-4">
                                    <div class="form-group">
                                       <label id="label-object-title">Khách hàng:</label>
                                       <select id="income_to" class="select select2">
											
                                          <option disabled selected>Chọn Khách hàng</option>
                                          <?php foreach($customers as $customer)
										  {
											?>
											<option value="<?php echo $customer->id;?>"><?php echo $customer->customer_name;?></option>
											<?php
										  }
											?>
                                       </select>
                                    </div>
                                 </div>
								 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Số phiếu</label>
                                       <input type="text" readonly="true" id="income_code" value="<?php echo $income_code;?>" class="form-control" style="font-weight: bold;">
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Ngày hạch toán</label>
                                       <div class="cal-icon">
                                          <input id="income_account_date" class="form-control datetimepicker" type="text">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Ngày chứng từ</label>
                                       <div class="cal-icon">
                                          <input id="income_create_date" class="form-control datetimepicker" type="text">
                                       </div>
                                    </div>
                                 </div>
                                 
                                 <div class="col-md-4">
                                    <div class="form-group">
                                       <label id="label-object-title">Nhân viên thực hiện:</label>
                                       <select id="income_staff" class="select select2">
                                          <option disabled selected>Chọn nhân viên</option>
                                          <?php foreach($employees as $employee)
										  {
											?>
											<option value="<?php echo $employee->id;?>"><?php echo $employee->employee_name;?></option>
											<?php
										  }
											?>
                                       </select>
                                    </div>
                                 </div>
								 <div class="col-md-8">
                                    <div class="form-group">
                                       <label>Lý do</label>
                                       <input type="text" id="income_note" value="" class="form-control" style="font-weight: bold;">
                                    </div>
                                 </div>
								 <div class="col-md-4">
                                    <div class="form-group">
                                       <label>Kèm theo (chứng từ gốc)</label>
                                       <div class="input-group">
										<input type="number" id="income_document" class="form-control" placeholder="Số lượng" aria-label="Số lượng chứng từ gốc" aria-describedby="basic-addon2">
										<span class="input-group-text" id="basic-addon2" style="font-style: italic">chứng từ gốc</span>
										</div>
                                    </div>
                                 </div>
                              </div>
                              <div class="table-responsive mt-4">
                                 <table class="table table-hover">
                                    <thead>
                                       <tr>
                                          <th>Diễn giải</th>
                                          <th style="width: 10%">TK có</th>
                                          <th style="width: 10%">TK nợ</th>
                                          <th style="width: 20%">Số tiền</th>
                                          <th style="width: 7%">Thao tác</th>
                                       </tr>
                                    </thead>
                                    <tbody id="list-detail">
                                       <tr>
                                          <td>
                                             <input type="text" id="income_detail_description_0" class="form-control">
                                          </td>
										  <td>
                                             <select id="income_detail_credit_0" class="select select2withsearch">
												<?php 
												foreach($accounts as $account)
												{
												?>
													<option value="<?php echo $account->account_number;?>" ><?php echo $account->account_number;?>  - <?php echo $account->account_name;?></option>
												<?php
												}
												?>
										   </select>
                                          </td>
										  <td>
                                             <select id="income_detail_debit_0" class="select select2withsearch">
												<?php 
												foreach($accounts as $account)
												{
												?>
													<option value="<?php echo $account->account_number;?>"><?php echo $account->account_number;?>  - <?php echo $account->account_name;?></option>
												<?php
												}
												?>
										   </select>
                                          </td>
                                          <td>
                                             <input type="text" id="income_detail_amount_0" class="form-control row-amount">
                                          </td>
                                          <td class="add-remove text-end">
                                             <i data-row="0" class="fas fa-plus-circle btn-add-row"></i > <i class="fas fa-minus-circle btn-remove-row"></i>
                                          </td>
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                              <div class="table-responsive mt-4">
                                 <table class="table table-stripped table-center table-hover">
                                    <thead></thead>
                                    <tbody>
                                       <tr>
										   <td colspan="4" class="text-end">Tổng cộng: <span id="total-amount">0</span></td>
                                       </tr>
                                       <tr>
                                          <td colspan="4" class="text-end">Bằng chữ: <i id="amounttotext">Không đồng./.</i></td>
                                       </tr>
                                       
                                    </tbody>
                                 </table>
                              </div>
                              <div class="text-end mt-4">
                                 <button type="button" id="btn-save" class="btn btn-primary"><i class="fas fa-save"></i> Lưu</button>
                                 <button type="button" class="btn btn-warning"><i class="fas fa-paper-plane"></i> Lưu và gửi duyệt</button>
                                 <div class="btn-group">
								   <button type="button" disabled class="btn btn-success btn-print-01"><i class="fas fa-print"></i> In phiếu</button>
								   <button type="button" disabled class="btn  btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								   <span class="sr-only">Toggle Dropdown</span>
								   </button>
								   <div class="dropdown-menu">
									  <a class="dropdown-item  btn-print-01" href="#">In mẫu số 01-TT</a>
									  <a class="dropdown-item  btn-print-02" href="#">In 2 liên</a>
								   </div>
								</div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      
<?php include_once "footer.php"; ?>