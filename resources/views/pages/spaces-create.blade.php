@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Create Space</h5>
					<div class="ibox-tools">
						<a class="collapse-link">
							<i class="fa fa-chevron-up"></i>
						</a>
						<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="fa fa-wrench"></i>
						</a>
						<ul class="dropdown-menu dropdown-user">
							<li><a href="#">Config option 1</a>
							</li>
							<li><a href="#">Config option 2</a>
							</li>
						</ul>
						<a class="close-link">
							<i class="fa fa-times"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal" action="" method="POST">
					{{csrf_field()}}
						<div class="form-group {{ $errors->has('space_name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Space Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Space Name" name="space_name" value="{{old('space_name')}}" class="form-control">
										@if ($errors->has('space_name'))
											<span class="help-block"><strong>{{ $errors->first('space_name') }}</strong></span>
										@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Address</label>
								<div class="col-lg-10"><input type="text" placeholder="Address" name="address" value="{{old('address')}}" class="form-control">
										@if ($errors->has('address'))
											<span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
										@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('suburb') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Suburb</label>
								<div class="col-lg-10"><input type="text" placeholder="Suburb" name="suburb" value="{{old('suburb')}}" class="form-control">
										@if ($errors->has('suburb'))
											<span class="help-block"><strong>{{ $errors->first('suburb') }}</strong></span>
										@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('postcode') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Postcode</label>
								<div class="col-lg-10"><input type="text" placeholder="Postcode" name="postcode" value="{{old('postcode')}}" class="form-control">
										@if ($errors->has('postcode'))
											<span class="help-block"><strong>{{ $errors->first('postcode') }}</strong></span>
										@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">State</label>
								<div class="col-lg-10"><input type="text" placeholder="State" name="state" value="{{old('state')}}" class="form-control">
										@if ($errors->has('state'))
											<span class="help-block"><strong>{{ $errors->first('state') }}</strong></span>
										@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('country') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Country</label>
								<div class="col-lg-10"><input type="text" placeholder="Country" name="country" value="{{old('country')}}" class="form-control">
										@if ($errors->has('country'))
											<span class="help-block"><strong>{{ $errors->first('country') }}</strong></span>
										@endif
								</div>
						</div>
						<div class="form-group{{ $errors->has('timezone') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Timezone</label>
							<div class="col-lg-10">
								<select name="timezone" class="form-control">
									<option value="">Select timezone</option>
									<optgroup label="Australia">
										@foreach (\DateTimeZone::listIdentifiers(\DateTimeZone::AUSTRALIA) as $timezone)
											<option value="{{ $timezone }}">{{ preg_replace('%.*/%', '', str_replace('_', ' ', $timezone)) }} ({{ (new DateTime('now', new DateTimeZone($timezone)))->format('g:ia') }})</option>
										@endforeach
									</optgroup>
								</select>
								@if ($errors->has('timezone'))
									<span class="help-block">
										<strong>{{ $errors->first('timezone') }}</strong>
									</span>
								@endif
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<a href="/admin/spaces" class="btn btn-sm btn-white">Cancel</a>
								<button class="btn btn-sm btn-primary" type="submit">Create</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection