<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
	protected $guarded = [];

	public function images()
	{
		return $this->hasMany('App\OfficeImage');
	}

	public function space()
	{
		return $this->belongsTo('App\Space');
	}

}
