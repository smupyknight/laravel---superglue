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

					<form class="form-horizontal" method="post" action="/admin/powerups/edit/{{ $powerup->id }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group">
							<label class="col-lg-2 control-label">Title</label>
							<div class="col-lg-10">
								<textarea  placeholder="Powerup Title" name="title" class="form-control" >{{ old('title', $powerup->title) }}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Description</label>
							<div class="col-lg-10">
								<div class="summernote">
									{!! old('description', $powerup->description) !!}
								</div>
								<input type="hidden" name="description" value="{{ old('description', $powerup->description) }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Coupon Code</label>
							<div class="col-lg-10">
								<input type="text" placeholder="Coupon Code" name="coupon_code" class="form-control" value="{{ old('coupon_code', $powerup->coupon_code) }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Link</label>
							<div class="col-lg-10">
								<input type="text" placeholder="Link" name="link" class="form-control" value="{{ old('link', $powerup->link) }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Image</label>
								<div class="col-lg-10">
                                    @if ($powerup->image)
									<img src="{{ asset('storage/powerups/'.$powerup->image) }}" class="img-responsive" style="max-width:250px">
									@endif
									<br>
									<input type="file" name="image">
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

@section('css')
	<link href="/css/summernote.css" rel="stylesheet">
	<link href="/css/summernote-bs3.css" rel="stylesheet">
@endsection

@section('scripts')
	<script src="/js/summernote.min.js"></script>
	<script>
		$(document).ready(function() {
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