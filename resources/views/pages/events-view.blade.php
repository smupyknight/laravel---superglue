@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>

						<div class="ibox-tools">
							<a href="/admin/events/edit/{{ $event->id }}" class="btn btn-default btn-xs">Edit Event</a>
						</div>
					</div>

					<div class="ibox-content">
						<div class="form-horizontal">
							<div class="form-group">
								<label class="col-lg-2 control-label">Event Title :</label>
								<div class="col-lg-4">
									<p class="form-control-static"> {{ $event->name }}</p>
								</div>
								<label class="col-lg-2 control-label">Location :</label>
								<div class="col-lg-4">
									<p class="form-control-static"> {{ $event->space_id ? $event->space->name.' '.$event->space->address.' '.$event->space->suburb : $event->location_other }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Type :</label>
								<div class="col-lg-4">
									<p class="form-control-static"> {{ $event->paid==0 ? 'Free' : 'Paid' }}</p>
								</div>
								<label class="col-lg-2 control-label">Ticket Link :</label>
								<div class="col-lg-4">
									<p class="form-control-static"> {{ $event->ticket_link }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Start time :</label>
								<div class="col-lg-4">
									<p class="form-control-static"> {{ $event->start_time }}</p>
								</div>
								<label class="col-lg-2 control-label">End time :</label>
								<div class="col-lg-4">
									<p class="form-control-static"> {{ $event->finish_time }}</p>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Description :</label>
								<div class="col-lg-10">
									<div class="well">
										{!! $event->description !!}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Event Attendees</h5>
					</div>
					<div class="ibox-content">
						@if (count($event->attendees))
							<table class="table table-striped">
								<thead>
									<tr>
										<th>ID</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Email</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody class="table_body">
									@foreach ($event->attendees as $attendee)
										<tr>
											<td>{{ $attendee->id }}</td>
											<td>{{ $attendee->user->first_name }}</td>
											<td>{{ $attendee->user->last_name }}</td>
											<td>{{ $attendee->user->email }}</td>
											<td>{{ $attendee->status }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No attendee registered for this Event</p>
							</div>
						@endif
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Cover Photo</h5>
					</div>
					<div class="ibox-content">
						@if ($event->cover_photo != '')
							<img src="{{ $event->getCoverImageUrl() }}" class="img img-responsive">
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
