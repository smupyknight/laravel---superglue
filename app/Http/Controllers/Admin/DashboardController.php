<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Invoice;

class DashboardController extends Controller
{

	public function getIndex()
	{
		$invoices = Invoice::whereStatus('pending')->get();

		return view('pages.dashboard')
		     ->with('invoices', $invoices);
	}

}
