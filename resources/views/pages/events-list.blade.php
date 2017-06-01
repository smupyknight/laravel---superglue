@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/admin/events/create" class="btn btn-primary btn-xs">Create Event</a>
						</div>
					</div>
					<div class="ibox-content">
						@if($events)
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Title</th>
										<th>Location</th>
										<th>Type</th>
										<th>Start Date</th>
										<th>Status</th>
									</tr>
								</thead>
								<tbody>
									@foreach($events as $event)
										<tr>
											<td>{{ $event->name }}</td>
											<td>{{ $event->space_id ? $event->space->name.' '.$event->space->address.' '.$event->space->suburb : $event->location_other }}</td>
											<td>{{ $event->paid==0 ? 'Free' : 'Paid' }}</td>
											<td>{{ $event->start_time }}</td>
											<td>{{ $event->status }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/admin/events/view/{{ $event->id }}">View</a></li>
														<li><a href="/admin/events/edit/{{ $event->id }}">Edit</a></li>
														<li><a href="/admin/events/create?copy={{ $event->id }}">Copy</a></li>
														<li class="divider"></li>
														<li><a href="/admin/events/delete/{{ $event->id }}">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No events found in the system, please <a href="/admin/events/create">create</a> one.</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
