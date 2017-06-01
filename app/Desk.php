<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Desk extends Model
{

	protected $guarded = [];

	public function space()
	{
		return $this->belongsTo('App\Space');
	}

}
