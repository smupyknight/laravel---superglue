<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Space;
use App\Room;

class RoomsController extends Controller
{

	/**
	 * Show create room for space
	 * @param  int $space_id
	 * @return view
	 */
	public function getCreate($space_id)
	{
		$space = Space::findOrFail($space_id);

		return view('pages.rooms-create')
		     ->with('space', $space)
		     ->with('title', 'Create Room');
	}

	/**
	 * Handle create room for space
	 * @param  Request $request
	 * @param  int  $space_id
	 * @return redirect
	 */
	public function postCreate(Request $request, $space_id)
	{
		$this->validate($request, [
			'name'             => 'required',
			'capacity'         => 'required|numeric',
			'credits_per_hour' => 'required|numeric',
		]);

		$room = new Room;
		$room->space_id = $space_id;
		$room->name = $request->name;
		$room->description = $request->description;
		$room->capacity = $request->capacity;
		$room->credits_per_hour = $request->credits_per_hour;
		$room->save();

		return redirect('/admin/spaces/view/'.$space_id);
	}

	/**
	 * Show edit room page
	 * @param  int $room_id
	 * @return view
	 */
	public function getEdit($room_id)
	{
		$room = Room::findOrFail($room_id);

		return view('pages.rooms-edit')
		     ->with('room', $room)
		     ->with('title', 'Edit Room');
	}

	/**
	 * Handle editing of room
	 * @param  Request $request
	 * @param  int  $room_id
	 * @return redirect
	 */
	public function postEdit(Request $request,$room_id)
	{
		$this->validate($request, [
			'name'             => 'required',
			'capacity'         => 'required|numeric',
			'credits_per_hour' => 'required|numeric',
		]);

		$room = Room::find($room_id);
		$room->name = $request->name;
		$room->description = $request->description;
		$room->capacity = $request->capacity;
		$room->credits_per_hour = $request->credits_per_hour;
		$room->save();

		return redirect('/admin/spaces/view/'.$room->space_id);
	}

	/**
	 * Ajax call to delete room
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function postDelete(Request $request)
	{
		Room::where('id', $request->room_id)->delete();
	}

}
