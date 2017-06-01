@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>{{ $title or '' }}</h5>
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
					<form class="form-horizontal" method="POST" action="">
						{{csrf_field()}}
						<div class="form-group {{ $errors->has('community_name') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Community Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Community Name" name="community_name" class="form-control" value="{{old('community_name')}}">
									@if ($errors->has('community_name'))
										<span class="help-block"><strong>{{ $errors->first('community_name') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Admin Email</label>
								<div class="col-lg-10"><input type="text" placeholder="Email of user who will have administrator rights" name="admin_email" class="form-control">
								</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<a href="/communities" class="btn btn-sm btn-white" type="submit">Back</a>
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