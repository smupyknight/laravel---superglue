<?php
namespace App\Http\Controllers\Admin;

use App\Space;
use App\Desk;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

class DesksController extends Controller
{

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'       => 'required',
			'signup_fee' => 'required',
			'cost'       => 'required|numeric',
		]);

		$desk = new Desk;
		$desk->space_id = $request->space_id;
		$desk->name = $request->name;
		$desk->signup_fee = $request->signup_fee;
		$desk->cost = $request->cost;
		$desk->save();

		return redirect('/admin/spaces/view/'.$request->space_id);
	}

	public function postEdit(Request $request, $desk_id)
	{
		$this->validate($request, [
			'name'       => 'required',
			'signup_fee' => 'required',
			'cost'       => 'required|numeric',
		]);

		$desk = Desk::find($desk_id);
		$desk->name = $request->name;
		$desk->signup_fee = $request->signup_fee;
		$desk->cost = $request->cost;
		$desk->save();

		return redirect('/admin/spaces/view/'.$desk->space_id);
	}

	public function postDelete($desk_id)
	{
		Desk::where('id', $desk_id)->delete();

		return response()->json(true);
	}

}
