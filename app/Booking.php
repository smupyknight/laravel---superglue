<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class Booking extends Model
{

	protected $dates = ['start_date','end_date'];

	public function updateReminder()
	{
		DB::table('booking_reminders')->whereBookingId($this->id)->delete();

		if (!$this->reminder) {
			return;
		}

		$remind_at = $this->start_date->copy()->subMinutes($this->reminder);

		if ($remind_at > Carbon::now()) {
			DB::table('booking_reminders')->insert([
				'booking_id' => $this->id,
				'remind_at'  => $remind_at,
			]);
		}
	}

	public function rooms()
	{
		return $this->belongsToMany('App\Room', 'booking_rooms');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function bookedRooms()
	{
		return $this->hasMany('App\BookingRoom');
	}

}
