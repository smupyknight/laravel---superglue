<!doctype html>
<html>
<head>
	@include('includes.head')
</head>
<body>
<meta name="_token" content="{!! csrf_token() !!}"/>
<div id="wrapper">
	<nav class="navbar-default navbar-static-side" role="navigation">
		<div class="sidebar-collapse">
			<ul class="nav metismenu" id="side-menu">
				<li class="nav-header">
					<div class="dropdown profile-element">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</strong>
							 </span> <span class="text-muted text-xs block">Settings<b class="caret"></b></span> </span> </a>
							<ul class="dropdown-menu animated fadeInRight m-t-xs">
								<li><a href="/account/edit">Edit Account</a></li>
								<li><a href="/logout">Logout</a></li>
							</ul>
					</div>
					<div class="logo-element">
						SG
					</div>
				</li>
				@if (Auth::user()->isAdmin())
					<li class="{{ Request::is('admin/dashboard*') ? 'active' : '' }}"><a href="/admin"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a></li>
					<li class="{{ Request::is('admin/reports*') ? 'active' : '' }}"><a href="/admin/reports"><i class="fa fa-th-large"></i> <span class="nav-label">Reports</span></a></li>
					<li class="{{ Request::is('admin/users*') ? 'active' : '' }}"><a href="/admin/users/"><i class="fa fa-th-large"></i> <span class="nav-label">Users</span></a></li>
					<li class="{{ Request::is('admin/accounts*') ? 'active' : '' }}"><a href="/admin/accounts"><i class="fa fa-th-large"></i> <span class="nav-label">Accounts</span></a></li>
					<li class="{{ Request::is('admin/invoices*') ? 'active' : '' }}"><a href="/admin/invoices"><i class="fa fa-th-large"></i> <span class="nav-label">Invoices</span></a></li>
					<li class="{{ Request::is('admin/plans*') ? 'active' : '' }}"><a href="/admin/plans"><i class="fa fa-th-large"></i> <span class="nav-label">Plans</span></a></li>
					<li class="{{ Request::is('admin/communities*') ? 'active' : '' }}"><a href="/admin/communities"><i class="fa fa-th-large"></i> <span class="nav-label">Communities</span></a></li>
					<li class="{{ Request::is('admin/spaces*') ? 'active' : '' }}"><a href="/admin/spaces"><i class="fa fa-th-large"></i> <span class="nav-label">Spaces</span></a></li>
					<li class="{{ Request::is('admin/events*') ? 'active' : '' }}"><a href="/admin/events"><i class="fa fa-th-large"></i> <span class="nav-label">Events</span></a></li>
					<li class="{{ Request::is('admin/powerups*') ? 'active' : '' }}"><a href="/admin/powerups"><i class="fa fa-th-large"></i> <span class="nav-label">Powerups</span></a></li>
					<li class="{{ Request::is('admin/event-requests*') ? 'active' : '' }}"><a href="/admin/event-requests"><i class="fa fa-th-large"></i> <span class="nav-label">Event Requests</span></a></li>
					{{-- <li class="{{ Request::is('admin/mentors-schedule*') ? 'active' : '' }}"><a href="/admin/mentors-schedule"><i class="fa fa-th-large"></i> <span class="nav-label">Mentor Schedule</span></a></li> --}}
					{{-- <li class="{{ Request::is('admin/mentor-requests*') ? 'active' : '' }}"><a href="/admin/mentor-requests"><i class="fa fa-th-large"></i> <span class="nav-label">Mentor Requests</span></a></li> --}}
					<li class="{{ Request::is('admin/announcements*') ? 'active' : '' }}"><a href="/admin/announcements"><i class="fa fa-th-large"></i> <span class="nav-label">Announcements</span></a></li>
					<li class="{{ Request::is('admin/refer-friends*') ? 'active' : '' }}"><a href="/admin/refer-friends"><i class="fa fa-th-large"></i> <span class="nav-label">Friend Referrals</span></a></li>
				@else
					<li class="{{ Request::is('/') ? 'active' : '' }}"><a href="/"><i class="fa fa-th-large"></i> <span class="nav-label">Home</span></a></li>
					<li class="{{ Request::is('events*') ? 'active' : '' }}"><a href="/events"><i class="fa fa-calendar"></i> <span class="nav-label">Events</span></a></li>
					<li class="{{ Request::is('members*') ? 'active' : '' }}"><a href="/members"><i class="fa fa-group"></i> <span class="nav-label">Members</span></a></li>
					<li class="{{ Request::is('powerups*') ? 'active' : '' }}"><a href="/powerups"><i class="fa fa-group"></i> <span class="nav-label">Powerups</span></a></li>
					{{-- <li class="{{ Request::is('mentors*') ? 'active' : '' }}"><a href="/mentors"><i class="fa fa-group"></i> <span class="nav-label">Mentors</span></a></li> --}}
					@if (Auth::user()->isMentor())
						{{-- <li class="{{ Request::is('work-history*') ? 'active' : '' }}"><a href="/work-history"><i class="fa fa-graduation-cap"></i> <span class="nav-label">Work Histroy</span></a></li> --}}
					@endif
					@if (Auth::user()->isAccountAdmin())
						<li class="{{ Request::is('account*') ? 'active' : '' }}">
							<a href="#"><i class="fa fa-gears"></i> <span class="nav-label">My Account</span> <span class="fa arrow"></span></a>
							<ul class="nav nav-second-level">
								<li class="{{ Request::is('account/overview*') ? 'active' : '' }}"><a href="/account/overview">Overview</a></li>
								<li class="{{ Request::is('account/invoices*') ? 'active' : '' }}"><a href="/account/invoices">My Invoices</a></li>

								{{-- <li class="{{ Request::is('account/bookings*') ? 'active' : '' }}"><a href="/account/bookings">My Bookings</a></li> --}}
							</ul>
						</li>
					@endif
				@endif
				{{-- <li class="{{ Request::is('bookings*') ? 'active' : '' }}"><a href="/bookings"><i class="fa fa-university"></i> <span class="nav-label">Book a Room</span></a></li> --}}
			</ul>

		</div>
	</nav>

	<div id="page-wrapper" class="gray-bg">
		<div class="row border-bottom">
			<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
				<div class="navbar-header">
					<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
					<form role="search" class="navbar-form-custom" method="post" action="#">
						<div class="form-group">
							<input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
						</div>
					</form>
				</div>
				<ul class="nav navbar-top-links navbar-right">
					<li>
						<a href="/logout">
							<i class="fa fa-sign-out"></i> Log out
						</a>
					</li>
				</ul>

			</nav>
		</div>

		@yield('content')


	<footer class="row">
		<div class="footer">

			<div>
				<strong>Copyright</strong> Superglue &copy; {{ date("Y") }}
			</div>
		</div>
	</footer>
</div>

<!-- Mainly scripts -->
<script src="/js/jquery-2.1.1.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/js/moment.min.js"></script>
<script src="/js/datetimepicker.js"></script>

<!-- jQuery UI custom -->
<script src="/js/jquery-ui.custom.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>
<script src="/js/plugins/pace/pace.min.js"></script>
<script src="/js/bootbox.min.js"></script>

@yield('scripts')
</body>
</html>
