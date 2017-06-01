<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Event;
use App\EventAttendee;
use App\EventRequest;
use DateTime;
use DB;
use Auth;

class EventsController extends Controller
{

	/**
	 * Get All events
	 * @param  int $feed_id
	 * @return json
	 */
	public function getIndex(Request $request)
	{
		$query = Event::where('status', 'Published');

		if ($request->start_date) {
			$query->where('start_time', '>=', $request->start_date);
		}

		if ($request->end_date) {
			$query->where('finish_time', '<=', $request->end_date);
		}

		$events = $query->orderBy('id', 'desc')->paginate(25);

		$data = [];

		foreach ($events as $event) {
			$data[] = [
				'event_id'    => $event->id,
				'name'        => $event->name,
				'description' => $event->description,
				'location'    => $event->space_id ? $event->space->name.' '.$event->space->address.' '.$event->space->suburb : $event->location_other,
				'type'        => $event->paid == 1 ? 'Paid' : 'Free',
				'cover_photo' => $event->getCoverImageUrl(),
				'start_time'  => $event->start_time,
			];
		}

		$results = [
			'total'         => $events->total(),
			'per_page'      => $events->perPage(),
			'current_page'  => $events->currentPage(),
			'last_page'     => $events->lastPage(),
			'next_page_url' => $events->nextPageUrl(),
			'prev_page_url' => $events->previousPageUrl(),
			'from'          => $events->firstItem(),
			'to'            => $events->lastItem(),
			'data'          => $data
		];

		return response()->json($results);
	}

	/**
	 * Get event details
	 * @return json
	 */
	public function getDetail($event_id)
	{
		$event = Event::whereStatus('Published')->findOrFail($event_id);

		$result = [
			'event_id'                => $event->id,
			'name'                    => $event->name,
			'description'             => $event->description,
			'location'                => $event->space_id ? $event->space->name.' '.$event->space->address.' '.$event->space->suburb : $event->location_other,
			'type'                    => $event->paid == 1 ? 'Paid' : 'Free',
			'cover_photo'             => $event->getCoverImageUrl(),
			'start_time'              => $event->start_time,
			'current_user_attendance' => EventAttendee::where('user_id', Auth::guard('api')->user()->id)->where('event_id', $event_id)->value('status'),
			'attendees'               => DB::table('event_attendees')->groupBy('status')->lists(DB::raw('COUNT(*)'), 'status')
		];

		return response()->json($result);
	}

	/**
	 * Create or update a users attendance status
	 * @return json
	 */
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

	/**
	 * Event request from a user
	 * @return json
	 */
	public function getRequestEvent(Request $request)
	{
		$this->validate($request, [
			'content' => 'required'
		]);

		$event_request = new EventRequest;
		$event_request->user_id = Auth::guard('api')->user()->id;
		$event_request->content = $request->get('content', '');
		$event_request->save();

		return response()->json([
			'message' => 'Requested added successfully.',
		]);
	}

}
