<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\Invoice;
use Auth;

class InvoicesController extends Controller
{

	public function __construct()
	{
		$this->middleware('admin', ['only' => ['postDelete']]);
	}

	public function getView($invoice_id)
	{
		$invoice = Invoice::findOrFail($invoice_id);

		if (!Auth::user()->isAdmin() && $invoice->account_id != Auth::user()->account_id) {
			abort(401);
		}

		return view('invoices.invoice')
		     ->with('invoice', $invoice);
	}

	public function getPdf($invoice_id)
	{
		$invoice = Invoice::findOrFail($invoice_id);

		if (!Auth::user()->isAdmin() && $invoice->account_id != Auth::user()->account_id) {
			abort(404);
		}

		$filename = sprintf('invoice-%05d.pdf', $invoice->id);

		return response($invoice->pdf(), 200)
		     ->header('Content-Type', 'application/pdf; filename="' . $filename . '"');
	}

	public function postDelete($invoice_id)
	{
		$invoice = Invoice::whereStatus('pending')->findOrFail($invoice_id);
		$invoice->items()->delete();
		$invoice->delete();
	}

}
