@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/bookings/create" class="btn btn-primary btn-xs">New Booking</a>
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Booking Name</th>
									<th>Space</th>
									<th>Start</th>
									<th>End</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody class="table_body">
								@if(count($bookings))
									@foreach($bookings as $booking)
										<tr>
											<td>{{ $booking->name }}</td>
											<td>{{ $booking->rooms()->first()->space->name }}</td>
										<td>{{ $booking->start_date->setTimezone($booking->rooms()->first()->space->timezone)->toDayDateTimeString() }}</td>
											<td>{{ $booking->end_date->setTimezone($booking->rooms()->first()->space->timezone)->toDayDateTimeString() }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="">View</a></li>
														<li><a href="}">Edit</a></li>
														<li class="divider"></li>
														<li><a href="#">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
									@if ($bookings->count() > 25)
										<tr>
											<td colspan="7" align="right">
												{{$bookings->render()}}
											</td>
										</tr>
									@endif
								@else
								    <tr>
								    	<td colspan="7" class="text-center">No bookings found</td>
								    </tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')

@endsection