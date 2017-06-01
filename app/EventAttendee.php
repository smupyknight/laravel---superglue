<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventAttendee extends Model
{

	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function event()
	{
		return $this->belongsTo('App\Event');
	}

}
