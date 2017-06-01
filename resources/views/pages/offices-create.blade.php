@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Create Office</h5>
					<div class="ibox-tools">

					</div>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal" action="" method="POST">
						{{csrf_field()}}
						<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Office Name</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Office Name" name="name" value="{{old('name')}}" class="form-control">
									@if ($errors->has('name'))
										<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('features') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Features</label>
								<div class="col-lg-10">
									<textarea rows="3" placeholder="Features" name="features" class="form-control">{{old('features','Whiteboard, Mobile Pedestal (Lockable), Rubbish Bin, Desk Lamp, Glass Door, Wooden Door, Natural Light, Chair, Data Point (CAT 6), Power Point, Air Conditioning Vent (Ducted), Air Conditioning Unit (Split System), Window, Blinds, Shelving Unit')}}</textarea>
									@if ($errors->has('features'))
										<span class="help-block"><strong>{{ $errors->first('features') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('length') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Length</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Length" name="length" value="{{old('length')}}" class="form-control">
									@if ($errors->has('length'))
										<span class="help-block"><strong>{{ $errors->first('length') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('width') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Width</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Width" name="width" value="{{old('width')}}" class="form-control">
									@if ($errors->has('width'))
										<span class="help-block"><strong>{{ $errors->first('width') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('capacity') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Capacity</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Capacity" name="capacity" value="{{old('capacity')}}" class="form-control">
									@if ($errors->has('capacity'))
										<span class="help-block"><strong>{{ $errors->first('capacity') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('signup_fee') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">SignUp Fee</label>
								<div class="col-lg-10">
									<input type="text" placeholder="SignUp Fee" name="signup_fee" value="{{old('signup_fee')}}" class="form-control">
									@if ($errors->has('signup_fee'))
										<span class="help-block"><strong>{{ $errors->first('signup_fee') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('cost') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Cost per fortnight</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Cost" name="cost" value="{{old('cost')}}" class="form-control">
									@if ($errors->has('cost'))
										<span class="help-block"><strong>{{ $errors->first('cost') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group">
							<input type="hidden" name="space_id" value="{{$space->id}}">
							<div class="col-lg-offset-2 col-lg-10">
								<a href="/admin/spaces/view/{{$space->id}}" class="btn btn-sm btn-white">Cancel</a>
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
