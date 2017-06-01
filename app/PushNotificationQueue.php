<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PushNotificationQueue extends Model
{
	protected $table = 'push_notification_queue';

	public function user()
	{
		return $this->belongsTo('App\User');
	}

}
