<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\NotEnoughCreditException;
use App\CreditTransaction;
use Stripe;

class Account extends Model
{

	protected $dates = ['renewal_date', 'start_date'];

	protected $guarded = [];

	/**
	 * Deducts credit from the account's available total and inserts a credit
	 * transaction record.
	 *
	 * Amount can be negative in order to grant credit.
	 */
	public function debit($amount, $description)
	{
		if ($amount == 0) {
			return;
		}

		if ($amount > $this->credit_balance) {
			throw new NotEnoughCreditException;
		}

		CreditTransaction::create([
			'account_id'    => $this->id,
			'description'   => $description,
			'amount'        => $amount,
		]);

		$this->credit_balance -= $amount;
		$this->save();
	}

	public function setCard($card_token)
	{
		if ($this->stripe_id) {
			$customer = Stripe::customers()->update($this->stripe_id, [
				'description' => $this->name,
				'email'       => $this->email,
				'metadata'    => ['account_id' => $this->id],
				'source'      => $card_token,
			]);
		} else {
			$customer = Stripe::customers()->create([
				'description' => $this->name,
				'email'       => $this->email,
				'metadata'    => ['account_id' => $this->id],
				'source'      => $card_token,
			]);
		}

		$this->stripe_id = $customer['id'];
		$this->card_brand = $customer['sources']['data'][0]['brand'];
		$this->card_last_four = $customer['sources']['data'][0]['last4'];
		$this->save();
	}

	public function getFormattedAbn()
	{
		return preg_replace('/^(\d{2})(\d{3})(\d{3})(\d*)$/', '$1 $2 $3 $4', $this->abn);
	}

	public function billingItems()
	{
		return $this->hasMany('App\BillingItem');
	}

	public function files()
	{
		return $this->hasMany('App\File');
	}

	public function invoices()
	{
		return $this->hasMany('App\Invoice');
	}

	public function users()
	{
		return $this->hasMany('App\User');
	}

	public function holidays()
	{
		return $this->hasMany('App\HolidayPeriod', 'account_id');
	}

	public function creditTransactions()
	{
		return $this->hasMany('App\CreditTransaction');
	}

	public function notes()
	{
		return $this->hasMany('App\Note');
	}

	public function space()
	{
		return $this->belongsTo('App\Space');
	}

	public function timelineItems()
	{
		return $this->hasMany('App\Timeline');
	}

}
