<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\PowerUp;

class PowerUpsController extends Controller
{

	/**
	 * Show PowerUps listing page
	 * @return view
	 */
	public function getIndex()
	{
		$powerups = PowerUp::orderBy('id', 'desc')->paginate(25);

		return view('pages.public.powerups-list')
			->with('powerups', $powerups)
			->with('title', 'PowerUps List');
	}

	/**
	 * Show PowerUp view page
	 * @return view
	 */
	public function getView($id)
	{
		$powerup = PowerUp::findOrFail($id);

		return view('pages.public.powerups-view')
			->with('powerup', $powerup)
			->with('title', 'View Powerup');
	}

}
