@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row animated fadeInDown">
		<div class="col-lg-12">
			  <!-- Nav tabs -->
			  <ul id="spaces" class="nav nav-tabs" role="tablist">
			    @foreach($spaces as $i => $space)
			    	@if(count($space->rooms))
				    	<li role="presentation" {!!$i == 0? 'class="active"' : ''!!}>
				    		<a data-id="{{$space->id}}" href="#" class="select-space">{{ $space->name }}</a>
				    	</li>
				    @endif
				@endforeach
			  </ul>
		</div>
		<div class="col-lg-3">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Select Rooms</h5>
					
				</div>
				<div class="ibox-content">
					<form class="form-horizontal">
						<div class="form-group">
							<div id="rooms-of-selected-space"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Availability </h5>
					<div class="ibox-tools">
						<button onclick="newBooking();" type="buttton" class="btn btn-primary btn-xs">New Booking</buttton>
					</div>
				</div>
				<div class="ibox-content">
					<div id="calendar"></div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
	
@section('scripts')

<script src="/js/modalform.js"></script>
<!-- Full Calendar -->
<script src="/js/fullcalendar/fullcalendar.min.js"></script>
<script src="/js/plugins/iCheck/icheck.min.js"></script>

<script>

	$(document).ready(function() {

		/* initialize the external events
		 -----------------------------------------------------------------*/


		$('#external-events div.external-event').each(function() {

			// store data so the calendar knows to render an event upon drop
			$(this).data('event', {
				title: $.trim($(this).text()), // use the element's text as the event title
				stick: true // maintain when user navigates (see docs on the renderEvent method)
			});

			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 1111999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});

		});

		selectSpace();
		$('#spaces li:eq(0) a').click();
		createCalendar();
	});

	function createCalendar() {
		full = $('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},eventSources: [
		        // your event source
		        {
		            url: '/bookings/load-bookings',
		            type: 'get',
		            data: {
		                space_id: function(){
		                	return $('#spaces li.active a').data('id');
		                },
		                room_ids: function(){
		                	return selectedRooms();
		                },
		            },
		            error: function() {
		            	bootbox.alert('At least one room must be selected');
		            },
		        }
			],
		    eventClick: function(calEvent, jsEvent, view) {
		    	if (calEvent.is_future_data && calEvent.is_my_event) {
		    		editBooking(calEvent);
		    	}
		    }

		});
	}

	var spaces = {
		@foreach($spaces as $space)
			{{$space->id}}: [
				@foreach($space->rooms as $room)
					{ id: {{$room->id}}, name: "{{$room->name}}", credit: "{{$room->credits_per_hour}}" },
				@endforeach
			],
		@endforeach
	}

	/*
	* set date time picker
	*/
	function datePickers() {
	    $('#start').datetimepicker({
	    	format: "ddd, DD MMM YYYY H:mm:ss ZZ",
	    	stepping: 30,
	    	minDate: new Date
	    });
	    $('#end').datetimepicker({
	    	format: "ddd, DD MMM YYYY H:mm:ss ZZ",
	    	stepping: 30,
	    	minDate: new Date
	    });

	    $('#start, #end').datetimepicker().on('dp.change', function(e) {
	    	calculateCredits();
	    });

	}

	function spaceRoomsHtml() {
		var html = '';
		$.each(spaces[$('#spaces li.active a').data('id')], function(i, room) {
			html += '<span class="col-md-4"><div class="checkbox">'+
						'<label> <input type="checkbox" class="select-room" name="room_ids[]" data-credit="'+room.credit+'" value="'+room.id+'"> '+room.name+'</label>'+
					'</div></span>'
		});
		return html;
	}

	function newBooking() {
		var form_html = ''+
					'<form id="booking-form" action="/bookings/create" class="form-horizontal" method="post" enctype="multipart/form-data">'+
	        			'<div class="form-group">'+
	        				'<label class="col-md-12 control-label"> Your Credit Balance: <span class="user-credits" data-credit="{{$user_credit}}">{{$user_credit}}</span></label>'+
    					'</div>'+
	        			'<div class="form-group">'+
	        				'<label class="col-md-3 control-label"> Select Rooms</label>'+
	        				'<div id="rooms-container" class="col-md-9">'+
		        				spaceRoomsHtml()+
        					'</div>'+
    					'</div>'+
	        			'<div class="form-group">'+
	        				'<label class="col-md-3 control-label"> Start Date</label>'+
	        				'<div class="col-md-9">'+
	        					'<input id="start" name="start" type="text" class="form-control input-sm datetime-picker">'+
	        				'</div>'+
	        			'</div>'+
	        			'<div class="form-group">'+
	        				'<label class="col-md-3 control-label"> End Date</label>'+
	        				'<div class="col-md-9">'+
	        					'<input id="end" name="end" type="text" class="form-control input-sm datetime-picker">'+
	        				'</div>'+
	        			'</div>'+
        				'<div class="form-group">'+
        					'<label class="col-md-3 control-label"> Name</label>'+
	        				'<div class="col-md-9">'+
	        					'<input type="text" name="name" class="form-control input-sm">'+
        					'</div>'+
        				'</div>	        			'+
        				'<div class="form-group">'+
        					'<label class="col-md-3 control-label"> Private</label>'+
	        				'<div class="col-md-9">'+
		        				'<div class="checkbox">'+
		        					'<label>'+
										'<input type="checkbox" name="is_private">'+
		        					'</label>'+
								'</div>'+
	        				'</div>'+
	        			'</div>'+
	        			'<div class="form-group">'+
        					'<label class="col-md-3 control-label"> Reminder</label>'+
	        				'<div class="col-md-9">'+
								'<select name="reminder" class="form-control">'+
									'<option value="0">None</option>'+
									'<option value="5">5 minutes before</option>'+
									'<option value="10">10 minutes before</option>'+
									'<option value="15">15 minutes before</option>'+
								'</select>'+
	        				'</div>'+
	        			'</div>'+
	        			'{{csrf_field()}}'+
	        		'</form>';
		modalform.dialog({
			bootbox: {
				title: 'Room Booking',
				message: form_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Book',
						className: 'btn-primary'
					}
				}
			},
			autofocus : false,
			after_init : function() {
				datePickers();
				calculateCredits();
				$('.select-room,#end,#start').on('change', function(e) {
					calculateCredits();
				});
			}
		});
	}

	function editBooking($booking) {
		var form_html = ''+
				'<form id="edit-booking-form" action="/bookings/edit/'+$booking.booking_id+'" class="form-horizontal" method="post" enctype="multipart/form-data">'+
        			'<div class="form-group">'+
        				'<label class="col-md-12 hidden control-label"> Your Credit Balance: <span class="user-credits" data-credit="{{$user_credit}}">{{$user_credit}}</span></label>'+
        				'<label class="col-md-12 control-label">'+
        					'<buttton type="buttton" class="btn btn-danger btn-xs delete-event" title="Delete Event"><i class="fa fa-trash"></i></buttton>'+
    					'</label>'+
					'</div>'+
        			'<div class="form-group">'+
        				'<label class="col-md-3 control-label"> Select Rooms</label>'+
        				'<div id="rooms-container" class="col-md-9">'+
	        				spaceRoomsHtml()+
    					'</div>'+
					'</div>'+
        			'<div class="form-group">'+
        				'<label class="col-md-3 control-label"> Start Date</label>'+
        				'<div class="col-md-9">'+
        					'<input id="start" name="start" type="text" class="form-control input-sm datetime-picker">'+
        				'</div>'+
        			'</div>'+
        			'<div class="form-group">'+
        				'<label class="col-md-3 control-label"> End Date</label>'+
        				'<div class="col-md-9">'+
        					'<input id="end" name="end" type="text" class="form-control input-sm datetime-picker">'+
        				'</div>'+
        			'</div>'+
    				'<div class="form-group">'+
    					'<label class="col-md-3 control-label"> Name</label>'+
        				'<div class="col-md-9">'+
        					'<input type="text" id="name" name="name" class="form-control input-sm">'+
    					'</div>'+
    				'</div>	        			'+
    				'<div class="form-group">'+
    					'<label class="col-md-3 control-label"> Private</label>'+
        				'<div class="col-md-9">'+
	        				'<div class="checkbox">'+
	        					'<label>'+
									'<input type="checkbox" id="is_private" name="is_private">'+
	        					'</label>'+
							'</div>'+
        				'</div>'+
        			'</div>'+
        			'<div class="form-group">'+
    					'<label class="col-md-3 control-label"> Reminder</label>'+
        				'<div class="col-md-9">'+
							'<select id="reminder" name="reminder" class="form-control">'+
								'<option value="0">None</option>'+
								'<option value="5">5 minutes before</option>'+
								'<option value="10">10 minutes before</option>'+
								'<option value="15">15 minutes before</option>'+
							'</select>'+
        				'</div>'+
        			'</div>'+
        			'<input type="hidden" id="booking_id" name="booking_id" value="'+$booking.booking_id+'">'+
        			'{{csrf_field()}}'+
        		'</form>';
		modalform.dialog({
			bootbox: {
				title: 'Room Booking',
				message: form_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Book',
						className: 'btn-primary'
					}
				}
			},
			autofocus : false,
			after_init : function() {
				datePickers();
				deleteEvent();
				$('#start').val($booking.start.format('ddd, DD MMM YYYY H:mm:ss ZZ'));
				$('#end').val($booking.end.format('ddd, DD MMM YYYY H:mm:ss ZZ'));
				$('#reminder').val($booking.reminder);
				$('#name').val($booking.title);
				$('#is_private').prop('checked', $booking.is_private?true: false);
				var rooms = $booking.room_ids.split(',');
				$('#edit-booking-form .select-room').each(function(i, room) {
					$(room).prop('checked', ($.inArray($(room).val().toString() ,rooms) != -1)?true:false);
				});
			}
		});
	}

	function deleteEvent() {
		$('.delete-event').on('click', function(event) {
			var delete_booking_html = ''+
			'<form action="/bookings/delete/'+$('#booking_id').val()+'" method="post" class="form-horizontal">'+
    			'<input type="hidden" id="booking_id" name="booking_id" value="'+$('#booking_id').val()+'">'+
				'{{ csrf_field() }}'+
			'</form>';

			modalform.dialog({
				bootbox: {
					title: 'Are you sure want to delete this Booking?',
					message: delete_booking_html,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Delete',
							className: 'btn-primary'
						}
					}
				},
			});
		});
	}

	function calculateCredits() {
		var duration = moment.duration(moment(new Date($('#end').val())).diff(moment(new Date($('#start').val()))));
		var minutes = duration.asMinutes();
		var hours = 1;
		if (minutes > 60) {
			hours = (minutes%60 == 0)? (minutes/60): parseInt(minutes/60) + 1;
		}
		var credit = 0;
		$('.select-room:checked').each(function(i, room) {
			credit += hours * $(room).data('credit');
		});
		$('.user-credits').text($('.user-credits').data('credit') - credit);
	}

	function selectSpace() {
		$('.select-space').on('click', function(event) {
			event.preventDefault();
			$(this).parents('ul:eq(0)').find('li.active').removeClass('active');
			$(this).parents('li').addClass('active');
			var html = '';
			$.each(spaces[$(this).data('id')], function(i, room) {
				html += '<span class="col-md-12"><div class="checkbox">'+
							'<label> <input type="checkbox" class="select-room" name="room_ids[]" data-credit="'+room.credit+'" value="'+room.id+'" checked> '+room.name+'</label>'+
						'</div></span>'
			});
			$('#rooms-of-selected-space').html(html);
			$('.select-room').on('change', function(event) {
				event.preventDefault();
				rebuildCalendar();
			});
			rebuildCalendar();
		});
	}

	function selectedRooms() {
		var ids = [];
		$('#rooms-of-selected-space .select-room:checked').each(function(i, room) {
			ids.push($(room).val());
		});
		return ids;
	}

	function rebuildCalendar() {
		$('#calendar').fullCalendar('destroy');	
		createCalendar();
	}

	modalform.options.success = function() {
		bootbox.hideAll();
		rebuildCalendar();
	}
</script>

@endsection