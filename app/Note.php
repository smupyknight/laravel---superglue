<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
	protected $dates = ['created_at'];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
