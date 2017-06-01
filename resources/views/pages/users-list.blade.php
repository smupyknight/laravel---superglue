@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/admin/users/create" class="btn btn-primary btn-xs">Create User</a>
							<a href="/admin/users/invite" class="btn btn-primary btn-xs">Invite</a>
						</div>
					</div>
					<div class="ibox-content">
						<div class="row">
							<form method="GET" action="/admin/users">
								<div class="col-sm-11">
									<div class="input-group">
										<input type="text" placeholder="Search Users" class="input form-control" name="search" value="{{ Request::get('search') }}">
										<span class="input-group-btn">
											<button type="submit" class="btn btn btn-primary "> <i class="fa fa-search"></i> Search</button>
										</span>
									</div>
								</div>
								<select name="type" id="type" style="height:30px" onchange="this.form.submit()">
									<option {{ Request::get('type') == 'All' ? 'selected' : '' }} >All</option>
									<option {{ Request::get('type') == 'Admin' ? 'selected' : '' }} >Admin</option>
									<option {{ Request::get('type') == 'Mentor' ? 'selected' : '' }} >Mentor</option>
									<option {{ Request::get('type') == 'Member' ? 'selected' : '' }} >Member</option>
								</select>
							</form>
						</div>
						<br>
						<ul class="nav nav-tabs"> 
	                        <li class="{{ Request::get('showDeleted') == null ? 'active' : '' }}"><a href="/admin/users" aria-expanded="true"><i class="fa fa-user"></i> Users</a></li>
	                        <li class="{{ Request::get('showDeleted') == 1 ? 'active' : '' }}"><a href="/admin/users?showDeleted=1" aria-expanded="false"><i class="fa fa-user-times"></i> Deleted Users</a></li>
	                    </ul>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Type</th>
									<th>Created At</th>
									<th>Last Login</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody class="table_body">
								@if(count($users)!==0)
									@foreach($users as $user)
										<tr>
											<td><a href="/admin/users/view/{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</a></td>
											<td>{{ $user->email }}</td>
											<td>{{ ucfirst($user->type) }}</td>
											<td>{{ $user->created_at->format('d M Y') }}</td>
											<td>{{ $user->last_login_at }}</td>
											<td>{{ $user->password ? 'Active' : 'Pending' }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
													@if (Request::get('showDeleted') == 1) 
														<li><a href="/admin/users/restore/{{ $user->id }}">Restore User</a></li>
													@else
														<li><a href="/admin/users/view/{{ $user->id }}">View</a></li>
														<li><a href="/admin/users/edit/{{ $user->id }}">Edit</a></li>
														<li><a href="/admin/accounts/view/{{ $user->account_id }}">Go to Account</a></li>
														@if (!$user->password)

															<li>
																@if ($user->invitations()->count())
																	<a href="#" onclick="resend_invite('{{ $user->id }}',this);return false;">Resend Invite <br><small>{{ 'Last Sent: ' . $user->invitations->first()->updated_at->setTimezone(Auth::user()->timezone)->format('F d, Y') }}</small></a>
																@else
																	<a href="#" onclick="resend_invite('{{ $user->id }}',this);return false;">Send Invite <br></a>
																@endif
															</li>
														@endif
														@if ($user->type == 'Mentor')
														<li><a href="/admin/mentors-schedule/mentor/{{ $user->id }}">Schedule Mentor</a></li>
														@endif
														<li class="divider"></li>
														<li><a href="#" onclick="delete_user({{ $user->id }}, '{{ $user->email }}'); return false;" class="btn-delete-user">Delete</a></li>
													@endif
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
								@else
								    <tr>
								    	<td colspan="7" class="text-center">No user found</td>
								    </tr>
								@endif
								@if ($users->total() > 25)
									<tr>
										<td colspan="7" align="right">
											{{ $users->render() }}
										</td>
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

		function resend_invite(user_id,selector)
		{
			if ($(selector).hasClass('disabled')) {
				return false;
			}
			$(selector).addClass('disabled');
			$(selector).text('Sending invite...');
			$.ajax({
				url: '/admin/users/resend-invite',
				type: "post",
				data:
				{
					'_token': '{{ csrf_token() }}',
					'user_id' : user_id,
				},
				success: function(){
					$(selector).removeClass('disabled');
					$(selector).text('Invite Sent');
				}
			});
		}

		function delete_user(user_id, email)
		{
			modalform.dialog({
				bootbox : {
					title: 'Delete User?',
					message: ''+
						'<form action="/admin/users/delete/' + user_id + '" method="post">'+
							'<p>Are you sure you want to delete this user (' + email + ')?</p>'+
							'{{ csrf_field() }}'+
						'</form>',
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Delete User',
							className: 'btn-danger'
						}
					}
				}
			});
		}

	</script>
@endsection