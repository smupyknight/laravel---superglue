<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\ServiceValidationException;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\MentorBooking;
use App\MentorRequest;
use App\MentorSchedule;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Mail;

class MentorsController extends Controller
{

	/**
	 * Get list of mentors
	 * @return json
	 */
	public function getIndex()
	{
		$users = User::where('type', 'mentor')->paginate(10);

		$data = [];

		foreach ($users as $user) {
			$data[] = [
				'id'               => $user->id,
				'salutation'       => $user->salutation,
				'first_name'       => $user->first_name,
				'last_name'        => $user->last_name,
				'avatar'           => $user->getAvatarUrl(),
				'company_name'     => $user->company_name,
				'job_title'        => $user->job_title,
				'is_public'        => $user->is_public
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
	 * Get mentor profile
	 * @param  int $user_id
	 * @return json
	 */
	public function getProfile($user_id)
	{
		$user = User::whereType('Mentor')->findOrFail($user_id);

		$result = [
			'id'               => $user->id,
			'salutation'       => $user->salutation,
			'first_name'       => $user->first_name,
			'last_name'        => $user->last_name,
			'twitter_handle'   => $user->twitter_handle,
			'instagram_handle' => $user->instagram_handle,
			'email'            => $user->email,
			'phone'            => $user->phone,
			'bio'              => $user->bio,
			'avatar'           => $user->getAvatarUrl(),
			'company_name'     => $user->company_name,
			'job_title'        => $user->job_title,
			'is_public'        => $user->is_public,
			'created_at'       => $user->created_at,
			'work_history'     => $user->WorkHistoryItems
		];

		return response()->json($result);
	}

	/**
	 * Book a mentor
	 * @return json
	 */
	public function postBookMentor(Request $request)
	{
		$this->validate($request, [
			'mentor_id' => 'required',
			'start'     => 'required|date_format:"D, d M Y H:i O"'
		]);

		$start = (new Carbon($request->start))->setTimezone('UTC');
		$end = $start->copy()->addMinutes(30);

		$mentor_schedule = MentorSchedule::where('mentor_id', $request->mentor_id)
		                              ->where('start_date', '<=', $start)
		                              ->where('end_date', '>=', $end)
		                              ->first();

		if (!$mentor_schedule) {
			throw new ServiceValidationException('Sorry, the mentor does not appear to be available on the requested day.', 'start');
		}

		// Check for conflicting rooms/times
		$has_conflict = DB::table('mentor_bookings AS b')
		                  ->where('b.start_date', '<', $end)
		                  ->where('b.end_date', '>', $start)
		                  ->exists();

		if ($has_conflict) {
			throw new ServiceValidationException('Sorry, those times conflict with another booking.', 'start');
		}

		$mentor_booking = new MentorBooking;
		$mentor_booking->mentor_id = $request->mentor_id;
		$mentor_booking->member_id = $this->user->id;
		$mentor_booking->schedule_id = $mentor_schedule->id;
		$mentor_booking->start_date = $start;
		$mentor_booking->end_date = $end;
		$mentor_booking->save();

		return response()->json(['booking_id' => $mentor_booking->id]);
	}

	/**
	 * Get mentor availability
	 * @param  int $mentor_id
	 * @return json
	 */
	public function getAvailability($mentor_id)
	{
		$today = (new Carbon())->subDay();
		$mentor = User::where('type', 'Mentor')->findOrFail($mentor_id);

		$mentor_schedules = $mentor->mentorSchedules()
		                           ->where('end_date', '>=', $today)
		                           ->orderBy('start_date')
		                           ->get();

		$array = MentorSchedule::createScheduleInterval($mentor_schedules, $mentor_id);

		return response()->json($array);
	}

	/**
	 * Book a mentor
	 * @return json
	 */
	public function postRequestMentor(Request $request)
	{
		$this->validate($request, [
			'mentor_id' => 'required',
			'topic'     => 'required',
		]);

		$mentor = User::where('type', 'Mentor')->findOrFail($request->mentor_id);

		$mentor_request = new MentorRequest;
		$mentor_request->member_id = Auth::guard('api')->user()->id;
		$mentor_request->mentor_id = $mentor->id;
		$mentor_request->topic = $request->topic;
		$mentor_request->save();

		$data = [
			'member' => Auth::guard('api')->user(),
			'topic'  => $request->topic
		];

		$this->sendRequestEmail($mentor, $data);

		return response()->json([
			'message' => 'Requested successfully.',
		]);
	}

	public function sendRequestEmail($user,$data)
	{
		try {
			Mail::send('emails.mentor-request-email', $data, function ($mail) use ($user) {
				$mail->from(env('MAIL_FROM'));
				$mail->to($user->email);
				$mail->subject('Request from user');
			});
		} catch (Exception $e) {
			Log::info('Sending request email error : '.$e->getMessage());
		}
	}

	public function getSearch(Request $request)
	{
		$query = User::where('type', 'mentor');

		foreach (explode(' ', $request->name) as $words) {
			$users->where(function($query) use ($words) {
				$users->orWhere('first_name', 'like', '%'.$words.'%');
				$users->orWhere('last_name', 'like', '%'.$words.'%');
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
	 * Get list of mentor bookings
	 * @return json
	 */
	public function getBookings(Request $request)
	{
		$this->validate($request, [
			'start_date' => 'date_format:' . Carbon::ISO8601,
			'end_date'   => 'date_format:' . Carbon::ISO8601,
		]);

		$user = Auth::guard('api')->user();

		if (!$user->isMentor()) {
			abort('403');
		}

		$query = MentorBooking::where('mentor_id', $user->id);

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
				'member_id'   => $mentor_booking->member_id,
				'member_name' => $mentor_booking->member->first_name . ' ' . $mentor_booking->member->last_name,
				'location'    => $mentor_booking->schedule->space->name,
				'start_date'  => (new Carbon($mentor_booking->start_date))->format('c'),
				'end_date'    => (new Carbon($mentor_booking->end_date))->format('c'),
			];
		}

		return response()->json($result);
	}

	/**
	 * Get list of mentor schedules
	 * @return json
	 */
	public function getSchedule(Request $request)
	{
		$this->validate($request, [
			'start_date' => 'date_format:' . Carbon::ISO8601,
			'end_date'   => 'date_format:' . Carbon::ISO8601,
		]);

		$user = Auth::guard('api')->user();

		if (!$user->isMentor()) {
			abort('403');
		}

		$query = MentorSchedule::where('mentor_id', $user->id);

		if ($request->start_date) {
			$query->where('start_date', '>=', (new Carbon($request->start_date))->setTimezone('UTC'));
		}

		if ($request->end_date) {
			$query->where('end_date', '<=', (new Carbon($request->end_date))->setTimezone('UTC'));
		}

		$mentor_schedules = $query->get();

		$result = [];

		foreach ($mentor_schedules as $mentor_schedule) {
			$result[] = [
				'schedule_id' => $mentor_schedule->id,
				'space_id'    => $mentor_schedule->space_id,
				'space_name'  => $mentor_schedule->space->name,
				'start_date'  => (new Carbon($mentor_schedule->start_date))->format('c'),
				'end_date'    => (new Carbon($mentor_schedule->end_date))->format('c'),
			];
		}

		return response()->json($result);
	}

}
