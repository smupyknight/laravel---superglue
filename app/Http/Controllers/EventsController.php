<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use App\Event;
use App\EventAttendee;
use Auth;

class EventsController extends Controller
{

	public function getIndex()
	{
		$time = Carbon::now()->startOfDay();
		$events = Event::where('status', 'Published')->where('start_time', '>', $time)->paginate(25);

		return view('pages.public.events-list')
		     ->with('events', $events)
		     ->with('title', 'Events List');
	}

	public function getView($event_id)
	{
		$event = Event::findOrFail($event_id);
		$attendee_status = EventAttendee::where('user_id', Auth::user()->id)->where('event_id', $event->id)->first();

		return view('pages.public.events-view')
		     ->with('event', $event)
		     ->with('attendee_status', $attendee_status);
	}

	public function postUpdateAttendance(Request $request, $event_id)
	{
		$this->validate($request, [
			'status' => 'required|in:Attending,Not Attending,Maybe',
		]);

		$attendee = EventAttendee::where('user_id', $this->user->id)->where('event_id', $event_id)->first();

		if (!$attendee) {
			$attendee = new EventAttendee;
			$attendee->user_id = $this->user->id;
			$attendee->event_id = $event_id;
		}

		$attendee->status = $request->status;
		$attendee->save();
	}

}
