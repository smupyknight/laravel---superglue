<?php

namespace App;

use App\PushNotificationQueue;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Storage;

class User extends Authenticatable
{
	use SoftDeletes;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'first_name', 'last_name', 'email', 'password', 'timezone'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	protected $dates = [
		'last_login_at','deleted_at',
	];

	public function setAvatar(UploadedFile $file)
	{
		if ($this->avatar) {
			Storage::disk('public')->delete('avatars/' . $this->avatar);
		}

		$unique_id = $this->id . '-' . substr(md5(microtime()), 0, 10);
		$this->avatar = $unique_id . '.' . $file->getClientOriginalExtension();

		Storage::disk('public')->put('avatars/' . $this->avatar, file_get_contents($file));

		$this->save();
	}

	public function isAccountAdmin()
	{
		return $this->is_account_admin;
	}

	public function invitations()
	{
		return $this->hasMany('App\Invitation');
	}

	public function timeline()
	{
		return $this->hasMany('App\Timeline');
	}

	public function bookings()
	{
		return $this->hasMany('App\Booking');
	}

	/**
	 * Get devices associated with the user.
	 */
	public function devices()
	{
		return $this->hasMany('App\Device');
	}

	/**
	 * Get work history items associated with the user.
	 */
	public function workHistoryItems()
	{
		return $this->hasMany('App\WorkHistoryItem');
	}

	/**
	 * Get the company record associated with the user.
	 */
	public function company()
	{
		return $this->belongsTo('App\Company');
	}

	/**
	 * Get the account record associated with the user.
	 */
	public function account()
	{
		return $this->belongsTo('App\Account');
	}

	public function isAdmin()
	{
		return $this->type == 'Admin';
	}

	public function isMentor()
	{
		return $this->type == 'Mentor';
	}

	/**
	 * will determine if the account the user belongs to has an active membership in the billing items.
	 *
	 * @return bool
	 */
	public function isMember()
	{
		// check if any billing items is associated with account.
		return BillingItem::where('account_id', $this->account->id)
		                  ->where(function($query)
		                  {
		                      $query->where('end_date', '>=', Carbon::now())
		                          ->orWhereNull('end_date');
		                  })
		                  ->whereNotNull('plan_id')
		                  ->orWhereNotNull('desk_id')
		                  ->orWhereNotNull('office_id')
		                  ->exists();
	}

	/**
	 * Get the user's avatar as a url.
	 *
	 * @return string
	 */
	public function getAvatarUrl()
	{
		if ($this->avatar != '') {
			return url('/avatars') . '/' . $this->avatar;
		}
	}

	public function highFives()
	{
		return $this->hasMany('App\HighFive');
	}

	/**
	 * Send Push Notifications
	 *
	 * @return string
	 */
	public function sendPushNotification($title, $body)
	{
		foreach ($this->devices as $device) {
			$notification = new PushNotificationQueue;
			$notification->device_id = $device->id;
			$notification->title = $title;
			$notification->body = $body;
			$notification->save();
		}
	}

	public function mentorSchedules()
	{
		return $this->hasMany('App\MentorSchedule', 'mentor_id');
	}

}
