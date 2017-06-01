<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Space;
use App\Room;

class SpacesController extends Controller
{

	/**
	 * Get list of spaces
	 * @return json
	 */
	public function getIndex()
	{
		$spaces = Space::get();

		return response()->json($spaces);
	}

	/**
	 * Get list of rooms for space
	 * @param  int $space_id
	 * @return json
	 */
	public function getRooms($space_id)
	{
		$rooms = Room::orderBy('id', 'desc')->where('space_id', $space_id)->get();

		return response()->json($rooms);
	}

}
