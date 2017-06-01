<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Announcement;

class AnnouncementsController extends Controller
{

	public function getIndex()
	{
		$announcements = Announcement::paginate(25);

		return view('pages.public.announcements-list')
		     ->with('announcements', $announcements)
		     ->with('title', 'Announcements');
	}

}
