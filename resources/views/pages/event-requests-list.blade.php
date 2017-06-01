@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="ibox">
						<div class="ibox-title">
							<h5>{{ $title or '' }}</h5>
						</div>
						<div class="ibox-content">
							@if(count($event_requests)!==0)
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Content</th>
											<th>Requested By</th>
											<th>Created At</th>
										</tr>
									</thead>
									<tbody>
									@foreach($event_requests as $event_request)
										<tr>

											<td>{{ $event_request->content }}</td>
											<td>{{ $event_request->user->first_name . ' ' . $event_request->user->last_name }}</td>
											<td>{{ $event_request->created_at->format('d M Y') }}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							@else
								<div class="text-center">
									<p>No event requests found in the system.</p>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection