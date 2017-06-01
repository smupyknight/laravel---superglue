@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="ibox">
						<div class="ibox-title">
							<h5>{{ $title or '' }}</h5>
							<div class="ibox-tools">
								<a href="/admin/communities/create" class="btn btn-primary btn-xs">Create Community</a>
							</div>
						</div>
						<div class="ibox-content">
							@if($communities->count())
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Name</th>
											<th>Created At</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
									@foreach($communities as $community)
										<tr>
											<td>{{ $community->name }}</td>
											<td>{{ $community->created_at->format('F d, Y') }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/admin/communities/view/{{$community->id}}">View</a></li>
														<li><a href="/admin/communities/edit/{{$community->id}}">Edit</a></li>
														<li class="divider"></li>
														<li><a href="#" onclick="delete_community('{{$community->id}}');return false;">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
									@if ($communities->total() > 10)
										<tr>
											<td colspan="3" align="right">
												{{$communities->render()}}
											</td>
										</tr>
									@endif
									</tbody>
								</table>
							@else
								<div class="text-center">
									<p>No communities found in the system, please <a href="/admin/communities/create">create</a> one.</p>
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
		function delete_community(community_id)
		{
			if (window.confirm('Delete this community?')) {
				window.location.href = "/admin/communities/delete/"+community_id;
			}
		}
	</script>
@endsection