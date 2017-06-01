@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-4">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Upcoming Start Dates</h5>
						<div class="ibox-tools">
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-striped">
							<thead>
								<th>Account</th>
								<th>Start Date</th>
								<th>Actions</th>
							</thead>
							<tbody>
								@foreach($upcoming_start_dates as $item)
									<tr>
										<td>{{ $item->name }}</td>
										<td>{{ $item->start_date->format('d/m/Y') }}</td>
										<td><a href="/admin/accounts/view/{{ $item->id }}"><button class="btn btn-xs btn-default">View</button></a></td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Upcoming End Dates</h5>
						<div class="ibox-tools">
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-striped">
							<thead>
								<th>Account</th>
								<th>Start Date</th>
								<th>Actions</th>
							</thead>
							<tbody>
								@foreach($upcoming_end_dates as $item)
									<tr>
										<td>{{ $item->name }}</td>
										<td>{{ $item->end_date->format('d/m/Y') }} <small>({{ $item->end_date->diffForHumans() }})</small></td>
										<td><a href="/admin/accounts/view/{{ $item->id }}"><button class="btn btn-xs btn-default">View</button></a></td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Upcoming Bills</h5>
						<div class="ibox-tools">
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-striped">
							<thead>
								<th>Account</th>
								<th>Start Date</th>
								<th>Actions</th>
							</thead>
							<tbody>
								<tr>
									<td colspan="3">
										No data yet.
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
