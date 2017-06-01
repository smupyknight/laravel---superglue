@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
				<form method="GET" action="/members">
					<div class="col-sm-9">
						<div class="input-group">
							<input type="text" placeholder="Search Members" class="input form-control" name="global_search" value="{{Request::get('global_search')}}">
							<span class="input-group-btn">
								<button type="submit" class="btn btn btn-primary "> <i class="fa fa-search"></i> Search</button>
							</span>
						</div>
					</div>
					<div class="col-sm-3">
						<select class="form-control m-b" name="industry" onchange="this.form.submit()">
							@foreach($industries as $industry)
								<option {{ Request::get('industry') == $industry ? 'selected' : '' }}>{{ $industry }}</option>
							@endforeach
						</select>
					</div>
				</form>
			</div>
			<hr>
			<div class="row">
				@if(count($members))
					@foreach($members as $member)
						<div class="col-lg-4">
							<div class="contact-box ">
								<a href="">
								<div class="col-sm-4">
									<div class="text-center ">
										<img alt="image" class="img-circle m-t-xs img-responsive" width="150px" src="{{ $member->avatar !== '' ? $member->getAvatarUrl() : 'http://placehold.it/100x100' }}">
										<div class="m-t-xs font-bold ">{{ $member->job_title or '' }}</div>
									</div>
								</div>
								</a>
								<div class="col-sm-8">
									<h3><strong>{{ $member->first_name . ' ' . $member->last_name }}</strong></h3>
									<p><i class="fa fa-map-marker"></i> {{ $member->account->space->name }}</p>
									<address>
										<strong>{{ $member->company_name }}</strong><br>
										Industry: {{ $member->industry }}<br>
										<i class="fa fa-twitter"></i> {!! $member->twitter_handle != '' ? '<a href="https://twitter.com/' . $member->twitter_handle . '" >' . $member->twitter_handle . '</a>' : ' ' !!}<br>
									</address>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					@endforeach
				@else
				<p>No members found.</p>
				@endif
			</div>
			<div class="row text-center">
				{!! $members->render() !!}
			</div>
		</div>
@endsection