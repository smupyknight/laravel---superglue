@extends('layouts.default')

@section('content')
	<div class="row wrapper border-bottom white-bg page-heading">
		<div class="col-sm-4">
			<h2>{{ $title or '' }}</h2>
			<ol class="breadcrumb">
				<li><a href="/admin">Home</a></li>
				<li><a href="/admin/spaces">Spaces</a></li>
				<li class="active"><strong>{{ $title or '' }}</strong></li>
			</ol>
		</div>
	</div>
	<div class="wrapper wrapper-content">
		<div class="tabs-container">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab-rooms" data-toggle="tab">Rooms</a></li>
				<li><a href="#tab-desks" data-toggle="tab">Desks</a></li>
				<li><a href="#tab-offices" data-toggle="tab">Offices</a></li>
			</ul>
			<div class="tab-content">

				<div id="tab-rooms" class="tab-pane active">
					<div class="panel-body">
						<a href="/admin/rooms/create/{{$space->id}}" class="btn btn-primary btn-xs pull-right">Create Room</a>

						<table class="table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Description</th>
									<th>Capacity</th>
									<th>Credits/hour</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if (count($space->rooms))
									@foreach ($space->rooms as $room)
										<tr>
											<td>{{$room->name}}</td>
											<td>{{$room->description}}</td>
											<td>{{$room->capacity}}</td>
											<td>{{$room->credits_per_hour}}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Actions <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/admin/rooms/edit/{{$room->id}}">Edit</a></li>
														<li class="divider"></li>
														<li><a href="#" onclick="delete_room('{{$room->id}}');return false;">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								@else
									<tr>
										<td colspan="5" align="center">No Rooms Yet.</td>
									</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
				<div id="tab-desks" class="tab-pane">
					<div class="panel-body">
						<a href="#" class="btn btn-primary btn-xs btn-create-desk pull-right">Create Desk</a>

						<table class="table">
							<thead>
								<tr>
									<th>Name</th>
									<th>SignUp Fee</th>
									<th>Cost</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if (count($space->desks))
									@foreach ($space->desks as $desk)
										<tr data-id="{{$desk->id}}" data-name="{{$desk->name}}" data-cost="{{$desk->cost}}" data-signup_fee="{{$desk->signup_fee}}" >
											<td>{{$desk->name}}</td>
											<td>${{number_format($desk->signup_fee,2)}}</td>
											<td>${{number_format($desk->cost,2)}}/ fortnight</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Actions <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a class="btn-edit-desk" href="#">Edit</a></li>
														<li class="divider"></li>
														<li><a class="btn-delete-desk" href="#">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								@else
									<tr>
										<td colspan="4" class="text-center">No desk found</td>
									</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
				<div id="tab-offices" class="tab-pane">
					<div class="panel-body">
						<a href="/admin/offices/create/{{$space->id}}" class="btn btn-primary btn-xs pull-right">Create Office</a>

						<table class="table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Features</th>
									<th>Capacity</th>
									<th>SignUp Fee</th>
									<th>Cost</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if (count($space->offices))
									@foreach ($space->offices as $office)
										<tr data-id="{{$office->id}}" data-name="{{$office->name}}" data-features="{{$office->features}}" data-capacity="{{$office->capacity}}" data-cost="{{$office->cost}}">
											<td>{{$office->name}}</td>
											<td>{{$office->features}}</td>
											<td>{{$office->capacity}}</td>
											<td>${{$office->signup_fee}}</td>
											<td>${{$office->cost}}/ fortnight</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Actions <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a class="" href="/admin/offices/view/{{$office->id}}">Manage</a></li>
														<li><a class="" href="/admin/offices/edit/{{$office->id}}">Edit</a></li>
														<li class="divider"></li>
														<li><a class="btn-delete-office" href="#">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								@else
									<tr>
										<td colspan="6" class="text-center">No office found</td>
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
	var desk_modal_html = ''+
		'<form action="/admin/desks/create" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Name</label>'+
				'<div class="col-md-9"><input type="text" name="name" class="form-control"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">SignUp Fee </label>'+
				'<div class="col-md-9"><input type="text" name="signup_fee" class="form-control"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Price </label>'+
				'<div class="col-md-9"><input type="text" name="cost" class="form-control"></div>'+
			'</div>'+
			'<input type="hidden" name="space_id" value="{{$space->id}}">'+
			'{{ csrf_field() }}'+
		'</form>';
	$('.btn-create-desk').on('click', function(event) {
		event.preventDefault();
		modalform.dialog({
			bootbox: {
				title: 'Create New Desk',
				message: desk_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Create Desk',
						className: 'btn-primary'
					}
				}
			},
			after_init: function() {
				$('.modal input[name="name"]').val('');
				$('.modal input[name="cost"]').val('');
				$('.modal input[name="signup_fee"]').val('');

			}
		});
	});
	$('.btn-edit-desk').on('click', function(event) {
		event.preventDefault();
		var tr = $(this).closest('tr');
		modalform.dialog({
			bootbox: {
				title: 'Edit Desk',
				message: desk_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default',
					},
					submit: {
						label: 'Save Changes',
						className: 'btn-primary'
					}
				}
			},
			after_init: function() {
				$('.modal input[name="name"]').val(tr.data('name'));
				$('.modal input[name="cost"]').val(tr.data('cost'));
				$('.modal input[name="signup_fee"]').val(tr.data('signup_fee'));
				$('.modal form').attr('action', '/admin/desks/edit/' + tr.data('id'));
			}
		});
	});
	$('.btn-delete-desk').on('click', function(event) {
		event.preventDefault();
		var desk_id = $(this).closest('tr').data('id');
		modalform.dialog({
			bootbox : {
				title: 'Delete Desk',
				message: ''+
					'<form action="/admin/desks/delete/' + desk_id + '" method="post" class="form-horizontal">'+
						'<p>Are you sure you want to delete this desk?</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Desk',
						className: 'btn-danger'
					}
				}
			}
		});
	});
	</script>

	<script>
	$('.btn-delete-office').on('click', function(event) {
		event.preventDefault();
		var office_id = $(this).closest('tr').data('id');
		modalform.dialog({
			bootbox : {
				title: 'Delete Office',
				message: ''+
					'<form action="/admin/offices/delete/' + office_id + '" method="post" class="form-horizontal">'+
						'<p>Are you sure you want to delete this office entry?</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Office',
						className: 'btn-danger'
					}
				}
			}
		});
	});
	</script>

	<script>
		function delete_room(room_id)
		{
			if (! window.confirm('Delete this room?')) {
				return false;
			}

			$.ajax({
				url: '/admin/rooms/delete',
				method: 'post',
				data: {
					room_id: room_id,
					_token: '{{ csrf_token() }}'
				},
				success: function() {
					window.location.reload();
				}
			});
		}
	</script>
@endsection
