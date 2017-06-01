<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EventRequest;
use DateTime;
use Auth;
use App\Http\Requests;

class EventRequestsController extends Controller
{

	 /**
	 * Show Event Request listing page
	 * @return view
	 */
	public function getIndex()
	{
		$event_requests = EventRequest::orderBy('id', 'desc')->paginate(25);

		return view('pages.event-requests-list')
				->with('event_requests', $event_requests)
				->with('title', 'Event Requests List');
	}

}
