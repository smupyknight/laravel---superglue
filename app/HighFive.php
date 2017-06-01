<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HighFive extends Model
{

	public $timestamps = false;

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function sender()
	{
		return $this->belongsTo('App\User', 'created_by');
	}

}
