<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MentorBooking extends Model
{

	public function schedule()
	{
		return $this->belongsTo('App\MentorSchedule');
	}

	public function member()
	{
		return $this->belongsTo('App\User');
	}

	public function mentor()
	{
		return $this->belongsTo('App\User');
	}

}
