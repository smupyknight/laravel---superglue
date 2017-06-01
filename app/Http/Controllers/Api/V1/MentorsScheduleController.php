<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\MentorSchedule;
use App\Space;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MentorsScheduleController extends Controller
{

	/**
	 * Get list of schedules
	 * @return json
	 */
	public function getIndex(Request $request)
	{
		$request->merge(['mentor_id' => Auth::guard('api')->id()]);

		$mentor_schedule = MentorSchedule::whereId(Auth::guard('api')->id())->get();

		return response()->json($mentor_schedule);
	}

	/**
	 * Get list of mentors
	 * @return json
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'space_id'   => 'required',
			'start_date' => 'required|date_format:d/m/Y H:i',
			'end_date'   => 'required|date_format:d/m/Y H:i',
		]);

		$space = Space::find($request->space_id);

		$mentor_schedule = new MentorSchedule;
		$mentor_schedule->mentor_id = $this->user->id;
		$mentor_schedule->space_id = $request->space_id;
		$mentor_schedule->start_date = Carbon::createFromFormat('d/m/Y H:i', $request->start_date, $space->timezone)->setTimezone('UTC');
		$mentor_schedule->end_date = Carbon::createFromFormat('d/m/Y H:i', $request->end_date, $space->timezone)->setTimezone('UTC');
		$mentor_schedule->save();

		return response()->json($mentor_schedule);
	}

}
