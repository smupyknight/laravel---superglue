@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="ibox">
			<div class="ibox-title">
				<h5>{{ $title or '' }}</h5>

				<div class="ibox-tools">
					<a href="/admin/announcements/edit/{{ $announcement->id }}" class="btn btn-default btn-xs">Edit Announcement</a>
				</div>
			</div>
			<div class="ibox-content">
				<div class="form-horizontal">

					<div class="form-group">
						<label class="col-lg-2 control-label">Title :</label>
						<div class="col-lg-4">
							<p class="form-control-static">{{ $announcement->title }}</p>
						</div>
					</div>

					<div class="form-group">
						<label class="col-lg-2 control-label">Content :</label>
						<div class="col-lg-4">
							<div class="well">
								{!! $announcement->content !!}
							</div>
						</div>
						<label class="col-lg-2 control-label">Link :</label>
						<div class="col-lg-4">
							<p class="form-control-static"> {!! $announcement->content !!}</p>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
@endsection
