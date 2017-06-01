<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Payment;

class PaymentsController extends Controller
{

	/**
	 * Ajax call to add payment to invoice
	 * @param  Request $request
	 * @return null
	 */
	public function postAdd(Request $request)
	{
		$this->validate($request, [
			'invoice_id'   => 'required',
			'account_id'   => 'required',
			'amount'       => 'required|numeric',
			'method'       => 'required',
			'payment_date' => 'required|date_format:d/m/Y',
		]);

		$payment = new Payment;
		$payment->account_id = $request->account_id;
		$payment->invoice_id = $request->invoice_id;
		$payment->stripe_transaction_id = null;
		$payment->amount = $request->amount;
		$payment->method = $request->method;
		$payment->payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date);
		$payment->save();

		$payment->invoice->updateStatus();

		$this->addTimeline([
			'created_by' => $this->user->id,
			'account_id' => $request->account_id,
			'title'      => 'Added payment',
			'message'    => 'Added payment for invoice '.$request->invoice_id.' for $'.number_format($request->amount),
			'type'       => 'info',
		]);
	}

	/**
	 * Ajax call to edit payment details
	 * @param  Request $request
	 * @param  int	   $payment_id
	 * @return null
	 */
	public function postEdit(Request $request,$payment_id)
	{
		$this->validate($request, [
			'amount'       => 'required|numeric',
			'method'       => 'required',
			'payment_date' => 'required|date_format:d/m/Y',
		]);

		$payment = Payment::findOrFail($payment_id);
		$payment->amount = $request->amount;
		$payment->method = $request->method;
		$payment->payment_date = Carbon::createFromFormat('d/m/Y', $request->payment_date);
		$payment->save();

		$payment->invoice->updateStatus();
	}

	public function postDelete($payment_id)
	{
		$payment = Payment::findOrFail($payment_id);
		$invoice = $payment->invoice;
		$payment->delete();

		$invoice->updateStatus();
	}

}
