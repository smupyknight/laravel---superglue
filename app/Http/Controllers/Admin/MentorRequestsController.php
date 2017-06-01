<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\MentorRequest;
use DateTime;
use Auth;
use App\Http\Requests;

class MentorRequestsController extends Controller
{

	 /**
	 * Show Event Request listing page
	 * @return view
	 */
	public function getIndex()
	{
		$mentor_requests = MentorRequest::orderBy('id', 'desc')->paginate(25);

		return view('pages.mentor-requests-list')
		     ->with('mentor_requests', $mentor_requests)
		     ->with('title', 'Mentor Requests List');
	}

}
