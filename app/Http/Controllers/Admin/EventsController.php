<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Event;
use DateTime;
use Storage;
use App\Http\Requests;
use App\Space;

class EventsController extends Controller
{

	 /**
	 * Show Event listing page
	 * @return view
	 */
	public function getIndex()
	{
		$events = Event::orderBy('id', 'desc')->paginate(25);

		return view('pages.events-list')
		     ->with('events', $events)
		     ->with('title', 'Events List');
	}

	 /**
	 * Show Event create page
	 * @return view
	 */
	public function getCreate(Request $request)
	{
		if ($request->copy) {
			$original = Event::find($request->copy);
		} else {
			$original = new Event;
		}

		$spaces = Space::orderBy('name', 'asc')->get();

		return view('pages.events-create')
		     ->with('title', 'Create Event')
		     ->with('spaces', $spaces)
		     ->with('original', $original);
	}

	 /**
	 * Save new event
	 * @return view
	 */
	public function postCreate(Request $request)
	{
		$this->validate($request, [
			'name'           => 'required',
			'description'    => 'required',
			'space_id'       => 'required',
			'location_other' => 'required_if:space_id,Other',
			'paid'           => 'required',
			'status'         => 'required',
			'ticket_link'    => 'url',
			'start_time'     => 'date_format:d/m/Y H:i:s',
			'finish_time'    => 'date_format:d/m/Y H:i:s',
		]);

		$event = new Event;
		$event->name = $request->get('name', '');
		$event->description = $request->get('description', '');

		if ($request->space_id == 'Other') {
			$event->space_id = null;
			$event->location_other = $request->location_other;
		} else {
			$event->space_id = $request->space_id;
			$event->location_other = '';
		}

		$event->paid = $request->get('paid', '');
		$event->status = $request->get('status', '');
		$event->ticket_link = $request->get('ticket_link', '');

		// These times are not timezone aware. They're stored as, say, 1pm UTC
		// which means 1pm in the location's timezone.
		if ($time = $request->get('start_time')) {
			$event->start_time = DateTime::createFromFormat('d/m/Y H:i:s', $time);
		}

		if ($time = $request->get('finish_time')) {
			$event->finish_time = DateTime::createFromFormat('d/m/Y H:i:s', $time);
		}

		$event->save();

		if ($request->hasfile('cover_photo')) {
			$event->cover_photo = $this->uploadCover($request, $event);
			$event->save();
		}

		return redirect('/admin/events');
	}

	private function uploadCover($request, $event)
	{
		$extension = $request->file('cover_photo')->getClientOriginalExtension();
		$unique_id = $event->id . '-' . substr(md5(microtime()), 0, 10);
		$filename = $unique_id . '.' . $extension;

		Storage::disk('public')->put('storage/event_covers/' . $filename, file_get_contents($request->file('cover_photo')));

		if ($event->cover_photo != '') {
			Storage::disk('public')->delete('storage/event_covers/'.$event->cover_photo);
		}

		return $filename;
	}

	/**
	 * Show Event view page
	 * @param  int $event_id
	 * @return view
	 */
	public function getView($event_id)
	{
		$event = Event::findOrFail($event_id);

		return view('pages.events-view')
		     ->with('event', $event)
		     ->with('title', 'View Event');
	}

	/**
	 * Show Event edit page
	 * @param  int $event_id
	 * @return view
	 */
	public function getEdit($event_id)
	{
		$event = Event::findOrFail($event_id);
		$spaces = Space::orderBy('name', 'asc')->get();

		return view('pages.events-edit')
		     ->with('event', $event)
		     ->with('spaces', $spaces)
		     ->with('title', 'Edit Event');
	}

	/**
	 * Update event
	 * @return view
	 */
	public function postEdit(Request $request,$event_id)
	{
		$this->validate($request, [
			'name'           => 'required',
			'description'    => 'required',
			'space_id'       => 'required',
			'location_other' => 'required_if:space_id,Other',
			'paid'           => 'required',
			'status'         => 'required',
			'ticket_link'    => 'url',
			'start_time'     => 'date_format:d/m/Y H:i:s',
			'finish_time'    => 'date_format:d/m/Y H:i:s',
		]);

		$event = Event::findOrFail($event_id);;
		$event->name = $request->get('name', '');
		$event->description = $request->get('description', '');

		if ($request->space_id == 'Other') {
			$event->space_id = null;
			$event->location_other = $request->location_other;
		} else {
			$event->space_id = $request->space_id;
			$event->location_other = '';
		}

		$event->paid = $request->get('paid', '');
		$event->status = $request->get('status', '');
		$event->ticket_link = $request->get('ticket_link', '');

		// These times are not timezone aware. They're stored as, say, 1pm UTC
		// which means 1pm in the location's timezone.
		if ($time = $request->get('start_time')) {
			$event->start_time = DateTime::createFromFormat('d/m/Y H:i:s', $time);
		}

		if ($time = $request->get('finish_time')) {
			$event->finish_time = DateTime::createFromFormat('d/m/Y H:i:s', $time);
		}

		$event->save();

		if ($request->hasfile('cover_photo')) {
			$event->cover_photo = $this->uploadCover($request, $event);
			$event->save();
		}

		return redirect('/admin/events');
	}

	/**
	 * Delete Event
	 * @param  int $event_id
	 * @return view
	 */
	public function getDelete($event_id)
	{
		Event::where('id', $event_id)->delete();

		return redirect('/admin/events');
	}

}
