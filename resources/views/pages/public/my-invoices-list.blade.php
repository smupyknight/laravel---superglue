@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title }}</h5>
					</div>
					<div class="ibox-content">
						<form method="GET" action="/my-invoices">
							<div class="input-group">
								<input type="text" name="search" class="form-control" value="{{ Request::get('search') }}" placeholder="Xero Invoice Number">
								<span class="input-group-btn">
									<button type="submit" class="btn btn btn-primary"> <i class="fa fa-search"></i> Search</button>
								</span>
							</div>
						</form>

						<hr>

						@if (count($invoices))
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Xero Invoice Number</th>
										<th>Amount</th>
										<th>Processing Fee</th>
										<th>Due Date</th>
										<th>Status</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody class="table_body">
									@foreach ($invoices as $invoice)
										<tr>
											<td># {{ $invoice->xero_invoice_number }}</td>
											<td>$ {{ $invoice->total }}</td>
											<td>$ {{ $invoice->processing_fee }}</td>
											<td>{{ $invoice->due_date }}</td>
											<td>{{ $invoice->status }}</td>
											<td>
												<a href="/invoices/view/{{ $invoice->id }}" class="btn btn-default btn-xs">View</a>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
 						@else
							<p>There are no invoices matching your search criteria.</p>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
