<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Space;
use App\User;
use App\MentorSchedule;
use Carbon\Carbon;

class MentorsScheduleController extends Controller
{

	public function getIndex()
	{
		return view('pages.mentors-schedule-list')
		     ->with('schedules', MentorSchedule::paginate(25))
		     ->with('title', 'Mentor Schedules');
	}

	public function getMentor($user_id)
	{
		return view('pages.mentors-schedule-list')
		     ->with('schedules', MentorSchedule::where('mentor_id', $user_id)->paginate(25))
		     ->with('title', 'Mentor Schedules');
	}

	public function getCreate()
	{
		return view('pages.mentors-schedule-create')
		     ->with('spaces', Space::all())
		     ->with('mentors', User::whereType('Mentor')->get());
	}

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'mentor_id'  => 'required',
			'space_id'   => 'required',
			'start_date' => 'required|date_format:d/m/Y H:i',
			'end_date'   => 'required|date_format:d/m/Y H:i',
		]);

		$space = Space::find($request->space_id);

		$mentor_schedule = new MentorSchedule;
		$mentor_schedule->mentor_id = $request->mentor_id;
		$mentor_schedule->space_id = $request->space_id;
		$mentor_schedule->start_date = Carbon::createFromFormat('d/m/Y H:i', $request->start_date, $space->timezone)->setTimezone('UTC');
		$mentor_schedule->end_date = Carbon::createFromFormat('d/m/Y H:i', $request->end_date, $space->timezone)->setTimezone('UTC');
		$mentor_schedule->save();

		return redirect('/admin/mentors-schedule');
	}

	public function getEdit($schedule_id)
	{
		$mentor_schedule = MentorSchedule::findOrFail($schedule_id);

		return view('pages.mentors-schedule-edit')
		     ->with('spaces', Space::all())
		     ->with('mentors', User::whereType('Mentor')->get())
		     ->with('mentor_schedule', $mentor_schedule);
	}

	public function postEdit(Request $request, $schedule_id)
	{
		$this->validate($request, [
			'mentor_id'  => 'required',
			'space_id'   => 'required',
			'start_date' => 'required|date_format:d/m/Y H:i',
			'end_date'   => 'required|date_format:d/m/Y H:i',
		]);

		$space = Space::find($request->space_id);

		$mentor_schedule = MentorSchedule::findOrFail($schedule_id);
		$mentor_schedule->mentor_id = $request->mentor_id;
		$mentor_schedule->space_id = $request->space_id;
		$mentor_schedule->start_date = Carbon::createFromFormat('d/m/Y H:i', $request->start_date, $space->timezone)->setTimezone('UTC');
		$mentor_schedule->end_date = Carbon::createFromFormat('d/m/Y H:i', $request->end_date, $space->timezone)->setTimezone('UTC');
		$mentor_schedule->save();

		return redirect('/admin/mentors-schedule');
	}

	/**
	 * Delete mentor schedule
	 * @param  int $mentor_schedule_id
	 * @return redirect
	 */
	public function postDelete($mentor_schedule_id)
	{
		$mentor_schedule = MentorSchedule::findOrFail($mentor_schedule_id);
		$mentor_schedule->delete();

		return redirect('/admin/mentors-schedule');
	}

}
