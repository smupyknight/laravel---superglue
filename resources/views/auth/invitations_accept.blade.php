@extends('layouts.public')

@section('content')
<div class="container">
	<div class="well">

		<div class="row">
			<div class="ibox-content">
                <div style="height:auto;width:400px;margin:auto">
					<img src="/images/logo/superglue-plain-logo.png" class="img-responsive" style="margin:auto;"><br>
				</div>
				<h3>{{ $user->name }}</h3>
				<form id="form" class="m-t wizard-big" role="form" enctype="multipart/form-data" method="POST" action="/invitations/accept/{{ $invitation->token }}">
					{!! csrf_field() !!}
                    <h1>Step 1</h1>
                    <fieldset>
                        <h2>Account Information</h2>
                        <div class="row">
                            <div class="col-lg-8">
	                            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
									<input type="text" class="form-control" name="first_name" placeholder="First Name" value="{{ old('first_name',$user->first_name) }}">
									@if ($errors->has('first_name'))
										<span class="help-block"><strong>{{ $errors->first('first_name') }}</strong></span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
									<input type="text" class="form-control" name="last_name" placeholder="Last Name" value="{{ old('last_name',$user->last_name) }}">
									@if ($errors->has('last_name'))
										<span class="help-block"><strong>{{ $errors->first('last_name') }}</strong></span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
									<input type="password" class="form-control" placeholder="Password" name="password">
									@if ($errors->has('password'))
										<span class="help-block">
											<strong>{{ $errors->first('password') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
									<input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
									@if ($errors->has('password_confirmation'))
										<span class="help-block">
											<strong>{{ $errors->first('password_confirmation') }}</strong>
										</span>
									@endif
								</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="text-center">
                                    <div style="margin-top: 20px">
                                        <i class="fa fa-sign-in" style="font-size: 180px;color: #e5e5e5 "></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <h1>Step 2</h1>
                    <fieldset>
                        <h2>Profile Information</h2>
                        <div class="row">
                            <div class="col-lg-8">
								<div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
									<input type="text" class="form-control date-of-birth" placeholder="Date of Birth" name="dob" value="{{ old('dob') }}">
									@if ($errors->has('dob'))
										<span class="help-block">
											<strong>{{ $errors->first('dob') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
									<input type="text" class="form-control" placeholder="Mobile Number" name="phone" value="{{ old('phone') }}">
									@if ($errors->has('phone'))
										<span class="help-block">
											<strong>{{ $errors->first('phone') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('postcode') ? ' has-error' : '' }}">
									<input type="text" class="form-control" placeholder="Postcode" name="postcode" value="{{ old('postcode') }}">
									@if ($errors->has('postcode'))
										<span class="help-block">
											<strong>{{ $errors->first('postcode') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
									<label>Profile image</label>
									<input type="file" class="form-control" placeholder="123" name="avatar" >
									@if ($errors->has('avatar'))
										<span class="help-block">
											<strong>{{ $errors->first('avatar') }}</strong>
										</span>
									@endif
								</div>
                            </div>
                        </div>
                    </fieldset>

                    <h1>Step 3</h1>
                    <fieldset>
                        <h2>Company Info</h2>
                        <div class="row">
                            <div class="col-lg-8">
	                        	<div class="form-group{{ $errors->has('job_title') ? ' has-error' : '' }}">
									<input type="text" class="form-control" placeholder="Job Title" name="job_title" value="{{ old('job_title') }}">
									@if ($errors->has('job_title'))
										<span class="help-block">
											<strong>{{ $errors->first('job_title') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('company_name') ? ' has-error' : '' }}">
									<input type="text" class="form-control" placeholder="Company Name" name="company_name" value="{{ old('company_name') }}">
									@if ($errors->has('company_name'))
										<span class="help-block">
											<strong>{{ $errors->first('company_name') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('industry') ? ' has-error' : '' }}">
									<select name="industry" id="industry" class="form-control">
										<option value="">Select Industry</option>
										@foreach ($industries as $industry)
										<option {{old('industry') == $industry ? 'selected' : ''}}>{{$industry}}</option>
										@endforeach
									</select>
									@if ($errors->has('industry'))
										<span class="help-block"><strong>{{ $errors->first('industry') }}</strong></span>
									@endif
									@if ($errors->has('industry'))
										<span class="help-block">
											<strong>{{ $errors->first('industry') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('timezone') ? ' has-error' : '' }}">
									<select name="timezone" class="form-control">
										<option value="">Select timezone</option>
										<optgroup label="Australia">
											@foreach (\DateTimeZone::listIdentifiers(\DateTimeZone::AUSTRALIA) as $timezone)
												<option value="{{ $timezone }}">{{ preg_replace('%.*/%', '', str_replace('_', ' ', $timezone)) }} ({{ (new DateTime('now', new DateTimeZone($timezone)))->format('g:ia') }})</option>
											@endforeach
										</optgroup>
										<optgroup label="New Zealand">
											<option value="Pacific/Auckland">Auckland ({{ (new DateTime('now', new DateTimeZone('Pacific/Auckland')))->format('g:ia') }})</option>
											<option value="Pacific/Chatham">Chatham ({{ (new DateTime('now', new DateTimeZone('Pacific/Chatham')))->format('g:ia') }})</option>
										</optgroup>
									</select>
									@if ($errors->has('timezone'))
										<span class="help-block">
											<strong>{{ $errors->first('timezone') }}</strong>
										</span>
									@endif
								</div>
	                        </div>
                        </div>
                    </fieldset>

                    <h1>Step 4</h1>
                    <fieldset>
                        <h2>Terms and Conditions</h2>
                        <div class="row">
                            <div class="col-lg-8">
                            	<div class="form-group{{ $errors->has('twitter_handle') ? ' has-error' : '' }}">
									<input type="text" class="form-control" placeholder="Twitter Handle (e.g. @superglue)" name="twitter_handle" value="{{ old('twitter_handle') }}">
									@if ($errors->has('twitter_handle'))
										<span class="help-block">
											<strong>{{ $errors->first('twitter_handle') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('instagram_handle') ? ' has-error' : '' }}">
									<input type="text" class="form-control" placeholder="Instagram handle (e.g. @superglue)" name="instagram_handle" value="{{ old('instagram_handle') }}">
									@if ($errors->has('instagram_handle'))
										<span class="help-block">
											<strong>{{ $errors->first('instagram_handle') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('bio') ? ' has-error' : '' }}">
									<textarea class="form-control" name="bio" rows="3" placeholder="Bio">{{ old('bio') }}</textarea>
									@if ($errors->has('bio'))
										<span class="help-block">
											<strong>{{ $errors->first('bio') }}</strong>
										</span>
									@endif
								</div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	$(function() {
		$("#form").steps({
			bodyTag: "fieldset",
			// Disables the finish button (required if pagination is enabled)
		    enableFinishButton: true,
		    // Disables the next and previous buttons (optional)
		    enablePagination: true,
		    // Enables all steps from the begining
		    enableAllSteps: true,
		    // Removes the number from the title
		    titleTemplate: "#title#",
		    onFinished: function (event, currentIndex)
            {
                var form = $(this);

                // Submit form input
                form.submit();
            }
		});

		$('.date-of-birth').datetimepicker({
			format : 'DD/MM/YYYY',
			useCurrent : false,
		});
	})
</script>
@endsection
