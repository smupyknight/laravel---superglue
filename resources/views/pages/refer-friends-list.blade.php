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
							@if(count($refer_friends)!==0)
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Referrer Name</th>
											<th>Name</th>
											<th>Email</th>
											<th>Phone</th>
											<th>Message</th>
										</tr>
									</thead>
									<tbody>
									@foreach($refer_friends as $refer_friend)
										<tr>
											<td>{{ $refer_friend->user->first_name. ' ' .$refer_friend->user->last_name}}</td>
											<td>{{ $refer_friend->name }}</td>
											<td>{{ $refer_friend->email }}</td>
											<td>{{ $refer_friend->phone }}</td>
											<td>{{ $refer_friend->message }}</td>
										</tr>
									@endforeach
									</tbody>
								</table>
							@else
								<div class="text-center">
									<p>No friend referrals found in the system</p>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection