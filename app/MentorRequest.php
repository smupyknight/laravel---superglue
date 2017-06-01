<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MentorRequest extends Model
{

	public function member()
	{
		return $this->hasOne('App\User', 'id', 'member_id');
	}

	public function mentor()
	{
		return $this->hasOne('App\User', 'id', 'mentor_id');
	}

}
