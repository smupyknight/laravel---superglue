<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\WorkHistoryItem;
use DateTime;
use Carbon\Carbon;

class WorkHistoryController extends Controller
{

	public function getIndex()
	{
		$workhistories = WorkHistoryItem::where('user_id', Auth::user()->id)->paginate(25);

		return view('pages.public.workhistory-list')
		     ->with('title', 'Work History')
		     ->with('workhistories', $workhistories);
	}

	/**
	 * Save new work-history
	 * @return view
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'job_title'   => 'required',
			'company'     => 'required',
			'city'        => 'required',
			'start_date'  => 'required|date_format:Y-m-d',
			'end_date'    => 'required_without:still_working|date_format:Y-m-d',
			'description' => 'required',
		]);

		$workhistory = new WorkHistoryItem;
		$workhistory->user_id = Auth::user()->id;
		$workhistory->job_title = $request->get('job_title', '');
		$workhistory->company = $request->get('company', '');
		$workhistory->city = $request->get('city', '');
		$workhistory->start_date = Carbon::createFromFormat('Y-m-d', $request->get('start_date'));
		$workhistory->description = $request->get('description', '');

		if ($request->get('still_working')) {
			$workhistory->end_date = null;
		} else {
			$workhistory->end_date = Carbon::createFromFormat('Y-m-d', $request->get('end_date'));
		}

		$workhistory->save();

		$result = ['status' => 1, 'message' => 'Work History added.', 'error' => 0];

		return response()->json($result);
	}

	/**
	 * Edit work-history
	 * @return view
	 */
	public function postEdit(Request $request,$workhistory_id)
	{
		$this->validate($request, [
			'job_title'   => 'required',
			'company'     => 'required',
			'city'        => 'required',
			'start_date'  => 'required|date_format:Y-m-d',
			'end_date'    => 'required_without:still_working|date_format:Y-m-d',
			'description' => 'required',
		]);

		$workhistory = WorkHistoryItem::findOrFail($workhistory_id);
		$workhistory->job_title = $request->get('job_title', '');
		$workhistory->company = $request->get('company', '');
		$workhistory->city = $request->get('city', '');
		$workhistory->start_date = Carbon::createFromFormat('Y-m-d', $request->get('start_date'));
		$workhistory->description = $request->get('description', '');

		if ($request->get('still_working')) {
			$workhistory->end_date = null;
		} else {
			$workhistory->end_date = Carbon::createFromFormat('Y-m-d', $request->get('end_date'));
		}

		$workhistory->save();

		$result = ['status' => 1, 'message' => 'Work History updated.', 'error' => 0];

		return response()->json($result);
	}

	/**
	 * Delete Work History
	 * @param  int $workhistory_id
	 * @return view
	 */
	public function postDelete($workhistory_id)
	{
		WorkHistoryItem::where('id', $workhistory_id)->delete();

		return response()->json(true);
	}

}
