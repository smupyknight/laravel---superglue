<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipItem extends Model
{

	protected $dates = ['expiry'];

	public function feature()
	{
		return $this->belongsTo('App\MembershipFeature');
	}

}
