<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
	protected $table = 'timeline';

	public function author()
	{
		return $this->belongsTo('App\User', 'created_by');
	}

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
