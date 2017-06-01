<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\HolidayPeriod;

class HolidaysController extends Controller
{

	/**
	 * Ajax call to add holiday period to account
	 * @param  Request $request
	 * @param  int  $account_id
	 * @return null
	 */
	public function postAdd(Request $request, $account_id)
	{
		$this->validate($request, [
			'start_date' => 'required|date_format:d/m/Y',
			'end_date'   => 'required|date_format:d/m/Y',
		]);

		$start = Carbon::createFromFormat('d/m/Y', $request->start_date);
		$end = Carbon::createFromFormat('d/m/Y', $request->end_date);

		$holiday = new HolidayPeriod;
		$holiday->account_id = $account_id;
		$holiday->start_date = $start;
		$holiday->end_date = $end;
		$holiday->save();
	}

	/**
	 * Ajax call to update holiday period to account
	 * @param  Request $request
	 * @param  int  $account_id
	 * @return null
	 */
	public function postEdit(Request $request,$holiday_id)
	{
		$this->validate($request, [
			'start_date' => 'required|date_format:d/m/Y',
			'end_date'   => 'required|date_format:d/m/Y',
		]);

		$start = Carbon::createFromFormat('d/m/Y', $request->start_date);
		$end = Carbon::createFromFormat('d/m/Y', $request->end_date);

		$holiday = HolidayPeriod::findOrFail($holiday_id);
		$holiday->start_date = $start;
		$holiday->end_date = $end;
		$holiday->save();
	}

	public function postDelete($holiday_id)
	{
		HolidayPeriod::where('id', $holiday_id)->delete();
	}

}
