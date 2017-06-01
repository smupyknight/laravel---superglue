<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Account;
use App\BillingItem;
use Carbon\Carbon;

class ReportsController extends Controller
{

	public function getIndex()
	{
		return view('pages.reports-list')
		     ->with('title', 'Reports List');
	}

	public function getBillingReport()
	{
		$upcoming_start_dates = BillingItem::where('billing_items.start_date', '>=', Carbon::now())
		                                   ->join('accounts', 'billing_items.account_id', '=', 'accounts.id')
		                                   ->select('accounts.id', 'accounts.name', 'billing_items.start_date')
		                                   ->orderBy('billing_items.start_date', 'ASC')
		                                   ->take(15)
		                                   ->get();

		$upcoming_end_dates = BillingItem::where('billing_items.end_date', '>=', Carbon::now())
		                                 ->join('accounts', 'billing_items.account_id', '=', 'accounts.id')
		                                 ->select('accounts.id', 'accounts.name', 'billing_items.end_date')
		                                 ->orderBy('billing_items.end_date', 'ASC')
		                                 ->take(15)
		                                 ->get();

		return view('pages.reports-billing-report')
		     ->with('upcoming_start_dates', $upcoming_start_dates)
		     ->with('upcoming_end_dates', $upcoming_end_dates);
	}

}
