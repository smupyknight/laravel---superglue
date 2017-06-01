@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row m-b-lg m-t-lg">
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="row">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
						<span class="label label-success pull-right">
							<a href="#" style="color:white" onclick="add_credit();return false;">Add Credit</a>
						</span>
							<h5>Credits</h5>
						</div>
						<div class="ibox-content">
							<h1 class="no-margins">{{ $user->account->credit_balance }}</h1>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Billing Items</h5>
					{{--
					<div class="ibox-tools">
						<a href="#" class="btn btn-primary btn-xs" onclick="add_billing_item();return false;">Add</a>
					</div>
					--}}
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<tr>
								<th>Name</th>
								<th>Cost</th>
								<th>Credits</th>
								<th>Start Date</th>
								<th>Next Billing Date</th>
								<th>End Date</th>
							</tr>
						</thead>
						<tbody>
							@if (count($billing_items))
								@foreach ($billing_items as $item)
									<tr>
										<td>{{ $item->name }}</td>
										<td>${{ number_format($item->cost,2) }}</td>
										<td>{{ $item->num_credits }}</td>
										<td>{{ $item->start_date->format('j M Y') }}</td>
										<td>{{ $item->next_billing_date->format('j M Y') }}</td>
										<td>{{ $item->end_date ? $item->end_date->format('j M Y') : '-' }}</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td align="center" colspan="6">No billing items</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
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
								<th>Status</th>
								<th>Due Date</th>
							</tr>
						</thead>
						<tbody>
							@if (count($invoices))
								@foreach ($invoices as $invoice)
									<tr>
										<td>{{ sprintf('INV-%05d', $invoice->id) }}</td>
										<td>${{ number_format($invoice->total, 2) }}</td>
										<td>
											@if ($invoice->status == 'paid')
												<span class="label label-success">Paid</span>
											@else
												<span class="label label-danger">{{ ucfirst($invoice->status) }}</span>
											@endif
										</td>
										<td>{{ $invoice->due_date->format('j M Y') }}</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td align="center" colspan="4">No invoices</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row m-b-lg m-t-lg">
		<div class="col-md-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Payments</h5>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<tr>
								<th>Date</th>
								<th>Invoice</th>
								<th>Amount</th>
								<th>Method</th>
							</tr>
						</thead>
						<tbody>
							@if (count($payments))
								@foreach ($payments as $payment)
									<tr>
										<td>{{ $payment->payment_date->format('j M Y') }}</td>
										<td>{{ sprintf('INV-%05d', $payment->invoice_id) }}</td>
										<td>${{ number_format($payment->amount, 2) }}</td>
										<td>{{ ucwords($payment->method) }}</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="4" align="center">No payments</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Credit History</h5>
				</div>
				<div class="ibox-content">
					<table class="table">
						<thead>
							<tr>
								<th>Date</th>
								<th>Description</th>
								<th>Amount</th>
						</thead>
						<tbody>
							@if (count($credit_transactions))
								@foreach ($credit_transactions as $transaction)
									<tr>
										<td>{{ $transaction->created_at->format('j M Y') }}</td>
										<td>{{ $transaction->description }}</td>
										<td>{{ $transaction->amount > 0 ? '' : '-' }}${{ number_format(abs($transaction->amount), 2) }}</td>
									</tr>
								@endforeach
							@else
								<tr>
									<td align="center" colspan="3">No transactions</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row m-b-lg m-t-lg">
		<div class="col-lg-6">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Credit Card Details</h5>
				</div>
				<div class="ibox-content">
					@if ($user->account->card_last_four)
						<p>{{ $user->account->card_brand }}: xxxx xxxx xxxx {{ $user->account->card_last_four }} &nbsp;<button type="button" class="btn btn-default btn-configure-card">Edit</button></p>
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
	<script src="/js/modalform.js"></script>
	<script src="https://js.stripe.com/v2/"></script>
	<script>
		Stripe.setPublishableKey('{{ env('STRIPE_KEY') }}');
		$(document).ready(function(){
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
										url: '/account/card/{{ $user->account->id }}',
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
		});

		function add_credit()
		{
			var add_credit_modal = ''+
			'<form action="/account/add-credit" method="post" class="form-horizontal">'+
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

		function add_billing_item()
		{
			var html = ''+
			'<form action="/billing-items/add" method="post" class="form-horizontal">'+
				'<div class="form-group">'+
					'<label class="col-md-3 control-label">Plan</label>'+
					'<div class="col-md-9">'+
						'<select name="plan" class="form-control">'+
							'<option value="">Select Plan</option>'+
							@foreach ($plans as $plan)
								'<option value="{{ $plan->id }}">{{ addslashes($plan->name) }}</option>'+
							@endforeach
						'</select>'+
					'</div>'+
				'</div>'+
				'<div class="form-group">'+
					'<label class="col-md-3 control-label">Start Date</label>'+
					'<div class="col-md-9"><input type="text" name="start_date" class="start-date form-control"></div>'+
				'</div>'+
				'{{ csrf_field() }}'+
			'</form>';

			modalform.dialog({
				bootbox: {
					title: 'Add Billing Item',
					message: html,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Add Billing Item',
							className: 'btn-primary'
						}
					}
				},
				autofocus : false,
				after_init : function() {
					$('.start-date').datetimepicker({
						format : 'DD/MM/YYYY',
						useCurrent : false,
					});
				}
			});
		}
	</script>
@endsection
