<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Invoice;

class InvoicesController extends Controller
{

	public function getIndex(Request $request)
	{
		$query = Invoice::with('account');

		if ($request->search) {
			$query->whereHas('account', function ($query) use ($request) {
				$query->where(function($query) use ($request) {
					$query->where('name', 'LIKE', '%' . $request->search . '%');
					$query->orWhere('email', 'LIKE', '%' . $request->search . '%');
				});
			});
		}

		$invoices = $query->orderBy('id', 'desc')->paginate(25);

		return view('pages.admin.invoices-list')
		     ->with('invoices', $invoices);
	}

	public function getPdf($invoice_id)
	{
		$invoice = Invoice::findOrFail($invoice_id);

		$filename = sprintf('invoice-%05d.pdf', $invoice->id);

		return response($invoice->pdf(), 200)
		     ->header('Content-Type', 'application/pdf; filename="' . $filename . '"');
	}

}
