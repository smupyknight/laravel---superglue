<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Carbon\Carbon;
use DB;
use App\Account;
use App\User;
use App\Payment;
use App\Plan;
use App\BillingItem;
use App\Invoice;
use App\Space;
use App\CreditTransaction;

class AccountController extends Controller
{

	public function getIndex()
	{
		return redirect('/account/overview');
	}

	/**
	 * Show Invoices listing page
	 * @return view
	 */
	public function getInvoices(Request $request)
	{
		if (!Auth::user()->isAccountAdmin()) {
			abort(401);
		}

		$query = Invoice::where('account_id', Auth::user()->account_id);

		if ($request->search) {
			$query->where( function($query)	use ($request) {
				$query->orWhere('xero_invoice_number', 'like', '%' . $request->search . '%');
			});
		}

		$invoices = $query->orderBy('xero_invoice_number', 'desc')->paginate(25);

		return view('pages.public.my-invoices-list')
			->with('invoices', $invoices)
			->with('title', 'My Invoices');
	}

	public function getOverview()
	{
		if (!$this->user->isAccountAdmin()) {
			abort(403);
		}

		$invoices = Invoice::leftJoin('payments AS p', 'p.invoice_id', '=', 'invoices.id')
						->groupBy('invoices.id')
						->where('invoices.account_id', $this->user->account_id)
						->selectRaw('invoices.*, SUM(p.amount) AS amount_paid')
						->orderBy('invoices.id', 'desc')
						->get();

		$payments = Payment::where('account_id', $this->user->account_id)
						->orderBy('id', 'desc')
						->get();

		$billing_items = BillingItem::where('account_id', $this->user->account_id)
						->orderBy('id', 'desc')
						->get();

		$credit_transactions = CreditTransaction::where('account_id', $this->user->account_id)
						->orderBy('id', 'desc')
						->limit(10)
						->get();

		$plans = Plan::orderBy('name')->get();

		return view('pages.public.account-overview')
		     ->with('invoices', $invoices)
		     ->with('payments', $payments)
		     ->with('billing_items', $billing_items)
		     ->with('credit_transactions', $credit_transactions)
		     ->with('plans', $plans)
		     ->with('user', $this->user)
		     ->with('title', 'Account');
	}

	public function postAddCredit(Request $request)
	{
		$this->validate($request, [
			'credits' => 'required|numeric',
		]);

		$account = Account::findOrFail($account_id);

		$item = new BillingItem;
		$item->account_id = $account_id;
		$item->name = 'Credit top-up: ' . $request->credits . ' credits';
		$item->cost = $request->credits;
		$item->start_date = Carbon::now();
		$item->next_billing_date = Carbon::now();
		$item->end_date = Carbon::now();
		$item->save();

		$account->debit(-$request->credits, 'Credit top-up');
	}

	public function postCard(Request $request, $account_id)
	{
		$this->validate($request, [
			'card_token' => 'required',
		]);

		$account = Account::findOrFail($account_id);
		$account->setCard($request->card_token);
	}

	public function getEdit()
	{
		$user = Auth::user();

		return view('pages.public.account-edit')
		     ->with('user', $user)
		     ->with('title', 'Edit Profile');
	}

	/**
	 * Handle edit user data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postEdit(Request $request)
	{
		$this->validate($request, [
			'first_name' => 'required',
			'last_name'  => 'required',
			'email'      => 'required|email|unique:users,email,'.$this->user->id,
			'dob'        => 'date_format:d/m/Y'
		]);

		$dob = $request->dob ? Carbon::createFromFormat('d/m/Y', $request->dob)->format('Y-m-d') : null;

		$user = $this->user;

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->email = $request->email;
		$user->dob = $dob;
		$user->company_name = $request->get('company_name', '');
		$user->job_title = $request->get('job_title', '');
		$user->industry = $request->get('industry', '');
		$user->phone = $request->get('phone', '');
		$user->address = $request->get('address', '');
		$user->twitter_handle = $request->get('twitter_handle', '');
		$user->instagram_handle = $request->get('instagram_handle', '');
		$user->bio = $request->get('bio', '');
		$user->security_card_number = $request->get('security_card_number', '');

		if ($request->password) {
			$user->password = bcrypt($request->password);
		}

		$user->timezone = 'Australia/Brisbane';
		$user->save();

		return redirect('/account/edit')->with('success', 'Account successfully updated.');
	}

	public function getMembership()
	{
		$membership = Auth::user()->membership;

		if (!$membership) {
			return view('pages.public.account-membership-upsell');
		}

		return view('pages.public.account-membership')
				->with('membership', $membership);
	}

	public function getBookings()
	{
		$user = Auth::user();
		$spaces = Space::all();

		return view('pages.public.account-bookings')
		     ->with('user', $user)
		     ->with('spaces', $spaces)
		     ->with('title', 'My Bookings');
	}

}
