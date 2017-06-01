@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">

			<div class="col-lg-6 col-lg-offset-3">
				<div class="ibox">
					<div class="ibox-content text-center">
						<h3 class="m-b-xxs">Announcements</h3>
						<small>What's hip-hop-happinin'</small>
					</div>
				</div>

				@if ($announcements->count())
					@foreach ($announcements as $announcement)
						<div class="social-feed-box">
							<div class="social-avatar">
								<a href="" class="pull-left">
									@if ($announcement->user->avatar !== '')
										<img alt="image" src="{{ $announcement->user->getAvatarUrl() }}">
									@endif
								</a>
								<div class="media-body">
									<p class="title">{{ $announcement->title }}</p>
									<small class="text-muted">{{ $announcement->created_at->toDayDateTimeString() . ' (' . $announcement->created_at->diffForHumans() .')' }}</small>
								</div>
							</div>
							<div class="social-body">
								{!! $announcement->content !!}

								<div class="btn-group">
									@if ($announcement->link != '')
										<a href="{{ $announcement->link }}"><button class="btn btn-white btn-xs"><i class="fa fa-link"></i> Learn More</button></a>
									@endif
									{{-- <button class="btn btn-white btn-xs"><i class="fa fa-comments"></i> Comment</button>
									<button class="btn btn-white btn-xs"><i class="fa fa-share"></i> Share</button> --}}
								</div>
							</div>
						</div>
					@endforeach

					<div class="text-center">
						{{ $announcements->links() }}
					</div>
				@else
					<p>No Announcements</p>
				@endif
			</div>
		</div>
	</div>
	<div class="modal inmodal" id="set_billing_details_modal" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content animated fadeIn">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<i class="fa fa-clock-o modal-icon"></i>
					<h4 class="modal-title">Credit Card Missing</h4>
					<small>Please update your payment details</small>
				</div>
				<div class="modal-body">
					<p>It looks like your account is missing payment details. Please update your payment details to avoid any disruption to your membership.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
					<a href="/account/overview" class="btn btn-primary">Update Details</a>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	@if (Auth::user()->isAccountAdmin() && !Auth::user()->account->stripe_id && Auth::user()->account->billingItems->count())
		<script type="text/javascript">
			$(document).ready(function() {
				$('#set_billing_details_modal').modal('show')
			});
		</script>
	@endif
@endsection
