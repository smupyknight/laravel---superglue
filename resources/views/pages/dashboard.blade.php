@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-sm-6">
			<div class="ibox">
				<div class="ibox-title">
					<h5>Overdue Invoices</h5>
					<div class="ibox-tools">
					</div>
				</div>
				<div class="ibox-content">
					@if($invoices)
						<table class="table table-striped">
							<thead>
								<th>ID</th>
								<th>Account</th>
								<th>Email</th>
								<th>Amount</th>
								<th>Due</th>
								<th>Actions</th>
							</thead>
							<tbody>
								@foreach($invoices as $invoice)
									<tr>
										<td>{{ $invoice->id }}</td>
										<td><a href="/admin/accounts/view/{{ $invoice->account->id }}">{{ $invoice->account->name }}</a></td>
										<td>{{ $invoice->account->email }}</td>
										<td>${{ $invoice->total }}</td>
										<td>{{ $invoice->due_date->format('d/m/Y') }}</td>
										<td><a href="/invoices/view/{{ $invoice->id }}" class="btn btn-xs btn-default">View</a><a href="#" onclick="deleteInvoice('{{ $invoice->id }}');return false;" class="btn btn-xs btn-default">Delete</a></td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<p>No Invoices Overdue</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
	<script>
		function deleteInvoice(invoice_id)
		{
			if (window.confirm('Delete Invoice?')) {
				$.ajax({
					url:'/invoices/delete/' + invoice_id,
					method:'post',
					data:{
						_token : '{{ csrf_token() }}',
					},
					success: function(response){
						window.location.reload();
					}
				})
			}
		}
	</script>
@endsection