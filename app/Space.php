<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Space extends Model
{

	public function rooms()
	{
		return $this->hasMany('App\Room');
	}

	public function offices()
	{
		return $this->hasMany('App\Office');
	}

	public function desks()
	{
		return $this->hasMany('App\Desk');
	}

}
