<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class MentorSchedule extends Model
{

	protected $table = 'mentor_schedule';

	protected $dates = ['start_date','end_date'];

	public static function createScheduleInterval($schedules,$mentor_id,$date = null)
	{
		$array = [];
		$user = Auth::user() ? Auth::user() : Auth::guard('api')->user();

		foreach ($schedules as $schedule) {
			$start = $schedule->start_date;
			$end = $schedule->end_date->subMinutes(30);
			while ($start <= $end) {
				$check = MentorBooking::where('mentor_id', $mentor_id)
				                      ->where('start_date', $start)
				                      ->first();

				$data = [
					'start_time' => $start->format('c'),
					'end_time'   => $start->addMinutes(30)->format('c'),
					'is_booked'  => $check ? 1 : 0,
					'mentor_id'  => $mentor_id,
				];
				$checker = new Carbon($start);
				if ($date && $date == $checker->setTimezone($user->timezone)->format('Y-m-d')) {
					$array[] = $data;
				}

				if (!$date) {
					$array[] = $data;
				}
			}
		}

		return $array;
	}

	public function mentor()
	{
		return $this->belongsTo('App\User', 'mentor_id');
	}

	public function space()
	{
		return $this->belongsTo('App\Space');
	}

	public function bookings()
	{
		return $this->hasMany('App\MentorBooking', 'schedule_id');
	}

}
