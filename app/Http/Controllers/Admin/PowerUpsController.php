<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PowerUp;
use DateTime;
use Storage;

use App\Http\Requests;

class PowerUpsController extends Controller
{

	 /**
	 * Show PowerUps listing page
	 * @return view
	 */
	public function getIndex()
	{
		$powerups = PowerUp::orderBy('id', 'desc')->paginate(25);

		return view('pages.powerups-list')
			 ->with('powerups', $powerups)
			 ->with('title', 'PowerUps List');
	}

	/**
	 * Show PowerUp create page
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.powerups-create')
			 ->with('title', 'Create PowerUp');
	}

	 /**
	 * Save new powerup
	 * @return view
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'title'       => 'required',
			'description' => 'required',
			'link'        => 'url',
		]);

		$powerup = new PowerUp;
		$powerup->title = $request->get('title', '');
		$powerup->description = $request->get('description', '');
		$powerup->coupon_code = $request->get('coupon_code', '');
		$powerup->link = $request->get('link', '');
		$powerup->save();

		if ($request->file('image')) {
			$powerup->setImage($request->file('image'));
		}

		return redirect('/admin/powerups');
	}

	/**
	 * Show PowerUp view page
	 * @param  int $powerup_id
	 * @return view
	 */
	public function getView($powerup_id)
	{
		$powerup = PowerUp::findOrFail($powerup_id);

		return view('pages.powerups-view')
		     ->with('powerup', $powerup)
		     ->with('title', 'View PowerUp');
	}

	/**
	 * Show PowerUp edit page
	 * @param  int $powerup_id
	 * @return view
	 */
	public function getEdit($powerup_id)
	{
		$powerup = PowerUp::findOrFail($powerup_id);

		return view('pages.powerups-edit')
		     ->with('powerup', $powerup)
		     ->with('title', 'Edit PowerUp');
	}

	/**
	 * Update powerup
	 * @return view
	 */
	public function postEdit(Request $request,$powerup_id)
	{
		$this->validate($request, [
			'title'       => 'required',
			'description' => 'required',
			'link'        => 'required|url',
		]);

		$powerup = PowerUp::findOrFail($powerup_id);
		$powerup->title = $request->get('title', '');
		$powerup->description = $request->get('description', '');
		$powerup->coupon_code = $request->get('coupon_code', '');
		$powerup->link = $request->get('link', '');
		$powerup->save();

		if ($request->file('image')) {
			$powerup->setImage($request->file('image'));
		}

		return redirect('/admin/powerups');
	}

	/**
	 * Delete PowerUp
	 * @param  int $powerup_id
	 * @return view
	 */
	public function postDelete($powerup_id)
	{
		$powerup = PowerUp::findOrFail($powerup_id);
		$powerup->delete();

		return redirect('/admin/powerups');
	}

}
