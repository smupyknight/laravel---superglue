<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{

	public function booking()
	{
		return $this->belongsTo('App\Booking');
	}

	public function room()
	{
		return $this->belongsTo('App\Room');
	}

}
