@extends('layouts.default')
@section('content')
	<style>
		.fc-time {
			display:none !important;
		}
	</style>
	<div class="wrapper wrapper-content animated fadeInRight">

			<div class="row">
				<form method="GET" action="/mentors">
					<div class="col-sm-12">
						<div class="input-group">
							<input type="text" placeholder="Search Mentors" class="input form-control" name="global_search" value="{{Request::get('global_search')}}">
							<span class="input-group-btn">
								<button type="submit" class="btn btn btn-primary "> <i class="fa fa-search"></i> Search</button>
							</span>
						</div>
					</div>

				</form>
			</div>
			<hr>
			<div class="row">
				@if(count($mentors))
					@foreach($mentors as $mentor)
						<div class="col-lg-4">
							<div class="contact-box">
								<a href="">
								<div class="col-sm-4">
									<div class="text-center">
										<img alt="image" class="img-circle m-t-xs img-responsive" width="150px" src="{{ $mentor->avatar !== '' ? $mentor->getAvatarUrl() : 'http://placehold.it/100x100' }}">
										<div class="m-t-xs font-bold">{{ $mentor->job_title or '' }}</div>
									</div>
								</div>
								</a>
								<div class="col-sm-8">
									<h3><strong>{{ $mentor->first_name . ' ' . $mentor->last_name }}</strong></h3>
									<p><i class="fa fa-map-marker"></i> {{ $mentor->account->space->name }}</p>
									<address>
										<strong>{{ $mentor->company_name }}</strong><br>
										Industry: {{ $mentor->industry }}<br>
										<i class="fa fa-twitter"></i> {!! $mentor->twitter_handle != '' ? '<a href="https://twitter.com/' . $mentor->twitter_handle . '">' . $mentor->twitter_handle . '</a>' : ' ' !!}<br>
									</address>
								</div>
								<div class="pull-right">
									<button data-id="{{$mentor->id}}" class="btn btn-primary btn-sm btn-request-booking  m-y-10">Request Booking</button>
									<button data-id="{{$mentor->id}}" class="btn btn-primary btn-sm btn-book-mentor  m-y-10">Book Mentor</button>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					@endforeach
				@else
				<p>No mentors found.</p>
				@endif
			</div>
			<div class="row text-center">
				{!! $mentors->render() !!}
			</div>
		</div>
@endsection

@section('scripts')
		<script src="/js/modalform.js"></script>
		<script src="/js/fullcalendar/fullcalendar.min.js"></script>
		<script>
		var add_mentor_reqest = ''+
			'<form action="/mentors/request-mentor" method="post" class="form-horizontal">'+
				'<div class="form-group">'+
					'<div class="col-md-12"><label class="control-label">Topic</label>'+
					'<input type="text" name="topic" class="form-control" /></div>'+
				'</div>'+
				'<input type="hidden" name="mentor_id" value="">'+
				'{{ csrf_field() }}'+
			'</form>';

		$('.btn-request-booking').on('click', function() {

			var mentor_id = $(this).data('id');

			modalform.dialog({
				bootbox: {
					title: 'Request Mentor',
					message: add_mentor_reqest,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Submit Request',
							className: 'btn-primary'
						}
					}
				},
				after_init: function() {
					$('.modal [name="mentor_id"]').val(mentor_id);
				}
			});
		});

		$('.btn-book-mentor').on('click',function(){
			var calendar_html = '<div id="calendar"></div>';

			var mentor_id = $(this).data('id');

			var dialog = bootbox.dialog({
				title : 'Choose day to book for mentor.',
				message : calendar_html,
				buttons : {
					cancel : {
						label : 'Cancel',
						className : 'btn-default'
					}
				}
			});

			dialog.init(function(){
				$('#calendar').html('<div style="text-align:center"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>');

				setTimeout(function(){ create_calendar(mentor_id) }, 500);
			});
		});

		function create_calendar(mentor_id)
		{
			$.ajax({
				url: '/mentors/calendar/' + mentor_id,
				type: "get",
				success: function(data) {
					if (data.length == 0) {
						$('#calendar').html('There are no available schedules for this mentor.');
						return false;
					}

					$('#calendar').html('');
					$('#calendar').fullCalendar({
						events: data,
						eventClick : function( event, jsEvent, view ) {
							var schedule_id = event.id;
							var caseNumber = Math.floor((Math.abs(jsEvent.offsetX + jsEvent.currentTarget.offsetLeft) / $(this).parent().parent().width() * 100) / (100 / 7));
							    // Get the table
							    var table = $(this).parent().parent().parent().parent().children();
							    $(table).each(function(){
							        // Get the thead
							        if($(this).is('thead')){
							            var tds = $(this).children().children();
							            var date = $(tds[caseNumber]).attr("data-date");
							            bootbox.hideAll();
							            show_day_list(schedule_id,date);
							        }
							    });
						},
					});
				}
			});
		}

		function show_day_list(schedule_id,date)
		{
			$.ajax({
				url: '/mentors/availability/' + schedule_id + '/' + date,
				type: "get",
				success: function(data) {
					bootbox.dialog({
						title : 'Book Mentor',
						message : data,
						buttons : {
							cancel : {
								label : 'Cancel',
								className : 'btn-default'
							}
						}
					});
				}
			});
		}

		function book_mentor(mentor_id,date)
		{
			bootbox.hideAll();
			bootbox.confirm("Are you sure you want to book this mentor?", function(result){
				if (result) {
					$.ajax({
						url: '/mentors/book-mentor',
						type: "post",
						data : {
							'mentor_id' : mentor_id,
							'start' : date,
							'_token' : $('meta[name=_token]').attr('content')
						},
						success: function(data) {
							document.location.reload();
						}
					});
				}
			});
		}
		</script>
@endsection