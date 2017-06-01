@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
	<div class="ibox float-e-margins">
		<div class="ibox-title">
			<h5>Create New Account</h5>
		</div>
		<div class="ibox-content">
			@include('partials.errors')

			<form action="/admin/accounts/create" method="post" class="form-horizontal">

				<div class="form-group">
					<label class="col-md-2 control-label">Billing Name</label>
					<div class="col-md-4"><input type="text" name="name" value="{{ old('name') }}" class="form-control"></div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Xero Contact</label>
					<div class="col-md-4">
						<select name="xero_contact_id" class="form-control" onchange="$('[name=xero_contact_name]').val($(this).find('option:selected').text())">
							<option value=""></option>
							@foreach ($xero_contacts as $contact)
								<option value="{{ $contact->getContactID() }}"{{ $contact->getContactID() == old('xero_contact_id') ? ' selected' : '' }}>{{ $contact->getName() }}</option>
							@endforeach
						</select>
						<span class="help-block">If left empty, a Xero contact will be created when this account pays their first invoice.</span>
						<input type="hidden" name="xero_contact_name" value="{{ old('xero_contact_name') }}">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Address</label>
					<div class="col-md-4"><input type="text" name="address" value="{{ old('address') }}" class="form-control"></div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Suburb</label>
					<div class="col-md-4"><input type="text" name="suburb" value="{{ old('suburb') }}" class="form-control"></div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">State</label>
					<div class="col-md-4">
						<select name="state" class="form-control">
							<option value=""></option>
							@foreach (['ACT','NSW','NT','QLD','SA','TAS','VIC','WA'] as $state)
								<option value="{{ $state }}"{{ $state == old('state') ? ' selected' : '' }}>{{ $state }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Postcode</label>
					<div class="col-md-4"><input type="text" name="postcode" value="{{ old('postcode') }}" class="form-control"></div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Country</label>
					<div class="col-md-4"><input type="text" name="country" value="{{ old('country') }}" class="form-control"></div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">ABN</label>
					<div class="col-md-4"><input type="text" name="abn" value="{{ old('abn') }}" class="form-control"></div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Email</label>
					<div class="col-md-4"><input type="text" name="email" value="{{ old('email') }}" class="form-control"></div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Space</label>
					<div class="col-md-4">
						<select name="space" class="form-control">
							<option value=""></option>
							@foreach ($spaces as $space)
							<option {{old('space') == $space->id ? 'selected' : ''}} value="{{$space->id}}">{{$space->name}}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Customer Start Date</label>
					<div class="col-md-4">
					<input type="text" placeholder="DD/MM/YYYY" name="start_date" class="start_date form-control" value="{{  old('start_date', \Carbon\Carbon::today(Auth::user()->timezone)->format('d/m/Y')) }}">
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-offset-2 col-md-4">
						<button type="submit" class="btn btn-primary">Create Account</button>
						<a href="/admin/accounts" class="btn btn-default">Cancel</a>
						{!! csrf_field() !!}
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
@endsection

@section('scripts')
	<script>
		$(document).ready(function(){
			$('.start_date').datetimepicker({
				format : 'DD/MM/YYYY',
				useCurrent : false,
			});
		});
	</script>
@endsection
