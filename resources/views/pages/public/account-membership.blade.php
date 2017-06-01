@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
	<div class="row m-t-lg">
		<div class="col-md-6">
			<div class="">
				<div>
					<h2 class="no-margins">{{ $membership->name }}</h2>
					<h4>ABN: {{ $membership->getFormattedAbn() }}</h4>
					<p>Email: {{ $membership->email }}</p>
				</div>
			</div>
		</div>
		<div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Current</span>
                    <h5>Credits</h5>
                </div>
                <div class="ibox-content">
					<h1 class="no-margins">{{ $membership->credit_balance }}</h1>
                </div>
            </div>
        </div>
		@if ($user = $membership->users()->whereNotNull('last_login_at')->orderBy('last_login_at','desc')->first())
			<div class="col-md-3">
				<small>Last Login</small>
				<br>
				<p class="no-margins">{{ $user->last_login_at->setTimezone(Auth::user()->timezone)->format('H:i:s j/m/Y') }}</p>
				<p>{{ $user->last_login_at->diffForHumans() }}</p>
			</div>
		@endif
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Plan Details</h5>
				</div>
				<div class="ibox-content">
					<table class="table">
						<tbody>
							<tr>
								<th class="text-right">Plan</th>
								<td>{{ $membership->plan->name }}</td>
							</tr>
							<tr>
								<th class="text-right">Seats</th>
								<td>{{ $membership->num_seats }}</td>
							</tr>
							<tr>
								<th class="text-right">Credit</th>
								<td>{{ $membership->credit_per_renewal }} per renewal</td>
							</tr>
							<tr>
								<th class="text-right">Cost</th>
								<td>${{ number_format($membership->cost, 2) }} per fortnight</td>
							</tr>
							<tr>
								<th class="text-right">Expires</th>
								<td>{{ $membership->renewal_date->format('l, j F Y') }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>
						Memberships (Users)
					</h5>
					<!-- <div class="ibox-tools">
						<a href="#" class="btn btn-primary btn-xs btn-add-user">Add</a>
						<a href="#" class="btn btn-primary btn-xs btn-invite-user">Invite</a>
					</div> -->
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($membership->users as $user)
								<tr>
									<td>{{ $user->first_name }} {{ $user->last_name }}</td>
									<td>{{ $user->email }}</td>
									<td>
										<div class="btn-group">
											<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="/users/view/{{ $user->id }}">View</a></li>
												<li><a href="/users/edit/{{ $user->id }}">Edit</a></li>
												<li class="divider"></li>
												<li><a href="#" class="btn-remove-user" data-user-id="{{ $user->id }}">Remove</a></li>
											</ul>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Invoices</h5>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<tr>
								<th>Invoice Number</th>
								<th>Created At</th>
								<th>Total</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($membership->invoices as $invoice)
								<tr>
									<td>{{ sprintf('INV-%05d', $invoice->id) }}</td>
									<td>{{ $invoice->created_at->setTimezone(Auth::user()->timezone)->format('g:ia j/m/Y') }}</td>
									<td>${{ number_format($invoice->total, 2) }}</td>
									<td>{{ ucfirst($invoice->status) }}</td>
									<td>
										<div class="btn-group">
											<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="/invoices/view/{{ $invoice->id }}">View</a></li>
												<li><a href="/invoices/edit/{{ $invoice->id }}">Edit</a></li>
												<li class="divider"></li>
												<li><a href="/invoices/delete/{{ $invoice->id }}">Delete</a></li>
											</ul>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Credit Card Details</h5>
				</div>
				<div class="ibox-content">
					@if ($membership->card_last_four)
						<p>{{ $membership->card_brand }}: xxxx xxxx xxxx {{ $membership->card_last_four }} &nbsp;<button type="button" class="btn btn-default btn-configure-card">Edit</button></p>
					@else
						<p><em class="text-muted">Not configured</em> &nbsp; <button type="button" class="btn btn-default btn-configure-card">Configure</button></p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
	<script src="/js/typeahead.js"></script>
	<script src="/js/handlebars.js"></script>
	<script src="https://js.stripe.com/v2/"></script>
	<script>
	Stripe.setPublishableKey('{{ env('STRIPE_KEY') }}');

	$('.btn-add-user').on('click', function(event) {
		event.preventDefault();

		bootbox.dialog({
			title: 'Add User to Membership',
			message: '' +
				'<div class="form-horizontal">' +
					'<div class="form-group">' +
						'<label class="col-md-4 control-label">User</label>' +
						'<div class="col-md-8"><input type="text" name="user" class="form-control"></div>' +
					'</div>' +
					'<input type="hidden" name="user_id">' +
				'</div>',
			buttons: {
				submit: {
					label: 'Add User',
					className: 'btn-primary',
					callback: function() {
						$('.modal-footer .text-danger').remove();
						$('.modal-footer button').attr('disabled','disabled');

						$.ajax({
							url: '/admin/memberships/associate-user/{{ $membership->id }}',
							method: 'post',
							data: {
								user_id: $('input[name="user_id"]').val(),
								_token: '{{ csrf_token() }}'
							},
							success: function() {
								bootbox.hideAll();
								document.location.reload();
							},
							error: function(jqxhr, status, error) {
								if (jqxhr.status == 422) {
									var field = Object.keys(jqxhr.responseJSON)[0];
									error = jqxhr.responseJSON[field][0];
								}

								$('.modal-footer').prepend($('<span class="text-danger pull-left"></span>').html(error));
								$('.modal-footer button').removeAttr('disabled');
							}
						});

						return false;
					}
				},
				cancel: {
					label: 'Cancel',
					className: 'btn-default'
				}
			}
		});

		var users = new Bloodhound({
			datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: '/admin/users/typeahead/%QUERY',
				wildcard: '%QUERY'
			}
		});

		$('input[name="user"]').typeahead(null, {
			name: 'users',
			display: function(suggestion) { return suggestion.first_name + ' ' + suggestion.last_name },
			source: users,
			limit: 9999,
			templates: {
				empty: '<div class="empty-message">(no users found)</div>',
				suggestion: Handlebars.compile('<div><strong>@{{first_name}} @{{last_name}}</strong> - @{{email}}@{{#if membership_id}} (already in a membership)@{{/if}}</div>')
			}
		}).on('typeahead:select', function(event, suggestion) {
			$('input[name="user_id"]').val(suggestion.id);
		});
	});

	$('.btn-invite-user').on('click', function(event) {
		event.preventDefault();

		bootbox.dialog({
			title: 'Invite User',
			message: '' +
				'<div class="form-horizontal">' +
					'<div class="form-group">' +
						'<label class="col-md-4 control-label">First Name</label>' +
						'<div class="col-md-8"><input type="text" name="first_name" class="form-control"></div>' +
					'</div>' +
					'<div class="form-group">' +
						'<label class="col-md-4 control-label">Last Name</label>' +
						'<div class="col-md-8"><input type="text" name="last_name" class="form-control"></div>' +
					'</div>' +
					'<div class="form-group">' +
						'<label class="col-md-4 control-label">Email</label>' +
						'<div class="col-md-8"><input type="text" name="email" class="form-control"></div>' +
					'</div>' +
				'</div>',
			buttons: {
				submit: {
					label: 'Invite User',
					className: 'btn-primary',
					callback: function() {
						$('.modal-footer .text-danger').remove();
						$('.modal-footer button').attr('disabled','disabled');

						$.ajax({
							url: '/admin/users/invite',
							method: 'post',
							data: {
								first_name: $('input[name="first_name"]').val(),
								last_name: $('input[name="last_name"]').val(),
								email: $('input[name="email"]').val(),
								membership: {{ $membership->id }},
								_token: '{{ csrf_token() }}'
							},
							success: function() {
								bootbox.hideAll();
								document.location.reload();
							},
							error: function(jqxhr, status, error) {
								if (jqxhr.status == 422) {
									var field = Object.keys(jqxhr.responseJSON)[0];
									error = jqxhr.responseJSON[field][0];
								}

								$('.modal-footer').prepend($('<span class="text-danger pull-left"></span>').html(error));
								$('.modal-footer button').removeAttr('disabled');
							}
						});

						return false;
					}
				},
				cancel: {
					label: 'Cancel',
					className: 'btn-default'
				}
			}
		});
	});

	$('.btn-configure-card').on('click', function(event) {
		event.preventDefault();

		bootbox.dialog({
			title: 'Configure Credit Card',
			message: '' +
				'<div class="form-horizontal">' +
					'<div class="form-group">' +
						'<label class="col-md-4 control-label">Card Number</label>' +
						'<div class="col-md-8"><input type="text" name="card_number" class="form-control"></div>' +
					'</div>' +
					'<div class="form-group">' +
						'<label class="col-md-4 control-label">Expiry</label>' +
						'<div class="col-md-4">' +
							'<select name="card_expiry_month" class="form-control">' +
								@for ($i = 1; $i <= 12; $i++)
									'<option value="{{ $i }}">{{ sprintf('%02d', $i) }} ({{ (new DateTime("2000-$i-15"))->format('F') }})</option>' +
								@endfor
							'</select>' +
						'</div>' +
						'<div class="col-md-4">' +
							'<select name="card_expiry_year" class="form-control">' +
								@for ($i = date('Y'); $i <= date('Y') + 15; $i++)
									'<option value="{{ $i }}">{{ $i }}</option>' +
								@endfor
							'</select>' +
						'</div>' +
					'</div>' +
					'<div class="form-group">' +
						'<label class="col-md-4 control-label">CVC</label>' +
						'<div class="col-md-8"><input type="text" name="card_cvc" class="form-control"></div>' +
					'</div>' +
				'</div>',
			buttons: {
				submit: {
					label: 'Save Changes',
					className: 'btn-primary',
					callback: function() {
						$('.modal-footer .text-danger').remove();
						$('.modal-footer button').attr('disabled','disabled');

						// Send the CC details to Stripe to get a card token back
						Stripe.card.createToken({
							number: $('input[name="card_number"]').val(),
							exp_month: $('select[name="card_expiry_month"]').val(),
							exp_year: $('select[name="card_expiry_year"]').val(),
							cvc: $('input[name="card_cvc"]').val()
						}, function(status, response) {
							if (response.error) {
								$('.modal-footer').prepend($('<span class="text-danger pull-left"></span>').html(response.error.message));
								$('.modal-footer button').removeAttr('disabled');
								return;
							}

							// Submit AJAX request to our server
							$.ajax({
								url: '/admin/memberships/card/{{ $membership->id }}',
								method: 'post',
								data: {
									card_token: response.id,
									_token: '{{ csrf_token() }}'
								},
								success: function() {
									bootbox.hideAll();
									document.location.reload();
								},
								error: function(jqxhr, status, error) {
									if (jqxhr.status == 422) {
										var field = Object.keys(jqxhr.responseJSON)[0];
										error = jqxhr.responseJSON[field][0];
									}

									$('.modal-footer').prepend($('<span class="text-danger pull-left"></span>').html(error));
									$('.modal-footer button').removeAttr('disabled');
								}
							});
						});

						return false;
					}
				},
				cancel: {
					label: 'Cancel',
					className: 'btn-default'
				}
			}
		});
	});

	$('.btn-remove-user').on('click', function(event) {
		event.preventDefault();

		var user_id = $(this).data('user-id');

		bootbox.confirm({
			title: 'Remove User',
			message: 'Are you sure you want to remove this user from this membership?',
			callback: function(result) {
				if (!result) {
					return;
				}

				$('.modal-footer .text-danger').remove();
				$('.modal-footer button').attr('disabled','disabled');

				$.ajax({
					url: '/admin/memberships/remove-user/{{ $membership->id }}',
					method: 'post',
					data: {
						user_id: user_id,
						_token: '{{ csrf_token() }}'
					},
					success: function() {
						bootbox.hideAll();
						document.location.reload();
					},
					error: function(jqxhr, status, error) {
						if (jqxhr.status == 422) {
							var field = Object.keys(jqxhr.responseJSON)[0];
							error = jqxhr.responseJSON[field][0];
						}

						$('.modal-footer').prepend($('<span class="text-danger pull-left"></span>').html(error));
						$('.modal-footer button').removeAttr('disabled');
					}
				});

				return false;
			}
		});
	});
	</script>
@endsection
