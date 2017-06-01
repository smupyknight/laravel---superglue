<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ReferFriend;
use DateTime;

use App\Http\Requests;

class ReferFriendsController extends Controller
{

	 /**
	 * Show Refer Friends listing page
	 * @return view
	 */
	public function getIndex()
	{
		$refer_friends = ReferFriend::orderBy('id', 'desc')->paginate(25);

		return view('pages.refer-friends-list')
				->with('refer_friends', $refer_friends)
				->with('title', 'Friend Referrals List');
	}

}
