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
						<img src="/images/logo/lt2_logo.jpg" width="300px">
						<h5>From your friends at:</h5>
						<address>
							<p>
								<strong>Little Tokyo Two (SuperGlue)</strong><br>
								ABN: 65 600 877 567
							</p>
							<p>
								36 Mein Street,<br>
								Spring Hill, QLD 4000<br>
								<abbr title="Phone">P:</abbr> (07) 3831 6936<br>
								Email: <a href="mailto:accounts@littletokyotwo.com">accounts@littletokyotwo.com</a>
							</p>
						</address>
					</div>

					<div class="col-sm-6 text-right">
						<h2>TAX INVOICE</h2>
						<h4>Invoice No.</h4>
						<h4 class="text-navy">INV-{{ $invoice->id }}</h4>
						<span>To:</span>
						<address>
							<strong>{{ $invoice->account->name }}</strong><br>

							@if ($invoice->address)
								{{ $invoice->account->address }}<br>
								{{ $invoice->account->suburb .', '. $invoice->account->state .', '. $invoice->account->country .', '. $invoice->account->postcode }}<br>
							@endif

							{{ $invoice->account->email }}
						</address>
						<p>
							<span><strong>Invoice Date:</strong> {{ $invoice->created_at->format('M j, Y') }}</span><br/>
							<span><strong>Due Date:</strong> {{ $invoice->due_date->format('M j, Y') }}</span>
						</p>
					</div>
				</div>

				<div class="table-responsive m-t">
					<table class="table invoice-table">
						<thead>
							<tr>
								<th>Invoice Item</th>
								<th>Cost (ex GST)</th>
								<th>GST</th>
								<th>Cost (inc GST)</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($invoice->items as $item)
								<tr>
									<td>{{ $item->description }}</td>
									<td>${{ number_format($item->cost / 11 * 10, 2) }}</td>
									<td>${{ number_format($item->cost / 11, 2) }}</td>
									<td>${{ number_format($item->cost, 2) }}</td>
								</tr>
							@endforeach
							@if ($invoice->processing_fee > 0)
								<tr>
									<td>Processing Fee (American Express)</td>
									<td>${{ number_format($invoice->processing_fee / 11 * 10, 2) }}</td>
									<td>${{ number_format($invoice->processing_fee / 11, 2) }}</td>
									<td>${{ number_format($invoice->processing_fee, 2) }}</td>
								</tr>
							@endif
						</tbody>
					</table>
				</div>

				<table class="table invoice-total">
					<tbody>
						<tr>
							<td>Total ex GST:</td>
							<td>${{ number_format($invoice->total / 11 * 10, 2) }}</td>
						</tr>
						<tr>
							<td>GST:</td>
							<td>${{ number_format($invoice->total / 11, 2) }}</td>
						</tr>
						<tr>
							<td><strong>TOTAL:</strong></td>
							<td>${{ number_format($invoice->total, 2) }}</td>
						</tr>
					</tbody>
				</table>
				<div class="well m-t">
					You're awesome! Thanks for making Little Tokyo Two great!<br>
					We'd love to know if there is anything we can do better. Let us know your thoughts via <a href="mailto:happiness@littletokyotwo.com">happiness@littletokyotwo.com</a>
				</div>
			</div>
		</div>

		<!-- Mainly scripts -->
		<script src="{{ url('/js/jquery-2.1.1.js') }}"></script>
		<script src="{{ url('/js/bootstrap.min.js') }}"></script>
		<script src="{{ url('/js/moment.min.js') }}"></script>
		<script src="{{ url('/js/datetimepicker.js') }}"></script>
		<script src="{{ url('/js/plugins/steps/jquery.steps.min.js') }}"></script>
	</body>
</html>
