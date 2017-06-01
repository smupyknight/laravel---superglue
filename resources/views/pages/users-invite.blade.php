@extends('layouts.default')
@section('content')
   <div class="wrapper wrapper-content">
	  <div class="row">
		 <div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Invite User</h5>
				</div>
				<div class="ibox-content">
				  <form method="POST" action="/admin/users/invite">
					{!! csrf_field() !!}

					 <fieldset class="form-horizontal">

						<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
						   <label class="col-sm-2 control-label">First Name:</label>
						   <div class="col-sm-10">
								<input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
								@if ($errors->has('first_name'))
									<span class="help-block"><strong>{{ $errors->first('first_name') }}</strong></span>
								@endif
							</div>
						</div>

						<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
						   <label class="col-sm-2 control-label">Last Name:</label>
						   <div class="col-sm-10">
								<input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
								@if ($errors->has('last_name'))
									<span class="help-block"><strong>{{ $errors->first('last_name') }}</strong></span>
								@endif
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-2 control-label">Email:</label>
						   <div class="col-sm-10">
							  <input type="text" class="form-control" name="email" value="{{ old('email') }}">
							  @if ($errors->has('email'))
								 <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
							  @endif
						   </div>
						</div>

						<div class="form-group{{ $errors->has('account_id') ? ' has-error' : '' }}">
						   <label class="col-sm-2 control-label">Account:</label>
						   <div class="col-sm-10">
							  <select class="form-control" name="account_id" id="account_id">
								<option value="">Select account</option>
								@foreach ($accounts as $account)
									<option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
								@endforeach
							  </select>
							  @if ($errors->has('account_id'))
								 <span class="help-block"><strong>{{ $errors->first('account_id') }}</strong></span>
							  @endif
						   </div>
						</div>

						<div class="form-group{{ $errors->has('is_account_admin') ? ' has-error' : '' }}">
							<label class="col-sm-2 control-label">Account Admin</label>
							<div class="col-sm-10">
								<input type="checkbox" name="is_account_admin" class="form-control" value="1">
								@if ($errors->has('is_account_admin'))
								 <span class="help-block"><strong>{{ $errors->first('is_account_admin') }}</strong></span>
								@endif
							</div>
						</div>

						<hr>
						<div class="form-group">
						   <div class="row">
							  <div class="form-group">
								<div class="col-sm-4 col-sm-offset-9">
									<a href="/admin/users" class="btn btn-white cancel-btn" type="button">Cancel</a>
									<button class="btn btn-primary" type="submit">Send Invitation</button>
								</div>
							  </div>
						   </div>
						</div>
					 </fieldset>
				  </form>
			   </div>
			</div>
		 </div>
	  </div>
   </div>
@endsection
