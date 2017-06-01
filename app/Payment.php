<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
	protected $guarded = [];

	protected $dates = ['payment_date'];

	public function invoice()
	{
		return $this->belongsTo('App\Invoice');
	}

}
