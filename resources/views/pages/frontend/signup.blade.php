@extends('layouts.public')
@section('content')
<div class="container">
	<div class="well">
		<div class="row">
			<div class="ibox-content">
				<div style="height:auto;width:400px;margin:auto">
					<img src="/images/logo/superglue-plain-logo.png" class="img-responsive" style="margin:auto;padding-top:35px;"><br>
				</div>
				@include('partials.errors')
				<div class="charge-error alert alert-danger" style="display:none;">

				</div>
				<form method="post" class="plansignup" action="/signup">

					<div class="row">
						<div class="form-group col-lg-12">
							<select class='form-control m-b' name="space" >
								<option value="">Select Location</option>
								@foreach($spaces as $space)
									<option value="{{$space->id}}" {{ old('space') == $space->id ? ' selected' : '' }}>{{ $space->name }}, {{ $space->address }}, {{ $space->suburb }} {{ $space->state }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<h3>Personal Details</h3>

					<div class="row">
						<div class="form-group col-lg-6">
							<input class="form-control" name="first_name" value="{{old('first_name')}}" placeholder="First Name *" type="text">
							</input>
						</div>
						<div class="form-group col-lg-6">
							<input class="form-control" name="last_name" value="{{old('last_name')}}" placeholder="Last Name *" type="text">
							</input>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-6">
							<input class="form-control" name="mobile_number" value="{{old('mobile_number')}}" placeholder="Mobile Number *" type="text">
							</input>
						</div>
						<div class="form-group col-lg-6">
							<input class="form-control" name="email" value="{{old('email')}}" placeholder="Email *" type="text">
							</input>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-6">
							<input type="password" placeholder="Password" class="form-control" name="password" >
						</div>
						<div class="form-group col-lg-6">
							<input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-12">
							<input class="form-control" name="street_address" value="{{old('street_address')}}" placeholder="Address *" type="text">
							</input>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-6">
							<select name="state" class="form-control">
							<option value="">Select State</option>
							@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
								<option value="{{ $state }}"{{ $state == old('state') ? ' selected' : '' }}>{{ $state }}</option>
							@endforeach
						</select>
						</div>
						<div class="form-group col-lg-6">
							<input class="form-control" name="postcode" value="{{old('postcode')}}"  placeholder="Postcode *" type="text">
							</input>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-6">
							<input type="text" placeholder="Date of Birth" name="dob" class="date-of-birth form-control" value="{{ old('dob') }}">
						</div>
					</div>

					<h3>Emergency Contact Details</h3>

					<div class="row">
						<div class="form-group col-lg-6">
							<input class="form-control" name="emergencyContactName" value="{{old('emergencyContactName')}}" placeholder="Name *" type="text">
							</input>
						</div>
						<div class="form-group col-lg-6">
							<input class="form-control" name="emergencyContactMobile"  value="{{old('emergencyContactMobile')}}"  placeholder="Mobile *" type="text">
							</input>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-12">
							<input class="form-control" name="emergencyContactRelation" value="{{old('emergencyContactRelation')}}" placeholder="Emergency Contact Relationship to You *" type="text">
							</input>
						</div>
					</div>
					<hr/>

					<h3>How did you hear about LT2?</h3>

					<div class="row">
						<div class="form-group col-lg-6">
							<select name="referrer" class="select form-control" >
								<option value="">Select Source</option>
								<option value="Member">Member</option>
								<option value="Staff">Staff</option>
								<option value="Event">Event</option>
								<option value="Search">Search</option>
								<option value="Facebook">Facebook</option>
								<option value="Twitter">Twitter</option>
								<option value="Linkedin">Linkedin</option>
								<option value="Friend">Friend</option>
								<option value="Other">Other</option>
							</select>
						</div>
						<div class="form-group col-lg-6">
							<input class="form-control" name="referrer_detail" value="{{old('referrer_detail')}}" placeholder="Name of person, event or other who referred you (if applicable)" type="text">
							</input>
						</div>
					</div>
					<hr/>

					<h3>Business Information</h3>

					<div class="row">
						<div class="form-group col-lg-6">
							<input type="text" class="input-text form-control" name="billing_company" id="billing_company"	value="{{old('billing_company')}}" placeholder="Entity / Company Name *" value="">
							</input>
						</div>
						<div class="form-group col-lg-6">
							<input type="text" class="input-text form-control" name="company_abn" id="company_abn" value="{{old('company_abn')}}" placeholder="ABN" value="">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-lg-6">
							<input type="text" class="input-text form-control" name="company_industry" id="company_industry" value="{{old('company_industry')}}" placeholder="Industry" value="">
						</div>
						<div class="form-group col-lg-6">
							<input type="text" class="input-text form-control" name="company_website" id="company_website" value="{{old('company_website')}}" placeholder="Website" value="">
						</div>
					</div>

					<hr/>
					<h3>Payment Details</h3>

					<div class="row">
						<div class="form-group col-md-12">
							<input type="text" name="card_number" placeholder="Card Number"  class="form-control">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<select name="card_expiry_month" class="form-control">
								@for ($i = 1; $i <= 12; $i++)
									<option value="{{ $i }}">{{ sprintf('%02d', $i) }} ({{ (new DateTime("2000-$i-15"))->format('F') }})</option>
								@endfor
							</select>
						</div>
						<div class="form-group col-md-6">
							<select name="card_expiry_year" class="form-control">
								@for ($i = date('Y'); $i <= date('Y')+15; $i++)
									<option value="{{ $i }}">{{ $i }}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12"><input type="text" name="card_cvc" placeholder="CVC"  class="form-control"></div>
					</div>

					<hr>
					<h3 class="text-center">Your Order Summary</h3>

					<div class="row">
						<table class="table">
							<thead>
								<tr>
									<th>Product</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody class="table_body">
								<tr>
									<td>
										Membership Plan <br>
										(<b>{{ $plan->name}}</b>)
									</td>
									<td>${{$plan->cost}}/fortnight + ${{$plan->setup_cost}} sign-up fee</td>
								</tr>
								<tr>
									<td><b>Order Total</b></td>
									<td><b>${{$plan->cost + $plan->setup_cost}}</b> (includes ${{ number_format(($plan->cost + $plan->setup_cost) * 0.1, 2) }} GST)</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="row">
						<div class="form-group col-lg-12">
							<button class="btn btn-lg btn-primary" type="submit">SIGN UP NOW</button>
							<input type="hidden" name="plan_id" value="{{ $plan->id }}">
							{!! csrf_field() !!}
						</div>
					</div>

				</form>
			</div>
		</div>

</div>
@endsection
@section('scripts')
	<script src="https://js.stripe.com/v2/"></script>
	<script>
		Stripe.setPublishableKey('{{ env('STRIPE_KEY') }}');
		$('.plansignup').submit(function(event){
			event.preventDefault();
			$('button[type="submit"]').text('One moment...').attr('disabled', 'disabled');
			$('.charge-error').css("display","none");

			Stripe.card.createToken({
				number: $('input[name="card_number"]').val(),
				exp_month: $('select[name="card_expiry_month"]').val(),
				exp_year: $('select[name="card_expiry_year"]').val(),
				cvc: $('input[name="card_cvc"]').val()
			},function(status, response) {
				if (response.error)
				{
					$('.charge-error').css("display","block");
					$('.charge-error').text(response.error.message);
					$('button[type="submit"]').text('SIGN UP NOW').removeAttr('disabled');
				}
				else
				{
					var token = response.id;
					$('.plansignup').append($('<input type="hidden" name="stripeToken"/>').val(token));
					$('.plansignup').get(0).submit();
				}
			})
		});
	</script>
	<script>
		$(document).ready(function(){
			$('.date-of-birth').datetimepicker({
				format : 'YYYY-MM-DD',
				useCurrent : false,
			});
		});
	</script>
@endsection
