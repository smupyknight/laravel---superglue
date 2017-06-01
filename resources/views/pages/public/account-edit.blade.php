@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Edit User</h5>
					<div class="ibox-tools">

					</div>
				</div>
				<div class="ibox-content">
					@if (session('success'))
						<div class="alert alert-success">
							{{ session('success') }}
						</div>
					@endif
					<form class="form-horizontal" method="post" action="">
						{{ csrf_field() }}

						<div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">First Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Name" name="first_name" class="form-control" value="{{ old('first_name',$user->first_name) }}">
									@if ($errors->has('first_name'))
										<span class="help-block"><strong>{{ $errors->first('first_name') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Last Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Last Name" name="last_name" class="form-control" value="{{ old('last_name',$user->last_name) }}">
									@if ($errors->has('last_name'))
										<span class="help-block"><strong>{{ $errors->first('last_name') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Email</label>
							<div class="col-lg-10">
							<input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email',$user->email) }}">
								@if ($errors->has('email'))
									<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
								@endif
							</div>
						</div>
						<div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}"><label class="col-lg-2 control-label">Password</label>
							<div class="col-lg-10"><input type="password" placeholder="Password" class="form-control" name="password" value="">
								@if ($errors->has('password'))
									<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
								@endif
							</div>
						</div>
						<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Confirm Password</label>
							<div class="col-lg-10">
							<input type="password" class="form-control" placeholder="Password" name="password_confirmation">
								@if ($errors->has('password_confirmation'))
									<span class="help-block"><strong>{{ $errors->first('password_confirmation') }}</strong></span>
								@endif
							</div>
						</div>
						<div class="form-group {{ $errors->has('company_name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Company Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Company Name" name="company_name" class="form-control" value="{{ old('company_name',$user->company_name) }}">
									@if ($errors->has('company_name'))
										<span class="help-block"><strong>{{ $errors->first('company_name') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('job_title') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Job Title</label>
								<div class="col-lg-10"><input type="text" placeholder="Job Title" name="job_title" class="form-control" value="{{ old('job_title',$user->job_title) }}">
									@if ($errors->has('job_title'))
										<span class="help-block"><strong>{{ $errors->first('job_title') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Date of Birth</label>
								<div class="col-lg-10">
								<input type="text" placeholder="DD/MM/YYYY" name="dob" class="date-of-birth form-control" value="{{ old('dob',$user->dob) ?  Carbon\Carbon::createFromFormat('Y-m-d',old('dob',$user->dob))->format('d/m/Y') : ''}}">
									@if ($errors->has('dob'))
										<span class="help-block"><strong>{{ $errors->first('dob') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Phone</label>
								<div class="col-lg-10"><input type="text" placeholder="Phone" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}">
									@if ($errors->has('phone'))
										<span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Address</label>
								<div class="col-lg-10"><input type="text" placeholder="Address" name="address" class="form-control" value="{{ old('address',$user->address) }}">
									@if ($errors->has('address'))
										<span class="help-block"><strong>{{ $errors->first('address') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('twitter_handle') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Twitter Handle</label>
								<div class="col-lg-10"><input type="text" placeholder="Twitter Handle" name="twitter_handle" class="form-control" value="{{ old('twitter_handle',$user->twitter_handle) }}">
									@if ($errors->has('twitter_handle'))
										<span class="help-block"><strong>{{ $errors->first('twitter_handle') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('instagram_handle') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Instagram Handle</label>
								<div class="col-lg-10"><input type="text" placeholder="Instagram Handle" name="instagram_handle" class="form-control" value="{{ old('instagram_handle',$user->instagram_handle) }}">
									@if ($errors->has('instagram_handle'))
										<span class="help-block"><strong>{{ $errors->first('instagram_handle') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('bio') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Bio</label>
								<div class="col-lg-10">
								<textarea rows="3" name="bio" id="bio" placeholder="Bio" class="form-control">{{old('bio',$user->bio)}}</textarea>
									@if ($errors->has('bio'))
										<span class="help-block"><strong>{{ $errors->first('bio') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button class="btn btn-sm btn-white" type="submit">Update</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
	<script>
		$(document).ready(function(){
			$('.date-of-birth').datetimepicker({
				format : 'DD/MM/YYYY',
				useCurrent : false,
			});
		});
	</script>
@endsection