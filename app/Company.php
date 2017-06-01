<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'companies';
	public $dates = ['date_started'];

	public function account()
	{
		return $this->hasOne('App\Account', 'id', 'account_id');
	}

}
