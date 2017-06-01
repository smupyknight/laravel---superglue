<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Announcement;
use DateTime;
use DB;
use Auth;

class AnnouncementsController extends Controller
{

	/**
	 * Get All announcements
	 * @return json
	 */
	public function getList()
	{
		$announcements = Announcement::orderBy('id', 'desc')->paginate(25);

		$result = [];

		foreach ($announcements as $announcement) {
			$result[] = [
				'announcement_id' => $announcement->id,
				'user_id'         => $announcement->user_id,
				'user_name'       => ucwords($announcement->user->first_name.' '.$announcement->user->last_name),
				'title'           => $announcement->title,
				'content'         => $announcement->content,
				'link'            => $announcement->link,
				'created_at'      => $announcement->created_at,
			];
		}

		return response()->json($result);
	}

}
