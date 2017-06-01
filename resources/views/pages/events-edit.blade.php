@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>

					</div>
					<div class="ibox-content">
						@include('partials.errors')

						<form class="form-horizontal" method="post" action="/admin/events/edit/{{ $event->id }}" enctype="multipart/form-data">
							{{ csrf_field() }}
							<div class="form-group">
								<label class="col-lg-2 control-label">Name</label>
								<div class="col-lg-10"><input type="text" placeholder="Event Title" name="name" class="form-control" value="{{ old('name',$event->name) }}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Description</label>
								<div class="col-lg-10">
									<div class="summernote">
										{!! old('description', $event->description) !!}
									</div>
									<input type="hidden" name="description" value="{{ old('description', $event->description) }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Location</label>
								<div class="col-lg-10">
									<select name="space_id" class="form-control event-location">
										<option value="">Select Location</option>
										@foreach ($spaces as $space)
										<option value="{{ $space->id }}" {{ old('space_id', $event->space_id) == $space->id ? 'selected' : '' }}>{{ $space->name.' '.$space->address.' '.$space->suburb }}</option>
										@endforeach
										<option {{ old('space_id', $event->space_id) == null ? 'selected' : '' }}>Other</option>
									</select>
								</div>
								<div class="col-lg-10 col-lg-offset-2 location-other" style="{{ old('space_id', $event->space_id) != null ? 'display:none' : '' }}">
									<input type="text" placeholder="Location" name="location_other" id="location-other" class="form-control" value="{{ old('location_other', $event->location_other) }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Paid</label>
								<div class="col-lg-10">
									<select class='form-control' placeholder='Parent' name="paid">
										<option value="">Please select</option>
										<option value="1" {{ old('paid',$event->paid) ==1 ? 'selected' :'' }}>Yes</option>
										<option value="0" {{ old('paid',$event->paid) ==0 ? 'selected' :'' }}>No</option>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Start Date/Time</label>
								<div class="col-lg-10">
									<input type="text" name="start_time" class="date-input form-control" value="{{ old('start_time', $event->start_time ? $event->start_time->format('d/m/Y H:i:s') : '') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Finish Date/Time</label>
								<div class="col-lg-10">
									<input type="text" name="finish_time" class="date-input form-control" value="{{ old('finish_time', $event->finish_time ? $event->finish_time->format('d/m/Y H:i:s') : '') }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Ticket link</label>
								<div class="col-lg-10"><input type="text" placeholder="Ticket link" name="ticket_link" class="form-control" value="{{ old('ticket_link',$event->ticket_link) }}">
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Status</label>
								<div class="col-lg-10">
									<select name="status" class="form-control">
										<option value="">Select Status</option>
										@foreach (['Draft','Published'] as $status)
											<option {{ old('status',$event->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-lg-2 control-label">Cover Photo</label>
								<div class="col-lg-10">
									<input type="file" name="cover_photo" accept="image/jpeg" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button class="btn btn-sm btn-primary" type="submit">Update</button>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('css')
	<link href="/css/summernote.css" rel="stylesheet">
	<link href="/css/summernote-bs3.css" rel="stylesheet">
@endsection

@section('scripts')
	<script src="/js/summernote.min.js"></script>
	<script>
		$(document).ready(function() {
			$('.event-location').change(function() {
				if ($(this).val() == 'Other') {
					$('.location-other').fadeIn();
					$('#location-other').focus();
				} else {
					$('.location-other').hide();
				}
			});

			$('.summernote').summernote({
				height: 300,
				callbacks: {
					onBlur: function() {
						// Update value of hidden input
						$('[name="description"]').val($('.summernote').summernote('code'));
					}
				}
			});

			$('.date-input').datetimepicker({
				format : 'DD/MM/YYYY H:mm:ss',
				useCurrent : false,
			});
		});
	</script>
@endsection
