<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Public Routes
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');

Route::controller('invitations', 'InvitationsController');
Route::controller('signup', 'SignupController');

Route::get('/access-denied', function() {
	return 'Access Denied';
});

Route::get('/demo', function() {
	return view('pages.frontend.demo');
});

Route::group(['middleware' => ['auth']], function () {
	Route::get('/', 'AnnouncementsController@getIndex');

	Route::controller('bookings', 'BookingsController');
	Route::controller('events', 'EventsController');
	Route::controller('members', 'MembersController');
	Route::controller('mentors', 'MentorsController');
	Route::controller('account', 'AccountController');
	Route::controller('billing-items', 'BillingItemsController');
	Route::controller('invoices', 'InvoicesController');
	Route::controller('powerups', 'PowerUpsController');
	Route::controller('work-history', 'WorkHistoryController');
});

// Admin routes
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth','admin']], function() {
	Route::get('/', function()
	{
		return redirect('/admin/dashboard');
	});
	Route::controller('dashboard', 'DashboardController');
	Route::controller('users', 'UsersController');
	Route::controller('spaces', 'SpacesController');
	Route::controller('offices', 'OfficesController');
	Route::controller('desks', 'DesksController');
	Route::controller('rooms', 'RoomsController');
	Route::controller('companies', 'CompaniesController');
	Route::controller('communities', 'CommunitiesController');
	Route::controller('events', 'EventsController');
	Route::controller('event-requests', 'EventRequestsController');
	Route::controller('mentor-requests', 'MentorRequestsController');
	Route::controller('announcements', 'AnnouncementsController');
	Route::controller('accounts', 'AccountsController');
	Route::controller('plans', 'PlansController');
	Route::controller('holidays', 'HolidaysController');
	Route::controller('notes', 'NotesController');
	Route::controller('payments', 'PaymentsController');
	Route::controller('billing-items', 'BillingItemsController');
	Route::controller('refer-friends', 'ReferFriendsController');
	Route::controller('powerups', 'PowerUpsController');
	Route::controller('mentors-schedule', 'MentorsScheduleController');
	Route::controller('reports', 'ReportsController');
	Route::controller('invoices', 'InvoicesController');
});

// API routes
Route::group(['prefix' => 'api/v1', 'namespace' => 'Api\V1'], function() {
	// Token not required
	Route::controller('auth', 'AuthController');
	Route::controller('resources', 'ResourcesController');

	//Some functions need the token
	Route::controller('users', 'UsersController');

	// Token required
	Route::group(['middleware' => 'auth:api'], function() {
		Route::controller('account', 'AccountController');
		Route::controller('mentors', 'MentorsController');
		Route::controller('members', 'MembersController');
		Route::controller('spaces', 'SpacesController');
		Route::controller('posts', 'PostsController');
		Route::controller('comments', 'CommentsController');
		Route::controller('likes', 'LikesController');

		Route::controller('announcements', 'AnnouncementsController');
		Route::controller('powerups', 'PowerUpsController');

		Route::get('events', 'EventsController@getIndex');
		Route::get('events/request-event', 'EventsController@getRequestEvent');
		Route::get('events/{event_id}', 'EventsController@getDetail');
		Route::post('events/{event_id}/update-attendance', 'EventsController@postUpdateAttendance');

		Route::get('bookings/init', 'BookingsController@getInit');
		Route::get('bookings/calendar', 'BookingsController@getCalendar');
		Route::get('bookings/list', 'BookingsController@getList');
		Route::get('bookings/my', 'BookingsController@getMy');
		Route::post('bookings/create', 'BookingsController@postCreate');
		Route::post('bookings/{booking_id}/edit', 'BookingsController@postEdit');
		Route::post('bookings/{booking_id}/delete', 'BookingsController@postDelete');
		Route::post('mentors-schedule/create', 'MentorsScheduleController@postCreate');
	});
});
