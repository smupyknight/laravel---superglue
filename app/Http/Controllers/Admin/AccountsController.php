<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\BillingItem;
use App\Desk;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Invoice;
use App\Note;
use App\Office;
use App\Plan;
use App\Space;
use App\User;
use Carbon\Carbon;
use DB;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Storage;
use Stripe;
use App\Exceptions\ServiceValidationException;

class AccountsController extends Controller
{

	public function getIndex(Request $request)
	{
		if (isset($request->search)) {
			$accounts = Account::orWhere('name', 'like', '%'.$request->search.'%')
			                   ->orWhere('email', 'like', '%'.$request->search.'%')
			                   ->get();
		} else {
			$accounts = Account::get();
		}

		return view('pages.accounts-list')
		     ->with('title', 'Accounts List')
		     ->with('accounts', $accounts);
	}

	public function getEdit(Request $request, $account_id)
	{
		$account = Account::find($account_id);

		$spaces = Space::orderBy('name')->get();

		$xero_contacts = app('xero')->load('Accounting\Contact')
			->orderBy('name')
			->execute();

		return view('pages.accounts-edit')
		     ->with('request', $request)
		     ->with('account', $account)
		     ->with('xero_contacts', $xero_contacts)
		     ->with('spaces', $spaces);
	}

	public function postEdit(Request $request, $account_id)
	{
		$this->validate($request, [
			'name'       => 'required',
			'suburb'     => 'required_with:address',
			'state'      => 'required_with:address',
			'postcode'   => 'required_with:address|numeric',
			'country'    => 'required_with:address',
			'email'      => 'required|email',
			'start_date' => 'required|date_format:d/m/Y',
			'space_id'   => 'required|exists:spaces,id',
		]);

		$account = Account::find($account_id);
		$account->space_id = $request->space_id;
		$account->name = $request->name;
		$account->address = $request->address;
		$account->suburb = $request->suburb;
		$account->state = $request->state;
		$account->postcode = $request->postcode;
		$account->country = $request->country;
		$account->abn = preg_replace('/[^\d]+/', '', $request->abn);
		$account->email = $request->email;
		$account->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);

		if ($request->xero_contact_id) {
			$account->xero_contact_id = $request->xero_contact_id;
			$account->xero_contact_name = $request->xero_contact_name;
		} else {
			$account->xero_contact_id = null;
			$account->xero_contact_name = null;
		}

		$account->save();

