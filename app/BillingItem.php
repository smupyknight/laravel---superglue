<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotEnoughCreditException;
use App\CreditTransaction;

class BillingItem extends Model
{

	protected $dates = ['start_date', 'next_billing_date', 'end_date'];

	protected $guarded = [];

	public function space()
	{
		return $this->belongsTo('App\Space');
	}

	public function plan()
	{
		return $this->belongsTo('App\Plan');
	}

	public function office()
	{
		return $this->belongsTo('App\Office');
	}

	public function desk()
	{
		return $this->belongsTo('App\Desk');
	}

}
