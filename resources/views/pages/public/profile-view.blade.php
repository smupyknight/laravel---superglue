@extends('layouts.default')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row m-b-lg m-t-lg">
		<div class="col-md-6">

			<div class="profile-image">
				<img src="{{ $user->avatar !== '' ? $user->getAvatarUrl() : 'http://placehold.it/100x100' }}" class="img-circle circle-border m-b-md" alt="profile">
			</div>
			<div class="profile-info">
				<div class="">
					<div>
						<h2 class="no-margins">
							{{ $user->first_name . ' ' . $user->last_name }}
						</h2>
						<h4>{{ $user->job_title or '' }}</h4>
						<p> Company: {{ $user->company_name or '' }}</p>
						<p> Industry: {{ $user->industry or '' }}</p>
						<small>
							{{ $user->bio }}
						</small>
					</div>
				</div>
			</div>
		</div>

	</div>
	<div class="row">

		<div class="col-lg-3">

			<div class="ibox">
				<div class="ibox-content">
						<h3>About <b>{{ Auth::user()->first_name }}</b></h3>

					<p class="small">
						{{ $user->bio }}
					</p>
				</div>
			</div>
		</div>

	</div>

</div>
@endsection
