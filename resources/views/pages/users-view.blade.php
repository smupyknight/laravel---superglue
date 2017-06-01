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
		<div class="col-md-3">
			<table class="table small m-b-xs">
				<tbody>
				<tr>
					<td>
					</td>
					<td>
						<strong>Account:</strong> {{ $user->account->id or '' }}
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td>
						<strong>Security Card Number:</strong> {{ $user->security_card_number or '' }}
					</td>
				</tr>
				<tr>
					<td>
					</td>

				</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-3">
			<small>Last Login</small><br>
			<p class="no-margins"> {{ $user->last_login_at ? $user->last_login_at->setTimezone(Auth::user()->timezone)->format('H:i d/m/Y') : '' }}</p>
			<p>({{ $user->last_login_at ? $user->last_login_at->diffForHumans() : ''}})</p>
		</div>


	</div>
	<div class="row">

		<div class="col-lg-3">

			<div class="ibox">
				<div class="ibox-content">
						<h3>About <b>{{ $user->first_name }}</b></h3>

					<p class="small">
						{{ $user->bio }}
					</p>
				</div>
			</div>

			<div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Bookings</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="feed-activity-list">
                    	@if($user->bookings()->count())
							@foreach($user->bookings as $booking)
		                        <div class="feed-element">
		                            <div>
		                                <small class="pull-right text-navy">{{$booking->start_date->diffForHumans()}}</small>
		                                <strong>{{ $user->first_name . ' ' . $user->last_name }}</strong>
										@foreach($booking->rooms as $room)
		                                	<div>{{$booking->name}}- {{$room->name}} -{{$booking->start_date->diffInHours($booking->end_date) }} hour</div>
										@endforeach
		                                <small class="text-muted">{{$booking->created_at->format('h:i a - d.m.Y')}}</small>
		                                <div class="actions">
		                                    <a class="btn btn-xs btn-white"><i class="fa fa-cog"></i> Edit </a>
		                                </div>
		                            </div>
		                        </div>
							@endforeach
						@else
							<p>No bookings found</p>
						@endif
                    </div>
                </div>
            </div>
		</div>

		<div class="col-lg-9 m-b-lg">
			<div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>User Timeline</h5>
                    <div class="ibox-tools">
                        <span class="label label-warning-light pull-right">{{count($user->timeline)}} Items</span>
                       </div>
                </div>
                <div class="ibox-content">

                    <div>
                        <div id="timeline-feeds-container" class="feed-activity-list">
							@if (count($user->timeline))
								@foreach($user->timeline()->limit(10)->offset(0)->get() as $timeline)
									<div class="feed-element">
									    <div class="media-body ">
									       <small class="pull-right text-navy">{{ $timeline->created_at->diffForHumans() }}</small>
									        {{ $timeline->author ? $timeline->author->first_name . ' ' . $timeline->author->last_name : 'System' }}: <strong> {{ $timeline->title }}</strong>. <br>
									        <small class="text-muted">{{ $timeline->created_at->setTimezone(Auth::user()->timezone)->format('h:i a - d.m.Y') }}</small>
								      		@if (strlen($timeline->message))
										        <div class="well">
										        	{{ $timeline->message }}
										        </div>
							                @endif
									    </div>
									</div>
								@endforeach
							@endif
                        </div>

                        <button class="btn btn-primary btn-block m-t load-more-timeline-feeds {{count($user->timeline) <= 10? 'hidden': ''}}"><i class="fa fa-arrow-down"></i> Show More</button>

                    </div>

                </div>
            </div>

		</div>

	</div>

</div>
@endsection

@section('scripts')
	<script>
		var $offset = 10;
		$(function() {
			$('.load-more-timeline-feeds').on('click', function(event) {
				event.preventDefault();
				var request = {_token:"{{csrf_token()}}", user_id: "{{$user->id}}", limit: 10 , offset: $offset}
				$.ajax({
					type: "get",
					url: "/admin/users/load-timeline",
					data: request,
				}).done(function(response) {
					if (response.status == 0) {
						$('.load-more-timeline-feeds').addClass('hidden');
					}else {
						timeLineHtml(response.feeds);
						$offset += 10;
					}
				});
			});
		});

		function timeLineHtml(feeds) {
			var html = '';
			$.each(feeds, function(i, feed) {
				html += '<div class="feed-element">'+
					'<div class="media-body ">'+
						'<small class="pull-right text-navy">'+ feed.age +'</small>'+
						 feed.title +' <strong>'+ feed.author +'.</strong>. <br>'+
						'<small class="text-muted">'+ feed.created_at +'</small>';
				if (feed.message.length) {
					html += '<div class="well">'+ feed.message +'</div>';
				}
				html += '</div> </div>';
				$("#timeline-feeds-container").append(html);
			})
		}
	</script>
@endsection