		return redirect('/admin/accounts');
	}

	public function getView($account_id)
	{
		$account = Account::findOrFail($account_id);

		$invoices = DB::table('invoices AS i')
						->leftJoin('payments AS p', 'p.invoice_id', '=', 'i.id')
						->groupBy('i.id')
						->where('i.account_id', $account_id)
						->selectRaw('i.*, SUM(p.amount) AS amount_paid')
						->get();

		$spaces = Space::orderByRaw('id = ? DESC', [$account->space_id])->orderBy('name')->get();

		return view('pages.accounts-view')
		     ->with('title', 'View Account')
		     ->with('account', $account)
		     ->with('invoices', $invoices)
		     ->with('plans', Plan::all())
		     ->with('spaces', $spaces)
		     ->with('offices', Office::all())
		     ->with('desks', Desk::all());
	}

	public function postAssociateUser(Request $request, $account_id)
	{
		$this->validate($request, [
			'user_id' => 'required|exists:users,id',
		]);

		$user = User::find($request->user_id);

		if ($user->account_id) {
			return response()->json(['general' => ['This user is already in an account.']], 422);
		}

		$user->account_id = $account_id;
		$user->save();

		return response()->json(true);
	}

	public function postRemoveUser(Request $request, $account_id)
	{
		$this->validate($request, [
			'user_id' => 'required|exists:users,id',
		]);

		$user = User::find($request->user_id);

		if ($user->account_id != $account_id) {
			return response()->json(['general' => ['This user is not in this account.']], 422);
		}

		$user->account_id = null;
		$user->save();

		return response()->json(true);
	}

	public function getCreate()
	{
		$spaces = Space::orderBy('name')->get();

		$xero_contacts = app('xero')->load('Accounting\Contact')
			->orderBy('name')
			->execute();

		return view('pages.accounts-create')
		     ->with('spaces', $spaces)
		     ->with('xero_contacts', $xero_contacts)
		     ->with('title', 'Create Account');
	}

	public function postTerminate(Request $request, $account_id)
	{
		$this->validate($request, [
			'termination_date' => 'required|date_format:d/m/Y'
		]);

		$termination_date = Carbon::createFromFormat('d/m/Y', $request->termination_date);

		BillingItem::where('account_id', $account_id)
		           ->where(function($query) use ($termination_date) {
		               $query->where('end_date', null);
		               $query->orWhere('end_date', '>', $termination_date);
		           })
		           ->update([
		               'end_date' => $request->termination_date,
		           ]);
	}

	public function postDelete(Request $request, $account_id)
	{
		$account = Account::findOrFail($account_id);

		if ($account->users()->count()) {
			throw new ServiceValidationException('You cannot delete an account that has users assigned to it.');
		}

		$account->billingItems()->delete();
		$account->holidays()->delete();
		$account->files()->delete();

		foreach ($account->files() as $file) {
			Storage::delete($file->getLocalPath());
			$file->delete();
		}

		foreach ($account->invoices as $invoice) {
			$invoice->payments()->delete();
		}

		$account->invoices()->delete();
		$account->holidays()->delete();
		$account->creditTransactions()->delete();
		$account->notes()->delete();
		$account->timelineItems()->delete();

		$account->delete();
	}

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'       => 'required',
			'suburb'     => 'required_with:address',
			'state'      => 'required_with:address',
			'postcode'   => 'required_with:address|numeric',
			'country'    => 'required_with:address',
			'email'      => 'required|email',
			'space'      => 'required',
			'start_date' => 'required|date_format:d/m/Y',
		]);

		$account = new Account;
		$account->space_id = $request->space;
		$account->name = $request->name;
		$account->address = $request->address;
		$account->suburb = $request->suburb;
		$account->state = $request->state;
		$account->postcode = $request->postcode;
		$account->country = $request->country;
		$account->abn = preg_replace('/[^\d]+/', '', $request->abn);
		$account->email = $request->email;
		$account->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);

		if ($request->xero_contact_id) {
			$account->xero_contact_id = $request->xero_contact_id;
			$account->xero_contact_name = $request->xero_contact_name;
		}

		$account->save();

		return redirect('/admin/accounts/view/'.$account->id);
	}

	public function postCard(Request $request, $account_id)
	{
		$this->validate($request, [
			'card_token' => 'required',
		]);

		$account = Account::findOrFail($account_id);
		$account->setCard($request->card_token);
	}

	public function postAddCredit(Request $request,$account_id)
	{
		$this->validate($request, [
			'cost'    => 'numeric',
			'credits' => 'required|numeric',
		]);

		$account = Account::findOrFail($account_id);

		if ($request->cost) {
			$item = new BillingItem;
			$item->account_id = $account_id;
			$item->name = 'Credit top-up: ' . $request->credits . ' credits';
			$item->cost = $request->cost;
			$item->start_date = Carbon::now();
			$item->next_billing_date = Carbon::now();
			$item->end_date = Carbon::now();
			$item->save();
		}

		$account->debit(-$request->credits, 'Credit top-up');
	}

	public function postAddFile(Request $request, $account_id)
	{
		$this->validate($request, [
			'file' => 'required|mimes:pdf,doc,docx,rtf',
		]);

		$uploaded_file = $request->file;

		$db_file = \App\File::create([
			'account_id' => $account_id,
			'name'       => $uploaded_file->getClientOriginalName(),
			'size'       => $uploaded_file->getSize(),
		]);

		Storage::put($db_file->getLocalPath(), file_get_contents($uploaded_file));
	}

	public function getFile($account_id, $file_id)
	{
		$file = \App\File::where('account_id', $account_id)->findOrFail($file_id);

		return response(Storage::get($file->getLocalPath()))
		     ->header('Content-disposition', sprintf('attachment; filename="%s"', $file->name));
	}

	public function postDeleteFile($account_id, $file_id)
	{
		$file = \App\File::where('account_id', $account_id)->findOrFail($file_id);

		Storage::delete($file->getLocalPath());

		$file->delete();
	}

}
