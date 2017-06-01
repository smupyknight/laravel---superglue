<?php

namespace App\Http\Controllers;

use App\Exceptions\ServiceValidationException;
use App\Http\Requests;
use App\MentorBooking;
use App\MentorRequest;
use App\MentorSchedule;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class MentorsController extends Controller
{

	public function getIndex(Request $request)
	{
		$query = User::orderBy('id', 'desc');

		$query->where('type', 'Mentor');

		$query->where(function($query) use($request) {
			$query->orWhere('first_name', 'like', '%'.$request->global_search.'%');
			$query->orWhere('last_name', 'like', '%'.$request->global_search.'%');
			$query->orWhere('email', 'like', '%'.$request->global_search.'%');
		});

		$mentors = $query->paginate(25);

		return view('pages.public.mentors-list')
		     ->with('mentors', $mentors)
		     ->with('title', 'Mentors List');
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

		$user = User::where('type', 'Mentor')->findOrFail($request->mentor_id);

		$mentor_request = new MentorRequest;
		$mentor_request->member_id = Auth::user()->id;
		$mentor_request->mentor_id = $user->id;
		$mentor_request->topic = $request->topic;
		$mentor_request->save();

		return response()->json([
				'message' => 'Requested successfully.',
			]);
	}

	public function getCalendar($mentor_id)
	{
		$today = (new Carbon())->subDay();
		$mentor = User::where('type', 'Mentor')->findOrFail($mentor_id);
		$schedules = $mentor->mentorSchedules()
		                    ->where('end_date', '>=', $today)
		                    ->orderBy('start_date')
		                    ->get();

		$array = [];
		foreach ($schedules as $schedule) {
			$timezone = Auth::user()->timezone;

			$array[] = [
				'id'    => $schedule->id,
				'title' => $schedule->space->name,
				'start' => $schedule->start_date->setTimezone($timezone)->format('Y-m-d 00:00:00'),
				'end'   => $schedule->end_date->setTimezone($timezone)->format('Y-m-d 23:59:59'),
				'color' => 'black'
			];
		}

		return response()->json($array);
	}

	public function getAvailability($schedule_id,$date)
	{
		$schedules = MentorSchedule::where('id', $schedule_id)->get();
		$schedules = MentorSchedule::createScheduleInterval($schedules, $schedules[0]->mentor_id, $date);

		if (!$schedules) {
			return response()->json('There are no available schedules for this mentor.');
		}

		$html = '<h4>'.(new Carbon($date))->format('F d, Y').'</h4><hr>';

		foreach ($schedules as $schedule) {
			$timezone = $this->user->timezone;
			$start = (new Carbon($schedule['start_time']))->setTimezone($timezone);

			$book = $schedule['is_booked'] == 1 ? '<a class="btn btn-xs btn-default">Booked</a>' : '<a onclick="book_mentor(\''.$schedule['mentor_id'].'\',\''.$start->format('D, d M Y H:i O').'\');return false;" class="btn btn-xs btn-primary">Book</a>';
			$html .= '
				<div class="row">
					'.$start->format('H:i:s').' - '.$start->addMinutes(30)->format('H:i:s').' <div class="pull-right">'.$book.'</div>
				</div>
				<hr>
			';
		}

		return response()->json($html);
	}

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

		return redirect('/mentors');
	}

}
