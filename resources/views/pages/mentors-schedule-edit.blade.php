@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Edit Schedule</h5>
					<div class="ibox-tools">

					</div>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal" method="post" action="">
						{{csrf_field()}}
						<div class="form-group {{ $errors->has('mentor_id') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Mentor</label>
							<div class="col-lg-10">
								<select name="mentor_id" id="mentor_id" class="form-control">
									<option value="">Select Mentor</option>
									@foreach ($mentors as $mentor)
									<option value="{{ $mentor->id }}" {{old('mentor_id', $mentor_schedule->mentor_id) == $mentor->id ? 'selected' : ''}}>{{ $mentor->first_name . ' ' . $mentor->last_name }}</option>
									@endforeach
								</select>
								@if ($errors->has('mentor_id'))
									<span class="help-block"><strong>{{ $errors->first('mentor_id') }}</strong></span>
								@endif
							</div>
						</div>
						<div class="form-group {{ $errors->has('space_id') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Space</label>
							<div class="col-lg-10">
								<select name="space_id" id="space_id" class="form-control">
									<option value="">Select space</option>
									@foreach ($spaces as $space)
									<option value="{{ $space->id }}" {{old('space_id', $mentor_schedule->space_id) == $space->id ? 'selected' : ''}}>{{ $space->name }}</option>
									@endforeach
								</select>
								@if ($errors->has('space_id'))
									<span class="help-block"><strong>{{ $errors->first('space_id') }}</strong></span>
								@endif
							</div>
						</div>
						<div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Date Start</label>
								<div class="col-lg-10">
								<input type="text" placeholder="DD/MM/YYYY 00:00" name="start_date" class="date form-control" value="{{ old('start_date',$mentor_schedule->start_date->setTimezone($mentor_schedule->space->timezone)->format('d/m/Y H:i')) }}">
									@if ($errors->has('start_date'))
										<span class="help-block"><strong>{{ $errors->first('start_date') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group {{ $errors->has('end_date') ? ' has-error' : '' }}">
							<label class="col-lg-2 control-label">Date End</label>
								<div class="col-lg-10">
								<input type="text" placeholder="DD/MM/YYYY 00:00" name="end_date" class="date form-control" value="{{ old('end_date',$mentor_schedule->end_date->setTimezone($mentor_schedule->space->timezone)->format('d/m/Y H:i')) }}">
									@if ($errors->has('end_date'))
										<span class="help-block"><strong>{{ $errors->first('end_date') }}</strong></span>
									@endif
								</div>
						</div>
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button class="btn btn-sm btn-primary" type="submit">Edit</button>
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
			$('.date').datetimepicker({
				format : 'DD/MM/YYYY HH:mm',
				stepping : 30,
				useCurrent : false,
			});
		});
	</script>
@endsection