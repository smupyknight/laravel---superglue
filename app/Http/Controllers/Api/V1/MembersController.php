<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\EventAttendee;
use App\Exceptions\ServiceValidationException;
use App\HighFive;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\MentorBooking;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;

class MembersController extends Controller
{

	/**
	 * Get list of members
	 * @return json
	 */
	public function getIndex()
	{
		$users = User::where('type', 'member')->paginate(10);

		$data = [];

		foreach ($users as $user) {
			$data[] = [
				'id'           => $user->id,
				'salutation'   => $user->salutation,
				'first_name'   => $user->first_name,
				'last_name'    => $user->last_name,
				'avatar'       => $user->getAvatarUrl(),
				'company_name' => $user->company_name,
				'job_title'    => $user->job_title,
				'is_public'    => $user->is_public
			];
		}

		$results = [
			'total'         => $users->total(),
			'per_page'      => $users->perPage(),
			'current_page'  => $users->currentPage(),
			'last_page'     => $users->lastPage(),
			'next_page_url' => $users->nextPageUrl(),
			'prev_page_url' => $users->previousPageUrl(),
			'from'          => $users->firstItem(),
			'to'            => $users->lastItem(),
			'data'          => $data
		];

		return response()->json($results);
	}

	/**
	 * Get member profile
	 * @param  int $user_id
	 * @return json
	 */
	public function getProfile($user_id)
	{
		$user = User::whereType('Member')->findOrFail($user_id);

		$result = [
			'id'               => $user->id,
			'salutation'       => $user->salutation,
			'first_name'       => $user->first_name,
			'last_name'        => $user->last_name,
			'twitter_handle'   => $user->twitter_handle,
			'instagram_handle' => $user->instagram_handle,
			'email'            => $user->email,
			'phone'            => $user->phone,
			'avatar'           => $user->getAvatarUrl(),
			'company_name'     => $user->company_name,
			'job_title'        => $user->job_title,
			'is_public'        => $user->is_public,
			'num_high_fives'   => $user->num_high_fives
		];

		return response()->json($result);
	}

	public function postHighFive($user_id)
	{
		$receiver = User::findOrFail($user_id);
		$sender = $this->user;

		$exists = HighFive::where('user_id', $receiver->id)
		                  ->where('created_by', $sender->id)
		                  ->where('created_at', '>=', (new Carbon('today', $sender->timezone))->setTimezone('UTC'))
		                  ->exists();

		if ($exists) {
			throw new ServiceValidationException('High five already sent for today.');
		}

		$high_five = new HighFive;
		$high_five->user_id = $receiver->id;
		$high_five->created_by = $sender->id;
		$high_five->save();

		$receiver->num_high_fives++;
		$receiver->save();

		return response()->json(['num_high_fives' => $receiver->num_high_fives]);
	}

	public function getSearch(Request $request)
	{
		$query = User::where('type', 'member');

		foreach (explode(' ', $request->name) as $words) {
			$query->where(function($query) use ($words) {
				$query->where('first_name', 'like', '%'.$words.'%');
				$query->orWhere('last_name', 'like', '%'.$words.'%');
			});
		}

		$users = $query->paginate(10);

		$results = [];

		foreach ($users as $user) {
			$results[] = [
				'id'               => $user->id,
				'salutation'       => $user->salutation,
				'first_name'       => $user->first_name,
				'last_name'        => $user->last_name,
				'avatar'           => $user->getAvatarUrl(),
				'company_name'     => $user->company_name,
				'job_title'        => $user->job_title,
				'is_public'        => $user->is_public,
			];
		}

		return response()->json([
			'total'         => $users->total(),
			'per_page'      => $users->perPage(),
			'current_page'  => $users->currentPage(),
			'last_page'     => $users->lastPage(),
			'next_page_url' => $users->nextPageUrl(),
			'prev_page_url' => $users->previousPageUrl(),
			'from'          => $users->firstItem(),
			'to'            => $users->lastItem(),
			'data'          => $results,
		]);
	}

	/**
	 * Get list of mentors
	 * @return json
	 */
	public function getEvents(Request $request)
	{
		$this->validate($request, [
			'start_date' => 'date_format:' . Carbon::ISO8601,
			'end_date'   => 'date_format:' . Carbon::ISO8601,
		]);

		$user = Auth::guard('api')->user();

		$query = DB::table('events as e')
		           ->join('event_attendees as a', 'a.event_id', '=', 'e.id')
		           ->selectRaw('e.*,a.status');

		$query = $query->where('a.user_id', $user->id);

		if ($request->start_date) {
			$query->where('e.start_time', '>=', (new Carbon($request->start_date))->setTimezone('UTC'));
		}

		if ($request->end_date) {
			$query->where('e.end_time', '<=', (new Carbon($request->end_date))->setTimezone('UTC'));
		}

		$event_attendees = $query->get();

		$result = [];

		foreach ($event_attendees as $event_attendee) {
			$result[] = [
				'event_id'         => $event_attendee->id,
				'name'             => $event_attendee->name,
				'description'      => $event_attendee->description,
				'location'         => $event_attendee->space_id ? $event_attendee->space->name.' '.$event_attendee->space->address.' '.$event_attendee->space->suburb : $event_attendee->location_other,
				'start_time'       => (new Carbon($event_attendee->start_time))->format('c'),
				'attending_status' => $event_attendee->status
			];
		}

		return response()->json($result);
	}

	/**
	 * Get list of bookings done by current logged In member
	 * @return json
	 */
	public function getMentorBookings(Request $request)
	{
		$this->validate($request, [
			'start_date' => 'date_format:' . Carbon::ISO8601,
			'end_date'   => 'date_format:' . Carbon::ISO8601,
		]);

		$user = Auth::guard('api')->user();

		$query = MentorBooking::where('member_id', $user->id);

		if ($request->start_date) {
			$query->where('start_date', '>=', (new Carbon($request->start_date))->setTimezone('UTC'));
		}

		if ($request->end_date) {
			$query->where('end_date', '<=', (new Carbon($request->end_date))->setTimezone('UTC'));
		}

		$mentor_bookings = $query->get();

		$result = [];

		foreach ($mentor_bookings as $mentor_booking) {
			$result[] = [
				'booking_id'  => $mentor_booking->id,
				'mentor_id'   => $mentor_booking->mentor_id,
				'mentor_name' => $mentor_booking->mentor->first_name .' '. $mentor_booking->mentor->last_name,
				'location'    => $mentor_booking->schedule->space->name,
				'start_date'  => (new Carbon($mentor_booking->start_date))->format('c'),
				'end_date'    => (new Carbon($mentor_booking->end_date))->format('c'),
			];
		}

		return response()->json($result);
	}

}
