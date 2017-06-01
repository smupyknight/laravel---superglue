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
							@if(count($mentor_requests)!==0)
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Mentor Name</th>
											<th>Topic</th>
											<th>Requested By</th>
											<th>Created At</th>
										</tr>
									</thead>
									<tbody>
									@foreach($mentor_requests as $mentor_request)
										<tr>
											<td>{{ $mentor_request->mentor->first_name . ' ' . $mentor_request->mentor->last_name }}</td>
											<td>{{ $mentor_request->topic }}</td>
											<td>{{ $mentor_request->member->first_name . ' ' . $mentor_request->member->last_name }}</td>
											<td>{{ $mentor_request->created_at->format('d M Y') }}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							@else
								<div class="text-center">
									<p>No mentor requests found in the system.</p>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection