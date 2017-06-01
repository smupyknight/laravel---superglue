@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
	<div class="row m-t-lg">
		<div class="col-md-6">
			<div class="">
				<div>
					<h2 class="no-margins">{{ $account->name }}</h2>
					<h4>ABN: {{ $account->getFormattedAbn() }}</h4>
					<p>Email: {{ $account->email }}</p>
					<button class="btn btn-info" onclick="add_credit('{{$account->id}}');return false;">Add Credit</button>
					<button class="btn btn-danger" onclick="terminate_account('{{$account->id}}');return false;">Terminate Account</button>
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
					<h1 class="no-margins">{{ $account->credit_balance }}</h1>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			@if ($user = $account->users()->whereNotNull('last_login_at')->orderBy('last_login_at','desc')->first())
				<small>Last Login</small>
				<br>
				<p class="no-margins">{{ $user->last_login_at->setTimezone(Auth::user()->timezone)->format('H:i:s j/m/Y') }}</p>
				<p>{{ $user->last_login_at->diffForHumans() }}</p>
			@endif
			@if ($account->xero_contact_id)
				<small>Xero Contact</small><br>
				<p>{{ $account->xero_contact_name }}</p>
			@endif
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Billing Items</h5>
					<div class="ibox-tools">
						<a href="#" class="btn btn-primary btn-xs btn-add-billingitem">Add</a>
					</div>
				</div>
				<div class="ibox-content">
					@if (count($account->billingItems))
						<table class="table">
							<thead>
								<tr>
									<th>Name</th>
									<th>Cost</th>
									<th>Start Date</th>
									<th>Next Billing Date</th>
									<th>End Date</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($account->billingItems as $item)
									<tr
										data-id="{{ $item->id }}"
										data-space-id="{{ $item->space_id }}"
										data-plan-id="{{ $item->plan_id }}"
										data-office-id="{{ $item->office_id }}"
										data-desk-id="{{ $item->desk_id }}"
										data-name="{{ $item->name }}"
										data-cost="{{ $item->cost }}"
										data-credits="{{ $item->num_credits }}"
										data-start="{{ $item->start_date->format('j/m/Y') }}"
										data-end="{{ $item->end_date ? $item->end_date->format('j/m/Y') : '' }}">
										<td>{{ $item->name }}</td>
										<td>${{ number_format($item->cost, 2) }}</td>
										<td>{{ $item->start_date->format('j M Y') }}</td>
										<td>{{ $item->next_billing_date->format('j M Y') }}</td>
										<td>
											@if (!$item->end_date)
												<span class="label label-success">Indefinite</span>
											@elseif ($item->start_date->eq($item->end_date))
												<span class="label label-info">One-off</span>
											@else
												<span class="label label-danger">{{ $item->end_date->format('j M Y') }}</span>
											@endif
										</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="#" class="btn-edit-billingitem">Edit</a></li>
													<li><a href="javascript:delete_billingitem({{ $item->id }})">Delete</a></li>
												</ul>
											</div>
										</td>
								@endforeach
							</tbody>
						</table>
					@else
						<p>This account does not have any billing items yet.</p>
					@endif
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Users</h5>
					<div class="ibox-tools">
						<button class="btn btn-primary btn-xs btn-invite-user">Invite</button>
					</div>
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
							@foreach ($account->users as $user)
								<tr>
									<td>{{ $user->first_name }} {{ $user->last_name }} {!! !$user->password ? "<i>(pending)</i>" : '' !!}</td>
									<td>{{ $user->email }}</td>
									<td>
										<div class="btn-group">
											<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="/admin/users/view/{{ $user->id }}">View</a></li>
												<li><a href="/admin/users/edit/{{ $user->id }}">Edit</a></li>
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
								<th>Total</th>
								<th>Amount Paid</th>
								<th>Status</th>
								<th>Due Date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($invoices as $invoice)
								<tr>
									<td>{{ sprintf('INV-%05d', $invoice->id) }}</td>
									<td>${{ number_format($invoice->total, 2) }}</td>
									<td>${{ number_format($invoice->amount_paid, 2) }}</td>
									<td>{{ ucfirst($invoice->status) }}</td>
									<td>{{ Carbon\Carbon::createFromFormat('Y-m-d',$invoice->due_date)->format('j/m/Y') }}</td>
									<td>
										<div class="btn-group">
											<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
											<ul class="dropdown-menu">
												<li><a href="/invoices/view/{{ $invoice->id }}">View</a></li>
												<li><a href="#" onclick="add_payment('{{$invoice->id}}','{{ sprintf('INV-%05d', $invoice->id) }}');return false;">Add Payment</a></li>
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
					@if ($account->card_last_four)
						<p>{{ $account->card_brand }}: xxxx xxxx xxxx {{ $account->card_last_four }} &nbsp;<button type="button" class="btn btn-default btn-configure-card">Edit</button></p>
					@else
						<p><em class="text-muted">Not configured</em> &nbsp; <button type="button" class="btn btn-default btn-configure-card">Configure</button></p>
					@endif
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Payments</h5>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<tr>
								<th>Account ID</th>
								<th>Invoice ID</th>
								<th>Stripe ID</th>
								<th>Amount</th>
								<th>Method</th>
								<th>Payment Date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($account->invoices as $invoice)
								@if (count($invoice->payments))
									@foreach ($invoice->payments as $payment)
									<tr>
										<td>{{ $payment->account_id }}</td>
										<td>{{ sprintf('INV-%05d', $payment->invoice_id) }}</td>
										<td>{{ $payment->stripe_transaction_id }}</td>
										<td>${{ number_format($payment->amount,2) }}</td>
										<td>{{ ucwords($payment->method) }}</td>
										<td>{{ $payment->payment_date->format('d/m/Y') }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="#" onclick="edit_payment({{$payment}},'{{sprintf('INV-%05d', $payment->invoice_id)}}','{{ $payment->payment_date->format('d/m/Y') }}');return false;">Edit</a></li>
													<li class="divider"></li>
													<li><a href="#" onclick="delete_payment('{{$payment->id}}');return false;">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
									@endforeach
								@endif
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Holiday Periods</h5>
					<div class="ibox-tools">
						<a href="#" onclick="add_holiday('{{$account->id}}');return false;" class="btn btn-primary btn-xs">Add</a>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<tr>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@if (count($account->holidays))
								@foreach ($account->holidays()->orderBy('id','desc')->get() as $holiday)
									<tr>
										<td>{{Carbon\Carbon::createFromFormat('Y-m-d',$holiday->start_date)->format('F d, Y')}}</td>
										<td>{{Carbon\Carbon::createFromFormat('Y-m-d',$holiday->end_date)->format('F d, Y')}}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="#" onclick="edit_holiday('{{ $holiday->id }}','{{Carbon\Carbon::createFromFormat('Y-m-d',$holiday->start_date)->format('d/m/Y')}}','{{Carbon\Carbon::createFromFormat('Y-m-d',$holiday->end_date)->format('d/m/Y')}}');return false;">Edit</a></li>
													<li class="divider"></li>
													<li><a href="#" onclick="delete_holiday('{{ $holiday->id }}');return false;">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="3">No Holiday Periods Added.</td>
								</tr>
							@endif
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
					<h5>Files</h5>
					<div class="ibox-tools">
						<a href="#" onclick="add_files();return false;" class="btn btn-primary btn-xs">Add</a>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Size</th>
								<th>Date</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							@if (count($account->files))
								@foreach ($account->files()->orderBy('id','desc')->get() as $file)
									<tr>
										<td><a href="/admin/accounts/file/{{ $account->id }}/{{ $file->id }}">{{ $file->name }}</a></td>
										<td>{{ $file->sizeForHumans() }}</td>
										<td>{{ $file->created_at->setTimezone(Auth::user()->timezone)->format('j M Y, g:ia') }}</td>
										<td>
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
												<ul class="dropdown-menu">
													<li><a href="#" onclick="delete_file({{ $file->id }});return false;">Delete</a></li>
												</ul>
											</div>
										</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="4">No files added.</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>User Timeline</h5>
				</div>
				<div class="ibox-content">
						@foreach ($account->notes as $note)
							<div class="user-timeline-div">
								<p style="margin-bottom: 5px;"><strong>{{ $note->user->first_name }} {{ $note->user->last_name }} added note</strong>
								</p>
								<div class="btn-group timeline-action">
									<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle" aria-expanded="false" style="margin: 0;">Action <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="#" data-id="{{$note->id}}" data-content="{{ $note->content }}" onclick="edit_note(this);return false;">Edit</a></li>
										<li class="divider"></li>
										<li><a href="#" onclick="delete_note('{{ $note->id }}');return false;">Delete</a></li>
									</ul>
								</div>

								<p class="" style="clear: both;">
									<small>{{$note->created_at->setTimezone(Auth::user()->timezone)->format('h:m:a d-m-Y')}}</small>
									<span class="pull-right">{{$note->created_at->diffForHumans()}}</span>
								</p>
								<pre>{{$note->content}}</pre>
							</div>
						@endforeach
				</div>

				<div class="ibox-content">
					<div class="row">
						<div class="col-md-12">
							<button type="button" class="btn btn-primary btn-xs pull-right btn-add-note"><i class="fa fa-plus-circle"></i> <span>Add Note</span></button>
						</div>
					</div>

					<div class="row note-box" style="display:none;">
						<form class="form-horizontal" id="add-note-form" >
							{{ csrf_field() }}
							<div class="col-md-12">
								<div class="form-group">
									<textarea class="form-control" rows="2" name="note" id="note"></textarea>
									<input type="hidden" name="account_id" id="account_id" value="{{ $account->id }}">
								</div>
							</div>
							<div class="col-md-12">
								<button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-floppy-o"></i> Save Note</button>
							</div>
						</form>
				 	</div>
				</div>
			</div>
		</div>
	</div>


</div>
@endsection

@section('css')
	<link href="/css/dropzone.css" rel="stylesheet">
@endsection

@section('scripts')
	<script src="/js/typeahead.js"></script>
	<script src="/js/handlebars.js"></script>
	<script src="https://js.stripe.com/v2/"></script>
	<script src="/js/modalform.js"></script>
	<script src="/js/dropzone.js"></script>
	<script>
	Stripe.setPublishableKey('{{ env('STRIPE_KEY') }}');

	var billingitem_html = ''+
		'<form action="/admin/billing-items/create" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-4 control-label">Type</label>'+
				'<div class="col-md-8">'+
					'<select name="type" class="form-control">'+
						'<option value="">Please select</option>'+
						'<optgroup label="General">'+
							@foreach ($plans as $plan)
								'<option value="plan:{{ $plan->id }}" data-setup-cost="{{ $plan->setup_cost }}" data-cost="{{ $plan->cost }}" data-credits="{{ $plan->credit_per_renewal }}">Membership: {{ addslashes($plan->name) }}</option>'+
							@endforeach
						'</optgroup>'+
						@foreach ($spaces as $space)
							'<optgroup label="{{ addslashes($space->name) }}{{ $space->id == $account->space_id ? ' (primary)' : '' }}">'+
								@foreach ($offices as $office)
									@if ($office->space_id == $space->id)
										'<option value="office:{{ $office->id }}">Office: {{ addslashes($office->name) }}</option>'+
									@endif
								@endforeach
								@foreach ($desks as $desk)
									@if ($desk->space_id == $space->id)
										'<option value="desk:{{ $desk->id }}">Desk: {{ addslashes($desk->name) }}</option>'+
									@endif
								@endforeach
							'</optgroup>'+
						@endforeach
						'<option value="other">Other</option>'+
					'</select>'+
				'</div>'+
			'</div>'+
			'<div class="form-group" id="space">'+
				'<label class="col-md-4 control-label">Space</label>'+
				'<div class="col-md-8">'+
					'<select name="space_id" class="form-control">'+
						'<option value="">Please select</option>'+
						@foreach ($spaces as $space)
							'<option value="{{ $space->id }}">{{ addslashes($space->name) }}</option>'+
						@endforeach
					'</select>'+
				'</div>'+
			'</div>'+
			'<div class="form-group" id="name">'+
				'<label class="col-md-4 control-label">Name</label>'+
				'<div class="col-md-8">'+
					'<input type="text" name="name" class="form-control">'+
				'</div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-4 control-label">Cost</label>'+
				'<div class="col-md-8">'+
					'<div class="input-group">'+
						'<span class="input-group-addon">$</span>'+
						'<input type="text" name="cost" class="form-control">'+
					'</div>'+
				'</div>'+
			'</div>'+
			'<div class="form-group signup-fee-div">'+
				'<label class="col-md-4 control-label">Signup Fee</label>'+
				'<div class="col-md-8">'+
					'<div class="input-group">'+
						'<span class="input-group-addon">$</span>'+
						'<input type="text" name="signup_fee" class="form-control">'+
					'</div>'+
				'</div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-4 control-label">Additional Credit</label>'+
				'<div class="col-md-8">'+
					'<input type="text" name="num_credits" class="form-control">'+
				'</div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-4 control-label">Start Date</label>'+
				'<div class="col-md-8">'+
					'<input type="text" name="start_date" class="form-control">'+
				'</div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-4 control-label">Recurrence</label>'+
				'<div class="col-md-8">'+
					'<label class="radio-inline"><input type="radio" name="recurrence" value="none">One off</label>'+
					'<label class="radio-inline"><input type="radio" name="recurrence" value="indefinite" checked>Recur indefinitely</label>'+
					'<label class="radio-inline"><input type="radio" name="recurrence" value="limited">Recur until...</label>'+
				'</div>'+
			'</div>'+
			'<div class="form-group" id="end-date">'+
				'<label class="col-md-4 control-label">End Date</label>'+
				'<div class="col-md-8">'+
					'<input type="text" name="end_date" class="form-control">'+
				'</div>'+
			'</div>'+
			'<input type="hidden" name="account_id" value="{{ $account->id }}">'+
			'{{ csrf_field() }}'+
		'</form>';

	$('.btn-add-billingitem').on('click', function(event) {
		event.preventDefault();

		modalform.dialog({
			bootbox: {
				title: 'Add Billing Item',
				message: billingitem_html,
				buttons: {
					submit: {
						label: 'Add Billing Item',
						className: 'btn-primary'
					},
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					}
				}
			},
			after_init: function() {
				$('#space').hide();
				$('#name').hide();
				$('#end-date').hide();

				$('select[name="type"]').on('change', function() {
					$('#space').toggle($(this).val().indexOf('plan:') == 0);
					$('#name').toggle($(this).val() == 'other');

					if ($(this).val().indexOf('plan:') == 0) {
						var option = $(this).find('option:selected');
						$('input[name="cost"]').val(option.data('cost'));
						$('input[name="signup_fee"]').val(option.data('setup-cost'));
						$('input[name="num_credits"]').val(option.data('credits'));
					}
				});

				$('input[name="recurrence"]').on('change', function() {
					$('#end-date').toggle($(this).val() == 'limited');
				});

				$('input[name$="_date"]').datetimepicker({
					format: 'DD/MM/YYYY',
					minDate: moment()
				});
			}
		});
	});

	$('.btn-edit-billingitem').on('click', function(event) {
		event.preventDefault();

		var tr = $(this).closest('tr');

		modalform.dialog({
			bootbox: {
				title: 'Edit Billing Item',
				message: billingitem_html,
				buttons: {
					submit: {
						label: 'Save Changes',
						className: 'btn-primary'
					},
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					}
				}
			},
			after_init: function() {
				if (tr.data('plan-id')) {
					var type = 'plan:' + tr.data('plan-id');
				} else if (tr.data('office-id')) {
					var type = 'office:' + tr.data('office-id');
				} else if (tr.data('desk-id')) {
					var type = 'desk:' + tr.data('desk-id');
				} else {
					var type = 'other';
				}

				if (!tr.data('end')) {
					var recurrence = 'indefinite';
				} else if (tr.data('end') == tr.data('start')) {
					var recurrence = 'none';
				} else {
					var recurrence = 'limited';
				}

				$('.modal form').attr('action', '/admin/billing-items/edit/' + tr.data('id'));
				$('.modal [name="type"]').val(type);
				$('.modal [name="space_id"]').val(tr.data('space-id'));
				$('.modal [name="name"]').val(tr.data('name'));
				$('.modal [name="cost"]').val(tr.data('cost'));
				$('.modal [name="num_credits"]').val(tr.data('credits'));
				$('.modal [name="start_date"]').val(tr.data('start'));
				$('.modal [name="recurrence"][value="' + recurrence + '"]').prop('checked', 'checked');
				$('.modal [name="end_date"]').val(tr.data('end'));
				$('.modal .signup-fee-div').hide();

				$('select[name="type"]').on('change', function() {
					$('#name').toggle($(this).val() == 'other');
					$('#space').toggle($(this).val().indexOf('plan:') == 0);

					if ($(this).val().indexOf('plan:') == 0) {
						var option = $(this).find('option:selected');
						$('input[name="cost"]').val(option.data('cost'));
						$('input[name="num_credits"]').val(option.data('credits'));
					}
				});

				$('#space').toggle(tr.data('plan-id') > 0);
				$('#name').toggle($('select[name="type"]').val() == 'other');

				$('input[name="recurrence"]').on('change', function() {
					$('#end-date').toggle($('input[name="recurrence"]:checked').val() == 'limited');
				}).trigger('change');

				$('input[name$="_date"]').datetimepicker({
					format: 'DD/MM/YYYY',
					minDate: moment(),
					keepInvalid: true
				});
			}
		});
	});

	function delete_billingitem(item_id)
	{
		modalform.dialog({
			bootbox : {
				title: 'Delete Billing Item',
				message: ''+
					'<form action="/admin/billing-items/delete/' + item_id + '" method="post">'+
						'<p>Are you sure you want to delete this billing item?</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Billing Item',
						className: 'btn-danger'
					}
				}
			}
		});
	}

	$('.btn-create-user').on('click', function() {
		var html = ''+
			'<form action="/admin/users/create" method="post" class="form-horizontal">'+
				'<div class="form-group">'+
					'<label class="col-md-4 control-label">First Name</label>'+
					'<div class="col-md-8"><input type="text" name="first_name" class="form-control"></div>'+
				'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-4 control-label">Last Name</label>'+
					'<div class="col-md-8"><input type="text" name="last_name" class="form-control"></div>'+
				'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-4 control-label">Email</label>'+
					'<div class="col-md-8"><input type="text" name="email" class="form-control"></div>'+
				'</div>'+
				'<input type="hidden" name="account_id" value="{{ $account->id }}">'+
				'{{ csrf_field() }}'+
			'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Create User',
				message: html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Create User',
						className: 'btn-primary'
					}
				}
			}
		});
	});

	$('.btn-invite-user').on('click', function() {
		var html = ''+
			'<form action="/admin/users/invite" method="post" class="form-horizontal">'+
				'<div class="form-group">'+
					'<label class="col-md-4 control-label">First Name</label>'+
					'<div class="col-md-8"><input type="text" name="first_name" class="form-control"></div>'+
				'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-4 control-label">Last Name</label>'+
					'<div class="col-md-8"><input type="text" name="last_name" class="form-control"></div>'+
				'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-4 control-label">Email</label>'+
					'<div class="col-md-8"><input type="text" name="email" class="form-control"></div>'+
				'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-4 control-label">Is Admin</label>'+
					'<div class="col-md-1"><input type="checkbox" name="is_account_admin" class="form-control" value="1"></div>'+
				'</div>'+
				'<input type="hidden" name="account_id" value="{{ $account->id }}">'+
				'{{ csrf_field() }}'+
			'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Invite User',
				message: html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Invite User',
						className: 'btn-primary'
					}
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
								url: '/admin/accounts/card/{{ $account->id }}',
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
			message: 'Are you sure you want to remove this user from this account?',
			callback: function(result) {
				if (!result) {
					return;
				}

				$('.modal-footer .text-danger').remove();
				$('.modal-footer button').attr('disabled','disabled');

				$.ajax({
					url: '/admin/accounts/remove-user/{{ $account->id }}',
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

	function edit_payment(payment_details,invoice_number,payment_date)
	{
		var add_payment_modal_html = ''+
		'<form action="/admin/payments/edit/'+payment_details.id+'" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Invoice ID</label>'+
				'<div class="col-md-9">'+
					'<div class="control-label" style="text-align:left">'+invoice_number+'</div>'+
				'</div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Amount</label>'+
				'<div class="col-md-9"><input type="text" name="amount" class="form-control" placeholder="e.g. 5.00" value="'+payment_details.amount+'"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Payment Type</label>'+
				'<div class="col-md-9">'+
					'<select name="method" class="form-control">'+
						'<option>credit card</option>'+
						'<option>bank deposit</option>'+
						'<option>cash</option>'+
						'<option>cheque</option>'+
					'</select>'+
				'</div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Payment Date</label>'+
				'<div class="col-md-9"><input type="text" name="payment_date" class="payment-date form-control" value="'+payment_date+'"></div>'+
			'</div>'+
			'{{ csrf_field() }}'+
		'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Edit Payment for Invoice '+invoice_number,
				message: add_payment_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Edit Payment',
						className: 'btn-primary'
					}
				}
			},
			after_init : function() {
				$('.payment-date').datetimepicker({
					format : 'DD/MM/YYYY',
					useCurrent : false,
				});

				$('select[name="method"]').val(payment_details.method);
			}
		});
	}

	function add_payment(invoice_id,invoice_number)
	{
		var add_payment_modal_html = ''+
		'<form action="/admin/payments/add" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Invoice ID</label>'+
				'<div class="col-md-9">'+
					'<input type="hidden" name="invoice_id" class="form-control" value="'+invoice_id+'">'+
					'<input type="hidden" name="account_id" class="form-control" value="{{$account->id}}">'+
					'<div class="control-label" style="text-align:left">'+invoice_number+'</div>'+
				'</div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Amount</label>'+
				'<div class="col-md-9"><input type="text" name="amount" class="form-control" placeholder="e.g. 5.00"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Payment Type</label>'+
				'<div class="col-md-9">'+
					'<select name="method" class="form-control">'+
						'<option>credit card</option>'+
						'<option>bank deposit</option>'+
						'<option>cash</option>'+
						'<option>cheque</option>'+
					'</select>'+
				'</div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Payment Date</label>'+
				'<div class="col-md-9"><input type="text" name="payment_date" class="payment-date form-control"></div>'+
			'</div>'+
			'{{ csrf_field() }}'+
		'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Add Payment for Invoice '+invoice_number,
				message: add_payment_modal_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Add Payment',
						className: 'btn-primary'
					}
				}
			},
			after_init : function() {
				$('.payment-date').datetimepicker({
					format : 'DD/MM/YYYY',
					useCurrent : false,
				});
			}
		});
	}

	function delete_payment(payment_id)
	{
		modalform.dialog({
			bootbox : {
				title: 'Delete Payment',
				message: ''+
					'<form action="/admin/payments/delete/' + payment_id + '" method="post" class="form-horizontal">'+
						'<p>Are you sure you want to delete this payment entry?</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Payment',
						className: 'btn-danger'
					}
				}
			}
		});
	}

	function add_holiday(account_id)
	{
		var add_holiday_period_html = ''+
		'<form action="/admin/holidays/add/'+account_id+'" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Start Date</label>'+
				'<div class="col-md-9"><input type="text" name="start_date" class="start-date form-control"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">End Date</label>'+
				'<div class="col-md-9"><input type="text" name="end_date" class="end-date form-control"></div>'+
			'</div>'+
			'{{ csrf_field() }}'+
		'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Add Holiday Period',
				message: add_holiday_period_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Add',
						className: 'btn-primary'
					}
				}
			},
			autofocus : false,
			after_init : function() {
				$('.start-date,.end-date').datetimepicker({
					format : 'DD/MM/YYYY',
					useCurrent : false,
					minDate: moment(),
				});
			}
		});
	}

	function edit_holiday(holiday_id,start_date,end_date)
	{
		var edit_holiday_period_html = ''+
		'<form action="/admin/holidays/edit/'+holiday_id+'" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Start Date</label>'+
				'<div class="col-md-9"><input type="text" name="start_date" value="'+start_date+'" class="start-date form-control"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">End Date</label>'+
				'<div class="col-md-9"><input type="text" name="end_date" value="'+end_date+'" class="end-date form-control"></div>'+
			'</div>'+
			'{{ csrf_field() }}'+
		'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Edit Holiday Period',
				message: edit_holiday_period_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Update',
						className: 'btn-primary'
					}
				}
			},
			autofocus : false,
			after_init : function() {
				$('.start-date,.end-date').datetimepicker({
					format : 'DD/MM/YYYY',
					useCurrent : false,
					minDate: moment(),
				});
			}
		});
	}

	function delete_holiday(holiday_id)
	{
		modalform.dialog({
			bootbox : {
				title: 'Delete Holiday',
				message: ''+
					'<form action="/admin/holidays/delete/' + holiday_id + '" method="post" class="form-horizontal">'+
						'<p>Are you sure you want to delete this holiday period?</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Holiday Period',
						className: 'btn-danger'
					}
				}
			}
		});
	}


	function edit_note(note)
	{
		var note_id = $(note).data("id");
		var content = $(note).data("content");
		var edit_note_html = ''+
		'<form action="/admin/notes/edit/'+note_id+'" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Note</label>'+
				'<div class="col-md-9"><textarea class="form-control" rows="5" id="note" name="note"></textarea></div>'+
			'</div>'+
			'{{ csrf_field() }}'+
		'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Edit Note',
				message: edit_note_html,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Update',
						className: 'btn-primary'
					}
				}
			},
			autofocus : true,
			after_init : function() {
				$('.modal [name="note"]').val(content);
			}
		});
	}

	function delete_note(note_id)
	{
		modalform.dialog({
			bootbox : {
				title: 'Delete Note',
				message: ''+
					'<form action="/admin/notes/delete/' + note_id + '" method="post" class="form-horizontal">'+
						'<p>Are you sure you want to delete this note?</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Note',
						className: 'btn-danger'
					}
				}
			}
		});
	}

	function add_files()
	{
		var html = ''+
			'<form action="/admin/accounts/add-file/{{ $account->id }}" method="post" enctype="multipart/form-data" class="dropzone">'+
				'{{ csrf_field() }}'+
			'</form>';

		bootbox.dialog({
			title: 'Add Files',
			message: html,
			buttons: {
				submit: {
					label: 'Done',
					className: 'btn-primary',
					callback: function() {
						document.location.reload();
					}
				}
			}
		});

		$('.dropzone').dropzone();
	}

	function delete_file(file_id)
	{
		var html = ''+
			'<form action="/admin/accounts/delete-file/{{ $account->id }}/' + file_id + '" method="post">'+
				'<p>Are you sure you want to delete this file?</p>'+
				'{{ csrf_field() }}'+
			'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Delete File',
				message: html,

				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {

						label: 'Delete Note',

						className: 'btn-danger'
					}
				}
			}
		});
	}


	function add_credit(account_id)
	{
		var add_credit_modal = ''+
		'<form action="/admin/accounts/add-credit/'+account_id+'" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Cost</label>'+
				'<div class="col-md-9"><input type="text" name="cost" id="add_credit_cost" class="form-control"></div>'+
			'</div>'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Number of Credits</label>'+
				'<div class="col-md-9"><input type="text" id="add_credit_credits" name="credits" class="form-control"></div>'+
			'</div>'+
			'{{ csrf_field() }}'+
		'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Please enter the amount of credit you want to add.',
				message: add_credit_modal,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Submit',
						className: 'btn-primary'
					}
				}
			},
			after_init : function() {
				$('#add_credit_cost').on('change',function(){
					$('#add_credit_credits').val($(this).val());
				});
			}
		});
	}

	function terminate_account(account_id)
	{
		var delete_account_modal = ''+
		'<form action="/admin/accounts/terminate/'+account_id+'" method="post" class="form-horizontal">'+
			'<div class="form-group">'+
				'<label class="col-md-3 control-label">Termination Date</label>'+
				'<div class="col-md-9"><input type="text" name="termination_date" class="termination-date form-control"></div>'+
			'</div>'+
			'{{ csrf_field() }}'+
		'</form>';

		modalform.dialog({
			bootbox: {
				title: 'Are you sure you want to delete this account?',
				message: delete_account_modal,
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete',
						className: 'btn-primary'
					}
				}
			},
			autofocus : false,
			after_init : function() {
				var date = new Date();
				$('.termination-date').datetimepicker({
					format : 'DD/MM/YYYY',
					minDate : date,
				});
			}
		});
	}
	function addNote() {
		$('.btn-add-note').off('click');
		$('.btn-add-note').on('click',function(event){
			$('.note-box').toggle(300);
			$(".btn-add-note span").html($(".btn-add-note span").html() == 'Cancel' ? 'Add Note' : 'Cancel');
		});
	}

	$(document).ready(function(){
		addNote();
		@if ($errors->has('note'))
			$('.btn-add-note').click();
		@endif
	});

	$(document).ready(function() {
		$('#add-note-form').off('submit');
		$('#add-note-form').on('submit', function(e) {
			e.preventDefault();
			$( ".help-block" ).remove();
			if (validateNote()) {

				var formData = {
					_token     : '{{ csrf_token() }}',
					acc_id     : $('#account_id').val(),
					note  : $('#note').val()
				};

				$.ajax({
					type: 'post',
					url: '/admin/notes/add/'+formData.acc_id,
					data: formData,
					cache: false,
					error: function(jqxhr, status, error) {
						var errors = [];

						if (jqxhr.status == 422) {
							for (var field in jqxhr.responseJSON) {
								$.merge(errors, jqxhr.responseJSON[field]);
							}
						} else {
							errors = [error];
						}

						$("#note").parent().find(".alert.alert-danger").remove();
						$("#note").parent().append($('<div class="alert alert-danger"></div>').html(errors.join('<br>')));
					},
					success:function(){
						location.reload();
					}
				});
			}
		});
		});

	function validateNote(){
		if($('#note').val() == ""){
			$('#note').parent().addClass('has-error');
			$("#note").parent().append('<span class="help-block"><strong>The Note is required.</strong></span>');
			return false;
		}
		return true;
	}
	</script>
@endsection
