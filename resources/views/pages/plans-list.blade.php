@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/admin/plans/create" class="btn btn-primary btn-xs">Create Plan</a>
						</div>
						{{ csrf_field() }}
					</div>
					<div class="ibox-content">
						@if (count($plans))
						<table class="table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Seats</th>
									<th>Credit</th>
									<th>Cost</th>
									<th>Setup</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($plans as $plan)
								<tr>
									<td>{{ $plan->id }}</td>
									<td>{{ $plan->name }}</td>
									<td>{{ $plan->num_seats }}</td>
									<td>{{ $plan->credit_per_renewal }}</td>
									<td>${{ number_format($plan->cost, 2) }}/fortnight</td>
									<td>${{ number_format($plan->setup_cost, 2) }}</td>
									<td>
										<div class="btn-group">
											<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="/admin/plans/edit/{{ $plan->id }}">View</a></li>
												<li><a href="/admin/plans/edit/{{ $plan->id }}">Edit</a></li>
											</ul>
										</div>
									</td>
								</tr>
								@endforeach
								@if ($plans->total() > 10)
								<tr>
									<td colspan="5" align="right">{{ $plan->render() }}</td>
								</tr>
								@endif
							</tbody>
						</table>
						@else
						<div class="text-center">
							<p>No plans found in the system, please <a href="/admin/plans/create">create</a> one.</p>
						</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="/js/plans.js"></script>
@endsection
