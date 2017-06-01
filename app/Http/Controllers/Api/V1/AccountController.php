<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Storage;
use App\User;
use App\Plan;
use App\BillingItem;
use Carbon\Carbon;

class AccountController extends Controller
{

	public function getProfile()
	{
		$user = Auth::guard('api')->user();

		if (!$user) {
			abort(404);
		}

		$user->avatar = $user->getAvatarUrl();
		$user->membership_id = 1;
		$user->is_member = $user->isMember();
		$user->credit_available = $user->account->credit_balance;
		$user->valid_payment = (bool) $user->account->stripe_id;

		// Remove the account object from the results.
		unset($user->account);

		// Remove tokens
		unset($user->linkedin_token);
		unset($user->api_token);

		return response()->json($user);
	}

	public function postAvatar(Request $request)
	{
		$this->validate($request, [
			'avatar' => 'required|image',
		]);

		$this->user->setAvatar($request->file('avatar'));
	}

	public function postUpdateCreditCard(Request $request)
	{
		$this->validate($request, [
			'stripe_customer_token' => 'required',
			'card_brand'            => 'required',
			'card_last_four'        => 'required',
		]);

		$account = $this->user->account;
		$account->stripe_id = $request->stripe_customer_token;
		$account->card_brand = $request->card_brand;
		$account->card_last_four = $request->card_last_four;
		$account->save();
	}

	public function getPlans()
	{
		$plans = Plan::orderBy('name')->get();

		return response()->json($plans);
	}

	public function postAddMembership(Request $request)
	{
		$this->validate($request, [
			'plan_id' => 'required',
		]);

		$plan = Plan::findOrFail($request->plan_id);

		$item = new BillingItem;
		$item->account_id = $this->user->account_id;
		$item->plan_id = $request->plan_id;
		$item->name = 'Plan: '.$plan->name;
		$item->cost = $plan->cost;
		$item->num_credits = $plan->credit_per_renewal;
		$item->start_date = Carbon::now();
		$item->next_billing_date = Carbon::now();
		$item->save();

		return response()->json(['billingitem_id' => $item->id]);
	}

	public function postPurchaseCredit(Request $request, $account_id)
	{
		$this->validate($request, [
			'credits' => 'required|numeric',
		]);

		$account = $this->user->account;

		$item = new BillingItem;
		$item->account_id = $account_id;
		$item->name = 'Credit top-up: ' . $request->credits . ' credits';
		$item->cost = $request->cost;
		$item->start_date = Carbon::now();
		$item->next_billing_date = Carbon::now();
		$item->end_date = Carbon::now();
		$item->save();

		$account->debit(-$request->credits, 'Credit top-up');

		return response()->json(['billingitem_id' => $item->id]);
	}

}
