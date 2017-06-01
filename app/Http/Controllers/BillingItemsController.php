<?php

namespace App\Http\Controllers;

use App\BillingItem;
use App\Http\Requests;
use App\Plan;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillingItemsController extends Controller
{

	public function postAdd(Request $request)
	{
		$this->validate($request, [
			'plan'       => 'required',
			'start_date' => 'required|date_format:d/m/Y',
		]);

		$start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);

		$plan = Plan::findOrFail($request->plan);

		$item = new BillingItem;
		$item->account_id = $this->user->account_id;
		$item->plan_id = $plan->id;
		$item->name = 'Plan: ' . $plan->name;
		$item->cost = $plan->cost;
		$item->num_credits = $plan->credit_per_renewal;
		$item->start_date = $start_date;
		$item->next_billing_date = $start_date;
		$item->save();
	}

}
