<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use App\Space;
use App\Booking;
use App\BookingRoom;
use App\BookingReminder;
use App\Account;
use App\Room;
use Auth;
use App\Services\BookingsService;
use DB;

class BookingsController extends Controller
{

	public function getIndex()
	{
		$spaces = Space::all();

		$user_credit = Auth::user()->account->credit_balance;

		return view('pages.public.bookings-list')
		     ->with('spaces', $spaces)->with('user_credit', $user_credit)
		     ->with('title', 'Bookings');
	}

	public function postCreate(Request $request)
	{
		$service = new BookingsService;
		$booking = $service->create($request);

		return $booking;
	}

	public function getLoadBookings(Request $request)
	{
		$this->validate($request, [
			'start'    => 'required|date',
			'end'      => 'required|date',
			'room_ids' => 'required|string',
		]);

		$bookings = $this->getBookings($request);

		return response()->json($bookings);
	}

	public function getBookings(Request $request)
	{
		$bookings = DB::table('bookings as b')
						->join('booking_rooms AS b_r', 'b_r.booking_id', '=', 'b.id')
						->join('rooms AS r', 'r.id', '=', 'b_r.room_id')
						->join('spaces AS s', 's.id', '=', 'r.space_id')
						->selectRaw('s.timezone as room_timezone, b.id as booking_id, b.name as title, b.start_date as start, b.end_date as end, b.is_private, b.reminder, GROUP_CONCAT(b_r.room_id) as room_ids,  IF(b.start_date > NOW(), 1, 0) as is_future_data,  IF(b.user_id = '.Auth::user()->id.', 1, 0) as is_my_event')
						->groupBy('b.id');

		if (isset($request->start)) {
			$bookings->where('b.start_date', '>=', $request->start);
		}

		if (isset($request->end)) {
			$bookings->where('b.end_date', '<=', $request->end);
		}

		if (isset($request->room_ids)) {
			$bookings->whereIn('b_r.room_id', explode(',', $request->room_ids));
		}

		$bookings = $bookings->get();

		foreach ($bookings as $booking) {
			if ($booking->is_my_event == 1) {
				$booking->color = '#2f4050';
			}

			$booking->start = (new Carbon($booking->start))->setTimezone($booking->room_timezone)->format('D, d M Y H:i:s');
			$booking->end = (new Carbon($booking->end))->setTimezone($booking->room_timezone)->format('D, d M Y H:i:s');
		}

		return $bookings;
	}

	public function postEdit(Request $request, $booking_id)
	{
		$request->merge(['booking_id' => $booking_id]);

		$service = new BookingsService;
		$service->edit($request);
	}

	public function postDelete(Request $request, $booking_id)
	{
		$request->merge(['booking_id' => $booking_id]);

		$service = new BookingsService;
		$service->delete($request);
	}

}
