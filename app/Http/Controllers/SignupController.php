<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Http\Requests;
use App\Space;
use App\Plan;
use App\User;
use App\Account;
use App\BillingItem;
use App\Invoice;
use App\InvoiceItem;
use App\App;
use DB;
use Auth;
use Illuminate\Support\Facades\Artisan;

class SignupController extends Controller
{

	public function getIndex(Request $request)
	{
		$plan = Plan::findOrFail($request->plan_id);

		$spaces = Space::all();

		return view('pages.frontend.signup')
			->with('spaces', $spaces)
			->with('plan', $plan);
	}

	public function postIndex(Request $request)
	{
		$this->validate($request, [
			'first_name'               => 'required',
			'last_name'                => 'required',
			'mobile_number'            => 'required|numeric',
			'email'                    => 'required|email|unique:users',
			'password'                 => 'required|confirmed',
			'password_confirmation'    => 'required',
			'street_address'           => 'required',
			'state'                    => 'required',
			'postcode'                 => 'required|numeric',
			'dob'                      => 'required|date_format:Y-m-d',
			'emergencyContactName'     => 'required',
			'emergencyContactMobile'   => 'required',
			'emergencyContactRelation' => 'required',
			'billing_company'          => 'required',
			'stripeToken'              => 'required',
		]);

		$plan = Plan::findOrFail($request->plan_id);

		DB::beginTransaction();

		$account = new Account;
		$account->name = $request->first_name . ' ' . $request->last_name;
		$account->email = $request->email;
		$account->address = $request->street_address;
		$account->state = $request->get('state', '');
		$account->postcode = $request->postcode;
		$account->billing_name = $request->billing_company;
		$account->abn = $request->company_abn;
		$account->space_id = $request->get('space');
		$account->save();

		$user = new User;
		$user->account_id = $account->id;
		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->dob = $request->dob;
		$user->company_name = $request->billing_company;
		$user->job_title = $request->get('job_title', '');
		$user->industry = $request->company_industry;
		$user->website = $request->company_website;
		$user->phone = $request->mobile_number;
		$user->address = $request->street_address;
		$user->state = $request->get('state', '');;
		$user->postcode = $request->postcode;
		$user->type = 'member';
		$user->password = bcrypt($request->password);
		$user->timezone = $request->get('timezone', 'Australia/Brisbane');
		$user->save();

		$today = Carbon::today('Australia/Brisbane');

		// Add membership billing item
		BillingItem::create([
			'account_id'        => $account->id,
			'plan_id'           => $plan->id,
			'name'              => 'Membership: ' . $plan->name,
			'cost'              => $plan->cost,
			'num_credits'       => $plan->credit_per_renewal,
			'start_date'        => $today,
			'end_date'          => null,
			'next_billing_date' => $today,
		]);

		// Add setup fee billing item
		BillingItem::create([
			'account_id'        => $account->id,
			'plan_id'           => null,
			'name'              => 'Setup: ' . $plan->name,
			'cost'              => $plan->setup_cost,
			'num_credits'       => 0,
			'start_date'        => $today,
			'end_date'          => null,
			'next_billing_date' => $today,
		]);

		$account->setCard($request->stripeToken);

		DB::commit();

		Artisan::call('billing');

		Auth::login($user);

		return redirect('/');
	}

}
