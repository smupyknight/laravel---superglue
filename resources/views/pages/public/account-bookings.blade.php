@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-sm-12">
			<div class="ibox">
				<div class="ibox-title">
					<h5>{{ $title or '' }}</h5>
					<div class="ibox-tools">
						<a href="/bookings" class="btn btn-primary btn-xs">Create a Booking</a>
					</div>
				</div>
				<div class="ibox-content">
					@if($user->bookings)
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Title</th>
									<th>Rooms</th>
									<th>Start Date</th>
									<th>End Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							@foreach($user->bookings as $booking)
								<tr data-id="{{$booking->id}}" data-start="{{$booking->start_date}}" data-end="{{$booking->end_date}}" data-title="{{$booking->name}}" data-reminder="{{$booking->reminder}}" data-private="{{$booking->is_private}}">
									<td>{{ $booking->name }}</td>
									<td>
										@foreach($booking->rooms as $room)
											<p class="booking-room" data-id="{{ $room->id }}" data-space="{{ $room->space_id }}">{{ $room->name }}</p>
										@endforeach</td>
									<td>{{ $booking->start_date->timezone($booking->rooms()->first()->space->timezone) }} - {{ $booking->start_date->timezone($booking->rooms()->first()->space->timezone)->diffForHumans() }}</td>
									<td>{{ $booking->start_date->timezone($booking->rooms()->first()->space->timezone) }}</td>

									<td>
										<div class="btn-group">
											<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="#" class="edit-booking">Edit</a></li>
												<li class="divider"></li>
												<li><a href="#" class="delete-event">Delete</a></li>
											</ul>
										</div>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					@else
						<div class="text-center">
							<p>No bookings found in the system, please <a href="/bookings">create</a> one.</p>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@endsection


@section('scripts')
	<script src="/js/modalform.js"></script>
	<script>
	var space_id = 0;
	$(function(){
		deleteEvent();

		$('.edit-booking').on('click', function(event) {
			event.preventDefault();
			var tr = $(this).parents('tr:eq(0)');
			space_id = $(tr).find('.booking-room:eq(0)').data('space');;
			var room_ids = [];
			$(tr).find('.booking-room').each(function(i, room) {
				room_ids.push($(room).data('id'));
			})

			$booking = {
				booking_id	:	$(tr).data('id'),
				end	:	$(tr).data('end'),
				is_future_data	:	1,
				is_private	:	$(tr).data('private'),
				reminder	:	$(tr).data('reminder'),
				room_ids	:	room_ids.toString(),
				start	:	$(tr).data('start'),
				title:	$(tr).data('title')
			}
			editBooking($booking);
		})
	});

	function deleteEvent() {
		$('.delete-event').on('click', function(event) {
			var tr = $(this).parents('tr:eq(0)');
			var delete_booking_html = ''+
			'<form action="/bookings/delete/'+$(tr).data('id')+'" method="post" class="form-horizontal">'+
    			'<input type="hidden" id="booking_id" name="booking_id" value="'+$(tr).data('id')+'">'+
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

	function editBooking($booking) {
		var form_html = ''+
				'<form id="booking-form" action="/bookings/edit/'+$booking.booking_id+'" class="form-horizontal" method="post" enctype="multipart/form-data">'+
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
				title: 'Edit Booking',
				message: form_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Update',
						className: 'btn-primary'
					}
				}
			},
			autofocus : false,
			after_init : function() {
				datePickers();
				$booking.start = moment($booking.start);
				$booking.end = moment($booking.end);
				$('#start').val($booking.start.format('ddd, DD MMM YYYY H:mm:ss ZZ'));
				$('#end').val($booking.end.format('ddd, DD MMM YYYY H:mm:ss ZZ'));
				$('#reminder').val($booking.reminder);
				$('#name').val($booking.title);
				$('#is_private').prop('checked', $booking.is_private?true: false);
				var rooms = $booking.room_ids.split(',');
				$('.select-room').each(function(i, room) {
					$(room).prop('checked', ($.inArray($(room).val().toString() ,rooms) != -1)?true:false);
				});
			}
		});
	}

	/*
	* set date time picker
	*/
	function datePickers() {
	    $('#start').datetimepicker({
	    	format: "ddd, DD MMM YYYY H:mm:ss ZZ",
	    	minDate: new Date
	    });
	    $('#end').datetimepicker({
	    	format: "ddd, DD MMM YYYY H:mm:ss ZZ",
	    	minDate: new Date
	    });

	    $('#start, #end').datetimepicker().on('dp.change', function(e) {
	    	calculateCredits();
	    });

	}

	function spaceRoomsHtml() {
		var html = '';
		$.each(spaces[space_id], function(i, room) {
			html += '<span class="col-md-4"><div class="checkbox">'+
						'<label> <input type="checkbox" class="select-room" name="room_ids[]" data-credit="'+room.credit+'" value="'+room.id+'"> '+room.name+'</label>'+
					'</div></span>'
		});
		return html;
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
	</script>
@endsection
