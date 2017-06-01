<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Superglue | {{ $title or '' }}</title>

		<link href="{{ url('/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ url('/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

		<link href="{{ url('/css/animate.css') }}" rel="stylesheet">
		<link href="{{ url('/css/style.css') }}" rel="stylesheet">
		<link href="{{ url('/css/datetimepicker.css') }}" rel="stylesheet">
		<link href="{{ url('/css/plugins/steps/jquery.steps.css') }}" rel="stylesheet">
	</head>
	<body class="gray-bg">
		<div class="wrapper wrapper-content p-xl">
			<div class="ibox-content p-xl">
				<div class="row">
					<div class="col-sm-6">
						<h5>From:</h5>
						<address>
							<strong>Superglue</strong><br>
							Demo Address<br>
							Demo City, State 32456<br>
							<abbr title="Phone">P:</abbr> (123) 601-4590
						</address>
					</div>

					<div class="col-sm-6 text-right">
						<h4>Invoice No.</h4>
						<h4 class="text-navy">INV-{{ $invoice->id }}</h4>
						<span>To:</span>
						<address>
							<strong>{{ $invoice->account->billing_name }}</strong><br>
							{{ $invoice->account->address }}<br>
							{{ $invoice->account->suburb .', '. $invoice->account->state .', '. $invoice->account->country .', '. $invoice->account->postcode }}<br>
							{{ $invoice->account->email }}
						</address>
						<p>
							<span><strong>Invoice Date:</strong> {{ $invoice->created_at->format('M j , Y') }}</span><br/>
							<span><strong>Due Date:</strong> {{ $invoice->due_date->format('M j , Y') }}</span>
						</p>
					</div>
				</div>

				<div class="table-responsive m-t">
					<table class="table invoice-table">
						<thead>
							<tr>
								<th>Invoice Item</th>
								<th>Credits</th>
								<th>Cost</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($invoice->items as $item)
								<tr>
									<td><strong>{{ $item->description }}</strong></td>
									<td>{{ $item->num_credits }}</td>
									<td>${{ $item->cost }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				<table class="table invoice-total">
					<tbody>
						@if ($invoice->processing_fee > 0)
							<tr>
								<td>Processing Fee:</td>
								<td>${{ number_format($invoice->processing_fee, 2) }}</td>
							</tr>
						@endif
						<tr>
							<td><strong>TOTAL:</strong></td>
							<td>${{ number_format($invoice->total, 2) }}</td>
						</tr>
					</tbody>
				</table>
				<div class="well m-t">
					<strong>Comments</strong>
					Some general guidelines for invoice can be added here.
				</div>
			</div>
		</div>

		<script src="{{ url('/js/jquery-2.1.1.js') }}"></script>
		<script src="{{ url('/js/bootstrap.min.js') }}"></script>
		<script src="{{ url('/js/moment.min.js') }}"></script>
		<script src="{{ url('/js/datetimepicker.js') }}"></script>
		<script src="{{ url('/js/plugins/steps/jquery.steps.min.js') }}"></script>
	</body>
</html>
