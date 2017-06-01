@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/admin/accounts/create" class="btn btn-primary btn-xs">Create New Account</a>
						</div>
					</div>
					<div class="ibox-content">
						<form method="GET" action="/admin/accounts">
							<div class="input-group">
								<input type="text" placeholder="Search Accounts" class="input form-control" name="search" value="{{Request::get('search')}}">
								<span class="input-group-btn">
									<button type="submit" class="btn btn btn-primary "> <i class="fa fa-search"></i> Search</button>
								</span>
							</div>
						</form>
						<table class="table table-striped">
							<thead>
								<tr>
									<th>ID</th>
									<th>Account Name</th>
									<th>Billing Email</th>
									<th>Credit Card</th>
									<th>Created At</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@if ($accounts)
								@foreach ($accounts as $account)
									<tr>
										<td>{{ $account->id }}</td>
										<td><a href="/admin/accounts/view/{{$account->id}}">{{ $account->name }}</a></td>
										<td>{{ $account->email }}</td>
										<td>
											@if ($account->stripe_id)
												<span class="label label-success">Yes</span>
											@else
												<span class="label label-info">No</span>
											@endif
										</td>
										<td>{{ $account->created_at->setTimezone(Auth::user()->timezone)->format('F jS \a\t g:ia') }}</td>
										<td>
											<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/admin/accounts/view/{{$account->id}}">Manage</a></li>
														<li><a href="/admin/accounts/edit/{{$account->id}}">Edit</a></li>
														<li class="divider"></li>
														<li><a href="#" onclick="delete_account({{ $account->id }}, '{{ $account->name }}'); return false;" class="btn-delete-user">Delete</a></li>
													</ul>
												</div>
										</td>
									</tr>
								@endforeach
								@else
									<tr>
										<td colspan="6">No accounts</td>
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
		function delete_account(account_id, name)
		{
			modalform.dialog({
				bootbox : {
					title: 'Delete Account?',
					message: ''+
						'<form action="/admin/accounts/delete/' + account_id + '" method="post">'+
							'<p>Are you sure you want to delete this account (' + name + ')?</p>'+
							'<p>If you delete an account, all billing items, invoices, users, etc will be deleted with it. It is best to ensure'+
							'that all users have been reassigned to a different account first</p>'+
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
