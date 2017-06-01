<?php
namespace App\Http\Controllers\Admin;

use App\Space;
use App\Office;
use App\OfficeImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests;
use Auth;

class OfficesController extends Controller
{

	/**
	 * Show create office for space
	 * @param  int $space_id
	 * @return view
	 */
	public function getCreate($space_id)
	{
		$space = Space::findOrFail($space_id);

		return view('pages.offices-create')
			 ->with('space', $space)
			 ->with('title', 'Create Office');
	}

	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'       => 'required',
			'length'     => 'required|numeric',
			'width'      => 'required|numeric',
			'capacity'   => 'required|numeric',
			'signup_fee' => 'required|numeric',
			'cost'       => 'required|numeric',
		]);

		$office = new Office;
		$office->space_id = $request->space_id;
		$office->name = $request->name;
		$office->features = $request->features;
		$office->length = $request->length;
		$office->width = $request->width;
		$office->capacity = $request->capacity;
		$office->signup_fee = $request->signup_fee;
		$office->cost = $request->cost;
		$office->save();

		return redirect('/admin/offices/view/'.$office->id);
	}

	/**
	 * Show office details
	 * @param  int $office_id
	 * @return view
	 */
	public function getView($office_id)
	{
		$office = Office::findOrFail($office_id);

		return view('pages.offices-view')
			 ->with('office', $office)
			 ->with('title', 'View Office');
	}

	/**
	 * Show edit office for space
	 * @param  int $office_id
	 * @return view
	 */
	public function getEdit($office_id)
	{
		$office = Office::findOrFail($office_id);

		return view('pages.offices-edit')
				->with('office', $office)
				->with('title', 'Edit Office');
	}

	public function postEdit(Request $request, $office_id)
	{
		$this->validate($request, [
			'name'       => 'required',
			'length'     => 'required|numeric',
			'width'      => 'required|numeric',
			'capacity'   => 'required|numeric',
			'signup_fee' => 'required|numeric',
			'cost'       => 'required|numeric',
		]);

		$office = Office::find($office_id);
		$office->name = $request->name;
		$office->features = $request->get('features', '');
		$office->length = $request->length;
		$office->width = $request->width;
		$office->capacity = $request->capacity;
		$office->signup_fee = $request->signup_fee;
		$office->cost = $request->cost;
		$office->save();

		return redirect('/admin/offices/view/'.$office->id);
	}

	public function postDelete($office_id)
	{
		Office::where('id', $office_id)->delete();

		return response()->json(true);
	}

	public function postAddImage(Request $request)
	{
		$this->validate($request, [
			'office_id' => 'required',
			'selected_file' => 'required'
		]);

		$office = Office::findOrFail($request->office_id);

		$office_image = new OfficeImage;
		$office_image->office_id = $office->id;
		$office_image->name = $request->file('selected_file')->getClientOriginalName();
		$uploader = $this->uploadOfficeImage($request, $office);

		if ($uploader['status'] == 1) {
			$office_image->file = $uploader['file'];
		}

		$office_image->save();
		return $office_image;
	}

	public function uploadOfficeImage($request, $office)
	{
		$result = ['status' => 0, 'message' => 'Error: Something went wrong. Please try again.'];
		$ext = $request->file('selected_file')->guessExtension();

		$file = 'office_'.$office->id.'_'.microtime(true).'.'.$ext;

		if ($request->file('selected_file')->move('storage/office_images/', $file)) {
			$result = ['status' => 1, 'message' => 'Image uploded successfully.', 'file' => $file];
		}

		return $result;
	}

}
