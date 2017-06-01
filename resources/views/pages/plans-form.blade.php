@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>{{ $title or '' }}</h5>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal" method="POST" action="">
						{{csrf_field()}}

						<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Name</label>
							<div class="col-lg-4">
								<input type="text" name="name" class="form-control" value="{{ old('name', $plan->name) }}">
								@if ($errors->has('name'))
									<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
								@endif
							</div>
						</div>

						<div class="form-group {{ $errors->has('num_seats') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Seats</label>
							<div class="col-lg-4">
								<input type="text" name="num_seats" class="form-control" value="{{ old('num_seats', $plan->num_seats) }}">
								@if ($errors->has('num_seats'))
									<span class="help-block"><strong>{{ $errors->first('num_seats') }}</strong></span>
								@endif
							</div>
						</div>

						<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Credit</label>
							<div class="col-lg-4">
								<div class="input-group">
									<input type="text" name="credit_per_renewal" class="form-control" value="{{ old('credit_per_renewal', $plan->credit_per_renewal) }}">
									<span class="input-group-addon">per renewal</span>
								</div>
								@if ($errors->has('credit_per_renewal'))
									<span class="help-block"><strong>{{ $errors->first('credit_per_renewal') }}</strong></span>
								@endif
							</div>
						</div>

						<div class="form-group {{ $errors->has('cost') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Cost</label>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input type="text" name="cost" class="form-control" value="{{ old('cost', $plan->cost) }}">
									<span class="input-group-addon">per fortnight</span>
								</div>
								@if ($errors->has('cost'))
									<span class="help-block"><strong>{{ $errors->first('cost') }}</strong></span>
								@endif
							</div>
						</div>

						<div class="form-group {{ $errors->has('setup_cost') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Setup Cost</label>
							<div class="col-lg-4">
								<div class="input-group">
									<span class="input-group-addon">$</span>
									<input type="text" name="setup_cost" class="form-control" value="{{ old('setup_cost', $plan->setup_cost) }}">
								</div>
								@if ($errors->has('setup_cost'))
									<span class="help-block"><strong>{{ $errors->first('setup_cost') }}</strong></span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<a href="/admin/plans" class="btn btn-sm btn-white" type="submit">Back</a>
								<button class="btn btn-sm btn-primary" type="submit">{{$submit_button}}</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
