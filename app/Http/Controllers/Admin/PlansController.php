<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Plan;

class PlansController extends Controller
{

	/**
	 * Show list of plans
	 * @return view
	 */
	public function getIndex()
	{
		$plans = Plan::orderBy('id', 'asc')->paginate(10);

		return view('pages.plans-list')
				->with('plans', $plans)
				->with('title', 'Plans');
	}

	/**
	 * Show create plan form
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.plans-form')
				->with('plan', new Plan)
				->with('submit_button', 'Create')
				->with('title', 'Create Plan');
	}

	/**
	 * Handle data for creating plan
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'               => 'required',
			'num_seats'          => 'required|numeric',
			'credit_per_renewal' => 'required|numeric',
			'cost'               => 'required|numeric',
			'setup_cost'         => 'required|numeric',
		]);

		$plan = new Plan;
		$plan->name = $request->name;
		$plan->num_seats = $request->num_seats;
		$plan->credit_per_renewal = $request->credit_per_renewal;
		$plan->cost = $request->cost;
		$plan->setup_cost = $request->setup_cost;
		$plan->save();

		return redirect('/admin/plans');
	}

	/**
	 * Show edit plan form
	 * @param  int $plan_id
	 * @return view
	 */
	public function getEdit($plan_id)
	{
		$plan = Plan::findOrFail($plan_id);

		return view('pages.plans-form')
				->with('plan', $plan)
				->with('submit_button', 'Update')
				->with('title', 'Edit Plan');
	}

	/**
	 * Handle data for editing plan
	 * @param  Request $request
	 * @param  int  $plan_id
	 * @return redirect
	 */
	public function postEdit(Request $request, $plan_id)
	{
		$this->validate($request, [
			'name'               => 'required',
			'num_seats'          => 'required|numeric',
			'credit_per_renewal' => 'required|numeric',
			'cost'               => 'required|numeric',
			'setup_cost'         => 'required|numeric',
		]);

		$plan = Plan::findOrFail($plan_id);
		$plan->name = $request->name;
		$plan->num_seats = $request->num_seats;
		$plan->credit_per_renewal = $request->credit_per_renewal;
		$plan->cost = $request->cost;
		$plan->setup_cost = $request->setup_cost;
		$plan->save();

		return redirect('/admin/plans');
	}

	/**
	 * Ajax request for deleting plan
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function postDelete(Request $request)
	{
		Plan::where('id', $request->plan_id)->delete();
	}

}
