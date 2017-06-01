@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-9">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>{{ $event->name }}</h5>
					</div>
					<div class="ibox-content">
						<h5>About:</h5>
						{!! $event->description !!}

						<h5>Location:</h5>
						<p>{{ $event->space_id ? $event->space->name.' '.$event->space->address.' '.$event->space->suburb : $event->location_other }}</p>

						<h5>Time:</h5>
						<p>
							@php($start = $event->start_time->setTimezone(Auth::user()->timezone))
							@php($finish = $event->finish_time->setTimezone(Auth::user()->timezone))

							@if ($start->format('Y-m-d') ==  $finish->format('Y-m-d'))
								{{ $start->format('l, j F Y, g:ia') }} - {{ $finish->format('g:ia') }}
							@else
								{{ $start->format('l, j F Y, g:ia') }} - {{ $finish->format('l, j F Y, g:ia') }}
							@endif
						</p>

						<h5>Cost:</h5>
						<p>{{ $event->paid ? 'Paid' : 'Free' }}</p>

						@if ($event->ticket_link)
							<h5>Tickets:</h5>
							<p><a href="{{ $event->ticket_link }}">Purchase Tickets</a></p>
						@endif
					</div>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Attendance</h5>
					</div>
					<div class="ibox-content">
						@if ($attendee_status)
							<p>You attendance status is currently: {{ $attendee_status->status }}</p><a onclick="change_status()">Change Attandance</a>
							<div id="change_status" style="display:none;">
								<br>
								<a onclick="update_attendance('Attending')"><button class="btn btn-md btn-primary">I'm going!</button></a>
								<a onclick="update_attendance('Maybe')"><button class="btn btn-md btn-primary">Maybe</button></a>
							</div>
						@else
							<a onclick="update_attendance('Attending')"><button class="btn btn-md btn-primary">I'm going!</button></a>
							<a onclick="update_attendance('Maybe')"><button class="btn btn-md btn-primary">Maybe</button></a>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		function update_attendance(status)
		{
			$.ajax({
				url: '/events/update-attendance/{{ $event->id }}',
				type: "post",
				data: {'status' : status, '_token': $('meta[name=_token]').attr('content')},
				success: function(data){
					location.reload();
				}
			});
		}

		function change_status()
		{
			$('#change_status').show();
		}
	</script>
@endsection
