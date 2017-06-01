<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Space;

class SpacesController extends Controller
{

	/**
	 * Show spaces list
	 * @return view
	 */
	public function getIndex()
	{
		$spaces = Space::orderBy('id', 'desc')->paginate(10);

		return view('pages.spaces-list')
		     ->with('spaces', $spaces)
		     ->with('title', 'Spaces list');
	}

	/**
	 * Show specific space page
	 * @param  int $space_id
	 * @return view
	 */
	public function getView($space_id)
	{
		$space = Space::findOrFail($space_id);

		return view('pages.spaces-view')
		     ->with('space', $space)
		     ->with('title', 'Manage Space : '.$space->name);
	}

	/**
	 * Show create space page
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.spaces-create')
		     ->with('title', 'Create Space');
	}

	/**
	 * Handle create space data
	 * @param  Request $request
	 * @return redirect
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'space_name' => 'required|unique:spaces,name',
			'address'    => 'required',
			'suburb'     => 'required',
			'state'      => 'required',
			'country'    => 'required',
			'timezone'   => 'required',
			'postcode'   => 'required|numeric|max:9999',
		]);

		$space = new Space;
		$space->name = $request->get('space_name', '');
		$space->address = $request->get('address', '');
		$space->suburb = $request->get('suburb', '');
		$space->postcode = $request->get('postcode', '');
		$space->state = $request->get('state', '');
		$space->country = $request->get('country', '');
		$space->timezone = $request->get('timezone', '');
		$space->save();

		return redirect('/admin/spaces');
	}

	/**
	 * Show edit space page
	 * @param  int $space_id
	 * @return view
	 */
	public function getEdit($space_id)
	{
		$space = Space::findOrFail($space_id);

		return view('pages.spaces-edit')
		     ->with('space', $space)
		     ->with('title', 'Edit Space');
	}

	/**
	 * Handle edit space data
	 * @param  Request $request
	 * @param  int     $space_id
	 * @return redirect
	 */
	public function postEdit(Request $request,$space_id)
	{
		$this->validate($request, [
			'space_name' => 'required|unique:spaces,name,'.$space_id,
			'address'    => 'required',
			'suburb'     => 'required',
			'state'      => 'required',
			'country'    => 'required',
			'timezone'   => 'required',
			'postcode'   => 'required|numeric|max:9999',
		]);

		$space = Space::find($space_id);
		$space->name = $request->get('space_name', '');
		$space->address = $request->get('address', '');
		$space->suburb = $request->get('suburb', '');
		$space->postcode = $request->get('postcode', '');
		$space->state = $request->get('state', '');
		$space->country = $request->get('country', '');
		$space->timezone = $request->get('timezone', '');
		$space->save();

		return redirect('/admin/spaces');
	}

	/**
	 * Delete space
	 * @param  int $space_id
	 * @return redirect
	 */
	public function getDelete($space_id)
	{
		Space::where('id', $space_id)->delete();

		return redirect('/admin/spaces');
	}

}
