@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>Invoices</h5>
					</div>
					<div class="ibox-content">
						<form method="GET" action="/admin/invoices">
							<div class="input-group">
								<input type="text" name="search" class="form-control" value="{{ Request::get('search') }}" placeholder="Account name or email">
								<span class="input-group-btn">
									<button type="submit" class="btn btn btn-primary"> <i class="fa fa-search"></i> Search</button>
								</span>
							</div>
						</form>

						<hr>

						@if ($invoices->total())
							<table class="table table-striped">
								<thead>
									<tr>
										<th>ID</th>
										<th>Account</th>
										<th>Amount</th>
										<th>Due Date</th>
										<th>Status</th>
										<th>Credit Card</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody class="table_body">
									@foreach ($invoices as $invoice)
										<tr>
											<td>{{ $invoice->id }}</td>
											<td><a href="/admin/accounts/view/{{ $invoice->account_id }}">{{ $invoice->account->name }}</a></td>
											<td>${{ number_format($invoice->total, 2) }}</td>
											<td>{{ $invoice->due_date->format('j M Y') }}</td>
											<td>
												@if ($invoice->status == 'pending')
													<span class="label label-info">Pending</span>
												@else
													<span class="label label-success">Paid</span>
												@endif
											</td>
											<td>
												@if ($invoice->account->stripe_id)
													<span class="label label-success">Yes</span>
												@else
													<span class="label label-info">No</span>
												@endif
											</td>
											<td>
												<a href="/invoices/view/{{ $invoice->id }}" class="btn btn-default btn-xs">View</a>
												@if ($invoice->status == 'pending')
													<a href="#" onclick="delete_invoice({{ $invoice->id }});return false;" class="btn btn-default btn-xs">Delete</a>
												@endif
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>

							{!! $invoices->appends(Request::all())->render() !!}
						@else
							<p>There are no invoices matching your search criteria.</p>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
<script src="/js/modalform.js"></script>
<script type="text/javascript">
	function delete_invoice(invoice_id)
	{
		modalform.dialog({
			bootbox : {
				title: 'Delete Invoice',
				message: ''+
					'<form action="/invoices/delete/' + invoice_id + '" method="post" class="form-horizontal">'+
						'<p>Are you sure you want to delete this invoice entry?</p>'+
						'{{ csrf_field() }}'+
					'</form>',
				buttons: {
					cancel: {
						label: 'Cancel',
						className: 'btn-default'
					},
					submit: {
						label: 'Delete Invoice',
						className: 'btn-danger'
					}
				}
			}
		});
	}
</script>
@endsection