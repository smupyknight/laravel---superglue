<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Announcement;
use DateTime;
use Auth;
use App\Http\Requests;
use App\PushNotificationQueue;

class AnnouncementsController extends Controller
{

	 /**
	 * Show Announcement listing page
	 * @return view
	 */
	public function getIndex()
	{
		$announcements = Announcement::orderBy('id', 'desc')->paginate(25);

		return view('pages.announcements-list')
		     ->with('announcements', $announcements)
		     ->with('title', 'Announcements List');
	}

	 /**
	 * Show Announcement create page
	 * @return view
	 */
	public function getCreate()
	{
		return view('pages.announcements-create')
		     ->with('title', 'Create Announcement');
	}

	 /**
	 * Save new announcement
	 * @return view
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'title'   => 'required',
			'content' => 'required',
		]);

		$announcement = new Announcement;
		$announcement->user_id = Auth::user()->id;
		$announcement->title = $request->get('title', '');
		$announcement->content = $request->get('content', '');
		$announcement->link = $request->get('link', '');
		$announcement->save();

		$users = \App\User::whereType('Member')->get();

		foreach ($users as $user) {
			$user->sendPushNotification($announcement->title, $announcement->content);
		}

		return redirect('/admin/announcements');
	}

	/**
	 * Show Announcement view page
	 * @param  int $announcement_id
	 * @return view
	 */
	public function getView($announcement_id)
	{
		$announcement = Announcement::findOrFail($announcement_id);

		return view('pages.announcements-view')
		     ->with('announcement', $announcement)
		     ->with('title', 'View Announcement');
	}

	/**
	 * Show Announcement edit page
	 * @param  int $announcement_id
	 * @return view
	 */
	public function getEdit($announcement_id)
	{
		$announcement = Announcement::findOrFail($announcement_id);

		return view('pages.announcements-edit')
		     ->with('announcement', $announcement)
		     ->with('title', 'Edit Announcement');
	}

	/**
	 * Update announcement
	 * @return view
	 */
	public function postEdit(Request $request,$announcement_id)
	{
		$this->validate($request, [
			'title'   => 'required',
			'content' => 'required',
		]);

		$announcement = Announcement::findOrFail($announcement_id);
		$announcement->user_id = Auth::user()->id;
		$announcement->title = $request->get('title', '');
		$announcement->content = $request->get('content', '');
		$announcement->link = $request->get('link', '');
		$announcement->save();

		return redirect('/admin/announcements');
	}

	/**
	 * Delete Announcement
	 * @param  int $announcement_id
	 * @return view
	 */
	public function postDelete($announcement_id)
	{
		$announcement = Announcement::findOrFail($announcement_id);
		$announcement->delete();

		return redirect('/admin/announcements');
	}

}
