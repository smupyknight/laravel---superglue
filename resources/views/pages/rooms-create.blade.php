@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Create Room</h5>
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
						<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Room Name</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Room Name" name="name" value="{{old('name')}}" class="form-control">
									@if ($errors->has('name'))
										<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Description</label>
								<div class="col-lg-10">
									<textarea rows="3" placeholder="Description" name="description" class="form-control">{{old('description')}}</textarea>
									@if ($errors->has('description'))
										<span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
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
						<div class="form-group {{ $errors->has('credits_per_hour') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Credits per Hour</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Credits per Hour" name="credits_per_hour" value="{{old('credits_per_hour')}}" class="form-control">
									@if ($errors->has('credits_per_hour'))
										<span class="help-block"><strong>{{ $errors->first('credits_per_hour') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group">
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
