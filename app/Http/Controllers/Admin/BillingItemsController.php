<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\BillingItem;
use App\Plan;
use App\Office;
use App\Desk;
use Carbon\Carbon;

class BillingItemsController extends Controller
{

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'type'        => 'required',
			'name'        => 'required_if:type,other',
			'cost'        => 'numeric',
			'signup_fee'  => 'numeric',
			'num_credits' => 'numeric',
			'start_date'  => 'required|date_format:d/m/Y',
			'end_date'    => 'required_if:recurrence,limited|date_format:d/m/Y',
		]);

		$item = new BillingItem;
		$item->account_id = $request->account_id;
		$item->next_billing_date = Carbon::createFromFormat('d/m/Y', $request->start_date);

		$this->update($item, $request);

		if ($request->signup_fee > 0) {
			$this->addSignupFee($request);
		}
	}

	private function addSignupFee(Request $request)
	{
		$item = new BillingItem;
		$item->account_id = $request->account_id;
		$item->next_billing_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
		$item->cost = $request->signup_fee;
		$item->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
		$item->end_date = $item->start_date;
		$item->is_signup_fee = 1;

		if ($request->type == 'other') {
			$entity_type = 'other';
		} else {
			list($entity_type, $entity_id) = explode(':', $request->type);
		}

		switch ($entity_type) {
			case 'plan':
				$item->plan_id = $entity_id;
				$item->space_id = $request->input('space_id', null);
				$item->name = Plan::find($entity_id)->name . ' - Signup Fee';
				break;
			case 'office':
				$office = Office::findOrFail($entity_id);
				$item->office_id = $office->id;
				$item->space_id = $office->space_id;
				$item->name = 'Office: ' . $office->name . ' - Signup Fee';
				break;
			case 'desk':
				$desk = Desk::findOrFail($entity_id);
				$item->desk_id = $desk->id;
				$item->space_id = $desk->space_id;
				$item->name = 'Desk: ' . $desk->name . ' - Signup Fee';
				break;
			default:
				$item->space_id = $request->input('space_id', null);
				$item->name = $request->name . ' - Signup Fee';
		}

		$item->save();
	}

	public function postEdit(Request $request, $item_id)
	{
		$this->validate($request, [
			'type'        => 'required',
			'name'        => 'required_if:type,other',
			'cost'        => 'numeric',
			'num_credits' => 'numeric',
			'start_date'  => 'required|date_format:d/m/Y',
			'end_date'    => 'required_if:recurrence,limited|date_format:d/m/Y',
		]);

		$item = BillingItem::find($item_id);

		$this->update($item, $request);
	}

	private function update(BillingItem $item, Request $request)
	{
		$item->cost = $request->cost;
		$item->num_credits = $request->num_credits;
		$item->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);

		if ($request->type == 'other') {
			$entity_type = 'other';
		} else {
			list($entity_type, $entity_id) = explode(':', $request->type);
		}

		switch ($entity_type) {
			case 'plan':
				$item->plan_id = $entity_id;
				$item->space_id = $request->space_id;
				$item->name = 'Membership: ' . Plan::find($entity_id)->name;
				break;
			case 'office':
				$office = Office::findOrFail($entity_id);
				$item->office_id = $office->id;
				$item->space_id = $office->space_id;
				$item->name = 'Office: ' . $office->name;
				break;
			case 'desk':
				$desk = Desk::findOrFail($entity_id);
				$item->desk_id = $desk->id;
				$item->space_id = $desk->space_id;
				$item->name = 'Desk: ' . $desk->name;
				break;
			default:
				$item->space_id = null;
				$item->name = $request->name;
		}

		switch ($request->recurrence) {
			case 'none':
				$item->end_date = $item->start_date;
				break;
			case 'indefinite':
				$item->end_date = null;
				break;
			case 'limited':
				$item->end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);
		}

		$item->save();
	}

	public function postDelete($item_id)
	{
		BillingItem::whereId($item_id)->delete();
	}

}
