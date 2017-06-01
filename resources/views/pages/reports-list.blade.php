@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="/admin/reports" class="btn btn-primary btn-xs">Create New Account</a>
						</div>
					</div>
					<div class="ibox-content">
						 <ul class="list-group">
							<li class="list-group-item">
								<h4>Billing Report <a href="/admin/reports/billing-report"><button class="btn btn-xs btn-primary pull-right">
									Run Report
								</button></a></h4>
								Upcoming billables list.
							</li>
						</ul>
						<hr>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
