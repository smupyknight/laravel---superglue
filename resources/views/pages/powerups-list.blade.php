@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/admin/powerups/create" class="btn btn-primary btn-xs">Create New Powerup</a>
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Title</th>
									<th>Description</th>
									<th>Link</th>
									<th>Image</th>
								</tr>
							</thead>
							<tbody>
								@if(count($powerups)!==0)
								@foreach ($powerups as $powerup)
									<tr>
										<td>{{ $powerup->title }}</td>
										<td>{!! $powerup->description !!}</td>
										<td>{{ $powerup->coupon_code ? $powerup->coupon_code : 'N/A' }}</td>
										<td>{{ $powerup->link }}</td>
										<td>
											@if ($powerup->image)
											<img src="{{ asset('storage/powerups/'.$powerup->image) }}" class="img-responsive" style="max-width:50px">
											@endif
										</td>
										<td>
											<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/admin/powerups/view/{{ $powerup->id }}">View</a></li>
														<li><a href="/admin/powerups/edit/{{ $powerup->id }}">Edit</a></li>
														<li class="divider"></li>
														<li><a onclick="delete_powerup({{ $powerup->id }}); return false;" href="#">Delete</a></li>
													</ul>
												</div>
										</td>
									</tr>
								@endforeach
								@else
									<tr>
										<td colspan="6">No powerups</td>
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
	<script src="/js/modalform.js"></script>
	<script>

		function delete_powerup(powerup_id)
		{
			modalform.dialog({
				bootbox : {
					title: 'Delete PowerUp?',
					message: ''+
						'<form action="/admin/powerups/delete/' + powerup_id + '" method="post">'+
							'<p>Are you sure you want to delete this PowerUp?</p>'+
							'{{ csrf_field() }}'+
						'</form>',
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Delete PowerUp',
							className: 'btn-danger'
						}
					}
				}
			});
		}

	</script>
@endsection