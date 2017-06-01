<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Knp\Snappy\Pdf;
use App\Payment;
use Log;
use Stripe;
use Carbon\Carbon;
use Mail;

class Invoice extends Model
{

	protected $dates = ['due_date'];

	protected $guarded = [];

	public function recalculateTotal()
	{
		$this->total = $this->items()->sum('cost') + $this->processing_fee;
		$this->save();
	}

	public function pay()
	{
		$account = $this->account;

		if ($account->card_brand == 'American Express') {
			$this->processing_fee = (($this->items()->sum('cost') + 0.3) / (1 - 0.0175)) - $this->items()->sum('cost');
			$this->recalculateTotal();
		}

		try {
			$charge = Stripe::charges()->create([
				'customer'             => $account->stripe_id,
				'currency'             => 'AUD',
				'amount'               => $this->total,
				'description'          => 'SuperGlue INV' . $this->id,
				'statement_descriptor' => 'SuperGlue INV' . $this->id,
				'receipt_email'        => $account->email,
			]);
		} catch (\Cartalyst\Stripe\Exception\NotFoundException $e) {
			Log::error($e->getMessage());
			return false;
		} catch (\Cartalyst\Stripe\Exception\MissingParameterException $e) {
			Log::error($e->getMessage());
			return false;
		} catch (\Cartalyst\Stripe\Exception\CardErrorException $e) {
			Log::error($e->getMessage());
			return false;
		}

		if (!$charge['paid']) {
			Log::warning('Payment for invoice #' . $this->id . ' failed: ' . $charge['failure_message']);
			return false;
		}

		$this->status = 'paid';
		$this->save();

		Log::info('Payment for invoice #' . $this->id . ' succeeded');

		Payment::create([
			'account_id'       => $this->account_id,
			'invoice_id'       => $this->id,
			'stripe_charge_id' => $charge['id'],
			'amount'           => $this->total,
			'method'           => 'credit card',
			'payment_date'     => Carbon::today('Australia/Brisbane'),
		]);

		// Apply credit
		foreach ($this->items as $item) {
			$account->credit_balance += $item->num_credits;
		}

		$account->save();

		dispatch(new \App\Jobs\NotifyXeroOfPayment($this));

		$this->emailInvoice();

		return true;
	}

	public function updateStatus()
	{
		$amount_paid = $this->payments()->selectRaw('sum(amount) as total')->first()->total;

		$this->status = $amount_paid >= $this->total ? 'paid' : 'pending';
		$this->save();
	}

	private function emailInvoice()
	{
		$data = [
			'invoice' => $this,
		];
		try {
			Mail::send('emails.invoice-email', $data, function ($mail) {
				$mail->from('developer@littletokyotwo.com');
				$mail->to($this->account->email);
				$mail->subject('Little Tokyo Two Invoice');
			});
		} catch (Exception $e) {
			Log::info('Sending invite email error : '.$e->getMessage());
		}
	}

	public function pdf()
	{
		$html = view('pdfs.invoice')
		      ->with('invoice', $this)
		      ->render();

		$snappy = new Pdf(env('WKHTMLTOPDF', 'wkhtmltopdf'));

		return $snappy->getOutputFromHtml($html);
	}

	public function items()
	{
		return $this->hasMany('App\InvoiceItem');
	}

	public function account()
	{
		return $this->belongsTo('App\Account');
	}

	public function payments()
	{
		return $this->hasMany('App\Payment');
	}

}
