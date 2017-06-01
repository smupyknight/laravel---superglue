<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
	use SoftDeletes;

	protected $guarded = [];
	protected $dates = ['start_time', 'finish_time'];

	public function attendees()
	{
		return $this->hasMany('App\EventAttendee');
	}

	/**
	 * Get the cover url.
	 *
	 * @return string
	 */
	public function getCoverImageUrl()
	{
		if ($this->cover_photo != '') {
			return url('storage/event_covers') . '/' . $this->cover_photo;
		}
	}

	public function space()
	{
		return $this->belongsTo('App\Space');
	}

}
