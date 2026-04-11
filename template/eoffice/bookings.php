<?php include_once "header.php"; ?>
<script src="<?php echo $template_path;?>/assets/plugins/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
<script type="text/javascript">
   $(function () {
       $('#event_from').datetimepicker();
       $('#event_to').datetimepicker({
   useCurrent: false //Important! See issue #1075
   });
       $("#event_from").on("dp.change", function (e) {
           $('#event_to').data("DateTimePicker").minDate(e.date);
       });
       $("#event_to").on("dp.change", function (e) {
           $('#event_from').data("DateTimePicker").maxDate(e.date);
       });
   });
</script>
<script>
        $(document).ready(function() {
            var KTCalendarBasic = function() {

				return {
					//main function to initiate the module
					init: function() {
						var todayDate = moment().startOf('day');
						var YM = todayDate.format('YYYY-MM');
						var YESTERDAY = todayDate.clone().subtract(1, 'day').format('YYYY-MM-DD');
						var TODAY = todayDate.format('YYYY-MM-DD');
						var TOMORROW = todayDate.clone().add(1, 'day').format('YYYY-MM-DD');

						var calendarEl = document.getElementById('kt_calendar');
						var calendar = new FullCalendar.Calendar(calendarEl, {
							plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid', 'list' ],
							
							isRTL: false,

							header: {
								left: 'prev,next today',
								center: 'title',
								right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
							},

							height: 800,
							contentHeight: 780,
							aspectRatio: 3,  // see: https://fullcalendar.io/docs/aspectRatio

							nowIndicator: true,
							now: TODAY + 'T09:25:00', // just for demo

							views: {
								dayGridMonth: { buttonText: 'month' },
								timeGridWeek: { buttonText: 'week' },
								timeGridDay: { buttonText: 'day' }
							},

							defaultView: 'dayGridMonth',
							defaultDate: TODAY,

							editable: true,
							eventLimit: true, // allow "more" link when too many events
							navLinks: true,
							events: 'https://e-office.anlocgroup.vn/page/booking',
								

							eventRender: function(info) {
								var element = $(info.el);

								if (info.event.extendedProps && info.event.extendedProps.description) {
									if (element.hasClass('fc-day-grid-event')) {
										element.data('content', info.event.extendedProps.description);
										element.data('placement', 'top');
										KTApp.initPopover(element);
									} else if (element.hasClass('fc-time-grid-event')) {
										element.find('.fc-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
									} else if (element.find('.fc-list-item-title').lenght !== 0) {
										element.find('.fc-list-item-title').append('<div class="fc-description">' + info.event.extendedProps.description + '</div>');
									}
								}
							}
						});

						calendar.render();
					}
				};
			}();
			jQuery(document).ready(function() {
				KTCalendarBasic.init();
			});
        });
		
    </script>
	<script>
		$(document).ready(function(){
			$("#btnbooking").click(function(){
				var event_title = $('#event_title').val();
				var event_from = $('#event_from').val();
				var event_host = $("#event_host").val();
				var event_to = $("#event_to").val();
				var event_assign_to = $("#event_assign_to").val();
				var event_description = $("#event_description").val();
				var event_type = $("#event_type").val();
				$.ajax({
					type:"POST",
					url:"<?php echo XC_URL;?>/api/addbookings",
					data:{
						'event_title':event_title,
						'event_from': event_from,
						'event_host': event_host,
						'event_to': event_to,
						'event_assign_to': event_assign_to,
						'event_description':event_description,
						'event_type': event_type
					},
					dataType: 'json',
					success: function(data){
						if(data.status == 200){
							Swal.fire({
							  icon: 'success',
							  title: "Đặt thành công",
							  footer: '<a href=""></a>',
							  timer: 1700
							})
							setTimeout(function(){ location.reload();     }, 2000);
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
				
			});
		});
	</script>
<div class="content container-fluid">

    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">Quản lý phòng họp</h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo XC_URL;?>">CloudERP</a></li>
                    <li class="breadcrumb-item active">Quản lý phòng họp</li>
                </ul>
            </div>
            <div class="col-auto text-right float-right ml-auto">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_event">Đăng ký lịch</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card bg-white">
                <div class="card-body">
                    <div id="kt_calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="add_event" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đăng ký lịch mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" >
                        <div class="row" >
							<div class="col-md-12" >
                                <div class="form-group">
									<label>Nội dung tóm tắt</label>
									<input type="text" class="form-control" id='event_title'>
								</div>
							</div>
						</div>
                        <div class="row" >
                            <div class="col-md-6" >
                                <div class="form-group">
                                    <label>Từ:</label>
                                    <div class="cal-icon">
                                        <input id="event_from" class="form-control datetimepicker-2" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Chủ trì:</label>
                                    <select id="event_host" class="select select2">
                                          <option disabled selected>Chọn chủ trì</option>
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
                            <div class="col-md-6" data-select2-id="25">
                                <div class="form-group">
                                    <label>Đến:</label>
                                    <div class="cal-icon">
                                        <input id="event_to" class="form-control datetimepicker-2" type="text">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Thành phần tham dự:</label>
                                    <select id="event_assign_to" multiple="multiple" class="select select2">
                                          
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
							
                        </div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Nội dung:</label>
									<textarea rows="5" cols="5" class="form-control" placeholder="Nội dung" id = "event_description"></textarea>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
										<label>Loại phòng:</label>
										<select id="event_type"  class="select form-control">
												<option disabled selected="selected">Chọn loại</option>
												<option value="1">Họp</option>
												<option value="2">Đào tạo</option>
												<option value="3">Tiếp khách</option>
												<option value="4">Khác</option>
										   </select>
								</div>
							</div>
							
							<div class="text-end mt-4">
                                    <button type="button" class="btn btn-primary" id="btnbooking">Đặt lịch</button>
                                </div>
						</div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal custom-modal fade none-border" id="my_event">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Event</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success save-event submit-btn">Create event</button>
                    <button type="button" class="btn btn-danger delete-event submit-btn" data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal custom-modal fade" id="add_new_event">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Category</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label>Category Name</label>
                            <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name" />
                        </div>
                        <div class="form-group mb-0">
                            <label>Choose Category Color</label>
                            <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                <option value="success">Success</option>
                                <option value="danger">Danger</option>
                                <option value="info">Info</option>
                                <option value="primary">Primary</option>
                                <option value="warning">Warning</option>
                                <option value="inverse">Inverse</option>
                            </select>
                        </div>
                        <div class="submit-section">
                            <button type="button" class="btn btn-primary save-category submit-btn" data-dismiss="modal">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>



<?php include_once "footer.php"; ?>