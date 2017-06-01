@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="ibox">
						<div class="ibox-title">
							<h5>{{ $title or '' }}</h5>
							<div class="ibox-tools">

							</div>
						</div>
						<div class="ibox-content">
							@if(count($events))
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Title</th>
											<th>Location</th>
											<th>Created At</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
									@foreach($events as $event)
										<tr>
											<td>{{ $event->name }}</td>
											<td>{{ $event->space_id ? $event->space->name.' '.$event->space->address.' '.$event->space->suburb : $event->location_other }}</td>
											<td>{{ $event->created_at }}</td>
											<td>
												<div class="btn-group">
													<a href="/events/view/{{ $event->id }}"><button class="btn btn-default btn-xs">View Event</button></a>
												</div>
											</td>
										</tr>
									@endforeach
									</tbody>
								</table>
								@if ($events->lastPage() > 1)
									<tr>
										<td colspan="7" align="right">
											{{ $events->render() }}
										</td>
									</tr>
								@endif
							@else
								<div class="text-center">
									<p>No events found in the system</p>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection