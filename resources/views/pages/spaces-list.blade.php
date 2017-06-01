@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="ibox">
						<div class="ibox-title">
							<h5>{{ $title or '' }}</h5>
							<div class="ibox-tools">
								<a href="/admin/spaces/create" class="btn btn-primary btn-xs">Create Space</a>
							</div>
						</div>
						<div class="ibox-content">
							@if ($spaces->count())
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Name</th>
											<th>Address</th>
											<th>Postcode</th>
											<th>Suburb</th>
											<th>State</th>
											<th>Country</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>

									@foreach($spaces as $space)
										<tr>
											<td>{{ $space->name }}</td>
											<td>{{ $space->address }}</td>
											<td>{{ $space->suburb }}</td>
											<td>{{ $space->postcode }}</td>
											<td>{{ $space->state }}</td>
											<td>{{ $space->country }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/admin/spaces/view/{{$space->id}}">Manage</a></li>
														<li><a href="/admin/spaces/edit/{{$space->id}}">Edit</a></li>
														<li class="divider"></li>
														<li><a href="#" onclick="delete_space('{{$space->id}}');return false;">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
										@if ($spaces->total() > 10)
										<tr>
											<td colspan="7" align="right">{{$spaces->render()}}</td>
										</tr>
										@endif
									</tbody>
								</table>
							@else
								<div class="text-center">
									<p>No spaces found in the system, please <a href="/admin/spaces/create">create</a> one.</p>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection

@section('scripts')
	<script>
		function delete_space(space_id)
		{
			if (window.confirm('Delete this space?')) {
				window.location.href = "/admin/spaces/delete/"+space_id;
			}
		}
	</script>
@endsection