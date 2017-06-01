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

						<form class="form-horizontal" method="post" action="/admin/announcements/edit/{{ $announcement->id }}">
							{{ csrf_field() }}
							<div class="form-group">
								<label class="col-lg-2 control-label">Title</label>
								<div class="col-lg-10">
									<textarea  placeholder="Title" name="title" class="form-control" >{{ old('title',$announcement->title) }}</textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Content</label>
								<div class="col-lg-10">
									<div class="summernote">
										{!! old('content', $announcement->content) !!}
									</div>
									<input type="hidden" name="content" value="{{ old('content', $announcement->content) }}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Link</label>
								<div class="col-lg-10">
									<input type="text" placeholder="Link" name="link" class="form-control" value="{{ old('link',$announcement->link) }}">
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
			$('.summernote').summernote({
				height: 300,
				callbacks: {
					onBlur: function() {
						// Update value of hidden input
						$('[name="content"]').val($('.summernote').summernote('code'));
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
