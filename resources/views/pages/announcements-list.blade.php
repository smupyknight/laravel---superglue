@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/admin/announcements/create" class="btn btn-primary btn-xs">Create Announcement</a>
						</div>
					</div>
					<div class="ibox-content">
						@if ($announcements->count())
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Title</th>
										<th>Content</th>
										<th>Link</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($announcements as $announcement)
										<tr>
											<td>{{ $announcement->title }}</td>
											<td>{{ str_limit(preg_replace('/<[^>]+>/', ' ', $announcement->content), 200) }}</td>
											<td>{{ $announcement->link }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/admin/announcements/view/{{ $announcement->id }}">View</a></li>
														<li><a href="/admin/announcements/edit/{{ $announcement->id }}">Edit</a></li>
														<li class="divider"></li>
														<li><a onclick="delete_announcement({{ $announcement->id }}); return false;" href="#">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						@else
							<div class="text-center">
								<p>No announcements found in the system, please <a href="/admin/announcements/create">create</a> one.</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="/js/modalform.js"></script>
	<script>
		function delete_announcement(announcement_id)
		{
			modalform.dialog({
				bootbox : {
					title: 'Delete Announcement?',
					message: ''+
						'<form action="/admin/announcements/delete/' + announcement_id + '" method="post">'+
							'<p>Are you sure you want to delete this Announcement?</p>'+
							'{{ csrf_field() }}'+
						'</form>',
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Delete Announcement',
							className: 'btn-danger'
						}
					}
				}
			});
		}

	</script>
@endsection